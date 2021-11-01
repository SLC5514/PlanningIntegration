<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'home';
$route['404_override'] = 'error';
$route['translate_uri_dashes'] = FALSE;

$route['search/([a-z_?]+)'] = 'search/$1';
$route['search/([a-z_\d-]+)'] = 'search/index/$1';

$route['styles/([a-z]+)/?'] = 'styles/$1';
$route['styles/([a-z_\d-]+)/?'] = 'styles/index/$1';
$route['stylesub/([a-z]+)/?'] = 'stylesub/$1';
$route['stylesub/([a-z_\d-]+)/?'] = 'stylesub/index/$1';
$route['stylezone/([a-z]+)/?'] = 'stylezone/$1';
$route['stylezone/([a-z_\d-]+)/?'] = 'stylezone/index/$1';
$route['trends/([a-z_]+)'] = 'trends/$1';
$route['trends/([a-z_\d-]+)'] = 'trends/index/$1';
$route['analysis/([a-z_]+)'] = 'analysis/$1';
$route['analysis/([a-z_\d-]+)'] = 'analysis/index/$1';
$route['runways/([a-z_\d-]+)'] = 'runways/index/$1';
$route['books/([a-z_]+)'] = 'books/$1';
$route['books/([a-zA-Z%_\d-]+)'] = 'books/index/$1';
$route['brands/([a-z_]+)'] = 'brands/$1';
$route['brands/([a-zA-Z%_\d-]+)'] = 'brands/index/$1';

$route['mutual/([a-z_\d-]+)/?'] = 'mutual/index/$1';
$route['topic/([a-zA-Z\d_]+)'] = 'topic/index/$1';
$route['blog/([a-zA-Z\d_-]+)'] = 'blog/index/$1';

// 趋势聚合页
$route['trendtopic/([\d-]+)/?'] = 'aggregation/index/$1';

//top热榜
$route['smarttrends/topstyles/?'] = 'top/index/';//列表


