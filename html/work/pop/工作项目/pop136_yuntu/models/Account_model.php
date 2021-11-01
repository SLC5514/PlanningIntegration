<?php
/**
 * app注册类
 * Class Account_model
 */

class Account_model extends POP_Model
{
    public $codeExpireTime = 60;
    const T_POP136_FASHION_USER = '`pop136`.`fashion_user`';// pop136用户账号总表
    const T_POP136_GLOBAL_CELLPHONE_VERIFY = '`pop136`.`global_cellphone_verify`';// pop136用户账号总表
    const T_FASHION_USER_PASSWORD_CHANGE = '`fashion`.`user_password_change`';// 验证码记录表
    const T_FASHION_FASHION_USER = '`fashion`.`fashion_user`';// 服装用户表
    const T_YUNTU_TRY_NUM = '`yuntu`.`t_yuntu_try_num`';// 云图普通用试用模板次数表

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 验证手机号是否存在
     *
     * @param $phone
     * @return bool
     */
    public function verifyPhone($phone)
    {
        $sql = "SELECT `id` FROM " . self::T_POP136_FASHION_USER . " WHERE `bind_mobile`=? and `user_from`=1";
        $res = $this->query($sql, $phone);

        return !empty($res) ? false : true;
    }

    /**
     * 验证账户是否存在
     *
     * @param $sAccount
     * @return bool
     */
    public function check_user_exists($sAccount)
    {
        $sql = "SELECT id FROM " . self::T_POP136_FASHION_USER . "  WHERE account=? ";
        $rs = $this->query($sql, $sAccount);

        return $rs ? $rs[0]['id'] : false;
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
        $sql = 'SELECT `iVerifyID` FROM ' . self::T_POP136_GLOBAL_CELLPHONE_VERIFY . ' WHERE `sCellPhone`= ? AND `sVerifyCode`= ? AND `iVerifyStatus`= 0 AND `iWebsite`=? limit 1';
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
        return $this->executeUpdate(self::T_POP136_GLOBAL_CELLPHONE_VERIFY, $aLog, $where);
    }

    /**
     * 写入到注册用户表pop136.fashion_user  fashion.fashion_user
     *
     * @param $sAccount
     * @param $sPassword
     * @param $sCellphone
     * @param int $itype
     * @param $captcha_code
     * @return array
     */
    public function registerCloudUser($sAccount, $sPassword, $sCellphone, $itype = 1, $captcha_code)
    {
        $rs = [];
        $rs['status'] = 0;
        $areaCodes = OpPopFree::getAreaCodes($sCellphone);
        $nowTime = date('Y-m-d H:i:s');
        $pop136UserInfo = [
            'area_id' => $areaCodes['FashionAreaId'],
            'account' => $sAccount,
            'passwd' => $sPassword,
            'user_from' => $itype,
            'tel' => $sCellphone,
            'mobile' => $sCellphone,
            'bind_mobile' => $sCellphone,
            'create_time' => $nowTime,
            'memo' => 'pop136_yuntu'
        ];
        $iUserID = $this->executeSave(self::T_POP136_FASHION_USER, $pop136UserInfo);
        if ($iUserID) {
            $aRegisterUser = [
                'id' => $iUserID,
                'account' => $sAccount,
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
            $bFlag_user = $this->executeSave(self::T_FASHION_FASHION_USER, $aRegisterUser);
            if ($bFlag_user) {
                $rs['status'] = 1;
                $rs['data'] = ['id' => $iUserID];
            }
        }

        return $rs;
    }

    /**
     * 获取栏目vip有效期
     *
     * @param string|int $userId 主账号ID
     * @return array|bool
     */
    public function getUserPowerDate($userId)
    {
        $nowTime = date('Y-m-d H:i:s');
        $sql = "SELECT `iPrivId`,`sColumn`,`isTrial`,MAX(`dEndTime`) AS `dEndTime` FROM " . self::T_FASHION_FM_PRIVILEGE_CLOUD . " WHERE `iAccountId`=? AND dStartTime<=? AND dEndTime>=? AND sDeviceType=? GROUP BY sColumn ORDER BY sColumn ASC";

        $result = $this->query($sql, [$userId, $nowTime, $nowTime, 'APP']);

        if (!$result) {
            return false;
        }

        return $result;
    }

    /**
     * 获取用户的虚拟样衣模板，vip用户默认
     *
     * @param $uId
     * @return mixed
     */
    public function getUserTplSite($uId)
    {
        $rs = $this->db->where(['id' => $uId])->get(self::T_POP136_FASHION_USER)->result_array();
        $iTplSite = $rs[0]['iTplSite'];

        return $iTplSite;
    }

    public function modTplSite($uId, $iTplSite)
    {
        // 无首饰
        if (!in_array($iTplSite, array(1, 2, 3, 5))) return false;

        $sql = "UPDATE pop136.fashion_user SET iTplSite={$iTplSite} WHERE id=$uId";
        return $this->db->query($sql);
    }

    public function getTemplates($iSite = 1, $iAccountId)
    {
        //云图 增加个人定制模板查询,条件如 iAccountId=0 OR iAccountId = 当前登录用户ID
        $where = " `iSite`={$iSite} AND `iStatus`=1 AND `iAccountId` IN (0,{$iAccountId}) ";
        $lists = $this->db->where($where)->order_by('iWeight DESC')->get('fashion.t_virtual_spl_template')->result_array();
        $list = array();
        foreach ($lists as $k => $v) {
            if (!key_exists($v['iClassifyId'], $list)) {
                $list[$v['iClassifyId']] = array();
            }
            $list[$v['iClassifyId']][] = $v;

            //是否有定制模板
            if ($v['iClassifyId'] == '12218') {
                $list['isCustom'] = '1';  //1：有定制模板
            }
        }

        $lists = $list;
        return $lists;
    }

    // 获取云图普通用试用模板次数
    public function getYuntuTryNum($userId, $type)
    {
        $sql = "SELECT * FROM " . self::T_YUNTU_TRY_NUM . " WHERE `iAccountId`=? AND iType=? AND iStatus=0  ORDER BY dCreateTime DESC LIMIT 1";
        $result = $this->query($sql, [$userId, $type]);

        return $result[0]['iTryNum'] ? intval($result[0]['iTryNum']) : 0;
    }

    // 增加云图普通用试用模板次数
    public function addYuntuTryNum($userId, $type)
    {
        $sql = "SELECT * FROM " . self::T_YUNTU_TRY_NUM . " WHERE `iAccountId`=?  AND iType=? AND iStatus=0  ORDER BY dCreateTime DESC LIMIT 1";
        $result = $this->query($sql, [$userId, $type]);

        $nowTime = date('Y-m-d H:i:s');
        if ($result) {
            $data_update = [
                'iTryNum' => $result[0]['iTryNum'] + 1,
                'dUpdateTime' => $nowTime,
            ];
            $where_update = [
                'iAccountId' => $userId,
                'iType' => $type,
            ];
            return $this->executeUpdate(self::T_YUNTU_TRY_NUM, $data_update, $where_update);
        } else {
            $data_save = [
                'iAccountId' => $userId,
                'iType' => $type,
                'dCreateTime' => $nowTime,
                'iTryNum' => 1, // 创建加1
                'iStatus' => 0, // 0-有效
            ];
            return $this->executeSave(self::T_YUNTU_TRY_NUM, $data_save);
        }
    }
}