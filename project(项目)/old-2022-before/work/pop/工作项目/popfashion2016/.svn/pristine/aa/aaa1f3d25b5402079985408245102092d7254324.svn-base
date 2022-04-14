<?php

/**
 * 独家-快反应栏目控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/10
 * Time: 15:16
 */
class Exclusive extends POP_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
		$power = memberPower();
		//游客-5 普通用户-4 试用-3 vip子-2 vip-1
		$this->assign('usertype', $power[ 'P_UserType' ]); //用户类型
        $this->display("exclusive.html");
    }
}