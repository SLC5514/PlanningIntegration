$(function(){
  var isH5 = false;
  // H5适配
  if($(window).width()<=800){
    isH5 = true;
    $(".footerFixed").hide();
    setTimeout(function(){
      $(".js-banner").addClass("anit-banner");
    },500)
  }
  // 首屏动画
  setTimeout(function(){
    $('.js-banner').addClass('anit-banner');
  },500)

  // 大导航切换
  $('.js-nav-section a').on('click', function() {
    var idx = $(this).parent().index();
    $(this).parent().addClass('active').siblings().removeClass('active');
    scrollTopSmooth($('.js-section' + (idx + 1)).offset().top - 140);
  })
  $('.js-h5-nav-section a').on('click', function() {
    var idx = $(this).parent().index();
    $(this).parent().addClass('active').siblings().removeClass('active');
    scrollTopSmooth($('.js-s' + (idx + 1) + '-box').offset().top - ($('.js-h5-header').height() + $('.js-h5-nav-section').height()));
  })
  // h5 小tab切换
  $('.js-h5-tab-btn a').on('click', function() {
    var idx = $(this).parent().index();
    if (idx == 3) {
      scrollLeftSmooth($(this).parents('.nav-tab-box')[0], $('.js-h5-tab-btn').width() - document.body.clientWidth + parseInt($('.js-h5-tab-btn').css('padding-left')));
    } else if (idx == 0) {
      scrollLeftSmooth($(this).parents('.nav-tab-box')[0], 0);
    }
    $(this).parent().addClass('active').siblings().removeClass('active');
    $(this).parents('.h5-st-box').find('.js-h5-tab-box img:eq(' + idx + ')').css({'display': 'block'}).siblings().css({'display': 'none'});
  })
  // 氢能动力 小tab切换
  $('.js-section1 .js-tab-btn a').on('click', function() {
    var idx = $(this).parent().index();
    anitFn(idx, 1, function() {
      $('.js-section1 .tab-box').removeClass('td-1');
    });
  })
  // 无塑海岸 小tab切换
  $('.js-section2 .js-tab-btn a').on('click', function() {
    var idx = $(this).parent().index();
    anitFn(idx, 2, function() {
      $('.js-section2 .tab-box').removeClass('td-2');
    });
  })
  // MAKE KOL 小tab切换
  $('.js-section3 .js-tab-btn a').on('click', function() {
    var idx = $(this).parent().index();
    anitFn(idx, 3);
  })
  // 情绪工业 小tab切换
  $('.js-section4 .js-tab-btn a').on('click', function() {
    var idx = $(this).parent().index();
    anitFn(idx, 4);
  })
  // 书籍特色 小tab切换
  $('.js-section5 .st5-tab-btn a, .js-section5 .js-tab-btn a').on('click', function() {
    var idx = $(this).parent().index();
    anitFn(idx, 5, function() {
      $('.js-section5 .tab1-box .st-pic1').removeClass('td-3').addClass('td-1');
      $('.js-section5 .tab1-box .st-pic2').removeClass('td-4').addClass('td-2');
      $('.js-section5 .tab1-box .st-pic3').removeClass('td-5').addClass('td-3');
      $('.js-section5 .tab1-box .st-title').removeClass('td-6').addClass('td-4');
      $('.js-section5 .tab1-box .st-txt').removeClass('td-8').addClass('td-5');
    });
  })
  var browser=navigator.appName;
  var b_version=navigator.appVersion;
  var version=b_version.split(";");
  var trim_Version= version[1] && version[1].replace(/[ ]/g,"");
  var isIe8 = false;
  var arrowMT = '-20px';
  if (browser=="Microsoft Internet Explorer" && trim_Version=="MSIE8.0") { 
    arrowMT = '0';
    isIe8 = true;
  }
  var arrowClickType = false;
  // 箭头
  $('.arrow-left, .arrow-right').on('mouseenter', function() {
    $(this).children().animate({'margin-top': arrowMT}, 500);
  })
  $('.arrow-left, .arrow-right').on('mouseleave', function(e) {
    $(this).children().animate({'margin-top': '0'}, 500);
  })
  $('.arrow-left, .arrow-right').on('click', function() {
    if (arrowClickType) {
      return false;
    }
    arrowClickType = true;
    setTimeout(function() {
      arrowClickType = false;
    }, 200);
    var idx = $(this).parents('.w1200').find('.st-tab-btn').children('.active').index();
    var arrow;
    var nuxtIdx = 0;
    if ($(this).hasClass('arrow-right')) {
      arrow = true;
    } else {
      arrow = false;
    }
    if ($(this).parents('.w1200').parent().hasClass('js-section1')) {
      anitFn(countFn(arrow, 1), 1, function() {
        $('.js-section1 .tab-box').removeClass('td-1');
      });
    }
    if ($(this).parents('.w1200').parent().hasClass('js-section2')) {
      anitFn(countFn(arrow, 2), 2, function() {
        $('.js-section2 .tab-box').removeClass('td-2');
      });
    }
    if ($(this).parents('.w1200').parent().hasClass('js-section3')) {
      anitFn(countFn(arrow, 3), 3);
    }
    if ($(this).parents('.w1200').parent().hasClass('js-section4')) {
      anitFn(countFn(arrow, 4), 4);
    }
    if ($(this).parents('.w1200').parent().hasClass('js-section5')) {
      anitFn(countFn(arrow, 5), 5, function() {
        $('.js-section5 .tab1-box .st-pic1').removeClass('td-3').addClass('td-1');
        $('.js-section5 .tab1-box .st-pic2').removeClass('td-4').addClass('td-2');
        $('.js-section5 .tab1-box .st-pic3').removeClass('td-5').addClass('td-3');
        $('.js-section5 .tab1-box .st-title').removeClass('td-6').addClass('td-4');
        $('.js-section5 .tab1-box .st-txt').removeClass('td-8').addClass('td-5');
      });
    }
  })

  function countFn(type, num) {
    var nuxtIdx = 0;
    if (type) {//右
      nuxtIdx = count['count' + num] + 1;
      if (nuxtIdx > 3) { nuxtIdx = 0; }
    } else {
      nuxtIdx = count['count' + num] - 1;
      if (nuxtIdx < 0) { nuxtIdx = 3; }
    }
    return nuxtIdx;
  }

  var swiperType1,swiperType2,swiperType3,swiperType4,swiperType5;
  var navTop = $('.js-nav-section').offset().top - 40;
  var h5NavTop = $('.js-h5-nav-section').offset().top - $('.js-h5-header').height();
  $(window).on('scroll', function(){
    var scrollTop = $(this).scrollTop();
    // 导航吸顶
    if(scrollTop >= navTop) {
      $('.js-nav-section').addClass('ceiling');
      $('.js-section1').css({'margin-top': '100px'});
    } else {
      $('.js-nav-section').removeClass('ceiling');
      $('.js-section1').css({'margin-top': '0'});
    }
    if(scrollTop >= h5NavTop){
      $('.js-h5-nav-section').addClass('ceiling');
      $('.js-s1-box').css({'margin-top': ($('.js-h5-nav-section').height() + 'px')});
    } else {
      $('.js-h5-nav-section').removeClass('ceiling');
      $('.js-s1-box').css({'margin-top': '0'});
    }
    // 导航+氢能动力 动画
    scrollAnitFn(1, scrollTop, 1, function() {
      // 氢能动力 小tab轮播
      !swiperType1 && swiperFn(1, function() {
        swiperType1 = true;
        $('.js-section1 .tab-box').removeClass('td-1');
      });
      $('.js-nav-section').addClass('anit-nav-section');
    }, true, 'js-nav-section', 'anit-nav-section');
    // 无塑海岸 动画
    scrollAnitFn(1, scrollTop, 2, function() {
      // 无塑海岸 小tab轮播
      !swiperType2 && swiperFn(2, function() {
        swiperType2 = true;
        $('.js-section2 .tab-box').removeClass('td-2');
      });
    });
    // MAKE KOL 动画
    scrollAnitFn(1, scrollTop, 3, function() {
      // MAKE KOL 小tab轮播
      !swiperType3 && swiperFn(3, function() {
        swiperType3 = true;
      });
    });
    // 情绪工业 动画
    scrollAnitFn(1, scrollTop, 4, function() {
      // 情绪工业 小tab轮播
      !swiperType4 && swiperFn(4, function() {
        swiperType4 = true;
      });
    });
    // 书籍特色 动画
    if(scrollTop > $('.js-book-bg').offset().top - 400 && !$('.js-book-bg').hasClass('anit-book-bg')) {
      $('.js-book-bg').addClass('anit-book-bg');
      $('.js-section5').addClass('anit-section5');
      $('.js-section5 .tab1-box').css({'display': 'block'}).animate({'opacity': '1'}, 200, function() {
        $(this).addClass('anit-section5-tab');
      })
      // 情绪工业 小tab轮播
      !swiperType5 && swiperFn(5, function() {
        swiperType5 = true;
      });
    }
    // 书籍内容结构 动画
    if(scrollTop > $('.js-section6').offset().top - 400 && !$('.js-section6').hasClass('anit-section6')) {
      $('.js-section6').addClass('anit-section6');
    }
    // 咨询 动画
    if(scrollTop > $('.js-section7').offset().top - 750 && !$('.js-section7').hasClass('anit-section7')) {
      $('.js-section7').addClass('anit-section7');
    }
    // H5 滚动
    scrollAnitFn(2, scrollTop, 1);
    scrollAnitFn(2, scrollTop, 2);
    scrollAnitFn(2, scrollTop, 3);
    scrollAnitFn(2, scrollTop, 4);
  });
  var swiper = {};
  var count = {};
  // 自动轮播
  function swiperFn(stnum, fn) {
    count['count' + stnum] = $('.section' + stnum + '-box .st-tab-btn li.active').index();
    swiper['swiper' + stnum] = setInterval(function() {
      count['count' + stnum]++;
      if (count['count' + stnum] > 3) { count['count' + stnum] = 0; }
      anitFn(count['count' + stnum], stnum, fn);
    }, 3500);
    $('.section' + stnum + '-box').on('mouseenter', function() {
      $('.section' + stnum + '-box .tab' + (count['count' + stnum] + 1) + '-box').animate({'opacity': '1'}, 200)
      clearInterval(swiper['swiper' + stnum]);
    })
    $('.section' + stnum + '-box').on('mouseleave', function() {
      clearInterval(swiper['swiper' + stnum]);
      swiper['swiper' + stnum] = setInterval(function() {
        count['count' + stnum]++;
        if (count['count' + stnum] > 3) { count['count' + stnum] = 0; }
        anitFn(count['count' + stnum], stnum, fn);
      }, 3500);
    })
  }
  var timeout1 = {};
  var timeout2 = {};
  // 小tab切换
  function anitFn(idx, stnum, fn) {
    clearTimeout(timeout1['timeout' + stnum]);
    clearTimeout(timeout2['timeout' + stnum]);
    count['count' + stnum] = idx;
    $(this).parent().addClass('active').siblings().removeClass('active');
    fn && fn();
    if (isIe8) {
      $('.js-section' + stnum + ' .tab' + (idx + 1) +'-box').siblings().animate({'opacity': '0'}, 200);
      $('.js-section' + stnum + ' .st' + stnum + '-tab-btn li:eq(' + idx +')').addClass('active').siblings().removeClass('active');
      $('.js-section' + stnum + ' .js-tab-btn').find('li:eq(' + idx +')').addClass('active').siblings().removeClass('active');
      $('.js-section' + stnum + ' .tab' + (idx + 1) +'-box').siblings().removeClass('anit-section' + stnum + '-tab').css({'display': 'none'});
      $('.js-section' + stnum + ' .tab' + (idx + 1) +'-box').css({'display': 'block'}).animate({'opacity': '1'}, 200, function() {
        $(this).addClass('anit-section' + stnum + '-tab');
      })
    } else {
      $('.js-section' + stnum + ' .tab' + (idx + 1) +'-box').siblings().animate({'opacity': '0'}, 200);
      timeout1['timeout' + stnum] = setTimeout(function() {
        $('.js-section' + stnum + ' .st' + stnum + '-tab-btn li:eq(' + idx +')').addClass('active').siblings().removeClass('active');
        $('.js-section' + stnum + ' .js-tab-btn').find('li:eq(' + idx +')').addClass('active').siblings().removeClass('active');
        timeout2['timeout' + stnum] = setTimeout(function() {
          $('.js-section' + stnum + ' .tab' + (idx + 1) +'-box').siblings().removeClass('anit-section' + stnum + '-tab').css({'display': 'none'});
          $('.js-section' + stnum + ' .tab' + (idx + 1) +'-box').css({'display': 'block'}).animate({'opacity': '1'}, 200, function() {
            $(this).addClass('anit-section' + stnum + '-tab');
          })
        }, 200)
      }, 200);
    }
  }
  // scroll
  function scrollAnitFn(type, scrollTop, stnum, fn, type2, class1, class2) {
    if (type == 1) {
      var b;
      if (type2) {
        b = scrollTop > $('.' + class1).offset().top - 400 && !$('.' + class1).hasClass(class2);
      } else {
        b = scrollTop > $('.js-section' + stnum).offset().top - 400 && !$('.js-section' + stnum).hasClass('anit-section' + stnum);
      }
      if(b) {
        fn && fn();
        $('.js-section' + stnum).addClass('anit-section' + stnum);
        $('.js-section' + stnum + ' .tab1-box').css({'display': 'block'}).animate({'opacity': '1'}, 200, function() {
          $(this).addClass('anit-section' + stnum + '-tab');
        })
      } else if (scrollTop > $('.js-section' + stnum).offset().top -140 - 30 && scrollTop < $('.js-section' + stnum).offset().top -140 + 30) {
        $('.js-nav-section li:eq(' + (stnum - 1) + ')').addClass('active').siblings().removeClass('active');
      }
    } else if (type == 2) {
      if (scrollTop > $('.js-s' + stnum + '-box').offset().top - ($('.js-h5-nav-section').height() + $('.js-h5-header').height()) - 50 && !$('.js-h5-nav-section').find('li:eq(' + (stnum - 1) + ')').hasClass('active')) {
        $('.js-h5-nav-section').find('li:eq(' + (stnum - 1) + ')').addClass('active').siblings().removeClass('active');
      }
    }
  }
  // 缓冲函数
  function easeout(position, destination, rate, callback) {
    if (position === destination || typeof destination !== 'number') {
      return false;
    }
    destination = destination || 0;
    rate = rate || 2;
    // 不存在原生`requestAnimationFrame`，用`setTimeout`模拟替代
    if (!window.requestAnimationFrame) {
      window.requestAnimationFrame = function (fn) {
        return setTimeout(fn, 17);
      }
    }
    var step = function () {
      position = position + (destination - position) / rate;
      if (Math.abs(destination - position) < 1) {
        callback(destination, true);
        return;
      }
      callback(position, false);
      requestAnimationFrame(step);
    };
    step();
  }
  // 滚动至
  function scrollTopSmooth(position) {
    // 当前滚动高度
    var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    easeout(scrollTop, position, 5, function (val) {
      window.scrollTo(0, val);
    });
  }
  function scrollLeftSmooth(el, position) {
    if (!el) {
      return false;
    }
    var scrollLeft = el.scrollLeft;
    easeout(scrollLeft, position, 5, function (val) {
      $(el).scrollLeft(val)
    });
  }
})