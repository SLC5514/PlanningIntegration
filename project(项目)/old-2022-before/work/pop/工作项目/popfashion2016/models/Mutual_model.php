<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2016/3/15
 * Time: 12:44
 * @property-read common_model $common_model
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 交叉页 专用类
 */
class Mutual_model extends POP_Model
{
    //memcache缓存前缀
    const FM_TEM_MUTUAL_CACHE = 'FM_TEM_MUTUAL_CACHE_';
    const T_INDEX_REC_REPORTS = 't_index_rec_reports';
    const KOREA_AND_JANPAN_MUTUAL = 'korea_janpan_mutual_data';

    public $reg_arr = array(194=>'korea',209=>'japan');

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
    }

    /*
     * 根据性别 行业 栏目ID 获取交叉页的数据
     * $sGender intval 查询条件中的性别  -->女装 男装 童装 ...
     * $sIndustry intval 查询条件中的的品名    --> 毛衫 ...
     * $iColumnIdName intval 栏目名
     * $dCreateTime string solr倒序查询字段   -->dCreateTime ...
     * $offset intval  偏移量
     * $limit intval 查询条数
     * $params array URL传值条件
     */
    public function getCombineData($iColumnId, $offset = 0, $limit = 14, &$totalCount = '', $refresh = false, $params = array())
    {
        if (is_array($params)) {
            $aParams = [];
            foreach ($params as $key => $val) {
                if ($val) {
                    $aParams[] = $key . '_' . $val;
                }
            }
            $sParams = implode('-', $aParams);
        }
        $this->benchmark->mark('getCombineData' . $iColumnId);
        // 条件构造
        $conditions = [];
        $conditions_sql = [];

        $iColumnId = (int)$iColumnId;
        if ($iColumnId == 5) { // 品牌只查款式库的数据
            $conditions['iColumnId'] = [1, 2, 4, 8];
        } elseif ($iColumnId == 6) {// 特殊处理,将手稿合辑里面的lookbook移除，并加入到款式库侧边广告通
            // $conditions['iColumnId'] = [70, 72, 113, 114, 115];
            $conditions['iColumnId'] = [71];//只取广告大片栏目的数据 product_brochures
            $conditions['iDisplay'] = 1;
        } elseif ($iColumnId == 7) {// 服饰品像lookbook一样单独拎出来
            $conditions['iColumnId'] = [80, 81, 85, 117];
        } elseif ($iColumnId == 9) {// 图案库-图案素材
            $conditions['iColumnId'] = 82;
            $conditions['aWebsite'] = 1;
        } else {
            $conditions['iColumnId'] = $iColumnId;
        }
        if(isset($params['reg']) && !empty($params['reg'])){
            $conditions['iRegion'] = $params['reg'];
        }

        $gender = $this->common_model->getGenderByRequest($params);
        $industry = $this->common_model->getIndustryByRequest($params);
        // 灵感不分行业性别
        if ($iColumnId != 8) {
            $this->common_model->childGender($gender, $conditions);
            // 行业与性别之间 AND
            if ($iColumnId != 3) {  // 发布会没有行业
                if ($gender) {
                    //选择性别时排除掉行业
                    if ($iColumnId != 5) {
                        //品牌推荐不排除条件
                        $conditions['other'][] = '-aLabelIds:(6 OR 7 OR 8 OR 9 OR 10 OR 11)';
                    }
                }
                if ($industry && $iColumnId != 9) {
                    $conditions['other'][] = 'aLabelIds:' . $industry;
                }
            } else {
                //发布会部分加上成衣条件
                $tshowpic = $this->isTshowpic($gender, $industry);
                if ($tshowpic) {
                    $conditions['sClass'] = 'tshowpic';
                }
            }
        }

        // 加入仅与行业偏移量栏目有关的缓存
        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_mutual_20190809_' . intval($iColumnId
                . $offset . $limit . $gender . $industry);
        $memcache_obj = OpMemcache::getInstance();

        $res = $result = [];
        // 品牌
        if ($iColumnId == 5) {
            $res = $memcache_obj->get($mem_key);
            if ($res === false || $refresh == true || $_GET['refresh']) {
                $this->load->model('brands_model');

                $res = $this->brands_model->getBrands($conditions, 0, $limit);

                $memcache_obj->set($mem_key, $res, 0, 600);
            }
            $totalCount = count($res);
            $this->benchmark->mark('getCombineData' . $iColumnId . 'End');
            return $res;
        }

        // 排序
        $arSort = $this->common_model->getSort([], [], $iColumnId);

        $_res = $memcache_obj->get($mem_key);
        if ($_res === false || $refresh == true || $_GET['refresh']) {
            if ($iColumnId == 1 || $iColumnId == 2) {

                //取发布时间最新的6篇报告
                /*if($gender){
                    if($gender == 5){
                        $conditions[] = "aLabelIds:(3 OR 4 OR 5)";
                    }
                }*/
                $totalCount = POPSearch::wrapQueryPopFashionMerger('',$conditions,$res,0,6,$arSort);
                foreach ($res as $value){
                    $result[] = array(
                        'iId' => $value['pri_id'],
                        'sTableName' => $value['tablename'],
                        'iColumnId' => end($value['iColumnId']),
                    );
                }
            } elseif ($iColumnId == 4) {

                $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $arSort);
                //款式库特殊处理 每页取子栏目前两条数据
                // 秀场提炼
                $conditions['iColumnId'] = [50];
                POPSearch::wrapQueryPopFashionMerger('', $conditions, $result1, 0, 6, $arSort);
                //订货会
                $conditions['iColumnId'] = [52];
                POPSearch::wrapQueryPopFashionMerger('', $conditions, $result2, 0, 6, $arSort);
                //设计师品牌
                $conditions['iColumnId'] = [122];
                POPSearch::wrapQueryPopFashionMerger('', $conditions, $result3, 0, 6, $arSort);
                //名牌精选
                $conditions['iColumnId'] = [55];
                POPSearch::wrapQueryPopFashionMerger('', $conditions, $result4, 0, 6, $arSort);
                //款式流行
                $conditions['iColumnId'] = [123];
                POPSearch::wrapQueryPopFashionMerger('', $conditions, $result5, 0, 6, $arSort);
                //全球实拍
                $conditions['iColumnId'] = [54];
                POPSearch::wrapQueryPopFashionMerger('', $conditions, $result6, 0, 6, $arSort);
                //潮流领袖
                $conditions['iColumnId'] = [57];
                POPSearch::wrapQueryPopFashionMerger('', $conditions, $result7, 0, 3, $arSort);
                //街拍图库
                $conditions['iColumnId'] = [56];
                POPSearch::wrapQueryPopFashionMerger('', $conditions, $result8, 0, 3, $arSort);
                $result = [];
                $result1Arr1 = array_chunk($result1, 2);
                $result1Arr2 = array_chunk($result2, 2);
                $result1Arr3 = array_chunk($result3, 2);
                $result1Arr4 = array_chunk($result4, 2);
                $result1Arr5 = array_chunk($result5, 2);
                $result1Arr6 = array_chunk($result6, 2);
                $result1Arr7 = array_chunk($result7, 1);
                $result1Arr8 = array_chunk($result8, 1);
                for ($i = 0; $i < 3; $i++) {
                    if (is_array($result1Arr1[$i]) && $result1Arr1[$i]) {
                        $result = array_merge($result, $result1Arr1[$i]);
                    }
                    if (is_array($result1Arr2[$i]) && $result1Arr2[$i]) {
                        $result = array_merge($result, $result1Arr2[$i]);
                    }
                    if (is_array($result1Arr3[$i]) && $result1Arr3[$i]) {
                        $result = array_merge($result, $result1Arr3[$i]);
                    }
                    if (is_array($result1Arr4[$i]) && $result1Arr4[$i]) {
                        $result = array_merge($result, $result1Arr4[$i]);
                    }
                    if (is_array($result1Arr5[$i]) && $result1Arr5[$i]) {
                        $result = array_merge($result, $result1Arr5[$i]);
                    }
                    if (is_array($result1Arr6[$i]) && $result1Arr6[$i]) {
                        $result = array_merge($result, $result1Arr6[$i]);
                    }
                    if (is_array($result1Arr7[$i]) && $result1Arr7[$i]) {
                        $result = array_merge($result, $result1Arr7[$i]);
                    }
                    if (is_array($result1Arr8[$i]) && $result1Arr8[$i]) {
                        $result = array_merge($result, $result1Arr8[$i]);
                    }
                }
            } else {
                $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $arSort);
            }

            // 存在
            if ($totalCount) {
                $this->load->model('details_model');
                switch ($iColumnId) {
                    case 1: // 趋势
                    case 2: // 分析
                        $this->load->model('details_model');
                        foreach ($result as $k => $v) {
                            //从数据库中取推荐报告
                            $id = $v['iId'];
                            $tablename = $v['sTableName'];
                            $columId = $v['iColumnId'];

                            $tableInfo = OpPopFashionMerger::getProductData($id, $tablename);
                            if ($tablename == 'fashion_shows') {
                                if ($tableInfo[$id]['part_series']) {
                                    $aPart = array_keys(unserialize($tableInfo[$id]['part_series']));
                                } else {
                                    continue;
                                }
                                if (in_array('qsdq', $aPart)) {
                                    $tablename = OpPopFashionMerger::POP_Table_Name_Fs_Trend;
                                } elseif (in_array('qhsj', $aPart)) {
                                    $tablename = OpPopFashionMerger::POP_Table_Name_Fs_Design;
                                } elseif (in_array('clfx', $aPart)) {
                                    $tablename = OpPopFashionMerger::POP_Table_Name_Fs_Analysis;
                                } elseif (in_array('lgrd', $aPart)) {
                                    $tablename = OpPopFashionMerger::POP_Table_Name_Fs_Inspiration;
                                } elseif (in_array('spbg', $aPart)) {
                                    $tablename = OpPopFashionMerger::POP_Table_Name_Fs_Commodity;
                                }
                                $res[$k] = $tableInfo[$id];
                                $sql = "SELECT id FROM $tablename WHERE group_id = ?";
                                $query = $this->db()->query($sql, [$id]);
                                $num = $query->num_rows();
                                // $this->db()->close();
                                if ($num === 0) {
                                    return false;
                                } else {
                                    $row = $query->row_array();
                                    $id = $res[$k]['id'] = $row['id'];
                                }
                            } else {
                                $res[$k] = $tableInfo[$id];
                            }

                            $imgPath = getImagePath($tablename, $res[$k]);
                            $res[$k]['smallPath'] = $imgPath['smallPath'];
                            $res[$k]['iColumnId'] = $columId;
                            $res[$k]['tablename'] = getProductTableName($tablename);
                            //$tableInfo = $this->details_model->getPicInfo($id, $tablename,$params,$columId); // 后期处理
                            $res[$k]['title'] = !empty($res[$k]['title']) ? $res[$k]['title'] : $res[$k]['sTitle'];
                            if (isset($res[$k]['sDesc'])) {
                                $res[$k]['description'] = trim(strip_tags($res[$k]['sDesc']));
                            } elseif (isset($res[$k]['abstract'])) {
                                $res[$k]['description'] = trim(strip_tags($res[$k]['abstract']));
                            }
                            $res[$k]['description'] = strip_tags(stripcslashes($res[$k]['description']));
                            //子栏目名称
                            $res[$k]['columnName'] = GetCategory::getOtherFromColId($columId, 'sName');
                            $res[$k]['columnEnName'] = GetCategory::getOtherFromColId($columId, 'sEnName');
                            $res[$k]['columnLink'] = $this->common_model->getLink($columId, $sParams);
                        }
                        break;
                    case 3: // T台发布
                        foreach ($result as $k => $v) {
                            $id = $v['pri_id'];
                            $tablename = $v['tablename'];
                            $tableInfo = OpPopFashionMerger::getProductData($id, $tablename);
                            $res[$k] = $tableInfo[$id];
                            $imgPath = getImagePath($tablename, $res[$k]);
                            // 地区名称
                            if ($res[$k]['iRegion']) {
                                $res[$k]['region_text'] = GetCategory::getFieldFromId($res[$k]['iRegion']);
                            } else {
                                $res[$k]['region_text'] = '';
                            }
                            // 设计师（品牌）
                            if ($res[$k]['brand_tid']) {
                                //品牌
                                $res[$k]['designer'] = GetCategory::getBrandOtherFormId($res[$k]['brand_tid']);
                            } else {
                                $res[$k]['designer'] = '';
                            }
                            // 季节
                            if ($res[$k]['iSeason']) {
                                $res[$k]['for_date_text'] = GetCategory::getOtherFromIds($res[$k]['iSeason'], ['sName']);
                            } else {
                                $res[$k]['for_date_text'] = '';
                            }
                            // $res[$k]['create_time'] = date('Y-m-d', strtotime($res[$k]['update_time']));
                            $times = array();
                            if (isset($res[$k]['create_time_special']) && $res[$k]['create_time_special']) {
                                array_push($times, strtotime($res[$k]['create_time_special']));
                            }
                            if (isset($res[$k]['create_time_focus']) && $res[$k]['create_time_focus']) {
                                array_push($times, strtotime($res[$k]['create_time_focus']));
                            }
                            if (isset($res[$k]['create_time']) && $res[$k]['create_time']) {
                                array_push($times, strtotime($res[$k]['create_time']));
                            }
                            if (isset($res[$k]['create_time_video']) && $res[$k]['create_time_video']) {
                                array_push($times, strtotime($res[$k]['create_time_video']));
                            }
                            $res[$k]['create_time'] = $times ? date('Y-m-d', min($times)) : '';
                            $res[$k]['smallPath'] = $imgPath['smallPath'];
                        }
                        break;
                    case 4: // 款式库
                        $this->load->model('common_model');

                        foreach ($result as $k => $v) {
                            $id = $v['pri_id'];
                            $tablename = $v['tablename'];
                            $columId = end($v['iColumnId']);
                            $tableInfo = OpPopFashionMerger::getProductData($id, $tablename);
                            $res[$k] = $tableInfo[$id];
                            $imgPath = getImagePath($tablename, $res[$k]);
                            $res[$k]['smallPath'] = $imgPath['smallPath'];
                            $res[$k]['iColumnId'] = $columId;
                            $res[$k]['tablename'] = getProductTableName($tablename);
                            //获取栏目名称
                            if ($res[$k]['iColumnId']) {
                                $sParams = $sParams ? $sParams . '-dis_1' : 'dis_1';
                                $res[$k]['columnName'] = GetCategory::getOtherFromColId($res[$k]['iColumnId'], 'sName');
                                $res[$k]['columnLink'] = $this->common_model->getLink($res[$k]['iColumnId'], $sParams);
                            }
                            $category = $res[$k]['iCategory'];
                            $subcategory = $res[$k]['iSubcategory'];
                            $brand = '';
                            $seasonId = 0;

                            switch ($tablename) {
                                case 'product_perform':
                                case 'product_marketphoto_item':
                                case 'product_ordermeeting':
                                case 'product_brand':
                                case 'product_style_graphic':
                                case 'product_style_graphic_china':
                                    $brand = $res[$k]['brand_tid'];
                                    $seasonText = $res[$k]['for_date'];
                                    if ($seasonText) {
                                        $seasonInfo = GetCategory::getIdsFrom(5, 'sOriginalName,' . $seasonText);
                                        $seasonId = $seasonInfo[0];
                                    }
                                    //如果有更新时间显示更新时间而不是创建时间
                                    $res[$k]['update_time'] = date('Y-m-d', strtotime($res[$k]['updateTime']));
                                    break;
                                case 'product_streetitem':
                                    $seasonText = $res[$k]['for_date'];
                                    if ($seasonText) {
                                        $seasonInfo = GetCategory::getIdsFrom(5, 'sOriginalName,' . $seasonText);
                                        $seasonId = $seasonInfo[0];
                                    }
                                    break;
                                case 'mostrend_content':
                                    $seasonText = $res[$k]['popular_time'];
                                    if ($seasonText) {
                                        $seasonInfo = GetCategory::getIdsFrom(5, 'sOriginalName,' . $seasonText);
                                        $seasonId = $seasonInfo[0];
                                    }
                                    break;
                                case 'product_tideleader':
                                    $brand = $res[$k]['brand_tid'];
                                    $seasonId = $res[$k]['iFordate'];
                                    $res[$k]['update_time'] = date('Y-m-d', strtotime($res[$k]['dUpdateTime']));
                                    break;
                            }

                            if ($brand) {
                                $brandInfo = OpPopFashionMerger:: getBrandData($brand);
                                $res[$k]['brand_text'] = $brandInfo['name'] ? $brandInfo['name'] : '';
                                $res[$k]['link_brand'] = $this->common_model->getLink($columId, 'dis_1', 'bra', $brand);
                            } else {
                                $res[$k]['brand_text'] = '';
                                $res[$k]['link_brand'] = '';
                            }
                            if ($seasonId) {
                                $res[$k]['seasonName'] = GetCategory::getOtherFromIds($seasonId, ['sName']);
                                $res[$k]['link_season'] = $this->common_model->getLink($columId, 'dis_1', 'sea', $seasonId);
                            } else {
                                $res[$k]['seasonName'] = '';
                                $res[$k]['link_season'] = '';
                            }

                            if (count($res[$k]['aAttribute']) > 1) {
                                //多条单品品名属性
                                $res[$k]['iCategory_text'] = '搭配';
                                $res[$k]['link_category'] = 'javascript:void(0);';
                                $res[$k]['iSubcategory_text'] = '搭配';
                                $res[$k]['link_subcategory'] = 'javascript:void(0);';
                            } else {
                                if ($category) {
                                    $res[$k]['iCategory_text'] = GetCategory::getOtherFromIds($category, array('sName'));
                                    $res[$k]['link_category'] = $this->common_model->getLink($columId, 'dis_1', 'cat', $category);
                                } else {
                                    $res[$k]['iCategory_text'] = '';
                                    $res[$k]['link_category'] = '';
                                }
                                if ($subcategory) {
                                    $res[$k]['iSubcategory_text'] = GetCategory::getOtherFromIds($subcategory, array('sName'));
                                    $res[$k]['link_subcategory'] = $this->common_model->getLink($columId, 'dis_1', 'cat', $subcategory);
                                } else {
                                    $res[$k]['iSubcategory_text'] = '';
                                    $res[$k]['link_subcategory'] = '';
                                }
                            }
                            $create_time = empty($res[$k]['create_time']) ? "" : date('Y-m-d', strtotime($res[$k]['create_time']));
                            $dCreateTime = date('Y-m-d', strtotime($res[$k]['dCreateTime']));
                            if ($create_time) {
                                $res[$k]['create_time'] = $create_time;
                            } else {
                                $res[$k]['create_time'] = $dCreateTime;
                            }

                            $detailInfo = $this->details_model->getPicInfo($id, $tablename, $sParams, $columnId);
                            $res[$k]['title'] = $detailInfo['title'];
                        }
                        break;
                    case 6: // 刊物
                        foreach ($result as $k => $v) {
                            $id = $v['pri_id'];
                            $tablename = $v['tablename'];
                            $columId = end($v['iColumnId']);
                            $tableInfo = OpPopFashionMerger::getProductData($id, $tablename == 'product_vector_refrence_group' ? 'product_vector_refrence_list' : $tablename);
                            $res[$k] = $tableInfo[$id];
                            $imgPath = getImagePath($tablename, $res[$k]);
                            $res[$k]['smallPath'] = $imgPath['smallPath'];
                            $res[$k]['iColumnId'] = $columId;
                            $res[$k]['tablename'] = getProductTableName($tablename);
                            // 个别表里面的书名处理
                            switch ($tablename) {
                                case 'product_design_refrence':
                                    $res[$k]['name'] = $res[$k]['name_text'];
                                    break;
                                case 'mostrend_content':
                                    $res[$k]['name'] = $res[$k]['title'];
                                    break;
                                case 'product_vector_refrence_group':
                                    $res[$k]['name'] = $res[$k]['theme'];
                                    break;
                                case 'product_brochures':
                                    $res[$k]['name'] = $res[$k]['name_text'];
                                    break;
                            }
                        }
                        break;
                    case 7: // 素材
                    case 8: // 灵感
                        foreach ($result as $k => $v) {
                            $id = $v['pri_id'];
                            $tablename = $v['tablename'];
                            $columId = end($v['iColumnId']);
                            $tableInfo = OpPopFashionMerger::getProductData($id, $tablename);
                            $res[$k] = $tableInfo[$id];
                            $imgPath = getImagePath($tablename, $res[$k]);
                            $res[$k]['smallPath'] = $imgPath['smallPath'];
                            $res[$k]['iColumnId'] = $columId;
                            $res[$k]['tablename'] = getProductTableName($tablename);
                            if ($iColumnId == 7) {
                                $detailInfo = $this->details_model->getPicInfo($id, $tablename, $sParams, $columnId);
                                $res[$k]['title'] = $detailInfo['title'];
                            } else {
                                if ($tablename == 't_trend_report') {
                                    $res[$k]['title'] = $res[$k]['sTitle'];// 标题
                                }
                                $res[$k]['title'] = htmlspecialchars(stripcslashes($res[$k]['title']));
                            }
                        }
                        break;
                    case 9: // 图案库-图案素材
                        foreach ($result as $k => $v) {
                            $id = $v['pri_id'];
                            $tablename = $v['tablename'];
                            $columId = end($v['iColumnId']);
                            $tableInfo = OpPopFashionMerger::getProductData($id, $tablename);
                            $res[$k] = $tableInfo[$id];
                            $imgPath = getImagePath($tablename, $res[$k]);
                            $res[$k]['smallPath'] = $imgPath['smallPath'];
                            $res[$k]['iColumnId'] = $columId;
                            $res[$k]['tablename'] = getProductTableName($tablename);
                        }
                        break;
                }
            }
            $_res = ['list' => $res, 'total' => $totalCount];
            $memcache_obj->set($mem_key, $_res, 0, 600);
        }
        $res = $_res['list'];
        $totalCount = $_res['total'];
        $this->benchmark->mark('getCombineData' . $iColumnId . 'End');
        return $res;
    }

    /*
     * 获取站内图片总数 和今日更新图片总数
     * @param intval $gender   [季节]
     * @param intval $industry [行业]
     * @return array    [总条数]
     */
    public function getSolrImgCount($gender = 0, $industry = 0)
    {
        // 加入仅与行业有关的缓存
        $conditions = $result = [];
        // 空则从cookie取
        $gender OR $gender = intval($this->input->cookie("gender"));
        $industry OR $industry = intval($this->input->cookie("industry"));
        // 童装特殊处理 OR
        $this->common_model->childGender($gender, $conditions);
        // 行业与性别之间 AND
        if ($industry) {
            $conditions['other'] = 'aLabelIds:' . $industry;
        }

        // 站内图片总数 统一方法调用
        $allCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, 0, 1);
        return ['all' => $allCount, 'day' => 0];
    }

    // 根据id获取front_attr_xx里面字段名称
    public function getNameById($id, $type = 1, $field = 'sName')
    {
        if (empty($id)) {
            return '';
        }
        $id = intval($id);
        $front = @include getenv('BASEPATH') . '/category/popfashion/fm_conf/front_attr_' . intval($type) . '.php';
        $ret = isset($front[$id][$field]) && !empty($front[$id][$field]) ? $front[$id][$field] : '';
        unset($front);
        return $ret;
    }

    // 获取全部数据并设置缓存
    public function getMutualData($iGender, $iIndustry, $refresh = false)
    {
        $_id = 0;
        if ($iGender) {
            $_id = $iGender;
        } elseif ($iIndustry) {
            $_id = $iIndustry;
        }

        $this->load->driver('cache');
        $mem_key = self :: FM_TEM_MUTUAL_CACHE . 'Mutual_type_' . $_id . '_' . date('Ymd').'_2020_05_12';

        $data = $this->cache->memcached->get($mem_key);
        if (!$data || $refresh || $_GET['refresh']) {
            $data = [];
            // 背景图
            if ($iGender == 1) {
                $data['class'] = 'bg_man';
            } elseif ($iGender == 2) {
                $data['class'] = 'bg_woman';
            } elseif ($iGender == 5) {
                $data['class'] = 'bg_child';
            } else {
                $data['class'] = '';
            }
            $condition = [];
            $condition['gen'] = $iGender;
            $condition['ind'] = $iIndustry;
            //获取总数
            $data['count'] = $this->getSolrImgCount($iGender, $iIndustry);
            //性别名称
            $data['sGenderName'] = $this->getNameById($iGender);
            //行业名称
            $data['sIndustryName'] = $this->getNameById($iIndustry, 2);
            //热搜词
            $data['hotWord'] = $this->mutual_model->getSpecialHotKeyWords('sp_' . $_id);
            // 初始化
            $countTrend = $countAnalyze = $countRunways = $countStyle = $countBrands = $countBooks = $countReferences = $countPatterns = $countInspiration = 0;
            /**趋势**/
            $trendData = $this->getCombineData(1, 0, 6, $countTrend, true, $condition);
            $trend_count_page = $countTrend ? ceil($countTrend / 2) : 1;    //总页码数
            $data[1]['data'] = $trendData;
            $data[1]['count'] = $trend_count_page;

            /**分析**/
            $analyzeData = $this->getCombineData(2, 0, 6, $countAnalyze, true, $condition);
            $offset_count_page = $countAnalyze ? ceil($countAnalyze / 2) : 1; //总页码数
            $data[2]['data'] = $analyzeData;
            $data[2]['count'] = $offset_count_page;

            /**发布会**/
            $runwaysData = $this->getCombineData(3, 0, 9, $countRunways, true, $condition);
            $runways_count_page = $countRunways ? ceil($countRunways / 3) : 1;    //总页码数
            $data[3]['data'] = $runwaysData;
            $data[3]['count'] = $runways_count_page;

            /**款式**/
            $styleData = $this->getCombineData(4, 0, 28, $countStyle, true, $condition);
            $style_count_page = $countStyle ? ceil($countStyle / 14) : 1;   //总页码数
            $data[4]['data'] = $styleData;
            $data[4]['count'] = $style_count_page;

            /**品牌**/
            $brandsData = $this->getCombineData(5, 0, 12, $countBrands, true, $condition);
            $brands_count_page = $countBrands ? ceil($countBrands / 4) : 1;  //总页数
            $data[5]['data'] = $brandsData;
            $data[5]['count'] = $brands_count_page;

            /**刊物**/
            $booksData = $this->getCombineData(6, 0, 15, $countBooks, true, $condition);
            $books_count_page = $countBooks ? ceil($countBooks / 5) : 1;    //总页面数
            $data[6]['data'] = $booksData;
            $data[6]['count'] = $books_count_page;

            /**素材**/
            $referencesData = $this->getCombineData(7, 0, 9, $countReferences, true, $condition);
            $references_count_page = $countReferences ? ceil($countReferences / 3) : 1;  //总页码数
            $data[7]['data'] = $referencesData;
            $data[7]['count'] = $references_count_page;

            /**灵感**/
            $inspirationData = $this->getCombineData(8, 0, 9, $countInspiration, true, $condition);
            $inspiration_count_page = $countInspiration ? ceil($countInspiration / 3) : 1;    //总页数
            $data[8]['data'] = $inspirationData;
            $data[8]['count'] = $inspiration_count_page;

            /**图案库**/
            $patternsData = $this->getCombineData(9, 0, 3, $countPatterns, true, $condition);
            $patterns_count_page = $countPatterns ? ceil($countPatterns / 3) : 1;  //总页码数
            $data[9]['data'] = $patternsData;
            $data[9]['count'] = $patterns_count_page;

            // 链接构造
            $gen = $this->common_model->getGenIndInfo(3);    // T台发布 品牌库
            $genInd = $this->common_model->getGenIndInfo(4);    // 未来趋势 潮流解析 款式站 手稿合辑 设计素材
            $data['gen'] = $gen;
            $data['genInd'] = $genInd;
            //发布会成衣条件
            $tshowpic = $this->isTshowpic($iGender, $iIndustry);
            if ($tshowpic) {
                $data['tshowpic'] = 'typ_tshowpic/';
            }

            $this->cache->memcached->save($mem_key, $data, 360000);
        }
        return $data ? $data : [];
    }

    /*
    * 根据栏目id获取推荐报告
    * $iColumnId intval 栏目id
    * $limit intval 查询条数
    * $return array 查询结果
    */
    public function getReportList($conditions_sql, $limit = 6)
    {
        if (empty($conditions_sql) || empty($limit)) return 0;
        $where_sql = $this->makeSqlCondition($conditions_sql);
        if ($where_sql) {
            $sql = 'SELECT * FROM ' . self::T_INDEX_REC_REPORTS . ' WHERE iRecChannel=1 AND ' . $where_sql . ' ORDER BY dCreateTime DESC LIMIT ?';
        } else {
            $sql = 'SELECT * FROM ' . self::T_INDEX_REC_REPORTS . ' WHERE iRecChannel=1 ORDER BY dCreateTime DESC LIMIT ?';
        }
        $res = $this->query($sql, [$limit]);
        return $res;
    }

    /*
    * 根据栏目id获取推荐报告总数
    * $iColumnId intval 栏目id
    * $return 总数
    */
    public function getReportCount($conditions_sql)
    {
        if (empty($conditions_sql)) return 0;
        $where_sql = $this->makeSqlCondition($conditions_sql);
        if ($where_sql) {
            $sql = 'SELECT count(*) as total FROM ' . self::T_INDEX_REC_REPORTS . ' WHERE iRecChannel=1 AND ' . $where_sql . ' ORDER BY dCreateTime DESC';
        } else {
            $sql = 'SELECT count(*) as total FROM ' . self::T_INDEX_REC_REPORTS . ' WHERE iRecChannel=1 ORDER BY dCreateTime DESC';
        }
        $res = $this->query($sql);
        return intval($res[0]['total']);
    }

    public function makeSqlCondition($conditions)
    {
        $where_sql = '';
        if (is_array($conditions)) {
            foreach ($conditions as $key => $val) {
                switch ($val['op']) {
                    case 'find':
                        $where_sql[] = 'find_in_set("' . $val['value'] . '",' . $val['field'] . ')';
                        break;
                    case 'in':
                        $where_sql[] = $val['field'] . ' in (' . $val['value'] . ')';
                        break;
                    case '=':
                        $val['field'] . ' = "' . $val['value'] . '"';
                        break;
                    case 'other':
                        $where_sql[] = $val['value'];
                        break;
                }
            }
            $where_sql = implode(' AND ', $where_sql);
        }
        return $where_sql;
    }

    /*
     * 发布会部分加上成衣条件
     * @param intval $gender   [性别]
     * @param intval $industry [行业]
     * @return bool    [状态]
     */
    public function isTshowpic($gender, $industry)
    {
        $aType = [
            1, // 男装
            2, // 女装
            5, // 童装
            6, // 毛衫
            7, // 牛仔
            8, // 皮革/皮草
            11, // 运动'
        ];
        if ($gender || $industry) {
            if (in_array($gender, $aType) || in_array($industry, $aType)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //专区获取热门词
    public function getSpecialHotKeyWords($type, $refresh = false)
    {
        $manualRefresh = $this->input->get('refresh');
        $memKey = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_hot_keywords_new_' . $type;
        $this->load->driver('cache');
        $hotRecommWords = $this->cache->memcached->get($memKey);
        if (empty($hotRecommWords) || $manualRefresh || $refresh) {
            $condition = "`iStatus`=0 AND `iColumnId`='$type'";
            $hotkeywords = $this->db()->select('iColumnId,sKeywords')
                ->where($condition)
                ->get(self::T_FASHION_T_FM_HOT_KEYWORDS)
                ->result_array();
            $hotRecommWords = $memVal = $item = [];
            foreach ($hotkeywords as $hotkeyword) {
                $item = $this->keyword_to_format_arr($hotkeyword['sKeywords']);
                $hotRecommWords = array_merge($hotRecommWords, $item);
            }
            $this->cache->memcached->save($memKey, $hotRecommWords, 86400);
        }
        return $hotRecommWords;
    }

    //老格式关键词转换成新格式数组输出，新关键词本身为json，输出数组
    private function keyword_to_format_arr($sKeywords)
    {
        if (empty($sKeywords)) {
            return array();
        }
        $keys_arr = json_decode($sKeywords, true);
        if (is_null($keys_arr) || !is_array($keys_arr)) {
            $keys_arr = explode(',', $sKeywords);
            if (is_array($keys_arr)) {
                foreach ($keys_arr as $key => $item) {
                    $keys_arr[$key] = array('keyword' => $item, 'url' => '');
                }
            }
        }
        return $keys_arr;
    }


    public function getRegionMutualData($params, $refresh=false){
        $this->load->model('common_model');
        $paramsStr = implode('_', $params);
        $now = time();
        $tomorrow =strtotime(date('Y-m-d 00:00:00',strtotime('+1 day')));
        $memcach_time = $tomorrow-$now;//缓存时间为每天12点到期
        $memcacheKey = self::KOREA_AND_JANPAN_MUTUAL.'_'.$paramsStr;
        $result = $this->cache->memcached->get($memcacheKey);
        if($result && !$refresh){
            return $result;
        }

        $result = array();
        //广告大片
        $lookbook = $this->getGroupData(71, $params, 'iCountry', 0, 12);
        $result['lookbook'] = $lookbook;
        $_params = $params;
        $this->getLink(71, 'lookbook', $result, $_params);
        //日韩实拍
        $retail = $this->getGroupData(54, $params, 'iCountry', 0, 12);
        $result['retail'] = $retail;
        $this->getLink(54, 'retail', $result, $_params);
        //日韩款式提炼
        $designerbrand = $this->getGroupData(122, $params, 'iCountry', 0, 18);
        $result['designerbrand'] = $designerbrand;
        $this->getLink(4, 'designerbrand', $result, $_params);

        //日韩街拍图库
        $streetsnaps = $this->getGroupData(56, $params, 'iCountry', 0, 3);
        $result['streetsnaps'] = $streetsnaps;
        $this->getLink(56, 'streetsnaps', $result, $_params);

        unset($params['dis'],$_params['dis']);//一下不需要单张dis
        //日韩T台
        $runway = $this->getGroupData(3, $params, 'iCountry', 0, 6);
        $result['runway'] = $runway;
        $this->getLink(3, 'runway', $result, $_params);

        //日韩流行分析
        $reg_arr = $this->reg_arr;
        unset($_params);
        foreach ($reg_arr as $reg => $reg_name) {
            $ch_name = $reg==194?'韩国':'日本';
            if (empty($params['gen'])) {
                $gen = array(1, 2, 5);
                foreach ($gen as $v) {
                    $_params['key'] = $ch_name;
                    $_params['gen'] = $v;
                    $analysis = $this->getGroupData(2, $_params, 'iRegion', 0, 3);
                    $result['analysis'][$reg_name][$v] = $analysis;
                    unset($_params['gen'],$_params['key']);
                }
                $result['analysis'][$reg_name]['link'] = $this->common_model->getLink(2) . $this->parseParams($_params) . '?key=' . $ch_name;

            } else {
                $_params = $params;
                $_params['key'] = $ch_name;
                $analysis = $this->getGroupData(2, $_params, 'iRegion', 0, 9);
                $result['analysis'][$reg_name] = $analysis;
                unset($_params['key']);
                $result['analysis'][$reg_name]['link'] = $this->common_model->getLink(2) . $this->parseParams($_params) . '?key=' . $ch_name;
            }
        }

        //日韩流行画册
        unset($_params['gen']);//删除性别
        $_params['reg'] = 194;
        $magazine = $this->MutualRegData($_params, 130);
        $result['magazine']['korea'] = $magazine;
        $_params['reg'] = 209;
        $magazine = $this->MutualRegData($_params, 130);
        $result['magazine']['japan'] = $magazine;
        $result['magazine']['link'] = '/books/magazine/';
        $this->cache->memcached->save($memcacheKey, $result, $memcach_time);
        return $result;
    }


    public function MutualRegData($params, $columnId, $isAjax = false){
        $this->load->model(['styles_model','common_model']);
        $result = array();
//        $params['page'] = 1;//默认第一页
        switch($columnId){
            case 50:
            case 55:
            case 56:
            case 122:
            case 123:
            case 54://获取日韩实拍数据
                $this->load->model('styles_model');
            $params = $this->common_model->parseParams($params, 2);
                $powers = $this->common_model->getPowers(4, $params, $columnId);
                $result = $this->styles_model->ajaxList($params, $columnId, $powers, $isAjax);
                break;
            case 3://T台
                $this->load->model('runways_model');
                $params = $this->common_model->parseParams($params, 2);
                $powers = $this->common_model->getPowers(3, $params, $columnId);
                $result = $this->runways_model->ajaxList($params, $columnId, $powers, $isAjax);
                break;
            case 130://日韩流行画册
                $this->load->model('books_model');
                $aSort = array('order_time' => 'DESC');
                $totalCount = 0;
                $offset = 0;
                if($params['reg'] == 194){
                    $options[] = 'Korea';
                }else{
                    $options[] = 'Japan';
                }

                if(isset($params['gen'])){
                    switch ($params['gen']){
                        case 1:
                            $search = '男';
                            break;
                        case 2:
                            $search = '女';
                            break;
                        default:
                            $search = '童';
                            break;
                    }
                    $options[] = new MongoRegex('/' . $search . '/i');
                }
                $pageSize = isset($params['pageSize'])?$params['pageSize']:3;
                if(isset($params['pageSize'])) unset($params['pageSize']);
                $result = $this->books_model->getMagazineLists($options, $offset, $pageSize, $aSort, $totalCount);
                break;
            case 71://广告大片
                $this->load->model('books_model');
                $params = $this->common_model->parseParams($params, 2);
                $powers = $this->common_model->getPowers(6, $params, $columnId);
                $result = $this->books_model->ajaxList($params, $columnId, $powers, $isAjax);
                break;
            case 2://流行分析 添加keyword='韩国|日本'
                $this->load->model('analysis_model');
                $powers = $this->common_model->getPowers(3, $params, $columnId);
                $pageSize = isset($params['pageSize'])?$params['pageSize']:9;
                if(isset($params['pageSize'])) unset($params['pageSize']);
                $offset = 0;
                $condition['iColumnId'] = $columnId;
//                $condition['aLabelIds'][] = $params['gen'];
                $condition['other'][] = 'combine:(' . $params['key'] . ')';
                $this->analysis_model->getAnalysisLists($params, $columnId, $result, $offset, $pageSize, $powers, $condition);
                break;
        }
        return $result;
    }

    public function getGroupData($columnId,$params, $field='iRegion', $offset=0, $limit=12){
        $reg = array(194,209);//194韩 209日
        $reg_name = $this->reg_arr;
        $ch_name = array(194=>'韩国',209=>'日本');

        $ret = array();
        $this->load->model(['category_model','details_model','common_model']);
        $result = array();
        $condition['iColumnId'] = $columnId;
        if(!isset($params['key'])) {
            $condition['other'][] = "(({$field}:{$reg[0]} OR {$field}:{$reg[1]}))";
        }
        if($params['gen']){
            $this->common_model->childGender($params['gen'], $condition);
        }
        if($params['key']){
            $condition['other'][] = 'combine:('.$params['key'].')';
        }
        $param = array();
        $param['group'] = 'true';
        $param['group.limit'] = $limit;
        $param['group.ngroups'] = 'true';
        $param['group.field'] = $field;
        $arSort = array('dCreateTime' => 'DESC', 'pri_id' => 'DESC');//排序
//        $this->category_model->getGroupList('', $field, $condition, $ret, $arSort, $offset, $limit);
        POPSearch::wrapQueryPopFashionMerger('', $condition, $ret, $offset, $limit, $arSort, $param);
        if($params['key']){
            $ch_name = array_flip($ch_name);
            $ret[$field]['groups'][0]['groupValue'] = $ch_name[$params['key']];
        }
        $_params = $params;
        if(!empty($ret[$field]['groups'])){
            foreach($ret[$field]['groups'] as $items) {
                foreach ($items['doclist']['docs'] as $item) {
                    $id = $item['pri_id'];
                    $tableName = $item['tablename'];
                    $data = OpPopFashionMerger::getProductData($id, $tableName);
                    $info = $data[$id];
                    $imgPath = getImagePath($tableName, $info);
                    $info['col'] = $col = array_pop($item['iColumnId']);
                    $info['table'] = getProductTableName($tableName);
                    $info['cover'] = getFixedImgPath($imgPath['cover']);
                    $info['labels'] = $this->details_model->getLabelInfo($tableName, $id, $params, $columnId, 'list', $col);
                    if($columnId==2){
                        $this->load->model('report_model');
                        $info['labels'] = $this->report_model->getLabelInfo($tableName, $id, $columnId, $params, 'list'); // 取列表页标签
                        $info['sTitle'] = isset($info['sTitle'])?$info['sTitle']:$info['title'];
                        $info['columnId'] = isset($info['columnId'])?$info['columnId']:$info['col'];
                        $info['iViewCount'] = isset($info['iViewCount'])?$info['iViewCount']:$info['view_count'];
                        $info['sDesc'] = isset($info['sDesc'])?$info['sDesc']:$info['abstract'];
                    }
                    if($columnId == 3){
                        $info['seasonName'] = $info['iSeason'] ? GetCategory::getOtherFromIds($info['iSeason'], ['sName']) : '';
                        $info['brand_name'] = $info['brand_tid'] ? GetCategory::getBrandOtherFormId($info['brand_tid']) : '';
                        $info['regionName'] = $info['iRegion'] ? GetCategory::getFieldFromId($info['iRegion']) : '';
                        $times = [];
                        if (isset($info['create_time_special']) && $info['create_time_special']) {
                            $times[] = strtotime($info['create_time_special']);
                        }
                        if (isset($info['create_time_focus']) && $info['create_time_focus']) {
                            $times[] = strtotime($info['create_time_focus']);
                        }
                        if (isset($info['create_time']) && $info['create_time']) {
                            $times[] = strtotime($info['create_time']);
                        }
                        if (isset($info['create_time_video']) && $info['create_time_video']) {
                            $times[] = strtotime($info['create_time_video']);
                        }
                        $info['create_time'] = $times ? date('Y-m-d', min($times)) : '';
                        $this->load->model('statistics_model');
                        $info['view_count'] = $this->statistics_model->getViews($tableName, $id, $info);
                    }
                    if ($params['key']) {
                        $result[] = $info;
                    } else {
                        $result[$reg_name[$items['groupValue']]][] = $info;
                    }
                }
            }
        }
        return $result;
    }

    public function getLink($clomnId, $categroy, &$result, $_params){
        $reg = array(194,209);//194韩 209日
        $unset_reg = array(56);
        if(in_array($clomnId, $unset_reg)) {
            unset($_params['reg']);
            $result[$categroy]['link'] = $this->common_model->getLink($clomnId) . $this->parseParams($_params);
        }else{
            $_params['reg'] = $reg[0];
            $result[$categroy]['korea']['link'] = $this->common_model->getLink($clomnId) . $this->parseParams($_params);
            $_params['reg'] = $reg[1];
            $result[$categroy]['japan']['link'] = $this->common_model->getLink($clomnId) . $this->parseParams($_params);
        }
    }


    public function parseParams($params){
        if(!is_array($params) || empty($params)){
            return '';
        }
        $new_params = http_build_query($params);
        return str_replace(['=', '&'],['_', '-'], $new_params).'/';
    }
}