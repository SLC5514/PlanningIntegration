<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Home
 *
 * @property-read common_model $common_model
 * @property-read home_model $home_model
 * @property-read RelateHome_model $RelateHome_model
 */
class Home extends POP_Controller
{
    //成功/错误状态码
    public $success_code = 200;
    public $error_code = 100;
    public $module_power;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['home_model', 'common_model', 'RelateHome_model']);
        $this->module_power = $this->RelateHome_model->module;

        // vip用户即将过期提示条
        $vipTips = $this->isVipExpireTips();
        $this->assign('vipTips', $vipTips);

        $this->assign('columnPid', 92);
        $this->assign('is_home', 1);

        $this->assign('title', '服装设计网站_设计师信赖的流行趋势预测平台-POP服装趋势网');
        $this->assign('keywords', '时尚杂志,时尚网站,时尚网,服装设计,服装设计网,服装设计网站,服装设计师,服装流行趋势');
        $this->assign('description', 'POP服装趋势网是国内最大、国际领先的专业高端服装设计资源网站，涵盖独立设计师作品、时装周秀场高清图片和时尚杂志书籍，从色彩、面料、图案印花、款式、灵感、主题、廓形等方面，为设计师提供最新最前沿的服装解析。');

        $this->json = $this->common_model->getJsonOutputObj();


    }


    //个性化首页
    public function index()
    {
        if (!getUserId()) {
            //未登陆首页
            $words = $this->home_model->getHomeKeyWords();// 词云

            $adsHome = $this->common_model->getAds(0, 7, 3);// 首页广告
            $adsTopHome = $this->common_model->getAds(0, 24, 5);// 首页首屏推广位
            $adsFeatured = $this->common_model->getAds(0, 17, 4);// 推荐位广告

            // 设计师数量 有效注册用户量*1.4
            $DesignerCount = $this->home_model->getDesignerCount();
            $this->assign('designerCount', ceil($DesignerCount));

            // 趋势资源数量 款式库总数*3
            $cond = [];
            $cond['iColumnId'] = 4;
            $result = [];
            $styleCount = POPSearch::wrapQueryPopFashionMerger('', $cond, $result);
            $this->assign('styleCount', $styleCount * 3);

            $this->assign('adsHome', $adsHome);// 首页广告
            $this->assign('adsTopHome', $adsTopHome);// 首页首屏推广位
            $this->assign('adsFeatured', $adsFeatured);// 推荐位广告
            $this->assign('words', $words);
            $this->display('home.html');
        } else {

            //用户类型-判断是否能收藏
            $userInfo = get_cookie_value();
            $this->assign('iAccountType', $userInfo['iAccountType']);
            //头部banner
            $banner = $this->RelateHome_model->get_banner(28, 6);
            $this->assign('banner', $banner);
            //头部推荐标签
            $this->grl();
            //色系-季节筛选项
            $color_sea = $this->RelateHome_model->get_color_season();
            $this->assign('color_sea', $color_sea);

            //品牌排行榜-性别筛选项未选择时 偏好中性别的优先顺序:女-男-童
            if (empty($this->RelateHome_model->user_like['gen'])) {
                $selected_sex = 'all';
            }
            if (in_array(5, $this->RelateHome_model->user_like['gen'])) {
                $selected_sex = 5;
            }
            if (in_array(1, $this->RelateHome_model->user_like['gen'])) {
                $selected_sex = 1;
            }
            if (in_array(2, $this->RelateHome_model->user_like['gen'])) {
                $selected_sex = 2;
            }
            if (count(array_intersect([1, 2, 5], $this->RelateHome_model->user_like['gen'])) >= 3) {
                $selected_sex = 'all';
            }
            //品牌热门榜-性别筛选项
            $all_sex = GetCategory::getSomeAttr(1);
            unset($all_sex[3]);
            unset($all_sex[4]);
            $all_sex = ['all' => '全部性别'] + $all_sex;
            $this->assign('all_sex', $all_sex);
            $this->assign('selected_sex', $selected_sex);

            //图案排行榜-图案内容一级筛选项
            $first_pattern = $this->RelateHome_model->get_pattern_filter();
            $this->assign('first_pattern', $first_pattern);

            //是否是个性化首页
            $this->assign('is_relate', true);
            //模块权限
            $this->assign('module', $this->module_power);
            $this->assign('power_modules', json_encode(['power_modules' => $this->module_power]));
            //偏好
            $like_name = implode(',', $this->module_power['like_name']);
            $like_name = mb_strlen($like_name) > 25 ? mb_substr($like_name, 0, 25, 'utf-8') . '...' : $like_name;
            $this->assign('like_name', $like_name);

            $this->assign('title', '今日推荐-POP服装趋势网');

            $this->display('fashion_home.html');

        }
    }

    //一级色系 (默认展示1级色系占比)
    public function g_color()
    {
        if (getUserId() && $this->module_power['top_show']) {
            $sea_id = $this->input->get('sea');
            $first_color = $this->RelateHome_model->get_first_color($sea_id);

            if ($this->input->is_ajax_request()) {
                $this->json->code($this->success_code)->msg('ok')->data($first_color)->out();
            }
        }
    }

    //二级色系
    public function g_second_color()
    {
        if (getUserId() && $this->module_power['top_show']) {
            $sea_id = $this->input->get('sea');
            $first_color_id = $this->input->get('first_color');
            $second_color = $this->RelateHome_model->get_second_color($sea_id, $first_color_id);
            if ($this->input->is_ajax_request()) {
                $this->json->code($this->success_code)->msg('ok')->data($second_color)->out();
            }
        }
    }

    //品牌排行榜
    public function g_brand_top()
    {
        if (getUserId() && $this->module_power['top_show']) {
            $sex_id = $this->input->get('sex');
            $brands = $this->RelateHome_model->get_brand_top($sex_id);
            if ($this->input->is_ajax_request()) {
                $this->json->code($this->success_code)->msg('ok')->data($brands)->out();
            }
        }
    }

    //图案排行榜
    public function g_pattern_top()
    {

        if (getUserId() && $this->module_power['top_show']) {
            $pattern_id = $this->input->get('pattern_id');
            $patterns_data = $this->RelateHome_model->get_pattern_top($pattern_id);
            if ($this->input->is_ajax_request()) {
                $this->json->code($this->success_code)->msg('ok')->data($patterns_data)->out();
            }
        }

    }

    //接口-最新报告/热门报告
    public function g_report()
    {
        if (getUserId()) {
            if ($this->input->is_ajax_request()) {

                //最新报告
                $new_report = $this->RelateHome_model->get_new_report($this->module_power['new_report_relate']);
                //热门报告
                $hot_report = $this->RelateHome_model->get_hot_report($this->module_power['hot_report_relate']);

                $this->json->code($this->success_code)
                    ->data(['new_report' => $new_report, 'hot_report' => $hot_report])
                    ->info(['module' => $this->module_power])
                    ->msg('ok')
                    ->out();

            }
        }
    }

    //获取头部推荐标签，
    public function grl()
    {
        if (getUserId()) {
            list($return, $have_label, $have_brand_label) = $this->RelateHome_model->get_recommoned_label();
            $user_label_count = count($have_label) + count($have_brand_label);
            if ($this->input->is_ajax_request()) {
                $this->json->code($this->success_code)->data($return)->info(['user_label' => intval($user_label_count)])->msg('ok')->out();
            } else {
                $this->assign('recommend_label', $return);
                $this->assign('user_label', intval($user_label_count));
            }
        }
    }

    //接口-获取用户可选标签数据
    public function g_cate()
    {
        if (getUserId()) {
            list($return, $s, $selected) = $this->RelateHome_model->g_cate();

            // 品牌跟属性的ID可能重复的，无法区分的,新建字段
            $brand_selected = $selected['brand'];
            if (!empty($brand_selected)) {
                $brand_count = count($brand_selected);
            }
            if (isset($selected['brand'])) {
                unset($selected['brand']);
            }
            $selected_count = intval($brand_count) + count($selected);// 数量

            if ($this->input->is_ajax_request()) {
                $this->json->code($this->success_code)->data($return)->info(['selected_count' => $selected_count])->msg('ok')->out();
            }
        }
    }

    //接口-保存/删除推荐标签
    public function s_label()
    {
        if (getUserId()) {
            $a = $this->input->post('a', true); // save / del

            $label_arr = $this->input->post('label_arr', true);//单个标签id
            $brand_arr = $this->input->post('brand_arr', true);// 判断标识（单独出，因为brand品牌标签与list其他有重复）

            // 为空时，可以保存成功
            $rs = $this->RelateHome_model->mod_label($label_arr, $brand_arr, $a);
            $code = $rs ? $this->success_code : $this->error_code;

            $this->json->code($code)->msg('ok')->out();
        }
    }

    //接口-个性化首页/保存偏好选择 模块展示/个性化设置
    public function s_relate()
    {
        if (getUserId()) {

            $type = $this->input->post('type', true);
            switch ($type) {
                case 1://保存偏好选择
                    $label_arr = $this->input->post('label_arr', true);//多选
                    $rs = $this->RelateHome_model->save_like($label_arr);
                    break;
                case 2://保存模块设置
                    $params = $this->input->post(null, true);
                    $rs = $this->RelateHome_model->save_module($params);
                    break;
                case 3://保存个性化设置
                    $params = $this->input->post(null, true);
                    $rs = $this->RelateHome_model->save_relate($params);
                    break;
                case 4://页面 模块/个性化表单数据展示
                    $this->json->code(0)->data($this->module_power)->msg('ok')->out();
                    break;
                default://返回已选中的偏好
                    $user_have_like = $this->RelateHome_model->get_like();
                    $this->json->code(0)->data($user_have_like)->msg('ok')->out();
                    break;
            }

            $code = $rs ? $this->success_code : $this->error_code;
            $this->json->code($code)->msg('ok')->out();
        }
    }

    //T台数据
    public function g_t_show()
    {
        if (getUserId() && $this->module_power['t_show_relate']) {
            //偏好设置和模块权限数组
            $lists = $this->RelateHome_model->g_t_show();
            if ($this->input->is_ajax_request()) {
                $this->json->code($this->success_code)->data($lists)->msg('ok')->out();
            }
        }
    }

    //广告大片(注意：ad接口名会被当做广告屏蔽)
    public function g_look_book()
    {
        if (getUserId() && $this->module_power['advert_relate']) {
            $lists = $this->RelateHome_model->g_advert();

            if ($this->input->is_ajax_request()) {
                $this->json->code($this->success_code)->data($lists)->msg('ok')->out();
            }
        }
    }

    /**
     * 在线QQ 客服
     * 2018-11-09
     */
    public function wpa()
    {
        $type = intval($this->input->get('type'));
        $this->assign('title', $type ? '售后' : '售前');
        $this->assign('type', $type ? $type : 0);
        $this->display('wpa.html');
    }

    /**
     * 接口-获取品牌
     * 今日推荐三期
     */
    public function g_brands()
    {
        if (getUserId()) {
            $this->load->model('brands_model');

            // solr条件
            $condition = [];
            /*
            $endTime = date("Y-m-d") . 'T' . date("H:i:s", strtotime("-2 hour")) . 'Z';
            $yearTime = '[2018-01-01T00:00:00Z' . ' TO ' . $endTime . ']';// 2018-01-01至今
            $condition['dCreateTime'] = $yearTime;
            */
            $condition['iColumnId'] = '5'; // 5-品牌

            //搜索框
            $seachKey = $this->input->post_get('searchKey', true);
            $seachKey = str_replace(' ', '*', strtolower($seachKey));
            if (!empty($seachKey)) {
                $condition['other'][] = '(sBrandName:' . $seachKey . '* OR sBrandBname:' . $seachKey . '*)';
            }


            //通过solr搜索符合条件的品牌数据返回
            $data = $brandsInfo = $brandids = [];

            $arSort = ['sBrandAlias' => 'ASC', 'sBrandName' => 'ASC'];

            $this->benchmark->mark('actionAjax');
            $brandDataRes = [];
            $totalcount = POPSearch::wrapQueryPopFashionMerger('', $condition, $brandDataRes, 0, 10, $arSort);

            foreach ($brandDataRes as $val) {
                array_push($brandids, intval($val['pri_id']));
            }
            $brandsInfo = array_merge($brandsInfo, $this->brands_model->getBrandsFromId($brandids));

            //如果第一个和第二个的首字母值一样，记住该首字母
            $lastTimeAlias = 'AZ';

            $residueData = [];
            foreach ($brandsInfo as $brandInfo) {
                $brandid = $brandInfo['id'];
                $brandname = isset($brandInfo['b_name']) && !empty($brandInfo['b_name']) ? $brandInfo['name'] . '/' . $brandInfo['b_name'] : $brandInfo['name'];

                //如果该次取出的数据结果中，首字母和上次的最后一条的首字母相同，则这些数据用于追加到上一类中
                $alias = strtolower($brandInfo['alias']);
                if ($alias == $lastTimeAlias) {
                    $residueData[] = ['i' => $brandid, 'n' => $brandname];
                } elseif (!empty($alias)) {
                    $data[$alias][] = ['i' => $brandid, 'n' => $brandname];
                }
            }
            $this->benchmark->mark('actionAjaxEnd');

            //新首字母分类的数据
            $aHtml = $tmp = [];
            if ($data) {
                foreach ($data as $key => $val) {
                    $key = strtoupper($key);
                    $initial = $key == "NUMBER" ? '0-9' : ($key == "OTHER" ? '其他' : $key);
                    $tmp['initial'] = $initial;
                    if ($val) {
                        foreach ($val as $_val) {
                            $params = 'bra_' . $_val['i'] . '-dis_1-ind_0-gen_0';
                            $tmp['id'] = $_val['i'];
                            $tmp['name'] = $_val['n'];
                            $tmp['link'] = $this->common_model->getLink(4, $params);
                            $aHtml[] = $tmp;
                        }
                    }
                }
            }
            if (empty($data)) {
                $result = [];
                $this->json->code($this->success_code)->data($result)->msg('搜索不到该品牌')->out();
            } else {
                $this->json->code($this->success_code)->data($aHtml)->info(['total' => $totalcount])->msg('ok')->out();
            }
        }
    }
}