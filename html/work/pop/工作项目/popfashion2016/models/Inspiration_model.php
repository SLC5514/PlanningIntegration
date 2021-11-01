<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Inspiration栏目 专用类
 * @property-read common_model $common_model
 */
class Inspiration_model extends POP_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'statistics_model']);
    }

    /**
     * [getInspirations 获取灵感源页面左侧显示的灵感源类型中英文及url地址]
     * @param  int $columnId [用于构造url]
     * @param  string $params [用于构造url]
     * @return array  $types
     */
    public function getInspirations($columnId, $params = '')
    {
        $this->benchmark->mark('getInspirations');
        if (!empty($params)) {
            $paramsArr = $this->common_model->parseParams($params);
            if (isset($paramsArr['type'])) {
                return [];
                unset($paramsArr['type']);
            }
            $params = $this->common_model->parseParams($paramsArr, 2);
        }
        $inspirationTypes = GetCategory::getSomeAttr(12);
        $types = [];
//		$types['all'] = ['sName' => '全部', 'sEnName' => 'ALL', 'sLink' => $this->common_model->getLink($columnId, $params),];

        foreach ($inspirationTypes as $key => $val) {
            // 11213=>科技，11214=>杂志精选, 暂时隐藏
            if ($key == 11213 || $key == 11214) {
                continue;
            }
            $types[$key]['sName'] = $val;
            $types[$key]['sEnName'] = GetCategory::getOtherFromIds($key, ['sEnName']);
            $types[$key]['sLink'] = $this->common_model->getLink($columnId, $params, 'type', $key);
        }
        $this->benchmark->mark('getInspirationsEnd');
        return $types;
    }

    /**
     * [getInspirationLists 获取每页单张展示的数据+总条数]
     * @param  string $params [url参数]
     * @param  integer $columnId [栏目id]
     * @param  integer $offset [偏移量]
     * @param  integer $limit [每页条数]
     * @return [array]   &$lists     [根据条件查询出的结果]
     * @return [array]   $totalCount [根据条件查询出的总条数]
     */
    public function getInspirationLists($params = '', $columnId, &$lists, $offset = 0, $limit = 10, $powers = array())
    {
        $this->benchmark->mark('getInspirationLists');
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
        foreach ($result as $key => $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];
            $data = OpPopFashionMerger::getProductData($id, $tableName);
            $info = $data[$id];
            // 发布时间和获取浏览量
            switch ($tableName) {
                case 'mostrend_content':
                    $info['create_time'] = $info['release_time'];
                    $info['abstract'] = $info['title_describe'];// 摘要
                    break;
                case 't_inspiration_img_db':
                    $info['create_time'] = $info['dCreateTime'];
                    break;
                case 't_trend_report':
                    $info['create_time'] = $info['dCreateTime'];
                    $info['abstract'] = $info['sDesc'];// 摘要
                    $info['title'] = $info['sTitle'];// 标题
                    break;
                default:
                    $info['create_time'] = $info['create_time'];
                    break;
            }
            $info['view_count'] = $this->statistics_model->getViews($tableName, $id, $info);// 浏览量

            // 灵感源类型
            if (isset($info['iInspirationType'])) {
                $info['inspirationTypeName'] = GetCategory::getOtherFromIds($info['iInspirationType'], ['sName']);
            }
            // 摘要
            if (isset($info['description'])) {
                $info['abstract'] = $info['description'];
            }
            $info['title'] = htmlspecialchars(stripcslashes($info['title']));
            $info['abstract'] = htmlspecialchars(stripcslashes($info['abstract']));
            // 图片路径
            $imgPath = [];
            $imgPath = getImagePath($tableName, $info);
            $info['cover'] = getFixedImgPath($imgPath['cover']);
            $lists[$key]['list'] = $info;

            // 如果栏目是父级栏目则获取栏目名称
            if (count($val['iColumnId']) > 1 && $columnId == $val['iColumnId'][0]) {
                //栏目中文名
                $lists[$key]['columnName'] = GetCategory::getOtherFromColId($val['iColumnId'][1], 'sName');
            }
            $lists[$key]['columnId'] = $val['iColumnId'][1];
            $lists[$key]['tableName'] = getProductTableName($tableName);
            $lists[$key]['offset'] = $offset++;
            $lists[$key]['total'] = $totalCount;
            // 判断图片是否有遮罩
            $lists[$key]['shade'] = $powers['shade'] && $powers['P_UserType'] != 4 ? $powers['shade'] : '';
            $lists[$key]['iCollectStatus'] = array_search($val['pop_id'], $collectStatusList) === false ? 0 : 1; // 是否收藏
        }
        $this->benchmark->mark('getInspirationListsEnd');
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
        //灵感源类型,灵感源特殊处理,类型作为子栏目
        if (isset($paramsArr['type'])) {
            $conditions['sCommonTag'] = $paramsArr['type'];
        }
        // 只看收藏
        if (isset($paramsArr['coll']) && $paramsArr['coll']) {
            $conditions['aCollectAccount'] = getUserId();
        }
        // 时间范围,0=>不限，1=>近7日,2=>近30日,3=>近半年
        if (isset($paramsArr['tim']) && $paramsArr['tim'] != 0) {
            $conditions['dCreateTime'] = $this->common_model->getTimeRange($paramsArr['tim']);
        }
        // 关键字检索
        $keyword = $this->common_model->getKeyword($params);
        if (strpos($keyword, '?key=') === 0) {
            $keyword = ltrim($keyword, '?key=');
        }
        if ($keyword) {
            $conditions['other'][] = 'combine:(' . $keyword . ')';
        }
        return $conditions;
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
        $totalCount = $this->getInspirationLists($params, $columnId, $lists, $offset, $pageSize, $powers);

        $info = [
            'totalCount' => $totalCount,
            'page' => $page,
            'pageSize' => $pageSize,
            'powers' => $powers,
        ];

        $this->benchmark->mark('ajaxGetListEnd');

        if ($isAjax) {
            $jsonOutput = $this->getJsonOutputObj();
            $jsonOutput->code(0)->data($lists)->info($info)->msg('ok')->out();
        }

        return [$lists, $info];
    }

    /**
     * [getSeoArray   获取seo搜索引擎，标题、关键字、描述]
     * @param  integer $columnId [栏目id]
     * @return string   [中文字符]
     */
    public function getSeoArray($columnId, $params = '')
    {
        $paramsArr = [];
        if (!empty($params)) {
            $paramsArr = $this->common_model->parseParams($params, 1);
        }
        //灵感源类型
        $type = $this->common_model->getDefaultParams('type', $params, $columnId);
        $timArray = array('全部时段', '近7日', '近30日', '近半年');
        $sorArray = array(1 => "按时间最新", 2 => "按浏览最高", 3 => "按收藏最多");
        //时段
        $jtime = $timArray[$paramsArr['tim']];
        //时间
        $sor = $sorArray[$paramsArr['sor']];
        //栏目名称
        $colName = GetCategory::getOtherFromColId($columnId, 'sName');
        $_colName = $columnId == 90 || $columnId == 91 ? $colName . '_灵感源' : $colName;
        if (empty($params)) {
            $title = $_colName . '_素材-POP服装趋势网';
            $description = 'POP服装趋势网' . $colName . '栏目汇集最新男装、女装、童装等服装设计图片，为您提供最新、最具代表性的服装款式细节图片和服装设计效果图等，为您提供有价值的服装设计资讯服务。';
            $keyInfo = [
                90 => '灵感源,服装设计灵感',
                91 => '灵感源,服装设计灵感',
                116 => '灵感视频,设计灵感'
            ];
            $keywords = $keyInfo[$columnId];
        } else {
            $jh = array_filter([$type, $jtime, $sor, $colName]);
            $t = implode('_', $jh) . '_';
            $k = implode(',', $jh) . ',';
            $d = implode('/', $jh);
            if ($columnId == 90 || $columnId == 91) {
                $t .= '灵感源';
                $k .= '灵感源';
                $colName = '灵感源';
            }
            $title = $t . '-POP服装趋势网';
            $keywords = $k;
            $description = 'POP服装趋势网' . $colName . '栏目汇集服装设计版型、模板、款式、图案素材等最新' . $d . '资讯内容，为您提供最新、最具代表性的服装设计' . $d . '资讯服务。';
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
            case '116':
            case '90':
            case '91':
            case '8':
                $columnPresentation = "全球权威面辅料、原材料展会，精选下一季最新面料工艺，推荐优质面辅料供应商。";
                break;
            default:
                $columnPresentation = "全球权威面辅料、原材料展会，精选下一季最新面料工艺，推荐优质面辅料供应商。";
                break;
        }
        return $columnPresentation;
    }
}
