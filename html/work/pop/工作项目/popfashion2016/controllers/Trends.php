<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @todo    趋势-子栏目的处理
 * @author  chenqiuju
 * @time    20160301
 */
class Trends extends POP_Controller
{
    private $columnPid = 1;
    private $pageSize = 40;

    /**
     * @var Common_model
     */
    public $common_model;

    /**
     * @var Trends_model
     */
    public $trends_model;

    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'trends_model']);
        $this->assign('columnPid', $this->columnPid);
        //vip用户即将过期提示条
        $vipTips = $this->isVipExpireTips();
        $this->assign('vipTips', $vipTips);
    }

    // 趋势首页+全部
    public function index($params = '')
    {
        $this->_common($this->columnPid, $params);
    }

    // 趋势-关注
    public function capsules($params = '')
    {
        $this->_common(20, $params);
    }

    // 趋势-企划
    public function design($params = '')
    {
        $this->_common(21, $params);
    }

    // 趋势-主题色彩
    public function color($params = '')
    {
        $this->_common(125, $params);
    }

    // 趋势-图案趋势
    public function pattern($params = '')
    {
        $this->_common(126, $params);
    }

    // 趋势-面辅料趋势
    public function material($params = '')
    {
        $this->_common(127, $params);
    }

    // 趋势-工艺趋势
    public function craft($params = '')
    {
        $this->_common(128, $params);
    }

    // 趋势-廓形趋势
    public function silhouette($params = '')
    {
        $this->_common(129, $params);
    }

    // 条件栏部分
    public function filterConditions()
    {
        // 用户权限
        $columnId = $this->input->post('col');
        $params = $this->input->post('params');
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $paramsArr = $this->common_model->parseParams($params, 1);

        // 只有在选择童装时出现此性别选项
        $gender = $this->common_model->getGenderByRequest($params);
        if (in_array($gender, [3, 4, 5])) {
            $sGender = $this->common_model->genderSpecialSelect($columnId, $params);
        }

        //查询条件
        $selectItem = array('iSeason', 'sManner', 'sAgeLayer');
        $categorys = $this->trends_model->getCategorys($columnId, $params, $powers);
        $this->assign('categorys', $categorys); // 单品
        switch ($columnId) {
            // 快反应
            case '20':
                $selectItem[] = 'iNo';
                $selectItems = $this->trends_model->getSelectItems($selectItem, $columnId, $params, $powers);
                $iNo = $selectItems['iNo'];
                $iNo = array_slice($iNo, 0, 48);
                //主题类型,1=>图案主题,2=>款式主题
                /*$topicTypes = [
                    ['name' => '图案主题', 'link' => $this->common_model->getLink($columnId, $params, 'top', 1, TRUE, 'anchor')],
                    ['name' => '款式主题', 'link' => $this->common_model->getLink($columnId, $params, 'top', 2, TRUE, 'anchor')],
                ];*/
                if (isset($paramsArr['top'])) {
                    $topicTypes = [];
                }

                $this->assign('iNo', $iNo); // 地域风格
                $this->assign('topicTypes', $topicTypes); // 主题类型
                break;
            case '22':// 预测
            case '23':// 前瞻
                $selectItem[] = 'iRelationTheme';
                $selectItems = $this->trends_model->getSelectItems($selectItem, $columnId, $params, $powers);
                $iRelationTheme = $selectItems['iRelationTheme'];
                break;
            // 未来趋势
            case '1':
                // 企划
            case '21':
            case '125':
            case '126':
            case '127':
            case '129':
                $selectItem[] = 'iRelationTheme';
                $selectItems = $this->trends_model->getSelectItems($selectItem, $columnId, $params, $powers);
                $iRelationTheme = $selectItems['iRelationTheme'];
                break;
            case '128':
                $selectItem[] = 'iRelationTheme';
                $selectItem[] = 'iTechnologyType';
                $selectItems = $this->trends_model->getSelectItems($selectItem, $columnId, $params, $powers);
                $iTechnologyType = $selectItems['iTechnologyType'];
                $iRelationTheme = $selectItems['iRelationTheme'];
                $this->assign('iTechnologyType', $iTechnologyType); //工艺类型
                break;
            case 'iSpecialTopicPatterns':// 图案专题
                $selectItem = array('iSeason', 'iRelationTheme');
                $params .= '-stp_1'; // 图案专题条件,stp(iSpecialTopicPatterns)
                // 1 是未来趋势
                $selectItems = $this->trends_model->getSelectItems($selectItem, 1, $params, $powers);
                if (isset($selectItems['iSeason']) && !empty($selectItems['iSeason'])) {
                    foreach ($selectItems['iSeason'] as $k => $v) {
                        $selectItems['iSeason'][$k]['link'] = str_replace('/trends/', '/patterns/specialtopicpatterns/', $selectItems['iSeason'][$k]['link']);
                    }
                }
                // 替换url链接
                if (isset($selectItems['iRelationTheme']) && !empty($selectItems['iRelationTheme'])) {
                    $iRelationTheme = $selectItems['iRelationTheme'];
                    foreach ($iRelationTheme as $k => $v) {
                        $iRelationTheme[$k]['link'] = str_replace('/trends/', '/patterns/specialtopicpatterns/', $v['link']);
                        if (!empty($iRelationTheme[$k]['attrs'])) {
                            foreach ($iRelationTheme[$k]['attrs'] as $k1 => $v1) {
                                $iRelationTheme[$k]['attrs'][$k1]['link'] = str_replace('/trends/', '/patterns/specialtopicpatterns/', $v1['link']);
                            }
                        }
                    }
                }
                break;
            default:
                $selectItems = $this->trends_model->getSelectItems($selectItem, $columnId, $params, $powers);
                break;
        }
        if (!empty($iRelationTheme)) {
            $this->assign('iRelationTheme', $iRelationTheme); // 趋势专题
        }
        // 季节
        $iSeason = $selectItems['iSeason'];

        // 视角
        if ($selectItems['sVisualAngle']) {
            //排除 品牌、其他两个视角分类
            foreach ($selectItems['sVisualAngle'] as $_vaK => $_vaVal) {
                if (in_array($_vaVal['id'], array(11620, 20))) {
                    unset($selectItems['sVisualAngle'][$_vaK]);
                }
            }
        }
        $sVisualAngle = array_values($selectItems['sVisualAngle']);

        // 年龄层
        if ($columnId == 21) {
            // 21--企划/组货,特殊处理
            $sAgeLayer = !empty($selectItems['sAgeLayer']) ? $selectItems['sAgeLayer'] : array();
        } else {
            if ($gender == 5 || (!empty($paramsArr['gcen']) && in_array($paramsArr['gcen'], array(3, 4)))) {
                $sAgeLayer = !empty($selectItems['sAgeLayer']) ? $selectItems['sAgeLayer'] : array();
            }
        }

        $this->assign('columnId', $columnId);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('sGender', $sGender);// 男童、女童 // 单品
        $this->assign('iSeason', $iSeason); // 季节 // 季节
        $this->assign('sVisualAngle', $sVisualAngle); // 视角
        $this->assign('sManner', $selectItems['sManner']);
        $this->assign('sAgeLayer', $sAgeLayer ? $sAgeLayer : []);// 年龄层
        echo $this->fetch('lists/trends_select_items.html');
    }

    /**
     * 各个子栏目方法体基本一致
     * @param $columnId
     * @param $params
     */
    private function _common($columnId, $params)
    {
        // 头部广告
        // $topAds = $this->common_model->getAds($this->columnPid, 1);
        $powers = memberPower('other');//传other时才能获取用户真正类型
        $Real_UserType = $powers["P_UserType"];
        $this->assign('Real_UserType', $Real_UserType);
        // $this->assign('topAds', $topAds);

        //注意参数$params需要做安全处理,cat=2&sea=3&gen=1&ind=2
        $params = $this->common_model->restoreParams($params);
        $paramsArr = $this->common_model->parseParams($params, 1);

        if ($columnId != 21) {
            // 年龄层 -- 获取您已选择内容
            $gender_str = $this->common_model->getGenderByRequest($params);
            if ($gender_str != 5 || (!empty($paramsArr['gcen']) && !in_array($paramsArr['gcen'], array(3, 4)))) {
                if (isset($paramsArr['age'])) {
                    unset($paramsArr['age']);
                }
            }
            $params = $this->common_model->parseParams($paramsArr, 2, false);
        }

        //判断用户身份
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
        // pop发现
        // $leftAds = $this->common_model->getAds($columnId, 18, 5, $this->columnPid);
        // 热门推荐关键字
        $keys = $this->common_model->getHotKeyWords($columnId, $params);

        //获取列表和分页
        $this->getLists($params, $columnId, $powers, $rootUrl);
        // 获取您已选择内容
        $tips = $this->common_model->getTips($columnId, $params);
        // 您已选择中排除视角
        if (isset($tips['vis'])) {
            unset($tips['vis']);
        }
        //获取时间范围
        $time_text = $this->common_model->getTimeText($params);
        $this->assign('time_text', $time_text);
        //seo搜索引擎，标题、关键字、描述
        $this->getTDK($columnId, $params);
        //栏目介绍
        $presentation = $this->trends_model->getColumnsPresentation($columnId);
        $this->assign('presentation', $presentation);

        $this->assign('columnId', $columnId);
        $this->assign('tips', $tips);
        $this->assign('clearAll', $clearAll);
        $this->assign('columns', $columns);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('userType', $userType);
        // $this->assign('leftAds', $leftAds);
        $this->assign('keys', $keys);
        $this->assign('rootUrl', $rootUrl);
        $this->assign('link', $link);

        $this->display('lists/trends_list.html');
    }

    /**
     * 获取列表数据和分页
     */
    private function getLists($params, $columnId, $powers, $rootUrl)
    {
        $paramsArr = $this->common_model->parseParams($params, 1);
        $page = $this->common_model->getPage($paramsArr); //当前页

        $lists = $try_list = [];

        // 获取列表
        $offset = ($page - 1) * ($this->pageSize);// 偏移量
        $totalCount = $this->trends_model->getTrendLists($params, $columnId, $lists, $offset, $this->pageSize, $powers);
        //生成页码
        $pageHtml = $this->makePageHtml($paramsArr, $totalCount, $this->pageSize, $page, $rootUrl);
        //生成简单页码
        $simplePageHtml = $this->makePageHtml($paramsArr, $totalCount, $this->pageSize, $page, $rootUrl, true);

        // 免费试读
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

            $this->trends_model->getTrendLists("", $columnId, $try_list, 0, 5, [], $condition);
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
        $seoArray = $this->trends_model->getSeoArray($columnId, $params);
        $title = $seoArray['title'];
        $keywords = $seoArray['keywords'];
        $description = $seoArray['description'];

        $this->assign('title', $title);
        $this->assign('keywords', $keywords);
        $this->assign('description', $description);
    }
}
