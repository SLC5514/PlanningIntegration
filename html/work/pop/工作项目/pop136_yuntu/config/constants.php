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


defined('STATIC_URL1') OR define('STATIC_URL1', 'https://imgyt1.pop-fashion.com');//网站静态访问路径
defined('STATIC_URL2') OR define('STATIC_URL2', 'https://imgyt2.pop-fashion.com');
defined('STATIC_URL3') OR define('STATIC_URL3', 'https://imgyt3.pop-fashion.com');


defined('STATIC_CHANGE_TIME') OR define('STATIC_CHANGE_TIME', '20200722');//静态文件时间戳

defined('COOKIE_ENCRYPT_KEYS') OR define('COOKIE_ENCRYPT_KEYS', 'pop-fashion_001!#^)');//定义cookie加密的密钥

defined('POP_GLOBAL_KEYS') OR define('POP_GLOBAL_KEYS', 'acd2605364d8017f0828a26c60cc7f64');//POP全站API接口通用秘钥

defined('POP_GLOBAL_CRONTABS_KEYS') OR define('POP_GLOBAL_CRONTABS_KEYS', 'LR9UxOvtqviGWw0u');//POP全站定时key

defined('POP_UPLOAD_DIR') OR define('POP_UPLOAD_DIR', APPPATH . 'upload/');//上传文件目录

defined('UID_MEMKEY_PRE') OR define('UID_MEMKEY_PRE', 'POP_COLUD_UID_MEMKEY_PRE_');//轮询唯一串memecahe前缀
defined('MIAN_UID_MEMKEY_PRE') OR define('MIAN_UID_MEMKEY_PRE', 'POP_COLUD_MIAN_UID_MEMKEY_PRE_'); //存(主用户=>[唯一串,唯一串])的前缀
defined('UID_MEMKEY_TIME') OR define('UID_MEMKEY_TIME', 90);//UID存在时间，如果90s未被重新设置则掉线
defined('USER_TIME_OUT') OR define('USER_TIME_OUT', 3600);//用户不操作超时时间

defined('BENCHMARK') OR define('BENCHMARK', FALSE);// 显示调试执行时间 TRUE 开启  FALSE 关闭
defined('T_FM_FASHION_PRIVILGE_CLOUD_') OR define('T_FM_FASHION_PRIVILGE_CLOUD_', 'T_FM_FASHION_PRIVILGE_CLOUD_');//用户vip权限前缀

//米绘-智能设计图片保存路径
defined('MIHUI_DESIGN_IMAGE_PATH') OR define('MIHUI_DESIGN_IMAGE_PATH', '/data/');

// 云图app--分类--图案内容的icon的图片的路径前缀
defined('APP_PATTERN_IMAGE_ICON') OR define('APP_PATTERN_IMAGE_ICON', 'http://yuntu.pop136.com/global/images/app/pattern/');

//极验-POP注册
defined('GEETEST_REGISTER_ID') OR define('GEETEST_REGISTER_ID', '6ed2fbdfc1dbc0dc09afc0d64c92fb83');
defined('GEETEST_REGISTER_KEY') OR define('GEETEST_REGISTER_KEY', '5297eb0764b6c0ebce54ff6781e875ce');
