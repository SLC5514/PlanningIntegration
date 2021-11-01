<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Colorsearch 以色搜图的控制器
 */
class Colorsearch extends POP_Controller
{
    /**
     * @var Common_model
     */
    public $common_model;

    /**
     * @var Colorsearch_model
     */
    public $colorsearch_model;

    //成功/错误状态码
    public $success_code = 200;
    public $error_code = 1000; // 统一错误码
    public $error_code1 = 1001;// 次数超限
    public $error_code2 = 1002;// 获取列表失败
    public $error_code3 = 1003;// 用户类型不对
    public $error_code4 = 1004;// 网络开小差
    public $pageSize = 60;
    public $json;
    public $powers;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['colorsearch_model', 'common_model']);
        $this->json = $this->common_model->getJsonOutputObj();

        $this->powers = memberPower();
        if ($this->powers['P_UserType'] == 5) {
            header('Location:/member/pagelogin/');
            return;
        }
    }

    // 选择颜色--list
    public function index()
    {
        // 搜索框除色块外，其他跳转到全站搜索
        $keywords = $this->common_model->getKeyword('');
        if (!empty($keywords)) {
            header("Location:/search/?key={$keywords}");
            return;
        }
        $this->display('lists/search_color_list.html');
    }

    // 接口--获取一级色系
    public function g_first_color()
    {
        $first_color_data = $this->colorsearch_model->get_first_color();
        if ($this->input->is_ajax_request()) {
            if (!empty($first_color_data)) {
                $this->json->code(200)->msg('ok')->data($first_color_data)->out();
            } else {
                $this->json->code($this->error_code)->msg('获取一级色失败')->out();
            }
        }
    }

    // 接口--获取二级色系
    public function g_second_color()
    {
        $pid = $this->input->post_get('pid', true);// 获取一级色
        $first_color = !empty($pid) ? intval($pid) : 1;

        // 一级色存在获取二级色，默认1-黄色
        $second_color_data = $this->colorsearch_model->get_second_color($first_color);

        if ($this->input->is_ajax_request()) {
            if (!empty($second_color_data)) {
                $this->json->code($this->success_code)->msg('ok')->data($second_color_data)->out();
            } else {
                $this->json->code($this->error_code)->msg('获取二级色失败')->out();
            }
        }
    }

    // 色彩搜图--列表
    public function get_list()
    {
        list($bool, $conditions) = $this->colorsearch_model->getConditions();
        if (!$bool) {
            $this->json->code($this->error_code)->msg('传递颜色参数错误')->out();
        }
        $isNumCheck = $this->input->post_get('isNumCheck', true);// 计算次数的按钮

        // 普通用户使用次数
        $totalCount = 0;
        if ($this->powers['P_UserType'] == 4) {
            $this->load->driver('cache');
            $mem_key = Colorsearch_model::COLOR_USE_MEMCACED_KEY_PREFIX . getUserId();
            $use_num = $this->cache->memcached->get($mem_key);
            if ($use_num >= 20) {
                $this->json->code($this->error_code1)->msg('普通用户使用次数超限')->out();
            }
            // 点击色彩分析按钮 计数
            if ($isNumCheck == 1) {
                list($totalCount, $msg, $code) = $this->colorsearch_model->addNormalColorSearchLog();
                if ($totalCount === false) {
                    $this->json->code($code)->msg($msg)->out();
                }
            } else {
                // 其他调用，不计数
                $totalCount = $use_num;
            }
        }

        $page = max($this->input->post_get('page'), 1);
        $offset = ($page - 1) * $this->pageSize;

        list($total, $lists) = $this->colorsearch_model->getColorList($conditions, $offset, $this->pageSize);
        if ($this->input->is_ajax_request()) {
            if (intval($total) > 0) {
                if ($this->powers['P_UserType'] == 4) {
                    $free_num = 20 - intval($totalCount);
                    $info = [
                        'total' => $total,
                        'free' => intval($free_num),
                        'user_type' => 'NORMAL',
                        'page' => $page,
                        'pageSize' => $this->pageSize,
                    ];
                    $this->json->code($this->success_code)->msg('ok')->data($lists)->info($info)->out();
                } else {
                    $info = [
                        'total' => $total,
                        'page' => $page,
                        'pageSize' => $this->pageSize,
                    ];
                    $this->json->code($this->success_code)->msg('ok')->data($lists)->info($info)->out();
                }
            } else {
                $this->json->code(999)->msg('无数据，请刷新')->out();
            }
        }
    }

    // 色彩搜图--筛选项
    public function get_filter()
    {
        list($bool, $conditions) = $this->colorsearch_model->getConditions();
        if (!$bool) {
            $this->json->code($this->error_code)->msg('传递颜色参数错误')->out();
        }
        // 筛选项
        $filter = $this->colorsearch_model->get_interface_filter($conditions);

        if ($this->input->is_ajax_request()) {
            if (!empty($filter)) {
                $this->json->code($this->success_code)->msg('ok')->data($filter)->out();
            } else {
                $this->json->code($this->error_code)->msg('获取筛选项失败')->out();
            }
        }

    }


}