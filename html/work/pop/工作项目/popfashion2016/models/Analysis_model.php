<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Analysis栏目 专用类
 *
 * @property-read common_model $common_model
 */
class Analysis_model extends POP_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
    }

    /**
     * [getAnalysisLists 获取每页单张展示的数据+总条数]
     * @param  string $params [url参数]
     * @param  integer $columnId [栏目id]
     * @param  integer $offset [偏移量]
     * @param  integer $limit [每页条数]
     * @return [array]   &$lists     [根据条件查询出的结果]
     * @return [array]   $totalCount [根据条件查询出的总条数]
     */
    public function getAnalysisLists($params = '', $columnId, &$lists, $offset = 0, $limit = 10, $powers = array(), $conditions = [])
    {
        $this->benchmark->mark('getAnalysisLists');
        $paramsArr = [];
        if (!empty($params)) {
            $paramsArr = $this->common_model->parseParams($params);
        }
        $columnPid = GetCategory::getOtherFromColId($columnId);
        if (!$columnPid) {
            $columnPid = $columnId;
        }
        // 条件
        if (empty($conditions)) {
            $conditions = $this->getConditions($params, $columnId, $powers);
        }
        // 排序
        $arSort = $this->common_model->getSort($params, $powers, $columnPid);
        // 性别
        $gender = $this->common_model->getGenderByRequest($params);
        // 行业
        $industry = $this->common_model->getIndustryByRequest($params);
        $aIndustry = [6, 7, 8, 9, 10, 11];
        // 只取推荐到款式栏目的报告
        $styleRec = (bool)stristr($params, 'styleRec');
        if ($styleRec) {
            $conditions['iStyleRec'] = 1;
        }
        $result = [];
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $arSort);
        $collectStatusList = $this->getListDataCollectStatus($result);
        $this->load->model('report_model');
        $lists = [];
        foreach ($result as $key => $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];
            $data = OpPopFashionMerger::getProductData($id, $tableName);
            $info = $data[$id];

            //用做权限判断的时间，只取创建时间
            if (!empty($info['dPubTime'])) {
                $info['iCreateTime'] = strtotime($info['dPubTime']);
            } elseif (!empty($info['dCreateTime'])) {
                $info['iCreateTime'] = strtotime($info['dCreateTime']);
            } elseif (!empty($info['create_time'])) {
                $info['iCreateTime'] = strtotime($info['create_time']);
            } elseif (!empty($info['addtime'])) {
                $info['iCreateTime'] = strtotime($info['addtime']);
            } elseif (!empty($info['create_time'])) {
                $info['iCreateTime'] = strtotime($info['time']);
            }

            // 发布时间
            switch ($tableName) {
                case 'specialtopic':
                case 'specialtopic_graphic':
                    $info['description'] = htmlspecialchars(trim(strip_tags($info['description']))); //摘要
                    $info['create_time'] = $info['addtime'];
                    break;
                case 'mostrend_content':
                    $info['create_time'] = $info['release_time'];
                    $info['description'] = htmlspecialchars(trim(strip_tags($info['description']))); //摘要
                    break;
                case 't_trend_report':
                    $info['title'] = $info['sTitle'];
                    $info['create_time'] = $info['dPubTime'];
                    $info['description'] = htmlspecialchars(trim(strip_tags($info['sDesc'])));//摘要
                    break;
                case 'fs_analysis':
                case 'fs_commodity':
                case 'fs_design':
                case 'fs_inspiration':
                case 'fs_trend':
                    $info['description'] = htmlspecialchars(trim(strip_tags($info['abstract']))); //摘要
                    break;
            }

            // 获取浏览量，浏览量的数据实时存放在memecache中，memecache中的数据通过消息队列，异步永久化到数据库中。
            $this->load->model('statistics_model');
            $info['view_count'] = $this->statistics_model->getViews($tableName, $id, $info);

            // 潮流解析标题$info['title']
            $info['title'] = htmlspecialchars(stripcslashes($info['title']));
            // 图片路径
            $imgPath = getImagePath($tableName, $info);
            $info['cover'] = getFixedImgPath($imgPath['cover']);

            // 详情是否有视频 ==> 角标
            $info['isLiveVideo'] = false;
            if (!empty($info['sLiveUrl']) && !empty($info['dLiveStartTime']) && !empty($info['dLiveEndTime'])) {
                $info['isLiveVideo'] = true;
            }
            // 视频链接详情才可以看
            if (isset($info['sLiveUrl'])) {
                unset($info['sLiveUrl']);
            }
            if (isset($info['dLiveStartTime'])) {
                unset($info['dLiveStartTime']);
            }
            if (isset($info['dLiveEndTime'])) {
                unset($info['dLiveEndTime']);
            }

            $lists[$key]['list'] = $info;

            //是否显示推荐角标
            $tmpTime3 = strtotime('-18 month');
            $tmpTime4 = strtotime('-4 month');
            $tmpTime5 = strtotime('-19 month');
            $lists[$key]['isShowMark'] = 0;
            $lists[$key]['isShow'] = 0;
            if (isset($info['iReconmmendMutual']) && $info['iReconmmendMutual'] == 1) {
                if ($industry) {
                    //行业优先
                    if ($info['iCreateTime']) {
                        if ($info['iCreateTime'] >= $tmpTime4) {
                            $lists[$key]['isShowMark'] = 1; //  热门
                        }
                    }
                } elseif ($gender) {
                    //只选择性别时排除行业
                    if (isset($info['sIndustry'])) {
                        $_industry = explode(',', $info['sIndustry']);
                    } elseif (isset($info['iIndustry'])) {
                        $_industry = explode(',', $info['iIndustry']);
                    }
                    $_temp = [];
                    if (is_array($_industry)) {
                        $_temp = array_intersect_assoc($_industry, $aIndustry);
                    }
                    if (empty($_temp)) {
                        if ($info['iCreateTime']) {
                            if ($info['iCreateTime'] >= $tmpTime4) {
                                $lists[$key]['isShowMark'] = 1; //  热门
                            }
                        }
                    }
                } else {
                    if ($info['iCreateTime']) {
                        if ($info['iCreateTime'] >= $tmpTime4) {
                            $lists[$key]['isShowMark'] = 1; //  热门
                        }
                    }
                }
                if ($info['iCreateTime'] && $info['iCreateTime'] >= $tmpTime5 && $info['iCreateTime'] < $tmpTime3) {
                    $lists[$key]['isShow'] = 1; //  试读
                }
            }
            $lists[$key]['columnId'] = $val['iColumnId'][1];
            $lists[$key]['tableName'] = getProductTableName($tableName);
            $lists[$key]['offset'] = $offset++;
            $lists[$key]['total'] = $totalCount;
            //判断图片是否有遮罩
            $lists[$key]['shade'] = $powers['shade'] ? $powers['shade'] : '';
            $lists[$key]['iCollectStatus'] = array_search($val['pop_id'], $collectStatusList) === FALSE ? 0 : 1; // 是否收藏

            $_labels = $this->report_model->getLabelInfo($tableName, $id, $columnId, $params, 'list'); // 取列表页标签
            $lists[$key]['labels'] = $_labels;
        }
        $this->benchmark->mark('getAnalysisListsEnd');
        return $totalCount;
    }

    /**
     * [getConditions 获取solr查询的condition条件]
     * @param          string    or array    $params     [url参数]
     * @param  integer $columnId [栏目id]
     * @return [array]  $conditions
     */
    public function getConditions($params = '', $columnId, $powers = [])
    {
        if (!empty($params)) {
            if (is_array($params)) {
                $paramsArr = $params;
            } else {
                $paramsArr = $this->common_model->parseParams($params);
            }
        } else {
            $paramsArr = [];
        }
        // 权威数据
        if ($columnId == 112) {
            $conditions = ['iColumnId' => 2,];
            $conditions['iIsAuthorized'] = 1;
        } else {
            $conditions = ['iColumnId' => $columnId,];
        }
        // 性别
        $gender = $this->common_model->getGenderByRequest($params);
        $this->common_model->childGender($gender, $conditions);
        if ($gender == 5) {
            // 年龄层
            if (isset($paramsArr['age'])) {
                $conditions['other'][] = 'aLabelIds:' . $paramsArr['age'];
            }
        }
        // 行业
        $industry = $this->common_model->getIndustryByRequest($params);
        if ($industry) {
            $conditions['other'][] = 'aLabelIds:' . $industry;
        }
        // 只看收藏
        if (isset($paramsArr['coll']) && $paramsArr['coll']) {
            $conditions['aCollectAccount'] = getUserId();
        }
        // 只看热门
        if (isset($paramsArr['ihot']) && $paramsArr['ihot']) {
            if ($paramsArr['ihot'] == 1) {
                // 1 => 热门
                $startTime = date("Y-m-d\TH:i:s\Z", strtotime('-4 month'));
                $endTime = date("Y-m-d\TH:i:s\Z", strtotime("-2 hour"));
            } else if ($paramsArr['ihot'] == 2) {
                // 2 => 试读
                $startTime = date("Y-m-d\TH:i:s\Z", strtotime('-10 month'));
                $endTime = date("Y-m-d\TH:i:s\Z", strtotime('-4 month'));
            } else if ($paramsArr['ihot'] == 3) {
                // 3 即是热门也是试读
                $startTime = date('Y-m-d', strtotime('-4 month')) . 'T' . date('H:i:s') . 'Z';
                $endTime = date('Y-m-d', strtotime('-3 month')) . 'T' . date('H:i:s') . 'Z';
            }
            $conditions['other'][] = 'dCreateTime:[' . $startTime . ' TO ' . $endTime . ']';
            if ($gender && !$industry) {
                $conditions['other'][] = '-aLabelIds:(6 OR 7 OR 8 OR 9 OR 10 OR 11)';
            }
            $conditions['iHot'] = 1;
        }
        // 只看数据
        if (isset($paramsArr['auth']) && $paramsArr['auth']) {
            $conditions['iIsAuthorized'] = 1;
        }
        // 只看面料
        if (isset($paramsArr['uli']) && $paramsArr['uli']) {
            $conditions['iUliaobao'] = 1;
        }
        // 没有权限检索(除了性别和行业)
        if (!empty($powers) && !$powers['P_Search']) {
            return $conditions;
        }

        // SB需求导致性别的进一步特殊处理
        $this->common_model->childCGender($paramsArr, $conditions);

        // 时间范围,0=>不限，1=>近7日,2=>近30日,3=>近半年
        if (isset($paramsArr['tim']) && !$paramsArr['tim']) {
            $conditions['dCreateTime'] = $this->common_model->getTimeRange($paramsArr['tim']);
        }
        // 地区
        if (isset($paramsArr['reg']) && $region = (int)$paramsArr['reg']) {
            $conditions['other'][] = "(iRegion:{$region} OR iArea:{$region} OR iContinent:{$region} OR iCountry:{$region})";
        }
        // 品牌
        if (isset($paramsArr['bra'])) {
            $conditions['iBrand'] = (int)$paramsArr['bra'];
        }
        if (isset($paramsArr['man']) && !empty($paramsArr['man'])) {
            $conditions['sManner'] = $paramsArr['man'];
        }
        // 展会名称
        if (isset($paramsArr['exh'])) {
            $conditions['sExhibitionName'] = $paramsArr['exh'];
        }
        // 季节
        if (isset($paramsArr['sea'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['sea'];
        }
        // 品类
        if (isset($paramsArr['cat'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['cat'];
        }
        // 视角
        if (isset($paramsArr['vis'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['vis'];
        }
        // 品牌定位
        if (isset($paramsArr['bpos'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['bpos'];
        }
        // 时装周专题
        if (isset($paramsArr['fas'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['fas'];
        }
        // 市场类型
        if (isset($paramsArr['mar'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['mar'];
        }
        // 街拍类型
        if (isset($paramsArr['str'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['str'];
        }
        // 街拍类型
        if (isset($paramsArr['str'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['str'];
        }
        // 陈列类型
        if (isset($paramsArr['dtyp'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['dtyp'];
        }
        // 报道类型
        if (isset($paramsArr['rep'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['rep'];
        }
        // 潮流类型
        if (isset($paramsArr['tid'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['tid'];
        }
        // 关键字检索
        $keyword = $this->common_model->getKeyword($params, $powers);
        if (strpos($keyword, '?key=') === 0) {
            $keyword = ltrim($keyword, '?key=');
        }
        // 详情是否有视频
        if (isset($paramsArr['tlive']) && ($paramsArr['tlive'] == 1)) {
            $conditions['aVersion'] = 'video';
        }
        if ($keyword) {
            $conditions['other'][] = 'combine:(' . $keyword . ')';
        }
        return $conditions;
    }

    /**
     * [getCategorys 获取某栏目的品类(有数据)]
     * @param  integer $columnId [栏目id]
     * @param  string $params [url参数]
     * @return [array]  $categorys
     */
    public function getCategorys($columnId, $params = '', $powers = [])
    {
        $paramTmp = $this->common_model->parseParams($params);
        if (!empty($paramTmp['cat'])) {
            return [];
        }
        $this->benchmark->mark('getCategorys');
        $this->load->model('category_model');
        $conditions = $this->getConditions($params, $columnId, $powers);
        $keys = $this->common_model->getKeyword('', $powers);
        if (isset($paramTmp['man']) && !empty($paramTmp['man'])) {
            $conditions['sManner'] = $paramTmp['man'];
        }
        $realCategorys = $this->category_model->getCategoryAndSubCategory($columnId == 112 ? 2 : $columnId, $conditions, $keys);
        $categorys = [];
        if (!empty($realCategorys)) {
            foreach ($realCategorys as $id => $category) {
                $subcategorys = [];
                $_categorys = ['name' => $category['name'], 'link' => $this->common_model->getLink($columnId, $params, 'cat', $id, TRUE, 'anchor')];
                if (!empty($category['sSubCategory'])) {
                    foreach ($category['sSubCategory'] as $_id => $_name) {
                        $subcategorys[] = ['name' => $_name, 'link' => $this->common_model->getLink($columnId, $params, 'cat', $_id, TRUE, 'anchor')];
                    }
                    $_categorys['subcategorys'] = $subcategorys;
                }
                $categorys[] = $_categorys;
            }
        }

        $this->benchmark->mark('getCategorysEnd');
        return $categorys;
    }

    /**
     * [ getSelectItems  获取指定栏目有数据的所有查询条件]
     * @param  array $selectItem [筛选条件]
     * @param  integer $columnId [栏目id]
     * @param  string $params [url参数]
     * @return array   $selectItems  [筛选条件下的solr里有数据的所有子条件]
     */
    public function getSelectItems($selectItem, $columnId, $params = '', $powers = [])
    {
        $this->benchmark->mark('groupbySelect');
        $this->load->model('category_model');
        $conditions = $this->getConditions($params, $columnId, $powers);

        $keys = $this->common_model->getKeyword('', $powers);
        //=============================
        $selectItems = [];
        $paramToSolrArr = $this->common_model->getKeyValMap();
        $paramTmp = $this->common_model->parseParams($params);
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
        $items = $this->category_model->getRealCategory($selectItem, $columnId == 112 ? 2 : $columnId, $conditions, $keys);
        foreach ($items as $key => $val) {
            if (empty($val)) {
                $selectItems[$key] = [];
                continue;
            }
            switch ($key) {
                // 视角
                case 'sVisualAngle':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'vis');
                    break;
                // 季节
                case 'iSeason':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'sea');
                    break;
                // 展会名称
                case 'sExhibitionName':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'exh', TRUE);
                    break;
                // 品牌定位
                case 'sMarketHotPosition':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'bpos');
                    break;
                // 时装周类型
                case 'sFashionWeek':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'fas', TRUE);
                    break;
                // 市场类型
                case 'sMarketType':
                    if (in_array($columnId, array(33, 132))) {
                        $selectItems[$key] = $this->getItems($columnId, $params, $val, 'mar', TRUE);
                    } else {
                        foreach ($val as $id => $name) {
                            $selectItems[$key][] = ['name' => $name['sName'], 'link' => $this->common_model->getLink($columnId, $params, 'mar', $id, TRUE, 'anchor')];
                        }
                    }
                    break;
                // 报道类型
                case 'sReportType':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'rep');
                    break;
                // 街拍类型
                case 'sStreetBeatType':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'str');
                    break;
                // 陈列类型
                case 'sDisplayType':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'dtyp');
                    break;
                // 潮流类型
                case 'sTidalType':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'tid');
                    break;
                // 风格
                case 'sManner':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'man');
                    break;
                //年龄层
                case 'sAgeLayer':
                    $gender_str = $this->common_model->getGenderByRequest($params);
                    if ($gender_str == 5 || (!empty($paramTmp['gcen']) && in_array($paramTmp['gcen'], array(3, 4)))) {
                        foreach ($val as $k => $v) {
                            //童装年龄层 婴幼童 395 / 中小童 394 / 青少年393 | 11302（不知道是什么）
                            $AgeLevelForChild = [395, 394, 393];
                            if (!in_array($k, $AgeLevelForChild)) {
                                unset($val[$k]);
                            }
                        }
                    }
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'age');
                    break;
                default:
                    // # code...
                    break;
            }
        }
        $this->benchmark->mark('groupbySelectEnd');
        return $selectItems;
    }

    /**
     * [getSeoArray   获取seo搜索引擎，标题、关键字、描述]
     * @param  integer $columnId [栏目id]
     * @return string   [中文字符]
     */
    public function getSeoArray($columnId, $params = '')
    {
        //性别
        $gender = $this->common_model->getGenderByRequest($params);
        $gen = !empty($gender) ? trim(GetCategory::getOtherFromIds($gender, ['sName'])) : '';
        //行业
        $industry = $this->common_model->getIndustryByRequest($params);
        $ind = !empty($industry) ? trim(GetCategory::getOtherFromIds($industry, ['sName'])) : '';
        if ($ind == '运动') {
            $ind = '运动装';
        }
        $paramsArr = [];
        if (!empty($params)) {
            $paramsArr = $this->common_model->parseParams($params, 1);
        }
        //单品，品名
        $cat = $subcat = '';
        if (isset($paramsArr['cat']) && !empty($paramsArr['cat'])) {
            $arr = GetCategory::getOtherFromIds($paramsArr['cat'], ['sName', 'iAttributePid'], 'array');
            // 说明该参数是品名，根据品名取单品
            if ($arr['iAttributePid'][0] == '11115') {
                $catId = GetCategory::getCatIdsBySubcatIds($paramsArr['cat']);
                $cat = GetCategory::getOtherFromIds($catId, ['sName']);
                $subcat = $arr['sName'][0];
            } else {
                $cat = $arr['sName'][0];
            }
        }
        $cat = trim($cat);
        //地区
        $reg = $this->common_model->getDefaultParams('reg', $params, $columnId);
        //季节
        $sea = $this->common_model->getDefaultParams('sea', $params, $columnId);
        //品牌
        $bra = $this->common_model->getDefaultParams('bra', $params, $columnId);
        //展会名称
        $exh = $this->common_model->getDefaultParams('exh', $params, $columnId);
        //时装周专题
        $fas = $this->common_model->getDefaultParams('fas', $params, $columnId);
        //视角
        $vis = $this->common_model->getDefaultParams('vis', $params, $columnId);
        //报道类型
        $rep = $this->common_model->getDefaultParams('rep', $params, $columnId);
        // 爆款数据
        if ($columnId == 132) {
            // 风格
            $man = $this->common_model->getDefaultParams('man', $params, $columnId);
        }

        //栏目名称
        $colName = GetCategory::getOtherFromColId($columnId, 'sName');
        //测试获取时段信息
        $timArray = array('全部时段', '近7日', '近30日', '近半年');
        $sorArray = array(1 => "按时间最新", 2 => "按浏览最高", 3 => "按收藏最多");
        //最热门
        $ihot = $paramsArr['ihot'] ? '最热门' : '';
        //大数据
        $auth = $paramsArr['auth'] ? '大数据' : '';
        //配面料
        $uli = $paramsArr['uli'] ? '配面料' : '';
        //时段
        $jtime = $timArray[$paramsArr['tim']];
        //时间
        $sor = $sorArray[$paramsArr['sor']];
        //替换分页字段为空
        $preg = '/page_\d*/';
        $paramsArray = explode('-', $params);
        if (count($paramsArray) == 1) {
            $paramsArray[0] = preg_replace($preg, "", $paramsArray[0]);
        }
        $params = implode('-', $paramsArray);
        if (empty($params) && empty($gen) && empty($ind)) {
            if ($columnId == 2) {
                $title = 'T台/订货会/设计师/标杆品牌/市场/展会/街拍分析_潮流领袖_行业报道-POP服装趋势网';
                $description = 'POP服装趋势网流行分析栏目为您提供服装最新、最前沿、最具权威的流行分析资讯，以及及时为您提供相关的市场环境、店铺形象、行业渠道、潮流领袖、街拍等的分析报告。';
            } else {
                $title = $colName . '_流行分析-POP服装趋势网';
                $description = 'POP服装趋势网流行分析的' . $colName . '栏目为您提供服装最新、最前沿、最具权威的时尚资讯，为您及时提供与' . $colName . '相关的趋势讲解、深度剖析和详情报告等资讯，为您提供有价值的服装设计资讯服务。';
            }
            $keyInfo = [
                2 => 'T台分析,订货会分析,设计师品牌分析,标杆品牌分析,市场分析,展会分析,潮流领袖,街拍分析,行业报道',
                30 => 'T台秀,T台分析,T台解析',
                32 => '订货会,订货会分析,订货会解析',
                34 => '设计师品牌,品牌在线分析,品牌解析',
                35 => '品牌在线,标杆品牌,品牌分析',
                33 => '市场分析,零售市场,零售市场分析,零售解析',
                132 => '爆款分析,爆款数据,爆款解析',
                31 => '展会报告,展会分析,展览',
                38 => '潮流领袖,潮流解析,名星时装,时尚潮流',
                37 => '街拍图库,街拍分析,街拍解析,时尚街拍',
                40 => '行业分析,行业解析,行业报道'
            ];
            $keywords = $keyInfo[$columnId];
        } else {
            $jh = array_filter([$gen, $ind, $sea, $fas, $vis, $bra, $cat, $subcat, $reg, $exh, $rep, $ihot, $auth, $uli, $jtime, $sor]);
            if ($columnId == 132) {
                // 爆款数据多个【风格】
                $jh = array_filter([$gen, $ind, $sea, $fas, $vis, $bra, $cat, $subcat, $reg, $exh, $rep, $ihot, $auth, $uli, $jtime, $sor, $man]);
            }
            $t = implode('_', $jh) . '_';
            $k = implode(',', $jh) . ',';
            $d = implode('/', $jh);
            if ($columnId == 2) {
                $title = $t . '流行分析-POP服装趋势网';
                $description = 'POP服装趋势网流行分析栏目为您提供' . $d . '最新、最前沿、最具权威的流行分析资讯，为您及时提供相关的T台、订货会、标杆品牌、市场和展会等的分析报告，为您提供有价值的服装设计资讯服务。';
            } else {
                $title = $t . $colName . '-POP服装趋势网';
                $description = 'POP服装趋势网' . $colName . '栏目为您提供' . $d . '最新、最前沿、最具权威的时尚资讯，为您及时提供与' . $d . '相关的趋势讲解、深度剖析和详情报告等资讯，为您提供有价值的服装设计资讯服务。';
            }
            $keywords = $k . $colName;
        }
        $seoArray[$columnId] = ['title' => $title, 'keywords' => $keywords, 'description' => $description];
        return $seoArray[$columnId];
    }

    /**
     * 获取栏目介绍的内容
     * @param $columnId 栏目ID
     * @return string 栏目介绍
     */
    public function getColumnsPresentation($columnId)
    {
        switch ($columnId) {
            case '2':
            case '30':
            case '31':
            case '32':
            case '33':
            case '34':
            case '35':
            case '36':
            case '37':
            case '38':
            case '40':
            case '112':
            case '132': // 爆款数据
            default:
                $columnPresentation = "有依据；够全面——理性的数据分析，感性的流行变化推荐。了解全球潮流动向、全面完整解析市场变化、重点观察中国潮流趋势动态";
                break;
        }
        return $columnPresentation;
    }

    private function getItems($columnId, $params, $arr, $type, $double = FALSE)
    {
        $ret = [];
        if ($double) {
            foreach ($arr as $Pid => $Pname) {
                $tempArr = [];
                $tempArr['name'] = $Pname['sName'];
                if (intval($Pid) > 0) {
                    $tempArr['link'] = $this->common_model->getLink($columnId, $params, $type, $Pid, TRUE, 'anchor');
                }
                if (is_array($Pname['attrs'])) {
                    foreach ($Pname['attrs'] as $id => $name) {
                        $tempArr['attrs'][] = ['name' => $name['sName'], 'link' => $this->common_model->getLink($columnId, $params, $type, $id, TRUE, 'anchor')];
                    }
                } else {
                    $tempArr['attrs'][] = ['name' => '----', 'link' => ''];
                }
                $ret[] = $tempArr;
            }
        } else {
            foreach ($arr as $id => $name) {
                // 去掉视角中的品牌和单品（11620=>品牌，11621=>单品）
                /*if($type == 'vis' && in_array($id, [11620,11621])) {
                    continue;
                }*/
                $ret[] = ['name' => $name, 'link' => $this->common_model->getLink($columnId, $params, $type, $id, TRUE, 'anchor')];
            }
        }
        return $ret;
    }
}
