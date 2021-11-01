$(function(){	
	var def={
		img_path:page_img_path_str+"/global/topic/",
		list_ele:$(".js-review-list"),
		history_list:[
			{"id":13,"url":"/topic/20180704/","title":"","src":"common/images/re14.jpg","alt":"https://www.pop-fashion.com/topic/20180704","class":"review-hover14"},
			{"id":13,"url":"/topic/20180515/","title":"","src":"common/images/re13.jpg","alt":"https://www.pop-fashion.com/topic/20180515","class":"review-hover13"},
			{"id":12,"url":"/topic/20180409/","title":"","src":"common/images/re12.jpg","alt":"https://www.pop-fashion.com/topic/20180409","class":"review-hover12"},
			{"id":11,"url":"/topic/20180122/","title":"2018新年特辑","src":"common/images/re11.jpg","alt":"2018新年特辑","class":"review-hover11"},
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
		]
		
	};

		
	/*top内容效果*/
	var rotat_timer=null,rotat_val=0,is_hover=false,back_timer=null;
	rotat_timer = setInterval(function(){
		rotat_val++;
		if(rotat_val>=28800){
			rotat_val=0;
		}
		$('.js-cont-msg').css({
			'transform':'rotate('+rotat_val/80+'deg)',
			'-webkit-transform':'rotate('+rotat_val/80+'deg)'
		});
		
		$('.js-cont-msg a').css({
			'transform':'rotate('+rotat_val/-80+'deg)',
			'-webkit-transform':'rotate('+rotat_val/-80+'deg)'
		});
		
	},4);
	//js-season-top
	$('.js-cont-msg a').hover(function(){
		var d_w = $('.js-season-contain').width();
		is_hover=true;
		clearInterval(rotat_timer);		//hover停止转动
		clearTimeout(back_timer);		//清楚out变化
		
		var _src=$(this).data('src'),img_link='';
		var _bgsrc=$(this).data('bg'),bg_link='';
		img_link='url('+def.img_path+'20190301/images/'+_src+')';
		bg_link='url('+def.img_path+'20190301/images/'+_bgsrc+')';
		$(".js-top-cont").css({
			'background-image':bg_link
		});
		$('.js-bg-set').stop().hide().css({
			"width": "400px",
			'height': '400px',
			'margin-left':'-200px',
			'margin-top':'-200px',
			'border-radius': '50%',
			'opacity': '0.7'
		}).children().attr({
			'src': (def.img_path+'20190301/images/'+_src)
    });
		
		$('.js-bg-set').stop().show().animate({
			"width": d_w+"px",
			'height': d_w+"px",
			'margin-left':-(d_w/2)+'px',
			'margin-top':-(d_w/2)+'px',
			'opacity': '1'
		},800,"linear",function(){
			$('.js-season-top').css({
				'background-image': img_link
			});
			$('.js-bg-set').hide();
			is_hover=false;
		});
		
	},function(){
		rotat_timer = setInterval(function(){
			rotat_val++;
			if(rotat_val>=28800){
				rotat_val=0;
			}
			$('.js-cont-msg').css({
				'transform':'rotate('+rotat_val/80+'deg)',
				'-webkit-transform':'rotate('+rotat_val/80+'deg)'
			});
			
			$('.js-cont-msg a').css({
				'transform':'rotate('+rotat_val/-80+'deg)',
				'-webkit-transform':'rotate('+rotat_val/-80+'deg)'
			});
			
		},4);
		hvBack();
	});
	
	function hvBack(){
		clearTimeout(back_timer);
		back_timer=setTimeout(function(){
			var d_w = $('.js-season-contain').width();
			var img_link1='url('+def.img_path+'20190301/images/t-bg1.jpg)';
			var bg_link1='url('+def.img_path+'20190301/images/top-tit.png)';			

			$('.js-bg-set').stop().show().css({
				"width": d_w+"px",
				'height': d_w+"px",
				'margin-left':-(d_w/2)+'px',
				'margin-top':-(d_w/2)+'px',
				'border-radius': '50%',
				'opacity': '1'
			});
			
			$('.js-season-top').css({
				'background-image': img_link1
			});
			
			$('.js-bg-set').stop().show().animate({
				"width": "400px",
				'height': '400px',
				'margin-left':'-200px',
				'margin-top':'-200px',
				'opacity': '0.7'
			},600,"linear", function(){
				$(".js-top-cont").css({
					'background-image':bg_link1
				});
				$('.js-bg-set').hide();
			});
		},420);
	}
	
	// 生成往期内容
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

	
});