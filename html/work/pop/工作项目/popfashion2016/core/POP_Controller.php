<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author Jason Zou
 * @since 1.0
 */
class POP_Controller extends CI_Controller
{
    /**
     * @var Smarty
     */
    public $smarty;

    /**
     * Class constructor
     * @return    void
     */
    public function __construct()
    {
        parent::__construct();
        $pop_uid = $this->input->cookie("POP_UID");
        if (empty($pop_uid)) {
            $pop_uid = md5(uniqid('', mt_rand(10000, 99999) . '-'));
            $this->input->set_cookie('POP_UID', $pop_uid);
        }
        // $this->isRedirectEn();

        $method = $this->router->fetch_method();
        $class = $this->router->fetch_class();
        $route = "{$class}/{$method}";
        if($route != 'home/wpa'){//联系客服页面不加限制
            //是否禁用此ip
             $this->disable_ip();
        }


        //是否长时间未修改密码
        $this->edit_password();


        // vip 是否能看数据报告
        if ($this->canUseDataReport()) {
            $this->assign('dataReport', true);
        } else {
            $this->assign('dataReport', false);
        }

        // 网络推广来源
        $this->init_pidtt();

        // 头部token
        $header_token = md5(POP_GLOBAL_JWT_KEY . $pop_uid);
        $this->assign('header_token', $header_token);
    }

    //是否禁用此ip
    public function disable_ip()
    {
        $ip = $this->input->ip_address();
        $redis = getRedisConnection();
        $redis_key = 'REDIS_CRM_DISABLED_IPS';
        $flag = $redis->SISMEMBER($redis_key,$ip);//查找成员是否在集合中
        //是否禁用此ip
        $this->assign('disabled_ip',$flag);
        if($flag) {
            $this->display('errors/html/ip_abnormal.html');
            die;
        }
    }

    //是否长时间未修改密码
    public function edit_password()
    {
        $userId = getUserId();
        $pwd_tip = false;
        if(!empty($userId) && is_numeric($userId)) {//主账号
            $userInfo = get_cookie_value();
            $pwd_tip = $userInfo['pwd_tip'];
        }

        //是否提示
        $this->assign('pwd_tip',$pwd_tip);

    }


    /**
     * 网络推广来源
     */
    protected function init_pidtt()
    {
        $pid = $this->input->get('pid'); // 推广id
        // $pidtt = trim($this->input->cookie('pidtt'));
        if ($pid) {
            $this->input->set_cookie('pidtt', $pid);
        }
    }

    /**
     * vip用户登录过期提示条
     */
    public function isVipExpireTips()
    {
        $powers = memberPower();
        if (in_array($powers['P_UserType'], [1, 2])) {
            $userInfo = get_cookie_value();
            if (!empty($userInfo)) {
                $this->load->model('common_model', 'common');
                $flag = $this->common->vipExpireTips($userInfo['id']);
                return $flag;
            }
            return false;
        }
        return false;
    }

    // fetch the params
    public function getParams()
    {
        return $this->uri->rsegment(3, []);
    }

    /**
     * assign method extends smarty
     */
    public function assign($key, $val)
    {
        $this->smarty->assign($key, $val);
    }

    /**
     * display method extends smarty
     */
    public function display($html)
    {
        $this->smarty->display($html);
    }

    /**
     * assign method extends smarty
     */
    public function fetch($val)
    {
        return $this->smarty->fetch($val);
    }

    /**
     * @param string $params URL中传带的参数 eg.(a=a&b=b&page=1)
     * @param int $totalCount 总数
     * @param int $pageSize 每页显示条数
     * @param int $page 页码
     * @param string $uri 页码要跳转的链接（由控制器及方法等组成）
     * @param boolean $simple 是否生成简单页码（ < 1/207 > ）
     *
     * @return $pageHtml string 页码HTML代码
     */
    public function makePageHtml($params, $totalCount, $pageSize, $page, $uri, $simple = FALSE)
    {
        if (substr(ltrim($uri), 0, 1) == '/') {
            $uri = ltrim($uri, '/');
        }
        $this->load->model('common_model', 'common');
        //分页
        if (isset($params['page'])) {
            unset($params['page']);
        }
        $_url = $this->common->parseParams($params, 2, FALSE);
        $_url = !empty($_url) ? $_url : '';
        $url = $this->config->item('base_url') . $uri . $_url;
        if (count($params) > 0 && !empty($params)) {
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

    /**
     * 2018-05-29
     * 是否自动跳转到英文站
     * 跳转条件：
     * 1) cookie 没有 lang, 且为国外ip
     * 2) cookie 有 lang, 且lang值不是 'cn'
     */
    private function isRedirectEn()
    {
        return;
        if ($this->input->is_ajax_request()) {
            return;
        }
        $this->load->helper('cookie');
        $lang_cookie = get_cookie('lang');
        $ip = $this->input->ip_address();
        if (ENVIRONMENT != 'production') {
            $_ip = $this->input->get_post('ip');
            $ip = $_ip ? $_ip : $ip;
        }
        // $ip = '47.104.22.91'; // 国内
        // $ip = '47.88.159.132'; // 国外
        $expires_in = 365 * 86400; // cookie 有效时长

        if ($lang_cookie) {
            if ($lang_cookie != 'cn') {
                set_cookie('lang', 'en', $expires_in);
                header("Location: http://www.popfashioninfo.com");
                return;
            }
        } else {
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
            $country_id = $res['data']['country_id'];
            // 是否国内
            $is_inland = in_array($country_id, ['CN', 'xx', '']); // taobao 的接口内网地址会返回xx, 不合法IP会返回空字符串

            if ($is_inland) {
                set_cookie('lang', 'cn', $expires_in);
            } else {
                set_cookie('lang', 'en', $expires_in);
                header("Location: http://en.pop-fashion.com");
                return;
            }
        }
    }

    /**
     * 判断是否可以查看数据报告
     * @return bool
     * @date 2018-10-23
     */
    private function canUseDataReport()
    {
        $isAjax = $this->input->is_ajax_request();
        if ($isAjax) {
            return false;
        }
        $userInfo = get_cookie_value();
        if (!$userInfo || $userInfo['iAccountType'] != 1) {
            // 未登录或不是主帐号
            return false;
        }

        $userId = $userInfo['id'];
        $redisConn = getRedisConnection();
        $redisKeyOverview = "data_report:user:{$userId}:overview";

        if (!$redisConn->exists($redisKeyOverview)) {
            // 没有可查看的信息
            return false;
        }
        return true;
    }

    public function __destruct()
    {
        if (isset($this->db) && is_object($this->db)) {
            $this->db->close();
        }
    }

}
