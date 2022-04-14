<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| MongoDB settings
| -------------------------------------------------------------------------
| Your MongoDB servers can be specified below.
|
*/


switch (ENVIRONMENT) {
    case 'production':
        $config = [
            'host' => '192.168.0.10',
            'port' => 27017,
            'db' => 'pop006',
            'user' => 'root',
            'pass' => 'p0p!#^OK168',
            /*
             * Defaults to FALSE. If FALSE, the program continues executing without waiting for a database response.
             * If TRUE, the program will wait for the database response and throw a MongoCursorException if the update did not succeed.
            */
            'query_safety' => TRUE,
            'db_flag' => TRUE,    //If running in auth mode and the user does not have global read/write then set this to true
        ];
        break;
    case 'testing':
    case 'development':
    default:
        $config = [
            'host' => '192.168.3.184',
            'port' => 27017,
            'db' => 'pop006',
            'user' => 'pop006',
            'pass' => '123',
            'query_safety' => TRUE,
            'db_flag' => TRUE,
        ];
        break;
}
