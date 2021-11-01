<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Aggregation_model
 * @property-read common_model $common_model
 */
class Aggregation_model extends POP_Model
{
    public $gender = 0;
    public $related_theme_pid;
    public $related_theme_id;

    public $t_trend_aggregation_page = 't_trend_aggregation_page';
    public $t_trend_aggregation_special_topic = 't_trend_aggregation_special_topic';
    public $t_trend_aggregation_special_topic_modules = 't_trend_aggregation_special_topic_modules';


    private $dict_topics;

    private $dict_gender;

    /**
     * Aggregation_model constructor.
     */
    public function __construct()
    {
        parent::__construct();

        if (!$this->dict_topics) {
            $this->load->model('category_model');
            $this->dict_topics = $this->category_model->getAll('iRelationTheme');
        }

        if (!$this->dict_gender) {
            $this->dict_gender = GetCategory::getAttrValueByType(1);
        }
    }


    /**
     * 获取趋势聚合页数据
     *
     * @return mixed
     * @throws Exception
     */
    public function aggregationPageInfo()
    {
        if (!$this->related_theme_pid) {
            throw new Exception('关联主题父id未确定', 1001);
        }

        $page_info = $this->db()->select('title, memo, banner')
            ->where('status', 1)
            ->where('related_topics_pid', $this->related_theme_pid)
            ->limit(1)
            ->get($this->t_trend_aggregation_page)
            ->row_array();

        if (!$page_info) {
            throw new Exception('趋势聚合页数据不存在', 1005);
        }
        $page_info['tdk'] = $this->getTDK($page_info);
        $page_info['link'] = [
            'gender' => [
                '/trendtopic/' . implode('-', [$this->related_theme_pid, $this->related_theme_id]) . '/',
                '/trendtopic/' . implode('-', [$this->related_theme_pid, $this->related_theme_id, 1]) . '/',
                '/trendtopic/' . implode('-', [$this->related_theme_pid, $this->related_theme_id, 2]) . '/'
            ],
            'select' => $this->gender
        ];

        $page_info['topics'] = $this->topicsList($this->related_theme_pid);
        $page_info['modules'] = $this->moduleList($this->related_theme_id);

        return $page_info;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function aggregationPageList()
    {
        $this->db()->select('related_topics_pid, title, banner')
            ->where('status', 1);
        if (isset($this->related_theme_pid) && !empty($this->related_theme_pid)) {
            $this->db()->where('related_topics_pid !=', $this->related_theme_pid);
        }
        $list = $this->db()->order_by('id', 'DESC')
            ->get($this->t_trend_aggregation_page)
            ->result_array();

        foreach ($list as &$item) {
            $item['topics'] = $this->topicsList($item['related_topics_pid'], false);
        }
        unset($item);

        return $list;
    }


    /**
     * @param $related_theme_id
     * @return mixed
     */
    private function moduleList($related_theme_id)
    {
        $modules = $this->db()->select('is_stay_tuned, module')
            ->where('status', 1)
            ->where('related_topics_id', $related_theme_id)
            ->order_by('weight', 'DESC')
            ->get($this->t_trend_aggregation_special_topic_modules)
            ->result_array();

        if (!$modules) {
            return [];
        }

        $module_condition = ['iRelationTheme' => $this->related_theme_id];

        foreach ($modules as $index => &$module) {

            $cond = $module_condition;

            $module_id = $module['module'];
            if (strpos($module_id, '_') !== false) {
                list($col, $color_category) = explode('_', $module_id);
                $cond['iColumnId'] = $col;
                if ($this->gender) {
                    $cond['other'][] = 'aLabelIds:('.$color_category . ' AND ' . $this->gender . ')';
                } else {
                    $cond['aLabelIds'] = $color_category;
                }
                $module['title'] = trim(GetCategory::getAttrNameById($color_category));
            } else {
                $cond['iColumnId'] = $module_id;
                if ($this->gender) {
                    $cond['aLabelIds'] = $this->gender;
                }
                $module['title'] = trim(GetCategory::getOtherFromColId($module_id, 'sName'));
            }

            $module['is_stay_tuned'] = isset($module['is_stay_tuned']) && $module['is_stay_tuned'] > 0 ? strtotime($module['is_stay_tuned']) : 0;
            $module['season'] = [];
            $module['gender'] = !empty($this->gender) ? GetCategory::getAttrById($this->gender) : '全部';

            $params = [];
            $params['raw'] = 1;
            $params['facet'] = 'true';
            $params['facet.field'] = 'iSeason';
            $params['facet.limit'] = 5;

            $solr_facet_data = POPSearch::wrapQueryPopFashionMerger('', $cond, $result, 0, 10, ['dCreateTime' => 'DESC'], $params);

            if ($solr_facet_data['facet_counts']['facet_fields']['iSeason']) {
                foreach ($solr_facet_data['facet_counts']['facet_fields']['iSeason'] as $k => $v) {
                    if (!$v) {
                        continue;
                    }
                    $module['season'][] = GetCategory::getAttrNameById($k);
                }
            }

            $module['report_list'] = $this->reportList($solr_facet_data['response']['docs']);

            // 切换到性别 && 非期待栏目 && 该条件下没有报告
            if ($this->gender && !$module['is_stay_tuned'] && !$module['report_list']) {
                unset($module, $modules[$index]);
            }
        }
        unset($module);

        return $modules;
    }

    /**
     * @param $related_theme_pid
     * @param bool $report_data
     * @return mixed
     */
    private function topicsList($related_theme_pid, $report_data = true)
    {
        $topics = $this->db()->select('related_topics_id, cover_pic')
            ->where('status', 1)
            ->where('related_topics_pid', $related_theme_pid)
            ->order_by('weight', 'DESC')
            ->get($this->t_trend_aggregation_special_topic)
            ->result_array();

        $related_topics_ids = array_column($topics, 'related_topics_id');

        if (!$topics) {
            return [];
        }

        // 是否取报告数据
        if (!$report_data) {
            goto module;
        }

        $params = [];
        $params['group'] = 'true';
        $params['group.field'] = 'iRelationTheme';

        $condition = ['iColumnId' => 1, 'iRelationTheme' => $related_topics_ids];
        if ($this->gender) {
            $condition['aLabelIds'] = $this->gender;
        }

        POPSearch::wrapQueryPopFashionMerger('', $condition, $solr_group_result, 0, count($related_topics_ids), ['dCreateTime' => 'DESC'], $params);

        $solr_data = [];
        $solr_group_result = $solr_group_result['iRelationTheme']['groups'];
        foreach ($solr_group_result as $item) {
            $doc = $item['doclist']['docs'][0];

            $col_id = end($doc['iColumnId']);
            $table = $doc['tablename'];
            $pri_id = $doc['pri_id'];

            $solr_data[$item['groupValue']]['report'] = $this->reportData($table, $pri_id, $col_id);
        }
        module:

        $dict_topics = $this->dict_topics;

        foreach ($topics as &$item) {
            $item['title'] = $dict_topics[$related_theme_pid]['attrs'][$item['related_topics_id']]['sName'];

            $param = [$related_theme_pid, $item['related_topics_id']];
            if ($this->gender) {
                $param[] = $this->gender;
            }

            $item['link'] = '/trendtopic/' . implode('-', $param) . '/';

            if ($report_data && $solr_data[$item['related_topics_id']]['report']) {
                $item += $solr_data[$item['related_topics_id']]['report'];
            }
        }
        unset($item);

        return $topics;
    }

    /**
     * @param $solr_report_docs
     * @return array
     */
    private function reportList($solr_report_docs)
    {
        $list = [];

        if (!$solr_report_docs) {
            return $list;
        }
        $this->load->model(['report_model', 'statistics_model']);

        foreach ($solr_report_docs as $doc) {

            $pri_id = $doc['pri_id'];
            $table_name = $doc['tablename'];
            $col_id = end($doc['iColumnId']);

            $data = OpPopFashionMerger::getProductData($pri_id, $table_name);
            $info = $data[$pri_id];

            if (!$info) {
                continue;
            }

            $info['id'] = $pri_id;

            // 发布时间,标题
            switch ($table_name) {
                case 'specialtopic':
                case 'specialtopic_graphic':
                    $info['create_time'] = $info['addtime'];// 发布时间
                    break;
                case 'mostrend_content':
                    $info['create_time'] = $info['release_time'];
                    break;
                case 't_trend_report':
                    $info['title'] = $info['sTitle'];//标题
                    $info['create_time'] = $info['dPubTime'];// 发布时间
                    break;
            }
            // 摘要
            if (isset($info['abstract'])) {
                $info['description'] = $info['abstract'];
            } elseif (isset($info['title_describe'])) {
                $info['description'] = $info['title_describe'];
            } elseif (isset($info['sDesc'])) {
                $info['description'] = $info['sDesc'];
            }

            $info['title'] = htmlspecialchars(stripcslashes($info['title']));
            $info['description'] = htmlspecialchars(trim(strip_tags($info['description'])));
            $info['view_count'] = $this->statistics_model->getViews($table_name, $pri_id, $info);
            $imgPath = getImagePath($table_name, $info);// 图片路径
            $info['cover'] = getFixedImgPath($imgPath['cover']);
            $info['labels'] = $this->report_model->getLabelInfo($table_name, $pri_id, $col_id, '', 'list');// 取列表页标签
            $info['link'] = '/details/report/t_' . getProductTableName($table_name) . '-id_' . $pri_id . '-col_' . $col_id . '/';

            $list[] = $info;
        }

        return $list;
    }


    /**
     * @param $table
     * @param $pri_id
     * @param $col_id
     * @param bool $raw
     * @return array
     */
    private function reportData($table, $pri_id, $col_id, $raw = false)
    {

        $this->load->model('report_model');

        $fs_report_tables = [
            'fs_analysis',
            'fs_commodity',
            'fs_design',
            'fs_inspiration',
            'fs_trend'
        ];

        $res = [];

        if (in_array($table, $fs_report_tables)) {
            $res = $this->report_model->getFsDatas($table, $pri_id, 'id', $col_id);
        }

        switch ($table) {
            case 't_trend_report':
                $res = $this->report_model->getTrendReport($pri_id, $col_id);
                break;
            case 'mostrend_content':
                $res = $this->report_model->getMosReport($pri_id, $col_id);
                break;
            case 'specialtopic':
                $res = $this->report_model->getSpecialtopic($pri_id, $col_id);
                break;
            case 'specialtopic_graphic':
                $res = $this->report_model->getSpecialtopicGraphic($pri_id, $col_id);
                break;
        }

        if ($raw) {
            return $res;
        }

        $col_name = trim(GetCategory::getOtherFromColId($col_id, 'sName'));

        return ['col_name' => $col_name, 'create_time' => $res['iCreateTime']];
    }


    private function getTDK($page_info)
    {
        $seo = [];

        $seo['title'] = $page_info['title'] . '-' . $this->dict_topics[$this->related_theme_pid]['sName'] . $this->dict_gender[$this->gender] . '-POP服装趋势网';
        $seo['keywords'] = $page_info['title'] . ',' . $this->dict_topics[$this->related_theme_pid]['sName'] . $this->dict_gender[$this->gender] . '服装趋势专题,服装趋势解读,流行趋势预测';
        $seo['description'] = 'POP服装趋势网趋势专题为服装企业和设计师提供全方位阅读趋势专题内容，以故事形式层层深入，在大数据支撑下，通过趋势专题形式，为用户提供趋势前瞻观点，让您更自信的进行风格决策，稳定产品研发步调的同时，兼顾迅速应对市场的变化。';

        return $seo;
    }


}