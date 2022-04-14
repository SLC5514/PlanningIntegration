<?php

/**
 * 前台商城控制器
 * Date: 2017/03/07
 */
class Item extends POP_Controller
{
    public $useInfo=[];

    public function __construct()
    {
        parent::__construct();

        $this->useInfo = get_cookie_value();

        $this->load->model('item_model');
    }

    /**
     * 新商城首页入口
     * @param  string $params [description]
     * @return void
     */
    public function index()
    {
        // 轮播图
        $loopPics = $this->item_model->getLoopPicsForIndex();
        // 首推商品列表（3条）
        $recList = $this->item_model->getRecommendList();
        // 参数数组
        $conditions = $this->item_model->getConditions();
        // 专业书刊列表（6条）
        $conditions['iCategory'] = 3;
        $proBooks = [];
        $this->item_model->getItemList($conditions, $proBooks, 1, 6);
        // 商品选定大分类时，小分类是否出现，通过solr group 小分类来确定
        $bookGroup = $this->item_model->getItemGroupCount('', $conditions);
        // 超值服务列表（3条）
        $conditions['iCategory'] = 4;
        $premiumServices = [];
        $this->item_model->getItemList($conditions, $premiumServices, 1, 3);
        // 商品选定大分类时，小分类是否出现，通过solr group 小分类来确定
        $srvGroup = $this->item_model->getItemGroupCount('', $conditions);

        // 周边零售列表（3条）
        $conditions['iCategory'] = 5;
        $peripheralRetails = [];
        $this->item_model->getItemList($conditions, $peripheralRetails, 1, 3);
        // 商品选定大分类时，小分类是否出现，通过solr group 小分类来确定
        $retailGroup = $this->item_model->getItemGroupCount('', $conditions);
        // print_r($recList);

        //$hotWords = ['2018春夏', '色彩趋势', '欧洲游学'];
		//$hotWords = ['2018春夏', '色彩趋势'];
        $hotWords = [ '18/19秋冬' , 'APM' , '趋势色卡' , '图案工艺' ];
        $hotLinks = [];
        foreach ($hotWords as $value) {
            $search = $this->common_model->getSearchSufix($value);
            $link = '/item/seclist/' . $search;
            $hotLinks[] = ['link' => $link, 'word' => htmlspecialchars($value)];
        }
        $this->assign('loopPics', $loopPics);
        $this->assign('recList', $recList);
        $this->assign('proBooks', $proBooks);
        $this->assign('bookGroup', $bookGroup);
        $this->assign('premiumServices', $premiumServices);
        $this->assign('srvGroup', $srvGroup);
        $this->assign('peripheralRetails', $peripheralRetails);
        $this->assign('retailGroup', $retailGroup);
        $this->assign('hotLinks', $hotLinks);
        $isPopMall = true;
        $this->assign('isPopMall', $isPopMall);
        // TKD --
        $title = 'POP商城-POP服装趋势网';
        $keywords = 'POP商城';
        $description = 'POP服装趋势网POP商城栏目拥有非常丰富的趋势指导、高性价比的超值服务和多样化的周边零售内容。';
        $this->assign('title', $title);
        $this->assign('keywords', $keywords);
        $this->assign('description', $description);
        // TKD --

        $this->display('popmall/popmall.html');
    }

    /**
     * 商品二级列表页
     * @param  string $params [a_b-c_d-e_f] => a=b,c=d,e=f
     * @return void
     */
    public function seclist($params='')
    {
        $keys = $this->common_model->getKeyword('', ['P_Search' => true]);
        $params = $this->common_model->restoreParams($params);
        $paramsArr = $this->common_model->parseParams($params, 1);

        $condition = $this->item_model->getConditions($paramsArr);
        $page = $this->common_model->getPage($paramsArr); //当前页
        $limit = 20;
        $secondList = [];
        if ($keys) {
            $condition['combine'] = strtolower($keys);
        }
        $totalCount = $this->item_model->getItemList($condition, $secondList, $page, $limit);

        // 商品选定大分类时，小分类是否出现，通过solr group 小分类来确定
        // 切换全部的时候下面3个大类和12个小类都不要显示
        $subCategoryGroup = $this->item_model->getItemGroupCount($keys, $condition);

        if ($condition['iCategory']) {
            $allLink = '/item/seclist/mct_' . $condition['iCategory'] . '/';
        } else {
            $allLink = '/item/seclist/';
            $currentOption['link'] = $allLink;
        }

        $categoryOption = OpPopIndustryActivity::getDictCategory('itemType');

        $currentOption = [];
        $currentOption['link'] = $allLink;
        $currentOption['txt'] = '全部';
        if ($condition['iCategory']) {
            $currentOption['txt'] = $categoryOption[$condition['iCategory']];
        }

        $rootUrl = '/item/seclist/';
        // 生成页码
        $pageHtml = $this->makePageHtml($paramsArr, $totalCount, $limit, $page, $rootUrl);
        // 生成简单页码
        //$simplePageHtml = $this->makePageHtml($paramsArr, $totalCount, $limit, $page, $rootUrl, TRUE);
        $totalPage = ceil($totalCount / $limit);
        if ($paramsArr) {
            $pageUrl = [];
            foreach ($paramsArr as $key => $value) {
                if ($key != 'page') {
                    $pageUrl[] = $key.'_'.$value;
                }
            }
        }
        if ($page == 1 ) {
            $preNum = 1;
        } else {
            $preNum = $page - 1;
        }

        if ($page == $totalPage) {
            $nextNum = $totalPage;
        } else {
            $nextNum = $page + 1;
        }
        $keySearch = $this->common_model->getSearchSufix($keys);
        $pageUrl['page'] = 'page_' . $preNum;
        $prePage = $rootUrl . implode('-', $pageUrl) . '/' . $keySearch;
        $pageUrl['page'] = 'page_' . $nextNum;
        $nextPage = $rootUrl . implode('-', $pageUrl) . '/' . $keySearch;
        // 大类的值
        $mct = isset($paramsArr['mct']) ? $paramsArr['mct'] : '';
        // 小类的值
        $mst = isset($paramsArr['mst']) ? $paramsArr['mst'] : '';

        $this->assign('keyword', $keys ? htmlspecialchars($keys) : '请输入关键字');
        $this->assign('categoryOption', $categoryOption);
        $this->assign('currentOption', $currentOption);

        $this->assign('prePage', $prePage);
        $this->assign('nextPage', $nextPage);
        $this->assign('secondList', $secondList);
        $this->assign('totalCount', $totalCount);
        $this->assign('pageHtml', $pageHtml);
        $this->assign('page', $page);
        $this->assign('totalPage', $totalPage);
        //$this->assign('simplePageHtml', $simplePageHtml);
        $this->assign('allLink', $allLink);
        $this->assign('subCategoryGroup', $subCategoryGroup);
        $this->assign('mst', $mst);
        $this->assign('mct', $mct);
        $isPopMall = true;
        $this->assign('isPopMall', $isPopMall);
        // TKD --
        if ($totalCount) {
            $seoArray = $this->getSecListSeo($mct, $mst);
            $title = $seoArray['title'];
            $keywords = $seoArray['keywords'];
            $description = $seoArray['description'];
        } else {
            $title = '没有找到商品_POP商城-POP服装趋势网';
            $keywords = '没有找到商品';
            $description = '很抱歉，没有找到符合条件的商品！';
        }
        $this->assign('title', $title);
        $this->assign('keywords', $keywords);
        $this->assign('description', $description);
        // TKD --
        $this->display('popmall/popmall_list.html');
    }
    /**
     * 获取二级列表页的seo
     * @param  integer $c 大分类的值
     * @param  integer $s 小分类的值
     * @return [type]     [description]
     */
    public function getSecListSeo($c = 3, $s = 9)
    {
        if ($c == '') {
            // 全部
            return ['title' => '趋势指导_超值服务_周边零售_POP商城-POP服装趋势网', 'keywords' => '趋势指导,超值服务,周边零售', 'description' => 'POP服装趋势网全部栏目涉及趋势指导、超值服务和周边零售三个板块。'];
        } else {
            if ($s) {
                $categoryOption = OpPopIndustryActivity::getDictCategory('itemType');
                $categoryOptionChild = OpPopIndustryActivity::getDictCategory('itemTypeChild');
                $word = $categoryOption[$c];
                $wordChild = $categoryOptionChild[$c][$s];
                $res = [];
                $res['title'] = "{$wordChild}_{$word}-POP服装趋势网";
                $res['keywords'] = $wordChild;
                $tmp = [
                    3 => "POP服装趋势网{$wordChild}栏目汇集服装相关的杂志、期刊、画册、矢量手稿等资讯内容，为您提供有价值的服装设计资讯服务。",
                    4 => "POP服装趋势网{$wordChild}栏目为设计师提供详细完善的在线设计方案和专业的知识体系，以及丰富的面料。",
                    5 => "POP服装趋势网{$wordChild}栏目提供国际知名卖场的丰富实拍图和色彩卡。",
                ];
                $res['description'] = $tmp[$c];
                return $res;
            } else {
                $tmp = [
                    3 => ['title' => '趋势指导_POP商城-POP服装趋势网', 'keywords' => '超级手稿,单品合辑,T台系列,快反应系列', 'description' => 'POP服装趋势网趋势指导栏目包括超级手稿、单品合辑、T台系列和快反应系列四个板块。'],
                    4 => ['title' => '超值服务_POP商城-POP服装趋势网', 'keywords' => '游学团,讲堂,找料帮,数据包', 'description' => 'POP服装趋势网超值服务栏目包括游学团、讲堂、找料帮和数据包四个板块。'],
                    5 => ['title' => '周边零售_POP商城-POP服装趋势网', 'keywords' => '资料盘,色卡', 'description' => 'POP服装趋势网周边零售栏目包括资料盘和色卡两个板块。'],
                ];
                return $tmp[$c];
            }
        }

    }

    /**
     * 商品详情页
     * @param  string $params [description]
     * @return void
     */
    public function detail($params='')
    {
        // 链接形式 /item/detail/id_385340/
        // $params = $this->common_model->restoreParams($params);
        // $paramsArr = $this->common_model->parseParams($params, 1);
        $id = array_pop(explode('_',$params));
        $itemInfo = OpPopIndustryActivity::getProductData($id, 't_commodity');

        if (false === $itemInfo) {
            show_404();
            exit;
        }
        $itemInfo = $this->item_model->handleItemInfo($itemInfo, TRUE);
        $itemInfo = $itemInfo[$id];

        $category = OpPopIndustryActivity::getDictCategory('itemType');
        $subCategory = OpPopIndustryActivity::getDictCategory('itemTypeChild');



        $iCategory = $itemInfo['iCategory'];
        $itemInfo['available'] = false;
        $today = date("Y-m-d");
        if ($itemInfo['iCommodityStatus'] == 1
            && strtotime(array_shift(explode(' ', $itemInfo['dPutawayTime']))) <= strtotime($today)
            && strtotime(array_shift(explode(' ', $itemInfo['dSoldOutTime']))) >= strtotime($today)
        ) {
            $itemInfo['available'] = true;
        }

        $subCategoryId = $itemInfo['iSubcategory'];
        $categoryHtml = $category[$iCategory];
        $categoryLink = '/item/seclist/mct_' .$iCategory.'/';
        $subCategoryHtml = $subCategory[$iCategory][$subCategoryId];
        $subCategoryLink = '/item/seclist/mct_' .$iCategory. '-mst_' . $subCategoryId. '/';

        $otherSubcategory = $this->item_model->getItemGroupCount('', ['iCategory' => $iCategory]);

        //更多推荐[3条]
        $conditonsRec = ['iCategory'=>$iCategory, 'iSubcategory'=>$subCategoryId, 'id'=>$itemInfo['id']]; //推荐条件
        $moreList = $this->item_model->getMoreRecommend($conditonsRec, 3);
        // 登录用户id不存在使用匿名id
        $distinct_id = getUserId(); // 用户id

        $sSmallShopLink = 'http://'.$_SERVER['HTTP_HOST']."/item/turnweixin/?id={$itemInfo['id']}&distinct_id={$distinct_id}&url=".$itemInfo['sSmallShopLink'];
        if ($sSmallShopLink) {
            $itemInfo['qrcodeLink'] = '/item/qrcode/?url=' . rawurlencode($sSmallShopLink);
        } else {
            $itemInfo['qrcodeLink'] = '';
        }
		
		$itemInfo[ 'sDetailPage' ] = preg_replace( 
			"#((href|src)=\"|url)([\(]?[\s]*)((/fashion/mall/)([^\"|\']*?)\.(jpg|png|gif|jpeg))([\)]?)#i" , 
			"$1$3" . STATIC_URL1 . "$4$8", 
			$itemInfo[ 'sDetailPage' ]
		);

        $this->assign('categoryHtml', $categoryHtml);
        $this->assign('categoryLink', $categoryLink);
        $this->assign('subCategoryHtml', $subCategoryHtml);
        $this->assign('subCategoryLink', $subCategoryLink);
        $this->assign('otherSubcategory', $otherSubcategory);

        $this->assign('itemInfo', $itemInfo);
        $this->assign('moreList', $moreList);
        if ($itemInfo['available'] == false) {
            $guessList = []; //猜你喜欢[4条]
            $guessList = $this->item_model->getMoreRecommend($conditonsRec, 4);
            $this->assign('guessList', $guessList);
        }
        $isPopMall = true;
        $this->assign('isPopMall', $isPopMall);

        $sName = $itemInfo['sName'];
        $title = "{$sName}_POP商城-POP服装趋势网";
        $keywords = "{$sName}";
        $description = "POP服装趋势网的{$sName}，包括商品参数、商品详情、产品介绍和产品实拍，可实现在线下单及微信支付。";
        $this->assign('title', $title);
        $this->assign('keywords', $keywords);
        $this->assign('description', $description);

        $this->display('popmall/popmall_detail.html');
    }
    /**
     * 微信二维码
     * @return [type] [description]
     */
    public function qrcode()
    {
        $url = $this->input->get('url');
        include APPPATH . 'phpqrcode/qrlib.php';
        QRcode::png($url, false, 'L', 4, 0);
    }
    /**
     * 跳转中间页，记录后跳转至微信（神策埋点）
     */
    public function turnweixin(){
    	$url = $this->input->get('url');
    	header("Location:".$url);
    }
}
