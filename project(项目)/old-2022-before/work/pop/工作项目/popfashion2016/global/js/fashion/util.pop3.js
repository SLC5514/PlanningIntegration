/**
 * 弹出层js效果
 */
var POPTOOL = POPTOOL || {};
POPTOOL.pop = {};
POPTOOL.pop.replaceUrl = '';
var num = 0;

POPTOOL.pop.popLayer = function (popLayerId, url, uc) {
    if (typeof uc == 'undefined' || uc == 0) {
        if ('pushState' in history) {
            history.pushState(null, null, url);
        } 
        POPTOOL.pop.replaceUrl = url;
    }
    $('html,body').css("overflowY", "hidden");
    $('html,body').css("overflowX", "hidden");
    popLayerId.show();
};
POPTOOL.pop.render = function (container, templateid, data) {
    var html = template(templateid, data);
	if (templateid == 'T_Title') {
		document.title = data.stitle;
	}
    container.html(html);
};

(function ($) {
	var timer;
    var nowUrl = window.location.href;
    var index = 0;
    var total = 0;
    var pop_layer = $('#pop_layer');
    var allPic = $('.rightc_main');
    var p = $('#link').data('param');

    var J_Title = $('#J_Title');                // 标题
    var J_Tab = $('#J_Tab');                    // 细节图数字
    var J_DetailList = $('#J_DetailList');      // 细节大图
    var J_LeftRightBtn = $('#J_LeftRightBtn');  // 左右键
    var J_StyleInfo = $('#J_StyleInfo');        // 款式信息
    var J_SupllierInfo = $('#J_SupllierInfo');  // 供应商信息
    var J_Label = $('#J_Label');                // 关联标签
    var J_Fitting = $('#J_Fitting');            // 在线3D试衣
    var J_MoreSea = $('#J_MoreSea');            // 更多搜索
    var J_MoreSeaNum = $('#J_MoreSeaNum');      // 搜索结果数量
    var J_RecFabric = $('#J_RecFabric');        // 推荐面料
    var J_StyleAd = $('#J_StyleAd');            // 广告
    var J_DownloadType = $('#J_DownloadType');  // 图片下载类型
    var J_DownloadType2= $('#J_DownloadType2'); // 图片下载类型2
    var J_MoreRec = $('#J_MoreRec');            // 更多推荐
    var J_Collect = $('#J_Collect');            // 更多推荐 T_Collect   T_JoinW T_Download  T_Share
    var J_JoinW = $('#J_JoinW');
    var J_Download = $('#J_Download');
    var J_MoreRecDis = $('#J_MoreRecDis');		//更多推荐是否显示
    var J_Fabric = $('#J_Fabric');		//更多推荐是否显示
    var J_VendorAdLists = $("#J_VendorAdLists");
    var J_Spatter = $("#J_Spatter");             //相关图案
    var J_Rfabric = $("#J_Rfabric");             //相关面料
    var J_Vclose  =$("#J_Vclose");               //虚拟样衣

    var curUrl, resultHei = 0, ruHei = 0, totalNum = 0, pageNum = 1;
    var waterTime = null;
    var scrollFlage = null;
    var selLiPos = 0;
    var body = $('body');
    var isF5 = pop_layer.data("f5") ? true : false;

    var t, id, col;

    function replaceChangeContent(data) {
        POPTOOL.pop.render(J_Title, 'T_Title', data);
        POPTOOL.pop.render(J_Tab, 'T_Tab', data);
        POPTOOL.pop.render(J_DetailList, 'T_DetailList', data);
        POPTOOL.pop.render(J_LeftRightBtn, 'T_LeftRightBtn', data);
        POPTOOL.pop.render(J_StyleInfo, 'T_StyleInfo', data);
        POPTOOL.pop.render(J_SupllierInfo, 'T_SupplierInfo', data);
        POPTOOL.pop.render(J_Label, 'T_Label', data);
        POPTOOL.pop.render(J_Fitting, 'T_Fitting', data);
        POPTOOL.pop.render(J_RecFabric, 'T_RecFabric', data);
        POPTOOL.pop.render(J_StyleAd, 'T_StyleAd', data);
        POPTOOL.pop.render(J_DownloadType, 'T_DownloadType', data);
        POPTOOL.pop.render(J_DownloadType2, 'T_DownloadType2', data);
        //POPTOOL.pop.render(J_MoreRec, 'T_MoreRec', data);
        POPTOOL.pop.render(J_Collect, 'T_Collect', data);
        POPTOOL.pop.render(J_JoinW, 'T_JoinW', data);
        POPTOOL.pop.render(J_Download, 'T_Download', data);
		POPTOOL.pop.render(J_MoreRecDis, 'T_MoreRecDis', data);
		POPTOOL.pop.render(J_Fabric, 'T_Fabric', data);
		POPTOOL.pop.render(J_VendorAdLists, 'T_VendorAdLists', data);
        POPTOOL.pop.render(J_Rfabric, 'T_Rfabric', data);
        POPTOOL.pop.render(J_Spatter, 'T_Spatter', data);
        POPTOOL.pop.render(J_Spatter, 'T_Vclose', data);
        // TDK(data.searchKeys);
        // review(data.tablename, data.id);
    }

    
    // 点击图片触发弹层
    allPic.on('click', 'a.img_click', function () {
		if(timer!=undefined){
			clearInterval(timer);
		}
        qqflag = false;  
        var self = $(this);
        var uc = self.data('isuc');
        var total = self.data('total');
        var index = self.data('index');
        var type = self.data('type');
        var table = self.data('t');
        t = table;
        id = self.data('id');
        col = self.data('col');
        var wbid = self.data('wbid');
        var link = $('#link');
        var colPid = link.data('col');
        var sort = link.data('sor');
        var search = link.data('search');
        var url = '/details/style/';
        var curUrl = url+'t_'+table+'-id_'+id+'-col_'+col+'/';
		$("#otherPageTurnList").val($("#thisPageTurnList").val());
		var pageTurnList = $("#otherPageTurnList").val();
		if(pageTurnList=='' || pageTurnList==undefined){
			var pageTurn=0;
		}else{
			var pageTurn=dealPageTurn(pageTurnList,table,id);
		}		
        var params = {p: p, key:search, total: total, index: index, colPid:colPid, type:type, isuc:uc, wbid:wbid, sort:sort,'pageTurn':pageTurn};
        /* var POP_USER = $.cookie('POP_USER'); */
        //是手机端时,不走F5页面
        if(IsPC()==false){
            window.location.href = curUrl;
            return ;
        }
        // POP_USER
        //先判断权限，cookie中没有值未登录，跳到/details/style/(display中间页)
        if( ((P_UserId == null || P_UserId == '') || $("#link").data("pow") == 2) && col != 121 ) {
            window.open(curUrl);
        }else {
            $.ajax({
                type: "post",
                data: params,
                dataType: "json",
                url: curUrl,
                beforeSend: function () {
                    $("#loadingBg").show();
                },
                success: function (data) {
                    //登陆状态失效
                    if (data.code=="5001") {
                        window.location.href = data.url;
                        return false;
                    }
                    // 如果是聚合页则请求加载搜索结果
                    var cat_s = $('.shaixuan_wid');
                    var catshow = cat_s.find('.category_num_cur');
                    var cat_ele = catshow.find('em');
                    var txt=cat_ele.text();
                    var type_list=catshow.children(".cateNum_list");
                    var catshowt=1;
                    type_list.children("a").each(function(){
                        var ntxt=$(this).text() || "";
                        var val=$(this).attr("data-val") || "";
                        ntxt='按'+ntxt+'浏览';
                        if(ntxt==txt && ntxt!=""){
                            catshowt=val;
                            return false;
                        }
                    });
                    if (catshowt != 2 && catshowt != 3) {
                        if (data.success==0) {
                            window.location.href="/error/";
                            return false;
                        }
                    }
                    replaceChangeContent(data);
                    // 面料列表的异步请求---因为加载太慢
                    if (col==117) {//col == 82 || col == 7 || 
                        var fabricUrl = '/ajax/getfabriclist/';
                        var fabircParams = {path: data['detailList'][0]['smallPath']};
                        $.post(fabricUrl, fabircParams, function(data){
                            POPTOOL.pop.render(J_RecFabric, 'T_RecFabric', data);
                        },'json');
                    }
					// 非工作台
					if (typeof uc == 'undefined' || uc == 0){
						if (catshowt == 2 || catshowt == 3) {
							pageNum = 1;
							var getMoreParams = {p:p, key:search, page:pageNum++, cat:catshowt, type:type, col:col, colPid:colPid, limit:12};
							var aUrl = '/ajax/getmoresealist/';
							$.post(aUrl, getMoreParams, function (data) {
								POPTOOL.pop.render(J_MoreSea, 'T_MoreSea', data);
								POPTOOL.pop.popLayer(pop_layer, curUrl);
                                hideToggleBtn();
								if (data.hasOwnProperty('result')) {
									totalNum = data.total;
									showResultImg(data.result.length);
									$(".layerRight .resultBox li").eq(0).addClass('cur').siblings('li').removeClass("cur");
								}
							}, "json");
						} else {
							POPTOOL.pop.popLayer(pop_layer, curUrl);
                            hideToggleBtn();
						}
					} else {
						POPTOOL.pop.popLayer(pop_layer, curUrl, uc);
                        hideToggleBtn();
					}
					//统计浏览量
					viewCount(table,id,col);
					timer = setInterval(lunbo, 5000);
                    // imgSize($("#draggable img"));
                },
                complete: function () {
                    // 引导页调用
                    $("#loadingBg").hide();
                }
            });
        }
        return false;
    });
    if(!isF5){
        function closeLayer(){
            oCommon.hiddenWXshare();
            $('.load_close_btn,.close').trigger('click');
            closeMoreRec();
            pop_layer.hide();
            $('html,body').css({overflowY:"auto",overflowX: "auto"});
             if ('pushState' in history) {
                history.replaceState(null, null, nowUrl);
             }
            selLiPos = 0;
        }
        $('body').on("click", ".pop_close , .poplayer_close", function () {              
            closeLayer()
        });
        $('body').on('click','.pop_alert',function(){
            var layerblack = pop_layer.find('.layerblack')
            var layerblackH = layerblack.height();
            if(layerblackH > 350){
                layerblack.animate({'height':'0','width':'0'}, 300);
            }else{
                closeLayer()
            }
        });
        $('body').on('click','.pop_alert div' ,function(e){
            e.stopPropagation();
        });
   }

    if (history.pushState) {
        // 浏览器后退按钮 触发关闭按钮点击事件
        window.addEventListener("popstate", function () {
            pop_layer.find('.pop_close').trigger('click');
        });
    }

    //弹出层左右切换
    var keyDrection;
    pop_layer.on("click", ".rightBtn,.leftBtn", function () {
        oCommon.hiddenWXshare();
        closeMoreRec();
        var self = $(this);
        var uc = self.data('isuc');
        var link = $('#link');
        var colPid = link.data('col');
        var sort = link.data('sor');
        col = self.data('col');
        var wbid = self.data('wbid');
        var type = self.data('type');
		var table = self.data('t');
        t = table;
		id = self.data('id');
        var url = '/details/style/';//link.data('urlprefix');
        var curUrl = url+'t_'+table+'-id_'+id+'-col_'+col+'/';
        var total = self.data('total');
        var index = self.data('index');
        var search = link.data('search');
		var pageTurnList = $("#otherPageTurnList").val();
		if(pageTurnList=='' || pageTurnList==undefined){
			var pageTurn=0;
		}else{
			var pageTurn=dealPageTurn(pageTurnList,table,id);
		}
        var params = {p: p,key:search,total: total, index: index, colPid:colPid, isuc:uc, wbid:wbid, type:type, sort:sort,pageTurn:pageTurn};
        $.ajax({
            type: "post",
            data: params,
            dataType: "json",
            url: curUrl,
            beforeSend: function () {
                // $("#loadingBg").show();
            },
            success: function(data){
				if(data.pageTurnList!=undefined){
					var oldpageTurnList = JSON.parse($("#otherPageTurnList").val());
					var appendTurnList = JSON.parse(data.pageTurnList);
					var newpageTurnList = {};
					if(appendTurnList['direction']=='pre'){
						oldpageTurnList.splice(96,48);
						newpageTurnList = appendTurnList['list'].concat(oldpageTurnList);
					}else{
						oldpageTurnList.splice(0,48);
						newpageTurnList =  oldpageTurnList.concat(appendTurnList['list']);
					}
					$("#otherPageTurnList").val(JSON.stringify(newpageTurnList));
				}
                $("#loadingBg").hide();
				replaceChangeContent(data);
				//统计浏览量
				viewCount(table,id,col);
				// 面料列表的异步请求---因为加载太慢
				if (col == 117) {//col == 82 || col == 7
					var fabricUrl = '/ajax/getfabriclist/';
					var fabircParams = {path: data['detailList'][0]['smallPath']};//data.path
					$.post(fabricUrl, fabircParams, function(data){
						POPTOOL.pop.render(J_RecFabric, 'T_RecFabric', data);
					},'json');
				}
				// 如果是聚合页则请求加载搜索结果
				if (typeof uc == 'undefined' || uc == 0) {
					POPTOOL.pop.popLayer(pop_layer, curUrl, uc);
					var selLi = $(".layerRight .resultBox li");
					var resultBoxHei = $(".layerRight .resultBox").height();
					selLi.eq(index).addClass('cur').siblings('li').removeClass("cur");
					if($(".layerRight .resultBox").length<=0) return;
					if(self.hasClass('rightBtn')){
						keyDrection = true;
					}
					else if(self.hasClass('leftBtn')){
						keyDrection = false;
					}
                    var liLen = selLi.length;
					if (index < liLen) {
						var selHei = selLi.eq(index).outerHeight(true);
						var selTop = selLi.eq(index).position().top + selHei;
						var selTopLeft = selLi.eq(index).position().top;
						var boxUlHei = selLi.parent().height();
						var nowScrollT = $(".layerRight .resultBox").scrollTop();
						var selPos =Math.abs(resultBoxHei - (selTop - nowScrollT));
						var scrollT = Math.round(nowScrollT+selPos);
						if(keyDrection){
						   if(boxUlHei >resultBoxHei && (nowScrollT+resultBoxHei) < selTop  ){
								$(".layerRight .resultBox").scrollTop(scrollT);
						   }
						}
						else{
							 if( selTopLeft < nowScrollT ){
								$(".layerRight .resultBox").scrollTop(selTopLeft);
						   }
						}
					}
				}	
			}
        });
    });
    //弹出层右侧图片搜索结果选中图片
    pop_layer.on("click", ".resultBox[data-hook=ajaxChange] img", function () {
        var self = $(this);
        var link = $('#link');
        var colPid = link.data('col');
        var sort = link.data('sor');
        var url = '/details/style/';//link.data('urlprefix');
        var curUrl = url+'t_'+self.data('t')+'-id_'+self.data('id')+'-col_'+self.data('col')+'/';
        var total = self.data('total');
        var index = self.data('index');
        var type = self.data('type');
        var keys = link.data('search');
        var params = {p: p, total: total, index: index, colPid:colPid, 'sort':sort, 'type':type, key:keys};
        $.ajax({
            type: "post",
            data: params,
            dataType: "json",
            url: curUrl,
            success: replaceChangeContent
        });
        POPTOOL.pop.popLayer(pop_layer, curUrl);
        $(".layerRight .resultBox li").eq(index).addClass('cur').siblings('li').removeClass("cur");
        if($("#pop_layer").length > 0){
           var resultBoxlayer = $(".layerRight .resultBox");
            var selLi = resultBoxlayer.find("li");
            var resultBoxHei = resultBoxlayer.height();
            if(index%11 == 0 && (index+1) == selLi.length){
                var selH = selLi.eq(index).outerHeight(true);
                var selT = selLi.eq(index).position().top + 2*selH - resultBoxHei;
                $(".layerRight .resultBox").scrollTop(selT);
            }
        }
    });


    // ========= 更多推荐的点击事件 ========
    var selcon = pop_layer.find(".layer_dwid");
    pop_layer.on("click", ".btnbox", function () {
        var self = $(this);
        var layerblack = pop_layer.find(".layerblack");
        if ($(window).width() >= 1500) {
                layerblack.animate({'height': '706px','width':'100%'}, 300);
                ajaxGetMoreRec();
        } else {
                layerblack.animate({'height': '352px','width':'100%'}, 1000);
                ajaxGetMoreRec();
            }
    });
    pop_layer.on("click" , ".layer_dwid_close" , function() {
        var layerblack = pop_layer.find(".layerblack");
        layerblack.animate({'height':'0','width':'0'}, 300);
    });
    // 右键点击弹出下载格式(款式库，灵感源图库)
    var _bigbox = pop_layer.find('.bigbox');
    if (_bigbox.length > 0) {
        _bigbox[0].oncontextmenu = function(){
            rightClick(2);
            return false;
        }
    }
    var _layerbigpic = pop_layer.find('.layerBigPic');
    if (_layerbigpic.length > 0) {
        _layerbigpic[0].oncontextmenu = function(){
            rightClick(1);
            return false;
        }
    }

    function rightClick(position) {
        var imgShape = $('#imgShape');
        var imgSize = $('#imgSize');
        var isDetailImg = imgShape.length > 0 ? true : false;
        var curImg = $('#J_DetailList').find('img').filter('.on');
        if (parseInt(curImg.data('col')) == 121) {
            return false;
        }
        if (!oCommon.downloadPrivilege()) {
            return false;
        }
        if (isDetailImg) {
            imgShape.html(curImg.data('shape'));
            imgSize.html(curImg.data('size'));
            $('.pop_download').find('.download_btn').data('bp', curImg.data('bp'));
        }else{
            //素材(有多种下载格式)
            var smallImagePath = curImg.data('sp');
            var _smallName = smallImagePath.split('/');
            var i = _smallName.length-1;
            var smallName = _smallName[i];//当前小图名称
            var pop_download_table = $(".pop_download").find("table tbody");
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
                                sDownHtml += "<tr><td>."+ suffix + eps +"</td><td>"+ downInfo[suffix].type +"</td> <td>"+ downInfo[suffix].size +"</td><td><a class='download_btn' href='javascript:void(0);' title='下载' data-bp='"+ downInfo[suffix].bp +"'>下载</a></td></tr>";
                            }
                        }
                    }
                }
            }
            pop_download_table.html(sDownHtml);
        }
        $('.shadow_black , .pop_download').show();
        if(position == 1){
            $('.shadow_black').hide();
        }

    }

    // 获取更多推荐
    function ajaxGetMoreRec() {
        var J_MoreRec = $('#J_MoreRec');
        t = t ? t: J_MoreRec.data('t');
        id = id ? id: J_MoreRec.data('id');
        col = col ? col: J_MoreRec.data('col');

        $.ajax({
            type: 'post',
            url: '/ajax/getmorerec/',
            data: {t:t, id:id, col:col, time: Math.random()},
            dataType: 'json',
            async: true,
            beforeSend: function() {
                J_MoreRec.html($("#J_MoreRecLodingTemplate").html());
            },
            success: function(data) {
                J_MoreRec.html(template('J_MoreRecTemplate', data));
            }
        });
    }
    //关闭详情时对更多推荐等做初始化（还原为最初的隐藏状态）
    function closeMoreRec() {
        num = 0;
        // clearInterval(timer);
        $('#J_MoreRec').html('');
        var layerblack = pop_layer.find(".layerblack");
        var moreRec = pop_layer.find(".moreRec");
        layerblack.animate({height:0}, 300);
        moreRec.removeClass("down");
    }

    function hideToggleBtn() {
        if ($('#J_JoinW').length || $('#J_Collect').length || $('#J_Download').length) {
            var w1 = pop_layer.find("#J_JoinW a").outerWidth();
            var w2 = pop_layer.find("#J_Collect a").outerWidth();
            var w3 = pop_layer.find("#J_Download a").outerWidth();
            //var w = w1 + w2 + 20;
            if ($('#J_JoinW').length == 0) {
                pop_layer.find(".share_btn").css("left", w1 + w2 + w3 + 20);
            } else {
                pop_layer.find(".share_btn").css("left", w1 + w2 + w3 + 30);
            }
        } else {
            var $abox = pop_layer.find("#J_DownNumContanier");
            var left = 0;
            if ($abox.length == 1) {
                left = $abox.eq(0).outerWidth();
            } else {
                left = $abox.eq(0).outerWidth() + $abox.eq(1).outerWidth();
            }
            pop_layer.find(".share_btn").css("left", left + 20);

            var adiv = pop_layer.find(".pop_report .indexdiv");
            for (var i = 0, len = adiv.length; i < len; i++) {
                layerTop[i] = adiv.eq(i).offset().top;
            }
        }
    }
    hideToggleBtn()
    // ========= 分享 ========
    pop_layer.on("mouseenter mouseleave", ".shareMouse", function (e) {
        // 简单的权限控制
        if($(this).hasClass('pow')){
            return;
        }
        if (e.type == "mouseenter") {
            $(this).stop().animate({width: "280px", backgroundColor: "#fff"}, 300);
        } else {
            $(this).stop().animate({width: "40px", backgroundColor: "#ccc"}, 300);
        }
    });
	// 百度分享 https://www.hrwhisper.me/baidu-share-not-support-https-solution/
	window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"1","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"24"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];
	
	if(isF5){  
        $(window).scroll(function () { 
            if($(window).scrollTop() >=$(document).height()-$(window).height()){
                    $(".layer_down").fadeOut(200);
                }else{
                     $(".layer_down").fadeIn(200);
                }
        });              
    } else {
        pop_layer.scroll(function () {
            var scrolltop =$(this).scrollTop();
            var poplayerup = pop_layer.find('.poplayer_up');
            var poplayerclose = pop_layer.find('.poplayer_close');
            if(scrolltop >40){
                poplayerup.fadeIn(200);
                poplayerclose.fadeIn(200);
            } else {
                poplayerup.fadeOut(200);
                poplayerclose.fadeOut(200);
            }
        });
        pop_layer.on('click', '.poplayer_up', function() {
            pop_layer.animate({scrollTop:0}, 500);
        });

    }
	if(typeof($('#J_DetailList img'))!='undefined'){
		num = 0;
        var $box = $('#J_DetailList img').eq(num);
        var bigbox=pop_layer.find('#J_DetailList');
        var img = $box.data('bp');
        $box.prop('src', img);
        $box.addClass('on');
        $box.css('display','inline');
		timer = setInterval(lunbo, 5000);
	}
    pop_layer.on('mouseover mouseout', '.numlistRight a , #J_DetailList img', function(e){        
        if (e.type == 'mouseover') {           
            clearInterval(timer);
        } else if(e.type == 'mouseout'){            
            timer = setInterval(lunbo, 5000);
        }
    });
    
    // 细节图手动切换
    var bigbox=pop_layer.find('#J_DetailList');
    var widX =parseInt(bigbox.width());
    pop_layer.on('mousemove','#J_DetailList' ,function(e){        
        var $img = $('#J_DetailList img');
        if ($img.length <= 1) {
            bigbox.removeClass('img_left');
            bigbox.removeClass('img_right');
            return false;
        }        
        e =e || window.event;
        var x=e.offsetX;        
        if(x>(widX/2) && x <widX){
            bigbox.removeClass('img_left');
            bigbox.addClass('img_right');
        } else if(x>0 && x<(widX /2)){
            bigbox.removeClass('img_right');
            bigbox.addClass('img_left');
        }
    });    
    pop_layer.on('click','#J_DetailList',function(e){
        clearInterval(timer);
        e =e || window.event;
        var x=e.offsetX;
        if(x>(widX/2) && x <widX){
            clearInterval(timer);
            lunbo();
        }else if(x>0 && x<(widX /2)){
            clearInterval(timer);
            lunboleft();
        }
    });
    // 在线3D试衣
    pop_layer.on('click', '#fitting_cat', function() {
        var self = $(this);
        var t = self.data('t');
        var id = self.data('id');
        var pic = self.attr('data-pic');
        var sign = self.attr('data-sign');
        var params={table:t, id:id};
        $.ajax({
            type: "get",
            url: "/ajax/recordfitting/" + Math.random(),
            data: params,
            async: true
        });
        codepic = encodeURIComponent(encodeURIComponent(oCommon.popReplace(pic)));
        url = '/details/virtualSpl/';
        window.open(url+'?id='+id+'&t='+t+'&sign='+sign+'&codepic='+codepic);
    });
	function lunbo() {
        var bigbox=pop_layer.find('#J_DetailList');
        var $img = $('#J_DetailList img');
        var isLoad = true;
        var tim_g;
        if ($img.length <= 1) {
            return false;
        }    
        num++;          
        if (num >= $img.length) {
            num = 0
        }
        $(".cur_num").text(num+1);
        var $box = $img.eq(num);
        var img = $box.data('bp');
        var small_img = $box.data('sp');
        var mbig_img = $box.data('mp');
        if (typeof $box.attr('footprint') == 'undefined') {
            $box.prop('src', img);
            $box.attr('footprint', 1);
        }
		$('#J_Fabric a').prop('href', '/picmatch/piccutlist/t_'+$box.data('t')+'-id_'+$box.data('id')+'/?path='+$box.data('up'));
        $box.addClass('on').siblings('').removeClass('on');
        $box.css('display','inline').siblings('img').css('display','none');        
        var t_img; // 定时器
        var isLoad = true; // 控制变量
         
        // 判断图片加载状况，加载完成后回调
        isImgLoad(function(){
          bigbox.css('height', 'auto');
        }); 
        // 判断图片加载的函数
        function isImgLoad(callback){
            // 查找所有封面图，迭代处理
            $img.each(function(){
                // 找到为0就将isLoad设为false，并退出each
                if(this.height === 0){
                    isLoad = false;
                    return false;
                }
            });
            // 为true，没有发现为0的。加载完毕
            if(isLoad){
                clearTimeout(t_img); // 清除定时器
                // 回调函数
                var imgheight = $('#J_DetailList img').eq(num).height();
                bigbox.height(imgheight);
                callback();
            // 为false，因为找到了没有加载完成的图，将调用定时器递归
            }else{
                isLoad = true;
                t_img = setTimeout(function(){
                    isImgLoad(callback); // 递归扫描
                },500);
            }
        } 
        
        // 右侧智能识别图片路径轮播
        var fabric_a = $('.online_tool #fabric_li a');
        var fabric_a_url = fabric_a.data('url');
        var fabric_a_path = $box.data('bpurlencode');
        fabric_a.attr('href', fabric_a_url+'?path='+fabric_a_path);
        // 右侧相似图案图片路径轮播
        var pattern_a = $('.online_tool .pattern_a');
        var pattern_a_url = pattern_a.data('url');
        var pattern_a_path = $box.data('bpurlencode');
        pattern_a.attr('href', pattern_a_url+'?path='+pattern_a_path);
        // 右侧相关面料图片路径轮播
        var fabric = $('.online_tool .fabric_a');
        var fabric_url = fabric.data('url');
        var fabric_path = $box.data('staticurl') + $box.data('bpurlencode');
        fabric.attr('href', fabric_url+'?imageUrl='+fabric_path);
        // 右侧虚拟样衣图片路径轮播
        var cloth_a = $('.online_tool .cloth_a');
        var cloth_a_url = cloth_a.data('url');
        var sign = $box.data('sign');
        cloth_a.attr('data-pic', mbig_img);
        cloth_a.attr('data-sign', sign);
    }

    function lunboleft() {
        var $img = $('#J_DetailList img');
        if ($img.length <= 1) {
            return false;
        }
        num--;
        if (num < 0) {
            num = $img.length -1;            
        }
        $(".cur_num").text(num+1);
        var $box = $img.eq(num);
        var img = $box.data('bp');
        var small_img = $box.data('sp');
        var mbig_img = $box.data('mp');
        if (typeof $box.attr('footprint') == 'undefined') {
            $box.prop('src', img);
			$box.attr('footprint', 1);
        }
		$('#J_Fabric a').prop('href', '/picmatch/piccutlist/t_'+$box.data('t')+'-id_'+$box.data('id')+'/?path='+$box.data('up'));
        $box.addClass('on').siblings('').removeClass('on');
        $box.css('display','inline').siblings('img').css('display','none');
        // 右侧智能识别图片路径轮播
        var fabric_a = $('.online_tool #fabric_li a');
        var fabric_a_url = fabric_a.data('url');
        var fabric_a_path = $box.data('bpurlencode');
        fabric_a.attr('href', fabric_a_url+'?path='+fabric_a_path);
        // 右侧相似图案图片路径轮播
        var pattern_a = $('.online_tool .pattern_a');
        var pattern_a_url = pattern_a.data('url');
        var pattern_a_path = $box.data('bpurlencode');
        pattern_a.attr('href', pattern_a_url+'?path='+pattern_a_path);
        // 右侧相关面料图片路径轮播
        var fabric = $('.online_tool .fabric_a');
        var fabric_url = fabric.data('url');
        var fabric_path = $box.data('staticurl') + $box.data('bpurlencode');
        fabric.attr('href', fabric_url+'?imageUrl='+fabric_path);
        // 右侧虚拟样衣图片路径轮播
        var cloth_a = $('.online_tool .cloth_a');
        var cloth_a_url = cloth_a.data('url');
        var sign = $box.data('sign');
        cloth_a.attr('data-pic', mbig_img);
        cloth_a.attr('data-sign', sign);
    }

    pop_layer.on('click', '.next_num', function(){
        lunbo();        
    });
    pop_layer.on('click', '.pre_num', function(){
        lunboleft();
    });
    function showResultImg(lilen) {    
        var imgUl = $('#J_MoreSeaUl');
        var ulBox = $('.pop_layer_page .resultBox');
        var J_GMResultHei = 0;
        imagesLoaded(imgUl, function (e) {
            waterfallThatAlert();
            J_GMResultHei = imgUl.height();
            if (lilen < totalNum && J_GMResultHei < 310) {
                ulBox.height(J_GMResultHei - 10);
            }
            ulBox.scrollTop(0.1);
            addMoreResult();
            $(".resultBox").perfectScrollbar(); 
        });
    }

    //右侧搜索结果滚动加载更多
    function addMoreResult() {
        var resultLi_template = '<li class="grid curGrid"><a href="javascript:void(0);"><img src="#smallImgPath" data-bigpic="#bigImgPath" data-t="#tablename" data-id="#tableid" data-index="#index" data-col="#column" data-total="#total" data-type="#type"></a></li>';
        var oldTop_r = 0;
        var scrollFlage = true;
        $(".layerRight .resultBox").scroll(function () {
            var self = $(this);
            var scrollT = self.scrollTop();
            var resultUlHei = self.find("ul").outerHeight();
            resultHei = self.height();
            var html = "";
            var fixedHei = parseInt((resultUlHei - resultHei) / 3 * 2);
            var liLen = self.find("li").length;
            if (liLen === totalNum) {
                return;
            }
            //console.log("one1 "+resultUlHei);
            if (scrollFlage == true) {
                if (oldTop_r < scrollT && scrollT >= fixedHei) {//向下滚动
                    scrollFlage = false;
                    var $img = self.find('li').filter('.cur').find('img');
                    // 如果是聚合页则请求加载搜索结果
                    var cat_s = $('.shaixuan_category');
                    var catshow = cat_s.find('.category_num_cur');
                    var cat_ele = catshow.find('em');
                    var txt=cat_ele.text();
                    var type_list=catshow.children(".cateNum_list");
                    var type = $img.data('type');
                    var col = $img.data('col');
					var link = $('#link');
					var colPid = link.data('col');
                    var keys = link.data('search');                    
                    var aUrl = '/ajax/getmoresealist/';
                    var cat_s = $('.shaixuan_category');
                    var catshowt=1;                    
                    type_list.children("a").each(function(){
                        var ntxt=$(this).text() || "";
                        var val=$(this).attr("data-val") || "";
                        if(ntxt==txt && ntxt!=""){
                            catshowt=val;
                            return false;
                        }
                    });
                    var getMoreParams = {p:p, page:pageNum++, cat:catshowt, type:type, col:col, colPid:colPid, limit:12, key:keys};
                    $.post(aUrl, getMoreParams, function (data) {
                        var result = data.result;
                        for (var i in result) {
                            if (result.hasOwnProperty(i)) {
                                html += resultLi_template.replace("#smallImgPath", result[i].smallPath).replace("#bigImgPath", result[i].bigPath).replace("#tablename", result[i].tableName).replace("#tableid", result[i].id).replace("#index", result[i].offset).replace("#column", result[i].columnId).replace("#total", data.total).replace('#type', data.type);
                            }
                        }
                        $("#J_MoreSea #J_MoreSeaUl").append(html);
                        imagesLoaded($('.pop_layer_page #J_MoreSea #J_MoreSeaUl'), function () {
                            waterfallThatAlert();
                        });
                        scrollFlage = true;
                        oldTop_r = scrollT;
                    }, "json");
                }
            }

        });
    }


    
    // 初始化弹出层大图图片大小
    var oBigImg = $("#bigImages");
    if (oBigImg.length) {
        var oImg = oBigImg[0];
        var wheelScroll = function (ev) {
            var ev = ev || window.event;
            var zoom = parseInt(oImg.style.zoom, 10) || 100;
            var image = new Image();
            image.src = oImg.src;
            var imgW = oImg.width;
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
            imgW = imgW * (zoom / 100);
            if (zoom > 50) {
                oImg.style.cssText = '-moz-transform:scale(' + zoom / 100 + ');-moz-transform-origin: center top 0px;';
                oImg.style.zoom = zoom + "%"; //ff，opera不支持zoom发大缩小
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
                oBigImg.addClass("szoom").removeClass('dragIcon');
            }
        };
        $("#draggable").draggable();
    }
   
    // 点击放大镜按钮
    var isPop_layer = pop_layer.length;
    pop_layer.on('click', '.enlarge', function (e) {
        var bigImages = $("#bigImages");
        var src = $('.bigpic').filter('.on').attr('src');
        $('.layer_bgBlack').show();
        $('.bigImg').show().find('.layerBigPic').attr('src', src);
        oCommon.SetImg(bigImages[0], 658, 1038);
        bigImages.addClass("szoom");
        intBigImg();
        clearInterval(timer);
        e.stopPropagation();
    });
    // 获取图片原始大小
    function natural(img){        
        var image = new Image();
        image.src=bigImages.src;
        var naturalWid = image.width;
        var naturalHei = image.height;
        bigImages.width = naturalWid;
        bigImages.height = naturalHei;     
    }
    // 图片初始大小
    pop_layer.on('click', '.original_size', function() {
        var bigImages = $("#bigImages");
        natural(bigImages);        
        intBigImg()
    });
    // 重置图片
     pop_layer.on('click', '.reset_size', function() {
        var bigImages = $("#bigImages");
        oCommon.SetImg(bigImages[0], 658, 1038);        
        intBigImg()
    });

    function intBigImg() {
        var bigImgBox = $("#draggable");
        bigImgBox.css({"left": "50%", "margin-left": "-600px", "top": 0});
        bigImgBox.find("img").css({'-moz-transform': 'scale(1)', "zoom": "100%"});
    }

    /*关闭大图弹层*/
    pop_layer.on('click', '.layerClose', function () {
        $('.bigImg').find('.layerBigPic').attr('src', '');
        $('.layer_bgBlack').hide();
        $('.bigImg').hide();
        timer = setInterval(lunbo, 5000);
    });
    //弹出层搜索结果左右切换-键盘左右键，细节切换-键盘上下键，关闭弹出层-键盘ESC
     $(document).keydown(function(event){   
        var event = event ? event: (window.event ? window.event: null);
        if (event && event.keyCode == 37){ //left
            $(".pop_layer_page .leftBtn").trigger("click");
        }           
        if (event && event.keyCode == 39){//right
            $(".pop_layer_page .rightBtn").trigger("click");
        }
        if (event && event.keyCode == 38){ //up
            $(".pop_layer_page .detailBox .prevbtn").trigger("click");
//          return false;
        }
        if (event && event.keyCode == 40){//down
            $(".pop_layer_page .detailBox .nextbtn").trigger("click");
//          return false;
        }
        if (event && event.keyCode == 27){//esc
            if(($(".collect_layer").is(":hidden") || $(".collect_layer").length <= 0) && $(".bigImg").is(":hidden") && $('.pop_download').is(':hidden')){
               $(".pop_layer_page .pop_close").trigger("click");
            }
            else if(!$(".bigImg").is(":hidden")){
                $(".pop_layer_page .layerClose").trigger("click");
            }
            else{
                return false;
            }
        }
        else{
          return;
        }
    });
    // 收藏
    pop_layer.on("click", "#J_Collect", function() {
        var self = $(this).find('a');
        var iscollected = self.data('iscollected');
        if (iscollected == 2) {
            var usertype = self.data('usertype');
            if (4 == usertype) {
                //普通用户点击无反应
                return; 
            }
            var layer_bg = $('.layer_bg');  // 弹层
            var collectSureBox = $(".collect_layer_sure");
            layer_bg.show();
            collectSureBox.find( 'p' ).html( '对不起，只有设计师专属账号才能使用此功能，<br/>请您<a href="/member/associate/" title="添加" target="_blank">添加</a>或登录设计师专属账号！' );
            collectSureBox.show();
            setTimeout(function(){
                collectSureBox.find('.closeBtn').trigger('click');
            },3000);
            return;
        }
        if (iscollected) {
            var status = 0;
        }else {
            var status = 1;
        }
        var colpara = self.data('colpara');
        var params = colpara.split('-');
        var callback = function () {
            if(status == 0) {
                self.removeClass('cur').find('span').eq(0).html('收藏');
                self.data('iscollected', 0);
            }else {
                self.addClass('cur').find('span').eq(0).html('已收藏');
                self.data('iscollected', 1);
            }
            if (isF5) {
                window.location.reload();
            }
        };
        oCommon.collect(self, params[0], params[1], params[2], params[3], callback, status);
    });

})(jQuery);
//处理翻页数据
function dealPageTurn(obj,table,id){
	var obj=JSON.parse(obj);
	var ret={}
	ret['total']=obj.length;
	for(var i in obj){
		i = parseInt(i);
		if(obj[i]['tb']==table && obj[i]['id']==id){
			if(obj[i-1]!=undefined){
				ret['pre'] = obj[i-1];
			}
			if(obj[i+1]!=undefined){
				ret['next'] = obj[i+1]
			}
			ret['index'] = i;
			break;
		}
	}
	return ret;
}
function stopPropagation(e) {
  var e = e || window.event;
  if (e.stopPropagation) { //W3C阻止冒泡方法
    e.stopPropagation();
  } else {
    e.cancelBubble = true; //IE阻止冒泡方法
  }
}
function showtips(tip, important) {
  $(".collect_layer .warnTips").hide();
  $('.tipText').html(tip);
  $(".collect_layer .dynamicTip").attr('important', important);
  $(".collect_layer .dynamicTip").show();
}
function limit(obj, l) {
  var txt = $(obj).val();
  if (txt.length > l) {
    showtips('请输入少于' + l + '个字符', 0);
    return false;
  } else {
    $('.dynamicTip').hide();
    return true;
  }
}

//比较数组大小
function maxData(data) {
    var maxD = data[0];
    for (var k = 0; k < data.length; k++) {
        if (maxD < data[k]) {
            maxD = data[k];
        }
    }
    return maxD;
}
//右侧搜索结果瀑布流布局
function waterfallThatAlert() {
    var lieshu = 3;
    var J_GetMoreResult = $("#J_MoreSeaUl");
    var aGrid = $("#J_MoreSeaUl .grid");
    var aMax = [];
    if (aGrid.length <= lieshu) {
        for (var i = 0; i <= aGrid.length - 1; i++) {
            aGrid.eq(i).css({"left": i % lieshu * 78, "top": 0}).addClass('curGrid');
            aMax.push(aGrid.eq(i).outerHeight(true));
        }
        J_GetMoreResult.height(maxData(aMax));
        return false;
    }
    for (var i = 0; i <= aGrid.length - 1; i++) {
        var shangmianhezigao = 0;
        for (var j = i - lieshu; j >= 0; j = j - lieshu) {
            shangmianhezigao = shangmianhezigao + aGrid.eq(j).outerHeight(true);
            aMax.push(shangmianhezigao + aGrid.eq(i).outerHeight(true));
        }
        aGrid.eq(i).css({"left": i % lieshu * 78, "top": shangmianhezigao}).addClass('curGrid');
    }
    J_GetMoreResult.height(maxData(aMax));
}

function TDK(obj) {
    var picdescribeArr = [];
    var picdescribe;
    $.each(obj, function (key, val) {
        picdescribeArr.push(val.txt);
    });
    picdescribe = picdescribeArr.join('');
    var title = picdescribe + '-POP趋势资讯网';
    document.title = title;
    document.Keywords = picdescribe;
    document.Description = picdescribe;
}

// 下载
function down(path,t,id,col) {
    if (typeof path == 'undefined') {
        return;
    }
    var fileType = path.split('.');
    fileType = fileType.pop();	//下载文件格式
	var params={ table:t , id:id , iColumnId:col ,action:'DownCount',fileType:fileType};
	$.ajax({
		type: "get",
		url: "/ajax/setcount/" + Math.random(),
		data: params,
		async: false
	});
    oCommon.download(path);
}

// 浏览量统计
function viewCount(t,id,col) {
   var params={ table:t , id:id , iColumnId:col ,action:'ViewCount'};
	$.ajax({
		type: "get",
		url: "/ajax/setcount/" + Math.random(),
		data: params,
		async: false
	})
}

//搜索框失去/获取焦点
$(function(){
    var Itext=$(".search_cont_r");
    Itext.on('blur', '#search_box', function(){
        $this = $(this);
        var _val = $.trim($this.val());
        if(_val == ''){
            $this.val(this.defaultValue);
        }
    }).on('focus', '#search_box', function(){
        var $this = $(this);
        var txt = $this.val();
        if(txt == this.defaultValue){
            $this.val('');
        }
    }) 

// 3D试衣
    $(".default_option").on('click',function(){
        var list =$(".option_list");
        var display =list.css('display');
        if(display == 'none'){
            list.slideDown(300);
        }else{
            list.slideUp(300);
        }
        
  }); 

})

// 设置图片大小
function imgSize(img){
    var detail_con=$(".js-detail-content");
    var deW=detail_con.width();
    var deH=detail_con.height();
    var imgW=img.width();
    var imgH=img.height();
    if(imgW > imgH){
        imgW=parseInt(deW*70/100);
        img[0].width=imgW;
    }else{
        imgH=parseInt((deH-159)*90/100);
        img[0].height=imgH;
    }
}
 
 
 
 