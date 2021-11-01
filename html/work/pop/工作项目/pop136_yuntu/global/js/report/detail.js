/*
 * @Author: SLC
 * @Date: 2020-06-19 16:11:38
 * @LastEditors: SLC
 * @LastEditTime: 2020-06-30 13:50:55
 * @Description: file content
 */
require.config({
    paths: {
        "jqcookie": ["lib/jquery.cookie"],
        "jqueryUi": ["lib/jquery-ui.min"],
        "mCustomScrollbar": ["lib/jquery.mCustomScrollbar.concat.min"],
        "scaleImg": ["report/scale-img-1.0"]
    },
    shim: {
        "jqcookie": {
            deps: ["jquery"]
        },
        "jqueryUi": {
            deps: ["jquery"],
            exports: "jqueryUi"
        },
        "mCustomScrollbar": {
            deps: ["css!" + STATIC_URL3 + "/global/css/lib/jquery.mCustomScrollbar.css", "jquery", "mousewheel"]
        },
        "scaleImg": {
            exports: "scaleImg"
        }
    }
});
require(["jquery", "general", "msg", 'scaleImg', "mCustomScrollbar", 'jqcookie', "jqueryUi"], function (jquery, general, msg, scaleImg) {
    $(function () {
        //屏蔽右键菜单
        $('body')[0].oncontextmenu = function (event) {
            if (window.event) {
                event = window.event;
            }
            try {
                return false;
            } catch (e) {
                return false;
            }
        }

        var statistics_action_token = '';
        if (typeof statistics_token == 'undefined') {
            statistics_action_token = '';
        } else {
            statistics_action_token = statistics_token;
        }
        var pVisit = 0;
        if (P_UserType_action == 1) {
            pVisit = 1;
        }
        // var P_UserType_action = '4';
        // if (userType == 'VIP') {
        //     P_UserType_action = '1';
        //     pVisit = 1;
        // } else if (userType == 'PROBATION') {
        //     P_UserType_action = '2';
        // } else if (userType == 'GENERAL') {
        //     P_UserType_action = '3';
        // } else if (userType == 'TOURIST') {
        //     P_UserType_action = '4';
        // }
        var params = general.fn.getLocationParameter();
        var table = params.t || '';
        var id = params.id || '';
        var col = params.col || '';

        // 切换图片数据
        var _def = {
            down_list: [],
            page: 0
        }
        var def = {
            loadingEl: $('.js-loading-div'),
            loadingBgEl: $('.js-bg-div'),
            tipEl: $('.tip-box'),
            timeout: null,
            params: params,
            reportData: {},
            link_reg_bef: /^http+s?:\/\//i,
            img_prefix: 'https://imgyt3.pop-fashion.com',
            img_imgf1: 'https://imgyt1.pop-fashion.com',
            // img_prefix: 'https://imgf3.pop-fashion.com',
            // img_imgf1: 'https://imgf1.pop-fashion.com',

            show_img_model: null,
            is_set_img_model: false,
            is_review: false,       // 真实查看
            action_down: true,   //下载统计
            action_timer: null,

            action: {
                token: statistics_action_token,
                iUserType: P_UserType_action,       //用户类型
                iUserId: userId,           //用户ID
                sChildUserId: '',       //子账号id
                sTableName: table,      //假表名
                iPrid: id,              //当前详情ID
                iColumnId: '',          //主栏目ID
                iSubColumnId: col,          //子栏目ID
                sSelfUrl: location.href,            //当前URL
                sRefererUrl: document.referrer||'',     //上个页面URL
                iSite: 1,               //站点
                sLang: 'cn',            //语言
                iVisit: pVisit||0,          //是否能访问 0-无访问权限 1-有访问权限
            }
        };
        var download_list_length = 0;

        /* =================== 格子操作 =================== */

        // 查看大图
        $(".js-pop-report>.layer-main").on("mouseenter", ".js-report-box", function () {
            $(this).next('.js-txt-div').addClass('on');
        }).on("mouseleave", ".js-report-box", function () {
            $(this).next('.js-txt-div').removeClass('on');
        });
        $(".js-pop-report>.layer-main").on("click", ".js-report-section .js-show-bg-btn", function (e) {
            var src = $(this).attr("data-src") || "";
            var id = $(this).attr("data-id") || "";
            var rename = $(this).attr("data-rename") || "";
            var downhref = $(this).attr("data-downhref") || "";
            $('.js-show-pic-section').addClass('status');
            $('.js-recommend').hide();
            //禁止浏览器滚动
            document.body.style.overflow = 'hidden';
            document.addEventListener("touchmove", bodyScroll, false);//禁止页面滑动
            // src="https://imgf3.pop-fashion.com/fashion/styledetail/2018022604465019456/EuropeUS/man/1718AW/159/outerwear/sleeve/big/150bc1f1ec320992b5c0934088e39da6.jpg";
            if (src != "") {
                modelChangeImg(src);
                $('.js-show-pic-section').show();
                $('.js-show-pic-bg').show();
                var nimg = new Image();
                nimg.src = $('.js-down-img')[0].src;
                $('.js-down-img')[0].onload = function () {
                    $('.js-gifImg').hide();
                    $('.js-down-img').show();
                };
                $('.js-src').each(function () {
                    $(this).attr('data-src', src)
                    $(this).attr('data-id', id)
                    $(this).attr('data-rename', rename)
                    $(this).attr('data-downhref', downhref)
                })
                downPage(id);
            } else {
                console.log("图片链接为空！");
            }
            e.stopPropagation();
        });

        // 切换图片
        $('.js-i-lfet').on('click', function () {
            --_def.page;
            if (_def.page < 0) {
                _def.page = _def.down_list.length - 1;
            }
            var src = _def.down_list[_def.page].photo.big;
            var id = _def.down_list[_def.page].photo.id;
            var rename = _def.down_list[_def.page].photo.rename;
            if (src.indexOf('fm.pop-fashion.com') != -1) {
                src = def.img_prefix + src.split('fm.pop-fashion.com')[1];
            }
            src = src ? (def.link_reg_bef.test(src) ? src : (def.img_prefix + src)) : '';
            modelChangeImg(src);
            $('.js-src').each(function () {
                $(this).attr('data-src', src)
                $(this).attr('data-id', id)
                $(this).attr("data-rename", rename)
            })
            $('.js-show-pic-section').addClass('status');
            if (id.indexOf('col') > 0) {
                $('.js-recommend').show();
            } else {
                $('.js-recommend').hide();
            }
        });
        $('.js-i-right').on('click', function () {
            ++_def.page;
            if (_def.page >= _def.down_list.length) {
                _def.page = 0;
            }
            var src = _def.down_list[_def.page].photo.big;
            var id = _def.down_list[_def.page].photo.id;
            var rename = _def.down_list[_def.page].photo.rename;
            if (src.indexOf('fm.pop-fashion.com') != -1) {
                src = def.img_prefix + src.split('fm.pop-fashion.com')[1];
            }
            src = src ? (def.link_reg_bef.test(src) ? src : (def.img_prefix + src)) : '';
            modelChangeImg(src);
            $('.js-src').each(function () {
                $(this).attr('data-src', src)
                $(this).attr('data-id', id)
                $(this).attr("data-rename", rename)
            })
            $('.js-show-pic-section').addClass('status');
            if (id.indexOf('col') > 0) {
                $('.js-recommend').show();
            } else {
                $('.js-recommend').hide();
            }
        });

        // 下载图片
        // var encrypt = $("#encrypt");
        // var cur_params;
        $(".js-pop-report>.layer-main,.js-show-pic-section").on("click", ".js-download-btn2", function (event) {
            event.stopPropagation();
            if (userType != 'VIP' || col_user_type === 'TRIAL') {
                msg.msg({ txt: "升级为正式VIP后才能下载" }, 2000);
                return;
            }
            var id = $(this).attr("data-id") || "";
            var src = $(this).attr("data-src") || "";
            var rename = $(this).attr("data-rename") == undefined || $(this).attr("data-rename") == "" ? "" : $(this).attr("data-rename");
            src = src + '?rename=' + rename;
            if (!isNaN(id)) {
                down(src, "", "", "");
                // 下载统计
                // actionFunc(def.params.t, id, 'download', request_id, scene_type);
                actionDown();
                return false;
            }
            var _t = id.split('t_')[1] || '';
            _t = _t.split('-')[0] || '';
            var _col = id.split('col_')[1] || '';
            _col = parseFloat(_col) || 0;
            var _id = id.split('id_')[1] || '';
            _id = _id.split('-col_')[0] || '';
            // var timeStamp = encrypt.data("timestamp");
            // var sign = encrypt.data("sign");
            // var token = encrypt.data("token");
            // var s_p = {t:_t,id:_id,col:_col,timeStamp:timeStamp,sign:sign};            
            // var s_p_str = JSON.stringify(s_p);
            // cur_params ={s_p:rsa_encrypt(s_p_str),token:token};
            if (id != "") {
                getDownload($(this).attr('data-downhref'), _t, _id, _col);
            } else {
                alert("无法下载图片！");
            }
        });

        $(".js-download-btn5").on("click", function () {
            // 下载统计
            // actionFunc(def.params.t, id, 'download', request_id, scene_type);
            actionDown();
        });

        // 下载按钮
        $(".js-download-img-box").on("click", ".js-download-file-btn", function () {
            var bp = $(this).attr("data-bp") || "";
            var table = $(this).attr("data-table") || "";
            var id = $(this).attr("data-id") || "";
            var col = $(this).attr("data-col") || "";
            down(bp, table, id, col);
            if (download_list_length < 2) {
                $(".js-download-img-box").hide();
                $(".js-bg-div").hide();
            }
            // 下载统计
            // actionFunc(table, id, 'download', request_id, scene_type);
            actionDown();

        });

        // 关闭下载框
        $(".js-down-close").on("click", function () {
            $(".js-download-img-box").hide();
            $(".js-bg-div").hide();
        });

        // 报告视频
        var $rpt_player = $("#reportPlayer");
        var reportPlayer;
        $(".js-pop-report>.layer-main").on('click', '.js-report-section .js-video-play', function () {
            $('.js-report-video-bg').show();
            $('.js-report-video-section').show();
            if ($rpt_player.length) {
                reportPlayer = video_player('reportPlayer', $(this).attr("data-videoId"));
            }
        });
        $('.js-report-video-section').on('click', '.close', function () {
            $('.js-report-video-bg').hide();
            $('.js-report-video-section').hide();
            // try { reportPlayer.pauseVideo(); } catch {}
            $rpt_player.html('');
        });

        var scroll_item_index = -1;
        var canRun = true;
        $(window).on('scroll', function () {
            var _t = $(window).scrollTop();
            if (_t > 200) {
                $('.js-go-top').show();
            } else {
                $('.js-go-top').hide();
            }
            var layer_list = $('.layer-main>.indexdiv'), layer_list_len = layer_list.length;
            layer_list.each(function (i, n) {
                var this_t = $(this).offset().top;
                if (_t > this_t - 2) {
                    scroll_item_index = i;
                    $('.js-see-nav1>ul>li').eq(scroll_item_index).addClass('this-item').siblings('li').removeClass('this-item');
                    $('.js-see-nav2>ul>li').eq(scroll_item_index).addClass('this-item').siblings('li').removeClass('this-item');
                }
            });
            //如果有大纲导航
            if ($('.layer-indexdiv-main').length > 0) {
                var max_nav_top = $('.layer-indexdiv-main').offset().top + $('.layer-indexdiv-main').height();
                if (_t >= max_nav_top) {
                    $('.js-see-nav1>ul>li').removeClass('this-item');
                    $('.js-see-nav2>ul>li').removeClass('this-item');
                }
            }
            //真实浏览数据判断条件
            if (!def.is_review) {
                if (layer_list_len >= 6) {
                    if (scroll_item_index + 1 >= 6) {
                        actionRview();
                    }
                } else if (6 > layer_list_len && layer_list_len > 0) {
                    if (scroll_item_index + 1 == layer_list_len) {
                        actionRview();
                    }
                } else {
                    if ($(".report_bottom_infor").length > 0) {
                        var setTop = $(".report_bottom_infor").offset().top;
                        if (setTop - (_t + 600) <= 0) {
                            actionRview();
                        }
                    }
                }
            }
            upScroll();
        });

        function upScroll() {
            /* var Scroll = $('.tit-info').offset().top; */
            var _t = $(window).scrollTop();
            if (200 > _t) {
                $('.js-sTitle').hide();
            } else {
                $('.js-sTitle').show();
            }
        }
        upScroll();

        // 键盘上下键子专题跳转
        var _href;
        $(window).on('keydown', function (event) {
            var event = event ? event : (window.event ? window.event : null);
            var max_num = $(".js-see-nav1 li").length;
            if (event && event.which == 40) {
                scroll_item_index++;
                _href = $(".js-see-nav1 li").eq(scroll_item_index).find("a").attr("href");
                if (scroll_item_index > max_num - 1) {
                    scroll_item_index = max_num - 1;
                    return false;
                }
                $('.js-see-nav1 li').eq(scroll_item_index).find("a").trigger('click');
                return false;
            } else if (event && event.which == 38) {
                if (scroll_item_index >= max_num - 1 && $(".js-see-nav1 li.this-item").length == 0) {
                    scroll_item_index = scroll_item_index + 1;
                }
                scroll_item_index--;
                _href = $(".js-see-nav1 li").eq(scroll_item_index).find("a").attr("href");
                if (scroll_item_index <= -1) {
                    scroll_item_index = 0;
                    return false;
                }
                $('.js-see-nav1 li').eq(scroll_item_index).find("a").trigger('click');
                return false;
            }   //向上        
        });

        //导航按键收缩
        $(document).on('keydown', function (ev) {      //按键
            var e = ev || event;
            var _v = e.keyCode;
            if (_v == 37) {
                navMaxShow();
            } else if (_v == 39) {
                navMinShow();
            }
        });

        /* =================== 控制记忆 =================== */

        // 右边导航栏控制记忆
        var report_nav_control = $.cookie('report_nav_control', { path: '/', domain: '.yuntu.pop136.com' }) || '';
        if (report_nav_control == 1) {
            $('.js-nav-control-disc').hide();       //控制说明显示
        } else {
            $('.js-nav-control-disc').show();
        }

        // 底部说明控制记忆
        var report_prompt_disc = $.cookie('report_prompt_disc', { path: '/', domain: '.yuntu.pop136.com' });
        if (report_prompt_disc == 1) {
            $('.js-prompt>.prompt-disc').hide();        // 控制说明显示
        } else {
            $('.js-prompt>.prompt-disc').show();
        }

        /* =================== 底部操作 =================== */

        $('.dic-close').on('click', function (ev) {
            general.fn.stopBubble(ev);       //阻止事件冒泡
            $.cookie('report_prompt_disc', 1, { path: '/', expires: 365, domain: '.yuntu.pop136.com' });
            $(this).parents('.prompt-disc').hide();
        })

        // 点击显示底部说明
        $('.js-prompt').on('click', function () {
            $(this).find('.prompt-disc').toggle();
        })

        //回到顶部
        $('.js-go-top').on('click', function () {
            $("html,body").animate({
                "scrollTop": "0"
            }, 700);
        });

        // 下载pdf, 图片打包下载
        $("#J_DownPDF1, .report-down-imgpack").on("click", function () {
            if (userType != 'VIP' || col_user_type === 'TRIAL') {
                msg.msg({ txt: "升级为正式VIP后才能下载" }, 2000);
                return;
            }
            var self = $(this);
            var path = self.data("path");
            var rename = self.data("rename");
            var type = self.hasClass('report-down-imgpack') ? 'zip' : 'pdf';
            if (!path) {
                if (type === 'pdf') {
                    msg.msg({ txt: "pdf生成中" }, 2000);
                } else {
                    msg.msg({ txt: "图片包生成中" }, 2000);
                }
                return;
            }
            var fileType = path.split('.').pop(); // 下载文件格式
            path += '?rename=' + rename + '.' + fileType;
            // 下载统计
            // actionFunc(def.params.t, self.data('id'), 'download', request_id, scene_type);
            actionDown(type);
            window.location.href = '/download/downImg/?dl_link=' + encodeURIComponent(path) + '&' + Math.random();
        });

        //收藏
        $('.js-report-collect').on('click', function () {
            var self = $(this);
            var iscollected = self.data('iscollected') * 1 || 0;
            if (iscollected == 2) {
                msg.msg({ txt: "对不起，只有设计师专属账号才能使用此功能" }, 2000);
                return;
            }
            collectFunc(self);
        })

        /* =================== 右侧导航 =================== */

        // ie10 filter
        if (!!window.ActiveXObject || "ActiveXObject" in window){ 
            $(".js-fiter-img").find("img").hide(); 
        }else{ 
            $(".js-fiter-img").find("img").show();
        }

        //右边导航显示小的
        var page_min_w = 1500;
        function navMinShow() {
            $('.js-nav-right').hide();
            $('.js-nav-right-min').fadeIn(200);

            $('.report-footer>div,.js-trial-pr').stop().animate({       //右边空白
                "padding-right": "0px"
            }, 100);
            $('.report-cont').stop().animate({      //右边空白
                "padding-right": "100px"
            }, 100);
            $('.report-contant').css({
                "min-width": "1200px"
            })
            $(".js-fiter-img").hide();
        }
        //右边导航显示大的
        function navMaxShow() {
            $('.js-nav-right-min').hide();
            $('.js-nav-right').stop().show().animate({
                "right": "0"
            }, 100);
            $('.report-cont,.report-footer>div,.js-trial-pr').stop().animate({      //右边空白
                "padding-right": "380px"
            }, 100);

            $('.report-contant').css({
                "min-width": page_min_w + "px"
            })
            $(".js-fiter-img").show();
        }
        $('.js-see-nav1>ul>li>a,.js-see-nav2>ul>li>a').on('click', function () {
            var i = $(this).parent().index();
            $('.js-see-nav1>ul>li').eq(i).addClass('this-item').siblings('li').removeClass('this-item');
            $('.js-see-nav2>ul>li').eq(i).addClass('this-item').siblings('li').removeClass('this-item');
        })

        //nav收缩
        $('.js-nav-control1').on('click', function () {
            navMinShow();
        })

        //nav展开
        $('.js-nav-control2').on('click', function () {
            navMaxShow();
            // bookMsgTxtFn();
        })

        //关闭导航提示
        $('.js-nav-control-disc>div').on('click', function (ev) {
            general.fn.stopBubble(ev);       //阻止事件冒泡
            $('.js-nav-control-disc').hide();
            $.cookie('report_nav_control', 1, { path: '/', expires: 365, domain: '.yuntu.pop136.com' });
        })

        //文案提示框
        $('.js-nav-control-disc,.prompt-disc').on('click', function (ev) {
            general.fn.stopBubble(ev);       //阻止事件冒泡
        })

        $('.report-msg').hover(function () {
            var _t = $(this).find('span');
            if (_t.length > 0) {
                $(this).find('.report-messages').stop().show();
            }
        }, function () {
            $(this).find('.report-messages').stop().hide();
        });

        $('.js-see-nav1,.js-see-nav2').on('click', 'ul>li>a', function () {
            var i = $(this).parent().index();
            $('.js-see-nav1>ul>li').eq(i).addClass('this-item').siblings('li').removeClass('this-item');
            $('.js-see-nav2>ul>li').eq(i).addClass('this-item').siblings('li').removeClass('this-item');
        })

        /* =================== 视窗事件 =================== */

        $(window).on('scroll', function () {
            var _t = $(window).scrollTop();
            if (_t > 200) {
                $('.js-go-top').show();
            } else {
                $('.js-go-top').hide();
            }
        });

        $(window).on('resize', function () {
            pageSize();
        });

        /* =================== 方法 =================== */

        function bodyScroll(event) {
            event.preventDefault();
        }

        function modelChangeImg(src) {
            if (def.is_set_img_model == false) {
                def.is_set_img_model = true;
                def.show_img_model = new scaleImg({
                    "box": ".js-show-pic-section",
                    "src": src
                });
            } else {
                def.show_img_model.changeImg(src);
            }
        }

        //切换图片数据获取
        function downPage(id) {
            _def.down_list = [];
            var gridList = [], layerList = [];
            var flashPages = def.reportData.flashPages;
            for (var item in flashPages) {
                if (flashPages[item].sContent.grids != undefined && flashPages[item].sContent.grids != '') {
                    for (var _item in flashPages[item].sContent.grids) {
                        var _id = flashPages[item].sContent.grids[_item].photo.id;
                        if (_id == id) {
                            for (var grid in flashPages[item].sContent.grids) {
                                if (flashPages[item].sContent.grids[grid].photo.big != '') {
                                    gridList.push(flashPages[item].sContent.grids[grid]);
                                }
                            }
                            for (var layer in flashPages[item].sContent.layers) {
                                if (flashPages[item].sContent.layers[layer].photo.big != '') {
                                    layerList.push(flashPages[item].sContent.layers[layer]);
                                }
                            }
                            _def.page = _item;
                        }
                    }
                }
                if (flashPages[item].sContent.layers != undefined && flashPages[item].sContent.layers != '') {
                    for (var _item in flashPages[item].sContent.layers) {
                        var _id = flashPages[item].sContent.layers[_item].photo.id;
                        if (_id == id) {
                            for (var layer in flashPages[item].sContent.layers) {
                                if (flashPages[item].sContent.layers[layer].photo.big != '') {
                                    layerList.push(flashPages[item].sContent.layers[layer]);
                                }
                            }
                            for (var grid in flashPages[item].sContent.grids) {
                                if (flashPages[item].sContent.grids[grid].photo.big != '') {
                                    gridList.push(flashPages[item].sContent.grids[grid]);
                                }
                            }
                            _def.page = _item;
                        }
                    }
                }
            }
            _def.down_list = gridList.concat(layerList);
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

        // tip提示控制
        function tipToggle(msg, type) {
            if (type) {
                def.tipEl.addClass('on');
            } else {
                def.tipEl.removeClass('on');
            }
            def.tipEl.text(msg).fadeIn(200);
            clearTimeout(def.timeout);
            def.timeout = setTimeout(function () {
                def.tipEl.fadeOut(200);
                clearTimeout(def.timeout);
            }, 2000);
        }

        var nav_control_w = 1500;
        if ($('.layer-main').hasClass('layer-main-max')) {        //大报告时
            $('body,html').addClass('min_w1302');
            page_min_w = 1600;
            nav_control_w = 1610;
        }
        pageSize();
        function pageSize() {        // 监听屏幕
            var _w = $(window).outerWidth(true);
            var _t = $(window).scrollTop();
            if (_t > 200) {         // 返回顶部显示
                $('.js-go-top').show();
            } else {
                $('.js-go-top').hide();
            }
            if (_w > nav_control_w) {
                navMaxShow();
            } else {
                navMinShow();
            }
            if (_w > 900) {
                $('.report-down,.report-collect,.report-share').removeClass('no-detail');
            } else {
                $('.report-down,.report-collect,.report-share').addClass('no-detail');
            }
        }

        // 获取详情数据
        var report_box = {
            ow: 2000,
            oh: 1125,
            nh: 626,
            nw: $(".js-pop-report>.layer-main").width()
        };
        var report_info = {
            k1: report_box.nw / report_box.ow
        };
        getDetail();
        function getDetail() {
            $.ajax({
                url: '/trendspattern/detail/',
                ctp: 'application/x-www-form-urlencoded',
                dataType: 'json',
                type: 'post',
                data: def.params,
                success: function (res) {
                    if (res.code == 0) {
                        def.reportData = res.data;
                        // 侧栏
                        $('.js-nav-right .right-top>h3').text(def.reportData.title);
                        $('.js-nav-right .js-report-msg>div,.js-nav-right .js-report-msg>p').text(def.reportData.description);
                        var report_msg = $('.js-nav-right .js-report-msg>div').text() || '';
                        var new_report_msg = report_msg.substring(0, 35) || '';
                        if (report_msg > 35) {new_report_msg += '...';}
                        // var new_report_msg = general.fn.cutByWidth(report_msg, 424, 14) || '';
                        if (new_report_msg != report_msg) {
                            $('.js-nav-right .js-report-msg>div').html(new_report_msg + ' ' + '<span>更多</span>');
                        }
                        var labels = def.reportData.aDetailLabels || [];
                        var labelStr = '';
                        for (var i = 0; i < labels.length; i++) {
                            labelStr += '<li><a target="_blank" href="' + labels[i].dLink + '" title="' + labels[i].name + '">' + labels[i].name + '</a></li>';
                        }
                        $('.js-nav-right .js-msg-i4>ul').html(labelStr);
                        // 底部
                        $('#J_DownPDF1').data({
                            rename: def.reportData.rename,
                            path: def.reportData.pdfPath && (def.reportData.pdfPrefix + def.reportData.pdfPath) || ''
                        });
                        $('.js-report-down-imgpack').show().data({
                            rename: def.reportData.rename,
                            path: def.reportData.sImgPackPath && (def.reportData.pdfPrefix + def.reportData.sImgPackPath) || ''
                        });
                        $('.js-report-collect').data({
                            iscollected: def.reportData.collect_status,
                            id: def.params.id,
                            t: def.params.t
                        });
                        if (def.reportData.collect_status == 1) {
                            $('.js-report-collect').addClass('is-collected').children('span').text('已收藏');
                        }
                        // 主体
                        if (def.reportData.bigImgPath) {
                            $('.js-pop-report .fiter-img>img,.js-pop-report .bannerpic>img').attr('src', (def.img_prefix + def.reportData.bigImgPath)).parent().show();
                        } else {
                            $('.js-pop-report .fiter-img,.js-pop-report .bannerpic').hide();
                        }
                        $('.js-pop-report .header-detail-infor>h1').text(def.reportData.title);
                        $('.js-pop-report .tit-info>span').eq(0).text(def.reportData.updateDate);
                        def.reportData.authorPenName && $('.js-pop-report .tit-info>span').eq(1).text('作者：' + def.reportData.authorPenName).show() || $('.js-pop-report .tit-info>span').eq(1).hide();
                        $('.js-pop-report .tit-info>span').eq(2).text('浏览量：' + def.reportData.viewNum + '次');
                        if (def.reportData.sLiveUrl) {
                            def.reportData.live_status == 1 && $('.js-pop-report .live-box').html('<span>视频直播解读</span><a href="' + def.reportData.sLiveUrl + '" target="_blank">立即预约<i></i></a>').show();
                            def.reportData.live_status == 2 && $('.js-pop-report .live-box').html('<span>视频直播解读</span><a href="' + def.reportData.sLiveUrl + '" target="_blank">回看解读<i></i></a>').show();
                            def.reportData.live_status == 3 && $('.js-pop-report .live-box').html('<span>视频直播解读</span><a href="' + def.reportData.sLiveUrl + '" target="_blank">立即收看<i></i></a>').show();
                        } else {
                            $('.js-pop-report .live-box').hide();
                        }
                        var subTopic = def.reportData.subTopic;
                        var subTopicStr = '';
                        var topicNavStr = '';
                        for (var i = 0; i < subTopic.length; i++) {
                            var subPage = subTopic[i].subPage.length && subTopic[i].subPage[0] || {};
                            subTopicStr += '<div class="indexdiv new-indexdiv clear padd-bott-parren" id="index_' + subTopic[i].iSubTopicId + '">' +
                                '<h3><span>' + subTopic[i].sTitle + '</span></h3>' +
                                '<div class="xq">' + (subTopic[i].sDesc && ('<p class="fl">' + subTopic[i].sDesc + '</p>') || '') + '</div>';
                            var grids = subPage.sContent && subPage.sContent.grids || [];
                            var layers = subPage.sContent && subPage.sContent.layers || [];
                            subTopicStr += '<div class="report-section js-report-section" data-pageid="' + (subPage && subPage.iPageId || '') + '">';
                            var gridStr = '';
                            var layerStr = '';
                            var main_img_src = subPage.sContent && subPage.sContent.photo && subPage.sContent.photo.url || '';
                            if (main_img_src.indexOf('fm.pop-fashion.com') != -1) {
                                main_img_src = def.img_imgf1 + main_img_src.split('fm.pop-fashion.com')[1];
                            }
                            subTopicStr += '<img class="big-pic-bg" src="' + main_img_src + '" alt="pic">';
                            gridStr += domFunc(grids, gridStr, 1);
                            layerStr += domFunc(layers, layerStr, 2);
                            gridStr += layerStr;
                            subTopicStr += gridStr;
                            subTopicStr += '</div></div>';
                            // 目录
                            topicNavStr += '<li class="' + (i === 0 && 'this-item') + '"><a data-id="index_' + subTopic[i].iSubTopicId + '" onclick="' + ("javascript:document.getElementById('index_" + subTopic[i].iSubTopicId + "').scrollIntoView()") + '" class="clear" title="' + subTopic[i].sTitle + '"><i></i><p>' + subTopic[i].sTitle + '</p></a></li>';
                        }
                        $('.js-pop-report>.layer-main').html(subTopicStr);
                        // 目录
                        $('.js-nav-right .js-see-nav1>ul,.js-nav-right-min .js-see-nav2>ul').html(topicNavStr);
                    } else if (res.code == 110) {
                        msg.msg({
                            txt: "报告已失效",
                            success: function () {
                                location.href = '/trendspattern/';
                            }
                        }, 2000);
                    }
                    loadingToggle(false);
                },
                error: function () {
                    loadingToggle(false);
                }
            })
        }

        function domFunc(arr, _html, type) {
            for (var i = 0; i < arr.length; i++) {
                var is_local = arr[i]["isLocal"],
                    grid_w = arr[i]["width"] * report_info.k1 || 0,
                    grid_h = arr[i]["height"] * report_info.k1 || 0,
                    grid_x = 0,
                    grid_y = 0;
                if (type == 1) {
                    grid_x = arr[i]["x"] * report_info.k1 || 0;
                    grid_y = arr[i]["y"] * report_info.k1 || 0;
                } else {
                    grid_x = (arr[i]["x"] + report_box.ow / 2) * report_info.k1 || 0;
                    grid_y = (arr[i]["y"] + report_box.oh / 2) * report_info.k1 || 0;
                }
                var photo = arr[i]["photo"] || {},
                    download_src = photo["download"] || "",
                    rename = photo["rename"] || "",
                    img_src = photo["big"] || "",
                    img_id = photo["id"] || "",
                    big_src = photo["big"] || "",
                    img_src = img_src ? (def.link_reg_bef.test(img_src) ? img_src : (def.img_prefix + img_src)) : '',
                    brand = photo["brand"] || "",
                    description = photo["description"] || "",
                    video_id = getVideoMsg(description)[0],
                    scale = photo["scale"] || 1, // 图片缩放比
                    rotation = photo["rotation"] || 0, // 图片旋转: [0]↑, [90]→, [-180]↓, [-90]←
                    // rotation 取90或-90时减数宽高互换
                    rotation90 = Math.abs(rotation) === 90,
                    rotationX = photo["rotationX"] || 0, // 图片沿X轴镜像: 由 <=> 甲
                    rotationY = photo["rotationY"] || 0, // 图片沿Y轴镜像: 部 <=> 陪
                    photo_w = photo['width'] || 0, // 图片缩放后的宽
                    photo_h = photo['height'] || 0, // 图片缩放后的高
                    img_w = rotation90 ? photo_h : photo_w, // 图片css的宽
                    img_h = rotation90 ? photo_w : photo_h || 0, // 图片css的高
                    img_x = photo["x"] || 0, // 缩放后的图片中心点到格子左上角的x
                    img_y = photo["y"] || 0, // 缩放后的图片中心点到格子左上角的y
                    img_href = photo["link"] || "", // 图片链接
                    // 计算图片到格子左上角的坐标
                    minus_x = rotation90 ? photo_h / 2 : photo_w / 2,
                    minus_y = rotation90 ? photo_w / 2 : photo_h / 2,
                    img_x = (img_x - minus_x) * report_info.k1 || 0,
                    img_y = (img_y - minus_y) * report_info.k1 || 0;

                // js替换
                if (img_src.indexOf('fm.pop-fashion.com') != -1) {
                    img_src = def.img_imgf1 + img_src.split('fm.pop-fashion.com')[1];
                }
                // 报告视频
                if (video_id) {
                    if (type == 1) {
                        _html += '<div class="report-video-box report-box" style="left:' + Math.round(grid_x) + 'px;top:' + Math.round(grid_y) + 'px;width:' + Math.round(grid_w) + 'px;height:' + Math.round(grid_h) + 'px" data-src="' + img_src + '" data-id="' + img_id + '" >';
                    } else {
                        _html += '<div class="report-video-box report-box up-box" data-index="' + (i + 1000) + '" style="z-index:' + (i + 1000) + ';left:' + Math.round(grid_x) + 'px;top:' + Math.round(grid_y) + 'px;width:' + Math.round(grid_w) + 'px;height:' + Math.round(grid_h) + 'px" data-src="' + img_src + '" data-id="' + img_id + '" >';
                    }
                    _html += '<div class="report-video"><button class="video-play js-video-play" data-videoId="' + video_id + '" title="播放"><i></i>视频</button></div>';
                    _html += '</div>';
                } else {
                    if (type == 1) {
                        _html += '<div class="report-box js-report-box js-show-bg-btn" style="left:' + Math.round(grid_x) + 'px;top:' + Math.round(grid_y) + 'px;width:' + Math.round(grid_w) + 'px;height:' + Math.round(grid_h) + 'px" data-src="' + img_src + '" data-id="' + img_id + '" >';
                    } else {
                        _html += '<div class="report-box up-box js-report-box js-show-bg-btn" data-index="' + (i + 1000) + '" style="z-index:' + (i + 1000) + ';left:' + Math.round(grid_x) + 'px;top:' + Math.round(grid_y) + 'px;width:' + Math.round(grid_w) + 'px;height:' + Math.round(grid_h) + 'px" data-src="' + img_src + '" data-id="' + img_id + '" >';
                    }
                    _html += '<div class="tool-div">';
                    if (img_src) {
                        _html += '<button class="js-show-bg-btn" data-downhref="' + download_src + '" data-src="' + img_src + '" data-id="' + img_id + '" data-rename="' + rename + '" title="查看大图"></button>';
                        if (is_local) {
                            _html += '<a class="download-btn js-download-btn5" href="' + encodeURIComponent(download_src) + '" title="下载" target="_blank"></a>';
                        } else {
                            // 非本地图
                            _html += '<a class="download-btn js-download-btn2" href="javascript:void(0);" data-downhref="' + download_src + '" data-rename="' + rename + '" data-id="' + img_id + '" data-src="' + img_src + '" title="下载"></a>';
                        }
                    }
                    if (img_href) {
                        _html += '<a class="detail-btn" href="' + img_href + '" title="详情" target="_blank"></a>';
                    }
                    _html += '</div></div>';
                }
                if (brand) {
                    // 校正底部越界
                    var _top = Math.round(grid_y) + Math.round(grid_h) - 20;
                    _top = _top > 0 ? _top : 0;
                    _html += '<div class="txt-div txt-detail-div js-txt-div" style="position:absolute;top: ' + _top + 'px;left: ' + Math.round(grid_x) + 'px;z-index: ' + (i + 2000) + ';">';
                    _html += '<a href="/trendspattern/?key=' + encodeURIComponent(encodeURIComponent(brand)) + '" target="_blank">' + brand + '</a>';
                    _html += '</div>';
                }
            }
            return _html;
        }

        function getVideoMsg(str) {
            var matchArr = str.match(/#YK#\s*(.+)\s*#YK#\s*(\S*)\s*/);
            if (!matchArr) { return [] }
            return [matchArr[1], matchArr[2]];
        }

        // 获取下载图片
        function getDownload(url, t, id, col) {
            $.ajax({
                url: url,
                type: "post",
                dataType: "json",
                success: function (data) {
                    if (data.code == 1) {
                        alert("图片已失效");
                        return false;
                    }
                    if (data.data.downloadType) {
                        setDownloadDom(data.data.downloadType, t, id, col);
                    } else {
                        down(url, t, id, col);
                        // 下载统计
                        // actionFunc(t, id, 'download', request_id, scene_type);
                        actionDown();
                    }
                }
            })
        };

        function setDownloadDom(downloadType, t, id, col) {
            download_list_length = 0;
            var _html = '';
            var data = JSON.parse(JSON.stringify(downloadType));
            var obj = data[0]["aDownInfo"] || {};
            var is_more = false;
            var eps = '';
            for (var key in obj) {
                var name = "." + key;
                var type = obj[key].type;
                var bp = obj[key].bp;
                var size = obj[key].size;
                if (name == '.eps') {
                    eps = '<span class="eps-txt"><span style="position: relative;top: 2px;">*</span> 支持ai、cdr软件工具</span>';
                } else {
                    eps = '';
                }
                _html += '<tr>';
                _html += '<td>' + name + eps + '</td>';
                _html += '<td>' + type + '</td>';
                _html += '<td>' + size + '</td>';
                _html += '<td><a class="js-download-file-btn" data-bp="' + bp + '" data-table="' + t + '" data-id="' + id + '" data-col="' + col + '" href="javascript:void(0);" title="下载">下载</a></td>';
                _html += '</tr>';
                download_list_length++;
            }
            $(".js-download-tbody").html(_html);
            $(".js-download-img-box").show();
            $(".js-bg-div").show();
        };

        // 下载
        function down(path, t, id, col) {
            if (!path) {
                return;
            }
            // var fileType = path.split('.');
            // fileType = fileType.pop();	//下载文件格式
            // var params={ table:t , id:id , iColumnId:col ,action:'DownCount',fileType:fileType};
            // $.ajax({
            //     type: "get",
            //     url: "/ajax/setcount/" + Math.random(),
            //     data: params,
            //     async: false
            // });
            window.location.href = '/download/downImg/?dl_link=' + encodeURIComponent(path) + '&' + Math.random();
        }

        // 收藏
        function collectFunc(obj) {
            if (def.is_collect) { return; }
            def.is_collect = true;
            var id = obj.data("id");
            var t = obj.data("t");
            actionFunc(t, id, 'collect', request_id, scene_type)
            if (obj.hasClass("is-collected")) {
                def.collect_data = {
                    id: id,
                    t: t,
                    handle: 'cancel'
                }
            } else {
                def.collect_data = {
                    id: id,
                    t: t,
                    handle: 'join'
                }
            }
            $.ajax({
                url: '/collect/setcollect/' + Math.random(),
                type: 'POST',
                data: def.collect_data,
                success: function (data) {
                    if (data.code == 0) {
                        // 取消成功
                        if (parseInt(data.data.code) === 10) {
                            obj.removeClass('is-collected').data('iscollected', 0).children('span').text('加入收藏');
                            // msg.msg({ 'txt': data.data.msg }, 2000);
                            tipToggle('已取消收藏');
                        }
                        // 加入成功
                        if (parseInt(data.data.code) === 20) {
                            obj.addClass('is-collected').data('iscollected', 1).children('span').text('已收藏');
                            // msg.msg({ 'txt': data.data.msg }, 2000);
                            tipToggle('已收藏');
                        }
                    }
                    def.is_collect = false;
                },
                error: function () {
                    def.is_collect = false;
                }
            })
        }

        // 报告视频
        function video_player(player_id, vid) {
            var params = {
                styleid: '0',
                client_id: '6304acd0252780d2',
                vid: vid,
                newPlayer: true,
                autoplay: true,
                show_related: false
            };
            return new YKU.Player(player_id, params);
        }

        // 用户行为统计
        var action_data = {
            t: "",
            id: "",
            userid: userId,
            site: 1,
            action_type: "",
            timestamp: ""
        };
        var iframe_src = $(".js-detail-frame", parent.document).find("iframe").attr("src")
        var win_search = general.fn.getLocationParameter(iframe_src);
        var request_id = win_search.request_id || "";
        var scene_type = win_search.scene_type || "";
        function actionFunc(tablename, id, action_type, request_id, scene_type, func) {
            if (userType == "TOURIST") {
                return false;
            }
            action_data.request_id = request_id;
            action_data.scene_type = scene_type;
            action_data.t = tablename;
            action_data.id = id;
            action_data.action_type = action_type;
            action_data.timestamp = new Date().getTime();
            $.ajax({
                type: "get",
                dataType: "jsonp",
                url: '//api.pop136.com/internal/datagrand.php?' + Math.random(),
                data: action_data,
                success: func
            })
        }

        // 浏览量
        // actionFunc(def.params.t,def.params.id,"view",request_id,scene_type);
        actionView();
        function actionView() {
            var action_obj = def.action;
            $.ajax({
                "url": "//api.pop136.com/internal/statistics.php?action=view&" + Math.random(),
                "data": action_obj,
                "type": 'get',
                'dataType': "jsonp",
                'jsonp': "callback"
            });
        }

        // 真实查看
        function actionRview() {
            def.is_review = true;
            var action_obj = def.action;
            $.ajax({
                "url": "//api.pop136.com/internal/statistics.php?action=rview&" + Math.random(),
                "data": action_obj,
                "type": 'get',
                'dataType': "jsonp",
                'jsonp': "callback"
            });
        }

        // 下载统计
        function actionDown(type) {
            if (def.action_down) {
                def.action_down = false;

                def.action.type = type || 'pdf';// pdf|zip 报告下载类型
                $.ajax({
                    "url": "//api.pop136.com/internal/statistics.php?action=down&" + Math.random(),
                    "data": def.action,
                    "type": 'get',
                    'dataType': "jsonp",
                    'jsonp': "callback"
                });

                clearTimeout(def.action_timer);
                def.action_timer = setTimeout(function () {
                    def.action_down = true;
                }, 3000);
            }
        }
    })
});