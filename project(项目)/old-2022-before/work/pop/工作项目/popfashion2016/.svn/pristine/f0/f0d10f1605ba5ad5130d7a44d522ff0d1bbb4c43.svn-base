<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Collect_model extends POP_Model
{

    const T_FM_COLLECT = 'fm_collect';


    //收藏临时缓存  FM_TEM_COLLECT_MD5( 表名_子账号ID_ID )
    const T_COLLECT_MEMCACHE_KEY = 'FM_TEM_COLLECT_';

    //收藏临时缓存 有效期
    const T_COLLECT_MEMCACHE_KEY_EXPIRE = 180;

    public function __construct()
    {
        parent::__construct();
    }

    /*
      + 加入收藏操作
    */
    public function insertCollect(& $data)
    {
        $this->db()->insert(self::T_FM_COLLECT, $data);

        $this->load->driver('cache');
        $sMemcacheKey = $this->getCollectMemcacheKey($data['sChildID'], $data['sTableName'], $data['iPriId']);

        if ($iInsertId = $this->db()->insert_id() > 0) {
            //收藏量+
            $this->load->model('statistics_model');
            $this->statistics_model->setCollectCount($data['sTableName'], $data['iPriId'], $data['iColumnId']);

            $data['iInsertId'] = $iInsertId;
            $this->cache->memcached->save(
                $sMemcacheKey, 'TRUE', self::T_COLLECT_MEMCACHE_KEY_EXPIRE
            );
            return true;
        } else {
            $data['iInsertId'] = 0;
            $this->cache->memcached->save(
                $sMemcacheKey, 'FALSE', self::T_COLLECT_MEMCACHE_KEY_EXPIRE
            );
            return false;
        }
    }

    /*
     + 详情页收藏
    */
    public function updateCollectSolr($table, $id)
    {
        $sql = "SELECT iCollectId,sAccountId,sTableName,iPriId FROM `fashion`.`fm_collect` WHERE sTableName='{$table}' AND iPriId=$id LIMIT 1";
        $rows = $this->db()->query($sql)->result_array();
        return OpPopFashionMerger::feedFmCollect($rows);
    }


    /*
      + 取消收藏
    */
    public function cancelCollect($data)
    {
        $this->db()->update(
            self::T_FM_COLLECT,
            array('iStatus' => 1),
            $data
        );
        $iAffectedRows = $this->db()->affected_rows();

        $this->load->driver('cache');
        $sMemcacheKey = $this->getCollectMemcacheKey($data['sAccountId'], $data['sTableName'], $data['iPriId']);

        if ($iAffectedRows > 0) {
            $this->cache->memcached->save(
                $sMemcacheKey, 'FALSE', self::T_COLLECT_MEMCACHE_KEY_EXPIRE
            );
            return true;
        } else {
            return false;
        }
    }


    /*
      + 验证是否收藏
      + @parameter $sChildID		子账号id
      + @parameter $sDatabase		数据库
      + @parameter $sTableName		数据表名
      + @parameter $iPriId			主键id
    */
    public function existCollectStatus($sChildID, $sDatabase, $sTableName, $iPriId)
    {

        $this->load->driver('cache');
        $sMemcacheKey = $this->getCollectMemcacheKey($sChildID, $sTableName, $iPriId);
        $memResult = $this->cache->memcached->get($sMemcacheKey);

        //验证是否已收藏
        if ($memResult == 'TRUE') {
            return true;
        }

        $sExistSql = 'SELECT iCollectId FROM ' . self::T_FM_COLLECT . ' WHERE iPriId="' . $iPriId . '" AND sAccountId="' . $sChildID . '" AND sTableName="' . $sTableName . '" AND sDatabase="' . $sDatabase . '" AND iStatus=0 LIMIT 1';

        $num_rows = $this->db()->query($sExistSql)->num_rows();
        // $this->db()->close();
        if ($num_rows > 0) {
            $this->cache->memcached->save(
                $sMemcacheKey, 'TRUE', self::T_COLLECT_MEMCACHE_KEY_EXPIRE
            );
            return true;
        } else {
            $this->cache->memcached->save(
                $sMemcacheKey, 'FALSE', self::T_COLLECT_MEMCACHE_KEY_EXPIRE);
            return false;
        }
    }

    /*
      + 获取收藏memcache key
    */
    public function getCollectMemcacheKey($sChildID, $sTableName, $iPriId)
    {
        return self::T_COLLECT_MEMCACHE_KEY . MD5($sTableName . '_' . $iPriId . '_' . $sChildID);
    }

}
