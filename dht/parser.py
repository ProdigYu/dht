#!/usr/bin/env python
# -*- coding: utf-8 -*-

import sys
from pprint import pprint as pp

from .btdht.bencode import bdecode

class Parser(object):

    def __init__(self, file_path):
        self.file_path = file_path
        with open(self.file_path, 'rb') as f:
            self.metainfo = bdecode(f.read())

    # TODO: encoding, decoding; example: gb2312, big5
    def get_name(self):
        info = self.metainfo['info']
        return info.get('name.utf-8') or info.get('name', '')

    def get_files(self):
        info = self.metainfo['info']
        files = []
        if 'files' in info:
            for i in info['files']:
                file = {}
                file['length'] = i.get('length', 0)
                file['path'] = i.get('path.utf-8') or i.get('path', [])
                files.append(file)
        return files

    def get_length(self):
        info = self.metainfo['info']
        total_length = 0
        if 'files' in info:
            for i in info['files']:
                total_length += int(i.get('length', 0))
        elif 'length' in info:
            total_length = int(info.get('length', 0))
        return total_length

    def get_creation_date(self):
        return self.metainfo.get('creation date')

    # TODO: encoding, decoding
    def get_comment(self):
        metainfo = self.metainfo
        return metainfo.get('comment.utf-8') or metainfo.get('comment', '')


def main():
    if len(sys.argv) < 2:
        print 'Usage:'
        print 'python parser.py a_torrent_file'
        return
    torrent_file = sys.argv[1]
    parser = Parser(torrent_file)
    pp(parser.metainfo)
    print 'parsed info:'
    print parser.get_name()
    print parser.get_comment()
    print parser.get_files()
    print parser.get_length()
    print parser.get_creation_date()

if __name__ == '__main__':
    main()
