<?php /* Smarty version 3.1.27, created on 2020-07-22 16:36:28
         compiled from "/data/htdocs/pop136_yuntu/views/home.html" */ ?>
<?php
/*%%SmartyHeaderCode:12122056075f17fa8c878ba7_94607811%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e706c53c6ca8a12efe3206b0ee3b24d1f8e41118' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/home.html',
      1 => 1595397799,
      2 => 'file',
    ),
    '4b22eedd05bdc48aa69395e981b7badb50229f97' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/layout.html',
      1 => 1587877444,
      2 => 'file',
    ),
    '877d9a2b47d13653a0dfe91f8bc2586e1277579b' => 
    array (
      0 => '877d9a2b47d13653a0dfe91f8bc2586e1277579b',
      1 => 0,
      2 => 'string',
    ),
    'c270d5b9b9b9fca55e14fe96d756c566613109c6' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/header.html',
      1 => 1595397799,
      2 => 'file',
    ),
    '4cf5ea79485fbae31d314fbd3a0b601b877ef21b' => 
    array (
      0 => '4cf5ea79485fbae31d314fbd3a0b601b877ef21b',
      1 => 0,
      2 => 'string',
    ),
    'a91b5d00af2d271a5ee5bd89fa15b4701643d6db' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/footer.html',
      1 => 1587877444,
      2 => 'file',
    ),
    'e9458bb5c2887edb37da72bcee99804a5b5e120b' => 
    array (
      0 => 'e9458bb5c2887edb37da72bcee99804a5b5e120b',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '12122056075f17fa8c878ba7_94607811',
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
  'unifunc' => 'content_5f17fa8cba19a3_81154155',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5f17fa8cba19a3_81154155')) {
function content_5f17fa8cba19a3_81154155 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '12122056075f17fa8c878ba7_94607811';
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
$_smarty_tpl->properties['nocache_hash'] = '12122056075f17fa8c878ba7_94607811';
?>

	<link rel="stylesheet" type="text/css" href="<?php echo @constant('STATIC_URL1');?>
/global/css/home/home.css?<?php echo @constant('STATIC_CHANGE_TIME');?>
">


    </head>
<body>
    <?php echo $_smarty_tpl->tpl_vars['token']->value;?>

    <div class="view">
        
        <?php /*  Call merged included template "header.html" */
echo $_smarty_tpl->getInlineSubTemplate("header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '1795008895f17fa8c9b5815_86656434', 'content_5f17fa8c9b4b35_51562023');
/*  End of included template "header.html" */?>

        

        <?php
$_smarty_tpl->properties['nocache_hash'] = '12122056075f17fa8c878ba7_94607811';
?>

<div class="content">
	<div class="home-section js-content-section">
		<!-- 智能设计下线提示 -->
		<?php if ($_smarty_tpl->tpl_vars['is_show_tip']->value) {?>
		<div class="intelligent-tips js-intelligent-tips">智能设计模块已下线，若对你的使用带来不便，请联系你的专属小秘书，或拨打4008-210-662<a href="javascript:void(0);" class="js-know-btn">我知道了</a></div>
		<?php }?>
		<!-- 智能设计下线提示 -->
		<div class="section">
			<ul class="product-nav clear js-product-nav">
				<li class="bg3 now-light">
					<a href="/patternlibrary/" title="图案&趋势">
						<div class="txt-div pattern-txt-div">
							<div class="circle-div">
								<div>
									<span class="icon"></span>
									<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/home/icon3.png" alt="icon"></div>
								<span></span>
							</div>
							图案&趋势<br/>Pattern&Trends
							<?php if ($_smarty_tpl->tpl_vars['user_type']->value != "VIP") {?>
							<div class="ordinary-user-div"></div>
							<?php }?>
							<p>查看最新图案趋势报告，预测并了解图案流行<br/>趋势，同时可无限次下载超过27万多张的图案<br/>素材及12万矢量图</p>
						</div>
						<div class="h-bg"></div>
					</a>
				</li>
				<li class="bg2 now-light">
					<a href="/similarpatterns/" title="图搜图">
						<div class="txt-div">
							<div class="circle-div">
								<div>
									<span class="icon"></span>
									<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/home/icon2.png" alt="icon"></div>
								<span></span>
							</div>
							图搜图<br/>Similar Patterns
							<?php if ($_smarty_tpl->tpl_vars['module_power']->value["yuntu:similar"][1] != -1) {?>
							<div class="ordinary-user-div">剩余<span><?php echo $_smarty_tpl->tpl_vars['module_power']->value["yuntu:similar"][1];?>
</span>次体验机会</div>
							<?php }?>
							<p>上传的本地图案，或在图案库选取的图案，<br/>都能快速找到相匹配的图案</p>
						</div>
						<div class="h-bg"></div>
					</a>
				</li>
				<li class="bg1 now-light">
					<a href="/virtualtryon/virtualspl/" title="实景模拟">
						<div class="txt-div">
							<div class="circle-div">
								<div>
									<span class="icon"></span>
									<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/home/icon1.png" alt="icon"></div>
								<span></span>
							</div>
							实景模拟<br/>All Occasion Styling
							<?php if ($_smarty_tpl->tpl_vars['module_power']->value["yuntu:virtualtry"][1] != -1) {?>
							<div class="ordinary-user-div">剩余<span><?php echo $_smarty_tpl->tpl_vars['module_power']->value["yuntu:virtualtry"][1];?>
</span>次体验机会</div>
							<?php }?>
							<p>通过2D贴图方式，模拟图案在实物生活场景中<br/>的效果，支持花型尺寸，循环等操作</p>
						</div>
						<div class="h-bg"></div>
					</a>
				</li>
				<li class="bg0 now-light" style="margin-right: 0;">
					<a href="/simulate3d/" title="3D模拟成品">
						<div class="txt-div pattern-txt-div">
							<div class="circle-div">
								<div>
									<span class="icon"></span>
									<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/home/fin-cion.png" alt="icon">
								</div>
								<span></span>
							</div>
							3D模拟成品<br/>Pattern Simulation
							<?php if ($_smarty_tpl->tpl_vars['user_type']->value != "VIP") {?>
							<div class="ordinary-user-div"></div>
							<?php }?>
							<p>通过3D模型看图案花型面料在实物上的效果，<br/>支持更换主料辅料</p>
						</div>
						<div class="h-bg"></div>
					</a>
					<!-- hover提示 -->
					<?php if (!in_array(1,$_smarty_tpl->tpl_vars['templete_2d_sites']->value) && !empty($_smarty_tpl->tpl_vars['templete_2d_sites']->value)) {?>
						<div class="ord-user">
							<!-- 非服装模板的历史用户 -->
							<div class="old-user">
								<div class="old-user-btn">功能升级中，敬请期待</div>
							</div>
						</div>
					<?php } else { ?>
						<?php if (empty($_smarty_tpl->tpl_vars['lastPowerApply3D']->value)) {?>
							<?php if ($_smarty_tpl->tpl_vars['user_type']->value != "VIP") {?>
								<!-- 普通用户提示 -->
								<div class="ord-user">
									<div class="user">
										<!-- <div class="video-btn js-video-btn">观看3D模拟成品视频</div> -->
										<div class="trial-btn" style="margin-top: 210px;">
											<span>申请免费试用</span>
											<div class="qq-box">
												<span>客服热线<br>4008-210-662</span>
												<div class="qq-btn js-contact-qq-btn">QQ咨询</div>
											</div>
										</div>
									</div>
								</div>
							<?php } else { ?>
								<!-- 服装模板的VIP用户申请免费试用 -->
								<div class="ord-user">
									<div class="trial">
										<div class="tri-btn js-vip-btn">申请免费试用</div>
									</div>
								</div>
							<?php }?>
						<?php } else { ?>
							<?php if ($_smarty_tpl->tpl_vars['lastPowerApply3D']->value['isTrial'] == 1) {?>
								<?php if (isset($_smarty_tpl->tpl_vars['lastPowerApply3D']->value['dEndTime']) && strtotime($_smarty_tpl->tpl_vars['lastPowerApply3D']->value['dEndTime']) > time()) {?>
									<div class="ord-user vip-action">
										<a href="/simulate3d/" title="3D模拟成品" id="trial"></a>
										<!-- 服装模板的VIP用户免费试用到期时间 -->
										<div class="vip-btn js-trial-btn">升级为正式VIP
											<span>剩余试用时间：<?php echo ceil((strtotime($_smarty_tpl->tpl_vars['lastPowerApply3D']->value['dEndTime'])-time())/86400);?>
天</span>
										</div>
									</div>
								<?php } else { ?>
									<!-- 试用到期 -->
									<div class="ord-user">
										<div class="expire" style="display: block;">
											<div class="expire-btn js-trial-btn">升级为正式VIP
												<span>试用已过期</span>
											</div>
										</div>
									</div>
								<?php }?>
							<?php }?>
						<?php }?>
					<?php }?>
				</li>
			</ul>
			<!-- 开启vip -->
			<?php if ($_smarty_tpl->tpl_vars['user_type']->value != "VIP") {?>
			<div class="upgrade-vip">
				<h3>开启云图无限使用模式</h3>
				<a href="/topic/enterprise_service/" title="查看详情" target="_blank">咨询了解企业VIP特权</a>
			</div>
			<?php }?>
			<!-- 蒙层 -->
			<div class="mongolia" style="display: none;"></div>
			<!-- 开通VIP弹层 -->
			<div class="open-vip js-open-vip" style="display: none;">
				<div class="box-head">开通试用</div>
				<div class="box-dec">此功能为新增付费功能，您是VIP会员，现申请可限时免费体验<?php echo $_smarty_tpl->tpl_vars['canTrailTime3D']->value;?>
，<br/>后续仍需使用，可以联系您的专属客服！</div>
				<div class="box-btn">
					<span class="cancel js-close">取消</span>
					<span class="confirm js-confirm">确认开通</span>
				</div>
			</div>
			<!-- 开通成功 -->
			<div class="vip-success js-vip-success" style="display: none;">
				<img src="<?php echo @constant('STATIC_URL2');?>
/global/images/home/success.png"/>
				<span>开通成功</span>
			</div>
			<!-- 升级为正式VIP -->
			<div class="upg-vip js-upg-vip" style="display: none;">
				<div class="upg-head">升级为正式VIP</div>
				<div class="upg-dec">为保证您的专属客服能够以最快的速度联系到您，请您如实填写信息</div>
				<div class="upg-tab">
					<div class="name">
						<span><i>*</i>姓名</span>
						<input type="text" id="upg_name"/>
						<label>请输入您的姓名</label>
					</div>
					<div class="phone">
						<span><i>*</i>手机号</span>
						<input type="text" id="upg_phone" maxlength="11"/>
						<label>请输入您的手机号</label>
					</div>
				</div>
				<div class="upg-btn js-upg-btn">提交</div>
				<div class="close js-close"></div>
			</div>
			<!-- 推广视频弹层 -->
			<div class="video-mongolia" style="display: none;"></div>
			<div class="video-layer" style="display: none;">
				<div class="video-main">
					<!-- <video src="/global/images/home/cesi.mp4" controls="controls" width="1080"></video> -->
				</div>
				<div class="close js-close"></div>
				<div class="video-trial">
					<span class="js-trial-btn">申请免费试用</span>
					<div class="layer">
						<span>客服热线<br/>4008-210-662</span>
						<div class="qq-btn js-contact-qq-btn">QQ咨询</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


        
        <?php /*  Call merged included template "footer.html" */
echo $_smarty_tpl->getInlineSubTemplate("footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '11491063915f17fa8caf48c8_19400408', 'content_5f17fa8caf3e17_04708714');
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
$_smarty_tpl->properties['nocache_hash'] = '12122056075f17fa8c878ba7_94607811';
?>

<?php echo '<script'; ?>
 type="text/javascript" charset="utf-8">
	require(["jquery","general","msg"],function(jquery,general,msg){
		$(function(){
			var def={
				content_def_height:0,
				loading_ele:$(".js-loading-div"),                                                                   //加载等待
				bg_ele:$(".js-bg-div")                                                                              //加载等待
			};
			def.content_def_height=$(".js-content-section").height();
			setSty(def.content_def_height);

			def.loading_ele.fadeOut(200);
			def.bg_ele.fadeOut(400);
			$('.js-close').click(function(){
				$('.mongolia').hide();
				$('.js-open-vip').hide();
				$('.js-upg-vip').hide();
				$('.video-mongolia').hide();
				$('.video-layer').hide();
			});

			$(".js-product-nav>li").on("mouseenter",function(){
				$(this).siblings("li").removeClass("now-light");
			}).on("mouseleave",function(){
				$(".js-product-nav>li").addClass("now-light");
			});
			$('.js-video-btn').click(function(){
				$('.video-mongolia').show();
				$('.video-layer').show();
			})
			$('.js-vip-btn').click(function(){
				$('.js-open-vip').show();
				$('.mongolia').show();
			})
			$('.js-confirm').click(function(){
				general.fn.subAjax({
					url:"/simulate3d/opentrial",
					type:"get",
					ctp:"application/x-www-form-urlencoded",
					success:function(data){
						if(data.code=="0"){
							$('.js-open-vip').hide();
							$('.js-vip-success').show();
							setTimeout(function() {
								$('.js-vip-success').hide();
								$('.mongolia').hide();
								window.location.reload();
							}, 1200);
						}
					},
					error:function(data){
						
					}
				});
			})
			$('.js-trial-btn').click(function(){
				$('.js-upg-vip').show();
				$('.mongolia').show();
				$('.video-mongolia').hide();
				$('.video-layer').hide();
			})
			var sta_name,sta_phone=true;
			$('#upg_name').blur(function(){
				var val=$(this).val();
				if(val==''){
					sta_name=true;
					$(this).next().show();
				}else{
					sta_name=false;
					$(this).next().hide();
				}
			});
			$('#upg_phone').blur(function(){
				var val=$(this).val();
				if(val==''){
					sta_phone=true;
					$(this).next().show();
				}else{
					sta_phone=false;
					$(this).next().hide();
				}
			});
			$('.js-upg-btn').click(function(){
				var upg_name=$('#upg_name').val();
				var upg_phone=$('#upg_phone').val();
				if(!sta_name && !sta_phone){
					submitFun(upg_name,upg_phone);
				}
			})
			function submitFun(real_name,cellphone){
				real_name = encodeURIComponent(real_name);
				general.fn.subAjax({
					url:"/simulate3d/applytrial?real_name="+real_name+"&cellphone="+cellphone,
					type:"get",
					ctp:"application/x-www-form-urlencoded",
					success:function(data){
						if(data.code=="0"){
							msg.msg({ "txt":"提交成功" },1200)
							$('.js-upg-vip').hide();
							$('.mongolia').hide();
						}
					},
					error:function(data){
						
					}
				});
			}
			

			$(window).on('resize',function(){
				general.fn.throttle(setSty,null,[def.content_def_height]);
			});
			function setSty(nh){
				var wh=document.documentElement.clientHeight || document.body.clientHeight;
				if(wh>nh+159+256){
					$(".js-content-section").height(wh-159-256+"px");
				}
			};
			
			// 只能设计下线
			$(".js-know-btn").on("click", function() {
				$.ajax({
					url:'/recommend/save_show_tip_user/?'+Math.random(),
					type:'GET',
					success:function(){
						$(".js-intelligent-tips").hide();
					}
				});
				
			})
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
/*%%SmartyHeaderCode:1795008895f17fa8c9b5815_86656434%%*/
if ($_valid && !is_callable('content_5f17fa8c9b4b35_51562023')) {
function content_5f17fa8c9b4b35_51562023 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '1795008895f17fa8c9b5815_86656434';
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
/*/%%SmartyNocache:1795008895f17fa8c9b5815_86656434%%*/
}
}
?><?php
/*%%SmartyHeaderCode:11491063915f17fa8caf48c8_19400408%%*/
if ($_valid && !is_callable('content_5f17fa8caf3e17_04708714')) {
function content_5f17fa8caf3e17_04708714 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '11491063915f17fa8caf48c8_19400408';
?>
<!-- footer start -->
<div class="footer">
    <span>上海逸尚云联信息技术股份有限公司 ©2004-2020 法律顾问：北京中银（上海）律师事务所 <a href="http://www.beian.miit.gov.cn/" target="_blank">沪ICP备06003020号-1</a></span>
</div>
<!-- footer end --><?php
/*/%%SmartyNocache:11491063915f17fa8caf48c8_19400408%%*/
}
}
?>