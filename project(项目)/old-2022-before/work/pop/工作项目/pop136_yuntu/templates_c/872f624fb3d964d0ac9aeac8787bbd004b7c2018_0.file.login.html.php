<?php /* Smarty version 3.1.27, created on 2020-07-22 16:36:14
         compiled from "/data/htdocs/pop136_yuntu/views/user/login.html" */ ?>
<?php
/*%%SmartyHeaderCode:19591394815f17fa7e2aa328_39962669%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '872f624fb3d964d0ac9aeac8787bbd004b7c2018' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/user/login.html',
      1 => 1587877445,
      2 => 'file',
    ),
    '4b22eedd05bdc48aa69395e981b7badb50229f97' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/layout.html',
      1 => 1587877444,
      2 => 'file',
    ),
    '19f242cd1846fb95f0d48976689e8d6907c82cb6' => 
    array (
      0 => '19f242cd1846fb95f0d48976689e8d6907c82cb6',
      1 => 0,
      2 => 'string',
    ),
    'c270d5b9b9b9fca55e14fe96d756c566613109c6' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/header.html',
      1 => 1595397799,
      2 => 'file',
    ),
    '3f54c68012804add4a5b80043ddb9fe8ac631a56' => 
    array (
      0 => '3f54c68012804add4a5b80043ddb9fe8ac631a56',
      1 => 0,
      2 => 'string',
    ),
    '9c1119b1ce73ee6a0904c21a326721e95d135d9c' => 
    array (
      0 => '9c1119b1ce73ee6a0904c21a326721e95d135d9c',
      1 => 0,
      2 => 'string',
    ),
    'a91b5d00af2d271a5ee5bd89fa15b4701643d6db' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/footer.html',
      1 => 1587877444,
      2 => 'file',
    ),
    'c5fc97f4c23f8f593cf0ef5cfc2ab6fd1d5c180a' => 
    array (
      0 => 'c5fc97f4c23f8f593cf0ef5cfc2ab6fd1d5c180a',
      1 => 0,
      2 => 'string',
    ),
    '24fc4ec35f302205dba6a69dbba24151d9d93402' => 
    array (
      0 => '24fc4ec35f302205dba6a69dbba24151d9d93402',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '19591394815f17fa7e2aa328_39962669',
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
  'unifunc' => 'content_5f17fa7e713fc7_75728930',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5f17fa7e713fc7_75728930')) {
function content_5f17fa7e713fc7_75728930 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '19591394815f17fa7e2aa328_39962669';
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
$_smarty_tpl->properties['nocache_hash'] = '19591394815f17fa7e2aa328_39962669';
?>

	<link rel="stylesheet" type="text/css" href="<?php echo @constant('STATIC_URL1');?>
/global/css/login/login.css?<?php echo @constant('STATIC_CHANGE_TIME');?>
">


    </head>
<body>
    <?php echo $_smarty_tpl->tpl_vars['token']->value;?>

    <div class="view">
        <?php
$_smarty_tpl->properties['nocache_hash'] = '19591394815f17fa7e2aa328_39962669';
?>

<div class="new-header clear">
	<a class="home-btn" href="https://yuntu.pop136.com/home" title="云图"><img src="<?php echo @constant('STATIC_URL2');?>
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


        <?php
$_smarty_tpl->properties['nocache_hash'] = '19591394815f17fa7e2aa328_39962669';
?>

<!-- 登录框 -->
<div class="login-section">
	<div class="section clear">
		<div class="product-div">
			<img class="pic1" src="<?php echo @constant('STATIC_URL2');?>
/global/images/login/6.png?<?php echo @constant('STATIC_CHANGE_TIME');?>
" alt="icon">
			<!-- <a class="item4" href="/topic/20180919/" title="智能设计" target="_blank">智能设计</a> -->
			<span class="item1">模拟成品</span>
			<span class="item2">图案库</span>
			<span class="item3">图搜图</span>
		</div>
		<div class="login-div">
			<h3 class="clear">
				<span>账号登录</span>
				<a class="btn-color1" href="/user/register/" title="注册试用">注册试用</a>
			</h3>
			<form class="js-form-ele" name="loginForm" action="" method="POST" target="_self" autocomplete="off">
				<!--隐藏域-->
				<input type="hidden" name="" value="">
				<!-- 账户 -->
				<input class="input-area js-input-area" name="account" type="text" id="username" placeholder="请输入账号">
				<p class="error-p js-error-ele"><span class="icon"></span>请输入正确的用户名</p>
				<!-- 密码 -->
				<input class="input-area js-input-area" name="password" type="password" id="password" placeholder="请输入登录密码">
                <p class="error-p js-error-ele"><span class="icon"></span>请输入正确的密码</p>
				<!-- 图片验证码 -->
				<span class="code-span clear">
					<input class="input-area js-input-area js-code" id="code" name="verify_code" type="text" placeholder="请输入验证码" maxlength="4"/>
					<img class="js-img-code-btn" src="/user/imgcaptcha/yuntu_login_code/" alt="img-code"/>
				</span>
                <p class="error-p js-error-ele"><span class="icon"></span>请输入正确的验证码</p>
                <!-- 记住用户名 -->
                <div style="float: left;">
                    <input name="user" id="userBox" style="vertical-align: -2px;" type="checkbox" value="" />
                    <span>记住用户名</span>
                </div>
				<!-- 密码相关 -->
				<div class="pass-method clear not-selected">
					<!-- <input id="check-area" class="hide" name="checkbox1" type="checkbox" value="">
					<label class="js-check-area" for="check-area"><img src="<?php echo '<%';?>$WEB_GLOBAL_IMG_URL_NEW<?php echo '%>';?>/global/src/images/login/n-check.png" alt="icon">30天内免登陆</label> -->
					<a href="/user/findpasswordview/" title="忘记密码">忘记密码？</a>
				</div>
				<!-- 提交按钮 -->
				<input type="button" value="立即登录" class="login-btn js-login-btn">
			</form>
		</div>
	</div>
</div>
<div class="introduction-section section clear">
	<h3> “云图”是一套帮助服饰企业进行图案、面料等图像类设计开发的智能系统</h3>
	<ul>
		<!-- <li>
			<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/login/7.png?<?php echo @constant('STATIC_CHANGE_TIME');?>
" alt="icon">
			<div>
				<h3>一分钟在线制作原创花型</h3>
				<p>智能设计提供了大量素材，让您在1分钟内快速实现想要的花型，<br/>从设计要验证效果在云图一步搞定</p>
			</div>
		</li> -->
		<li>
			<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/login/2.png" alt="icon">
			<div>
				<h3>图像识别,节省成本</h3>
				<p>将智能图像识别技术运用到资讯领域，<br/>帮助客户高效进行图案的可行性验证和延展，简化流程，节省成本</p>
			</div>
		</li>
		<li>
			<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/login/3.png" alt="icon">
			<div>
				<h3>本地上传,样衣模拟演示</h3>
				<p>客户可使用本地上传功能， <br/>不限次地上传自有素材到云图上进行样衣模拟演示</p>
			</div>
		</li>
		<li>
			<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/login/4.png" alt="icon">
			<div>
				<h3>27万款图案库,查找相似图案</h3>
				<p>客户自有图案可与数量27万以上的POP图案库关联，<br/>查找相似图案(含位图/矢量图)并下载</p>
			</div>
		</li>
		<li>
			<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/login/5.png" alt="icon">
			<div>
				<h3>免费使用,提供定制模板服务</h3>
				<p>提供不同品类的基础模板，免费给到客户使用，并提供定制模板服务</p>
			</div>
		</li>
	</ul>
	<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/login/1.png?<?php echo @constant('STATIC_CHANGE_TIME');?>
" alt="pic">
</div>
<div class="define-pop-section">
	<div class="section">
		<h3>POP（全球）时尚网络机构<br/>国际时尚资讯服务平台</h3>
		POP提供服装、箱包、鞋子、首饰、家纺等设计所需的流行资讯，<br/>
		专业为设计师提供前沿趋势资讯，海量流行趋势分析报告、款式素材、设计手稿等十几个方面，是行业内服务较全面的时尚资讯平台。
	</div>
</div>
<div class="company-info-section section clear">
	<div class="tel-div">
		<h3><span class="icon"></span>联系方式</h3>
		业务咨询：<span>4008 210 500</span><br/>
		客服热线：<span>4008 210 662</span><br/>
		周一至周日：09:00-22:00
	</div>
	<div class="company-site">
		<h3><span class="icon"></span>公司地址</h3>
		公司总部：上海市虹桥商务区双联路158号东隆大厦3楼<br/>

		广州分部： 广州市海珠区（新港西路82号）轻纺交易园D区时尚发布中心二楼设界2017-2044<br/>

		浙江分部-杭州： 浙江省杭州市江干区德胜东路3333号东大门交易中心B区1027室<br/>

		浙江分部-桐乡： 浙江省桐乡市濮院国际品牌中心B楼交易区320创意广场4F10<br/>

		土耳其分部： Yenidogan mahallesi. 48 sok. No:156 zeytinburnu/Istanbul
	</div>
	<div class="weixin-div">
		<h3>官服微信服务号</h3>
		<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/common/weixin-code.png" alt="weixin-code">
	</div>
</div>


        <?php
$_smarty_tpl->properties['nocache_hash'] = '19591394815f17fa7e2aa328_39962669';
?>

<!-- footer start -->
<div class="footer">
    <span>上海逸尚云联信息技术股份有限公司 © 2004-2020 法律顾问：北京中银（上海）律师事务所 <a href="http://www.beian.miit.gov.cn/" target="_blank">沪ICP备06003020号-1</a></span>
</div>
<!-- footer end -->
<!-- 悬浮留资框 -->
<div class="footwrap">
    <div class="w1200">
        <p class="txt1">限时免费领取POP云图<i></i>试用</p>
        <div class="input-box">
            <span id="msg" class="msg">请输入正确的手机号</span>
            <input type="text" placeholder="输入您的手机号码免费领取" maxlength="11" id="val1">
        </div>
        <a class="free-get js-free-get" href="javascript:void(0);">免费领取</a>
    </div>
</div>
<!-- 提交提示 -->
<div class="sem-fail js-sem-layer">
    <span class="fail-txt">提交失败</span>
    <span class="success-txt">提交成功</span>
    <label class="fail-txt">再试着重新填写一次吧 </label>
    <label class="success-txt">您的专属顾问将会尽快与您联系 </label>
    <div class="fail-btn js-sem-close" data-sta='1'>我知道啦</div>
    <div class="sem-close js-sem-close" data-sta='1'></div>
</div>

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
$_smarty_tpl->properties['nocache_hash'] = '19591394815f17fa7e2aa328_39962669';
?>

	<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('STATIC_URL3');?>
/global/js/login/login.js?<?php echo @constant('STATIC_CHANGE_TIME');?>
"><?php echo '</script'; ?>
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
/*%%SmartyHeaderCode:3117771785f17fa7e4c5a91_01221380%%*/
if ($_valid && !is_callable('content_5f17fa7e4c3db3_27465012')) {
function content_5f17fa7e4c3db3_27465012 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '3117771785f17fa7e4c5a91_01221380';
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
/*/%%SmartyNocache:3117771785f17fa7e4c5a91_01221380%%*/
}
}
?><?php
/*%%SmartyHeaderCode:16215278485f17fa7e65d624_42988831%%*/
if ($_valid && !is_callable('content_5f17fa7e65c663_78950021')) {
function content_5f17fa7e65c663_78950021 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '16215278485f17fa7e65d624_42988831';
?>
<!-- footer start -->
<div class="footer">
    <span>上海逸尚云联信息技术股份有限公司 ©2004-2020 法律顾问：北京中银（上海）律师事务所 <a href="http://www.beian.miit.gov.cn/" target="_blank">沪ICP备06003020号-1</a></span>
</div>
<!-- footer end --><?php
/*/%%SmartyNocache:16215278485f17fa7e65d624_42988831%%*/
}
}
?>