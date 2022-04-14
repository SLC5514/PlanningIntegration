<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @todo    设计素材-子栏目的处理
 * @author  chenqiuju
 * @time    20160301
 */
class References extends POP_Controller
{
    private $columnPid = 7;

    /**
     * @var References_model
     */
    public $references_model;

    /**
     * @var Common_model
     */
    public $common_model;


    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'references_model']);
        // 头部广告
        $topAds = $this->common_model->getAds($this->columnPid, 1);
        //判断详情页权限，（是否可以加入工作台和下载）
        $memberPower = memberPower('detail');
        if (isset($memberPower['P_Collect']) && $memberPower['P_Collect'] == false) {
            $notJoinW = 1;
            $this->assign('notJoinW', $notJoinW);
        }
        if (isset($memberPower['P_SingleDownLoad']) && $memberPower['P_SingleDownLoad'] == false) {
            $notDomn = 1;
            $this->assign('notDomn', $notDomn);
        }
        $power = memberPower('other');
        $memberFlag = 0;
        if ($power['P_UserType'] == 5) {//游客
            $memberFlag = 1;
        }
        if ($power['P_UserType'] == 4) {//普通
            $memberFlag = 2;
        }
        $this->assign('memberFlag', $memberFlag);
        $this->assign('topAds', $topAds);
        $this->assign('columnPid', $this->columnPid);


        //获取js时间戳
        $regionPath = FCPATH . 'global/js/fashion/regionFromSolr.js';
        $regionStaticTime = getFileModifyTime($regionPath, 'region');
        $brandPath = FCPATH . 'global/js/fashion/Column4_Brand.js';
        $brandStaticTime = getFileModifyTime($brandPath, 'brand');

        $this->assign('regionStaticTime', $regionStaticTime);
        $this->assign('brandStaticTime', $brandStaticTime);

        //vip用户即将过期提示条
        $vipTips = $this->isVipExpireTips();
        $this->assign('vipTips', $vipTips);
    }

    // 设计素材-款式模板（矢量图）
    public function design($params = '')
    {
        $this->_common(80, $params);
    }

    // 设计素材-款式细节
    public function details($params = '')
    {
        $this->_common(81, $params);
    }

    // 设计素材-展会面料
    public function fabricgallery($params = '')
    {
        $this->_common(117, $params);
    }

    // 设计素材-服饰品
    public function accessories($params = '')
    {
        $this->_common(84, $params);
    }

    // 设计素材-店铺陈列
    public function visual($params = '')
    {
        $this->_common(85, $params);
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
        switch ($columnId) {
            case '80': // 款式模板
                $selectItem[] = 'sCategory';
                $selectItems = $this->references_model->getSelectItems($selectItem, $columnId, $params, $powers);
                $sCategory = $selectItems['sCategory'];
                $this->assign('sCategory', $sCategory); // 单品
                break;
            case '81': // 款式细节
                $selectItem = ['sCategory', 'sStylePart'];
                $selectItems = $this->references_model->getSelectItems($selectItem, $columnId, $params, $powers);
                $sCategory = $selectItems['sCategory'];
                $sStylePart = $selectItems['sStylePart'];
                $this->assign('sCategory', $sCategory); //单品
                $this->assign('sStylePart', $sStylePart); // 款式部位
                break;
            case '82':// 图案素材
                $selectItem = array('sPatternUse', 'sPatternFormat', 'sPatternTechnology', 'sPatternContent');
                $gender = $this->common_model->getGenderByRequest($params);
                if (5 == $gender) {//为童装时
                    $selectItem[] = 'sGender';
                }
                $selectItems = $this->references_model->getSelectItems($selectItem, $columnId, $params, $powers);
                //性别(男童、女童)
                $sGender = isset($selectItems['sGender']) ? $selectItems['sGender'] : [];
                // 图案应用
                $patternUses = $selectItems['sPatternUse'];
                // 格式
                $patternFormats = $selectItems['sPatternFormat'];
                // 图案工艺
                $patternTechnologys = $selectItems['sPatternTechnology'];
                // 图案内容
                $patternContents = $selectItems['sPatternContent'];

                $this->assign('sGender', $sGender);
                $this->assign('patternUses', $patternUses);
                $this->assign('patternFormats', $patternFormats);
                $this->assign('patternTechnologys', $patternTechnologys);
                $this->assign('patternContents', $patternContents);
                break;
            case '117':  // 面料图库
                $selectItem = array('sFabricCraft', 'sMaterial', 'sExhibitionName', 'sPatternContent');
                $selectItems = $this->references_model->getSelectItems($selectItem, $columnId, $params, $powers);
                // 性别(男童、女童)
                $sGender = isset($selectItems['sGender']) ? $selectItems['sGender'] : [];
                // 面料工艺
                $sFabricCraft = $selectItems['sFabricCraft'];
                // 面料材质
                $sMaterial = $selectItems['sMaterial'];
                // 图案内容
                $patternContents = $selectItems['sPatternContent'];
                // 面料展
                $sExhibitionName = $selectItems['sExhibitionName'];

                $this->assign('sGender', $sGender);
                $this->assign('sFabricCraft', $sFabricCraft);
                $this->assign('sMaterial', $sMaterial);
                $this->assign('patternContents', $patternContents);
                $this->assign('sExhibitionName', $sExhibitionName);
                break;
            case '84': // 服饰品
                $selectItem = array('iSeason', 'sCategory');
                $selectItems = $this->references_model->getSelectItems($selectItem, $columnId, $params, $powers);
                // 单品
                $sCategory = $selectItems['sCategory'];
                // 季节
                $seasons = $selectItems['iSeason'];

                $this->assign('sCategory', $sCategory);
                $this->assign('seasons', $seasons);
                $this->assign('regions', TRUE);
                break;
            case '85': // 店铺陈列
                $selectItem = array('iSeason');
                $selectItems = $this->references_model->getSelectItems($selectItem, $columnId, $params, $powers);
                // 季节
                $seasons = $selectItems['iSeason'];
                // 品牌
                $brands = $this->common_model->getBrands($columnId);

                $this->assign('seasons', $seasons);
                $this->assign('brands', $brands);
                $this->assign('regions', TRUE);
                break;
            default:
                $selectItems = $this->styles_model->getSelectItems([], $columnId, $params, $powers);
                break;
        }
        $this->assign('columnId', $columnId);
        $this->assign('paramsArr', $paramsArr);// 参数数组

        echo $this->fetch('lists/references_select_items.html');
        $this->benchmark->mark('selectItemsEnd');
    }

    /**
     * 各个子栏目方法体基本一致
     * @param $iColumnId
     * @param $params
     */
    private function _common($iColumnId, $params)
    {
        $this->benchmark->mark('action');
        $columnId = $iColumnId;
        // 注意参数$params需要做安全处理,cat=2&sea=3&gen=1&ind=2
        $params = $this->common_model->restoreParams($params);
        // 判断用户权限
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
        // 热门推荐关键字
        $keys = $this->common_model->getHotKeyWords($columnId, $params);
        $paramsArr = $this->common_model->parseParams($params, 1);
        // 获取列表和分页 通过ajax获取列表
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $this->references_model->ajaxList($params, $columnId, $powers);
            return;
        }
        // 判断是否要显示水印或遮罩
        isShowWatermark($paramsArr);
        // 获取您已选择内容
        $tips = $this->common_model->getTips($columnId, $params);
        $this->assign('tips', $tips);

        // 获取时间范围
        $time_text = $this->common_model->getTimeText($params);
        $this->assign('time_text', $time_text);

        // seo搜索引擎，标题、关键字、描述
        $this->getTDK($columnId, $params);

        // 栏目介绍
        $presentation = $this->references_model->getColumnsPresentation($columnId);
        $this->assign('presentation', $presentation);
        // 左侧广告
        $leftAds = $this->common_model->getAds($columnId, 2);
        $this->assign('leftAds', $leftAds);
        // 底部广告
        $bottomAds = $this->common_model->getAdsBottom($columnId, $this->columnPid);
        $this->assign('bottomAds', $bottomAds);

        //反扒参数
        $this->load->model('Secret_model');
        list($symmetricKey, $timeStamp) = $this->Secret_model->init_data();
        $token = $this->Secret_model->get_token();
        $this->assign('token', $token);//token(uid_popuid_secret)
        $this->assign('sign', $symmetricKey);//AES_KEY
        $this->assign('timeStamp', $timeStamp);//生成AES_KEY的时间戳


        $this->assign('columnId', $columnId);
        $this->assign('rootUrl', $rootUrl);
        $this->assign('link', $link);
        $this->assign('clearAll', $clearAll);
        $this->assign('columns', $columns);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('userType', $userType);
        $this->assign('keys', $keys);
        $this->benchmark->mark('actionEnd');
        $this->display('lists/references_list.html');
    }

    /**
     * 获取seo搜索引擎，标题、关键字、描述
     * @param $columnId int|string 栏目ID
     * @param $params string 参数字符串
     */
    private function getTDK($columnId, $params)
    {
        $seoArray = $this->references_model->getSeoArray($columnId, $params);

        $this->assign('title', $seoArray['title']);
        $this->assign('keywords', $seoArray['keywords']);
        $this->assign('description', $seoArray['description']);
    }

}

