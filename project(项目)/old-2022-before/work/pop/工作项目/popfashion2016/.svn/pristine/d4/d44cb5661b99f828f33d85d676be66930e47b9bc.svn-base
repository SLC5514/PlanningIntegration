/*
 	shuzhan
 	2017-12-18
 
 * */

$(function(){
	
	var def = {
		'list_data':[],		//列表数据
		'list_all':[],
		'list_num':-1,		//当前滚动第几屏幕,
		'list_total':0,		//总页数
		'list_more':true,	//一页还有数据
		'list_scroll':false,
		'path':'',		//图片
		'id':'',
		't':'',
		'load_timer':null
	};
	
	def.path = getvl('path')||'';
	def.id = getvl('id')||'';
	def.t = getvl('t')||'';
	
	
	if(def.path){
		var pic_path = decodeURIComponent(def.path)||'';
		if(pic_path.indexOf('pop-fashion.com') == -1){
			pic_path = 'https://imgf2.pop-fashion.com'+pic_path;
		}
		
		var pic_ele = new Image();
		pic_ele.onload=function(){		//图片加载后
    		var pic_w=pic_ele.width||1;
	    	var pic_h=pic_ele.height||1;
    		if(pic_w<pic_h){
    			$('.js-list-pic').show().find('img').css({
    				'height':'100%',
    				'width':'auto'
    			}).attr('src',pic_path);
    		}else{
    			$('.js-list-pic').show().find('img').css({
    				'width':'100%',
    				'height':'auto'
    			}).attr('src',pic_path);
    		}
    		
    		if((pic_w/pic_h) < (165/221)){
    			$('.js-pic-cont img').show().css({
    				'width':'auto',
    				'height':'100%'
    			}).attr('src',pic_path);
    		}else{
    			$('.js-pic-cont img').show().attr('src',pic_path);
    		}
    		
	    };
		pic_ele.src = pic_path;
		
	}
	
	//加载效果
	loadGress();
	function loadGress(){
		var defaultTop = -4;
	    var status_list = $(".js-status-list");
	    function statusMove(){
	    	clearTimeout(def.load_timer);
	    	if(defaultTop < -252){
	    		defaultTop =-4;
	    	}
	    	defaultTop -=8;
	    	status_list.css('top', defaultTop +'px');
	    	def.load_timer = setTimeout(statusMove,70);
	    }
	    def.load_timer = setTimeout(statusMove,70);
	}
	

	
	//写入图案列表
    function setFabricList(list){
    	list = list||[];
    	if(list.length>0){
    		def.list_scroll=true; 		//正在写入列表
    		var _html='',len=list.length,len1=def.list_num*24+24;
    		len1 = len1>len?len:len1;
    		for(var i=def.list_num*24;i<len1;i++){
    			var _obj = list[i]||{};
    			var _pic = _obj.cover||'';
    			var labels = _obj.labels||{};
    			var t = _obj.t||'';
    			var id = _obj.id||'';
    			var iCategory = _obj.iCategory||'';
    			var color_arr = _obj.colorProportion||[];
    			
    			if(_pic.indexOf('pop-fashion.com') == -1){
    				_pic = 'https://imgf2.pop-fashion.com'+_pic;
    			}
    			
    			_html+='<li><div class="pic-box"><a title="查看详情页" href="/details/style/t_'+t+'-id_'+id+'-col_50/" target="_blank">';
    			if(i<=5){
    				_html+='<img src="'+_pic+'"></a>';
    			}else{
    				_html+='<img src="https://imgf2.pop-fashion.com/global/images/loading/style.gif" data-original="'+_pic+'"></a>';
    			}
    			
    			if(color_arr.length>0){
    				_html+='<div class="color-list clearfix">';
	    			for(var k=0;k<color_arr.length;k++){
	    				_html+='<a href="javascript:void(0);" title="潘通参考值：'+color_arr[k].pantonColorNumber+'" style="background:'+color_arr[k].Color+';width:'+color_arr[k].Percent+'%;"></a>';
	    			}
	    			_html+='</div>';
    			}
    			
    			_html+='</div><p>';
    			
    			for(var j in labels){
    				
    				_html += '<span>'+labels[j]+'</span>';
    			}
    			
    			_html+='</p></li>';
    			
    		}
    		
    		if(def.list_num==0){
    			$('.js-analysis-list').html(_html);
    		}else{
    			$('.js-analysis-list').append(_html);
    		}
    		
    		$('.js-analysis-list img').lazyload({effect: "fadeIn"});
    		
    		if(def.list_num >= def.list_total-1){
    			$('.js-list-more').hide();
    			def.list_more=false; 		//不再加载
    		}
    		def.list_scroll=false; 		//跳出列表
    	}
    }
    
    
    // echarts图表的配置项和数据画图
	function createPie (main, data) {
		data = data||[], _len = data.length;
		
		if(_len<=0){
			return;
		}
		
		for(var i=0;i<_len;i++){
			var sColorNumber = data[i].sColorNumber||'';
			var itemStyle = {
				'normal':{
					'color':sColorNumber
				},
				'emphasis':{
					'color':sColorNumber
				}
			};
			var labelLine = {
				'normal':{
					'lineStyle':{
						'color':sColorNumber
					}
				},
				'emphasis':{
					'lineStyle':{
						'color':sColorNumber
					}
				}
			}
			
			
			
			if(sColorNumber == '#ffffff' || sColorNumber == '#FFFFFF' || sColorNumber == '#fff' || sColorNumber == '#FFF'){
				var label = {"normal":{"textStyle":{"color":"#aeaeae"}}};
				labelLine = {
					'normal':{
						'lineStyle':{
							'color':"#aeaeae"
						}
					},
					'emphasis':{
						'lineStyle':{
							'color':"#aeaeae"
						}
					}
				}
				
				data[i].label = label;
//				data[i].labelLine = labelLine;
			}
			
			data[i].itemStyle = itemStyle;
			data[i].labelLine = labelLine;
		}
		
		$('.js-pie-box').removeClass('pie-start');
		// 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(main);
        var formatter = function(){
            return "";
        }
        
        var _data = data;
        // 指定图表的配置项和数据
        var option = {
            tooltip: {
                trigger: 'item',
                formatter: formatter
            },
            series: [
                {
                    name: '色彩分析',
                    type: 'pie',
                    radius: ['50%', '65%'],
                    data: _data,
                    cursor:'default',
//                  legendHoverLink:false,
                    label: {
                        normal: {
                            formatter: '潘通参考值：\n{b} ',
                            textStyle:{
								"fontSize":14,
								"lineHeight":16
							}
                        }
                    },
                    labelLine: {
                        normal: {
                            length: 10,
                            length2: 100
                        }
                    },
                    itemStyle: {
                        normal: {
                            shadowBlur: 5,
                            shadowOffsetX: 0,
                            shadowColor: 'rgba(0, 0, 0, 0.2)'
                        }
                    }
                }
            ]
        };
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption(option);
		
    };
    
    
    //写入选中列表
    function setCategorys(_data, _count){
    	_data = _data||{};
    	_count = _count||0;
    	_count = _count>300?300:_count;
    	var sec_html = '<li class="sec-item" data-id="">全部单品（'+_count+'）</li>';
    	for(var i in _data){
    		sec_html+='<li class="" data-id="'+i+'">'+_data[i]+'</li>';
    	}
    	$('.js-sec-list').html(sec_html);
    	
    }
    
	
    
    //获取列表
    getFabricList();
    function getFabricList(){
    	var get_obj = {
    		'id':def.id,
    		't':def.t,
    		'imgUrl':def.path
    	};
    	
    	
    	pop_fashion_global.fn.subAjax({
    		'url':'/coloranalysis/search/?'+Math.random(),
    		'data':get_obj,
    		'ctp':'application/x-www-form-urlencoded',
    		'successFunc':function(data){
    			data = data||{};
            	var _info = data.info||{};
    			var list=data.data||[], pie_data = _info.pieDatas||[], usetime=_info.usetime||0, categorys = _info.Categorys||'', count=_info.count||0;   
    			var _len = list.length;
    			def.list_total = Math.ceil(_len/24);   //记录总页数
    			
    			$('.js-end-t').text('搜索共耗时 '+usetime+' 秒');
    			
    			if(_len<=24){
    				$('.js-list-more').hide();
    				def.list_more = false;	//滚动没有数据了
    			}
    			
    			
    			$('.js-analysis-load').hide();
				$('.js-analysis-cont').show();
    			createPie(document.getElementById('analysis-pie'), pie_data); //色彩分析
    			setCategorys(categorys,count);		//标签
    			
    			if(_len>0){
    				def.list_num = 0;			//新页第一屏
        			def.list_data = list;		//存入列表数据
        			def.list_all = list;		//存入列表数据
        			setFabricList(list);
    			}
    			
				//结束加载效果
				clearTimeout(def.load_timer);
    		},
    		'errorFunc':function(data){
    			//结束加载效果
				clearTimeout(def.load_timer);
				oCommon.noPower( '', '抱歉，色彩分析失败！' );
				$(".js-load-fail").show();
    		}
    	})
    }
    
    
    //获取参数
    function getvl(name) {
        var reg = new RegExp("(^|\\?|&)"+ name +"=([^&]*)(\\s|&|$)", "i");
        if (reg.test(location.href)) return decodeURIComponent(RegExp.$2.replace(/\+/g, " "));
        return "";
    };
    
        
        
	//-----------------------------------------事件绑定--------------------------------------------        
	
	//页面滚动加载
	$(window).on('scroll',function(){
		var content_h=$(document).height();
  	    var scroll_top=$(this).scrollTop();
		var w_h = document.documentElement.clientHeight || document.body.clientHeight;
		
		if(content_h-scroll_top <= w_h+460 && def.list_scroll==false && def.list_more==true && def.list_num!=-1){
			def.list_num++;
			setFabricList(def.list_data);		//加载下一屏
		}
		
	});
	
	$('.js-list-more').on('click',function(){
		if(def.list_scroll==false && def.list_more==true && def.list_num!=-1){
			def.list_num++;
			setFabricList(def.list_data);		//加载下一屏
		}
	});
	
	
	//标签筛选
	$('.js-sec-list').on('click','li',function(){
		
		if(!$(this).hasClass('sec-item') && def.list_num!=-1){
			def.list_num = -1;
			$('.js-list-more').hide();
			$('.js-analysis-list').html('');
			var _id = $(this).data('id')+''||'';
			var _arr = [];		//筛选循环数据
			if(_id == ''){
				_arr = def.list_all;
			}else{
				$(def.list_all).each(function(index, item){
					item = item||{};
					var id_arr = (item.iCategory+''||'').split('-')||[];
					if($.inArray(_id, id_arr) != -1){
						_arr.push(item);
					}
				});
			}
			
			def.list_data = _arr;
			var _len = _arr.length;
			def.list_total = Math.ceil(_len/24);   //记录总页数
			
			
			if(_len<=24){
				$('.js-list-more').hide();
				def.list_more = false;	//滚动没有数据了
			}else{
				$('.js-list-more').show();
				def.list_more = true;	//滚动没有数据了
			}
			
			def.list_num = 0;			//新页第一屏
			setFabricList(_arr);
			
			$(this).addClass('sec-item').siblings().removeClass('sec-item');
		}
		
	});
	
});








