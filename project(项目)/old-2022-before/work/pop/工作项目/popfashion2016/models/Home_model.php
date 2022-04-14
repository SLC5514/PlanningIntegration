<?php

/**
 * Created by PhpStorm.
 * User: limeng
 * Date: 2016/5/31
 * Time: 12:04
 * @property-read common_model $common_model
 */
class Home_model extends POP_Model
{
    private $refresh;

    public function __construct()
    {
        parent::__construct();

        $this->refresh = $this->input->get('refresh') ? true : false;
    }

    /**
     * 获取词云中的热词
     */
    public function getHomeKeyWords()
    {
        $memcacheKey = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_home_keywords';
        $this->load->driver('cache');
        $aKeywords = $this->cache->memcached->get($memcacheKey);
        if (!$aKeywords || $this->refresh) {
            //查询栏目
            $sql = 'SELECT sKeyword,sLink,sGrade FROM ' . self::T_FASHION_FM_INDEX . ' WHERE `iStatus`=1 AND `iDisplay`=1 ORDER BY `dUpdateTime` DESC';
            $rows = $this->query($sql);

            // 词云在前台页面展示的字号大小
            $frq = ['A' => '', 'B' => '', 'C' => '', 'D' => ''];
            foreach ($frq as $key => &$val) {
                switch ($key) {
                    case 'A':
                        $val = rand(24, 28);
                        break;
                    case 'B':
                        $val = rand(20, 23);
                        break;
                    case 'C':
                        $val = rand(15, 19);
                        break;
                    case 'D':
                        $val = rand(10, 14);
                        break;
                }
            }
            unset($val);

            $aKeywords = $tempRow = [];
            if ($rows) {
                foreach ($rows as $row) {
                    $_k = strtoupper($row['sGrade']);

                    $tempRow['word'] = $row['sKeyword'];
                    $tempRow['url'] = $row['sLink'];
                    $tempRow['frq'] = $frq[$_k];

                    $aKeywords[] = $tempRow;
                }
            }


            $this->cache->memcached->save($memcacheKey, $aKeywords, 86400);
            if (!$aKeywords) {
                return [];
            }
        }
        return $aKeywords;
    }


    /**
     * 获取有效注册用户的总数
     * 设计师数量 有效注册用户量*1.4
     */
    public function getDesignerCount()
    {
        $this->load->driver('cache');
        $key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_' . 'HOME_DESIGNER_COUNT';

        $num = $this->cache->memcached->get($key);
        if (!$num || $this->refresh) {
            $sql = "SELECT sum(t.num) as num FROM (SELECT COUNT(*) as num FROM fashion.fashion_user UNION SELECT COUNT(*) as num FROM fashion.fashion_user_child ) as t";
            $row = $this->query($sql);
            $num = $row[0]['num'] * 1.4;

            $this->cache->memcached->save($key, $num, 3600);
        }

        return $num;
    }

}