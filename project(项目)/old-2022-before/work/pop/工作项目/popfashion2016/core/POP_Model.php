<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Model Class
 */
class POP_Model extends CI_Model
{
    //==============================pop136==========================================
    //pop136用户账号总表
    const T_POP136_FASHION_USER = '`pop136`.`fashion_user`';
    // pop136用户电子合同表
    const T_POP136_GLOBAL_ELECTRONIC_CONTRACT = '`pop136`.`global_electronic_contract`';
    //子账号注册验证码表
    const T_POP136_GLOBAL_CELLPHONE_VERIFY = '`pop136`.`global_cellphone_verify`';
    //子账号职称表
    const T_POP136_GLOBAL_POSITIONS = '`pop136`.`global_positions`';
    //推荐链接表
    const T_POP136_GLOBAL_RECOMMEND = '`pop136`.`global_recommend`';
    // 推荐链接结果表
    const T_POP136_GLOBAL_RECOMMEND_RESULT = '`pop136`.`global_recommend_result`';
    // 鞋子试用开通记录表
    const T_POP136_LOG_TRAIL_SHOE = '`pop136`.`log_trail_shoe`';
    //注册成功表
    const T_POP136_REGISTER_INFO = '`pop136`.`temp_register_info`';
    const T_POP136_UNBIND_CELLPHONE_VERIFY = '`pop136`.`unbind_cellphone_verify`';
    //网站注册临时中间表 
    const T_POP136_GLOBAL_USERS_TMP = '`pop136`.`global_users_tmp`';

    //IP登录限制表
    const T_POP136_T_VISIT_IP = 'pop136.t_visit_ip';


    //===============================服装============================================
    // 服装用户账号表
    const T_FASHION_FASHION_USER = '`fashion`.`fashion_user`';
    // 服装用户子账号表
    const T_FASHION_FASHION_USER_CHILD = '`fashion`.`fashion_user_child`';
    // 服装用户账号权限表
    const T_FASHION_FM_PRIVILEGE = '`fashion`.`fm_privilege`';
    //服装子账号基本信息表
    const T_FASHION_FASHION_USER_CHILD_INFORMATION = '`fashion`.`fashion_user_child_information`';
    //主账号账号登陆日志表
    const T_FASHION_LOGIN_LOG = '`fashion`.`fashion_login_log`';
    //试用登录日志
    const T_FASHION_TRAIL_LOGIN = '`fashion`.`fashion_trail_login`';
    //子账号登陆日志表
    const T_FASHION_USER_CHILD_LOGIN_LOG = '`fashion`.`fashion_user_child_login_log`';
    //限制用户登陆IP
    const T_FASHION_USER_LOGIN_IP = '`fashion`.`fashion_user_login_ip`';
    //VIP绑定客户端记录日志表
    const T_CLIENT_LOG = '`fashion`.`client_log`';
    //登陆固定用户IP段限制表
    const T_FASHION_FASHION_USER_LOGIN_IP = '`fashion`.`fashion_user_login_ip`';
    //积分消费明细表
    const T_INTEGRAL_CONSUME = '`fashion`.`integral_consume`';
    //订单头表
    const T_ORDER_HEADER = '`fashion`.`order_header`';
    //订单详细表
    const T_ORDER_DETAILS = '`fashion`.`order_details`';
    //服装开通试用或VIP日志记录
    const T_FASHION_FASHION_USER_CONSUME = '`fashion`.`fashion_user_consume`';
    //服装专区权限记录
    const T_FASHION_FASHION_SPECIAL = '`fashion`.`fashion_special`';
    const T_FASHION_FASHION_ADMIN_USER = '`fashion`.`fashion_admin_user`';
    const T_FASHION_USER_PASSWORD_CHANGE = '`fashion`.`user_password_change`';
    const T_FASHION_T_APM_RECORD = '`fashion`.`t_apm_record`';
    //分享记录表
    const T_POP_SHARE_LOG = '`pop_gather_sys`.`t_share_log`';
    const T_FASHION_FASHION_AREA = '`fashion`.`fashion_area`';
    const T_FASHION_FASHION_CLIENT = '`fashion`.`fashion_client`';
    const T_FASHION_FM_WORKBENCH = '`fashion`.`fm_workbench`';
    //工作台图片表
    const T_FASHION_FM_WORKBENCH_IMG = '`fashion`.`fm_workbench_img`';
    //下载包表
    const T_FASHION_FM_DOWNLOAD = '`fashion`.`fm_download`';
    //资料包表
    const T_FASHION_ORDER_PACKAGE = '`fashion`.`order_package`';
    //收藏表(关注)
    const T_FASHION_FM_COLLECT = '`fashion`.`fm_collect`';
    //广告表
    const  T_FASHION_FM_AD = '`fashion`.`fm_ad`';
    //栏目表
    const T_FASHION_T_DICT_COLUMN = '`fashion`.`t_dict_column`';
    //箱包用户账号表
    const T_BAGS_SHOE_USER = '`popbags`.`shoe_user`';
    //老鞋子用户账号表
    const T_SHOE_SHOE_USER = '`popshoe`.`shoe_user`';
    //首饰用户账号表
    const T_DECORATION_SHOE_USER = '`decoration`.`shoe_user`';
    //家纺用户账号表
    const T_HOMETEXTILE_SHOE_USER = '`hometextile`.`shoe_user`';
    //韩版
    const T_KOREA_USER_APPEND_MESSAGE = '`fashion`.`t_korea_user_append_message`';
    const T_FASHION_FM_INDEX = '`fashion`.`fm_index`';
    const T_FASHION_T_INDEX_REC_REPORTS = '`fashion`.`t_index_rec_reports`';
    //热门关键字
    const T_FASHION_T_FM_HOT_KEYWORDS = '`fashion`.`fm_hot_keywords`';

    //消息表
    const T_FASHION_T_MESSAGE_PUSH = '`fashion`.`t_message_push`';

    //资料包表
    const T_FASHION_T_ORDER_PACKAGE = '`fashion`.`order_package`';

    //消息访问表
    const T_FASHION_T_MESSAGE_VISIT = '`fashion`.`t_message_visit`';

    //图案区域表
    const T_FASHION_FM_DESIGN_AREA = '`fashion`.`fm_design_area`';

    //用户图片上传表
    const T_FASHION_FM_UPLOAD_PIC_MATERIAL = '`fashion`.`fm_upload_pic_material`';

    //广告浏览量统计表
    const T_FASHION_FM_AD_HITS = '`fashion`.`fm_ad_hits`';

    //韩版东大门资料
    const T_FASHION_T_KOREA_DONGDAEMUNZ_ZIP = '`fashion`.`t_korea_dongdaemun_zip`';//东大门资料表
    const T_FASHION_T_KOREA_DONGDAEMUNZ_ZIP_PRIVILEGE = '`fashion`.`t_korea_dongdaemun_zip_privilege`';//东大门资料权限表
    const T_FASHION_T_KOREA_DONGDAEMUNZ_ZIP_LOG = '`fashion`.`t_korea_dongdaemun_zip_log`';//东大门资料下载日志表


    //趋势手稿和矢量文件关联表
    const T_FASHION_T_VECTOR_RELATED_TO_DESIGN = '`fashion`.`t_vector_related_to_design`';

    //友情链接
    const T_POP_GENERALIZE_T_FRIENDLY_LINK = '`pop_generalize`.`t_friendly_link`';

    /**
     * Class constructor
     * @return    void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed CI_DB_mysqli_driver
     */
    public function db()
    {
        if (!is_object($this->db)) {
            $this->load->database();
        }
        return $this->db;
    }

    /**
     * 根据SQL查询， 参数通过$param绑定
     * @param string $sql 查询语句，如SELECT * FROM some_table WHERE id = ? AND status = ? AND author = ?
     * @param array $param array(3, 'live', 'Rick')
     * @param boolean $param mysql链接是否关闭，true=>关闭，false=>不关闭
     * @return array 未找到记录返回空数组，找到记录返回二维数组
     */
    public function query($sql, $param = array(), $ic = true)
    {
        $query = $this->db()->query($sql, $param);
        $rs = $query->result_array();
        if ($ic) {
            // $this->db->close();
        }
        return $rs;
    }

    /**
     * 更新操作update
     * @param $tableName
     * @param $data
     * @param $condition
     * @param bool $ic
     * @return mixed
     */
    public function executeUpdate($tableName, $data, $condition, $ic = true)
    {
        $this->db();
        $rs = $this->db->set($data)->where($condition)->update($tableName);
        if ($ic) {
            // $this->db->close();
        }
        return $rs;
    }

    /**
     * 批量更新操作update
     * @param $tableName表名
     * @param $data要修改的数据
     * @param $key条件的字段
     * @param $val条件的值
     * @param bool $ic
     * @return mixed
     */
    public function executeBatchupdate($tableName, $data, $key, $val, $ic = true)
    {
        $this->db();
        //使用where_in修改多条信息
        $rs = $this->db->set($data)->where_in($key, $val)->update($tableName);
        //判断是否关闭连接
        if ($ic) {
            // $this->db->close();
        }
        //返回修改结果
        return $rs;
    }

    /**
     * 添加操作insert
     * @param $tableName
     * @param $data
     * @param bool $ic
     * @return mixed
     */
    public function executeSave($tableName, $data, $ic = true)
    {
        $this->db();
        $this->db->insert($tableName, $data);
        $insertId = $this->db->insert_id();
        if ($ic) {
            // $this->db->close();
        }
        return $insertId;
    }

    /**
     * 获取一个json输出类实例
     * @return mixed
     */
    public function getJsonOutputObj()
    {
        if (!$this->pop_outputjson) {
            $this->load->library('POP_OutputJson');
        }
        return $this->pop_outputjson;
    }


    /**
     * 获取列表数据收藏状态
     * @param array $searchList solr查询数据列表
     * @return array 关联数组['{$pop_id}'=> {$status}]  $status 1、已收藏 0、未收藏
     */
    public function getListDataCollectStatus($searchList)
    {
        // 有无收藏权限
        $aPower = memberPower();
        if (!$aPower['P_Collect']) {
            return [];
        }
        // 谁收藏的
        $aLogonMessage = get_cookie_value();
        $accountId = $aLogonMessage['sChildID'];
        if (!$accountId || !$searchList) {
            return [];
        }

        // 构造sql语句
        $aTableNames = [];
        $aPopIds = [];
        foreach ($searchList as $_val) {
            $aTableNames[] = "'{$_val['tablename']}'";
            $aPopIds[] = "'{$_val['pop_id']}'";
        }
        $aTableNames = array_unique($aTableNames);
        $strTableNames = $aTableNames ? " `sTableName` IN (" . implode(',', $aTableNames) . ") AND " : '';
        $strPopIds = $aPopIds ? " CONCAT(`sTableName`, '_', `iPriId`) IN (" . implode(',', $aPopIds) . ") AND  " : '';
        $sql = "SELECT CONCAT(`sTableName`, '_', `iPriId`) AS pop_id FROM `fm_collect` WHERE `sAccountId`='{$accountId}' AND {$strTableNames} {$strPopIds} iStatus=0";
        $rows = $this->query($sql);

        $collectStatusList = [];
        foreach ($rows as $_row) {
            $collectStatusList[] = $_row['pop_id'];
        }

        return array_unique($collectStatusList);
    }


    public function __destruct()
    {
        if (is_object($this->db)) {
            $this->db->close();
        }
    }

}
