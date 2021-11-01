<?php
/**
 * 云图APP 接口控制器基类
 *
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/19
 * Time: 15:07
 */

require_once FCPATH . "core/Base_Controller.php";

class APP_Controller extends Base_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function jwt_check()
    {
        $this->load->model('app/Token_model');
        $header_token = $_SERVER['HTTP_ACCESS_TOKEN'];

        return $this->Token_model->jwt_verify($header_token);
    }


    // 校验token,获取用户id
    protected function check_jwt_token()
    {
        $result = $this->jwt_check();
        return $result['id'] ? $result['id'] : false;
    }

}