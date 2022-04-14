<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Trends栏目 专用类
 */
class FriendlyLink_model extends POP_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [getTrendLists 获取每页单张展示的数据+总条数]
     * @param  string $params [url参数]
     * @param  integer $columnId [栏目id]
     * @param  integer $offset [偏移量]
     * @param  integer $limit [每页条数]
     * @return [array]   &$lists     [根据条件查询出的结果]
     * @return [array]   $totalCount [根据条件查询出的总条数]
     */
    public function getFriendlyLinkLists()
    {
        $dNowTime = date('Y-m-d H:i:s');
        $sql = 'SELECT * FROM ' . self::T_POP_GENERALIZE_T_FRIENDLY_LINK . ' WHERE iStatus=1 AND iSite=1 AND dStartTime<="' . $dNowTime . '" AND dEndTime>="' . $dNowTime . '" ORDER BY iOrder DESC,dCreateTime DESC';
        return $this->query($sql);
    }


}
