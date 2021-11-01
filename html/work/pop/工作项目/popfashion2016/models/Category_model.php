<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Category_model
 * @property-read common_model $common_model
 */
class Category_model extends POP_Model
{
    private $refresh;

    private $MemTime = 3600;
    /**
     * @var string
     */
    private $memcachePref;

    public function __construct()
    {
        parent::__construct();

        if ($this->input->get('refresh')) {
            $this->setRefresh(true);
        }

        $this->load->driver('cache');
        $this->load->helper('cookie');
        $this->memcachePref = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_CategoryRealData_19112201_';
    }

    public function setRefresh($refresh = false)
    {
        $this->refresh = $refresh;
    }
    //===============================功能1：根据solr获取真实存在的筛选条件==============================================
    /*@todo 根据字段分组，并获取该分组下的数据
     *@param $groupField string 分组的字段名
     *@param $condition array  搜索条件
     *@param $return  array 返回结果
     *@param $arSort array 排序
     *@param $offset  
     *@param $limit 
     *@return int 总数
     */
    public function getGroupList($keyword = '', $groupField, $condition, &$return, $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'], $offset = 0, $limit = 30, $glimit = 4)
    {
        $params = [];
        $params['group'] = 'true';
        $params['group.offset'] = 0;
        $params['group.limit'] = $glimit;//每组取得数据条数
        $params['group.ngroups'] = 'true';
        $params['group.field'] = $groupField;
        $params['group.sort'] = ['iViewCount' => 'DESC'];    // 按最热门的排序
        if (!isset($condition[$groupField]) && !empty($groupField)) {
            $condition[$groupField] = '{0 TO *}';
        }
        POPSearch::wrapQueryPopFashionMerger($keyword, $condition, $res, $offset, $limit, $arSort, $params);
        if (empty($res)) {
            $count = 0;
            $return = [];
        } else {
            $return = self::dealGroupSolrDate($groupField, $res);
            $count = $res["$groupField"]['ngroups'];
        }
        return $count;
    }

    /*
     *@todo  根据solr获取真实存在的筛选条件
     *@param $field String 所要取得字段
     *@param $iColumnId int 栏目ID
     *@param array
     */
    public function getRealCategory($field, $iColumnId, $condition = array(), $keyWord = '', $limit = 150, $Refresh = false)
    {
        if (empty($field)) {
            return [];
        }

        if (empty($keyWord) && empty($condition['aCollectAccount'])) { //如果没有 关键子搜索。groupBy做缓存
            $lang = $this->input->cookie('lang');
            $lang = !empty($lang) ? $lang : '1';

            $conditionTmp = $condition;
            unset($conditionTmp['dCreateTime']);
            ksort($conditionTmp);
            $conditionStr = '';
            foreach ($conditionTmp as $key => $val) {
                if (is_array($val)) {
                    asort($val);
                    $conditionTmp[$key] = implode('-', $val);
                }
                $conditionStr .= $key . '_' . $conditionTmp[$key] . '-';
            }
            if (is_array($field)) {
                asort($field);
                $fieldStr = implode('.', $field);
            }
            unset($conditionTmp['iColumnId']);
            $MemcacheKey = $this->memcachePref . md5($lang . '-' . $iColumnId . '-' . $fieldStr . '-' . $conditionStr . '-20190829');
            $returns = $this->cache->memcached->get($MemcacheKey);
        }
        if (empty($returns) || $Refresh || $this->refresh || $this->input->post('refresh')) {

            //------------------------图案内容不联动 sPatternContent [start]---------------$returns = array();-----------------//
            if (in_array('sPatternContent', $field) && $iColumnId != 121) {
                $TmpKey = array_search('sPatternContent', $field);
                unset($field["{$TmpKey}"]);
                $sPatternContents = GetCategory::getSomeAttr(25, '', false);
                $_tmp = [];
                foreach ($sPatternContents as $val) {

                    // 图案内容一级标签设置了前台不显示
                    if (!$val['iDisplay']) {
                        continue;
                    }

                    $_tmp[$val['iAttributeId']]['sName'] = $val['sName'];
                    foreach ($val['attrs'] as $v) {

                        // 图案内容二级标签设置了前台不显示
                        if (!$v['iDisplay']) {
                            continue;
                        }

                        // 过去的一年时间内，该标签有上新数据
                        if (strtotime($v['dLastUpdateTime']) > strtotime('-1 year')) {
                            $_tmp[$val['iAttributeId']]['attrs'][$v['iAttributeId']] = ['sName' => $v['sName']];
                        }
                    }

                    // 一级标签，过去一年内没有上新数据，且其下二级标签也没有上新数据，则前台不显示改标签
                    if (strtotime($val['dLastUpdateTime']) < strtotime('-1 year') && empty($_tmp[$val['iAttributeId']]['attrs'])) {
                        unset($_tmp[$val['iAttributeId']]);
                    }
                }
                $returns['sPatternContent'] = $_tmp;
            };
            //------------------------图案内容不联动 sPatternContent [end]--------------------------------//
            if (!empty($field)) {
                $arSort = array('dCreateTime' => 'DESC', 'pri_id' => 'DESC');//排序
                // 特殊处理,将手稿合辑里面的lookbook移除，并加入到款式库侧边广告通
                if ($iColumnId == 6) {
                    $iColumnId = [70, 72, 113, 114, 115];
                }
                $condition['iColumnId'] = $iColumnId;

                $params = $ret = [];
                $params['facet'] = 'true';
                $params['facet.limit'] = -1;
                // $params['facet.ngroups'] = 'true';
                $params['facet.field'] = $field;
                $params['raw'] = true;
                $start = 0;
                if ($keyWord) {
                    if (isset($condition['other']) && !empty($condition['other'])) {
                        if (is_array($condition['other'])) {
                            $condition['other'][] = 'combine:(' . $keyWord . ')';
                            $condition['other'] = array_unique($condition['other']);
                        } else {
                            stripos($condition['other'], 'combine:(' . $keyWord . ')') === false && $condition['other'] .= ' AND combine:(' . $keyWord . ')';
                        }
                    } else {
                        $condition['other'] = 'combine:(' . $keyWord . ')';
                    }
                }
                $num = POPSearch::wrapQueryPopFashionMerger('', $condition, $ret, $start, $limit, $arSort, $params);
                if (is_array($field)) {
                    foreach ($field as $key => $val) {
                        if (!array_key_exists($val, $ret)) {
                            $returns[$val] = array();
                        }
                    }
                }
                if ($num['facet_counts']['facet_fields']) {
                    foreach ($num['facet_counts']['facet_fields'] as $field => $value) {
                        $groupValue = array();
                        $return = array();
                        //$group_tmp = [];

                        foreach ($num['facet_counts']['facet_fields'][$field] as $key => $val) {
                            if ($val > 0) {
                                if (strpos($key, ',')) {
                                    foreach (explode(',', $key) as $k => $v) {
                                        $groupValue[] = $v;
                                        //$group_tmp = $groupValue;
                                    }
                                } else {
                                    $groupValue[] = $key;
                                }
                            }
                        }
                        if (!empty($groupValue)) {
                            $field = ($field == 'iDataSource') ? 'iTrendOrManual' : $field;
                            $all = $this->getAll($field, $Refresh);
                            $return = $this->getReturnData($all, $groupValue, $field);
                            unset($all);
                        }

                        $returns[$field] = $return;
                    }
                }
            }
            if (empty($keyWord) && empty($condition['aCollectAccount'])) { //关键词为空时，才做缓存
                $this->cache->memcached->save($MemcacheKey, $returns, $this->MemTime);
            }
        }
        return $returns;
    }

    //热度品牌group特殊
    public function getHotBrandIds($iColumnId, $condition = array(), $keyWord = '', $Refresh = false)
    {
        //========数据库查询部分========
        $sql = "SELECT A.id FROM `pop136`.`brand_library` A LEFT JOIN `fashion`.`t_dict_attribute` B ON A.iGrade=B.iAttributeId WHERE B.iSort>1000";
        $rows = $this->query($sql);
        if (empty($rows)) {
            return [];
        }
        $iBrands = [];
        foreach ($rows as $key => $value) {
            $iBrands[] = $value['id'];
        }

        //========solr查询部分=========
        //1、关键字
        if ($keyWord) {
            if (isset($condition['other']) && !empty($condition['other'])) {
                if (is_array($condition['other'])) {
                    $condition['other'][] = 'combine:(' . $keyWord . ')';
                    $condition['other'] = array_unique($condition['other']);
                } else {
                    stripos($condition['other'], 'combine:(' . $keyWord . ')') === false && $condition['other'] .= ' AND combine:(' . $keyWord . ')';
                }
            } else {
                $condition['other'] = 'combine:(' . $keyWord . ')';
            }
        }
        //2、创建时间
        $condition['iColumnId'] = $iColumnId;
        $arSort = array('dCreateTime' => 'DESC', 'pri_id' => 'DESC');//排序

        //3、memcache键值设置 与 取值
        $return = [];
        if (empty($keyWord) && empty($condition['aCollectAccount'])) {
            $conditionTmp = $condition;
            unset($conditionTmp['dCreateTime']);
            ksort($conditionTmp);
            $conditionStr = '';
            foreach ($conditionTmp as $key => $val) {
                if (is_array($val)) {
                    asort($val);
                    $conditionTmp[$key] = implode('-', $val);
                }
                $conditionStr .= $key . '_' . $conditionTmp[$key] . '-';
            }
            unset($conditionTmp['iColumnId']);
            $MemcacheKey = $this->memcachePref . md5($iColumnId . '-' . $conditionStr);
            $return = $this->cache->memcached->get($MemcacheKey);
        }
        //无缓存时取solr
        if (empty($return) || $Refresh || $this->refresh || $this->input->post('refresh')) {
            //分组信息
            $params = array();
            $params['group'] = 'true';
            $params['group.limit'] = 0;
            $params['group.ngroups'] = 'true';
            $params['group.field'] = 'sBrand';

            //处理返回结果
            $return = [];
            $aChunkBrands = array_chunk($iBrands, 400);
            foreach ($aChunkBrands as $_hotBrand) {
                $condition['iBrand'] = $_hotBrand;
                $start = 0;
                $totalCount = 0;
                $limit = count($_hotBrand);
                while (true) {
                    //solr查询
                    $num = POPSearch::wrapQueryPopFashionMerger('', $condition, $ret, $start, $limit, $arSort, $params);
                    $totalCount = $ret['sBrand']['ngroups'];
                    if (!empty($ret['sBrand']['groups'])) {
                        $groupValue = array();
                        $group_tmp = array();
                        foreach ($ret['sBrand']['groups'] as $key => $value) {
                            if (!empty($value['groupValue'])) {
                                foreach (explode(',', $value['groupValue']) as $v) {
                                    if (!in_array($v, $group_tmp)) {
                                        $groupValue[] = intval($v);
                                        $group_tmp = $groupValue;
                                    }
                                }
                            }
                        }
                        $return = array_merge($return, $groupValue);
                    }
                    //判断是否跳出
                    $start += $limit;
                    if ($start >= $totalCount) {
                        break;
                    }
                }
            }
            if (empty($keyWord) && empty($condition['aCollectAccount'])) {
                $this->cache->memcached->save($MemcacheKey, $return, $this->MemTime);
            }
        }
        return $return;
    }


    /*
    * @todo  获取单品加品名的真实存在的筛选条件
    * @param $iColumnId int栏目名称
    * @param array
    */
    public function getCategoryAndSubCategory($iColumnId, $condition = array(), $keyWord = '', $limit = 75, $Refresh = false)
    {
        if (empty($keyWord) && empty($condition['aCollectAccount'])) { //如果没有 关键子搜索。groupBy做缓存
            $lang = $this->input->cookie('lang');
            $lang = !empty($lang) ? $lang : '1';
            ksort($condition);
            $conditionStr = implode(',', $condition);
            $MemcacheKey = $this->memcachePref . md5($lang . $iColumnId . $conditionStr) . '_cat';
            $returns = $this->cache->memcached->get($MemcacheKey);
        }
        if (empty($returns) || $Refresh || $this->refresh || $this->input->post('refresh')) {
            $field = array('sCategory', 'sSubCategory');
            $arr = $this->getRealCategory($field, $iColumnId, $condition, $keyWord, $limit, $Refresh);
            $sCategory = $arr['sCategory'];
            $sSubCategory = $arr['sSubCategory'];
            $sCategoryAll = GetCategory::getSingle('', true, true);
            $returns = [];
            foreach ($sCategoryAll as $key => $val) {
                if (is_array($sCategory) && in_array($val['sName'], $sCategory)) {
                    $returns["$key"]['name'] = $val['sName'];
                    foreach ($val['Pinming'] as $k => $v) {
                        if (is_array($sSubCategory) && in_array($v, $sSubCategory)) {
                            $returns["$key"]['sSubCategory']["$k"] = $v;
                        }
                    }
                }
            }
            if (empty($keyWord) && empty($condition['aCollectAccount'])) { //关键词为空时，才做缓存
                $this->cache->memcached->save($MemcacheKey, $returns, $this->MemTime);
            }
        }
        return $returns;
    }


//---------------------------------以下是私有变量-------------------------------------------------
    /*
    * @todo  获取缓存中的数据，以供筛选
    * @param $field String
    * @param array
    */
    public function getAll($field, $Refresh = false)
    {
        $lang = get_cookie('lang');
        $lang = !empty($lang) ? $lang : '1';
        switch ($lang) {
            case '1'://中文
                $toCnName = 'sName';
                break;
            case '2'://英文
                $toCnName = 'sEnName';
                break;
            case '3'://韩文
                $toCnName = 'sKoreanName';
                break;
            default:
                $toCnName = 'sName';
        }

        $ret = array();
        switch ($field) {
            case 'sGender':    // 性别
                $ret = GetCategory::getGender('', $toCnName);
                break;
            case 'iSeason'://季节
                $ret = GetCategory::getSeason('', $toCnName);
                break;
            case 'sMarketHotPosition'://市场热度定位
                $ret = GetCategory::getBrand('', $toCnName);
                $ret = $this->toOneArray($ret[168]['attrs'], $toCnName);
                break;
            case 'sAgeLayer'://款式->年龄层(389)
                $ret = GetCategory::getStyle(array(389), $toCnName);
                $ret = $this->toOneArray($ret[389]['attrs'], $toCnName);
                break;
            case 'sWearing'://款式->穿着
                $ret = GetCategory::getStyle(array(161), $toCnName);
                $ret = $this->toOneArray($ret[161]['attrs'], $toCnName, true);//只取一级
                break;
            case 'sCategory'://单品
                $sCategory = GetCategory::getAttrValueByType(3, 0, 0, ['sName', 'iDisplay', 'iAttributeId']);

                $ret = array_column(
                    array_filter($sCategory, function ($val) {
                        return $val['iDisplay'];
                    }), 'sName', 'iAttributeId');
                break;
            case 'sSubCategory'://品名
                $sSubCategory = GetCategory::getAttrValueByType(4, 0, 0, ['sName', 'iDisplay', 'iAttributeId']);

                $ret = array_column(
                    array_filter($sSubCategory, function ($val) {
                        return $val['iDisplay'];
                    }), 'sName', 'iAttributeId');
                break;
            case 'sExhibitionName'://展会名称
                $memcacheKey = $this->memcachePref . 'sExhibitionName';
                $ret = $this->cache->memcached->get($memcacheKey);
                if ($ret && !$Refresh && !$this->refresh && !$this->input->post('refresh')) {
                    return $ret;
                } else {
                    $ret = [];
                    include getenv('BASEPATH') . '/category/popfashion/conf/category_conf.php';
                    $sql = 'SELECT id,cat_name,cat_en,sTradeshowType FROM `fashion`.`fashion_category` WHERE parent_id=3488';
                    $arr = $this->query($sql);
                    $ret = array_fill_keys(array_keys($marketExhibitionType), null);
                    foreach ($arr as $val) {
                        $key = $val['sTradeshowType'];
                        $name = $marketExhibitionType["{$val['sTradeshowType']}"];
                        $ret["{$key}"]['sName'] = $name;
                        $ret["{$key}"]['attrs'][$val['id']] = array('sName' => $lang == '2' ? $val['cat_en'] : $val['cat_name']);
                    }
                    $this->cache->memcached->save($memcacheKey, $ret, $this->MemTime);
                    return $ret;
                }
                break;
            case 'sViewpoint'://观点
                $ret = GetCategory::getPoint('', $toCnName);
                break;
            case 'sVisualAngle'://视角
                $ret = GetCategory::getView('', $toCnName);
                break;
            case 'sCommonTag'://灵感源类型
                $ret = GetCategory::getSomeAttr(12, '', $toCnName);
                break;
            case 'sStylePart'://款式细节部位
                $ret = GetCategory::getSomeAttr(13, '', $toCnName);
                break;
            case 'sClass'://类型
                require getenv('BASEPATH') . '/category/popfashion/conf/category_conf.php';
                // 特殊处理  LOOKBOOK里面的类型
                $tmp = [
                    1 => '广告大片',
                    2 => '搭配手册',
                    3 => '结构版',
                    4 => '订货会',
                    5 => '米兰展会',
                ];
                $ret = $popPressconCatgories + $tmp;
                break;
            case 'sVersion'://版本
                $ret = [
                    'special' => '品牌细节版',
                    'focus' => '现场高清版',
                    'live' => '现场及时版',
                    'video' => '动态视频版'
                ];
                break;
            case 'sColorMerge'://色彩组合
                $ret = GetCategory::getStyle(array(11605), $toCnName);
                $ret = $this->toOneArray($ret[11605]['attrs'], $toCnName);
                break;
            case 'sPatternTechnology'://图案工艺
                $ret = GetCategory::getSomeAttr(18);
                break;

            case 'sCraftSource'://工艺来源
                $ret = GetCategory::getSomeAttr(34);
                break;
            case 'sPatternContent'://图案内容 t_dict_attribuite  iType = 25

                $element = GetCategory::getAttrValueByType(25, 1, 0, ['sName', 'iDisplay', 'iAttributeId']);

                // 过滤掉图案内容一级前台不显示的标签
                $element = array_filter($element, function ($val) {
                    return $val['iDisplay'];
                });

                // 取出图案内容一级标签名称/id
                $ret = array_column($element, 'sName', 'iAttributeId');

                // 过滤图案内容二级，前台不显示的标签
                array_walk($element, function ($val) use (&$ret) {
                    $ret += array_column(
                        array_filter($val['children'], function ($v) {
                            return $v['iDisplay'];
                        }), 'sName', 'iAttributeId');
                });
                break;
            case 'sFabricCraft':// 面料工艺
                $memcacheKey = $this->memcachePref . 'sFabricCraft';
                $ret = $this->cache->memcached->get($memcacheKey);
                if (!$ret || $Refresh || $this->refresh || $this->input->post('refresh')) {
                    $ret = [];
                    $sql = 'SELECT `iAttributeId`,`sName`,`sEnName` FROM `fashion`.`t_dict_attribute` WHERE `iAttributePid` = 277 AND `iStatus` = 0 ORDER BY `iSort`';
                    $arr = $this->query($sql);
                    foreach ($arr as $val) {
                        $ret["{$val['iAttributeId']}"] = $val['sName'];
                    }
                    $this->cache->memcached->save($memcacheKey, $ret, $this->MemTime);
                }
                return $ret;
                break;
            case 'sMaterial':// 面料材质
                $memcacheKey = $this->memcachePref . 'sMaterial';
                $ret = $this->cache->memcached->get($memcacheKey);
                if (!$ret || $Refresh || $this->refresh || $this->input->post('refresh')) {
                    $ret = [];
                    $sql = 'SELECT `iAttributeId`,`sName`,`sEnName` FROM `fashion`.`t_dict_attribute` WHERE `iAttributePid` = 258 AND `iStatus` = 0 ORDER BY `iSort`';
                    $arr = $this->query($sql);
                    foreach ($arr as $val) {
                        $ret["{$val['iAttributeId']}"] = $val['sName'];
                    }
                    $this->cache->memcached->save($memcacheKey, $ret, $this->MemTime);
                }
                return $ret;
                break;
            case 'sPatternUse'://图案应用
                $memcacheKey = $this->memcachePref . 'sPatternUse';
                $ret = $this->cache->memcached->get($memcacheKey);
                if ($ret && !$Refresh && !$this->refresh && !$this->input->post('refresh')) {
                    return $ret;
                } else {
                    $ret = [];
                    $sql = 'SELECT cat_id as id,cat_name FROM `fashion`.`category` WHERE parent_id=14101';
                    $arr = $this->query($sql);
                    foreach ($arr as $val) {
                        $ret["{$val['id']}"] = $val['cat_name'];
                    }
                    $this->cache->memcached->save($memcacheKey, $ret, $this->MemTime);
                    return $ret;
                }
                break;
            case 'sPatternFormat'://图案格式
                $memcacheKey = $this->memcachePref . 'sPatternFormat';
                $ret = $this->cache->memcached->get($memcacheKey);
                if ($ret && !$Refresh && !$this->refresh && !$this->input->post('refresh')) {
                    return $ret;
                } else {
                    $ret = [];
                    $sql = 'SELECT cat_id as id,cat_name FROM `fashion`.`category` WHERE parent_id=10959';
                    $arr = $this->query($sql);
                    foreach ($arr as $val) {
                        $ret["{$val['id']}"] = $val['cat_name'];
                    }
                    $this->cache->memcached->save($memcacheKey, $ret, $this->MemTime);
                    return $ret;
                }
                break;
            //地域风格
            case 'sRegionalStyle':
                $ret = GetCategory::getStyle(array(11326), $toCnName);
                $ret = $this->toOneArray($ret[11326]['attrs'], $toCnName);
                break;
            //性格风格
            case 'sCharacterStyle':
                $ret = GetCategory::getStyle(array(11316), $toCnName);
                $ret = $this->toOneArray($ret[11316]['attrs'], $toCnName);
                break;
            //时装周专题(多层)
            case 'sFashionWeek':
                $ret = GetCategory::getSomeAttr(19);
                break;
            //市场类型(报告类多层，款式类单层)
            case 'sMarketType':
                //$ret=GetCategory::getSomeAttr(20);//报告类暂时不做

                //以下为款式库专用
                $memcacheKey = $this->memcachePref . 'sMarketType';
                $ret = $this->cache->memcached->get($memcacheKey);
                if ($ret && !$Refresh && !$this->refresh && !$this->input->post('refresh')) {
                    return $ret;
                } else {
                    $ret = [];
                    $sql = 'SELECT id,cat_name FROM `fashion`.`fashion_category` WHERE parent_id=3486 AND is_show=1 ORDER BY orderby DESC';
                    $arr = $this->query($sql);
                    foreach ($arr as $val) {
                        $ret["{$val['id']}"] = $val['cat_name'];
                    }
                    $this->cache->memcached->save($memcacheKey, $ret, $this->MemTime);
                    return $ret;
                }
                break;
            //陈列类型
            case 'sDisplayType':
                $ret = GetCategory::getSomeAttr(21);
                break;
            //报道类型
            case 'sReportType':
                $ret = GetCategory::getSomeAttr(23);
                break;
            //街拍类型
            case 'sStreetBeatType':
                $ret = GetCategory::getSomeAttr(24);
                break;
            //潮流类型
            case 'sTidalType':
                $ret = GetCategory::getSomeAttr(22);
                break;
            //书籍类型
            case 'sContentDirection':
                $ret = GetCategory::getSomeAttr(15);
                break;
            // 风格
            case 'sManner':
                $sManner = GetCategory::getAttrValueByType(28, 0, 0, ['sName', 'iDisplay', 'iAttributeId']);
                $ret = array_column(
                    array_filter($sManner, function ($val) {
                        return $val['iDisplay'];
                    }), 'sName', 'iAttributeId');
                break;
            //工艺类型
            case 'iTechnologyType':
                $ret = GetCategory::getSomeAttr(31);
                break;
            //期数
            case 'iNo':
                $ret = GetCategory::getSomeAttr(32);
                break;
            //趋势/手稿
            case 'iTrendOrManual':
                $ret = GetCategory::getSomeAttr(33);
                break;
            // 廓形
            case 'sShape':
                $ret = $this->getElementById(11768);
                break;
            // 细节
            case 'sSpecifics':
                $ret = $this->getElementById(11797);
                break;
            // 工艺
            case 'sTechnologys':
                $ret = $this->getElementById(11755);
                break;
            // 图案
            case 'sPattern':
                $ret = $this->getElementById(11787);
                break;
            // 面料
            case 'sFabric':
                $ret = $this->getElementById(11776);
                break;
            // 辅料
            case 'sAccessory':
                $ret = $this->getElementById(11752);
                break;
            //趋势专题（关联主题）
            case 'iRelationTheme':
                $memcacheKey = $this->memcachePref . 'iRelationTheme';
                $ret = $this->cache->memcached->get($memcacheKey);
                if ($ret && !$Refresh && !$this->refresh && !$this->input->post('refresh')) {
                    return $ret;
                } else {
                    $ret = [];
                    $sql = 'SELECT iRelatedTopicsId as id, iRelatedTopicsPid as pid,sRelatedTopicsName as sName FROM `fashion`.`t_related_topics_db` WHERE `iDisplay`=1 ORDER BY iSort DESC';
                    $arr = $this->query($sql);
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
                    $this->cache->memcached->save($memcacheKey, $ret, $this->MemTime);
                    return $ret;
                }
                break;
            //lookbook栏目地区
            // case 'iArea':
            // require getenv('BASEPATH').'/category/popfashion/conf/category_conf.php';
            // $ret = $inlandAbroad;
            // break;
            // 城市 （1=>国内，2=>国外）
            case 'iRegion':
                $array = GetCategory::getRegionList();
                require getenv('BASEPATH') . '/category/popfashion/conf/city_conf.php';
                $ret[10001]['sName'] = '国内全部';
                $ret[10002]['sName'] = '国外全部';
                foreach ($array as $key => $val) {
                    $_sEnRegionPathName = explode(',', $val['sEnRegionPathName']);
                    if (array_intersect($_sEnRegionPathName, $config_f010_region['domestic'])) {
                        $ret[10001]['attrs'][$val['iRegionId']] = ['sName' => $val['sCnRegionName']];
                    } elseif (array_intersect($_sEnRegionPathName, $config_f010_region['foreign'])) {
                        $ret[10002]['attrs'][$val['iRegionId']] = ['sName' => $val['sCnRegionName']];
                    }
                }
                break;
            case 'sStarLabel':
                $memcacheKey = $this->memcachePref . 'sStarLabel';
                $ret = $this->cache->memcached->get($memcacheKey);
                if (!$ret || $Refresh || $this->refresh || $this->input->post('refresh')) {
                    $ret = [];
                    $sql = 'SELECT cat_id, cat_name, parent_id, is_show, is_hot, orderby, is_choice FROM `fashion`.`category` WHERE parent_id = 5169 AND is_show = 1 group by cat_name';
                    $arr = $this->query($sql);
                    foreach ($arr as $val) {
                        $ret["{$val['cat_id']}"] = $val['cat_name'];
                    }
                    $this->cache->memcached->save($memcacheKey, $ret, $this->MemTime);
                }
                return $ret;
                break;
            // 明星/ins(原：潮流领袖)
            case 'iStarIns':
                $memcacheKey = $this->memcachePref . 'ISTARINS_2020';
                $ret = $this->cache->memcached->get($memcacheKey);
                if (!$ret || $Refresh || $this->refresh) {
                    $ret = [];
                    $sql = $sql = 'SELECT iAttributeId,iAttributePid,sName,sEnName FROM `fashion`.`t_dict_attribute` WHERE iAttributePid = 12343 AND iType=36 AND `iStatus` = 0 ORDER BY iSort DESC' ;
                    $arr = $this->query($sql);
                    foreach ($arr as $val) {
                        $ret["{$val['iAttributeId']}"] = $val['sName'];
                    }
                    $this->cache->memcached->save($memcacheKey, $ret, $this->MemTime);
                }
                return $ret;
                break;
        }
        return $ret;
    }

    private function displayFilter($ret)
    {

    }

    /**
     * 根据字典表前台显示状态，显示各筛选项内容
     * fashion.t_dict_attribute
     * iDisplay 是否前台显示1-显示，0-不显示
     */
    private function getElementById($id)
    {
        if (!$id) {
            return [];
        }

        // 廓形 细节 工艺 图案 面料 辅料
        $element = GetCategory::getAttrValueByType(29, 1, 0, ['sName', 'iDisplay', 'iAttributeId']);

        $ret = array_column(
            array_filter($element[$id]['children'], function ($val) {
                return $val['iDisplay'];
            }), 'sName', 'iAttributeId');

        return $ret;
    }

    /**
     * 多维数组转变为一维数组
     * @param array $dataArr 需要转换的数组
     * @param string $field 简化的字段
     * @param bool $isFirst 多层数组，是否只取第一层
     * @return mixed
     */
    public function toOneArray($dataArr, $field = 'sName', $isFirst = false)
    {
        if (!$dataArr) {
            return [];
        }

        $res = [];

        foreach ($dataArr as $key => $val) {
            $res[$key] = $val[$field];
            if (!$isFirst && !empty($val['attrs'])) {
                $arr = $this->toOneArray($val['attrs']);
                $res += $arr;
            }
        }

        return $res;
    }

    private function dealGroupSolrDate($groupField, $data)
    {
        $list = [];

        foreach ($data["$groupField"]['groups'] as $value) {
            $list["{$value['groupValue']}"]['count'] = $value['doclist']['numFound'];
            foreach ($value['doclist']["docs"] as $val) {
                $arr['pri_id'] = $val['pri_id'];
                $arr['tablename'] = $val['tablename'];
                $arr['iColumnId'] = $val['iColumnId'];
                $list["{$value['groupValue']}"]['list'][] = $arr;
            }
        }

        return $list;
    }

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
}
