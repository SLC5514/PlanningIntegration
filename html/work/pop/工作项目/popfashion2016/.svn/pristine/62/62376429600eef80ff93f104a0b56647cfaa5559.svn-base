<?php
/**
 * Created by PhpStorm.
 * User: win7
 * Date: 2016/3/16
 * Time: 10:20
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/*
 * 定时脚本
 */

class Crontabs extends POP_Controller
{
    //memcache缓存前缀
    const FM_TEM_MUTUAL_CACHE = 'FM_TEM_MUTUAL_CACHE_';

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'mutual_model']);
    }

    // 交叉页缓存 token_FDFjjkf-gen_2
    // /usr/local/webserver/php/bin/php /data/htdocs/popfashion2016/index.php crontabs mutual token_LR9UxOvtqviGWw0u
    public function mutual($param = '')
    {
        $params = array_shift(explode('-', $param));
        $token = end(explode('_', $params));
        // memcache缓存
        if ($token == POP_GLOBAL_CRONTABS_KEYS) {
            $aType = [
                0 => '全部', 1 => '男装', 2 => '女装', 5 => '童装', 6 => '毛衫',
                7 => '牛仔', 8 => '皮革/皮草', 9 => '内衣/泳装/家居服',
                10 => '婚纱/礼服', 11 => '运动', 12 => '服饰品', 159 => '梭织'
            ];
            foreach ($aType as $key => $val) {
                if (in_array($key, [1, 2, 5])) {// 性别
                    $data = $this->mutual_model->getMutualData($key, '', true);
                } elseif (in_array($key, [6, 7, 8, 9, 10, 11, 12, 159])) {// 行业
                    $data = $this->mutual_model->getMutualData('', $key, true);
                } else {// 全部
                    $data = $this->mutual_model->getMutualData('', '', true);
                }

                $mem_key = self :: FM_TEM_MUTUAL_CACHE . 'Mutual_type_' . $key . '_' . date('Ymd');
                if ($data) {
                    echo $mem_key . '缓存生成成功！';
                } else {
                    echo $mem_key . '缓存生成失败！';
                }
            }
        } else {
            echo '非法访问!';
        }
    }

    // 面料专区页缓存 token_FDFjjkf-gen_2
    // /usr/local/webserver/php/bin/php /data/htdocs/popfashion2016/index.php crontabs fabriczone token_LR9UxOvtqviGWw0u

    public function fabriczone()
    {

        $this->load->model('Fabriczone_model');
        $data = $this->Fabriczone_model->getFabricData(true);
        $mem_key = 'fabriczone_fabric' . date('Ymd');
        if ($data) {
            echo $mem_key . '缓存生成成功！';
        } else {
            echo $mem_key . '缓存生成失败！';
        }
    }

    public function delHotkeywords()
    {
        $this->load->driver('cache');
        $colmun = GetCategory::getColumn();
        foreach ($colmun as $val) {
            $aColumnIds[] = $val['iColumnId'];
            if (isset($val['col']) && !empty($val['col'])) {
                foreach ($val['col'] as $v) {
                    $aColumnIds[] = $v['iColumnId'];
                }
            }
        }
        $aColumnIds[] = '101';//服装杂志热门推荐关键字 特殊处理
        foreach ($aColumnIds as $iColumnId) {
            $memKey = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_hot_keywords_' . $iColumnId;
            $this->cache->memcached->delete($memKey);
        }
        echo 'success';
    }
}
