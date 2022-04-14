(function(doc, win) {
	var docEl = doc.documentElement,
		resizeEvt = "orientationchange" in window ? "orientationchange" : "resize",
		recalc = function() {
			var clientWidth = docEl.clientWidth;
			if (!clientWidth) return;
			docEl.style.fontSize = clientWidth / 7.5 + "px";
      console.log(document.documentElement.style.fontSize)
		};
	if (!doc.addEventListener) return;
	win.addEventListener(resizeEvt, recalc, false);
	doc.addEventListener("DOMContentLoaded", recalc, false);
})(document, window);

$(function() {
  var regexEnum = {
      username:"^[a-zA-Z0-9_@\\-&\u4e00-\u9fa5]{2,20}$",
      password: '^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$',
      intege1: "^[1-9]\\d*$",
      num1: "^[1-9]\\d*|0$",
      email: "^\\w+((-\\w+)|(\\.\\w+))*\\@[A-Za-z0-9]+((\\.|-)[A-Za-z0-9]+)*\\.[A-Za-z0-9]+$",
      url: "^http[s]?:\\/\\/([\\w-]+\\.)+[\\w-]+([\\w-./?%&=]*)?$",
      zipcode: "^\\d{6}$",
      mobile: "^1\\d{10}$",
      notempty: "^\\S+$",
      letter: "^[A-Za-z]+$"
  }
  var def = {
    category: '',
    name: '',
    mobile: '',
    company: ''
  }
  var categoryIpt = $('#categoryIpt')
    , nameIpt = $('#nameIpt')
    , mobileIpt = $('#mobileIpt')
    , companyIpt = $('#companyIpt');
  var categoryTest = function() {
    if (!def.category) {
      categoryIpt.parent().siblings('.info').show();
      return false;
    } else {
      categoryIpt.parent().siblings('.info').hide();
      return true;
    }
  }
  var nameTest = function() {
    def.name = nameIpt.val();
    if (!def.name || !RegExp(regexEnum.username, 'ig').test(def.name)) {
      nameIpt.parent().siblings('.info').show();
      return false;
    } else {
      nameIpt.parent().siblings('.info').hide();
      return true;
    }
  }
  var mobileTest = function() {
    def.mobile = mobileIpt.val();
    if (!def.mobile || !RegExp(regexEnum.mobile, 'ig').test(def.mobile)) {
      mobileIpt.parent().siblings('.info').show();
      return false;
    } else {
      mobileIpt.parent().siblings('.info').hide();
      return true;
    }
  }
  nameIpt.blur(nameTest);
	var	notNumIdx = 0;
  mobileIpt.blur(mobileTest).on("input", function(e) {
    var val = this.value;
    var len = this.value.length;
    if (len == 1) {
      notNumIdx = 0;
    }
    if (isNaN(Number(this.value))) {
      if (!notNumIdx) {
        notNumIdx = len;
      }
      $(this).val(val.substr(0, notNumIdx - 1));
    } else {
      notNumIdx = 0;
    }
  });
  companyIpt.blur(function() {
    def.company = $(this).val();
  });
  $('body').click(function() {
    $('.js-options').slideUp();
    $('.js-arrow').removeClass('on');
  })
  $('.js-select').on('click', function(e) {
    var e = e || window.event;
    e.stopPropagation();
    $('.js-options').slideToggle();
    $('.js-arrow').toggleClass('on');
  })
  $('.js-options>li').on('click', function(e) {
    var e = e || window.event;
    e.stopPropagation();
    categoryIpt.text($(this).text()).addClass('on');
    def.category = $(this).data().val;
    $('.js-options').slideToggle();
    $('.js-arrow').toggleClass('on');
    if (!categoryTest()) {
      categoryIpt.removeClass('on');
    }
  })
  $('.js-submit').on('click', function() {
    if (!categoryTest() || !nameTest() || !mobileTest()) {
      return;
    }
    $.ajax({
      url: '/member/vipapply/',
      type: 'post',
      dataType: 'json',
      data: {
        purchased: def.category,
        username: def.name,
        phone: def.mobile,
        company: def.company,
        type: 'pc'
      },
      success: function(res) {
        if (res.ret == 0) {
          toast('提交成功，我们会尽快处理您的试用申请', function() {
            window.history.go(-1);
          });
        } else {
          toast('提交失败，请稍后再试');
          console.log(res.msg);
        }
      }
    })
  })
  function toast(text, fn) {
    $('.js-toast').text(text).show();
    var timeout = setTimeout(function() {
      $('.js-toast').hide();
      clearTimeout(timeout);
      fn && fn();
    }, 3000);
  }

  // 咨询
  $('.js-vip-btn').on('click', function() {
    $('.js-advisory-alert-bg').show();
    $('.js-advisory-alert').show();
  });
  $('.js-adv-close').on('click', function() {
    $('.js-advisory-alert-bg').hide();
    $('.js-advisory-alert').hide();
  });
  $('.js-adv-ul').on('click', 'li', function() {
    $(this).addClass('on').siblings().removeClass('on');
    var idx = $(this).data().idx;
    $('#qqwpa-btn' + idx).show().siblings().hide();
  });
  BizQQWPA.addCustom([{
    aty: '0',
    nameAccount: '800030036',
    selector: 'qqwpa-btn1'
  },{
    aty: '0',
    nameAccount: '800036829',
    selector: 'qqwpa-btn2'
  },{
    aty: '0',
    nameAccount: '800067136',
    selector: 'qqwpa-btn3'
  }]);

  // H5
  if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
    $('.js-h5-btns').hide();
    $('.h5-box>.info').hide();
    $('.h5-box>.hline').hide();
    $('.h5-box').show();
  } else if (/(Android)/i.test(navigator.userAgent)) {
    $('.h5-box>.info').show();
    $('.h5-box>.hline').show();
    $('.h5-box').show();
  } else {
    // pc
  };

  var h5def = {
    categoryName: '',
    category: '',
    name: '',
    mobile: '',
    company: ''
  };
  var h5CategoryIpt = $('#h5-categoryIpt')
    , h5NameIpt = $('#h5-nameIpt')
    , h5MobileIpt = $('#h5-mobileIpt')
    , h5CompanyIpt = $('#h5-companyIpt');

  var h5CategoryTest = function() {
    if (!h5def.category) {
      toast('请输入您的主营品类');
      return false;
    } else {
      return true;
    }
  }
  var h5NameTest = function() {
    h5def.name = h5NameIpt.val();
    if (!h5def.name || !RegExp(regexEnum.username, 'ig').test(h5def.name)) {
      toast('请输入您的姓名');
      return false;
    } else {
      return true;
    }
  }
  var h5MobileTest = function() {
    h5def.mobile = h5MobileIpt.val();
    if (!h5def.mobile || !RegExp(regexEnum.mobile, 'ig').test(h5def.mobile)) {
      toast('请输入您的电话');
      return false;
    } else {
      return true;
    }
  }
  var h5BbtnTest = function() {
    if (h5def.category && h5def.name && h5def.mobile) {
      $('.js-h5-submit').addClass('on');
    } else {
      $('.js-h5-submit').removeClass('on');
    }
  }

  h5CompanyIpt.blur(function() {
    h5def.company = $(this).val();
  }).on('input', function() {
    if (this.value) {
      $(this).addClass('on');
    } else {
      $(this).removeClass('on');
    }
  });
  h5NameIpt.blur(h5NameTest).on('input', function() {
    h5def.name = this.value;
    if (this.value) {
      $(this).addClass('on');
    } else {
      $(this).removeClass('on');
    }
    h5BbtnTest();
  });
	var	h5notNumIdx = 0;
  h5MobileIpt.blur(h5MobileTest).on("input", function(e) {
    var val = this.value;
    var len = this.value.length;
    h5def.mobile = val;
    if (len == 1) {
      h5notNumIdx = 0;
    }
    if (isNaN(Number(this.value))) {
      if (!h5notNumIdx) {
        h5notNumIdx = len;
      }
      h5def.mobile = val = val.substr(0, h5notNumIdx - 1);
      $(this).val(val);
    } else {
      h5notNumIdx = 0;
    }
    if (val) {
      $(this).addClass('on');
    } else {
      $(this).removeClass('on');
    }
    h5BbtnTest();
  });

  // 提交
  $('.js-h5-submit').on('click', function() {
    if (!h5CategoryTest() || !h5NameTest() || !h5MobileTest()) {
      return;
    }
    $.ajax({
      url: '/member/vipapply/',
      type: 'post',
      dataType: 'json',
      data: {
        purchased: h5def.category,
        username: h5def.name,
        phone: h5def.mobile,
        company: h5def.company,
        type: 'h5'
      },
      success: function(res) {
        if (res.ret == 0) {
          $('.js-h5-comfirm-alert').show();
        } else {
          toast('提交失败，请稍后再试');
          console.log(res.msg);
        }
      }
    })
  });

  $('.js-comfirm-close').on('click', function() {
    $('.js-h5-comfirm-alert').hide();
    window.history.go(-1);
  })

  // qq咨询
  $('.js-h5-qqbtn').on('click', function() {
    $('.js-h5-qqwpa-box').show();
  });

  $('.js-h5-qqwpa-close').on('click', function() {
    $('.js-h5-qqwpa-box').hide();
  });

  $('.js-h5-qqwpa-ul').on('click', 'li', function() {
    $(this).addClass('on').siblings().removeClass('on');
    var idx = $(this).data().idx;
    $('#h5-qqwpa-btn' + idx).show().siblings().hide();
  });
  BizQQWPA.addCustom([{
    aty: '0',
    nameAccount: '800030036',
    selector: 'h5-qqwpa-btn1'
  },{
    aty: '0',
    nameAccount: '800036829',
    selector: 'h5-qqwpa-btn2'
  },{
    aty: '0',
    nameAccount: '800067136',
    selector: 'h5-qqwpa-btn3'
  }]);

  // 选择品类
  h5CategoryIpt.on('click', function() {
    $('.js-set-category-box-bg').show();
    $('.js-set-category-box').addClass('on');
  });

  $('.js-sel-cancel').on('click', function() {
    $('.js-set-category-box-bg').hide();
    $('.js-set-category-box').removeClass('on');
  });

  $('.js-sel-comfirm').on('click', function() {
    if (!h5def.categoryName) {
      h5def.categoryName = '服装';
    }
    if (!h5def.category) {
      h5def.category = '1';
    }
    $('.js-set-category-box-bg').hide();
    h5CategoryIpt.text(h5def.categoryName).addClass('on');
    $('.js-set-category-box').removeClass('on');
    h5BbtnTest();
  });

  var touchData = {
    start: 0,
    move: 0,
    startop: 0,
    endtop: 0,
  };
  var itemH = document.documentElement.clientWidth / 7.5 * 1.2;
  var len = $('.js-set-list>ul>li').length;
  $('.js-set-category').on('touchstart', function() {
    var e = window.event;
    var touch = e.targetTouches[0];
    touchData.start = touch.pageY;
    touchData.startop = parseInt($('.js-set-list>ul').css('top'));
  }).on('touchmove', function() {
    var e = window.event;
    var touch = e.targetTouches[0];
    touchData.move = touch.pageY;
    var distance = touchData.move - touchData.start;
    touchData.endtop = touchData.startop + distance;
    $('.js-set-list>ul').css('top', touchData.endtop + 'px');
  }).on('touchend', function() {
    var count = Math.round(touchData.endtop / itemH);
    var top = count * itemH;
    if (top >= 0) {
      top = 0;
      count = 0;
    } else if (top <= -itemH * (len - 1)) {
      top = -itemH * (len - 1);
      count = -(len - 1);
    }
    $('.js-set-list>ul').animate({
      top: top + 'px'
    }, 200, '', function() {
      var idx = Math.abs(count);
      var el = $('.js-set-list>ul>li').eq(idx);
      h5def.categoryName = el.text();
      h5def.category = el.data().val;
    });
  });
})