<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends POP_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * [getCustomerLists 获取数据+总条数]
     * @param  array $condition 条件
     * @param  integer $offset [偏移量]
     * @param  integer $limit [每页条数]
     * @param [array]    $arSort     [排序]
     * @param [array]    $totalCount [根据条件查询出的总条数]
     * @return [array]    $lists    [查询结果]
     */
    public function getCustomerLists($condition = [], $offset = 0, $limit = 12, $arSort, &$totalCount)
    {

        $tableName = OpPopIndustryActivity::POP_Table_Name_T_services_Customer_Case;
        $lists = [];
        // 排序
        if (empty($arSort)) {
            $arSort['dPublishTime'] = 'desc';
        }
        $condition['tablename'] = $tableName;
        $result = [];
        $totalCount = POPSearch::wrapQueryPopIndustryActivity('', $condition, $result, $offset, $limit, $arSort);
        foreach ($result as $key => $val) {
            $id = $val['pri_id'];
            $info = OpPopIndustryActivity::getProductData($id, $tableName, true);
            $info = $info[$id];
            $info['dPublishTime'] = date('Y年m月d日', strtotime($info['dPublishTime']));
            $aStaff = OpPopIndustryActivity::getProductData($info['iStaffId'], OpPopIndustryActivity::POP_Table_Name_T_Services_Staff);
            $info['sSignaturePic'] = $aStaff[$info['iStaffId']]['sSignaturePic'];
            $info['sPosition'] = $aStaff[$info['iStaffId']]['sPosition'];
            $info['sName'] = $aStaff[$info['iStaffId']]['sName'];
            $lists[] = $info;
        }
        return $lists;
    }
}
