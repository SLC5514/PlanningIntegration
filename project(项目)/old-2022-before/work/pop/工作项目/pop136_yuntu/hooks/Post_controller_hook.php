<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @todo    在你的控制器完全运行结束时执行。
 */
class Post_controller_hook
{

    public function __construct()
    {
        log_message('info', 'Hi, My name is Post_controller_hook!');
    }

    public function index()
    {

    }
}