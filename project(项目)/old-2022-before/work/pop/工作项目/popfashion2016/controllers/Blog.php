<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog extends POP_Controller
{
    private $websites = [
        // "" => '全部快讯',
        1 => '服装',
        3 => '家纺',
        5 => '箱包',
        4 => '鞋子',
        6 => '首饰'
    ];
    private $site_url = [
        1 => ['name' => '服装', 'link' => '/blog/'],
        3 => ['name' => '家纺', 'link' => 'https://www.91jiafang.com/article/'],
        5 => ['name' => '箱包', 'link' => 'http://www.pop-bags.com/article/'],
        4 => ['name' => '鞋子', 'link' => 'https://www.pop-shoe.com/article/'],
        6 => ['name' => '首饰', 'link' => 'http://www.51shoushi.com/article/'],
    ];

    function __construct()
    {
        parent::__construct();
        $this->load->model("blog_model");
    }

    public function index($params = "")
    {
        $paramsArr = $this->common_model->parseParams($params);
        $page = (int)$this->input->get_post("page");
        $site = (int)$paramsArr["site"] ? $paramsArr["site"] : 1;//默认展示服装

        //新增热门标签筛选
        $label = (int)$paramsArr['label'] ? $paramsArr['label'] : $this->input->get_post("label");
        $hot_data = $this->blog_model->get_hot_tag_data($paramsArr, $site);
        $page = $page > 1 ? $page : 1;
        $page_size = 20;
        $offset = ($page - 1) * $page_size;
        list($list, $total_count) = $this->blog_model->get_list($offset, $page_size, $site, 'dPublishTime DESC,id DESC', $label);
        if ($this->input->is_ajax_request()) {
            $result = ["code" => 0, "msg" => "OK", "data" => $list, "info" => ["page_size" => $page_size, "total_count" => $total_count]];
            echo json_encode($result);
        } else {
            $tdk = $this->get_list_tdk($site);
            $this->assign("blog_tdk", $tdk);
            $this->assign("site", $site);
            $this->assign("websites", $this->websites);
            $this->assign("site_url", $this->site_url);
            $this->assign("page_size", $page_size);
            $this->assign("list", $list);
            $this->assign("total_count", $total_count);
            $this->assign("hot_labels", $hot_data['hot_labels']);
            $this->assign("selected_tag", $hot_data['selected_tag']);
            $this->display("blog/lists/article-list.html");
        }
    }

    public function detail($params = "")
    {
        $paramsArr = $this->common_model->parseParams($params);
        $id = (int)$paramsArr["id"];
        $label = (int)$paramsArr['label'];
        $detail = $this->blog_model->get_detail($id);

        // 本网站打开本网站的详情链接
        $site_data = $detail['sWebsite'] ? explode(',', $detail['sWebsite']) : array();
        // 网站：1=>服装中文,2=>服装英文,3=>家纺,4=>鞋子,5=>箱包,6=>首饰
        $allow_data = array(1);
        $is_data_exists = array_intersect($site_data, $allow_data);
        if (empty($is_data_exists)) {
            show_404();// 4-鞋子 与 2-服装英文 迁移出去了
        }

        // 替换文本域的图片
        $this->load->model(["activity_model", "details_model"]);
        $detail["sContent"] = $this->activity_model->replaceTextAreaImg($detail["sContent"]);
        // $detail["sContent"] = $this->details_model->getVedioPath($detail["sContent"]);// http与https兼容

        if (!$detail['id']) {
            show_404();
        }
        $site = (int)$paramsArr["site"];


        $pre_next = $this->blog_model->get_pre_next($id, $detail["dPublishTime"], $site, $label);
        $detail["dPublishTime"] = date("Y-m-d", strtotime($detail["dPublishTime"]));
        //新增热门标签
        $hot_data = $this->blog_model->get_hot_tag_data([],1);
        $this->assign("hot_labels", $hot_data['hot_labels']);
        $tdk = $this->get_detail_tdk($detail);
        $this->assign("blog_tdk", $tdk);
        $this->assign("pre_next", $pre_next);
        $this->assign("detail", $detail);
        $this->assign("site", $site);
        $this->display("blog/detail/article-detail.html");
    }

    private function get_list_tdk($site)
    {
        $ret = [
            "T" => "时尚博客_时尚快讯_流行趋势-POP服装趋势网",
            "K" => "时尚博客,时尚快讯,流行趋势",
            "D" => "趋势预见流行、智能助力设计，POP服装趋势网为企业和设计师提供最前沿最时尚的服装设计解决方案。"
        ];
        if (isset($this->websites[$site])) {
            $web_name = $this->websites[$site];
            $ret["T"] = "{$web_name}博客_{$web_name}资讯_{$web_name}设计-POP{$web_name}趋势网";
            $ret["K"] = "{$web_name}博客,{$web_name}资讯,{$web_name}设计";
            $ret["D"] = "POP服装趋势网是国内最大、国际领先的专业高端服装设计资源网站，提供的专业分析报告和图片信息，从色彩、图案、面料和款式等方面，为设计师提供不同主题的设计灵感，为时尚设计助力。";
        }
        return $ret;
    }

    private function get_detail_tdk($data)
    {
        $ret = ["T" => "", "D" => "", "K" => ""];
        $sWebsites = explode(",", $data["sWebsite"]);
        $web_names = [];
        foreach ($sWebsites as $Website) {
            $web_names[] = $this->websites[$Website];
        }
        $web_names = implode("/", array_filter($web_names));
        $ret["T"] = "{$data["sTitle"]}-POP{$web_names}趋势网";
        $ret["K"] = $data["sTitle"] . ',' . implode(',', $data['sLabel']);
        $ret["D"] = $this->get_first_p($data["sContent"]);
        return $ret;
    }

    //获取不为空的p标签的值，并且在200字以内
    private function get_first_p($content)
    {
        preg_match_all("/<p[^>]*>(?:(?!<\/p>)[\s\S])*<\/p>/", $content, $matchs);
        foreach ($matchs[0] as $match) {
            $match = preg_replace("#<[^>]+>#", "$2", $match);
            $match = trim($match);
            if (!empty($match)) {
                return mb_substr($match, 0, 200);
            }
        }
        return "";
    }
}
