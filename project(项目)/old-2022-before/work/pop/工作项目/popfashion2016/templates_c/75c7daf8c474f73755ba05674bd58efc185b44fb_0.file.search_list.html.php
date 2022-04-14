<?php /* Smarty version 3.1.27, created on 2020-04-27 16:03:44
         compiled from "/data/htdocs/popfashion2016/views/lists/search_list.html" */ ?>
<?php
/*%%SmartyHeaderCode:8519447295ea691e04d2527_22466053%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '75c7daf8c474f73755ba05674bd58efc185b44fb' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/lists/search_list.html',
      1 => 1587872786,
      2 => 'file',
    ),
    '186731afe75f9278a5feaa0172e7d2a4d76f421e' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/layout.html',
      1 => 1587872782,
      2 => 'file',
    ),
    'a58b1dfb758eb88491402b162ae70e27fb9dafe9' => 
    array (
      0 => 'a58b1dfb758eb88491402b162ae70e27fb9dafe9',
      1 => 0,
      2 => 'string',
    ),
    '3e3e8eef1d499fd1205c41d1ede66dff0805dcc5' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/header.html',
      1 => 1587872780,
      2 => 'file',
    ),
    '72877bbbd5474db811f07237b2ab66381b2dd2bf' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/common/header_nav_down.html',
      1 => 1587872789,
      2 => 'file',
    ),
    '40d3c14ee1eb1a926be6f71ed11019f44af26bb5' => 
    array (
      0 => '40d3c14ee1eb1a926be6f71ed11019f44af26bb5',
      1 => 0,
      2 => 'string',
    ),
    '968146f456edc446c173b916343297f791f68251' => 
    array (
      0 => '968146f456edc446c173b916343297f791f68251',
      1 => 0,
      2 => 'string',
    ),
    '4266e74bbd5c6b205d9e820a2a21d120fff8e0e7' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/common/search_head.html',
      1 => 1587872789,
      2 => 'file',
    ),
    'a15220a915805af59fb1e4856ea1182b312d537c' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/footer.html',
      1 => 1587872789,
      2 => 'file',
    ),
    'df9f2102c3779411c38baab9f90d5d1db7c01711' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/common/nav_fixed_box.html',
      1 => 1587872789,
      2 => 'file',
    ),
    '55f0223628bd28165422d974fe8a3ea2b2b119c9' => 
    array (
      0 => '55f0223628bd28165422d974fe8a3ea2b2b119c9',
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
  'nocache_hash' => '8519447295ea691e04d2527_22466053',
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
    'brandAllStaticTime' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5ea691e1bfeb99_80905513',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5ea691e1bfeb99_80905513')) {
function content_5ea691e1bfeb99_80905513 ($_smarty_tpl) {
if (!is_callable('smarty_modifier_truncate')) require_once '/data/htdocs/popfashion2016/libraries/Smarty/plugins/modifier.truncate.php';
if (!is_callable('smarty_modifier_date_format')) require_once '/data/htdocs/popfashion2016/libraries/Smarty/plugins/modifier.date_format.php';

$_smarty_tpl->properties['nocache_hash'] = '8519447295ea691e04d2527_22466053';
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
$_smarty_tpl->properties['nocache_hash'] = '8519447295ea691e04d2527_22466053';
?>

<link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL2;?>
/global/css/pop_layer.css?<?php echo STATIC_CHANGE_TIME;?>
">
<link rel="stylesheet" type="text/css" href="<?php echo STATIC_URL2;?>
/global/css/per.css?<?php echo STATIC_CHANGE_TIME;?>
">
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL1;?>
/global/js/common/template.js"><?php echo '</script'; ?>
>

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


<?php if ($_smarty_tpl->tpl_vars['title']->value) {?>
 <h1 style="display: none;"><?php echo str_replace('-POP服装趋势网','',$_smarty_tpl->tpl_vars['title']->value);?>
</h1>
<?php }?>
    <!-- header start -->
	<?php
$_smarty_tpl->properties['nocache_hash'] = '8519447295ea691e04d2527_22466053';
?>



    <!-- header end -->

    <!-- main start -->
    <?php
$_smarty_tpl->properties['nocache_hash'] = '8519447295ea691e04d2527_22466053';
?>

<div class="cont_search">
    <div class="search_cont_topW">
        <div class="division"></div>
        <div class="con_width">
            <?php /*  Call merged included template "../common/search_head.html" */
echo $_smarty_tpl->getInlineSubTemplate("../common/search_head.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '2642417175ea691e0a07180_62648993', 'content_5ea691e0a05e01_29212688');
/*  End of included template "../common/search_head.html" */?>

            <div class="search-main">
                <div class="search_head js-search_head clearfix">
                    <a data-name="col" data-id="all" data-num="<?php echo $_smarty_tpl->tpl_vars['allTotal']->value['all'];?>
" class="head_item js-jump <?php if ($_smarty_tpl->tpl_vars['iColumnPid']->value == 'all') {?>action<?php }?>">全部<span><?php echo $_smarty_tpl->tpl_vars['allTotal']->value['all'];?>
</span></a>
                    <a data-name="col" data-id="4" data-num="<?php echo $_smarty_tpl->tpl_vars['allTotal']->value[4];?>
" class="head_item js-jump <?php if ($_smarty_tpl->tpl_vars['iColumnPid']->value == '4') {?>action<?php }?>">款式<span><?php echo $_smarty_tpl->tpl_vars['allTotal']->value[4];?>
</span></a>
                    <a data-name="col" data-id="1" data-num="<?php echo $_smarty_tpl->tpl_vars['allTotal']->value[1];?>
" class="head_item js-jump <?php if ($_smarty_tpl->tpl_vars['iColumnPid']->value == '1') {?>action<?php }?>">趋势解读<span><?php echo $_smarty_tpl->tpl_vars['allTotal']->value[1];?>
</span></a>
                    <a data-name="col" data-id="2" data-num="<?php echo $_smarty_tpl->tpl_vars['allTotal']->value[2];?>
" class="head_item js-jump <?php if ($_smarty_tpl->tpl_vars['iColumnPid']->value == '2') {?>action<?php }?>">流行分析<span><?php echo $_smarty_tpl->tpl_vars['allTotal']->value[2];?>
</span></a>
                    <a data-name="col" data-id="82" data-num="<?php echo $_smarty_tpl->tpl_vars['allTotal']->value[82];?>
" class="head_item js-jump <?php if ($_smarty_tpl->tpl_vars['iColumnPid']->value == '82' || $_smarty_tpl->tpl_vars['iColumnPid']->value == '120') {?>action<?php }?>">图案<span><?php echo $_smarty_tpl->tpl_vars['allTotal']->value[82];?>
</span></a>
                    <a data-name="col" data-id="3" data-num="<?php echo $_smarty_tpl->tpl_vars['allTotal']->value[3];?>
" class="head_item js-jump <?php if ($_smarty_tpl->tpl_vars['iColumnPid']->value == '3') {?>action<?php }?>">T台<span><?php echo $_smarty_tpl->tpl_vars['allTotal']->value[3];?>
</span></a>
                    <a data-name="col" data-id="71" data-num="<?php echo $_smarty_tpl->tpl_vars['allTotal']->value[71];?>
" class="head_item js-jump <?php if ($_smarty_tpl->tpl_vars['iColumnPid']->value == '71') {?>action<?php }?>">广告大片<span><?php echo $_smarty_tpl->tpl_vars['allTotal']->value[71];?>
</span></a>
                </div>
                <div class="select_items_box js_select_items_box clearfix">
                    <span class="aspan_load">
                        <img src="<?php echo STATIC_URL2;?>
/global/images/5-121204193934-50.gif">
                            &nbsp筛选项加载中，请稍等...
                    </span>
                </div>
            </div>
        </div>
    </div>
    <?php if ($_smarty_tpl->tpl_vars['spellcheck']->value) {?>
        <div class="con_width search-tips js-search-tips">你要找的是不是：
            <?php
$_from = $_smarty_tpl->tpl_vars['spellcheck']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['word'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['word']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['word']->value) {
$_smarty_tpl->tpl_vars['word']->_loop = true;
$foreach_word_Sav = $_smarty_tpl->tpl_vars['word'];
?>
                <span class="sel"><?php echo $_smarty_tpl->tpl_vars['word']->value;?>
</span>
            <?php
$_smarty_tpl->tpl_vars['word'] = $foreach_word_Sav;
}
?>
        </div>
    <?php }?>
    <div class="con_width serach_box">
        <div class="all_search_main">
            <?php if ($_smarty_tpl->tpl_vars['iColumnPid']->value == "4") {?>
            <!-- 款式 -->
                <div class="style_box box js-box-style">
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[4]['data'])) {?>
                    <div class="style_list clearfix" style="padding-top:0;">
                        <?php
$_from = $_smarty_tpl->tpl_vars['lists']->value[4]['data'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                        <div data-url="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" class="style_item js-item">
                            <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" class="img_box" target="_blank">
                                <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                                <div class="time"></div>
                                <span><?php echo $_smarty_tpl->tpl_vars['item']->value['publish_time'];?>
</span>
                                <div class="shuiyin"></div>
                            </a>
                            <div class="js-page-text">
                                <div class="label js-label">
                                    <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                    <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                </div>
                            </div>
                        </div>
                        <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                    </div>
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[4]['more_url'])) {?>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['lists']->value[4]['more_url'];?>
" class="more-btn"><span>查看更多<i></i></span></a>
                    <?php }?>
                    <?php } else { ?>
                    <!-- 无结果 -->
                    <div class="serach_no"><span>抱歉，没找到符合条件的内容</span></div>
                    <div class="sear-box clearfix">
                        <div>如有其它疑问，请联系您的专属资讯小秘书：</div>
                        <p>
                            <?php if (!empty($_smarty_tpl->tpl_vars['P_UserType']->value) && in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2))) {?>
                            <a class="neiyeList fl js-contact-qq-btn" data-type="1" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-662</em></span>
                            <?php } else { ?>
                            <a class="neiyeList fl js-contact-qq-btn" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-500</em></span>
                            <?php }?>
                        </p>
                    </div>
                    <?php }?>
                </div>
            <?php } elseif ($_smarty_tpl->tpl_vars['iColumnPid']->value == "1") {?>
            <!-- 趋势解读 -->
                <div class="trend_box box">
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[1]['data'])) {?>
                    <div class="new_trend clearfix" style="padding-top:0;">
                        <?php
$_from = $_smarty_tpl->tpl_vars['lists']->value[1]['data'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                            <?php if ($_smarty_tpl->tpl_vars['item']->value['sign'] == 1) {?>
                            <!--主报告-->
                            <div class="trend_item js-analysis-main">
                                <!--分数-->
                                <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" data-score="<?php echo $_smarty_tpl->tpl_vars['item']->value['score'];?>
" target="_blank" class="trend_bg">
                                    <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                                </a>
                                <div class="trend_box">
                                    <div class="trend_text">
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="clearfix">
                                            <p class="ay-time clearfix">
                                                <span class="fl"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['publish_time'],'%Y-%m-%d');?>
</span>
                                                <span class="fr">浏览（<?php echo $_smarty_tpl->tpl_vars['item']->value['iViewCount'];?>
）</span>
                                            </p>
                                            <p class="ay-title"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_title'];?>
</p>
                                        </a>
                                        <p class="ay-img-description js-ay-img-description"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_desc'];?>
</p>
                                        <div class="ay-label-img">
                                            <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                            <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                            <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['item']->value['sign'] == 2) {?>
                            <!--子报告全部取到-->
                            <div class="trend_item js-analysis-main">
                                <!--分数-->
                                <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" data-score="<?php echo $_smarty_tpl->tpl_vars['item']->value['score'];?>
" target="_blank" class="trend_bg">
                                    <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                                </a>
                                <div class="trend_box">
                                    <div class="trend_text">
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="clearfix">
                                            <p class="ay-time clearfix">
                                                <span class="fl"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['publish_time'],'%Y-%m-%d');?>
</span>
                                                <span class="fr">浏览（<?php echo $_smarty_tpl->tpl_vars['item']->value['iViewCount'];?>
）</span>
                                            </p>
                                            <p class="ay-title fu-title">
                                                <span><?php echo $_smarty_tpl->tpl_vars['item']->value['minor_title'];?>
</span>
                                                <label class="js-major"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_title'];?>
</label>
                                            </p>
                                        </a>
                                        <p class="ay-img-description js-ay-img-description"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_desc'];?>
</p>
                                        <div class="ay-label-img">
                                            <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                            <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                            <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['item']->value['sign'] == 3) {?>
                            <!--子报告封面图取得是主报告的封面图-->
                            <div class="trend_item js-analysis-main">
                                <!--分数-->
                                <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" data-score="<?php echo $_smarty_tpl->tpl_vars['item']->value['score'];?>
" target="_blank" class="trend_bg">
                                    <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                                    <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == '1' || $_smarty_tpl->tpl_vars['P_UserType']->value == '2' || $_smarty_tpl->tpl_vars['P_UserType']->value == '3') {?>
                                    <div class="bg"><span><?php echo $_smarty_tpl->tpl_vars['item']->value['minor_title'];?>
</span></div>
                                    <?php }?>
                                </a>
                                <div class="trend_box">
                                    <div class="trend_text">
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="clearfix">
                                            <p class="ay-time clearfix">
                                                <span class="fl"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['publish_time'],'%Y-%m-%d');?>
</span>
                                                <span class="fr">浏览（<?php echo $_smarty_tpl->tpl_vars['item']->value['iViewCount'];?>
）</span>
                                            </p>
                                            <p class="ay-title fu-title">
                                                <span><?php echo $_smarty_tpl->tpl_vars['item']->value['minor_title'];?>
</span>
                                                <label class="js-major"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_title'];?>
</label>
                                            </p>
                                        </a>
                                        <p class="ay-img-description js-ay-img-description"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_desc'];?>
</p>
                                        <div class="ay-label-img">
                                            <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                            <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                            <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                        <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                    </div>
                    <?php if ($_smarty_tpl->tpl_vars['lists']->value[1]['more_url']) {?>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['lists']->value[1]['more_url'];?>
" class="more-btn"><span>查看更多<i></i></span></a>
                    <?php }?>
                    <?php } else { ?>
                    <!--无结果-->
                    <div class="serach_no"><span>抱歉，没找到符合条件的内容</span></div>
                    <div class="sear-box clearfix">
                        <div>如有其它疑问，请联系您的专属资讯小秘书：</div>
                        <p>
                            <?php if (!empty($_smarty_tpl->tpl_vars['P_UserType']->value) && in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2))) {?>
                            <a class="neiyeList fl js-contact-qq-btn" data-type="1" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-662</em></span>
                            <?php } else { ?>
                            <a class="neiyeList fl js-contact-qq-btn" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-500</em></span>
                            <?php }?>
                        </p>
                    </div>
                    <?php }?>
                </div>
            <?php } elseif ($_smarty_tpl->tpl_vars['iColumnPid']->value == "2") {?>
            <!-- 流行分析 -->
                <div class="trend_box box">
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[2]['data'])) {?>
                    <div class="new_trend clearfix" style="padding-top:0;">
                        <?php
$_from = $_smarty_tpl->tpl_vars['lists']->value[2]['data'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                        <?php if ($_smarty_tpl->tpl_vars['item']->value['sign'] == 1) {?>
                        <!--主报告-->
                        <div class="trend_item js-analysis-main">
                            <!--分数-->
                            <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" data-score="<?php echo $_smarty_tpl->tpl_vars['item']->value['score'];?>
" target="_blank" class="trend_bg">
                                <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                            </a>
                            <div class="trend_box">
                                <div class="trend_text">
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="clearfix">
                                        <p class="ay-time clearfix">
                                            <span class="fl"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['publish_time'],'%Y-%m-%d');?>
</span>
                                            <span class="fr">浏览（<?php echo $_smarty_tpl->tpl_vars['item']->value['iViewCount'];?>
）</span>
                                        </p>
                                        <p class="ay-title"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_title'];?>
</p>
                                    </a>
                                    <p class="ay-img-description js-ay-img-description"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_desc'];?>
</p>
                                    <div class="ay-label-img">
                                        <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                        <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['item']->value['sign'] == 2) {?>
                        <!--子报告全部取到-->
                        <div class="trend_item js-analysis-main">
                            <!--分数-->
                            <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" data-score="<?php echo $_smarty_tpl->tpl_vars['item']->value['score'];?>
" target="_blank" class="trend_bg">
                                <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                            </a>
                            <div class="trend_box">
                                <div class="trend_text">
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="clearfix">
                                        <p class="ay-time clearfix">
                                            <span class="fl"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['publish_time'],'%Y-%m-%d');?>
</span>
                                            <span class="fr">浏览（<?php echo $_smarty_tpl->tpl_vars['item']->value['iViewCount'];?>
）</span>
                                        </p>
                                        <p class="ay-title fu-title">
                                            <span><?php echo $_smarty_tpl->tpl_vars['item']->value['minor_title'];?>
</span>
                                            <label class="js-major"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_title'];?>
</label>
                                        </p>
                                    </a>
                                    <p class="ay-img-description js-ay-img-description"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_desc'];?>
</p>
                                    <div class="ay-label-img">
                                        <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                        <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php }?>
                        <?php if ($_smarty_tpl->tpl_vars['item']->value['sign'] == 3) {?>
                        <!--子报告封面图取得是主报告的封面图-->
                        <div class="trend_item js-analysis-main">
                            <!--分数-->
                            <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" data-score="<?php echo $_smarty_tpl->tpl_vars['item']->value['score'];?>
" target="_blank" class="trend_bg">
                                <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                                <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == '1' || $_smarty_tpl->tpl_vars['P_UserType']->value == '2' || $_smarty_tpl->tpl_vars['P_UserType']->value == '3') {?>
                                <div class="bg"><span><?php echo $_smarty_tpl->tpl_vars['item']->value['minor_title'];?>
</span></div>
                                <?php }?>
                            </a>
                            <div class="trend_box">
                                <div class="trend_text">
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="clearfix">
                                        <p class="ay-time clearfix">
                                            <span class="fl"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['publish_time'],'%Y-%m-%d');?>
</span>
                                            <span class="fr">浏览（<?php echo $_smarty_tpl->tpl_vars['item']->value['iViewCount'];?>
）</span>
                                        </p>
                                        <!-- <p class="ay-title"><?php echo $_smarty_tpl->tpl_vars['item']->value['minor_title'];?>
</p> -->
                                        <p class="ay-title fu-title">
                                            <span><?php echo $_smarty_tpl->tpl_vars['item']->value['minor_title'];?>
</span>
                                            <label class="js-major"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_title'];?>
</label>
                                        </p>
                                    </a>
                                    <p class="ay-img-description js-ay-img-description"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_desc'];?>
</p>
                                    <div class="ay-label-img">
                                        <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                        <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php }?>
                        <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                    </div>
                    <?php if ($_smarty_tpl->tpl_vars['lists']->value[2]['more_url']) {?>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['lists']->value[2]['more_url'];?>
" class="more-btn"><span>查看更多<i></i></span></a>
                    <?php }?>
                    <?php } else { ?>
                    <!--无结果-->
                    <div class="serach_no"><span>抱歉，没找到符合条件的内容</span></div>
                    <div class="sear-box clearfix">
                        <div>如有其它疑问，请联系您的专属资讯小秘书：</div>
                        <p>
                            <?php if (!empty($_smarty_tpl->tpl_vars['P_UserType']->value) && in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2))) {?>
                            <a class="neiyeList fl js-contact-qq-btn" data-type="1" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-662</em></span>
                            <?php } else { ?>
                            <a class="neiyeList fl js-contact-qq-btn" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-500</em></span>
                            <?php }?>
                        </p>
                    </div>
                    <?php }?>
                </div>
            <?php } elseif ($_smarty_tpl->tpl_vars['iColumnPid']->value == "3") {?>
            <!-- T台 -->
                <div class="station_box box">
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[3]['data'])) {?>
                    <div class="new_station clearfix lazyload" style="padding-top:0;">
                        <?php
$_from = $_smarty_tpl->tpl_vars['lists']->value[3]['data'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" class="station_item js_station_item" target="_blank">
                            <div class="cover">
                                <?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>

                            </div>
                            <div class="station">
                                <div class="station_title js-station_title"><?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
</div>
                                <div class="details">
                                    <span>设计师：<?php echo $_smarty_tpl->tpl_vars['item']->value['brand_name'];?>
</span>
                                    <span>时间：<?php echo $_smarty_tpl->tpl_vars['item']->value['seasonName'];?>
</span>
                                    <span>地点：<?php echo $_smarty_tpl->tpl_vars['item']->value['regionName'];?>
</span>
                                </div>
                                <span class="time"><?php echo $_smarty_tpl->tpl_vars['item']->value['publish_time'];?>
</span>
                                <span class="browse">浏览（<?php echo $_smarty_tpl->tpl_vars['item']->value['view_count'];?>
）</span>
                            </div>
                        </a>
                        <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                    </div>
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[3]['more_url'])) {?>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['lists']->value[3]['more_url'];?>
" class="more-btn"><span>查看更多<i></i></span></a>
                    <?php }?>
                    <?php } else { ?>
                    <div class="serach_no"><span>抱歉，没找到符合条件的内容</span></div>
                    <div class="sear-box clearfix">
                        <div>如有其它疑问，请联系您的专属资讯小秘书：</div>
                        <p>
                            <?php if (!empty($_smarty_tpl->tpl_vars['P_UserType']->value) && in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2))) {?>
                            <a class="neiyeList fl js-contact-qq-btn" data-type="1" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-662</em></span>
                            <?php } else { ?>
                            <a class="neiyeList fl js-contact-qq-btn" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-500</em></span>
                            <?php }?>
                        </p>
                    </div>
                    <?php }?>
                </div>
            <?php } elseif ($_smarty_tpl->tpl_vars['iColumnPid']->value == "82") {?>
            <!-- 图案 -->
                <div class="pattern_box box js-box-style">
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[82]['data'])) {?>
                        <div class="new_pattern clearfix" style="padding-top:0;">
                            <?php
$_from = $_smarty_tpl->tpl_vars['lists']->value[82]['data'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                            <div data-url="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" class="pattern_item js-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="img_box">
                                    <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                                    <div class="time"></div>
                                    <span><?php echo $_smarty_tpl->tpl_vars['item']->value['publish_time'];?>
</span>
                                    <div class="shuiyin"></div>
                                </a>
                                <div class="js-page-text">
                                    <div class="label js-label">
                                        <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                        <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                    </div>
                                </div>
                            </div>
                            <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                        </div>
                        <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[82]['more_url'])) {?>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['lists']->value[82]['more_url'];?>
" target="_blank" class="more-btn"><span>查看更多<i></i></span></a>
                        <?php }?>
                    <?php } else { ?>
                    <div class="serach_no"><span>抱歉，没找到符合条件的内容</span></div>
                    <div class="sear-box clearfix">
                        <div>如有其它疑问，请联系您的专属资讯小秘书：</div>
                        <p>
                            <?php if (!empty($_smarty_tpl->tpl_vars['P_UserType']->value) && in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2))) {?>
                            <a class="neiyeList fl js-contact-qq-btn" data-type="1" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-662</em></span>
                            <?php } else { ?>
                            <a class="neiyeList fl js-contact-qq-btn" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-500</em></span>
                            <?php }?>
                        </p>
                    </div>
                    <?php }?>
                </div>
            <?php } elseif ($_smarty_tpl->tpl_vars['iColumnPid']->value == "120") {?>
            <!-- 图案 -->
                <div class="pattern_box box js-box-style">
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[120]['data'])) {?>
                    <div class="new_pattern clearfix" style="padding-top:0;">
                        <?php
$_from = $_smarty_tpl->tpl_vars['lists']->value[120]['data'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                        <div data-url="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" class="pattern_item js-item">
                            <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="img_box">
                                <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                                <div class="time"></div>
                                <span><?php echo $_smarty_tpl->tpl_vars['item']->value['publish_time'];?>
</span>
                                <div class="shuiyin"></div>
                            </a>
                            <div class="js-page-text">
                                <div class="label js-label">
                                    <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                    <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                </div>
                            </div>
                        </div>
                        <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                    </div>
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[82]['more_url'])) {?>
                    <a href="<?php echo $_smarty_tpl->tpl_vars['lists']->value[82]['more_url'];?>
" target="_blank" class="more-btn"><span>查看更多<i></i></span></a>
                    <?php }?>
                    <?php } else { ?>
                    <div class="serach_no"><span>抱歉，没找到符合条件的内容</span></div>
                    <div class="sear-box clearfix">
                        <div>如有其它疑问，请联系您的专属资讯小秘书：</div>
                        <p>
                            <?php if (!empty($_smarty_tpl->tpl_vars['P_UserType']->value) && in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2))) {?>
                            <a class="neiyeList fl js-contact-qq-btn" data-type="1" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-662</em></span>
                            <?php } else { ?>
                            <a class="neiyeList fl js-contact-qq-btn" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-500</em></span>
                            <?php }?>
                        </p>
                    </div>
                    <?php }?>
                </div>
            <?php } elseif ($_smarty_tpl->tpl_vars['iColumnPid']->value == "71") {?>
                <!-- 广告大片 -->
                <div class="ad_box box">
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[71]['data'])) {?>
                    <div class="new_ad clearfix" style="padding-top:0;">
                            <?php
$_from = $_smarty_tpl->tpl_vars['lists']->value[71]['data'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                        <div class="ad_item" target="_blank">
                            <div class="img_box">
                                <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
" />
                                <div class="hover-link">
                                    <div class="<?php if ($_smarty_tpl->tpl_vars['item']->value['sBuyingLinks']) {?>buy-link<?php }?>">
                                        <?php if ($_smarty_tpl->tpl_vars['item']->value['iPreviewMode'] == 1) {?>
                                            <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 5) {?>
                                            <a class="wz js-general-user-status" href="javascript:void(0);" title="免费预览"><i></i>免费预览</a>
                                            <?php } else { ?>
                                            <a itemprop="full browser" class="wz" href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank"><i></i>免费预览</a>
                                            <?php }?>
                                        <?php } else { ?>
                                            <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 5) {?>
                                            <a class="wz js-general-user-status" href="javascript:void(0);" title="完整浏览"><i></i>完整浏览</a>
                                            <?php } else { ?>
                                            <a itemprop="full browser" class="wz" href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank"><i></i>完整浏览</a>
                                            <?php }?>
                                        <?php }?>
                                        <?php if ($_smarty_tpl->tpl_vars['item']->value['sBuyingLinks']) {?>
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['sBuyingLinks'];?>
" class="buy" target="_blank" title="购买"><i></i>购买</a>
                                        <?php }?>
                                    </div>
                                    <p class="update-time"><img src="<?php echo STATIC_URL2;?>
/global/images/lists/books-list/time.png"><?php echo $_smarty_tpl->tpl_vars['item']->value['publish_time'];?>
</p>
                                </div>
                            </div>
                            <div class="ad">
                                <span class="js-ad-span"><?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
</span>
                            </div>
                        </div>
                        <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                    </div>
                      <?php if ($_smarty_tpl->tpl_vars['lists']->value[71]['more_url']) {?>
                       <a href="<?php echo $_smarty_tpl->tpl_vars['lists']->value[71]['more_url'];?>
" class="more-btn"><span>查看更多<i></i></span></a>
                      <?php }?>
                    <?php } else { ?>
                    <div class="serach_no"><span>抱歉，没找到符合条件的内容</span></div>
                    <div class="sear-box clearfix">
                        <div>如有其它疑问，请联系您的专属资讯小秘书：</div>
                        <p>
                            <?php if (!empty($_smarty_tpl->tpl_vars['P_UserType']->value) && in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2))) {?>
                            <a class="neiyeList fl js-contact-qq-btn" data-type="1" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-662</em></span>
                            <?php } else { ?>
                            <a class="neiyeList fl js-contact-qq-btn" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-500</em></span>
                            <?php }?>
                        </p>
                    </div>
                    <?php }?>
                </div>
            <?php } else { ?>
            <!-- 全部 -->
                <?php if (empty($_smarty_tpl->tpl_vars['lists']->value[4]['data']) && empty($_smarty_tpl->tpl_vars['lists']->value[1]['data']) && empty($_smarty_tpl->tpl_vars['lists']->value[2]['data']) && empty($_smarty_tpl->tpl_vars['lists']->value[82]['data']) && empty($_smarty_tpl->tpl_vars['lists']->value[3]['data']) && empty($_smarty_tpl->tpl_vars['lists']->value[71]['data']) && empty($_smarty_tpl->tpl_vars['moreLists']->value)) {?>
                <!-- 全部 无结果 -->
                    <div class="serach_no"><span>抱歉，没找到符合条件的内容</span></div>
                    <div class="sear-box clearfix" style="margin-bottom: 158px;">
                        <div>如有其它疑问，请联系您的专属资讯小秘书：</div>
                        <p>
                            <?php if (!empty($_smarty_tpl->tpl_vars['P_UserType']->value) && in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2))) {?>
                            <a class="neiyeList fl js-contact-qq-btn" data-type="1" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-662</em></span>
                            <?php } else { ?>
                            <a class="neiyeList fl js-contact-qq-btn" href="javascript:void(0);" title="QQ咨询">QQ咨询</a>
                            <span class="neiyeList fr">小秘书热线：<em>4008-210-500</em></span>
                            <?php }?>
                        </p>
                    </div>
                <?php } else { ?>
                <!-- 全部 有结果 -->
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[4]['data'])) {?>
                    <!--款式-->
                    <div class="style_box box js-box-style">
                        <div class="main-title">款式<span>STYLE</span></div>
                        <div class="style_list clearfix">
                            <?php
$_from = $_smarty_tpl->tpl_vars['lists']->value[4]['data'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                            <div class="style_item js-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="img_box">
                                    <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                                    <div class="time"></div>
                                    <span><?php echo $_smarty_tpl->tpl_vars['item']->value['publish_time'];?>
</span>
                                    <div class="shuiyin"></div>
                                </a>
                                <div class="js-page-text">
                                    <div class="label js-label">
                                        <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                        <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                    </div>
                                </div>
                            </div>
                            <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                        </div>
                        <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[4]['more_url'])) {?>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['lists']->value[4]['more_url'];?>
" class="more-btn"><span>查看更多<i></i></span></a>
                        <?php }?>
                    </div>
                    <?php }?>
                    <!--趋势解读-->
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[1]['data'])) {?>
                    <div class="trend_box box">
                        <div class="main-title">趋势解读<span>FORECAST</span></div>
                        <div class="new_trend clearfix">
                            <?php
$_from = $_smarty_tpl->tpl_vars['lists']->value[1]['data'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                                <?php if ($_smarty_tpl->tpl_vars['item']->value['sign'] == 1) {?>
                                <!--主报告-->
                                <div class="trend_item js-analysis-main">
                                    <!--分数-->
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" data-score="<?php echo $_smarty_tpl->tpl_vars['item']->value['score'];?>
" target="_blank" class="trend_bg">
                                        <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                                    </a>
                                    <div class="trend_box">
                                        <div class="trend_text">
                                            <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="clearfix">
                                                <p class="ay-time clearfix">
                                                    <span class="fl"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['publish_time'],'%Y-%m-%d');?>
</span>
                                                    <span class="fr">浏览（<?php echo $_smarty_tpl->tpl_vars['item']->value['iViewCount'];?>
）</span>
                                                </p>
                                                <p class="ay-title"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_title'];?>
</p>
                                            </a>
                                            <p class="ay-img-description js-ay-img-description"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_desc'];?>
</p>
                                            <div class="ay-label-img">
                                                <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                                <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                                <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['item']->value['sign'] == 2) {?>
                                <!--子报告全部取到-->
                                <div class="trend_item js-analysis-main">
                                    <!--分数-->
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" data-score="<?php echo $_smarty_tpl->tpl_vars['item']->value['score'];?>
" target="_blank" class="trend_bg">
                                        <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                                    </a>
                                    <div class="trend_box">
                                        <div class="trend_text">
                                            <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="clearfix">
                                                <p class="ay-time clearfix">
                                                    <span class="fl"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['publish_time'],'%Y-%m-%d');?>
</span>
                                                    <span class="fr">浏览（<?php echo $_smarty_tpl->tpl_vars['item']->value['iViewCount'];?>
）</span>
                                                </p>
                                                <p class="ay-title fu-title">
                                                    <span><?php echo $_smarty_tpl->tpl_vars['item']->value['minor_title'];?>
</span>
                                                    <label class="js-major"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_title'];?>
</label>
                                                </p>
                                            </a>
                                            <p class="ay-img-description js-ay-img-description"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_desc'];?>
</p>
                                            <div class="ay-label-img">
                                                <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                                <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                                <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                                <?php if ($_smarty_tpl->tpl_vars['item']->value['sign'] == 3) {?>
                                <!--子报告封面图取得是主报告的封面图-->
                                <div class="trend_item js-analysis-main">
                                    <!--分数-->
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" data-score="<?php echo $_smarty_tpl->tpl_vars['item']->value['score'];?>
" target="_blank" class="trend_bg">
                                        <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                                        <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == '1' || $_smarty_tpl->tpl_vars['P_UserType']->value == '2' || $_smarty_tpl->tpl_vars['P_UserType']->value == '3') {?>
                                        <div class="bg"><span><?php echo $_smarty_tpl->tpl_vars['item']->value['minor_title'];?>
</span></div>
                                        <?php }?>
                                    </a>
                                    <div class="trend_box">
                                        <div class="trend_text">
                                            <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="clearfix">
                                                <p class="ay-time clearfix">
                                                    <span class="fl"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['publish_time'],'%Y-%m-%d');?>
</span>
                                                    <span class="fr">浏览（<?php echo $_smarty_tpl->tpl_vars['item']->value['iViewCount'];?>
）</span>
                                                </p>
                                                <p class="ay-title fu-title">
                                                    <span><?php echo $_smarty_tpl->tpl_vars['item']->value['minor_title'];?>
</span>
                                                    <label class="js-major"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_title'];?>
</label>
                                                </p>
                                            </a>
                                            <p class="ay-img-description js-ay-img-description"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_desc'];?>
</p>
                                            <div class="ay-label-img">
                                                <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                                <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                                <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                            <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                        </div>
                        <?php if ($_smarty_tpl->tpl_vars['lists']->value[1]['more_url']) {?>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['lists']->value[1]['more_url'];?>
" class="more-btn"><span>查看更多<i></i></span></a>
                        <?php }?>
                    </div>
                    <?php }?>
                    <!--流行分析-->
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[2]['data'])) {?>
                    <div class="trend_box box">
                        <div class="main-title">流行分析<span>ANALYSIS</span></div>
                        <div class="new_trend clearfix">
                            <?php
$_from = $_smarty_tpl->tpl_vars['lists']->value[2]['data'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                            <?php if ($_smarty_tpl->tpl_vars['item']->value['sign'] == 1) {?>
                            <!--主报告-->
                            <div class="trend_item js-analysis-main">
                                <!--分数-->
                                <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" data-score="<?php echo $_smarty_tpl->tpl_vars['item']->value['score'];?>
" target="_blank" class="trend_bg">
                                    <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                                </a>
                                <div class="trend_box">
                                    <div class="trend_text">
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="clearfix">
                                            <p class="ay-time clearfix">
                                                <span class="fl"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['publish_time'],'%Y-%m-%d');?>
</span>
                                                <span class="fr">浏览（<?php echo $_smarty_tpl->tpl_vars['item']->value['iViewCount'];?>
）</span>
                                            </p>
                                            <p class="ay-title"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_title'];?>
</p>
                                        </a>
                                        <p class="ay-img-description js-ay-img-description"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_desc'];?>
</p>
                                        <div class="ay-label-img">
                                            <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                            <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                            <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['item']->value['sign'] == 2) {?>
                            <!--子报告全部取到-->
                            <div class="trend_item js-analysis-main">
                                <!--分数-->
                                <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" data-score="<?php echo $_smarty_tpl->tpl_vars['item']->value['score'];?>
" target="_blank" class="trend_bg">
                                    <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                                </a>
                                <div class="trend_box">
                                    <div class="trend_text">
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="clearfix">
                                            <p class="ay-time clearfix">
                                                <span class="fl"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['publish_time'],'%Y-%m-%d');?>
</span>
                                                <span class="fr">浏览（<?php echo $_smarty_tpl->tpl_vars['item']->value['iViewCount'];?>
）</span>
                                            </p>
                                            <p class="ay-title fu-title">
                                                <span><?php echo $_smarty_tpl->tpl_vars['item']->value['minor_title'];?>
</span>
                                                <label class="js-major"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_title'];?>
</label>
                                            </p>
                                        </a>
                                        <p class="ay-img-description js-ay-img-description"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_desc'];?>
</p>
                                        <div class="ay-label-img">
                                            <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                            <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                            <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                            <?php if ($_smarty_tpl->tpl_vars['item']->value['sign'] == 3) {?>
                            <!--子报告封面图取得是主报告的封面图-->
                            <div class="trend_item js-analysis-main">
                                <!--分数-->
                                <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" data-score="<?php echo $_smarty_tpl->tpl_vars['item']->value['score'];?>
" target="_blank" class="trend_bg">
                                    <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                                    <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == '1' || $_smarty_tpl->tpl_vars['P_UserType']->value == '2' || $_smarty_tpl->tpl_vars['P_UserType']->value == '3') {?>
                                    <div class="bg"><span><?php echo $_smarty_tpl->tpl_vars['item']->value['minor_title'];?>
</span></div>
                                    <?php }?>
                                </a>
                                <div class="trend_box">
                                    <div class="trend_text">
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="clearfix">
                                            <p class="ay-time clearfix">
                                                <span class="fl"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['item']->value['publish_time'],'%Y-%m-%d');?>
</span>
                                                <span class="fr">浏览（<?php echo $_smarty_tpl->tpl_vars['item']->value['iViewCount'];?>
）</span>
                                            </p>
                                            <!-- <p class="ay-title"><?php echo $_smarty_tpl->tpl_vars['item']->value['minor_title'];?>
</p> -->
                                            <p class="ay-title fu-title">
                                                <span><?php echo $_smarty_tpl->tpl_vars['item']->value['minor_title'];?>
</span>
                                                <label class="js-major"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_title'];?>
</label>
                                            </p>
                                        </a>
                                        <p class="ay-img-description js-ay-img-description"><?php echo $_smarty_tpl->tpl_vars['item']->value['major_desc'];?>
</p>
                                        <div class="ay-label-img">
                                            <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                            <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                            <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                            <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                        </div>
                        <?php if ($_smarty_tpl->tpl_vars['lists']->value[2]['more_url']) {?>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['lists']->value[2]['more_url'];?>
" class="more-btn"><span>查看更多<i></i></span></a>
                        <?php }?>
                    </div>
                    <?php }?>
                    
                    <!--图案-->
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[82]['data'])) {?>
                    <div class="pattern_box box js-box-style">
                        <div class="main-title">图案<span>PATTERN</span></div>
                        <div class="new_pattern clearfix">
                            <?php
$_from = $_smarty_tpl->tpl_vars['lists']->value[82]['data'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                            <div data-url="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" class="pattern_item js-item">
                                <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank" class="img_box">
                                    <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
">
                                    <div class="time"></div>
                                    <span><?php echo $_smarty_tpl->tpl_vars['item']->value['publish_time'];?>
</span>
                                    <div class="shuiyin"></div>
                                </a>
                                <div class="js-page-text">
                                    <div class="label js-label">
                                        <?php
$_from = $_smarty_tpl->tpl_vars['item']->value['labels'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['val'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['val']->_loop = false;
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['__foreach_loop'] = new Smarty_Variable(array('total' => $_smarty_tpl->_count($_from), 'iteration' => 0));
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['val']->value) {
$_smarty_tpl->tpl_vars['val']->_loop = true;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration']++;
$_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] = $_smarty_tpl->tpl_vars['__foreach_loop']->value['iteration'] == $_smarty_tpl->tpl_vars['__foreach_loop']->value['total'];
$foreach_val_Sav = $_smarty_tpl->tpl_vars['val'];
?>
                                        <a href="<?php echo $_smarty_tpl->tpl_vars['val']->value['lLink'];?>
" target="_blank"><?php echo $_smarty_tpl->tpl_vars['val']->value['name'];?>
</a><?php if (!(isset($_smarty_tpl->tpl_vars['__foreach_loop']->value['last']) ? $_smarty_tpl->tpl_vars['__foreach_loop']->value['last'] : null)) {?><s></s><?php }?>
                                        <?php
$_smarty_tpl->tpl_vars['val'] = $foreach_val_Sav;
}
?>
                                    </div>
                                </div>
                            </div>
                            <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                        </div>
                        <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[82]['more_url'])) {?>
                          <a href="<?php echo $_smarty_tpl->tpl_vars['lists']->value[82]['more_url'];?>
" class="more-btn"><span>查看更多<i></i></span></a>
                        <?php }?>
                    </div>
                    <?php }?>
                    <!-- T台 -->
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[3]['data'])) {?>
                    <div class="station_box box">
                        <div class="main-title">T台<span>T STATION</span></div>
                        <div class="new_station clearfix lazyload">
                            <?php
$_from = $_smarty_tpl->tpl_vars['lists']->value[3]['data'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                            <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" class="station_item js_station_item" target="_blank">
                                <!--<img src="">-->
                                <div class="cover">
                                    <?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>

                                </div>
                                <div class="station">
                                    <div class="station_title js-station_title"><?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
</div>
                                    <div class="details">
                                        <span>设计师：<?php echo $_smarty_tpl->tpl_vars['item']->value['brand_name'];?>
</span>
                                        <span>时间：<?php echo $_smarty_tpl->tpl_vars['item']->value['seasonName'];?>
</span>
                                        <span>地点：<?php echo $_smarty_tpl->tpl_vars['item']->value['regionName'];?>
</span>
                                    </div>
                                    <span class="time"><?php echo $_smarty_tpl->tpl_vars['item']->value['publish_time'];?>
</span>
                                    <span class="browse">浏览（<?php echo $_smarty_tpl->tpl_vars['item']->value['view_count'];?>
）</span>
                                </div>
                            </a>
                            <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                        </div>
                        <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[3]['more_url'])) {?>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['lists']->value[3]['more_url'];?>
" class="more-btn"><span>查看更多<i></i></span></a>
                        <?php }?>
                    </div>
                    <?php }?>
                    <!-- 广告大片 -->
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[71]['data'])) {?>
                    <div class="ad_box box">
                        <div class="main-title">广告大片<span>BIOCKBUSTER</span></div>
                        <div class="new_ad hei clearfix">
                            <?php
$_from = $_smarty_tpl->tpl_vars['lists']->value[71]['data'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                            <div class="ad_item" target="_blank">
                                <div class="img_box">
                                    <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
" />
                                    <div class="hover-link">
                                        <div class="<?php if ($_smarty_tpl->tpl_vars['item']->value['sBuyingLinks']) {?>buy-link<?php }?>">
                                            <?php if ($_smarty_tpl->tpl_vars['item']->value['iPreviewMode'] == 1) {?>
                                                <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 5) {?>
                                                <a class="wz js-general-user-status" href="javascript:void(0);" title="免费预览"><i></i>免费预览</a>
                                                <?php } else { ?>
                                                <a itemprop="full browser" class="wz" href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank"><i></i>免费预览</a>
                                                <?php }?>
                                            <?php } else { ?>
                                                <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 5) {?>
                                                <a class="wz js-general-user-status" href="javascript:void(0);" title="完整浏览"><i></i>完整浏览</a>
                                                <?php } else { ?>
                                                <a itemprop="full browser" class="wz" href="<?php echo $_smarty_tpl->tpl_vars['item']->value['detailUrl'];?>
" target="_blank"><i></i>完整浏览</a>
                                                <?php }?>
                                            <?php }?>
                                            <?php if ($_smarty_tpl->tpl_vars['item']->value['sBuyingLinks']) {?>
                                            <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['sBuyingLinks'];?>
" class="buy" target="_blank" title="购买"><i></i>购买</a>
                                            <?php }?>
                                        </div>
                                        <p class="update-time"><img src="<?php echo STATIC_URL2;?>
/global/images/lists/books-list/time.png"><?php echo $_smarty_tpl->tpl_vars['item']->value['publish_time'];?>
</p>
                                    </div>
                                </div>
                                <div class="ad">
                                    <span class="js-ad-span"><?php echo $_smarty_tpl->tpl_vars['item']->value['title'];?>
</span>
                                </div>
                            </div>
                            <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                        </div>
                        <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value[71]['more_url'])) {?>
                        <a href="<?php echo $_smarty_tpl->tpl_vars['lists']->value[71]['more_url'];?>
" class="more-btn"><span>查看更多<i></i></span></a>
                        <?php }?>
                    </div>
                    <?php }?>
                    <!-- 动态加载部分 -->
                    <?php if (!empty($_smarty_tpl->tpl_vars['lists']->value['have_column_show'])) {?>
                    <div class="js_search_main">
                        
                    </div>
                    <div class="js-search-load" style="width: 100px;margin: 0 auto;text-align: center;margin-bottom: 60px;">
                        <div class="loader"></div>
                        <span style="display: block;font-size: 14px;">加载中...</span>
                    </div>
                    <?php }?>
                <?php }?>
                <!-- 更多内容 start -->
                <?php if ($_smarty_tpl->tpl_vars['moreLists']->value) {?>
                  <div class="more_box box">
                    <div class="main-title">更多内容<span>MORE CONTENT</span></div>
                    <div class="more_main">
                        <i class="i-left"></i>
                        <div class="main-ul">
                            <ul class="clearfix js-more">
                                <?php
$_from = $_smarty_tpl->tpl_vars['moreLists']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$_smarty_tpl->tpl_vars['item'] = new Smarty_Variable;
$_smarty_tpl->tpl_vars['item']->_loop = false;
foreach ($_from as $_smarty_tpl->tpl_vars['item']->value) {
$_smarty_tpl->tpl_vars['item']->_loop = true;
$foreach_item_Sav = $_smarty_tpl->tpl_vars['item'];
?>
                                <li>
                                    <a href="<?php echo $_smarty_tpl->tpl_vars['item']->value['url'];?>
"  target="_blank">
                                        <img src="<?php echo $_smarty_tpl->tpl_vars['item']->value['cover'];?>
"/>
                                        <div class="more-text">
                                            <span><?php echo $_smarty_tpl->tpl_vars['item']->value['column_name'];?>
</span>
                                            <div class="amount"><span></span><?php echo $_smarty_tpl->tpl_vars['item']->value['total'];
echo $_smarty_tpl->tpl_vars['item']->value['type'];?>
<span></span></div>
                                            <div class="more">查看全部</div>
                                        </div>
                                    </a>
                                </li>
                                <?php
$_smarty_tpl->tpl_vars['item'] = $foreach_item_Sav;
}
?>
                            </ul>
                        </div>
                        <i class="i-right"></i>
                  </div>
                </div>
                <?php }?>
                <!--/全部 end-->
                <!--/tab end-->
            <?php }?>
            <!-- 分页 -->
            <?php if (in_array($_smarty_tpl->tpl_vars['iColumnPid']->value,array(1,2,4,82,120,3,71))) {?>
            <div class="rightc_bottom">
                <div class="pages">
                    <?php echo $_smarty_tpl->tpl_vars['pageHtml']->value;?>

                </div>
            </div>
            <?php }?>   
        </div>
        <!-- 无权限 -->
        <div class="serach_vip_bg js-serach-vip-bg" style="display: none;"></div>
        <div class="serach_vip js-serach-vip" style="display: none;">
            <!-- 普通用户 -->
            <div class="members js-type4" style="display:none;">
                <span class="members_txt">VIP专享内容<br/>升级后，即可查看更多详情内容</span>
                <a href="javascript:void(0);" class="members_vip js-contact-qq-btn">咨询了解VIP，会员体验畅通无阻</a>
                <span class="members_phone">电话咨询 <span>4008-210-500</span>
            </div>
            <!-- 游客 -->
            <div class="members js-type5" style="display:none;">
                <span class="members_txt">注册登录后，将呈现更多精彩内容</span>
                <a href="/member/register/" target="_blank" class="members_vip">立即注册体验</a>
                <a href="javascript:void(0);" class="members_phone loginLayer">登录后查看更多</a>
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
                    <div class="code_btn js-click-btn" data-cla=".js-puzzle-drag-content5" data-stu="1">点击按钮完成图形验证</div>
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
    <!-- 权限弹出层 -->
    <div class="general-user-info-fixbox js-general-user-info-fixbox">
        <div class="contentW">
            <button title="关闭"></button>  
            <h3>这是品牌产品系列展示，</h3>
            <p>全球一线品牌、市场热门品牌、新锐设计师品牌，时尚大片品牌灵魂与当季搭配展示</p>
            <a class="js-contact-qq-btn" href="javascript:void(0)" title="在线客服">咨询了解VIP，会员体验畅通无阻</a>
            <a href="/member/pagelogin/" title="立即登录">登录/注册 发现免费推荐</a>
            <span>电话咨询 <span>4008-210-500</span></span>
        </div>
    </div>
</div>

    <!-- main end -->

    <!-- footer start -->
	
		<?php /*  Call merged included template "footer.html" */
echo $_smarty_tpl->getInlineSubTemplate("footer.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '11849929265ea691e1823997_85612409', 'content_5ea691e1822ad9_98096162');
/*  End of included template "footer.html" */?>

	
    <!-- footer end -->

    <!-- other dom -->
    
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
$_smarty_tpl->properties['nocache_hash'] = '8519447295ea691e04d2527_22466053';
?>

<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL1;?>
/global/js/fashion/search.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL2;?>
/global/js/fashion/index.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL2;?>
/global/js/fashion/work.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/fashion/global.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="<?php echo STATIC_URL3;?>
/global/js/fashion/util.pop3.js?<?php echo STATIC_CHANGE_TIME;?>
"><?php echo '</script'; ?>
>

<?php echo '<script'; ?>
>
    template.helper('dateFormat', function (date, format) {
        date = new Date(date);
        var map = {
            "M": date.getMonth() + 1, //月份
            "d": date.getDate(), //日
            "h": date.getHours(), //小时
            "m": date.getMinutes(), //分
            "s": date.getSeconds(), //秒
            "q": Math.floor((date.getMonth() + 3) / 3), //季度
            "S": date.getMilliseconds() //毫秒
        };
        format = format.replace(/([yMdhmsqS])+/g, function(all, t){
            var v = map[t];
            if(v !== undefined){
                if(all.length > 1){
                    v = '0' + v;
                    v = v.substr(v.length-2);
                }
                return v;
            }
            else if(t === 'y'){
                return (date.getFullYear() + '').substr(4 - all.length);
            }
            return all;
        });
        return format;
    });
    function scrollFun(){
        var scroll_t = $(window).scrollTop()||0;
        var client_len = $(".search_cont_top").offset().top+32;
        var head_slide=$('.con_width').offset().top+210;
        if (head_slide - scroll_t <= 0) {
            $('.js-head-slide').css('visibility','visible');
            $('.js-my-recommend-nav').css('position','fixed');
        }else{
            $('.js-head-slide').css('visibility','hidden');
            $('.js-my-recommend-nav').css('position','absolute');
        }
    }
    $(window).on("scroll",function(){
        scrollFun();
    });
    scrollFun();
    
    $(function(){
        $(".lazyload img").lazyload({effect : "fadeIn"});
        // 标签显示全部
        var style_li = $(".style_main li");
        style_li.on('mouseenter mouseleave',function(e){
            var label_position=$(this).find(".style-main-position")
            var cur_height=label_position.css('height');
            label_position.css('height','auto');
            var animate_height=label_position.css('height');
            label_position.css('height',cur_height);
            if(e.type=="mouseenter"){
                label_position.animate({height:animate_height},100);
            }else{
                label_position.animate({height:'53px'},50);
            }
        });

    });
    

    // 原本的缺少-
    oCommon.getDelGenIndInfo = function () {
        var url = location.href;
        var genPattern = /gen_\d+-?/;
        var indPattern = /ind_\d+-?/;
        if (genPattern.test(url)) {
            url = url.replace(genPattern, '');
        }
        if (indPattern.test(url)) {
            url = url.replace(indPattern, '');
        }
        url = url.replace(/\/\/\?/, '/?');
        return url;
    }
    $(".search_cont_tishi").on("click","#click_here",function(){
        $.cookie('gender', "", { 'domain':'.pop-fashion.com', 'path':'/', 'expires': -1 });
        $.cookie('industry', "", { 'domain':'.pop-fashion.com', 'path':'/', 'expires': -1 });
        location.href = oCommon.getDelGenIndInfo();
    });

    $('.active_click').on('click',function(){
        window.open($(this).parent().prev().children('a').prop('href'));
    });
    function offScroll(){
        $('html, body').animate({
            scrollTop:0
        }, 'slow');
        document.documentElement.style.overflow='hidden';
        var move = function (e) {
            e.preventDefault && e.preventDefault();
            e.returnValue = false;
            e.stopPropagation && e.stopPropagation();
            return false;
        }
        var keyFunc = function (e) {
            if (37 <= e.keyCode && e.keyCode <= 40) {
                return move(e);
            }
        }
        document.body.οnkeydοwn = keyFunc;
    }
    var pid="<?php echo $_smarty_tpl->tpl_vars['iColumnPid']->value;?>
";
    var index="<?php echo $_smarty_tpl->tpl_vars['page']->value;?>
";
    index=parseInt(index);
    if(P_UserType == 5){
        if(pid != 'all'){
            offScroll();
            $('.js-type5').css('top','350px');
            $('.js-serach-vip-bg').show();
            $('.js-serach-vip').show();
            $('.js-type5').show();
            $('.shuiyin').show();
        }
    }else if(P_UserType == 4){
        if(index==1){
            $('.shuiyin').hide();
        }else if(index <= 5 && index > 1){
            $('.shuiyin').show();
        }else{
            offScroll();
            $('.js-type5').css('top','350px');
            $('.js-serach-vip-bg').show();
            $('.js-serach-vip-bg').addClass('Top');
            $('.serach_vip').addClass('Top');
            $('.js-serach-vip').show();
            $('.js-type4').show();
            $('.shuiyin').show();
        }
    }else{
        $('.js-serach-vip-bg').hide();
        $('.js-serach-vip').hide();
    }
    // 标签显示全部
    $(".js-box-style").on('mouseenter mouseleave','.js-item',function(e){
        var label_position=$(this).find(".js-label");
        var cur_height=label_position.css('height');
        label_position.css('height','auto');
        var animate_height=label_position.css('height');
        label_position.css('height',cur_height);
        var hei=parseInt(animate_height.substring(0,animate_height.indexOf('px')));
        if(hei<=48){
            animate_height="48px";
        }else if(hei >= 50){
            animate_height=(hei-2)+"px";
        }
        if(e.type=="mouseenter"){
            label_position.animate({
                height:animate_height
            },100);
        }else{
            label_position.animate({
                height:'48px'
            },50);
        }
    });

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
echo $_smarty_tpl->getInlineSubTemplate("common/common_script.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '12215875725ea691e1b88726_14429651', 'content_5ea691e1b87500_08425534');
/*  End of included template "common/common_script.html" */?>

	
</body>
</html>
<?php }
}
?><?php
/*%%SmartyHeaderCode:18086610445ea691e0909b25_13368303%%*/
if ($_valid && !is_callable('content_5ea691e09083f1_75996336')) {
function content_5ea691e09083f1_75996336 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '18086610445ea691e0909b25_13368303';
?>

<!--头部导航-->
<div class="dropdown-nav js-dropdown-nav" style="z-index: 100;">
    <div class="widthx80">
        <div class="nav-category clearfix">
            <dl class="first-dl">
                <dt><a href="/trends/" title="趋势解读">趋势解读</a></dt>
                <dd><a href="/trends/" title="全部">全部</a></dd>
                <dd><a href="/trends/color/" title="主题色彩">主题色彩</a></dd>
                <dd><a href="/trends/pattern/" title="图案趋势">图案趋势</a></dd>
                <dd><a href="/trends/material/" title="面辅料趋势">面辅料趋势</a></dd>
                <dd><a href="/trends/craft/" title="工艺趋势">工艺趋势</a></dd>
                <dd><a href="/trends/silhouette/" title="廓形趋势">廓形趋势</a></dd>
                <dd><a href="/trends/design/" title="企划/组货">企划/组货</a></dd>
                <dd><a href="/trends/capsules/" title="快反应">快反应</a></dd>
            </dl>
            <dl>
                <dt><a href="/analysis/" title="流行分析">流行分析</a></dt>
                <dd><a href="/analysis/" title="全部">全部</a></dd>
                <dd><a href="/analysis/tops/" title="爆款数据">爆款数据</a></dd>
                <dd><a href="/analysis/runways/" title="T台分析">T台分析</a></dd>
                <dd><a href="/analysis/shows/" title="订货会分析">订货会分析</a></dd>
                <dd><a href="/analysis/online/" title="设计师品牌分析">设计师品牌分析</a></dd>
                <dd><a href="/analysis/retail/" title="标杆品牌分析">标杆品牌分析</a></dd>
                <dd><a href="/analysis/market/" title="市场分析">市场分析</a></dd>
                <dd><a href="/analysis/fairs/" title="展会分析">展会分析</a></dd>
                <dd><a href="/analysis/trendsetters/" title="明星&街拍分析">明星&街拍分析</a></dd>
            </dl>
            <dl>
                <dt><a href="/styles/" title="款式">款式</a></dt>
                <dd><a href="/styles/" title="全部">全部</a></dd>
                <dd><a href="/styles/runways/" title="秀场提炼">秀场提炼</a></dd>
                <dd><a href="/styles/shows/" title="订货会精选">订货会精选</a></dd>
                <dd><a href="/styles/designerbrand/" title="设计师品牌">设计师品牌</a></dd>
                <dd><a href="/styles/online/" title="名牌精选">名牌精选</a></dd>
                <dd><a href="/styles/popular/" title="款式流行">款式流行</a></dd>
                <dd><a href="/styles/retail/" title="全球实拍">全球实拍</a></dd>
                <dd><a href="/styles/trendsetters/" title="明星/ins">明星/ins</a></dd>
                <dd><a href="/styles/streetsnaps/" title="街拍图库">街拍图库</a></dd>
            </dl>
            <dl>
                <dt><a href="/patterns/graphics/" title="图案">图案</a></dt>
                <dd><a href="/patterns/graphics/" title="图案素材">图案素材</a></dd>
                <dd><a href="/patterns/topbrands/" title="大牌花型">大牌花型</a></dd>
                <dd><a href="/patterns/specialtopicpatterns/" title="图案专题">图案专题</a></dd>
                <dd><a href="/patterns/technics/" title="图案工艺">图案工艺</a></dd>
                <dd><a href="/patterns/digitalprint/" title="数码云打印">数码云打印</a></dd>
            </dl>
            <dl>
                <dt><a href="/fabriczone/" title="面料">面料</a></dt>
                <dd><a href="/fabriczone/" title="全部">全部</a></dd>
                <dd><a href="/fabriczone/#trend" title="面料趋势">面料趋势</a></dd>
                <dd><a href="/fabriczone/#gallery" title="面料图库">面料图库</a></dd>
                <!--<dd><a href="/fabriczone/#book" title="面料书籍">面料书籍</a></dd>-->
            </dl>
            <dl>
                <dt><a href="/runways/" title="T台">T台</a></dt>
                <dd><a href="/runways/" title="全部">全部</a></dd>
                <dd><a href="/runways/reg_272/" title="巴黎时装周">巴黎时装周</a></dd>
                <dd><a href="/runways/reg_341/" title="纽约时装周">纽约时装周</a></dd>
                <dd><a href="/runways/reg_335/" title="米兰时装周">米兰时装周</a></dd>
                <dd><a href="/runways/reg_323/" title="伦敦时装周">伦敦时装周</a></dd>
                <dd><a href="/runways/reg_other/" title="其他">其他</a></dd>
            </dl>
            <dl>
                <dt><a href="/books/lookbook/" title="读物">读物</a></dt>
                <dd><a href="/books/collections/" title="单品合辑">单品合辑</a></dd>
                <!--<dd><a href="/books/store/" title="趋势手稿">趋势手稿</a></dd>-->
                <dd><a href="/books/fast/" title="快反应系列">快反应系列</a></dd>
                <dd><a href="/books/lookbook/" title="广告大片">广告大片</a></dd>
                <dd><a href="/books/ordermeeting/" title="订货会合辑">订货会合辑</a></dd>
                <dd><a href="/books/magazine/" title="流行画册">流行画册</a></dd>
            </dl>
            <dl>
                <dt><a href="/references/details/" title="素材">素材</a></dt>
                <dd><a href="/references/details/" title="款式细节">款式细节</a></dd>
                <dd><a href="/references/design/" title="款式手稿">款式手稿</a></dd>
                <dd><a href="/references/visual/" title="店铺陈列">店铺陈列</a></dd>
                <dd><a href="/references/fabricgallery/" title="展会面料">展会面料</a></dd>
                <dd><a href="/references/accessories/" title="服饰品">服饰品</a></dd>
                <dd><a href="/inspiration/" title="灵感源">灵感源</a></dd>
                <dd><a href="/inspiration/video/" title="灵感视频">灵感视频</a></dd>
            </dl>
            <dl>
                <dt><a href="https://appmfz0ensy5593.pc.xiaoe-tech.com/page/800948" target="_blank" title="在线课堂">在线课堂</a></dt>
                <dd><a href="https://appmfz0ensy5593.pc.xiaoe-tech.com/page/827920" target="_blank" title="行业课程">行业课程</a></dd>
                <dd><a href="https://appmfz0ensy5593.pc.xiaoe-tech.com/page/828792" target="_blank" title="趋势讲堂">趋势讲堂</a></dd>
            </dl>
            <dl>
                <dt><a class="no_hover" href="javascript:void(0)" title="研发资源">研发资源</a></dt>
                <dd><a href="/topic/20171129/" target="_blank" title="设界">设界</a></dd>
                <dd><a href="http://www.uliaobao.com/" target="_blank" title="优料宝">优料宝</a></dd>
                <dd><a href="/topic/20171208/" target="_blank" title="找料帮">找料帮</a></dd>
                <dd><a href="/activity/lists/" target="_blank" title="时尚商学院">时尚商学院</a></dd>
            </dl>
            <dl class="last-dl">
                <dt><a class="no_hover" href="javascript:void(0)" title="更多内容">更多内容</a></dt>
                <dd><a href="/item/" target="_blank" title="商城">商城</a></dd>
                <dd><a href="/topic/20180409/" title="POP观点">POP观点</a></dd>
                <dd><a href="/exclusive/" target="_blank" title="快反应专题">快反应专题</a></dd>
                <dd><a href="/customer/service/" target="_blank" title="客户服务">客户服务</a></dd>
                <dd><a href="javascript:void(0);" title="意见反馈" class="js-msg-feedback">意见反馈</a></dd>
                <!-- <dd><a href="http://www.pop136.com/pop_exe.php" target="_blank" title="客户端下载">客户端下载</a></dd> -->
            </dl>
        </div>
        <div class="dropdown-bottom-content clearfix">
            <div class="dropdown-area fl">
                <ul class="clearfix js-area-box">
                    <li><a href="javascript:void(0)" title="女装" data-id="2" data-key="gen"><span>女装</span></a></li>
                    <li><a href="javascript:void(0)" title="男装" data-id="1" data-key="gen"><span>男装</span></a></li>
                    <li><a href="javascript:void(0)" title="童装" data-id="5" data-key="gen"><span>童装</span></a></li>
                    <li class="area-fg"></li>
                    <li><a href="javascript:void(0)" title="毛衫" data-id="6" data-key="ind"><span>毛衫</span></a></li>
                    <li><a href="javascript:void(0)" title="牛仔" data-id="7" data-key="ind"><span>牛仔</span></a></li>
                    <li><a href="javascript:void(0)" title="皮革/皮草" data-id="8" data-key="ind"><span>皮革<br/>皮草</span></a></li>
                    <li><a href="javascript:void(0)" title="内衣/泳装" data-id="9" data-key="ind"><span>内衣<br/>泳装</span></a></li>
                    <li><a href="javascript:void(0)" title="婚纱/礼服" data-id="10" data-key="ind"><span>婚纱<br/>礼服</span></a></li>
                    <li><a href="javascript:void(0)" title="运动" data-id="11" data-key="ind"><span>运动</span></a></li>
                </ul>
            </div>
            <div class="dropdown-contact fr">
                <ul class="clearfix">
                    <li class="product-tel"><em class="fl"></em><p class="fl">产品咨询<br/><span>4008-210-500</span></p></li>
                    <li class="service-tel"><em class="fl"></em><p class="fl">售后帮助<br/><span>4008-210-662</span></p></li>
                </ul>
            </div>
        </div>
    </div>
</div><?php
/*/%%SmartyNocache:18086610445ea691e0909b25_13368303%%*/
}
}
?><?php
/*%%SmartyHeaderCode:8063703885ea691e0710376_93930522%%*/
if ($_valid && !is_callable('content_5ea691e070ada7_06725274')) {
function content_5ea691e070ada7_06725274 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '8063703885ea691e0710376_93930522';
?>


<div class="new_header">
	<div class="head-top js-head-top">
		<div class="conleft fl">
			<ul class="leftT clearfix">
				<li class="webIco">
					<a href="https://www.pop-fashion.com/" rel="nofollow" title="POP服装趋势"><img src="<?php echo STATIC_URL2;?>
/global/images/vi_logo.png" alt="POP服装趋势"><i></i></a>
					<!-- <span class="new-year"></span> -->
					<div class="allWeb">
						<ul class="leftlists">
							<li><a href="https://www.pop-fashion.com/" target="_blank" rel="nofollow" title="服装"><em class="fashion"></em>服装</a></li>
							<li><a href="http://www.pop-bags.com/" target="_blank" rel="nofollow" title="箱包"><em class="bag"></em>箱包</a></li>
							<li><a href="http://www.pop-shoe.com/" target="_blank" rel="nofollow" title="鞋子"><em class="shoe"></em>鞋子</a></li>
							<li><a href="http://www.51shoushi.com/" target="_blank" rel="nofollow" title="首饰"><em class="dec"></em>首饰</a></li>
                            <li><a href="http://www.91jiafang.com/" target="_blank" rel="nofollow" title="家纺"><em class="home"></em>家纺</a></li>
                            <li><a href="http://yuntu.pop136.com/" target="_blank" rel="nofollow" title="云图"><em class="yuntu"></em>云图</a></li>
							<li><a href="http://www.pop136.com/" target="_blank" rel="nofollow" title="官网"><em class="guanw"></em>官网</a></li>
                            <!-- <li class="popli"><a href="http://www.pop136.com/lecture/" target="_blank" rel="nofollow" title="POP趋势讲堂"><em class="jiangt"></em>POP趋势讲堂</a></li> -->
							<!--<?php if ($_smarty_tpl->tpl_vars['randAuthKey']->value && in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2))) {?>-->
							<!--<li>-->
								<!--<a href="http://www.mynfd.com/index.php?key=<?php echo $_smarty_tpl->tpl_vars['randAuthKey']->value;?>
" target="_blank" title="趋势参考"><em class="qushi"></em>趋势参考</a>-->
							<!--</li>-->
                            <!--<?php }?>-->
                            <!--<li><a href="https://fashiontrend.productai.cn/index" target="_blank" rel="nofollow" title="FASHIONTREND"><em class="fashi"></em>FASHIONTREND</a></li>-->
						</ul>
					</div>
				</li>
				<li class="fg-line"></li>
				<li class="langU js-leftT">
					<a class="ch-lang" title="中文"><em></em>中文<i></i></a>
					<div class="lang-list js-lang-list">
						<!-- <a class="korea-lang" href="http://k.pop-fashion.com" target="_blank"><em></em>한국어</a> -->
						<a class="eng-lang" href="https://www.popfashioninfo.com/" target="_blank" title="English"><em></em>English</a>
					</div>
				</li>				
				<li class="web_use_li">
					<a href="http://yuntu.pop136.com/" rel="nofollow" target='_blank' title="云图"><em></em>云图</a>
				</li>
                <li class="trend_app_li">
                <a href="https://www.pop-fashion.com/topic/trend_app/" rel="nofollow" target='_blank' title="趋势APP"><em></em>趋势APP</a>
                </li>
				<!-- <li class="kh_down_li">
					<a href="http://www.pop136.com/pop_exe.php" target="_blank" rel="nofollow" title="客户端"><em></em>客户端</a>
				</li> -->
			</ul>			
		</div>
		<div class="conright fr" id="mybiy">
			<ul class="head_li clearfix">
				<li class="tel-and-qq js-tel-and-qq">
					<a class="tel-icon" href="javascript:void(0);"><em></em></a>
					<div class="tel-downlist js-tel-downlist">
						<p class="shouqian-tel"><em></em><span>4008-210-500</span>（售前客服）</p>
						<p class="shouhou-tel"><em></em><span>4008-210-662</span>（售后小秘书）</p>
						<a class="qq-contact js-contact-qq-btn" href="javascript:void(0);" data-type="<?php if (in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2))) {?>after<?php }?>" title="QQ咨询"><em></em>QQ咨询</a>
					</div>
				</li>
				<li class="total_search">
					<div class="search-box js-search-box">
						<input class="search-btn js-search-btn" type="button">
						<input type="text" class="Itext js-Itext" placeholder="搜索" growing-track="true">
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
							<!-- <div class="look-brand-box">
								<a class="look-all-brand js-look-all-brand" href="javascript:void(0)" title="查看所有品牌">查看所有品牌<em></em></a>
							</div> -->
						</div>
						<!-- 上传图片 -->
						<!-- <div class="upload-files js-figure-btn"></div> -->
					</div>
				</li>
				<li class="find-color">
                    <a href="/Colorsearch/" title="色彩搜图"><em></em>色彩搜图</a>
                </li>
                <li class="find-camera js-figure-btn">
                    <a title="以图搜图"><em></em>以图搜图</a>
                </li>
				<li class="fg-line"></li>
				<?php if ($_smarty_tpl->tpl_vars['account']->value == '' && $_smarty_tpl->tpl_vars['accountShow']->value != 'true') {?>
				<li class="login_li">
					<a href='javascript:void(0);' class='loginLayer' rel="nofollow" title="登录"><span class="lg"></span>登录</a>
				</li>
				<li class="fg-line white-line"></li>
				<li class="rgSearch">
					<a class="redRg" href="/member/register/" rel="nofollow" title="注册"><span class="rg"></span>注册</a>
				</li>
				<?php } else { ?>
				<li class="information_li">
					<a class="infor_a" href='/member/message/' title="消息">消息<?php if ($_smarty_tpl->tpl_vars['issetNewMessage']->value) {?><span class="ix"></span><?php }?></a>
				</li>
				<li class="returnLi js-returnLi">
                    <em></em>
                    <span><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['account']->value,9,"...",true);?>
</span>
                    <i></i>
                    <!-- <a href="/member/logout/" id="loginout" class="lout" title="退出">退出</a> -->
                    <div class="forUl js-forUl" style="display:none;width: 138px;">
                        <?php if ($_smarty_tpl->tpl_vars['canChangLoginAccount']->value) {?>
							<?php if ($_smarty_tpl->tpl_vars['P_AccountType']->value == "main") {?>
							<div class="forLi switch js-switch" data-type="1">
								<em class=""></em>
								<span>切换成设计师账号</span>
							</div>
							<label style="top:-20px;">(管理员账号)</label>
							<?php } else { ?>
							<div class="forLi switch js-switch" data-type="2">
								<em class=""></em>
								<span>切换成管理员账号</span>
							</div>
							<label style="top:-20px;">(设计师账号)</label>
							<?php }?>
						<?php }?>
						<div class="forLi myStudio js-myStudio">
							<a href="/member/workbench/" title="我的工作室">
								<em class=""></em>
								<span>我的工作室</span>
							</a>
						</div>
						<div class="forLi myFavorites js-myFavorites">
							<a href="/member/mycollect/" title="我的收藏">
								<em class=""></em>
								<span>我的收藏</span>
							</a>
                        </div>
                        <div class="forLi myHistory">
                            <a href="/member/myHistory/4/" title="浏览历史">
								<em class=""></em>
								<span>浏览历史</span>
							</a>
                        </div>
                        <div class="forLi signOut js-signOut">
                            <em class=""></em>
                            <span>退出登录</span>
                        </div>
                    </div>
				</li>
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
				<li class="apply-try js-contact-qq-btn">
					<a href="javascript:void(0);" title="咨询VIP服务">咨询VIP服务</a>
				</li>
                <?php }?>
				<li class="like">
					<a class="" href="http://www.pop136.com/product_help.php">帮助中心</a>
				</li>
				<li class="all-nav-li js-all-nav-li">
					<span></span>
				</li>
			</ul>
		</div>
    </div>
    <input type="hidden" id="remind-time" value="<?php echo $_smarty_tpl->tpl_vars['vipTips']->value;?>
"/>
    <?php if ($_smarty_tpl->tpl_vars['vipTips']->value <= 90 && $_smarty_tpl->tpl_vars['vipTips']->value > 7) {?>
    <div class="remind-list">
        <div class="interval"></div>
        <span>
            <img src="<?php echo STATIC_URL2;?>
/global/images/remind.png?<?php echo STATIC_CHANGE_TIME;?>
" alt="remind">
            您的会员账号即将到期，提前续约更享<span>惊喜好礼</span> 立即联系 4008-210-662
        </span>
        <a href="javascript:void(0);" onclick="return false" title="关闭"><img class="js-remind-close" src="<?php echo STATIC_URL2;?>
/global/images/remind-close.png?<?php echo STATIC_CHANGE_TIME;?>
" alt="关闭"></a>
    </div>
    <?php } elseif ($_smarty_tpl->tpl_vars['vipTips']->value <= 7 && $_smarty_tpl->tpl_vars['vipTips']->value >= 1) {?>
    <div class="remind-list active">
        <div class="interval"></div>
        <span>
            <img src="<?php echo STATIC_URL2;?>
/global/images/remind.png?<?php echo STATIC_CHANGE_TIME;?>
" alt="remind">
            您的会员账号到期时间只剩<span><?php echo $_smarty_tpl->tpl_vars['vipTips']->value;?>
</span>天，为了不影响您的正常使用 请立即联系 4008-210-662
        </span>
        <a href="javascript:void(0);" onclick="return false" title="关闭"><img class="js-remind-close" src="<?php echo STATIC_URL2;?>
/global/images/remind-close.png?<?php echo STATIC_CHANGE_TIME;?>
" alt="关闭"></a>
    </div>
    <?php } elseif ($_smarty_tpl->tpl_vars['vipTips']->value == 'today') {?>
    <div class="remind-list active">
        <div class="interval"></div>
        <span>
            <img src="<?php echo STATIC_URL2;?>
/global/images/remind.png?<?php echo STATIC_CHANGE_TIME;?>
" alt="remind">
            您的会员账户今天即将到期，为不影响您的正常使用 请立即联系 4008-210-662
        </span>
        <a href="javascript:void(0);" onclick="return false" title="关闭"><img class="js-remind-close" src="<?php echo STATIC_URL2;?>
/global/images/remind-close.png?<?php echo STATIC_CHANGE_TIME;?>
" alt="关闭"></a>
    </div>
    <?php }?>
	<div class="new_nav_box">
		<div class="contentNew clearfix">
			<div class="new_lanmu">
				<ul class="clearfix">
					<li class="nl_hover switch-area js-switch-area  <?php if ($_smarty_tpl->tpl_vars['is_relate']->value) {?>selected<?php }?>">
						<?php if ($_smarty_tpl->tpl_vars['account']->value == '' && $_smarty_tpl->tpl_vars['accountShow']->value != 'true') {?>
						<a href="/" class="nl_a" title="切换专区"><em></em>切换专区</a>
						<?php } else { ?>
						<a href="/" class="nl_a" title="今日推荐">今日推荐<span class="hover-bar"></span></a>
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
					<li class="nl_hover has_down <?php if ($_smarty_tpl->tpl_vars['columnPid']->value == 1) {?>selected<?php }?>">
						<a class="nl_a" href="/trends/" title="趋势解读">趋势解读<span class="hover-bar"></span></a>						
						<div class="downlist_box">
							<span class="list-line"></span>
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
						<a class="nl_a" href="/analysis/" title="流行分析">流行分析<span class="hover-bar"></span></a>						
						<div class="downlist_box">
							<span class="list-line"></span>
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
								<!-- <li><a href="/analysis/street/" title="街拍分析">街拍分析</a></li> -->
								<!-- <li><a href="/analysis/reports/" title="行业报道">行业报道</a></li> -->
							</ul>
						</div>
					</li>
					<li class="nl_hover fg-line"></li>
					<li class="nl_hover has_down <?php if ($_smarty_tpl->tpl_vars['columnPid']->value == 4) {?>selected<?php }?>">
						<a class="nl_a" href="/styles/" title="款式">款式<span class="hover-bar"></span></a>						
						<div class="downlist_box">
							<span class="list-line"></span>
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
						<a class="nl_a" href="/patterns/graphics/" title="图案">图案<span class="hover-bar"></span></a>						
						<div class="downlist_box">
							<span class="list-line"></span>
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
						<a class="nl_a" href="/runways/" title="T台">T台<span class="hover-bar"></span></a>						
						<div class="downlist_box">
							<span class="list-line"></span>
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
						<a class="nl_a" href="/references/details/" title="素材">素材<span class="hover-bar"></span></a>
						<div class="downlist_box downlist_box2 downlist_references">
							<span class="list-line"></span>
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
						<a class="nl_a" href="/books/lookbook/" title="读物">读物<span class="hover-bar"></span></a>
						<div class="downlist_box">
							<span class="list-line"></span>
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
                    <li class="nl_hover <?php if ($_smarty_tpl->tpl_vars['columnPid']->value == 10) {?>selected<?php }?>"><a class="nl_a" href="/video/" title="视频">视频<span class="hover-bar"></span></a></li>
                    <li class="nl_hover fg-line"></li>
                    <li class="nl_hover has_down design_tools">
						<a class="nl_a" href="https://appmfz0ensy5593.pc.xiaoe-tech.com/page/800948" target="_blank" title="在线课堂"><em></em>在线课堂<i></i><span class="hover-bar"></span></a>
						<div class="downlist_box">
							<span class="list-line"></span>
							<ul>
								<!-- <li><a href="/runways/" title="智能色彩分析">智能色彩分析</a></li>
								<li><a href="/runways/reg_272/" title="虚拟样衣">虚拟样衣</a></li>
								<li><a href="/topic/20171209/" target="_blank" title="工具简介">工具简介</a></li>								
								<li><a class="js-figure-btn" href="javascript:void(0)" title="智能识别">智能识别</a></li>
								<li><a href="/topic/yuntu/" target="_blank" title="云图">云图</a></li> -->
								<li><a href="https://appmfz0ensy5593.pc.xiaoe-tech.com/page/827920" target="_blank" title="行业课程">行业课程</a></li>								
								<li><a href="https://appmfz0ensy5593.pc.xiaoe-tech.com/page/828792" target="_blank" title="趋势讲堂">趋势讲堂</a></li>
							</ul>
						</div>
                    </li>
                    <li class="nl_hover has_down service_resources">
                        <a class="nl_a" href="/topic/resources/" title="研发资源"><em></em>研发资源<span class="hover-bar"></span></a>
                        <div class="downlist_box">
                            <span class="list-line"></span>
                            <ul>
                                <li><a href="/topic/20171129/" target="_blank" title="设界">设界</a></li>
                                <li><a href="/item/" target="_blank" title="商城">商城</a></li>
                                <li><a href="http://www.uliaobao.com/" target="_blank" title="优料宝">优料宝</a></li>
                                <li><a href="/topic/20171208/" target="_blank" title="找料帮">找料帮</a></li>
                                <li><a href="/activity/lists/" target="_blank" title="时尚商学院">时尚商学院</a></li>
                            </ul>
                        </div>
                    </li>
                    <li class="nl_hover find-brand">
                        <a class="nl_a" href="/brands/" target="_blank" title="发现品牌"><em></em>发现品牌<span class="hover-bar"></span></a>
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
                        <span class="hover-bar"></span></a>
                        <div class="downlist_box">
                            <span class="list-line"></span>
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
	<?php /*  Call merged included template "common/header_nav_down.html" */
echo $_smarty_tpl->getInlineSubTemplate("common/header_nav_down.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '18086610445ea691e0909b25_13368303', 'content_5ea691e09083f1_75996336');
/*  End of included template "common/header_nav_down.html" */?>

</div>
<!-- 侧边绑定手机号 -->
<div class="bindPhone js-open-quick-login open-quick-login"></div>


<?php
/*/%%SmartyNocache:8063703885ea691e0710376_93930522%%*/
}
}
?><?php
/*%%SmartyHeaderCode:2642417175ea691e0a07180_62648993%%*/
if ($_valid && !is_callable('content_5ea691e0a05e01_29212688')) {
function content_5ea691e0a05e01_29212688 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '2642417175ea691e0a07180_62648993';
?>
<div class="search_cont_top clearfix">
    <input type="hidden" id="have_column" value="<?php echo $_smarty_tpl->tpl_vars['lists']->value['have_column_show'];?>
">
    <div class="search_cont_l fl">
        <a href="/"><img src="<?php echo STATIC_URL2;?>
/global/images/lists/nav_logo.png?<?php echo STATIC_CHANGE_TIME;?>
"></a>
    </div>
    <div class="search_cont_r">
        <input id="search_box2" class="js-search-ipt" value="<?php echo $_smarty_tpl->tpl_vars['keyword']->value;?>
" data-staut="1" growing-track="true" placeholder="搜索你想要的" type="text" οnfοcus="this.placeholder=''" οnblur="this.placeholder='搜索你想要的'" autocomplete='off'/>
        <div class="search_listDown search_page_listDown">
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
        <!-- 上传图片 -->
        <a href="/Colorsearch/" class="sea-color"></a>
        <div class="files js-figure-btn"></div>
        <input type="hidden" id="cropData1"/>
        <input type="file" id="uploadImg1" style="display: none;"/>
        <!-- 上传图片end -->
        <input id="params" name="params" type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['params']->value;?>
">
        <i class="search js-search-btn"></i>
        <div class="search-filesImg" style="display: none;">
            <img src=""/>
        </div>
        <div class="sea-col js-sea-color" style="display: none;">
            
        </div>
        <?php if ($_smarty_tpl->tpl_vars['account']->value == '' && $_smarty_tpl->tpl_vars['accountShow']->value != 'true') {?>
        <!-- 未登录 -->
        <div class="heae-user">
            <a class="loginLayer">登录</a>
            <i></i>
            <a href="/member/register/" target="_blank">注册</a>
        </div>
        <?php } else { ?>
        <!-- 已登录 -->
        <div class="heae-user">
            <!--<span>消息<i></i></span>-->
            <a class="infor_a" href='/member/message/' title="消息">消息<?php if ($_smarty_tpl->tpl_vars['issetNewMessage']->value) {?><span class="ix"></span><?php }?></a>
            <i></i>
            <div class="userName action">
                <a href="/member/myHistory/4/"><?php echo smarty_modifier_truncate($_smarty_tpl->tpl_vars['account']->value,9,"...",true);?>
</a>
                <i></i>
                <div class="forUl js-forUl" style="width: 138px;display: none;">
                    <?php if ($_smarty_tpl->tpl_vars['canChangLoginAccount']->value) {?>
                        <?php if ($_smarty_tpl->tpl_vars['P_AccountType']->value == "main") {?>
                        <div class="forLi switch js-switch" data-type="1">
                            <em class=""></em>
                            <span>切换成设计师账号</span>
                        </div>
                        <label style="top:-27px;">(管理员账号)</label>
                        <?php } else { ?>
                        <div class="forLi switch js-switch" data-type="2">
                            <em class=""></em>
                            <span>切换成管理员账号</span>
                        </div>
                        <label style="top:-27px;">(设计师账号)</label>
                        <?php }?>
                    <?php }?>
                    <div class="forLi myStudio js-myStudio">
                        <a href="/member/workbench/" title="我的工作室">
                            <em class=""></em>
                            <span>我的工作室</span>
                        </a>
                    </div>
                    <div class="forLi myFavorites js-myFavorites">
                        <a href="/member/mycollect/" title="我的收藏">
                            <em class=""></em>
                            <span>我的收藏</span>
                        </a>
                    </div>
                    <div class="forLi myHistory js-myHistory">
                        <a href="/member/myHistory/4/" title="浏览历史">
                            <em></em>
                            <span>浏览历史</span>
                        </a>
                    </div>
                    <div class="forLi signOut js-signOut">
                        <em class=""></em>
                        <span>退出登录</span>
                    </div>
                </div>
            </div>
        </div>
        <?php }?>
    </div>
</div><?php
/*/%%SmartyNocache:2642417175ea691e0a07180_62648993%%*/
}
}
?><?php
/*%%SmartyHeaderCode:16861152195ea691e183b4f6_82920660%%*/
if ($_valid && !is_callable('content_5ea691e183a739_34344641')) {
function content_5ea691e183a739_34344641 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '16861152195ea691e183b4f6_82920660';
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
		<li class="pop-map-icon js-pop-map">
			<div class="arrow-map-icon js-arrow-map"></div>
			<div class="map-contain js-map-show">
				<div class="map-img js-map-img">
					<!--改为js动态添加-->
					<!--<iframe src="" width="370" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>-->
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
		</li>
		<li class="qq_icon bj_icon lix1200">
            <!-- data-type="<?php if (in_array($_smarty_tpl->tpl_vars['P_UserType']->value,array(1,2))) {?>after<?php }?>" -->
			<a class="" href="/member/leavemessage"  rel="nofollow" title="咨询"></a>
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
		<li class="new-icon lix1200 js-get-new-trend">
			<div class="new-icon-box"></div>
			<div class="new-box show_left">最新趋势</div>
			<i></i>
		</li>
		<li class="backTop" id="backTop"></li>
	</ul>
	<?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 5 || $_smarty_tpl->tpl_vars['P_UserType']->value == 4) {?>
	<div class="nav_trial js-new-trend-enter">
		<span>免费试读</span>
	</span>
	<?php }?>
</div>
<?php }?><?php
/*/%%SmartyNocache:16861152195ea691e183b4f6_82920660%%*/
}
}
?><?php
/*%%SmartyHeaderCode:11849929265ea691e1823997_85612409%%*/
if ($_valid && !is_callable('content_5ea691e1822ad9_98096162')) {
function content_5ea691e1822ad9_98096162 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '11849929265ea691e1823997_85612409';
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
echo $_smarty_tpl->getInlineSubTemplate("common/nav_fixed_box.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '16861152195ea691e183b4f6_82920660', 'content_5ea691e183a739_34344641');
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
                <label for="name">姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名</label><input value="" autocomplete="off" class="js-input-area" id="name" name="name" type="text" maxlength="20" placeholder="方便我们称呼您">
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


<!-- 最新推荐弹层 -->
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
        <div class="trials-tit clear">
            <h3>立即注册即可免费查看以下内容</h3>
            <div class="trials-tit-line">更多流行趋势报告和潮流款式等你来～</div>
            <div class="trials-tit-itembox trials-out">
                <a class="button" href="/member/register/" target="_blank">立即注册</a>
                <a class="trials-tit-item-center js-contact-qq-btn" href="javascript:void(0);">畅通无阻快速了解</a>
                <a>电话咨询 <span>4008-210-500</span></a>
            </div>
        </div>
        <?php }?>
        <?php if ($_smarty_tpl->tpl_vars['P_UserType']->value == 4) {?>
        <div class="trials-tit clear">
            <h3>免费精选内容</h3>
            <div class="trials-tit-itembox trials-out" style="margin-top: 40px;">
                <span class="trials-s">需要更多会员服务，请联系：</span>
                <a class="button">咨询会员服务</a>
                <a>电话咨询 <span>4008-210-500</span></a>
            </div>
        </div>
        <?php }?>
        <div class="trials-itembox">
            <!--免费报告-->
            <div class="trials-item-title">报告免费试读</div>
            <div class="trials-item-top js-trials-item-top clear">
                
            </div>
            <!--/免费报告-->
            <!--免费款式-->
            <div class="trials-item-title">款式免费查看</div>
            <div class="trials-item-btm js-trials-item-btm clear">
                
            </div>
            <!--/免费款式-->
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
</div>

<?php
/*/%%SmartyNocache:11849929265ea691e1823997_85612409%%*/
}
}
?><?php
/*%%SmartyHeaderCode:12215875725ea691e1b88726_14429651%%*/
if ($_valid && !is_callable('content_5ea691e1b87500_08425534')) {
function content_5ea691e1b87500_08425534 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '12215875725ea691e1b88726_14429651';
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
/*/%%SmartyNocache:12215875725ea691e1b88726_14429651%%*/
}
}
?>