<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 趋势解读/图案趋势控制器
 * 取【服装站】-【趋势解读】-【图案趋势】下，2019年5月份以后打过热门标签的报告
 * Created by PhpStorm.
 * User: gcl
 * Date: 2020/06/16 11:04
 */
class TrendsPattern extends POP_Controller
{
    public $pageSize = 40;

    const COL = 3;

    function __construct()
    {
        parent::__construct();

        $this->load->model(["TrendsPattern_model", "Collect_model"]);
        $this->assign("col", self::COL);
        // 趋势解读/图案趋势 权限归于 原来的图案库
        $this->get_use_type(self::COL);
        $this->checkPower();
    }

    // 首页
    public function index($params = "")
    {
        $paramsArr = decodeParams($params);
        $selected = $this->TrendsPattern_model->getSelected($paramsArr, true);
        $this->setTDK("patternlibrary", "index", $selected);
        $this->assign("params", $params);
        $this->assign("key", rawurlencode(rawurlencode(getKeyWord())));
        $this->display("report/trends_list.html");
    }

    // 列表数据
    public function getList()
    {
        $params = $this->input->get_post("params");
        $paramsArr = decodeParams($params);

        // 图案库显示5篇
        $isIndex = $this->input->get_post("is_index");
        if (!empty($isIndex)) {
            list($lists, $totalCount) = $this->TrendsPattern_model->getList();
            outPrintApiJson(0, "OK", $lists);
        }

        $result = [];
        $result["selected"] = $this->TrendsPattern_model->getSelected($paramsArr);

        $page = max(intval($paramsArr["page"]), 1);
        $offset = ($page - 1) * $this->pageSize;
        list($lists, $total) = $this->TrendsPattern_model->getList($paramsArr, $offset, $this->pageSize);
        $result["list"] = $lists;

        $info = [];
        $info["total"] = $total;
        $info["pagesize"] = $this->pageSize;
        //取分页
        $result["pageHtml"] = $this->makePageHtml($paramsArr, $total, $this->pageSize, $page, "/trendspattern/");
        outPrintApiJson(0, "OK", $result, $info);
    }

    // 筛选
    public function filterconditions()
    {
        $params = $this->input->get_post("params");
        $paramsArr = decodeParams($params);
        $fields = ["sGender", "iSeason"];

        //过滤已选中
        foreach ($fields as $key => $field) {
            $temp = $this->TrendsPattern_model->solrCondArr[$field];
            if (isset($paramsArr[$temp])) {
                unset($fields[$key]);
            }
        }
        $reslut = $this->TrendsPattern_model->getSelectItems($fields, $paramsArr);
        outPrintApiJson(0, "OK", $reslut);
    }

    // 详情数据
    public function detail()
    {
        $id = $this->input->get_post("id");
        $table = $tableName = $this->input->get_post("t");
        $col = $tableName = $this->input->get_post("col");
        $tableName = getProductTableName($table);

        // 获取报告的相关详情信息，无报告目录
        $res = $this->TrendsPattern_model->getReportDetailsData($id, $col);
        $this->checkPower(self::COL);

        if (!empty($res)) {
            // 取详情标签
            $aDetailLabels = $this->TrendsPattern_model->getLabelsInfo($col, $tableName, $id);
            $res['aDetailLabels'] = $aDetailLabels;
            $res['collect_status'] = $this->Collect_model->getCollectStatus($table, $id, 'fashion', true);
            $res['pdfPrefix'] = strpos($res['pdfPath'], 'http:') === false ? STATIC_URL1 : '';
        }

        // flash报告页
        $flashPages = [];
        if (!empty($res['subTopic'])) {
            // 按权重倒序
            usort($res['subTopic'], function ($a, $b) {
                if ($a['iSort'] == $b['iSort']) {
                    return $a['iSubTopicId'] > $b['iSubTopicId'];
                }
                return $a['iSort'] < $b['iSort'];
            });
            foreach ($res['subTopic'] as $k => $subTopic) {
                foreach ($subTopic['subPage'] as $key => $subPage) {
                    if ($subPage['pageType'] == 4) {
                        $flashPages[$subPage['iPageId']] = $subPage;
                    }
                    // 替换video路径
                    if ($subPage['content']) {
                        $res['subTopic'][$k]['subPage'][$key]['content'] = $this->TrendsPattern_model->getVedioPath($subPage['content']);
                    }
                }
            }
        }

        if (!empty($res)) {
            $rename = htmlspecialchars_decode($res['title']);
            $rename = preg_replace("/[^\x{4e00}-\x{9fa5}a-z0-9\_]/iu", "", $rename);
            $res['rename'] = $rename;
            $res['flashPages'] = $flashPages;
        }

        if ($this->input->is_ajax_request()) {
            if (!empty($res)) {
                outPrintApiJson(0, 'ok', $res);
            } else {
                outPrintApiJson(110, '当前数据不存在');
            }
        } else {
            $this->display("report/trends_detail.html");
        }
    }
}