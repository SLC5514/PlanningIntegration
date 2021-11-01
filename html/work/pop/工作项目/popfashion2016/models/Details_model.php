<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Details详情页 专用类
 * @property-read common_model $common_model
 */
class Details_model extends POP_Model
{
    const FABRIC_APP_HASH = 'fabric_app_hash';

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'category_model']);
    }

    public function getMarketName($source, &$iDataSource = "")
    {
        $memcahe_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . "source_fashion_category_" . $source;
        $result = $this->cache->memcached->get($memcahe_key);
        if (empty($result['cat_name'])) {
            $sql = "SELECT parent_id,cat_name FROM fashion_category WHERE id='$source'";
            $result = $this->db()->query($sql)->row_array();
            $this->cache->memcached->save($memcahe_key, $result, 28800);
        }
        // $this->db()->close();
        switch ($result['parent_id']) {
            case '3486' :
                $iDataSource = 1;
                break;
            case '3487' :
                $iDataSource = 2;
                break;
            case '3488' :
                $iDataSource = 3;
                break;
        }
        return isset($result['cat_name']) ? $result['cat_name'] : '';
    }

    //有款式图栏目 1、brand 2、marketphoto 3、style_graphic 4、styley_graphic_china

    /**
     * @todo    单张图片详情页获取图片详情
     * @name    getPicInfo
     * @param   tablename  string  真实表名
     * @param   id       inter   id
     * @param   params     array   条件
     * @param   columnid   inter   栏目id
     * @param   index      inter   图片索引
     * @return  array
     */
    public function getPicInfo($id, $tablename, $params, $columnid, $index = 0, &$_info = [], $columnPid = 0)
    {
        $this->benchmark->mark('DetailsModelGetPicInfo');
        if (empty($id) || empty($tablename)) return [];

        if (empty($_info)) {
            $_info = OpPopFashionMerger::getProductData($id, $tablename);
        }

        $info = $_info[$id];
        $tablename = strtolower($tablename);
        // 明星博主字典表
        $typeArr = $this->category_model->getAll('iStarIns');
        switch ($tablename) {
            case 'product_perform'://秀场提炼
                //季节
                $info['iSeason_text'] = GetCategory::getNewEnName(5, $info['for_date']);
                $seasonId = getCategory::getIdsFrom(5, "sOriginalName,{$info['iSeason_text']}");
                if (isset($seasonId[0])) {
                    $info['iSeason'] = $seasonId[0];
                }
                //地区
                $region_id = GetCategory::getRegionIdFromEnName($info['region']);
                $info['iRegion_text'] = GetCategory::getFieldFromId($region_id);
                $info['iRegion'] = $region_id;
                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['typ']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                //品名
                $info['iSubcategory_text'] = GetCategory::getOtherFromIds($info['iSubcategory'], ['sName']);
                //品牌
                $info['brand_name'] = GetCategory::getBrandOtherFormId($info['brand_tid'], 'en_cn_brand');
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);

                //标题
                $info['title'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'] . $info['iSubcategory_text'] . $info['brand_name'];

                // 浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);

                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';
                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);
                $type = $info['iGender'] == 3 || $info['iGender'] == 4 ? 'gcen' : 'gen';
                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                $info['detail_img'][0]['simpleName'] = $simpleName;
                $info['detail_img'][0]['id'] = $id;
                $info['detail_img'][0]['columnid'] = $columnid;

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_marketphoto_item'://商场爆款
                //季节
                $info['iSeason_text'] = GetCategory::getNewEnName(5, $info['for_date']);
                $seasonId = getCategory::getIdsFrom(5, "sOriginalName,{$info['iSeason_text']}");
                if (isset($seasonId[0])) {
                    $info['iSeason'] = $seasonId[0];
                }
                // 线下和街拍栏目对季节 特殊处理
                if ($columnid == 54 || $columnid == 56) {
                    $tempId = OpPopFashionMerger::getSimilarId($info['iSeason']);
                    $info['iSeason'] = !empty($tempId['sOwnChildId']) ? $tempId['sOwnChildId'] : $info['iSeason'];
                }
                //地区
                $region_id = GetCategory::getRegionIdFromEnName($info['region']);
                $info['iRegion_text'] = GetCategory::getFieldFromId($region_id);
                $info['iRegion'] = $region_id;
                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['typ']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                //品名
                $info['iSubcategory_text'] = GetCategory::getOtherFromIds($info['iSubcategory'], ['sName']);
                //品牌
                $info['brand_name'] = GetCategory::getBrandOtherFormId($info['brand_tid'], 'en_cn_brand');
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);

                //标题
                $info['title'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'] . $info['iSubcategory_text'] . $info['brand_name'];

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);

                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';

                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);

                //款式图
                $info['detail_img'] = $this->getMarketphotoDetailImage($tablename, $info['flag_text'], $info['dir_name'], $id, $columnid);
                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_brand'://名牌高清
                //季节
                $info['iSeason_text'] = GetCategory::getNewEnName(5, $info['for_date']);
                $seasonId = getCategory::getIdsFrom(5, "sOriginalName,{$info['iSeason_text']}");
                if (isset($seasonId[0])) {
                    $info['iSeason'] = $seasonId[0];
                }
                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['typ']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                //品名
                $info['iSubcategory_text'] = GetCategory::getOtherFromIds($info['iSubcategory'], ['sName']);
                //品牌
                $brandInfo = OpPopFashionMerger::getBrandData($info['brand_tid']);
                $info['brand_name'] = $brandInfo['name'];
                //地区
                if (isset($info['iRegion']) && !empty($info['iRegion'])) {
                    $region_id = intval($info['iRegion']);
                } else {
                    $region_id = $brandInfo['sRegion'];
                }
                $info['iRegion'] = $region_id;
                $info['iRegion_text'] = GetCategory::getFieldFromId($region_id);
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);

                //标题
                $info['title'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'] . $info['iSubcategory_text'] . $info['brand_name'];

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);

                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';

                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);

                $type = $info['iGender'] == 3 || $info['iGender'] == 4 ? 'gcen' : 'gen';
                //款式图
                $info['detail_img'] = $this->getBrandDetailImage($tablename, $info['flag_text'], $id, $columnid);
                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_style_graphic'://款式流行
                //季节
                $info['iSeason_text'] = GetCategory::getNewEnName(5, $info['for_date']);
                $seasonId = getCategory::getIdsFrom(5, "sOriginalName,{$info['iSeason_text']}");
                if (isset($seasonId[0])) {
                    $info['iSeason'] = $seasonId[0];
                }
                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['typ']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                //品名
                $info['iSubcategory_text'] = GetCategory::getOtherFromIds($info['iSubcategory'], ['sName']);
                //品牌
                $brandInfo = OpPopFashionMerger::getBrandData($info['brand_tid']);
                $info['brand_name'] = $brandInfo['name'];
                //地区
                if (isset($info['iRegion']) && !empty($info['iRegion'])) {
                    $region_id = intval($info['iRegion']);
                } else {
                    $region_id = $brandInfo['sRegion'];
                }
                $info['iRegion'] = $region_id;
                $info['iRegion_text'] = GetCategory::getFieldFromId($region_id);

                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);

                //标题
                $info['title'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'] . $info['iSubcategory_text'] . $info['brand_name'];

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);

                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';

                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);

                //款式图
                $info['detail_img'] = $this->getStyleGraphicDetailImage($tablename, $info['flag_text'], $id, $columnid);
                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_style_graphic_china'://国内市场
                //季节
                $info['iSeason_text'] = GetCategory::getNewEnName(5, $info['for_date']);
                $seasonId = getCategory::getIdsFrom(5, "sOriginalName,{$info['iSeason_text']}");
                if (isset($seasonId[0])) {
                    $info['iSeason'] = $seasonId[0];
                }
                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['typ']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                //品名
                $info['iSubcategory_text'] = GetCategory::getOtherFromIds($info['iSubcategory'], ['sName']);
                //品牌
                $brandInfo = OpPopFashionMerger::getBrandData($info['brand_tid']);
                $info['brand_name'] = $brandInfo['name'];
                //地区
                if (isset($info['iRegion']) && !empty($info['iRegion'])) {
                    $region_id = intval($info['iRegion']);
                } else {
                    $region_id = $brandInfo['sRegion'];
                }
                $info['iRegion'] = $region_id;
                $info['iRegion_text'] = GetCategory::getFieldFromId($region_id);
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);

                //标题
                $info['title'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'] . $info['iSubcategory_text'] . $info['brand_name'];

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);

                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';

                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);

                //款式图
                $info['detail_img'] = $this->getStyleGraphicChinaDetailImage($tablename, $info['flag_text'], $id, $columnid);
                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_streetitem'://街头时尚
                //季节
                $info['iSeason_text'] = GetCategory::getNewEnName(5, $info['for_date']);
                $seasonId = getCategory::getIdsFrom(5, "sOriginalName,{$info['iSeason_text']}");
                if (isset($seasonId[0])) {
                    $info['iSeason'] = $seasonId[0];
                }
                // 线下和街拍栏目对季节 特殊处理
                if ($columnid == 54 || $columnid == 56) {
                    $tempId = OpPopFashionMerger::getSimilarId($info['iSeason']);
                    $info['iSeason'] = !empty($tempId['sOwnChildId']) ? $tempId['sOwnChildId'] : $info['iSeason'];
                }
                //地区
                $region_id = GetCategory::getRegionIdFromEnName($info['region']);
                $info['iRegion_text'] = GetCategory::getFieldFromId($region_id);
                $info['iRegion'] = $region_id;
                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['typ']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                //品名
                $info['iSubcategory_text'] = GetCategory::getOtherFromIds($info['iSubcategory'], ['sName']);
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);

                //标题
                $info['title'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'] . $info['iSubcategory_text'] . $info['brand_name'];
                // 重命名规则调整
                $info['rename_rule'] = $info['iSeason_text'] . rtrim($info['iRegion_text']) . ltrim($info['iGender_text']) . " 街拍图库" . $info['iCategory_text'] . $info['iSubcategory_text'] . $info['brand_name'];

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);

                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';

                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);
                //品牌
                $brandInfo = OpPopFashionMerger::getBrandData($info['iBrand']);
                $info['brand_name'] = $brandInfo['name'];

                $type = $info['iGender'] == 3 || $info['iGender'] == 4 ? 'gcen' : 'gen';
                //单品品名品牌多选处理
                if (count($info['aAttribute']) > 1) {
                    //多条单品品名属性
                    $info['iCategory_text'] = '搭配';
                    $info['iSubcategory_text'] = '搭配';
                    $info['brand_name'] = '其他';
                    $linkCategory = 'javascript:void(0);';
                    $linkSubcategory = 'javascript:void(0);';
                    $linkBrand = 'javascript:void(0);';
                } else {
                    $linkCategory = $this->common_model->getLink($columnid, $params, 'cat', $info['iCategory'], true, 'anchor');
                    $linkSubcategory = $this->common_model->getLink($columnid, $params, 'cat', $info['iSubcategory'], true, 'anchor');
                    $linkBrand = 'javascript:void(0);';
                }
                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                $info['detail_img'][0]['simpleName'] = $simpleName;
                $info['detail_img'][0]['id'] = $id;
                $info['detail_img'][0]['columnid'] = $columnid;

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_ordermeeting'://订货会
                //季节
                $info['iSeason_text'] = GetCategory::getNewEnName(5, $info['for_date']);
                $seasonId = getCategory::getIdsFrom(5, "sOriginalName,{$info['iSeason_text']}");
                if (isset($seasonId[0])) {
                    $info['iSeason'] = $seasonId[0];
                }
                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['gender']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['gender'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);

                //品名
                $info['iSubcategory_text'] = GetCategory::getOtherFromIds($info['iSubcategory'], ['sName']);
                //品牌
                $brandInfo = OpPopFashionMerger::getBrandData($info['brand_tid']);
                $info['brand_name'] = $brandInfo['name'];
                //地区
                if (isset($info['iRegion']) && !empty($info['iRegion'])) {
                    $region_id = intval($info['iRegion']);
                } else {
                    $region_id = $brandInfo['sRegion'];
                }
                $info['iRegion'] = $region_id;
                $info['iRegion_text'] = GetCategory::getFieldFromId($region_id);

                //标题
                $info['title'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'] . $info['iSubcategory_text'] . $info['brand_name'];
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);

                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';

                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                if ($path['detailImg']) { //新数据
                    foreach ($path['detailImg'] as $key => $_detailpath) {
                        $info['detail_img'][$key]['smallPath'] = $_detailpath['smallPath'];
                        $info['detail_img'][$key]['bigPath'] = $_detailpath['bigPath'];
                        $info['detail_img'][$key]['simpleName'] = $simpleName;
                        $info['detail_img'][$key]['id'] = $id;
                        $info['detail_img'][$key]['columnid'] = $columnid;
                    }
                } else { //老数据
                    $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                    $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                    $info['detail_img'][0]['simpleName'] = $simpleName;
                    $info['detail_img'][0]['id'] = $id;
                    $info['detail_img'][0]['columnid'] = $columnid;
                }

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_templateitem'://设计模板
                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['typ']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                //品名
                $info['iSubcategory_text'] = GetCategory::getOtherFromIds($info['iSubcategory'], ['sName']);
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);

                //标题
                $info['title'] = $info['iGender_text'] . $info['iCategory_text'] . $info['iSubcategory_text'];

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);

                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';

                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                $info['detail_img'][0]['simpleName'] = $simpleName;
                $info['detail_img'][0]['id'] = $id;
                $info['detail_img'][0]['columnid'] = $columnid;

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_style_detail'://款式细节
                //季节
                $info['iSeason_text'] = GetCategory::getNewEnName(5, $info['for_date']);
                $seasonId = getCategory::getIdsFrom(5, "sOriginalName,{$info['iSeason_text']}");
                if (isset($seasonId[0])) {
                    $info['iSeason'] = $seasonId[0];
                }
                //地区
                $region_id = GetCategory::getRegionIdFromEnName($info['region']);
                $info['iRegion_text'] = GetCategory::getFieldFromId($region_id);
                $info['iRegion'] = $region_id;
                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['typ']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                //标题
                $info['title'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'];

                // 款式部位
                $iStylePart = $info['iStylePart'];
                $sStylePartName = GetCategory::getOtherFromIds($iStylePart, ['sName']);
                // 下载图片重命名
                $info['rename_rule'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'] . $sStylePartName;

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);

                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';

                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                $info['detail_img'][0]['simpleName'] = $simpleName;
                $info['detail_img'][0]['id'] = $id;
                $info['detail_img'][0]['columnid'] = $columnid;

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_graphicitem'://图案花型
                //标题
                $memo_key = trim($info['memo']);
                $info['title'] = $memo_key ? $memo_key : '';
                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);
                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';
                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);
                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);
                //品牌
                $brandInfo = OpPopFashionMerger::getBrandData($info['iBrandId']);
                $info['brand_name'] = $brandInfo['name'];

                $info['rename_rule'] = trim($info['brand_name']) . ' ' . trim($info['title']);

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                if ($path['detailImg']) { //新数据
                    foreach ($path['detailImg'] as $key => $_detailpath) {
                        $info['detail_img'][$key]['smallPath'] = $_detailpath['smallPath'];
                        $info['detail_img'][$key]['bigPath'] = $_detailpath['bigPath'];
                        $info['detail_img'][$key]['simpleName'] = $simpleName;
                        $info['detail_img'][$key]['id'] = $id;
                        $info['detail_img'][$key]['columnid'] = $columnid;
                    }
                } else { //老数据
                    $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                    $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                    $info['detail_img'][0]['simpleName'] = $simpleName;
                    $info['detail_img'][0]['id'] = $id;
                    $info['detail_img'][0]['columnid'] = $columnid;
                }

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_graphic_craft_item'://图案工艺
                $spatterTechnics = $info['sPatternCraft'] ? trim(GetCategory::getOtherFromIds($info['sPatternCraft'], ['sName'])) : '';
                //标题
                $info['title'] = $spatterTechnics;
                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);
                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';
                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);
                //款式信息
                $info['relation_info'] = [
                    'classify' => ['name' => GetCategory::getOtherFromColId($columnid, 'sName'), 'link' => $this->common_model->columnRootPath($columnid)],
                ];
                //品牌
                $brandInfo = OpPopFashionMerger::getBrandData($info['iBrandId']);
                $info['brand_name'] = $brandInfo['name'];
                // 图片下载重命名
                $info['rename_rule'] = trim($info['title']) . ' ' . trim($info['brand_name']);

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                if ($path['detailImg']) {
                    foreach ($path['detailImg'] as $key => $_detailpath) {
                        $info['detail_img'][$key]['smallPath'] = $_detailpath['smallPath'];
                        $info['detail_img'][$key]['bigPath'] = $_detailpath['bigPath'];
                        $info['detail_img'][$key]['simpleName'] = $simpleName;
                        $info['detail_img'][$key]['id'] = $id;
                        $info['detail_img'][$key]['columnid'] = $columnid;
                    }
                }

                // =========== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);

                $info['sPatternCraftName'] = $spatterTechnics;
                break;
            case 'product_fabricgallery_item'://面料图库
                //标题
                $memo_key = trim($info['memo']);
                $info['title'] = $memo_key ? $memo_key : '';
                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);
                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';
                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);
                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);
                //品牌
                $brandInfo = OpPopFashionMerger::getBrandData($info['iBrandId']);
                $info['brand_name'] = $brandInfo['name'];

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $tablename_detail = 'product_fabricgallery_item_detail';
                $info['detail_img'] = $this->getFabricgalleryDetailImage($tablename_detail, $info['related_flag'], $id, $columnid);
                if (empty($info['detail_img'])) {
                    $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                    $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                    $info['detail_img'][0]['simpleName'] = $simpleName;
                    $info['detail_img'][0]['id'] = $id;
                    $info['detail_img'][0]['columnid'] = $columnid;
                }

                //供应商信息
                $supllierTableName = '`fashion`.`product_fabricgallery_supplier`';
                if (isset($info['supplier']) && !empty($info['supplier'])) {
                    $supllierInfo = $this->db()->select('name,telphone,qq')->from($supllierTableName)->where('id', $info['supplier'])->get()->result_array();
                    // $this->db()->close();
                    $info['supllierInfo'] = $supllierInfo[0];
                }
                break;
            case 'product_hat':
                //iCategory,iIndustry,iRegion,iType,iFordate,
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['type']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['type'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                //季节
                $info['iSeason_text'] = GetCategory::getOtherFromIds($info['iFordate'], ['sName']);
                $info['iSeason'] = $info['iFordate'];
                //地区
                $info['iRegion_text'] = GetCategory::getFieldFromId($info['iRegion']);
                $info['title'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'];
                $info['rename_rule'] = '服饰品' . $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'];
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);

                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';

                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                $info['detail_img'][0]['simpleName'] = $simpleName;
                $info['detail_img'][0]['id'] = $id;
                $info['detail_img'][0]['columnid'] = $columnid;

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_wrapsitem':
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);

                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['typ']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                //季节
                $info['iSeason_text'] = GetCategory::getOtherFromIds($info['iFordate'], ['sName']);
                $info['iSeason'] = $info['iFordate'];
                //地区
                $info['iRegion_text'] = GetCategory::getFieldFromId($info['iRegion']);
                // 详情标题
                $info['title'] = $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'];
                // 下载重命名规则
                $info['rename_rule'] = '服饰品' . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'];
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);

                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';

                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                $info['detail_img'][0]['simpleName'] = $simpleName;
                $info['detail_img'][0]['id'] = $id;
                $info['detail_img'][0]['columnid'] = $columnid;

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_glove':
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['type']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['type'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                //季节
                $info['iSeason_text'] = GetCategory::getNewEnName(5, $info['for_date']);
                $seasonId = getCategory::getIdsFrom(5, "sOriginalName,{$info['iSeason_text']}");
                if (isset($seasonId[0])) {
                    $info['iSeason'] = $seasonId[0];
                }
                //地区
                $info['iRegion_text'] = GetCategory::getFieldFromId($info['iRegion']);
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);
                $info['title'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'];
                $info['rename_rule'] = '服饰品' . $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'];

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);

                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';

                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                $info['detail_img'][0]['simpleName'] = $simpleName;
                $info['detail_img'][0]['id'] = $id;
                $info['detail_img'][0]['columnid'] = $columnid;

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_docerationitem':
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                //性别
                $info['iGender_text'] = GetCategory::getOtherFromIds($info['iGender'], ['sName']);
                //季节
                $info['iSeason_text'] = GetCategory::getNewEnName(5, $info['for_date']);
                $seasonId = getCategory::getIdsFrom(5, "sOriginalName,{$info['iSeason_text']}");
                if (isset($seasonId[0])) {
                    $info['iSeason'] = $seasonId[0];
                }
                //地区
                $info['iRegion_text'] = GetCategory::getFieldFromId($info['iRegion']);
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);
                $info['title'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'];
                $info['rename_rule'] = '服饰品' . $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'];

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);

                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';

                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                $info['detail_img'][0]['simpleName'] = $simpleName;
                $info['detail_img'][0]['id'] = $id;
                $info['detail_img'][0]['columnid'] = $columnid;

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_shopset':
                //性别
                // $info['iGender_text'] = GetCategory::getOtherFromIds( $info['iSeason'] );
                //季节
                $info['iSeason_text'] = trim(GetCategory::getOtherFromIds($info['iSeason'], ['sName']));
                //地区
                $info['iRegion_text'] = GetCategory::getFieldFromId($info['iRegion']);
                //品牌
                $info['brand_name'] = GetCategory::getBrandOtherFormId($info['brand_tid'], 'en_cn_brand');
                //行业
                $info['iIndustry_text'] = trim(GetCategory::getOtherFromIds($info['iIndustry'], ['sName']));
                //标题
                $info['title'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['brand_name'];
                // 图片下载重命名
                $brand_name_str = !empty($info['brand_name']) ? ' ' . $info['brand_name'] : '';
                $info['rename_rule'] = $info['iSeason_text'] . $info['iRegion_text'] . $brand_name_str . ' 店铺陈列';

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);

                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';

                //关联标签
                // $info['sRelationLabels'] = $this->getStyleRelationLabels($id, $tablename);
                // $info['relationLabels'] = $this->getRelationLabels($info['sRelationLabels'], $columnid);

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                $info['detail_img'][0]['simpleName'] = $simpleName;
                $info['detail_img'][0]['id'] = $id;
                $info['detail_img'][0]['columnid'] = $columnid;

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 't_inspiration_img_db':
                //iBrandTid iRegion  sIndustry fordate
                //季节
                $info['iSeason_text'] = GetCategory::getNewEnName(5, $info['fordate']);
                $seasonId = getCategory::getIdsFrom(5, "sOriginalName,{$info['iSeason_text']}");
                if (isset($seasonId[0])) {
                    $info['iSeason'] = $seasonId[0];
                }
                //地区
                $info['iRegion_text'] = GetCategory::getFieldFromId($info['iRegion']);
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);
                $info['title'] = isset($info['title']) ? $info['title'] : $info['iSeason_text'] . $info['iRegion_text'];

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);

                //更新时间
                $info['updateTime'] = isset($info['dCreateTime']) && !empty($info['dCreateTime']) ? date('Y-m-d', strtotime($info['dCreateTime'])) : '';

                //关联标签
                // $info['relationLabels'] = isset($info['sRelationLabels']) && (!empty($info['sRelationLabels'])) ? $this->getRelationLabels($info['sRelationLabels'], $columnid) : [];

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                $info['detail_img'][0]['simpleName'] = $simpleName;
                $info['detail_img'][0]['id'] = $id;
                $info['detail_img'][0]['columnid'] = $columnid;

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_presscon_details':
                //性别
                $info['iGender'] = $info['sGender'];
                $info['iGender_text'] = GetCategory::getOtherFromIds($info['sGender'], ['sName']);
                //季节
                $info['iSeason_text'] = GetCategory::getOtherFromIds($info['iSeason'], ['sName']);
                //地区
                $info['iRegion_text'] = GetCategory::getFieldFromId($info['iRegion']);
                //品牌
                $info['brand_name'] = GetCategory::getBrandOtherFormId($info['brand_tid'], 'en_cn_brand');
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);
                //标题
                $info['title'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['category_text'] . $info['brand_name'];

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);
                $times = array();
                if (isset($info['create_time_special']) && $info['create_time_special']) {
                    array_push($times, strtotime($info['create_time_special']));
                }
                if (isset($info['create_time_focus']) && $info['create_time_focus']) {
                    array_push($times, strtotime($info['create_time_focus']));
                }
                if (isset($info['create_time']) && $info['create_time']) {
                    array_push($times, strtotime($info['create_time']));
                }
                if (isset($info['create_time_video']) && $info['create_time_video']) {
                    array_push($times, strtotime($info['create_time_video']));
                }
                $info['updateTime'] = $times ? date('Y-m-d', min($times)) : '';

                //关联标签
                // $info['relationLabels'] = isset($info['sRelationLabels']) && (!empty($info['sRelationLabels'])) ? $this->getRelationLabels($info['sRelationLabels'], $columnid) : [];

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                $info['detail_img'][0]['simpleName'] = $simpleName;
                $info['detail_img'][0]['id'] = $id;
                $info['detail_img'][0]['columnid'] = $columnid;
                // 单张下载重命名
                $rename_rule = $this->getRenameData($columnid, $info['title'], $id, $path['bigPath']);
                $info['detail_img'][0]['rename'] = $rename_rule;

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_brochures_photo':
                $info_p = OpPopFashionMerger::getProductData($info['borochid'], 'product_brochures');
                $info_p = $info_p[$info['borochid']];

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews('product_brochures', $info_p['id'], $info_p);

                //季节
                $info['iSeason_text'] = GetCategory::getNewEnName(5, $info['for_date']);
                $seasonId = getCategory::getIdsFrom(5, "sOriginalName,{$info['iSeason_text']}");
                if (isset($seasonId[0])) {
                    $info['iSeason'] = $seasonId[0];
                }
                //地区
                $region_id = GetCategory::getRegionIdFromEnName($info['region']);
                $info['iRegion_text'] = GetCategory::getFieldFromId($region_id);
                $info['iRegion'] = $region_id;
                //品牌
                $info['brand_name'] = GetCategory::getBrandOtherFormId($info['brand_tid'], 'en_cn_brand');
                $genderInfo = [];
                if ($info_p['iGender']) {
                    $genderArr = GetCategory::getOtherFromIds($info_p['iGender'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($genderArr)) {
                        foreach ($genderArr['iAttributeId'] as $key => $item) {
                            $genderInfo[$key]['name'] = $genderArr['sName'][$key];
                            $type = ($item == 3 || $item == 4) ? 'gcen' : 'gen';
                            $genderInfo[$key]['link'] = $this->common_model->getLink($columnid, '', $type, $item, true, 'anchor');
                        }
                    }
                }
                //单品
                $catagoryInfo = [];
                if ($info_p['iCategory']) {
                    $categoryArr = GetCategory::getOtherFromIds($info_p['iCategory'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($categoryArr)) {
                        foreach ($categoryArr['iAttributeId'] as $key => $item) {
                            $catagoryInfo[$key]['name'] = $categoryArr['sName'][$key];
                            $catagoryInfo[$key]['link'] = $this->common_model->getLink($columnid, '', 'cat', $item, true, 'anchor');
                        }
                    }
                }
                //行业
                $industryInfo = [];
                if ($info_p['iIndustry']) {
                    $industryArr = GetCategory::getOtherFromIds($info_p['iIndustry'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($industryArr)) {
                        foreach ($industryArr['iAttributeId'] as $key => $item) {
                            $industryInfo[$key]['name'] = $industryArr['sName'][$key];
                            $industryInfo[$key]['link'] = $this->common_model->getLink($columnid, '', 'ind', $item, true, 'anchor');
                        }
                    }
                }
                //标题
                $info['title'] = $info_p['name_text'] ? $info_p['name_text'] : '';
                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';

                //关联标签
                // $info['relationLabels'] = isset($info['sRelationLabels']) && (!empty($info['sRelationLabels'])) ? $this->getRelationLabels($info['sRelationLabels'], $columnid) : [];

                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                $info['detail_img'][0]['simpleName'] = $simpleName;
                $info['detail_img'][0]['id'] = $id;
                $info['detail_img'][0]['columnid'] = $columnid;

                // 单张下载重命名
                $rename_rule = $this->getRenameData($columnid, $info['title'], $id, $path['bigPath']);
                $info['detail_img'][0]['rename'] = $rename_rule;

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_design_refrence_details':
                //iCategory, iIndustry
                $info_p = OpPopFashionMerger::getProductData($info['pid'], 'product_design_refrence');
                $info_p = $info_p[$info['pid']];
                //标题
                $info['title'] = $info_p['name_text'] ? $info_p['name_text'] : '';
                //浏览量 记录册浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews('product_design_refrence', $info['pid'], $info_p);
                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';
                //关联标签
                // $info['relationLabels'] = isset($info['sRelationLabels']) && (!empty($info['sRelationLabels'])) ? $this->getRelationLabels($info['sRelationLabels'], $columnid) : [];
                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);
                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['typ']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                $genderInfo = [];
                if ($info['iGender']) {
                    $genderArr = GetCategory::getOtherFromIds($info['iGender'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($genderArr)) {
                        foreach ($genderArr['iAttributeId'] as $key => $item) {
                            $genderInfo[$key]['name'] = $genderArr['sName'][$key];
                            $type = ($item == 3 || $item == 4) ? 'gcen' : 'gen';
                            $genderInfo[$key]['link'] = $this->common_model->getLink($columnid, '', $type, $item, true, 'anchor');
                        }
                    }
                }
                //单品
                $catagoryInfo = [];
                if ($info_p['iCategory']) {
                    $categoryArr = GetCategory::getOtherFromIds($info_p['iCategory'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($categoryArr)) {
                        foreach ($categoryArr['iAttributeId'] as $key => $item) {
                            $catagoryInfo[$key]['name'] = $categoryArr['sName'][$key];
                            $catagoryInfo[$key]['link'] = $this->common_model->getLink($columnid, '', 'cat', $item, true, 'anchor');
                        }
                    }
                }
                //行业
                $industryInfo = [];
                if ($info_p['iIndustry']) {
                    $industryArr = GetCategory::getOtherFromIds($info_p['iIndustry'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($industryArr)) {
                        foreach ($industryArr['iAttributeId'] as $key => $item) {
                            $industryInfo[$key]['name'] = $industryArr['sName'][$key];
                            $industryInfo[$key]['link'] = $this->common_model->getLink($columnid, '', 'ind', $item, true, 'anchor');
                        }
                    }
                }

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                $info['detail_img'][0]['simpleName'] = $simpleName;
                $info['detail_img'][0]['id'] = $id;
                $info['detail_img'][0]['columnid'] = $columnid;

                // 单张下载重命名
                $rename_rule = $this->getRenameData($columnid, $info['title'], $id, $path['bigPath']);
                $info['detail_img'][0]['rename'] = $rename_rule;

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_vector_refrence':
                $memo_key = trim($info['theme']);
                //标题
                $info['title'] = $memo_key ? $memo_key : '';
                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews('product_vector_refrence_group', $info['groupid'], $info);
                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';
                //关联标签
                // $info['relationLabels'] = isset($info['sRelationLabels']) && (!empty($info['sRelationLabels'])) ? $this->getRelationLabels($info['sRelationLabels'], $columnid) : [];
                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);
                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['type']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['type'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                $genderInfo = [];
                if ($info['iGender']) {
                    $genderArr = GetCategory::getOtherFromIds($info['iGender'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($genderArr)) {
                        foreach ($genderArr['iAttributeId'] as $key => $item) {
                            $genderInfo[$key]['name'] = $genderArr['sName'][$key];
                            $type = ($item == 3 || $item == 4) ? 'gcen' : 'gen';
                            $genderInfo[$key]['link'] = $this->common_model->getLink($columnid, '', $type, $item, true, 'anchor');
                        }
                    }
                }
                //单品
                $catagoryInfo = [];
                if ($info['iCategory']) {
                    $categoryArr = GetCategory::getOtherFromIds($info['iCategory'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($categoryArr)) {
                        foreach ($categoryArr['iAttributeId'] as $key => $item) {
                            $catagoryInfo[$key]['name'] = $categoryArr['sName'][$key];
                            $catagoryInfo[$key]['link'] = $this->common_model->getLink($columnid, '', 'cat', $item, true, 'anchor');
                        }
                    }
                }
                //行业
                $industryInfo = [];
                if ($info['iIndustry']) {
                    $industryArr = GetCategory::getOtherFromIds($info['iIndustry'], ['iAttributeId', 'sName'], 'array');
                    if (is_array($industryArr)) {
                        foreach ($industryArr['iAttributeId'] as $key => $item) {
                            $industryInfo[$key]['name'] = $industryArr['sName'][$key];
                            $industryInfo[$key]['link'] = $this->common_model->getLink($columnid, '', 'ind', $item);
                        }
                    }
                }

                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                $info['detail_img'][0]['simpleName'] = $simpleName;
                $info['detail_img'][0]['id'] = $id;
                $info['detail_img'][0]['columnid'] = $columnid;

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'mostrend_content':
                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['type']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['type'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                //季节
                $info['iSeason_text'] = $info['iSeason_text'] = GetCategory::getNewEnName(5, $info['popular_time']);
                $seasonId = getCategory::getIdsFrom(5, 'sOriginalName,' . $info['iSeason_text']);
                if (isset($seasonId[0])) {
                    $info['iSeason'] = $seasonId[0];
                }
                // 线下和街拍栏目对季节 特殊处理
                if ($columnid == 54 || $columnid == 56) {
                    $tempId = OpPopFashionMerger::getSimilarId($info['iSeason']);
                    $info['iSeason'] = !empty($tempId['sOwnChildId']) ? $tempId['sOwnChildId'] : $info['iSeason'];
                }
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                //品名
                $info['iSubcategory_text'] = GetCategory::getOtherFromIds($info['iSubcategory'], ['sName']);
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);
                //地区
                $info['iRegion_text'] = GetCategory::getFieldFromId($info['iRegion']);
                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);
                //更新时间
                $info['updateTime'] = isset($info['create_time']) && !empty($info['create_time']) ? date('Y-m-d', strtotime($info['create_time'])) : '';
                //关联标签
                // $info['relationLabels'] = isset($info['sRelationLabels']) && (!empty($info['sRelationLabels'])) ? $this->getRelationLabels($info['sRelationLabels'], $columnid) : [];
                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);
                $simpleName = getProductTableName($tablename);
                //所有图片列表
                if ($info['channel'] == 'bookstore') {
                    //书店
                    $path = getImagePath($tablename, $info);
                    $pic_name = json_decode($info['pic_name']);
                    if (is_array($pic_name)) {
                        $info['detail_img'][0]['smallPath'] = $path['booklist'][$index]['smallPath'];
                        $info['detail_img'][0]['bigPath'] = $path['booklist'][$index]['bigPath'];
                        $info['detail_img'][0]['id'] = $id;
                        $info['detail_img'][0]['columnid'] = $columnid;
                        $info['detail_img'][0]['simpleName'] = $simpleName;

                    }
                    $info['title'] = $info['title'] ? $info['title'] : '';
                    //性别
                    $info['relation_info']['gender'] = [];
                    if ($info['iGender']) {
                        $genderArr = GetCategory::getOtherFromIds($info['iGender'], ['iAttributeId', 'sName'], 'array');
                        if (is_array($genderArr)) {
                            foreach ($genderArr['iAttributeId'] as $key => $item) {
                                $info['relation_info']['gender'][$key]['name'] = $genderArr['sName'][$key];
                                $type = ($item == 3 || $item == 4) ? 'gcen' : 'gen';
                                $info['relation_info']['gender'][$key]['link'] = $this->common_model->getLink($columnid, '', $type, $item, true, 'anchor');
                            }
                        }
                    }
                    //单品
                    $info['relation_info']['catagory'] = [];
                    if ($info['iCategory']) {
                        $categoryArr = GetCategory::getOtherFromIds($info['iCategory'], ['iAttributeId', 'sName'], 'array');
                        if (is_array($categoryArr)) {
                            foreach ($categoryArr['iAttributeId'] as $key => $item) {
                                $info['relation_info']['catagory'][$key]['name'] = $categoryArr['sName'][$key];
                                $info['relation_info']['catagory'][$key]['link'] = $this->common_model->getLink($columnid, '', 'cat', $item, true, 'anchor');
                            }
                        }
                    }
                    //行业
                    $info['relation_info']['industry'] = [];
                    if ($info['iIndustry']) {
                        $industryArr = GetCategory::getOtherFromIds($info['iIndustry'], ['iAttributeId', 'sName'], 'array');
                        if (is_array($industryArr)) {
                            foreach ($industryArr['iAttributeId'] as $key => $item) {
                                $info['relation_info']['industry'][$key]['name'] = $industryArr['sName'][$key];
                                $info['relation_info']['industry'][$key]['link'] = $this->common_model->getLink($columnid, '', 'ind', $item, true, 'anchor');
                            }
                        }
                    }
                } else {
                    // 街头时尚	MEMCACHE+MYSQL
                    $tocnl['mostrendid'] = $id;
                    $sql = "select id from trends_new.picture where mostrendid='" . $id . "'";
                    $result = $this->query($sql);
                    $simplePicName = getProductTableName('picture');
                    if ($result) {
                        foreach ($result as $k => $v) {
                            $picture = OpPopFashionMerger::getProductData($v['id'], 'picture');
                            $picture = $picture[$v['id']];
                            $path = getImagePath('picture', $picture);
                            $info['detail_img'][$k]['smallPath'] = $path['smallPath'];
                            $info['detail_img'][$k]['bigPath'] = $path['bigPath'];
                            $info['detail_img'][$k]['simpleName'] = $simplePicName;
                            $info['detail_img'][$k]['id'] = $v['id'];
                            $info['detail_img'][$k]['columnid'] = $columnid;
                        }

                    }
                    $info['title'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['brand_name'];

                }
                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'picture':
                //性别
                $info['iGender_text'] = GetCategory::getNewEnName(1, $info['type']);
                $info['iGender'] = GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['type'], $columnid);
                $info['iGender'] = $info['iGender'][0];
                //季节
                $info['iSeason_text'] = $info['iSeason_text'] = GetCategory::getNewEnName(5, $info['popular_time']);
                $seasonId = getCategory::getIdsFrom(5, "sOriginalName,{$info['iSeason_text']}");
                if (isset($seasonId[0])) {
                    $info['iSeason'] = $seasonId[0];
                }
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                //品名
                $info['iSubcategory_text'] = GetCategory::getOtherFromIds($info['iSubcategory'], ['sName']);
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);
                //地区
                $info['iRegion_text'] = GetCategory::getFieldFromId($info['iRegion']);

                $info['title'] = $info['iSeason_text'] . $info['iRegion_text'];

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);
                //更新时间
                $info['create_time'] = $info['updateTime'] = isset($info['time']) && !empty($info['time']) ? date('Y-m-d', strtotime($info['time'])) : '';
                //关联标签
                // $info['relationLabels'] = isset($info['sRelationLabels']) && (!empty($info['sRelationLabels'])) ? $this->getRelationLabels($info['sRelationLabels'], $columnid) : [];
                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);
                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                $info['detail_img'][0]['smallPath'] = $path['smallPath'];
                $info['detail_img'][0]['bigPath'] = $path['bigPath'];
                $info['detail_img'][0]['simpleName'] = $simpleName;
                $info['detail_img'][0]['id'] = $id;
                $info['detail_img'][0]['columnid'] = $columnid;

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['source_text']);
                break;
            case 'product_tideleader':
                //性别
                $info['iGender_text'] = GetCategory::getOtherFromIds($info['iType'], ['sName']);
                $info['iGender'] = $info['iType'];
                //季节
                $info['iSeason'] = $info['iFordate'];
                $info['iSeason_text'] = GetCategory::getOtherFromIds($info['iFordate'], ['sName']);
                //单品
                $info['iCategory_text'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                //品名
                $info['iSubcategory_text'] = GetCategory::getOtherFromIds($info['iSubcategory'], ['sName']);
                //行业
                $info['iIndustry_text'] = GetCategory::getOtherFromIds($info['iIndustry'], ['sName']);
                //地区
                $info['iRegion_text'] = GetCategory::getFieldFromId($info['iRegion']);
                // 详情标题
                $info['title'] = $info['iSeason_text'] . $info['iRegion_text'] . $info['iGender_text'] . $info['iCategory_text'] . $info['iSubcategory_text'];
                // 明星达人/ins博主
                $iStarIns = !empty($info['iStarIns']) ? trim($info['iStarIns']) : '';
                $starInsStr = !empty($typeArr[$iStarIns]) ? ' ' . $typeArr[$iStarIns] : '';
                // 人名
                $starLabel_txt = OpPopFashionMerger::getOldCategoryLabel($info['iStarLabel'])['cat_name'];
                // 新的重命名规则 ：pop_季节+地区+性别_单品_品名_明星达人/ins博主_人名
                $info['rename_rule'] = $info['iSeason_text'] . trim($info['iRegion_text']) . trim($info['iGender_text']) . $info['iCategory_text'] . $info['iSubcategory_text'] . $starInsStr . ' ' . trim($starLabel_txt);

                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);
                //更新时间
                $info['updateTime'] = isset($info['dCreateTime']) && !empty($info['dCreateTime']) ? date('Y-m-d', strtotime($info['dCreateTime'])) : '';
                //关联标签
                // $info['relationLabels'] = isset($info['sRelationLabels']) && (!empty($info['sRelationLabels'])) ? $this->getRelationLabels($info['sRelationLabels'], $columnid) : [];
                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);
                //品牌
                $brandInfo = OpPopFashionMerger::getBrandData($info['iBrand']);
                $info['brand_name'] = $brandInfo['name'];
                //图片信息
                $info['detail_img'] = $this->getDideleaderDetailImage($tablename, $info['sFlagText'], $id, $columnid);

                // ====== 来源 ===========
                $info['source'] = $this->_getSourceText($info['sSourceText']);
                break;
            case 't_digital_quick_print'://数码云打印
                //标题
                $info['title'] = isset($info['sTitle']) ? $info['sTitle'] : '';
                //优料宝ID
                $info['iUlbId'] = isset($info['iUlbId']) ? $info['iUlbId'] : '';
                //浏览量
                $this->load->model('statistics_model');
                $info['view_count'] = $this->statistics_model->getViews($tablename, $id, $info);
                //图案内容
                if ($info['sPatternContent']) {
                    $sPatternContent = trim(GetCategory::getOtherFromIds($info['sPatternContent'], ['sName']));
                }

                $info['sPatternContent'] = $sPatternContent;

                //更新时间
                $info['updateTime'] = isset($info['dCreateTime']) && !empty($info['dCreateTime']) ? date('Y-m-d', strtotime($info['dCreateTime'])) : '';
                //广告
                // $info['ads'] = $this->common_model->getAds($columnid, Common_model::AD_DETAIL_RIGHT, '', $columnPid, true);
                //图片信息
                $path = getImagePath($tablename, $info);
                $simpleName = getProductTableName($tablename);
                if ($path['detailImg']) { //新数据
                    foreach ($path['detailImg'] as $key => $_detailpath) {
                        $info['detail_img'][$key]['smallPath'] = $_detailpath['smallPath'];
                        $info['detail_img'][$key]['bigPath'] = $_detailpath['bigPath'];
                        $info['detail_img'][$key]['simpleName'] = $simpleName;
                        $info['detail_img'][$key]['id'] = $id;
                        $info['detail_img'][$key]['columnid'] = $columnid;
                    }
                }
                break;
        }

        /*---------------------色彩分析 begin----------------------------*/
        //所有颜色的占比
        $colorDetails = isset($info['sColorDetails']) && $info['sColorDetails'] ? json_decode($info['sColorDetails'], true) : [];
        if ($colorDetails) {
            $info['colorProportion'] = $colorDetails;
        }
        /*---------------------色彩分析 end------------------------------*/
        $this->benchmark->mark('DetailsModelGetPicInfoEnd');

        return $info;
    }

    /**
     * 获取下载重命名
     * @param string|int $col 栏目id
     * @param string $val 拼接的规则 像（标题/品牌）
     * @param int $id 这条数据的id
     * @param string $bigPath 大图路径获取后缀名
     */
    public function getRenameData($col, $val, $id, $bigPath)
    {
        if (empty($col) || empty($val) || empty($bigPath) || empty($id)) {
            return '';
        }
        $rename = '';
        switch ($col) {
            // T台（单张）
            case 3:
                // 广告大片（单张）
            case 71:
                // 订货会合辑（单张）
            case 131:
                // 单品合辑 (单张)
            case 72:
                // 趋势手稿(单张)
            case 70:
                // 快反应系列(单张)
            case 115:
                // 流行画册(单张)  mongodb的数据
            case 130:
                $rename = str_replace([' ', '<', '>', '/', '\\', '|', ':', ' * ', '#', '\'', '"'], ['_'], trim($val)) . '_' . $id . '.' . pathinfo(basename($bigPath), PATHINFO_EXTENSION);
                break;
        }

        return $rename;
    }

    /**
     * 改变部分子栏目的下载重命名规则
     * time: 2020/06/02
     * @param string $col 子栏目
     * @param array $tableInfo 新改的命名主题
     * @param array $val $tableInfo['detail_img'] 循环的值$val
     */
    public function changeRenameRule($col = '', $tableInfo = array(), $val = array())
    {
        switch ($col) {
            // 57--明星/ins pop_季节+地区+性别_单品_品名_明星达人/ins博主_人名_ID
            case 57:
                // 56--街拍图库 pop_季节+地区+性别_街拍图库_单品_品名_ID
            case 56:
                // 120--大牌花型 pop_品牌_描述_ID
            case 120:
                // 124--图案工艺 pop_图案工艺_品牌_ID
            case 124:
                // 81--款式细节 pop_季节+性别_单品_款式部位_ID
            case 81:
                // 85--店铺陈列 pop_季节+地区_品牌_店铺陈列_ID
            case 85:
                // 84--服饰品 pop_服饰品_单品_ID
            case 84:
                $srting = 'pop_' . str_replace([' ', '<', '>', '/', '\\', '|', ':', ' * ', '#', '\'', '"'], ['_'], trim($tableInfo['rename_rule'])) . '_' . $val['id'] . '.' . pathinfo(basename($val['bigPath']), PATHINFO_EXTENSION);
                break;
            default:
                // 原来的公共重命名规则
                $srting = 'pop_' . str_replace([' ', '<', '>', '/', '\\', '|', ':', ' * ', '#', '\'', '"'], ['_'], trim($tableInfo['title'])) . '_' . $val['id'] . '.' . pathinfo(basename($val['bigPath']), PATHINFO_EXTENSION);
                break;
        }
        return $srting;
    }

    /**
     * 替换  http://img1.fm.pop-fashion.com 为 https://imgf1.pop-fashion.com
     * /ajax/getWorkbenchBottomDataList
     * @param $image
     * @param bool $is_ture 没有域名是否加
     * @return string
     */
    public function getImgfPath($image, $is_ture = false)
    {
        if (!$image) return '';
        if (substr($image, 0, 4) == 'http') {
            $new_https = explode('pop-fashion.com', $image);
            $imgList = getStaticUrl($new_https[1]) . $new_https[1];
        } else {
            $imgList = $image; // 不加域名
            if ($is_ture) { // 加域名
                $imgList = getStaticUrl($image) . $image;
            }

        }
        return $imgList;
    }

    /**
     * 替换成  like //player.youku.com/player.php/sid/XNDA0MDY2Nzg1Mg==/v.swf
     * @param string $vedioPath 视频路径
     * @return mixed|string
     */
    public function getVedioPath($vedioPath)
    {
        if (!$vedioPath) return '';

        if (strpos($vedioPath, 'http:') !== false) {
            $vedioPath = str_replace('http:', '', $vedioPath);
        }
        if (strpos($vedioPath, 'https:') !== false) {
            $vedioPath = str_replace('https:', '', $vedioPath);
        }
        return $vedioPath ? $vedioPath : '';
    }


    /**
     * @param string $tableName
     * @param int|string $id
     * @param string|array $params
     * @param int|string $columnId
     * @param string $type 'detail','list' 详情页和列表页
     * @param bool|int $colIdLink 是否取子栏目链接--子栏目id
     * @return array
     */
    public function getLabelInfo($tableName, $id, $params, $columnId, $type = 'detail', $colIdLink = 0)
    {
        $this->benchmark->mark('DetailsModelGetLabelInfo');

        $columnPid = (int)GetCategory::getOtherFromColId($columnId, 'iColumnPid');

        // 详情页key
        $dKeys = [
            /**
             * 款式库
             * 性别gen, 季节sea, 地区reg, 品牌bra, 单品cat, 品名scat, 风格-man, 元素(廓形shap, 细节spe, 工艺tech, 图案pat, 面料fab)
             */
            '4' => ['gen', 'sea', 'reg', 'bra', 'cat', 'scat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab'],
            // 秀场提炼: 色系aco
            '50' => ['gen', 'ind', 'sea', 'reg', 'bra', 'cat', 'scat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab', 'aco'],
            // 订货会
            '52' => ['gen', 'ind', 'sea', 'reg', 'bra', 'cat', 'prop'],
            // 全球实拍
            '54' => ['gen', 'ind', 'sea', 'reg', 'bra', 'cat', 'scat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab', 'exh'],
            // 全球实拍-批发市场
            '54_1' => ['gen', 'ind', 'sea', 'reg', 'bra', 'cat', 'scat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab'],
            // 全球实拍-零售市场
            '54_2' => ['gen', 'ind', 'sea', 'reg', 'bra', 'cat', 'scat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab'],
            // 全球实拍-展会图库
            '54_3' => ['gen', 'ind', 'sea', 'reg', 'bra', 'cat', 'exh'],
            // 名牌精选
            '55' => ['gen', 'ind', 'sea', 'reg', 'bra', 'cat', 'scat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab'],
            // 街拍图库
            '56' => ['gen', 'ind', 'sea', 'reg', 'cat'],
            // 潮流领袖： 明星达人sta
            '57' => ['gen', 'ind', 'sea', 'reg', 'cat', 'sta'],
            // 设计师品牌
            '122' => ['gen', 'ind', 'sea', 'reg', 'bra', 'cat', 'scat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab'],
            // 款式流行
            '123' => ['gen', 'ind', 'sea', 'reg', 'bra', 'cat', 'scat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab'],
            /**
             * 图案: 图案内容con, 矢量/位图for, 局部/满身use, 性别gen
             */
            // 图案素材
            '82' => ['con', 'for', 'use', 'gen'],
            // 大牌花型: 图案内容con, 矢量/位图for, 局部/满身use, 性别gen、品牌bra
            '120' => ['con', 'for', 'use', 'gen', 'bra'],
            // 数码云打印:
            '121' => ['con', 'use'],
            // 图案工艺：图案工艺tec
            '124' => ['tec'],
            /**
             * 素材
             */
            // 款式模板: 单品、品名、行业、性别
            '80' => ['cat', 'scat', 'ind', 'gen'],
            // 款式细节: 单品、品名、款式部位、行业、性别
            '81' => ['cat', 'scat', 'par', 'ind', 'gen'],
            // 服饰品: 季节、地区、单品
            '84' => ['sea', 'reg', 'cat'],
            // 店铺陈列: 季节、地区、品牌、性别
            '85' => ['sea', 'reg', 'bra', 'gen'],
            // 展会面料: 面料展exh、面料材质mat、面料工艺cra、图案内容con、性别gen
            '117' => ['exh', 'mat', 'cra', 'con', 'gen'],

        ];
        // 列表页key
        $lKeys = [
            /**
             * 款式库
             * 子栏目名称col, 子tab（即：全球实拍的需要显示零售、批发、展会）,
             * 季节sea, 地区reg, 明星达人sta, 展会名称exh, 市场类型mar, 品牌bra,
             * 单品cat, 品名scat, 风格-man, 元素(廓形shap, 细节spe, 工艺tech, 图案pat, 面料fab)
             */
            '4' => ['col', 'ds', 'sea', 'reg', 'sta', 'exh', 'mar', 'bra', 'cat', 'scat', 'man', 'shap', 'spe', 'tech', 'pat', 'fab'],
            /**
             * 图案库
             */
            // 图案素材: 局部/满身use, 矢量/位图for, 图案内容con(一级,二级)
            '82' => ['use', 'for', 'con'],
            // 大牌花型: 品牌bra, 局部/满身use, 矢量/位图for, 图案内容con(一级,二级)
            '120' => ['bra', 'use', 'con'],
            // 数码云打印: 局部/满身use, 图案内容con(一级,二级), 内容提供商 // todo 未取
            '121' => ['use', 'con', 'vendor'],
            // 图案工艺：图案工艺tec
            '124' => ['tec'],
            /**
             * 素材
             */
            // 款式模板: 单品
            '80' => ['cat'],
            // 款式细节: 性别、单品、款式部位
            '81' => ['gen', 'cat', 'par'],
            // 服饰品: 单品
            '84' => ['cat'],
            // 店铺陈列: 地区、品牌
            '85' => ['reg', 'bra'],
            // 展会面料: 面料展exh、面料材质mat、面料工艺cra、图案内容con
            '117' => ['exh', 'mat', 'cra', 'con'],
        ];

        switch ($type) {
            case 'list':
                $labelKeys = [];
                if (key_exists($columnId, $lKeys)) {
                    $labelKeys = $lKeys[$columnId];
                } elseif (key_exists($columnPid, $lKeys)) {
                    $labelKeys = $lKeys[$columnPid];
                }
                // 不是全部的时候
                if ($columnPid) {
                    $_index = array_search('col', $labelKeys);
                    if ($_index !== false) unset($labelKeys[$_index]);
                } // 全部的时候去除'ds', 'sta', 'exh', 'mar' 明显达人 展会名称 市场类型
                else {
                    $__keys = ['ds', 'sta', 'exh', 'mar'];
                    foreach ($__keys as $_key) {
                        $_index = array_search($_key, $labelKeys);
                        if ($_index !== false) unset($labelKeys[$_index]);
                    }
                }
                break;
            case 'detail':
                $tableName = getProductTableName($tableName); // 因详情表名为假表名需转换
            default:
                $labelKeys = key_exists($columnId, $dKeys) ? $dKeys[$columnId] : [];
                break;
        }

        $result = [];
        if (!$labelKeys) {
            return $result;
        }

        if ($columnId == 4 || $columnPid == 4) {
            // 款式栏目标签跳单张
            $params = $params ? $params . '-dis_1' : 'dis_1';
        }
        $aLabels = $this->_getLabelsInfo($tableName, $id, $params, $columnId);

        // 全部的时候取子栏目链接
        if ($columnPid === 0) {
            $aLabels['col'][] = [
                'id' => $colIdLink,
                'name' => GetCategory::getOtherFromColId($colIdLink, 'sName'),
                'dLink' => '',
                'lLink' => $this->common_model->getLink($colIdLink, $params)
            ];
        }

        foreach ($labelKeys as $labelKey) {
            if (key_exists($labelKey, $aLabels)) {
                $result = array_merge($result, $aLabels[$labelKey]);
            }
        }

        $this->benchmark->mark('DetailsModelGetLabelInfoEnd');
        return $result;
    }


    /**
     * @param string $tableName 真表名
     * @param $id
     * @param string|array $params
     * @param int|string $columnId 全部传主栏目id
     * @return array
     */
    private function _getLabelsInfo($tableName, $id, $params, $columnId)
    {
        // 生成关键词用的param，如果是款式页则默认到单张
        $keyParams = stristr($params, 'dis_1') ? 'dis_1' : '';
        $this->benchmark->mark('_DetailsModelGetLabelsInfo');
        if (empty($id) || empty($tableName)) return [];

        $tableName = strtolower($tableName);
        $_info = OpPopFashionMerger::getProductData($id, $tableName);
        $info = $_info[$id];

        $aLabels = [];
        switch ($tableName) {
            case 'product_perform'://秀场提炼
                // 性别
                $sGenderName = GetCategory::getNewEnName(1, $info['typ']);
                $iGender = current(GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnId));
                // 季节
                $sSeasonName = GetCategory::getNewEnName(5, $info['for_date']);
                $iSeason = current(getCategory::getIdsFrom(5, "sOriginalName,{$sSeasonName}"));
                // 地区
                $iRegion = GetCategory::getRegionIdFromEnName($info['region']);
                $sRegionName = GetCategory::getFieldFromId($iRegion);
                // 品牌
                $iBrand = $info['brand_tid'];
                $sBrandName = GetCategory::getBrandOtherFormId($iBrand, 'en_cn_brand');
                // 单品
                $iCategory = $info['iCategory'];
                // 品名
                $iSubCategory = $info['iSubcategory'];
                // 行业
                $iIndustry = $info['iIndustry'];

                // 色系
                $this->load->model('styles_model');
                $aColor = OpPopFashionMerger::getAutoMatchingColor($tableName, $id);
                $aAssortColor = [];
                $aAssortColor[] = $aColor['iFirstColorFirstLevel'];
                $aAssortColor[] = $aColor['iFirstColorSecondLevel'];
                if ($aAssortColor) {
                    $aAssortColor = array_filter($aAssortColor);
                    foreach ($aAssortColor as $_id) {
                        $_name = $this->styles_model->getColorDict($_id, 'sAssortColor')['sName'];
                        $aLabels['aco'][] = [
                            'id' => $_id,
                            'name' => $_name,
                            'lLink' => $this->common_model->getLink($columnId, $params, 'aco', $_id, true, 'anchor'),
                            'dLink' => $this->common_model->getLink($columnId, '', 'aco', $_id, true, 'anchor') // 色系链接跳筛选
                        ];
                    }
                }
                break;
            case 'product_marketphoto_item'://商场爆款
                // 性别
                $sGenderName = GetCategory::getNewEnName(1, $info['typ']);
                $iGender = current(GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnId));
                // 季节
                $sSeasonName = GetCategory::getNewEnName(5, $info['for_date']);
                $iSeason = current(getCategory::getIdsFrom(5, "sOriginalName,{$sSeasonName}"));
                if ($columnId == 54 || $columnId == 56) {
                    $_seasonInfo = OpPopFashionMerger::getSimilarId($info['iSeason']);
                    $iSeason = !empty($_seasonInfo['sOwnChildId']) ? $_seasonInfo['sOwnChildId'] : $info['iSeason'];
                }
                // 地区
                $iRegion = GetCategory::getRegionIdFromEnName($info['region']);
                $sRegionName = GetCategory::getFieldFromId($iRegion);
                // 单品
                $iCategory = $info['iCategory'];
                // 品名
                $iSubCategory = $info['iSubcategory'];
                // 行业
                $iIndustry = $info['iIndustry'];
                // 品牌
                $iBrand = $info['brand_tid'];
                $sBrandName = GetCategory::getBrandOtherFormId($iBrand, 'en_cn_brand');


                // 子tab（即：全球实拍的需要显示零售、批发、展会）1-批发市场 2-零售市场 3-展会图库
                $subTabName = $this->getMarketName($info['source'], $iDataSource);
                $iDataSourceName = ($iDataSource == 1) ? '批发市场' : (($iDataSource == 2) ? '零售市场' : '展会图库');

                if ($iDataSource == 3) {
                    $iExhibition = $info['source'];
                    $sExhibitionName = $subTabName;
                }
                if ($iDataSource == 1) {
                    $iMarket = $info['source'];
                    $sMarketName = $subTabName;
                }
                if ($iMarket) {
                    $aLabels['mar'][] = [
                        'id' => $iMarket,
                        'name' => $sMarketName,
                        'lLink' => $this->common_model->getLink($columnId, $params, 'mar', $iMarket, true, 'anchor'),
                        'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $sMarketName, true, 'anchor')
                    ];
                }

                $columnPid = (int)GetCategory::getOtherFromColId($columnId, 'iColumnPid');

                if ($columnPid === 0) {
                    if ($iDataSource) {
                        $aLabels['ds'][] = [
                            'id' => $iDataSource,
                            'name' => $iDataSourceName,
                            'lLink' => $this->common_model->getLink(54, $params, 'ds', $iDataSource, true, 'anchor'),
                            'dLink' => $this->common_model->getLink(54, $keyParams, 'ds', $iDataSource, true, 'anchor')
                        ];
                    }
                }
                break;
            case 'product_brand'://名牌高清
            case 'product_style_graphic'://款式流行
            case 'product_style_graphic_china'://国内市场
                // 性别
                $sGenderName = GetCategory::getNewEnName(1, $info['typ']);
                $iGender = current(GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnId));
                // 季节
                $sSeasonName = GetCategory::getNewEnName(5, $info['for_date']);
                $iSeason = current(getCategory::getIdsFrom(5, "sOriginalName,{$sSeasonName}"));
                // 地区
                if ($info['iRegion']) {
                    $iRegion = GetCategory::getRegionIdFromEnName($info['iRegion']);
                    $sRegionName = GetCategory::getFieldFromId($iRegion);
                } else {
                    $brand_tid = $info['brand_tid'];
                    if ($brand_tid) {
                        $brandData = OpPopFashionMerger::getBrandData($brand_tid);    // 通过品牌id获取地区id
                        if (isset($brandData["sRegion"]) && !empty($brandData["sRegion"])) {
                            $iRegion = $brandData["sRegion"];
                            $sRegionName = GetCategory::getFieldFromId($iRegion);
                        }
                    }
                }
                // 单品
                $iCategory = $info['iCategory'];
                // 品名
                $iSubCategory = $info['iSubcategory'];
                // 行业
                $iIndustry = $info['iIndustry'];
                // 品牌
                $iBrand = $info['brand_tid'];
                $sBrandName = GetCategory::getBrandOtherFormId($iBrand, 'en_cn_brand');
                break;
            case 'product_streetitem'://街头时尚
                // 性别
                $sGenderName = GetCategory::getNewEnName(1, $info['typ']);
                $iGender = current(GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnId));
                // 季节
                $sSeasonName = GetCategory::getNewEnName(5, $info['for_date']);
                $iSeason = current(getCategory::getIdsFrom(5, "sOriginalName,{$sSeasonName}"));
                if ($columnId == 54 || $columnId == 56) {
                    $_seasonInfo = OpPopFashionMerger::getSimilarId($info['iSeason']);
                    $iSeason = !empty($_seasonInfo['sOwnChildId']) ? $_seasonInfo['sOwnChildId'] : $info['iSeason'];
                }
                // 地区
                $iRegion = GetCategory::getRegionIdFromEnName($info['region']);
                $sRegionName = GetCategory::getFieldFromId($iRegion);
                // 单品
                $iCategory = $info['iCategory'];
                // 品名
                $iSubCategory = $info['iSubcategory'];
                // 行业
                $iIndustry = $info['iIndustry'];
                // 品牌
                $iBrand = $info['iBrand'];
                $sBrandName = OpPopFashionMerger::getBrandData($iBrand)['name'];
                break;
            case 'product_ordermeeting'://订货会
                // 性别
                $sGenderName = GetCategory::getNewEnName(1, $info['gender']);
                $iGender = current(GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['gender'], $columnId));
                // 季节
                $sSeasonName = GetCategory::getNewEnName(5, $info['for_date']);
                $iSeason = current(getCategory::getIdsFrom(5, "sOriginalName,{$sSeasonName}"));
                // 地区
                if ($info['iRegion']) {
                    $iRegion = $info['iRegion'];
                    $sRegionName = GetCategory::getFieldFromId($iRegion);
                } else {
                    $brand_tid = $info['brand_tid'];
                    if ($brand_tid) {
                        $brandData = OpPopFashionMerger::getBrandData($brand_tid);    // 通过品牌id获取地区id
                        if (isset($brandData["sRegion"]) && !empty($brandData["sRegion"])) {
                            $iRegion = $brandData["sRegion"];
                            $sRegionName = GetCategory::getFieldFromId($iRegion);
                        }
                    }
                }
                // 单品
                $iCategory = $info['iCategory'];
                // 品名
                $iSubCategory = $info['iSubcategory'];
                // 行业
                $iIndustry = $info['iIndustry'];
                // 品牌
                $iBrand = $info['brand_tid'];
                $sBrandName = OpPopFashionMerger::getBrandData($iBrand)['name'];
                // 手稿图
                $iStyleOrManuscript = $info['iStyleOrManuscript'];
                if ($iStyleOrManuscript) {
                    $aLabels['prop'][] = [
                        'id' => $iStyleOrManuscript,
                        'name' => $iStyleOrManuscript == 1 ? '款式图' : '手稿图',
                        'lLink' => $this->common_model->getLink($columnId, $params, 'prop', $iStyleOrManuscript, true, 'anchor'),
                        'dLink' => $this->common_model->getLink($columnId, $keyParams, 'prop', $iStyleOrManuscript, true, 'anchor')
                    ];
                }
                break;
            case 'product_templateitem'://设计模板
                // 性别
                $sGenderName = GetCategory::getNewEnName(1, $info['typ']);
                $iGender = current(GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnId));
                // 单品
                $iCategory = $info['iCategory'];
                // 行业
                $iIndustry = $info['iIndustry'];
                break;
            case 'product_style_detail'://款式细节
                // 性别
                $sGenderName = GetCategory::getNewEnName(1, $info['typ']);
                $iGender = current(GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnId));
                // 款式部位
                $iStylePart = $info['iStylePart'];
                $sStylePartName = GetCategory::getOtherFromIds($iStylePart, ['sName']);
                if ($iStylePart) {
                    $aLabels['par'][] = [
                        'id' => $iStylePart,
                        'name' => $sStylePartName,
                        'lLink' => $this->common_model->getLink($columnId, $params, 'par', $iStylePart, true, 'anchor'),
                        'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $sStylePartName, true, 'anchor')
                    ];
                }
                // 单品
                $iCategory = $info['iCategory'];
                // 行业
                $iIndustry = $info['iIndustry'];
                break;
            case 'product_graphicitem'://图案素材
                // 性别
                $aGender = explode(',', $info['sGender']);
                // 图案内容
                $aPatternContent = explode(',', $info['sPatternContent']);
                // 格式--矢量/位图
                $aFormat = explode(',', $info['sFormat']);
                // 图案应用--局部/满身
                $aApplication = explode(',', $info['sApplication']);
                // 地区
                $iRegion = GetCategory::getRegionIdFromEnName($info['iRegion']);
                $sRegionName = GetCategory::getFieldFromId($iRegion);
                // 品牌
                $iBrand = $info['iBrandId'];
                $sBrandName = OpPopFashionMerger::getBrandData($info['iBrandId'])['name'];
                break;
            case 'product_graphic_craft_item'://图案工艺
                $aPatternCraft = explode(',', $info['sPatternCraft']);
                break;
            case 'product_fabricgallery_item'://面料图库
                // 性别
                $aGender = explode(',', $info['sGender']);
                // 品牌
                $iBrand = $info['iBrandId'];
                $sBrandName = OpPopFashionMerger::getBrandData($iBrand)['name'];
                // 展会名称
                if ($info['iExhibitionName'] != 3488) {
                    $iExhibition = $info['iExhibitionName'];
                    $sExhibitionName = $this->getMarketName($iExhibition);
                }
                // 面料工艺
                $aFabricCraft = explode(',', $info['sFabricCraft']);
                // 面料材质
                $aMaterial = explode(',', $info['sMaterial']);
                // 图案内容
                $aPatternContent = explode(',', $info['sPatternContent']);
                break;
            case 'product_hat':
                // 季节
                $sSeasonName = GetCategory::getNewEnName(5, $info['for_date']);
                $iSeason = current(getCategory::getIdsFrom(5, "sOriginalName,{$sSeasonName}"));
                // 单品
                $iCategory = $info['iCategory'];
                // 地区
                $iRegion = $info['iRegion'];
                $sRegionName = GetCategory::getFieldFromId($iRegion);
                break;
            case 'product_wrapsitem':
                // 季节
                $sSeasonName = GetCategory::getNewEnName(5, $info['for_date']);
                $iSeason = current(getCategory::getIdsFrom(5, "sOriginalName,{$sSeasonName}"));
                // 单品
                $iCategory = $info['iCategory'];
                // 地区
                $iRegion = $info['iRegion'];
                $sRegionName = GetCategory::getFieldFromId($iRegion);
                break;
            case 'product_glove':
                // 季节
                $sSeasonName = GetCategory::getNewEnName(5, $info['for_date']);
                $iSeason = current(getCategory::getIdsFrom(5, "sOriginalName,{$sSeasonName}"));
                // 地区
                $iRegion = $info['iRegion'];
                $sRegionName = GetCategory::getFieldFromId($iRegion);
                // 单品
                $iCategory = $info['iCategory'];
                break;
            case 'product_docerationitem':
                // 季节
                $sSeasonName = GetCategory::getNewEnName(5, $info['for_date']);
                $iSeason = current(getCategory::getIdsFrom(5, "sOriginalName,{$sSeasonName}"));
                // 地区
                $iRegion = $info['iRegion'];
                $sRegionName = GetCategory::getFieldFromId($iRegion);
                // 单品
                $iCategory = $info['iCategory'];
                break;
            case 'product_shopset':
                // 性别
                $iGender = OpPopFashionMerger::getNewDataByOld($info['attribute'], $columnId)['s'];
                $sGenderName = GetCategory::getOtherFromIds($iGender, ['sName']);;
                // 季节
                $iSeason = $info['iSeason'];
                $sSeasonName = GetCategory::getOtherFromIds($iSeason, ['sName']);
                // 地区
                $iRegion = $info['iRegion'];
                $sRegionName = GetCategory::getFieldFromId($iRegion);
                // 品牌
                $iBrand = $info['brand_tid'];
                $sBrandName = GetCategory::getBrandOtherFormId($iBrand, 'en_cn_brand');
                break;
            case 'product_presscon_details':
                // 性别
                $iGender = $info['sGender'];
                $sGenderName = GetCategory::getOtherFromIds($iGender, ['sName']);
                // 季节
                $iSeason = $info['iSeason'];
                $sSeasonName = GetCategory::getOtherFromIds($iSeason, ['sName']);
                // 地区
                $iRegion = $info['iRegion'];
                $sRegionName = GetCategory::getFieldFromId($iRegion);
                // 品牌
                $iBrand = $info['brand_tid'];
                $sBrandName = GetCategory::getBrandOtherFormId($iBrand, 'en_cn_brand');
                // 行业
                $iIndustry = $info['iIndustry'];
                break;
            case 'product_brochures_photo':
                // 有些属性存储在主表中
                $info_p = OpPopFashionMerger::getProductData($info['borochid'], 'product_brochures');
                $info_p = $info_p[$info['borochid']];

                // 季节
                $sSeasonName = GetCategory::getNewEnName(5, $info['for_date']);
                $iSeason = current(getCategory::getIdsFrom(5, "sOriginalName,{$sSeasonName}"));
                // 地区
                $iRegion = GetCategory::getRegionIdFromEnName($info['region']);
                $sRegionName = GetCategory::getFieldFromId($iRegion);
                // 品牌
                $iBrand = $info['brand_tid'];
                $sBrandName = GetCategory::getBrandOtherFormId($iBrand, 'en_cn_brand');
                // 性别
                $aGender = explode(',', $info_p['iGender']);
                // 单品
                $aCategory = explode(',', $info_p['iCategory']);
                // 行业
                $aIndustry = explode(',', $info_p['iIndustry']);
                break;
            case 'product_design_refrence_details':
                // 有些属性存储在主表中
                $info_p = OpPopFashionMerger::getProductData($info['pid'], 'product_design_refrence');
                $info_p = $info_p[$info['pid']];

                // 性别
                $sGenderName = GetCategory::getNewEnName(1, $info['typ']);
                $iGender = current(GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['typ'], $columnId));
                // 单品
                $iCategory = $info_p['iCategory'];
                // 行业
                $iIndustry = $info_p['iIndustry'];
                break;
            case 'product_vector_refrence':
                // 性别
                $sGenderName = GetCategory::getNewEnName(1, $info['type']);
                $iGender = current(GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['type'], $columnId));
                // 单品
                $iCategory = $info['iCategory'];
                // 行业
                $iIndustry = $info['iIndustry'];
                break;
            case 'mostrend_content':
                // 性别
                $sGenderName = GetCategory::getNewEnName(1, $info['type']);
                $iGender = current(GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['type'], $columnId));
                // 季节
                $sSeasonName = GetCategory::getNewEnName(5, $info['popular_time']);
                $iSeason = current(getCategory::getIdsFrom(5, 'sOriginalName,' . $sSeasonName));
                // 线下和街拍栏目对季节 特殊处理
                if ($columnId == 54 || $columnId == 56) {
                    $_seasonInfo = OpPopFashionMerger::getSimilarId($info['iSeason']);
                    $iSeason = !empty($_seasonInfo['sOwnChildId']) ? $_seasonInfo['sOwnChildId'] : $info['iSeason'];
                }
                // 单品
                $iCategory = $info['iCategory'];
                // 品名
                $iSubCategory = $info['iSubcategory'];
                // 行业
                $iIndustry = $info['iIndustry'];
                // 地区
                $iRegion = $info['iRegion'];
                $sRegionName = GetCategory::getFieldFromId($iRegion);

                // 所有图片列表
                if ($info['channel'] == 'bookstore') {
                    // 性别
                    $aGender = explode(',', $info['sGender']);
                    // 单品
                    $aCategory = explode(',', $info['iCategory']);
                    // 行业
                    $aIndustry = explode(',', $info['iIndustry']);
                }
                break;
            case 'picture':
                // 性别
                $sGenderName = GetCategory::getNewEnName(1, $info['type']);
                $iGender = current(GetCategory::getIdsFrom(1, 'sOriginalName,' . $info['type'], $columnId));
                // 单品
                $aCategory = explode(',', $info['iCategory']);
                // 行业
                $aIndustry = explode(',', $info['iIndustry']);
                break;
            case 'product_tideleader':
                // 性别
                $iGender = $info['iType'];
                $sGenderName = GetCategory::getOtherFromIds($iGender, ['sName']);
                // 季节
                $iSeason = $info['iFordate'];
                $sSeasonName = GetCategory::getOtherFromIds($iSeason, ['sName']);
                // 单品
                $iCategory = $info['iCategory'];
                // 品名
                $iSubCategory = $info['iSubcategory'];
                // 行业
                $iIndustry = $info['iIndustry'];
                // 地区
                $iRegion = $info['iRegion'];
                $sRegionName = GetCategory::getFieldFromId($iRegion);
                // 明星达人
                $iStarLabel = $info['iStarLabel'];
                $sStarLabelName = OpPopFashionMerger::getOldCategoryLabel($iStarLabel)['cat_name'];
                if ($iStarLabel) {
                    $aLabels['sta'][] = [
                        'id' => $iStarLabel,
                        'name' => $sStarLabelName,
                        'lLink' => $this->common_model->getLink($columnId, $params, 'sta', $iStarLabel, true, 'anchor'),
                        'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $sStarLabelName, true, 'anchor')
                    ];
                }
                // 品牌
                // $iBrand = $info['iBrand'];
                // $sBrandName = OpPopFashionMerger::getBrandData($iBrand)['name'];
                break;
            case 't_digital_quick_print'://数码云打印
                // 图案内容
                $aPatternContent = explode(',', $info['sPatternContent']);
                // 图案应用--局部/满身
                $aApplication = explode(',', $info['sApplication']);
                // 内容提供商--公司名称
                $companyName = $info['sCompanyName'];
                if ($companyName) {
                    $aLabels['vendor'][] = [
                        'id' => 0,// 关键字查询，没有id
                        'name' => $companyName,
                        'lLink' => $this->common_model->getLink($columnId, $params, 'key', $companyName, true, 'anchor'),
                        'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $companyName, true, 'anchor')
                    ];
                }
                break;
        }


        if (!isset($columnPid)) {
            $columnPid = (int)GetCategory::getOtherFromColId($columnId, 'iColumnPid');
        }

        if (!in_array($columnId, [52, 57, 56]) && $columnPid == 4) {
            // 风格&元素
            $aMannerAndElementInfo = OpPopFashionMerger::getMannerAndElement($tableName, $id);
            // 风格
            $iManners = $aMannerAndElementInfo['sManner'];
            if (!empty($iManners)) {
                if (is_array($iManners)) {
                    foreach ($iManners as $iManner) {
                        $sMannerName = GetCategory::getOtherFromIds($iManner, [LANG_FIELD]);
                        if ($iManner) {
                            $aLabels['man'][] = [
                                'id' => $iManner,
                                'name' => $sMannerName,
                                'lLink' => $this->getLink($columnId, $params, 'man', $iManner, true, 'anchor'),
                                'dLink' => $this->getLink($columnId, $keyParams, 'key', $sMannerName, true, 'anchor')
                            ];
                        }
                    }
                } else {
                    $sMannerName = GetCategory::getOtherFromIds($iManner, [LANG_FIELD]);
                    if ($iManner) {
                        $aLabels['man'][] = [
                            'id' => $iManner,
                            'name' => $sMannerName,
                            'lLink' => $this->getLink($columnId, $params, 'man', $iManner, true, 'anchor'),
                            'dLink' => $this->getLink($columnId, $keyParams, 'key', $sMannerName, true, 'anchor')
                        ];
                    }
                }
            }
            // 辅料
            $aAccessory = explode(',', $aMannerAndElementInfo['sAccessory']);
            if ($aAccessory) {
                $aAccessory = array_filter($aAccessory);
                foreach ($aAccessory as $_id) {
                    $_name = GetCategory::getOtherFromIds($_id, ['sName']);
                    $aLabels['acc'][] = [
                        'id' => $_id,
                        'name' => $_name,
                        'lLink' => $this->common_model->getLink($columnId, $params, 'acc', $_id, true, 'anchor'),
                        'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $_name, true, 'anchor')
                    ];
                }
            }
            // 工艺
            $aTechnology = explode(',', $aMannerAndElementInfo['sTechnologys']);
            if ($aTechnology) {
                $aTechnology = array_filter($aTechnology);
                foreach ($aTechnology as $_id) {
                    $_name = GetCategory::getOtherFromIds($_id, ['sName']);
                    $aLabels['tech'][] = [
                        'id' => $_id,
                        'name' => $_name,
                        'lLink' => $this->common_model->getLink($columnId, $params, 'tech', $_id, true, 'anchor'),
                        'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $_name, true, 'anchor')
                    ];
                }
            }
            // 廓形
            $aShape = explode(',', $aMannerAndElementInfo['sShape']);
            if ($aShape) {
                $aShape = array_filter($aShape);
                foreach ($aShape as $_id) {
                    $_name = GetCategory::getOtherFromIds($_id, ['sName']);
                    $aLabels['shap'][] = [
                        'id' => $_id,
                        'name' => $_name,
                        'lLink' => $this->common_model->getLink($columnId, $params, 'shap', $_id, true, 'anchor'),
                        'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $_name, true, 'anchor')
                    ];
                }
            }
            // 面料
            $aFabric = explode(',', $aMannerAndElementInfo['sFabric']);
            if ($aFabric) {
                $aFabric = array_filter($aFabric);
                foreach ($aFabric as $_id) {
                    $_name = GetCategory::getOtherFromIds($_id, ['sName']);
                    $aLabels['fab'][] = [
                        'id' => $_id,
                        'name' => $_name,
                        'lLink' => $this->common_model->getLink($columnId, $params, 'fab', $_id, true, 'anchor'),
                        'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $_name, true, 'anchor')
                    ];
                }
            }
            // 图案
            $aPattern = explode(',', $aMannerAndElementInfo['sPattern']);
            if ($aPattern) {
                $aPattern = array_filter($aPattern);
                foreach ($aPattern as $_id) {
                    $_name = GetCategory::getOtherFromIds($_id, ['sName']);
                    $aLabels['pat'][] = [
                        'id' => $_id,
                        'name' => $_name,
                        'lLink' => $this->common_model->getLink($columnId, $params, 'pat', $_id, true, 'anchor'),
                        'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $_name, true, 'anchor')
                    ];
                }
            }
            // 细节
            $aSpecifics = explode(',', $aMannerAndElementInfo['sSpecifics']);
            if ($aSpecifics) {
                $aSpecifics = array_filter($aSpecifics);
                foreach ($aSpecifics as $_id) {
                    $_name = GetCategory::getOtherFromIds($_id, ['sName']);
                    $aLabels['spe'][] = [
                        'id' => $_id,
                        'name' => $_name,
                        'lLink' => $this->common_model->getLink($columnId, $params, 'spe', $_id, true, 'anchor'),
                        'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $_name, true, 'anchor')
                    ];
                }
            }
        }


        /**
         * 标签
         * 1、单选
         * 2、多选（已经处理为数组）
         */
        // 性别
        if ($iGender) {
            $aLabels['gen'][] = [
                'id' => $iGender,
                'name' => $sGenderName,
                'lLink' => $this->common_model->getLink($columnId, $params, 'gen', $iGender, true, 'anchor'),
                'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $sGenderName, true, 'anchor')
            ];
        }
        if ($aGender) {
            $aGender = array_filter($aGender);
            foreach ($aGender as $_id) {
                $_name = GetCategory::getOtherFromIds($_id, ['sName']);
                $aLabels['gen'][] = [
                    'id' => $_id,
                    'name' => $_name,
                    'lLink' => $this->common_model->getLink($columnId, $params, 'gen', $_id, true, 'anchor'),
                    'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $_name, true, 'anchor')
                ];
            }
        }
        // 季节
        if ($iSeason) {
            $aLabels['sea'][] = [
                'id' => $iSeason,
                'name' => $sSeasonName,
                'lLink' => $this->common_model->getLink($columnId, $params, 'sea', $iSeason, true, 'anchor'),
                'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $sSeasonName, true, 'anchor')
            ];
        }
        // 地区
        if ($iRegion) {
            $aLabels['reg'][] = [
                'id' => $iRegion,
                'name' => $sRegionName,
                'lLink' => $this->common_model->getLink($columnId, $params, 'reg', $iRegion, true, 'anchor'),
                'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $sRegionName, true, 'anchor')
            ];
        }
        // 品牌
        if ($iBrand) {
            $aLabels['bra'][] = [
                'id' => $iBrand,
                'name' => $sBrandName,
                'lLink' => $this->common_model->getLink($columnId, $params, 'bra', $iBrand, true, 'anchor'),
                'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $sBrandName, true, 'anchor')
            ];
        }
        // 单品&品名 (单选OR多选)
        if ($iCategory || $aCategory || $iSubCategory) {
            $aCategory[] = $iCategory;
            $aCategory[] = $iSubCategory;
            $aCategory = array_filter($aCategory);
            foreach ($aCategory as $_id) {
                $_name = trim(GetCategory::getOtherFromIds($_id, ['sName']));
                $aLabels['cat'][] = [
                    'id' => $_id,
                    'name' => $_name,
                    'lLink' => $this->common_model->getLink($columnId, $params, 'cat', $_id, true, 'anchor'),
                    'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $_name, true, 'anchor')
                ];
            }
        }
        // 行业
        if ($iIndustry || $aIndustry) {
            $aIndustry[] = $iIndustry;
            $aIndustry = array_filter($aIndustry);
            foreach ($aIndustry as $_id) {
                $_name = trim(GetCategory::getOtherFromIds($_id, ['sName']));
                $aLabels['ind'][] = [
                    'id' => $_id,
                    'name' => $_name,
                    'lLink' => $this->common_model->getLink($columnId, $params, 'ind', $_id, true, 'anchor'),
                    'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $_name, true, 'anchor')
                ];
            }
        }
        // 图案内容
        if ($aPatternContent) {
            $aPatternContent = array_filter($aPatternContent);
            foreach ($aPatternContent as $_id) {

                $_aPatternContents = $this->category_model->getAll('sPatternContent');
                $_name = $_aPatternContents[$_id];

                if (empty($_name) || $_name == '其他') continue;

                $aLabels['con'][] = [
                    'id' => $_id,
                    'name' => $_name,
                    'lLink' => $this->common_model->getLink($columnId, $params, 'con', $_id, true, 'anchor'),
                    'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $_name, true, 'anchor')
                ];
            }
        }
        // 矢量/位图
        if ($aFormat) {
            $aFormat = array_filter($aFormat);
            !isset($this->category_model) && $this->load->model('category_model');
            $aFormatList = $this->category_model->getAll('sPatternFormat');
            foreach ($aFormat as $_id) {
                if ($aFormatList && isset($aFormatList[$_id])) {
                    $_name = $aFormatList[$_id];
                } else {
                    continue;
                }
                $aLabels['for'][] = [
                    'id' => $_id,
                    'name' => $_name,
                    'lLink' => $this->common_model->getLink($columnId, $params, 'for', $_id, true, 'anchor'),
                    'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $_name, true, 'anchor')
                ];
            }
        }
        // 局部/满身
        if ($aApplication) {
            $aApplication = array_filter($aApplication);
            !isset($this->category_model) && $this->load->model('category_model');
            $aAppList = $this->category_model->getAll('sPatternUse');
            foreach ($aApplication as $_id) {
                $_name = $aAppList && isset($aAppList[$_id]) ? $aAppList[$_id] : '';
                $aLabels['use'][] = [
                    'id' => $_id,
                    'name' => $_name,
                    'lLink' => $this->common_model->getLink($columnId, $params, 'use', $_id, true, 'anchor'),
                    'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $_name, true, 'anchor')
                ];
            }
        }
        // 面料展
        if ($iExhibition) {
            $aLabels['exh'][] = [
                'id' => $iExhibition,
                'name' => $sExhibitionName,
                'lLink' => $this->common_model->getLink($columnId, $params, 'exh', $iExhibition, true, 'anchor'),
                'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $sExhibitionName, true, 'anchor')
            ];
        }
        // 图案工艺
        if ($aPatternCraft) {
            $aPatternCraft = array_filter($aPatternCraft);
            foreach ($aPatternCraft as $_id) {
                $_name = GetCategory::getOtherFromIds($_id, ['sName']);
                $aLabels['tec'][] = [
                    'id' => $_id,
                    'name' => $_name,
                    'lLink' => $this->common_model->getLink($columnId, $params, 'tec', $_id, true, 'anchor'),
                    'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $_name, true, 'anchor')
                ];
            }
        }
        // 面料工艺
        if ($aFabricCraft) {
            $aFabricCraft = array_filter($aFabricCraft);
            foreach ($aFabricCraft as $_id) {
                $_name = GetCategory::getOtherFromIds($_id, ['sName']);
                $aLabels['cra'][] = [
                    'id' => $_id,
                    'name' => $_name,
                    'lLink' => $this->common_model->getLink($columnId, $params, 'cra', $_id, true, 'anchor'),
                    'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $_name, true, 'anchor')
                ];
            }
        }
        // 面料材质
        if ($aMaterial) {
            $aMaterial = array_filter($aMaterial);
            foreach ($aMaterial as $_id) {
                $_name = GetCategory::getOtherFromIds($_id, ['sName']);
                $aLabels['mat'][] = [
                    'id' => $_id,
                    'name' => $_name,
                    'lLink' => $this->common_model->getLink($columnId, $params, 'mat', $_id, true, 'anchor'),
                    'dLink' => $this->common_model->getLink($columnId, $keyParams, 'key', $_name, true, 'anchor')
                ];
            }
        }
        $this->benchmark->mark('_DetailsModelGetLabelsInfoEnd');

        return $aLabels;
    }


    /**
     * 获取第三方来源信息
     * @param $source_text
     * @return string
     */
    public function _getSourceText($source_text)
    {
        $aSourceText = OpPopFree::getProductData($source_text);
        if ($aSourceText) {
            switch ($aSourceText['source']) {
                case 1 :
                    //采集
                    $sSourceText = '<a href="' . $aSourceText['source_text'] . '" style="color:#666;" target="_blank" title="' . $aSourceText['source_text'] . '">' . $aSourceText['source_text'] . '</a>';
                    break;
                case 2 :
                    //第三方
                    if ($aSourceText['link']) {
                        $sSourceText = '<a href="' . $aSourceText['link'] . '" style="color:#666;" title="' . $aSourceText['source_text'] . '" target="_blank">' . $aSourceText['source_text'] . '</a>';
                    } else {
                        $sSourceText = '<a href="javascript:void(0);" style="color:#666;" title="' . $aSourceText['source_text'] . '">' . $aSourceText['source_text'] . '</a>';
                    }
                    break;
                case 3 :
                    //分享
                default:
                    $sSourceText = '<a href="javascript:void(0);" style="color:#666;" title="' . $aSourceText['source_text'] . '">' . $aSourceText['source_text'] . '</a>';
                    break;
            }
        }

        return $sSourceText ? $sSourceText : '';
    }

    // 获取商场爆款款式图
    public function getMarketphotoDetailImage($tablename, $flag_text, $dir_name, $id, $columnid)
    {
        $sql = "SELECT * FROM $tablename WHERE flag_text=? and dir_name = ? and is_show=1 AND iStatus=1  order by flag desc,id asc";
        $result = $this->query($sql, [$flag_text, $dir_name]);
        $path = [];
        $img = [];
        $simpleName = getProductTableName($tablename);
        if (is_array($result)) {
            foreach ($result as $key => $val) {
                $path = getImagePath($tablename, $val);
                $img[$key]['smallPath'] = $path['smallPath'];
                $img[$key]['bigPath'] = $path['bigPath'];
                $img[$key]['simpleName'] = $simpleName;
                $img[$key]['id'] = $id;
                $img[$key]['columnid'] = $columnid;
            }
        }
        return $img;
    }

    // 获取名牌高清款式图
    public function getBrandDetailImage($tablename, $flag_text, $id, $columnid)
    {
        $sql = "SELECT * FROM $tablename WHERE flag_text=? AND iStatus=1 order by flag desc,id asc";
        $result = $this->query($sql, [$flag_text]);
        $path = [];
        $img = [];
        $simpleName = getProductTableName($tablename);
        if (is_array($result)) {
            foreach ($result as $key => $val) {
                $path = getImagePath($tablename, $val);
                $img[$key]['smallPath'] = $path['smallPath'];
                $img[$key]['bigPath'] = $path['bigPath'];
                $img[$key]['simpleName'] = $simpleName;
                $img[$key]['id'] = $id;
                $img[$key]['columnid'] = $columnid;
            }
        }
        return $img;
    }

    // 获取款式流行款式图
    public function getStyleGraphicDetailImage($tablename, $flag_text, $id, $columnid)
    {
        $sql = "SELECT * FROM product_style_graphic_details WHERE flag_text=? AND iStatus=1 order by flag desc,id asc";
        $result = $this->query($sql, [$flag_text]);
        $path = [];
        $img = [];
        $simpleName = getProductTableName($tablename);
        if (is_array($result)) {
            foreach ($result as $key => $val) {
                $path = getImagePath($tablename, $val);
                $img[$key]['smallPath'] = $path['smallPath'];
                $img[$key]['bigPath'] = $path['bigPath'];
                $img[$key]['simpleName'] = $simpleName;
                $img[$key]['id'] = $id;
                $img[$key]['columnid'] = $columnid;
            }
        }
        return $img;
    }

    // 获取国内市场款式图
    public function getStyleGraphicChinaDetailImage($tablename, $flag_text, $id, $columnid)
    {
        $this->benchmark->mark('getStyleGraphicChinaDetailImage');
        $sql = "SELECT * FROM $tablename WHERE flag_text=?  AND iStatus=1 order by flag desc,id asc";
        $result = $this->query($sql, [$flag_text]);
        $path = [];
        $img = [];
        $simpleName = getProductTableName($tablename);
        if (is_array($result)) {
            foreach ($result as $key => $val) {
                $path = getImagePath($tablename, $val);
                $img[$key]['smallPath'] = $path['smallPath'];
                $img[$key]['bigPath'] = $path['bigPath'];
                $img[$key]['simpleName'] = $simpleName;
                $img[$key]['id'] = $id;
                $img[$key]['columnid'] = $columnid;
            }
        }
        $this->benchmark->mark('getStyleGraphicChinaDetailImageEnd');
        return $img;
    }

    // 获取潮流解析款式图
    public function getDideleaderDetailImage($tablename, $flag_text, $id, $columnid)
    {
        $this->benchmark->mark('getDideleaderDetailImage');
        $sql = "SELECT * FROM $tablename WHERE sFlagText=?  AND iStatus=1 order by iFlag desc,id asc";
        $result = $this->query($sql, [$flag_text]);
        $path = [];
        $img = [];
        $simpleName = getProductTableName($tablename);
        if (is_array($result)) {
            foreach ($result as $key => $val) {
                $path = getImagePath($tablename, $val);
                $img[$key]['smallPath'] = $path['smallPath'];
                $img[$key]['bigPath'] = $path['bigPath'];
                $img[$key]['simpleName'] = $simpleName;
                $img[$key]['id'] = $id;
                $img[$key]['columnid'] = $columnid;
            }
        }
        $this->benchmark->mark('getDideleaderDetailImage');
        return $img;
    }

    // 获取面料图库细节图
    public function getFabricgalleryDetailImage($tablename, $related_flag, $id, $columnid)
    {
        $this->benchmark->mark('getFabricgalleryDetailImage');
        $sql = "SELECT * FROM $tablename WHERE related_flag=? order by id asc";
        $result = $this->query($sql, [$related_flag]);
        $path = [];
        $img = [];
        $simpleName = getProductTableName($tablename);
        if (is_array($result)) {
            foreach ($result as $key => $val) {
                $path = getImagePath($tablename, $val);
                $img[$key]['smallPath'] = $path['smallPath'];
                $img[$key]['bigPath'] = $path['bigPath'];
                $img[$key]['simpleName'] = $simpleName;
                $img[$key]['id'] = $id;
                $img[$key]['columnid'] = $columnid;
            }
        }
        $this->benchmark->mark('getFabricgalleryDetailImage');
        return $img;
    }

    // 获取关联标签及链接
    public function getRelationLabels($ids, $columnid)
    {
        $labels = explode(',', $ids);

        if (is_array($labels)) {
            foreach ($labels as $key => $val) {
                if ($this->getRelationLabelsName($val)) {
                    $_res[$this->getRelationLabelsName($val)] = $this->common_model->getLink($columnid, '', 'key', $this->getRelationLabelsName($val));
                }
            }
        }
        return $_res;
    }

    //获取款式站关联标签
    public function getStyleRelationLabels($id, $table)
    {
        $id = intval($id);
        $sql = 'SELECT sLabelsIds FROM `t_style_label` WHERE sTableName="' . $table . '" AND iPriId="' . $id . '"';
        $result = $this->query($sql);
        return $result[0]['sLabelsIds'];
    }

    // 获取关联标签名称
    public function getRelationLabelsName($id)
    {
        $id = intval($id);
        $sql = 'SELECT sLabelName FROM t_label_db WHERE iLabelsId="' . $id . '" AND iStatus=0 LIMIT 1';
        $result = $this->query($sql);
        return $result[0]['sLabelName'];

    }

    // 获取面料列表
    public function getFabricList($path, $refresh = false)
    {
        $this->benchmark->mark('getFabricList');
        $return = ['data' => [], 'url' => ''];
        if (empty($path)) {
            return $return;
        }
        $this->load->driver('cache');
        $Md5path = md5(strtolower($path));
        $memcahe_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_FABRICLIST_' . $Md5path;
        $memcache_res = $this->cache->memcached->get($memcahe_key);
        if (!empty($memcache_res) && !$refresh) {
            $return = $memcache_res;
        } else {
            // 调用面料API接口获取数据
            $url = 'http://api.uliaow.com/apiForPop/getMatchingFabrics.php';
            $ch = curl_init();
            $post = 'url=' . urlencode($path);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $ret = curl_exec($ch);
            $ret = json_decode($ret, true);
            curl_close($ch);
            if ($ret['success']) {
                $return = ['data' => $ret['data'], 'url' => $ret['more_url']];
                $this->cache->memcached->save($memcahe_key, $return, 28800);
            }
        }
        $this->benchmark->mark('getFabricListEnd');
        return $return;
    }

    //获取素材类下载信息
    public function getMaterialDown($data, $tablename)
    {
        $this->benchmark->mark('getMaterialDown');
        $basename = pathinfo($data['small_image_name'], PATHINFO_FILENAME);
        $sApppath = rtrim(APPPATH, '/');
        $fileArr = [];
        if ($tablename == 'product_graphicitem') {
            $geshiArr = ['eps', 'ai', 'cdr', 'psd'];
            if ($data['sPathDetails']) {
                $sPathDetails = json_decode($data['sPathDetails'], true);
                $key = 0;
                foreach ($sPathDetails as $_smallName => $_bigName) {
                    $basename = pathinfo($_smallName, PATHINFO_FILENAME);
                    $fileArr[$key]['sSmallName'] = $_smallName;
                    foreach ($geshiArr as $g) {
                        $file = $sApppath . $data['sPath'] . $g . '/' . $basename . '.' . $g;
                        $fileUpper = $sApppath . $data['sPath'] . $g . '/' . $basename . '.' . strtoupper($g);
                        if (api_file_exists($file)) {
                            //图片路径
                            $fileArr[$key]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $file));
                            //图片大小
                            $imageSize = api_getPicSize($file);
                            $fileArr[$key]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                            $fileArr[$key]['aDownInfo'][$g]['type'] = '--';
                        }
                        if (api_file_exists($fileUpper)) {
                            //图片路径
                            $fileArr[$key]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $fileUpper));
                            //图片大小
                            $imageSize = api_getPicSize($fileUpper);
                            $fileArr[$key]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                            $fileArr[$key]['aDownInfo'][$g]['type'] = '--';
                        }
                    }
                    //jpg格式
                    if (api_file_exists($sApppath . $data['sPath'] . 'hd/' . $_bigName)) {    //高清图存在则使用高清图
                        $sBigPath = $sApppath . $data['sPath'] . 'hd/' . $_bigName;
                    } else {
                        $sBigPath = $sApppath . $data['sPath'] . 'big/' . $_bigName;
                    }
                    $_ext = strtolower(pathinfo($_bigName, PATHINFO_EXTENSION));
                    //图片大小
                    $imageSize = api_getPicSize($sBigPath);
                    $fileArr[$key]['aDownInfo'][$_ext]['type'] = isset($imageSize['type']) && !empty($imageSize['type']) ? $imageSize['type'] : '--';
                    $fileArr[$key]['aDownInfo'][$_ext]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                    //图片路径
                    $fileArr[$key]['aDownInfo'][$_ext]['bp'] = urlencode(str_replace($sApppath, '', $sBigPath));
                    $key++;
                }
            }
        }
        if ($tablename == 'product_fabricgallery_item') {

            $detail_img = $this->getFabricgalleryDetailImage('product_fabricgallery_item_detail', $data['related_flag'], $data['id'], 82);
            $geshiArr = ['eps', 'ai', 'cdr', 'psd', 'bmp'];
            if ($detail_img) {
                //细节图存在
                foreach ($detail_img as $key => $_img) {
                    $afileInfo = pathinfo($_img['smallPath']);
                    $basename = $afileInfo['filename'];
                    $fileArr[$key]['sSmallName'] = $afileInfo['basename'];//小图名称
                    $sBigName = pathinfo($_img['bigPath'], PATHINFO_BASENAME);
                    foreach ($geshiArr as $g) {
                        $file = $sApppath . '/fashion/fabricgallery/' . $data['dir_name'] . '/' . $data['category'] . '/' . $data['sub_category'] . '/' . $g . '/' . $basename . '.' . $g;
                        $fileUpper = $sApppath . '/fashion/fabricgallery/' . $data['dir_name'] . '/' . $data['category'] . '/' . $data['sub_category'] . '/' . $g . '/' . $basename . '.' . strtoupper($g);
                        if (api_file_exists($file)) {
                            //图片路径
                            $fileArr[$key]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $file));
                            //图片大小
                            $imageSize = api_getPicSize($file);
                            $fileArr[$key]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                            $fileArr[$key]['aDownInfo'][$g]['type'] = '--';
                        }
                        if (api_file_exists($fileUpper)) {
                            //图片路径
                            $fileArr[$key]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $fileUpper));
                            //图片大小
                            $imageSize = api_getPicSize($fileUpper);
                            $fileArr[$key]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                            $fileArr[$key]['aDownInfo'][$g]['type'] = '--';
                        }
                    }
                    //图片大小
                    $sBigPath = '/fashion/fabricgallery/' . $data['dir_name'] . '/' . $data['category'] . '/' . $data['sub_category'] . '/big/' . $sBigName;
                    $imageSize = api_getPicSize($sApppath . $sBigPath);
                    $_ext = strtolower(pathinfo($sBigName, PATHINFO_EXTENSION));
                    $fileArr[$key]['aDownInfo'][$_ext]['type'] = isset($imageSize['type']) && !empty($imageSize['type']) ? $imageSize['type'] : '--';
                    $fileArr[$key]['aDownInfo'][$_ext]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                    //图片路径
                    $fileArr[$key]['aDownInfo'][$_ext]['bp'] = urlencode($sBigPath);
                }
            } else {
                //只有主图
                $fileArr[0]['sSmallName'] = $data['small_image_name'];
                foreach ($geshiArr as $g) {
                    $file = $sApppath . '/fashion/fabricgallery/' . $data['dir_name'] . '/' . $data['category'] . '/' . $data['sub_category'] . '/' . $g . '/' . $basename . '.' . $g;
                    $fileUpper = $sApppath . '/fashion/fabricgallery/' . $data['dir_name'] . '/' . $data['category'] . '/' . $data['sub_category'] . '/' . $g . '/' . $basename . '.' . strtoupper($g);
                    if (api_file_exists($file)) {
                        //图片路径
                        $fileArr[0]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $file));
                        //图片大小
                        $imageSize = api_getPicSize($file);
                        $fileArr[0]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                        $fileArr[0]['aDownInfo'][$g]['type'] = '--';
                    }
                    if (api_file_exists($fileUpper)) {
                        //图片路径
                        $fileArr[0]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $fileUpper));
                        //图片大小
                        $imageSize = api_getPicSize($fileUpper);
                        $fileArr[0]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                        $fileArr[0]['aDownInfo'][$g]['type'] = '--';
                    }
                }
                $path = getImagePath($tablename, $data, $sApppath);
                //图片大小
                $imageSize = api_getPicSize($path['bigPath']);
                $_ext = strtolower(pathinfo($path['bigPath'], PATHINFO_EXTENSION));
                $fileArr[0]['aDownInfo'][$_ext]['type'] = isset($imageSize['type']) && !empty($imageSize['type']) ? $imageSize['type'] : '--';
                $fileArr[0]['aDownInfo'][$_ext]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                //图片路径
                $fileArr[0]['aDownInfo'][$_ext]['bp'] = urlencode(str_replace($sApppath, '', $path['bigPath']));
            }
        }
        if ($tablename == 'product_templateitem') {

            $geshiArr = ['eps', 'ai', 'cdr'];
            $fileArr[0]['sSmallName'] = $data['small_image_name'];
            foreach ($geshiArr as $g) {
                $file = $sApppath . '/fashion/template/' . $data['dir_name'] . '/' . $data['typ'] . '/' . $data['category'] . '/' . $g . '/' . $basename . '.' . $g;
                $fileUpper = $sApppath . '/fashion/template/' . $data['dir_name'] . '/' . $data['typ'] . '/' . $data['category'] . '/' . $g . '/' . $basename . '.' . strtoupper($g);

                if (api_file_exists($file)) {
                    //图片路径
                    $fileArr[0]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $file));
                    //图片大小
                    $imageSize = api_getPicSize($file);
                    $fileArr[0]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                    $fileArr[0]['aDownInfo'][$g]['type'] = '--';
                }
                if (api_file_exists($fileUpper)) {
                    //图片路径
                    $fileArr[0]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $fileUpper));
                    //图片大小
                    $imageSize = api_getPicSize($fileUpper);
                    $fileArr[0]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                    $fileArr[0]['aDownInfo'][$g]['type'] = '--';
                }
            }
            $path = getImagePath($tablename, $data, $sApppath);
            //图片大小
            $imageSize = api_getPicSize($path['bigPath']);
            $_ext = strtolower(pathinfo($path['bigPath'], PATHINFO_EXTENSION));
            $fileArr[0]['aDownInfo'][$_ext]['type'] = isset($imageSize['type']) && !empty($imageSize['type']) ? $imageSize['type'] : '--';
            $fileArr[0]['aDownInfo'][$_ext]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
            //图片路径
            $fileArr[0]['aDownInfo'][$_ext]['bp'] = urlencode(str_replace($sApppath, '', $path['bigPath']));
        }
        if ($tablename == 'picture') {
            $fileArr[0]['sSmallName'] = $data['pic'];
            if ($data['ai']) {
                $fileArr[0]['aDownInfo']['ai']['bp'] = urlencode($data['path'] . $data['ai']);
                $imageSize = api_getPicSize($sApppath . $fileArr['bp']);
                $fileArr[0]['aDownInfo']['ai']['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                $fileArr[0]['aDownInfo']['ai']['type'] = '--';
            }
            if ($data['cdr']) {
                $fileArr[0]['aDownInfo']['cdr']['bp'] = urlencode($data['path'] . $data['cdr']);
                $imageSize = api_getPicSize($sApppath . $fileArr['bp']);
                $fileArr[0]['aDownInfo']['cdr']['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                $fileArr[0]['aDownInfo']['cdr']['type'] = '--';
            }
            if ($data['psd']) {
                $fileArr[0]['aDownInfo']['psd']['bp'] = urlencode($data['path'] . $data['psd']);
                $imageSize = api_getPicSize($sApppath . $fileArr['bp']);
                $fileArr[0]['aDownInfo']['psd']['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                $fileArr[0]['aDownInfo']['psd']['type'] = '--';
            }
            $path = getImagePath($tablename, $data, $sApppath);
            //图片大小
            $imageSize = api_getPicSize($path['bigPath']);

            $fileArr[0]['aDownInfo']['jpg']['type'] = isset($imageSize['type']) && !empty($imageSize['type']) ? $imageSize['type'] : '--';
            $fileArr[0]['aDownInfo']['jpg']['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
            //图片路径
            $fileArr[0]['aDownInfo']['jpg']['bp'] = urlencode(str_replace($sApppath, '', $path['bigPath']));


        }
        if ($tablename == 'product_vector_refrence') {
            $geshiArr = ['eps', 'ai', 'psd', 'rar', 'zip'];
            $fileArr[0]['sSmallName'] = $data['imgname'];
            $basename = pathinfo($data['imgname'], PATHINFO_FILENAME);
            foreach ($geshiArr as $g) {
                $file = $sApppath . '/fashion/vectorrefrence/' . $data['dir_name'] . '/' . $data['type'] . '/' . $data['for_date'] . '/' . $data['category'] . '/' . $g . '/' . $basename . '.' . $g;
                $fileUpper = $sApppath . '/fashion/vectorrefrence/' . $data['dir_name'] . '/' . $data['type'] . '/' . $data['for_date'] . '/' . $data['category'] . '/' . $g . '/' . $basename . '.' . strtoupper($g);
                if (api_file_exists($file)) {
                    //图片路径
                    $fileArr[0]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $file));
                    //图片大小
                    $imageSize = api_getPicSize($file);
                    $fileArr[0]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                    $fileArr[0]['aDownInfo'][$g]['type'] = '--';
                }
                if (api_file_exists($fileUpper)) {
                    //图片路径
                    $fileArr[0]['aDownInfo'][$g]['bp'] = urlencode(str_replace($sApppath, '', $fileUpper));
                    //图片大小
                    $imageSize = api_getPicSize($fileUpper);
                    $fileArr[0]['aDownInfo'][$g]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                    $fileArr[0]['aDownInfo'][$g]['type'] = '--';
                }
            }
            $path = getImagePath($tablename, $data, $sApppath);
            //图片大小
            $imageSize = api_getPicSize($path['bigPath']);
            $_ext = strtolower(pathinfo($path['bigPath'], PATHINFO_EXTENSION));
            $fileArr[0]['aDownInfo'][$_ext]['type'] = isset($imageSize['type']) && !empty($imageSize['type']) ? $imageSize['type'] : '--';
            $fileArr[0]['aDownInfo'][$_ext]['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
            //图片路径
            $fileArr[0]['aDownInfo'][$_ext]['bp'] = urlencode(str_replace($sApppath, '', $path['bigPath']));
        }
        if ($tablename == 't_digital_quick_print') {//数码云打印
            $geshiArr = ['eps', 'ai', 'cdr', 'psd'];
            if ($data['sPathDetails']) {
                $sPathDetails = json_decode($data['sPathDetails'], true);
                $key = 0;
                foreach ($sPathDetails as $_smallName => $_bigName) {
                    $basename = pathinfo($_smallName, PATHINFO_FILENAME);
                    $fileArr[$key]['sSmallName'] = $_smallName;
//                    foreach($geshiArr as $g) {
//                        $file = $sApppath . $data[ 'sPath' ] . $g . '/' . $basename . '.' . $g;
//                        $fileUpper =  $sApppath . $data[ 'sPath' ] . $g.'/' . $basename . '.' . strtoupper($g);
//                        if (api_file_exists($file)) {
//                            //图片路径
//                            $fileArr[ $key ]['aDownInfo'][$g]['bp'] = urlencode( str_replace( $sApppath, '', $file ) );
//                            //图片大小
//                            $imageSize = api_getPicSize( $file );
//                            $fileArr[ $key ][ 'aDownInfo' ][$g]['size'] = isset( $imageSize['size'] ) && !empty( $imageSize['size'] ) ? $imageSize['size'] : '--';
//                            $fileArr[ $key ][ 'aDownInfo' ][$g]['type'] = '--';
//                        }
//                        if (api_file_exists($fileUpper)) {
//                            //图片路径
//                            $fileArr[ $key ][ 'aDownInfo' ][$g]['bp'] = urlencode( str_replace( $sApppath, '', $fileUpper ) );
//                            //图片大小
//                            $imageSize = api_getPicSize( $fileUpper );
//                            $fileArr[ $key ][ 'aDownInfo' ][$g]['size'] = isset( $imageSize['size'] ) && !empty( $imageSize['size'] ) ? $imageSize['size'] : '--';
//                            $fileArr[ $key ][ 'aDownInfo' ][$g]['type'] = '--';
//                        }
//                    }
                    //jpg格式
                    $sBigPath = $sApppath . $data['sPath'] . 'big/' . $_bigName;
                    //图片大小
                    $imageSize = api_getPicSize($sBigPath);
                    $fileArr[$key]['aDownInfo']['jpg']['type'] = isset($imageSize['type']) && !empty($imageSize['type']) ? $imageSize['type'] : '--';
                    $fileArr[$key]['aDownInfo']['jpg']['size'] = isset($imageSize['size']) && !empty($imageSize['size']) ? $imageSize['size'] : '--';
                    //图片路径
                    $fileArr[$key]['aDownInfo']['jpg']['bp'] = urlencode(str_replace($sApppath, '', $sBigPath));
                    $key++;
                }
            }
        }
        $this->benchmark->mark('getMaterialDownEnd');
        return json_encode($fileArr, true);
    }

    public function getPicSize($file)
    {
        if (file_exists($file)) {
            $this->benchmark->mark('getPicSize');
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if (strtolower($extension) == 'jpg') {
                //获取图片大小
                $imagesizeInfo = getimagesize($file);
                //获取图片尺寸
                if ($imagesizeInfo[0] && $imagesizeInfo[1]) {
                    $image['type'] = $imagesizeInfo[0] . '*' . $imagesizeInfo[1];
                }
            } else {
                $image['type'] = '';
            }

            $imagejpgSize = filesize($file);
            if ($imagejpgSize / 1024 / 1024 > 1) {
                $image['size'] = ceil($imagejpgSize / 1024 / 1024) . 'MB';
            } else {
                $image['size'] = ceil($imagejpgSize / 1024) . 'KB';
            }
            $this->benchmark->mark('getPicSizeEnd');
            return $image;

        } else {
            return [];
        }
    }

    /**
     * [getConditions 获取solr查询的condition条件]
     * @param          string    or array    $params     [url参数]
     * @param  integer $columnId [栏目id]
     * @return [array]  $conditions
     */
    public function getConditions($params = '', $columnId, $powers = [])
    {
        switch ($columnId) {
            //趋势
            case '1':
            case '20'://关注
            case '21'://企划
            case '22'://预测
            case '23'://前瞻
            case '125'://主题色彩
            case '126'://图案趋势
            case '127'://面辅料趋势
            case '128'://工艺趋势
            case '129'://廓形趋势
                $this->load->model('trends_model');
                $conditions = $this->trends_model->getConditions($params, $columnId, $powers);
                break;
            //潮流解析
            case '2':
            case '30'://T台分析
            case '31'://展会分析
            case '32'://订货会分析
            case '33'://批发
            case '132'://爆款数据
            case '34'://线上分析（电商）
            case '35'://线下分析（实体）
            case '36'://店铺形象
            case '37'://街拍分析
            case '38'://潮流领袖
            case '40'://行业报道
                $this->load->model('analysis_model');
                $conditions = $this->analysis_model->getConditions($params, $columnId, $powers);
                break;
            //款式
            case '4':
            case '50'://T台款式
            case '52'://订货会
            case '54'://线下（实体）
            case '55'://线上（电商）
            case '56'://街拍
            case '57'://潮流领袖
            case '122':
            case '123':
                $this->load->model('styles_model');
                $conditions = $this->styles_model->getConditions($params, $columnId, $powers);
                break;
            //T台发布
            case '3':
                $this->load->model('runways_model');
                $conditions = $this->runways_model->getConditions($params, $columnId, $powers);
                break;
            //手稿合辑
            case '6':
            case '70'://书店
            case '71'://lookbook
            case '72'://单品合集
            case '73'://矢量书稿
            case '113'://T台系列
            case '114'://品牌系列
            case '115'://快反应系列
            case '131'://订货会合辑
                $this->load->model('books_model');
                $conditions = $this->books_model->getConditions($params, $columnId, $powers);
                break;
            case '7':
            case '80'://款式模板（矢量图）
            case '81'://款式细节
            case '83'://面料
            case '84'://服饰品
            case '85'://店铺陈列
            case '117':// 面料图库
                $this->load->model('references_model');
                $conditions = $this->references_model->getConditions($params, $columnId, $powers);
                break;
            //灵感源
            case '8':
            case '90'://灵感报告
            case '91'://灵感图库
            case '116'://灵感图库
                $this->load->model('inspiration_model');
                $conditions = $this->inspiration_model->getConditions($params, $columnId, $powers);
                break;
            case '9':// 图案库
            case '82':// 图案素材
            case '120':// 大牌花型
            case '121':// 数码云打印
            case '124':// 图案工艺
                $this->load->model('patterns_model');
                $conditions = $this->patterns_model->getConditions($params, $columnId, $powers);
            default:
                # code...
                break;
        }
        return $conditions;
    }

    /**
     * [getLists 获取每页单张展示的数据+总条数]
     * @param  string $params [url参数]
     * @param  integer $columnId [栏目id]
     * @param  integer $offset [偏移量]
     * @param  integer $limit [每页条数]
     *
     * @param  string $source [来源，book为手稿合辑、个人中心共享资料-trends趋势手稿、vector矢量书稿]
     *
     * @return [array]   &$lists     [根据条件查询出的结果]
     * @return [array]   $totalCount [根据条件查询出的总条数]
     */
    public function getLists($params = '', $columnId, &$lists, $offset = 0, $limit = 10, $powers = array(), $iIsAuthorized = 0)
    {
        $this->benchmark->mark('getLists');
        if (!empty($params)) {
            $paramsArr = $this->common_model->parseParams($params);
        }
        $lists = [];
        $columnPid = GetCategory::getOtherFromColId($columnId);
        if ($columnPid == 0) {
            $columnPid = $columnId;
        }
        //条件
        $conditions = $this->getConditions($params, $columnId, $powers);

        //趋势-关注，主题类型,1=>图案主题,2=>款式主题
        if (isset($paramsArr['top']) && !empty($paramsArr['top'])) {
            if ($paramsArr['top'] == 1) {
                $conditions['tablename'] = 'specialtopic_graphic';
            } elseif ($paramsArr['top'] == 2) {
                $conditions['tablename'] = 'specialtopic';
            }
        }
        //是否权威分析
        if ($iIsAuthorized) {
            $conditions['iIsAuthorized'] = 1;
        }
        //排序
        $arSort = $this->common_model->getSort($params, $powers, $columnPid);

        $result = [];
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $arSort);
        foreach ($result as $key => $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];
            if ($tableName == 'product_vector_refrence_group') {
                $data = OpPopFashionMerger::getProductData($id, 'product_vector_refrence_list');
            } else {
                $data = OpPopFashionMerger::getProductData($id, $tableName);
            }
            $info = $data[$id];

            $lists[$key]['list'] = $info;
            $lists[$key]['columnId'] = $val['iColumnId'][1];
            $lists[$key]['tableName'] = getProductTableName($tableName);
        }
        $this->benchmark->mark('getListsEnd');
        return $totalCount;
    }

    public function getFabricMatchResult($sReportID, $iStatus = 0)
    {
        $tableName = "`fashion`.`t_report_fabric_rel`";
        if ($iStatus) {
            $sql = "SELECT * FROM {$tableName} WHERE `sReportID`=? AND `iStatus`=1";
        } else {
            $sql = "SELECT * FROM {$tableName} WHERE `sReportID`=?";
        }
        $res = $this->query($sql, $sReportID);
        return $res[0];
    }

    // 将用户试衣的的记录存入表t_3d_log中
    public function saveFittingLog($data = array())
    {
        $tableName = "`fashion`.`fm_3d_log`";
        $this->db()->insert($tableName, $data);
        $insertId = $this->db()->insert_id();
        return $insertId;
    }

    // 获取在线3D试衣分类
    public function getFittingCat()
    {
        $this->benchmark->mark('getFittingCat');
        $return = [];
        $this->load->driver('cache');
        $memcahe_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_20170118_FittingCategory';
        $memcache_res = $this->cache->memcached->get($memcahe_key);
        if (!empty($memcache_res) && !$_GET['refresh']) {
            $return = $memcache_res;
        } else {
            $ULBfabricLink = getUlbApiLink('fabricsUnification/matchingList');
            $opcurl = opcurl::getInstance($ULBfabricLink);
            $opcurl->createCurl();
            $res = $opcurl->__tostring();
            $res = json_decode($res, true);
            if ($res['code'] == 0) {
                $return = $res['data'];
            }
            $this->cache->memcached->save($memcahe_key, $return, 7200);
        }
        $this->benchmark->mark('getFittingCat');
        return $return;
    }

    // 获取在线3D试衣结果
    public function getFittingPic($pid, $pic, $random)
    {
        $this->benchmark->mark('getFittingPic');
        $return = [];
        $ULBfabricLink = getUlbApiLink('fabricsUnification/abbrFittingResultSet', ['iPid' => $pid, 'sUrl' => $pic, 'sFid' => md5($pic)]);
        $opcurl = opcurl::getInstance($ULBfabricLink);
        $opcurl->createCurl();
        $res = $opcurl->__tostring();
        $res = json_decode($res, true);
        if ($res['code'] == 0) {
            $return = $res['data'];
        }
        $this->benchmark->mark('getFittingPic');
        return $res;
    }

    /**
     * 获取供应商广告列表
     * @param $col 栏目id
     * @return array 广告列表
     */
    public function getVendorAdList($col)
    {
        $lists = [];
        //报告-款式栏目 新增大牌花型和数码快印需要显示行业资源推荐
        $cols = [1, 20, 21, 22, 23, 2, 30, 31, 32, 33, 34, 35, 36, 37, 38, 40, 4, 50, 52, 54, 55, 56, 57, 82, 120, 121, 122, 123, 132];
        if (!in_array($col, $cols)) {
            return $lists;
        }
        $curTime = date("Y-m-d H:i:s");

        $fields = '`id`,`sAdImageUrl`,`sAdLinkAddr`,`sCompanyName`';
        $sql = "SELECT {$fields} FROM `fashion`.`t_vendor_ad` WHERE `iStatus`=1 AND `dOnlineTime` < '{$curTime}' AND `dOffLineTime` > '{$curTime}' ORDER BY `iWeight` DESC";
        $lists = $this->query($sql);

        foreach ($lists as $key => $val) {
            $lists[$key]['sAdLinkAddr'] = '/statistics/vendoradlink/?url=' . base64_encode($val['id']) . '_' . base64_encode($val['sAdLinkAddr']);
            $lists[$key]['sAdImageUrl'] = STATIC_URL3 . $val['sAdImageUrl'];
        }

        return $lists;
    }

}