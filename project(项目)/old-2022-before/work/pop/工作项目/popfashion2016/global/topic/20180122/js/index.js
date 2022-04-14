$(function(){	
	var def={
		img_path:page_img_path_str+"/global/topic/",
		list_ele:$(".js-review-list"),
		history_list:[
			{"id":10,"url":"/topic/20171211/","title":"2019春夏大趋势预测发布: 重置","src":"common/images/re10.jpg","alt":"2019春夏大趋势预测发布重置","class":"review-hover10"},
			{"id":9,"url":"/topic/20171101/","title":"上海订货会","src":"common/images/re9.jpg","alt":"图案专栏","class":"review-hover9"},
			{"id":8,"url":"/topic/20170930/","title":"一键获取PV展矢量花型","src":"common/images/re8.jpg","alt":"图案专栏","class":"review-hover8"},
			{"id":7,"url":"/topic/20170915/","title":"日本潮流实战情报","src":"common/images/re7.jpg","alt":"图案专栏","class":"review-hover7"},
			{"id":6,"url":"/topic/20170830/","title":"联名玩转潮流","src":"common/images/re6.png","alt":"图案专栏","class":"review-hover6"},
			{"id":5,"url":"/topic/20170802/","title":"独立设计师季","src":"common/images/re5.png","alt":"图案专栏","class":"review-hover5"},
			{"id":4,"url":"/topic/20170703/","title":"POP独家带来，最权威的色彩趋势--<br />新生","src":"common/images/re-4.jpg","alt":"图案专栏","class":"review-hover0"},
			{"id":3,"url":"/topic/20170602/","title":"图案专栏重磅来袭，POP带你玩转趋势","src":"common/images/re0.jpg","alt":"新生","class":"review-hover1"},
			{"id":2,"url":"/topic/20170516/","title":"18/19秋冬POP大主题趋势预测发布--新生","src":"common/images/re1.jpg","alt":"新生","class":"review-hover2"},
			{"id":1,"url":"/topic/20170516/","title":"时尚也要玩大数据，流行色一秒掌握！","src":"common/images/re2.jpg","alt":"大数据","class":"review-hover3"}
		],


		timer1:null,
		timer5_s:1000,					//item显示时长
		timer6_s:1100,					//item文字显示时长
		timer7_s:3000+500,					//关闭按钮显示时间
		timer9_s:3000,					//圆环旋转时长
		timer14_s:900,					//查看按钮显示时长
		timer15_s:1000,					//main-item显示时长
		_birds:null,


		btns:$(".js-circular-div>button"),
		items:$(".js-item"),
		circular_box:$(".js-circular-box"),
		line_div:$(".js-line-div"),

		links:[
			"https://www.pop-fashion.com/styles/gen_2/?key=%25E9%25A2%2584%25E5%2594%25AE%25E6%25AC%25BE#anchor",
			"https://www.pop-fashion.com/patterns/graphics/?key=%25E7%258B%2597%2520#anchor",
			"https://www.pop-fashion.com/styles/retail/reg_194/#anchor",
			"https://www.pop-fashion.com/styles/retail/reg_209/#anchor"
		]
	};



	
	
	/*------------动画开始--------------*/
	// 飞鸟
	function revealApp(){
		// BIRDS
		def._birds = new Birds();
		def._birds.init();
		def._birds.play();
		TweenMax.to($('#birds-canvas-holder'), 1, {
			opacity: 1,
			onStart: function(){
			}
		});
	};
	if(is_ie==false){
		revealApp();
		// 鸟停住
		// def.timer1=setTimeout(function(){
		// 	TweenMax.to($('#birds-canvas-holder'), 1, {
		// 		opacity: 0
		// 	});
		// 	def._birds.pause();
		// },7000);
		// 大圆出现
		def.timer1=setTimeout(function(){
			def.circular_box.addClass("transform-scale1");
			$(".js-circular-div").addClass("opacity1");
			$(".js-line-list").addClass("show-height");
			def.timer2=setTimeout(function(){
				def.timer16=setTimeout(function(){
					def.line_div.addClass("opacity1");
					$(".js-circular-div").addClass("transform-scale2");
					def.timer3=setTimeout(function(){
						$(".js-main-item .js-translate-ele").addClass("translate-x");
						def.timer4=setTimeout(function(){
							def.btns.addClass("transform-scale1");
						},2000);
					},1200);
				},1500);
			},1500);
		},1500);
		

		// 点击查看
		def.btns.on("click",function(){
			var index=def.btns.index($(this));
			var status=$(this).attr("data-status");
			
			def.btns.removeClass("transform-scale1");
			def.timer14=setTimeout(function(){
				
				$(".js-main-item").addClass("transform-scale3");
				def.timer15=setTimeout(function(){
					var nitem=def.items.eq(index);
					nitem.addClass("transform-scale4 high-zindex");
					def.timer5=setTimeout(function(){
						def.circular_box.addClass(status);
						
						
						nitem.find(".js-translate-ele").addClass("translate-y");

						def.timer6=setTimeout(function(){
							def.line_div.removeClass("opacity1").addClass("opacity0");
							$(".type4").show();
							def.timer9=setTimeout(function(){
								$(".type4").hide();
								def.line_div.removeClass("opacity0").addClass("opacity1 border-style");
							},def.timer9_s);
							def.timer7=setTimeout(function(){
								nitem.find("button").addClass("opacity1 move1");
							},def.timer7_s);
						},def.timer6_s);
					},def.timer5_s);
				},def.timer15_s)
			},def.timer14_s)
		});


		// 关闭
		def.items.find("button").on("click",function(){
			var self=$(this);
			var status=self.attr("data-status");
			self.removeClass("opacity1 move1");
			def.circular_box.removeClass("circular-box-status1");

			def.timer8=setTimeout(function(){

				def.line_div.removeClass("opacity0 border-style").addClass("opacity1");
				$(".type4").hide();
				self.parent().removeClass("transform-scale4 high-zindex");
				def.timer10=setTimeout(function(){
					self.siblings(".js-translate-ele").removeClass("translate-y");
				},def.timer5_s);
				def.timer11=setTimeout(function(){
					def.circular_box.removeClass(status);
					def.timer12=setTimeout(function(){
						$(".js-main-item").removeClass("transform-scale3");
						def.timer13=setTimeout(function(){
							def.btns.addClass("transform-scale1");
						},def.timer15_s);
					},500);
				},def.timer5_s);
			},500);
		});
	}else{
		$(".js-line-list>li>div").stop(true).animate({"height":"100%"},600,function(){
			// def.circular_box.addClass("transform-scale1");
			def.circular_box.stop(true).animate({"opacity":1},800,"linear",function(){
				$(".js-circular-div").stop(true).animate({"opacity":1});
				def.line_div.stop(true).animate({"opacity":1},500,function(){
					$(".js-main-item .js-translate-ele").stop(true).animate({"opacity":1},500,function(){
						def.btns.stop(true).animate({"opacity":1},1000);
					});
					
				});
				
			});
		});



		def.btns.on("click",function(){
			var index=def.btns.index($(this));
			var status=$(this).attr("data-status");
			var nitem=def.items.eq(index);
			def.btns.stop(true).animate({"opacity":0},500,function(){

				$(".js-main-item").stop(true).animate({"opacity":0},800,function(){
					nitem.addClass("high-zindex").stop(true).animate({"opacity":1},800,function(){
						def.circular_box.addClass(status);

						nitem.find(".js-translate-ele").stop(true).animate({"opacity":1},500,function(){
							def.line_div.addClass("border-style");
							nitem.find("button").stop(true).animate({"opacity":1},800)
						});
					})
				});
			});
			
		});



		def.items.find("button").on("click",function(){
			var self=$(this);
			var status=self.attr("data-status");
			self.stop(true).animate({"opacity":0},500,function(){
				def.line_div.removeClass("border-style");
				self.parent().removeClass("high-zindex").stop(true).animate({"opacity":0},600,function(){
					def.circular_box.removeClass(status);
					$(".js-main-item").stop(true).animate({"opacity":1},600,function(){
						def.btns.stop(true).animate({"opacity":1},800,function(){

						});
					});
				});
			});
		});
	}




	def.items.on("click",function(event){
		if(event.target.nodeName!=="BUTTON"){
			var index=def.items.index($(this));
			var src=def.links[index];
			window.open(src,"_blank");
		}
	});















	var narr=def.history_list.concat(def.history_list);
	setListDom(def.list_ele,narr);

	function setListDom(obj,arr){
		var len=arr.length,_html='';
		var src="",url="",title="",alt="pic",nclass="",id="";
		for(var i=0;i<len;i++){
			id=arr[i]["id"];
			src=arr[i]["src"];
			url=arr[i]["url"];
			title=arr[i]["title"];
			alt=arr[i]["alt"];
			nclass=arr[i]["class"];


			_html+='<li>';
			_html+='<a href="'+url+'" target="_blank" title="'+title+'">';
			_html+='<div>';
			_html+='<div class="review-hover '+nclass+'">';
			if(id==3){
				_html+='<img src="'+def.img_path+'20171211/images/hover-1.png" alt="'+alt+'" />';
			}
			_html+='</div>';
			_html+='<div class="img-c"><img src="'+def.img_path+src+'" alt="'+alt+'" /></div>';
			_html+='</div>';
			_html+='<p>'+title+'</p>';
			_html+='</a>';
			_html+='</li>';
		};

		obj.html(_html);

		historyFunc();
	};

	function historyFunc(){
		//往期回顾hover效果
    	$('.js-review-list>li').hover(function(){
    		$(this).siblings('li').find('.img-c>img').stop().animate({'opacity':0},400,function(){
    			$(this).hide();
    		});
    		$(this).siblings('li').find('.review-hover').stop().show().animate({'opacity':1},400);
    	},function(){
    		$(this).siblings('li').find('.img-c>img').stop().show().animate({'opacity':1},100);
    		$(this).siblings('li').find('.review-hover').stop().animate({'opacity':0},400,function(){
    			$(this).hide();
    		});
    	});
    	
    	var _index=0,is_lun=false,review_ul=$('.js-review-list'),_len=$('.js-review-list li').length/2,list_w=$('.js-review-list li').eq(0).outerWidth(true);		//轮播当前i

    	def.list_ele.width((_len+1)*2*300+"px");

    	$(window).on('resize',function(){
    		is_lun=true;		//正在轮播
    		list_w=$('.js-review-list li').eq(0).outerWidth(true);
    		review_ul.stop().css({
    			"margin-left":_index*-1*list_w+"px"
    		});
    		
    		setTimeout(function(){
	    		list_w=$('.js-review-list li').eq(0).outerWidth(true);
	    		review_ul.stop().css({
	    			"margin-left":_index*-1*list_w+"px"
	    		})
	    		is_lun=false;		//正在轮播
    		},300)
    	})
    	
    	$('.js-left-btn').on('click',function(){
    		if(is_lun==true){
    			return;
    		}
    		is_lun=true;		//正在轮播
    		_index--;
    		if(_index<0){
    			_index=_len-1;
    			review_ul.stop().css({
	    			"margin-left":_len*-1*list_w+"px"
	    		})
    		}
    		
    		review_ul.stop().animate({
    			"margin-left":_index*-1*list_w+"px"
    		},300,function(){
    			is_lun=false;
    		});
    	});
    	
    	$('.js-right-btn').on('click',function(){
    		if(is_lun==true){
    			return;
    		}
    		is_lun=true;
    		_index++;
    		if(_index>_len){
    			_index=1;
    			review_ul.stop().css({
	    			"margin-left":"0px"
	    		})
    		}
    		
    		review_ul.stop().animate({
    			"margin-left":_index*-1*list_w+"px"
    		},300,function(){
    			is_lun=false;
    		});
    	});
	};

	$(".pop_sole").addClass("selected");
	
});