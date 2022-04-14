<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 搜索栏目 专用model类
 * @property-read common_model $common_model
 */
class Search_model extends POP_Model
{
    private $suffix = '/';
    protected static $base_url = '/search/';

    // 无vip权限报告栏目--封面图
    protected static $vipReportCoverPath = "/global/images/search/vip_report_cover/";
    protected static $vipReportCover = [
        '1_content_list_massk_vip.png',
        '2_content_list_massk_vip.png',
        '3_content_list_massk_vip.png',
        '4_content_list_massk_vip.png',
        '5_content_list_massk_vip.png',
        '6_content_list_massk_vip.png',
    ];

    //【更多】栏目--封面图
    protected static $moreCoverPath = '/global/images/search/more_cover/';
    protected static $moreColumnInfo = [
        90 => ['column_name' => '灵感源', 'cover' => '90_inspirations.jpg', 'url' => '/inspiration/'],
        72 => ['column_name' => '单品合辑', 'cover' => '72_style_collections.jpg', 'url' => '/books/collections/'],
        80 => ['column_name' => '款式模板', 'cover' => '80_design_sketches.jpg', 'url' => '/references/design/'],
        81 => ['column_name' => '款式细节', 'cover' => '81_style_details.jpg', 'url' => '/references/details/'],
        84 => ['column_name' => '服饰品', 'cover' => '84_accessories.jpg', 'url' => '/references/accessories/'],
        85 => ['column_name' => '店铺陈列', 'cover' => '85_visual_merchandise.jpg', 'url' => '/references/visual/'],
        115 => ['column_name' => '快反应系列', 'cover' => '115_apm.jpg', 'url' => '/books/fast/'],
        116 => ['column_name' => '灵感视频', 'cover' => '116_inspiration_video.jpg', 'url' => '/inspiration/video/'],
        117 => ['column_name' => '展会面料', 'cover' => '117_trade_fair_textiles.jpg', 'url' => '/references/fabricgallery/'],
        131 => ['column_name' => '订货会合辑', 'cover' => '131_order_meeting_collection.jpg', 'url' => '/books/ordermeeting/']
    ];

    //【全部】栏目展示数据取多少条/栏目展示数据的顺序，(键【key】)顺序不可乱
    private static $columnLimit = [1 => 20, 2 => 20, 4 => 30, 82 => 20, 3 => 12, 71 => 21];
    private static $moreColumnTypes = [72 => '篇', 115 => '册', 131 => '册', 130 => '册', 80 => '张', 81 => '张', 117 => '张', 84 => '张', 85 => '张', 124 => '张', 90 => '张', 116 => '张'];

    private $total_all = [];

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'details_model', 'category_model']);
    }

    /**
     * 拼写检查,建议
     * @param $keyword
     * @return array
     */
    public function spellcheck()
    {
        $this->load->config('solr');
        $config = config_item('solr');

        foreach ($config['endpoint'] as &$item) {
            if (isset($item['core'])) {
                $item['core'] = 'hot_key';
            }
        }
        $keyword = $this->dealKeyword();
        if (!$keyword) {
            return [];
        }

        $client = new Solarium\Client($config);
        $query = $client->createQuery($client::QUERY_SELECT);
        $query->setHandler('spell');
        $query->addParam('q', $keyword);

        $res = $client->select($query);

        return $res->getData()['spellcheck']['suggestions'][1]['suggestion'] ?: [];
    }

    /**
     * @获取报告栏目的数据
     * solr: pop_report
     * POPSearch::wrapQueryPopFashionReport
     * 展示栏目：1-趋势解读 | 2-流行分析
     */
    public function getPopReportData($columnId, $params, $offset = 0, $limit = 1)
    {
        $reportResult = $solrSort = $data = $solrParams = [];
        // solr条件
        $lists = $this->getCommonCondition($columnId, $params);
        $condition = isset($lists['condition']) && !empty($lists['condition']) ? $lists['condition'] : [];

        // 是否匹配--2/最新--1（默认）
        $is_report_match = $this->getReportMatch($columnId, $params);
        if ($is_report_match) {
            $solrParams = isset($lists['solrParams']) && !empty($lists['condition']) ? $lists['solrParams'] : [];
        }
        if (!$is_report_match) {
            $solrSort = ['publish_time' => 'DESC'];
        }

        $total = POPSearch::wrapQueryPopFashionReport('', $condition, $reportResult, $offset, $limit, $solrSort, $solrParams);
        if (!empty($reportResult)) {
            $data = $this->dealSolrData($reportResult, $columnId);
            $data['cnt'] = $total ? $total : 0;
        }
        return $data;
    }

    /**
     * @获取其他栏目的数据
     * solr: pop-fashion
     * POPSearch::wrapQueryPopFashionMerger
     * 更多内容（只取数量）：8-90 灵感源（取灵感报告）| 8-116 灵感视频 | 6-72 单品合辑
     *         1-20 快反应 | 6-131 订货会合辑  | 7-80 款式模板
     *         7-81 款式细节 | 7-117 展会面料 | 7-84  服饰品 | 7-85  店铺陈列
     *
     * 展示栏目：3-T台 | 4-款式 | 6-71 广告大片
     *         9-图案 => 82-图案素材  120-大牌花型  124-图案工艺
     */
    public function getPopFashionData($columnId, $params = '', $offset = 0, $limit = 1)
    {
        $searchResult = $solrSort = $data = [];
        // 允许的栏目
        $allow_column = [3, 4, 71, 82, 120];
        if (!in_array($columnId, $allow_column)) return [];

        switch ($columnId) {
            case 4:// 款式
            case 120:// 大牌花型
            case 3:// T台
            case 71:// 广告大片
                $lists = $this->getCommonCondition($columnId, $params);
                $condition = isset($lists['condition']) ? $lists['condition'] : [];
                break;
            case 82:
                // 规则：具体tab图案取全部(包括120-大牌花型)，大牌花型勾选后只取大牌花型的数据(有无数据都显示)
                $condition = $this->getSpecialCondition($columnId, $params, true);
                break;
        }
        $solrSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        $total = POPSearch::wrapQueryPopFashionMerger('', $condition, $searchResult, $offset, $limit, $solrSort);
        if ($searchResult) {
            $data = $this->dealSolrData($searchResult, $columnId);
            $data['cnt'] = $total ? $total : 0;
        }
        return $data;
    }

    // 全部--同步渲染
    public function getAllShowData($params = '', $limit_num = 3)
    {
        $data = [];
        $columnArr = $this->getAllCommon($params);
        if (empty($columnArr)) {
            return [];
        }
        if (count($columnArr) >= $limit_num) {
            $column_show = array_slice($columnArr, 0, $limit_num, true);
            // 异步加载
            $other_json_show = array_filter(array_slice($columnArr, $limit_num, 10, true));
            $data['have_column_show'] = !empty($other_json_show) ? implode(',', array_keys($other_json_show)) : '';
        } else {
            $column_show = $columnArr;
            $data['have_column_show'] = '';
        }
        if (!empty($column_show)) {
            foreach ($column_show as $col => $num) {
                $limit = in_array($col, array_keys(self::$columnLimit)) ? self::$columnLimit[$col] : 10;
                switch ($col) {
                    case 1:
                    case 2:
                        $data[$col] = $this->getPopReportData($col, $params, 0, $limit);
                        break;
                    case 82:
                        // 全部的图案素材
                        $data[$col] = $this->getPopFashionData($col, $params, 0, $limit);
                        break;
                    case 3:
                    case 71:
                    case 4:
                        $data[$col] = $this->getPopFashionData($col, $params, 0, $limit);
                        break;
                }
            }
        }
        return $data;
    }

    // 全部--异步渲染(单条取值)
    public function getAllJsonData($params = '', $col = '')
    {
        $data = [];
        $allow_column = [82, 3, 71];

        // 异步加载
        $columnArr = $this->getAllCommon($params);
        $column_show = array_slice($columnArr, 0, 3, true);
        if (in_array($col, $column_show)) {
            return [];
        }

        if (!in_array($col, $allow_column)) return [];

        $limit = in_array($col, array_keys(self::$columnLimit)) ? self::$columnLimit[$col] : 10;
        switch ($col) {
            case 82:
                $data[$col] = $this->getPopFashionData($col, $params, 0, $limit);
                break;
            case 3:
            case 71:
                $data[$col] = $this->getPopFashionData($col, $params, 0, $limit);
                break;
        }

        $data = !empty($data) ? array_filter($data) : [];
        return $data;
    }

    /**
     * 获取全部数据公共部分
     * @param $params
     */
    private function getAllCommon($params = '')
    {
        // 获取有数据的栏目
        $showColumnArr = $this->getShowDataByTotal($params);
        if (empty($showColumnArr)) {
            return [];
        }
        $column_order = [];
        foreach (self::$columnLimit as $col => $limit) {
            $column_order[$col] = in_array($col, array_keys($showColumnArr)) ? $showColumnArr[$col] : '';
        }
        $column_order = array_filter(array_unique($column_order));
        return $column_order;
    }

    /**
     * @ 获取【更多】数据
     * @param $params
     */
    public function getMoreColumnInfo($params = '')
    {
        $data = [];
        $moreDataTotal = $this->getShowDataByTotal($params);
        $moreDataTotal = array_filter($moreDataTotal);
        if (empty($moreDataTotal)) {
            return [];
        }
        // 从全部栏目中筛选出 => 更多栏目
        $moreColumnArr = array_keys(self::$moreColumnInfo);
        if ($moreDataTotal) {
            foreach ($moreDataTotal as $col => $num) {
                if (!in_array($col, $moreColumnArr)) {
                    unset($moreDataTotal[$col]);
                }
            }
        }
        if ($moreDataTotal) {
            foreach ($moreDataTotal as $col => $num) {
                $index = 'col_' . $col;
                $data[$index]['columnId'] = $col;
                $data[$index]['total'] = $num;
                $data[$index]['type'] = $col ? self::$moreColumnTypes[$col] : '';
                $data[$index]['column_name'] = in_array($col, array_keys(self::$moreColumnInfo)) ? self::$moreColumnInfo[$col]['column_name'] : '';
                $data[$index]['cover'] = in_array($col, array_keys(self::$moreColumnInfo)) ? STATIC_URL2 . self::$moreCoverPath . self::$moreColumnInfo[$col]['cover'] : '';
                // 更多栏目的URL
                $url_base = in_array($col, array_keys(self::$moreColumnInfo)) ? self::$moreColumnInfo[$col]['url'] : '';
                if ($url_base) {
                    list($moreUri, $keyword) = $this->getMoreColumnUri($params, $col);
                    if ($moreUri) {
                        $data[$index]['url'] = $url_base . $moreUri . "/" . $keyword;
                    } else {
                        $data[$index]['url'] = $url_base . $keyword;
                    }
                } else {
                    $data[$index]['url'] = '';
                }
            }
        }
        return $data;
    }

    // 更多栏目的uri
    public function getMoreColumnUri($params = '', $columnId = '')
    {
        $paramsArr = $this->common_model->parseParams($params, 1);
        $key = $this->dealKeyword();
        if ($paramsArr['col']) {
            unset($paramsArr['col']);
        }
        // 更多内容的栏目要去掉不必要的uri参数
        if ($columnId) {
            $paramsArr = $this->parameterFiltering($columnId, $paramsArr);
        }

        $moreUri = '';
        if (!empty($paramsArr)) {
            $moreUri = $this->encodeParams($paramsArr);
        }
        $search_word = '';
        if (!empty($key)) {
            $search_word = $this->common_model->getSearchSufix($key);
        }
        return [$moreUri, $search_word];
    }

    //-----------------------------------------------------------------------
    // @ 统计数量，按照数量来展示页面(全部)
    //-----------------------------------------------------------------------
    public function getShowDataByTotal($params)
    {
        $columnData = $rows = [];
        if (!empty($this->total_all)) {
            return $this->total_all;
        }
        $search_key = $this->dealKeyword();
        $gender = $this->common_model->getGenderByRequest($params);
        $industry = $this->common_model->getIndustryByRequest($params);
        $paramsArr = $this->common_model->parseParams($params, 1);
        $season = isset($paramsArr['sea']) && !empty($paramsArr['sea']) ? $paramsArr['sea'] : '';

        $this->load->driver('cache');
        $mem_key = 'INDEX_ALL_COLUMN_TOTAL_' . $gender . $industry . $season . $search_key;
        $result = $this->cache->memcached->get($mem_key);
        if (empty($result)) {
            $result = [];
            // 报告栏目
            $reportData1 = $this->getPopreportTotal($params);
            array_push($columnData, $reportData1);
            // 其他栏目+更多栏目
            $reportData2 = $this->getPopFashionTotal($params);
            array_push($columnData, $reportData2);
            if ($columnData) {
                foreach ($columnData as $item) {
                    foreach ($item as $col => $num) {
                        $rows[$col] = $num;
                    }
                }
            }
            if ($rows) {
                $result = $this->getArsortTotal($rows);
            }
            $this->cache->memcached->save($mem_key, $result, 300);
        }
        $this->total_all = $result;
        return $this->total_all;
    }

    // 公共的获取PopFashion的数据
    private function getSolrPopFashionCondition($params = '', $filter_sign = false)
    {
        $condition = [];
        // $cond_1 = $this->common_model->getTimeRange();
        // $endTime = date("Y-m-d") . 'T' . date("H:i:s", strtotime("-2 hour"));
        // $cond_2 = '[* TO ' . $endTime . 'Z]';
        if ($filter_sign) {
            // 1, 筛选项条件的solr条件
            $baseConditions = [
                // 4 OR 1 OR 2  取 [ 季节 ] 特殊处理（pop_fashion）
                82 => "((iColumnId:(82) AND aWebsite:1) OR (iColumnId:120))",
                3 => "(iColumnId:(3))",
                4 => "((iColumnId:(4 OR 1 OR 2) OR (iDisplay:1 AND iColumnId:71)))",
                124 => "(iColumnId:(124))",
            ];
        } else {
            // 2,全部数量的solr条件
            $baseConditions = [
                82 => "((iColumnId:(82) AND aWebsite:1) OR (iColumnId:120))",// 性别
                3 => "(iColumnId:(3 OR 85))",//季节和性别
                80 => "(iColumnId:(80 OR 81))",//行业和性别
                84 => "(iColumnId:(84))",//季节
                4 => "((iColumnId:(4) OR (iDisplay:1 AND iColumnId:(71 OR 72 OR 115 OR 131))))",//季节、行业和性别
                90 => "((iColumnId:(90 OR 116 OR 124)) OR (iColumnId:117 AND aWebsite:1))",//无季节、行业和性别
            ];
        }
        $allConditions = [];
        foreach ($baseConditions as $col => $cond) {
            $filterArr = $this->getSolrOtherCondition($col, $params);
            $filter = !empty($filterArr) ? $filterArr[0] : '';
            $allConditions[] = !empty($filter) ? '(' . $cond . ' AND ' . $filter . ')' : $cond;

        }
        $key = $this->dealKeyword();// 关键字
        if (!empty($allConditions)) {
            if ($key) {
                $condition['other'][] = '(' . implode(' OR ', $allConditions) . ')' . " AND combine:(" . $key . ")";
            } else {
                $condition['other'][] = implode(' OR ', $allConditions);
            }
        }
        return $condition;
    }

    // 获取【wrapQueryPopFashionMerger】下的栏目展示总的条数
    private function getPopFashionTotal($params = '')
    {
        $columnData = $searchResult = $condition = $facet_data = [];
        // 构建完整的solr条件
        $condition = $this->getSolrPopFashionCondition($params);
        $solrParams = [
            'facet' => 'true',
            'facet.field' => 'iColumnId',
            'facet.limit' => 100,
            'raw' => true
        ];
        $res = POPSearch::wrapQueryPopFashionMerger('', $condition, $searchResult, 0, 1, [], $solrParams);
        $facet_data = $res['facet_counts']['facet_fields']['iColumnId'];
        $allow_columns = [4, 3, 82, 120, 124, 71, 90, 116, 72, 115, 131, 80, 81, 117, 84, 85];
        if (!empty($facet_data)) {
            foreach ($facet_data as $col => $num) {
                if (in_array($col, $allow_columns)) {
                    $columnData[$col] = $num ?: 0;
                }
            }
        }
        if (!empty($columnData)) {
            /**
             * @特殊处理
             * 图案--全部： 120-大牌花型 + 82--图案素材 + 124-图案工艺 ==> 混合取值
             * 图案--tab具体栏目: 图案素材: 124 + 82 | 大牌花型 :120
             */
            $columnData[82] = intval($facet_data[120]) + intval($facet_data[124]) + intval($facet_data[82]);
            if ($columnData[124]) {
                unset($columnData[124]);
            }

            if ($columnData[120]) {
                unset($columnData[120]);
            }
        }
        return $columnData;
    }

    private function getSolrPopreportCondition($params = '')
    {
        // 性别/季节/行业 等筛选条件拼接
        $filterArr = $this->getSolrOtherCondition(1, $params);
        if (!empty($filterArr)) {
            $condition["other"] = $filterArr;
        }
        $endTime = date("Y-m-d") . 'T' . date("H:i:s", strtotime("-2 hour"));
        $cond_2 = '[* TO ' . $endTime . 'Z]';
        $condition["other"][] = "(column_ids:(1 OR 2) AND publish_time:{$cond_2})";
        // 关键字
        $key = $this->dealKeyword();
        if ($key) {
            $condition["other"][] = "(minor_title:({$key}) OR major_title:({$key}) OR minor_description:({$key}) OR labels_name:({$key}) OR minor_memo:({$key}))";
        }
        return $condition;
    }

    // 报告栏目统计数量
    private function getPopreportTotal($params = '')
    {
        $columnData = $condition = $reportResult = [];
        // 报告栏目
        $reportParams = [
            'facet' => 'true',
            'facet.field' => 'column_ids',
            'facet.limit' => 100,
            'raw' => true
        ];
        $condition = $this->getSolrPopreportCondition($params);
        $rows = POPSearch::wrapQueryPopFashionReport('', $condition, $reportResult, 0, 0, [], $reportParams);
        $facet_data = $rows['facet_counts']['facet_fields']['column_ids'];
        $columnData[1] = $facet_data[1] ?: 0;
        $columnData[2] = $facet_data[2] ?: 0;

        return $columnData;
    }

    // 栏目展示数量 => 排序，取和
    private function getArsortTotal($columnData)
    {
        $columnData = array_filter($columnData);
        arsort($columnData);
        if (!empty($columnData)) {
            $columnData['all'] = array_sum($columnData);
        }
        return $columnData;
    }

    // 处理关键字
    private function dealKeyword()
    {
        $key = '';
        $_keyword = $this->common_model->getKeyword('');
        if ($_keyword) {
            $keyword = str_replace('|||', '', $_keyword);
            //$key = !empty($keyword) ? str_replace(array('"', "'"), '', $keyword) : '';
            $pattern = '/(\+|-|&&|\|\||!|\(|\)|\{|}|\[|]|\^|"|~|\*|\?|:|\/|\\\)/';
            $key = preg_replace($pattern, '\\\$1', $keyword);
        }
        return !empty($key) ? $key : '';
    }

    //-- 统计数量 end ---------------------------------------------------------

    //-----------------------------------------------------------------------
    // @ 处理数据，遍历列表
    //-----------------------------------------------------------------------
    private function dealSolrData($searchResult, $columnId)
    {
        if (empty($searchResult) || empty($columnId)) return [];

        // 趋势企划-1 市场分析-2
        $productData = [];
        if (in_array($columnId, [1, 2])) {
            // 1, 获取id与表名
            $idArr = [];
            foreach ($searchResult as $val) {
                $idArr[] = $val['pri_id'];
            }
            // 获取 [ pop_report ] 报告栏目数据库数据
            $tablename = 't_trend_report';
            $productData = $this->getProductData($idArr, $tablename);
        }

        $resultTemp = [];
        $bool = $this->getUserVipPower();
        $freeTrialReport = $this->getFreeTrialReportIds();
        foreach ($searchResult as $value) {
            $resultTemp['id'] = $value['pri_id'];
            if (in_array($columnId, [1, 2])) {
                $_result = $productData[$resultTemp['id']];
                $tablename = $value['table_name'];
                $_columnId = end($value['column_ids']);
                // sub_report_pri_id--子报告id
                $subReportId = $value['sub_report_pri_id'] ? $value['sub_report_pri_id'] : '';
                // minor_title--子报告标题
                $minor_title = $value['minor_title'] ? $value['minor_title'] : '';
                // minor_description--子报告描述标题
                $minor_description = $value['minor_description'] ? $value['minor_description'] : '';
                // minor_memo--子报告图片标签(图片上的品牌名)
                $minor_memo = $value['minor_memo'] ? $value['minor_memo'] : '';
                // major_cover--主报告封面图
                $major_cover = $_major_cover = '';
                $_major_cover = $value['major_cover'];
                // 主报告封面图加上域名
                if (!empty($_major_cover)) {
                    $major_cover = getStaticUrl($_major_cover) . $_major_cover;
                }
                // minor_cover--子报告封面图
                $minor_cover = $_minor_cover = '';
                $_minor_cover = isset($value['minor_cover']) ? $value['minor_cover'] : '';
                // 子报告封面图加上https域名，外站的不加
                if (!empty($_minor_cover)) {
                    if (strpos($_minor_cover, 'pop-fashion') === false) {
                        $minor_cover = $_minor_cover;
                    } else {
                        $minor_cover = !empty($_minor_cover) ? getFixedImgPath($_minor_cover) : '';
                    }
                }
                $score = $value['score'];// 显示分数
            } else {
                $tablename = $value['tablename'];
                $_columnId = end($value['iColumnId']);
                $_result = OpPopFashionMerger::getProductData($resultTemp['id'], $tablename);
                /*
                // 去除【纯家纺】的【图案】数据
                if ($tablename == 'product_graphicitem') {
                    // channel => jiafang,fashion
                    if (!in_array('fashion', explode(',', $_result[$resultTemp['id']]['channel']))) {
                        continue;
                    }
                }*/
                $_result = $_result[$resultTemp['id']];

                $path = getImagePath($tablename, $_result);
                $resultTemp['cover'] = getFixedImgPath($path['cover']);
            }
            $resultTemp['col'] = $_columnId;// 子栏目ID
            $resultTemp['t'] = getProductTableName($tablename);

            switch ($columnId) {
                case 1:
                case 2:
                    $this->load->model(['statistics_model', 'report_model']);

                    // 时间转为时间戳，前台再由smarty来替换为 Y-m-d
                    $resultTemp['publish_time'] = $_result['dPubTime'] ? strtotime($_result['dPubTime']) : '';
                    $resultTemp['iViewCount'] = $this->statistics_model->getViews($tablename, $resultTemp['id'], $_result);
                    // major_title--主标题
                    $resultTemp['major_title'] = isset($_result['sTitle']) ? $_result['sTitle'] : ($_result['title'] ? $_result['title'] : '');
                    // major_desc--主报告描述
                    $resultTemp['major_desc'] = isset($_result['sDesc']) ? htmlspecialchars_decode(stripcslashes($_result['sDesc'])) : '';
                    // labels_name--主标签
                    $resultTemp['labels'] = $this->report_model->getLabelInfo($tablename, $resultTemp['id'], $columnId, '', 'list');

                    // -- 报告栏目封面 start--------------------------------------------------------------------------
                    /**
                     * @子报告封面图取不到，取主报告封面图 (vip权限) | 非vip权限随机封面
                     * sub_report_pri_id 允许为空(非flash报告)
                     * 试读部分，在试读期间展示VIP权限样式（子报告封面或封面）可以正常查看，非试读期间则回到正常权限样式
                     */
                    if ($bool) {
                        // 1, vip权限（包括部分vip权限）
                        $resultTemp['cover'] = !empty($subReportId) ? (!empty($minor_cover) ? $minor_cover . '!h215w300' : $major_cover) : $major_cover;
                    } else {
                        // 2, 其他权限
                        if (in_array($resultTemp['id'], $freeTrialReport)) {
                            // 免费试读报告封面
                            $resultTemp['cover'] = !empty($subReportId) ? (!empty($minor_cover) ? $minor_cover . '!h215w300' : $major_cover) : $major_cover;
                        } else {
                            // 普通报告随机封面
                            $resultTemp['cover'] = $this->randReportNotVipCoverImage();
                        }
                    }
                    // 1，报告样式1--主报告
                    $resultTemp['sign'] = empty($subReportId) ? 1 : 'other';
                    if ($resultTemp['sign'] == 'other') {
                        // 2，报告样式2--子报告全部取到--true
                        // 3，报告样式3--子报告封面图取得是主报告的封面图--false
                        $resultTemp['sign'] = !empty($minor_cover) ? 2 : 3;
                    }
                    // -- 报告栏目封面 end----------------------------------------------------------------------------

                    // minor_title--子报告标题
                    $resultTemp['minor_title'] = $minor_title;
                    // minor_desc--子报告描述标题
                    $resultTemp['minor_desc'] = $minor_description;
                    // minor_memo--子报告图片标签(图片上的品牌名)
                    $resultTemp['minor_memo'] = $minor_memo;
                    if ($subReportId) {
                        $resultTemp['detailUrl'] = "/details/report/t_report-id_{$resultTemp['id']}-col_{$resultTemp['col']}/?searchId=index_{$subReportId}";
                    } else {
                        $resultTemp['detailUrl'] = "/details/report/t_report-id_{$resultTemp['id']}-col_{$resultTemp['col']}/";
                    }
                    $resultTemp['score'] = $score ? $score : 0;
                    break;
                // 款式（单张）
                case 4:
                    $createTime = isset($_result['create_time']) ? $_result['create_time'] : $_result['dCreateTime'];
                    $resultTemp['publish_time'] = date("Y-m-d", strtotime($createTime));
                    // $_columnId--子栏目ID   $columnId--主栏目ID
                    $resultTemp['labels'] = $this->details_model->getLabelInfo($tablename, $resultTemp['id'], '', $columnId, 'list', $_columnId);
                    $resultTemp['detailUrl'] = "/details/style/t_{$resultTemp['t']}-id_{$resultTemp['id']}-col_{$resultTemp['col']}/";
                    break;
                // 图案（无图案专题，数据结构统一性需要;图案专题取的是报告的数据，这里便不取值了）
                case 82: // 图案素材（默认） = 82-图案素材 + 124-图案工艺(产品要求,82=82+124)
                case 120: // 大牌花型
                    $info = $this->details_model->getPicInfo($resultTemp['id'], $tablename, '', $resultTemp['col']);
                    $resultTemp['publish_time'] = date("Y-m-d", strtotime($info['create_time'])); // 与图案列表取得时间同步
                    // $_columnId--子栏目ID
                    $resultTemp['labels'] = $this->details_model->getLabelInfo($tablename, $resultTemp['id'], '', $_columnId, 'list');
                    $resultTemp['detailUrl'] = "/details/style/t_{$resultTemp['t']}-id_{$resultTemp['id']}-col_{$resultTemp['col']}/";
                    break;
                case 3:// T台
                    $this->load->model('statistics_model');
                    $resultTemp['cover'] = $path['cover'];
                    // 发布时间
                    $times = [];
                    if (isset($_result['create_time_special']) && $_result['create_time_special']) {
                        array_push($times, strtotime($_result['create_time_special']));
                    }
                    if (isset($_result['create_time_focus']) && $_result['create_time_focus']) {
                        array_push($times, strtotime($_result['create_time_focus']));
                    }
                    if (isset($_result['create_time']) && $_result['create_time']) {
                        array_push($times, strtotime($_result['create_time']));
                    }
                    if (isset($_result['create_time_video']) && $_result['create_time_video']) {
                        array_push($times, strtotime($_result['create_time_video']));
                    }
                    $resultTemp['publish_time'] = date('Y-m-d', min($times));
                    // 设计师
                    $resultTemp['brand_name'] = $_result['brand_tid'] ? GetCategory::getBrandOtherFormId($_result['brand_tid']) : '';
                    // 时间/季节
                    $resultTemp['seasonName'] = $_result['iSeason'] ? GetCategory::getOtherFromIds($_result['iSeason'], ['sName']) : '';
                    // 地点
                    $resultTemp['regionName'] = $_result['iRegion'] ? GetCategory::getFieldFromId($_result['iRegion']) : '';
                    // title
                    $resultTemp['title'] = $_result['nme'];
                    $resultTemp['view_count'] = $this->statistics_model->getViews($tablename, $resultTemp['id'], $_result);
                    $resultTemp['detailUrl'] = "/runways/inside/id_{$resultTemp['id']}/";
                    break;
                case 71:// 广告大片 lookbook (brochures)
                    $resultTemp['title'] = stripcslashes($_result['name_text']);
                    // 封面图
                    $resultTemp['cover'] = $this->details_model->getImgfPath($resultTemp['cover'], true);
                    // 发布时间
                    $resultTemp['publish_time'] = $_result['create_time'] ? date("Y-m-d", strtotime($_result['create_time'])) : '';
                    // 1--免费预览  其他--完整预览
                    $resultTemp['iPreviewMode'] = $_result['iPreviewMode'];
                    $resultTemp['sBuyingLinks'] = $_result['sBuyingLinks'] ? $_result['sBuyingLinks'] : '';// 购买链接
                    $resultTemp['detailUrl'] = "/books/seclist/id_{$resultTemp['id']}-t_{$resultTemp['t']}-yl_{$resultTemp['iPreviewMode']}-col_{$columnId}/";
                    break;
            }
            $result['data'][] = $resultTemp;
        }

        // 报告栏目中每一页按照发布时间倒序排列
        /*if (in_array($columnId, array(1, 2))) {
            $resultTmp = twoDimensionSort($result['data'], 'publish_time');
            $result['data'] = $resultTmp;
        }
        */
        return !empty($result) ? array_filter($result) : [];
    }

    //------------------------------------------------------------------
    // @公共条件, $solr_all = true 方便计算solr数量(多条件拼接)
    //------------------------------------------------------------------
    private function getCommonCondition($columnId, $params)
    {
        if (empty($columnId)) return [];
        $condition = $data = $solrParams = [];
        // 关键字
        $key = $this->dealKeyword();
        //-- 筛选条件 -------------------------------------------------
        $filterArr = $this->getSolrOtherCondition($columnId, $params);
        // 性别/季节/行业 等筛选条件拼接
        if (!empty($filterArr)) {
            $condition["other"] = $filterArr;
        }
        //-- 匹配 (报告栏目+全部) ---------------------------------------
        $is_report_match = $this->getReportMatch($columnId, $params);
        //-- solr时间 -------------------------------------------------
        // $cond_1 = $this->common_model->getTimeRange();
        // $endTime = date("Y-m-d") . 'T' . date("H:i:s", strtotime("-2 hour"));
        // $cond_2 = '[* TO ' . $endTime . 'Z]';
        switch ($columnId) {
            case 1: // 趋势解读
            case 2: // 流行分析
                $condition["other"][] = "column_ids:{$columnId}"; // 栏目id
                $sub_title_power = 0.40; // 子报告标题
                $main_title_power = 0.26; // 主标题
                $sub_memo_power = 0.19; // 子报告描述标题
                $main_label_power = 0.11; // 主标签
                $sub_brand_power = 0.14; // 子报告图片标签（图片上的品牌名）
                /**
                 * minor_title^0.40 major_title^0.26 minor_description^0.19 labels_name^0.11 minor_memo^0.14
                 * minor_title--子报告标题 major_title--主标题 minor_description--子报告描述标题 labels_name--主标签 minor_memo--子报告图片标签(图片上的品牌名)
                 * ((minor_title:皮草大衣) OR (major_title:皮草大衣) OR (minor_description:皮草大衣) OR (labels_name:皮草大衣) OR (minor_memo:皮草大衣))
                 */
                $edismax_power = "minor_title^{$sub_title_power} major_title^{$main_title_power} minor_description^{$sub_memo_power} labels_name^{$main_label_power} minor_memo^{$sub_brand_power}";

                // $condition["other"][] = "publish_time:{$cond_2}"; // 时间
                if ($is_report_match) {
                    // 报告栏目(关键字) => 匹配,配合edismax使用
                    if ($key) {
                        $condition["other"][] = "({$key})";
                    }
                } else {
                    if ($key) {
                        // 报告栏目(关键字) => 最新（默认）,不配合edismax使用
                        $condition["other"][] = "(minor_title:({$key}) OR major_title:({$key}) OR minor_description:({$key}) OR labels_name:({$key}) OR minor_memo:({$key}))";
                    }
                }

                // 权重排序
                $solrParams['defType'] = 'edismax';
                $solrParams['qf'] = $edismax_power; // 或者 $solrParams['bf'] = $edismax_power'; // 计算权重 分值要在【加权】前
                // $solrParams['bf'] = "scale(product(0.2,sub(30,div(ms(NOW,publish_time),86400000))),0,3.0)";
                // $solrParams['bq'] = "{!func}sum(recip(ms(NOW,publish_time),1,1,100))";
                $solrParams['boost'] = "recip(ms(NOW,publish_time),3.16e-11,1,1)"; // 发布时间加的权重
                // $solrParams['bq'] = "{!func}recip(rord(publish_time),1,1000,1000)";
                // $solrParams['raw'] = 'true'; // 原始值没有$searchResult, $total会全部显示
                // $solrParams['tie'] = '1';
                $solrParams['fl'] = '*,score'; // 显示分值

                /**
                 * @ 标签没有办法，不带链接标红，不做了，标签是数据库取出的
                 * $solrParams['hl'] = 'true'; // 搜索关键字高亮显示
                 * $solrParams['hl.fl'] = "minor_title,major_title,minor_description"; // 要显示的高亮字段
                 * $solrParams['hl.simple.pre'] = "<em>"; //开始标签
                 * $solrParams['hl.simple.post'] = "<em>"; //结束标签
                 */
                break;
            case 4:// 款式
            case 120:// 大牌花型
            case 3:// T台
            case 124:// 图案工艺
            case 90: // 灵感源（取灵感报告）
            case 116: // 灵感视频
            case 80: // 款式模板
            case 81: // 款式细节
            case 84: // 服饰品
            case 85: // 店铺陈列
                $condition["other"][] = "iColumnId:{$columnId}";
                break;
            //-- 更多 ------------------------------------------
            case 72: // 单品合辑
            case 131: // 订货会合辑
            case 115: // 快反应系列
            case 71: // 广告大片
                $condition["other"][] = "iDisplay:1 AND (iColumnId:{$columnId})";
                break;
            case 117: // 展会面料
            case 82:// 9-图案素材(默认) = 82-图案素材 + 124-图案工艺
                $condition["other"][] = "iColumnId:{$columnId} AND aWebsite:1";
                break;
        }
        if (!in_array($columnId, [1, 2])) {
            if (!empty($key)) {
                $condition['other'][] = "combine:(" . $key . ")";// 关键字搜索
            }
        }
        $data = array_filter(compact('condition', 'solrParams'));
        return $data;
    }

    // 获取参数的solr[other]条件拼接(主要拼接 => 筛选条件(不包括关键字))
    public function getSolrOtherCondition($columnId, $params)
    {
        if (empty($columnId)) return [];

        $condition = $cond = [];
        $paramsArr = $this->common_model->parseParams($params, 1);
        // 性别
        $gender = $this->common_model->getGenderByRequest($params);
        // 行业
        $industry = $this->common_model->getIndustryByRequest($params);
        // 季节
        $season = isset($paramsArr['sea']) && !empty($paramsArr['sea']) ? $paramsArr['sea'] : '';

        $field = 'aLabelIds';
        switch ($columnId) {
            case 1:// 趋势解读
            case 2:// 流行分析
            case 4:// 款式
            case 71:// 广告大片
            case 72: // 单品合辑
            case 131: // 订货会合辑
            case 115: // 快反应系列
                if (in_array($columnId, [1, 2])) {
                    $field = 'labels';
                }
                if ($gender) { // 性别
                    if ($gender == 5) {
                        $_gender = "{$field}:(3 OR 4 OR 5)";
                    } else {
                        $_gender = "{$field}:" . $gender;
                    }
                    $condition[] = $_gender;
                }
                if ($industry) { // 行业
                    $_industry = "{$field}:" . $industry;
                    $condition[] = $_industry;
                }
                if ($season) { // 季节
                    $_season = "{$field}:" . $season;
                    $condition[] = $_season;
                }
                break;
            case 82:
            case 120:
                if ($gender) { // 性别
                    if ($gender == 5) {
                        $_gender = "({$field}:(3 OR 4 OR 5))";
                    } else {
                        $_gender = "{$field}:" . $gender;
                    }
                    $condition[] = $_gender;
                }
                break;
            case 3:// T台
            case 85: // 店铺陈列
                if ($season) { // 季节
                    $_season = "{$field}:" . $season;
                    $condition[] = $_season;
                }
                if ($gender) { // 性别
                    if ($gender == 5) {
                        $_gender = "{$field}:(3 OR 4 OR 5)";
                    } else {
                        $_gender = "{$field}:" . $gender;
                    }
                    $condition[] = $_gender;
                }
                break;
            case 80: // 款式模板
            case 81: // 款式细节
                if ($gender) { // 性别
                    if ($gender == 5) {
                        $_gender = "{$field}:(3 OR 4 OR 5)";
                    } else {
                        $_gender = "{$field}:" . $gender;
                    }
                    $condition[] = $_gender;
                }
                if ($industry) { // 行业
                    $_industry = "{$field}:" . $industry;
                    $condition[] = $_industry;
                }
                break;
            case 84: // 服饰品
                if ($season) { // 季节
                    $_season = "{$field}:" . $season;
                    $condition[] = $_season;
                }
                break;
            case 90: // 灵感源（取灵感报告）
            case 116: // 灵感视频
            case 117: // 展会面料
            case 124: // 图案工艺
                break;
        }
        $condition = array_filter($condition);
        if (!empty($condition)) {
            $cond[] = implode(' AND ', $condition);
        }
        $cond = array_filter($cond);
        return !empty($cond) ? $cond : [];
    }

    // 构建特殊要求的条件
    private function getSpecialCondition($columnId, $params, $is_all = false)
    {
        $condition = $cod = [];
        if (empty($columnId) || !in_array($columnId, [82])) return [];

        switch ($columnId) {
            /**
             * @全部【图案栏目特殊处理】 -- 混合所有取值
             * 图案 全部： 120-大牌花型 + 82--图案素材 + 124-图案工艺 【 混合取值 】
             * 图案 tab具体栏目： 图案素材 ： 124 + 82 | 大牌花型 :120
             */
            case 82:
                $cod[] = $this->getFacetCondition(82, $params);
                $cod[] = $this->getFacetCondition(124, $params);
                if ($is_all) {
                    $cod[] = $this->getFacetCondition(120, $params);
                }
                $cod = !empty($cod) ? array_filter($cod) : [];
                if (!empty($cod)) {
                    $condition['other'][] = !empty($cod) ? implode(' OR ', $cod) : [];
                }
                break;
        }
        return $condition;
    }

    // 单个栏目--完整solr条件
    private function getFacetCondition($columnId, $params)
    {
        if (empty($columnId)) return '';

        $_condition = $this->getCommonCondition($columnId, $params);
        $condition = !empty($_condition['condition']) ? $_condition['condition'] : [];
        if (isset($condition['other']) && !empty($condition['other'])) {
            $condition['other'] = array_filter(array_unique($condition['other']));
            $data = !empty($condition['other']) ? "(" . implode(' AND ', $condition['other']) . ")" : '';
        }
        return !empty($data) ? $data : '';
    }

    //-------------------------------------------------------------------
    // @ 公共筛选 : 所有栏目,构建url
    //-------------------------------------------------------------------
    public function getSelectItems($selectItem, $columnId, $params)
    {
        if (empty($columnId)) return [];

        $columnId = isset($columnId) ? ($columnId == 'all' ? $columnId : intval($columnId)) : 'all';
        $allow_column = ['all', 1, 2, 4, 82, 120, 3, 71];
        if (!in_array($columnId, $allow_column)) {
            return [];
        }

        // tab--站点
        $param_tmp = $this->common_model->parseParams($params, 1);
        if (empty($param_tmp['col']) || !isset($param_tmp['col'])) {
            if ($columnId != 'all') {
                $param_tmp['col'] = $columnId;
                $params = $this->encodeParams($param_tmp);
            }
        }
        // 报告栏目，group不使用edismax,不获取 getCommonCondition方法的 solrParams
        $params_tmp = $this->common_model->parseParams($params, 1);
        if (isset($params_tmp['match']) && !empty($params_tmp['match'])) {
            unset($params_tmp['match']);
            $params = $this->encodeParams($params_tmp);
        }
        //------------------------------------------------------------------
        $selectItems = [];
        $paramToSolrArr = $this->common_model->getKeyValMap();
        $paramTmp = $this->common_model->parseParams($params, 1);
        if (!empty($paramTmp)) {
            foreach ($paramTmp as $key => $val) {
                if (!empty($val)) {
                    $ItemTmp = $paramToSolrArr["$key"];
                    $TmpKey = array_search($ItemTmp, $selectItem);
                    unset($selectItem["{$TmpKey}"]);
                    $selectItems["{$ItemTmp}"] = [];
                }
            }
        }
        //------------------------------------------------------------------
        $items = [];
        switch ($columnId) {
            case 'all':
                $all_lists = [];
                $all_lists[] = $this->dealFilterCategory(82, $params, $selectItem);
                // 季节筛选项--处理，合并
                $items['iSeason'] = $this->dealFilter($all_lists, $selectItem, 'iSeason');
                break;
            case 1:
            case 2:
                $lists = $this->getCommonCondition($columnId, $params);
                $condition = !empty($lists['condition']) ? $lists['condition'] : [];
                $items = $this->getRealCategory($columnId, $condition, $selectItem);
                break;
            case 4:
            case 3:
            case 71:
            case 82:
            case 120:
                $lists = $this->getCommonCondition($columnId, $params);
                $condition = !empty($lists['condition']) ? $lists['condition'] : [];
                $items = $this->getRealCategory($columnId, $condition, $selectItem);
                break;
        }

        // 筛选项排序--季节
        $items = $this->getOrderByFilter($items, 'iSeason');

        // 构建筛选有效数据的筛选项
        $selectItems = [];
        if (!empty($items)) {
            foreach ($items as $key => $val) {
                if (empty($val)) {
                    $selectItems[$key] = [];
                    continue;
                }
                switch ($key) {
                    case 'iSeason': // 季节
                        $selectItems[$key] = $this->getItems($params, $val, 'sea');
                        break;
                }
            }
        }
        return $selectItems;
    }

    // 【全部】条件筛选项--处理,合并
    private function dealFilter($data, $selectItem, $type_sign)
    {
        if (empty($type_sign) || empty($data) || empty($selectItem)) {
            return [];
        }
        $lists = [];
        switch ($type_sign) {
            case 'iSeason':
                if (in_array('iSeason', $selectItem)) {
                    $rows = array_column($data, 'iSeason');
                    if (!empty($rows)) {
                        foreach ($rows as $val) {
                            foreach ($val as $k => $v) {
                                $lists[$k] = $v;
                            }
                        }
                    }
                }
                break;
        }
        return $lists;
    }

    // 筛选项排序--处理
    private function getOrderByFilter($items, $filed)
    {
        if (empty($filed)) {
            return $items;
        }
        $result = [];
        switch ($filed) {
            case 'iSeason':
                $seaSonDict = GetCategory::getSeason('', 'sName');
                if (!empty($seaSonDict)) {
                    foreach ($seaSonDict as $id => $name) {
                        if (!empty($items['iSeason'][$id])) {
                            $result['iSeason'][$id] = $items['iSeason'][$id];
                        }
                    }
                }
                break;
        }
        return !empty($result) ? $result : $items;
    }

    // 筛选项全部批量处理
    private function dealFilterCategory($columnId, $params, $selectItem)
    {
        $condition = $this->getSolrPopFashionCondition($params, true);
        $items = $this->getRealCategory($columnId, $condition, $selectItem);

        return $items;
    }

    // 根据solr筛选条件获取真实存在的数据
    private function getRealCategory($columnId, $condition, $field)
    {
        $rows = $solrParams = [];
        if (empty($columnId) || empty($field)) return [];

        switch ($columnId) {
            case 1:
            case 2:
                // 季节字典 sName--中文
                $searchResult = [];
                $seaDict = GetCategory::getSeason('', 'sName');
                $solrParams = [
                    'facet' => 'true',
                    'facet.field' => 'labels',
                    'facet.limit' => 100,
                    'raw' => true
                ];
                $result = POPSearch::wrapQueryPopFashionReport('', $condition, $searchResult, 0, 0, [], $solrParams);
                $labels = $result['facet_counts']['facet_fields']['labels'];
                if ($labels) {
                    foreach ($labels as $label => $num) {
                        // 获取季节--筛选项
                        if (in_array($label, array_keys($seaDict)) && (intval($num) > 0)) {
                            $rows['iSeason'][$label] = $seaDict[$label];
                        }
                    }
                }
                break;
            case 3:// T台
            case 4:// 款式
            case 71: // 广告大片
            case 82:// 图案素材 = 82-图案素材 + 124-图案工艺(产品要求,82=82+124),无季节
            case 120:// 大牌花型,无季节
                $solrParams = [
                    'facet' => 'true',
                    'facet.field' => $field,
                    'facet.limit' => 100,
                    'raw' => true
                ];

                $searchResult = [];
                $result = POPSearch::wrapQueryPopFashionMerger('', $condition, $searchResult, 0, 0, [], $solrParams);
                // 查询有效的筛选条件
                $labels = $result['facet_counts']['facet_fields'];
                if (!empty($labels)) {
                    foreach ($labels as $field => $value) {
                        // 联动搜索
                        $groupValue = $return = [];
                        foreach ($labels[$field] as $key => $val) {
                            if ($val > 0) {
                                if (strpos($key, ',')) {
                                    foreach (explode(',', $key) as $k => $v) {
                                        $groupValue[] = $v;
                                    }
                                } else {
                                    $groupValue[] = $key;
                                }
                            }
                        }
                        if (!empty($groupValue)) {
                            $field = ($field == 'iDataSource') ? 'iTrendOrManual' : $field;
                            $all = $this->category_model->getAll($field);
                            $return = $this->getReturnData($all, $groupValue, $field);
                            unset($all);
                        }
                        $rows[$field] = $return;
                    }
                }
                break;
        }
        return $rows;
    }

    // 构建url
    private function getItems($params, $arr, $type)
    {
        $ret = [];
        if ($arr) {
            foreach ($arr as $id => $name) {
                $ret[] = array('id' => $id, 'name' => $name, 'link' => $this->getLink($params, $type, $id));
            }
            $ret = isset($ret) && !empty($ret) ? twoDimensionSort($ret, 'id') : [];

            $paramsArr = $this->common_model->parseParams($params, 1);
            if ($paramsArr[$type]) {
                unset($paramsArr[$type]);
            }
            if ($paramsArr['page']) {
                unset($paramsArr['page']);
            }
            $keyword = $this->common_model->getSearchSufix('');

            // 全部的链接
            $uri = $this->encodeParams($paramsArr);
            if (!empty($uri)) {
                $all_link = self::$base_url . $uri . '/' . $keyword;
            } else {
                $all_link = self::$base_url . $uri . $keyword;
            }
            array_unshift($ret, array('id' => 'all', 'name' => '全部', 'link' => $all_link));
        }
        return $ret;
    }


    /**
     * 全站搜索 TDK
     * @param string $spaceKeywords 搜索关键字
     * @return array
     */
    public function getTDK($spaceKeywords)
    {
        $web_site = 'POP服装趋势网';
        $tdk = [
            't' => "{$spaceKeywords}-服装设计资讯_流行趋势预测平台-{$web_site}",
            'd' => "{$web_site}是一家时尚、专业、高端服装设计资讯资源网站，从趋势报告、流行分析、图案、款式等方面，为设计师提供前沿的服装流行趋势企划报告和市场解析。",
            'k' => "{$spaceKeywords},服装设计,趋势报告,流行分析,服装款式,服装流行趋势,服装设计图案,女装设计,女装款式"
        ];
        return $tdk;
    }

    /*******************************工具方法******************************/

    // 报告栏目构建筛选项
    private function getLink($params = '', $type = '', $value = '', $filter = true)
    {
        $_params = !empty($params) ? $this->common_model->replaceParams($params) : '';
        // 直接追加编码的参数
        if (empty($type) || empty($value)) {
            return self::$base_url . trim($_params, '/') . $this->getSuffix($_params) . $this->common_model->getSearchSufix();
        }

        if ($type == 'key') {
            $search = $this->common_model->getSearchSufix($value);
            $type = $value = '';
        } else {
            $search = $this->common_model->getSearchSufix();
        }
        $params = $this->common_model->replaceParams($params, $type, $value, $filter);

        return self::$base_url . trim($params, '/') . $this->getSuffix($params) . $search;
    }

    // 获取后缀
    private function getSuffix($ret = true)
    {
        if ($ret) {
            return $this->suffix;
        } else {
            return '';
        }
    }

    // 水印与遮罩
    public function isShowWatermark($paramsArr)
    {
        // 是否显示遮罩
        $showMask = false;
        // 是否显示水印
        $showWatermark = false;
        // 是否有筛选条件
        $withConditions = false;
        // 有筛选条件 gen ind col sea match
        if (!empty($paramsArr)) {
            $withConditions = true;
        }
        // 页码
        $page = isset($paramsArr['page']) ? intval($paramsArr['page']) : 1;
        $powers = memberPower();
        switch ($powers['P_UserType']) {
            case 4: // 普通用户
                // 大于1页, 加水印
                if ($page > 1) {
                    $showWatermark = true;
                }
                // 大于5页, 加遮罩,申请vip权限
                if ($page > 5) {
                    $showMask = true;
                }
                break;
            case 5: // 游客
                // 有筛选条件, 大于1页 $showMask--显示遮罩  $showWatermark--显示水印
                if ($withConditions || $page > 1) {
                    $showMask = true;
                    $showWatermark = true;
                }
                break;
        }
        $result = array_filter(compact('showWatermark', 'showMask'));
        return $result;
    }

    // 判断VIP用户权限
    private function getUserVipPower()
    {
        $powers = memberPower();
        // 账号类型P_UserType => 1vip 2子账号vip 3试用 4普通 5游客
        $power = in_array($powers['P_UserType'], [1, 2, 3]) ? true : false;
        return $power;
    }

    /**
     * 报告栏目免费试读id -- 权限使用判断，针对部分vip权限
     * 规则：取 报告热门的数据（【性别】或者【行业】条件可能存在），在 -19个月与-18个月之间的前四篇
     * @return array
     */
    private function getFreeTrialReportIds()
    {
        $this->load->model('member_model');
        $params = '';
        $gender = $this->common_model->getGenderByRequest($params);
        $industry = $this->common_model->getIndustryByRequest($params);

        $this->load->driver('cache');
        $mem_key = 'INDEX_FREE_REPORT_' . $gender . '_' . $industry;
        $trial_ids = $this->cache->memcached->get($mem_key);
        if (empty($trial_ids)) {
            $condition = $try_list = [];
            $gender && $condition['other'][] = "aLabelIds:{$gender}";
            $industry && $condition['other'][] = "aLabelIds:{$industry}";
            $condition['iColumnId'] = [1, 2];
            $condition['iHot'] = 1;
            $condition["other"][] = 'dCreateTime:[' . date('Y-m-d\TH:i:s\Z', strtotime('-19 month')) . ' TO ' .
                date('Y-m-d\TH:i:s\Z', strtotime('-18 month')) . '}';
            $this->member_model->getRegisterFreeReport($params, $try_list, 0, 4, [], $condition);

            // 获取报告栏目免费试读id
            $trial_ids = [];
            if (!empty($try_list)) {
                foreach ($try_list as $k => $v) {
                    $trial_ids[] = $v['list']['id'];
                }
            }
            $this->cache->memcached->save($mem_key, $trial_ids, 3600);
        }
        return $trial_ids;
    }

    // 随机获取报告栏目非VIP封面图
    private function randReportNotVipCoverImage()
    {
        $k = array_rand(self::$vipReportCover, 1);
        $url = STATIC_URL2 . self::$vipReportCoverPath . self::$vipReportCover[$k];
        return $url;
    }

    // 获取数据库表的数据
    private function getProductData($ids, $tablename)
    {
        $data = $rows = [];
        if (!is_array($ids) || empty($tablename)) {
            return $data;
        }
        $ids = implode(',', $ids);
        switch ($tablename) {
            case 't_trend_report':
                $real_table = '`fashion`.`t_trend_report`';
                $sql = "SELECT * FROM  " . $real_table . " WHERE `iTopicId` IN ( {$ids} )";
                $data = $this->query($sql);
                $field = 'iTopicId';
                break;
        }
        if (!empty($data)) {
            foreach ($data as $val) {
                $rows[$val[$field]] = $val;
            }
        }
        return $rows;
    }

    // 判断报告栏目匹配 => 其他默认1-最新 / 2-匹配
    private function getReportMatch($columnId, $params)
    {
        if (empty($columnId)) return false;

        $allow_columns = [1, 2, 'all'];
        $paramsArr = $this->common_model->parseParams($params, 1);

        if (in_array($columnId, $allow_columns)) {
            $is_report_match = !empty($paramsArr['match']) ? $paramsArr['match'] : '';
        }
        return $is_report_match == 2 ? true : false;
    }

    // 把数组编码成url参数形式
    public function encodeParams($array)
    {
        $data = [];
        $data['col'] = isset($array['col']) ? $array['col'] : ''; // 栏目
        $data['gen'] = isset($array['gen']) ? $array['gen'] : ''; // 性别
        $data['ind'] = isset($array['ind']) ? $array['ind'] : ''; // 行业
        $data['sea'] = isset($array['sea']) ? $array['sea'] : ''; // 季节
        $data['match'] = isset($array['match']) ? $array['match'] : ''; // 最新/匹配
        $data = array_filter($data);

        if (is_array($data)) {
            $string = http_build_query($data);
            $string = str_replace(array('=', '&'), array('_', '-'), $string);
        } else {
            $string = "";
        }
        return $string;
    }

    // 筛选项相关的数据层级取值
    private function getReturnData($all, $groupValue, $field)
    {
        foreach ($all as $key => $val) {
            //两层时
            if (is_array($val) && !empty($val["attrs"])) {
                if (in_array($key, $groupValue)) {
                    $tmp_val = $val;
                    unset($tmp_val['attrs']);
                    foreach ($val['attrs'] as $k => $v) {
                        if (in_array($k, $groupValue)) {
                            $tmp_val['attrs'][$k] = $v;
                        }
                    }
                    $return[$key] = $tmp_val;
                } else {
                    $tmp_val = $val;
                    unset($tmp_val['attrs']);
                    foreach ($val['attrs'] as $k => $v) {
                        if (in_array($k, $groupValue)) {
                            $tmp_val['attrs'][$k] = $v;
                        }
                    }
                    if (!empty($tmp_val['attrs'])) {
                        $return[$key] = $tmp_val;
                    }
                }
                //一层时
            } else {
                if (in_array($key, $groupValue) && !empty($val)) {
                    if ($field == 'sStarLabel') {
                        $initial = array(
                            'A', 'B', 'C', 'D', 'E',
                            'F', 'G', 'H', 'I', 'J',
                            'K', 'L', 'M', 'N', 'O',
                            'P', 'Q', 'R', 'S', 'T',
                            'U', 'V', 'W', 'X', 'Y',
                            'Z', 'OTHER'
                        );
                        $alias = 'OTHER';
                        foreach ($initial as $init) {
                            $val = ltrim($val);
                            if (preg_match("/^[a-zA-Z]+$/", trim($val))) {
                                $valPinyin = $val;
                            } else {
                                $valPinyin = OpPopFree::getPinyin($val);
                            }
                            if (strtoupper(substr(trim($valPinyin), 0, 1)) == $init) {
                                $alias = $init;
                            }
                        }
                        $return[$alias][] = ['id' => $key, 'name' => $val, 'alias' => $alias, 'pinyin' => $valPinyin, 'columns' => 57];
                    } else {
                        $return[$key] = $val;
                    }
                }
            }
        }
        if ($field == 'sStarLabel') {
            $return = json_encode($return);
        }

        return $return;
    }

    // 过滤删选项，有些栏目的参数删除
    private function parameterFiltering($columnId, $paramsArr)
    {
        if (empty($paramsArr) || empty($columnId)) return '';

        switch ($columnId) {
            case 90: // 灵感源（取灵感报告）
            case 116: // 灵感视频
            case 117: // 展会面料
            case 124:// 图案工艺
                if ($paramsArr['match']) {
                    unset($paramsArr['match']);
                }
                if ($paramsArr['sea']) {
                    unset($paramsArr['sea']);
                }
                if ($paramsArr['gen']) {
                    unset($paramsArr['gen']);
                }
                if ($paramsArr['ind']) {
                    unset($paramsArr['ind']);
                }
                break;
            case 80: // 款式模板
            case 81: // 款式细节
                if ($paramsArr['match']) {
                    unset($paramsArr['match']);
                }
                if ($paramsArr['sea']) {
                    unset($paramsArr['sea']);
                }
                break;
            case 84: // 服饰品
                if ($paramsArr['match']) {
                    unset($paramsArr['match']);
                }
                if ($paramsArr['gen']) {
                    unset($paramsArr['gen']);
                }
                if ($paramsArr['ind']) {
                    unset($paramsArr['ind']);
                }
                break;
            case 3:// T台
            case 85: // 店铺陈列
                if ($paramsArr['match']) {
                    unset($paramsArr['match']);
                }
                if ($paramsArr['ind']) {
                    unset($paramsArr['ind']);
                }
                break;
            case 82:// 图案素材
            case 120:// 大牌花型
                if ($paramsArr['match']) {
                    unset($paramsArr['match']);
                }
                if ($paramsArr['sea']) {
                    unset($paramsArr['sea']);
                }
                if ($paramsArr['ind']) {
                    unset($paramsArr['ind']);
                }
                break;
            case 4:// 款式
            case 71: // 广告大片
            case 72: // 单品合辑
            case 131: // 订货会合辑
            case 115: // 快反应系列
                if ($paramsArr['match']) {
                    unset($paramsArr['match']);
                }
                break;
            case 'all':
            case 1: // 趋势解读
            case 2: // 流行分析
                break;
        }
        return $paramsArr ?: '';
    }
}
