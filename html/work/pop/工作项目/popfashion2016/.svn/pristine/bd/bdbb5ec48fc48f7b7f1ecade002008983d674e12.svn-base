$(function(){
	// POP商城首页轮播
	var $mall_banner = $(".mall_banner");
	var $li = $(".mall_banner li");
	var $lilen = $li.length;
	var $banner_nav = $(".banner-nav span");
	var num =0;
	var timer;
	$li.eq(0).show();
	function popMall(){
		num++;
		if (num >= $lilen) {
			num =0;
		}
		$li.eq(num).stop().fadeIn(800).siblings().stop().fadeOut(800);
		$banner_nav.eq(num).addClass('selected').siblings().removeClass('selected');
	}
	timer = setInterval(popMall,5000);
	$banner_nav.on('click',function(){
		clearInterval(timer);
		var index = $(this).index();
		$li.eq(index).stop().fadeIn(800).siblings().stop().fadeOut(800);
		$banner_nav.eq(index).addClass('selected').siblings().removeClass('selected');
		num = index;
	});
	$mall_banner.on('mouseenter mouseleave', function(e){
		if(e.type == 'mouseenter'){
			clearInterval(timer);
		}else{
			timer = setInterval(popMall,5000);
		}
	});

	// 搜索框
	var search = $(".search_box");
	var defaultValue = '请输入关键词';
	search.on('focus', function(){
		if(search.val() == defaultValue){
			search.val('').css('color','#333');
		}
	});
	search.on('blur', function(){
		if($.trim(search.val()) == ''){
			search.val(defaultValue).css('color','#999');
		}else if(search.val() != '' || search.val() != defaultValue){
			search.css('color','#333');
		}
	});
	var js_search = $(".js_search");
	var js_val = js_search.val();
	js_search.on('focus', function(){
		js_search.val('');
	});
	js_search.on('blur', function(){
        var newValue = $(this).val();
        if (newValue == '') {
            js_search.val(js_val);
        }
	});

	// 侧边导航
	if($(".professional_book").length > 0) {
		var sidebar_nav = $(".sidebar_nav");
		var lis = sidebar_nav.find('li.sidebar');
		var indexdiv = $(".indexdiv");
		var offsetTop = $(".professional_book").offset().top;
		var bOffsetLeft = $(".professional_book").offset().left;
		$(window).scroll(function(){
			var scrollTop = $(window).scrollTop();
			for(var i=0; i<indexdiv.length; i++){
				var divHei = indexdiv.eq(i).offset().top;
				if(scrollTop >= offsetTop){
					sidebar_nav.css({'position':'fixed','top':'40px','left':bOffsetLeft-68});
				}else{
					sidebar_nav.css({'position':'absolute','top':'406px','left':'-68px'});
				}
				if(scrollTop > divHei -40){
					lis.eq(i).addClass('cur').siblings('li').removeClass('cur');
				}else{
					lis.eq(i).removeClass('cur')
				}
			}
		});
		lis.on('click', function(){
			var index = $(this).index();
			var divHei = indexdiv.eq(index).offset().top;
			$('html,body').animate({scrollTop : divHei},300);
		});
	}
	// 回到顶部
	$(".sidebar_4").on('click',function(){
		$("html,body").animate({scrollTop: 0}, 1000);
	});

	// 图片放大镜
	var img = $(".big_img img");
	if(img.length > 0){
		img.imagezoom();
	}
	var smallImg = $(".small_img li");
	var smallLen = smallImg.length;
	var smallWidth = smallImg.outerWidth(true)
	var smallUl = $(".small_img ul");
	var ulWidth = smallWidth*smallLen;
	smallImg.eq(0).addClass('cur');
	$(".small_img ul").width(ulWidth);
	if (smallImg.length <= 4) {
		$(".s_btn").hide();
	}
	smallImg.on('click', function(){
		var self = $(this);
		var Ssrc = $(this).find('img').attr('src');
		self.addClass('cur').siblings().removeClass('cur');
		$(".big_img img").attr('src', Ssrc);
	});
	var l_btn = $(".left_switch");
	var r_btn = $(".right_switch");
	var i = 0;
	r_btn.on('click', function(){
		if (i < smallLen-4){
			i++;
			smallUl.animate({left : -smallWidth*i},300);
		}

	});
	l_btn.on('click', function(){
		if(i >=1){
			i--;
			smallUl.animate({left : -smallWidth*i},300);
		}
	});

	// 下拉
	$(".choose_list").on('mouseenter mouseleave',function(e){
		if(e.type == 'mouseenter'){
			$(".md_list").stop().slideDown(200);
		}else{
			$(".md_list").stop().slideUp(50);
		}

	});
	// 微信下单
	$(".js-wechat_div").on('mouseenter mouseleave', function(e){
		if (e.type == 'mouseenter'){
			$(this).find(".js-rq_code").show();
			$(this).addClass('cur');
		}else{
			$(this).find(".js-rq_code").hide();
			$(this).removeClass('cur');
		}		
	});
	if ($(".detail_bottom").length > 0) {
		var deTop = $(".detail_bottom").offset().top;
		$(window).scroll(function(){		
			var scrollTop = $(window).scrollTop();
			if(scrollTop >= deTop - 55){
				$(".header_fix_bar").show();
			}else{
				$(".header_fix_bar").hide();
			}
		});
	}

	// 微信二维码
	var weixin_rq = $(".weixin_rq");	
	$(".auto_weixin").on('click', function(e){
		var w_display = weixin_rq.css('display');
		if (w_display == 'none') {
			weixin_rq.show();
		}else if(w_display == 'block'){
			weixin_rq.hide();
		}
	});
	$('.wx_close').on('click', function(){
		weixin_rq.hide();
	});
	
    // 详情页价格切换
    var priceTypes = $('.m_choose_type').find('ul').find('li');
    var priceSpan = $('span.price_num');
    if (priceTypes.length > 1) {
        priceTypes.on('click', function() {
            var self = $(this);
            self.siblings().removeClass('cur');
            self.addClass('cur');
            priceSpan.html("<em>￥</em>" + self.data('price'))

        });
    }

    // 二级列表页搜索
    var rootLink = $("#rootLink").data('link');
    function getKeySuffix(key){
        return "?key=" + encodeURIComponent(encodeURIComponent(key)).replace(/\'/g,'%27');
    }
    $("#myserach").on('click', function() {
        var key = $("#myserach-input").val();
        if ($.trim(key) === ''){
            return false;
        }
        location.href = rootLink + getKeySuffix(key);
    });
    $("#myserach-input").on('keypress',function(event){
        var e = event || window.event;
        var code = e.keyCode || e.which;
		var key = $(this).val();
        if(code == 13){
			if ($.trim(key) === ''){
				return false;
			}
			location.href = rootLink + getKeySuffix(key);
        }
    });
});
