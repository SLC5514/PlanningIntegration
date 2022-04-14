<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Brands_model
 * @property-read common_model $common_model
 */
class Brands_model extends POP_Model
{
    public $refresh;

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('cookie');
        $this->load->model(['details_model', 'statistics_model', 'common_model', 'report_model']);
        $this->refresh = $this->input->get('refresh', true);
    }

    /**
     * 获取 2=>潮流分析、3=>T台发布、4=>款式库在该品牌的数据
     * @param $iBrand int 品牌id
     * @param $iColumnId int 栏目ID(只适用于栏目2,3,4);
     * @param string $gender
     * @param string $industry
     * @return array
     */
    public function getList($iBrand, $iColumnId, $gender = '', $industry = '')
    {
        $limit = [2 => 5, 3 => 3, 4 => 16, 71 => 7, 131 => 7, 85 => 5, 120 => 6, 81 => 8];//各栏目所取条数

        if ($iColumnId == 71 || $iColumnId == 131) { // LOOKBOOK || 订货会合辑
            $condition['iDisplay'] = 1;
        }
        $condition['iBrand'] = $iBrand;
        $condition['iColumnId'] = $iColumnId;
        if (!empty($gender)) {
            $this->common_model->childGender($gender, $condition);
        }
        if (!empty($industry)) {
            $condition['other'][] = '(aLabelIds:' . $industry . ')';
        }
        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        $return = $res = [];
        $num = POPSearch::wrapQueryPopFashionMerger('', $condition, $res, 0, $limit[$iColumnId], $arSort);
        $collectStatusList = $this->getListDataCollectStatus($res);

        if ($num) {
            foreach ($res as $_val) {
                $id = $_val['pri_id'];
                $realTableName = $_val['tablename'];

                $row = OpPopFashionMerger::getProductData($id, $realTableName);
                $v = $row[$id];

                $_createTime = '';
                $returnTemp = [];
                $returnTemp['id'] = $id;
                $returnTemp['tablename'] = getProductTableName($realTableName); // 假表名
                $returnTemp['col'] = $_col = end($_val['iColumnId']); // 栏目id
                $returnTemp['title'] = isset($v['title']) && !empty($v['title']) ? $v['title'] : $v['sTitle'];

                switch ($iColumnId) {
                    case 2:
                        $img = getImagePath($realTableName, $v);
                        $returnTemp['sSmallPath'] = $img['smallPath'];//小图
                        $returnTemp['description'] = '';
                        if (!$returnTemp['description']) {
                            $returnTemp['description'] = trim(strip_tags($v['description'])); //摘要
                        }
                        if (!$returnTemp['description']) {
                            $returnTemp['description'] = trim(strip_tags($v['sDesc']));
                        }
                        if (!$returnTemp['description']) {
                            $returnTemp['description'] = trim(strip_tags($v['abstract'])); //摘要
                        }

                        $returnTemp['labels'] = $this->report_model->getLabelInfo($realTableName, $id, $_col, '', 'list', true);
                        break;
                    case 3:
                        $returnTemp['nme'] = $v['nme']; //T台名
                        // 设计师（品牌）
                        $returnTemp['designer'] = '';
                        if ($v['brand_tid']) {
                            //品牌s
                            $returnTemp['designer'] = GetCategory::getBrandOtherFormId($v['brand_tid']);
                        }
                        // 季节
                        $returnTemp['for_date_text'] = '';
                        if ($v['iSeason']) {
                            $returnTemp['for_date_text'] = GetCategory::getOtherFromIds($v['iSeason'], ['sName']);
                        }
                        // 地区名称
                        $returnTemp['region_text'] = '';
                        if ($v['iRegion']) {
                            $returnTemp['region_text'] = GetCategory::getFieldFromId($v['iRegion']);
                        }
                        $img = getImagePath($realTableName, $v);
                        $returnTemp['cover'] = $img['cover'];

                        // T台更新时间
                        $times = [];
                        if (isset($v['create_time_special']) && $v['create_time_special']) {
                            $times[] = $v['create_time_special'];
                        }
                        if (isset($v['create_time_focus']) && $v['create_time_focus']) {
                            $times[] = $v['create_time_focus'];
                        }
                        if (isset($v['create_time']) && $v['create_time']) {
                            $times[] = $v['create_time'];
                        }
                        if (isset($v['create_time_video']) && $v['create_time_video']) {
                            $times[] = $v['create_time_video'];
                        }
                        $_createTime = min($times);

                        $returnTemp['viewCount'] = $this->statistics_model->getViews($realTableName, $v['id'], $v); //浏览量
                        break;
                    case 4:
                        $img = getImagePath($realTableName, $v);
                        $returnTemp['cover'] = $returnTemp['sSmallPath'] = $img['smallPath'];//小图
                        // 标题
                        $_info = $this->details_model->getPicInfo($v['id'], $realTableName, [], $iColumnId);
                        $returnTemp['title'] = $_info['title'];
                        $returnTemp['labels'] = $this->details_model->getLabelInfo($realTableName, $id, '', $_col, 'list', true);
                        break;
                    case 71: // lookbook
                    case 131:// 订货会合辑
                        $img = getImagePath($realTableName, $v);
                        $returnTemp['title'] = stripcslashes($v['name_text']);
                        $returnTemp['sSmallPath'] = $img['smallPath'];//小图
                        $returnTemp['iPreviewMode'] = $v['iPreviewMode'];
                        break;
                    case 85:
                    case 120:
                    case 81:
                        $img = getImagePath($realTableName, $v);
                        $returnTemp['sSmallPath'] = $img['smallPath'];//小图
                        $returnTemp['labels'] = $this->details_model->getLabelInfo($realTableName, $id, '', $_col, 'list');
                        break;
                }

                // 浏览量
                $returnTemp['viewCount'] = $this->statistics_model->getViews($realTableName, $id, $v);

                // 创建时间
                if (!$_createTime) {
                    $_createTime = $v['create_time'];
                }
                if (!$_createTime) {
                    $_createTime = $v['addtime'];
                }
                if (!$_createTime) {
                    $_createTime = $v['dCreateTime'];
                }
                $returnTemp['update_time'] = $_createTime ? date('Y-m-d', strtotime($_createTime)) : '';
                $returnTemp['iCollectStatus'] = array_search($_val['pop_id'], $collectStatusList) === false ? 0 : 1; // 是否收藏
                $return[] = $returnTemp;
            }
        }
        return $return;
    }


    private function _getAplusBrands($p, $limit)
    {

        $__offset = ($p - 1) * $limit;

        //去A+最新更新的数据资料
        $_condition = [
            'iColumnId' => 5,
            'iWeight' => '{1000 TO *}'
        ];
        $_brands = [];
        $iTotal = POPSearch::wrapQueryPopFashionMerger('', $_condition, $_brands, $__offset, $limit, ['dStyleReportUpdateTime' => 'DESC'], ['fl' => 'pri_id']);

        if ($iTotal == 0) {
            return;
        }
        // A+类品牌id
        $aPlusBrandIds = [];

        foreach ($_brands as $_v) {
            array_push($aPlusBrandIds, $_v['pri_id']);
        }
        $this->load->model('brands_model');
        $_brandData = $this->getbranddata($aPlusBrandIds[count($aPlusBrandIds) - 1]);
        $solrTime = strtotime($_brandData['dStyleReportUpdateTime']);
        return [$aPlusBrandIds, $solrTime];
    }


    /**
     * 获取大牌推荐
     * @param $condition
     * @param int $offset
     * @param int $limit
     * @param string[] $arSort
     * @return array 返回品牌描述
     */
    public function getBrands($condition, $offset = 0, $limit = 33, $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'])
    {
        $this->benchmark->mark('actionBrands');

        $condition = $condition ? $condition : array();

        $condition['other'][] = 'iColumnId:(1 OR 2 OR 4 OR 8)';

        /**
         * 推荐规则:按权重和条件取前33个品牌
         * 只取A+类品牌(等于1000为A类 大于1000为A+品牌)
         */
        //有效数据 临时存储
        $__tmpValidArray = array();

        //临时存储仓库 数组
        $__tmpArray = array();

        //临时数据记录数存储仓库 数组
        $__tmpNumberArray = array();

        //最近更新资料的品牌当前页下最小的更新时间
        $__tmpLastTime = 0;

        $groups = [];
        $groups['group'] = 'true';
        $groups['group.field'] = 'sBrand';
        $groups['group.ngroups'] = 'true';
        $groups['group.limit'] = 1;

        $i = 1;
        while (true) {
            $aPlusBrandIds = $this->_getAplusBrands($i, $limit * 10);
            if (!$aPlusBrandIds[0]) {
                break;
            }
            $__tmpLastTime = $aPlusBrandIds[1];
            $condition['other']['iBrand'] = 'iBrand:(' . trim(implode(' OR ', $aPlusBrandIds[0]), ' OR ') . ')';
            $_offset = 0;
            do {
                $_res = [];
                POPSearch::wrapQueryPopFashionMerger('', $condition, $_res, $_offset, $limit * 3, $arSort, $groups);
                $_offset += $limit * 3;
                if (!$_res['sBrand'] ['groups']) {
                    break;
                }
                foreach ($_res['sBrand'] ['groups'] as $groupValue) {
                    $_groupValue = $groupValue['groupValue'];
                    $_numFound = $groupValue['doclist']['numFound'];
                    $_pri_id = $groupValue['doclist']['docs'][0]['pri_id'];
                    $_tablename = $groupValue['doclist']['docs'][0]['tablename'];
                    $__tmp = OpPopFashionMerger::getProductData($_pri_id, $_tablename);
                    switch ($_tablename) {
                        case 'fs_design':
                        case 'fs_commodity':
                        case 'fs_analysis':
                        case 'fs_inspiration':
                        case 'fs_trend':
                            $__time = strtotime($__tmp[$_pri_id]['order_time']);
                            break;
                        case 'product_brand':
                        case 'product_style_graphic':
                        case 'product_marketphoto_item':
                        case 'product_perform':
                        case 'product_streetitem':
                        case 'product_style_graphic_china':
                        case 'product_ordermeeting':
                        case 'mostrend_content':
                            $__time = strtotime($__tmp[$_pri_id]['create_time']);
                            break;
                        case 'product_tideleader':
                        case 't_trend_report':
                        case 't_inspiration_img_db':
                            $__time = strtotime($__tmp[$_pri_id]['dCreateTime']);
                            break;
                        case 'specialtopic':
                        case 'specialtopic_graphic':
                            $__time = strtotime($__tmp[$_pri_id]['addtime']);
                            break;
                    }

                    if (stripos($_groupValue, ',')) {
                        //Group 多个品牌处理
                        $_aMoreBrands = explode(',', $_groupValue);
                        foreach ($_aMoreBrands as $_mb) {
                            if ((!$__tmpArray[$_mb] || ($__tmpArray[$_mb] && $__time > $__tmpArray[$_mb])) && false === array_search($_mb, $aPlusBrandIds[0])) {
                                $__tmpArray[$_mb] = $__time;
                                $__tmpNumberArray[$_mb] = $_numFound;
                            }
                        }
                    } else {
                        if (!$__tmpArray[$_groupValue] || ($__tmpArray[$_groupValue] && $__time > $__tmpArray[$_groupValue])) {
                            $__tmpArray[$_groupValue] = $__time;
                            $__tmpNumberArray[$_groupValue] = $_numFound;
                        }
                    }
                }
                arsort($__tmpArray);

                foreach ($__tmpArray as $_B => $_T) {
                    if ($_T >= $__tmpLastTime) {
                        $__tmpValidArray[$_B] = $__tmpNumberArray[$_B];
                        unset($__tmpArray[$_B], $__tmpNumberArray[$_B]);
                    }
                    if (count($__tmpValidArray) >= $limit) {
                        unset($__tmpArray, $__tmpNumberArray);
                        break 3;
                    }
                }
            } while ($_offset < $_res['sBrand']['ngroups']);

            //防止内存溢出，次要处理方式 满1000放弃处理，讲最新的 填充到 有效数据中
            if (count($__tmpArray) > 1000) {
                $__tmpValidArray += array_slice($__tmpNumberArray, 0, $limit - count($__tmpValidArray), true);
                unset($__tmpArray, $__tmpNumberArray);
                break;
            }

            $i++;
        }

        $brandData = $this->getBrandsFromId(array_keys($__tmpValidArray));
        foreach ($brandData as &$_data) {
            $_data['count'] = $__tmpValidArray[$_data['id']];
        }
        return $brandData;
    }

    /**
     * 通过id获取其相似品牌
     * @param $id
     * @param $gender
     * @param $industry
     * @return array 返回品牌描述
     */
    public function similarBrands($id, $gender, $industry)
    {
        $ret = $this->getbranddata($id);
        $condition['iColumnId'] = 5;
        $condition['other'][] = '-pri_id:' . $id;

        //性别
        if (!empty($ret['sGender'])) {
            $sGender = implode(' OR ', explode(',', $ret['sGender']));
            $condition['other'][] = "(aLabelIds:($sGender))";
        }
        //行业
        if (!empty($ret['sIndustry'])) {
            $sIndustry = implode(' OR ', explode(',', $ret['sIndustry']));
            $condition['other'][] = "(aLabelIds:($sIndustry))";
        }
        //性格风格
        if (!empty($ret['sCharacterStyle'])) {
            $sCharacterStyle = implode(' OR ', explode(',', $ret['sCharacterStyle']));
            $condition['other'][] = "(aLabelIds:($sCharacterStyle))";
        }
        //穿着
        if (!empty($ret['sDress'])) {
            $sDress = implode(' OR ', explode(',', $ret['sDress']));
            $condition['other'][] = "(aLabelIds:($sDress))";
        }
        //年龄层
        if (!empty($ret['sAgeLevel'])) {
            $sAgeLevel = implode(' OR ', explode(',', $ret['sAgeLevel']));
            $condition['other'][] = "(aLabelIds:($sAgeLevel))";
        }
        //地域风格
        if (!empty($ret['sRegionStyle'])) {
            $sRegionStyle = implode(' OR ', explode(',', $ret['sRegionStyle']));
            $condition['other'][] = "(aLabelIds:($sRegionStyle))";
        }
        //市场热度
        if (!empty($ret['sMarketHotPosition'])) {
            $sMarketHotPosition = implode(' OR ', explode(',', $ret['sMarketHotPosition']));
            $condition['other'][] = "(aLabelIds:($sMarketHotPosition))";
        }
        //相似品牌,竞争品牌
        if (!empty($ret['sTargetBrand']) || !empty($ret['sCompeteBrand'])) {
            $BrandIds = array_merge(explode(',', $ret['sTargetBrand']), explode(',', $ret['sCompeteBrand']));
            $BrandIds = array_filter($BrandIds);
            $condition['other'][] = "(pri_id:" . implode(' OR ', $BrandIds) . ")";
        }

        $condition['iStyleCount'] = "{0 TO *}";
        $arSort = ['iWeight' => 'DESC', 'dStyleReportUpdateTime' => 'DESC'];//排序
        $resTmp = [];
        $length = $j = count($condition['other']);
        $ids = [];
        for ($i = 0; $i <= $length; $i++) {
            $total = POPSearch::wrapQueryPopFashionMerger('', $condition, $resTmp, 0, 15, $arSort);
            if (!empty($resTmp)) {
                foreach ($resTmp as $val) {
                    $ids[$val['pri_id']] = $val['pri_id'];
                }
            }
            if (count($ids) >= 10) {
                break;
            } else {
                unset($condition['other'][$j - 1]);
                $j--;
            }
        }

        return $this->getBrandsFromId($ids);
    }

    /* @todo   通过品牌品牌ID获取其他信息
     * @param $brandIds
     * @return array 返回品牌描述
     * @internal param array $ids 品牌ids
     */
    public function getBrandsFromId($brandIds)
    {
        return $this->getbranddata($brandIds);
    }

    /**
     * @param $ids
     * @return array
     */
    public function getbranddata($ids)
    {
        $data = [];
        if (empty($ids)) return $data;

        if (is_array($ids)) {
            foreach ($ids as $id) {
                array_push($data, OpPopFashionMerger::getBrandData($id, $this->refresh));
            }
        } else {
            $data = OpPopFashionMerger::getBrandData($ids, $this->refresh);
        }
        return $data;
    }

    /* @todo   获取品牌名称 和 品牌描述
     * @param  $id int 品牌id
     * @return String返回品牌英文名称
     */
    public function getBrandName($id, &$brandDescribe = '')
    {
        $id = intval($id);
        $ret = $this->getbranddata($id);

        $sRegion = GetCategory::getRegionInfo($ret['sRegion'], $field = 'ids');
        $sRegion = GetCategory::getFieldFromId($sRegion, 'sCnRegionName');//起源地
        $sMarketHotPosition = GetCategory::getOtherFromIds($ret['sMarketHotPosition'], array('sName'), 'array');//市场热度
        if (!empty($sMarketHotPosition)) {
            $sMarketHotPosition = implode('、', $sMarketHotPosition['sName']);
        }
        $sGender = GetCategory::getOtherFromIds($ret['sGender'], array('sName'), 'array');//性别 $ret['sGender']
        if (!empty($sGender["sName"])) {
            $sGenderA = implode('、', $sGender["sName"]);
            $sGenderA = str_replace('男童', '男童装', $sGenderA);
            $sGenderA = str_replace('女童', '女童装', $sGenderA);
            $sGenderB = implode('、', $sGender["sName"]);
            $sGenderB = str_replace('装', '', $sGenderB);
        } else {
            $sGenderA = $sGenderB = '';
        }
        if (!empty($sRegion) && !empty($sMarketHotPosition) && !empty($sGenderA)) {
            if (empty($sRegion[1])) {
                $sRegionStr = $sRegion[0];
            } elseif (empty($sRegion[2])) {
                $sRegionStr = $sRegion[1];
            } else {
                $sRegionStr = $sRegion[1] . $sRegion[2] . $sRegion[3];
            }
            $brandDescribe = "该品牌起源于{$sRegionStr}，定位于{$sMarketHotPosition}。主要生产{$sGenderA}，适合{$sGenderB}消费群体。";
        }

        return $ret['name'];
    }

    /**
     * 获取栏目介绍的内容
     * @param $columnId 栏目ID
     * @return string 栏目介绍
     */
    public function getColumnsPresentation($columnId)
    {
        switch ($columnId) {
            case '5':
            default:
                $columnPresentation = "提供全球4万多品牌时时产品变化和款式资料，同时提供重点地区重点品牌全维度多视角、货品结构与设计特点深入解析，关注品牌前世今生动态变化与原因分析";
                break;
        }
        return $columnPresentation;
    }

    /**
     * 获取品牌库地区条件
     */
    public function getBrandHotRegion()
    {
        //地区
        $region = GetCategory::getRegionList('0');
        //增加地区
        $aHotRegion = getCommonData('brand_data', 'aHotRegion');
        foreach ($region as $val) {
            $regionTemp["{$val['iRegionId']}"]['name'] = $val['sCnRegionName'];
            $regionTemp["{$val['iRegionId']}"]['child'] = $aHotRegion[$val['iRegionId']];
        }
        return $regionTemp;
    }
}
