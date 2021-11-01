<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Runways栏目 专用类
 * @property-read common_model $common_model
 */
class Runways_model extends POP_Model
{
    const POP_FASHION_DATABASE_NAME = 'fashion';
    const POP_FASHION_DATABASE_SELECT_MODE = 'write';
    const MEM_EXPIRE = 7200; //缓存周期2小时

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'statistics_model']);
    }

    /**
     * [getRegionIds 获取T台发布页面左边栏的中英文及url]
     * @param  int $columnId [用于构造url]
     * @param  string $params [用于构造url]
     * @return array  $regions
     */
    public function getRegionIds($columnId, $params, &$regIds = '')
    {
        $regs = ['Paris' => '巴黎时装周', 'Newyork' => '纽约时装周', 'Milan' => '米兰时装周', 'London' => '伦敦时装周',];
        $regions = [];
        if (empty($params)) {
            //参数为空
            $link = $this->common_model->getLink($columnId);
        } else {
            // 大栏目里面的子栏目之间只进行公共条件的传递
            $columnPid = GetCategory::getOtherFromColId($columnId);
            if ($columnPid == 0) {
                $columnPid = $columnId;
            }
            $paramsArr = $this->common_model->parseParams($params);
            unset($paramsArr['reg']);
            $params = $this->common_model->getColParams($columnId, $paramsArr);
            $link = $this->common_model->getLink($columnId, $params);
        }
        $regions['3'] = ['sName' => '全部', 'sEnName' => 'ALL', 'sLink' => $link,];

        foreach ($regs as $key => $val) {
            $regIds[] = $regId = GetCategory::getRegionIdFromEnName($key);
            $regions[$regId]['sName'] = $val;
            $regions[$regId]['sEnName'] = $key == 'Newyork' ? 'New York' : $key;
            $link = $this->common_model->getLink($columnId, $params, 'reg', $regId);
            $regions[$regId]['sLink'] = $link;
            // 巴黎时装周的特殊处理
            // if ($regId == 272) {
            if (strpos($link, 'typ_') === false) {
                $regions[$regId]['sLink'] = str_replace('reg_', 'typ_tshowpic-reg_', $link);
            }
            // }
        }
        $regions['other'] = ['sName' => '其它', 'sEnName' => 'Other', 'sLink' => $this->common_model->getLink($columnId, $params, 'reg', 'other'),];

        return $regions;
    }

    /**
     * [getRunwayLists 获取每页单张展示的数据+总条数]
     * @param  string $params [url参数]
     * @param  integer $columnId [栏目id]
     * @param  integer $offset [偏移量]
     * @param  integer $limit [每页条数]
     * @return [array]   &$lists     [根据条件查询出的结果]
     * @return [array]   $totalCount [根据条件查询出的总条数]
     */
    public function getRunwayLists($params = '', $columnId, &$lists, $offset = 0, $limit = 10, $powers = array())
    {
        $this->benchmark->mark('getRunwayLists');
        if (!empty($params)) {
            $paramsArr = $this->common_model->parseParams($params);
        }
        $lists = [];
        $columnPid = GetCategory::getOtherFromColId($columnId);
        if ($columnPid == 0) {
            $columnPid = $columnId;
        }
        // 条件
        $conditions = $this->getConditions($params, $columnId, $powers);

        // 排序
        $arSort = $this->common_model->getSort($params, $powers, $columnPid);

        $result = $relaPopIdArr = [];
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $arSort);

        // T台 + 视频专栏表 判断 视频是否存在
        if (!empty($result) && $columnId == 3) {
            $this->load->model('video_model');
            $videoExistDict = $this->video_model->getVideoExist($result, $columnId);
        }
        $collectStatusList = $this->getListDataCollectStatus($result);
        foreach ($result as $key => $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];
            $data = OpPopFashionMerger::getProductData($id, $tableName);
            $info = $data[$id];
            // T台是否有视频 (花絮也是视频)
            $info['video_have'] = false;
            if ($columnId == 3) {
                $relation_pop_id = 'presscon_' . $id;
                $info['video_have'] = !empty($videoExistDict) && $videoExistDict[$relation_pop_id] ? true : false;
            }
            // 性别
            $info['genderName'] = $info['sGender'] ? GetCategory::getOtherFromIds($info['sGender'], ['sName']) : '';
            // 地区名称
            $info['regionName'] = $info['iRegion'] ? GetCategory::getFieldFromId($info['iRegion']) : '';
            // 季节
            $info['seasonName'] = $info['iSeason'] ? GetCategory::getOtherFromIds($info['iSeason'], ['sName']) : '';
            // 设计师（品牌）
            $info['brand_name'] = $info['brand_tid'] ? GetCategory::getBrandOtherFormId($info['brand_tid']) : '';
            // 发布时间
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

            $info['nme'] = stripcslashes($info['nme']);
            // 图片路径
            $imgPath = getImagePath($tableName, $info);
            $info['cover'] = $imgPath['cover'];

            // 如果栏目是父级栏目则获取栏目名称
            if (count($val['iColumnId']) > 1 && $columnId == $val['iColumnId'][0]) {
                // 栏目中文名
                $info['columnName'] = GetCategory::getOtherFromColId($val['iColumnId'][1], 'sName');
            }
            $info['columnId'] = $val['iColumnId'][1];
            $info['tableName'] = getProductTableName($tableName);
            $info['offset'] = $offset++;
            $info['total'] = $totalCount;
            $info['view_count'] = $this->statistics_model->getViews($tableName, $id, $info);
            $info['iCollectStatus'] = array_search($val['pop_id'], $collectStatusList) === false ? 0 : 1; // 是否收藏
            // $lists[$key]['videoStatus'] = !empty($val['aVersion']) && in_array('video', $val['aVersion']) ? 1 : 0; // 是否有视频

            $lists[] = $info;
        }
        $this->benchmark->mark('getRunwayListsEnd');
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
        $conditions = ['iColumnId' => $columnId,];
        // 性别
        $gender = $this->common_model->getGenderByRequest($params);
        $this->common_model->childGender($gender, $conditions);
        // 地区
        if (isset($paramsArr['reg'])) {
            if ($paramsArr['reg'] == 'other') {
                $this->getRegionIds($columnId, $params, $regIds);
                $regions = [];
                foreach ($regIds as $key => $val) {
                    $regions[] = $val;
                }
                if (!empty($regions)) {
                    $conditions['other'][] = '-iRegion:(' . implode(' OR ', $regions) . ')';
                }
            } else {
                $region = intval($paramsArr['reg']);
                $conditions['other'][] = "(iRegion:{$region} OR iArea:{$region} OR iContinent:{$region} OR  iCountry:{$region})";
            }
        }
        // 只看收藏
        if (isset($paramsArr['coll']) && $paramsArr['coll']) {
            $conditions['aCollectAccount'] = getUserId();
        }
        // 没有权限检索(除了性别和行业)
        if ((!empty($powers) && !$powers['P_Search'])) {
            return $conditions;
        }

        //***************以下没有权限不可以搜索********************//

        // 时间范围,0=>不限，1=>近7日,2=>近30日,3=>近半年
        if (isset($paramsArr['tim']) && $paramsArr['tim'] != 0) {
            $conditions['dCreateTime'] = $this->common_model->getTimeRange($paramsArr['tim']);
        }
        // 品牌
        if (isset($paramsArr['bra'])) {
            $conditions['iBrand'] = intval($paramsArr['bra']);
        }
        // T台发布-版本
        if (isset($paramsArr['ver'])) {
            if ($paramsArr['ver'] == 'video') {
                // 花絮也算视频
                $conditions['other'][] = '(aVersion:(video OR tidbit))';
            } else {
                // 版本
                $conditions['aVersion'] = $paramsArr['ver'];
            }

        }
        // 发布会类型
        if (isset($paramsArr['typ'])) {
            $conditions['sClass'] = $paramsArr['typ'];
        }
        // 季节
        if (isset($paramsArr['sea'])) {
            $conditions['other'][] = 'aLabelIds:' . intval($paramsArr['sea']);
        }
        // 关键字检索
        $keyword = $this->common_model->getKeyword($params, $powers);
        if (strpos($keyword, '?key=') === 0) {
            $keyword = ltrim($keyword, '?key=');
        }
        if ($keyword) {
            $conditions['other'][] = 'combine:(' . $keyword . ')';
        }
        return $conditions;
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
        $items = $this->category_model->getRealCategory($selectItem, $columnId, $conditions, $keys);
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
                // 版本
                case 'sVersion':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'ver');
                    break;
                // 类型
                case 'sClass':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'typ');
                    break;
                default:
                    # code...
                    break;
            }
        }
        $this->benchmark->mark('groupbySelectEnd');
        return $selectItems;
    }

    /**
     * [getRunwaysInsideLists 根据不同参数获取二级列表]
     * @param  integer $id [发布会id]
     * @param  integer $offset [偏移量]
     * @param  integer $versionFlag [版本类型]
     * @param  string $limit [每页条数]
     * @return [array]   $result [根据条件查询出的结果]
     * @return [array]   $totalCount  [根据条件查询出的总条数]
     */
    public function getRunwaysInsideLists($id, $versionFlag, &$totalCount, $offset = 0, $limit = 10, $refresh = false)
    {
        $tableDetail = 'product_presscon_details';
        $key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_' . 'innerPage_' . md5($tableDetail . $id . $versionFlag . $offset . $limit);

        $this->load->driver('cache');
        $memResult = $this->cache->memcached->get($key);

        if ($memResult === false || $refresh || $this->input->get('refresh')) {
            if (!empty($versionFlag) && $versionFlag != 'video') {
                switch ($versionFlag) {
                    case 'live': //现场及时版
                        $versionId = 1;
                        break;
                    case 'focus': //高清版
                        $versionId = 2;
                        break;
                    case 'special': //细节版
                        $versionId = 3;
                        break;
                }
                $where = 'main_id = ' . $id . ' ' . 'AND main_flag = ' . $versionId;
                $field = 'id,main_id,main_flag,small_name,big_name,path,create_time,category,';
                $field .= 'category_text,designer,detail,for_date,for_date_text,nme,region,region_text,';
                $field .= 'typ,type_text,rar_file,updateTime,brand_tid,jobnumber,source_text,`sGender`,`iSeason`,`iRegion` ';

                $sql = 'SELECT ' . $field . ' FROM ' . $tableDetail . ' WHERE ' . $where . ' LIMIT ' . $offset . ',' . $limit;
                $totalCountSql = 'SELECT COUNT(*) AS total FROM ' . $tableDetail . ' WHERE ' . $where;
            }
            $result = $this->query($sql);
            $totalCountRow = $this->query($totalCountSql);
            $totalCount = $totalCountRow[0]['total'];
            if (!empty($result)) {
                $value['result'] = $result;
                $value['totalCount'] = $totalCount;
                $this->cache->memcached->save($key, $value, self::MEM_EXPIRE);
            } else {
                $result = array();
            }
        } else {
            $result = $memResult['result'];
            $totalCount = $memResult['totalCount'];
        }
        return $result;
    }

    /**
     * [getSeoArray   获取seo搜索引擎，标题、关键字、描述]
     * @param  integer $columnId [栏目id]
     * @return string   [中文字符]
     */
    public function getSeoArray($columnId, $params = '')
    {
        if (!empty($params)) {
            $paramsArr = $this->common_model->parseParams($params);
        } else {
            $paramsArr = [];
        }
        //性别
        $gender = $this->common_model->getGenderByRequest($params);
        $gen = !empty($gender) ? trim(GetCategory::getOtherFromIds($gender, ['sName'])) : '';
//        //行业
//        $industry = $this->common_model->getIndustryByRequest($params);
//        $ind = !empty($industry)?trim(GetCategory::getOtherFromIds($industry, ['sName'])):'';
//        if($ind == '运动') {
//            $ind = '运动装';
//        }
        //地区
        $reg = $this->common_model->getDefaultParams('reg', $params, $columnId);
        //季节
        $sea = $this->common_model->getDefaultParams('sea', $params, $columnId);
        //品牌
        $bra = $this->common_model->getDefaultParams('bra', $params, $columnId);
        //T台发布类型
        $typ = $this->common_model->getDefaultParams('typ', $params, $columnId);
        //版本
        $ver = $this->common_model->getDefaultParams('ver', $params, $columnId);
        $timArray = array('全部时段', '近7日', '近30日', '近半年');
        $sorArray = array(1 => "按时间最新", 2 => "按浏览最高", 3 => "按收藏最多");
        //时段
        $jtime = $timArray[$paramsArr['tim']];
        //时间
        $sor = $sorArray[$paramsArr['sor']];
        //栏目名称
        $colName = GetCategory::getOtherFromColId($columnId, 'sName');
        switch ($columnId) {
            case 3: // T台发布（没有行业）
                if (empty($params) && empty($gen)) {
                    $title = '巴黎/纽约/米兰/伦敦时装周-POP服装趋势网';
                    $keywords = '巴黎时装周,纽约时装周,米兰时装周,伦敦时装周';
                    $description = 'POP服装趋势网T台栏目汇集了国际各大时装周、时装秀、T台走秀发布会资讯和高清大图，及时更新各时装周的最新报道、走秀图片以及相关分析报告，为您提供有价值的资讯服务。';
                } else {
                    if (count($paramsArr) == 1 && in_array($reg, ['巴黎', '纽约', '米兰', '伦敦', 'other'])) {
                        $_reg = ($reg == 'other' ? '其他' : $reg) . '时装周';
                        $keyInfo = [
                            '巴黎' => '巴黎时装周,巴黎时装秀',
                            '纽约' => '纽约时装周,纽约时装秀',
                            '米兰' => '米兰时装周,米兰时装秀',
                            '伦敦' => '伦敦时装周,伦敦时装秀',
                            'other' => '时装周,时装发布会,成衣秀'
                        ];
                        $title = $_reg . '_T台-POP服装趋势网';
                        $keywords = $keyInfo[$reg];
                        $description = 'POP服装趋势网T台的' . $_reg . '栏目汇集了时装周、T台、成衣时装秀照片等资讯，及时更新各时装周的最新报道、走秀图片、发布会款式图片以及服装相关分析报告，为您提供有价值的服装设计资讯服务。';
                    } else {
                        $reg = ($reg == 'other' ? '其他' : ($reg == '' ? '' : $reg . '时装周'));
                        $jh = array_filter([$gen, $reg, $sea, $bra, $typ, $ver, $jtime, $sor]);
                        $t = implode('_', $jh) . '_';
                        $k = implode(',', $jh) . ',';
                        $d = implode('/', $jh);
                        if (empty($reg)) {
                            $title = $t . 'T台-POP服装趋势网';
                            $keywords = $k . 'T台';
                            $description = 'POP服装趋势网T台栏目汇集了国际各大时装周、时装秀、T台走秀发布会' . $d . '资讯，高清大图，及时更新各时装周的最新报道、走秀图片以及' . $d . '相关分析报告，为您提供有价值的资讯服务。';
                        } else {
                            $title = $t . $reg . '-POP服装趋势网';
                            $keywords = $t . $reg;
                            $description = 'POP服装趋势网的' . $reg . '栏目汇集了' . $d . '时装周、T台、成衣时装秀照片等资讯，及时更新各时装周的最新报道、走秀图片、发布会款式图片以及' . $d . '相关分析报告，为您提供有价值的服装设计资讯服务。';
                        }
                    }
                }
                break;
        }
        $seoArray[$columnId] = ['title' => $title, 'keywords' => $keywords, 'description' => $description];
        return $seoArray[$columnId];
    }

    /**
     * 获取栏目介绍的内容
     * @param $columnId int 栏目ID
     * @return string 栏目介绍
     */
    public function getColumnsPresentation($columnId)
    {
        switch ($columnId) {
            case '3':
            default:
                $columnPresentation = "全球30多个国家和地区，2000多个先锋品牌，秀场动态时时同步跟踪更新，并根据特色款式进行款式提炼推荐和深度品牌解析与城市维度单品提炼和综合分析";
                break;
        }
        return $columnPresentation;
    }

    /**
     * ajax列表数据
     * @param string $params 接收的参数
     * @param int $columnId 栏目ID
     * @param mixed $powers 权限
     * @param bool $isAjax 是否是ajax请求
     * @return array array(数据列表$lists, 其他信息$info)
     */
    public function ajaxList($params, $columnId, $powers, $isAjax = true)
    {
        $this->benchmark->mark('ajaxGetList');

        $paramsArr = $this->common_model->parseParams($params, 1);
        $page = $this->common_model->getPage($paramsArr); // 当前页
        $page = max($page, 1);
        $pageSize = getRequestPageSize(18);
        $offset = ($page - 1) * $pageSize;

        $lists = [];
        $totalCount = $this->getRunwayLists($params, $columnId, $lists, $offset, $pageSize, $powers);

        $info = [
            'totalCount' => $totalCount,
            'page' => $page,
            'pageSize' => $pageSize,
            'powers' => $powers
        ];

        $this->benchmark->mark('ajaxGetListEnd');

        if ($isAjax) {
            $getJsonOutput = $this->getJsonOutputObj();
            $getJsonOutput->code(0)->data($lists)->info($info)->msg('ok')->out();
        }

        return [$lists, $info];
    }

    // 相关分析--报告推荐的数据处理(T台分析)
    public function dealRelationReportSolrData($searchResult)
    {
        if (empty($searchResult) || !is_array($searchResult)) return [];
        $lists = [];
        foreach ($searchResult as $key => $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];
            $iColumnId = $val['iColumnId'][1];
            $data = OpPopFashionMerger::getProductData($id, $tableName);
            $info = $data[$id];
            $info['columnId'] = !empty($iColumnId) ? $iColumnId : '';

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
            $info['tableName'] = getProductTableName($tableName);
            // 潮流解析标题$info['title']
            $info['title'] = htmlspecialchars(stripcslashes($info['title']));
            // 图片路径
            $imgPath = getImagePath($tableName, $info);
            $info['cover'] = getFixedImgPath($imgPath['cover']);
            $lists[] = $info;
        }
        return $lists;
    }


    private function getItems($columnId, $params, $arr, $type)
    {
        $ret = [];
        foreach ($arr as $id => $name) {
            $ret[] = ['name' => $name, 'link' => $this->common_model->getLink($columnId, $params, $type, $id, true, 'anchor')];
        }
        return $ret;
    }
}
