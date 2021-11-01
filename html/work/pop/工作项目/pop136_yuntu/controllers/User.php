<?php

/**
 * 个人中心控制器
 *
 * Created by PhpStorm.
 * User: limeng
 * Date: 2017/11/3 9:16
 */
class User extends POP_Controller
{
    public $ajaxCode;

    public function __construct()
    {
        parent::__construct();
        $this->load->driver('cache');
        $this->load->model('user_model');
        $this->load->helper('common_helper');
        $this->load->library('imageVerification');
        $this->ajaxCode = [
            '0' => 'ok',
            '1' => '失败',

            '1001' => '没有权限',
            '1002' => '登录超时',
            '10015' => '超过登录人数限制',
            '10001' => '图形验证码不能为空',
            '10002' => '图形验证码错误',
            '10003' => '账号不能为空',
            '10004' => '密码不能为空',
            '10005' => '账号或密码错误',
            '10006' => '用户名不正确',
            '10007' => '真实用户名不正确',
            '10008' => '6-20位字符，可由数字、字母、特殊字符组成',
            '10009' => '两次输入的密码不一致',
            '10010' => '真实姓名不能为空',
            '10011' => '手机号码不能为空',
            '10012' => '该号码已存在',
            '10013' => '手机号无效',
            '10014' => '此账号已存在',
            '101' => '手机号错误！',
            '102' => '发送短信失败',
            '10016' => '您的访问频率过高, 请您稍后再试!',
            '10017' => '同手机号不可重复注册账号',
            '10020' => '图片验证码错误或者为空',
            '10021' => '用户名或手机号码填写错误',
            '10022' => '短信验证码已过期',
            '10023' => '短信验证码已被使用',
            '10024' => 'token值不正确',
            '10025' => '密码格式不正确',
            '10026' => '旧密码不正确',
            '10027' => '密码不符合系统要求',
            '10028' => '用户未登录，请先登录',
            '10029' => '没有此用户信息',
            '10030' => '对不起！您已绑定过此手机',
            '10031' => '绑定失败',
            '10032' => '短信验证码错误',
            '10033' => '主营品类不能为空',
        ];
    }

    /**
     * 登录页面
     */
    public function login()
    {
        $userInfo = get_cookie_value();
        $isLoginOut = $this->user_model->isLoginOut();
        if ($userInfo && $isLoginOut) {//cookie存在并且未超时
            header("Location:/home/");
        }
        $this->setTDK("user", "login");
        $this->display('user/login.html');
    }

    /**
     * 注册页面
     */
    public function register()
    {
        $this->setTDK("user", "register");
        $this->display('user/register.html');
    }

    //用户中心页面
    public function usercenterview()
    {
        $this->checkPower();
        $this->display('user/center-view.html');
    }

    /**
     * 找回密码页面
     */
    public function findpasswordview()
    {
        $this->display('user/find-password.html');
    }

    /**
     * 登录处理
     */
    public function doLogin()
    {
        /**接收参数值*/
        $account = $this->input->post('account', true);
        $passwd = $this->input->post('password', true);
        $verify_code = $this->input->post('verify_code', true);

        if (empty($verify_code)) {
            outPrintApiJson(10001, $this->ajaxCode[10001]);
        } elseif ($verify_code != get_cookie('yuntu_login_code')) {
            outPrintApiJson(10002, $this->ajaxCode[10002]);
        }
        if (empty($account)) {
            outPrintApiJson(10003, $this->ajaxCode[10003]);
        } else {
            if (empty($passwd)) {
                outPrintApiJson(10004, $this->ajaxCode[10004]);
            }
            $userInfo = $this->user_model->check_user_login($account, $passwd);
            if ($userInfo) {
                //登陆成功
                if ($userInfo['isLoginAllow'] == false) {
                    outPrintApiJson(1, '当前登陆人数已超过限制');
                }
                if ($userInfo['iAccountType'] == 1 && !empty($userInfo['status'])) {
                    outPrintApiJson(1, '您的账号异常请联系客服');
                }
                if ($userInfo['iAccountType'] == 2 && $userInfo['child_status'] != 1) {
                    outPrintApiJson(1, '您的子账号异常请联系客服');
                }
                $uId = $userInfo['userId'];//主账号ID
                $childId = $userInfo['childId'];//子账号
                $this->user_model->loginLog($uId, $childId);

                $aUserInfo = [];
                if ($userInfo['iAccountType'] == 1) {
                    $aUserInfo['iAccountType'] = 1;
                    $aUserInfo['id'] = $uId;
                    $aUserInfo['iTplSite'] = $userInfo['iTplSite'];
                    $aUserInfo['iLoginNumber'] = $userInfo['iLoginNumber'];//允许登录的人数
                } else {
                    $aUserInfo['iAccountType'] = 2;
                    $aUserInfo['sChildID'] = $childId;
                    $aUserInfo['id'] = $uId;
                }
                $aUserInfo['account'] = $userInfo['account'];
                //用户信息
                $this->load->helper('cookie');
                $cookieInfo = encrypt(serialize($aUserInfo));
                $pop_uid = md5(uniqid() . $aUserInfo['id']);
                // $this->load->helper('url');
                // echo base_url();
                $this->input->set_cookie('POP_U', $cookieInfo);
                $this->input->set_cookie('POP_UID', $pop_uid);
                $this->input->set_cookie('userinfo_id', $aUserInfo['id']);
                //存memcache(唯一串UID与登录时间的关系)
                $memcacheKey = UID_MEMKEY_PRE . $pop_uid;
                $this->cache->memcached->save($memcacheKey, time(), UID_MEMKEY_TIME);
                //存memcache(主用户和唯一串UID的关系)
                $memcacheKey = MIAN_UID_MEMKEY_PRE . $aUserInfo['id'];
                $pop_uids = [];
                if (is_array($this->cache->memcached->get($memcacheKey))) {
                    $pop_uids = $this->cache->memcached->get($memcacheKey);
                }
                $pop_uids[] = $pop_uid;
                $this->cache->memcached->save($memcacheKey, $pop_uids, 0);

                //登录成功
                outPrintApiJson(0, 'ok');

            } else {
                //登陆失败(账号或密码不存在)
                outPrintApiJson(10005, $this->ajaxCode[10005]);
            }

        }
    }

    // 产生图片验证码
    public function imgcaptcha($params = 'verification_code')
    {
        $params = trim($params);
        $imgcaptcha = $this->imageverification->getImages($params);
        echo $imgcaptcha;
    }

    /**
     * 注册处理
     */
    public function doRegister()
    {
        /**接收注册参数值**/
        $account = $this->input->post('account', true);
        $real_name = $this->input->post('real_name', true);
        $passwd = trim($this->input->post('password', true));
        $re_passwd = trim($this->input->post('re_password', true));
        $mobile = $this->input->post('mobile', true);
        $captcha_code = $this->input->post('check_code', true);//短信验证码
        $main_category = $this->input->post('main_category', true);//主营品类

        // 主营品类必选
        if (empty($main_category) || !is_numeric($main_category)) {//主营品类不能为空s
            outPrintApiJson(10033, $this->ajaxCode[10033]);
        }

        // 开始验证信息
        if (empty($account)) {//账号不能为空s
            outPrintApiJson(10003, $this->ajaxCode[10003]);
        }
        if (!$this->user_model->verifyUserName($account)) {
            //4-20位字符，一个汉字是两个字符
            outPrintApiJson(10006, $this->ajaxCode[10006]);
        }
        if (!$this->user_model->verifyUserName($real_name)) {
            //4-20位字符，一个汉字是两个字符
            outPrintApiJson(10007, $this->ajaxCode[10007]);
        }
        if ($this->user_model->check_user_exists($account)) {// 此账号已经存在
            outPrintApiJson(10014, $this->ajaxCode[10014]);
        }
        if (empty($passwd)) {//密码不能为空
            outPrintApiJson(10004, $this->ajaxCode[10004]);
        }
        if (!$this->user_model->verifyPwd($passwd)) {// 6-20位字符，可由数字、字母、特殊字符组成
            outPrintApiJson(10008, $this->ajaxCode[10008]);
        }
        if ($re_passwd != $passwd) {//两次密码不一致
            outPrintApiJson(10009, $this->ajaxCode[10009]);
        }
        if (empty($real_name)) {//真实姓名不能为空
            outPrintApiJson(10010, $this->ajaxCode[10010]);
        }
        if (empty($mobile)) {//手机号码不能为空
            outPrintApiJson(10011, $this->ajaxCode[10011]);
        }
        if (!$this->user_model->verifyPhone($mobile)) {//该号码已存在
            outPrintApiJson(10012, $this->ajaxCode[10012]);
        }
        if (!preg_match('/^1\d{10}$/is', $mobile)) {//请确保输入手机号的有效性，以便领取相应活动优惠
            outPrintApiJson(10013, $this->ajaxCode[10013]);
        }

        //验证手机短信
        $verifyStatus = $this->user_model->checkSMSCode($mobile, $captcha_code, 10);
        if (!$verifyStatus) {
            outPrintApiJson(1, '短信验证失败');
        }
        //写入用户表
        $rs = $this->user_model->registerCloudUser($account, $passwd, $mobile, 1, $real_name, $captcha_code, $main_category);
        if ($rs['status']) {
            $userId = $rs['data']['id'];
            $this->input->set_cookie('userId', $userId);
            outPrintApiJson(0, 'ok');
        } else {
            outPrintApiJson(1, '注册失败');
        }
    }

    //极验二次验证
    private function geetCheckAgain()
    {
        $this->load->library('GeetestLib', array(GEETEST_REGISTER_ID, GEETEST_REGISTER_KEY));
        $data = array(
            "user_id" => getUserId(), # 网站用户id
            "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => $this->input->ip_address() # 请在此处传输用户请求验证时所携带的IP
        );

        $pop_uid = $this->input->cookie("POP_UID");
        $geetest_status = $this->cache->memcached->get('fashion_yuntu_geetest_status_' . $pop_uid);//服务是否正常，否则宕机
        if ($geetest_status == 1) {   //服务器正常
            $result = $this->geetestlib->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
            if ($result) {
                return true;
            } else {
                return false;
            }
        } else {  //服务器宕机,走failback模式
            if ($this->geetestlib->fail_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'])) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function geetest()
    {
        $this->load->library('GeetestLib', array(GEETEST_REGISTER_ID, GEETEST_REGISTER_KEY));

        $data = array(
            "user_id" => getUserId(), # 网站用户id
            "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => $this->input->ip_address() # 请在此处传输用户请求验证时所携带的IP
        );

        $status = $this->geetestlib->pre_process($data, 1);
        $pop_uid = $this->input->cookie("POP_UID");
        $this->cache->memcached->save('fashion_yuntu_geetest_status_' . $pop_uid, $status, 3600);//状态是判断服务是否正常，否则宕机
        echo $this->geetestlib->get_response_str();
    }

    //发送手机验证短信
    public function sendMessage()
    {
        $act = $this->input->post('act', true);
        switch ($act) {
            case 'reg'://注册
                $mobile = $this->input->post('mobile', true);
                if (!$this->geetCheckAgain()) {
                    outPrintApiJson(20001, '验证失败，请重试！');
                }
                $img_code = $this->input->post('img_code', true);//图片验证码
                $this->user_model->sendMessage($mobile, $img_code, 1);
                break;
            case 'find_password'://找回密码
                $account = $this->input->post('account', true);
                $bind_mobile = $this->input->post('bind_mobile', true);
                $img_code = $this->input->post('find_code', true);
                $this->user_model->sendMessageForPassWord($bind_mobile, $account, $img_code);
                break;
        }
    }

    //修改绑定手机--获取验证码
    public function sendCodeForBind()
    {
        $this->checkPower();//检测用户是否登录超时
        $id = getUserId();
        $bind_imgcode = $this->input->post('img_code');//图片验证码
        $mobile = $this->input->post('mobile');//手机号
        if ($bind_imgcode != $this->input->cookie('bind_imgcode1')) {
            outPrintApiJson(10002, $this->ajaxCode[10002]);
        }
        $condition = array();
        $condition['id'] = $id;
        //获取验证码
        $result = $this->user_model->getAccountInfo($condition['id'], '`bind_mobile`,`check_code`,`check_time`');
        if ($result) {
            $result = $result[0];
            $bind_mobile = $result['bind_mobile'];
            if ($bind_mobile != $mobile) {
                outPrintApiJson(1, '您没有绑定过此手机，不能修改');
            }
            $check_time = strtotime($result['check_time']);

            if (time() > ($check_time + 60 * 60) || $result['check_time'] == "") {
                //生成验证码，
                $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
                $returnStr = '';
                for ($i = 0; $i < 6; $i++) {
                    $returnStr .= $pattern{mt_rand(0, 61)}; //生成php随机数
                }
                $MessageConfig = array(
                    'templateId' => 45371, // 模版id
                    'appId' => 2,       //应用id
                    'params' => array($returnStr), //模版参数
                    'to' => strval($bind_mobile) //手机号码
                );
                $rs = MessageInterface::sendMessage($MessageConfig);
                //查日志
                if ($rs == 200) {
                    //入库
                    $data = array();
                    $data['check_code'] = $returnStr;
                    $data['check_time'] = date("Y-m-d H:i:s", time());
                    $this->user_model->modMainAccountDate($data, $condition);
                    // echo "短信发送成功!";
                    outPrintApiJson(0, 'ok');
                } else {
                    $msg = "短信发送失败,请联系客服。";
                    outPrintApiJson(102, $msg);
                }
            } else {
                $msg = "对不起！一小时之内您只能获取一次验证码。";
                outPrintApiJson(102, $msg);
            }
        } else {
            outPrintApiJson(1, '信息有误');
        }
    }

    //绑定新手机--获取验证码
    public function sendModBindMobile()
    {
        $this->checkPower();//检测用户是否登录超时
        $post = $this->input->post();
        $newMobile = $post['newMobile'];
        $bind_imgcode2 = $post['img_code'];
        if ($bind_imgcode2 != $this->input->cookie('bind_imgcode2')) {
            outPrintApiJson(10002, $this->ajaxCode[10002]);
        }
        $condition = array();
        $userInfo = get_cookie_value();
        $uid = $userInfo['id'];

        $condition['bind_mobile'] = $newMobile;
        $condition['user_from'] = 1;

        $result = $this->user_model->checkBindmobile($newMobile, 1);

        //获取验证码
        if ($result) {
            $msg = "对不起！此手机号已被绑定过！";
            outPrintApiJson(10030, $msg);
        } else {
            //生成验证码，
            $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
            $returnStr = '';
            for ($i = 0; $i < 6; $i++) {
                $returnStr .= $pattern{mt_rand(0, 61)}; //生成php随机数
            }
            $MessageConfig = array(
                'templateId' => 45371, // 模版id
                'appId' => 2,       //应用id
                'params' => array($returnStr), //模版参数
                'to' => strval($newMobile) //手机号码
            );
            $rs = MessageInterface::sendMessage($MessageConfig);
            //查日志
            if ($rs == 200) {
                // echo "短信发送成功!";
                //入库
                $data = array();
                $data['check_code'] = $returnStr;
                $data['check_time'] = date("Y-m-d H:i:s", time());
                $cond['id'] = $uid;
                $this->user_model->modMainAccountDate($data, $cond);
                outPrintApiJson(0, 'ok');
            } else {
                // echo "短信发送失败,请联系客服。";
                outPrintApiJson(102, $this->ajaxCode[102]);
            }

        }
    }

    /**
     *修改绑定手机
     */
    public function unbindMobile()
    {
        $act = $this->input->post("act", true);
        switch ($act) {
            case 'bind_step1':
                $this->checkPower();//检测用户是否登录超时
                $mobile = $this->input->post('mobile', true);
                $captcha_code = $this->input->post('msg_code', true);//短信验证码
                $img_code = $this->input->post('img_code', true);//图片验证码
                $imgCode = $this->input->cookie('bind_imgcode1', true);
                if ($img_code != $imgCode) {
                    outPrintApiJson(10002, '图片验证码错误或者为空');
                }
                $checkSms = $this->user_model->checkBindSMSCode($mobile, $captcha_code, 1);
                if ($checkSms) {
                    outPrintApiJson(0, 'ok');
                } else {
                    outPrintApiJson(1, '验证失败');
                }
                break;
        }
    }

    /**
     * 找回密码处理
     */
    public function findPassword()
    {
        $act = $this->input->post('act');
        switch ($act) {
            case 'find_step1':
                $account = $this->input->post('account', true);
                $bind_mobile = $this->input->post('bind_mobile', true);
                $img_code = $this->input->post('find_code', true);
                $captcha_code = $this->input->post('captcha1_code', true);
                //验证身份
                $this->user_model->step1($account, $bind_mobile, $img_code, $captcha_code);
                break;
            case 'find_step2':

                $hashcode = $this->input->post('hashcode', true);
                $token = $this->input->post('token', true);
                $_token = $this->input->cookie('token', true);
                if ($token != $_token) {
                    outPrintApiJson(10024, 'token值不正确');
                }

                $id = array_pop(explode('=POP=', EncryptionDeciphering(urldecode($hashcode), false)));
                $spass1 = $this->input->post('pass1', true);
                $spass2 = $this->input->post('pass2', true);
                $account = $this->input->post('account', true);

                //设置新密码
                $res = $this->user_model->step2($id, $spass1, $spass2, $account);
                if ($res) {
                    outPrintApiJson(0, '找回密码成功');
                } else {
                    outPrintApiJson(1, '失败');
                }
                break;
        }
    }


    //绑定新手机
    public function modBind()
    {
        $this->checkPower();//检测用户是否登录超时
        $userInfo = get_cookie_value();

        //判断是否已经登录
        if (!empty($userInfo)) {

            $img_code = $this->input->post('img_code', true);
            $imgCode = $this->input->cookie('bind_imgcode2', true);
            if ($img_code != $imgCode) {
                outPrintApiJson(10002, $this->ajaxCode[10002]);
            }
            $post = $this->input->post();
            $oldMobile = $post['oldMobile'];
            $newMobileNo = $post['newMobile'];
            $checkCode = $post['msg_code'];
            $id = $userInfo['id'];
            $condition = array();
            $condition['id'] = $id;
            $condition['check_code'] = $checkCode;

            //获取验证码 根据验证码和用户id去数据库查询，如果有返回值，说明验证码通过
            $result = $this->user_model->getAccountInfoCode($id, $checkCode);
            if (!empty($result)) {
                if ($newMobileNo) {//判断电话号码
                    $result = $this->user_model->globalModifyBindMobile($condition['id'], $newMobileNo);
                    if ($result) {
                        // echo 'success';
                        $info['new_mobile'] = $newMobileNo;
                        $data['sCellPhone'] = $newMobileNo;
                        $cond['iVerifyStatus'] = 1;
                        $cond['sCellPhone'] = $oldMobile;
                        $cond['iWebsite'] = 10;
                        $this->user_model->updateGlobalVerify($cond, $data);
                        outPrintApiJson(0, 'ok', $info);
                    } else {
                        $msg = '绑定失败，请重试！';
                        outPrintApiJson(10031, $msg);
                    }
                } else {
                    $msg = '请输入正确的手机号码';
                    outPrintApiJson(10013, $msg);
                }
            } else {
                // echo '验证码错误';
                outPrintApiJson(1, '短信验证码错误');
            }
        } else {
            // echo '您未登录，请先登录';
            outPrintApiJson(10028, $this->ajaxCode[10028]);
        }
    }

    /**
     * 用户中心-修改密码
     */
    public function modPwd()
    {
        $this->checkPower();//检测用户是否登录超时
        //通过cookies获取用户信息 确认用户是否已经登录
        $userInfo = get_cookie_value();
        if ($userInfo) {
            $iAccountType = $userInfo['iAccountType'];
            $post = $this->input->post();
            $newPassword = trim($post['newPassword']);
            $oldPassword = trim($post['oldPassword']);

            if ($newPassword == trim($post['reNewPassword'])) {//密码确认成功
                if (!preg_match('/^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$/im', $newPassword)) {
                    $msg = '密码格式不正确! 6-20位字符，可由数字、字母、特殊字符组成！';
                    outPrintApiJson(10025, $msg);
                }

                $modResult = '';
                //判断主账号或是子账号
                $sChildID = $userInfo['sChildID'];
                //主账号
                //验证旧密码
                $result = $this->user_model->checkMainPassword($userInfo['account'], $oldPassword);
                if ($result) {
                    //旧密码通过，去修改所有站点账号密码
                    $id = $userInfo['id'];
                    $modResult = $this->user_model->globalModifyPwd($id, $newPassword, $userInfo['account']);

                } else {
                    $msg = '旧密码不正确！';
                    outPrintApiJson(10026, $this->ajaxCode[10026]);
                }
                if ($modResult) {
                    outPrintApiJson(0, 'ok');
                }
            } else {
                $msg = '两次密码输入不一致！';
                outPrintApiJson(10009, $this->ajaxCode[10009]);
            }
        } else {
            $msg = '用户未登录，请先登录！';
            outPrintApiJson(10028, $msg);
        }
    }

    /**
     * 获取用户中心--账号信息
     */
    public function getUserInfo()
    {
        $this->checkPower();//检测用户是否登录超时
        $uid = getUserId(false);//当前登录账号的ID
        $userInfo = get_cookie_value();
        // var_dump($userInfo);
        if ($userInfo) {
            //用户类型1=>主账号,2=>子账号
            $iAccountType = $userInfo['iAccountType'];

            $returnData = [];
            $iParentId = $userInfo['id'];
            $returnData['id'] = $uid;
            //获取此用户所有信息
            $userInfo_data = $this->user_model->getUserInfo($iAccountType, $uid);
            $returnData['iTplSite'] = $this->VirtualTryOn_model->getUserTplSite($userInfo['id']);
            $returnData['iTplSite'] = is_array($returnData['iTplSite']) ? array_keys($returnData['iTplSite']) : [];
            if ($userInfo_data) {
                if ($userInfo_data[0]['bind_mobile']) {
                    $returnData['bind_mobile'] = $userInfo_data[0]['bind_mobile'];
                } else {
                    $returnData['bind_mobile'] = $userInfo_data[0]['username'];
                }
                if ($userInfo_data[0]['memo'] && $userInfo_data[0]['memo'] == 'pop136_yuntu') {
                    $returnData['memo'] = true;
                } else {
                    $returnData['memo'] = false;
                }
                $returnData['account'] = $userInfo_data[0]['username'];//用户名
                $returnData['register_time'] = $userInfo_data[0]['register_time'];//注册时间
                //获取此账号限制人数
                $iLoginNumber = $this->user_model->getLoginNumber($iParentId);

                //获取当前在线人数
                $loginNumber = $this->user_model->getUserCount($iParentId);
                if ($loginNumber) {
                    $returnData['Number'] = $loginNumber;//当前在线人数
                }

                //获取栏目vip
                $info = $this->user_model->getUserPowerDate($iParentId);
                if ($info) {
                    $returnData['isvip'] = true;
                    if ($iLoginNumber) {
                        $returnData['iLoginNumber'] = $iLoginNumber;//vip用户允许登录人数
                    }
                } else {
                    $returnData['isvip'] = false;
                    $returnData['iLoginNumber'] = 1000;//普通用户允许登录人数为1000
                }

                //特殊处理，当开通了3D权限，但是没选模板的情况下
                if (empty($returnData['iTplSite']) && !empty($info)) {
                    foreach ($info as $row) {
                        if ($row['sColumn'] == 5) {
                            $returnData['iTplSite'] = array(1);
                            break;
                        }
                    }
                }

                $returnData['yuntu_power'] = $info;
                $photo = $this->user_model->getUserImg($uid);
                $returnData['photo'] = $photo['sAvatar'];
                outPrintApiJson(0, 'ok', $returnData);
            } else {
                outPrintApiJson(10029, $this->ajaxCode[10029]);
            }
        } else {
            outPrintApiJson(10028, $this->ajaxCode[10028]);
        }

    }

//上传头像
    public function uploadAvatar()
    {
        $this->load->library('POP_Uploadpic');

        $aCookieUserInfo = get_cookie_value();
        if (!empty($_FILES) && $aCookieUserInfo) {
            $iUid = getUserId(false);
            //验证
            $res = $this->pop_uploadpic->getFileStream($_FILES['uploadAvatar']);
            //上传
            $savePath = '/yuntu/avatar/' . date("Ym") . '/';
            $body = array(
                'fName' => $iUid . '_' . MD5($iUid . time()) . '.' . $res['ext'],
                'fTargetPath' => $savePath,
                'fStream' => $res['stream'],
                'fPrefix' => 'material',
                'fWidth' => 151,
                'fHeight' => 151
            );
            $json = $this->pop_uploadpic->curlRequest($body);
            $res = json_decode($json, true);
            if ($res['status']) {
                $aData = array(
                    'iUid' => $iUid,
                    'sAvatar' => $res['info']
                );
                $data = $this->user_model->updateAvatarData($aData);
            } else {
                $msg = empty($this->pop_uploadpic->error) ? '上传失败' : $this->pop_uploadpic->error;
                outPrintApiJson('1', $msg);
            }
        } else {
            outPrintApiJson(1, '上传失败');
        }

    }

    //注册成功页
    public function regcomplete()
    {
        $this->display("user/register-complete.html");
    }

    /**
     * 退出登录
     */
    public function logOut()
    {
        $isLogout = logout();
        if ($isLogout) {
            outPrintApiJson(0, '退出成功');
        }
    }
}
