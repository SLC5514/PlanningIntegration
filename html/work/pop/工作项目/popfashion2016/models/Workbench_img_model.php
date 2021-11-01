<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Workbench_img_model
 * @property-read common_model $common_model
 */
class Workbench_img_model extends POP_Model
{
    const TABLENAME = 'fm_workbench_img';

    public function __construct()
    {
        parent::__construct();
    }

    // 根据表名、id、工作台来获取已收藏的图片数量 0=>未收藏
    public function getImageNum($tablename, $id, $wbid, $spath, $sAccountId = '')
    {
        if (empty($tablename) || empty($id) || empty($wbid)) return 0;
        if (empty($sAccountId)) {
            $sAccountId = getUserId();
        }
        $tablename = getProductTableName($tablename);
        $sql = 'SELECT COUNT(*) cnt FROM ' . self::TABLENAME . ' WHERE iWorkbenchId=? AND sAccountId=? AND sTableName=? AND iPriId=? AND sSmallPath=? AND iStatus=0';
        $res = $this->query($sql, [$wbid, $sAccountId, $tablename, $id, $spath]);
        // var_dump($this->db()->last_query());_die;
        return intval($res[0]['cnt']);
    }

    // 获取工作台收藏的图片总数
    public function getImageNumByWBid($wbid, $sAccountId = '')
    {
        if (empty($wbid)) return 0;
        if (empty($sAccountId)) {
            $sAccountId = getUserId();
        }
        $sql = 'SELECT COUNT(*) cnt FROM ' . self::TABLENAME . ' WHERE iWorkbenchId=? AND sAccountId=? AND iStatus=0';
        $res = $this->query($sql, [$wbid, $sAccountId]);
        return intval($res[0]['cnt']);
    }

    // 保存收藏图片
    public function saveImage($wkb, $t, $id, $col, $sp = '', $bp = '', $desc = '', $sAccountId = '')
    {
        if (empty($wkb) || empty($t) || empty($id) || empty($col)) {
            return 0;
        }
        if (empty($sAccountId)) {
            $sAccountId = getUserId();
        }
        $sql = 'INSERT INTO ' . self::TABLENAME . ' SET iWorkbenchId=?, sAccountId=?, sTableName=?, iPriId=?, sImageDesc=?, iStatus=0, dCreateTime=?, iColumnId=?, sSmallPath=?, sBigPath=?';
        $t = getProductTableName($t);
        $this->db()->query($sql, [intval($wkb), $sAccountId, $t, intval($id), $desc, date('Y-m-d H:i:s'), $col, $sp, $bp]);
        $insertId = $this->db()->insert_id();
        // $this->db()->close();
        return intval($insertId);
    }
}

