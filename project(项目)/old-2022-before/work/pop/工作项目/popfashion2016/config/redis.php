<?php
defined('BASEPATH') OR exit('No direct script access allowed');

switch (ENVIRONMENT) {
    case 'production':
        $config = [
            'redis_host' => '192.168.0.156',
            'redis_port' => 6379,
            'redis_dbid' => 15,// 默认选择库
            'redis_password' => '',
            'redis_timeout' => 5,
            'redis_pconnect' => false,
        ];
        break;
    case 'testing':
    case 'development':
    default:
        $config = [
            'redis_host' => '192.168.3.253',
            'redis_port' => 6379,
            'redis_dbid' => 15,// 默认选择库
            'redis_password' => '',
            'redis_timeout' => 5,
            'redis_pconnect' => false,
        ];
        break;
}
