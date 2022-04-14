<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/11/25
 * Time: 15:15
 */
class POP_Uploadpic
{
    public $error;
    public function __construct()
    {
    }

    public function getFileStream( $file , $size=5242880 )
    {
        $fileInfo = getimagesize($file['tmp_name']);
        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
        // 如果是图像文件 检测文件格式
        if( in_array(strtolower($file['type']), ['image/gif','image/jpg','image/jpeg','image/bmp','image/png','image/swf']) && false === $fileInfo) {
            $this->error = '非法图像文件';
            return '';
        }

        $width = $fileInfo[0];
        $heigth = $fileInfo[1];

        $fileSize = filesize($file['tmp_name']);
        if ($fileSize > $size) {
            $this->error = '文件大小超出限制';
            return '';
        }

        $handle = fopen($file['tmp_name'], "rb");
        $fileStream = fread($handle, $fileSize);
        fclose($handle);
        return ['width'=>$width, 'height'=>$heigth, 'stream'=>$fileStream, 'ext'=>$fileExt];
    }

    public function curlRequest($body)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.pop136.com/internal/UploadApi.php');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT,120);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_POST, true);
        $body = http_build_query( $body );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $body );
        $http_resp = curl_exec($ch);
//        $e1 = curl_error($ch);
//        $e2 = curl_errno($ch);
//        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close( $ch );

        return $http_resp;
    }

    public function getError()
    {
        return $this->error;
    }

}