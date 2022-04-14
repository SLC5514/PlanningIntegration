<?php


class Suggestion extends POP_Controller
{

    public $table_name = 't_keyword_search_log';

    /* public function __construct()
     {
         parent::__construct();
     }*/


    /**
     * 收录用户输入的关键词到mysql做推荐词源
     */
    public function story()
    {
        $this->load->model('common_model');

        $db = $this->common_model->db();


        $data = [];

        $data['name'] = $this->input->post('keyword');
        $data['user_type'] = memberPower()['P_UserType'];
        $data['create_time'] = date('Y-m-d H:i:s');

        $db->insert($this->table_name, $data);

        echo json_encode(['code' => 0, 'data' => [], 'msg' => 'success']);
        return;
    }

    /**
     * 拼写建议或热词推荐
     */
    public function index()
    {
        $this->load->config('solr');
        $config = config_item('solr');

        foreach ($config['endpoint'] as &$item) {
            if (isset($item['core'])) {
                $item['core'] = 'hot_key';
            }
        }

        $client = new Solarium\Client($config);

        $query = $client->createQuery($client::QUERY_SELECT);
        $query->setFields(['keyword', 'score']);
        $query->setHandler('suggest_topic');

        $keyword = $this->input->post('keyword');
        if ($keyword) {
            $query->addParam('q', "$keyword AND pop_self:1");
        } else {
            $query->addParam('q', '*:*');
        }
        $res = $client->select($query);

        $data = $res->getData()['response']['docs'];
        echo json_encode(['code' => 0, 'data' => $data, 'msg' => 'success']);
        return;
    }
}