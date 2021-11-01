
$(function(){
	var index = 0;
	var timer = null;
	var $imgObj = $('.bannerbox li');
	var $pointObj = $('.jiaobiao li');
	var pointObjLen = $pointObj.length;
	$imgObj.eq(0).show();

	// 轮播	
	function carousel() {
		index++;
		if (index >= pointObjLen) {
			index = 0;
		}
		$imgObj.eq(index).stop(true,true).fadeIn(800).siblings().stop(true,true).fadeOut(800);
		$pointObj.eq(index).addClass('cur').siblings().removeClass('cur');
	}
	timer = setInterval(carousel, 5000);
	// 鼠标经过
	$imgObj.on('mouseenter mouseleave', function(e){
		handerCarousel(this, e);
	});
	$pointObj.on('mouseenter mouseleave', function(e){
		clearInterval(timer);
		handerCarousel(this, e);
	});
	function handerCarousel(obj, e) {
		var e = e || window.event;
		if (e.type == 'mouseenter') {
			clearInterval(timer);
			index = $(obj).index();
			$imgObj.eq(index).stop(true,true).fadeIn(800).siblings().stop(true,true).fadeOut(800);
			$pointObj.eq(index).addClass('cur').siblings().removeClass('cur');
		} else if (e.type == 'mouseleave') {
			timer = setInterval(carousel, 5000);
		}
	}


	/*内页左侧列表*/
	$(".searInputbox").focus(function() {
		var $text=$(this).val();
		if($text==this.defaultValue){
			$(this).val("");
		}
	});
	$(".searInputbox").blur(function() {
		var $text=$(this).val();
		if($text==""){
			$(this).val(this.defaultValue);
		}
	});

	$(".leftc_List li").click(function(){
		$(this).addClass('cur').siblings('li').removeClass('cur');
	});
	
	$(".leftc_List li:last").find("a").css("border-bottom","none");

	// /*右侧排序*/
	// var paixu=$(".paixu li");
	// $("body").on('mouseenter mouseleave', '.paixu li' ,function(e){
	// 	if(e.type == 'mouseenter'){
	// 		$(this).addClass('hover').find('.showbox').stop().css("visibility","visible");
	// 	} else{
	// 		$(this).removeClass('hover').find('.showbox').stop().css("visibility","hidden");
	// 		$(this).find(".js-filter-list").show().siblings("ul").hide();
	// 		$(this).find(".js-search-brand-area").val("搜索热门品牌名").blur();
	// 	}
	// });

	$('.brand .all_pp .all_con .s_leibie li:last').css('border','none');
	
	$(".showdiv a").click(function(){
		$(this).parent('div').siblings('span').text($(this).text());
	});


	/*时间范围*/	
	$(".category_show a").click(function(){
		$(this).addClass('curclick').siblings('a').removeClass('curclick');
	});
	
	$(".js-s-time-sort").on('mouseenter mouseleave',function(e){
		if(e.type =="mouseenter"){
			$(this).find('div').css('display','block');
		}else{
			$(this).find('div').css('display','none');
		}		
	});
	
	
	$(".updateTime").on('mouseenter mouseleave',function(e){
		if(e.type=="mouseenter"){
			$(this).find('div').css('display','block');
		}else{
			$(this).find('div').css('display','none');
		}		
	});
	$(".chotime a").click(function(){
		$(".updateTitle i").text($(this).text());
		$(".chotime").css('display','none');
	});

	$('.contentHolder').mCustomScrollbar({
        theme:'dark-3',
        mouseWheel:{ 
            enable:true,
            preventDefault: true 
        }
    });

	// 只看收藏
	$(".only_info").on('click',function(){
		$(this).hide();
	});

	// 左侧栏目鼠标经过出现栏目介绍
	$(".leftc_List li").hover(function(){
		$(this).find('.sub_lanmu').stop(true, true).fadeIn(400);
	},function(){
		$(this).find('.sub_lanmu').stop(true, true).fadeOut(200);
	});

	//品牌隐藏重新计算位置
	// $('.showbox').each(function(){
	// 	var $self = $(this);
	// 	var $parent=$self.parent('li');
	// 	var selfWid=$self.outerWidth(true);
	// 	var rightWidth=parseInt(document.body.offsetWidth) -parseInt($parent.offset().left);
	// 	var leftWidth = $parent.offset().left;
	// 	var liWid=$(".paixu li").width();
	// 	if(selfWid > rightWidth){
	// 		$self.css('left', -selfWid+liWid+1);
	// 	}else if(selfWid > leftWidth){
	// 		$self.css('left', '-1px');
	// 	}
	// });
	// 无筛选条件
	var rightc_s=$(".rightc_s");
	if(rightc_s.length==0){
		$(".rightc_s_t").css('border','none');
	}

	//趋势页,品牌库列表hover状态
	function showdiv(classname){ 
		var self=$(classname);
		var sel=self.find('li');
		sel.hover(function() {
			$(this).find('.showdiv').stop().fadeIn(100);
		}, function() {
			$(this).find('.showdiv').stop().fadeOut(200);
		});
	}
	// showdiv('.trend');
	// showdiv('.updata_list');

	

  // 列表页左侧高度
  var page_timer1=null;
  setTabHeight(20);
  function setTabHeight(times){
  	clearTimeout(page_timer1);
  	var leftNav=$(".left_nav");
	var leftNavH=leftNav.height();
	var rightNav=$(".right_nav").height();
  	if(times>0){
		times--;
		page_timer1=setTimeout(function(){
			setTabHeight(times);
		},500);
		
  	}else{
  		if(leftNavH < rightNav){
			leftNav.height(rightNav);
		}
  	}
  };

  

  // 品牌hover

  // hover延迟
  (function($){
    $.fn.hoverDelay = function(options){
        var defaults = {
            hoverDuring: 100,
            outDuring: 100,
            hoverEvent: function(){
                $.noop();
            },
            outEvent: function(){
                $.noop();    
            }
        };
        var sets = $.extend(defaults,options || {});        
        return $(this).each(function(){
        	var hoverTimer, outTimer;
        	var that=this;
            $(this).hover(function(){
                clearTimeout(outTimer);
                hoverTimer = setTimeout(
					function(){sets.hoverEvent.apply(that)},
					sets.hoverDuring
				);
            },function(){
                clearTimeout(hoverTimer);
                outTimer = setTimeout(
					function(){sets.outEvent.apply(that)},
					sets.outDuring
				);
            });    
        });
    }      
   })(jQuery);  

   $(".updata_list li").hoverDelay({
   	  hoverEvent:function(){
   	  	$(this).find('.showxq').stop(true).fadeIn(100);
   	  	$(this).find('.showxq .span_l').animate({'height':'111px','width':'108px'},300)
   	  	$(this).find('.showxq .span_r').animate({'height':'111px','width':'108px'},300)
   	  },
   	  outEvent:function(){
   	  	$(this).find('.showdiv').stop(true).fadeOut(200);
   	  	$(this).find('.showxq .span_l').animate({'height':'0','width':'0'},0)
   	  	$(this).find('.showxq .span_r').animate({'height':'0','width':'0'},0)
   	  }
   });

   // 品牌列表图片高度改变
   var listHei=$(".updata_list").height() -8;
   var brandHei=$(".brand_img");   
   var brandSpan=$(".brand_img span");
   brandSpan.height(listHei);
   brandHei.css('height',listHei);

    // 鼠标滚动定位 带头部广告的页面的控制
	// if ($('.left_nav').length || $('.updata_pp').length  || $(".js-select-label-contain").length || $('.js-report-section').length ) {
	// 	if($('.left_nav').length){
	// 		var chei = $('.left_nav').offset().top;
	// 	}else if($('.updata_pp').length){
	// 		var chei = $('.updata_pp').offset().top;
	// 	}else if($(".js-select-label-contain").length){
	// 		var chei = $(".js-select-label-contain").offset().top;
	// 	}else if( $('.js-report-section').length ){
	// 		var chei = $('.js-report-section').offset().top;
	// 	}
		
	// 	var hei=chei-40;
	// 	var direct = true;
	// 	var scrollFunc=function(e){
	// 		if( $('.js-report-section').length ){
	// 			hei = $('.js-report-section').offset().top-40;
	// 		}
			
	// 		var e = e || window.event;
	// 		var scrollTop = $(window).scrollTop();

	// 		// IE/Opera/Chrome
	// 		if(e.wheelDelta){
	// 			// 向下滚
	// 			if (e.wheelDelta < 0) {
	// 				if (scrollTop < hei && direct) {
	// 					$("html,body").animate({scrollTop:hei},200);
	// 					direct = false;
	// 				}
	// 			} else {
	// 				if (scrollTop < hei) {
	// 					direct = true;
	// 				}
	// 			}
	// 		}
	// 		// Firefox
	// 		else if(e.detail){
	// 			// 这是什么鬼...向下滚是大于0
	// 			if (e.detail > 0) {
	// 				if (scrollTop < hei && direct) {
	// 					$("html,body").animate({scrollTop:hei},200);
	// 					direct = false;
	// 				}
	// 			} else {
	// 				if (scrollTop < hei) {
	// 					direct = true;
	// 				}
	// 			}
	// 		}
	// 	}
	// 	// FF
	// 	if(document.addEventListener){
	// 		document.addEventListener('DOMMouseScroll',scrollFunc,false);
	// 	}
	// 	//W3C   IE/Opera/Chrome/Safari
	// 	document.onmousewheel = scrollFunc;
	// }	

	// 栏目介绍
	var introCont=$(".intro_cont");
	$(".lanmu_intro").on('mouseenter mouseleave' , function (e) {
		if(e.type == 'mouseenter'){
			introCont.stop(true,true).slideDown(200);
		} else {
			introCont.stop(true,true).slideUp(200);
		}
	});

	var left = $('.total_search').length ? $('.total_search').offset().left : 0;
	// 其他栏目
	var wid=$('.a_guide .allStep1').width();
	$('.a_guide .allStep1').css('left',left-wid+190);
	// 款式
	var wid1=$(".style_guide .styleStep3").width();
	$(".style_guide .styleStep3").css('left',left-wid1+312);
	// 品牌
	var wid2=$('.brand_guide .brandStep1').width();
	$(".brand_guide .brandStep1").css('left',left-wid2+312);
	// 灵感
	var wid3=$('.insp_guide .inspStep1').width();
	$('.insp_guide .inspStep1').css('left',left-wid3+312);

	// 品牌详情页款式库高度
	var brandul=$(".con_width_floor3");
	var brandli=$(".con_width_floor3 .styles li");
	var conwidth=$(".con_width").width();
	if(conwidth == 1200){
		if(brandli.length < 6){
			brandul.css('height','388px');
		}else{
			brandul.css('height','708px');
		}
	}else if(conwidth ==1500){
		if(brandli.length < 8){
			brandul.css('height','388px');
		}else{
			brandul.css('height','708px');
		}
	}

    //点击下载本文PDF 判断用户权限
    $('body').on('click', '.J_DOWNLOAD_FILE, .dl_jpg, .dl_ai', function () {
        return oCommon.downloadPrivilege();
    });

    // 品牌详情
    $(".js-brand-description").each(function(){
    	var report_str =$(this).text() || '';
	    var report_new_str = pop_fashion_global.fn.cutByWidth(report_str,496,12)
	    $(this).text(report_new_str);
    });
    // 图案专题
    $(".js-topic-description").each(function(){
    	var report_str =$(this).text() || '';
    	var wid =$(".con_width").width();
    	if(wid == 1200){
    		var report_new_str = pop_fashion_global.fn.cutByWidth(report_str,528,12)
    	}else{    		
	    	var report_new_str = pop_fashion_global.fn.cutByWidth(report_str,528,12)
    	}
	    $(this).text(report_new_str);
    });

});