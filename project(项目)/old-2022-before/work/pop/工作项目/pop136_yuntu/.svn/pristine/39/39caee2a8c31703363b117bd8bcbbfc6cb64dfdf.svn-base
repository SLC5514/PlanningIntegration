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
        'server1' => array(
            'hostname' => '192.168.0.222',
            'port'     => '11211',
            'weight'   => '1',
        ),
        'server2' => array(
            'hostname' => '192.168.0.223',
            'port'     => '11211',
            'weight'   => '1',
        ),
        'server3' => array(
            'hostname' => '192.168.0.224',
            'port'     => '11211',
            'weight'   => '1',
        ),
    );
}
elseif ( $_BASEENV_ == 2 ) {
    $config = array(
        'default' => array(
            'hostname' => '192.168.3.186',
            'port'     => '11211',
            'weight'   => '1',
        ),
    );
}
else {
    $config = array(
        'default' => array(
            'hostname' => '192.168.3.184',
            'port'     => '11211',
            'weight'   => '1',
        ),
    );
}
