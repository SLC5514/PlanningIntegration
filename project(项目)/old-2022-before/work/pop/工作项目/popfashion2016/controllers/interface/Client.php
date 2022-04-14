<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @date 2017.12.26
 * @todo 登录软件控制器
 */
class Client extends POP_Controller
{
    private $diffTime = 300; //token过期时间

    //不在此数组中则全部强制更新
    private $version = [
        "nowVersion" => "v18.1.0", //当前最新版本
        "noTipVersion" => ["v18.1.0"],//不提示更新版本
        "tipVersion" => []//提示更新版本(非强制)
    ];
    private $downLoadUrl = "http://www.pop136.com/pop_exe.php"; //下载更新目录

    /**
     * @var Client_model
     */
    public $client_model;

    private $clientLang = [
        0 => 'OK',
        1 => '签名验证失败,或签名超时！',
        1001 => '签名参数有误！',
        1002 => '参数错误或缺失！',
        1003 => '账号或密码错误！',
        1004 => '未设置绑定或无权限绑定，请用网页登录!',
        1005 => '尊敬的客户您好，您的账号已经在当前电脑绑定！如需修改，请联系客服 4008 210 662。',
        1006 => '尊敬的客户您好，您的账号已经达到绑定数量上限！如需增加数量，请联系客服 4008 210 662。',
        1007 => '对不起，您的账号尚未在此电脑上绑定',
        1008 => '写入日志失败！',
        1009 => '账号密码不能为空',

    ];

    public function __construct()
    {
        parent::__construct();
        $this->load->model("interface/Client_model", "client_model");
        $this->DataType = (isset($_REQUEST['DataType']) && $_REQUEST['DataType'] == 'json') ? 'json' : 'xml';
    }

    /*------------------------------------------------------------------------------------------------------------------
    * @todo 客户端绑定
    * @param 账号 密码 站点 硬件信息（MAC地址）【post】
    *-----------------------------------------------------------------------------------------------------------------*/
    public function binding()
    {
        $this->checkSign();
        //参数验证
        $Site = $this->input->get("Site");
        $Account = $this->input->get("Account");
        $Password = $this->input->get("Password");
        $Mac = $this->input->get("Mac");
        if (empty($Account) || empty($Password)) {
            $this->printXml(["code" => 1009, "message" => $this->clientLang[1009]]);
        }
        if (!in_array($Site, [1, 2, 3, 4, 5]) || empty($Mac)) {
            $this->printXml(["code" => 1002, "message" => $this->clientLang[1002]]);
        }

        //获取用户信息
        $userInfo = $this->client_model->getUserInfo($Account, $Password, $Site);
        $MainAccount = $userInfo["isChild"] ? $userInfo["account"] : $Account; //是子账号时，绑定主账号
        //验证是否可以绑定
        try {
            if (empty($userInfo['id'])) {
                throw new Exception(1003);            //账号密码错误
            }
            $BindingInfo = $this->client_model->getBindingInfo($Site, $MainAccount, $Mac);
            if (!empty($BindingInfo) && $BindingInfo['mac'] == $Mac) {
                throw new Exception(1005);            //已绑定
            }
            if ($userInfo["userType"] == "normal" || $userInfo["client_type"] != 2) {
                throw new Exception(1004);            //未设置绑定或无权限绑定，请用网页登录
            }
            $BindingCount = $this->client_model->getBindingCount($Site, $MainAccount);
            if ($BindingCount >= $userInfo["client_num"]) {
                throw new Exception(1006);            //绑定数量已满
            }
        } catch (Exception $e) {
            $code = $e->getMessage();
            $this->printXml(["code" => $code, "message" => $this->clientLang[$code]]);
            exit;
        }
        //绑定客户端
        $this->client_model->addBindingInfo($Site, $MainAccount, $Mac);
        $this->printXml(["code" => 0, "message" => $this->clientLang[0]]);
        exit;
    }

    /*------------------------------------------------------------------------------------------------------------------
    * @todo 客户端登录
    * @param 账号 密码 站点 硬件信息（MAC地址）【post】
    *-----------------------------------------------------------------------------------------------------------------*/
    public function login()
    {

        $this->checkSign();

        //参数验证
        $array['Site'] = $Site = $this->input->get('Site');
        $array['Account'] = $Account = $this->input->get('Account');
        $array['Password'] = $Password = $this->input->get('Password');
        $array['Mac'] = $Mac = $this->input->get('Mac');

        if (!$Account || !$Password) {
            $this->printXml([
                'code' => 1009,
                'message' => $this->clientLang[1009]
            ]);
            exit;
        }

        if (!in_array($Site, [1, 2, 3, 4, 5]) || !$Mac) {
            $this->printXml([
                'code' => 1002,
                "message" => $this->clientLang[1002]
            ]);
            exit;
        }

        try {
            //获取用户信息
            $userInfo = $this->client_model->getUserInfo($Account, $Password, $Site);
            $MainAccount = $userInfo['isChild'] ? $userInfo['account'] : $Account;        //是子账号时，绑定主账号
            if (!$userInfo['id']) {
                throw new Exception(1003);    //账号密码错误
            }
            $BindingInfo = $this->client_model->getBindingInfo($Site, $MainAccount, $Mac);

            if (!$BindingInfo['mac']) {
                throw new Exception(1007);    //此电脑未绑定客户端
            }
        } catch (Exception $e) {
            $code = $e->getMessage();
            $this->printXml([
                'code' => $code,
                'message' => $this->clientLang[$code]
            ]);
            exit;
        }
        $encodeData = encrypt(json_encode($array));

        $httpData = ['Timestamp' => date('YmdHis'), 'params' => $encodeData];

        $Secret = POP_GLOBAL_KEYS;

        $httpData['Sign'] = $this->getServerSign($Secret, $httpData);

        $httpData = http_build_query($httpData);

        $url = 'https://www.pop-fashion.com/interface/client/location/?' . $httpData;
        $result = [
            'code' => 0,
            'message' => $this->clientLang[0],
            'url' => $url
        ];
        $this->printXml($result);
        exit;
    }

    //跳转至各个站点登录[非接口]
    public function location()
    {
        $this->checkSign("POP_GLOBAL_KEYS");
        $params = $this->input->get("params");
        $params = json_decode(decrypt($params), true);
        $Site = $params["Site"];
        $account = $params["Account"];
        $passwd = $params["Password"];
        $array = array(
            "account" => $account,
            "password" => $passwd,
            "website" => $Site,
        );
        ksort($array);
        $clientLoginToken = md5(POP_GLOBAL_KEYS . json_encode($array));
        $query = 'account=' . urlencode($account) . '&password=' . urlencode($passwd) . '&clientLoginToken=' . urlencode($clientLoginToken);
        switch ($Site) {
            case 1:
                $url = 'https://www.pop-fashion.com/member/clientLogin/?' . $query;
                break;
            case 2:
                $url = 'http://www.pop-bags.com/user/client_login/?' . $query;
                break;
            case 3:
                $url = 'https://www.pop-shoe.com/user/client_login/?' . $query;
                break;
            case 4:
                $url = 'http://www.51shoushi.com/user.php?act=client_login&' . $query;
                break;
            case 5:
                $url = 'https://www.91jiafang.com/member/login/client/?' . $query;
                break;
        }
        if (!empty($url)) {
            header("Location:{$url}");
        }
    }

    /*------------------------------------------------------------------------------------------------------------------
    * @todo 客户端日志记录
    *-----------------------------------------------------------------------------------------------------------------*/
    public function log()
    {
        $this->checkSign();
        $data["Os"] = $this->input->get("Os");
        $data["Account"] = $this->input->get("Account");
        $data["WebsiteSource"] = (int)$this->input->get("Site");
        $data["BrowserType"] = $this->input->get("Browsertype");
        $data["MachineId"] = $this->input->get("Mac");
        $data["Msg"] = $this->input->get("Msg");
        $data["CreateTime"] = date("Y-m-d H:i:s");
        $data["Level"] = 0; //默认0（此字段已废弃）

        $result = $this->client_model->addLoginLog($data);
        if ($result) {
            $result = ["code" => 0, "message" => $this->clientLang[0]];
            $this->printXml($result);
        } else {
            $result = ["code" => 1008, "message" => $this->clientLang[1008]];
            $this->printXml($result);
        }
    }

    /*------------------------------------------------------------------------------------------------------------------
    * @todo 客户端版本判断
    *-----------------------------------------------------------------------------------------------------------------*/
    public function version()
    {
        $this->checkSign();
        $Version = $this->input->get("Version");
        if (empty($Version)) {
            $result = ["code" => 1002, "message" => $this->clientLang[1002]];
            $this->printXml($result);
        }
        if (in_array($Version, $this->version["noTipVersion"])) {
            $updatetype = 0; //不提示
        } elseif (in_array($Version, $this->version["tipVersion"])) {
            $updatetype = 2; //非强制
        } else {
            $updatetype = 1; //强制更新
        }

        $result = ["code" => 0, "updatetype" => $updatetype, "version" => $this->version["nowVersion"], "url" => $this->downLoadUrl, "message" => $this->clientLang[0]];
        $this->printXml($result);
    }

    public function websites()
    {
        $this->checkSign();
        if ($this->DataType == 'json') {
            $arr = array(
                ['key' => '1', 'name' => '服装'],
                ['key' => '2', 'name' => '箱包'],
                ['key' => '3', 'name' => '鞋子'],
                ['key' => '4', 'name' => '首饰'],
                ['key' => '5', 'name' => '家纺'],
            );
            header('Content-type: application/json;charset=UTF-8');
            echo json_encode($arr);
            exit;
        } else {
            header("Content-type:text/xml");
            $domXml = '<?xml version="1.0" encoding="utf-8"?>
			<result>
				<code>0</code>
				<websites>
					<website name="1">服装</website>
					<website name="2">箱包</website>
					<website name="3">鞋子</website>
					<website name="4">首饰</website>
					<website name="5">家纺</website>
				</websites>
				<message>OK</message>
			</result>';
            echo $domXml;
        }
    }
    /*================================================以下私有方法===================================================*/

    /*------------------------------------------------------------------------------------------------------------------
    * @todo 验证签名
    *-----------------------------------------------------------------------------------------------------------------*/
    private function checkSign($Key = "")
    {
        $constantKey = !empty($Key) ? $Key : $this->input->get("Key");//所使用的常量
        $ClientSign = $this->input->get("Sign");
        $cTimestamp = $this->input->get("Timestamp");
        $get_post = $this->input->get_post(null);
        $log_data = [
            'Msg' => is_array($get_post) ? json_encode($get_post) : "",
            'CreateTime' => date('Y-m-d H:i:s'),
            'Level' => 9,
            'ClientTime' => date('YmdHis'),
        ];
        if (!defined($constantKey) || empty($ClientSign) || empty($cTimestamp)) {
            $this->client_model->addLoginLog($log_data);
            $result = ["code" => 1001, "message" => $this->clientLang[1001]];
            $this->printXml($result);
        }
        $Secret = constant($constantKey);
        $sTimestamp = time();//当前服务器时间
        $cTimestamp = strtotime($cTimestamp);
        $params = $this->input->get();
        $ServerSign = $this->getServerSign($Secret, $params);
        if ($ClientSign != $ServerSign || $sTimestamp - $cTimestamp > $this->diffTime) {
            $this->client_model->addLoginLog($log_data);
            $result = ["code" => 1, "message" => $this->clientLang[1]];
            $this->printXml($result);
        }
    }

    /*------------------------------------------------------------------------------------------------------------------
	* @todo 获取服务端Sign
	*-----------------------------------------------------------------------------------------------------------------*/
    private function getServerSign($Secret, $params)
    {
        unset($params["Sign"]);
        ksort($params);
        $other = "";
        foreach ($params as $key => $val) {
            $other .= trim($key) . trim($val);
        }
        return md5($Secret . $other);
    }

    /*------------------------------------------------------------------------------------------------------------------
     * @TODO 		生成XML
     * $arr 	 	[array] 	[需要被转化成XML格式的数组]
     * $nodeName 	[string] 	[节点名称,根节点默认data]
     * 注意:传进的数组键名要符合变量定义规范(仅适应和简单无属性XML)
     *-----------------------------------------------------------------------------------------------------------------*/
    private function printXml($arr, $nodeName = 'result', $Dom = '')
    {
        if ($this->DataType == 'json') {
            header('Content-type: application/json;charset=UTF-8');
            echo json_encode($arr);
            exit;
        } else {
            header("Content-type:text/xml");
            $Dom = empty($Dom) ? new DOMDocument('1.0', 'utf-8') : $Dom;
            $data = $Dom->createElement($nodeName);
            $Dom->appendChild($data);
            foreach ($arr as $key => $value) {
                if ($key == "url") {
                    $node = $Dom->createCDATASection($value);
                    $element = $Dom->createElement($key);
                    $element->appendChild($node);
                } else {
                    $element = $Dom->createElement($key, $value);
                }
                $data->appendChild($element);
            }
            echo $Dom->saveXML();
            exit;
        }
    }
}