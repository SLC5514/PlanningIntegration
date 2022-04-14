<?php /* Smarty version 3.1.27, created on 2020-07-30 09:16:17
         compiled from "/data/htdocs/pop136_yuntu/views/system/system-notice.html" */ ?>
<?php
/*%%SmartyHeaderCode:13551495015f221f6159d528_17993765%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fc004e8b0d4ba1ee4c54b1526485b822fb8cfcdc' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/system/system-notice.html',
      1 => 1587877444,
      2 => 'file',
    ),
    '4b22eedd05bdc48aa69395e981b7badb50229f97' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/layout.html',
      1 => 1587877444,
      2 => 'file',
    ),
    'd865eef590d35801858c26f95a30a1ca3b821834' => 
    array (
      0 => 'd865eef590d35801858c26f95a30a1ca3b821834',
      1 => 0,
      2 => 'string',
    ),
    'c270d5b9b9b9fca55e14fe96d756c566613109c6' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/header.html',
      1 => 1595397799,
      2 => 'file',
    ),
    'bf030fc2d919b8b21bdcabdc3d77584abce339d7' => 
    array (
      0 => 'bf030fc2d919b8b21bdcabdc3d77584abce339d7',
      1 => 0,
      2 => 'string',
    ),
    'a91b5d00af2d271a5ee5bd89fa15b4701643d6db' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/footer.html',
      1 => 1587877444,
      2 => 'file',
    ),
    'daa1e0b062147118c975b090e315fcdda2a0d242' => 
    array (
      0 => 'daa1e0b062147118c975b090e315fcdda2a0d242',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '13551495015f221f6159d528_17993765',
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
  'unifunc' => 'content_5f221f61c9cd93_19470106',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5f221f61c9cd93_19470106')) {
function content_5f221f61c9cd93_19470106 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '13551495015f221f6159d528_17993765';
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
$_smarty_tpl->properties['nocache_hash'] = '13551495015f221f6159d528_17993765';
?>

<link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL1;?>
/global/css/system/system-notice.css?<?php echo @constant('STATIC_CHANGE_TIME');?>
" />


    </head>
<body>
    <?php echo $_smarty_tpl->tpl_vars['token']->value;?>

    <div class="view">
        
        <?php /*  Call merged included template "header.html" */
echo $_smarty_tpl->getInlineSubTemplate("header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '2235509725f221f61970ad2_26702434', 'content_5f221f6196f991_97231189');
/*  End of included template "header.html" */?>

        

        <?php
$_smarty_tpl->properties['nocache_hash'] = '13551495015f221f6159d528_17993765';
?>

<div class="content js-content-section">
	<div class="section vip-null">
	
		<div class="null-pic"></div>
		<p class="msg-line js-msg-line">对不起，您正在使用的是VIP功能，请联系客服<span>开通VIP</span>后使用！</p>
		<p class="msg-line js-msg-line">您所使用的浏览器不支持模拟成品功能，请用谷歌浏览器（chrome）打开页面或点击<a href="//download.skycn.com/hao123-soft-online-bcs/soft/C/2015-12-17_ChromeStandalone_47.0.2526.106_Setup.1450323126.exe" title="下载">下载</a>安装浏览器！</p>
		<p class="msg-line js-msg-line">对不起！您帐号限制同时使用人数已满，可稍后再试...<br/>如有任何疑问，您可以联系帐号管理员或售后服务人员进行咨询。</p>
		<p class="msg-line js-msg-line">对不起！您的登录已超时，请重新<a href="/user/login/" title="去登录">登录</a>！</p>
		<p class="msg-line js-msg-line">1分钟在线绘制原创图案，想体验？</p>
		<p class="msg-line js-msg-line">模拟成品体验次数已耗尽，升级为企业账号，从此打样0成本</p>
		<p class="msg-line js-msg-line">以图搜图体验次数已耗尽，升级为企业VIP账号，可随时随地畅享搜图体验</p>
		<p class="msg-line js-msg-line">当前账号权限不足，升级为企业VIP账号，查看高清大图，享受智能推荐特权</p>
		<div class="clear link-msg js-link-msg">
			<?php if (empty($_smarty_tpl->tpl_vars['userId']->value)) {?>
				<a class="js-contact-qq-btn" href="javascript:void(0);" data-type="2" rel="nofollow"><span class="icon"></span>在线客服</a>
				<div><span class="icon"></span class="js-tel-spn">咨询热线：4008-210-500</div>
			<?php } else { ?>
				<a class="js-contact-qq-btn" href="javascript:void(0);" data-type="1" rel="nofollow"><span class="icon"></span>在线客服</a>
				<div><span class="icon"></span class="js-tel-spn">咨询热线：4008-210-662</div>
			<?php }?>
		</div>
		<div class="clear link-msg hide js-upgrade-vip-div">
			<a class="upgrade-vip-btn" href="/topic/enterprise_service/" target="_blank" data-type="1" rel="nofollow" title="查看详情">了解企业VIP特权服务</a>
		</div>
		<a class="js-return-back-btn" href="javascript:void(0);" title="返回">返回</a>
	</div>
</div>


        
        <?php /*  Call merged included template "footer.html" */
echo $_smarty_tpl->getInlineSubTemplate("footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '13953043215f221f61b33e31_66924386', 'content_5f221f61b16bb9_91658664');
/*  End of included template "footer.html" */?>

        
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
$_smarty_tpl->properties['nocache_hash'] = '13551495015f221f6159d528_17993765';
?>

<?php echo '<script'; ?>
 type="text/javascript" charset="utf-8">
	
	require(["jquery","general"],function(jquery,general){
		$(function(){
			var def={
				href_obj:general.fn.getLocationParameter() || {},
				content_def_height:0,
				loading_ele:$(".js-loading-div"),                                                                   //加载等待
	            bg_ele:$(".js-bg-div")	                                                                            //加载等待
			};
			def.loading_ele.fadeOut(200);
	        def.bg_ele.fadeOut(400);
			var ntype=def.href_obj["type"]!=undefined?def.href_obj["type"]:1;
			$(".js-msg-line").eq(ntype-1).show();
			if(ntype==1){
				// $(".js-contact-qq-btn").attr("data-type",2);
			}else if(ntype==2){
				$(".js-contact-qq-btn").attr("data-type",1);
				$(".js-tel-spn").text("咨询热线：4008-210-662");
				$(".js-login-out-btn").hide();
			}else if(ntype==3 || ntype==4){
				$(".js-login-out-btn").hide();
				$(".js-personal-center").hide();
			}else if(ntype==6 || ntype==7){
				$('.js-link-msg').hide();
				$('.js-return-back-btn').hide();
				$('.js-upgrade-vip-div').show();
			}

			def.content_def_height=$(".js-content-section").height();
			setSty(def.content_def_height);

			$(window).on('resize',function(){
	            general.fn.throttle(setSty,null,[def.content_def_height]);
	        });
	        function setSty(nh){
	            var wh=document.documentElement.clientHeight || document.body.clientHeight;
	            if(wh>nh+159){
	                $(".js-content-section").height(wh-159+"px");
	            }
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
/*%%SmartyHeaderCode:2235509725f221f61970ad2_26702434%%*/
if ($_valid && !is_callable('content_5f221f6196f991_97231189')) {
function content_5f221f6196f991_97231189 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '2235509725f221f61970ad2_26702434';
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
/*/%%SmartyNocache:2235509725f221f61970ad2_26702434%%*/
}
}
?><?php
/*%%SmartyHeaderCode:13953043215f221f61b33e31_66924386%%*/
if ($_valid && !is_callable('content_5f221f61b16bb9_91658664')) {
function content_5f221f61b16bb9_91658664 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '13953043215f221f61b33e31_66924386';
?>
<!-- footer start -->
<div class="footer">
    <span>上海逸尚云联信息技术股份有限公司 ©2004-2020 法律顾问：北京中银（上海）律师事务所 <a href="http://www.beian.miit.gov.cn/" target="_blank">沪ICP备06003020号-1</a></span>
</div>
<!-- footer end --><?php
/*/%%SmartyNocache:13953043215f221f61b33e31_66924386%%*/
}
}
?>