<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Trends栏目 专用类
 * @property-read common_model $common_model
 */
class Trends_model extends POP_Model
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
    }

    /**
     * [getTrendLists 获取每页单张展示的数据+总条数]
     * @param  string $params [url参数]
     * @param  integer $columnId [栏目id]
     * @param  array $lists
     * @param  integer $offset [偏移量]
     * @param  integer $limit [每页条数]
     * @param  array $powers
     * @param  array $conditions
     * @return bool [array]   &$lists     [根据条件查询出的结果]
     */
    public function getTrendLists($params = '', $columnId, &$lists, $offset = 0, $limit = 10, $powers = array(), $conditions = [])
    {
        $this->benchmark->mark('getTrendLists');
        if (!empty($params)) {
            $paramsArr = $this->common_model->parseParams($params);
        }
        $lists = [];
        $columnPid = GetCategory::getOtherFromColId($columnId);
        if ($columnPid == 0) {
            $columnPid = $columnId;
        }
        // 条件
        if (empty($conditions)) {
            $conditions = $this->getConditions($params, $columnId, $powers);
        }
        // 趋势-关注，主题类型,1=>图案主题,2=>款式主题
        if (isset($paramsArr['top']) && !empty($paramsArr['top'])) {
            if ($paramsArr['top'] == 1) {
                $conditions['tablename'] = 'specialtopic_graphic';
            } elseif ($paramsArr['top'] == 2) {
                $conditions['tablename'] = 'specialtopic';
            }
        }
        if (isset($paramsArr['man']) && !empty($paramsArr['man'])) {
            $conditions['sManner'] = $paramsArr['man'];
        }
        // 排序
        $arSort = $this->common_model->getSort($params, $powers, $columnPid);
        // 性别
        $gender = $this->common_model->getGenderByRequest($params);
        // 行业
        $industry = $this->common_model->getIndustryByRequest($params);
        $aIndustry = [6, 7, 8, 9, 10, 11];

        $result = [];
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $arSort);
        $collectStatusList = $this->getListDataCollectStatus($result);
        foreach ($result as $key => $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];
            $data = OpPopFashionMerger::getProductData($id, $tableName);
            $info = $data[$id];

            // id
            $info['id'] = $id;

            //用做权限判断的时间，只取创建时间
            if (!empty($info['dPubTime'])) {
                $info['iCreateTime'] = strtotime($info['dPubTime']);
            } elseif (!empty($info['dPubTime'])) {
                $info['iCreateTime'] = strtotime($info['dPubTime']);
            } elseif (!empty($info['create_time'])) {
                $info['iCreateTime'] = strtotime($info['create_time']);
            } elseif (!empty($info['addtime'])) {
                $info['iCreateTime'] = strtotime($info['addtime']);
            } elseif (!empty($info['time'])) {
                $info['iCreateTime'] = strtotime($info['time']);
            }
            // 发布时间,标题,摘要
            switch ($tableName) {
                case 'specialtopic':
                case 'specialtopic_graphic':
                    $info['create_time'] = $info['addtime'];// 发布时间
                    break;
                case 'mostrend_content':
                    $info['create_time'] = $info['release_time'];
                    break;
                case 't_trend_report':
                    $info['title'] = $info['sTitle'];//标题
                    $info['create_time'] = $info['dPubTime'];// 发布时间
                    break;
            }
            // 摘要
            if (isset($info['abstract'])) {
                $info['description'] = $info['abstract'];
            } elseif (isset($info['title_describe'])) {
                $info['description'] = $info['title_describe'];
            } elseif (isset($info['sDesc'])) {
                $info['description'] = $info['sDesc'];
            }

            // 获取浏览量，浏览量的数据实时存放在memecache中，memecache中的数据通过消息队列，异步永久化到数据库中。
            $this->load->model('statistics_model');
            $info['view_count'] = $this->statistics_model->getViews($tableName, $id, $info);

            $info['title'] = htmlspecialchars(stripcslashes($info['title']));
            $info['description'] = htmlspecialchars(trim(strip_tags($info['description'])));
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
            $lists[$key]['isShowMark'] = 0; //热门
            $lists[$key]['isShow'] = 0; //试读
            if (isset($info['iReconmmendMutual']) && $info['iReconmmendMutual'] == 1) {
                if ($industry) {
                    //行业优先
                    if (!empty($info['iCreateTime'])) {
                        if ($info['iCreateTime']) {
                            if ($info['iCreateTime'] >= $tmpTime4) {
                                $lists[$key]['isShowMark'] = 1; //  热门
                            }
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
                        $_temp = array_intersect($_industry, $aIndustry);
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
            // 判断图片是否有遮罩
            $lists[$key]['shade'] = $powers['shade'] ? $powers['shade'] : '';
            $lists[$key]['iCollectStatus'] = array_search($val['pop_id'], $collectStatusList) === false ? 0 : 1; // 是否收藏

            $this->load->model('report_model');
            $_labels = $this->report_model->getLabelInfo($tableName, $id, $columnId, $params, 'list'); // 取列表页标签
            $lists[$key]['labels'] = $_labels;
        }
        $this->benchmark->mark('getTrendListsEnd');
        return $totalCount;
    }

    /**
     * [getConditions 获取solr查询的condition条件]
     * @param string $params
     * @param  integer $columnId [栏目id]
     * @param array $powers
     * @return array [array]  $conditions
     */
    public function getConditions($params = '', $columnId, $powers = [])
    {
        $this->benchmark->mark('getConditions');
        if (!empty($params)) {
            if (is_array($params)) {
                $paramsArr = $params;
            } else {
                $paramsArr = $this->common_model->parseParams($params);
            }
        } else {
            $paramsArr = [];
        }
        $conditions = ['iColumnId' => $columnId,];
        // 性别
        $gender = $this->common_model->getGenderByRequest($params);
        $this->common_model->childGender($gender, $conditions);
        // 行业
        $industry = $this->common_model->getIndustryByRequest($params);

        //图案库-图案专题 不处理行业
        $isSTP = false;
        if (isset($paramsArr['stp']) && $paramsArr['stp'] == 1) {
            $isSTP = true;
        }

        if ($industry && !$isSTP) {
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
            } elseif ($paramsArr['ihot'] == 2) {
                // 2 => 试读
                $startTime = date("Y-m-d\TH:i:s\Z", strtotime('-10 month'));
                $endTime = date("Y-m-d\TH:i:s\Z", strtotime('-4 month'));
            } elseif ($paramsArr['ihot'] == 3) {
                // 3 即是热门也是试读
                $startTime = date("Y-m-d\TH:i:s\Z", strtotime('-10 month'));
                $endTime = date("Y-m-d\TH:i:s\Z", strtotime('-4 month'));
            }
            $conditions['other'][] = 'dCreateTime:[' . $startTime . ' TO ' . $endTime . ']';
            if ($gender && !$industry && !$isSTP) {
                $conditions['other'][] = '-aLabelIds:(6 OR 7 OR 8 OR 9 OR 10 OR 11)';
            }
            $conditions['iHot'] = 1;
        }
        // 只看面料
        if (isset($paramsArr['uli']) && $paramsArr['uli']) {
            $conditions['iUliaobao'] = 1;
        }
        // 没有权限检索(除了性别和行业)
        if (!empty($powers) && !$powers['P_Search']) {
            return $conditions;
        }

        //***************以下没有权限不可以搜索********************//

        // SB需求导致性别的进一步特殊处理
        $this->common_model->childCGender($paramsArr, $conditions);

        // 时间范围,0=>不限，1=>近7日,2=>近30日,3=>近半年
        if (isset($paramsArr['tim']) && $paramsArr['tim'] != 0) {
            $conditions['dCreateTime'] = $this->common_model->getTimeRange($paramsArr['tim']);
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
        // 趋势专题
        if (isset($paramsArr['relt'])) {

            $this->load->model('Category_model');
            $_relationTheme = $this->Category_model->getAll('iRelationTheme');
            // 条件成立说明是一级趋势专题
            if (isset($_relationTheme[$paramsArr['relt']])) {
                $theme_ids = array_keys($_relationTheme[$paramsArr['relt']]['attrs']);
                $conditions['other'][] = 'iRelationTheme:(' . implode(' OR ', $theme_ids) . ')';
            } else {
                $conditions['iRelationTheme'] = $paramsArr['relt'];
            }
        }

        // 详情是否有视频
        if (isset($paramsArr['tlive']) && ($paramsArr['tlive'] == 1)) {
            $conditions['aVersion'] = 'video';
        }

        // 年龄层
        if ($gender == 5 || ($columnId == 21)) {
            if (isset($paramsArr['age'])) {
                $conditions['other'][] = 'aLabelIds:' . $paramsArr['age'];
            }
        }
        // 地域风格
        if (isset($paramsArr['regs'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['regs'];
        }
        //工艺类型
        if (isset($paramsArr['tect'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['tect'];
        }
        //期数
        if (isset($paramsArr['no'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['no'];
        }
        // 关键字检索
        $keyword = $this->common_model->getKeyword($params, $powers);
        if (strpos($keyword, '?key=') === 0) {
            $keyword = ltrim($keyword, '?key=');
        }
        if ($keyword) {
            $conditions['other'][] = 'combine:(' . $keyword . ')';
        }
        // 图案专题特殊处理:stp(iSpecialTopicPatterns)
        if (isset($paramsArr['stp']) && $paramsArr['stp'] != 0) {
            $conditions['iIsSpecialTopicPatterns'] = 1;
        }
        $this->benchmark->mark('getConditionsEnd');
        return $conditions;
    }

    /**
     * [getSelectItems 获取指定栏目有数据的所有查询条件]
     * @param  array $selectItem [筛选条件]
     * @param  integer $columnId [栏目id]
     * @param  string $params [url参数]
     * @param  array $powers
     * @return array   $selectItems  [筛选条件下的solr里有数据的所有子条件]
     */
    public function getSelectItems($selectItem, $columnId, $params = '', $powers = [])
    {
        $this->benchmark->mark('groupBySelect');
        $this->load->model('category_model');
        // 视角特殊处理
        if (in_array('sVisualAngle', $selectItem)) {
            $conditions = ['iColumnId' => $columnId,];
            $visualRet = $this->category_model->getRealCategory(['sVisualAngle'], $columnId, $conditions);
        }
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
        //=============================
        $conditions = $this->getConditions($params, $columnId, $powers);
        if (isset($paramTmp['man']) && !empty($paramTmp['man'])) {
            $conditions['sManner'] = $paramTmp['man'];
        }
        $items = $this->category_model->getRealCategory($selectItem, $columnId, $conditions, $keys);
        $items['sVisualAngle'] = $visualRet['sVisualAngle'];
        foreach ($items as $key => $val) {
            if (empty($val)) {
                $selectItems[$key] = [];
                continue;
            }
            switch ($key) {
                // 季节
                case 'iSeason':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'sea');
                    break;
                //观点
                case 'sViewpoint':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'vie');
                    break;
                //视角
                case 'sVisualAngle':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'vis');
                    break;
                //趋势专题
                case 'iRelationTheme':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'relt', true);
                    break;
                //年龄层
                case 'sAgeLayer':
                    if ($columnId != 21) { // 21--企划/组货,特殊处理
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
                    }
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'age');
                    break;
                //地域风格
                case 'sRegionalStyle':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'regs');
                    break;
                //工艺类型
                case 'iTechnologyType':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'tect');
                    break;
                //期数
                case 'iNo':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'no');
                    break;
                // 风格
                case 'sManner':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'man');
                    break;
                default:
                    # code...
                    break;
            }
        }
        $this->benchmark->mark('groupBySelectEnd');
        return $selectItems;
    }

    /**
     * [getCategorys 获取某栏目的品类(有数据)]
     * @param  integer $columnId [栏目id]
     * @param  string $params [url参数]
     * @param array $powers
     * @return array [array]  $categorys
     */
    public function getCategorys($columnId, $params = '', $powers = [])
    {
        $this->benchmark->mark('getCategory');
        $paramTmp = $this->common_model->parseParams($params);
        if (!empty($paramTmp['cat'])) {
            return [];
        }
        $this->load->model('category_model');
        $conditions = $this->getConditions($params, $columnId, $powers);
        $keys = $this->common_model->getKeyword('', $powers);
        if (isset($paramTmp['man']) && !empty($paramTmp['man'])) {
            $conditions['sManner'] = $paramTmp['man'];
        }
        $realCategorys = $this->category_model->getCategoryAndSubCategory($columnId, $conditions, $keys);
        $categorys = [];
        if (!empty($realCategorys)) {
            foreach ($realCategorys as $id => $category) {
                $subcategorys = [];
                $_categorys = ['name' => $category['name'], 'link' => $this->common_model->getLink($columnId, $params, 'cat', $id, true, 'anchor')];
                if (!empty($category['sSubCategory'])) {
                    foreach ($category['sSubCategory'] as $_id => $_name) {
                        $subcategorys[] = ['name' => $_name, 'link' => $this->common_model->getLink($columnId, $params, 'cat', $_id, true, 'anchor')];
                    }
                    $_categorys['subcategorys'] = $subcategorys;
                }
                $categorys[] = $_categorys;
            }
        }

        $this->benchmark->mark('getCategoryEnd');
        return $categorys;
    }

    /**
     * [getSeoArray   获取seo搜索引擎，标题、关键字、描述]
     * @param  integer $columnId [栏目id]
     * @param string $params
     * @return string   [中文字符]
     */
    public function getSeoArray($columnId, $params = '')
    {
        //性别
        $paramsArr = [];
        if (!empty($params)) {
            $paramsArr = $this->common_model->parseParams($params, 1);
        }
        //性别
        $_array['gen'] = $this->common_model->getDefaultParams('gen', $params, $columnId);
        //行业
        $_array['ind'] = $this->common_model->getDefaultParams('ind', $params, $columnId);
        //季节
        $_array['sea'] = $this->common_model->getDefaultParams('sea', $params, $columnId);
        //单品
        $_array['cat'] = $this->common_model->getDefaultParams('cat', $params, $columnId);
        //趋势专题
        $_array['relt'] = $this->common_model->getDefaultParams('relt', $params, $columnId);
        //工艺类型
        $_array['tect'] = $this->common_model->getDefaultParams('tect', $params, $columnId);
        //期数
        $_array['no'] = $this->common_model->getDefaultParams('no', $params, $columnId);
        //时间段
        $tims = array(0 => "全部时段", 1 => "近7日", 2 => "近30日", 3 => "近半年");
        $_array['tim'] = !isset($tims[$paramsArr['tim']]) ? "" : $tims[$paramsArr['tim']];
        //排序
        $sors = array(1 => "按时间最新", 2 => "按浏览最高", 3 => "按收藏最多");
        $_array['sor'] = !isset($sors[$paramsArr['sor']]) ? "" : $sors[$paramsArr['sor']];
        $_array['coll'] = $paramsArr['coll'] == 1 ? "只看收藏" : "";
        $_array['ihot'] = $paramsArr['ihot'] == 1 ? "最热门" : "";
        $_array['uli'] = $paramsArr['uli'] == 1 ? "配面料" : "";
        $_array = array_filter($_array);

        $str1 = implode('_', $_array);
        $str2 = implode('、', $_array);
        $str3 = implode(',', $_array);

        //栏目名称
        $colName = GetCategory::getOtherFromColId($columnId, 'sName');
        if (empty($str1)) {
            if ($columnId == 1) {
                $title = '主题色彩_图案|面辅助|工艺|廓形趋势_企划/组货_快反应-POP服装趋势网';
                $keywords = '主题色彩,图案趋势,面辅助趋势,工艺趋势,廓形趋势,企划/组货,快反应';
                $description = 'POP服装趋势网趋势解读栏目提供服装最新最热门的设计专业分析报告，从色彩、面料、款式等方面，让设计师们从不同主题中获取服装设计灵感，为您提供有价值的服装设计资讯服务。';
            } else {
                $title = "{$colName}_趋势解读-POP服装趋势网";
                $keywords = "色彩,图案,面辅料,剪裁细节,工艺,配饰,{$colName}";
                $description = "POP服装趋势网{$colName}栏目提供服装最新、最前沿、最具权威的专业的流行趋势预测分析报告，从色彩、图案、面辅料、剪裁细节、工艺、配饰等方面，专业分析预测未来服装设计趋势走向，为您提供有价值的服装设计资讯服务。";
            }
        } else {
            $title = "{$str1}_{$colName}-POP服装趋势网";
            $keywords = "{$str3},{$colName}";
            $description = "POP服装趋势网{$colName}栏目提供{$str2}最新、最前沿、最具权威的专业的流行趋势预测分析报告，从色彩、图案、面辅料、剪裁细节、工艺、配饰等方面，专业分析预测未来服装设计趋势走向，为您提供有价值的服装设计资讯服务。";
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
            case '1':
            case '20':
            case '21':
            case '22':
            case '23':
            default:
                $columnPresentation = "能聚焦，快反应（大数据支撑下的趋势前瞻观点，层层深入的趋势预测-企划设计-时时提炼的趋势快反应）让您更自信的进行风格决策，稳定产品研发步调的同时，兼顾迅速应对市场的变化。";
                break;
        }
        return $columnPresentation;
    }

    private function getItems($columnId, $params, $arr, $type, $double = false)
    {
        $ret = [];
        if ($double) {
            foreach ($arr as $Pid => $Pname) {
                $tempArr = [];
                $tempArr['name'] = $Pname['sName'];

                if ($type == 'relt' && intval($Pid) === 0) {
                    $tempArr['link'] = $this->common_model->getLink($columnId, $params, $type, $Pname['id'], true, 'anchor');
                } elseif (intval($Pid) > 0) {
                    $tempArr['link'] = $this->common_model->getLink($columnId, $params, $type, $Pid, true, 'anchor');
                }

                if (is_array($Pname['attrs'])) {
                    foreach ($Pname['attrs'] as $id => $name) {
                        $tempArr['attrs'][] = ['name' => $name['sName'], 'link' => $this->common_model->getLink($columnId, $params, $type, $id, true, 'anchor'), 'val' => $id];
                    }
                } else {
                    $tempArr['attrs'][] = ['name' => '----', 'link' => '', 'id' => ''];
                }
                $ret[] = $tempArr;
            }
        } else {
            foreach ($arr as $id => $name) {
                $ret[] = ['name' => $name, 'link' => $this->common_model->getLink($columnId, $params, $type, $id, true, 'anchor'), 'id' => $id];
            }
            // 视角时，加入全部
            if ($type == 'vis') {
                $paramsArr = $this->common_model->parseParams($params, 1);
                if (isset($paramsArr['vis'])) unset($paramsArr['vis']);
                $params_str = $this->common_model->parseParams($paramsArr, 2);
                array_unshift($ret, ['name' => '全部视角', 'link' => $this->common_model->getLink($columnId, $params_str, '', '', true, 'anchor'), 'id' => 0]);
            }
        }
        return $ret;
    }

    /******************************* 疫情专题部分，2020-02-14 ******************************/

    // 获取疫情专题的数据
    public function getEpidemicTopicData($col, &$lists, $offset = 0, $limit = 120)
    {
        $conditions = $this->getEpidemicConditions($col);
        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];
        $result = array();
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $arSort);
        if (!empty($result)) {
            $rows = array();
            foreach ($result as $key => $val) {
                $id = $val['pri_id'];
                $tableName = $val['tablename'];
                $data = OpPopFashionMerger::getProductData($id, $tableName);
                $info = $data[$id];
                $rows['columnId'] = $val['iColumnId'][1];
                // 只取创建时间
                if (!empty($info['dPubTime'])) {
                    $rows['iCreateTime'] = strtotime($info['dPubTime']);
                } elseif (!empty($info['dPubTime'])) {
                    $rows['iCreateTime'] = strtotime($info['dPubTime']);
                } elseif (!empty($info['create_time'])) {
                    $rows['iCreateTime'] = strtotime($info['create_time']);
                } elseif (!empty($info['addtime'])) {
                    $rows['iCreateTime'] = strtotime($info['addtime']);
                } elseif (!empty($info['time'])) {
                    $rows['iCreateTime'] = strtotime($info['time']);
                }
                // 发布时间,标题,摘要
                switch ($tableName) {
                    case 'specialtopic':
                    case 'specialtopic_graphic':
                        $rows['create_time'] = $info['addtime'];// 发布时间
                        break;
                    case 'mostrend_content':
                        $rows['create_time'] = $info['release_time'];
                        break;
                    case 't_trend_report':
                        $rows['title'] = $info['sTitle'];//标题
                        $rows['create_time'] = $info['dPubTime'];// 发布时间
                        break;
                }
                // 摘要
                if (isset($info['abstract'])) {
                    $rows['description'] = $info['abstract'];
                } elseif (isset($info['title_describe'])) {
                    $rows['description'] = $info['title_describe'];
                } elseif (isset($info['sDesc'])) {
                    $rows['description'] = $info['sDesc'];
                }
                $rows['id'] = $id;
                $rows['t'] = getProductTableName($tableName);
                $rows['title'] = htmlspecialchars(stripcslashes($rows['title']));
                $rows['description'] = htmlspecialchars(trim(strip_tags($rows['description'])));
                // 图片路径
                $imgPath = getImagePath($tableName, $info);
                $rows['cover'] = getFixedImgPath($imgPath['cover']);
                $rows['detail_url'] = "/details/report/t_{$rows['t']}-id_{$id}-col_{$rows['columnId']}/";
                $lists[] = $rows;
            }
        }
        return $totalCount;
    }

    // 疫情专题接口,获取有数据的栏目
    public function getEpidemicTopicColumns()
    {
        $reportResult = [];
        // 报告栏目
        $reportParams = [
            'facet' => 'true',
            'facet.field' => 'iColumnId',
            'facet.limit' => 150,
            'raw' => true
        ];
        $condition = [
            'iEpidemicTopic' => 1,
            'iColumnId' => [1, 2],
        ];
        $rows = POPSearch::wrapQueryPopFashionMerger('', $condition, $reportResult, 0, 0, [], $reportParams);
        $facet_data = $rows['facet_counts']['facet_fields']['iColumnId'];
        if ($facet_data) {
            //有效栏目ID
            $valid_cols = array_keys(array_filter($facet_data));
            // 栏目字典
            $columnArr = $this->getColumns();
            if (!empty($valid_cols)) {
                $columnData[] = array(
                    'col' => 'all',
                    'name' => '全部内容',
                );
                foreach ($valid_cols as $col) {
                    // 去除大栏目
                    if ($col == 1 || $col == 2) {
                        continue;
                    }
                    $columnData[] = array(
                        'col' => $col,
                        'name' => $columnArr[$col],
                    );
                }
            }
        }
        return $columnData;
    }

    // 获取疫情专题的条件
    private function getEpidemicConditions($columnId)
    {
        $conditions = array();
        if ($columnId != 'all') {
            $conditions['iColumnId'] = intval($columnId);
        } else {
            $conditions['iColumnId'] = [1, 2];
        }
        // 疫情专题
        $conditions['iEpidemicTopic'] = 1;
        return $conditions;
    }

    // 获取栏目
    public function getColumns($columns = array(1, 2))
    {
        $this->load->driver('cache');
        $mem_key = 'TOPIC_EPIDEMIC_COLUMN';
        $data = $this->cache->memcached->get($mem_key);
        if (empty($data)) {
            $columnDict = GetCategory::getColumns($columns);
            $columnArr = array_column($columnDict, 'col');
            if ($columnArr) {
                $data = array();
                foreach ($columnArr as $items) {
                    foreach ($items as $val) {
                        $data[$val['iColumnId']] = $val['sName'];
                    }
                }
            }
            $this->cache->memcached->save($mem_key, $data, 3600);
        }
        return $data;
    }
}
