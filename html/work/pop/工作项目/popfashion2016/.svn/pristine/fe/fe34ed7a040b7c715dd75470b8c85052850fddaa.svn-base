<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE') OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE') OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE') OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ') OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE') OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE') OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE') OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE') OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT') OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT') OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS') OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR') OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG') OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE') OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS') OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT') OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE') OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN') OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX') OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

/**
 * 网站静态访问路径
 */
defined('STATIC_URL1') OR define('STATIC_URL1', "https://imgf1.pop-fashion.com"); // http://img1.fm.pop-fashion.com
defined('STATIC_URL2') OR define('STATIC_URL2', "https://imgf2.pop-fashion.com"); // http://img2.fm.pop-fashion.com
defined('STATIC_URL3') OR define('STATIC_URL3', "https://imgf3.pop-fashion.com"); // http://img3.fm.pop-fashion.com

//静态文件时间戳
defined('STATIC_CHANGE_TIME') OR define('STATIC_CHANGE_TIME', '2020070601');


//定义cookie加密的密钥
defined('COOKIE_ENCRYPT_KEYS') OR define('COOKIE_ENCRYPT_KEYS', 'pop-fashion_001!#^)');

defined('POP_GLOBAL_JWT_KEY') OR define('POP_GLOBAL_JWT_KEY', 'jkosd32d@d+_)!667&eee*!#?');

//POP全站API接口通用秘钥
defined('POP_GLOBAL_KEYS') OR define('POP_GLOBAL_KEYS', 'acd2605364d8017f0828a26c60cc7f64');

//POP接口Token通用秘钥(此秘钥对外，如客户登录端软件此种第三方)
defined('API_POP_CLIENT') OR define('API_POP_CLIENT', 'Y0h0s8xjb[J6kUuRJHLq%g1a]jNsMlOJ');

//POP全站定时key
defined('POP_GLOBAL_CRONTABS_KEYS') OR define('POP_GLOBAL_CRONTABS_KEYS', 'LR9UxOvtqviGWw0u');

//上传文件目录
defined('POP_UPLOAD_DIR') OR define('POP_UPLOAD_DIR', APPPATH . 'upload/');

//发布会下载包
defined('DOWNLOAD_PRESSCON_PATH') OR define('DOWNLOAD_PRESSCON_PATH', '/download/old/');
//手稿合辑下载包路径
defined('DOWNLOAD_DESIGN_REFRENCE_PATH') OR define('DOWNLOAD_DESIGN_REFRENCE_PATH', '/fashion/designreference/graphic/');
defined('DOWNLOAD_BROCHURES_PATH') OR define('DOWNLOAD_BROCHURES_PATH', '/fashion/brochures/graphic/');

//服装杂志
defined('IMAGE_MAGAZINE_PATH') OR define('IMAGE_MAGAZINE_PATH', '/fashion/magazine/graphic/');

// 显示调试执行时间 TRUE 开启 FALSE 关闭
defined('BENCHMARK') OR define('BENCHMARK', FALSE);

defined('DOWNZIP_URL3') OR define('DOWNZIP_URL3', '//downzip3.pop-fashion.com');

defined('PAGE_LIMIT') OR define('PAGE_LIMIT', 5000);
// 站点id
defined('SITE_ID') OR define('SITE_ID', 1);
// 优酷 client_id
defined('YOU_KU_CLIENT_ID') OR define('YOU_KU_CLIENT_ID', '6304acd0252780d2');


if (!function_exists('show_404')) {
    /**
     * 覆盖show_404()
     */
    function show_404()
    {
        set_status_header(404);
        $ci = &get_instance();
        $ci->smarty->assign('tdk', [
            'title' => '出错啦-POP服装趋势网',
            'description' => 'error,404',
            'keywords' => '您访问的页面出错！您可以稍后刷新或者返回网站首页']);
        $ci->smarty->display('errors/error.html');
        exit(4);
    }
}