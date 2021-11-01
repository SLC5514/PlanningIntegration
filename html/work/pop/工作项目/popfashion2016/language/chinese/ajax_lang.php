<?php
/*
*@说明: 中文语言--ajax相关提示
*@Date: 2016-09-07
*@注意: 键不能有重复
*/
	$lang=[

		//=======收藏=======
		'ajax_collect_notSonAccount'=>'只有个人专属账号可以做收藏操作。',
		'ajax_collect_noLogin'=>'未登录',//账号未登录
		'ajax_collect_already'=>'已收藏',
		'ajax_collect_success'=>'收藏成功',
		'ajax_collect_fail'=>'收藏失败',
		'ajax_collect_noPower'=>'无权限',	

		//======创建工作台 与 个人中心创建编辑工作台 与 个人中心删除工作台======
		'ajax_Workbench_1'=>'已经存在',
		'ajax_Workbench_2'=>'工作台数量已满20个',
		'ajax_Workbench_3'=>'创建成功',
		'ajax_createWorkbench_4'=>'对不起，{$workbenchName}创建失败，请稍后尝试',//{$workbenchName}工作台名称

		'ajax_Workbench_5'=>'工作台名字不能为空',
		'ajax_Workbench_6'=>'该工作台已存在',
		'ajax_Workbench_7'=>'修改成功',
		'ajax_Workbench_8'=>'修改失败',
		'ajax_Workbench_9'=>'最多可添加20个工作台',
		'ajax_Workbench_10'=>'添加失败',

		//工作台删除失败 使用 ajax_delete_fail

		//收藏图片到工作台 与  图片是否可以收藏
		'ajax_collectImage_most'=>'每个工作台只能加入最多99张图片',
		'ajax_collectImage_already'=>'您已经收集过此图片无需再收集',
		'ajax_collectImage_already_2'=>'图片已经收集',
		'ajax_collectImage_allow'=>'可以收集',
		'ajax_collectImage_success'=>'加入成功',
		'ajax_collectImage_fail'=>'加入失败',

		//获取工作台列表
		'ajax_getWorkbenchList_noPower'=>'对不起，您没有操作权限',

		//公共
		'ajax_delete_success'=>'删除成功',
		'ajax_delete_fail'=>'删除失败',
	];
?>