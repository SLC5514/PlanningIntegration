<?php /* Smarty version 3.1.27, created on 2020-08-10 09:57:31
         compiled from "/data/htdocs/pop136_yuntu/views/pattern/detail.html" */ ?>
<?php
/*%%SmartyHeaderCode:1867806445f30a98bc0de94_88474289%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '05ea055e603651adcc3f98ab61a69c44e805b1f4' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/pattern/detail.html',
      1 => 1596503948,
      2 => 'file',
    ),
    '4b22eedd05bdc48aa69395e981b7badb50229f97' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/layout.html',
      1 => 1587877444,
      2 => 'file',
    ),
    '9dd1528d89931b1dfe738770d009ee314bafe69e' => 
    array (
      0 => '9dd1528d89931b1dfe738770d009ee314bafe69e',
      1 => 0,
      2 => 'string',
    ),
    'c270d5b9b9b9fca55e14fe96d756c566613109c6' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/header.html',
      1 => 1595397799,
      2 => 'file',
    ),
    '0e5e5ef2456a4736443a2537899f49a1d58b59c3' => 
    array (
      0 => '0e5e5ef2456a4736443a2537899f49a1d58b59c3',
      1 => 0,
      2 => 'string',
    ),
    'a91b5d00af2d271a5ee5bd89fa15b4701643d6db' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/footer.html',
      1 => 1587877444,
      2 => 'file',
    ),
    '32a6b6370a04492e98e62745030d1373f0b71dda' => 
    array (
      0 => '32a6b6370a04492e98e62745030d1373f0b71dda',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '1867806445f30a98bc0de94_88474289',
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
  'unifunc' => 'content_5f30a98bdc7311_00574052',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5f30a98bdc7311_00574052')) {
function content_5f30a98bdc7311_00574052 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '1867806445f30a98bc0de94_88474289';
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
$_smarty_tpl->properties['nocache_hash'] = '1867806445f30a98bc0de94_88474289';
?>

<link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL1;?>
/global/css/pattern/detail.css?<?php echo @constant('STATIC_CHANGE_TIME');?>
" />


    </head>
<body>
    <?php echo $_smarty_tpl->tpl_vars['token']->value;?>

    <div class="view">
        
        <?php /*  Call merged included template "header.html" */
echo $_smarty_tpl->getInlineSubTemplate("header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '15260562105f30a98bce7101_22760963', 'content_5f30a98bce68e3_38541628');
/*  End of included template "header.html" */?>

        

        <?php
$_smarty_tpl->properties['nocache_hash'] = '1867806445f30a98bc0de94_88474289';
?>

<div class="section detail-contant" id="pop_layer">
	<!--内容-->
	<div class="detail-content js-detail-content">
		<div class="detail-close js-pop-close"></div>
        <div class="pics-prev pics-item-control js-pic-prev">
            <p></p>
            <span>上一款</span>
        </div>
    
        <div class="pics-next pics-item-control js-pic-next">
            <p></p>
            <span>下一款</span>
        </div>
	    
		<div class="big-contain">
			<div class="bp-load js-bp-load"></div>
			<div class="img-contain ui-draggable ui-draggable-handle js-img-contain" id="draggable">
		        <img class="bigbox js-bigbox" src="https://imgf2.pop-fashion.com/global/images/new_index/ds4.png"
					 data-t="" data-id="" data-sp="" data-mp="" data-bp="" data-bpurlencode="" data-staticurl="" data-sign="" data-col="" data-shape="" data-size="" data-up="" data-rename="">
		    </div>
		</div>
	</div>

	<!--侧边导航-->
	<div class="nav-right js-nav-right Scrollbar">
		<div class="nav-control js-nav-control"></div>
		<div class="right-cont ">
			<div class="msg-list clear">
				<span class="icon icon6"></span>
				<ul class="clear js-msg-list"></ul>
			</div>
			<div class="img-detail">
				<div class="img-title">
					<span class="icon icon5"></span>细节图
				</div>
				<ul class="clear js-min-imgs"></ul>
			</div>
			<div class="guess-like-list">
				<div>
					<span class="icon icon9"></span>相关推荐
				</div>
				<ul class="clear js-guess-like">
					<li>
						<a>
							<img src=""/>
						</a>
						<div class="js-dislike-btn">不喜欢</div>
					</li>
					<li></li>
				</ul>
			</div>
		</div>
	</div>
	
	<!--底部-->
	<div class="detail-footer js-detail-footer">
		<div class="footer-cont">
			<!--图片控制器-->
			<div class="pic-control js-pic-control">
				<div class="bp-set js-bp-set">
					<div class="clear">
						<div class="disc-icon">
							<p style="padding: 8px 0 12px;margin-top: -2px;">
								<span class="set-icon1 icon"></span>
							</p>
							<p class="esc-p">原图</p>
							<p class="esc-p">复位</p>
						</div>
						<div class="dic-msg">
							<p style="padding-top: 4px;">滚动鼠标中键或<span class="set-icon2 icon"></span>拖拽放大镜，<br />缩放图片</p>
							<p style="padding-top: 14px;">点击原图，显示原始尺寸</p>
							<p style="padding-top: 17px;">点击复位，恢复缩放图片为初始状<br />态</p>
						</div>
					</div>
					<div class="dic-close">知道了</div>
				</div>
				<div class="control-l js-control-l">
					<span class="icon"></span>
				</div>
				<p class="js-original">原图</p>
				<p class="js-reset on-reset">复位</p>
			</div>

			<ul class="clear js-foot-list">
				<?php if ($_smarty_tpl->tpl_vars['showMiYin']->value && $_smarty_tpl->tpl_vars['miHui_detail']->value) {?>
				   <li class="js-edit-pattern-btn">
                       <a class="bott-item" href="javascript:void(0);" title="重新编辑"><span class="icon icon7"></span>重新编辑</a>
                   </li>
				<?php }?>				
				<li class="js-yun-vcloth">
					<a  class="bott-item" href="javascript:void(0);" title="实景模拟"><span class="icon icon1"></span>实景模拟</a>
				</li>
				<li class="js-spatter-pic">
					<a  class="bott-item" href="javascript:void(0);" title="相似图案"><span class="icon icon2"></span>相似图案</a>
				</li>
				<!--试用账号隐藏下载按钮-->
				<?php if ($_smarty_tpl->tpl_vars['col_user_type']->value != 'TRIAL') {?>
				<!-- 下载单张 -->
				<li class="detail-down js-detail-down">
					<div class="bott-item">
						<div  class="bott-item"><span class="icon icon3"></span>下载素材</div>
					</div>
                    
                    <div class="downimg-layer downimg-layer-data js-downimg-layer">
                    	<div class="close-arrow js-close-arrow"></div>
	                    <div class="down-main-top clear">
	                        <h2>下载文件格式</h2>
						</div>
                        <?php if ($_smarty_tpl->tpl_vars['miHui_detail']->value) {?>
						  <div class="overdue-warming">若您喜欢这个图案，请于2019年8月1日前下载</div>
                        <?php }?>
	                    <table class="down-table js-down-table" width="100%">
				        	<thead>
								<tr>
									<th width="25%">格式</th>
									<th width="25%">尺寸</th>
									<th width="25%">文件大小</th>
									<th width="25%">点击下载</th>
								</tr>
							</thead>
							<tbody data-downtype="">		
								<tr>
									<td>.jpg</td>
									<td class="img-shape">尺寸</td>
									<td class="img-size">文件大小</td>
									<td><a class="download-btn js-download-btn" data-bp="">下载</a></td>
								</tr>
							</tbody>
				        </table>
	                    <div class="box-arrow"></div>
	                </div>
				</li>
				<?php }?>
				<!-- 搜索列表 -->
				<li class="js-detail-search detail-search detail-search2">
					<div  class="bott-item"><span class="icon icon4"></span><span class="search-msg1">收起图案列表</span><span class="search-msg2">展开图案列表</span></div>
				</li>
				<li class="js-collect-btn collect-detail">
					<a  class="bott-item" href="javascript:void(0);" title="加入收藏"><span class="icon icon8"></span>加入收藏</a>
				</li>
			</ul>

			<!-- 图片列表 -->
			<div class="patter-contain js-patter-contain">
				<div class="list-prev js-list-prev icon"></div>
				<div class="list-next js-list-next icon"></div>
				
				<div class="list-cont js-list-cont">
					<ul class="clear js-detail-list"></ul>
				</div>
				
			</div>
			<!-- /图片列表 -->
			
		</div>
	</div>
	<!--试用账号隐藏下载按钮-->
	<?php if ($_smarty_tpl->tpl_vars['col_user_type']->value != 'TRIAL') {?>
    <!-- 右键下载 -->
    <div class="bg-layer js-bg-layer"></div>
    <div class="rclick-layer downimg-layer-data js-downimg-right">
    	<div class="close-arrow js-close-arrow icon"></div>
        <div class="down-main-top clear">
            <h2>下载文件格式</h2>
		</div>
        <?php if ($_smarty_tpl->tpl_vars['miHui_detail']->value) {?>
		  <div class="overdue-warming">若您喜欢这个图案，请于2019年8月1日前下载</div>
        <?php }?>
        <table class="down-table js-down-table " width="100%">
        	<thead>
				<tr>
					<th width="25%">格式</th>
					<th width="25%">尺寸</th>
					<th width="25%">文件大小</th>
					<th width="25%">点击下载</th>
				</tr>
			</thead>
			<tbody data-downtype="">		
				<tr>
					<td>.jpg</td>
					<td class="img-shape">尺寸</td>
					<td class="img-size">文件大小</td>
					<td><a class="download-btn js-download-btn" data-bp="">下载</a></td>
				</tr>
			</tbody>
        </table>
    </div>
	<?php }?>
    
</div>



        
        <?php /*  Call merged included template "footer.html" */
echo $_smarty_tpl->getInlineSubTemplate("footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '6039719095f30a98bd67e02_56968634', 'content_5f30a98bd67565_85804873');
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
$_smarty_tpl->properties['nocache_hash'] = '1867806445f30a98bc0de94_88474289';
?>

<?php echo '<script'; ?>
 type="text/javascript">
	var pattern_detail_back_obj={
		col_user_type:'<?php echo $_smarty_tpl->tpl_vars['col_user_type']->value;?>
'
	};
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo STATIC_URL3;?>
/global/js/pattern/detail.js?<?php echo @constant('STATIC_CHANGE_TIME');?>
" type="text/javascript" charset="utf-8"><?php echo '</script'; ?>
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
/*%%SmartyHeaderCode:15260562105f30a98bce7101_22760963%%*/
if ($_valid && !is_callable('content_5f30a98bce68e3_38541628')) {
function content_5f30a98bce68e3_38541628 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '15260562105f30a98bce7101_22760963';
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
/*/%%SmartyNocache:15260562105f30a98bce7101_22760963%%*/
}
}
?><?php
/*%%SmartyHeaderCode:6039719095f30a98bd67e02_56968634%%*/
if ($_valid && !is_callable('content_5f30a98bd67565_85804873')) {
function content_5f30a98bd67565_85804873 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '6039719095f30a98bd67e02_56968634';
?>
<!-- footer start -->
<div class="footer">
    <span>上海逸尚云联信息技术股份有限公司 ©2004-2020 法律顾问：北京中银（上海）律师事务所 <a href="http://www.beian.miit.gov.cn/" target="_blank">沪ICP备06003020号-1</a></span>
</div>
<!-- footer end --><?php
/*/%%SmartyNocache:6039719095f30a98bd67e02_56968634%%*/
}
}
?>