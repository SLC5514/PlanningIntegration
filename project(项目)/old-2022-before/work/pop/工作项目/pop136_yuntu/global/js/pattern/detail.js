	/*
	#author		shuzhan
	#date 		2017/11/09
	*/
	require.config({
	paths:{
		"jqueryUi":["lib/jquery-ui.min"],
		"mCustomScrollbar": ["lib/jquery.mCustomScrollbar.concat.min"]
	},
	shim:{
		"jqueryUi": {
			deps: ["jquery"],
			exports: "jqueryUi"
		},
		"mCustomScrollbar": {
			deps:["css!"+STATIC_URL3+"/global/css/lib/jquery.mCustomScrollbar.css","jquery","mousewheel"]
		}
	}
	});
	require(["jquery","general","msg","mCustomScrollbar","jqueryUi"],function(jquery,general,msg){
	$(function(){
		if (top.location != location) {
			pushHistory();
			window.addEventListener("popstate", function(e) {
				var e = e || window.event;
				e.preventDefault();
				e.stopPropagation();
				if (!(e && e.preventDefault)) {
					window.event.returnValue = false;
				}
				closeLayer();
				return false;
			}, false);
			function pushHistory() {
				window.history.pushState({title: 'forward', url: ''}, 'forward', '');
				window.onpopstate = function () {
					window.history.forward(1);
				};
			}
		}

		//初始数据
		var detail_def_obj={
			"img_reg" : /http(s?):\/\//,
			"origin_type":'',														//1.正常图案库进入；2.图搜图进入；3.我的设计模型进入
			'virtual':false,
			"cat":'',
			"path":'',
			"download_type":'',
			"collect_status":0,
			"t":'',
			"id":'',
			"_params":'',
			"_key":'',
			"detail_list":[],		//细节图列表
			"is_cat_set":false,
			"move_y":0,				//滚动条移动距离
			"is_move_pic":false,	//是否移动中
			"control_top":74,		//放大镜top距离
			"min_radio":0.5,		//图片放大最小倍数
			"max_radio":3,			//图片放大最大倍数
			"is_first_list":false,	//第一次加载list
			"list_w":20000,		//列表宽度
			'imgW':0,           //图片宽
			'imgH':0,			//图片宽
			'_bigbox':'',
			"lun_timer":null,
			"detail_lun":{		//底部轮播
				item_w:165,		//推荐li宽度
				lun_n:1,		//一次滚动宽度
				hist_n:0,		//历史滚动距离
				is_lun:false,		//是否轮播状态
				has_list:true,		//是否有数据了	
				page:1,				//页数
				pre_page:1,
				next_page:1,
				page_size:60						//每页数量
			}
		};
		var pop_layer = $('#pop_layer');
		var global_storage = general.fn.getLocalSto('global_storage')||{};
		
		function designDetail(){
			$('.js-nav-control').hide();
			$('.js-nav-right').css({'right':'-380px'});
			$('.js-pop-close,.js-pic-next').css({'right':'0'});
			$('.js-pic-control').css({'right':'30px'});
			$('.js-detail-content,.js-detail-footer>div').css({'padding-right':'0'});

			if(pattern_detail_back_obj.col_user_type=='VIP'){
				$('.js-edit-pattern-btn').show();
			}
		}
		
		//页面初始加载数据
		getDetailMsg();
		function getDetailMsg(){
			detail_def_obj._params = getvl('params')||'';
			detail_def_obj._key = getvl('key')||'';
			detail_def_obj.id = getvl('id')||'';
			detail_def_obj.t = getvl('t')||'';
			detail_def_obj.cat = getvl('cat')||'';
			detail_def_obj.path = getvl('path')||'';
			detail_def_obj.origin_type = getvl('origin_type') || 1;
			

			detail_def_obj.virtual = getvl('virtual') || false;
			
			if(detail_def_obj.origin_type==3){
				designDetail();
			}


			
			detail_def_obj._params = detail_def_obj._params=='' ? 'page_1' : detail_def_obj._params; 
			if(detail_def_obj._params.indexOf('page_')==-1){
				detail_def_obj._params+='-page_1';
			}
			var _page = detail_def_obj._params.split('page_')[1]||1;
			_page = parseFloat(_page)||1;
			
			if( detail_def_obj.origin_type==3 ){     //智能设计详情页码
				_page = getvl('page')||1;
			}
			
			var _data = {
				'id':detail_def_obj.id,
				't':detail_def_obj.t
			};
			
			var detail_url = '/patternlibrary/detail/';
			general.fn.subAjax({
				'url': detail_url,
				'data':_data,
				'ctp':'application/x-www-form-urlencoded',
				'success':function(data){  
					var detail_d = data.data||{};
					var detail_list=detail_d.detail_img||[];
					//写入页面dom
					replaceChangeContent(data,-1);
					//页面初始化
					detailInit(_page, detail_list);  
					$('.js-loading-div,.js-bg-div').fadeOut(100);     //去除加载
					if(detail_list.length>0){
						$('.js-edit-pattern-btn').attr('data-serial',detail_list[0].sThirdPicId);
					}
				},
				'error':function(data){
					$('.js-loading-div,.js-bg-div').fadeOut();     //加载去除
					if(data.code==1001){
						top.location.href='/system/systemnotice/?type=1';
					}

				}
			});
		}
		
		
		//页面初始化
		function detailInit(_page,detail_list){
			detail_list = detail_list||[];
			_page = _page||1;
			//缓存初始化
			detail_def_obj['detail_list'] = detail_list;//细节图列表
			detail_def_obj['is_cat_set'] = false;
			detail_def_obj['move_y'] = 0;			    //滚动条移动距离
			detail_def_obj['is_move_pic'] = false;	    //是否移动中
			detail_def_obj['control_top'] = 74;		//放大镜top距离
			detail_def_obj['min_radio'] = 0.5;		//图片放大最小倍数
			detail_def_obj['max_radio'] = 3;			//图片放大最大倍数
			detail_def_obj['is_first_list'] = false;	//第一次加载list
			detail_def_obj['list_w'] = 20000;		//列表宽度
			detail_def_obj['imgW'] = 0;           //图片宽
			detail_def_obj['imgH'] = 0;			//图片宽
			detail_def_obj['_bigbox'] = '';
			detail_def_obj['lun_timer'] = null;
			//底部轮播
			detail_def_obj['detail_lun']['item_w'] = 165;		//推荐li宽度
			detail_def_obj['detail_lun']['lun_n'] = 1;		//一次滚动宽度
			detail_def_obj['detail_lun']['hist_n'] = 0;		//历史滚动距离
			detail_def_obj['detail_lun']['is_lun'] = false;		//是否轮播状态
			detail_def_obj['detail_lun']['has_list'] = true;		//是否有数据了	
			detail_def_obj['detail_lun']['page'] = 1;				//页数
			detail_def_obj['detail_lun']['pre_page'] = 1;
			detail_def_obj['detail_lun']['next_page'] = 1;

			detail_def_obj['detail_lun']['page_size'] = getvl('page_size') || 30;
			
			//大图控制说明
			var yuntu_bp_set=global_storage.yuntu_bp_set||0;
			if(yuntu_bp_set!=1){
				$('.js-bp-set').show();
			}else{
				$('.js-bp-set').hide();
			}
			
			//轮播数据初始化
			detail_def_obj.detail_lun.page=_page;
			detail_def_obj.detail_lun.pre_page=_page;
			detail_def_obj.detail_lun.next_page=_page;
			
			
			//请求列表
			$('.js-detail-list').html('').css({"margin-left":0,"width":'10000px'});
			$('.js-patter-contain').hide();
			$('.js-detail-footer').removeClass('bott-up');
			$('.js-detail-search').addClass('detail-search2');
			
			//轮播初始化
			if(detail_def_obj.virtual == 'true' || detail_def_obj.origin_type == 5){
				detail_def_obj['is_first_list'] = true;
				$('.js-detail-search, .js-patter-contain, .pics-item-control').hide();
			}else{
				$('.js-detail-search, .pics-item-control').show();
				lunInit();
				lunSetStart(1);
				getPatterList(0,-1,2);
			}
			
			
			$(window).on('resize',function(){
				lunInit();
			});
		}
		
		
		//关闭弹窗
		function closeLayer(){		
			//去除页面的键盘事件
			$(window.parent.document).off('keydown',docparentKeyd);
	//	        $(".js-down-close").trigger('click');
	//	        pop_layer.hide();
			$('body', window.parent.document).removeClass('over-hidden');
			$('.js-detail-frame', window.parent.document).hide();
			$('.js-detail-frame>iframe', window.parent.document).attr('src','');
			
	//	        $('.js-detail-list,.js-min-imgs,.js-msg-list').html('');
	//	        $('.js-patter-contain').hide();
	//	        
	//	        $('.js-detail-footer').removeClass('bott-up');
	//	        $('.js-detail-search').addClass('detail-search2');
	//	        $('.js-img-contain').show();	//大图区
	//	        
	//	        //右边导航展开
	//	        $('.js-nav-control').removeClass('nav-control2');
	//	    	$('.js-nav-right').css({'right':'0'});
	//	    	$('.js-pop-close').css({'right':'380px'});
	//	    	$('.js-pic-control').css({'right':'410px'});
	//	    	$('.js-detail-content,.js-detail-footer>div').css({'padding-right':'380px'});
			
		}
		
		//获取参数
		function getvl(name) {
			var reg = new RegExp("(^|\\?|&)"+ name +"=([^&]*)(\\s|&|$)", "i");
			if (reg.test(location.href)) return unescape(RegExp.$2.replace(/\+/g, " "));
			return "";
		};

		// 模板数据更新
		function replaceChangeContent(r_data, _i) {
			var data=r_data.data||{}, sApplication=data.sApplication||'', sFormat=data.sFormat||'', brand = data.brand||'';
			_i = _i==undefined?-1:_i;
			var html1='',html2='',detail_list=data.detail_img||[],msg_list=data.sPatternContent||[];
			detail_def_obj.detail_list=detail_list||[];
			
			//下载数据配置
			detail_def_obj.download_type = data.downloadType||[];
			//添加详情页dom
			if(detail_list.length>0){
				for (var i=0;i<detail_list.length;i++) {
					var bigPath = detail_list[i].bigPath||'';
					if( !detail_def_obj.img_reg.test(bigPath) ){
						bigPath = STATIC_URL2 + bigPath;
					}
					var simpleName = detail_list[i].simpleName||'';
					var id = detail_list[i].id||'';
					var smallPath = detail_list[i].smallPath||'';
					if( !detail_def_obj.img_reg.test(smallPath) ){
						smallPath = STATIC_URL2 + smallPath;
					}
					
					if(i==0){
						html1+='<li class="img-sec" data-id="'+id+'" data-id="'+id+'" data-bp="'+bigPath+'" data-sp="'+smallPath+'" data-sn="'+simpleName+'"><img src="'+smallPath+'" alt="'+simpleName+'"></li>';
					}else{
						html1+='<li data-id="'+id+'" data-id="'+id+'" data-bp="'+bigPath+'" data-sp="'+smallPath+'" data-sn="'+simpleName+'"><img src="'+smallPath+'" alt="'+simpleName+'"></li>';
					}
				}
				if(detail_list.length <= 1 ){
					$(".img-detail").hide().find('.js-min-imgs').html(html1);
				}else{
					$(".img-detail").show().find('.js-min-imgs').html(html1);
				}
				draggImg(detail_list[0], $('.js-img-contain .js-bigbox'));
			}
			// 收藏
			detail_def_obj.collect_status = data.collect_status||0;
			var pattern_detail = data.pattern_detail || undefined;
			if(pattern_detail && pattern_detail != undefined ){
				$(".js-collect-btn").show();
				if(detail_def_obj.collect_status){
					$(".js-collect-btn").addClass("on").html('<a  class="bott-item" href="javascript:void(0);" title="已收藏" data-id="'+id+'" data-t="'+simpleName+'"><span class="icon icon8"></span>已收藏</a>');
				}else{
					$(".js-collect-btn").removeClass("on").html('<a  class="bott-item" href="javascript:void(0);" title="加入收藏" data-id="'+id+'" data-t="'+simpleName+'"><span class="icon icon8"></span>加入收藏</a>')
				}
			}else{
				$(".js-collect-btn").hide();
			}			
			btnLinkInit(detail_list,0);
			
			if(msg_list.length>0 || sApplication || sFormat || brand){
				if(brand){
					if(brand.link){
						html2+='<li data-href="'+brand.link+'"><a href="javascript:void(0);" title="'+(brand.name||'')+'">'+(brand.name||'')+'</a></li>';
					}
				}
				
				for (var i=0;i<msg_list.length;i++) {
					if(msg_list[i].link){
						html2+='<li data-href="'+msg_list[i].link+'"><a href="javascript:void(0);" title="'+(msg_list[i].name||'')+'">'+(msg_list[i].name||'')+'</a></li>';
					}
				}
				
				if(sApplication && !(sApplication instanceof Array)){
					if(sApplication.link){
						html2+='<li data-href="'+sApplication.link+'"><a href="javascript:void(0);" title="'+(sApplication.name||'')+'">'+(sApplication.name||'')+'</a></li>';
					}
				}
				
				if(sFormat && !(sFormat instanceof Array)){
					if(sFormat.link){
						html2+='<li data-href="'+sFormat.link+'"><a href="javascript:void(0);" title="'+(sFormat.name||'')+'">'+(sFormat.name||'')+'</a></li>';
					}
				}
				
				$('.js-msg-list').html(html2);
				if(html2 == ''){
					$('.js-msg-list').parents('.msg-list').hide();
				}else{
					$('.js-msg-list').parents('.msg-list').show();
				}
				
			}else{
				$('.js-msg-list').parents('.msg-list').hide();
			}
			
			
			//判断上下款按钮
			if(_i != -1){
				var list_len=$('.js-detail-list li').length;
				if(_i==0 && detail_def_obj.detail_lun.pre_page==1){
					$('.pics-item-control').eq(0).hide();
				}else{
					$('.pics-item-control').eq(0).show();
				}
				if(_i==list_len-1 && detail_def_obj.detail_lun.has_list==false){
					$('.pics-item-control').eq(1).hide();
				}else{
					$('.pics-item-control').eq(1).show();
				}
			}
			
		
			detail_def_obj._bigbox = pop_layer.find('.js-bigbox');
			// 右键点击弹出下载格式(款式库)
			if (detail_def_obj._bigbox.length > 0) {
				detail_def_obj._bigbox[0].oncontextmenu = function(){
					$('.js-downimg-layer').hide();
					rightClick();
					return false;
				};
			}
			
			imgScroll();
			detail_def_obj.is_cat_set=false;	
			actionFunc(detail_def_obj.t,detail_def_obj.id,"view",request_id,scene_type);
			guessLike();	
		}

		
		//阻止默认事件
		function stopDefault(ev){ 		//阻止默认事件
			var e=ev || window.event; 
			if(e && e.preventDefault){ 
				e.preventDefault(); 
			} else{ 
				window.event.returnValue=false; 
			} 
			return false;
		}

		
		
		//初始底部btn链接
		function btnLinkInit(detail_list,_i){
			var _arr=detail_list||[];
			if(_arr.length<=0){
				return;
			}
			_i=_i||0;
			var _bp = _arr[_i].bigPath||'';
			var _mp = _arr[_i].mbigPath||'';
			if( !detail_def_obj.img_reg.test(_bp) ){
				_bp = STATIC_URL2 + _bp;
			}
			if( !detail_def_obj.img_reg.test(_mp) ){
				_mp = STATIC_URL2 + _mp;
			}
			var _id = _arr[_i].id||'';
			var _t = detail_def_obj.t;
			
			var encod_src = encodeURIComponent(_bp);
			var encod_src1 = encodeURIComponent(_mp);
			
			//相似图案
			if($('.js-spatter-pic').length>0){
				$('.js-spatter-pic').data('path',encod_src1);
				$('.js-spatter-pic').data('id',_id);
				$('.js-spatter-pic').data('t',_t);
			}
			
			//虚拟样衣
			if($('.js-yun-vcloth').length>0){
				$('.js-yun-vcloth').data('pic',encod_src1);
				$('.js-yun-vcloth').data('id',_id);
				$('.js-yun-vcloth').data('t',_t);
			}
		}
		

		// 进入弹层重新计算图片大小
		function imgSize(img,_w,_h){
			if(img.length>0){
				if(detail_def_obj.is_first_list==false || !$('.js-detail-search').hasClass('detail-search2')){
					var bott_h=290;
				}else{
					var bott_h=90;
				}
				
				var detail_con=$(".js-detail-content");
				var deW=detail_con.width();
				var deH=detail_con.height();
				detail_def_obj.imgW=_w||1;
				detail_def_obj.imgH=_h||1;
				if(_w > _h){
					deW=parseInt(deW*85/100);
					var now_h = _h/_w*deW;
					if(now_h>(deH-bott_h)){
						deH=parseInt((deH-bott_h)*98/100);
						img[0].height=deH;
						img[0].width=_w/_h*deH;
					}else{
						img[0].width=deW;
						img[0].height=now_h;
					}
				}else{
					deH=parseInt((deH-bott_h)*96/100);
					img[0].height=deH;
					img[0].width=_w/_h*deH;
				}
				$('.js-bp-load').hide();
				$("#draggable .js-bigbox").show();
			}
		}
		
		
		//重新计算图片位置 
		function intBigImg() {
			var bigImgBox = $("#draggable");
			bigImgBox.css({"left": 0,"top": 0,"width":'100%'});
			bigImgBox.find(".js-bigbox").css({'-moz-transform': 'scale(1)', "zoom": "100%","display":"inline"});
			radioSet(1);
		}
		
		// 获取图片原始大小
		function natural(img){
			var dImg =$("#draggable .js-bigbox");
			var nImg =new Image();
			nImg.src=dImg[0].src;
			detail_def_obj.imgW = nImg.width;
			detail_def_obj.imgH = nImg.height;
			dImg[0].width=detail_def_obj.imgW;
			dImg[0].height=detail_def_obj.imgH;
		}
		
		//放大镜位置同步 距离同步到倍数
		function ySet(_y){
			var _img=$(".big-contain img");
			if(_y > 74){
				_y=74;
			}else if(_y<0){
				_y=0;
			}
			var _radio=1,b_t=50,m_t=24;
			if(_y > b_t){
				_radio=1-(_y-b_t)/m_t*0.5;
			}else if(_y < b_t){
				_radio=3-_y/b_t*2;
			}
			if(_img.length>0){
				$('.js-reset').removeClass('on-reset');   //可以复位
				_img[0].style.cssText = '-moz-transform:scale(' + _radio  + ');-moz-transform-origin: center top 0px;display:inline;';
				_img[0].style.zoom =  _radio*100 + "%"; //ff，opera不支持zoom放大缩小
			}
		}
		
		//放大镜位置同步 倍数同步到距离
		function radioSet(_radio){
			_radio=_radio||-1;
			if(_radio!=-1){
				if(_radio > detail_def_obj.max_radio){
					_radio = detail_def_obj.max_radio;
				}else if(_radio < detail_def_obj.min_radio){
					_radio = detail_def_obj.min_radio;
				}
				var _top=50,b_t=50,m_t=24;
				if(_radio>1){
					_top = (detail_def_obj.max_radio - _radio)*b_t/2;
				}else if(_radio<1){
					_top = b_t+m_t-(_radio - detail_def_obj.min_radio)*m_t*2;
				}
				$(".js-control-l").find(".icon").css('top',_top+'px');
			}
		}
		
		// 下载
		function down(path) {
			if (typeof path == 'undefined' || path=='') {
				return;
			}
			var fileType = path.split('.');
			fileType = fileType.pop();  //下载文件格式
			/*var params={ table:t , id:id , action:'DownCount',fileType:fileType};
			$.ajax({
				type: "get",
				url: "/ajax/setCount/" + Math.random(),
				data: params,
				async: false
			});*/
	//		    oCommon.download(path);
			location.href = '/download/downimg/?dl_link=' + encodeURIComponent(path) + '&' + Math.random();
		}
		
		// 大图右键点击
		function rightClick() {
			
			var curImg = $('#draggable').find('.js-bigbox');
			var pop_download_table = $(".downimg-layer-data").find("table tbody");
			var sDownHtml='';
			var downloadType = detail_def_obj.download_type;
			

			setDownloadList(downloadType,pop_download_table,curImg);    
			
			$('.js-bg-layer,.js-downimg-right').show();
		}
		
		//鼠标滚动图片放大缩小     
		function imgScroll(){
			var oBigImg = $("#draggable .js-bigbox");
			if(oBigImg.length<=0){
				return;
			}
			var oImg=oBigImg[0];
			var wheelScroll = function (ev) {
				var ev = ev || window.event;
				var zoom = parseInt(oImg.style.zoom, 10) || 100;
				if (ev.wheelDelta) {
					if (ev.wheelDelta > 0) {
						$(oImg).addClass("bzoom").removeClass('szoom');
					}
					else {
						$(oImg).addClass("szoom").removeClass('bzoom');
					}
				}else {
					if (-ev.detail > 0) {
						$(oImg).addClass("bzoom").removeClass('szoom');
					}
					else {
						$(oImg).addClass("szoom").removeClass('bzoom');
					}
				}
				
				zoom += ev.wheelDelta ? (ev.wheelDelta / 12) : (-ev.detail);
				if (zoom >= 50 && zoom<=300) {
					$('.js-reset').removeClass('on-reset');   //可以复位
					oImg.style.cssText = '-moz-transform:scale(' + zoom / 100 + ');-moz-transform-origin: center top 0px;display:inline;';
					oImg.style.zoom =  zoom + "%"; //ff，opera不支持zoom放大缩小                
					radioSet(zoom/100);
					if (ev.preventDefault) {
						ev.preventDefault();
					}
					ev.returnValue = false;
				}
				return false;
			};
			if (oImg.addEventListener) {
				/** DOMMouseScroll is for mozilla. */
				oImg.addEventListener('DOMMouseScroll', wheelScroll, false);
			}
			oImg.onmousewheel = wheelScroll;
			oImg.onmousedown = function () {
		
				if (oBigImg.hasClass("bzoom")) {
					oBigImg.addClass("dragIcon").removeClass('bzoom');
				}else if (oBigImg.hasClass("szoom")) {
					oBigImg.addClass("dragIcon").removeClass('szoom');
				}
				
				oImg.onmouseup = function () {
					oBigImg.addClass("dragIcon");
				}
			};
			$("#draggable").draggable();
		}
		
		function catSet(_ele){
			_ele=_ele||'';
			if(_ele.length<=0){
				detail_def_obj.is_cat_set=false;
				return;
			}
			var self = _ele;
			detail_def_obj.id = self.data('id')||'';
			detail_def_obj.t = self.data('t')||'';
			
			var _data = {
				'id':detail_def_obj.id,
				't':detail_def_obj.t
			};
			
			var detail_url = '/patternlibrary/detail/';
			// if( detail_def_obj.origin_type==3){
				// detail_url = '/mihui/detail/?'+Math.random();
			// }
			
			general.fn.subAjax({
				'url': detail_url,
				'data':_data,
				'ctp':'application/x-www-form-urlencoded',
				'success':function(data){
					var detail_d = data.data||{};
					var detail_list=detail_d.detail_img||[];					 
					var _i=_ele.index();
					if(detail_list.length>0){
						$('.js-edit-pattern-btn').attr('data-serial',detail_list[0].sThirdPicId);
					}
					replaceChangeContent(data,_i);
				},
				error:function(){
					detail_def_obj.is_cat_set=false;
				}
				
			});
			//统计浏览量
	//	        viewCount(table,id,col);
		}
		
		//轮播初始化
		function lunSetStart(_i){
			clearTimeout(detail_def_obj.lun_timer);
			_i++;
			if(_i >= 20 || detail_def_obj.detail_lun.lun_n >= 2){
				return;
			}
			detail_def_obj.lun_timer = setTimeout(function(){
				lunInit();
				lunSetStart(_i);
			},200);
		}
		
		//细节图初始化
		function draggImg(bp_obj, ele2){
			ele2.hide();
			ele2.attr({"src":''});
			$('.js-bp-load').show();
			if(detail_def_obj.is_first_list==false || !$('.js-detail-search').hasClass('detail-search2')){
				var bott_h=290;
			}else{
				var bott_h=90;
			}
			
			var bigPath = bp_obj.bigPath||'';
			if( !detail_def_obj.img_reg.test(bigPath) ){
				bigPath = STATIC_URL2 + bigPath;
			}
			var simpleName = bp_obj.simpleName||'';
			var id = bp_obj.id||'';
			var smallPath = bp_obj.smallPath||'';
			if( !detail_def_obj.img_reg.test(smallPath) ){
				smallPath = STATIC_URL2 + smallPath;
			}
			var b_obj = new Image();
			
			b_obj.onload=function(){
				var b_w=b_obj.width||1;
				var b_h=b_obj.height||1;
				imgSize($("#draggable .js-bigbox"),b_w,b_h);
				intBigImg();
			};
			
			//写入大图小图
	//		    s_obj.src = smallPath;
			// bigPath='https://imgyt1.pop-fashion.com/global/images/common/logo.png'
			b_obj.src =	bigPath;
			
			
			ele2.attr({
				"src":bigPath,
				"data-bp": bigPath,
				"data-id":id,
				"data-sp":smallPath,
				"data-t":simpleName
			});
			$('.js-reset').addClass('on-reset');   //初始不能复位
		}
		
		//底部轮播图
		function lunInit(){
			var _len=$('.js-detail-list li').length;
			if(_len>0){
				detail_def_obj.detail_lun.item_w = 165;		//获取li宽度
			}
			//大小屏包含框宽度
			var contain_w=$('.footer-cont').width()-240;
			detail_def_obj.detail_lun.lun_n = Math.floor(contain_w/detail_def_obj.detail_lun.item_w);
			detail_def_obj.detail_lun.lun_n = detail_def_obj.detail_lun.lun_n<=0?1:detail_def_obj.detail_lun.lun_n;
			
			if(detail_def_obj.detail_lun.lun_n >= 2 && detail_def_obj.detail_lun.has_list==false && _len>0){
				if(Math.abs(detail_def_obj.detail_lun.hist_n-detail_def_obj.detail_lun.lun_n) < _len){
					$('.js-list-next').addClass('list-hv-on');
				}
			}
		}
		
		
		//获取更多图案列表
		function getPatterList(_type, _move, is_init){
			if(detail_def_obj.id == '' || detail_def_obj.origin_type == 5){
				return;
			}
			is_init=is_init==undefined?2:is_init;
			is_init=is_init||2;
			_move=_move||-1;	//移动选中图案
			_type=_type||0;		//加载数据类型  加载，向左加载，向右加载 0,2,1
			
			var _url
			if(detail_def_obj.origin_type == 4){
				_url = "/collect/getlist/"
			}else{
				_url = '/patternlibrary/getList/';
			}       
			
			var old_page = 'page_'+detail_def_obj.detail_lun.page, new_page='', param='';
			
			if(_type==1 || is_init!=2){
				detail_def_obj.detail_lun.page=detail_def_obj.detail_lun.next_page;		//记录下一页页数
			}else if(_type==2){
				detail_def_obj.detail_lun.pre_page--;
				detail_def_obj.detail_lun.page=detail_def_obj.detail_lun.pre_page;		//记录上一页页数
			}
			//记录新的_params
			if(detail_def_obj._params.indexOf(old_page) != -1){
				new_page = 'page_'+detail_def_obj.detail_lun.page;
				detail_def_obj._params = detail_def_obj._params.replace(old_page, new_page);
			}
			
			if(detail_def_obj.origin_type == 2){		//图搜图详情页
				_url = '/similarpatterns/getsimilarlist/?'+Math.random();
				param = {
					'path':detail_def_obj.path,
					'cat':detail_def_obj.cat
				};
				general.fn.subAjax({
					"url":_url,
					ctp:"application/x-www-form-urlencoded",
					data:param,
					success:function(data){
						var _data=data['data']['list']||[],_len=_data.length,_html='';
						if(_len>0){
							for(var i=0;i<_data.length;i++){
								var _cover=_data[i].path||'';		//图片
								var _id=_data[i].id||'';
								var _t=_data[i].t||'';
								
								if(detail_def_obj.is_first_list==false && _id==detail_def_obj.id && is_init==2){		
									_html+='<li class="list-sec" data-id="'+_id+'" data-t="'+_t+'"><div><img src="'+_cover+'"/></div></li>'
								}else{
									_html+='<li data-id="'+_id+'" data-t="'+_t+'"><div><img src="'+_cover+'"/></div></li>'
								}
							}
							detail_def_obj.list_w+=_len*detail_def_obj.detail_lun.item_w;
							$('.js-detail-list').css({"width":detail_def_obj.list_w+'px'});
							$('.js-detail-list').html(_html);
						}
						detail_def_obj.detail_lun.has_list=false;		//后面没有数据了
						
						//轮播列表初始化
						if(detail_def_obj.is_first_list==false){		
							var contain_w=$('.footer-cont').width()-240;
							detail_def_obj.detail_lun.lun_n = Math.floor(contain_w/detail_def_obj.detail_lun.item_w);
							if(_len > (detail_def_obj.detail_lun.lun_n||10)){
								$('.js-list-next').addClass('list-hv-on');
							}
							detail_def_obj.detail_lun.is_lun=true;		//轮播状态
							$('.js-detail-search').removeClass('detail-search2');
							$('.js-patter-contain').slideDown(10);
							$('.js-detail-footer').addClass('bott-up');
							
							
							var _i=$('.js-detail-list .list-sec').index();		//当前选中的index
							var lun_n=detail_def_obj.detail_lun.lun_n||13;
							if(_len>lun_n){
								$('.js-list-next').addClass('list-hv-on');
							}
							
							if(_i==0){
								$('.pics-item-control').eq(0).hide();
							}
							if(_i==_len-1){
								$('.pics-item-control').eq(1).hide();
							}
							
							var _l=Math.abs( detail_def_obj.detail_lun.hist_n - lun_n);
							var _s=Math.abs( detail_def_obj.detail_lun.hist_n );
							
							if(_i+1 >= _s && _i+1 <= _l-1){		//当前视线之内
								detail_def_obj.detail_lun.is_lun=false;		//跳出 轮播状态
							}else {			//视线外
								var _i=$('.js-detail-list .list-sec').index();		//当前选中的index
								detail_def_obj.detail_lun.hist_n = -_i;
								$('.js-detail-list').stop().animate({
									"margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
								},400,function(){
									$('.js-list-prev').addClass('list-hv-on');
									detail_def_obj.detail_lun.is_lun=false;	//跳出操作
								});
							}
							detail_def_obj.is_first_list=true;
						}
					},
					error:function(){
						detail_def_obj.detail_lun.is_lun=false;	//跳出操作
					}
				});
			}else{
				param = {
					'params':detail_def_obj._params,
					'key':detail_def_obj._key
				};
				if( detail_def_obj.origin_type==3 ){  //智能设计列表
					param = {
						'params': 'page_'+detail_def_obj.detail_lun.page+'-pageSize_'+detail_def_obj.detail_lun.page_size,
					}
					_url = '/patternlibrary/mihuidesignlist/';
				}

				general.fn.subAjax({
					"url": _url,
					ctp: "application/x-www-form-urlencoded",
					data: param,
					success:function(data){
						var _data=data['data']['list']||[],_len=_data.length,_html='',_info=data['info']||{},_size=_info.pagesize||90,_total=_info.total||2000;
						
						if(_len>0){
							for(var i=0;i<_len;i++){
								var _cover=_data[i]['cover']||'';
								if( !detail_def_obj.img_reg.test(_cover) ){
									_cover = STATIC_URL2 + _cover;
								}
								var _id=_data[i]['id']||'';
								var _tb=_data[i]['t']||'';
								var _index=_data[i]['index']||0;
								
								if(detail_def_obj.is_first_list==false && _id==detail_def_obj.id && is_init==2){		
									_html+='<li class="list-sec" data-index="'+_index+'" data-id="'+_id+'" data-t="'+_tb+'"><div><img src="'+_cover+'"/></div></li>'
								}else{
									_html+='<li data-index="'+_index+'" data-id="'+_id+'" data-t="'+_tb+'"><div><img src="'+_cover+'"/></div></li>'
								}
							}
							
							if(_type==2 && is_init==2){
								$('.js-detail-list').prepend(_html);
							}else{
								$('.js-detail-list').append(_html);
								if(_size>_len){
									detail_def_obj.detail_lun.has_list=false;		//后面没有数据了
								}
							}
							
						}else{
							if(_type==1){
								detail_def_obj.detail_lun.has_list=false;		//后面没有数据了
							}
						}
						
						if($('.js-detail-list li').length>=2000){
							detail_def_obj.detail_lun.has_list=false;		//后面没有数据了
						}
		
						//加载dom增加列表宽度
						detail_def_obj.list_w+=_len*detail_def_obj.detail_lun.item_w;
						$('.js-detail-list').css({"width":detail_def_obj.list_w+'px'});
						
						if(is_init!=2){
							detail_def_obj.detail_lun.next_page++;
							var _i=$('.js-detail-list .list-sec').index();		//当前选中的index
							detail_def_obj.detail_lun.hist_n = -_i;
							$('.js-detail-list').stop().animate({
								"margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
							},400,function(){
								$('.js-list-prev').addClass('list-hv-on');
								detail_def_obj.detail_lun.is_lun=false;	//跳出操作
							});
						}else if(_type==1){	//向后加载
							detail_def_obj.detail_lun.next_page++;
							if(_move==1){
								var _i=$('.js-detail-list .list-sec').index();		//当前选中的index
								var cat_ele=$('.js-detail-list li').eq((_i+1));
								cat_ele.addClass('list-sec').siblings('li').removeClass('list-sec');
								$('.pics-item-control').eq(0).show();
								catSet(cat_ele);
								detail_def_obj.detail_lun.hist_n = -1-_i;
								$('.js-detail-list').stop().animate({
									"margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
								},400,function(){
									detail_def_obj.detail_lun.is_lun=false;	//跳出操作
								});
							}else{
								detail_def_obj.detail_lun.hist_n -= detail_def_obj.detail_lun.lun_n;
								if(Math.abs(detail_def_obj.detail_lun.hist_n)>=$('.js-detail-list li').length){
									detail_def_obj.detail_lun.is_lun=false;		//跳出轮播状态
									return;
								}
								$('.js-detail-list').stop().animate({
									"margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
								},500,function(){
									if(Math.abs(detail_def_obj.detail_lun.hist_n-detail_def_obj.detail_lun.lun_n)>=$('.js-detail-list li').length && detail_def_obj.detail_lun.has_list==false){
										$('.js-list-next').removeClass('list-hv-on');
									}
									detail_def_obj.detail_lun.is_lun=false;		//跳出轮播状态
								});
							}
						}else if(_type==2){
							detail_def_obj.detail_lun.hist_n -= _len;
							$('.js-detail-list').stop().css({"margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'});
							
							if(_move==2){
								//当前视线之外
								var _i=$('.js-detail-list .list-sec').index();		//当前选中的index
								var cat_ele=$('.js-detail-list li').eq((_i-1));
								cat_ele.addClass('list-sec').siblings('li').removeClass('list-sec');
								$('.pics-item-control').eq(1).show();
								catSet(cat_ele);
								detail_def_obj.detail_lun.hist_n = 1-_i;
								$('.js-detail-list').stop().animate({
									"margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
								},400,function(){
									if(Math.abs(detail_def_obj.detail_lun.hist_n-detail_def_obj.detail_lun.lun_n)>=$('.js-detail-list li').length && detail_def_obj.detail_lun.has_list==false){
										$('.js-list-next').removeClass('list-hv-on');
									}
									detail_def_obj.detail_lun.is_lun=false;	//跳出操作
								});
							}else{
								//轮播
								detail_def_obj.detail_lun.hist_n+=detail_def_obj.detail_lun.lun_n;
								detail_def_obj.detail_lun.hist_n=detail_def_obj.detail_lun.hist_n>0?0:detail_def_obj.detail_lun.hist_n;
								$('.js-detail-list').stop().animate({
									"margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
								},500,function(){
									if(detail_def_obj.detail_lun.hist_n==0 && detail_def_obj.detail_lun.pre_page==1){
										$('.js-list-prev').removeClass('list-hv-on');
									}
									detail_def_obj.detail_lun.is_lun=false;		//跳出轮播状态
								});
							}
						}else{
							detail_def_obj.detail_lun.is_lun=false;	//跳出操作
							detail_def_obj.detail_lun.next_page++;
						}
						
						//轮播列表初始化
						if(detail_def_obj.is_first_list==false){		//list-hv-on
							var _i=$('.js-detail-list .list-sec').index();		//当前选中的index
							if(_i==0 && detail_def_obj.detail_lun.pre_page==1){
								$('.pics-item-control').eq(0).hide();
							}
							if(_i==(_len-1) && detail_def_obj.detail_lun.has_list==false){
								$('.pics-item-control').eq(1).hide();
							}
							var contain_w=$('.footer-cont').width()-240;
							detail_def_obj.detail_lun.lun_n = Math.floor(contain_w/detail_def_obj.detail_lun.item_w);
							if(detail_def_obj.detail_lun.pre_page > 1){
								$('.js-list-prev').addClass('list-hv-on');
							}
							if(detail_def_obj.detail_lun.is_lun==false && _len > (detail_def_obj.detail_lun.lun_n||10)){
								$('.js-list-next').addClass('list-hv-on');
							}
							
							detail_def_obj.detail_lun.is_lun=true;		//轮播状态
							$('.js-detail-search').removeClass('detail-search2');
							$('.js-patter-contain').slideDown(10);
							$('.js-detail-footer').addClass('bott-up');
							
							lunFirstL();		//轮播初始位置
							detail_def_obj.is_first_list=true;
						}
						
					},
					error:function(){
						detail_def_obj.detail_lun.is_lun=false;	//跳出操作
					}
				});
		
			}
			
			
		}
		
		
		//轮播位置初始化
		function lunFirstL(){
			var _len = $('.js-detail-list li').length;
			var _i = $('.js-detail-list .list-sec').index();		//当前选中的index
			var lun_n = detail_def_obj.detail_lun.lun_n||3;
			var _l = Math.abs( detail_def_obj.detail_lun.hist_n - lun_n);
			var _s = Math.abs( detail_def_obj.detail_lun.hist_n );
			
			if(_i+1 >= _s && _i+1 <= _l-1){		//当前视线之内
				detail_def_obj.detail_lun.is_lun=false;		//跳出 轮播状态
			}else if(_i+13 <= _len){
				detail_def_obj.detail_lun.hist_n = detail_def_obj.detail_lun.hist_n-_i;
				$('.js-detail-list').stop().animate({
					"margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
				},400,function(){
					detail_def_obj.detail_lun.is_lun=false;		//跳出 轮播状态
				});
			}else {			//视线外
				getPatterList(0,-1,1);
			}
		}
		
		
		//上一款
		function picPrev(){
			if(detail_def_obj.detail_lun.is_lun==true || detail_def_obj.is_first_list==false || detail_def_obj.is_cat_set==true){		//轮播中
				return;
			}
			detail_def_obj.detail_lun.is_lun=true;
			detail_def_obj.is_cat_set=true;
			
			var _len=$('.js-detail-list li').length;
			var _i=$('.js-detail-list .list-sec').index();		//当前选中的index
			var _l=Math.abs( detail_def_obj.detail_lun.hist_n - detail_def_obj.detail_lun.lun_n);
			var _s=Math.abs( detail_def_obj.detail_lun.hist_n );
			
			if(_i-1 >= _s && _i-1 <= _l && _i>0){		//当前视线之内
				var cat_ele=$('.js-detail-list li').eq((_i-1));
				cat_ele.addClass('list-sec').siblings('li').removeClass('list-sec');
				$('.pics-item-control').eq(1).show();
				catSet(cat_ele);
				setTimeout(function(){
					detail_def_obj.detail_lun.is_lun=false;	//跳出操作
				},200);
			}else if(_i<=0 && detail_def_obj.detail_lun.pre_page>1){		//当前列表之外加载数据
				if(_len>=2000){	//单张页最左边
					//超出长度提示
					msg.msg({"txt":'还有更多内容继续浏览搜索列表~'},1800);
					
					detail_def_obj.is_cat_set=false;
					detail_def_obj.detail_lun.is_lun=false;	//跳出操作
					return;
				}
				getPatterList(2,2);
			}else{
				//当前视线之外
				if(1-_i>0){
					detail_def_obj.detail_lun.is_lun=false;
					detail_def_obj.is_cat_set=false;
					return;
				}
				var cat_ele=$('.js-detail-list li').eq((_i-1));
				cat_ele.addClass('list-sec').siblings('li').removeClass('list-sec');
				$('.pics-item-control').eq(1).show();
				catSet(cat_ele);
				detail_def_obj.detail_lun.hist_n = 1-_i;
				$('.js-detail-list').stop().animate({
					"margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
				},300,function(){
					if(detail_def_obj.detail_lun.hist_n==0 && detail_def_obj.detail_lun.pre_page==1){
						$('.js-list-prev').removeClass('list-hv-on');
					}
					detail_def_obj.detail_lun.is_lun=false;	//跳出操作
				});
			}
		}
		
		//下一款
		function pixNext(){
			if(detail_def_obj.detail_lun.is_lun==true || detail_def_obj.is_first_list==false || detail_def_obj.is_cat_set==true){		//轮播中
				return;
			}
			detail_def_obj.detail_lun.is_lun=true;
			detail_def_obj.is_cat_set=true;
			var _i=$('.js-detail-list .list-sec').index();		//当前选中的index
			var _l=Math.abs( detail_def_obj.detail_lun.hist_n - detail_def_obj.detail_lun.lun_n);
			var _s=Math.abs( detail_def_obj.detail_lun.hist_n );
			
			var _len=$(".js-detail-list li").length;
			
			if(_i+1 >= _s && _i+1 <= _l-1 && _i<_len-1){		//当前视线之内
				var cat_ele=$('.js-detail-list li').eq((_i+1));
				cat_ele.addClass('list-sec').siblings('li').removeClass('list-sec');
				$('.pics-item-control').eq(0).show();
				catSet(cat_ele);
				setTimeout(function(){
					detail_def_obj.detail_lun.is_lun=false;	//跳出操作
				},300);
			}else {			//视线外
				if(_i>=_len-13 && _len<2000 && detail_def_obj.detail_lun.has_list==true){		//当前列表之外加载数据
					getPatterList(1,1);		//后面添加数据并移动
				}else{
					if(_i>=_len-1){	
						if(_len>=2000 ){	//最右边
							//超出长度提示
							msg.msg({"txt":'还有更多内容继续浏览搜索列表~'},1800);
						}
						detail_def_obj.detail_lun.is_lun=false;
						detail_def_obj.is_cat_set=false;
						return;
					}
					var cat_ele=$('.js-detail-list li').eq((_i+1));
					cat_ele.addClass('list-sec').siblings('li').removeClass('list-sec');
					$('.pics-item-control').eq(0).show();
					catSet(cat_ele);
					detail_def_obj.detail_lun.hist_n = -1-_i;
					$('.js-detail-list').stop().animate({
						"margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
					},400,function(){
						detail_def_obj.detail_lun.is_lun=false;	//跳出操作
					});
				}
			}
		}
		
		
		
		function docparentKeyd(ev){
			//图案列表初始化后
			if(detail_def_obj.detail_lun.is_lun!=true && detail_def_obj.is_first_list!=false){
				var e=ev||event;
				var _v=e.keyCode;
				if(_v==37){//左
					stopDefault(ev);
					if($('.js-pic-prev').length>0){
						$('.downimg-layer-data,.js-bg-layer').hide();
						picPrev();
					}
				}else if(_v==39){//右
					stopDefault(ev);
					if($('.js-pic-next').length>0){
						$('.downimg-layer-data,.js-bg-layer').hide();
						pixNext();
					}
				}else if(_v==38){//上
					stopDefault(ev);
					if($('.js-min-imgs li').length>1 && $('.js-min-imgs .img-sec').length>0){
						var _index=$('.js-min-imgs .img-sec').index();
						if(_index>0){
							$('.downimg-layer-data,.js-bg-layer').hide();
							$('.js-min-imgs li').eq(_index-1).addClass('img-sec').siblings('li').removeClass('img-sec');
							var min_pic=detail_def_obj.detail_list[_index-1],dragg_img=$('#draggable .js-bigbox');
							//重置大图
							draggImg(min_pic,dragg_img);
							btnLinkInit(detail_def_obj.detail_list,(_index-1));
						}
					}
				}else if(_v==40){//下
					stopDefault(ev);
					if($('.js-min-imgs li').length>1 && $('.js-min-imgs .img-sec').length>0){
						var _index = $('.js-min-imgs .img-sec').index();
						if(_index < $('.js-min-imgs li').length-1){
							$('.downimg-layer-data,.js-bg-layer').hide();
							$('.js-min-imgs li').eq(_index+1).addClass('img-sec').siblings('li').removeClass('img-sec');
							var min_pic=detail_def_obj.detail_list[_index+1],dragg_img=$('#draggable .js-bigbox');
							//重置大图
							draggImg(min_pic,dragg_img);
							btnLinkInit(detail_def_obj.detail_list,(_index+1));
						}
					}
				}else if(_v==27){		//esc
					closeLayer();		//关闭弹框
				}
			}
		}
		
		
		
		
	//--------------------------------------事件绑定--------------------------
		
		//键盘控制
		$(window.parent.document).on('keydown',docparentKeyd);
		$(document).on('keydown',docparentKeyd);
		
		//标签点击
		pop_layer.on("click", ".js-msg-list>li", function () {              
			var _href = $(this).data('href')||window.parent.location.href;
			window.parent.location.href = _href;
		});
		
		
		//关闭弹框
		pop_layer.on("click", ".js-pop-close", function () {              
			closeLayer();
		});
		
		
		pop_layer.on('click','.detail-down .downimg-layer-data' ,function(ev){
			var e = e || window.event;
			if (e.stopPropagation) { //W3C阻止冒泡方法
				e.stopPropagation();
			} else {
				e.cancelBubble = true; //IE阻止冒泡方法
			}
		});
		
		pop_layer.on('click',function(ev){
			$('.js-downimg-layer').hide();
		});
		
		// 右键点击弹出下载格式(款式库)
		$('.js-bigbox')[0].oncontextmenu = function(){
			$('.js-downimg-layer').hide();
			rightClick();
			return false;
		};
		
		//搜索列表--
		pop_layer.on('click','.js-detail-search',function(){
			if(detail_def_obj.is_first_list==true){
				if($(this).hasClass('detail-search2')){
					$(this).removeClass('detail-search2');
					$('.js-detail-footer').addClass('bott-up');
					$('.js-patter-contain').slideDown(200);
				}else{
					$('.js-detail-search').addClass('detail-search2');
					$('.js-detail-footer').removeClass('bott-up');
					$('.js-patter-contain').slideUp(200);
				}
			}
		});
		
		
		//细节图列表
		pop_layer.on('click','.js-min-imgs li', function(){
			if(!$(this).hasClass('img-sec') && !$(this).hasClass('imgs-no-hv')){
				var _index=$(this).index();
				$(this).addClass('img-sec').siblings('li').removeClass('img-sec');
				var min_pic=detail_def_obj.detail_list[_index],dragg_img=$('#draggable .js-bigbox');			
				//重置大图
				draggImg(min_pic,dragg_img);
				btnLinkInit(detail_def_obj.detail_list,_index);
			}
		});
		
		//放大缩小滚动条
		pop_layer.on('mousedown', '.js-control-l .icon',function(e){
			if($("#draggable .js-bigbox").length<=0 || $("#draggable .js-bigbox").is(":hidden")){
				return;
			}
			var ev=e||event;
			general.fn.stopBubble(ev);
			detail_def_obj.move_y=ev.clientY;
			detail_def_obj.is_move_pic=true;
			detail_def_obj.control_top=parseFloat($('.js-control-l .icon').css('top'));//初始top
		});
		
		//放大镜控制
		pop_layer.on('mousemove',function(e){
			if(detail_def_obj.is_move_pic==true){
				var ev=e||event;
				var _y=ev.clientY-detail_def_obj.move_y;
				_y=detail_def_obj.control_top+_y;
				if(_y>=0 && _y<=74){
					$('.js-control-l .icon').css({'top':_y+'px'});
					ySet(_y);		//同步放大倍数
				}
			}
		});
		
		//放大镜控制
		pop_layer.on('mouseup',function(e){
			detail_def_obj.is_move_pic=false;
		});
		
		
		//轮播左滑
		pop_layer.on('click','.js-list-prev',function(){
			if(detail_def_obj.detail_lun.is_lun==true || detail_def_obj.is_first_list==false){
				return;
			}
			detail_def_obj.detail_lun.is_lun=true;		//轮播状态
			var _len=$('.js-detail-list li').length;
			//到左边并有数据
			if(detail_def_obj.detail_lun.hist_n+detail_def_obj.detail_lun.lun_n >0 && detail_def_obj.detail_lun.pre_page>1 && _len<2000 ){
				//向前加载数据
				$('.js-list-next').addClass('list-hv-on');
				getPatterList(2);
				return;
			}
			
			if(_len>=2000 && detail_def_obj.detail_lun.hist_n>=0){	//单张页最左边
				//超出长度提示
				msg.msg({"txt":'还有更多内容继续浏览搜索列表~'},1800);
				
				detail_def_obj.detail_lun.is_lun=false;		//跳出轮播状态
				return;
			}
			
			var h_n = detail_def_obj.detail_lun.hist_n;
			detail_def_obj.detail_lun.hist_n += detail_def_obj.detail_lun.lun_n;
			detail_def_obj.detail_lun.hist_n = detail_def_obj.detail_lun.hist_n>0?0:detail_def_obj.detail_lun.hist_n;
			if(h_n!=detail_def_obj.detail_lun.hist_n){
				$('.js-list-next').addClass('list-hv-on');
			}
			$('.js-detail-list').stop().animate({
				"margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
			},500,function(){
				if(detail_def_obj.detail_lun.hist_n==0 && detail_def_obj.detail_lun.pre_page==1){
					$('.js-list-prev').removeClass('list-hv-on');
				}
				detail_def_obj.detail_lun.is_lun=false;		//跳出轮播状态
			});
		});
		
		//轮播右滑
		pop_layer.on('click','.js-list-next',function(){
			if(detail_def_obj.detail_lun.is_lun==true || detail_def_obj.is_first_list==false ){
				return;
			}
			detail_def_obj.detail_lun.is_lun=true;		//轮播状态
			var _len=$('.js-detail-list li').length;
			
			//距离右边近并且少于2000数据
			if(Math.abs(detail_def_obj.detail_lun.hist_n-detail_def_obj.detail_lun.lun_n) >= _len-25 && _len<2000){
				//向后加载
				if(detail_def_obj.detail_lun.has_list==true){
					$('.js-list-prev').addClass('list-hv-on');
					getPatterList(1);
					return;
				}
			}
			
			if(Math.abs(detail_def_obj.detail_lun.hist_n-detail_def_obj.detail_lun.lun_n)>=_len){		//轮播到最右端
				if(_len>=2000){			//超出长度提示
					msg.msg({"txt":'还有更多内容继续浏览搜索列表~'},1800);
				}
				detail_def_obj.detail_lun.is_lun=false;		//跳出轮播状态
				return;
			}
			detail_def_obj.detail_lun.hist_n -= detail_def_obj.detail_lun.lun_n;
			$('.js-detail-list').stop().animate({
				"margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
			},500,function(){
				$('.js-list-prev').addClass('list-hv-on');
				if(Math.abs(detail_def_obj.detail_lun.hist_n-detail_def_obj.detail_lun.lun_n)>=_len && detail_def_obj.detail_lun.has_list==false){
					$('.js-list-next').removeClass('list-hv-on');
				}
				detail_def_obj.detail_lun.is_lun=false;		//跳出轮播状态
			});
		});
		
		//上一款
		pop_layer.on('click','.js-pic-prev',function(){
			picPrev();
		});
		
		//下一款
		pop_layer.on('click','.js-pic-next',function(){
			pixNext();
		});
		
		//上下款hover效果
		$('.js-list-prev').hover(function(){
			if($(this).hasClass('list-hv-on')){
				$(this).css({
					'background-position':'-133px -180px'
				});
			}
		},function(){
			$(this).css({
				'background-position':'-204px -180px'
			});
		});
		
		$('.js-list-next').hover(function(){
			if($(this).hasClass('list-hv-on')){
				$(this).css({
					'background-position':'-349px -180px'
				});
			}
		},function(){
			$(this).css({
				'background-position':'-276px -180px'
			});
		});
		
		
		//款式图案选择
		pop_layer.on('click','.js-detail-list li',function(){
			if(detail_def_obj.detail_lun.is_lun==true || detail_def_obj.is_first_list==false || detail_def_obj.is_cat_set==true){		//轮播中
				return;
			}
			detail_def_obj.is_cat_set=true;
			$(this).addClass('list-sec').siblings('li').removeClass('list-sec');
			catSet($(this));
		});
			
		// 图片初始大小--原图
		pop_layer.on('click', '.js-original', function() {  
			if($("#draggable .js-bigbox").length<=0 || $("#draggable .js-bigbox").is(":hidden")){
				return;
			}
			$('.js-reset').removeClass('on-reset');
			natural($("#J_Visit img.js-bigbox"));   
			intBigImg();
		});
		
		// 重置图片--复位
		pop_layer.on('click', '.js-reset', function() {
			if($("#draggable .js-bigbox").length<=0 || $("#draggable .js-bigbox").is(":hidden")){
				return;
			}
			if(!$(this).hasClass('on-reset')){
				$(this).addClass('on-reset');
				imgSize($("#draggable .js-bigbox"),detail_def_obj.imgW,detail_def_obj.imgH);    
				intBigImg();
			}
		});
		
		pop_layer.on('click', '.js-bp-set .dic-close', function(ev) {
			general.fn.stopBubble(ev);		//阻止事件冒泡
			global_storage.yuntu_bp_set = 1;
			general.fn.setLocalSto('global_storage',global_storage);
			$(this).parents('.js-bp-set').hide();
		});
		
		// 虚拟样衣
		function popReplace (str) {
			str = str.replace(/</g,'pop389').replace(/>/g,'pop390').replace(/-/g,'pop380').replace(/_/g,'pop381').replace(/~/g,'pop382').replace(/!/g,'pop383').replace(/\./g,'pop384').replace(/\*/g,'pop385').replace(/\(/g,'pop386').replace(/\)/g,'pop387').replace(/&/g,'pop388').replace(/\'/g,'pop391').replace(/\+/g,'pop392').replace(/\#/g,'pop35');
			return str;
		}
		pop_layer.on('click', '.js-yun-vcloth', function() {
			var self = $(this);
			var t = self.data('t')||'';
			var id = self.data('id')||'';
			var pic = self.data('pic')||'';
			actionFunc(detail_def_obj.t,detail_def_obj.id,'imitate',request_id,scene_type);
	//	        var sign = self.attr('data-sign');
			/*var params={table:t, id:id};
			$.ajax({
				type: "get",
				url: "/ajax/recordFitting/" + Math.random(),
				data: params,
				async: true
			});*/
			var _url = '/virtualtryon/virtualspl/';
			window.parent.location.href = (_url+'?id='+id+'&t='+t+'&pattern='+pic+'&origin_type='+detail_def_obj.origin_type);
		}); 
		
		//相似图案
		pop_layer.on('click', '.js-spatter-pic', function() {
			var self = $(this);
			var path = self.data('path')||'';
			var id = self.data('id')||'';
			var t = self.data('t')||'';
			var _url = '/similarpatterns/similarList/';
			window.parent.location.href = (_url+'?id='+id+'&t='+t+'&path='+path+'&origin_type='+detail_def_obj.origin_type);
		}); 
		//重新编辑图案
		pop_layer.on('click', '.js-edit-pattern-btn', function() {
			var self = $(this);
			var serial=self.attr('data-serial') || '';
			if(serial!=''){
				window.parent.location.href='https://yuntu.pop136.com/mihui/mihuiedit/?serial='+serial;
			}else{
				msg.msg({'txt':'参数错误！'},1200);
			}
		});
		//下载
		pop_layer.on('click', '.js-detail-down', function (ev) {
			general.fn.stopBubble(ev);
			// 权限控制
			var $detail = $('.js-bigbox');
			/*var id = $detail.attr('data-id')||'';
			var t = $detail.attr('data-t')||'';
			if( id=='' || t==''){
				return false;
			}*/
			var pop_download_table = $(".js-downimg-layer").find("table tbody");
			var sDownHtml='';
			var downloadType = detail_def_obj.download_type||'';
			
			setDownloadList(downloadType,pop_download_table,$detail);
			
			$('.js-downimg-layer').show();
			
		});
		

		function setDownloadList(data,tag,ele){
			var sDownHtml='';
			if (data) {
				//矢量书稿(有多种下载格式)
				var smallImagePath = ele.attr('data-sp');
				var _smallName = smallImagePath.split('/');
				var i = _smallName.length-1;
				var smallName = _smallName[i];//当前小图名称
				
				if(detail_def_obj.origin_type==3){
					for( var k in data ){
						if( smallName == data[k].sSmallName ){
							var downInfo = data[k].aDownInfo;
							if(downInfo){
								for( var suffix in downInfo ){
									var status=downInfo[suffix].status!=undefined?downInfo[suffix].status:'';
									var txt='下载';
									var serial=downInfo[suffix].serial!=undefined?downInfo[suffix].serial:'';
									if(status==3){
										txt='生成';
									}else if(status==2){
										txt='生成中...';
									}else if(status==1){
										txt='下载';
									}
									if(status==2){
										sDownHtml += "<tr class='status-"+status+"'><td>."+ suffix +"</td><td>"+ downInfo[suffix].type +"</td> <td>"+ downInfo[suffix].size +"</td><td><a class='download-btn js-download-btn' data-status='"+status+"' data-serial='"+serial+"' data-bp='"+ downInfo[suffix].bp +"'>"+txt+"</a></td></tr>";
										sDownHtml += "<tr><td colspan='4'>这个设计超赞，AI智能正在紧锣密鼓为您制作psd文件，需等待一段时间</td></tr>";
									}else{
										sDownHtml += "<tr><td>."+ suffix +"</td><td>"+ downInfo[suffix].type +"</td> <td>"+ downInfo[suffix].size +"</td><td><a class='download-btn js-download-btn' data-status='"+status+"' data-serial='"+serial+"' data-bp='"+ downInfo[suffix].bp +"'>"+txt+"</a></td></tr>";
									}		                            
								}
							}
						}
					}
				}else{
					for( var k in data ){
						if( smallName == data[k].sSmallName ){
							var downInfo = data[k].aDownInfo;
							if(downInfo){
								for( var suffix in downInfo ){
									sDownHtml += "<tr><td>."+ suffix +"</td><td>"+ downInfo[suffix].type +"</td> <td>"+ downInfo[suffix].size +"</td><td><a class='download-btn js-download-btn' data-bp='"+ downInfo[suffix].bp +"'>下载</a></td></tr>";
								}
							}
						}
					}
				}


				
				if(sDownHtml != ''){
					tag.html(sDownHtml);
				}
				
			}else{
				$('.img-shape').html(curImg.attr('data-shape')||'--');
				$('.img-size').html(curImg.attr('data-size')||'--');
				$('.downimg-layer-data').find('.js-download-btn').attr('data-bp', curImg.attr('data-bp'));
			}
		};


		// 弹框下载
		var is_set_psd=false;
		pop_layer.on('click', '.js-download-btn', function () {
			
			var status=$(this).attr('data-status')!=undefined?$(this).attr('data-status'):'';
			var serial=$(this).attr('data-serial')!=undefined?$(this).attr('data-serial'):'';
			if(is_set_psd==true){return false;}
			if(status!=''){
				if(status==3){	   
					is_set_psd=true;     		
					general.fn.subAjax({
						'url': '/mihui/mihuipsd/',
						'data':{'serial':serial},
						'ctp':'application/x-www-form-urlencoded',
						'success':function(data){  
							msg.msg({'txt':'PSD文件已在生成中，请耐心等待。',btn:true},function(){
								$('.js-downimg-layer').hide();
								$('.js-downimg-right').hide();
								$('.js-bg-layer').hide();
								is_set_psd=false;
								if(detail_def_obj.download_type[0].aDownInfo.psd!=undefined){
									detail_def_obj.download_type[0].aDownInfo.psd.status=2;
								}
								
							});
						},
						'error':function(){
							is_set_psd=false;
							$('.js-downimg-layer').hide();
							$('.js-downimg-right').hide();
							$('.js-bg-layer').hide();
						}
					});


				}else if(status==2){
					return false;
				}else if(status==1){
					
					var $detail = $('.js-bigbox');
					var bigImage = $(this).attr('data-bp')||'';

					if(detail_def_obj.origin_type==3){
						if(bigImage!=''){
							top.location.href=bigImage;
						}else{
							msg.msg({'txt':'下载链接为空。'},1200);
						}
						return false;
					}

					var bigImage_suffix =  bigImage.split('.');
					if( bigImage_suffix.pop() == 'jpg'){
						var _rename = $detail.attr('data-rename')==undefined||$detail.attr('data-rename')==""?"":"?rename="+$detail.attr('data-rename');
						bigImage = bigImage + _rename;
					}
					down(bigImage);
				}
			}else{
				var $detail = $('.js-bigbox');
				var bigImage = $(this).attr('data-bp')||'';
				var bigImage_suffix =  bigImage.split('.');
				if( bigImage_suffix.pop() == 'jpg'){
					var _rename = $detail.attr('data-rename')==undefined||$detail.attr('data-rename')==""?"":"?rename="+$detail.attr('data-rename');
					bigImage = bigImage + _rename;
				}
				down(bigImage);
			}
			actionFunc(detail_def_obj.t,detail_def_obj.id,'download',request_id,scene_type);
		}); 
		
		$('.js-close-arrow').on('click', function () {
			$('.downimg-layer-data, .js-bg-layer').fadeOut();
		});	
		
		// 导航收缩
		pop_layer.on('click', '.js-nav-control', function () {
			if(detail_def_obj.is_first_list==false || detail_def_obj.detail_lun.is_lun==true){	
				return;
			}
			detail_def_obj.detail_lun.is_lun = true;	//禁止轮播
			if($(this).hasClass('nav-control2')){		//收
				$(this).removeClass('nav-control2');
				$('.js-nav-right').animate({'right':'0'},200);
				$('.js-pop-close,.js-pic-next').animate({'right':'380px'},200);
				$('.js-pic-control').animate({'right':'410px'},200);
				$('.js-detail-content,.js-detail-footer>div').animate({'padding-right':'380px'},200,function(){
					lunInit();
					detail_def_obj.detail_lun.is_lun = false;
				});
			}else{
				$(this).addClass('nav-control2');
				$('.js-nav-right').animate({'right':'-380px'},200);
				$('.js-pop-close,.js-pic-next').animate({'right':'0'},200);
				$('.js-pic-control').animate({'right':'30px'},200);
				$('.js-detail-content,.js-detail-footer>div').animate({'padding-right':'0'},200,function(){
					lunInit();
					detail_def_obj.detail_lun.is_lun = false;
				});
			}
		}); 
		


		// 收藏
		var def = {
			is_collect:false,
			collect_data:{}
		}
		pop_layer.on("click",'.js-collect-btn', function(e) {
			general.fn.stopBubble(e);
				if(!def.is_collect){
				collectFunc($(this));
				}
		})
		
		function collectFunc(obj){
			def.is_collect = true;
			var data_a = obj.find("a");
			var id = data_a.data("id");
			var t = data_a.data("t");
			actionFunc(t,id,'collect',request_id,scene_type);
			if(obj.hasClass("on")){
				def.collect_data = {
					id:id,
					t:t,
					handle:'cancel'
				}
			}else{
				def.collect_data = {
					id:id,
					t:t,
					handle:'join'
				}
			}
			$.ajax({
				url:'/collect/setcollect/'+Math.random(),
				type:'POST',
				data:def.collect_data,
				success:function(data) {
					// 取消成功
					if(parseInt(data.data.code) === 10){
						obj.removeClass('on').html('<a  class="bott-item" href="javascript:void(0);" title="加入收藏" data-id="'+id+'" data-t="'+t+'"><span class="icon icon8"></span>加入收藏</a>')
						msg.msg({'txt':data.data.msg},1200);
					}
					// 加入成功
					if(parseInt(data.data.code) === 20) {
						obj.addClass('on').html('<a  class="bott-item" href="javascript:void(0);" title="已收藏" data-id="'+id+'" data-t="'+t+'"><span class="icon icon8"></span>已收藏</a>');
						msg.msg({'txt':data.data.msg},1200);
					}
					def.is_collect=false;
				},
				error:function(){              
					def.is_collect=false;
				}
			})
		}

		
		// 用户行为统计
		var action_data = {
			t:"",
			id:"",
			userid:userId,
			site:1,
			action_type:"",
			timestamp:""
		};
		var iframe_src = $(".js-detail-frame",parent.document).find("iframe").attr("src")
		var win_search = general.fn.getLocationParameter(iframe_src);
		var request_id = win_search.request_id || "";
		var scene_type = win_search.scene_type || "";
		function actionFunc(tablename,id,action_type,request_id,scene_type,func){
			if(userType == "TOURIST"){
				return false;
			}
			action_data.request_id = request_id;
			action_data.scene_type = scene_type;
			action_data.t= tablename;
			action_data.id=id;
			action_data.action_type = action_type;
			action_data.timestamp = new Date().getTime();
			$.ajax({
				type: "get",
				dataType: "jsonp",
				url: '//api.pop136.com/internal/datagrand.php?'+Math.random(),
				data:action_data,
				success:func
			})
		}

		// 相关推荐		
		function guessLike(){
			var def = {
				guess_data:{
					t:detail_def_obj.t,
					id:detail_def_obj.id
				},
				is_add_dom:false
			}		
			getData()
			function getData(){
				def.is_add_dom = true;		
				$.ajax({
					type: "post",
					dataType: "json",
					url: '/recommend/get_like_data/',
					data:def.guess_data,
					success:function(data){
						def.is_add_dom = false;
						var arr=data.data||[];
						if(!arr.length || data.code == 1001){
							return false;
						}
						var request_id =data.info.request_id?data.info.request_id:"";						
						$(".guess-like-list").show();
						loadData(arr,request_id);				
					}
				})				
			}
			

			function loadData(totalArr,request_id){		
				var ele = $(".js-guess-like");
				var _html = "";
				for(var i=0;i<totalArr.length;i++){
					var cover=totalArr[i]["cover"]?totalArr[i]["cover"]:"";
					var title=totalArr[i]["title"]?totalArr[i]["title"]:"";
					var tableName= totalArr[i]["t"]?totalArr[i]["t"]:"";
					var id=totalArr[i]["id"]?totalArr[i]["id"]:"";
					var columnId=totalArr[i]["columnId"]?totalArr[i]["columnId"]:"";					
					var link=totalArr[i]["link"]?totalArr[i]["link"]:"";
					_html+='<li>';
					_html+='<a data-t="'+tableName+'" data-col="'+columnId+'" data-id="'+id+'" data-request_id="'+request_id+'" target="_blank">';
					_html+='<img alt="'+title+'" src="'+cover+'" />';
					_html+='</a>';
					_html+='<div class="js-dislike-btn">不喜欢</div>';
					_html+='</li>';
				}            
				ele.html(_html);
				$('.Scrollbar').mCustomScrollbar({
					theme:'dark'
				});		
			}
			
		}
		
		pop_layer.on("click", ".js-guess-like a", function() {
			var this_t = $(this).data("t");
			var this_id = $(this).data("id");
			var request_id = $(this).data("request_id");
			var frame_url = '/patternlibrary/detail/?id='+this_id+'&t='+this_t+'&request_id='+request_id+'&scene_type=relate_cloud&origin_type=5';
			$('.js-detail-frame',parent.document).find('iframe').attr('src',frame_url);
			actionFunc(this_t,this_id,'rec_click',request_id,'relate_cloud');
		});

		pop_layer.on("click", ".js-dislike-btn", function() {
			var this_t = $(this).siblings("a").data("t");
			var this_id = $(this).siblings("a").data("id");
			var request_id = $(this).siblings("a").data("request_id");
			var _this_html = $(this).parents("li");
			actionFunc(this_t,this_id,'dislike',request_id,'relate_cloud',function(){
				_this_html.remove();
			});
		})
	})

	});