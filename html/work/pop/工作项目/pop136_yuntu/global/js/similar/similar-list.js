/*
	#author		shuzhan
	#date 		2017/11/09
*/
require.config({
    paths:{
        "lazyload":["lib/jquery.lazyload.min"]
    },
    shim:{
       	"lazyload":{
       		deps: ["jquery"],
        	exports: "lazyload"
       	}
    }
});

require(["jquery","general","msg","lazyload"],function(jquery,general,msg){
	$(function(){
		var def = {
			'is_first_list':true,		//是不是新的一页
			'list_data':[],		//列表数据
			'list_num':0,		//当前滚动第几屏幕
			'list_more':true,	//一页还有数据
			'list_scroll':false,
			'path':'',		//图片
			'cat':'',			//已筛选关键字
			'id':'',
			't':'',
			'load_timer':null,
            location_obj:general.fn.getLocationParameter() || {},
			'load_end':false
		};
		
		def.path = getvl('path')||'';
		def.cat = getvl('cat')||'';
		def.id = getvl('id')||'';
		def.t = getvl('t')||'';
		
		if(def.path){
			var pic_path = decodeURIComponent(def.path)||'';
			if(pic_path.indexOf('pop-fashion.com') == -1){
				pic_path = 'https://imgyt2.pop-fashion.com'+pic_path;
			}
			
			var pic_ele = new Image();
			
			pic_ele.onload=function(){		//图片加载后
	    		var pic_w=pic_ele.width||1;
		    	var pic_h=pic_ele.height||1;
	    		if(pic_w<pic_h){
	    			$('.js-pic-contain').show().find('img').css({
	    				'height':'100%',
	    				'width':'auto'
	    			}).attr('src',pic_path);
	    		}else{
	    			$('.js-pic-contain').show().find('img').css({
	    				'width':'100%',
	    				'height':'auto'
	    			}).attr('src',pic_path);
	    		}
	    		
		    };
			pic_ele.src = pic_path;
			
			
			
			if(def.t!='' && def.id!=''){
                var origin_type=def.location_obj.origin_type?def.location_obj.origin_type:1;
				var pic_pattern = '/virtualtryon/virtualspl/?id='+def.id+'&t='+def.t+'&pattern='+encodeURIComponent(pic_path)+'&origin_type='+origin_type;
			}else{
				var pic_pattern = '/virtualtryon/virtualspl/?pattern='+encodeURIComponent(pic_path);
			}
			
	        $('.js-yun-virtual').attr('href',pic_pattern);
	        loadGress();
		}
		
		
		//加载效果
		// 图片识别过度效果
		function loadGress(){
			var defaultTop = -4;
		    var status_list = $(".js-load-list>ul");
		    var load_progress = $('.js-load-progress>p');
		    function statusMove(){
		    	if(defaultTop < -168){
		    		defaultTop =-4;
		    	}
		    	defaultTop -=8;
		    	status_list.css('top', defaultTop +'px');
		    }
		    def.load_timer =setInterval(statusMove,80);
		    
		    progressAnimate();
		    function progressAnimate(){
		    	if(def.load_end == false){
		    		load_progress.animate({
				    	'right':'-62px'
				    },560, 'linear', function(){
				    	load_progress.css('right','320px');
				    	progressAnimate();
				    });
		    	}
		    }
		}
		
    
		
		//写入图案列表
        function setFabricList(list){
        	list = list||[];
        	if(list.length>0){
        		def.list_scroll=true; 		//正在写入列表
        		var _html='',len=list.length,len1=def.list_num*30+30;
        		len1 = len1>len?len:len1;
        		for(var i=def.list_num*30;i<len1;i++){
        			var path=list[i].path||'';		//图片
        			var id=list[i].id||'';
        			var index=list[i].index||0;
        			var t=list[i].t||'';
        			var description = list[i].description||'';		//字段
        			var dCreateTime = list[i].dCreateTime||'';	//时间
        			var colorProportion = list[i].colorProportion||[];	//色系
        			var brand_name = list[i].brandName||'';	//品牌名
        			
        			_html+='<li data-id="'+id+'" data-t="'+t+'" data-index="'+index+'"><a href="javascript:void(0);" title="">';
        			if(i < 12){
        				_html+='<img src="'+path+'" alt="" />';
        			}else{
        				_html+='<img src="'+'/global/images/common/reference150-150.gif'+'" data-original="'+path+'" alt="" />';
        			}
        			
        			if(colorProportion && colorProportion.length>0){
        				_html+='<div class="dis-posion">';
        			}else{
        				_html+='<div class="dis-posion" style="bottom:0px;">';
        			}
        			
        			if(brand_name != ''){
    					_html+='<h3>'+brand_name+'</h3>';
    				}
        			
        			if(description){
        				_html+='<div class="list-words">';
	        				_html+='<p>'+description+'</p>';
        				_html+='</div>';
        			}
        			
        			_html+='<p><span class="icon"></span>'+dCreateTime+'</p></div></a>';
        			
        			if(colorProportion && colorProportion.length>0){
        				_html+='<div class="color-bott clear">';
	        			//色条
	        			for(var k=0;k<colorProportion.length;k++){
	        				_html+='<a href="javascript:void(0);" title="潘通参考值：'+colorProportion[k].pantonColorNumber+'" style="background: '+(colorProportion[k].Color||'')+'; width:'+colorProportion[k].Percent+'%;"></a>';
	        			}
	        			_html+='</div>';
        			}
        			_html+='</li>';
        		}
        		
        		if(def.list_num==0){
        			$('.js-fabric-list').html(_html);
        		}else{
        			$('.js-fabric-list').append(_html);
        		}
        		$('.js-fabric-list img').lazyload({effect: "fadeIn"});
        		
        		if(len1-def.list_num*30 < 30){
        			$('.js-list-more').hide();
        			def.list_more=false; 		//不再加载
        		}
        		def.list_scroll=false; 		//跳出列表
        	}
        }
        
        
        //列表控制
        var words_close_set = 1, words_close_timer = null;
        function wordsClose(){
        	
        	var _h = $('.js-search-content ul').height()||0;
        	if(_h <= 3){
        		clearTimeout(words_close_timer);
        		words_close_timer = setTimeout(function(){
        			words_close_set++;
        			if(words_close_set <= 5){
        				wordsClose();
        			}
        		},200);
        		return;
        	}
        	
        	var _h1 = $('.js-search-content').height()||56;		
        	if(_h > 60){
        		if(_h1 > 60){
	        		$('.js-words-close').removeClass('words-close2');
	        		$('.js-search-content').css('height', _h+'px');
	        	}else{
	        		$('.js-words-close').addClass('words-close2');
	        		$('.js-search-content').css('height', '56px');
	        	}
        		$('.js-words-close').show();
        	}else{
        		$('.js-search-content').css('height', _h+'px');
        		$('.js-words-close').hide();
        	}
        	$(window).on('resize',wordsClose);
        }
        
        
        
        //获取列表
        getFabricList();
        function getFabricList(){
        	var star_t = new Date();
        	var _path = decodeURIComponent(def.path);
        	var get_obj = {
        		'path':_path,
        		'cat':def.cat
        	};
        	general.fn.subAjax({
        		'url':'/similarpatterns/getsimilarlist/?'+Math.random(),
        		'data':get_obj,
        		'ctp':'application/x-www-form-urlencoded',
        		'success':function(data){
        			
        			var ndata = data||{};
        			var list=ndata.data.list||[],sec_list=ndata.data.category||'',total=0;   
        			var end_t = (new Date()-star_t)/1000;
        			$('.js-end-t').text(end_t);
        			
                    if(similar_list_back_obj.col_user_type=='GENERAL'){
                        $('.js-remaining-times span').text(ndata.info.free);
                        $('.js-remaining-times').css({'opacity':1});
                    }

        			$('.js-fabric-list').html('');
        			if(list.length<=30){
        				$('.js-list-more').hide();
        				def.list_more = false;	//滚动没有数据了
        			}
        			
        			if(list.length>0){
        				def.list_num = 0;			//新页第一屏
	        			def.list_data = list;		//存入列表数据
	        			setFabricList(list);
        			}else{
        				$('.js-pattern-null').show();
        				$('.js-fabric-contain').hide();
        			}
        			//写入筛选列表
        			if(sec_list){
        				var select_html='';
        				for (var i=0;i<sec_list.length;i++) {
        					var _id = sec_list[i].id||'';
        					var _name = sec_list[i].name||'';
        					var _num = sec_list[i].count||'';
        					
        					if(def.cat){		//选过的情况
        						if(_id == def.cat){
        							select_html+='<li class="word-sec" data-id="'+_id+'">'+_name+'（'+_num+'）</li>';
        						}else{
        							select_html+='<li data-id="'+_id+'">'+_name+'（'+_num+'）</li>';
        						}
	        				}else{				//全部没选的情况
	        					if(i==0){
        							select_html+='<li class="word-sec" data-id="'+_id+'">'+_name+'（'+_num+'）</li>';
        						}else{
        							select_html+='<li data-id="'+_id+'">'+_name+'（'+_num+'）</li>';
        						}
	        				}
        				}
        				
        				$('.js-word-sec').html(select_html);
        				$('.js-word-sec li').eq(sec_list.length-1).css('border','0 none');
        				
        				if(select_html == ''){
        					$('.js-search-content').hide();
        				}else{
        					wordsClose();
        				}
        			}else{
        				$('.js-search-content').hide();
        			}
        			
        			
        			$('.js-similar-load').hide();
    				$('.js-similar-suc').show();
    				//结束加载效果
    				def.load_end = true;
    				clearInterval(def.load_timer);
        		},
        		'error':function(){
        			msg.msg({"txt":'网络错误，请稍后刷新页面重试！'},1800);
        		}
        	})
        }
        
        
        //获取参数
        function getvl(name) {
            var reg = new RegExp("(^|\\?|&)"+ name +"=([^&]*)(\\s|&|$)", "i");
            if (reg.test(location.href)) return unescape(RegExp.$2.replace(/\+/g, " "));
            return "";
        };
        
            
            
    	//-----------------------------------------事件绑定--------------------------------------------        
        
        //图案内容筛选
        $('.js-word-sec').on('click','li',function(){
        	var _id = $(this).data('id')||'';
        	var _url = '/similarpatterns/similarlist/?path='+encodeURIComponent(def.path)+'&cat='+_id+'&id='+def.id+'&t='+def.t;
        	if(!$(this).hasClass('word-sec')){
        		location.href = _url;
        	}
        });
        
        
        //图案列表点击
		$('.js-fabric-list').on('click','li',function(e){
            if(similar_list_back_obj.col_user_type=='GENERAL'){
                $('.js-general-user-info-fixbox').slideDown();
                return false;
            }

			var _id=$(this).data('id')||'';
			var _t=$(this).data('t')||'';
			var frame_url = '/patternlibrary/detail/?id='+_id+'&t='+_t+'&origin_type=2&cat='+def.cat+'&path='+def.path;
			$('.js-detail-frame').find('iframe').attr('src',frame_url);
			$('body').addClass('over-hidden');
			$('.js-detail-frame').fadeIn();
		});
        
		//图案列表hover
		$('.js-fabric-list').on('mouseenter mouseleave','li',function(e){
			var e = e || window.event;
			if (e.type == 'mouseenter') {
				$(this).find('.dis-posion').stop(true).slideDown(200);
				$(this).find('.dis-posion').animate({
					'opacity':1
				},150,'linear');
			}else{
				$(this).find('.dis-posion').stop(true).slideUp(200);
				$(this).find('.dis-posion').animate({
					'opacity':0.5
				},150,'linear');
			}
		});
		

        //无权限提示关闭
        $('.js-general-user-info-fixbox button').on('click',function(e){            
            $(this).parents('.js-general-user-info-fixbox').slideUp(200);
        });
		
		//页面滚动加载
		$(window).on('scroll',function(){
			var content_h=$(document).height();
	  	    var scroll_top=$(this).scrollTop();
			var w_h = document.documentElement.clientHeight || document.body.clientHeight;
			
			if(content_h-scroll_top <= w_h+180 && def.list_scroll==false && def.list_more==true){
				def.list_num++;
				setFabricList(def.list_data);		//加载下一屏
			}
			
		});
		
		$('.js-list-more').on('click',function(){
			if(def.list_scroll==false && def.list_more==true){
				def.list_num++;
				setFabricList(def.list_data);		//加载下一屏
			}
		});
		
		
		//筛选列表收起
		$('.js-words-close').on('click',function(){
			var _this = $(this);
			var _h = $('.js-word-sec').height()||56;
			if(_this.hasClass('words-close2')){
				$('.js-search-content').animate({
					'height':_h+'px'
				},200);
				_this.removeClass('words-close2');
			}else{
				$('.js-search-content').animate({
					'height':'56px'
				},200);
				_this.addClass('words-close2');
			}
		});
		
		
		
		
		
	})
	
	
})