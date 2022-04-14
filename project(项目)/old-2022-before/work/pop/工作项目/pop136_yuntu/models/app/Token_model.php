<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/23
 * Time: 9:18
 */

class Token_model
{
    /**
     * jwt  构建token -- 加密
     *
     * 学习文档
     * https://jwt.io/
     * https://github.com/firebase/php-jwts
     *
     * 生成jwt字符串
     * @param array $payload 要在jwt中携带的信息
     * @param int $exp 时间戳：过期时间；payload 和 exp 均不设置则永不过期
     * @return string 生成的jwt字符串
     */
    public function jwt_encode($payload = [], $exp = 0)
    {
        /* jwt标准中注册的声明
        array(
            "iss" => "http://example.org", // jwt签发者
            "sub" => "", // jwt面向的用户
            "aud" => "http://example.com", // jwt接收者
            "iat" => 1356999524, // jwt签发时间
            "nbf" => 1357000000, // jwt生效时间，在该时间前一直无效
            "exp" => 1520000000, // jwt的过期时间，这个过期时间必须要大于签发时间
            "jti" => '', // jwt的唯一身份标识，主要用来作为一次性token，从而回避重放攻击。
        );
        */
        $now = time();
        $_payload = array(
            "iat" => $now,
            "nbf" => $now,
        );

        $payload = array_merge($_payload, $payload);
        if ($exp > 0) {
            $payload['exp'] = $exp;
        }

        // 签发服务器和验证服务器有时间差时设置，不要设置太大
        // JWT::$leeway = 60;
        return \Firebase\JWT\JWT::encode($payload, config_item('encryption_key'));
    }

    /**
     * 校验jwt是否有效--解密
     * @param string $jwt
     * @return array|bool 有效则返回 jwt 数组，无效返回 false
     */
    public function jwt_verify($jwt)
    {
        $decoded = null;

        try {
            $decoded = \Firebase\JWT\JWT::decode($jwt, config_item('encryption_key'), array('HS256'));

        } catch (\Firebase\JWT\ExpiredException $e) {
            // 过期
            outPrintApiJson(20001, 'Token过期，请重新获取');
        } catch (\Exception $e) {
            outPrintApiJson(100012, 'Token校验不成功');
        }
        /*
         * NOTE: This will now be an object instead of an associative array. To get
         * an associative array, you will need to cast it as such:
         */
        return (array)$decoded;
    }

}