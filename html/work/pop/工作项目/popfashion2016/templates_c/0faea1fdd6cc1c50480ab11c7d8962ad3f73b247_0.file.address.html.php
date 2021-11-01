<?php /* Smarty version 3.1.27, created on 2020-04-26 16:09:16
         compiled from "/data/htdocs/popfashion2016/views/service/address.html" */ ?>
<?php
/*%%SmartyHeaderCode:7351517105ea541acb22401_27812262%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0faea1fdd6cc1c50480ab11c7d8962ad3f73b247' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/service/address.html',
      1 => 1587872783,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7351517105ea541acb22401_27812262',
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5ea541accf3631_23196134',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5ea541accf3631_23196134')) {
function content_5ea541accf3631_23196134 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '7351517105ea541acb22401_27812262';
?>
<!DOCTYPE html>
<html>
<head>
	<title>逸尚创展（上海）科技有限公司</title>
	<meta charset="utf-8">
	<meta name="author" content="pop"/>
    <meta name="keywords" content="逸尚创展,百度地图,位置"/>
    <meta name="description" content="逸尚创展（上海）科技有限公司百度地图位置"/>
    <meta name="format-detection" content="telephone=no"/>
    <meta name="viewport" content="initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"/>
    
    <style type="text/css">
    	*{margin: 0;padding: 0}
    	html,body{height: 100%;}
    	#map{height: 100%;}
    </style>
    
</head>
<body>
	<div id="map"></div>
	<?php echo '<script'; ?>
 type="text/javascript" src="//api.map.baidu.com/api?v=2.0&ak=BQtrC7t3SWOnelZPBWPMSSZi"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript">
		// 百度地图API功能
		var map = new BMap.Map("map");  // 创建Map实例
		map.centerAndZoom("上海市虹桥商务区双联路158号东隆大厦3楼",15);      // 初始化地图,用城市名设置地图中心点
		map.enableScrollWheelZoom(true);

		// 创建地址解析器实例
		var myGeo = new BMap.Geocoder();
		// 将地址解析结果显示在地图上,并调整地图视野
		myGeo.getPoint("上海市虹桥商务区双联路158号东隆大厦3楼", function(point){
			if (point) {
				
				var marker = new BMap.Marker(point);  // 创建标注
				map.addOverlay(marker);               // 将标注添加到地图中
				map.centerAndZoom(point, 16);
				// marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画

				var opts = {
				  	width : 370,     // 信息窗口宽度
				  	height: 85,     // 信息窗口高度
				  	title : "逸尚创展（上海）科技有限公司" , // 信息窗口标题
				  	enableMessage:true,//设置允许信息窗发送短息
				  	message:"~"
				};
				var infoWindow = new BMap.InfoWindow("地址：上海市虹桥商务区双联路158号东隆大厦3楼<br/><a href='http://www.pop136.com/' target='_blank' title='http://www.pop136.com/'>进入官网</a>", opts);  // 创建信息窗口对象 
				marker.addEventListener("click", function(){          
					map.openInfoWindow(infoWindow,point); //开启信息窗口
				});
			}else{
				alert("您选择地址没有解析到结果!");
			}
		}, "上海市");

		
	<?php echo '</script'; ?>
>
</body>
</html><?php }
}
?>