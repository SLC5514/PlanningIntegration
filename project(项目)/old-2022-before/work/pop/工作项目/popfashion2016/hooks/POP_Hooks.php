<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 所有钩子的父类
 */
class POP_Hooks extends CI_Hooks
{
    protected $ci;
    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
        $this->ci = &get_instance();
        log_message('info', 'Hi, My name is POP_Hooks!');
    }

}