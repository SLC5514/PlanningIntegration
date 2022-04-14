<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 在系统执行的早期调用，这个时候只有 基准测试类 和 钩子类 被加载了， 还没有执行到路由或其他的流程。
 */
class Pre_system_hook extends POP_Hooks
{

    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
        log_message('info', 'Hi, My name is Pre_system_hook!');
    }

    public function index()
    {

    }
}