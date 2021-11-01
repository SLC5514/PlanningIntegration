<?php

class User_model extends POP_Model
{

    //pop136用户账号总表
    const T_POP136_FASHION_USER = '`pop136`.`fashion_user`';
    // 服装用户子账号表
    const T_FASHION_FASHION_USER_CHILD = '`fashion`.`fashion_user_child`';
    //服装用户表
    const T_FASHION_FASHION_USER = '`fashion`.`fashion_user`';
    const T_POP136_GLOBAL_CELLPHONE_VERIFY = '`pop136`.`global_cellphone_verify`';
    const T_FASHION_USER_PASSWORD_CHANGE = '`fashion`.`user_password_change`';
    //服装子账号基本信息表
    const T_FASHION_FASHION_USER_CHILD_INFORMATION = '`fashion`.`fashion_user_child_information`';
    //栏目权限表
    const  T_FASHION_FM_PRIVILEGE_CLOUD = '`pop136`.`fm_privilege_cloud`';
    //登录日志主账号表
    const  T_FASHION_FM_LOGIN_LOG = "`fashion`.`fashion_login_log`";
    //登录日志子账号表
    const  T_FASHION_FM_CHILD_LOGIN_LOG = "`fashion`.`fashion_user_child_login_log`";
    // 验证码生存时间
    private $codeExpireTime = 60;
    //头像表
    const T_KOREA_USER_APPEND_MESSAGE = '`fashion`.`t_korea_user_append_message`';
    //箱包用户账号表
    const T_BAGS_SHOE_USER = '`popbags`.`shoe_user`';
    //老鞋子用户账号表
    const T_SHOE_SHOE_USER = '`popshoe`.`shoe_user`';
    //首饰用户账号表
    const T_DECORATION_SHOE_USER = '`decoration`.`shoe_user`';
    //家纺用户账号表
    const T_HOMETEXTILE_SHOE_USER = '`hometextile`.`shoe_user`';

    private $user_power;
    private $is_trial;

    //每个模块限制数 ，MODULE_LIMITS = [栏目=>[模块名=>[总限制数量,剩余数量]]]
    private $module_limits = array(
        "yuntu:virtualtry" => [20, 0],//虚拟样衣
        "yuntu:similar" => [20, 0],//图搜图
        "miyin:design" => [3, 0],//米绘保存图片
        "miyin:psdgenerate" => [20, 0]//米绘生成PSD（VIP也只有20个/天）
    );
    //功能模块与栏目的对应关系
    private $module_col = array(
        "yuntu:virtualtry" => 1,
        "yuntu:similar" => 2,
        "miyin:design" => 4,
        "miyin:psdgenerate" => 4
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('common_helper');
        $this->load->helper('cookie');
        $this->load->driver('cache');
    }

    /**
     * 用户登录
     *
     * @param $account
     * @param $passwd
     * @return array
     */
    public function check_user_login($account, $passwd)
    {
        if ($account && $passwd) {
            $rs = [];
            //查询pop136账号总表
            $sql = "select `id`,`account`,`nme`,`bind_mobile`,`memo`,`iTplSite`,`template_2d`,`iLoginNumber`,`status` from " . self::T_POP136_FASHION_USER . " where account=? and MD5(passwd)=? ";
            $row = $this->query($sql, [$account, $passwd]);
            //pop136账号总表中没有此用户
            if (!$row) {
                //查询fashion.fashion_user_child表
                $sql = "select `sChildId`,`iParentId`,`sChildAccount`,`iStatus`  from " . self::T_FASHION_FASHION_USER_CHILD . "where sChildAccount=? and MD5(sPassword)=?";
                $row = $this->query($sql, [$account, $passwd]);

                if (!$row) {
                    //没有此账号信息，登录失败状态
                    return false;
                } else {
                    $rs['iAccountType'] = 2;//类型：子账号
                    $rs['account'] = $row[0]['sChildAccount'];//子账号手机号
                    $rs['userId'] = $row[0]['iParentId'];
                    $rs['childId'] = $row[0]['sChildId'];
                    $rs['child_status'] = $row[0]['iStatus'];

                }
            } else {
                $rs['iTplSite'] = $row[0]['iTplSite'];//调用哪个网站的模板用的
                $rs['iLoginNumber'] = $row[0]['iLoginNumber'];
                $rs['iAccountType'] = 1;//类型：主账号
                $rs['account'] = $row[0]['account']; //用户名
                $rs['nme'] = $row[0]['nme'];//昵称
                $rs['userId'] = $row[0]['id'];
                $rs['memo'] = $row[0]['memo'];//标识是否是云图自己的用户
                $rs['status'] = $row[0]['status'];
            }
            //判断登录用户是否是vip
            $isVip = $this->getUserPowerDate($rs['userId']);
            if ($isVip) {
                //获取当前主账号ID限制登录人数
                $allowLoginTotal = $this->getLoginNumber($rs['userId']);
            } else {
                $allowLoginTotal = 1000;
            }

            //当前主账号在线人数
            $loginNumber = $this->getUserCount($rs['userId']);
            if ($loginNumber >= $allowLoginTotal) {//是否超过登录人数限制
                // outPrintApiJson('1','当前登陆人数已超过限制');
                $rs['isLoginAllow'] = false;
            } else {
                $rs['isLoginAllow'] = true;
            }

            // print_r($rs);die;
            return $rs;
        }
    }

    /**
     * 获取个人中心用户信息
     *
     * @param $iAccountType
     * @param $uid
     *
     * @return mixed
     */
    public function getUserInfo($iAccountType, $uid)
    {
        if ($iAccountType == 1) {
            //查询主账号表
            $field = "`account` as username,`nme`,`create_time` as register_time,`bind_mobile`,`memo`";
            $sql = "select {$field} from" . self::T_POP136_FASHION_USER . " where `id`=" . $uid;
        } else {
            //查询子账号表
            $field = "`sChildAccount` as username,`dCreateTime`  as register_time ";
            $sql = "select {$field} from " . self::T_FASHION_FASHION_USER_CHILD . " where `sChildID` = " . "'{$uid}'";
        }

        return $this->query($sql);
    }

    /**
     * 获取栏目vip有效期
     *
     * @param $userId 主账号ID
     * @return array|bool
     */
    public function getUserPowerDate($userId)
    {
        $nowTime = date('Y-m-d H:i:s');
        $sql = "SELECT `iPrivId`,`sColumn`,MAX(`dEndTime`) AS `dEndTime`,`isTrial` FROM " . self::T_FASHION_FM_PRIVILEGE_CLOUD . " WHERE `iAccountId`=? AND dStartTime<=? AND dEndTime>=? AND (sDeviceType is null OR sDeviceType='PC') GROUP BY sColumn ORDER BY sColumn ASC";

        $result = $this->query($sql, [$userId, $nowTime, $nowTime]);
        if (!$result) {
            return false;
        }

        return $result;
    }

    /**
     * 3D栏目最后的权限时间，不论是否有效
     *
     * @param $userId 主账号ID
     * @return array|bool
     */
    public function lastPowerApply3D($userId)
    {
        $sql = "SELECT `iPrivId`,`sColumn`,`dEndTime`,`isTrial`  FROM " . self::T_FASHION_FM_PRIVILEGE_CLOUD . " WHERE `iAccountId`=? AND sColumn=5 AND (sDeviceType is null OR sDeviceType='PC') ORDER BY dEndTime DESC LIMIT 1";
        $result = $this->query($sql, [$userId]);
        if (empty($result)) {
            return false;
        }else{
            return $result[0];
        }
    }

    /**
     * 修改绑定手机之后 更新验证码表中的手机号
     *
     * @param $condition
     * @param $data
     * @return mixed
     */
    public function updateGlobalVerify($condition, $data)
    {
        return $this->executeUpdate(self::T_POP136_GLOBAL_CELLPHONE_VERIFY, $data, $condition);
    }

    /**
     * 获取限制登录的总个数
     *
     * @param $uId 主账号ID
     *
     * @return bool
     */
    public function getLoginNumber($uId)
    {
        $sql = "SELECT `iLoginNumber` FROM " . self::T_POP136_FASHION_USER . "WHERE id= ? limit 1";
        $rs = $this->query($sql, $uId);

        return $rs[0]['iLoginNumber'] ?: 0;
    }


    /**
     * 验证用户名
     *
     * @param $par
     *
     * @return bool
     */
    public function verifyUserName($par)
    {
        return !$par || !preg_match('/^[a-zA-Z0-9_\-@&\x{4e00}-\x{9fa5}]{2,}$/imu', $par) ? false : true;
    }

    /**
     * 检测用户名是否存在
     *
     * @param $sAccount
     *
     * @return mixed
     */
    public function check_user_exists($sAccount)
    {
        $sql = "SELECT id FROM " . self::T_POP136_FASHION_USER . "  WHERE account=? ";
        $rs = $this->query($sql, $sAccount);

        return $rs ? $rs[0]['id'] : false;
    }

    /**
     * 验证密码
     *
     * @param $par
     *
     * @return bool
     */
    public function verifyPwd($par)
    {
        if ($par) {
            if (!preg_match('/^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$/im', $par)) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    // 验证手机号
    public function verifyPhone($par)
    {
        $sql = "SELECT `id` FROM " . self::T_POP136_FASHION_USER . " WHERE `bind_mobile`=? and `user_from`=1";
        $rs = $this->query($sql, $par);

        return !empty($rs) ? false : true;
    }

    //写入到注册用户表pop136.fashion_user  fashion.fashion_user
    public function registerCloudUser($sAccount, $sPassword, $sCellphone, $itype = 1, $sTrueName, $captcha_code,$main_category = '')
    {
        $rs = [];
        $rs['status'] = 0;
        $areaCodes = OpPopFree::getAreaCodes($sCellphone);
        $nowTime = date('Y-m-d H:i:s');
        $pop136UserInfo = [
            'area_id' => $areaCodes['FashionAreaId'],
            'account' => $sAccount,
            'nme' => $sTrueName,
            'passwd' => $sPassword,
            'user_from' => $itype,
            'tel' => $sCellphone,
            'mobile' => $sCellphone,
            'bind_mobile' => $sCellphone,
            'create_time' => $nowTime,
            'memo' => 'pop136_yuntu',
            'main_category' => $main_category,//主营品类
        ];
        $iUserID = $this->executeSave(self :: T_POP136_FASHION_USER, $pop136UserInfo);
        if ($iUserID) {
            $aRegisterUser = [
                'id' => $iUserID,
                'account' => $sAccount,
                'nme' => $sTrueName,
                'passwd' => $sPassword,
                'user_from' => $itype,
                'tel' => $sCellphone,
                'mobile' => $sCellphone,
                'bind_mobile' => $sCellphone,
                'status' => 0,
                'flag' => 2,
                'client' => 0,
                'client_type' => 1,
                'client_num' => 0,
                'vip_type' => 1,
                'create_time' => $nowTime,
                'area_id' => $areaCodes['FashionAreaId'],
                'memo' => 'pop136_yuntu',
                'check_code' => $captcha_code,
            ];
            $bFlag_user = $this->executeSave(self :: T_FASHION_FASHION_USER, $aRegisterUser);
            if ($bFlag_user) {
                $rs['status'] = 1;
                $rs['data'] = ['id' => $iUserID];
            }
        }

        return $rs;
    }

    /**
     * 注册发送手机短信
     *
     * @param $mobile
     * @param $img_code
     * @param int $itype
     * @param bool $is_app 云图app不在此验证图片验证码
     */
    public function sendMessage($mobile, $img_code, $itype = 1)
    {
        if ($mobile == "") {
            outPrintApiJson(10011, '手机号码不能为空');
        }

        if (!preg_match('/^1\d{10}$/is', $mobile)) {
            outPrintApiJson(10013, '请输入真实手机号码');
        }

        // 短信发送限制验证
        $ssend_message_popEncode = get_cookie('send_message');
        // 进行解密
        $ssend_message_popDecode = popDecode($ssend_message_popEncode, COOKIE_ENCRYPT_KEYS);

        $asend_message = unserialize($ssend_message_popDecode);

        // if (!$asend_message || !isset($asend_message['last_time']) || !isset($asend_message[ 'count' ])) {
        //     outPrintApiJson('10016','您的访问频率过高, 请您稍后再试!');
        // }

        $expireTime = time() - strtotime($asend_message['last_time']);

        if ($expireTime < $this->codeExpireTime) {
            outPrintApiJson(10016, '您的访问频率过高, 请您稍后再试!');
        }
        if ($asend_message['count'] > 29) {
            outPrintApiJson(10016, '您的访问频率过高, 请您稍后再试!');
        }
        // 查询之前是否发送过验证码 若存在则将原来的验证码重新获取发送
        $table = '`pop136`.`global_cellphone_verify`';
        $field = '*';
        $iWebsite = 10;
        $where[] = "sCellPhone = " . "'{$mobile}'";
        $where[] = "iWebsite = " . "'{$iWebsite}'";
        $aCellphoneVerifyRows = $this->getSearchLists($table, $where, array(), 1, 1, $field);
        // var_dump($aCellphoneVerifyRows);die;
        $captcha = '';
        // 当前站点记录数组
        $aCellphoneVerify = array();
        if ($aCellphoneVerifyRows) {
            $captcha = $aCellphoneVerifyRows[0]['sVerifyCode'];

            if ($aCellphoneVerifyRows[0]['iWebsite'] == $iWebsite) {
                $aCellphoneVerify = $aCellphoneVerifyRows[0];
            }
        }
        if ($aCellphoneVerify && $aCellphoneVerify['iVerifyStatus'] == 1) {
            // 当前手机在当前站点已注册
            outPrintApiJson(10017, '同手机号不可重复注册账号');

        } elseif ($aCellphoneVerify && time() - strtotime($aCellphoneVerify['dCreateTime']) < $this->codeExpireTime) {
            // 验证上次发送时间
            outPrintApiJson(10018, $this->codeExpireTime . '秒后再获取');
        } else {
            //产生短信验证码
            $captcha = $captcha ? $captcha : MessageInterface::randCaptcha();
			 $MessageConfig = [
                'templateId' => 426208, // 模版id
                'appId' => 9, // 应用id
                'params' => array($captcha, 60, '4008-210-500'), // 模版参数
                'to' => strval($mobile) // 手机号码
            ];
            $returnCode = MessageInterface::sendMessage($MessageConfig);
            if ($returnCode == 200) {
                $dCreateTime = date('Y-m-d H:i:s');
                // 发送成功
                if ($aCellphoneVerify) {
                    $row = array('iVerifyID' => $aCellphoneVerify['iVerifyID'],
                        'sVerifyCode' => $captcha,
                        'iVerifyStatus' => 0,
                        'dVerifiedTime' => '',
                        'dCreateTime' => $dCreateTime
                    );
                    if ($itype == 1) {
                        $row['iCountMessageFromRegPage'] = $aCellphoneVerify['iCountMessageFromRegPage'] + 1;
                    }
                    if ($itype == 2) {
                        $row['iCountMessageFromFastReg'] = $aCellphoneVerify['iCountMessageFromFastReg'] + 1;
                    }

                    $table = self :: T_POP136_GLOBAL_CELLPHONE_VERIFY;
                    $where_update['iVerifyID'] = $aCellphoneVerify['iVerifyID'];
                    //更新注册次数
                    $this->executeUpdate($table, $row, $where_update);
                    // $this -> update_result($table, $row, $where_update);
                } else {
                    $row = array('sCellPhone' => $mobile,
                        'sVerifyCode' => $captcha,
                        'dCreateTime' => $dCreateTime,
                        'iWebsite' => $iWebsite
                    );
                    if ($itype == 1) {
                        $row['iCountMessageFromRegPage'] = 1;
                    }
                    if ($itype == 2) {
                        $row['iCountMessageFromFastReg'] = 1;
                    }
                    // $this->executeSave(self::T_POP136_GLOBAL_CELLPHONE_VERIFY, $row);
                    $table = self :: T_POP136_GLOBAL_CELLPHONE_VERIFY;
                    //插入注册记录
                    $this->executeSave($table, $row);
                    // $this -> insert_result($table, $row);
                }
                // 重置cookie的短信信息
                $scookie_send_message_val = EncryptionDeciphering(serialize(array("last_time" => date('y-m-d H:i:s', time()), "count" => $asend_message['count'] + 1)), true);
                set_cookie('send_message', $scookie_send_message_val, 7200);
                $data['info'] = $asend_message['count'];
                outPrintApiJson(0, '发送成功', $data);
            } elseif ($returnCode == 101) {
                outPrintApiJson(101, '手机号错误');
            } else {
                outPrintApiJson(102, '发送失败');
            }
        }

    }

    /**
     * 检测手机号是否绑定
     *
     * @param $newMobile
     * @param $iType
     * @return bool
     */
    public function checkBindmobile($newMobile, $iType)
    {
        $sql = "SELECT 1 FROM " . self::T_POP136_FASHION_USER . " WHERE bind_mobile=? AND user_from=? ";
        $rs = $this->query($sql, [$newMobile, $iType]);

        return count($rs) > 0 ? true : false;
    }

    /**
     * 用于修改所有站点的主账号绑定的手机号码
     *
     * @param integer $id 账号id
     * @param string $newMobileNo 新绑定的手机号码
     *
     * @return bool
     */
    public function globalModifyBindMobile($id, $newMobileNo)
    {
        $id = intval($id);
        $data['bind_mobile'] = $newMobileNo;
        $conditions['id'] = $id;

        // 更新136用户数据表绑定手机号 和 最后修改时间 （含鞋子 高端趋势）
        $this->executeUpdate(self::T_POP136_FASHION_USER, $data, $conditions);
        // 服装表
        $this->executeUpdate(self::T_FASHION_FASHION_USER, $data, $conditions);

        // 箱包
        $this->executeUpdate(self::T_BAGS_SHOE_USER, $data, $conditions);
        // 鞋子
        $this->executeUpdate(self::T_SHOE_SHOE_USER, $data, $conditions);
        // 首饰
        $this->executeUpdate(self::T_DECORATION_SHOE_USER, $data, $conditions);
        // 家纺
        $this->executeUpdate(self::T_HOMETEXTILE_SHOE_USER, $data, $conditions);

        return true;
    }

    /**
     * 找回密码发送短信
     * @param $smobile
     * @param $saccount
     * @param $img_code
     * @param bool $is_app 是否是app
     */
    public function sendMessageForPassWord($smobile, $saccount, $img_code)
    {
        // 开始验证图片验证码
        if ($img_code != get_cookie('find_code')) {
            outPrintApiJson(10020, '图片验证码错误!');
        }

        $data = array();
        $data['status'] = 1;
        $table = self::T_POP136_FASHION_USER;
        $field = 'id,bind_mobile,passwd';
        $findpass_where[] = "account = " . "'{$saccount}'";
        $findpass_where[] = "bind_mobile = " . "'{$smobile}'";
        $findpass_where[] = "memo = 'pop136_yuntu'";
        $aUserInfo = $this->getSearchLists($table, $findpass_where, array(), 1, 1, $field);

        if ($aUserInfo) {
            $frequencyInfo = get_cookie('cookiemessage_findpass');
            if ($frequencyInfo) {
                $find_message = unserialize(EncryptionDeciphering($frequencyInfo, false));
                if (!$find_message || !isset($find_message['last_time']) || !isset($find_message['count'])) {
                    outPrintApiJson(10016, '您的访问频率过高, 请您稍后再试!');
                }

                $expireTime = time() - strtotime($find_message['last_time']);
                if ($expireTime < $this->codeExpireTime) {
                    outPrintApiJson(10016, '您的访问频率过高, 请您稍后再试!');
                }
                if ($find_message['count'] > 6) {
                    outPrintApiJson(10016, '您的访问频率过高, 请您稍后再试!');
                }
            }

            if (!preg_match('/^1\d{10}$/is', $smobile)) {
                outPrintApiJson(10013, '手机号码无效');
            } else {
                $anHourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));
                $table = self::T_FASHION_USER_PASSWORD_CHANGE;
                $field = 'check_num';

                $where_checknum[] = "user_id = " . $aUserInfo[0]['id'];
                $where_checknum[] = "create_time>=" . "'{$anHourAgo}'";
                $where_checknum[] = "is_checked = 0";
                $row = $this->getSearchLists($table, $where_checknum, array(), 1, 1, $field);
                $insertFlag = true;
                if ($row) {
                    $row = $row[0];
                    $insertFlag = false;
                    $string = $captcha = $row['check_num'];
                } else {
                    $string = $captcha = MessageInterface::randCaptcha();
                }
                $nowTime = date('Y-m-d H:i:s');

                // 发送短信
                $MessageConfig = [
                    'templateId' => 46837, // 模版id
                    'appId' => 2, // 应用id
                    'params' => array($captcha, '4008-210-500'), // 模版参数
                    'to' => strval($smobile) // 手机号码
                ];
                $returnCode = MessageInterface::sendMessage($MessageConfig);
                if ($returnCode == 200) {
                    $cookiedata1['last_time'] = $nowTime;
                    $cookiedata1['count'] = $find_message['count'] + 1;
                    set_cookie('cookiemessage_findpass', EncryptionDeciphering(serialize($cookiedata1)), 7200);
                    $table = self::T_FASHION_USER_PASSWORD_CHANGE;
                    if ($insertFlag) {
                        $ins_da['user_id'] = $aUserInfo[0]['id'];
                        $ins_da['account'] = $saccount;
                        $ins_da['bind_mobile'] = $aUserInfo[0]['bind_mobile'];
                        $ins_da['check_num'] = $string;
                        $ins_da['old_password'] = $aUserInfo[0]['passwd'];
                        $ins_da['is_checked'] = '0';
                        $ins_da['create_time'] = date('y-m-d H:i:s');
                        $this->executeSave($table, $ins_da);
                    } else {
                        $fin_update_da['create_time'] = $nowTime;
                        $up_whee['user_id'] = $aUserInfo[0]['id'];
                        $up_whee['check_num'] = $string;
                        $this->executeUpdate($table, $fin_update_da, $up_whee);
                    }
                    outPrintApiJson(0, '短信发送成功');
                } else {
                    outPrintApiJson(102, '短信发送失败');
                }

            }
        } else {
            outPrintApiJson(10021, '用户名或手机号码填写错误');
        }

    }

    /**
     * 找回密码-验证身份
     * @param $saccount
     * @param $sbind_mobile
     * @param $img_code
     * @param $svalid_code
     */
    public function step1($saccount, $sbind_mobile, $img_code, $svalid_code)
    {
        // 判断手机验证码是否过期
        // $aReturnData = ['status' => 0, 'data' => ''];
        $table = self::T_FASHION_USER_PASSWORD_CHANGE;
        $field = '*';

        $where1[] = "account = " . "'{$saccount}'";
        $where1[] = "bind_mobile = " . "'{$sbind_mobile}'";
        $oderbys = ['create_time' => 'DESC'];
        $code_arr = $this->getSearchLists($table, $where1, $oderbys, 1, 1, $field);
        $code_arr = $code_arr[0];
        // 开始验证图片验证码
        if ($img_code != get_cookie('find_code')) {
            outPrintApiJson(10020, '图片验证码错误!');
        }
        if ($svalid_code == "") {

            outPrintApiJson(10001, '验证码不能为空!');
        }
        // 查看验证码时间差
        $dif_time = time() - strtotime($code_arr['create_time']);

        if (isset($code_arr['create_time']) && $dif_time > 3600) {

            outPrintApiJson(10022, '短信验证码已过期!');
        }
        // 判断手机验证码是否已被使用
        if ($code_arr['is_checked'] == 1) {

            outPrintApiJson(10023, '短信验证码已被使用');

        }
        // 判断手机验证码
        if (strtolower($svalid_code) != strtolower($code_arr['check_num'])) {

            outPrintApiJson(10032, '短信验证码错误');
        }

        $table = self::T_POP136_FASHION_USER;
        $field = 'id,account,bind_mobile,passwd,user_from,vip_type';

        $where_ch2[] = "account = " . "'{$saccount}'";
        $where_ch2[] = "bind_mobile = " . "'{$sbind_mobile}'";
        $where_ch2[] = "memo = 'pop136_yuntu'";
        $aUserInfo = $this->getSearchLists($table, $where_ch2, array(), 1, 1, $field);

        if ($aUserInfo[0]['bind_mobile'] != '') {
            // 将手机验证的字段的改为1，已验证
            $this->executeUpdate(self::T_FASHION_USER_PASSWORD_CHANGE, ['is_checked' => 1], ['id' => $code_arr['id']]);
            // $aReturnData['status'] = 5;
            $aReturnData['website'] = $aUserInfo[0]['user_from'];
            $aReturnData['hashcode'] = urlencode(EncryptionDeciphering($this->config->item('encryption_key') . '=POP=' . $aUserInfo[0]['id']));
            $aReturnData['token'] = md5($aUserInfo[0]['id'] . 'findPassword' . time());
            $aReturnData['account'] = $aUserInfo[0]['account'];//账号
            $this->input->set_cookie('token', $aReturnData['token']);
            outPrintApiJson(0, '验证成功', $aReturnData);
        } else {
            outPrintApiJson(10011, '手机号码不能为空');
        }
    }

    /**
     * 找回密码--设置新密码
     * @param $id
     * @param $spass1
     * @param $spass2
     * @param $account
     * @return bool
     */
    public function step2($id, $spass1, $spass2, $account)
    {
        if ($spass1 !== $spass2) {
            outPrintApiJson(10009, '两次密码不一致');
        }

        $flag = $this->verifyPwd($spass1);
        if (!$flag) {
            outPrintApiJson(10008, '密码不正确');

        }

        // 开始修改密码
        if ($this->globalModifyPwd($id, $spass1, $account)) {
            // outPrintApiJson('0','OK');
            return true;
        } else {
            // outPrintApiJson('1','error');
            return false;
        }
    }

    public function globalModifyChildPwd($id, $spass1)
    {
        // 用户id验证
        if (empty($id)) return false;
        // 新密码验证规则
        if (!preg_match('/^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$/im', $spass1)) return false;
        // 获取用户是否存在  分别判断用户是主账号还是子账号id
        $condition = ['sChildID' => $id];
        $aUserRows = $this->getChildInfo($condition, '', '*', 'find_passwd');
        if (!$aUserRows) return false;

        //修改子账号密码
        return $this->executeUpdate(self::T_FASHION_FASHION_USER_CHILD, ['sPassword' => $spass1], $condition) ? true : false;

    }

    /**
     * 获取子账户基本信息
     *
     * @param string $fields
     * @param  $condition
     * @param string $flag
     *
     * @return mixed
     */
    public function getChildInfo($condition, $flag = "", $fields = "*", $act = '')
    {
        if ($flag == "base") {
            $table = self :: T_FASHION_FASHION_USER_CHILD_INFORMATION;
        } else {
            $table = self :: T_FASHION_FASHION_USER_CHILD;
        }
        if ($act = 'find_passwd') {
            $sql = "select " . $fields . " from " . $table . "where sChildID=?";
            $result = $this->query($sql, [$condition['sChildID']]);
        } else {
            $sql = "select " . $fields . " from " . $table . "where sChildID=? and sPassword=?";
            $result = $this->query($sql, [$condition['sChildID'], $condition['sPassword']]);
        }
        return $result;
    }

    /**
     * 修改密码--主账号用户名+密码是否重复
     */
    public function checkMainPassword($account, $password)
    {
        $sql = 'SELECT 1 FROM ' . self :: T_FASHION_FASHION_USER . ' WHERE `account`=? AND `passwd`=? limit 1';
        $rs = $this->query($sql, array($account, $password));
        return $rs ? true : false;
    }

    /**
     * 修改子账户的密码
     *
     * @param array $userInfo 需要修改的数据 （字段 => 值）
     * @param array $condition 条件
     * @param string $flag 标志操作的表
     *
     * @return boolean TRUE or FALSE
     */
    public function modChildAccountDate($userInfo = array(), $condition = array(), $flag = '')
    {
        if ($flag == 'base') {
            $tableName = self :: T_FASHION_FASHION_USER_CHILD_INFORMATION;
        } else {
            $tableName = self :: T_FASHION_FASHION_USER_CHILD;
        }
        // 修改密码
        if (isset($userInfo['sPassword']) && !empty($userInfo['sPassword'])) {
            $rs = $this->executeUpdate($tableName, $userInfo, $condition);
            return $rs;
        }
        // 查看是否存在数据，因为更新操作要比插入频繁，所以插入操作写在后面。
        $sql = "SELECT 1 FROM " . self::T_FASHION_FASHION_USER_CHILD . " WHERE sChildID=?";
        $query = $this->query($sql, $condition['sChildID']);
        $row = count($query);
        if (!$row) {
            $rs = $this->executeSave($tableName, $userInfo);
        } else {
            $rs = $this->executeUpdate($tableName, $userInfo, $condition);
        }
        return $rs;
    }

    /**
     * 用于修改所有站点的主账号密码
     *
     * @param integer $id 账号id
     * @param string $newPassword 新密码
     *
     * @return bool
     */
    public function globalModifyPwd($id, $newPassword, $account)
    {
        // 用户id验证
        if (empty($id)) return false;
        // 新密码验证规则
        if (!preg_match('/^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$/im', $newPassword)) return false;
        // 获取用户是否存在  分别判断用户是主账号还是子账号id
        // $aUserRows = get_cookie_value();
        // if (!$aUserRows) return false;

        if (preg_match('/^1\d{10}$/im', $account)) {
            // 如果账号为手机号，验证当前账号+重置密码是否与子账号账号+密码重复
            $condition = array();
            $condition['sChildAccount'] = $account;
            $condition['sPassword'] = $newPassword;

            $aUserChildRows = $this->getChildInfo($condition);
            // 密码与子账号冲突
            if ($aUserChildRows) return false;
        }

        //  $cond['memo'] = 'pop136_yuntu';  // pc,app同步，这个没有意义了
        $cond = $data = $aData = [];
        $cond['id'] = $id;
        $data_ps['pwd_last_modify_time'] = date('Y-m-d H:i:s');
        $data_ps['passwd'] = $newPassword;//注意：passwd

        $data['pwd_last_modify_time'] = date('Y-m-d H:i:s');
        $data['password'] = $newPassword;//注意：password

        $aData['account'] = $account;
        $aData['user_id'] = $id;
        $aData['create_time'] = date('Y-m-d H:i:s');

        // 更新136用户数据表密码 和 最后修改时间 （含鞋子 高端趋势）
        $this->executeUpdate(self::T_POP136_FASHION_USER, $data_ps, $cond);
        // 服装表
        $this->executeUpdate(self::T_FASHION_FASHION_USER, $data_ps, $cond);
        $this->executeSave('`fashion`.`password_modify_log`', $aData);
        // 箱包
        $this->executeUpdate(self::T_BAGS_SHOE_USER, $data, $cond);
        $this->executeSave('`popbags`.`password_modify_log`', $aData);
        // 鞋子
        $this->executeUpdate(self::T_SHOE_SHOE_USER, $data, $cond);
        $this->executeSave('`popshoe`.`password_modify_log`', $aData);
        // 首饰
        $this->executeUpdate(self::T_DECORATION_SHOE_USER, $data, $cond);
        $this->executeSave('`decoration`.`password_modify_log`', $aData);
        // 家纺
        $this->executeUpdate(self::T_HOMETEXTILE_SHOE_USER, $data, $cond);
        $this->executeSave('`hometextile`.`password_modify_log`', $aData);
        return true;
    }

    /**
     * 查询手机号码验证记录
     */
    public function getSearchLists($tableName, $where, $sort = array(), $page = 1, $pageSize = 0, $sqlField = '*')
    {
        $sort = $sort ? $sort : array();
        $order = '';
        if ($sort) {
            $order = " ORDER BY";
            foreach ($sort as $_f => $_s) {
                $order .= " {$_f} {$_s},";
            }
            $order = substr($order, 0, -1);
        }
        // 查询条件
        $where = implode(' AND ', $where);
        $where = trim($where, 'AND');
        $where = $where == '' ? '' : 'WHERE ' . $where;

        // 查询限制
        $limitSql = $pageSize ? ' LIMIT ' . ($page - 1) * $pageSize . ',' . $pageSize : '';
        // 查询字段
        $fields = $sqlField ? $sqlField : '*';
        // 查询记录sql
        $sql = 'select ' . $fields . ' FROM ' . $tableName . $where . $order . $limitSql;
        $row = $this->query($sql);
        // var_dump($query);die;
        return $row;
    }

    /**
     * 检测手机短信验证
     *
     * @param $mobile
     * @param $captcha_code
     * @param $iWebsite
     *
     * @return boolean
     */
    public function checkSMSCode($mobile, $captcha_code, $iWebsite = 10)
    {
        $nowTime = date('Y-m-d H:i:s');
        $sql = 'select `iVerifyID` from ' . self::T_POP136_GLOBAL_CELLPHONE_VERIFY . ' WHERE `sCellPhone`= ? and `sVerifyCode`= ? and `iVerifyStatus`= 0 and `iWebsite`=? limit 1';
        $row = $this->query($sql, array($mobile, $captcha_code, $iWebsite));
        if (!$row) {
            return false;
        }

        $iVerifyID = $row[0]['iVerifyID'];
        $aLog = [
            'iVerifyID' => $iVerifyID,
            'iVerifyStatus' => 1,
            'dVerifiedTime' => $nowTime
        ];
        $where = ['iVerifyID' => $iVerifyID];
        //验证成功 更新验证码状态
        return $this->executeUpdate(self :: T_POP136_GLOBAL_CELLPHONE_VERIFY, $aLog, $where);
    }

    /**
     * 修改绑定手机-检测手机号是否有绑定
     * @param $id
     * @param string $fields
     * @return array
     */
    public function getAccountInfo($id, $fields = '*')
    {
        $sql = "select " . $fields . "from " . self::T_FASHION_FASHION_USER . " where id =? and memo='pop136_yuntu'";
        return $this->query($sql, $id);
    }

    //修改绑定手机-查看手机号是否绑定过
    public function getAccountInfoCode($id, $check_code, $fields = '*')
    {
        $sql = "select " . $fields . "from " . self::T_FASHION_FASHION_USER . " where id =? and check_code=?";
        return $this->query($sql, [$id, $check_code]);
    }

    /**
     * 修改主账号用户信息
     *
     * @param array $userInfo
     * @param array $condition
     *
     * @return boolean
     */
    public function modMainAccountDate($userInfo = array(), $condition = array())
    {
        return $this->executeUpdate(self :: T_FASHION_FASHION_USER, $userInfo, $condition);
    }

    /**
     * 检测绑定手机号验证码验证
     * @param $mobile
     * @param $captcha
     * @param $iType
     * @return bool
     */
    public function checkBindSMSCode($mobile, $captcha, $iType)
    {
        $sql = "select id from " . self::T_FASHION_FASHION_USER . " where `bind_mobile`=? and `check_code`=? and `user_from`=? and memo='pop136_yuntu'";
        $rs = $this->query($sql, [$mobile, $captcha, $iType]);

        return count($rs) ? true : false;
    }

    /**
     * 获取当前主账号下登录人数
     * @param  $userId 用户主账号ID
     * @return int [int] 数量
     */
    public function getUserCount($userId)
    {
        $count = 0;
        $this->load->driver('cache');
        $timeOut = USER_TIME_OUT;
        $memcacheKey = MIAN_UID_MEMKEY_PRE . $userId;
        $popUids = $this->cache->memcached->get($memcacheKey);
        $newPopUids = [];
        //计算其中有效的账号
        if (is_array($popUids)) {
            foreach ($popUids as $uid) {
                $_memcaheKey = UID_MEMKEY_PRE . $uid;
                $time = $this->cache->memcached->get($_memcaheKey);
                if ($time && $time + $timeOut >= time()) {
                    $newPopUids[] = $uid;
                    $count++;
                } elseif ($time && $time + $timeOut < time()) {
                    $this->cache->memcached->delete($_memcaheKey);
                }
            }
        }
        //存新的主账号与子账号的关系
        $this->cache->memcached->save($memcacheKey, $newPopUids, 0);
        return $count;
    }

    /**
     * 已开通权限
     * @param string $uId 主账号ID
     * @param bool $refresh
     * @return array array(1,2,3)
     */
    public function checkUserVip($uId = '', $refresh = false)
    {
        /*
        // 去除PC端权限缓存
        $this->load->driver('cache');
        $userInfo = get_cookie_value('user_info');
        $uId = trim($uId) ? $uId : $userInfo['id'];
        $memcacheKey = T_FM_FASHION_PRIVILGE_CLOUD_ . md5($uId);
        $column_power = $this->cache->memcached->get($memcacheKey);
        if (!$column_power || $refresh) {
        */
        $userInfo = get_cookie_value('user_info');
        $uId = trim($uId) ? $uId : $userInfo['id'];
        if(!isset($this->user_power[$uId]))
        {
            $nowTime = date('Y-m-d H:i:s');
            $column_power = [];
            $field = '`iPrivId`,`sColumn`,`dEndTime`';
            $sql = "select {$field} from " . self::T_FASHION_FM_PRIVILEGE_CLOUD . " where `iAccountId`=? and dStartTime<=? and dEndTime>=?  AND sDeviceType = ? order by sColumn asc";
            $res = $this->query($sql, [$uId, $nowTime, $nowTime, 'PC']);
            foreach ($res as $k => $v) {
                $column_power[] = $v['sColumn'];
            }
            $this->user_power[$uId] = $column_power;
        }
        /*
            $this->cache->memcached->save($memcacheKey, $column_power, 3600);
        }*/
        return $this->user_power[$uId];
    }

    /**
     * 获取当前用户类型
     * @param string $uId 主账号ID
     * @param int $col  栏目id
     */
    public function checkUserType($uId = '', $col = '')
    {

        $userInfo = get_cookie_value('user_info');
        $uId = trim($uId) ? $uId : $userInfo['id'];
        if(!isset($this->is_trial[$uId][$col]))
        {
            $nowTime = date('Y-m-d H:i:s');

            $field = '`iPrivId`,`sColumn`,`dEndTime`,`isTrial`';
            $sql = "select {$field} from " . self::T_FASHION_FM_PRIVILEGE_CLOUD . " where `iAccountId`=? and dStartTime<=? and dEndTime>=? AND sDeviceType = ? and sColumn={$col}  order by sColumn asc";
            $res = $this->query($sql, [$uId, $nowTime, $nowTime, 'PC']);

            $this->is_trial[$uId][$col] = 'GENERAL';
            if(!empty($res)){
                foreach ($res as $val){
                    if($val['isTrial']==1){
                        $this->is_trial[$uId][$col] = 'TRIAL';
                    }
                    if($val['isTrial']==0){
                        $this->is_trial[$uId][$col] = 'VIP';
                        break;
                    }
                }

            }

        }

        return $this->is_trial[$uId];
    }


    // 修改头像数据
    public function updateAvatarData($aData)
    {
        $data = array();
        $sTableName = self :: T_KOREA_USER_APPEND_MESSAGE;
        $sql = 'UPDATE ' . $sTableName . ' SET `sAvatar`=? WHERE `iUid`=? LIMIT 1';
        $this->db->query($sql, array($aData['sAvatar'], $aData['iUid']));
        $rs = $this->db->affected_rows();
        if (!$rs) {
            if ($this->executeSave($sTableName, $aData)) {
                // $data[ 'status' ] = 1;
                $data['info'] = $aData['sAvatar'];
                outPrintApiJson(0, '上传成功', $data);
            }
        } else {
            // $data[ 'status' ] = 1;
            $data['info'] = $aData['sAvatar'];
            outPrintApiJson(0, '上传成功', $data);
        }
        return $data;
    }

    // 获取头像数据
    public function getUserImg($iUid)
    {
        $sql = 'SELECT `sAvatar` FROM ' . self :: T_KOREA_USER_APPEND_MESSAGE . ' WHERE iUid=? LIMIT 1';
        $result = $this->db->query($sql, $iUid)->result_array();
        return $result[0];
    }

    //是否登录超时
    public function isLoginOut()
    {
        $POP_UID = $this->input->cookie("POP_UID"); //唯一串
        $actionTime = $this->cache->memcached->get(UID_MEMKEY_PRE . "{$POP_UID}");
        $timeOut = USER_TIME_OUT;
        if (empty($actionTime) || ($actionTime + $timeOut < time())) {
            return false;
        } else {
            return true;
        }
    }

    /*------------------------------------------------------------------------------------------------------------------
     * @todo 记录登录日志
     * $mainID      [int]       主账号ID
     * $childID     [string]    子账号ID
     *----------------------------------------------------------------------------------------------------------------*/
    public function loginLog($mainID, $childID = "")
    {
        $create_time = date("Y-m-d H:i:s");
        $ip = $this->input->ip_address();
        $power = $this->checkUserVip($mainID);
        $isVip = !empty($power) ? 1 : 0;
        if (empty($childID)) {
            $data = ["user_id" => (int)$mainID, "create_time" => $create_time, "login_ip" => $ip, "iSource" => 2, "iType" => $isVip,];
            $table = self::T_FASHION_FM_LOGIN_LOG;
        } else {
            $data = ["sChildID" => $childID, "iParentID" => (int)$mainID, "dLoginTime" => $create_time, "sLoginIP" => $ip, "iSource" => 2, "iType" => $isVip,];
            $table = self::T_FASHION_FM_CHILD_LOGIN_LOG;
        }

        $this->db->insert($table, $data);

        // 新增日志记录表 pop136.t_user_login_log
        $log_table = 'pop136.t_user_login_log';
        $log_row = [
            'login_time' => $create_time,
            'uid' => $mainID,
            'ucid' => empty($childID) ? '' : $childID,
            'login_ip' => $ip,
            'user_identity' => $isVip ? 'VIP' : 'NORMAL',
            'user_type' => empty($childID) ? 'P' : 'S',
            'website' => 6
        ];
        $this->db->insert($log_table, $log_row);
    }

    //------------------------------------------------------------------------------------------------------------------
    //  模块试用限制
    //------------------------------------------------------------------------------------------------------------------
    /**
     * 获取当前用户的所有模块试用情况
     *
     * @param $user_id 用户ID
     * @param $col_power 当前用户每个栏目的权限
     * @return array 每个模块限制数 [栏目=>[模块名=>[总限制数量,剩余数量]]]
     */
    public function get_rest_times($user_id, $col_power)
    {
        $result = $this->module_limits;
        // 虚拟样衣
        if (in_array(1, $col_power)) {
            $result["yuntu:virtualtry"] = [-1, -1];
        } else {
            $used_num = $this->get_try_out_num("yuntu:virtualtry", $user_id);
            $rest_num = $result["yuntu:virtualtry"][0] - $used_num;
            $result["yuntu:virtualtry"][1] = $rest_num;
        }
        // 图搜图
        if (in_array(2, $col_power)) {
            $result["yuntu:similar"] = [-1, -1];
        } else {
            $used_num = $this->get_try_out_num("yuntu:similar", $user_id);
            $rest_num = $result["yuntu:similar"][0] - $used_num;
            $result["yuntu:similar"][1] = $rest_num;
        }
        // 米绘
        if (in_array(4, $col_power)) {
            $result["miyin:design"] = [-1, -1];

            $used_num = $this->get_try_out_num("miyin:psdgenerate", $user_id);
            $rest_num = $result["miyin:psdgenerate"][0] - $used_num;
            $result["miyin:psdgenerate"][1] = $rest_num;
        } else {
            $used_num = $this->get_try_out_num("miyin:design", $user_id);
            $rest_num = $result["miyin:design"][0] - $used_num;
            $result["miyin:design"][1] = $rest_num;

            $used_num = $this->get_try_out_num("miyin:psdgenerate", $user_id);
            $rest_num = 3 - $used_num;
            $result["miyin:psdgenerate"] = [3, $rest_num];
        }

        return $result;
    }

    /**
     * 记录功能模块使用次数
     * @param $module 功能模块名称
     * @param $hashKey 哈希KEY (此处通常为图片路径)
     * @return array [是否成功，总次数，已使用次数]
     */
    public function add_try_out_num($module, $hashKey)
    {
        // VIP 非mihui_psd_down 无限制使用不记录
        $col_power = $this->User_model->checkUserVip();
        // 当前模块是否是VIP
        $is_vip = in_array($this->module_col[$module], $col_power) ? true : false; //当前模块是否是VIP
        if ($is_vip && $module != "miyin:psdgenerate") {
            return array(true, -1, -1);
        }

        // VIP mihui_psd_down记录次数（每天20次）
        if (!$is_vip && $module == "miyin:psdgenerate") {
            $max_num = 3;
        } else {
            $max_num = $this->module_limits[$module][0];
        }

        $user_id = getUserId();
        $redis = get_redis_connection();

        $hashKey = preg_replace('#http[s]?://([^/]*?)/#', '/', $hashKey);
        $hashKey = preg_replace('#([/]+)#', '/', $hashKey);
        $key = "site:6:user:{$user_id}:$module";
        $result = false;
        if ($redis->hExists($key, $hashKey)) {
            $redis->hIncrBy($key, $hashKey, 1);
            $result = true;
        } elseif ($redis->hLen($key) < $max_num) {
            $redis->hIncrBy($key, $hashKey, 1);
            if ($is_vip && $redis->TTL($key) <= 0) {
                $expire = strtotime(date("Y-m-d")) + 86400;
                $redis->expireAt($key, $expire);
            }
            $result = true;
        }
        return array($result, $max_num, $redis->hLen($key));
    }

    //获取指定模块的使用次数
    public function get_try_out_num($module, $user_id)
    {
        $user_id = $user_id ? $user_id : getUserId();
        $redis = get_redis_connection();
        $key = "site:6:user:{$user_id}:$module";
        return $redis->hLen($key);
    }

    //获取psd是否生成中(此处$hashKey为米绘花型唯一码)
    public function mihui_psd_generate_status($hashKey, $user_id = "")
    {
        $user_id = $user_id ? $user_id : getUserId();
        $redis = get_redis_connection();
        $key = "site:6:user:{$user_id}:miyin:psdgenerate";
        return $redis->hExists($key, $hashKey);
    }

}