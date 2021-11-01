<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 云图APP -- 用户的注册登录
 * 继承：APP_Controller
 */

require_once FCPATH . "/core/APP_Controller.php";

class Account extends APP_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 注册
     * http://yuntu.pop136.com/api/account/register
     */
    public function register()
    {
        $this->load->model('Account_model');

        $account = $this->input->get_post('account');
        $phone = $this->input->get_post('phone');
        $passwd = trim($this->input->get_post('password'));
        $captcha_code = $this->input->get_post('check_code'); // 短信验证码

        if (empty($account)) {
            outPrintApiJson(10001, '用户名不为空');
        }
        $strlen = mb_strlen($account);
        if ($strlen < 4 || $strlen > 20) {
            outPrintApiJson(10002, '输入字符已超限，4-20位字符');
        }
        if (!preg_match('/^[a-zA-Z0-9_\-@&\x{4e00}-\x{9fa5}]{4,}$/imu', $account)) {
            outPrintApiJson(10002, '4-20位字符');
        }
        if (empty($passwd)) {
            outPrintApiJson(10003, '密码不能为空');
        }
        if (!preg_match('/^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$/im', $passwd)) {
            outPrintApiJson(10004, '6-20位字符，可由数字、字母、特殊字符组成');
        }
        if (!preg_match('/^1\d{10}$/is', $phone)) {
            outPrintApiJson(10005, '手机格式不正确');
        }
        if (!$this->Account_model->verifyPhone($phone)) {
            outPrintApiJson(10006, '该号码已存在');
        }
        if ($this->Account_model->check_user_exists($account)) {// 此账号已经存在
            outPrintApiJson(10007, '此账号已经存在');
        }

        //验证手机短信
        $verifyStatus = $this->Account_model->checkSMSCode($phone, $captcha_code, 10);
        if (!$verifyStatus) {
            outPrintApiJson(10008, '短信验证失败');
        }

        // 写入用户表
        $rs = $this->Account_model->registerCloudUser($account, $passwd, $phone, 1, $captcha_code);
        if ($rs['status']) {
            outPrintApiJson(0, 'ok');
        } else {
            outPrintApiJson(10009, '注册失败');
        }
    }

    /**
     * app版本
     * http://yuntu.pop136.com/api/account/getappversion
     */
    public function getAppVersion()
    {
        $doc = new DOMDocument('1.0', 'utf-8');    //实例化XML
        $doc->load('app_version.xml'); //读取xml文件

        // 接收的参数
        $device_type = $_SERVER['HTTP_DEVICE_TYPE'];// 设备号
        $version = $_SERVER['HTTP_VERSION'];// 版本号
        if (empty($device_type) || empty($version)) {
            outPrintApiJson(100080, '参数缺失');
        }

        //取得appVersion标签的对象数组
        $xmlApp = $doc->getElementsByTagName("app")->item(0);
        // 当前的ios 与 android 版本号
        $ios_version = $xmlApp->getElementsByTagName('ios')->item(0)->nodeValue;
        $android_version = $xmlApp->getElementsByTagName('android')->item(0)->nodeValue;

        // true--需要更新；false--默认不更新
        if ($device_type == "ios") {
            $forceUp = $ios_version >= $version ? true : false;
        }
        if ($device_type == "android") {
            $forceUp = $android_version >= $version ? true : false;
        }
        outPrintApiJson(0, 'OK', compact('forceUp'));
    }

    /**
     * 获取云图App端--用户权限
     * token
     * http://yuntu.pop136.com/api/account/getpowerinfo
     */
    public function getPowerInfo()
    {
        $this->load->model('Account_model');

        $userId = $this->check_jwt_token();
        if (!$userId) {
            outPrintApiJson(1020, '用户id不存在，检查token值');
        }

        $data = ['identity' => '', 'privilege' => []];
        $priInfo = $this->Account_model->getUserPowerDate($userId);
        if ($priInfo) {
            $data['identity'] = 'VIP';

            $priInfo = array_map(function ($val) {
                unset($val['iPrivId']);
                $val['dEndTime'] = strtotime($val['dEndTime']);
                return $val;
            }, $priInfo);

            $data['privilege'] = $priInfo;
        } else {
            $data['identity'] = 'GENERAL';
        }

        // 获取报告 或者 款式收藏量与封面图
        $this->load->model('Favorite_model');
        list($patternList, $patternTotal) = $this->Favorite_model->getList(0, 1, $userId, 'pattern');
        list($reportList, $reportTotal) = $this->Favorite_model->getList(0, 1, $userId, 'report');
        $data['reportCollectTotal'] = $reportTotal;
        $data['reportCollectImg'] = $reportList[0]['imgPath'] ? $reportList[0]['imgPath'] : '';
        $data['patternCollectTotal'] = $patternTotal;
        $data['patternCollectImg'] = $patternList[0]['imgPath'] ? $patternList[0]['imgPath'] : '';

        outPrintApiJson(0, 'OK', $data, '请求时间:' . date("Y-m-d H:i:s"));
    }

    /**
     * 提供云图app端使用
     * 根据用户id，获取用户虚拟样衣模板数据
     * token
     * http://yuntu.pop136.com/api/account/virtualsampletemplate?site=1&type=1
     */
    public function VirtualSampleTemplate()
    {
        $this->load->model('Account_model');

        $userId = $this->check_jwt_token();
        if (!$userId) {
            outPrintApiJson(1020, '用户id不存在，检查token值');
        }

        // 云图系统虚拟样衣模板站点：1-服装，2-箱包，3-鞋子，5-家纺
        $site = $this->input->get_post("site");
        // 不同入口：1=>图案详情的模拟成品，2=>拍照的模拟成品，3=>以图搜图（其他接口）
        $type = $this->input->get_post("type");

        // 普通用户 选择模板
        $info = [];
        $priInfo = $this->Account_model->getUserPowerDate($userId);
        if (!$priInfo) {
            $iTplSite = $this->Account_model->getUserTplSite($userId);
            // 1,没有模板，没有选择模板网站，无参数site
            if (!$site && !$iTplSite) {
                $data = array();
                outPrintApiJson(0, 'OK', $data, ['site' => '']);
            }

            // 4--首饰，没有模拟样衣模板
            if ($site && !in_array($site, array(1, 2, 3, 5))) {
                outPrintApiJson(10017, '网络开小差了,请重试');
            }

            // 2，绑定模板，有参数site
            if (!$iTplSite) {
                // $iTplSite : $site : 1-服装,2-箱包,3-鞋子,5-家纺
                if (!$site) {
                    outPrintApiJson(10016, '必要参数缺失');
                }
                $res = $this->Account_model->modTplSite($userId, $site);
                if (!$res) {
                    outPrintApiJson(10014, '设置失败！');
                }
            }

            // 3，选择入口：不同入口：1=>图案详情的模拟成品，2=>拍照的模拟成品，3=>以图搜图（其他接口）
            if (!$type) {
                outPrintApiJson(10010, '缺少模板参数！');
            }

            // 4，获取已使用的次数  普通用户
            $total = $this->Account_model->getYuntuTryNum($userId, $type);
            if (intval($total) >= 3) {
                // 1014 为固定的
                outPrintApiJson(1014, '试用次数结束');
            } else {
                $this->Account_model->addYuntuTryNum($userId, $type);
            }
        }

        // VIP 或者 有试用权限的用户
        $iTplSite = $this->Account_model->getUserTplSite($userId);
        if (!$iTplSite) {
            outPrintApiJson(10013, '模板未设置站点');
        }
        // 4--首饰，没有模拟样衣模板
        if ($iTplSite && !in_array($iTplSite, array(1, 2, 3, 5))) {
            outPrintApiJson(10017, '网络开小差了,请重试');
        }

        $data = $this->Account_model->getTemplates($iTplSite, $userId);

        foreach ($data as $key => $item) {
            if ($key == 'isCustom') {
                continue;
            }

            $data[$key] = array_map(function ($val) {
                $val['sLargePath'] = STATIC_URL1 . $val['sLargePath'];
                $val['sThumbnailPath'] = STATIC_URL1 . $val['sThumbnailPath'];
                return $val;
            }, $item);
        }

        // 1-服装,2-箱包,3-鞋子,5-家纺
        $relation = [];
        switch ($iTplSite) {
            case 1:
                // fashion.t_dict_attribute 虚拟样衣模板分类
                $relation = GetCategory::getAttrValueByType(30);
                break;
            case 2:
                // 后台配置硬编码
                $relation = [
                    1 => '男包',
                    2 => '女包',
                    3 => '运动拉杆',
                    4 => '小皮件',
                    100 => '定制',
                ];
                break;
            case 3:
                // 后台配置硬编码
                $relation = [
                    1 => '女鞋',
                    2 => '男鞋',
                    3 => '运动鞋',
                    4 => '童鞋',
                    100 => '定制',
                ];
                break;
            case 5:
                // 后台配置硬编码
                $relation = [
                    1 => '床上用品',
                    2 => '家居用品',
                    3 => '软装布艺',
                    4 => '家居服',
                    5 => '配饰',
                    100 => '定制',
                ];
                break;
        }

        // 排除不存在的模板，在info中
        $exitsArr = array_keys($relation);
        if ($exitsArr) {
            foreach ($exitsArr as $item) {
                if (!in_array($item, array_keys($data))) {
                    unset($relation[$item]);
                }
            }
        }

        $map = array_flip($relation);
        $keys = array_keys(array_flip($relation));

        // info
        $info['site'] = '所选站点为:' . $iTplSite;
        $info['map'] = $map;
        $info['keys'] = $keys;

        outPrintApiJson(0, 'OK', $data, $info);
    }
}