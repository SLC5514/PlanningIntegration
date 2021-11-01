<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @todo    潮流解析-子栏目的处理
 * @author  chenqiuju
 * @time    20160301
 */
class Analysis extends POP_Controller
{
    private $columnPid = 2;
    private $pageSize = 40;

    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'analysis_model']);

        $this->assign('columnPid', $this->columnPid);
        //vip用户即将过期提示条
        $vipTips = $this->isVipExpireTips();
        $this->assign('vipTips', $vipTips);
    }

    // 潮流解析首页+全部
    public function index($params = '')
    {
        $this->_common($this->columnPid, $params);
    }

    // 潮流解析-T分析
    public function runways($params = '')
    {
        $this->_common(30, $params);
    }

    // 潮流解析-展会分析
    public function fairs($params = '')
    {
        $this->_common(31, $params);
    }

    // 潮流解析-订货会分析
    public function shows($params = '')
    {
        $this->_common(32, $params);
    }

    // 潮流解析-批发市场分析
    public function market($params = '')
    {
        $this->_common(33, $params);
    }

    // 潮流解析-线上（电商）
    public function online($params = '')
    {
        $this->_common(34, $params);
    }

    // 潮流解析-线下（实体）
    public function retail($params = '')
    {
        $this->_common(35, $params);
    }

    // 潮流解析-店铺形象
    public function visual($params = '')
    {
        $this->_common(36, $params);
    }

    // 潮流解析-行业报道
    public function reports($params = '')
    {
        $this->_common(40, $params);
    }

    // 潮流解析-街拍分析
    public function street($params = '')
    {
        $this->_common(37, $params);
    }

    // 潮流解析-潮流领袖
    public function trendsetters($params = '')
    {
        $this->_common(38, $params);
    }

    // 权威数据
    public function authorized($params = '')
    {
        $this->_common(112, $params);
    }

    // 爆款数据,新增, 2019-09-03
    public function tops($params = '')
    {
        $this->_common(132, $params);
    }

    // 条件栏部分
    public function filterConditions()
    {
        $columnId = $this->input->post('col');
        $params = $this->input->post('params');
        $paramsArr = $this->common_model->parseParams($params, 1);
        // 用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);

        // 只有在选择童装时出现此性别选项
        $gender = $this->common_model->getGenderByRequest($params);
        if (in_array($gender, [3, 4, 5])) {
            $sGender = $this->common_model->genderSpecialSelect($columnId, $params);
        }
        // 单品
        $categorys = $this->analysis_model->getCategorys($columnId, $params, $powers);
        // 品牌
        $brands = $this->common_model->getBrands($columnId);

        // 查询条件
        $selectItem = ['iSeason', 'sManner', 'sAgeLayer']; // 年龄层sAgeLayer
        $region = false;
        switch ($columnId) {
            //T台分析
            case '30':
                $selectItem[] = 'sFashionWeek';
                $selectItem[] = 'sVisualAngle';
                $selectItems = $this->analysis_model->getSelectItems($selectItem, $columnId, $params, $powers);
                $sFashionWeek = $selectItems['sFashionWeek'];// 时装周专题
                $sVisualAngle = $selectItems['sVisualAngle'];// 视角
                $this->assign('sFashionWeek', $sFashionWeek);
                $this->assign('sVisualAngle', $sVisualAngle);
                break;
            // 展会分析
            case '31':
                $selectItem[] = 'sVisualAngle';
                $selectItem[] = 'sExhibitionName';
                $selectItems = $this->analysis_model->getSelectItems($selectItem, $columnId, $params, $powers);
                $sExhibitionName = $selectItems['sExhibitionName'];// 展会名称
                $this->assign('sExhibitionName', $sExhibitionName);
                break;
            // 订货会分析
            case '32':
                $selectItem[] = 'sVisualAngle';
                $selectItems = $this->analysis_model->getSelectItems($selectItem, $columnId, $params, $powers);
                $sVisualAngle = $selectItems['sVisualAngle'];// 视角
                $this->assign('sVisualAngle', $sVisualAngle);
                break;
            // 批发市场分析
            case '33':
            case '132': // 爆款数据
                $region = true; // 地区
            // 品牌在线分析
            case '34':
                // 零售市场分析
            case '35':
                $selectItems = $this->analysis_model->getSelectItems($selectItem, $columnId, $params, $powers);
                break;
            // 店铺形象
            case '36':
                $selectItem[] = 'sDisplayType';
                $selectItem[] = 'sMarketHotPosition';
                $selectItems = $this->analysis_model->getSelectItems($selectItem, $columnId, $params, $powers);
                $sDisplayType = $selectItems['sDisplayType'];// 陈列类型
                $sMarketHotPosition = $selectItems['sMarketHotPosition'];// 品牌定位
                $this->assign('sDisplayType', $sDisplayType);
                $this->assign('sMarketHotPosition', $sMarketHotPosition);
                break;
            // 行业报道
            case '40':
                $selectItem[] = 'sReportType';
                $selectItems = $this->analysis_model->getSelectItems($selectItem, $columnId, $params, $powers);
                $iSeason = $selectItems['iSeason']; //季节
                $sReportType = $selectItems['sReportType']; //报道类型
                $this->assign('iSeason', $iSeason);
                $this->assign('sReportType', $sReportType);
                break;
            // 街拍分析
            case '37':
                $region = true; // 地区
            // 潮流领袖
            case '38':
                // 权威数据
            case '112':
            default:
                $selectItems = $this->analysis_model->getSelectItems($selectItem, $columnId, $params, $powers);
                break;
        }
        $iSeason = $selectItems['iSeason']; //季节
        $this->assign('iSeason', $iSeason);

        // 年龄层
        $sAgeLayer = array();
        if ($gender == 5 || (!empty($paramsArr['gcen']) && in_array($paramsArr['gcen'], array(3, 4)))) {
            $sAgeLayer = !empty($selectItems['sAgeLayer']) ? $selectItems['sAgeLayer'] : array();
        }

        $this->assign('columnId', $columnId);
        $this->assign('paramsArr', $paramsArr);// 参数数组
        $this->assign('bpos', $paramsArr['bpos'] ? $paramsArr['bpos'] : '');// 品牌定位
        $this->assign('sGender', $sGender);// 男童、女童
        $this->assign('categorys', $categorys); // 单品
        $this->assign('brands', $brands); // 品牌
        $this->assign('region', $region); // 地区
        $this->assign('sManner', $selectItems['sManner']);
        $this->assign('sAgeLayer', $sAgeLayer);// 年龄层
        echo $this->fetch('lists/analysis_select_items.html');
    }

    /**
     * 获取列表数据和分页
     *
     * @param $params
     * @param $columnId
     * @param $powers
     * @param $rootUrl
     */
    private function getLists($params, $columnId, $powers, $rootUrl)
    {
        $paramsArr = $this->common_model->parseParams($params, 1);
        $page = $this->common_model->getPage($paramsArr); //当前页

        $lists = $try_list = [];
        // 获取列表
        $offset = ($page - 1) * ($this->pageSize);// 偏移量
        $totalCount = $this->analysis_model->getAnalysisLists($params, $columnId, $lists, $offset, $this->pageSize, $powers);

        //生成页码
        $pageHtml = $this->makePageHtml($paramsArr, $totalCount, $this->pageSize, $page, $rootUrl);
        //生成简单页码
        $simplePageHtml = $this->makePageHtml($paramsArr, $totalCount, $this->pageSize, $page, $rootUrl, true);

        //免费试读
        if (in_array($powers['P_UserType'], [4, 5])) {
            // 性别
            $gender = $this->common_model->getGenderByRequest($params);
            // 行业
            $industry = $this->common_model->getIndustryByRequest($params);
            $gender && $condition['other'][] = "aLabelIds:{$gender}";
            $industry && $condition['other'][] = "aLabelIds:{$industry}";
            $condition['iColumnId'] = $columnId;
            $condition['iHot'] = 1;
            $condition["other"][] = 'dCreateTime:[' . date('Y-m-d\TH:i:s\Z', strtotime('-19 month')) . ' TO ' . date('Y-m-d\TH:i:s\Z', strtotime('-18 month')) . '}';

            $this->analysis_model->getAnalysisLists("", $columnId, $try_list, 0, 5, [], $condition);
            $this->assign('try_list', $try_list);
        }
        $this->load->model('Report_model');
        $existReportBookIds = $this->Report_model->getExistReportBookIds($lists);
        $this->assign('existReportBookIds', $existReportBookIds);
        $this->assign('lists', $lists);
        $this->assign('totalCount', $totalCount);
        $this->assign('pageHtml', $pageHtml);
        $this->assign('simplePageHtml', $simplePageHtml);
        $this->assign('powers', $powers);
    }

    /**
     * 获取seo搜索引擎，标题、关键字、描述
     * @param $columnId 栏目ID
     * @param $params 参数字符串
     */
    private function getTDK($columnId, $params)
    {
        $seoArray = $this->analysis_model->getSeoArray($columnId, $params);

        $this->assign('title', $seoArray['title']);
        $this->assign('keywords', $seoArray['keywords']);
        $this->assign('description', $seoArray['description']);
    }

    private function init()
    {
        $powers = memberPower('other');//传other时才能获取用户真正类型
        $Real_UserType = $powers["P_UserType"];
        $this->assign('Real_UserType', $Real_UserType);
        // 头部广告
        $topAds = $this->common_model->getAds($this->columnPid, 1);
        $this->assign('topAds', $topAds);

        //获取js时间戳
        $regionPath = FCPATH . 'global/js/fashion/' . 'regionFromSolr.js';
        $regionStaticTime = getFileModifyTime($regionPath, 'region');    //地区时间戳
        $brandPath = FCPATH . 'global/js/fashion/' . 'Column4_Brand.js';
        $brandStaticTime = getFileModifyTime($brandPath, 'brand');    //品牌时间戳
        $this->assign('regionStaticTime', $regionStaticTime);
        $this->assign('brandStaticTime', $brandStaticTime);
    }

    /**
     * 各个子栏目方法体基本一致
     * @param $iColumnId
     * @param $params
     */
    private function _common($iColumnId, $params)
    {
        $this->benchmark->mark('common');
        $this->init();

        $columnId = $iColumnId;
        //注意参数$params需要做安全处理,cat=2&sea=3&gen=1&ind=2
        $params = $this->common_model->restoreParams($params);
        $paramsArr = $this->common_model->parseParams($params, 1);

        // 年龄层 -- 获取您已选择内容
        $gender_str = $this->common_model->getGenderByRequest($params);
        if ($gender_str != 5 || (!empty($paramsArr['gcen']) && !in_array($paramsArr['gcen'], array(3, 4)))) {
            if (!empty($paramsArr['age']) && isset($paramsArr['age'])) {
                unset($paramsArr['age']);
            }
        }
        $params = $this->common_model->parseParams($paramsArr, 2, false);

        //判断用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $userType = $powers['userType'];
        $sortRandom = $powers['sort'];
        $rootUrl = $this->common_model->columnRootPath($columnId);
        $search = $powers['P_Search'] ? $this->common_model->getSearchSufix() : '';
        $link = ['url' => $rootUrl, 'param' => $params, 'sortRandom' => $sortRandom, 'search' => $search];
        // 子栏目
        $columns = $this->common_model->getColumns($this->columnPid, $params);

        // 清除全部
        $clearAll = $this->common_model->getClearLink($columnId, $params);
        // 左侧广告
        //$leftAds = $this->common_model->getAds($columnId, 2);
        // pop发现
        $leftAds = $this->common_model->getAds($columnId, 18, 5, $this->columnPid);
        // 热门推荐关键字
        $keys = $this->common_model->getHotKeyWords($columnId, $params);
        // 获取列表和分页
        $this->getLists($params, $columnId, $powers, $rootUrl);
        // 获取您已选择内容
        $tips = $this->common_model->getTips($columnId, $params);
        // 获取时间范围
        $time_text = $this->common_model->getTimeText($params);
        //seo搜索引擎，标题、关键字、描述
        $this->getTDK($columnId, $params);
        //栏目介绍
        $presentation = $this->analysis_model->getColumnsPresentation($columnId);
        $this->assign('presentation', $presentation);

        $this->assign('time_text', $time_text);
        $this->assign('columnId', $columnId);
        $this->assign('tips', $tips);
        $this->assign('clearAll', $clearAll);
        $this->assign('columns', $columns);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('userType', $userType);
        $this->assign('leftAds', $leftAds);
        $this->assign('keys', $keys);
        $this->assign('rootUrl', $rootUrl);
        $this->assign('link', $link);
        $this->benchmark->mark('commonEnd');
        $this->display('lists/analysis_list.html');
    }

}

