<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @todo    T台发布-子栏目的处理
 * @author  chenqiuju
 * @time    20160301
 */
class Runways extends POP_Controller
{
    private $columnPid = 3;
    private $pageSize = 18;

    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'runways_model', 'details_model']);
        // 头部广告
        $topAds = $this->common_model->getAds($this->columnPid, 1);
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

    // T台发布首页+全部     URL类似 http://www.fashion-mostrend.com/runways/index/
    public function index($params = '')
    {
        $this->benchmark->mark('action');
        $columnId = 3;
        //注意参数$params需要做安全处理,cat=2&sea=3&gen=1&ind=2
        $params = $this->common_model->restoreParams($params);
        //判断用户身份
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $userType = $powers['userType'];
        $sortRandom = $powers['sort'];
        $rootUrl = $this->common_model->columnRootPath($columnId);
        $search = $powers['P_Search'] ? $this->common_model->getSearchSufix() : '';
        $link = ['url' => $rootUrl, 'param' => $params, 'sortRandom' => $sortRandom, 'search' => $search];
        // 获取二级列表页面根目录
        $rootInside = '/runways/inside/';

        // 子栏目
        $columns = $this->common_model->getColumns($this->columnPid, $params);

        $paramsArr = $this->common_model->parseParams($params, 1);

        // 获取左侧发布会名称  $regions['3'] 代表 all
        $regions = $this->runways_model->getRegionIds($columnId, $params);

        // 获取T台子栏目的id
        $reg_columnId = isset($paramsArr['reg']) ? $paramsArr['reg'] : 3;
        foreach ($regions as $col_id => $col_info) {
            $regions[$col_id]['sPresentation'] = $columns[3]['sPresentation'];
        }
        $this->assign('reg_columnId', $reg_columnId);// 判断的id
        // 判断url中是否含有类型
        $region = '';
        if (isset($paramsArr['reg'])) {
            $region = $paramsArr['reg'];
        }
        // 清除全部
        $clearAll = $this->common_model->getClearLink($columnId, $params);
        // 左侧广告
        $leftAds = $this->common_model->getAds($columnId, 2);
        // 热门推荐关键字
        $keys = $this->common_model->getHotKeyWords($columnId, $params);

        // 获取列表和分页 通过ajax获取列表
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $this->runways_model->ajaxList($params, $columnId, $powers);
            return;
        }

        // 获取您已选择内容
        $tips = $this->common_model->getTips($columnId, $params);
        // 获取时间范围
        $time_text = $this->common_model->getTimeText($params);
        $this->assign('time_text', $time_text);
        // seo搜索引擎，标题、关键字、描述
        $this->getTDK($columnId, $params);

        //栏目介绍
        $presentation = $this->runways_model->getColumnsPresentation($columnId);
        $this->assign('presentation', $presentation);

        // 底部广告
        $bottomAds = $this->common_model->getAdsBottom($columnId, $this->columnPid);
        $this->assign('bottomAds', $bottomAds);

        $this->assign('columnId', $columnId);
        $this->assign('tips', $tips);
        $this->assign('columns', $columns);
        $this->assign('regions', $regions);
        $this->assign('region', $region);
        $this->assign('params', $params);
        $this->assign('clearAll', $clearAll);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('userType', $userType);
        $this->assign('leftAds', $leftAds);
        $this->assign('keys', $keys);
        $this->assign('rootUrl', $rootUrl);
        $this->assign('rootInside', $rootInside);
        $this->assign('link', $link);

        $this->assign('powers', $powers);
        $this->benchmark->mark('actionEnd');
        $this->display('lists/runways_list.html');
    }

    //T台发布-二级列表页
    public function inside($params = '')
    {
        $this->benchmark->mark('action');
        $columnId = 3;
        //注意参数$params需要做安全处理,cat=2&sea=3&gen=1&ind=2
        $params = $this->common_model->restoreParams($params);
        $paramsArr = $this->common_model->parseParams($params, 1, false);//参数数组

        //判断用户身份
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $userType = $powers['userType'];

        //二级列表页面根目录
        $rootInside = '/runways/inside/';
        $link = ['url' => $rootInside, 'param' => $params];
        //初始值
        $default = $sVideoHtml = $imgPath = $pageHtml = $simplePageHtml = $totalCount = $tempLink = $youku_vid = '';
        $data = [];

        //seo搜索引擎，标题、关键字、描述
        $this->getTDK($columnId, $params);

        //反扒参数
        $this->load->model('Secret_model');
        list($symmetricKey, $timeStamp) = $this->Secret_model->init_data();
        $token = $this->Secret_model->get_token();
        $this->assign('token', $token);//token(uid_popuid_secret)
        $this->assign('sign', $symmetricKey);//AES_KEY
        $this->assign('timeStamp', $timeStamp);//生成AES_KEY的时间戳


        if (isset($paramsArr['id'])) {
            $id = intval($paramsArr['id']);
            $this->load->model('runways_model');
            $pressConArr = OpPopFashionMerger::getProductData($id, OpPopFashionMerger::POP_Table_Name_Product_Presscon);
            $data = $pressConArr[$id];
            if (empty($data)) {
                //找不到内容时，跳404
                show_404();
                exit;
            }
            $path = getImagePath(OpPopFashionMerger::POP_Table_Name_Product_Presscon, $data);
            $data['cover'] = $path['cover'];

            //顶部广告
            $aTopAds = $this->common_model->getAds($columnId, 8, 1, 3);
            //侧边广告
            $aBroadsideAd = $this->common_model->getAds($columnId, 9, 2, 3);
            $this->assign('aTopAds', $aTopAds);
            $this->assign('aBroadsideAd', $aBroadsideAd);

            //相关分析，去潮流解析下的T台分析栏目，仅根据条件（性别，季节，品牌）查询是否有数据，有则显示
            $tempColumnId = 30;//潮流解析下的T台分析栏目id
            $conditions['iColumnId'] = $tempColumnId;
            //性别
            $tempParamsArr = [];
            if ($data['sGender']) {
                $sGender = intval($data['sGender']);
                $this->common_model->childGender($sGender, $conditions);
                $tempParamsArr['gen'] = $sGender;
            }
            //季节
            if ($data['iSeason']) {
                $iSeason = $data['iSeason'];
                $conditions['other'] = ['aLabelIds:' . $iSeason];
                $tempParamsArr['sea'] = $iSeason;
            }
            //品牌
            $brand_tid = intval($data['brand_tid']);
            $conditions['iBrand'] = $brand_tid;
            $tempParamsArr['bra'] = $brand_tid;
            $trend_lists = $searchResult = [];
            $tempTotal = POPSearch::wrapQueryPopFashionMerger('', $conditions, $searchResult);
            if ($tempTotal > 0) {
                $this->assign('relate', true); // $tempLink
                // 相关分析--报告推荐的数据处理(T台分析)
                $trend_lists = $this->runways_model->dealRelationReportSolrData($searchResult);
            }
            $this->assign('trend_lists', $trend_lists);
            $tempParams = $this->common_model->parseParams($tempParamsArr, 2, false);
            $tempRootUrl = $this->common_model->columnRootPath($tempColumnId);
            $tempLink = $tempRootUrl . $tempParams . '/';

            //中间页标签
            $tags = [
                'brandName' => GetCategory::getBrandOtherFormId($brand_tid), 'brandLink' => '/brands/detail/id_' . $brand_tid . '/',
                'colName' => GetCategory::getOtherFromColId($columnId, 'sName'), 'colLink' => $this->common_model->getLink($columnId),
            ];
            if ($data['sGender']) {
                $tags['genInfo'][] = ['sName' => GetCategory::getOtherFromIds($data['sGender'], ['sName'], ''), 'link' => $this->common_model->getLink($columnId, '', 'gen', $data['sGender'])];
            }
            $this->assign('middlePageTags', $tags);

            //---------------------------------------权限判定---------------------------------------------------------
            $sGenderIDs = $data['sGender'];
            //查看列表权限
            $powerArr = memberPower('other');
            if ($powerArr['P_UserType'] == 5) { //T台游客、普通进入二级列表，游客不给看二级列表，普通用户可看有水印列表 2018-06-25
                $this->assign('title', $data['nme'] . '-POP趋势资讯网');
                $this->assign('keywords', $data['nme']);
                $this->assign('description', !empty($data['detail']) ? $data['detail'] : $data['nme']);
                $this->assign("cover", $data['cover']);
                $this->assign("P_UserType", $powerArr['P_UserType']);
                $this->assign("is_runways", true);
                $this->assign('tit', $data['nme']);
                $this->assign('column', $columnId);
                $this->assign('descriptionHtml', $data['detail']);
                $this->assign('data', $data);
                $this->benchmark->mark('actionEnd');
                $this->display('mid_tourist.html');
                die;
            }
            //查看详情权限
            $detailPower = memberPower('detail', array('P_Gender' => $sGenderIDs, 'P_Column' => $columnId));
            if (!$detailPower['P_Visit'] && $powerArr['P_UserType'] != 4) {
                $genVal = GetCategory::getOtherFromIds($sGenderIDs, array('sName'), 'string');
                $pColVal = GetCategory::getOtherFromColId($this->columnPid, 'sName');
                $sColVal = GetCategory::getOtherFromColId($columnId, 'sName');

                $this->assign('sGender', $genVal);
                $this->assign('columnP', $pColVal);
                $this->assign('columnC', $sColVal);

                $this->assign('title', $data['nme'] . '-POP趋势资讯网');
                $this->assign("partVip", true);
                $this->assign('keywords', $data['nme']);
                $this->assign('description', !empty($data['detail']) ? $data['detail'] : $data['nme']);
                $this->assign("P_UserType", $powerArr['P_UserType']);
                $this->assign("cover", $data['cover']);
                $this->assign("is_runways", true);
                $this->assign('tit', $data['nme']);
                $this->assign('column', $columnId);
                $this->assign('descriptionHtml', $data['detail']);
                $this->assign('data', $data);
                $this->benchmark->mark('actionEnd');
                $this->display('mid_tourist.html');
                die;
            }

            //浏览量+1
            $this->load->model('statistics_model');
            $this->statistics_model->setIncrViews('product_presscon', $id, $columnId);

            //---------------------------------------权限判定---------------------------------------------------------

            //存在哪个版本则显示出来
            /*
            // 动态视频的展示不走以前的了
            if ($data['video_flag'] == 1) {
                $this->assign('video_flag', true);
                $default = 'video';
            }*/
            //细节版图片
            if ($data['special_flag'] == 1) {
                $this->assign('special_flag', true);
                $default = 'special';
            }
            //现场及时版
            if ($data['detail_flag'] == 1) {
                $this->assign('detail_flag', true);
                $default = 'live';
            }
            //默认高清版图片
            if ($data['focus_flag'] == 1) {
                $this->assign('focus_flag', true);
                $default = 'focus';
            }
            //显示默认
            if (isset($paramsArr['ver'])) {
                $version = $paramsArr['ver'];
            } else {
                $version = $default;
            }

            //动态视频
            if ($version == 'video') {
                if (empty($data["video_url"])) {
                    $sVideoHtml = '视频缺失';
                } elseif (preg_match('/imgcache.qq.com/i', $data["video_url"])) {
                    $sVideoHtml = $this->details_model->getVedioPath($data["video_url"]);
                } else {
                    //$sVideoHtml = '<embed src="' . $data["video_url"] . '"; quality="high" width="600" height="450" align="middle" allowScriptAccess="always" allowFullScreen="true" mode="transparent" type="application/x-shockwave-flash"></embed>';
                    //$sVideoHtml = '<iframe height=450 width=600 src="' . $data["video_url"] . '" frameborder=0 "allowfullscreen"></iframe>';

                    // 替换video路径
                    if ($data["video_url"]) {
                        $data["video_url"] = $this->details_model->getVedioPath($data["video_url"]);
                    }
                    $youku_vid = $data["video_url"];
                    $sVideoHtml = '<embed src="' . $data["video_url"] . '" quality="high" align="middle" width="600" height="450" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" allowFullScreen="true" ></embed>';

                }
            } else {

                //当前页
                $page = $this->common_model->getPage($paramsArr);

                $pageSize = 35;
                //偏移量
                $offset = ($page - 1) * $pageSize;
                $lists = $this->runways_model->getRunwaysInsideLists($id, $version, $totalCount, $offset, $pageSize, TRUE);
                //生成页码
                $pageHtml = $this->makePageHtml($paramsArr, $totalCount, $pageSize, $page, $rootInside);
                //生成简单页码
                $simplePageHtml = $this->makePageHtml($paramsArr, $totalCount, $pageSize, $page, $rootInside, TRUE);
                foreach ($lists as $key => $val) {
                    $imgPath = getImagePath('product_presscon_details', $val);
                    $fileName = $imgPath['smallPath'];
                    if (!in_array(strtolower(pathinfo($fileName, PATHINFO_EXTENSION)), array('jpg'))) {
                        --$totalCount;
                        unset($lists[$key]);
                        continue;
                    }
                    $lists[$key]['imgSmallPath'] = $fileName;
                    $lists[$key]['imgBigPath'] = $imgPath['bigPath'];
                    $lists[$key]['version'] = $version;
                    $lists[$key]['index'] = $offset++;
                    //判断图片是否有遮罩
                    if (isset($powers['P_Shade']) && $powers['P_Shade']) {
                        if ($page > 1) {
                            $lists[$key]['shade'] = 'shuiy';
                        }
                    }
                }
            }
            //下载按钮状态
            if ($version == 'live') {
                $buttonStatue = isset($data['rar_file']) && !empty($data['rar_file']) ? 1 : 0;
            } else {
                $index = 'rar_file_' . $version;
                $buttonStatue = isset($data[$index]) && !empty($data[$index]) ? 1 : 0;
            }
            $downLoadUrl = $this->config->item('base_url') . 'download/presscon/' . $version . '-' . $id . '/';
            $this->assign('buttonStatue', $buttonStatue);
            $this->assign('downLoadUrl', $downLoadUrl);
        }

        //收藏判断收藏状态  1已收藏 -1无收藏权限 0未收藏
        $bIsCollect = 0;
        if ($powers['P_Collect']) {
            $this->load->model('collect_model');
            $aLogonMessage = get_cookie_value();
            $whetherCollect = $this->collect_model->existCollectStatus($aLogonMessage['sChildID'], OpPopFashionMerger::POP_FASHION_DATABASE_NAME, OpPopFashionMerger::POP_Table_Name_Product_Presscon, $data['id']);
            if ($whetherCollect) {
                $bIsCollect = 1;
            }
        } else {
            $bIsCollect = -1;
        }

        //-- 关联视频专栏的数据 ---------------------------------------------------
        $this->load->model('video_model');
        if ($id) {
            $relation_pop_id = 'presscon_' . $id;
            $videoData = $this->video_model->getVideoDataByRelationPopId($relation_pop_id);
        }
        $this->assign('videoData', !empty($videoData) ? $videoData : []);
        // 性别
        $data['genderName'] = $this->video_model->getAttrName($data['sGender'], 'gen');
        // 季节
        $data['seasonName'] = $this->video_model->getAttrName($data['iSeason'], 'sea');
        // 设计师（品牌）
        $data['brand_name'] = $this->video_model->getAttrName($data['brand_tid'], 'bra');
        // 地区
        $data['regionName'] = $this->video_model->getAttrName($data['iRegion'], 'reg');

        $filter = $filterData = $insideAllData = [];
        if (!empty($data['brand_tid'])) {
            $filterData['iSeason'] = $this->video_model->getRelevantSeason($data, $columnId);
            if (!empty($filterData['iSeason'])) {
                // 选中季节
                $filter['sea'] = !empty($data['iSeason']) ? $data['iSeason'] : '';
            }
            $seasonId = !empty($data['iSeason']) ? $data['iSeason'] : '';
            $brandId = $data['brand_tid'];
            $compact = compact('seasonId', 'brandId');
            $insideAllData = $this->video_model->getColumnInsideAllData($compact, $columnId);
            $filter['id'] = !empty($data['id']) ? $data['id'] : '';
        }

        $this->assign('filter', $filter);
        $this->assign('insideAllData', $insideAllData);
        $this->assign('filterData', $filterData);

        $this->assign('title', $data['nme'] . '-POP服装趋势网');
        $this->assign('keywords', $data['nme']);

        $this->assign('youku_vid', $youku_vid);
        $this->assign('columnId', $columnId);
        $this->assign('bIsCollect', $bIsCollect);
        $this->assign('t', 'presscon');
        $this->assign('id', $id);
        $this->assign('data', $data);
        $this->assign('lists', $lists);
        $this->assign('version', $version);
        $this->assign('tempLink', $tempLink);
        $this->assign('sVideoHtml', $sVideoHtml);
        $this->assign('link', $link);
        $this->assign('userType', $userType);
        $this->assign('totalCount', $totalCount);
        $this->assign('pageHtml', $pageHtml);
        $this->assign('simplePageHtml', $simplePageHtml);
        $this->benchmark->mark('actionEnd');
        // 模板中区分是否是书籍
        $this->assign('isBook', false);

        $this->display('lists/runways_list_inside.html');
    }

    /*
    *@todo 条件栏部分
    */
    public function filterConditions()
    {

        $columnId = $this->input->post('col');
        $params = $this->input->post('params');
        $paramsArr = $this->common_model->parseParams($params, 1);
        // 用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);

        // 品牌
        $brands = $this->common_model->getBrands($columnId);
        // 查询条件
        $selectItem = array('iSeason', 'sVersion', 'sClass');

        $selectItems = $this->runways_model->getSelectItems($selectItem, $columnId, $params, $powers);
        // 季节
        $seasons = $selectItems['iSeason'];
        //版本
        $versions = $selectItems['sVersion'];
        //类型
        $types = $selectItems['sClass'];

        $this->assign('columnId', $columnId);
        $this->assign('paramsArr', $paramsArr);// 参数数组
        $this->assign('brands', $brands);
        $this->assign('seasons', $seasons);
        $this->assign('types', $types);
        $this->assign('versions', $versions);

        echo $this->fetch('lists/runways_select_items.html');
    }

    /**
     * 获取seo搜索引擎，标题、关键字、描述
     * @param $columnId int|string 栏目ID
     * @param $params string 参数字符串
     */
    private function getTDK($columnId, $params)
    {

        // 根据选择条件的品牌ID获取其主品牌和副线品牌的相关信息
        $paramsArr = $this->common_model->parseParams($params, 1, false);//参数数组
        $aRelateBrandsInfo = array();
        if (isset($paramsArr['bra']) && $paramsArr['bra']) {
            $this->load->model('styles_model');
            $aRelateBrandsInfo = $this->styles_model->getRelateBrandsInfoById($paramsArr['bra']);
        }

        $seoArray = $this->runways_model->getSeoArray($columnId, $params);

        $this->assign('aRelateBrandsInfo', $aRelateBrandsInfo);
        $this->assign('title', $seoArray['title']);
        $this->assign('keywords', $seoArray['keywords']);
        $this->assign('description', $seoArray['description']);
    }

}

