<?php

/**
 * 前台商城model
 * Date: 2017/03/07
 * @property-read common_model $common_model
 */
class Item_model extends POP_Model
{
    const TABLENAME = '`fashion`.`t_industry_activity`';
    const ACTIVITY_DICT = '`fashion`.`t_dict_industry_activity`';
    const T_COMMODITY_FIRST = '`fashion`.`t_commodity_first`';
    const FM_AD = '`fashion`.`fm_ad`';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
    }

    /**
     * 获取轮播图
     * @return array
     */
    public function getLoopPicsForIndex()
    {
        return $this->common_model->getAds(0, 13);
    }

    /**
     * 获取首推商品
     * @return array
     */
    public function getRecommendList()
    {
        $sql = "select id,sCommodityUrl,sCoverUrl from " . self::T_COMMODITY_FIRST . " order by id desc limit 3";
        $rows = $this->query($sql);
        return $rows;
    }

    /* *
     * @todo    getConditions   [获取solr的查询条件]
     * @param   $paramsArr      array  [参数]
     * @return  $conditions  array   [solr条件]
     */
    public function getConditions($paramsArr = [])
    {
        // 正常的
        $conditions['iStatus'] = 1;
        // 上架的
        $conditions['iCommodityStatus'] = 1;
        // 发布的
        $conditions['iPublishStatus'] = 1;
        // 商品表
        $conditions['tablename'] = 't_commodity';
        // 大类
        if (isset($paramsArr['mct'])) {
            $conditions['iCategory'] = $paramsArr['mct'];
        }
        // 小类
        if (isset($paramsArr['mst'])) {
            $conditions['iSubcategory'] = $paramsArr['mst'];
        }
        // 关键词
        if (isset($paramsArr['memo'])) {
            $conditions['combine'] = $paramsArr['memo'];
        }
        return $conditions;
    }

    /**
     *  获取正常上架状态的条件
     * @param   $condition   array  [参数]
     * @return  $conditions  array   [solr条件]
     */
    public function getPublishItemCondition($condition = array())
    {
        $condition['iCommodityStatus'] = 1; //上架状态
        $condition['iPublishStatus'] = 1; //上架状态
        $condition['iStatus'] = 1; //上架状态
        $today = date("Y-m-d\T00:00:00\Z");
        $condition['dActivityStartTime'] = "[* TO $today]"; //开始时间 不大于今天
        $condition['dActivityEndTime'] = "[$today TO *]"; //结束时间 不小于今天
        return $condition;
    }

    public function getItemList($condition = array(), &$lists, $page = 1, $limit = 10)
    {
        // 商品列表，一页取10条
        $offset = ($page - 1) * $limit;
        // 排序 发布时间倒序，热卖的商品靠前显示【1-热卖 2-不是热卖】
        $arSort = ['iHotSale' => 'ASC', 'dPublishTime' => 'DESC'];

        $condition = $this->getPublishItemCondition($condition);

        $result = [];
        $totalCount = POPSearch::wrapQueryPopIndustryActivity('', $condition, $result, $offset, $limit, $arSort);
        $ids = [];
        foreach ($result as $key => $value) {
            $ids[] = $value['pri_id'];
        }

        if ($ids) {
            $lists = OpPopIndustryActivity::getProductData($ids, 't_commodity');
            $lists = $this->handleItemInfo($lists);
        } else {
            $lists = [];
        }
        return $totalCount;
    }

    public function handleItemInfo($lists = [], $isDetail = false)
    {
        foreach ($lists as $key => $val) {
            if ($val['sOriginalPrice'] == '0.00') {
                $lists[$key]['sOriginalPrice'] = '';
            }
            // 商品图片信息
            $images = json_decode($val['sPicture'], true);
            $lists[$key]['images'] = $images;
            $lists[$key]['firstImage'] = $images[0];
            if (!$isDetail) {
                continue;
            }
            // 商品售价信息
            $sSellingPriceInfo = json_decode($val['sSellingPrice'], true);
            $lists[$key]['sSellingPriceInfo'] = $sSellingPriceInfo;
            // 商品价格范围
            $range = [];
            foreach ($sSellingPriceInfo as $value) {
                $range[] = $value['price'];
            }
            $minPrice = min($range);
            $maxPrice = max($range);
            if ($minPrice == $maxPrice) {
                $lists[$key]['priceline'] = "{$minPrice}";
            } else {
                $lists[$key]['priceline'] = "{$minPrice}-{$maxPrice}";
            }
        }
        return $lists;
    }

    /**
     * 通过商品的大类计算对应的各个小类的商品个数
     * @param  string $keyword [description]
     * @param  [type] $condition [description]
     * @return [type]            [description]
     */
    public function getItemGroupCount($keyword = '', $condition = [])
    {
        $iCategory = $condition['iCategory'];
        //去除子类
        if (isset($condition['iSubcategory'])) {
            unset($condition['iSubcategory']);
        }
        $condition = $this->getPublishItemCondition($condition);
        if ($iCategory) {
            $filed = 'iSubcategory';
            $params = array();
            $params['group'] = 'true';
            $params['group.offset'] = 0;
            $params['group.limit'] = 0;//每组取得数据条数
            $params['group.ngroups'] = 'true';
            $params['group.field'] = $filed;
            $params['group.sort'] = ['iSubcategory' => 'desc'];
            $result = [];
            $arSort = ['iSubcategory' => 'asc'];
            POPSearch::wrapQueryPopIndustryActivity($keyword, $condition, $result, 0, 0, $arSort, $params);
            $total = $result[$filed]['ngroups'];
            POPSearch::wrapQueryPopIndustryActivity($keyword, $condition, $result, 0, $total, $arSort, $params);

            $subCategory = OpPopIndustryActivity::getDictCategory('itemTypeChild');

            $groups = $result[$filed]['groups'];
            $res = [];
            foreach ($groups as $key => $value) {
                $subCategoryId = $value['groupValue'];
                $link = '/item/seclist/mct_' . $iCategory . '-mst_' . $subCategoryId . '/';
                if ($keyword) {
                    $link .= '?keyword=' . rawurlencode($keyword);
                }
                $res[] = [
                    'mst' => $subCategoryId,
                    'txt' => $subCategory[$iCategory][$subCategoryId],
                    'link' => $link
                ];
            }
        } else {
            $res = [];
        }
        return $res;
    }

    /**
     * @param $conditions 推荐条件
     * @param int $limit 推荐条数
     * @param string $keyword 关键字
     * @return array ['id'=>'图片路径']
     */
    public function getMoreRecommend($conditions, $limit = 3, $keyword = '')
    {
        $recommendList = $conditonbase = [];

        $conditonbase = $this->getPublishItemCondition($conditonbase);

        // $conditonbase['iStatus'] = 1;// 正常的
        // $conditonbase['iCommodityStatus'] = 1;// 上架的
        // $conditonbase['iPublishStatus'] = 1;// 发布的
        $conditonbase['tablename'] = 't_commodity';// 商品表
        $conditonbase['other'][] = '-(pri_id:' . $conditions['id'] . ')';// 排除自身

        //先按同小类若无则按大类若无再推荐所有商城里热门商品【随机】
        $arSort = ['iHotSale' => 'ASC', 'dPublishTime' => 'DESC'];

        $conditonSub = $conditonbase;
        $conditonSub['iSubcategory'] = $conditions['iSubcategory'];
        $count = POPSearch::wrapQueryPopIndustryActivity($keyword, $conditonSub, $result, 0, $limit, $arSort);

        if ($count < $limit) {

            $conditonCategory = $conditonbase;
            $conditonCategory['iCategory'] = $conditions['iCategory'];
            $conditonCategory['other'][] = '-(iSubcategory:' . $conditions['iSubcategory'] . ')';
            POPSearch::wrapQueryPopIndustryActivity($keyword, $conditonCategory, $resultCategory, 0, $limit - $count, $arSort);
            $result = array_merge($result, $resultCategory);
            $count = count($result);

            if ($count < $limit) {
                $conditonHot = $conditonbase;
                $conditonHot['other'][] = '-(iCategory:' . $conditions['iCategory'] . ')';
                $conditonHot['iHotSale'] = 1;// 热门 随机取
                POPSearch::wrapQueryPopIndustryActivity($keyword, $conditonHot, $resultHot, 0, $limit - $count, $arSort);
                $result = array_merge($result, $resultHot);
            }
        }

        foreach ($result as $item) {
            $itemInfo = OpPopIndustryActivity::getProductData($item['pri_id'], $conditonbase['tablename']);
            $itemInfo = $itemInfo[$item['pri_id']];
            $pictures = json_decode($itemInfo['sPicture'], true);
            $recommendList[$item['pri_id']] = [
                'link' => '/item/detail/id_' . $item['pri_id'] . '/',
                'sName' => $itemInfo['sName'],
                'firstImage' => array_shift($pictures)
            ];
        }

        return $recommendList;
    }
}
