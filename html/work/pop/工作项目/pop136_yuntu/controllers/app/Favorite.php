<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 云图APP -- 收藏列表
 * 继承：APP_Controller
 */

require_once FCPATH . "/core/APP_Controller.php";

class Favorite extends APP_Controller
{
    protected $pageSize = 10;

    public function __construct()
    {
        parent::__construct();
    }

    /*
     * 收藏列表 图案 与 报告 （数据来源:  服装/趋势解读/图案趋势）
     * token
     * https://yuntu.pop136.com/api/favorite/getlist
     */
    public function getList()
    {
        $this->load->model('Favorite_model');

        $userId = $this->check_jwt_token();
        if (!$userId) {
            outPrintApiJson(1020, '用户id不存在，检查token值');
        }

        $page = $this->input->get_post("page");
        $_col = $this->input->get_post("col");// 栏目 pattern--图案  report--服装/趋势解读/图案趋势
        $col = !empty($_col) ? (in_array($_col, array('pattern', 'report')) ? $_col : 'pattern') : 'pattern';

        $page = max($page, 1);
        $offset = ($page - 1) * $this->pageSize;

        list($list, $total) = $this->Favorite_model->getList($offset, $this->pageSize, $userId, $col);
        if ($total <= 0) {
            outPrintApiJson(0, "无收藏数据", array());
        }

        // info
        $info = [];
        $info["total"] = $total;
        $info["pageSize"] = $this->pageSize;

        outPrintApiJson(0, "OK", array_values($list), $info);
    }

    /**
     * 设置收藏--接口
     * token
     * http://yuntu.pop136.com/api/favorite/setcollect
     */
    public function setCollect()
    {
        $this->load->model('Favorite_model');
        $userId = $this->check_jwt_token();
        if (!$userId) {
            outPrintApiJson(1020, '用户id不存在，检查token值');
        }

        // 获取假表名与id
        $popId = $this->input->get_post("popId");
        $params = explode("_", $popId);
        $id = $params[1];
        $table = $params[0];

        // 参数只有： join || cancel
        $handle = $this->input->get_post("handle");

        $data = $this->Favorite_model->setCollectStatus($table, $id, $userId, $handle);
        if (!$data) {
            outPrintApiJson(10032, "失败");
        }
        outPrintApiJson(0, "OK", $data);

    }
}
