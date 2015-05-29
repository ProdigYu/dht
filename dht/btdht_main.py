#!/usr/bin/env python
# -*- coding: utf-8 -*-

import sys
import logging
import time
from argparse import ArgumentParser

from .btdht import DHT

if __name__ == "__main__":

    arg_parser = ArgumentParser()
    arg_parser.add_argument("-p", dest="port", type=int, help=u"from port; default 6900")
    arg_parser.add_argument("-n", dest="thread_num", type=int, help=u"thread number; default 1")
    arg_parser.add_argument("-t", dest="work_time", type=int, help=u"work time(second); default 3600")
    args = arg_parser.parse_args()

    from_port = args.port or 6900
    thread_num = args.thread_num or 1
    work_time = args.work_time or 3600

    # Enable logging
    loglevel = logging.DEBUG
    formatter = logging.Formatter("[%(levelname)s@%(created)s] %(message)s")
    stdout_handler = logging.StreamHandler()
    stdout_handler.setFormatter(formatter)
    logging.getLogger("btdht").setLevel(loglevel)
    logging.getLogger("btdht").addHandler(stdout_handler)

    logger = logging.getLogger(__name__)
    logger.setLevel(loglevel)
    logger.addHandler(stdout_handler)

    # # threads pool
    # threads = []
    # for i in xrange(thread_num):
    #     port = from_port + i
        # print "thread {}".format(str(thread_num))
    port = from_port
    logger.debug('binding to %s:%d' %('0.0.0.0', port))
    dht = DHT(host='0.0.0.0', port=port)
    dht.start()

    # Boostrap it
    dht.bootstrap('router.bittorrent.com', 6881)

    CurrentMagnet = "746385fe32b268d513d068f22c53c46d2eb34a5c"
    # CurrentMagnet = "4CDE5B50A8930315B479931F6872A3DB59575366"

    # Find me peers for that torrent hashes
    dht.ht.add_hash(CurrentMagnet.decode("hex"))

        # threads.append(dht)

        # time.sleep(2)

    time.sleep(work_time)

    for i in threads:
        i.stop()
        i.join()
        i.rt.nodes.clear()
        i.ht.hashes.clear()
        print 'finish thread'+i.name
