<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**款式首页
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/20
 * Time: 14:14
 */
class Stylezone extends POP_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('stylezone_model');
    }


    public function index($params = '')
    {
        $paramArr = $this->common_model->parseParams($params);
        $this->load->library('POP_TrendExponent');
        $columnPid = 4;
        $this->assign('columnPid', $columnPid);
        $gender = intval($paramArr['gen']);
        if ($gender == 0) {
            $gender = $this->input->cookie('gender', TRUE);
        }

        $res = $this->pop_trendexponent->getTrendExponentData($this->input->get('refresh'));
        switch ($gender) {
            case 1:
                $trendIndex = $res[1];
                break;
            case 2:
                $trendIndex = $res[2];
                break;
            case 3:
            case 4:
            case 5:
                $trendIndex = $res[5];
                break;
            default:
                $trendIndex = $res['all'];
                break;
        }
        //获取热门推荐关键字
        $keys = $this->common_model->getHotKeyWords(4, $params, $refresh=FALSE);

        $this->assign('trendIndex', $trendIndex);
        $powers  = '';
        $limit   = 30;

        $this->assign('keys',$keys);
        $this->getLists($params, $powers, $limit);
        $this->assign('gender',$gender);

        $this->getAds($gender);
        $this->assign('title', '女|男|童装款式-POP服装趋势网');
        $this->assign('keywords', '秀场提炼,订货会,设计师品牌,名牌精选,款式流行,全球实拍,潮流领袖,街拍图库');
        $this->assign('description', 'POP服装趋势网款式栏目，为您提供零时差、无国界、实时更新最新款式资讯，全线索、多渠道、全面汇聚市场流行单品，以及最近更新的品牌和趋势指数，方便你的查阅与筛选。');
        $this->display('styles_page.html');
    }

    /**
     * 获取列表数据和分组列表数据和分页
     */
    private function getLists($params, $powers, $limit = 30)
    {
        $paramsArr = $this->common_model->parseParams($params, 1);
        $page      = $this->common_model->getPage($paramsArr); //当前页

        $page   = $page <= 0 ? 1 : $page;
        $offset = ($page - 1) * $limit;
        $lists  = [];

        $totalCount = $this->stylezone_model->getList($params, $lists, $offset, $limit, $powers);

        $this->assign('lists', json_encode($lists, JSON_UNESCAPED_SLASHES));
        $this->assign('totalCount', $totalCount);
        $this->assign('powers', $powers);
    }

    /**
     * 获取3个广告位
     * @param $params
     */
    public function getAds($gender){

        $ads = $this->stylezone_model->getAds($gender);

        $this->assign('ads',$ads);
    }

}