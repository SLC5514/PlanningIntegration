<?php
/**
 * app登录类
 * Class Login_model
 */

class Login_model extends POP_Model
{
    public $codeExpireTime = 60;
    const T_POP136_FASHION_USER = '`pop136`.`fashion_user`';// pop136用户账号总表
    const T_POP136_GLOBAL_CELLPHONE_VERIFY = '`pop136`.`global_cellphone_verify`';// pop136用户账号总表
    const T_FASHION_USER_PASSWORD_CHANGE = '`fashion`.`user_password_change`';// 验证码记录表
    const T_FASHION_FASHION_USER_CHILD = '`fashion`.`fashion_user_child`'; // 服装用户子账号表
    const T_FASHION_FM_LOGIN_LOG = "`fashion`.`fashion_login_log`"; // 登录日志主账号表
    const T_FASHION_FASHION_USER = '`fashion`.`fashion_user`';// 服装用户账号表
    //箱包用户账号表
    const T_BAGS_SHOE_USER = '`popbags`.`shoe_user`';
    //老鞋子用户账号表
    const T_SHOE_SHOE_USER = '`popshoe`.`shoe_user`';
    //首饰用户账号表
    const T_DECORATION_SHOE_USER = '`decoration`.`shoe_user`';
    //家纺用户账号表
    const T_HOMETEXTILE_SHOE_USER = '`hometextile`.`shoe_user`';
    // app客户端设备绑定日志表
    const T_YUNTU_DEVICE_BINDING_LOG = '`yuntu`.`t_device_binding_log`';
    // 云图账号PC及APP用户登录限制表
    const T_YUNTU_LOGIN_LIMIT_CLOUD = '`yuntu`.`t_login_limit_cloud`';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 注册--发送手机短信
     *
     * @param $mobile
     * @param int $itype
     */
    public function sendAppMessage($mobile, $itype = 1)
    {
        $captcha = '';// 验证码
        $aCellphoneVerify = []; // 数组

        // 判断手机验证码是否存在
        $iWebsite = 10; // 服装网站
        $sql = "SELECT * FROM  " . self::T_POP136_GLOBAL_CELLPHONE_VERIFY . "  WHERE sCellPhone=? AND iWebsite=? limit 1";
        $result = $this->query($sql, [$mobile, $iWebsite]);

        if ($result) {
            $captcha = $result[0]['sVerifyCode'];
            if ($result[0]['iWebsite'] == $iWebsite) {
                $aCellphoneVerify = $result[0];
            }
        }

        if ($aCellphoneVerify && $aCellphoneVerify['iVerifyStatus'] == 1) {
            // 当前手机在当前站点已注册
            outPrintApiJson(10081, '同手机号不可重复注册账号');

        } elseif ($aCellphoneVerify && time() - strtotime($aCellphoneVerify['dCreateTime']) < $this->codeExpireTime) {
            // 验证上次发送时间
            outPrintApiJson(10082, $this->codeExpireTime . '秒后再获取');
        } else {

            //产生短信验证码
            $captcha = $captcha ? $captcha : MessageInterface::randCaptcha();

            // 验证码一小时有效
            if ($aCellphoneVerify && strtotime($aCellphoneVerify['dCreateTime']) + 3600 > time()) {
                $captcha_item = $captcha;
            } else {
                $captcha_item = MessageInterface::randCaptcha();
            }

            $MessageConfig = array(
                'templateId' => 426208, // 模版id
                'appId' => 9, // 应用id
                'params' => array($captcha_item, 60, '4008-210-500'), // 模版参数
                'to' => strval($mobile) // 手机号码
            );
            $returnCode = MessageInterface::sendMessage($MessageConfig);

            if ($returnCode == 200) {
                $dCreateTime = date('Y-m-d H:i:s');
                // 发送成功
                if ($aCellphoneVerify) {

                    $row = array(
                        'iVerifyID' => $aCellphoneVerify['iVerifyID'],
                        'sVerifyCode' => $captcha_item,
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

                    $table = self::T_POP136_GLOBAL_CELLPHONE_VERIFY;
                    $where_update['iVerifyID'] = $aCellphoneVerify['iVerifyID'];
                    $this->executeUpdate($table, $row, $where_update);
                } else {
                    $row = array(
                        'sCellPhone' => $mobile,
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

                    $table = self::T_POP136_GLOBAL_CELLPHONE_VERIFY;
                    //插入注册记录
                    $this->executeSave($table, $row);
                }
                // 60秒后再获取
                $data['info'] = $this->codeExpireTime;

                outPrintApiJson(0, '短信发送成功', $data);
            } elseif ($returnCode == 101) {
                outPrintApiJson(101, '手机号错误');
            } else {
                outPrintApiJson(102, '发送失败');
            }
        }

    }

    /**
     * 忘记密码--发送手机验证码
     */
    public function sendAppMessagePwd($phone)
    {
        // todo 云图的数据没有与服装以及四大品类网站同步，memo = pop136_yuntu 的缘故
        $sql = "SELECT id,bind_mobile,passwd FROM " . self::T_POP136_FASHION_USER . " WHERE bind_mobile =? AND memo =? LIMIT 1";
        $aUserInfo = $this->query($sql, [$phone, 'pop136_yuntu']);

        if ($aUserInfo) {
            $sql = "SELECT * FROM " . self::T_FASHION_USER_PASSWORD_CHANGE . " WHERE user_id =? AND is_checked = 0 LIMIT 1";
            $res = $this->query($sql, [$aUserInfo[0]['id']]);

            if ($res && time() - strtotime($res['create_time']) < $this->codeExpireTime) {
                outPrintApiJson(10083, '您的访问频率过高, 请您稍后再试!');
            }

            $anHourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));
            $sql = "SELECT check_num FROM " . self::T_FASHION_USER_PASSWORD_CHANGE . " WHERE user_id =? AND create_time >=? AND  is_checked = 0  LIMIT 1";
            $row = $this->query($sql, [$aUserInfo[0]['id'], $anHourAgo]);

            $insertFlag = true;
            if ($row) {
                $insertFlag = false;
                $row = $row[0];
                $string = $captcha = $row['check_num'];
            } else {
                $string = $captcha = MessageInterface::randCaptcha();
            }
            $nowTime = date('Y-m-d H:i:s');

            // 发送短信
            $MessageConfig = [
                'templateId' => 426216, // 模版id
                'appId' => 9, // 应用id
                'params' => array($captcha, 60, '4008-210-500'), // 模版参数
                'to' => strval($phone) // 手机号码
            ];
            $returnCode = MessageInterface::sendMessage($MessageConfig);

            // 接口成功
            if ($returnCode == 200) {
                $table = self::T_FASHION_USER_PASSWORD_CHANGE;
                if ($insertFlag) {
                    $ins_da['user_id'] = $aUserInfo[0]['id'];
                    $ins_da['account'] = $aUserInfo[0]['account'];
                    $ins_da['bind_mobile'] = $aUserInfo[0]['bind_mobile'];
                    $ins_da['check_num'] = $string;
                    $ins_da['old_password'] = $aUserInfo[0]['passwd'];
                    $ins_da['is_checked'] = '0';
                    $ins_da['create_time'] = $nowTime;
                    $this->executeSave($table, $ins_da);
                } else {
                    $fin_update_da['create_time'] = $nowTime;
                    $up_whee['user_id'] = $aUserInfo[0]['id'];
                    $up_whee['check_num'] = $string;
                    $this->executeUpdate($table, $fin_update_da, $up_whee);
                }
                $data['info'] = $this->codeExpireTime;
                outPrintApiJson(0, '短信发送成功', $data);
            } else {
                outPrintApiJson(102, '短信发送失败');
            }
        } else {
            outPrintApiJson(10084, '目前仅支持云图APP注册用户找回密码哦');
        }
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
        if (!$account || !$passwd) {
            return false;
        }

        //查询pop136账号总表
        $sql = "select `id`,`account`,`nme`,`bind_mobile`,`memo`,`iTplSite`,`iLoginNumber` from " . self::T_POP136_FASHION_USER . " where account=? and passwd=?";
        $row = $this->query($sql, [$account, $passwd]);

        //pop136账号总表中没有此用户
        $rs = [];
        if (!$row) {
            //查询fashion.fashion_user_child表
            $sql = "select `sChildId`,`iParentId`,`sChildAccount`  from " . self::T_FASHION_FASHION_USER_CHILD . " where sChildAccount=? and sPassword=?";
            $row = $this->query($sql, [$account, $passwd]);

            //没有此账号信息，登录失败状态
            if (!$row) return false;

            $rs['iAccountType'] = 2;//类型：子账号
            $rs['account'] = $row[0]['sChildAccount'];//子账号手机号
            $rs['userId'] = $row[0]['iParentId'];
            $rs['childId'] = $row[0]['sChildId'];
        } else {
            $rs['iTplSite'] = $row[0]['iTplSite'];//调用哪个网站的模板用的
            $rs['iLoginNumber'] = $row[0]['iLoginNumber'];
            $rs['iAccountType'] = 1;//类型：主账号
            $rs['account'] = $row[0]['account']; //用户名
            $rs['nme'] = $row[0]['nme'];//昵称
            $rs['userId'] = $row[0]['id'];
            $rs['memo'] = $row[0]['memo'];//标识是否是云图自己的用户
        }
        return $rs;
    }

    /**
     * 记录--主账号--日志，云图APP子账号不能登录
     * @param $mainID
     * @param string $childID
     */
    public function loginLog($mainID)
    {
        $create_time = date("Y-m-d H:i:s");
        $ip = $this->input->ip_address();
        $power = $this->checkUserVip($mainID);

        $isVip = !empty($power) ? 1 : 0;
        $data = ["user_id" => (int)$mainID, "create_time" => $create_time, "login_ip" => $ip, "iSource" => 2, "iType" => $isVip];// "iSource" => 2 云图

        $table = self::T_FASHION_FM_LOGIN_LOG;
        $this->db->insert($table, $data);
    }

    /**
     * 已开通权限
     * @param string $uId 主账号ID
     * @param bool $refresh
     * @return array array(1,2,3)
     */
    public function checkUserVip($uId, $refresh = false)
    {
        $this->load->driver('cache');
        $memcacheKey = T_FM_FASHION_PRIVILGE_CLOUD_ . 'yuntu_app_' . md5($uId);
        $column_power = $this->cache->memcached->get($memcacheKey);
        if (!$column_power || $refresh) {
            $nowTime = date('Y-m-d H:i:s');
            $column_power = [];
            $field = '`iPrivId`,`sColumn`,`dEndTime`';
            $sql = "select {$field} from " . self::T_FASHION_FM_PRIVILEGE_CLOUD . " where `iAccountId`=? and dStartTime<=? and dEndTime>=? AND sDeviceType=? order by sColumn asc";
            $res = $this->query($sql, [$uId, $nowTime, $nowTime, 'APP']);
            foreach ($res as $k => $v) {
                $column_power[] = $v['sColumn'];
            }
            $this->cache->memcached->save($memcacheKey, $column_power, 3600);
        }
        return $column_power;
    }

    /**
     * 修改密码--主账号用户名+密码是否重复
     */
    public function checkMainPassword($account, $password)
    {
        $sql = 'SELECT * FROM ' . self::T_POP136_FASHION_USER . ' WHERE `account`=? AND `passwd`=? limit 1';
        $rs = $this->query($sql, array($account, $password));
        return $rs ? true : false;
    }

    /**
     * 修改密码--服装与四大品类站全部修改,主账号
     */
    public function globalModifyPwd($id, $sNewPassword)
    {
        // 获取用户是否存在(多语言：主账号)
        $aUserRows = $this->get_user_info($id);
        if (!$aUserRows) return false;

        // 如果账号为手机号，验证当前账号+重置密码是否与子账号账号+密码重复
        if (preg_match('/^1\d{10}$/im', $aUserRows['account'])) {
            $condition = array();
            $condition['sChildAccount'] = $aUserRows['account'];
            $condition['sPassword'] = $sNewPassword;

            $aUserChildRows = $this->get_child_info($condition);
            // 密码与子账号冲突
            if ($aUserChildRows) return false;
        }


        $cond = $data = $aData = [];
        $cond['id'] = $id;
        $data_ps['pwd_last_modify_time'] = date('Y-m-d H:i:s');
        $data_ps['passwd'] = $sNewPassword;//注意：passwd

        $data['pwd_last_modify_time'] = date('Y-m-d H:i:s');
        $data['password'] = $sNewPassword;//注意：password

        $aData['account'] = $aUserRows["account"];
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
     * 根据id得到单个用户信息
     * @param integer $id 用户ID
     * @return array 用户基本信息
     */
    public function get_user_info($id)
    {
        $sql = "SELECT * FROM " . self::T_FASHION_FASHION_USER . " WHERE `id`=? LIMIT 1";
        $query = $this->query($sql, [$id])[0];
        return $query;
    }

    /**
     * 注意：global_modify_pwd方法的调用
     * 修改密码涉及子账号的相关信息
     *
     * @param $condition
     *
     * @return array
     */
    public function get_child_info($condition)
    {
        $table = self::T_FASHION_FASHION_USER_CHILD;
        $query = $this->db()->select('*')->from($table)->where($condition)->get();
        $totalCount = $this->db()->where($condition)->count_all_results($table);
        if ($totalCount == 1) {
            $result = $query->row_array();
        } else {
            $result = $query->result_array();
        }
        return $result;
    }

    // 获取忘记密码---短信验证码
    public function getCodeForgetPwd($phone)
    {
        $sql = "SELECT id,bind_mobile,passwd FROM " . self::T_POP136_FASHION_USER . " WHERE bind_mobile =? AND memo =? LIMIT 1";
        $aUserInfo = $this->query($sql, [$phone, 'pop136_yuntu']);

        $sql = "SELECT * FROM " . self::T_FASHION_USER_PASSWORD_CHANGE . " WHERE user_id =? AND is_checked = 0 LIMIT 1";
        $res = $this->query($sql, [$aUserInfo[0]['id']]);

        return $res[0]['check_num'] ? $res[0]['check_num'] : false;
    }

    //将验证码状态置为已校验
    public function updateCodeStatus($id)
    {
        if (!$id) return false;
        return $this->executeUpdate(self::T_FASHION_USER_PASSWORD_CHANGE, ['is_checked' => 1], ['id' => $id]);
    }

    /**
     * 检测用户名是否存在，获取id
     *
     * @param $sAccount
     *
     * @return mixed
     */
    public function check_user_exists($sAccount)
    {
        $sql = "SELECT id FROM " . self::T_POP136_FASHION_USER . "  WHERE account=? ";
        $rs = $this->query($sql, [$sAccount]);

        return $rs ? $rs[0]['id'] : false;
    }

    /**
     * 判断app账号当前设备号是否绑定
     */
    public function checkBindDeviceStatus($userId, $deviceNumber = '')
    {
        $sql = "SELECT * FROM " . self::T_YUNTU_DEVICE_BINDING_LOG . " WHERE status=1 AND device_number=? AND user_id=? LIMIT 1";
        $rs = $this->query($sql, [$deviceNumber, $userId]);

        return $rs ? $rs[0] : false;
    }

    /**
     * 判断app账号客户端是否超限
     */
    public function checkBindDeviceLimit($userId)
    {
        // t_device_binding_log -- 设备表
        $sql = "SELECT COUNT(*) AS total FROM " . self::T_YUNTU_DEVICE_BINDING_LOG . " WHERE status=1 AND user_id=?";
        $rows = $this->query($sql, [$userId]);

        // 已绑定的设备数, 没有记录便是没有绑定，便是为零
        $bind_num = $rows[0]['total'] ? intval($rows[0]['total']) : 0;

        // t_login_limit_cloud -- 云图账号PC及APP用户登录限制表
        $sql = "SELECT user_id,pcu,device_limit FROM " . self::T_YUNTU_LOGIN_LIMIT_CLOUD . " WHERE user_id=? ORDER BY create_time limit 1";
        $rows = $this->query($sql, [$userId]);
        // 限制的设备数
        $limit_num = $rows[0]['device_limit'] ? intval($rows[0]['device_limit']) : 0;

        $total = $limit_num - $bind_num;
        if ($total <= 0) {
            $total = false;
            return [$limit_num, $total];
        }

        // 返回剩余绑定设备的数量
        return [$limit_num, $total];
    }

    /**
     * 设备绑定信息填入数据库表
     */
    public function addDevicebindData($userId, $deviceNumber, $equipmentType)
    {
        if (!$userId || !$deviceNumber || !$equipmentType) return false;

        $bindType = $this->checkBindDeviceStatus($userId, $deviceNumber);
        if ($bindType) return false;

        $nowtime = date('Y-m-d H:i:s');
        $data = [
            'user_id' => $userId,
            'device_number' => $deviceNumber,// 设备号，唯一
            'equipment_type' => $equipmentType,// 设备型号
            'status' => 1, // 1-绑定，0-已解绑
            'binding_time' => $nowtime,
        ];
        $rows = $this->executeSave(self::T_YUNTU_DEVICE_BINDING_LOG, $data);

        return $rows ? true : false;
    }

    /**
     * 根据id获取账号
     */
    public function get_user_id($userId)
    {
        $sql = "SELECT account,id FROM " . self::T_POP136_FASHION_USER . "  WHERE id=? limit 1";
        $rs = $this->query($sql, [$userId]);

        return $rs ? $rs[0]['account'] : false;
    }


}