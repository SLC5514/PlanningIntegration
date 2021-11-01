<?php /* Smarty version 3.1.27, created on 2020-06-02 11:59:07
         compiled from "/data/htdocs/pop136_yuntu/views/user/register.html" */ ?>
<?php
/*%%SmartyHeaderCode:8182132255ed5ce8b9de946_39588725%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'dd9eeb2522a0726b20ed2b855fcf64e1599c89c9' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/user/register.html',
      1 => 1591070123,
      2 => 'file',
    ),
    '4b22eedd05bdc48aa69395e981b7badb50229f97' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/layout.html',
      1 => 1587877444,
      2 => 'file',
    ),
    '3f23278b0513413201aa434f1ac114fcd8a681b4' => 
    array (
      0 => '3f23278b0513413201aa434f1ac114fcd8a681b4',
      1 => 0,
      2 => 'string',
    ),
    'c270d5b9b9b9fca55e14fe96d756c566613109c6' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/header.html',
      1 => 1591004404,
      2 => 'file',
    ),
    '1cb73799d40bbc27720cf59305428b23ec9b0083' => 
    array (
      0 => '1cb73799d40bbc27720cf59305428b23ec9b0083',
      1 => 0,
      2 => 'string',
    ),
    'a91b5d00af2d271a5ee5bd89fa15b4701643d6db' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/footer.html',
      1 => 1587877444,
      2 => 'file',
    ),
    '3976c8150922d82882d3e33c92cb288507fb9e1c' => 
    array (
      0 => '3976c8150922d82882d3e33c92cb288507fb9e1c',
      1 => 0,
      2 => 'string',
    ),
    'a612b9bea26b25875920f8b19482c00c0ff1c905' => 
    array (
      0 => 'a612b9bea26b25875920f8b19482c00c0ff1c905',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '8182132255ed5ce8b9de946_39588725',
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
  'unifunc' => 'content_5ed5ce8bca4a43_62570908',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5ed5ce8bca4a43_62570908')) {
function content_5ed5ce8bca4a43_62570908 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '8182132255ed5ce8b9de946_39588725';
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
$_smarty_tpl->properties['nocache_hash'] = '8182132255ed5ce8b9de946_39588725';
?>

	<link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL1;?>
/global/css/login/register.css?<?php echo STATIC_CHANGE_TIME;?>
">


    </head>
<body>
    <?php echo $_smarty_tpl->tpl_vars['token']->value;?>

    <div class="view">
        
        <?php /*  Call merged included template "header.html" */
echo $_smarty_tpl->getInlineSubTemplate("header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '17732213575ed5ce8bb44981_62811752', 'content_5ed5ce8bb437a3_44515366');
/*  End of included template "header.html" */?>

        

        <?php
$_smarty_tpl->properties['nocache_hash'] = '8182132255ed5ce8b9de946_39588725';
?>

<!-- 登录框 -->
<div class="content js-content-section">
	<div class="register-section js-register-section">
		<div class="section clear">
			<div class="advertisement-div js-advertisement-div">
				<a href="" title=""><img src="<?php echo STATIC_URL2;?>
/global/images/login/1.jpg?<?php echo STATIC_CHANGE_TIME;?>
" alt=""></a>
				<button class="js-contact-qq-btn" data-type="2"><span class="icon"></span>联系客服</button>
			</div>
			<div class="register-div js-register-div">
				<h3><span>欢迎注册云图会员</span></h3>
				<form class="js-form-ele" action="" autocomplete="off">
					<!--隐藏域-->
					<input type="hidden" name="" value="">
					<ul class="register-list">
						<li>
							<label for="account">用户名：</label><input value="" autocomplete="off" class="js-input-area" id="account" name="account" type="text" maxlength="20">
							<span class="icon"></span>
							<p>请输入用户名</p>
						</li>
						<li>
							<label for="real-name">真实姓名：</label><input value="" autocomplete="off" class="js-input-area" id="real-name" name="real_name" type="text" maxlength="20">
							<span class="icon"></span>
							<p>请输入真实姓名</p>
						</li>
						<li>
							<label for="password">登录密码：</label><input value="" autocomplete="new-password" class="js-input-area js-password-area" id="password" name="password" type="password" maxlength="20">
							<span class="icon"></span>
							<p>请输入密码</p>
						</li>
						<li>
							<label for="re-password">确认密码：</label><input value="" autocomplete="off" class="js-input-area js-password-area" id="re-password" name="re_password" type="password" maxlength="20">
							<span class="icon"></span>
							<p>两次密码输入不一致</p>
						</li>
						<li>
							<label for="mobile">手机号码：</label><input value="" class="js-input-area js-mobile-area" id="mobile" name="mobile" type="text" maxlength="11">
							<span class="icon"></span>

							<p>手机号码为空，请正确填写</p>
						</li>

						<!-- <li class="img-code-item">
							<label for="img-code">图片验证码：</label><input value="" class="js-input-area" id="img-code" name="img_code" type="text" maxlength="4">
							<span class="icon"></span>
							<img class="js-img-code-btn" src="/user/imgcaptcha/reg_code/" alt="img-code"/>
							<p>请输入图形验证码</p>
						</li> -->
						<li class="msg-code-item">
							<label for="check-code">短信验证码：</label><input value="" class="js-input-area" id="check-code" name="check_code" type="text" maxlength="6">
							<span class="icon"></span>
							<a class="js-get-code get-code">获取验证码</a>
							<p>请输入短信验证码</p>
						</li>
						<li>
							<label for="category" class="label-category">主营品类：</label>
							<div class="select js-input-area js-category-area" id="category" name="category"></div>
							<!-- <input value="" class="select js-input-area js-category-area" id="category" name="category" type="text" readonly> -->
							<ul class="select-options">
								<li data-id="1">服装<i></i></li>
								<li data-id="2">箱包<i></i></li>
								<li data-id="3">鞋子<i></i></li>
								<li data-id="4">首饰<i></i></li>
								<li data-id="5">家纺<i></i></li>
							</ul>
							<i class="select-icon" onclick="$(this).parent('li').children('.select').trigger('click')"></i>
							<p>请选择主营品类</p>
						</li>
					</ul>
					<button class="icon agree-btn js-agree-btn"></button><span class="font-12 v-c">我同意</span><button class="agreement-btn v-c js-agreement-btn" title="服务协议">网站服务协议</button>
					<button class="register-btn js-register-btn" title="立即注册">立即注册</button>
				</form>
				<p><span class="icon"></span>如过程中遇到问题，请联系客服</p>
			</div>
		</div>
	</div>
</div>


        
        <?php /*  Call merged included template "footer.html" */
echo $_smarty_tpl->getInlineSubTemplate("footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '16967413005ed5ce8bbf3626_10818521', 'content_5ed5ce8bbf2ca4_95530621');
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
$_smarty_tpl->properties['nocache_hash'] = '8182132255ed5ce8b9de946_39588725';
?>

<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/login/register.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/lib/gt.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>

    <?php
$_smarty_tpl->properties['nocache_hash'] = '8182132255ed5ce8b9de946_39588725';
?>

	<div class="agreement-section js-agreement-section">
		<button class="icon js-close-btn" title="关闭"></button>
		<h3><span>POP服务协议</span>
			<div></div>
		</h3>
		<div class="txt-div">
			<h4>一、接受条款</h4>
			<p>通过首页进入"POP(全球)时尚网络机构"网站即表示您同意自己已经与上海逸尚云联信息技术股份有限公司(以下简称"本公司")订立本协议，且您将受本协议的条款和条件("条款")约束。本公司可随时自行全权决定更改"条款"。如"条款"有任何变更，本公司将在本网站上刊载公告，通知予您。如您不同意相关变更，必须停止使用"服务"。经修订的"条款"一经在本网站的公布后，立即自动生效。您登录或继续使用"服务"将表示您接受经修订的"条款"。除另行明确声明外，任何使"服务"范围扩大或功能增强的新内容均受本协议约束。除非经本公司的授权高层管理人员签订书面协议，本协议不得另行作出修订。</p>
			<h4>二、您的资料</h4>
			<p>"您的资料"是您在本网站上的注册资料，或者通过任何电子邮件形式向本公司提供的任何资料。您应对"您的资料"负全部责任，而本公司仅作为您在网上发布和刊登"您的资料"的被动渠道。但是，倘若本公司认为"您的资料"可能使本公司承担任何法律或道义上的责任，或可能使本公司全部或部分地失去本公司互联网服务客户，或你未在本网站规定的期限内登录或再次登录网站，则本公司可自行全权决定对"您的资料"采取本公司认为必要或适当的任何行动，包括但不限于删除该类资料。您特此保证，您对提交给本网站"您的资料"拥有全部权利。</p>
			<h4>三、关于收费</h4>
			<p>本网站之作品都是经过本公司设计师精心设计的产品，你在浏览或下载本网站的有偿服务区域时，本网站有收取服务费的权利。您通过网络向本公司获取有偿服务或接触本公司服务器而发生的应纳税赋，以及一切硬件、软件、服务及其他方面的费用均由您负责支付。本公司保留在无须发出通知的情况下，暂时或永久地更改或停止部分或全部服务的权利。</p>
			<h4>四、注册义务：</h4>
			<p>如果你在本网站注册，根据本网站注册网址所刊载的会员资料表格的要求，提供关于您或贵公司的真实、准确、完整和反映当前情况的资料；您应该维持并及时更新会员资料，使其保持真实、准确、完整和反映当前情况。倘若您提供任何不真实、不准确、不完整或不能反映当前情况的资料，或本网站有合理理由怀疑该等资料不真实、不准确、不完整或不能反映当前情况，本网站有权暂停或终止您的注册身份及资料，并拒绝您在目前或将来对"服务"(或其任何部分)以任何形式使用。如您代表一家公司或其他法律主体在本公司登记，则您声明和保证，您有权使该公司或其他法律主体受本协议"条款"约束。 在登记过程中，您将选择会员注册名和密码。您须自行负责对您的会员注册名和密码保密，且须对您在会员注册名和密码下发生的所有活动承担责任。您同意：(a)如发现任何人未经授权使用您的会员注册名或密码，或发生违反保密规定的任何其他情况，您会立即通知本网站；及(b)确保您在每个上网时段结束时，以正确步骤离开网站。本网站不能也不会对因您未能遵守本款规定而发生的任何损失或损毁负责。</p>
			<h4>五、不作商业性利用</h4>
			<p>您同意，您不得对任何资料作商业性利用，包括但不限于在未经本网站授权高层管理人员事先书面批准的情况下，复制本网站上展示的任何资料。</p>
			<h4>六、终止或访问限制</h4>
			<p>您同意，本网站可自行全权决定以任何理由(包括但不限于本网站认为您已违反本协议的字面意义和精神，或以不符合本协议的字面意义和精神的方式行事)终止您的"服务"密码、帐户(或其任何部分)或您对"服务"的使用，本网站同时可自行全权决定，在发出通知或不发出通知的情况下，随时停止提供"服务"或其任何部分。您同意，根据本协议的任何规定终止您使用"服务"之措施可在不发出事先通知的情况下实施，并承认和同意，本网站可立即使您的帐户无效，或撤销您的帐户以及在您的帐户内的所有相关资料和档案，和/或禁止您进一步接入该等档案或"服务"。账号终止后，本网站没有义务为您保留原账号中或与之相关的任何信息。</p>
			<h4>七、违反规则会有什么后果？</h4>
			<p>在不限制其他补救措施的前提下，发生下述任一情况，本公司可立即发出警告，暂时中止、永久中止或终止您的会员资格：(i)您违反本协议；(ii)本公司无法核实或鉴定您向本公司提供的任何资料；或(iii)您的账号和第三方共用。</p>
			<h4>八、服务"按现状"提供</h4>
			<p>本公司会尽一切努力使您在使用本网站过程中得到乐趣。遗憾的是，本公司不能随时预见到任何技术上的问题或其他困难。该等困难可能会导致数据损失或其他服务中断。为此，您明确理解和同意，您使用"服务"的风险由您自行承担。"服务"以"按现状"和"按可得到"的基础提供。</p>
			<h4>九、赔偿</h4>
			<p>您同意，因您违反本协议或经在此提及而纳入本协议的其他文件，包括把账号和密码借给第三方使用，及把我方精心设计的产品图片传播给第三方。</p>
			<h4>十、链接</h4>
			<p>由于本网站并不控制链接网站的资源，您承认并同意，本网站并不对该等外在网站或资源的可用性负责，且不认可该等网站或资源上或可从该等网站或资源获取的任何内容、宣传、产品、服务或其他材料，也不对其等负责或承担任何责任。您进一步承认和同意，对于任何因使用或信赖从此类网站或资源上获取的此类内容、宣传、产品、服务或其他材料而造成（或声称造成）的任何直接或间接损失，本网站均不承担责任。</p>
			<h4>十一、转让</h4>
			<p>本网站转让本协议无需经您同意。</p>
			<h4>十二、仲裁</h4>
			<p>因本协议或本公司服务所引起或与其有关的任何争议应提交上海市仲裁委员会并根据其适用的仲裁规则进行仲裁裁决。</p>
		</div>
		<div class="btn-div clear">
			<button class="sure-agree-btn js-sure-agree-btn" title="接受">接受</button>
			<button class="cancel-agree-btn js-cancel-agree-btn" itle="取消">取消</button>
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
/*%%SmartyHeaderCode:17732213575ed5ce8bb44981_62811752%%*/
if ($_valid && !is_callable('content_5ed5ce8bb437a3_44515366')) {
function content_5ed5ce8bb437a3_44515366 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '17732213575ed5ce8bb44981_62811752';
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
        <li><a <?php if ($_smarty_tpl->tpl_vars['col']->value == 3) {?>class="now-choice"<?php }?> href="/patternlibrary/" title="图案库"><span class="icon gallery-spn"></span>图案库</a></li>
        <li><a <?php if ($_smarty_tpl->tpl_vars['col']->value == 2) {?>class="now-choice"<?php }?> href="/similarpatterns/" title="图搜图"><span class="icon search-spn"></span>图搜图</a></li>
        <li><a <?php if ($_smarty_tpl->tpl_vars['col']->value == 1) {?>class="now-choice"<?php }?> href="/virtualtryon/virtualspl/" title="实景模拟"><span class="icon virtual-spn"></span>实景模拟</a></li>
        <!-- <?php if (in_array(5,$_smarty_tpl->tpl_vars['col_power']->value) && ($_smarty_tpl->tpl_vars['iTplSite']->value == 1 || empty($_smarty_tpl->tpl_vars['iTplSite']->value))) {?>
        <li><a <?php if ($_smarty_tpl->tpl_vars['col']->value == 5) {?>class="now-choice"<?php }?> href="/simulate3d/" title="3D模拟成品"><span class="icon simulate3d-spn"></span>3D模拟成品</a></li>
        <?php }?> -->
    </ul>
    <div class="user-info">
        <a class="my-collect" href="/collect/getlist/"><span class="icon"></span>我的收藏</a>
        <a class="personal-center js-personal-center" href="/user/usercenterview/" title="个人中心">
            <img <?php if ($_smarty_tpl->tpl_vars['avatarUri']->value != '') {?> src="<?php echo @constant('STATIC_URL2');
echo $_smarty_tpl->tpl_vars['avatarUri']->value;?>
" <?php } else { ?>src="<?php echo @constant('STATIC_URL2');?>
/global/images/common/header-default.png" <?php }?> alt="头像" onload="setHeightPos(this)" onerror="setDefaultHeader(this)">
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
/*/%%SmartyNocache:17732213575ed5ce8bb44981_62811752%%*/
}
}
?><?php
/*%%SmartyHeaderCode:16967413005ed5ce8bbf3626_10818521%%*/
if ($_valid && !is_callable('content_5ed5ce8bbf2ca4_95530621')) {
function content_5ed5ce8bbf2ca4_95530621 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '16967413005ed5ce8bbf3626_10818521';
?>
<!-- footer start -->
<div class="footer">
    <span>上海逸尚云联信息技术股份有限公司 ©2004-2020 法律顾问：北京中银（上海）律师事务所 <a href="http://www.beian.miit.gov.cn/" target="_blank">沪ICP备06003020号-1</a></span>
</div>
<!-- footer end --><?php
/*/%%SmartyNocache:16967413005ed5ce8bbf3626_10818521%%*/
}
}
?>