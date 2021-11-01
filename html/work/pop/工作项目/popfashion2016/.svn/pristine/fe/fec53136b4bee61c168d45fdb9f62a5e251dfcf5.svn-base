$(function(){
	// 下载包隔行变色
	$(".xiazai_table tr:even").addClass('bg');
	$(".xiazai_table tr:first").removeClass('bg');

	//创建工作台弹框
	$("#creatTab").on("click", function () {
		$("#tableName").val('');
		$("#tableStatus").val('');
		$("#workBenchId").val('');
		$(".shadow_black,.pop_work").show();
	});

	/*
	 *编辑工作台弹框
	 *flag 1:单张页面 否则 工作台页面
	 */
	$(".work_tab .edit_btn").each(function () {
		$(this).on('click', function (ev) {
			$(".pop_work").find("h1").text("编辑工作台").end().find(".saveBtn").text("保存");
			$("#tableName").val('');
			$("#tableStatus").val('');
			$("#workBenchId").val('');
			if ($(this).data('flag') == 1)
			{
				$("#tableName").val($(this).data('name'));
				$("#tableStatus").val($(this).data('describe'));
				$("#workBenchId").val($(this).data('id'));
			}
			else
			{
				var info = $(this).parents('li');
				$("#tableName").val(info.data('name'));
				$("#tableStatus").val(info.data('describe'));
				$("#workBenchId").val(info.data('id'));
			}
			$(".shadow_black,.pop_work").show();
			return false;
		});
	});
	//弹框关闭
	$(".pop_work .closebtn").on("click", function () {
		$(this).closest(".pop_work").hide();
		$(".shadow_black").hide();
	});


	// 账号设置
	$(".bd a").click(function(){
		$(this).addClass('cur').siblings().removeClass('cur');
	});



// 交叉页轮播
	 var contain=$(".contain");
	 var lis=$(".contain ul li");     
     var btnSpan=$(".buttons span");
     var btnleft=$(".lunbbtn_left");
     var btnright=$('.lunbbtn_right');
     var btnlen=$(".buttons span").length;
     var timer;
     var num=0;
     lis.eq(0).show();
     btnSpan.eq(0).addClass('cur');
     timer=setInterval(crossPageLunbo,5000);
     function crossPageLunbo(){
     	num++;
     	if(num == btnlen){
     		num=0;
     	}
     	btnSpan.eq(num).addClass('cur').siblings('span').removeClass('cur');
     	lis.eq(num).stop(true,true).fadeIn(200).siblings('li').stop(true,true).fadeOut(200);
     }
     function crossPageleft(){     	     	
     	if(num==0){
     		num=btnlen;
     	}
     	num--;
     	//console.log(num);
     	btnSpan.eq(num).addClass('cur').siblings('span').removeClass('cur');
     	lis.eq(num).stop(true,true).fadeIn(200).siblings('li').stop(true,true).fadeOut(200);
     }
     btnSpan.hover(function(){
     	clearInterval(timer);
     	var index=$(this).index();
     	$(this).addClass('cur').siblings('span').removeClass('cur');
     	lis.eq(index).stop(true,true).fadeIn(300).siblings('li').stop(true,true).fadeOut(300);
     	num=index;
     });
     contain.hover(function(){
     	clearInterval(timer);
     },function(){
     	timer=setInterval(crossPageLunbo,5000);
     });
     btnleft.on('click',function(){
     	clearInterval(timer);
     	crossPageleft();
     });
     btnright.on('click',function(){     	
     	clearInterval(timer);
     	crossPageLunbo();
     });


     // 交叉页 1200 热搜数量为3
     function searchHeight(){
          if($(".con_width").width() == 1200){
               $(".hot_search").css('height', '101px');
          }else if($(".con_width").width() == 1500){
               $(".hot_search").css('height', '143px');
          }  
     }
     searchHeight();
     $(window).resize(function() {
          searchHeight();
     });
     
	 // 交叉页
	 var left = 0;
	 if($('.total_search').length>0){
		left = $('.total_search').offset().left;
	 }
     var wid4=$('.mu_guide .muStep1').width();
     $('.mu_guide .muStep1').css('left',left-wid4+312);    
     

});