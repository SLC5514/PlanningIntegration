<?php

/**
 * 个人中心-浏览历史记录接口
 * Class PersonalCenter
 */
class PersonalCenter extends POP_Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    //获取浏览历史列表
    public function get_p_his()
    {
        //参数:栏目 筛选日期 页码
        $params = $this->input->post(null, true);
        $col = isset($params['col']) && !empty($params['col']) ? $params['col'] : '';//栏目
        $date = isset($params['date']) && !empty($params['date']) ? $params['date'] : '';//筛选日期 Y-m-d
        $page = isset($params['page']) && !empty($params['page']) ? $params['page'] : 1;//页码
        //第一次请求的总数 用于分页的总数
        $length = isset($params['length']) && !empty($params['length']) ? $params['length'] : 0;

        $limit = 32;
        if ($col == 1) {//报告分页数量
            $limit = 24;
        }

        $d = empty($date) ? '' : str_replace('-', '', $date);

        $uid = getUserId();
        if (!$uid) {
            getJsonInstance()->code(0)->msg('请先登录')->out();
        }
        if (!$col) {
            getJsonInstance()->code(0)->msg('栏目id不能为空')->out();
        }
        if (!$d) {
            getJsonInstance()->code(0)->msg('日期不能为空')->out();
        }

        $return = [];
        $redisConn = getRedisConnection();

        //取筛选日期和栏目的列表
        $pop_ids_key = "fashion_history:{$uid}_{$col}_{$d}";
        if ($redisConn->exists($pop_ids_key)) {
            list($total_page, $lists, $length) = $this->getPageNum($pop_ids_key, $page, $limit, $length);
            $lists = $this->getData($lists, $col);
            $return[$date] = [
                'totalPage' => $total_page,//总页数
                'list' => $lists,
                'length' => $length,//总条数
            ];
        }
        //数据被删除
        if (empty($return)) {
            getJsonInstance()->code(0)->msg('列表数据为空')->out();
        }
        if ($page > $total_page) {
            getJsonInstance()->code(0)->msg('超过最大页码')->out();
        }

        getJsonInstance()->code(0)->msg('ok')->data($return)->out();
    }

    //获取30天每个日期是否有数据
    public function get_date_count()
    {
        //参数:栏目
        $params = $this->input->post(null, true);

        $uid = getUserId();
        $col = isset($params['col']) && !empty($params['col']) ? $params['col'] : '';//栏目
        if (!$uid) {
            getJsonInstance()->code(0)->msg('请先登录')->out();
        }
        if (!$col) {
            getJsonInstance()->code(0)->msg('栏目id不能为空')->out();
        }

        $return = $date_arr = [];
        $end_date = strtotime(date('Y-m-d'));
        $start_time = strtotime(date('Y-m-d', strtotime('-29 days')));

        while ($start_time <= $end_date) {
            $key = date('Y-m-d', $start_time);
            $date_arr[] = $key;
            $start_time = strtotime('+1 days', $start_time);
        }
        arsort($date_arr);
        $redisConn = getRedisConnection();

        foreach ($date_arr as $date) {
            $d = str_replace('-', '', $date);
            $redisKey = "fashion_history:{$uid}_{$col}_{$d}";
            $count = 0;
            if ($redisConn->exists($redisKey)) {
                $count = $redisConn->lLen($redisKey);
            }
            $return[$date] = $count ? 1 : 0;
        }

        //区分权限页面 0=有权限有记录     1=有权限无记录    2=无权限无记录     3=无权限有记录
        //array(账号类型P_UserType => 1 主账号是vip , 2子账号是vip 、3试用 4普通 5游客);
        $vipTypeArr = memberPower('other');
        $flag = 0;

        $list_num = count(array_filter($return));
        if (in_array($vipTypeArr['P_UserType'], [1, 2, 3])) {//有权限无记录
            if ($list_num <= 0) {
                $flag = 1;
            }
        } else {
            if ($list_num > 0) {//无权限有记录
                $flag = 3;
            }else{//无权限无记录
                $flag = 2;
            }
        }

        getJsonInstance()->code(0)->msg('ok')->data($return)->info(['flag'=>$flag,'user_type'=>$vipTypeArr['P_UserType']])->out();

    }

    //删除浏览历史
    public function del_his()
    {
        //参数:栏目 筛选日期 t  id
        $params = $this->input->post(null, true);
        $col = isset($params['col']) && !empty($params['col']) ? $params['col'] : 0;//父栏目
        $sub_col = isset($params['sub_col']) && !empty($params['sub_col']) ? $params['sub_col'] : 0;//子栏目
        $date = isset($params['date']) && !empty($params['date']) ? $params['date'] : '';//筛选日期 Y-m-d
        $t = isset($params['t']) && !empty($params['t']) ? $params['t'] : '';//表名
        $id = isset($params['id']) && !empty($params['id']) ? $params['id'] : '';//id
        $d = empty($date) ? '' : str_replace('-', '', $date);
        if($t && $id){
            $table = getProductTableName($t);
            $pop_id =   $table.'_'.$id;
        }

        $uid = getUserId();
        if (!$uid) {
            getJsonInstance()->code(0)->msg('请先登录')->out();
        }
        if (!$col) {
            getJsonInstance()->code(0)->msg('栏目id不能为空')->out();
        }

        $redisConn = getRedisConnection();
        $pop_ids_key = "fashion_history:{$uid}_{$col}_{$d}";

        if (!empty($d)) {
            if($pop_id){//删除单条记录

                //删除pop_id_key
                $pop_key = "fashion_history_pop_ids:{$uid}_{$col}_{$d}_{$pop_id}";
                if ($redisConn->exists($pop_key)) {
                    $del_key_num2 = $redisConn->delete($pop_key);
                }
                //删除list_key
                if($redisConn->exists($pop_ids_key)){
                    $del_key_num1= $redisConn->lRem($pop_ids_key,$pop_id.'-'.$sub_col,0);
                }


            }else {//按天删除

                if ($redisConn->exists($pop_ids_key)) {
                    //删除list_key
                    $del_key_num1 = $redisConn->delete($pop_ids_key);
                    //删除pop_id_key
                    $pop_key = "fashion_history_pop_ids:{$uid}_{$col}_{$d}_*";
                    $keys = $redisConn->keys($pop_key);
                    $del_key_num2 = $redisConn->delete($keys);
                }
            }
        } else {//删除所有
            $date_arr = [];
            $end_date = strtotime(date('Y-m-d'));
            $start_time = strtotime(date('Y-m-d', strtotime('-13 days')));

            while ($start_time <= $end_date) {
                $key = date('Ymd', $start_time);
                $date_arr[] = $key;
                $start_time = strtotime('+1 days', $start_time);
            }

            foreach ($date_arr as $d) {
                $redisKey = "fashion_history:{$uid}_{$col}_{$d}";
                //删除list
                $del_key_num1 = $redisConn->delete($redisKey);
                //删除pop_id_key
                $pop_key = "fashion_history_pop_ids:{$uid}_{$col}_{$d}_*";
                $keys = $redisConn->keys($pop_key);
                $del_key_num2 = $redisConn->delete($keys);

            }

        }
        if ($del_key_num1) {
            getJsonInstance()->code(0)->msg('删除成功')->out();
        } else {
            getJsonInstance()->code(0)->msg('删除失败')->out();
        }
    }

    //获取redis中 list分页后的数据
    protected function getPageNum($redisKey, $page = 1, $stop, $length)
    {
        $lists = [];
        $redisConn = getRedisConnection();
        // $redisKey = "fashion_history:106645_4_20200417";
        $count = $redisConn->lLen($redisKey);
        $length = !empty($length) && !empty($page) ? $length : $count;
        $total_page = ceil($length / $stop);


        if ($page <= $total_page) {

            if (empty($page) || $page == 1) {
                $start = 0;
            } else {
                $start = ($page - 1) * $stop;
                $stop *= $page;
            }
            $stop -= 1;

            //redis追加的数据偏移量
            $start = $start + ($count - $length);
            $stop = $stop + ($count - $length);
            $lists = $redisConn->lRange($redisKey, $start, $stop);
        }

        return [$total_page, $lists, $length];
    }


    //接口-最新报告
    public function g_report()
    {
        if (getUserId()) {

            $this->load->model(['RelateHome_model']);
            //偏好设置和模块权限数组
            $module = $this->RelateHome_model->module;

            //最新报告
            $new_report = [];
            $new_report = $this->RelateHome_model->get_new_report($module['new_report_relate']);

            getJsonInstance()->code(0)->msg('ok')->data($new_report)->out();

        } else {
            getJsonInstance()->code(0)->msg('请先登录')->out();

        }
    }

    //取列表信息
    protected function getData($pop_ids = [], $col = '')
    {
        if (empty($pop_ids)) {
            return [];
        }
        $return = [];

        foreach ($pop_ids as $pop_id) {
            //取子栏目id
            $pop_id_arr = explode('-', $pop_id);
            $column = end($pop_id_arr);
            array_pop($pop_id_arr);

            //取表名 id
            $pop_id_arr = explode('_', $pop_id_arr[0]);
            $id = end($pop_id_arr);
            array_pop($pop_id_arr);
            $table = implode('_', $pop_id_arr);
            $t = getProductTableName($table);

            $detail_url = '';
            $url = "t_{$t}-id_{$id}-col_{$column}";
            switch ($col) {
                case 1:
                    $detail_url = "/details/report/{$url}/";
                    break;
                case 4:
                case 9:
                    $detail_url = "/details/style/{$url}/";
                    break;
            }


            $rs = OpPopFashionMerger::getProductData($id, $table);
            $info = empty($rs[$id]) ? [] : $rs[$id];
            if (!empty($info)) {
                $path = getImagePath($table, $info);
                $image_path = getFixedImgPath($path['cover']);
                $title = '';
                // 报告标题
                switch ($table) {
                    case 'specialtopic':
                    case 'specialtopic_graphic':
                    case 'mostrend_content':
                        $title = $info['title'];// 发布时间
                        break;
                    case 't_trend_report':
                        $title = $info['sTitle'];//标题
                        break;
                }
                $return[] = [
                    'id' => $id,
                    't' => $t,
                    'col' => $col,
                    'sub_col'=>$column,
                    'small_url' => $image_path,
                    'title' => !empty($title) ? htmlspecialchars(stripslashes($title)) : '',
                    'detail_url' => $detail_url,
                ];
            }

        }

        return array_filter($return);
    }

}