<?php

/**
 * 个人中心-活动管理控制器
 * Date: 2016/11/19
 */
class Activity extends POP_Controller
{
    public $useInfo = [];

    public function __construct()
    {
        parent::__construct();

        $this->useInfo = get_cookie_value();

        $this->load->model('activity_model');
    }

    //活动管理入口
    public function index()
    {
        //指定有哪些用户是可以管理活动
        $privilege = [
            '106645', '1262414'
        ];

        //验证用户是否登录，是否有权限操作管理活动
        $power = memberPower('other');
        if (!in_array($power['P_UserType'], [1, 2]) || !in_array($this->useInfo['id'], $privilege)) {//子账号或主账号可以操作
            echo '对不起，您没有权限操作';
            die;
        }

        if ($power['P_UserType'] == 1) {//主账号
            $sign = md5(POP_GLOBAL_KEYS . '_' . $this->useInfo['id'] . date('Y-m-d H:00:00'));//url签名
        } elseif ($power['P_UserType'] == 2) {//子账号
            $sign = md5(POP_GLOBAL_KEYS . '_' . $this->useInfo['id'] . '_' . $this->useInfo['sChildID'] . date('Y-m-d H:00:00'));//url签名
        }

        $url = '/activity/actentry/?sign=' . $sign . '&time=' . strtotime(date('Y-m-d H:00:00'));

        header('Location:' . $url);
    }

    public function actEntry()
    {
        $getSign = $this->input->get("sign");
        $getTime = $this->input->get("time");

        $power = memberPower('other');
        if ($power['P_UserType'] == 1) {//主账号
            $memcacheKey = OpPopFashionMerger::POP_FASHION_MERGER_MEMCACHE_KEY_PREFIX . '_activity_management_privilege_' . $this->useInfo['id'];
            $sign = md5(POP_GLOBAL_KEYS . '_' . $this->useInfo['id'] . date('Y-m-d H:00:00'));//url签名 用于验证url真伪

            $memcacheSign = md5(POP_GLOBAL_KEYS . '_' . $this->useInfo['id']);
        } elseif ($power['P_UserType'] == 2) {//子账号
            $memcacheKey = OpPopFashionMerger::POP_FASHION_MERGER_MEMCACHE_KEY_PREFIX . '_activity_management_privilege_' . $this->useInfo['id'] . '_' . $this->useInfo['sChildID'];
            $sign = md5(POP_GLOBAL_KEYS . '_' . $this->useInfo['id'] . '_' . $this->useInfo['sChildID'] . date('Y-m-d H:00:00'));//url签名 用于验证url真伪

            $memcacheSign = md5(POP_GLOBAL_KEYS . '_' . $this->useInfo['id'] . '_' . $this->useInfo['sChildID']);
        }

        if ($getTime == strtotime(date("Y-m-d H:00:00")) && $sign == $getSign) {
            $this->load->driver('cache');
            $expir = strtotime(date("Y-m-d 23:59:59")) - time();
            $this->cache->memcached->save($memcacheKey, $memcacheSign, $expir);
        } else {
            echo '签名已经过期,请重新获取管理入口链接';
            die;
        }

        header("Location:/activity/manage/msgtyp_1/");
    }


//--------------------------------------对接会前后台 begin------------------------------------
    public function publish($params = '')
    {
        if (!$this->checkSign()) die;
        $col = '';
        //判断时尚教育/商贸对接
        if ($params) {
            $arr = explode('-', $params);
            if (in_array('msgtyp_1', $arr)) {
                $col = 1;
            } elseif (in_array('msgtyp_2', $arr)) {
                $col = 2;
            }
        }

        $this->assign('col', $col);//栏目
        //活动类型
        $activityTypes = $this->activity_model->getActivityDictByiType(3, 16, false, $col);
        //线上活动子类型
        $activityChildTypes = $this->activity_model->getActivityDictByiType(3, 17, false, $col);

        $this->assign('activityTypes', $activityTypes);
        $this->assign('activityChildTypes', $activityChildTypes);

        //活动默认开始时间
        $defaultActBeginDate = date("Y-m-d");
        $this->assign('defaultActBeginDate', $defaultActBeginDate);

        //活动类型，用于控制显示地图还是网址
        $online = true;
        $offline = false;
        $this->assign('online', $online);
        $this->assign('offline', $offline);

        //默认活动类型
        $defaultActType = array_shift($activityTypes);
        $this->assign('defaultActType', $defaultActType);

        //是否官方
        $this->assign('isOfficial', true);

        //默认活动子类型
        $defaultActChildType = array_shift($activityChildTypes);
        $this->assign('defaultActChildType', $defaultActChildType);
        // var_dump($this->activity_model->db->last_query());
        $this->display('activity/activity_editor.html');
    }

    public function opSave($op)
    {
        if (!$this->input->is_ajax_request()) {
            echo '非法进入';
            die;
        }
        if (!$this->checkSign()) die;

        try {
            $data = $condition = [];
            $useInfo = $this->useInfo;
            $condition['iAccountId'] = $data['iAccountId'] = intval($useInfo['id']);
            if (isset($useInfo['sChildID']) && !empty($useInfo['sChildID'])) {
                $condition['sChildAccountId'] = $data['sChildAccountId'] = $useInfo['sChildID'];
            }
            $post = array_map("htmlspecialchars", $this->input->post());
            $data['sTheme'] = trim($post['activityTheme']);//活动主题
            $data['sPosterUrl'] = trim($post['activityPoster']);//活动海报

            $data['dActivityStartTime'] = trim($post['activityBeginDate'] . ' ' . $post['activityBeginTime']);//活动开始时间
            $data['dActivityEndTime'] = trim($post['activityEndDate'] . ' ' . $post['activityEndTime']);//截止时间
            $col = intval($this->input->get_post('col', true));
            $data['iActivityMessageType'] = $col;//活动消息类型

            //活动类型
            $activityType = intval($post['activityType']);
            $data['iActivityType'] = $activityType;
            if ($activityType == 17) {//活动类型为线上活动时，过滤
                $data['sActivityURL'] = trim($post['activityUrl']);//活动网址

                //重置线下字段
                $data['iActivityArea'] = 0;//活动地点
                $data['iActivityCity'] = 0;
                $data['sActivityDetailAddress'] = '';
                $data['fLongitude'] = 0.000000;//经度
                $data['fLatitude'] = 0.000000;//纬度
                $data['sMapAddress'] = '';//地图地址
            }
            if ($activityType == 18) {//活动类型为线下活动时，过滤
                //重置线上字段
                $data['sActivityURL'] = '';//活动网址

                $activityArea = intval($post['activityArea']);
                $data['iActivityArea'] = $activityArea;//活动地点
                $data['iActivityCity'] = intval($post['activityCity']);
                $data['sActivityDetailAddress'] = trim($post['activityAddress']);
                if ($activityArea == 2) {// 国外
                    $data['fLongitude'] = 0.000000;//经度
                    $data['fLatitude'] = 0.000000;//纬度
                    $data['sMapAddress'] = '';//地图地址
                } else {// 国内
                    $data['fLongitude'] = $post['fLng'] ? number_format($post['fLng'], 10, '.', '') : 0.000000;//经度
                    $data['fLatitude'] = $post['fLat'] ? number_format($post['fLat'], 10, '.', '') : 0.000000;//纬度
                    $data['sMapAddress'] = trim($post['activityAddrMap']);//地图地址
                }
            }

            $data['iActivityChildType'] = intval($post['activityChildType']);
            $data['sActivityContent'] = trim($post['activityDetails']);

            $data['sSponsor'] = trim($post['sponsor']);
            $data['iOfficial'] = intval($post['official']);//是否官方
            $data['sContactsName'] = $post['contactName'];
            $data['sContactsPhone'] = $post['contactPhone'];

            $url = '';
            switch ($op) {
                //保存并预览
                case 'preview':
                    $url = '/activity/detail/';
                    $data['iCheckStatus'] = 2;//审核状态（1-审核、2-未审核（默认）、3-审核未通过）
                    $data['dCheckTime'] = null;
                    $data['iPublishStatus'] = 2;//发布状态( 2-不发布（默认）、1-发布)
                    $data['dPublishTime'] = null;
                    break;
                //保存不发布
                case 'notrelease':
                    $url = '';
                    $data['iCheckStatus'] = 2;//审核状态（1-审核、2-未审核（默认）、3-审核未通过）
                    $data['dCheckTime'] = null;
                    $data['iPublishStatus'] = 2;//发布状态( 2-不发布（默认）、1-发布)
                    $data['dPublishTime'] = null;
                    break;
                //保存并发布
                case 'release':
                    $data['iCheckStatus'] = 1;//审核状态（1-审核、2-未审核（默认）、3-审核未通过）
                    $data['dCheckTime'] = date("Y-m-d H:i:s");
                    $data['iPublishStatus'] = 1;//发布状态( 2-不发布（默认）、1-发布)
                    $data['dPublishTime'] = date("Y-m-d H:i:s");
                    break;
                default:
                    break;
            }

            //查询数据表中是否存在该活动，如果存在说明是修改操作
            $activityId = isset($post['activityId']) && !empty($post['activityId']) ? intval($post['activityId']) : 0;
            $activityInfo = $this->activity_model->getOneActivityById($activityId, $condition);

            if ($activityInfo) {//更新
                $condition['id'] = $activityId;

                if ($activityInfo['iPublishStatus'] == 1) {
                    unset($data['dPublishTime'], $data['dCheckTime']);
                }

                $result = $this->activity_model->updActivity($data, $condition);
            } else {//新增
                $data['dCreateTime'] = date("Y-m-d H:i:s");
                $result = $activityId = $this->activity_model->addActivity($data);
            }

            if ($url) {
                $sign = md5($activityId . '_preview_' . POP_GLOBAL_KEYS . '_' . date("Y-m-d"));//预览页面的签名  $activityId.'_preview_'.POP_GLOBAL_KEYS.'_'.date("Y-m-d")
                $url = $url . '?sign=' . $sign . '&id=' . $activityId;
            }
            if ($result) {
                $res = ['status' => 'success', 'url' => $url];
                //更新solr数据
                $this->activity_model->updateSolrByActId($activityId);
            } else {
                $res = ['status' => '更新失败'];
            }
        } catch (Exception $e) {
            echo 'Caught exception: ', $e->getMessage(), "\n";
        }


        echo json_encode($res);
        die;
    }

    public function editor($params = '')
    {
        if (!$this->checkSign()) die;
        if ($params) {
            $paramsArr = explode('-', $params);
            $activityId = $paramsArr[0];
            $col = $paramsArr[1];
            $this->assign('col', $col);
        }
        $activityInfo = $this->activity_model->getOneActivityById($activityId);

        //已发布的不能再修改
        // if ($activityInfo['iPublishStatus'] == 1) {
        //     header("location:/activity/manage/");
        // }
        $this->assign('activityId', $activityId);
        //活动主题
        $activityTheme = $activityInfo['sTheme'];
        $this->assign('activityTheme', $activityTheme);

        //活动海报Url
        $activityPoster = $activityInfo['sPosterUrl'];
        $this->assign('activityPoster', $activityPoster);

        //开始时间
        $defaultActBeginDate = date("Y-m-d", strtotime($activityInfo['dActivityStartTime']));
        $actBeginTime = date("H:i", strtotime($activityInfo['dActivityStartTime']));
        $this->assign('defaultActBeginDate', $defaultActBeginDate);
        $this->assign('actBeginTime', $actBeginTime);

        //结束时间
        $defaultActEndDate = date("Y-m-d", strtotime($activityInfo['dActivityEndTime']));
        $actEndTime = date("H:i", strtotime($activityInfo['dActivityEndTime']));
        $this->assign('defaultActEndDate', $defaultActEndDate);
        $this->assign('actEndTime', $actEndTime);

        //活动类型
        $activityTypes = $this->activity_model->getActivityDictByiType(3, 16, false);
        $this->assign('activityTypes', $activityTypes);
        //线上活动子类型
        $activityChildTypes = $this->activity_model->getActivityDictByiType(3, 17, false);
        $this->assign('activityChildTypes', $activityChildTypes);

        //活动类型，用于控制显示地图还是网址
        $actType = intval($activityInfo['iActivityType']);
        $online = $actType == 17 ? true : false;
        $this->assign('online', $online);
        $offline = $actType == 18 ? true : false;
        $this->assign('offline', $offline);

        //默认活动类型
        $defaultActType['id'] = $actType;
        $defaultActType['sName'] = $this->activity_model->getOtherByIds($actType);
        $this->assign('defaultActType', $defaultActType);

        //活动子类型
        $activityChildTypes = $this->activity_model->getActivityDictByiType(3, $actType, false, $col);
        $this->assign('activityChildTypes', $activityChildTypes);
        //默认活动子类型
        $actChildType = intval($activityInfo['iActivityChildType']);
        $defaultActChildType['id'] = $actChildType;
        $defaultActChildType['sName'] = $this->activity_model->getOtherByIds($actChildType);
        $this->assign('defaultActChildType', $defaultActChildType);

        //活动区域
        $activityArea = $this->activity_model->getActivityDictByiType(1, 0, false);
        $this->assign('activityArea', $activityArea);

        //默认区域
        $actArea = intval($activityInfo['iActivityArea']);
        $defaultActArea['id'] = $actArea;
        $defaultActArea['sName'] = $this->activity_model->getOtherByIds($actArea);
        $this->assign('defaultActArea', $defaultActArea);

        //活动城市
        $activityCity = $this->activity_model->getActivityDictByiType(1, $actArea, false);
        $this->assign('activityCity', $activityCity);

        //默认城市
        $actCity = intval($activityInfo['iActivityCity']);
        $defaultActCity['id'] = $actCity;
        $defaultActCity['sName'] = $this->activity_model->getOtherByIds($actCity);
        $this->assign('defaultActCity', $defaultActCity);

        //活动详细地址
        $actAddrDetails = $activityInfo['sActivityDetailAddress'];
        $this->assign('actAddrDetails', $actAddrDetails);

        //经度
        $fLongitude = $activityInfo['fLongitude'];
        $this->assign('fLongitude', $fLongitude);
        //纬度
        $fLatitude = $activityInfo['fLatitude'];
        $this->assign('fLatitude', $fLatitude);

        //线上活动网址
        $activityWebUrl = $activityInfo['sActivityURL'];
        $this->assign('activityWebUrl', $activityWebUrl);

        //活动详情
        $activityContent = $activityInfo['sActivityContent'];
        $this->assign('activityContent', $activityContent);

        //是否官方
        $isOfficial = ($activityInfo['iOfficial'] == 1) ? true : false;
        $this->assign('isOfficial', $isOfficial);

        //主办方
        $sponsor = $activityInfo['sSponsor'];
        $this->assign('sponsor', $sponsor);
        //主办方联系人
        $contactsName = $activityInfo['sContactsName'];
        $this->assign('contactsName', $contactsName);
        //主办方联系方式
        $contactsPhone = $activityInfo['sContactsPhone'];
        $this->assign('contactsPhone', $contactsPhone);

        $this->display('activity/activity_editor.html');
    }

    public function detail()
    {
        $id = intval($this->input->get('id'));
        if (!$id) {
            header("Location:/error/");
            exit;
        }
        $info = $this->activity_model->getOneActivityById($id);
        if (!$info) {
            header("Location:/error/");
            exit;
        }
        $info = $this->activity_model->reHandleInfo($info);
        // 推荐列表
        $recLists = [];
        $totalCount = $this->activity_model->getRecLists($info, $recLists);

        // 侧边广告(不限制数量)
        $leftAds = $this->common_model->getAds(0, 12, '', '', true);
        $this->assign('info', $info);
        $this->assign('recLists', $recLists);
        $this->assign('totalCount', $totalCount);
        $this->assign('leftAds', $leftAds);

        $msgType = $info['iActivityMessageType'];
        $colName = [
            1 => '时尚教育',
            2 => '商贸对接',
            3 => '精英联盟'
        ];

        $title = $colName[$msgType] . '-POP服装趋势网';
        $keywords = $colName[$msgType];
        $description = $info['iActivityChildTypeName'] . '/' . $info['sSponsor'] . '/' . $info['dStartTime'] . '起' . $info['dEndTime'] . '止' . '/' . $info['region'];

        //2018-6-20 新TDK
        $this->assign('title', $title);
        $this->assign('keywords', $keywords);
        $this->assign('description', trim($description, '/'));

        $this->display('activity/activity_detail.html');
    }

    public function manage($params = '')
    {
        //验证memcache中签名是否有效,无效则需重新获取入口链接，并更新缓存
        if (!$this->checkSign()) die;

        $this->load->model('common_model');
        $params = $this->common_model->restoreParams($params);

        // 参数数组
        $paramsArr = $this->common_model->parseParams($params, 1);

        //判断时尚教育/商贸对接
        if ($params) {
            $arr = explode('-', $params);
            if (in_array('msgtyp_1', $arr)) {
                $paramsArr['msgtyp'] = 1;
            } elseif (in_array('msgtyp_2', $arr)) {
                $paramsArr['msgtyp'] = 2;
            } else {
                $paramsArr['msgtyp'] = '';
            }
        }

        $col = '';
        if ($paramsArr['msgtyp']) {
            $col = intval($paramsArr['msgtyp']) == 1 ? '1' : '2';
        }

        //活动类型
        $activityTypes = $this->activity_model->getActivityDictByiType(3, 16, false, $col);
        // 地区(国内，国外)
        $activityArea = $this->activity_model->getActivityDictByiType(1, 0, false);
        // 用户信息，只能管理自己的记录
        $useInfo = $this->useInfo;
        $conditions['iAccountId'] = $useInfo['id'];
        if (isset($useInfo['sChildID'])) {
            $conditions['sChildAccountId'] = $useInfo['sChildID'];
        }
        $conditions['iActivityMessageType'] = $col;
        // 拼接搜索条件
        // 主题
        $sTheme = $this->input->get('key', true);
        if ($sTheme) {
            $conditions['sTheme'] = '*' . $sTheme . '*';
            $this->assign('sTheme', $sTheme);
        }
        // 活动区域
        if (isset($paramsArr['are'])) {
            $iActivityArea = intval($paramsArr['are']);
            $conditions['iActivityArea'] = $iActivityArea;
            $this->assign('iActivityArea', $iActivityArea);
        }
        // 活动城市
        if (isset($paramsArr['cit'])) {
            $iActivityCity = intval($paramsArr['cit']);
            $conditions['iActivityCity'] = $iActivityCity;
            $this->assign('iActivityCity', $iActivityCity);
        }
        // 活动类型
        if (isset($paramsArr['typ'])) {
            $iActivityType = intval($paramsArr['typ']);
            $conditions['iActivityType'] = $iActivityType;
            $this->assign('iActivityType', $iActivityType);
        }
        // 活动子类型
        if (isset($paramsArr['chi'])) {
            $iActivityChildType = intval($paramsArr['chi']);
            $conditions['iActivityChildType'] = $iActivityChildType;
            $this->assign('iActivityChildType', $iActivityChildType);
        }
        // 发布开始时间
        if (isset($paramsArr['beg'])) {
            $beginTime = $this->common_model->strReplace($paramsArr['beg']);
            $beginTimeSolr = date('Y-m-d\T00:00:00\Z', strtotime($beginTime));
            $this->assign('beginTime', $beginTime);
        } else {
            $beginTimeSolr = '*';
        }
        // 发布结束时间
        if (isset($paramsArr['end'])) {
            $endTime = $this->common_model->strReplace($paramsArr['end']);
            if ($endTime == $beginTime) {
                $endTimeSolr = date('Y-m-d\T23:59:59\Z', strtotime($endTime));
            } else {
                $endTimeSolr = date('Y-m-d\T00:00:00\Z', strtotime($endTime));
            }
            $this->assign('endTime', $endTime);
        } else {
            $endTimeSolr = '*';
        }
        // 拼接发布时间
        if (($beginTime && trim($beginTime) != '请选择开始时间') || ($endTime && trim($endTime) != '请选择结束时间')) {
            $conditions['dPublishTime'] = '[' . $beginTimeSolr . ' TO ' . $endTimeSolr . ']';
        }
        // 是否审核
        if (isset($paramsArr['che'])) {
            $iCheckStatus = intval($paramsArr['che']);
            $conditions['iCheckStatus'] = $iCheckStatus;
            $this->assign('iCheckStatus', $iCheckStatus);
        }
        // 是否官方
        if (isset($paramsArr['off'])) {
            $iOfficial = intval($paramsArr['off']);
            $conditions['iOfficial'] = $iOfficial;
            $this->assign('iOfficial', $iOfficial);
        }
        // 每页展示数据
        $rootUrl = '/activity/manage/';
        $pageSize = 5;
        $page = $this->common_model->getPage($paramsArr); //当前页
        $offset = ($page - 1) * $pageSize;// 偏移量
        $lists = [];
        $totalCount = $this->activity_model->getManageLists($conditions, $lists, $offset, $pageSize);
        // 生成页码
        $pageHtml = $this->makePageHtml($paramsArr, $totalCount, $pageSize, $page, $rootUrl);

        $this->assign('col', $col);
        $this->assign('activityTypes', $activityTypes);
        $this->assign('activityArea', $activityArea);
        $this->assign('totalCount', $totalCount);
        $this->assign('lists', $lists);
        $this->assign('pageHtml', $pageHtml);
        $this->display('activity/activity_management.html');
    }

//--------------------------------------对接会前后台 end------------------------------------


//--------------------------------------联盟汇前后台 begin------------------------------------

    public function allianceManage($params = '')
    {
        //验证memcache中签名是否有效,无效则需重新获取入口链接，并更新缓存
        if (!$this->checkSign()) die;

        $this->load->model('common_model');
        $params = $this->common_model->restoreParams($params);
        // 参数数组
        $paramsArr = $this->common_model->parseParams($params, 1);
        //公司类型
        $companyTypes = $this->activity_model->getActivityDictByiType(4, 24, false);
        $this->assign('companyTypes', $companyTypes);
        // 地区(国内，国外)
        $activityArea = $this->activity_model->getActivityDictByiType(1, 0, false);
        $this->assign('activityArea', $activityArea);

        // 用户信息，只能管理自己的记录
        $useInfo = $this->useInfo;
        $conditions['iAccountId'] = $useInfo['id'];
        if (isset($useInfo['sChildID'])) {
            $conditions['sChildAccountId'] = $useInfo['sChildID'];
        }
        $conditions['iActivityMessageType'] = 3;
        // 拼接搜索条件
        // 主题
        $sTheme = trim($this->input->get('key', true));
        if ($sTheme) {
            $conditions['sTheme'] = '*' . $sTheme . '*';
            $this->assign('sTheme', $sTheme);
        }
        // 区域
        if (isset($paramsArr['are'])) {
            $iActivityArea = intval($paramsArr['are']);
            $conditions['iActivityArea'] = $iActivityArea;
            $this->assign('iActivityArea', $iActivityArea);
        }
        // 城市
        if (isset($paramsArr['cit'])) {
            $iActivityCity = intval($paramsArr['cit']);
            $conditions['iActivityCity'] = $iActivityCity;
            $this->assign('iActivityCity', $iActivityCity);
        }
        // 类型
        if (isset($paramsArr['typ'])) {
            $iActivityType = intval($paramsArr['typ']);
            $conditions['iCompanyType'] = $iActivityType;
            $this->assign('iCompanyType', $iActivityType);
        }
        // 联系人
        if (isset($paramsArr['ctn'])) {
            $sContactsName = trim($paramsArr['ctn']);
            $conditions['sContactsName'] = '*' . $sContactsName . '*';
            $this->assign('sContactsName', $sContactsName);
        }
        // 联系方式
        if (isset($paramsArr['ctp'])) {
            $sContactsPhone = trim($paramsArr['ctp']);
            $conditions['sContactsPhone'] = '*' . $sContactsPhone . '*';
            $this->assign('sContactsPhone', $sContactsPhone);
        }

        // 每页展示数据
        $rootUrl = '/activity/alliancemanage/';
        $pageSize = 5;
        $page = $this->common_model->getPage($paramsArr); //当前页
        $offset = ($page - 1) * $pageSize;// 偏移量
        $lists = [];
        $totalCount = $this->activity_model->getManageLists($conditions, $lists, $offset, $pageSize);
        // 生成页码
        $pageHtml = $this->makePageHtml($paramsArr, $totalCount, $pageSize, $page, $rootUrl);

        $this->assign('totalCount', $totalCount);
        $this->assign('lists', $lists);
        $this->assign('pageHtml', $pageHtml);
        $this->display('activity/alliance_management.html');
    }

    public function allianceAdd()
    {
        if (!$this->checkSign()) die;
        //公司类型
        $companyTypes = $this->activity_model->getActivityDictByiType(4, 24, false);
        $this->assign('companyTypes', $companyTypes);
        $defaultCompanyType = array_shift($companyTypes);
        $this->assign('defaultCompanyType', $defaultCompanyType);

        //活动区域
        $activityArea = $this->activity_model->getActivityDictByiType(1, 0, false);
        $this->assign('activityArea', $activityArea);

        //默认区域
        $actArea = 1;
        $defaultActArea['id'] = $actArea;
        $defaultActArea['sName'] = $this->activity_model->getOtherByIds($actArea);
        $this->assign('defaultActArea', $defaultActArea);

        //活动城市
        $activityCity = $this->activity_model->getActivityDictByiType(1, $actArea, false);
        $this->assign('activityCity', $activityCity);

        //默认城市
        $actCity = 3;
        $defaultActCity['id'] = $actCity;
        $defaultActCity['sName'] = $this->activity_model->getOtherByIds($actCity);
        $this->assign('defaultActCity', $defaultActCity);

        $this->display('activity/alliance_editor.html');
    }

    public function allianceOp($op = '')
    {
        if (!$this->input->is_ajax_request()) {
            echo '非法进入';
            die;
        }
        if (!$this->checkSign()) die;

        $data = $condition = [];
        $useInfo = $this->useInfo;
        $condition['iAccountId'] = $data['iAccountId'] = intval($useInfo['id']);
        if (isset($useInfo['sChildID']) && !empty($useInfo['sChildID'])) {
            $condition['sChildAccountId'] = $data['sChildAccountId'] = $useInfo['sChildID'];
        }

        $post = $this->input->post();
        $data['iActivityMessageType'] = 3;//活动消息类型（1:对接会、2:顾问团、3:联盟汇）

        $data['sTheme'] = trim($post['companyName']);
        $data['sImageFigure'] = trim($post['sImageFigure']);
        $data['sImageEnterprise'] = trim($post['sImageEnterprise']);

        $data['iCompanyType'] = intval($post['companyType']);
        $companyArea = intval($post['companyArea']);
        $data['iActivityArea'] = $companyArea;//活动地点
        $data['iActivityCity'] = intval($post['companyCity']);
        $data['sActivityDetailAddress'] = trim($post['companyAddr']);

        if ($companyArea == 2) {//国外
            $data['fLongitude'] = 0;//经度
            $data['fLatitude'] = 0;//纬度
            $data['sMapAddress'] = '';//地图地址
        }
        if ($companyArea == 1) {//国内
            $data['fLongitude'] = $post['fLng'] ? number_format($post['fLng'], 10, '.', '') : 0;//经度
            $data['fLatitude'] = $post['fLat'] ? number_format($post['fLat'], 10, '.', '') : 0;//纬度
            $data['sMapAddress'] = trim($post['activityAddrMap']);//地图地址
        }

        $data['sContactsName'] = trim($post['contactName']);
        $data['sContactsPhone'] = trim($post['contactPhone']);
        $data['sActivityContent'] = htmlspecialchars(trim($post['companyAbstract']));
        $data['iOfficial'] = intval($post['official']);//是否官方
        $data['sActivityURL'] = trim($post['officialWebUrl']);

        $url = '';
        switch ($op) {
            //保存并预览
            case 'preview':
                $url = '/activity/alliancedetail/';
                $data['iCheckStatus'] = 2;//审核状态（1-审核、2-未审核（默认）、3-审核未通过）
                $data['dCheckTime'] = null;
                $data['iPublishStatus'] = 2;//发布状态( 2-不发布（默认）、1-发布)
                $data['dPublishTime'] = null;
                break;
            //保存不发布
            case 'notrelease':
                $url = '';
                $data['iCheckStatus'] = 2;//审核状态（1-审核、2-未审核（默认）、3-审核未通过）
                $data['dCheckTime'] = null;
                $data['iPublishStatus'] = 2;//发布状态( 2-不发布（默认）、1-发布)
                $data['dPublishTime'] = null;
                break;
            //保存并发布
            case 'release':
                $data['iCheckStatus'] = 1;//审核状态（1-审核、2-未审核（默认）、3-审核未通过）
                $data['dCheckTime'] = date("Y-m-d H:i:s");
                $data['iPublishStatus'] = 1;//发布状态( 2-不发布（默认）、1-发布)
                $data['dPublishTime'] = date("Y-m-d H:i:s");
                break;
            default:
                break;
        }

        //查询数据表中是否存在该活动，如果存在说明是修改操作
        $activityId = isset($post['activityId']) && !empty($post['activityId']) ? intval($post['activityId']) : 0;
        $activityInfo = $this->activity_model->getOneActivityById($activityId, $condition);

        $data = array_map("mysql_escape_string", $data);
        if ($activityInfo) {//更新
            $condition['id'] = $activityId;
            $result = $this->activity_model->updActivity($data, $condition);
        } else {//新增
            $data['dCreateTime'] = date("Y-m-d H:i:s");
            $result = $activityId = $this->activity_model->addActivity($data);
        }

        if ($url) {
            $sign = md5($activityId . '_preview_' . POP_GLOBAL_KEYS . '_' . date("Y-m-d"));//预览页面的签名  $activityId.'_preview_'.POP_GLOBAL_KEYS.'_'.date("Y-m-d")
            $url = $url . '?sign=' . $sign . '&id=' . $activityId;
        }
        if ($result) {
            $res = ['status' => 'success', 'url' => $url];
            //更新solr数据
            $this->activity_model->updateSolrByActId($activityId);
        } else {
            $res = ['status' => '更新失败'];
        }

        echo json_encode($res);
        die;
    }

    public function allianceEditor($activityId)
    {
        if (!$this->checkSign()) die;

        $activityInfo = $this->activity_model->getOneActivityById($activityId);

        //已发布的不能再修改
        // if ($activityInfo['iPublishStatus'] == 1) {
        //    header("location:/activity/alliancemanage/");
        // }
        $this->assign('activityId', $activityId);

        $activityTheme = $activityInfo['sTheme'];
        $this->assign('companyName', $activityTheme);

        $sImageFigure = $activityInfo['sImageFigure'];
        $this->assign('sImageFigure', $sImageFigure);

        $sImageEnterprise = $activityInfo['sImageEnterprise'];
        $this->assign('sImageEnterprise', $sImageEnterprise);

        //类型
        $companyTypes = $this->activity_model->getActivityDictByiType(4, 24, false);
        $this->assign('companyTypes', $companyTypes);

        $defaultCompanyType['id'] = $activityInfo['iCompanyType'];
        $defaultCompanyType['sName'] = $this->activity_model->getOtherByIds($activityInfo['iCompanyType']);
        $this->assign('defaultCompanyType', $defaultCompanyType);

        //活动区域
        $activityArea = $this->activity_model->getActivityDictByiType(1, 0, false);
        $this->assign('activityArea', $activityArea);

        //默认区域
        $actArea = intval($activityInfo['iActivityArea']);
        $defaultActArea['id'] = $actArea;
        $defaultActArea['sName'] = $this->activity_model->getOtherByIds($actArea);
        $this->assign('defaultActArea', $defaultActArea);
        $this->assign('actArea', $actArea);

        //活动城市
        $activityCity = $this->activity_model->getActivityDictByiType(1, $actArea, false);
        $this->assign('activityCity', $activityCity);

        //默认城市
        $actCity = intval($activityInfo['iActivityCity']);
        $defaultActCity['id'] = $actCity;
        $defaultActCity['sName'] = $this->activity_model->getOtherByIds($actCity);
        $this->assign('defaultActCity', $defaultActCity);

        //详细地址
        $actAddrDetails = $activityInfo['sActivityDetailAddress'];
        $this->assign('actAddrDetails', $actAddrDetails);

        $actMapAddr = $activityInfo['sMapAddress'];
        $this->assign('actMapAddr', $actMapAddr);

        //经度
        $fLongitude = $activityInfo['fLongitude'];
        $this->assign('fLongitude', $fLongitude);
        //纬度
        $fLatitude = $activityInfo['fLatitude'];
        $this->assign('fLatitude', $fLatitude);

        //线上活动网址
        $activityWebUrl = $activityInfo['sActivityURL'];
        $this->assign('activityWebUrl', $activityWebUrl);

        //活动详情
        $activityContent = $activityInfo['sActivityContent'];
        $this->assign('activityContent', str_replace('\n', "\n", $activityContent));

        //是否官方
        $isOfficial = ($activityInfo['iOfficial'] == 1) ? true : false;
        $this->assign('isOfficial', $isOfficial);

        //主办方联系人
        $contactsName = $activityInfo['sContactsName'];
        $this->assign('contactsName', $contactsName);
        //主办方联系方式
        $contactsPhone = $activityInfo['sContactsPhone'];
        $this->assign('contactsPhone', $contactsPhone);
        $this->display('activity/alliance_editor.html');
    }

    public function allianceDetail()
    {
        $id = intval($this->input->get('id', true));
        if ($id) {
            $info = $this->activity_model->getOneActivityById($id);
        }
        if (!$info) {
            header("Location:/error/");
            exit;
        }
        $info = $this->activity_model->reHandleInfo($info);
        $info['sActivityContent'] = htmlspecialchars_decode($info['sActivityContent']);
        $info['sActivityContent'] = str_replace('\n', '<br/>', $info['sActivityContent']);
        $this->assign('info', $info);

        if (stripos($info['sActivityContent'], '。') !== false) {
            $length = stripos($info['sActivityContent'], '。');
            $description = substr($info['sActivityContent'], 0, $length);
        } else {
            $description = $info['sActivityContent'];
        }

        $this->assign('title', "精英联盟-POP服装趋势网");
        $this->assign('keywords', "精英联盟");
        $this->assign('description', $description);

        $this->display('activity/alliance_detail.html');
    }


//--------------------------------------联盟汇前后台 end---------------------------------------


    public function delete()
    {
        if (!$this->input->is_ajax_request()) {
            echo '非法进入';
            die;
        }
        if (!$this->checkSign()) die;

        $activityId = $this->input->post('id', true);
        $data = ['iStatus' => -1];
        $condition = ['id' => $activityId];
        $status = $this->activity_model->updActivity($data, $condition);
        if ($status) {
            $res = ['status' => 'success'];
        } else {
            $res = ['status' => 'fail'];
        }
        //更新solr数据
        $pop_ids = 't_industry_activity' . '_' . $activityId;
        $this->activity_model->delSolrByPopIds($pop_ids);

        echo json_encode($res);
        die;
    }

    public function lists($params = '')
    {
        $params = $this->common_model->restoreParams($params);
        // 参数数组
        $paramsArr = $this->common_model->parseParams($params, 1);
        $mesType = isset($paramsArr['mes']) && !empty($paramsArr['mes']) ? intval($paramsArr['mes']) : 1;
        // 活动消息类型(页面暂时写死)
        $iActivityMessageType = $this->activity_model->getActivityDictByiType(2, 12);
        $conditions = $this->activity_model->getConditions($paramsArr, $mesType);
        // 审核通过的
        $conditions['iCheckStatus'] = 1;
        $colName = [
            1 => '时尚教育',
            2 => '商贸对接',
            3 => '精英联盟'
        ];
        $_title = '';
        $_description = '';
        $_keywords = '';
        switch ($mesType) {
            case '1':// 对接会
            case '2':// 顾问团
                // 顶部广告
                $topAds = $this->common_model->getAds(0, 11, 1);
                $this->assign('topAds', $topAds);
                //活动类型（线上，线下）
                $chi = $iActivityChildType = $this->activity_model->getActivityDictByiType(3, 18, true, $mesType);
                // 时间范围
                $tim = $timeRange = [
                    '1' => ['id' => 1, 'sName' => '今天'],
                    '2' => ['id' => 2, 'sName' => '近一周'],
                    '3' => ['id' => 3, 'sName' => '近一个月'],
                    '4' => ['id' => 4, 'sName' => '近半年'],
                    '5' => ['id' => 5, 'sName' => '近一年']
                ];
                $stat = [
                    1 => ['sName' => '即将开始'],
                    2 => ['sName' => '进行中'],
                    3 => ['sName' => '已结束']
                ];
                $this->assign('timeRange', $timeRange);
                $arSort = ['dPublishTime' => 'DESC', 'pri_id' => 'DESC']; //排序-按发布时间倒序
                // if(empty($paramsArr)||(isset($paramsArr['mes'])&&count($paramsArr)==1))

                if (empty($paramsArr) || (!isset($paramsArr['chi']) && !isset($paramsArr['tim']) && !isset($paramsArr['stat']))) {
                    //2018-6-20 新tdk
                    $title = $colName[$mesType] . "_行业资讯-POP服装趋势网";
                    if ($mesType == 1) {
                        $keywords = '时尚总裁班,时尚游学,时尚游轮,时尚短训,时尚讲座';
                        $description = 'POP服装趋势网行业资讯的时尚教育汇集总裁班、游学、游轮、短训和讲座，为设计师提供全方位的培训和教育服务。';

                    } else {
                        $keywords = '订货会,展会,发布会';
                        $description = 'POP服装趋势行业资讯的商贸对接提供最新最完整的订货会和展会资讯。';
                    }
                } else {
                    $key = ['chi', 'tim', 'stat'];
                    foreach ($key as $k => $v) {
                        if (isset($paramsArr[$v])) {
                            $_title .= ${$v}[$paramsArr[$v]]['sName'] . '_';
                            $_description .= ${$v}[$paramsArr[$v]]['sName'] . '/';
                            $_keywords .= ${$v}[$paramsArr[$v]]['sName'] . ',';
                        }
                    }
                    $_keywords = trim($_keywords, ',');
                    $_description = trim($_description, '/');
                    $_title = trim($_title, '_');
                    if ($mesType == 1) {
                        $title = $_title . '_时尚教育-POP服装趋势网';
                        $keywords = $_keywords . ',时尚教育';
                        $description = 'POP服装趋势网不仅有时尚的教育培训，此外还为您显现' . $_description . '全面、完整的资讯内容。';
                    } else {
                        $title = $_title . '_商贸对接-POP服装趋势网';
                        $keywords = $_keywords . ',商贸对接';
                        $description = 'POP服装趋势网不仅有最全面的商贸对接信息，此外还为您显现' . $_description . '全面、完整的资讯内容。';
                    }
                }
                break;

            case '3':// 联盟汇
                $topAds = $this->common_model->getAds(0, 11, 1);
                $this->assign('topAds', $topAds);
                //分类
                $cpt = $iActivityChildType = $this->activity_model->getActivityDictByiType(4, 24, false);
                //城市
                $cit = $activityCity = $this->activity_model->getActivityDictByiType(1, 1);
                $this->assign("activityCity", $activityCity);
                $arSort = ['dPublishTime' => 'DESC', 'pri_id' => 'DESC']; //排序-按发布时间倒序
                if (empty($paramsArr) || (!isset($paramsArr['cpt']) && !isset($paramsArr['cit']))) {
                    $title = $colName[$mesType] . '_行业资讯-POP服装趋势网';
                    $keywords = '供应商,独立设计师,服饰企业,集合/SHOWROOM,协会/机构';
                    $description = 'POP服装趋势网行业资讯的精英联盟是由分布于不同地区的供应商、独立设计师、服饰企业、集合SHOWROOM和协会/机构组成。';
                } else {
                    $key = ['cpt', 'cit'];
                    foreach ($key as $k => $v) {
                        if (isset($paramsArr[$v])) {
                            $_title .= ${$v}[$paramsArr[$v]]['sName'] . '_';
                            $_description .= ${$v}[$paramsArr[$v]]['sName'] . '/';
                            $_keywords .= ${$v}[$paramsArr[$v]]['sName'] . ',';
                        }
                    }
                    $_keywords = trim($_keywords, ',');
                    $_description = trim($_description, '/');
                    $_title = trim($_title, '_');
                    $title = $_title . '_精英联盟-POP服装趋势网';
                    $keywords = $_keywords . ',精英联盟';
                    $description = 'POP服装趋势网不仅汇集精英人才、知名企业和机构，此外还为您显现' . $_description . '全面、完整的资讯内容。';
                    break;
                }
        }
        $this->assign('mesType', $mesType);

        $pageSize = 10;// 每页展示数据
        $page = $this->common_model->getPage($paramsArr);// 当前页
        $offset = ($page - 1) * $pageSize;// 偏移量
        $lists = [];

        $totalCount = $this->activity_model->getActivityLists($conditions, $lists, $offset, $pageSize, $arSort);

        $rootUrl = '/activity/lists/';
        $this->assign('rootUrl', $rootUrl);
        $pageHtml = $this->makePageHtml($paramsArr, $totalCount, $pageSize, $page, $rootUrl);// 生成页码
        $this->assign('lists', $lists);
        $this->assign('totalCount', $totalCount);
        $this->assign('pageHtml', $pageHtml);

        $this->assign('params', $params);
        $this->assign('paramsArr', $paramsArr);

        $this->assign('iActivityMessageType', $iActivityMessageType);
        $this->assign('iActivityChildType', $iActivityChildType);


        $this->assign('title', $title);
        $this->assign('keywords', $keywords);
        $this->assign('description', $description);

        $this->display('activity/activity_list.html');
    }

    public function redirectditu()
    {
        $ditu = $this->input->get('ditu');
        if ($ditu) {
            $ditu = urldecode($ditu);
            header("Location:" . $ditu);
        } else {
            header("Location:/error/");
        }
        exit;
    }


    public function upload($op)
    {
        $file = array_shift($_FILES);
        $this->load->library('POP_Uploadpic');
        $fileInfo = $this->pop_uploadpic->getFileStream($file);

        if (!$fileInfo) {
            $response = ['error' => 1, 'message' => $this->pop_uploadpic->getError()];
            echo json_encode($response);
            die;
        }

        $width = $height = 0;
        if ($op == 'details') {
            $dir = $this->input->get('dir', true);
            $fileTargetPath = '/activity/' . $dir . '/' . $op . '/' . date("Ymd") . '/';
        } elseif ($op == 'poster') {
            $fileTargetPath = '/activity/' . $op . '/' . date("Ymd") . '/';
            $width = 580;
            $height = 405;
        } else {
            $fileTargetPath = '/activity/' . $op . '/' . date("Ymd") . '/';
        }

        $filename = uniqid('activity_') . '.' . $fileInfo['ext'];
        $body = [
            'fWidth' => $width ? $width : $fileInfo['width'],
            'fHeight' => $height ? $height : $fileInfo['height'],
            'fName' => $filename,
            'fStream' => $fileInfo['stream'],
            'fTargetPath' => $fileTargetPath
        ];

        $http_resp = $this->pop_uploadpic->curlRequest($body);

        $res = json_decode($http_resp, true);
        if ($res['status'] == 1) {
            $response = ['error' => 0, 'url' => STATIC_URL1 . $res['info']];
        } else {
            $response = ['error' => 1, 'message' => $res['info']];
        }
        echo json_encode($response);
        die;

    }

    public function addrDate()
    {
        if (!$this->input->is_ajax_request()) {
            echo '非法进入';
            die;
        }

        $id = $this->input->post('id', true);
        $id = $id ? intval($id) : 1;
        //活动地区--线下使用
        $area = $this->activity_model->getActivityDictByiType(1, 0, false);
        $city = $this->activity_model->getActivityDictByiType(1, $id, false);

        echo json_encode(['area' => $area, 'defaultArea' => $area[$id], 'city' => $city, 'defaultCity' => array_shift($city)]);
    }

    public function childType()
    {
        if (!$this->input->is_ajax_request()) {
            echo '非法进入';
            die;
        }

        $id = $this->input->post('id', true);
        if ($this->input->post('iType', true)) {
            $iType = intval($this->input->post('iType', true));
        } else {
            $iType = 3;
        }

        $col = $this->input->get_post('col', true);
        //活动子类型
        $activityChildTypes = $this->activity_model->getActivityDictByiType($iType, $id, false, $col);

        echo json_encode(['child' => $activityChildTypes, 'defval' => array_shift($activityChildTypes)]);
    }


    private function checkSign()
    {
        $power = memberPower('other');
        if ($power['P_UserType'] == 1) {//主账号
            $memcacheKey = OpPopFashionMerger::POP_FASHION_MERGER_MEMCACHE_KEY_PREFIX . '_activity_management_privilege_' . $this->useInfo['id'];
            $sign = md5(POP_GLOBAL_KEYS . '_' . $this->useInfo['id']);
        } elseif ($power['P_UserType'] == 2) {//子账号
            $memcacheKey = OpPopFashionMerger::POP_FASHION_MERGER_MEMCACHE_KEY_PREFIX . '_activity_management_privilege_' . $this->useInfo['id'] . '_' . $this->useInfo['sChildID'];
            $sign = md5(POP_GLOBAL_KEYS . '_' . $this->useInfo['id'] . '_' . $this->useInfo['sChildID']);
        } else {
            echo '对不起，您没有权限操作';
            return false;
        }
        $this->load->driver('cache');
        $memcacheSign = $this->cache->memcached->get($memcacheKey);
        if ($sign != $memcacheSign) {
            echo '签名已经过期,请重新获取管理入口链接';
            return false;
        } else {
            return true;
        }

    }

}