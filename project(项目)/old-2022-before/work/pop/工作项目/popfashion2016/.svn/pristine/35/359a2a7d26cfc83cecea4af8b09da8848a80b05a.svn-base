<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 以色搜图 专用model类
 * @property-read common_model $common_model
 */
class Colorsearch_model extends POP_Model
{
    const T_FASHION_COLOR_USE_LOG = '`fashion`.`fashion_color_use_log`';// 普通用户以色搜图使用次数表
    const COLOR_USE_MEMCACED_KEY_PREFIX = 'COLOR_SEARCH_COLOR_USES_';

    protected $filterData;
    public $column_style = 50; // 款式-秀场提炼
    public $column_pattern = 82; // 图案-图案素材

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model']);
    }

    // 获取一级色系  缓存
    public function get_first_color()
    {
        $this->load->driver('cache');
        $mem_key = 'COLOR_SEARCH_FIRST_COLOR_2020';
        $sAssortColor = $this->cache->memcached->get($mem_key);
        if (empty($sAssortColor) || $this->input->get_post('refresh')) {
            $field = 'iFirstColorFirstLevel';
            $condition = [];
            $condition['iColumnId'] = [$this->column_style, $this->column_pattern];// 50-款式秀场提炼 | 82-图案图案素材

            $sAssortColor = $this->get_facet_data($field, $condition);
            $sAssortColor = !empty($sAssortColor) ? array_slice($sAssortColor, 0, 16) : [];

            $this->cache->memcached->save($mem_key, $sAssortColor, 3600);
        }
        return $sAssortColor;
    }

    /**
     * 取二级色系  默认1-黄色
     * @param int|string $first_color_id 一级色
     * @return array
     */
    public function get_second_color($first_color_id)
    {
        if (empty($first_color_id)) {
            return [];
        }
        $field = 'iFirstColorSecondLevel'; // 二级色
        $condition = [];
        $condition['iColumnId'] = [$this->column_style, $this->column_pattern];// 50-款式秀场提炼 | 82-图案图案素材
        $condition['iFirstColorFirstLevel'] = $first_color_id;// 一级色

        $sAssortColor = $this->get_facet_data($field, $condition);
        return !empty($sAssortColor) ? $sAssortColor : [];
    }

    // 获取一级色与二级色的公共方法
    public function get_facet_data($field, $cond)
    {
        $this->load->model('styles_model');

        $param = $rows = [];
        $param['facet'] = 'true';
        $param['facet.field'] = [$field];
        $param['raw'] = true;

        $resAnalysis = POPSearch::wrapQueryPopFashionMerger('', $cond, $rows, 0, 1, ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'], $param);

        $facet_value = $resAnalysis ? array_filter($resAnalysis['facet_counts']['facet_fields'][$field]) : [];
        $total = $resAnalysis['response']['numFound'];

        if (empty($facet_value)) {
            return [];
        }
        // 颜色字典
        $colors = $this->styles_model->getColorDict();

        $dataAnalysis = [];
        foreach ($facet_value as $colorid => $count) {
            if (isset($colors[$colorid]) && !empty($count)) {
                $tmp = [];
                $tmp['id'] = $colorid;
                $tmp['iPid'] = $colors[$colorid]['iPid'];
                $tmp['name'] = $colors[$colorid]['sName'];// 颜色中文名
                $tmp['colorNumber'] = $colors[$colorid]['sColorNumber'];// 潘通色
                $tmp['percentage'] = round(($count / $total) * 100, 1);
                $tmp['itemStyle']['normal']['color'] = $colors[$colorid]['sColor'];// rgb
                $dataAnalysis[] = $tmp;
            }
        }
        if (!empty($dataAnalysis)) {
            $dataAnalysis = twoDimensionSort($dataAnalysis, 'iSort');// 色系排序
            $dataAnalysis = array_values($dataAnalysis);
        }

        $sAssortColor = [];
        if (!empty($dataAnalysis)) {
            foreach ($dataAnalysis as $color) {
                $sAssortColor[] = [
                    'id' => $color['id'],
                    'sName' => $color['name'],
                    'iPid' => $color['iPid'],
                    'sColorNumber' => $color['colorNumber'],// 潘通色
                    'percentage' => $color['percentage'],//占比
                    'sAlias' => $color['itemStyle']['normal']['color'],
                ];
            }
        }
        return $sAssortColor;
    }

    // 获取色彩搜图筛选项
    public function getConditions()
    {
        // 色系
        $color = $this->input->post_get('aco');
        $color = !empty($color) ? (is_array($color) ? $color : explode(',', $color)) : [];

        $firstColorData = $this->get_first_color();// 一级色不作为条件
        $firstColorAllow = array_column($firstColorData, 'id');
        if (!empty($color) && array_intersect($firstColorAllow, $color) || empty($color)) {
            return [false, []];
        }

        // 栏目
        $_columnId = $this->input->post_get('col');
        $col = !empty($_columnId) ? $_columnId : [$this->column_style, $this->column_pattern];
        $columnId = !empty($col) ? (is_array($col) ? $col : explode(',', $col)) : [];

        // 性别
        $gender = $this->input->post_get('gen');

        // 获取款式栏目的标签  50
        $styleCondition = $solrCondition = [];
        if (in_array($this->column_style, $columnId)) {
            $industry = $this->input->post_get('ind');// 行业
            $season = $this->input->post_get('sea');// 季节
            $category = $this->input->post_get('cat');// 单品
            // 栏目
            $styleCondition[] = "iColumnId:" . $this->column_style;
            // 行业
            if (!empty($industry)) {
                $styleCondition[] = 'aLabelIds:' . $industry;
            }
            // 季节
            if (!empty($season)) {
                $styleCondition[] = 'aLabelIds:' . $season;
            }
            // 品类
            if (!empty($category)) {
                $styleCondition[] = 'aLabelIds:' . $category;
            }
            // 二级色系
            if (!empty($color)) {
                $styleCondition[] = 'iFirstColorSecondLevel:(' . implode(' OR ', $color) . ')';
            }
        }
        // 合并solr条件
        $solrCondition[] = !empty($styleCondition) ? ' ( ' . implode(' AND ', $styleCondition) . ' ) ' : '';

        // 获取图案素材栏目的标签  82
        $paternCondition = [];
        if (in_array($this->column_pattern, $columnId)) {
            $content = $this->input->post_get('con');// 图案内容（只获取一级）
            // 图案内容
            if (!empty($content)) {
                $paternCondition[] = 'aLabelIds:' . $content;
            }
            // 只获取服装  aWebsite = 1
            $paternCondition[] = 'aWebsite:1';
            // 栏目
            $paternCondition[] = 'iColumnId:' . $this->column_pattern;
            // 二级色系
            if (!empty($color)) {
                $paternCondition[] = 'iFirstColorSecondLevel:(' . implode(' OR ', $color) . ')';
            }
        }
        // 合并solr条件
        $solrCondition[] = !empty($paternCondition) ? ' ( ' . implode(' AND ', $paternCondition) . ' ) ' : '';

        // 构建solr条件
        $conditions = [];
        // 性别
        if (!empty($gender)) {
            if (in_array($gender, [3, 4, 5])) {
                $conditions['other'][] = '(aLabelIds:3 OR aLabelIds:4 OR aLabelIds:5)';
            } else {
                $conditions['other'][] = 'aLabelIds:' . $gender;
            }
        }
        // 图案 + 款式的solr条件
        $solrCondition = array_filter($solrCondition);
        if (!empty($solrCondition)) {
            $conditions['other'][] = ' ( ' . implode(' OR ', $solrCondition) . ' ) ';
        }
        return [true, $conditions];
    }

    // 获取色彩搜图的列表
    public function getColorList($conditions, $offset = 0, $limit = 60)
    {
        if (empty($conditions)) return [];

        $this->load->model(['details_model']);
        $result = $lists = [];
        $aSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        $total = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $aSort);
        if (!empty($result)) {
            foreach ($result as $key => $val) {
                $info = [];
                $id = $val['pri_id'];
                $tableName = $val['tablename'];
                $iColumnPid = $val['iColumnId'][0];
                $iColumnId = end($val['iColumnId']);
                $productData = OpPopFashionMerger::getProductData($id, $tableName);

                // 去除家纺数据
                $allow_site = 'fashion';
                $channel_site = isset($productData[$id]['channel']) ? $productData[$id]['channel'] : 'fashion';
                if (!in_array($allow_site, explode(',', $channel_site))) {
                    $productData = array();
                }
                if (empty($productData)) {
                    $total = intval($total) > 0 ? intval($total) - 1 : 0;
                    continue;
                }

                // 具体数据
                $productData = $productData[$id];

                $info['id'] = $id;
                $info['col'] = $iColumnId;
                $info['t'] = getProductTableName($tableName);
                $imgPath = getImagePath($tableName, $productData);// 图片路径
                $info['cover'] = getFixedImgPath($imgPath['cover']);

                // 图案--图案素材(82)
                if ($iColumnId == 82) {
                    $_info = $this->details_model->getPicInfo($info['id'], $tableName, '', $info['col']);
                    $info['create_time'] = $_info['create_time'];
                    $info['date'] = date('Y-m-d', strtotime($info['create_time']));
                    $labels = $this->details_model->getLabelInfo($tableName, $info['id'], '', $iColumnId, 'list');
                    $info['labels'] = !empty($labels) ? array_values(array_column($labels, 'name', 'id')) : [];
                    $info['detail_url'] = "/details/style/t_{$info['t']}-id_{$info['id']}-col_{$info['col']}/";
                } else {
                    // 款式
                    $createTime = isset($productData['create_time']) ? $productData['create_time'] : $productData['dCreateTime'];
                    $info['create_time'] = $createTime;
                    $info['date'] = date('Y-m-d', strtotime($createTime));
                    $labels = $this->details_model->getLabelInfo($tableName, $id, '', $iColumnPid, 'list', $iColumnId);
                    $info['labels'] = !empty($labels) ? array_values(array_column($labels, 'name', 'id')) : [];

                    $info['detail_url'] = "/details/style/t_{$info['t']}-id_{$info['id']}-col_{$info['col']}/";
                }
                $lists[] = $info;
            }
        }
        return [intval($total), $lists];
    }

    // 筛选项
    public function get_interface_filter($conditions)
    {
        if (!empty($this->filterData)) {
            return $this->filterData;
        }

        $params = $lists = $rows = [];
        // 款式-单品sCategory、季节iSeason、行业sIndustry、性别sGender | 图案素材-季节iSeason、图案内容sPatternContent
        $field = ['sCategory', 'iSeason', 'sIndustry', 'sGender', 'sPatternContent'];
        $params['facet'] = 'true';
        $params['facet.limit'] = -1;
        $params['facet.field'] = $field;
        $params['raw'] = true;

        $result = POPSearch::wrapQueryPopFashionMerger('', $conditions, $rows, 0, 0, [], $params);
        $facet_data = $result['facet_counts']['facet_fields'];

        if (empty($facet_data)) {
            return [];
        }

        foreach ($facet_data as $field => $value) {
            if (is_array($value) && !empty($value)) {
                $groupValue = $return = [];
                foreach ($value as $key => $val) {
                    if (strpos($key, ',')) {
                        foreach (explode(',', $key) as $k => $v) {
                            if (!empty($v) && !empty($val) && !in_array($v, $groupValue)) {
                                $name = GetCategory::getAttrNameById($v);
                                $groupValue[$v] = !empty($name) ? $name : '';
                            }
                        }
                    } else {
                        // id存在并且数量不为空
                        if (!empty($key) && !empty($val) && !in_array($v, $groupValue)) {
                            $name = GetCategory::getAttrNameById($key);
                            $groupValue[$key] = !empty($name) ? $name : '';
                        }
                    }
                }
            }
            //有数据的标签id
            $filters[$field] = array_filter($groupValue);
        }
        //只取图案内容一级标签
        $patternDict = $this->getPatternContentOne();
        $filters['sPatternContent'] = array_intersect_key($patternDict, $filters['sPatternContent']);
        // 季节排序
        $seasonDict = GetCategory::getSeason('', 'sName');
        $iSeasonArr = array_intersect_key($seasonDict, $filters['iSeason']);
        $filters['iSeason'] = $this->getAttrSortData($iSeasonArr);

        if (!empty($filters['sGender'])) {
            if (!empty($filters['sGender'][3]) || !empty($filters['sGender'][5])) {
                $filters['sGender'][5] = '童装';
                unset($filters['sGender'][3]);
                unset($filters['sGender'][4]);
            }
        }

        $lists['filters'] = array_filter($filters);
        $this->filterData = $lists;
        return $this->filterData;
    }

    // 排序的数据结构
    public function getAttrSortData($data)
    {
        if (empty($data)) {
            return array();
        }
        if (!is_array($data)) {
            $data = explode(',', $data);
        }

        $result = array();
        foreach ($data as $id => $name) {
            $result[] = [
                'id' => $id,
                'name' => $name,
            ];
        }
        return $result;
    }

    // 取出图案内容--图案内容一级标签名称/id
    public function getPatternContentOne()
    {
        $element = GetCategory::getAttrValueByType(25, 1, 0, ['sName', 'iDisplay', 'iAttributeId']);
        // 过滤掉图案内容一级前台不显示的标签
        $element = array_filter($element, function ($val) {
            return $val['iDisplay'];
        });

        // 取出图案内容一级标签名称/id
        $result = !empty($element) ? array_column($element, 'sName', 'iAttributeId') : [];
        return $result;
    }

    // 普通用户使用次数表
    public function getNormalColorSearchLog($refresh = false)
    {
        $this->load->driver('cache');
        $user_id = getUserId();
        $mem_key = self::COLOR_USE_MEMCACED_KEY_PREFIX . $user_id;
        $total = $this->cache->memcached->get($mem_key);
        if (empty($total) || $refresh) {
            $sql = "SELECT count(*) as total FROM  " . self::T_FASHION_COLOR_USE_LOG . " WHERE `user_id`={$user_id}";
            $data = $this->query($sql);
            $total = !empty($data) ? $data[0]['total'] : 0;

            $this->cache->memcached->save($mem_key, $total, 3600);
        }
        return $total;
    }

    // 普通用户使用次数增加
    public function addNormalColorSearchLog()
    {
        $powers = memberPower();
        if ($powers['P_UserType'] != 4) {
            return [false, '不是普通用户', 1003];
        }
        $total = $this->getNormalColorSearchLog(true);
        if (intval($total) >= 20) {
            return [false, '普通用户使用次数超限', 1001];
        }
        $insert_data = [
            'user_id' => getUserId(),
            'create_time' => date('Y-m-d H:i:s'),
        ];
        $result = $this->executeSave(self::T_FASHION_COLOR_USE_LOG, $insert_data);
        if (!empty($result)) {
            $total_count = $this->getNormalColorSearchLog(true);
            return [$total_count, 'ok', 200];
        } else {
            return [false, '网络开小差', 1004];
        }
    }
}