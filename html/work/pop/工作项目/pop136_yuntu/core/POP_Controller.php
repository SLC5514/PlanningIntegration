<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH . "core/Base_Controller.php";

/**
 * @author Jason Zou
 * @since 1.0
 */
class POP_Controller extends Base_Controller
{
    /**
     * Class constructor
     */
    protected $templete_2d_sites;

    public function __construct()
    {
        parent::__construct();
        // $class = $this->router->fetch_class();
        // if($class!="system"){
        //     header('Location:/system/upgrade/');
        //     exit;
        // }
        $this->header();
        $this->initUserIdentityInfo();
        $this->showMiYin();
        $this->showMiYinOffLineTips();//是否显示智能设计下线提示条

        $this->updateOpMemTime();

        // 根据客户端IP判断所在区域，国外的显示谷歌翻译下拉选
        $google_select_show = $this->is_foreign();
        $this->assign('google_select_show', $google_select_show);

        //2D模板拥有的站点
        $userId = getUserId();
        $templete_2d_sites = $col_power = array();
        if ($userId) {
            $this->load->model('VirtualTryOn_model');
            $col_power = $this->User_model->checkUserVip();
            $templete_2d_sites = $this->VirtualTryOn_model->getUserPowerTemplateSite();
            if (in_array(1, $col_power)) {
                $templete_2d_sites = json_decode($templete_2d_sites, true);
                $templete_2d_sites = is_array($templete_2d_sites) ? array_keys($templete_2d_sites) : array();
            } else {
                $templete_2d_sites = empty($templete_2d_sites) ? array() : explode(',', $templete_2d_sites);
            }
        }

        // 统计浏览量、下载相关
        if (!empty($userId)) {
            // 统计的token
            $statistics_token = md5("LR9UxOvtqviGWw0u_" . intval($userId) . "_" . date('Ymd'));
            $this->smarty->assign("statistics_token", $statistics_token);

            // 用户id
            $this->smarty->assign("iUserId", $userId);

            // 同步服装(图案趋势与图案库的权限一致，2020-06-29)的类型，1--主账号id | 4--普通id
            $this->load->model('User_model');
            $user_type = $this->User_model->checkUserType($userId, 3);
            $P_UserType_action = $user_type[3] == 'VIP' ? 1 : 4;
            $this->smarty->assign("P_UserType_action", $P_UserType_action);
        }

        $this->assign('col_power', $col_power);
        $this->assign('iTplSite', $templete_2d_sites);
        $this->templete_2d_sites = $templete_2d_sites;
        $this->assign("templete_2d_sites", $templete_2d_sites); //普通用户
    }

    /**
     * 判断给定IP是否是国外的
     *
     * @param $ip
     * @return bool
     */
    private function is_foreign($ip = '')
    {
        $ip = $ip ? $ip : $this->input->ip_address();
        $MemcacheKey = "cate_ip_area_" . $ip;
        $country_id = $this->cache->memcached->get($MemcacheKey);
        if (!$country_id) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
            $http_resp = curl_exec($ch);
            curl_close($ch);
            $res = json_decode($http_resp, true);

            if ($_GET['debug']) {
                echo 'IP:' . $ip;
                var_dump($res);
                die;
            }
            $country_id = $res['data']['country_id'];
            if (!empty($res['data']['country_id'])) {
                $this->cache->memcached->save($MemcacheKey, $country_id, 86400);
            }
        }
        // 是否国内
        $is_inland = in_array($country_id, ['CN', 'xx', '']); // taobao 的接口内网地址会返回xx, 不合法IP会返回空字符串
        return !$is_inland;
    }

    //是否显示智能设计下线提示条
    private function showMiYinOffLineTips()
    {
        $this->load->model('User_model');
        $power = $this->User_model->checkUserVip();
        $userId = getUserId();
        $mem = 'POP_YUNTUMIYIN_OFFLINETIPS_' . $userId;
        $haveUser = $this->cache->memcached->get($mem);
        $is_show_tip = false;
        if (in_array(4, $power) && !$haveUser) {
            $is_show_tip = true;
        }
        $this->assign('is_show_tip', $is_show_tip);
    }

    /**
     * 一小时内未操作，视为过期，强制退出
     */
    private function updateOpMemTime()
    {
        //屏蔽控制器或方法名 全小写
        $aShieldControllers = ['api', 'looprequire'];

        //控制器
        $class = $this->router->fetch_class();

        //方法名
        // $method = $this->router->fetch_method();

        //赋值token用于轮询
        $userId = getUserId();
        if (!empty($userId)) {
            $time = time();
            $token = getToken($userId, $time);
            $this->assign("loopToken", $token);
            $this->assign("loopTime", $time);
        }

        //该控制器在所屏蔽数组中默认跳过
        // if (false !== array_search(strtolower($class), $aShieldControllers)) {
        if (!in_array(strtolower($class), $aShieldControllers)) {
            return;
        }

        //更新用户操作时间
        $UID = $this->input->cookie("POP_UID");
        if (!empty($UID)) {
            $this->load->driver('cache');
            $timeOut = USER_TIME_OUT;
            $memcacheKey = UID_MEMKEY_PRE . $UID;
            $time = $this->cache->memcached->get($memcacheKey);
            if ($time && $time + $timeOut >= time()) {
                //未超时更新用户操作时间
                $this->cache->memcached->save($memcacheKey, time(), UID_MEMKEY_TIME);
            } elseif ($time && $time + $timeOut < time()) {
                //超时自动删除缓存
                $this->cache->memcached->delete($memcacheKey);
            }
        }
    }


    /**
     * 初始化用户身份信息
     */
    private function initUserIdentityInfo()
    {
        $this->load->model('User_model');
        $power = $this->User_model->checkUserVip();

        $userIdentity = 'TOURIST';
        $userInfo = get_cookie_value();
        $accountType = '';
        if ($userInfo) {
            $userIdentity = 'GENERAL';
            $accountType = $userInfo['iAccountType'] == 2 ? 'child' : 'main';
        }
        $accountId = $userInfo['id'];

        if ($power) {
            $userIdentity = 'VIP';
        }
        $this->assign('user_type', $userIdentity);
        $this->assign('user_id', getUserId());
        $this->assign('account_type', $accountType);
        $this->assign('account_id', $accountId);
    }

    //获取当前栏目权限
    protected function get_use_type($col)
    {
        $col_power = $this->User_model->checkUserVip();
        $col_user_type = in_array($col, $col_power) ? "VIP" : "GENERAL";

        //检测是否是试用账号,隐藏下载按钮
        $uid = getUserId();
        $trial_type = $this->User_model->checkUserType($uid, $col);
        if ($trial_type[$col] == 'TRIAL') {
            $col_user_type = 'TRIAL';
        }

        $this->assign('col_user_type', $col_user_type);
        return $col_user_type;
    }

    /**
     * assign method extends smarty
     * @param $key
     * @param $val
     */
    public function assign($key, $val)
    {
        $this->smarty->assign($key, $val);
    }

    /**
     * display method extends smarty
     * @param $html
     */
    public function display($html)
    {
        $this->smarty->display($html);
    }

    /**
     * assign method extends smarty
     * @param $val
     * @return
     */
    public function fetch($val)
    {
        return $this->smarty->fetch($val);
    }

    /*------------------------------------------------------------------------------------------------------------------
    * @todo 获取头部信息
    *----------------------------------------------------------------------------------------------------------------*/
    public function header()
    {
        $isAjax = $this->input->is_ajax_request();
        if ($isAjax) {
            return;
        }
        $this->load->model('user_model');
        $userInfo = get_cookie_value();
        if ($userInfo) {
            $uid = getUserId(false);
            //获取用户头像
            $avatarUri = $this->user_model->getUserImg($uid);
            //获取cookie中用户名
            $account = $userInfo['account'] ? $userInfo['account'] : 'null';
            $this->assign('avatarUri', $avatarUri['sAvatar']);
            $this->assign('account', $account);
        }
    }

    // 是否显示智能设计
    public function showMiYin()
    {
        $userId = getUserId();
        if (!$userId) {
            return false;
        }

        $this->load->model("User_model");
        $showMiYinAll = $this->User_model->checkUserVip($userId);

        $showMiYin = false;
        if (in_array(4, $showMiYinAll)) {
            $showMiYin = true;
        }
        $this->assign("showMiYin", $showMiYin);
        return $showMiYin;
    }

    /*------------------------------------------------------------------------------------------------------------------
     * @todo 权限验证
     * @param $col 栏目 (PS:为0/空或不传，将不会验证栏目权限)
     * @param $tryout_model (PS:试用模块名,在无VIP权限时会)
     *----------------------------------------------------------------------------------------------------------------*/
    public function checkPower($col = 0, $module = "")
    {
        $this->load->model("User_model");
        $this->load->model('VirtualTryOn_model');
        $this->load->driver("cache");
        $isAjax = $this->input->is_ajax_request();
        $ua = $_SERVER['HTTP_USER_AGENT'];
        // ie9及以下不支持 浏览器升级提示
        if (strstr($ua, 'MSIE 6') || strstr($ua, 'MSIE 7') || strstr($ua, 'MSIE 8')) {
            if ($isAjax) {
                outPrintApiJson(1005, "浏览器需要升级！");
            } else {
                echo '<script>top.location.href="/system/systemnotice/?type=2"</script>';
                exit;
            }
        }
        //--是否登录
        $userId = getUserId();
        if (empty($userId)) {
            if ($isAjax) {
                outPrintApiJson(1000, "未登录！");
            } else {
                echo '<script>top.location.href="/user/login/"</script>';
                exit;
            }
        }
        //--是否强制下线
        $allUser = $this->cache->memcached->get(MIAN_UID_MEMKEY_PRE . $userId);
        if ($allUser === false) {
            if ($isAjax) {
                outPrintApiJson(1003, "登录失效");
            } else {
                echo '<script>top.location.href="/"</script>';
                exit;
            }
        }
        //--是否超时
        $main_user_id = getUserId(true);
        $timeOut = USER_TIME_OUT;
        $POP_UID = $this->input->cookie("POP_UID"); //唯一串
        $actionTime = $this->cache->memcached->get(UID_MEMKEY_PRE . "{$POP_UID}");
        if ($actionTime == false || $actionTime + $timeOut < time()) {
            logout();
            if ($isAjax) {
                outPrintApiJson(1002, "登录超时！");
            } else {
                echo '<script>top.location.href="/system/systemnotice/?type=4"</script>';
                exit;//跳转路径待定
            }
        }
        //--是否有权限(首页、用户中心只要登录就可以访问)
        $col_power = $this->User_model->checkUserVip();
        $module_power = $this->User_model->get_rest_times($userId, $col_power);


        $this->assign('col_power', $col_power);
        $this->assign('module_power', $module_power);
        if ($col !== 0) {
            $has_power = false;
            if (in_array($col, $col_power)) {
                $has_power = true;
            } elseif (!empty($module)) {
                $has_power = $module_power[$module][1] > 0 ? true : false;
            }
            if (!$has_power) {
                if (empty($module)) {
                    $type = 1;
                    if ($col == 4) {
                        $type = 5; // 智能设计，没有权限的type值,原来的
                    } elseif ($col == 3) {
                        $type = 8; // 图案库详情，没有权限的type值，跳转 /home/
                    }

                    $code = 1001;
                    $msg = "没有权限";
                    $url = "/system/systemnotice/?type={$type}";
                } else {
                    //跳快照次数用完页面
                    $code = 2001;
                    $msg = "使用次数用尽";
                    $types = [1 => 1, 2 => 2, 4 => 3];
                    $type = $types[$col];
                    $url = "/system/snapshot/?type={$type}";
                }
                if ($isAjax) {
                    outPrintApiJson($code, $msg);
                } else {
                    echo "<script>top.location.href = '$url'</script>";
                    exit;//跳转路径待定
                }
            }
        }
    }

    /*------------------------------------------------------------------------------------------------------------------
    *@todo 设置TDK
    *@param $controller 总数
    *@param $function   每页显示条数
    *@param $selected   页码
    *-----------------------------------------------------------------------------------------------------------------*/
    public function setTDK($controller, $function = "index", $selected = [])
    {
        $controller = strtolower($controller);
        $function = strtolower($function);
        $array = getCommonData("tdk_data", "TDK", "data", $selected);
        $function = !empty($selected) ? $function . "_s" : $function; //有参数则用另外一种
        if (isset($array[$controller][$function])) {
            $tdk = $array[$controller][$function];

            if (!$this->input->is_ajax_request()) {
                $this->assign("TDK_title", $tdk["T"]);
                $this->assign("TDK_keywords", $tdk["K"]);
                $this->assign("TDK_description", $tdk["D"]);
            }
            return $tdk;
        } else {
            return [];
        }
    }

    /*------------------------------------------------------------------------------------------------------------------
     * @param $params URL中传带的参数 eg.(a=a&b=b&page=1)
     * @param $totalCount 总数
     * @param $pageSize 每页显示条数
     * @param $page 页码
     * @param $uri 页码要跳转的链接（由控制器及方法等组成）
     * @param $simple 是否生成简单页码（ < 1/207 > ）
     * @return $pageHtml string 页码HTML代码
     *----------------------------------------------------------------------------------------------------------------*/
    public function makePageHtml($paramsArr, $totalCount, $pageSize, $page, $uri, $simple = false)
    {
        if (substr(ltrim($uri), 0, 1) == '/') {
            $uri = ltrim($uri, '/');
        }
        //分页
        if (isset($paramsArr['page'])) {
            unset($paramsArr['page']);
        }
        $_url = encodeParams($paramsArr);
        $_url = !empty($_url) ? $_url : '';;
        $url = $this->config->item('base_url') . $uri . $_url;
        if (count($paramsArr) > 0 && !empty($paramsArr)) {
            $url .= '-';
        }
        //创建分页器
        $paramsArr = array(
            'count' => $totalCount,
            'size' => $pageSize,
            'pageNo' => $page,
            'url' => $url
        );
        $this->load->library('POP_MakePage', $paramsArr);
        //生成页码
        if ($simple) {
            $pageHtml = $this->pop_makepage->echoSimplePageAsDiv();
        } else {
            $pageHtml = $this->pop_makepage->echoPageAsDiv();
        }
        return $pageHtml;
    }
}
