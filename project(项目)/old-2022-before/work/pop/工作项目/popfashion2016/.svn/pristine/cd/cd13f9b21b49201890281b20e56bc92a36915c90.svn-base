<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Brands extends POP_Controller
{

    /**
     * Class constructor
     * @return  void
     */
    private $sAccountId;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'collect_model', 'brands_model']);
        $this->load->helper('cookie');
        $this->assign('columnPid', 5);
        $this->sAccountId = getUserId();
    }


    public function index($params = '')
    {
        //地区 reg  性别  gen(gcen童装) 行业 ind  市场定位 bpos  年龄层 age
        $this->benchmark->mark('action');
        $columnId = 5;
        $AgeLevelForChild = [395, 394, 393, 11302];//童装年龄层
        $AgeLevelForAdult = [392, 391, 390];      //男女装年龄层
        $param = $this->common_model->parseParams($params, 1, false);

        $GenderChecked['id'] = $IndustryChecked['id'] = 0;
        $GenderChecked['val'] = $IndustryChecked['val'] = '全部';
        //性别
        $gender = $this->common_model->getGenderByRequest($params);
        $sGender = GetCategory::getGender();
        foreach ($sGender as $key => $val) {
            unset($sGender[$key]);
            $tmp = $params;
            if (in_array($param['age'], $AgeLevelForAdult) && $key == 5) {
                $tmp = preg_replace("/(age_(.*?)-)|(-age_([^-]*?))$/", '', $tmp);
            } elseif (in_array($param['age'], $AgeLevelForChild) && in_array($key, [1, 2])) {
                $tmp = preg_replace("/(age_(.*?)-)|(-age_([^-]*?))$/", '', $tmp);
            }
            if (!in_array($key, [3, 4, $gender])) {
                $sGender[$key]['name'] = $val;
                $sGender[$key]['link'] = $this->common_model->getLink($columnId, $tmp, $type = 'gen', $value = $key, $filter = TRUE);
            }
        }
        if (!empty($gender)) {
            $GenderChecked['id'] = $gender;
            $GenderChecked['val'] = GetCategory::getOtherFromIds($gender, ['sName']);

            $all[0]['name'] = '全部';
            $tmp = preg_replace("/(gen_(.*?)-)|(-?gen_([^-]*?))$/", '', $params);
            $tmp = preg_replace("/(age_(.*?)-)|(-?age_([^-]*?))$/", '', $tmp);
            $all[0]['link'] = empty($tmp) ? '' : $tmp . '/';
            $sGender = $all + $sGender;
        }
        //行业
        $sIndustry = GetCategory::getTrade();
        $industry = $this->common_model->getIndustryByRequest($params);
        foreach ($sIndustry as $key => $val) {
            unset($sIndustry[$key]);
            if (!in_array($key, [12, 159, $industry])) {
                $sIndustry[$key]['name'] = $val;
                $sIndustry[$key]['link'] = $this->common_model->getLink($columnId, $params, $type = 'ind', $value = $key, $filter = TRUE);
            }
        }

        if (!empty($industry)) {
            $IndustryChecked['id'] = $industry;
            $IndustryChecked['val'] = GetCategory::getOtherFromIds($industry, ['sName']);

            $all[0]['name'] = '全部';
            $all[0]['link'] = preg_replace("/(ind_(.*?)-)|(-?ind_([^-]*?))$/", '', $params) . (count($param) > 1 ? '/' : '');
            $sIndustry = $all + $sIndustry;
        }
        //获取地区条件
        $region = $this->brands_model->getBrandHotRegion();
        foreach ($region as $key => $val) {
            $region[$key]['link'] = '/brands/' . $this->common_model->getLink($columnId, $params, $type = 'reg', $value = $key, $filter = TRUE);
            if (!empty($val['child'])) {
                foreach ($val['child'] as $k => $v) {
                    unset($region[$key]['child'][$k]);
                    $region[$key]['child'][$k]['name'] = $v;
                    $region[$key]['child'][$k]['link'] = '/brands/' . $this->common_model->getLink($columnId, $params, $type = 'reg', $value = $k, $filter = TRUE);
                }
            }
        }
        $regionAll[0]['name'] = '全部';
        $regionAll[0]['link'] = '/brands/' . (empty($param['reg']) ? $params : preg_replace("/(reg_(.*?)-)|(-?reg_([^-]*?))$/", '', $params) . (count($param) > 1 ? '/' : ''));
        $region = $regionAll + $region;
        //年龄层
        $sAgeLevel = GetCategory::getStyle(array(389), true);
        $sAgeLevel = $sAgeLevel[389]['attrs'];
        foreach ($sAgeLevel as $key => $val) {
            $sAgeLevel[$key]['link'] = '/brands/' . $this->common_model->getLink($columnId, $params, $type = 'age', $value = $key, $filter = TRUE);
        }
        //品牌库前台性别和年龄层标签关联，默认不出现年龄层，选择男/女/童后出现对应的年轻层选项
        if (in_array($param['gen'], [1, 2]) && !empty($sAgeLevel)) {
            //男装、女装 => 年轻、成熟、轻熟
            if (!empty($sAgeLevel)) {
                foreach ($sAgeLevel as $key => $val) {
                    if (in_array($key, $AgeLevelForAdult)) {
                        $sAgeLevelTemp[$key] = $val;
                    }
                }
            }
            $sAgeLevel = $sAgeLevelTemp;
            $sAgeLevelAll[0]['sName'] = '全部';
            $sAgeLevelAll[0]['link'] = '/brands/' . (empty($param['age']) ? $params : preg_replace("/(age_(.*?)-)|(-?age_([^-]*?))$/", '', $params) . (count($param) > 1 ? '/' : ''));
            $sAgeLevel = $sAgeLevelAll + $sAgeLevel;
        } elseif ($param['gen'] == 5 && !empty($sAgeLevel)) {
            foreach ($sAgeLevel as $key => $val) {
                if (in_array($key, $AgeLevelForChild)) {
                    $sAgeLevelTemp[$key] = $val;
                }
            }
            $sAgeLevel = $sAgeLevelTemp;
            $sAgeLevelAll[0]['sName'] = '全部';
            $sAgeLevelAll[0]['link'] = '/brands/' . (empty($param['age']) ? $params : preg_replace("/(age_(.*?)-)|(-?age_([^-]*?))$/", '', $params) . (count($param) > 1 ? '/' : ''));
            $sAgeLevel = $sAgeLevelAll + $sAgeLevel;
        } else {
            //全部 默认不出现年龄层
            $sAgeLevel = [];
        }
        //市场热度
        $Brand = GetCategory::getBrand();
        $sMarketHotPosition = $Brand[168]["attrs"];
        foreach ($sMarketHotPosition as $key => $val) {
            $sMarketHotPosition[$key]['link'] = '/brands/' . $this->common_model->getLink($columnId, $params, $type = 'bpos', $value = $key, $filter = TRUE);
        }
        $sMarketHotPositionAll[0]['sName'] = '全部';
        $sMarketHotPositionAll[0]['link'] = '/brands/' . (empty($param['bpos']) ? $params : preg_replace("/(bpos_(.*?)-)|(-?bpos_([^-]*?))$/", '', $params) . (count($param) > 1 ? '/' : ''));
        $sMarketHotPosition = $sMarketHotPositionAll + $sMarketHotPosition;
        //TDK
        if (!empty($param)) {
            $tdkArr['sMarketHotPosition'] = GetCategory::getOtherFromIds($param['bpos'], ['sName']);
            $tdkArr['sAgeLevel'] = GetCategory::getOtherFromIds($param['age'], ['sName']);
            $tdkArr['sGender'] = GetCategory::getOtherFromIds($param['gen'], ['sName']);
            $tdkArr['region'] = GetCategory::getFieldFromId($param['reg']);
            $title = $tdkArr['region'] . $tdkArr['sMarketHotPosition'] . $tdkArr['sAgeLevel'] . $tdkArr['sGender'] . '服装服饰品牌-POP服装趋势网';
            $keywords = $tdkArr['region'] . $tdkArr['sMarketHotPosition'] . $tdkArr['sAgeLevel'] . $tdkArr['sGender'] . '服装服饰品牌';
            $description = 'POP趋势资讯网品牌库栏目拥有非常丰富、多样化的国际服装品牌库，包括' . $tdkArr['region'] . $tdkArr['sMarketHotPosition'] . $tdkArr['sAgeLevel'] . $tdkArr['sGender'] . '服装服饰品牌等，不仅可以提供品牌款式、风格、细节等，更将它们与品牌的图案、主题、资讯整合，为您提供有价值的服装设计资讯服务。';
        } else {
            $title = '男/女/童装国际时装_服装品牌库-POP服装趋势网';
            $keywords = '服装品牌,衣服品牌,服装品牌库';
            $description = 'POP服装趋势网品牌栏目拥有非常丰富、多样化的品牌库，不仅可以提供品牌款式、风格、细节等，更将它们与品牌的图案、主题、资讯整合，为您提供有价值的资讯服务。';
        }

        //热门品牌推荐
        $condition = [];
        if (!empty($gender)) {
            if ($gender == 5) {
                $condition['other'][] = 'aLabelIds:(3 OR 4 OR 5)';
            } else {
                $condition['other'][] = 'aLabelIds:' . intval($gender);
            }
        }
        if (!empty($industry)) {
            $condition['other'][] = 'aLabelIds:' . intval($industry);
        }
        if (!empty($param['age'])) {
            $condition['other'][] = 'aLabelIds:' . intval($param['age']);
        }
        if (!empty($param['bpos'])) {
            $condition['other'][] = 'aLabelIds:' . intval($param['bpos']);
        }
        if (!empty($param['reg'])) {
            $condition['other'][] = '(iArea:' . intval($param['reg']) . ' OR iContinent:' . intval($param['reg']) . ' OR iCountry:' . intval($param['reg']) . ' OR iRegion:' . intval($param['reg']) . ')';
        }
        $topBrands = $this->brands_model->getBrands($condition);//热门品牌推荐
        $AliasAll = range('A', 'Z');
        //顶部广告
        $topAds = $this->common_model->getAds($columnId, 1, 5);
        $this->assign('params', $params);
        $this->assign('param', $param);
        $this->assign('title', $title);
        $this->assign('keywords', $keywords);
        $this->assign('description', $description);
        $this->assign('topAds', $topAds);
        $this->assign('sGender', $sGender);
        $this->assign('sIndustry', $sIndustry);
        $this->assign('region', $region);
        $this->assign('sAgeLevel', $sAgeLevel);
        $this->assign('sMarketHotPosition', $sMarketHotPosition);
        $this->assign('GenderChecked', $GenderChecked);
        $this->assign('IndustryChecked', $IndustryChecked);
        $this->assign('AliasAll', $AliasAll);
        $this->assign('topBrands', $topBrands);
        $this->benchmark->mark('actionEnd');
        $this->display('brand.html');
    }


    //品牌详情页
    public function detail($params = '')
    {
        $this->benchmark->mark('action');
        $sAccountId = $this->sAccountId;
        $brandDescribe = '';//品牌描述
        $param = urldecode($params);
        $param = $this->common_model->parseParams($param, 1, false);
        $iBrand = intval($param['id']);    //当前品牌id
        // 根据选择条件的品牌ID获取其主品牌和副线品牌的相关信息
        $this->load->model('styles_model');
        $aRelateBrandsInfo = $this->styles_model->getRelateBrandsInfoById($iBrand);

        $c = $param['c'];  //条件

        $gender = $this->common_model->getGenderByRequest($param);
        $industry = $this->common_model->getIndustryByRequest($param);
        $isCollect = $this->collect_model->existCollectStatus($sAccountId, 'pop136', 'brand_library', $iBrand);
        $BrandName = $this->brands_model->getBrandName($iBrand, $brandDescribe);
        $brandInfo = OpPopFashionMerger::getBrandData($iBrand);
        if (empty($brandInfo['id'])) {
            //找不到内容时，跳404
            show_404();
            exit;
        }
        $bname = $brandInfo['b_name'];
        $similarBrands = $this->brands_model->similarBrands($iBrand, $gender, $industry);//相似款信息

        $listC = $this->brands_model->getList($iBrand, 2, $gender, $industry);//2=>潮流分析
        $listT = $this->brands_model->getList($iBrand, 3, $gender, $industry);//3=>T台发布
        $listK = $this->brands_model->getList($iBrand, 4, $gender, $industry);//4=>款式库
        $listL = $this->brands_model->getList($iBrand, 71, $gender, $industry);//71=>取LOOKBOOK数据
        $listO = $this->brands_model->getList($iBrand, 131, $gender, $industry);//131=>取订货会合辑数据
        $listV = $this->brands_model->getList($iBrand, 85, $gender, $industry);//85=>取店铺陈列数据
        $listG = $this->brands_model->getList($iBrand, 120, $gender, $industry);//82=>取图案素材数据
        $listX = $this->brands_model->getList($iBrand, 81, $gender, $industry);//81=>取款式细节数据

        $alias = strtoupper(substr($BrandName, 0, 1));// 根据品牌首字父判断归属【A-Z|0-9|OTHER】
        if (preg_match("/[0-9]/", $alias)) {
            $alias = '0-9';
        } else {
            $alias = 'OTHER';
        }
        $aLogonMessage = get_cookie_value();
        $iAccountType = $aLogonMessage['iAccountType'];
        $genderTxt = GetCategory::getOtherFromIds($gender, array('sName'));
        $industryTxt = GetCategory::getOtherFromIds($industry, array('sName'));

        $this->assign('gender', trim($genderTxt));//性别
        $this->assign('industry', trim($industryTxt));//行业
        $this->assign('iAccountType', $iAccountType);//用户类型
        $this->assign('brandDescribe', $brandDescribe);//品牌描述
        $this->assign('listC', $listC);
        $this->assign('listT', $listT);
        $this->assign('listK', $listK);
        $this->assign('listL', $listL);
        $this->assign('listO', $listO);
        $this->assign('listV', $listV);
        $this->assign('listG', $listG);
        $this->assign('listX', $listX);

        $brandinfo = OpPopFashionMerger::getBrandData($iBrand);
        $xjBrandname = $this->common_model->getSearchSufix($brandinfo['name']);
        $this->assign('xjBrandname', $xjBrandname);

        $this->assign('similarBrands', $similarBrands);
        $this->assign('BrandName', $BrandName);
        $this->assign('iBrand', $iBrand);
        $this->assign('c', urlencode($c));
        $this->assign('alias', $alias);
        $this->assign('isCollect', $isCollect);//是否收藏
        $this->assign('aRelateBrandsInfo', $aRelateBrandsInfo);//主品牌和副线品牌的id和名称

        //TDK
        $brandname = $bname . $BrandName;
        $title = "{$brandname}国际时装_{$brandname}品牌库-POP服装趋势网";
        $keywords = "{$brandname}服装,{$brandname}手册,{$brandname}款式";
        $description = "POP服装趋势网{$brandname}品牌栏目拥有非常丰富、多样化的精彩内容，不仅可以提供款式、风格、细节等的图片资讯，而且还将它们与品牌的图案、主题相整合，为您提供有价值的服装设计资讯服务。";

        $this->assign('title', $title);
        $this->assign('keywords', $keywords);
        $this->assign('description', $description);

        $this->benchmark->mark('actionEnd');

        if (empty($listC) && empty($listT) && empty($listK) && empty($listL) && empty($listO) && empty($listV) && empty($listG) && empty($listX)) {
            $this->display('brand_search_null.html');
        }
        else {
            $this->display('brand_deta.html');
        }
    }

}
