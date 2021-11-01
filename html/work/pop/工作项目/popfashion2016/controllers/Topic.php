<?php

/**
 * 专题控制器
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/31
 * Time: 15:16
 */
class Topic extends POP_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($topicTime = '')
    {
        $topicTime = $topicTime ? $topicTime : '20180404'; //20180404 专题列表页

        // 图案素材
        if (strtolower($topicTime) === 'toppatterns') {
            $this->topPatterns();
            return;
        }

        // 大牌花型
        if (strtolower($topicTime) === 'topbrands') {
            $this->topBrands();
            return;
        }

        // 疫情专题 /views/topic/20200213/index.html
        if (strtolower($topicTime) === '20200213') {
            // tdk
            $TDK = $this->getEpidemicTopicTDK();
            $this->assign('title', $TDK['t']);
            $this->assign('keywords', $TDK['k']);
            $this->assign('description', $TDK['d']);
        }
        $power = memberPower();
        //游客-5 普通用户-4 试用-3 vip子-2 vip-1
        $this->assign('usertype', $power['P_UserType']); //用户类型
        $this->display("topic/{$topicTime}/index.html");
    }
    // top100图案专题
    private function topPatterns()
    {
        $power = memberPower();
        $this->load->model('Patterns_model');
        //post参数：年-月 2018-12
        $start = $end = '';
        $selectTime = $this->input->get_post('selectTime');
        if ($selectTime) {
            $start = $selectTime . '-01';
            $end = date('Y-m-d', strtotime('+1 months', strtotime($selectTime)));
        }
        $list = $this->Patterns_model->getTopPatterns($start, $end);
        // 获取列表和分页 通过ajax获取列表
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $data = ['list' => $list, 'power' => $power];
            $jo = $this->Patterns_model->getJsonOutputObj();
            $jo->code(0)->data($data)->msg('ok')->out();
        }

        $this->assign('power', $power);

        $this->display('topic/top_patterns.html');
    }

    // top100大牌花型
    private function topBrands()
    {
        $power = memberPower();
        $this->load->model('Patterns_model');
        //post参数：年-月 2018-12
        $start = $end = '';
        $selectTime = $this->input->get_post('selectTime');
        if ($selectTime) {
            $start = $selectTime . '-01';
            $end = date('Y-m-d', strtotime('+1 months', strtotime($selectTime)));
        }
        $list = $this->Patterns_model->getTopPatterns($start, $end, 120);
        // 获取列表和分页 通过ajax获取列表
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $data = ['list' => $list, 'power' => $power];
            $jo = $this->Patterns_model->getJsonOutputObj();
            $jo->code(0)->data($data)->msg('ok')->out();
        }

        $this->assign('power', $power);

        $this->display('topic/topBrands/index.html');
    }

    // 疫情专题tdk
    private function getEpidemicTopicTDK()
    {
        $TDK = array(
            't' => '时尚战“疫”_服装行业市场洞察分析和流行趋势解读-POP服装趋势网',
            'k' => 'POP趋势,服装行业洞察,防护面料趋势,色彩流行变化,服饰市场变化',
            'd' => 'POP服装趋势网为应对疫情之后服装行业趋势和复苏，从市场分析、企划组货、主题色彩、工艺面料趋势、图案趋势等方面为设计师和时尚从业者提供前瞻性洞察分析和行业报告，包括防护面料趋势，色彩喜好变化，改良设计，行业洞察等分析等等。',
        );
        return $TDK;
    }
}