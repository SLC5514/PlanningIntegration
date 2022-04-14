<?php /* Smarty version 3.1.27, created on 2020-06-23 09:19:37
         compiled from "/data/htdocs/pop136_yuntu/views/virtual/shareVirtual.html" */ ?>
<?php
/*%%SmartyHeaderCode:654130475ef158a91a7016_20047998%%*/
if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'be6c2bb03200c00ecb58b113fe1fb610cb1adcc9' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/virtual/shareVirtual.html',
      1 => 1592277615,
      2 => 'file',
    ),
    '4b22eedd05bdc48aa69395e981b7badb50229f97' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/layout.html',
      1 => 1587877444,
      2 => 'file',
    ),
    '7a3471a19922688028dba0b6207bef87c8feb77b' => 
    array (
      0 => '7a3471a19922688028dba0b6207bef87c8feb77b',
      1 => 0,
      2 => 'string',
    ),
    'c270d5b9b9b9fca55e14fe96d756c566613109c6' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/header.html',
      1 => 1592644410,
      2 => 'file',
    ),
    'd7b3caf3feb32cf5baf7c8d4f709ddd708de9ab9' => 
    array (
      0 => 'd7b3caf3feb32cf5baf7c8d4f709ddd708de9ab9',
      1 => 0,
      2 => 'string',
    ),
    'fe4fcce8d5885cea76541ca0fff402cb1e44b98c' => 
    array (
      0 => 'fe4fcce8d5885cea76541ca0fff402cb1e44b98c',
      1 => 0,
      2 => 'string',
    ),
    'a91b5d00af2d271a5ee5bd89fa15b4701643d6db' => 
    array (
      0 => '/data/htdocs/pop136_yuntu/views/footer.html',
      1 => 1587877444,
      2 => 'file',
    ),
    '3c64392c01949b44f25c5475a2e63fb27694ac1d' => 
    array (
      0 => '3c64392c01949b44f25c5475a2e63fb27694ac1d',
      1 => 0,
      2 => 'string',
    ),
    '04a3070bb5735fa0b233ce99559f2860f9f5fc78' => 
    array (
      0 => '04a3070bb5735fa0b233ce99559f2860f9f5fc78',
      1 => 0,
      2 => 'string',
    ),
  ),
  'nocache_hash' => '654130475ef158a91a7016_20047998',
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
  'unifunc' => 'content_5ef158a96f93b2_56229141',
),false);
/*/%%SmartyHeaderCode%%*/
if ($_valid && !is_callable('content_5ef158a96f93b2_56229141')) {
function content_5ef158a96f93b2_56229141 ($_smarty_tpl) {

$_smarty_tpl->properties['nocache_hash'] = '654130475ef158a91a7016_20047998';
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
$_smarty_tpl->properties['nocache_hash'] = '654130475ef158a91a7016_20047998';
?>

<link rel="stylesheet" type="text/css"
    href="<?php echo @constant('STATIC_URL1');?>
/global/css/virtual/share-virtual.css?<?php echo @constant('STATIC_CHANGE_TIME');?>
" />


    </head>
<body>
    <?php echo $_smarty_tpl->tpl_vars['token']->value;?>

    <div class="view">
        <?php
$_smarty_tpl->properties['nocache_hash'] = '654130475ef158a91a7016_20047998';
?>


        <?php
$_smarty_tpl->properties['nocache_hash'] = '654130475ef158a91a7016_20047998';
?>

<div class="content js-content on">
    <input id="params" name="params" type="hidden" data-isencrypt="<?php echo $_smarty_tpl->tpl_vars['isEncrypt']->value;?>
">
    <div class="header">
        <a href="https://yuntu.pop136.com/home"><img src="<?php echo @constant('STATIC_URL2');?>
/global/images/common/logo2.png"
                alt=""></a>
        <span>智能设计，让打样更快速</span>
    </div>
    <div class="empty-ctn">
        <img src="<?php echo @constant('STATIC_URL2');?>
/global/images/virtual/share/share_empty.png" alt="">
        <div class="title">哈尼,您访问的页面不存在了</div>
        <div class="msg">
            <p>可能原因:</p>
            <p>1.访问地址错误;</p>
            <p>2.访问的链接已过期;</p>
            <p>3.访问的文件已取消分享;</p>
        </div>
    </div>
    <div class="banner">
        <div class="section">
            <div class="title js-group-name"><?php echo $_smarty_tpl->tpl_vars['groupName']->value;?>
</div>
            <p>2D实景模拟 让打样更快速，让生产更智能</p>
            <span>POP云图 智能云试衣系统</span>
        </div>
    </div>
    <div class="section js-section">
        <ul class="vir-list js-vir-list clear">
            <!-- <li><img src="<?php echo @constant('STATIC_URL2');?>
/global/images/user/placeholder.png" alt=""></li>  -->
        </ul>
        <div class="nav-loading js-nav-loading"><i></i><span>加载中</span></div>
    </div>
    <div class="h5-footer">
        <div class="title">让设计更轻松，让打样更快速</div>
        <div class="info">官方网站：https://yuntu.pop136.com/</div>
        <a class="btn js-app-btn" href="javascript:;">下载云图APP查看更多</a>
    </div>
    <!-- 邀请码 -->
    <div class="invitation-code-box js-invitation-code-box">
        <div class="title">获取邀请码之后才能查看</div>
        <div class="ctn">
            <div class="ipt clear">
                <label class="fl" for="invCode">邀请码：</label>
                <input class="fl" id="invCode" type="text">
                <div class="msg">邀请码错误</div>
            </div>
        </div>
        <div class="btn-box">
            <button class="confirm">确定</button>
        </div>
    </div>
    <!-- 查看大图 -->
    <div class="preview-box js-preview-box">
        <div class="ctn">
            <div class="probar">1/1</div>
            <i class="close"></i>
            <i class="prev"></i>
            <i class="next"></i>
            <img src="" alt="">
        </div>
    </div>
</div>


        <?php
$_smarty_tpl->properties['nocache_hash'] = '654130475ef158a91a7016_20047998';
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
$_smarty_tpl->properties['nocache_hash'] = '654130475ef158a91a7016_20047998';
?>

<?php echo '<script'; ?>
 type="text/javascript">
    (function () {
        function autoRootFontSize() {
            var width = document.documentElement.getBoundingClientRect().width;
            document.documentElement.style.fontSize = (Math.min(screen.width, width < 750 ? width : 750) / 750) * 100 + 'px';
        }
        window.addEventListener('onorientationchange' in window ? 'orientationchange' : 'resize', autoRootFontSize, false);
        autoRootFontSize();
    })();

    var iosLinkUrl = "https://apps.apple.com/cn/app/pop%E4%BA%91%E5%9B%BE/id1436269034";
    var androidLinkurl = "https://android.myapp.com/myapp/detail.htm?apkName=com.pop136.cloudpicture";
    var u = navigator.userAgent, isAndroid, isIOS;
    window.onload = function () {
        init();
    }
    function init() {
        isAndroid = u.indexOf('Android') > -1 || u.indexOf('Linux') > -1;
        isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/);
        var link = isIOS ? iosLinkUrl : androidLinkurl;
        $('.js-app-btn').attr('href', link);
    }

    require.config({
        paths: {
            "perfectScrollbar": ["lib/perfect-scrollbar-1.2.min"],
        }
    });
    require(["jquery", "general", "msg"], function (jquery, general, msg) {
        $(function () {
            var def = {
                urlParams: general.fn.getLocationParameter() || {},
                iptParams: $('#params').data() || {},
                loadingEl: $('.js-loading-div'),
                loadingBgEl: $('.js-bg-div'),
                invCodeBoxEl: $('.js-invitation-code-box'),
                invCodeIpt: $('#invCode'),
                invCodeMsg: $('#invCode').siblings('.msg'),
                screenType: $('.js-section').width() <= 750 ? 2 : ($('.js-section').width() < 1500 ? 1 : 0), // 大屏0, 小屏1, 移动2
                virData: {
                    el: $('.js-vir-list'),
                    page: 0,
                    pageSize: 30,
                    total: 0,
                    isLoad: false,
                    pwd: '',
                    group_name: '',
                },
                previewData: {
                    idx: 0,
                    pageX: 0,
                    pageY: 0,
                    moveNum: 0
                }
            }

            if (def.iptParams.isencrypt === 1) {
                loadingToggle(false);
                def.invCodeBoxEl.fadeIn(200);
            } else {
                getShareList(true);
            }

            $(window).on("resize", function () {
                general.fn.throttle(waterFall, null, ['.js-vir-list', true], 18);
            });
            $('.js-content').on("scroll", function () {
                general.fn.throttle(scrollLoad, this, [], 18);
            });

            // 邀请码
            def.invCodeBoxEl.on('click', '.confirm', function () {
                var pwd = def.invCodeIpt.val();
                def.virData.pwd = pwd;
                if (!pwd) {
                    def.invCodeMsg.show();
                } else {
                    def.invCodeMsg.hide();
                    getShareList(false, pwd, function() {
                        def.invCodeBoxEl.fadeOut(200);
                    });
                }
            })

            // 查看大图
            def.virData.el.on('click', 'li', function () {
                var idx = $(this).index();
                def.previewData.idx = idx;
                loadingToggle(true);
                $('.js-preview-box').fadeIn(200).find('img').attr('src', $(this).find('img').attr('src'));
                if (def.screenType === 2) {
                    var imgs = def.virData.el.find('img');
                    $('.js-preview-box img').css('left', 0);
                    $('.js-preview-box .probar').text(def.previewData.idx + 1 + '/' + imgs.length);
                }
            })
            $('.js-preview-box').on('click', '.close', function () {
                $('.js-preview-box').fadeOut(200)
                loadingToggle(false);
            }).on('click', '.prev', function () {
                if (def.previewData.idx > 0) {
                    def.previewData.idx--;
                    loadingToggle(true);
                    $('.js-preview-box').fadeIn(200).find('img').attr('src', def.virData.el.children().eq(def.previewData.idx).find('img').attr('src')).on('load', function () {
                        $(this).parent().height(this.height)
                        def.loadingEl.fadeOut(200);
                    })
                }
                console.log(def.previewData.idx)
            }).on('click', '.next', function () {
                if (def.previewData.idx < def.virData.el.children().length - 1) {
                    def.previewData.idx++;
                    loadingToggle(true);
                    $('.js-preview-box').fadeIn(200).find('img').attr('src', def.virData.el.children().eq(def.previewData.idx).find('img').attr('src')).on('load', function () {
                        $(this).parent().height(this.height)
                        def.loadingEl.fadeOut(200);
                    })
                }
                console.log(def.previewData.idx)
            }).on('touchstart', 'img', function (e) {
                var e = e || window.event;
                var touches = e.originalEvent.changedTouches[0];
                def.previewData.pageX = touches.pageX;
                def.previewData.pageY = touches.pageY;
            }).on('touchmove', 'img', function (e) {
                var e = e || window.event;
                var touches = e.originalEvent.changedTouches[0];
                def.previewData.moveNum = def.previewData.pageX - touches.pageX;
                $(this).css({
                    left: -def.previewData.moveNum + 'px'
                })
            }).on('touchend', 'img', function (e) {
                var e = e || window.event;
                var touches = e.originalEvent.changedTouches[0];
                var imgs = def.virData.el.find('img');
                if (def.previewData.moveNum > 100 && def.previewData.idx < imgs.length) {
                    def.previewData.idx++;
                    def.loadingEl.fadeIn(200);
                    $('.js-preview-box img').animate({
                        left: '0px'
                    }, 200).attr('src', imgs[def.previewData.idx].getAttribute('src')).on('load', function () {
                        def.loadingEl.fadeOut(200);
                    })
                    $('.js-preview-box .probar').text(def.previewData.idx + 1 + '/' + imgs.length);
                } else if (def.previewData.moveNum < -100 && def.previewData.idx > 0) {
                    def.previewData.idx--;
                    def.loadingEl.fadeIn(200);
                    $('.js-preview-box img').animate({
                        left: '0px'
                    }, 200).attr('src', imgs[def.previewData.idx].getAttribute('src')).on('load', function () {
                        def.loadingEl.fadeOut(200);
                    })
                    $('.js-preview-box .probar').text(def.previewData.idx + 1 + '/' + imgs.length);
                } else {
                    def.loadingEl.fadeOut(200);
                    $(this).animate({
                        left: '0px'
                    }, 200);
                }
            })

            $('.js-preview-box img').on('load', function () {
                $(this).parent().height(this.height).css({
                    'margin-top': ($(window).height() - this.height) / 2,
                    'margin-bottom': ($(window).height() - this.height) / 2
                })
                def.loadingEl.fadeOut(200);
            }).on('error', function () {
                console.log('加载失败');
            });

            // 加载下一页
            function scrollLoad() {
                var lastEl = def.virData.el.children().last();
                var lastOffsetTop = lastEl.offset().top;
                var reH = $(window).height() - lastEl.height();
                if (!def.isLoad && lastOffsetTop <= reH) {
                    getShareList(false, def.virData.pwd);
                }
            }

            // 瀑布流
            function waterFall(strName, loop) {
                var boxEl = $(strName);
                var itemEls = boxEl.find('li');
                var rootSize = parseFloat($('html').css('font-size'));
                var smallScreen = $('.js-section').width() <= 750 ? 2 : ($('.js-section').width() < 1500 ? 1 : 0); // 大屏0, 小屏1, 移动2
                var pageWidth = smallScreen === 2 ? $('.js-section').width() - rootSize * 0.3 * 2 : $('.js-section').width();
                var columns = smallScreen === 0 ? 6 : (smallScreen === 1 ? 5 : 3);
                var itemWidth = smallScreen === 2 ? rootSize * 2.1 : 210;
                var space = (pageWidth - itemWidth * columns) / (columns - 1);
                var spaceBtm = smallScreen === 2 ? rootSize * 0.3 : 48;
                var defh = smallScreen === 2 ? rootSize * 2.76 : 276;
                var colHArr = [];
                var maxTop = 0;
                for (var i = 0; i < columns; i++) {
                    colHArr[i] = 0;
                }
                itemEls.each(function (i, v) {
                    var item = $(v);
                    var itemImg = $(v).find('img');
                    var h = itemImg.data('h');
                    var minValue = colHArr[0];
                    var minIndex = 0;
                    if (!h) {
                        h = defh;
                        itemImg.data('h', defh);
                        if (!loop) {
                            itemImg.on('load', function () {
                                $(this).data('h', this.height);
                                waterFall(strName, true);
                            }).on('error', function () {
                                $(this).data('h', defh).height(defh);
                                waterFall(strName, true);
                            })
                        }
                    }
                    for (var i = colHArr.length; i >= 0; i--) {
                        if (colHArr[i] <= minValue) {
                            minValue = colHArr[i];
                            minIndex = i;
                        }
                    }
                    item.css({
                        left: minIndex * itemWidth + minIndex * space,
                        top: minValue + (i > columns - 1 ? spaceBtm : 0)
                    })
                    colHArr[minIndex] += h + spaceBtm;
                    var top = parseInt(item.css('top'));
                    if (maxTop < top + h) {
                        maxTop = top + h;
                        boxEl.height(maxTop);
                    }
                })
            }

            // 页面加载控制
            function loadingToggle(type) {
                if (type === true) {
                    def.loadingEl.fadeIn(200);
                    def.loadingBgEl.fadeIn(400);
                } else if (type === false) {
                    def.loadingEl.fadeOut(200);
                    def.loadingBgEl.fadeOut(400);
                } else {
                    def.loadingEl.fadeToggle(200);
                    def.loadingBgEl.fadeToggle(400);
                }
            }

            // 获取单个分组下模板图列表
            function getShareList(type, pwd, callback) {
                if (def.virData.page && def.virData.total && (def.virData.page * def.virData.pageSize >= def.virData.total)) {
                    return;
                }
                def.virData.page++;
                def.isLoad = true;
                if (type) {
                    loadingToggle(true);
                } else {
                    $('.js-nav-loading').fadeIn(200);
                }
                $.ajax({
                    url: location.pathname,
                    type: 'post',
                    dataType: "json",
                    data: {
                        pwd: pwd,
                        page: def.virData.page,
                        pageSize: def.virData.pageSize
                    },
                    success: function (res) {
                        if (res.code === 0) {
                            def.virData.total = res.data.count;
                            var list = res.data.list;
                            var str = '';
                            for (var i = 0; i < list.length; i++) {
                                str += '<li data-id="' + list[i].id + '"><img src="' + general.def.img_path + list[i].sTemplateRender + '" alt=""></li>';
                            }
                            def.virData.group_name = res.data.group_name;
                            $('.js-group-name').text(res.data.group_name);
                            def.virData.el.append(str);
                            $('.js-content').removeClass('on');
                            waterFall('.js-vir-list');
                            callback && callback();
                        } else if (res.code === 1003) {
                            def.invCodeIpt.val('');
                            def.invCodeMsg.show();
                            def.virData.page = 0;
                        } else if (res.code === 1004) {
                            $('.js-content').addClass('empty-box');
                            callback && callback();
                        } else {
                            msg.msg({ "txt": res.msg }, 2000);
                            callback && callback();
                        }
                    },
                    error: function (err) {
                        console.log('for bug: ' + err)
                    },
                    complete: function () {
                        def.isLoad = false;
                        loadingToggle(false);
                        $('.js-nav-loading').fadeOut(200);
                    }
                })
            }

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
/*%%SmartyHeaderCode:16187000625ef158a94685f9_00519213%%*/
if ($_valid && !is_callable('content_5ef158a94673f0_25441781')) {
function content_5ef158a94673f0_25441781 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '16187000625ef158a94685f9_00519213';
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
/*/%%SmartyNocache:16187000625ef158a94685f9_00519213%%*/
}
}
?><?php
/*%%SmartyHeaderCode:6494894495ef158a95ac113_89196871%%*/
if ($_valid && !is_callable('content_5ef158a95ab4f0_14058995')) {
function content_5ef158a95ab4f0_14058995 ($_smarty_tpl) {
?>
<?php
$_smarty_tpl->properties['nocache_hash'] = '6494894495ef158a95ac113_89196871';
?>
<!-- footer start -->
<div class="footer">
    <span>上海逸尚云联信息技术股份有限公司 ©2004-2020 法律顾问：北京中银（上海）律师事务所 <a href="http://www.beian.miit.gov.cn/" target="_blank">沪ICP备06003020号-1</a></span>
</div>
<!-- footer end --><?php
/*/%%SmartyNocache:6494894495ef158a95ac113_89196871%%*/
}
}
?>