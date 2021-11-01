<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Workbench_model
 * @property-read common_model $common_model
 */
class Workbench_model extends POP_Model
{
    //返回结果数组数组
    public $json = array('status' => -1, 'info' => '', 'data' => array());
    //服装注册非VIP网站分类代码
    private $iWebsite = 10;
    //网站名称
    public $website = 'fashion';
    //网站对应值
    public $websites = array(
        'fashion' => 1, 'bags' => 2,
        'shoe' => 3, 'decoration' => 4,
        'hometextile' => 5, 'mostrend' => 8
    );
    //站点全部对应值 1-服装、2-箱包、3-鞋子、4-首饰、5-家纺、8-高端趋势、10-服装开通试用验证码、20-箱包开通试用、30-鞋子开通试用、40-首饰开通试用、50-家纺开通试用
    private $aWebsites = array(10, 20, 30, 40, 50);
    //验证码生存时间60s
    private $codeExpireTime = 60;
    //注册非VIP试用前缀
    private static $trialPrefix = 'popsy';

    //账号积分数量
    public $Score = 0;
    //现绑定子账号数量
    public $UserInfo = array();
    public $ChildNumber = 0;
    public $ChildAll = 0;
    public $ChildList = array();

    //-----------------

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 根据账号id得到我的工作台信息
     * @param integer $sAccountId 用户ID
     * @return array 工作台信息
     */
    public function getWorkBenchByAccountId($sAccountId)
    {
        $sql = "SELECT * FROM " . self::T_FASHION_FM_WORKBENCH . " WHERE `sAccountId`=? AND iStatus=0 ORDER BY iWorkbenchId DESC";
        $result = $this->query($sql, $sAccountId);
        return $result;
    }

    /**
     * 根据工作台id、子账号id得到图片信息
     * @param string $sAccountId 子账号id
     * @param integer $iWorkbenchId 工作台id
     * @param integer $limit 获取图片数量
     * @return array 图片信息
     */
    public function getWorkBenchPicList($sAccountId, $iWorkbenchId, $limit = 99)
    {

        $sql = "SELECT * FROM " . self::T_FASHION_FM_WORKBENCH_IMG . " WHERE `sAccountId`=? AND `iWorkbenchId`=? AND iStatus=0 ORDER BY iImgId DESC LIMIT ?";
        $result = $this->query($sql, array($sAccountId, $iWorkbenchId, $limit));
        return $result;
    }

    /**
     * 根据工作台id、子账号id得到图片信息
     * @param string $sAccountId 子账号id
     * @param integer $iWorkbenchId 工作台id
     * @param int $page
     * @param int $pagesize
     * @param $totalCount
     * @return array 图片信息
     */
    public function getWorkbenchPic($sAccountId, $iWorkbenchId, $page = 1, $pagesize = 99, &$totalCount)
    {
        $sql = "SELECT count(*) as total FROM " . self::T_FASHION_FM_WORKBENCH_IMG . " WHERE `sAccountId`=? AND `iWorkbenchId`=? AND iStatus=0 ";
        $rel = $this->query($sql, [$sAccountId, $iWorkbenchId]);
        $totalCount = $rel[0]['total'];

        $offset = ($page - 1) * $pagesize;
        $sql = "SELECT * FROM " . self::T_FASHION_FM_WORKBENCH_IMG . " WHERE `sAccountId`=? AND `iWorkbenchId`=? AND iStatus=0 ORDER BY iImgId DESC LIMIT ?,?";
        return $this->query($sql, [$sAccountId, $iWorkbenchId, $offset, $pagesize]);
    }

    /**
     * 根据图片id得到图片详细信息
     * @param integer $imgId 图片id
     * @param string $table 图片所在原表
     * @param string $base 图片所在原库
     * @return array 图片信息
     */
    public function getPic($base, $table, $imgId)
    {
        $sql = "SELECT * FROM $base.`$table` WHERE `id`=?";
        $result = $this->query($sql, $imgId);
        return $result[0];
    }


    /**
     * 根据图片id得到图片详细信息
     * @param integer $id 工作台id
     * @param integer $sAccountId 用户id
     * @param string $sWbenchName 工作台名称
     * @return array 工作台信息
     */
    public function checkWorkBenchExisted($id, $sAccountId, $sWbenchName)
    {
        $sql = "SELECT * FROM " . self::T_FASHION_FM_WORKBENCH . " WHERE `iWorkbenchId`<>? AND `sAccountId`=? AND `sWbenchName`=? AND iStatus=0";
        $result = $this->query($sql, array($id, $sAccountId, $sWbenchName));
        return $result;
    }

    /**
     * 根据工作台id得到工作台信息
     * @param integer $id 工作台id
     * @return array 工作台信息
     */
    public function getWorkBenchById($id)
    {
        $sql = "SELECT * FROM " . self::T_FASHION_FM_WORKBENCH . " WHERE `iWorkbenchId`=? AND iStatus=0";
        $result = $this->query($sql, [$id]);
        return $result;
    }

    /**
     * 修改工作台信息
     * @param string $sWbenchDesc 工作台描述
     * @param string $sWbenchName 工作台名称
     * @param integer $iWorkbenchId 工作台id
     * @return boolean
     */
    public function updateWorkBench($sWbenchName, $sWbenchDesc, $iWorkbenchId)
    {
        $wbenchInfo['sWbenchName'] = $sWbenchName;
        $wbenchInfo['sWbenchDesc'] = $sWbenchDesc;
        $condition['iWorkbenchId'] = $iWorkbenchId;
        $rs = $this->executeUpdate(self::T_FASHION_FM_WORKBENCH, $wbenchInfo, $condition);
        return $rs;
    }


    /**
     * 创建工作台信息
     * @param array $data 工作台信息
     * @return int
     */
    public function createWorkbench($data)
    {
        return $this->executeSave(self::T_FASHION_FM_WORKBENCH, $data);
    }

    /**
     * 删除工作台信息
     * @param integer $iWorkbenchId 工作台id
     * @return boolean
     */
    public function deleteWorkbench($iWorkbenchId)
    {
        $data['iStatus'] = 1;
        $condition['iWorkbenchId'] = $iWorkbenchId;
        $res = $this->executeUpdate(self::T_FASHION_FM_WORKBENCH, $data, $condition);
        return $res;
    }

    /**
     * 插入生成的压缩包记录
     * @param array $data 压缩包信息
     * @return int
     */
    public function insertDownBag($data)
    {
        return $this->executeSave(self::T_FASHION_FM_DOWNLOAD, $data);
    }

    /**
     * 根据用户id得到我的下载包
     * @param integer $sAccountId 用户id
     * @return array 下载包信息
     */
    public function getMyDownLoadByAccountId($sAccountId)
    {
        $sql = "SELECT * FROM " . self::T_FASHION_FM_DOWNLOAD . " WHERE `sAccountId`=? ORDER BY dCreateTime DESC LIMIT 10";
        $result = $this->query($sql, $sAccountId);
        return $result;
    }

    /**
     * 根据用户id得到我的资料包
     * @param integer $sAccountId 用户id
     * @return array 下载包信息
     */
    public function getMyOrderZipByAccountId($sAccountId, $pUserType, $pExpireTime)
    {
        $dateTime = date('Y-m-d H:i:s');
        if ($pUserType == 2) {
            //主账号下所有子账号都推送资料包
            //如果是子账号，查询对应的主账号ID是否在资料包表中，如果存在则返回资料包
            $sql_1 = "select iParentId from fashion.fashion_user_child WHERE sChildId = ? LIMIT 1";
            $row = $this->query($sql_1, $sAccountId);
            $sAccountId = $row[0]['iParentId'];

            $sql = "SELECT * FROM " . self::T_FASHION_ORDER_PACKAGE . " WHERE find_in_set(?,`sPushAccount`) and `iStatus` = 0  AND (dPushTime < ? and dPushTime <= ?) ORDER BY `dPushTime` DESC";
            $res = $this->query($sql, [$sAccountId, $pExpireTime, $dateTime]);
        }
        if ($pUserType == 4) {
            //主账号下所有子账号都推送资料包
            //如果是子账号，查询对应的主账号ID是否在资料包表中，如果存在则返回资料包
            $sql_1 = "select iParentId from fashion.fashion_user_child WHERE sChildId = ? LIMIT 1";
            $row = $this->query($sql_1, $sAccountId);
            $sUserId = $row[0]['iParentId'];
            if ($sUserId) {
                $sql = "select iAccountId,dEndTime from fashion.fm_privilege WHERE iAccountId = ? ORDER BY dEndTime DESC limit 1";
                $endTime = $this->query($sql, $sUserId);
                $dEndTime = $endTime[0]['dEndTime'];
                $sql = "SELECT * FROM " . self::T_FASHION_ORDER_PACKAGE . " WHERE find_in_set(?,`sPushAccount`) and `iStatus` = 0  AND dPushTime <? AND  dPushTime <= ? ORDER BY `dPushTime` DESC";
                $res = $this->query($sql, [$sUserId, $dEndTime, $dateTime]);
            } else {
                $sql = "select iAccountId,dEndTime from fashion.fm_privilege WHERE iAccountId = ? ORDER BY dEndTime DESC limit 1";
                $endTime = $this->query($sql, $sAccountId);
                $dEndTime = $endTime[0]['dEndTime'];
                $sql = "SELECT * FROM " . self::T_FASHION_ORDER_PACKAGE . " WHERE find_in_set(?,`sPushAccount`) and `iStatus` = 0  AND dPushTime <? AND  dPushTime <= ? ORDER BY `dPushTime` DESC";
                $res = $this->query($sql, [$sAccountId, $dEndTime, $dateTime]);
            }
        }

        return $res;
    }

    //前台调用资料包分页列表
    public function getMyOrderZipList($sAccountId, $pUserType, $pExpireTime, $offset, $pageSize)
    {
        $dateTime = date('Y-m-d H:i:s');
        if ($pUserType == 2) {
            //主账号下所有子账号都推送资料包
            //如果是子账号，查询对应的主账号ID
            $sql_1 = "select iParentId from fashion.fashion_user_child WHERE sChildId = ? LIMIT 1";
            $row = $this->query($sql_1, $sAccountId);
            $sAccountId = $row[0]['iParentId'];

            $sql = "SELECT * FROM " . self::T_FASHION_ORDER_PACKAGE . " WHERE find_in_set(?,`sPushAccount`) and `iStatus` = 0  AND (dPushTime < ? and dPushTime <= ?) ORDER BY `dPushTime` DESC LIMIT ?,?";
            $list = $this->query($sql, [$sAccountId, $pExpireTime, $dateTime, $offset, $pageSize]);
        }
        if ($pUserType == 4) {
            $sql_1 = "select iParentId from fashion.fashion_user_child WHERE sChildId = ? LIMIT 1";
            $row = $this->query($sql_1, $sAccountId);
            $sUserId = $row[0]['iParentId'];
            if ($sUserId) {
                $sql = "select iAccountId,dEndTime from fashion.fm_privilege WHERE iAccountId = ? ORDER BY dEndTime DESC limit 1";
                $endTime = $this->query($sql, $sUserId);
                $dEndTime = $endTime[0]['dEndTime'];
                $sql = "SELECT * FROM " . self::T_FASHION_ORDER_PACKAGE . " WHERE find_in_set(?,`sPushAccount`) and `iStatus` = 0  AND dPushTime < ? AND dPushTime <= ? ORDER BY `dPushTime` DESC LIMIT ?,?";
                $list = $this->query($sql, [$sUserId, $dEndTime, $dateTime, $offset, $pageSize]);
            } else {
                $sql = "select iAccountId,dEndTime from fashion.fm_privilege WHERE iAccountId = ? ORDER BY dEndTime DESC limit 1";
                $endTime = $this->query($sql, $sAccountId);
                $dEndTime = $endTime[0]['dEndTime'];
                $sql = "SELECT * FROM " . self::T_FASHION_ORDER_PACKAGE . " WHERE find_in_set(?,`sPushAccount`) and `iStatus` = 0  AND dPushTime < ? AND dPushTime <= ? ORDER BY `dPushTime` DESC LIMIT ?,?";
                $list = $this->query($sql, [$sAccountId, $dEndTime, $dateTime, $offset, $pageSize]);
            }
        }

        return $list;
    }

    /**
     * 根据用户id得到我的收藏
     * @param integer $sAccountId 用户id
     * @param string $column 收藏类别 1=>发布会 2=>品牌 素材类[3=>款式模板 4=>款式细节 5=>图案素材 6=>面料 7=>店铺陈列] 8=>图册 灵感[9=>灵感报告 10=>灵感图库] 11=>趋势 12=>分析
     * @return array 我的收藏信息
     */
    public function getCollect($sAccountId, $column = 0, $page, $pagesize = 1)
    {

        $start = $page > 0 ? ($page - 1) * $pagesize : 0;
        $sql = "SELECT * FROM " . self::T_FASHION_FM_COLLECT . " WHERE `sAccountId`=? AND iType in(?) AND iStatus=0 ORDER BY iCollectId DESC LIMIT ?,?";
        $result = $this->query($sql, [$sAccountId, $column, $start, $pagesize]);
        return $result;
    }

    /**
     * 根据用户id得到我的收藏总数
     * @param integer $sAccountId 用户id
     * @param string $column 收藏类别 1=>发布会 2=>品牌 素材类[3=>款式模板 4=>款式细节 5=>图案素材 6=>面料 7=>店铺陈列] 8=>图册 灵感[9=>灵感报告 10=>灵感图库] 11=>趋势 12=>分析
     * @return array 我的收藏信息
     */
    public function getCollectCount($sAccountId, $column = 0)
    {
        if (!$column) {
            $sql = "SELECT count(*) as total FROM " . self::T_FASHION_FM_COLLECT . " WHERE `sAccountId`=? AND iStatus=0";
            $result = $this->query($sql, $sAccountId);
        } else {
            $sql = "SELECT count(*) as total FROM " . self::T_FASHION_FM_COLLECT . " WHERE `sAccountId`=? AND iType in($column) AND iStatus=0";
            $result = $this->query($sql, array($sAccountId));
        }
        return $result[0];
    }

    /**
     * 删除单张图片
     * @param integer iImgId 工作台id
     * @return boolean
     */
    public function deletePic($iImgId)
    {
        $data['iStatus'] = 1;
        $condition['iImgId'] = $iImgId;
        $res = $this->executeUpdate(self::T_FASHION_FM_WORKBENCH_IMG, $data, $condition);
        return $res;
    }

    /**
     * 删除批量图片
     * @param integer iImgId 工作台id
     * @return boolean
     */
    public function deleteBath($iImgId)
    {
        //要修改的字段与值
        $data['iStatus'] = 1;
        //条件的字段
        $key = 'iImgId';
        //条件的值（是个数组）
        $val = $iImgId;
        //请求的方法参数1表名，参数2修改的数据，参数3条件的键，参数4条件的值
        $res = $this->executeBatchupdate(self::T_FASHION_FM_WORKBENCH_IMG, $data, $key, $val);
        //返回修改的结果
        return $res;
    }

    public function cancelFocus($id)
    {
        $data['iStatus'] = 1;
        $condition['iCollectId'] = $id;
        $rs = $this->executeUpdate(self::T_FASHION_FM_COLLECT, $data, $condition);
        return $rs;
    }

    function cutString($string, $sublen, $start = 0, $code = 'UTF-8')
    {
        if ($code == 'UTF-8') {
            $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
            preg_match_all($pa, $string, $t_string);

            if (count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen)) . "...";
            return join('', array_slice($t_string[0], $start, $sublen));
        } else {
            $start = $start * 2;
            $sublen = $sublen * 2;
            $strlen = strlen($string);
            $tmpstr = '';

            for ($i = 0; $i < $strlen; $i++) {
                if ($i >= $start && $i < ($start + $sublen)) {
                    if (ord(substr($string, $i, 1)) > 129) {
                        $tmpstr .= substr($string, $i, 2);
                    } else {
                        $tmpstr .= substr($string, $i, 1);
                    }
                }
                if (ord(substr($string, $i, 1)) > 129) $i++;
            }
            if (strlen($tmpstr) < $strlen) $tmpstr .= "...";
            return $tmpstr;
        }
    }

    // 根据用户id获取的工作台列表
    public function getList($fields = '*', $cnt = false, $sAccountId = '')
    {
        if (empty($sAccountId)) {
            $sAccountId = getUserId();
        }
        $sql = 'SELECT ' . $fields . ' FROM ' . self::T_FASHION_FM_WORKBENCH . ' WHERE sAccountId=? AND iStatus=0 order by dUpdateTime desc';
        $list = $this->query($sql, $sAccountId);
        $res = ['list' => [], 'total' => 0];
        if (!empty($list)) {
            $res['list'] = $list;
            if ($cnt) {
                $_cnt = $this->getWKNum($sAccountId);
                $res['total'] = $_cnt;
            }
        }
        return $res;
    }

    //加入工作台时更新时间，为了列表展示最新操作状态的工作台列表
    public function updateWorkbenchTime($workbenchId, $updateTime)
    {
        if (!empty($workbenchId)) {
            $workbenchId = intval($workbenchId);
        }
        $data['dUpdateTime'] = $updateTime;
        $conditions['iWorkbenchId'] = $workbenchId;
        $rs = $this->executeUpdate(self::T_FASHION_FM_WORKBENCH, $data, $conditions);
        return $rs;
    }

    // 获取工作台的数量
    public function getWKNum($sAccountId = '')
    {
        if (empty($sAccountId)) {
            $sAccountId = getUserId();
        }
        $sql = 'SELECT COUNT(*) cnt FROM ' . self::T_FASHION_FM_WORKBENCH . ' WHERE sAccountId=? AND iStatus=0';
        $res = $this->query($sql, $sAccountId);
        return intval($res[0]['cnt']);
    }

    // 根据工作台名称判断工作台是否已经存在
    public function isExistWBByName($name, $sAccountId = '')
    {
        if (empty($name)) return false;
        if (empty($sAccountId)) {
            $sAccountId = getUserId();
        }
        $sql = 'SELECT COUNT(*) cnt FROM ' . self::T_FASHION_FM_WORKBENCH . ' WHERE sAccountId=? AND sWbenchName=? AND iStatus=0';
        $res = $this->query($sql, [$sAccountId, $name]);
        return intval($res[0]['cnt']) ? true : false;
    }

    //用户类型
    public function _getAccountType($accountType)
    {
        if ($accountType == '1') {
            $type = "企业账号";
        } else {
            $type = "设计师专属账号";
        }
        $CI = &get_instance();
        $CI->assign('type', $type);
    }

    //会员类型
    public function _getVipType($vipTypeArr)
    {
        //账号显示时间
        //array(账号类型P_UserType => 1 主账号是vip , 2子账号是vip 、3试用 4普通 5游客);
        $show_btn = '';
        if (in_array($vipTypeArr['P_UserType'], [1, 2])) {
            if ($vipTypeArr['P_UserType'] == 1) {
                $vip_type = "VIP主账号";
            }
            if ($vipTypeArr['P_UserType'] == 2) {
                $vip_type = "设计师专属账号";
            }
            if (strtotime($vipTypeArr['P_ExpireTime']) < strtotime('+3 month')) {
                $show_btn = 'renew';//续费
            }
        }

        switch ($vipTypeArr['P_UserType']) {
            case 3:
                $vip_type = "试用会员";
                break;
            case 4:
                $vip_type = '开通VIP 看高清大图';
                $show_btn = 'open';//开通
                break;
        }

        $CI = &get_instance();
        $CI->assign('vip_type', $vip_type);
        $CI->assign('userType', $vipTypeArr['P_UserType']);
        $CI->assign('show_btn', $show_btn);
    }

    //会员权限
    public function _getVipPrivilege($vipTypeArr)
    {
        //账号类型P_UserType => 1 主账号是vip , 2子账号是vip 、3试用 4普通 5游客;
        switch ($vipTypeArr['P_UserType']) {
            case 1:
            case 2:
            case 3:
                $privilege = "浏览高清大图";
                break;
            case 4:
                $privilege = "浏览部分大图";
                break;
            case 5:
                $privilege = "免费浏览小图";
                break;
        }
        $CI = &get_instance();
        $CI->assign('privilege', $privilege);
    }

    /**
     * 根据用户id得到上次登录时间
     * @param integer/string $sAccountId 用户id
     * @param integer $accoutType 用户类型
     * @return string 时间
     */
    public function getLastLogionTime($sAccountId, $accoutType)
    {
        $lastLogionTime = '';
        if ($accoutType == 1) {
            //主账号
            $sql = "SELECT create_time as lastLogionTime FROM " . self :: T_FASHION_LOGIN_LOG . " WHERE user_id=? ORDER BY create_time DESC LIMIT 2";
        } else {
            //子账号
            $sql = "SELECT dLoginTime as lastLogionTime FROM " . self :: T_FASHION_USER_CHILD_LOGIN_LOG . " WHERE sChildID=? ORDER BY dLoginTime DESC LIMIT 2";
        }
        $result = $this->query($sql, [$sAccountId]);
        $lastLogionTime = isset($result[1]['lastLogionTime']) && $result[1]['lastLogionTime'] ? $result[1]['lastLogionTime'] : '1970-01-01 07:00:00';
        return $lastLogionTime;
    }
}

