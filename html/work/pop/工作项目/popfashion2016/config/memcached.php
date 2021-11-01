<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Memcached settings
| -------------------------------------------------------------------------
| Your Memcached servers can be specified below.
|
|	See: http://codeigniter.com/user_guide/libraries/caching.html#memcached
|
*/
switch (ENVIRONMENT) {
    case 'production':
        $config = [
            'server1' => [
                'hostname' => '192.168.0.222',
                'port' => '11211',
                'weight' => '1',
            ],
            'server2' => [
                'hostname' => '192.168.0.223',
                'port' => '11211',
                'weight' => '1',
            ],
            'server3' => [
                'hostname' => '192.168.0.224',
                'port' => '11211',
                'weight' => '1',
            ],
        ];
        break;
    case 'testing':
        $config = [
            'default' => [
                'hostname' => '192.168.3.186',
                'port' => '11211',
                'weight' => '1',
            ],
        ];
        break;
    case 'development':
    default:
        $config = [
            'default' => [
                'hostname' => '192.168.3.184',
                'port' => '11211',
                'weight' => '1',
            ],
        ];
        break;
}
