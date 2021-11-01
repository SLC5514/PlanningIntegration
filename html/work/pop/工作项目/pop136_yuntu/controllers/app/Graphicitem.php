<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 云图APP -- 首页数据
 * 继承：APP_Controller
 */
require_once FCPATH . "/core/APP_Controller.php";

class Graphicitem extends APP_Controller
{
    public $pageSize = 10;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 搜索筛选项--图案 / 数据来源:  服装/趋势解读/图案趋势
     * https://yuntu.pop136.com/api/graphicitem/search
     */
    public function search()
    {
        $this->load->model('Graphicitem_model');

        $_col = $this->input->get_post("col");// 栏目 pattern--图案  report--服装/趋势解读/图案趋势
        $col = !empty($_col) ? (in_array($_col, array('pattern', 'report')) ? $_col : 'pattern') : 'pattern';

        $reslut = $this->Graphicitem_model->getSearchClassify($col);

        if (!$reslut) {
            outPrintApiJson(10033, "获取筛选项失败");
        }
        outPrintApiJson(0, "OK", $reslut);
    }

    /**
     * 云图app分类筛选项 + icon
     * 图案内容
     * http://yuntu.pop136.com/api/graphicitem/sortsearch
     */
    public function sortSearch()
    {
        $this->load->model('Graphicitem_model');
        $reslut = $this->Graphicitem_model->getPatternContentData();
        if (!$reslut) {
            outPrintApiJson(10037, "获取分类筛选项失败");
        }
        outPrintApiJson(0, "OK", $reslut);
    }

    /**
     * 获取首页列表
     * https://yuntu.pop136.com/api/graphicitem/getlist
     */
    public function getList()
    {
        $this->load->model('Graphicitem_model');

        $page = (int)$this->input->get_post("page");
        // 1-最新发布(时间倒序) 2-最受欢迎（浏览量） 3-以图搜图匹配度最高
        $newStorgreet = (int)$this->input->get_post("newStorgreet");// 单选
        $newStorgreet = $newStorgreet ? $newStorgreet : 1;
        $queryTime = $this->input->get_post("queryTime");// 取16天前的数据(热门数据) 输入 16

        $patternContent = $this->input->get_post("patternContent");// 图案内容（多选）
        $patternTechnology = $this->input->get_post("patternTechnology");// 图案工艺（多选）
        $format = $this->input->get_post("format");// 图案格式（多选）
        $gender = $this->input->get_post("gender");// 性别（多选）
        $application = $this->input->get_post("application");// 局部/满身（多选）

        $patternContent = $this->getParamData($patternContent);
        $patternTechnology = $this->getParamData($patternTechnology);
        $format = $this->getParamData($format);
        $gender = $this->getParamData($gender);
        $application = $this->getParamData($application);

        $page = max($page, 1);
        $offset = ($page - 1) * $this->pageSize;

        $paramsArr = array_filter(compact('newStorgreet', 'patternContent', 'patternTechnology', 'format', 'gender', 'application', 'queryTime'));

        $list = $rows = $info = [];
        $total = $this->Graphicitem_model->getList($list, $paramsArr, $offset, $this->pageSize);

        // 获取总数
        $allTotal = $this->Graphicitem_model->getList($rows, array(), 0, 1);

        $info["total"] = $total;// 条件筛选后的总数
        $info["allTotal"] = $allTotal; // 获取总数
        $info["pageSize"] = $this->pageSize;

        outPrintApiJson(0, "OK", $list, $info);
    }

    /**
     * 获取图案详情
     * http://yuntu.pop136.com/api/graphicitem/detail
     */
    public function detail()
    {
        $this->load->model('Graphicitem_model');

        // 游客也能看,$userId 获取详情状态用
        $userId = $this->check_jwt_token();

        // pop_id
        $pop_id = $this->input->get_post('popId');
        if (!$pop_id) {
            outPrintApiJson(10034, "popId不存在");
        }
        $detail_info = explode("_", $pop_id);
        $table = getProductTableName($detail_info[0]);// 转为真表名
        $id = $detail_info[1];

        if (!$table || !$id) {
            outPrintApiJson(10035, "参数错误");
        }

        $data = $this->Graphicitem_model->getDetail($table, $id, $userId);
        if (!$data) {
            outPrintApiJson(10036, "获取详情错误");
        }

        outPrintApiJson(0, 'ok', $data);
    }

    /**
     * 获取报告栏目的数据
     * https://yuntu.pop136.com/api/graphicitem/getReportList
     */
    public function getReportList()
    {
        $this->load->model('Graphicitem_model');
        $page = (int)$this->input->get_post("page");
        $gender = $this->input->get_post("gender");// 性别（多选）
        $season = $this->input->get_post("season");// 季节（多选）
        $isIndex = $this->input->get_post("isIndex");// 前台传递值判断标识
        // 1-最新发布(时间倒序) 2-最受欢迎（浏览量）
        $newStorgreet = (int)$this->input->get_post("newStorgreet");// 单选
        $newStorgreet = $newStorgreet ? $newStorgreet : 1;

        // 游客也要传递参数
        $userId = $this->check_jwt_token();

        // 首页只获取前5条数据,列表获取10条
        $limit_count = '';
        if ($isIndex == 1) {
            $limit_count = 5;
        }

        $gender = $this->getParamData($gender);
        $season = $this->getParamData($season);

        $page = max($page, 1);
        $offset = ($page - 1) * $this->pageSize;
        $paramsArr = array_filter(compact('newStorgreet', 'gender', 'season'));

        list($lists, $totalCount) = $this->Graphicitem_model->getReportShowList($paramsArr, $offset, $this->pageSize, $userId, $limit_count);
        // 获取总数
        list($rows, $allTotal) = $this->Graphicitem_model->getReportShowList([], 0, 1);

        $info["total"] = $totalCount;// 条件筛选后的总数
        $info["allTotal"] = $allTotal; // 获取总数
        $info["pageSize"] = $this->pageSize;

        outPrintApiJson(0, "OK", $lists, $info);
    }

    /**
     * 获取 服装/趋势解读/图案趋势 详情
     * 数据来源:  服装/趋势解读/图案趋势
     * https://yuntu.pop136.com/api/graphicitem/reportDetail
     */
    public function reportDetail()
    {
        $this->load->model('Graphicitem_model');

        // 游客也能看,$userId 获取详情状态用(游客/普通用户只能看第一篇子报告)
        $userId = $this->check_jwt_token();

        // pop_id  report_6710
        $pop_id = $this->input->get_post('popId');
        if (!$pop_id) {
            outPrintApiJson(10034, "popId不存在");
        }

        $detail_info = explode("_", $pop_id);
        $table = getProductTableName($detail_info[0]);// 转为真表名
        $id = $detail_info[1];

        if (!$table || !$id) {
            outPrintApiJson(10035, "参数错误");
        }

        $result = $this->Graphicitem_model->getReportDetail($table, $id, $userId);
        set_today_incr_views($table, $id);

        //  游客与普通用户可以看报告详情的第一篇子报告
        $this->load->model('Account_model');
        // 获取用户VIP权限判断
        $priInfo = $this->Account_model->getUserPowerDate($userId);
        if ($priInfo) {
            $identity = 'VIP';
        } else {
            if (!empty($userId)) {
                $identity = 'NORMAL';// 普通用户
            } else {
                $identity = 'GUEST';// 游客
            }
        }
        switch ($identity) {
            case 'GUEST':// 游客 GUEST  TOURIST
                if (isset($result['child'])) {
                    unset($result['child']);
                }
                break;
            case 'NORMAL':// 普通用户 NORMAL GENERAL
                $child = array_slice($result['child'], 0, 1);
                $result['child'] = $child;
                break;
        }
        if (!$result) {
            outPrintApiJson(10036, "获取详情错误");
        }

        $info = [];
        $info["user_type"] = $identity;//用户类型
        $info["has_down_power"] = 0; // 下载权限:1-有权限
        if ($identity == 'VIP') {
            // 下载权限:1-有权限
            $info["has_down_power"] = 1;
        }
        outPrintApiJson(0, 'ok', $result, $info);
    }

    /**
     * @ 报告栏目相关推荐
     * 趋势专题>季节>单品>风格
     * 推荐规则：根据报告标签相似度、报告更新时间（最近更新的报告）推荐3篇相关报告（点击后跳转至报告详情页面）
     * 数据来源:  服装/趋势解读/图案趋势
     * https://yuntu.pop136.com/api/graphicitem/reportRecommend
     */
    public function reportRecommend()
    {
        $this->load->model('Graphicitem_model');

        // 游客也能看,$userId 获取详情状态用
        $userId = $this->check_jwt_token();

        // $popId  report_6710
        $popId = $this->input->get_post('popId');
        if (!$popId) {
            outPrintApiJson(10034, "popId不存在");
        }
        $data = $this->Graphicitem_model->getReportRecommend($popId, $userId);
        outPrintApiJson(0, 'ok', $data);
    }


    /********************************************* 私有方法 *****************************************/

    // 列表接收的参数转为数组
    private function getParamData($item = '')
    {
        return isset($item) && !empty($item) ? explode(',', $item) : '';
    }


}