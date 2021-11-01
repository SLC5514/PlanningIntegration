<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Firebase\JWT;
/**
 * @ 密钥model
 * @ 使用规则：非对称加密包裹着对称加密
 * 1，加密顺序：对称加密 加密后的结果，由非对称加密再次加密
 * 2，解密顺序：非对称加密 解密后的结果，由对称加密再次解密
 */
class Secret_model extends POP_Model
{
    /**
     * 两套非对称加密--加密方式： 2048bit位  PKCS#8
     * 生成非对称加密的网址： http://web.chacuo.net/netrsakeypair
     * 非对称加密 RSA: https://codeload.github.com/travist/jsencrypt/zip/master  下载 jsencrypt.js
     * 对称加密 AES:  https://codeload.github.com/brix/crypto-js/zip/develop    下载 crypto-js.js
     *
     * @ 两套非对称加密-密钥使用 原因：jsencrypt.js没有公钥解密的方法(不使用了，只使用一套，前台对参数公钥加密)
     * 1，前台向后台发送数据时，使用第一套公钥加密，后台用第一套私钥解密。
     * 2，后台向前台发送数据时，后台使用第二套公钥加密，前台使用第二套私钥解密。
     */
    public static $private_key = "-----BEGIN PRIVATE KEY-----
MIIEvwIBADANBgkqhkiG9w0BAQEFAASCBKkwggSlAgEAAoIBAQDXTncxyqfpYf5/
6WImJ3qtwRok473g5JCxbMhBIZYzjKhU5WDzlgqFbkmXAAcfndl3ywBIuTTKlC/n
N7rzN5Lc9JTmOvO7MNQSpm8RBo4d8pxRdHHcLrWJH2JOcDpgJkuUhfcKdEnNhYIk
1GNX6qkYcZevveJdGEuCp9CELl2UyKxuvcaqca081U4ENga0aApLialpjOk7rSS6
eigqZ0cC/Dl5LdSQg+LGPgHSZmSGf1Vn2Y6AFGCz0uj8iFz+ElcCTeRm/NCMinMq
tgCodinhwrIB9YJ6ADQbR8ajZolaqkWsCDbkVMNcV/QNEPP8AgS8m74R+O5aMnHP
TYy5ayKHAgMBAAECggEBAK6/IaDI8YVLIqsgeaer7Tbu/lAog2jvOby3JMUwBF9s
bwcQcKLjCUEIpTOoMx/nFqTSnzVnFEDDCyonolVaqpko7Vz7ReyNEeLdMFHqNxh5
W4QE9ZCWXIKhdUFidw6opjkyjbHvdiMXk+xecQ7fB0UulMVvYTTbzhWKLIPLuepR
g+5Sj7Klxb/gia8xtz5y7uQrLNJM85KfLu5Xb3YamI8GNIkpxeT/UBeh1aR4slQq
ElTLJC/Eiyp8qUuaj/tOjJgdBLleY5rslSrQp4joFl5vKfuIFAGd0eqSpjU7DL0k
4kv1wHIwVpCcW37NHVtownpuJf5kdSPgPryEDisjtxkCgYEA72gkAdMgemLgYBKp
Qdvt+MCjLTqw0k2dBpKNauI/LkspgVwDJz2Bfw8q4bw0+C5icDOTkpPC8UXeW4bd
lTO3x3VjwIzHlDiJsPtn5vA7FbZyw/qkNJ+pbC5Rw27cxJKPGhyHY/hBhvumPx69
W8nN0+uZ7cI5mjpPM0yr8j0WejsCgYEA5jq0+wrWH2eFN0EL6GDH0YHXnQr4sCdY
uzh5cGOm8gx2gxRVajIgggMjIufR1hZ+HMYhEJl7ERLUaJx5SEZW9hIKLNTOsX2S
EoJdsnx7K4wnWceuj1yqtad4bV4WEWujhonbhlrbtNknvIeZJ17yxS5an4LwfnsV
m47iwabS6CUCgYA2z0TPFiLtYg20cRFG/q7wsEmrZTBuNU6p3ot52OpWEuchsosW
RMKM8FAE9i26VAQ9Y9F5UVLkIfbSRYIdUoXUqE96GcDncjozxpr3CDC+u9Jyg8Ns
ONF07vXRJGRurI8Oj1F/1apK2PoqoUMA7KauydWn1TuSz56qQ/dpsgqfWQKBgQC6
vu3kH6MVcBvPeNSW1kHef8qL2MVl5VSE8EcY0cwt08whgNHTxPs3WiIbAxzi4fXD
kzZox4XakIJ4pEuAQ4XwLSvRt4A82hUtLE+iInSqGxIkMeM26DyUAladp86QrNXE
oHfPYE2MQQSJWRfypoKXTIU3k7dNcLtTJbBq0c2LfQKBgQDb4qCsja8WigX6w7Ct
QD0EZnlu9dUSSkG7g4UdEMo4LmIEK3n/iEe93xlND6iIlhXE9/6b1/W3ub4uA7Sf
g8znBQO83NIoPAPh2h7Qp3yek57g+WShMoUz310RPRpxFMPegPb0OMxks+0pIy5Y
DSin1fScezcCQWTkOO3pT/BtDg==
-----END PRIVATE KEY-----";

    public static $public_key = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA1053Mcqn6WH+f+liJid6
rcEaJOO94OSQsWzIQSGWM4yoVOVg85YKhW5JlwAHH53Zd8sASLk0ypQv5ze68zeS
3PSU5jrzuzDUEqZvEQaOHfKcUXRx3C61iR9iTnA6YCZLlIX3CnRJzYWCJNRjV+qp
GHGXr73iXRhLgqfQhC5dlMisbr3GqnGtPNVOBDYGtGgKS4mpaYzpO60kunooKmdH
Avw5eS3UkIPixj4B0mZkhn9VZ9mOgBRgs9Lo/Ihc/hJXAk3kZvzQjIpzKrYAqHYp
4cKyAfWCegA0G0fGo2aJWqpFrAg25FTDXFf0DRDz/AIEvJu+EfjuWjJxz02MuWsi
hwIDAQAB
-----END PUBLIC KEY-----";

    // 对称加密 openssl_encrypt 的参数 iv (16位) 【同步secret_key.js】
    private static $iv = '1234567887654301';
    // 对称加密--加密必须的加盐字符串(可以改)
    private static $key = 'jkosd32d@d+_)!667&eee*!#?';
    private static $confusion = null;

    protected $timeStamp = null;
    protected $symmetricKey = '';

    public function __construct()
    {
        parent::__construct();
    }

    // 渲染页面的初始数据,初始化
    public function init_data($time = '')
    {
        $time = !$time ? time() : $time;
        // 对称加密--密钥
        $timeStamp = $this->timeStamp = $time + 86400; // 时间戳 , 加24小时
        $symmetricKey = $this->symmetricKey = $this->get_symmetric_key($timeStamp);// 必须16位

        if ($timeStamp && $symmetricKey) {
            return [$symmetricKey, $timeStamp];
        }

        return array();
    }

    // 渲染页面的js代码混淆,初始化
    public static function init_confusion()
    {
        // js代码混淆
        $str = @file_get_contents("./global/js/secret/secret_key.js");
        self::$confusion = !empty($str) ? self::js_confusion($str) : '';
        return self::$confusion;
    }

    // 获取token
    public function get_token()
    {
        $user_id = getUserId();
        $pop_uid = $this->input->cookie("POP_UID");
        if ($user_id && $pop_uid && POP_GLOBAL_KEYS) {
            // 使用非对称加密--前端js不用解开
            $token = $this->rsa_encrypt($user_id.'_' . $pop_uid.'_' . md5(POP_GLOBAL_KEYS));
        }
        return $token ? $token : '';
    }

    //验证token
    public function check_token($sign = '')
    {
        if(!$sign){
            return false;
        }
        $token = $this->get_token();
        //私钥解密token
        $result_token = $this->rsa_decrypt($token);
        $result_sign = $this->rsa_decrypt($sign);

        if($result_token == $result_sign){
            return $result_token;
        }else {
            return false;
        }

    }

    /**
     * @非对称加密,私钥加密 [ 后台向前台发送数据 => 公钥后台加密 ]
     * @param string $aes_encrypt 对称加密  加密后的数据
     * @return string
     */
    public function rsa_encrypt($aes_encrypt)
    {
        if (empty($aes_encrypt)) return '';

        $bool = openssl_public_encrypt($aes_encrypt, $result, self::$public_key);
        return $result && $bool ? base64_encode($result) : '';
    }

    /**
     * @非对称加密,私钥解密 [ 前台第一套公钥加密，后台第一套私钥加密 ]
     * @param string $aes_decrypt 对称加密  解密后的数据
     * @return string
     * 使用：返回值 $data = json_decode(aes_decrypt($result,$symmetric_key),true);
     */
    public function rsa_decrypt($aes_decrypt)
    {
        if (empty($aes_decrypt)) return '';

        // js--encodeURI(res).replace(/\+/g, '%2B')  php--置换回来
        $aes_decrypt = str_replace("%2B", "+", $aes_decrypt);
        $bool = openssl_private_decrypt(base64_decode($aes_decrypt), $result, self::$private_key);
        return $result && $bool ? $result : '';
    }

    // 对称加密--加密
    public function aes_encrypt($data, $key)
    {
        if (empty($data) || empty($key)) return '';

        // 数组加密
        if (is_array($data)) {
            $data = json_encode($data);
        }
        $encrypted_data = openssl_encrypt($data, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, self::$iv);
        return base64_encode($encrypted_data);
    }

    // 对称加密--解密
    public function aes_decrypt($data, $key)
    {
        if (empty($data) || empty($key)) return '';

        $decrypted = openssl_decrypt(base64_decode($data), 'aes-128-cbc', $key, OPENSSL_RAW_DATA, self::$iv);
        return $decrypted;
    }

    // 生成对称加密--密钥字符串
    public function get_symmetric_key($time_stamp)
    {
        //时间戳不存在 || 密钥key的生成时间超过4小时 需要刷新页面重新生成
        if (empty($time_stamp) || !is_numeric($time_stamp) || $time_stamp+14400<time()) return '';

        // 密钥 = 时间戳+密钥加盐串
        $hash = self::$key . $time_stamp;
        $symmetric_key = hash('sha256', $hash);
        return substr(md5(base64_encode($symmetric_key)), 8, 16);
    }

    // 验证对称加密--密钥是否正确
    public function check_symmetric_key($time_stamp, $symmetric_key)
    {
        if (empty($symmetric_key) || !is_numeric($time_stamp)) return false;

        $check_key = $this->get_symmetric_key($time_stamp);
        if ($symmetric_key != $check_key || $time_stamp < time()) {
            return false;
        }
        return true;
    }

    /****************************** 私有方法 ************************************/
    /**
     * @js混淆
     *
     * @param string $string 要混淆的js代码
     * @return bool|string
     */
    private static function js_confusion($string)
    {
        if (empty($string)) return '';

        $str = $res = $eval = '';
        $string = iconv('UTF-8', 'UCS-2', $string);
        $len = strlen($string);
        for ($i = 0; $i < $len - 1; $i = $i + 2) {
            $c = $string[$i];
            $c2 = $string[$i + 1];
            if (ord($c) > 0) {
                // 两个字节的文字
                $str .= '\u' . base_convert(ord($c), 10, 16) . base_convert(ord($c2), 10, 16);
            } else {
                $str .= $c2;
            }
        }
        if (!empty($str)) {
            $str = bin2hex($str); // 这里替换成你要混淆的js代码
            for ($i = 0; $i < strlen($str) - 1; $i += 2) {
                $tmp = '\x' . $str[$i] . $str[$i + 1];
                $res .= $tmp;
            }
            $eval = 'eval(\'' . $res . '\')';
        }
        return $eval;
    }
}