<?php
/**
 * Created by PhpStorm.
 * User: limeng
 * Date: 2017/11/3 9:42
 */

if (!function_exists('outPrintApiJson')) {
    /**
     * 输出API/AJAX json结果 并结束（exit）
     *
     * @param $code 成功或错误码
     * @param $msg 提示信息
     * @param array $data 数据
     * @param array $info 其他
     */
    function outPrintApiJson($code, $msg, $data = array(), $info = array())
    {
        $array = array(
            "code" => $code,
            "msg" => $msg,
        );
        if (!empty($data)) {
            $array["data"] = $data;
        }
        if (!empty($info)) {
            $array["info"] = $info;
        }
        echo json_encode($array);
        exit;
    }
}
/*----------------------------------------------------------------------------------------------------------------------
 * @todo   获取浏览器端与服务器之间通讯的token
 * @param   $userId [int/string] 用户 id
 * @param   $time  [int] 时间time()函数的值
 * @param   $other [string] 附加值（当有区别与其他token时使用）
 * @return  $token [string]
 *--------------------------------------------------------------------------------------------------------------------*/
if (!function_exists('getToken')) {
    function getToken($userId, $time, $other = "")
    {
        $token = md5($other . COOKIE_ENCRYPT_KEYS . "_" . $userId . "_" . $time);
        return $token;
    }
}
/*----------------------------------------------------------------------------------------------------------------------
 * @todo   把数组编码成url参数形式
 * @param   $array [array]
 * @return         [string]
 *--------------------------------------------------------------------------------------------------------------------*/
if (!function_exists('encodeParams')) {
    function encodeParams($array)
    {
        if (is_array($array)) {
            $string = http_build_query($array);
            $string = str_replace(['=', '&'], ['_', '-'], $string);
        } else {
            $string = "";
        }
        return $string;
    }
}
/*----------------------------------------------------------------------------------------------------------------------
 * @todo   把url参数解码成数组
 * @param   $string [string]
 * @return          [array]
 *--------------------------------------------------------------------------------------------------------------------*/
if (!function_exists('decodeParams')) {
    function decodeParams($string)
    {
        $ci = &get_instance();
        $string = $ci->security->xss_clean($string);
        $string = trim($string, '-');
        $string = str_replace(['_', '-'], ['=', '&'], $string);
        parse_str($string, $array);
        return $array;
    }
}
/*----------------------------------------------------------------------------------------------------------------------
 * @todo
 * 获取关键字
 * @return          [string]
 *--------------------------------------------------------------------------------------------------------------------*/
if (!function_exists('getKeyWord')) {
    function getKeyWord()
    {
        $ci = &get_instance();
        $string = $ci->input->get_post("key");
        $string = $ci->security->xss_clean($string);
        $string = htmlspecialchars_decode(rawurldecode($string));
        return $string;
    }
}

//字符串替换,$flip = FALSE,默认不反转
if (!function_exists('strReplace')) {
    function strReplace($str, $flip = FALSE)
    {
        $arr1 = ['pop380', 'pop381', 'pop382', 'pop383', 'pop384', 'pop385', 'pop386', 'pop387', 'pop388', 'pop389', 'pop390', 'pop391', 'pop392', 'pop35'];
        $arr2 = ['-', '_', '~', '!', '.', '*', '(', ')', '&', '<', '>', "'", '+', '#'];
        return $flip ? str_replace($arr2, $arr1, $str) : str_replace($arr1, $arr2, $str);
    }
}
/*----------------------------------------------------------------------------------------------------------------------
 * @todo   通过表名获取假表名，或者假表名获取表名
 * @param    [string]
 *--------------------------------------------------------------------------------------------------------------------*/
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
/*----------------------------------------------------------------------------------------------------------------------
 * @todo   通过表名获取假表名，或者假表名获取表名
 * @param   $filename   [string]    文件名
 * @param   $varName    [string]    变量名
 * @param   $folderName [string]    文件夹名
 * @param   $params     [array]     配置中的变量(给配置中的变量赋值)
 *--------------------------------------------------------------------------------------------------------------------*/
if (!function_exists('getCommonData')) {
    function getCommonData($filename, $varName, $folderName = 'data', $params = [])
    {
        if (empty($filename) || empty($varName)) {
            return array();
        }
        foreach ($params as $key => $val) {
            $$key = $val;
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

//清除图片域名,$filter是否清除多余的斜线
if (!function_exists('clear_image_host')) {
    function clear_image_host($image_url, $filter = false)
    {
        $image_url = preg_replace('/^(http|https)(:\/\/([^\/]+))(\/(.*))/', '${4}', $image_url);
        if ($filter) {
            $image_url = preg_replace('#(/)+#', '/', $image_url);
        }
        return $image_url;
    }
}

/*
 * $image_path 必须数据库中存储的值，尚未处理变化域名
 */
if (!function_exists('get_image_md5_key')) {
    function get_image_md5_key($image_path)
    {
        if (!preg_match('#(^http)#', $image_path)) {
            $image_path = 'https://imgf1.pop-fashion.com' . $image_path;//无域名的图片
        } elseif (preg_match('/(^http\:\/\/img1\.fm.pop-fashion\.com)/', $image_path)) {
            $image_path = 'https://imgf1.pop-fashion.com' . clear_image_host($image_path);//替换过域名的flash图，此处替换回来
        }
        return md5($image_path);
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

//当天浏览量自增
if (!function_exists('set_today_incr_views')) {
    function set_today_incr_views($table, $id, $incr_num = 1)
    {
        $key = OpPopFashionMerger::POP_FM_TEM_VIEWS_INCR_MEMCACHE_KEY . $table . '_' . $id;
        $count = OpMemcache::getInstance()->get($key);
        if ($count === false) {
            OpMemcache::getInstance()->set($key, $incr_num, 0, 86400);
        } elseif (is_array($count)) {//兼容CI
            OpMemcache::getInstance()->set($key, intval($count[0]) + $incr_num, 0, 86400);
        } else {
            OpMemcache::getInstance()->increment($key, $incr_num);
        }
    }
}

/*----------------------------------------------------------------------------------------------------------------------
 * @todo 通过数据获取其图片路径
 * @param $tablename  [string]  表名
 * @param $_res       [array]   一行数据
 *--------------------------------------------------------------------------------------------------------------------*/
function getImagePath($tablename = '', $_res = [])
{
    $bigpath = $smallpath = $cover = '';
    $path = $aDetail = [];
    switch ($tablename) {
        case 'product_graphicitem':         //图案花型
        case 'product_graphic_craft_item':  //图案工艺
            //新数据  主图加细节图
            $aDetailImg = json_decode($_res['sPathDetails'], true);
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
        // 报告栏目
        case 't_trend_report':
            $smallpath = $cover = getFixedImgPath($_res['sImgPath']);
            break;
    }
    //为图片添加图片域名
    $path['bigPath'] = getStaticUrl($bigpath) . $bigpath;
    $path['smallPath'] = getStaticUrl($smallpath) . $smallpath;
    $path['cover'] = getStaticUrl($cover) . $cover;
    //除去多余的斜线（/）
    $path['bigPath'] = str_replace('https:/', 'https://', preg_replace('/[\/]{2,}/', '/', $path['bigPath']));
    $path['smallPath'] = str_replace('https:/', 'https://', preg_replace('/[\/]{2,}/', '/', $path['smallPath']));
    $path['cover'] = str_replace('https:/', 'https://', preg_replace('/[\/]{2,}/', '/', $path['cover']));
    return $path;
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

if (!function_exists('array_cloumn')) {
    function array_cloumn($arr, $field)
    {
        $return = array();
        foreach ($arr as $val) {
            $return[] = $val[$field];
        }
        return $return;
    }
}

/*----------------------------------------------------------------------------------------------------------------------
 * @todo 获取静态域名(仅返回图片域名 如：http://img1.yuntu.pop136.com )
 * @param  $path  [sring] 图片的路径（同一张图片通过此方法返回的图片域名不变）
 *--------------------------------------------------------------------------------------------------------------------*/
if (!function_exists('getStaticUrl')) {
    function getStaticUrl($path = '')
    {
        $url = [STATIC_URL1, STATIC_URL2, STATIC_URL3];
        if (empty($path)) {
            return $url[0];
        } else {
            $md5path = md5(strtolower($path));
            $num = (hexdec($md5path[0]) % 3 + 1) % 3;
            return $url[$num];
        }
    }
}
/*----------------------------------------------------------------------------------------------------------------------
 * @todo 对二维数组，按某个字段排序
 * @$arrays   [arrray] 原数组
 * @$sort_key [string] 用来排序的字段名
 * @$sort_order [const] 正序还是倒序常量（例如：SORT_DESC、SORT_ASC）
 * $sort_type   [const] 排序的类型（例如SORT_REGULAR（常规排序）、SORT_NUMERIC（数字排序） 、SORT_STRING（字母排序））
 *--------------------------------------------------------------------------------------------------------------------*/
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
/*----------------------------------------------------------------------------------------------------------------------
 * @todo 去挂载后，获取图片大小
 *--------------------------------------------------------------------------------------------------------------------*/
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
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_POST, true);
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
/*----------------------------------------------------------------------------------------------------------------------
 * @todo 去挂载后，判断文件是否存在
 *--------------------------------------------------------------------------------------------------------------------*/
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
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_POST, true);
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

if (!function_exists('get_cookie_value')) {
    function get_cookie_value()
    {
        if (isset($_COOKIE['POP_U']) && !empty($_COOKIE['POP_U'])) {
            $str_cookie = decrypt($_COOKIE['POP_U']);
            $arr_cookie = unserialize($str_cookie);
        } else {
            $arr_cookie = array();
        }
        return $arr_cookie;
    }
}

/*----------------------------------------------------------------------------------------------------------------------
 * @todo   获取用户ID
 * @$main_user 只取主账号
 *--------------------------------------------------------------------------------------------------------------------*/
if (!function_exists('getUserId')) {
    function getUserId($main_user = true)
    {
        $userInfo = get_cookie_value();
        if ($main_user) {
            $UserId = $userInfo['id'];
        } else {
            $UserId = $userInfo['iAccountType'] == 2 ? $userInfo['sChildID'] : $userInfo['id'];
        }
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
/**
 * @todo 退出登录 清除用户信息
 */
if (!function_exists('logout')) {
    function logout()
    {
        $ci = &get_instance();
        $ci->load->driver('cache');
        $userId = getUserId();
        $pop_uid = get_cookie('POP_UID');
        $ci->cache->memcached->delete(UID_MEMKEY_PRE . $pop_uid); //删除用户唯一串
        $user_info = get_cookie_value();
        $memcacheKey = T_FM_FASHION_PRIVILGE_CLOUD_ . md5($user_info['id']);
        $ci->cache->memcached->delete($memcacheKey);//删除权限信息
        if ($user_info) {
            delete_cookie('POP_U');//删除用户信息
        }
        delete_cookie('POP_UID');
        delete_cookie('cookiemessage_findpass');
        delete_cookie('userId');
        return true;
    }
}
//获取redis链接对象（PS：CI的cache很多方法缺失）
if (!function_exists('get_redis_connection')) {
    function get_redis_connection($db = 15)
    {
        $ci = &get_instance();
        $ci->load->config("redis");
        $redis_host = config_item("host");
        $redis_port = config_item("port");
        $redis_timeout = config_item("timeout");
        $redis_password = config_item("password");
        $redis = new Redis();
        $redis->connect($redis_host, $redis_port, $redis_timeout);
        if (!empty($redis_password)) {
            $redis->auth($redis_password);
        }
        $redis->select($db);
        return $redis;
    }
}

