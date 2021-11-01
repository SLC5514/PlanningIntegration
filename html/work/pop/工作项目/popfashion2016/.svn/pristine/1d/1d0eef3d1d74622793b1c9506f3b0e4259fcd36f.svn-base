if (!Array.prototype.forEach) {
    Array.prototype.forEach = function(callback/*, thisArg*/) {
        var T, k;
        if (this == null) {
            throw new TypeError('this is null or not defined');
        }
        var O = Object(this);
        var len = O.length >>> 0;
        if (typeof callback !== 'function') {
            throw new TypeError(callback + ' is not a function');
        }
        if (arguments.length > 1) {
            T = arguments[1];
        }
        k = 0;
        while (k < len) {
            var kValue;
            if (k in O) {
                kValue = O[k];
                callback.call(T, kValue, k, O);
            }
            k++;
        }
    };
}
$(function() {
    // 标签多行
    $('.label').toArray().forEach(function(v, i) {
        var label = $(v);
        if (!label.parents('.report-box').length) {
            if (label.height() > 44) {
                label.css('height', '44px');
                label.parent().hover(function() {
                    label.animate({'height': '66px'}, 200);
                }, function() {
                    label.animate({'height': '44px'}, 200);
                });
            } else {
                label.css('height', '44px');
            }
        }
    });

    // T台多图
    var tBox = $('.js-t-box');
    tBox.toArray().forEach(function(v, i) {
        var el = $(v);
        var len = el.children().length;
        el.addClass('len' + len);
    });

    // 多行省略
    var infoArr = $('.info').toArray();
    $('.info').toArray().forEach(function(v, i) {
        var el = $(v);
        var text = el.text();
        var w = el.width();
        var size = parseFloat(el.css('font-size'));
        el.text(pop_fashion_global.fn.cutByWidth(text, w * 1.6, size));
    });

    // 懒加载
    $(".section1 .js-data-ul img, .section4 .fr .js-data-ul img").lazyload({
        placeholder : "https://imgf2.pop-fashion.com/global/images/loading/book.gif",
        effect: "fadeIn",
        threshold: 2000
    });
    $(".section2 .js-data-ul img, .section3 .fl .js-data-ul img").lazyload({
        placeholder : "https://imgf2.pop-fashion.com/global/images/loading/style.gif",
        effect: "fadeIn",
        threshold: 2000
    });
    $(".section3 .fr .js-data-ul img").lazyload({
        placeholder : "https://imgf2.pop-fashion.com/global/images/loading/runway180.gif",
        effect: "fadeIn",
        threshold: 2000
    });
    $(".section4 .fl .js-data-ul img").lazyload({
        placeholder : "https://imgf2.pop-fashion.com/global/images/loading/analysis.gif",
        effect: "fadeIn",
        threshold: 2000
    });

    // 分页初始化
    controls($('.js-box'));

    // 导航滚动
    var navEl = $('.js-main-nav');
    var navTop = navEl.offset().top - 40;
    var scrollTop = 0;
    navScroll();
    window.onscroll = function() {
		navScroll();
    }
    function navScroll() {
		scrollTop = $(window).scrollTop();
        if (scrollTop >= navTop) {
            navEl.addClass('fixed');
        } else {
            navEl.removeClass('fixed');
        }
    }

    /**
     * @description: 控制器
     * @param {type} 控制盒 (pageType:上下页{0|1} nowpage:当前页{>0<=allpage} allpage:总页数{>0})
     * @return: undefined
     */
    function controls(boxs) {
        var boxs = boxs;
        if (!(boxs instanceof Array)) {
            boxs = boxs.toArray();
        }
        boxs.forEach(function (box, idx) {
            var box = $(box);
            var paginationEl = box.find('.js-pagination')
            ,   dataUlEl = box.find('.js-data-ul')
            ,   dataUlLiEl = dataUlEl.children()
            ,   allpage = dataUlLiEl.length
            ,   nowpage = 1;
            paginationEl.on('click', 'li', function() {
                var pageBtn = $(this);
                var pageType = parseInt(pageBtn.data().type);
                if (pageBtn.hasClass('noclick')) {
                    return;
                }
                pageBtn.siblings().removeClass('noclick');
                if (pageType) {
                    nowpage++;
                    if (nowpage >= allpage) {
                        nowpage = allpage;
                        pageBtn.addClass('noclick');
                    }
                } else {
                    nowpage--;
                    if (nowpage <= 1) {
                        nowpage = 1;
                        pageBtn.addClass('noclick');
                    }
                }
                dataUlLiEl.eq(nowpage - 1).addClass('on').siblings().removeClass('on');
            });
        });
    }
})
