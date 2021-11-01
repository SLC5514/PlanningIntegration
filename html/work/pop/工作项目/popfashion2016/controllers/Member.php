<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Member extends POP_Controller
{
    public $mainAccountInfo = array();//主账号信息
    public $childAccountInfo = array();//子账号信息

    public $accountVipType = array();//账号VIP信息

    public $iAccountType = '';//用户类型  1代表主账号 2代表子账号
    public $arrCookie = array();//cookie中存的用户信息
    public $sLastLogionTime = '';//上次登录时间
    public $sAccountId = '';//用户id

    CONST MEMC = false;

    public $_refresh = false;

    public function __construct()
    {
        parent:: __construct();
        $this->load->helper(['cookie', 'url']);
        $this->load->model(['Workbench_model' => 'workbench', 'common_model', 'Member_model']);
        $this->load->library('ImageVerification');
        $this->assign('columnPid', 92);
        $this->assign('title', '');
        $this->assign('keywords', '');
        $this->assign('description', '');
        $this->_refresh = isset($_GET['refresh']);
    }

    // 注册模块
    public function register($parameter = '')
    {
        $aParams = $parameter ? explode('-', $parameter) : array('register');
        switch ($aParams[0]) {
            case 'register':
                $power = memberPower();
                if (!empty($power["P_UserType"]) && $power["P_UserType"] != 5) {
                    header("Location:/member/workbench/");
                }
                // 港澳台 地区标识  pop136.t_leave_message.area
                $aAreas = array(1 => '香港', 2 => '澳门', 3 => '台湾', 4 => '其他');
                $this->assign("aAreas", json_encode($aAreas));

                //注册广告
                $aAds = $this->common_model->getAds(0, 25, 1, 0);
                // 注册用户可使用网站查看 ，伪静态页面
                $RL = isset($aparams[1]) && !empty($aparams[1]) ? trim($aparams[1]) : null;
                $RID = isset($aparams[2]) && !empty($aparams[2]) ? intval($aparams[2]) : 0;
                $UID = isset($aparams[3]) && !empty($aparams[3]) ? trim($aparams[3]) : null;
                $APM = isset($aparams[4]) && $aparams[4] == 'APM' ? 'APM' : null; //APM注册标识
                $scookie_send_message_val = EncryptionDeciphering(serialize(array("last_time" => date('y-m-d H:i:s', time() - 7200), "count" => "0")), true);
                $this->input->set_cookie('send_message', $scookie_send_message_val, 7200);
                $this->assign("aAds", $aAds);
                $this->assign("RL", $RL);
                $this->assign("RID", $RID);
                $this->assign("UID", $UID);
                $this->assign("APM", $APM);
                break;
            case 'register_complete':
                //非真实账号，只为显示登录状态
                $this->assign("accountShow", "true");
                $auotLoginHtml = get_cookie('auotLoginHtml');
                if (get_cookie('auotLoginHtml')) {
                    delete_cookie('auotLoginHtml');
                }
                $sAccountId = getUserId();
                // 手机号是否已验证，已验证tel=手机号，未验证tel=false
                $tel = $this->member_model->isTelVerified($sAccountId);
                $aAds = $this->common_model->getAds(0, 21, 1, 0);

                $this->assign('Ad', $aAds[0]);
                $this->assign('tel', $tel);
                $this->assign("auotLoginHtml", $auotLoginHtml);
                break;
            default:
                // code...
                break;
        }
        $this->assign('title', '注册-POP服装趋势网');
        $this->assign('keywords', '欢迎注册POP会员,海外用户账号');
        $this->assign('description', '注册完后即可免费尽览全球款式图库！');
        $this->display('member/' . $aParams[0] . '.html');
    }

    /**
     * 登录页
     */
    public function login()
    {
        $sBackUrl = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : $this->config->item('base_url');
        $this->assign('title', '用户登陆-POP服装趋势网');
        $this->assign('keywords', '用户登陆,POP趋势资讯网,国际服饰设计在线,服装设计网,服装书籍,服装款式网,服饰流行网,流行服装,设计手稿,矢量图案,趋势报告,款式细节,服装设计图,服装设计师,发布会,服装书籍,服装款式,服装杂志,橱窗陈列,商场实拍,2009秋冬服装发布会,2009春夏服装发布会,街头时尚,潮流时尚,秋冬款式,春夏款式,女装品牌,牛仔,皮草,裘皮,牛仔设计,印花,绣花,毛衫,毛织,毛衣,羊毛衫,职业装,高尔夫球衫,运动装,少女装,宝宝装,风衣,时装裤,休闲裤,流行图案,珠片图案,贴布绣,刺绣,布艺图案,字母图案,设计素材,印花,绣花,烫钻,流行趋势,趋势报告,流行色,面料,鞋子皮包,服装,服饰,男装,女装,童装,羽绒服,棉服,毛衣,皮装,T恤,外套,内衣,泳装,婚纱,韩国服装,日本服装,欧美服装,流行,服饰,最新服饰,流行服饰,fashion,fashion 流行网,最新流行,服装流行,女装流行网,最新时装发布会,2006年度T恤最新款式,时尚流行网,服饰流行,中国服饰流行网,流行网站,最新流行服装,情侣印花图案,最新款式服饰,服装 流行,fashion 流行網,最新流行服装,服装款式网');
        $this->assign('description', 'POP趋势资讯网是一家提供专业服装设计、专业服装书籍、的最大的服装款式图库。POP趋势资讯网有大量服装流行趋势,时装发布,T台走秀,韩国服装款式,最新流行服装款式,服装款式结构,服装款式大全,服装款式设计手稿,最新款式款式,春夏服装款式,秋冬服装款式,专门为时装、时尚产业提供网上资讯搜集、趋势分析服务；网站还包含男装、女装、童装所有的服装款式类型和趋势报告。');
        $this->assign("sBackUrl", urlencode($sBackUrl));
        $this->display('member/login.html');
    }

    public function pageLogin()
    {
        $power = memberPower();
        if (!empty($power["P_UserType"]) && $power["P_UserType"] != 5) {
            header("Location:/member/workbench/");
        }

        //登录页广告
        $aAds = $this->common_model->getAds(0, 10, 1, 0);
        $this->assign("aAds", $aAds);

        $this->assign('title', '用户登录-POP服装趋势网');
        $this->assign('keywords', '用户登录,POP趋势资讯网,国际服饰设计在线,服装设计网,服装书籍,服装款式网,服饰流行网,流行服装,设计手稿,矢量图案,趋势报告,款式细节,服装设计图,服装设计师,发布会,服装书籍,服装款式,服装杂志,橱窗陈列,商场实拍,2009秋冬服装发布会,2009春夏服装发布会,街头时尚,潮流时尚,秋冬款式,春夏款式,女装品牌,牛仔,皮草,裘皮,牛仔设计,印花,绣花,毛衫,毛织,毛衣,羊毛衫,职业装,高尔夫球衫,运动装,少女装,宝宝装,风衣,时装裤,休闲裤,流行图案,珠片图案,贴布绣,刺绣,布艺图案,字母图案,设计素材,印花,绣花,烫钻,流行趋势,趋势报告,流行色,面料,鞋子皮包,服装,服饰,男装,女装,童装,羽绒服,棉服,毛衣,皮装,T恤,外套,内衣,泳装,婚纱,韩国服装,日本服装,欧美服装,流行,服饰,最新服饰,流行服饰,fashion,fashion 流行网,最新流行,服装流行,女装流行网,最新时装发布会,2006年度T恤最新款式,时尚流行网,服饰流行,中国服饰流行网,流行网站,最新流行服装,情侣印花图案,最新款式服饰,服装 流行,fashion 流行網,最新流行服装,服装款式网');
        $this->assign('description', 'POP趋势资讯网是一家提供专业服装设计、专业服装书籍、的最大的服装款式图库。POP趋势资讯网有大量服装流行趋势,时装发布,T台走秀,韩国服装款式,最新流行服装款式,服装款式结构,服装款式大全,服装款式设计手稿,最新款式款式,春夏服装款式,秋冬服装款式,专门为时装、时尚产业提供网上资讯搜集、趋势分析服务；网站还包含男装、女装、童装所有的服装款式类型和趋势报告。');

        $this->display('member/page_login.html');
    }

    /**
     * 找回密码
     */
    public function findpassword($parameter = '')
    {
        $aParams = $parameter ? explode('-', $parameter) : array('findpassword');
        switch ($aParams[0]) {
            case 'bindingMobile':
                $sAccount = $this->input->post('account', true);
                if ($sAccount) {
                    $aUser = $this->Member_model->checkBindMobileExists($sAccount);
                    if ($aUser && $aUser['bind_mobile']) {
                        echo substr($aUser['bind_mobile'], 0, 3) . "****" . substr($aUser['bind_mobile'], -4, 4);
                        exit;
                    }
                }
                break;

            case 'findpassword_submit':
                $sact = $this->input->post('act', true);
                switch ($sact) {
                    case 'step1':
                        $saccount = $this->input->post('account', true);
                        $sbind_mobile = $this->input->post('bind_mobile', true);
                        $svalid_code = $this->input->post('valid_code', true);

                        $this->Member_model->step1($saccount, $sbind_mobile, $svalid_code);
                        break;

                    case 'childstep1':
                        $saccount = $this->input->post('account', true);
                        $svalid_code = $this->input->post('valid_code', true);

                        $this->Member_model->childstep1($saccount, $svalid_code);
                        break;

                    case 'step2':
                        $hashcode = $this->input->post('hashcode', true);
                        $id = array_pop(explode('=POP=', EncryptionDeciphering(urldecode($hashcode), false)));
                        $spass1 = $this->input->post('pass1', true);
                        $spass2 = $this->input->post('pass2', true);

                        $this->Member_model->step2($id, $spass1, $spass2);
                        break;
                }
                break;

            case 'findpassword_modify':
                break;

            case 'findpassword':        //身份验证
                $this->display('member/findpassword_1.html');
                break;

            case 'findpassword_2':      //设置新密码
                $hashcode = $this->input->get('hashcode', true);
                $this->assign('hashcode', $hashcode);
                $this->display('member/findpassword_2.html');
                break;

            case 'findpassword_3':      //修改密码完成
                $this->display('member/findpassword_3.html');
                break;
        }
    }

    public function findaccount()
    {
        $this->assign('title', '找回用户名-POP服装趋势网');
        $this->display('member/find_account.html');
    }

    /**
     * 电子合同页
     */
    public function econtract()
    {
        // 准备订单数据
        $cookie_electronic_contract = $this->input->cookie('_ELECTRONIC_CONTRACT_NEW');
        if ($cookie_electronic_contract) {
            $electronic_contract_info = EncryptionDeciphering($cookie_electronic_contract, false);
            $electronic_contract_info = explode('#POP#', $electronic_contract_info);
            $iMainUserId = $electronic_contract_info[1];
            $website = 1;
            $electronicContractinfo = $this->Member_model->getElectranicInfo($iMainUserId, $website);
            if ($electronicContractinfo) {
                $this->assign("electronicContractinfo", $electronicContractinfo);
            }
        }
        $this->display('member/econtract.html');
    }


    /**
     * 用户登录
     */
    public function doLogin($parameter = '')
    {

        $scheck_code = '';
        $shidEncryptCode = '';
        $iKeepPassword = '';
        $sisajax = '';
        $encryption = '';
        $bKipCode = false;        //是否跳过验证码 跳过：true ， 不跳过 false
        if ($parameter) {
            /* 自动登录 */
            $aParams = $parameter ? explode('-', $parameter) : array();
            $iUid = intval($aParams[0]) > 0 ? intval($aParams[0]) : 0;
            $iTime = intval($aParams[1]) > 0 ? intval($aParams[1]) : 0;
            $sHash = $aParams[2] ? strtolower(trim($aParams[2])) : '';

            if (time() - $iTime > 3600) {
                exit('The link is invalid！');
            }
            if ($sHash != strtolower(md5($iUid . POP_GLOBAL_KEYS . $iTime))) {
                exit('Hash error!');
            }
            $sSql = 'SELECT account, passwd FROM pop136.fashion_user WHERE id = ?';

            $this->load->model('POP_model');
            $aUser = $this->POP_model->query($sSql, $iUid);

            if (!$aUser) {
                exit('User not exist!');
            }
            $saccount = $aUser[0]['account'];
            $spassword = $aUser[0]['passwd'];
            $shidRandCode = 173;
            $bKipCode = true;
        } else {
            $saccount = $this->input->get_post('account', true);
            $spassword = $this->input->get_post('passwd', true);
            $scheck_code = strtolower($this->input->get_post('check_code', true));
            $shidEncryptCode = $this->input->get_post('hidEncryptCode', true);
            $shidRandCode = $this->input->get_post('hidRandCode', true);
            $iKeepPassword = $this->input->get_post('KeepPassword', true);
            $sisajax = $this->input->get_post('isajax', true);
            $encryption = $this->input->get_post('encryption', true);
            $dragtoken = $this->input->get_post('dragtoken', true);
        }
        $client_ip = $this->input->ip_address();
        $adata['hidRandCode'] = $shidRandCode;
        $adata['hidEncryptCode'] = $shidEncryptCode;
        $adata['account'] = $saccount;
        $adata['passwd'] = $spassword;
        $adata['check_code'] = $scheck_code;
        $adata['isajax'] = $sisajax;
        $adata['encryption'] = $encryption;
        $adata['is_ajax_request'] = $this->input->is_ajax_request();
        $adata['method'] = $this->input->method(false);
        $adata['client_ip'] = $client_ip;
        $adata['KeepPassword'] = $iKeepPassword;
        $adata['bKipCode'] = $bKipCode;
        $adata['dragtoken'] = $dragtoken;
        $this->Member_model->dealLogin($adata);
    }


    //手机短信验证码验证  验证成功后插入主表
    public function checkSMSCode()
    {
        $regToken = $this->input->post('regToken');//token
        $tmpId = $this->input->post('tmpId');//临时表ID
        $scellphone = $this->input->post('cellphone', true);//手机号
        $verifyCode = $this->input->post('verifyCode', true);//短信验证码
        $susername = $this->input->post('username');
        $json['code'] = '';
        $json['msg'] = 'error';
        $json['data'] = [];
        $json['info'] = [];

        if ($this->Member_model->check_user_exists($susername)) {// 此账号已经存在
            $json['code'] = 10001;
            $json['msg'] = '此用户名已存在';
            echo json_encode($json);
            die;
        }
        if (empty($scellphone) || !preg_match('/^1\d{10}$/is', $scellphone)) {//请确保输入手机号的有效性，以便领取相应活动优惠
            $json['code'] = '-3';
            echo json_encode($json);
            die;
        }

        if (strtolower(md5($tmpId . POP_GLOBAL_KEYS)) == $regToken) {
            $result = $this->Member_model->updateCellPhone($tmpId, $scellphone, $verifyCode);
            if ($result['status'] == -1) {
                $json['code'] = $result['info'];
                $json['msg'] = '短信验证码不正确';
                echo json_encode($json);
                die;
            } elseif ($result['status'] == 1) {
                echo json_encode(['code' => 0, 'msg' => '验证成功', 'data' => [], 'info' => []]);
                die;
            }
        } else {
            $json['code'] = 1;
            $json['msg'] = 'token值不正确';
            echo json_encode($json);
        }
    }


    // 提交用户注册
    public function doRegister()
    {
        $susername = $this->input->post('username', true);
        //$struename = $this->input->post('truename', true);
        $spassword = $this->input->post('password', true);
        $scellphone = $this->input->post('cellphone', true);
        $itype = $this->input->post('itype', true);

        $json['code'] = '';
        $json['msg'] = 'error';
        $json['data'] = [];
        $json['info'] = [];
        // 开始验证信息
        if (empty($susername)) {//账号不能为空
            $json['code'] = '-22';
            echo json_encode($json);
            die;
        }
        if (!$this->Member_model->verifyUserName($susername)) {
            //4-20位字符，一个汉字是两个字符
            $json['code'] = '-11';
            echo json_encode($json);
            die;
        }
        if ($this->Member_model->check_user_exists($susername)) {// 此账号已经存在
            $json['code'] = '-1';
            echo json_encode($json);
            die;
        }
        if (empty($spassword)) {//密码不能为空
            $json['code'] = '-33';
            echo json_encode($json);
            die;
        }
        if (!$this->Member_model->verifyPwd($spassword)) {// 6-20位字符，可由数字、字母、特殊字符组成
            $json['code'] = '-2';
            echo json_encode($json);
            die;
        }

        if (empty($scellphone)) {//手机号码不能为空
            $json['code'] = '-55';
            echo json_encode($json);
            die;
        }

        if (!$this->Member_model->verifyPhone($scellphone)) {//该号码已存在
            $json['code'] = '-333';
            echo json_encode($json);
            die;
        }
        if (!preg_match('/^1\d{10}$/is', $scellphone)) {//请确保输入手机号的有效性，以便领取相应活动优惠
            $json['code'] = '-3';
            echo json_encode($json);
            die;
        }

        //点击注册 写入用户临时记录表
        $struename = "";
        $result = $this->Member_model->registerTempUser($susername, $spassword, $scellphone, $itype, $struename);
        if ($result['status'] == -1) {
            // echo $result['info'];die;
            $json['code'] = '-400';
            $json['msg'] = 'error-临时表写入失败';
            echo json_encode($json);
        } else {
            $data['tmpId'] = $result['data']['id'];
            $data['regToken'] = STRTOLOWER(md5($result['data']['id'] . POP_GLOBAL_KEYS));

            echo json_encode(['code' => 0, 'msg' => 'ok-临时表写入成功', 'data' => $data, 'info' => []]);
            die;
        }
    }

    //添加更多用户信息
    public function addmoreuserinfo()
    {
        $nme = trim($this->input->get_post("name"));//姓名/联系人
        $com_name = trim($this->input->get_post("com_name"));//公司名称
        $position = trim($this->input->get_post("position"));//职位
        $com_type = $this->input->get_post("com_type");//公司类型
        $industry = $this->input->get_post("industry");//行业
        $com_type = json_decode($com_type, true);
        $industry = json_decode($industry, true);
        $id = getUserId();
        try {
            if (empty($id)) {
                throw new Exception("用户ID丢失！", 999);
            }
            if (empty($nme)) {
                throw new Exception("联系人不能为空！", 1001);
            }
            if (empty($com_name)) {
                throw new Exception("公司名称不能为空！", 1002);
            }
            if (empty($position)) {
                throw new Exception("职位不能为空！", 1003);
            }

            $memo = !empty($position) ? " 职位:{$position}" : "";
            $memo .= !empty($com_type) && is_array($com_type) ? " 公司类型:" . implode(",", $com_type) : "";
            $memo .= !empty($industry) && is_array($industry) ? " 行业:" . implode(",", $industry) : "";
            $data = compact("nme", "com_name", "memo", "id");
            $res = $this->Member_model->updateUserInfo($data);
            if ($res) {
                $result = ["code" => "0", "msg" => "OK"];
            } else {
                $result = ["code" => "1004", "msg" => "入库失败!"];
            }
        } catch (Exception $e) {
            $result = ["code" => $e->getCode(), "msg" => $e->getMessage()];
        }
        echo json_encode($result);
    }

    // 产生图片验证码
    public function imgcaptcha($params = 'verification_code')
    {
        $params = trim($params);
        $imgcaptcha = $this->imageverification->getImages($params);
        echo $imgcaptcha;
    }

    /*
     * 验证滑动验证码
     * 成功返回 token
     */
    public function slideification()
    {
        $pop_uid = $this->input->cookie("POP_UID");
        $action = $this->input->get_post("action");
        $jsonData = $this->input->get_post("jsonData");
        $this->load->library("SlideVerification");
        $result = $this->slideverification->humanCheck($jsonData, $pop_uid, $action);
        echo json_encode($result);
    }

    // 发送短信 fs
    public function sendMessage()
    {
        $sact = $this->input->post('act', true);
        switch ($sact) {
            case 'reg':
                echo 1;
                exit;
                $smobile = $this->input->post('freeCellphone', true);
                $itype = $this->input->post('itype', true);
                // $checkimg_code = $this->input->post('check_code', true);
                $is_voice = $this->input->post('is_voice');
                if ($is_voice == 'true') {
                    $this->Member_model->registerSendMessage($smobile, $itype, true);
                } else {
                    $this->Member_model->registerSendMessage($smobile, $itype);
                }
                break;

            case 'findpassword':
                $smobile1 = $this->input->post('bind_mobile', true);
                $saccount = $this->input->post('account', true);
                $is_voice = $this->input->post('is_voice');
                if ($is_voice == 'true') {
                    $this->Member_model->sendMessageForPassWord($smobile1, $saccount, true);
                } else {
                    $this->Member_model->sendMessageForPassWord($smobile1, $saccount);
                }
                break;

            case 'unbind':
                //子账号账号、解绑手机号
                $user_info = get_cookie_value();
                $unbind_mobile = $user_info['sChildAccount'];
                $this->Member_model->sendMessageForUnbind($unbind_mobile);
                break;

            case 'findchildpassword':
                //设计师专属账号密码找回发送验证码
                $saccount = $this->input->post('account', true);
                $this->Member_model->sendMessageForChildFindPassWord($saccount);
                break;
        }
    }


    // 同意电子证书
    public function confirmElectronicContract()
    {
        if ($this->input->is_ajax_request()) {
            echo $this->Member_model->confirmElectronicContract();
            exit();
        } else {
            echo 0;
            exit();
        }
    }


    // 退出登录
    public function logout()
    {
        //---------新登录方式退出start-----------------
        delete_cookie('POP_USER');
        if ($_GET["iplimit"] != 1) {
            SsoMember::setUserClientLogoutCache();//清除一个客户端缓存
        }
        //---------新登录方式退出end-------------------

        // 用户ID
        delete_cookie('userinfo_id');
        // 用户信息
        delete_cookie('user_info');
        // 专区信息
        delete_cookie('special_info');
        // 登陆IP
        delete_cookie('client_ip');
        // 会员类型
        delete_cookie('viptype');
        // 试用状态
        // delete_cookie('trailqt');
        // 记住用户名
        delete_cookie('_MemberName');
        // 保持登陆
        if (get_cookie('_KeepPassword')) {
            delete_cookie('_KeepPassword');
        }
        // 保持登陆
        if (get_cookie('verification_code')) {
            delete_cookie('verification_code');
        }
        // 索取发票
        if (get_cookie('_INVOICE_LINK')) {
            delete_cookie('_INVOICE_LINK');
        }
        // 电子合同
        if (get_cookie('_ELECTRONIC_CONTRACT_NEW')) {
            delete_cookie('_ELECTRONIC_CONTRACT_NEW');
        }
        // 账号类型主 子
        if (get_cookie('_ACCOUNT_TYPE')) {
            delete_cookie('_ACCOUNT_TYPE');
        }
        // 子账号注册层
        if (get_cookie('_ACCOUNT_CHILD_REG')) {
            delete_cookie('_ACCOUNT_CHILD_REG');
        }
        // 密码安全警告
        if (get_cookie('_PWD_WARNING')) {
            delete_cookie('_PWD_WARNING');
        }

        if (get_cookie('loginStatus')) {
            delete_cookie('loginStatus');
        }

        if (get_cookie('send_message')) {
            delete_cookie('send_message');
        }
        if (get_cookie('auotLoginHtml')) {
            delete_cookie('auotLoginHtml');
        }
        $backurls = $this->input->get('backurl');
        if (isset($backurls) && !empty($backurls)) {
            $backurl = $backurls;
        } elseif (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] != "") {
            $sHttpRefer = $_SERVER['HTTP_REFERER'];
            // 退出时不做
            if (stripos($sHttpRefer, 'member/workbench') !== false) {
                $backurl = $this->config->item('base_url');
            } // 个人中心里面的共享资料
            elseif (stripos($sHttpRefer, 'books/store/sha_trends') !== false || stripos($sHttpRefer, 'books/magazine') !== false || stripos($sHttpRefer, 'books/vector/sha_vector') !== false) {
                $backurl = $this->config->item('base_url');
            } else {
                $personal = strstr($sHttpRefer, 'personal');
                $register_complete = strstr($sHttpRefer, 'register_complete');
                $loginout = strstr($sHttpRefer, 'loginout');

                if ($personal != "" || $register_complete != "" || $loginout != "") {
                    $backurl = $this->config->item('base_url');
                } else {
                    $backurl = $sHttpRefer;
                }
            }
        } else {
            $backurl = $this->config->item('base_url');
        }
        $this->load->helper('url');
        redirect($backurl, 'location', 302);
    }

    //===============================================以下为个人中心部分=========================================================
    //我的工作台
    public function workbench()
    {
        //头部
        $this->assign('selected_flag', 3);
        $this->_fecthHeader();

        list($WorkList, $downZip, $orderZip, $focusCount, $facet_data) = $this->userCenterCountWithStatus();
        $this->assign('downZip', $downZip);
        $this->assign('orderZipCount', count($orderZip));
        $this->assign('focusCount', $focusCount);

        //获取账号下所有工作台
        $workCount = count($WorkList);
        foreach ($WorkList as $key => $val) {
            $WorkList[$key]['sWbenchName'] = htmlspecialchars($val['sWbenchName']);
            $WorkList[$key]['sWbenchDesc'] = htmlspecialchars($val['sWbenchDesc']);
            $WorkBenchPicList = array();
            //获取工作台下图片列表，显示4张
            $WorkList[$key]['imgList'] = $this->workbench->getWorkBenchPicList($this->sAccountId, $val['iWorkbenchId'], 4);
            $totalCount = count($WorkList[$key]['imgList']);
            $WorkList[$key]['totalCount'] = $totalCount;
            if ($WorkList[$key]['imgList']) {
                foreach ($WorkList[$key]['imgList'] as $k => $v) {
                    //获取每张图片详细信息
                    $pic = OpPopFashionMerger::getProductData($v['iPriId'], $v['sTableName'], $this->_refresh);
                    $WorkList[$key]['imgList'][$k] = $pic[$v['iPriId']];
                    // $path = getImagePath( $v[ 'sTableName' ] , $WorkList[ $key ][ 'imgList' ][ $k ] );
                    if (substr($v['sSmallPath'], 0, 4) == 'http') {
                        $new_https = explode('com', $v['sSmallPath']);
                        $WorkList[$key]['imgList'][$k]['smallPath'] = getStaticUrl($new_https[1]) . $new_https[1];
                    } else {
                        $WorkList[$key]['imgList'][$k]['smallPath'] = getStaticUrl($v['sSmallPath']) . $v['sSmallPath'];
                    }
                    if (substr($v['sBigPath'], 0, 4) == 'http') {
                        $new_https = explode('com', $v['sBigPath']);
                        $WorkList[$key]['imgList'][$k]['bigPath'] = getStaticUrl($new_https[1]) . $new_https[1];
                    } else {
                        $WorkList[$key]['imgList'][$k]['bigPath'] = getStaticUrl($v['sBigPath']) . $v['sBigPath'];
                    }
                }
                //图片少于4个的，用默认图片补上
                $v = 4 - $totalCount;
                for ($i = 4 - $v; $i <= 3; $i++) {
                    $WorkList[$key]['imgList'][$i]['smallPath'] = '';
                }
            } else {
                //图片少于4个的，用默认图片补上
                $WorkList[$key]['imgList'][0]['smallPath'] = '';
                $WorkList[$key]['imgList'][1]['smallPath'] = '';
                $WorkList[$key]['imgList'][2]['smallPath'] = '';
                $WorkList[$key]['imgList'][3]['smallPath'] = '';
            }
        }
        $this->assign('workCount', $workCount);
        $this->assign('WorkList', $WorkList);

        $this->assign('title', '工作台-POP服装趋势网');
        $this->assign('description', '我的工作台');
        $this->assign('keywords', '工作台');

        $this->display('personalCenter/work_bench.html');
    }

    //我的工作台内页
    public function workbenchdetail($args = '')
    {
        $this->_fecthHeader();
        $params = $this->common_model->parseParams($args, 1, false);
        $page = isset($params['page']) && !empty($params['page']) ? intval($params['page']) : 1;
        $pageSize = 35;
        $iWorkbenchId = isset($params['workid']) && !empty($params['workid']) ? intval($params['workid']) : 0;
        //获取工作台信息
        $_w = $this->workbench->getWorkBenchById($iWorkbenchId);
        $work = $_w[0];
        $work['sWbenchName'] = htmlspecialchars($work['sWbenchName']);
        $work['sWbenchDesc'] = htmlspecialchars($work['sWbenchDesc']);
        //获取工作台下图片列表
        $totalCount = 0;
        $imgList = $this->workbench->getWorkbenchPic($this->sAccountId, $iWorkbenchId, $page, $pageSize, $totalCount);
        //分页
        $pageHtml = $this->makePageHtml($params, $totalCount, $pageSize, $page, 'member/workbenchdetail/');
        if ($totalCount > 0) {
            // $offset = ($page - 1) * $pageSize;
            // $i = 0;
            foreach ($imgList as $k => $v) {
                //获取每张图片详细信息
                $pic = OpPopFashionMerger::getProductData($v['iPriId'], $v['sTableName'], $this->_refresh);
                if (substr($v['sSmallPath'], 0, 4) == 'http') {
                    // 替换  http://img1.fm.pop-fashion.com 为https://imgf1.pop-fashion.com
                    $new_https = explode('com', $v['sSmallPath']);
                    $imgList[$k]['sSmallPath'] = getStaticUrl($new_https[1]) . $new_https[1];
                } else {
                    $imgList[$k]['sSmallPath'] = getStaticUrl($v['sSmallPath']) . $v['sSmallPath'];
                }
                if (substr($v['sBigPath'], 0, 4) == 'http') {
                    // 替换  http://img1.fm.pop-fashion.com 为https://imgf1.pop-fashion.com
                    $new_https = explode('com', $v['sBigPath']);
                    $imgList[$k]['sBigPath'] = getStaticUrl($new_https[1]) . $new_https[1];
                } else {
                    $imgList[$k]['sBigPath'] = getStaticUrl($v['sBigPath']) . $v['sBigPath'];
                }
                $imgList[$k]['info'] = $pic[$v['iPriId']];
                $imgList[$k]['info']['index'] = $v['iImgId'];
                // $i++;
                $imgList[$k]['info']['iCategory'] = isset($imgList [$k]['info']['iCategory']) ? $imgList [$k]['info']['iCategory'] : 0;
                $imgList[$k]['info']['iSubcategory'] = isset($imgList [$k]['info']['iSubcategory']) ? $imgList [$k]['info']['iSubcategory'] : 0;
                $imgList[$k]['info']['brand_tid'] = isset($imgList [$k]['info']['brand_tid']) ? $imgList [$k]['info']['brand_tid'] : 0;

                // 单品
                $imgList[$k]['info']['iCategory_text'] = GetCategory::getOtherFromIds($imgList [$k]['info']['iCategory'], ['sName']);
                // $imgList[ $k ][ 'info' ][ 'iCategory_link' ] = $this->common_model->getLink( $v[ 'iColumnId' ], '', 'cat', $imgList[ $k ][ 'info' ]['iCategory'] );
                // 品名
                $imgList[$k]['info']['iSubcategory_text'] = GetCategory::getOtherFromIds($imgList [$k]['info']['iSubcategory'], ['sName']);
                // $imgList[ $k ][ 'info' ][ 'iSubcategory_link' ] = $this->common_model->getLink( $v[ 'iColumnId' ], '', 'cat', $imgList[ $k ][ 'info' ]['iSubcategory'] );
                // 季节
                if ($v['sTableName'] == 'mostrend_content') {
                    $imgList[$k]['info']['iSeason_text'] = GetCategory::getNewEnName(5, $imgList [$k]['info']['popular_time']);
                } else {
                    $imgList[$k]['info']['iSeason_text'] = GetCategory::getNewEnName(5, $imgList [$k]['info']['for_date']);
                }
                //品牌
                $imgList[$k]['info']['brandname'] = GetCategory::getBrandOtherFormId($imgList [$k]['info']['brand_tid']);
                //表名
                $imgList[$k]['info']['simpleName'] = getProductTableName($v['sTableName']);

                //---------------------------------------------------------------
                //	下载时重命名的图片名
                //---------------------------------------------------------------
                $columnPid = GetCategory::getOtherFromColId($v['iColumnId']);
                $this->load->model("details_model");
                $tableInfo = $this->details_model->getPicInfo($v['iPriId'], $v['sTableName'], $args, $v['iColumnId']);
                if (!empty($tableInfo['rename_rule'])){
                    $imgList[$k]['rename'] = 'pop_' . str_replace(' ', '_', trim($tableInfo['rename_rule'])) . '_' . $v['iPriId'] . '.' . pathinfo(basename($imgList[$k]['sBigPath']), PATHINFO_EXTENSION);
                }else{
                    $imgList[$k]['rename'] = 'pop_' . str_replace(' ', '_', trim($tableInfo['title'])) . '_' . $v['iPriId'] . '.' . pathinfo(basename($imgList[$k]['sBigPath']), PATHINFO_EXTENSION);
                }
            }
        }


        //AES_KEY
        $this->load->model('Secret_model');
        list($symmetricKey, $timeStamp) = $this->Secret_model->init_data();
        $token = $this->Secret_model->get_token();

        $this->assign('token', $token);//token(uid_popuid_secret)
        $this->assign('sign', $symmetricKey);//AES_KEY
        $this->assign('timeStamp', $timeStamp);//生成AES_KEY的时间戳

        //个人中心我的工作台标识
        $this->assign('isUc', 1);

        $this->assign('page', $page);
        $this->assign('imgList', $imgList);
        $this->assign('work', $work);
        $this->assign('total', $totalCount);
        $this->assign('pageHtml', $pageHtml);
        $this->assign('title', '工作台-POP服装趋势网');
        $this->assign('description', '我的工作台');
        $this->assign('keywords', '工作台');
        $this->display('personalCenter/per_style.html');
    }

    //我的下载包
    public function mydownload()
    {
        //头部
        $this->assign('selected_flag', 4);
        $this->_fecthHeader();

        list($WorkList, $downZip, $orderZip, $focusCount) = $this->userCenterCountWithStatus();
        //获取账号下所有下载包
        foreach ($downZip as $key => $val) {
            $downZip[$key]['sWbenchName'] = htmlspecialchars($val['sWbenchName']);
            $downZip[$key]['sWbenchDesc'] = htmlspecialchars($val['sWbenchDesc']);
        }


        $this->assign('WorkList', $WorkList);
        $this->assign('downZip', $downZip);
        $this->assign('focusCount', $focusCount);// 收藏量
        $this->assign('orderZipCount', count($orderZip));

        $this->assign('title', '工作台-POP服装趋势网');
        $this->assign('description', '我的工作台');
        $this->assign('keywords', '工作台');

        $this->display('personalCenter/my_down.html');
    }

    //我的收藏
    public function mycollect()
    {
        //头部
        $this->assign('selected_flag', 2);
        $this->_fecthHeader();

        list($WorkList, $downZip, $orderZip, $focusCount, $facet_data) = $this->userCenterCountWithStatus();
        $this->assign('WorkList', $WorkList);
        $this->assign('downZip', $downZip);
        $this->assign('orderZipCount', count($orderZip));

        $count['trend'] = $facet_data[1];//未来趋势
        $count['analysis'] = $facet_data[2];//潮流解析
        $count['prescon'] = $facet_data[3];//发布会
        $count['style'] = $facet_data[4];//款式
        $count['brand'] = $facet_data[5];//品牌 iColumnId=5
        $count['book'] = array_sum([$facet_data[70], $facet_data[72], $facet_data[114], $facet_data[115]]);//手稿合辑
        $count['lookbook'] = $facet_data[71];//lookbook
        $count['ordermeeting'] = $facet_data[131];//订货会合辑
        $count['patterns'] = array_sum([$facet_data[82], $facet_data[120], $facet_data[124]]);//图案库
        $count['material'] = array_sum([$facet_data[80], $facet_data[81], $facet_data[85], $facet_data[117], $facet_data[8]]);//设计素材
        $count['accessories'] = $facet_data[84];// 服饰品 单独拿出去

        if ($facet_data[82] > 0) {
            $patternsUrl = '/patterns/graphics/coll_2/#anchor';
        } else {
            $patternsUrl = '/patterns/topbrands/coll_2/#anchor';
        }
        $this->assign('patternsUrl', $patternsUrl);

        //设计素材链接
        $materialUrl = '/references/visual/coll_2/#anchor';
        if ($facet_data[8] > 0) {
            $materialUrl = '/inspiration/report/coll_2/#anchor';
        } elseif ($facet_data[80] > 0) {
            $materialUrl = '/references/design/coll_2/#anchor';
        } elseif ($facet_data[81] > 0) {
            $materialUrl = '/references/details/coll_2/#anchor';
        } elseif ($facet_data[117] > 0) {
            $materialUrl = '/references/fabricgallery/coll_2/#anchor';
        }
        $this->assign('materialUrl', $materialUrl);

        $this->assign('focusCount', $focusCount);// 收藏量
        $this->assign('count', $count);

        $this->assign('title', '我的收藏-POP服装趋势网');
        $this->assign('description', '我的收藏');
        $this->assign('keywords', '我的收藏');
        $this->display('personalCenter/my_collect.html');
    }

    //我的收藏详情
    public function collectlist($args = '')
    {
        //头部
        $this->_fecthHeader();
        $params = $this->common_model->parseParams($args, 1, false);
        $column = isset($params['column']) && !empty($params['column']) ? $params['column'] : '';
        $page = isset($params['page']) && !empty($params['page']) ? intval($params['page']) : 1;
        switch ($column) {

            case 'brand':
            default:
                $pageSize = 20;
                //获取此收藏下总数
                $brandCount = $this->workbench->getCollectCount($this->sAccountId, 2, $page);
                $total = $brandCount['total'];
                $type = 2;
                //获取此收藏下列表
                $brandList = $this->workbench->getCollect($this->sAccountId, 2, $page, $pageSize);
                $this->load->model('brands_model');
                foreach ($brandList as $key => $val) {
                    $brandList[$key]['info'] = $this->workbench->getPic($val['sDatabase'], $val['sTableName'], $val['iPriId']);
                    $brandList[$key]['info']['simpleName'] = getProductTableName($val['sTableName']);

                    //品牌资料最新更新时间
                    $brandInfo = OpPopFashionMerger:: getBrandData(intval($val['iPriId']));
                    $sLastUpdateTime = isset($brandInfo['dStyleReportUpdateTime']) && $brandInfo['dStyleReportUpdateTime'] ? $brandInfo['dStyleReportUpdateTime'] : '1970-01-01 07:00:00';
                    $sNewUpdate = strtotime($sLastUpdateTime) - strtotime($this->sLastLogionTime);
                    //是否大于上次登录时间
                    if ($sNewUpdate > 0) {
                        $brandList[$key]['info']['sNewUpdateClass'] = ' new_information';
                    } else {
                        $brandList[$key]['info']['sNewUpdateClass'] = '';
                    }
                    $brandList[$key]['info']['sLastUpdateTime'] = date('Y/m/d', strtotime($sLastUpdateTime));

                }
                //分页
                $pageHtml = $this->makePageHtml($params, $total, $pageSize, $page, 'member/collectlist/');
                $this->assign('pageHtml', $pageHtml);
                $this->assign('brandList', $brandList);
                $this->assign('total', $total);
                $this->assign('nowcount', count($brandList));
                $this->assign('type', $type);
                $this->assign('title', '品牌收藏-POP服装趋势网');
                $this->assign('description', '品牌收藏');
                $this->assign('keywords', '品牌收藏');
                $this->display('personalCenter/per_brand.html');
                break;
        }
    }

    //个人中心头部
    private function _fecthHeader($type = '')
    {
        $this->arrCookie = get_cookie_value();
        if (!$this->arrCookie) {
            $this->load->helper('url');
            header("Location:/");
            exit;
        }
        $this->iAccountType = intval($this->arrCookie['iAccountType']);

        //账号显示时间
        //array(账号类型P_UserType => 1 主账号是vip , 2子账号是vip 、3试用 4普通 5游客);
        $vipTypeArr = memberPower('other');

        if ($this->iAccountType == 1) {

            // die('该功能只对子账号开放！');
            $id = intval($this->arrCookie['id']);
            $this->AccountInfo = $this->Member_model->getUserById($id);

            $this->assign('columnPid', 0);
            $this->sAccountId = intval($this->arrCookie['id']);
            $avatarUri = $this->Member_model->getAvatar($this->sAccountId);
            //用户类型
            $this->workbench->_getAccountType($this->iAccountType);
            //会员类型
            $this->workbench->_getVipType($vipTypeArr);
            //会员权限
            $this->workbench->_getVipPrivilege($vipTypeArr);

            $sectionFlag = $this->uri->segment(2);

            $avatarUri = $this->Member_model->getAvatar($this->sAccountId);

            $this->assign('accountType', $this->iAccountType);
            $this->assign('avatarUri', $avatarUri['sAvatar']);
            $this->assign('userType', $vipTypeArr['P_UserType']);

            $this->assign('sectionFlag', $sectionFlag);
            $this->assign('user', $this->AccountInfo);
            $header = $this->fetch('personalCenter/per_Header.html');
            $this->assign('personalHeader', $header);
            //TDK
            $this->assign('title', '工作台-POP服装趋势网');
            $this->assign('description', '我的工作台');
            $this->assign('keywords', '工作台');

            if (!$type) {//浏览历史记录
                $this->display('personalCenter/work_bench_nopower.html');
                exit;
            }

        } else {
            //子账号
            $condition = array();

            $this->sAccountId = $this->arrCookie['sChildID'];
            $avatarUri = $this->Member_model->getAvatar($this->sAccountId);

            $condition['sChildID'] = $this->sAccountId;
            $this->AccountInfo = $this->Member_model->getChildInfo($condition, 'base');
            $childInfo = $this->Member_model->getChildInfo($condition);

            $Pid = $this->arrCookie['id'];
            $this->mainAccountInfo = $this->Member_model->getUserById($Pid);

            $this->AccountInfo['account'] = $childInfo['sChildAccount'];
            $this->AccountInfo['create_time'] = $childInfo['dCreateTime'];
            $this->AccountInfo['sChildID'] = $childInfo['sChildID'];
            $this->AccountInfo['flag'] = $this->mainAccountInfo['flag'];
            $this->AccountInfo['vip_type'] = $this->mainAccountInfo['vip_type'];
            $this->AccountInfo['bao_date_end'] = $this->mainAccountInfo['bao_date_end'];
            $this->assign('columnPid', 0);

            //用户类型
            $this->workbench->_getAccountType($this->iAccountType);
            //会员类型
            $this->workbench->_getVipType($vipTypeArr);
            //会员权限
            $this->workbench->_getVipPrivilege($vipTypeArr);

            $flag = $this->uri->segment(2);

            $this->assign('accountType', $this->iAccountType);
            $this->assign('avatarUri', $avatarUri['sAvatar']);

            $this->assign('user', $this->AccountInfo);
            $header = $this->fetch('personalCenter/per_Header.html');
            $this->assign('personalHeader', $header);
        }
        //获取上次登录时间
        $this->sLastLogionTime = $this->workbench->getLastLogionTime($this->sAccountId, $this->iAccountType);
    }


    //===============================================以下为账号设置部分=========================================================
    // --------------------------类属性初始化--------------------------------------------
    private function init($headerType = 0)
    {
        //通过cookie获取存储的用户信息，并区分用户账号类型
        $this->arrCookie = get_cookie_value();
        if (empty($this->arrCookie)) {
            header("Location:/member/pagelogin/");
            exit;
        }
        $this->iAccountType = intval($this->arrCookie['iAccountType']);
        if ($this->iAccountType == '1') {
            //主账号
            $id = intval($this->arrCookie['id']);
            $this->sAccountId = $id;
            $this->mainAccountInfo = $this->Member_model->getUserById($id);

            //个人中心头部及左侧菜单
            $this->_fecthHeaderLeftMenu($this->mainAccountInfo, $personalHeader, $personalLeftMenu, $headerType);
            $this->assign('personalHeader', $personalHeader);
            $this->assign('personalLeftMenu', $personalLeftMenu);
        } elseif ($this->iAccountType == '2') {
            //子账号
            $this->sAccountId = $this->arrCookie['sChildID'];
            $condition = array();
            $condition['sChildID'] = $this->sAccountId;
            $this->childAccountInfo = $this->Member_model->getChildInfo($condition, 'base');
            $childInfo = $this->Member_model->getChildInfo($condition);

            $Pid = $this->arrCookie['id'];
            $this->mainAccountInfo = $this->Member_model->getUserById($Pid);

            $this->childAccountInfo['account'] = $childInfo['sChildAccount'];
            $this->childAccountInfo['create_time'] = $childInfo['dCreateTime'];
            $this->childAccountInfo['sChildID'] = $childInfo['sChildID'];
            $this->childAccountInfo['flag'] = $this->mainAccountInfo['flag'];
            $this->childAccountInfo['vip_type'] = $this->mainAccountInfo['vip_type'];
            $this->childAccountInfo['bao_date_end'] = $this->mainAccountInfo['bao_date_end'];
            $this->childAccountInfo['parent_account'] = $this->mainAccountInfo['account'];

            //个人中心头部及左侧菜单
            $this->_fecthHeaderLeftMenu($this->childAccountInfo, $personalHeader, $personalLeftMenu, $headerType);
            $this->assign('personalHeader', $personalHeader);
            $this->assign('personalLeftMenu', $personalLeftMenu);
        }
    }

    /*--------------------------子账号解绑 start------------------------------------------*/
    public function unbind()
    {
        $this->init();
        if ($this->accountVipType == 1) {
            echo "<script language=javascript>";
            echo "alert('对不起，只有子账号能访问！');window.location.href='/member/base/';";
            echo "</script>";
        }
        if (!get_cookie('send_message')) {
            $scookie_send_message_val = EncryptionDeciphering(serialize(array("last_time" => date('y-m-d H:i:s', time() - 7200), "count" => "0")), true);
            $this->input->set_cookie('send_message', $scookie_send_message_val, 7200);
        }
        $this->assign("childAccount", $this->childAccountInfo['account']);
        $this->assign("parent_account", $this->childAccountInfo['parent_account']);
        $this->display('personalCenter/unbind.html');
    }

    //执行子账号结案个操作
    public function dounbind()
    {
        $this->init();
        $sverifyCode = $this->input->post('verifyCode', true);
        // 子账号解绑
        $result = $this->Member_model->doUbind($this->childAccountInfo['sChildID'], $this->childAccountInfo['account'], $sverifyCode);

        if ($result['status'] == -1) {
            echo json_encode($result['info']);
            // exit;
        } else {
            $this->Member_model->bindChildAccountOperateLog('子账号操作-解除绑定', $this->mainAccountInfo['id'],  $this->childAccountInfo['account']);
            echo json_encode($result['status']);
        }
    }

    //未绑定主账号子账号登录时页面
    public function nobind()
    {
        $backurl = $this->input->get('backurl');
        // $params = $this->common_model->parseParams( $params ,1 ,FALSE );
        $backurl = isset($backurl) && $backurl ? '/' . $backurl : '';
        $http_referer = $backurl && !strpos($backurl, 'nobind') ? $backurl : '';
        $http_referer = urldecode($http_referer);
        if ($this->input->cookie('user_info')) {
            $this->load->helper('url');
            redirect($http_referer);
        }
        $this->display('personalCenter/nobind.html');
    }
    /*--------------------------子账号解绑 end------------------------------------------*/

    // --------------------------基本信息的修改------------------------------------------
    public function software()
    {
        $this->assign('selected_flag', 7);
        $this->init();
        $this->display('personalCenter/software.html');
    }

    //基本信息
    public function base()
    {
        $this->assign('selected_flag', 7);
        $this->init();
        //1、代表主账号 2、代表子账号
        if ($this->iAccountType == '2') {
            $this->_childAccount();
        } else {
            $this->_mainAccount();
        }
    }

    //主账号
    private function _mainAccount()
    {
        //职业
        $career = [];
        $career[] = '服装设计师';
        $career[] = '服装生产销售人员';
        $career[] = '模特';
        $career[] = '从事摄影艺术者';
        $career[] = '时尚爱好者';
        $career[] = '其它';
        //涉及领域
        $domain = [];
        $domain[] = ['id' => 1, 'value' => '男装', 'isChecked' => ''];
        $domain[] = ['id' => 2, 'value' => '女装', 'isChecked' => ''];
        $domain[] = ['id' => 3, 'value' => '童装', 'isChecked' => ''];
        $domain[] = ['id' => 4, 'value' => '婴儿装', 'isChecked' => ''];
        $domain[] = ['id' => 5, 'value' => '鞋帽', 'isChecked' => ''];
        $domain[] = ['id' => 6, 'value' => '饰品', 'isChecked' => ''];
        $domain[] = ['id' => 7, 'value' => '其他', 'isChecked' => ''];

        //职业及涉及领域
        $domainVal = explode(',', $this->mainAccountInfo['feld']);
        for ($i = 0; $i < count($domainVal); $i++) {
            foreach ($domain as $key => $val) {
                if ($domainVal[$i] == $val['value']) {
                    $domain[$key]['isChecked'] = 'checked';
                }
            }
        }
        $this->assign('domain', $domain);
        $this->assign('career', $career);

        if ($this->arrCookie['flag'] == 1) {//企业账号
            $this->assign('accountFlag', '1');
        } else {//个人账号
            $this->assign('accountFlag', '0');
        }
        $this->assign('country', $this->mainAccountInfo['country']);
        $this->assign('province', $this->mainAccountInfo['province']);
        $this->assign('city', $this->mainAccountInfo['city']);
        $this->assign('com_type', $this->mainAccountInfo['com_type']);
        $this->assign('user', $this->mainAccountInfo);
        $this->display('personalCenter/mainAccount.html');
    }

    //子账号
    private function _childAccount()
    {
        //公司职务
        $position = $this->Member_model->getPositions(3);

        $this->assign("childAccount", $this->childAccountInfo['account']);
        $this->assign("sChildID", $this->childAccountInfo['sChildID']);
        $this->assign("sName", $this->childAccountInfo['sName']);
        $this->assign("sGender", $this->childAccountInfo['sGender']);
        $this->assign("sEmail", $this->childAccountInfo['sEmail']);
        $this->assign("sCompanyName", $this->childAccountInfo['sCompanyName']);
        $this->assign("iPositionsID", $this->childAccountInfo['iPositionsID']);
        $this->assign("position", $position);

        $this->display('personalCenter/childAccount.html');
    }


    //个人中心头部及左侧菜单
    private function _fecthHeaderLeftMenu($accountInfo, &$header, &$leftMenu, $headerType = 0)
    {
        //array(账号类型P_UserType => 1 主账号是vip , 2子账号是vip 、3试用 4普通 5游客);
        $vipTypeArr = memberPower('other');
        $this->accountVipType = $vipTypeArr['P_UserType'];

        //用户类型
        $this->workbench->_getAccountType($this->iAccountType);
        //会员类型
        $this->workbench->_getVipType($vipTypeArr);
        //会员权限
        $this->workbench->_getVipPrivilege($vipTypeArr);

        $flag = $this->uri->segment(2);
        $flag = strtolower($flag);
        switch ($flag) {
            case 'pwd':
                $LeftManageMenuHtml = 1;
                break;
            case 'mobile':
                $LeftManageMenuHtml = 2;
                break;
            case 'associate':
                $LeftManageMenuHtml = 3;
                break;
            case 'integral':
                $LeftManageMenuHtml = 4;
                break;
            case 'invoice':
                $LeftManageMenuHtml = 5;
                break;
            case 'sharing':
                $LeftManageMenuHtml = 6;
                break;
            case 'share':
                $LeftManageMenuHtml = 7;
                break;
            case 'software':
                $LeftManageMenuHtml = 8;
                break;
            case 'unbind':
                $LeftManageMenuHtml = 9;
                break;
            case 'moddefaultlogin':
                $LeftManageMenuHtml = 10;
                break;
            default :
                $LeftManageMenuHtml = 0;
                break;
        }

        $aCookieUserInfo = get_cookie_value();

        //既是主账号又是VIP  才有  索取发票及子账号管理菜单
        if ($this->iAccountType == 1 && $this->accountVipType == 1) {
            $invoiceLink = $this->_invoice();
            $this->assign('invoiceLink', $invoiceLink);
            $this->assign('vipflag', 1);
            //获取头像
            $avatarUri = $this->Member_model->getAvatar($this->arrCookie['id']);
        } elseif ($this->iAccountType == 2) {//子账号
            if ($this->accountVipType == 2 || $this->accountVipType == 3) {//未过期的
                $this->assign('vipflagShare', 3);
            }
            $this->assign('vipflag', 2);
            $avatarUri = $this->Member_model->getAvatar($this->arrCookie['sChildID']);
        }

        $this->assign('accountType', $this->iAccountType);
        $this->assign('avatarUri', $avatarUri['sAvatar']);

        $this->assign('LeftManageMenuHtml', $LeftManageMenuHtml);
        $this->assign('user', $accountInfo);

        $header = $this->fetch('personalCenter/per_Header.html');
        // if ($headerType == 1) {
        //     $header = $this->fetch('personalCenter/per_Header.html');
        // } else {
        //     $header = $this->fetch('personalCenter/personalHeader.html');
        // }
        $leftMenu = $this->fetch('personalCenter/personalLeftMenu.html');
    }


    //修改基本信息
    public function modInfo()
    {
        $this->init();
        $post = $this->input->post(null, true);
        //print_r($post);
        $post = array_map(function ($argument) {
            if (is_numeric($argument)) {
                return $argument;
            } elseif (is_array($argument)) {
                return $argument;
            } else {
                return $this->common_model->db()->escape_like_str(htmlspecialchars($argument));
            }
        }, $post);
        //1、代表主账号 2、代表子账号
        if ($this->iAccountType == '2') {
            $this->_modChildAccount($post);
        } else {
            $this->_modMainAccount($post);
        }
    }

    //修改主账户信息
    private function _modMainAccount($data)
    {
        $userData = array();
        if ($this->arrCookie['flag'] == 1) {//企业账号
            $userData['com_web'] = $data['com_web'];
        } else {//个人账号
            $userData['qqmsn'] = $data['qqmsn'];
        }
        $userData['country'] = $data['country'];
        $userData['province'] = $data['province'];
        $userData['city'] = $data['city'];
        $userData['com_fax'] = $data['com_fax'];
        $userData['address'] = $data['address'];
        $userData['tel'] = $data['tel'];
        $userData['mobile'] = $data['mobile'];
        $userData['com_type'] = $data['com_type'];
        $userData['feld'] = !empty($data['feld']) ? implode(',', $data['feld']) : null;

        //邮箱修改
        $userData['email'] = $data['sEmail'];

        $arrArea = $this->Member_model->getAllArea();
        $area_id = 0;
        // 匹配省
        for ($i = 0; $i < count($arrArea); $i++) {
            if ($data['province'] == $arrArea[$i]["nme"]) {
                $area_id = $arrArea[$i]["id"];
            }
        }
        // 省没有匹配成功的话匹配城市
        if (!$area_id) {
            for ($i = 0; $i < count($arrArea); $i++) {
                if ($data['city'] == $arrArea[$i]["nme"]) {
                    $area_id = $arrArea[$i]["id"];
                }
            }
        }
        // 前面没有匹配成功的话 匹配固定电话区号
        if (!$area_id) {
            for ($i = 0; $i < count($arrArea); $i++) {
                if (!empty($arrArea[$i]["tel_pre"]) && strpos($data['tel'], $arrArea[$i]["tel_pre"]) === 0) {
                    $area_id = $arrArea[$i]["id"];
                }
            }
        }
        // 都没有的划入 其他区域
        if (!$area_id) {
            for ($i = 0; $i < count($arrArea); $i++) {
                if ($arrArea[$i]["nme"] == "其他") {
                    $area_id = $arrArea[$i]["id"];
                }
            }
        }
        $userData["area_id"] = $area_id;

        $condition = array();
        $condition['id'] = $data['id'];

        $result = $this->Member_model->modMainAccountDate($userData, $condition);

        if ($result) {
            echo 'success';
        } else {
            echo '很抱歉，修改失败！';
        }
    }

    //修改子账户信息
    private function _modChildAccount($data)
    {
        if (!preg_match('/^\\w+((-\\w+)|(\\.\\w+))*\\@[A-Za-z0-9]+((\\.|-)[A-Za-z0-9]+)*\\.[A-Za-z0-9]+$/', $data['sEmail'])) {
            echo '邮箱格式错误！';
        }

        $userData = array();
        $userData['sName'] = $data['sName'];
        $userData['sGender'] = $data['sGender'];
        $userData['sEmail'] = $data['sEmail'];
        $userData['sCompanyName'] = $data['sCompanyName'];
        $userData['iPositionsID'] = $data['iPositionsID'];
        $userData['sChildID'] = $data['sChildID'];

        $condition = array();
        $condition['sChildID'] = $data['sChildID'];

        $result = $this->Member_model->modChildAccountDate($userData, $condition, 'base');

        if ($result) {
            echo 'success';
        } else {
            echo '很抱歉，修改失败！';
        }
    }

    //修改密码-页面
    public function pwd()
    {
        $this->assign('selected_flag', 7);
        $this->init();
        $this->display('personalCenter/editorPwd.html');
    }

    //修改密码-业务
    public function modPwd()
    {
        $this->init();
        //通过cookies获取用户信息 确认用户是否已经登录
        if (!empty($this->arrCookie)) {

            $post = $this->input->post();
            $newPassword = trim($post['newPassword']);
            $oldPassword = trim($post['oldPassword']);

            if ($newPassword == trim($post['reNewPassword'])) {//密码确认成功
                if (!preg_match('/^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$/im', $newPassword)) {
                    echo '密码格式不正确! 6-20位字符，可由数字、字母、特殊字符组成！';
                }

                $modResult = '';
                //判断主账号或是子账号
                if ($this->iAccountType == '2') {//子账号
                    $sChildID = $this->childAccountInfo['sChildID'];
                    // 验证旧密码
                    if ($oldPassword) {
                        $condition = array();
                        $condition['sChildID'] = $sChildID;
                        $condition['sPassword'] = $oldPassword;
                        $result = $this->Member_model->getChildInfo($condition);
                        if (empty($result)) {
                            echo '旧密码不正确！';
                        } else {
                            $res = $this->Member_model->checkMainPassword($result["sChildAccount"], $newPassword);
                            if ($res) {
                                echo '对不起，该密码不符合系统要求，请尝试设置其它密码！';
                            } else {
                                $data = array();
                                $data["sPassword"] = $newPassword;
                                $condition = array();
                                $condition["sChildID"] = $sChildID;
                                $modResult = $this->Member_model->modChildAccountDate($data, $condition);
                            }
                        }
                    }
                } else {//主账号
                    //验证旧密码
                    $result = $this->Member_model->checkMainPassword($this->mainAccountInfo['account'], $oldPassword);
                    if ($result) {
                        //旧密码通过，去修改所有站点账号密码
                        $id = $this->mainAccountInfo['id'];

                        $modResult = $this->Member_model->globalModifyPwd($id, $newPassword);
                    } else {
                        echo '旧密码不正确！';
                    }
                }
                //加载cookie辅助函数
                $this->load->helper('cookie');
                //密码复杂度
                if (preg_match('/^[\d]+$/i', $newPassword) || preg_match('/^[a-zA-Z]+$/i', $newPassword)) {
                    // 纯数字或纯字母:低;
                    //set_cookie('_PWD_WARNING',1,0);
                } else {
                    // 数字+字母或+其他:中 高;
                    //密码安全警告消除
                    if ($_COOKIE['_PWD_WARNING']) {
                        //set_cookie( '_PWD_WARNING','',time()-3600);
                    }
                }
                if ($modResult) {
                    clear_login_user_cache();
                    echo 'success';
                }
            } else {
                echo '两次密码输入不一致！';
            }
        } else {
            echo '用户未登录，请先登录！';
        }
    }

    private function clearSsoLogin()
    {
        get_cookie_value();
    }

    //绑定手机-页面
    public function mobile()
    {
        $this->assign('selected_flag', 7);
        $this->init();
        $userId = getUserId();
        $sql = "SELECT login_phone FROM `pop136`.`fashion_user` WHERE id=?";
        $query = $this->Member_model->db()->query($sql, [$userId]);
        $result = $query->row_array();
        $mobile = !empty($result["login_phone"]) ? $result["login_phone"] : "";
        //处理手机号
        if (!empty($mobile)) {
            $mobile = substr($mobile, 0, 3) . '*****' . substr($mobile, -3);
        }
        //已绑定手机号码
        $this->assign('bind_mobile', $mobile);
        $this->display('personalCenter/bindMoblile.html');
    }

    //绑定手机-业务 仅针对主账户生效
    public function modBind()
    {
        $this->init();
        //判断是否已经登录
        if (!empty($this->arrCookie)) {
            $post = $this->input->post();
            $newMobileNo = $post['newMobile'];
            $checkCode = $post['checkCode'];
            $id = $this->arrCookie['id'];

            $condition = array();
            $condition['id'] = $id;
            $condition['check_code'] = $checkCode;

            //获取验证码 根据验证码和用户id去数据库查询，如果有返回值，说明验证码通过
            $result = $this->Member_model->getAccountInfo($condition);
            if (!empty($result)) {
                if ($newMobileNo) {//判断电话号码
                    $result = $this->Member_model->globalModifyBindMobile($id, $newMobileNo);
                    if ($result) {
                        echo 'success';
                    } else {
                        echo '绑定失败，请重试！';
                    }
                } else {
                    echo '请输入正确的手机号码';
                }
            } else {
                echo '验证码错误';
            }
        } else {
            echo '您未登录，请先登录';
        }
    }

    //绑定手机--获取验证码
    public function sendCodeForBind()
    {
        $this->init();
        $id = $this->arrCookie['id'];
        $condition = array();
        $condition['id'] = $id;

        //获取验证码
        $result = $this->Member_model->getAccountInfo($condition, '`bind_mobile`,`check_code`,`check_time`');
        $result = $result[0];
        $bind_mobile = $result['bind_mobile'];
        $check_time = strtotime($result['check_time']);
        if ($bind_mobile == "") {
            echo "对不起！您尚未绑定手机，无法进行此操作。";
        } else {
            if (time() > ($check_time + 60 * 60) || $result['check_time'] == "") {
                //生成验证码，
                $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';
                $returnStr = '';
                for ($i = 0; $i < 6; $i++) {
                    $returnStr .= $pattern{mt_rand(0, 61)}; //生成php随机数
                }
                $MessageConfig = array(
                    'templateId' => 45371, // 模版id
                    'appId' => 2,       //应用id
                    'params' => array($returnStr), //模版参数
                    'to' => strval($bind_mobile) //手机号码
                );
                $rs = MessageInterface::sendMessage($MessageConfig);
                //查日志
                if ($rs == 200) {
                    echo "短信发送成功!";
                    //入库
                    $data = array();
                    $data['check_code'] = $returnStr;
                    $data['check_time'] = date("Y-m-d H:i:s", time());
                    $this->Member_model->modMainAccountDate($data, $condition);
                } else {
                    echo "短信发送失败,请联系客服。";
                }
            } else {
                echo "对不起！一小时之内您只能获取一次验证码。";
            }
        }
    }

    //索取发票
    private function _invoice()
    {
        $sIdPopAccount = $this->mainAccountInfo['id'] . '#POP#' . $this->mainAccountInfo['account'];
        $website = 'fashion';
        //发票链接
        $sInvoiceLink = 'http://www.pop136.com/invoice/index.php?website=' . $website . '&info=' . base64_encode(base64_encode($sIdPopAccount)) . '&hash=' . MD5($sIdPopAccount . POP_GLOBAL_KEYS . $website);
        return $sInvoiceLink;
    }

    // --------------------------关联账号------------------------------------------
    //关联账号管理首页
    public function associate($args = '')
    {
        $this->assign('selected_flag', 7);
        $this->init();
        if ($this->accountVipType != 1) {
            echo "<script language=javascript>";
            echo "alert('对不起，您的操作权限不够！');window.location.href='/member/base/';";
            echo "</script>";
        }
        $pageSize = 10;
        $totalCount = 0;
        $iParentID = $this->arrCookie['id'];
        $condition = array();
        $condition['iParentID'] = $iParentID;
        $condition['iStatus'] = 1;
        //获取主账号允许开通子账号数量、当前开通子账号数量和总积分数
        $result = $this->Member_model->getMainAccountLicenseIntegral($iParentID);
        // 注意参数$params需要做安全处理,cat_2-sea_3-gen_1-ind_2
        $params = $this->common_model->restoreParams($args);
        $search = $this->common_model->getSearchSufix();
        $rootUrl = '/member/associate/';
        $link = ['url' => $rootUrl, 'param' => $params, 'search' => $search];
        $params = $this->common_model->parseParams($params, 1, false);
        //当前页码
        $page = isset($params['page']) && !empty($params['page']) ? intval($params['page']) : 1;
        //账号或姓名搜索
        $keyword = $this->common_model->getKeyword($params, $powers);
        //获取子账号列表
        $childList = $this->Member_model->getChildList($condition, $totalCount, $page, $pageSize, $keyword);
        //分页
        $pageHtml = $this->makePageHtml($params, $totalCount, $pageSize, $page, 'member/associate/');
        $this->assign('childList', $childList);
        $this->assign('keyword', $keyword);
        $this->assign('link', $link);
        $this->assign('childListAll', $this->Member_model->ChildList);
        $this->assign('iIntegralTotalNumber', $result['iIntegralTotalNumber']);//可用积分
        $this->assign('iLicenseNumber', $result['iLicenseNumber']);//可以绑定
        $this->assign('iNowLicense', $result['iNowLicense']);//已绑定
        $this->assign('page', $page);//用来生成序号
        $this->assign('pageSize', $pageSize);
        $this->assign('pageHtml', $pageHtml);
        $this->display('personalCenter/accountList.html');
    }

    //添加个人账号
    public function addChild()
    {
        $this->init();
        //通过提交 - 获取账号及验证码
        $code = $this->input->post('code');
        $count = $this->input->post('count');
        $sName = trim($this->input->post('sur', true)) . trim($this->input->post('name', true));
        $Flag = $this->Member_model->verifyPhoneCode($count, $code);

        if ($Flag['status'] != 1) {
            $data['success'] = 0;
            $data['msg'] = '验证码错误';
            echo json_encode($data);
            exit;
        }

        //绑定的子账号数量
        $iParentID = $this->arrCookie['id'];
        $condition = array();
        $condition['iParentID'] = $iParentID;
        $condition['iStatus'] = 1;
        $this->Member_model->getChildList($condition, $totalCount);

        if ($this->Member_model->ChildAll >= $this->Member_model->UserInfo['iLicenseNumber']) {
            $data['success'] = 0;
            $data['msg'] = '个人账号数量已满';
            echo json_encode($data);
            exit;
        }
        $json = $this->Member_model->createChildAccount($this->Member_model->UserInfo['id'], $count, $code, $sName);
        switch ($json['status']) {
            case 1:
                $data['success'] = 1;
                $data['msg'] = '添加成功';
                $data['count'] = $count;
                $data['pwd'] = $code;
                break;
            case -1:
                $data['success'] = 0;
                $data['msg'] = '添加失败';
                break;
            case -2 :
                $data['success'] = 0;
                $data['msg'] = '密码格式不正确';
                break;
            case -3 :
                //账号密码在主账号中已存在
                $data['success'] = 0;
                $data['msg'] = '对不起，该密码不符合系统要求，请尝试设置其它密码';
                break;
        }
        echo json_encode($data);
        exit;
    }

    //发送手机验证码
    public function sendCode()
    {
        $this->init();
        $post = $this->input->post();
        $this->load->driver('cache');
        $phone = isset($post['phone']) && !empty($post['phone']) ? intval($post['phone']) : '';
        if (empty($phone)) {
            $data['success'] = 0;
            $data['msg'] = '请填写需要添加的个人专属账号';
            echo json_encode($data);
            exit;
        }
        $user_id = getUserId();

        $mem_key = "fashion_member_model_send_code_".$user_id;
        $send_times_arr = $this->cache->memcached->get($mem_key);
        $now_time = time();
        $exp_time = 1800; //过期时间
        if(!empty($send_times_arr)){
            $send_times = (int)$send_times_arr['send_times']+1;
            if(time()-$send_times_arr['time']>$exp_time){
                $send_times_arr = array();
            }
        }
        if(empty($send_times_arr))
        {
            $send_times = 1;
            //发送时间半小时重新设置一次，发送次数半小时内累加
            $send_times_arr = array('time'=>$now_time,'send_times'=>$send_times);
        }
		if (empty($user_id)) {
            $data['success'] = 1;
            $data['msg'] = '请先登录';
            echo json_encode($data);
            exit;
        }
        if($send_times>10){
            $data['success'] = 1;
            $data['msg'] = '您获取验证码的频率过高，请稍后再试！';
            echo json_encode($data);
            exit;
        }

        $Info = $this->Member_model->cTestGetCode($phone);
        $data['success'] = $Info['status'];
        $data['msg'] = $Info['info'];
        if ($Info['status'] == 1) {
            $send_times_arr['send_times'] = $send_times;
            $this->cache->memcached->save($mem_key, $send_times_arr, $exp_time);
            $data['msg'] = '验证码已发送';
        }
        echo json_encode($data);
        exit;
    }

    //分配积分
    public function allotIntegral()
    {
        $this->init();
        $this->load->helper('cookie');
        $post = $this->input->post();

        $code = isset($post['code']) ? intval($post['code']) : 0;//验证码
        $account = isset($post['count']) ? trim($post['count']) : '';//子账号
        $score = isset($post['score']) ? abs(intval($post['score'])) : 0;//积分数

        if ($code != get_cookie('verification_code')) {
            $data['success'] = 0;
            $data['msg'] = '验证码错误';
            echo json_encode($data);
            exit;
        }
        if ($score > $this->mainAccountInfo['iIntegralTotalNumber']) {
            $data['success'] = 0;
            $data['own'] = $this->mainAccountInfo['iIntegralTotalNumber'];//主账号可用积分数
            $data['msg'] = '您的积分不够';
            echo json_encode($data);
            exit;
        }
        $Flag = $this->Member_model->sendScore($score, $account);

        if ($Flag) {
            $data['success'] = 1;
            $data['msg'] = '分配成功';
            echo json_encode($data);
            exit;
        } else {
            $data['success'] = 0;
            $data['msg'] = '分配失败';
            echo json_encode($data);
            exit;
        }
    }

    //获取分配积分的子账号信息
    public function childInfo()
    {
        $this->init();
        $post = $this->input->post();

        $account = intval($post['count']);
        $condition = array();
        $condition['sChildAccount'] = $account;
        $childInfo = $this->Member_model->getChildInfo($condition);
        if ($childInfo) {
            $data['success'] = 1;
            $data['info'] = $childInfo;
        } else {
            $data['success'] = 0;
        }
        echo json_encode($data);
    }

    //解除关联
    public function unAssociate()
    {
        $this->init();
        $post = $this->input->post();

        $code = isset($post['code']) ? intval($post['code']) : 0;//验证
        $account = isset($post['count']) ? trim($post['count']) : '';//子账号

        if ($code != get_cookie('verification_code')) {
            $data['success'] = 0;
            $data['msg'] = '验证码错误';
            echo json_encode($data);
            exit;
        }
        $Flag = $this->Member_model->unsetAssociate($account, '主账号操作-解除绑定');
        if ($Flag) {
            $data['success'] = 1;
            $data['msg'] = '解除成功';
            echo json_encode($data);
            exit;
        } else {
            $data['success'] = 0;
            $data['msg'] = '解除失败';
            echo json_encode($data);
            exit;
        }
    }

    //重置密码
    public function reset()
    {
        $this->init();
        //获取子账号（手机号）
        $phone = $this->input->post('phone', true);

        if (empty($phone)) {
            $data['success'] = 0;
            $data['msg'] = '信息错误';
            echo json_encode($data);
            exit;
        }
        $Info = $this->Member_model->resetPwd($phone);
        if ($Info) {
            $data['success'] = 1;
            $data['msg'] = '重置成功';
            $data['pwd'] = $Info;
        } else {
            $data['success'] = 0;
            $data['msg'] = '重置失败';
        }
        echo json_encode($data);
        exit;
    }

    //账户积分
    public function integral($args = '')
    {
        $this->init();
        $popIntegralConsumeTypes = getCommonData('common_data', 'popIntegralConsumeTypes');

        $condition = array();
        if ($this->iAccountType == '1') {//主账号
            $condition['iAccountID'] = $this->mainAccountInfo['id'];
        } else {
            $condition['iAccountID'] = $this->childAccountInfo['sChildID'];
        }

        $params = $this->common_model->parseParams($args, 1, false);
        $page = isset($params['page']) && !empty($params['page']) ? intval($params['page']) : 1;
        $pageSize = 10;
        $totalCount = 0;
        $integralInfo = $this->Member_model->getIntegralDetailList($condition, $totalCount, $page, $pageSize);

        $pageHtml = $this->makePageHtml($params, $totalCount, $pageSize, $page, 'member/integral/');

        $this->assign('popIntegralConsumeTypes', $popIntegralConsumeTypes);
        $this->assign('integralInfo', $integralInfo);
        $this->assign('pageHtml', $pageHtml);
        $this->assign('page', $page);
        $this->assign('pageSize', $pageSize);
        $this->display('personalCenter/integral.html');
    }

    // --------------------------分享------------------------------------------
    //我要分享首页
    public function share($args = '')
    {
        $this->assign('selected_flag', 7);
        $this->init();
        $iWebsite = $this->Member_model->websites[$this->Member_model->website];

        $params = $this->common_model->parseParams($args, 1, false);
        $page = isset($params['page']) && !empty($params['page']) ? intval($params['page']) : 1;
        $pageSize = 10;
        $totalCount = 0;
        $userId = $this->mainAccountInfo['id'];

        $condition = array();
        $status = isset($params['status']) && !empty($params['status']) ? intval($params['status']) : 0;
        if ($status) {
            $condition['approvalSt'] = intval($status);//分享状态 1未审核 2通过 3不通过
        }
        $condition['status'] = 1;
        $condition['iWebsite'] = $iWebsite;
        $condition['sUserId'] = $userId;

        $shareList = $this->Member_model->getShareList($condition, $totalCount, $page, $pageSize);

        $pageHtml = $this->makePageHtml($params, $totalCount, $pageSize, $page, 'member/share/');

        $this->assign('shareList', $shareList);
        $this->assign('status', $status);
        $this->assign('pageHtml', $pageHtml);
        $this->assign('totalCount', $totalCount);
        $this->display('personalCenter/share.html');
    }

    //我要分享---上传分享图片
    public function updatePic()
    {
        $this->init();
        $post = $this->input->post();
        $this->load->library('POP_Uploadpic');
        //通过cookie获取用户信息
        $sTitle = $post['sTitle'] ? trim($post['sTitle']) : '';
        $sUserId = $this->mainAccountInfo['id'];
        $dCreateTime = date('Y-m-d H:i:s');
        $iWebsite = $this->Member_model->websites[$this->Member_model->website];;

        $data = array();
        $data['sTitle'] = $sTitle;
        $data['sUserId'] = $sUserId;
        $data['dCreateTime'] = $dCreateTime;
        $data['iWebsite'] = $iWebsite;
        $data['column'] = 'share';
        //将记录插入到记录表中
        if (!empty($_FILES['shareUrl']['name'])) {
            $id = $this->Member_model->executeSave(Member_model::T_POP_SHARE_LOG, $data);
            if ($id > 0) {
                $savePath = 'share/' . ceil($id / 1000000) . '/' . ceil($id / 1000) . '/' . $id . '/';
                $res = $this->pop_uploadpic->getFileStream($_FILES['shareUrl']);
                //上传
                $body = array(
                    'fName' => $id . '_' . rand(111, 999) . '.' . $res['ext'],
                    'fTargetPath' => $savePath,
                    'fStream' => $res['stream'],
                );
                $json = $this->pop_uploadpic->curlRequest($body);
                $res = json_decode($json, true);
                if ($res['status']) {
                    $upData = array(
                        'sSharedPic' => $res['info']
                    );
                    $condition = array(
                        'id' => $id
                    );
                    $rs = $this->Member_model->executeUpdate(Member_model::T_POP_SHARE_LOG, $upData, $condition);
                    if ($rs) {
                        echo 'success';
                    } else {
                        echo '分享操作失败！';
                    }
                } else {
                    echo $res['info'] . '分享操作失败！';
                }
            }
        } else {
            echo '请上传图片！';
            exit;
        }
    }

    //取消/删除分享
    public function unsetShare()
    {
        $this->init();
        $post = $this->input->post();

        $shareId = intval($post['shareid']);
        $userId = $this->mainAccountInfo['id'];

        $condition = array();
        $condition['id'] = $shareId;
        $condition['sUserId'] = $userId;

        $rs = $this->Member_model->opShare($condition);
        if ($rs > 0) {
            echo 'success';
        }
    }

    //上传头像
    public function uploadAvatar()
    {
        $this->load->library('POP_Uploadpic');
        $this->init();
        $aCookieUserInfo = get_cookie_value();
        if (!empty($_FILES) && $aCookieUserInfo) {
            $iUid = intval($aCookieUserInfo['id']);
            //验证
            $res = $this->pop_uploadpic->getFileStream($_FILES['uploadAvatar']);
            //上传
            $savePath = 'avatar/' . ceil($iUid / 1000000) . '/' . ceil($iUid / 1000) . '/' . $iUid . '/';
            $body = array(
                'fName' => ($this->iAccountType == '2' ? $this->sAccountId : $iUid) . '_' . MD5($iUid . time()) . '.' . $res['ext'],
                'fTargetPath' => $savePath,
                'fStream' => $res['stream'],
                'fWidth' => 151,
                'fHeight' => 151
            );
            $json = $this->pop_uploadpic->curlRequest($body);
            $res = json_decode($json, true);
            if ($res['status']) {
                $aData = array(
                    'iUid' => ($this->iAccountType == '2' ? $this->sAccountId : $iUid),
                    'sAvatar' => $res['info']
                );
                $data = $this->Member_model->updateAvatarData($aData);
            } else {
                $data = array('status' => 0, 'info' => '上传失败');
            }
        } else {
            $data = array('status' => 0, 'info' => '上传失败');
        }
        echo json_encode($data);
    }

    // 我的消息
    public function message($args = '')
    {
        $this->assign('selected_flag', 6);
        $this->init(1);

        $params = $this->common_model->restoreParams($args);
        $params = $this->common_model->parseParams($params, 1, false);
        $page = isset($params['page']) && !empty($params['page']) ? intval($params['page']) : 1;
        $pageSize = 10;
        $totalCount = 0;
        $result = $this->Member_model->getMessageList($page, $pageSize, $totalCount);
        $pageHtml = $this->makePageHtml([], $totalCount, $pageSize, $page, '/member/message/');
        $this->assign('result', $result);
        $this->assign('pageHtml', $pageHtml);
        $this->assign('totalCount', $totalCount);

        //记录消息访问时间
        $this->Member_model->insertMessageVisit(0);

        list($WorkList, $downZip, $orderZip, $focusCount) = $this->userCenterCountWithStatus();
        $this->assign('WorkList', $WorkList);
        $this->assign('downZip', $downZip);
        $this->assign('orderZipCount', count($orderZip));
        $this->assign('focusCount', $focusCount);

        $this->assign('issetNewMessage', 0);

        // 账号类型P_UserType => 1 主账号是vip , 2子账号是vip 、3试用 4普通 5游客
        $vipTypeArr = memberPower('other');
        $this->assign('accountType', $vipTypeArr['P_UserType']);

        $this->assign('title', '我的消息-POP服装趋势网');
        $this->assign('keywords', '我的消息');
        $this->assign('description', '我的消息');

        $this->display('personalCenter/my_information.html');
    }

    //我的资料包
    public function myOrderZip($args = '')
    {
        //头部
        $this->assign('selected_flag', 5);
        $this->_fecthHeader();

        list($WorkList, $downZip, $allOrderZip, $focusCount) = $this->userCenterCountWithStatus();
        $this->assign('WorkList', $WorkList);
        $this->assign('downZip', $downZip);
        $this->assign('focusCount', $focusCount);

        //记录消息访问时间
        $this->Member_model->insertMessageVisit(1); //消息类型

        //array(账号类型P_UserType => 1 主账号是vip , 2子账号是vip 、3试用 4普通 5游客);
        $vipTypeArr = memberPower('other');
        $this->assign('accountType', $vipTypeArr['P_UserType']);

        //分页
        $pageSize = 28;
        $page = $this->input->get('page', true);
        $page = isset($page) ? $page : 1;
        $offset = ($page - 1) * $pageSize;

        $orderZipCount = count($allOrderZip);
        $pageCount = ceil($orderZipCount / $pageSize);
        $this->assign('pageCount', $pageCount);
        $this->assign('orderZipCount', $orderZipCount);

        $orderZip = $this->workbench->getMyOrderZipList($this->sAccountId, $vipTypeArr['P_UserType'], $vipTypeArr['P_ExpireTime'], $offset, $pageSize);
        $this->assign('eDate', date('Y-m-d', strtotime('-30 days')));
        $this->assign('dataZip', $orderZip);

        //判断当前用户是否已过期,如果已过期提示将不再推送资料包
        $vaildStatus = time() >= strtotime($vipTypeArr['P_ExpireTime']) ? 1 : 0;
        $this->assign('vaildStatus', $vaildStatus);
        $this->assign('orderMessage', 0);

        $this->assign('title', '工作台-POP服装趋势网');
        $this->assign('description', '我的工作台');
        $this->assign('keywords', '工作台');

        $this->display('personalCenter/my_order_zip.html');
    }

    //个人中心-历史记录
    public function myHistory($col = '')
    {

        //头部
        $this->assign('selected_flag', 1);
        $this->_fecthHeader('history');

        list($WorkList, $downZip, $allOrderZip, $focusCount) = $this->userCenterCountWithStatus();
        $this->assign('WorkList', $WorkList);
        $this->assign('downZip', $downZip);
        $this->assign('focusCount', $focusCount);
        $this->assign('orderZipCount', count($allOrderZip));

        //array(账号类型P_UserType => 1 主账号是vip , 2子账号是vip 、3试用 4普通 5游客);
        $vipTypeArr = memberPower('other');
        $this->assign('accountType', $vipTypeArr['P_UserType']);

        $this->assign('col', $col);
        $this->assign('title', '工作台-POP服装趋势网');
        $this->assign('description', '我的工作台');
        $this->assign('keywords', '工作台');

        $this->smarty->display('personalCenter/browsing_history.html');
    }

    /**
     * vip数据报告
     * [参考：./docs/20180926.数据报告.md]
     */
    public function dataReport()
    {
        $isAjax = $this->input->is_ajax_request();
        $jsonOutput = getJsonInstance();
        $this->load->model('common_model');
        $model = $this->common_model;
        if ($user_id = $this->input->get_post("user_id")) {
            $hash = $this->input->get_post("hash");
            $s_hash = md5(POP_GLOBAL_KEYS . $user_id);
            if ($hash === $s_hash) {
                $sql = "SELECT id,account,mobile FROM fashion_user WHERE id=?";
                $res = $model->query($sql, [$user_id]);
                if (isset($res[0])) {
                    $userInfo = $res[0];
                } else {
                    if ($isAjax) {
                        $jsonOutput->code(1)->msg('不是主帐号')->out();
                    }
                }
                $sql = "SELECT id,account,mobile FROM fashion_user WHERE id=?";
                $res = $model->query($sql, [$user_id]);
                $this->assign("hash", $hash);
                $this->assign("user_id", $user_id);
            } else {
                redirect('/member/pagelogin/');
            }
        } else {
            $userInfo = get_cookie_value();
            if (!$userInfo || $userInfo['iAccountType'] != 1) {
                // 未登录或不是主帐号
                if ($isAjax) {
                    $jsonOutput->code(1)->msg('未登录或不是主帐号')->out();
                }
                // echo '未登录或不是主帐号';
                if (!$userInfo) {
                    redirect('/member/pagelogin/');
                } else {
                    redirect('');
                }
                return;
            }
        }
        $userId = $userInfo['id'];
        if ($userId) {
            $sql = "SELECT count(*) as cnt FROM fashion_user_child WHERE iParentID=? AND iStatus=1";
            $res = $model->query($sql, [$user_id]);
            $hasChildAccount = $res[0]["cnt"] > 0 ? 1 : 0;
            $this->assign("hasChildAccount", $hasChildAccount);
        }
        // var_dump($userId);
        $redisConn = getRedisConnection();
        $redisKeyOverview = "data_report:user:{$userId}:overview";
        $redisKeyChildInfo = "data_report:user:{$userId}:child_info";

        if (!$redisConn->exists($redisKeyOverview)) {
            if ($isAjax) {
                $jsonOutput->code(1)->msg('没有可查看的信息')->out();
            }
            // echo '没有可查看的信息';
            redirect('');
            return;
        }

        $overview = $redisConn->hMGet($redisKeyOverview, [
            'login_rate', // {"value":0.06060606,"rank":1,"morethan":0.5}
            'login_days', // {"value":1,"rank":1,"morethan":0.5}
            'login_times', // {"value":2,"rank":1,"morethan":0.5}
            'login_days_top5', // [{"account":"18621281938","value":"1"},{"account":"15755377973","value":"1"}]
            'login_times_top5', // [{"account":"18621281938","value":"2"},{"account":"15755377973","value":"1"}]
            'main_info', // {"user_id":"106645","mobile":"{暂时不取}","login_days":0,"login_times":0,"last_login":"","ip":"","report_view":0,"style_view":0,"download":0}
            'login_rate_chart', // {"login_cnt":"2","total_cnt":33,"login_percent":0.06060606}
            'main_login_days',
            'main_login_times'
        ]);
        $loginRate = json_decode($overview['login_rate'], true);
        $loginDays = json_decode($overview['login_days'], true);
        $loginTimes = json_decode($overview['login_times'], true);
        $loginDaysTop5 = json_decode($overview['login_days_top5'], true);
        $loginTimesTop5 = json_decode($overview['login_times_top5'], true);
        $mainInfo = json_decode($overview['main_info'], true);
        $main_login_days = json_decode($overview['main_login_days'], true);
        $main_login_times = json_decode($overview['main_login_times'], true);
        $mainInfo['account'] = $userInfo['account'];
        $mainInfo['mobile'] = $userInfo['mobile'];
        $loginRateChart = json_decode($overview['login_rate_chart'], true);

        $childInfo = $redisConn->hGetAll($redisKeyChildInfo);
        foreach ($childInfo as $key => $val) {
            // $val: {"sChildID":"d6a6c196-feb3-11e4-a697-000c290d1ed5","sChildAccount":"15221186469","login_days":0,"login_times":0,"last_login":"","ip":"","report_view":0,"style_view":0,"download":0}
            $childInfo[$key] = json_decode($val, true);
        }

        if ($isAjax) {
            $data = compact('loginRate', 'loginDays', 'loginTimes', 'loginDaysTop5', 'loginTimesTop5', 'mainInfo', 'childInfo', 'loginRateChart', 'main_login_times', 'main_login_days');
            $jsonOutput->code(0)->data($data)->out();
        }

        // $this->assign('loginRate', $loginRate);
        // $this->assign('loginDays', $loginDays);
        // $this->assign('loginTimes', $loginTimes);
        // $this->assign('loginDaysTop5', $loginDaysTop5);
        // $this->assign('loginTimesTop5', $loginTimesTop5);
        // $this->assign('childInfo', $childInfo);
        $this->display('member/data_report.html');
    }

    /**
     * 脚本执行前执行该指令，清除redis缓存数据: `/usr/local/redis/src/redis-cli -n 15 keys "data_report*" | xargs /usr/local/redis/src/redis-cli -n 15 del`
     *
     * 执行脚本 [ php /data/htdocs/popfashion2016/index.php member dealDataReport ]
     *
     * 处理 dataReport 数据
     *
     */
    public function dealDataReport()
    {
        if (!is_cli()) {
            echo 'not allowed', PHP_EOL;
            return;
        }
        function print_ln($string = '')
        {
            echo $string, PHP_EOL;
        }

        print_ln('[start] ' . date('Y-m-d H:i:s'));

        // 加锁
        $lockFile = './templates_c/data_report.lock';
        $fd = fopen($lockFile, 'w');
        if (!flock($fd, LOCK_EX | LOCK_NB)) {
            print_ln('running...');
            return;
        }

        // 业务开始
        $this->load->model('common_model');
        $model = $this->common_model;
        $redisConn = getRedisConnection();
        $keyLoginRate = 'data_report:login_rate';
        $keyLoginDays = 'data_report:login_days';
        $keyLoginTimes = 'data_report:login_times';
        $timeStart = date('Y-m-01', strtotime('-1 month'));
        $timeEnd = date('Y-m-01');

        // 取当前所有vip主帐号
        $sql = 'SELECT DISTINCT iAccountId FROM `fashion`.`fm_privilege` WHERE iType = 3 AND (dEndTime > ?  AND  dStartTime < ?)';
        $userIds = $model->query($sql, [$timeStart, $timeEnd]);

        // var_dump($userIds);
        if (!$userIds) {
            print_ln('没有要处理的vip账号');
            print_ln('[done] ' . date('Y-m-d H:i:s'));
            return;
        }
        $userIds = array_column($userIds, 'iAccountId');
        //-----------------------------------------取账号数量和排名[start]----------------------------------
        $sql = "SELECT user_id,COUNT(DISTINCT DAY(create_time)) AS days FROM `fashion`.`fashion_login_log` WHERE  iSource=0 AND create_time BETWEEN ? AND ? AND user_id IN (" . implode(",", $userIds) . ") GROUP BY user_id ORDER BY days DESC";
        $rows = $model->query($sql, [$timeStart, $timeEnd]);
        $rank = 1;
        $pre_days = 0;
        $morethan_num = $total = count($userIds);
        foreach ($rows as $row) {
            $morethan = $morethan_num / $total;
            $redisConn->hSet("data_report:user:{$row["user_id"]}:overview", "main_login_days", json_encode(["value" => $row["days"], "rank" => $rank, "morethan" => $morethan]));
            if ($pre_days != $row["days"]) {
                $rank++;
                $morethan_num--;
            }
            $pre_days = $row["days"];
        }
        $sql = "SELECT user_id,COUNT(*) AS cnt FROM `fashion`.`fashion_login_log` WHERE  iSource=0 AND create_time BETWEEN ? AND ? AND user_id IN (" . implode(",", $userIds) . ") GROUP BY user_id ORDER BY cnt DESC";
        $rows = $model->query($sql, [$timeStart, $timeEnd]);
        $rank = 1;
        $pre_cnt = 0;
        $morethan_num = $total = count($userIds);
        foreach ($rows as $row) {
            $morethan = $morethan_num / $total;
            $redisConn->hSet("data_report:user:{$row["user_id"]}:overview", "main_login_times", json_encode(["value" => $row["cnt"], "rank" => $rank, "morethan" => $morethan]));
            if ($pre_cnt != $row["cnt"]) {
                $rank++;
                $morethan_num--;
            }
            $pre_cnt = $row["cnt"];
        }
        //-----------------------------------------取账号数量和排名[end]----------------------------------

        // 遍历上一步得到的vip主帐号
        foreach ($userIds as $userId) {
            // print_ln($userId);
            $keyOverview = "data_report:user:{$userId}:overview";

            // 【1. 登录率】
            // 主帐号下已开通子账号数
            $sql = 'SELECT sChildID,sChildAccount FROM `fashion`.`fashion_user_child` WHERE iParentID = ? AND iStatus = 1';
            $_childUsers = $model->query($sql, [$userId]);

            // 有效的子账号sql语句，用于下面子查询使用
            $sql_valid_child = 'SELECT sChildID FROM `fashion`.`fashion_user_child` WHERE iParentID = ' . $userId . ' AND iStatus = 1';

            $childCntTotal = count($_childUsers);
            if (!$childCntTotal) {
                // 没有子账号不处理, 当前情况不会出现
                continue;
            }
            // 补充字段默认值
            $childUsers = [];
            foreach ($_childUsers as $key => $val) {
                $childUsers[$val['sChildID']] = [
                    'sChildID' => $val['sChildID'], // d756cd38-f3ba-11e4-a697-000c290d1ed5
                    'sChildAccount' => $val['sChildAccount'], // 18817982830 或 pop54061187
                    'login_days' => 0, // 登录天数
                    'login_times' => 0, // 登录次数
                    'last_login' => '', // 最后登录时间
                    'ip' => '', // 最后登录ip
                    'region' => '', // 最后登录ip对应地区
                    'report_view' => 0, // 报告浏览次数
                    'style_view' => 0, // 款式浏览次数
                    'download' => 0, // 下载次数
                ];
            }
            // var_dump($childUsers);
            // 主帐号下登录过的子账号数
            $sql = 'SELECT COUNT( DISTINCT sChildID ) AS cnt FROM `fashion`.`fashion_user_child_login_log` WHERE iParentID = ? AND dLoginTime BETWEEN ? AND ? AND sChildID IN (' . $sql_valid_child . ')';
            $rows = $model->query($sql, [$userId, $timeStart, $timeEnd]);
            $childCntLogin = $rows[0]['cnt'];
            // 主帐号登录率
            $loginRate = round($childCntLogin / $childCntTotal, 8); // 保留8位小数
            $redisConn->zAdd($keyLoginRate, $loginRate, $userId);
            // 饼图
            $redisConn->hMset($keyOverview, ['login_rate_chart' => json_encode([
                'login_cnt' => $childCntLogin,
                'total_cnt' => $childCntTotal,
                'login_percent' => $loginRate,
            ])]);

            // 【2. 登录天数】
            // 主帐号下各子账号登录总天数
            $sql = 'SELECT sChildID, COUNT(DISTINCT DAY(dLoginTime)) AS days FROM `fashion`.`fashion_user_child_login_log` WHERE iParentID = ? AND dLoginTime BETWEEN ? AND ? AND sChildID IN (' . $sql_valid_child . ') GROUP BY sChildID ORDER BY days DESC';
            $rows = $model->query($sql, [$userId, $timeStart, $timeEnd]);
            // var_dump($rows);
            // 主帐号下登录天数top5
            $loginDaysTop5 = [];
            if (count($rows) > 0) {
                $loginDaysMax = $rows[0]['days'];
                foreach ($rows as $row) {
                    if (isset($childUsers[$row['sChildID']])) {
                        $childUsers[$row['sChildID']]['login_days'] = $row['days'];
                        if (count($loginDaysTop5) < 5) {
                            $loginDaysTop5[] = [
                                'account' => $childUsers[$row['sChildID']]['sChildAccount'],
                                'value' => $row['days']
                            ];
                        }
                    }
                }
            } else {
                $loginDaysMax = 0;
            }
            $redisConn->hMset($keyOverview, ['login_days_top5' => json_encode($loginDaysTop5)]);
            // 各主帐号下登录天数最多的子账号排行
            $redisConn->zAdd($keyLoginDays, $loginDaysMax, $userId);

            // 【3. 登录次数】
            // 主帐号下各子账号登录总次数
            $sql = 'SELECT sChildID, COUNT(*) as cnt FROM `fashion`.`fashion_user_child_login_log` WHERE iParentID = ? AND dLoginTime BETWEEN ? AND ? AND sChildID IN (' . $sql_valid_child . ') GROUP BY sChildID ORDER BY cnt DESC';
            $rows = $model->query($sql, [$userId, $timeStart, $timeEnd]);
            // var_dump($rows);
            // 主帐号下登录次数top5
            $loginTimesTop5 = [];
            if (count($rows) > 0) {
                $loginCntMax = $rows[0]['cnt'];
                foreach ($rows as $row) {
                    if (isset($childUsers[$row['sChildID']])) {
                        $childUsers[$row['sChildID']]['login_times'] = $row['cnt'];
                        if (count($loginTimesTop5) < 5) {
                            $loginTimesTop5[] = [
                                'account' => $childUsers[$row['sChildID']]['sChildAccount'],
                                'value' => $row['cnt']
                            ];
                        }
                    }
                }
            } else {
                $loginCntMax = 0;
            }
            $redisConn->hMset($keyOverview, ['login_times_top5' => json_encode($loginTimesTop5)]);
            // 各主帐号下登录次数最多的子账号排行
            $redisConn->zAdd($keyLoginTimes, $loginCntMax, $userId);

            // 【4. 子账号最后登录信息】
            $sql = 'SELECT sChildID,MAX(dLoginTime) as dLoginTime,sLoginIP FROM `fashion`.`fashion_user_child_login_log` WHERE iParentID = ? AND dLoginTime BETWEEN ? AND ? AND sChildID IN (' . $sql_valid_child . ') GROUP BY sChildID ORDER BY dLoginTime DESC';
            $rows = $model->query($sql, [$userId, $timeStart, $timeEnd]);
            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    if (isset($childUsers[$row['sChildID']])) {
                        $childUsers[$row['sChildID']]['last_login'] = $row['dLoginTime'];
                        $childUsers[$row['sChildID']]['ip'] = $row['sLoginIP'];
                        // $childUsers[$row['sChildID']]['region'] = getRegionByIp($row['sLoginIP']);
                    }
                }
            }
            // 【5. 子账号浏览次数，下载次数】
            // 款式浏览次数
            $sql = 'SELECT iUserId,sChildUserId as sChildID,COUNT(*) AS cnt FROM `statistics`.`t_f_views` WHERE iUserId = ? AND iColumnId = 4 AND dCreateTime BETWEEN ? AND ? AND sChildUserId IN (' . $sql_valid_child . ') GROUP BY sChildUserId';
            $rows = $model->query($sql, [$userId, $timeStart, $timeEnd]);
            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    if (isset($childUsers[$row['sChildID']])) {
                        $childUsers[$row['sChildID']]['style_view'] = $row['cnt'];
                    }
                }
            }
            // 报告浏览次数
            $sql = 'SELECT iUserId,sChildUserId as sChildID,COUNT(*) AS cnt FROM `statistics`.`t_f_views` WHERE iUserId = ? AND iColumnId IN (1,2) AND dCreateTime BETWEEN ? AND ? AND sChildUserId IN (' . $sql_valid_child . ') GROUP BY sChildUserId';
            $rows = $model->query($sql, [$userId, $timeStart, $timeEnd]);
            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    if (isset($childUsers[$row['sChildID']])) {
                        $childUsers[$row['sChildID']]['report_view'] = $row['cnt'];
                    }
                }
            }
            // 下载次数
            $sql = 'SELECT iUserId,sChildUserId as sChildID,COUNT(*) AS cnt FROM `statistics`.`t_f_down` WHERE iUserId = ? AND dCreateTime BETWEEN ? AND ? AND sChildUserId IN (' . $sql_valid_child . ') GROUP BY sChildUserId';
            $rows = $model->query($sql, [$userId, $timeStart, $timeEnd]);
            if (count($rows) > 0) {
                foreach ($rows as $row) {
                    if (isset($childUsers[$row['sChildID']])) {
                        $childUsers[$row['sChildID']]['download'] = $row['cnt'];
                    }
                }
            }

            // var_dump($childUsers);
            $redisKeyChildInfo = "data_report:user:{$userId}:child_info";
            foreach ($childUsers as $childId => $childUser) {
                $redisConn->hMset($redisKeyChildInfo, [$childId => json_encode($childUser)]);
            }
            // return;
        }

        // 处理【登录率】排行名次并列的问题
        $loginRateData = $redisConn->zRevRange($keyLoginRate, 0, -1, true);
        $loginRateRank = [];
        $_rank = 1;
        $_index = 1;
        $_prev = -1;
        foreach ($loginRateData as $userId => $value) {
            if ($_prev < 0) { // 第一个
                $_rank = 1;
            } else {
                $_rank = $value == $_prev ? $_rank : $_index;
            }
            $_index++;
            $_prev = $value;
            $loginRateRank[$userId] = [
                'value' => $value,
                'rank' => $_rank
            ];
        }

        // 处理【登录天数】排行名次并列的问题
        $loginDaysData = $redisConn->zRevRange($keyLoginDays, 0, -1, true);
        $loginDaysRank = [];
        $_rank = 1;
        $_index = 1;
        $_prev = -1;
        foreach ($loginDaysData as $userId => $value) {
            if ($_prev < 0) { // 第一个
                $_rank = 1;
            } else {
                $_rank = $value == $_prev ? $_rank : $_index;
            }
            $_index++;
            $_prev = $value;
            $loginDaysRank[$userId] = [
                'value' => $value,
                'rank' => $_rank
            ];
        }

        // 处理【登录次数】排行名次并列的问题
        $loginTimesData = $redisConn->zRevRange($keyLoginTimes, 0, -1, true);
        $loginTimesRank = [];
        $_rank = 1;
        $_index = 1;
        $_prev = -1;
        foreach ($loginTimesData as $userId => $value) {
            if ($_prev < 0) { // 第一个
                $_rank = 1;
            } else {
                $_rank = $value == $_prev ? $_rank : $_index;
            }
            $_index++;
            $_prev = $value;
            $loginTimesRank[$userId] = [
                'value' => $value,
                'rank' => $_rank
            ];
        }
        // var_dump(compact('loginRateRank', 'loginDaysRank', 'loginTimesRank'));

        // 【构造各个主帐号统计数据】
        foreach ($userIds as $userId) {
            $keyOverview = "data_report:user:{$userId}:overview";

            // 1. 登录率
            $rate = $redisConn->zScore($keyLoginRate, $userId); // 登录率作为score进行存储的
            if ($rate !== false) {
                $bigPartCnt = $redisConn->zCount($keyLoginRate, $rate, 1.0); // 从当前登录率到最大登录率包含的元素数量
                $total = $redisConn->zCard($keyLoginRate); // 排行榜总数量
                // var_dump(compact('rate', 'bigPartCnt', 'total'));
                $loginRateInfo = [
                    'value' => $rate,
                    'rank' => isset($loginRateRank[$userId]) ? $loginRateRank[$userId]['rank'] : 0,
                    'morethan' => ($total - $bigPartCnt) / $total
                ];
                $redisConn->hMset($keyOverview, ['login_rate' => json_encode($loginRateInfo)]);
            }


            // 2. 登录天数
            $days = $redisConn->zScore($keyLoginDays, $userId); // 登录天数作为score进行存储的
            if ($days !== false) {
                $bigPartCnt = $redisConn->zCount($keyLoginDays, $rate, 35.0); // 从当前登录天数到最大登录天数(这里取35)包含的元素数量
                $total = $redisConn->zCard($keyLoginDays); // 排行榜总数量
                // var_dump(compact('days', 'bigPartCnt', 'total'));
                $loginDaysInfo = [
                    'value' => $days,
                    'rank' => isset($loginDaysRank[$userId]) ? $loginDaysRank[$userId]['rank'] : 0,
                    'morethan' => ($total - $bigPartCnt) / $total
                ];
                $redisConn->hMset($keyOverview, ['login_days' => json_encode($loginDaysInfo)]);
            }


            // 3. 登录次数
            $times = $redisConn->zScore($keyLoginTimes, $userId); // 登录次数作为score进行存储的
            if ($times !== false) {
                $bigPartCnt = $redisConn->zCount($keyLoginTimes, $rate, 1e6); // 从当前登录次数到最大登录次数(这里取1e6)包含的元素数量
                $total = $redisConn->zCard($keyLoginTimes); // 排行榜总数量
                // var_dump(compact('times', 'bigPartCnt', 'total'));
                $loginTimesInfo = [
                    'value' => $times,
                    'rank' => isset($loginTimesRank[$userId]) ? $loginTimesRank[$userId]['rank'] : 0,
                    'morethan' => ($total - $bigPartCnt) / $total
                ];
                $redisConn->hMset($keyOverview, ['login_times' => json_encode($loginTimesInfo)]);
            }
        }

        // 取主帐号信息
        foreach ($userIds as $userId) {
            $keyOverview = "data_report:user:{$userId}:overview";
            if (!$redisConn->exists($keyOverview)) {
                continue;
            }
            $mainInfo = [
                'user_id' => $userId,
                'account' => '', // 暂时不取
                'mobile' => '', // 暂时不取
                'login_days' => 0, // 登录天数
                'login_times' => 0, // 登录次数
                'last_login' => '', // 最后登录时间
                'ip' => '', // 最后登录ip
                'region' => '', // 最后登录ip对应地区
                'report_view' => 0, // 报告浏览次数
                'style_view' => 0, // 款式浏览次数
                'download' => 0, // 下载次数
            ];
            // 登录天数、次数
            $sql = 'SELECT user_id,COUNT(DISTINCT DAY(create_time)) AS days,COUNT(*) AS times FROM `fashion`.`fashion_login_log` WHERE user_id = ? AND iSource = 0 AND create_time BETWEEN ? AND ?';
            $rows = $model->query($sql, [$userId, $timeStart, $timeEnd]);
            if (count($rows) > 0) {
                $mainInfo['login_days'] = $rows[0]['days'];
                $mainInfo['login_times'] = $rows[0]['times'];
            }

            // 最后登录时间、ip
            $sql = 'SELECT user_id,create_time,login_ip FROM `fashion`.`fashion_login_log` WHERE user_id = ? AND iSource = 0 AND create_time BETWEEN ? AND ? ORDER BY create_time DESC LIMIT 1';
            $rows = $model->query($sql, [$userId, $timeStart, $timeEnd]);
            if (count($rows) > 0) {
                $mainInfo['last_login'] = $rows[0]['create_time'];
                $mainInfo['ip'] = $rows[0]['login_ip'];
                // $mainInfo['region'] = getRegionByIp($rows[0]['login_ip']);
            }

            // 报告浏览次数
            $sql = 'SELECT COUNT(*) AS cnt FROM `statistics`.`t_f_views` WHERE iUserId = ? AND sChildUserId = \'\' AND iColumnId IN (1,2) AND dCreateTime BETWEEN ? AND ?';
            $rows = $model->query($sql, [$userId, $timeStart, $timeEnd]);
            if (count($rows) > 0) {
                $mainInfo['report_view'] = $rows[0]['cnt'];
            }

            // 款式浏览次数
            $sql = 'SELECT COUNT(*) AS cnt FROM `statistics`.`t_f_views` WHERE iUserId = ? AND sChildUserId = \'\' AND iColumnId = 4 AND dCreateTime BETWEEN ? AND ?';
            $rows = $model->query($sql, [$userId, $timeStart, $timeEnd]);
            if (count($rows) > 0) {
                $mainInfo['style_view'] = $rows[0]['cnt'];
            }

            // 下载次数
            $sql = 'SELECT COUNT(*) AS cnt FROM `statistics`.`t_f_down` WHERE iUserId = ? AND sChildUserId = \'\' AND dCreateTime BETWEEN ? AND ?';
            $rows = $model->query($sql, [$userId, $timeStart, $timeEnd]);
            if (count($rows) > 0) {
                $mainInfo['download'] = $rows[0]['cnt'];
            }

            $redisConn->hMset($keyOverview, ['main_info' => json_encode($mainInfo)]);
        }

        // 释放锁
        flock($fd, LOCK_UN);
        fclose($fd);
        print_ln('[done] ' . date('Y-m-d H:i:s'));
    }

    public function leavemessage()
    {
        $this->assign("title", "线上留言-POP服装趋势网");
        $this->display("message.html");
    }

    public function modDefaultLogin()
    {
        $this->init();
        $deaultLoginType = $this->member_model->getMobileDefaultAccount();
        $this->assign("deaultLoginType", $deaultLoginType);
        $this->display("personalCenter/modifyLogin.html");
    }

    public function clientLogin()
    {
        $this->display("member/client.html");
    }

    /**
     * 工作室数量
     * 下载包数量
     * 资料包数量
     * 未读消息状态
     * @return array
     */
    private function userCenterCountWithStatus()
    {
        //获取账号下所有工作台
        $WorkList = $this->workbench->getWorkBenchByAccountId($this->sAccountId);
        //获取账号下所有下载包
        $downZip = $this->workbench->getMyDownLoadByAccountId($this->sAccountId);
        // 账号类型P_UserType => 1 主账号是vip , 2子账号是vip 、3试用 4普通 5游客
        $vipTypeArr = memberPower('other');
        // 获取此账号下所有资料包
        $orderZip = $this->workbench->getMyOrderZipByAccountId($this->sAccountId, $vipTypeArr['P_UserType'], $vipTypeArr['P_ExpireTime']);

        list($focusCount, $collectFacetData) = $this->collectionTotal();

        return [$WorkList, $downZip, $orderZip, $focusCount, $collectFacetData];
    }

    /**
     * 用户中心获取收藏总量
     * @return array
     */
    private function collectionTotal()
    {
        $cond = [];
        $cond['aCollectAccount'] = $this->sAccountId;
        $cond['other'] = [
            '(dCreateTime:[* TO ' . date("Y-m-d") . 'T' . date("H:i:s", strtotime("-2 hour")) . 'Z] OR aWebsite:1)',
        ];
        $params = [
            'facet' => 'true',
            'facet.field' => 'iColumnId',
            'facet.limit' => 200,
            'raw' => true
        ];
        $res = POPSearch::wrapQueryPopFashionMerger('', $cond, $result, 0, 0, [], $params);

        $totalCount = $res['response']['numFound'];
        $facet_data = $res['facet_counts']['facet_fields']['iColumnId'];

        return [$totalCount, $facet_data];
    }


    public function vipapply()
    {
        if (!$this->input->is_ajax_request()) {
            $title = '免费试用申请';
            $keyword = '';
            $description = '';
            $this->assign('title', $title);
            $this->assign('keyword', $keyword);
            $this->assign('description', $description);
            $this->display('member\vip_try.html');
        } else {
            $purchased_site = '';
            $user_type = 'NORMAL';
            $user = get_cookie_value();
            $power = memberPower('other', []);
            $userType = $power['P_UserType'];
            $userId = $user['userId'];
            $purchased = $this->input->post('purchased');//品类
            $username = $this->input->post('username');
            $phone = $this->input->post('phone');
            $company = $this->input->post('company');
            $type = $this->input->post('type');//来源
            if (empty($type)) {
                $type = 'H5';
            }
            $db = OpGlobalDb::getInstance('pop_app', 'write');
            $data = array('ret' => 1, 'data' => '', 'msg' => 'save faild');
            if (empty($purchased) || empty($username) || empty($phone)) {
                $data['msg'] = 'params empty';
                echo json_encode($data);
                exit();
            }
            $purc = array(1, 2, 3, 4, 5);
            foreach ($purc as $id) {
                switch ($id) {
                    case 1:
                        if ($userType < 3) {//服装
                            $purchased_site .= '1,';
                            $user_type = 'VIP';
                        } elseif ($userType == 3) {
                            $purchased_site .= '1,';
                            $user_type = 'TRIAL';
                        }
                        break;
                    case 2:
                        $bag_sql = 'select * from shoe_user where id = ' . $userId;
                        $row = $this->_getUserType($bag_sql, 'popbags');//箱包
                        if ($row[0]['vip_type'] > 0 && time() < strtotime($row[0]['consume_end_time'])) {
                            $purchased_site .= '2,';
                            $user_type = 'VIP';
                        } elseif ($row[0]['trail'] == 1 && time() > strtotime($row[0]['trail_begin_time']) && time() < strtotime($row[0]['trail_end_time'])) {
                            $purchased_site .= '2,';
                            $user_type = 'TRIAL';
                        }
                        break;
                    case 3:
                        $shoe_sql = 'select * from popshoe where id = ' . intval($userId);
                        $row = $this->_getUserType($shoe_sql, 'popshoe');//鞋
                        if ($row[0]['vip_type'] == 1 && time() < strtotime($row[0]['consume_end_time'])) {
                            $purchased_site .= '3,';
                            $user_type = 'VIP';
                        } elseif ($row[0]['trail'] == 1 && time() < strtotime($row[0]['trail_end_time'])) {
                            $purchased_site .= '3,';
                            $user_type = 'TRIAL';
                        }
                        break;
                    case 4:
                        $decoration_sql = "SELECT * FROM shoe_user WHERE id=" . intval($userId);
                        $row = $this->_getUserType($decoration_sql, 'decoration');//首饰
                        if (!empty($row) && $row[0]['vip_type'] == 1 && time() < strtotime($row[0]['consume_end_time'])) {
                            $purchased_site .= '4,';
                            $user_type = 'VIP';
                        } elseif ($row[0]['trail'] && time() < strtotime($row[0]['trail_end_time'])) {
                            $purchased_site .= '4,';
                            $user_type = 'TRIAL';
                        }
                        break;
                    case 5:
                        $home_sql = 'select * from shoe_user where id = ' . $userId;
                        $row = $this->_getUserType($home_sql, 'hometextile');
                        if (!empty($row) && $row[0]['vip_type'] == 1 && time() < $row[0]['consume_end_time']) {
                            $purchased_site .= '5';
                            $user_type = 'VIP';
                        } elseif ($row[0]['vip_type'] == 2 && time() < $row[0]['consume_end_time']) {
                            $purchased_site .= '5,';
                            $user_type = 'TRIAL';
                        }
                        break;
                    default:
                        break;
                }
            }

            $save = array(
                'name' => $username,
                'mobile' => $phone,
                'company_name' => $company,
                'user_type' => $userType == 5 ? 'TOURIST' : $user_type,
                'purchased_site' => $purchased_site,
                'apply_trial_site' => $purchased,
                'apply_time' => date('Y-m-d H:i:s'),
                'source' => $type,
                'uid' => $userId
            );
            $key = implode(",", array_keys($save));
            $val = implode("','", array_values($save));
            $save_sql = "insert into app_apply_trial ({$key}) values('{$val}')";
            $res = $db->mod($save_sql);
            if ($res) {
                $data['ret'] = 0;
                $data['msg'] = 'save success';
                $data['data'] = $res;
                echo json_encode($data);
                exit();
            }
            echo json_encode($data);
            exit();
        }
    }


    private function _getUserType($sql, $type = 'fashion', $table = 'fm_privilege')
    {
        $row = [];
        $fashion_db = OpGlobalDb::getInstance($type, 'read');
        $fashion_db->select($sql, $row);
        return $row;
    }

}