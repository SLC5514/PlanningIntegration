<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @todo    灵感源-子栏目的处理
 * @author  chenqiuju
 * @time    20160301
 */
class Inspiration extends POP_Controller
{
    private $columnPid = 8;
    private $pageSize = 36;

    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model','inspiration_model']);
        // 头部广告
        $topAds = $this->common_model->getAds($this->columnPid, 1);
        $this->assign('topAds', $topAds);
        $this->assign('columnPid', $this->columnPid);
        //vip用户即将过期提示条
        $vipTips = $this->isVipExpireTips();
        $this->assign('vipTips', $vipTips);
    }

    // 灵感源首页+全部     URL类似 http://www.fashion-mostrend.com/inspiration/index/
    public function index($params = '')
    {
        $params = $params ? $params : $this->uri->segment(2);
        $this->report($params);
    }

    // 灵感源-灵感报告
    public function report($params = '')
    {
        $this->benchmark->mark('action');
        $columnId = 90;
        //注意参数$params需要做安全处理,cat=2&sea=3&gen=1&ind=2
        $params = $this->common_model->restoreParams($params);
        //判断用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $userType = $powers['userType'];
        $sortRandom = $powers['sort'];
        $rootUrl = $this->common_model->columnRootPath($columnId);
        $search = $powers['P_Search']?$this->common_model->getSearchSufix():'';
        $link = ['url' => $rootUrl, 'param' => $params, 'sortRandom' => $sortRandom, 'search'=>$search];

        // 子栏目
        $columns = $this->common_model->getColumns(7, $params);
        // $cols = $this->common_model->getColumns(8, $params); // 去除灵感图库
        // 获取您已选择内容
        $tips = $this->common_model->getTips($columnId, $params);
        // 清除全部
        $clearAll = $this->common_model->getClearLink($columnId, $params);
        // 左侧广告
        $leftAds = $this->common_model->getAds($columnId, 2);
        //灵感源
        $types = $this->inspiration_model->getInspirations($columnId, $params);
        // 热门推荐关键字
        $keys = $this->common_model->getHotKeyWords($columnId, $params);

        $paramsArr = $this->common_model->parseParams($params, 1);//获取参数

        //判断url中是否含有类型
        $type = '';
        if (isset($paramsArr['type'])) {
            $type = $paramsArr['type'];
        }

        // 获取列表和分页 通过ajax获取列表
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $this->inspiration_model->ajaxList($params, $columnId, $powers);
            return;
        }

        //获取时间范围
        $time_text = $this->common_model->getTimeText($params);
        $this->assign('time_text', $time_text);

        //栏目介绍
        $presentation = $this->inspiration_model->getColumnsPresentation($columnId);
        $this->assign('presentation', $presentation);

        //seo搜索引擎，标题、关键字、描述
        $this->getTDK($columnId, $params);

        // 底部广告
        $bottomAds = $this->common_model->getAdsBottom($columnId,$this->columnPid);
        $this->assign('bottomAds', $bottomAds);

        $this->assign('columnId', $columnId);
        $this->assign('columns', $columns);
        //$this->assign('cols', $cols); // 去除灵感图库-8
        $this->assign('tips', $tips);
        $this->assign('clearAll', $clearAll);
        $this->assign('types', $types);
        $this->assign('type', $type);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('userType', $userType);
        $this->assign('leftAds', $leftAds);
        $this->assign('keys', $keys);
        $this->assign('rootUrl', $rootUrl);
        $this->assign('link', $link);
        $this->assign('isReport', 1);
        $this->assign('urlprefix', '/details/report/');
        $this->benchmark->mark('actionEnd');
        $this->display('lists/inspirations_list.html');
    }

    // 灵感源-灵感图库
    public function library($params = '')
    {
        $this->benchmark->mark('action');
        $columnId = 91;
        //注意参数$params需要做安全处理,cat=2&sea=3&gen=1&ind=2
        $params = $this->common_model->restoreParams($params);
        //判断用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $userType = $powers['userType'];
        $sortRandom = $powers['sort'];
        $rootUrl = $this->common_model->columnRootPath($columnId);
        $search = $powers['P_Search']?$this->common_model->getSearchSufix():'';
        $link = ['url' => $rootUrl, 'param' => $params, 'sortRandom' => $sortRandom, 'search'=>$search];

        // 子栏目
        $columns = $this->common_model->getColumns(7, $params);
        $cols = $this->common_model->getColumns(8, $params);//灵感图库
        // 获取您已选择内容
        $tips = $this->common_model->getTips($columnId, $params);
        // 清除全部
        $clearAll = $this->common_model->getClearLink($columnId, $params);
        // 左侧广告
        $leftAds = $this->common_model->getAds($columnId, 2);
        //灵感源
        $types = $this->inspiration_model->getInspirations($columnId, $params);
        // 热门推荐关键字
        $keys = $this->common_model->getHotKeyWords($columnId, $params);

        $paramsArr = $this->common_model->parseParams($params, 1);
        //判断url中是否含有类型
        $type = '';
        if (isset($paramsArr['type'])) {
            $type = $paramsArr['type'];
        }

        // 获取列表和分页 通过ajax获取列表
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $this->inspiration_model->ajaxList($params, $columnId, $powers);
            return;
        }

        //获取时间范围
        $time_text = $this->common_model->getTimeText($params);
        $this->assign('time_text', $time_text);

        //栏目介绍
        $presentation = $this->inspiration_model->getColumnsPresentation($columnId);
        $this->assign('presentation', $presentation);

        //seo搜索引擎，标题、关键字、描述
        $this->getTDK($columnId, $params);

        // 底部广告
        $bottomAds = $this->common_model->getAdsBottom($columnId,$this->columnPid);
        $this->assign('bottomAds', $bottomAds);

        $this->assign('columnId', $columnId);
        $this->assign('columns', $columns);
        $this->assign('cols', $cols);
        $this->assign('tips', $tips);
        $this->assign('clearAll', $clearAll);
        $this->assign('types', $types);
        $this->assign('type', $type);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('userType', $userType);
        $this->assign('leftAds', $leftAds);
        $this->assign('keys', $keys);
        $this->assign('rootUrl', $rootUrl);
        $this->assign('link', $link);
        $this->assign('isReport', 0);
        $this->assign('urlprefix', '/details/style/');
        $this->benchmark->mark('actionEnd');
        $this->display('lists/inspirations_list.html');
    }
	
	 // 灵感源-灵感视频
    public function video($params = '')
    {
        $this->benchmark->mark('action');
        $columnId = 116;
        //注意参数$params需要做安全处理,cat=2&sea=3&gen=1&ind=2
        $params = $this->common_model->restoreParams($params);
        //判断用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $userType = $powers['userType'];
        $sortRandom = $powers['sort'];
        $rootUrl = $this->common_model->columnRootPath($columnId);
        $search = $powers['P_Search']?$this->common_model->getSearchSufix():'';
        $link = ['url' => $rootUrl, 'param' => $params, 'sortRandom' => $sortRandom, 'search'=>$search];

        // 子栏目
        $columns = $this->common_model->getColumns(7, $params);
        $cols = $this->common_model->getColumns(8, $params);

        // 获取您已选择内容
        $tips = $this->common_model->getTips($columnId, $params);
        // 清除全部
        $clearAll = $this->common_model->getClearLink($columnId, $params);
        // 左侧广告
        $leftAds = $this->common_model->getAds($columnId, 2);
        //灵感源
        $types = $this->inspiration_model->getInspirations($columnId, $params);
        // 热门推荐关键字
        $keys = $this->common_model->getHotKeyWords($columnId, $params);

        $paramsArr = $this->common_model->parseParams($params, 1);
        //判断url中是否含有类型
        $type = '';
        if (isset($paramsArr['type'])) {
            $type = $paramsArr['type'];
        }
        //当前页
        $page = $this->common_model->getPage($paramsArr);
        //偏移量
        $this->pageSize = 24;
        $offset = ($page - 1) * ($this->pageSize);

        $lists = [];
        $totalCount = $this->inspiration_model->getInspirationLists($params, $columnId, $lists, $offset, $this->pageSize, $powers);

        //生成页码
        $pageHtml = $this->makePageHtml($paramsArr, $totalCount, $this->pageSize, $page, $rootUrl);
        //生成简单页码
        $simplePageHtml = $this->makePageHtml($paramsArr, $totalCount, $this->pageSize, $page, $rootUrl, TRUE);

        //获取时间范围
        $time_text = $this->common_model->getTimeText($params);
        $this->assign('time_text', $time_text);

        //栏目介绍
        $presentation = $this->inspiration_model->getColumnsPresentation($columnId);
        $this->assign('presentation', $presentation);

        //seo搜索引擎，标题、关键字、描述
        $this->getTDK($columnId, $params);

        $this->assign('columnId', $columnId);
        $this->assign('cols', $cols);
        $this->assign('columns', $columns);
        $this->assign('tips', $tips);
        $this->assign('clearAll', $clearAll);
        $this->assign('types', $types);
        $this->assign('type', $type);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('userType', $userType);
        $this->assign('leftAds', $leftAds);
        $this->assign('keys', $keys);
        $this->assign('rootUrl', $rootUrl);
        $this->assign('link', $link);
        $this->assign('totalCount', $totalCount);
        $this->assign('lists', $lists);
        $this->assign('pageHtml', $pageHtml);
        $this->assign('simplePageHtml', $simplePageHtml);
        $this->assign('isReport', 1);
        $this->assign('urlprefix', '/details/style/');
        $this->assign('powers', $powers);
        $this->benchmark->mark('actionEnd');
        $this->display('lists/inspirations_list.html');
    }

    // 暂时无用的条件拼接
    public function filterConditions()
    {
        $this->benchmark->mark('selectItems');

        $columnId = $this->input->post('col');
        $params = $this->input->post('params');

        //灵感源
        $types = $this->inspiration_model->getInspirations($columnId, $params);
        $this->assign('columnId', $columnId);
        $this->assign('types', $types);

        echo $this->fetch('lists/inspiration_select_items.html');
        $this->benchmark->mark('selectItemsEnd');
    }

    /**
     * 获取seo搜索引擎，标题、关键字、描述
     * @param $columnId int|string 栏目ID
     * @param $params string 参数字符串
     */
    private function getTDK($columnId, $params)
    {
        $seoArray = $this->inspiration_model->getSeoArray($columnId, $params);

        $this->assign('title', $seoArray['title']);
        $this->assign('keywords', $seoArray['keywords']);
        $this->assign('description', $seoArray['description']);
    }


}
