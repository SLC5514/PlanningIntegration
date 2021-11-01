<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 款式库-子栏目的处理
 * @author    jasonzou
 * @time    20160227    黑色的周末
 * @property-read styles_model $styles_model
 * @property-read common_model $common_model
 * @property-read member_model $member_model
 * @property-read category_model $category_model
 */
class Styles extends POP_Controller
{
    private $columnPid = 4;
    private $pageSize = 90; // 单张展示
    private $groupPageSize = 60; // 分组展示

    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'styles_model', 'member_model']);
        $this->assign('columnPid', $this->columnPid);
        //vip用户即将过期提示条
        $vipTips = $this->isVipExpireTips();
        $this->assign('vipTips', $vipTips);
    }

    // 款式库首页+全部
    public function index($params = '')
    {
        $this->common($this->columnPid, $params);
    }

    // 款式库-T台款式
    public function runways($params = '')
    {
        $this->common(50, $params);
    }

    // 款式库-订货会精选
    public function shows($params = '')
    {
        $this->common(52, $params);
    }

    // 款式库-街拍
    public function streetsnaps($params = '')
    {
        $this->common(56, $params);
    }

    // 款式库-潮流领袖
    public function trendsetters($params = '')
    {
        $this->common(57, $params);
    }

    // 款式库-全球实拍（<!-- 全球实拍分类 iDataSource 2-零售市场 1-批发市场 3-展会图库 -->）
    public function retail($params = '')
    {
        $this->common(54, $params);
    }

    // 款式库-设计师品牌
    public function designerbrand($params = '')
    {
        $this->common(122, $params);
    }

    // 款式库-名牌精选（原：品牌在线）
    public function online($params = '')
    {
        $this->common(55, $params);
    }

    // 款式库-款式流行
    public function popular($params = '')
    {
        $this->common(123, $params);
    }

    private function init()
    {
        // 头部广告
        $topAds = $this->common_model->getAds($this->columnPid, 1);
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

        // 获取js时间戳
        $regionJsPath = FCPATH . 'global/js/fashion/regionFromSolr.js';
        $regionStaticTime = getFileModifyTime($regionJsPath, 'region');    //地区时间戳
        $brandJsPath = FCPATH . 'global/js/fashion/Column4_Brand.js';
        $brandStaticTime = getFileModifyTime($brandJsPath, 'brand');    //品牌时间戳

        $this->assign('regionStaticTime', $regionStaticTime);
        $this->assign('brandStaticTime', $brandStaticTime);
    }

    private function common($columnId, $params)
    {
        $this->benchmark->mark('common');
        $this->init();
        //1、参数处理
        $params = $this->common_model->restoreParams($params);
        $paramsArr = $this->common_model->parseParams($params, 1); //1代表把参数转化成数组

        //2、判断用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $userType = $powers['userType'];
        $sortRandom = $powers['sort'];
        $rootUrl = $this->common_model->columnRootPath($columnId);
        $search = $powers['P_Search'] ? $this->common_model->getSearchSufix() : '';
        $links = ['url' => $rootUrl, 'param' => $params, 'sortRandom' => $sortRandom, 'urlprefix' => '/details/style/', 'search' => $search];

        //3、子栏目列表
        $columns = $this->common_model->getColumns($this->columnPid, $params);
        list ($columns1, $columns2) = $this->getColumns($columnId, $params);
        $this->assign('columns1', $columns1);
        $this->assign('columns2', $columns2);

        // 4、ajax获取列表
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $this->styles_model->ajaxList($params, $columnId, $powers);
            return;
        }
        // 判断是否要显示水印或遮罩
        isShowWatermark($paramsArr, ['isStyle' => true]);

        $group = $this->styles_model->getGroup($columnId, $params); // 获取展示形式: 成册/单张

        $this->assign('group', $group);
        // $page = $this->common_model->getPage($paramsArr); // 当前页
        $page = $this->input->get_post('page') ? intval($this->input->get_post('page')) : 1;// 当前页
        $this->assign('page', $page);
        $this->assign('pageSize', $group ? $this->groupPageSize : $this->pageSize);
        // 生成成册和单张链接
        $this->genGroupLink($columnId, $paramsArr);

        //5、其他
        $time_text = $this->common_model->getTimeText($params);//获取时间范围
        $this->getTDK($columnId, $params);//seo搜索引擎，标题、关键字、描述
        $presentation = $this->styles_model->getColumnsPresentation($columnId);//栏目介绍
        $clearAll = $this->common_model->getClearLink($columnId, $params); // 清除全部
        // 底部广告
        $bottomAds = $this->common_model->getAdsBottom($columnId, $this->columnPid);
        $keys = $this->common_model->getHotKeyWords($columnId, $params);   //热门推荐关键字
        $tips = $this->common_model->getTips($columnId, $params);           // 获取您已选择内容
        if (isset($tips['key']) && $tips['key']['value'] == '韩国') {
            $tips['key']['value'] = '韩版';
        }

        list($totalCount, $list, $link) = $this->getReportRec($columnId, $params, $powers);

        $this->assign('iReportCount', $totalCount);
        $this->assign('aReportList', $list);
        $this->assign('sReportLink', $link);

        // 给前端埋点用
        $sColumn = $columns[$columnId]['sName'];
        $iInd = max(intval($paramsArr['ind']), intval($this->input->cookie('industry')));
        $sInd = trim(GetCategory::getOtherFromIds($iInd, ['sName']));
        $sInd = $sInd ? $sInd : '全部';
        $iGen = max(intval($paramsArr['gen']), intval($this->input->cookie('gender')));
        $sGen = trim(GetCategory::getOtherFromIds($iGen, ['sName']));
        $sGen = $sGen ? $sGen : '全部';
        $this->assign('sGender', $sGen);
        $this->assign('sIndustry', $sInd);
        $this->assign('sColumn', $sColumn);

        // 获取展示形式: 成册/单张
        $isGroup = !!$this->styles_model->getGroup($columnId, $params);
        $this->assign('isGroup', $isGroup);

        //详情反扒参数
        $this->load->model('Secret_model');
        list($symmetricKey, $timeStamp) = $this->Secret_model->init_data();
        $token = $this->Secret_model->get_token();
        $this->assign('token', $token);//token(uid_popuid_secret)
        $this->assign('sign', $symmetricKey);//AES_KEY
        $this->assign('timeStamp', $timeStamp);//生成AES_KEY的时间戳

        //6、页面渲染
        $this->assign('time_text', $time_text);
        $this->assign('presentation', $presentation);
        $this->assign('columnId', $columnId);

        if($columnId==54){
            $_columnId = !empty($paramsArr['ds'])?($columnId.'_'.$paramsArr['ds']):($columnId.'_2');
        }else{
            $_columnId = $columnId;
        }
        $this->assign('_columnId', $_columnId);

        $this->assign('columnPid', $this->columnPid);
        $this->assign('tips', $tips);
        $this->assign('clearAll', $clearAll);
        $this->assign('columns', $columns);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('userType', $userType);
        $this->assign('bottomAds', $bottomAds);
        $this->assign('keys', $keys);
        $this->assign('rootUrl', $rootUrl);
        $this->assign('link', $links);
        $this->display('lists/styles_list.html');
        $this->benchmark->mark('commonEnd');
    }

    //商拍页面
    public function business()
    {
        $this->display("business_photo.html");
    }

    public function addBusiness()
    {
        $data = [];
        $data['area'] = $this->input->post('businessArea', TRUE);
        $data['brand'] = $this->input->post('brandName', TRUE);
        $data['sex'] = $this->input->post('category', TRUE);
        $telephone = $this->input->post('telephone', TRUE);
        if (!empty($telephone) && (preg_match('/^1\d{10}$/', $telephone) || preg_match('/^(\d{3,4}\-)?[1-9]\d{6,7}$/', $telephone))) {
            $data['cellphone'] = $telephone;
        } else {
            echo '请正确填写联系电话';
            die;
        }

        $data['createtime'] = date('Y-m-d H:i:s', time());
        $data['status'] = 0;

        $aUserInfo = get_cookie_value();
        if ($aUserInfo['iAccountType'] == 2) {
            $data['user_id'] = $aUserInfo['sChildID'];
            $data['user_account'] = $aUserInfo['sChildAccount'];
        } else {
            $data['user_id'] = $aUserInfo['id'];
            $data['user_account'] = $aUserInfo['account'];
        }
        $data['sAccountType'] = $aUserInfo['iAccountType'];

        $result = $this->styles_model->saveBusiness($data);
        if ($result) {
            echo '恭喜您，提交成功';
            die;
        } else {
            echo '提交失败';
            die;
        }
    }

    /**
     * 异步色彩分析和单品指南
     */
    public function getPieData()
    {
        $columnId = $this->input->post('col');
        $params = $this->input->post('params');
        $keywords = $this->input->post('key');
        // 用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);

        $data = $this->styles_model->pieData($params, $columnId, $powers);
        echo json_encode($data);
    }

    // 条件栏部分
    public function filterConditions()
    {
        $this->benchmark->mark('filterConditions');
        $columnId = $this->input->post('col');
        $params = $this->input->post('params');
        $paramsArr = $this->common_model->parseParams($params, 1);
        // 用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $conditions = $this->styles_model->getConditions($params, $columnId, $powers);
        // 只有在选择童装时出现此性别选项
        $gender = $this->common_model->getGenderByRequest($params);
        if (in_array($gender, [3, 4, 5])) {
            // 男女童
            $sGender = $this->common_model->genderSpecialSelect($columnId, $params);
        }
        // 单品 默认点击后单张
        $_params = $this->styles_model->dealParamsSingle($params);
        $categorys = $this->styles_model->getCategorys($columnId, $_params, $powers);
        // 品牌
        $brands = array_merge(['HOT'], range('A', 'Z'), ['0-9', 'OTHER']);
        //查询条件  已删除 'sColorMerge','sWearing', 'sCharacterStyle'

        if ($gender == 5) {
            // $selectItem = ['iSeason', 'sMarketHotPosition'];// ,'sAgeLayer', 'sAssortColor', 'sManner', 'sShape', 'sSpecifics', 'sTechnologys', 'sPattern', 'sFabric', 'sAccessory' 风格，元素暂时隐藏
            $selectItem = ['iSeason', 'sAgeLayer', 'sManner', 'sShape', 'sSpecifics', 'sTechnologys', 'sPattern', 'sFabric', 'sAccessory'];
        } else {
            //$selectItem = ['iSeason', 'sMarketHotPosition'];// ,'sAssortColor', 'sManner', 'sShape', 'sSpecifics', 'sTechnologys', 'sPattern', 'sFabric', 'sAccessory' 风格，元素暂时隐藏
            $selectItem = ['iSeason', 'sManner', 'sShape', 'sSpecifics', 'sTechnologys', 'sPattern', 'sFabric', 'sAccessory'];
        }

        // 明星/ins 筛选项
        if ($columnId == 57) {
            $iStarInsData =  $this->styles_model->getStarInsItems($columnId, $params, $powers);
            $this->assign('iStarInsData', $iStarInsData);
        }

        $region = FALSE;
        switch ($columnId) {
            //T台款式-秀场提炼
            case '50':
                $selectItem[] = 'sFashionWeek';
                $selectItems = $this->styles_model->getSelectItems($selectItem, $columnId, $params, $powers);
                $sFashionWeek = $selectItems['sFashionWeek'];
                $region = TRUE;
                $this->assign('sFashionWeek', $sFashionWeek); // 时装周专题

                $colorRes = $this->styles_model->getColorAnalysisData($conditions, $columnId);
                $colorRes = json_decode($colorRes, true);
                // 色系 默认点击后单张
                $_params = $this->styles_model->dealParamsSingle($params);
                foreach ($colorRes as $color) {
                    $sAssortColor[$color['id']] = [
                        'sName' => $color['name'],
                        'link' => $this->common_model->getLink($columnId, $_params, 'aco', $color['id'], TRUE, 'anchor'),
                        'sAlias' => $color['itemStyle']['normal']['color'],
                    ];
                }
                $selectItems['sAssortColor'] = $sAssortColor;
                break;
            case '54':
                if ($paramsArr['ds'] == 1) {        //批发市场
                    $region = TRUE;
                    $selectItem[] = 'sMarketType';
                } elseif ($paramsArr['ds'] == 3) {    //展会图库
                    $selectItem[] = 'sExhibitionName';
                }
                $selectItems = $this->styles_model->getSelectItems($selectItem, $columnId, $params, $powers);
                if ($paramsArr['ds'] == 1) {
                    $this->assign('sMarketType', $selectItems['sMarketType']);         // 市场类型
                } elseif ($paramsArr['ds'] == 3) {    //展会图库
                    $this->assign('sExhibitionName', $selectItems['sExhibitionName']); // 展会名称
                }
            //街拍
            case '56':
                $selectItem[] = 'sStreetBeatType';
                $selectItems = $this->styles_model->getSelectItems($selectItem, $columnId, $params, $powers);
                $sStreetBeatType = $selectItems['sStreetBeatType'];
                $region = TRUE;
                $this->assign('sStreetBeatType', $sStreetBeatType); // 街拍类型
                break;
            // 明星/ins
            case '57':
                $selectItem[] = 'sStarLabel';
                $selectItems = $this->styles_model->getSelectItems($selectItem, $columnId, $params, $powers);
                $sStarLabel = $selectItems['sStarLabel'];
                $region = TRUE;
                $star = array_merge(range('A', 'Z'), ['OTHER']);
                $this->assign('sStarLabel', $sStarLabel); // 明星/达人 => 人名
                $this->assign('star', $star);
                break;
            default:
                $selectItems = $this->styles_model->getSelectItems($selectItem, $columnId, $params, $powers);
                break;
        }
        //获取热度品牌ID
        $keys = $this->common_model->getKeyword('', $powers);
        $HotBrandIds = $this->category_model->getHotBrandIds($columnId, $conditions, $keys);
        $HotBrandIdsStr = json_encode($HotBrandIds);
        // 季节
        $iSeason = $selectItems['iSeason'];
        //获取色系-拼色（主色）
        $sAssortColor = $selectItems['sAssortColor'];
        // 年龄层
        $sAgeLayer = $selectItems['sAgeLayer'];
        // 品牌定位
        $sMarketHotPosition = $selectItems['sMarketHotPosition'];

        $region = TRUE;//前台增加地区检索，去除地域风格 2016-10-14
        if ($paramsArr['ds'] == 3 || in_array($columnId, [52, 56, 57])) {
            $sManner = $sShape = $sSpecifics = $sTechnologys = $sPattern = $sFabric = $sAccessory = [];
        } else {
            // 风格
            $sManner = $selectItems['sManner'];
            // 廓形
            $sShape = $selectItems['sShape'];
            // 细节
            $sSpecifics = $selectItems['sSpecifics'];
            // 工艺
            $sTechnologys = $selectItems['sTechnologys'];
            // 图案
            $sPattern = $selectItems['sPattern'];
            // 面料
            $sFabric = $selectItems['sFabric'];
            // 辅料
            $sAccessory = $selectItems['sAccessory'];
        }
        // 热门元素
        $options = $this->getLabelsMap($columnId, $params);

        $this->assign('columnId', $columnId);
        $this->assign('paramsArr', $paramsArr);// 参数数组
        $this->assign('bpos', $paramsArr['bpos'] ? $paramsArr['bpos'] : '');// 品牌定位
        $this->assign('HotBrandIdsStr', $HotBrandIdsStr);// solr中存在的热门品牌
        $this->assign('labels', $options);
        $this->assign('sGender', $sGender);// 男童、女童
        $this->assign('categorys', $categorys); // 单品
        $this->assign('brands', $brands); // 品牌
        $this->assign('iSeason', $iSeason); // 季节
        $this->assign('sAssortColor', $sAssortColor);// 拼色（主色）
        $this->assign('sAgeLayer', $sAgeLayer);// 年龄层
        $this->assign('sMarketHotPosition', $sMarketHotPosition);// 品牌定位
        $this->assign('region', $region);// 地区
        $this->assign('sManner', $sManner);// 风格
        $this->assign('sShape', $sShape);// 廓形
        $this->assign('sSpecifics', $sSpecifics);// 细节
        $this->assign('sTechnologys', $sTechnologys);// 工艺
        $this->assign('sPattern', $sPattern);// 图案
        $this->assign('sFabric', $sFabric);// 面料
        $this->assign('sAccessory', $sAccessory);// 辅料

        echo $this->fetch('lists/styles_select_items.html');
        $this->benchmark->mark('filterConditionsEnd');
    }


    /**********************************************以下为私有方法***********************************************/

    /**
     * 获取子栏目列表
     * @param int|string $columnId 当前栏目id
     * @param string $params
     * @return array array(columns1, columns2)
     */
    private function getColumns($columnId, $params)
    {
        $this->benchmark->mark('getStylesColumns');
        $columns = $this->common_model->getColumns($this->columnPid, $params);
        $paramsArr = $this->common_model->parseParams($params, 1);

        $columns1 = $columns2 = [];
        foreach ($columns as $colId => $column) {
            if ($colId == 4) {
                $column['sName'] = '全部款式';
            }
            $column['selected'] = $colId == $columnId;
            $columns1[$colId] = $column;

            // 全球实拍
            if ($colId == 54) {
                $ds = isset($paramsArr['ds']) ? $paramsArr['ds'] : '';

                $column['sName'] = '全球实拍/零售市场';
                $column['selected'] = $colId == $columnId && ($ds != 1 && $ds != 3);
                $params = $this->common_model->getColParams('54_2', $paramsArr);
                $column['sLink'] = $this->common_model->getLink($colId, $params, 'ds', 2, TRUE);
                $columns2['54_2'] = $column;

                $column['sName'] = '全球实拍/批发市场';
                $column['selected'] = $colId == $columnId && $ds == 1;
                $params = $this->common_model->getColParams('54_1', $paramsArr);
                $column['sLink'] = $this->common_model->getLink($colId, $params, 'ds', 1, TRUE);
                $columns2['54_1'] = $column;

                $column['sName'] = '全球实拍/展会图库';
                $column['selected'] = $colId == $columnId && $ds == 3;
                $params = $this->common_model->getColParams('54_3', $paramsArr);
                $column['sLink'] = $this->common_model->getLink($colId, $params, 'ds', 3, TRUE);
                $columns2['54_3'] = $column;
            } else {
                $columns2[$colId] = $column;
            }
        }
        $this->benchmark->mark('getStylesColumnsEnd');
        return [$columns1, $columns2];
    }

    /**
     * 生成成册和单张链接
     * @param int|string $columnId
     * @param array $paramArr
     * @return array  array(成册链接, 单张链接)
     */
    public function genGroupLink($columnId, $paramArr)
    {
        $this->benchmark->mark('genGroupLink');
        $paramArr['dis'] = 1;
        unset($paramArr['page']);
        $params = $this->common_model->parseParams($paramArr, 2);
        $sLinkSingle = $this->common_model->getLink($columnId, $params, $type = '', $value = '', $filter = TRUE, $anchor = 'anchor');
        $params = str_replace('dis_1', 'dis_2', $params);
        $sLinkGroup = $this->common_model->getLink($columnId, $params, $type = '', $value = '', $filter = TRUE, $anchor = 'anchor');
        $this->assign('sLinkSingle', $sLinkSingle);
        $this->assign('sLinkGroup', $sLinkGroup);
        $this->benchmark->mark('genGroupLinkEnd');
        return [$sLinkGroup, $sLinkSingle];
    }

    /**
     * 获取seo搜索引擎，标题、关键字、描述
     * @param int|string $columnId 栏目ID
     * @param string $params 参数字符串
     */
    private function getTDK($columnId, $params)
    {
        $seoArray = $this->styles_model->getSeoArray($columnId, $params);
        $this->assign('title', $seoArray['title']);
        $this->assign('keywords', $seoArray['keywords']);
        $this->assign('description', $seoArray['description']);
    }

    // 参考F:\Project\market\MOSTREND-POP\每日需求更新\2016-08-11\老网站标签.xlsx
    private function getLabelsMap($columnId, $params)
    {
        $options = [];
        $paramsArr = $this->common_model->parseParams($params);
        if (is_array($paramsArr) && count($paramsArr)) {
            $cond = '';
            if ($paramsArr['ind']) {
                $cond .= $paramsArr['ind'];
            }
            if ($paramsArr['gen']) {
                $cond .= $paramsArr['gen'];
            }
            if ($cond) {
                $labelsMap = $this->styles_model->getLabels();
                foreach ($labelsMap as $_k => $_v) {
                    $tmp = [];
                    if (in_array($cond, $_v['cond']) || in_array(strrev($cond), $_v['cond'])) {
                        $tmp['name'] = $_v['name'];
                        $tmp['link'] = $this->common_model->getLink($columnId, $params, 'hot', $_v['id'], TRUE, 'anchor');
                        $options[] = $tmp;
                    }
                }
            }
        }
        if (empty($options)) {
            $cookies = $this->input->cookie();
            if ($cookies) {
                $cond = '';
                if ($cookies['industry']) {
                    $cond .= $cookies['industry'];
                }
                if ($cookies['gender']) {
                    $cond .= $cookies['gender'];
                }
                if ($cond) {
                    $labelsMap = $this->styles_model->getLabels();
                    foreach ($labelsMap as $_k => $_v) {
                        $tmp = [];
                        if (in_array($cond, $_v['cond']) || in_array(strrev($cond), $_v['cond'])) {
                            $tmp['name'] = $_v['name'];
                            $tmp['link'] = $this->common_model->getLink($columnId, $params, 'hot', $_v['id'], TRUE, 'anchor');
                            $options[] = $tmp;
                        }
                    }
                }
            }
        }
        return $options;
    }

    /**
     * 获取推荐到款式的报告
     *
     * @param int|string $columnId
     * @param string $params
     * @param            $powers
     *
     * @return array  [totalCount, lists]
     */
    private function getReportRec($columnId, $params, $powers)
    {
        $this->benchmark->mark('getReportRec');
        $paramsArr = $this->common_model->parseParams($params, 1);

        // VIP用户中，未购买趋势栏目的用户，不显示推荐条 款式--款式流行(123)不推荐报告数据
        if ($powers['userType'] == 'vip' && $columnId != 123) {
            $col_ids = GetCategory::getColumns(array(1, 2));

            $report_col_ids = array_merge(
            // array_column($col_ids[1]['col'], 'iColumnId'), // 趋势解读
                array_column($col_ids[2]['col'], 'iColumnId'),// 流行分析（趋势栏目推荐的是流行分析的报告）
                array(2)
            );

            $aUserInfo = get_cookie_value();
            $power_info = $this->member_model->checkNewVip($aUserInfo['id']);

            // 合并用户的栏目权限
            $power_info = array_filter($power_info, function ($item) {
                return $item['iType'] == 3;
            });

            $sColumn = implode(',', array_column($power_info, 'sColumn'));
            $power_cols = array_unique(explode(',', $sColumn));

            // 报告栏目的VIP用户 取交集（用户的vip权限与栏目的vip权限取交集）
            $aTrendSets = array_intersect($power_cols, $report_col_ids);
            if (!$aTrendSets) {
                $totalCount = 0;
                $list = [];
                $link = '';

                return [$totalCount, $list, $link];
            }
        } elseif ($columnId == 123) {// 款式--款式流行(123)不推荐报告数据
            $totalCount = 0;
            $list = [];
            $link = '';

            return [$totalCount, $list, $link];
        }

        $_columnId = $columnId;
        if ($columnId == 54) {
            $ds = $paramsArr['ds'] ? $paramsArr['ds'] : 2;
            $_columnId = $columnId . '_' . $ds;
        }
        // 根据 行业、性别、季节、品牌、单品筛选
        $allowParams = ['ind', 'gen', 'sea', 'bra', 'cat'];
        if ($columnId == 4) {
            // 全部款式时还根据 男女童 筛选
            $allowParams[] = 'gcen';
        }
        foreach ($paramsArr as $key => $val) {
            if (!in_array($key, $allowParams)) {
                unset($paramsArr[$key]);
            }
        }
        $params = $this->common_model->parseParams($paramsArr, 2);
        $params = $params ? $params . '-styleRec_1' : 'styleRec_1'; // 取推荐到款式的报告
        $this->load->model('analysis_model');
        // iDataSource 54_2-零售市场 54_1-批发市场 54_3-展会图库
        $ids = [4 => 2, 50 => 30, 52 => 32, 122 => 34, 55 => 35, '54_1' => 33, '54_2' => 33, '54_3' => 31, 57 => 38, 56 => 38];
        // 转换成流行分析栏目id
        $columnId = isset($ids[$_columnId]) ? $ids[$_columnId] : 2;
        $list = [];
        // 最多取5条
        $totalCount = $this->analysis_model->getAnalysisLists($params, $columnId, $list, $offset = 0, 5);
        $link = $this->common_model->getLink($columnId, $params);
        foreach ($list as $key => $item) {
            $list[$key]['sColumn'] = GetCategory::getOtherFromColId($item['columnId'], 'sName');
            $list[$key]['sLink'] = $link . "t_{$item['tableName']}-id_{$item['list']['id']}-col_{$item['columnId']}/";
        }

        $this->benchmark->mark('getReportRecEnd');
        return [$totalCount, $list, $link];
    }

}
