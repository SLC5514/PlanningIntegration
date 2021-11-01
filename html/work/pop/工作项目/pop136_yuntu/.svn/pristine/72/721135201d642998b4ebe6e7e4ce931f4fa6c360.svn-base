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
$_BASEENV_ = getenv('BASEENV');
if ( $_BASEENV_ == 1 ) {
    $config = array(
        'socket_type' => 'tcp',
        'host' => '192.168.0.156',
        'password' => NULL,
        'port' => 6379,
        'timeout' => 5,
        'pconnect' => FALSE,
    );
}
elseif ( $_BASEENV_ == 2 ) {
    $config = array(
        'socket_type' => 'tcp',
        'host' => '192.168.3.253',
        'password' => NULL,
        'port' => 6379,
        'timeout' => 5,
        'pconnect' => FALSE,
    );
}
else {
    $config = array(
        'socket_type' => 'tcp',
        'host' => '192.168.3.253',
        'password' => NULL,
        'port' => 6379,
        'timeout' => 5,
        'pconnect' => FALSE,
    );
}
