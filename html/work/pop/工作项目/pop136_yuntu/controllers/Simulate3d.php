<?php

/**
 * Created by PhpStorm.
 * User: jiangwei
 * Date: 2020/1/2
 * Time: 11:33
 */
include_once getenv('BASEPATH') . '/public/JWT.php';

class Simulate3d extends POP_Controller
{
    const COL = 5;
    const KEY = "X7Z6j1igjZ3chM1@uEIwrKrMIi70h!Ah";//加解密秘钥
    const EXPIRE = 300;//有效期一天

    /*------------------------------------------------------------------------------------------------------------------
    * 构造函数，权限判断
    *-----------------------------------------------------------------------------------------------------------------*/
    function __construct()
    {
        parent::__construct();
        $this->assign("col", self::COL);
    }

    public function index()
    {
        $arr = [
            "exp" => time() + self::EXPIRE,
            "user_id" => getUserId(),
        ];
        
        $token = JWT::encode($arr, self::KEY);
        $this->checkPower(self::COL);
        $this->assign('iframe_url', 'https://3d.pop136.com?token=' . $token);
        $this->display('simulate/simulate.html');
    }

    //开通试用
    public function opentrial()
    {
        $this->load->model("User_model");
        $this->load->model("VirtualTryOn_model");
        $user_id = getUserId();
        if (empty($user_id)) {
            outPrintApiJson(1, '您的登录信息失效，请登录后重新尝试！');
        }
        $powerInfo = $this->User_model->getUserPowerDate($user_id);
        if (empty($powerInfo)) {
            outPrintApiJson(1, '普通用户无法申请,请联系客服 4008-210-662');
        }
        if (!in_array(1,$this->templete_2d_sites)) {
            outPrintApiJson(1, '您选择的模拟成品模板是非服装的，敬请等待后期推出');
        }
        $dEndTime = array();
        foreach ($powerInfo as $item) {
            $dEndTime[$item["sColumn"]] = $item["dEndTime"];
        }
        if (!empty($dEndTime[5])) {
            outPrintApiJson(1, '您已申请试用成功，无需重复申请！');
        }
        $lastPowerApply3D = $this->User_model->lastPowerApply3D($user_id);
        if (!empty($lastPowerApply3D)) {
            outPrintApiJson(1, '您已申请过3D模拟成品功能，请联系客服！');
        }
        $maxEndTime = max($dEndTime);
        $data = [
            'iAccountId' => $user_id,
            'sColumn' => '5',
            'dStartTime' => date('Y-m-d H:i:s'),
            'dEndTime' => strtotime($maxEndTime) > strtotime("+2 month") ? date('Y-m-d H:i:s', strtotime("+2 month")) : $maxEndTime,
            'dCreateTime' => date('Y-m-d H:i:s'),
            'sDeviceType' => 'PC',
            'isTrial' => 1,
        ];
        if ($this->User_model->executeSave('pop136.fm_privilege_cloud', $data)) {
            outPrintApiJson(0, 'OK');
        } else {
            outPrintApiJson(1, '操作失败，请重试！');
        }

    }

    //申请试用
    public function applytrial()
    {
        $real_name = $this->input->get_post("real_name");
        $cellphone = $this->input->get_post("cellphone");
        if (empty($real_name)) {
            outPrintApiJson(1, '请输入您的姓名');
        }
        if (empty($cellphone) || !preg_match("/1[0-9]{10}/", $cellphone)) {
            outPrintApiJson(1, '请输入正确的手机号');
        }
        $this->load->model('Simulate3d_model');
        $data = [
            "real_name" => $real_name,
            "cellphone" => $cellphone,
            "web_code" => 6,
            "create_time" => date("Y-m-d H:i:s"),
            "user_id" => getUserId()
        ];
        $id = $this->Simulate3d_model->saveApplyTrial($data);
        if ($id) {
            outPrintApiJson(0, 'SUCCESS');
        } else {
            outPrintApiJson(1, '提交失败，请重试!');
        }

    }
}