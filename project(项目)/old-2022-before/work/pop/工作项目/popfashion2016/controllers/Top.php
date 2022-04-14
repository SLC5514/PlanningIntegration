<?php

/**
 * Top热榜
 * Created by PhpStorm.
 * User: dsx
 * Date: 2020/3/17
 * Time: 9:51
 */
class Top extends POP_Controller
{

    //custom:自定义; 1男 2女 5童榜; hot:热门榜；region1:日韩榜; region2:欧美榜;
    public $list_type = ['custom', 1, 2, 5, 'hot', 'region1', 'region2'];
    private $filter_table = 'app_user_filter';
    private $user_id;
    private $user_info;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('top_model');
        $this->user_info = get_cookie_value();
        $this->user_id = $this->user_info['id'];//主账号id
        $this->load->database();
    }

    //top热榜列表
    public function index()
    {
        $type = $this->input->get_post('type');//热榜分类 男女童地区
        $filter_id = $this->input->get_post('filter_id');//自定义热榜ID
        $page = max($this->input->get_post('page'), 1);
        $limit = $this->input->get_post('limit') ? $this->input->get_post('limit') : 30;
        $offset = ($page - 1) * $limit;
        $is_ajax = $this->input->is_ajax_request();
        //判断用户权限
        $powers = memberPower();
        $user_type = $powers['P_UserType'];
        if ($is_ajax && !in_array(strtolower($type), $this->list_type)) {
            getJsonInstance()->code(1)->msg('参数错误')->out();
        }

        $lists = [];
        $total_count = 0;
        //是否有自定义热榜
        $count = 0;
        $can_generate = false;
        if ($user_type != 5) {
            //查询热榜总数和ID
            list($count, ) = $this->top_model->getFilterByWhere([], 0, 10);
        }
        switch ($user_type) {
            case 1:
            case 2:
            case 3:
                //1 主账号VIP , 2子账号VIP 、3试用
                if($count<10){
                    $can_generate = true;
                }
                list($total_count, $lists) = $this->top_model->getListDataByType($type, $filter_id, $limit, $offset);
                $total_page = ceil($total_count / $limit);
                break;
            case 4://普通用户
            case 5://游客
                if($user_type == 4 && $count<1){
                    $can_generate = true;
                }
                //款式栏目最新30张
                $condition = $this->top_model->getFashionStyleCondition($type);
                $limit = $limit ? $limit : 30;
                if ($type == 'custom') {
                    $limit = $limit ? $limit : 12;
                }
                $lists = $this->top_model->getFashionStyleList($condition, 0, $limit);
                $total_count = count($lists);
                $total_page = 1;
                break;

        }
        if ($is_ajax) {
            $message = '请求成功';
            if (count($lists) <= 0) {
                $message = '数据为空';
            }
            getJsonInstance()->code(0)->msg($message)->data($lists)->info(['totalCount' => $total_count, 'totalPage' => $total_page])->out();
        }

        $tdk = $this->top_model->getTdk('list');
        $this->assign('tdk', $tdk);
        $this->assign('is_custom_count', $count ? $count : false);
        $this->assign('user_type', $user_type);
        $this->assign('can_generate', $can_generate);
        $this->display('top/top.html');
    }

    //获取自定义热榜条件ID
    public function customFilterId()
    {
        $type = $this->input->get_post('type', true);
        if ($type != 'custom') {
            getJsonInstance()->code(1)->msg('参数错误')->out();
        }
        if(!getUserId()){
            getJsonInstance()->code(1)->msg('未登录')->out();
        }
        //查询热榜总数和ID
        list($count, $rows) = $this->top_model->getFilterByWhere([], 0, 10);
        $return = $this->top_model->getTopTitle($rows);

        getJsonInstance()->code(0)->msg('请求成功')->data($return)->info(['filter_count' => $count])->out();

    }

    //自定义热榜条件数据接口
    public function getStyleLabels()
    {
        $post_data = $this->input->post(NULL);
        $act = $post_data['act'];
        $user_id = $this->user_id;
        if (empty($user_id)) {
            getJsonInstance()->code(1)->msg('未登录')->out();
        }
        //判断用户权限
        $powers = memberPower();
        $user_type = $powers['P_UserType'];

        if ($act == 'modify') {
            //新增或修改
            $rules = [
                ['field' => 'time_range', 'label' => '时间范围', 'rules' => 'required'],
            ];
            $this->load->library('form_validation', $rules);
            $this->form_validation->set_data($post_data);
            $result = $message = $errors = $info = null;

            //验证表单
            if (!$this->form_validation->run()) {
                getJsonInstance()->code(1001)->msg('请选择时间')->out();
            }
            $fileds = ["time_range", "gender", "season", "category", "industry"];
            $data = array_only($post_data, $fileds);
            $insert_data = [];
            $field_assoc = $this->top_model->dict_field;
            foreach ($data as $key => $item) {
                $insert_data[$field_assoc[$key]] = !empty($item) ? trim($item) : '';
            }
            $insert_data['iSite'] = 1;
            $insert_data['iUid'] = $user_id;
            $insert_data['iStatus'] = 1;
            //查询条件是否已存在
            if (!empty($post_data['filter_id'])) {
                $exist_data = $this->db->select('id')->from($this->filter_table)->where($insert_data)->where('id !=', $post_data['filter_id'])->get()->row_array();
            } else {
                $exist_data = $this->db->select('id')->from($this->filter_table)->where($insert_data)->get()->row_array();
            }
            if (!empty($exist_data)) {
                $result = ['filter_id' => $exist_data['id']];
                getJsonInstance()->code(1002)->msg('榜单已经生成过啦')->info($result)->out();
            }
            //查询条件下数据是否大于10条
            $condition = $this->top_model->getTopSolrCondition($insert_data);
            $pop_ids = $this->top_model->getPopIds($condition);
            $total = count($pop_ids);
            if ($total < 10) {
                $result = ['total' => intval($total)];
                getJsonInstance()->code(1003)->msg('将要生成榜单内容数量过少，请重新选择')->info($result)->out();
            }
            //新增时判断已有条件是否超过限制条数
            list($count,) = $this->top_model->getFilterByWhere();
            if (empty($post_data['filter_id'])) {
                if (in_array($user_type, [1, 2, 3])) {
                    $can_generate_total = 10;
                } else {
                    $can_generate_total = 1;
                }
                if ($count >= $can_generate_total) {
                    $result = ['have_count' => $count, 'can_generate_total' => $can_generate_total];
                    getJsonInstance()->code(1004)->msg('您已达到所能生成榜单数量上限,无法继续生成')->info($result)->out();
                }
            }
            //更新or插入条件
            if (!empty($post_data['filter_id'])) {
                $res = $this->db->set($insert_data)->where('id', $post_data['filter_id'])->update($this->filter_table);
                $result = ['filter_id' => $post_data['filter_id']];
            } else {
                $insert_data['dCreateTime'] = date('Y-m-d H:i:s');
                $where_arr = $insert_fileds_arr = $insert_values_arr = [];
                foreach ($insert_data as $filed => $value) {
                    $where_arr[] = "`{$filed}`='{$value}'";
                    $insert_fileds_arr[] = $filed;
                    $insert_values_arr[] = $value;
                }

                $insert_fileds = "`" . implode('`,`', $insert_fileds_arr) . "`";
                $insert_values = "'" . implode("','", $insert_values_arr) . "'";
                $where_string = implode(" AND ", $where_arr);

                //防止重复插入，防止插入超过数量限制
                $sql = "INSERT INTO `app_user_filter` ($insert_fileds) SELECT $insert_values FROM DUAL WHERE 
                        NOT EXISTS(SELECT count(1) AS cnt FROM  app_user_filter WHERE iSite='1' AND iStatus=1 AND iUid='{$user_id}' HAVING cnt>={$can_generate_total} LIMIT 1) 
                        AND NOT EXISTS(SELECT id FROM  app_user_filter WHERE $where_string)";
                $this->db->query($sql);
                $res = $this->db->insert_id();
                $result = ['filter_id' => $res];
            }
            if ($res) {
                $code = 200;
                $message = "生成热榜成功!";
            } else {
                $code = 1005;
                $message = "生成热榜出现异常请重试!";
            }

            getJsonInstance()->code($code)->msg($message)->data($result)->out();

        } elseif ($act == 'del') {
            //删除
            if (empty($post_data['filter_id'])) {
                getJsonInstance()->code(1)->msg('无需要删除的ID')->out();
            }

            $row = $this->db->from($this->filter_table)->where(['iSite' => 1, 'id' => $post_data['filter_id'], 'iUid' => $user_id, 'iStatus' => 1])->get()->row_array();
            if (empty($row)) {
                getJsonInstance()->code(1)->msg('您要删除的榜单不存在')->out();
            }

            $id = $post_data['filter_id'];
            $result = $this->db->set('iStatus', 0)->where(['iUid' => $user_id, 'id' => $id])->update($this->filter_table);
            $code = $result ? 0 : 1;
            getJsonInstance()->code($code)->msg('请求成功')->out();

        } else {
            //获取热榜筛选条件
            $return = $this->top_model->getStyleLabels();
            getJsonInstance()->code(0)->msg('请求成功')->data($return)->out();
        }

    }

    //自定义热榜详情
    public function detail()
    {
        $filter_id = $this->input->get('filter_id');//自定义标签ID
        $page = max($this->input->get('page'), 1);
        $limit = $this->input->get('limit') ? $this->input->get('limit') : 30;
        $offset = ($page - 1) * $limit;

        $is_ajax = $this->input->is_ajax_request();

        if (!$filter_id) {
            show_404();
        }
        //判断用户权限
        $powers = memberPower();
        $user_type = $powers['P_UserType'];
        if ($user_type == 5 ) {
            if($is_ajax) {
                getJsonInstance()->code(1)->msg('未登录')->out();
            }else{
                header('Location:/member/pagelogin/');
            }
        }
        $res = [];
        $where = ['iUid' => $this->user_id, 'iStatus' => 1, 'id' => $filter_id];
        list(, $row) = $this->top_model->getFilterByWhere($where);

        if (!empty($row)) {
            if($is_ajax){
                if($user_type==4){//普通用户
                    //款式栏目最新30张
                    $condition = $this->top_model->getFashionStyleCondition('hot');
                    $lists = $this->top_model->getFashionStyleList($condition, $offset, $limit);
                    $total_count = 100;
                    $total_page = ceil($total_count/$limit);
                    
                }else {//VIP
                    list($total_count, $lists) = $this->top_model->getListDataByType('custom', $filter_id, $limit, $offset);
                    $total_page = ceil($total_count / $limit);//总页数
                }

                getJsonInstance()->code(0)->msg('请求成功')->data($lists)->info(['totalPage' => $total_page, 'totalCount' => $total_count,])->out();

            }else{
                $res = $this->top_model->getTopTitle($row);
                $date_range = $res[0]['date_range'];
            }
        } else {

            if ($is_ajax) {
                getJsonInstance()->code(1)->msg('该数据不存在')->out();
            }

        }
        $title = !empty($res[0]['title_arr']) ? implode('', $res[0]['title_arr']) : '';
        $title_glue = !empty($res[0]['title_arr']) ? implode(' ', $res[0]['title_arr']) : '';
        $this->assign('title', $title);
        $this->assign('title_glue', $title_glue);
        $this->assign('date_range', $date_range ? $date_range : '');

        $tdk = $this->top_model->getTdk('detail', $row[0]);
        $this->assign('tdk', $tdk);
        $this->assign('user_type', $user_type);
        $this->display('top/top_detail.html');
    }

    //流行分析-爆款数据-最新报告
    public function getTopsReport()
    {
        $this->load->model('analysis_model');
        $lists = [];
        // 获取报告列表
        $powers = memberPower();
        $totalCount = $this->analysis_model->getAnalysisLists('', 132, $lists, 0, 15, $powers);
        getJsonInstance()->code(0)->msg('请求成功')->data($lists)->out();

    }
}