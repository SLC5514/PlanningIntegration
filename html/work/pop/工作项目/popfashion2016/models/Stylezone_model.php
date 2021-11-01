<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * 款式首页
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/20
 * Time: 14:15
 * @property-read common_model $common_model
 */
class Stylezone_model extends POP_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');

    }

    /**
     * [getStyleLists 获取款式库每页单张展示的数据+总条数]
     * @param  string $params [url参数]
     * @param  integer $offset [偏移量]
     * @param  integer $limit [每页条数]
     * @return [array]   &$lists     [根据条件查询出的结果]
     * @return [array]   $totalCount [根据条件查询出的总条数]
     */
    public function getList($params = '', &$lists, $offset = 0, $limit = 30, $powers = array())
    {
        $this->benchmark->mark('getGroupLists');
        $paramsArr = [];
        $group = 'sBrand';//品牌展示形式
        if (!empty($params)) {
            $paramsArr = $this->common_model->parseParams($params);
        }
        $return = [];
        $columnId = 4;  // 款式库
        $columnPid = 4;  // 款式库

        // 条件
        $conditions = $this->getConditions($params, $columnId, $powers);
        $conditions['sBrand'] = '{0 TO *}';
        $conditions['iColumnId'] = [50, 52, 54, 55, 122, 123];//排除街拍图库和潮流领袖

        // 排序
        $arSort = array('dCreateTime' => 'DESC', 'pri_id' => 'DESC');

        $result = [];
        $return = [];

        $this->load->model('category_model');
        $totalCount = $this->category_model->getGroupList('', $group, $conditions, $result, $arSort, $offset, $limit);
        foreach ($result as $key => $val) {
            $return[$key]['count'] = $val['count'];
            if (!empty($val['list'])) {
                foreach ($val['list'] as $k => $v) {
                    $id = $v['pri_id'];
                    $tableName = $v['tablename'];
                    $data = OpPopFashionMerger::getProductData($id, $tableName);
                    $info = $data[$id];
                    $info['id'] = $id;
                    $info['tableName'] = getProductTableName($tableName);
                    // 图片路径
                    $imgPath = getImagePath($tableName, $info);
                    $info['cover'] = getFixedImgPath($imgPath['cover']);


                    $return[$key]['list'][] = $info;
                    $return[$key]['columnId'] = $v['iColumnId'][1];
                    $return[$key]['offset'] = $offset++;
                    if (empty($tmpName[$key])) {
                        $tmpName[$key] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
                    }
                }
                if (empty($return[$key]['list'][0])) {
                    $return[$key]['count'] = 0;
                    continue;
                }
                if (isset($paramsArr['bra'])) {
                    $groupId = $paramsArr['bra'];
                } else {
                    $groupId = $key;
                }
                $brandInfo = OpPopFashionMerger::getBrandData(intval($key));
                $return[$key]['name'] = $brandInfo['name'];
                $return[$key]['groupId'] = $groupId;
            }
        }

        //$lists = $return;
        //$this->benchmark->mark('getStyleListsEnd');
        //return $totalCount;

        $lists = [];
        foreach ($return as $key => $val) {
            $list = $val['list'];
            $columnId = $val['columnId'];

            $details = [];
            foreach ($list as $k => $v) {
                if ($k > 0 && $k <= 3) {
                    $details[] = [
                        'id' => $v['id'],
                        'cover' => $v['cover'],
                        'table' => $v['tableName']
                    ];
                }
            }
            $urlParams = [
                'sor_1',
                'bra_' . $list[0]['brand_tid'],
                'ind_0',
            ];
            $urlParams = implode('-', $urlParams);

            $lists[] = [
                'id' => $list[0]['id'],
                'brandName' => $return[$key]['name'],
                'cover' => $list[0]['cover'],
                'tableName' => $list[0]['tableName'],
                'columnId' => $columnId,
                'url' => "/styles/" . $urlParams . '/',
                'details' => $details,
            ];
        }

        $this->benchmark->mark('getStyleListsEnd');
        return $totalCount;
    }

    /**
     * [getConditions 获取solr查询的condition条件]
     * @param string|array $params [url参数]
     * @param  integer $columnId [栏目id]
     * @return [array]  $conditions
     */
    public function getConditions($params = '', $columnId, $powers = [])
    {
        if (!empty($params)) {
            $paramsArr = is_array($params) ? $params : $this->common_model->parseParams($params);
        } else {
            $paramsArr = [];
        }
        // 性别
        $gender = $this->common_model->getGenderByRequest($params);
        $this->common_model->childGender($gender, $conditions);

        return $conditions;
    }

    /**
     * 获取广告位
     * @param $gen 获取选中的性别
     * @param $skeyword 获取搜索关键字
     */
    public function getAds($gender)
    {
        if (!$gender) {
            $gender = 0;
        }
        $sql = "select * from `fashion`.`stylezone_index` WHERE `iSex`  = {$gender} AND `iStatus` = 0 limit 0,3";
        $rows = $this->db()->query($sql)->result_array();

        return $rows;
    }

}