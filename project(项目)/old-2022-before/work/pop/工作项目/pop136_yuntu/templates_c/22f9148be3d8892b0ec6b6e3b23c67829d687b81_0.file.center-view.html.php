<?php /* Smarty version 3.1.27, created on 2020-08-07 15:04:35
         compiled from "/data/htdocs/pop136_yuntu/views/user/center-view.html" */ ?>
<?php
/*%%SmartyHeaderCode:18068831305f2cfd03f12ba6_25699635%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '22f9148be3d8892b0ec6b6e3b23c67829d687b81' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/user/center-view.html',
      1 => 1594384067,
      2 => 'file',
    ),
    '4b22eedd05bdc48aa69395e981b7badb50229f97' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/layout.html',
      1 => 1587877444,
      2 => 'file',
    ),
    '55791ee080ea92c7fd258d09184f1a36e440f3b3' => 
    array (
      0 => '55791ee080ea92c7fd258d09184f1a36e440f3b3',
      1 => 0,
      2 => 'string',
    ),
    'c270d5b9b9b9fca55e14fe96d756c566613109c6' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/header.html',
      1 => 1595397799,
      2 => 'file',
    ),
    '35aa34b996d3183825cc39952b35fce669d66b48' => 
    array (
      0 => '35aa34b996d3183825cc39952b35fce669d66b48',
      1 => 0,
      2 => 'string',
    ),
    'a91b5d00af2d271a5ee5bd89fa15b4701643d6db' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/footer.html',
      1 => 1587877444,
      2 => 'file',
    ),
    'acfbc192e16c15829fc6d4830073c005c5024044' => 
    array (
      0 => 'acfbc192e16c15829fc6d4830073c005c5024044',
      1 => 0,
      2 => 'string',
    ),
    'f8ed5147597c48d440542efc0a8489bd1b28de1d' => 
    array (
      0 => 'f8ed5147597c48d440542efc0a8489bd1b28de1d',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '18068831305f2cfd03f12ba6_25699635',
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
  'unifunc' => 'content_5f2cfd041ba799_78570475',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5f2cfd041ba799_78570475')) {
function content_5f2cfd041ba799_78570475 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '18068831305f2cfd03f12ba6_25699635';
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
$_smarty_tpl->properties['nocache_hash'] = '18068831305f2cfd03f12ba6_25699635';
?>

<link rel="stylesheet" type="text/css"
	href="<?php echo @constant('STATIC_URL1');?>
/global/css/lib/perfect-scrollbar-og.css?<?php echo @constant('STATIC_CHANGE_TIME');?>
">
<link rel="stylesheet" type="text/css"
	href="<?php echo @constant('STATIC_URL1');?>
/global/css/user/center-view.css?<?php echo @constant('STATIC_CHANGE_TIME');?>
" />


    </head>
<body>
    <?php echo $_smarty_tpl->tpl_vars['token']->value;?>

    <div class="view">
        
        <?php /*  Call merged included template "header.html" */
echo $_smarty_tpl->getInlineSubTemplate("header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '12825103065f2cfd040d4886_89112992', 'content_5f2cfd040d3b66_82531890');
/*  End of included template "header.html" */?>

        

        <?php
$_smarty_tpl->properties['nocache_hash'] = '18068831305f2cfd03f12ba6_25699635';
?>

<!-- <input class="params" type="hide" value="<?php echo $_smarty_tpl->tpl_vars['module_power']->value['yuntu:virtualtry'][1];?>
"> -->
<div class="content js-content-section">
	<div class="personal-section">
		<div class="personal-work section js-section clear">
			<div class="person-vav clear js-person-vav hide js-page"
				page-name="userinfo userinfo-set masterroom-group masterroom-virtual">
				<div class="person-pic">
					<label for="upload-src">
						<img class="js-fill-ele js-img-ele" data-key="photo"
							src="<?php echo @constant('STATIC_URL2');?>
/global/images/common/header-default.png" alt="头像" />
						<span>更换头像</span>
					</label>
					<!-- <p class="js-fill-ele" data-key="account"></p> -->
					<form class="js-form hide" enctype="multipart/form-data">
						<input class="js-upload-ele" id="upload-src" name="uploadAvatar" type="file">
					</form>
				</div>
				<div class="fl">
					<p class="account-name js-fill-ele" data-key="account"></p>
					<div class="pages-list js-pages-list">
						<a href="/user/usercenterview">制版间</a>
						<a href="/user/usercenterview?page=userinfo">账号管理</a>
					</div>
				</div>
				<div class="tab-list hide js-page" page-name="masterroom-group masterroom-virtual">
					<!-- <a class="tab3d" href="javascript:;">3D模拟成品</a> -->
					<a class="tab2d js-tab2d" href="/user/usercenterview">实景模拟</a>
				</div>
				<div class="tab-list hide js-page" page-name="userinfo userinfo-set">
					<a class="tab-msg js-tab-msg" href="/user/usercenterview?page=userinfo">账号信息</a>
					<a class="tab-set js-tab-set" href="/user/usercenterview?page=userinfo-set">账号设置</a>
				</div>
				<!-- <a class="service-link js-contact-qq-btn" data-type="1" href="javascript:void(0);">
					<span class="icon"></span>
					联系客服
				</a> -->
			</div>
			<div class="lab-list js-lab-list hide js-page" page-name="masterroom-group masterroom-virtual">
				<a href="/user/usercenterview/">分组</a>
				<a href="/user/usercenterview/?page=masterroom-virtual">全部</a>
				<a class="fr batch-manage js-batch-manage" href="javascript:;">批量管理</a>
			</div>
			<div class="check-col js-check-col clear hide js-page" page-name="masterroom-group">
				<div class="section">
					<a class="fl setall on" href="javascript:;">全选</a>
					<a class="additem" href="javascript:;">添加</a>
					<a class="delete on" href="javascript:;">删除</a>
				</div>
			</div>
			<div class="check-col js-vircheck-col clear hide js-page" page-name="groupdetail">
				<div class="section">
					<a class="fl setall on" href="javascript:;">全选</a>
					<a class="remove" href="javascript:;">移除</a>
					<a class="moveto" href="javascript:;">移动至...</a>
					<a class="delete on" href="javascript:;">删除</a>
				</div>
			</div>
			<div class="person-right">
				<!-- 实景模拟 分组 -->
				<div class="master-right js-div-section js-lab-ctn hide js-page" page-name="masterroom-group">
					<ul class="group-list js-water-fall1 js-group-list clear">
						<li class="add">
							<i></i>
							<span>创建分组</span>
						</li>
						<!-- <li data-id="" data-name=""><img src="" alt=""><div class="edit">编辑</div><div class="title">test</div></li> -->
					</ul>
					<div class="nav-loading js-nav-loading"><i></i><span>加载中</span></div>
				</div>
				<!-- 实景模拟 全部 -->
				<div class="master-right js-div-section js-lab-ctn hide js-page" page-name="masterroom-virtual">
					<ul class="val-list js-water-fall2 js-vir-list">
						<li class="add">
							<i></i>
							<span>添加效果图</span>
						</li>
						<!-- <li data-id=""><img src="" alt=""><div class="check-btn"></div></li> -->
					</ul>
					<div class="nav-loading js-nav-loading"><i></i><span>加载中</span></div>
				</div>
				<!-- 分组详情 -->
				<div class="group-detail js-div-section js-lab-ctn hide js-page" page-name="groupdetail">
					<div class="title-box clear">
						<div class="fl name js-group-name"></div>
						<div class="fr js-col-box">
							<div class="btn edit">编辑</div>
							<div class="btn batch-manage">批量管理</div>
							<div class="text share">分享</div>
						</div>
					</div>
					<ul class="val-list js-water-fall3 js-groupvir-list">
						<li class="add">
							<i></i>
							<span>添加效果图</span>
						</li>
						<!-- <li data-id=""><img src="" alt=""><div class="check-btn"></div></li> -->
					</ul>
					<div class="nav-loading js-nav-loading"><i></i><span>加载中</span></div>
				</div>
				<!--账号信息-->
				<div class="account-right js-div-section hide js-page" page-name="userinfo">
					<ul class="msg-list">
						<li class="clear">
							<span>注册账户：</span>
							<p class="js-fill-ele" data-key="account"></p>
						</li>
						<li class="clear">
							<span>注册时间：</span>
							<p class="js-fill-ele" data-key="register_time"></p>
						</li>
						<li class="clear">
							<span>会员类型：</span>
							<p class="js-vip-show-p"></span></p>
						</li>
					</ul>

					<ul class="acount-list clear js-vip-list">
						<li class="tu-an" data-id="3">
							<div class="acount-icon">
								<p>高清\矢量图下载、图案色彩分析、图案内容分析</p>
							</div>
							<p>图案&趋势</p>
							<span></span>
							<div class="ktinfo">未开通</div>
						</li>
						<li class="sou-tu" data-id="2">
							<div class="acount-icon">
								<p>图像云计算、人工智能匹配</p>
							</div>
							<p>图搜图</p>
							<span></span>
							<div class="ktinfo">未开通</div>
						</li>
						<li class="shi-yi" data-id="1">
							<div class="acount-icon">
								<p>本地图案试衣、款式模板调色</p>
							</div>
							<p>实景模拟</p>
							<span></span>
							<div class="ktinfo">未开通</div>
						</li>
						<li class="fin-tu" data-id="5">
							<div class="acount-icon">
								<p>通过3D模型看图案花型面料在实物上的效果，支持更换主料辅料</p>
							</div>
							<p>3D模拟成品</p>
							<span></span>
							<div class="ktinfo">未开通</div>
						</li>
					</ul>
					<div class="use-num clear js-use-num">
						<span>会员使用情况：</span>
						<p>
							<span class="js-fill-ele" data-key="iLoginNumber"></span> 人（目前在线数量／最大限制数量）
						</p>
					</div>
				</div>
				<!--账号设置-->
				<div class="account-right js-div-section hide js-page" page-name="userinfo-set">
					<ul class="setuser-list js-setuser-list">
						<li class="on">
							<div class="title">修改绑定手机号</div>
							<div class="ctn">
								<ul class="form-list js-form-list1">
									<li class="account clear">
										<label for="account-ipt1">账户已绑定：</label>
										<input id="account-ipt1 js-number-only" class="account-ipt js-account-ipt1 js-input-area" name="mobile"
											type="mobile" placeholder="已绑定手机号" maxlength="11" readonly value="">
									</li>
									<li class="ptcode clear">
										<label for="ptcode-ipt1">图形验证码：</label>
										<input id="ptcode-ipt1" class=" js-input-area" name="img_code" type="text"
											placeholder="请输入图形验证码" maxlength="4">
										<img class="ptcode-code js-img-code-btn" src="/user/imgcaptcha/bind_imgcode1/"
											data-src="/user/imgcaptcha/bind_imgcode1/" alt="img-code">
										<div class="err">请输入图形验证码</div>
									</li>
									<li class="sms clear">
										<label for="sms-ipt1">短信验证码：</label>
										<input id="sms-ipt1" class=" js-input-area" name="msg_code" type="text"
											placeholder="请输入短信验证码">
										<div class="sms-code js-get-code" data-key="is_get_code1">获取验证码</div>
										<div class="err">请输入短信验证码</div>
									</li>
									<li>
										<div class="btn js-next-btn1">下一步</div>
									</li>
								</ul>
								<ul class="form-list js-form-list2 hide">
									<li class="account clear">
										<label for="account-ipt2">新绑手机号：</label>
										<input id="account-ipt2" class="js-number-only" name="newMobile" type="mobile" placeholder="请输入手机号"
											maxlength="11">
										<div class="err">请输入手机号</div>
									</li>
									<li class="ptcode clear">
										<label for="ptcode-ipt2">图形验证码：</label>
										<input id="ptcode-ipt2" name="img_code" type="text" placeholder="请输入图形验证码"
											maxlength="4">
										<img class="ptcode-code js-img-code-btn" src="/user/imgcaptcha/bind_imgcode2/"
											data-src="/user/imgcaptcha/bind_imgcode2/" alt="img-code">
										<div class="err">请输入图形验证码</div>
									</li>
									<li class="sms clear">
										<label for="sms-ipt2">短信验证码：</label>
										<input id="sms-ipt2" name="msg_code" type="text" placeholder="请输入短信验证码">
										<div class="sms-code js-get-code" data-key="is_get_code2">获取验证码</div>
										<div class="err">请输入短信验证码</div>
									</li>
									<li>
										<div class="btn on js-next-btn2">提交</div>
									</li>
								</ul>
							</div>
						</li>
						<li>
							<div class="title">修改密码</div>
							<div class="ctn">
								<ul class="form-list js-form-list3">
									<li class="pswdog clear">
										<label for="pswdog-ipt">原密码：</label>
										<input id="pswdog-ipt" type="password" name="oldPassword" maxlength="20" placeholder="请输入原密码" autocomplete="new-password">
										<div class="err">请输入原密码</div>
									</li>
									<li class="pswdnew clear">
										<label for="pswdnew-ipt">新密码：</label>
										<input id="pswdnew-ipt" class="js-new-password" type="password" name="newPassword" maxlength="20" placeholder="请输入新密码" autocomplete="new-password">
										<ul class="level js-strength-list">
											<li>低</li>
											<li>中</li>
											<li>高</li>
										</ul>
										<div class="err">请输入新密码</div>
									</li>
									<li class="pswdagn clear">
										<label for="pswdagn-ipt">确认新密码：</label>
										<input id="pswdagn-ipt" type="password" name="reNewPassword" maxlength="20" placeholder="请再次输入密码进行确认，注意区分大小写" autocomplete="new-password">
										<div class="err">请再次输入密码进行确认，注意区分大小写</div>
									</li>
									<li>
										<div class="btn on js-sub-change-password">提交</div>
									</li>
								</ul>
							</div>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<!-- 提示tip -->
	<div class="tip-box">提示</div>
	<!-- 对话框 编辑分组 -->
	<div class="dialog-box js-dialog-box" store="deit-group">
		<i class="close"></i>
		<div class="title" store="add-group">添加分组</div>
		<div class="title" store="deit-group">编辑分组</div>
		<div class="title" store="delete">确认要删除数据吗？</div>
		<div class="title" store="delete-group">确认删除这个分组吗？</div>
		<div class="ctn" store="deit-group add-group">
			<input class="group-name" type="text" maxlength="20" placeholder="请输入分组名称">
		</div>
		<div class="btns clear">
			<button class="delete" store="deit-group">删除分组</button>
			<button class="cancel" store="delete">取消</button>
			<button class="cancel" store="delete-group">取消</button>
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
/global/images/user/prompt.png" alt="">
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
	<!-- 对话框 分享 on -->
	<div class="share-box js-share-box">
		<i class="close"></i>
		<div class="ctn">
			<div class="no-copy">
				<label>分享形式：</label>
				<ul class="radio">
					<li class="on" data-id="1">私密仅拥有分享码的用户可以查看</li>
					<li data-id="0">公开 任何人都可查看</li>
				</ul>
			</div>
			<div class="no-copy">
				<label class="sel">有效期：</label>
				<div class="select" id="shareIpt">
					<span class="on" data-id="1">1天</span>
					<ul class="share-list js-share-list">
						<li data-id="1">1天</li>
						<li data-id="7">7天</li>
						<li data-id="30">一个月</li>
						<li data-id="0">永久</li>
					</ul>
				</div>
			</div>
			<div class="copy-item yq-link">
				<label class="sel" style="margin-right: 14px;">链接：</label>
				<input type="text" placeholder="生成链接" readonly="readonly">
			</div>
			<div class="copy-item yq-code">
				<label class="sel">邀请码：</label>
				<input type="text" placeholder="邀请码" readonly="readonly">
			</div>
			<p class="copy-item yq-time"></p>
		</div>
		<div class="btns clear">
			<button class="cancel no-copy">取消</button>
			<button class="confirm on no-copy">确定</button>
			<button class="confirm-copy on copy-item">复制链接及邀请码</button>
		</div>
	</div>
	<!-- 对话框 分组上限 -->
	<div class="limit-box js-group-limit-box">
		<i class="close"></i>
		<div class="title">创建分组数量已达上限<br>您可删除部分分组后继续创建</div>
		<div class="btns">
			<button class="cancel">取消</button>
			<button class="confirm on">确定</button>
		</div>
	</div>
</div>



        <?php
$_smarty_tpl->properties['nocache_hash'] = '18068831305f2cfd03f12ba6_25699635';
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
$_smarty_tpl->properties['nocache_hash'] = '18068831305f2cfd03f12ba6_25699635';
?>

<?php echo '<script'; ?>
 type="text/javascript">
	require.config({
		paths: {
			"master-room": ["user/master-room"],
			"change-userinfo": ["user/change-userinfo"],
			"ajaxform": ["lib/ajaxform/ajaxform"],
			"perfectScrollbar": ["lib/perfect-scrollbar-1.2.min"],
		},
		shim: {
			"ajaxform": {
				deps: ["jquery"]
			}
		}
	});
	require(["jquery", "general", "msg", "ajaxform"], function (jquery, general, msg, ajaxform) {
		$(function () {
			var def = {
				date: new Date(),
				is_load: false,																						//是否上传的图
				// content_def_height:0,
				href_obj: general.fn.getLocationParameter() || {},
				nav_ele: $(".js-person-list"),
				section_ele: $(".js-div-section"),
				fill_ele: $(".js-fill-ele"),
				loading_ele: $(".js-loading-div"),                                                                   //加载等待
				bg_ele: $(".js-bg-div")	                                                                            //加载等待
			};
			// def.content_def_height=$(".js-content-section").height();
			// setSty(def.content_def_height);


			// 拉取用户信息
			general.fn.subAjax({
				url: "/user/getuserinfo/?t=" + def.date.getTime(),
				type: "get",
				ctp: "application/x-www-form-urlencoded",
				success: setUserInfo,
				error: function (data) {
					var ndata = data || {};
					if (ndata.code == 1002) {
						window.location.href = "/system/systemnotice/?type=4";
					}
					def.loading_ele.fadeOut(200);
					def.bg_ele.fadeOut(400);
				}
			});

			function setUserInfo(data) {
				var ndata = data || {};
				var is_yuntu = ndata.data.memo != undefined ? ndata.data.memo : false;
				if (is_yuntu == false) {
					var npage = def.href_obj["page"] != undefined ? def.href_obj["page"] : "masterroom-group";
					$('.js-tab-set').remove();
					if (npage === 'userinfo-set') {
						window.location.replace('/user/usercenterview?page=userinfo');
					}
				}
				$('.js-account-ipt1').val(ndata.data.bind_mobile);
				def.fill_ele.each(function () {
					var key = $(this).attr("data-key") || "";
					if (key != "") {
						var nval = ndata.data[key] || "";
						if (key == "photo") {
							$(this).attr("src", general.def.img_path + nval);
							return;
						}
						if (key == "iLoginNumber") {
							var online_num = ndata.data["Number"] != undefined ? ndata.data["Number"] : "";
							nval = online_num + "/" + nval;
						}
						$(this).text(nval);
					}
				});

				// 功能
				var isvip = ndata.data.isvip != undefined ? ndata.data.isvip : false;
				var narr = ndata.data.yuntu_power || [], tag = $(".js-vip-list");
				tag.children('li').each(function () {
					var iTplSite = ndata.data.iTplSite || '';
					var nid = $(this).attr('data-id');
					for (var i = 0, len = narr.length; i < len; i++) {
						var s_column = narr[i]["sColumn"] || '';
						var endtime = narr[i]["dEndTime"].substr(0, 10) || "";
						endtime = endtime.replace(/-/g, "/");
						if (nid == '5' && $.inArray(1, iTplSite) == -1) {
							$(this).addClass("iTplSite");
						} else if (nid == s_column) {
							$(this).addClass("vip-item");
							$(this).children("span").text("有效期至：" + endtime).siblings('.ktinfo').text('已开通');
							break;
						}
					}
				});

				var show_txt = isvip == true ? "云图VIP会员<span>（已开通）</span>" : "普通会员";
				$(".js-vip-show-p").html(show_txt);
				if (isvip != true) {
					$(".js-use-num").hide();
				}
				def.loading_ele.fadeOut(200);
				def.bg_ele.fadeOut(400);
			};

			// $(window).on('resize',function(){
			// 	general.fn.throttle(setSty,null,[def.content_def_height]);
			// });
			// function setSty(nh){
			//     var wh=document.documentElement.clientHeight || document.body.clientHeight;
			//     if(wh>nh+60){
			//         $(".js-content-section").height(wh-60+"px");
			//     }
			// };


			var npage = def.href_obj["page"] != undefined ? def.href_obj["page"] : "masterroom-group";
			if (npage == "masterroom-group") {
				$('.js-page[page-name~="masterroom-group"]').removeClass('hide');
				$('.js-pages-list').children().eq(0).addClass('on').siblings().removeClass('on');
				$('.js-lab-list').children().eq(0).addClass('on');
				$('.js-tab2d').addClass('on');
				require(["master-room"]);
			} else if (npage == "masterroom-virtual") {
				$('.js-page[page-name~="masterroom-virtual"]').removeClass('hide');
				$('.js-pages-list').children().eq(0).addClass('on').siblings().removeClass('on');
				$('.js-lab-list').children().eq(1).addClass('on').next().addClass('on');
				$('.js-tab2d').addClass('on');
				require(["master-room"]);
			} else if (npage == "groupdetail") {
				$('.js-page[page-name~="groupdetail"]').removeClass('hide');
				require(["master-room"]);
			} else if (npage == "userinfo") {
				$('.js-page[page-name~="userinfo"]').removeClass('hide');
				$('.js-pages-list').children().eq(1).addClass('on').siblings().removeClass('on');
				$('.js-tab-msg').addClass('on');
			} else if (npage == "userinfo-set") {
				$('.js-page[page-name~="userinfo-set"]').removeClass('hide');
				$('.js-pages-list').children().eq(1).addClass('on').siblings().removeClass('on');
				$('.js-tab-set').addClass('on');
				require(["change-userinfo"]);
			}/* else if(npage=="changemobile"){
				require(["change-mobile"]);
				def.nav_ele.eq(1).addClass("nav-sec");
				def.section_ele.eq(1).show().siblings(".js-div-section").hide();
			}else if(npage=="changepassword"){
				require(["change-password"]);
				def.nav_ele.eq(2).addClass("nav-sec");
				def.section_ele.eq(2).show().siblings(".js-div-section").hide();
			} */



			//上传头像
			$(".js-upload-ele").on("change", function () {
				$(".js-form").submit();
			});

			// 头像处理
			$(".js-img-ele").on("load", function () {
				// if(def.is_load==false){
				var ow = this.width;
				var oh = this.height;
				var k = ow / oh;
				var pw = $(this).parent().width();
				var ph = $(this).parent().height();
				var nw = 0, nh = 0;
				if (ow >= oh) {
					nw = ph * k;
					$(this).css("height", ph + "px");
					$(this).css("margin-left", (pw - nw) / 2 + "px");
				} else {
					nh = pw / k;
					$(this).css("width", pw + "px");
					$(this).css("margin-top", (ph - nh) / 2 + "px");
				}
				// }else{
				// 	var src=$(this).attr("src");
				// 	getImgSize(this,src);
				// }

			}).on("error", function () {
				var times = $(this).attr("data-times") || 0;
				if (times < 3) {
					times++;
					$(this).attr("src", general.def.img_path + "/global/images/common/" + general.def.img.header_default);
					$(this).attr("data-times", times);
				}
			});

			setAjaxForm();
			// 模拟form表单提交
			function setAjaxForm() {
				var options = {
					target: '#upload-src',   // target element(s) to be updated with server response 
					beforeSubmit: function (arr, $from, options) {
						def.loading_ele.fadeIn(200);
						def.bg_ele.fadeIn(400);
					},
					dataType: "json",
					success: function (data) {
						if (data && data.data) {
							var ndata = data.data || {};
							var src = ndata.info || "";
							def.is_load = true;
							$(".js-img-ele").attr("src", general.def.img_path + src);
							// $(".js-img-ele").attr("src",general.def.img_path+"/global/images/common/head-def.jpg");
							$(".js-form")[0].reset();
							def.loading_ele.fadeOut(200);
							def.bg_ele.fadeOut(400);
						}

					},
					error: function (data) {
						// console.log("图片上传失败！");
						def.loading_ele.fadeOut(200);
						def.bg_ele.fadeOut(400);
						$(".js-form")[0].reset();
					},
					clearForm: true,
					url: "/user/uploadavatar/",
					type: "post"
					//timeout:   3000 
				};

				// bind to the form's submit event 
				$('.js-form').submit(function () {
					$(this).ajaxSubmit(options);
					return false;
				});
				// $(".js-form").ajaxSubmit(options);

			};

			// 获取宽高属性
			function getImgSize(obj, src) {
				var img = new Image();
				img.onload = function () {
					var ow = this.width;
					var oh = this.height;
					var k = ow / oh;
					var pw = obj.parentNode.offsetWidth - 8;
					var ph = obj.parentNode.offsetHeight - 8;
					var nw = 0, nh = 0;
					if (ow >= oh) {
						nw = ph * k;
						obj.style.height = ph + "px";
						obj.style.marginLeft = (pw - nw) / 2 + "px";
					} else {
						nh = pw / k;
						obj.style.width = pw + "px";
						obj.style.marginTop = (ph - nh) / 2 + "px";
					}
				}
				img.src = src;
			};

		});
	});
<?php echo '</script'; ?>
>

    

    
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
/*%%SmartyHeaderCode:12825103065f2cfd040d4886_89112992%%*/
if ($_valid && !is_callable('content_5f2cfd040d3b66_82531890')) {
function content_5f2cfd040d3b66_82531890 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '12825103065f2cfd040d4886_89112992';
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
/*/%%SmartyNocache:12825103065f2cfd040d4886_89112992%%*/
}
}
?><?php
/*%%SmartyHeaderCode:2781667115f2cfd04155435_15138810%%*/
if ($_valid && !is_callable('content_5f2cfd04154c01_05840386')) {
function content_5f2cfd04154c01_05840386 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '2781667115f2cfd04155435_15138810';
?>
<!-- footer start -->
<div class="footer">
    <span>上海逸尚云联信息技术股份有限公司 ©2004-2020 法律顾问：北京中银（上海）律师事务所 <a href="http://www.beian.miit.gov.cn/" target="_blank">沪ICP备06003020号-1</a></span>
</div>
<!-- footer end --><?php
/*/%%SmartyNocache:2781667115f2cfd04155435_15138810%%*/
}
}
?>