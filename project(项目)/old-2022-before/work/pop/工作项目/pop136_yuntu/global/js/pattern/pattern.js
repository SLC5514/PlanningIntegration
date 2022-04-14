	/*
	#author		shuzhan
	#date 		2017/11/09
	*/
	require.config({
	paths:{
		"echarts":["lib/echarts.min"],
		"columnyuntu_brand":["json-data/Columnyuntu_Brand"],
		"lazyload":["lib/jquery.lazyload.min"],
		"jquery_mousewheel":["lib/jquery.mousewheel"],
		'perfectScrollbar':["lib/perfect-scrollbar"]
	},
	shim:{
		"echarts": {exports: "echarts"},
		"columnyuntu_brand": {exports: "columnyuntu_brand"},
		"lazyload":{
			deps: ["jquery"],
			exports: "lazyload"
		},
		"jquery_mousewheel":{
			deps: ["jquery"],
			exports: "jquery_mousewheel"
		},
		'perfectScrollbar':{
			deps: ["jquery","jquery_mousewheel"],
			exports: "perfectScrollbar"
		}
	}
	});

	require(["jquery","general","echarts","perfectScrollbar","jquery_mousewheel","columnyuntu_brand","lazyload"],function(jquery,general,echarts,perfectScrollbar){
		$(function(){
			var def = {
				"cond":$('#cond'),	//数据
				'is_first_list':true,		//是不是新的一页
				'aco':'',
				'con':'',
				'list_data':[],		//列表数据
				'list_num':0,		//当前滚动第几屏幕
				'list_more':true,	//一页还有数据
				'list_scroll':false,
				'pie_exsist':false,	//饼图是否存在
				'cond_data':{
					'params':'',
					'key':''
				}
				
			};
			def.cond_data.params = def.cond.data('params')||'';
			def.cond_data.key = def.cond.data('key')||'';
			var global_storage = general.fn.getLocalSto('global_storage')||{};
		//		console.log(Brand);


			// 获取设计列表
			getDesignList();
			function getDesignList(){
				$.ajax({
					url: '/recommend/?'+Math.random(),
					type:"POST",
					data:{list:true},
					dataType:"json",
					success:function(data){  
						$('.js-loading-div,.js-bg-div').fadeOut();     //加载去除
						var arr = data.data||{}, _html='';
						var request_id = data.info.request_id || '';
						for (var i = 0; i < arr.length; i++) {
							var cover = arr[i].cover||'';
							var id = arr[i].id||'';
							var t = arr[i].t||'';

							_html += '<li data-id="'+id+'" data-t="'+t+'" data-request_id="'+request_id+'"><a href="javascript:void(0);"><img src="'+cover+'"></a></li>';
						}
						if(_html){
							$('.js-design-div>ul').html(_html);
							$('.js-design-content').slideDown();
							// designMoreShow();
						}
						
					}
				})
			}

			function designMoreShow(){
				var _w1 = $('.js-design-content').width()-28;
				var item_w1 = $('.js-design-div li').eq(0).outerWidth(true);
				var _len1 = $('.js-design-div li').length;
				if( _len1*item_w1 > _w1  ){
					$('.js-design-div>a').css({
						'display': 'block'
					})
				}

				$(window).on('resize', function(){
					var _w = $('.js-design-content').width()-28;
					var item_w = $('.js-design-div li').eq(0).outerWidth(true);
					var _len = $('.js-design-div li').length;
					if( _len*item_w > _w  ){
						$('.js-design-div>a').css({
							'display': 'block'
						})
					}else{
						$('.js-design-div>a').css({
							'display': 'none'
						})
					}
				})



			}


			//获取图案列表
			getPatternList();

			// 对参数进行处理
			function dealParam (param, type, _val) {
				var val, param_str;
				if(typeof _val == 'undefined') {
					val = 1;
				}else {
					val = _val;
				}
				if($.trim(param) == null || !param) {
					param_str = type+'_'+val;
				}else {
					var param_arr = param.split('-');
					// flag=false=》不存在本身的参数直接添加，falg=1=》去掉参数   page=true=>去掉page参数
					var flag = page = false;
					for(var i = 0 in param_arr) {
						var type_val_arr = param_arr[i].split('_');
						if(type_val_arr[0] == 'page') {
							var page_index = i;
							page = true;
						}
						// 存在自身的type
						if(type_val_arr[0] == type) {
							if(type_val_arr[1] == val || type == 'coll' || type == 'vect') {
								// 再次点击本身去掉参数
								if(type == 'coll' || type == 'vect' || type == 'ihot' || type == 'uli' || type == 'auth' || type == 'off' || type == 'popky'){
									var type_index = i;
									flag = 1;
								}else {
									return param;
								}

							}else if(val == 'all') {
								// 点击全部去掉本身的参数
								var type_index = i;
								flag = 1;
							}else {
								if(type == 'ihot') {
									if(type_val_arr[1] == 3) {
										type_val_arr[1] = 3 - val;
									}else {
										type_val_arr[1] = 3;
									}
								}else {
									type_val_arr[1] = val;
								}
								flag = true;
							}
						}
						type_val_str = type_val_arr.join('_');
						param_arr[i] = type_val_str;
					}
					if(page) {
						param_arr.splice(page_index, 1);
					}
					// 不存在本身的参数或者不是点击了全部则直接添加参数
					if(!flag && val != 'all') {
						param_arr.push(type+'_'+val);
					}else if(flag === 1) {
						param_arr.splice(type_index, 1);
					}
					param_str = param_arr.join('-');
				}
				return param_str;
			}

			// echarts图表的配置项和数据画图
			function createPie (main, data, type) {
				// 基于准备好的dom，初始化echarts实例
				var myChart = echarts.init(main);
				if( def.aco!="" && type=="aco"){
					var formatter = function(e){
						return e.data.sColorNumber;
					}
				}else if(def.con !="" && type=="con"){
					var formatter = "{c}";
				}else{
					var formatter = function(){
						return "";
					}
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
							radius: ['0%', '60%'],
							data: _data,
							label: {
								normal: {
									formatter: '{b} {d}%'
								}
							},
							labelLine: {
								normal: {
									length: 10,
									length2: 30
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
				
				var _search = def.cond_data.key;
				_search = _search?'?key='+_search:'';
				
				var _url = '/patternlibrary/';
				myChart.on('click', function (d) {
					var self = $(this);
					var param = def.cond_data.params;
					
					param = dealParam(param, type, d.data.id);
					param = _search ? param.replace(/\/$/,'')+'/'+_search : param+'/';
					location.href = _url+param.replace(/^\//,'');
				});
			};
			
			//搜索
			function editKeyJump(val) {
				var param = def.cond_data.params, _url='/patternlibrary/';
				// 页码如果存在则将页码去掉
				if (param.indexOf('-page_') !== -1) {
					var reg = RegExp('-page_([^-]*)', 'gi');
					param = param.replace(reg,'');
				}else if (param.indexOf('page_') !== -1) {
					var reg = RegExp('page_([^-]*)', 'gi');
					param = param.replace(reg,'');
				}
				// 关键字条件
				val = encodeURIComponent(encodeURIComponent(val));
				if ($.trim(param) != '') {
					// 不存在则增加
					if (param.indexOf('?key=') === -1) {
						param = param.replace(/\/$/,'')+'/?key='+val;
					}
					// 否则替换
					else {
						param = param.replace(/\/$/,'').replace(/\?key=([^&]*)/gi,'/?key='+val);
					}
				} else {
					param = '?key='+val;
				}
				location.href = _url+param.replace(/^\//,'');
			}
			
			
			//获取图标数据
			function getPiePic(){
				general.fn.subAjax({
					'url':'/patternlibrary/getPieData/?'+Math.random(),
					'data':def.cond_data,
					'ctp':'application/x-www-form-urlencoded',
					'success':function(data){  
						if(is_down==true){
							$('.js-color-analysis').removeClass('load-pie');
							data = data.data||{};
							var _info = data.info||{};
							def.aco=_info.aco||'';
							def.con=_info.con||'';
							
							var colorTag = data.colorBack ? '<a href="' + data.colorBack + '"><img src="/global/images/pattern/color-back.png" alt="返回"></a>' : '<img alt="显示图表" src="/global/images/pattern/color-analysis.png"/>';
							var patternContentT = data.patternContentBack ? '<a href="' + data.patternContentBack + '"><img src="/global/images/pattern/color-back.png" alt="返回"></a>' : '<img alt="显示图表" src="/global/images/pattern/patter-cav.png"/>';
							
							$('.js-analysis-pic1').html(colorTag);
							$('.js-analysis-pic2').html(patternContentT);
							
							createPie(document.getElementById('main-color'), data.color, 'aco'); //色彩分析
							createPie(document.getElementById('main-category'), data.patternContent, 'con'); //图案内容
							def.pie_exsist = true;
						}
					},
					'error':function(){
						
					}
				})
			}
			
			
			//头部饼图控制伸缩 
			var guide_exsist = global_storage.guide_exsist||'',is_down = true, is_click = false;
			if(guide_exsist == 1){
				is_down = false;
				$('.js-analysis-box').hide();
				$('.js-analysis-slid').removeClass('slid-up');
			}else {
				is_down = true;
				$('.js-analysis-box').show();
				$('.js-analysis-slid').addClass('slid-up');
				getPiePic();
			}
			
			
			//写入图案列表
			function setPatternList(list){
				list = list||[];
				if(list.length>0){
					def.list_scroll=true; 		//正在写入列表
					var _html='',len=list.length,len1=def.list_num*30+30;
					len1 = len1>len?len:len1;
					for(var i=def.list_num*30;i<len1;i++){
						var cover=list[i].cover||'';		//图片
						var id=list[i].id||'';
						var index=list[i].index||0;
						var t=list[i].t||'';
						var s_p = list[i].sPatternContent||[];		//字段
						var dCreateTime = list[i].dCreateTime||'';	//时间
						var colorProportion = list[i].colorProportion||[];	//色系
						var iBrand = list[i].brand||{};	//品牌名
						var collect_status = list[i].collect_status||false;
						
						_html+='<li data-id="'+id+'" data-t="'+t+'" data-index="'+index+'"><a href="javascript:void(0);" title="">';
						if(i < 12){
							_html+='<img src="'+cover+'" alt="" />';
						}else{
							_html+='<img src="'+'/global/images/common/reference150-150.gif'+'" data-original="'+cover+'" alt="" />';
						}
						
						if(colorProportion && colorProportion.length>0){
							_html+='<div class="dis-posion">';
						}else{
							_html+='<div class="dis-posion" style="bottom:0px;">';
						}
						
						if(iBrand.name){
							_html+='<h3 class="js-h-link" data-url="'+iBrand.link+'">'+iBrand.name+'</h3>';
						}
						
						if(s_p && s_p.length>0){
							_html+='<div class="list-words js-list-words">';
							for(var j=0;j<s_p.length;j++){
								_html+='<p data-url="'+s_p[j]['link']+'">'+s_p[j]['name']+'</p>';
							}
							_html+='</div>';
						}
						
						_html+='<p><span class="icon"></span>'+dCreateTime+'</p></div>';
						if(collect_status){
							_html+='<div class="collect-data js-collect-data on"></div>';
						}else{
							_html+='<div class="collect-data js-collect-data"></div>'
						}					
						_html+='</a>'
						
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
						$('.js-pattern-list').html(_html);
					}else{
						$('.js-pattern-list').append(_html);
					}
					$('.js-pattern-list img').lazyload({effect: "fadeIn"});
					
					if(def.list_num>=2 || (def.list_num==1 && list.length<=60)){
						$('.js-list-more').hide();
						$('.js-rightc-bottom').show();
						def.list_more=false; 		//不再加载
					}
					def.list_scroll=false; 		//跳出列表
					
				}
			}
			
			
			//品牌点击
			function clickJump(ele, _type) {
				var type = _type||'bra';
				
				var _bra = ele.data(type)||'';
				
				var _url = '/patternlibrary/';
				var _param = def.cond_data.params;
				var _key = def.cond_data.key;
				_key = _key?'?key='+_key:'';
				
				if (_param != '') {
					// 页码如果存在则将页码参数去掉
					if (_param.indexOf('-page_') !== -1) {
						var reg = new RegExp('-page_([^-]*)', 'gi');
						_param = _param.replace(reg, '');
					}else if (_param.indexOf('page_') !== -1) {
						var reg = new RegExp('page_([^-]*)', 'gi');
						_param = _param.replace(reg, '');
					}
					
					// 不存在则增加
					if (_param.indexOf(type + '_') === -1) {
						_param = _param + '-' + type + '_' + _bra;
					}else {  // 否则替换
						var reg = new RegExp(type + '_([^-]*)', 'gi');
						_param = _param.replace(reg, type + '_' + _bra);
					}
					
					if(_param.indexOf('-bra_')!=-1 && _param.split('-bra_')[0] == ''){
						_param = _param.replace('-bra_', 'bra_');
					}
					
				}else {
					_param = type + '_' + _bra;
				}
				location.href = _url + _param.replace(/\/$/, '') + '/' + _key;
			}

			
			
			//获取筛选项
			getConditions()
			function getConditions(){
				general.fn.subAjax({
					'url':'/patternlibrary/filterconditions/?'+Math.random(),
					'data':def.cond_data,
					'ctp':'application/x-www-form-urlencoded',
					'success':function(data){  
						data=data.data||{},_html='';
						var sGender=data.sGender||'';	//性别
						var sPatternUse=data.sPatternUse||'';	//局部满身
						var sPatternFormat=data.sPatternFormat||'';	//矢量图
						var sPatternContent=data.sPatternContent||'';	//图案内容
						var sAssortColor=data.sAssortColor||'';	//色系
						var sGender=data.sGender||'';	//性别
						
						if(sGender){    //性别
							_html+='<li><p class="aspan">性别: 全部<span class="icon"></span></p><div class="showbox sum"><div class="date-height"><div class="seck-content">';
							for(var i in sGender){
								_html+='<a class="clickme" href="'+sGender[i].link+'">'+sGender[i].name+'</a>';
							}
							_html+='</div></div></div></li>';
						}
						
						if(sPatternUse){     //局部满身
							_html+='<li><p class="aspan">局部/满身<span class="icon"></span></p><div class="showbox sum"><div class="date-height"><div class="seck-content">';
							
							_html+='';
							for(var i in sPatternUse){
								_html+='<a class="clickme" href="'+sPatternUse[i].link+'">'+sPatternUse[i].name+'</a>';
							}
							_html+='</div></div></div></li>';
						}
						
						if(sPatternFormat){   //矢量图
							_html+='<li><p class="aspan">矢量/位图<span class="icon"></span></p><div class="showbox sum"><div class="date-height"><div class="seck-content">';
							for(var i in sPatternFormat){
								_html+='<a class="clickme" href="'+sPatternFormat[i].link+'">'+sPatternFormat[i].name+'</a>';
							}
							_html+='</div></div></div></li>';
						}
						
						if(sPatternContent){	//图案内容
							_html+='<li><p class="aspan">图案内容<span class="icon"></span></p><div class="showbox category-box"><div class="category-div js-perfect1"><div class="seck-content">';
							for(var i in sPatternContent){
								_html+='<dl class="category-dl clear border-bottom"><dt><a class="clickme" href="'+sPatternContent[i].link+'">'+sPatternContent[i].sName+'</a></dt><dd>';
								
								for(var j in sPatternContent[i].attrs){
									_html+='<a class="clickme" href="'+sPatternContent[i].attrs[j].link+'">'+sPatternContent[i].attrs[j].sName+'</a>';
								}
								_html+='</dd></dl>';	
							}
							_html+='</div></div></div></li>';
						}
						
						if(sAssortColor){		//色系
							_html+='<li><p class="aspan">色系<span class="icon"></span></p><div class="showbox sum-color js-sum-color"><div class="color-div"><div class="seck-content clear">';
							for(var i in sAssortColor){
								_html+='<a href="'+sAssortColor[i].link+'"><div class="box-color" style="background-color:'+sAssortColor[i].sAlias+'"><p>'+sAssortColor[i].sName+'</p></div></a>';
							}
							_html+='</div></div></div></li>';
						}
						
						if(def.cond_data.params.indexOf('bra_')==-1){
							_html+='<li><p class="aspan">大牌图案<span class="icon"></span></p><div class="showbox big-pattern"><div class="patterns-cont js-perfect2"><div class="seck-content"><ul class="clear">';
							_html+='<li><a class="brand-ibra js-brand-ibra" data-ibra="1" href="javascript:void(0);" title="选择全部品牌">选择全部品牌</a></li>'
							for(var i in Brand){
								for(var j=0;j<Brand[i].length;j++){
									_html+='<li><a class="js-brand-tid" data-bra="'+Brand[i][j].id+'" href="javascript:void(0);" title="'+Brand[i][j].name+'">'+Brand[i][j].name+'</a></li>';
								}
							}
							_html+='</ul></div></div></div></li>';
						}
						
						$('.js-paixu').html(_html);
						
						if($('.js-perfect1').length > 0){
							$(".js-perfect1").perfectScrollbar();	
						}
						
						if($('.js-perfect2').length > 0){
							$('.js-perfect2').perfectScrollbar();
						}
						
					},
					'error':function(){
						
					}
				})
			}
			
			
			//获取列表
			function getPatternList(){
				general.fn.subAjax({
					'url':'/patternlibrary/getList/?'+Math.random(),
					'data':def.cond_data,
					'ctp':'application/x-www-form-urlencoded',
					'success':function(data){
						$('.js-loading-div,.js-bg-div').fadeOut();     //加载去除
						data = data||{};
						var list=data.data.list||[],selected=data.data.selected||'',page_html=data.data.pageHtml,total=data.info.total||0;   
						$('.js-finds-tyle>span').text(total);		//总数
						$('.js-pattern-list').html('');
						if(list.length<=30){
							$('.js-rightc-bottom').show();
							$('.js-list-more').hide();
							def.list_more = false;	//滚动没有数据了
						}
						
						if(list.length>0){
							def.list_num = 0;			//新页第一屏
							def.list_data = list;		//存入列表数据
							$('.js-rightc-bottom').html(page_html);
							setPatternList(list);
						}else{
							$('.js-pattern-null').show();
							$('.js-pattern-section').hide();
						}
						
						//写入已选择筛选项
						var select_html='';
																
						if(selected){
							
							for(var i in selected){
								var sel_link = selected[i].cancelLink || '/patternlibrary/'+def.cond_data.params+'/';
								if(i!='aco'){
									select_html+='<div class="key-word"><a class="auto-sx" href="'+sel_link+'" title="'+selected[i].name+'">'+selected[i].name+' : <p>'+selected[i].value+'</p><span class="icon"></span></a></div>';
								}else{
									select_html+='<div class="key-word"><a class="auto-sx" href="'+sel_link+'" title="'+selected[i].name+'">'+selected[i].name+' : <p class="bg-p" style="background:'+selected[i].color+';"></p><span class="icon"></span></a></div>';
								}
							}
							
							if(select_html!=''){
								select_html+='<a class="delefilter" href="/patternlibrary/"><span class="icon"></span>清空筛选</a>';
								$('.js-keycho').slideDown(120);
							}
							
							$('.js-key-allbox').html(select_html);
						}
					},
					'error':function(){
						$('.js-loading-div,.js-bg-div').fadeOut();     //加载去除
					}
				})
			}
			
			function goto(event){
				if(event.which == 13){
					//获取需要跳转到的页码
					var page = $('#J_GoPage').val();
					page = parseInt(page);
					//获取总页数pageCount
					var pageCount = parseInt($("#_pageCount").val());
					if(page>=pageCount) {
						page = pageCount;
					}
					//获取需要跳转到的URL
					var url = $('#goBtn').attr('data');
					var search = $('#goBtn').attr('search');
					if(isNaN(page)){
						return false;
					}
					window.location.href = url+'page_'+page+'/'+(search ? search : '');
				}
			};
			
			
				
				
		//-----------------------------------------事件绑定--------------------------------------------      
			//猜你喜欢点击
			$('.js-design-div').on('click','li',function(e){ 
				if(pattern_list_back_obj.col_user_type=='GENERAL'){
					$('.js-general-user-info-fixbox').slideDown();
					return false;
				}           
				var _id=$(this).data('id')||'';
				var _t=$(this).data('t')||'';
				var request_id = $(this).data('request_id')||'';
				var frame_url = '/patternlibrary/detail/?id='+_id+'&t='+_t+'&request_id='+request_id+'&scene_type=personal_cloud&origin_type=5';
				$('.js-detail-frame').find('iframe').attr('src',frame_url);
				$('.js-detail-frame').fadeIn();
				$('body').addClass('over-hidden');
			});
			
			//图案列表点击
			$('.js-pattern-list').on('click','li',function(e){
				if(pattern_list_back_obj.col_user_type=='GENERAL'){
					$('.js-general-user-info-fixbox').slideDown();
					return false;
				}
				var _id=$(this).data('id')||'';
				var _t=$(this).data('t')||'';
				var frame_url = '/patternlibrary/detail/?params='+def.cond_data.params+'&key='+def.cond_data.key+'&id='+_id+'&t='+_t;
				$('.js-detail-frame').find('iframe').attr('src',frame_url);
				$('.js-detail-frame').fadeIn();
				$('body').addClass('over-hidden');
			});
			
			$('.js-pattern-list').on('click','.js-list-words>p, .js-h-link',function(e){
				general.fn.stopBubble(e);
				var _url=$(this).data('url')||'';
				if(_url != ''){
					window.location.href = _url;
				}
			});

			// 猜你喜欢更多
			$(".js-recommend-more").on("click", function() {
				if(pattern_list_back_obj.col_user_type=='GENERAL'){
					$('.js-general-user-info-fixbox').slideDown();
					return false;
				}
			});
			
			
			
			

			$(".js-rightc-bottom").on("click",'#goBtn', function(){
				//获取需要跳转到的页码
				var page = $('#J_GoPage').val();
				page = parseInt(page);
				//获取总页数pageCount
				var pageCount = parseInt($("#_pageCount").val());
				if(page>=pageCount) {
					page = pageCount;
				}
				//获取需要跳转到的URL
				var url = $('#goBtn').attr('data');
				var search = $('#goBtn').attr('search');
				if(isNaN(page)){
					return false;
				}
				window.location.href = url+'page_'+page+'/'+(search ? search : '');
			});
			
			$('.js-rightc-bottom').on('keypress','#J_GoPage', goto);
			
			$('.js-rightc-bottom').on('focus','#J_GoPage',function(){
				$(this).val('');
			});
			
			$('.js-rightc-bottom').on('blur','#J_GoPage',function(){
				var $self = $(this);
				if (!$.trim($self.val())) {
					$self.val(this.defaultValue);
				}
			});
			
			
			//页面滚动加载
			$(window).on('scroll',function(){
				var content_h=$(document).height();
				var scroll_top=$(this).scrollTop();
				var w_h = document.documentElement.clientHeight || document.body.clientHeight;
				
				if(content_h-scroll_top <= w_h+180 && def.list_scroll==false && def.list_more==true){
					def.list_num++;
					setPatternList(def.list_data);		//加载下一屏
				}
			});
			
			$('.js-list-more').on('click',function(){
				if(def.list_scroll==false && def.list_more==true){
					def.list_num++;
					setPatternList(def.list_data);		//加载下一屏
				}
			});

			//头部饼图控制伸缩    
			$(".js-analysis-slid").on('click',function(){
				if (is_click) {
					return;
				}
				is_click = true;
				if(!is_down){ //下拉状态
					is_down=true;
					$('.js-analysis-box').slideDown(400,function(){
						global_storage.guide_exsist = 0;
						general.fn.setLocalSto('global_storage',global_storage);
						is_click = false;
						$('.js-analysis-slid').addClass('slid-up');
						if(def.pie_exsist==false){
							getPiePic();
						}
					});
				}else{
					is_down=false;
					$('.js-analysis-box').slideUp(400,function(){
						global_storage.guide_exsist = 1;
						general.fn.setLocalSto('global_storage',global_storage);
						is_click = false;
						$('.js-analysis-slid').removeClass('slid-up');
					});
				}
			}); 
			
			
			//色系hover
			$('.js-paixu').on('mouseenter mouseleave', '.js-sum-color .box-color', function(e){
				var $self = $(this);
				var e = e || window.event;
				$self.css({'position':'relative'});
				// 变大
				if (e.type == 'mouseenter') {
					$self.animate({width:'+=4px',height:'+=4px',left:'-=2px',top:'-=2px','line-height':'36px'}, 200);
					$self.find('p').show();
				}else if (e.type == 'mouseleave') {     // 还原
					$self.animate({width:'-=4px',height:'-=4px',left:'+=2px',top:'+=2px','line-height':'40px'}, 200);
					$self.find('p').hide();
				}
			});
			
			
			//搜索框
			$('.js-sear-input').on('focus',function(){
				var _val=$.trim($('.js-sear-input').val())||'';
				if(_val == '搜索'){
					$('.js-sear-input').val('');
				}
			})
			
			$('.js-sear-input').on('blur',function(){
				var _val=$.trim($('.js-sear-input').val())||'';
				if(_val == ''){
					$('.js-sear-input').val('搜索');
				}
			});
			
			//图案列表hover
			$('.js-pattern-list').on('mouseenter mouseleave','li',function(e){
				var e = e || window.event;
				if (e.type == 'mouseenter') {
					$(this).find('.dis-posion').stop(true).slideDown(200);
					$(this).find(".collect-data").show();
					$(this).find('.dis-posion').animate({
						'opacity':1
					},150,'linear');
				}else{
					$(this).find('.dis-posion').stop(true).slideUp(200);
					$(this).find(".collect-data").hide();
					$(this).find('.dis-posion').animate({
						'opacity':0.5
					},150,'linear');
				}
			});
			
			
			//大牌图案选择
			$('.js-paixu').on('click','.js-brand-tid',function(){
				clickJump($(this), 'bra');
			});
			
			//大牌图案选择
			$('.js-paixu').on('click','.js-brand-ibra',function(){
				clickJump($(this), 'ibra');
			});
			
			
			//搜索
			$('.js-neiye-list').on('click', function(){
				var $self = $(this);
				var _keywords = $('.js-sear-input').val();
				if ($.trim(_keywords) == '' || $.trim(_keywords) == '搜索') {
					return false;
				}
				editKeyJump(_keywords);
			});
			
			$('.js-search-box').on('keydown',function(ev){
				var _keywords = $('.js-sear-input').val();
				if($.trim(_keywords) == '') {
					return;
				}
				var e = ev || window.event;
				var keycode = e.keyCode || e.which;
				if (keycode == 13) {
					editKeyJump(_keywords);
				}
			});
			
			//无权限提示关闭
			$('.js-general-user-info-fixbox button').on('click',function(e){            
				$(this).parents('.js-general-user-info-fixbox').slideUp(200);
			});

			// 最新趋势 普通用户提示
			$('.js-trend-prompt').on('click', '.close', function() {
				$('.js-trend-prompt').slideUp(200);
			})

			// 获取报告列表数据
			getTrendsList();
			function getTrendsList() {
				$.ajax({
					url: '/trendspattern/getList/',
					dataType: 'json',
					data: {
						is_index: 1
					},
					success: function (res) {
						if (res.code === 0) {
							var list = res.data || [], _html = '';
							if (list.length > 0) {
								for (var i = 0; i < list.length; i++) {
									var labels = list[i].labels || '';
									_html += '<li data-id="' + list[i].id + '" data-t="' + list[i].t + '" data-col="' + list[i].col + '" data-handle="' + (list[i].collect_status ? 'cancel' : 'join') + '">' +
										'<a data-href="' + list[i].detail_url + '" title="' + list[i].title + '">' +
										'<img src="' + list[i].cover + '" alt="' + list[i].title + '">' +
										'</a>' +
										'<div class="text-down">' +
										'<a data-href="' + list[i].detail_url + '">' +
										'<p class="time clear">' +
										'<span class="fl">浏览（' + list[i].view + '）</span>' +
										'<span class="fr">' + list[i].publish_time + '</span>' +
										'</p>' +
										'<p class="title" title="' + list[i].title + '">' + list[i].title + '</p>' +
										'</a>' +
										'<div class="label">';
									for (var j = 0; j < labels.length; j++) {
										_html += '<a href="' + labels[j].lLink + '" title="' + labels[j].name + '" target="_blank">' + labels[j].name + '</a>'
									}
									_html += '</div></div></li>';
								}
								$('.js-trend-div>ul').html(_html);
								$('.js-trend-content').slideDown();
							}
						}
					},
					complete: function () {
					}
				})
			}

			// 跳转详情
			$('.js-trend-div').on('click', 'a[data-href]', function() {
				if (pattern_list_back_obj.col_user_type === 'GENERAL') {
					$('.js-trend-prompt').slideDown();
				} else {
					var data = $(this).parents('li').data();
					var frame_url = '/trendspattern/detail/?id=' + data.id + '&t=' + data.t + '&col=' + (data.col || '')/*  + '&refresh=1' */;
					window.open(frame_url);
				}
			})
		})
	})