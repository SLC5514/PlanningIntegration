$(function() {
    var scrollBarWidth = getScrollBarWidth();
    var viewport = $(window).width() > (1500 - scrollBarWidth) ? 1 : 0;
    var rmstyleW = viewport ? 234 : 215;
    var trendRmstyleW = viewport ? 136 : 116;
    var def = {
        viewport: viewport, // 大屏/小屏
        params: $('.params').data('params'),
        maskEl: $('.js-mask'), // 蒙层
        // 色彩组合分析================================
        analysisLoading: false, // 加载状态
        analysiSeasonId: $('.js-analysis-season .active').data().id, // 季节id
        analysiCategoryId: '', // 单品id
        analysiColorId: '', // 颜色id
        analysiLastclick: '', // 最后点击
        // 推荐款式图=================
        rmstyleW: rmstyleW, // 单个宽度
        rmstylePageOW: viewport ? rmstyleW * 6 : rmstyleW * 5, // 一页宽度
        rmstyleLen: 0, // 总个数
        rmstylePages: 1, // 总页数
        // 查看图片===================
        action_down: false, // 下载状态
        getImgLoading: false, // 大图获取状态
        move_y: 0, // 滚动条移动距离
        is_move_pic: false, // 是否移动中
        control_top: 74, // 放大镜top距离
        min_radio: 0.5, // 图片放大最小倍数
        max_radio: 3, // 图片放大最大倍数
        imgDefW: viewport ? 548 : 400, // 图片默认宽548/640/400
        // 色彩走势====================================
        trendSubShow: false, // 二级显示状态
        trendLoading: false, // 加载状态
        trendSeasonId: $('.js-trend-season:eq(0) .active').text(), // 季节id(字符)
        trendColorId: '', // 颜色id
        // 走势二级款式===================
        trendStyleLoading: false, // 加载状态
        trendRmstyleW: trendRmstyleW, // 单个宽度
        trendRmstylePageOW: trendRmstyleW * 4, // 一页宽度
        trendRmstyleLen: [0, 0, 0], // 总个数
        trendRmstylePages: [1, 1, 1], // 总页数
        // 上升下降色===================================
        udColorLoading: false, // 加载状态
        udColorSubLoading: false, // 二级加载状态
        udColorSeasonId: $('.js-udcolor-season .active').text(), // 季节id(字符)
        udColorId: '', // 颜色id
        // 推荐色列表==================================
        remColorLoading: false, // 加载状态
        remColorSeasonId: '', // 季节id(字符)
        remColorId: '', // 颜色id
        remColorStyleLen: 0, // 总个数
        remColorPvShow: false, // 大图显示状态
        // 图表========================================
        chartAnalysi: {
            total: 0, // 款式总数
            percent: {colour: 0, no_colour: 0} // 有色无色比
        },
        chartUdColor: {
            total: 0 // 总数
        },
        // 推荐色======================================
        reColorChartOneW: 0, // 一个卡片的最大宽度
    };

    // 切换性别/时装周==================================
    $('.js-nav-sex').on('click', 'li', function() {
        $(this)
            .addClass('active')
            .siblings()
            .removeClass('active');
    });
    $('.js-nav-week').on('click', 'li', function() {
        $('.js-nav-week-name').text($(this).text());
        $(this)
            .addClass('active')
            .siblings()
            .removeClass('active');
    });

    // 季节========================================
    // 色彩组合分析季节初始化
    var seasonBox = $('.js-analysis-season');
    var seasonItems = seasonBox.find('li');
    var seasonPageEl = $('.js-season-pager');
    var seasonLen = seasonItems.length;
    var seasonPages = Math.ceil(seasonLen/5);
    var seasonW = 110;
    var seasonPageOW = seasonW * 5;
    var seasonPage = 1;
    if (seasonLen <= 5) {
        seasonBox.width(seasonLen * seasonW);
        seasonPageEl.find('.r').addClass('disabled');
    } else {
        seasonBox.width(seasonLen * seasonW);
    }
    // 切页
    seasonPageEl.on('click', 'i', function() {
        var target = $(this);
        if (target.hasClass('disabled')) {
            return;
        }
        var left = 0;
        if (target.hasClass('l')) {
            seasonPage--;
            if (seasonPage <= 1) {
                seasonPage = 1;
                seasonPageEl.find('.l').addClass('disabled');
                seasonPageEl.find('.r').removeClass('disabled');
            } else {
                seasonPageEl.find('.r').removeClass('disabled');
            }
            left = (seasonPage - 1) * seasonPageOW;
            seasonBox.css('margin-left', -left + 'px');
        } else if (target.hasClass('r')) {
            seasonPage++;
            if (seasonPage >= seasonPages) {
                seasonPage = seasonPages;
                left = (seasonPage - 1) * seasonPageOW - (5 - seasonLen%5)*seasonW;
                seasonPageEl.find('.r').addClass('disabled');
                seasonPageEl.find('.l').removeClass('disabled');
            } else {
                seasonPageEl.find('.l').removeClass('disabled');
                left = (seasonPage - 1) * seasonPageOW;
            }
            seasonBox.css('margin-left', -left + 'px');
        }
    });
    // 色彩组合分析 切换季节
    seasonBox.on('click', 'li', function() {
        if (P_UserType == 5) { return; }
        $(this).addClass('active').siblings().removeClass('active');
        def.analysiSeasonId = $(this).data().id;
        colorAnalysisFn(def.analysiSeasonId, def.analysiCategoryId, def.analysiColorId, def.analysiLastclick);
    });
    // 色彩走势 切换季节
    var trendSeasonEl = $('.js-trend-season');
    trendSeasonEl.on('click', 'li', function() {
        if (P_UserType == 5 || P_UserType == 4) { return; }
        var idx = $(this).index();
        $(trendSeasonEl[0]).children().eq(idx).addClass('active').siblings().removeClass('active');
        $(trendSeasonEl[1]).children().eq(idx).addClass('active').siblings().removeClass('active');
        def.trendSeasonId = $(this).text();
        colorTrendFn(def.trendSeasonId, def.trendColorId);
    });
    // 上升下降色 切换季节
    var udColorSeasonEl = $('.js-udcolor-season');
    udColorSeasonEl.on('click', 'li', function() {
        if (P_UserType == 5 || P_UserType == 4) { return; }
        $(this).addClass('active').siblings().removeClass('active');
        def.udColorSeasonId = $(this).text();
        if (def.udColorId) {
            uporDownColorSubFn(def.udColorSeasonId, def.udColorId);
        } else {
            uporDownColorFn(def.udColorSeasonId);
        }
    });

    // 推荐色==========================================
    var reColorChartIdx = 0;
    var reColorChartDefW = def.viewport ? 64 : 48;
    var reColorChartEl = $('.js-recommend-color-chart');
    var reColorChartChildEl = $('.js-recommend-color-chart').children();
    var reColorChartChildLen = reColorChartChildEl.length;
    initReColorChart();
    reColorChartChildEl.on('click', function() {
        if (def.remColorLoading) { return; }
        var self = $(this);
        reColorChartIdx = self.index();
        def.remColorSeasonId = self.data().season;
        def.remColorId = self.data().color;
        self.width(def.reColorChartOneW).addClass('active').siblings().width('').removeClass('active');
        recommendColorListFn(def.remColorSeasonId, def.remColorId);
    });
    // 推荐色初始化
    function initReColorChart() {
        var reColorChartW = reColorChartEl.width();
        var itemEl = reColorChartChildEl.eq(reColorChartIdx);
        def.reColorChartOneW = reColorChartW - (reColorChartChildLen - 1) * reColorChartDefW;
        itemEl.width(def.reColorChartOneW).siblings().width('');
        reColorChartEl.removeClass('childhide');
        def.remColorSeasonId = itemEl.data().season;
        def.remColorId = itemEl.data().color;
    }

    // 引导===========================================
    var guideIdx = 1;
    var guideEl = $('.js-guide');
    var guidePageEl = $('.js-guide-page');
    var guideBarEl = $('.js-guide-bar');
    var isFirstGuide = localStorage.getItem('guide-first');
    if ((P_UserType == 1 || P_UserType == 2) && !isFirstGuide) {
        localStorage.setItem('guide-first', 1);
        $('html,body').addClass('overflow-layer');
        def.maskEl.show();
        $('.js-guide-content').show();
    }
    // 打开引导
    guideEl.on('click', function() {
        $('html,body').addClass('overflow-layer');
        def.maskEl.show();
        $('.js-guide-content').show();
    });
    // 关闭
    $('.js-guide-close, .js-guide-btn').on('click', function() {
        $('html,body').removeClass('overflow-layer');
        def.maskEl.hide();
        $('.js-guide-content').hide();
        $('.js-guide-btn').hide();
        var oldGuideIdx = guideIdx;
        guideIdx = 1;
        $('.js-guide-pager i').siblings('span').text(guideIdx + '/5');
        guideBarEl.width((guideIdx / 5) * 100 + '%');
        guidePageEl.removeClass('page' + oldGuideIdx).addClass('page' + guideIdx);
    });
    // 切页
    $('.js-guide-pager').on('click', 'i', function() {
        var target = $(this);
        if (target.hasClass('disabled')) {
            return;
        }
        if (target.hasClass('l')) {
            guideIdx--;
            if (guideIdx < 1) {
                guideIdx = 1;
            }
            $(this).siblings('span').text(guideIdx + '/5');
            guideBarEl.width((guideIdx / 5) * 100 + '%');
            guidePageEl.removeClass('page' + (guideIdx + 1)).addClass('page' + guideIdx);
        } else if (target.hasClass('r')) {
            guideIdx++;
            if (guideIdx > 5) {
                guideIdx = 5;
            }
            $(this).siblings('span').text(guideIdx + '/5');
            guideBarEl.width((guideIdx / 5) * 100 + '%');
            guidePageEl.removeClass('page' + (guideIdx - 1)).addClass('page' + guideIdx);
        }
        if (guideIdx == 5) {
            $('.js-guide-btn').show();
        } else {
            $('.js-guide-btn').hide();
        }
    });

    var pop_layer = $('#pop_layer');
    var styleIdx = 0; // 查看款式位置
    // 推荐款式图======================================
    var rmstyleSub = $('.js-recommend-item');
    var rmstylePageEl = $('.js-recommend-pager');
    var rmstylePage = 1; // 当前页码
    // 列表 切页
    rmstylePageEl.on('click', 'i', function() {
        var target = $(this);
        if (target.hasClass('disabled')) {
            return;
        }
        var left = 0;
        if (target.hasClass('l')) {
            rmstylePage--;
            if (rmstylePage <= 1) {
                rmstylePage = 1;
                rmstylePageEl.find('.l').addClass('disabled');
                rmstylePageEl.find('.r').removeClass('disabled');
            } else {
                rmstylePageEl.find('.r').removeClass('disabled');
            }
        } else if (target.hasClass('r')) {
            rmstylePage++;
            if (rmstylePage >= def.rmstylePages) {
                rmstylePage = def.rmstylePages;
                rmstylePageEl.find('.r').addClass('disabled');
                rmstylePageEl.find('.l').removeClass('disabled');
            } else {
                rmstylePageEl.find('.l').removeClass('disabled');
            }
        }
        left = (rmstylePage - 1) * def.rmstylePageOW;
        rmstyleSub.css('margin-left', -left + 'px');
    });
    // 查看图片
    rmstyleSub.on('click', 'li', function() {
        if (def.analysisLoading || def.getImgLoading) { return; }
        var self = $(this);
        styleIdx = self.index();
        if (def.rmstyleLen <= 1) {
            $('.js-viewimg-pager i').addClass('disabled');
        } else {
            $('.js-viewimg-pager i').removeClass('disabled');
        }
        getImgDetailFn(self.find('img').data());
    });

    // 色彩走势二级====================================
    var trendAside = $('.js-trend-aside');
    var trendRmstyleBox = $('.js-style-list');
    var trendRmmstylePageEl = trendRmstyleBox.find('.pager');
    var trendRmstylePage = [1, 1, 1]; // 当前页码
    var trendViewimgIdx = 0; // 展开大图所属列表位置
    // 列表 切页
    trendRmmstylePageEl.on('click', 'i', function() {
        var target = $(this);
        if (target.hasClass('disabled')) {
            return;
        }
        var left = 0;
        var idx = target.parents('.js-style-list').index();
        var box = $(trendRmstyleBox[idx]);
        var pagerEl = $(trendRmmstylePageEl[idx]);
        var isMin = idx < 2;
        if (target.hasClass('l')) {
            trendRmstylePage[idx]--;
            if (trendRmstylePage[idx] <= 1) {
                trendRmstylePage[idx] = 1;
                pagerEl.find('.l').addClass('disabled');
                pagerEl.find('.r').removeClass('disabled');
            } else {
                pagerEl.find('.r').removeClass('disabled');
            }
        } else if (target.hasClass('r')) {
            trendRmstylePage[idx]++;
            if (trendRmstylePage[idx] >= def.trendRmstylePages[idx]) {
                trendRmstylePage[idx] = def.trendRmstylePages[idx];
                pagerEl.find('.r').addClass('disabled');
                pagerEl.find('.l').removeClass('disabled');
            } else {
                pagerEl.find('.l').removeClass('disabled');
            }
        }
        left = (trendRmstylePage[idx] - 1) * def.trendRmstylePageOW;
        if (isMin) {
            box.find('.recommend-item').css('margin-left', -left + 'px');
        } else {
            box.find('.recommend-list').css('margin-left', -left + 'px');
        }
    });
    // 展开收起
    $('.js-expand').on('click', function() {
        trendAside.toggleClass('active')
    })
    // 关闭
    $('.js-trend-close').on('click', function() {
        $('html,body').removeClass('overflow-layer');
        trendAside.removeClass('active')
        $('.js-color-trend').hide();
        def.trendSubShow = false;
    })
    // 查看图片
    trendRmstyleBox.on('click', '.recommend-item li', function() {
        if (def.trendStyleLoading || def.getImgLoading) { return; }
        var self = $(this);
        var idx = self.parents('.js-style-list').index();
        trendViewimgIdx = idx;
        if (idx < 2) {
            styleIdx = self.index();
        } else {
            styleIdx = ((trendRmstylePage[idx] - 1) * 8) + self.index();
        }
        if (def.trendRmstyleLen[idx] <= 1) {
            $('.js-viewimg-pager i').addClass('disabled');
        } else {
            $('.js-viewimg-pager i').removeClass('disabled');
        }
        getImgDetailFn(self.find('img').data());
    });

    // 推荐色款式========================================
    var remColorList = $('.js-remcolor-list');
    // 查看图片
    remColorList.on('click', 'li', function() {
        if (def.remColorLoading || def.getImgLoading) { return; }
        var self = $(this);
        styleIdx = self.index();
        def.remColorPvShow = true;
        if (def.remColorStyleLen <= 1) {
            $('.js-viewimg-pager i').addClass('disabled');
        } else {
            $('.js-viewimg-pager i').removeClass('disabled');
        }
        getImgDetailFn(self.find('img').data());
    });

    // 大图==============================================
    // 图片初始大小--原图
    pop_layer.on('click', '.js-original', function() {
        if ($('#draggable img').is(':hidden')) {
            return;
        }
        $('.js-reset').removeClass('on-reset');
        natural($('img.js-bigbox'));
        intBigImg();
    });
    // 重置图片--复位
    pop_layer.on('click', '.js-reset', function() {
        if ($('#draggable img').is(':hidden')) {
            return;
        }
        if (!$(this).hasClass('on-reset')) {
            $(this).addClass('on-reset');
            imgSize($('#draggable img'));
            intBigImg();
        }
    });
    //放大缩小滚动条
    pop_layer.on('mousedown', '.js-control-l i', function(e) {
        if ($('#draggable img').is(':hidden')) {
            return;
        }
        var ev = e || event;
        // pop_fashion_global.fn.stopBubble(ev);
        if (ev && ev.stopPropagation) {
            ev.stopPropagation();
        } else {
            window.event.cancelBubble = true;
        }
        def.move_y = ev.clientY;
        def.is_move_pic = true;
        def.control_top = parseFloat($('.js-control-l i').css('top')); //初始top
    });
    //放大镜控制
    pop_layer.on('mousemove', function(e) {
        if (def.is_move_pic == true) {
            var ev = e || event;
            var _y = ev.clientY - def.move_y;
            _y = def.control_top + _y;
            if (_y >= 0 && _y <= 74) {
                $('.js-control-l i').css({ top: _y + 'px' });
                ySet(_y); //同步放大倍数
            }
        }
    });
    //放大镜控制
    pop_layer.on('mouseup', function(e) {
        def.is_move_pic = false;
    });
    imgScroll();
    //鼠标滚动图片放大缩小
    function imgScroll() {
        var oBigImg = $('img.js-bigbox');
        if (oBigImg.length <= 0) {
            return;
        }
        var oImg = oBigImg[0];
        var wheelScroll = function(ev) {
            var ev = ev || window.event;
            var zoom = parseInt(oImg.style.zoom, 10) || 100;
            if (ev.wheelDelta) {
                if (ev.wheelDelta > 0) {
                    $(oImg)
                        .addClass('bzoom')
                        .removeClass('szoom');
                } else {
                    $(oImg)
                        .addClass('szoom')
                        .removeClass('bzoom');
                }
            } else {
                if (-ev.detail > 0) {
                    $(oImg)
                        .addClass('bzoom')
                        .removeClass('szoom');
                } else {
                    $(oImg)
                        .addClass('szoom')
                        .removeClass('bzoom');
                }
            }
            zoom += ev.wheelDelta ? ev.wheelDelta / 12 : -ev.detail;
            if (zoom >= 50 && zoom <= 300) {
                $('.js-reset').removeClass('on-reset'); //可以复位
                oImg.style.cssText = '-moz-transform:scale(' + zoom / 100 + ');-moz-transform-origin: center top 0px;display:inline;';
                oImg.style.zoom = zoom + '%'; //ff，opera不支持zoom放大缩小
                radioSet(zoom / 100);
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
        oImg.onmousedown = function() {
            if (oBigImg.hasClass('bzoom')) {
                oBigImg.addClass('dragIcon').removeClass('bzoom');
            } else if (oBigImg.hasClass('szoom')) {
                oBigImg.addClass('dragIcon').removeClass('szoom');
            }
            oImg.onmouseup = function() {
                oBigImg.addClass('dragIcon');
            };
        };
        $('#draggable').draggable();
    }
    //放大镜位置同步 距离同步到倍数
    function ySet(_y) {
        var _img = $('img.js-bigbox');
        if (_y > 74) {
            _y = 74;
        } else if (_y < 0) {
            _y = 0;
        }
        var _radio = 1,
            b_t = 50,
            m_t = 24;
        if (_y > b_t) {
            _radio = 1 - ((_y - b_t) / m_t) * 0.5;
        } else if (_y < b_t) {
            _radio = 3 - (_y / b_t) * 2;
        }
        if (_img.length > 0) {
            $('.js-reset').removeClass('on-reset'); //可以复位
            _img[0].style.cssText = '-moz-transform:scale(' + _radio + ');-moz-transform-origin: center top 0px;display:inline;';
            _img[0].style.zoom = _radio * 100 + '%'; //ff，opera不支持zoom放大缩小
        }
    }
    //放大镜位置同步 倍数同步到距离
    function radioSet(_radio) {
        _radio = _radio || -1;
        if (_radio != -1) {
            if (_radio > def.max_radio) {
                _radio = def.max_radio;
            } else if (_radio < def.min_radio) {
                _radio = def.min_radio;
            }
            var _top = 50,
                b_t = 50,
                m_t = 24;
            if (_radio > 1) {
                _top = ((def.max_radio - _radio) * b_t) / 2;
            } else if (_radio < 1) {
                _top = b_t + m_t - (_radio - def.min_radio) * m_t * 2;
            }
            $('.js-control-l')
                .find('i')
                .css('top', _top + 'px');
        }
    }
    //重新计算图片位置
    function intBigImg() {
        var bigImgBox = $('#draggable');
        bigImgBox.css({ left: 0, top: 0, width: '100%' });
        bigImgBox.find('img').css({ '-moz-transform': 'scale(1)', zoom: '100%', display: 'inline' });
        radioSet(1);
    }
    // 获取图片原始大小
    function natural(img) {
        var dImg = $('#draggable img');
        var nImg = new Image();
        nImg.src = dImg[0].src;
        dImg[0].width = nImg.width;
    }
    // 进入弹层重新计算图片大小
    function imgSize(img) {
        if (img.length > 0) {
            img[0].width = def.imgDefW;
            $('.js-bp-load').hide();
            $('#draggable img').show();
        }
    }
    // 关闭图片
    $('.js-close-verimg').on('click', function() {
        if (!def.trendSubShow) {
            $('html,body').removeClass('overflow-layer');
        }
        if (def.remColorPvShow) { def.remColorPvShow = false; }
        pop_layer.hide();
        def.maskEl.hide();
    });
    // 下载
    $('.js-viewimg-download').on('click', function() {
        var data = {};
        if (def.remColorPvShow) {
            data = remColorList.children().eq(styleIdx).find('img').data();
        } else if (def.trendSubShow) {
            if (trendViewimgIdx < 2) {
                data = trendRmstyleBox.eq(trendViewimgIdx).find('.recommend-item').children().eq(styleIdx).find('img').data();
            } else {
                var page = Math.ceil((styleIdx + 1)/8);
                data = trendRmstyleBox.eq(trendViewimgIdx).find('.recommend-list').children().eq(page - 1).find('li').eq(styleIdx - (page - 1) * 8).find('img').data();
            }
        } else {
            data = rmstyleSub.children().eq(styleIdx).find('img').data();
        }
        downloadFun(data);
    });
    // 图片 切页
    $('.js-viewimg-pager').on('click', 'i', function() {
        if (def.getImgLoading) { return; }
        var target = $(this);
        if (target.hasClass('disabled')) { return; }
        var styleLen = def.rmstyleLen;
        if (def.remColorPvShow) {
            styleLen = def.remColorStyleLen;
        } else if (def.trendSubShow) {
            styleLen = def.trendRmstyleLen[trendViewimgIdx];
        }
        if (target.hasClass('l')) {
            styleIdx--;
            if (styleIdx < 0) {
                styleIdx = styleLen - 1;
            }
        } else if (target.hasClass('r')) {
            styleIdx++;
            if (styleIdx > styleLen - 1) {
                styleIdx = 0;
            }
        }
        var data = {};
        if (def.remColorPvShow) {
            data = remColorList.children().eq(styleIdx).find('img').data();
        } else if (def.trendSubShow) {
            if (trendViewimgIdx < 2) {
                data = trendRmstyleBox.eq(trendViewimgIdx).find('.recommend-item').children().eq(styleIdx).find('img').data();
            } else {
                var page = Math.ceil((styleIdx + 1)/8);
                data = trendRmstyleBox.eq(trendViewimgIdx).find('.recommend-list').children().eq(page - 1).find('li').eq(styleIdx - (page - 1) * 8).find('img').data();
            }
        } else {
            data = rmstyleSub.children().eq(styleIdx).find('img').data();
        }
        getImgDetailFn(data);
    });
    // 图片加载
    $('#draggable img').on('load', function() {
        $(this).parents('.img-content').removeClass('loading');
    })

    // 图表==========================================
    // 数据分析===================
    var isPantongAnalysi = false;
    var colorTitle = $('.js-color-title');
    // 面包屑
    var crumbsAnalysis = $('.js-crumbs-analysis');
    var chartAnalysiLocation = [];
    crumbsAnalysis.on('click', 'span', function() {
        if (P_UserType == 5) { return; }
        if (def.analysisLoading) { return; }
        var child = crumbsAnalysis.children();
        var len = child.length;
        var self = $(this);
        var idx = self.parent().index();
        if (idx == len - 1) { return; }
        if (idx == 0) {
            def.analysiCategoryId = '';
            def.analysiColorId = '';
            def.analysiLastclick = '';
            chartAnalysiLocation = [];
            isPantongAnalysi = false;
            colorTitle.removeClass('child');
            self.parent().next().find('span').attr({
                'data-id': '',
                'data-key': ''
            }).text('总览');
            for (var i = 0; i < len; i++) {
                if (i > 1) {
                    child.eq(i).remove();
                }
            }
        } else {
            var locationIdx = 0;
            for (var i = 0; i < len; i++) {
                if (i > idx) {
                    child.eq(i).remove();
                }
            }
            for (var i = 0; i < chartAnalysiLocation.length; i++) {
                if (chartAnalysiLocation[i].id == self.data().id) {
                    locationIdx = i;
                    break;
                }
            }
            chartAnalysiLocation.splice(locationIdx + 1);
            var locationLen = chartAnalysiLocation.length;
            def.analysiCategoryId = '';
            def.analysiColorId = '';
            def.analysiLastclick = '';
            isPantongAnalysi = false;
            for (var i = 0; i < locationLen; i++) {
                if (typeof chartAnalysiLocation[i].colour === 'boolean') {
                    def.analysiColorId = chartAnalysiLocation[i].id;
                    def.analysiLastclick = 'color';
                    isPantongAnalysi = true;
                }
                if (typeof chartAnalysiLocation[i].colour === 'undefined') {
                    def.analysiCategoryId = chartAnalysiLocation[i].id;
                    def.analysiLastclick = 'category';
                }
            }
        }
        colorAnalysisFn(def.analysiSeasonId, def.analysiCategoryId, def.analysiColorId, def.analysiLastclick);
    });
    // 柱状图
    var chartBarAnalysi = echarts.init(document.getElementsByClassName('js-chart-bar-analysis')[0]);
    var chartBarAnalysiSource = [
        { "name": "内衣", "count": '0' },
        { "name": "礼服", "count": '0' },
        { "name": "婚纱", "count": '0' },
        { "name": "家居服", "count": '0' },
        { "name": "套装", "count": '0' },
        { "name": "裙", "count": '0' },
        { "name": "裤", "count": '0' },
        { "name": "外套", "count": '0' },
        { "name": "上衣", "count": '0' }
    ];
    var chartBarAnalysiOpt = {
        animationDurationUpdate: 400,
        title: [
            {
                subtext: '',
                left: 40,
                bottom: 0,
                subtextStyle: {
                    color: '#9899AD',
                    fontSize: 12
                }
            }
        ],
        textStyle: { color: '#9899AD' },
        tooltip: {
            trigger: 'item',
            formatter: function(params) {
                if (!params.value) { return; }
                return params.seriesName + '<br>' + params.name + ': ' + params.value.count + '款';
            }
        },
        grid: {
            left: 108,
            bottom: 70
        },
        xAxis: {
            axisLine: { show: false },
            axisTick: { show: false },
            axisLabel: { margin: 12 },
            splitLine: {
                lineStyle: { color: '#333947' }
            },
            splitNumber: 12,
            min: 360
        },
        yAxis: {
            type: 'category',
            axisLine: { show: false },
            axisTick: { show: false },
            axisLabel: {
                margin: 24,
                align: 'right'
            }
        },
        dataset: {
            dimensions: ['name', 'count'],
            source: chartBarAnalysiSource
        },
        series: [
            {
                name: '数据分析',
                type: 'bar',
                barWidth: 12,
                label: {
                    show: true,
                    position: 'right',
                    formatter: function(params) {
                        if (params.data.count === '0') {
                            return '';
                        } else {
                            return '{bg|' + params.data.count + '}';
                        }
                    },
                    rich: {
                        bg: {
                            backgroundColor: { image: IMGURL + '/global/images/color_data/label.png' },
                            color: '#fff',
                            align: 'center',
                            verticalAlign: 'middle',
                            padding: [0, 0, 0, 8],
                            height: 24
                        }
                    }
                },
                itemStyle: {
                    barBorderRadius: 10,
                    color: new echarts.graphic.LinearGradient(0, 0, 1, 0, [
                        {
                            offset: 0,
                            color: '#FC9E84'
                        },
                        {
                            offset: 1,
                            color: '#FF358A'
                        }
                    ])
                }
            }
        ]
    };
    if (!def.viewport) {
        chartBarAnalysiOpt.grid.left = 90;
    }
    chartBarAnalysi.setOption(chartBarAnalysiOpt);
    chartBarAnalysi.on('click', { seriesType: 'bar' }, function(params) {
        if (def.analysisLoading) { return; }
        var child = crumbsAnalysis.children();
        var len = child.length;
        var isHave = true;
        for (var i = 0; i < chartAnalysiLocation.length; i++) {
            if (chartAnalysiLocation[i].id == params.data.id) {
                isHave = false;
                break;
            }
        }
        if (isHave) {
            chartAnalysiLocation.push(params.data);
            if (len < 3 && child.eq(1).find('span').text() === '总览') {
                child.eq(1).find('span').attr({
                    'data-id': params.data.id,
                    'data-key': 'category'
                }).text(params.data.name);
            } else {
                crumbsAnalysis.append('<div><i></i><span data-id="' + params.data.id + '" data-key="category">' + params.data.name + '</span></div>');
            }
        }
        def.analysiCategoryId = params.data.id;
        def.analysiLastclick = 'category';
        colorAnalysisFn(def.analysiSeasonId, def.analysiCategoryId, def.analysiColorId, def.analysiLastclick);
    });
    // 饼图
    var chartPieAnalysi = echarts.init(document.getElementsByClassName('js-chart-pie-analysis')[0]);
    var chartPieAnalysiSource = [
        { "name": "", "count": 30, "color_number": "#303648" },
        { "name": "", "count": 30, "color_number": "#2C3343" },
        { "name": "", "count": 30, "color_number": "#303648" },
        { "name": "", "count": 30, "color_number": "#2C3343" },
        { "name": "", "count": 30, "color_number": "#303648" },
        { "name": "", "count": 30, "color_number": "#2C3343" },
        { "name": "", "count": 30, "color_number": "#303648" },
        { "name": "", "count": 30, "color_number": "#2C3343" },
        { "name": "", "count": 30, "color_number": "#303648" },
        { "name": "", "count": 30, "color_number": "#2C3343" },
        { "name": "", "count": 30, "color_number": "#303648" },
        { "name": "", "count": 30, "color_number": "#2C3343" }
    ];
    var chartPieAnalysiOpt = {
        animationDurationUpdate: 400,
        textStyle: { color: '#9899AD' },
        tooltip: {
            show: false,
            trigger: 'item',
            formatter: function(params) {
                if (isPantongAnalysi) {
                    return params.seriesName + '<br>' + params.name + ': ' + params.value.count + '款' + '<br>' + params.value.pantone_number;
                }
                return params.seriesName + '<br>' + params.name + ': ' + params.value.count + '款';
            }
        },
        dataset: {
            dimensions: ['name', 'count'],
            source: chartPieAnalysiSource
        },
        series: [
            {
                type: 'pie',
                legendHoverLink: false,
                hoverAnimation: false,
                tooltip: { show:false },
                bottom: 50,
                radius: [0, '25%'],
                label: {
                    position: 'center',
                    color: '#9899AD',
                    fontSize: 24,
                    verticalAlign: 'middle'
                },
                labelLine: { show: false },
                emphasis: {
                    label: { color: '#fff' },
                    labelLine: {
                        lineStyle: { color: '#fff' }
                    },
                    itemStyle: { opacity: 0 }
                },
                data: [
                    {value: 0, name: '色彩分类', type: 'btn', itemStyle: { color: '#292F3E' }}
                ]
            },
            {
                name: '色彩分类',
                type: 'pie',
                bottom: 50,
                radius: ['30%', '65%'],
                label: {
                    show: false,
                    fontSize: 16,
                    formatter: function(params) {
                        return params.data.name + ' ' + params.data.count + '款';
                    }
                },
                labelLine: {
                    show: false,
                    length: 50,
                    lineStyle: { color: '#9899AD' }
                },
                emphasis: {
                    label: { show: false, color: '#fff' },
                    labelLine: {
                        show: false,
                        lineStyle: { color: '#fff' }
                    }
                },
                itemStyle: {
                    color: function(params) {
                        return params.data.color_number
                    }
                }
            }
        ]
    };
    if (!def.viewport) {
        chartPieAnalysiOpt.series[0].radius = [0, '18%'];
        chartPieAnalysiOpt.series[0].label.fontSize = 18;
        chartPieAnalysiOpt.series[1].radius = ['22%', '45%'];
        chartPieAnalysiOpt.series[1].labelLine.length = 50;
    }
    chartPieAnalysi.setOption(chartPieAnalysiOpt);
    chartPieAnalysi.on('click', { seriesType: 'pie' }, function(params) {
        if (!params.data.name) { return; }
        if (def.analysisLoading) { return; }
        if (params.data.type == 'btn') {
            if (params.data.name == '返回') {
                chartAnalysiLocation.pop();
                var locationLen = chartAnalysiLocation.length;
                var child = crumbsAnalysis.children();
                var len = child.length;
                if (locationLen > 0) {
                    crumbsAnalysis.children().eq(len - 1).remove();
                } else {
                    crumbsAnalysis.children().eq(1).find('span').attr({
                        'data-id': '',
                        'data-key': ''
                    }).text('总览');
                    chartPieAnalysiOpt.series[0].data[0].name = '色彩分类';
                    chartPieAnalysi.setOption(chartPieAnalysiOpt);
                    chartPieAnalysi.resize();
                }
                def.analysiCategoryId = '';
                def.analysiColorId = '';
                def.analysiLastclick = '';
                isPantongAnalysi = false;
                for (var i = 0; i < locationLen; i++) {
                    if (typeof chartAnalysiLocation[i].colour === 'boolean') {
                        def.analysiColorId = chartAnalysiLocation[i].id;
                        def.analysiLastclick = 'color';
                        isPantongAnalysi = true;
                    }
                    if (typeof chartAnalysiLocation[i].colour === 'undefined') {
                        def.analysiCategoryId = chartAnalysiLocation[i].id;
                        def.analysiLastclick = 'category';
                    }
                }
                colorAnalysisFn(def.analysiSeasonId, def.analysiCategoryId, def.analysiColorId, def.analysiLastclick);
            }
            return;
        }
        isPantongAnalysi = true;
        var child = crumbsAnalysis.children();
        var len = child.length;
        var isHave = true;
        for (var i = 0; i < chartAnalysiLocation.length; i++) {
            if (chartAnalysiLocation[i].id == params.data.id) {
                isHave = false;
                break;
            }
        }
        if (isHave) {
            chartAnalysiLocation.push(params.data);
            if (len < 3 && child.eq(1).find('span').text() === '总览') {
                child.eq(1).find('span').attr({
                    'data-id': params.data.id,
                    'data-key': 'color'
                }).text(params.data.name + '系');
            } else {
                crumbsAnalysis.append('<div><i></i><span data-id="' + params.data.id + '" data-key="category">' + params.data.name + '系</span></div>');
            }
        }
        colorTitle.addClass('child').find('span').eq(2).text(params.data.name + '系');
        chartPieAnalysiOpt.series[0].data[0].name = '返回';
        chartPieAnalysi.setOption(chartPieAnalysiOpt);
        chartPieAnalysi.resize();
        def.analysiColorId = params.data.id;
        def.analysiLastclick = 'color';
        colorAnalysisFn(def.analysiSeasonId, def.analysiCategoryId, def.analysiColorId, def.analysiLastclick);
    });

    // 色彩走势====================
    var titleTrendSub = '';
    // 面包屑
    var crumbsTrend = $('.js-crumbs-trend');
    crumbsTrend.on('click', 'span', function() {
        if (P_UserType == 5 || P_UserType == 4) { return; }
        if (def.trendLoading) { return; }
        var self = $(this);
        var child = self.parents('.js-crumbs-trend').children();
        var len = child.length;
        var idx = self.parent().index();
        if (idx == len - 1) { return; }
        if (def.trendSubShow) { return; }
        if (idx == 0 || idx == 1) {
            def.trendColorId = '';
        }
        $('html,body').removeClass('overflow-layer');
        $('.js-color-trend').hide();
        def.trendSubShow = false;
        colorTrendFn(def.trendSeasonId, def.trendColorId);
    });
    // 柱状图
    var chartBarTrendSeason = '';
    var chartBarTrendSource = [{ "name": "", "percent_1": 0, "percent_2": 0, "percent_3": 0 }];
    var chartBarTrend = echarts.init(document.getElementsByClassName('js-chart-bar-trend')[0]);
    var chartBarTrendOpt = {
        animationDurationUpdate: 400,
        textStyle: { color: '#9899AD' },
        tooltip: {
            trigger: 'item',
            formatter: function(params) {
                return params.name + '系<br>' + params.seriesName + '年: ' + params.value['percent_' + (params.seriesIndex + 1)] + '%';
            }
        },
        grid: {
            left: 80,
            right: 50,
            bottom: 80
        },
        dataset: {
            dimensions: ['name', 'percent_1', 'percent_2', 'percent_3'],
            source: chartBarTrendSource
        },
        xAxis: {
            type: 'category',
            axisLine: { show: false },
            axisTick: { show: false },
            axisLabel: {
                rotate: -30,
                margin: 45,
                align: 'center',
                formatter: function (value) {
                    if (value) {
                        return ['{title|' + value + '系}', '{left|' + chartBarTrendSeason + '}'].join('\n');
                    }
                    return '';
                },
                rich: {
                    left: { align: 'left' },
                    title: {
                        align: 'left',
                        padding: [8, 0, 0, 5]
                    }
                }
            },
        },
        yAxis: {
            axisLine: { show: false },
            axisTick: { show: false },
            axisLabel: {
                margin: 12,
                align: 'right',
                formatter: '{value}%'
            },
            splitLine: {
                lineStyle: { color: '#333947' }
            },
            splitNumber: 10,
            min: 100
        },
        series: [
            {
                name: '',
                type: 'bar', barWidth: 12,
                label: {
                    show: true,
                    position: 'top',
                    distance: 10,
                    rotate: -90,
                    align: 'right',
                    verticalAlign: 'middle',
                    offset: [-1.5, 0],
                    formatter: function(params) {
                        if (params.data.name) {
                            return params.data.percent_1 + '%';
                        }
                        return '';
                    }
                },
                itemStyle: {
                    barBorderRadius: 10,
                    color: function(params) {
                        return params.data.color_number;
                    }
                }
            },
            {
                name: '',
                type: 'bar', barWidth: 12,
                label: {
                    show: true,
                    position: 'top',
                    distance: 10,
                    rotate: -90,
                    align: 'right',
                    verticalAlign: 'middle',
                    offset: [-1.5, 0],
                    formatter: function(params) {
                        if (params.data.name) {
                            return params.data.percent_2 + '%';
                        }
                        return '';
                    }
                },
                itemStyle: {
                    barBorderRadius: 10,
                    color: function(params) {
                        return params.data.color_number;
                    }
                }
            },
            {
                name: '',
                type: 'bar', barWidth: 12, barGap: '60%',
                label: {
                    show: true,
                    position: 'top',
                    distance: 10,
                    rotate: -90,
                    align: 'right',
                    verticalAlign: 'middle',
                    offset: [-1.5, 0],
                    formatter: function(params) {
                        if (params.data.name) {
                            return params.data.percent_3 + '%';
                        }
                        return '';
                    }
                },
                itemStyle: {
                    barBorderRadius: 10,
                    color: function(params) {
                        return params.data.color_number;
                    }
                }
            }
        ]
    };
    if (!def.viewport) {
        chartBarTrendOpt.grid.left = 70;
        chartBarTrendOpt.grid.right = 30;
    }
    chartBarTrend.setOption(chartBarTrendOpt);
    chartBarTrend.on('click', { seriesType: 'bar' }, function(params) {
        if (def.trendLoading) { return; }
        def.trendColorId = params.data.id;
        colorTrendFn(def.trendSeasonId, def.trendColorId);
        titleTrendSub = params.data.name;
        $(crumbsTrend[1]).children().eq(2).find('span').text(params.data.name + '系');
        $('.js-trend-category-title').text(params.data.name + '系-单品走势');
        $('.js-trend-pie-title span').text(params.data.name + '系相关颜色分类');
        $('html,body').addClass('overflow-layer');
        $('.js-color-trend').show();
        def.trendSubShow = true;
        chartBarTrendSub.resize();
        chartPieTrendSub.resize();
        chartBarTrendSubItem.resize();
    });

    // 色彩走势二级===================
    var chartBarTrendSubList = [];
    // 柱状图
    var chartBarTrendSub = echarts.init(document.getElementsByClassName('js-chart-bar-trendsub')[0]);
    var chartBarTrendSubOpt = {
        animationDurationUpdate: 400,
        textStyle: { color: '#9899AD' },
        tooltip: {
            trigger: 'item',
            formatter: function(params) {
                return params.name + '系<br>' + params.seriesName + '年: ' + params.value['percent_' + (params.seriesIndex + 1)] + '%';
            }
        },
        grid: {
            left: 90,
            right: 10,
            bottom: 100
        },
        dataset: {
            dimensions: ['name', 'percent_1', 'percent_2', 'percent_3'],
            source: chartBarTrendSource
        },
        xAxis: {
            type: 'category',
            axisLine: { show: false },
            axisTick: { show: false },
            axisLabel: {
                interval: 0,
                margin: 15,
                align: 'center',
                formatter: function (value) {
                    if (value) {
                        return ['{padding|' + value + '系}', chartBarTrendSeason].join('\n');
                    }
                    return '';
                },
                rich: {
                    padding: { padding: [8, 0, 0, 0] }
                }
            }
        },
        yAxis: {
            axisLine: { show: false },
            axisTick: { show: false },
            axisLabel: {
                margin: 12,
                align: 'right',
                formatter: '{value}%'
            },
            splitLine: {
                lineStyle: { color: '#333947' }
            },
            splitNumber: 10,
            min: 100
        },
        dataZoom: {
            show: false,
            type: 'slider',
            borderColor: '#333947',
            handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
            handleSize: '80%',
            handleStyle: {
                color: '#fff',
                shadowBlur: 3,
                shadowColor: 'rgba(0, 0, 0, 0.6)',
                shadowOffsetX: 2,
                shadowOffsetY: 2
            },
            showDetail: false,
            height: 9,
            rangeMode: ['value', 'value'],
            zoomLock: true,
            startValue: 0,
            endValue: 7
        },
        series: [
            {
                name: '',
                type: 'bar', barWidth: 12,
                label: {
                    show: true,
                    position: 'top',
                    distance: 10,
                    rotate: -90,
                    align: 'right',
                    verticalAlign: 'middle',
                    offset: [-1.5, 0],
                    formatter: function(params) {
                        if (params.data.name) {
                            return params.data.percent_1 + '%';
                        }
                        return '';
                    }
                },
                itemStyle: {
                    barBorderRadius: 10,
                    color: function(params) {
                        return params.data.color_number;
                    }
                }
            },
            {
                name: '',
                type: 'bar', barWidth: 12,
                label: {
                    show: true,
                    position: 'top',
                    distance: 10,
                    rotate: -90,
                    align: 'right',
                    verticalAlign: 'middle',
                    offset: [-1.5, 0],
                    formatter: function(params) {
                        if (params.data.name) {
                            return params.data.percent_2 + '%';
                        }
                        return '';
                    }
                },
                itemStyle: {
                    barBorderRadius: 10,
                    color: function(params) {
                        return params.data.color_number;
                    }
                }
            },
            {
                name: '',
                type: 'bar', barWidth: 12, barGap: '60%',
                label: {
                    show: true,
                    position: 'top',
                    distance: 10,
                    rotate: -90,
                    align: 'right',
                    verticalAlign: 'middle',
                    offset: [-1.5, 0],
                    formatter: function(params) {
                        if (params.data.name) {
                            return params.data.percent_3 + '%';
                        }
                        return '';
                    }
                },
                itemStyle: {
                    barBorderRadius: 10,
                    color: function(params) {
                        return params.data.color_number;
                    }
                }
            }
        ]
    };
    chartBarTrendSub.setOption(chartBarTrendSubOpt);
    // 饼图
    var chartPieTrendSub = echarts.init(document.getElementsByClassName('js-chart-pie-trendsub')[0]);
    var chartPieTrendSubSource = [
        { "name": "", "pievalue": 30, "color_number": "#303648" },
        { "name": "", "pievalue": 30, "color_number": "#2C3343" },
        { "name": "", "pievalue": 30, "color_number": "#303648" },
        { "name": "", "pievalue": 30, "color_number": "#2C3343" },
        { "name": "", "pievalue": 30, "color_number": "#303648" },
        { "name": "", "pievalue": 30, "color_number": "#2C3343" },
        { "name": "", "pievalue": 30, "color_number": "#303648" },
        { "name": "", "pievalue": 30, "color_number": "#2C3343" },
        { "name": "", "pievalue": 30, "color_number": "#303648" },
        { "name": "", "pievalue": 30, "color_number": "#2C3343" },
        { "name": "", "pievalue": 30, "color_number": "#303648" },
        { "name": "", "pievalue": 30, "color_number": "#2C3343" }
    ];
    var chartPieTrendSubOpt = {
        animationDurationUpdate: 400,
        textStyle: { color: '#9899AD' },
        tooltip: {
            show: false,
            trigger: 'item',
            formatter: function(params) {
                return titleTrendSub + '系: ' + params.name + '<br>' + params.value.pantone_number;
            }
        },
        dataset: {
            dimensions: ['name', 'pievalue'],
            source: chartPieTrendSubSource
        },
        series: [
            {
                type: 'pie',
                legendHoverLink: false,
                hoverAnimation: false,
                tooltip: { show:false },
                radius: [0, '25%'],
                label: {
                    position: 'center',
                    color: '#9899AD',
                    fontSize: 20,
                    verticalAlign: 'middle'
                },
                labelLine: { show: false },
                emphasis: {
                    label: { color: '#fff' },
                    itemStyle: { opacity: 0 }
                },
                data: [
                    {value: 0, name: '返回', type: 'btn', itemStyle: { color: '#292F3E' }}
                ]
            },
            {
                name: '色彩走势',
                type: 'pie',
                radius: ['30%', '60%'],
                label: {
                    show: false,
                    fontSize: 12
                },
                labelLine: { show: false },
                emphasis: {
                    label: { show: false, color: '#fff' }
                },
                itemStyle: {
                    color: function(params) {
                        return params.data.color_number;
                    }
                }
            }
        ]
    };
    chartPieTrendSub.setOption(chartPieTrendSubOpt);
    chartPieTrendSub.on('click', { seriesType: 'pie' }, function(params) {
        if (!params.data.name) { return; }
        if (def.trendLoading) { return; }
        if (params.data.type == 'btn') {
            if (params.data.name == '返回') {
                $('html,body').removeClass('overflow-layer');
                $('.js-color-trend').hide();
                def.trendSubShow = false;
            }
            return;
        }
        for (var i = 0; i < chartBarTrendSubList.length; i++) {
            if (chartBarTrendSubList[i].id == params.data.id) {
                var item = chartBarTrendSubList.splice(i, 1)[0];
                chartBarTrendSubList.unshift(item);
                chartBarTrendSubOpt.dataset.source = chartBarTrendSubList;
                chartBarTrendSub.setOption(chartBarTrendSubOpt);
                chartBarTrendSub.resize();
                break;
            }
        }
        colorTrendStyleFn(def.trendSeasonId, params.data.id);
    });
    // 单品
    var chartBarTrendSubItemColors = ["#D74A4A", "#DC4B79", "#D849C3", "#9B4BD9", "#5F50E8", "#4C9FDE", "#47D6D1", "#49DA92", "#66DF4C", "#ECD150"];
    var chartBarTrendSubItem = echarts.init(document.getElementsByClassName('js-chart-bar-trendsub-item')[0]);
    var chartBarTrendSubItemOpt = {
        animationDurationUpdate: 400,
        textStyle: { color: '#9899AD' },
        tooltip: {
            trigger: 'item',
            formatter: function(params) {
                return params.name + '<br>' + params.seriesName + '年: ' + params.value['percent_' + (params.seriesIndex + 1)] + '%';
            }
        },
        grid: {
            left: 100,
            right: 50,
            bottom: 100
        },
        dataset: {
            dimensions: ['name', 'percent_1', 'percent_2', 'percent_3'],
            source: chartBarTrendSource
        },
        xAxis: {
            type: 'category',
            axisLine: { show: false },
            axisTick: { show: false },
            axisLabel: {
                interval: 0,
                margin: 15,
                align: 'center',
                formatter: function (value) {
                    if (value) {
                        return ['{padding|' + value + '}', '{left|' + chartBarTrendSeason + '}'].join('\n');
                    }
                    return '';
                },
                rich: {
                    padding: { padding: [8, 0, 0, 0] }
                }
            }
        },
        yAxis: {
            axisLine: { show: false },
            axisTick: { show: false },
            axisLabel: {
                margin: 12,
                align: 'right',
                formatter: '{value}%'
            },
            splitLine: {
                lineStyle: { color: '#333947'}
            },
            splitNumber: 10,
            min: 100
        },
        dataZoom: {
            show: false,
            type: 'slider',
            borderColor: '#333947',
            handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
            handleSize: '80%',
            handleStyle: {
                color: '#fff',
                shadowBlur: 3,
                shadowColor: 'rgba(0, 0, 0, 0.6)',
                shadowOffsetX: 2,
                shadowOffsetY: 2
            },
            showDetail: false,
            height: 9,
            rangeMode: ['value', 'value'],
            zoomLock: true,
            startValue: 0,
            endValue: 7
        },
        series: [{
            name: '',
            type: 'pictorialBar',
            barWidth: 36,
            barMinHeight: 0,
            symbol: 'path://M0,10 L10,10 C10,10 8,5 5,5 C2,5 0,10 0,10 z',
            itemStyle: {
                opacity: 0.6,
                color: function (params) {
                    if (params.data.name) {
                        if (params.data.percent_1) {
                            return chartBarTrendSubItemColors[params.dataIndex%10];
                        }
                        return '#fff';
                    }
                    return '';
                }
            },
            emphasis: {
                itemStyle: { opacity: 1 }
            },
            label: {
                show: true,
                position: 'top',
                distance: 10,
                rotate: -90,
                align: 'right',
                verticalAlign: 'middle',
                offset: [-1.5, 0],
                formatter: function(params) {
                    if (params.data.name) {
                        return params.data.percent_1 + '%';
                        // return '{bg|' + params.data.percent_1 + '%}';
                    }
                    return '';
                },
                rich: {
                    bg: {
                        backgroundColor: 'rgba(66, 70, 91, 0.8)',
                        color: '#fff',
                        align: 'center',
                        borderRadius: 5,
                        padding: 5
                    }
                }
            }
        }, {
            name: '',
            type: 'pictorialBar',
            barWidth: 36,
            barMinHeight: 0,
            symbol: 'path://M0,10 L10,10 C10,10 8,5 5,5 C2,5 0,10 0,10 z',
            itemStyle: {
                opacity: 0.6,
                color: function (params) {
                    if (params.data.name) {
                        if (params.data.percent_2) {
                            return chartBarTrendSubItemColors[params.dataIndex%10];
                        }
                        return '#fff';
                    }
                    return '';
                }
            },
            emphasis: {
                itemStyle: { opacity: 1 }
            },
            label: {
                show: true,
                position: 'top',
                distance: 10,
                rotate: -90,
                align: 'right',
                verticalAlign: 'middle',
                offset: [-1.5, 0],
                formatter: function(params) {
                    if (params.data.name) {
                        return params.data.percent_2 + '%';
                        // return '{bg|' + params.data.percent_2 + '%}';
                    }
                    return '';
                },
                rich: {
                    bg: {
                        backgroundColor: 'rgba(66, 70, 91, 0.8)',
                        color: '#fff',
                        align: 'center',
                        borderRadius: 5,
                        padding: 5
                    }
                }
            }
        }, {
            name: '',
            type: 'pictorialBar',
            barGap: '-50%',
            barWidth: 36,
            barMinHeight: 0,
            symbol: 'path://M0,10 L10,10 C10,10 8,5 5,5 C2,5 0,10 0,10 z',
            itemStyle: {
                opacity: 0.6,
                color: function (params) {
                    if (params.data.name) {
                        if (params.data.percent_3) {
                            return chartBarTrendSubItemColors[params.dataIndex%10];
                        }
                        return '#fff';
                    }
                    return '';
                }
            },
            emphasis: {
                itemStyle: { opacity: 1 }
            },
            label: {
                show: true,
                position: 'top',
                distance: 10,
                rotate: -90,
                align: 'right',
                verticalAlign: 'middle',
                offset: [-1.5, 0],
                formatter: function(params) {
                    if (params.data.name) {
                        return params.data.percent_3 + '%';
                        // return '{bg|' + params.data.percent_3 + '%}';
                    }
                    return '';
                },
                rich: {
                    bg: {
                        backgroundColor: 'rgba(66, 70, 91, 0.8)',
                        color: '#fff',
                        align: 'center',
                        borderRadius: 5,
                        padding: 5
                    }
                }
            }
        }]
    };
    chartBarTrendSubItem.setOption(chartBarTrendSubItemOpt);
    var chartBarTrendSubItemTimeout = null;
    chartBarTrendSubItem.on('click', {seriesType: 'pictorialBar'}, function () {
        if (chartBarTrendSubItemTimeout) { return; }
        $('.js-trend-category-tips').show();
        chartBarTrendSubItemTimeout = setTimeout(function() {
            $('.js-trend-category-tips').hide();
            clearTimeout(chartBarTrendSubItemTimeout);
            chartBarTrendSubItemTimeout = null;
        }, 2000);
    })

    // 上升下降色====================
    // 面包屑
    var crumbsUdcolor = $('.js-crumbs-udcolor');
    crumbsUdcolor.on('click', 'span', function() {
        if (P_UserType == 5 || P_UserType == 4) { return; }
        if (def.trendLoading) { return; }
        var self = $(this);
        var child = self.parents('.js-crumbs-udcolor').children();
        var len = child.length;
        var idx = self.parent().index();
        if (idx == len - 1) { return; }
        if (idx == 0 || idx == 1) {
            def.udColorId = '';
            chartPieLiftSelectIdx = -1;
        }
        if (def.udColorId) {
            uporDownColorSubFn(def.udColorSeasonId, def.udColorId);
        } else {
            crumbsUdcolor.children().eq(2).remove();
            $('.js-chart-bar-lift').show().siblings().hide();
            uporDownColorFn(def.udColorSeasonId);
        }
    });
    // 柱状图
    var chartBarLift = echarts.init(document.getElementsByClassName('js-chart-bar-lift')[0]);
    var chartBarLiftSource = [{name: '', diff_up: 0}];
    var chartBarLiftOpt = {
        animationDurationUpdate: 400,
        textStyle: { color: '#9899AD' },
        tooltip: {
            trigger: 'item',
            formatter: function(params) {
                return params.name + '<br>' + params.seriesName + ': ' + params.value.percent_diff + '%';
            }
        },
        grid: {
            left: 80,
            right: 50,
            bottom: 20
        },
        dataset: {
            dimensions: ['name', 'diff_up', 'diff_down'],
            source: chartBarLiftSource
        },
        xAxis: {
            type: 'category',
            axisLine: { show: false },
            axisTick: { show: false },
            axisLabel: {show: false }
        },
        yAxis: {
            type: 'value',
            axisLine: { show: false },
            axisTick: { show: false },
            axisLabel: {
                margin: 12,
                align: 'right',
                formatter: '{value}%'
            },
            splitLine: {
                lineStyle: { color: '#333947' }
            },
            splitNumber: 10,
            min: 100
        },
        series: [
            {
                name: '上升',
                type: 'bar', barWidth: 32, stack: 'stack',
                label: {
                    show: true,
                    align: 'center',
                    position: 'top',
                    distance: 10,
                    formatter: function(params) {
                        if (!params.data.name) { return ''; }
                        return ['{bg|} {count|' + params.data.diff_up + '%}', '{name|' + params.data.name + '}'].join('\n');
                    },
                    rich: {
                        count: {
                            color: '#fff',
                            padding: [-5, 0, 0, 0]
                        },
                        name: { padding: [0, 0, 8, 0] },
                        bg: {
                            height: 13,
                            backgroundColor: { image: IMGURL + '/global/images/color_data/upor_arrow.png' }
                        }
                    }
                },
                itemStyle: {
                    barBorderRadius: [100, 100, 0, 0],
                    color: function(params) {
                        if (!params.data.color_number) { return ''; }
                        return params.data.color_number;
                    }
                }
            },
            {
                name: '下降',
                type: 'bar', barWidth: 32, stack: 'stack',
                label: {
                    show: true,
                    align: 'center',
                    position: 'bottom',
                    distance: 10,
                    formatter: function(params) {
                        if (!params.data.name) { return ''; }
                        return ['{bg|} {count|' + params.data.diff_down + '%}', '{name|' + params.data.name + '}'].join('\n');
                    },
                    rich: {
                        count: { color: '#fff' },
                        name: { padding: [0, 0, 5, 0] },
                        bg: {
                            height: 13,
                            backgroundColor: { image: IMGURL + '/global/images/color_data/down_arrow.png' }
                        }
                    }
                },
                itemStyle: {
                    barBorderRadius: [0, 0, 100, 100],
                    color: function(params) {
                        if (!params.data.color_number) { return ''; }
                        return params.data.color_number;
                    }
                }
            }
        ]
    };
    if (!def.viewport) {
        chartBarLiftOpt.grid.left = 70;
        chartBarLiftOpt.grid.right = 30;
    }
    chartBarLift.setOption(chartBarLiftOpt);
    chartBarLift.on('click', { seriesType: 'bar' }, function(params) {
        if (def.udColorLoading || def.udColorSubLoading) { return; }
        def.udColorId = params.data.id;
        uporDownColorSubFn(def.udColorSeasonId, def.udColorId);
        crumbsUdcolor.append('<div><i></i><span data-id="' + params.data.id + '" data-key="color">' + params.data.name + '</span></div>');
        $('.js-chart-bar-lift').hide().siblings().show();
        chartLineLift.resize();
        chartPieLift.resize();
        chartLineLiftColor = params.data.color_number;
    });
    // 折线图
    var chartLineLift = echarts.init(document.getElementsByClassName('js-chart-line-lift')[0]);
    var chartLineLiftSource = [{year: '', percent: 0}];
    var chartLineLiftColor = '#fff';
    var chartLineLiftOpt = {
        animationDurationUpdate: 400,
        textStyle: { color: '#9899AD' },
        tooltip: {
            trigger: 'item',
            formatter: function(params) {
                return params.seriesName + '<br>' + params.value.season_name + ': ' + params.value.percent + '%';
            }
        },
        grid: {
            left: 80,
            right: 50,
            bottom: 30
        },
        dataset: {
            dimensions: ['year', 'percent'],
            source: chartLineLiftSource
        },
        xAxis: {
            type: 'category',
            boundaryGap: false,
            axisLabel: { margin: 12 },
            axisLine: { show: false },
            axisTick: { show: false }
        },
        yAxis: {
            type: 'value',
            axisLine: { show: false },
            axisTick: { show: false },
            axisLabel: {
                margin: 12,
                align: 'right',
                formatter: '{value}%'
            },
            splitLine: {
                lineStyle: { color: '#333947' }
            },
            splitNumber: 10,
            min: 100
        },
        series: {
            name: '排行榜',
            type: 'line',
            showAllSymbol: true,
            symbolSize: 0,
            label: {
                show: true,
                position: 'top',
                distance: 10,
                formatter: function(params) {
                    if (!params.data.year) { return ''; }
                    return '{bg|' + params.data.percent + '%}';
                },
                rich: {
                    bg: {
                        backgroundColor: 'rgba(66, 70, 91, 0.8)',
                        color: '#fff',
                        align: 'center',
                        borderRadius: 5,
                        padding: 5
                    }
                }
            },
            lineStyle: { color: '#fff' },
            itemStyle: { color: '#fff' },
            areaStyle: {
                color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                    offset: 0,
                    color: 'rgba(255, 255, 255, 0.6)'
                }, {
                    offset: 1,
                    color: 'rgba(255, 255, 255, 0)'
                }])
            }
        }
    };
    if (!def.viewport) {
        chartLineLiftOpt.grid.left = 70;
        chartLineLiftOpt.grid.right = 30;
    }
    chartLineLift.setOption(chartLineLiftOpt);
    // 饼图
    var chartPieLift = echarts.init(document.getElementsByClassName('js-chart-pie-lift')[0]);
    var chartPieLiftSelectIdx = -1;
    var chartPieLiftSource = [
        { "name": "", "count": 30, "color_number": "#303648" },
        { "name": "", "count": 30, "color_number": "#2C3343" },
        { "name": "", "count": 30, "color_number": "#303648" },
        { "name": "", "count": 30, "color_number": "#2C3343" },
        { "name": "", "count": 30, "color_number": "#303648" },
        { "name": "", "count": 30, "color_number": "#2C3343" },
        { "name": "", "count": 30, "color_number": "#303648" },
        { "name": "", "count": 30, "color_number": "#2C3343" },
        { "name": "", "count": 30, "color_number": "#303648" },
        { "name": "", "count": 30, "color_number": "#2C3343" },
        { "name": "", "count": 30, "color_number": "#303648" },
        { "name": "", "count": 30, "color_number": "#2C3343" }
    ];
    var chartPieLiftOpt = {
        animationDurationUpdate: 400,
        textStyle: { color: '#9899AD' },
        tooltip: {
            show: false,
            trigger: 'item',
            formatter: function(params) {
                return '颜色推荐<br>' + params.name + ': ' + params.value.count + '款<br>' + params.value.pantone_number;
            }
        },
        dataset: {
            dimensions: ['name', 'count'],
            source: chartPieLiftSource
        },
        series: [
            {
                type: 'pie',
                legendHoverLink: false,
                hoverAnimation: false,
                tooltip: { show:false },
                center: ['50%', '50%'],
                radius: [0, '25%'],
                label: {
                    position: 'center',
                    color: '#9899AD',
                    fontSize: 24,
                    verticalAlign: 'middle'
                },
                labelLine: { show: false },
                emphasis: {
                    label: { color: '#fff' },
                    labelLine: {
                        lineStyle: { color: '#fff' }
                    },
                    itemStyle: { opacity: 0 }
                },
                data: [
                    {value: 0, name: '返回', type: 'btn', itemStyle: { color: '#292F3E' }}
                ]
            },
            {
                name: '色彩走势',
                type: 'pie',
                center: ['50%', '50%'],
                radius: ['30%', '60%'],
                selectedMode: 'single',
                label: {
                    show: false,
                    fontSize: 12,
                    formatter: function(params) {
                        return params.data.name + ' ' + (params.data.count/def.chartUdColor.total*100).toFixed(2) + '%';
                    }
                },
                labelLine: {
                    show: false,
                    lineStyle: { color: '#9899AD' }
                },
                emphasis: {
                    label: { show: false, color: '#fff' },
                    labelLine: {
                        show: false,
                        lineStyle: { color: '#fff' }
                    }
                },
                itemStyle: {
                    color: function(params) {
                        return params.data.color_number;
                    }
                }
            }
        ]
    };
    chartPieLift.setOption(chartPieLiftOpt);
    chartPieLift.on('click', { seriesType: 'pie' }, function(params) {
        if (!params.data.name) { return; }
        if (def.udColorSubLoading) { return; }
        if (params.data.type == 'btn') {
            if (params.data.name == '返回') {
                crumbsUdcolor.children().eq(2).remove();
                $('.js-chart-bar-lift').show().siblings().hide();
                def.udColorId = 0;
                chartPieLiftSelectIdx = -1;
                uporDownColorFn(def.udColorSeasonId, def.udColorId);
            }
            return;
        }
        chartLineLiftColor = params.data.color_number;
        var child = crumbsUdcolor.children();
        child.eq(2).find('span').attr('data-id', params.data.id).text(params.data.name);
        def.udColorId = params.data.id;
        uporDownColorSubFn(def.udColorSeasonId, def.udColorId);
    });

    // 全局===========================================
    //禁用右键
    if (P_UserType != 1 && P_UserType != 2) {
        $(document).bind("contextmenu",function(){return false;});
    }
    var guidePW = viewport ? 1500 : 1200;
    var guideRight = (($(window).width() - scrollBarWidth - guidePW)/2 - 60)/2 + 60;
    guideEl.css('right', -(guideRight < 60 ? 60 : guideRight) + 'px');
    $(window).on('resize', function() {
        // 函数节流
        pop_fashion_global.fn.throttle(function(){
            def.ow=document.documentElement.clientWidth || document.body.clientWidth;
            def.oh=document.documentElement.clientHeight || document.body.clientHeight;
        },this,[]);

        viewport = $(window).width() > (1500 - scrollBarWidth) ? 1 : 0;
        def.imgDefW = viewport ? 548 : 400;
        // 引导
        guidePW = viewport ? 1500 : 1200;
        guideRight = (($(window).width() - scrollBarWidth - guidePW)/2 - 60)/2 + 60;
        guideEl.css('right', -(guideRight < 60 ? 60 : guideRight) + 'px');
        // 推荐色
        reColorChartDefW = def.viewport ? 64 : 48;
        initReColorChart();
        // 图表初始
        if (def.viewport != viewport) {
            def.viewport = viewport;
            // 推荐款式图==================
            def.rmstyleW = viewport ? 234 : 215;
            def.rmstylePageOW = viewport ? def.rmstyleW * 6 : def.rmstyleW * 5;
            def.rmstylePages = Math.ceil(def.rmstyleLen/(def.viewport ? 6 : 5));
            if (rmstylePage >= def.rmstylePages) {
                rmstylePage = def.rmstylePages;
                rmstylePageEl.find('.r').addClass('disabled');
                rmstylePageEl.find('.l').removeClass('disabled');
            } else if (rmstylePage == 1) {
                rmstylePageEl.find('.r').removeClass('disabled');
                rmstylePageEl.find('.l').addClass('disabled');
            } else {
                rmstylePageEl.find('.r').removeClass('disabled');
                rmstylePageEl.find('.l').removeClass('disabled');
            }
            if (def.rmstyleLen * def.rmstyleW > def.rmstylePageOW) {
                rmstyleSub.width(def.rmstyleLen * def.rmstyleW);
            } else {
                rmstylePageEl.find('i').addClass('disabled');
            }
            var left = (rmstylePage - 1) * def.rmstylePageOW;
            rmstyleSub.css('margin-left', -left + 'px');
            // 色彩走势二级款式==============
            def.trendRmstyleW = viewport ? 136 : 116;
            def.trendRmstylePageOW = def.trendRmstyleW * 4;
            for (var i = 0; i < 3; i++) {
                var box = $(trendRmstyleBox[i]);
                var pagerEl = $(trendRmmstylePageEl[i]);
                var isMin = i < 2;
                def.trendRmstylePages[i] = Math.ceil(def.trendRmstyleLen[i]/4);
                if (trendRmstylePage[i] >= def.trendRmstylePages[i]) {
                    trendRmstylePage[i] = def.trendRmstylePages[i];
                    pagerEl.find('.r').addClass('disabled');
                    pagerEl.find('.l').removeClass('disabled');
                } else if (trendRmstylePage[i] == 1) {
                    pagerEl.find('.r').removeClass('disabled');
                    pagerEl.find('.l').addClass('disabled');
                } else {
                    pagerEl.find('.r').removeClass('disabled');
                    pagerEl.find('.l').removeClass('disabled');
                }
                if (def.trendRmstyleLen[i] * def.trendRmstyleW > def.trendRmstylePageOW) {
                    if (isMin) {
                        box.find('.recommend-item').width(def.trendRmstyleLen[i] * def.trendRmstyleW);
                    } else {
                        box.find('.recommend-list').width(def.trendRmstyleLen[i] * def.trendRmstyleW);
                    }
                } else {
                    pagerEl.find('i').addClass('disabled');
                }
                var left = (trendRmstylePage[i] - 1) * def.trendRmstylePageOW;
                if (isMin) {
                    box.find('.recommend-item').css('margin-left', -left + 'px');
                } else {
                    box.find('.recommend-list').css('margin-left', -left + 'px');
                }
            }

            if (def.viewport) { // 大屏
                // 数据分析==================
                chartBarAnalysiOpt.grid.left = 108;
                chartPieAnalysiOpt.series[0].radius = [0, '30%'];
                chartPieAnalysiOpt.series[0].label.fontSize = 24;
                chartPieAnalysiOpt.series[1].radius = ['30%', '65%'];
                chartPieAnalysiOpt.series[1].labelLine.length = 60;
                // 色彩走势==================
                chartBarTrendOpt.grid.left = 80;
                chartBarTrendOpt.grid.right = 50;
                // 上升下降色==================
                chartBarLiftOpt.grid.left = 80;
                chartBarLiftOpt.grid.right = 50;
                chartLineLiftOpt.grid.left = 80;
                chartLineLiftOpt.grid.right = 50;
            } else { // 小屏
                // 数据分析==================
                chartBarAnalysiOpt.grid.left = 90;
                chartPieAnalysiOpt.series[0].radius = [0, '18%'];
                chartPieAnalysiOpt.series[0].label.fontSize = 18;
                chartPieAnalysiOpt.series[1].radius = ['22%', '45%'];
                chartPieAnalysiOpt.series[1].labelLine.length = 50;
                // 色彩走势==================
                chartBarTrendOpt.grid.left = 70;
                chartBarTrendOpt.grid.right = 30;
                // 上升下降色==================
                chartBarLiftOpt.grid.left = 70;
                chartBarLiftOpt.grid.right = 30;
                chartLineLiftOpt.grid.left = 70;
                chartLineLiftOpt.grid.right = 30;
            }
            // 数据分析==================
            chartBarAnalysi.setOption(chartBarAnalysiOpt);
            chartBarAnalysi.resize();
            chartPieAnalysi.setOption(chartPieAnalysiOpt);
            chartPieAnalysi.resize();
            // 色彩走势==================
            chartBarTrend.setOption(chartBarTrendOpt);
            chartBarTrend.resize();
            // 上升下降色==================
            chartBarLift.setOption(chartBarLiftOpt);
            chartBarLift.resize();
            // 二级
            chartLineLift.setOption(chartLineLiftOpt);
            chartLineLift.resize();
            chartPieLift.resize();
        }
    });

    // 工具方法========================================
    // 下载
    function downloadFun(params) {
        if (!oCommon.downloadPrivilege()) {
			return false;
        }
        if (def.action_down) { return; }
        def.action_down = true;
        pop_fashion_global.fn.subAjax({
            url: '/ajax/downloadstylesingle/?id=' + params.id + '&table=' + params.t + '&col=' + params.col,
            ctp: 'application/x-www-form-urlencoded; charset=UTF-8',
            successFunc: function (data) {
                def.action_down = false;
                var link = data.data || "";
                window.location.href = '/download/dlsingle/?dl_link=' + encodeURIComponent(link) + '&' + Math.random();
            },
            errorFunc: function (data) {
                def.action_down = false;
                oCommon.showTips(data.message);
            }
        });
    }
    // 获取滚动条宽度
    function getScrollBarWidth() {
        var el = document.createElement("p"),
            styles = {
                width: "100px",
                height: "100px",
                overflowY: "scroll"
            },
            i;
        for (i in styles) {
            el.style[i] = styles[i];
        }

        // 将元素加到body里面
        document.body.appendChild(el);

        var scrollBarWidth = el.offsetWidth - el.clientWidth;
        //将添加的元素删除
        $(el).remove();
        return scrollBarWidth;
    }
    // 16进制颜色转为RGBA格式
    function colorRgba(val, op) {
        var reg = /^#([0-9a-fA-f]{3}|[0-9a-fA-f]{6})$/;
        var sColor = val.toLowerCase();
        if (sColor && reg.test(sColor)) {
            if (sColor.length === 4) {
                var sColorNew = "#";
                for (var i = 1; i < 4; i += 1) {
                    sColorNew += sColor.slice(i, i + 1).concat(sColor.slice(i, i + 1));
                }
                sColor = sColorNew;
            }
            //处理六位的颜色值
            var sColorChange = [];
            for (var i = 1; i < 7; i += 2) {
                sColorChange.push(parseInt("0x" + sColor.slice(i, i + 2)));
            }
            return "rgba(" + sColorChange.join(",") + ", " + op + ")";
        } else {
            return sColor;
        }
    }

    if (P_UserType == 5) {
        $('html,body').addClass('overflow-layer');
        $('.js-gradient-mask').show();
        $('.js-tourist').show();
        $('.js-recommend-color-chart').html('');
        $('.js-remcolor .section-chart-interpreta p').html('');
        return;
    }

    // 请求数据========================================
    // 色彩单品组合分析
    colorAnalysisFn(def.analysiSeasonId, def.analysiCategoryId, def.analysiColorId, def.analysiLastclick);
    function colorAnalysisFn(season, category, color, lastclick) {
        var data = {};
        if (season) { data.season = season; }
        if (category) { data.category = category; }
        if (color) { data.color = color; }
        if (lastclick) { data.lastclick = lastclick; }
        def.analysisLoading = true;
        crumbsAnalysis.next('.loading').removeClass('hide');
        $.ajax({
            url: '/colortrends/getcolordata/' + def.params,
            dataType: 'json',
            type: 'post',
            data: data,
            success: function(res) {
                if (res.code == 0) {
                    var seasonStr = seasonBox.find('.active').text(); // 季节
    
                    def.chartAnalysi.total = res.data.category_total;
                    def.chartAnalysi.percent = res.data.colour_percent;
                    $('.js-color-title span').eq(0).text(def.chartAnalysi.percent.colour + '%');
                    $('.js-color-title span').eq(1).text(def.chartAnalysi.percent.no_colour + '%');
                    $('.js-analysis .section-chart-interpreta p').text(res.data.interpret);
                    // 柱状图
                    if (res.data.category && res.data.category.length) {
                        if (data.category || data.color) {
                            chartBarAnalysiOpt.title[0].subtext = '';
                        } else {
                            chartBarAnalysiOpt.title[0].subtext = '• ' + seasonStr + '款式总数：' + def.chartAnalysi.total + '款';
                        }
                        chartBarAnalysiOpt.dataset.source = res.data.category.reverse();
                        chartBarAnalysiOpt.xAxis.splitNumber = 5;
                        chartBarAnalysiOpt.xAxis.min = 0;
                    } else {
                        chartBarAnalysiOpt.title[0].subtext = '';
                        chartBarAnalysiOpt.dataset.source = chartBarAnalysiSource;
                        chartBarAnalysiOpt.xAxis.splitNumber = 12;
                        chartBarAnalysiOpt.xAxis.min = 360;
                    }
                    chartBarAnalysi.setOption(chartBarAnalysiOpt);
                    chartBarAnalysi.resize();
                    // 饼图
                    if (res.data.color && res.data.color.length) {
                        chartPieAnalysiOpt.dataset.source = res.data.color;
                        chartPieAnalysiOpt.tooltip.show = true;
                        chartPieAnalysiOpt.series[1].label.show = true;
                        chartPieAnalysiOpt.series[1].labelLine.show = true;
                        chartPieAnalysiOpt.series[1].emphasis.label.show = true;
                        chartPieAnalysiOpt.series[1].emphasis.labelLine.show = true;
                    } else {
                        chartPieAnalysiOpt.dataset.source = chartPieAnalysiSource;
                        chartPieAnalysiOpt.tooltip.show = false;
                        chartPieAnalysiOpt.series[1].label.show = false;
                        chartPieAnalysiOpt.series[1].labelLine.show = false;
                        chartPieAnalysiOpt.series[1].emphasis.label.show = false;
                        chartPieAnalysiOpt.series[1].emphasis.labelLine.show = false;
                    }
                    chartPieAnalysi.setOption(chartPieAnalysiOpt);
                    chartPieAnalysi.resize();
                    // 推荐款式
                    $('.js-more-analysis').attr('href', res.data.more_link);
                    var list = res.data.list.slice(0,24);
                    def.rmstyleLen = list.length;
                    if (P_UserType != 4 && def.rmstyleLen > 0) {
                        def.rmstylePages = Math.ceil(def.rmstyleLen/(def.viewport ? 6 : 5));
                        var renderStr = '';
                        for (var i = 0; i < def.rmstyleLen; i++) {
                            renderStr += '<li><img src="' + list[i].cover + '" data-id="' + list[i].id + '" data-t="' + list[i].t + '" data-col="' + list[i].col + '"></li>';
                        }
                        rmstyleSub.css('margin-left', 0);
                        rmstyleSub.html(renderStr);
                        rmstylePage = 1;
                        if (def.rmstyleLen * def.rmstyleW > def.rmstylePageOW) {
                            rmstyleSub.width(def.rmstyleLen * def.rmstyleW);
                            rmstylePageEl.find('.l').addClass('disabled');
                            rmstylePageEl.find('.r').removeClass('disabled');
                        } else {
                            rmstylePageEl.find('i').addClass('disabled');
                        }
                        $('.js-section-recommend-analysis').show();
                    } else {
                        $('.js-section-recommend-analysis').hide();
                    }
                    // 停止加载
                    def.analysisLoading = false;
                    $('.js-analysis').removeClass('loading');
                } else {
                    oCommon.showTips(res.msg);
                }
            },
            error: function(err) {
                oCommon.showTips(err.msg);
            },
            complete: function() {
                crumbsAnalysis.next('.loading').addClass('hide');
            }
        })
    }

    if (P_UserType == 4) {
        $('.js-recommend-color-chart').html('');
        $('.js-remcolor .section-chart-interpreta p').html('');
        $('.js-trend').addClass('show-mask');
        $('.js-udcolor').addClass('show-mask');
        $('.js-remcolor').addClass('show-mask');
        $('.js-section-recommend-analysis').hide();
        return;
    }

    // 色彩走势一二级
    colorTrendFn(def.trendSeasonId, def.trendColorId);
    function colorTrendFn(season, color) {
        var data = {};
        if (season) { data.sea_cn = season; }
        if (color) { data.color = color; }
        def.trendLoading = true;
        crumbsTrend.next('.loading').removeClass('hide');
        $.ajax({
            url: '/colortrends/threecolortrends/' + def.params,
            dataType: 'json',
            type: 'post',
            data: data,
            success: function(res) {
                if (res.code == 0) {
                    chartBarTrendSeason = res.name;
                    if (!color) { // 一级
                        $('.js-trend .section-chart-interpreta p').text(res.interpret);
                        // 柱状图
                        if (res.data && res.data.length) {
                            chartBarTrendOpt.dataset.source = res.data;
                            chartBarTrendOpt.series[0].name = '20' + chartBarTrendSeason.substr(0,8).split('/')[0];
                            chartBarTrendOpt.series[1].name = '20' + chartBarTrendSeason.substr(0,8).split('/')[1];
                            chartBarTrendOpt.series[2].name = '20' + chartBarTrendSeason.substr(0,8).split('/')[2];
                            chartBarTrendOpt.yAxis.splitNumber = 5;
                            chartBarTrendOpt.yAxis.min = 0;
                        } else {
                            chartBarTrendOpt.dataset.source = chartBarTrendSource;
                            chartBarTrendOpt.series[0].name = '';
                            chartBarTrendOpt.series[1].name = '';
                            chartBarTrendOpt.series[2].name = '';
                            chartBarTrendOpt.yAxis.splitNumber = 10;
                            chartBarTrendOpt.yAxis.min = 100;
                        }
                        chartBarTrend.setOption(chartBarTrendOpt);
                        chartBarTrend.resize();
                        $('.js-trend').removeClass('loading');
                    } else { // 二级
                        $('.js-color-trend .section-chart-interpreta p').text(res.interpret);

                        // 柱状图
                        if (res.data && res.data.length) {
                            chartBarTrendSubList = res.data;
                            chartBarTrendSubOpt.dataset.source = res.data;
                            chartBarTrendSubOpt.series[0].name = '20' + chartBarTrendSeason.substr(0,8).split('/')[0];
                            chartBarTrendSubOpt.series[1].name = '20' + chartBarTrendSeason.substr(0,8).split('/')[1];
                            chartBarTrendSubOpt.series[2].name = '20' + chartBarTrendSeason.substr(0,8).split('/')[2];
                            chartBarTrendSubOpt.yAxis.splitNumber = 5;
                            chartBarTrendSubOpt.yAxis.min = 0;
                        } else {
                            chartBarTrendSubOpt.dataset.source = chartBarTrendSource;
                            chartBarTrendSubOpt.series[0].name = '';
                            chartBarTrendSubOpt.series[1].name = '';
                            chartBarTrendSubOpt.series[2].name = '';
                            chartBarTrendSubOpt.yAxis.splitNumber = 10;
                            chartBarTrendSubOpt.yAxis.min = 100;
                        }
                        if (res.data.length > 8) {
                            chartBarTrendSubOpt.dataZoom.show = true;
                        } else {
                            chartBarTrendSubOpt.dataZoom.show = false;
                        }
                        chartBarTrendSub.setOption(chartBarTrendSubOpt);
                        chartBarTrendSub.resize();

                        // 饼图
                        if (res.data && res.data.length) {
                            for (var i = 0; i < res.data.length; i++) {
                                res.data[i].pievalue = 0;
                            }
                            chartPieTrendSubOpt.dataset.source = res.data;
                            chartPieTrendSubOpt.tooltip.show = true;
                            chartPieTrendSubOpt.series[1].name = res.first_color_name + '系';
                            chartPieTrendSubOpt.series[1].label.show = true;
                            chartPieTrendSubOpt.series[1].emphasis.label.show = true;
                        } else {
                            chartPieTrendSubOpt.dataset.source = chartPieTrendSubSource;
                            chartPieTrendSubOpt.tooltip.show = false;
                            chartPieTrendSubOpt.series[1].name = '色彩走势';
                            chartPieTrendSubOpt.series[1].label.show = false;
                            chartPieTrendSubOpt.series[1].emphasis.label.show = false;
                        }
                        chartPieTrendSub.setOption(chartPieTrendSubOpt);
                        chartPieTrendSub.resize();

                        // 单品
                        if (res.category && res.category.length) {
                            chartBarTrendSubItemOpt.dataset.source = res.category
                            chartBarTrendSubItemOpt.series[0].name = '20' + chartBarTrendSeason.substr(0,8).split('/')[0];
                            chartBarTrendSubItemOpt.series[1].name = '20' + chartBarTrendSeason.substr(0,8).split('/')[1];
                            chartBarTrendSubItemOpt.series[2].name = '20' + chartBarTrendSeason.substr(0,8).split('/')[2];
                            chartBarTrendSubItemOpt.series[0].barMinHeight = 20;
                            chartBarTrendSubItemOpt.series[1].barMinHeight = 20;
                            chartBarTrendSubItemOpt.series[2].barMinHeight = 20;
                            chartBarTrendSubItemOpt.yAxis.splitNumber = 5;
                            chartBarTrendSubItemOpt.yAxis.min = 0;
                        } else {
                            chartBarTrendSubItemOpt.dataset.source = chartBarTrendSource;
                            chartBarTrendSubItemOpt.series[0].name = '';
                            chartBarTrendSubItemOpt.series[1].name = '';
                            chartBarTrendSubItemOpt.series[2].name = '';
                            chartBarTrendSubItemOpt.series[0].barMinHeight = 0;
                            chartBarTrendSubItemOpt.series[1].barMinHeight = 0;
                            chartBarTrendSubItemOpt.series[2].barMinHeight = 0;
                            chartBarTrendSubItemOpt.yAxis.splitNumber = 10;
                            chartBarTrendSubItemOpt.yAxis.min = 100;
                        }
                        if (res.category.length > 8) {
                            chartBarTrendSubItemOpt.dataZoom.show = true;
                        } else {
                            chartBarTrendSubItemOpt.dataZoom.show = false;
                        }
                        chartBarTrendSubItem.setOption(chartBarTrendSubItemOpt);
                        chartBarTrendSubItem.resize();

                        $('.js-color-trend').removeClass('loading');
                    }
                    def.trendLoading = false;
                } else {
                    oCommon.showTips(res.msg);
                }
            },
            error: function(err) {
                oCommon.showTips(err.msg);
            },
            complete: function() {
                if (!def.trendStyleLoading) {
                    crumbsTrend.next('.loading').addClass('hide');
                }
            }
        })
        if (color) {
            colorTrendStyleFn(season, color);
        }
    }

    // 色彩二级款式
    function colorTrendStyleFn(season, color) {
        var data = {};
        if (season) { data.sea_cn = season; }
        if (color) { data.color = color; }
        def.trendStyleLoading = true;
        $.ajax({
            url: '/colortrends/threecolortrendslist/' + def.params,
            dataType: 'json',
            type: 'post',
            data: data,
            success: function(res) {
                if (res.code == 0) {
                    var allData = [];
                    for (var i in res.lists) {
                        if (res.lists[i].data && res.lists[i].data.length) {
                            allData.push(res.lists[i]);
                        }
                    }
                    for (var i = 0; i < 3; i++) {
                        var box = $(trendRmstyleBox[i]);
                        var pagerEl = $(trendRmmstylePageEl[i]);
                        var isMin = i < 2;
                        var str = '';
                        var list = [];
                        if (allData[i]) {
                            list = allData[i].data.slice(0, (isMin ? 12 : 24));
                        }
                        def.trendRmstyleLen[i] = list.length;
                        def.trendRmstylePages[i] = Math.ceil(def.trendRmstyleLen[i]/(isMin ? 4 : 8));
                        if (def.trendRmstyleLen[i] <= 1) {
                            pagerEl.find('i').addClass('disabled');
                        }
                        if (def.trendRmstyleLen[i] > 0) {
                            box.find('.title').text(allData[i].title + '款式图');
                            box.find('.more').attr('href', allData[i].link);
                            if (isMin) {
                                for (var j = 0; j < allData[i].data.length; j++) {
                                    str += '<li><img src="' + allData[i].data[j].cover + '" data-id="' + allData[i].data[j].id + '" data-t="' + allData[i].data[j].t + '" data-col="' + list[i].col + '"></li>';
                                }
                                box.find('.recommend-item').html(str).css('margin-left', 0);
                            } else {
                                var len = allData[i].data.length;
                                var pages = Math.ceil(len/8);
                                var list = allData[i].data;
                                for (var j = 0; j < pages; j++) {
                                    str += '<li><ul class="recommend-item clearfix">';
                                    for (var l = 0; l < 8; l++) {
                                        if (list[j * 8 + l]) {
                                            str += '<li><img src="' + list[j * 8 + l].cover + '" data-id="' + list[j * 8 + l].id + '" data-t="' + list[j * 8 + l].t + '" data-col="' + list[j * 8 + l].col + '"></li>';
                                        }
                                    }
                                    str += '</ul></li>';
                                }
                                box.find('.recommend-list').html(str).css('margin-left', 0);
                            }
                            trendRmstylePage[i] = 1;
                            if (def.trendRmstyleLen[i] * def.trendRmstyleW > def.trendRmstylePageOW) {
                                if (isMin) {
                                    box.find('.recommend-item').width(def.trendRmstyleLen[i] * def.trendRmstyleW);
                                } else {
                                    box.find('.recommend-list').width(def.trendRmstyleLen[i] * def.trendRmstyleW);
                                }
                                pagerEl.find('.l').addClass('disabled');
                                pagerEl.find('.r').removeClass('disabled');
                            } else {
                                pagerEl.find('i').addClass('disabled');
                            }
                            box.show();
                        } else {
                            box.hide();
                        }
                    }

                    def.trendStyleLoading = false;
                    trendAside.removeClass('loading');
                } else {
                    oCommon.showTips(res.msg);
                }
            },
            error: function(err) {
                oCommon.showTips(err.msg);
            },
            complete: function() {
                if (!def.trendLoading) {
                    crumbsTrend.next('.loading').addClass('hide');
                }
            }
        })
    }

    // 上升下降色一级
    var defMin = function (value) { return Math.floor(value.min); }
    uporDownColorFn(def.udColorSeasonId);
    function uporDownColorFn(season) {
        var data = {};
        if (season) { data.sea_cn = season; }
        def.udColorLoading = true;
        crumbsUdcolor.next('.loading').removeClass('hide');
        $.ajax({
            url: '/colortrends/upordowncolor/' + def.params,
            dataType: 'json',
            type: 'post',
            data: data,
            success: function(res) {
                if (res.code == 0) {
                    $('.js-udcolor-info span:eq(0)').text(res.seasons[0]);
                    $('.js-udcolor-info span:eq(1)').text(res.seasons[1]);
                    $('.js-udcolor .section-chart-interpreta p').text(res.interpret);
                    var list = [];
                    for (var i = 0; i < 10; i++) {
                        if (res.top.up[i]) {
                            res.top.up[i].diff_up = res.top.up[i].percent_diff;
                            list.push(res.top.up[i]);
                        }
                        if (res.top.down[i]) {
                            res.top.down[i].diff_down = res.top.down[i].percent_diff;
                            list.push(res.top.down[i]);
                        }
                    }
                    // 柱状图
                    if (list.length) {
                        chartBarLiftOpt.dataset.source = list;
                        chartBarLiftOpt.yAxis.splitNumber = 5;
                        chartBarLiftOpt.yAxis.min = defMin;
                    } else {
                        chartBarLiftOpt.dataset.source = chartBarLiftSource;
                        chartBarLiftOpt.yAxis.splitNumber = 10;
                        chartBarLiftOpt.yAxis.min = 100;
                    }
                    chartBarLift.setOption(chartBarLiftOpt);
                    chartBarLift.resize();

                    def.udColorLoading = false;
                    $('.js-udcolor').removeClass('loading');
                } else {
                    oCommon.showTips(res.msg);
                }
            },
            error: function(err) {
                oCommon.showTips(err.msg);
            },
            complete: function() {
                crumbsUdcolor.next('.loading').addClass('hide');
            }
        })
    }
    
    // 上升下降色二级
    function uporDownColorSubFn(season, color) {
        var data = {};
        if (season) { data.sea_cn = season; }
        if (color) { data.color = color; }
        def.udColorSubLoading = true;
        crumbsUdcolor.next('.loading').removeClass('hide');
        $.ajax({
            url: '/colortrends/upordowncolorsecond/' + def.params,
            dataType: 'json',
            type: 'post',
            data: data,
            success: function(res) {
                if (res.code == 0) {
                    $('.js-udcolor .section-chart-interpreta p').text(res.interpret);
                    $('.js-udcolor-color-title span').text(res.p_color_name + '系相关颜色推荐');
                    // 折现图
                    if (res.color_line_chart && res.color_line_chart.length) {
                        chartLineLiftOpt.dataset.source = res.color_line_chart;
                        chartLineLiftOpt.series.symbolSize = 10;
                        chartLineLiftOpt.yAxis.splitNumber = 5;
                        chartLineLiftOpt.yAxis.min = 0;
                        chartLineLiftOpt.series.lineStyle.color = chartLineLiftColor;
                        chartLineLiftOpt.series.itemStyle.color = chartLineLiftColor;
                        chartLineLiftOpt.series.areaStyle.color = new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                            offset: 0,
                            color: colorRgba(chartLineLiftColor, 0.6)
                        }, {
                            offset: 1,
                            color: colorRgba(chartLineLiftColor, 0.1)
                        }]);
                    } else {
                        chartLineLiftOpt.dataset.source = chartLineLiftSource;
                        chartLineLiftOpt.series.symbolSize = 0;
                        chartLineLiftOpt.yAxis.splitNumber = 10;
                        chartLineLiftOpt.yAxis.min = 100;
                    }
                    chartLineLift.setOption(chartLineLiftOpt);
                    chartLineLift.resize();
                    // 饼图
                    if (chartPieLiftSelectIdx < 0) {
                        def.chartUdColor.total = res.pie_res_total;
                        if (res.color_pie_chart && res.color_pie_chart.length) {
                            chartPieLiftOpt.dataset.source = res.color_pie_chart;
                            chartPieLiftOpt.series[0].center = ['50%', '48%'];
                            chartPieLiftOpt.series[1].name = res.color_name + '系';
                            chartPieLiftOpt.tooltip.show = true;
                            chartPieLiftOpt.series[1].label.show = true;
                            chartPieLiftOpt.series[1].labelLine.show = true;
                            chartPieLiftOpt.series[1].emphasis.label.show = true;
                            chartPieLiftOpt.series[1].emphasis.labelLine.show = true;
                        } else {
                            chartPieLiftOpt.dataset.source = chartPieLiftSource;
                            chartPieLiftOpt.series[0].center = ['50%', '50%'];
                            chartPieLiftOpt.series[1].name = '排行榜';
                            chartPieLiftOpt.tooltip.show = false;
                            chartPieLiftOpt.series[1].label.show = false;
                            chartPieLiftOpt.series[1].labelLine.show = false;
                            chartPieLiftOpt.series[1].emphasis.label.show = false;
                            chartPieLiftOpt.series[1].emphasis.labelLine.show = false;
                        }
                        chartPieLift.setOption(chartPieLiftOpt);
                        chartPieLift.resize();
                    }
                    for (var i = 0; i < res.color_pie_chart.length; i++) {
                        if (res.color_pie_chart[i].id == def.udColorId) {
                            chartPieLiftSelectIdx = i;
                            break;
                        }
                    }
                    if (chartPieLiftSelectIdx >= 0) {
                        chartPieLift.dispatchAction({
                            type: 'pieSelect',
                            dataIndex: chartPieLiftSelectIdx
                        });
                    }

                    def.udColorSubLoading = false;
                } else {
                    oCommon.showTips(res.msg);
                }
            },
            error: function(err) {
                oCommon.showTips(err.msg);
            },
            complete: function() {
                crumbsUdcolor.next('.loading').addClass('hide');
            }
        })
    }

    // 推荐色列表
    recommendColorListFn(def.remColorSeasonId, def.remColorId);
    function recommendColorListFn(season, color) {
        var data = {};
        if (season) { data.season = season; }
        if (color) { data.color = color; }
        def.remColorLoading = true;
        $.ajax({
            url: '/colortrends/recommendcolorlist/' + def.params,
            dataType: 'json',
            type: 'post',
            data: data,
            success: function(res) {
                if (res.code == 0) {
                    $('.js-remcolor-more').attr('href', res.more_link);
                    var len = res.list.length;
                    var renderStr = '';
                    def.remColorStyleLen = len;
                    for (var i = 0; i < len; i++) {
                        renderStr += '<li><img src="' + res.list[i].cover + '" data-id="' + res.list[i].id + '" data-t="' + res.list[i].t + '" data-col="' + res.list[i].col + '"></li>';
                    }
                    remColorList.html(renderStr);
                    def.remColorLoading = false;
                    $('.js-remcolor').removeClass('loading');
                } else {
                    oCommon.showTips(res.msg);
                }
            },
            error: function(err) {
                oCommon.showTips(err.msg);
            }
        })
    }

    // 查看大图
    function getImgDetailFn(data) {
        def.getImgLoading = true;
        $('#draggable').parent().addClass('loading');
        $.ajax({
            url: '/Colortrends/getimagedetail/',
            dataType: 'json',
            type: 'get',
            data: data,
            success: function(res) {
                if (res.code == 0) {
                    $('#draggable img').attr('src', res.big_img);
                    $('html,body').addClass('overflow-layer');
                    // 复位
                    $('.js-reset').addClass('on-reset');
                    imgSize($('#draggable img'));
                    intBigImg();
                    def.maskEl.show();
                    pop_layer.show();
                } else if (res.code == 1001) {
                    window.open(res.url);
                } else {
                    oCommon.showTips(res.msg);
                }
            },
            error: function(err) {
                oCommon.showTips(err.msg);
            },
            complete: function() {
                def.getImgLoading = false;
            }
        })
    }

});
