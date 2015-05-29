#!/usr/bin/env python
# -*- coding: utf-8 -*-

from socket import gethostname

def get_db_config(sub_key='beauty_dht'):
    return get_spec_config(config['database'], 'production', sub_key)

def get_redis_config(sub_key='infohash'):
    return get_spec_config(config['redis'], 'production', sub_key)

# 根据不同机器获取不同配置
def get_spec_config(dict_config, default_key, sub_key=None):
    hostname = gethostname()
    if dict_config.has_key(hostname):
        get_config = dict_config[hostname]
    else:
        get_config = dict_config[default_key]
    if sub_key:
        if not get_config.has_key(sub_key):
            raise Exception('{} does not exist'.format(sub_key))
        return get_config[sub_key]
    else:
        return get_config

config = {
    "database": {
        "production": {
            "beauty_dht": {
                'host': 'localhost',
                'port': 3306,
                'db': 'beauty_dht',
                'user': 'beauty_dht',
                'passwd': 'beauty_dht_spt',
                'charset': 'utf8'
            }
        },
        "shipengtaodeMacBook-Pro.local": {
            "beauty_dht": {
                'host': 'localhost',
                'port': 3306,
                'db': 'beauty_dht',
                'user': 'root',
                'passwd': 'password',
                'charset': 'utf8'
            }
        }
    },
    "redis": {
        "production": {
            "infohash": {
                'host': 'localhost',
                'port': 6379,
            }
        },
        "shipengtaodeMacBook-Pro.local": {
            "infohash": {
                'host': 'localhost',
                'port': 6379,
            }
        }
    },
    "infohash_key": "infohash",
    "worker_sleep": 5, # 当 redis 为空时, sleep 多长时间
}

__all__ = ['config', 'get_db_config', 'get_redis_config']
