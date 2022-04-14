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
    private $allowed_types = [ 'gif', 'jpg', 'png', 'jpeg' ];	//允许上传的文件格式
    /*---------------------------------------------------------------------------------
     * @todo  上传图片（PS：此上传是通过调接口上传到图片服务器上）
     * @param  $file [string]  图片的$_FILES
     * @param  $size [int]     图片的大小限制
     *---------------------------------------------------------------------------------*/
    public function uploadPic($file,$savePath,$size=5242880)
    {
        $imgInfo = $this->getFileStream($file,$size);
        if($imgInfo===false){
            return false;
        }else{
            $body = [
                'fName' => md5( date('ymdHis').rand(0,10000) ).'.'.$imgInfo['ext'],
                'fTargetPath' => $savePath,
                'fStream' => $imgInfo['stream'],
                'fWidth' => $imgInfo['width'],
                'fHeight' => $imgInfo['height'],
                'fPrefix' => 'material',
                'allowSuffix' => implode(',', $this->allowed_types),
            ];
            $resJson = $this->curlRequest($body);
            $resArr = json_decode($resJson, true);
            $result['success'] = $resArr['status'];
            if ($result['success'] == 1  && !empty($resArr['info']) ) {
                $path = $resArr['info'];
                return $path;   //上传成功返回图片路径
            }else{
                $this->error = $resArr["info"];
                return false;   //上传失败返回false，并赋值error信息
            }
        }
    }
    /*---------------------------------------------------------------------------------
    * @todo  切图处理（PS：此方法切的图为图片服务器上的图片）
    * @param  $source_image [string]    原图路径
    * @param  $x            [int]       图片x坐标
    * @param  $y            [int]       图片y坐标
    * @param  $width        [int]       图片长
    * @param  $height       [int]       图片宽
    * @param  $sourceImageId[int]       原大图ID（非必传,用于图片路径文件夹）
    *---------------------------------------------------------------------------------*/
    public function cutPic($source_image,$x,$y,$width,$height,$cut_w=0,$cut_h=0,$sourceImageId = false)
    {
        $sourceImageId = $sourceImageId === false ? date("Ymd"):$sourceImageId; //无原大图ID时，取时间
        $body = [
            'src_image' => $source_image,
            'dst_x' => $x,
            'dst_y' => $y,
            'dst_w' => $width,
            'dst_h' => $height,
			'cut_w' => $cut_w,
            'cut_h' => $cut_h,
            'imgid' => $sourceImageId,
            'action' => 'cutPicDeal',
        ];
        $resJson = $this->curlRequest($body);
        $resArr = json_decode($resJson,true);
        if ($resArr['status'] == 1 && !empty($resArr['info']))
        {
            return $resArr['info'];
        }
        else
        {
            $this->error = $resArr['info'];
            return false;
        }
    }
    /*---------------------------------------------------------------------------------
     * @todo  验证是否图片、大小是否超出，并返回图片二进制和长宽尺寸
     * @param  $file [string]  图片的$_FILES
     * @param  $size [int]     图片的大小限制
     *---------------------------------------------------------------------------------*/
    public function getFileStream( $file , $size=5242880 )
    {
        if( $file["error"]== 1){
            $this->error = '文件大小超过服务器限制'; //php.ini中的限制
            return false;
        }elseif($file["error"] > 1){
            $this->error = '上传失败';
            return false;
        }
        $fileInfo = getimagesize($file['tmp_name']);
        $fileExt = pathinfo($file['name'], PATHINFO_EXTENSION);
        // 如果是图像文件 检测文件格式
        if( in_array(strtolower($file['type']), ['image/gif','image/jpg','image/jpeg','image/bmp','image/png','image/swf']) && false === $fileInfo) {
            $this->error = '非法图像文件';
            return false;
        }
        $width = $fileInfo[0];
        $heigth = $fileInfo[1];

        $fileSize = filesize($file['tmp_name']);
        if ($fileSize > $size) {
            $this->error = '文件大小超出限制';
            return false;
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
        curl_close( $ch );

        return $http_resp;
    }
    public function getError()
    {
        return $this->error;
    }

}