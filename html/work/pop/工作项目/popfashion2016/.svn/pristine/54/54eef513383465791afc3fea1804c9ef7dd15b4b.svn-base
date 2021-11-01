<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 视频栏目 专用model类
 * @property-read common_model $common_model
 */
class Video_model extends POP_Model
{
    const T_FASHION_PRODUCT_VIDEO = "`fashion`.`product_video`";// 视频专栏表

    // 116-灵感视频（主题灵感，取全部）| 3-T台（发布会--1）|71-广告大片（lookbook--2）
    public static $columnPages = [3 => 20, 71 => 21, 116 => 20];

    private $video_have = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'runways_model', 'statistics_model', 'details_model']);
    }

    // 获取列表
    public function ajaxList($columnId, $params, $isAjax = true)
    {
        $lists = $info = [];
        $totalCount = $this->getVideoLists($columnId, $params, $lists);
        switch ($columnId) {
            case 3:
            case 71:
                if ($isAjax) {
                    // 异步--更多
                    $info['more_url'] = $this->getMoreUrl($columnId, $params, $isAjax);
                } else {
                    // 同步--更多
                    $info['more_url'] = $this->getMoreUrl($columnId);
                }
                break;
        }
        $info['totalCount'] = intval($totalCount);
        if ($isAjax) {
            $getJsonOutput = $this->getJsonOutputObj();
            $getJsonOutput->code(0)->data($lists)->msg('ok')->info($info)->out();
        }
        return [$lists, $info];
    }


    // 获取视频专栏的列表 $columnId -- 具体栏目的栏目ID
    protected function getVideoLists($columnId, $params, &$lists)
    {
        $lists = [];
        // 获取条件
        $conditions = $this->getConditions($columnId, $params);
        // 获取排序
        if (in_array($columnId, array(3, 71))) {
            $arSort = ['dPublishTime' => 'DESC', 'pri_id' => 'DESC'];
        } elseif ($columnId == 116) {
            $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        }
        // 获取数据
        $result = $rows = [];
        $limit = in_array($columnId, array_keys(self::$columnPages)) ? self::$columnPages[$columnId] : 1;
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, 0, $limit, $arSort);

        foreach ($result as $key => $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];
            $data = OpPopFashionMerger::getProductData($id, $tableName);
            $info = $data[$id];
            // 是否有关联id
            $solr_relation_pop_id = !empty($info['pop_id']) ? trim($info['pop_id']) : '';
            switch ($columnId) {
                case 3:// 发布会(T台)
                case 71:// 广告大片
                    // 获取关联的相关信息
                    if (!empty($solr_relation_pop_id)) {
                        $relationData = $this->getRealTbaleId($solr_relation_pop_id);
                        if (empty($relationData)) {
                            continue;
                        }
                        $relation_real_table = $relationData['relation_real_table'];
                        $relation_pop_id = $relationData['relation_pop_id'];
                        // 获取 发布会/广告大片的数据库数据
                        $relation_data = OpPopFashionMerger::getProductData($relation_pop_id, $relation_real_table);
                        $relation = $relation_data[$relation_pop_id];
                        if (empty($relation)) {
                            continue;
                        }
                        if (!empty($relation) && isset($relation)) {
                            $rows['id'] = $relation_pop_id;
                            $rows['tablename'] = $relation_real_table;
                            $imgPath = getImagePath($relation_real_table, $relation);
                            switch ($columnId) {
                                case 3:
                                    // 性别
                                    $rows['genderName'] = $this->getAttrName($relation['sGender'], 'gen');
                                    // 地区名称
                                    $rows['regionName'] = $this->getAttrName($relation['iRegion'], 'reg');
                                    // 季节
                                    $rows['seasonName'] = $this->getAttrName($relation['iSeason'], 'sea');
                                    // 设计师（品牌）
                                    $rows['brand_name'] = $this->getAttrName($relation['brand_tid'], 'bra');
                                    // 发布时间
                                    $times = [];
                                    if (isset($relation['create_time_special']) && $relation['create_time_special']) {
                                        $times[] = strtotime($relation['create_time_special']);
                                    }
                                    if (isset($relation['create_time_focus']) && $relation['create_time_focus']) {
                                        $times[] = strtotime($relation['create_time_focus']);
                                    }
                                    if (isset($relation['create_time']) && $relation['create_time']) {
                                        $times[] = strtotime($relation['create_time']);
                                    }
                                    if (isset($relation['create_time_video']) && $relation['create_time_video']) {
                                        $times[] = strtotime($relation['create_time_video']);
                                    }
                                    $rows['tidbits_id'] = $info['tidbits_id'] ? $info['tidbits_id'] : '';
                                    $rows['video_id'] = $info['video_id'] ? $info['video_id'] : '';
                                    $rows['publish_time'] = $times ? date('Y-m-d', min($times)) : '';
                                    $rows['title'] = stripcslashes($relation['nme']);
                                    $rows['cover'] = $this->getRunwaySpecialCover($imgPath['cover']);
                                    $rows['video_tag'] = 1; // 不带img
                                    if (strpos($rows['cover'], '<img') !== false) {
                                        $rows['video_tag'] = 2; // 带img
                                    }
                                    $rows['view_count'] = $this->statistics_model->getViews($relation_real_table, $relation_pop_id, $relation);
                                    $rows['inside_url'] = "/runways/inside/id_{$relation_pop_id}/";
                                    break;
                                case 71:
                                    // 性别
                                    if (isset($relation['typ'])) {
                                        $genderId = GetCategory::getIdsFrom(1, "sOriginalName,{$relation['typ']}");
                                        if (is_array($genderId)) {
                                            $genderId = $genderId[0];
                                        }
                                        $rows['genderName'] = $this->getAttrName($genderId, 'gen');
                                    }
                                    // 季节
                                    if (isset($relation['for_date_text'])) {
                                        $seasonId = GetCategory::getIdsFrom(5, "sOriginalName,{$relation['for_date_text']}");
                                    } elseif (isset($relation['for_date'])) {
                                        $seasonId = GetCategory::getIdsFrom(5, "sOriginalName,{$relation['for_date']}");
                                    }
                                    $rows['video_id'] = $info['video_id'] ? $info['video_id'] : '';
                                    $rows['seasonName'] = $this->getAttrName($seasonId, 'sea');
                                    $rows['title'] = stripcslashes($relation['name_text']);
                                    $rows['publish_time'] = $relation['create_time'];
                                    // 设计师（品牌）
                                    $rows['brand_name'] = $this->getAttrName($relation['brand_tid'], 'bra');
                                    // 封面
                                    $cover = getFixedImgPath($imgPath['cover']);
                                    $rows['cover'] = $this->details_model->getImgfPath($cover, true);// 替换图片
                                    $rows['video_tag'] = 1; // 不带img
                                    if (strpos($rows['cover'], '<img') !== false) {
                                        $rows['video_tag'] = 2; // 带img
                                    }
                                    // 1--免费预览  其他--完整预览
                                    $rows['iPreviewMode'] = $relation['iPreviewMode'];
                                    $rows['sBuyingLinks'] = $relation['sBuyingLinks'] ? $relation['sBuyingLinks'] : '';// 购买链接
                                    $t = !empty($relation_real_table) ? getProductTableName($relation_real_table) : '';
                                    $_col = $columnId;
                                    if ($relation['type_mark'] == 4) { // 订货会
                                        $_col = 131;
                                    }
                                    $rows['inside_url'] = "/books/seclist/id_{$rows['id']}-t_{$t}-yl_{$rows['iPreviewMode']}-col_{$_col}/";
                                    break;
                            }
                        }
                    } else {
                        // 3，视频栏目--独立视频
                        $rows['id'] = $id;
                        $rows['tablename'] = $tableName;
                        $rows['title'] = $info['title'];
                        $rows['publish_time'] = $info['publish_time'];
                        // 性别
                        $rows['genderName'] = $this->getAttrName($info['gender'], 'gen');
                        // 地区名称
                        if ($columnId == 3) {
                            $rows['regionName'] = $this->getAttrName($info['region'], 'reg');
                        }
                        $rows['tidbits_id'] = $info['tidbits_id'] ? $info['tidbits_id'] : '';
                        $rows['video_id'] = $info['video_id'] ? $info['video_id'] : '';
                        $cover = !empty($info['video_cover']) ? $info['video_cover'] : '';
                        // 封面视频的封面不存在  获取 花絮的封面
                        $rows['tidbits_cover'] = !empty($info['tidbits_cover']) ? $info['tidbits_cover'] : '';
                        $rows['cover'] = $cover ? $cover : (!empty($rows['tidbits_cover']) ? $rows['tidbits_cover'] : '');
                        $rows['video_tag'] = 1; // 不带img
                        if (strpos($rows['cover'], "<img") !== false) {
                            $rows['video_tag'] = 2; // 带img
                        }
                        if ($rows['video_type'] == 2) {
                            $rows['memo'] = $info['memo'] ? $info['memo'] : '';
                        }
                        // 季节
                        $rows['seasonName'] = $this->getAttrName($info['season'], 'sea');
                        // 设计师（品牌）
                        $rows['brand_name'] = $this->getAttrName($info['brand_tid'], 'bra');
                        $rows['inside_url'] = "/video/inside/id_{$info['id']}/";
                    }
                    break;
                case 116:// 灵感视频
                    $rows['id'] = $info['id'];
                    $rows['publish_time'] = $info['dCreateTime'];
                    $imgPath = getImagePath($tableName, $info);
                    $rows['cover'] = getFixedImgPath($imgPath['cover']);
                    $rows['memo'] = htmlspecialchars(stripcslashes($info['sDesc']));// 摘要abstract
                    $rows['title'] = htmlspecialchars(stripcslashes($info['sTitle']));// 标题
                    $rows['view_count'] = $this->statistics_model->getViews($tableName, $id, $info);// 浏览量
                    $rows['columnId'] = $val['iColumnId'][1];
                    $rows['tablename'] = $tableName;
                    $rows['t'] = getProductTableName($tableName);
                    $rows['inside_url'] = "/details/report/t_{$rows['t']}-id_{$rows['id']}-col_{$rows['columnId']}/";
                    break;
            }
            if (!empty($rows)) {
                $lists[] = $rows;
            }
        }
        return $totalCount;
    }

    // 获取solr拼接条件
    protected function getConditions($columnId, $params)
    {
        $conditions = [];
        // $solr_time = $this->common_model->getTimeRange();// 2013-10-01 00:00:00 至今
        switch ($columnId) {
            case 3: // T台（发布会）
                $conditions['iColumnId'] = 10;
                $conditions['iVideoType'] = 1;
                // $conditions['dPublishTime'] = $solr_time;
                // 花絮
                if (isset($params['titbit']) && !isset($params['reg'])) {
                    $conditions['aVersion'] = 'tidbit';
                }
                // 地区时装周
                if (isset($params['reg']) && !isset($params['titbit'])) {
                    $reg = $params['reg'];
                    if ($reg == 'other') {
                        $regIds = $regions = []; // ['272','341','335','323']
                        $this->runways_model->getRegionIds($columnId, 'reg_other', $regIds);
                        foreach ($regIds as $key => $val) {
                            $regions[] = $val;
                        }
                        if (!empty($regions)) {
                            $conditions['other'][] = '-iRegion:(' . implode(' OR ', $regions) . ')';
                        }
                    } else {
                        $region = intval($reg);
                        $conditions['other'][] = "(iRegion:{$region} OR iArea:{$region} OR iContinent:{$region} OR iCountry:{$region})";
                    }
                }
                break;
            case 71:// 广告大片
                $conditions['iColumnId'] = 10;
                $conditions['iVideoType'] = 2;
                // $conditions['dPublishTime'] = $solr_time;
                break;
            case 116:// 灵感视频（主题灵感）
                $conditions['iColumnId'] = 116;
                // $conditions['dCreateTime'] = $solr_time;
                break;
        }
        return $conditions;
    }

    /************************************* 二级列表页 **********************************************/

    // 独立视频/3-发布会/71-广告大片--品牌来获取相关的季节 solr facet
    public function getRelevantSeason($data, $col = 10)
    {
        $cond = $result = $rows = [];
        $cond['iColumnId'] = $col;
        $brand_tid = $data['brand_tid'];
        /*switch ($col) {
            case 10: // 独立视频
                $cond['dPublishTime'] = $this->common_model->getTimeRange();
                break;
            case 3: // 发布会
                $cond['dCreateTime'] = $this->common_model->getTimeRange();
                break;
            case 71:// 广告大片
                $cond['dCreateTime'] = $this->common_model->getTimeRange();
                break;
        }*/

        // 品牌
        if (!empty($brand_tid)) {
            $cond['iBrand'] = intval($brand_tid);
        }
        $params = [
            'facet' => 'true',
            'facet.field' => 'iSeason',
            'facet.limit' => 500,
            'raw' => true
        ];

        $res = POPSearch::wrapQueryPopFashionMerger('', $cond, $result, 0, 0, [], $params);
        $facet_data = $res['facet_counts']['facet_fields']['iSeason'];
        $result_data = [];
        if (!empty($facet_data)) {
            // 季节字典
            $seaDict = GetCategory::getSeason('', 'sName');
            foreach ($facet_data as $id => $num) {
                if (in_array($id, array_keys($seaDict)) && (intval($num) > 0)) {
                    $rows[$id] = $seaDict[$id];
                }
            }
            // 季节排序
            if (!empty($rows) && !empty($seaDict)) {
                foreach ($seaDict as $id => $name) {
                    if (!empty($rows[$id])) {
                        $result_data[$id] = $rows[$id];
                    }
                }
            }
        }
        return $result_data;
    }

    // 获取3-发布会/71-广告大片的同一品牌/地区/季节/下的其他数据
    public function getColumnInsideAllData($compact, $col)
    {
        if (empty($compact)) return [];

        $limit = 100;// 暂定100
        $cond = $result = [];
        $cond['iColumnId'] = $col;
        // $cond['dCreateTime'] = $this->common_model->getTimeRange();
        $seasonId = !empty($compact['seasonId']) ? $compact['seasonId'] : '';// 季节
        $brandId = !empty($compact['brandId']) ? $compact['brandId'] : '';// 品牌

        // 地区
        if ($col == 3) {
            $region = !empty($compact['regionId']) ? $compact['regionId'] : '';
            if (!empty($region)) {
                $cond['other'][] = "(iRegion:{$region} OR iArea:{$region} OR iContinent:{$region} OR iCountry:{$region})";
            }
        }
        // 品牌
        if ($brandId) {
            $cond['iBrand'] = intval($brandId);
        }
        // 季节
        if ($seasonId) {
            $cond['other'][] = 'aLabelIds:' . intval($seasonId);
        }
        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        $total = POPSearch::wrapQueryPopFashionMerger('', $cond, $result, 0, $limit, $arSort);

        $lists = $rows = [];
        if ($total > 0) {
            foreach ($result as $key => $val) {
                $id = $val['pri_id'];
                $tableName = $val['tablename'];
                $data = OpPopFashionMerger::getProductData($id, $tableName);
                $info = $data[$id];
                switch ($col) {
                    case 3:
                        if (!empty($info)) {
                            $rows['id'] = $id;
                            $rows['title'] = stripcslashes($info['nme']);
                            $rows['inside_url'] = "/runways/inside/id_{$id}/";
                        }
                        break;
                    case 71:
                        if (!empty($info)) {
                            $rows['id'] = $id;
                            $rows['title'] = stripcslashes($info['name_text']);
                            $t = !empty($tableName) ? getProductTableName($tableName) : '';
                            $_col = $col;
                            if ($info['type_mark'] == 4) { // 订货会
                                $_col = 131;
                            }
                            $rows['inside_url'] = "/books/seclist/id_{$id}-t_{$t}-yl_{$info['iPreviewMode']}-col_{$_col}/";
                        }
                        break;
                }
                if ($rows) {
                    $lists[] = $rows;
                }
            }
        }
        return $lists;
    }

    /***************************** 独立视频二级列表页推荐取值 *************************/
    // 独立视频--其他发布会/广告大片推荐
    public function getOtherRecommendData($data)
    {
        $cond = $cond_1 = $result = $_result = $other_result = [];
        $id = $data['id'];
        // 排序
        $arSort = ['dPublishTime' => 'DESC', 'pri_id' => 'DESC'];

        // 1,品牌+季节+当前类型
        $cond_1['iColumnId'] = $cond['iColumnId'] = 10;
        // $cond_1['dPublishTime'] = $cond['dPublishTime'] = $this->common_model->getTimeRange();
        if (!empty($id)) {
            $cond['other'][] = '-(pri_id:' . $id . ')';// 排除自身
        }
        // 品牌
        if ($data['brand_tid']) {
            $cond_1['iBrand'] = $cond['iBrand'] = intval($data['brand_tid']);
        }
        // 当前类型
        if ($data['video_type']) {
            $cond_1['iVideoType'] = $cond['iVideoType'] = $data['video_type'];
        }
        // 季节
        if ($data['season']) {
            $cond['other'][] = 'aLabelIds:' . intval($data['season']);
        }
        $rows_1 = POPSearch::wrapQueryPopFashionMerger('', $cond, $_result, 0, 8, $arSort);

        // 排序 ：有视频的优先级高一点,排在前面
        if (!empty($_result)) {
            $m = $n = [];
            foreach ($_result as $vl) {
                if (isset($vl['aVersion']) && in_array('video', $vl['aVersion'])) {
                    $m[] = $vl;
                } else {
                    $n[] = $vl;
                }
            }
            $result = array_merge($m, $n);
        }

        // 2,如果不满8篇,显示【品牌】相关的发布会或者广告大片
        $total = count($result);
        if ($total < 8) {
            if (!empty($_result)) {
                // 去除重复推荐
                $ids = array_column($_result, 'pri_id');
                $idArr = !empty($ids) ? implode(' OR ', $ids) : '';
                if (!empty($idArr)) {
                    $idArr .= " OR " . $id;
                }
            }
            if (!empty($idArr)) {
                $cond_1['other'][] = '-(pri_id:(' . $idArr . '))';// 不要重复推荐
            } else {
                if (!empty($id)) {
                    $cond_1['other'][] = '-(pri_id:(' . $id . '))';// 排除自身
                }
            }
            $limit = 8 - $total;
            $other_result = [];
            $rows_2 = POPSearch::wrapQueryPopFashionMerger('', $cond_1, $other_result, 0, $limit, $arSort);
        }
        $res = [];
        $searchResult = array_merge($_result, $other_result);
        if (!empty($searchResult)) {
            $res = $this->dealInsideSolrData($searchResult);
        }
        return $res;
    }
    /********************************** 其他方法 *****************************/

    // 获取季节切换最新的一条数据
    public function getVideoInsideUrl($params)
    {
        $cond = $result = [];
        $sea = !empty($params['sea']) ? $params['sea'] : '';// 季节
        $bra = !empty($params['bra']) ? $params['bra'] : '';// 品牌

        $iColumnId = $cond['iColumnId'] = !empty($params['col']) ? $params['col'] : 10;
        if ($iColumnId == 10) {
            $arSort = ['dPublishTime' => 'DESC', 'pri_id' => 'DESC'];
        } else {
            $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        }

        // 品牌
        if (!empty($bra)) {
            $cond['iBrand'] = intval($bra);
        }
        // 季节
        if (!empty($sea)) {
            $cond['other'][] = 'aLabelIds:' . intval($sea);
        }
        $total = POPSearch::wrapQueryPopFashionMerger('', $cond, $result, 0, 1, $arSort);

        if (!empty($result)) {
            switch ($iColumnId) {
                case 3:
                case 71:
                    foreach ($result as $key => $val) {
                        $id = $val['pri_id'];
                        $tableName = $val['tablename'];
                        $data = OpPopFashionMerger::getProductData($id, $tableName);
                        $info = $data[$id];
                        switch ($iColumnId) {
                            case 3:
                                if (!empty($info)) {
                                    $inside_url = "/runways/inside/id_{$info['id']}/";
                                }
                                break;
                            case 71:
                                if (!empty($info)) {
                                    $t = !empty($tableName) ? getProductTableName($tableName) : '';
                                    $_col = $iColumnId; // 71-广告大片
                                    if ($info['type_mark'] == 4) {
                                        $_col = 131;// 订货会
                                    }
                                    $inside_url = "/books/seclist/id_{$info['id']}-t_{$t}-yl_{$info['iPreviewMode']}-col_{$_col}/";
                                }
                                break;
                        }
                    }
                    break;
                case 10:
                    $res = $this->dealInsideSolrData($result);
                    if (!empty($res)) {
                        $inside_url = $res[0]['inside_url'];
                    }
                    break;
            }
        }
        return !empty($inside_url) ? $inside_url : '';
    }

    private function dealInsideSolrData($searchResult)
    {
        if (empty($searchResult)) return [];

        $lists = $rows = [];
        foreach ($searchResult as $key => $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];
            $data = OpPopFashionMerger::getProductData($id, $tableName);
            $info = $data[$id];
            // 是否有关联id
            $solr_relation_pop_id = !empty($info['pop_id']) ? trim($info['pop_id']) : '';

            // 1，有关联的数据
            if (!empty($solr_relation_pop_id) && isset($solr_relation_pop_id)) {
                $relationData = $this->getRealTbaleId($solr_relation_pop_id);
                if (empty($relationData)) {
                    continue;
                }
                $relation_real_table = $relationData['relation_real_table'];
                $relation_pop_id = $relationData['relation_pop_id'];

                // 获取 发布会/广告大片的数据库数据
                $relation_data = OpPopFashionMerger::getProductData($relation_pop_id, $relation_real_table);
                $relation = $relation_data[$relation_pop_id];
                if (empty($relation)) {
                    continue;
                }
                if (!empty($relation)) {
                    $rows['id'] = $relation_pop_id;
                    $rows['tablename'] = $relation_real_table;
                    $imgPath = getImagePath($relation_real_table, $relation);
                    switch ($info['video_type']) {
                        case 1: // 发布会
                            // 性别
                            $rows['genderName'] = $this->getAttrName($relation['sGender'], 'gen');
                            // 地区名称
                            $rows['regionName'] = $this->getAttrName($relation['iRegion'], 'reg');
                            // 季节
                            $rows['seasonName'] = $this->getAttrName($relation['iSeason'], 'sea');
                            // 设计师（品牌）
                            $rows['brand_name'] = $this->getAttrName($relation['brand_tid'], 'bra');
                            // 发布时间
                            $times = [];
                            if (isset($relation['create_time_special']) && $relation['create_time_special']) {
                                $times[] = strtotime($relation['create_time_special']);
                            }
                            if (isset($relation['create_time_focus']) && $relation['create_time_focus']) {
                                $times[] = strtotime($relation['create_time_focus']);
                            }
                            if (isset($relation['create_time']) && $relation['create_time']) {
                                $times[] = strtotime($relation['create_time']);
                            }
                            if (isset($relation['create_time_video']) && $relation['create_time_video']) {
                                $times[] = strtotime($relation['create_time_video']);
                            }
                            $rows['tidbits_id'] = $info['tidbits_id'] ? $info['tidbits_id'] : '';
                            $rows['video_id'] = $info['video_id'] ? $info['video_id'] : '';
                            $rows['publish_time'] = $times ? date('Y-m-d', min($times)) : '';
                            $rows['title'] = stripcslashes($relation['nme']);
                            $rows['video_type'] = 1;
                            $rows['cover'] = $this->getRunwaySpecialCover($imgPath['cover']);
                            $rows['video_tag'] = 1; // 不带img
                            if (strpos($rows['cover'], '<img') !== false) {
                                $rows['video_tag'] = 2; // 带img
                            }
                            $rows['view_count'] = $this->statistics_model->getViews($relation_real_table, $relation_pop_id, $relation);
                            $rows['inside_url'] = "/runways/inside/id_{$relation_pop_id}/";
                            break;
                        case 2: // 广告大片
                            // 性别
                            if (isset($relation['typ'])) {
                                $genderId = GetCategory::getIdsFrom(1, "sOriginalName,{$relation['typ']}");
                                if (is_array($genderId)) {
                                    $genderId = $genderId[0];
                                }
                                $rows['genderName'] = $this->getAttrName($genderId, 'gen');
                            }
                            // 季节
                            if (isset($relation['for_date'])) {
                                $seasonId = GetCategory::getIdsFrom(5, "sOriginalName,{$relation['for_date']}");
                            } elseif (isset($relation['for_date_text'])) {
                                $seasonId = GetCategory::getIdsFrom(5, "sOriginalName,{$relation['for_date_text']}");
                            }
                            $rows['seasonName'] = $this->getAttrName($seasonId, 'sea');
                            $rows['title'] = stripcslashes($relation['name_text']);
                            $rows['video_id'] = $info['video_id'] ? $info['video_id'] : '';
                            $rows['publish_time'] = $relation['create_time'];
                            // 设计师（品牌）
                            $rows['brand_name'] = $this->getAttrName($relation['brand_tid'], 'bra');
                            $rows['video_type'] = 2;
                            // 封面
                            $cover = getFixedImgPath($imgPath['cover']);
                            $rows['cover'] = $this->details_model->getImgfPath($cover, true);// 替换图片
                            $rows['video_tag'] = 1; // 不带img
                            if (strpos($rows['cover'], '<img') !== false) {
                                $rows['video_tag'] = 2; // 带img
                            }
                            // 1--免费预览  其他--完整预览
                            $rows['iPreviewMode'] = $relation['iPreviewMode'];
                            $rows['sBuyingLinks'] = $relation['sBuyingLinks'] ? $relation['sBuyingLinks'] : '';// 购买链接
                            $t = !empty($relation_real_table) ? getProductTableName($relation_real_table) : '';
                            $_col = 71; // 广告大片
                            if ($relation['type_mark'] == 4) {
                                $_col = 131;// 订货会
                            }
                            $rows['inside_url'] = "/books/seclist/id_{$rows['id']}-t_{$t}-yl_{$rows['iPreviewMode']}-col_{$_col}/";
                            break;
                    }
                }
            } else {
                // 2,独立视频
                $rows['id'] = $id;
                $rows['tablename'] = $tableName;
                $rows['title'] = $info['title'];
                $rows['video_type'] = $info['video_type'];
                $rows['publish_time'] = $info['publish_time'];
                // 性别
                $rows['genderName'] = $this->getAttrName($info['gender'], 'gen');
                // 地区名称
                if ($info['video_type'] == 1) {
                    $rows['regionName'] = $this->getAttrName($info['region'], 'reg');
                }
                $rows['tidbits_id'] = $info['tidbits_id'] ? $info['tidbits_id'] : '';
                $rows['video_id'] = $info['video_id'] ? $info['video_id'] : '';
                $cover = !empty($info['video_cover']) ? $info['video_cover'] : '';

                // 封面视频的封面不存在  获取 花絮的封面
                $rows['tidbits_cover'] = !empty($info['tidbits_cover']) ? $info['tidbits_cover'] : '';
                $rows['cover'] = $cover ? $cover : (!empty($rows['tidbits_cover']) ? $rows['tidbits_cover'] : '');
                $rows['video_tag'] = 1; // 不带img
                if (strpos($rows['cover'], '<img') !== false) {
                    $rows['video_tag'] = 2; // 带img
                }
                if ($rows['video_type'] == 2) {
                    $rows['memo'] = $info['memo'] ? $info['memo'] : '';
                }
                // 季节
                $rows['seasonName'] = $this->getAttrName($info['season'], 'sea');
                // 设计师（品牌）
                $rows['brand_name'] = $this->getAttrName($info['brand_tid'], 'bra');
                $rows['inside_url'] = "/video/inside/id_{$info['id']}/";
            }
            $lists[] = $rows;
        }
        return $lists;
    }

    // 获取二级列表页优酷视频的相关数据
    public function getInsideYouKuVideoOneData($id = '')
    {
        if (empty($id)) return [];
        $result = OpPopFashionMerger::getProductData($id, OpPopFashionMerger::POP_Table_Name_Product_Video);
        if (!empty($result[$id]) && $result[$id]['status'] == 1) {
            // 地区名称
            if ($result[$id]['video_type'] == 1) {
                $result[$id]['regionName'] = $this->getAttrName($result[$id]['region'], 'reg');
            }
            // 性别
            $result[$id]['genderName'] = $this->getAttrName($result[$id]['gender'], 'gen');
            // 季节
            $result[$id]['seasonName'] = $this->getAttrName($result[$id]['season'], 'sea');
            // 设计师（品牌）
            $result[$id]['brand_name'] = $this->getAttrName($result[$id]['brand_tid'], 'bra');
            return $result;
        }
        return [];
    }

    // 发布会/广告大牌根据pop_id获取相关视频--单条
    public function getVideoDataByRelationPopId($pop_id)
    {
        if (empty($pop_id)) return [];
        $sql = "SELECT * FROM " . self::T_FASHION_PRODUCT_VIDEO . " WHERE status=1 AND pop_id = '{$pop_id}' limit 1 ";
        $data = $this->query($sql);

        if (!empty($data)) {
            $id = $data[0]['id'];// 视频专栏表的id
            $info = $this->getInsideYouKuVideoOneData($id);
            $result = !empty($info) ? $info[$id] : [];
        }
        return !empty($result) && isset($result) ? $result : [];
    }

    //集中处理/性别/季节/地区/品牌方法--中文名称
    public function getAttrName($item, $attr)
    {
        if (empty($item) || empty($attr)) return '';
        switch ($attr) {
            case 'sea':// 季节
            case 'gen':// 性别
                $string = GetCategory::getOtherFromIds($item, ['sName']);
                break;
            case 'reg':// 地区
                $string = GetCategory::getFieldFromId($item);
                break;
            case 'bra':// 品牌
                $string = GetCategory::getBrandOtherFormId($item);
                break;
        }
        return !empty($string) ? trim($string) : '';
    }

    // 除独立视频外T台(发布会）/广告大片 批量获取视频（注：T台包含花絮视频）是否存在
    public function getVideoExist($result, $columnId)
    {
        if (empty($result) || empty($columnId)) return false;

        if (!empty($this->video_have)) {
            return $this->video_have;
        }

        $relaPopIdArr = $rows = [];
        switch ($columnId) {
            case 3:
                $pop_id_prefix = 'presscon_';
                break;
            case 71:
                $pop_id_prefix = 'brochures_';
                break;
        }

        $pri_ids = array_column($result, 'pri_id');
        foreach ($pri_ids as $vl) {
            if (!empty($vl)) {
                $rela_pop_id = $pop_id_prefix . $vl;
                $relaPopIdArr[] = "'{$rela_pop_id}'";
            }
        }

        if (!empty($relaPopIdArr)) {
            $idsArr = implode(',', $relaPopIdArr);
            $sql = "SELECT * FROM " . self::T_FASHION_PRODUCT_VIDEO . " WHERE status=1 AND pop_id IN ({$idsArr})";
            $data = $this->query($sql);
            if (!empty($data)) {
                foreach ($data as $v) {
                    switch ($columnId) {
                        case 3: // T台(发布会）
                            if (!empty($v['video_id']) || !empty($v['tidbits_id'])) {
                                $rows[$v['pop_id']] = true;
                            }
                            break;
                        case 71: // 广告大片
                            if (!empty($v['video_id'])) {
                                $rows[$v['pop_id']] = true;
                            }
                            break;
                    }
                }
            }
        }
        $this->video_have = !empty($rows) && isset($rows) ? $rows : [];
        return $this->video_have;
    }

    // 获取视频专栏广告推荐
    public function getAds($limit = 5, $iPosition = 27)
    {
        $this->load->driver('cache');
        $mem_key = 'VIDEO_ADS_' . $limit . '_' . $iPosition;
        $result = $this->cache->memcached->get($mem_key);
        if (empty($result) || $_GET["refresh"] == 1) {
            $result = [];
            $time = date('Y-m-d H:i:s');
            $sql = "SELECT iAdId,iColumnId,iPosition,sImagePath,sUrl,dCreateTime,sTitle,subTitle,sMemo,sBackground,sNewWindow,dStartTime,dEndTime, IF(dEndTime>'$time', 1, 0) e FROM " . self::T_FASHION_FM_AD . " WHERE iPosition ={$iPosition} AND iStatus = 1 ORDER BY e DESC, iOrder DESC limit {$limit}";
            $data = $this->query($sql);
            if (!empty($data)) {
                foreach ($data as $row) {
                    // 广告统计
                    $link = "/statistics/link/?url=" . base64_encode($row["iAdId"]) . "_" . base64_encode($row["sUrl"]);
                    $sLink = ($row["sUrl"] == "javascript:void(0)" || empty($row["sUrl"])) ? "javascript:void(0);" : $link;

                    $result[] = [
                        'sTitle' => $row["sTitle"],
                        'sMemo' => $row["sMemo"],
                        'sImagePath' => $row["sImagePath"],
                        'sLink' => $sLink,
                        'sNewWindow' => $row["sNewWindow"],// 0=>本窗口打开,1=>新窗口打开
                        'manual' => 1
                    ];
                }
            }
            $this->cache->memcached->save($mem_key, $result, 7200);
        }
        return $result;
    }

    // 获取tdk
    public function getTDk()
    {
        $webname = 'POP服装趋势网';
        $tdk = [
            't' => "时装周发布会T台视频_时尚广告大片_服装设计灵感视频-{$webname}",
            'k' => '时装周视频,发布会视频,T台视频,时尚广告大片,服装设计灵感视频',
            'd' => "{$webname}视频栏目汇集了国际各大时装周视频、时装秀视频、T台走秀发布会视频，提供最新、最前沿的时尚广告大片、灵感视频等，为您及时提供时尚相关的视频和幕后花絮，为您提供有价值的资讯服务。",
        ];
        return $tdk;
    }

    // 剔除发布会（T台）特殊封面（三张封面）的 width 与 height
    private function getRunwaySpecialCover($cover)
    {
        if (strpos($cover, 'width') !== false || strpos($cover, 'height') !== false) {
            // 1, 特殊封面（三张封面）的 width 与 height
            $html = preg_replace('/(<img.*?)((width)=[\'"]+[0-9]+[\'"]+)/', '$1', $cover);
            $html_str = preg_replace('/(<img.*?)((height)=[\'"]+[0-9]+[\'"]+)/', '$1', $html);
            // 2, 去除懒加载
            if (strpos($html_str, 'src') !== false) {
                $html_load = str_replace('src', 'data-test', $html_str);
                if (strpos($html_load, 'data-original') !== false) {
                    $html_str = str_replace('data-original', 'src', $html_load);
                }
            }
        }
        return !empty($html_str) ? $html_str : (!empty($cover) ? $cover : '');
    }

    // 获取T台与广告大片的真实表名与ID
    private function getRealTbaleId($relation_pop_id = '')
    {
        if (empty($relation_pop_id)) return [];

        if (strpos($relation_pop_id, '_') !== false) {
            $data = explode('_', trim($relation_pop_id));
            $relation_real_table = !empty($data[0]) ? getProductTableName($data[0]) : '';
            $relation_pop_id = !empty($data[1]) ? $data[1] : '';
            return compact('relation_real_table', 'relation_pop_id');
        }
        return [];
    }

    // 获取更多的url
    private function getMoreUrl($columnId, $params = [], $isAjax = false)
    {
        if (empty($columnId)) return '';
        switch ($columnId) {
            case 3: // 发布会
                if ($params['reg'] && empty($params['titbit']) && $isAjax) {
                    // 更多: 地区时装周
                    $more_url = "/runways/typ_tshowpic-ver_video-reg_{$params['reg']}/";
                } else {
                    // 更多: 全部/视频花絮
                    $more_url = '/runways/ver_video/';
                }
                break;
            case 71:// 广告大片
                $more_url = '/books/lookbook/ver_video/';
                break;
            /*case 116: // 灵感视频
                $more_url = '/inspiration/video/';
                break;*/
        }
        return !empty($more_url) ? $more_url : '';
    }

}