<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Aggregation extends POP_Controller
{
    /**
     * @var Aggregation_model
     */
    public $aggregation_model;

    public function index($params)
    {
        $params = explode('-', $params);

        $this->load->model('aggregation_model');

        $this->aggregation_model->related_theme_pid = $params[0];
        $this->aggregation_model->related_theme_id = $params[1];
        $this->aggregation_model->gender = isset($params[2]) && !empty($params[2]) ? $params[2] : 0;

        try {
            $page_info = $this->aggregation_model->aggregationPageInfo();
            $page_list = $this->aggregation_model->aggregationPageList();

            // 处理上一篇下一篇链接
            $page_info['pre_next'] = [
                'pre' => [],
                'next' => []
            ];


            if ($page_info['topics']) {
                $index = 0;
                foreach ($page_info['topics'] as $i => $topic) {
                    if ($topic['related_topics_id'] == $params[1]) {
                        $index = $i;
                    }
                }

                $topic_count = count($page_info['topics']);

                if ($index === 0) {
                    $page_info['pre_next']['pre'] = [];
                } else {
                    $page_info['pre_next']['pre'] = [
                        'link' => $page_info['topics'][$index - 1]['link'],
                        'text' => $page_info['topics'][$index - 1]['title']
                    ];
                }

                if ($index < $topic_count - 1) {
                    $page_info['pre_next']['next'] = [
                        'link' => $page_info['topics'][$index + 1]['link'],
                        'text' => $page_info['topics'][$index + 1]['title']
                    ];
                } else {
                    $page_info['pre_next']['next'] = [];
                }
            }

        } catch (Exception $e) {
            show_404();
            return;
        }

        $this->getTDK($page_info['tdk']);

        $this->assign('page_info', $page_info);
        $this->assign('page_list', $page_list);
        $this->assign('page_topic_id', $params[1]);

        $this->display('aggregation/home.html');
    }

    private function getTDK($seo)
    {
        $this->assign('title', $seo['title']);
        $this->assign('keywords', $seo['keywords']);
        $this->assign('description', $seo['description']);
    }

}