<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Search 搜索列表的控制器
 */
class Search extends POP_Controller
{
    static $rootUrl = '/search/';

    /**
     * @var Common_model
     */
    public $common_model;

    /**
     * @var Search_model
     */
    public $search_model;

    // 120-大牌花型，是在图案下切换
    private static $columnDict = ['all' => '全部', 1 => '趋势解读', 2 => '流行分析', 4 => '款式', 82 => '图案', 120 => '图案', 3 => 'T台', 71 => '广告大片'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'search_model']);
    }

    public function index($params = '')
    {
        $this->benchmark->mark('action');

        // $search = $this->common_model->getSearchSufix();
        // $this->assign('search', $search);
        $keywords = $this->common_model->getKeyword('');

        // 关键字
        $spaceKeywords = !empty($keywords) ? str_replace('|||', ' ', $keywords) : '';
        $this->assign('keyword', $spaceKeywords);

        // tab切换
        $paramsArr = $this->common_model->parseParams($params, 1);
        $iColumnId = isset($paramsArr['col']) && $paramsArr['col'] && is_numeric($paramsArr['col']) ? (int)$paramsArr['col'] : 'all';
        // 1-趋势解读 2-流行分析 3-T台 4-款式 82-图案 120-大牌花型 71-广告大片
        if (!array_key_exists($iColumnId, self::$columnDict)) {
            show_404();
            die;
        }
        // solr拼写建议
        $spellcheck = $this->search_model->spellcheck();
        $this->assign('spellcheck', $spellcheck);


        // 当前页
        $page = $this->common_model->getPage($paramsArr);

        //------------------------------------------------------------
        // @tab具体栏目封面是否有（遮罩/水印）
        //------------------------------------------------------------
        $compt = $this->search_model->isShowWatermark($paramsArr);
        $powers = memberPower();
        if (in_array($iColumnId, [1, 2])) {
            $showMask = in_array($powers['P_UserType'], [1, 2, 3]) ? $compt['showMask'] : '';
        } else {
            $showMask = $compt['showMask'];
        }
        $this->assign('showMask', $showMask); // 遮罩，显示vip权限
        $this->assign('showWatermark', isset($compt['showWatermark']) ? $compt['showWatermark'] : ''); // 水印

        $totalCount = 0;
        $pageSize = 40;
        $lists = [];
        switch ($iColumnId) {
            case 'all':
                $lists = $this->getAllData($params);
                break;
            case 1:
            case 2:
                $offset = ($page - 1) * $pageSize;
                $lists[$iColumnId] = $this->search_model->getPopReportData($iColumnId, $params, $offset, $pageSize);
                $totalCount = intval($lists[$iColumnId]['cnt']);
                $this->assign('pageSize', $pageSize);
                break;
            case 4:
            case 82:
            case 120:
            case 3:
            case 71:
                if ($iColumnId == 4) {
                    $pageSize = 30;
                } elseif ($iColumnId == 3) {
                    $pageSize = 24;
                } elseif ($iColumnId == 71) {
                    $pageSize = 42;
                }
                $page_limit = 100;
                $page = ($page > $page_limit) ? $page_limit : $page;
                $offset = ($page - 1) * $pageSize;
                $lists[$iColumnId] = $this->search_model->getPopFashionData($iColumnId, $params, $offset, $pageSize);
                $_totalCount = intval($lists[$iColumnId]['cnt']);
                $limit_page = $pageSize * $page_limit; // 100页以后的分页不显示
                $totalCount = $_totalCount > $limit_page ? $limit_page : $_totalCount;
                $this->assign('pageSize', $pageSize);
                break;
        }
        //------------------------------------------------------------
        // @ 分页 （全部不分页，具体tab才分页）
        //------------------------------------------------------------
        if (in_array($iColumnId, [1, 2, 3, 4, 82, 120, 71])) {
            // 生成页码
            $pageHtml = $this->makePageHtml($paramsArr, $totalCount, $pageSize, $page, self::$rootUrl);
            // 生成简单页码
            $simplePageHtml = $this->makePageHtml($paramsArr, $totalCount, $pageSize, $page, self::$rootUrl, true);
            $this->assign('pageHtml', $pageHtml);
            $this->assign('simplePageHtml', $simplePageHtml);
        }
        // 数量 => 搜索后的各个栏目的数量（tab上的栏目）
        $showTotalArr = $this->getShowColumnTotal($params);
        // tdk
        $tdk = $this->search_model->getTDK($spaceKeywords);
        $this->assign('title', $tdk['t']);
        $this->assign('keywords', $tdk['k']);
        $this->assign('description', $tdk['d']);

        //是否是个性化首页
        if (getUserId()) {
            $this->assign('is_relate', true);
        }
        // 更多栏目List
        $moreLists = $this->search_model->getMoreColumnInfo($params);
        $this->assign('moreLists', $moreLists);

        $this->assign('params', $params);
        $this->assign('lists', $lists);
        $this->assign('iColumnPid', $iColumnId); // tab切换
        $this->assign('page', $page);// 当前页
        $this->assign('allTotal', $showTotalArr);
        $this->benchmark->mark('actionEnd');
        $this->display('lists/search_list.html');
    }

    /**
     * @用户筛选条件栏部分
     */
    public function filterConditions()
    {
        $columnId = $this->input->post('col');
        $params = $this->input->post('params');
        if (!in_array($columnId, array_keys(self::$columnDict))) {
            $columnId = 'all';
        }

        // 性别与行业的筛选 【全站统一，性别/行业不联动】
        $tips = $this->common_model->getTips($columnId, $params);

        // 匹配/最新
        if (in_array($columnId, ['all', 1, 2])) {
            $matchs = [1 => '最新', 2 => '匹配'];
            $this->assign('matchs', $matchs);
        }
        $iSeason = $selectItem = [];
        switch ($columnId) {
            case 'all':
            case 1:
            case 2:
            case 4:
            case 3:
            case 71:
            case 82: // 9-(子栏目:82+120+124)-图案,无季节
            case 120:
                $selectItem[] = 'iSeason';
                $selectItems = $this->search_model->getSelectItems($selectItem, $columnId, $params);
                $iSeason = $selectItems['iSeason'];
                break;
        }

        // 关键字
        $keywords = $this->common_model->getKeyword('');
        $spaceKeywords = !empty($keywords) ? str_replace('|||', ' ', $keywords) : '';
        $this->assign('keyword', $spaceKeywords);

        // 筛选项--选中
        $paramsArr = $this->common_model->parseParams($params, 1);
        $sea = $paramsArr['sea'] ? $paramsArr['sea'] : '';
        $gen = $this->common_model->getGenderByRequest($params);
        $ind = $this->common_model->getIndustryByRequest($params);
        $match = $paramsArr['match'] ? $paramsArr['match'] : 1;

        // 筛选项参数--字典
        $seaArr = GetCategory::getSeason('', 'sName');
        $genArr = GetCategory::getGender();
        $indArr = GetCategory::getTrade();

        $filter = []; // 获取键值
        $filter['sea_id'] = $sea ?: '';
        $filter['gen_id'] = $gen ?: '';
        $filter['ind_id'] = $ind ?: '';
        $filter['match_id'] = $match ?: 1;
        $filter['sea'] = in_array($sea, array_keys($seaArr)) ? $seaArr[$sea] : '全部';
        $filter['gen'] = in_array($gen, array_keys($genArr)) ? $genArr[$gen] : '全部';
        $filter['ind'] = in_array($ind, array_keys($indArr)) ? $indArr[$ind] : '全部';
        $filter['match'] = $matchs && in_array($match, array_keys($matchs)) ? $matchs[$match] : '最新';
        $filter['column_name'] = in_array($columnId, array_keys(self::$columnDict)) ? self::$columnDict[$columnId] : '全部';
        $columnArr = ['all' => '全部', 4 => '款式', 1 => '趋势解读', 2 => '流行分析', 82 => '图案', 3 => 'T台', 71 => '广告大片'];

        $this->assign('filter', array_filter($filter));// 筛选选中项
        $this->assign('column_id', $columnId);// 栏目id
        $this->assign('tips', $tips); // 全部 行业/性别
        $this->assign('iSeason', $iSeason); // 全部季节
        $this->assign('columnArr', $columnArr);// tab全部栏目
        $this->assign('patternColumnArr', [82 => '图案素材', 120 => '大牌花型']);
        echo $this->fetch('lists/search_select_items.html');
        die;
    }

    // 【全部】显示的数据 以及 查看更多...
    private function getAllData($params, $column = '', $is_json = false)
    {
        if ($is_json) {
            $lists = $this->search_model->getAllJsonData($params, $column);// 异步渲染
            $list_keys = array_keys($lists);
        } else {
            $lists = $this->search_model->getAllShowData($params);// 同步渲染
            $list_keys = array_keys($lists);
            array_shift($list_keys); // 删除 have_json_data(key)
        }
        if (!empty($lists)) {
            foreach ($list_keys as $col) {
                list($moreUri, $keyword) = $this->search_model->getMoreColumnUri($params);
                if ($moreUri) {
                    $lists[$col]['more_url'] = self::$rootUrl . "col_{$col}-" . $moreUri . "/" . $keyword;
                } else {
                    $lists[$col]['more_url'] = self::$rootUrl . "col_{$col}/" . $keyword;
                }
            }
        }
        return !empty($lists) ? $lists : [];
    }

    /**
     * @json获取全部栏目的数据
     * 异步渲染
     */
    public function getSearchAll()
    {
        $columnId = $this->input->post('col');
        $params = $this->input->post('params');
        $column = $this->input->post('column');// 82,3,71
        $paramsArr = $this->common_model->parseParams($params, 1);
        if (!isset($columnId) && empty($paramsArr['col'])) {
            $columnId = 'all';
        }

        $jsonOutput = getJsonInstance();
        if ($this->input->is_ajax_request()) {
            if ($columnId != 'all') {
                $jsonOutput->code(1)->msg('错误请求')->out();
                return;
            }
            $lists = $this->getAllData($params, $column, true);
            if (!empty($lists)) {
                $jsonOutput->code(0)->data($lists)->msg('ok')->out();
                return;
            } else {
                $jsonOutput->code(1)->msg('无数据')->out();
                return;
            }
        }
    }

    // 获取要展示具体数据栏目的数量
    private function getShowColumnTotal($params)
    {
        $data = ['all' => 0, 4 => 0, 1 => 0, 2 => 0, 82 => 0, 3 => 0, 71 => 0];
        $columnArr = array_keys($data);
        $showTotalArr = $this->search_model->getShowDataByTotal($params);
        if ($showTotalArr) {
            foreach ($columnArr as $item) {
                $data[$item] = in_array($item, array_keys($showTotalArr)) ? $showTotalArr[$item] : 0;
            }
        }
        return $data;
    }
}