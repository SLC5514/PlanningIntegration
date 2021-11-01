<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/15
 * Time: 17:03
 */
/**
 * @toDo 详情页下载图片
 */
class Download extends POP_Controller
{
    /*
         + $aStylesUrlName 款式类路径判断配置
         + $aBooksUrlName  书刊类路径判断配置
       */
    private static $aStylesUrlName = array(
        'presscon', 'perform', 'brand', 'marketphoto', 'street', 'fabricgallery'
    );

    private static $aBooksUrlName = array(
        'magazine', 'stylegraphic', 'brochures', 'graphic', 'ordermeeting', 'designreference'
    );

    /*
          + 单张 或 单文件下载
          + 下载图片方式 https://yuntu.pop136.com/download/downimg/?dl_link=http%3A%2F%2F2016.pop-fashion.com%2Fglobal%2Fimages%2Fimg1.jpg
        */
    public function downImg(){

        $sDlLink = $this->input->get('dl_link') ? html_entity_decode(urldecode($this->input->get('dl_link'))) : '';
        if (!$sDlLink) {
            show_404();
            return false;
        }
        $rename = parse_url($sDlLink, PHP_URL_QUERY);
        $rename = !empty($rename) ? urlencode(end(explode('=', $rename))) : "";
        $rename = !empty($rename) ? ('&rename=' . $rename) : "";

        if (strpos($sDlLink, "https:") === FALSE && strpos($sDlLink, "http:") === FALSE) {
            $sDlLink = '/' . ltrim($sDlLink, '/');
        } else {
            $sDlLink = parse_url($sDlLink, PHP_URL_PATH);
        }
        $sExtensionType = strtolower(pathinfo($sDlLink, PATHINFO_EXTENSION));
        //downzip1|2|3 下载的扩展名
        $aExtensionType = array( 'cdr', 'eps', 'zip', 'ai','bmp');
        $sDomainForm = array_search($sExtensionType, $aExtensionType) === false ? 'image' : 'zip';

        // 有无水印大图，则下载无水印大图
        if ($sDomainForm == 'image') {
            $nowaterLink = str_replace('/fashion/', '/fashion/nowatermark/', $sDlLink);
            if (api_file_exists(rtrim(FCPATH, '/') . $nowaterLink)) {
                $sDlLink = $nowaterLink;
            }
        }

        $sDownloadUrl = $this->changeDownUrl($sDlLink, $sDomainForm);
        header('Location:' . $sDownloadUrl . "{$rename}");
        exit;
    }

    /*
  + 下载路径加密钥
  + $path:源文件路径
  + $type:  image || zip
*/
    public function changeDownUrl($path, $type, $source = '')
    {
        $path = trim(urldecode($path));
        $path = preg_replace("/[\/]{2,}/", "/", $path);
        if ($source != "") {
            $num = $source;
        } else {
            $num = $this->getNum($path);
        }

        $pathArr = explode('/', $path);

        $zipnum = $this->getZipNum($path);
        $secret = 'pop-136-down';
        $expire = time() + 3600;
        $md5 = base64_encode(md5($secret . $path . $expire, true));
        $md5 = strtr($md5, '+/', '-_');
        $md5 = str_replace('=', '', $md5);

        if ($type == "image") {
            $url_host = "https://downzip" . $num . ".pop136.com";
        } else if ($type == "zip") {
            $url_host = "https://downzip" . $zipnum . ".pop136.com";
        }
        $url = $url_host . $path . "?string=" . $md5 . "&time=" . $expire;
        return $url;
    }


    protected function getNum($path)
    {
        for ($i = 0; $i < count(self::$aStylesUrlName); $i++) {
            if (strpos($path, self::$aStylesUrlName[$i])) {
                $num = "1";
                return $num;
            }
        }
        for ($i = 0; $i < count(self::$aBooksUrlName); $i++) {
            if (strpos($path, self::$aBooksUrlName[$i])) {
                $num = "2";
                return $num;
            }
        }
        $num = "3";
        return $num;
    }

    protected function getZipNum($path)
    {
        for ($i = 0; $i < count(self::$aStylesUrlName); $i++) {
            if (strpos($path, self::$aStylesUrlName[$i])) {
                $num = "1";
                return $num;
            }
        }
        for ($i = 0; $i < count(self::$aBooksUrlName); $i++) {
            if (strpos($path, self::$aBooksUrlName[$i])) {
                $num = "2";
                return $num;
            }
        }
        $num = "3";
        return $num;
    }


}