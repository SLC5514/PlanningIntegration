<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//获取数据表正式表名，或真实表名对应简称(工作台)
$popTableNameKeyValue = [
	'graphicitem'			=> 'product_graphicitem',
	'fabricgalleryitem'		=> 'product_fabricgallery_item',
    'uploadpicmaterial'		=> 'fm_upload_pic_material',
    'mihuidesignpic'=> 't_mihui_design_graphic',//智能设计花型表
    'report' => 't_trend_report',
    'graphictechnics'		=> 'product_graphic_craft_item',
    'quickprint'		    => 't_digital_quick_print',

    'perform'				=> 'product_perform',
    'brand'					=> 'product_brand',
    'marketphotoitem'		=> 'product_marketphoto_item',
    'stylegraphic'			=> 'product_style_graphic',
    'stylegraphicdetails'	=> 'product_style_graphic_details',
    'stylegraphicchina'		=> 'product_style_graphic_china',
    'brochures'				=> 'product_brochures',
    'brochuresphoto'		=> 'product_brochures_photo',
    'styledetail'			=> 'product_style_detail',
    'streetitem'			=> 'product_streetitem',
    'ordermeeting'			=> 'product_ordermeeting',
    'templateitem'			=> 'product_templateitem',
    'designrefrence'		=> 'product_design_refrence',
    'refrencegroup'			=> 'product_vector_refrence_group',
    'branddb'				=> 'brand_library',
    'presscon'    			=> 'product_presscon',
    'presscondetails' 		=> 'product_presscon_details',
    'hat'    				=> 'product_hat',
    'wrapsitem'    			=> 'product_wrapsitem',
    'docerationitem'    	=> 'product_docerationitem', // 首饰，暂时没有用到
    'glove'    				=> 'product_glove',
    'shopset'    			=> 'product_shopset',
    'inspirationdb'    		=> 't_inspiration_img_db',
    'analysis'				=> 'fs_analysis',
    'commodity'				=> 'fs_commodity',
    'design'				=> 'fs_design',
    'inspiration'			=> 'fs_inspiration',
    'trend'					=> 'fs_trend', // 趋势5个表
    'designrefrencedetails'	=> 'product_design_refrence_details',
    'moscon'				=> 'mostrend_content', // mostrend 报告
    'spt'					=> 'specialtopic',     //关键主题
    'sptg'					=> 'specialtopic_graphic', //图案主题
    'pic'					=> 'picture',
    'tideleader'			=> 'product_tideleader', // 潮流领袖
    'vectorrefrence'		=> 'product_vector_refrence', // 矢量书稿
    'refrencelist'			=> 'product_vector_refrence_list', // 适量书稿列表，集合两张表到getProductData方法里面取数据
    'fabricgalleryitemdetail'	=> 'product_fabricgallery_item_detail',
    'video'		            => 'product_video',// 视频专栏
    'styles'                => 'styles',//八爪鱼款式数据
];

