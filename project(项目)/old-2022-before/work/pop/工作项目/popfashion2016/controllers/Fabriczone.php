<?php
/**面料专区页控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/31
 * Time: 14:27
 */
defined('BASEPATH') OR exit('No direct script access allowed');

class Fabriczone extends POP_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(['fabriczone_model', 'common_model']);

        // vip用户即将过期提示条
        $vipTips = $this->isVipExpireTips();
        $this->assign('vipTips', $vipTips);

        $this->getTdk();
        $this->assign('columnPid', "fabriczone_item");
    }

    // seo优化查询TDK
    private function getTdk()
    {
        $title = "面料趋势专区-POP服装趋势网";
        $description = "POP服装趋势网的面料趋势专区广告位限量招商,60万服装企业用户、每天点击量达180万+，多维度广告投放渠道，POP资源网站广告、APM书籍广告和ULB平台广告，精准、高效、低成本，共享POP优质客户资源。";
        $keywords = "面料趋势,面料图库,面料书籍";
        $this->assign('title', $title);
        $this->assign('description', $description);
        $this->assign('keywords', $keywords);
    }

    public function index()
    {
        $allData = $this->fabriczone_model->getFabricData($_REQUEST['refresh']);
        // 获取专区页所有数据
        $this->assign('allData', $allData);

        // 头部广告数据，面料趋势广告位置=15
        $topAds = $this->common_model->getAds('0', 15);
        $this->assign('topAds', $topAds);

        $power = memberPower('other');
        $this->assign('power', $power);

        $this->display("fabric_mutual.html");
    }

}