<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * References栏目 专用类
 * @property-read common_model $common_model
 */
class References_model extends POP_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
    }

    /**
     * [getLists 获取每页单张展示的数据+总条数]
     * @param  string $params [url参数]
     * @param  integer $columnId [栏目id]
     * @param  integer $offset [偏移量]
     * @param  integer $limit [每页条数]
     * @return [array]   &$lists     [根据条件查询出的结果]
     * @return [array]   $totalCount [根据条件查询出的总条数]
     */
    public function getReferenceLists($params = '', $columnId, &$lists, $offset = 0, $limit = 10, $powers = array())
    {
        $this->benchmark->mark('getReferenceLists');
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

        $result = [];
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $arSort);
        $collectStatusList = $this->getListDataCollectStatus($result);
        $this->load->model('details_model');
        foreach ($result as $key => $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];

            $_info = $this->details_model->getPicInfo($id, $tableName, $params, $columnId);
            $info = [
                'id' => $_info['id']
            ];
            $info['create_time'] = $_info['create_time'];
            $info['date'] = date('Y-m-d', strtotime($_info['create_time']));
            $info['updateTime'] = $info['date'];
            $info['isNew'] = date('Y-m-d', time()) == $info['date'];
            // $info['o'] = $_info;
            // 图片路径
            $imgPath = getImagePath($tableName, $_info);
            $info['cover'] = getFixedImgPath($imgPath['cover']);
            $info['columnId'] = $val['iColumnId'][1];
            $info['tableName'] = getProductTableName($tableName);
            $info['total'] = $totalCount;
            // 判断图片是否有遮罩
            $info['shade'] = $powers['shade'] && $powers['P_UserType'] != 4 ? $powers['shade'] : '';
            $info['iCollectStatus'] = array_search($val['pop_id'], $collectStatusList) === false ? 0 : 1; // 是否收藏
            $info['labels'] = $this->details_model->getLabelInfo($tableName, $id, $params, $columnId, 'list');
            $lists[] = $info;
        }
        $this->benchmark->mark('getReferenceListsEnd');
        return $totalCount;
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
        $pageSize = getRequestPageSize(20);
        $offset = ($page - 1) * $pageSize;

        $lists = [];
        $totalCount = $this->getReferenceLists($params, $columnId, $lists, $offset, $pageSize, $powers);

        // 判断是否要显示水印或遮罩
        $ret = isShowWatermark($paramsArr, ['isAjax' => true]);
        $info = [
            'totalCount' => $totalCount,
            'page' => $page,
            'pageSize' => $pageSize,
            'powers' => $powers,
            'showMask' => $ret['showMask'], // 是否显示遮罩
            'showWatermark' => $ret['showWatermark'], // 是否显示水印
            'withConditions' => $ret['withConditions'] // 是否有筛选条件
        ];

        $this->benchmark->mark('ajaxGetListEnd');

        if ($isAjax) {
            $jsonOutput = $this->getJsonOutputObj();
            $jsonOutput->code(0)->data($lists)->info($info)->msg('ok')->out();
        }

        return [$lists, $info];
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
        if ($columnId == 117) {
            $conditions['aWebsite'] = 1; // 服装网显示(面料-117)
        }
        // 性别(84=>服饰品, 117=>展会面料)括号内栏目没有性别
        if (!in_array($columnId, [84, 117])) {
            // 性别
            $gender = $this->common_model->getGenderByRequest($params);
            $this->common_model->childGender($gender, $conditions);
        }
        // 行业,(84=>服饰品,85=>店铺陈列)括号内栏目没有行业
        if (!in_array($columnId, [84, 85, 117])) {
            // 行业
            $industry = $this->common_model->getIndustryByRequest($params);
            if ($industry) {
                $conditions['other'][] = 'aLabelIds:' . $industry;
            }
        }
        // 只看收藏
        if (isset($paramsArr['coll']) && $paramsArr['coll']) {
            $conditions['aCollectAccount'] = getUserId();
        }
        //pop快印
        if (isset($paramsArr['popky'])) {
            $conditions['iQuickPrint'] = $paramsArr['popky'];
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
            $conditions['other'][] = "(iRegion:{$region} OR iArea:{$region} OR iContinent:{$region} OR  iCountry:{$region})";
        }
        // 品牌
        if (isset($paramsArr['bra'])) {
            $conditions['iBrand'] = intval($paramsArr['bra']);
        }
        // 设计素材-图案应用
        if (isset($paramsArr['use'])) {
            $conditions['aPatternUse'] = $paramsArr['use'];
        }
        // 设计素材-图案格式
        if (isset($paramsArr['for'])) {
            $conditions['aPatternFormat'] = $paramsArr['for'];
        }
        // 设计素材-图案工艺
        if (isset($paramsArr['tec'])) {
            $conditions['aPatternTechnology'] = $paramsArr['tec'];
        }
        // 设计素材-图案内容
        if (isset($paramsArr['con'])) {
            //$conditions['aPatternContent'] = $paramsArr['con'];
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['con'];//图案素材改走精确查找
        }
        // 设计素材-面料工艺
        if (isset($paramsArr['cra'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['cra'];
        }
        // 设计素材-面料材质
        if (isset($paramsArr['mat'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['mat'];
        }
        // 设计素材-展会名称
        if (isset($paramsArr['exh'])) {
            $conditions['sExhibitionName'] = $paramsArr['exh'];
        }
        // 设计素材-款式部位
        if (isset($paramsArr['par'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['par'];
        }
        // 季节
        if (isset($paramsArr['sea'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['sea'];
        }
        // 品类
        if (isset($paramsArr['cat'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['cat'];
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
                //季节
                case 'iSeason':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'sea');
                    break;
                //图案应用
                case 'sPatternUse':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'use');
                    break;
                //图案格式
                case 'sPatternFormat':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'for');
                    break;
                //图案工艺
                case 'sPatternTechnology':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'tec');
                    break;
                //图案内容
                case 'sPatternContent':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'con', true);
                    break;
                // 面料工艺
                case 'sFabricCraft':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'cra');
                    break;
                // 面料材质
                case 'sMaterial':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'mat');
                    break;
                // 面料展
                case 'sExhibitionName':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'exh', true);
                    break;
                //款式部位
                case 'sStylePart':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'par');
                    break;
                //单品（没有品名的栏目）
                case 'sCategory':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'cat');
                    break;
                case 'sGender':
                    if ($columnId == 82 || $columnId == 117) {
                        $val = array_intersect([3 => '男童', 4 => '女童'], $val);
                    }
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'gcen');
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
     * [getSeoArray   获取seo搜索引擎，标题、关键字、描述]
     * @param  integer $columnId [栏目id]
     * @return string   [中文字符]
     */
    public function getSeoArray($columnId, $params = '')
    {
        //性别
        $gen = '';
        if ($columnId !== 117) {
            $gender = $this->common_model->getGenderByRequest($params);
            $gen = !empty($gender) ? trim(GetCategory::getOtherFromIds($gender, ['sName'])) : '';
        }
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
        if (isset($paramsArr['cat'])) {
            $arr = GetCategory::getOtherFromIds($paramsArr['cat'], ['sName', 'iAttributePid'], 'array');
            $subcat = $arr['sName'][0];
            if ($arr['iAttributePid'][0] == '11115') {
                $catId = GetCategory::getCatIdsBySubcatIds($paramsArr['cat']);
                $cat = GetCategory::getOtherFromIds($catId, ['sName']);
            } else {
                $cat = $subcat;
                $subcat = '';
            }
        } else {
            $cat = $subcat = '';
        }
        //地区
        $reg = $this->common_model->getDefaultParams('reg', $params, $columnId);
        //季节
        $sea = $this->common_model->getDefaultParams('sea', $params, $columnId);
        //品牌
        $bra = $this->common_model->getDefaultParams('bra', $params, $columnId);
        //款式部位
        $par = $this->common_model->getDefaultParams('par', $params, $columnId);
        /*//图案应用
        $use = $this->common_model->getDefaultParams('use', $params, $columnId);
        //图案格式
        $for = $this->common_model->getDefaultParams('for', $params, $columnId);
        //图案工艺
        $tec = $this->common_model->getDefaultParams('tec', $params, $columnId);*/
        //图案内容
        $con = $this->common_model->getDefaultParams('con', $params, $columnId);
        // 面料工艺
        $cra = $this->common_model->getDefaultParams('cra', $params, $columnId);
        // 面料材质
        $mat = $this->common_model->getDefaultParams('mat', $params, $columnId);
        //展会名称
        $exh = $this->common_model->getDefaultParams('exh', $params, $columnId);
        /*// 面料展
        $exh = $this->common_model->getDefaultParams('exh', $params, $columnId);*/
        //栏目名称
        $colName = GetCategory::getOtherFromColId($columnId, 'sName');
        if (empty($params) && empty($gen) && empty($ind)) {
            if ($columnId == 4) {
                $title = '服装杂志_手稿合辑-POP服装趋势网';
                $description = 'POP服装趋势网读物栏目汇集服装相关的杂志、期刊、画册等资讯内容，为您提供最新、最具代表性的封面、手册、手稿等细节图片和素材，为您提供有价值的资讯服务。';
            } else {
                $title = $colName . '_素材-POP服装趋势网';
                $description = 'POP服装趋势网' . $colName . '栏目汇集最新男装、女装、童装等服装设计图片，为您提供最新、最具代表性的服装款式细节图片和服装设计效果图等，为您提供有价值的服装设计资讯服务。';
            }
            $keyInfo = [
                80 => '款式模板,款式样板,设计手稿',
                81 => '款式细节,服装工艺',
                85 => '店铺陈列,店铺装饰',
                117 => '展会面料,布料',
                84 => '服饰品,首饰,装饰品'
            ];
            $keywords = $keyInfo[$columnId];
        } else {
            $jh = array_filter([$gen, $ind, $sea, $exh, $bra, $cat, $subcat, $reg, $par, $mat, $cra, $con]);
            $t = implode('_', $jh) . '_';
            $k = implode(',', $jh) . ',';
            $d = implode('/', $jh);
            if ($columnId == 4) {
                $title = $t . '读物-POP服装趋势网';
                $description = 'POP服装趋势网读物栏目汇集' . $d . '相关的杂志、期刊、画册、矢量手稿等资讯内容和细节图片及素材，为您提供有价值的' . $d . '资讯服务。';
            } else {
                $title = $t . $colName . '-POP服装趋势网';
                $description = 'POP服装趋势网' . $colName . '栏目汇集服装设计版型、模板、款式、图案素材等最新' . $d . '资讯内容，为您提供最新、最具代表性的服装设计' . $d . '资讯服务。';
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
            case '117':
                $columnPresentation = "全球权威面辅料、原材料展会，精选下一季最新面料工艺，推荐优质面辅料供应商。";
                break;
            case '80':
            case '81':
            case '82':
            case '84':
            case '85':
            default:
                $columnPresentation = "全球权威面辅料、原材料展会，精选下一季最新面料工艺，推荐优质面辅料供应商。";
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
}

