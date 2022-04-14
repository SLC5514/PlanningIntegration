<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| SMARTY
| -------------------------------------------------------------------------
*/
$config['smarty_template_dir'] = APPPATH . 'views';
$config['smarty_compile_dir'] = APPPATH . 'templates_c';
$config['smarty_plugins_dir'] = APPPATH . 'libraries/Smarty/plugins';    // 处理调节器路径问题
$config['smarty_left_delimiter'] = '{';
$config['smarty_right_delimiter'] = '}';
$config['smarty_caching'] = FALSE;
$config['smarty_cache_dir'] = APPPATH . 'cache/smarty';
$config['smarty_cache_lifetime'] = 120;