<?php
/**
 * 趋势解读/图案趋势model
 * Created by PhpStorm.
 * User: gcl
 * Date: 2020/06/16 13:20
 */

class TrendsPattern_model extends POP_Model
{
    // 服装/趋势解读/图案趋势--条件简写与Solr字段对应关系 s 代表查询字段 g 代表 Group字段
    public $condReportSolrArr = [
        "gen" => ["name" => "性别", "s" => "sGender", "g" => "sGender"],// 性别
        "sea" => ["name" => "季节", "s" => "aLabelIds", "g" => "iSeason"],// 季节
    ];

    public $solrCondArr = [
        "sGender" => "gen",
        "iSeason" => "sea",
    ];

    private $refresh;
    private $memcachePref;

    public function __construct()
    {
        parent::__construct();
        $this->refresh = $this->input->get_post("refresh", true);
        $this->memcachePref = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_CategoryRealData_19112201_';
    }

    // 选中的筛选项
    public function getSelected($paramsArr, $isSimple = false)
    {
        $selected = [];
        $key = getKeyWord();
        if (!empty($key)) {
            $selected["key"] = ["name" => "关键字", "value" => htmlspecialchars($key), "cancelLink" => $this->getLink($paramsArr, "key")];
        }
        $this->load->model("Attr_model");
        foreach ($paramsArr as $k => $val) {
            // 性别和季节是具体的标签 其他标签为关键字
            switch ($k) {
                case 'sea':
                case 'gen':
                    $value = $this->Attr_model->Attribute[$val];
                    break;
            }

            if (!empty($value) && $k != 'page') {
                if ($isSimple) {
                    $selected[$k] = $value;
                } else {
                    switch ($k) {
                        case 'sea':
                        case 'gen':
                            $selected[$k] = ["name" => $this->condReportSolrArr[$k]["name"], "value" => $value, "cancelLink" => $this->getLink($paramsArr, $k)];
                            break;
                    }
                }
            }
        }
        // var_dump($selected);exit;
        return $selected;
    }

    /**
     *  获取存在的筛选项
     * @param $fields
     * @param $paramsArr
     * @return mixed
     */
    public function getSelectItems($fields, $paramsArr)
    {
        $items = $this->getFacetCategory($fields, $paramsArr);
        foreach ($items as $key => $val) {
            if (empty($val)) {
                $selectItems[$key] = array();
                continue;
            }
            switch ($key) {
                // 季节
                case 'iSeason':
                    $selectItems[$key] = $this->getItems($paramsArr, $val, 'sea');
                    break;
                // 性别
                case 'sGender':
                    $val = array_intersect(array(1 => '男装', 2 => '女装', 5 => '童装'), $val);
                    $selectItems[$key] = $this->getItems($paramsArr, $val, 'gen');
                    break;
            }
        }
        return $selectItems;
    }

    // 获取完整的url数据
    private function getItems($paramsArr, $arr, $type)
    {
        if (empty($arr)) return [];
        $ret = array();
        foreach ($arr as $id => $name) {
            $ret[] = array('name' => $name, 'link' => $this->getLink($paramsArr, $type, $id), 'id' => $id);
        }
        return $ret;
    }

    // 获取存在的筛选项
    public function getFacetCategory($fields, $paramsArr)
    {
        $condition = $this->getCondition($paramsArr);
        $keyWord = getKeyWord();
        $_params = [
            'facet' => 'true',
            'facet.field' => $fields,
            'facet.limit' => '-1',
            'raw' => true
        ];
        $arSort = array('dCreateTime' => 'DESC');
        $temp = array();
        $res = POPSearch::wrapQueryPopFashionMerger($keyWord, $condition, $temp, 0, 0, $arSort, $_params);

        $returns = [];
        if (is_array($fields)) {
            foreach ($fields as $key => $val) {
                if (!array_key_exists($val, $temp)) {
                    $returns[$val] = array();
                }
            }
        }
        $facet = $res['facet_counts']['facet_fields'];
        if (empty($facet)) {
            return [];
        }
        foreach ($facet as $field => $value) {
            $groupValue = $return = array();
            foreach ($facet[$field] as $key => $val) {
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
                $all = $this->getAll($field);
                $return = $this->getReturnData($all, $groupValue);
                unset($all);
            }
            $returns[$field] = $return;
        }
        return $returns;
    }

    /**
     * @ 获取列表数据
     *
     * @param array $paramsArr 搜索项
     * @param int $offset 开始位置
     * @param int $limit 结束位置
     * @return array
     */
    public function getList($paramsArr = [], $offset = 0, $limit = 5)
    {
        $keyword = getKeyWord();

        $conditions = $this->getCondition($paramsArr);
        $arSort = $this->getSort($paramsArr);

        $result = $lists = [];
        $totalCount = POPSearch::wrapQueryPopFashionMerger($keyword, $conditions, $result, $offset, $limit, $arSort);
        if (empty($result)) {
            return [$lists, intval($totalCount)];
        }

        $lists = $this->dealData($result, $paramsArr);
        return [$lists, intval($totalCount)];
    }

    // 处理数据
    protected function dealData($data = [], $params = [])
    {
        if (empty($data)) return [];

        $lists = [];
        $this->load->model(["Graphicitem_model", "Collect_model"]);
        foreach ($data as $key => $val) {
            // $colPid = reset($val['iColumnId']);
            $colId = $val['iColumnId'] ? end($val['iColumnId']) : 126;
            $info = [];
            $tableName = $val["tablename"];
            $id = $info['id'] = $val["pri_id"];
            $info['t'] = getProductTableName($val["tablename"]);
            $info['col'] = $colId;

            $_result = OpPopFashionMerger::getProductData($id, $tableName);
            $res = $_result[$id];

            $info['title'] = htmlspecialchars(stripcslashes($res['sTitle']));//标题
            $info['publish_time'] = !empty($res['dPubTime']) ? date('Y-m-d', strtotime($res['dPubTime'])) : '';// 发布时间
            $info['memo'] = htmlspecialchars(trim(strip_tags($res['sDesc']))); // 描述
            $info['view'] = $this->Graphicitem_model->getViews($tableName, $id, $res);
            $imgPath = getImagePath($val["tablename"], $res);
            $info["cover"] = $imgPath['smallPath'];// 封面图/小图
            $info["labels"] = $this->getLabelsInfo($colId, $tableName, $id, $params, 'list');// 获取标签
            $info['detail_url'] = '/trendspattern/detail/t_' . $info['t'] . '-id_' . $id . '-col_' . $colId . '/';// 详情链接
            if (!empty($info)) {
                // 收藏
                if ($tableName == 't_trend_report') {
                    $info['collect_status'] = $this->Collect_model->getCollectStatus($info['t'], $id, 'fashion', true);
                } else {
                    $info['collect_status'] = 0;
                }
                $lists[$key] = $info;
            }
        }
        return $lists;
    }

    // 获取趋势解读/图案趋势标签
    public function getLabelsInfo($colId, $tableName, $id, $params = [], $type = 'detail')
    {
        switch ($colId) {
            // 126--趋势解读/图案趋势
            case 126:
                // 126--趋势解读详情: 季节sea, 品牌bra, 单品cat, 品名scat,  行业ind, 视角vis, 栏目col
                $dKeys = ['sea', 'bra', 'cat', 'scat', 'label', 'ind', 'vis', 'col'];
                // 126--趋势解读列表: 季节sea, 趋势专题relt,单品cat, 品名scat
                $lKeys = ['sea', 'relt', 'cat', 'scat'];
                break;
            default:
                $dKeys = $lKeys = [];
                break;
        }
        $aLabel = $this->getLabelInfoOrigin($colId, $tableName, $id, $params);
        if (!$aLabel) {
            return false;
        }
        switch ($type) {
            case 'list':
                $keys = $lKeys;
                break;
            case 'detail':
                $keys = $dKeys;
                break;
        }
        $aResult = [];
        if (!empty($keys)) {
            foreach ($keys as $key) {
                if (key_exists($key, $aLabel)) {
                    $aResult = array_merge($aResult, $aLabel[$key]);
                }
            }
        }
        return $aResult;
    }

    // 获取标签来源
    public function getLabelInfoOrigin($colId, $tableName, $id, $params = [])
    {
        if ($tableName != 't_trend_report') {
            return [];
        }
        $productData = OpPopFashionMerger::getProductData($id, $tableName);
        $res = $productData[$id];
        $_colId = !empty($res['iOriginColumn']) ? trim($res['iOriginColumn']) : $colId;

        // 栏目
        $aLabel['col'][] = [
            'id' => $_colId,
            'name' => GetCategory::getOtherFromColId($_colId, 'sName'),
            'dLink' => $this->getLink($params),
            'lLink' => '', // 不取列表数据
        ];

        // 构建标签数组
        $aLabel = [];

        // 季节
        $season_id = $res['iSeason'];
        if (!empty($season_id)) {
            $seasonArr = GetCategory::getOtherFromIds($season_id, ['sName'], 'array');
            $sName = $seasonArr['sName'][0];
            $sDLink = $this->getLink([], 'key', $sName);
            $slLink = $this->getLink($params, 'sea', $season_id);
            $aLabel['sea'][] = [
                'id' => $season_id,
                'name' => !empty($sName) ? $sName : '',
                'dLink' => $sDLink,
                'lLink' => $slLink
            ];
        }

        // 下面关键字不带page
        if (isset($params['page'])) {
            unset($params['page']);
        }

        // 品牌
        $brand_id = $res['iBrandTid'];
        if (!empty($brand_id)) {
            $sName = GetCategory::getBrandOtherFormId($brand_id, 'en_cn_brand');
            $sName = htmlspecialchars($sName);
            $sDLink = $this->getLink([], 'key', $sName);
            $slLink = $this->getLink($params, 'key', $sName); // 'bra'  $brand_id
            $aLabel['bra'][] = [
                'id' => $brand_id,
                'name' => $sName,
                'dLink' => $sDLink,
                'lLink' => $slLink
            ];
        }

        // 单品
        $cat_id = $res['sCategory'];
        if (!empty($cat_id)) {
            $retArr = GetCategory::getOtherFromIds($cat_id, ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr)) {
                $aLabel['cat'] = [];
                foreach ($retArr['iAttributeId'] as $key => $labelId) {
                    $sName = $retArr['sName'][$key];
                    $sName = htmlspecialchars($sName);
                    $sDLink = $this->getLink([], 'key', $sName);
                    $slLink = $this->getLink($params, 'key', $sName);// 'cat' $labelId
                    $aLabel['cat'][] = [
                        'id' => $labelId,
                        'name' => $sName,
                        'dLink' => $sDLink,
                        'lLink' => $slLink
                    ];
                }
            }
        }

        // 品名
        $scat_id = $res['sSubCategory'];
        if (!empty($scat_id)) {
            $retArr = GetCategory::getOtherFromIds($scat_id, ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                foreach ($retArr['iAttributeId'] as $key => $labelId) {
                    $sName = $retArr['sName'][$key];
                    $sName = htmlspecialchars($sName);
                    $sDLink = $this->getLink([], 'key', $sName);
                    $slLink = $this->getLink($params, 'key', $sName);// scat $labelId
                    $aLabel['scat'][] = [
                        'id' => $labelId,
                        'name' => $sName,
                        'dLink' => $sDLink,
                        'lLink' => $slLink
                    ];
                }
            }
        }

        // 行业
        $ind_id = $res['sIndustry'];
        if (!empty($ind_id)) {
            $retArr = GetCategory::getOtherFromIds($ind_id, ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr)) {
                $aLabel['ind'] = [];
                foreach ($retArr['iAttributeId'] as $key => $labelId) {
                    $sName = $retArr['sName'][$key];
                    $sName = htmlspecialchars($sName);
                    $sDLink = $this->getLink([], 'key', $sName);
                    $slLink = $this->getLink($params, 'key', $sName);// 'ind' $labelId
                    $aLabel['ind'][$key] = [
                        'id' => $labelId,
                        'name' => $sName,
                        'dLink' => $sDLink,
                        'lLink' => $slLink
                    ];
                }
            }
        }

        // 视角
        $vis_id = $res['sVisualAngle'];
        if (!empty($vis_id)) {
            $retArr = GetCategory::getOtherFromIds($vis_id, ['iAttributeId', 'sName'], 'array');
            if (is_array($retArr) && $retArr) {
                $aLabel['vis'] = [];
                foreach ($retArr['iAttributeId'] as $key => $labelId) {
                    $sName = $retArr['sName'][$key];
                    $sName = htmlspecialchars($sName);
                    $sDLink = $this->getLink([], 'key', $sName);
                    $slLink = $this->getLink($params, 'key', $sName);// 'vis' $labelId
                    $aLabel['vis'][$key] = [
                        'id' => $labelId,
                        'name' => $sName,
                        'dLink' => $sDLink,
                        'lLink' => $slLink
                    ];
                }
            }
        }

        // 趋势专题 relt
        $iReltId = $res['iRelatedTopicsId'];
        if (!empty($iReltId)) {
            $_tmpArr = $this->getAll('iRelationTheme');
            $aRelThemes = [];
            foreach ($_tmpArr as $key => $value) {
                if ($value && isset($value['attrs'])) {
                    foreach ($value['attrs'] as $k => $v) {
                        $aRelThemes[$k] = $v['sName'];
                    }
                }
            }
            if (key_exists($iReltId, $aRelThemes)) {
                $aLabel['relt'] = [];
                $sName = $aRelThemes[$iReltId];
                $sName = htmlspecialchars($sName);
                $sDLink = $this->getLink([], 'key', $sName);
                $slLink = $this->getLink($params, 'key', $sName);// 'relt' $iReltId
                $aLabel['relt'][$key] = [
                    'id' => $iReltId,
                    'name' => $sName,
                    'dLink' => $sDLink,
                    'lLink' => $slLink
                ];
            }
        }
        // 关联标签 label
        if ($res['sRelationLabels']) {
            $sRelationLabels = $this->getLabelsByIds($res['sRelationLabels']);
            $aLabel['label'] = [];
            foreach ($sRelationLabels as $key => $item) {
                $labelId = $item['iLabelsId'];
                $sName = $item['sLabelName'];
                $sName = htmlspecialchars($sName);
                $sDLink = $this->getLink([], 'key', $sName);
                $slLink = ''; // 不取列表标签
                $aLabel['label'][] = [
                    'id' => $labelId,
                    'name' => $sName,
                    'dLink' => $sDLink,
                    'lLink' => $slLink
                ];
            }
        }

        return $aLabel;
    }

    // 拼接链接
    public function getLink($paramsArr = [], $type = '', $value = '')
    {
        $listRootLink = "/trendspattern/";
        $get = getKeyWord() ? ["key" => getKeyWord()] : [];
        if ($type == "key") {
            //get中的参数 key
            if (empty($value) && isset($get["key"])) {
                unset($get["key"]);
            } else {
                $get["key"] = rawurlencode($value);
            }
        } else {
            //url中的参数
            if (isset($paramsArr['page'])) {
                unset($paramsArr['page']);
            }
            if (!empty($type) && empty($value) && isset($paramsArr[$type])) {
                unset($paramsArr[$type]);
            } elseif (!empty($type) && !empty($value)) {
                // 服装栏目 126 除 季节 与 性别  其他为 key
                if (in_array($type, ['sea', 'gen'])) {
                    $paramsArr[$type] = $value;
                }
            }
        }
        $params = empty($paramsArr) ? "" : (encodeParams($paramsArr) . "/");
        $get = empty($get) ? "" : ("?" . http_build_query($get));
        return $listRootLink . $params . $get;
    }

    // 拼接 服装/趋势解读/图案趋势 solr条件  不含关键字
    public function getCondition($paramsArr = [])
    {
        $conditions = [];
        $conditions['iColumnId'] = 126;
        // $conditions['iHot'] = 1; // 热门标签

        $endTime = date("Y-m-d") . 'T' . date("H:i:s", strtotime("-2 hour")) . 'Z';
        $conditions['dCreateTime'] = "[2016-05-01T00:00:00Z" . ' TO ' . $endTime . ']'; // 2019

        if (!empty($paramsArr) && is_array($paramsArr)) {
            foreach ($paramsArr as $key => $value) {
                if (isset($this->condReportSolrArr[$key])) {
                    $solrField = $this->condReportSolrArr[$key]["s"];
                    if ($solrField == "aLabelIds") {
                        $v = is_array($value) ? implode(" OR ", $value) : $value;
                        $conditions["other"][] = "{$solrField}:($v)";
                    } elseif ($solrField == "sGender") {
                        // TODO 男童与女童算童装
                        if (is_array($value)) {
                            $v1 = is_array($value) ? implode(" OR ", $value) : $value;
                            if (in_array(5, $value)) {
                                $conditions['other'][] = "(aLabelIds:{$v1} OR 3 OR 4)";
                            } else {
                                $conditions["other"][] = "{$solrField}:($v1)";
                            }
                        } else {
                            if ($value == 5) {
                                $conditions['other'][] = "(aLabelIds:5 OR 3 OR 4)";
                            } else {
                                $conditions['other'][] = "(aLabelIds:{$value})";
                            }
                        }
                    } else {
                        $conditions[$solrField] = $value;
                    }
                }
            }
            // 关键字
            $keyword = getKeyWord();
            if ($keyword) {
                $conditions['other'][] = 'combine:(' . $keyword . ')';
            }
        }
        return $conditions;
    }

    /**
     * @ 获取详情
     */
    public function getReportDetailsData($id, $col = '')
    {
        $this->load->driver('cache');
        $table = 't_trend_report';

        // 只有图案趋势才有权限查看
        $colInfo = $this->getColFromSolr($table, $id);
        $colInfo_col = $colInfo[1];
        if ($colInfo_col != 126) {
            return false;
        }

        // 先判断数据存不存在,memcached缓存的键值与服装的不同
        $sql = "SELECT iTopicId  FROM  fashion.t_trend_report WHERE iTopicId=?  AND iStatus!=1 limit 1";
        $query = $this->query($sql, [$id]);
        if (empty($query)) {
            return false;
        }

        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_20200629_' . $table . '_' . $id;
        $res = $this->cache->memcached->get($mem_key);
        if ($this->refresh || $res === false) {
            $sql = "SELECT * FROM  fashion.t_trend_report WHERE iTopicId=? AND iStatus!=1 limit 1";
            $query = $this->query($sql, [$id]);
            $num = count($query);
            if ($num === 0) {
                return false;
            } else {
                $row = $query[0];
                $res = [];
                $res['id'] = $id;
                $res['iViewCount'] = $row['iViewCount'];

                // 品牌
                $res['iBrandTid'] = $row['iBrandTid'];
                $res['brandName'] = GetCategory::getBrandOtherFormId($row['iBrandTid'], 'en_cn_brand');

                $res['sLiveUrl'] = $row['sLiveUrl'];// 视频直播链接
                $res['dLiveStartTime'] = $row['dLiveStartTime'] ? $row['dLiveStartTime'] : '';// 视频直播开始时间
                $res['dLiveEndTime'] = $row['dLiveEndTime'] ? $row['dLiveEndTime'] : '';// 视频直播结束时间

                $res['table'] = $table;
                $res['authorPenName'] = $this->getPenNameById($row['iPublisher']);
                // $res['iBook'] = $row['iBook'];// 报告书籍ID [报告成册，这期不做]
                if ($col) {
                    $res['col'] = $col;
                } else {
                    $res['col'] = $colInfo[1];
                    $res['colPid'] = $colInfo[0];
                    $res['colPsName'] = GetCategory::getOtherFromColId($res['colPid'], 'sName');
                }
                // 栏目名
                $res['colsName'] = GetCategory::getOtherFromColId($res['col'], 'sName');

                $res['gender'] = $row['iGender'];// 性别
                $res['industry'] = $row['sIndustry'];// 行业

                $res['title'] = htmlspecialchars(stripslashes($row['sTitle']));
                $res['description'] = strip_tags($row['sDesc']);

                $res['updateDate'] = date('Y年m月d日', strtotime($row['dPubTime']));
                $res['iCreateTime'] = strtotime($row['dPubTime']); // 发布时间
                $res['bigImgPath'] = $row['sBigImgPath'];

                //列表页封面图，显示在中间页
                $_path = getImagePath($table, $row);
                $res['cover'] = getFixedImgPath($_path['cover']);

                $subReoprtPages = json_decode($row['sUniVal'], true);
                // 子主题页排序BEGIN ---------------------------------------
                if (is_array($subReoprtPages)) {
                    foreach ($subReoprtPages as $key => $sub) {
                        $volume = [];
                        $pageSorts = [];
                        foreach ($sub as $pageSort => $page) {
                            $volume[] = intval($page['pageSort']);
                            $pageSorts[] = intval($pageSort);
                        }
                        array_multisort($volume, SORT_DESC, $pageSorts, SORT_ASC, $subReoprtPages[$key]);
                    }
                }
                // 子主题页排序END ------------------------------------------

                $sql = "SELECT * FROM fashion.t_trend_subreport WHERE iTopicId=? AND iStatus!=1";
                $result = $this->query($sql, [$id]);

                $res['subTopic'] = [];
                foreach ($result as $key => $val) {
                    array_push($res['subTopic'], $val);
                    $res['subTopic'][$key]['subPage'] = isset($subReoprtPages[$key]) ? array_values($subReoprtPages[$key]) : [];
                }

                $sql = "SELECT sZipname,iExpansion,iImgType FROM fashion.t_trend_subreport_page WHERE iTopicId=? AND iPageType=1 ORDER BY iPageId ASC";
                $sZipnameRes = $this->query($sql, [$id]);

                $i = 0;
                $this->load->model('Download_model');
                foreach ($res['subTopic'] as $key => $subTopic) {
                    foreach ($subTopic['subPage'] as $k => $page) {
                        if ($page['pageType'] == 1) {
                            $res['subTopic'][$key]['subPage'][$k]['zipname'] = $sZipnameRes[$i]['sZipname'];
                            $res['subTopic'][$key]['subPage'][$k]['expansion'] = $sZipnameRes[$i]['iExpansion'];
                            $res['subTopic'][$key]['subPage'][$k]['imgtype'] = $sZipnameRes[$i]['iImgType'];
                            $i++;
                        } else {
                            $res['subTopic'][$key]['subPage'][$k]['zipname'] = '';
                            $res['subTopic'][$key]['subPage'][$k]['expansion'] = 1;
                            $res['subTopic'][$key]['subPage'][$k]['imgtype'] = 1;
                        }
                        $res['subTopic'][$key]['subPage'][$k]['content'] = $page['content'] ? preg_replace("#(=\")([\s]*/fashion/fashion_shows/image/(.*?)\.(jpg|JPG))#", "$1" . STATIC_URL1 . "$2", stripslashes($page['content'])) : '';
                        // 款式图
                        if ($page['pageType'] == 1) {
                            if (isset($page['stylePic']) && is_array($page['stylePic'])) {
                                // 款式图排序BEGIN ----------------------------------------
                                $picSort = [];
                                foreach ($page['stylePic'] as $k2 => $pic) {
                                    if ($pic['indexShow'] == 0) {
                                        // 没有添加到报告中的图片不显示
                                        unset($res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]);
                                        unset($page['stylePic'][$k2]);
                                        continue;
                                    }
                                    $picSort[] = $pic['imgSort'];
                                }
                                array_multisort($picSort, SORT_DESC, $res['subTopic'][$key]['subPage'][$k]['stylePic']);
                                array_multisort($picSort, SORT_DESC, $page['stylePic']);
                                // 款式图排序END ---------------------------------------------

                                foreach ($page['stylePic'] as $k2 => $pic) {
                                    if ($pic['indexShow'] == 0) {
                                        // 没有添加到报告中的图片不显示
                                        continue;
                                    }
                                    $old_id = $pic['id'];

                                    $eachImg = OpPopFashionMerger::getProductData($old_id, $pic['table'], false);
                                    $eachImg = $eachImg[$old_id];
                                    // 图片所在的栏目id
                                    $imgCol = $this->getColFromSolr($pic['table'], $eachImg['id']);
                                    $imgCol = $imgCol[1];
                                    $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['col'] = $imgCol;

                                    if ($pic['table'] == 'product_graphicitem') {
                                        $arr = OpPopFashionMerger:: getPatternAttribute($eachImg['memo']);
                                        $sPatternUse = $this->getAll('sPatternUse');// 图案应用
                                        $PatternID = $eachImg['sPatternContent'];
                                        $sPatternContent = trim(GetCategory::getOtherFromIds($PatternID, ['sName']));
                                        $sPatternUseName = $sPatternUse[$arr['sPatternUse']];

                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['genderName'] = $sPatternContent;
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['sCategoryName'] = $sPatternUseName;
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['sSubcategoryName'] = '';
                                    } else {
                                        // 单品id
                                        $itemId = $eachImg['iCategory'];
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['iCategory'] = 0;
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['sCategoryName'] = '';
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['categoryLink'] = '';
                                        $categoryArr = GetCategory::getOtherFromIds($itemId, ['iAttributeId', 'sName'], 'array');
                                        if ($categoryArr) {
                                            $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['iCategory'] = $categoryArr['iAttributeId'][0];
                                            $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['sCategoryName'] = $categoryArr['sName'][0];
                                            $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['categoryLink'] = '';
                                        }
                                        // 品名id
                                        $prodNameId = $eachImg['iSubcategory'];
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['iSubcategory'] = 0;
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['sSubcategoryName'] = '';
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['subCategoryLink'] = '';
                                        $subCategoryArr = GetCategory::getOtherFromIds($prodNameId, ['iAttributeId', 'sName'], 'array');
                                        if ($subCategoryArr) {
                                            $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['iSubcategory'] = $subCategoryArr['iAttributeId'][0];
                                            $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['sSubcategoryName'] = $subCategoryArr['sName'][0];
                                            $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['subCategoryLink'] = '';
                                        }
                                        $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['genderName'] = GetCategory::getNewEnName(1, $eachImg['typ'], 'sName');
                                    }
                                    // 暂时不获取款式图案库的详情链接
                                    // $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['link'] = $this->getDetailLink('style', $pic['table'], $old_id, $imgCol);
                                    $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['colsName'] = GetCategory::getOtherFromColId($imgCol, 'sName');
                                    $brandInfo = OpPopFashionMerger::getBrandData($eachImg['brand_tid']);
                                    $res['subTopic'][$key]['subPage'][$k]['stylePic'][$k2]['brandName'] = $brandInfo['name'];
                                }
                            }
                        }
                    }
                }
                // flash报告暂时只有flash子报告页(iPageType=4)
                $res['iIsFlash'] = $row['iIsFlash'];
                if ($row['iIsFlash']) {
                    $subPages = $this->getSubReportPages($row['iTopicId']);
                    foreach ($res['subTopic'] as $key => $subTopic) {
                        if (key_exists($subTopic['iSubTopicId'], $subPages)) {
                            foreach ($subPages[$subTopic['iSubTopicId']] as $subPage) {
                                $_sContent = json_decode($subPage['sContent'], true);
                                // 格子数据中的详情链接
                                foreach ($_sContent['grids'] as &$grid) {
                                    // 款式库的图
                                    $_popId = $grid['photo']['id'];
                                    $grid['photo']['link'] = ''; // 暂时不获取格子中图片详情链接
                                    if ($grid['photo']['isLocal'] === false && $_popId) {
                                        $_aPopId = explode('-', $_popId);
                                        // t_product_style_graphic-id_17825753-col_123
                                        $_table = substr($_aPopId[0], 2);
                                        $_tmp_id = substr($_aPopId[1], 3);// 款式图案库数据对应的id

                                        // 表名转换
                                        $grid['photo']['id'] = '';
                                        $_table = getProductTableName($_table);
                                        if (!empty($_table)) {
                                            $_aPopId[0] = 't_' . $_table;
                                            $_popId = implode('-', $_aPopId);
                                            $grid['photo']['id'] = $_popId;
                                        }
                                        // 格子中图片详情链接[ 暂时不做 ], 如果没有手动输入链接则用图片对应报告的详情链接
                                        /*if (trim($grid['photo']['link']) == '') {
                                            $grid['photo']['link'] = $downloadType; // '/details/style/' . $_popId . '/'
                                        }*/

                                        // todo 格子中图片下载链接
                                        // $grid['photo']['download'] = '/download/dlsingle/?dl_link=' . urlencode(STATIC_URL1 . $grid['photo']['big'] . '&t=' . time());
                                        $grid['photo']['download'] = '/patternlibrary/detail/?id=' . $_tmp_id . '&t=' . $_table;
                                    } else {
                                        // 格子中图片下载链接  STATIC_URL1  https://imgf1.pop-fashion.com
                                        $grid['photo']['download'] = '/download/downimg/?dl_link=' . urlencode(STATIC_URL1 . $grid['photo']['big']);
                                    }
                                    // 公共重命名规则
                                    $brand_str = !empty($grid['photo']['brand']) ? $grid['photo']['brand'] : (!empty($res['brandName']) ? $res['brandName'] : '');
                                    if (!empty($grid['photo']['big']) && !empty($brand_str)) {
                                        $grid['photo']['rename'] = 'pop_' . str_replace([' ', '<', '>', '/', '\\', '|', ':', ' * ', '#', '\'', '"'], ['_'], trim($brand_str)) . '_' . $subTopic['iSubTopicId'] . '.' . pathinfo(basename($grid['photo']['big']), PATHINFO_EXTENSION);
                                    }
                                }
                                unset($_sContent['gridSources']);
                                $subPage['sContent'] = $_sContent;
                                $res['subTopic'][$key]['subPage'][] = $subPage;
                            }
                        }
                    }
                }
                $res['iRecChannel'] = OpPopFashionMerger::getRecommend($table, $row)['iRecChannel'];
                $this->cache->memcached->save($mem_key, $res, 1800);
            }
        }
        $this->load->model("Graphicitem_model");
        $res['viewNum'] = $this->Graphicitem_model->getViews($table, $id, $res);

        // 报告直播
        $live_time = date("Y-m-d H:i:s");
        if (!empty($res['sLiveUrl'])) {// 视频直播状态
            if (strtotime($live_time) <= strtotime($res['dLiveStartTime'])) {
                $res['live_status'] = 1; // 立即预约
            } elseif (strtotime($live_time) >= strtotime($res['dLiveEndTime'])) {
                $res['live_status'] = 2;// 已结束(回看解读)
            } elseif ((strtotime($live_time) <= strtotime($res['dLiveEndTime'])) && (strtotime($live_time) >= strtotime($res['dLiveStartTime']))) {
                $res['live_status'] = 3;// 进行中(立即收看)
            }
        }

        // 取pdf和图片打包路径
        $sql = "SELECT iPdfStatus,sPdfFilePath,iImgStatus,sImgPackPath FROM `fashion`.`t_trend_report` WHERE iTopicId=? AND iStatus!=1";
        $query = $this->query($sql, [$id]);
        $row = $query[0];
        if ($row) {
            $res['iPdfStatus'] = $row['iPdfStatus'];
            $res['sPdfFilePath'] = $row['iPdfStatus'] == 2 ? $row['sPdfFilePath'] : '';
            $res['iImgStatus'] = $row['iImgStatus'];
            $res['sImgPackPath'] = $row['iImgStatus'] == 2 ? $row['sImgPackPath'] : '';
            $res['pdfPath'] = $res['sPdfFilePath'] ? $res['sPdfFilePath'] : 0;
        } else {
            $res['iPdfStatus'] = 0;
            $res['sPdfFilePath'] = '';
            $res['iImgStatus'] = 0;
            $res['sImgPackPath'] = '';
            $res['pdfPath'] = 0;
        }
        return $res;
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
     * 通过labelid 获取labelname(memcache)
     * @param string $sRelationLabels
     * @return mixed
     */
    public function getLabelsByIds($sRelationLabels = '')
    {
        $this->load->driver('cache');
        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_' . 't_label_db_' . md5($sRelationLabels);
        $result = $this->cache->memcached->get($mem_key);
        if ($this->refresh || $result === false) {
            if ($sRelationLabels) {
                $labelSting = '(' . $sRelationLabels . ')';
                $andSql = " AND iLabelsId IN $labelSting ";
            } else {
                $andSql = '';
            }
            $sql = "SELECT iLabelsId, sLabelName FROM fashion.t_label_db WHERE iStatus=0 {$andSql} ORDER BY iLabelsId DESC";
            $result = $this->query($sql);
            $this->cache->memcached->save($mem_key, $result, 3600);
        }
        return $result;
    }

    //获取solr查询的排序条件
    public function getSort($params = array())
    {
        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        // 排序
        $params['sor'] = isset($params['sor']) && !empty($params['sor']) ? $params['sor'] : intval($this->input->get_post("sor"));
        if (!empty($params)) {
            if (isset($params['sor'])) {
                //1=>更新时间,2=>浏览量,3=>收藏量
                switch ($params['sor']) {
                    case '2':
                        $arSort = ['iViewCount' => 'DESC', 'pri_id' => 'DESC'];
                        break;
                    case '3':
                        $arSort = ['iCollectCount' => 'DESC', 'pri_id' => 'DESC'];
                        break;
                }
            }
        }
        return $arSort;
    }

    public function getAll($field, $Refresh = false)
    {
        $ret = [];
        $toCnName = 'sName';
        switch ($field) {
            case 'sGender':    // 性别
                $ret = GetCategory::getGender('', $toCnName);
                break;
            case 'iSeason'://季节
                $ret = GetCategory::getSeason('', $toCnName);
                break;
            //趋势专题（关联主题）
            case 'iRelationTheme':
                $this->load->driver('cache');
                $memcacheKey = $this->memcachePref . 'iRelationTheme';
                $ret = $this->cache->memcached->get($memcacheKey);
                if ($ret && !$Refresh && !$this->refresh && !$this->input->post('refresh')) {
                    return $ret;
                } else {
                    $ret = [];
                    $sql = 'SELECT iRelatedTopicsId as id, iRelatedTopicsPid as pid,sRelatedTopicsName as sName FROM `fashion`.`t_related_topics_db` WHERE `iDisplay`=1 ORDER BY iSort DESC';
                    $arr = $this->query($sql);
                    if (!empty($arr)) {
                        foreach ($arr as $val) {
                            if ($val['pid'] == '0') {
                                $ret["{$val['id']}"]["sName"] = $val['sName'];
                                $ret["{$val['pid']}"]["attrs"] = [];
                            }
                        }
                        foreach ($arr as $val) {
                            if ($val['pid'] != '0') {
                                $ret["{$val['pid']}"]["attrs"]["{$val['id']}"] = ['sName' => $val['sName']];
                            }
                        }
                        $this->cache->memcached->save($memcacheKey, $ret, 3600);
                        return $ret;
                    }
                }
                break;
            //图案应用
            case 'sPatternUse':
                $memcacheKey = $this->memcachePref . 'sPatternUse';
                $ret = $this->cache->memcached->get($memcacheKey);
                if ($ret && !$this->input->post('refresh')) {
                    return $ret;
                } else {
                    $ret = array();
                    $sql = 'SELECT cat_id as id,cat_name FROM `fashion`.`category` WHERE parent_id=14101';
                    $arr = $this->query($sql);
                    foreach ($arr as $val) {
                        $ret["{$val['id']}"] = $val['cat_name'];
                    }
                    $this->cache->memcached->save($memcacheKey, $ret, $this->MemTime);
                    return $ret;
                }
                break;
        }
        return $ret;
    }

    // 获取返回值，构建solr
    private function getReturnData($all, $groupValue)
    {
        // 只有性别与季节
        $return = [];
        if (!empty($all) && !empty($groupValue)) {
            foreach ($all as $key => $val) {
                if (in_array($key, $groupValue) && !empty($val)) {
                    $return[$key] = $val;
                }
            }
        }
        return $return;
    }

    /**
     * 从solr中获取栏目id
     * @param string $table
     * @param int $id
     * @return mixed
     */
    public function getColFromSolr($table = '', $id = 0)
    {
        $cond = ['pop_id:' . $table . '_' . $id];
        $con1 = ['other' => implode(' AND ', $cond)];
        $arSort = ['pri_id' => 'desc'];
        $result = [];
        POPSearch::wrapQueryPopFashionMerger('', $con1, $result, 0, 1, $arSort, ['fl' => 'pri_id,tablename,iColumnId']);

        return !empty($result[0]['iColumnId']) ? $result[0]['iColumnId'] : '';
    }

    /**
     * 获取子报告页
     * @param $iTopicId
     * @param int $iSubTopicId 子报告id, 可选, 默认0取主报告下所有子报告页
     * @param int $iPageType 子报告页类型, 可选, 默认0取全部类型
     * @return array
     */
    public function getSubReportPages($iTopicId, $iSubTopicId = 0, $iPageType = 0)
    {
        if (!$iTopicId) {
            return array();
        }
        $where = array();
        $where[] = ' iTopicId=' . $iTopicId . ' ';
        $where[] = ' iStatus<>1';
        if ($iSubTopicId) {
            $where[] = ' iSubTopicId=' . $iSubTopicId . ' ';
        }
        if ($iPageType) {
            $where[] = ' iPageType=' . $iPageType . ' ';
        }
        $_where = empty($where) ? '' : implode(' AND ', $where);
        $fields = 'iPageId,iTopicId,iSubTopicId,sTitle,iPageType,iPageType as pageType,iSort,sContent,iPriority';
        $sql = "SELECT {$fields} FROM `fashion`.`t_trend_subreport_page` WHERE {$_where}  ORDER BY iSort DESC LIMIT 100";
        $query = $this->query($sql);
        $num = intval(count($query));
        if ($num === 0) {
            return array();
        }
        $result = array();
        $rows = $query;
        foreach ($rows as $row) {
            if (!isset($result[$row['iSubTopicId']])) {
                $result[$row['iSubTopicId']] = array();
            }
            $result[$row['iSubTopicId']][] = $row;
        }
        return $result;
    }

    /**
     * 通过 id 获取 笔名(memcache)
     * @param int $id
     * @return string
     */
    public function getPenNameById($id = 0)
    {
        $this->load->driver('cache');
        $mem_key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_' . 'penname';
        $result = $this->cache->memcached->get($mem_key);
        if ($this->refresh || $result === false) {
            // 获取全部的笔名
            $sql = "SELECT id,sPenName FROM `fashion`.`fashion_pop_login_name` ";
            $array = $this->query($sql);
            $result = array();
            foreach ($array as $val) {
                $result[$val['id']] = $val['sPenName'];
            }
            $this->cache->memcached->save($mem_key, $result, 360);
        }
        // 获取单个笔名
        return isset($result[$id]) && !empty($result[$id]) ? $result[$id] : '';
    }
}