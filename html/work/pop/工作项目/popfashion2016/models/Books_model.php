<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Books栏目 专用类
 *
 * @property-read common_model $common_model
 * @property-read details_model $details_model
 * @property-read video_model $video_model
 */
class Books_model extends POP_Model
{
    public $oMongo;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
        $this->load->model('details_model');
    }

    /**
     * [getBookLists 获取每页单张展示的数据+总条数]
     * @param string $params [url参数]
     * @param integer $columnId [栏目id]
     * @param integer $offset [偏移量]
     * @param integer $limit [每页条数]
     *
     * @param string $source [来源，book为手稿合辑、个人中心共享资料-trends趋势手稿、vector矢量书稿]
     *
     * @return [array]   &$lists     [根据条件查询出的结果]
     * @return [array]   $totalCount [根据条件查询出的总条数]
     */
    public function getBookLists($params = '', $columnId, &$lists, $offset = 0, $limit = 10, $powers = array(), $source = '')
    {
        $this->benchmark->mark('getBookLists');
        $paramsArr = [];
        if (!empty($params)) {
            $paramsArr = $this->common_model->parseParams($params);
        }
        $lists = [];
        $columnPid = GetCategory::getOtherFromColId($columnId);
        if ($columnPid == 0) {
            $columnPid = $columnId;
        }
        // 条件
        $conditions = $this->getConditions($params, $columnId, $powers);

        if (in_array($columnId, [70, 71, 72, 115, 131])) {
            $conditions['iDisplay'] = 1;
        }

        // 排序
        $arSort = $this->common_model->getSort($params, $powers, $columnPid);

        $result = [];
        $totalCount = POPSearch::wrapQueryPopFashionMerger('', $conditions, $result, $offset, $limit, $arSort);
        // T台 + 视频专栏表 判断 视频是否存在
        if (!empty($result) && $columnId == 71) {
            $this->load->model('video_model');
            $videoExistDict = $this->video_model->getVideoExist($result, $columnId);
        }
        $collectStatusList = $this->getListDataCollectStatus($result);
        foreach ($result as $key => $val) {
            $id = $val['pri_id'];
            $tableName = $val['tablename'];
            $data = OpPopFashionMerger::getProductData($id, $tableName == 'product_vector_refrence_group' ? 'product_vector_refrence_list' : $tableName);
            $info = $data[$id];

            if ($tableName == 'product_design_refrence') {
                $sql = "SELECT * FROM " . self::T_FASHION_T_VECTOR_RELATED_TO_DESIGN . " WHERE `iDesignId`=" . $id . " AND `iStatus`=1";
                $info['vectorIcon'] = $this->query($sql) ? 1 : 0;
            }

            // 性别和总条数
            switch ($tableName) {
                case 'product_vector_refrence_group':
                    $info['page_num'] = $info['total_num'];// 总条数
                    $info['title'] = $info['theme'];// 标题
                    if ($info['belong_type'] == 'synthesis') {
                        $info['genderName'] = ' 综合';
                    } else {
                        $genderId = GetCategory::getIdsFrom(1, "sOriginalName,{$info['belong_type']}");
                        if (is_array($genderId)) {
                            $genderId = $genderId[0];
                        }
                        $info['genderName'] = GetCategory::getOtherFromIds($genderId, ['sName']);
                    }
                    break;
                case 'product_design_refrence':
                    if ($info['typ'] == 'synthesis') {
                        $info['genderName'] = ' 综合';
                    } else {
                        $genderId = GetCategory::getIdsFrom(1, "sOriginalName,{$info['typ']}");
                        if (is_array($genderId)) {
                            $genderId = $genderId[0];
                        }
                        $info['genderName'] = GetCategory::getOtherFromIds($genderId, ['sName']);
                    }
                    break;

                case 'mostrend_content':
                case 'picture':
                    if (!empty($info['channel']) && in_array($info['channel'], array('man', 'men', 'woman', 'women'))) {
                        $gen = $info['channel'];
                    } else {
                        $gen = $info['type'];
                    }
                    $genderId = GetCategory::getIdsFrom(1, "sOriginalName,$gen");
                    if (is_array($genderId)) {
                        $genderId = $genderId[0];
                    }
                    $info['genderName'] = GetCategory::getOtherFromIds($genderId, ['sName']);

                    break;
                default:
                    if (isset($info['typ'])) {
                        $genderId = GetCategory::getIdsFrom(1, "sOriginalName,{$info['typ']}");
                        if (is_array($genderId)) {
                            $genderId = $genderId[0];
                        }
                        $info['genderName'] = GetCategory::getOtherFromIds($genderId, ['sName']);
                    }
                    break;
            }
            // 单品
            if (isset($info['iCategory']) && !empty($info['iCategory'])) {
                $lists[$key]['link']['category'] = $this->common_model->getLink($columnId, $params, 'cat', $info['iCategory'], true, 'anchor');
                $info['categoryName'] = GetCategory::getOtherFromIds($info['iCategory'], ['sName']);
            }
            // 品名
            if (isset($info['iSubcategory']) && !empty($info['iSubcategory'])) {
                $lists[$key]['link']['subCategory'] = $this->common_model->getLink($columnId, $params, 'cat', $info['iSubcategory'], true, 'anchor');
                $info['subCategoryName'] = GetCategory::getOtherFromIds($info['iSubcategory'], ['sName']);
            }
            // 品牌名称和链接和地区id
            if (isset($info['brand_tid'])) {
                $brandInfo = OpPopFashionMerger:: getBrandData($info['brand_tid']);

                $regionId = $brandInfo['sRegion'];
                $lists[$key]['link']['region'] = $this->common_model->getLink($columnId, $params, 'reg', $regionId, true, 'anchor');
                $lists[$key]['link']['brand'] = $this->common_model->getLink($columnId, $params, 'bra', $info['brand_tid'], true, 'anchor');
                $info['regionName'] = GetCategory::getFieldFromId($regionId);
                $info['brandName'] = $brandInfo['en_cn_brand'];
            }
            // 季节和发布时间和标题和手稿合辑每个id的总条数
            switch ($tableName) {
                case 'product_tideleader':
                    $seasonId = $info['iFordate'];
                    break;
                case 'mostrend_content':
                    $seasonId = GetCategory::getIdsFrom(5, "sOriginalName,{$info['popular_time']}");
                    $info['create_time'] = $info['release_time'];
                    $info['page_num'] = $info['count'];
                    break;
                case 'picture':
                    $info['create_time'] = $info['time'];
                    break;
                default:
                    if (isset($info['for_date_text'])) {
                        $seasonId = GetCategory::getIdsFrom(5, "sOriginalName,{$info['for_date_text']}");
                    } elseif (isset($info['for_date'])) {
                        $seasonId = GetCategory::getIdsFrom(5, "sOriginalName,{$info['for_date']}");
                    }
                    break;
            }
            // 季节名称和链接
            if (is_array($seasonId)) {
                $seasonId = $seasonId[0];
            }
            $lists[$key]['link']['season'] = $this->common_model->getLink($columnId, $params, 'sea', $seasonId, true, 'anchor');
            $info['seasonName'] = GetCategory::getOtherFromIds($seasonId, ['sName']);
            // 手稿合辑前台显示标题
            if (isset($info['name_text'])) {
                $info['title'] = $info['name_text'];
            }
            $info['title'] = stripcslashes($info['title']);

            //书名
            if (isset($info['nme'])) {
                $info['bookname'] = $info['nme'];
            } elseif (isset($info['name_code'])) {
                $info['bookname'] = $info['name_code'];
            } else {
                $info['bookname'] = $info['sBookName'];
            }
            $info['pop_id'] = $val['pop_id'];


            // 图片路径
            $imgPath = getImagePath($tableName, $info);

            $info['cover'] = getFixedImgPath($imgPath['cover']);
            $info['cover'] = $this->details_model->getImgfPath($info['cover'], true);// 替换图片

            $lists[$key]['list'] = $info;
            // 如果栏目是父级栏目则获取栏目名称(趋势栏目，子栏目也需要取栏目名称)
            if (count($val['iColumnId']) > 1 && $columnId == $val['iColumnId'][0]) {
                // 栏目中文名
                $lists[$key]['columnName'] = GetCategory::getOtherFromColId($val['iColumnId'][1], 'sName');

                $_params = $this->common_model->getAssociates($columnId, 'all');
                $_paramsArr = array_intersect_key($paramsArr, $_params);
                $sParams = $this->common_model->parseParams($_paramsArr, 2);
                $lists[$key]['columnLink'] = $this->common_model->getLink($val['iColumnId'][1], $sParams);
            }

            $lists[$key]['columnId'] = $val['iColumnId'][1];
            // 是否有视频--图标
            $lists[$key]['video_have'] = false;
            if ($lists[$key]['columnId'] == 71) {
                $relation_pop_id = 'brochures_' . $val['pri_id'];
                $lists[$key]['video_have'] = !empty($videoExistDict) && $videoExistDict[$relation_pop_id] ? true : false;
            }

            $lists[$key]['tableName'] = getProductTableName($tableName);
            $lists[$key]['offset'] = $offset++;
            $lists[$key]['total'] = $totalCount;
            // 判断图片是否有遮罩
            $lists[$key]['shade'] = $powers['shade'] ? $powers['shade'] : '';
            $lists[$key]['iCollectStatus'] = array_search($val['pop_id'], $collectStatusList) === false ? 0 : 1; // 是否收藏
            // $lists[$key]['videoStatus'] = !empty($val['aVersion']) && in_array('video', $val['aVersion']) ? 1 : 0; // 是否有视频
        }
        $this->benchmark->mark('getBookListsEnd');
        return $totalCount;
    }

    /**
     * ajax列表数据
     * @param string $params 接收的参数
     * @param int $columnId 栏目ID
     * @param mixed $powers 权限
     * @param bool $isAjax 是否是ajax请求
     * @return array array(数据列表$lists, 其他信息$info)
     */
    public function ajaxList($params, $columnId, $powers, $isAjax = true)
    {
        $this->benchmark->mark('ajaxGetList');

        $paramsArr = $this->common_model->parseParams($params, 1);
        $page = $this->common_model->getPage($paramsArr); // 当前页
        $page = max($page, 1);
        $pageSize = getRequestPageSize(21);
        $offset = ($page - 1) * $pageSize;

        $lists = [];
        $totalCount = $this->getBookLists($params, $columnId, $lists, $offset, $pageSize, $powers, $type = 'books');

        $info = [
            'totalCount' => $totalCount,
            'page' => $page,
            'pageSize' => $pageSize,
            'powers' => $powers
        ];

        $this->benchmark->mark('ajaxGetListEnd');

        if ($isAjax) {
            $jsonOutPut = $this->getJsonOutputObj();
            $jsonOutPut->code(0)->data($lists)->info($info)->msg('ok')->out();
        }

        return [$lists, $info];
    }

    /**
     * [getConditions 获取solr查询的condition条件]
     * @param string    or array    $params     [url参数]
     * @param integer $columnId [栏目id]
     * @return [array]  $conditions
     */
    public function getConditions($params = '', $columnId, $powers = [])
    {
        if (!empty($params)) {
            if (is_array($params)) {
                $paramsArr = $params;
            } else {
                $paramsArr = $this->common_model->parseParams($params);
            }
        } else {
            $paramsArr = [];
        }

        // 特殊处理,将手稿合辑里面的lookbook移除，并加入到款式库侧边广告通
        if ($columnId == 6) {
            $conditions = ['iColumnId' => [70, 72, 113, 114, 115]];
        } else {
            $conditions = ['iColumnId' => $columnId];
        }
        // 广告大片有视频
        if ($columnId == 71 && $paramsArr['ver'] == 'video') {
            $conditions['aVersion'] = $paramsArr['ver'];
        }
        // 性别
        $gender = $this->common_model->getGenderByRequest($params);
        $this->common_model->childGender($gender, $conditions);
        // 行业
        $industry = $this->common_model->getIndustryByRequest($params);
        if ($industry) {
            $conditions['other'][] = 'aLabelIds:' . $industry;
        }
        // 只看收藏
        if (isset($paramsArr['coll']) && $paramsArr['coll']) {
            $conditions['aCollectAccount'] = getUserId();
        }
        // 趋势手稿栏目 添加 数据来源（趋势/手稿）查询条件
        if (isset($paramsArr['btype']) && $paramsArr['btype'] && $columnId == 70) {
            $conditions['iDataSource'] = $paramsArr['btype'];
        }

        //含适量文件
        if (isset($paramsArr['vect']) && $paramsArr['vect'] == 1) {
            $conditions['iRelate'] = 1;
        }

        // 时间范围,0=>不限，1=>近7日,2=>近30日,3=>近半年
        if (isset($paramsArr['tim']) && !$paramsArr['tim']) {
            $conditions['dCreateTime'] = $this->common_model->getTimeRange($paramsArr['tim']);
        }
        //地区
        if (isset($paramsArr['reg']) && $region = (int)$paramsArr['reg']) {
            $conditions['other'][] = "(iRegion:{$region} OR iArea:{$region} OR iContinent:{$region} OR iCountry:{$region})";
        }
        //书名
        if (isset($paramsArr['boo']) && !empty($paramsArr['boo']) && !in_array($columnId, array(71, 131))) {
            $conditions['sBookName'] = $this->common_model->strReplace($paramsArr['boo']);
        }
        //品牌
        if (isset($paramsArr['bra']) && !empty($paramsArr['bra']) && in_array($columnId, array(71, 131))) {
            $conditions['iBrand'] = (int)$paramsArr['bra'];
        }
        //季节
        if (isset($paramsArr['sea'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['sea'];
        }
        //品类
        if (isset($paramsArr['cat'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['cat'];
        }
        //书籍类型
        if (isset($paramsArr['cont'])) {
            $conditions['other'][] = 'aLabelIds:' . $paramsArr['cont'];
        }
        //lookbook类型
        if (isset($paramsArr['typ'])) {
            $conditions['sClass'] = $paramsArr['typ'];
        }
        // 关键字检索
        $keyword = $this->common_model->getKeyword($params, $powers);
        if (strpos($keyword, '?key=') === 0) {
            $keyword = ltrim($keyword, '?key=');
        }
        if ($keyword) {
            $conditions['other'][] = 'combine:(' . $keyword . ')';
        }

        return $conditions;
    }

    /**
     * [getSelectItems 获取指定栏目有数据的所有查询条件]
     * @param array $selectItem [筛选条件]
     * @param integer $columnId [栏目id]
     * @param string $params [url参数]
     * @return array   $selectItems  [筛选条件下的solr里有数据的所有子条件]
     */
    public function getSelectItems($selectItem, $columnId, $params = '', $powers = [], $share = false)
    {
        $this->benchmark->mark('groupbySelect');
        $this->load->model('category_model');
        $conditions = $this->getConditions($params, $columnId, $powers);
        $conditionConDirs = $this->getConditions('', $columnId, $powers);//内容方向

        if (in_array($columnId, [71, 72, 115, 131])) {
            $conditions['iDisplay'] = 1;
            $conditionConDirs['iDisplay'] = 1;
        }

        $keys = $this->common_model->getKeyword('', $powers);
        //=============================
        $selectItems = [];
        $paramToSolrArr = $this->common_model->getKeyValMap();
        $paramTmp = $this->common_model->parseParams($params);
        if (!empty($paramTmp)) {
            foreach ($paramTmp as $key => $val) {
                if (!empty($val)) {
                    $ItemTmp = $paramToSolrArr["$key"];
                    $TmpKey = array_search($ItemTmp, $selectItem);
                    unset($selectItem["{$TmpKey}"]);
                    $selectItems["{$ItemTmp}"] = [];
                }
            }
        }
        //=============================
        $sContentDirection = $this->category_model->getRealCategory(['sContentDirection'], $columnId, $conditionConDirs);
        $items = $this->category_model->getRealCategory($selectItem, $columnId, $conditions, $keys);
        $items['sContentDirection'] = $sContentDirection['sContentDirection'];
        foreach ($items as $key => $val) {
            if (empty($val)) {
                $selectItems[$key] = [];
                continue;
            }
            switch ($key) {
                //季节
                case 'iSeason':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'sea', true, 'anchor');
                    break;
                //类型
                case 'sClass':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'typ', true, 'anchor');
                    break;
                //单品（没有品名的栏目）
                case 'sCategory':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'cat', true, 'anchor');
                    break;
                //区域(地区)
                case 'iRedion':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'reg', true, 'anchor');
                    break;
                // 书籍类型
                case 'sContentDirection':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'cont', true, 'anchor');
                    break;
                // 书籍类型(趋势/手稿)
                case 'iTrendOrManual':
                    $selectItems[$key] = $this->getItems($columnId, $params, $val, 'btype', true, 'anchor');
                    break;
                default:
                    # code...
                    break;
            }
        }

        $this->benchmark->mark('groupbySelectEnd');
        return $selectItems;
    }

    /**
     * 获取二级列表页数据
     * @param $table
     * @param string $bookId 条件
     * @param int $totalCount 查询总数
     * @param int $page 当前页码
     * @param int $pageSize 每页显示条数
     *
     * @param bool $refresh 刷新缓存
     * @return mixed 结果集--二维数组
     * @internal param string $tableName 表名
     */
    public function getSecondLevelList($table, $bookId, &$totalCount, $page = 1, $pageSize = 10, $refresh = false)
    {
        $this->benchmark->mark('getSecondLevelList');
        $condition = array();
        $totalCondition = array('id' => $bookId);
        //真实表名
        switch ($table) {
            //趋势手稿&单品合集
            case 'designrefrence':
                $database = 'fashion';
                $tableName = 'product_design_refrence_details';
                $resultList['collectName'] = 'product_design_refrence';
                $condition['pid'] = $bookId;
                $rs = $this->db()->select('page_num,sBookNumber,sBuyingLinks')
                    ->from($database . '.' . $resultList['collectName'])
                    ->where($totalCondition)
                    ->get()
                    ->result_array();
                // $this->db()->close();
                $totalCount = $rs[0]['page_num'];
                $sBookNumber = $rs[0]['sBookNumber'];
                $sBuyingLinks = $rs[0]['sBuyingLinks'];
                $order = 'small_pic';

                break;

            //m书店
            case 'moscon':
                $database = 'trends_new';
                $tableName = 'mostrend_content';
                $condition['id'] = $bookId;
                $BookInfo = $this->getBookInfo($database . '.' . $tableName, $condition, $refresh);
                $BookInfo['collectName'] = 'mostrend_content';
                $totalCount = $BookInfo['count'];
                return $BookInfo;
                break;

            //lookbook
            case 'brochures':
                $database = 'fashion';
                $tableName = 'product_brochures_photo';
                $resultList['collectName'] = 'product_brochures';
                $condition['borochid'] = $bookId;
                $rs = $this->db()->select('page_num,sBookNumber,sBuyingLinks')
                    ->from($database . '.' . $resultList['collectName'])
                    ->where($totalCondition)
                    ->get()
                    ->result_array();
                // $this->db()->close();
                $totalCount = $rs[0]['page_num'];
                $sBookNumber = $rs[0]['sBookNumber'];
                $sBuyingLinks = $rs[0]['sBuyingLinks'];
                $order = 'pic';
                break;

            //矢量书稿
            case 'refrencegroup':
                $database = 'fashion';
                $tableName = 'product_vector_refrence';
                $resultList['collectName'] = 'product_vector_refrence_group';
                $condition['groupid'] = $bookId;
                $rs = $this->db()->select('total_num,sBookNumber,sBuyingLinks')
                    ->from($database . '.' . $resultList['collectName'])
                    ->where($totalCondition)
                    ->get()
                    ->result_array();
                // $this->db()->close();
                $totalCount = $rs[0]['total_num'];
                $sBookNumber = $rs[0]['sBookNumber'];
                $sBuyingLinks = $rs[0]['sBuyingLinks'];
                $order = 'imgname';
                break;
        }
        $baseTable = $database . '.' . $tableName;
        $this->load->driver('cache');
        $key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_' . 'secondLevel_book' . md5($baseTable . implode('', $condition) . $page . $pageSize);
        $memResult = $this->cache->memcached->get($key);
        $offSet = ($page - 1) * $pageSize;
        if (empty($memResult) || $refresh) {
            $result = $this->db()->select()
                ->from($baseTable)
                ->where($condition)
                ->limit($pageSize, $offSet)
                ->order_by($order, 'ASC')
                ->get()
                ->result_array();
            // $this->db()->close();

            $resultList['sBookNumber'] = $sBookNumber;
            $resultList['sBuyingLinks'] = $sBuyingLinks;
            $resultList[$table] = $result;
            $this->cache->memcached->save($key, $resultList, 1800);
            $memResult = $resultList;
        }

        $this->benchmark->mark('getSecondLevelListEnd');
        return $memResult;
    }

    public function getBookInfo($tableName, $condition, $refresh = false)
    {
        $this->load->driver('cache');
        $key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_' . 'bookInfo' . md5($tableName . implode('', $condition));
        $memResult = $this->cache->memcached->get($key);

        if (empty($memResult) || $refresh) {
            $memResult = $this->db()->select()
                ->from($tableName)
                ->where($condition)
                ->limit(1)
                ->get()
                ->row_array();
            // $this->db()->close();

            $this->cache->memcached->save($key, $memResult, 1800);
        }

        return $memResult;
    }

    public function getVectorLinks($table, $bookId)
    {
        //  /books/seclist/id_{$val.list.id}-t_{$val.tableName}-yl_{$val.list.iPreviewMode}-col_{$val.columnId}/
        $linksDate = $iVectorIds = [];
        switch ($table) {
            //趋势手稿-进入-只取矢量书稿的链接即可
            case 'designrefrence':
                $sql = "SELECT `iVectorId` FROM " . self::T_FASHION_T_VECTOR_RELATED_TO_DESIGN . " WHERE `iDesignId`=? AND `iStatus`=1";
                $iVectorIds = $this->query($sql, $bookId);
                break;
            //矢量书稿-进入-需取趋势手稿和矢量书稿链接
            case 'refrencegroup':
                $sql = "SELECT `iDesignId` FROM " . self::T_FASHION_T_VECTOR_RELATED_TO_DESIGN . " WHERE `iVectorId`=? AND `iStatus`=1";
                $iDesignId = $this->query($sql, $bookId);
                $iDesignId = $iDesignId[0]['iDesignId'];
                if ($iDesignId) {
                    $designData = OpPopFashionMerger::getProductData($iDesignId, 'product_design_refrence');
                    $linksDate['design'] = ['id' => $iDesignId, 't' => 'designrefrence', 'yl' => $designData[$iDesignId]['iPreviewMode'], 'col' => $designData[$iDesignId]['iColumnId']];
                } else {
                    //找不到内容时，跳404
                    show_404();
                    die;
                }

                $sql = "SELECT `iVectorId` FROM " . self::T_FASHION_T_VECTOR_RELATED_TO_DESIGN . " WHERE `iDesignId`=? AND `iStatus`=1";
                $iVectorIds = $this->query($sql, $iDesignId);
                break;
        }
        if ($iVectorIds) {
            foreach ($iVectorIds as $val) {
                $vectorId = $val['iVectorId'];
                $vectorData = OpPopFashionMerger::getProductData($vectorId, 'product_vector_refrence_list');
                $vectorData = $vectorData[$vectorId];
                $linksDate['vector'][] = ['id' => $vectorId, 't' => 'refrencegroup', 'yl' => $vectorData['iPreviewMode'], 'col' => 73];
            }
        }

        return $linksDate;
    }

    //-----------------------------------------------以下为：个人中心-共享资料-服装杂志 mongoDB-------------------------------------------------------

    //杂志分类-详细搜索的菜单栏
    public function getMagazineSearch()
    {
        $this->load->library('POP_MongoDb');
        $omongodb = $this->pop_mongodb->getInstance();

        //品名
        $condition_m = ['site' => '001', 'column' => 'f014', 'group_name' => 'category'];
        $cursor = $omongodb->where($condition_m)->get('label');
        $arylist_type = $cursor->result_array();
        $arrTyp = $arylist_type[0]['label_array'];

        //季节
        $condition_m = ['site' => '001', 'column' => 'f014', 'group_name' => 'fordate'];
        $cursor = $omongodb->where($condition_m)->get('label');
        $arylist_fordate = $cursor->result_array();
        $arrfordate = $arylist_fordate[0]['label_array'];

        //地区
        $condition_m = ['site' => '001', 'column' => 'f014', 'group_name' => 'region'];
        $cursor = $omongodb->where($condition_m)->get('label');
        $arylist_region = $cursor->result_array();
        $arrRegion = $arylist_region[0]['label_array'];

        //书名
        $condition_m = array('show_site' => ['$all' => ["001"]], 'column' => 'f014');
        $cursor = $omongodb->where($condition_m)->get('brand');
        $arylist_bookname = $cursor->result_array();;
        if (!empty($arylist_bookname)) {
            foreach ($arylist_bookname as $v) {
                $arrBookName[$v['show_label']] = $v['show_name'];
            }
        }

        return ['sCategory' => $arrTyp, 'iSeason' => array_reverse($arrfordate, true), 'region' => $arrRegion, 'bookName' => $arrBookName];
    }

    //列表
    public function getMagazineLists($options, $offset, $pageSize, $orderBy, &$totalCount)
    {
        $this->load->library('POP_MongoDb');
        $omongodb = $this->pop_mongodb->getInstance();

        $options[] = '001';
        $options[] = 'f014';
        $order = array();
        if ($orderBy) {
            foreach ($orderBy as $_field => $_sort) {
                $order[$_field] = ($_sort == 'DESC') ? -1 : 1;
            }
        } else {
            $order['order_time'] = -1;
        }

        $condition_m = ['element_search' => ['$all' => $options], 'is_show' => 1];
        $this->load->helper('power');
        $condition_m['create_time'] = ['$gt' => strtotime(yearTimeDiff())];
        $cursor = $omongodb->where($condition_m)->order_by($order)->get('imgcol_set');
        $totalCount = $ary_list_count = $cursor->num_rows();
        $cursor->limit($pageSize)->skip($offset);
        $aryList = $cursor->result_array();

        foreach ($aryList as $key => &$value) {
            if ($value['source'] != "") {
                //source
                $web_site = $value['source'];
            } else {
                $web_site = $value['site'];
            }
            if ($value['is_old']) {
                $value['magazine_cover'] = IMAGE_MAGAZINE_PATH . '/' . $value['update_url'] . '/images/1.jpg';
                //老数据只需要一个di就可查询
                $value['url_id'] = $value['id'];
            } else {
                $value['magazine_cover'] = '/' . $web_site . '/' . substr($value['create_date'], 0, 4) . '/' . $value['create_date'] . '/' . $value['serial_number'] . '/small_cover.jpg';
                //mongodb新数据需要流水号、日期和网站
                $value['url_id'] = $value['site'] . 'I' . $value['create_date'] . 'I' . $value['serial_number'];
            }
            $value['pubtime'] = $pubtime = date("Y-m-d", $value['order_time']);
            $value['attribute']['category'] = substr(implode('/', explode(',', $value['attribute']['category'])), 0, -1);
            $value['magazine_cover'] = parse_url($value['magazine_cover'], PHP_URL_PATH);
        }

        return $aryList;
    }

    public function getMagazineInfo($magazineID, &$arylist, $viewModel = '')
    {
        $this->load->library('POP_MongoDb');
        $omongodb = $this->pop_mongodb->getInstance();

        $arr = explode('I', $magazineID);
        foreach ($arr as $k => $v) {
            $options[] = $v;
        }
        $icount = count($arr);
        // 根据传来的id参数判断新老数据
        if ($icount == 1) {
            // 老数据
            if ($_REQUEST['simple']) {
                $option_c[] = 'simple';
                $condition_v = ['id' => $arr[0]];
                $condition_m = ['id' => $arr[0], 'version' => ['$all' => $option_c], 'is_show' => 1];
            } else {
                $option_c[] = 'detail';
                $condition_v = ['id' => $arr[0]];
                $condition_m = ['id' => $arr[0], 'version' => ['$all' => $option_c], 'is_show' => 1];
            }
        } else {
            $condition_v = ['site' => '001', 'serial_number' => $arr[2], 'column' => 'f014'];
            $condition_m = ['site' => $arr[0], 'create_date' => $arr[1], 'serial_number' => $arr[2], 'is_show' => 1];
        }

        $arylist = $omongodb->where($condition_v)->get('imgcol_set')->result_array();
        //更新操作
        $condition_u = ['site' => "{$options[0]}", 'create_date' => "{$options[1]}", 'serial_number' => "{$options[2]}"];
        $omongodb->where($condition_u)->inc('view_count')->update('imgcol_set');
        if ($viewModel == "isSimple") {
            if ($icount == 1) {
                // 判断新老版本依据
                $option_c = array();
                $option_c[] = 'simple';
                $option_v[] = 'f014';
                $condition_m = array('id' => $arr[0], 'site' => '001', 'version' => array('$all' => $option_c), 'element_search' => array('$all' => $option_v), 'is_show' => 1);
            } else {
                $option_c[] = 'simple';
                $condition_m = array('site' => $arr[0], 'create_date' => $arr[1], 'serial_number' => $arr[2], 'version' => array('$all' => $option_c), 'is_show' => 1);
            }
        }
        $order['list_img_name'] = 1;
        $arylist_pic = $omongodb->where($condition_m)->order_by($order)->get('imgcol_single')->result_array();
        return $arylist_pic;
    }

    public function getMagazineFilePath($magazine_id, $viewModel = '')
    {
        $this->load->library('POP_MongoDb');
        $omongodb = $this->pop_mongodb->getInstance();

        $arr = explode('I', $magazine_id);
        foreach ($arr as $k => $v) {
            $options[] = $v;
        }
        $icount = count($arr);
        if (count($arr) == 1) {
            $condition_m = array('site' => '001', 'id' => $arr[0]);
        } else {
            $condition_m = array('site' => '999', 'serial_number' => $arr[2]);
        }
        $arylist = $omongodb->where($condition_m)->get('img_download')->result_array();

        if ($viewModel == "isSimple") {
            if ($icount == 1) {
                $file = '/download/' . 'old/fashion/magazine/graphic/' . $arylist[0]['update_url'] . '/simple/' . $arylist[0]['package_name_simple_001'];
            } else {
                $str = str_replace('/data/download/', '', $arylist[0]['update_url']);
                $str = str_replace('/data1/download/', '', $str);
                $file = '/download/' . $str . $arylist[0]['package_name_simple_001'];
            }
        } else {
            if ($icount == 1) {
                $file = '/download/' . 'old/fashion/magazine/graphic/' . $arylist[0]['update_url'] . '/detail/' . $arylist[0]['package_name'];
            } else {
                $str = str_replace('/data/download/', '', $arylist[0]['update_url']);
                $str = str_replace('/data1/download/', '', $str);
                $file = '/download/' . $str . $arylist[0]['package_name'];
            }
        }
        return $file;
    }

    public function getMagazineDetails($params, &$arylist_pic)
    {
        $this->load->library('POP_MongoDb');
        $omongodb = $this->pop_mongodb->getInstance();

        $magazineid = $params['id'];
        $arr = explode('I', $magazineid);
        foreach ($arr as $k => $v) {
            $options[] = $v;
        }
        $vcount = count($arr);
        if ($vcount == 2) {
            $img_name = str_replace('qq', '.', $arr[1]);
            if (isset($params['ver']) && trim($params['ver']) == 'isSimple') {
                $option_c[] = 'simple';
                $option_v[] = 'f014';
                $condition_m = ['id' => $arr[0], 'list_img_name' => $img_name, 'version' => ['$all' => $option_c]];
                $condition_m['element_search']['$all'] = $option_v;
                $condition_c = ['id' => $arr[0], 'version' => ['$all' => $option_c]];
                $condition_c['element_search']['$all'] = $option_v;
            } else {
                $option_c[] = 'detail';
                $condition_m = ['id' => $arr[0], 'list_img_name' => $img_name, 'version' => ['$all' => $option_c]];
                $condition_c = ['id' => $arr[0], 'version' => ['$all' => $option_c]];
            }
        } else {
            $img_name = str_replace(['qq', '-'], ['.', '_'], $arr[3]);
            $condition_m = ['site' => $arr[0], 'create_date' => $arr[1], 'serial_number' => $arr[2], 'list_img_name' => $img_name];
            $condition_c = ['site' => $arr[0], 'create_date' => $arr[1], 'serial_number' => $arr[2]];
        }

        $arylist_pic = $omongodb->where($condition_m)->get('imgcol_single')->result_array();
        $arylist_all = $omongodb->where($condition_c)->get('imgcol_single')->result_array();

        return $arylist_all;
    }


    /**
     * [getSeoArray   获取seo搜索引擎，标题、关键字、描述]
     * @param integer $columnId [栏目id]
     * @return string   [中文字符]
     */
    public function getSeoArray($columnId, $params = '')
    {
        //性别
        $gender = $this->common_model->getGenderByRequest($params);
        $gen = !empty($gender) ? trim(GetCategory::getOtherFromIds($gender, ['sName'])) : '';
        //行业
        $industry = $this->common_model->getIndustryByRequest($params);
        $ind = !empty($industry) ? trim(GetCategory::getOtherFromIds($industry, ['sName'])) : '';
        if ($ind == '运动') {
            $ind = '运动装';
        }
        $paramsArr = [];
        if (!empty($params)) {
            $paramsArr = $this->common_model->parseParams($params, 1);
        }
        //单品，品名
        if (isset($paramsArr['cat'])) {
            $arr = GetCategory::getOtherFromIds($paramsArr['cat'], ['sName', 'iAttributePid'], 'array');
            $subcat = $arr['sName'][0];
            if ($arr['iAttributePid'][0] == '11115') {
                $catId = GetCategory::getCatIdsBySubcatIds($paramsArr['cat']);
                $cat = GetCategory::getOtherFromIds($catId, ['sName']);
            } else {
                $cat = $subcat;
                $subcat = '';
            }
        } else {
            $cat = $subcat = '';
        }
        //地区
        $reg = $this->common_model->getDefaultParams('reg', $params, $columnId);
        //季节
        $sea = $this->common_model->getDefaultParams('sea', $params, $columnId);
        //品牌
        $bra = $this->common_model->getDefaultParams('bra', $params, $columnId);
        //观点
        $vie = $this->common_model->getDefaultParams('vie', $params, $columnId);
        //书名
        $boo = $this->common_model->getDefaultParams('boo', $params, $columnId);
        //类型
        $typ = $this->common_model->getDefaultParams('typ', $params, $columnId);
        $timArray = array('全部时段', '近7日', '近30日', '近半年');
        $sorArray = array(1 => "按时间最新", 2 => "按浏览最高", 3 => "按收藏最多");
        //时段
        $jtime = $timArray[$paramsArr['tim']];
        //时间
        $sor = $sorArray[$paramsArr['sor']];
        //$gen,$ind,$sea,$bra,$cat,$subcat,$reg,$typ,$boo,$jtime,$sor
        //栏目名称
        $colName = GetCategory::getOtherFromColId($columnId, 'sName');
        //替换分页字段为空
        $preg = '/page_\d*/';
        $paramsArray = explode('-', $params);
        if (count($paramsArray) == 1) {
            $paramsArray[0] = preg_replace($preg, "", $paramsArray[0]);
        }
        $params = implode('-', $paramsArray);
        if (empty($params) && empty($gen) && empty($ind)) {
            if ($columnId == 4) {
                $title = '服装杂志_手稿合辑-POP服装趋势网';
                $description = 'POP服装趋势网读物栏目汇集服装相关的杂志、期刊、画册等资讯内容，为您提供最新、最具代表性的封面、手册、手稿等细节图片和素材，为您提供有价值的资讯服务。';
            } else {
                $title = $colName . '_读物-POP服装趋势网';
                $description = 'POP服装趋势网读物的' . $colName . '栏目汇集服装相关的杂志、期刊、画册等资讯内容，为您提供最新、最具代表性的封面、手册、手稿等细节图片和素材，' . $colName . '从款式、廓形、图案、面料等方面，为您提供有价值的资讯服务。';
            }
            $keyInfo = [
                '' => '趋势手稿,单品合辑,快反应系列,广告大片',
                70 => '趋势手稿,趋势杂志,趋势书籍',
                72 => '单品合辑,杂志合辑,单品书箱',
                115 => '快反应系列,单品系列,APM系列',
                71 => '服装杂志,服装杂志封面,杂志画册',
                131 => '服装杂志,服装杂志封面,杂志画册',
            ];
            $keywords = $keyInfo[$columnId];
        } else {
            $jh = array_filter([$gen, $ind, $sea, $bra, $cat, $subcat, $reg, $typ, $boo, $jtime, $sor]);
            $t = implode('_', $jh) . '_';
            $k = implode(',', $jh) . ',';
            $d = implode('/', $jh);
            if ($columnId == 4) {
                $title = $t . '读物-POP服装趋势网';
                $description = 'POP服装趋势网读物栏目汇集' . $d . '相关的杂志、期刊、画册、矢量手稿等资讯内容和细节图片及素材，为您提供有价值的' . $d . '资讯服务。';
            } else {
                $title = $t . $colName . '-POP服装趋势网';
                $description = 'POP服装趋势网' . $colName . '栏目汇集' . $d . '相关的杂志、期刊、画册、矢量手稿等资讯内容和细节图片及素材，为您提供有价值的' . $d . '资讯服务。';
            }
            $keywords = $k . $colName;
        }
        $seoArray[$columnId] = ['title' => $title, 'keywords' => $keywords, 'description' => $description];
        return $seoArray[$columnId];
    }

    /**
     * 获取栏目介绍的内容
     * @param $columnId 栏目ID
     * @return string 栏目介绍
     */
    public function getColumnsPresentation($columnId)
    {
        switch ($columnId) {
            case '71':
            case '131':
                $columnPresentation = "品牌产品系列展示，全球一线品牌到市场热门品牌到新锐设计师品牌，时尚大片品牌灵魂与当季搭配展示。";
                break;
            case '6':
            case '70':
            case '72':
            case '73':
            case '101':
            default:
                $columnPresentation = "从趋势书籍到单品书籍，到快反系列，POP独家的书籍体系POP服装趋势首创资讯趋势O2O";
                break;
        }
        return $columnPresentation;
    }

    private function getItems($columnId, $params, $arr, $type)
    {
        $ret = [];
        foreach ($arr as $id => $name) {
            $ret[] = ['id' => $id, 'name' => $name, 'link' => $this->common_model->getLink($columnId, $params, $type, $id, true, 'anchor')];
        }

        // 加入全部
        if ($type == 'cont') {
            $paramsArr = $this->common_model->parseParams($params, 1);
            if (isset($paramsArr['cont'])) unset($paramsArr['cont']);
            $params_str = $this->common_model->parseParams($paramsArr, 2);
            array_unshift($ret, ['id' => 0, 'name' => '全部类型', 'link' => $this->common_model->getLink($columnId, $params_str, '', '', true, 'anchor')]);
        }
        return $ret;
    }

    public function getBookMemo($bookName, $refresh = false)
    {
        $this->load->driver('cache');

        if (empty($bookName)) {
            return '';
        }

        $key = OpPopFashionMerger::POP_FM_TEM_SOLR_MEMCACHE_KEY_PREFIX . '_' . 'brand_library_' . md5($bookName);
        $bookMemo = $this->cache->memcached->get($key);
        if ($bookMemo === false || $refresh) {
            $sql = "SELECT memo FROM pop136.brand_library WHERE `name`=?";
            $res = $this->query($sql, $bookName);
            $bookMemo = $res[0]['memo'];
            $this->cache->memcached->save($key, $bookMemo, 1800);
        }

        return $bookMemo;
    }
}
