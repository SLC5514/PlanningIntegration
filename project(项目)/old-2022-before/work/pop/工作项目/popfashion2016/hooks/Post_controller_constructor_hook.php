<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 在你的控制器实例化之后立即执行，控制器的任何方法都还尚未调用。
 */
class Post_controller_constructor_hook extends POP_Hooks
{

    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
        log_message('info', 'Hi, My name is Post_controller_constructor_hook!');
    }
}