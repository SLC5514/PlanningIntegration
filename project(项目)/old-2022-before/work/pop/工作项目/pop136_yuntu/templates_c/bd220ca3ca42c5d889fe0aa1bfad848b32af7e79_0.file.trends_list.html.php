<?php /* Smarty version 3.1.27, created on 2020-06-29 16:09:48
         compiled from "/data/htdocs/pop136_yuntu/views/report/trends_list.html" */ ?>
<?php
/*%%SmartyHeaderCode:7149511725ef9a1cc47f063_95325280%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bd220ca3ca42c5d889fe0aa1bfad848b32af7e79' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/report/trends_list.html',
      1 => 1593414837,
      2 => 'file',
    ),
    '4b22eedd05bdc48aa69395e981b7badb50229f97' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/layout.html',
      1 => 1587877444,
      2 => 'file',
    ),
    '7f3f0ef5620ac7bc902bcdda7af75a9a469e32d4' => 
    array (
      0 => '7f3f0ef5620ac7bc902bcdda7af75a9a469e32d4',
      1 => 0,
      2 => 'string',
    ),
    'c270d5b9b9b9fca55e14fe96d756c566613109c6' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/header.html',
      1 => 1592644410,
      2 => 'file',
    ),
    '4b09f3e233dcbdd071a7fae9456cab9009a64a72' => 
    array (
      0 => '4b09f3e233dcbdd071a7fae9456cab9009a64a72',
      1 => 0,
      2 => 'string',
    ),
    'a91b5d00af2d271a5ee5bd89fa15b4701643d6db' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/footer.html',
      1 => 1587877444,
      2 => 'file',
    ),
    '2280aa49232bf32d3869a2b9a600eb5d590a077e' => 
    array (
      0 => '2280aa49232bf32d3869a2b9a600eb5d590a077e',
      1 => 0,
      2 => 'string',
    ),
    '4e5e43ca26f19bf260cf350eaf4e92449525beea' => 
    array (
      0 => '4e5e43ca26f19bf260cf350eaf4e92449525beea',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '7149511725ef9a1cc47f063_95325280',
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
  'unifunc' => 'content_5ef9a1cc9ab082_63003200',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5ef9a1cc9ab082_63003200')) {
function content_5ef9a1cc9ab082_63003200 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '7149511725ef9a1cc47f063_95325280';
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
$_smarty_tpl->properties['nocache_hash'] = '7149511725ef9a1cc47f063_95325280';
?>

<!-- <link rel="stylesheet" type="text/css" href="<?php echo @constant('STATIC_URL1');?>
/global/css/lib/perfect-scrollbar.css?<?php echo @constant('STATIC_CHANGE_TIME');?>
"> -->
<link rel="stylesheet" type="text/css" href="<?php echo @constant('STATIC_URL1');?>
/global/css/report/list.css?<?php echo @constant('STATIC_CHANGE_TIME');?>
" />


    </head>
<body>
    <?php echo $_smarty_tpl->tpl_vars['token']->value;?>

    <div class="view">
        
        <?php /*  Call merged included template "header.html" */
echo $_smarty_tpl->getInlineSubTemplate("header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '9600846625ef9a1cc758576_28665195', 'content_5ef9a1cc757693_37430622');
/*  End of included template "header.html" */?>

        

        <?php
$_smarty_tpl->properties['nocache_hash'] = '7149511725ef9a1cc47f063_95325280';
?>

<div class="content">
	<div id="cond" data-params="<?php echo $_smarty_tpl->tpl_vars['params']->value;?>
" data-key="<?php echo $_smarty_tpl->tpl_vars['key']->value;?>
" style="display:none;"></div>
	<div class="section">
		<!-- 列表控制器 -->
		<div class="list-controller">
			<div class="select-nav js-select-nav">
				<div class="selected clear js-keycho hide">
					<span>您已经选择：</span>
					<div>
						<!-- <span>季节：2020春夏<a class="close"></a></span> -->
					</div>
					<a class="empty" href="/trendspattern/">清空筛选</a>
				</div>
				<div class="select clear" data-key="gen" data-name="sGender">
					<span class="label">性别</span>
					<div>
						<!-- <a>男鞋</a> -->
					</div>
				</div>
				<div class="select season clear" data-key="sea" data-name="iSeason">
					<span class="label">季节</span>
					<div>
						<!-- <a>2020春夏</a> -->
					</div>
					<a class="more js-season-more">更多</a>
				</div>
			</div>
			<div class="search-nav clear">
				<ul class="type-list clear fl js-type-list">
					<li class="on"><a href="javascript:void(0);">最新</a></li>
					<li><a href="javascript:void(0);">最热</a></li>
					<!-- <li class="on"><a href="/trendspattern/sor_1/">最新</a></li>
					<li><a href="/trendspattern/sor_2/">最热</a></li> -->
				</ul>
				<div class="fr clear screen-result">
					<div class="search-box js-search-box clear">
						<input class="sear-input js-sear-input" type="text" placeholder="搜索">
						<input class="icon neiye-list js-neiye-list" type="button">
					</div>
				</div>
			</div>
		</div>
		<!--搜索结果为空-->
		<div class="trends-null js-trends-null">
			<div class="null-pic"></div>
			<p>抱歉，搜索不到相关结果！可尝试更换关键字或减少筛选条件哦~</p>
			<div class="clear link-msg">
				<a class="js-contact-qq-btn" href="javascript:void(0);" data-type="1" rel="nofollow"><span class="icon"></span>在线客服</a>
				<div><span class="icon"></span>咨询热线：4008-210-662</div>
			</div>
		</div>
		<!-- 列表 -->
		<div class="trends-section js-trends-section">
			<ul class="clear js-trends-list js-lists-parent">
				<!-- <li>
					<div class="collect-data js-collect-data"></div>
					<a href="javascript:void(0);" target="_blank">
						<img src="" alt="">
					</a>
					<div class="text-down">
						<a href="javascript:void(0);" target="_blank">
							<p class="time clear">
								<span class="fl">浏览（263）</span>
								<span class="fr">2019-07-13</span>
							</p>
							<p class="title" title="--女装围巾图案趋势">--女装围巾图案趋势</p>
						</a>
						<div class="label">
							<a href="javascript:void(0);" target="_blank" title="2019春夏">2019春夏</a>
							<a href="javascript:void(0);" target="_blank" title="围巾">围巾</a>
						</div>
					</div>
				</li> -->
			</ul>
			<!--分页-->
			<div class="rightc-bottom js-rightc-bottom"></div>
			<!-- <div class="list-more js-list-more"><span class="icon"></span>下拉加载更...</div> -->
		</div>
	</div>
</div>


        
        <?php /*  Call merged included template "footer.html" */
echo $_smarty_tpl->getInlineSubTemplate("footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '3413331945ef9a1cc8b8485_27756603', 'content_5ef9a1cc8b7759_11973963');
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
$_smarty_tpl->properties['nocache_hash'] = '7149511725ef9a1cc47f063_95325280';
?>

	<?php echo '<script'; ?>
 src="<?php echo @constant('STATIC_URL3');?>
/global/js/report/list.js?<?php echo @constant('STATIC_CHANGE_TIME');?>
" type="text/javascript" charset="utf-8"><?php echo '</script'; ?>
>
	<?php echo '<script'; ?>
 src="<?php echo @constant('STATIC_URL3');?>
/global/js/pattern/collect-common.js?<?php echo @constant('STATIC_CHANGE_TIME');?>
" type="text/javascript" charset="utf-8"><?php echo '</script'; ?>
>

    <?php
$_smarty_tpl->properties['nocache_hash'] = '7149511725ef9a1cc47f063_95325280';
?>

	<!-- 收藏提示tip -->
	<div class="tip-box">提示</div>
	<!-- 最新趋势 普通用户提示 -->
	<div class="trend-prompt js-trend-prompt">
		<div class="section">
			<div class="close"></div>
			<div class="title">企业VIP用户专享</div>
			<div class="info">查看海量图案趋势报告<br/>第一时间掌握市场流行图案花型趋势变化</div>
			<a class="btn" href="/topic/enterprise_service/" target="_blank">了解企业VIP特权服务</a>
		</div>
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
/*%%SmartyHeaderCode:9600846625ef9a1cc758576_28665195%%*/
if ($_valid && !is_callable('content_5ef9a1cc757693_37430622')) {
function content_5ef9a1cc757693_37430622 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '9600846625ef9a1cc758576_28665195';
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
/*/%%SmartyNocache:9600846625ef9a1cc758576_28665195%%*/
}
}
?><?php
/*%%SmartyHeaderCode:3413331945ef9a1cc8b8485_27756603%%*/
if ($_valid && !is_callable('content_5ef9a1cc8b7759_11973963')) {
function content_5ef9a1cc8b7759_11973963 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '3413331945ef9a1cc8b8485_27756603';
?>
<!-- footer start -->
<div class="footer">
    <span>上海逸尚云联信息技术股份有限公司 ©2004-2020 法律顾问：北京中银（上海）律师事务所 <a href="http://www.beian.miit.gov.cn/" target="_blank">沪ICP备06003020号-1</a></span>
</div>
<!-- footer end --><?php
/*/%%SmartyNocache:3413331945ef9a1cc8b8485_27756603%%*/
}
}
?>