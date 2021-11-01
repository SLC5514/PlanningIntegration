<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 在你的控制器调用之前执行，所有的基础类都已加载，路由和安全检查也已经完成。
 */
class Pre_controller_hook extends POP_Hooks
{

    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        log_message('info', 'Hi, My name is Pre_controller_hook!');
    }

    public function index()
    {

    }
}