<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Zip_model
 * @property-read common_model $common_model
 */
class Zip_model extends POP_Model
{
    const FM_TEM_KOREA_DONGDAEMUN = 'FM_TEM_KOREA_DONGDAEMUN_';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取所有下载包信息
     * @param integer $aCondition 查询条件
     * @param integer $offset 开始
     * @param integer $limit 限制条数
     * @param integer $aPrivilege 权限
     * @param integer $totalCount 总数
     * @return array 下载包信息
     */
    public function getDongdaemunList($aCondition, $offset, $limit, $aPrivilege, &$totalCount)
    {
        $sWhereSql = '';
        $dNowTime = date('Y-m-d');
        foreach ($aCondition as $_key => $_value) {
            if ($_key == 'st') {
                $sWhereSql .= ' AND dValidTime >= "' . $_value . '"';
            } elseif ($_key == 'et') {
                $sWhereSql .= ' AND dValidTime <= "' . $_value . '"';
            }
        }
        $sWhereSql .= ' AND dValidTime <= "' . $dNowTime . '"';
        $sql = 'SELECT count(*) as total FROM ' . self::T_FASHION_T_KOREA_DONGDAEMUNZ_ZIP . ' WHERE iStatus=1' . $sWhereSql . ' ORDER BY dValidTime DESC';
        $query = $this->db()->query($sql);
        $result = $query->row_array();
        $totalCount = $result['total'];

        $sql = 'SELECT * FROM ' . self::T_FASHION_T_KOREA_DONGDAEMUNZ_ZIP . ' WHERE iStatus=1' . $sWhereSql . ' ORDER BY dValidTime DESC LIMIT ' . $offset . ',' . $limit;
        $result = $this->query($sql);
        if ($result) {
            foreach ($result as $_key => $_res) {
                if ($aPrivilege['isOpen'] == 1) {//判断权限
                    //开通过
                    foreach ($aPrivilege['aDetailInfo'] as $_privilege) {
                        if ((strtotime($_res['dValidTime']) <= strtotime($_privilege['dEndTime'])) && (strtotime($_res['dValidTime']) >= strtotime($_privilege['dStartTime']))) {
                            $result[$_key]['isPower'] = 1;
                            continue;
                        }
                    }
                }
                $result[$_key]['dValidTime'] = date('Y年m月d日', strtotime($_res['dValidTime']));
                if (strripos($_res['sLink'], 'http') !== false) { //加下载统计
                    $result[$_key]['sLink'] = '/statistics/dongdaemun/?url=' . base64_encode($_res['id']) . '_' . base64_encode($_res['sLink']);
                }
            }
        }
        return $result;
    }

    /**
     * 根据用户得到下载权限
     * @param integer $sAccountId 用户id
     * @return array 权限信息
     */
    public function getDongdaemunPrivilege($iAccountId)
    {
        $iAccountId = intval($iAccountId);
        //memcache缓存
        $this->load->driver('cache');
        $mem_key = self :: FM_TEM_KOREA_DONGDAEMUN . $iAccountId;
        $aPrivilege = $this->cache->memcached->get($mem_key);
        if (!$aPrivilege || $_GET['refresh']) {
            $aPrivilege = [];
            $aPrivilege['isOpen'] = 0;//是否开通过
            $aPrivilege['isValidity'] = 0;//是否有效期内

            $sql = "SELECT * FROM " . self::T_FASHION_T_KOREA_DONGDAEMUNZ_ZIP_PRIVILEGE . " WHERE iStatus=1 AND `iAccountId`=? ORDER BY dCreateTime DESC";
            $result = $this->query($sql, $iAccountId);
            if ($result) {
                $aPrivilege['isOpen'] = 1; //开通过
                foreach ($result as $_res) {
                    if (strtotime($_res['dEndTime']) >= strtotime(date('Y-m-d'))) {
                        $aPrivilege['isValidity'] = 1;//有效期内
                        continue;
                    }
                }
            }
            $aPrivilege['aDetailInfo'] = $result;
            $mem_result = $this->cache->memcached->save($mem_key, $aPrivilege, 3600);
        }
        return $aPrivilege;
    }
}

