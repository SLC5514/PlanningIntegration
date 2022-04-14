<?php /* Smarty version 3.1.27, created on 2020-07-30 10:11:22
         compiled from "/data/htdocs/popfashion2016/views/fashion_home.html" */ ?>
<?php
/*%%SmartyHeaderCode:8898130275f222c4a01df38_45222199%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9f52cf8fd0c2c763338113e0ee68912244a5c477' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/fashion_home.html',
      1 => 1591777579,
      2 => 'file',
    ),
    '186731afe75f9278a5feaa0172e7d2a4d76f421e' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/layout.html',
      1 => 1592302491,
      2 => 'file',
    ),
    '0e46508cd45a4877a5b20691a6d8ce02cdd91216' => 
    array (
      0 => '0e46508cd45a4877a5b20691a6d8ce02cdd91216',
      1 => 0,
      2 => 'string',
    ),
    '3e3e8eef1d499fd1205c41d1ede66dff0805dcc5' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/header.html',
      1 => 1593495255,
      2 => 'file',
    ),
    '475a84aba3e7bfb15407e431ee353e63b6657b55' => 
    array (
      0 => '475a84aba3e7bfb15407e431ee353e63b6657b55',
      1 => 0,
      2 => 'string',
    ),
    'a15220a915805af59fb1e4856ea1182b312d537c' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/footer.html',
      1 => 1591777580,
      2 => 'file',
    ),
    'df9f2102c3779411c38baab9f90d5d1db7c01711' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/common/nav_fixed_box.html',
      1 => 1592302199,
      2 => 'file',
    ),
    '97fa21f6142ab9fbaa2ff83195ddcac033c6b823' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/common/recommend-layer-box.html',
      1 => 1591777580,
      2 => 'file',
    ),
    '7e555d1592ce14d18ac21b5cc540e7ecb5571ddd' => 
    array (
      0 => '7e555d1592ce14d18ac21b5cc540e7ecb5571ddd',
      1 => 0,
      2 => 'string',
    ),
    '5b8d593fe8ba558336cddeb6ed09b25a15a38854' => 
    array (
      0 => '5b8d593fe8ba558336cddeb6ed09b25a15a38854',
      1 => 0,
      2 => 'string',
    ),
    '2dd0cd316c1945627526c15d101131b164f5c164' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/common/common_script.html',
      1 => 1587872789,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8898130275f222c4a01df38_45222199',
  'variables' => 
  array (
    'header_token' => 0,
    'title' => 0,
    'keywords' => 0,
    'description' => 0,
    'account' => 0,
    'statistics_token' => 0,
    'P_UserId' => 0,
    'P_UserType' => 0,
    'P_AccountType' => 0,
    'P_AccountId' => 0,
    'P_Collect' => 0,
    'is_relate' => 0,
    'columnId' => 0,
    'brandAllStaticTime' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5f222c4d02d8b1_31154439',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5f222c4d02d8b1_31154439')) {
function content_5f222c4d02d8b1_31154439 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '8898130275f222c4a01df38_45222199';
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="360-site-verification" content="5f7a09c76f4dbe80283ce1d9b27040e3" />
    <meta name="pop-verification" content="<?php echo $_smarty_tpl->tpl_vars['header_token']->value;?>
">
	<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
	<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
"/>
	<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
"/>
    <meta name="robots" content="all"/>
	<meta http-equiv="pragma" name="" content="no-cache"/>
	<meta http-equiv="cache-control" name="" content="no-cache"/>

	
    <link rel="canonical" href="//<?php echo $_SERVER['HTTP_HOST'];
echo $_SERVER['REQUEST_URI'];?>
"/>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL2;?>
/global/css/reset.css?<?php echo STATIC_CHANGE_TIME;?>
">
    <link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL3;?>
/global/css/common.css?<?php echo STATIC_CHANGE_TIME;?>
">
    <link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL3;?>
/global/css/common/new_common.css?<?php echo STATIC_CHANGE_TIME;?>
">
    <link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL3;?>
/global/css/lib/Jcrop/jquery.Jcrop.min.css?<?php echo STATIC_CHANGE_TIME;?>
">
	<link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL2;?>
/global/css/global.css?<?php echo STATIC_CHANGE_TIME;?>
">
	<link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL2;?>
/global/css/perfect-scrollbar.css?<?php echo STATIC_CHANGE_TIME;?>
">
    <link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL3;?>
/global/css/home.css?<?php echo STATIC_CHANGE_TIME;?>
">
    <link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL2;?>
/global/css/common/drag.css?<?php echo STATIC_CHANGE_TIME;?>
">
    <link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL2;?>
/global/css/user/sso_alert.css?<?php echo STATIC_CHANGE_TIME;?>
">
    <link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL2;?>
/global/css/jquery.mCustomScrollbar.css?<?php echo STATIC_CHANGE_TIME;?>
">
    <?php
$_smarty_tpl->properties['nocache_hash'] = '8898130275f222c4a01df38_45222199';
?>

<link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL2;?>
/global/css/home/fashion-home.css?<?php echo STATIC_CHANGE_TIME;?>
">
<style>
    .ui-helper-hidden-accessible{
        display: none;
    }
</style>

</head>
<body>

<!-- 单点登陆弹窗 -->
<div class="sso-box js-sso-box">
    <div class="sso-bg"></div>
    <div class="sso-block">
        <div class="sso-close-parent">
            <i class="sso-close js-sso-close"></i>
        </div>
        <div class="sso-con">
            <div class="sso-header">
                <div class="sso-h2 js-sso-h1">
                    <div class="sso-t2">点击头像快速登录</div>
                    <p>发现您已有账号在本地登录，可选择下面账号直接登录</p>
                </div>
            </div>
            <div class="sso-content">
                <div class="sso-c2 js-sso-c2">
                    <ul class="sso-user-list js-sso-user-list">
                        <!-- <li>
                            <label><img src="" alt=""></label>
                            <span>用户名：<span>17600747652</span></span>
                        </li>-->
                    </ul>
                    <div class="sso-scrollbar js-sso-scrollbar">
                        <div></div>
                    </div>
                    <div class="sso-other js-sso-other-login">
                        其他方式登录
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="sso-error">你的账号已退出，请重新登录！</div>
</div>
<!-- 切图弹层 -->
<div class="file-layer js-file-layer">
    <div class="layer-head">请框选图片主体</div>
    <div class="layer-img js-layer-img">

    </div>
    <img src="" style="display: none;">
    <div class="layer-bottom">
        <span class="crop-cancel">取消</span>
        <span class="crop-save">确定</span>
    </div>
</div>
<!-- 切图蒙层 -->
<div class="files-mongolia"></div>
<input type="hidden" id="cropData"/>
<input type="file" id="uploadImg"/>
<!-- 上传图片end -->

<!-- 登录弹层 -->
<div class="pop_loginbg stu">
    <input type="password" style="display: none;"/>
    <div class="loginClose"></div>
    <div class="login-title">
        <span>欢迎登录POP趋势资讯网</span>
    </div>
    <div class="login-main">
        <div class="loginClass js-loginClass" style="margin-bottom: 0px;">
            <input type="hidden" id='login-type1'/>
            <span class="action" data-type='1'><label><i></i>用户名登录</label><i></i></span>
            <span data-type='2'><label><i></i>手机号登录</label></span>
            <!-- <span data-type='3'>手机号验证码登录</span> -->
        </div>
        <ul style="position: relative;">
            <p class="errorbox10" style="top:0;"></p>
            <li>
                <input type="text" id="account1" placeholder="请输入用户名"/>
            </li>
            <li style="position: relative;">
                <input type="password" id="password1" placeholder="请输入登录密码"/>
                <span class='code-btn js-code-btn'>获取验证码</span>
                <!-- <em id="pwd-em1" class="pwd-display"></em> -->
            </li>
            <li class="l_drag js-slip-code" style="padding-top: 17px;border: none;position: relative;display: none;height: 50px;line-height: 50px;">
                <div class="code_btn js-click-btn" data-cla=".js-puzzle-drag-content4" data-stu="1">点击按钮完成图形验证</div>
                <div class="puzzle-drag-wraper js-drag-show" style="display: none;">
                    <s class="drag-s"></s>
                    <t class="drag-t"></t>
                    <div class="puzzle-drag-content js-puzzle-drag-content4">
                        <div class="drag-title clearfix">
                            <span>为保证你的账户安全，请先完成图形验证</span>
                            <span class="close js-close"></span>
                            <span class="change js-change"><i></i>换一换</span>
                        </div>
                        <div class="puzzle-img-box">
                            <div class="puzzle-img js-puzzle-img">
                                <img>
                            </div>
                            <div class="puzzle-lost js-puzzle-lost">
                                <img>
                            </div>
                        </div>
                        <div class="drag-slide-nav">
                            <div class="drag-slide-text">
                                <div class="drag-slide-left"></div>
                                <div class="drag-slide-center">向右滑动完成拼图</div>
                                <div class="drag-slide-right"></div>
                            </div>
                            <div class="drag-slide-bar js-drag-slide-bar">
                                <div class="drag-slide-bar-left"></div>
                                <div class="drag-slide-bar-center"></div>
                                <div class="drag-slide-bar-right"></div>
                            </div>
                            <div class="drag-slide-btn js-drag-slide-btn"></div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <label class="check-free-login stu">
            <input type="checkbox" name="KeepPassword1" value="1" class="js-free-login1">
            30天内免登录
        </label>
        <div class="login-btn js-login-btn">立即登录</div>
        <div class="forget-pwd clearfix js-forget-pwd">
            <div class="forget-left">
                <a data-url="/member/findpassword/findpassword_2/" style="padding-left: 0px;">忘记密码？</a>
                <a data-url="/member/findaccount/" style="border: none;">忘记用户名？</a>
            </div>
            <div class="forget-right">
                没有账号？
                <a data-url="/member/register/">立即注册</a>
            </div>
        </div>
    </div>
    <div class="login-bottom">
        <span class="contact-login">如需帮助请联系：<i>4008-210-662</i></span>
        <span>
            <span class="js-contact-qq-btn" data-type='1' style="margin-right: 20px;cursor: pointer;"><em></em>在线客服</span>
            <span class="js-message-on" style="cursor: pointer;"><em class="posi"></em>线上留言</span>
        </span>
    </div>
    <div class="chooseUser">
        <div class="chooseHead">
            <span>当前手机号下有两个账号，必须勾选一个登录哦</span>
            <s class="close js-user-close"></s>
        </div>
        <div class="userList clearfix">
            <input type="hidden" id="userId1" value="" />
            <input type="hidden" id="userDef1" value="false" />
            <input type="hidden" id="jsJwt1" value="" />
            <div class="userLi">
                <div class="default" style="border-right:1px solid #f1f1f1;">
                    <!-- <i class="js-userLi i-action"></i> -->
                    <span>管理员账号:</span>
                    <div class="account" style="margin-top: 10px;">
                        <i class="js-default1"></i>
                        <span class="js-userName1">1111137465</span>
                    </div>
                    <div class="js-default-action1" style="margin: 5px 0px 0px 20px;display: none;"><i style="margin-right: 5px;"></i><span>设为默认登录账号</span></div>
                </div>
            </div>
            <div class="userLi">
                <div class="default">
                    <!-- <i class="js-userLi"></i> -->
                    <span>设计师账号:</span>
                    <div class="account" style="margin-top: 10px;">
                        <i class="js-default1"></i>
                        <span class="js-userName1">1111137465</span>
                    </div>
                    <div class="js-default-action1" style="margin: 5px 0px 0px 20px;display: none;"><i style="margin-right: 5px;"></i><span>设为默认登录账号</span></div>
                </div>
            </div>
        </div>
        <div style="width: 392px;" class="confirm-btn js-confirm-btn1">确认</div>
    </div>
</div>

<!-- 上传图片不合格 -->
<div class="err-upload-img-bg js-err-upload-img-bg"></div>
<div class="err-upload-img js-err-upload-img">
	<div class="info">抱歉，当前图片太大啦，换一张图试试吧！（图片不得大于4M或4096px）</div>
	<div>
		<div class="btn confirm js-errupimg-confirm">换一张图</div>
		<div class="btn cancal js-errupimg-cancal">取消</div>
	</div>
</div>

<?php if ($_smarty_tpl->tpl_vars['title']->value) {?>
 <h1 style="display: none;"><?php echo str_replace('-POP服装趋势网','',$_smarty_tpl->tpl_vars['title']->value);?>
</h1>
<?php }?>
    <!-- header start -->
	
	    <?php /*  Call merged included template "header.html" */
echo $_smarty_tpl->getInlineSubTemplate("header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '2513586015f222c4a7d6ef9_28162783', 'content_5f222c4a7d5b47_92982510');
/*  End of included template "header.html" */?>

	
    <!-- header end -->

    <!-- main start -->
    <?php
$_smarty_tpl->properties['nocache_hash'] = '8898130275f222c4a01df38_45222199';
?>

<div class="content">
    <!-- 首页banner -->
    <?php if (!empty($_smarty_tpl->tpl_vars['banner']->value)) {?>
    <div class="fashion-home-banner">
        <div class="con_width">
            <div class="swiper-container-banner">
                <ul class="roundabout-holder js-swiper-wrapper-banner">
                    <?php
$_from = $_smarty_tpl->tpl_vars['banner']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['value']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
$foreach_value_Sav = $_smarty_tpl->tpl_vars['value'];
?>
                    <li class="roundabout-moveable-item">
                        <a href="<?php echo $_smarty_tpl->tpl_vars['value']->value['sUrl'];?>
" target="_blank" title="<?php echo $_smarty_tpl->tpl_vars['value']->value['sTitle'];?>
" data-growing-title="<?php echo $_smarty_tpl->tpl_vars['value']->value['sTitle'];?>
">
                            <img src="<?php echo STATIC_URL1;
echo $_smarty_tpl->tpl_vars['value']->value['sImagePath'];?>
"/>
                        </a>
                    </li>
                    <?php
$_smarty_tpl->tpl_vars['value'] = $foreach_value_Sav;
}
?>
                    <?php if (count($_smarty_tpl->tpl_vars['banner']->value) == 1) {?>
                    <li class="roundabout-moveable-item">
                        <a href="/styles/" target="_blank" data-growing-title="海量款式" title="海量款式">
                            <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/banner1.jpg"/>
                        </a>
                    </li>
                    <li class="roundabout-moveable-item">
                        <a href="/trends/" target="_blank" data-growing-title="趋势流行报告" title="趋势流行报告">
                            <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/banner2.jpg"/>
                        </a>
                    </li>
                    <?php }?>
                    <?php if (count($_smarty_tpl->tpl_vars['banner']->value) == 2) {?>
                    <li class="roundabout-moveable-item">
                        <a href="/styles/" target="_blank" data-growing-title="海量款式" title="海量款式">
                            <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/banner1.jpg"/>
                        </a>
                    </li>
                    <?php }?>
                </ul>
                <a class="swiper-btn-switch swiper-prev js-swiper-prev"></a>
                <a class="swiper-btn-switch swiper-next js-swiper-next"></a>
            </div>
        </div>
    </div>
    <?php }?>
    <!-- 个性化标签 -->
    <div class="interest-tags-contain">
        <div class="con_width">
            <div class="interest-tags-selected">
                <p><?php if ($_smarty_tpl->tpl_vars['like_name']->value) {?>你已选择偏好：<?php echo $_smarty_tpl->tpl_vars['like_name']->value;
} else { ?>选择偏好，定制我的专属推荐页<?php }?></p>
                <a class="set-preference js-myRecommend">偏好设置</a>
                <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 1) {?>
                <a class="open-sub-account" href="/member/associate/">开通子账号，看专属内容</a>
                <?php }?>
            </div>
            <div class="interest-tags-box clearfix">
                <div class="my-exclusive-box fl">
                    <h2>我的</h2>
                    <div class="my-exclusive-link">
                        <a class="is-recommend" href="/patterns/patternRecommend/"><i></i>为我推荐</a>
                        <a class="is-history" href="/member/myHistory/4/"><i></i>浏览历史</a>
                        <a class="is-collect" href="/member/mycollect/"><i></i>收藏</a>
                        <a class="is-workbench" href="/member/workbench/"><i></i>工作室</a>
                        <a class="is-information" href="/member/message/"><i></i>消息</a>
                    </div>
                </div>
                <div class="special-area-box fl">
                    <h2>专区</h2>
                    <ul class="special-area-link js-area-box">
                        <li><a href="javascript:void(0);" data-id="2" data-key="gen"><span>女　装</span><i></i></a></li>
                        <li><a href="javascript:void(0);" data-id="6" data-key="ind" title="毛衫"><span>毛　衫</span><i></i></a></li>
                        <li><a href="javascript:void(0);" data-id="1" data-key="reg"><span>日韩专区</span><i></i></a></li>
                        <li><a href="javascript:void(0);" data-id="1" data-key="gen"><span>男　装</span><i></i></a></li>
                        <li><a href="javascript:void(0);" data-id="7" data-key="ind"><span>牛　仔</span><i></i></a></li>
                        <li><a href="javascript:void(0);" data-id="8" data-key="ind"><span>皮革/皮草</span><i></i></a></li>
                        <li><a href="javascript:void(0);" data-id="5" data-key="gen"><span>童　装</span><i></i></a></li>
                        <li><a href="/fabriczone/"><span>面　料</span><i></i></a></li>
                        <li><a href="javascript:void(0);" data-id="9" data-key="ind"><span>内衣/泳衣</span><i></i></a></li>
                        <li><a href="javascript:void(0);" data-id="11" data-key="ind"><span>运　动</span><i></i></a></li>
                        <li><a href="/brands/"><span>品牌库</span><i></i></a></li>
                        <li><a href="javascript:void(0);" data-id="10" data-key="ind"><span>婚纱/礼服</span><i></i></a></li>
                    </ul>
                </div>
                <div class="follow-box fl">
                    <h2>关注<a class="follow-editor js-follow-editor fr">编辑</a></h2>                    
                    <div class="follow-link js-tags-show-box">
                        <span style="<?php if ($_smarty_tpl->tpl_vars['user_label']->value >= 14) {?>display:none;<?php }?>">+</span>
                        <div class="follow-tags-list js-tags-list-box">
                            <?php
$_from = $_smarty_tpl->tpl_vars['recommend_label']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['value'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['value']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->_loop = true;
$foreach_value_Sav = $_smarty_tpl->tpl_vars['value'];
?>
                            <div>
                                <a href="<?php echo $_smarty_tpl->tpl_vars['value']->value['link'];?>
" data-id="<?php echo $_smarty_tpl->tpl_vars['value']->value['id'];?>
" data-type="<?php echo $_smarty_tpl->tpl_vars['value']->value['label_type'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
"><span><?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
</span></a>
                                <?php if (!$_smarty_tpl->tpl_vars['value']->value['num_sort']) {?><em class="js-remove-tag"></em><?php }?>
                            </div>
                            <?php
$_smarty_tpl->tpl_vars['value'] = $foreach_value_Sav;
}
?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- 款式流行排行榜 -->
    <?php if ($_smarty_tpl->tpl_vars['module']->value['top_show']) {?>
    <div class="top-ranking-style-contain">
        <div class="con_width">
            <div class="ranking-title">
                <h3 class="js-intro-title">热门排行榜<i></i>
                    <div class="ranking-all-intro js-intro-content">
                        <i></i>
                        分析近期的热门款式和图案，发现最受欢迎的色彩、品牌与图案内容
                    </div>
                </h3>
            </div>
            <p class="ranking-eng">TOP RANKING STYLE</p>
            <div class="top-ranking-box js-top-ranking-box clearfix">
                <div class="color-ranking-content">
                    <div class="intro-title clearfix">
                        <h4 class="js-intro-title fl">
                            色彩排行榜<i></i>
                            <div class="ranking-per-intro ranking-per-intro-color js-intro-content">
                                <i></i>
                                分析最新秀场款式使用的颜色，发现热门色彩，可以通过筛选季节与色系，查看更详细的色彩排名
                            </div>
                        </h4>
                        <div class="label-filter season-filter js-labels-filter js-season-label-filter fl">
                            <span data-id="<?php echo $_smarty_tpl->tpl_vars['color_sea']->value[0]['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['color_sea']->value[0]['name'];?>
<i></i></span>
                            <div class="label-filter-downlist js-season-label-downlist">
                                <div class="label-filter-content contentHolder">
                                    <?php
$_from = $_smarty_tpl->tpl_vars['color_sea']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                    <a data-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a>
                                    <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                </div>
                            </div>
                        </div>
                        <div class="more-ranking-color fr"><i></i>更多色彩数据分析，请点<a href="/smarttrends/colordata/" target="_blank">查看更多<i></i></a></div>                
                    </div>
                    <div class="color-ranking-data-box clearfix">
                        <div class="ranking-all-box">
                            <div class="ranking-all-color js-ranking-all-color">
                                
                            </div>
                            <p>点击色块切换色系</p>
                        </div>
                        <div class="ranking-top-color js-ranking-top-color">
                            <ul>
                            </ul>
                        </div>
                        <div class="top-data-null js-top-data-color">
                            <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/null.png">
                            <div>该选项下暂无数据，试试其他筛选项吧</div>
                        </div>
                        <div class="loading-top-data js-loading-color-data">
                            <img src="<?php echo STATIC_URL1;?>
/global/images/styles_page/loading.gif"/>
                        </div>
                    </div>
                </div>
                <div class="brand-pattern-ranking-content">
                    <div class="ranking-content js-ranking-content-iBrand">
                        <div class="intro-title clearfix">
                            <h4 class="js-intro-title fl">品牌排行榜（近一个月）<i></i>
                                <div class="ranking-per-intro js-intro-content">
                                    <i></i>
                                    品牌热度与款式下载量相关，通过品牌热度可以发现近期最受设计师欢迎的品牌；通过筛选，可以查看对应性别的品牌排名
                                </div>
                            </h4>
                            <div class="label-filter js-labels-filter js-brand-label-filter fr">
                                <span data-id="<?php echo $_smarty_tpl->tpl_vars['selected_sex']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['all_sex']->value[$_smarty_tpl->tpl_vars['selected_sex']->value];?>
<i></i></span>
                                <div class="label-filter-downlist js-label-filter-downlist">
                                    <div class="label-filter-content contentHolder">
                                        <?php
$_from = $_smarty_tpl->tpl_vars['all_sex']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['id'] = new Smarty_Variable;
foreach ($_from as $_smarty_tpl->tpl_vars['id']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                        <a data-id="<?php echo $_smarty_tpl->tpl_vars['id']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value;?>
</a>
                                        <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ranking-data-box brand-ranking-data-box">
                            <ul class="js-brand-ranking-data">
                            </ul>
                            <div class="top-data-null js-top-data-brand">
                                <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/null.png">
                                <div>该选项下暂无数据，试试其他筛选项吧</div>
                            </div>
                            <div class="loading-top-data js-loading-brand-data">
                                <img src="<?php echo STATIC_URL1;?>
/global/images/styles_page/loading.gif"/>
                            </div>
                        </div>
                    </div>
                    <div class="ranking-content js-ranking-content-sPattern">
                        <div class="intro-title clearfix">
                            <h4 class="js-intro-title fl">图案排行榜（近一个月）<i></i>
                                <div class="ranking-per-intro js-intro-content">
                                    <i></i>
                                    图案热度与图案下载量相关，通过图案热度可以发现近期最受设计师喜欢的图案内容；通过筛选，可以查看对应类型的图案排名
                                </div>
                            </h4>
                            <div class="label-filter js-labels-filter js-pattern-label-filter fr">
                                <span data-id="<?php echo $_smarty_tpl->tpl_vars['first_pattern']->value[0]['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['first_pattern']->value[0]['name'];?>
<i></i></span>
                                <div class="label-filter-downlist js-pattern-label-downlist">
                                    <div class="label-filter-content contentHolder">
                                        <?php
$_from = $_smarty_tpl->tpl_vars['first_pattern']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                        <a data-id="<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a>
                                        <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ranking-data-box pattern-ranking-data-box">
                            <ul class="js-pattern-ranking-data">
                            </ul>
                            <div class="top-data-null js-top-data-sPattern">
                                <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/null.png">
                                <div>该选项下暂无数据，试试其他筛选项吧</div>
                            </div>
                            <div class="loading-top-data js-loading-pattern-data">
                                <img src="<?php echo STATIC_URL1;?>
/global/images/styles_page/loading.gif"/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php }?>
    <!-- 报告 -->
    <div class="reports-infor-contain">
        <div class="con_width clearfix">
            <!--  -->
            <div class="latest-report-box fl">
                <div class="latest-report-top">
                    <div class="latest-report-title">
                        <h3>最新报告</h3>
                        <p>LATEST REPORTS</p>
                    </div>
                    <div class="latest-report-jump">
                        <a class="left-jump js-left-jump"></a>
                        <span><em class="js-jump-num">1</em>/3</span>
                        <a class="right-jump js-right-jump"></a>
                    </div>
                </div>
                <div class="report-list-box js-report-list-box">
                    <ul class="clearfix">                        
                    </ul>
                    <ul class="clearfix">                        
                    </ul>
                    <ul class="clearfix">                       
                    </ul>


                </div>
            </div>
            <!-- 最热报告 -->
            <div class="hot-reports-box fr">
                <h3>热门报告</h3>
                <p>HOT REPORTS</p>
                <div class="hot-reports-content">
                    <em class="line-top"></em>
                    <ul class="js-hot-reports-list">
                        
                    </ul>
                    <em class="line-top line-bottom"></em>
                </div>
            </div>
        </div>
    </div>

    <!-- 款式推荐 -->
    <div class="style-recommend-contain">
        <div class="con_width">
            <div class="style-r-top clearfix">
                <div class="style-r-title fl">
                    <div class="recommend-title">
                        <h3 class="js-intro-title">款式推荐<i></i>
                            <div class="recommend-all-intro js-intro-content">
                                <i></i>
                                根据你账号的历史行为推荐合适的款式，为了提高推荐的准确率，建议每个账号只由一个人使用（偷偷告诉你，每个人都可以开通属于自己的设计师账号哦）
                            </div>
                        </h3>
                        <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 1) {?>
                        <a class="open-sub-account" href="/member/associate/">开通子账号，看专属内容</a>
                        <?php }?>
                    </div> 
                    <p>RECOMMEND</p>
                </div>
                <div class="style-r-more fr">
                    <a href="/patterns/patternRecommend/?col=4" target="_blank"><i></i>查看更多</a>
                </div>
            </div>
            <div class="style-lists-contain js-style-lists-contain js-datagrand-click">
                <ul class="clearfix lazyload">                    
                </ul>
                <a href="/patterns/patternRecommend/?col=4" class="style-more-bottom" target="_blank"><span>查看更多<i></i></span><em></em></a>
            </div>
        </div>        
    </div>
    <!-- 图案推荐 -->
    <div class="pattern-recommend-contain">
        <div class="con_width">
            <div class="pattern-r-top clearfix">
                <div class="pattern-r-title fl">
                    <div class="recommend-title">
                        <h3 class="js-intro-title">图案推荐<i></i>
                            <div class="recommend-all-intro js-intro-content">
                                <i></i>
                                根据你账号的历史行为推荐合适的图案，为了提高推荐的准确率，建议每个账号只由一个人使用（偷偷告诉你，每个人都可以开通属于自己的设计师账号哦）
                            </div>
                        </h3>
                        <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 1) {?>
                        <a class="open-sub-account" href="/member/associate/">开通子账号，看专属内容</a>
                        <?php }?>
                    </div>                    
                    <p>PATTERN RECOMMEND</p>
                </div>
                <div class="pattern-r-more fr">
                    <a href="/patterns/patternRecommend/?col=82" target="_blank"><i></i>查看更多</a>
                </div>
            </div>
            <div class="pattern-lists-contain js-pattern-lists-contain js-datagrand-click">
                <ul class="clearfix lazyload">                    
                </ul>
                <a href="/patterns/patternRecommend/?col=82" class="pattern-more-bottom" target="_blank"><span>查看更多<i></i></span><em></em></a>
            </div>
        </div>        
    </div>

    <!-- 最新T台 -->
    <?php if ($_smarty_tpl->tpl_vars['module']->value['t_show_relate']) {?>
    <div class="runway-recommend-contain">
        <div class="con_width">
            <div class="runway-r-top clearfix">
                <div class="runway-r-title fl">
                    <h3>最新T台</h3>
                    <p>CATWALK</p>
                </div>
                <div class="runway-r-more fr">
                    <a href="/runways/" target="_blank"><i></i>查看更多</a>
                </div>
            </div>
            <div class="runway-lists-contain">
                <ul class="clearfix js-runway-lists lazyload">
                </ul>
            </div>
        </div>
    </div>
    <?php }?>

    <!-- 广告大片 -->
    <?php if ($_smarty_tpl->tpl_vars['module']->value['advert_relate']) {?>
    <div class="lookbook-recommend-contain">
        <div class="con_width">
            <div class="lookbook-r-top clearfix">
                <div class="lookbook-r-title fl">
                    <h3>广告大片</h3>
                    <p>LOOKBOOK</p>
                </div>
                <div class="lookbook-r-more fr">
                    <a href="/books/lookbook/" target="_blank"><i></i>查看更多</a>
                </div>
            </div>
            <div class="lookbook-lists-contain">
                <ul class="clearfix js-lookbook-lists">
                </ul>
            </div>
        </div>
    </div>
    <?php }?>

    <!-- 专属看板 -->
    <?php if ($_smarty_tpl->tpl_vars['module']->value['look_show']) {?>
    <div class="my-board-contain">
        <div class="con_width">
            <div class="board-content">
                <div class="my-board-title">
                    <h3 class="js-intro-title">专属看板<i></i>
                        <div class="board-all-intro js-intro-content">
                            <i></i>
                            在浏览款式时，可以保存喜欢的品牌、色彩等搜索条件，下次就能直接从专属看板进入对应的款式页面了哦
                        </div>
                    </h3>                    
                </div>
                <p class="my-board-eng">MY BOARD</p>
                <div class="borad-search-box">
                    <div class="borad-search-list js-borad-search-list">
                    </div>
                    <span class="js-save-search-null"><img src="<?php echo STATIC_URL3;?>
/global/images/fashion_home/board_add1.png"/></span>
                </div>                
            </div>
        </div>
    </div>
    <?php }?>

    <!-- 偏好选择弹窗 -->
    <div class="preference-select-bg js-preference-select-bg"></div>
    <div class="preference-select-bg js-myboard-bg"></div>
    <div class="preference-select-bg js-del-label-bg"></div>
    <div class="preference-select-contain js-preference-select-contain">
        <div class="preference-select-close js-preference-select-close"></div>
        <h2><i></i><span>偏好选择</span><i></i></h2>
        <p>选择的类型将影响排行榜和报告模块</p>
        <div class="labels-select-box js-labels-select-box">
            <ul class="gender-label-ul">
                <li data-id="1">
                    <div class="preference-select-label select-label-gender">
                        <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/man_img.png"/>
                        <div class="layer-labels-bg js-layer-labels-bg"><span>男装</span></div>
                        <div class="selected-label-state js-selected-label-state">
                            <div>
                                <i></i>
                                <span>男装</span>
                            </div>                            
                        </div>
                    </div>
                </li>
                <li data-id="2">
                    <div class="preference-select-label select-label-gender">
                        <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/w_img.png"/>
                        <div class="layer-labels-bg js-layer-labels-bg"><span>女装</span></div>
                        <div class="selected-label-state js-selected-label-state">
                            <div>
                                <i></i>
                                <span>女装</span>
                            </div>
                        </div>
                    </div>
                </li>
                <li data-id="5">
                    <div class="preference-select-label select-label-gender">
                        <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/child_img.png"/>
                        <div class="layer-labels-bg js-layer-labels-bg"><span>童装</span></div>
                        <div class="selected-label-state js-selected-label-state">
                            <div>
                                <i></i>
                                <span>童装</span>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="industry-labels-ul1">
                <li data-id="7">
                    <div class="preference-select-label">
                        <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/miuzai_img.png"/>
                        <div class="layer-labels-bg js-layer-labels-bg"><span>牛仔</span></div>
                        <div class="selected-label-state js-selected-label-state">
                            <div>
                                <i></i>
                                <span>牛仔</span>
                            </div>
                        </div>
                    </div>
                </li>
                <li data-id="8">
                    <div class="preference-select-label">
                        <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/picao_img.png"/>
                        <div class="layer-labels-bg js-layer-labels-bg"><span>皮革/皮草</span></div>
                        <div class="selected-label-state js-selected-label-state">
                            <div>
                                <i></i>
                                <span>皮革/皮草</span>
                            </div>
                        </div>
                    </div>
                </li>
                <li data-id="11">
                    <div class="preference-select-label">
                        <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/yundong_img.png"/>
                        <div class="layer-labels-bg js-layer-labels-bg"><span>运动</span></div>
                        <div class="selected-label-state js-selected-label-state">
                            <div>
                                <i></i>
                                <span>运动</span>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="industry-labels-ul2">
                <li data-id="159">
                    <div class="preference-select-label">
                        <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/chengyi_img.png"/>
                        <div class="layer-labels-bg js-layer-labels-bg"><span>成衣</span></div>
                        <div class="selected-label-state js-selected-label-state">
                            <div>
                                <i></i>
                                <span>成衣</span>
                            </div>
                        </div>
                    </div>
                </li>
                <li data-id="11116">
                    <div class="preference-select-label">
                        <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/qunku_img.png"/>
                        <div class="layer-labels-bg js-layer-labels-bg"><span>裙/裤</span></div>
                        <div class="selected-label-state js-selected-label-state">
                            <div>
                                <i></i>
                                <span>裙/裤</span>
                            </div>
                        </div>
                    </div>
                </li>
                <li data-id="10">
                    <div class="preference-select-label">
                        <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/hunsha_img.png"/>
                        <div class="layer-labels-bg js-layer-labels-bg"><span>婚纱/礼服</span></div>
                        <div class="selected-label-state js-selected-label-state">
                            <div>
                                <i></i>
                                <span>婚纱/礼服</span>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <ul class="industry-labels-ul3"> 
                <li data-id="6">
                    <div class="preference-select-label">
                        <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/maoshan_img.png"/>
                        <div class="layer-labels-bg js-layer-labels-bg"><span>毛衫</span></div>
                        <div class="selected-label-state js-selected-label-state">
                            <div>
                                <i></i>
                                <span>毛衫</span>
                            </div>
                        </div>
                    </div>
                </li>
                <li data-id="9">
                    <div class="preference-select-label">
                        <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/jiaju_img.png"/>
                        <div class="layer-labels-bg js-layer-labels-bg"><span>内衣/泳装/<br/>家居服</span></div>
                        <div class="selected-label-state js-selected-label-state">
                            <div>
                                <i></i>
                                <span>内衣/泳装/<br/>家居服</span>
                            </div>
                        </div>
                    </div>
                </li>
                <li data-id="12">
                    <div class="preference-select-label">
                        <img src="<?php echo STATIC_URL1;?>
/global/images/fashion_home/peishi_img.png"/>
                        <div class="layer-labels-bg js-layer-labels-bg"><span>服饰配件</span></div>
                        <div class="selected-label-state js-selected-label-state">
                            <div>
                                <i></i>
                                <span>服饰配件</span>
                            </div>                            
                        </div>
                    </div>
                </li>            
            </ul>
        </div>
    </div>
    <!-- 个性化标签 -->
    <div class="preference-select-bg js-interested-select-bg"></div>
    <div class="interested-select-contain js-interested-select-contain">
        <div class="interested-select-close js-interested-select-close"></div>
        <p>选择你关注的标签</p>
        <div class="interested-select-box js-interested-select-box">
            <ul>
                <li>
                    <div class="clearfix">
                        <label class="fl">品牌</label>
                        <i></i>
                        <div class="fl brand-input">
                            <input type="type" placeholder="选择关注的品牌" class="js-search-brand-labels" />
                            <div class="brand-labels-searchlist js-brand-labels-searchlist"></div>
                        </div>
                    </div>
                    <div class="interested-select-brand"></div>
                </li>
            </ul>
        </div>
        <div class="save-interested-select">
            <a class="cancel-interested-select js-cancel-interested-select">取消</a>
            <a class="confirm-interested-select js-confirm-interested-select">确定</a>
        </div>
        <div class="prompt-layer js-prompt-layer">最多能自主添加14个标签，可删除已选标签再重试</div>
    </div>
    <!-- 是否确认删除个性化标签 -->
    <div class="delete-relate-labels-contain js-delete-relate-contain">
        <p>确认删除该关注吗？</p>
        <div>
            <a class="delete-labels js-delete-labels">删除</a>
            <a class="cancel-delete js-cancel-delete">取消</a>
        </div>
    </div>
    <!-- 添加专属看板 -->
    <div class="add-my-borads js-add-my-borads">
        <div class="close-my-borads js-close-my-borads"></div>
        <p>新增专属看板</p>
        <div class="my-borads-steps clearfix">
            <a>
                <img src="<?php echo STATIC_URL2;?>
/global/images/fashion_home/board1.png?20200526"/>
                <p>1.在查看款式时选择喜欢的搜索条件</p>
            </a>
            <span></span>
            <a>
                <img src="<?php echo STATIC_URL2;?>
/global/images/fashion_home/board2.png?20200526"/>
                <p>2.点击保存搜索</p>
            </a>
            <span></span>
            <a>
                <img src="<?php echo STATIC_URL2;?>
/global/images/fashion_home/board3.png?20200526"/>
                <p>3.通过专属看板查看</p>
            </a>
        </div>
        <div class="jump-search-labels">
            <a class="cancel-labels js-cancel-delete">取消</a>
            <a class="confirm-labels js-find-news">发现款式</a>
        </div>
    </div>
    <!-- 个性化推荐页设置 -->
	<div class="my-recommend-nav js-my-recommend-nav">
		<div class="my-recommend-close js-my-recommend-close"></div>
		<div class="recommend-nav-tab js-recommend-nav-tab">
			<div class="wid_900">
				<a class="s-on">偏好选择<span></span></a>
				<a>模块展示<span></span></a>
				<a>个性化设置<span></span></a>
			</div>			
		</div>
		<div class="wid_900">
            <div class="modular-show-box">
                <div>
                    <p>（选择的偏好将影响数据和报告模块，但款式，图案的推荐跟偏好无关）</p>
                </div>
                <div class="modular-labels-box js-modular-labels-box clearfix">
                    <a data-id="1">男装<i></i></a>
                    <a data-id="7">牛仔<i></i></a>
                    <a data-id="159">成衣<i></i></a>
                    <a data-id="6">毛衫<i></i></a>
                    <a data-id="2">女装<i></i></a>
                    <a data-id="8">皮革/皮草<i></i></a>
                    <a data-id="11116">裤/裙<i></i></a>
                    <a data-id="9">内衣/泳装/家居服<i></i></a>
                    <a data-id="5">童装<i></i></a>
                    <a data-id="11">运动<i></i></a>
                    <a data-id="10">婚纱/礼服<i></i></a>
                    <a data-id="12">服饰配件<i></i></a>
                </div>
                <a class="save-set-btn js-labels-save-set">保存设置</a>
            </div>
            <div class="modular-show-box" style="display: none;">
                <div class="modular-raking-box clearfix">
                    <p class="fl">热门排行榜：是否希望在今日推荐中展示热门排行榜</p>
                    <div class="fr modular-show-btn top-modular-box">
                        <span class="top-show-span"><input type="radio" name="top_show" value="1" checked>展示</span>
                        <span class="top-hide-span"><input type="radio" name="top_show" value="0">隐藏</span>
                    </div>
                </div>
                <div class="modular-runway-box clearfix">
                    <p class="fl">T台：是否希望在今日推荐中展示T台</p>
                    <div class="fr modular-show-btn runway-modular-box">
                        <span class="runway-show-span"><input type="radio" name="t_show" value="1">展示</span>
                        <span class="runway-hide-span"><input type="radio" name="t_show" value="0" checked>隐藏</span>
                    </div>
                </div>
                <div class="modular-advert-box clearfix">
                    <p class="fl">广告大片：是否希望在今日推荐中展示广告大片</p>
                    <div class="fr modular-show-btn advert-modular-box">
                        <span class="advert-show-span"><input type="radio" name="advert_show" value="1">展示</span>
                        <span class="advert-hide-span"><input type="radio" name="advert_show" value="0" checked>隐藏</span>
                    </div>
                </div>
				<div class="modular-board-box clearfix">
					<p class="fl">专属看板：是否希望在今日推荐中展示专属看板</p>
					<div class="fr modular-show-btn look-modular-box">
						<span class="mo-show-span"><input type="radio" name="look_show" value="1">展示</span>
						<span class="mo-hide-span"><input type="radio" name="look_show" value="0" checked>隐藏</span>
					</div>
				</div>				
				<a class="save-set-btn js-modular-save-set">保存设置</a>
			</div>
			<div class="modular-show-box" style="display: none;">
				<div>
					<p>（若开启个性化选项，数据来源将与选择的偏好相关；若关闭个性化选项，数据来源将与选择的偏好无关）</p>
				</div>
				<div class="modular-board-box clearfix">
					<p class="fl">热门排行榜：是否开启热门排行榜的个性化选项</p>
					<div class="fr modular-show-btn relate-modular-box">
						<span class="relate-show-span"><input type="radio" name=" top_relate" value="1" checked>开启</span>
						<span class="relate-hide-span"><input type="radio" name=" top_relate" value="0" >关闭</span>
					</div>
				</div>
				<div class="modular-raking-box clearfix">
					<p class="fl">最新报告：是否开启最新报告的个性化选项</p>
					<div class="fr modular-show-btn new-modular-box">
						<span class="new-show-span"><input type="radio" name="new_report_relate" value="1" checked>开启</span>
						<span class="new-hide-span"><input type="radio" name="new_report_relate" value="0">关闭</span>
					</div>
				</div>
				<div class="modular-raking-box clearfix">
					<p class="fl">热门报告：是否开启热门报告的个性化选项</p>
					<div class="fr modular-show-btn hot-modular-box">
						<span class="hot-show-span"><input type="radio" name="hot_report_relate" value="1" checked>开启</span>
						<span class="hot-hide-span"><input type="radio" name="hot_report_relate" value="0">关闭</span>
					</div>
				</div>
				<a class="save-set-btn js-relate-save-set">保存设置</a>
			</div>
		</div>
	</div>
</div>

    <!-- main end -->

    <!-- footer start -->
	
		<?php /*  Call merged included template "footer.html" */
echo $_smarty_tpl->getInlineSubTemplate("footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '6932055265f222c4c53e123_32658876', 'content_5f222c4c522fc9_80648440');
/*  End of included template "footer.html" */?>

	
    <!-- footer end -->

    <!-- other dom -->
    <?php
$_smarty_tpl->properties['nocache_hash'] = '8898130275f222c4a01df38_45222199';
?>



	<!-- popup start -->
	
    <!-- popup end -->

    <!-- 意见反馈 -->
    <input type="hidden" id="user-name" data-name="<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
">
    <div class="msg-feedback-box js-msg-feedback-box">
        <div class="msg-feedback-con">
            <div class="title">请帮助我们改进产品</div>
            <p class="tips">朋友你好，你对网站有任何建议，都可以在本页面反馈，以帮助我们为你提供更好用的功能。</p>
            <ul>
                <li>
                    <div class="topic">1*. 你有多大可能把<span class="appoint-word">我们这个产品</span>推荐给朋友或同事? 请从0-10分打分。</div>
                    <div class="clearfix info">
                        <div class="fl">极度不可能</div>
                        <div class="fr">极度可能</div>
                    </div>
                    <ul class="clearfix radio-list js-msg-feedback-radio">
                        <li>1</li>
                        <li>2</li>
                        <li>3</li>
                        <li>4</li>
                        <li>5</li>
                        <li>6</li>
                        <li>7</li>
                        <li>8</li>
                        <li>9</li>
                        <li>10</li>
                    </ul>
                </li>
                <li>
                    <div class="topic">2. 你觉得<span class="appoint-word">我们这个产品</span>有哪些打动你的地方?</div>
                    <textarea class="js-msg-feedback-text1" placeholder="觉得好的地方，可以直接说出来哦！"></textarea>
                </li>
                <li>
                    <div class="topic">3. 你觉得<span class="appoint-word">我们这个产品</span>还有哪些地方需要改进?</div>
                    <textarea class="js-msg-feedback-text2" placeholder="觉得不好的地方，也请让我们知道吧！"></textarea>
                </li>
                <li class="btn-box js-msg-feedback-btn-box">
                    <button data-type="0">取消</button>
                    <button data-type="1" class="active">提交</button>
                </li>
            </ul>
        </div>
        <div class="msg-feedback-toast">感谢反馈，我们会努力为你提供更优质、更贴心的服务!</div>
    </div>

    <!-- 共用js -->
    
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL1;?>
/global/js/common/jquery-1.9.1.min.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/fashion/perfect-scrollbar.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
	<!--[if lte IE 8]>
	<?php echo '<script'; ?>
 type="text/javascript">
		var is_lte_ie8=true;
	<?php echo '</script'; ?>
>
    <![endif]-->

    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL2;?>
/global/js/common/jquery.cookie.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL2;?>
/global/js/common/pop-msg-1.2.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL2;?>
/global/js/common/general-1.0.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL2;?>
/global/js/common/select-1.0.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL2;?>
/global/js/fashion/jquery.inputSearch.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL2;?>
/global/js/common/Lazyload.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL2;?>
/global/js/common/jquery-ui.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL2;?>
/global/js/common/jquery.form.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL1;?>
/global/js/common/jquery.mousewheel.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/fashion/jquery.masonry.min.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/layer/layer.js"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL2;?>
/global/js/common/drag-slide.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL2;?>
/global/js/user/login.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL2;?>
/global/js/fashion/jquery.mCustomScrollbar.concat.min.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/lib/Jcrop/jquery.Jcrop.min.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
    
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/fashion/feedback.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/fashion/common.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/common/new_common.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
    
    <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo @constant('STATIC_URL3');?>
/global/js/lib/gt.js?<?php echo @constant('STATIC_CHANGE_TIME');?>
"><?php echo '</script'; ?>
>
    <?php echo '<script'; ?>
>
		// 统计参数token
		var statistics_token = "<?php echo $_smarty_tpl->tpl_vars['statistics_token']->value;?>
";

        var P_UserId = '<?php echo $_smarty_tpl->tpl_vars['P_UserId']->value;?>
';//当前登录用户ID(PS:可能是主ID,也可能是子用户ID)
        var P_UserType = '<?php echo $_smarty_tpl->tpl_vars['P_UserType']->value;?>
';//用户权限类型VIP、游客、还是试用
        var P_AccountType = '<?php echo $_smarty_tpl->tpl_vars['P_AccountType']->value;?>
';//用户主、子类型
        var P_AccountId = '<?php echo $_smarty_tpl->tpl_vars['P_AccountId']->value;?>
';//主账号ID
        var P_Collect = parseInt('<?php echo $_smarty_tpl->tpl_vars['P_Collect']->value;?>
');
        var pid = '<?php echo $_GET['pid'];?>
' || '<?php echo $_COOKIE['pidtt'];?>
';// 渠道代码
        var static_url = '<?php echo STATIC_URL2;?>
';
        var is_relate = '<?php echo $_smarty_tpl->tpl_vars['is_relate']->value;?>
';                  //是否是个性化页面
        var columnId = '<?php echo $_smarty_tpl->tpl_vars['columnId']->value;?>
';
    <?php echo '</script'; ?>
>
    
    

    
    <?php if (in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(4,5))) {?>
    <!-- <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/fashion/qq_layer.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
> -->
    <?php }?>
    

    <?php
$_smarty_tpl->properties['nocache_hash'] = '8898130275f222c4a01df38_45222199';
?>

<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL2;?>
/global/js/lib/swiper/jquery.roundabout.min.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL2;?>
/global/js/lib/swiper/jquery.easing.1.3.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/lib/require.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/home/fashion_home.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
var modules = <?php echo $_smarty_tpl->tpl_vars['power_modules']->value;?>
;
<?php echo '</script'; ?>
>


    
		<input id="brandAllInfo" type="hidden" data-time="<?php echo $_smarty_tpl->tpl_vars['brandAllStaticTime']->value;?>
" data-domain="<?php echo STATIC_URL1;?>
">
		
		
		

		<!-- 注册送好礼 start -->
		<!-- <div class="waikuang" id="qqpromotid" style='display:none;'>
			<div class="waibg">
				<a class="update_img" href="javascript:void(0);"><img alt="pic"></a>
				<a class="close" href="javascript:void(0);"></a>
				<a class="qq_btn js-contact-qq-btn" href="javascript:void(0);"></a>
			</div>
		</div> -->
		<!-- 注册送好礼 end -->

		<?php /*  Call merged included template "common/common_script.html" */
echo $_smarty_tpl->getInlineSubTemplate("common/common_script.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '11041001855f222c4ce3c177_79279370', 'content_5f222c4ce32092_56173392');
/*  End of included template "common/common_script.html" */?>

	
</body>
</html>
<?php }
}
?><?php
/*%%SmartyHeaderCode:2513586015f222c4a7d6ef9_28162783%%*/
if ($_valid && !is_callable('content_5f222c4a7d5b47_92982510')) {
function content_5f222c4a7d5b47_92982510 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '2513586015f222c4a7d6ef9_28162783';
?>
<!-- 导航改版 -->
<div class="new_header new-header-section">
    <?php if (!empty($_smarty_tpl->tpl_vars['columns']->value) && !empty($_smarty_tpl->tpl_vars['columnId']->value)) {?>
    <div class="contentW">
        <div class="new-function-guide1 js-new-function-guide1">
            <img class="search-wid1" src="<?php echo STATIC_URL2;?>
/global/images/guide/search1.png"/>
            <img class="search-wid2" src="<?php echo STATIC_URL2;?>
/global/images/guide/search2.png"/>
            <img class="search-wid3" src="<?php echo STATIC_URL2;?>
/global/images/guide/search3.png"/>
            <div class="">
                <img src="<?php echo STATIC_URL2;?>
/global/images/guide/search_info.png"/>
                <a class="js-step-btn1">下一步</a>
            </div>
        </div>
    </div>
    <?php }?>
    <div class="head-top-static js-head-top clearfix">
        <div class="head-t-content clearfix">
            <div class="head-logo fl js-head-logo">
                <a href="https://www.pop-fashion.com/" rel="nofollow" title="POP服装趋势"><img src="<?php echo STATIC_URL2;?>
/global/images/new_common/nav_logo.png" alt="POP服装趋势"><i class="common-icon"></i></a>
                <div class="allWeb">
                    <ul class="leftlists">
                        <li><a href="https://www.pop-fashion.com/" target="_blank" rel="nofollow" title="服装"><em class="common-icon fashion"></em>服装</a></li>
                        <li><a href="http://www.pop-bags.com/" target="_blank" rel="nofollow" title="箱包"><em class="common-icon bag"></em>箱包</a></li>
                        <li><a href="http://www.pop-shoe.com/" target="_blank" rel="nofollow" title="鞋子"><em class="common-icon shoe"></em>鞋子</a></li>
                        <li><a href="http://www.51shoushi.com/" target="_blank" rel="nofollow" title="首饰"><em class="common-icon dec"></em>首饰</a></li>
                        <li><a href="http://www.91jiafang.com/" target="_blank" rel="nofollow" title="家纺"><em class="common-icon home"></em>家纺</a></li>
                        <li><a href="http://yuntu.pop136.com/" target="_blank" rel="nofollow" title="云图"><em class="common-icon yuntu"></em>云图</a></li>
                        <li><a href="http://www.pop136.com/" target="_blank" rel="nofollow" title="官网"><em class="common-icon guanw"></em>官网</a></li>
                    </ul>
                </div>
            </div>
            <div class="lang-contain js-leftT fl">
                <a class="ch-lang" title="中文"><img src="<?php echo STATIC_URL2;?>
/global/images/new_common/flag.png"/><i class="common-icon"></i></a>
                <div class="lang-list js-lang-list">
                    <a href="https://www.popfashioninfo.com/" target="_blank" title="English"><em></em>English</a>
                </div>
            </div>
            <div class="user-contain fr">
                <?php if ($_smarty_tpl->tpl_vars['account']->value == '' && $_smarty_tpl->tpl_vars['accountShow']->value != 'true') {?>
                <div class="user-box fl">
                    <a href='javascript:void(0);' class='loginLayer' rel="nofollow" title="登录"><span class="lg"></span>登录</a>
                    <i></i>
                    <a class="reg-btn" href="/member/register/" rel="nofollow" title="注册"><span class="rg"></span>注册</a>
                </div>
                <?php } else { ?>
                <?php if ($_smarty_tpl->tpl_vars['pwd_tip']->value) {?>
                <div class="login-password-info js-login-password-info">您已很久没修改密码，为了您的账号安全，请前往<a href="/member/pwd/">修改密码</a><i onclick="$('.js-login-password-info').hide()"></i></div>
                <?php }?>
                <div class="login-user-box fl js-returnLi">
                    <a><em></em><span><?php echo $_smarty_tpl->tpl_vars['account']->value;?>
</span></a>
                    <div class="login-success-section js-forUl <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 1 || $_smarty_tpl->tpl_vars['P_UserType']->value == 2) {?>login-success-vip<?php }?>">
                        <div class="login-suc-head">
                            <div class="login-suc-head-t clearfix">
                                <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 1) {?>
                                <p class="fl"><em><?php echo $_smarty_tpl->tpl_vars['account']->value;?>
</em><span><i class="common-icon"></i>管理员账号</span></p>                                
                                <?php } elseif ($_smarty_tpl->tpl_vars['P_UserType']->value == 2) {?>
                                <p class="fl"><em><?php echo $_smarty_tpl->tpl_vars['account']->value;?>
</em><span><i class="common-icon"></i>设计师账号</span></p>
                                <?php } elseif ($_smarty_tpl->tpl_vars['P_UserType']->value == 3) {?>
                                <p class="fl"><em><?php echo $_smarty_tpl->tpl_vars['account']->value;?>
</em><span>试用账号</span></p>
                                <?php } else { ?>
                                <p class="fl"><em><?php echo $_smarty_tpl->tpl_vars['account']->value;?>
</em><span>普通用户</span></p>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['issetNewMessage']->value) {?>
                                <a class="fl infor-account common-icon" href='/member/message/'><span class="ix"></span></a>
                                <?php }?>
                                <a class="fr signout-account js-signOut">退出</a>
                                <?php if ($_smarty_tpl->tpl_vars['canChangLoginAccount']->value) {?>
                                    <?php if ($_smarty_tpl->tpl_vars['P_AccountType']->value == "main") {?>
                                    <a class="fr switch-account js-switch" data-type="1"><i class="common-icon"></i>切换</a>                                    
                                    <?php } else { ?>
                                    <a class="fr switch-account js-switch" data-type="2"><i class="common-icon"></i>切换</a>
                                    <?php }?>
                                <?php }?>                                
                            </div>
                            <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 1) {?>
                            <p>用VIP主账号，可管理多个子账号哦~<a href="/member/associate/" target="_blank">管理子账号</a></p>
                            <?php } elseif ($_smarty_tpl->tpl_vars['P_UserType']->value == 2) {?>
                            <p>设计师账号，可探索个性化推荐内容</p>
                            <?php } elseif ($_smarty_tpl->tpl_vars['P_UserType']->value == 3) {?>
                            <p>了解潮流趋势 / 服饰研发必备平台</p>
                            <?php } else { ?>
                            <p>升级成VIP之后，才能查看网站全部内容</p>
                            <?php }?>                            
                        </div>
                        <div class="login-suc-content">
                            <div class="login-suc-link">
                                <a class="collect-link" href="/member/mycollect/" target="_blank"><span><i></i></span>我的收藏</a>
                                <a class="history-link" href="/member/myHistory/4/" target="_blank"><span><i></i></span>浏览历史</a>
                                <a class="workbench-link" href="/member/workbench/" target="_blank"><span><i></i></span>工作室</a>
                                <a class="color-link" href="/smarttrends/colordata/" target="_blank"><span><i></i></span>色彩分析</a>
                                <a class="top-link" href="/smarttrends/topstyles/" target="_blank"><span><i></i></span>TOP榜单</a>
                                <a class="recommend-link" href="/patterns/patternRecommend/" target="_blank"><span><i></i></span>个性推荐</a>
                            </div>
                            <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 4) {?>
                            <a href="javascript:void(0);" class="js-contact-qq-btn open-account-vip">开通VIP</a>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <!-- 切换账号验证弹层 -->
                <div class="switch-box js-switch-box" style="display: none;">
                    <div class="loginClose js-loginClose"></div>
                    <div class="login-title">
                        <span>请先进行身份验证</span>
                    </div>
                    <div class="login-main">
                        <p class="errorbox errorbox3" style="top:0px;"></p>
                        <ul>
                            <li><input class="js-account" type="text" placeholder="请输入手机号"/></li>
                            <li>
                                <input class="js-switch-code" type="text" placeholder="请输入验证码"/>
                                <span class="code-btn js-code-btn1">获取验证码</span>
                            </li>
                            <li class="l_drag border-none" style="padding-top: 15px;border: none;position: relative;">
                                <div class="code_btn js-click-btn" data-cla=".js-puzzle-drag-content5">点击按钮完成图形验证</div>
                                <div class="puzzle-drag-wraper js-drag-show" style="display: none;">
                                    <s class="drag-s"></s>
                                    <t class="drag-t"></t>
                                    <div class="puzzle-drag-content js-puzzle-drag-content5">
                                        <div class="drag-title clearfix">
                                            <span>为保证你的账户安全，请先完成图形验证</span>
                                            <span class="close js-close"></span>
                                            <span class="change js-change"><i></i>换一换</span>
                                        </div>
                                        <div class="puzzle-img-box">
                                            <div class="puzzle-img js-puzzle-img">
                                                <img>
                                            </div>
                                            <div class="puzzle-lost js-puzzle-lost">
                                                <img>
                                            </div>
                                        </div>
                                        <div class="drag-slide-nav">
                                            <div class="drag-slide-text">
                                                <div class="drag-slide-left"></div>
                                                <div class="drag-slide-center">向右滑动完成拼图</div>
                                                <div class="drag-slide-right"></div>
                                            </div>
                                            <div class="drag-slide-bar js-drag-slide-bar">
                                                <div class="drag-slide-bar-left"></div>
                                                <div class="drag-slide-bar-center"></div>
                                                <div class="drag-slide-bar-right"></div>
                                            </div>
                                            <div class="drag-slide-btn js-drag-slide-btn"></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="switch-btn js-changeUser">提交</div>
                    </div>
                </div>
                <!-- 弹层背景 -->
                <div class="switch-bg js-switch-bg" style="display: none;"></div>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 5 || $_smarty_tpl->tpl_vars['P_UserType']->value == 4) {?>
                <a href="javascript:void(0);" class="js-contact-qq-btn contact-btn">了解VIP</a>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value != 5) {?>
                <a class="help-center-btn" href="http://www.pop136.com/product_help.php">帮助中心</a>
                <?php }?>
            </div>
            <div class="function-contain con_width">
                <div class="f-search-section clearfix">
                    <?php if (!empty($_smarty_tpl->tpl_vars['columns']->value) && !empty($_smarty_tpl->tpl_vars['columnId']->value)) {?>
                    <div class="f-switch-box fl js-f-switch-box">
                        <a><span>栏目搜索</span><i class="common-icon"></i></a>
                        <div class="search-type-list js-search-type-list">
                            <a data-txt="全站搜索">全站搜索</a>
                            <a data-txt="栏目搜索">栏目搜索</a>
                        </div>
                    </div>                    
                    <?php }?>
                    <div class="f-search-input js-search-box fl">
                        <?php if (!empty($_smarty_tpl->tpl_vars['columns']->value) && !empty($_smarty_tpl->tpl_vars['columnId']->value)) {?>
                        <div class="js-column-input-contain">
                            <?php if ($_smarty_tpl->tpl_vars['columnPid']->value == 3) {?>
                            <input type="text" class="Itext input-Itext js-input-search" growing-track="true" placeholder="搜索你要的：设计师/品牌/地区" <?php if (!empty($_smarty_tpl->tpl_vars['columns']->value) && !empty($_smarty_tpl->tpl_vars['columnId']->value)) {?>style="padding-left:94px;"<?php }?>>
                            <?php } elseif ($_smarty_tpl->tpl_vars['columnPid']->value == 6) {?>
                            <input type="text" class="Itext input-Itext js-input-search" growing-track="true" placeholder="搜索你要的内容" <?php if (!empty($_smarty_tpl->tpl_vars['columns']->value) && !empty($_smarty_tpl->tpl_vars['columnId']->value)) {?>style="padding-left:94px;"<?php }?>>
                            <?php } else { ?>
                            <input type="text" class="Itext input-Itext js-input-search" growing-track="true" placeholder="搜索你要的：季节/品牌/单品" <?php if (!empty($_smarty_tpl->tpl_vars['columns']->value) && !empty($_smarty_tpl->tpl_vars['columnId']->value)) {?>style="padding-left:94px;"<?php }?>>
                            <?php }?>
                            <input class="search-btn js-sear common-icon" type="button">
                        </div>
                        <?php }?>
                        <div class="js-all-input-contain" <?php if (!empty($_smarty_tpl->tpl_vars['columns']->value) && !empty($_smarty_tpl->tpl_vars['columnId']->value)) {?>style="display:none;"<?php }?>>
                            <input type="text" class="Itext input-Itext js-Itext" placeholder="时尚资讯一网打尽" growing-track="true" <?php if (!empty($_smarty_tpl->tpl_vars['columns']->value) && !empty($_smarty_tpl->tpl_vars['columnId']->value)) {?>style="padding-left:94px;"<?php }?>>
                            <input class="search-btn js-search-btn common-icon" type="button">
                            <div class="search_listDown">
                                <div class="menu"></div>
                                <div class="hot-menu">
                                    <div class="menu-hot-list">
                                        <div class="label js-history-label">历史记录<div class="fr"><span class="operat js-clear-operat">清除记录</span></div></div>
                                        <ul class="history-list"></ul>
                                        <div class="label">热门搜索</div>
                                        <ul class="hot-list"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>                     
                    </div>
                </div>
                <div class="f-find-box">
                    <a class="find-color" href="/Colorsearch/" title="色彩搜图"><i class="common-icon"></i>色彩搜图</a>
                    <span></span>
                    <a class="find-camera js-figure-btn" href="javascript:void(0);" title="以图搜图"><i class="common-icon"></i>以图搜图</a>
                </div>
            </div>            
        </div>
    </div>
    <!-- 滚动悬浮 -->
    <div class="head-top-static head-top-fixed clearfix js-head-top-fixed">
        <div class="head-t-content clearfix">
            <div class="head-logo fl js-head-logo">
                <a href="https://www.pop-fashion.com/" rel="nofollow" title="POP服装趋势"><img src="<?php echo STATIC_URL2;?>
/global/images/new_common/c_nav_logo.png" alt="POP服装趋势"><i class="common-icon"></i></a>
                    <div class="allWeb">
                        <ul class="leftlists">
                            <li><a href="https://www.pop-fashion.com/" target="_blank" rel="nofollow" title="服装"><em class="common-icon fashion"></em>服装</a></li>
                            <li><a href="http://www.pop-bags.com/" target="_blank" rel="nofollow" title="箱包"><em class="common-icon bag"></em>箱包</a></li>
                            <li><a href="http://www.pop-shoe.com/" target="_blank" rel="nofollow" title="鞋子"><em class="common-icon shoe"></em>鞋子</a></li>
                            <li><a href="http://www.51shoushi.com/" target="_blank" rel="nofollow" title="首饰"><em class="common-icon dec"></em>首饰</a></li>
                            <li><a href="http://www.91jiafang.com/" target="_blank" rel="nofollow" title="家纺"><em class="common-icon home"></em>家纺</a></li>
                            <li><a href="http://yuntu.pop136.com/" target="_blank" rel="nofollow" title="云图"><em class="common-icon yuntu"></em>云图</a></li>
                            <li><a href="http://www.pop136.com/" target="_blank" rel="nofollow" title="官网"><em class="common-icon guanw"></em>官网</a></li>
                        </ul>
                    </div>
            </div>
            <div class="user-contain fr">
                <?php if ($_smarty_tpl->tpl_vars['account']->value == '' && $_smarty_tpl->tpl_vars['accountShow']->value != 'true') {?>
                <div class="user-box fl">
                    <a href='javascript:void(0);' class='loginLayer' rel="nofollow" title="登录"><span class="lg"></span>登录</a>
                    <i></i>
                    <a class="reg-btn" href="/member/register/" rel="nofollow" title="注册"><span class="rg"></span>注册</a>
                </div>
                <?php } else { ?>
                <?php if ($_smarty_tpl->tpl_vars['pwd_tip']->value) {?>
                <div class="login-password-info js-login-password-info">您已很久没修改密码，为了您的账号安全，请前往<a href="/member/pwd/">修改密码</a><i onclick="$('.js-login-password-info').hide()"></i></div>
                <?php }?>
                <div class="login-user-box fl js-returnLi">
                    <a><em></em><span><?php echo $_smarty_tpl->tpl_vars['account']->value;?>
</span></a>
                    <div class="login-success-section js-forUl <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 1 || $_smarty_tpl->tpl_vars['P_UserType']->value == 2) {?>login-success-vip<?php }?>">
                        <div class="login-suc-head">
                            <div class="login-suc-head-t clearfix">
                                <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 1) {?>
                                <p class="fl"><?php echo $_smarty_tpl->tpl_vars['account']->value;?>
<span><i class="common-icon"></i>管理员账号</span></p>                                
                                <?php } elseif ($_smarty_tpl->tpl_vars['P_UserType']->value == 2) {?>
                                <p class="fl"><?php echo $_smarty_tpl->tpl_vars['account']->value;?>
<span><i class="common-icon"></i>设计师账号</span></p>
                                <?php } elseif ($_smarty_tpl->tpl_vars['P_UserType']->value == 3) {?>
                                <p class="fl"><?php echo $_smarty_tpl->tpl_vars['account']->value;?>
<span>试用账号</span></p>
                                <?php } else { ?>
                                <p class="fl"><?php echo $_smarty_tpl->tpl_vars['account']->value;?>
<span>普通用户</span></p>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['issetNewMessage']->value) {?>
                                <a class="fl infor-account common-icon" href='/member/message/'><span class="ix"></span></a>
                                <?php }?>
                                <a class="fr signout-account js-signOut">退出</a>
                                <?php if ($_smarty_tpl->tpl_vars['canChangLoginAccount']->value) {?>
                                    <?php if ($_smarty_tpl->tpl_vars['P_AccountType']->value == "main") {?>
                                    <a class="fr switch-account js-switch" data-type="1"><i class="common-icon"></i>切换</a>                                    
                                    <?php } else { ?>
                                    <a class="fr switch-account js-switch" data-type="2"><i class="common-icon"></i>切换</a>
                                    <?php }?>
                                <?php }?>                                
                            </div>
                            <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 1) {?>
                            <p>用VIP主账号，可管理多个子账号哦~<a href="/member/associate/" target="_blank">管理子账号</a></p>
                            <?php } elseif ($_smarty_tpl->tpl_vars['P_UserType']->value == 2) {?>
                            <p>设计师账号，可探索个性化推荐内容</p>
                            <?php } elseif ($_smarty_tpl->tpl_vars['P_UserType']->value == 3) {?>
                            <p>了解潮流趋势 / 服饰研发必备平台</p>
                            <?php } else { ?>
                            <p>升级成VIP之后，才能查看网站全部内容</p>
                            <?php }?>                            
                        </div>
                        <div class="login-suc-content">
                            <div class="login-suc-link">
                                <a class="collect-link" href="/member/mycollect/" target="_blank"><span><i></i></span>我的收藏</a>
                                <a class="history-link" href="/member/myHistory/4/" target="_blank"><span><i></i></span>浏览历史</a>
                                <a class="workbench-link" href="/member/workbench/" target="_blank"><span><i></i></span>工作室</a>
                                <a class="color-link" href="/smarttrends/colordata/" target="_blank"><span><i></i></span>色彩分析</a>
                                <a class="top-link" href="/smarttrends/topstyles/" target="_blank"><span><i></i></span>TOP榜单</a>
                                <a class="recommend-link" href="/patterns/patternRecommend/" target="_blank"><span><i></i></span>个性推荐</a>
                            </div>
                            <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 4) {?>
                            <a href="javascript:void(0);" class="js-contact-qq-btn open-account-vip">开通VIP</a>
                            <?php }?>
                        </div>
                    </div>
                </div>
                <!-- 切换账号验证弹层 -->
                <div class="switch-box js-switch-box" style="display: none;">
                    <div class="loginClose js-loginClose"></div>
                    <div class="login-title">
                        <span>请先进行身份验证</span>
                    </div>
                    <div class="login-main">
                        <p class="errorbox errorbox3" style="top:0px;"></p>
                        <ul>
                            <li><input class="js-account" type="text" placeholder="请输入手机号"/></li>
                            <li>
                                <input class="js-switch-code" type="text" placeholder="请输入验证码"/>
                                <span class="code-btn js-code-btn1">获取验证码</span>
                            </li>
                            <li class="l_drag border-none" style="padding-top: 15px;border: none;position: relative;">
                                <div class="code_btn js-click-btn" data-cla=".js-puzzle-drag-content5">点击按钮完成图形验证</div>
                                <div class="puzzle-drag-wraper js-drag-show" style="display: none;">
                                    <s class="drag-s"></s>
                                    <t class="drag-t"></t>
                                    <div class="puzzle-drag-content js-puzzle-drag-content5">
                                        <div class="drag-title clearfix">
                                            <span>为保证你的账户安全，请先完成图形验证</span>
                                            <span class="close js-close"></span>
                                            <span class="change js-change"><i></i>换一换</span>
                                        </div>
                                        <div class="puzzle-img-box">
                                            <div class="puzzle-img js-puzzle-img">
                                                <img>
                                            </div>
                                            <div class="puzzle-lost js-puzzle-lost">
                                                <img>
                                            </div>
                                        </div>
                                        <div class="drag-slide-nav">
                                            <div class="drag-slide-text">
                                                <div class="drag-slide-left"></div>
                                                <div class="drag-slide-center">向右滑动完成拼图</div>
                                                <div class="drag-slide-right"></div>
                                            </div>
                                            <div class="drag-slide-bar js-drag-slide-bar">
                                                <div class="drag-slide-bar-left"></div>
                                                <div class="drag-slide-bar-center"></div>
                                                <div class="drag-slide-bar-right"></div>
                                            </div>
                                            <div class="drag-slide-btn js-drag-slide-btn"></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        <div class="switch-btn js-changeUser">提交</div>
                    </div>
                </div>
                <!-- 弹层背景 -->
                <div class="switch-bg js-switch-bg" style="display: none;"></div>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 5 || $_smarty_tpl->tpl_vars['P_UserType']->value == 4) {?>
                <a href="javascript:void(0);" class="js-contact-qq-btn contact-btn">了解VIP</a>
                <?php }?>
                <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value != 5) {?>
                <a class="help-center-btn" href="http://www.pop136.com/product_help.php">帮助中心</a>
                <?php }?>
            </div>
            <div class="function-contain con_width">
                <div class="f-search-section clearfix">
                    <?php if (!empty($_smarty_tpl->tpl_vars['columns']->value) && !empty($_smarty_tpl->tpl_vars['columnId']->value)) {?>
                    <div class="f-switch-box fl js-f-switch-box">
                        <a><span>全站搜索</span><i class="common-icon"></i></a>
                        <div class="search-type-list js-search-type-list">
                            <a data-txt="全站搜索">全站搜索</a>
                            <a data-txt="栏目搜索">栏目搜索</a>
                        </div>
                    </div>
                    <?php }?>
                    <div class="f-search-input js-search-box fl">
                        <div class="js-all-input-contain">
                            <input type="text" class="Itext input-Itext js-Itext" placeholder="时尚资讯一网打尽" growing-track="true" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
">
                            <input class="search-btn js-search-btn common-icon" type="button">
                            <div class="search_listDown">
                                <div class="menu"></div>
                                <div class="hot-menu">
                                    <div class="menu-hot-list">
                                        <div class="label js-history-label">历史记录<div class="fr"><span class="operat js-clear-operat">清除记录</span></div></div>
                                        <ul class="history-list"></ul>
                                        <div class="label">热门搜索</div>
                                        <ul class="hot-list"></ul>
                                    </div>
                                </div>
                            </div>
                        </div>                  
                    </div>
                </div>
                <div class="f-find-box">
                    <a class="find-color" href="/Colorsearch/" title="色彩搜图"><i class="common-icon"></i>色彩搜图</a>
                    <span></span>
                    <a class="find-camera js-figure-btn" href="javascript:void(0);" title="以图搜图"><i class="common-icon"></i>以图搜图</a>
                </div>
            </div>            
        </div>
    </div>
    <div class="new_nav_box js-new_nav_box">
		<div class="contentNew clearfix">
			<div class="new_lanmu">
				<ul class="clearfix">
                    <li class="nl_hover all-nav-li js-all-nav-li">
                        <a href="javascript:void(0);" class="common-icon"></a>
                        <div class="dropdown-column-list">
                            <div class="clearfix">
                                <label class="fl">
                                    <a href="javascript:void(0);">未来趋势</a>
                                </label>
                                <div class="fl">
                                    <a href="/trends/color/" target="_blank">主题/色彩趋势</a>
                                    <a href="/trends/pattern/" target="_blank">图案趋势</a>
                                    <a href="/trends/craft/" target="_blank">工艺趋势</a>
                                    <a href="/trends/material/" target="_blank">面辅料趋势</a>
                                    <a href="/trends/silhouette/" target="_blank">廓形趋势</a>
                                    <a href="/trends/design/" target="_blank"> 企划组货</a>
                                    <a href="/inspiration/" target="_blank">灵感源</a>
                                </div>
                            </div>
                            <div class="clearfix">
                                <label class="fl">
                                    <a href="javascript:void(0);">正在流行</a>
                                </label>
                                <div class="fl">
                                    <a href="/analysis/" target="_blank">分析报告</a>
                                    <a href="/styles/" target="_blank">款式精选</a>
                                    <a href="/trends/capsules/" target="_blank">快反应</a>
                                    <a href="/books/lookbook/" target="_blank">广告大片</a>
                                    <a href="/books/lookbook/typ_2/#anchor" target="_blank">搭配手册</a>
                                    <a href="/books/magazine/" target="_blank">书籍杂志</a>
                                </div>
                            </div>
                            <div class="clearfix">
                                <label class="fl">
                                    <a href="javascript:void(0);">全球商拍</a>
                                </label>
                                <div class="fl">
                                    <a href="/analysis/market/" target="_blank">市场分析</a>
                                    <a href="/analysis/fairs/" target="_blank">展会分析</a>
                                    <a href="/styles/retail/" target="_blank">零售实拍</a>
                                    <a href="/styles/retail/ds_1/" target="_blank">批发精选</a>
                                    <a href="/styles/retail/ds_3/" target="_blank">展会精选</a>
                                    <a href="/references/fabricgallery/" target="_blank">展会面料</a>
                                </div>
                            </div>
                            <div class="clearfix">
                                <label class="fl">
                                    <a href="javascript:void(0);">订货会</a>
                                </label>
                                <div class="fl">
                                    <a href="/analysis/shows/" target="_blank">订货会分析</a>
                                    <a href="/styles/shows/" target="_blank">订货会精选</a>
                                    <a href="/books/ordermeeting/" target="_blank">全款合辑</a>
                                </div>
                            </div>
                            <div class="clearfix">
                                <label class="fl">
                                    <a href="javascript:void(0);">T台秀场</a>
                                </label>
                                <div class="fl">
                                    <a href="/analysis/runways/" target="_blank">T台分析</a>
                                    <a href="/styles/runways/" target="_blank">T台精选</a>
                                    <a href="/runways/" target="_blank">T台合辑</a>
                                </div>
                            </div>
                            <div class="clearfix">
                                <label class="fl">
                                    <a href="javascript:void(0);">设计师品牌</a>
                                </label>
                                <div class="fl">
                                    <a href="/analysis/online/" target="_blank">品牌分析</a>
                                    <a href="/styles/online/" target="_blank">品牌精选</a>
                                    <a href="/styles/designerbrand/" target="_blank">品牌合辑</a>
                                </div>
                            </div>
                            <div class="clearfix">
                                <label class="fl">
                                    <a href="javascript:void(0);">标杆品牌</a>
                                </label>
                                <div class="fl">
                                    <a href="/analysis/retail/" target="_blank">标杆分析</a>
                                    <a href="/styles/online/" target="_blank">标杆款式</a>
                                    <a href="/styles/popular/" target="_blank">款式流行</a>
                                    <a href="/patterns/topbrands/" target="_blank">大牌花型</a>
                                </div>
                            </div>
                            <div class="clearfix">
                                <label class="fl">
                                    <a href="javascript:void(0);">明星街拍</a>                                        
                                </label>
                                <div class="fl">
                                    <a href="/analysis/trendsetters/" target="_blank">街拍分析</a>
                                    <a href="/styles/trendsetters/" target="_blank">明星街拍</a>
                                    <a href="/styles/streetsnaps/" target="_blank">时尚穿搭</a>
                                </div>
                            </div>
                            <div class="clearfix">
                                <label class="fl">
                                    <a href="javascript:void(0);">图案/素材</a>                                        
                                </label>
                                <div class="fl">
                                    <a href="/trends/pattern/" target="_blank">图案分析</a>
                                    <a href="/patterns/graphics/" target="_blank">图案下载</a>
                                    <a href="/references/design/" target="_blank">款式模板</a>
                                    <a href="/references/details/" target="_blank">款式细节</a>
                                    <a href="/references/accessories/" target="_blank">服饰品</a>
                                    <a href="/references/visual/" target="_blank">店铺陈列</a>
                                </div>
                            </div>
                            <div class="clearfix">
                                <label class="fl">
                                    <a href="javascript:void(0);">数据分析</a>                                        
                                </label>
                                <div class="fl">
                                    <a href="/smarttrends/colordata/" target="_blank">色彩数据分析</a>
                                    <a href="https://yuntu.pop136.com/" target="_blank">2D&3D试衣</a>
                                    <a href="/smarttrends/topstyles/" target="_blank">TOP款式热榜</a>
                                    <a href="/Colorsearch/" target="_blank">色彩搜图</a>
                                    <a href="/picmatch/similarpic/" target="_blank">以图搜图</a>
                                </div>
                            </div>
                            <p class="contact-tel clearfix">
                                <em class="fl"></em>
                                <span class="fl">
                                    <i class="common-icon"></i>
                                    产品咨询:<strong class="strong-m">4008-210-500</strong>售后帮助:<strong>4008-210-662</strong>
                                </span>
                                <em class="fl"></em>
                            </p>
                        </div>
                    </li>
                    <?php if ($_smarty_tpl->tpl_vars['is_home']->value != 1 || $_smarty_tpl->tpl_vars['account']->value != '' || $_smarty_tpl->tpl_vars['accountShow']->value == 'true') {?>
                    <li class="nl_hover switch-area js-switch-area  <?php if ($_smarty_tpl->tpl_vars['is_relate']->value) {?>selected<?php }?>">                        
						<?php if ($_smarty_tpl->tpl_vars['account']->value == '' && $_smarty_tpl->tpl_vars['accountShow']->value != 'true') {?>
						<a href="/" class="nl_a" title="切换专区">切换专区</a>
						<?php } else { ?>
						<a href="/" class="nl_a" title="今日推荐">今日推荐</a>
						<?php }?>
						<div class="navshaixuan-list">
							<ul class="small-fenlei js-area-box">
								<li class="clearfix"><a href="javascript:void(0);" data-id="2" data-key="gen" title="女装"><em>女装</em></a></li>
								<li class="clearfix"><a href="javascript:void(0);" data-id="1" data-key="gen" title="男装"><em>男装</em></a></li>
								<li class="clearfix"><a href="javascript:void(0);" data-id="5" data-key="gen" title="童装"><em>童装</em></a></li>
								<li class="fenge-area-line"></li>
								<li class="clearfix"><a href="javascript:void(0);" data-id="1" data-key="reg" title="日韩专区"><em>日韩专区</em></a></li>
								<li class="clearfix"><a href="/fabriczone/" title="面料专区"><em>面料专区</em></a></li>
								<li class="fenge-area-line"></li>
								<li class="clearfix"><a href="javascript:void(0);" data-id="6" data-key="ind" title="毛衫"><em>毛衫</em></a></li>
								<li class="clearfix"><a href="javascript:void(0);" data-id="7" data-key="ind" title="牛仔"><em>牛仔</em></a></li>
								<li class="clearfix"><a href="javascript:void(0);" data-id="8" data-key="ind" title="皮革/皮草"><em>皮革/皮草</em></a></li>
								<li class="clearfix"><a href="javascript:void(0);" data-id="9" data-key="ind" title="内衣/泳装"><em>内衣/泳装</em></a></li>
								<li class="clearfix"><a href="javascript:void(0);" data-id="10" data-key="ind" title="婚纱/礼服"><em>婚纱/礼服</em></a></li>
                                <li class="clearfix"><a href="javascript:void(0);" data-id="11" data-key="ind" title="运动"><em>运动</em></a></li>
                            </ul>
                            <?php if ($_smarty_tpl->tpl_vars['account']->value == '' && $_smarty_tpl->tpl_vars['accountShow']->value != 'true') {?>
                            <a class="back-index-page" href="/" title="切换专区"><em></em>切换专区</a>
                            <?php } else { ?>
                            <a class="back-index-page" href="/" title="今日推荐"><em></em>今日推荐</a>
                            <?php }?>
                        </div>                        
                    </li>
					<li class="nl_hover fg-line"></li>
                    <?php }?>
					<li class="nl_hover has_down <?php if ($_smarty_tpl->tpl_vars['columnPid']->value == 1) {?>selected<?php }?>">
						<a class="nl_a" href="/trends/" title="趋势解读">趋势解读</a>						
						<div class="downlist_box">                            
							<ul>
								<li><a href="/trends/" title="全部">全部</a></li>
								<li><a href="/trends/color/" title="主题色彩">主题色彩</a></li>
								<li><a href="/trends/pattern/" title="图案趋势">图案趋势</a></li>
								<li><a href="/trends/material/" title="面辅料趋势">面辅料趋势</a></li>
								<li><a href="/trends/craft/" title="工艺趋势">工艺趋势</a></li>
								<li><a href="/trends/silhouette/" title="廓形趋势">廓形趋势</a></li>
								<li><a href="/trends/design/" title="企划/组货">企划/组货</a></li>
								<li><a href="/trends/capsules/" title="快反应">快反应</a></li>
							</ul>								
						</div>
					</li>
					<li class="nl_hover has_down <?php if ($_smarty_tpl->tpl_vars['columnPid']->value == 2) {?>selected<?php }?>">
						<a class="nl_a" href="/analysis/" title="流行分析">流行分析</a>						
						<div class="downlist_box">
							<ul>
								<li><a href="/analysis/" title="全部">全部</a></li>
                                <li><a href="/analysis/tops/" title="爆款数据">爆款数据</a></li>
								<li><a href="/analysis/runways/" title="T台分析">T台分析</a></li>
								<li><a href="/analysis/shows/" title="订货会分析">订货会分析</a></li>
								<li><a href="/analysis/online/" title="设计师品牌分析">设计师品牌分析</a></li>
								<li><a href="/analysis/retail/" title="标杆品牌分析">标杆品牌分析</a></li>
								<li><a href="/analysis/market/" title="市场分析">市场分析</a></li>
								<li><a href="/analysis/fairs/" title="展会分析">展会分析</a></li>
								<li><a href="/analysis/trendsetters/" title="明星&街拍分析">明星&街拍分析</a></li>
							</ul>
						</div>
					</li>
					<li class="nl_hover fg-line"></li>
					<li class="nl_hover has_down <?php if ($_smarty_tpl->tpl_vars['columnPid']->value == 4) {?>selected<?php }?>">
						<a class="nl_a" href="/styles/" title="款式">款式</a>						
						<div class="downlist_box">
							<ul>
								<li><a href="/styles/" title="全部">全部</a></li>
								<li><a href="/styles/runways/" title="秀场提炼">秀场提炼</a></li>
								<li><a href="/styles/shows/" title="订货会精选">订货会精选</a></li>
								<li><a href="/styles/designerbrand/" title="设计师品牌">设计师品牌</a></li>
								<li><a href="/styles/online/" title="名牌精选">名牌精选</a></li>
								<li><a href="/styles/popular/" title="款式流行">款式流行</a></li>
								<li><a href="/styles/retail/" title="全球实拍">全球实拍</a></li>
								<li><a href="/styles/trendsetters/" title="明星/ins">明星/ins</a></li>
								<li><a href="/styles/streetsnaps/" title="街拍图库">街拍图库</a></li>
							</ul>
						</div>
					</li>
					<li class="nl_hover has_down <?php if ($_smarty_tpl->tpl_vars['columnPid']->value == 9) {?>selected<?php }?>">
						<a class="nl_a" href="/patterns/graphics/" title="图案">图案</a>						
						<div class="downlist_box">
							<ul>
								<li><a href="/patterns/graphics/" title="图案素材">图案素材</a></li>
								<li><a href="/patterns/topbrands/" title="大牌花型">大牌花型</a></li>
								<li><a href="/patterns/specialtopicpatterns/" title="图案专题">图案专题</a></li>
								<li><a href="/patterns/technics/" title="图案工艺">图案工艺</a></li>
								<li><a href="/patterns/digitalprint/" title="数码云打印">数码云打印</a></li>
							</ul>
						</div>
					</li>
					<li class="nl_hover has_down <?php if ($_smarty_tpl->tpl_vars['columnPid']->value == 3) {?>selected<?php }?>">
						<a class="nl_a" href="/runways/" title="T台">T台</a>						
						<div class="downlist_box">
							<ul>
								<li><a href="/runways/" title="全部">全部</a></li>
								<li><a href="/runways/reg_272/" title="巴黎时装周">巴黎时装周</a></li>
								<li><a href="/runways/reg_341/" title="纽约时装周">纽约时装周</a></li>
								<li><a href="/runways/reg_335/" title="米兰时装周">米兰时装周</a></li>
								<li><a href="/runways/reg_323/" title="伦敦时装周">伦敦时装周</a></li>
								<li><a href="/runways/reg_other/" title="其它">其它</a></li>
							</ul>
						</div>
					</li>
					<li class="nl_hover has_down <?php if ($_smarty_tpl->tpl_vars['columnPid']->value == 7 || $_smarty_tpl->tpl_vars['columnPid']->value == 8) {?>selected<?php }?>">
						<a class="nl_a" href="/references/details/" title="素材">素材</a>
						<div class="downlist_box downlist_box2 downlist_references">
							<ul>
								<li><a href="/references/details/" title="款式细节">款式细节</a></li>
								<li><a href="/references/design/" title="款式手稿">款式手稿</a></li>
								<li><a href="/references/visual/" title="店铺陈列">店铺陈列</a></li>
								<li><a href="/references/fabricgallery/" title="展会面料">展会面料</a></li>
								<li><a href="/references/accessories/" title="服饰品">服饰品</a></li>
								<li><a href="/inspiration/" title="灵感源">灵感源</a></li>
								<li><a href="/inspiration/video/" title="灵感视频">灵感视频</a></li>
							</ul>
						</div>
                    </li>
                    <li class="nl_hover has_down <?php if ($_smarty_tpl->tpl_vars['columnPid']->value == 6) {?>selected<?php }?>">
						<a class="nl_a" href="/books/lookbook/" title="读物">读物</a>
						<div class="downlist_box">
							<ul>
								<li><a href="/books/lookbook/" title="广告大片">广告大片</a></li>
								<li><a href="/books/ordermeeting/" class="js-ordermeeting" title="订货会合辑">订货会合辑</a></li>
								<li><a href="/books/collections/" title="单品合辑">单品合辑</a></li>
								<?php if (in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2,3))) {?>
								<li><a href="/books/store/" title="趋势手稿">趋势手稿</a></li>
								<?php }?>
								<li><a href="/books/fast/" title="快反应系列">快反应系列</a></li>
								<li><a href="/books/magazine/" title="流行画册">流行画册</a></li>
							</ul>
                        </div>
                    </li>
                    <li class="nl_hover <?php if ($_smarty_tpl->tpl_vars['columnPid']->value == 10) {?>selected<?php }?>"><a class="nl_a" href="/video/" title="视频">视频</a></li>
                    <li class="nl_hover fg-line"></li>
                    <li class="nl_hover has_down design_tools">
						<a class="nl_a" href="javascript:void(0);" style="cursor: default;" title="课堂/书店"><em></em>课堂/书店<i></i></a>
						<div class="downlist_box">
							<ul>
								<li><a href="/item/seclist/mct_3/" target="_blank" title="云 · 书店">云 · 书店</a></li>
								<li><a href="https://course.pop-fashion.com/page/1244172" target="_blank" title="云 · 时尚">云 · 时尚</a></li>
								<li><a href="https://course.pop-fashion.com/page/827920" target="_blank" title="行业课程">行业课程</a></li>
								<li><a href="https://course.pop-fashion.com/page/828792" target="_blank" title="趋势讲堂">趋势讲堂</a></li>
							</ul>
						</div>
                    </li>
                    <li class="nl_hover find-brand">
                        <a class="nl_a" href="/brands/" target="_blank" title="发现品牌"><em></em>品牌库</a>
                    </li>
                    <li class="nl_hover has_down service_resources">
                        <a class="nl_a" href="/topic/resources/" title="研发资源"><em></em>资源</a>
                        <div class="downlist_box">
                            <ul>
                                <li><a href="/topic/20171129/" target="_blank" title="设界">设界</a></li>
                                <li><a href="/item/" target="_blank" title="商城">商城</a></li>
                                <li><a href="http://www.uliaobao.com/" target="_blank" title="优料宝">优料宝</a></li>
                                <li><a href="/topic/20171208/" target="_blank" title="找料帮">找料帮</a></li>
                                <li><a href="/activity/lists/" target="_blank" title="时尚商学院">时尚商学院</a></li>
                            </ul>
                        </div>
                    </li>                    
                    <li class="nl_hover has_down intelligent">
                        <a class="nl_a" href="/smarttrends/" title="智能+">
                            <span class="js-intelligent-gif">
                                <img class="png" src="<?php echo STATIC_URL2;?>
/global/images/color_data/intelligent.png?<?php echo STATIC_CHANGE_TIME;?>
" alt="">
                                <img class="gif" src="<?php echo STATIC_URL2;?>
/global/images/color_data/intelligent.gif?<?php echo STATIC_CHANGE_TIME;?>
" alt="">
                            </span>
                        </a>
                        <div class="downlist_box">                            
                            <ul>
                                <li><a href="/smarttrends/colordata/" target="_blank" title="色彩数据">色彩数据</a></li>
                                <li><a href="/smarttrends/topstyles/" target="_blank" title="款式热榜">款式热榜</a></li>
                                <li><a href="https://yuntu.pop136.com/" target="_blank" title="2D&3D试衣">2D&3D试衣</a></li>
                                <li><a href="/picmatch/similarpic/" target="_blank" title="以图搜图">以图搜图</a></li>
                                <li><a href="/patterns/patternRecommend/" target="_blank" title="智能推荐">智能推荐</a></li>
                                <li><a href="/Colorsearch/" target="_blank" title="色彩搜图">色彩搜图</a></li>
                                <li><a href="/topic/yuntu/" target="_blank" title="云图">云图</a></li>
                            </ul>
                        </div>
                    </li>
				</ul>
			</div>
		</div>
    </div>
    <input type="hidden" id="remind-time" value="<?php echo $_smarty_tpl->tpl_vars['vipTips']->value;?>
"/>
    <div class="contentW">        
        <?php if ($_smarty_tpl->tpl_vars['vipTips']->value <= 90 && $_smarty_tpl->tpl_vars['vipTips']->value > 7) {?>
        <div class="remind-list">
            <div class="interval"></div>
            <span>
                <img src="<?php echo STATIC_URL2;?>
/global/images/new_common/remind.png?<?php echo STATIC_CHANGE_TIME;?>
" alt="remind">
                您的会员账号即将到期，提前续约更享<span>惊喜好礼</span> 立即联系 <span>4008-210-662</span>
            </span>
            <a href="javascript:void(0);" onclick="return false" title="关闭"><img class="js-remind-close" src="<?php echo STATIC_URL2;?>
/global/images/new_common/remind-close.png?<?php echo STATIC_CHANGE_TIME;?>
" alt="关闭"></a>
        </div>
        <?php } elseif ($_smarty_tpl->tpl_vars['vipTips']->value <= 7 && $_smarty_tpl->tpl_vars['vipTips']->value >= 1) {?>
        <div class="remind-list">
            <div class="interval"></div>
            <span>
                <img src="<?php echo STATIC_URL2;?>
/global/images/new_common/remind.png?<?php echo STATIC_CHANGE_TIME;?>
" alt="remind">
                您的会员账号到期时间只剩<span><?php echo $_smarty_tpl->tpl_vars['vipTips']->value;?>
</span>天，为了不影响您的正常使用 请立即联系 <span>4008-210-662</span>
            </span>
            <a href="javascript:void(0);" onclick="return false" title="关闭"><img class="js-remind-close" src="<?php echo STATIC_URL2;?>
/global/images/new_common/remind-close.png?<?php echo STATIC_CHANGE_TIME;?>
" alt="关闭"></a>
        </div>
        <?php } elseif ($_smarty_tpl->tpl_vars['vipTips']->value == 'today') {?>
        <div class="remind-list">
            <div class="interval"></div>
            <span>
                <img src="<?php echo STATIC_URL2;?>
/global/images/new_common/remind.png?<?php echo STATIC_CHANGE_TIME;?>
" alt="remind">
                您的会员账户今天即将到期，为不影响您的正常使用 请立即联系 <span>4008-210-662</span>
            </span>
            <a href="javascript:void(0);" onclick="return false" title="关闭"><img class="js-remind-close" src="<?php echo STATIC_URL2;?>
/global/images/new_common/remind-close.png?<?php echo STATIC_CHANGE_TIME;?>
" alt="关闭"></a>
        </div>
        <?php }?>
    </div>
</div>

<!-- 侧边绑定手机号 -->
<div class="bindPhone js-open-quick-login open-quick-login"></div>

<?php
/*/%%SmartyNocache:2513586015f222c4a7d6ef9_28162783%%*/
}
}
?><?php
/*%%SmartyHeaderCode:17968852855f222c4c5715f6_17176388%%*/
if ($_valid && !is_callable('content_5f222c4c5656e2_42411860')) {
function content_5f222c4c5656e2_42411860 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '17968852855f222c4c5715f6_17176388';
?>
<!-- 侧边导航 -->
<?php if (!$_smarty_tpl->tpl_vars['isPopMall']->value) {?>
<div class="nav_fixed">
	<ul>
		<li class="zixun-icon">
			<a class="js-contact-qq-btn" href="javascript:void(0);" data-type="<?php if (in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2))) {?>after<?php }?>" title="咨询">咨询</a>
		</li>
		<li class="qq_icon lix1200">
            <a class="js-contact-qq-btn" href="javascript:void(0);" data-type="<?php if (in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2))) {?>after<?php }?>" rel="nofollow" title="咨询"></a>
            <?php if (!empty($_smarty_tpl->tpl_vars['P_UserType']->value) && in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2))) {?>
            <div class="qq_box show_left">售后小秘书</div>
            <?php } else { ?>
            <div class="qq_box show_left">在线咨询</div>
            <?php }?>
            <i></i>
        </li>
		<li class="tel_before_icon lix1200">
			<div class="child show_left">
				<span>售前客服</span>
				<em>4008 210 500</em>
			</div>
			<i></i>
		</li>
		<li class="tel_after_icon lix1200">
			<div class="child show_left">
				<span>售后小秘书</span>
				<em>4008 210 662</em>
			</div>
			<i></i>
		</li>
		<li class="rqcode_icon">
			<div class="rqcode_img show_left">
        <div class="tab_box clearfix js_side_tab">
            <div class="item fl on">微信小助手</div>
            <div class="item fl">下载趋势APP</div>
        </div>
        <div class="tab_content js_side_tabcon">
            <div class="item clearfix on">
                <div class="fl">
                <img src="<?php echo STATIC_URL1;?>
/global/images/QRcode/weixin_code.jpg?<?php echo STATIC_CHANGE_TIME;?>
" alt="微信小助手">
                </div>
                <div class="fl right">
                <div class="hline"></div>
                <span>添加官方小秘书</span>
                <span>随时随地了解最新趋势</span>
                </div>
            </div>
            <div class="item clearfix">
                <div class="fl">
                <img class="trend" src="<?php echo STATIC_URL1;?>
/global/images/QRcode/app_code.png?<?php echo STATIC_CHANGE_TIME;?>
" alt="趋势APP">
                </div>
                <div class="fl right">
                <div class="hline"></div>
                <span>下载趋势APP</span>
                <span>让趋势预见流行</span>
                </div>
            </div>
        </div>
			</div>
			<i></i>
		</li>
		<!-- <li class="pop-map-icon js-pop-map">
			<div class="arrow-map-icon js-arrow-map"></div>
			<div class="map-contain js-map-show">
				<div class="map-img js-map-img">
					<a href="/service/address/" target="_blank" title="查看大图"><img src="//api.map.baidu.com/mapCard/img/bigMapIcon.jpg" alt="icon">查看大图</a>
				</div>
				<div class="map-contact">
					<h2>逸尚创展（上海）科技有限公司</h2>
					<p>电话：4008-210-500</p>
					<p>传真：021-64083123</p>
					<p>E-mail：service@pop136.com</p>
				</div>
				<a href="http://www.pop136.com/" target="_blank" title="了解更多">了解更多</a>
			</div>
		</li> -->
		<?php if (in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2,3,4))) {?>
		<li class="qq_icon bj_icon lix1200">
            <!-- data-type="<?php if (in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2))) {?>after<?php }?>" -->
			<a class="" href="/member/leavemessage"  rel="nofollow" title="咨询"></a>
		</li>
		<?php }?>
		<!-- <li class="new-icon lix1200 js-get-new-trend">
			<div class="new-icon-box"></div>
			<div class="new-box show_left">最新趋势</div>
			<i></i>
		</li> -->
		<li class="backTop" id="backTop"></li>
	</ul>
	<?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 5 || $_smarty_tpl->tpl_vars['P_UserType']->value == 4) {?>
	<div class="nav_trial js-new-trend-enter">
		<span>免费试读</span>
    </div>
	<?php }?>
</div>
<?php }?><?php
/*/%%SmartyNocache:17968852855f222c4c5715f6_17176388%%*/
}
}
?><?php
/*%%SmartyHeaderCode:4409344455f222c4c862b16_97226050%%*/
if ($_valid && !is_callable('content_5f222c4c81c038_44920301')) {
function content_5f222c4c81c038_44920301 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '4409344455f222c4c862b16_97226050';
?>
<div class="new-trends-fix js-new-trends-fix trends-fix-start">
    <div class="new-trends-bg js-new-trends-bg"></div>
    <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 5 || $_smarty_tpl->tpl_vars['P_UserType']->value == 4) {?>
    <!-- 旧版弹层 -->
	<div class="explore-pop js-explore-pop">
        <div class="trends-msg-close"></div>
        <div class="new-explore-list">
			<h3><a href="/topic/scene_home/" target="_blank"></a></h3>
			<div class="scene-links">
				<a href="/topic/theme_color/" target="_blank"><span>主题<br/>色彩</span></a>
				<a href="/topic/patterns/" target="_blank"><span>图案</span></a>
				<a href="/topic/fabric/" target="_blank"><span>面料</span></a>
				<a href="/topic/single/" target="_blank"><span>单品</span></a>
				<a href="/topic/brand/" target="_blank"><span>品牌</span></a>
			</div>
            <ul class="clearfix js-new-trends load-start"></ul>
            <div class="trends-pics js-trends-pics">
                <ul class="clearfix"></ul>
            </div>
        </div>
        <div class="new-explore-bott clearfix">
            <div class="report-new"></div>
            <div class="is-user">
                <p></p>
                <a class="bott-a1 loginLayer" href="javascript:void(0);" rel="nofollow" title="查看大图">登录</a>
                <a class="bott-a2" href="/member/register/" target="_blank" title="查看大图">注册</a>
                <a class="bott-a3 js-contact-qq-btn" href="javascript:void(0);" title="查看大图">联系客服</a>
            </div>
        </div>
    </div>
    <!-- 弹层改版 -->
    <div class="trials-box js-trials-box">
        <div class="trials_close_count js-new-trends-bg"></div>
        <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 5) {?>
        <div class="trials-tit clearfix">
            <div class="fl">
                <h3>开始免费试读</h3>
                <p>拨打 4008-210-500 咨询VIP服务</p>
            </div>
            <div class="fr">
                <a class="trials-reg-btn" href="/member/register/" target="_blank">立即注册</a>
                <a class="trials-contact-btn js-contact-qq-btn" href="javascript:void(0);">畅通无阻快速了解</a>
            </div>
        </div>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 4) {?>
        <div class="trials-tit clearfix">
            <div class="fl">
                <h3>免费精选内容</h3>
                <p>拨打 4008-210-500 咨询VIP服务</p>
            </div>
            <div class="fr">
                <a class="trials-reg-btn trials-reg-usertype-btn js-contact-qq-btn" href="javascript:void(0);">咨询会员服务</a>
            </div>
        </div>
        <?php }?>
        <div class="trials-itembox clearfix">
            <div class="trials-trend-fl fl">
                <!--免费报告-->
                <div class="trials-item-title">报告免费试读</div>
                <div class="trials-item-top js-trials-item-top clearfix">
                    
                </div>
                <!--/免费报告-->
            </div>
            <div class="trials-style-fl fl">
                <!--免费款式-->
                <div class="trials-item-title">款式免费查看</div>
                <div class="trials-item-btm js-trials-item-btm clear">
                    
                </div>
                <!--/免费款式-->
            </div>
        </div>
    </div>
    <?php } else { ?>
    <div class="trends-msg">
        <!-- 侧边导航 -->
        <div class="trends-nav">
            <ul class="js-trends-navs"></ul>
        </div>

        <div class="trends-msg-close"></div>
        <div class="new-trends-list load-start">
            <h3></h3>
            <ul class="clearfix js-new-trends"></ul>
            <div class="trends-pics js-trends-pics">
                <ul class="clearfix"></ul>
            </div>
        </div>
	</div>
    <?php }?>
    <!-- 款式详情弹层弹层 -->
    <div class="trends-bg js-style-close"></div>
    <div class="style-list-content js-style-list-content">
        <div class="style-close js-style-close"></div>
        <div class="style-list-top">
            <h2 class="list-title js-list-title">ces cesc ecs</h2>
            <div class="style-btn">
                <p>需要更多会员服务，请联系：<i></i><strong>4008-210-500</strong></p>
                <a href="javascript:void(0);" class="js-contact-qq-btn">咨询会员服务</a>
                <a class="js-more-url btn" title="查看更多" target="_blank">查看更多</a>
            </div>
        </div>
        <div class="img-box js-img-box">
            <div class="img-box-btn img-left js-img-left"></div>
            <img src="">
            <div class="img-box-btn img-right js-img-right"></div>
        </div>
    </div>
</div><?php
/*/%%SmartyNocache:4409344455f222c4c862b16_97226050%%*/
}
}
?><?php
/*%%SmartyHeaderCode:6932055265f222c4c53e123_32658876%%*/
if ($_valid && !is_callable('content_5f222c4c522fc9_80648440')) {
function content_5f222c4c522fc9_80648440 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '6932055265f222c4c53e123_32658876';
?>

<div class="web-common-foot">
    <div class="contentW">
        <div class="common-foot-top clearfix">
            <div class="foot-top-left fl">
                <h3>POP时尚服务矩阵</h3>
                <p>POP时尚趋势获工信部认可并授予  工信部纺织服装创意设计试点平台</p>
                <div>
                    <a href="http://www.51shoushi.com" target="_blank">POP首饰趋势</a>
                    <a href="http://www.pop-shoe.com" target="_blank">POP鞋子趋势</a>
                    <a href="http://www.91jiafang.com" target="_blank">POP家纺趋势</a>
                    <a href="http://www.pop-bags.com" target="_blank">POP箱包趋势</a><br/>
                    <a href="http://www.uliaobao.com/" target="_blank">ULB优料宝-面料</a>
                    <a href="/topic/trend_app/" target="_blank">APP-POP趋势</a>
                    <a href="/topic/yuntu/" target="_blank">POP云图</a>
                    <a href="http://www.pop136.com/" target="_blank">POP官网</a>
                    <a href="/blog/" target="_blank">时尚博客</a>
                </div>
            </div>
            <div class="foot-top-right fr">
                <div class="fl contact-foot">
                    <p>客服热线</p>
                    <div>
                        4008-210-500<span>（售前）</span><br/>                        
                        4008-210-662<span>（售后）</span>
                    </div>
                </div>
                <div class="fr qrcode-foot">
                    <img src="<?php echo STATIC_URL1;?>
/global/images/QRcode/erwm.png"/>
                    <p>官方小秘书</p>
                </div>
            </div>
        </div>
        <div class="common-foot-bottom clearfix">
            <div class="foot-bottom-left fl">
                <div class="foot-nav-list">
					<a href="/service/aboutus/" target="_blank" title="关于我们" rel="nofollow">关于我们</a>|
					<a href="/service/joinmember/" target="_blank" title="加入会员" rel="nofollow">加入会员</a>|
					<a href="/service/membernotice/" target="_blank" title="会员须知" rel="nofollow">会员须知</a>|
					<a href="/service/tolllist/" target="_blank" title="收费一览" rel="nofollow">收费一览</a>|
					<a href="/service/payment/" target="_blank" title="付款方式" rel="nofollow">付款方式</a>|
					<a href="/service/declaration/" target="_blank" title="法律声明" rel="nofollow">法律声明</a>|
					<a href="/service/contactus/" target="_blank" title="联系我们" rel="nofollow">联系我们</a>|
					<a href="/service/friendlylink/" target="_blank" title="友情链接">友情链接</a>|
					<a href="/service/sitemap/" target="_blank" title="网站地图">网站地图</a>|
					<a href="/topic/" target="_blank" title="活动专题">活动专题</a>
                </div>
                <p>逸尚创展（上海）科技有限公司 | 上海逸尚云联信息技术股份有限公司 © 2004-2020法律顾问：北京中银（上海）律师事务所 <a href="http://www.beian.miit.gov.cn/" target="_blank" style="margin:0;vertical-align:initial;">沪ICP备06003020号-1</a></p>
            </div>
            <div class="foot-bottom-right fr">
                <a target="_blank" href="http://wap.scjgj.sh.gov.cn/businessCheck/verifKey.do?showType=extShow&amp;serial=9031000020160526093630000000851495-SAIC_SHOW_310000-20120428141018720124&amp;signData=MEUCIQDZWDSUQE195cBFzEmHGyMfx92RgQ9hs6ZMwm3ePnH6kAIgMh9DQQ9W3S+fDej1PTh5x9PdFq9QKDSvtOGWbRJfWZU=">
                    <img src="https://imgf1.pop-fashion.com/global/images/safty1.png" >
                </a>
                <a target="_blank" href="http://www.beian.gov.cn/portal/registerSystemInfo?recordcode=31010402000973">
                    <img src="https://imgf1.pop-fashion.com/global/images/safty.png" alt="icon">
                    <span>沪公网安备 31010402000973号</span>
                </a>
            </div>
        </div>
    </div>
</div>

<?php /*  Call merged included template "common/nav_fixed_box.html" */
echo $_smarty_tpl->getInlineSubTemplate("common/nav_fixed_box.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '17968852855f222c4c5715f6_17176388', 'content_5f222c4c5656e2_42411860');
/*  End of included template "common/nav_fixed_box.html" */?>



<!-- VIP登录增加弹窗 -->
<div class="vip-add-layer js-vip-add-layer">
	<div class="vip-add-layer-con">
		<div class="layer-remind-title">欢迎回来，<?php echo $_smarty_tpl->tpl_vars['account']->value;?>
</div>
		<div class="layer-remid-con">
			<p class="vip-cus-name">您现在是管理员账号，可作为POP会员账号的管理操作</p>
			<p class="vip-cus-remind">体验收藏、工作台、共享资料等更多操作，需创建设计师专属账号，你还没有创建设计师专属账号？</p>			
			<div class="exclusive-account-fs">
				<h3>立即创建账号，体验以下全部功能！</h3>
				<ul class="clearfix">
					<li>
						<img src="<?php echo STATIC_URL1;?>
/global/images/v_layer1.png" alt="icon">
						<p>我的工作室</p>
					</li>
					<li>
						<img src="<?php echo STATIC_URL1;?>
/global/images/v_layer2.png" alt="icon">
						<p>我的下载包</p>
					</li>
					<li>
						<img src="<?php echo STATIC_URL1;?>
/global/images/v_layer3.png" alt="icon">
						<p>个人收藏</p>
					</li>
					<li>
						<img src="<?php echo STATIC_URL1;?>
/global/images/v_layer4.png" alt="icon">
						<p> 共享资料</p>
					</li>
					<li>
						<img src="<?php echo STATIC_URL1;?>
/global/images/v_layer5.png" alt="icon">
						<p>更多专属功能</p>
					</li>
				</ul>
			</div>
			<div class="vip-add-btn">
				<a href="/member/associate/" target="_blank" class="im-create-a" title="立即创建设计师专属账号">立即创建设计师专属账号</a>
				<a class="ignore-a js-vip-close" href="javascript:void(0);" title="忽略">忽略</a>
			</div>
		</div>
		<a class="vip-add-close js-vip-close" href="javascript:void(0);"></a>
	</div>
</div>


<!-- 申请试用弹出 -->
<div class="feedback-section-box clear js-feedback-section-box">
    <button></button>
    <h3><span></span>联系我们</h3>
    <div class="contact-div">
        <span>直接联系客服</span>
        <button class="js-contact-qq-btn" title="直接咨询">直接咨询</button>

        <p>咨询热线：<span>4008-210-500</span></p>
        <p>售后服务：<span>4008-210-662</span></p>
    </div>
    <div class="form-div js-form-div">
        <h3>请留下简要的信息，我们将在两个工作日内与您联系!</h3>
        <ul class="form-list js-form-list js-form-list1">
            <li>
                <label for="name">姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名</label><input value="" autocomplete="off" class="js-input-area" name="name" type="text" maxlength="20" placeholder="方便我们称呼您">
                <p>4-20位字符，一个汉字是两个字符</p>
            </li>
            <li>
                <label for="tel">电&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;话</label><input value="" autocomplete="off" class="js-input-area js-mobile-area" id="tel" name="tel" type="text" placeholder="联系您需要">
                <p>请输入手机号</p>
            </li>
            <li class="select-item">
                <label>咨询类型</label>
                <div class="select-div js-select-div">
                    <span class="select-spn now-choice" value="5">申请试用</span>
                    <ul class="option-list">
                        <?php
$_from = $_smarty_tpl->tpl_vars['feedbackTypes']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                        <li value="<?php echo $_smarty_tpl->tpl_vars['val']->value['id'];?>
"><?php echo $_smarty_tpl->tpl_vars['val']->value['sName'];?>
</li>
                        <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                    </ul>
                </div>
                <p>请选择咨询类型的内容</p>

            </li>
            <li class="txt-box">
                <textarea class="js-input-area" name="content" placeholder="可在此处填写您想告知我们的其他信息" maxlength="100"></textarea>
            </li>
        </ul>
        <button class="sub-btn js-sub-btn" data-type="1" title="马上联系我们">马上联系我们</button>
    </div>
</div>
<div class="bg-div js-bg-div"></div>
<div class="common-bg-div js-common-bg-div"></div>


<!-- 最新推荐弹层 -->
<?php /*  Call merged included template "common/recommend-layer-box.html" */
echo $_smarty_tpl->getInlineSubTemplate("common/recommend-layer-box.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '4409344455f222c4c862b16_97226050', 'content_5f222c4c81c038_44920301');
/*  End of included template "common/recommend-layer-box.html" */?>


<?php
/*/%%SmartyNocache:6932055265f222c4c53e123_32658876%%*/
}
}
?><?php
/*%%SmartyHeaderCode:11041001855f222c4ce3c177_79279370%%*/
if ($_valid && !is_callable('content_5f222c4ce32092_56173392')) {
function content_5f222c4ce32092_56173392 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '11041001855f222c4ce3c177_79279370';
?>


    <?php if (!$_smarty_tpl->tpl_vars['isPdf']->value) {?>
    <?php echo '<script'; ?>
>
        ;(function(){
            var bp = document.createElement('script');
            var curProtocol = window.location.protocol.split(':')[0];
            if (curProtocol === 'https') {
                bp.src = 'https://zz.bdstatic.com/linksubmit/push.js';
            }
            else {
                bp.src = 'http://push.zhanzhang.baidu.com/push.js';
            }
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(bp, s);

        })();
    <?php echo '</script'; ?>
>
    <?php }?>
    <!-- 第三方统计js -->
    <!-- GrowingIO Analytics code version 2.1 -->
    <!-- Copyright 2015-2017 GrowingIO, Inc. More info available at http://www.growingio.com -->
    
    <?php echo '<script'; ?>
 type='text/javascript'>
        !function(e,t,n,g,i){e[i]=e[i]||function(){(e[i].q=e[i].q||[]).push(arguments)},n=t.createElement("script"),tag=t.getElementsByTagName("script")[0],n.async=1,n.src=('https:'==document.location.protocol?'https://':'http://')+g,tag.parentNode.insertBefore(n,tag)}(window,document,"script","assets.giocdn.com/2.1/gio.js","gio");
        gio('init','8de2f524d49e13e1', {});
        gio('config', {hashtag: true});

        //custom page code begin here
        var $user_identity = {'1':'会员用户', '2':'会员用户', '3':'试用用户', '4':'普通用户', '5':'游客用户'};
        var $account_type = {'main':'主账号', 'child':'子账号'};

        var $member_set = {};
        $member_set.UserIdentity = $user_identity[P_UserType];
        $member_set.ChannelCode = pid;

        if (P_UserId) {
            gio('setUserId', P_UserId);
            $member_set.AccountType = $account_type[P_AccountType];
            $member_set.AccountId = P_AccountId;
        } else {
            gio('clearUserId');
        }

        gio('people.set', $member_set);

        //custom page code end here

        gio('send');

    <?php echo '</script'; ?>
>
    

    <!-- End GrowingIO Analytics code version: 2.1 -->
    <?php if (!$_smarty_tpl->tpl_vars['isPdf']->value) {?>
    <?php echo '<script'; ?>
>
        var _hmt = _hmt || [];
        var customer_type = 'guest';
        if( P_UserType == 1) customer_type = 'vip';
        else if(P_UserType == 4) customer_type = 'normal';
        _hmt.push(['_setCustomVar', 1, 'customer', customer_type, 3]);
        (function() {
            var hm = document.createElement("script");
            var s = document.getElementsByTagName("script")[0];
            //百度付费统计代码
            hm.src = "//hm.baidu.com/hm.js?ec1d5de03c39d652adb3b5432ece711d";
            s.parentNode.insertBefore(hm, s);
            //百度免费统计代码(www)
            hm = document.createElement("script");
            s = document.getElementsByTagName("script")[0];
            hm.src = "//hm.baidu.com/hm.js?40163307b5932c7d36838ff99a147621";
            s.parentNode.insertBefore(hm, s);
        })();
    <?php echo '</script'; ?>
>
    <?php }?>
<?php
/*/%%SmartyNocache:11041001855f222c4ce3c177_79279370%%*/
}
}
?>