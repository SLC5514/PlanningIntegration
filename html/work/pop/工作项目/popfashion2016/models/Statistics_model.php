<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 网站统计 （浏览、下载、收藏）
 * Class Statistics_model
 * @property-read common_model $common_model
 */
class Statistics_model extends POP_Model
{
    //const KEYPRE='data_statistics_';//收藏量、浏览量、下载量前缀
    public $keypre;
    private $refresh = false;

    //$Mkey=$this->keypre.$key.$table.'_'.$id;--- memcache  键值 =前缀+相关字符(例如：_collectCount_)+表名+_id名
    public function __construct()
    {
        parent::__construct();
        //memcache前缀
        $this->keypre = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_dataStatistics_';
        $this->load->driver('cache');
        $this->load->helper('cookie');
        if (isset($_GET['refresh']))
            $this->refresh = intval($this->input->get('refresh'));
    }

    /*
     * 设置收藏量
     */
    public function setCollectCount($table, $id, $iColumnId)
    {
        return $this->set($table, $id, '_collectCount_', 'iCollectCount', $iColumnId);
    }

    /*
     * 获取收藏量
    */
    public function getCollectCount($table, $id, $iColumnId = '')
    {
        return $this->get($table, $id, '_collectCount_', 'iCollectCount', $iColumnId);
    }

    //===============下载量==================
    public function setDownCount($table, $id, $iColumnId)
    {
        $key = '_downnum_';
        if ($table == 'product_tideleader') {
            $field = 'iDownnum';
        } else {
            $field = 'downnum';
        }
        if (in_array($table, array('product_design_refrence_details', 'product_brochures_photo', 'product_vector_refrence'))) {
            //书籍类，统计书的浏览和下载量
            $data = OpPopFashionMerger::getProductData($id, $table);
            switch ($table) {
                case 'product_design_refrence_details':
                    $ptable = 'product_design_refrence';
                    $pid = $data[$id]['pid'];
                    break;
                case 'product_brochures_photo':
                    $ptable = 'product_brochures';
                    $pid = $data[$id]['borochid'];
                    break;
                case 'product_vector_refrence':
                    $ptable = 'product_vector_refrence_group';
                    $pid = $data[$id]['groupid'];
                    break;
            }
            $this->set($ptable, $pid, $key, $field, $iColumnId);
        }
        return $this->set($table, $id, $key, $field, $iColumnId);
    }

    public function getDownCount($table, $id, $iColumnId = '')
    {
        $key = '_downnum_';
        if ($table == 'product_tideleader') {
            $field = 'iDownnum';
        } else {
            $field = 'downnum';
        }
        return $this->get($table, $id, $key, $field, $iColumnId);
    }

    //-------------------------------------以下私有方法-------------------------------------------------
    private function get($table, $id, $key, $field, $iColumnId)
    {
        $Mkey = $this->keypre . $key . $table . '_' . $id;
        $ret = $this->cache->memcached->get($Mkey);
        if ($ret != false) {
            return $ret;
        } else {
            $data = OpPopFashionMerger::getProductData($id, $table);
            $_count = intval($data[$id][$field]);
            $this->cache->memcached->save($Mkey, $_count);
            return $_count;
        }
    }

    private function set($table, $id, $key, $field, $iColumnId)
    {
        if (in_array($table, array('brand_library'))) {
            return false;//品牌不需要下载收藏量
        }
        //============memcache相关==================
        $Mkey = $this->keypre . $key . $table . '_' . $id;
        $tnowNum = $this->get($table, $id, $key, $field, $iColumnId);
        $nowNum = $tnowNum ? $tnowNum : 0;
        $val = 1 + $nowNum;//数据自加
        $this->cache->memcached->save($Mkey, $val, 86400);

        //=============队列相关======================
        $user_id = getUserId();
        $sAccountType = EncryptionDeciphering(get_cookie('_ACCOUNT_TYPE'), false);
        $params = [
            'table' => $table,
            'field' => $field,
            'id' => $id,
            'iColumnId' => $iColumnId,
            'user_id' => $user_id,
            'sAccountType' => $sAccountType,
            'time' => time()
        ];
        if (!$this->_spread($params, 'fashion_mostrend'))
            return false;
        else
            return true;
    }

    //初始队列
    private function _spread($params, $action, $type = 'person')
    {
        $OpPopAsyncMsg = new OpPopAsyncMsg();
        $return = $OpPopAsyncMsg->pop_async_msg_send($type, $action, $params, $group = 'korea_statistics');
        unset($OpPopAsyncMsg);
        return $return;
    }

    //统计广告点击量
    public function setAdsHits($iAdId)
    {
        $iAdId = intval($iAdId);
        $power = memberPower();
        //游客-5 普通用户-4 试用-3 vip子-2 vip-1
        $iAccountType = $power['P_UserType']; //用户类型
        $sAccountId = getUserId();
        $data = [
            'sAccountId' => $sAccountId,
            'iAccountType' => $iAccountType,
            'iAdId' => $iAdId,
            'dHitsTime' => date('Y-m-d H:i:s'),
        ];
        $this->db()->insert(self::T_FASHION_FM_AD_HITS, $data);
        return $this->db()->insert_id();
    }

    //统计韩版东大门下载量
    public function setDongdaemunDownloadNum($id)
    {
        $id = intval($id);
        $sAccountId = getUserId();//用户id
        $data = [
            'iAccount' => $sAccountId,
            'iZipId' => $id,
            'dDownloadTime' => date('Y-m-d H:i:s'),
        ];
        $this->db()->insert(self::T_FASHION_T_KOREA_DONGDAEMUNZ_ZIP_LOG, $data);
        return $this->db()->insert_id();
    }

    /**
     * 供应商广告展现次数统计
     * @param $iAdIds 所展现的广告的id
     * @return bool
     */
    public function vendorAdShowTimes($iAdIds)
    {
        if (!$this->_spread($iAdIds, 'vendor_ad'))
            return false;
        else
            return true;
    }


    /**
     * 供应商广告点击量统计
     * @param $iAdId
     * @return mixed
     */
    public function vendorAdClick($iAdId)
    {
        $iAdId = intval($iAdId);
        $power = memberPower();
        //游客-5 普通用户-4 试用-3 vip子-2 vip-1
        $iAccountType = $power['P_UserType']; //用户类型
        $sAccountId = getUserId();//用户id

        if ($iAccountType == 2) {
            $iUserId = get_cookie_value()['id'];
        } else {
            $iUserId = 0;
        }

        $data = [
            'iAdId' => $iAdId,
            'dClickTime' => date('Y-m-d H:i:s'),
            'iUserId' => $iUserId,
            'sAccountId' => $sAccountId,
            'sUserIp' => $this->input->ip_address()
        ];
        $this->db()->insert('`fashion`.`t_vendor_ad_click_rate`', $data);
        return $this->db()->insert_id();
    }


    /*
      + @ function		浏览量增量设置
      + @ $_Tab			数据表名
      + @ $_Id			主键ID
      + @ $_ColumnId	栏目ID
    */
    public function setIncrViews($_Tab, $_Id, $_ColumnId)
    {

        if (in_array($_Tab, array('product_design_refrence_details', 'product_brochures_photo', 'product_vector_refrence'))) {

            //书籍类，统计书的浏览和下载量 只统计册
            $_aTmp = OpPopFashionMerger::getProductData($_Id, $_Tab);

            switch ($_Tab) {
                case 'product_design_refrence_details':
                    $_Tab = 'product_design_refrence';
                    $_Id = $_aTmp[$_Id]['pid'];
                    break;

                case 'product_brochures_photo':
                    $_Tab = 'product_brochures';
                    $_Id = $_aTmp[$_Id]['borochid'];
                    break;

                case 'product_vector_refrence':
                    $_Tab = 'product_vector_refrence_group';
                    $_Id = $_aTmp[$_Id]['groupid'];
                    break;
            }
        }
        //前台显示增量		V
        $_sV_keys = OpPopFashionMerger::POP_FM_TEM_VIEWS_INCR_MEMCACHE_KEY . $_Tab . '_' . $_Id;
        $_iV = intval($this->cache->memcached->get($_sV_keys));

        //前台真实浏览量	V_real
        $_sVreal_keys = OpPopFashionMerger::POP_FM_TEM_REAL_VIEWS_MEMCACHE_KEY . $_Tab . '_' . $_Id;
        $iV_real = intval($this->cache->memcached->get($_sVreal_keys));

        //增量
        if (!$iV_real) {
            $iV_real = OpPopFashionMerger::getRealViews($_Tab, $_Id, true);
        }

        /*
          + 显示增量随机增加
          + 增量区间:
            a、300<真实浏览量<=800; rand( 75 , 150 ) rand( 15 , 30 );
            b、其他 rand( 75 , 150 );
          + 真实浏览量+1
        */
        if ($iV_real > 300 && $iV_real <= 800) {
            $_iV += rand(75, 150);
        } else {
            $_iV += rand(15, 30);
        }

        $iV_real++;

        if (in_array($_Tab, array('specialtopic', 'specialtopic_graphic'))) {
            $_sV_field = 'views';
        } elseif ($_Tab == 'mostrend_content') {
            $_sV_field = 'click_rate';
        } elseif (in_array($_Tab, array('product_tideleader', 't_trend_report', 't_digital_quick_print'))) {
            $_sV_field = 'iViewCount';
        } else {
            $_sV_field = 'view_count';
        }


        //加入spread队列
        $iUid = getUserId();
        $sAccountType = EncryptionDeciphering(get_cookie('_ACCOUNT_TYPE'), false);

        $params = [
            'table' => $_Tab,
            'field' => $_sV_field,
            'id' => $_Id,
            'iColumnId' => $_ColumnId,
            'user_id' => $iUid,
            'sAccountType' => $sAccountType,
            'time' => time()
        ];
        if (!$this->_spread($params, 'fashion_mostrend')) {
            return false;
        } else {
            $this->cache->memcached->save($_sV_keys, $_iV, 86400, true);
            $this->cache->memcached->save($_sVreal_keys, $iV_real, 86400, true);
            return true;
        }
    }

    /*

      + @ function	获取浏览量
      + @ $_Tab		表名
      + @ $_Var		当前记录
    */
    public function getViews($_Tab, $_Id, $_Var)
    {
        if (!$_Tab || !$_Id || !$_Var) {
            return 0;
        }
        /*
          + 特殊字段特殊库，查询源表记录总数
        */
        switch ($_Tab) {
            case 'specialtopic':
                $_Vfield = 'views';
                break;
            case 'mostrend_content':
                $_Vfield = 'click_rate';
                break;
            case 't_trend_report':
            case 'product_tideleader':
            case 't_digital_quick_print':
                $_Vfield = 'iViewCount';
                break;
            default:
                $_Vfield = 'view_count';
                break;
        }

        //前台显示增量		V
        $_sV_keys = OpPopFashionMerger::POP_FM_TEM_VIEWS_INCR_MEMCACHE_KEY . $_Tab . '_' . $_Id;
        //原浏览量+浏览增量
        return intval($_Var[$_Vfield]) + intval($this->cache->memcached->get($_sV_keys));

    }
}
