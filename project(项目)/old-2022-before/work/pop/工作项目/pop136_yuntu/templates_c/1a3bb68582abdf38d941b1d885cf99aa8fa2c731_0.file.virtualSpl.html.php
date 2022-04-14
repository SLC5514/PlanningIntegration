<?php /* Smarty version 3.1.27, created on 2020-08-11 15:19:33
         compiled from "/data/htdocs/pop136_yuntu/views/virtual/virtualSpl.html" */ ?>
<?php
/*%%SmartyHeaderCode:20055561095f324685c31340_61025541%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '1a3bb68582abdf38d941b1d885cf99aa8fa2c731' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/virtual/virtualSpl.html',
      1 => 1597129662,
      2 => 'file',
    ),
    '4b22eedd05bdc48aa69395e981b7badb50229f97' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/layout.html',
      1 => 1587877444,
      2 => 'file',
    ),
    '92bb942cfa1d057656da6d81f43817f4b8e1be0d' => 
    array (
      0 => '92bb942cfa1d057656da6d81f43817f4b8e1be0d',
      1 => 0,
      2 => 'string',
    ),
    'c270d5b9b9b9fca55e14fe96d756c566613109c6' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/header.html',
      1 => 1595397799,
      2 => 'file',
    ),
    '86373358af7881ac519bd547330d766b514a6f0b' => 
    array (
      0 => '86373358af7881ac519bd547330d766b514a6f0b',
      1 => 0,
      2 => 'string',
    ),
    'a91b5d00af2d271a5ee5bd89fa15b4701643d6db' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/footer.html',
      1 => 1587877444,
      2 => 'file',
    ),
    'cecb2cc79ed043e3678155729c137676da8cd8b8' => 
    array (
      0 => 'cecb2cc79ed043e3678155729c137676da8cd8b8',
      1 => 0,
      2 => 'string',
    ),
    '9f1a53f43a3033aee97d0385f1643a475bd5988b' => 
    array (
      0 => '9f1a53f43a3033aee97d0385f1643a475bd5988b',
      1 => 0,
      2 => 'string',
    ),
    'a1b84aff60be014b1949bc7ba2a2951727e17723' => 
    array (
      0 => 'a1b84aff60be014b1949bc7ba2a2951727e17723',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '20055561095f324685c31340_61025541',
  'variables' => 
  array (
    'TDK_title' => 0,
    'TDK_keywords' => 0,
    'TDK_description' => 0,
    'token' => 0,
    'loopTime' => 0,
    'loopToken' => 0,
    'user_type' => 0,
    'user_id' => 0,
    'account_type' => 0,
    'account_id' => 0,
    'google_select_show' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5f3246869f9b57_78646147',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5f3246869f9b57_78646147')) {
function content_5f3246869f9b57_78646147 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '20055561095f324685c31340_61025541';
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="applicable-device" content="pc">
        <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">

        <title><?php echo $_smarty_tpl->tpl_vars['TDK_title']->value;?>
</title>

        <meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['TDK_keywords']->value;?>
"/>
        <meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['TDK_description']->value;?>
"/>

        <meta http-equiv="pragma" name="" content="no-cache">
        <meta http-equiv="cache-control" name="" content="no-cache">
        <link rel="shortcut icon" href="<?php echo @constant('STATIC_URL2');?>
/global/images/common/favicon.ico?<?php echo @constant('STATIC_CHANGE_TIME');?>
" type="image/x-icon">
        
        <link rel="stylesheet" type="text/css" href="<?php echo @constant('STATIC_URL1');?>
/global/css/common/base.css?<?php echo @constant('STATIC_CHANGE_TIME');?>
">
        <?php
$_smarty_tpl->properties['nocache_hash'] = '20055561095f324685c31340_61025541';
?>

	<link rel="stylesheet" type="text/css" href="<?php echo @constant('STATIC_URL1');?>
/global/css/lib/perfect-scrollbar-og.css?<?php echo @constant('STATIC_CHANGE_TIME');?>
">
	<link rel="stylesheet" type="text/css" href="<?php echo @constant('STATIC_URL1');?>
/global/css/virtual/virtual.css?<?php echo @constant('STATIC_CHANGE_TIME');?>
">


    </head>
<body>
    <?php echo $_smarty_tpl->tpl_vars['token']->value;?>

    <div class="view">
        
        <?php /*  Call merged included template "header.html" */
echo $_smarty_tpl->getInlineSubTemplate("header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '9282287365f32468604c263_85997100', 'content_5f324686044743_85575337');
/*  End of included template "header.html" */?>

        

        <?php
$_smarty_tpl->properties['nocache_hash'] = '20055561095f324685c31340_61025541';
?>

<div class="content js-scroll-Sb">
	<!-- 实景模拟主体盒子 -->
	<div class="virtual-box">
		<!-- 侧栏导航 -->
		<?php if ($_smarty_tpl->tpl_vars['isPreview']->value != 1) {?>
		<div class="aside-nav-box">
			<!-- 侧栏导航列表 -->
			<ul class="aside-nav-list js-aside-nav-list js-scroll-Sb">
				<!-- <li class="my-pattern"><i></i>我的花型</li> -->
				<li class="clothing async" data-id="1"><i></i>服装</li>
				<li class="home-textile async" data-id="5"><i></i>家纺</li>
				<li class="bags async" data-id="2"><i></i>箱包</li>
				<li class="shoes async" data-id="3"><i></i>鞋子</li>
				<li class="other async" data-id="100"><i></i>其他</li>
				<li class="custom-made"><i></i>定制</li>
				<li class="pattern"><i></i>图案花型</li>
				<li class="master-room on"><i></i>制版间</li>
			</ul>
			<!-- 侧栏导航内容 -->
			<ul class="aside-nav-content-box js-aside-nav-content-box">
				<li class="aside-nav-content select-tpl async" data-id="1">
					<!-- 侧栏导航内容控制器 -->
					<div class="aside-nav-content-control">
						<!-- <button class="btn on" data-id="">女装</button> -->
						<!-- <button class="btn unfold js-classify-more">展开更多</button> -->
						<!-- <button class="btn collapse js-classify-more">收起</button> -->
					</div>
					<!-- 侧栏导航内容列表 -->
					<ul class="aside-nav-content-list simubtn-list js-aside-nav-Sb">
						<!-- <li data-id=""><img src="" alt=""><button class="simu-btn">模拟成品</button></li> -->
					</ul>
					<!-- 无内容 -->
					<div class="empty-list">
						<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/virtual/nav_empty5.png?<?php echo @constant('STATIC_CHANGE_TIME');?>
" alt="">
						<span>加载失败</span>
					</div>
				</li>
				<li class="aside-nav-content select-tpl async" data-id="5">
					<!-- 侧栏导航内容控制器 -->
					<div class="aside-nav-content-control">
						<!-- <button class="btn on" data-id="">女装</button> -->
						<!-- <button class="btn unfold js-classify-more">展开更多</button> -->
						<!-- <button class="btn collapse js-classify-more">收起</button> -->
					</div>
					<!-- 侧栏导航内容列表 -->
					<ul class="aside-nav-content-list simubtn-list js-aside-nav-Sb">
						<!-- <li data-id=""><img src="" alt=""><button class="simu-btn">模拟成品</button></li> -->
					</ul>
					<!-- 无内容 -->
					<div class="empty-list">
						<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/virtual/nav_empty5.png?<?php echo @constant('STATIC_CHANGE_TIME');?>
" alt="">
						<span>加载失败</span>
					</div>
				</li>
				<li class="aside-nav-content select-tpl async" data-id="2">
					<!-- 侧栏导航内容控制器 -->
					<div class="aside-nav-content-control">
						<!-- <button class="btn on" data-id="">女装</button> -->
						<!-- <button class="btn unfold js-classify-more">展开更多</button> -->
						<!-- <button class="btn collapse js-classify-more">收起</button> -->
					</div>
					<!-- 侧栏导航内容列表 -->
					<ul class="aside-nav-content-list simubtn-list js-aside-nav-Sb">
						<!-- <li data-id=""><img src="" alt=""><button class="simu-btn">模拟成品</button></li> -->
					</ul>
					<!-- 无内容 -->
					<div class="empty-list">
						<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/virtual/nav_empty5.png?<?php echo @constant('STATIC_CHANGE_TIME');?>
" alt="">
						<span>加载失败</span>
					</div>
				</li>
				<li class="aside-nav-content select-tpl async" data-id="3">
					<!-- 侧栏导航内容控制器 -->
					<div class="aside-nav-content-control">
						<!-- <button class="btn on" data-id="">女装</button> -->
						<!-- <button class="btn unfold js-classify-more">展开更多</button> -->
						<!-- <button class="btn collapse js-classify-more">收起</button> -->
					</div>
					<!-- 侧栏导航内容列表 -->
					<ul class="aside-nav-content-list simubtn-list js-aside-nav-Sb">
						<!-- <li data-id=""><img src="" alt=""><button class="simu-btn">模拟成品</button></li> -->
					</ul>
					<!-- 无内容 -->
					<div class="empty-list">
						<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/virtual/nav_empty5.png?<?php echo @constant('STATIC_CHANGE_TIME');?>
" alt="">
						<span>加载失败</span>
					</div>
				</li>
				<li class="aside-nav-content select-tpl async" data-id="100">
					<!-- 侧栏导航内容控制器 -->
					<div class="aside-nav-content-control">
						<!-- <button class="btn on" data-id="">女装</button> -->
						<!-- <button class="btn unfold js-classify-more">展开更多</button> -->
						<!-- <button class="btn collapse js-classify-more">收起</button> -->
					</div>
					<!-- 侧栏导航内容列表 -->
					<ul class="aside-nav-content-list simubtn-list js-aside-nav-Sb">
						<!-- <li data-id=""><img src="" alt=""><button class="simu-btn">模拟成品</button></li> -->
					</ul>
					<!-- 无内容 -->
					<div class="empty-list">
						<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/virtual/nav_empty5.png?<?php echo @constant('STATIC_CHANGE_TIME');?>
" alt="">
						<span>加载失败</span>
					</div>
				</li>
				<li class="aside-nav-content select-tpl custom-made">
					<!-- 侧栏导航内容控制器 -->
					<div class="aside-nav-content-control padding5"></div>
					<!-- 侧栏导航内容列表 -->
					<ul class="aside-nav-content-list simubtn-list js-aside-nav-Sb">
						<div class="nav-loading on"><i></i><span>加载中</span></div>
						<!-- <li data-id=""><img src="" alt=""><button class="simu-btn">模拟成品</button></li> -->
					</ul>
					<!-- 无内容 -->
					<div class="empty-list">
						<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/virtual/nav_empty3.png?<?php echo @constant('STATIC_CHANGE_TIME');?>
" alt="">
						<span>你还没有定制过模板</span>
						<a href="/topic/20180920/" target="_blank">立即定制模版</a>
					</div>
				</li>
				<li class="aside-nav-content pattern-box">
					<ul class="tab js-pattern-tab">
						<li class="on">推荐花型</li>
						<li>我的花型</li>
					</ul>
					<ul class="tab-content js-pattern-tab-content">
						<li class="aside-nav-content pattern on">
							<!-- 侧栏导航内容控制器 -->
							<div class="aside-nav-content-control padding010">
								<span>当前选中的花型</span>
								<div class="pattern-view js-pattern-view" data-id="" data-t="" data-src="">
									<img src="" alt="">
								</div>
								<span>猜你喜欢</span>
							</div>
							<!-- 侧栏导航内容列表 -->
							<ul class="aside-nav-content-list js-aside-nav-Sb">
								<div class="nav-loading on"><i></i><span>加载中</span></div>
								<!-- <li data-id=""><img src="" alt=""></li> -->
							</ul>
							<!-- 无内容 -->
							<div class="empty-list">
								<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/virtual/nav_empty4.png?<?php echo @constant('STATIC_CHANGE_TIME');?>
" alt="">
								<button class="js-pattern-reload">请重新加载</button>
							</div>
						</li>
						<li class="aside-nav-content my-pattern">
							<!-- 侧栏导航内容控制器 -->
							<div class="aside-nav-content-control">
								<div class="batch js-batch"><!-- on -->
									<div class="col">批量管理<i></i></div>
									<div class="clear"><span class="fl cancel">取消</span><button class="fr confirm">确定删除</button></div>
								</div>
							</div>
							<!-- 侧栏导航内容列表 --><!-- checkbtn-list-on -->
							<ul class="aside-nav-content-list checkbtn-list js-aside-nav-Sb js-aside-nav-Sb-sss">
								<div class="nav-loading on"><i></i><span>加载中</span></div>
								<!-- <li data-id=""><img src="" alt=""><button class="check-btn"></button></li> -->
							</ul>
							<!-- 无内容 -->
							<div class="empty-list">
								<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/virtual/nav_empty2.png?<?php echo @constant('STATIC_CHANGE_TIME');?>
" alt="">
								<span>你还没上传过花型</span>
							</div>
						</li>
					</ul>
				</li>
				<li class="aside-nav-content select-tpl master-room on">
					<!-- 侧栏导航内容控制器 -->
					<div class="aside-nav-content-control">
						<div class="select">
							<span data-id="0">全部</span>
							<ul class="hide">
								<!-- <li class="on" data-id="">全部</li> -->
							</ul>
						</div>
					</div>
					<!-- 侧栏导航内容列表 -->
					<ul class="aside-nav-content-list movebtn-list nomove js-aside-nav-Sb">
						<div class="nav-loading on"><i></i><span>加载中</span></div>
						<!-- <li data-id=""><img src="" alt=""><button class="move-btn">移动</button></li> -->
					</ul>
					<!-- 无内容 -->
					<div class="empty-list">
						<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/virtual/nav_empty1.png?<?php echo @constant('STATIC_CHANGE_TIME');?>
" alt="">
						<span>你还没有保存过效果图</span>
					</div>
					<!-- 底部按钮 -->
					<a class="js-aside-nav-Sb-next" href="/user/usercenterview/">查看我的制版间</a>
				</li>
				<li class="aside-nav-content">
					<!-- 侧栏导航内容控制器 -->
					<div class="aside-nav-content-control">
						<label class="upload js-upload" for="uploadIpt"><i></i>上传本地花型
							<form class="js-upload-form">
								<input type="file" class="js-upload-ipt" id="uploadIpt" name="material" accept="image/gif,image/jpeg,image/jpg,image/png">
							</form>
						</label>
					</div>
				</li>
			</ul>
			<a class="function-tips-btn js-function-tips-btn" href="javascript:void(0);"><i></i>功能引导说明</a>
			<a class="aside-nav-tpl-btn" href="/topic/20180920/" target="_blank">定制专属模板</a>
		</div>
		<!-- 侧栏控制器 -->
		<div class="aside-control-box js-aside-control-box">
			<button class="aside-control-collapse"><i></i></button>
			<div class="aside-control-content js-aside-control-Sb">
				<div class="aside-control-title"><span>选择部件</span></div>
				<ul class="com-list js-com-list js-scroll-Sb">
					<!-- <li class="on" data-idx="0">部件名称</li> -->
				</ul>
				<div class="aside-control-title"><span>图案</span></div>
				<ul class="loop-list js-loop-list">
					<li data-type="1" class="on">全循环</li>
					<li data-type="2">水平循环</li>
					<li data-type="3">垂直循环</li>
					<li data-type="0">无循环</li>
				</ul>
				<div class="err-info">当前部件你未添加图案</div>
				<ul class="slider-list js-slider-list">
					<li class="slider-scale js-slider-scale">
						<div><span>大小</span><span class="text">x1.00</span></div>
						<div>
							<i class="sub"></i>
							<div><button class="js-slider-scale-btn"></button></div>
							<i class="add"></i>
						</div>
					</li>
					<li class="slider-angle js-slider-angle">
						<div><span>角度</span><span class="text">0°</span></div>
						<div>
							<i class="sub"></i>
							<div><button class="js-slider-angle-btn"></button></div>
							<i class="add"></i>
						</div>
					</li>
					<li class="slider-panl js-slider-panl">
						<div><span>水平平移</span><span class="text">x0</span></div>
						<div>
							<i class="sub"></i>
							<div><button class="js-slider-panl-btn"></button></div>
							<i class="add"></i>
						</div>
					</li>
					<li class="slider-panv js-slider-panv">
						<div><span>垂直平移</span><span class="text">x0</span></div>
						<div>
							<i class="sub"></i>
							<div><button class="js-slider-panv-btn"></button></div>
							<i class="add"></i>
						</div>
					</li>
				</ul>
				<div class="color-picker js-color-picker">
					<div class="title">底色调整</div>
					<div class="color-ribbon-box">
						<canvas class="color-ribbon"></canvas>
						<span></span>
					</div>
					<div class="color-swatch-box">
						<canvas class="color-swatch"></canvas>
						<span></span>
					</div>
					<div class="swatch-val">
						<i class="js-straw"></i>
						<span>RGB</span>
						<input readonly type="text" maxlength="11" value="255,255,255">
						<span>#</span>
						<input readonly type="text" maxlength="6" value="FFFFFF">
					</div>
				</div>
				<!-- <div class="info"><i></i><span>鼠标左键旋转图案素材、鼠标中键缩放图案素材，鼠标右键平移图案素材。</span></div> -->
			</div>
		</div>
		<?php }?>
		<!-- 模拟效果 -->
		<div class="virtual-content-box js-virtual-content-box js-scroll-Sb">
			<div class="virtual-content js-virtual-content">
				<svg id="paper" width="100%" height="100%" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"></svg>
				<!--  version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" -->
			</div>
			<?php if ($_smarty_tpl->tpl_vars['isPreview']->value != 1) {?>
			<br>
			<ul class="virtual-control js-virtual-control">
				<li class="js-uploadimg"><i class="uploadimg"></i><span>上传花型</span></li>
				<li class="js-virtual-viewpt nodrop"><i class="viewpt"></i><span>查看图案详情</span></li>
				<li class="js-virtual-save"><i class="save"></i><span>保存至我的制版间</span></li>
				<!--试用账号隐藏下载按钮-->
				<?php if ($_smarty_tpl->tpl_vars['col_user_type']->value != 'TRIAL') {?>
				<li class="js-virtual-download"><i class="download"></i><span>下载模拟效果图</span></li>
				<?php }?>
				<li class="js-virtual-reset"><i class="reset"></i><span>重置</span></li>
			</ul>
			<?php }?>
		</div>
	</div>
	<!-- 选择关注的品类 -->
	<div class="select-category-box js-select-category-box">
		<!-- <i class="close"></i> -->
		<div class="title">选择关注的品类</div>
		<p>选择后，您在模拟成品时，将使用对应模版</p>
		<ul>
			<li data-id="1">服装</li>
			<li data-id="2">箱包</li>
			<li data-id="3">鞋子</li>
			<li data-id="5">家纺</li>
			<li data-id="100">其他</li>
		</ul>
		<button class="confirm">我决定好了</button>
	</div>
	<!-- 提示tip -->
	<div class="tip-box">提示</div>
	<div class="err-box">为保证您的体验流畅性，建议您升级浏览器或使用谷歌浏览器打开！</div>
	<!-- 对话框 删除 -->
	<div class="dialog-box js-dialog-box">
		<i class="close"></i>
		<div class="title">确认要删除数据吗？</div>
		<div class="btns">
			<button class="cancel">取消</button>
			<button class="confirm on">确定</button>
		</div>
	</div>
	<!-- 对话框 添加至分组 -->
	<div class="addmegroup-box js-addmegroup-box">
		<i class="close"></i>
		<div class="title">添加至我的分组</div>
		<div class="ctn">
			<label for="addMeGroupIpt">选择分组</label>
			<input class="group-name" id="addMeGroupIpt" type="text" maxlength="20" placeholder="选择分组或创建新的分组">
			<ul class="megroup-list js-megroup-list js-scroll-Sb">
				<!-- <li data-id=""><span>女装图案</span><div class="btn">添加</div></li> -->
			</ul>
			<div class="empty">
				<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/user/prompt.png?<?php echo @constant('STATIC_CHANGE_TIME');?>
" alt="">
				<span>立即创建分组</span>
			</div>
		</div>
		<div class="btns clear">
			<button class="confirm">+ 确认创建分组</button>
		</div>
	</div>
	<!-- 对话框 移动至分组 -->
	<div class="movegroup-box js-movegroup-box">
		<i class="close"></i>
		<div class="title">移动至我的分组</div>
		<div class="ctn">
			<label>选择分组</label>
			<div class="select" id="moveGroupIpt">
				<span>选择分组</span>
				<ul class="movegroup-list js-movegroup-list js-scroll-Sb"></ul>
			</div>
		</div>
		<div class="btns clear">
			<button class="confirm">确定移动</button>
		</div>
	</div>
	<!-- 对话框 上传上限 -->
	<div class="dialog-box js-upload-limit-box">
		<i class="close"></i>
		<div class="title">可上传的素材数量已达上限</div>
		<p>最多允许上传100张素材，请删除部分图片后再继续上传</p>
		<div class="btns">
			<button class="confirm on">知道了</button>
		</div>
	</div>
	<!-- 对话框 分组上限 -->
	<div class="dialog-box js-group-limit-box">
		<i class="close"></i>
		<div class="title">创建分组数量已达上限<br>您可删除部分分组后继续创建</div>
		<div class="btns">
			<button class="cancel">取消</button>
			<button class="confirm on">确定</button>
		</div>
	</div>
	<!-- 功能提示 -->
	<div class="function-tips js-function-tips">
		<div class="ctn">
			<i class="close"></i>
			<div class="title">我们的功能全新升级了，快来了解下吧</div>
			<div class="bg">
				<a href="https://www.pop136.com/product_help.php?id=15&site=1" target="_blank">查看功能介绍</a>
			</div>
		</div>
	</div>
</div>


        <?php
$_smarty_tpl->properties['nocache_hash'] = '20055561095f324685c31340_61025541';
?>

    </div>
    <!-- 全局dom -->
    <div class="loading-div js-loading-div"></div>
    <div class="bg-div js-bg-div"></div>
    <!-- 问卷dom -->
    <div class="bgDom"></div>
    <div class="iframeDom">
        <div style="overflow: auto;overflow-x:hidden;width: 1000px;height: 650px;" id="iframeDom">
            <iframe src="" id="iframeName" width="1000" height="1800px" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
            <div class="iframeImg">
                <!-- <img src="<?php echo @constant('STATIC_URL2');?>
/global/images/home/u11.png" /> -->
                <img src="<?php echo @constant('STATIC_URL2');?>
/global/images/home/u12.png" />
            </div>
        </div>
    </div>
    
        <?php echo '<script'; ?>
 type="text/javascript">
            var STATIC_URL1 = "<?php echo @constant('STATIC_URL1');?>
";
            var STATIC_URL2 = "<?php echo @constant('STATIC_URL2');?>
";
            var STATIC_URL3 = "<?php echo @constant('STATIC_URL3');?>
";// 推荐js
			var loopTime  = "<?php echo $_smarty_tpl->tpl_vars['loopTime']->value;?>
";
			var loopToken = "<?php echo $_smarty_tpl->tpl_vars['loopToken']->value;?>
";
            var static_change_time =  "<?php echo @constant('STATIC_CHANGE_TIME');?>
";
            var userType = "<?php echo $_smarty_tpl->tpl_vars['user_type']->value;?>
";
            var userId = "<?php echo $_smarty_tpl->tpl_vars['user_id']->value;?>
";
            var accountType = "<?php echo $_smarty_tpl->tpl_vars['account_type']->value;?>
";
            var accountId = "<?php echo $_smarty_tpl->tpl_vars['account_id']->value;?>
";
        <?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('STATIC_URL3');?>
/global/js/lib/require.js?<?php echo @constant('STATIC_CHANGE_TIME');?>
"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="//wpa.b.qq.com/cgi/wpa.php?<?php echo @constant('STATIC_CHANGE_TIME');?>
"><?php echo '</script'; ?>
>
        <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('STATIC_URL3');?>
/global/js/common/main.js?<?php echo @constant('STATIC_CHANGE_TIME');?>
"><?php echo '</script'; ?>
>
    
    <?php
$_smarty_tpl->properties['nocache_hash'] = '20055561095f324685c31340_61025541';
?>

	<?php echo '<script'; ?>
>
		// 添加classList兼容
		if (!("classList" in document.documentElement)) {
			Object.defineProperty(HTMLElement.prototype, 'classList', {
				get: function() {
					var self = this;
					function update(fn) {
						return function(value) {
							var classes = self.className.split(/\s+/g),
								index = classes.indexOf(value);
		
							fn(classes, index, value);
							self.className = classes.join(" ");
						}
					}
		
					return {
						add: update(function(classes, index, value) {
							if (!~index) classes.push(value);
						}),
		
						remove: update(function(classes, index) {
							if (~index) classes.splice(index, 1);
						}),
		
						toggle: update(function(classes, index, value) {
							if (~index)
								classes.splice(index, 1);
							else
								classes.push(value);
						}),
		
						contains: function(value) {
							return !!~self.className.split(/\s+/g).indexOf(value);
						},
		
						item: function(i) {
							return self.className.split(/\s+/g)[i] || null;
						}
					};
				}
			});
		}

		// 添加getPixelColor方法
		if (typeof HTMLElement == 'undefined'){
			var HTMLElement = function(){};
			if (window.webkit) document.createElement("iframe"); //fixes safari
			HTMLElement.prototype = (window.webkit) ? window["[[DOMElement.prototype]]"] : {};
		}
		HTMLElement.prototype.getPixelColor = function(x, y){
			var thisContext = this.getContext("2d");
			var imageData = thisContext.getImageData(x, y, 1, 1);
			// 获取该点像素数据
			var pixel = imageData.data;
			var r = pixel[0];
			var g = pixel[1];
			var b = pixel[2];
			var a = pixel[3] / 255
			a = Math.round(a * 100) / 100;
			var rHex = r.toString(16);
			r < 16 && (rHex = "0" + rHex);
			var gHex = g.toString(16);
			g < 16 && (gHex = "0" + gHex);
			var bHex = b.toString(16);
			b < 16 && (bHex = "0" + bHex);
			var rgbaColor = "rgba(" + r + "," + g + "," + b + "," + a + ")";
			var rgbColor = "rgb(" + r + "," + g + "," + b + ")";
			var hexColor = "#" + rHex + gHex + bHex;
			return {
				rgba : rgbaColor,
				rgb : rgbColor,
				hex : hexColor,
				r : r,
				g : g,
				b : b,
				a : a
			};
		}

	<?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('STATIC_URL3');?>
/global/js/virtual/virtual.js?<?php echo @constant('STATIC_CHANGE_TIME');?>
"><?php echo '</script'; ?>
>

    <?php
$_smarty_tpl->properties['nocache_hash'] = '20055561095f324685c31340_61025541';
?>

	<div class="detail-frame js-detail-frame">
		<iframe src="" width="100%" height="100%"  frameborder="no" border="0" scrolling="no" allowtransparency="yes"></iframe>
	</div>


    
    <!-- GrowingIO Analytics code version 2.1 -->
    <!-- Copyright 2015-2018 GrowingIO, Inc. More info available at http://www.growingio.com -->
    <?php echo '<script'; ?>
 type='text/javascript'>
        !function(e,t,n,g,i){e[i]=e[i]||function(){(e[i].q=e[i].q||[]).push(arguments)},n=t.createElement("script"),tag=t.getElementsByTagName("script")[0],n.async=1,n.src=('https:'==document.location.protocol?'https://':'http://')+g,tag.parentNode.insertBefore(n,tag)}(window,document,"script","assets.giocdn.com/2.1/gio.js","gio");
        gio('init','bc48bd042fbe8722', {});

        //custom page code begin here
        var $user_identity = {'VIP': '会员用户', 'PROBATION': '试用用户', 'GENERAL': '普通用户', 'TOURIST': '游客用户'};
        var $account_type = {'main':'主账号', 'child':'子账号'};

        var $member_set = {};
        $member_set.UserIdentity = $user_identity[userType];

        if (userId) {
            gio('setUserId', userId);
            $member_set.AccountType = $account_type[accountType];
            $member_set.AccountId = accountId;
        } else {
            gio('clearUserId');
        }

        gio('people.set', $member_set);
        //custom page code end here

        gio('send');

    <?php echo '</script'; ?>
>
    <!-- End GrowingIO Analytics code version: 2.1 -->
    

    <?php if ($_smarty_tpl->tpl_vars['google_select_show']->value) {?> 
    
    <!-- google翻译 -->
    <?php echo '<script'; ?>
>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                    // 这个是当前页面的原语言，便于插件精确翻译
                    pageLanguage: 'zh-CN',
                    autoDisplay: true,
                    //0，原生select，并且谷歌logo显示在按钮下方。1，原生select，并且谷歌logo显示在右侧。2，完全展开语言列表，适合pc，
                    layout: /mobile/i.test(navigator.userAgent) ? 0 : 2,
                }, 'google_translate_element' // 触发按钮的id
            );
        }
    <?php echo '</script'; ?>
>
    <!-- <?php echo '<script'; ?>
  src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"><?php echo '</script'; ?>
> -->
    <?php echo '<script'; ?>
  src="//translate.google.cn/translate_a/element.js?&cb=googleTranslateElementInit"><?php echo '</script'; ?>
>
    
    
    <?php }?>

</body>
</html>
<?php }
}
?><?php
/*%%SmartyHeaderCode:9282287365f32468604c263_85997100%%*/
if ($_valid && !is_callable('content_5f324686044743_85575337')) {
function content_5f324686044743_85575337 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '9282287365f32468604c263_85997100';
?>
<!-- header start -->
<?php echo '<script'; ?>
 type="text/javascript">
    function setHeightPos(obj){
        var ow=obj.width;
        var oh=obj.height;
        var k=ow/oh;
        var pw=32;
        var ph=32;
        var nw=0,nh=0;
        if(ow>=oh){
            nw=ph*k;
            obj.style.height=ph+"px";
            obj.style.marginLeft=(pw-nw)/2+"px";
        }else{
            nh=pw/k;
            obj.style.width=pw+"px";
            obj.style.marginTop=(ph-nh)/2+"px";
        }
    };
    function setDefaultHeader(obj){
        var times=obj.getAttribute("data-times") || 0;
        if(times<3){
            times++;
            obj.src="/global/images/common/header-default.png";
            obj.setAttribute("data-times",times);
        }
    };
<?php echo '</script'; ?>
>
<?php if ($_smarty_tpl->tpl_vars['user_type']->value == "TOURIST" && $_smarty_tpl->tpl_vars['is_topic']->value) {?>
<div class="new-header clear">
    <a class="home-btn" href="//yuntu.pop136.com/home" title="云图"><img src="<?php echo @constant('STATIC_URL2');?>
/global/images/common/logo-init.png" alt="POP">云图</a>
    <?php if ($_smarty_tpl->tpl_vars['google_select_show']->value) {?>
    <div id="google_translate_element" style="top:0;"></div>
    <?php }?>
	<a class="btn btn1 btn-type2" href="/user/register/" title="去注册">注册，立即试用</a>
	<a class="btn btn1 btn-type1" href="/user/login/" title="去登录">登录</a>
	<div>
		<span class="icon tel-icon1"></span>4008-210-500 <label>（售前）</label>
		<span class="icon tel-icon2"></span>4008-210-662<label>（售后）</label>

	</div>
</div>
<?php } else { ?>
<div class="header js-header">
    <a class="home-btn" href="//yuntu.pop136.com/home/" title="云图"><img src="<?php echo @constant('STATIC_URL2');?>
/global/images/common/logo.png" alt="POP云图"></a>
    <?php if ($_smarty_tpl->tpl_vars['google_select_show']->value) {?>
    <div id="google_translate_element"></div>
    <?php }?>
    <ul class="nav-list">
        <li><a <?php if ($_smarty_tpl->tpl_vars['col']->value == 3) {?>class="now-choice"<?php }?> href="/patternlibrary/" title="图案&趋势"><span class="icon gallery-spn"></span>图案&趋势</a></li>
        <li><a <?php if ($_smarty_tpl->tpl_vars['col']->value == 2) {?>class="now-choice"<?php }?> href="/similarpatterns/" title="图搜图"><span class="icon search-spn"></span>图搜图</a></li>
        <li><a <?php if ($_smarty_tpl->tpl_vars['col']->value == 1) {?>class="now-choice"<?php }?> href="/virtualtryon/virtualspl/" title="实景模拟"><span class="icon virtual-spn"></span>实景模拟</a></li>
        <?php if (in_array(5,$_smarty_tpl->tpl_vars['col_power']->value) && (in_array(1,$_smarty_tpl->tpl_vars['iTplSite']->value) || empty($_smarty_tpl->tpl_vars['iTplSite']->value))) {?>
        <li><a <?php if ($_smarty_tpl->tpl_vars['col']->value == 5) {?>class="now-choice"<?php }?> href="/simulate3d/" title="3D模拟成品"><span class="icon simulate3d-spn"></span>3D模拟成品</a></li>
        <?php }?>
    </ul>
    <div class="user-info">
        <a class="my-collect" href="/collect/getlist/"><span class="icon"></span>我的收藏</a>
        <a class="personal-center js-personal-center" href="/user/usercenterview/" title="个人中心">
            <img <?php if ($_smarty_tpl->tpl_vars['avatarUri']->value != '') {?> src="<?php echo @constant('STATIC_URL2');
echo $_smarty_tpl->tpl_vars['avatarUri']->value;?>
" <?php } else { ?>src="<?php echo @constant('STATIC_URL2');?>
/global/images/common/header-default.png" <?php }?> alt="头像" onload="window.setHeightPos && window.setHeightPos(this)" onerror="window.setDefaultHeader && window.setDefaultHeader(this)">
            <?php echo $_smarty_tpl->tpl_vars['account']->value;?>

        </a>
        <?php if (!$_smarty_tpl->tpl_vars['no_login_out']->value) {?>
        <a class="login-out-btn js-login-out-btn" href="javascript:void(0);" title="退出登录"><span class="icon"></span>退出</a>
        <?php }?>
        <a class="return-home-btn" href="/home/" title="返回首页"><span class="icon"></span>返回首页</a>
        <p>已有账号，马上<a class="go-login-btn" href="/user/login/" title="登录"><span class="icon"></span>登录</a></p>
    </div>
</div>
<?php }?>
<!-- header end --><?php
/*/%%SmartyNocache:9282287365f32468604c263_85997100%%*/
}
}
?><?php
/*%%SmartyHeaderCode:9174246095f324686610f02_68856280%%*/
if ($_valid && !is_callable('content_5f3246864798f7_59375088')) {
function content_5f3246864798f7_59375088 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '9174246095f324686610f02_68856280';
?>
<!-- footer start -->
<div class="footer">
    <span>上海逸尚云联信息技术股份有限公司 ©2004-2020 法律顾问：北京中银（上海）律师事务所 <a href="http://www.beian.miit.gov.cn/" target="_blank">沪ICP备06003020号-1</a></span>
</div>
<!-- footer end --><?php
/*/%%SmartyNocache:9174246095f324686610f02_68856280%%*/
}
}
?>