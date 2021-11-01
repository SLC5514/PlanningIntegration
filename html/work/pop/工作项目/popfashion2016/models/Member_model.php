<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 个人中心专用类
 * @property-read common_model $common_model
 */
class Member_model extends POP_Model
{

    //登录用户IP限制临时缓存  FM_TEM_IP_LIMIT_MD5( 账号ID )
    const FM_TEM_IP_LIMIT_MEMCACHE_KEY = 'FM_TEM_IP_LIMIT_';


    //登录用户访问新套餐权限数据缓存  FM_TEM_PRIVILEGE_MD5( 账号ID )
    const FM_TEM_PRIVILEGE_MEMCACHE_KEY = 'FM_TEM_PRIVILEGE_';

    // 返回结果数组数组
    public $json = array(
        'status' => -1,
        'info' => '',
        'data' => array()
    );
    // 服装注册非VIP网站分类代码
    private $iWebsite = 10;

    // 网站名称
    public $website = 'fashion';

    // 网站对应值
    public $websites = array(
        'fashion' => 1,
        'bags' => 2,
        'shoe' => 3,
        'decoration' => 4,
        'hometextile' => 5,
        'mostrend' => 8
    );
    //网站对应值
    public $iWebsites = array(
        'fashion' => 1, 'bags' => 2,
        'shoe' => 3, 'decoration' => 4,
        'hometextile' => 5, 'mostrend' => 8
    );
    /*
	  + 站点全部对应值
		1-服装、2-箱包、3-鞋子、4-首饰、5-家纺、8-高端趋势、
		10-服装开通试用验证码、20-箱包开通试用、30-鞋子开通试用、40-首饰开通试用、50-家纺开通试用
	*/
    private $aWebsites = array(10, 20, 30, 40, 50);

    // 验证码生存时间90s
    private $codeExpireTime = 60;

    // 注册非VIP试用前缀
    private static $trialPrefix = 'popsy';

    // 账号积分数量
    public $Score = 0;

    // 现绑定子账号数量
    public $UserInfo = array();

    public $ChildNumber = 0;

    public $ChildAll = 0;

    public $ChildList = array();

    /**
     * + spread 消息中间件
     */
    public static $spread = null;
    public $loginError = [
        1 => '用户名不能为空',
        2 => '密码不能为空',
        3 => '验证码不能为空',
        4 => '验证码错误',
        5 => '用户名或密码错误',
        6 => '您的账号有异常，请联系客服热线 4008-210-662',
        7 => '您绑定了客户端，请使用客户端登陆',
        8 => '对不起，您的账号尚未在此电脑上绑定',
        9 => '您绑定了固定IP登录，请在绑定IP内登录',
        10 => '您的子账号已经解绑',
    ];

    // -----------------
    public function __construct()
    {
//		parent :: __construct();
    }

    /**
     * 根据id得到用户信息
     *
     * @param integer $id 用户ID
     * @return array 用户基本信息
     */
    public function getUserById($id)
    {
        $sql = "SELECT * FROM " . self :: T_FASHION_FASHION_USER . " WHERE `id`=? LIMIT 1";
        $query = $this->db()->query($sql, $id);
        $result = $query->row_array();
        $this->UserInfo = $result;
        return $result;
    }

    /**
     * 修改主账号用户信息
     *
     * @param array $data
     * @param array $condition
     * @return boolean
     */
    public function modMainAccountDate($userInfo = array(), $condition = array())
    {
        $rs = $this->db->update(self :: T_FASHION_FASHION_USER, $userInfo, $condition);
        return $rs;
    }

    /**
     * 修改子账户的基本信息
     *
     * @param array $userInfo 需要修改的数据 （字段 => 值）
     * @param array $condition 条件
     * @param string $flag 标志操作的表
     * @return boolean TRUE or FALSE
     */
    public function modChildAccountDate($userInfo = array(), $condition = array(), $flag = '')
    {
        if ($flag == 'base') {
            $tableName = self :: T_FASHION_FASHION_USER_CHILD_INFORMATION;
        }
        else {
            $tableName = self :: T_FASHION_FASHION_USER_CHILD;
        }
        // 修改密码
        if (isset($userInfo['sPassword']) && !empty($userInfo['sPassword'])) {
            $rs = $this->executeUpdate($tableName, $userInfo, $condition);
            return $rs;
        }
        // 查看是否存在数据，因为更新操作要比插入频繁，所以插入操作写在后面。
        $sql = "SELECT 1 FROM $tableName WHERE sChildID=?";
        $query = $this->db->query($sql, $condition['sChildID']);
        $row = $query->num_rows();
        if (!$row) {
            $rs = $this->executeSave($tableName, $userInfo);
        }
        else {
            $rs = $this->executeUpdate($tableName, $userInfo, $condition);
        }
        return $rs;
    }

    /**
     * 获取子账号注册人职称
     *
     * @param int $layout :
     * 1 返回普通数组
     * 2 返回键值索引数组
     * 3 返回键值对名称数组
     * @return array
     */
    public function getPositions($layout = 1)
    {
        $aPositions = array();
        $sql = 'SELECT `iPositionsID`,`sPositionsName` FROM ' . self :: T_POP136_GLOBAL_POSITIONS . ' WHERE `iStatus`=1 ORDER BY `iPositionsID` ASC';

        $aPositionsInfo = $this->db->query($sql)->result_array();
        if ($aPositionsInfo) {
            if ($layout == 1) {
                $aPositions = $aPositionsInfo;
            }
            elseif ($layout == 2) {
                foreach ($aPositionsInfo as $row) {
                    $aPositions[$row['iPositionsID']] = $row;
                }
            }
            elseif ($layout == 3) {
                foreach ($aPositionsInfo as $row) {
                    $aPositions[$row['iPositionsID']] = $row['sPositionsName'];
                }
            }
        }
        return $aPositions;
    }

    /**
     * 子账号用户名+密码是否重复
     *
     * @param  $account 账号
     * @param  $password 密码
     * @return bool true-账号+密码已经存在，false-账号+密码不存在
     */
    public function checkChildPassword($account, $password)
    {
        $sql = 'SELECT 1 FROM ' . self :: T_FASHION_FASHION_USER_CHILD . ' WHERE `sChildAccount`=? AND `sPassword`=? limit 1';
        $rs = $this->db->query($sql, array($account, $password))->result_array();
        return $rs ? true : false;
    }

    /**
     * 主账号用户名+密码是否重复
     *
     * @param  $account 账号
     * @param  $password 密码
     * @return bool true-账号+密码已经存在，false-账号+密码不存s
     */
    public function checkMainPassword($account, $password)
    {
        $sql = 'SELECT 1 FROM ' . self :: T_FASHION_FASHION_USER . ' WHERE `account`=? AND `passwd`=? limit 1';
        $rs = $this->db->query($sql, array($account, $password))->result_array();
        return $rs ? true : false;
    }

    /**
     * 获取主账号允许开通子账号数量、当前开通子账号数量和总积分数
     */
    public function getMainAccountLicenseIntegral($iUid)
    {
        $iUid = intval($iUid);
        if ($iUid <= 0) {
            return false;
        }
        $mainSql = 'SELECT `iIntegralTotalNumber`,`iLicenseNumber` FROM ' . self :: T_FASHION_FASHION_USER . ' WHERE `id`=? LIMIT 1';
        $query = $this->db->query($mainSql, $iUid);
        $aRowMain = $query->row_array();

        $childSql = 'SELECT COUNT(1) AS iNowLicense FROM ' . self :: T_FASHION_FASHION_USER_CHILD . ' WHERE `iStatus`=1 AND `iParentID`=?';
        $query = $this->db->query($childSql, $iUid);
        $aRowChild = $query->row_array();
        return array_merge($aRowMain, $aRowChild);
    }

    /**
     * 验证用户是否为vip会员
     * $uid 当前登录用户id
     * $website 网站
     */
    public function verifyUserWhetherVip($uid, $website)
    {
        if (!is_numeric($uid) || intval($uid) == 0 || false === array_key_exists($website, $this->iWebsites))
            return false;
        // 查询用户基本信息
        $sql = 'SELECT `id`,`account`,`shoe_power`,`shoe_vip_type` ,`trends_vip_type`, `trends_power` FROM pop136.`fashion_user` WHERE `id`=? LIMIT 1';
        $query = $this->db->query($sql, $uid);
        $user_info = $query->row_array();
        if (empty($user_info)) {
            return false;
        }
        // 当前时间
        $time = time();
        // 是否为vip客户
        $isVip = false;

        switch ($website) {
            case 'fashion':
                // 服装网站VIP
                $sql = 'SELECT `id`,`bao_date_end`,`bao_date_begin`,`vip_type` FROM ' . self :: T_FASHION_FASHION_USER . ' WHERE `id`=? LIMIT 1';
                $query = $this->db->query($sql, $user_info['id']);
                $fashion_user_info = $query->row_array();

                if ($fashion_user_info) {
                    if ($fashion_user_info['vip_type'] == 3 && $time >= strtotime($fashion_user_info['bao_date_begin']) && $time <= strtotime($fashion_user_info['bao_date_end'])) {
                        $isVip = true;
                    }
                }
                break;
            case 'mostrend':
                $trends_power = json_decode($user_info['trends_power'], true);
                // 判断是否为趋势网vip
                if ($trends_power['vip_type'] == 1) {
                    if ($time >= strtotime($trends_power['vip_start_time']) && $time <= strtotime($trends_power['vip_end_time'])) {
                        $isVip = true;
                    }
                }
                break;
        }
        return $isVip ? $user_info['id'] . '#POP#' . $user_info['account'] : false;
    }

    public function verifyUserName($par)
    {
        return !$par || !preg_match('/^[a-zA-Z0-9_\-@&\x{4e00}-\x{9fa5}]{2,}$/imu', $par) ? false : true;
    }

    // 中文或含中文的字符串将其中的一个中文字替换成为两个* 返回字符串长度值
    public function cn_length($par)
    {
        return strlen(preg_replace("/[\x{4e00}-\x{9fa5}]/u", '**', $par));
    }

    // 验证密码
    public function verifyPwd($par)
    {
        if ($par) {
            if (!preg_match('/^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$/im', $par)) {
                return false;
            }
            else {
                return true;
            }
        }
        else {
            return false;
        }
    }

    // 验证手机号
    public function verifyPhone($par)
    {
        $sql = "SELECT `id` FROM " . self::T_FASHION_FASHION_USER . " WHERE `tel`='{$par}'";
        $rs = $this->db()->query($sql)->row_array();
        if (!empty($rs)) {
            return false;
        }
        return !empty($rs) ? false : true;
    }

    // 插入记录
    public function insert_result($table, $data)
    {
        $this->db()->insert($table, $data);
        return $this->db()->insert_id();
    }

    // 更新记录
    public function update_result($table, $data = array(), $where = array())
    {
        $this->db()->update($table, $data, $where);
        return $this->db()->affected_rows();
    }

    // 查询主方法
    public function getSearchLists($tableName, $condition = array(), $sort = array(), $page = 1, $pageSize = 0, $sqlField = '*')
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
        $whereSql = $this->_WhereSqlMontage($condition);
        // 查询条件
        $where = $whereSql == '' ? '' : 'WHERE ' . $whereSql;
        // 查询限制
        $limitSql = $pageSize ? ' LIMIT ' . ($page - 1) * $pageSize . ',' . $pageSize : '';
        // 查询字段
        $fields = $sqlField ? $sqlField : '*';
        // 查询记录sql
        $sql = 'select ' . $fields . ' FROM ' . $tableName . ' ' . $where . ' ' . $order . $limitSql;
        $query = $this->db()->query($sql);
        $row = $query->row_array();
        // $this->db()->close();
        return $row;
    }

    /*
	  +  SQL拼装
	  +  @condition = array( array( 字段名 ， 操作 ， 值 ) );
	*/
    public function _WhereSqlMontage(array $condition)
    {
        // gt大于  gte、ge大于等于，lt小于，lte、le 小于等于，neq 不等于 ,isNull 为空 isNotNull 不为空
        $selectFlag = array('in', 'noin', 'inset', 'neq', 'eq', 'like', 'regexp', 'gt', 'gte', 'lt', 'lte', 'isNull', 'isNotNull');
        $count = sizeof($condition);
        $whereSql = '';
        try {
            foreach ($condition as $_key => $_condition) {
                $_field = $_condition[0];    //操作字段
                $_operation = $_condition[1];    //操作条件
                $_value = $_condition[2];    //操作值
                if (in_array($_operation, $selectFlag)) {
                    if (!empty($_value) || $_value == 0) {
                        switch ($_operation) {
                            case 'noin':
                            case 'in' :
                                $val = '';
                                if (is_string($_condition[2])) {
                                    $_var = explode(',', $_condition[2]);
                                }
                                elseif (is_array($_condition[2])) {
                                    $_var = $_value;
                                }
                                $c = sizeof($_var);
                                foreach ($_var as $_c => $_v) {
                                    $val .= $this->db()->escape($_v) . ($_c + 1 != $c ? ',' : '');
                                }
                                if ($_condition[1] == 'noin') {
                                    $whereSql .= $_field . ' NOT IN ( ' . $val . ' )';
                                }
                                else {
                                    $whereSql .= $_field . ' IN ( ' . $val . ' )';
                                }
                                break;
                            case 'inset' :
                                $whereSql .= 'FIND_IN_SET( ' . $this->db()->escape($_condition[2]) . ' , ' . $_condition[0] . '  ) ';
                                break;
                            case 'regexp' :
                                $whereSql .= $_field . ' REGEXP ' . $this->db()->escape($_value);
                                break;
                            case 'like' :
                                $whereSql .= $_field . ' LIKE ' . $this->db()->escape('%' . $_value . '%');
                                break;
                            case 'eq' :
                                $whereSql .= $_field . ' = ' . $this->db()->escape($_value);
                                break;
                            case 'lt' :
                                $whereSql .= $_field . ' < ' . $this->db()->escape($_value);
                                break;
                            case 'lte' :
                                $whereSql .= $_field . ' <=' . $this->db()->escape($_value);
                                break;
                            case 'gte' :
                                $whereSql .= $_field . ' >=' . $this->db()->escape($_value);
                                break;
                            case 'gt' :
                                $whereSql .= $_field . ' > ' . $this->db()->escape($_value);
                                break;
                            case 'neq' :
                                $whereSql .= $_field . ' <> ' . $this->db()->escape($_value);
                                break;
                            case 'isNotNull' :
                                $whereSql .= '(' . $_field . ' <> "" or ' . $_field . ' IS NOT NULL )';
                                break;
                            case 'isNull' :
                                $whereSql .= '(' . $_field . '="" or ' . $_field . ' IS NULL )';
                                break;
                            default:
                                throw new Exception("操作错误：\n <br/> &nbsp;错误信息——" . var_export($_condition, true) . '<br/> &nbsp;操作符：' . $_operation);
                                break;
                        }
                        $whereSql = $whereSql . ($whereSql ? ($_key < $count - 1 ? ' AND ' : '') : '');
                    }
                }
                else {
                    throw new Exception("操作错误：\n <br/> &nbsp;错误信息——" . var_export($_condition, true) . '<br/>&nbsp;操作符：' . $_operation);
                }
            }
        } catch (Exception $e) {
            //printf( "Caught exception: %s<br/>" , $e->getMessage() );exit;
        }
        return $whereSql;
    }

    public function updateCellPhone($tmpId, $sCellphone, $verifyCode)
    {
        $json = $this->json;
        $nowTime = date('Y-m-d H:i:s');
        $iWebsite = $this->iWebsite;
        // 验证手机号验证码
        $aCellphoneVerify = $this->getSearchLists(
            self :: T_POP136_GLOBAL_CELLPHONE_VERIFY,
            [
                ['sCellPhone', 'eq', $sCellphone],
                ['sVerifyCode', 'eq', $verifyCode],
                ['iVerifyStatus', 'eq', 0],
                ['iWebsite', 'eq', $iWebsite]
            ],
            [], 1, 1, 'iVerifyID , dCreateTime'
        );
        if ($aCellphoneVerify) {
            $iVerifyID = $aCellphoneVerify['iVerifyID'];
            $aLog = [
                'iVerifyID' => $iVerifyID,
                'iVerifyStatus' => 1,
                'dVerifiedTime' => $nowTime
            ];
            $where = ['iVerifyID' => $iVerifyID];
            //验证成功 更新验证码状态
            $rs = $this->executeUpdate(self :: T_POP136_GLOBAL_CELLPHONE_VERIFY, $aLog, $where);
            if ($rs) {
                //查询临时表中的用户信息
                $sql = "select `sAccount`,`sPassword`,`sRealName`,`iWebsite` from " . self::T_POP136_GLOBAL_USERS_TMP . " where `id`=? limit 1";
                $row = $this->db->query($sql, $tmpId)->row_array();
                $susername = $row['sAccount'];
                $spassword = $row['sPassword'];
                $itype = $row['iWebsite'];
                $struename = $row['sRealName'];

                //把用户信息保存到正式表中`fashion`.`fashion_user`  `pop136`.`fashion_user`
                $result = $this->registerNewUser($susername, $spassword, $sCellphone, $itype, $struename);
                $iUid = $result['data']['id'];

                //更新临时表中的iUserId
                $data = [
                    'iUserId' => $iUid,
                ];
                $condition = ['id' => $tmpId];
                $this->executeUpdate(self::T_POP136_GLOBAL_USERS_TMP, $data, $condition);
                $json['status'] = 1;
                $dTime = time();
                $sHash = STRTOLOWER(md5($iUid . POP_GLOBAL_KEYS . $dTime));
                $sIp = $this->input->ip_address();
                $auotLoginHtml = "<script src='/member/doLogin/" . $iUid . "-" . $dTime . "-" . $sHash . "/'></script>";

                set_cookie("auotLoginHtml", $auotLoginHtml, 0);
                set_cookie("check_info", "$iUid-$dTime-$sHash", 0);
                set_cookie("userinfo_id", $iUid, 0);
                return $json;
                // 如果有推荐人信息则添加推荐记录;
                /*
              $RL = $this->input->post('RL', true);
                $RID = $this->input->post('RID', true);
                $UID = $this->input->post('UID', true);
                $APM = $this->input->post('APM', true);

                $RID = !empty($RID) && intval($RID) > 0 ? intval($RID) : 0;
                if ($RL && $RID > 0 && $UID) {
                    $this->Member_model->disposeRecommendLink($RL , $RID , $UID , $iUid);
                }
                if ($APM == 'APM') {
                    $res = $this->Member_model->recommendApm($iUid);
                    echo $res;
                }
              */
            }
        }
        else {
            $json['info'] = '-44';
            return $json;
        }

    }

    //写入到注册用户临时表
    public function registerTempUser($sAccount, $sPassword, $sCellphone, $itype = 1, $sTrueName)
    {
        $json = $this->json;
        $nowTime = date('Y-m-d H:i:s');
        $tempUserInfo = [
            'sAccount' => $sAccount,
            'sPassword' => $sPassword,
            'sRealName' => $sTrueName,
            'sMobile' => $sCellphone,
            'iWebsite' => $itype,
            'dCreateTime' => $nowTime,
        ];
        $iUserID = $this->insert_result(self :: T_POP136_GLOBAL_USERS_TMP, $tempUserInfo);
        if ($iUserID) {
            $json['status'] = 1;
            $json['data'] = ['id' => $iUserID];
        }
        // $this->db()->close();
        return $json;
    }

    // 注册主方法 zc
    public function registerNewUser($sAccount, $sPassword, $sCellphone, $itype = 1, $sTrueName)
    {
        $json = $this->json;
        $nowTime = date('Y-m-d H:i:s');

        // 事务处理
        $this->db()->trans_begin();
        // 当前所操作站点代号
        $website = $this->websites[$this->website];
        $areaCodes = $this->getAreaCodes($sCellphone);

        // 当前用户不存在
        $aPopUser = [
            'account' => $sAccount,
            'nme' => $sTrueName,
            'passwd' => $sPassword,
            'user_from' => $website,
            'tel' => $sCellphone,
            'mobile' => $sCellphone,
            'bind_mobile' => $sCellphone,
            'create_time' => $nowTime
        ];
        $pidtt = get_cookie('pidtt') != '' ? trim(get_cookie('pidtt')) : '';
        // 网络组推广标识 11对应crm中 用户来源 网络组推广
        if ($pidtt != '') {
            $aPopUser['user_sbcate'] = 11;
            $aPopUser['memo'] = 'PID:' . $pidtt;
        }
        $iUserID = $this->insert_result(self :: T_POP136_FASHION_USER, $aPopUser);

        switch ($website) {
            case 1:// 服装
                $aRegisterUser = [
                    'id' => $iUserID,
                    'account' => $sAccount,
                    'nme' => $sTrueName,
                    'passwd' => $sPassword,
                    'user_from' => $website,
                    'tel' => $sCellphone,
                    'mobile' => $sCellphone,
                    'bind_mobile' => $sCellphone,
                    'status' => 0,
                    'flag' => 2,
                    'client' => 0,
                    'client_type' => 1,
                    'client_num' => 0,
                    'area_id' => $areaCodes['FashionAreaId'],
                    'vip_type' => 1,
                    'create_time' => $nowTime
                ];
                $bFlag_user = $this->insert_result(self :: T_FASHION_FASHION_USER, $aRegisterUser);
                break;
        }
        if ($iUserID && $bFlag_user) {
            if ($website == 1) {
                $row = [
                    'id' => $iUserID,
                    'account' => $sAccount,
                    'nme' => $sTrueName,
                    'iWebsite' => $website,
                    'sCellPhone' => $sCellphone,
                    'iType' => $itype,
                    'create_time' => $nowTime
                ];
                $this->insert_result(self :: T_POP136_REGISTER_INFO, $row);
            }
            // 提交一个事务
            $this->db()->trans_commit();

            //注册完成，发送短息
            $MessageConfig = array(
                'templateId' => 142721, // 模版id
                'appId' => 2, // 应用id
                'params' => array(), // 模版参数
                'to' => strval($sCellphone) // 手机号码
            );
            MessageInterface::sendMessage($MessageConfig);

            // ================ 春节期间免费开通试用BEGIN =================//
            $strNowTime = strtotime($nowTime);
            if ($strNowTime <= strtotime('2017-02-04 23:59:59')) {
                $iAccountId = $iUserID;
                $iOperatorId = 0;
                $sGender = "1,2,5";
                $sIndustry = "6,7,8,9,10,11,12,158,159";
                $sColumn = "1,2,3,4,5,6,7,8,20,21,22,23,30,31,32,33,34,35,36,37,38,40,50,51,52,53,54,55,56,57,70,71,72,73,80,81,82,83,84,85,90,91,112,113,114,115,116,117,131";
                $iType = 1;
                $dStartTime = '2017-01-21 00:00:01';
                $dEndTime = '2017-02-04 23:59:59';
                $dCreateTime = $nowTime;
                $sMemo = "春节期间免费开通试用";
                $sql = "insert into " . self::T_FASHION_FM_PRIVILEGE . "(iAccountId,iOperatorId,sGender,sIndustry,sColumn,iType,dStartTime,dEndTime,dCreateTime,sMemo) values($iAccountId,$iOperatorId,'$sGender','$sIndustry','$sColumn',$iType,'$dStartTime','$dEndTime','$dCreateTime','$sMemo')";
                $this->db()->query($sql);
            }
            // ================ 春节期间免费开通试用END  =================//

            $json['status'] = 1;
            $json['data'] = ['id' => $iUserID];
        }
        else {
            // 事务回滚
            $this->db()->trans_rollback();
            $json['info'] = '注册失败...';
        }
        // $this->db()->close();
        return $json;
    }

    private function getAreaCodes($tel)
    {
        $sql = 'SELECT `FashionAreaId`, `BagAreaId`, `ShoeAreaId`, `ShoushiAreaId`, `JiafangAreaId` FROM `pop136`.`dm_mobile` WHERE MobileNumber="' . substr($tel, 0, 7) . '" LIMIT 1';
        $query = $this->db()->query($sql);
        $row = $query->row_array();

        if (!$row) {
            $row = array('FashionAreaId' => 581176,
                'BagAreaId' => 581176,
                'ShoeAreaId' => 581176,
                'ShoushiAreaId' => 581176,
                'JiafangAreaId' => 581176
            );
        }
        else {
            $row = $row;
        }
        return $row;
    }

    // 注册发送短信的主方法
    public function registerSendMessage($smobile, $itype = '1', $is_voice = false)
    {
        $data['status'] = 0;
        $aWebsites = $this->aWebsites;
        $iWebsite = $this->iWebsite;
        if ($smobile == "") {
            $data['info'] = '手机号码不能为空';
            echo json_encode($data);
            exit();
        }

        if (!preg_match('/^1\d{10}$/is', $smobile)) {
            $data['info'] = '请输入真实手机号码';
            echo json_encode($data);
            exit();
        }
        // 开始验证图片验证码
        // if ($checkimg_code != get_cookie('checkcode')) {
        //     $data['info'] = '图片验证码错误或者为空';
        //     echo json_encode($data);
        //     exit();
        // }
        $dragtoken = $this->input->get_post("dragtoken");
        if (!empty($dragtoken)) {
            $pop_uid = $this->input->cookie("POP_UID");
            $this->load->library("SlideVerification");
            $result = $this->slideverification->checkToken($dragtoken, $pop_uid, "register");
            if (!$result) {
                $data['info'] = '请重新拖拽滑块！';
                echo json_encode($data);
                exit();
            }
        }
        // 短信发送限制验证
        $ssend_message_popEncode = get_cookie('send_message');
        // 进行解密
        $ssend_message_popDecode = popDecode($ssend_message_popEncode, COOKIE_ENCRYPT_KEYS);

        $asend_message = unserialize($ssend_message_popDecode);

        if (!$asend_message || !isset($asend_message['last_time']) || !isset($asend_message['count'])) {
            $data['info'] = '您的访问频率过高, 请您稍后再试!';
            echo json_encode($data);
            exit();
        }

        $expireTime = time() - strtotime($asend_message['last_time']);

        if ($expireTime < $this->codeExpireTime) {
            $data['info'] = '您的访问频率过高, 请您稍后再试!';
            echo json_encode($data);
            exit();
        }
        if ($asend_message['count'] > 29) {
            $data['info'] = '您的访问频率过高, 请您稍后再试!';
            echo json_encode($data);
            exit();
        }

        $sWillCreateAccount = self:: $trialPrefix . $smobile;
        // 验证当前手机是否已经开启试用账号
        $table = '`pop136`.`global_cellphone_verify`';
        $where[] = array('sCellPhone', 'eq', $smobile);
        $where[] = array('iWebsite', 'in', $aWebsites);
        $field = '*';
        $aCellphoneVerifyRows = $this->getSearchLists($table, $where, array(), 1, 1, $field);
        // mydebug($aCellphoneVerifyRows);
        // 查询之前是否发送过验证码 若存在则将原来的验证码重新获取发送
        $captcha = '';
        // 当前站点记录数组
        $aCellphoneVerify = array();
        if ($aCellphoneVerifyRows) {
            $captcha = $aCellphoneVerifyRows['sVerifyCode'];

            if ($aCellphoneVerifyRows['iWebsite'] == $iWebsite) {
                $aCellphoneVerify = $aCellphoneVerifyRows;
            }
        }
        if ($aCellphoneVerify && $aCellphoneVerify['iVerifyStatus'] == 1) {
            // 当前手机在当前站点已注册
            $data['info'] = '同手机号不可重复注册账号';
            echo json_encode($data);
            exit();
        }
        elseif ($aCellphoneVerify && time() - strtotime($aCellphoneVerify['dCreateTime']) < $this->codeExpireTime) {
            // 验证上次发送时间
            $data['info'] = $this->codeExpireTime . '秒后再获取';
            echo json_encode($data);
            exit();
        }
        else {
            // 产生短信验证码
            $captcha = $captcha ? $captcha : MessageInterface::randCaptcha();
            //$captcha = '123456';
            if ($is_voice) {
                include_once getenv('BASEPATH') . "/message_verify/MessageVerifyHelp.class.php";
                // 发送语音短信验证
                $voiceConfig = array(
                    'appType' => 2,        // 应用类型	参照配置文件
                    'to' => $smobile,    // 接收号码
                    'verifyCode' => $captcha,    // 验证码内容
                );
                // 调用语音验证码接口结果
                $ret = MessageVerifyHelp::voiceVerify($voiceConfig);
                if ($ret) {
                    $data['status'] = 1;
                    $data['info'] = $asend_message['count'];
                    // 重置cookie的短信信息
                    $scookie_send_message_val = EncryptionDeciphering
                    (serialize(array("last_time" => date('y-m-d H:i:s', time()), "count" => $asend_message['count'] + 1)), true);
                    set_cookie('send_message', $scookie_send_message_val, 7200);
                }
                else {
                    $data['status'] = 0;
                    $data['info'] = '发送失败';
                }
                echo json_encode($data);
                exit;
            }
            else {
                $MessageConfig = array('templateId' => '', // 模版id
                    'appId' => 2, // 应用id
                    'params' => array($captcha, '4008-210-500'), // 模版参数
                    'to' => strval($smobile) // 手机号码
                );
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
                        $this->update_result($table, $row, $where_update);
                    }
                    else {
                        $row = array('sCellPhone' => $smobile,
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
                        $this->insert_result($table, $row);
                    }
                    // 重置cookie的短信信息
                    $scookie_send_message_val = EncryptionDeciphering
                    (serialize(array("last_time" => date('y-m-d H:i:s', time()), "count" => $asend_message['count'] + 1)), true);
                    set_cookie('send_message', $scookie_send_message_val, 7200);
                    $data['status'] = 1;
                    $data['info'] = $asend_message['count'];
                    echo json_encode($data);
                    exit();
                }
                elseif ($returnCode == 101) {
                    $data['info'] = '手机号错误！';
                    echo json_encode($data);
                    exit();
                }
                else {
                    $data['info'] = '发送失败';
                    echo json_encode($data);
                    exit();
                }
            }
        }
    }

    // $account 账号,$code 验证码
    public function verifyPhoneCode($phoneNo, $code)
    {
        $json = $this->json;
        $iWebsite = $this->iWebsites[$this->website];
        if (!preg_match('/^1\d{10}$/im', $phoneNo)) {
            $json['status'] = -1;
        }
        elseif (!preg_match('/^[0-9A-Za-z]{6}$/im', $code)) {
            $json['status'] = -2;
        }
        else {
            $sql = 'SELECT `dCreateTime`,`iVerifyID` FROM ' . self :: T_POP136_GLOBAL_CELLPHONE_VERIFY . ' WHERE `sVerifyCode`=? AND `sCellPhone`=? AND `iWebsite`=? AND `iVerifyStatus`=0 LIMIT 1';
            $oCellphoneVerify = $this->db->query($sql, array($code, $phoneNo, $iWebsite))->result_array();

            if ($oCellphoneVerify) {
                $data = array('dVerifiedTime' => date('Y-m-d H:i:s'),
                    'iVerifyStatus' => 1
                );
                $condition = array('iVerifyID' => $oCellphoneVerify[0]['iVerifyID']
                );

                $this->executeUpdate(self :: T_POP136_GLOBAL_CELLPHONE_VERIFY, $data, $condition);
                $json['status'] = 1;
            }
            else {
                $json['status'] = -4;
            }
        }

        return $json;
    }

    // 获取手机验证码
    public function cTestGetCode($cellphone)
    {
        $json = $this->json;
        $iWebsite = $this->iWebsites[$this->website];
        if (!preg_match('/^1\d{10}$/im', $cellphone)) {
            $json['info'] = '请输入正确的手机号';
        }
        else {
            // 验证子账号是否存在
            $sql = 'SELECT `sChildAccount` FROM ' . self :: T_FASHION_FASHION_USER_CHILD . ' WHERE `sChildAccount`=? AND `iStatus`=1 LIMIT 1';
            $iTotal = $this->db->query($sql, array($cellphone))->num_rows();

            if ($iTotal > 0) {
                $json['info'] = '个人专属账号已存在';
            }
            else {
                // 验证上次发送时间
                $sql = 'SELECT `dCreateTime`,`iVerifyID`,`sVerifyCode` FROM ' . self :: T_POP136_GLOBAL_CELLPHONE_VERIFY . ' WHERE `sCellPhone`=? AND `iWebsite`=? LIMIT 1';
                $query = $this->db->query($sql, array($cellphone, $iWebsite));
                $iTotal = $query->num_rows();
                $aCellphoneVerify = $query->result_array();

                if ($iTotal > 0 && time() - strtotime($aCellphoneVerify[0]['dCreateTime']) < $this->codeExpireTime) {
                    $json['info'] = '请在' . $this->codeExpireTime . '秒后重新获取';
                }
                else {
                    $captcha = null;
                    if (time() - strtotime($aCellphoneVerify[0]['dCreateTime']) < 3600) {
                        $captcha = $aCellphoneVerify[0]['sVerifyCode'];
                    }
                    else {
                        $captcha = MessageInterface:: randCaptcha();
                    }
                    // 发送验证码
                    $MessageConfig = [
                        'templateId' => 426208, // 模版id
                        'appId' => 9, // 应用id
                        'params' => array($captcha, 60, '4008-210-500'), // 模版参数
                        'to' => strval($cellphone) // 手机号码
                    ];
                    $aResult = MessageInterface:: sendMessage($MessageConfig);
                    $dCreateTime = date('Y-m-d H:i:s');
                    if ($aResult == 200) {
                        // 发送成功
                        if ($iTotal > 0) {
                            $row = array('sVerifyCode' => $captcha,
                                'iVerifyStatus' => 0,
                                'dCreateTime' => $dCreateTime
                            );
                            $condition = array('iVerifyID' => $aCellphoneVerify[0]['iVerifyID'],
                            );
                            $this->executeUpdate(self :: T_POP136_GLOBAL_CELLPHONE_VERIFY, $row, $condition);
                        }
                        else {
                            $row = array('sCellPhone' => $cellphone,
                                'sVerifyCode' => $captcha,
                                'dCreateTime' => $dCreateTime,
                                'iWebsite' => $iWebsite
                            );
                            $this->executeSave(self :: T_POP136_GLOBAL_CELLPHONE_VERIFY, $row);
                        }
                        $json['status'] = 1;
                    }
                    else {
                        $json['info'] = '信息发送失败，请重新发送！';
                    }
                }
            }
        }
        return $json;
    }

    // 新增子账户
    public function createChildAccount($iUid, $sChildAccount, $sPassword, $sName)
    {
        $json = $this->json;
        $this->load->helper('common');
        $sName = $this->db->escape_like_str($sName);
        if (!preg_match('/^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$/im', $sPassword)) {
            // 密码格式不正确
            $json['status'] = -2;
        }
        else {
            // 验证是否通主账号重复（账号+密码）
            $sql = 'SELECT `id` FROM ' . self :: T_POP136_FASHION_USER . ' WHERE `account`=? AND `passwd`=? LIMIT 1';
            $iTotal = $this->db->query($sql, array($sChildAccount, $sPassword))->num_rows();
            if ($iTotal > 0) {
                // 账号密码在主账号中已存在
                $json['status'] = -3;
            }
            else {
                $sql = 'SELECT `sChildID` FROM ' . self :: T_FASHION_FASHION_USER_CHILD . ' WHERE `sChildAccount`=? LIMIT 1';
                $query = $this->db->query($sql, array($sChildAccount));
                $iTotal = $query->num_rows();
                $aChildRow = $query->result_array();
                $condition = array();
                if ($iTotal > 0) {
                    // 更新子账号信息
                    $aRegs = array();
                    $aRegs['iParentID'] = $iUid;
                    $aRegs['iStatus'] = 1;
                    $aRegs['sPassword'] = $sPassword;

                    $condition['sChildID'] = $aChildRow[0]['sChildID'];
                    $rs = $this->executeUpdate(self :: T_FASHION_FASHION_USER_CHILD, $aRegs, $condition);
                    if ($rs) {
                        $this->bindChildAccountOperateLog('主账号操作-再次绑定', $iUid, $sChildAccount);
                        $json['status'] = 1;
                    }
                    else {
                        // 操作错误
                        $json['status'] = -1;
                    }
                }
                else {
                    // 添加子账号
                    $data = array('sChildID' => uuid(),
                        'iParentID' => $iUid,
                        'sChildAccount' => $sChildAccount,
                        'sPassword' => $sPassword,
                        'dCreateTime' => date('Y-m-d H:i:s')
                    );
                    $this->executeSave(self :: T_FASHION_FASHION_USER_CHILD, $data);

                    $json['status'] = 1;

                    $sql = 'SELECT `sChildID` FROM ' . self :: T_FASHION_FASHION_USER_CHILD . ' WHERE `sChildAccount`=? LIMIT 1';
                    $query = $this->db->query($sql, array($sChildAccount));
                    $iTotal = $query->num_rows();
                    $aChildRow = $query->result_array();
                    $condition['sChildID'] = $aChildRow[0]['sChildID'];
                    $this->bindChildAccountOperateLog('主账号操作-绑定', $iUid, $sChildAccount);
                    if ($iTotal > 0) {
                        // 注册赠送100积分
                        $this->integralConsume($aChildRow[0]['sChildID'], 2, 7, 100);
                    }
                }
                // 子账号信息表中填写姓名,已经存在则修改
                $sql = 'SELECT `sChildID` FROM ' . self :: T_FASHION_FASHION_USER_CHILD_INFORMATION . ' WHERE `sChildID`=? LIMIT 1';
                $iTotal = $this->db->query($sql, array($condition['sChildID']))->num_rows();
                if ($iTotal > 0) {
                    $this->executeUpdate(self :: T_FASHION_FASHION_USER_CHILD_INFORMATION, array('sName' => $sName), $condition);
                }
                else {
                    $this->executeSave(self :: T_FASHION_FASHION_USER_CHILD_INFORMATION, array('sChildID' => $condition['sChildID'], 'sName' => $sName));
                }
            }
        }
        return $json;
    }

    public function infoOerfect($sChildAccount, $data)
    {
        // 验证子账号是否存在
        $sql = 'SELECT `sChildID`,`iInfoOerfectStatus` FROM ' . self :: T_FASHION_FASHION_USER_CHILD . ' WHERE `sChildAccount`=? LIMIT 1';
        $query = $this->db->query($sql, $sChildAccount);
        $aCellphoneVerify = $query->result_array();
        // 完善信息所有字段
        $aInformation = array('sChildID', 'sName', 'sGender', 'sEmail', 'sCompanyName', 'iPositionsID');
        $aData = array();
        foreach ($aInformation as $sField) {
            if ($sField == 'sGender' && in_array($data[$sField], array(1, 2))) {
                $aData[$sField] = $data[$sField];
            }
            elseif ($sField == 'iPositionsID' && $data[$sField] > 0) {
                $aData[$sField] = $data[$sField];
            }
            elseif ($data[$sField]) {
                $aData[$sField] = $data[$sField];
            }
        }
        // 是否为完善信息状态，送100积分
        $iInfoOerfectStatus = false;
        if (count($aInformation) == count($aData) && $aCellphoneVerify[0]['iInfoOerfectStatus'] == 0) {
            $iInfoOerfectStatus = true;
        }

        $sql = 'SELECT `sChildID` FROM ' . self :: T_FASHION_FASHION_USER_CHILD_INFORMATION . ' WHERE `sChildID`=? LIMIT 1';
        $query = $this->db->query($sql, $aCellphoneVerify[0]['sChildID']);
        $iTotal = $query->num_rows();
        if ($iTotal > 0) {
            // 信息已存在做更新操作
            $condition = array();
            $condition['sChildID'] = $aCellphoneVerify[0]['sChildID'];
            $this->executeUpdate(self :: T_FASHION_FASHION_USER_CHILD_INFORMATION, $aData, $condition);
        }
        else {
            // 信息不存在做新增操作
            $this->executeSave(self :: T_FASHION_FASHION_USER_CHILD_INFORMATION, $aData);
        }
        if ($iInfoOerfectStatus) {
            // 增加100积分
            $bResult = $this->integralConsume($aCellphoneVerify[0]['sChildID'], 2, 5, 100);
            if ($bResult) {
                $sql = 'UPDATE ' . self :: T_FASHION_FASHION_USER_CHILD . ' SET `iInfoOerfectStatus`=1 WHERE `sChildID`=？ LIMIT 1';
                $this->db->query($sql, $aCellphoneVerify[0]['sChildID']);
            }
        }
        return true;
    }

    // 发送手机验证码 返回发送状态及验证码值
    public function sendPhoneCode($cellphone, $message = null, $captcha = null)
    {
        include_once DOCUMENT_ROOT . '/class/Phone.class.php';
        $Phone = new Phone();
        $onLine = true; //true:正式环境  false:测试环境
        if ($onLine) {
            // 正式环境
            // 如果之前验证或者已经存在则发送原来的验证码
            $status = $Phone->sendMessage($cellphone, $message, $captcha);
            $captcha = $Phone->getCaptcha();
        }
        else {
            // 测试环境
            $status = 200;
            $captcha = '888888';
        }
        return
            array('status' => $status,
                'code' => $captcha
            );
    }

    /**
     * 获取子账户基本信息
     *
     * @param string $fields
     * @param  $condition
     * @param string $flag
     * @return mixed
     */
    public function getChildInfo($condition, $flag = "", $fields = "*")
    {
        if ($flag == "base") {
            $table = self :: T_FASHION_FASHION_USER_CHILD_INFORMATION;
        }
        else {
            $table = self :: T_FASHION_FASHION_USER_CHILD;
        }
        $query = $this->db()->select($fields)->from($table)->where($condition)->get();
        $totalCount = $this->db()->where($condition)->count_all_results($table);
        if ($totalCount == 1) {
            $result = $query->row_array();
        }
        else {
            $result = $query->result_array();
        }
        return $result;
    }

    /**
     * 获取关联子账号列表
     *
     * @param  $condition 查询条件，关联数组
     * @param  $totalCount 总数用于分页
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getChildList($condition, &$totalCount, $page = 1, $pageSize = 10, $search = '')
    {
        $offSet = ($page - 1) * $pageSize;
        if ($search) {
            $search = htmlspecialchars($search);
            if ($condition) {
                $where_sql = [];
                foreach ($condition as $key => $val) {
                    $where_sql[] = $key . '=' . $val;
                }
                $where = implode(' AND ', $where_sql);
                $where .= " AND (a.sChildAccount like '%" . $this->db()->escape_like_str($search) . "%' OR b.sName like '%" . $this->db()->escape_like_str($search) . "%')";
            }
            else {
                $where = "(a.sChildAccount like " . $this->db()->escape_like_str('%' . $search . '%') . " OR b.sName like " . $this->db()->escape_like_str('%' . $search . '%') . ")";
            }

            $result = $this->db->select('a.sChildAccount,a.iIntegraNumber,a.dCreateTime,b.sName')->from(self :: T_FASHION_FASHION_USER_CHILD . ' as a')->join(self ::T_FASHION_FASHION_USER_CHILD_INFORMATION . ' as b', 'a.sChildID = b.sChildID', 'left')->where($where)->order_by('dCreateTime', 'DESC')->limit($pageSize, $offSet)->get()->result_array();
            // echo $this->db()->last_query();
            $totalCount = $this->db->select('count(*) as total')->from(self :: T_FASHION_FASHION_USER_CHILD . ' as a')->join(self ::T_FASHION_FASHION_USER_CHILD_INFORMATION . ' as b', 'a.sChildID = b.sChildID', 'left')->where($where)->get()->row_array();
            $totalCount = $totalCount['total'];
            $this->ChildAll = $totalCount;
            $this->ChildList = $this->db->select('sChildAccount,iIntegraNumber,dCreateTime')->from(self :: T_FASHION_FASHION_USER_CHILD)->where($condition)->order_by('dCreateTime', 'DESC')->get()->result_array();
        }
        else {
            $result = $this->db->select('a.sChildAccount,a.iIntegraNumber,a.dCreateTime,b.sName')->from(self :: T_FASHION_FASHION_USER_CHILD . ' as a')->join(self ::T_FASHION_FASHION_USER_CHILD_INFORMATION . ' as b', 'a.sChildID = b.sChildID', 'left')->where($condition)->order_by('dCreateTime', 'DESC')->limit($pageSize, $offSet)->get()->result_array();
            $totalCount = $this->db->where($condition)->count_all_results(self :: T_FASHION_FASHION_USER_CHILD);
            $this->ChildAll = $totalCount;
            $this->ChildList = $this->db->select('sChildAccount,iIntegraNumber,dCreateTime')->from(self :: T_FASHION_FASHION_USER_CHILD)->where($condition)->order_by('dCreateTime', 'DESC')->get()->result_array();
        }
        return $result;
    }

    /**
     * 积分消费记录
     * POP服装积分个人专享服务( 1：下载、2：充值、3：推荐VIP、4：每日首次登陆、5：完善信息、6：分配积分、7：注册子账号 、8：推荐非VIP)
     *
     * @parameter  $iAccountID 子账号ID
     * @parameter  $sAccountType 子账号类型
     * @parameter  $iService 消费类型
     * @parameter  $iIntegralConsume 消费积分数量
     */
    public function integralConsume($iAccountID, $sAccountType, $iService, $iIntegralConsume)
    {
        $popIntegralConsumeTypes = getCommonData('common_data', 'popIntegralConsumeTypes');
        $aLog = array();
        if (!in_array($sAccountType, array('1', '2'))) {
            return false;
        }
        if (!array_key_exists($iService, $popIntegralConsumeTypes)) {
            return false;
        }
        if (!is_numeric($iIntegralConsume)) {
            return false;
        }
        // 开启一个事务
        //$this -> db -> trans_begin();
        $sUpSql = '';
        if ($sAccountType == 2) {
            // 子账号
            $sUpSql = 'UPDATE ' . self :: T_FASHION_FASHION_USER_CHILD . ' SET `iIntegraNumber`=`iIntegraNumber`+? WHERE `sChildID`=? LIMIT 1';
        }
        else {
            // 主账号
            $sUpSql = 'UPDATE ' . self :: T_FASHION_FASHION_USER . ' SET `iIntegralTotalNumber`=`iIntegralTotalNumber`+? WHERE `id`=? LIMIT 1';
        }
        $bFlag1 = $this->db->query($sUpSql, array($iIntegralConsume, $iAccountID));

        $aLog['iService'] = $iService;
        $aLog['iIntegralConsume'] = $iIntegralConsume;
        $aLog['iAccountID'] = $iAccountID;
        $aLog['sAccountType'] = $sAccountType;
        $aLog['dConsumeTime'] = date('Y-m-d H:i:s');
        $bFlag2 = $this->executeSave(self :: T_INTEGRAL_CONSUME, $aLog);
        if ($bFlag1 && $bFlag2) {
            // 提交一个事务
            //$this -> db -> trans_commit();
            return true;
        }
        else {
            // 事务回滚
            //$this -> db -> trans_rollback();
            return false;
        }
    }

    /**
     * 订单生成处理
     *
     * @parameter  $iAccountID 子账号ID
     * @parameter  $sAccountType 子账号类型
     * @parameter  $iPayType 支付类型 1-支付宝网银支付 2-支付宝余额支付
     * @parameter  $fPayMoney 支付金额
     * @parameter  $iRechargeNumber 购买积分数
     * @parameter  $sRemarks 备注
     */
    public function orderGenerated($iAccountID, $sAccountType, $iPayType, $fPayMoney, $iRechargeNumber, $sRemarks = '')
    {
        $json = $this->json;
        $json['status'] = -1;
        $aHeader = $aDetails = array();
        $aHeader['dOrderTime'] = date('Y-m-d H:i:s');
        if (!$iAccountID || !$sAccountType) {
            $json['info'] = '登陆信息异常！';
        }
        else {
            $aHeader['iAccountID'] = $iAccountID;
            $aHeader['sAccountType'] = $sAccountType;
            if (!in_array($iPayType, array('1', '2'))) {
                $json['info'] = '请选择正确的支付方式';
            }
            else {
                $fPayMoney = floatval($fPayMoney);
                $iRechargeNumber = intval($iRechargeNumber);
                $checkfPayMoney = floatval($iRechargeNumber / 10);
                if ($fPayMoney != $checkfPayMoney) {
                    $json['info'] = '支付金额有误！';
                }
                else {
                    $aHeader['iPayType'] = $iPayType;
                    $aHeader['fPayMoney'] = $fPayMoney;
                    $aDetails['fPayMoney'] = $fPayMoney;
                    $aDetails['iRechargeNumber'] = $iRechargeNumber;
                    $aDetails['sRemarks'] = $sRemarks;
                    // 开启一个事务
                    $this->OpGlobalDb->begin();
                    $iFlag1 = $iFlag2 = 0;
                    $iFlag1 = $this->executeSave(self :: T_ORDER_HEADER, $aHeader, 'l', false);
                    if ($iFlag1) {
                        $aDetails['sOrderNumber'] = $iFlag1;
                        $iFlag2 = $this->executeSave(self :: T_ORDER_DETAILS, $aDetails, 'l', false);
                    }
                    if ($iFlag1 && $iFlag2) {
                        $json['status'] = 1;
                        $json['info'] = $iFlag1; //返回订单号
                        $this->OpGlobalDb->commit();
                    }
                    else {
                        $json['info'] = '订单生成失败！';
                        $this->OpGlobalDb->rollback();
                    }
                }
            }
        }
        return $json;
    }

    /**
     * 获取子账号推荐链接
     *
     * @parameter  $iAccountID 子账号ID
     */
    public function getRecommendLink($iAccountID)
    {
        $sLink = 'https://www.pop-fashion.com/register.php?_a=new-account';
        $sSql = 'SELECT `iRecommendID` , `sRecommendLink` FROM ' . self :: T_POP136_GLOBAL_RECOMMEND . ' WHERE `sChildID`="' . mysql_escape_string($iAccountID) . '" AND `iWebsite`=1';
        $iTotal = $this->OpGlobalDb->select($sSql, $aRecommend);
        if ($iTotal > 0 && $aRecommend[0]['sRecommendLink']) {
            return $aRecommend[0]['sRecommendLink'];
        }
        else {
            $aRecommondLog = array();
            $aRecommondLog['sChildID'] = $iAccountID;
            $aRecommondLog['sRecommendLink'] = '';
            $aRecommondLog['iWebsite'] = 1;
            // 开启一个事务
            $this->OpGlobalDb->begin();
            $iRecommendID = $this->executeSave(self :: T_POP136_GLOBAL_RECOMMEND, $aRecommondLog, 'l', false);

            $iAffectedRows = 0;
            if ($iRecommendID > 0) {
                // 机密方式 推荐ID+#POP#+key+#POP#+子账号id
                $sLink .= '&RL=' . md5($iRecommendID . '#POP#' . POP_GLOBAL_KEYS . '#POP#' . $iAccountID) . '&RID=' . $iRecommendID . '&UID=' . $iAccountID;

                $aRecommondUpdateLog = array();
                $aRecommondUpdateLog['iRecommendID'] = $iRecommendID;
                $aRecommondUpdateLog['sRecommendLink'] = $sLink;
                $iAffectedRows = $this->executeUpdate(self :: T_POP136_GLOBAL_RECOMMEND, $aRecommondUpdateLog, 'iRecommendID', false);
            }
            if ($iAffectedRows > 0) {
                // 提交一个事务
                $this->OpGlobalDb->commit();
                return $sLink;
            }
            else {
                // 事务回滚
                $this->OpGlobalDb->rollback();
                return false;
            }
        }
    }

    /**
     * 获取子账号推荐链接
     *
     * @parameter  $sKey 客户端传入加密串
     * @parameter  $iRecommendID 推荐ID
     * @parameter  $iAccountID 子账号ID
     * @parameter  $iRegisterID 推荐注册生成主账号ID （CRM开通VIP送积分用）
     */
    public function disposeRecommendLink($sKey, $iRecommendID, $iAccountID, $iRegisterID)
    {
        // 生成验证推荐链接加密串
        $sKeyCheck = md5($iRecommendID . '#POP#' . POP_GLOBAL_KEYS . '#POP#' . $iAccountID);
        if ($sKeyCheck == $sKey) {
            $aRecommondLog = array();
            $aRecommondLog['iRecommendID'] = intval($iRecommendID);
            $aRecommondLog['sChildID'] = $iAccountID;
            $aRecommondLog['iRegisterID'] = intval($iRegisterID);
            $aRecommondLog['iWebsite'] = 1;
            $iRecommendID = $this->executeSave(self :: T_POP136_GLOBAL_RECOMMEND_RESULT, $aRecommondLog);
            if ($iRecommendID > 0) {
                // 送推荐积分 50
                return $this->integralConsume($iAccountID, 2, 8, 50);
            }
            else {
                // 记录推送结果表失败
                return false;
            }
        }
        else {
            // 推荐信息错误
            return false;
        }
    }

    /**
     *
     * @param  $userId 用户ID
     * @param  $status 分享状态
     * @param  $page 页数
     * @param  $pagesize 分页大小
     * @param  $total 总数
     * @return mixed
     */
    function getShareList($condition, &$totalCount, $page = 1, $pageSize = 10)
    {
        $offSet = ($page - 1) * $pageSize;
        $result = $this->db->select('*')->from(self :: T_POP_SHARE_LOG)->where($condition)->order_by('id', 'DESC')->limit($pageSize, $offSet)->get()->result_array();
        $totalCount = $this->db->where($condition)->count_all_results(self :: T_POP_SHARE_LOG);
        return $result;
    }

    /**
     *
     * @param  $condition 取消/删除 分享记录 的条件
     * @return integer 返回：影响行数
     */
    function opShare($condition)
    {
        $this->db->set('status', 'approvalSt+1', false)->where($condition)->update(self :: T_POP_SHARE_LOG);
        $rs = $this->db->affected_rows();
        return $rs;
    }

    /**
     * 删除记录
     *
     * @param  $shareId 记录的ID
     * @return integer 影响行数
     */
    public function deleteShareLog($tableName, $shareId)
    {
        $deleteSql = "DELETE FROM " . $tableName . " WHERE `id`=? LIMIT 1";
        $this->db->query($deleteSql, array($shareId));
        $rs = $this->db->affected_rows();
        // $this->db->close();
        return $rs;
    }

    /**
     *
     * @param  $condition
     */
    public function getAccountInfo($condition, $fields = '*')
    {
        $result = $this->db->from(self :: T_FASHION_FASHION_USER)->where($condition)->select($fields)->get()->result_array();
        return $result;
    }

    /**
     * 获取积分明细列表
     *
     * @param  $condition 查询条件，关联数组
     * @param  $totalCount 总数，用于分页
     * @param int $page
     * @param int $pageSize
     * @return array
     */
    public function getIntegralDetailList($condition, &$totalCount, $page = 1, $pageSize = 10)
    {
        $offSet = ($page - 1) * $pageSize;
        $result = $this->db->select('iService,iIntegralConsume,dConsumeTime')->from(self :: T_INTEGRAL_CONSUME)->where($condition)->order_by('dConsumeTime', 'DESC')->limit($pageSize, $offSet)->get()->result_array();
        $totalCount = $this->db->where($condition)->count_all_results(self :: T_INTEGRAL_CONSUME);
        return $result;
    }

    // 关联账号管理-密码重置
    public function resetPwd($phone)
    {
        if (empty($this->UserInfo)) return false;
        $pwd = mt_rand(100000, 999999);
        $this->benchmark->mark('selectzhu');
        // 获取主账号信息
        $sqlMain = "SELECT * FROM " . self :: T_FASHION_FASHION_USER . " where account=?";
        $query = $this->db->query($sqlMain, $phone);
        $this->benchmark->mark('selectzhuEnd');
        $accountMain = $query->row_array();
        if ($accountMain) {
            if ($accountMain['passwd'] == $pwd) {
                $pwd = mt_rand(100000, 999999);
            }
        }
        $this->benchmark->mark('selectzi');
        // 获取子账号信息
        $sqlSub = "SELECT * FROM " . self :: T_FASHION_FASHION_USER_CHILD . " WHERE sChildAccount=?";
        $accountChild = $this->db->query($sqlSub, array($phone));
        $this->benchmark->mark('selectziEnd');
        if ($accountChild) {
            $this->benchmark->mark('updatezi');
            $sql = "UPDATE " . self :: T_FASHION_FASHION_USER_CHILD . " SET iStatus=1,sPassword=? WHERE sChildAccount=?";
            $rs = $this->db->query($sql, array($pwd, $phone));
            $this->benchmark->mark('updateziEnd');
            if ($rs) {
                return $pwd;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    /**
     * 解除子账号的绑定关系
     *
     * @param $account
     * @param $operateSource
     * @return bool
     */
    public function unsetAssociate($account, $operateSource)
    {
        if (empty($this->UserInfo)) return false;
        $bid = $this->UserInfo['id'];
        $sql = "UPDATE " . self :: T_FASHION_FASHION_USER_CHILD . " SET iStatus=-1 where sChildAccount=? AND iParentID=?";
        $res = $this->db->query($sql, array($account, $bid));

        $this->bindChildAccountOperateLog($operateSource, $bid, $account);

        return $res;
    }

    // 分配
    public function sendScore($score, $account)
    {
        if (empty($this->UserInfo)) return false;

        $bid = $this->UserInfo['id'];
        $Flag = $this->integralConsume($bid, 1, 6, -$score);
        if ($Flag) {
            $sql = "select * FROM " . self :: T_FASHION_FASHION_USER_CHILD . " where sChildAccount=?";
            $query = $this->db->query($sql, $account);
            $data = $query->result_array();
            if ($data) {
                $Flag = $this->integralConsume($data[0]['sChildID'], 2, 6, $score);
                if ($Flag) {
                    return true;
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    // 登陆的时候验证用户的账号类型.
    private function callPop136Login($aUserLoginMsg)
    {
        if (!$aUserLoginMsg) {
            return false;
        }
        $aReturn = $this->getLoginResult($aUserLoginMsg);
        $result = array(
            'user_info' => '', // 用户信息
            'msg' => '' // 错误提示信息
        );

        if ($aReturn['iAccountType'] == -1) {
            // 登录验证失败
            $result['msg'] = 0;
        }
        else {
            // 登陆成功,更新登陆时间
            $result['user_info'] = $aReturn;
            $result['msg'] = 1;
            $update_data['last_login_time'] = date('Y-m-d H:i:s');
            $this->update_result(self :: T_POP136_FASHION_USER, $update_data, ['id' => $aReturn['id']]);
        }
        return $result;
    }

    // 获取登陆的所需数据
    private function getLoginResult($aUserLoginMsg)
    {
        $return = array('iAccountType' => -1);
        if (!$aUserLoginMsg) {
            return $return;
        }
        $aRowMain = array();
        // 查询主账号
        $aRowMain = $this->getSearchLists(
            self::T_POP136_FASHION_USER,
            [
                array('account', 'eq', $aUserLoginMsg['account']),
                array('passwd', 'eq', $aUserLoginMsg['passwd']),
            ],
            array(), 1, 1, '*'
        );
        if ($aRowMain) {
            $aRowMain['iAccountType'] = 1;
            return $aRowMain;
        }
        // 查询子账号
        $childrow = $this->getSearchLists(
            self :: T_FASHION_FASHION_USER_CHILD,
            [
                ['sChildAccount', 'eq', $aUserLoginMsg['account']],
                ['sPassword', 'eq', $aUserLoginMsg['passwd']],
            ],
            array(), 1, 1,
            "sChildID,sChildAccount,iIntegraNumber,iParentID,iStatus"
        );
        if ($childrow) {
            if ($childrow['iStatus'] == 1) {
                $aRowMain = $this->getSearchLists(
                    self :: T_POP136_FASHION_USER,
                    [['id', 'eq', $childrow['iParentID']]],
                    array(), 1, 1,
                    '*'
                );

                if ($aRowMain) {
                    $aRowMain['iAccountType'] = 2;
                    $aRowMain['sChildID'] = $childrow['sChildID'];
                    $aRowMain['sChildAccount'] = $childrow['sChildAccount'];
                    $aRowMain['iIntegraNumber'] = $childrow['iIntegraNumber'];
                    $aRowMain['iStatus'] = $childrow['iStatus'];
                }
            }
            else {
                $aRowMain['iAccountType'] = 2;
                $aRowMain['sChildID'] = $childrow['sChildID'];
                $aRowMain['sChildAccount'] = $childrow['sChildAccount'];
                $aRowMain['iIntegraNumber'] = $childrow['iIntegraNumber'];
                $aRowMain['iStatus'] = $childrow['iStatus'];
            }
            return $aRowMain;
        }
        return $return;
    }


    // 插入用户记录
    public function selectOrInsertPopUser($data)
    {
        // 查询主账号
        $table = self :: T_FASHION_FASHION_USER;
        $field = 'id';
        $pop136userwhere[] = array('account', 'eq', $data['account']);
        $aRowMain = $this->getSearchLists($table, $pop136userwhere, array(), 1, 1, $field);

        if (!$aRowMain) {
            // 插入记录
            $this->insert_result($table, $data);
        }
    }

    /**
     * + spread 消息中间件
     */
    public function connectSpread()
    {
        if (is_null(self:: $spread) || !(self:: $spread instanceof OpPopAsyncMsg)) {
            self:: $spread = new OpPopAsyncMsg();
        }
        return self:: $spread;
    }

    /*
	  + IP限制操作
	  + $user_id	主账号ID
	  + $ip			当前登录IP
	  + $rwx		r-读 ， w-写
	  + $refresh	重新获取IP限制记录数
	*/
    public function ip_limits($user_id, $ip, $rw = 'w', $refresh = false)
    {

        if (!$user_id || !$ip) {
            return false;
        }

        /*
		  + 判断在线IP个数
		  + is_Allow_Two_Ip：2-不限制 0-1，1-2，3-3，4-4，5-5
		*/
        $this->load->driver('cache');

        $key = self::FM_TEM_IP_LIMIT_MEMCACHE_KEY . md5($user_id);
        $result = $this->cache->memcached->get($key);
        if (!$result || $refresh) {
            $result = $this->getSearchLists(
                self::T_FASHION_FASHION_USER,
                [
                    ['id', 'eq', $user_id],
                ],
                array(), 1, 1, 'id , is_Allow_Two_Ip'
            );
            $this->cache->memcached->save($key, $result, 86400);
        }

        $ip_number = intval($result['is_Allow_Two_Ip']);

        if ($ip_number == 2) {
            //不限制
            $ip_number = 0;
        }
        else {
            if ($ip_number == 1 || $ip_number == 0) {
                $ip_number++;
            }
        }

        //0 不限制跳过
        if ($ip_number == 0) {
            return true;
        }

        //记录当前在线IP信息[  ]
        //$s_ip_mem_key = self::FM_TEM_IP_LIMIT_MEMCACHE_KEY . md5( 'IPS_'.$user_id );
        //$a_ips = $this->cache->memcached->get( $s_ip_mem_key );
        //if( !$a_ips || $refresh ) {
        //获取当前IP登录情况
        $a_ips = $this->getSearchLists(
            self::T_POP136_T_VISIT_IP,
            [
                ['i_user_id', 'eq', $user_id],
                ['i_site', 'eq', 1]
            ],
            array(), 1, 1, '*'
        );

//        $sql = "select * from " . self::T_POP136_T_VISIT_IP . " where i_user_id = '{$user_id}' and i_site = 1 limit 1";
//        OpGlobalDb::getInstance('pop136')->select($sql, $a_ips);
//        $a_ips = $a_ips[0];

        //$this->cache->memcached->save( $s_ip_mem_key , $a_ips , 3600 );
        //}

        if ($rw == 'w') {
            //登录时执行写操作，根据限制数处理允许在线IP
            $_ips = [];
            if ($a_ips) {
                /*
				  + 更新操作  IP => 时间戳
				  + [
						192.168.20.93 => 1234567890,
						192.168.20.94 => 1234567890
					]
				*/
                //逻辑1.如果记录总在线IP数多于允许值如何处理？
                //逻辑2.如果当前IP属于在线IP中，算最新更新
                $_ips = json_decode($a_ips['s_ips'], true);
                $_ips[$ip] = time();
                asort($_ips);

                //处理后当前在线个数
                $_ip_number = count($_ips);
                if ($_ip_number > $ip_number) {
                    //当前在线数大于允许值，舍弃最早登录的IP
                    $_ips = array_slice($_ips, abs($ip_number - count($_ips)));
                }
                $aff = $this->executeUpdate(
                    self::T_POP136_T_VISIT_IP,
                    [
                        's_ip' => $ip,
                        's_ips' => json_encode($_ips)
                    ],
                    ['id' => $a_ips['id']]
                );

//                $s_ips = json_encode($_ips);
//                $sql = "update " . self::T_POP136_T_VISIT_IP . " set s_ip = '{$ip}', s_ips = '{$s_ips}' where id = '{$a_ips['id']}'";
//                $aff = OpGlobalDb::getInstance('pop136')->mod($sql);
            }
            else {
                $_ips[$ip] = time();
                $aff = $this->executeSave(
                    self::T_POP136_T_VISIT_IP,
                    [
                        'i_user_id' => $user_id,
                        's_ip' => $ip,
                        's_ips' => json_encode($_ips),
                        'i_site' => 1
                    ]
                );
//                $s_ips = json_encode($_ips);
//                $sql = "insert into " . self::T_POP136_T_VISIT_IP . " set s_ip = '{$ip}', s_ips = '{$s_ips}', i_user_id = '{$user_id}', i_site = 1";
//                $aff = OpGlobalDb::getInstance('pop136')->mod($sql);
            }
            return $aff ? true : false;
        }
        elseif ($rw == 'r') {
            //判断是否被其他IP挤掉
            $_ips = json_decode($a_ips['s_ips'], true);
            return array_key_exists($ip, $_ips) ? true : $a_ips['s_ip'];
        }
        return true;
    }

    /*
	  + 用户登录的方法
	  + @$aLogin 登录信息
	*/
    public function dealLogin($aLogin)
    {
        //清除当前浏览器登录用户Memcache缓存
        $this->load->driver('cache');
        $aCookieUserInfo = get_cookie_value();
        if ($aCookieUserInfo) {
            $iUid = intval($aCookieUserInfo['id']);
            //IP限制缓存清除
            $this->cache->memcached->delete(self::FM_TEM_IP_LIMIT_MEMCACHE_KEY . md5($iUid));
            $this->cache->memcached->delete(self::FM_TEM_IP_LIMIT_MEMCACHE_KEY . md5('IPS_' . $iUid));

            //权限套餐缓存清除
            $this->cache->memcached->delete(self::FM_TEM_PRIVILEGE_MEMCACHE_KEY . md5($iUid));
        }

        $soap_pwd = POP_GLOBAL_KEYS;

        //1-非客户端 , 2-新客服端
        $iClientType = $aLogin['hidEncryptCode'] && !$aLogin['hidRandCode'] ? 2 : 1;

        if (intval($this->input->get_post('return')) == 1 && $iClientType == 2) {
            $account = $aLogin['account'];
            $passwd = $aLogin['passwd'];
            $array = array(
                "account" => $account,
                "password" => $passwd,
                "website" => '1',
            );
            ksort($array);
            $clientLoginToken = md5(POP_GLOBAL_KEYS . json_encode($array));
            $query = 'account=' . urlencode($account) . '&password=' . urlencode($passwd) . '&clientLoginToken=' . urlencode($clientLoginToken);
            header('Location:/member/clientLogin/?' . $query);
            exit;
        }

        if ($aLogin['is_ajax_request'] && $aLogin['method'] == 'post') {
            /**
             * + isajax : true 新客户端登录
             * + isajax : false或其他ajax登录 （ 记住登录状态 ）
             */
            //自动登录用户信息
            $sAutomaticLogin = $aLogin['encryption'];
            if ($sAutomaticLogin) {
                $aAutomaticLogin = EncryptionDeciphering($sAutomaticLogin, false); //解密字符串
                $splitP = explode('#POP#', $aAutomaticLogin);
                // 获取主账号或子账号
                $m_id = $splitP[0];
                $m_account = $splitP[1];
                $m_password = $splitP[2];

                if (is_numeric($m_id) && intval($m_id) > 0) {
                    //主账号自动登录
                    // 查询用户信息
                    $a_Tmp_User = $this->getSearchLists(
                        self::T_FASHION_FASHION_USER,
                        [
                            ['id', 'eq', $m_id],
                            ['account', 'eq', $m_account]
                        ],
                        array(), 1, 1,
                        'id , account , passwd , client_type'
                    );

                    if ($a_Tmp_User) {
                        $aLogin['account'] = $a_Tmp_User['account'];
                        $aLogin['passwd'] = $a_Tmp_User['passwd'];

                        // 客户端直接记住密码
                        $aLogin['hidEncryptCode'] = '';
                        if ($a_Tmp_User['client_type'] == 2) {
                            // 已绑定客户端
                            // 查询客户端信息，模拟客户端登陆信息
                            $clientLog = $this->getSearchLists(
                                self::T_CLIENT_LOG,
                                [
                                    ['user_account', 'eq', $a_Tmp_User['account']]
                                ],
                                array(), 1, 1,
                                'mac,disk,cpu'
                            );
                            if ($clientLog) {
                                $aLogin['hidEncryptCode'] = base64_encode('173,' . $clientLog['mac'] . ',' . $clientLog['disk'] . ',' . $clientLog['cpu']);
                            }
                        }
                    }
                    else {
                        // 保持登陆清除
                        $_KeepPassword = get_cookie('_KeepPassword');
                        if ($_KeepPassword) {
                            delete_cookie('_KeepPassword');
                            delete_cookie('user_info');
                        }
                    }
                }
                else {
                    //子账号自动登陆

                    // 查询用户信息
                    $aUserInfo = $this->getSearchLists(
                        self :: T_FASHION_FASHION_USER_CHILD,
                        [
                            ['sChildID', 'eq', $m_id],
                            ['sChildAccount', 'eq', $m_account],
                            ['sPassword', 'eq', $m_password],
                            ['iStatus', 'eq', 1]
                        ],
                        array(), 1, 1,
                        'sChildID,iParentID,sChildAccount,sPassword'
                    );
                    if ($aUserInfo) {
                        // 客户端直接记住密码
                        $aLogin['account'] = $aUserInfo['sChildAccount'];
                        $aLogin['passwd'] = $aUserInfo['sPassword'];
                        $aMainUserInfo = $this->getSearchLists(self::T_FASHION_FASHION_USER, ['id', 'eq', $aUserInfo['iParentID']], array(), 1, 1, 'client_type , account ');
                        if ($aMainUserInfo['client_type'] == 2) {
                            // 查询客户端信息，模拟客户端登陆信息
                            $clientLog = $this->getSearchLists(self::T_CLIENT_LOG, ['user_account', 'eq', $aMainUserInfo['account']], array(), 1, 1, 'mac,disk,cpu');
                            if ($clientLog) {
                                $aLogin['hidEncryptCode'] = base64_encode('173,' . $clientLog['mac'] . ',' . $clientLog['disk'] . ',' . $clientLog['cpu']);
                            }
                        }
                        else {
                            $aLogin['hidEncryptCode'] = '';
                        }
                    }
                    else {
                        // 清除自动登录信息
                        $_KeepPassword = get_cookie('_KeepPassword');
                        if ($_KeepPassword) {
                            delete_cookie('_KeepPassword');
                            delete_cookie('user_info');
                        }
                    }
                }
            }
        }

        //如果客户端登录或自动登陆跳过验证码 直接读取cookie中验证码
        if (empty($sAutomaticLogin) && $iClientType != 2 && !$aLogin['bKipCode']) {
            $pop_uid = $this->input->cookie("POP_UID");
            $this->load->library("SlideVerification");
            $result = $this->slideverification->checkToken($aLogin["dragtoken"], $pop_uid, "login");
            if (!$result) {
                echo 4;
                exit;
            }
        }

        //验证账号
        if (!$aLogin['account']) {
            echo $iClientType == 2 ? $this->loginError[1] : 1;
            exit;
        }

        //验证密码
        if (!$aLogin['passwd']) {
            echo $iClientType == 2 ? $this->loginError[2] : 2;
            exit;
        }
        //用户名密码
        $account = $aLogin['account'];
        $passwd = $aLogin['passwd'];

        // 网络通信验证，发送请求到主库验证账号
        $result = $this->callPop136Login([
            'account' => $account,
            'passwd' => $passwd
        ]);

        if ($result['msg'] == 0) {
            echo $iClientType == 2 ? $this->loginError[5] : 5;
            exit;
        }

        //客户IP
        $client_ip = $aLogin['client_ip'];

        // 登陆成功 账号类型 1主账号 2子账号
        $iAccountType = $result['user_info']['iAccountType'];
        $aMainUser = array();
        $aMainUser = $this->getSearchLists(self :: T_FASHION_FASHION_USER, [['id', 'eq', $result['user_info']['id']]], array(), 1, 1, '*');

        if ($iAccountType == 1) {

            if (!$aMainUser) {
                $data = array();
                $data['id'] = $result['user_info']['id'];
                $data['tel'] = $result['user_info']['tel'];
                $data['create_time'] = $result['user_info']['create_time'];
                $data['city'] = $result['user_info']['city'];
                $data['flag'] = $result['user_info']['flag'];
                $data['province'] = $result['user_info']['province'];
                $data['country'] = $result['user_info']['country '];
                $data['nme'] = $result['user_info']['nme'];

                $data['account'] = $result['user_info']['account'];
                $data['passwd'] = $result['user_info']['passwd'];
                $data['user_from'] = $result['user_info']['user_from'];
                $data['city'] = $result['user_info']['city'];
                $data['email'] = $result['user_info']['email'];
                $data['address'] = $result['user_info']['address'];
                $data['ip'] = $result['user_info']['ip'];
                $data['card'] = $result['user_info']['card'];
                $data['com_fax'] = $result['user_info']['com_fax'];
                $data['com_name'] = $result['user_info']['com_name'];
                $data['brand_name'] = $result['user_info']['brand_name'];
                $data['com_type'] = $result['user_info']['com_type'];
                $data['com_web'] = $result['user_info']['com_web'];
                $data['mobile'] = $result['user_info']['mobile'];
                $data['qqmsn'] = $result['user_info']['qqmsn'];
                $data['gender'] = $result['user_info']['gender'];
                $data['status'] = 0;
                $data['trail'] = 0;
                $data['count_num'] = 0;
                $data['client'] = 0;
                $data['vip_type'] = 1;

                $totalcount = 0;
                $areaCodes = $this->getAreaCodes($data['mobile']);
                $area_id = $areaCodes['FashionAreaId'];
                $data['area_id'] = $area_id;
                // 判断该用户是否存在，不存在则插入
                $this->selectOrInsertPopUser($data);
                // 这段代码用于自动分配到指定ID业务员下
                $pidtt = get_cookie('pidtt');

                /*
                if ($pidtt) {
                    $saler_id = $pidtt;
                    $table = SELF :: T_FASHION_FASHION_ADMIN_USER;
                    $field = '*';
                    $admin_where[] = array('id' => $saler_id);
                    $xxsaler = $this->getSearchLists($table, $admin_where, array(), 1, 1, $field);
                    if ($xxsaler['id'] && $xxsaler['sale_manager_id']) {
                        $table = SELF :: T_FASHION_FASHION_AREA;
                        $field = '*';
                        $fashion_area_where[] = array('id' => $saler_id);
                        $arrxx = $this->getSearchLists($table, $fashion_area_where, array(), 1, 1, $field);
                        if ($arrxx) {
                            $areaid = $arrxx[0]['id'];
                            $table = self :: T_FASHION_FASHION_CLIENT;
                            $arr_client = array('create_time' => date('Y-m-d H:i:s'),
                                'id2' => $saler_id,
                                'id3' => $data['id']
                            );
                            // 加入事务控制
                            $this->db->trans_begin();
                            $objmanager->add_client($arr_client, $table, $arrcon);
                            $insert_id = $this->insert_result($table, $arr_client);
                            $table = self :: T_FASHION_FASHION_USER;
                            $update_data['client'] = 1;
                            $update_data['area_id'] = $areaid;
                            $update_where['id'] = $data['id'];
                            $affected_rows = $this->update_result($table, $update_data, $update_where);
                            if ($insert_id && $affected_rows) {
                                // 提交事务
                                $this->db->trans_commit();
                            } else {
                                // 回滚
                                $this->db->trans_rollback();
                            }
                        }
                    }
                }
                */
                // 直接分配到管理非主营业务的业务员
                if ($result['user_info']['user_from'] != 1) {
                    $table = self :: T_FASHION_FASHION_CLIENT;
                    $arr_client = array('create_time' => date('Y-m-d H:i:s'),
                        'id2' => 456,
                        'id3' => $data['id'],
                    );
                    $this->insert_result($table, $arr_client);
                    // 调主营业务网站的借口修改其他时间
                    $user_id = $data['id'];
                    $time = time();
                    if ($result['user_info']['user_from'] == 2) {
                        $hash = md5($user_id . "fashion" . $soap_pwd . $time);
                        $url = BAG_SITE_TIME_MODIFY_URL . "?id=" . $user_id . "&site=fashion&time=" . $time . "&hash=" . $hash;
                    }
                    elseif ($result['user_info']['user_from'] == 3) {
                        $hash = md5($user_id . "fashion" . $soap_pwd . $time);
                        $url = SHOE_SITE_TIME_MODIFY_URL . "?id=" . $user_id . "&site=fashion&time=" . $time . "&hash=" . $hash;
                    }
                    elseif ($result['user_info']['user_from'] == 4) {
                        $hash = md5($user_id . "fashion" . $soap_pwd . $time);
                        $url = SHOUSHI_SITE_TIME_MODIFY_URL . "?id=" . $user_id . "&site=fashion&time=" . $time . "&hash=" . $hash;
                    }
                    elseif ($result['user_info']['user_from'] == 5) {
                        $hash = md5($user_id . "fashion" . $soap_pwd . $time);
                        $url = JIAF_SITE_TIME_MODIFY_URL . "?id=" . $user_id . "&site=fashion&time=" . $time . "&hash=" . $hash;
                    }
                    if ($url) {
                        file($url);
                    }
                }
            }
        }

        $sMac = '';
        if ($aLogin['hidEncryptCode']) {
            $arr_post = explode(',', base64_decode($aLogin['hidEncryptCode']));
            // 去除电脑标识首尾空格
            $sMac = trim($arr_post[1]);
        }

        // 登陆验证权限qmd
        $aResult = $this->user_login($account, $passwd, $client_ip, $sMac, $iClientType);

        if ($aResult['code'] == 0) {
            if (!$aMainUser) {
                $where_se1[] = array('id', 'eq', $result['user_info']['id']);
                $field = '*';
                $aMainUser = $this->getSearchLists(self :: T_FASHION_FASHION_USER, $where_se1, array(), 1, 1, $field);
            }
            $aUnsetUserFields = array('memo', 'brand_name', 'com_name', 'com_type', 'email',
                'last_admeasure_time', 'last_contact_time', 'quote', 'card',
                'saler_name_str', 'address', 'message_tag_unread', 'message_tag_read', 'website_other_enddate', 'jiafang_create_time', 'zd_memo', 'tel_memo', 'nfd_tt', 'open_auto', 'syn_order', 'website_other', 'jdo_version', 'domain', 'domain_status', 'passwd', 'nme', 'trail', 'area_id', 'create_time', 'country', 'province', 'city', 'nfd_memo', 'com_fax', 'com_web', 'count_num', 'feld', 'gender', 'qqmsn', 'shoe_create_time', 'bag_create_time', 'shoushi_create_time', 'tel', 'special', 'user_category', 'last_saler_name', 'trail_flag', 'pwd_last_modify_time', 'password_call_num', 'last_login_time', 'user_result', 'user_sbcate', 'last_saler_id', 'crm_status', 'channel', 'channel_start_time', 'channel_end_time', 'channel_status', 'check_code', 'check_time'
            );
            foreach ($aUnsetUserFields as $_field) {
                unset($aMainUser[$_field]);
            }
            // 主账号和子账号存储过程
            if ($iAccountType == 1) {
                // 主账号
                $iUid = $aMainUser['id'];
                // 主账号名
                $sAccount = $aMainUser['account'];
            }
            else {
                // 子账号
                $iUid = $result['user_info']['sChildID'];
                // 子账号名
                $sAccount = $result['user_info']['sChildAccount'];
                $aMainUser['sChildID'] = $result['user_info']['sChildID'];
                $aMainUser['sChildAccount'] = $result['user_info']['sChildAccount'];
                $aMainUser['iIntegraNumber'] = $result['user_info']['iIntegraNumber'];
            }
            $aMainUser['iAccountType'] = $result['user_info']['iAccountType'];

            // 主或子账号类型加密存储
            $sEncode = EncryptionDeciphering(strval($aMainUser['iAccountType']));
            set_cookie('_ACCOUNT_TYPE', $sEncode, 0);

            // 主或子账号ID
            set_cookie('userinfo_id', $iUid, 0);

            // 主或子账号名称
            set_cookie('_MemberName', $sAccount, 0);

            /*
			  + 电子合同,查看当前用户是否同意电子协议
			$table = self :: T_POP136_GLOBAL_ELECTRONIC_CONTRACT;
			$where_elec[] = array('iUid', 'eq', $aMainUser[ 'id' ]);
			$where_elec[] = array('iWebsite', 'eq', 1);
			$where_elec[] = array('iConfirmStatus', 'eq', 0);
			$where_elec[] = array('iNewOld', 'eq', 1);
			$oderby = array('dCreateTime' => 'ASC');
			$electronicContract = $this -> getSearchLists($table, $where_elec, $oderby, 1, 1, '*');

			if ($electronicContract) {
				$sEncodeElec = EncryptionDeciphering($electronicContract[ 'sContractOrderID' ] . "#POP#" . $aMainUser[ 'id' ]);
				set_cookie('_ELECTRONIC_CONTRACT_NEW', $sEncodeElec, 0);
			}
			*/

            $aMainUser['p_ip'] = $aResult['p_ip'];

            // 用户信息
            $cookieinfo = encrypt(serialize($aMainUser));

            // 记住登录状态 加密为：会员id-会员账号 失效期为一个月 即： 30*24*60*60 = 2592000s
            $_KeepPasswordCookie = get_cookie('_KeepPassword');
            if (isset($aLogin['KeepPassword']) && $aLogin['KeepPassword'] && intval($aLogin['KeepPassword']) == 1) {
                $sAutomaticLogin = $iUid . '#POP#' . $sAccount . '#POP#' . $aLogin['passwd'];
                set_cookie('_KeepPassword', EncryptionDeciphering($sAutomaticLogin), intval(2592000));
                set_cookie('user_info', strval($cookieinfo), intval(2592000));
            }
            elseif (empty($_KeepPasswordCookie)) {
                set_cookie('user_info', strval($cookieinfo), 0);
            }

            //账号
            // set_cookie( 'account', $sAccount , 0 );

            // 会员类型
            set_cookie('viptype', $aMainUser['vip_type'], 0);

            // 当前登录IP
            set_cookie('client_ip', base64_encode($client_ip), 0);

            // 密码复杂度
            if (preg_match('/^[\d]+$/i', $aLogin['passwd']) || preg_match('/^[a-zA-Z]+$/i', $aLogin['passwd'])) {
                // 纯数字或纯字母 ： 低;
                //set_cookie('_PWD_WARNING', 1, 0);
            }

            if ($aLogin['encryption']) {
                $ajson['status'] = 1;
                $ajson['account'] = $aLogin['account'];
                echo json_encode($ajson);
                exit;
            }
        }
        else {
            echo $iClientType == 2 ? $this->loginError[$aResult['code']] : $aResult['code'];
            exit;
        }

        //所有通过验证后,客户端通过URL登录;$aLogin['hidEncryptCode']不为空是客户端
        // $adata[ 'bKipCode' ]  true|false 判断是否oa登入
        if ((intval($this->input->get_post('return')) == 1 && $iClientType == 2) || $aLogin['bKipCode']) {
            header("Location:/");
            exit;
        }
        elseif ($iClientType == 2) {
            echo "Success";
            exit;
        }

    }

    private function user_login($account, $psw, $client_ip, $sMac, $iClientType)
    {

        $aReturn = $this->getLoginResult(['account' => $account, 'passwd' => $psw]);

        //定义返回数组
        $aResult = array(
            'p_ip' => false,    //是否验证IP
            'code' => 0        //返回错误码  为0 验证通过 5账号不存在 6 账号被删除；7 绑定客户端没有使用客户端登录；8 非指定客户端电脑登陆；9 没有在固定ip内登录；10 子账号已经解绑；
        );
        if ($aReturn['iAccountType'] == -1) {
            $aResult['code'] = 5;
        }
        elseif ($aReturn['iStatus'] == -1) {
            $aResult['code'] = 10;
        }
        else {
            $aMainUserInfo = $this->getSearchLists(self :: T_FASHION_FASHION_USER, [['id', 'eq', $aReturn['id']]], array(), 1, 1, '*');

            // 登陆账号类型 1主 2子
            $iAccountType = $aReturn['iAccountType'];
            if ($aMainUserInfo) {

                $dNowTime = date('Y-m-d H:i:s');
                // 登陆日志
                if ($iAccountType == 2) {
                    // 记录子账号登陆日志
                    $table = self :: T_FASHION_USER_CHILD_LOGIN_LOG;
                    $insert_data['sChildID'] = $aReturn['sChildID'];
                    $insert_data['iParentID'] = $aMainUserInfo['id'];
                    $insert_data['sLoginIP'] = $client_ip;
                }
                else {
                    // 记录主账号登陆日志
                    $table = self :: T_FASHION_LOGIN_LOG;
                    $insert_data['user_id'] = $aMainUserInfo['id'];
                    $insert_data['create_time'] = $dNowTime;
                    $insert_data['login_ip'] = $client_ip;
                }

                //客户端登录都将走新的登录接口，新接口会记录日志，这边不需要
                if ($iClientType != 2) {
                    $this->insert_result($table, $insert_data);
                }

                // 更新最后登录时间
                $this->update_result(
                    self :: T_FASHION_FASHION_USER,
                    array('last_login_time' => $dNowTime),
                    array('id' => $aMainUserInfo['id'])
                );
                if ($aMainUserInfo['status'] == -1) {
                    // 账号被删除
                    $aResult['code'] = 6;
                }
                else {
                    // 获取该用户是否购买新服装网套餐
                    $aPower = $this->checkNewVip($aReturn['id'], true);
                    $bIsVip = false;
                    $bIsTrial = false;
                    if ($aPower) {
                        foreach ($aPower as $aP) {
                            if ($aP['iType'] == 3) {
                                $bIsVip = true;
                                break;
                            }
                            if ($aP['iType'] == 1) {
                                $bIsTrial = true;
                            }
                        }
                    }
                    if ((time() > strtotime($aMainUserInfo['bao_date_begin']) && time() < strtotime($aMainUserInfo['bao_date_end']) && $aMainUserInfo['vip_type'] == 3) || $bIsVip) {
                        if ($aMainUserInfo['client_type'] == 2 && $sMac && $iClientType == 2) {
                            // 判断是否绑定客户端
                            //判断多个MAC
                            $_mac = array_filter(explode('|', $sMac));
                            sort($_mac);
                            $aClientLog = $this->getSearchLists(
                                self :: T_CLIENT_LOG,
                                [
                                    ['user_account', 'eq', $aReturn['account']],
                                    ['mac', 'in', $_mac],
                                ],
                                array(), 1, 1, 'id'
                            );
                            if (!$aClientLog) {
                                // 非指定客户端电脑登陆
                                $aResult['code'] = 8;
                            }
                        }
                        else {
                            //判断固定IP规则
                            $aUserLoginIp = $this->getSearchLists(
                                self :: T_FASHION_USER_LOGIN_IP,
                                [['user_id', 'eq', $aMainUserInfo['id']]],
                                array(), 1, 1, 'user_id'
                            );

                            if ($aUserLoginIp) {
                                $arrDateIp = $this->getSearchLists(
                                    self :: T_FASHION_USER_LOGIN_IP,
                                    [['user_id', 'eq', $aMainUserInfo['id']], ['ip', 'eq', $client_ip]],
                                    array(), 1, 1, 'count(*) as amount'
                                );
                                if ($arrDateIp['amount'] <= 0) {
                                    $aResult['code'] = 9;
                                }
                            }
                            else {
                                //IP个数限制
                                $this->ip_limits($aMainUserInfo['id'], $client_ip, 'w', true);
                                $aResult['p_ip'] = true;    //是否VIP
                            }
                        }
                        /**
                         * + 子账号首次登陆成功并且主账号为VIP会员身份，记录登陆消息中间件队列
                         * + $type : person
                         * + $action:login
                         * + $parameters : 参数
                         * + $stat_group : login
                         * + pop_async_msg_send($type, $action, $parameters, $stat_group = 'pop_group_1')
                         * -
                         */
                        if ($aReturn['iAccountType'] == 2) {
                            $this->connectSpread();
                            $parameters = array(
                                'uid' => $aReturn['sChildID'],
                                'logintime' => date('Ymd')
                            );
                            self:: $spread->pop_async_msg_send('person', 'login', $parameters, 'login');
                        }
                    }
                    elseif (($aMainUserInfo['vip_type'] == 1 && $aMainUserInfo['trail'] == 1 && time() > strtotime($aMainUserInfo['bao_date_begin']) && time() < strtotime($aMainUserInfo['bao_date_end'])) || $bIsTrial) {
                        // 记录试用账号登陆日志
                        $insert_trail['user_id'] = $aMainUserInfo['id'];
                        $insert_trail['create_time'] = $dNowTime;
                        $insert_trail['login_ip'] = $client_ip;
                        $this->insert_result(self :: T_FASHION_TRAIL_LOGIN, $insert_trail);
                    }
                }
            }
            else {
                $aResult['code'] = 5;
            }
        }

        return $aResult;
    }

    // 得到所有区域
    function getAllArea()
    {
        $sql = "SELECT * FROM `fashion`.`fashion_area` ORDER BY id DESC ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function confirmElectronicContract()
    {
        // 解密cookie的值
        $_ELECTRONIC_CONTRACT = EncryptionDeciphering(get_cookie('_ELECTRONIC_CONTRACT_NEW'), false);
        if ($_ELECTRONIC_CONTRACT == "") {
            return 0;
        }
        $aOrderUid = explode('#POP#', $_ELECTRONIC_CONTRACT);
        $sContractOrderID = $aOrderUid[0];
        $iUid = $aOrderUid[1];
        $table = self :: T_POP136_GLOBAL_ELECTRONIC_CONTRACT;
        $update_elecdata['iConfirmStatus'] = 1;
        $update_elecdata['dConfirmTime'] = date('Y-m-d H:i:s');
        $where_elec['sContractOrderID'] = $sContractOrderID;
        $where_elec['iUid'] = $iUid;
        $where_elec['iConfirmStatus'] = 0;
        $where_elec['iNewOld'] = 1;
        $affected_rows = $this->update_result($table, $update_elecdata, $where_elec);
        delete_cookie('_ELECTRONIC_CONTRACT_NEW');
        return $affected_rows;
    }

    public function check_user_exists($sAccount)
    {
        $table = self :: T_POP136_FASHION_USER;
        $field = 'id';
        $where[] = array('account', 'eq', $sAccount);
        return $this->getSearchLists($table, $where, array(), 1, 1, $field);
    }

    public function checkBindMobileExists($sAccount)
    {
        $table = self :: T_FASHION_FASHION_USER;
        $field = 'bind_mobile';
        $where[] = array('account', 'eq', $sAccount);
        return $this->getSearchLists($table, $where, array(), 1, 1, $field);
    }

    public function sendMessageForPassWord($smobile, $saccount, $is_voice = false)
    {
        $data = array();
        $data['status'] = 1;
        $table = self :: T_FASHION_FASHION_USER;
        $field = 'id,bind_mobile,passwd,vip_type';
        $findpass_where[] = array('account', 'eq', $saccount);
        $findpass_where[] = array('bind_mobile', 'eq', $smobile);
        $aUserInfo = $this->getSearchLists($table, $findpass_where, array(), 1, 1, $field);

        if ($aUserInfo) {
            $frequencyInfo = get_cookie('cookiemessage_findpass');
            if ($frequencyInfo) {
                $find_message = unserialize(EncryptionDeciphering($frequencyInfo, false));
                if (!$find_message || !isset($find_message['last_time']) || !isset($find_message['count'])) {
                    $data['status'] = 2;
                    echo json_encode($data);
                    exit();
                }

                $expireTime = time() - strtotime($find_message['last_time']);
                if ($expireTime < $this->codeExpireTime) {
                    $data['status'] = 2;
                    echo json_encode($data);
                    exit();
                }
                if ($find_message['count'] > 6) {
                    $data['status'] = 2;
                    echo json_encode($data);
                    exit();
                }
            }

            if (!preg_match('/^1\d{10}$/is', $smobile)) {
                $data['status'] = 3;
            }
            else {
                $anHourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));
                $table = self::T_FASHION_USER_PASSWORD_CHANGE;
                $field = 'check_num';
                $where_checknum[] = array('user_id', 'eq', $aUserInfo['id']);
                $where_checknum[] = array('create_time', 'gte', $anHourAgo);
                $where_checknum[] = array('is_checked', 'eq', 0);
                $row = $this->getSearchLists($table, $where_checknum, array(), 1, 1, $field);
                $insertFlag = true;
                if ($row) {
                    $insertFlag = false;
                    $string = $captcha = $row['check_num'];
                }
                else {
                    $string = $captcha = MessageInterface::randCaptcha();
                }
                $nowTime = date('Y-m-d H:i:s');
                if ($is_voice) {
                    include getenv('BASEPATH') . "/message_verify/MessageVerifyHelp.class.php";
                    // 发送语音短信验证
                    $voiceConfig = array(
                        'appType' => 2,        // 应用类型	参照配置文件
                        'to' => $smobile,    // 接收号码
                        'verifyCode' => $captcha,    // 验证码内容
                    );
                    // 调用语音验证码接口结果
                    $ret = MessageVerifyHelp::voiceVerify($voiceConfig);
                    if ($ret) {
                        $cookiedata1['last_time'] = date('y-m-d H:i:s', time());
                        $cookiedata1['count'] = $find_message['count'] + 1;
                        // 重置cookie的短信信息
                        set_cookie('cookiemessage_findpass', EncryptionDeciphering(serialize($cookiedata1)), 7200);
                    }
                    else {
                        $data['status'] = 0;
                        $data['info'] = '发送失败';
                    }
                    echo json_encode($data);
                    exit;
                }
                else {
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
                            $ins_da['user_id'] = $aUserInfo['id'];
                            $ins_da['account'] = $saccount;
                            $ins_da['bind_mobile'] = $aUserInfo['bind_mobile'];
                            $ins_da['check_num'] = $string;
                            $ins_da['old_password'] = $aUserInfo['passwd'];
                            $ins_da['is_checked'] = '0';
                            $ins_da['create_time'] = date('y-m-d H:i:s');
                            $this->insert_result($table, $ins_da);
                        }
                        else {
                            $fin_update_da['create_time'] = $nowTime;
                            $up_whee['user_id'] = $aUserInfo['id'];
                            $up_whee['check_num'] = $string;
                            $this->update_result($table, $fin_update_da, $up_whee);
                        }
                    }
                    else {
                        $data['status'] = 4;
                    }
                }
            }
        }
        else {
            $data['status'] = 5;
        }
        echo json_encode($data);
        exit();
    }

    /**
     * 设计师专属账号密码找回，发送验证码
     * @param $childAccount 设计师专属账号
     */
    public function sendMessageForChildFindPassWord($childAccount)
    {

        $fields = 'sChildID,sChildAccount,sPassword';
        $conditon = ['sChildAccount' => $childAccount];
        $aUserInfo = $this->getChildInfo($conditon, '', $fields);
        if (!$aUserInfo) {
            $result['status'] = 5;
            echo json_encode($result);
            die;
        }
        if (!preg_match('/^1\d{10}$/is', $childAccount)) {
            $result['status'] = 3;
            echo json_encode($result);
            die;
        }

        $frequencyInfo = get_cookie('cookiemessage_findpass');
        if ($frequencyInfo) {
            $find_message = unserialize(EncryptionDeciphering($frequencyInfo, false));
            if (!$find_message || !isset($find_message['last_time']) || !isset($find_message['count'])) {
                $data['status'] = 2;
                echo json_encode($data);
                exit();
            }

            $expireTime = time() - strtotime($find_message['last_time']);
            if ($expireTime < $this->codeExpireTime) {
                $data['status'] = 2;
                echo json_encode($data);
                exit();
            }
            if ($find_message['count'] > 6) {
                $data['status'] = 2;
                echo json_encode($data);
                exit();
            }
        }

        $conditon = [
            'user_id' => $aUserInfo['sChildID'],
            'account' => $childAccount,
            'is_checked' => 0,
            'create_time >' => date('Y-m-d H:i:s', strtotime('-1 hour'))
        ];

        $row = $this->db->select('check_num')->from(self::T_FASHION_USER_PASSWORD_CHANGE)
            ->where($conditon)->limit(1)->get()->row_array();

        $insertFlag = true;
        if ($row) {
            $insertFlag = false;
            $string = $captcha = $row['check_num'];
        }
        else {
            $string = $captcha = MessageInterface::randCaptcha();
        }
        $nowTime = date('Y-m-d H:i:s');

        // 发送短信
        $MessageConfig = [
            'templateId' => 46837, // 模版id
            'appId' => 2, // 应用id
            'params' => array($captcha, '4008-210-500'), // 模版参数
            'to' => $childAccount // 手机号码
        ];
        if (MessageInterface::sendMessage($MessageConfig) == 200) {
            $cookiedata1['last_time'] = $nowTime;
            $cookiedata1['count'] = $find_message['count'] + 1;
            set_cookie('cookiemessage_findpass', EncryptionDeciphering(serialize($cookiedata1)), 7200);

            $data = [];
            if ($insertFlag) {
                $data['user_id'] = $aUserInfo['sChildID'];
                $data['account'] = $childAccount;
                $data['bind_mobile'] = 'childaccount';
                $data['check_num'] = $string;
                $data['old_password'] = $aUserInfo['sPassword'];
                $data['is_checked'] = '0';
                $data['create_time'] = date('y-m-d H:i:s');
                $this->executeSave(self::T_FASHION_USER_PASSWORD_CHANGE, $data);
            }
            else {
                $data['create_time'] = $nowTime;
                $condtition = [
                    'user_id' => $aUserInfo['sChildID'],
                    'account' => $childAccount,
                    'bind_mobile' => 'childaccount',
                    'check_num' => $string
                ];
                $this->executeUpdate(self::T_FASHION_USER_PASSWORD_CHANGE, $data, $condtition);
            }
            $result['status'] = 1;
            echo json_encode($result);
            die;
        }
        else {
            $result['status'] = 4;
            echo json_encode($result);
            die;
        }
    }

    //子账号解绑发送短信验证码
    public function sendMessageForUnbind($smobile)
    {
        $data['status'] = 0;
        $aWebsites = $this->aWebsites;
        $iWebsite = $this->iWebsite;
        // 短信发送限制验证
        $ssend_message_popEncode = get_cookie('send_message');
        // 进行解密
        $ssend_message_popDecode = popDecode($ssend_message_popEncode, COOKIE_ENCRYPT_KEYS);

        $asend_message = unserialize($ssend_message_popDecode);

        if (!$asend_message || !isset($asend_message['last_time']) || !isset($asend_message['count'])) {
            $data['info'] = '您的访问频率过高, 请您稍后再试!';
            echo json_encode($data);
            exit();
        }

        $expireTime = time() - strtotime($asend_message['last_time']);

        if ($expireTime < $this->codeExpireTime) {
            $data['info'] = '您的访问频率过高, 请您稍后再试!';
            echo json_encode($data);
            exit();
        }
        if ($asend_message['count'] > 29) {
            $data['info'] = '您的访问频率过高, 请您稍后再试!';
            echo json_encode($data);
            exit();
        }
        // 验证当前手机是否已经
        $table = '`pop136`.`unbind_cellphone_verify`';
        $where[] = array('sCellPhone', 'eq', $smobile);
        $where[] = array('iWebsite', 'in', $aWebsites);
        $field = '*';
        $aCellphoneVerifyRows = $this->getSearchLists($table, $where, array(), 1, 1, $field);
        // mydebug($aCellphoneVerifyRows);
        // 查询之前是否发送过验证码 若存在则将原来的验证码重新获取发送
        $captcha = '';
        // 当前站点记录数组
        $aCellphoneVerify = array();
        if ($aCellphoneVerifyRows) {
            $captcha = $aCellphoneVerifyRows['sVerifyCode'];

            if ($aCellphoneVerifyRows['iWebsite'] == $iWebsite) {
                $aCellphoneVerify = $aCellphoneVerifyRows;
            }
        }
        if ($aCellphoneVerify && time() - strtotime($aCellphoneVerify['dCreateTime']) < $this->codeExpireTime) {
            // 验证上次发送时间
            $data['info'] = $this->codeExpireTime . '秒后再获取';
            echo json_encode($data);
            exit();
        }
        else {
            // 产生短信验证码
            $captcha = $captcha ? $captcha : MessageInterface::randCaptcha();
            //$captcha = '123456';

            $MessageConfig = [
                'templateId' => 426208, // 模版id
                'appId' => 9, // 应用id
                'params' => array($captcha, 60, '4008-210-500'), // 模版参数
                'to' => strval($smobile) // 手机号码
            ];

            $returnCode = MessageInterface::sendMessage($MessageConfig);
            if ($returnCode == 200) {
                $dCreateTime = date('Y-m-d H:i:s');
                // 发送成功
                if ($aCellphoneVerify) {
                    $row = array(
                        'iVerifyID' => $aCellphoneVerify['iVerifyID'],
                        'sVerifyCode' => $captcha,
                        'iVerifyStatus' => 0,
                        'dVerifiedTime' => '',
                        'dCreateTime' => $dCreateTime
                    );
                    $table = '`pop136`.`unbind_cellphone_verify`';
                    $where_update['iVerifyID'] = $aCellphoneVerify['iVerifyID'];
                    $this->update_result($table, $row, $where_update);
                }
                else {
                    $row = array(
                        'sCellPhone' => $smobile,
                        'sVerifyCode' => $captcha,
                        'dCreateTime' => $dCreateTime,
                        'iWebsite' => $iWebsite
                    );
                    $table = '`pop136`.`unbind_cellphone_verify`';
                    $this->insert_result($table, $row);
                }
                // 重置cookie的短信信息
                $scookie_send_message_val = EncryptionDeciphering
                (serialize(array("last_time" => date('y-m-d H:i:s', time()), "count" => $asend_message['count'] + 1)), true);
                set_cookie('send_message', $scookie_send_message_val, 7200);
                $data['status'] = 1;
                $data['info'] = $asend_message['count'];
                echo json_encode($data);
                exit();
            }
            elseif ($returnCode == 101) {
                $data['info'] = '手机号错误！';
                echo json_encode($data);
                exit();
            }
            else {
                $data['info'] = '发送失败';
                echo json_encode($data);
                exit();
            }
        }
    }

    //子账号解绑
    public function doUbind($sAccountId, $sCellphone, $verifyCode)
    {
        $json = $this->json;
        $json['status'] = -1;
        $iWebsite = $this->iWebsite;
        $nowTime = date('Y-m-d H:i:s');
        // 验证手机号验证码
        $aCellphoneVerify = $this->getSearchLists(
            self :: T_POP136_UNBIND_CELLPHONE_VERIFY,
            array(
                array('sCellPhone', 'eq', $sCellphone),
                array('sVerifyCode', 'eq', $verifyCode),
                array('iWebsite', 'eq', $iWebsite)
            ),
            array(), 1, 1, 'iVerifyID , dCreateTime'
        );
        if ($aCellphoneVerify) {
            if (false) {
                $json['info'] = '验证码已过期';
            }
            else {
                // 验证通过
                // 当前所操作站点代号
                $website = $this->websites[$this->website];
                // 是否有验证状态更新
                $bFlag_Verify = true;
                $iVerifyID = $aCellphoneVerify['iVerifyID'];
                $aLog = array();
                $aLog['iVerifyID'] = $iVerifyID;
                $aLog['iVerifyStatus'] = 1;
                $aLog['dVerifiedTime'] = $nowTime;
                $where['iVerifyID'] = $iVerifyID;
                $iAffectedRows = $this->update_result(self::T_POP136_UNBIND_CELLPHONE_VERIFY, $aLog, $where);
                // mydebug($iAffectedRows);
                if ($iAffectedRows == 0) {
                    $bFlag_Verify = false;
                }
                // 解除绑定
                $aChildInfo = array(
                    'iStatus' => -1,
                    'iParentID' => 0,
                );
                $childWhere['sChildID'] = $sAccountId;
                $iChild = $this->update_result(self::T_FASHION_FASHION_USER_CHILD, $aChildInfo, $childWhere);
            }
            if ($bFlag_Verify && $iChild) {
                $json['status'] = 1;
                $json['data'] = array('id' => $iChild);
            }
            else {
                $json['info'] = '解绑失败...';
            }
        }
        else {
            $json['info'] = '短信验证码错误！';
        }
        return $json;
    }


    public function step1($saccount, $sbind_mobile, $svalid_code)
    {
        // 判断手机验证码是否过期
        $aReturnData = ['status' => 0, 'data' => ''];
        $table = self::T_FASHION_USER_PASSWORD_CHANGE;
        $field = '*';
        $where1[] = ['account', 'eq', $saccount];
        $where1[] = ['bind_mobile', 'eq', $sbind_mobile];
        $oderbys = ['create_time' => 'DESC'];
        $code_arr = $this->getSearchLists($table, $where1, $oderbys, 1, 1, $field);

        if ($svalid_code == "") {
            $aReturnData['status'] = 0;
            echo json_encode($aReturnData);
            exit();
        }
        // 查看验证码时间差
        $dif_time = time() - strtotime($code_arr['create_time']);

        if ($dif_time > 3600) {
            $aReturnData['status'] = 1;
            echo json_encode($aReturnData);
            exit();
        }
        // 判断手机验证码是否已被使用
        if ($code_arr['is_checked'] == 1) {
            $aReturnData['status'] = 2;
            echo json_encode($aReturnData);
            exit();
        }
        // 判断手机验证码
        if (strtolower($svalid_code) != strtolower($code_arr['check_num'])) {
            $aReturnData['status'] = 3;
            echo json_encode($aReturnData);
            exit();
        }

        $table = self::T_FASHION_FASHION_USER;
        $field = 'id,bind_mobile,passwd,vip_type';
        $where_ch2[] = ['account', 'eq', $saccount];
        $where_ch2[] = ['bind_mobile', 'eq', $sbind_mobile];
        $aUserInfo = $this->getSearchLists($table, $where_ch2, array(), 1, 1, $field);

        if ($aUserInfo['bind_mobile'] != '') {
            // 将手机验证的字段的改为1，已验证
            $this->executeUpdate(self::T_FASHION_USER_PASSWORD_CHANGE, ['is_checked' => 1], ['id' => $code_arr['id']]);
            $aReturnData['status'] = 5;
            $aReturnData['data'] = urlencode(EncryptionDeciphering($this->config->item('encryption_key') . '=POP=' . $aUserInfo['id']));

            echo json_encode($aReturnData);
            exit();
        }
        else {
            $aReturnData['status'] = 4;
            echo json_encode($aReturnData);
            exit();
        }
    }


    public function childstep1($saccount, $svalid_code)
    {
        // 判断手机验证码是否过期
        $aReturnData = ['status' => 0, 'data' => ''];
        $table = self::T_FASHION_USER_PASSWORD_CHANGE;
        $field = '*';
        $where1[] = ['account', 'eq', $saccount];
        $where1[] = ['bind_mobile', 'eq', 'childaccount'];//固定值-标识设计师专属账号找回密码
        $oderbys = ['create_time' => 'DESC'];
        $code_arr = $this->getSearchLists($table, $where1, $oderbys, 1, 1, $field);

        if ($svalid_code == "") {
            $aReturnData['status'] = 0;
            echo json_encode($aReturnData);
            exit();
        }
        // 查看验证码时间差
        $dif_time = time() - strtotime($code_arr['create_time']);

        if ($dif_time > 3600) {
            $aReturnData['status'] = 1;
            echo json_encode($aReturnData);
            exit();
        }
        // 判断手机验证码是否已被使用
        if ($code_arr['is_checked'] == 1) {
            $aReturnData['status'] = 2;
            echo json_encode($aReturnData);
            exit();
        }
        // 判断手机验证码
        if (strtolower($svalid_code) != strtolower($code_arr['check_num'])) {
            $aReturnData['status'] = 3;
            echo json_encode($aReturnData);
            exit();
        }

        $table = self::T_FASHION_FASHION_USER_CHILD;
        $field = 'sChildID,sChildAccount,sPassword';
        $where_ch2[] = ['sChildAccount', 'eq', $saccount];
        $aUserInfo = $this->getSearchLists($table, $where_ch2, array(), 1, 1, $field);

        if ($aUserInfo) {
            // 将手机验证的字段的改为1，已验证
            $this->executeUpdate(self::T_FASHION_USER_PASSWORD_CHANGE, ['is_checked' => 1], ['id' => $code_arr['id']]);
            $aReturnData['status'] = 5;
            $aReturnData['data'] = urlencode(EncryptionDeciphering($this->config->item('encryption_key') . '=POP=' . $aUserInfo['sChildID']));

            echo json_encode($aReturnData);
            exit();
        }
        else {
            $aReturnData['status'] = 4;
            echo json_encode($aReturnData);
            exit();
        }
    }

    public function step2($id, $spass1, $spass2)
    {
        if ($spass1 !== $spass2) {
            echo 0;
            exit();
        }

        $flag = $this->verifyPwd($spass1);
        if (!$flag) {
            echo 1;
            exit();
        }

        // 开始修改密码
        if ($this->globalModifyPwd($id, $spass1)) {
            echo 2;
            exit();
        }
        elseif ($this->globalModifyChildPwd($id, $spass1)) {
            echo 4;
            exit();
        }
        else {
            echo 3;
            exit();
        }
    }

    // 电子合同查询
    public function getElectranicInfo($iUid, $website)
    {
        $table = self::T_POP136_GLOBAL_ELECTRONIC_CONTRACT;
        $where_ishow[] = array('iUid', 'eq', $iUid);
        $where_ishow[] = array('iWebsite', 'eq', $website);
        $where_ishow[] = array('iConfirmStatus', 'eq', 0);
        $where_ishow[] = array('iNewOld', 'eq', 1);
        $oderby = array('dCreateTime' => 'ASC');
        return $this->getSearchLists($table, $where_ishow, $oderby, 1, 1, '*');
    }

    /**
     * + APM信息记录
     */
    public function recommendApm($iUid)
    {
        if (empty($iUid)) {
            return -4;
            exit;
        }
        $dCreateTime = date('Y-m-d H:i:s');
        $table = self::T_FASHION_T_APM_RECORD;
        $insert_data['iUid'] = intval($iUid);
        $insert_data['dCreateTime'] = $dCreateTime;
        $this->insert_result($table, $insert_data);

        $updateSql = 'UPDATE `t_apm_amount` set amount=amount+1 where amount<200';

        $affect = $this->modisql($updateSql);
        if ($affect > 0) {
            return -5;
            exit;
        }
        else {
            return -4;
            exit;
        }
    }

    private function modisql($sql)
    {
        $this->db->query($sql);

        return $this->db->affected_rows();
    }

    /**
     * 用于修改所有站点的主账号密码
     *
     * @param integer $id 账号id
     * @param string $newPassword 新密码
     * @return bool
     */
    public function globalModifyPwd($id, $newPassword)
    {
        // 用户id验证
        if (empty($id)) return false;
        // 新密码验证规则
        if (!preg_match('/^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$/im', $newPassword)) return false;
        // 获取用户是否存在  分别判断用户是主账号还是子账号id
        $aUserRows = $this->getUserById($id);
        if (!$aUserRows) return false;

        if (preg_match('/^1\d{10}$/im', $aUserRows['account'])) {
            // 如果账号为手机号，验证当前账号+重置密码是否与子账号账号+密码重复
            $condition = array();
            $condition['sChildAccount'] = $aUserRows['account'];
            $condition['sPassword'] = $newPassword;

            $aUserChildRows = $this->getChildInfo($condition);
            // 密码与子账号冲突
            if ($aUserChildRows) return false;
        }

        $time = date("Y-m-d H:i:s");
        // 更新136用户数据表密码 和 最后修改时间 （含鞋子 高端趋势）
        $sql = 'UPDATE ' . self::T_POP136_FASHION_USER . ' SET `pwd_last_modify_time`=? , `passwd`=?  WHERE `id`=? LIMIT 1';
        $this->db->query($sql, array($time, $newPassword, $id));
        // 服装表
        $sql = 'UPDATE ' . self::T_FASHION_FASHION_USER . ' SET `pwd_last_modify_time`=? , `passwd`=?  WHERE `id`=? LIMIT 1';
        $this->db->query($sql, array($time, $newPassword, $id));
        $sql = 'INSERT INTO `fashion`.`password_modify_log` (account,user_id,create_time)values(?,?,?)';
        $this->db->query($sql, array($aUserRows["account"], $id, $time));
        // 箱包
        $sql = 'UPDATE ' . self::T_BAGS_SHOE_USER . ' SET `pwd_last_modify_time`=? , `password`=?  WHERE `id`=? LIMIT 1';
        $this->db->query($sql, array($time, $newPassword, $id));
        $sql = 'INSERT INTO `popbags`.`password_modify_log` (account,user_id,create_time)values(?,?,?)';
        $this->db->query($sql, array($aUserRows["account"], $id, $time));
        // 老鞋子
        $sql = 'UPDATE ' . self::T_SHOE_SHOE_USER . ' SET `pwd_last_modify_time`=? , `password`=?  WHERE `id`=? LIMIT 1';
        $this->db->query($sql, array($time, $newPassword, $id));
        $sql = 'INSERT INTO `popshoe`.`password_modify_log` (account,user_id,create_time)values(?,?,?)';
        $this->db->query($sql, array($aUserRows["account"], $id, $time));
        // 首饰
        $sql = 'UPDATE ' . self::T_DECORATION_SHOE_USER . ' SET `pwd_last_modify_time`=? , `password`=?  WHERE `id`=? LIMIT 1';
        $this->db->query($sql, array($time, $newPassword, $id));
        $sql = 'INSERT INTO `decoration`.`password_modify_log` (account,user_id,create_time)values(?,?,?)';
        $this->db->query($sql, array($aUserRows["account"], $id, $time));
        // 家纺
        $sql = 'UPDATE ' . self::T_HOMETEXTILE_SHOE_USER . ' SET `pwd_last_modify_time`=? , `password`=?  WHERE `id`=? LIMIT 1';
        $this->db->query($sql, array($time, $newPassword, $id));
        $sql = 'INSERT INTO `hometextile`.`password_modify_log` (account,user_id,create_time)values(?,?,?)';
        $this->db->query($sql, array($aUserRows["account"], $id, $time));

        return true;
    }


    public function globalModifyChildPwd($id, $newPassword)
    {
        // 用户id验证
        if (empty($id)) return false;
        // 新密码验证规则
        if (!preg_match('/^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$/im', $newPassword)) return false;
        // 获取用户是否存在  分别判断用户是主账号还是子账号id
        $condition = ['sChildID' => $id];
        $aUserRows = $this->getChildInfo($condition);
        if (!$aUserRows) return false;

        //修改子账号密码
        return $this->executeUpdate(self::T_FASHION_FASHION_USER_CHILD, ['sPassword' => $newPassword], $condition) ? true : false;
    }


    /**
     * 用于修改所有站点的主账号绑定的手机号码
     *
     * @param integer $id 账号id
     * @param string $newMobileNo 新绑定的手机号码
     * @return bool
     */
    public function globalModifyBindMobile($id, $newMobileNo)
    {
        $id = intval($id);
        // 获取用户是否存在
        $aUserRows = $this->getUserById($id);
        if (!$aUserRows)
            return false;
        // 更新136用户数据表绑定手机号 和 最后修改时间 （含鞋子 高端趋势）
        $sql = 'UPDATE ' . self::T_POP136_FASHION_USER . ' SET `bind_mobile`=?  WHERE `id`=? LIMIT 1';
        $this->db->query($sql, array($newMobileNo, $id));
        // 服装表
        $sql = 'UPDATE ' . self::T_FASHION_FASHION_USER . ' SET `bind_mobile`=?  WHERE `id`=? LIMIT 1';
        $this->db->query($sql, array($newMobileNo, $id));
        // 箱包
        $sql = 'UPDATE ' . self::T_BAGS_SHOE_USER . ' SET `bind_mobile`=?  WHERE `id`=? LIMIT 1';
        $this->db->query($sql, array($newMobileNo, $id));
        // 老鞋子
        $sql = 'UPDATE ' . self::T_SHOE_SHOE_USER . ' SET `bind_mobile`=?  WHERE `id`=? LIMIT 1';
        $this->db->query($sql, array($newMobileNo, $id));
        // 首饰
        $sql = 'UPDATE ' . self::T_DECORATION_SHOE_USER . ' SET `bind_mobile`=?  WHERE `id`=? LIMIT 1';
        $this->db->query($sql, array($newMobileNo, $id));
        // 家纺
        $sql = 'UPDATE ' . self::T_HOMETEXTILE_SHOE_USER . ' SET `bind_mobile`=?  WHERE `id`=? LIMIT 1';
        $this->db->query($sql, array($newMobileNo, $id));

        return true;
    }

    // 修改头像数据
    public function updateAvatarData($aData)
    {
        $data = array();
        $sTableName = self::T_KOREA_USER_APPEND_MESSAGE;
        $sql = 'UPDATE ' . $sTableName . ' SET `sAvatar`=? WHERE `iUid`=? LIMIT 1';
        $this->db->query($sql, array($aData['sAvatar'], $aData['iUid']));
        $rs = $this->db->affected_rows();
        if (!$rs) {
            if ($this->executeSave($sTableName, $aData)) {
                $data['status'] = 1;
                $data['info'] = $aData['sAvatar'];
            }
        }
        else {
            $data['status'] = 1;
            $data['info'] = $aData['sAvatar'];
        }
        return $data;
    }

    // 获取头像数据
    public function getAvatar($iUid)
    {
        $sql = 'SELECT `sAvatar` FROM ' . self::T_KOREA_USER_APPEND_MESSAGE . ' WHERE iUid=? LIMIT 1';
        $query = $this->db->query($sql, $iUid);
        $result = $query->row_array();

        return $result;
    }

    /*
	  + 获取用户登录信息
	*/
    public function logonMessage()
    {
        $userinfo = get_cookie_value();
        return !empty($userinfo) ? $userinfo : false;
    }


    /**
     * 获取个人中心收藏的数据列表
     * @param int $iType
     * @param string $sAccountId
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    public function getUCCollectList($iType = 0, $sAccountId = '', $offset = 0, $limit = 1)
    {
        $sql = "SELECT COUNT(*) as cnt FROM fm_collect WHERE iType=? AND sAccountId=? AND iStatus = 0";
        $query = $this->db()->query($sql, [$iType, $sAccountId]);
        $result = $query->row_array();
        $cnt = $result['cnt'];
        $sql = "SELECT * FROM fm_collect WHERE iType=? AND sAccountId=? AND iStatus = 0 ORDER BY iCollectId DESC LIMIT $offset, $limit;";
        $query = $this->db()->query($sql, [$iType, $sAccountId]);

        $result = $query->result_array();
        foreach ($result as $key => $val) {
            $result[$key]['tableName'] = getProductTableName($val['sTableName']);
            $result[$key]['index'] = $offset + $key;
        }
        $result['total'] = $cnt;
        // $this->db()->close();

        return $result;
    }


    /**
     * 获取个人中心控制台的数据列表
     * @param int $iWorkbenchId
     * @param string $sAccountId
     * @param int $iImgId
     * @param int $limit
     * @return mixed
     */
    public function getUCWorkbenchList($iWorkbenchId, $sAccountId = '', $iImgId = 0, $limit = 1)
    {
        $sql = "SELECT COUNT(*) as cnt FROM fm_workbench_img WHERE iWorkbenchId=? AND sAccountId=? AND iStatus = 0";
        $query = $this->db()->query($sql, [$iWorkbenchId, $sAccountId]);
        $result = $query->row_array();
        $cnt = $result['cnt'];
        $sql = "SELECT * FROM fm_workbench_img WHERE iWorkbenchId=? AND sAccountId=? AND iStatus = 0 AND iImgId = ? LIMIT $limit";
        $query = $this->db()->query($sql, [$iWorkbenchId, $sAccountId, $iImgId]);

        $result = $query->result_array();
        foreach ($result as $key => $val) {
            $result[$key]['tableName'] = getProductTableName($val['sTableName']);
            // $result[$key]['index'] = $offset + $key;
        }
        $result['total'] = $cnt;
        // $this->db()->close();

        return $result;
    }

    /*
	* 我的消息列表
	*/
    public function getMessageList($page, $pagesize, &$totalCount)
    {
        $aGenderIndustrys = [];
        $where = '';
        $offset = ($page - 1) * $pagesize;
        $aUserInfo = $this->logonMessage();
        $id = $aUserInfo['id'];
        //验证是否存在新消息
        $now = date('Y-m-d H:i:s');
        $aUserPowers = $this->checkNewVip($aUserInfo['id']);
        if ($aUserPowers) {
            foreach ($aUserPowers as $_aUserPowers) {
                if ($_aUserPowers['iType'] == 3) {
                    $aGenderIndustrys = $aGenderIndustrys + explode(',', $_aUserPowers['sGender']);
                    $aGenderIndustrys = $aGenderIndustrys + explode(',', $_aUserPowers['sIndustry']);
                }
            }
        }

        if ($aGenderIndustrys) {
            $where = ' OR ';
            foreach ($aGenderIndustrys as $_aGenderIndustrys) {
                $where .= 'FIND_IN_SET( ' . $_aGenderIndustrys . ', `sPushUser`) OR ';
            }
            $where = rtrim($where, 'OR ');
        }
        else {
            //普通用户
            $where = ' OR FIND_IN_SET(  100000, `sPushUser` )';
        }

        $sql = 'SELECT * FROM ' . self::T_FASHION_T_MESSAGE_PUSH . " WHERE iStatus=1 AND dPublishTime < '" . $now . "' AND (find_in_set(" . $id . ' , sPushUserID )' . $where . ') ORDER BY dPublishTime DESC, id DESC LIMIT ' . $offset . ',' . $pagesize;
        $totalSql = 'SELECT count(1) as total FROM ' . self::T_FASHION_T_MESSAGE_PUSH . " WHERE iStatus=1 AND dPublishTime < '" . $now . "' AND (find_in_set(" . $id . ' , sPushUserID )' . $where . ')';

        $result = $this->query($sql);
        $resultToal = $this->query($totalSql);
        $totalCount = $resultToal[0]['total'];

        return $result;
    }


    /*
	* 是否有我的新消息验证
	*/
    public function checkNewMessage($iType, $where = '')
    {
        $aGenderIndustrys = $res = [];
        $aUserInfo = $this->logonMessage();
        $parentId = $aUserInfo['id'];
        $realId = isset($aUserInfo['sChildID']) && !empty($aUserInfo['sChildID']) ? $aUserInfo['sChildID'] : $parentId;
        $now = date('Y-m-d H:i:s');
        if ($where == '') {
            $aUserPowers = $this->checkNewVip($aUserInfo['id']);
            if ($aUserPowers) {
                foreach ($aUserPowers as $_aUserPowers) {
                    if ($_aUserPowers['iType'] == 3) {
                        $aGenderIndustrys = $aGenderIndustrys + explode(',', $_aUserPowers['sGender']);
                        $aGenderIndustrys = $aGenderIndustrys + explode(',', $_aUserPowers['sIndustry']);
                    }
                }
            }

            if ($aGenderIndustrys) {
                $where = ' OR ';
                foreach ($aGenderIndustrys as $_aGenderIndustrys) {
                    if ($_aGenderIndustrys) {
                        $where .= 'FIND_IN_SET( ' . $_aGenderIndustrys . ', `sPushUser`) OR ';
                    }
                }
                $where = rtrim($where, 'OR ');
            }
            else {
                $where = ' OR FIND_IN_SET(  100000, `sPushUser` )';
            }
        }
        if (isset($aUserInfo['sChildID'])) {
            $parentId = $aUserInfo['id'];
        }
        if (!$parentId) {
            return '';
            exit;
        }

        $rows = [];
        $opdb = OpGlobalDb::getInstance('fashion');
        if ($iType == 1) {
            $sql = 'SELECT dViewTime FROM ' . self::T_FASHION_T_MESSAGE_VISIT . 'WHERE sUid="' . $realId . '" AND iType=1 LIMIT 1';
            // $res = $this->db()->query($sql, [$realId, 1])->row_array();
            $opdb->select($sql, $rows);
            $res = isset($rows[0]) ? $rows[0] : array();

            $dViewTime = !empty($res['dViewTime']) ? " AND dPushTime >='" . $res['dViewTime'] . "'" : '';
            //
            $sql = 'SELECT count(1) as total FROM  ' . self::T_FASHION_T_ORDER_PACKAGE . "  WHERE iStatus =0  AND dPushTime < '" . $now . "'" . $dViewTime . " AND (find_in_set(" . "{$parentId}" . ' , sPushAccount ))';
            // $result = $this->db()->query($sql)->row_array();
            $opdb->select($sql, $rows);
            $result = $rows;
        }
        else {

            $sql = 'SELECT dViewTime FROM  ' . self::T_FASHION_T_MESSAGE_VISIT . '  WHERE sUid="' . $realId . '"  AND  iType=0  LIMIT 1';
            // $res = $this->db()->query($sql, [$realId, 0])->row_array();
            $opdb->select($sql, $rows);
            $res = isset($rows[0]) ? $rows[0] : array();

            $dViewTime = !empty($res['dViewTime']) ? " AND dPublishTime >='" . $res['dViewTime'] . "'" : '';

            $sql = 'SELECT count(1) as total FROM  ' . self::T_FASHION_T_MESSAGE_PUSH . "  WHERE iStatus=1 AND dPublishTime < '" . $now . "'" . $dViewTime . " AND (find_in_set(" . "{$parentId}" . ' , sPushUserID )' . $where . ')';
            // $result = $this->db()->query($sql)->row_array();
            $opdb->select($sql, $rows);
            $result = $rows;
        }
        // $this->db()->close();
        return $result[0]['total'];

    }

    /*
	* 个人中心消息访问表验证
	*/
    private function _checkMessageVisit($id, $iType)
    {
        $sql = 'SELECT count(1) as total FROM ' . self::T_FASHION_T_MESSAGE_VISIT . ' where sUid = ? AND iType=? LIMIT 1';
        $result = $this->db()->query($sql, [$id, $iType])->row_array();
        // $this->db()->close();
        return $result['total'];
    }

    /*
	* 个人中心消息访问记录是否存在
	*/
    public function insertMessageVisit($iType = 0)
    {
        $aUserInfo = $this->logonMessage();
        $id = $aUserInfo['sChildID'] ? $aUserInfo['sChildID'] : $aUserInfo['id'];
        //消息类型
        $check = $this->_checkMessageVisit($id, $iType);

        $data = [];
        $data['sUid'] = $id;
        $data['dViewTime'] = date('Y-m-d H:i:s');
        $data['iType'] = $iType;

        if ($check) {
            $this->db()->update(self::T_FASHION_T_MESSAGE_VISIT, $data, "sUid='" . $id . "' AND iType='" . $iType . "'");
        }
        else {
            $this->db()->insert(self::T_FASHION_T_MESSAGE_VISIT, $data);
        }
    }

    /*
	* 新权限VIP判断
	*/
    public function checkNewVip($iUid, $bRefresh = false)
    {
        if (!$iUid) {
            return false;
        }
        $this->load->driver('cache');
        $key = self::FM_TEM_PRIVILEGE_MEMCACHE_KEY . md5($iUid);
        $aPowerInfo = $this->cache->memcached->get($key);
        if (!$aPowerInfo || $bRefresh === true || $_GET['refresh']) {
            $now = date('Y-m-d H:i:s');
            $sql = 'SELECT sGender, sIndustry, sColumn, iType, dStartTime, dEndTime FROM `fashion`.`fm_privilege` WHERE iAccountId = ? AND dEndTime >=  ? AND dStartTime <= ? order by dEndTime DESC';
            $aPowerInfo = $this->query($sql, [$iUid, $now, $now]);
            $this->cache->memcached->save($key, $aPowerInfo, 86400);
        }
        return $aPowerInfo;
    }

    /**
     * 获取已过期的vip用户的权限信息
     * @param $iUid
     * @param bool $bRefresh
     * @return array|bool
     */
    public function getExpiredVipUserInfo($iUid, $bRefresh = false)
    {
        if (empty($iUid)) {
            return false;
        }
        else {
            $this->load->driver('cache');
            $key = self::FM_TEM_PRIVILEGE_MEMCACHE_KEY . '_ExpiredVip_' . md5($iUid);
            $aPowerInfo = $this->cache->memcached->get($key);
            if (!$aPowerInfo || $bRefresh === true || $_GET['refresh']) {
                $now = date('Y-m-d H:i:s');
                $sql = 'SELECT * FROM `fm_privilege` WHERE iType=3 AND iAccountId = ? AND dEndTime < ? ORDER BY dEndTime DESC limit 1';
                $aPowerInfo = $this->query($sql, [$iUid, $now]);
                $this->cache->memcached->save($key, $aPowerInfo, 3600);
            }
            return $aPowerInfo[0];
        }
    }

    /*
	  + IP验证
	  + 判断当前操作IP是否被其他人挤掉
	*/
    public function checkIpConflit()
    {
        $REQUEST_URI = $_SERVER['REQUEST_URI'];
        if (strpos($REQUEST_URI, 'logout') === false) {

            $return = true;
            $a_user_info = get_cookie_value();
            if ($a_user_info && $a_user_info['p_ip']) {

                //当前浏览器在线IP 如果无，重新获取客户当前IP
                $old_cookie_ip = $this->input->cookie('client_ip');//用于兼容老登录模式，以防m站使用
                if (!empty($a_user_info['ip'])) {
                    $client_ip = $a_user_info['ip'];
                }
                elseif (!empty($old_cookie_ip)) {
                    $client_ip = base64_decode($old_cookie_ip);
                }
                else {
                    $client_ip = $this->input->ip_address();
                }

                $user_id = intval($a_user_info['id']);

                $return = $this->ip_limits($user_id, $client_ip, 'r', $this->input->get_post('refresh'));
            }
            //如果验证未通过，则下线
            if ($return !== true) {
                $url = urlencode($REQUEST_URI);
                $this->load->helper('url');
                $location = '/member/logout/?iplimit=1&backurl=' . urlencode('/ipconflict/ipconflict/ip_' . $return . '/?backurl=' . $url);
                if ($this->input->is_ajax_request()) {
                    if (preg_match("#^/details/#", $_SERVER["REQUEST_URI"])) {
                        //详情页异步请求，交由前端js跳转
                        $result = array("code" => 5001, "url" => $location);
                        echo json_encode($result);
                        exit;
                    }
                }
                else {
                    redirect($location);
                    exit;
                }
            }
        }
    }

    /*@todo  用户反馈
	*@param  $sFeedInfo  string  反馈信息
	*@return json
	*/
    public function userFeedBack($sFeedInfo = '')
    {
        $res = ['success' => 0, 'msg' => ''];
        $data = [];
        $data['sDescribe'] = $this->db()->escape(htmlspecialchars($sFeedInfo));
        $data['dCreateTime'] = date('Y-m-d H:i:s');
        $aCookieInfo = get_cookie_value();
        if (!empty($aCookieInfo)) {
            if ($aCookieInfo['iAccountType'] == 1) {
                $data['sUid'] = intval($aCookieInfo['id']);
            }
            elseif ($aCookieInfo['iAccountType'] == 2) {
                $data['sUid'] = trim($aCookieInfo['sChildID']);
            }
        }
        else {
            $data['sUid'] = 0;
        }

        $this->db()->insert('`fashion`.`t_korea_feedback`', $data);
        $iInsertId = $this->db()->insert_id();

        if ($iInsertId) {
            $res = ['success' => 1, 'msg' => '反馈成功'];
        }
        else {
            $res = ['success' => 0, 'msg' => '反馈失败'];
        }
        // $this->db()->close();

        return json_encode($res);
    }

    /**
     * 注册时是否验证了手机号
     * 2017/07/14
     * @param $accountId
     * @return bool|string
     */
    public function isTelVerified($accountId = '')
    {
        // 查手机号
        $sql = "SELECT mobile,tel,bind_mobile FROM " . self::T_FASHION_FASHION_USER . " WHERE id=?";
        $tel = $this->db()->query($sql, [$accountId])->row_array();
        $tel = empty($tel) ? '' : $tel['tel'];
        if (empty($tel)) {
            return '';
        }
        // 查手机号是否已验证
        $sql = "SELECT iVerifyID FROM " . self::T_POP136_GLOBAL_CELLPHONE_VERIFY . " WHERE sCellPhone=? AND iVerifyStatus=1 AND iWebsite=10";
        $row = $this->db->query($sql, [$tel])->row_array();
        return empty($row) ? $tel : 'isVerified';
    }

    /**免费试读报告
     * @param string $params
     * @param $lists
     * @param $offset
     * @param $limit
     * @param array $powers
     * @param $conditions
     * @return bool
     */
    public function getRegisterFreeReport($params = '', &$lists, $offset, $limit, $powers = [], $conditions)
    {
        // 排序
        $columnPid = 2;
        $arSort = $this->common_model->getSort($params, $powers, $columnPid);
        $result = [];
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $arSort);
        foreach ($result as $key => $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];
            $data = OpPopFashionMerger::getProductData($id, $tableName);
            $info = $data[$id];

            // 获取浏览量，浏览量的数据实时存放在memecache中，memecache中的数据通过消息队列，异步永久化到数据库中。
            $this->load->model('statistics_model');
            $info['view_count'] = $this->statistics_model->getViews($tableName, $id, $info);
            // 发布时间,标题,摘要
            switch ($tableName) {
                case 'specialtopic':
                case 'specialtopic_graphic':
                    $info['description'] = htmlspecialchars(trim(strip_tags($info['description']))); //摘要
                    break;
                case 'mostrend_content':
                    $info['description'] = htmlspecialchars(trim(strip_tags($info['description']))); //摘要
                    break;
                case 't_trend_report':
                    $info['title'] = $info['sTitle'];
                    $info['description'] = htmlspecialchars(trim(strip_tags($info['sDesc'])));//摘要
                    break;
                case 'fs_analysis':
                case 'fs_commodity':
                case 'fs_design':
                case 'fs_inspiration':
                case 'fs_trend':
                    $info['description'] = htmlspecialchars(trim(strip_tags($info['abstract']))); //摘要
                    break;
            }
            // 摘要
            if (isset($info['abstract'])) {
                $info['description'] = $info['abstract'];
            }
            elseif (isset($info['title_describe'])) {
                $info['description'] = $info['title_describe'];
            }
            elseif (isset($info['sDesc'])) {
                $info['description'] = $info['sDesc'];
            }
            $info['title'] = htmlspecialchars(stripcslashes($info['title']));
            $info['description'] = htmlspecialchars(trim(strip_tags($info['description'])));

            // 图片路径
            $imgPath = getImagePath($tableName, $info);
            $info['cover'] = getFixedImgPath($imgPath['cover']);
            $lists[$key]['list'] = $info;
            $lists[$key]['columnId'] = $val['iColumnId'][1];
            $lists[$key]['tableName'] = getProductTableName($tableName);
            $table = getProductTableName($tableName);
            $lists[$key]['offset'] = $offset++;
            $lists[$key]['total'] = $totalCount;
            $lists[$key]['link'] = "/details/report/t_$table-id_$id-col_{$val['iColumnId'][1]}/";
        }
        return $totalCount;

    }

    //
    public function updateUserInfo($data)
    {
        $data_one = [$data["nme"], $data["com_name"], $data["memo"], $data["memo"], $data["id"]];
        $sql = "UPDATE `pop136`.`fashion_user` SET nme=?,com_name=?,memo=IF(memo IS NULL,?,concat(memo,?)) WHERE id=?";
        $res_one = $this->db()->query($sql, $data_one);
        $sql = "UPDATE `fashion`.`fashion_user` SET nme=?,com_name=?  WHERE id=?";
        $res = $this->db()->query($sql, [$data["nme"], $data["com_name"], $data["id"]]);
        return $res;
    }

    //是否能切换主子账号登录
    public function canChangLoginAccount()
    {
        $userInfo = get_cookie_value();
        $userId = getUserId();//用户当前登录主、子
        $mobile = $this->getLoginPhone($userId, $userInfo["iAccountType"]);
        if (empty($userId) || empty($mobile)) {
            return false;
        }
        if ($userInfo["iAccountType"] == 1) {
            $sql = "SELECT sChildID as id FROM " . self::T_FASHION_FASHION_USER_CHILD . " WHERE sChildAccount=? AND iStatus=1";
        }
        else {
            $sql = "SELECT id FROM " . self::T_POP136_FASHION_USER . " WHERE login_phone=?";
        }
        $res = $this->db()->query($sql, $mobile)->row_array();
        $ret = !empty($res["id"]) ? true : false;
        return $ret;
    }

    //获取登录手机号
    public function getLoginPhone($userId, $iAccountType)
    {
        if (empty($userId) || empty($iAccountType)) {
            return "";
        }
        if ($iAccountType == 1) {
            $sql = "SELECT login_phone as mobile FROM " . self::T_POP136_FASHION_USER . " WHERE id=?";
        }
        else {
            $sql = "SELECT sChildAccount as mobile FROM " . self::T_FASHION_FASHION_USER_CHILD . " WHERE sChildID=?  AND iStatus=1";
        }
        $res = $this->db()->query($sql, [$userId])->row_array();
        $mobile = isset($res["mobile"]) ? $res["mobile"] : "";
        return $mobile;
    }

    //获取手机默认登录账号主、子
    public function getMobileDefaultAccount()
    {
        $userInfo = get_cookie_value();
        $userId = getUserId();
        $mobile = $this->getLoginPhone($userId, $userInfo["iAccountType"]);
        if (empty($mobile)) {
            return "";
        }
        $AccountType = [1 => "main", 2 => "child"];//当前账号类型
        $sql = "SELECT user_id FROM pop136.t_user_login_default WHERE cellphone=? LIMIT 1";
        $res = $this->db()->query($sql, [$mobile])->row_array();
        if (empty($res)) {
            return "";
        }
        elseif ($userId == $res["user_id"]) {
            return $AccountType[$userInfo["iAccountType"]];
        }
        else {
            $typeId = $userInfo["iAccountType"] == 1 ? 2 : 1;
            return $AccountType[$typeId];
        }
    }

    /**
     * 子账号绑定或解绑操作日志记录
     * @param $msg 操作描述信息
     * @param $uid
     * @param $child_account
     * @return mixed
     */
    public function bindChildAccountOperateLog($msg, $uid, $child_account)
    {
        $table = 'fashion.bind_child_account_operate_log';

        $router_uri =  $this->uri->uri_string();
        $params = json_encode($this->input->post_get(null));

        $data = [
            'msg' => $msg,
            'uid' => $uid,
            'child_account' => $child_account,
            'router' => $router_uri,
            'input_params' => $params,
        ];

        return $this->executeSave($table, $data);
    }
}
