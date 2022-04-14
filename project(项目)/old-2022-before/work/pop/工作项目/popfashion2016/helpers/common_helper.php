<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 自定义一些全局公用的方法
 * 最好是加上方法注释
 */

if (!function_exists('make_hash')) {
    function make_hash($arr)
    {
        if (!empty($arr)) {
            $str = "";
            foreach ($arr as $key => $value) {
                $str .= $value;
            }
            return md5($str);
        } else {
            return false;
        }
    }
}

if (!function_exists('get_cookie_value')) {
    function get_cookie_value()
    {
        $ci = &get_instance();
        $ci->load->helper(['cookie']);
        $arr_cookie = array();
        if (!empty($_COOKIE["POP_USER"])) {
            $cookieUser = SsoMember::getCookieUser();
            if (SsoMember::isMustLogout()) {
                SsoMember::clearLoginCookie();
            }
            if (!empty($cookieUser)) {
                $arr_cookie = $cookieUser;
                $arr_cookie["id"] = $cookieUser["userId"];
                if (!empty($cookieUser["childId"])) {
                    $arr_cookie["iAccountType"] = 2;
                    $arr_cookie["sChildAccount"] = $cookieUser["account"];
                    $arr_cookie["sChildID"] = $cookieUser["childId"];
                } else {
                    $arr_cookie["iAccountType"] = 1;
                    $arr_cookie["account"] = $cookieUser["account"];
                }
                $arr_cookie["p_ip"] = $cookieUser["checkIpNum"];
            }
        } elseif (isset($_COOKIE['user_info']) && !empty($_COOKIE['user_info'])) {
            //暂时兼容老登录
            $str_cookie = decrypt($_COOKIE['user_info']);
            $arr_cookie = unserialize($str_cookie);
        }
        SsoMember::setOtherUserCookie();
        return $arr_cookie;
    }
}

//清除所有已经登录的当前用户ID(修改密码时统一退出);
if (!function_exists('clear_login_user_cache')) {
    function clear_login_user_cache()
    {
        SsoMember::setUserLogoutCache();
    }
}

//获取用户ID
if (!function_exists('getUserId')) {
    function getUserId()
    {
        $userInfo = get_cookie_value();
        $UserId = $userInfo['iAccountType'] == 2 ? $userInfo['sChildID'] : $userInfo['id'];
        $UserId = empty($UserId) ? "" : $UserId;
        return $UserId;
    }
}

if (!function_exists('encrypt')) {
    function encrypt($text)
    {
        return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, 'SALT', $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }
}

if (!function_exists('decrypt')) {
    function decrypt($text)
    {
        return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, 'SALT', base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }
}

if (!function_exists('getImgPathByDir')) {
    /**
     * @todo    从指定目录中读取全部图片
     * @name    getImgPathByDir
     * @param   string $dir
     * @return  array
     */
    function getImgPathByDir($dir = '')
    {
        if (empty($dir) || !is_dir($dir)) return array();
        // 返回当前目录下的所有文件
        $image = array("jpg", "gif", "png");
        $files = glob($dir . '/*');
        $tmp = array();
        foreach ($files as $file) {
            $suffix = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($suffix, $image))
                continue;
            $tmp[] = $file;
        }
        return $tmp;
    }
}

if (!function_exists('getImagePath')) {
    /**
     * @todo    根据表名和信息获得图片
     * @name    getImagePath
     * @param   tablename  string  真实表名
     * @param   _res       array   图片信息
     * @param   mfs       string   图片路径前缀
     * @param   type      不知道什么东西
     * @return  array
     */
    function getImagePath($tablename = '', $_res = [], $mfs = '', $type = '')
    {
        $bigpath = $smallpath = $cover = '';
        $path = [];
        switch ($tablename) {
            //秀场提炼
            case 'product_perform':
                if ($_res['sBigPath']) {
                    $bigpath = $_res['sBigPath'];
                } else {
                    $bigpath = '/fashion/perform/' . $_res['dir_name'] . '/' . $_res['typ'] . '/' . $_res['region'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'big/' . md5($_res['imgname'] . 'www.pop-fashion.com' . ($_res['flag_status'] == 1 ? $_res['dir_name'] : '')) . '.jpg';
                }
                if ($_res['sSmallPath']) {
                    $smallpath = $cover = $_res['sSmallPath'];
                } else {
                    $smallpath = $cover = '/fashion/perform/' . $_res['dir_name'] . '/' . $_res['typ'] . '/' . $_res['region'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'middle/' . $_res['imgname'];
                }
                break;

            //名牌高清
            case 'product_brand':
                if ($_res['sBigPath']) {
                    $bigpath = $_res['sBigPath'];
                } else {
                    $bigpath = '/fashion/brand/' . $_res['dir_name'] . '/' . ($_res['is_newdata'] != 1 ? $_res['brand'] . '/' : '') . $_res['typ'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'big/' . md5($_res['small_image_name'] . 'www.pop-fashion.com' . ($_res['flag_status'] == 1 && $_res['is_newdata'] != 1 ? $_res['dir_name'] : '')) . '.jpg';
                }
                if ($_res['sSmallPath']) {
                    $smallpath = $cover = $_res['sSmallPath'];
                } else {
                    $smallpath = $cover = '/fashion/brand/' . $_res['dir_name'] . '/' . ($_res['is_newdata'] != 1 ? $_res['brand'] . '/' : '') . $_res['typ'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'middle/' . $_res['small_image_name'];
                }
                break;

            //款式流行
            case 'product_style_graphic':
                //国内市场
            case 'product_style_graphic_china':
                if ($_res['sBigPath']) {
                    $bigpath = $_res['sBigPath'];
                } else {
                    $bigpath = '/fashion/stylegraphic/' . $_res['dir_name'] . '/' . $_res['typ'] . '/' . $_res['region'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'big/' . md5($_res['imgname'] . 'www.pop-fashion.com' . ($_res['flag_status'] == 1 ? $_res['dir_name'] : '')) . '.jpg';
                }
                if ($_res['sSmallPath']) {
                    $smallpath = $cover = $_res['sSmallPath'];
                } else {
                    $smallpath = $cover = '/fashion/stylegraphic/' . $_res['dir_name'] . '/' . $_res['typ'] . '/' . $_res['region'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'middle/' . $_res['imgname'];
                }
                break;

            //商场爆款
            case 'product_marketphoto_item':
                if ($_res['sBigPath']) {
                    $bigpath = $_res['sBigPath'];
                } else {
                    $bigpath = '/fashion/marketphoto/' . $_res['dir_name'] . '/' . $_res['region'] . '/' . $_res['typ'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'big/' . md5($_res['category'] . $_res['small_image_name'] . 'www.pop-fashion.com') . '.jpg';
                }
                if ($_res['sSmallPath']) {
                    $smallpath = $cover = $_res['sSmallPath'];
                } else {
                    $smallpath = $cover = '/fashion/marketphoto/' . $_res['dir_name'] . '/' . $_res['region'] . '/' . $_res['typ'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'middle/' . $_res['small_image_name'];
                }
                break;

            //款式流行细节
            case 'product_style_graphic_details':
                if ($_res['sBigPath']) {
                    $bigpath = $_res['sBigPath'];
                } else {
                    $bigpath = '/fashion/stylegraphic/' . $_res['dir_name'] . '/' . $_res['typ'] . '/' . $_res['region'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'big/' . md5($_res['imgname'] . 'www.pop-fashion.com' . ($_res['flag_status'] == 1 ? $_res['dir_name'] : '')) . '.jpg';
                }
                if ($_res['sSmallPath']) {
                    $smallpath = $cover = $_res['sSmallPath'];
                } else {
                    $smallpath = $cover = '/fashion/stylegraphic/' . $_res['dir_name'] . '/' . $_res['typ'] . '/' . $_res['region'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'middle/' . $_res['imgname'];
                }
                break;

            //图案花型
            case 'product_graphicitem':
                //$bigpath = '/fashion/graphic/' . $_res['dir_name'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . '/big/' . $_res['big_image_name'];
                //$smallpath = $cover = '/fashion/graphic/' . $_res['dir_name'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . '/small/' . $_res['small_image_name'];
                //新数据  主图加细节图
                $aDetailImg = json_decode($_res['sPathDetails'], true);
                $aDetail = [];
                $key = 0;
                if (is_array($aDetailImg) && $aDetailImg) {
                    foreach ($aDetailImg as $_smallImg => $_bigImg) {
                        $aDetail[$key]['smallPath'] = $_res['sPath'] . 'small/' . $_smallImg;
                        $aDetail[$key]['bigPath'] = $_res['sPath'] . 'big/' . $_bigImg;
                        //加静态域名
                        $aDetail[$key]['smallPath'] = getStaticUrl($aDetail[$key]['smallPath']) . $aDetail[$key]['smallPath'];
                        $aDetail[$key]['bigPath'] = getStaticUrl($aDetail[$key]['bigPath']) . $aDetail[$key]['bigPath'];
                        $key++;
                    }
                }
                $path['detailImg'] = $aDetail;
                $bigpath = $_res['sPath'] . 'big/' . $_res['big_image_name'];
                $smallpath = $cover = $_res['sPath'] . 'small/' . $_res['small_image_name'];
                break;

            //图案工艺
            case 'product_graphic_craft_item':
                //新数据  主图加细节图
                $aDetailImg = json_decode($_res['sPathDetails'], true);
                $aDetail = [];
                $key = 0;
                if (is_array($aDetailImg) && $aDetailImg) {
                    foreach ($aDetailImg as $_smallImg => $_bigImg) {
                        $aDetail[$key]['smallPath'] = $_res['sPath'] . 'small/' . $_smallImg;
                        $aDetail[$key]['bigPath'] = $_res['sPath'] . 'big/' . $_bigImg;
                        //加静态域名
                        $aDetail[$key]['smallPath'] = getStaticUrl($aDetail[$key]['smallPath']) . $aDetail[$key]['smallPath'];
                        $aDetail[$key]['bigPath'] = getStaticUrl($aDetail[$key]['bigPath']) . $aDetail[$key]['bigPath'];
                        $key++;
                    }
                }
                $path['detailImg'] = $aDetail;
                $bigpath = $_res['sPath'] . 'big/' . $_res['big_image_name'];
                $smallpath = $cover = $_res['sPath'] . 'small/' . $_res['small_image_name'];
                break;

            //面料图库
            case 'product_fabricgallery_item':
            case 'product_fabricgallery_item_detail':
                $bigpath = '/fashion/fabricgallery/' . $_res['dir_name'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/big/' . md5($_res['sub_category'] . $_res['small_image_name'] . 'www.pop-fashion.com') . '.jpg';
                $smallpath = $cover = '/fashion/fabricgallery/' . $_res['dir_name'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/small/' . $_res['small_image_name'];
                break;

            //街头时尚
            case 'product_streetitem':
                if ($_res['sBigPath']) {
                    $bigpath = $_res['sBigPath'];
                } else {
                    $bigpath = '/fashion/street/' . $_res['dir_name'] . '/' . $_res['region'] . '/' . $_res['typ'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'big/' . md5($_res['category'] . $_res['small_image_name'] . 'www.pop-fashion.com') . '.jpg';
                }
                if ($_res['sSmallPath']) {
                    $smallpath = $cover = $_res['sSmallPath'];
                } else {
                    $smallpath = $cover = '/fashion/street/' . $_res['dir_name'] . '/' . $_res['region'] . '/' . $_res['typ'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'middle/' . $_res['small_image_name'];
                }
                break;

            //订货会
            case 'product_ordermeeting':
                $renamefile = pathinfo($_res['small_image_name']);
                $extension = $renamefile['extension'];
                $imgbasename = $renamefile['filename'];
                if (isset($_res['sPathDetails']) && $_res['sPathDetails']) {
                    //新数据  主图加细节图
                    $aDetailImg = json_decode($_res['sPathDetails'], true);
                    $aDetail = [];
                    $key = 0;
                    if (is_array($aDetailImg) && $aDetailImg) {
                        foreach ($aDetailImg as $_smallImg => $_bigImg) {
                            $aDetail[$key]['smallPath'] = $_res['sPath'] . 'small/' . $_smallImg;
                            $aDetail[$key]['bigPath'] = $_res['sPath'] . 'big/' . $_bigImg;
                            if ($key == 0) { //主图
                                $bigpath = $aDetail[$key]['bigPath'];
                                $smallpath = $cover = $aDetail[$key]['smallPath'];
                            }
                            //加静态域名
                            $aDetail[$key]['smallPath'] = getStaticUrl($aDetail[$key]['smallPath']) . $aDetail[$key]['smallPath'];
                            $aDetail[$key]['bigPath'] = getStaticUrl($aDetail[$key]['bigPath']) . $aDetail[$key]['bigPath'];
                            $key++;
                        }
                    }
                    $path['detailImg'] = $aDetail;
                } else {
                    //老数据
                    $bigpath = $_res['sBigPath'];
                    $smallpath = $cover = $_res['sSmallPath'];
                }
                break;
            //品牌画册
            case 'product_brochures_photo':
                $bigpath = $_res['big_pic'];
                $smallpath = $cover = $_res['pic'];
                break;
            case 'mostrend_content':
                // 成册 channel=bookstore
                if ($_res['channel'] == 'bookstore') {//书店二级列表图片
                    $bookPath = [];
                    $pic_name = json_decode($_res['pic_name']);
                    if (is_array($pic_name)) {
                        foreach ($pic_name as $key => $val) {
                            $tmp = pathinfo($val, PATHINFO_FILENAME);
                            $bookPath[$key]['bigPath'] = getFixedImgPath($_res['path'] . 'big/' . md5($tmp . $_res['path'] . 'big!@#') . '.jpg');
                            $bookPath[$key]['smallPath'] = getFixedImgPath($_res['path'] . 'small/' . $val);
                            if ($mfs == '') {
                                $mfs1 = getStaticUrl($bookPath[$key]['bigPath']);
                                $mfs2 = getStaticUrl($bookPath[$key]['smallPath']);
                                $bookPath[$key]['bigPath'] = $mfs1 . $bookPath[$key]['bigPath'];
                                $bookPath[$key]['smallPath'] = $mfs2 . $bookPath[$key]['smallPath'];
                            }
                        }
                    }
                }
                //封面图
                $smallpath = $cover = getFixedImgPath($_res['path'] . '/' . $_res['big_thumbnail']);
                break;
            case 'product_templateitem':
                //单张
                $bigpath = '/fashion/template/' . $_res['dir_name'] . '/' . $_res['typ'] . '/' . $_res['category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'big/' . md5($_res['category'] . $_res['small_image_name'] . 'www.pop-fashion.com') . '.jpg';
                $smallpath = $cover = '/fashion/template/' . $_res['dir_name'] . '/' . $_res['typ'] . '/' . $_res['category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'middle/' . $_res['small_image_name'];
                break;
            case 'picture':
                //单张
                if ($_res['channel'] == 'library') {
                    $bigpath = $_res['path'] . 'big/' . $_res['picbig'];
                    $smallpath = $cover = $_res['path'] . 'small/' . $_res['pic'];
                } elseif ($_res['channel'] == 'inspiration') {
                    $bigpath = $cover = $smallpath = $_res['path'] . 'pic/' . $_res['pic'];

                } else {
                    $bigpath = $_res['path'] . $_res['picbig'];
                    $smallpath = $cover = $_res['path'] . $_res['pic'];
                }
                break;
            //款式细节
            case 'product_style_detail':
                // 单张
                if ($_res['sBigPath']) {
                    $bigpath = $_res['sBigPath'];
                } else {
                    $bigpath = '/fashion/styledetail/' . $_res['dir_name'] . '/' . $_res['region'] . '/' . $_res['typ'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'big/' . md5($_res['small_image_name'] . 'www.pop-fashion.com' . ($_res['flag_status'] == 1 ? $_res['dir_name'] : '')) . '.jpg';
                }
                if ($_res['sSmallPath']) {
                    $smallpath = $cover = $_res['sSmallPath'];
                } else {
                    $smallpath = $cover = '/fashion/styledetail/' . $_res['dir_name'] . '/' . $_res['region'] . '/' . $_res['typ'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . $_res['sub_category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'middle/' . $_res['small_image_name'];
                }
                break;
            case 'product_hat':
                //单张
                $bigpath = '/fashion/hat/' . $_res['dir_name'] . '/' . $_res['type'] . '/' . $_res['for_date'] . '/' . $_res['origin'] . '/' . $_res['category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'big/' . md5($_res['imgname'] . 'www.pop-fashion.com') . '.jpg';
                $smallpath = $cover = '/fashion/hat/' . $_res['dir_name'] . '/' . $_res['type'] . '/' . $_res['for_date'] . '/' . $_res['origin'] . '/' . $_res['category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'small/' . $_res['imgname'];
                break;
            case 'product_wrapsitem':
                //单张
                if ($_res['sBigPath']) {
                    $bigpath = $_res['sBigPath'];
                } else {
                    $bigpath = '/fashion/wraps/' . $_res['dir_name'] . '/' . $_res['typ'] . '/' . $_res['for_date'] . '/' . $_res['origin'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'big/' . md5($_res['origin'] . $_res['small_image_name'] . 'www.pop-fashion.com') . '.jpg';
                }
                if ($_res['sSmallPath']) {
                    $smallpath = $cover = $_res['sSmallPath'];
                } else {
                    $smallpath = $cover = '/fashion/wraps/' . $_res['dir_name'] . '/' . $_res['typ'] . '/' . $_res['for_date'] . '/' . $_res['origin'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'small/' . $_res['small_image_name'];
                }
                break;
            case 'product_glove':
                //单张
                $bigpath = '/fashion/glove/' . $_res['dir_name'] . '/' . $_res['type'] . '/' . $_res['for_date'] . '/' . $_res['origin'] . '/' . $_res['category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'big/' . md5($_res['imgname'] . 'www.pop-fashion.com') . '.jpg';
                $smallpath = $cover = '/fashion/glove/' . $_res['dir_name'] . '/' . $_res['type'] . '/' . $_res['for_date'] . '/' . $_res['origin'] . '/' . $_res['category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'small/' . $_res['imgname'];
                break;
            case 'product_shopset':
                //单张
                if (isset($_res['sBigPath']) && !empty($_res['sBigPath'])) {
                    $bigpath = $_res['sBigPath'];
                } else {
                    $bigpath = '/fashion/shopset/' . $_res['dir_name'] . '/' . $_res['country'] . '/' . $_res['city'] . '/' . rawurlencode($_res['stre']) . '/' . rawurlencode($_res['brand']) . '/' . 'big/' . md5($_res['imgname'] . 'www.pop-fashion.com') . '.jpg';
                }
                if (isset($_res['sSmallPath']) && !empty($_res['sSmallPath'])) {
                    $smallpath = $cover = $_res['sSmallPath'];
                } else {
                    $smallpath = $cover = '/fashion/shopset/' . $_res['dir_name'] . '/' . $_res['country'] . '/' . $_res['city'] . '/' . $_res['stre'] . '/' . $_res['brand'] . '/' . 'small/' . $_res['imgname'];
                }
                break;
            case 'specialtopic':
                if (!empty($_res['sCoverImg'])) {
                    $smallpath = $cover = getFixedImgPath($_res['sCoverImg']);
                } elseif (!empty($_res['sIndexCoverlink'])) {
                    //封面图来自韩版时特殊
                    if (stripos($_res['sIndexCoverlink'], '/korea/') !== FALSE) {
                        $_res['sIndexCoverlink'] = '/fashion/' . $_res['sIndexCoverlink'];
                    }
                    $smallpath = $cover = getFixedImgPath($_res['sIndexCoverlink']);
                } elseif (!empty($_res['sCoverlink'])) {
                    //封面图来自韩版时特殊
                    if (stripos($_res['sCoverlink'], '/korea/') !== FALSE) {
                        $_res['sCoverlink'] = '/fashion/' . $_res['sCoverlink'];
                    }
                    $smallpath = $cover = getFixedImgPath($_res['sCoverlink']);
                } elseif (!empty($_res['special_coverpic'])) {
                    //封面图来自韩版时特殊
                    if (stripos($_res['special_coverpic'], '/korea/') !== FALSE) {
                        $_res['special_coverpic'] = '/fashion/' . $_res['special_coverpic'];
                    }
                    $smallpath = $cover = getFixedImgPath($_res['special_coverpic']);
                }
                break;
            case 'fs_trend':
            case 'fs_design':
            case 'fs_analysis':
            case 'fs_inspiration':
            case 'specialtopic_graphic':
            case 'fs_commodity':
                if (!empty($_res['sCoverImg'])) {
                    $smallpath = $cover = getFixedImgPath($_res['sCoverImg']);
                } elseif (!empty($_res['cover_pic_assist'])) {
                    $smallpath = $cover = getFixedImgPath($_res['cover_pic_assist']);
                } elseif (!empty($_res['cover_pic'])) {
                    $smallpath = $cover = getFixedImgPath($_res['cover_pic']);
                } elseif (!empty($_res['special_coverpic'])) {
                    $smallpath = $cover = getFixedImgPath($_res['special_coverpic']);
                }
                break;
            case 't_trend_report':
                $smallpath = $cover = getFixedImgPath($_res['sImgPath']);
                break;
            case 'product_presscon':
                //封面图（两种）1表示封面为一张图，2表示封面为三张图拼的
                $sCoverImg = json_decode($_res['sCoverImg']);
                if ($_res['cover_image_tag'] == 1) {
                    $cover = '<img alt="' . $_res['nme'] . '" itemprop="thumbnail" src="' . STATIC_URL2 . '/global/images/loading/runway180.gif" data-original="' . STATIC_URL2 . $sCoverImg[0] . '" width="180" height="135"/>';
                    $smallpath = '<img alt="' . $_res['nme'] . '" itemprop="thumbnail" src="' . STATIC_URL2 . $sCoverImg[0] . '" width="180" height="135"/>';
                } else {
                    $cover = '<img alt="' . $_res['nme'] . '" itemprop="thumbnail" src="' . STATIC_URL2 . '/global/images/loading/runway.gif" data-original="' . STATIC_URL2 . $sCoverImg[0] . '" width="60" height="135"/>';
                    $cover .= '<img alt="' . $_res['nme'] . '" itemprop="thumbnail" src="' . STATIC_URL2 . '/global/images/loading/runway.gif" data-original="' . STATIC_URL2 . $sCoverImg[1] . '" width="60" height="135"/>';
                    $cover .= '<img alt="' . $_res['nme'] . '" itemprop="thumbnail" src="' . STATIC_URL2 . '/global/images/loading/runway.gif" data-original="' . STATIC_URL2 . $sCoverImg[2] . '" width="60" height="135"/>';
                    $smallpath = '<img alt="' . $_res['nme'] . '" itemprop="thumbnail" src="' . STATIC_URL2 . $sCoverImg[0] . '" width="60" height="135"/>';
                    $smallpath .= '<img alt="' . $_res['nme'] . '" itemprop="thumbnail" src="' . STATIC_URL2 . $sCoverImg[1] . '" width="60" height="135"/>';
                    $smallpath .= '<img alt="' . $_res['nme'] . '" itemprop="thumbnail" src="' . STATIC_URL2 . $sCoverImg[2] . '" width="60" height="135"/>';
                }
                break;
            case 'product_presscon_details':
                $smallpath = $cover = $_res['path'] . 'middle/' . $_res['small_name'];
                $bigpath = $_res['path'] . 'images/' . $_res['big_name'];
                break;
            case 't_inspiration_img_db'://灵感图库
                $bigpath = $_res['sBigImgPath'];
                $smallpath = $cover = $_res['sSmallImgPath'];
                break;
            case 'product_brochures':
                if ($_res['fas_image_address'] == '') {
                    $smallpath = $cover = '/fashion/brochures/graphic/' . $_res['path'] . '/images/1.jpg';
                } else {
                    $smallpath = $cover = '/fashion/brochures/graphic/' . $_res['fas_image_address'];
                }
                break;

            case 'product_docerationitem':
                //单张
                $bigpath = '/fashion/doceration/' . $_res['dir_name'] . '/' . $_res['origin'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'big/' . md5($_res['category'] . $_res['small_image_name'] . 'www.pop-fashion.com') . '.jpg';
                $smallpath = $cover = '/fashion/doceration/' . $_res['dir_name'] . '/' . $_res['origin'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . ($_res['name_code'] ? $_res['name_code'] . '/' : '') . 'small/' . $_res['small_image_name'];
                break;
            case 'product_design_refrence':
                //封面图
                $smallpath = $cover = '/fashion/designreference/graphic/' . $_res['dir_name'] . '/' . $_res['typ'] . '/' . $_res['region'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/' . $_res['nme'] . '/images/1.jpg';
                break;
            case 'product_design_refrence_details':
                $smallpath = $cover = $_res['small_pic'];
                $bigpath = $_res['big_pic'];
                break;
            case 'product_vector_refrence':
                $smallpath = $cover = '/fashion/vectorrefrence/' . $_res['dir_name'] . '/' . $_res['type'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/small/' . $_res['imgname'];
                $bigpath = '/fashion/vectorrefrence/' . $_res['dir_name'] . '/' . $_res['type'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/big/' . md5($_res['imgname'] . 'www.pop-fashion.com') . '.jpg';

                break;
            case 'product_vector_refrence_list':
            case 'product_vector_refrence_group':
                //封面图,没有这个表，需要取product_vector_refrence_group 和 product_vector_refrence 两张表的数据
                if ($_res['cover_img'] != '') {
                    $cover_img = $_res['cover_img'];
                    $smallpath = $cover = '/fashion/vectorrefrence/' . $cover_img;
                } else {
                    $cover_img = $_res['imgname'];
                    $smallpath = '/fashion/vectorrefrence/' . $_res['dir_name'] . '/' . $_res['type'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/small/' . $cover_img;
                    $cover = '/fashion/vectorrefrence/' . $_res['dir_name'] . '/' . $_res['type'] . '/' . $_res['for_date'] . '/' . $_res['category'] . '/middle/' . $cover_img;
                }
                break;
            case 'product_tideleader':
                //单张
                $bigpath = getFixedImgPath($_res['sBigPath']);
                $smallpath = $cover = getFixedImgPath($_res['sSmallPath']);
                break;
            //数码云打印
            case 't_digital_quick_print':
                if (isset($_res['sPathDetails']) && $_res['sPathDetails']) {
                    //新数据  主图加细节图
                    $aDetailImg = json_decode($_res['sPathDetails'], true);
                    $aDetail = [];
                    $key = 0;
                    if (is_array($aDetailImg) && $aDetailImg) {
                        foreach ($aDetailImg as $_smallImg => $_bigImg) {
                            $aDetail[$key]['smallPath'] = $_res['sPath'] . 'small/' . $_smallImg;
                            $aDetail[$key]['bigPath'] = $_res['sPath'] . 'big/' . $_bigImg;
                            if ($key == 0) { //主图
                                $bigpath = $aDetail[$key]['bigPath'];
                                $smallpath = $cover = $aDetail[$key]['smallPath'];
                            }
                            //加静态域名
                            $aDetail[$key]['smallPath'] = getStaticUrl($aDetail[$key]['smallPath']) . $aDetail[$key]['smallPath'];
                            $aDetail[$key]['bigPath'] = getStaticUrl($aDetail[$key]['bigPath']) . $aDetail[$key]['bigPath'];
                            $key++;
                        }
                    }
                    $path['detailImg'] = $aDetail;
                }
                break;
            case 'styles':
                if(isset($_res['id'])){
                    $imgs = $_res['imgs'];
                    $bigpath = $smallpath = $cover = '';
                    if($imgs){
                        $img_arr = json_decode($imgs, true);
                        foreach($img_arr as $v){
                            if(!empty($v['headimg'])){
                                $bigpath = $v['big'];
                                $smallpath = $cover = $v['small'];
                            }
                        }
                    }

                }
                break;
            default :
                $bigpath = '';
                $smallpath = '';
                break;
        }

        if ($mfs == '' && $tablename != 'product_presscon') {
            $mfs1 = getStaticUrl($bigpath);
            $mfs2 = getStaticUrl($smallpath);
            $mfs3 = getStaticUrl($cover);
            $path['bigPath'] = $mfs1 . $bigpath;
            $path['smallPath'] = $mfs2 . $smallpath;
            $path['cover'] = $mfs3 . $cover;
        } else {
            $path['bigPath'] = $mfs . $bigpath;
            $path['smallPath'] = $mfs . $smallpath;
            $path['cover'] = $mfs . $cover;
        }


        if (isset($bookPath) && !empty($bookPath)) {
            $path['booklist'] = $bookPath;
        }

        $path['bigPath'] = str_replace('https:/', 'https://', preg_replace('/[\/]{2,}/', '/', $path['bigPath']));
        $path['smallPath'] = str_replace('https:/', 'https://', preg_replace('/[\/]{2,}/', '/', $path['smallPath']));
        $path['cover'] = str_replace('https:/', 'https://', preg_replace('/[\/]{2,}/', '/', $path['cover']));
        //		$path['booklist'] = str_replace('http:/', 'http://', preg_replace('/[\/]{2,}/', '/', $path['booklist']));
        return $path;
    }
}

if (!function_exists('uuid')) {
    function uuid($prefix = '')
    {
        $chars = md5(uniqid(mt_rand(), true));
        $uuid = substr($chars, 0, 8) . '-';
        $uuid .= substr($chars, 8, 4) . '-';
        $uuid .= substr($chars, 12, 4) . '-';
        $uuid .= substr($chars, 16, 4) . '-';
        $uuid .= substr($chars, 20, 12);
        return $prefix . $uuid;
    }
}

if (!function_exists('EncryptionDeciphering')) {
    /**
     * 加密/解密
     */
    function EncryptionDeciphering($idAccount, $isEncry = true)
    {
        if ($isEncry) {//加密
            if ($idAccount) {
                return popEncode($idAccount, COOKIE_ENCRYPT_KEYS);
            }
        } else {//解密
            $idAccount = popDecode($idAccount, COOKIE_ENCRYPT_KEYS);
            if ($idAccount) {
                return $idAccount;
            }
        }
        return false;
    }
}

if (!function_exists('popEncode')) {
    function popEncode($enString, $key)
    {
        srand((double)microtime() * 1000000);
        $enKeys = md5(rand(0, 32000));
        $ctr = 0;
        $tmp = '';
        for ($i = 0; $i < strlen($enString); $i++) {
            if ($ctr = strlen($enKeys)) {
                $ctr = 0;
            }
            $tmp .= $enKeys[$ctr] . ($enString[$i] ^ $enKeys[$ctr++]);
        }
        return base64_encode(popKeys($tmp, $key));
    }
}

if (!function_exists('popDecode')) {
    function popDecode($txt, $key)
    {
        $txt = popKeys(base64_decode($txt), $key);
        $tmp = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $md5 = $txt[$i];
            $tmp .= $txt[++$i] ^ $md5;
        }
        return $tmp;
    }
}

if (!function_exists('popKeys')) {
    function popKeys($txt, $encrypt_key)
    {
        $encrypt_key = md5($encrypt_key);
        $ctr = 0;
        $tmp = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
        }
        return $tmp;
    }
}

if (!function_exists('getProductTableName')) {
    //获取数据表正式表名，或真实表名对应简称(工作台)
    function getProductTableName($string)
    {
        //表名简称用于前台传值
        $popTableNameKeyValue = getCommonData('common_data', 'popTableNameKeyValue');
        if (array_key_exists(strtolower($string), $popTableNameKeyValue)) {
            return $popTableNameKeyValue[strtolower($string)];
        }
        if (false !== ($key = array_search(strtolower($string), $popTableNameKeyValue))) {
            return $key;
        }
        return false;
    }
}

if (!function_exists('getCommonData')) {
    /**
     * 获取已定义的公用的数组
     * @param        $filename 文件名或文件路径
     * @param        $varName 变量名
     * @param string $folderName 文件夹名
     *
     * @return array 返回以变量名为数组名的数组
     */
    function getCommonData($filename, $varName, $folderName = 'data')
    {
        if (empty($filename) || empty($varName)) {
            return array();
        }
        $path = '';
        if (($last_slash = strrpos($filename, '/')) !== FALSE) {
            $path = substr($filename, 0, ++$last_slash);
            $filename = substr($filename, $last_slash);
        }

        $common_name = pathinfo($filename, PATHINFO_BASENAME);
        $common_name_path = APPPATH . 'config/' . $folderName . '/' . $path . $common_name . '.php';

        if (file_exists($common_name_path)) {
            require($common_name_path);
        } else {
            show_error('Not found file: ' . $common_name_path);
        }

        if (!isset($$varName) OR !is_array($$varName)) {
            show_error('Undefined Variable ' . $$varName);
        }

        return $$varName;
    }
}

if (!function_exists('turntourl')) {

    function turntourl($urlAddress)
    {
        echo "<script language=\"javascript\">";
        echo "window.location.href= '" . $urlAddress . "';";
        echo "</script>";
        exit;
    }
}

if (!function_exists('alertmsg')) {

    function alertmsg($altMsg)
    {
        echo "<script language=javascript>";
        echo "alert('" . $altMsg . "')";
        echo "</script>";
    }
}

if (!function_exists('urlsafe_b64encode')) {
    function urlsafe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(['+', '/', '='], ['-', '_', ''], $data);

        return $data;
    }
}

if (!function_exists('urlsafe_b64decode')) {
    function urlsafe_b64decode($string)
    {
        $data = str_replace(['-', '_'], ['+', '/'], $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }

        return base64_decode($data);
    }
}
if (!function_exists('alertTip')) {
    /**
     *友情提示跳转
     * @param string $msg 提示信息
     * @param string $url 跳转地址
     * @return void;
     */
    function alertTip($msg = '', $url = '')
    {
        echo "<script>alert('" . $msg . "');location.href='" . $url . "'</script>";
        exit();
    }
}

if (!function_exists('getFixedImgPath')) {
    /**
     * 获取修正后的图片路径
     * @param string $path 图片路径
     * @return string  修正后的图片路径
     */
    function getFixedImgPath($path = '')
    {
        if (stripos($path, 'https://') !== FALSE) {
            return $path;
        } elseif (stripos($path, 'http://') !== FALSE) {
            $path = parse_url($path, PHP_URL_PATH);
            return getStaticUrl($path) . $path;
        } else {
            return '/' . ltrim($path, '/');
        }
    }
}

if (!function_exists('getSolrORString')) {
    function getSolrORString($ids = '1,2')
    {
        return strpos($ids, ',') ? '(' . str_replace(',', ' OR ', $ids) . ')' : $ids;
    }
}
if (!function_exists('getSolrAndString')) {
    function getSolrAndString($ids = '1,2')
    {
        return strpos($ids, ',') ? '(' . str_replace(',', ' AND ', $ids) . ')' : $ids;
    }
}

if (!function_exists('isFashionShows')) {
    function isFashionShows($table = 'fs_trend')
    {
        $fsFashionTables = ['fs_analysis', 'fs_commodity', 'fs_design', 'fs_inspiration', 'fs_trend'];
        return in_array($table, $fsFashionTables, TRUE);
    }
}

if (!function_exists('getStaticUrl')) {
    // 获取静态域名 path为文件相对路径
    function getStaticUrl($path = '')
    {
        $url = [STATIC_URL1, STATIC_URL2, STATIC_URL3];
        // 默认使用 STATIC_URL1
        if (empty($path)) {
            return $url[0];
        }
        $md5path = md5(strtolower($path));
        $num = (hexdec($md5path[0]) % 3 + 1) % 3;
        return $url[$num];
    }
}

if (!function_exists('getFileModifyTime')) {
    /* 获取文件修改时间
     + @param string filePath 为文件路径
     + @param string type 类型(决定缓存时间)
    */
    function getFileModifyTime($filePath, $type, $refresh = FALSE)
    {
        clearstatcache(); // 清除文件状态缓存
        //$last_modify_time = file_exists($filePath)?filemtime($filePath):time();
        //由于很多时候PHP读取文件时间，服务器未推送到其他地方，就算文件时间戳更换，CDN读取依然是老文件，等推送完全，时间戳也不再改变，造成文件无法刷新
        $last_modify_time = time()>strtotime(date('Y-m-d 09:00:00'))?date('Ymd'):date('Ymd',strtotime('-1 day'));
        return $last_modify_time ?: time();
    }
}
// 获取ULB 接口调用的链接
if (!function_exists('getUlbApiLink')) {
    function getUlbApiLink($path, $params = array('reportId' => 1675823))
    {
        $parameter = array();
        $parameter['app_id'] = config_item('fabric_app_id');

        list($t1, $t2) = explode(' ', microtime());
        $timestamp = (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
        $parameter['timestamp'] = $timestamp;
        $parameter['v'] = '1.0';

        $parameter = array_merge($parameter, $params);

        ksort($parameter, SORT_REGULAR);

        $appSuffix = 'GT%6kdy=7D8e-uj8m&FEgh7354-ditDSoef';

        $s = '';
        foreach ($parameter as $key => $value) {
            $s .= $key . '=' . $value . '&';
        }
        $s = trim($s, '&');
        $sign = md5($s . $appSuffix);
        $parameter['sign'] = $sign;

        $_BASEENV_ = getenv('BASEENV');
        if ($_BASEENV_ == 3) { // 开发环境
            $host = 'dev.api.uliaobao.com';
        } else if ($_BASEENV_ == 2) { // 测试环境
            $host = 'uat.api.uliaobao.com';
        } else { //正式环境
            $host = 'api.uliaobao.com';
        }

        return 'http://' . $host . '/extapi/' . $path . '?' . http_build_query($parameter);
    }
}

//去挂载后，获取图片大小
if (!function_exists('api_getPicSize')) {
    function api_getPicSize($file)
    {
        $file = urldecode($file);
        $file = str_replace(APPPATH, '/', $file);
        $body = array(
            'action' => 'getPicSize',
            'path' => $file,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.pop136.com/internal/getImgInfo.php');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 6);
        $body = http_build_query($body);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $http_resp = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($http_resp, true);
        if ($res['iStatus']) {
            return $res['info'];
        } else {
            return [];
        }
    }
}
//去挂载后，判断文件是否存在
if (!function_exists('api_file_exists')) {
    function api_file_exists($file)
    {
        $file = urldecode($file);
        $file = str_replace(APPPATH, '/', $file);
        $body = array(
            'action' => 'file_exists',
            'path' => $file,
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.pop136.com/internal/getImgInfo.php');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 6);
        $body = http_build_query($body);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $http_resp = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($http_resp, true);
        if ($res['iStatus']) {
            return $res['info'];
        } else {
            return false;
        }
    }
}

/**
 * 判断浏览器类型 是否 opera、谷歌、IE7、IE8、IE9、IE10、IE11、360、搜狗
 */
if (!function_exists('browserCheck')) {
    function browserCheck()
    {
        $return = false;
        $array = ['Edge', "rv:11", "MSIE", "opera", "Chrome", "360SE"];
        foreach ($array as $val) {
            if (strpos($_SERVER["HTTP_USER_AGENT"], $val)) {
                $return = true;
                break;
            }
        }
        return $return;
    }
}

/**
 * 下载重命名过滤规则
 */
if (!function_exists('getRenameStr')) {
    function getRenameStr($rename)
    {
        $rename = preg_replace('/[^\x{4e00}-\x{9fa5}a-z0-9]/iu', '', $rename);

        return $rename;
    }
}
/*
 * @todo 对二维数组，按某个字段排序
 * @$arrays   [arrray] 原数组
 * @$sort_key [string] 用来排序的字段名
 * @$sort_order [const] 正序还是倒序常量（例如：SORT_DESC、SORT_ASC）
 * $sort_type   [const] 排序的类型（例如SORT_REGULAR（常规排序）、SORT_NUMERIC（数字排序） 、SORT_STRING（字母排序））
 */
if (!function_exists('twoDimensionSort')) {
    function twoDimensionSort($arrays, $sort_key, $sort_order = SORT_DESC, $sort_type = SORT_REGULAR)
    {
        if (is_array($arrays) && !empty($arrays)) {
            foreach ($arrays as $array) {
                if (is_array($array)) {
                    $key_arrays[] = $array[$sort_key];
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        array_multisort($key_arrays, $sort_order, $sort_type, $arrays);
        return $arrays;
    }
}

/**
 * 判断是游客访问则跳至登录页
 * 2018-09-11
 */
if (!function_exists('checkDetailVisitor')) {
    function checkDetailVisitor()
    {
        $c = &get_instance();
        if ($c->input->is_ajax_request()) {
            // 后续可能对ajax请求做处理
            return;
        }
        if (getUserId()) {
            // 非游客不处理
            return;
        }
        $baseUrl = config_item('base_url');
        $referer = $_SERVER['HTTP_REFERER'];
        if (strpos($referer, $baseUrl) === 0) {
            // 这里要用 ===, 条件成立说明是本站跳转，否则为直接访问链接
            return;
        }
        // 游客跳转至登录页
        $c->load->helper(['url']);
        redirect('/member/pageLogin');
    }
}

if (!function_exists('getRequestPageSize')) {
    /**
     * 获取请求参数中的 pageSize 值
     * @param int $default
     * @param int $max
     * @return int
     *
     * 2018-09-13
     */
    function getRequestPageSize($default = 20, $max = 100)
    {
        $c = &get_instance();
        $pageSize = intval($c->input->get_post('pageSize'));

        if ($pageSize < 1) {
            return $default;
        } elseif ($pageSize > $max) {
            return $max;
        }

        return $pageSize;
    }
}

if (!function_exists('getRedisConnection')) {
    /**
     * 获取一个 redis 连接
     * @return Redis
     */
    function getRedisConnection()
    {
        $c = &get_instance();
        $c->config->load('redis');
        $redis_host = config_item('redis_host');
        $redis_port = config_item('redis_port');
        // $redis_pass = config_item('redis_password');
        $redis_dbid = config_item('redis_dbid');
        $redis = new Redis();
        $redis->connect($redis_host, $redis_port);
        // $redis->auth($redis_pass);
        $redis->select($redis_dbid);
        return $redis;
    }
}

if (!function_exists('getJsonInstance')) {
    /**
     * json输出类的一个实例
     * @return POP_OutputJson
     */
    function getJsonInstance()
    {
        $c = &get_instance();
        $c->load->library('POP_OutputJson');
        return new POP_OutputJson();
    }
}

if (!function_exists('getRegionByIp')) {
    /**
     * 调taobao接口获取ip地址信息
     * @param $ip
     * @param bool $raw
     * @return mixed|string
     */
    function getRegionByIp($ip, $raw = false)
    {
        // 115.156.238.114
        // {"code":0,"data":{"ip":"115.156.238.114","country":"中国","area":"","region":"湖北","city":"武汉","county":"XX","isp":"教育网","country_id":"CN","area_id":"","region_id":"420000","city_id":"420100","county_id":"xx","isp_id":"100027"}}

        // xxx 不正确的ip
        // {"code":0,"data":{"ip":"115.156.","country":"","area":"","region":"","city":"","county":"","isp":"","country_id":"","area_id":"","region_id":"","city_id":"","county_id":"","isp_id":""}}

        // 192.168.1.2
        // {"code":0,"data":{"ip":"192.168.0.123","country":"XX","area":"","region":"XX","city":"内网IP","county":"内网IP","isp":"内网IP","country_id":"xx","area_id":"","region_id":"xx","city_id":"local","county_id":"local","isp_id":"local"}}

        if (!$ip) {
            return '未知';
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        $http_resp = curl_exec($ch);
        curl_close($ch);
        $res = json_decode($http_resp, true);
        if ($raw) {
            return $res;
        }
        $country_id = $res['data']['country_id'];
        // 是否有效
        $is_valid = !in_array($country_id, ['xx', '']); // taobao 的接口内网地址会返回xx, 不合法IP会返回空字符串
        if ($is_valid) {
            if ($country_id != 'CN') {
                return '国外';
            }
            return $res['data']['region'] . ' ' . $res['data']['city'];
        } else {
            return '未知';
        }
    }
}

/*
 *过滤数组，只留下需要的建值
 * @param $array原数组
 * @param $keys留下的数据键
 */
if (!function_exists('array_only')) {
    function array_only(array $array, $keys)
    {
        return array_intersect_key($array, array_flip((array)$keys));
    }
}

