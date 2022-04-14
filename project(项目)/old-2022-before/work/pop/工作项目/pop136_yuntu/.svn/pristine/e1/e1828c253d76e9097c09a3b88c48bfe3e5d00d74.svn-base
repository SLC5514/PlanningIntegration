<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/19
 * Time: 17:47
 */

require_once FCPATH . "/core/APP_Controller.php";

class Login extends APP_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 注册获取图形验证码
     * http://yuntu.pop136.com/api/login/getverifyimg
     */
    public function getVerifyImg()
    {
        $phone = $this->input->get_post("phoneNo");// 随机数
        $this->load->library('ImageVerification');
        if (!$phone) {
            outPrintApiJson(10065, '网络问题，请联系客服');
        }
        ImageVerification::createCaptcha($phone);
    }

    /**
     * 验证图形验证码
     * http://yuntu.pop136.com/api/login/validateimgverify
     */
    public function validateImgVerify()
    {
        $this->load->library('ImageVerification');

        $phone = $this->input->get_post("phoneNo");// 随机数
        $verifyCode = $this->input->get_post("verifyCode");
        if (!$phone) {
            outPrintApiJson(10065, '网络问题，请联系客服');
        }

        if (empty($verifyCode)) {
            outPrintApiJson(10041, '图形验证码不能为空');
        } elseif (!ImageVerification::verifyCaptcha($verifyCode, $phone)) {
            outPrintApiJson(10042, '图片验证码错误');
        }

        outPrintApiJson(0, 'OK');
    }

    /**
     * 获取短信验证码
     * http://yuntu.pop136.com/api/login/getphoneverificationcode
     */
    public function getPhoneVerificationCode()
    {
        $this->load->library('ImageVerification');
        $this->load->model('Login_model');

        $phone = $this->input->get_post("cellPhone");// 手机号
        $type = $this->input->get_post("type");// 1--注册短信验证码|| 2--忘记密码短信验证码
        $imgSeed = $this->input->get_post("imgSeed"); // 生成图片验证码的种子(随机数)
        $imgCode = $this->input->get_post("imgCode");// 图片验证码

        if (!$phone || !$type) {
            outPrintApiJson(10043, '填写数据缺失');
        }
        if (!preg_match('/^1\d{10}$/is', $phone)) {
            outPrintApiJson(10044, '手机号格式不对，请重新输入');
        }

        switch ($type) {
            // 1--注册短信验证码
            case 1:
                if (!ImageVerification::verifyCaptcha($imgCode, $imgSeed)) {
                    outPrintApiJson(10045, '图片验证码错误');
                }
                $this->Login_model->sendAppMessage($phone, $itype = 1);
                break;
            // 2--忘记密码短信验证码
            case 2:
                $this->Login_model->sendAppMessagePwd($phone);
                break;
        }
    }

    /**
     * 登录逻辑
     * http://yuntu.pop136.com/api/login/user
     */
    public function user()
    {
        $this->load->model(['Login_model', 'Account_model']);

        $account = trim($this->input->get_post('account'));
        $passwd = trim($this->input->get_post('password'));
        $deviceNumber = $this->input->get_post('deviceNumber');// 设备号，唯一

        if (!$account) {
            outPrintApiJson(10046, "用户名不为空");
        }
        if (!$passwd) {
            outPrintApiJson(10047, "密码不为空");
        }

        // 判断用户是否存在
        $userInfo = $this->Login_model->check_user_login($account, $passwd);
        if (!$userInfo) {
            outPrintApiJson(10048, '账号或密码不存在');
        }
        if (isset($userInfo['childId']) && !empty($userInfo['childId'])) {
            outPrintApiJson(10049, '子账户暂不能登录');
        }

        // 判断权限
        $data = $datas = [];
        $priInfo = $this->Account_model->getUserPowerDate($userInfo['userId']);

        // vip 用户权限判断
        if ($priInfo) {
            if (!$deviceNumber) {
                outPrintApiJson(10050, "唯一设备号不存在");
            }

            // $all-总的设备数 | $limitDeviceNumber-剩余绑定设备数
            list($all, $limitDeviceNumber) = $this->Login_model->checkBindDeviceLimit($userInfo['userId']);
            if (!$all) { // $all--crm开通允许登录的总数
                // 1022,固定
                outPrintApiJson(1022, '该用户没有开通绑定设备权限');
            }

            // 当前设备是否绑定
            $bindType = $this->Login_model->checkBindDeviceStatus($userInfo['userId'], $deviceNumber);

            // 1,判断App客户的设备是否超限--告诉用户总数
            if (!$limitDeviceNumber && !$bindType) {
                // 1026,固定
                $datas['userId'] = $userInfo['userId']; //用户id
                $datas['total'] = $all; // 总数
                outPrintApiJson(1026, '设备超限', $datas);
            }

            // 2,判断当前用户在当前设备是否绑定--告诉用户剩余绑定数
            if (!$bindType && $limitDeviceNumber) {
                // 1021,固定   number-剩余绑定设备数为零时，显示total-总的设备数
                //             total-总的设备数不为零时，显示number-剩余绑定设备数
                $deviceData = [
                    'userId' => $userInfo['userId'],
                    'number' => $limitDeviceNumber
                ];
                outPrintApiJson(1021, '该用户没有绑定当前设备', $deviceData);
            }

            // VIP用户 token生成 --> id + account + token + deviceNumber(设备号)
            $data['deviceNumber'] = $bindType['device_number'];
        }

        // 普通用户 token生成 --> id + account + token
        $data['id'] = $userInfo['userId'];
        $data['account'] = $userInfo['account'];

        // 登录日志
        $this->Login_model->loginLog($data['id']);

        // 生成token
        $this->load->model('app/Token_model');
        $token = $this->Token_model->jwt_encode($data);
        $data['token'] = $token;

        outPrintApiJson(0, 'ok', $data);

    }

    /**
     * 绑定设备接口
     * http://yuntu.pop136.com/api/login/binddevices
     */
    public function bindDevices()
    {
        $this->load->model(['Login_model', 'Account_model']);

        $userId = $this->input->get_post('userId');
        $deviceNumber = $this->input->get_post('deviceNumber');// 设备号，唯一
        $equipmentType = $this->input->get_post('equipmentType');// 设备型号

        if (!$userId) {
            outPrintApiJson(10051, "用户id不为空");
        }
        if (!$deviceNumber) {
            outPrintApiJson(10052, "设备号不能为空");
        }
        if (!$equipmentType) {
            outPrintApiJson(10053, "设备型号不能为空");
        }

        // 1,判断当前用户在当前设备是否绑定
        $bindType = $this->Login_model->checkBindDeviceStatus($userId, $deviceNumber);
        if ($bindType) {
            outPrintApiJson(10066, "已绑定设备，不要重复绑定");
        }

        // 2,判断App客户的设备是否超限 $all--crm开通允许登录的总数
        list($all, $limitDeviceNumber) = $this->Login_model->checkBindDeviceLimit($userId);
        if (!$all) {
            // 1022,固定
            outPrintApiJson(1022, '该用户没有开通绑定设备权限');
        }
        // 告诉用户总数
        if (!$limitDeviceNumber) {
            // 1026,固定
            $data['userId'] = $userId;
            $data['total'] = $all;
            outPrintApiJson(1026, '设备超限', $data);
        }

        // 判断权限
        $priInfo = $this->Account_model->getUserPowerDate($userId);
        if (!$priInfo) {
            outPrintApiJson(10054, '非VIP用户，不能绑定设备号');
        }

        // 3,信息注入数据库
        $result = $this->Login_model->addDevicebindData($userId, $deviceNumber, $equipmentType);
        $account = $this->Login_model->get_user_id($userId);// 获取用户名
        if (!$result || !$account) {
            outPrintApiJson(10055, "绑定失败，请重新登录或联系客服");
        }

        $data = [
            'id' => $userId,
            'account' => $account,
            'deviceNumber' => $deviceNumber,
        ];

        // 登录日志
        $this->Login_model->loginLog($data['id']);

        $this->load->model('app/Token_model');
        $token = $this->Token_model->jwt_encode($data);

        $data['token'] = $token;
        outPrintApiJson(0, 'ok', $data);
    }

    /**
     * 修改密码
     * http://yuntu.pop136.com/api/login/modifypassword
     * post  token
     */
    public function modifyPassword()
    {
        $this->load->model('Login_model');

        $userId = $this->check_jwt_token();
        if (!$userId) {
            outPrintApiJson(1020, '用户id不存在，检查token值');
        }

        $newPassWord = trim($this->input->post('newPassWord'));
        $oldPassWord = trim($this->input->post('oldPassWord'));
        $reNewPassword = trim($this->input->post('reNewPassword'));

        if (!$oldPassWord || !$newPassWord || !$reNewPassword) {
            outPrintApiJson(10056, '密码不为空！');
        }
        if (!preg_match('/^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$/im', $newPassWord)) {
            outPrintApiJson(10057, '密码格式不正确! 6-20位字符，可由数字、字母、特殊字符组成！');
        }
        if ($newPassWord != $reNewPassword) {
            outPrintApiJson(10058, '两次密码输入不一致！');
        }

        //验证旧密码 $userInfo['account']
        $userInfo = $this->jwt_check();
        $result = $this->Login_model->checkMainPassword($userInfo['account'], $oldPassWord);
        if (!$result) {
            outPrintApiJson(10059, '旧密码不正确！');
        }

        //旧密码通过，去修改所有站点账号密码
        $id = $userInfo['id'];
        $modResult = $this->Login_model->globalModifyPwd($id, $newPassWord);

        if ($modResult) {
            outPrintApiJson(0, 'ok');
        }

    }

    /**
     * 忘记密码--找回密码
     * http://yuntu.pop136.com/api/login/smsmodifypassword
     */
    public function smsModifyPassword()
    {
        $this->load->model('Login_model');

        $newPassWord = trim($this->input->post('newPassWord'));
        $account = trim($this->input->post('account'));
        $phone = trim($this->input->post('phone'));
        $verify = trim($this->input->post('verify'));// 手机短信验证码

        if (!$newPassWord || !$account || !$phone || !$verify) {
            outPrintApiJson(10060, '参数不能为空！');
        }
        if (!preg_match('/^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$/im', $newPassWord)) {
            outPrintApiJson(10061, '密码格式不正确! 6-20位字符，可由数字、字母、特殊字符组成！');
        }
        if (!preg_match('/^1\d{10}$/is', $phone)) {
            outPrintApiJson(10062, '手机号格式不对，请重新输入');
        }

        $code = $this->Login_model->getCodeForgetPwd($phone);
        if ($verify != $code) {
            outPrintApiJson(10063, '短信验证码不对！');
        }

        // 判断账号是否存在
        $user_id = $this->Login_model->check_user_exists($account);
        if (!$user_id) {
            outPrintApiJson(10064, '账号不存在');
        }

        // 更新密码
        $modResult = $this->Login_model->globalModifyPwd($user_id, $newPassWord);

        // 将验证码状态置为已校验
        $this->Login_model->updateCodeStatus($user_id);
        if ($modResult) {
            outPrintApiJson(0, 'ok');
        }
    }

}