/**
 * 弹出层js效果
 */
var POPTOOL = POPTOOL || {};
POPTOOL.pop = {};
POPTOOL.pop.replaceUrl = "";

POPTOOL.pop.htmlspecialchars_decode = function (str) {
    str = str.replace(/&amp;/g, '&');
    str = str.replace(/&lt;/g, '<');
    str = str.replace(/&gt;/g, '>');
    str = str.replace(/&quot;/g, "''");
    str = str.replace(/&#039;/g, "'");
    return str;
};

POPTOOL.pop.popLayer = function (url, para, templateid, $container, callback) {
    if (IsPC() == false) {
        window.location.href = url;
        return;
    }
    $.ajax({
        type: "post",
        data: para,
        dataType: "json",
        url: url,
        beforeSend: function () {
            $("#loadingBg").show();
        },
        success: function (data) {
            if (data.length === 0) {
                window.location.href = "/error/";
                return false;
            }
            var pop_layer = $("#pop_layer");
            if ((templateid instanceof Array) && ($container instanceof Array)) {
                $.each(templateid, function (i, v) {
                    if (v == 'J_FabricTemplate') {
                        if (data.res.fabricData != null) {
                            $container[i].html(template(templateid[i], data));
                        } else {
                            $container[i].html('');
                        }
                    } else {
                        $container[i].html(template(templateid[i], data));
                    }
                });
                POPTOOL.pop.extendWithTo1300(data);
                pop_layer.scrollTop(0);
                pop_layer.show();
            } else {
                $container.html(template(templateid, data));
                pop_layer.scrollTop(0);
                $container.show();
            }
            pop_layer.scrollTop(0); // 加强调整

            if ("pushState" in history) {
                history.pushState(null, "", url);
                document.title = POPTOOL.pop.htmlspecialchars_decode(data.title);
            }
            POPTOOL.pop.replaceUrl = url;
            $("html,body").css("overflowY", "hidden");
            $("html,body").css("overflowX", "hidden");
            // 报告详情页引导
            var xiazai = pop_layer.find('.xiazai');
            if (xiazai.length == 0) {
                $('.report_guide').removeClass('guide_xia');
            } else {
                $('.report_guide').addClass('guide_xia');
            }
            if (typeof callback == "function") {
                callback();
            }
            var $img = $('.lazyloadTPL').find('img');
            var $ltimg = $img.filter(':lt(4)');
            $ltimg.each(function () {
                var $self = $(this);
                var src = $self.data('original');
                if (typeof src != 'undefined') {
                    $self.get(0).src = src;
                }
            });
            $img.not(':lt(4)').lazyload({effect: "show", container: '#pop_layer'});
        },
        complete: function () {
            $("#loadingBg").hide();
        }
    });
};
POPTOOL.pop.close = function ($container, backTitle, backUrl) {
    oCommon.hiddenWXshare();
    $container.hide();
    $("html,body").css("overflowY", "auto");
    $("html,body").css("overflowX", "auto");
    if ("pushState" in history) {
        history.replaceState(null, "", backUrl);
        document.title = backTitle;
    }
};

POPTOOL.pop.popUCLayer = function (url, para, templateid, $container, callback) {
    $.ajax({
        type: "post",
        data: para,
        dataType: "json",
        url: url,
        beforeSend: function () {
            $("#loadingBg").show();
        },
        success: function (data) {
            //登陆状态失效
            if (data.code=="5001") {
                window.location.href = data.url;
                return false;
            }
            var pop_layer = $("#pop_layer");
            if ((templateid instanceof Array) && ($container instanceof Array)) {
                $.each(templateid, function (i) {
                    $container[i].html(template(templateid[i], data));
                });
                POPTOOL.pop.extendWithTo1300(data);
                pop_layer.scrollTop(0);
                pop_layer.show();
            } else {
                $container.html(template(templateid, data))
                pop_layer.scrollTop(0);
                $container.show();
            }

            $("html,body").css("overflowY", "hidden");
            $("html,body").css("overflowX", "hidden");
            if (typeof callback == "function") {
                callback();
            }
            var $img = $('.lazyloadTPL').find('img');
            var $ltimg = $img.filter(':lt(4)');
            $ltimg.each(function () {
                var $self = $(this);
                var src = $self.data('original');
                if (typeof src != 'undefined') {
                    $self.get(0).src = src;
                }
            });
            $img.not(':lt(4)').lazyload({effect: "show", container: '#pop_layer'});
        },
        complete: function () {
            $("#loadingBg").hide();
        }

    });

};

POPTOOL.pop.extendWithTo1300 = function (data) {
    var addW1300 = $.trim(template('J_PopReportTemplate', data));
    $("#J_PopReport").removeClass('w_1300');
    if (addW1300) {
        // 报告详情页引导
        $('.report_guide').css({width: '1400px', marginLeft: '-700px'});
        $("#J_PopReport").addClass(addW1300);
        // 小屏时出现横的滚动条 让更多推荐的按钮向上移动15个像素
        if ($(window).width() < 1500) {
            $('#J_LayerDownContainer').css('bottom', '15px');
        }
    } else {
        $('.report_guide').css({width: '1200px', marginLeft: '-600px'});
        $('#J_LayerDownContainer').css('bottom', '0');
    }

};


$(function () {
    var t, id, col;

    var pop_layer = $("#pop_layer");
    var gobackUrl = window.location.href;
    var gobackTitle = document.title;
    var layerTop = new Array();
    var isF5 = pop_layer.data("f5") ? true : false;

    // 点击弹层上的X，关闭弹层
    if (!isF5) {
        function closeLayer() {
            $('html,body').css({overflowY: "auto", overflowX: "auto"});
            $('.layui-layer-btn1').trigger('click');
            closeMoreRec();
            POPTOOL.pop.close(pop_layer, gobackTitle, gobackUrl);
        }

        $("body").on("click", ".pop_close , .poplayer_close", function () {
            closeLayer()
        });
        $('body').on('click', '.pop_alert', function () {
            var layerblack = pop_layer.find('.layerblack')
            var layerblackH = layerblack.height();
            if (layerblackH > 350) {
                layerblack.animate({'height': '0', 'width': '0'}, 300);
            } else {
                closeLayer()
            }
        });
        $("body").on('click', '.pop_alert div', function (e) {
            e.stopPropagation();
        });

        function fixedClose() {
            var screenWidth = $(document.body).width();
            var reportClose = $(".pop_close_report");
            var colseTop = parseInt(pop_layer.scrollTop());
            if ($('.w_1300').length) {
                if (screenWidth <= 1400) {
                    reportClose.css({'position': 'fixed', 'right': '30px', 'top': 60 - colseTop});
                } else {
                    reportClose.css({'position': 'absolute', 'right': '10px', 'top': '10px'});
                }
            }
        }

        pop_layer.scroll(fixedClose);
        $(window).resize(fixedClose);
    }

    // 点击浏览器回退按钮，关闭弹层
    (function () {
        if ("pushState" in history) {
            // 浏览器后退按钮 触发关闭按钮点击事件
            window.addEventListener("popstate", function () {
                pop_layer.find('.pop_close').trigger('click');
            });
        }
    }());

    pop_layer.on("mouseenter mouseleave", ".chakan", function (e) {
        if (e.type == "mouseenter") {
            if ($(this).hasClass('noclick')) {
                return false;
            }
            $(".content_introduction").stop(true, true).slideDown(200);
        } else {
            $(".content_introduction").stop(true, true).slideUp(200);
        }
    });

    // ========= 列表点击li出现弹出层，显示详情 ========
    var liHtml = $(".rightc_main").find("ul").find("li");
    var templateIdArr = [
        'J_LeftRightBtnTemplate',
        'J_ReportTitleTemplate',
        'J_ReportFuncTemplate',
        'J_ReportIntroduceTemplate',
        'J_BannerpicTemplate',
        'J_LayerMainTemplate',
        'J_LayerRightTemplate',
        'J_LayerDownTemplate',
        'J_FabricTemplate'
    ];
    var containerArr = [];
    $.each(templateIdArr, function (i, v) {
        containerArr.push($("#" + v.replace('Template', 'Container')));
    });


    // 弹层方法
    /*function popUp(obj) {
        var self = $(obj);
        var link = $("#link");
        var para = link.data("param");
        var urlPrefix = '/details/report/';//link.data("urlprefix");
        var colPid = link.data("col");
        col = self.data("col");
        var table = self.data("t");
        t = table;
        id = self.data("id");
        var url = urlPrefix + "t_" + table + "-id_" + id + "-col_" + col + "/";
        var total = self.data("total");
        var index = self.data("index");
        var isuc = link.data("isuc");
		var iIsAuthorized = link.data("isauthorized");//是否权威分析
        var search = link.data('search').replace('?key=', '');
        var user_info = $.cookie('user_info');
        var mark = self.data("mark"); //试读、热门等
        var realUserType = self.data("utype"); //用户实际的类型
        //先判断权限，cookie中没有值未登录，跳到/details/style/(display中间页)
        if (user_info == null || (realUserType==4 && mark!=2)) {
            window.open(url);
        } else {
            if (isuc) {
                return false;
            }
            viewCount(table, id, col);
            POPTOOL.pop.popLayer(url, {
                total: total,
                index: index,
                p: para,
				iIsAuthorized: iIsAuthorized,
                colPid: colPid,
                col: col,
                key: search
            }, templateIdArr, containerArr, hideToggleBtn);
        }
    }

    liHtml.on("click", "a:first, .report_title", function (e) {
        var e = e || window.event;
        e.preventDefault();
        qqflag = false;
        popUp(this);
    });*/

    //弹出层左右切换
    pop_layer.on("click", ".rightBtn,.leftBtn", function () {
        oCommon.hiddenWXshare();
        closeMoreRec();
        if ($(this).data("isuc") > 0) {
            //个人中心
            popUCUp(this);
        } else {
            popUp(this);
        }
    });

    // 收藏报告
    pop_layer.on("click", "#J_Collect", function () {
        //iColumnId-sTableName-iPriId-iType
        var self = $(this);
        var iscollected = self.data('iscollected');
        if (iscollected) {
            var status = 0;
        } else {
            var status = 1;
        }
        if (self.data('mainaccount') == 1) {
            oCommon.noPower(-2);
            return;
        }
        var colpara = self.data('colpara');
        var params = colpara.split('-');
        var callback = function () {
            if (status == 0) {
                self.removeClass('cur').find('span').eq(0).html('收藏');
                self.data('iscollected', 0);
            } else {
                self.addClass('cur').find('span').eq(0).html('已收藏');
                self.data('iscollected', 1);
            }
            if (isF5) {
                window.location.reload();
            }
        };
        oCommon.collect(this, params[0], params[1], params[2], params[3], callback, status);
    });

    // 下载pdf
    pop_layer.on("click", "#J_DownPDF", function () {
        if (!oCommon.downloadPrivilege()) {
            return;
        }
        var self = $(this);
        var para = $("#J_Collect").data('colpara').split("-");
        var col = para[0];
        var t = para[1];
        var id = para[2];
        var path = self.data("path");
        down(path, t, id, col);
    });


    // ========= 点击展开收起 ========


    // 热点圈图
    pop_layer.on("mouseenter mouseleave", '.position', function (e) {
        if (e.type == 'mouseenter') {
            $(this).addClass('on');
            showPositionTxt(this);

        } else {
            $(this).removeClass("on");
        }
    });

    function showPositionTxt(target) {
        var positionEle = $(target),
            positionTxt = $(target).find('.position-txt'),
            isExist = positionTxt.attr('class');
        if (isExist) {
            var borderWidth = 2,
                h = positionTxt.outerHeight() + borderWidth,
                w = positionTxt.outerWidth() + borderWidth,
                positionW = positionEle.outerWidth() - borderWidth,
                positionH = positionEle.outerHeight() - borderWidth,
                top = positionEle.offset().top,
                left = positionEle.offset().left,
                right = left + positionW,
                bottom = top + positionH,
                dieTop = $('.img').offset().top,
                dieLeft = $('.img').offset().left,
                dieRight = dieLeft + $('.img').outerWidth(),
                dieBottom = dieTop + $('.img').outerHeight();

            /*
                以图描绘几种边界情况
                 _________________________________
                |       |    _________  |         |
                |_______|   |         | |_________|
                |           |_________|           |
                |            _________            |
                |_______    |         |  _________|
                |       |   |_________| |         |
                |_______|_______________|_________|
            */
            if ((right + w) > dieRight && left < (dieLeft + w) && (bottom + h) > dieBottom && top < (dieTop + h)) positionTxt.css({"margin-top": -borderWidth, "margin-left": -borderWidth});
            else if ((right + w) < dieRight && left < (dieLeft + w) && top < (dieTop + h)) positionTxt.css({"margin-left": positionW - 1, "margin-top": -borderWidth});
            else if ((right + w) > dieRight && left > (dieLeft + w) && top < (dieTop + h)) positionTxt.css({"margin-left": -w + 1, "margin-top": -borderWidth});
            else if ((right + w) < dieRight && left < (dieLeft + w) && (bottom + h) > dieBottom) positionTxt.css({"margin-left": positionW - 1, "margin-top": -h + positionH + borderWidth});
            else if ((right + w) > dieRight && left > (dieLeft + w) && (bottom + h) > dieBottom) positionTxt.css({"margin-left": -w + 1, "margin-top": -h + positionH + borderWidth});
            else if ((right + w) > dieRight && left < (dieLeft + w) && top < (dieTop + h)) positionTxt.css({"margin-top": positionH - 1, "margin-left": -borderWidth});
            else if ((right + w) > dieRight && left < (dieLeft + w) && top > (dieTop + h)) positionTxt.css({"margin-top": -h + 1, "margin-left": -borderWidth});
            else if ((right + w) > dieRight && (bottom + h) < dieBottom && top > (dieTop + h)) positionTxt.css({"margin-top": -borderWidth, "margin-left": -w + 1});
            else positionTxt.css({"margin-top": -borderWidth, "margin-left": positionW - 1});
        }
    }

    // ========= 更多推荐的点击事件 ========
    var selcon = pop_layer.find(".layer_dwid");
    pop_layer.on("click", ".btnbox", function () {
        var self = $(this);
        var layerblack = pop_layer.find(".layerblack");
        if ($(window).width() >= 1500) {
            layerblack.animate({'height': '706px', 'width': '100%'}, 300);
            ajaxGetMoreRec();
        } else {
            layerblack.animate({'height': '352px', 'width': '100%'}, 1000);
            ajaxGetMoreRec();
        }
    });
    pop_layer.on("click", ".layer_dwid_close", function () {
        var layerblack = pop_layer.find(".layerblack");
        layerblack.animate({'height': '0', 'width': '0'}, 300);
    });

    function hideToggleBtn() {
        var $abox = pop_layer.find("#J_ReportFuncContainer a");
        var left = 0;
        if ($abox.length == 1) {
            left = $abox.eq(0).outerWidth();
        } else {
            left = $abox.eq(0).outerWidth() + $abox.eq(1).outerWidth();
        }
        pop_layer.find(".share_btn").css("left", left + 20);
        var adiv = pop_layer.find(".pop_report .indexdiv");
        for (var i = 0, len = adiv.length; i < len; i++) {
            layerTop[i] = adiv.eq(i).offset().top;
        }
    }

    hideToggleBtn();

    // 获取更多推荐
    function ajaxGetMoreRec() {
        var J_MoreRec = $('#J_MoreRec');

        t = t ? t : J_MoreRec.data('t');
        id = id ? id : J_MoreRec.data('id');
        col = col ? col : J_MoreRec.data('col');


        $.ajax({
            type: 'post',
            url: '/ajax/getmorerec/',
            data: {t: t, id: id, col: col, time: Math.random()},
            dataType: 'json',
            async: true,
            beforeSend: function () {
                J_MoreRec.html($("#J_MoreRecLodingTemplate").html());
            },
            success: function (data) {
                J_MoreRec.html(template('J_MoreRecTemplate', data));
            }
        });
    }

    //关闭详情时对更多推荐等做初始化（还原为最初的隐藏状态）
    function closeMoreRec() {
        $('#J_MoreRec').html('');
        var layerblack = pop_layer.find(".layerblack");
        var moreRec = pop_layer.find(".moreRec");
        layerblack.animate({height: 0}, 300);
        moreRec.removeClass("down");
    }

    // ========= 分享 ========
    pop_layer.on("mouseenter mouseleave", ".share_btn", function (e) {
        if (e.type == "mouseenter") {
            $(this).stop().animate({width: "280px", backgroundColor: "#fff"}, 300);
        } else {
            $(this).stop().animate({width: "40px", backgroundColor: "#ccc"}, 300);
        }
    });

    /*报告详情页内容切换*/
    //报告文章导航按钮效果

    // pop_layer.on("click", ".pop_report .leftnavfix li", function () {
    //     var sel = $(this);
    //     var index = sel.index();
    //     var adiv = pop_layer.find(".pop_report").find(".indexdiv");
    //     if (isF5) {
    //         var adivhei = adiv.eq(index).offset().top;
    //         $("html,body").animate({scrollTop: adivhei}, 300);
    //     } else {            
    //         var adivhei = layerTop[index] - pop_layer.offset().top;
    //         pop_layer.animate({scrollTop: adivhei}, 300);
    //     }
    // });
    // 面料推荐
    // var fabhei;
    // pop_layer.on("click" ,'.fabric_btn', function(){ 
    //     var popfabric = pop_layer.find(".pop_report_fabric");
    //     if(isF5) {
    //        fabhei = popfabric.offset().top;
    //         $("html,body").animate({scrollTop: fabhei}, 500);
    //     } else {
    //         //fabhei=popfabric.offset().top - pop_layer.offset().top;
    //     	fabhei=document.getElementById("J_FabricContainer").offsetTop;
    //         pop_layer.animate({scrollTop: fabhei}, 500);
    //     }
    // });

    // 收起展开更多款式
    pop_layer.on('click', '.more_style_close', function () {
        $self = $(this);
        var parent = $self.parent();
        var ullist = parent.find('.ullist');
        ullist.slideUp(300);
        $self.hide();
        parent.find('.more_style_open').show();
    });
    pop_layer.on('click', '.more_style_open', function () {
        $self = $(this);
        var parent = $self.parent();
        var ullist = parent.find('.ullist');
        ullist.slideDown(300);
        $self.hide();
        parent.find('.more_style_close').show();
    });


    if (isF5) {
        $(window).scroll(function () {
            var fh = $(".layerLeft").height() + 100;
            var adiv = $(".pop_report .indexdiv");
            var pmidth = $(window).width();
            var cssleft = Math.floor((pmidth - 1100) / 2);
            if ($(window).scrollTop() >= fh) {
                pop_layer.find(".pop_report .leftnavfix").addClass("fix").css("left", cssleft - 53);
            } else {
                pop_layer.find(".pop_report .leftnavfix").removeClass("fix").css("left", "-53px");
            }

            for (var i = 0; i < adiv.length; i++) {
                var adivhei = adiv.eq(i).offset().top - 10;
                if ($(window).scrollTop() >= adivhei) {
                    pop_layer.find(".pop_report .leftnavfix li").eq(i).addClass("redbg").siblings("li").removeClass("redbg");
                } else {
                    pop_layer.find(".pop_report .leftnavfix li").eq(i).removeClass("redbg");
                }
            }
            if ($(window).scrollTop() >= $(document).height() - $(window).height()) {
                $(".layer_down").fadeOut(200);
            } else {
                $(".layer_down").fadeIn(200);
            }
        });
    } else {
        pop_layer.scroll(function () {
            var fh = pop_layer.find('.layerLeft').height() + 100;
            var adiv = pop_layer.find(".pop_report .indexdiv");
            var pmidth = $(window).width();
            var cssleft = Math.floor((pmidth - 1100) / 2);
            if (pop_layer.scrollTop() >= fh) {
                pop_layer.find(".pop_report .leftnavfix").addClass("fix").css("left", cssleft - 61);
            } else {
                pop_layer.find(".pop_report .leftnavfix").removeClass("fix").css("left", "-53px");
            }

            for (var i = 0; i < adiv.length; i++) {
                var adivhei = layerTop[i] - pop_layer.offset().top - 100;
                if (pop_layer.scrollTop() >= adivhei) {
                    pop_layer.find(".pop_report .leftnavfix li").eq(i).addClass("redbg").siblings("li").removeClass("redbg");
                } else {
                    pop_layer.find(".pop_report .leftnavfix li").eq(i).removeClass("redbg");
                }
            }
            var scrolltop = $(this).scrollTop();
            var poplayerup = pop_layer.find('.poplayer_up');
            var poplayerclose = pop_layer.find('.poplayer_close');
            if (scrolltop > 40) {
                poplayerup.fadeIn(200);
                poplayerclose.fadeIn(200);
            } else {
                poplayerup.fadeOut(200);
                poplayerclose.fadeOut(200);
            }
        });

        pop_layer.on('click', '.poplayer_up', function () {
            pop_layer.animate({scrollTop: 0}, 500);
        });
    }

    // 子栏目鼠标经过切换标题
    pop_layer.on("mouseenter mouseleave", ".pop_report .leftnavfix li", function (e) {
        if (e.type == "mouseenter") {
            $(this).find('span.spanHover').css("display", "none").end().find('span.spanHoverTitle').css("display", "block");
        } else {
            $(this).find('span.spanHover').css("display", "block").end().find('span.spanHoverTitle').css("display", "none");
        }
    });

    if (isF5) {
        // F5 详情也统计浏览量
        if ($("#J_Collect").length) {
            var para = $("#J_Collect").data('colpara').split("-");
            var col = para[0];
            var t = para[1];
            var id = para[2];
            viewCount(t, id, col);
        }
    }
    /*------------------------------个人中心----------------------------------*/
    var liUCHtml = $(".con_main").find("ul").find("li");

    // 弹层方法
    function popUCUp(obj) {
        var self = $(obj);
        var link = $("#link");
        var type = link.data("type");
        var urlPrefix = '/details/report/';//link.data("urlprefix");
        var total = self.data("total");
        id = self.data("id");
        t = self.data("t");
        col = self.data("col");
        var isuc = link.data("isuc");
        var index = self.data("index");
        var url = urlPrefix + "t_" + t + "-id_" + id + "-col_" + col + "/";
        POPTOOL.pop.popUCLayer(url, {
            total: total,
            index: index,
            type: type,
            isuc: isuc
        }, templateIdArr, containerArr, hideToggleBtn);
    }

    liUCHtml.on("click", "a.img_click", function () {
        qqflag = false;
        popUCUp(this);
        return false;
    });
    // 初始化弹出层大图图片大小
    var oBigImg = $("#bigImages");
    if (oBigImg.length) {
        var oImg = oBigImg[0];
        var wheelScroll = function (ev) {
            var ev = ev || window.event;
            var zoom = parseInt(oImg.style.zoom, 10) || 100;
            var image = new Image();
            image.src = oImg.src;
            var imgW = oImg.width;
            if (ev.wheelDelta) {
                if (ev.wheelDelta > 0) {
                    $(oImg).addClass("bzoom").removeClass('szoom');
                }
                else {
                    $(oImg).addClass("szoom").removeClass('bzoom');
                }
            }
            else {
                if (-ev.detail > 0) {
                    $(oImg).addClass("bzoom").removeClass('szoom');
                }
                else {
                    $(oImg).addClass("szoom").removeClass('bzoom');
                }
            }

            zoom += ev.wheelDelta ? (ev.wheelDelta / 12) : (-ev.detail);
            imgW = imgW * (zoom / 100);
            if (zoom > 50) {
                oImg.style.cssText = '-moz-transform:scale(' + zoom / 100 + ');-moz-transform-origin: center top 0px;';
                oImg.style.zoom = zoom + "%"; //ff，opera不支持zoom发大缩小
                if (ev.preventDefault) {
                    ev.preventDefault();
                }
                ev.returnValue = false;
            }
            return false;
        };
        if (oImg.addEventListener) {
            /** DOMMouseScroll is for mozilla. */
            oImg.addEventListener('DOMMouseScroll', wheelScroll, false);
        }
        oImg.onmousewheel = wheelScroll;

        oImg.onmousedown = function () {
            if (oBigImg.hasClass("bzoom")) {
                oBigImg.addClass("dragIcon").removeClass('bzoom');
            }
            else if (oBigImg.hasClass("szoom")) {
                oBigImg.addClass("dragIcon").removeClass('szoom');
            }
            oImg.onmouseup = function () {
                oBigImg.addClass("szoom").removeClass('dragIcon');
            }
        };
        $("#draggable").draggable();
    }

    // 点击热点图
    var isPop_layer = pop_layer.length;
    pop_layer.on('click', '.material', function () {
        var bigImages = $("#bigImages");
        var src = $('.material').filter('.on').data('src');
        var aisrc = $('.material').filter('.on').data('ai');
        if (src == undefined) {
            return;
        }
        $('.layer_bgBlack').show();
        $('.bigImg').show().find('.layerBigPic').attr('src', src);
        $('.bigImg').find('.download_btn a.dl_jpg').attr('href', '/download/dlsingle/?dl_link=' + encodeURI(src));
        if (aisrc) {
            $('.bigImg').find('.download_btn a.dl_ai').show().attr('href', '/download/dlsingle/?dl_link=' + encodeURI(aisrc));
        } else {
            $('.bigImg').find('.download_btn a.dl_ai').hide();
        }
        oCommon.SetImg(bigImages[0], 658, 1038);
        bigImages.addClass("szoom");
        intBigImg();
    });

    // 获取图片原始大小
    function natural(img) {
        var image = new Image();
        image.src = bigImages.src;
        var naturalWid = image.width;
        var naturalHei = image.height;
        bigImages.width = naturalWid;
        bigImages.height = naturalHei;
    }

    // 图片初始大小
    pop_layer.on('click', '.original_size', function () {
        var bigImages = $("#bigImages");
        natural(bigImages);
        intBigImg()
    });
    // 重置图片
    pop_layer.on('click', '.reset_size', function () {
        var bigImages = $("#bigImages");
        oCommon.SetImg(bigImages[0], 658, 1038);
        intBigImg()
    });

    function intBigImg() {
        var bigImgBox = $("#draggable");
        bigImgBox.css({"left": "50%", "margin-left": "-600px", "top": 0});
        bigImgBox.find("img").css({'-moz-transform': 'scale(1)', "zoom": "100%"});
    }

    /*关闭大图弹层*/
    pop_layer.on('click', '.layerClose', function () {
        $('.bigImg').find('.layerBigPic').attr('src', '');
        $('.layer_bgBlack').hide();
        $('.bigImg').hide();
    });

    /*------------------------------个人中心----------------------------------*/
    $(document).keydown(function (event) {
        var event = event ? event : window.event;
        // left
        if (event && event.keyCode == 37) {
            $(".pop_layer_page .leftBtn").trigger("click");
        }
        // right
        if (event && event.keyCode == 39) {
            $(".pop_layer_page .rightBtn").trigger("click");
        }
        // esc
        if (event && event.keyCode == 27) {
            $(".pop_layer_page .pop_close").trigger("click");
        }
    });

    //懒加载
    $img = $(".lazyload img");
    var $ltimg = $img.filter(':lt(4)');
    $ltimg.each(function () {
        var $self = $(this);
        var src = $self.data('original');
        if (typeof src != 'undefined') {
            $self.get(0).src = src;
        }
    });
    $img.not(':lt(4)').lazyload({effect: "show"});
});

// 下载
function down(path, t, id, col) {
    if (typeof path == 'undefined') {
        return;
    }
    var fileType = path.split('.');
    var fileType = fileType.pop();	//下载文件格式    
    var params = {table: t, id: id, iColumnId: col, action: 'DownCount', fileType: fileType};
    $.ajax({
        type: "get",
        url: "/ajax/setcount/" + Math.random(),
        data: params,
        async: false
    });
    oCommon.download(path);
}

// 浏览量统计
function viewCount(t, id, col) {
    var params = {table: t, id: id, iColumnId: col, action: 'ViewCount'};
    $.ajax({
        type: "get",
        url: "/ajax/setCount/" + Math.random(),
        data: params,
        async: false
    })
}

    

