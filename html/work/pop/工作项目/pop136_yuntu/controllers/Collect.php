<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @todo 收藏
 */
class Collect extends POP_Controller
{
    const COL = 3;
    private $pageSize = 30;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Collect_model');
        $this->get_use_type(self::COL);
        $this->checkPower();
    }

    /**
     * 我的收藏列表--页面
     * coll
     */
    public function getList()
    {
        $params = $this->input->get_post("params");
        $paramsArr = decodeParams($params);
        // $page = empty($paramsArr["page"]) ? 1 : intval($paramsArr["page"]);
        // $offset = ($page - 1) * $this->pageSize;

        $page = $this->input->get_post("page");

        // 区分图案与趋势收藏列表的
        $_change = $this->input->get_post("change");
        $change = !empty($_change) && in_array($_change, ['pattern', 'trends']) ? $_change : 'pattern';

        $page = max($page, 1);
        $offset = ($page - 1) * $this->pageSize;

        if ($this->input->is_ajax_request()) {
            $result = $info = [];

            list($list, $total) = $this->Collect_model->getList($offset, $this->pageSize,'fashion', $change);
            if (intval($total) <= 0) {
                outPrintApiJson(1001, "无收藏数据");
            }
            // 返回数据--data
            $result["list"] = array_values($list);
            $result["pageHtml"] = $this->makePageHtml($paramsArr, $total, $this->pageSize, $page, "/collect/");

            // 返回数据--info
            $info["total"] = $total;
            $info["pagesize"] = $this->pageSize;

            outPrintApiJson(0, "OK", $result, $info);
        }

        $this->display('collect/collect-list.html');
    }

    /**
     * 设置收藏--接口
     */
    public function setCollect()
    {
        if (!$this->input->is_ajax_request() || !getUserId()) {
            header("Location:/home/");
        }

        header('Content-Type:text/json;charset=utf-8');

        $id = $this->input->get_post("id");
        $table = $this->input->get_post("t");
        $handle = $this->input->get_post("handle");// 参数： join || cancel

        if (!$id || !$table) {
            outPrintApiJson(1002, "参数缺失");
        }

        $data = $this->Collect_model->setCollectStatus($table, $id, $handle);
        if (!$data) {
            outPrintApiJson(1003, "取消/加入收藏失败");
        }

        outPrintApiJson(0, "OK", $data);

    }
}