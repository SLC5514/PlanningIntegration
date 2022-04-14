<?php /* Smarty version 3.1.27, created on 2020-06-29 16:08:56
         compiled from "/data/htdocs/pop136_yuntu/views/report/trends_detail.html" */ ?>
<?php
/*%%SmartyHeaderCode:12882997215ef9a198de25e4_95550468%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e1c972c32687a07157a62d8461cd7d861c959efe' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/report/trends_detail.html',
      1 => 1593417616,
      2 => 'file',
    ),
    '4b22eedd05bdc48aa69395e981b7badb50229f97' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/layout.html',
      1 => 1587877444,
      2 => 'file',
    ),
    '0796d8b02e2cb72c459bbdc01d159914d6ba8605' => 
    array (
      0 => '0796d8b02e2cb72c459bbdc01d159914d6ba8605',
      1 => 0,
      2 => 'string',
    ),
    'c270d5b9b9b9fca55e14fe96d756c566613109c6' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/header.html',
      1 => 1592644410,
      2 => 'file',
    ),
    'ea6d133b5788763851b2155133b28f54c4d929f2' => 
    array (
      0 => 'ea6d133b5788763851b2155133b28f54c4d929f2',
      1 => 0,
      2 => 'string',
    ),
    'a91b5d00af2d271a5ee5bd89fa15b4701643d6db' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/footer.html',
      1 => 1587877444,
      2 => 'file',
    ),
    '7d9e3b73945f5a4092f8760d0089a313a8e41f9b' => 
    array (
      0 => '7d9e3b73945f5a4092f8760d0089a313a8e41f9b',
      1 => 0,
      2 => 'string',
    ),
    '12df490223b424893b0a51107deeb2d0d6f21d73' => 
    array (
      0 => '12df490223b424893b0a51107deeb2d0d6f21d73',
      1 => 0,
      2 => 'string',
    ),
    '6633b840b92e6c58362e7a90badf1988164cd731' => 
    array (
      0 => '6633b840b92e6c58362e7a90badf1988164cd731',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '12882997215ef9a198de25e4_95550468',
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
  'unifunc' => 'content_5ef9a199519544_45724908',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5ef9a199519544_45724908')) {
function content_5ef9a199519544_45724908 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '12882997215ef9a198de25e4_95550468';
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
$_smarty_tpl->properties['nocache_hash'] = '12882997215ef9a198de25e4_95550468';
?>

<link rel="stylesheet" type="text/css"
    href="<?php echo @constant('STATIC_URL1');?>
/global/css/report/detail.css?<?php echo @constant('STATIC_CHANGE_TIME');?>
" />


    </head>
<body>
    <?php echo $_smarty_tpl->tpl_vars['token']->value;?>

    <div class="view">
        
        <?php /*  Call merged included template "header.html" */
echo $_smarty_tpl->getInlineSubTemplate("header.html", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, '2736096105ef9a1991b3c38_68714149', 'content_5ef9a1991b15a0_41096293');
/*  End of included template "header.html" */?>

        

        <?php
$_smarty_tpl->properties['nocache_hash'] = '12882997215ef9a198de25e4_95550468';
?>

<input id="encrypt" type="hidden" data-sign="<?php echo $_smarty_tpl->tpl_vars['sign']->value;?>
" data-timestamp="<?php echo $_smarty_tpl->tpl_vars['timeStamp']->value;?>
" data-token="<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
">

<div class="report-content" id="pop_layer">
    <div class="report-cont">
        <!--报告-->
        <div class="pop-report pop-report-detail js-pop-report">
            <div class="bannner-background">
                <div class="fiter-img js-fiter-img">
                    <img src="" alt="pic">
                </div>
                <div class="layer-main">
                    <div class="header-detail-infor">
                        <h1></h1>
                        <div class="tit-info">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <div class="live-box live-trial-vip">
                        <!-- <span>视频直播解读</span> -->
                        <!-- <a href="javascript:void(0);" target="_blank">立即预约<i></i></a> -->
                        <!-- <a href="javascript:void(0);" target="_blank">回看解读<i></i></a> -->
                        <!-- <a href="javascript:void(0);" target="_blank">立即收看<i></i></a> -->
                    </div>
                    <div class="bannerpic">
                        <img src="" alt="pic">
                    </div>
                </div>
            </div>
            <div class="layer-main layer-indexdiv-main"></div>
        </div>
    </div>
</div>

<!--侧边导航-->
<div class="nav-right js-nav-right">
    <div class="nav-control js-nav-control1"></div>
    <ul class="right-tab clearfix js-right-tab nobook">
        <li class="fl right-tab-text active"><i></i><span>本文简介</span></li>
        <!-- <li class="fr right-tab-book"><i></i><span>系列推荐</span></li> -->
    </ul>
    <div class="right-cont right-cont-text">
        <div class="right-top">
            <h3></h3>
            <div class="report-msg js-report-msg">
                <div></div>
                <p class="report-messages"></p>
            </div>
            <div class="msg-i4-null" style="height: 12px;display: none;"></div>
        </div>
        <div class="right-top">
            <div class="msg-i msg-i4 js-msg-i4 clear">
                <i></i>
                <ul class="fl">
                    <!-- <li><a target="_blank" href="" title=""></a></li> -->
                </ul>
            </div>
        </div>
        <div class="see-nav js-see-nav1">
            <div class="see-title">
                <i></i>大纲
            </div>
            <ul>
                <!-- <li class="<?php if ($_smarty_tpl->tpl_vars['key']->value == 0) {?>this-item<?php }?>">
                    <a data-id="index_<?php echo $_smarty_tpl->tpl_vars['topic']->value['iSubTopicId'];?>
" onclick="javascript:document.getElementById('index_<?php echo $_smarty_tpl->tpl_vars['topic']->value['iSubTopicId'];?>
').scrollIntoView()" class="clear" title="<?php echo $_smarty_tpl->tpl_vars['topic']->value['sTitle'];?>
">
                        <i></i><span><?php echo $_smarty_tpl->tpl_vars['topic']->value['sTitle'];?>
</span>
                    </a>
                </li> -->
            </ul>
        </div>
    </div>
</div>
<!--小屏幕侧边导航-->
<div class="nav-right-min js-nav-right-min">
    <div class="nav-control js-nav-control2">
        <div class="nav-control-disc js-nav-control-disc">
            <i></i>
            <p>点击，可展开侧边栏</p>
            <div>知道了</div>
        </div>
    </div>
    <ul class="right-tab clearfix js-right-tab nobook">
        <li class="fl right-tab-text active"><i></i><span>本文简介</span></li>
        <!-- <li class="fr right-tab-book"><i></i><span>系列推荐</span></li> -->
    </ul>
    <div class="right-cont-min right-cont-text">
        <div class="see-nav js-see-nav2">
            <div class="see-title">
                <i></i>大纲
            </div>
            <ul class="js-see-list"></ul>
        </div>
    </div>
</div>

<!--底部-->
<div class="report-footer">
    <div class="clear">
        <div class="footer-cont">
            <div class="go-top js-go-top">
                <i></i>
                <p>返回<br />顶部</p>
            </div>
            <ul class="clear">
                <li class="prompt js-prompt">
                    <i></i>
                    <div class="prompt-disc">
                        <div class="clear">
                            <div class="disc-icon">
                                <p style="padding-bottom: 25px;">
                                    <i class="disc-icon1"></i>
                                </p>
                                <p>
                                    <i class="disc-icon2"></i>
                                </p>
                                <p>
                                    <i class="disc-icon3"></i>
                                </p>
                                <p style="padding-top: 3px;">
                                    <i class="disc-icon4"></i>
                                    <i class="disc-icon5"></i>
                                </p>
                            </div>
                            <div class="dic-msg">
                                <p>鼠标滑动滚轮，上滑报告</p>
                                <p style="padding-top: 32px;">键盘上下键，上滑报告</p>
                                <p style="padding-top: 18px;">按键盘左右键，侧边栏收<br />起展开操作</p>
                            </div>
                        </div>
                        <div class="dic-close">知道了</div>
                    </div>
                </li>
                <!-- 灵感视频无PDF与收藏 -->
                <li class="report-down" id="J_DownPDF1" data-rename="" data-path=""><i></i><span>下载PDF报告</span></li>
                <li class="report-down-imgpack js-report-down-imgpack" data-rename="" data-path="">
                    <i></i><span>下载全部图片</span></li>
                <li class="report-collect js-report-collect" data-iscollected="">
                    <i></i><span>加入收藏</span>
                </li>
                <li class="report-share">
                    <i></i><span>分享</span>
                    <div class="prompt-disc">
                        <div class="bdsharebuttonbox clear bdshare-button-style0-24" href="javascript:void(0);">
                            <a href="javascript:void(0)" class="bds-sqq" data-cmd="sqq" title="分享给QQ好友"></a>
                            <a href="javascript:void(0)" class="bds-weixin" data-cmd="weixin" title="分享到微信"></a>
                            <a href="javascript:void(0)" class="bds-qzone" data-cmd="qzone" title="分享到QQ空间"></a>
                            <a href="javascript:void(0)" class="bds-tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                        </div>
                    </div>
                </li>
                <li class="yuntu-app">
                    <a href="javascript:void(0);" title="云图APP">
                        <i></i><span>云图APP</span>
                    </a>
                    <div class="qr-code"></div>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- 下载图片 -->
<div class="download-img-box js-download-img-box">
    <div class="down-main-top clearfix">
        <h2 class="fl">下载文件格式</h2>
        <div class="fr down-close js-down-close"></div>
    </div>
    <table class="down-table" width="100%" id="J_DownloadType2">
        <thead>
            <tr>
                <th width="25%">格式</th>
                <th width="25%">尺寸</th>
                <th width="25%">文件大小</th>
                <th width="25%">点击下载</th>
            </tr>
        </thead>
        <tbody class="js-download-tbody">
            <tr>
                <td>.eps</td>
                <td>--</td>
                <td>10MB</td>
                <td><a class="js-download-btn" href="javascript:void(0);"
                        data-bp="%2Ffashion%2Fgraphic%2F201803070330_sh4650%2Feps%2F0-2790380308_sh4650.eps"
                        title="下载">下载</a></td>
            </tr>
            <tr>
                <td>.eps</td>
                <td>--</td>
                <td>10MB</td>
                <td><a class="js-download-btn" href="javascript:void(0);"
                        data-bp="%2Ffashion%2Fgraphic%2F201803070330_sh4650%2Feps%2F0-2790380308_sh4650.eps"
                        title="下载">下载</a></td>
            </tr>
        </tbody>
    </table>
</div>

<!-- 缩放图片层 -->
<div class="show-pic-section js-show-pic-section status" data-click="1">
    <div class="section" data-click="1">
        <button data-click="1"></button>
        <div class="down-pic js-download-btn2 js-src">
            <i></i>
            <span>下载图片</span>
        </div>
        <!-- <div class="recommend js-recommend">
            <span>相关推荐</span>
            <div class="icon"></div>
        </div> -->
        <i class="i-lfet js-i-lfet"></i>
        <img class="js-down-img" style="display: none;" ondragstart="return false;" alt="pic">
        <img src="<?php echo @constant('STATIC_URL1');?>
/global/images/report/loading_pie.gif" class="js-gifImg">
        <i class="i-right js-i-right"></i>
        <div class="operation-div">
            <div class="tool-div js-tool-div">
                <div><button style="top: 0px;"></button></div>
                <button class="js-origin-img-btn" title="原图">原图</button>
                <button class="js-reset-img-btn" title="复位">复位</button>
            </div>
        </div>
    </div>
    <div class="section-right Scrollbar">
        <div class="head">相关推荐</div>
        <div class="list js-guess-imgs clearfix">
        </div>
        <div class="loading js-loading">
            <img src="<?php echo @constant('STATIC_URL1');?>
/global/images/report/alertloading.gif" />
        </div>
    </div>
</div>
<div class="show-pic-bg js-show-pic-bg"></div>

<!-- 报告视频 -->
<div class="report-video-bg js-report-video-bg"></div>
<div class="report-video-section js-report-video-section">
    <i class="close"></i>
    <div id="reportPlayer"></div>
</div>



        <?php
$_smarty_tpl->properties['nocache_hash'] = '12882997215ef9a198de25e4_95550468';
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
$_smarty_tpl->properties['nocache_hash'] = '12882997215ef9a198de25e4_95550468';
?>


<?php echo '<script'; ?>
 type="text/javascript" src="https://player.youku.com/jsapi"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
    window._bd_share_config = { "common": { "bdSnsKey": {}, "bdText": "", "bdMini": "1", "bdMiniList": false, "bdPic": "", "bdStyle": "0", "bdSize": "24" }, "share": {} }; with (document) 0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = '/static/api/js/share.js?v=89860593.js?cdnversion=' + ~(-new Date() / 36e5)];
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
>
    // 页面定位
    var id = getQueryString("searchId");
    function getQueryString(name) {
        var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
        var r = window.location.search.substr(1).match(reg);
        if (r != null) {
            return unescape(r[2]);
        }
        return null;
    }
    if (id != null && id != undefined) {
        setTimeout(function () {
            document.getElementById(id).scrollIntoView();
        }, 2000);
    }
<?php echo '</script'; ?>
>

<?php echo '<script'; ?>
>
    var statistics_token = "<?php echo $_smarty_tpl->tpl_vars['statistics_token']->value;?>
";// 统计的token
    var P_UserType_action = "<?php echo $_smarty_tpl->tpl_vars['P_UserType_action']->value;?>
";// 同步服装的类型，1-VIP-主账号id | 4-普通id
    var iUserId = "<?php echo $_smarty_tpl->tpl_vars['iUserId']->value;?>
";// 用户ID
    var col_user_type = "<?php echo $_smarty_tpl->tpl_vars['col_user_type']->value;?>
"; // 试用用户 TRIAL
<?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo @constant('STATIC_URL3');?>
/global/js/report/detail.js?<?php echo @constant('STATIC_CHANGE_TIME');?>
"
    type="text/javascript" charset="utf-8"><?php echo '</script'; ?>
>

    <?php
$_smarty_tpl->properties['nocache_hash'] = '12882997215ef9a198de25e4_95550468';
?>

<!-- 收藏提示tip -->
<div class="tip-box">提示</div>


    
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
/*%%SmartyHeaderCode:2736096105ef9a1991b3c38_68714149%%*/
if ($_valid && !is_callable('content_5ef9a1991b15a0_41096293')) {
function content_5ef9a1991b15a0_41096293 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '2736096105ef9a1991b3c38_68714149';
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
/*/%%SmartyNocache:2736096105ef9a1991b3c38_68714149%%*/
}
}
?><?php
/*%%SmartyHeaderCode:4592518295ef9a199385bf8_86239457%%*/
if ($_valid && !is_callable('content_5ef9a199384b10_41943105')) {
function content_5ef9a199384b10_41943105 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '4592518295ef9a199385bf8_86239457';
?>
<!-- footer start -->
<div class="footer">
    <span>上海逸尚云联信息技术股份有限公司 ©2004-2020 法律顾问：北京中银（上海）律师事务所 <a href="http://www.beian.miit.gov.cn/" target="_blank">沪ICP备06003020号-1</a></span>
</div>
<!-- footer end --><?php
/*/%%SmartyNocache:4592518295ef9a199385bf8_86239457%%*/
}
}
?>