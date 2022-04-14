<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2016/3/16
 * Time: 10:20
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 交叉页控制器
 */

class Mutual extends POP_Controller
{
    public $cookie_gender = '';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('mutual_model');
        $this->load->model('common_model');
        //vip用户即将过期提示条
        $vipTips = $this->isVipExpireTips();
        $this->cookie_gender = $this->input->cookie('gender');
        $this->assign('vipTips', $vipTips);


        $this->assign('columnPid', 92);
        // 性别或行业
        $paramsArr = $this->common_model->parseParams($this->getParams());
        if(isset($paramsArr['gen'])) {
            $mutId = $paramsArr['gen'];
        }elseif(isset($paramsArr['ind'])) {
            $mutId = $paramsArr['ind'];
        }
        $mut = !empty($mutId)?trim(GetCategory::getOtherFromIds($mutId, ['sName'])):'';
        if(empty($mut)) {
            $t= '男装_女装_童装频道-POP服装趋势网';
            $k = '服装频道,专区通道';
            $d = 'POP服装趋势网专区通道为您提供最新、最前沿的女装、男装、童装图片资讯，为您及时提供与服装相关的款式、色彩、面料、印花图案等方面资讯，为您提供有价值的资讯服务。';
        }else {
            $t= $mut.'款式库_'.$mut.'品牌_发布会_'.$mut.'素材-POP服装趋势网';
            $k = $mut.'款式库,'.$mut.'品牌,'.$mut.'设计图,'.$mut.'素材';
            $d = 'POP服装趋势网'.$mut.'频道为您提供最新、最前沿的'.$mut.'图片资讯，为您及时提供与'.$mut.'相关的款式、色彩、面料、印花图案等方面资讯，为您提供有价值的资讯服务。';
        }
        
        $this->assign('title', $t);
        $this->assign('keywords', $k);
        $this->assign('description', $d);
    }

    public function index($params = '')
    {
        $params = $this->common_model->restoreParams($params);
        $params = $this->common_model->parseParams($params, 1, FALSE);
        if($params['reg'] == 1){
            if($this->cookie_gender){
                $this->input->set_cookie('gender', $this->cookie_gender);
            }else{
                $this->input->set_cookie('gender', $this->cookie_gender, -1);
            }
            $this->japanAndKorea($params);
        }else {
            $this->benchmark->mark('action');
            // 空则清空cookie
            if (empty($params)) {
                $sGender = $sIndustry = 0;
                $this->input->set_cookie('gender', $sGender, -1);
                $this->input->set_cookie('industry', $sIndustry, -1);
            } // 否则设置cookie
            else {

                $sIndustry = intval($params['ind']);
                $sGender = intval($params['gen']);
                if ($sGender) {
                    $this->input->set_cookie('gender', $sGender, 0);
                    $this->input->set_cookie('industry', 0, -1);
                } elseif ($sIndustry) {
                    $this->input->set_cookie('industry', $sIndustry, 0);
                    $this->input->set_cookie('gender', 0, -1);
                }
            }
            //头部广告数据-广告轮播图
            $AdData_Nb = $this->common_model->getAds($sGender . '-' . $sIndustry, '5');

            $allData = $this->mutual_model->getMutualData($sGender, $sIndustry);


            // 款式栏目更多的链接
            $styleLink = '/stylezone/dis_1/';
            // 选中性别
            if ($sGender) {
                $styleLink = "/stylezone/gen_{$sGender}-ind_0-dis_1/";
            } //选中行业
            elseif ($sIndustry) {
                $styleLink = "/styles/ind_{$sIndustry}-gen_0-dis_1/";
            }
            $this->assign('styleLink', $styleLink);
            $this->assign('sGender', $sGender);

            $this->assign('allData', $allData);
            $this->assign('AdData_Nb', $AdData_Nb);
            $this->benchmark->mark('actionEnd');
            $this->display("mutual.html");
        }
    }


    public function japanAndKorea($params){
        unset($params['reg']);
        $refresh = $this->input->get('refresh', false);

        //韩国
        $params['dis'] = 1;
//        $params['reg'] = 272;
        $params['sor'] = 1;
        $mutual_data = $this->mutual_model->getRegionMutualData($params, $refresh);
//        echo '<pre>';
//        print_r($mutual_data['analysis']['japan']);die;

        $title = '日韩款式库_日韩品牌_发布会_日韩素材-POP服装趋势网';
        $keywords = '日韩款式库,日韩品牌,日韩设计图,日韩素材';
        $description = 'POP服装趋势网日韩频道为您提供最新、最前沿的日韩图片资讯，为您及时提供与日韩相关的款式、色彩、面料、印花图案等方面资讯，为您提供有价值的资讯服务。';
        $this->assign('title', $title);
        $this->assign('keywords', $keywords);
        $this->assign('description', $description);

        $this->assign('params', $params);
        $this->assign('data', $mutual_data);
        $this->display('mutual_japan_korea.html');
    }
}
