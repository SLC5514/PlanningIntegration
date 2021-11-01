<?php
/**
 * 云图APP 接口控制器基类
 *
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/19
 * Time: 15:07
 */

defined('BASEPATH') OR exit('No direct script access allowed');

require_once BASEPATH . "core/Controller.php";

class Base_Controller extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }
}