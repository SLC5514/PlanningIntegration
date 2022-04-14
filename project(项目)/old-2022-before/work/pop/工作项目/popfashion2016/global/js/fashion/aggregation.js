/**
 * 弹出层js效果
 */
//初始数据
var P_UserType_action = '4';
if(P_UserType=='1'||P_UserType=='2'){
	P_UserType_action = '1';
}else if(P_UserType=='3'){
	P_UserType_action = '2';
}else if(P_UserType=='4'){
	P_UserType_action = '3';
}else if(P_UserType=='5'){
	P_UserType_action = '4';
}

var statistics_action_token = '';
if( typeof statistics_token == 'undefined'){
    statistics_action_token = '';
}else{
    statistics_action_token = statistics_token;
}

var link_reg_bef = /^http+s?:\/\//i;

//初始数据
var detail_def_obj={
	"fast_view":0,
	"table":'',
	"index":0,
	"id":'',
	"col":'',
	"is_cat_set":false,
	"move_y":0,				//滚动条移动距离
	"is_move_pic":false,	//是否移动中
	"control_top":74,		//放大镜top距离
	"min_radio":0.5,		//图片放大最小倍数
	"max_radio":3,			//图片放大最大倍数
	"is_first_list":false,	//第一次加载list
	"list_w":10000,		//列表宽度
	'imgW':0,           //图片宽
	'imgH':0,			//图片宽
	'_bigbox':'',
    "lun_timer":null,
    
    "action_down": true,   //下载统计
    "action_timer": null,   
    "action": {		//统计数据
        token: statistics_action_token,
		iUserType: P_UserType_action,		//用户类型
        	
		iUserId: '',  			//用户ID
		sChildUserId: '', 		//子账号id
		sTableName: '', 		//假表名
		iPrid: '',				//当前详情ID
		
		iColumnId: '',			//主栏目ID
		iSubColumnId: '',  		//子栏目ID
		sSelfUrl: location.href, 			//当前URL
		sRefererUrl: location.href,		//上个页面URL
		iSite: 1, 				//站点
		sLang: 'cn',			//语言
		
        iVisit: 0    		//是否能访问 0-无访问权限 1-有访问权限
        
	},

	"detail_lun":{			//底部轮播
		item_w:76,		//推荐li宽度
		lun_n:1,		//一次滚动宽度
		hist_n:0,	//历史滚动距离
		is_lun:false,		//是否轮播状态
		has_list:true,		//是否有数据了	
		page:1,				//页数
		pre_page:1,
		next_page:1,
		data:{}
    },
    'jump_url':""        //跳转链接
};



var POPTOOL = POPTOOL || {};
POPTOOL.pop = {};
POPTOOL.pop.replaceUrl = '';
var num = 0;


POPTOOL.pop.popLayer = function (popLayerId, url) {
	if ('pushState' in history) {
		history.pushState(null, null, url);
	} 
	POPTOOL.pop.replaceUrl = url;
    $('html,body').addClass('overflow-layer');
    popLayerId.show();

    detail_def_obj.action.sRefererUrl = document.referrer||'';
};
POPTOOL.pop.render = function (container, templateid, data) {
    var html = template(templateid, data);
	if (templateid == 'T_Title') {
		document.title = data.stitle + '-POP服装趋势网';
	}
    container.html(html);
};

// 浏览量统计
function actionView(){
    var action_obj = detail_def_obj.action;
    if( isBook || action_obj.sTableName == 'imgcol_set' ){ // 排除书籍的
        return;
    }
	
	$.ajax({
		"url": "//api.pop136.com/internal/statistics.php?action=view&" + Math.random(),
		"data": action_obj,
        "type": 'get',
        'dataType': "jsonp",
        'jsonp': "callback"
	});
}

// 下载统计
function actionDown(){
    var action_obj = detail_def_obj.action;
    if( detail_def_obj.action_down && !isBook && action_obj.sTableName != 'imgcol_set' ){     // 在能够下载统计时， 排除书籍的
        detail_def_obj.action_down = false;
        
        $.ajax({
            "url": "//api.pop136.com/internal/statistics.php?action=down&" + Math.random(),
            "data": action_obj,
            "type": 'get',
            'dataType': "jsonp",
            'jsonp': "callback"
        });

        clearTimeout(detail_def_obj.action_timer);
        detail_def_obj.action_timer = setTimeout(function(){
            detail_def_obj.action_down = true;
        }, 3000);
    }
}

detail_def_obj.action.iUserId = P_AccountId;	//主账号
if(P_UserType=='2'){
	detail_def_obj.action.sChildUserId = P_UserId;	//子账号  
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
		$(".js-control-l").find("i").css('top',_top+'px');
	}
}

(function ($) {
	// 弹层
	var layer_bg = $('.js-bg-layer');  
    var collectBox = $(".js-content-layer");
    var main = $('#main .picbox');
    
    var timer;
    var nowUrl = window.location.href;
    var index = 0;
    var total = 0;
    var pop_layer = $('#pop_layer');
    var allPic = $('.rightc_main');
    var p = $('#link').data('param');
    var J_Title = $('#J_Title');                // 标题
    var J_LeftRightBtn = $('#J_LeftRightBtn');  // 左右键
    var J_StyleInfo = $('#J_StyleInfo');        // 款式信息
    var J_Label = $('#J_Label');                // 关联标签
    var J_MoreSea = $('#J_MoreSea');            // 更多搜索
    var J_MoreSeaNum = $('#J_MoreSeaNum');      // 搜索结果数量
    var J_ReadList = $('#J_ReadList');          // 更多读物
    var J_StyleAd = $('#J_StyleAd');            // 广告
    var J_DownloadType = $('#J_DownloadType');  // 图片下载类型
    var J_DownloadType2= $('#J_DownloadType2'); // 图片下载类型右键
    var J_MoreRec = $('#J_MoreRec');            // 更多推荐
    var J_Collect = $('#J_Collect');            // 更多推荐 T_Collect   T_JoinW T_Download  T_Share
    var J_Download = $('#J_Download');          //下载单张
    var J_CeDownload = $('#J_CeDownload');      //下载全册
    var J_MoreRecDis = $('#J_MoreRecDis');      //更多推荐是否显示
    var J_BookBuy = $('#J_BookBuy');      		//书籍购买
    var J_Visit   =$("#J_Visit");               //判断栏目权限
    var isF5 = pop_layer.data("f5") ? true : false;
    var t, id, col;
    // 模板调用
    function replaceChangeContent(data, _i,_index) {
    	
    	data.isBook = isBook;		//区分书籍T台
    	data.isMagazine = isMagazine;		//区分杂志
    	
    	data.moreList = data.moreList||[];		//更多读物
    	if(data.moreList.length<=0){
    		$('.js-img-detail').hide();
    	}else{
    		$('.js-img-detail').show();
    	}
    	
    	_i = _i==undefined?-1:_i;
    	_index = _index||-1;
    	
    	if(_index>=3 && data.fastView && isBook){	//书籍中快速浏览中只有前3个可以看图
    		data.is_fast=true;
    	}else{
    		data.is_fast=false;
    	}
    	
        POPTOOL.pop.render(J_Title, 'T_Title', data);
        POPTOOL.pop.render(J_LeftRightBtn, 'T_LeftRightBtn', data);
        POPTOOL.pop.render(J_StyleInfo, 'T_StyleInfo', data);
        POPTOOL.pop.render(J_Label, 'T_Label', data);
        POPTOOL.pop.render(J_StyleAd, 'T_StyleAd', data);
        POPTOOL.pop.render(J_DownloadType, 'T_DownloadType', data);
        POPTOOL.pop.render(J_Download, 'T_Download', data);
        POPTOOL.pop.render(J_DownloadType2, 'T_DownloadType', data);
        if(J_Collect.length>0){
        	POPTOOL.pop.render(J_Collect, 'T_Collect', data);
        }
        POPTOOL.pop.render(J_CeDownload, 'T_CeDownload', data);
        POPTOOL.pop.render(J_Visit, 'T_Visit', data);
    	POPTOOL.pop.render(J_ReadList, 'T_ReadList', data);
        POPTOOL.pop.render(J_BookBuy, 'T_BookBuy', data);
        
        //收藏数据配置
        var collect_btn=$('#J_Collect>a');
        if(collect_btn.length>0){
        	if ($('#param').length) {
				var _link = $('#param');
				var _id = _link.data('id')||'';
				var _t = _link.data('t')||'';
				var _col = _link.data('col')||'';
			} else if($('#link').length){
				var _link = $('#link');
				var _id = _link.data('id')||'';
				var _t = _link.data('t')||'';
				var _col = _link.data('col')||'';
			}else{
				var _id = detail_def_obj.id;
				var _t = detail_def_obj.table;
				var _col = detail_def_obj.col;
			}
			
			collect_btn.data('id',_id);
			collect_btn.data('tab',_t);
			collect_btn.data('col',_col);
        }
        
        
        if($('.js-msg-i4 ul>li').length<=0){
        	$('.js-msg-i4').hide();
        	$('.right-top').css({'padding':'0 0 15px 19px'});
        }else{
        	$('.js-msg-i4').show();
        	$('.right-top').css({'padding':'0 0 6px 19px'});
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
		var min_pic=data.detailList||[],dragg_img=$('#draggable img');
		$('#J_Download').parents('li').show();
		$('.js-pic-control').show();
		if(data.visit==true && !data.is_fast){		//有权限
			$('.pics-item-control').removeClass('no-bott-set');
			$(".js-detail-footer").removeClass('visi-hidden');
			if(min_pic.length>0){
				var b_pic=min_pic[0]||{};
				intBigImg();
				draggImg(b_pic,dragg_img);
			}
		}else{
			// 无栏目权限
			if(min_pic.length>0){
				var b_pic=min_pic[0]||{};
				var null_img=$(".js-null-img"),small_img=b_pic.smallPath||'';
				null_img.attr('src',small_img);
			}
			if(data.is_fast){		//快速浏览无权限
				$('#J_Download').parents('li').hide();		//快速浏览后面的不能下载
				$('.js-pic-control').hide();
				$('.pics-item-control').removeClass('no-bott-set');
				$(".js-detail-footer").removeClass('visi-hidden');
			}else{		//没有权限情况
				$('.pics-item-control').addClass('no-bott-set');
				$(".js-detail-footer").addClass('visi-hidden');
			}
		}
		
		detail_def_obj._bigbox = pop_layer.find('.js-bigbox');
		// 右键点击弹出下载格式(款式库)
	    if (detail_def_obj._bigbox.length > 0) {
	        detail_def_obj._bigbox[0].oncontextmenu = function(){
	        	$('.js-downimg-layer').hide();
	            rightClick(2);
	            return false;
	        };
	    }
	    
	    //副标题文字限制
		var report_msg_ele=$('.report-msg>div');
		if(report_msg_ele.length>0){
			var report_msg = report_msg_ele.text()||'';
			var new_report_msg=pop_fashion_global.fn.cutByWidth(report_msg,424,14)||'';
	        if(new_report_msg!=report_msg){
	        	$('.report-msg>div').html(new_report_msg+' '+'<span>更多</span>');
	        }
		}
	    
	    if(!$('.js-detail-search').hasClass('detail-search2')){
	    	$('.pics-item-control').addClass('item-control-set');
	    }else{
	    	$('.pics-item-control').removeClass('item-control-set');
	    }
		imgScroll();
        detail_def_obj.is_cat_set=false;
    }
    
    // 点击图片触发弹层
    main.on('click', 'a.img_click', function () {
        var self = $(this);
        var total = self.data('total');
        var index = self.data('index');
        var table = self.data('t')||'';
        t = table;
        id = self.data('id')||'';
        col = self.data('col')||'';
        var ver = self.data('ver')||'';
		preindex = index;
		
		var this_url=window.location.href;
		if(this_url.indexOf('page_')!=-1){
			var _page=parseFloat(this_url.split('page_')[1])||1; 
		}else{
			var _page=1; 
		}
		if ($('#param').length) {
			var link = $('#param');
        	var pid = link.data('id')||'';
		} else if($('#link').length){
			var link = $('#link');
        	var pid = link.data('id')||'';
		} else {
        	var pid = '';
		}
        
        var url = '/details/bookpresscon/';
		var notbook = true;
		
		
        if(IsPC()==false){
            window.location.href = detail_def_obj.jump_url;
            return ;
        }
        var encrypt = $("#encrypt");
        var timeStamp = encrypt.data("timestamp");
        var sign = encrypt.data("sign");
        var token = encrypt.data("token");
        // var s_p;
        if (col == 70 || col == 71 || col == 72 || col == 73 || col == 113 || col == 114 || col == 115 || col == 131) {
			detail_def_obj.jump_url = url+'t_'+table+'-id_'+id+'-col_'+col+'-index_'+index+'/';
            var notbook = false;
		} else {
			detail_def_obj.jump_url = url+'t_'+table+'-id_'+id+'-col_'+col+'-ver_'+ver+'/';
		}
		
		if(isMagazine){
            url = '/details/magazine/';
        	detail_def_obj.jump_url = '/details/magazine/t='+table+'&id='+id+'&ver='+ver+'/';
        }
        var s_p = {t:t,id:id,col:col,timeStamp:timeStamp,sign:sign,total: total, index: index, pid:pid, ver:ver};            
        var s_p_str = JSON.stringify(s_p);
        var params = {s_p:rsa_encrypt(s_p_str),token:token};

        // var params = {};	// 取数据库
        /* var POP_USER = $.cookie('POP_USER'), usertype_runw=''; */
        if( typeof P_UserType != 'undefined' ){
            usertype_runw = P_UserType;
        }
        // POP_USER
        if((P_UserId == null || P_UserId == '') || usertype_runw==4 || $('.apply-try-runw').length>0 ) {
            // location.href = curUrl;
            // window.open(curUrl);
            if(typeof msg != 'undefined'){
                msg.msg({"txt":'开通VIP试用，浏览大图'},2000);
            }else{
                alert('开通VIP试用，浏览大图');
            }
        } else {
            $.ajax({
                type: "post",
                data: params,
                dataType: "json",
                url: url,
                beforeSend: function () {
                    $("#loadingBg").show();
                },
                success: function (data) {
                    var sign = encrypt.data("sign");
                    data = JSON.parse(aes_decrypt(data.data,sign));
                    console.log(data);
                    //登陆状态失效
                    if (data.code=="5001") {
                        window.location.href = data.url;
                        return false;
                    }
                	var fast_view=data.fastView||0;
                    replaceChangeContent(data,-1,index);
                    //页面初始化
                    detailInit(id,col,index,ver,table,_page,fast_view);  

                    // 当前url 
                    detail_def_obj.action.sSelfUrl = detail_def_obj.jump_url+'';
                    if(detail_def_obj.action.sSelfUrl.indexOf('pop-fashion.com')==-1){
                    	detail_def_obj.action.sSelfUrl = 'https://www.pop-fashion.com'+detail_def_obj.action.sSelfUrl;
                    }
                    if(data.visit){
                    	detail_def_obj.action.iVisit = 1;
                    }else{
                    	detail_def_obj.action.iVisit = 0;
                    }
                	
                    POPTOOL.pop.popLayer(pop_layer, detail_def_obj.jump_url);

                    detail_def_obj.action.sTableName = table;
			    	detail_def_obj.action.iPrid = id;
			    	detail_def_obj.action.iColumnId = col;
			    	
                    //统计浏览量
                    actionView();

                    $("#loadingBg").hide();
                },
                complete: function () {
                    $("#loadingBg").hide();                    
                }
            });
        }
		return false;
    });
    
    //页面初始化
    function detailInit(_id,_col,_index,_ver,_table,_page,fast_view){
    	_page = _page||1;
    	_table = _table||'';
    	_ver=_ver||'';
    	_index=_index||0;
    	_id=_id||'';
    	_col=_col||'';
    	//缓存初始化
    	detail_def_obj['fast_view'] = fast_view;
    	detail_def_obj['table'] = _table;
    	detail_def_obj['index'] = _index;
    	detail_def_obj['id'] = _id;
    	detail_def_obj['col'] = _col;
    	detail_def_obj['is_cat_set'] = false;
    	detail_def_obj['move_y'] = 0;			    //滚动条移动距离
    	detail_def_obj['is_move_pic'] = false;	    //是否移动中
    	detail_def_obj['control_top'] = 74;		//放大镜top距离
    	detail_def_obj['min_radio'] = 0.5;		//图片放大最小倍数
    	detail_def_obj['max_radio'] = 3;			//图片放大最大倍数
    	detail_def_obj['is_first_list'] = false;	//第一次加载list
    	detail_def_obj['list_w'] = 10000;		//列表宽度
    	detail_def_obj['imgW'] = 0;           //图片宽
    	detail_def_obj['imgH'] = 0;			//图片宽
    	detail_def_obj['_bigbox'] = '';
    	detail_def_obj['lun_timer'] = null;
    	//底部轮播
    	detail_def_obj['detail_lun']['item_w'] = 76;		//推荐li宽度
    	detail_def_obj['detail_lun']['lun_n'] = 1;		//一次滚动宽度
    	detail_def_obj['detail_lun']['hist_n'] = 0;		//历史滚动距离
    	detail_def_obj['detail_lun']['is_lun'] = false;		//是否轮播状态
    	detail_def_obj['detail_lun']['has_list'] = true;		//是否有数据了	
    	detail_def_obj['detail_lun']['page'] = 1;				//页数
    	detail_def_obj['detail_lun']['pre_page'] = 1;
    	detail_def_obj['detail_lun']['next_page'] = 1;
    	detail_def_obj['detail_lun']['data'] = {};
    	
		//大图控制说明
		var doc_w=$(window).outerWidth(true),doc_h=$(window).outerHeight(true);
		var tbook_bp_set=$.cookie('tbook_bp_set',{ path:'/', domain:'.pop-fashion.com'});
		if((doc_w<1300 || doc_h<600) && tbook_bp_set!=1){
			$('.js-bp-set').show();
		}else{
			$('.js-bp-set').hide();
		}
		
		var tbook_prompt_disc=$.cookie('tbook_prompt_disc',{ path:'/', domain:'.pop-fashion.com'});
		if(tbook_prompt_disc==1){
			$('.js-prompt .prompt-disc').hide();
		}else{
			$('.js-prompt .prompt-disc').show();
		}
		
		var _param=window.location.href;
		//获取更多图片列表
		if ($('#param').length) {
			var _link = $('#param');
        	var _pid=_link.data('id')||'';
			var _table=_link.data('t')||'';
			var link_id=_link.data('id')||'';
		} else if($('#link').length){
			var _link = $('#link');
			var _pid=_link.data('id')||'';
			var _table=_link.data('t')||'';
			var link_id=_link.data('id')||'';
		} else {
			var _pid=_param.split('id_')[1]||'';
    		_pid = _pid.split('/')[0]||'';
			var _table=_table;
			_pid = _pid||_id;
			var link_id=_pid||'';
		}
		link_id = link_id+''||'';
		if(link_id.indexOf('-page_')!=-1){
			link_id = link_id.split('-page_')[0]||'';
		}
		
		detail_def_obj.detail_lun.page=_page;
		detail_def_obj.detail_lun.pre_page=_page;
		detail_def_obj.detail_lun.next_page=_page;
		
		detail_def_obj.detail_lun.data['ver']=_ver;
		detail_def_obj.detail_lun.data['pid']=_pid;
		detail_def_obj.detail_lun.data['t']=_table;
		detail_def_obj.detail_lun.data['id']=link_id;
		detail_def_obj.detail_lun.data['page']=_page;
		
		//请求列表
		$('.js-detail-list').html('').css({"margin-left":0,"width":'10000px'});
		$('.js-patter-contain').hide();
		
		lunInit();
		lunSetStart(1);
		$('.js-detail-search').addClass('detail-search2');
		getPatterList(0,-1,2);
		
		
		$(window).on('resize',function(){
			
           	var _w=$(window).outerWidth(true),_h=$(window).outerHeight(true);
           	var tbook_bp_set=$.cookie('tbook_bp_set',{ path:'/', domain:'.pop-fashion.com'});
			if((_w<1300 || _h<600) && tbook_bp_set!=1){
				$('.js-bp-set').show();
			}else{
				$('.js-bp-set').hide();
			}
            lunInit();
		});
    }
    
    function stopDefault(ev){ 		//阻止默认事件
    	var e=ev || window.event; 
    	if(e && e.preventDefault){ 
    		e.preventDefault(); 
    	} else{ 
    		window.event.returnValue=false; 
    	} 
    	return false;
    }

    

    function closeLayer(){
        oCommon.hiddenWXshare();
        $('.load_close_btn,.close').trigger('click');
        $(".js-down-close,.js-workbench-close").trigger('click');
        pop_layer.hide();
        $('html,body').removeClass('overflow-layer'); 
        if ('pushState' in history) {
            history.replaceState(null, null, nowUrl);
        }
        $('.js-detail-list').html('');
        $('.js-patter-contain').hide();
        
        //右边导航展开
        // $('.js-nav-control').removeClass('nav-control2');
    	// $('.js-nav-right').css({'right':'0'});
    	// $('.js-pop-close').css({'right':'300px'});
        
        // 去除全屏
        $('.js-page-control').css({'right': '300px'});
        $('.js-detail-content,.js-detail-footer>div').css({'padding-right':'300px'});
        $('.js-max-box').removeClass('max-box2');
        $('.js-nav-right').css({'width': '299px'});
        $('.js-detail-search').attr('data-down', 0);
    }
    
    
    $('body').on("click", ".js-pop-close", function () {              
        closeLayer();
    });
    $('body').on('click','.js-detail-contant',function(){
        $(".js-downimg-layer,.js-join-workbench-layer,.bd_weixin_popup,.bd_weixin_popup_bg").hide();
    });

    $('body').on("click", ".js-detail-content", function (ev) {
        // ev.stopPropagation();
        if(!isF5){
            closeLayer();
        }
    });
    $('body').on('click','.detail-down,.js-welcome-add,#draggable>img,.bp-load' ,function(ev){
        stopPropagation(ev);
    });

    if (history.pushState) {
        // 浏览器后退按钮 触发关闭按钮点击事件
        window.addEventListener("popstate", function () {
            pop_layer.find('.js-pop-close').trigger('click');
        });
    }
    
    function catSet(_ele){
    	_ele=_ele||'';
    	if(_ele.length<=0){
    		detail_def_obj.is_cat_set=false;
    		return;
    	}
    	var self = _ele;
        var total = self.data('total');
        var index = self.data('index');
        var table = self.data('t')||'';
        id = self.data('id')||'';
        t = table;
        var ver = self.data('ver')||'';

		if ($('#param').length) {
			var link = $('#param');
        	var pid = link.data('id')||'';
        	var colPid = link.data('col');
		} else if($('#link').length){
			var link = $('#link');
        	var pid = link.data('id')||'';
        	var colPid = link.data('col');
		} else {
        	var pid = '';
        	var colPid = '';
        }
        var encrypt = $("#encrypt");
        var timeStamp = encrypt.data("timestamp");
        var sign = encrypt.data("sign");
        var token = encrypt.data("token");
		
        col = colPid||'';
        var url = '/details/bookpresscon/';
		if (col == 70 || col == 71 || col == 72 || col == 73 || col == 131) {
			detail_def_obj.jump_url = url+'t_'+table+'-id_'+id+'-col_'+col+'-index_'+index+'/';
		} else {
			detail_def_obj.jump_url = url+'t_'+table+'-id_'+id+'-col_'+col+'-ver_'+ver+'/';
		}
		
		if(isMagazine){
            url = '/details/magazine/';
        	detail_def_obj.jump_url = '/details/magazine/t='+table+'&id='+id+'&ver='+ver+'/';
        }

        var this_id = self.data('id')||'';
        var this_t = self.data('t')||'';
        var this_col = colPid;
        
        var s_p = {t:this_t,id:this_id,col:this_col,timeStamp:timeStamp,sign:sign,total: total, index: index, pid:pid, ver:ver};            
        var s_p_str = JSON.stringify(s_p);
        var params = {s_p:rsa_encrypt(s_p_str),token:token};
        $.ajax({
            type: "post",
            data: params,
            dataType: "json",
            url: url,
            success: function(data){
                var sign = encrypt.data("sign");
                data = JSON.parse(aes_decrypt(data.data,sign));	
            	var _i=_ele.index();
            	var index=_ele.data('index');
                replaceChangeContent(data,_i,index);
                POPTOOL.pop.popLayer(pop_layer, detail_def_obj.jump_url);
                
                // 当前url ,visit, userType
		        detail_def_obj.action.sSelfUrl = detail_def_obj.jump_url+'';
		        if(detail_def_obj.action.sSelfUrl.indexOf('pop-fashion.com')==-1){
		        	detail_def_obj.action.sSelfUrl = 'https://www.pop-fashion.com'+ detail_def_obj.action.sSelfUrl;
                }
                
                detail_def_obj.action.sTableName = this_t;
		    	detail_def_obj.action.iPrid = this_id;
                detail_def_obj.action.iColumnId = this_col;

                if(data.visit){
	            	detail_def_obj.action.iVisit = 1;
	            }else{
	            	detail_def_obj.action.iVisit = 0;
	            }
		    	// 浏览量统计
				actionView();
                

            },
            error:function(){
            	detail_def_obj.is_cat_set=false;
            }
        });
        
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
    	bp_obj=bp_obj||{};
    	$("#draggable img").hide();
    	ele2.attr({"src":''});
    	$('.js-bp-load').show();
    	if($('.js-detail-search').length<=0){
    		var bott_h=120;
    	}else if(detail_def_obj.is_first_list==false || !$('.js-detail-search').hasClass('detail-search2')){
			var bott_h=189;
		}else{
			var bott_h=79;
		}
    	var smallPath=bp_obj.smallPath||'';
    	var bigPath=bp_obj.bigPath||'';
    	var id=bp_obj.id||'';
    	var columnid=bp_obj.columnid||'';
    	var simpleName=bp_obj.simpleName||'';
    	var shape=bp_obj.shape||'';
        var size=bp_obj.size||'';
        var rename = bp_obj.rename || '';
    	smallPath = smallPath.indexOf("pop-fashion") !=-1?smallPath : static_url+smallPath;
		var s_obj = new Image(),b_obj = new Image();
		var obj_bp = bigPath;
		var obj_sp = smallPath;
		
	    s_obj.onload = function(){
	    	var detail_con=$(".js-detail-content");
	        var deW=detail_con.width();
	        var deH=detail_con.height();
	    	var _w=s_obj.width;
	    	var _h=s_obj.height;
	        if(_w > _h){
	            deW=parseInt(deW*85/100);
	            var now_h = _h/_w*deW;
	            if(now_h>(deH-bott_h)){
	            	deH=parseInt((deH-bott_h)*98/100);
		    		ele2[0].height=deH;
		    		ele2[0].width=_w/_h*deH;
	            }else{
	            	ele2[0].width=deW;
	    			ele2[0].height=now_h;
	            }
	        }else{
	            deH=parseInt((deH-bott_h)*96/100);
	    		ele2[0].height=deH;
	    		ele2[0].width=_w/_h*deH;
	        }
	        intBigImg();
	        $('.js-bp-load').hide();
	        $("#draggable img").show();
	    };
	    
	    b_obj.onload=function(){
    		var b_w=b_obj.width||1;
	    	var b_h=b_obj.height||1;
    		setTimeout(function(){
    			imgSize($("#draggable img"),b_w,b_h);
    		},200)
	    };
	    
	    s_obj.src = obj_sp;
	    b_obj.src =	obj_bp;
	    
		ele2.attr({
			"src":bigPath,
			"data-bp": bigPath,
			"data-id":id,
			"data-col":columnid,
			"data-sp":smallPath,
			"data-t":simpleName,
			"data-shape":shape,
            "data-size":size,
            "data-rename":rename
		});
		$('.js-reset').addClass('on-reset');   //初始不能复位
    }
    
    //底部轮播图
    function lunInit(){
		var _len=$('.js-detail-list li').length;
		if(_len>0){
			detail_def_obj.detail_lun.item_w = $('.js-detail-list li').eq(0).outerWidth(true)||76;		//获取li宽度
		}
		//大小屏包含框宽度
		var contain_w=$('.footer-cont').width()-108;
		detail_def_obj.detail_lun.lun_n = Math.floor(contain_w/detail_def_obj.detail_lun.item_w);
		detail_def_obj.detail_lun.lun_n = detail_def_obj.detail_lun.lun_n<=0?1:detail_def_obj.detail_lun.lun_n;
		
		if(detail_def_obj.detail_lun.lun_n >= 2 && detail_def_obj.detail_lun.has_list==false && _len>0){
			if(Math.abs(detail_def_obj.detail_lun.hist_n-detail_def_obj.detail_lun.lun_n) < _len){
				$('.js-list-next').addClass('list-hv-on');
			}
		}
	}
    
    //获取更多图案列表
    var bottomDataList = {};
    function getPatterList(_type, _move, is_init){
    	is_init=is_init==undefined?2:is_init;
    	is_init=is_init||2;
		_move=_move||-1;	//移动选中图案
        _type=_type||0;		//加载数据类型  加载，向左加载，向右加载 0,2,1
        var _url = isBook==1?'/ajax/getbookbottomdatalist/?':'/ajax/getPressBottomDataList/?';
        var src_bf = 'https://imgf3.pop-fashion.com';
        
        if(_type==1 || is_init!=2){
        	detail_def_obj.detail_lun.data['page']=detail_def_obj.detail_lun.next_page;		//记录下一页页数
        }else if(_type==2){
        	detail_def_obj.detail_lun.pre_page--;
        	detail_def_obj.detail_lun.data['page']=detail_def_obj.detail_lun.pre_page;		//记录上一页页数
        }
        
        if(isMagazine){		//杂志
        	_url = '/ajax/getmagazinebottomdatalist/';
        	if(detail_def_obj.is_first_list==false){
        		pop_fashion_global.fn.subAjax({
		    		"url":_url+Math.random(),
		    		ctp:"application/x-www-form-urlencoded",
		    		data:detail_def_obj.detail_lun.data,
		    		successFunc:function(data){
		    			bottomDataList = data;
		    			setBottomDataList(data);
		    		},
		    		errorFunc:function(){
		    			detail_def_obj.detail_lun.is_lun=false;	//跳出操作
		    		}
		    	});
        	}else{
        		setBottomDataList(bottomDataList);
        	}
        	
	    	function setBottomDataList(data){
    		 	$('.js-btn-load-left,.js-btn-load-right').fadeOut(100);
    			var _data=data['data']||[],_html='',_info=data['info']||{},_size=_info.pageSize||60,_total=_info.total||0;
    			var _start  = (detail_def_obj.detail_lun.data['page']-1)*_size,_end = detail_def_obj.detail_lun.data['page']*_size;
    			if(_end > _total){
    				_end = _total;
    			}
    			var _len = _end - _start;
    			if(_len>0){
    				for(var i=_start;i<_end;i++){
    					var ver=_data[i]['ver']||'';
    					var smallPicPath=_data[i]['smallPicPath']||'';

                        // js替换
                        if (smallPicPath.indexOf('fm.pop-fashion.com') != -1) {
                            smallPicPath = src_bf + smallPicPath.split('fm.pop-fashion.com')[1];
                        }
                        if (smallPicPath.indexOf('https://img') == -1) {
                            smallPicPath = src_bf + smallPicPath;
                        }

    					var _id=_data[i]['id']||'';
    					var _index=_data[i]['index']||0;
    					var _t = _data[i]['t']||'';
    					
    					if(detail_def_obj.is_first_list==false && _id==detail_def_obj.id && _index==detail_def_obj.index && is_init==2){		
					        _html+='<li class="list-sec" data-ver="'+ver+'" data-index="'+_index+'" data-id="'+_id+'" data-t="'+_t+'"><img src="'+smallPicPath+'"/></li>'
						}else{
							_html+='<li data-ver="'+ver+'" data-index="'+_index+'" data-id="'+_id+'" data-t="'+_t+'"><img src="'+smallPicPath+'"/></li>'
						}
    				}
    				
    				if(_type==2 && is_init==2){
	    				$('.js-detail-list').prepend(_html);
	    			}else{
	    				$('.js-detail-list').append(_html);
	    				if(_size>_len || _len>=_total){
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
    				var contain_w=$('.footer-cont').width()-108;
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
			        $('.pics-item-control').addClass('item-control-set');
			        
			        lunFirstL();		//轮播初始位置
					detail_def_obj.is_first_list=true;
				}
	    	}
        }else{
        	pop_fashion_global.fn.subAjax({
	    		"url":_url+Math.random(),
	    		ctp:"application/x-www-form-urlencoded",
	    		data:detail_def_obj.detail_lun.data,
	    		successFunc:function(data){
	    			$('.js-btn-load-left,.js-btn-load-right').fadeOut(100);
	    			var _data=data['data']||[], _len=_data.length, _html='', _info=data['info']||{}, _size=_info.pageSize||35, _total=_info.total||2000;
	    			
	    			if(_len>0){
	    				for(var i=0;i<_len;i++){
	    					var ver=_data[i]['ver']||'';
	    					var smallPicPath=_data[i]['smallPicPath']||'';

                            // js替换
                            if(smallPicPath.indexOf('fm.pop-fashion.com') != -1 ){
                                smallPicPath = src_bf + smallPicPath.split('fm.pop-fashion.com')[1];
                            }
                            if(smallPicPath.indexOf('https://img')==-1){
                                smallPicPath = src_bf+smallPicPath;
                            }


	    					var _id=_data[i]['id']||'';
	    					var _index=_data[i]['index']||0;
	    					var _t = _data[i]['t']||'';
	    					
	    					if(detail_def_obj.is_first_list==false && _id==detail_def_obj.id && _index==detail_def_obj.index && is_init==2){		
	    						if(detail_def_obj.fast_view==1 && isBook && _index>=3){
	    							_html+='<li class="list-sec" data-ver="'+ver+'" data-index="'+_index+'" data-id="'+_id+'" data-t="'+_t+'"><div class="fast-v"></div><img src="'+smallPicPath+'"/></li>'
	    						}else{
	    							_html+='<li class="list-sec" data-ver="'+ver+'" data-index="'+_index+'" data-id="'+_id+'" data-t="'+_t+'"><img src="'+smallPicPath+'"/></li>'
	    						}
							}else{
								if(detail_def_obj.fast_view==1 && isBook && _index>=3){
									_html+='<li data-ver="'+ver+'" data-index="'+_index+'" data-id="'+_id+'" data-t="'+_t+'"><div class="fast-v"></div><img src="'+smallPicPath+'"/></li>'
								}else{
									_html+='<li data-ver="'+ver+'" data-index="'+_index+'" data-id="'+_id+'" data-t="'+_t+'"><img src="'+smallPicPath+'"/></li>'
								}
							}
	    				}
	    				
	    				if(_type==2 && is_init==2){
		    				$('.js-detail-list').prepend(_html);
		    			}else{
		    				$('.js-detail-list').append(_html);
		    				if(_size>_len || _len>=_total){
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
	    				var contain_w=$('.footer-cont').width()-108;
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
				        $('.pics-item-control').addClass('item-control-set');
				        
				        lunFirstL();		//轮播初始位置
						detail_def_obj.is_first_list=true;
					}
	    			
	    		},
	    		errorFunc:function(){
	    			detail_def_obj.detail_lun.is_lun=false;	//跳出操作
	    		}
	    	});
        }
    	
    }
    
    
    //轮播位置初始化
    function lunFirstL(){
    	var _i=$('.js-detail-list .list-sec').index();		//当前选中的index
    	var lun_n=detail_def_obj.detail_lun.lun_n||20;
    	var _l=Math.abs( detail_def_obj.detail_lun.hist_n - lun_n);
    	var _s=Math.abs( detail_def_obj.detail_lun.hist_n );
    	
    	if(_i+1 >= _s && _i+1 <= _l-1){		//当前视线之内
			detail_def_obj.detail_lun.is_lun=false;		//跳出 轮播状态
		}else {			//视线外
	        getPatterList(0,-1,1);
    	}
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
	
	//搜索列表---细节图切换 
    pop_layer.on('click','.js-detail-search',function(){
    	if(detail_def_obj.is_first_list==true){
	    	if($(this).hasClass('detail-search2')){
	    		$(this).removeClass('detail-search2').attr('data-down', 0);
				$('.js-patter-contain').slideDown(200);
				$('.pics-item-control').addClass('item-control-set');
	    	}else{
	    		$('.js-detail-search').addClass('detail-search2').attr('data-down', 1);
				$('.js-patter-contain').slideUp(200);
				$('.pics-item-control').removeClass('item-control-set');
	    	}
    	}
    });
    
    
    //文案提示框
    pop_layer.on('click','.js-nav-control-disc,.prompt-disc',function(ev){
        pop_fashion_global.fn.stopBubble(ev);		//阻止事件冒泡
    });

    //点击显示底部说明
    pop_layer.on('click','.js-prompt',function(){
        $(this).find('.prompt-disc').toggle();
    });
	
    pop_layer.on('click','.js-prompt .dic-close',function(ev){
        pop_fashion_global.fn.stopBubble(ev);		//阻止事件冒泡
        $.cookie('tbook_prompt_disc',1,{ path:'/', expires: 365, domain:'.pop-fashion.com'});
        $(this).parents('.prompt-disc').hide();
    });
	
	//导航按键收缩
    $(document).on('keydown',function(ev){		//按键
    	//图案列表初始化后
    	if(detail_def_obj.detail_lun.is_lun!=true && detail_def_obj.is_first_list!=false){
			var e=ev||event;
	        var _v=e.keyCode;
	        if(_v==37){//左
	        	stopDefault(ev);
	        	if($('.js-pic-prev').length>0){
	        		$(".js-downimg-layer,.js-join-workbench-layer,.bd_weixin_popup,.bd_weixin_popup_bg").hide();
	        		picPrev();
	        	}
	        }else if(_v==39){//右
	        	stopDefault(ev);
	            if($('.js-pic-next').length>0){
	            	$(".js-downimg-layer,.js-join-workbench-layer,.bd_weixin_popup,.bd_weixin_popup_bg").hide();
	        		pixNext();
	        	}
	        }else if(_v==27){		//esc
	        	closeLayer();
	        }
		}
    });
    
    //放大缩小滚动条
    pop_layer.on('mousedown', '.js-control-l i',function(e){
    	if($("#draggable img").length<=0 || $("#draggable img").is(":hidden")){
    		return;
    	}
    	var ev=e||event;
    	pop_fashion_global.fn.stopBubble(ev);
    	detail_def_obj.move_y=ev.clientY;
    	detail_def_obj.is_move_pic=true;
    	detail_def_obj.control_top=parseFloat($('.js-control-l i').css('top'));//初始top
    });
    
    //放大镜控制
    pop_layer.on('mousemove',function(e){
    	if(detail_def_obj.is_move_pic==true){
    		var ev=e||event;
    		var _y=ev.clientY-detail_def_obj.move_y;
    		_y=detail_def_obj.control_top+_y;
    		if(_y>=0 && _y<=74){
    			$('.js-control-l i').css({'top':_y+'px'});
    			ySet(_y);		//同步放大倍数
    		}
    	}
    });
    
    //放大镜控制
    pop_layer.on('mouseup','.js-detail-contant',function(e){
    	detail_def_obj.is_move_pic=false;
    });
    
    //款式上下显示
    pop_layer.on('mouseover','.pics-item-control>div',function(){
    	if(detail_def_obj.is_first_list==true){
    		$(this).parents('.pics-item-control').addClass('item-this-set');
    	}
    });
    
    pop_layer.on('mouseleave','.pics-item-control',function(){
    	$(this).removeClass('item-this-set');
    	$('.js-pic-control,.js-pop-close').show();
    });
    
    
    //副标题显示详情
    pop_layer.on('mouseover','.report-msg',function(){
    	var _t=$(this).find('span');
    	if(_t.length>0){
    		$(this).find('.report-messages').stop().show();
    	}
    });
    
    pop_layer.on('mouseleave','.report-msg',function(){
    	$(this).find('.report-messages').stop().hide();
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
			$('.js-btn-load-left').fadeIn(100);
			getPatterList(2);
			return;
		}
		
		if(_len>=2000 && detail_def_obj.detail_lun.hist_n>=0){	//单张页最左边
			//超出长度提示
			layer_bg.show();
		    collectBox.find( 'p' ).html( '还有更多内容继续浏览搜索列表~' );
		    collectBox.show();
		    collectSureBoxHide();
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
				$('.js-btn-load-right').fadeIn(100);
				$('.js-list-prev').addClass('list-hv-on');
				getPatterList(1);
				return;
			}
		}
		
		if(Math.abs(detail_def_obj.detail_lun.hist_n-detail_def_obj.detail_lun.lun_n)>=_len){		//轮播到最右端
			if(_len>=2000){			//超出长度提示
				layer_bg.show();
			    collectBox.find( 'p' ).html( '还有更多内容继续浏览搜索列表~' );
			    collectBox.show();
			    collectSureBoxHide();
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
				layer_bg.show();
			    collectBox.find( 'p' ).html( '还有更多内容继续浏览搜索列表~' );
			    collectBox.show();
			    collectSureBoxHide();
			    detail_def_obj.is_cat_set=false;
				detail_def_obj.detail_lun.is_lun=false;	//跳出操作
			    return;
			}
			$('.js-btn-load-left').fadeIn(100);
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
			if(_i>=_len-20 && _len<2000 && detail_def_obj.detail_lun.has_list==true){		//当前列表之外加载数据
				$('.js-btn-load-right').fadeIn(100);
				getPatterList(1,1);		//后面添加数据并移动
	    	}else{
	    		
	    		if(_i>=_len-1){	
	    			if(_len>=2000 ){	//最右边
						//超出长度提示
						layer_bg.show();
					    collectBox.find( 'p' ).html( '还有更多内容继续浏览搜索列表~' );
					    collectBox.show();
					    collectSureBoxHide();
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
    
    //上一款
    pop_layer.on('click','.js-pic-prev',function(ev){
        ev.stopPropagation();
    	picPrev();
    });
    
    //下一款
    pop_layer.on('click','.js-pic-next',function(ev){
        ev.stopPropagation();
    	pixNext();
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
        
    // 获取图片原始大小
    function natural(img){
    	var dImg =$("#draggable img");
    	var nImg =new Image();
    	nImg.src=dImg[0].src;
    	detail_def_obj.imgW = nImg.width;
        detail_def_obj.imgH = nImg.height;
		dImg[0].width=detail_def_obj.imgW;
		dImg[0].height=detail_def_obj.imgH;
    }
    
    // 图片初始大小--原图
    pop_layer.on('click', '.js-original', function() {  
    	if($("#draggable img").length<=0 || $("#draggable img").is(":hidden")){
    		return;
    	}
    	$('.js-reset').removeClass('on-reset');
        natural($("#J_Visit img.js-bigbox"));   
        intBigImg();
    });
    
    // 重置图片--复位
    pop_layer.on('click', '.js-reset', function() {
    	if($("#draggable img").length<=0 || $("#draggable img").is(":hidden")){
    		return;
    	}
    	if(!$(this).hasClass('on-reset')){
    		$(this).addClass('on-reset');
    		imgSize($("#draggable img"),detail_def_obj.imgW,detail_def_obj.imgH);    
        	intBigImg();
    	}
    });
    
    pop_layer.on('click', '.js-bp-set .dic-close', function(ev) {
    	pop_fashion_global.fn.stopBubble(ev);		//阻止事件冒泡
        $.cookie('tbook_bp_set',1,{ path:'/', expires: 365, domain:'.pop-fashion.com'});
        $(this).parents('.js-bp-set').hide();
    });
    
    //重新计算图片位置 
    function intBigImg() {
        var bigImgBox = $("#draggable");
        bigImgBox.css({"left": 0,"top": 0,"width":'100%'});
        bigImgBox.find("img").css({'-moz-transform': 'scale(1)', "zoom": "100%","display":"inline"});
        radioSet(1);
    }

    //收藏
    var collectSureTime, is_collect=false;
    pop_layer.on("click", "#J_Collect>a", function () {
    	if(is_collect==true){
    		return;
    	}
    	is_collect=true;
    	
    	var self = $(this);
        var coll_ele = $('#collect');
        var iscollected = self.data('iscollected');
        if (iscollected) {
            var status = 0;
        }else {
            var status = 1;
        }
        if (iscollected == -1) {
        	is_collect=false;
            // oCommon.noPower(-2);
            // return;
        }
        var col = self.data('col');
        var tab = self.data('tab');
        var id = self.data('id');
        var type = self.data('type');
        var para = self.data('para');
        var callback = function () {
            if(status == 0) {
            	if(coll_ele.length>0){
            		coll_ele.addClass('nzt').find('span').eq(0).html('收藏');
                	coll_ele.data('iscollected', 0);
            	}
                layer_bg.show();
            	collectBox.find( 'p' ).html( '已取消收藏' );
            	collectBox.show();
            	self.data('iscollected', 0).removeClass('is-collected').find('span').eq(0).html('收藏全册');
            }else {
            	if(coll_ele.length>0){
            		coll_ele.removeClass('nzt').find('span').eq(0).html('已收藏');
                	coll_ele.data('iscollected', 1);
            	}
                layer_bg.show();
            	collectBox.find( 'p' ).html( '收藏成功' );
            	collectBox.show();
            	self.data('iscollected', 1).addClass('is-collected').find('span').eq(0).html('已收藏');
            }
            collectSureBoxHide();
            is_collect=false;		//跳出收藏
        };
        oCommon.collect(this, col, tab, id, type, callback, status, para);
    });
    
    
    function collectSureBoxHide(){
        collectSureTime=setTimeout(function(){
            clearInterval(collectSureTime);
            layer_bg.fadeOut(500);
            collectBox.fadeOut(500);
        },2000);
    }

    pop_layer.on('click', '.js-down-close', function () {
        layer_bg.hide();
        $('.js-downimg-layer').hide();
        $('.js-downimg-right').hide();
    });
      
    var smallImage, bigImage, tablename, tableid, column, $detail;  
    //下载
    pop_layer.on('click', '#J_Download', function () {
        /*if (!oCommon.downloadPrivilege()) {
            return;
        }*/
        // 简单的权限控制
        if($(this).hasClass('pow')){
            return;
        }
        // 权限控制
        $detail = $('.js-bigbox');
        var column = parseInt($detail.attr('data-col'));
        var id = $detail.attr('data-id');
        var t = $detail.attr('data-t');
        if(column==null || id==null || t==null){
           return false;
        }
        
        // 80=>款式模板（矢量图） 82=>图案素材
        if ($.inArray(column, [73,80,82,120]) != -1) {
        	//矢量书稿(有多种下载格式)
	        var smallImagePath = $detail.attr('data-sp');
	        var _smallName = smallImagePath.split('/');
	        var i = _smallName.length-1;
	        var smallName = _smallName[i];//当前小图名称
	        var pop_download_table = $(".js-downimg-layer").find("table tbody");
	        var sDownHtml='';
	        var downloadType = pop_download_table.data('downtype');//下载类型详细信息
          var eps = '';
	        if( downloadType ){
	            for( var k in downloadType ){
	                if( smallName == downloadType[k].sSmallName ){
	                    var downInfo = downloadType[k].aDownInfo;
	                    if(downInfo){
	                        for( var suffix in downInfo ){
                              if (suffix == 'eps') {
                                eps = '<span class="eps-txt"><span style="position: relative;top: 2px;">*</span> 支持ai、cdr软件工具</span>';
                              } else {
                                eps = '';
                              }
	                            sDownHtml += "<tr><td>."+ suffix + eps +"</td><td>"+ downInfo[suffix].type +"</td> <td>"+ downInfo[suffix].size +"</td><td><a class='js-download-btn' href='javascript:void(0);' title='下载' data-bp='"+ downInfo[suffix].bp +"'>下载</a></td></tr>";
	                        }
	                    }
	                }
	            }
	        }
	        pop_download_table.html(sDownHtml);
	        if(pop_download_table.children("tr").length<2){
                pop_download_table.find(".js-download-btn").attr("data-type",1);
            }
            $('.js-downimg-layer').show();
        } else {
			if (!oCommon.downloadPrivilege()) {
				return;
			}
            var _rename = $detail.attr('data-rename')==undefined||$detail.attr('data-rename')==""?"":"?rename="+$detail.attr('data-rename');
            bigImage = $detail.attr('data-bp')+_rename;
            down(bigImage,t,id,column);
        }
        
    });
    // 弹框下载
    pop_layer.on('click', '.js-download-btn', function () {
		if (!oCommon.downloadPrivilege()) {
			return false;
		}
        // 简单的权限控制
        if($(this).hasClass('pow')){
            return;
        }
        $detail = $('.js-bigbox');
        bigImage = $(this).attr('data-bp');
        bigImage_suffix =  bigImage.split('.');
        if( bigImage_suffix.pop() == 'jpg'){
            var _rename = $detail.attr('data-rename')==undefined||$detail.attr('data-rename')==""?"":"?rename="+$detail.attr('data-rename');
            bigImage = $detail.attr('data-bp')+_rename;
        }
        column = $detail.attr('data-col');
        id = $detail.attr('data-id');
        t = $detail.attr('data-t');
        down(bigImage,t,id,column);

        var type=$(this).attr("data-type");
        if(type==1){
        	layer_bg.hide();
	        $('.js-downimg-layer').hide();
	        $('.js-downimg-right').hide();
        }
    }); 

    // 全屏缩放
    var is_max = true;
    pop_layer.on('click','.js-max-box',function(){
        if( (detail_def_obj.is_first_list==true || !$('.js-detail-search').length) && is_max){
            is_max = false;
            if($(this).hasClass('max-box2')){   //收起
                $('.js-page-control').animate({
                    'right': '300px'
                }, 200, function(){
                    $('.js-max-box').removeClass('max-box2');
                    lunInit();
                    is_max = true;
                });

                $('.js-detail-content, .js-detail-footer>div').animate({
                    'padding-right': '300px'
                }, 200);

                $('.js-nav-right').animate({
                    'width': '299px'
                }, 200);

                if($('.js-detail-search').length){  // 底部展示
                    var _search = $('.js-detail-search').attr('data-down')||0;
                    if(_search != 1){
                        $('.js-detail-search').removeClass('detail-search2');
                        $('.js-patter-contain').slideDown(200);
                        $('.pics-item-control').addClass('item-control-set');
                    }
                }

            }else{                                  // 展开
                $('.js-page-control').animate({
                    'right': 0
                }, 200, function(){
                    $('.js-max-box').addClass('max-box2');
                    lunInit();
                    is_max = true;
                });

                $('.js-detail-content, .js-detail-footer>div').animate({
                    'padding-right': 0
                }, 200);

                $('.js-nav-right').animate({
                    'width': 0
                }, 200);

                if($('.js-detail-search').length){
                    $('.js-detail-search').addClass('detail-search2');
                    $('.js-patter-contain').slideUp(200);
                    $('.pics-item-control').removeClass('item-control-set');
                }

            }
            
        }
    });
    
    // 导航收缩
    // pop_layer.on('click', '.js-nav-control', function () {
    // 	if(detail_def_obj.is_first_list==false || detail_def_obj.detail_lun.is_lun==true){	
    // 		if($('.js-detail-search').length>0){		//弹框页面
    // 			return;
    // 		}
    // 	}
    // 	detail_def_obj.detail_lun.is_lun = true;	//禁止轮播
    //     if($(this).hasClass('nav-control2')){
    //     	$(this).removeClass('nav-control2');
    //     	$('.js-nav-right').animate({'right':'0'},200);
    //     	$('.js-pop-close').animate({'right':'300px'},200);
    //     	$('.js-detail-content,.js-detail-footer>div').animate({'padding-right':'300px'},200,function(){
    //     		lunInit();
    //     		detail_def_obj.detail_lun.is_lun = false;
    //     	});
    //     }else{
    //     	$(this).addClass('nav-control2');
    //     	$('.js-nav-right').animate({'right':'-300px'},200);
    //     	$('.js-pop-close').animate({'right':'0'},200);
    //     	$('.js-detail-content,.js-detail-footer>div').animate({'padding-right':'0'},200,function(){
    //     		lunInit();
    //     		detail_def_obj.detail_lun.is_lun = false;
    //     	});
    //     }
    // }); 
    
    function stopPropagation(e) {
	    var e = e || window.event;
	    if (e.stopPropagation) { //W3C阻止冒泡方法
	        e.stopPropagation();
	    } else {
	        e.cancelBubble = true; //IE阻止冒泡方法
	    }
    }
    

    window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"1","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"24"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];

    // 进入弹层重新计算图片大小
    function imgSize(img,_w,_h){
    	if(img.length>0){
    		if($('.js-detail-search').length<=0){
	    		var bott_h=120;
	    	}else if(detail_def_obj.is_first_list==false || !$('.js-detail-search').hasClass('detail-search2')){
				var bott_h=189;
			}else{
				var bott_h=79;
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
	        $("#draggable img").show();
    	}
    }
})(jQuery);

// 下载
function down(path,t,id,col) {
    if (typeof path == 'undefined') {
        return;
    }
    var fileType = path.split('.');
    fileType = fileType.pop();  //下载文件格式
    var params={ table:t , id:id , iColumnId:col ,action:'DownCount',fileType:fileType};
    // $.ajax({
    //     type: "get",
    //     url: "/ajax/setCount/" + Math.random(),
    //     data: params,
    //     async: false
    // });
    actionDown();  // 下载统计
    oCommon.download(path);
//	location.href = '/download/dlsingle/?dl_link=' + encodeURIComponent(path) + '&' + Math.random();
}

// 右键点击
function rightClick(position) {
	/*if (!oCommon.downloadPrivilege()) {
        return false;
    }*/
    var imgShape = $('#imgShape');
    var imgSize = $('#imgSize');
    var isDetailImg = imgShape.length > 0 ? true : false;
    var curImg = $('#draggable').find('img');
    if (isDetailImg) {
        imgShape.html(curImg.data('shape'));
        imgSize.html(curImg.data('size'));
        $('.downimg-layer-data').find('.js-download-btn').attr({'data-bp':curImg.attr('data-bp'),"data-type":1});
    }else{
        //矢量书稿(有多种下载格式)
        var smallImagePath = curImg.attr('data-sp');
        var _smallName = smallImagePath.split('/');
        var i = _smallName.length-1;
        var smallName = _smallName[i];//当前小图名称
        var pop_download_table = $(".downimg-layer-data").find("table tbody");
        var sDownHtml='';
        var downloadType = pop_download_table.data('downtype');//下载类型详细信息
        var eps = '';
        if( downloadType ){
            for( var k in downloadType ){
                if( smallName == downloadType[k].sSmallName ){
                    var downInfo = downloadType[k].aDownInfo;
                    if(downInfo){
                        for( var suffix in downInfo ){
                            if (suffix == 'eps') {
                              eps = '<span class="eps-txt"><span style="position: relative;top: 2px;">*</span> 支持ai、cdr软件工具</span>';
                            } else {
                              eps = '';
                            }
                            sDownHtml += "<tr><td>."+ suffix + eps +"</td><td>"+ downInfo[suffix].type +"</td> <td>"+ downInfo[suffix].size +"</td><td><a class='js-download-btn' href='javascript:void(0);' title='下载' data-bp='"+ downInfo[suffix].bp +"'>下载</a></td></tr>";
                        }
                    }
                }
            }
        }
        pop_download_table.html(sDownHtml);
        if(pop_download_table.children("tr").length<2){
            pop_download_table.find(".js-download-btn").attr("data-type",1);
        }
    }    
    if(position == 1){
        $('.js-downimg-layer').show();   
    }else{
        $('.js-bg-layer , .js-downimg-right').show();
    }
}

//鼠标滚动图片放大缩小     
function imgScroll(){
	var oBigImg = $("#J_Visit img.js-bigbox");
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
        }
        else {
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
        }
        else if (oBigImg.hasClass("szoom")) {
            oBigImg.addClass("dragIcon").removeClass('szoom');
        }
        oImg.onmouseup = function () {
            oBigImg.addClass("dragIcon");
        }
    };
    $("#draggable").draggable();
}   