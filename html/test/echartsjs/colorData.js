$(function() {
    var def = {
        maskEl: $('.js-mask'), // 蒙层
        // 查看图片====================================
        move_y: 0, // 滚动条移动距离
        is_move_pic: false, // 是否移动中
        control_top: 74, // 放大镜top距离
        min_radio: 0.5, // 图片放大最小倍数
        max_radio: 3, // 图片放大最大倍数
        imgDefW: 548, // 图片默认宽548/640/400
        // 推荐色======================================
        reColorChartOneW: 0 // 一个卡片的最大宽度
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

    // 切换季节========================================
    $('.js-season').on('click', 'li', function() {
        $(this)
            .addClass('active')
            .siblings()
            .removeClass('active');
    });
    // 切页
    $('.js-season-pager').on('click', 'i', function(e) {
        e = e || window.event;
        var target = $(e.target);
        if (target.hasClass('disabled')) {
            return;
        }
        if (target.hasClass('l')) {
            console.log('l');
        } else if (target.hasClass('r')) {
            console.log('r');
        }
    });

    // 推荐色==========================================
    var reColorChartIdx = 0;
    var reColorChartDefW = 64;
    var reColorChartMinW = 350;
    var reColorChartEl = $('.js-recommend-color-chart');
    var reColorChartChildEl = $('.js-recommend-color-chart').children();
    var reColorChartChildLen = reColorChartChildEl.length;
    initReColorChart();
    reColorChartChildEl.on('click', function() {
        reColorChartIdx = $(this).index();
        $(this)
            .width(def.reColorChartOneW)
            .addClass('active')
            .siblings()
            .width('')
            .removeClass('active');
    });
    // 推荐色初始化
    function initReColorChart() {
        var reColorChartW = reColorChartEl.width();
        def.reColorChartOneW = reColorChartW - (reColorChartChildLen - 1) * reColorChartDefW;
        reColorChartChildEl
            .eq(reColorChartIdx)
            .width(def.reColorChartOneW)
            .siblings()
            .width('');
    }

    // 引导===========================================
    var guideIdx = 1;
    var guideEl = $('.js-guide');
    var guideParentEl = guideEl.parent();
    var guidePageEl = $('.js-guide-page');
    var guideBarEl = $('.js-guide-bar');
    guideEl.css('left', guideParentEl.width() + guideParentEl.offset().left - $(window).scrollLeft() + 45);
    // 打开引导
    guideEl.on('click', function() {
        $('html,body').addClass('overflow-layer');
        def.maskEl.show();
        $('.js-guide-content').show();
    });
    // 关闭
    $('.js-guide-close').on('click', function() {
        $('html,body').removeClass('overflow-layer');
        def.maskEl.hide();
        $('.js-guide-content').hide();
    });
    // 切页
    $('.js-guide-pager').on('click', 'i', function(e) {
        e = e || window.event;
        var target = $(e.target);
        if (target.hasClass('disabled')) {
            return;
        }
        if (target.hasClass('l')) {
            guideIdx--;
            if (guideIdx < 1) {
                guideIdx = 1;
            }
            $(this)
                .siblings('span')
                .text(guideIdx + '/5');
            guideBarEl.width((guideIdx / 5) * 100 + '%');
            guidePageEl.removeClass('page' + (guideIdx + 1)).addClass('page' + guideIdx);
        } else if (target.hasClass('r')) {
            guideIdx++;
            if (guideIdx > 5) {
                guideIdx = 5;
            }
            $(this)
                .siblings('span')
                .text(guideIdx + '/5');
            guideBarEl.width((guideIdx / 5) * 100 + '%');
            guidePageEl.removeClass('page' + (guideIdx - 1)).addClass('page' + guideIdx);
        }
    });

    // 全局===========================================
    window.onscroll = function() {
        // 引导
        guideEl.css('left', guideParentEl.width() + guideParentEl.offset().left - $(window).scrollLeft() + 45);
    };
    window.onresize = function() {
        // 引导
        guideEl.css('left', guideParentEl.width() + guideParentEl.offset().left - $(window).scrollLeft() + 45);
        // 推荐色
        initReColorChart();
    };

    // 推荐款式图======================================
    var pop_layer = $('#pop_layer');
    // 列表 切页
    $('.js-recommend-pager').on('click', 'i', function(e) {
        e = e || window.event;
        var target = $(e.target);
        if (target.hasClass('disabled')) {
            return;
        }
        if (target.hasClass('l')) {
            console.log('l');
        } else if (target.hasClass('r')) {
            console.log('r');
        }
    });
    // 查看图片
    $('.js-recommend-item').on('click', 'li', function() {
        $('html,body').addClass('overflow-layer');
        // 复位
        $('.js-reset').addClass('on-reset');
        imgSize($('#draggable img'));
        intBigImg();
        def.maskEl.show();
        pop_layer.show();
    });
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
        $('html,body').removeClass('overflow-layer');
        pop_layer.hide();
        def.maskEl.hide();
    });
    // 图片 切页
    $('.js-viewimg-pager').on('click', 'i', function(e) {
        e = e || window.event;
        var target = $(e.target);
        if (target.hasClass('disabled')) {
            return;
        }
        if (target.hasClass('l')) {
            console.log('l');
        } else if (target.hasClass('r')) {
            console.log('r');
        }
    });
    // 下载
    $('.js-viewimg-download').on('click', function() {
        console.log($(this).data().bp);
    });

    // 图表==========================================
    // 数据分析
    // 柱状图
    var chartBarAnalysi = echarts.init(document.getElementsByClassName('js-chart-bar-analysi')[0]);
    var chartBarAnalysiOpt = {
        title: [
            {
                subtext: '• 2020年春夏款式总数：22211款',
                left: 40,
                bottom: 0,
                subtextStyle: {
                    color: '#9899AD',
                    fontSize: 12
                }
            }
        ],
        textStyle: {
            color: '#9899AD'
        },
        backgroundColor: '#292F3E',
        tooltip: {},
        grid: {
            left: 108
        },
        xAxis: {
            axisLine: {
                show: false
            },
            axisTick: {
                show: false
            },
            axisLabel: {
                margin: 12
            },
            splitLine: {
                lineStyle: {
                    color: '#333947'
                }
            }
        },
        yAxis: {
            data: ['衬衫', '羊毛衫', '雪纺衫', '裤子', '高跟鞋', '袜子'],
            axisLine: {
                show: false
            },
            axisTick: {
                show: false
            },
            axisLabel: {
                margin: 24,
                align: 'right'
            }
        },
        series: [
            {
                type: 'bar',
                barWidth: 12,
                data: [5, 20, 36, 10, 10, 20],
                label: {
                    normal: {
                        show: true,
                        position: 'right',
                        formatter: '{bg|{c}}',
                        rich: {
                            bg: {
                                backgroundColor: {
                                    image: './img/label.png'
                                },
                                color: '#fff',
                                align: 'center',
                                padding: [0, 0, 0, 5],
                                width: 50 - 5,
                                height: 24
                            }
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
    chartBarAnalysi.setOption(chartBarAnalysiOpt);
    chartBarAnalysi.on('click', { seriesType: 'bar' }, function(params) {
        console.log(params);
    });
    // 饼图
    var chartPieAnalysi = echarts.init(document.getElementsByClassName('js-chart-pie-analysis')[0]);
    var chartPieAnalysiOpt = {
        textStyle: {
            color: '#9899AD'
        },
        backgroundColor: '#292F3E',
        tooltip: {},
        series: [
            {
                type: 'pie',
                radius: ['80', '60%'],
                label: {
                    fontSize: 16
                },
                labelLine: {
                    length: 60,
                    lineStyle: {
                        color: '#9899AD'
                    }
                },
                emphasis: {
                    label: {
                        color: '#fff'
                    },
                    labelLine: {
                        lineStyle: {
                            color: '#fff'
                        }
                    }
                },
                data: [
                    { value: 335, name: '直接访问', itemStyle: { color: '#fff' } },
                    { value: 310, name: '邮件营销' },
                    { value: 234, name: '联盟广告' },
                    { value: 135, name: '视频广告' },
                    { value: 1548, name: '搜索引擎' }
                ]
            }
        ]
    };
    chartPieAnalysi.setOption(chartPieAnalysiOpt);
    chartPieAnalysi.on('click', { seriesType: 'pie' }, function(params) {
        console.log(params);
    });

    // $('html,body').addClass('overflow-layer');
    // $('.js-color-trend').show();
    // 色彩走势二级====================================
    var trendAside = $('.js-trend-aside');
    $('.js-expand').on('click', function() {
        trendAside.toggleClass('active')
    })
    // 关闭
    $('.js-trend-close').on('click', function() {
        $('html,body').removeClass('overflow-layer');
        $('.js-color-trend').hide();
    })
});
