<?php

/**
 * 专题控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/14
 * Time: 15:16
 */
class Topic extends POP_Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function index( $topic = '' ) {
        $this->assign("is_topic",true);
        $this->display("topic/{$topic}/index.html");
    }

}