<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Video 视频专栏的控制器
 */
class Video extends POP_Controller
{
    private $columnPid = 10;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['common_model', 'video_model']);
        $this->assign('columnPid', $this->columnPid);
    }

    // 视频专栏列表页
    public function index()
    {
        // 获取广告
        $adDatas = $this->video_model->getAds();
        $columnId = 10;

        // 3-发布会--季节切换是异步
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            $reg = $this->input->get_post('reg');// 地区时装周
            $titbit = $this->input->get_post('titbit');// 花絮
            $params = array_filter(compact('reg', 'titbit'));
            $this->video_model->ajaxList(3, $params);
            return;
        }
        // 权限
        $powers = $this->common_model->getPowers($this->columnPid, '', $columnId);

        // 同步
        $lists = $info = $params_arr = [];
        list($lists[3], $info[3]) = $this->video_model->ajaxList(3, $params_arr, false);
        list($lists[71], $info[71]) = $this->video_model->ajaxList(71, $params_arr, false);
        list($lists[116], $info[116]) = $this->video_model->ajaxList(116, $params_arr, false);

        $tdk = $this->video_model->getTDk();
        $this->assign('title', $tdk['t']);
        $this->assign('keywords', $tdk['k']);
        $this->assign('description', $tdk['d']);
        $this->assign('lists', $lists);
        $this->assign('info', $info);
        $this->assign('powers', $powers);
        $this->assign('adDatas', $adDatas);// 广告位
        $this->display('lists/video_list.html');
    }

    // 独立视频--视频专栏二级列表页
    public function inside($params = '')
    {
        $this->benchmark->mark('action');
        $columnId = 10;
        $params = $this->common_model->restoreParams($params);
        $paramsArr = $this->common_model->parseParams($params, 1, false);

        // 权限
        $powers = $this->common_model->getPowers($this->columnPid, $params, $columnId);
        $userType = $powers['userType'];

        // 获取数据
        if (isset($paramsArr['id'])) {
            $id = intval($paramsArr['id']);
            $videoArr = $this->video_model->getInsideYouKuVideoOneData($id);
            $data = $videoArr[$id];
            // 找不到内容时，跳404
            if (empty($data)) {
                show_404();
                exit;
            }
            // 其他发布会推荐
            $videoRecommend = $this->video_model->getOtherRecommendData($data);
            // 渲染数据
            $filterData = [];
            $filterData['iSeason'] = $this->video_model->getRelevantSeason($data);
            // 选中数据
            $filter = [];
            $filter['seasonId'] = $data['season'];
            if ($data['video_type'] == 1) {
                $filter['iRegion'] = $data['region'];
            }

            // -- 中间页 ---------------------------------------------------------
            //顶部广告
            $aTopAds = $this->common_model->getAds($columnId, 8, 1, 3);
            //侧边广告
            $aBroadsideAd = $this->common_model->getAds($columnId, 9, 2, 3);
            $this->assign('aTopAds', $aTopAds);
            $this->assign('aBroadsideAd', $aBroadsideAd);

            // 标签
            $tags = [
                'brandName' => $data['brand_name'],
                'brandLink' => 'javascript:void(0);',
                'colName' => GetCategory::getOtherFromColId($columnId, 'sName'),
                'colLink' => $this->common_model->getLink($columnId),
            ];
            if ($data['gender']) {
                $tags['genInfo'][] = [
                    'sName' => $data['genderName'],
                    'link' => 'javascript:void(0);',
                ];
            }
            $this->assign('middlePageTags', $tags);

            // -- 权限判定 ---------------------------------------------------------
            $sGenderIDs = $data['gender'];
            //查看列表权限
            $powerArr = memberPower('other');

            $power_user_bool = false;
            if ($data['video_type'] == 1 && !empty($data['pop_id'])) {
                // 1-发布会就到二级列表页，图片有水印，点击出提示
                $power_user_bool = $powerArr['P_UserType'] == 5 ? true : false;
            } elseif ($data['video_type'] == 2 && !empty($data['pop_id'])) {
                // 2-广告大片到中间页
                $power_user_bool = ($powerArr['P_UserType'] == 5 || ($powerArr['P_UserType'] == 4)) ? true : false;
            }
            if (empty($data['pop_id'])) {
                $power_user_bool = ($powerArr['P_UserType'] == 5 || ($powerArr['P_UserType'] == 4)) ? true : false;
            }
            if ($power_user_bool) {
                $this->assign('title', $data['title'] . '-POP趋势资讯网');
                $this->assign('keywords', $data['title']);
                $this->assign('description', $data['title']);
                $this->assign("P_UserType", $powerArr['P_UserType']);
                $this->assign("is_video", true);
                $this->assign('column', $columnId);
                $this->assign("cover", $data['video_cover']);
                $this->assign('data', $data);
                $this->assign('tit', $data['title']);
                $this->display('mid_tourist.html');
                die;
            }
            //查看详情权限
            $detailPower = memberPower('detail',
                array('P_Gender' => $sGenderIDs, 'P_Column' => $columnId)
            );
            if (!$detailPower['P_Visit'] && $powerArr['P_UserType'] != 4) {
                $genVal = GetCategory::getOtherFromIds($sGenderIDs, array('sName'), 'string');
                $pColVal = GetCategory::getOtherFromColId($this->columnPid, 'sName');
                $sColVal = GetCategory::getOtherFromColId($columnId, 'sName');
                $this->assign('sGender', $genVal);
                $this->assign('columnP', $pColVal);
                $this->assign('columnC', $sColVal);

                $this->assign('title', $data['title'] . '-POP趋势资讯网');
                $this->assign('keywords', $data['title']);
                $this->assign('description', $data['title']);
                $this->assign("partVip", true);
                $this->assign("P_UserType", $powerArr['P_UserType']);
                $this->assign("cover", $data['video_cover']);
                $this->assign("is_video", true);
                $this->assign('tit', $data['title']);
                $this->assign('column', $columnId);
                $this->assign('data', $data);
                $this->display('mid_tourist.html');
                die;
            }
        }

        //收藏判断收藏状态  1已收藏 -1无收藏权限 0未收藏
        $bIsCollect = 0;
        if ($powers['P_Collect']) {
            $this->load->model('collect_model');
            $aLogonMessage = get_cookie_value();
            $whetherCollect = $this->collect_model->existCollectStatus(
                $aLogonMessage['sChildID'],
                OpPopFashionMerger::POP_FASHION_DATABASE_NAME,
                OpPopFashionMerger::POP_Table_Name_Product_Video, $data['id']
            );
            if ($whetherCollect) {
                $bIsCollect = 1;
            }
        } else {
            $bIsCollect = -1;
        }

        $this->assign('title', $data['title'] . '-POP服装趋势网');
        $this->assign('keywords', $data['title']);
        $this->assign('bIsCollect', $bIsCollect);
        $this->assign('data', $data);
        $this->assign('t', 'video');
        $this->assign('id', $id);
        $this->assign('filterData', $filterData);
        $this->assign('filter', $filter);
        $this->assign('columnId', $columnId);
        $this->assign("P_UserType", $powerArr['P_UserType']);
        $this->assign('videoRecommend', $videoRecommend);
        $this->assign('userType', $userType);
        // 模板中区分是否是书籍
        $this->assign('isBook', false);
        $this->display('lists/video_list_inside.html');
    }

    // 获取广告大片/发布会的二级列表页或者独立视频的详情的url
    public function getInsideUrl()
    {
        if ($this->input->is_ajax_request() || $_GET['isAjax']) {
            // 栏目
            $col = $this->input->get_post('col');
            // 季节
            $sea = $this->input->get_post('sea');
            // 品牌
            $bra = $this->input->get_post('bra');
            if (empty($sea) || empty($bra)) {
                getJsonInstance()->code(1)->msg('参数缺失')->out();
            }

            $compact = array_filter(compact('bra', 'sea', 'col'));
            $inside_url = $this->video_model->getVideoInsideUrl($compact);
            if (!empty($inside_url)) {
                getJsonInstance()->code(0)->data(['url' => $inside_url])->msg('ok')->out();
            } else {
                getJsonInstance()->code(1)->msg('无数据')->out();
            }
        }
    }
}