<?php

/**
 * 米绘智能设计
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/24
 * Time: 12:01
 */
class Mihui extends POP_Controller
{
    const COL = 4;
    const MIHUI_HASH = 'b817233db942b2eec2550ce8d1a90ea7';

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Mihui_model', 'User_model']);
        $this->assign("col", self::COL);
        $this->get_use_type(self::COL);
    }

    // 初始化是否显示智能设计
    private function initShowMiYin()
    {
        $showMiYin = $this->showMiYin();
        if (!$showMiYin) {
            header("Location:/home/");
            exit;
        }
    }

    /*
     * 智能设计首页
     */
    public function muhuiIndex()
    {
        $this->initShowMiYin();
        $this->checkPower(self::COL, "miyin:design");
        $this->display('design/design.html');
    }

    /**
     * 登录米绘接口,跳转智能设计页面
     */
    public function loginMihui()
    {
        $this->initShowMiYin();
        $this->checkPower(self::COL, "miyin:design");
        $userId = getUserId();
        if (!$userId) {
            outPrintApiJson(0, '未登陆');
        }
        $str = time();
        list($uuid, $code) = $this->get_mihui_uuid_code($userId, $str);
        //插入用户openID表
        $data['sUserId'] = $userId;
        $data['sOpenid'] = $uuid;
        $userTable = '`yuntu`.`t_mihui_openid`';
        //判断是否已经生成过openID
        $openid = $this->Mihui_model->getOpenIdByUserId($userId);
        if (!$openid) {
            $insertId = $this->Mihui_model->executeSave($userTable, $data);
        }
        //接口实例
        $apiUrl = "https://www.mihuiai.com/r/user/urlRedirectForAuth/pop?uuid={$uuid}&str={$str}&code={$code}";
        if ($openid || $insertId) {
            header('Location:' . $apiUrl);
            exit;
        }
        header('Location:/home/');
        exit;
    }

    public function mihuiEdit()
    {
        $this->initShowMiYin();
        $this->checkPower(self::COL, "miyin:designmod");
        $serial = $this->input->get_post("serial"); //米绘花型唯一码
        $userId = getUserId();
        $time_str = time();
        list($uuid, $code) = $this->get_mihui_uuid_code($userId, $time_str);
        $apiUrl = "https://www.mihuiai.com/r/resourceManage/edit/pop?uuid={$uuid}&str={$time_str}&code={$code}&serial={$serial}";
        $this->assign("iframe_src", $apiUrl);
        $this->display('design/design.html');
    }

    //获取用户的openid与验证hash串
    private function get_mihui_uuid_code($userId, $time_str)
    {
        $uuid = md5($userId . 'miyinmihui' . POP_GLOBAL_KEYS);//openid
        $code = md5(self::MIHUI_HASH . $time_str . $uuid);
        return array($uuid, $code);
    }

    //米绘生成PDF请求
    public function mihuiPsd()
    {
        $this->checkPower(self::COL, "miyin:psdgenerate");
        $this->load->model('User_model');
        $serial = $this->input->get_post("serial"); //米绘花型唯一码
        $userId = getUserId();
        if (empty($serial)) {
            outPrintApiJson(1002, 'serial不能为空');
        }
        $exists = $this->User_model->mihui_psd_generate_status($serial);
        if ($exists) {
            outPrintApiJson(1003, '已经生成中，请勿重新生成！');
        }
        $is_has_psd = $this->Mihui_model->is_has_psd($serial);
        if ($is_has_psd) {
            outPrintApiJson(1003, '已有PSD，请前往直接下载！');
        }

        $col_power = $this->User_model->checkUserVip();
        $module_power = $this->User_model->get_rest_times($userId, $col_power);
        if ($module_power["miyin:psdgenerate"][1] === 0) {
            outPrintApiJson(1006, '您的免费生成PSD额度已用尽');
        }

        $apiUrl = "https://www.mihuiai.com/r/order/doDesign/pop/";
        $time_str = time();
        list($uuid, $code) = $this->get_mihui_uuid_code($userId, $time_str);
        $body = [
            "uuid" => $uuid,
            "code" => $code,
            "str" => $time_str,
            "serial" => $serial,
        ];
        $curl = new Curl\Curl();
        $curl->setHeader('Content-Type', 'application/json');
        $curl->post($apiUrl, json_encode($body));
        if ($curl->error) {
            outPrintApiJson(1004, $curl->error);
        } else {
            $response = $curl->response;
            $response = json_decode($response, true);
            if ($response["success"]) {
                $this->User_model->add_try_out_num("miyin:psdgenerate", $serial);
                outPrintApiJson(0, "OK");
            } else {
                outPrintApiJson(1005, $response["message"]);
            }
        }
    }
}