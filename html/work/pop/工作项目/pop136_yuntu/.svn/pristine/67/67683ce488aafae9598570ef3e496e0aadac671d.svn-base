<?php

/**
 * Created by PhpStorm.
 * User: dsx
 * Date: 2019/7/30
 * Time: 15:32
 */
class Recommend extends POP_Controller
{
    const COL = 3;
    static $table = ['product_graphicitem' => 'graphicitem', 'product_fabricgallery_item' => 'fabricgalleryitem'];

    function __construct()
    {
        parent::__construct();
        $this->assign("col", self::COL);
        $this->get_use_type(self::COL);
        $this->checkPower();
    }

    /**
     * 个性化推荐
     */
    public function index()
    {
        $condition = $lists = [];
        $flag = $this->input->get_post('list', true);
        $size = 60;
        if ($flag) {
            $size = 6;//只取6张
        }
        $user_id = getUserId();
        $condition['userid'] = $user_id;
        $condition['cateid'] = '服装_图案_图案素材';
        $condition['cnt'] = $size;
        $condition['scene_type'] = 'personal_cloud';
        $response = DataGrandSiteApi::getRecGrandData($condition);
        if (strtolower($response['status']) == 'ok') {
            $rec_data = !empty($response['recdata']) && isset($response['recdata']) ? $response['recdata'] : [];
            $lists = $this->deal_data($rec_data, 'personal_cloud', true);
        }
        if ($this->input->is_ajax_request() || $this->input->get('ajax', true)) {
            outPrintApiJson(0, 'ok', $lists, ['scene_type' => 'personal_cloud', 'request_id' => $response['request_id']]);
        }
        $this->assign('lists', $lists);
        $this->assign('is_recommend', true);
        $this->display('recommend/recommend.html');

    }

    //相关推荐-详情猜你喜欢数据
    public function get_like_data()
    {
        $t = $this->input->get_post('t', true);
        $id = $this->input->get_post('id', true);
        $user_id = getUserId();

        $this->load->model('Graphicitem_model');
        // $_popId = 'graphicitem_401655';// test
        $_popId = "{$t}_{$id}";// 假表名_id
        $item_id = $this->Graphicitem_model->getSiteByPopId(self::$table, $_popId);

        $lists = array();
        if ($user_id && !empty($item_id)) {
            $condition['itemid'] = $item_id;
            $condition['userid'] = $user_id;
            $condition['cateid'] = '服装_图案_图案素材';
            $condition['cnt'] = 20;
            $condition['scene_type'] = 'relate_cloud';
            $response = DataGrandSiteApi::getRelateGrandData($condition);

            if ($this->input->is_ajax_request() || $this->input->get('ajax', true)) {
                if (strtolower($response['status']) == 'ok') {
                    $rec_data = !empty($response['recdata']) && isset($response['recdata']) ? $response['recdata'] : [];
                    $lists = $this->deal_data($rec_data, 'relate_cloud', true);
                }
            }
        }

        outPrintApiJson(0, 'ok', $lists, ['scene_type' => 'relate_cloud', 'request_id' => $response['request_id']]);
    }

    /**
     * 处理数据
     * @param array $rec_data 达观返回的itemid
     * @param string $type 场景（个性化和相关）
     */
    public function deal_data($rec_data, $type = 'personal_cloud', $is_list = false)
    {
        $return = [];

        foreach ($rec_data as $value) {
            $item_id = $value['itemid'];
            $table = substr($item_id, stripos($item_id, '_') + 1, strripos($item_id, '_') - 2);
            $id = substr($item_id, strripos($item_id, '_') + 1);
            $table_ids[$table][] = $id;
        }

        if (!empty($table_ids)) {
            foreach ($table_ids as $table => $id) {
                $result = OpPopFashionMerger::getProductData($id, $table);
                foreach ($result as $id => $info) {
                    if (!empty($info)) {
                        $imgs = getImagePath($table, $info);
                        $return[] = [
                            't' => self::$table[$table],
                            'id' => $id,
                            'cover' => $imgs["cover"],
                        ];
                    }
                }
            }
        }

        return $return;
    }

    //保存已提示过的用户 (智能设计下线提示条显示)
    public function save_show_tip_user()
    {
        $user_id = getUserId();
        $mem = 'POP_YUNTUMIYIN_OFFLINETIPS_' . $user_id;
        $have_user = $this->cache->memcached->get($mem);
        if (!$have_user) {
            $this->cache->memcached->save($mem, $user_id, 0);
        }
        outPrintApiJson(0, 'ok');
    }

}

