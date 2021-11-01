<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Styles栏目 专用类
 * 次栏目所有数据来源：
 * fashion:product_perform,product_marketphoto_item,product_ordermeeting,product_brand,product_style_graphic,product_style_graphic_china,product_streetitem
 * trends_new:mostrend_content
 * @property-read common_model $common_model
 */
class Styles_model extends POP_Model
{
    private $screenMemcache = 'FASHION_SCREENING_CONDITION_RELATE';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
    }

    /**
     * 区分成册(分组)和单张
     * @param int|string $columnId 栏目id
     * @param string $params
     * @return string solr查询的分组条件
     */
    public function getGroup($columnId, $params = '')
    {
        $group = '';
        $paramsArr = $this->common_model->parseParams($params, 1);

        //如果默认带行业的话 以单张形式展示列表；手动切换成册不处理
        $ind = $this->common_model->getIndustryByRequest($paramsArr);
        if (($paramsArr['ind'] || $ind) && $paramsArr['dis'] != 2) {
            return $group;
        }

        if (isset($paramsArr['dis']) && $paramsArr['dis'] == 1) {
            // dis_1 单张展示
            return $group;
        }

        // 全球实拍-批发市场: ds_1, 全球实拍-零售市场: ds_2, 全球实拍-展会图库: ds_3
        $ds = isset($paramsArr['ds']) && in_array($paramsArr['ds'], [1, 2, 3]) ? $paramsArr['ds'] : 2;

        $group = 'sPublicGroups';
        // 56-街拍图库, 57-潮流领袖, 54-全球实拍
        if (in_array($columnId, [56, 57]) || ($columnId == 54 && $ds != 2)) {
            $group = 'sCustomizationGroups';
        }

        return $group;
    }

    /**
     * [getGroupLists 获取每页分组展示的数据+总条数]
     * @param  string $params [url参数]
     * @param  integer $columnId [栏目id]
     * @param  array &$groupLists [根据条件查询出的结果]
     * @param  string $group [分组字段]
     * @param  integer $offset [偏移量]
     * @param  integer $limit [每页条数]
     * @param  array $powers [权限]
     * @return integer $totalCount  [根据条件查询出的总条数]
     */
    public function getGroupLists($params = '', $columnId = 0, &$groupLists, $group, $offset = 0, $limit = 10, $powers = [])
    {
        // 是否第一页
        $isFirstPage = $offset == 0;
        $this->benchmark->mark('getGroupLists');
        $paramsArr = [];
        if (!empty($params)) {
            $paramsArr = $this->common_model->parseParams($params);
        }
        $groupLists = [];

        // 条件
        $conditions = $this->getConditions($params, $columnId, $powers);
        // 排序
        $arSort = ['dCreateTime' => 'DESC', 'iWeight' => 'DESC', 'pri_id' => 'DESC'];

        $this->load->model('category_model');
        $return = [];
        $totalCount = $this->category_model->getGroupList('', $group, $conditions, $return, $arSort, $offset, $limit, $glimit = 1);
        foreach ($return as $key => $val) {
            $groupItem = [];
            $groupItem['count'] = $val['count'];
            if (!isset($val['list'][0])) {
                continue;
            }

            $item = $val['list'][0];
            $id = $item['pri_id'];
            $tableName = $item['tablename'];
            $data = OpPopFashionMerger::getProductData($id, $tableName);
            $info = $data[$id];
            if (empty($info)) {
                $groupItem['count'] = 0;
                $groupItem['groupId'] = $key;
                $groupLists[] = $groupItem;
                continue;
            }
            // 图片路径
            $imgPath = getImagePath($tableName, $info);
            $groupItem['id'] = $id;
            $groupItem['tableName'] = getProductTableName($tableName);
            $groupItem['cover'] = getFixedImgPath($imgPath['cover']);
            $groupItem['columnId'] = $item['iColumnId'][1];

            $createTime = isset($info['create_time']) ? $info['create_time'] : $info['dCreateTime'];
            $updateTime = isset($info['update_time']) ? $info['update_time'] : $info['dUpdateTime'];
            $groupItem['create_time'] = $createTime;
            $groupItem['update_time'] = $updateTime;
            $groupItem['date'] = date('Y-m-d', strtotime($createTime));
            $groupItem['isNew'] = date('Y-m-d', time()) == $groupItem['date'];
            $groupItem['groupId'] = $key;
            $_ids = explode('-', $key);
            $groupItem['iSeason'] = end($_ids);
            reset($_ids);
            $groupItem['sSeason'] = trim(GetCategory::getOtherFromIds($groupItem['iSeason'], ['sName']));
            $_paramsArr = $paramsArr;
            $_paramsArr['sea'] = $groupItem['iSeason'];
            // 全球实拍子栏目区分, 默认 2-零售市场
            $_ds = 2;
            if ($groupItem['columnId'] == 54) {
                $_ds = isset($paramsArr['ds']) && in_array($paramsArr['ds'], [1, 2, 3]) ? $paramsArr['ds'] : 2;
            }
            //4全部、50秀场提炼、52订货会、122设计师品牌、55名牌精选、123款式流行、54_2全球实拍-零售市场
            if (in_array($columnId, [4, 50, 52, 122, 55, 123])
                || ($groupItem['columnId'] == 54 && $_ds == 2)) {
                // 品牌季节
                $groupItem['iBrand'] = $_ids[0];
                $groupItem['sBrand'] = GetCategory::getBrandOtherFormId($_ids[0], 'en_cn_brand');
                $groupItem['cnBrand'] = GetCategory::getBrandOtherFormId($_ids[0], 'name');
                $groupItem['enBrand'] = GetCategory::getBrandOtherFormId($_ids[0], 'b_name');
                $groupItem['title'] = $groupItem['sSeason'] . ' ' . $groupItem['sBrand'];
                $_paramsArr['bra'] = $groupItem['iBrand'];
            } elseif ($columnId == 56) {
                // 56 街拍图库, 取地区季节
                $groupItem['iRegion'] = $_ids[1];
                $groupItem['sRegion'] = GetCategory::getFieldFromId($_ids[1]);;
                $groupItem['title'] = $groupItem['sRegion'] . ' ' . $groupItem['sSeason'];
                $_paramsArr['reg'] = $groupItem['iRegion'];
            } elseif ($columnId == 57) {
                // 57 潮流领袖, 取人物季节
                $groupItem['iStar'] = $_ids[1];
                $groupItem['sStar'] = $sStarLabelName = OpPopFashionMerger::getOldCategoryLabel($groupItem['iStar'])['cat_name'];
                $groupItem['title'] = $groupItem['sSeason'] . ' ' . $groupItem['sStar'];
                $groupItem['create_time'] = $info['dCreateTime'];
                $groupItem['update_time'] = $info['dUpdateTime'];
                $_paramsArr['sta'] = $groupItem['iStar'];
            } elseif ($columnId == 54 && $_ds == 1) {
                // 54_1 全球实拍-批发市场, 取市场季节
                $this->load->model('details_model');
                $groupItem['iMarket'] = $_ids[1];
                $groupItem['sMarket'] = $this->details_model->getMarketName($groupItem['iMarket']);
                $groupItem['title'] = $groupItem['sSeason'] . ' ' . $groupItem['sMarket'];
                $_paramsArr['mar'] = $groupItem['iMarket'];
            } elseif ($columnId == 54 && $_ds == 3) {
                // 54_3 全球实拍-展会图库, 取展会季节
                $this->load->model('details_model');
                $groupItem['iExhibition'] = $_ids[1];
                $groupItem['sExhibition'] = $this->details_model->getMarketName($groupItem['iExhibition']);
                $groupItem['title'] = $groupItem['sSeason'] . ' ' . $groupItem['sExhibition'];
                $_paramsArr['exh'] = $groupItem['iExhibition'];
            }
            unset($_paramsArr['page']);
            $params = $this->common_model->parseParams($_paramsArr, 2);
            $link = $this->common_model->getLink($columnId, $params);
            $groupItem['link'] = str_replace('/styles/', '/stylesub/', $link);

            $groupItem['groupId'] = $key;
            $groupLists[] = $groupItem;
        }

        // 成册列表页第一页
        if ($isFirstPage) {
            // 只在日期相同时排序 2018-10-24
            $tmpArr = [];
            $new_groupLists = [];
            foreach ($groupLists as $group) {
                $tmpArr[$group['date']][] = $group;
            }
            foreach ($tmpArr as $_groupLists) {
                // 按包含款式张数，由大到小排序 2018-09-06
                usort($_groupLists, function ($a, $b) {
                    return $b['count'] - $a['count'];
                });
                // 品牌为空的向后移
                $tmp_1 = $tmp_2 = [];
                foreach ($_groupLists as $item) {
                    if ($item['iBrand']) {
                        $tmp_1[] = $item;
                    } else {
                        $tmp_2[] = $item;
                    }
                }
                $new_groupLists = array_merge($new_groupLists, $tmp_1, $tmp_2);
            }

            $groupLists = $new_groupLists;

        }

        $this->benchmark->mark('getGroupListsEnd');
        return $totalCount;
    }


    /**
     * 获取款式前台页面所需要的全部数据 并缓存
     * 详情数据，列表数据，分组列表
     *
     * @param $id
     * @param $table_name
     * @param $column_id
     * @return array
     */
    public function getStyleData($id, $table_name, $column_id)
    {
        if (!$id || !$table_name) return [];

        $data = OpPopFashionMerger::getProductData($id, $table_name);
        $data = $data[$id];

        $res = [];
        $res['id'] = $id;
        $res['table'] = getProductTableName($table_name);// 假表名
        $res['col'] = $column_id;
        $res['table_name'] = $table_name;// 真表名

        $imgPath = getImagePath($table_name, $data);
        $res['cover'] = getFixedImgPath($imgPath['cover']);
        $res['create_time'] = $data['create_time'];


        return $res;
    }

    /**
     * ajax列表数据
     * @param string $params
     * @param int|string $columnId
     * @param mixed $powers
     * @param bool $isAjax
     * @return array array(数据列表$list, 其他信息$info)
     */
    public function ajaxList($params, $columnId, $powers, $isAjax = true)
    {
        $this->benchmark->mark('ajaxGetList');

        $paramsArr = $this->common_model->parseParams($params, 1);
        $page = $this->common_model->getPage($paramsArr); // 当前页
        $group = $this->getGroup($columnId, $params); // 获取展示形式: 成册/单张
        $lists = [];
        if ($group) {
            // 分组展示数据
            $pageSize = getRequestPageSize(20);

            $limit = $pageSize;
            $offset = ($page - 1) * $pageSize;// 偏移量
            $totalCount = $this->getGroupLists($params, $columnId, $lists, $group, $offset, $limit, $powers);
        } else {
            // 单张展示数据
            $pageSize = getRequestPageSize(30);

            $limit = $pageSize;
            $offset = ($page - 1) * $pageSize;// 偏移量
            $totalCount = $this->getStyleLists($params, $columnId, $lists, $offset, $limit, $powers);
        }

        // 判断是否要显示水印或遮罩
        $ret = isShowWatermark($paramsArr, ['isStyle' => true, 'isAjax' => true]);
        $info = [
            'totalCount' => $totalCount,
            'page' => $page,
            'pageSize' => $pageSize,
            'isGroup' => !!$group,
            'powers' => $powers,
            'showMask' => $ret['showMask'], // 是否显示遮罩
            'showWatermark' => $ret['showWatermark'], // 是否显示水印
            'withConditions' => $ret['withConditions'] // 是否有筛选条件
        ];

        if ($isAjax) {
            // 根据选择条件的品牌ID获取其主品牌和副线品牌的相关信息
            $aRelateBrandsInfo = array();
            if (isset($paramsArr['bra']) && $paramsArr['bra']) {
                $aRelateBrandsInfo = $this->getRelateBrandsInfoById($paramsArr['bra']);
            }
            $info['aRelateBrandsInfo'] = $aRelateBrandsInfo;
        }

        $this->benchmark->mark('ajaxGetListEnd');

        if ($isAjax) {
            $jo = $this->getJsonOutputObj();
            $jo->code(0)->data($lists)->info($info)->msg('ok')->out();
        }
        return [$lists, $info];
    }

    /**
     * ajax二级列表数据
     * @param string $params
     * @param int|string $columnId
     * @param mixed $powers
     * @param int $pageSize
     * @param bool $isAjax
     * @return array array(数据列表$list, 其他信息$info)
     */
    public function ajaxSubList($params, $columnId, $powers, $pageSize = 0, $isAjax = true)
    {
        $this->benchmark->mark('ajaxGetList');
        $jo = $this->getJsonOutputObj();

        $paramsArr = $this->common_model->parseParams($params, 1);
        $page = $this->common_model->getPage($paramsArr); // 当前页
        $time = isset($_REQUEST['time']) ? intval($_REQUEST['time']) : 1;
        $time = max($time, 1);
        $pageShow = $page; // 前台显示的page
        // $page 为前端显示页码, 同一页最多可以请求4次分批获取数, 这里换算成后台取数据的真分页page
        $page = ($page - 1) * 4 + $time;

        $lists = [];

        // 单张展示数据
        $pageSize = $pageSize ? $pageSize : 96;// 24*4

        $limit = $pageSize;
        $offset = ($page - 1) * $pageSize;// 偏移量
        $totalCount = $this->getStyleLists($params, $columnId, $lists, $offset, $limit, $powers);

        // 判断是否要显示水印或遮罩
        $ret = isShowWatermark($paramsArr, ['isAjax' => true]);
        $info = [
            'totalCount' => $totalCount,
            'page' => $pageShow,
            'pageSize' => $pageSize,
            'isGroup' => false,
            'powers' => $powers,
            'time' => $time,
            'showMask' => $ret['showMask'], // 是否显示遮罩
            'showWatermark' => $ret['showWatermark'] // 是否显示水印
        ];

        $this->benchmark->mark('ajaxGetListEnd');

        if ($isAjax) {
            $jo->code(0)->data($lists)->info($info)->msg('ok')->out();
        }
        return [$lists, $info];
    }

    /**
     * [getStyleLists 获取款式库每页单张展示的数据+总条数]
     * @param  string $params [url参数]
     * @param  integer $columnId [栏目id]
     * @param array $lists
     * @param  integer $offset [偏移量]
     * @param  integer $limit [每页条数]
     * @param array $powers
     * @return bool [array]   &$lists     [根据条件查询出的结果]
     */
    public function getStyleLists($params = '', $columnId, &$lists, $offset = 0, $limit = 10, $powers = array())
    {
        $this->benchmark->mark('stylesModelGetStyleLists');

        $lists = [];
        $columnPid = GetCategory::getOtherFromColId($columnId);
        if ($columnPid == 0) {
            $columnPid = $columnId;
        }
        // 条件
        $conditions = $this->getConditions($params, $columnId, $powers);
        // 排序
        $arSort = $this->common_model->getSort($params, $powers, $columnPid);

        $result = array();
        //列表 iFirstColorFirstLevel 改取超过30% 当前色所有
        if (isset($conditions['iFirstColorFirstLevel'])) {
            $conditions['aAutoColors'] = $conditions['iFirstColorFirstLevel'];
            unset($conditions['iFirstColorFirstLevel']);
        } elseif (isset($conditions['iFirstColorSecondLevel'])) {
            $conditions['aAutoColors'] = $conditions['iFirstColorSecondLevel'];
            unset($conditions['iFirstColorSecondLevel']);
        }

        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $arSort);
        $collectStatusList = $this->getListDataCollectStatus($result);

        $this->benchmark->mark('listDataForeach');
        $this->load->model('details_model');
        if (!empty($result)) {
            foreach ($result as $key => $val) {
                $info = [];

                $id = $val['pri_id'];
                $tableName = $val['tablename'];

                $productData = OpPopFashionMerger::getProductData($id, $tableName);
                $productData = $productData[$id];

                $createTime = isset($productData['create_time']) ? $productData['create_time'] : $productData['dCreateTime'];
                // $info['origin_data'] = $productData;
                $info['id'] = $id;
                $info['col'] = $col = array_pop($val['iColumnId']);
                $info['table'] = getProductTableName($tableName);
                $imgPath = getImagePath($tableName, $productData);// 图片路径
                $info['cover'] = getFixedImgPath($imgPath['cover']);
                $info['create_time'] = $createTime;
                $info['date'] = date('Y-m-d', strtotime($createTime));
                $info['isNew'] = date('Y-m-d', time()) == $info['date'];
                $info['offset'] = $offset++;
                $info['total'] = $totalCount;
                $info['iCollectStatus'] = array_search($val['pop_id'], $collectStatusList) === false ? 0 : 1; // 是否收藏
                $info['labels'] = $this->details_model->getLabelInfo($tableName, $id, $params, $columnId, 'list', $col);

                // 秀场提炼的颜色占比
                if ($columnId == 50 && $col == 50) {
                    $info['colorProportion'] = !empty($productData['sColorDetails']) ? json_decode($productData['sColorDetails'], true) : [];
                }

                $lists[] = $info;
            }
        }
        $this->benchmark->mark('listDataForeachEnd');

        $this->benchmark->mark('stylesModelGetStyleListsEnd');

        return $totalCount;
    }

    /**
     * [getConditions 获取solr查询的condition条件]
     * @param string $params
     * @param  integer $columnId [栏目id]
     * @param array $powers
     * @param bool $ignore 忽略指定查询条件
     * @return array [array]  $conditions
     */
    public function getConditions($params = '', $columnId, $powers = [], $ignore = false)
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
        $conditions = ['iColumnId' => $columnId,];
        // 性别
        if (!$ignore) {
            $gender = $this->common_model->getGenderByRequest($params);
            $this->common_model->childGender($gender, $conditions);
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

        // 显示款式图或是手稿图----仅在订货会栏目
        if ($columnId == 52) {
            $conditions['iStyleOrManuscript'] = isset($paramsArr['prop']) && !empty($paramsArr['prop']) ? $paramsArr['prop'] : 1;
        }
        // 数据来源，用于全球实拍分类
        if ($columnId == 54) {
            $conditions['iDataSource'] = isset($paramsArr['ds']) && !empty($paramsArr['ds']) ? $paramsArr['ds'] : 2;
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
        // 地区
        if (isset($paramsArr['reg'])) {
            $region = intval($paramsArr['reg']);
            if ($columnId == 54 && ($region == 10001 || $region == 10002)) {
                $this->load->model('category_model');
                $typeArr = $this->category_model->getRealCategory(['iRegion'], $columnId, $conditions);
                $regionIds = array_keys($typeArr['iRegion'][$region]['attrs']);
                $reg = implode(' OR ', $regionIds);
                $conditions['other'][] = "iRegion: ({$reg})";
            } elseif ($region == 0) {
                $conditions['other'][] = "-iRegion:* AND -iArea:* AND -iContinent:* AND -iCountry:*";
            } else {
                $conditions['other'][] = "(iRegion:{$region} OR iArea:{$region} OR iContinent:{$region} OR  iCountry:{$region})";
            }
        }
        // 品牌
        if (isset($paramsArr['bra'])) {
            $_id = intval($paramsArr['bra']);
            if ($_id) {
                $conditions['iBrand'] = intval($paramsArr['bra']);
            } else {
                $conditions['other'][] = '-iBrand:{0 TO *}';
            }
        }
        // 展会名称
        if (isset($paramsArr['exh'])) {
            if ($paramsArr['exh'] == 0) {
                $conditions['other'][] = '-sExhibitionName:{0 TO *}';
            } else {
                $conditions['sExhibitionName'] = $paramsArr['exh'];
            }
        }
        // 季节
        if (isset($paramsArr['sea']) && !$ignore) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['sea'];
        }
        // 品类
        if (isset($paramsArr['cat']) && !$ignore) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['cat'];
        }
        // 色系
        if (isset($paramsArr['aco'])) {
            //判断当前色系是一级还是二级，如果是一级根据一级色系查询，如果是二级则根据二级色系查询
            $color = $this->getColorDict($paramsArr['aco']);
            if (!empty($color['iPid'])) { //二级色系
                $conditions['iFirstColorSecondLevel'] = $paramsArr['aco'];
            } else { //一级色系
                $conditions['iFirstColorFirstLevel'] = $paramsArr['aco'];
            }
        }
        // 年龄层
        if (isset($paramsArr['age'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['age'];
        }
        // 品牌定位
        if (isset($paramsArr['bpos'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['bpos'];
        }
        // 地域风格
        if (isset($paramsArr['regs'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['regs'];
        }
        // 穿着
        if (isset($paramsArr['wea'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['wea'];
        }
        // 性格风格
        if (isset($paramsArr['cha'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['cha'];
        }
        // 时装周专题
        if (isset($paramsArr['fas'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['fas'];
        }
        // 市场类型
        if (isset($paramsArr['mar'])) {
            if ($paramsArr['mar'] == 0) {
                $conditions['other'][] = '-aMarketType:{0 TO *}';
            } else {
                $conditions['aMarketType'] = $paramsArr['mar'];
            }
        }
        // 街拍类型
        if (isset($paramsArr['str'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['str'];
        }
        // 热门元素
        if (isset($paramsArr['hot'])) {
            $labelsMap = $this->getLabels(true);
            $conditions['other'][] = 'combine:(' . $labelsMap[$paramsArr['hot']] . ')';
        }
        // 人名 （原：明星/达人）
        if (isset($paramsArr['sta'])) {
            if ($paramsArr['sta'] == 0) {
                $conditions['other'][] = '-sStarLabel:{0 TO *}';
            } else {
                $conditions['sStarLabel'] = $paramsArr['sta'];
            }
        }
        // 风格
        if (isset($paramsArr['man'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['man'];
        }
        // 廓形
        if (isset($paramsArr['shap'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['shap'];
        }
        // 细节
        if (isset($paramsArr['spe'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['spe'];
        }
        // 工艺
        if (isset($paramsArr['tech'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['tech'];
        }
        // 图案
        if (isset($paramsArr['pat'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['pat'];
        }
        // 面料
        if (isset($paramsArr['fab'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['fab'];
        }
        // 辅料
        if (isset($paramsArr['acc'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['acc'];
        }
        // 明星/ins(原：潮流领袖)
        if (isset($paramsArr['starin'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['starin'];
        }
        // 关键字检索
        $keyword = $this->common_model->getKeyword($params, $powers);
        if (strpos($keyword, '?key=') !== false) {
            // $keyword = ltrim($keyword, '?key=');
            $keyword = str_replace('?key=', '', $keyword);
        }
        if ($keyword) {
            $conditions['other'][] = 'combine:(' . $keyword . ')';
        }
        return $conditions;
    }

    /*--------------------------饼图 start-----------------------------------*/

    public function pieData($params = '', $columnId, $powers = [])
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
        $colorBack = $categoryBack = '';
        $tips = $this->common_model->getTips($columnId, $params);
        if (isset($paramsArr['aco']) && !empty($paramsArr['aco'])) {
            $colorBack = $tips['aco']['backLink'];
        }
        if (isset($paramsArr['cat']) && !empty($paramsArr['cat'])) {
            $categoryBack = $tips['cat']['backLink'];
        }

        $conditions = $this->getConditions($params, $columnId, $powers);

        $keywords = $this->common_model->getKeyword('', $powers);

        $dataAnalysis = $this->getColorAnalysisData($conditions, $columnId);
        $dataCategory = $this->getCategoryPieData($params, $columnId, $conditions, $keywords);

        return ['analysis' => $dataAnalysis, 'category' => $dataCategory,
            'colorBack' => $colorBack, 'categoryBack' => $categoryBack];
    }

    public function getColorAnalysisData($conditions, $columnId)
    {
        $groups = [];
        $groups['group'] = 'true';
        $groups['group.ngroups'] = 'true';

        if (isset($conditions['iFirstColorSecondLevel']) || isset($conditions['iFirstColorFirstLevel'])) {
            $groups['group.field'] = 'iFirstColorSecondLevel';
        } else {
            $groups['group.field'] = 'iFirstColorFirstLevel';
        }
        $arSort = $resAnalysis = [];
        if ($columnId == 82) {
            $conditions['other'] = "-(iFirstColorSecondLevel:149)";
        }
        //色彩分析
        POPSearch::wrapQueryPopFashionMerger('', $conditions, $resAnalysis, 0, 30, $arSort, $groups);
        $resAnalysis = POPSearch::getGroupData($resAnalysis);
        $colors = $this->getColorDict();
        $dataAnalysis = [];
        foreach ($resAnalysis[$groups['group.field']]['groups'] as $colorid => $count) {
            if (isset($colors[$colorid])) {
                $tmp = [];
                $tmp['id'] = $colorid;
                $tmp['name'] = $colors[$colorid]['sName'];
                $tmp['sColorNumber'] = $colors[$colorid]['sColorNumber'];
                $tmp['value'] = $count;
                $tmp['itemStyle']['normal']['color'] = $colors[$colorid]['sColor'];
                $tmp['label']['normal']['textStyle']['color'] = "#000";
                $tmp['labelLine']['normal']['lineStyle']['color'] = "#000";
                $tmp['iSort'] = $colors[$colorid]['iSort'];
                $dataAnalysis[] = $tmp;
            }
        }
        if (!empty($dataAnalysis)) {
            $dataAnalysis = twoDimensionSort($dataAnalysis, 'iSort');
            $dataAnalysis = array_values($dataAnalysis);
        }

        return json_encode($dataAnalysis);
    }

    //通过RGB获取HTML色号
    private function rgbToColor($R, $G = -1, $B = -1)
    {
        if (is_array($R) && sizeof($R) == 3) {
            list($R, $G, $B) = $R;
        }
        $R = intval($R);
        $G = intval($G);
        $B = intval($B);
        $R = dechex($R < 0 ? 0 : ($R > 255 ? 255 : $R));
        $G = dechex($G < 0 ? 0 : ($G > 255 ? 255 : $G));
        $B = dechex($B < 0 ? 0 : ($B > 255 ? 255 : $B));
        $COLOR = (strlen($R) < 2 ? '0' : '') . $R;
        $COLOR .= (strlen($G) < 2 ? '0' : '') . $G;
        $COLOR .= (strlen($B) < 2 ? '0' : '') . $B;
        return '#' . $COLOR;
    }

    public function getCategoryPieData($params, $columnId, $conditions, $keywords)
    {

        $this->benchmark->mark('Analysis');
        if (!empty($params)) {
            if (is_array($params)) {
                $paramsArr = $params;
            } else {
                $paramsArr = $this->common_model->parseParams($params);
            }
        } else {
            $paramsArr = [];
        }

        //根据查询条件获取符合条件的单品和品名
        $this->load->model('category_model');
        $this->load->driver('cache');

        $conditionTmp = $conditions;
        $conditionStr = '';
        unset($conditionTmp['dCreateTime']);
        if (isset($paramsArr['tim'])) {
            $conditionTmp['tim'] = $paramsArr['tim'];
        }
        ksort($conditionTmp);
        foreach ($conditionTmp as $key => $val) {
            if (is_array($val)) {
                asort($val);
                $conditionTmp[$key] = implode('-', $val);
            }
            $conditionStr .= $key . '_' . $conditionTmp[$key] . '-';
        }
        $mcKey = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_' . md5(trim($conditionStr, '-')) . '_CategoryPie';
        $arSort = $resCategory = $dataCategory = [];
        //单品指南
        $dataCategory = $this->cache->memcached->get($mcKey);
        if (empty($dataCategory) || $this->input->get_post('refresh')) {
            $_conditions = $conditions;
            if (empty($paramsArr['tim']) && empty($paramsArr['sea'])) {
                $endTime = date("Y-m-d") . 'T' . date("H:i:s", strtotime("-2 hour")) . 'Z';
                $_conditions['dCreateTime'] = '[' . date('Y-m-d\T00:00:00\Z', strtotime('-1 years')) . ' TO ' . $endTime . ']';
            }
            $realCategorys = $this->category_model->getCategoryAndSubCategory($columnId, $_conditions, $keywords, 75, true);
            $dataCategory = [];
            if (isset($paramsArr['cat']) && !empty($paramsArr['cat'])) {
                //判断是单品还是品名
                $cateInfo = GetCategory::getOtherFromIds($paramsArr['cat'], ['iType', 'sName'], 'array');
                if ($cateInfo['iType'][0] == 3) { //单品 获取品名数据
                    if (!empty($realCategorys)) {
                        foreach ($realCategorys[$paramsArr['cat']]['sSubCategory'] as $id => $category) {
                            $conditionsTmp = $conditions;
                            $conditionsTmp['other'][] = 'aLabelIds:' . $id;

                            $count = POPSearch::wrapQueryPopFashionMerger('', $conditionsTmp, $resCategory, 0, 20, $arSort);
                            $_categorys = [];
                            $_categorys['id'] = $id;
                            $_categorys['name'] = $category;
                            $_categorys['value'] = $count;

                            $dataCategory[] = $_categorys;
                        }
                    }
                } elseif ($cateInfo['iType'][0] == 4) { //品名 不做操作
                    $_categorys = $this->getCategoryCount($paramsArr['cat'], $cateInfo['sName'][0], $conditions, $arSort);
                    $dataCategory[] = $_categorys;
                }
            } else {
                if (!empty($realCategorys)) {
                    foreach ($realCategorys as $categoryId => $category) {
                        $_categorys = $this->getCategoryCount($categoryId, $category['name'], $conditions, $arSort);
                        $dataCategory[] = $_categorys;
                    }
                }
            }
            $this->cache->memcached->save($mcKey, $dataCategory);
        }
        $this->benchmark->mark('AnalysisEnd');
        return json_encode($dataCategory);
    }

    public function getCategoryCount($categoryId, $categoryName, $conditions, $arSort)
    {
        $conditionsTmp = $conditions;
        $conditionsTmp['other'][] = 'aLabelIds:' . $categoryId;

        $res = $_categorys = [];
        $count = POPSearch::wrapQueryPopFashionMerger('', $conditionsTmp, $res, 0, 20, $arSort);
        $_categorys['id'] = $categoryId;
        $_categorys['name'] = $categoryName;
        $_categorys['value'] = $count;
        return $_categorys;
    }

    public function getColorDict($colorid = 0, $format = '')
    {

        $this->load->driver('cache');
        $memKey = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_0915_colorDict';
        $res = $this->cache->memcached->get($memKey);

        if (empty($res) || $this->input->get_post('refresh')) {
            $res = array();
            //查询颜色字典表做memcache缓存
            $sql = "SELECT * FROM `fashion`.`t_dict_color` WHERE `iStatus`=1";
            $colors = $this->query($sql);

            foreach ($colors as $color) {
                $color['sColor'] = $this->rgbToColor($color['iRed'], $color['iGreen'], $color['iBlue']);
                $res[$color['id']] = $color;
            }

            $this->cache->memcached->save($memKey, $res, 86400);
        }

        if (!empty($colorid)) {
            $resFormat = [];
            $resFormat['sName'] = $res[$colorid]['sName'];
            $resFormat['sAlias'] = $res[$colorid]['sColor'];
            if (!empty($res[$res[$colorid]['iPid']])) {
                $resFormat['PsName'] = $res[$res[$colorid]['iPid']]['sName'];
                $resFormat['PsAlias'] = $res[$res[$colorid]['iPid']]['sColor'];
                $resFormat['Pid'] = $res[$colorid]['iPid'];
            }
        }

        return !empty($colorid) ? ($format == 'sAssortColor') ? $resFormat : $res[$colorid] : $res;
    }

    /*--------------------------饼图 end-------------------------------------*/

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
        $_conditions = $conditions;
        if (empty($paramTmp['tim']) && empty($paramTmp['sea'])) {
            $endTime = date("Y-m-d") . 'T' . date("H:i:s", strtotime("-2 hour")) . 'Z';
            $_conditions['dCreateTime'] = '[' . date('Y-m-d\T00:00:00\Z', strtotime('-1 years')) . ' TO ' . $endTime . ']';
        }
        $realCategorys = $this->category_model->getCategoryAndSubCategory($columnId, $_conditions, $keys);
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

        $this->benchmark->mark('getCategorysEnd');
        return $categorys;
    }

    // 获取 明星/ins 标签 (没有 group 与 facet)
    public function getStarInsItems($columnId, $params = '', $powers = [])
    {
        $paramTmp = $this->common_model->parseParams($params);
        if (!empty($paramTmp['starin'])) {
            return [];
        }

        // 获取 明星/ins 字典
        $this->load->model(['category_model']);
        $aStarInsDict = $this->category_model->getAll('iStarIns');

        $aStarInsData = $_aStarInsData = array();
        if (!empty($aStarInsDict)) {
            foreach ($aStarInsDict as $id => $starIns) {
                $_aStarInsData = ['name' => $starIns, 'link' => $this->common_model->getLink($columnId, $params, 'starin', $id, true, 'anchor')];
                $aStarInsData[] = $_aStarInsData;
            }
        }
        return $aStarInsData;
    }

    /**
     * [getSelectItems 获取指定栏目有数据的所有查询条件]
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
        $paramsArr = $paramTmp;
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

        //除季节、市场类型、展会名称、人名（明星达人）、明星/ins，其他标签取一年内的
        $_selectItem1 = $_selectItem2 = [];
        foreach ($selectItem as $val) {
            if (in_array($val, ['iSeason', 'sMarketType', 'sExhibitionName', 'sStarLabel'])) {
                $_selectItem1[] = $val;
            } else {
                $_selectItem2[] = $val;
            }
        }
        $_conditions = $conditions;
        if (empty($paramsArr['tim']) && empty($paramsArr['sea']) ) {
            $endTime = date("Y-m-d") . 'T' . date("H:i:s", strtotime("-2 hour")) . 'Z';
            $_conditions['dCreateTime'] = '[' . date('Y-m-d\T00:00:00\Z', strtotime('-1 years')) . ' TO ' . $endTime . ']';
        }
        $items1 = $this->category_model->getRealCategory($_selectItem1, $columnId, $conditions, $keys);
        $items2 = $this->category_model->getRealCategory($_selectItem2, $columnId, $_conditions, $keys);
        $items = array_merge($items1, $items2);

        foreach ($items as $key => $val) {
            if (empty($val)) {
                $selectItems[$key] = [];
                continue;
            }
            if (!in_array($key, ['iSeason', 'iRegion'])) {
                // 除了特定的几个, 其他条件点击后均为单张
                $params = $this->dealParamsSingle($params);
            }
            switch ($key) {
                // 季节
                case 'iSeason':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'sea');
                    break;
                // 展会名称
                case 'sExhibitionName':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'exh', true);
                    break;
                // 年龄层
                case 'sAgeLayer':
                    if (!empty($paramTmp['gen']) && $paramTmp['gen'] == 5) {
                        foreach ($val as $k => $v) {
                            $AgeLevelForChild = [395, 394, 393, 11302];//童装年龄层
                            if (!in_array($k, $AgeLevelForChild)) {
                                unset($val[$k]);
                            }
                        }
                    }
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'age');
                    break;
                // 品牌定位
                case 'sMarketHotPosition':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'bpos');
                    break;
                // 地域风格
                case 'sRegionalStyle':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'regs');
                    break;
                // 穿着
                case 'sWearing':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'wea');
                    break;
                // 性格风格
                case 'sCharacterStyle':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'cha');
                    break;
                // 时装周类型
                case 'sFashionWeek':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'fas', true);
                    break;
                // 市场类型
                case 'sMarketType':
                    if ($columnId == 54) {//款式库--全球实拍--批发为单层
                        $selectItems[$key] = $this->getItems($columnId, $params, $val, 'mar');
                    } else {
                        $selectItems[$key] = $this->getItems($columnId, $params, $val, 'mar', true);
                    }
                    break;
                // 街拍类型
                case 'sStreetBeatType':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'str');
                    break;
                // 城市
                case 'iRegion':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'reg', true);
                    break;
                // 风格
                case 'sManner':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'man');
                    break;
                // 廓形
                case 'sShape':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'shap');
                    break;
                // 细节
                case 'sSpecifics':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'spe');
                    break;
                // 工艺
                case 'sTechnologys':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'tech');
                    break;
                // 图案
                case 'sPattern':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'pat');
                    break;
                // 面料
                case 'sFabric':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'fab');
                    break;
                // 辅料
                case 'sAccessory':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'acc');
                    break;
                default:
                    $selectItems[$key] = $val;
                    break;
            }
        }
        $this->benchmark->mark('groupbySelectEnd');
        return $selectItems;
    }

    /**
     * 处理params为单张
     * @param string|array $params
     * @param bool $array 是否返回参数数组, true-数组, false-字符串
     * @return string|array 默认返回字符串
     */
    public function dealParamsSingle($params, $array = false)
    {
        $paramArr = is_array($params) ? $params : $this->common_model->parseParams($params);
        $paramArr = $paramArr ? $paramArr : [];
        // 单张
        if (isset($paramArr['dis']) && $paramArr['dis'] == 2) {
            $paramArr['dis'] = 2;
        } else {
            $paramArr['dis'] = 1;
        }
        return $array ? $paramArr : $this->common_model->parseParams($paramArr, 2);
    }

    /**
     * [getBrandIdsById   通过品牌ID获取其主品牌和副线品牌的相关信息]
     * @param  integer $id [品牌id]
     * @return array   $return [主品牌和副线品牌的id和名称]
     */
    public function getRelateBrandsInfoById($id)
    {
        if (!$id) return;
        $sql = 'SELECT `sRelateBrands` FROM `pop136`.`brand_library` WHERE id = ' . intval($id);
        $res = $this->query($sql);
        // 通过品牌id查找其副线品牌
        $aRelateBrands = $aRelateBrandsInfo = [];
        if ($res) {
            $aRelateBrands = explode(',', $res[0]['sRelateBrands']);
        }
        // 通过品牌id去solr查找其主品牌
        $conditions['aRelateBrands'] = intval($id);
        $conditions['iColumnId'] = 5;
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, 0, 1000);
        if ($result) {
            foreach ($result as $key => $val) {
                $aRelateBrands[] = $val['pri_id'];
            }
        }
        if ($aRelateBrands) {
            $aRelateBrands = array_unique($aRelateBrands);
            foreach ($aRelateBrands as $val) {
                $val = intval($val);
                if ($val) {
                    $brandInfo = OpPopFashionMerger::getBrandData($val);
                    $en_cn_name = isset($brandInfo['b_name']) && $brandInfo['b_name'] && $brandInfo['b_name'] != $brandInfo['name'] ? $brandInfo['name'] . '/' . $brandInfo['b_name'] : $brandInfo['name'];
                    $aRelateBrandsInfo[] = ['id' => $val, 'name' => $en_cn_name];
                }
            }
        }
        return $aRelateBrandsInfo;
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
        // 参数特殊处理（参数中有且仅有prop|ds或没有参数） 52栏目默认prop=1，54栏目默认ds=2 此时TDK特殊处理
        $__TKD_k = false;
        if (!$params || (count($paramsArr) == 1 && (isset($paramsArr['prop']) || isset($paramsArr['ds'])))) {
            $__TKD_k = true;
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
        //色系
        $aco = $this->common_model->getDefaultParams('aco', $params, $columnId);
        $aco = isset($aco['sName']) ? $aco['sName'] : "";
        //风格
        $man = $this->common_model->getDefaultParams('man', $params, $columnId);
        //元素
        $shap = $this->common_model->getDefaultParams('shap', $params, $columnId);
        //细节
        $spe = $this->common_model->getDefaultParams('spe', $params, $columnId);
        //工艺
        $tech = $this->common_model->getDefaultParams('tech', $params, $columnId);
        //图案
        $pat = $this->common_model->getDefaultParams('pat', $params, $columnId);
        //面料
        $fab = $this->common_model->getDefaultParams('fab', $params, $columnId);
        //辅料
        $acc = $this->common_model->getDefaultParams('acc', $params, $columnId);
        //明星
        $sta = $this->common_model->getDefaultParams('sta', $params, $columnId);
        //栏目名称
        $colName = GetCategory::getOtherFromColId($columnId, 'sName');
        //测试获取时段信息
        $timArray = ['全部时段', '近7日', '近30日', '近半年'];
        $disArray = [1 => '按单张', 2 => '按成册'];
        $sorArray = [1 => "按时间最新", 2 => "按浏览最高", 3 => "按收藏最多"];
        $propArray = [1 => '显示款式图', 2 => '显示手稿图'];
        $dsArray = [1 => '批发市场', 2 => '零售市场', 3 => '展会图库'];

        $jtime = $timArray[$paramsArr['tim']];
        $dis = $disArray[$paramsArr['dis']];
        $sor = $sorArray[$paramsArr['sor']];
        $ds = $dsArray[$paramsArr['ds']];
        $prop = $propArray[$paramsArr['prop']];

        //替换分页字段为空
        $preg = '/page_\d*/';
        $paramsArray = explode('-', $params);
        if (count($paramsArray) == 1) {
            $paramsArray[0] = preg_replace($preg, "", $paramsArray[0]);
        }
        $params = implode('-', $paramsArray);
        if (empty($params) && empty($gen) && empty($ind) || $__TKD_k) {
            if ($columnId == 4) {
                $title = '男/女/童装款式-POP服装趋势网';
                $description = 'POP服装趋势网款式栏目提供最新、最前沿、最具权威的秀场提炼、订货会、设计师品牌、名牌精选、款式流行、全球实拍、潮流领袖和街拍图库图片，从服装款式、色彩、面料、印花图案等方面进行专业分析，为您提供有价值的资讯服务。';
            } else {
                $title = $colName . '_款式-POP服装趋势网';
                $description = 'POP服装趋势网款式的' . $colName . '栏目为您提供最新、最前沿的' . $colName . '图片资讯，为您及时提供与' . $colName . '相关的服装款式、色彩、面料、印花图案等方面资讯，为您提供有价值的资讯服务。';
            }
            $keyInfo = [
                4 => '秀场提炼,订货会,设计师品牌,名牌精选,款式流行,全球实拍,潮流领袖,街拍图库',
                50 => '秀场提炼,T台',
                52 => '订货会,订货会图片',
                122 => '设计师品牌,明星品牌',
                55 => '名牌精选,品牌精选',
                123 => '流行款式,流行趋势,服装款式',
                54 => '商场实拍,展会实拍,T台实拍,实拍图',
                57 => '潮流领袖,时尚潮流,星风尚',
                56 => '街拍图库,街拍搭配'
            ];
            $keywords = $keyInfo[$columnId];
        } else {
            $jh = array_filter([$gen, $ind, $bra, $cat, $subcat, $sea, $reg, $aco, $man, $shap, $jtime, $dis, $sor, $ds, $prop, $spe, $tech, $pat, $fab, $acc, $sta]);
            $t = implode('_', $jh) . '_';
            $k = implode(',', $jh) . ',';
            $d = implode('/', $jh);
            if ($columnId == 4) {
                $title = $t . '款式-POP服装趋势网';
                $description = 'POP趋势资讯网款式栏目为您提供最新、最前沿的' . $d . '图片资讯，为您及时提供与' . $d . '相关的服装款式、色彩、面料、印花图案等方面资讯，为您提供有价值的服装设计资讯服务。';
            } else {
                $title = $t . $colName . '-POP服装趋势网';
                $description = 'POP趋势资讯网款式的' . $colName . '栏目为您提供最新、最前沿的' . $d . '图片资讯，为您及时提供与' . $d . '相关的服装款式、色彩、面料、印花图案等方面资讯，为您提供有价值的服装设计资讯服务。';
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
            case '4':
            case '50':
            case '52':
            case '54':
            case '55':
            case '56':
            case '57':
            case '122':
            case '123':
            default:
                $columnPresentation = getCommonData('common_data', 'aColumnAbstract')[4];
                break;
        }
        return $columnPresentation;
    }

    public function saveBusiness($data)
    {
        $tableName = '`pop136`.`market_shoot_demand`';
        $this->db()->set($data)->insert($tableName);

        return $this->db()->affected_rows() ? true : false;
    }

    /**********************************************以下为私有方法***********************************************/

    /**
     * @param $columnId
     * @param $params
     * @param $arr
     * @param $type
     * @param bool $double
     * @return array
     */
    private function getItems($columnId, $params, $arr, $type, $double = false)
    {
        $ret = [];
        if ($double) {
            foreach ($arr as $Pid => $Pname) {
                $tempArr = [];
                $tempArr['name'] = $Pname['sName'];
                if (intval($Pid) > 0) {
                    $tempArr['link'] = $this->common_model->getLink($columnId, $params, $type, $Pid, true, 'anchor');
                }
                if (is_array($Pname['attrs'])) {
                    foreach ($Pname['attrs'] as $id => $name) {
                        $tempArr['attrs'][] = ['name' => $name['sName'], 'link' => $this->common_model->getLink($columnId, $params, $type, $id, true, 'anchor')];
                    }
                } else {
                    $tempArr['attrs'][] = ['name' => '----', 'link' => ''];
                }
                $ret[] = $tempArr;
            }
        } else {
            foreach ($arr as $id => $name) {
                $ret[] = ['name' => $name, 'link' => $this->common_model->getLink($columnId, $params, $type, $id, true, 'anchor')];
            }
        }
        return $ret;
    }

    // 默认返回原始数据，否则返回处理数据
    public function getLabels($handle = false)
    {
        $this->load->helper('common');
        $res = $labelsMap = getCommonData('labelsmap_data', 'labelsMap');

        if ($handle) {
            foreach ($labelsMap as $k => $v) {
                $res[$v['id']] = $v['name'];
            }
        }
        return $res;
    }

    /**
     * 获取筛选栏列表
     * @param $params
     * @param $columnId
     */
    public function getScreeningList($conditions, $powers, $arr)
    {
        $this->benchmark->mark('code_start');
        $this->load->driver('cache');

        $tableName = '`fashion`.`t_style_user_screening_items`';

        $data = $list = $field = [];
        //条件
        if ($conditions) {
            foreach ($conditions as $k => $v) {
                $field[] = $k . '=' . "'{$v}'";
            }
        }
        $fields = implode(' AND ', $field);

        //获取记录条数
        $data_sql = "select * from {$tableName} WHERE {$fields} order by `dCreateTime` desc";
        $result = $this->query($data_sql);
        $count = count($result);
        $arr = array_flip($arr);
        $arrKeys = array_keys($arr);
        $userId = getUserId();
        if ($count >= 1) {

            //获取列表数据
            $rs = [];
            foreach ($result as $key => $value) {
                $memcacheKey = $this->screenMemcache . $userId . '_' . $value['id'];
                $rs[$key] = $this->cache->memcached->get($memcacheKey);
                if (!empty($rs[$key])) {
                    continue;
                }
                $_rs = [];
                $rs[$key]['colName'] = $value['iColId'] == 4 ? '全部款式' : GetCategory::getOtherFromColId($value['iColId'], 'sName');
                $rs[$key]['filter_create_time'] = $value['dCreateTime'];//筛选条件创建时间
                $gender = GetCategory::getOtherFromIds($value['iGender'], ['sName']);
                $industry = GetCategory::getOtherFromIds($value['iIndustry'], ['sName']);
                if ($value['iGender']) {
                    if ($value['iGender'] == 5 && !empty($value['iSubGender'])) {
                        $gcen = GetCategory::getOtherFromIds($value['iSubGender'], ['sName']);
                        $_rs['iGender'] = $gcen;
                    } else {
                        $_rs['iGender'] = $gender;
                    }
                }
                if ($value['iIndustry']) {
                    $_rs['iIndustry'] = $industry;
                }
                if ($value['iColId'] == 52) {
                    if ($value['iStyleOrManuscript'] == 2) {//订货会
                        $_rs['iStyleOrManuscript'] = '手稿图';
                    } else {
                        $_rs['iStyleOrManuscript'] = '款式图';
                    }
                }
                if ($value['iColId'] == 54) {
                    if ($value['iGlobalPhotoShotTab'] == 1) {//全球实拍
                        $_rs['iGlobalPhotoShotTab'] = '批发市场';
                    } elseif ($value['iGlobalPhotoShotTab'] == 3) {
                        $_rs['iGlobalPhotoShotTab'] = '展会图库';
                    } else {
                        $_rs['iGlobalPhotoShotTab'] = '零售市场';
                    }
                }
                //其他筛选项
                $param = [];
                $value = array_filter($value);
                $value['iGender'] = isset($value['iGender']) ? $value['iGender'] : 0;
                $value['iIndustry'] = isset($value['iIndustry']) ? $value['iIndustry'] : 0;
                foreach ($value as $type => $_v) {
                    switch ($type) {
                        // 栏目id
                        case 'iColId':
                            $rs[$key][$type] = $_v;
                            break;
                        // 关键字
                        case 'sKeywords':
                            $_rs[$type] = $_v;
                            break;
                        // 品牌
                        case 'iBrand':
                            $brandInfo = OpPopFashionMerger::getBrandData(intval($_v));
                            $en_cn_name = isset($brandInfo['b_name']) && $brandInfo['b_name'] && $brandInfo['b_name'] != $brandInfo['name'] ? $brandInfo['name'] . '/' . $brandInfo['b_name'] : $brandInfo['name'];
                            $_rs[$type] = $en_cn_name ? $en_cn_name : '其他';
                            break;
                        // 地区
                        case 'iRegion':
                            if ($value['iRegion'] == 'other') {
                                $_rs[$type] = $_v;
                            } else {

                                // 款式库，零售市场，地区特殊处理
                                if ($value['iColId'] == 54 && $_v == 10001) {
                                    $_rs[$type] = '国内全部';
                                } elseif ($value['iColId'] == 54 && $_v == 10002) {
                                    $_rs[$type] = '国外全部';
                                } else {
                                    $_rs[$type] = GetCategory::getFieldFromId(intval($_v));
                                }
                            }
                            break;
                        case 'iSeason'://季节
                            $_rs[$type] = ltrim(GetCategory::getOtherFromIds(intval($_v), ["sName"]));
                            break;
                        case 'iCategory'://单品or品名
                        case 'sAgeLayer': // 年龄层
                        case 'sMarketHotPosition': // 品牌定位
                        case 'sRegionStyle': // 地域风格
                        case 'sDress': // 穿着
                        case 'sCharacterStyle': // 性格风格
                        case 'sFashionWeek': // 时装周专题
                        case 'iStreetBeatType': // 街拍类型
                        case 'sManner': // 风格
                        case 'sShape': // 廓形
                        case 'sSpecifics': // 细节
                        case 'sTechnology': // 工艺
                        case 'sPattern': // 图案
                        case 'sFabric': // 面料
                        case 'sAccessory': // 辅料
                            $_rs[$type] = ltrim(GetCategory::getOtherFromIds(intval($_v), ["sName"]));
                            break;
                        case 'sMarketType': // 市场类型
                            if ($value['iColId'] == 54) {
                                $this->load->model('category_model');
                                $typeArr = $this->category_model->getAll('sMarketType');
                                $_rs[$type] = $typeArr[$_v];
                            } else {
                                $_rs[$type] = ltrim(GetCategory::getOtherFromIds(intval($_v), ["sName"]));
                            }
                            break;
                        // 展会名称
                        case 'sExhibitionName':
                            $this->load->model("category_model");
                            $typeArr = $this->category_model->getAll('sExhibitionName');
                            foreach ($typeArr as $val) {
                                if (is_array($val['attrs']) && in_array($_v, array_keys($val['attrs']))) {
                                    $_rs[$type] = $val['attrs'][$_v]['sName'];
                                    break 2;
                                }
                            }

                        // 色系
                        case 'sAssortColor':
                            $typeArr = $this->styles_model->getColorDict($_v, 'sAssortColor');
                            $_rs[$type] = $typeArr['sName'];
                            break;
                        // 明星/达人
                        case 'sStarLabel':
                            $this->load->model('category_model');
                            $typeArr = $this->category_model->getAll('sStarLabel');
                            $_rs[$type] = $typeArr[$_v];
                            break;
                        //保存条件跳转URL
                        case 'sScreenUrl':
                            $rs[$key][$type] = $_v;
                            break;
                        case 'id':
                            $rs[$key][$type] = $_v;
                            break;
                    }

                    //设置参数
                    if (in_array($type, $arrKeys)) {
                        if ($arr[$type] != 'key') {
                            $param[] = $arr[$type] . '_' . $_v;
                        }
                    }
                }
                $param[] = 'tim_2';
                $rs[$key]['params'] = implode('-', $param);
                $rs[$key]['attr'] = $_rs;
                //更新时间
                $paramsArr = $this->common_model->parseParams($rs[$key]['param'], 1);
                $lists = [];
                // 每页单张展示数据
                $powers = $this->common_model->getPowers(4, $rs[$key]['params'], $rs[$key]['iColId']);
//                $_GET['debug_solr']=1;
                $totalCount = $this->getStyleLists($rs[$key]['params'], $rs[$key]['iColId'], $lists, $offset = 0, $limit = 1, $powers);
                //更新时间
                $nowTime = time();
                $updateTime = isset($lists[0]['create_time']) && !empty($lists[0]['create_time']) ? strtotime($lists[0]['create_time']) : strtotime($lists[0]['updateTime']);
                $time = $nowTime - $updateTime;
                $date = floor($time / (24 * 3600));//距离当前时间的天数
//                var_dump($date);
                if ($date >= 1 && $date < 2) {
                    $rs[$key]['updateTime'] = '昨日更新';
                } elseif ($date >= 2 && $date <= 30) {
                    $rs[$key]['updateTime'] = $date . '天前更新';
                } elseif ($date < 1) {
                    $rs[$key]['updateTime'] = '今日更新';
                }

                unset($rs[$key]['params']);
                //写入缓存
                //缓存有效期，第二天早上7点
                $secDate = date('Y-m-d', time() + 24 * 3600);
                $date = strtotime($secDate . ' 07:00:00');
                $expirTime = $date - time();
//                echo $expirTime;
                $this->cache->memcached->save($memcacheKey, $rs[$key], $expirTime);
            }

        }

        $list['count'] = $count;//记录条数
        if ($list['count'] > 0) {
            $code = 0;
            $msg = '列表请求成功';
            $list['list'] = $rs;//筛选条件
        } else {
            $code = 9;
            $msg = '列表为空';
            $list = [];
        }
        $data = [
            'code' => $code,
            'msg' => $msg,
            'data' => $list
        ];
        $this->benchmark->mark('code_end');
        return $data;

    }

    /**
     * 保存筛选条件
     * 根据用户类型判断用户的保存条数,并把条件记录写入表中
     * @param $paramsArr 参数数组
     * @param $P_UserType 用户类型
     * @param $userId 用户ID
     * @return array
     */
    public function saveCondition($arr, $paramsArr, $P_UserType, $userId, $conditions, $link, $columnId)
    {
        $tableName = '`fashion`.`t_style_user_screening_items`';
        $powers = memberPower('other');
//        插入记录所需字段
        if ($paramsArr) {
            $dateTime = date('Y-m-d H:i:s');
            $filedVals = [];
            if ($P_UserType == 2) {
                //根据子账号ID获取主账号
                $sql = "select iParentID from  `fashion`.`fashion_user_child` WHERE sChildID = ?";
                $rs = $this->db()->query($sql, $userId)->result_array();
                $filedVals['sChildId'] = $userId;
                if ($rs[0]['iParentID']) {
                    $filedVals["iAccountId"] = $rs[0]['iParentID'];
                } else {
                    $filedVals["iAccountId"] = '';
                }
            } else {
                $filedVals["iAccountId"] = $userId;
                $filedVals['sChildId'] = 0;
            }
            $filedVals['iUserType'] = $P_UserType;
            $filedVals["dCreateTime"] = $dateTime;
            $filedVals["iStatus"] = 1;
            $filedVals["sScreenUrl"] = $link;
            $filedVals["iColId"] = $columnId;
            foreach ($paramsArr as $type => $value) {
                $filedVals[$arr[$type]] = $value;
            }
        }
//       查询记录所需字段
        $fields = [];
        if ($conditions) {
            foreach ($conditions as $key => $val) {
                $fields[] = $key . "=" . '"' . $val . '"';
            }
        }
        $field = implode(' AND ', $fields);
        $list = array();
        if ($P_UserType) {
            if ($P_UserType == 1 || $P_UserType == 2) {


                //子账号 查询表中是否已存在>10条记录，如果不存在则继续插入记录
                $sql = "select count(1) as total from {$tableName} WHERE {$field}";
                $res = $this->query($sql);

                if ($res[0]['total'] < 10) {//可以保存
                    //先查询有没有重复的记录，如果没有在插入
                    $sql = "select id  from {$tableName} WHERE {$field} AND sScreenUrl=?";
                    $resId = $this->query($sql, $link);
                    if ($resId) {//如果存在重复记录
                        //点击取消
                        $code = 10;
                        $msg = "已有重复记录，是否需要置顶";
                        $id = $resId[0]['id'];

                    } else {//不存在重复记录，则插入表
                        $result = $this->executeSave($tableName, $filedVals);
                        if ($result) {
                            $code = 0;
                            $msg = "保存成功";
                            //更新缓存
                            $this->getScreeningList($conditions, $powers, $arr);
                        } else {
                            $code = '8';
                            $msg = "保存失败";
                        }
                    }
                } else {//不可以保存
                    $code = 2;
                    $msg = "子账号VIP保存记录大于10条";
                }

            } elseif ($P_UserType == 3 || $P_UserType == 4) {

                //主账号或者试用和普通用户
                $sql = "select count(1) as total from {$tableName} WHERE {$field}";
                $res = $this->query($sql);
                if ($res[0]['total'] < 1) {//可以保存
                    //插入筛选条件记录
                    $result = $this->executeSave($tableName, $filedVals);
                    if ($result) {
                        $code = 0;
                        $msg = "保存成功";
                        //更新缓存
                        $powers = memberPower('other');
                        $this->getScreeningList($conditions, $powers, $arr);
                    } else {
                        $code = '8';
                        $msg = "保存失败";
                    }

                } else {//不可以保存
                    if ($P_UserType == 1) {
                        $code = '4';
                        $msg = "主账号VIP用户保存记录大于1条";

                    } elseif ($P_UserType == 4) {
                        $code = '5';
                        $msg = "普通用户保存记录大于1条";

                    } elseif ($P_UserType == 3) {
                        $code = 3;
                        $msg = "使用VIP保存记录大于1条";
                    }

                }

            } elseif ($P_UserType == 5) {//游客
                $code = 1;
                $msg = "未登陆，无法保存";
            }

        }
        $data = [];

        $data = ['code' => $code, 'msg' => $msg, 'id' => $id];
        return $data;
    }

    //设置置顶
    public function listTop($id, $arr)
    {
        $tableName = '`fashion`.`t_style_user_screening_items`';
        $dateTime = date('Y-m-d H:i:s');
        //点击确定
        $data['dCreateTime'] = $dateTime;
        $con['id'] = $id;
        $rs = $this->executeUpdate($tableName, $data, $con);
        if ($rs) {
            $code = 0;
            $msg = '置顶成功';
            //更新缓存
            $powers = memberPower('other');
            $userId = getUserId();
            $conditions = $this->getSaveConditions($powers['P_UserType'], $userId);
            $this->getScreeningList($conditions, $powers, $arr);
            $list = [];
        }
        $data = ['code' => $code, 'msg' => $msg, 'data' => $list];
        return $data;
    }

    /**
     * 获取保存筛选栏目的条件
     * @param $P_UserType 用户类型
     * @param $userId   用户ID
     */
    public function getSaveConditions($P_UserType, $userId)
    {
        $conditions = [];
        if ($P_UserType) {
            if ($P_UserType == 2) {
                //子账号
                $conditions['sChildId'] = $userId;
                $conditions['iUserType'] = $P_UserType;
                $conditions['iStatus'] = 1;
            } elseif ($P_UserType == 1 || $P_UserType == 3) {
                //主账号或者试用和试用用户
                $conditions['iAccountId'] = $userId;
                $conditions['iUserType'] = $P_UserType;
                $conditions['iStatus'] = 1;
            } elseif ($P_UserType == 4) {
                //普通用户 判断子账号是否已保存过记录
                $sql = "select count(1) as total from `fashion`.`t_style_user_screening_items` WHERE sChildId=?";
                $rsTotal = $this->query($sql, $userId);
                if ($rsTotal[0]['total'] > 0) {
                    $conditions['sChildId'] = $userId;
                    $conditions['iStatus'] = 1;
                } else {
                    $conditions['iAccountId'] = $userId;
                    $conditions['iUserType'] = $P_UserType;
                    $conditions['iStatus'] = 1;
                }

            } elseif ($P_UserType == 5) {
                //游客
            }
        }
        return $conditions;
    }

    /**
     * 删除保存记录
     * @param $id 删除的记录ID
     */
    public function delScreeningList($id, $arr)
    {
        $tableName = '`fashion`.`t_style_user_screening_items`';
        if ($id) {
            $data['iStatus'] = 2;
            $con['id'] = $id;
            $rs = $this->executeUpdate($tableName, $data, $con);
            if ($rs) {
                $code = 0;
                $msg = '删除成功';
                //更新缓存
                $powers = memberPower('other');
                $userId = getUserId();
                $memcacheKey = $this->screenMemcache . $userId . '_' . $id;
                $this->cache->memcached->delete($memcacheKey);
                $conditions = $this->getSaveConditions($powers['P_UserType'], $userId);
                $this->getScreeningList($conditions, $powers, $arr);
            } else {
                $code = 6;
                $msg = '删除失败';
            }
        }
        $data = array('code' => $code, 'msg' => $msg, 'data' => array());
        return $data;

    }

}
