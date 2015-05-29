#!/usr/bin/env python
# -*- coding: utf-8 -*-

import sys
import os
import traceback

import requests

def save(filename, content):
    with open(filename, 'wb') as f:
        f.write(content)
        return filename

def download_torrent(infohash, directory=None):
    try:
        torrent = RequestTorrentFile.from_torcache_net(infohash)
        if not torrent:
            raise Exception('download torrent file error')

        directory = directory or \
            os.path.join(
                os.path.dirname(os.path.abspath(__file__)),
                'torrents')
        save_file = os.path.join(
            directory,
            '{}.torrent'.format(infohash.upper())
        )
        save(save_file, torrent)
    except Exception,e:
        traceback.print_exc()
        return None
    return save_file

class RequestTorrentFile:
    @staticmethod
    def from_zoink_it(infohash):
        url = "https://zoink.it/torrent/{}.torrent".format(infohash.upper())
        return RequestTorrentFile.request(url)

    @staticmethod
    def from_torrage_com(infohash):
        url = "http://torrage.com/torrent/{}.torrent".format(infohash.upper())
        return RequestTorrentFile.request(url)

    @staticmethod
    def from_torcache_net(infohash):
        url = "http://torcache.net/torrent/{}.torrent".format(infohash.upper())
        return RequestTorrentFile.request(url)

    @staticmethod
    def from_bt_box(infohash):
        url = "http://bt.box.n0808.com/{f_tag}/{l_tag}/{hash}.torrent".format(
            f_tag = infohash[:2].upper(),
            l_tag = infohash[-2].upper(),
            hash = infohash.upper()
        )
        return RequestTorrentFile.request(url)

    @staticmethod
    def request(url):
        try:
            ret = requests.get(url, timeout=30)
            if ret.status_code != 200:
                return None
            if ret.ok and ret.content:
                return ret.content
            else:
                return None
        except:
            traceback.print_exc()
            return None

__all__ = ['download_torrent', 'RequestTorrentFile']

def main():
    if len(sys.argv) < 2:
        print 'Usage:'
        print 'python download_torrent.py infohash'
        return
    infohash = sys.argv[1]
    print 'downloading...'
    download_file = download_torrent(infohash)
    print 'download done. file path: %s' %download_file

if __name__=="__main__":
    main()
