<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'user/login';
//$route['404_override'] = 'error';
$route['translate_uri_dashes'] = FALSE;

$route['similarpatterns/([a-z_?]+)'] = 'similarpatterns/$1';
$route['similarpatterns/([a-z_\d]+)'] = 'similarpatterns/index/$1';

$route['virtualtryon/([a-z]+)/?'] = 'virtualtryon/$1';
$route['virtualtryon/([a-z_\d-]+)/?'] = 'virtualtryon/index/$1';

$route['patternlibrary/([a-z]+)/?'] = 'patternlibrary/$1';
$route['patternlibrary/([a-z_\d-]+)/?'] = 'patternlibrary/index/$1';

// 【服装站】-【趋势解读】-【图案趋势】
$route['trendspattern/([a-z]+)/?'] = 'trendspattern/$1';
$route['trendspattern/([a-z_\d-]+)/?'] = 'trendspattern/index/$1';

// 接口api
$route['api/([a-z]+)/?'] = 'api/$1';
$route['api/([a-z_\d-]+)/?'] = 'api/index/$1';
$route['topic/([a-z_\d-]+)/?'] = 'topic/index/$1';
$route['collect/([a-z]+)/?'] = 'collect/$1';
$route['collect/([a-z_\d-]+)/?'] = 'collect/index/$1';

// 云图APP接口路由配置
$route['api/login/([a-zA-Z]+)/?'] = 'app/login/$1';
$route['api/account/([a-zA-Z]+)/?'] = 'app/account/$1';
$route['api/apptoken/([a-zA-Z]+)/?'] = 'app/apptoken/$1';
$route['api/favorite/([a-zA-Z]+)/?'] = 'app/favorite/$1';
$route['api/graphicitem/([a-zA-Z]+)/?'] = 'app/graphicitem/$1';
$route['api/searchpicture/([a-zA-Z]+)/?'] = 'app/searchpicture/$1';
$route['api/recommend/([a-zA-Z]+)/?'] = 'app/recommend/$1';// 达观推荐




