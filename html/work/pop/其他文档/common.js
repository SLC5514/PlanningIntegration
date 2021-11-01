// 全局方法
var pop_fashion_global = {
    fn: {

        /*-------------------window.location-------------------*/
        getLocationParameter: function () {              // 获取浏览器参数
            var url = location.search; //获取url中"?"符后的字串 
            var theRequest = {};
            if (url.indexOf("?") != -1) {
                var str = url.substr(1);
                strs = str.split("&");
                for (var i = 0; i < strs.length; i++) {
                    theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
                }
            }
            return theRequest;
        },
        textSize: function (cssList, text) {               // 通过元素获取文字宽高
            var a = pop_fashion_global.fn;
            var span = document.createElement("span");
            var result = {};
            result.width = span.offsetWidth;
            result.height = span.offsetWidth;
            span.style.visibility = "hidden";
            span.style.cssText = "font-size:14px;line-height:1em;display:inline;padding:0;margin:0;border:none;letter-spacing:0px";
            span.style.fontSize = cssList["fontsize"] !== undefined ? cssList["fontsize"] + "px" : "14px";
            span.style.lineHeight = cssList["lineheight"] !== undefined ? cssList["lineheight"] : "1em";
            document.body.appendChild(span);
            if (typeof span.textContent != "undefined") { span.textContent = text; } else { span.innerText = text; }
            result.width = span.offsetWidth - result.width;
            result.height = span.offsetHeight - result.height;
            span.parentNode.removeChild(span);
            return result.width;
        },
        cutByWidth: function (str, wid, fontSize) {               //通过宽度截取字符串
            var a = pop_fashion_global.fn, nstr = "";
            if (typeof str === "string" && wid > 0) {
                var nfs = fontSize !== undefined ? fontSize : 14;
                nstr = str, limit_val = wid, is_length = false;
                recursionFunc(nstr);
                function recursionFunc (keys) {
                    var nw = a.textSize({
                        "fontSize": nfs
                    }, keys);
                    if (nw > limit_val) {
                        is_length = true;
                        var nkey = keys.substr(0, keys.length - 1);
                        arguments.callee(nkey);
                    } else {
                        if (is_length === true) {
                            nstr = keys + "...";
                        } else {
                            nstr = keys;
                        }
                        return keys;
                    }
                }
            }
            return nstr;
        },
        /*----------------浏览器存储-----------------*/
        getSto: function (key_name) {                  //获取本地存储
            var a = pop_fashion_global.fn;
            if (window.localStorage) {
                // 支持localStorage
                var val = localStorage.getItem(key_name);
                if (val === "undefined") {
                    return "undefined";
                } else if (typeof val === "number") {
                    return val;
                } else if (val) {
                    return JSON.parse(val) ? JSON.parse(val) : "";
                }
            } else {
                // 用cookie
                return JSON.parse(a.getCookie(key_name)) ? JSON.parse(a.getCookie(key_name)) : "";
            }
        },
        setSto: function (key_name, data) {                 // 存储本地
            var a = pop_fashion_global.fn;
            if (window.localStorage) {
                localStorage.setItem(key_name, JSON.stringify(data));
            } else {
                a.setCookie(key_name, JSON.stringify(data), 10000);
            }
        },
        delSto: function (key_name) {                  // 删除本地存储
            var a = pop_fashion_global.fn;
            if (window.localStorage) {
                if (localStorage.getItem(key_name)) {
                    localStorage.removeItem(key_name);
                }
            } else {
                if (a.getCookie(key_name)) {
                    a.setCookie(key_name, "", -1);
                }
            }
        },
        setCookie: function (name, value, Days) {        // 设置cookie
            var exp = new Date();
            exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);    //设置过期时间
            document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString() + ";path=/";
        },
        getCookie: function (name) {  //获取cookie
            var arr = document.cookie.match(new RegExp("(^| )" + name + "=([^;]*)(;|$)"));
            if (arr != null) {
                return unescape(arr[2]);
            } else {
                return null;
            }
        },
        loginJWT: function () {         //获取登录cookie,
            return this.getCookie('POP_USER');
        },
        /*----------------------------性能优化-----------------------------*/
        throttle: function (method, context, arr, times) {          //函数节流
            clearTimeout(method.tid);
            method.tid = setTimeout(function () {
                method.apply(context, arr);
            }, times != undefined ? times : 50);
        },
        /*----------------------------事件相关-----------------------------*/
        stopBubble: function (ev) {            // 阻止事件冒泡
            var e = ev || window.event;
            if (e && e.stopPropagation) {
                e.stopPropagation();
            } else {
                window.event.cancelBubble = true;
            }
            return false;
        },
        subAjax: function (options) {                  //jquery  ajax封装
            var opt = {
                "url": "",
                "ctp": "",
                "data": {},
                "cache": false,
                "successFunc": null,
                "errorFunc": null,
                "isError": true,
                "header": null,
                "code": "code",          //状态字段名称  默认为code
                "code_value": 0,         //请求成功码  默认为0
                "message": "message"     //请求失败话术字段名  默认为message
            };
            opt["url"] = options["url"] ? options["url"] : "";
            opt["data"] = options["data"] ? options["data"] : {};
            opt["cache"] = options["cache"] != undefined ? options["cache"] : false;
            opt["ctp"] = options["ctp"] ? options["ctp"] : "application/json";
            opt["successFunc"] = options["successFunc"] ? options["successFunc"] : null;
            opt["errorFunc"] = options["errorFunc"] ? options["errorFunc"] : null;
            opt["isError"] = options["isError"] !== undefined ? options["isError"] : false;
            opt["code"] = options["code"] ? options["code"] : "code";
            opt["code_value"] = options["code_value"] !== undefined ? options["code_value"] : 0;
            opt["message"] = options["message"] !== undefined ? options["message"] : "message";
            if (typeof options["header"] != "undefined") {
                opt["header"] = options["header"];
            } else {
                /*这里设置默认头部
                opt["header"]={
                    
                };*/
            }
            $.ajax({
                headers: opt["header"],
                type: "POST",
                url: opt["url"],
                data: opt["data"],
                cache: opt["cache"],
                timeout: 12000,
                dataType: "json",
                contentType: opt["ctp"],
                success: function (data) {
                    if (data[opt["code"]] === opt["code_value"]) {
                        if (opt.successFunc && opt.successFunc instanceof Function) {
                            opt.successFunc(data);
                        }
                    } else {
                        if (opt.errorFunc && opt.errorFunc instanceof Function) {
                            opt.errorFunc(data);
                        }
                        if (opt["isError"] === true) {
                            oCommon.noPower('', data[opt["message"]]);
                        }
                    }
                },
                error: function (data) {
                    if (data.readyState != 4) {																	//解决火狐快速切换url网络错误问题
                        return;
                    }
                    if (opt["isError"] === true) {
                        if (opt.errorFunc && opt.errorFunc instanceof Function) {
                            oCommon.noPower('', "网络似乎出现了错误，请稍后重试。");
                        } else {
                            oCommon.noPower('', "网络似乎出现了错误，请稍后重试。");
                        }
                    } else {
                        if (opt.errorFunc && opt.errorFunc instanceof Function) {
                            opt.errorFunc(data);
                        }
                    }
                }
            });
        },
        subAjaxGet: function (options) {                  //jquery  ajax封装
            var opt = {
                "url": "",
                "ctp": "",
                "successFunc": null,
                "errorFunc": null,
                "isError": true,
                "header": null,
                "code": "code",          //状态字段名称  默认为code
                "code_value": 0,         //请求成功码  默认为0
                "message": "message"     //请求失败话术字段名  默认为message
            };
            opt["url"] = options["url"] ? options["url"] : "";
            opt["ctp"] = options["ctp"] ? options["ctp"] : "application/json";
            opt["successFunc"] = options["successFunc"] ? options["successFunc"] : null;
            opt["errorFunc"] = options["errorFunc"] ? options["errorFunc"] : null;
            opt["isError"] = options["isError"] !== undefined ? options["isError"] : false;
            opt["code"] = options["code"] ? options["code"] : "code";
            opt["code_value"] = options["code_value"] !== undefined ? options["code_value"] : 0;
            opt["message"] = options["message"] !== undefined ? options["message"] : "message";
            if (typeof options["header"] != "undefined") {
                opt["header"] = options["header"];
            } else {
                /*这里设置默认头部
                opt["header"]={
                    
                };*/
            }
            $.ajax({
                headers: opt["header"],
                type: "GET",
                url: opt["url"],
                timeout: 20000,
                dataType: "json",
                contentType: opt["ctp"],
                success: function (data) {
                    if (data[opt["code"]] === opt["code_value"]) {
                        if (opt.successFunc && opt.successFunc instanceof Function) {
                            opt.successFunc(data);
                        }
                    } else {
                        if (opt.errorFunc && opt.errorFunc instanceof Function) {
                            opt.errorFunc(data);
                        }
                        if (opt["isError"] === true) {
                            oCommon.noPower('', data[opt["message"]]);
                        }
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (opt["isError"] === true) {
                        if (opt.errorFunc && opt.errorFunc instanceof Function) {
                            oCommon.noPower('', "网络似乎出现了错误，请稍后重试。");
                        } else {
                            oCommon.noPower('', "网络似乎出现了错误，请稍后重试。");
                        }
                    } else {
                        if (opt.errorFunc && opt.errorFunc instanceof Function) {
                            opt.errorFunc();
                        }
                    }
                }
            });
        }
    }
};

var brandAll;

oCommon = {
    // 顶部所有站显示与隐藏
    'handleAllWeb': function () {
        var $left = $('.leftT li.webIco');
        var $conleft = $('.conleft');
        var $allweb = $('.allWeb');
        $left.on('mouseenter', function () {
            $allweb.css('display', 'block');
        });
        $left.on('mouseleave', function () {
            $allweb.css('display', 'none');
        });
    },
    // 导航栏行业筛选
    'industrySelect': function () {
        var $navsx = $(".js-switch-area");
        var $navlist = $('.navshaixuan-list');
        $navsx.on('mouseover mouseleave', function (e) {
            if (e.type == 'mouseover') {
                $navlist.css('display', 'block');
            } else if (e.type == 'mouseleave') {
                $navlist.css('display', 'none');
            }
        });
    },

    // 客户服务下拉
    'specialList': function () {
        var timer;
        var $sdown = $(".special_down");
        var $slist = $(".special_list");
        $sdown.on('mouseover', function () {
            clearTimeout(timer);
            $slist.css('display', 'block');
        });

        $sdown.on('mouseleave', function () {
            timer = setTimeout(function () {
                $slist.css('display', 'none');
            }, 200);
        });

    },

    // 窗口滚动时搜索框改变且出现回到顶端按钮
    'windowScroll': function () {
        var obj = this;
        $(window).scroll(function () {
            var scrollTop = $(this).scrollTop();
            var $searchLi = $('.searchLi');
            var $searchIn = $('.searchIn');
            var $backTop = $('#backTop');
            if (scrollTop > 40) {
                // $searchLi.off('mouseenter');
                // $searchIn.off('mouseleave');
                // $searchLi.hide();
                // $searchIn.fadeIn(200);
                $backTop.fadeIn(100);
            } else {
                // $searchIn.stop(true,true).fadeOut(200);
                // $searchLi.show();
                $('#backTop').fadeOut(100);
                // obj.headSearch();
            }
        });
    },
    /*底部广告*/
    'bottomAds': function () {
        var par = $("#footwrap");
        var $self = $('#footwrap .closebtn');
        var is_close = false;
        $self.on('click', function () {
            par.hide();
            is_close = true;
        });
        function adsScroll () {
            if (is_close == true) { return; }
            var scrollTop = $(window).scrollTop();
            if (scrollTop == 0) {
                par.hide();
            } else if (scrollTop > 0) {
                par.show();
            }
        };
        adsScroll();
        $(window).scroll(function () {
            adsScroll()
        });
    },
    /*回到顶部*/
    'backTop': function () {
        $('#backTop').on('click', function () {
            $("html,body").animate({ scrollTop: 0 }, 1000);
        });
    },
    /*右侧小导航*/
    'rightNav': function () {
        var lis = $(".nav_fixed li");
        lis.on('mouseenter mouseleave', function (e) {
            if (e.type == 'mouseenter') {
                $(this).find("i").stop(true, true).show(200).end().find(".show_left").stop(true, true).show(200);
            } else {
                $(this).find("i").stop(true, true).hide(100).end().find(".show_left").stop(true, true).hide(200);
            }
        });
        // 趋势APP及微信小助手
        $('.js_side_tab').on('click', '.item', function() {
          var idx = $(this).index();
          $(this).addClass('on').siblings().removeClass('on');
          $('.js_side_tabcon>.item').eq(idx).addClass('on').siblings().removeClass('on');
        })
        // pop地图
        var $map = $(".js-pop-map");
        $map.on("mouseenter mouseleave", function (e) {
            var $iframe = $(this).find("iframe");
            if ($iframe.length < 1) {
                $iframe = $('<iframe src="" width="370" height="300" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>');
                $(this).find('.js-map-img>a').before($iframe);
            }
            if (e.type === 'mouseenter') {
                $iframe.attr('src', '/service/address/');
                $(this).find('.js-map-show').stop(true, true).show(200);
                $(this).find('.js-arrow-map').show();
            } else {
                $(this).find('.js-map-show').stop(true, true).hide(100);
                $(this).find('.js-arrow-map').hide();
            }
        });

        // pop地图定位
        if ($map.length) {
            var mapTop = $map[0].getBoundingClientRect().top;
            var windowHei = $(window).height();
            var boxHei = parseInt(windowHei - mapTop);
            if (boxHei < 370) {
                $(".js-map-show").addClass("ab-fixed").removeClass("ab-absolute");
            } else {
                $(".js-map-show").addClass("ab-absolute").removeClass("ab-fixed");
            }
        }
    },
    'noPower': function (no_type, content) {
        var no_type = parseInt(no_type);
        var L = '';
        if (!content) {
            switch (no_type) {
                case -1: L = '对不起，只有VIP用户才能使用此功能！'; break;
                case -2: L = '对不起，只有设计师专属账号才能使用此功能，<br/>请您<a href="/member/associate/" target="_blank" title="添加">添加</a>或登录设计师专属账号！'; break;
                case -3: L = '对不起，只有VIP用户才能访问该栏目！'; break;
                case -4: L = '对不起，操作失败，请重试！'; break;
                case -5: L = '对不起，只有VIP用户才能使用此功能，<br/>请<a target="_blank" href="/service/joinmember/" style="color: #d8b056;" title="立即升级为本站VIP会员">立即升级为本站VIP会员</a>获取最新流行资讯！'; break;
                case -6: L = '对不起，您正在浏览的是会员内容...<br/>由于您尚未<a target="_blank" href="/member/register/" rel="nofollow" title="注册" style="color: #d8b056;">注册</a>或<a href="javascript:void(0);" class="loginLayer" title="登录" style="color: #d8b056;">登录</a>，暂无权限查看详情，如需帮助请联系我们。'; break;
                case -7: L = '对不起，您正在浏览的是会员内容...<br/>由于您尚未<a target="_blank" href="/service/joinmember/" rel="nofollow" title="成为VIP" style="color: #d8b056;">成为VIP</a>会员，暂无权限查看详情，如需帮助请联系我们。'; break;
                case 1: L = '操作成功！'; break;
            }
        }
        else {
            L = content;
        }
        var shtml = '<div class="qx_close"></div><p>' + L + '</p>';
        //页面层-自定义
        var oNoPowerLayer = layer.open({
            type: 1,
            title: false,
            // scrollbar:false,
            closeBtn: 0,
            skin: 'quanxian_cont',
            area: ['550px', '115px'],
            maxWidth: '660px',
            content: shtml
        });
        $('.quanxian_cont .qx_close').click(function () {
            layer.close(oNoPowerLayer);
        });
    },
    // 信息回馈
    'feedback': function () {
        var $box = $('#txtArea');
        var txtVal = $box.text();
        $box.inputText({
            txt: txtVal,
            lightColor: "#ccc",
            color: "#ccc",
            fontSize: "14px"
        });
        $('#feedback').on('click', function () {
            var feedBackVal = $('#txtArea').val();
            if (feedBackVal == '' || feedBackVal == '请输入您的反馈信息......') {
                oCommon.noPower('', '请输入您的反馈信息!!');
                return false;
            }
            else {
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    data: { feedBackVal: feedBackVal },
                    url: '/ajax/userfeedback/',
                    success: function (data) {
                        oCommon.noPower('', data.msg);
                        $('#txtArea').val('请输入您的反馈信息......');
                    }
                });
            };
        });
    },

    'collect': function (_this, iColumnId, sTableName, iPriId, iType, callback, status, para) {
        var that = this;
        if (status == 0) {
            var text = '您是否确认取消收藏？';
            var url = '/collect/setcollect/' + iColumnId + '-' + sTableName + '-' + iPriId + '-' + iType + '-' + para + '/cancel/?' + Math.random();
        } else {
            var text = '您是否确认收藏？';
            var url = '/collect/setcollect/' + iColumnId + '-' + sTableName + '-' + iPriId + '-' + iType + '-' + para + '/?' + Math.random();
        }
        //确认框
        $.ajax({
            type: 'get',
            url: url,
            anyc: true,
            success: function (e) {
                e = parseInt(e);
                if (e < 0) {
                    that.noPower(e); return;
                } else {
                    if (typeof callback == 'function') {
                        callback();
                    } else {
                        window.location.href = location.href; return;
                    }
                }
            }
        });
    },
    'loginLayer': function (status) {
        if (/AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))) {
            try {
                if (/Android|webOS|iPhone|iPod|BlackBerry/i.test(navigator.userAgent)) {
                    window.location.href = "/member/pagelogin/";
                    return;
                } else {

                }
            } catch (e) { }
        }
        //单点登录
        var url = '//member.pop136.com/login/ssoUserInfo';
        var obj = {}
        $.ajaxFun(url, obj, function (data) {
            if (data.code == '0') {
                var html = '';
                var list = data.data;
                for (var i = 0; i < list.length; i++) {
                    html += '<li data-userId="' + list[i].userId + '"><label><img src="' + list[i].avatar + '" alt=""></label >'
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
                    $('.js-new-trends-fix').hide();
                    $('.js-bg-div').show();
                })
                $('.js-sso-user-list li').on('click', function () {
                    var url1 = '//member.pop136.com/login/sso';
                    var obj1 = {
                        'userId': $(this).attr('data-userId'),
                        'website': '1'
                    }
                    $.ajaxFun(url1, obj1, function (data) {
                        if (data.code == '0') {
                            var POP_USER = data.data.jwt;
                            $.cookie('NO_REFRESH_JWT', '1', { path: '/', domain: 'pop-fashion.com', secure: false, raw: false });
                            $.cookie('POP_USER', POP_USER, { expires: data.data.expire, path: '/', domain: 'pop-fashion.com', secure: false, raw: false });
                            //跳转猜你喜欢
                            if(status){
                                location.href = '/patterns/patternRecommend/';
                                return;
                            }
                            // IP限制页面跳转到首页
                            var ipInfo = $('#ipInfo').val();
                            if (ipInfo=='true') {
                                location.href = '//www.pop-fashion.com/';
                            } else {
                                location.reload();
                            }
                        } else {
                            if (data.msg != '' && data.msg != undefined) {
                                $('.sso-error').text(data.msg); 
                            }
                            $('.sso-error').show();
                            $('.sso-block').hide();
                            setTimeout(function () {
                                $('.js-sso-box').hide();
                                $('.sso-error').hide();
                                $('.sso-block').show();
                            }, 2000);
                        }
                    })
                })
            } else {
                $('.pop_loginbg').show();
                $('.js-new-trends-fix').hide();
                $('.js-bg-div').show();
            }
        })
    },
    'download': function (path) {
        window.location.href = '/download/dlsingle/?dl_link=' + encodeURIComponent(path) + '&' + Math.random();
    },
    //提示信息闪烁
    'flicker': function () {
        var o = $(".wran"), i = 0, c = "tour_cus", times = 6;
        var t = setInterval(function () {
            i++;
            if (i % 2) {
                o.addClass(c);
            } else {
                o.removeClass(c);
            }
            if (i == times) {
                clearInterval(t);
            }
        }, 300);
    },
    'clickBeforeFlicker': function (obj) {
        return true;
        // 游客或者普通用户
        var power = parseInt($('#link').data('pow'));
        if (power == 1 || power == 2) {
            if (typeof obj != 'undefined') {
                $(obj).parents('.showbox,.showdiv').css({ visibility: 'hidden' });
            }
            this.flicker();
            return false;
        }
        return true;
    },
    // 行业性别筛选
    'giClick': function () {
        var obj = this;
        // 性别行业点击处理
        $('a[id^=gi_]').on('click', function () {
            var $box = $(this);
            var info = $box.prop('id').replace(/gi_/gi, '');
            var infoa = info.split('_');
            var type = parseInt(infoa[0]);
            var val = parseInt(infoa[1]);
            obj.replaceGI(val, type);
        });
    },
    'replaceGI': function (val, type) {
        var name, pattern, pattern1, pattern2, alias, gcen;
        var rtrimSep = /\/+$/;
        var _anchor = '#anchor';
        switch (type) {
            case 142:
                name = 'gender';
                alias = 'gen_';
                gcen = /\-?gcen_\d+/gi;
                pattern = /gen_\d+/gi;
                pattern1 = /\/\-?gen_\d+\-?/gi;
                pattern2 = /\-?gen_\d+/gi;
                break;
            case 158:
                name = 'industry';
                alias = 'ind_';
                gcen = '';
                pattern = /ind_\d+/gi;
                pattern1 = /\/\-?ind_\d+\-?/gi;
                pattern2 = /\-?ind_\d+/gi;
                break;
        }
        if (val) {
            $.cookie(name, val, { domain: '.pop-fashion.com', path: '/' });
        }
        // 删除
        else {
            $.cookie(name, "", { domain: '.pop-fashion.com', path: '/', expires: -1 });	// 清除COOKIE
        }
        var url = window.location.href;
        url = url.replace(_anchor, '').replace(/[?&]m=[\.\d]+/gi, '');
        // 页码如果存在则将页码去掉
        if (url.indexOf('-page_') !== -1) {
            var reg = RegExp('-page_([^-]*)', 'gi');
            url = url.replace(reg, '');
        }
        else if (url.indexOf('page_') !== -1) {
            var reg = RegExp('page_([^-]*)', 'gi');
            url = url.replace(reg, '');
        } else if (url.indexOf('page=') !== -1) {
            var reg = RegExp('[?&]page=([^-]*)', 'gi');
            url = url.replace(reg, '');
        }
        // 为了照顾款式在选择童装时可以筛选男童或女童而做的特殊处理
        // 有则改之
        if (pattern.test(url)) {
            // 全部时清除性别或行业
            if (val == 0) {
                if (/\?key=/gi.test(url)) {
                    var key = url.replace(/(.*)(\/\?key=.*)/gi, '$1|||$2');
                    var info = key.split('|||');
                    var _url = info[0];
                    var _key = info[1];
                    if (/\_/.test(_url)) {
                        _url = _url.replace(pattern1, '/').replace(pattern2, '');
                    }
                    _url = _url.replace(gcen, '').replace(rtrimSep, '');
                    url = _url + _key;
                } else {
                    url = url.replace(pattern1, '/').replace(pattern2, '').replace(gcen, '').replace(rtrimSep, '') + '/';
                }
            } else {
                if (/\?key=/gi.test(url)) {
                    url = url.replace(pattern, alias + val).replace(gcen, '');
                } else {
                    url = url.replace(pattern, alias + val).replace(gcen, '').replace(rtrimSep, '') + '/';
                }
            }
        }
        // 无则加勉
        else {
            if (val) {
                // 带关键字
                if (/\?key=/gi.test(url)) {
                    var key = url.replace(/(.*)(\/\?key=.*)/gi, '$1|||$2');
                    var info = key.split('|||');
                    var _url = info[0].replace(gcen, '').replace(rtrimSep, '');
                    var _key = info[1];
                    if (/\_/.test(_url)) {
                        _url = _url.replace(rtrimSep, '') + '-' + alias + val;
                    } else {
                        _url += '/' + alias + val;
                    }
                    url = _url + _key;
                } else {
                    url = url.replace(gcen, '').replace(rtrimSep, '') + '/';
                    if (/\_/.test(url)) {
                        url = url.replace(rtrimSep, '') + '-' + alias + val;
                    } else {
                        url += alias + val;
                    }
                    url = url.replace(rtrimSep, '') + '/';
                }
            } else {
                // 带关键字
                if (/\?key=/gi.test(url)) {
                    // 不带随机数
                    if (!/m=/gi.test(url)) {
                        url += '&m=' + Math.random();
                    }
                    else {
                        url = url.replace(/&m=[\.\d]+/gi, '&m=' + Math.random());
                    }
                } else {
                    // 带随机数
                    if (!/m=/gi.test(url)) {
                        url += '?m=' + Math.random();
                    }
                    else {
                        url = url.replace(/\?m=[\.\d]+/gi, '?m=' + Math.random());
                    }
                }
            }
        }
        window.location.href = url + _anchor;
    },
    'delGIClick': function () {
        //点击x,删除本身的条件
        $(".del_self").on('click', function () {
            var _url = $(this).data('url');
            if (_url == '#') {
                var url = window.location.href;	// 改URL
                // 为了照顾款式在选择童装时可以筛选男童或女童而做的特殊处理
                // 有则删之
                if (/gen_\d+/.test(url)) {
                    url = url.replace(/\-?gen_\d+/, '').replace(/\/+$/, '') + '/';
                }
                $.cookie('gender', "", { domain: '.pop-fashion.com', path: '/', expires: -1 });	// 清除COOKIE
                window.location.href = url;
            }
            // 行业
            else if (_url == '##') {
                var url = window.location.href;	// 改URL
                // 为了照顾款式在选择童装时可以筛选男童或女童而做的特殊处理
                // 有则删之
                if (/ind_\d+/.test(url)) {
                    url = url.replace(/\-?ind_\d+/, '').replace(/\/+$/, '') + '/';
                }
                $.cookie('industry', "", { domain: '.pop-fashion.com', path: '/', expires: -1 });
                window.location.href = url;
            } else {
                window.location.href = _url;
            }
        });
        //点击性别或行业条件,删除本身的cookie值
        $(".del").on('click', function () {
            var val = $(this).attr('href');
            // 点击不做处理
            if (val == '#' || val == '##') {
                return false;
            }
            // 性别
            if (val == '#') {
                var url = window.location.href;	// 改URL
                // 为了照顾款式在选择童装时可以筛选男童或女童而做的特殊处理
                // 有则删之
                if (/gen_\d+/.test(url)) {
                    url = url.replace(/\-?gen_\d+/, '').replace(/\/+$/, '') + '/';
                }
                $.cookie('gender', "", { domain: '.pop-fashion.com', path: '/', expires: -1 });
                window.location.href = url;
            }
            // 行业
            else if (val == '##') {
                var url = window.location.href;	// 改URL
                // 为了照顾款式在选择童装时可以筛选男童或女童而做的特殊处理
                // 有则删之
                if (/ind_\d+/.test(url)) {
                    url = url.replace(/\-?ind_\d+/, '').replace(/\/+$/, '') + '/';
                }
                $.cookie('industry', "", { domain: '.pop-fashion.com', path: '/', expires: -1 });
                window.location.href = url;
            }
        });
    },
    // 隐藏微信分享
    hiddenWXshare: function () {
        $('#bdshare_weixin_qrcode_dialog_bg,#bdshare_weixin_qrcode_dialog').remove();
    },
    // 共 757743 个相关款式=>共 75... 个相关款式
    // ellipsis:function(){
    // 	var $result = $('#s_result');
    // 	var w920 = parseInt($('.w920').width());
    // 	if (w920 == 1220) {
    // 		$result.find('.findstyle a').css('max-width','auto');
    // 		$result.find('.btn_page span.totalN').css({maxWidth: 'auto'});
    // 	} else if(w920 == 920){
    // 		$result.find('.findstyle a').css('max-width','40px');
    // 		$result.find('.btn_page span.totalN').css({maxWidth:'28px'});
    // 	}
    // },

    // 大图缩放拖拽
    SetImg: function (obj, maxW, maxH) {
        //初始化大图图片
        var temp_img = new Image();
        temp_img.onload = function () {
            var imgH = temp_img.height;
            var imgW = temp_img.width;
            //计算图片最大宽度
            if ((imgW > maxW) && (imgW > imgH)) {
                obj.width = maxW;
                obj.height = imgH * (maxW / imgW);
                imgW = obj.width;
                imgH = obj.height;
                if (imgH > maxH) {
                    obj.height = maxH;
                    obj.width = imgW * (maxH / imgH);
                }
            }
            //计算图片最大高度
            if ((imgH > maxH) && (imgH > imgW)) {
                obj.height = maxH;
                obj.width = imgW * (maxH / imgH);
                imgW = obj.width;
                imgH = obj.height;
                if (imgW > maxW) {
                    obj.width = maxW;
                    obj.height = imgH * (maxW / imgW);
                }
            }
            if ((imgW > maxW) && (imgW == imgH)) {
                obj.width = maxW;
                obj.height = imgH * (maxW / imgW);
                imgW = obj.width;
                imgH = obj.height;
                if (imgH > maxH) {
                    obj.height = maxH;
                    obj.width = imgW * (maxH / imgH);
                }
            }
            if ((imgW < maxW || imgW == maxW) && (imgH < maxH || imgH == maxH)) {
                obj.width = imgW;
                obj.height = imgH;
            }
            obj.width = imgW;
            obj.height = imgH;
        };
        temp_img.src = obj.src;
    },    
    // 全站搜索时，将性别或行业追加到URL里面
    // 返回值 gen_x||ind_x||空  x=数字
    getGenIndInfo: function () {
        var gen, ind;
        var url = location.href;
        var genPattern = /.*gen_(\d+)?.*/;
        var indPattern = /.*ind_(\d+)?.*/;
        // URL优先
        // 有性别
        if (genPattern.test(url)) {
            gen = parseInt(url.replace(genPattern, '$1'));
        } else {
            gen = parseInt($.cookie('gender'));
        }
        if (indPattern.test(url)) {
            ind = parseInt(url.replace(indPattern, '$1'));
        } else {
            ind = parseInt($.cookie('industry'));
        }
        // cookie
        if (gen && ind) {
            return 'gen_' + gen + '-' + 'ind_' + ind + '/';
        } else if (gen) {
            return 'gen_' + gen + '/';
        } else if (ind) {
            return 'ind_' + ind + '/';
        } else {
            return '';
        }
    },
    getDelGenIndInfo: function () {
        var url = location.href;
        var genPattern = /-?gen_\d+-?/;
        var indPattern = /-?ind_\d+-?/;
        if (genPattern.test(url)) {
            url = url.replace(genPattern, '');
        }
        if (indPattern.test(url)) {
            url = url.replace(indPattern, '');
        }
        url = url.replace(/\/\/\?/, '/?');	// 有隐患
        return url;
    },
    //游客/普通用户/试用会员 各类下载功能屏蔽
    downloadPrivilege: function () {
        if ($.inArray(P_UserType.toString(), ['3', '4', '5']) > -1) {
            oCommon.noPower(-5);//提示文案
            return false;
        }
        // console.log(P_UserType);

        return true;
    },
    //特殊字符替换
    popReplace: function (str) {
        str = str.replace(/</g, 'pop389').replace(/>/g, 'pop390').replace(/-/g, 'pop380').replace(/_/g, 'pop381').replace(/~/g, 'pop382').replace(/!/g, 'pop383').replace(/\./g, 'pop384').replace(/\*/g, 'pop385').replace(/\(/g, 'pop386').replace(/\)/g, 'pop387').replace(/&/g, 'pop388').replace(/\'/g, 'pop391').replace(/\+/g, 'pop392').replace(/\#/g, 'pop35');
        return str;
    },

    // 显示自定义提示层
    showTips: function (content, time) {
        time = time || 2000;
        layer.open({
            type: 0,
            area: ['240px'],
            title: false,
            closeBtn: false,
            btn: [],
            shade: 0.4,
            time: time,
            shade: 0,
            skin: 'demo-class',
            content: '<div style="text-align:center;margin-top:12px;">' + content + '</div>',
        });
    },

    // 运行
    run: function () {
        var obj = this;
        obj.handleAllWeb();
        obj.industrySelect();
        // obj.headNav();
        obj.specialList();
        obj.windowScroll();
        obj.bottomAds();
        obj.backTop();
        obj.rightNav();
        obj.feedback();
        obj.giClick();
        obj.delGIClick();
        obj.clickBeforeFlicker();
    }
};
$(function () {

    // 初始化 brandAll
    brandAllInit();
    function brandAllInit () {
        var thisBrandAllTime = pop_fashion_global.fn.getSto('brandAllTime');
        if ($("#brandAllInfo").data('time') == thisBrandAllTime) {
            brandAll = pop_fashion_global.fn.getSto('brandAll') || undefined;
        }
    }


    oCommon.run();
    $(window).resize(function () {
        oCommon.rightNav();
    });

    // 信息列表
    $(".information_li a").on('click', function (e) {
        $(".scrollHolder").perfectScrollbar('destroy');
        $(".scrollHolder").perfectScrollbar();
        $(".infor_box, .in_aro").css('visibility', 'visible');
        e.stopPropagation();
    });
    $("body").on('click', function (e) {
        $(".infor_box, .in_aro").css('visibility', 'hidden');
    });

    if (typeof ($().perfectScrollbar) == 'function') {
        $(".scrollHolder").perfectScrollbar();
    }



    // 首页合作伙伴轮播
    var $left = $(".switch_left");
    var $right = $(".switch_right");
    var $lis = $(".index_partner .p_ul1 li")
    var liswid = $lis.width();
    var $lilen = $lis.length;
    var $ul = $(".index_partner ul.p_ul1");
    var num = 0;
    $lis.eq(0).css('left', '0');
    $("body").on("mouseenter mouseleave", '.index_partner', function (e) {
        if (e.type == 'mouseenter') {
            $left.stop(true, true).animate({ 'left': '10px' }, 300);
            $right.stop(true, true).animate({ 'right': '10px' }, 300);
        } else {
            $left.stop(true, true).animate({ 'left': '-13px' }, 200);
            $right.stop(true, true).animate({ 'right': '-13px' }, 200);
        }
    });
    $right.on('click', function () {
        num++;
        if (num < $lilen) {
            $lis.eq(num - 1).stop(true, true).css({ 'z-index': '3' }).animate({ 'left': -liswid }, 300);
            $lis.eq(num).stop(true, true).css({ 'left': liswid, 'z-index': '4' }).animate({ 'left': 0 }, 300);
        } else if (num = $lilen - 1) {
            $lis.eq($lilen - 1).stop(true, true).css({ 'z-index': '3' }).animate({ 'left': -liswid }, 300);
            $lis.eq(0).stop(true, true).css({ 'left': liswid, 'z-index': '4' }).animate({ 'left': 0 }, 300);
            num = 0;
        }
    });
    $left.on('click', function () {
        num--;
        var now = -$lilen;
        if (num < $lilen) {
            $lis.eq(num + 1).stop(true, true).css({ 'z-index': '3' }).animate({ 'left': liswid }, 300);
            $lis.eq(num).stop(true, true).css({ 'left': -liswid, 'z-index': '4' }).animate({ 'left': 0 }, 300);
        }
        if (num <= now) {
            $lis.eq(now).stop(true, true).css({ 'left': -liswid, 'z-index': '4' }).animate({ 'left': 0 }, 300);
            num = 0;
        }
    });
});
//控制专题页底部弹层是否显示
$(function () {
    var ft_fixed = $(".footerFixed");
    var btn_close = $(".toClose");
    var docHeight = $(document).height();
    var winHeight = $(window).height();
    var flag = -1;
    $(window).scroll(function () {
        var scollTop = $(window).scrollTop();
        if (flag != 1) {
            if (scollTop >= docHeight - winHeight) {
                ft_fixed.hide();
            } else {
                ft_fixed.show();
            }
        }
    });

    btn_close.click(function () {
        flag = 1;
        ft_fixed.hide();
    });

});

//输入页码--跳转
$(function () {

    var goto = function (event) {
        var is_click = false;
        if (event.data) {
            is_click = (event.data.e == 'click');
        }
        if (event.which == 13 || is_click) {
            //获取需要跳转到的页码
            var page = $('#J_GoPage').val();
            page = parseInt(page);
            //获取总页数pageCount
            var pageCount = parseInt($("#_pageCount").val());
            if (page >= pageCount) {
                page = pageCount;
            }
            //获取需要跳转到的URL
            var url = $('#goBtn').attr('data');
            var search = $('#goBtn').attr('search');
            if (isNaN(page)) {
                return false;
            }
            window.location.href = url + 'page_' + page + '/' + (search ? search : '');
        }
    };

    $("body").on("click", "#goBtn", { 'e': 'click' }, goto);

    $('body').on('keypress', '#J_GoPage', goto)

    $("body").on("focus", "#J_GoPage", function () {
        $(this).val('');
    });
    $("body").on("blur", "#J_GoPage", function () {
        var $self = $(this);
        if (!$.trim($self.val())) {
            $self.val(this.defaultValue);
        }
    });


    // 登录弹窗
    $('body').on('click', '.loginLayer', function () {
        oCommon.loginLayer();
    });

    //检测是否同意了电子证书
    var show_electronicpage = function () {
        var viptype = $.cookie('viptype');
        var status = $.cookie('_MemberName');
        var isElectronicContract = $.cookie('_ELECTRONIC_CONTRACT_NEW');
        //if (status == null || parseInt(viptype) != 3 || isElectronicContract == null) {
        if (status == null || isElectronicContract == null) {
            return false;
        }
        else {
            //页面中打开电子证书 
            var elec_index = parent.layer.open({
                type: 2,
                title: '',
                shade: [0.8, '#FFFFFF'],
                shadeClose: false,
                closeBtn: 0,
                area: ['900px', '560px'],
                content: ['/member/econtract/', 'no']
            });
        }
    }

    show_electronicpage();

    //30天内免登陆 
    var Autologon = function () {
        var encryption = '';
        if ($.cookie('_KeepPassword') != null && $.cookie('userinfo_id') == null) {
            encryption = $.trim($.cookie('_KeepPassword'));
            $.ajax({
                type: 'post',
                url: '/member/doLogin/?' + Math.random(),
                async: false,
                data: { isajax: false, 'encryption': encryption },
                dataType: 'json',
                success: function (e) {
                    if (e.status == 1) {
                        window.location.href = location.href;
                        return true;
                    }
                    else {
                        return false;
                    }
                }
            });
        }
        else {
            return false;
        }
    };
    Autologon();


    // 尾部

    $(window).resize(function () {
        scrollLeft($('#footwrap'));
        scrollLeft($('.js-head-top'));
        scrollLeft($('.js-dropdown-nav'));
    });
    $(window).scroll(function () {
        scrollLeft($('#footwrap'));
        scrollLeft($('.js-head-top'));
        scrollLeft($('.js-dropdown-nav'));
    });
    function scrollLeft ($footwrap) {
        var scrollLeft = $(window).scrollLeft();
        $footwrap.css({ left: -$(window).scrollLeft() + 'px' });
        if (scrollLeft) {
            $footwrap.css({ width: 'auto' });
        } else {
            $footwrap.css({ width: '100%' });
        }
    }
    
    // 热门推荐数据
    var hotRec = [];
    // 搜索记录
    function searchHis(val) {
        $.ajax({
            url: '/suggestion/story/',
            method: 'post',
            data: {
                keyword: val
            }
        })
    }
    // 清除历史记录
    $('.js-clear-operat').click(function(e) {
        e.stopPropagation();
        $('.js-Itext').focus();
        initHistoryHot('', 'clear');
        return false;
    })
    // 初始化热门
    function initHistoryHot(val, type) {
        // 历史记录
        var history = pop_fashion_global.fn.getSto('search-history') || [];
        if (type == 'del') {
            var idx = history.indexOf(String(val));
            if (idx != -1) {
                history.splice(idx, 1);
                pop_fashion_global.fn.setSto('search-history', history);
            }
        } else if (type == 'clear') {
            history = [];
            pop_fashion_global.fn.setSto('search-history', history);
        } else if (val) {
            var idx = history.indexOf(String(val));
            if (idx != -1) {
                history.splice(idx, 1);
            }
            history.unshift(String(val));
            if (history.length > 5) {
                history.pop();
            }
            pop_fashion_global.fn.setSto('search-history', history);
        }
        var historyStr = '';
        history.forEach(function(v) {
            historyStr += '<li class="ui-menu-item" data-label="' + v + '">' + v + '<div class="fr"><i class="his-clear"></i></div></li>';
        })
        $('.search_listDown').find('.history-list').html(historyStr);
        if (!historyStr) {
            $('.js-history-label').hide();
        } else {
            $('.js-history-label').show();
        }
        // 热门推荐
        var hotStr = '';
        var count = 10 - history.length;
        for (var i = 0; i < count; i++) {
            hotStr += '<li class="ui-menu-item" data-label="' + (hotRec[i] && hotRec[i].keyword || '暂无数据') + '"><i class="serial"></i>' + (hotRec[i] && hotRec[i].keyword || '暂无数据') + '</li>';
        }
        $('.search_listDown').find('.hot-list').html(hotStr);
        // 初始化事件
        initHisHotFn();
    }
    // 搜索优化事件初始
    function initHisHotFn () {
        $('.js-Itext').next('.search_listDown').find('.menu-hot-list li').hover(function() {
            var listDown = $(this).parents('.search_listDown');
            var historyLen = listDown.find('.menu-hot-list .history-list li').length;
            var selfCount = $(this).index();
            var isHistory = $(this).parents('.history-list').length;
            var isHot = $(this).parents('.hot-list').length;
            var count = isHistory ? selfCount : (isHot ? historyLen + selfCount : -1);
            if (count != -1) {
                listDown.find('.menu-hot-list li').removeClass('ui-state-focus').eq(count).addClass('ui-state-focus');
            } else {
                $(this).addClass('ui-state-focus');
            }
        }, function() {
            $(this).parents('.search_listDown').find('.menu-hot-list li').removeClass('ui-state-focus');
        }).on('click', function(e) {
            e.stopPropagation();
            $('.js-Itext').val($(this).data().label);
            searchHis($(this).data().label);
            initHistoryHot($(this).data().label);
            $(".js-search-box").animate({ 'width': '127px' }, 500);
            $(".js-Itext").animate({ 'width': '110px' }, 500);
            $('.search_listDown').find('.menu-hot-list').hide().find('li').removeClass('ui-state-focus');
            $('.tel-icon').show();
            $('.js-Itext').blur();
            window.open("/search/?key=" + encodeURIComponent(encodeURIComponent($(this).data().label)));
            return false;
        }).find('.his-clear').on('click', function(e) {
            e.stopPropagation();
            $('.js-Itext').focus();
            initHistoryHot($(this).parents('.ui-menu-item').data().label, 'del');
            return false;
        })
    }
    // 搜索品牌初始
    function scrolldoc () {
        $('.js-Itext').each(function (i) {
            var _class;
            var $self = $(this);
            // head_topx42	滚动
            if (i) {
                _class = '.menu:last';
            }
            // head_topx40	刚进去
            else {
                _class = '.menu:first';
            }
            var $box = $self;
            $box.autocomplete({
                source: getBrandsData,
                appendTo: _class,
                minLength: 0,
                delay: 300,
                position: { my: "left-10 top+30", at: "left top" },
                _renderItem: function (ul, item) {
                    return $("<li>")
                        .data("id", item.value)
                        .append(item.label)
                        .appendTo(ul);
                },
                _renderMenu: function (ul, items) {
                    $(this).val(ui.item.label).data("id", ui.item.value);
                },
                select: function (event, ui) {
                    $(this).val(ui.item.label);
                    $(this).val(ui.item.label).data("id", ui.item.value);
                    $(".js-Itext").blur();
                    if (ui.item.label && ui.item.value) {
                        // window.open("/brands/detail/id_" + ui.item.value + "/");
                        window.open("/search/?key=" + encodeURIComponent(encodeURIComponent(ui.item.label)));
                        searchHis(ui.item.label);
                        initHistoryHot(ui.item.label);
                    }
                    return false;
                },
                focus: function (event, ui) {
                    event.stopPropagation();
                    // if (ui.item) {
                    //     $(this).val(ui.item.label).data("id", ui.item.value);
                    //     $(this).val(ui.item.label);
                    //     // $(this).parents('.js-search-box').find('.look-brand-box').show();
                    // }
                    return false;
                },
                open: function (event, ui) {
                    $('.search_listDown').eq(i).find('.menu-hot-list').hide().find('li').removeClass('ui-state-focus');
                    // var hotWordsHTML = $("#hotKey").html();
                    // if (!$(".log_searchIn .search_listDown .lately_hot").length) {
                    //     $(this).parents('.log_searchIn').find('.search_listDown ul')
                    //         .after(hotWordsHTML);
                    // }
                    // $(this).parents('.js-search-box').find('.look-brand-box').show();
                    $('.search_listDown').eq(i).find('.list_arrow').show();
                    // var $scroll = $('#ui-id-' + parseInt(i + 1));
                    // var mainW = $(".new_first_screen .tool_lists").width();
                    // $(".n_log_searchIn .search_listDown .ui-autocomplete").css({ width: mainW - 190 });
                    // $scroll.perfectScrollbar('destroy');
                    // $scroll.perfectScrollbar();
                },
                close: function (event, ui) {
                    // $(this).parents('.js-search-box').find('.look-brand-box').hide();
                    $('.search_listDown').eq(i).find('.list_arrow').hide();
                    if ($.trim($self.val()) == '' || $.trim($self.val()) == '时尚资讯一网打尽') {
                        if ($(this).is(':focus')) {
                            $('.search_listDown').eq(i).find('.menu-hot-list').show();
                        } else {
                            $(this).attr('placeholder', '搜索');
                            $('.search_listDown').eq(i).find('.menu-hot-list').hide().find('li').removeClass('ui-state-focus');
                        }
                    }
                }
            });
        });
    }
    // 输入框状态初始
    var iptOut = null;
    function initIpt() {
        if (!hotRec.length) {
            $('.search_listDown').find('.hot-list').html('<div class="loading">加载中...</div>');
            $.ajax({
                url: '/suggestion/index/',
                method: 'post',
                data: {
                    keyword: ''
                },
                success: function(res) {
                    var resJson = res;
                    if (typeof res == 'string') {
                        resJson = JSON.parse(res);
                    }
                    if (resJson.code == 0) {
                        hotRec = resJson.data;
                    }
                    initHistoryHot();
                },
                error: function() {
                    $('.search_listDown').find('.hot-list').html('<div class="loading">加载失败!</div>');
                }
            })
        } else {
            initHistoryHot();
        }
        clearTimeout(iptOut);
        iptOut = setTimeout(function(){
            $('.search_listDown').find('.menu-hot-list').show();
        }, 500);
    }
    var itextFlag = true;
    $('.js-Itext').on({
        compositionstart:function(){itextFlag = false;},
        compositionend:function(){itextFlag = true;}
    })
    .on('focus', function() {
        if ($(this).val()) {
            $('.search_listDown').find('.menu-hot-list').hide();
            $(this).autocomplete("search");
        } else {
            initIpt();
        }
    })
    .on('input', function() {
        $('.search_listDown').find('.menu-hot-list').hide();
        var _this = this;
        var isfocus = $(this).is(':focus');
        setTimeout(function(){
            if(itextFlag && isfocus){
                if (!$(_this).val()) {
                    initIpt();
                } else {
                    $('.search_listDown').find('.menu-hot-list').hide();
                }
            }
        },0);
    })
    .on('keydown', function(e) {
        var listDown = $(this).next('.search_listDown');
        var historyLen = listDown.find('.menu-hot-list .history-list li').length;
        var historyCount = listDown.find('.menu-hot-list .history-list li.ui-state-focus').index();
        var hotCount = listDown.find('.menu-hot-list .hot-list li.ui-state-focus').index();
        var count = historyCount != -1 ? historyCount : (hotCount != -1 ? historyLen + hotCount : -1);
        if (e.keyCode == 40) {
            count++;
            if (count > 9) {
                count = 0;
            }
            listDown.find('.menu-hot-list li').removeClass('ui-state-focus').eq(count).addClass('ui-state-focus');
        } else if (e.keyCode == 38) {
            count--;
            if (count < 0) {
                count = 9;
            }
            listDown.find('.menu-hot-list li').removeClass('ui-state-focus').eq(count).addClass('ui-state-focus');
        } else if (e.keyCode == 13) {
            if (count == -1 || $(this).val()) {
                return;
            } else {
                listDown.find('.menu-hot-list li').eq(count).trigger('click');
            }
        }
    })
    initHistoryHot();
    scrolldoc();
    // 全站搜索样式
    $(".js-Itext").on('focus', function (e) {
        e.stopPropagation();
        var placeholder_key = '时尚资讯一网打尽';
        $(this).attr('placeholder', placeholder_key);
        $(this).parent('.js-search-box').addClass('cur');
        $(".js-search-box").animate({ 'width': '317px' }, 500);
        $(".js-Itext").animate({ 'width': '300px' }, 500);
        $('.tel-icon').hide();
    });
    /* $(".look-brand-box").on('click', function (e) {
        window.open('/brands/')
        return false;
        e.stopPropagation();
    }); */
    $(document).scroll(function () {
        if ($('.js-Itext').is(':focus')) {
            $('.js-Itext').blur();
            $("body").trigger('click');
        }
    });

    $("body").on('click', function (e) {
        var itext = $(".js-Itext");
        var key = itext.val();
        if ($.trim(key) == '' || $.trim(key) == '时尚资讯一网打尽') {
            itext.attr('placeholder', '搜索');
        }
        $('.js-search-box').removeClass('cur');
        $(".js-search-box").animate({ 'width': '127px' }, 500);
        $(".js-Itext").animate({ 'width': '110px' }, 500);
        $('.search_listDown').find('.menu-hot-list').hide().find('li').removeClass('ui-state-focus');
        $('.tel-icon').show();
    });

    // 获取brandAll
    function getBrandAll () {
        if (typeof brandAll === 'undefined') {
            var $obj = $("#brandAllInfo");
            var brandAllTime = $obj.data('time');
            var domain = $obj.data('domain');
            $.ajax({
                url: domain + "/global/js/fashion/brandAll.js?" + brandAllTime,
                dataType: "script",
                cache: true,
                success: function () {
                    if (typeof brandAll !== 'undefined') {
                        pop_fashion_global.fn.setSto('brandAllTime', brandAllTime);
                        pop_fashion_global.fn.setSto('brandAll', brandAll);
                    }
                }
            });
        }
    }

    $(".js-Itext").on('click', function (e) {
        e.stopPropagation();
    });

    $(".js-search-btn").on('click', function (e) {
        $(".js-Itext").focus();
        e.stopPropagation();
    });

    var brandCache = {};
    function getBrandsData (request, resposeCallback) {
        var text = $.trim(request.term);
        /* var data = [];
        if (text && typeof brandAll != 'unde') {
            text = text.toUpperCase();
            // 引入brandAll.js
            // if (/^[A-Z]+/.test(text)) {
            //     var alias = text.substr(0, 1);
            //     data = rebuildBrandsArr(text, brandAll[alias]);
            // } else if (/^[0-9]+/.test(text)) {
            //     data = rebuildBrandsArr(text, brandAll["["]);
            // } else {
            //     data = rebuildBrandsArr(text, brandAll["]"]);
            // }
            
            var brandArr = [];
            for (var i in brandAll) {
                brandArr = brandArr.concat(brandAll[i]);
            }
            data = rebuildBrandsArr(text, brandArr);
            if (data.length > 200) {
                data = data.slice(0, 200);
            }
        }
        resposeCallback(data); */
        if (!text) {
            resposeCallback([]);
            return;
        } else if (brandCache[text]) {
            resposeCallback(brandCache[text]);
            return;
        }
        $.ajax({
            url: '/suggestion/index/',
            method: 'post',
            data: {
                keyword: text
            },
            success: function(res) {
                var resJson = res;
                if (typeof res == 'string') {
                    resJson = JSON.parse(res);
                }
                if (resJson.code == 0) {
                    var list = []
                    resJson.data.forEach(function(v) {
                        list.push({
                            label: v.keyword,
                            value: v.score
                        })
                    })
                    brandCache[text] = list;
                    resposeCallback(list);
                }
            }
        })
    }

    function rebuildBrandsArr (text, tempBrands) {
        var brands = [];
        var textLen = text.length;
        for (var i in tempBrands) {
            var brand = tempBrands[i];
            var objBrands = {};
            // 中英文查找
            var en = brand.e.toUpperCase();
            if (en) {
                // if (en.substr(0, text.length) == text) {
                if (en.indexOf(text) != -1) {
                    objBrands.label = brand.e;
                    objBrands.value = brand.i;
                    brands.push(objBrands);
                    continue;
                }
            }
            var cn = brand.c.toUpperCase();
            if (cn) {
                // if (cn.substr(0, text.length) == text) {
                if (cn.indexOf(text) != -1) {
                    objBrands.label = brand.e;
                    objBrands.value = brand.i;
                    brands.push(objBrands);
                    continue;
                }
            }
        }
        // return brands;
        return brands.sort(function(a, b) {
            var nameA = a.label.toUpperCase();
            var nameB = b.label.toUpperCase();
            var count1 = 0,count2 = 0;
            var str = text;
            var len1 = a.label.length;
            var len2 = b.label.length;
            var idx1 = nameA.indexOf(text);
            var idx2 = nameB.indexOf(text);
            var l1,l2;
            /* 完全匹配 */
            if (nameA == text) {
                count1 += 1000;
            }
            if (nameB == text) {
                count2 += 1000;
            }
            /* 以输入字符开头 */
            if (nameA.substr(0,textLen) == text) {
                count1 += 200;
            }
            if (nameB.substr(0,textLen) == text) {
                count2 += 200;
            }
            /* 完整包含 */
            if (nameA.indexOf(text) != -1) {
                count1 += 100;
            }
            if (nameB.indexOf(text) != -1) {
                count2 += 100;
            }
            /* 位置 */
            count1 += textLen - (idx1 != -1 ? idx1 : 0);
            count2 += textLen - (idx2 != -1 ? idx2 : 0);
            /* 重复度+ */
            for (var i = textLen; i > 0; i--) {
                str = str.substr(0, i);
                l1 = nameA.split(str).length - 1;
                l2 = nameB.split(str).length - 1;
                count1 += l1/len1+i;
                count2 += l2/len2+i;
            }
            /* 重复度- */
            // for (var i = 1; i <= textLen; i++) {
            //   str = str.substr((textLen - i), textLen);
            //   l1 = nameA.split(str).length - 1;
            //   l2 = nameB.split(str).length - 1;
            //   count1 += l1/len1+i;
            //   count2 += l2/len2+i;
            // }
            a.count = count1;
            b.count = count2;
            return b.count - a.count;
        });
    }
});

//注册pidtt
$(function () {
    //获取URL参数
    function getQueryString (name) {
        var reg = new RegExp('(^|&)' + name + '=([^&]*)(&|$)', 'i');
        var r = window.location.search.substr(1).match(reg);
        if (r != null) {
            return unescape(r[2]);
        }
        return null;
    }
    function setcookie_pidtt () {
        var pidVal = getQueryString('pid') == null ? '' : getQueryString('pid');
        if (!pidVal) {
            return false;
        }
        if (/^\+?[0-9]*$/.test(pidVal)) {
            $.cookie('pidtt', pidVal, { path: '/', domain: 'pop-fashion.com' })
        } else {
            return;
        }
    };
    setcookie_pidtt();

    // 关于我们
    var lis = $('.aboutUs .content_left li.on');
    lis.on('mouseenter mouseleave', function (e) {
        if (e.type == 'mouseenter') {
            $(this).addClass('cur');
        } else {
            $(this).removeClass('cur');
        }
    });
    
    // 首页导航下拉菜单
    $('.nav-lamu li.lmhover').on('mouseenter mouseleave', function (e) {
        $self = $(this);
        if (e.type == 'mouseenter') {
            $self.find('.lanmu_box').stop(true, true).slideDown(200);
        } else {
            $self.find('.lanmu_box').stop(true, true).slideUp(200);
        }
    });

    // 图片识别过度效果
    var defaultTop = -4;
    var $statusList = $(".statusList");
    var timer;
    function statusMove () {
        if (defaultTop < -180) {
            defaultTop = -4;
        }
        defaultTop -= 10;
        $statusList.css('top', defaultTop + 'px');
    }
    timer = setInterval(statusMove, 100);

});

//图片上传
function ajaxFabricUpload (o) {
    var options = {
        beforeSubmit: function () {
            return true;
        },
        type: 'post',
        url: '/picmatch/uploadpic/?time=' + Math.random(),
        dataType: 'json',
        success: function (obj) {
            if (obj.success == 1) {
                window.location.href = obj.path;
                return true;
            } else {
                oCommon.noPower('', '上传失败');
                return false;
            }
        },
        error: function (error) {
            oCommon.noPower('', error.responseText);
            return false;
        }
    };
    $(o).closest('form.uploadFileForm').ajaxSubmit(options);
}

//是否是PC端
function IsPC () {
    var userAgentInfo = navigator.userAgent;
    var Agents = [
        "Android", "iPhone",
        "SymbianOS", "Windows Phone",
        "iPod"
    ];
    var flag = true;
    for (var v = 0; v < Agents.length; v++) {
        if (userAgentInfo.indexOf(Agents[v]) > 0) {
            flag = false;
            break;
        }
    }
    return flag;
}

// 手机端显示
(function (doc, win) {
    var docEl = doc.documentElement,
        resizeEvt = 'orientationchange' in window ? 'orientationchange' : 'resize',
        recalc = function () {
            var clientWidth = docEl.clientWidth;
            if (!clientWidth) return;
            docEl.style.fontSize = 40 * (clientWidth / 750) + 'px';
        };
    if (!doc.addEventListener) return;
    win.addEventListener(resizeEvt, recalc, false);
    doc.addEventListener('DOMContentLoaded', recalc, false);
})(document, window);


// 首次手机端进入弹出页面
window.onload = function () {
    var mlayer = document.getElementById("m_layer");
    var know = document.getElementById("i_know");
    if (know) {
        if (know.addEventListener) {                    //所有主流浏览器，除了 IE 8 及更早 IE版本
            know.addEventListener("touchend", fn, true);
        } else if (know.attachEvent) {                  // IE 8 及更早 IE 版本
            know.attachEvent("touchend", fn, true);
        }
    } else {
        return;
    }

    function fn () {
        m_layer.style.display = 'none';
    }
};
//pop快印
function popky () {
    var json = JSON.parse($("#popky_json").val());
    var $nowImg = $("#draggable").find("img").data('bp');//当前被选中大图
    for (var i in json['data']) {
        var str1 = json['data'][i]['bigPic'].replace(/http:\/\/(.*?)\.com/, '');
        var str2 = $nowImg.replace(/http:\/\/(.*?)\.com/, '');
        if (str1 == str2) {
            json['data'][i]['isSelected'] = 1;
        } else {
            json['data'][i]['isSelected'] = 0;
        }
    }
    $("#popky_json").val(JSON.stringify(json));
    document.getElementById("popky_form").submit();
}
//pop快印提示
var popky_info = $(".popky_info");
var guide = $.cookie('guide') ? $.cookie('guide') : '';
guide = guide.split('-');
if ($.inArray('12', guide) == -1) {
    popky_info.show();
}
popky_info.on('click', function () {
    popky_hide();
});
function popky_hide () {
    guide.push('12');
    var guideVal = guide.join('-').replace(/^-/, '').replace(/-$/, '');
    $.cookie('guide', guideVal, { expires: 365, path: '/', domain: '.pop-fashion.com' });//一年
    $(".popky_info").hide();
}

//新导航
$(function () {
    var def = {
        input_ele1: $(".js-form-list1 .js-input-area"),
        input_ele2: $(".js-form-list2 .js-input-area"),
        re: {
            mobile: /^1[1-9][0-9]{9}$/,
        },
        is_sub: false,
        return_data: {}
    };

    // 申请试用
    $(".js-apply-item").on("click", function () {
        $(".js-feedback-section-box").fadeIn(200);
        $(".js-bg-div").fadeIn(400);
    });
    $(".js-feedback-section-box>button").on("click", function () {
        $(this).parent().fadeOut(200);
        $(".js-bg-div").fadeOut(400);
    });

    // QQ 咨询 2018-11-09
    var $qqPanel = $('#qqPanel');
    // 显示 QQ 咨询面板
    window.showWPA = function (type) {
        // type: 0-售前, 1-售后
        var url = '/home/wpa/';
        url = type ? url + '?type=1' : url; // 售后传参 type = 1, 售前传参 type = 0 或不传
        if ($qqPanel.length === 0) {
            var windowWidth = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
            var panelWidth = 465;
            var left = (windowWidth - panelWidth) / 2; // ie8 不支持 calc, 故手动计算
            $qqPanel = $('<iframe id="qqPanel" src="#" style="' +
                'position:fixed; ' +
                'width: ' + panelWidth + 'px; ' +
                'height: 294px; ' +
                'z-index: 99; ' +
                'top: 25%; ' +
                'left: ' + left + 'px; ' +
                // 'left: calc(50% - 232px);' + // ie8 不支持 calc
                'display: none;"></iframe>');
            $qqPanel.appendTo('body');
        }
        $qqPanel.attr('src', url).show();
    };
    // 隐藏 QQ 咨询面板
    window.hideWPA = function () {
        $qqPanel.hide();
    };

    $('body').on('click', '.js-contact-qq-btn', function (e) {
        // after-售后, 否则-售前
        var type = $(this).data('type');
        showWPA(type);
    });

    // input判断
    def.input_ele1.on("focus", function () {
        var error_ele = $(this).siblings("p");
        error_ele.hide();
    }).on("blur", function () {
        checkFunc($(this), def.input_ele1);
    });

    //限制只能输入数字
    def.input_ele1.eq(1).on("input propertychange", function () {
        var re = /\D/g;
        var txt = $(this).val();
        if (re.test(txt)) {
            txt = txt.replace(re, "");
            $(this).val(txt);
        }
    });
    def.input_ele2.on("focus", function () {
        var error_ele = $(this).siblings("p");
        error_ele.hide();
    }).on("blur", function () {
        checkFunc($(this), def.input_ele2);
    });

    //限制只能输入数字
    def.input_ele2.eq(1).on("input propertychange", function () {
        var re = /\D/g;
        var txt = $(this).val();
        if (re.test(txt)) {
            txt = txt.replace(re, "");
            $(this).val(txt);
        }
    });

    // 提交数据
    $(".js-sub-btn").on("click", function () {
        subFunc($(this));
    });
    // 回车事件
    $(".js-form-div").on("keydown", function (e) {
        var keycode = e.keyCode;
        if (keycode == 13) {
            $(this).children(".js-sub-btn").click();
        }
    });
    // 申请试用
    function checkFunc (obj, par) {
        var index = par.index(obj);
        var key_name = obj.attr("name");
        var is_ok = true, txt = obj.val();
        var error_ele = obj.siblings("p");
        txt = txt.replace(/\s/g, "");
        var msg_txt = "";

        switch (index) {
            case 0:
                if (txt == "") {
                    is_ok = false;
                    msg_txt = "请填写姓名";
                } else if (general.fn.getCharacterLen(txt) < 4 || general.fn.getCharacterLen(txt) > 20) {
                    is_ok = false;
                    msg_txt = "4-20位字符，一个汉字是两个字符";
                }
                break;
            case 1:
                if (txt == "") {
                    is_ok = false;
                    msg_txt = "手机号码为空，请正确填写";
                }
                // else if(txt.length<11 || def.re.mobile.test(txt)==false){
                //     is_ok=false;
                //     msg_txt="请输入11位正确手机号码";
                // }
                break;
            default:
                break;
        }
        error_ele.text(msg_txt);
        if (is_ok == true) {
            error_ele.hide();
        } else {
            error_ele.show();
        }
        obj.val(txt);
        def.return_data[key_name] = txt;
        return is_ok;
    };


    function subFunc (obj) {
        var par = obj.siblings(".js-form-list").find(".js-input-area");
        if (def.is_sub == true) { return false };
        var is_next = true;

        par.each(function (i) {
            is_next = checkFunc($(this), par);
            if (i == 2) { is_next = true };
            return is_next;
        });
        if (is_next == false) {
            return false;
        } else {
            var select_ele = obj.siblings(".js-form-list").find(".js-select-div");
            var select_val = select_ele.children("span").attr("value") || "";
            if (select_val == "") {
                select_ele.siblings("p").show();
                return false;
            }
            obj.text("提交中...");
            def.is_sub = true;
            def.return_data["type"] = select_val;
            general.fn.subAjax({
                url: "/ajax/fmfeedback/",
                data: def.return_data,
                ctp: "application/x-www-form-urlencoded",
                success: function (data) {
                    obj.text("马上联系我们");
                    def.is_sub = false;
                    msg.msg({ "txt": "pop小秘书已经收到您的信息，会在两个工作日内跟您联系，请保持电话畅通哦" }, 2000);
                    $(".js-input-area").val("");
                    $(".js-select-div").each(function (i) {
                        var type = $(this).attr("data-type");
                        if (type == 1) {
                            $(this).children("span").removeClass("now-choice");
                            $(this).children("span").attr("value", "");
                            $(this).children("span")[0].is_show = false;
                            $(this).children("span").text("请选择");
                        } else {
                            $(".js-feedback-section-box").fadeOut(200);
                            $(".js-bg-div").fadeOut(400);
                        }
                    });
                },
                error: function (data) {
                    var ndata = data || {};
                    obj.text("马上联系我们");
                    def.is_sub = false;
                }
            })
        }
    };

    //会员过期提醒
    var remind_time = $("#remind-time").val();
    var vip_remind = $(".remind-list");  //会员过期提醒
    var sta_remind = false;
    var curDate = new Date();
    var reDate = pop_fashion_global.fn.getSto("vip_remind_time") != undefined ? pop_fashion_global.fn.getSto("vip_remind_time") : 0;
    sta_remind = $.cookie('vip_remind') ? $.cookie('vip_remind') : "false";
    if (vip_remind.length > 0) {
        if (remind_time > 7) {
            if (curDate.getTime() < reDate) {
                vip_remind.hide();
                $(".new_nav_box").css("marginTop", "40px");
            } else {
                vip_remind.show();
                pop_fashion_global.fn.delSto("vip_remind_time");
                $(".new_nav_box").css("marginTop", "90px");
            }
        } else if (remind_time <= 7) {
            if (sta_remind == "true") {
                vip_remind.hide();
                $(".new_nav_box").css("marginTop", "40px");
            } else {
                vip_remind.show();
                $(".new_nav_box").css("marginTop", "90px");
            }
        } else {
            if (sta_remind == "true") {
                vip_remind.hide();
                $(".new_nav_box").css("marginTop", "40px");
            } else {
                vip_remind.show();
                $(".new_nav_box").css("marginTop", "90px");
            }
        }
        $(".js-remind-close").on("click", function () {
            vip_remind.hide();
            $(".new_nav_box").css("marginTop", "40px");
            if (remind_time > 7) {
                pop_fashion_global.fn.setSto("vip_remind_time", getRreindTime())   //存储关闭时间
            } else {
                $.cookie('vip_remind', "true", { path: '/' });
            }
        })
    }
    //自然天7天后
    function getRreindTime () {
        var curDate = new Date();
        var reDate = new Date(curDate.getTime() + (24 * 60 * 60 * 1000) * 7);
        var Y = reDate.getFullYear() + '/';
        var M = (reDate.getMonth() + 1 < 10 ? '0' + (reDate.getMonth() + 1) : reDate.getMonth() + 1) + '/';
        var D = reDate.getDate();
        var reDate1 = new Date(Y + M + D + " 00:00:00");
        return reDate1.getTime();
    }

    //游客弹层滚动
    $(window).scroll(function () {
        if ($(".tourist").length > 0) {
            var isScroll = $("body").scrollTop() == 0 ? $("html").scrollTop() : $("body").scrollTop();
            var isHeight = $("#isHeight").offset().top - isScroll - 40;
            var isTop = $(".tourist").offset().top - isScroll - 40;
            if (isTop > 0) {
                $(".permission").removeClass("per_action");
            } else if (isTop <= 0 && isHeight > -600) {
                $(".permission").addClass("per_action");
                $(".permission").removeClass("per_action1");
            } else if (isHeight <= -600) {
                $(".permission").removeClass("per_action");
                $(".permission").addClass("per_action1");
            }
        }
    });

    // 首页导航下拉
    var $down = $(".downlist_box");
    var timer;
    $(".new_lanmu li.has_down").on('mouseenter', function () {
        var self = $(this);
        timer = setTimeout(function () {
            self.find('.downlist_box').stop(true, true).show();
        }, 100)
    });
    $(".new_lanmu li.has_down").on('mouseleave', function () {
        clearTimeout(timer);
        $(this).find('.downlist_box').stop(true, true).hide();
        $(this).find(".d_dr").hide();
    });

    // 语言下拉
    $(".js-leftT").on('mouseenter mouseleave', function (e) {
        if (e.type == "mouseenter") {
            $(".js-lang-list").show();
        } else {
            $(".js-lang-list").hide();
        }
    })

    // 联系方式下拉
    $(".js-tel-and-qq").on('mouseenter mouseleave', function (e) {
        if (e.type == "mouseenter") {
            $(".js-tel-downlist").show();
        } else {
            $(".js-tel-downlist").hide();
        }
    })

    
    $(".js-all-nav-li").on('click', function () {
        $(this).toggleClass('fs');
        $(".js-dropdown-nav").stop(true, true).slideToggle(300, function () {
            $(".js-head-top").toggleClass('is-down');
        });
    });
    if (P_UserType == 1) {
        $('.js-mydata-item').show();
    }
    // 我的数据提示
    $('.js-mydata-btn').on('click', function () {
        msg.msg({ 'txt': '您的月度账号使用数据表正在生成中，请于下月初再次查询~' });
    });

    // 提示VIP账号创建子账号弹层
    setTimeout(function () {
        var pop_fashion_vip_cookie = pop_fashion_global.fn.getCookie('_VIP_CREATE_CHILD_ACCOUNT');
        if (pop_fashion_vip_cookie == null) {
            pop_fashion_global.fn.subAjaxGet({
                url: '/ajax/showvipcreatechildaccountpopup/',
                successFunc: function (data) {
                    var vip_add_layer = $(".js-vip-add-layer");
                    vip_add_layer.show();
                    $(".js-vip-close").click(function () {
                        vip_add_layer.hide();
                    })

                },
                errorFunc: null
            })
        }
    }, 2000);


    // 最新推荐
    var is_new_trends_fix = false;
    var pop_new_trend = pop_fashion_global.fn.getSto('pop_new_trend') || 0;	//上次推荐时间
    var pop_new_trend_time = new Date().getTime();

    if ($('.js-get-new-trend').length > 0 && pop_new_trend_time - pop_new_trend >= 0 && (is_relate == undefined || is_relate == false)) {
        getNewTrend();  // 获取最新推荐
    }

    // 关闭弹框
    $('.js-new-trends-bg, .js-new-trends-fix .trends-msg-close').on('click', function () {
        $('.js-new-trends-fix').addClass('trends-fix-start');
        $('.js-new-trends-fix').fadeOut(280);
    });


    $('.js-get-new-trend').on('click', function () {
        getNewTrend();  // 获取最新推荐
    });

    // 获取最新推荐
    function getNewTrend () {

        if (is_new_trends_fix) {
            return;
        }
        is_new_trends_fix = true;
        $('.js-new-trends-fix').show();
        var trend_time = (new Date(getNowDate())).getTime() + 24 * 3600 * 1000;
        pop_fashion_global.fn.setSto('pop_new_trend', trend_time);	//存储

        newtrendStart();
        setTimeout(function () {
            $('.js-new-trends-fix').removeClass('trends-fix-start');
        }, 50);

        $.ajax({
            url: '/ajax/getrecommendframe/?r=' + Math.random(),
            type: 'get',
            dataType: "json",
            contentType: 'application/x-www-form-urlencoded',
            success: function (data) {
                var code = data.code, _data = data.data;
                if (code == 0) {
                    setNewtrend(_data);	//写入数据
                }
                is_new_trends_fix = false;
            },
            error: function () {
                is_new_trends_fix = false;
            }
        });

    }


    // 初始化弹框
    function newtrendStart () {
        $('.js-new-trends-fix').addClass('trends-fix-start');

        $('.js-new-trends-fix .new-trends-list').addClass('load-start');
        $('.js-new-trends-fix .js-new-trends').html('').addClass("load-start");
        $('.js-new-trends-fix .js-trends-pics>ul').html('');
        $('.js-new-trends-fix .report-new').html('');
        $('.js-new-trends-fix .js-trends-navs').html('');
        $('.js-new-trends-fix .trends-nav').hide();
        $('.js-new-trends-fix .new-explore-bott').hide();
        $('.js-new-trends-fix .is-user').removeClass('user-type4').removeClass('user-type5');
        $('.js-new-trends-fix .is-user>p').text('');

    }

    // 最新推荐dom
    function setNewtrend (data) {
        var data = data || {};
        var report_list = data.report_list || [],
            free_report = data.free_report || [],
            guide_bar = data.guide_bar[0] || {},
            recommend_ads = data.recommend_ads || [],
            user_type = data.user_type || 0,
            style = data.style || {},
            pattern = data.pattern || {},
            runways = data.runways || {},
            brands = data.brands || {};

        var nav_html = '<li class="sec-nav"><a href="javascript:void(0);" title="趋势分析">趋势分析</a><i></i></li>'
        var style_html = '<li><a href="' + (style.link || '') + '" target="_blank" title="款式集结"><div>款式集合</div><p>近日更新</p><span>' + (style.count || 0) + '<span>款</span></span></a></li>'

        var pattern_html = '<li><a href="' + (pattern.link || '') + '" target="_blank" title="图案素材"><div>图案素材</div><p>近日更新</p><span>' + (pattern.count || 0) + '<span>款</span></span></a><div class="bord-top"></div></li>'

        var runways_html = '<li><a href="' + (runways.link || '') + '" target="_blank" title="T台秀场"><div>T台秀场</div><p>近日更新</p><span>' + (runways.count || 0) + '<span>场</span></span></a><div class="bord-top"></div></li>'

        var brands_html = '<li><a href="' + (brands.link || '') + '" target="_blank" title="发现品牌"><div>发现品牌</div><p>品牌趋势</p><span>' + (brands.count || 0) + '</span></a><div class="bord-top"></div></li>'

        nav_html = nav_html + style_html + pattern_html + runways_html + brands_html;

        var report_list_html = '', guide_bar_html = '', recommend_ads_html = '';
        for (var i = 0; i < report_list.length; i++) {
            var columnId = report_list[i].columnId || '';
            var id = report_list[i].list.id || '';
            var tableName = report_list[i].tableName || '';
            var cover = report_list[i].list.cover || '';
            var title = report_list[i].list.title || '';

            report_list_html += '<li><a href="/details/report/t_' + tableName + '-id_' + id + '-col_' + columnId + '/" target="_blank" title="' + title + '"><img src="' + cover + '">';
            report_list_html += '<div class="new-item">' + title + '</div></a></li>';
        }

        if ((user_type == 5 || user_type == 4) && free_report[0] != undefined) {
            var columnId = free_report[0].columnId || '';
            var id = free_report[0].list.id || '';
            var tableName = free_report[0].tableName || '';
            var cover = free_report[0].list.cover || '';
            var title = free_report[0].list.title || '';
            report_list_html += '<li><a href="/details/report/t_' + tableName + '-id_' + id + '-col_' + columnId + '/" target="_blank" title="' + title + '"><img src="' + cover + '">';
            report_list_html += '<div class="new-item">' + title + '</div><p>免费试读</p></a></li>';
        }

        // 最新免费资源
        if (guide_bar.sLink) {
            guide_bar_html += '<a href="' + guide_bar.sLink + '" target="_blank"><img src="' + guide_bar.sImagePath + '"></a>';
        }

        // banner 广告位
        if (recommend_ads.length) {
            for (var j = 0; j < recommend_ads.length; j++) {
                var sTitle = recommend_ads[j].sTitle || '';
                var sImagePath = recommend_ads[j].sImagePath || '';
                var sLink = recommend_ads[j].sLink || '';
                recommend_ads_html += '<li><a href="' + sLink + '" target="_blank" title="' + sTitle + '"><img src="' + sImagePath + '" alt="' + sTitle + '"></a></li>'
            }
        }

        $('.js-new-trends-fix .new-trends-list').removeClass('load-start');
        $('.js-new-trends-fix .js-new-trends').html(report_list_html).removeClass("load-start");
        $('.js-new-trends-fix .js-trends-pics>ul').html(recommend_ads_html);
        $('.js-new-trends-fix .report-new').html(guide_bar_html);
        $('.js-new-trends-fix .js-trends-navs').html(nav_html);
        $('.js-new-trends-fix .trends-nav').show();

        $('.js-new-trends-fix .new-explore-bott').show();
        if (user_type == 5) {		//游客
            $('.js-new-trends-fix .is-user').removeClass('user-type4').addClass('user-type5');
            $('.js-new-trends-fix .is-user>p').text('以上为付费报告，登录后可查看部分免费内容');
        } else if (user_type == 4) {		//普通用户
            $('.js-new-trends-fix .is-user').removeClass('user-type5').addClass('user-type4');
            $('.js-new-trends-fix .is-user>p').text('想体验完整网站？联系客服开通免费试用');
        }
    }

    // 会员是否过期
    // pop_fashion_global.fn.subAjaxGet({
    // 	url: '/ajax/getVipExpireInfo',
    // 	successFunc: function (data) {
    // 		var message = data["message"] ? data["message"] : "";
    // 		var _html = "";
    // 		var expire_time = $(".js-expire-time");
    // 		if (message != "") {
    // 			_html += '<button><span></span>' + message + '</button>';
    // 			$(".js-expire-right").append(_html);
    // 			var wid = expire_time.width();
    // 			var auto_wid = expire_time.css('width', 'auto').width();
    // 			expire_time.width(wid).animate({ width: auto_wid - 1 }, 500);
    // 			expire_time.show();
    // 		}
    // 	},
    // 	errorFunc: null
    // });





    function getNowDate () {
        var date = new Date();
        var y = date.getFullYear();
        var m = date.getMonth() + 1;
        var d = date.getDate();
        return y + "/" + m + "/" + d;
    };
});


var _def = {
    slide_Id: '',
    clientWidth: '1200'
}
var regex = {}
/* 登录改版 */
$(function () {
    var regexEnum = {
        password: '^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$',
        mobile: '/^1[34578]\d{9}$/'
    }
    regex.mobileFunc = function (_this) {
        var span = _this.siblings('p.msg');
        var username = $.trim(_this.val());
        if (!username || !RegExp(regexEnum.mobile, 'ig').test(username)) {
            span.html('手机号格式不正确！').show();
            _this.attr('data-isTable', 'false');
            return false;
        } else {
            _this.attr('data-isTable', 'true');
            span.html('').hide();
            return true;
        }
    };
    regex.passwordFunc = function (_this) {
        var span = _this.siblings('p.msg');
        var username = $.trim(_this.val());
        if (!username || !RegExp(regexEnum.password, 'ig').test(username)) {
            span.html('6-20位字符，可由数字、字母、特殊字符组成').show();
            _this.attr('data-isTable', 'false');
            return false;
        } else {
            _this.attr('data-isTable', 'true');
            span.html('').hide();
            return true;
        }
    };
    /* 切换账号 */
    $('.js-returnLi').mouseover(function () {
        $('.js-returnLi i').css('transform', 'rotate(-180deg)');
        $(".js-forUl").show();
    }).mouseout(function () {
        $('.js-returnLi i').css('transform', 'rotate(0)');
        $(".js-forUl").hide();
    });
    $('.js-returnLi').on('click', function () {
        if (/AppleWebKit.*Mobile/i.test(navigator.userAgent) || (/MIDP|SymbianOS|NOKIA|SAMSUNG|LG|NEC|TCL|Alcatel|BIRD|DBTEL|Dopod|PHILIPS|HAIER|LENOVO|MOT-|Nokia|SonyEricsson|SIE-|Amoi|ZTE/.test(navigator.userAgent))) {

        } else {
            location.href = "/member/myHistory/4/";
        }
    })
    var _obj = {
        str: "",
        url: '//member.pop136.com',
        codeState: false,
        jwt: pop_fashion_global.fn.loginJWT(),
        id: '',
        isSlide: false
    }
    //ajax基础封装
    jQuery.ajaxFun = function (url, obj, fun, syn, errfun) {
        //Safari cookie丢失适配
        if (/Safari/.test(navigator.userAgent) && !/Chrome/.test(navigator.userAgent)) {
            $.ajax({
                type: "GET",
                url: 'https://member.pop136.com/safari?callback=back',
                dataType: "jsonp",
                async: 'false',
                xhrFields: { withCredentials: true },
                success: function (data) {
                    if (data.data.window) {
                        var mywin = window.open('https://member.pop136.com/safari?callback=back', '_blank');
                        setTimeout(function () {
                            mywin.close();
                            $.ajax({
                                type: "GET",
                                url: url,
                                //timeout: 5000,
                                dataType: "jsonp",
                                xhrFields: { withCredentials: true },
                                data: obj,
                                async: syn,
                                success: fun,
                                error: errfun
                            })
                        }, 500);
                    } else {
                        $.ajax({
                            type: "GET",
                            url: url,
                            //timeout: 5000,
                            dataType: "jsonp",
                            xhrFields: { withCredentials: true },
                            data: obj,
                            async: syn,
                            success: fun,
                            error: errfun
                        })
                    }
                },
                error: function () {
                    $.ajax({
                        type: "GET",
                        url: url,
                        //timeout: 5000,
                        dataType: "jsonp",
                        xhrFields: { withCredentials: true },
                        data: obj,
                        async: syn,
                        success: fun,
                        error: errfun
                    })
                }
            })
        } else {
            $.ajax({
                type: "GET",
                url: url,
                //timeout: 5000,
                dataType: "jsonp",
                xhrFields: { withCredentials: true },
                data: obj,
                async: syn,
                success: fun,
                error: errfun
            })
        }
    }
    $(".js-change").on('click', slideFun);
    $(".js-click-btn").on("click", slideFun);
    $(".js-close").on("click", function () {
        $(this).parent().parent().parent().hide();
    })
    //获取滑块验证码
    var isDrag = true;
    var isStu;
    function slideFun () {
        isStu = $(this).attr('data-state');
        if (_obj.isSlide) return;
        if (isStu == '0') return;
        var _this;
        _obj.isSlide = true;
        var box = $(this).attr('data-cla');
        if (box != '' && box != undefined) {
            _this = $(this);
        } else {
            _this = $(this).parent().parent().parent().prev();
        }
        changeCode(_this);
        var stu = _this.data('stu');
        var objdrag = new setDrag({
            'box': _obj.str == '' ? $($(".js-click-btn")[0]).attr('data-cla') : _obj.str,
            success: function (data) {
                var obj = {
                    "trail": data.data, //拖动轨迹
                    "startTime": data.start, //拖动开始时间
                    "endTime": data.end, //拖动结束时间
                    "slideId": _obj.id, //拖动ID
                    "drag_x": data.data.x //最后拖动位置
                }
                //验证滑块拖动
                var url = _obj.url + "/captcha/ckeckSlide";
                $.ajaxFun(url, obj, function (data) {
                    if (data.code == '0') {
                        _def.slide_Id = data.data.dragtoken;

                        if(stu=='1'){
                            _this.html('验证通过<i></i>');
                            _this.addClass('stu');
                            _this.attr('data-state','0');
                        }else{
                            $(_obj.str).parent().prev().css('border', '1px solid #232f3c');
                            $(_obj.str).parent().prev().css('color', '#232f3c');
                        }
                        $(_obj.str + ' .drag-slide-center').css('background-color', '#232f3c');
                        $(_obj.str + ' .drag-slide-center').css('color', '#fff');
                        $(_obj.str + ' .drag-slide-center').css('border-radius', '15px');
                        $(_obj.str + ' .drag-slide-center').html('验证通过');
                        $(_obj.str + ' .drag-slide-left').hide();
                        $(_obj.str + ' .js-drag-slide-btn').hide();
                        setTimeout(function () {
                            $(_obj.str).parent().hide();
                            $(_obj.str + ' .js-puzzle-lost').css('left', '0px');
                            $(_obj.str + ' .drag-slide-left').css('width', '0px');
                            $(_obj.str + ' .js-drag-slide-btn').css('left', '0px');
                            isDrag = true;
                        }, 1000);
                        $(_obj.str).parent().prev().unbind('mouseenter').unbind('mouseleave');
                        $('.errorbox').hide();
                        $('.errorbox10').hide();
                    } else {
                        changeCode(_this, objdrag);
                        $(_obj.str + ' .drag-slide-center').html('验证失败，请重新拖动图片');
                    }
                })
            },
            error: function (data) {
            }
        });
        if (isDrag) {
            objdrag.init();
            isDrag = false;
        }
    }
    function changeCode (_this, objdrag) {
        _obj.str = _this.attr('data-cla');
        $(_obj.str + ' .drag-slide-center').html('向右滑动完成拼图');
        $(_obj.str + ' .drag-slide-center').css('color', '#666');
        $(_obj.str + ' .drag-slide-center').css('background-color', 'rgba(255,255,255,0.2)');
        $(_obj.str + ' .drag-slide-left').show();
        $(_obj.str + ' .js-drag-slide-btn').show();
        $(_obj.str).parent().show();
        var obj = {
            "clientWidth": _def.clientWidth//
        }
        var url = _obj.url + "/captcha/slide";
        $.ajaxFun(url, obj, function (data) {
            $(_obj.str + " .js-puzzle-img img").attr('src', data.data.bgImg);
            $(_obj.str + " .js-puzzle-lost img").attr('src', data.data.slideImg);
            $(_obj.str + " .js-puzzle-lost").css('top', data.data.slidePosY);
            _obj.id = data.data.slideId;
            _obj.isSlide = false;
        })
        _this.next().show();
        if (objdrag) {
            objdrag.init();
        }
    }
    //获取页面宽度
    function clientWid () {
        var wid = $(window).width();
        if (wid >= 1500) {
            _def.clientWidth = 1500;
        } else {
            _def.clientWidth = 1200;
        }
    };
    clientWid();
    $(window).resize(function () {
        clientWid();
    });
    //跳转到留言页面
    $('.js-message-user').on('click', function () {
        location.href = "/member/leavemessage";
    })
    //获取验证码
    $('.js-code-btn1').on('click', function () {
        if (_obj.codeState) return;
        var mobile = $('.js-account').val();
        if (mobile == '') {
            $('.errorbox').html('<i></i><span>请先输入手机号</span>').show();
            return;
        }
        var _this = $(this);
        var url = _obj.url + '/captcha/sendSms';
        var obj = {
            'mobile': $('.js-account').val(),   //手机号
            'website': '1',         //站点
            'sence': 'CHANGE_LOGIN',   //短信验证类型
            'dragtoken': _def.slide_Id,     //滑块验证，除注册外，非必填
            'jwt': _obj.jwt
        }
        $.ajaxFun(url, obj, function (data) {
            if (data.code == '0') {
                var i = 60;
                var code = setInterval(function () {
                    _obj.codeState = true;
                    _this.text('已发送(' + i + ')');
                    _this.css('backgroundColor', '#ccc');
                    i--;
                    if (i < 0) {
                        _obj.codeState = false;
                        _this.text('获取验证码');
                        _this.css('backgroundColor', '#232f3c');
                        clearInterval(code);
                    }
                }, 1000);
            } else {
                $('.errorbox').html('<i></i><span>' + data.msg + '</span>').show();
                if (data.errorCode == 'validation.captcha.slide.need' || data.errorCode == 'validation.captcha.slide.incorrect') {
                    $('.js-click-btn').show();
                    $('.js-slip-code').show();
                }
            }
        })
    })

    $('.js-switch').on("click", function (e) {
        e.stopPropagation();
        var type = $(this).attr('data-type');
        if (type == "1") {
            switchFun();
        } else {
            $('.js-switch-box').show();
            $('.js-switch-bg').show();
        }
    })
    $('.js-like').on('click',function(){
        if(P_UserType == '5'){
            $('.js-login-btn').attr('data-status','true');
            oCommon.loginLayer('true');
        }else{
            window.open("/patterns/patternRecommend/");
        }
    })
    $('.js-loginClose').on("click", function () {
        $('.js-switch-box').hide();
        $('.js-switch-bg').hide();
    })
    //退出登录
    $('.js-signOut').on("click", function (e) {
        e.stopPropagation();
        location.href = "/member/logout/"
    })
    $('.js-changeUser').on("click", function () {
        switchFun();
    })
    function switchFun () {
        var url = _obj.url + '/login/changeUser';
        var obj = {
            'jwt': _obj.jwt,   //获取登录存储的cookie值
            'website': '1',         //站点
            'captcha': $('.js-switch-code').val()
        }
        $.ajaxFun(url, obj, function (data) {
            if (data.code == '0') {
                var POP_USER = data.data.jwt;
                /* pop_fashion_global.fn.setCookie('POP_USER', "", -1); */
                $.cookie('NO_REFRESH_JWT', '1', { path: '/', domain: 'pop-fashion.com', secure: false, raw: false });
                $.cookie('POP_USER', POP_USER, { expires: data.data.expire, path: '/', domain: 'pop-fashion.com', secure: false, raw: false });
                location.href = '/';
            } else {
                $('.errorbox3').html('<i></i><span>' + data.msg + '</span>').show();
            }
        })
    }
    //防止单点登录丢失,刷新jwt
    function refreshUserJwt () {
        var POP_USER = $.cookie('POP_USER');
        var NO_REFRESH_JWT = $.cookie('NO_REFRESH_JWT');
        var url = _obj.url + '/login/refreshUserJwt';
        var obj = {
            'jwt': POP_USER
        }
        if (POP_USER && !NO_REFRESH_JWT) {
            $.ajaxFun(url, obj, function (data) {
                var pop_user = data.data.jwt;
                if (data.code == '0') {
                    $.cookie('NO_REFRESH_JWT', '1', { path: '/', domain: 'pop-fashion.com', secure: false, raw: false });
                    $.cookie('POP_USER', pop_user, { expires: data.data.expire, path: '/', domain: 'pop-fashion.com', secure: false, raw: false });
                }
            })
        }
    }
    refreshUserJwt();
    //侧边绑定手机号引导
    $(window).on('load', function () {
        var NOT_BIND_MOBILE = general.fn.getCookie('NOT_BIND_MOBILE');
        if (NOT_BIND_MOBILE) {
            $('.bindPhone').show();
            var openQuickT1, openQuickT2;
            openQuickT1 = setInterval(function () {
                $('.js-open-quick-login').addClass('anit');
                clearInterval(openQuickT2);
                openQuickT2 = setInterval(function () {
                    $('.js-open-quick-login').removeClass('anit');
                }, 2000);
            }, 3000); 
        } else {
            $('.bindPhone').hide();
        }
    });
    $('.bindPhone').on('click', function () {
        location.href = '/member/mobile/';
    })
   
})



/*
// vip满意度问卷
$(function () {
  if (P_UserType == 1 || P_UserType == 2) {
    $('.vip_psq_btn').fadeIn(200);
    var alertType = localStorage.getItem('vippsq');
    var count = 0;
    if (!alertType) {
      var timeval = setInterval(function () {
        count++;
        if (count >= 5) {
          if (!(alertType ? alertType : localStorage.getItem('vippsq'))) {
            localStorage.setItem('vippsq', 1);
            alertType = localStorage.getItem('vippsq');
            $('.vip_psq_box').slideDown('fast');
          }
          clearInterval(timeval);
        }
      }, 1000);
    }
  }
  $('.vip_psq_btn').on('click', function (e) {
    $('.vip_psq_box').slideDown('fast');
    if (!(alertType ? alertType : localStorage.getItem('vippsq'))) {
      localStorage.setItem('vippsq', 1);
      alertType = localStorage.getItem('vippsq');
    }
  });
  $('.vip_psq_close').on('click', function (e) {
    $('.vip_psq_box').slideUp('fast');
  });
}) */


// 意见反馈
$(function() {
    var userName = $('#user-name').data('name') || '';
    var topicId = ''; // 问题id
    var answer = ['', '', '']; // 答案
    var timeOut = null;
    var autoTimeOut = null;
    var getFeedbackQuestion = function(type, callback) {
        // init
        answer = ['', '', ''];
        $('.js-msg-feedback-radio li').removeClass('active');
        $('.js-msg-feedback-box textarea').val('');
        $.ajax({
            url: '/ajax/getFeedbackQuestion/',
            method: 'post',
            dataType: 'json',
            data: {
                type: type || 0,
                url: location.href
            },
            success: function(res) {
                if (res.code == 1) {
                    topicId = res.data.id;
                    $('.js-msg-feedback-box .title').text(res.data.title);
                    $('.js-msg-feedback-box .tips').text(res.data.tips);
                    $('.js-msg-feedback-box .appoint-word').text(res.data.appoint_word);
                    callback && callback(res.data);
                }
            }
        })
    }
    var updateMsgFeedbackTime = function(data, delayTime) {
        var nowTime = new Date().getTime();
        var startTime = data.start_time && new Date(data.start_time).getTime() || 0;
        var endTime = data.end_time && new Date(data.end_time).getTime() || nowTime + 24*3600*1000;
        var msgFeedbackFime = pop_fashion_global.fn.getSto('msg_feedback_time' + data.id) || 0;
        if (nowTime >= startTime && nowTime <= endTime && nowTime > (msgFeedbackFime + 7*24*3600*1000)) {
            clearTimeout(autoTimeOut);
            autoTimeOut = setTimeout(function() {
                if ($('.js-msg-feedback-box').css('display') == 'block') {
                    return;
                }
                // init
                answer = ['', '', ''];
                $('.js-msg-feedback-radio li').removeClass('active');
                $('.js-msg-feedback-box textarea').val('');
                
                topicId = data.id;
                $('.js-msg-feedback-box .title').text(data.title);
                $('.js-msg-feedback-box .tips').text(data.tips);
                $('.js-msg-feedback-box .appoint-word').text(data.appoint_word);
                $('.js-msg-feedback-box').show();

                pop_fashion_global.fn.setSto('msg_feedback_time' + data.id, nowTime);
            }, delayTime || 0);
        }
    }
    getFeedbackQuestion(0, function(data) {
        if (data.id) {
            updateMsgFeedbackTime(data, 2*60*1000);
        }
    });

    // 点击默认
    $('.js-msg-feedback').on('click', function() {
        if ($(".js-all-nav-li").hasClass('fs')) {
            $(".js-all-nav-li").trigger('click');
        }
        getFeedbackQuestion(1, function(data) {
            $('.js-msg-feedback-box').show();
        });
    })
    // 单选
    $('.js-msg-feedback-radio').on('click', 'li', function() {
        answer[0] = Number(this.innerText);
        $(this).addClass('active').siblings().removeClass('active');
    })
    // 文本
    $('.js-msg-feedback-text1').on('input', function() {
        answer[1] = this.value;
    })
    $('.js-msg-feedback-text2').on('input', function() {
        answer[2] = this.value;
    })
    // 提交、取消
    $('.js-msg-feedback-btn-box').on('click', 'button', function() {
        if ($(this).data('type')) {
            if (answer[0]) {
                $.ajax({
                    url: '/ajax/setFeedback/',
                    method: 'post',
                    dataType: 'json',
                    data: {
                        id: topicId,
                        answer: JSON.stringify(answer),
                        username: userName,
                        user_type: P_UserType
                    },
                    success: function(res) {
                        if (res.code == 1) {
                            $('.js-msg-feedback-box').addClass('nobg');
                            $('.js-msg-feedback-box .msg-feedback-con').hide();
                            $('.js-msg-feedback-box .msg-feedback-toast').text('感谢反馈，我们会努力为你提供更优质、更贴心的服务!').show();
                            clearTimeout(timeOut);
                            timeOut = setTimeout(function() {
                                $('.js-msg-feedback-box').hide().addClass('nobg');
                                $('.js-msg-feedback-box .msg-feedback-con').show();
                                $('.js-msg-feedback-box .msg-feedback-toast').hide();
                            }, 2000);
                        }
                    }
                })
            } else {
                $('.js-msg-feedback-box .msg-feedback-toast').text('请先填答第1题!').show();
                clearTimeout(timeOut);
                timeOut = setTimeout(function() {
                    $('.js-msg-feedback-box .msg-feedback-toast').hide();
                }, 2000);
            }
        } else {
            $('.js-msg-feedback-box').hide();
        }
    })

    /* 上传图片 */
    $("#uploadImg").change(function(){
        let fileList = $(this)[0].files;
        if (fileList.length > 0) {
            let file = fileList[0];
            let formData = new FormData();
            formData.append('userfile', file);
            console.log(formData.get("userfile"));
            $.ajax({
                url: '/picmatch/new_upload_pic/',
                method: 'post',
                dataType: 'json',
                processData: false,
                contentType: false,
                data:formData,
                success: function(data) {
                    console.log(data)
                    if (data.code == 0) {
                    
                    }
                }
            })
        }
    })
})


