<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImageVerification
{
    /**
     *
     * @param  $checkName 验证码cookie中存储的键值
     * @param int $codeLength 验证码字符长度
     * @param string $codeType 验证码组成字符 (ALL、CHAR、NUMBER)
     * @param string $background 验证码背景
     */
    public static function getImages($checkName, $codeLength = 4, $codeType = 'NUMBER', $background = '')
    {
        $cookiePath = config_item('cookie_path');
        $cookieDomain = config_item('cookie_domain');

        $nonceStr = time();

        $captcha = self::genCode($nonceStr, $codeLength, $codeType);
        setcookie($checkName, $captcha, 0, $cookiePath, $cookieDomain);
        self:: getAuthImage($captcha, $background);
    }

    /**
     * 生成图片验证码并输出
     * @param string $nonceStr 生成验证码的种子字符串
     * @param int $codeLength 验证码长度
     * @param string $codeType 验证码类型：ALL(字母数字混合), CHAR(纯字符), NUMBER(默认,纯数字)
     * @param string $background
     * @since 2018-03-29
     */
    public static function createCaptcha($nonceStr, $codeLength = 4, $codeType = 'NUMBER', $background = '')
    {
        $captcha = self::genCode($nonceStr, $codeLength, $codeType);
        self:: getAuthImage($captcha, $background);
    }

    /**
     * 校验验证码
     * @param string $captcha 用户输入的验证码
     * @param string $nonceStr 成验证码的种子字符串
     * @param int $codeLength 验证码长度
     * @param string $codeType 验证码类型：ALL(字母数字混合), CHAR(纯字符), NUMBER(默认,纯数字)
     * @return bool 验证码是否正确
     * @since 2018-03-29
     */
    public static function verifyCaptcha($captcha, $nonceStr, $codeLength = 4, $codeType = 'NUMBER')
    {
        return $captcha == self::genCode($nonceStr, $codeLength, $codeType);
    }

    private static function getAuthImage($text, $background = '')
    {
        $im_x = 80;
        $im_y = 30;
        $im = imagecreatetruecolor($im_x, $im_y);
        if ($background && $background == 'korea') {
            $text_c = ImageColorAllocate($im, 255, 0, 0);
            $buttom_c = ImageColorAllocate($im, 255, 255, 255);
        } else {
            $text_c = ImageColorAllocate($im, 255, 255, 255);
            $buttom_c = ImageColorAllocate($im, 0, 0, 0);
        }
        imagefill($im, 16, 13, $buttom_c);
        $font = getenv('BASEPATH') . '/internal/Arial.ttf';
        for ($i = 0; $i < strlen($text); $i++) {
            $tmp = substr($text, $i, 1);
            $size = 14;
            imagettftext($im, $size, 0, $i * $size, 20, $text_c, $font, $tmp);
        }
        $distortion_im = imagecreatetruecolor($im_x, $im_y);
        imagefill($distortion_im, 16, 13, $buttom_c);
        for ($i = 0; $i < $im_x; $i++) {
            for ($j = 0; $j < $im_y; $j++) {
                $rgb = imagecolorat($im, $i, $j);
                if ((int)($i + 20 + sin($j / $im_y * 2 * M_PI) * 10) <= imagesx($distortion_im) && (int)($i + 20 + sin($j / $im_y * 2 * M_PI) * 10) >= 0) {
                    imagesetpixel($distortion_im, (int)($i + 10 + sin($j / $im_y * 2 * M_PI - M_PI * 0.1) * 4), $j, $rgb);
                }
            }
        }
        // 加入干扰象素;
        $count = 20; //干扰像素的数量
        for ($i = 0; $i < $count; $i++) {
            $randColor = ImageColorallocate($distortion_im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
            imagesetpixel($distortion_im, mt_rand() % $im_x, mt_rand() % $im_y, $randColor);
        }
        // 设置文件头;
        Header("Content-type: image/JPEG");
        // 以PNG格式将图像输出到浏览器或文件;
        ImagePNG($distortion_im);
        // 销毁一图像,释放与image关联的内存;
        ImageDestroy($distortion_im);
        ImageDestroy($im);
    }

    /**
     * @param string $str 生成验证码的字符
     * @param int $len
     * @param string $format
     * @param string $salt
     * @return bool|string
     * @since 2018-03-29
     */
    private static function genCode($str, $len = 4, $format = 'NUMBER', $salt = '')
    {
        $salt = $salt ? $salt : 'en.pop-fashion.com-secret-str';
        if (!$str) {
            return false;
        }
        $_str = $str . $salt;
        $_str = trim(base64_encode(md5($_str)), '=');
        switch ($format) {
            case 'ALL':
                $chars = preg_replace('/0|o|O/', '', $_str);
                break;
            case 'CHAR':
                $chars = preg_replace('/\d|o|O/', '', $_str);
                break;
            case 'NUMBER':
            default:
                $chars = base_convert($_str, 36, 10);
                $chars = str_replace('0', '', $chars);
                break;
        }
        return substr($chars, 0, $len);
    }
} 
