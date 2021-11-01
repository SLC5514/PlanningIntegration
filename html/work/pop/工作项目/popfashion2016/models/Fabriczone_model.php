<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 面料专区页调用类
 * Class Fabriczone_model
 * @property-read common_model $common_model
 */
class Fabriczone_model extends POP_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('category_model');
    }

    /**根据栏目ID,子栏目,视角获取专区数据
     * @param $iColumnId 栏目ID
     * @param string $col 子栏目
     * @param string $labelId 视角
     * @param string $iHot 热门
     * @param string $limit
     * @param $iIsFabriczone 是否推荐到面料专区
     * @return array|mixed
     */
    public function getColData($iColumnId, $labelId = '', $iHot = 1, $limit = '', $iIsFabriczone = 0)
    {
        // 排序
        $arSort = ['dCreateTime' => 'DESC', 'pri_id' => 'DESC'];

        if ($iColumnId == 2) {
            //街拍分析
            $conditions = $this->getConditions(38, $labelId, $iHot, $iIsFabriczone);
            $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, 0, 1, $arSort);
            if ($totalCount) {
                $data4 = $this->foreachRes(38, '', $result);
            }

            // 取“潮流解析\T台分析”子栏目下，单选“面料”视角的报告中
            $conditions = $this->getConditions(30, 16, $iHot, $iIsFabriczone);
            $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result_1, 0, $limit, $arSort);
            if ($totalCount) {
                $data1 = $this->foreachRes(30, '16', $result_1);
            }

            //展会分析
            $conditions = $this->getConditions(31, $labelId, $iHot, $iIsFabriczone);
            $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, 0, 1, $arSort);
            if ($totalCount) {
                $data2 = $this->foreachRes(31, '', $result);
            }
            //订货会分析
            $conditions = $this->getConditions(32, $labelId, $iHot, $iIsFabriczone);
            $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, 0, $limit, $arSort);
            if ($totalCount) {
                $data3 = $this->foreachRes(32, '', $result);
            }
            //市场分析
            $conditions = $this->getConditions(33, $labelId, $iHot, $iIsFabriczone);
            $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, 0, 1, $arSort);
            if ($totalCount) {
                $data5 = $this->foreachRes(33, '', $result);
            }
            $data = [$data1, $data2, $data3, $data4, $data5];

        } else {
            $conditions = $this->getConditions($iColumnId, $labelId, $iHot);
            if ($iIsFabriczone == 1) {
                $conditions['iIsFabriczone'] = 1;
            }
            $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, 0, $limit, $arSort);

            if ($totalCount) {
                $data = $this->foreachRes($iColumnId, $labelId, $result);
            }
        }
        return $data;
    }

    public function getConditions($col, $labelId = '', $iHot = 1, $iIsFabriczone = '')
    {
        // 条件构造
        $conditions = [];
        $conditions['iColumnId'] = $col;
        if ($col == 82) {
            $conditions['aWebsite'] = 1;
        }
        $columnPid = GetCategory::getOtherFromColId($col);
        switch ($columnPid) {
            case 1:
                if (!empty($labelId))
                    $conditions['other'][] = 'sVisualAngle:' . $labelId;
                break;
            case 2:
                if ($iIsFabriczone == 1) {
                    $conditions['iIsFabriczone'] = $iIsFabriczone;
                }
                break;
            case 6:
                $conditions['iDisplay'] = 1;
                if (!empty($labelId))
                    $conditions['other'][] = 'sContentDirection:' . $labelId;
                break;
            case 7:
                $conditions['aWebsite'] = 1;
                break;
        }
        return $conditions;
    }


    /*返回全部数据*/
    public function getFabricData($refresh = '')
    {

        $this->load->driver('cache');
        $memcache_obj = OpMemcache::getInstance();
        $mem_key = 'fabriczone_fabric';
        $data = $this->cache->memcached->get($mem_key);

        if (!$data || $refresh) {
            /*趋势预测--色彩*/
            $forecastData_1 = $this->getColData(125, '', 0, 1, 1);
            $data[1]['data'] = $forecastData_1;

            /*趋势预测--面料*/
            $forecastData_2 = $this->getColData(127, '', 0, 1, 1);
            $data[2]['data'] = $forecastData_2;

            /*趋势预测--图案*/
            $forecastData_3 = $this->getColData(126, '', 0, 1, 1);
            $data[3]['data'] = $forecastData_3;

            /*快反应--面料*/
            $capsulesData_1 = $this->getColData(20, '', 0, 1, 1);
            $data[5]['data'] = empty($capsulesData_1[0]) ? [] : [$capsulesData_1[0]];
            // $data[6]['data'] = empty($capsulesData_1[1])?[]:[$capsulesData_1[1]];

            /*流行分析--面料*/
            $analysisData = $this->getColData(2, 16, 1, 1, 1);
            $data[4]['data'] = $analysisData;

            /*趋势手稿--色彩*/
            $storeData_1 = $this->getColData(70, 11444, '', 2);
            $data[10]['data'] = $storeData_1;
            /*趋势手稿--面料*/
            $storeData_2 = $this->getColData(70, 11445, '', 2);
            $data[11]['data'] = $storeData_2;
            /*趋势手稿--图案*/
            $storeData_3 = $this->getColData(70, 11446, '', 2);
            $data[12]['data'] = $storeData_3;

            /*图案素材*/
            $graphicsData = $this->getColData(82, '', '', 6);
            $data[8]['data'] = $graphicsData;

            /*大牌花型*/
            $topbrandsData = $this->getColData(120, '', '', 6);
            $data[7]['data'] = $topbrandsData;

            /*展会面料*/
            $fabricgalleryData = $this->getColData(117, '', '', 6);
            $data[9]['data'] = $fabricgalleryData;

            $this->cache->memcached->save($mem_key, $data, 7200);
        }

        return $data;

    }

    /**
     * 遍历数据
     * @param $col      子栏目ID
     * @param $labelId 视角类型
     * @param $result 遍历的数据
     * @return mixed
     */
    public function foreachRes($col, $labelId, $result)
    {
        $res = [];
        foreach ($result as $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];
            $data['tableName'] = getProductTableName($tableName);
            $tableInfo = OpPopFashionMerger::getProductData($id, $tableName);
            $info = $tableInfo[$id];
            $data['id'] = $id;
            $data['columnId'] = $col;

            switch ($col) {
                case 70: // 手稿合辑前台显示标题
                    if (isset($info['name_text'])) {
                        $data['title'] = $info['name_text'];
                    } else {
                        $data['title'] = isset($info['title']) ? $info['title'] : $info['sTitle'];
                    }
                    break;
                case 117://展会面料标题
                    //标题
                    $this->load->model('details_model');
                    // 展会名称
                    $marketName = $info['iExhibitionName'] != 3488 ? $this->details_model->getMarketName($info['iExhibitionName']) : '';
                    //面料工艺
                    $info['sFabricCraft'] = trim(GetCategory::getOtherFromIds($info['sFabricCraft'], ['sName']));
                    //面料材质
                    $info['sMaterial'] = trim(GetCategory::getOtherFromIds($info['sMaterial'], ['sName']));
                    //图案内容
                    $sPatternContent = trim(GetCategory::getOtherFromIds($info['sPatternContent'], ['sName']));
                    //标题
                    $data['title'] = trim($marketName . ' ' . $info['sMaterial'] . ' ' . $info['sFabricCraft'] . ' ' . $sPatternContent);
                    break;
                case 82:
                case 120:
                    if (isset($info['memo'])) {
                        $data['title'] = $info['memo'];
                    }
                    break;
                default:
                    $data['title'] = isset($info['title']) ? $info['title'] : $info['sTitle'];
                    break;

            }
            $data['title'] = htmlspecialchars($data['title']);

            $imagePath = getImagePath($tableName, $info);
            $this->load->model('details_model');
            $data['cover'] = getFixedImgPath($imagePath['cover']);
            $data['cover'] = $this->details_model->getImgfPath($data['cover'], true);

            $data['iPreviewMode'] = isset($info['iPreviewMode']) ? $info['iPreviewMode'] : '';
            // 视角标签
            if (in_array($labelId, [14, 15, 16])) {
                $type = 'vis';
            }
            if (in_array($labelId, [11444, 11445, 11446])) {
                $type = 'cont';
            }

            $params = '';
            // 33--市场分析 20--快反应 38--街拍分析（明星/街拍）
            if ($col == 33) { // 流行分析--市场分析栏目
                $params = 'uli_1';// 选中面料标签的标识
            }

            // 更多的链接
            $data['sLink'] = $this->common_model->getLink($col, $params, $type, $labelId);
            //图片详情页地址
            $data['detailLink'] = $this->getDetailLink($data['tableName'], $id, $col, $data['iPreviewMode']);
            $res[] = $data;
        }
        return $res;
    }

    /**获取图片详情页地址
     * @param $tablename
     * @param $id
     * @param $colId
     * @param string $iPreviewMode 书稿合籍用到的条件
     * @return string
     */
    public function getDetailLink($tablename, $id, $colId, $iPreviewMode = '')
    {
        $data['detailLink'] = '';
        switch ($colId) {
            case 82 ://图案素材
            case 117 ://展会面料
            case 120 ://大牌花型
                $data['detailLink'] = "/details/style/t_{$tablename}-id_{$id}-col_{$colId}/";
                break;
            case 30 ://T台分析
            case 32 ://订货会分析
            case 33 ://市场分析
            case 20 ://快反应
            case 22 ://趋势预测
            case 31 ://展会分析
            case 37 ://街拍分析
            case 38 ://街拍分析
            case 125:
            case 126:
            case 127:
                $data['detailLink'] = "/details/report/t_{$tablename}-id_{$id}-col_{$colId}/";
                break;
            case 70 ://趋势手稿
                $data['detailLink'] = "/books/seclist/t_{$tablename}-id_{$id}-col_{$colId}-yl_{$iPreviewMode}/";
                break;

        }
        return $data['detailLink'];
    }


}