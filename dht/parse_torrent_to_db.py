#!/usr/bin/env python
# -*- coding: utf-8 -*-

import sys
import os
import time
import datetime
import threading
from argparse import ArgumentParser
import traceback

import redis
import MySQLdb as mysqldb

from .config import *
from .parser import Parser
from .download_torrent import download_torrent

def get_db_instance():
    con = mysqldb.connect(**get_db_config())
    cursor = con.cursor(cursorclass=mysqldb.cursors.DictCursor)
    return con,cursor

def get_redis_instance():
    redis_instance = redis.StrictRedis(**get_redis_config())
    return redis_instance

def pull_infohash(min_id=None, limit=100):
    con,cursor = get_db_instance()

    sql = "select * from infohash where name=''"
    if min_id:
        sql += " and id>%s"
    sql += " limit %s"
    if sql.find('and id') > -1:
        cursor.execute(sql, (min_id, limit))
    else:
        cursor.execute(sql, (limit,))
    pull = cursor.fetchall()
    cursor.close()
    con.close()
    return pull

def parse_torrent(torrent_file, delete=True):
    # torrent_file maybe not valid
    try:
        parser = Parser(torrent_file)
    except:
        parser = None
    if delete:
        # maybe not exist?
        try:
            os.unlink(torrent_file)
        except:
            traceback.print_exc()
    return parser

def write_db(infohash, torrent_file):
    con,cursor = get_db_instance()

    sql = "select * from infohash where hash=%s"
    cursor.execute(sql, (infohash,))
    infohash_db = cursor.fetchone()

    if not infohash_db:
        return None
    # 不再解析以解析过的
    if infohash_db['name'] and infohash_db['total_size']:
        return None
    if not os.path.exists(torrent_file):
        return None

    # parse torrent file
    parser = parse_torrent(torrent_file)
    if not parser:
        return None

    name = parser.get_name()
    total_size = parser.get_length()
    files = parser.get_files()
    creation_date = parser.get_creation_date()

    if creation_date:
        try:
            creation_date = str(datetime.datetime.fromtimestamp(creation_date).\
                strftime('%Y-%m-%d %H:%M:%S'))
        except ValueError,e:  # year is out of range? maybe
            creation_date = '0000-00-00 00:00:00'
    else:
        creation_date = '0000-00-00 00:00:00'

    update_time = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')

    sql = "update infohash set \
                name=%s, total_size=%s, \
                creation_date=%s, update_time=%s \
                where hash=%s"
    cursor.execute(sql, (name, total_size, creation_date, update_time, infohash))

    # insert files info to database
    for file in files:
        length = file.get('length')
        filepath = '/'.join(file.get('path'))

        sql = "insert into filelist(infohash_id, filepath, length) \
            values(%s, %s, %s)"
        cursor.execute(sql, (infohash_db['id'], filepath, length))

    con.commit()
    cursor.close()
    con.close()

class Worker(threading.Thread):
    def __init__(self):
        threading.Thread.__init__(self)
        self.last_infohash_id_lock = threading.RLock()
        self.pull_lock = threading.RLock()
        self.stop = False

    def run(self):
        global last_infohash_id

        while True:
            if self.stop:
                break

            with self.pull_lock:
                infohash = redis_instance.lpop(redis_parse_list_key)
                if not infohash:
                    # clear redis set
                    self.clear_redis_set()

                    # read database
                    infohashes = pull_infohash(min_id=last_infohash_id)
                    if not infohashes:
                        print 'sleeping...'
                        time.sleep(30)
                        # parse from beginning
                        last_infohash_id = None
                        continue

                    # add to redis
                    for i in infohashes:
                        if redis_instance.sismember(redis_parse_set_key, i['id']):
                            continue
                        redis_instance.rpush(redis_parse_list_key, str(i['id'])+'-'+str(i['hash']))

                    del infohashes
                    continue

            infohash_id,infohash_hash = infohash.split('-')

            with self.last_infohash_id_lock:
                last_infohash_id = infohash_id

            print 'downloading, id: %s, infohash: %s' %(infohash_id, infohash_hash)
            torrent_file = download_torrent(infohash_hash)
            if not torrent_file:
                print 'download failed'
                continue
            write_db(infohash_hash, torrent_file)

    def clear_redis_set(self):
        while True:
            pop_set = redis_instance.spop(redis_parse_set_key)
            if not pop_set:
                break

# redis 待解析 list
redis_parse_list_key = 'parse_torrent_hashlist'
# for list uniqueness when multiprocess
redis_parse_set_key = 'parse_torrent_hashset'
# thread number
thread_num = 1

last_infohash_id = None
redis_instance = get_redis_instance()

def main():
    global last_infohash_id
    global thread_num

    arg_parser = ArgumentParser()
    arg_parser.add_argument('-m', dest="last_infohash_id", help="mix infohash.id")
    arg_parser.add_argument('-n', dest="thread_num", type=int, help="thread number; default=1")
    args = arg_parser.parse_args()

    last_infohash_id = args.last_infohash_id or last_infohash_id
    thread_num = args.thread_num or thread_num

    threads = []
    for i in xrange(thread_num):
        thread = Worker()
        thread.start()
        threads.append(thread)

    for i in threads:
        i.join()

if __name__ == '__main__':
    main()
