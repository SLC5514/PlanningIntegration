<?php
/**
 * @ 云图APP达观推荐
 * Created by PhpStorm.
 * User: gcl
 * Date: 2019/9/24
 * Time: 10:39
 */
require_once FCPATH . "/core/APP_Controller.php";

class Recommend extends APP_Controller
{
    // 构建达观推荐，轻易不要改变
    static $table = ['product_graphicitem' => 'graphicitem', 'product_fabricgallery_item' => 'fabricgalleryitem'];
    static $temp_userId = 796279; // 游客暂定的推荐id,同服装英文站

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @ 个性化推荐
     * https://yuntu.pop136.com/api/recommend/getlist
     * post
     */
    public function getList()
    {
        $condition = $lists = array();
        $userId = $this->check_jwt_token();
        if (!$userId) {
            $userId = self::$temp_userId; // 游客暂定的推荐id,同服装英文站
        }
        $size = 10; // 列表页每次获取10张(首页只获取50条数据)
        $condition['userid'] = $userId;
        // 新增大牌花型，服装图案与家纺图案获取到全量（产品要求的）排除家纺的表，只有服装
        $condition['cateid'] = '服装_图案,家纺_图案_大牌花型,家纺_图案_原创画稿,家纺_图案_图案花型';
        $condition['cnt'] = $size;
        $condition['scene_type'] = 'personal_cloud_app';
        $response = DataGrandSiteApi::getRecGrandData($condition);
        // 获取数据
        if (strtolower($response['status']) == 'ok') {
            $rec_data = !empty($response['recdata']) && isset($response['recdata']) ? $response['recdata'] : [];
            $lists = $this->deal_data($rec_data);
        }

        // 原来图案搜索的总数在首页显示
        $this->load->driver('cache');
        $memcacheKey = "POP_YUNTU_PATTERN_ALL_TOTAL_DATA";
        $allTotal = $this->cache->memcached->get($memcacheKey);
        if (empty($allTotal)) {
            $this->load->model('Graphicitem_model');
            $rows = array();
            $allTotal = $this->Graphicitem_model->getList($rows, array(), 0, 1);
            $this->cache->memcached->save($memcacheKey, $allTotal, 86400);
        }
        if ($lists) {
            foreach ($lists as &$items) {
                $items['scene_type'] = 'personal_cloud_app';
                $items['request_id'] = $response['request_id'];
            }
            $requestTotal = intval(count($lists));// 每次请求，本地获取数据的数量
            $recommendTotal = intval(count($rec_data));// 每次请求，达观推荐的数量
            outPrintApiJson(0, 'ok', $lists, ['scene_type' => 'personal_cloud_app', 'request_id' => $response['request_id'], 'allTotal' => $allTotal, 'requestTotal' => $requestTotal, 'recommendTotal' => $recommendTotal]);
        } else {
            outPrintApiJson(100080, '获取列表数据失败');
        }
    }

    /**
     * @ 相关推荐--详情的猜你喜欢
     * https://yuntu.pop136.com/api/recommend/guesslike
     */
    public function guessLike()
    {
        $popId = $this->input->get_post('popId', true);
        $userId = $this->check_jwt_token();
        if (!$userId) {
            $userId = self::$temp_userId;// 游客暂定的推荐id,同服装英文站
        }

        $this->load->model('Graphicitem_model');
        // $popId = 'graphicitem_401655'; // 假表名_id
        $item_id = $this->Graphicitem_model->getSiteByPopId(self::$table, $popId);

        $lists = array();
        if ($userId && !empty($item_id)) {
            $condition['itemid'] = $item_id;
            $condition['userid'] = $userId;
            // 新增大牌花型，服装图案与家纺图案获取到全量（产品要求的）
            $condition['cateid'] = '服装_图案,家纺_图案_大牌花型,家纺_图案_原创画稿,家纺_图案_图案花型';
            $condition['cnt'] = 20;
            $condition['scene_type'] = 'relate_cloud_app';
            $response = DataGrandSiteApi::getRelateGrandData($condition);

            if (strtolower($response['status']) == 'ok') {
                $rec_data = !empty($response['recdata']) && isset($response['recdata']) ? $response['recdata'] : [];
                $lists = $this->deal_data($rec_data);
            }
        }
        if (!empty($lists) && isset($lists)) {
            foreach ($lists as &$items) {
                $items['scene_type'] = 'relate_cloud_app';
                $items['request_id'] = $response['request_id'];
            }
            outPrintApiJson(0, 'ok', $lists, ['scene_type' => 'relate_cloud_app', 'request_id' => $response['request_id']]);
        } else {
            outPrintApiJson(100081, '获取详情数据失败');
        }
    }

    // 处理数据 列表--个性化推荐|详情--相关推荐
    private function deal_data($rec_data)
    {
        // test
        /*$rec_data = [['itemid' => '1_product_graphicitem_401655'], ['itemid' => '1_product_graphicitem_401656'],
            ['itemid' => '1_product_graphicitem_401649'], ['itemid' => '1_product_graphicitem_401650'],];*/
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
                            'popId' => self::$table[$table] && $id ? self::$table[$table] . '_' . $id : '',
                            'id' => $id ?: '',
                            't' => self::$table[$table] ?: '',
                            'imgPath' => $imgs["cover"] ? $imgs["cover"] : '',
                        ];
                    }
                }
            }
        }
        return $return;
    }
}