<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Apptoken 专用类
 */

require_once FCPATH . "/core/APP_Controller.php";

class Apptoken extends APP_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取无状态的token（如：游客）
     * 构建token
     * http://yuntu.pop136.com/api/apptoken/get
     */
    public function get()
    {
        $this->load->model('app/Token_model');
        // 新获取Token
        $token = $this->Token_model->jwt_encode();

        outPrintApiJson(0, 'OK', $token);
    }

}
