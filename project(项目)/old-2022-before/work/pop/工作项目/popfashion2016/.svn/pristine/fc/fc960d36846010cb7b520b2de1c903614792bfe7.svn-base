<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * References栏目 专用类
 * @property-read common_model $common_model
 */
class Patterns_model extends POP_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'trends_model']);
    }

    /**
     * [getLists 获取每页单张展示的数据+总条数]
     *
     * @param  string $params    [url参数]
     * @param  integer $columnId [栏目id]
     * @param  integer $offset   [偏移量]
     * @param  integer $limit    [每页条数]
     *
     * @return [array]   &$lists     [根据条件查询出的结果]
     * @return [array]   $totalCount [根据条件查询出的总条数]
     */
    public function getPatternsLists($params = '', $columnId, &$lists, $offset = 0, $limit = 10, $powers = array(), &$pageTurnList = array())
    {
        $this->benchmark->mark('getPatternsLists');
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
        //列表 iFirstColorFirstLevel 改取超过30% 当前色所有
        if (isset($conditions['iFirstColorFirstLevel'])) {
            $conditions['aAutoColors'] = $conditions['iFirstColorFirstLevel'];
            unset($conditions['iFirstColorFirstLevel']);
        } elseif (isset($conditions['iFirstColorSecondLevel'])) {
            $conditions['aAutoColors'] = $conditions['iFirstColorSecondLevel'];
            unset($conditions['iFirstColorSecondLevel']);
        }
        // 排序
        $arSort = $this->common_model->getSort($params, $powers, $columnPid);

        $result = [];
        $fls['fl'] = ['pop_id', 'pri_id', 'tablename', 'iColumnId'];
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $arSort, $fls);
        $collectStatusList = $this->getListDataCollectStatus($result);
        $this->load->model('details_model');
        foreach ($result as $key => $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];

            $_info = $this->details_model->getPicInfo($id, $tableName, $params, $columnId);
            $info = [
                'id' => $_info['id'],
                'title' => $_info['title'],
                'create_time' => $_info['create_time'],
            ];
            if (isset($_info['colorProportion'])) {
                $info['colorProportion'] = $_info['colorProportion'];
            }
            $imgPath = getImagePath($tableName, $_info);// 图片路径
            $info['cover'] = getFixedImgPath($imgPath['cover']);
            $info['columnId'] = $val['iColumnId'][1];
            $info['tableName'] = getProductTableName($tableName);
            $info['total'] = $totalCount;
            // 判断图片是否有遮罩
            $info['shade'] = $powers['shade'] && $powers['P_UserType'] != 4 ? $powers['shade'] : '';
            $info['iCollectStatus'] = array_search($val['pop_id'], $collectStatusList) === false ? 0 : 1; // 是否收藏
            $info['labels'] = $this->details_model->getLabelInfo($tableName, $id, $params, $columnId, 'list');

            // 图案素材、大牌花型列表项增加模拟成品入口
            if ($columnId == 82 || $columnId == 120) {
                $vParams = [
                    'act' => 'transfer',
                    'id' => $_info['id'],
                    't' => $info['tableName'],
                    'col' => $info['columnId'],
                ];
                $info['virtualUrl'] = '/details/virtualspl/?' . http_build_query($vParams);
            }

            $lists[] = $info;
        }
        $this->benchmark->mark('getPatternsListsEnd');

        return $totalCount;
    }

    //图案达观智能推荐
    public function patternRecommend($params, $columnId, $powers, $isAjax = true)
    {
        $lists = $info = [];

        $paramsArr = $this->common_model->parseParams($params, 1);
        $userId = getUserId();

        if ($userId) {

            $cond = [
                //用户id
                'userid' => $userId,
                // 开始项，用于翻页，默认为0并且关闭，返回结果会把用户历史推荐结果全部过滤
                'start' => 0,
                //默认取60个，最大64个
                'cnt' => 20,
            ];
            //请求达观个性化推荐接口
            $msg = POPDataGrandDeal::getRecGrandData($cond);

            if ($msg['status'] == 'OK') {
                $pop_ids = $msg['recdata'];
                if (!empty($pop_ids)) {
                    $totalCount = count($pop_ids);
                    if (!empty($pop_ids)) {
                        $lists = $this->dealDataGrand($pop_ids, $msg['request_id']);
                    }
                }
            }

        }

        // 判断是否要显示水印或遮罩
        if (in_array($columnId, array(82, 120, 124))) {
            $ret = isShowWatermark($paramsArr, ['isAjax' => true]);
        } else {
            $ret = array(
                'showMask' => false,
                'showWatermark' => false,
                'withConditions' => false
            );
        }
        $info = [
            'totalCount' => $totalCount,
            'page' => 0,
            'pageSize' => 20,
            'powers' => $powers,
            'showMask' => $ret['showMask'], // 是否显示遮罩
            'showWatermark' => $ret['showWatermark'], // 是否显示水印
            'withConditions' => $ret['withConditions'] // 是否有筛选条件
        ];


        if ($isAjax) {
            $jo = $this->getJsonOutputObj();
            $jo->code(0)->data($lists)->info($info)->msg('ok')->out();
        }

        return [$lists, $info];
    }

    //猜你喜欢
    public function getLikeDataByPopId($table, $id)
    {
        $jo = $this->getJsonOutputObj();
        $lists = [];

        $userId = getUserId();
        if (!$userId) {
            $jo->code(0)->data($lists)->msg('未登陆')->out();
        }

        if (!empty($table) && !empty($id) && in_array($table, ['product_graphicitem', 'product_fabricgallery_item'])) {

            $itemid = $table . '_' . $id;

            $cond = array(
                'userid' => $userId,
                'itemid' => $itemid,
                'scene_type' => '',
                'start' => 0,
                'cnt' => 20,
            );
            $msg = POPDataGrandDeal::getRelateGrandData($cond);

            if ($msg['status'] == 'OK') {
                $pop_ids = $msg['recdata'];

                if (!empty($pop_ids)) {
                    $lists = $this->dealDataGrand($pop_ids, $msg['request_id']);
                }
            }
        }

        $jo->code(0)->data($lists)->info(['recdata'=>count($pop_ids)])->msg('ok')->out();

    }

    //处理达观推荐数据
    public function dealDataGrand($pop_ids = array(), $request_id = '')
    {
        $paramArr = $lists = [];
        if (empty($pop_ids)) {
            return $lists;
        }

        $this->load->model('details_model');
        $iColumnId = 82;
        foreach ($pop_ids as $key => $val) {

            $pop_id = $val['itemid'];
            $table_name = substr($pop_id, 0, strripos($pop_id, '_'));
            $id = substr($pop_id, strripos($pop_id, '_') + 1);

            $_info = $this->details_model->getPicInfo($id, $table_name, $paramArr, $iColumnId);
            $imgPath = getImagePath($table_name, $_info);// 图片路径
            $info = [
                'id' => $_info['id'],
                'title' => $_info['title'],
                'create_time' => $_info['create_time'],
                'cover' => getFixedImgPath($imgPath['cover']),
                'columnId' => 82,
                'tableName' => getProductTableName($table_name),
                'request_id' => $request_id,
            ];

            $lists[] = $info;
        }

        return $lists;
    }



    /**
     * ajax列表数据
     *
     * @param string $params 接收的参数
     * @param int $columnId  栏目ID
     * @param mixed $powers  权限
     * @param bool $isAjax   是否是ajax请求
     *
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
        if ($columnId == 1) { // 图案专题
            $totalCount = $this->trends_model->getTrendLists($params, $columnId, $lists, $offset, $pageSize, $powers);
        } else {
            $totalCount = $this->getPatternsLists($params, $columnId, $lists, $offset, $pageSize, $powers);
        }

        // 判断是否要显示水印或遮罩
        if (in_array($columnId, array(82, 120, 124))) {
            // ·
            $ret = isShowWatermark($paramsArr, ['isAjax' => true]);
        } else {
            $ret = array(
                'showMask' => false,
                'showWatermark' => false,
                'withConditions' => false
            );
        }
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
            $jo = $this->getJsonOutputObj();
            $jo->code(0)->data($lists)->info($info)->msg('ok')->out();
        }
        return [$lists, $info];

    }

    /**
     * [getSelectItems 获取指定栏目有数据的所有查询条件]
     *
     * @param  array $selectItem [筛选条件]
     * @param  integer $columnId [栏目id]
     * @param  string $params    [url参数]
     *
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
                //工艺来源 市场工艺与品牌工艺
                case 'sCraftSource':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'protech');
                    break;
                //图案内容
                case 'sPatternContent':
                    if ($columnId == 121) {
                        $selectItems[$key] = $this->getItems($columnId, $params, $val, 'con');
                    } else {
                        $selectItems[$key] = $this->getItems($columnId, $params, $val, 'con', true);
                    }
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
     * [getConditions 获取solr查询的condition条件]
     *
     * @param          string    or array    $params     [url参数]
     * @param  integer $columnId [栏目id]
     *
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

        if ($columnId == 82) {
            $conditions['aWebsite'] = 1;
        }

        //性别 数码云打印没有性别
        if ($columnId != 121 && $columnId != 124) {
            $gender = $this->common_model->getGenderByRequest($params);
            $this->common_model->childGender($gender, $conditions);
        }

        // 没有权限检索(除了性别和行业)
        if (!empty($powers) && !$powers['P_Search']) {
            return $conditions;
        }

        // 只看收藏
        if ($paramsArr['coll'] && $columnId != 121) {
            $conditions['aCollectAccount'] = getUserId();
        }
        // 时间范围,0=>不限，1=>近7日,2=>近30日,3=>近半年
        if (isset($paramsArr['tim']) && $paramsArr['tim'] != 0) {
            $conditions['dCreateTime'] = $this->common_model->getTimeRange($paramsArr['tim']);
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
        // 设计素材-工艺来源(市场工艺与品牌工艺)
        if (isset($paramsArr['protech'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['protech'];
        }
        // 设计素材-图案内容
        if (isset($paramsArr['con'])) {
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

        // 品类
        if (isset($paramsArr['cat'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['cat'];
        }
        // 色系
        if (isset($paramsArr['aco'])) {
            //判断当前色系是一级还是二级，如果是一级根据一级色系查询，如果是二级则根据二级色系查询
            $this->load->model('styles_model');
            $color = $this->styles_model->getColorDict($paramsArr['aco']);
            if (!empty($color['iPid'])) { //二级色系
                $conditions['iFirstColorSecondLevel'] = $paramsArr['aco'];
            } else { //一级色系
                $conditions['iFirstColorFirstLevel'] = $paramsArr['aco'];
            }
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
     * 获取栏目介绍的内容
     *
     * @param $columnId 栏目ID
     *
     * @return string 栏目介绍
     */
    public function getColumnsPresentation($columnId)
    {
        switch ($columnId) {
            case '82':
            case '120':
            case '121':
            case '124':
            default:
                $columnPresentation = "图案花型栏目每周更新POP原创图案矢量稿和趋势文章，同步大牌的花型1:1矢量图，海量图案素材精选，以及工艺灵感照片和热门工艺实物照片。丰富的标签检索，迅速找到精确的图案内容。";
                break;
        }
        return $columnPresentation;
    }

    /**
     * [getSeoArray   获取seo搜索引擎，标题、关键字、描述]
     *
     * @param  integer $columnId [栏目id]
     *
     * @return string   [中文字符]
     */
    public function getSeoArray($columnId, $params = '')
    {
        //性别
        $gender = $this->common_model->getGenderByRequest($params);
        $gen = !empty($gender) ? trim(GetCategory::getOtherFromIds($gender, ['sName'])) : '';
        $paramsArr = [];
        if (!empty($params)) {
            $paramsArr = $this->common_model->parseParams($params, 1);
        }
        //季节
        $sea = $this->common_model->getDefaultParams('sea', $params, $columnId);
        //品牌
        $bra = $this->common_model->getDefaultParams('bra', $params, $columnId);

        //局部/满身
        $use = $this->common_model->getDefaultParams('use', $params, $columnId);
        //矢量/位图
        $for = $this->common_model->getDefaultParams('for', $params, $columnId);
        //图案内容
        $con = $this->common_model->getDefaultParams('con', $params, $columnId);
        //趋势专题
        $relt = $this->common_model->getDefaultParams('relt', $params, $columnId);
        //图案工艺
        $tec = $this->common_model->getDefaultParams('tec', $params, $columnId);
        //工艺来源（市场工艺与品牌工艺）
        $protech = $this->common_model->getDefaultParams('protech', $params, $columnId);
        //色系
        $aco = $this->common_model->getDefaultParams('aco', $params, $columnId);
        $aco = isset($aco['sName']) ? $aco['sName'] : "";
        //栏目名称
        $colName = GetCategory::getOtherFromColId($columnId, 'sName');
        $colName = $columnId == 'iSpecialTopicPatterns' ? '图案专题' : $colName;
        //测试获取时段信息
        $timArray = array('全部时段', '近7日', '近30日', '近半年');
        $sorArray = array(1 => "按时间最新", 2 => "按浏览最高", 3 => "按收藏最多");
        //最热门
        $ihot = $paramsArr['ihot'] ? '最热门' : '';
        /*    //大数据
        $auth = $paramsArr['auth']?'大数据':'';*/
        //配面料
        $uli = $paramsArr['uli'] ? '配面料' : '';
        //时段
        $jtime = $timArray[$paramsArr['tim']];
        //时间
        $sor = $sorArray[$paramsArr['sor']];

        // 数码云打印【时间段,按时间最新与按浏览最高】隐藏
        if ($columnId == 121) {
            unset($paramsArr['tim']);
            unset($paramsArr['sor']);
            $jtime = '';
            $sor = '';
        }

        //替换分页字段为空
        $preg = '/page_\d*/';
        $paramsArray = explode('-', $params);
        if (count($paramsArray) == 1) {
            $paramsArray[0] = preg_replace($preg, "", $paramsArray[0]);
        }
        $params = implode('-', $paramsArray);
        if (empty($params) && empty($gen)) {
            if ($columnId == 2) {
                $title = '服装设计图_服装素材_图案花型-POP服装趋势网';
                $description = 'POP服装趋势网的图案栏目汇集最新男装、女装、童装等服装设计图片，为您提供最新、最具代表性的服装设计效果图、服装款式细节图和设计师手稿图等，为您提供有价值的资讯服务。';
            } else {
                $title = $colName . '_图案-POP服装趋势网';
                $description = 'POP服装趋势网图案的' . $colName . '栏目汇集最新男装、女装、童装等服装设计图片，为您提供最新，最具代表性的服装设计效果图、服装款式细节图和设计师手稿图等，为您提供有价值的资讯服务。';

            }
            $keyInfo = [
//                2 => '图案素材,大牌花型,图案专题,图案工艺,数码云打印',
                82 => '图案素材,效果图,设计素材',
                120 => '大牌花型,图案花型',
                'iSpecialTopicPatterns' => '图案主题,图案专题',
                124 => '图案工艺,图案标识',
                121 => '数码云打印,数码打印,数码印刷',
            ];
            $keywords = $keyInfo[$columnId];
        } else {
            $jh = array_filter([$gen, $use, $for, $aco, $con, $bra, $sea, $relt, $ihot, $uli, $tec, $protech, $sor, $jtime]);

            $t = implode('_', $jh) . '_';
            $k = implode(',', $jh) . ',';
            $d = implode('/', $jh);
            if ($columnId == 2) {
                $title = $t . '图案-POP服装趋势网';
                $description = 'POP服装趋势网图案栏目为您提供' . $d . '最新、最前沿、最具权威的图案资讯，为您及时提供相关的T台、订货会、标杆品牌、市场和展会等的分析报告，为您提供有价值的服装设计资讯服务。';
            } else {
                $title = $t . $colName . '-POP服装趋势网';
                $description = 'POP服装趋势网' . $colName . '栏目汇集最新' . $d . '设计版型、模板、款式、素材等资讯内容，为您提供最新、最具代表性的服装设计资讯服务。';
            }
            $keywords = $k . $colName;
        }
        $seoArray[$columnId] = ['title' => $title, 'keywords' => $keywords, 'description' => $description];
        return $seoArray[$columnId];
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

    /*--------------------------饼图 start-----------------------------------*/

    public function pieData($params = '', $columnId, $powers = [])
    {
        //参数处理
        $paramsArr = [];
        $this->load->model('styles_model');
        $paramsArr = is_array($params) ? $params : $this->common_model->parseParams($params);;

        //返回按钮处理
        $colorBack = $categoryBack = '';
        $tips = $this->common_model->getTips($columnId, $params);
        if (isset($paramsArr['aco']) && !empty($paramsArr['aco'])) {
            $colorBack = $tips['aco']['backLink'];
        }
        if (isset($paramsArr['con']) && !empty($paramsArr['con'])) {
            $categoryBack = $tips['con']['backLink'];
        }
        //获取条件和关键字
        $conditions = $this->getConditions($params, $columnId, $powers);
        $keywords = $this->common_model->getKeyword('', $powers);
        $dataAnalysis = $this->styles_model->getColorAnalysisData($conditions, $columnId);
        $dataCategory = $this->getPatternContentPieData($paramsArr, $columnId, $conditions, $keywords);

        return ['analysis' => $dataAnalysis, 'category' => $dataCategory,
            'colorBack' => $colorBack, 'categoryBack' => $categoryBack];
    }


    public function getPatternContentPieData($paramsArr, $columnId, $conditions, $keywords)
    {
        $allContents = GetCategory::getSomeAttr(25, '', false);
        $arSort = array('dCreateTime' => 'DESC');
        $return = [];
        unset($conditions['dCreateTime']);
        if (empty($paramsArr['con'])) {
            $Contents = $allContents;
        } else {
            $Contents = array();
            foreach ($allContents as $val) {
                if ($val['iAttributeId'] == $paramsArr['con']) {
                    $Contents = $val['attrs'];
                }
            }
            if (empty($Contents)) {
                $sName = GetCategory::getOtherFromIds($paramsArr['con'], ['sName']);
                $Contents = [['iDisplay' => 1, 'iAttributeId' => $paramsArr['con'], 'sName' => trim($sName), 'dLastUpdateTime' => date('Y-m-d')]];
            }
        }
        foreach ($Contents as $val) {
            if ($val['iDisplay'] == 1 && strtotime($val['dLastUpdateTime']) > strtotime('-1 year')) {
                $_conditions = $conditions;
                $_conditions['other'][] = 'aLabelIds:' . $val['iAttributeId'];
                $count = POPSearch::wrapQueryPopFashionMerger($keywords, $_conditions, $res, 0, 1, $arSort);
                if ($count > 0) {
                    $return[] = ['id' => $val['iAttributeId'], 'name' => $val['sName'], 'value' => $count];
                }
            }
        }
        return json_encode($return);
    }

    /*--------------------------饼图 end-------------------------------------*/

    /**
     * TOP100专题页数据
     *
     * @param string $start
     * @param string $end
     * @param int $colId 82-图案素材 120-大牌花型
     *
     * @return array
     */
    public function getTopPatterns($start = '', $end = '', $colId = 82)
    {
        $this->load->driver('cache', ['adapter' => 'memcached']);
        if ($colId == 82) {
            $mem_key = 'POP_FASHION_TOPIC_TOP_PATTERNS_' . '181220' . $start;
        } else {
            $mem_key = 'POP_FASHION_TOPIC_TOP_PATTERNS_' . $colId . '_' . $start;
        }
        $returnData = $this->cache->memcached->get($mem_key);
        //加memcache缓存
        if (empty($returnData) || $this->input->get_post('refresh')) {
            $table = '`statistics`.`t_f_down`';
            $sql = "select DATE_FORMAT(dCreateTime,'%Y-%m') as y_m from {$table} where iSubColumnId={$colId} and sTableName ='product_graphicitem' GROUP BY y_m ORDER BY y_m desc;";
            $groupTime = $this->query($sql);
            if (empty($groupTime)) {
                return [];//没有数据
            }
            //没有选择月份，就用有数据且最新的月份数据；有选择月份，取当前选中的月份数据；
            if (!$start || !$end) {
                $start = $groupTime[0]['y_m'] . '-01';
                $end = date('Y-m-d', strtotime('+1 months', strtotime($groupTime[0]['y_m'])));
            }
            $sql = "select COUNT(1) as sort,sTableName,iPrid,iColumnId,iSubColumnId,CONCAT_WS('_',sTableName,iPrid) as pop_id,dCreateTime
  from {$table} where dCreateTime BETWEEN ? and ? and iSubColumnId={$colId} and sTableName ='product_graphicitem' GROUP BY 
  pop_id ORDER BY sort desc limit 100";
            $res = $this->query($sql, [$start, $end]);
            if (!$res) {
                return array();//没有数据
            }
            $sorts = $idToTable = [];
            foreach ($res as $val) {
                $data[$val['sTableName']][] = $val['iPrid'];
                $idToTable[$val['iPrid']] = $val['sTableName'];
                $sorts[$val['iPrid']] = $val['sort'];
            }
            foreach ($data as $t => $ids) {
                $info[] = OpPopFashionMerger::getProductData($ids, $t);
            }
            $res = array();
            foreach ($info as $val) {
                foreach ($val as $v) {
                    if (!empty($v)) {
                        $res[] = $v;
                    }
                }
            }
            //处理列表信息
            foreach ($res as $key => $_info) {
                $top = false;
                if ($key <= 3) {
                    $top = ++$key;
                }
                //列表信息
                $id = $_info['id'];
                $tableName = $idToTable[$id];
                $imgPath = getImagePath($tableName, $_info);// 图片路径
                $cover = getFixedImgPath($imgPath['cover']);
                $_table = getProductTableName($tableName);
                $list[] = [
                    'id' => $id,
                    'small' => $cover,
                    'link' => "/details/style/t_{$_table}-id_{$id}-col_{$colId}/",
                    'link2' => "http://m.pop136.com/searchDetail.html?id={$id}",// 找图网使用m.pop136.com
                    'cnt' => $sorts[$id],
                    'top' => $top,
                ];
            }
            $returnData = compact('groupTime', 'list');
            $this->cache->memcached->save($mem_key, $returnData, 24 * 3600 * 30);
        }
        return $returnData;
    }


}

