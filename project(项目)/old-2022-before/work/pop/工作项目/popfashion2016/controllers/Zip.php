<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 下载包
 */
class Zip extends POP_Controller
{
    private $pageSize = 20;//每页显示数量
    public $arrCookie = array();//cookie中存的用户信息

    /**
     * Class constructor
     *
     * @return  void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'zip_model']);

    }

    // 东大门资料
    //  /zip/dongdaemun/
    public function dongdaemun($params = '')
    {
        $aCondition = array(); //搜索条件
        $aPrivilege = array(); //权限数组
        $totalCount = 0; //总数
        $paramsArr = []; //参数
        $params = $this->common_model->restoreParams($params);
        if ($params) {
            $aParams = explode('-', $params);
            foreach ($aParams as $_val) {
                $_aVal = explode('_', $_val);
                $paramsArr[$_aVal[0]] = $_aVal[1];
            }
        }
        $page = intval($paramsArr['page']) ? intval($paramsArr['page']) : 1;//当前页
        $offset = ($page - 1) * ($this->pageSize);// 偏移量

        //通过cookie获取存储的用户信息
        $this->arrCookie = get_cookie_value();
        $iAccountId = $this->arrCookie['id'];//主账号id

        //获取账户下载权限
        if ($iAccountId) {
            $aPrivilege = $this->zip_model->getDongdaemunPrivilege($iAccountId);
        }

        //获取资料包列表
        $aCondition = $paramsArr;
        if ($aCondition['st']) {
            $aCondition['st'] = date('Y-m-d', strtotime($aCondition['st']));
        }
        if ($aCondition['et']) {
            $aCondition['et'] = date('Y-m-d', strtotime($aCondition['et']));
        }
        $aList = $this->zip_model->getDongdaemunList($aCondition, $offset, $this->pageSize, $aPrivilege, $totalCount);

        //用户类型
        $power = memberPower('other');
        $this->assign('userType', $power['P_UserType']);

        //分页
        $rootUrl = '/zip/dongdaemun/';
        $pageHtml = $this->makePageHtml($paramsArr, $totalCount, $this->pageSize, $page, $rootUrl);

        $this->assign('isOpen', $aPrivilege['isOpen']);
        $this->assign('isValidity', $aPrivilege['isValidity']);
        $this->assign('aList', $aList);
        $this->assign('aCondition', $aCondition);
        $this->assign('rootUrl', $rootUrl);
        $this->assign('pageHtml', $pageHtml);
        $this->assign('title', '韩国东大门批发市场新款数据包下载-POP服装趋势网');
        $this->assign('keywords', '韩国东大门批发市场新款数据包下载');
        $this->assign('description', 'POP的会员可享有下载韩国东大门批发市场新款数据包的权限。');
        $this->display('dongdaemun.html');
    }


}
