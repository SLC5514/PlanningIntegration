<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @todo    手稿合辑-子栏目的处理
 * @author  chenqiuju
 * @time    20160301
 */
class Books extends POP_Controller
{
    private $columnPid = 6;
    private $pageSize = 42;
    private $iAccountType;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'books_model']);
        // 判断详情页权限，（是否可以加入工作台和下载）
        $memberPower = memberPower('detail');
        if (isset($memberPower['P_Collect']) && $memberPower['P_Collect'] == false) {
            $this->assign('notJoinW', 1);
        }
        if (isset($memberPower['P_SingleDownLoad']) && $memberPower['P_SingleDownLoad'] == false) {
            $this->assign('notDomn', 1);
        }
        // 子账号还是主账号
        $aLogonMessage = get_cookie_value();
        $this->iAccountType = $aLogonMessage['iAccountType'];
        $books = array_merge(array('0-9'), range('A', 'Z'), array('OTHER'));
        $this->assign('books', $books);
        $this->assign('columnPid', $this->columnPid);

        // 获取js时间戳
        $regionPath = FCPATH . 'global/js/fashion/regionFromSolr.js';
        $regionStaticTime = getFileModifyTime($regionPath, 'region');    //地区时间戳

        $BookNameIdisplayPath = FCPATH . 'global/js/fashion/BookName_iDisplay.js';
        $bookNameIdisplayStaticTime = getFileModifyTime($BookNameIdisplayPath, 'bookName');    //书名时间戳

        $BookNamePath = FCPATH . 'global/js/fashion/BookName.js';
        $bookNameStaticTime = getFileModifyTime($BookNamePath, 'bookName');    //书名时间戳

        $brandPath = FCPATH . 'global/js/fashion/Column4_Brand.js';
        $brandStaticTime = getFileModifyTime($brandPath, 'brand');   //品牌时间戳

        $MagazineNamePath = FCPATH . 'global/js/fashion/mongoBookName.js';
        $magazineNameStaticTime = getFileModifyTime($MagazineNamePath, 'bookName');    //杂志书名时间戳

        // vip用户即将过期提示条
        $vipTips = $this->isVipExpireTips();
        $this->assign('vipTips', $vipTips);
        $this->assign('regionStaticTime', $regionStaticTime);
        $this->assign('bookNameIdisplayStaticTime', $bookNameIdisplayStaticTime);
        $this->assign('bookNameStaticTime', $bookNameStaticTime);
        $this->assign('brandStaticTime', $brandStaticTime);
        $this->assign('magazineNameStaticTime', $magazineNameStaticTime);
    }

    // 手稿合辑
    public function index($params = '')
    {
        $this->collections($params);
    }

    private function common($columnId, $params)
    {
        $params = $this->common_model->restoreParams($params);
        // 判断用户身份
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $userType = $powers['userType'];

        // P_UserType ：1-主账号vip 2-子账号vip 3-试用用户 4-普通 5-游客
        if ($columnId === 70 && !in_array($powers['P_UserType'],array(1,2,3))) {
            header("Location:/books/");
            die();
        }

        $this->assign('userType', $userType);
        $rootUrl = $this->common_model->columnRootPath($columnId);
        $this->assign('rootUrl', $rootUrl);
        $search = $powers['P_Search'] ? $this->common_model->getSearchSufix() : '';
        $link = ['url' => $rootUrl, 'param' => $params, 'sortRandom' => $powers['sort'], 'search' => $search];
        $this->assign('link', $link);

        // 获取列表和分页 通过ajax获取列表
        if ($this->input->is_ajax_request()) {
            $this->books_model->ajaxList($params, $columnId, $powers);
            return;
        }

        $paramsArr = $this->common_model->parseParams($params, 1);
        $this->assign('paramsArr', $paramsArr);
        // 子栏目
        $columns = $this->common_model->getColumns($this->columnPid, $params, $powers);
        $this->assign('columns', $columns);
        // 清除全部
        $clearAll = $this->common_model->getClearLink($columnId, $params);
        $this->assign('clearAll', $clearAll);
        // 热门推荐关键字
        $keys = $this->common_model->getHotKeyWords($columnId, $params);
        $this->assign('keys', $keys);
        // 获取您已选择内容
        $tips = $this->common_model->getTips($columnId, $params);
        $this->assign('tips', $tips);
        // 获取时间范围
        $time_text = $this->common_model->getTimeText($params);
        $this->assign('time_text', $time_text);
        // 栏目介绍
        $presentation = $this->books_model->getColumnsPresentation($columnId);
        $this->assign('presentation', $presentation);
        // seo
        $this->getTDK($columnId, $params);
        // 底部广告
        $bottomAds = $this->common_model->getAdsBottom($columnId, $this->columnPid);
        $this->assign('bottomAds', $bottomAds);

        $this->assign('columnId', $columnId);

        $this->display('lists/books_list.html');
    }

    // 手稿合辑-书店
    public function store($params = '')
    {
        $this->common(70, $params);
    }

    // 手稿合辑-lookbook
    public function lookbook($params = '')
    {
        $this->common(71, $params);
    }

    // 手稿合辑-单品合集
    public function collections($params = '')
    {
        $this->common(72, $params);
    }

    // 手稿合辑-矢量书稿 栏目移除（方法可删除）
    public function vector($params = '')
    {
        header("Location:/error/");
        die();
    }

    // 手稿合辑-T台系列
    public function runway($params = '')
    {
        $this->common(113, $params);
    }

    // 手稿合辑-品牌系列
    public function brands($params = '')
    {
        $this->common(114, $params);
    }

    // 手稿合辑-快反应系列
    public function fast($params = '')
    {
        $this->common(115, $params);
    }

    // 手稿合辑-订货会合辑
    public function orderMeeting($params = '')
    {
        $this->common(131, $params);
    }

    // 流行画册
    public function magazine($params = '')
    {
        $columnId = 130;
        $options = $aSort = [];

        // 注意参数$params需要做安全处理
        $params = $this->common_model->restoreParams($params);
        // 判断用户身份
        $powers = $this->common_model->getPowers($this->columnPid, '', $columnId);

        $paramsArr = $this->common_model->parseParams($params, 1, FALSE);
        $rootUrl = $this->common_model->columnRootPath($columnId);
        $search = $this->common_model->getSearchSufix('', TRUE);
        $link = ['url' => $rootUrl, 'param' => $params, 'search' => $search];

        // 当前页
        $page = $this->common_model->getPage($paramsArr);
        // 偏移量
        $offset = ($page - 1) * ($this->pageSize);

        // 子栏目
        $columns = $this->common_model->getColumns($this->columnPid, $params, $powers);

        // 单品/风格系列
        if (isset($paramsArr['cat']) && !empty($paramsArr['cat'])) {
            $options[] = $paramsArr['cat'];
        }
        // 季节
        if (isset($paramsArr['sea']) && !empty($paramsArr['sea'])) {
            $options[] = $paramsArr['sea'];
        }
        // 地区
        if (isset($paramsArr['reg']) && !empty($paramsArr['reg'])) {
            $options[] = $this->common_model->strReplace($paramsArr['reg']);
        }
        // 书名
        if (isset($paramsArr['boo']) && !empty($paramsArr['boo'])) {
            $options[] = $this->common_model->strReplace($paramsArr['boo']);
        }
        //关键字
        if (!empty($search)) {
            $options[] = new MongoRegex('/' . $search . '/i');
        }


        // 更新时间OR浏览量
        if (isset($paramsArr['sor']) && !empty($paramsArr['sor'])) {
            if ((int)$paramsArr['sor'] == 1) {//浏览量
                $aSort = array('view_count' => 'DESC');
            }
            if ((int)$paramsArr['sor'] == 2) { //最新资料
                $aSort = array('order_time' => 'DESC');
            }
        }

        $totalCount = 0;
        $lists = $this->books_model->getMagazineLists($options, $offset, $this->pageSize, $aSort, $totalCount);

        // 查询条件
        $selectItems = $this->books_model->getMagazineSearch();
        // 单品
        $categorys = $selectItems['sCategory'];
        // 季节
        $seasons = $selectItems['iSeason'];
        // 地区
        $regions = $selectItems['region'];

        array_walk(
            $regions,
            function (&$item) {
                $item['ename'] = $this->common_model->strReplace($item['ename'], true);
            }
        );
        // 书名
        $bookName = $selectItems['bookName'];

        // 热门推荐关键字
        $keys = $this->common_model->getHotKeyWords($columnId, $params);

        // 获取您已选择内容
        $__link = $this->common_model->columnRootPath($columnId);
        $tips = [];
        $search = $this->common_model->getSearchSufix('', TRUE);
        $searchKey = '';
        if (!empty($search)) {
            $value = $search;
            $searchKey = $this->common_model->getSearchSufix('', FALSE);
            $name = '关键字';
            // 关键字特殊处理
            $link_ = $this->common_model->parseParams($paramsArr, 2, FALSE) ? $this->common_model->parseParams($paramsArr, 2, FALSE) . '/' : '';
            $_link = $__link . $link_;
            $tips[] = ['name' => $name, 'value' => $value, 'link' => $_link, 'em' => ''];
            $allSingle['key'] = $_link;
        }
        if (isset($paramsArr['reg']) && !empty($paramsArr['reg'])) {
            foreach ($regions as $v) {
                if ($paramsArr['reg'] == $v['ename']) {
                    $value = $v['cname'];
                }
            }
            $name = '地区';
            $tmp = $paramsArr;
            unset($tmp['reg']);
            unset($tmp['page']);
            $link_ = $this->common_model->parseParams($tmp, 2, FALSE) ? $this->common_model->parseParams($tmp, 2, FALSE) . '/' : '';
            $_link = $__link . $link_ . $searchKey;

            $tips[] = ['name' => $name, 'value' => $value, 'link' => $_link, 'em' => ''];
            $allSingle['reg'] = $_link;
        }
        if (isset($paramsArr['sea']) && !empty($paramsArr['sea'])) {
            foreach ($seasons as $v) {
                if ($paramsArr['sea'] == $v['ename']) {
                    $value = $v['cname'];
                }
            }
            $tmp = $paramsArr;
            $name = '季节';
            unset($tmp['sea']);
            unset($tmp['page']);
            $link_ = $this->common_model->parseParams($tmp, 2, FALSE) ? $this->common_model->parseParams($tmp, 2, FALSE) . '/' : '';
            $_link = $__link . $link_ . $searchKey;

            $tips[] = ['name' => $name, 'value' => $value, 'link' => $_link, 'em' => ''];
            $allSingle['sea'] = $_link;
        }
        if (isset($paramsArr['cat']) && !empty($paramsArr['cat'])) {
            foreach ($categorys as $v) {
                if ($paramsArr['cat'] == $v['ename']) {
                    $value = $v['cname'];
                }
            }
            $name = '风格系列';
            $tmp = $paramsArr;
            unset($tmp['cat']);
            unset($tmp['page']);
            $link_ = $this->common_model->parseParams($tmp, 2, FALSE) ? $this->common_model->parseParams($tmp, 2, FALSE) . '/' : '';
            $_link = $__link . $link_ . $searchKey;

            $tips[] = ['name' => $name, 'value' => $value, 'link' => $_link, 'em' => ''];
            $allSingle['cat'] = $_link;
        }
        if (isset($paramsArr['boo']) && !empty($paramsArr['boo'])) {
            $a = $this->common_model->strReplace($paramsArr['boo']);
            $value = $bookName[$a];
            $name = '书名';
            $tmp = $paramsArr;
            unset($tmp['boo']);
            unset($tmp['page']);
            $link_ = $this->common_model->parseParams($tmp, 2, FALSE) ? $this->common_model->parseParams($tmp, 2, FALSE) . '/' : '';
            $_link = $__link . $link_ . $searchKey;

            $tips[] = ['name' => $name, 'value' => $value, 'link' => $_link, 'em' => ''];
            $allSingle['cat'] = $_link;
        }

        // 清除全部
        $clearAll = $__link;

        // 获取时间范围
        $time_text = $this->common_model->getTimeText($params);
        $this->assign('time_text', $time_text);

        // seo搜索引擎，标题、关键字、描述
        $this->getTDK($columnId, $params);

        // 栏目介绍
        $presentation = $this->books_model->getColumnsPresentation($columnId);
        $this->assign('presentation', $presentation);

        // 选择了所有的筛选条件或者数据为空时隐藏整个div
        $display = TRUE;
        if (
            (empty($seasons) && empty($categorys) && isset($paramsArr['boo']) && isset($paramsArr['reg']))
            ||
            empty($lists)
        ) {
            $display = FALSE;
        }
        if (isset($paramsArr['boo']) && !empty($paramsArr['boo'])) {
            $paramsArr['boo'] = $this->common_model->strReplace($paramsArr['boo'], TRUE);
        }

        // 生成页码
        $pageHtml = $this->makePageHtml($paramsArr, $totalCount, $this->pageSize, $page, $rootUrl);
        // 生成简单页码
        $simplePageHtml = $this->makePageHtml($paramsArr, $totalCount, $this->pageSize, $page, $rootUrl, TRUE);

        // 底部广告
        $bottomAds = $this->common_model->getAdsBottom($columnId, $this->columnPid);
        $this->assign('bottomAds', $bottomAds);

        $this->assign('display', $display);
        $this->assign('columnId', $columnId);
        $this->assign('tips', $tips);
        $this->assign('clearAll', $clearAll);
        $this->assign('allSingle', $allSingle);
        $this->assign('columns', $columns);
        $this->assign('paramsArr', $paramsArr);
        $this->assign('categorys', $categorys);
        $this->assign('seasons', $seasons);
        $this->assign('regions', $regions);
        $this->assign('keys', $keys);
        $this->assign('link', $link);
        $this->assign('totalCount', $totalCount);
        $this->assign('lists', $lists);
        $this->assign('pageHtml', $pageHtml);
        $this->assign('simplePageHtml', $simplePageHtml);

        $this->display('lists/share_magazine_list.html');
    }

    //--------------------------------------------二级列表页---------------------------------------------------------

    // 获取二级列表数据
    // 栏目id，记录的id，表名，展示形式
    public function secList($args = '')
    {
        $this->benchmark->mark('action');
        $this->load->model('books_model', 'book');
        $params = $this->common_model->parseParams($args, 1, FALSE);

        $bookId = intval($params['id']);
        $tableName = $params['t'];

        $page = isset($params['page']) && !empty($params['page']) ? intval($params['page']) : 1;
        //子栏目ID
        $columnId = intval($params['col']);
        $totalCount = 0;
        $this->pageSize = 30;
        $refresh = $this->input->get('refresh', TRUE);

        //seo搜索引擎，标题、关键字、描述
        $this->getTDK($columnId, $args);

        //反扒参数
        $this->load->model('Secret_model');
        list($symmetricKey, $timeStamp) = $this->Secret_model->init_data();
        $token = $this->Secret_model->get_token();
        $this->assign('token', $token);//token(uid_popuid_secret)
        $this->assign('sign', $symmetricKey);//AES_KEY
        $this->assign('timeStamp', $timeStamp);//生成AES_KEY的时间戳

        //真实表名
        switch ($tableName) {
            //趋势手稿&单品合集
            case 'designrefrence':
                $result =
                    $this->book->getSecondLevelList($tableName, $bookId, $totalCount, $page, $this->pageSize, $refresh);
                $database = 'fashion';
                $collectName = $result['collectName'];
                $resultList = $result[$tableName];
                // 书籍编号
                $sBookNumber = $result['sBookNumber'];
                $sBuyingLinks = $result['sBuyingLinks'];
                $offset = ($page - 1) * ($this->pageSize);
                foreach ($resultList as $key => $val) {

                    $imgPath = getImagePath('product_design_refrence_details', $val);
                    $resultList[$key]['smallPicPath'] = $imgPath['smallPath'];
                    $resultList[$key]['tableName'] = $tableName;
                    $resultList[$key]['realName'] = 'designrefrencedetails';
                    $resultList[$key]['columnId'] = $columnId;
                    $resultList[$key]['index'] = $offset++;
                }
                $theme = $resultList[0]['name_text'];
                //描述
                $bookDescription = $resultList[0]['detail'];
                $showForm = 'p';

                //趋势手稿和矢量文件的关联连接
                $relateLinks = $this->book->getVectorLinks($tableName, $bookId);
                break;

            //m书店
            case 'moscon':
                $bookInfo = $this->book->getSecondLevelList($tableName, $bookId, $totalCount, 1, 10, $refresh);
                $database = 'trends_new';
                $collectName = $bookInfo['collectName'];
                $pic_name = json_decode($bookInfo['pic_name']);
                is_array($pic_name) && asort($pic_name);
                $totalCount = count($pic_name);
                //分页数据
                $start = ($page - 1) * $this->pageSize;
                $end = 0;
                if ($start > ($totalCount - 1)) {
                    $start = $totalCount - 1;
                } elseif (($start + $this->pageSize) > $totalCount) {
                    $end = $totalCount;
                } else {
                    $end = $start + $this->pageSize;
                }
                $offset = ($page - 1) * ($this->pageSize);
                $imgPath = getImagePath($collectName, $bookInfo);
                $booklist = $imgPath['booklist'];
                for ($i = $start; $i < $end; $i++) {
                    $resultList[$i]['smallPicPath'] = $booklist[$i]['smallPath'];
                    $resultList[$i]['tableName'] = $tableName;
                    $resultList[$i]['realName'] = 'moscon';
                    $resultList[$i]['columnId'] = $columnId;
                    $resultList[$i]['index'] = $offset++;
                }
                $theme = $bookInfo['title'];
                $sBuyingLinks = $bookInfo['sBuyingLinks'];
                //描述
                $bookDescription = $resultList[0]['description'];
                $showForm = 'M';
                break;

            //lookbook
            case 'brochures':
                $result = $this->book->getSecondLevelList($tableName, $bookId, $totalCount, $page, $this->pageSize, $refresh);
                $database = 'fashion';
                $collectName = $result['collectName'];
                $resultList = $result[$tableName];
                // 书籍编号
                $sBookNumber = $result['sBookNumber'];
                $sBuyingLinks = $result['sBuyingLinks'];
                $offset = ($page - 1) * ($this->pageSize);
                foreach ($resultList as $key => $val) {
                    $fms = getImagePath();
                    $resultList[$key]['smallPicPath'] = $fms['smallPath'] . $val['pic'];
                    $resultList[$key]['tableName'] = $tableName;
                    $resultList[$key]['realName'] = 'brochuresphoto';
                    $resultList[$key]['columnId'] = $columnId;
                    $resultList[$key]['index'] = $offset++;
                }
                $theme = $resultList[0]['name_text'];
                //描述
                $bookMemo = $this->book->getBookMemo($resultList[0]['name'], $refresh);
                $bookDescription = trim($resultList[0]['detail']) ? trim($resultList[0]['detail']) : trim($bookMemo);
                $showForm = 'L';
                break;

            //矢量书稿
            case 'refrencegroup':
                $result = $this->book->getSecondLevelList($tableName, $bookId, $totalCount, $page, $this->pageSize, $refresh);
                $database = 'fashion';
                $collectName = $result['collectName'];
                $resultList = $result[$tableName];
                // 书籍编号
                $sBookNumber = $result['sBookNumber'];
                $sBuyingLinks = $result['sBuyingLinks'];
                $offset = ($page - 1) * ($this->pageSize);
                foreach ($resultList as $key => $val) {
                    $imgPath = getImagePath('product_vector_refrence_list', $val);
                    $resultList[$key]['smallPicPath'] = $imgPath['smallPath'];
                    $resultList[$key]['tableName'] = $tableName;
                    $resultList[$key]['realName'] = 'vectorrefrence';
                    $resultList[$key]['columnId'] = $columnId;
                    $resultList[$key]['index'] = $offset++;
                }
                $theme = $resultList[0]['theme'];
                //描述
                $bookDescription = $resultList[0]['digest'];
                $showForm = 'P';

                //趋势手稿和矢量文件的关联连接
                $relateLinks = $this->book->getVectorLinks($tableName, $bookId);
                break;
        }

        if ($totalCount <= 0) {
            //找不到内容时，跳404
            show_404();
            exit;
        }
        $this->assign('relateLinks', $relateLinks);

        // 获取关联视频专栏的视频数据
        $this->load->model('video_model');
        if (!empty($bookId) && ($columnId == 71 || $columnId == 131)) {
            $relation_pop_id = 'brochures_' . $bookId;
            $videoData = $this->video_model->getVideoDataByRelationPopId($relation_pop_id);
        }
        $this->assign('videoData', !empty($videoData) ? $videoData : []);

        //---------------------------------------权限判定---------------------------------------------------------
        $realTableName = getProductTableName($tableName);
        $conditions = $searchResult = array();
        $conditions['pri_id'] = $bookId;
        $conditions['tablename'] = $realTableName;
        if ($realTableName == 'product_vector_refrence_group') {
            $realTableName = 'product_vector_refrence_list';
        }
        $data = OpPopFashionMerger::getProductData($bookId, $realTableName);
        $data = $data[$bookId];
        $path = getImagePath($realTableName, $data);
        $data['cover'] = $path['cover'];

        // 71-广告大片 | 131-订货会合辑（使用其他的模板）
        $filter = $filterData = $insideAllData = [];
        if (in_array($columnId, array(71, 131))) {
            // 性别
            $data['genderName'] = $this->video_model->getAttrName($data['iGender'], 'gen');
            // 设计师（品牌）
            $data['brand_name'] = $this->video_model->getAttrName($data['brand_tid'], 'bra');
            // 地区
            $data['regionName'] = $this->video_model->getAttrName($data['iRegion'], 'reg');
            // 单品
            $data['iCategoryName'] = GetCategory::getOtherFromIds($data['iCategory'], ['sName'], '');
            // 特殊需求： 71 广告大片  与  131 订货会合辑的单品，只取一个(没有顺序要求)
            $iCategoryNames = explode(" ", trim($data['iCategoryName']));
            $data['iCategoryName'] = !empty($iCategoryNames) ? $iCategoryNames[0] : '';
            // 季节
            if (isset($data['for_date'])) {
                $seasonId = GetCategory::getIdsFrom(5, "sOriginalName,{$data['for_date']}");
            } elseif (isset($data['for_date_text'])) {
                $seasonId = GetCategory::getIdsFrom(5, "sOriginalName,{$data['for_date_text']}");
            }
            if (!empty($seasonId)) {
                $data['seasonName'] = $this->video_model->getAttrName($seasonId[0], 'sea');
            }
            if (!empty($data['brand_tid'])) {
                // 季节筛选数据,如果季节作为参数，要先转化
                $filterData['iSeason'] = $this->video_model->getRelevantSeason($data, $columnId);
                if (!empty($filterData['iSeason'])) {
                    // 选中季节
                    $filter['sea'] = !empty($seasonId) ? $seasonId[0] : '';
                }
                // 71-广告大片的同一品牌/季节下的其他数据
                $seasonId = !empty($seasonId) ? $seasonId[0] : '';
                $brandId = $data['brand_tid'];
                $compact = compact('seasonId', 'brandId');
                $insideAllData = $this->video_model->getColumnInsideAllData($compact, $columnId);
                $filter['id'] = !empty($data['id']) ? $data['id'] : '';
            }
        }
        $this->assign('filter', $filter);
        $this->assign('insideAllData', $insideAllData);
        $this->assign('filterData', $filterData);
        $this->assign('data', $data);

        //性别
        if (isset($data['sGender'])) {
            $genderId = $data['sGender'];
        } elseif (isset($data['iGender'])) {
            $genderId = $data['iGender'];
        } elseif (isset($data['typ'])) {
            $genderId = GetCategory::getIdsFrom(1, "sOriginalName,{$data['typ']}");
        }
        //mostrends_content
        if (in_array($tableName, ['mostrend_content', 'picture']) && isset($data['type'])) {
            $genderId = GetCategory::getIdsFrom(1, "sName,{$data['type']}");
        }
        if (isset($genderId)) {
            if (is_array($genderId)) {
                $genderId = $genderId[0];
            }
        } //product_vector_refrence_group
        elseif (isset($data['belong_type'])) {
            $genderId = GetCategory::getIdsFrom(1, "sOriginalName,{$data['belong_type']}");
            if (is_array($genderId)) {
                $genderId = $genderId[0];
            }
        }

        //获取预览模式
        $rs = OpPopFashionMerger::getProductData($bookId, $collectName);
        if ($rs[$bookId]['iPreviewMode'] == 1) {//快速预览
            $this->assign('viewMode', 'fastView');
            $this->assign('page', $page);
            $this->assign('shadeFlag', TRUE);
        }
        //顶部广告
        $aTopAds = $this->common_model->getAds($columnId, 8, 1, 6);
        //侧边广告
        $aBroadsideAd = $this->common_model->getAds($columnId, 9, 2, 6);
        $this->assign('aTopAds', $aTopAds);
        $this->assign('aBroadsideAd', $aBroadsideAd);

        //中间页标签
        $tags = [
            'colName' => GetCategory::getOtherFromColId($columnId, 'sName'), 'colLink' => $this->common_model->getLink($columnId),
            'regName' => GetCategory::getFieldFromId($data['iRegion']), 'regLink' => $this->common_model->getLink($columnId, '', 'reg', $data['iRegion']),
            'seaName' => GetCategory::getOtherFromIds($data['iSeason'], ['sName'], ''), 'seaLink' => $this->common_model->getLink($columnId, '', 'sea', $data['iSeason']),
        ];
        if ($genderId) {
            $tags['genInfo'][] = ['sName' => GetCategory::getOtherFromIds($genderId, ['sName'], ''), 'link' => $this->common_model->getLink($columnId, '', 'gen', $genderId)];
        }
        if ($data['iIndustry']) {
            $tags['indInfo'][] = ['sName' => GetCategory::getOtherFromIds($data['iIndustry'], ['sName'], ''), 'link' => $this->common_model->getLink($columnId, '', 'ind', $data['iIndustry'])];
        }
        if ($data['iCategory']) {
            $tags['catInfo'][] = ['sName' => GetCategory::getOtherFromIds($data['iCategory'], ['sName'], ''), 'link' => $this->common_model->getLink($columnId, '', 'cat', $data['iCategory'])];
        }
        $this->assign('middlePageTags', $tags);


        //查看列表权限
        $powerArr = memberPower('other');

        // 手稿合辑栏目-游客可以看免费预览书籍
        if ($powerArr['P_UserType'] == 5 || ($powerArr['P_UserType'] == 4 && $rs[$bookId]['iPreviewMode'] != 1)) {//手稿合辑快速预览功能权限开放给普通用户
            //普通身份
            $this->assign('descriptionHtml', $bookDescription);
            $this->assign('sBookNumber', $sBookNumber);
            $this->assign('tit', $theme);
            $this->assign('column', $columnId);
            $this->assign('cover', $data['cover']);

            //TDK
            $this->assign('title', $theme . '-POP服装趋势网');
            $this->assign('keywords', $theme);
            $this->assign('description', $theme);

            $this->benchmark->mark('actionEnd');
            $this->assign("P_UserType", $powerArr['P_UserType']);
            $this->display('mid_tourist.html');
            die;
        }

        //查看详情权限
        $detailPower = memberPower('detail', array('P_Gender' => $genderId, 'P_Industry' => $data['iIndustry'], 'P_Column' => $columnId));
        if (!$detailPower['P_Visit'] && $rs[$bookId]['iPreviewMode'] != 1) {//手稿合辑快速预览功能权限开放给普通用户
            $genVal = GetCategory::getOtherFromIds($genderId, array('sName'), 'string');
            $indVal = GetCategory::getOtherFromIds($data['iIndustry'], array('sName'), 'string');

            $pColVal = GetCategory::getOtherFromColId($this->columnPid, 'sName');
            $sColVal = GetCategory::getOtherFromColId($columnId, 'sName');

            $this->assign('sGender', $genVal);
            $this->assign('iIndustry', $indVal);
            $this->assign('columnP', $pColVal);
            $this->assign('columnC', $sColVal);

            $this->assign("partVip", true);
            $this->assign('descriptionHtml', $bookDescription);
            $this->assign("P_UserType", $powerArr['P_UserType']);
            $this->assign('sBookNumber', $sBookNumber);
            $this->assign('tit', $theme);
            $this->assign('column', $columnId);
            $this->assign('cover', $data['cover']);
            //TDK
            $this->assign('title', $theme . '-POP服装趋势网');
            $this->assign('keywords', $theme);
            $this->assign('description', $theme);
            $this->benchmark->mark('actionEnd');
            $this->display('mid_tourist.html');
            die;
        }
        //---------------------------------------权限判定---------------------------------------------------------

        //收藏判断收藏状态  1已收藏 -1无收藏权限 0未收藏
        $bIsCollect = 0;
        if ($powerArr['P_Collect']) {
            $this->load->model('collect_model');
            $aLogonMessage = get_cookie_value();
            $whetherCollect = $this->collect_model->existCollectStatus($aLogonMessage['sChildID'], $database, $collectName, $bookId);
            if ($whetherCollect) {
                $bIsCollect = 1;
            }
        } else {
            $bIsCollect = -1;
        }

        $this->assign('bIsCollect', $bIsCollect);
        $this->assign('realName', $collectName);

        //展现形式
        switch (strtoupper($showForm)) {
            case 'L':
                $listPage = 'bookl.html';
                break;
            case 'P':
                $listPage = 'bookp.html';
                break;
            case 'M':
                $listPage = 'bookm.html';
                break;
        }

        $uri = '/books/seclist/';
        //生成页码
        $pageHtml = $this->makePageHtml($params, $totalCount, $this->pageSize, $page, $uri);
        //生成简单页码
        $simplePageHtml = $this->makePageHtml($params, $totalCount, $this->pageSize, $page, $uri, TRUE);

        $mainID = $bookId;
        $mtab = $tableName;
        $this->assign('mainID', $mainID);
        $this->assign('mtab', $mtab);

        //水印遮罩
        if ($powerArr['P_Shade']) {
            if ($page >= 2) {
                $this->assign('shadeFlag', TRUE);
            } else {
                $this->assign('shadeFlag', FALSE);
            }
        } else {
            $this->assign('shadeFlag', FALSE);
        }

        //下载链接  高端趋势书店 矢量文件 单张 | POP书店 单品合集 lookbook 成册
        if (!in_array($tableName, ['refrencegroup'])) {
            //成册下载链接
            if ($tableName == 'moscon') {
                if ($data['pdf_name']) {
                    if ($relateLinks['vector'] && count($relateLinks) == 2) {
                        $downLoadUrl = '/download/book/' . $tableName . '-' . $bookId . '-' . $columnId . '-' . $mtab . '-' . $mainID . '/';
                    } else {
                        $downLoadUrl = '/download/book/' . $tableName . '-' . $bookId . '-' . $columnId . '/';
                    }
                }
            } else {
                if ($relateLinks['vector'] && count($relateLinks) == 2) {
                    $downLoadUrl = '/download/book/' . $tableName . '-' . $bookId . '-' . $columnId . '-' . $mtab . '-' . $mainID . '/';
                } else {
                    $downLoadUrl = '/download/book/' . $tableName . '-' . $bookId . '-' . $columnId . '/';
                }
            }
        } else {
            // 单张下载链接
            // https://2016.pop-fashion.com/download/dlsingle/?dl_link=https%3A%2F%2F2016.pop-fashion.com%2Fglobal%2Fimages%2Fimg1.pdf
            // $downLoadUrl = '/download/dlsingle/?';
        }

        //免费预览，所有用户均不可下载
        if ($rs[$bookId]['iPreviewMode'] == 1) {
            $downLoadUrl = '';
        }

        // 面包屑效果
        $__columnId = $columnId;
        if ($columnId == 73) {
            $__columnId = $relateLinks['design']['col'];
        }
        $sColLink = $this->common_model->getLink($__columnId);
        $sColName = GetCategory::getOtherFromColId($__columnId, 'sName');
        $this->assign('sColLink', $sColLink);
        $this->assign('sColName', $sColName);

        $this->assign('downLoadUrl', $downLoadUrl);
        $this->assign('pageHtml', $pageHtml);
        $this->assign('simplePageHtml', $simplePageHtml);
        $this->assign('totalCount', $totalCount);

        $this->assign('columnId', $columnId);//栏目id
        $this->assign('id', $bookId);
        $this->assign('ct', $tableName);
        $this->assign('bookDescription', $bookDescription);
        $this->assign('sBookNumber', $sBookNumber);
        $this->assign('sBuyingLinks', $sBuyingLinks);

        $this->assign('list', $resultList);
        $this->assign('theme', $theme);
        //TDK
        $this->assign('title', $theme . '-POP服装趋势网');
        $this->assign('keywords', $theme);
        $this->assign('description', $theme);
        $this->assign("P_UserType", $powerArr['P_UserType']);

        // 模板中区分是否是书籍
        $this->assign('isBook', true);

        $this->benchmark->mark('actionEnd');
        $this->display($listPage);
    }

    // 服装杂志二级列表
    public function innermaga($args = '')
    {
        //权限
        $specialPower = memberPower('other');

        $this->load->model('books_model');
        $paramsArr = $this->common_model->parseParams($args, 1, FALSE);
        $magazineID = $paramsArr['id'];
        $rootUrl = '/books/innermaga/';

        //反扒参数
        $this->load->model('Secret_model');
        list($symmetricKey, $timeStamp) = $this->Secret_model->init_data();
        $token = $this->Secret_model->get_token();
        $this->assign('token', $token);//token(uid_popuid_secret)
        $this->assign('sign', $symmetricKey);//AES_KEY
        $this->assign('timeStamp', $timeStamp);//生成AES_KEY的时间戳

        //浏览模式
        $viewModel = isset($paramsArr['model']) && !empty($paramsArr['model']) ? trim($paramsArr['model']) : '';
        $list = $picLists = [];

        $picLists = $this->books_model->getMagazineInfo($magazineID, $list);
        $totalCount = count($picLists);
        $magazineInfo = $list[0];
        $count = $totalCount - 1;

        //当前页
        $page = $this->common_model->getPage($paramsArr);
        $pageSize = 60;
        $start = ($page - 1) * $pageSize;
        if ($start > ($count - 1)) {
            $start = $count - 1;
        }
        if (($start + $pageSize) > $count) {
            $end = $totalCount;
        } else {
            $end = $start + $pageSize;
        }

        for ($i = $start; $i < $end; $i++) {
            if ($viewModel == "isSimple") {//精简版
                if ($picLists[$i]['is_old']) {
                    $middle_imageurl = IMAGE_MAGAZINE_PATH . '/' . $picLists[$i]['img_url'] . '/simple/thumbnails/' . $picLists[$i]['list_img_name'];
                } else {
                    if ($picLists[$i]['source'] != "") {
                        $web_site = $picLists[$i]['source'];
                    } else {
                        $web_site = $picLists[$i]['site'];
                    }
                    $middle_imageurl = '/' . $web_site . '/' . substr($picLists[$i]['create_date'], 0, 4) . '/' . $picLists[$i]['create_date'] . '/' . $picLists[$i]['serial_number'] . '/detail/small/' . $picLists[$i]['list_img_name'];
                }
            } else {
                if ($picLists[$i]['is_old']) {
                    // 老数据
                    $middle_imageurl = IMAGE_MAGAZINE_PATH . '/' . $picLists[$i]['img_url'] . '/detail/thumbnails/' . $picLists[$i]['list_img_name'];
                } else {
                    if ($picLists[$i]['source'] != "") {
                        $web_site = $picLists[$i]['source'];
                    } else {
                        $web_site = $picLists[$i]['site'];
                    }
                    $middle_imageurl = '/' . $web_site . '/' . substr($picLists[$i]['create_date'], 0, 4) . '/' . $picLists[$i]['create_date'] . '/' . $picLists[$i]['serial_number'] . '/detail/small/' . $picLists[$i]['list_img_name'];
                }
            }
            $magzine_bigview = $magazineID . 'I' . str_replace(array('.', '_'), array('qq', '-'), $picLists[$i]['list_img_name']);
            $portionPics[$i]['magzine_bigview'] = $magzine_bigview;//大图
            $portionPics[$i]['middle_imageurl'] = $picLists[$i]['list_img_name'] ? $middle_imageurl : '';
        }

        //--------------------------------------普通用户和游客时（中间页）-----------------------------------------------
        $columnId = 130;
        if ($specialPower['P_UserType'] == 4 || $specialPower['P_UserType'] == 5) {
            //中间页标签
            $colLink = $this->common_model->getLink($columnId);
            $categorys = explode(",", $magazineInfo["attribute"]["category"]);
            $tags = [
                'colName' => GetCategory::getOtherFromColId($columnId, 'sName'), 'colLink' => $colLink,
                'regName' => $magazineInfo["attribute"]["region"], 'regLink' => $colLink . "?key=" . urlencode($magazineInfo["attribute"]["region"]),
                'seaName' => $magazineInfo["attribute"]["fordate"], 'seaLink' => $colLink . "?key=" . urlencode($magazineInfo["attribute"]["fordate"]),
            ];
            foreach ($categorys as $val) {
                if (!empty($val)) {
                    $tags['catInfo'][] = ['sName' => $val, 'link' => $colLink . "?key=" . urlencode($val)];
                }
            }
            $this->assign('middlePageTags', $tags);

            //顶部广告
            $aTopAds = $this->common_model->getAds($this->columnPid, 8, 1, 6);
            $this->assign('aTopAds', $aTopAds);
            //侧边广告
            $aBroadsideAd = $this->common_model->getAds($this->columnPid, 9, 2, 6);
            $this->assign('aBroadsideAd', $aBroadsideAd);
            //缩略图
            $cover = $portionPics[0]["middle_imageurl"] ? STATIC_URL1 . $portionPics[0]["middle_imageurl"] : '';
            $this->assign('cover', $cover);
            //TDK
            $this->assign('title', $magazineInfo["column_title"] . '-POP服装趋势网');
            $this->assign('keywords', $magazineInfo["column_title"]);
            $this->assign('description', $magazineInfo["column_title"]);
            $this->assign('descriptionHtml', $magazineInfo["description"]);
            $this->assign('tit', $magazineInfo["column_title"]);
            $this->display('mid_tourist.html');
            die;
        }

        if ($viewModel == 'isSimple') {
            //下载链接
            $downLoadUrl = $this->config->item('base_url') . 'download/magazine/' . $magazineID . '-' . $viewModel . '/';
        } else {
            //下载链接
            $downLoadUrl = $this->config->item('base_url') . 'download/magazine/' . $magazineID . '/';
        }
        $download = TRUE;
        if ($specialPower['P_UserType'] == 3 && $this->iAccountType == 2) {
            $download = false;
        }

        if ($magazineInfo['control_down'] == 1 && date('Y-m-d H:i:s', strtotime('-15 day')) < date("Y-m-d H:i:s", $magazineInfo['create_time'])) {
            $download = FALSE;
        }
        $this->assign('viewModel', $viewModel);

        // 流行画册【二级列表页】增加标签（书名，季节，风格）
        $category = explode(',', $magazineInfo['attribute']['category']);// 风格
        $magazine_tags = [
            'bookName' => [ // 流行画册--书名
                'name' => $magazineInfo['set_brand'],
                'link' => $this->common_model->getLink($columnId, '', 'key', $magazineInfo['set_brand'])
            ],
            'season' => [ // 流行画册--季节
                'name' => $magazineInfo['attribute']['fordate'],
                'link' => $this->common_model->getLink($columnId, '', 'key', $magazineInfo['attribute']['fordate'])
            ],
            'catagory' => [ // 流行画册--风格
                'name' => $category[0],
                'link' => $this->common_model->getLink($columnId, '', 'key', $category[0])
            ],
            'subcatagory' => [ // 流行画册--子风格
                'name' => $category[1],
                'link' => $this->common_model->getLink($columnId, '', 'key', $category[1])
            ],
        ];
        // 去空
        foreach ($magazine_tags as $key => $magazine_tag) {
            if (empty($magazine_tag['name'])) {
                unset($magazine_tags[$key]);
            }
        }
        $this->assign('magazine_tags', $magazine_tags);

        //生成页码
        $pageHtml = $this->makePageHtml($paramsArr, $totalCount, $pageSize, $page, $rootUrl);
        //生成简单页码
        $simplePageHtml = $this->makePageHtml($paramsArr, $totalCount, $pageSize, $page, $rootUrl, TRUE);

        // 面包屑效果
        $sColLink = $this->common_model->getLink($columnId);
        $sColName = GetCategory::getOtherFromColId($columnId, 'sName');
        $this->assign('sColLink', $sColLink);
        $this->assign('sColName', $sColName);


        $this->assign('title', $magazineInfo['column_title'] . '-POP服装趋势网');
        $this->assign('keywords', $magazineInfo['column_title']);
        $this->assign('description', $magazineInfo['description']);


        $this->assign('portionPics', $portionPics);
        $this->assign('magazineID', $magazineID);
        $this->assign('totalCount', $totalCount);
        $this->assign('magazineInfo', $magazineInfo);
        $this->assign('pageHtml', $pageHtml);
        $this->assign('downLoadUrl', $downLoadUrl);
        $this->assign('simplePageHtml', $simplePageHtml);
        $this->assign('download', $download);
        $rename = htmlspecialchars_decode($magazineInfo['column_title']);
        $rename = preg_replace("/[^\x{4e00}-\x{9fa5}a-z0-9\_]/iu", "", $rename);
        $this->assign('dlRename', urlencode($rename));
        $this->assign('isMagazine', true);

        $this->display('lists/share_magazine_inside.html');
    }

    // 筛选项
    public function filterconditions()
    {
        // 用户权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $columnId = $this->input->post('col');
        $params = $this->input->post('params');
        $paramsArr = $this->common_model->parseParams($params, 1);

        $share = isset($paramsArr['sha']) ? true : false;
        //查询条件
        $selectItem = ['iSeason'];

        switch ($columnId) {
            case '71':// Lookbook
                $selectItem[] = 'sClass';// 类型
                $selectItem[] = 'sCategory';// 单品
                $selectItems = $this->books_model->getSelectItems($selectItem, $columnId, $params, $powers, $share);
                $sClass = $selectItems['sClass'];
                $sCategory = $selectItems['sCategory'];
                $this->assign('sClass', $sClass);
                $this->assign('categorys', $sCategory);
                break;
            case '115':// 快反应系列
                $selectItem[] = 'sContentDirection';// 书籍类型
                $selectItems = $this->books_model->getSelectItems($selectItem, $columnId, $params, $powers, $share);
                $sContentDirection = $selectItems['sContentDirection'];
                $this->assign('sContentDirection', $sContentDirection);
                break;
            case '72':// 单品合辑
                // $selectItem[] = 'sContentDirection';
                $selectItem[] = 'sCategory';// 单品
                $selectItems = $this->books_model->getSelectItems($selectItem, $columnId, $params, $powers, $share);
                // $sContentDirection = $selectItems['sContentDirection'];
                $sCategory = $selectItems['sCategory'];
                // $this->assign('sContentDirection', $sContentDirection);
                $this->assign('categorys', $sCategory);
                break;
            case '131':// 订货会合辑
                $selectItem[] = 'sCategory';// 单品
                $selectItems = $this->books_model->getSelectItems($selectItem, $columnId, $params, $powers, $share);
                $sCategory = $selectItems['sCategory'];
                $this->assign('categorys', $sCategory);
                break;
            default:
                $selectItem[] = 'sContentDirection';// 书籍类型
                $selectItem[] = 'sCategory';// 单品

                if ($columnId == 70) {
                    $selectItem[] = 'iDataSource';
                }

                $selectItems = $this->books_model->getSelectItems($selectItem, $columnId, $params, $powers, $share);
                $sContentDirection = $selectItems['sContentDirection'];
                $sCategory = $selectItems['sCategory'];

                if (isset($selectItems['iTrendOrManual']) && $selectItems['iTrendOrManual']) {
                    $this->assign('iTrendOrManual', $selectItems['iTrendOrManual']);
                }

                $this->assign('sContentDirection', $sContentDirection);
                $this->assign('categorys', $sCategory);
                break;
        }

        $iSeason = $selectItems['iSeason'];
        $this->assign('seasons', $iSeason); // 季节
        $this->assign('columnId', $columnId);
        $this->assign('paramsArr', $paramsArr);

        if ($share) {
            echo $this->fetch('lists/books_share_select_items.html');
        } else {
            echo $this->fetch('lists/books_select_items.html');
        }
    }

    /**
     * 获取列表数据和分页
     *
     * @param $params
     * @param $columnId
     * @param $powers
     * @param $rootUrl
     * @param string $type
     */
    private function getLists($params, $columnId, $powers, $rootUrl, $type = 'books')
    {
        $this->benchmark->mark('getList');

        $paramsArr = $this->common_model->parseParams($params, 1);

        list($lists, $info) = $this->books_model->ajaxList($params, $columnId, $powers, $pageSize = 0, $isAjax = false);
        $totalCount = $info['totalCount'];
        $page = $info['page'];
        $pageSize = $info['pageSize'] * 2;
        $time = $info['time']; // 请求次数

        //生成页码
        $pageHtml = $this->makePageHtml($paramsArr, $totalCount, $pageSize, $page, $rootUrl);
        $simplePageHtml = $this->makePageHtml($paramsArr, $totalCount, $pageSize, $page, $rootUrl, TRUE);

        $this->assign('lists', $lists);
        $this->assign('page', $page);
        $this->assign('pageSize', $pageSize);
        $this->assign('totalCount', $totalCount);
        $this->assign('pageHtml', $pageHtml);
        $this->assign('simplePageHtml', $simplePageHtml);
        $this->assign('powers', $powers);

        $this->benchmark->mark('getListEnd');
    }

    /**
     * 获取seo搜索引擎，标题、关键字、描述
     * @param $columnId int|string 栏目ID
     * @param $params string 参数字符串
     */
    private function getTDK($columnId, $params)
    {
        $seoArray = $this->books_model->getSeoArray($columnId, $params);

        $this->assign('title', $seoArray['title']);
        $this->assign('keywords', $seoArray['keywords']);
        $this->assign('description', $seoArray['description']);
    }


}

