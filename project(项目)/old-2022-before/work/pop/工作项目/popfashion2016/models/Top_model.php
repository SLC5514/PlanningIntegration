<?php

/**
 * Top热榜
 * Created by PhpStorm.
 * User: dsx
 * Date: 2020/3/18
 * Time: 12:04
 * @property-read common_model $common_model
 */
class Top_model extends POP_Model
{
    public $user_id;
    public $type_name;
    private $filter_table = 'app_user_filter';
    public $time_range = [
        4 => ["id" => "4", "name" => "近7天", 'type' => 'time_range','days'=>7],
        5 => ["id" => "5", "name" => "近15天", 'type' => 'time_range','days'=>15],
        1 => ["id" => "1", "name" => "近30天", 'type' => 'time_range','days'=>30],
        2 => ["id" => "2", "name" => "近60天", 'type' => 'time_range','days'=>60],
        3 => ["id" => "3", "name" => "近90天", 'type' => 'time_range','days'=>90],
    ];
    public $dict_field = ['season' => 'iSeason', 'gender' => 'iGender', 'category' => 'iCategory', 'industry' => 'iIndustry', 'time_range' => 'iTimeRange'];
    public  $scene_type = 'hot_clothing';

    private $tables = array(
        'product_brand',
        'product_marketphoto_item',
        'product_ordermeeting',
        'product_perform',
        'product_streetitem',
        'product_style_graphic',
        'product_style_graphic_china',
        'product_tideleader'
    );


    public function __construct()
    {
        $this->user_info = get_cookie_value();
        $this->user_id = $this->user_info['id'];//主账号id
        $this->type_name = [1 => '男', 2 => '女', 5 => '童', 'hot' => ''];
        $this->refresh = $this->input->get_post('refresh');
    }

    //根据type获取热榜列表数据
    public function getListDataByType($type = '', $filter_id = '', $limit = 12,$offset =0)
    {
        $return_data = [];
        if (!$type) {
            return [];
        }
        $total_count = 0;
        if (in_array($type, [1, 2, 5, 'hot'])) {

            //男女童，热门榜-达观数据（热门推荐）
            $cate_id = '服装_款式';
            if (!empty($this->type_name[$type])) {
                $cate_id = '服装_款式_性别_' . $this->type_name[$type];
            }
            $cnt = 64;
            $all_pop_ids = $this->getDataGrandApiHot($cate_id,'',$cnt);
            $total_count = count($all_pop_ids);
            if($total_count) {
                $pop_ids = array_slice($all_pop_ids, $offset, $limit);
                $return_data = $this->dealData($pop_ids);
            }

        } elseif ($type == 'custom') {
            //自定义榜
            if (!$filter_id || !is_numeric($filter_id)) {
                return [];
            }
            $where = ['iSite' => 1, 'iUid' => $this->user_id, 'iStatus' => 1,'id'=>$filter_id];

            //查询用户自定义标签
            list(, $rows) = $this->getFilterByWhere($where);
            if (!empty($rows)) {
                $filter_data = $rows[0];
                //获取solr查询条件
                $condition = $this->getTopSolrCondition($filter_data);
                if (!empty($condition)) {
                    $pop_ids = $this->getPopIds($condition);
                    $pop_ids = array_slice($pop_ids, 0, 100);
                    $total_count = count($pop_ids);
                    $pop_ids = array_slice($pop_ids, $offset, $limit);

                    //少于3条 数据较少
                    if ($total_count > 3) {
                        $return_data = $this->dealData($pop_ids);
                    }

                }

            }


        } elseif (in_array($type, ['region1', 'region2'])) {
            //日韩 欧美榜-下载量排行
            $condition = $this->getTopRegionConditions($type);
            $pop_ids = $this->getPopIds($condition);
            $pop_ids = array_slice($pop_ids, 0, 100);
            $total_count = count($pop_ids);
            $pop_ids = array_slice($pop_ids, $offset, $limit);
            $return_data = $this->dealData($pop_ids);

        }

        return array($total_count, $return_data);

    }

    private function getDataGrandApiHot($cate_id = '',$combine = '',$cnt = 64)
    {
        $key = "popfashion_datagrand_top_hot_recommend_{$cate_id}";
        $pop_ids = $this->cache->memcached->get($key);
        if(empty($pop_ids) || $_GET['refresh']) {
            //男女童，热门榜-达观数据（热门推荐）
            $response = DataGrandSiteApi::getHotRecommendGrandData($this->user_id, '', $this->scene_type, $cate_id, '', 0, $cnt);
            $pop_ids = [];
            if ($response['status'] == 'OK' && !empty($response['recdata'])) {
                $pop_ids = [];
                foreach ($response['recdata'] as $item) {
                    $pop_id = explode('_', $item['itemid']);
                    array_shift($pop_id);
                    $pop_ids[] = implode('_', $pop_id);
                }

                $pop_ids = array_slice($pop_ids, 0, $cnt);
                $this->cache->memcached->save($key,$pop_ids,7200);
            }
        }
        return $pop_ids;
    }

    //获取服装solr查询条件
    public function getFashionStyleCondition($type = '')
    {

        $cond = [];
        $cond['iColumnId'] = 4;
        switch ($type) {
            case 1:
            case 2:
                $cond['other'][] = "(aLabelIds:{$type})";
                break;
            case 5:
                $cond['other'][] = "(aLabelIds:(3 OR 4 OR 5))";
                break;
            case 'hot':
                break;
            case 'region1':
                //日韩
                $region = '('.implode(' OR ', [209, 194]).')';
                $cond['other'][] = "(iRegion:{$region} OR iArea:{$region} OR iContinent:{$region} OR  iCountry:{$region})";
                break;
            case 'region2':
                //欧美
                $region_id = 3;
                $cond['other'][] = "(iRegion:{$region_id} OR iArea:{$region_id} OR iContinent:{$region_id} OR  iCountry:{$region_id})";
                break;
            case 'custom':
                break;
        }
        return $cond;

    }

    //查询服装solr数据
    public function getFashionStyleList($condition = [], $offset = 0, $limit = 10)
    {
        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $condition, $result, $offset, $limit, $arSort);
        if (!$totalCount) {
            return [];
        }
        $pop_ids = [];
        foreach ($result as $value) {
            $pop_ids[] = $value['pop_id'];
        }

        $return = $this->dealData($pop_ids);

        return $return;
    }

    //获取服装标签数据
    public function getStyleLabels()
    {
        $filter_id = $this->input->get_post('filter_id');
        //趋势APP固定要取用的单品或品名ID
        $category_ids = [
            '11114', '11116', '11119', '11120', '11122', '11123', '11124', '11143', '11144',
            '11145', '11147', '11148', '11151', '11152', '11160', '11162', '11164',
            '11165', '11166', '11167', '11239', '11805'
        ];

        $selected_data = [];
        $result = ["gender" => [], "category" => [], "season" => [], "industry" => []];
        if (!empty($filter_id)) {
            $selected_data = $this->db()->from($this->filter_table)->where(['id' => $filter_id])->get()->row_array();
        }

        //是否选中
        $result["time_range"] = $this->time_range;
        $result = $this->dictSelectedMark($result, $selected_data);
        $result["time_range"] = array_values($result["time_range"]);
        //1-性别,2-行业,3-单品,4-品名,5-季节
        $dict = $this->getFashionDict();
        $dict = $this->dictSelectedMark($dict, $selected_data);
        //单品品名
        foreach ($category_ids as $category_id) {
            if (isset($dict["category"][$category_id])) {
                $result["category"][] = array_only($dict["category"][$category_id], ['id', 'name', 'selected', 'type']);
            }
        }
        $fields = ["gender", "season", "industry"];
        $solr_exists_data = $this->getSolrExistsData(['website' => 1], 'season');
        $exists_season = isset($solr_exists_data['season']) ? $solr_exists_data['season'] : [];
        foreach ($fields as $field) {
            foreach ($dict[$field] as $item) {
                if (empty($item) || $field == "gender" && in_array($item['id'], [3, 4])) {
                    continue;
                }
                if ($field == "season" && !in_array($item['id'], $exists_season)) {
                    continue;
                }
                if ($field == "industry" && in_array($item['id'], [12, 159])) {
                    continue;
                }
                $result[$field][] = array_only($item, ['id', 'name', 'selected', 'child', 'type']);
            }
        }
        $result['season'] = array_slice($result['season'],0,20);

        return $result;

    }


    private function getSolrExistsData($condition, $fields)
    {
        $params = [
            'facet' => 'true',
            'facet.field' => $fields,
            'facet.limit' => 200,
            'raw' => true
        ];
        $arSort = array('browse_time' => 'desc');
        $res = POPSearch::wrapQueryStatisticsDown('', $condition, $temp, 0, 0, $arSort, $params);
        $result = [];
        foreach ($res['facet_counts']['facet_fields'] as $field => $data) {
            $data = array_filter($data);
            $result[$field] = array_flip($data);
        }
        return $result;
    }


    public function dictSelectedMark($dict, $selected_data)
    {

        foreach ($dict as $_key => $_val) {
            $selected = isset($selected_data[$this->dict_field[$_key]]) && !empty($selected_data[$this->dict_field[$_key]]) ? $selected_data[$this->dict_field[$_key]] : '';
            foreach ($_val as $_k => $v) {
                if ($v['id'] == $selected) {
                    $_val[$_k]["selected"] = "1";
                } else {
                    $_val[$_k]["selected"] = "0";
                }
            }
            $dict[$_key] = $_val;
        }
        return $dict;
    }

    public function getTopTitle($res = [],$glue='')
    {
        if (!$res) {
            return [];
        }
        $return = $info = [];
        //1-性别,2-行业,3-单品,4-品名,5-季节
        foreach ($res as $key => $val) {
            //获取标题
            $attrs = $this->getParamsName($val);
            $title = [];
            $title[] = empty($attrs['season']) ? '' : $attrs['season'];
            $title[] = empty($attrs['gender']) ? '' : $attrs['gender'];
            $title[] = empty($attrs['category']) ? '' : $attrs['category'];
            $title[] = empty($attrs['ind']) ? '' : $attrs['ind'];
            $title = array_filter($title);
            if (empty($title)) {
                $title[] = '服装热门全款';
            }
            //返回值
            $info['iSeason'] = $val['iSeason'];
            $info['iGender'] = $val['iGender'];
            $info['iCategory'] = $val['iCategory'];
            $info['iIndustry'] = $val['iIndustry'];
            $info['iTimeRange'] = $val['iTimeRange'];
            $info['filter_id'] = $val['id'];
            $info['title'] = implode($glue,$title);
            $info['title_arr'] =$title;
            $info['time'] = $this->time_range[$val['iTimeRange']];

            $end_time = date('Y年m月d日');
            $day = $this->time_range[$val['iTimeRange']]['days'];
            $start_time = date('Y年m月d日',strtotime("-{$day} days"));
            $info['date_range'] = "{$start_time}-{$end_time}";
            $info['detail_link'] = "/top/detail/?filter_id={$val['id']}";
            $return[] = $info;
        }

        return $return;

    }

    //tdk
    public function getTdk($type = 'list', $res = [])
    {
        $tdk = array_fill_keys(['t', 'k', 'd'], '');
        if($type=='detail' && empty($res)){
            return $tdk;
        }
        if ($type == 'list') {
            $tdk = [
                't' => 'TOP数据热榜_服装行业大数据_服装款式排行榜_时尚潮流服饰流行款-POP服装趋势网',
                'k' => 'TOP数据热榜,服装行业大数据,服装大数据服装款式排行榜,时尚潮流款式图,服饰流行款',
                'd' => 'POP服装趋势网基于大数据智能分析，打造的服装款式图数据TOP榜单，为服装企业和设计师展示当下时尚潮流的款式图，快速发掘款式灵感，海量热门款式图每日更新，助力设计师服装研发，并兼顾市场潮流动向。'
            ];
        } elseif ($type == 'detail') {

            $attr_name = $this->getParamsName($res);
            $tdk['t'] = implode('-', $attr_name) . "-服装款式大数据-TOP数据热榜-POP服装趋势网";
            $tdk['k'] = implode(',', $attr_name) . ",服装行业大数据,服装款式排行榜,TOP数据热榜";
            $tdk['d'] = "POP服装趋势网基于大数据智能分析，打造的服装款式图数据TOP榜单，为服装企业和设计师展示当下时尚潮流的款式图，快速发掘款式灵感，海量热门款式图每日更新，助力设计师服装研发，并兼顾市场潮流动向。";

        }
        return $tdk;

    }

    //获取中文名
    public function getParamsName($res = [])
    {
        $return = [];
        $attrs = $this->getFashionDict();
        $return['gender'] = $attrs['gender'][$res['iGender']]['name'];
        $return['ind'] = $attrs['industry'][$res['iIndustry']]['name'];
        $return['category'] = $attrs['category'][$res['iCategory']]['name'];
        $return['season'] = $attrs['season'][$res['iSeason']]['name'];
        $return['time'] = $this->time_range[$res['iTimeRange']]['name'];

        return array_filter($return);

    }

    private function getFashionDict()
    {
        //服装只需 性别、行业、季节、单品、品名需要其他后期扩展
        //1-性别,2-行业,3-单品,4-品名,5-季节
        $key = "popfashion_filter_model_attr_fashion";
        $result = $this->cache->memcached->get($key);
        if (empty($result) || $this->refresh) {
        $result = array();
        $sql = "SELECT iType,iAttributeId as id,iAttributePid as pid, sName as name FROM fashion.t_dict_attribute 
                    WHERE iType IN(1,2,3,4,5) AND iStatus=0 AND iAttributePid != 0 ORDER BY iSort DESC,iAttributeId ASC";
        $_result = $this->db()->query($sql)->result_array();
        $itypes = [1 => "gender", 2 => "industry", 3 => "category", 4 => "category", 5 => "season"];
        foreach ($_result as $item) {
            $item["type"] = $itypes[$item['iType']];
            $result[$item["type"]][$item['id']] = array_only($item, ['id', 'pid', 'name', 'type']);
        }
            $this->cache->memcached->save($key, $result, 86400);
        }
        return $result;
    }

    public function getPopIds($condition = [])
    {
        ksort($condition);
        $mem_key = 'pop_fashion_top_model_down_' . date('YmdH') . '_' . md5(http_build_query($condition));
        $this->load->driver("cache");
        $pop_ids = $this->cache->memcached->get($mem_key);
        if ( empty($pop_ids) || !is_array($pop_ids) || isset($_GET['refresh'])) {
            $arSort = ['browse_time' => 'desc'];
            $params = [
                'facet' => 'true',
                'facet.field' => 'pop_id',
                'facet.raw' => 'true',
                'facet.limit' => '150',
                'raw' => true
            ];
            $pop_ids = array();

            $res = POPSearch::wrapQueryStatisticsDown('', $condition, $temp, 0, 0, $arSort, $params);
            if (!empty($res['facet_counts']['facet_fields']['pop_id'])) {
                foreach ($res['facet_counts']['facet_fields']['pop_id'] as $pop_id => $count) {
                    if ($count > 0) {
                        $pop_arr = explode('_', $pop_id);
                        array_shift($pop_arr);
                        $pop_ids[] = implode('_', $pop_arr);
                    }
                }
            }

            $this->cache->memcached->save($mem_key, $pop_ids, 3600);
        }
        return $pop_ids;
    }

    //获取自定义榜单solr查询条件
    public function getTopSolrCondition($condition = [])
    {

        if (!$condition) {
            return [];
        }

        $cond = [];
        $cond['website'] = !empty($condition['iSite']) ? $condition['iSite'] : 1;

        if (!empty($condition['iGender'])) {
            if ($condition['iGender'] == 5) {
                $cond['other'][] = "gender:(3 OR 4 OR 5)";
            } else {
                $cond['other'][] = "gender:{$condition['iGender']}";
            }
        }
        if (!empty($condition['iIndustry'])) {
            $cond['other'][] = "industry:{$condition['iIndustry']}";
        }
        if (!empty($condition['iSeason'])) {
            $cond['other'][] = "season:{$condition['iSeason']}";
        }
        if (!empty($condition['iCategory'])) {
            $cond['other'][] = "category:{$condition['iCategory']}";
        }

        if (!empty($condition['iTimeRange'])) {
            $start_time =$this->time_range[$condition['iTimeRange']]['days'];
            //测试
            $cond['browse_time'] = '[' . date('Y-m-d\T00:00:00\Z', strtotime("-{$start_time} days")) . ' TO *]';

        }

        $cond['tablename'] = $this->tables;

        return $cond;
    }

    //自定义榜标签ID
    public function getFilterByWhere($where = [], $limit = 0, $offset = 1)
    {
        if (empty($where)) {
            $where = ['iSite' => 1, 'iUid' => $this->user_id, 'iStatus' => 1];
        }
        $this->db()->from($this->filter_table)->where($where);
        $count = $this->db()->count_all_results("", FALSE);

        $query = $this->db()->order_by("id DESC")->limit($limit, $offset)->get();
        $result = $query->result_array();

        return [$count, $result];
    }


    //获取日韩和欧美榜查询solr条件
    public function getTopRegionConditions($type = '')
    {
        $condition = [];
        if (!$type) {
            return $condition;
        }

        if ($type == 'region1') {
            //日韩
            $condition['other'][] = "(region:(209 OR 194))";
        } elseif ($type == 'region2') {
            //欧美
            $condition['other'][] = "(region:3)";
        }
        $condition['website'] = 1;
         $condition['browse_time'] = '[' . date('Y-m-d\T00:00:00\Z', strtotime("-1 month")) . ' TO *]';

        return $condition;

    }

    /**
     * pop_id数据处理
     * @param array $pri_id_arr
     * $pri_id_arr = [
     *      'product_style_graphic_1',
     *      'product_style_graphic_2',
     *      'product_style_graphic_3',
     *  ]
     *
     * @return array
     */
    public function dealData($pri_id_arr = [])
    {
        if (empty($pri_id_arr)) {
            return [];
        }
        $this->load->model('datagrand_model');
        $pop_arr = [];
        foreach ($pri_id_arr as $pri_id) {
            $pri_ids = explode('_', $pri_id);
            $id = array_pop($pri_ids);
            $table = implode('_', $pri_ids);
            $pop_arr[$table][] = $id;
        }
        if (!$pop_arr) {
            return [];
        }

        $res = [];
        foreach ($pop_arr as $table => $id_arr) {
            $productData = OpPopFashionMerger::getProductData($id_arr, $table);
            if (!$productData) {
                continue;
            }

            foreach ($productData as $id => $_info) {
                if (!$_info) {
                    continue;
                }

                $info['id'] = $id;
                $info['t'] = getProductTableName($table);
                list($info['col'],) = $this->datagrand_model->getColId($_info);
                $info['cover'] = STATIC_URL1.$_info['sSmallPath'];
                $res[] = $info;
            }
        }

        return $res;
    }
}


