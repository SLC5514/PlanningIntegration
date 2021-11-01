<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImageVerification {
	/**
	 * 
	 * @param  $checkName 验证码cookie中存储的键值
	 * @param int $codeLength 验证码字符长度
	 * @param string $codeType 验证码组成字符 (ALL、CHAR、NUMBER)
	 * @param string $background 验证码背景
	 */
	public static function getImages($checkName, $codeLength = 4, $codeType = 'NUMBER', $background = '') {
		$CI = &get_instance();
		$cookiePath = $CI -> config -> item('cookie_path');
		$cookieDomain = $CI -> config -> item('cookie_domain');

		$checkCode = self :: make_rand($codeLength, $codeType);
		setcookie($checkName, $checkCode, 0, $cookiePath, $cookieDomain);
		self :: getAuthImage($checkCode , $background, $checkName);
	} 

	private static function getAuthImage($text, $background = '', $checkName = "") {
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
		for ($i = 0;$i < strlen($text);$i++) {
			$tmp = substr($text, $i, 1);
			$size = 14;
			imagettftext($im, $size, 0, $i * $size, 20, $text_c, $font, $tmp);
		} 
		$distortion_im = imagecreatetruecolor ($im_x, $im_y);
		imagefill($distortion_im, 16, 13, $buttom_c);
		for ($i = 0; $i < $im_x; $i++) {
			for ($j = 0; $j < $im_y; $j++) {
				$rgb = imagecolorat($im, $i , $j);
				if ((int)($i + 20 + sin($j / $im_y * 2 * M_PI) * 10) <= imagesx($distortion_im) && (int)($i + 20 + sin($j / $im_y * 2 * M_PI) * 10) >= 0) {
					imagesetpixel ($distortion_im, (int)($i + 10 + sin($j / $im_y * 2 * M_PI - M_PI * 0.1) * 4) , $j , $rgb);
				} 
			} 
		} 
		// 加入干扰象素;
		$count = 20; //干扰像素的数量
		for($i = 0; $i < $count; $i++) {
			$randColor = ImageColorallocate($distortion_im, mt_rand(0, 255), mt_rand(0, 255), mt_rand(0, 255));
			imagesetpixel($distortion_im, mt_rand() % $im_x , mt_rand() % $im_y , $randColor);
		} 
		// 设置文件头;
		Header("Content-type: image/JPEG"); 
		// 以PNG格式将图像输出到浏览器或文件;
		ImagePNG($distortion_im); 
		// 销毁一图像,释放与image关联的内存;
		ImageDestroy($distortion_im);
		ImageDestroy($im);
	} 

	private static function make_rand($length = 4, $format = 'ALL') {
		switch ($format) {
			case 'ALL':
				$chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz123456789';
				break;
			case 'CHAR':
				$chars = 'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz';
				break;
			case 'NUMBER':
				$chars = '123456789';
				break;
		} 
		$string = "";
		while (strlen($string) < $length) {
			$string .= substr($chars, (mt_rand() % strlen($chars)), 1);
		} 
		return $string;
	} 
} 
