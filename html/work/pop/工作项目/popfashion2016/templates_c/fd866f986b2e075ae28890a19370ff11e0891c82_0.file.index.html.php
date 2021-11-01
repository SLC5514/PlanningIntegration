<?php /* Smarty version 3.1.27, created on 2020-07-06 11:43:11
         compiled from "/data/htdocs/popfashion2016/views/topic/topBrands/index.html" */ ?>
<?php
/*%%SmartyHeaderCode:17256022185f029dcf57a982_28843496%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fd866f986b2e075ae28890a19370ff11e0891c82' => 
    array (
      0 => '/data/htdocs/popfashion2016/views/topic/topBrands/index.html',
      1 => 1587872788,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17256022185f029dcf57a982_28843496',
  'variables' => 
  array (
    'keywords' => 0,
    'description' => 0,
  ),
  'has_nocache_code' => false,
  'version' => '3.1.27',
  'unifunc' => 'content_5f029dcf6f7cb0_64772349',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5f029dcf6f7cb0_64772349')) {
function content_5f029dcf6f7cb0_64772349 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '17256022185f029dcf57a982_28843496';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="applicable-device" content="pc">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" media="screen" />
    <meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['keywords']->value;?>
" />
    <meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['description']->value;?>
" />
    <!--[if lt IE 9]>
        <link rel="stylesheet" href="<?php echo STATIC_URL2;?>
/global/topic/top_brand/css/ie8-index.css?<?php echo STATIC_CHANGE_TIME;?>
">
    <![endif]-->
    <link rel="stylesheet" href="<?php echo STATIC_URL2;?>
/global/topic/top_brand/css/index.css?<?php echo STATIC_CHANGE_TIME;?>
">
    <link rel="stylesheet" href="<?php echo STATIC_URL3;?>
/global/js/layer/skin/layer.css?<?php echo STATIC_CHANGE_TIME;?>
">
    <title>TOP100图案素材</title>
</head>

<body>
    <div class="main">
        <div class="head">
            <span class="headSpan">最受用户欢迎榜</span>
            <span class="headSpan2">TOP100大牌花型</span>
            <span class="headSpan3"><i></i>POP趋势每月发布<i></i></span>
        </div>
        <div class="topList">
            <div class="bnt js-bnt">
                <span></span><i></i>
                <div class="selectTime" id="js-selectTime">
                </div>
            </div>
            <ul class="js-ul">

            </ul>
        </div>
        <div class="bottomBg"></div>
    </div>
</body>
<?php echo '<script'; ?>
 src="<?php echo STATIC_URL2;?>
/global/js/common/jquery-1.7.2.min.js?<?php echo STATIC_CHANGE_TIME;?>
" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo STATIC_URL2;?>
/global/js/common/Lazyload.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo STATIC_URL3;?>
/global/js/layer/layer.js" type="text/javascript"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
    (function(doc, win) {
        var P_UserType = '5';
        var status = true; //避免网络卡顿时重复调用
        function getSelect(selectTime) {
            if (!status) return;
            var def = {
                'selectTime': selectTime
            }
            $.ajax({
                type: "post",
                url: "/topic/topbrands/",
                data: def,
                dataType: "json",
                success: function(data) {
                    if (data.code == "0") {
                        status = true;
                        P_UserType = data.data.power.P_UserType;
                        var groupTime = data.data.list.groupTime;
                        var list = data.data.list.list;
                        if (groupTime.length > 0) {
                            //填充selectTime
                            var html = '';
                            for (var i = 0; i < groupTime.length; i++) {
                                var str = groupTime[i].y_m.replace('-', '年') + '月篇';
                                if (i == 0 && (selectTime == undefined || selectTime == '')) {
                                    $(".js-bnt span").html(str);
                                }
                                html += '<span data-selectTime="' + groupTime[i].y_m + '" data-str="' + str + '">' + str + '</span>'
                            }
                            $(".selectTime").html(""); //清空旧数据
                            $(".selectTime").append(html);
                            $(".selectTime span").on("click", function() {
                                var getSel = $(this).attr("data-selectTime");
                                var str = $(this).attr("data-str");
                                $(".js-bnt span").html(str);
                                getSelect(getSel);
                            })
                        }
                        if (list.length > 0) {
                            var _html = '';
                            for (var i = 0; i < list.length; i++) {
                                if (i < 4) {
                                    _html += '<li class="lazyload" data-turl="' + list[i].link2 + '" data-url="' + list[i].link + '"><img src="' + list[i].small + '"><span class="topIcon' + (i + 1) + '"></span></li>'
                                } else {
                                    _html += '<li class="lazyload" data-turl="' + list[i].link2 + '" data-url="' + list[i].link + '"><img src="' + list[i].small + '"></li>'
                                }
                            }
                            $(".js-ul").html(""); //清空旧数据
                            $(".js-ul").append(_html);
                            var lazyloadimg = $(".lazyload img");
                            var _lazy = {
                                effect: "show",
                                failure_limit: 12
                            }
                            lazyloadimg.length && lazyloadimg.lazyload(_lazy);
                            $(".js-ul li").on('click', function() {
                                if (isPhone()) {
                                    var url = $(this).attr("data-turl");
                                    location.href = url;
                                } else {
                                    if (P_UserType == '5') {
                                        loginLayer();
                                    } else {
                                        var url = "https://www.pop-fashion.com" + $(this).attr("data-url");
                                        window.open(url);
                                    }
                                }
                            })
                        }
                    } else {
                        console.log(data.info);
                    }
                },
                beforeSend: function() {
                    status = false;
                }
            })
        }
        //默认加载
        getSelect();

        function isPhone() {
            var ua = navigator.userAgent;
            var ipad = ua.match(/(iPad).*OS\s([\d_]+)/),
                isIphone = !ipad && ua.match(/(iPhone\sOS)\s([\d_]+)/),
                isAndroid = ua.match(/(Android)\s+([\d.]+)/),
                isMobile = isIphone || isAndroid;
            return isMobile;
        }

        function loginLayer() {
            if(/AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))){
                try{
                    if(/Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)){
                        window.location.href="/member/pagelogin/";
                    }else{
                        
                    }
                }catch(e){}
            }
            //单点登录
            var url = '//member.pop136.com/login/ssoUserInfo';
            var obj = {}
            var loginLayer;
            $.ajaxFun(url, obj, function (data) {
                if (data.code == '0') {
                    var html = '';
                    var list = data.data;
                    for (var i = 0; i < list.length;i++){
                        html += '<li data-userId="' + list[i].userId + '"><label><img src="'+list[i].avatar+'" alt=""></label >'
                            + '<span>用户名：<span>' + list[i].account + '</span></span></li >';
                    }
                    $('.js-sso-user-list').html('');
                    $('.js-sso-user-list').append(html);
                    $('.js-sso-box').show();
                    parent.layer.closeAll();
                    $('.js-sso-close').on('click', function () {
                        $('.js-sso-box').hide();
                    })
                    $('.js-sso-other-login').on('click', function () {
                        parent.layer.closeAll();
                        $('.js-sso-box').hide();
                        $('.pop_loginbg').show();
                        $('.js-bg-div').show();
                    })
                } else {
                    $('.pop_loginbg').show();
                    $('.js-bg-div').show();
                }
            },function(e){
                $('.pop_loginbg').show();
                $('.js-bg-div').show();
            })
        }
        $(".js-bnt").on('click', function(e) {
            var sel = $(".selectTime")
            if (sel.is(':hidden')) {
                sel.show();
            } else {
                sel.hide();
            }
            //阻止事件冒泡
            e.stopPropagation();
        })
        //点击其他区域隐藏下拉框
        $(".main").on("click",function(){
            $(".selectTime").hide();
        })
        var docEl = doc.documentElement,
            // 手机旋转事件,大部分手机浏览器都支持 onorientationchange 如果不支持，可以使用原始的 resize
            resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
            recalc = function() {
                //clientWidth: 获取对象可见内容的宽度，不包括滚动条，不包括边框
                var clientWidth = docEl.clientWidth;
                if (!clientWidth) return;
                var maxSize = (20 * (clientWidth / 375)) > 40 ? 40 : (20 * (clientWidth / 375));
                docEl.style.fontSize = maxSize + 'px';
            };
        recalc();
        //判断是否支持监听事件 ，不支持则停止
        if (!doc.addEventListener) return;
        //注册翻转事件
        win.addEventListener(resizeEvt, recalc, false);
    })(document, window);
<?php echo '</script'; ?>
>

</html><?php }
}
?>