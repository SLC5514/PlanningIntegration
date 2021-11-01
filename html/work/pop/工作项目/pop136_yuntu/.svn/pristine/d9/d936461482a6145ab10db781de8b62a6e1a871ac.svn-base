<?php

class Mihui_model extends POP_Model
{
    const POP_YUNTU_T_MIHUI_OPENID = '`yuntu`.`t_mihui_openid`';
    const POP_YUNTU_T_MIHUI_DESIGN_GRAPHIC = '`yuntu`.`t_mihui_design_graphic`';//设计花型表

    public function __construct()
    {
        parent::__construct();
    }

    /**根据用户ID获取openID
     * @param $userId
     * @return mixed
     */
    public function getOpenIdByUserId($userId)
    {
        $sql = "select sOpenid from  " . self::POP_YUNTU_T_MIHUI_OPENID . " where sUserId=? limit 1";
        $result = $this->query($sql, [$userId]);
        return $result[0]['sOpenid'];
    }

    /**根据openID获取设计花型列表
     * @param $openId
     */
    public function getMihuiLists($openid, $offset, $pageSize)
    {
        if (!$openid) {
            return array();
        }
        $cond = [
            'sOpenId' => $openid,
            'status' => 0,
        ];
        $list = $total = [];
        $this->db->select('*')->from(self::POP_YUNTU_T_MIHUI_DESIGN_GRAPHIC)->where($cond);
        $total = $this->db->count_all_results('', false);
        $query = $this->db->order_by('dCreateTime desc')->limit($pageSize, $offset)->get();
        $list = $query->result_array();

        if (!empty($list)) {
            foreach ($list as $key => $val) {
                $list[$key]['cover'] = $val['sSmallPath'];
                $list[$key]['t'] = getProductTableName('t_mihui_design_graphic');
                $list[$key]['url'] = '/mihui/mihuiDesignList/';
            }
        }
        return array($list, $total);
    }

    public function getDetailById($id, $table)
    {
        $data = [];
        $sql = "select * from " . self::POP_YUNTU_T_MIHUI_DESIGN_GRAPHIC . " where id=? and status=? limit 1";
        $rs = $this->query($sql, [$id, 0]);
        if (!$rs) {
            return $data;
        }
        $data['detail_img'] = [
            array(
                'smallPath' => $rs[0]['sSmallPath'],
                'bigPath' => $rs[0]['sBigPath'],
                'mbigPath' => $rs[0]['sSmallPath'],
                'simpleName' => getProductTableName($table),
                'id' => $rs[0]['id'],
                'vectorInfo' => $rs[0]["vectorInfo"],
                'sThirdPicId' => $rs[0]["sThirdPicId"]
            ),

        ];
        $data['dCreateTime'] = $rs[0]['dCreateTime'];
        return $data;
    }

    public function is_has_psd($sThirdPicId)
    {
        $sql = "select vectorInfo from  " . self::POP_YUNTU_T_MIHUI_DESIGN_GRAPHIC . " where sThirdPicId=? limit 1";
        $result = $this->query($sql, [$sThirdPicId]);
        $vectorInfo = !empty($result[0]['vectorInfo']) ? json_decode($result[0]['vectorInfo'], true) : array();
        if ($vectorInfo["url"]) {
            return true;
        } else {
            return false;
        }
    }

}