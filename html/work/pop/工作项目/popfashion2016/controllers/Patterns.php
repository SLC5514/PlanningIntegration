<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @todo 图案库-子栏目处理
 * @author: dushuixian
 * Date: 2017/5/5
 * Time: 10:52
 */
class Patterns extends POP_Controller
{
    private $columnPid = 9;
    private $pageSize = 60;
    // 图案专题id
    const SPECIAL_TOPIC_PATTERNS = 'iSpecialTopicPatterns';

    public function __construct()
    {
        parent::__construct();

        $this->load->model(['common_model', 'Patterns_model']);
        // 头部广告
        $topAds = $this->common_model->getAds($this->columnPid, 1);
        $this->assign('topAds', $topAds);

        $power = memberPower('other');
        $memberFlag = 0;
        if ($power['P_UserType'] == 5) {//游客
            $memberFlag = 1;
        }
        if ($power['P_UserType'] == 4) {//普通
            $memberFlag = 2;
        }
        $this->assign('memberFlag', $memberFlag);
        $this->assign('columnPid', $this->columnPid);
        //vip用户即将过期提示条
        $vipTips = $this->isVipExpireTips();
        $this->assign('vipTips', $vipTips);
    }

    public function index($params = '')
    {
        $params = $params ? $params : $this->uri->segment(2);
        $this->graphics($params);
    }

    //调用详情接口时加解密算法
    private function secret_params()
    {
        //AES_KEY
        $this->load->model('Secret_model');
        list($symmetricKey, $timeStamp) = $this->Secret_model->init_data();
        $token = $this->Secret_model->get_token();

        $this->assign('token', $token);//token(uid_popuid_secret)
        $this->assign('sign', $symmetricKey);//AES_KEY
        $this->assign('timeStamp', $timeStamp);//生成AES_KEY的时间戳

    }

    // 图案库--图案素材
    public function graphics($params = '')
    {
        $this->benchmark->mark('action');
        $columnId = 82;
        //注意参数$params需要做安全处理,cat=2&sea=3&gen=1&ind=2
        $params = $this->common_model->restoreParams($params);

        //判断用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $userType = $powers['userType'];
        $sortRandom = $powers['sort'];
        $rootUrl = $this->common_model->columnRootPath($columnId);
        $search = $powers['P_Search'] ? $this->common_model->getSearchSufix() : '';
        $link = ['url' => $rootUrl, 'param' => $params, 'sortRandom' => $sortRandom, 'search' => $search];

        $paramsArr = $this->common_model->parseParams($params, 1);
        // 子栏目
        $columns = $this->common_model->getColumns($this->columnPid, $params);
        // 清除全部
        $clearAll = $this->common_model->getClearLink($columnId, $params);
        // 左侧广告
        $leftAds = $this->common_model->getAds($columnId, 2);
        // 热门推荐关键字
        $keys = $this->common_model->getHotKeyWords($columnId, $params);

        // 获取列表和分页 通过ajax获取列表
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $this->Patterns_model->ajaxList($params, $columnId, $powers);
            return;
        }
        // 判断是否要显示水印或遮罩
        isShowWatermark($paramsArr);

        // 获取您已选择内容
        $tips = $this->common_model->getTips($columnId, $params);

        //获取时间范围
        $time_text = $this->common_model->getTimeText($params);
        $this->assign('time_text', $time_text);

        //seo搜索引擎，标题、关键字、描述
        $this->getTDK($columnId, $params);

        //栏目介绍
        $presentation = $this->Patterns_model->getColumnsPresentation($columnId);
        $this->assign('presentation', $presentation);

        // 底部广告
        $bottomAds = $this->common_model->getAdsBottom($columnId, $this->columnPid);
        $this->assign('bottomAds', $bottomAds);
        $this->secret_params();
        $this->assign('columnId', $columnId);
        $this->assign('rootUrl', $rootUrl);
        $this->assign('link', $link);
        $this->assign('tips', $tips);
        $this->assign('clearAll', $clearAll);
        $this->assign('columns', $columns);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('userType', $userType);
        $this->assign('leftAds', $leftAds);
        $this->assign('keys', $keys);
        $this->benchmark->mark('actionEnd');
        $this->display('lists/patterns_list.html');
    }

    //达观智能推荐列表---款式、图案素材栏目
    public function patternRecommend()
    {
       /* $ip = $this->input->ip_address();
        $allow_ip = config_item('datagrand_allow_ip');
        if(!in_array($ip,$allow_ip)){
            echo 'Cannot Access!';die;
        }*/
        $this->load->model(['datagrand_model']);
        $col = $this->input->get('col',true);
        $page_size = $this->input->get('page_size',true);
        $paramsArr = $this->input->post(null,true) ? $this->input->post(null,true) : array();
        $paramsArr['col'] = empty($paramsArr['col']) ? $col : $paramsArr['col'];
        
        $paramsArr['page_size'] = !empty($page_size) ? $page_size : '';

        // 栏目信息
        $columns = $this->datagrand_model->getColumns();
        //判断用户权限 账号类型  1 VIP  4普通 5游客
        $powers =  $this->datagrand_model->getRecommendPower();

        // 获取列表和分页 通过ajax获取列表
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $this->datagrand_model->recommendList($paramsArr);
            return;
        }

        //tdk
        $title = "猜你喜欢-服装设计花型图案素材、服装设计款式图片-POP服装趋势网";
        $keywords = "服装设计图案素材,服装设计图,服装设计图片素材,服装设计款式图,服装设计花型图案";
        $description = "POP服装趋势网猜你喜欢栏目为服装设计师和企业提供符合需要的服装设计图案素材、服装设计款式图等，为设计师匹配合适的设计图。POP有上亿张款式图、图案素材，猜你喜欢能够帮助设计师在海量款式、图案中找到适合自己开发需求的图片素材，省时、省力、高效做服装研发设计。";
        $this->assign('title', $title);
        $this->assign('keywords', $keywords);
        $this->assign('description', $description);

        $this->assign('columns', $columns);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('powers', $powers);
        $this->assign('columnPid', '');

        $this->display('lists/patternRecommend_list.html');
    }

    //详情页猜你喜欢数据，调用达观相关推荐接口
    public function getLikeDataByPopId()
    {
        /*$ip = $this->input->ip_address();
        $allow_ip = config_item('datagrand_allow_ip');
        if(!in_array($ip,$allow_ip)){
            echo 'Cannot Access!';die;
        }*/
        $t = $this->input->post('t',true);
        $id = $this->input->post('id',true);
        $col = $this->input->post('col',true);
        $pageSize = $this->input->post('pageSize',true) ? $this->input->post('pageSize',true) : 20;
        $table = getProductTableName($t);

        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $this->load->model('datagrand_model');
            $this->datagrand_model->getLikeDataByPopId($table, $id, $col,$pageSize);
        }
    }


    // 图案库--大牌花型
    public function topbrands($params = '')
    {
        $this->benchmark->mark('action');
        $columnId = 120;
        //注意参数$params需要做安全处理,cat=2&sea=3&gen=1&ind=2
        $params = $this->common_model->restoreParams($params);

        //判断用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $userType = $powers['userType'];
        $sortRandom = $powers['sort'];
        $rootUrl = $this->common_model->columnRootPath($columnId);
        $search = $powers['P_Search'] ? $this->common_model->getSearchSufix() : '';
        $link = ['url' => $rootUrl, 'param' => $params, 'sortRandom' => $sortRandom, 'search' => $search];

        $paramsArr = $this->common_model->parseParams($params);
        // 子栏目
        $columns = $this->common_model->getColumns($this->columnPid, $params);
        // 清除全部
        $clearAll = $this->common_model->getClearLink($columnId, $params);
        // 左侧广告
        $leftAds = $this->common_model->getAds($columnId, 2);
        // 热门推荐关键字
        $keys = $this->common_model->getHotKeyWords($columnId, $params);

        // 1,获取列表和分页 通过ajax获取列表
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $this->Patterns_model->ajaxList($params, $columnId, $powers);
            return;
        }
        // 判断是否要显示水印或遮罩
        isShowWatermark($paramsArr);

        // 1,获取列表和分页
        // $this->getLists($params, $columnId, $powers, $rootUrl);

        // 获取您已选择内容
        $tips = $this->common_model->getTips($columnId, $params);
        //获取时间范围
        $time_text = $this->common_model->getTimeText($params);
        $this->assign('time_text', $time_text);

        //seo搜索引擎，标题、关键字、描述
        $this->getTDK($columnId, $params);

        //栏目介绍
        $presentation = $this->Patterns_model->getColumnsPresentation($columnId);
        $this->assign('presentation', $presentation);

        $brandPath = FCPATH . "global/js/fashion/Column{$columnId}_Brand.js";
        $brandStaticTime = getFileModifyTime($brandPath, 'brand');    //品牌时间戳
        $this->assign('brandStaticTime', $brandStaticTime);

        // 底部广告
        $bottomAds = $this->common_model->getAdsBottom($columnId, $this->columnPid);
        $this->assign('bottomAds', $bottomAds);
        $this->secret_params();
        $this->assign('columnId', $columnId);
        $this->assign('rootUrl', $rootUrl);
        $this->assign('link', $link);
        $this->assign('tips', $tips);
        $this->assign('clearAll', $clearAll);
        $this->assign('columns', $columns);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('userType', $userType);
        $this->assign('leftAds', $leftAds);
        $this->assign('keys', $keys);
        $this->benchmark->mark('actionEnd');
        $this->display('lists/patterns_list.html');
    }


    // 图案库--数码云打印
    public function digitalPrint($params = '')
    {
        $this->benchmark->mark('action');
        $columnId = 121;

        // 数码云打印--隐藏全部时段
        $params = str_replace('tim','',$params);
        // 数码云打印--隐藏按时间最新与按浏览最高
        $params = str_replace('sor','',$params);

        //注意参数$params需要做安全处理,cat=2&sea=3&gen=1&ind=2
        $params = $this->common_model->restoreParams($params);
        //判断用户权限
        // $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $powers = [
            'P_Shade' => FALSE,
            'P_Sort' => FALSE,
            'P_Search' => TRUE,
            'P_Collect' => FALSE,
            'P_UserType' => 1,
            'P_AccountFrom' => 1,
            'userType' => "vip"
        ];
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
        $leftAds = $this->common_model->getAds($columnId, 2);
        // 热门推荐关键字
        $keys = $this->common_model->getHotKeyWords($columnId, $params);
        $paramsArr = $this->common_model->parseParams($params);

        // 获取列表和分页 通过ajax获取列表
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $this->Patterns_model->ajaxList($params, $columnId, $powers);
            return;
        }
        // 获取列表和分页
        // $this->getLists($params, $columnId, $powers, $rootUrl);
        // 获取您已选择内容
        $tips = $this->common_model->getTips($columnId, $params);

        /*
        //获取时间范围--全部时段隐藏
        $time_text = $this->common_model->getTimeText($params);
        $this->assign('time_text', $time_text);
        */

        //seo搜索引擎，标题、关键字、描述
        $this->getTDK($columnId, $params);

        //栏目介绍
        $presentation = $this->Patterns_model->getColumnsPresentation($columnId);
        $this->assign('presentation', $presentation);

        $this->assign('memberFlag', 0);
        // 底部广告
        $bottomAds = $this->common_model->getAdsBottom($columnId, $this->columnPid);
        $this->assign('bottomAds', $bottomAds);

        $this->secret_params();
        $this->assign('columnId', $columnId);
        $this->assign('rootUrl', $rootUrl);
        $this->assign('link', $link);
        $this->assign('tips', $tips);
        $this->assign('clearAll', $clearAll);
        $this->assign('columns', $columns);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('userType', $userType);
        $this->assign('leftAds', $leftAds);
        $this->assign('keys', $keys);
        $this->benchmark->mark('actionEnd');
        $this->display('lists/patterns_list.html');
    }

    /**
     * 图案工艺
     * @param string $params
     */
    public function technics($params = '')
    {
        $this->benchmark->mark('action');
        $columnId = 124;
        //注意参数$params需要做安全处理,cat=2&sea=3&gen=1&ind=2
        $params = $this->common_model->restoreParams($params);

        //判断用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $userType = $powers['userType'];
        $sortRandom = $powers['sort'];
        $rootUrl = $this->common_model->columnRootPath($columnId);
        $search = $powers['P_Search'] ? $this->common_model->getSearchSufix() : '';
        $link = ['url' => $rootUrl, 'param' => $params, 'sortRandom' => $sortRandom, 'search' => $search];

        $paramsArr = $this->common_model->parseParams($params);
        // 子栏目
        $columns = $this->common_model->getColumns($this->columnPid, $params);
        // 清除全部
        $clearAll = $this->common_model->getClearLink($columnId, $params);
        // 左侧广告
        $leftAds = $this->common_model->getAds($columnId, 2);
        // 热门推荐关键字
        $keys = $this->common_model->getHotKeyWords($columnId, $params);

        // 获取列表和分页 通过ajax获取列表
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $this->Patterns_model->ajaxList($params, $columnId, $powers);
            return;
        }
        // 判断是否要显示水印或遮罩
        isShowWatermark($paramsArr);
        // 获取列表和分页
        // $this->getLists($params, $columnId, $powers, $rootUrl);
        // 获取您已选择内容
        $tips = $this->common_model->getTips($columnId, $params);
        //获取时间范围
        $time_text = $this->common_model->getTimeText($params);
        $this->assign('time_text', $time_text);

        //seo搜索引擎，标题、关键字、描述
        $this->getTDK($columnId, $params);

        //栏目介绍
        $presentation = $this->Patterns_model->getColumnsPresentation($columnId);
        $this->assign('presentation', $presentation);

        $brandPath = FCPATH . "global/js/fashion/Column{$columnId}_Brand.js";
        $brandStaticTime = getFileModifyTime($brandPath, 'brand');    //品牌时间戳
        $this->assign('brandStaticTime', $brandStaticTime);

        // 底部广告
        $bottomAds = $this->common_model->getAdsBottom($columnId, $this->columnPid);

        //AES_KEY加密
        $this->secret_params();

        $this->assign('bottomAds', $bottomAds);
        $this->assign('columnId', $columnId);
        $this->assign('rootUrl', $rootUrl);
        $this->assign('link', $link);
        $this->assign('tips', $tips);
        $this->assign('clearAll', $clearAll);
        $this->assign('columns', $columns);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('userType', $userType);
        $this->assign('leftAds', $leftAds);
        $this->assign('keys', $keys);
        $this->benchmark->mark('actionEnd');
        $this->display('lists/patterns_list.html');
    }

    /**
     * 图案库 -> 图案专题(非子栏目，取未来趋势数据)
     */
    public function specialTopicPatterns($params = '')
    {
        $this->benchmark->mark('action');
        $params = $this->common_model->restoreParams($params);
        $columnId = self::SPECIAL_TOPIC_PATTERNS; // 图案专题id
        $columnIdTrends = 1; // 未来趋势

        $this->load->model('trends_model');

        //判断用户权限
        $pid = 1; // Trends的pid
        $powers = $this->common_model->getPowers($pid, $params, $columnIdTrends);
        $userType = $powers['userType'];
        $rootUrl = '/patterns/specialtopicpatterns/';
        $sortRandom = $powers['sort'];
        $search = $powers['P_Search'] ? $this->common_model->getSearchSufix() : '';

        $paramsArr = $this->common_model->parseParams($params, 1);
        $linkP = $paramsArr;
        $linkP['stp'] = 1; // 图案专题
        $linkP = $this->common_model->parseParams($linkP, 2);
        $link = ['url' => $rootUrl, 'param' => $linkP, 'sortRandom' => $sortRandom, 'search' => $search];

        // 子栏目，图案中取
        $columns = $this->common_model->getColumns($this->columnPid, $params);

        // 清除全部
        $clearAll = '/patterns/specialtopicpatterns/';

        // 左侧广告,取图案素材的左侧广告
        $leftAds = $this->common_model->getAds(82, 2);

        // 热门推荐关键字,取图案素材的
        $keys = $this->common_model->getHotKeyWords($columnId, $params);

        // 获取列表和分页 通过ajax获取列表
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $params = empty($params) ? 'stp_1' : $params . '-stp_1';
            $this->Patterns_model->ajaxList($params, $columnIdTrends, $powers);
            return;
        }
        // 获取列表和分页
        // $this->getLists($params, $columnIdTrends, $powers, $rootUrl);

        // 获取您已选择内容
        $tips = $this->common_model->getTips(1, $params);
        unset($tips['ind']); // 去掉行业条件
        if (!empty($tips)) {
            foreach ($tips as $k => $v) {
                if (isset($v['link'])) {
                    $tips[$k]['link'] = str_replace('/trends/', '/patterns/specialtopicpatterns/', $v['link']);
                }
            }
        }
        //获取时间范围
        $time_text = $this->common_model->getTimeText($params);
        $this->assign('time_text', $time_text);

        //seo搜索引擎，标题、关键字、描述
        $this->getTDK($columnId, $params);

        //栏目介绍
        $presentation = $this->Patterns_model->getColumnsPresentation($this->columnPid);
        $this->assign('presentation', $presentation);

        // 底部广告
        $bottomAds = $this->common_model->getAdsBottom($columnIdTrends, $this->columnPid);
        $this->assign('bottomAds', $bottomAds);
        // 左侧子栏目选中状态用
        $this->assign('columnId', $columnId);
        $this->assign('columnIdTrends', $columnIdTrends);
        $this->assign('rootUrl', $rootUrl);
        $this->assign('link', $link);
        $this->assign('tips', $tips);
        $this->assign('clearAll', $clearAll);
        $this->assign('columns', $columns);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('userType', $userType);
        $this->assign('leftAds', $leftAds);
        $this->assign('keys', $keys);

        $this->benchmark->mark('actionEnd');
        $this->display('lists/patterns_special_topic_list.html');
    }

    // 条件栏部分
    public function filterConditions()
    {
        $this->benchmark->mark('selectItems');

        $columnId = $this->input->post('col');
        $params = $this->input->post('params');
        $paramsArr = $this->common_model->parseParams($params, 1);
        // 用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $conditions = $this->Patterns_model->getConditions($params, $columnId, $powers);
        switch ($columnId) {
            case '82':// 图案素材
                $selectItem = array('sPatternUse', 'sPatternFormat', 'sPatternTechnology', 'sPatternContent');
                $gender = $this->common_model->getGenderByRequest($params);
                if (5 == $gender) {//为童装时
                    $selectItem[] = 'sGender';
                }
                $selectItems = $this->Patterns_model->getSelectItems($selectItem, $columnId, $params, $powers);
                //性别(男童、女童)
                $sGender = isset($selectItems['sGender']) ? $selectItems['sGender'] : [];
                // 图案应用
                $patternUses = $selectItems['sPatternUse'];
                // 格式
                $patternFormats = $selectItems['sPatternFormat'];
                // 图案工艺
                //$patternTechnologys = $selectItems['sPatternTechnology'];
                // 图案内容
                $patternContents = $selectItems['sPatternContent'];
                
                $this->load->model("styles_model");
                $colorRes = $this->styles_model->getColorAnalysisData($conditions, $columnId);
                $colorRes = json_decode($colorRes, true);
                foreach ($colorRes as $color) {
                    $sAssortColor[$color['id']] = [
                        'sName' => $color['name'],
                        'link' => $this->common_model->getLink($columnId, $params, 'aco', $color['id'], TRUE, 'anchor'),
                        'sAlias' => $color['itemStyle']['normal']['color'],
                    ];
                }
                $selectItems['sAssortColor'] = $sAssortColor;
                $this->assign('sAssortColor', $sAssortColor);
                $this->assign('sGender', $sGender);
                $this->assign('patternUses', $patternUses);
                $this->assign('patternFormats', $patternFormats);
                //$this->assign('patternTechnologys', $patternTechnologys);
                $this->assign('patternContents', $patternContents);
                break;

            case '120'://大牌花型
                $selectItem = array('sPatternUse', 'sPatternFormat', 'sPatternTechnology', 'sPatternContent');
                $gender = $this->common_model->getGenderByRequest($params);
                if (5 == $gender) {//为童装时
                    $selectItem[] = 'sGender';
                }
                $selectItems = $this->Patterns_model->getSelectItems($selectItem, $columnId, $params, $powers);
                //性别(男童、女童)
                $sGender = isset($selectItems['sGender']) ? $selectItems['sGender'] : [];
                // 图案应用
                $patternUses = $selectItems['sPatternUse'];
                // 格式
                //$patternFormats = $selectItems['sPatternFormat'];
                // 图案内容
                $patternContents = $selectItems['sPatternContent'];
                // 品牌
                $brands = $this->common_model->getBrands();

                $this->assign('brands', $brands);
                $this->assign('sGender', $sGender);
                $this->assign('patternUses', $patternUses);
                //$this->assign('patternFormats', $patternFormats);
                $this->assign('patternContents', $patternContents);
                break;

            case '121'://数码云打印
                $selectItem = ['sPatternContent', 'sPatternUse'];
                $selectItems = $this->Patterns_model->getSelectItems($selectItem, $columnId, $params, $powers);

                // 图案内容
                $patternContents = $selectItems['sPatternContent'];
                // 图案应用
                $patternUses = $selectItems['sPatternUse'];

                $this->assign('patternUses', $patternUses);
                $this->assign('patternContents', $patternContents);
                break;

            case '124'://图案工艺
                $selectItem = ['sPatternTechnology', 'sCraftSource'];
                $selectItems = $this->Patterns_model->getSelectItems($selectItem, $columnId, $params, $powers);
                // 图案工艺
                $patternTechnologys = $selectItems['sPatternTechnology'];
                // 工艺来源
                $processSources = $selectItems['sCraftSource'];
                // 品牌
                $brands = $this->common_model->getBrands();
                $this->assign('brands', $brands);

                $this->assign('patternTechnologys', $patternTechnologys);
                $this->assign('processSources', $processSources);
                break;
        }
        $this->assign('columnId', $columnId);
        $this->assign('paramsArr', $paramsArr);// 参数数组

        echo $this->fetch('lists/references_select_items.html');
        $this->benchmark->mark('selectItemsEnd');
    }

    /**
     * 获取seo搜索引擎，标题、关键字、描述
     * @param $columnId int 栏目ID
     * @param $params string 参数字符串
     */
    private function getTDK($columnId, $params)
    {
        $seoArray = $this->Patterns_model->getSeoArray($columnId, $params);

        $this->assign('title', $seoArray['title']);
        $this->assign('keywords', $seoArray['keywords']);
        $this->assign('description', $seoArray['description']);
    }

    //--------------------------------------------------------饼图开始-------------------------------------------------------
    public function getPieData()
    {
        $columnId = $this->input->post('col');
        $params = $this->input->post('params');
        $keywords = $this->input->post('key');
        // 用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);

        $data = $this->Patterns_model->pieData($params, $columnId, $powers);
        echo json_encode($data);
    }
    //--------------------------------------------------------饼图结束-------------------------------------------------------

}
