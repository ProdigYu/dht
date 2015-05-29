#!/usr/bin/env python
# -*- coding: utf-8 -*-

from __future__ import division

import time
import datetime

import redis
import MySQLdb as mysqldb

from .config import *

def get_redis_instance():
	return redis.StrictRedis(**get_redis_config())

def get_db_instance():
	con = mysqldb.connect(**get_db_config())
	cursor = con.cursor(cursorclass=mysqldb.cursors.DictCursor)
	return con,cursor

redis_instance = get_redis_instance()
con,cursor = get_db_instance()

def worker():
	infohash = redis_instance.spop(config['infohash_key'])
	if not infohash:
		return 'empty'
	exist_sql = "select * from infohash where hash=%s"
	cursor.execute(exist_sql, (infohash,))
	if cursor.fetchone():
		return 'exist'
	sql = "insert into infohash(hash, create_time, update_time) \
		values(%s, %s, %s)"
	now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
	cursor.execute(sql, (infohash, now, now))
	con.commit()
	return 'success'

def main():
	progress = 0
	duplicate = 0
	while True:
		ret = worker()
		if ret and ret == 'empty':
			time.sleep(config['worker_sleep'])
			continue
		elif ret and ret == 'exist':
			duplicate += 1
		progress += 1
		if progress%10000 == 0:
			print 'progress: %d' %progress
		if duplicate%10000 == 0:
			print '\n'
			print '| ' + '*'*50
			print '| duplicate: %d, %.2f' %(duplicate, duplicate/progress)
			print '| ' + '*'*50
			print '\n'

if __name__ == '__main__':
	main()
