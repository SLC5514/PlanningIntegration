<?php

/**
 * Created by PhpStorm.
 * User: jiang
 * Date: 2020/2/17
 * Time: 15:40
 * @property-read common_model $common_model
 */
class Colortrends_model extends POP_Model
{
    public $regions = array(
        0 => ['id' => '0', 'name' => '全部时装周',],
        272 => ['id' => '272', 'name' => '巴黎时装周',],
        341 => ['id' => '341', 'name' => '纽约时装周',],
        335 => ['id' => '335', 'name' => '米兰时装周',],
        323 => ['id' => '323', 'name' => '伦敦时装周',],
    );
    public $genders = array(
        0 => ['id' => '0', 'name' => '全部',],
        1 => ['id' => '1', 'name' => '男装',],
        2 => ['id' => '2', 'name' => '女装',],
    );
    //特殊设置进行转换
    private $sp_color = [
        140 => "#171717",//黑色
        141 => '#282432',//龙葵紫
        142 => '#23140D',//黑咖啡色
        143 => '#201E1B',//印度墨
        144 => '#1B263A',//暗军蓝
        145 => '#232323',//乌黑
        146 => '#282628',//陨石色
        147 => '#1A1B1B',//酱黑色
    ];
    public $season_cns = ['早春', '春夏', '早秋', '秋冬'];
    private $allYearSeason;
    private $allColors;
    private $firstColors;//一级色
    private $secondColors;//二级色
    private $yearsGroupBySeason;

    //获取颜色(一级或二级色)和单品(或品名)的百分比
    public function getCountPercentByFacet($condition, Array $fields, $round_num = 2, $filter = true)
    {
        $result = array();
        $solr_res = $this->Colortrends_model->solorFacet($condition, $fields);
        foreach ($fields as $field) {
            switch ($field) {
                case 'iFirstColorFirstLevel':
                    $all = $this->getAllColors(0);
                    $out_filed = 'color';
                    break;
                case 'iFirstColorSecondLevel':
                    $all = $this->getAllColors($condition['aAutoColors']);
                    $out_filed = 'color';
                    break;
                case 'sCategory':
                    $all = GetCategory::getSingle();
                    $out_filed = 'category';
                    break;
                case 'sSubCategory':
                    $all = GetCategory::getPinMing();
                    $out_filed = 'category';
                    break;
                default:
                    $all = array();
            }
            $result[$out_filed]['items'] = array();
            $result[$out_filed]['total'] = 0;
            if (!empty($solr_res[$field])) {
                $result[$out_filed]['items'] = array_fill_keys(array_keys($all), null);
                $solr_res[$field] = array_intersect_key($solr_res[$field], $result[$out_filed]['items']);
                $result[$out_filed]['total'] = $total = array_sum($solr_res[$field]);
                $no_colour = $colour = 0;
                foreach ($solr_res[$field] as $id => $count) {
                    if (empty($all[$id])) {
                        continue;
                    }
                    $result[$out_filed]['items'][$id] = [
                        'id' => $id,
                        'name' => in_array($field, ['iFirstColorFirstLevel', 'iFirstColorSecondLevel']) ? $all[$id]['sName'] : $all[$id],
                        'count' => $count,
                        'percent' => $total > 0 ? round(($count / $total) * 100, $round_num) : 0,
                    ];
                    //色彩相关更多参数
                    if (in_array($field, ['iFirstColorFirstLevel', 'iFirstColorSecondLevel'])) {
                        $result[$out_filed]['items'][$id]['color_number'] = $all[$id]['sColor'];
                        $result[$out_filed]['items'][$id]['pantone_number'] = $all[$id]['sColorNumber'];

                        if (in_array($id, [140, 148, 158]) || in_array($all['iPid'], [140, 148, 158])) {
                            $no_colour += $count;//无色彩系
                            $result[$out_filed]['items'][$id]['colour'] = false;
                        } else {
                            $colour += $count;//有色彩系
                            $result[$out_filed]['items'][$id]['colour'] = true;
                        }
                    }
                }
                //色彩的有色系和无色系比例
                if (in_array($field, ['iFirstColorFirstLevel', 'iFirstColorSecondLevel'])) {
                    $result[$out_filed]['count_colour'] = $colour;
                    $result[$out_filed]['count_no_colour'] = $no_colour;
                    $result[$out_filed]['percent_colour'] = $total > 0 ? round(($colour / $total) * 100, $round_num) : 0;
                    $result[$out_filed]['percent_no_colour'] = $total > 0 ? round(($no_colour / $total) * 100, $round_num) : 0;
                }
                if ($filter) {
                    $result[$out_filed]['items'] = array_filter($result[$out_filed]['items']);
                }
            }
        }
        return $result;
    }

    //通过季节id获取名称(此处季节不完全等同字典表季节)
    public function getSeasonById($season_id, $filed = 'name')
    {
        $this->getAllYearSeason();
        if ($filed == 'name') {
            return isset($this->allYearSeason[$season_id]) ? ($this->allYearSeason[$season_id]['year'] . $this->allYearSeason[$season_id]['season']) : '';
        } else {
            return isset($this->allYearSeason[$season_id]) ? $this->allYearSeason[$season_id][$filed] : '';
        }
    }

    //通过颜色ID获取名称
    public function getColorById($color_id, $field = 'sName')
    {
        $this->getAllColors();
        return isset($this->allColors[$color_id][$field]) ? $this->allColors[$color_id][$field] : '';
    }

    public function getAllYearSeason()
    {
        if (empty($this->allYearSeason)) {
            $sql = "SELECT * FROM t_year_season WHERE  `status`=1 ORDER BY `year` DESC,season ASC";
            $res = $this->query($sql);
            foreach ($res as $item) {
                $this->allYearSeason[$item['season_id']] = $item;
            }
        }
        return $this->allYearSeason;
    }

    public function getAllColors($firstColorId = '')
    {
        if (empty($this->allColors)) {
            $this->load->driver('cache');
            $memcacheKey = 't_fashion_t_dict_color_all';
            $res = $this->cache->memcached->get($memcacheKey);
            if (empty($res)) {
                $sql = " SELECT * FROM `t_dict_color` WHERE iStatus=1 ORDER BY iSort DESC,id DESC";
                $res = $this->query($sql);
                $this->cache->memcached->save($memcacheKey, $res, 3600);
            }

            foreach ($res as $item) {
                if (!empty($this->sp_color[$item['id']])) {
                    $item['sColor'] = $this->sp_color[$item['id']];
                }
                if ($item['iPid'] == 0) {
                    $this->firstColors[$item['id']] = $item;
                } else {
                    $this->secondColors[$item['iPid']][$item['id']] = $item;
                }
                $this->allColors[$item['id']] = $item;
            }
        }
        if ($firstColorId === 0) {
            return $this->firstColors;
        } elseif (isset($this->secondColors[$firstColorId])) {
            return isset($this->secondColors[$firstColorId]) ? $this->secondColors[$firstColorId] : [];
        } elseif (isset($this->allColors[$firstColorId])) {
            //如果传入的是二级色，返回其本身
            return isset($this->allColors[$firstColorId]) ? [$firstColorId => $this->allColors[$firstColorId]] : [];
        } else {
            return $this->allColors;
        }
    }

    public function getYearsGroupBySeason($season = '')
    {
        if (empty($this->yearsGroupBySeason)) {
            if (empty($this->allYearSeason)) {
                $this->getAllYearSeason();
            }
            foreach ($this->allYearSeason as $key => $item) {
                $this->yearsGroupBySeason[$item['season']][$key] = $item;
            }
        }
        if (!empty($season)) {
            return isset($this->yearsGroupBySeason[$season]) ? $this->yearsGroupBySeason[$season] : [];
        } else {
            return $this->yearsGroupBySeason;
        }
    }

    //solr的Facet操作
    public function solorFacet(Array $condition, $fileds, $limit = 200)
    {
        $params = [
            'facet' => 'true',
            'facet.field' => $fileds,
            'facet.limit' => (int)$limit,
            'facet.mincount' => 1,
            'raw' => true
        ];
        $arSort = array('dCreateTime' => 'DESC');
        $res = POPSearch::wrapQueryPopFashionMerger('', $condition, $temp, 0, 0, $arSort, $params);
        $result = array();
        foreach ($fileds as $filed) {
            if (!empty($res['facet_counts']['facet_fields'][$filed])) {
                foreach ($res['facet_counts']['facet_fields'][$filed] as $key => $val) {
                    if (strpos($key, ',') === false) {
                        $result[$filed][$key] += $val;
                    } else {
                        $k_arr = array_filter(explode(',', $key));
                        foreach ($k_arr as $k) {
                            $result[$filed][$k] += $val;
                        }
                    }
                }
            }
        }
        return $result;
    }

    public function getList($condition, $limit, $arSort = array('dCreateTime' => 'DESC'))
    {
        $arSort = array('dCreateTime' => 'DESC', 'pri_id' => 'DESC');
        POPSearch::wrapQueryPopFashionMerger('', $condition, $result, 0, $limit, $arSort);
        $list = $this->dealSolorResult($result);
        return $list;
    }

    public function dealSolorResult($result)
    {
        $list = $group_tables = array();
        if (!empty($result)) {
            foreach ($result as $item) {
                $group_tables[$item['tablename']][] = $item['pri_id'];
            }

            foreach ($group_tables as $table => $ids) {
                $rows = OpPopFashionMerger::getProductData($ids, $table);
                if (empty($rows)) {
                    continue;
                }
                $rows = array_filter($rows);
                $t = getProductTableName($table);
                foreach ($rows as $id => $row) {
                    $images = getImagePath($table, $row);
                    $list[] = [
                        'id' => $id,
                        't' => $t,
                        'col' => 50,
                        'cover' => $images['cover'],
                    ];
                }
            }
        }
        return $list;
    }

    //获取最新的推荐色
    public function getLastRecommendColor()
    {
        $sql = "SELECT A.*,B.year,B.season FROM `t_recommend_color` AS A LEFT JOIN t_year_season AS B ON A.season_id = B.season_id WHERE A.status=1 ORDER BY B.year DESC,B.season ASC LIMIT 1";
        $result = $this->db()->query($sql)->row_array();
        if (!empty($result)) {
            $result['recommend_colors'] = json_decode($result['recommend_colors'], true);
        }
        return $result;
    }
}