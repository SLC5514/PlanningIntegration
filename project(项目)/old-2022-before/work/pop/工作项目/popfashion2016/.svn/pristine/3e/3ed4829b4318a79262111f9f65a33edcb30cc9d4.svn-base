<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 在你的控制器完全运行结束时执行。
 */
class Post_controller_hook extends POP_Hooks
{

    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
        log_message('info', 'Hi, My name is Post_controller_hook!');
    }

    public function index()
    {
        if (is_object($this->ci->db)) {
            $this->ci->db->close();
            log_message('info', 'Hi, My name is Post_controller_hook! close db_connect');
        }
    }
}