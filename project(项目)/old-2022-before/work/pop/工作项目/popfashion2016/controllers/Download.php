<?php
/*
  * 下载相关
*/
defined('BASEPATH') OR exit('No direct script access allowed');

class Download extends POP_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('common_model');
    }

    /*
      + 发布会 下载包下载
      + $pareameter : 下载包类型-发布会主ID
    */
    public function presscon($parameter = '')
    {
        header("Cache-Control: no-cache, must-revalidate");
        if (!$parameter) {
            show_404();
            return false;
        }

        //获取权限类型
        $aPower = memberPower();

        $aParameter = explode('-', $parameter);

        $iMid = intval($aParameter[1]);

        if ($iMid == 0 || ($aPower['P_UserType'] != 1 && $aPower['P_UserType'] != 2)) {
            show_404();
            return false;
        }
        $aPresscon = OpPopFashionMerger::getProductData($iMid, OpPopFashionMerger::POP_Table_Name_Product_Presscon, true);

        if (!$aPresscon) {
            show_404();
            return false;
        }
        $aPresscon = $aPresscon[$iMid];

        $sRarField = '';
        switch ($aParameter[0]) {
            case 'detail':
            case 'live':
                $aParameter[0] = 'detail';
                $sRarField = 'rar_file';
                $path = $aPresscon['sPath'];
                break;
            case 'special':
                $sRarField = 'rar_file_special';
                $path = $aPresscon['sPath_special'];
                break;
            case 'focus':
                $sRarField = 'rar_file_focus';
                $path = $aPresscon['sPath_focus'];
                break;
        }

        $dDownloadOpenTime = $aPresscon['download_open_time'];
        if (time() - strtotime($dDownloadOpenTime) <= 0) {
            show_404();
            return false;
        }

        $sDownloadUrl = $this->changeDownUrl(DOWNLOAD_PRESSCON_PATH . ltrim($path, '/') . '/' . $aPresscon[$sRarField], 'zip');

        $rename = getRenameStr($aPresscon['nme']);// 下载重命名 书籍标题
        $rename = $rename ? '&rename=' . urlencode($rename) . '.' . pathinfo($aPresscon[$sRarField], PATHINFO_EXTENSION) : '';

        header('location:' . $sDownloadUrl . $rename);
        exit;

    }

    /**
     * 手稿合辑 下载包下载
     * @param string $parameter :假表名-ID ($tableName-$iMid)
     * @return bool
     */
    public function book($parameter = '')
    {
        header("Cache-Control: no-cache, must-revalidate");
        if (!$parameter) {
            show_404();
            return FALSE;
        }
        //获取权限类型
        $aPower = memberPower();
        $aParameter = explode('-', $parameter);
        $iMid = intval($aParameter[1]);
        $tableName = getProductTableName($aParameter[0]);
        if ($iMid == 0 || !$tableName || ($aPower['P_UserType'] != 1 && $aPower['P_UserType'] != 2)) {
            show_404();
            return FALSE;
        }
        $aBook = OpPopFashionMerger::getProductData($iMid, $tableName);

        if (!$aBook) {
            show_404();
            return FALSE;
        }
        $aBook = $aBook[$iMid];
        switch ($tableName) {
            case 'mostrend_content':// pdf下载
                $rarPath = '/' . $aBook['path'] . $aBook['pdf_name'];
                $rename = $aBook['title'];
                break;
            case 'product_design_refrence':
                $rarPath = DOWNLOAD_DESIGN_REFRENCE_PATH . $aBook["dir_name"] . "/" . $aBook["typ"] . "/" . $aBook["region"] . "/" . $aBook["for_date"] . "/" . $aBook["category"] . "/" . $aBook["nme"] . "/detail/" . $aBook["rar_file"];
                $rename = $aBook['name_text'];
                break;
            case 'product_brochures':
                $rarPath = DOWNLOAD_BROCHURES_PATH . $aBook["path"] . "/detail/" . $aBook["rar_file"];
                $rename = $aBook['name_text'];
                break;
            case 'product_vector_refrence_group':// 单张 theme
                break;
        }

        $rename = getRenameStr($rename);// 下载重命名 书籍标题
        $rename = $rename ? '&rename=' . urlencode($rename) . '.'  . pathinfo($rarPath, PATHINFO_EXTENSION) : '';
        $sDownloadUrl = $this->changeDownUrl($rarPath, 'zip') .$rename;

        header('location:' . $sDownloadUrl);
        exit;
    }


    public function magazine($parameter = '')
    {
        header("Cache-Control: no-cache, must-revalidate");
        if (!$parameter) {
            show_404();
            return FALSE;
        }
	    $rename = '';
	    if (isset($_GET['rename']) && !empty($_GET['rename'])) {
			$rename = $_GET['rename'];
	    }
        //获取权限类型
        $aPower = memberPower('other');
        $aParameter = explode('-', $parameter);
        $iMid = $aParameter[0];
        $viewModel = isset($aParameter[1]) && !empty($aParameter[1]) ? trim($aParameter[1]) : '';

        if ($iMid == 0 || ($aPower['P_UserType'] != 1 && $aPower['P_UserType'] != 2)) {
            show_404();
            return FALSE;
        }

        $this->load->model('books_model');
        $rarPath = $this->books_model->getMagazineFilePath($iMid, $viewModel);

        $sDownloadUrl = $this->changeDownUrl($rarPath, 'zip');
	    if (!empty($rename)) {
		    $sDownloadUrl .= '&rename=' . urlencode($rename) . '.zip';
	    }
        header('location:' . $sDownloadUrl);
        exit;
    }


    /*
      + 单张 或 单文件下载
      + 下载图片方式 /download/dlsingle/?dl_link=https%3A%2F%2F2016.pop-fashion.com%2Fglobal%2Fimages%2Fimg1.pdf
    */
    public function dlsingle()
    {
        $sDlLink = $this->input->get('dl_link') ? html_entity_decode(urldecode($this->input->get('dl_link'))) : '';
        $aPower = memberPower();

        if (!$sDlLink || ($aPower['P_UserType'] != 1 && $aPower['P_UserType'] != 2)) {
            show_404();
            return false;
        }
        $rename = parse_url($sDlLink, PHP_URL_QUERY);
	    $rename = !empty($rename) ? urlencode(end(explode('=', $rename))) : "";
	    $rename = !empty($rename) ? ('&rename=' . $rename) : "";
	    if (strpos($sDlLink, "https:") === FALSE) {
            $sDlLink = '/' . ltrim($sDlLink, '/');
	    } else {
		    $sDlLink = parse_url($sDlLink, PHP_URL_PATH);
        }
        $sExtensionType = strtolower(pathinfo($sDlLink, PATHINFO_EXTENSION));

        //downzip1|2|3 下载的扩展名
        $aExtensionType = array('psd', 'cdr', 'pdf', 'eps', 'zip', 'rar', 'ai');

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
      + 下载路径加密钥
      + $path:源文件路径
      + $type:  image || zip
    */
    public function changeDownUrl($path, $type, $source = '')
    {
        $path = trim( $path );
        $path = preg_replace("/[\/]{2,}/", "/", $path);
        if ($source != "") {
            $num = $source;
        } else {
            $num = $this->getNum($path);
        }

        $zip_num = $this->getZipNum($path);
        $secret = 'pop-136-down';
        $expire = time() + 3600;
        $md5 = base64_encode(md5($secret . $path . $expire, true));
        $md5 = strtr($md5, '+/', '-_');
        $md5 = str_replace('=', '', $md5);

        if ($type == "zip") {
            // 服装打包下载downzipf.pop-fashion.com
            if (intval($this->input->get('flag')) === 1) {
                $zip_num = 'f';
            }

            $url_host = "//downzip" . $zip_num . ".pop-fashion.com";
        } else {
            $url_host = "//downimg" . $num . ".pop-fashion.com";
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