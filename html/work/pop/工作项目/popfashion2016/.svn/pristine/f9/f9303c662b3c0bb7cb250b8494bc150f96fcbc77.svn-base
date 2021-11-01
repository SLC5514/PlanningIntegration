<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
  + 客服服务
*/

class Customer extends POP_Controller
{
    /**
     * Class constructor
     *
     * @return  void
     */
    private $pageSize = 12;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Customer_model']);
    }

    /*
      + 客户服务
    */
    public function service($params = '')
    {
        $totalCount = 0;
        $recommendCount = 0;
        $arSort = [];
        $arSort['dPublishTime'] = 'desc';
        $paramsArr = $this->common_model->parseParams($params, 1);
        $page = $this->common_model->getPage($paramsArr); //当前页  
        $offset = ($page - 1) * ($this->pageSize);// 偏移量
        $condition = [];
        $condition['iPublishStatus'] = 1;//发布
        $aLists = $this->Customer_model->getCustomerLists($condition, $offset, $this->pageSize, $arSort, $totalCount);

        $condition['iRecommend'] = 1;
        $arSort = [];
        $arSort['dRecommendTime'] = 'desc';
        $aRecommendList = $this->Customer_model->getCustomerLists($condition, $offset, 2, $arSort, $recommendCount);
        $this->assign('aLists', $aLists);
        $this->assign('aRecommendList', $aRecommendList);
        $this->assign('title', 'VIP会员独享小秘书_合作客户_产业联盟-POP服装趋势网');
        $this->assign('keywords', '客户服务,服务特权,VIP会员');
        $this->assign('description', 'VIP会员独享小秘书为你提供准备、方便、快捷的时尚资讯服务，我们的服务热线是4008-210-662');
        $this->assign('pageSize', $this->pageSize);
        $this->assign('page', $page);
        $this->assign('totalCount', $totalCount);
        $this->display('customer_service.html');
    }

}