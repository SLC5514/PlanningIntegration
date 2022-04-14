<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| SPREAD
| -------------------------------------------------------------------------
*/
$_BASEENV_ = getenv('BASEENV');
if ( $_BASEENV_ == 1 ) {
    $config = array(
        'server' => '4803@192.168.0.222',
        'dir' => '/var/run/pop/asymsg',
    );
}
elseif ( $_BASEENV_ == 2 ) {
    $config = array(
        'server' => '4803@192.168.3.186',
        'dir' => '/var/run/pop/asymsg',
    );
}
else {
    $config = array(
        'server' => '4803@192.168.3.184',
        'dir' => '/var/run/pop/asymsg',
    );
}