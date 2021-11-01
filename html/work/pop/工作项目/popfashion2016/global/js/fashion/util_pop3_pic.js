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

var detail_def_obj={
    "total":0,
    "index":0,
    "detail_list":[],       //细节图列表
    "isuc":0,
    "wbid":0,
    "id":'',
    "col":'',
    "table":'',
    "type":'',
    "is_cat_set":false,
    "move_y":0,             //滚动条移动距离
    "dis_index":0,
    "is_move_pic":false,    //是否移动中
    "control_top":74,       //放大镜top距离
    "min_radio":0.5,        //图片放大最小倍数
    "max_radio":3,          //图片放大最大倍数
    "is_first_list":false,  //第一次加载list
    "list_w":10000,     //列表宽度
    'imgW':0,           //图片宽
    'imgH':0,           //图片宽
    '_bigbox':'',
    "lun_timer":null,
    "new_analysis":-1,  //色彩分析内容更新
    "colorPieData":'',

    "action_down": true,   //下载统计
    "action_timer": null,
    "visit":0,
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

    "detail_lun":{          //底部轮播
        item_w:76,      //推荐li宽度
        lun_n:1,        //一次滚动宽度
        hist_n:0,   //历史滚动距离
        is_lun:false,       //是否轮播状态
        has_list:true,      //是否有数据了    
        page:1,             //页数
        pre_page:1,
        next_page:1,
        data:{}
    },
    "jump_url":"",
    power:[82,120,4,50,52,122,55,123,54,57,56]
};


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
function actionView(action){
    $.ajax({
        "url": "//api.pop136.com/internal/statistics.php?action=view&" + Math.random(),
        "data": action,
        "type": 'get',
        'dataType': "jsonp",
        'jsonp': "callback"
    });
}

// 下载统计
function actionDown(action){
    if(detail_def_obj.action_down){     // 在能够下载统计时
        detail_def_obj.action_down = false;
        $.ajax({
            "url": "//api.pop136.com/internal/statistics.php?action=down&" + Math.random(),
            "data": action,
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

// 用户行为统计
var action_data = {
    t:"",
    id:"",
    site:1,
    action_type:"",
    timestamp:"",
    userid:P_AccountId
};
var win_search = pop_fashion_global.fn.getLocationParameter(window.location.search);
var request_id = win_search.request_id || "";
var scene_type = win_search.scene_type || "";
function actionFunc(tablename,id,action_type,request_id,scene_type,func){
    action_data.request_id = request_id;
    action_data.scene_type = scene_type;  
    action_data.t= tablename;
    action_data.id=id;
    action_data.action_type = action_type;       
    action_data.timestamp = new Date().getTime();
    $.ajax({
        type: "get",
        dataType: "jsonp",
        url: 'https://api.pop136.com/internal/datagrand.php',
        data:action_data,
        success:func
    })
}


detail_def_obj.action.iUserId = P_AccountId;	//主账号
if(P_UserType=='2'){
    detail_def_obj.action.sChildUserId = P_AccountType == "child" ? P_UserId :"";	//子账号
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

//初始底部btn链接
function btnLinkInit(detail_list,_i){
    var _arr=detail_list||[];
    if(_arr.length<=0){
        return;
    }
    _i=_i||0;
    var _bp = (_arr[_i].urlencodePath||'');
    //相似图案
    if($('#J_Spatter a').length>0){
        var _href1 = $('#J_Spatter a').attr('href');
        var h_n1='';
        if(_href1.indexOf('?path=')!=-1){
            h_n1='?path=';
        }else if(_href1.indexOf('?imageUrl=')!=-1){
            h_n1='?imageUrl=';
        }
        _href1 = _href1.split(h_n1)[0];
        _href1 += h_n1 + _bp;
        $('#J_Spatter a').attr('href',_href1);
    }
    //相关面料
    if($('#J_Rfabric a').length>0){
        var _href2 = $('#J_Rfabric a').attr('href');
        var h_n2='',src_bef='';
        if(_href2.indexOf('?path=')!=-1){
            h_n2='?path=';
        }else if(_href2.indexOf('?imageUrl=')!=-1){
            src_bef = _arr[_i].staticURL||'';
            h_n2='?imageUrl=';
        }
        _href2 = _href2.split(h_n2)[0];
        _href2 += h_n2 + src_bef + _bp;
        $('#J_Rfabric a').attr('href',_href2);
    }
    //虚拟样衣
    if($('#J_Vcloth a').length>0){
        var _pic = _arr[_i].mbigPath||'';
        var _sign = _arr[_i].sign||'';
        $('#J_Vcloth a').attr({
            'data-pic':_pic,
            'data-sign':_sign
        });
    }
}

(function ($) {
    // 弹层
    var layer_bg = $('.js-bg-layer');
    var collectBox = $(".js-content-layer");

    var timer;
    var nowUrl = window.location.href;
    var index = 0;
    var total = 0;
    var pop_layer = $('#pop_layer');
    var allPic = $('.rightc_main,.js-styleslist-parent,.js-list-parent');
    var p = $('#link').data('param');
    var J_Title = $('#J_Title');                // 标题
    var J_DetailList = $('#J_DetailList');      // 细节大图
    var J_LeftRightBtn = $('#J_LeftRightBtn');  // 左右键
    var J_StyleInfo = $('#J_StyleInfo');        // 款式信息
    var J_SupllierInfo = $('#J_SupllierInfo');  // 供应商信息
    var J_Label = $('#J_Label');                // 关联标签
    var J_MoreSea = $('#J_MoreSea');            // 更多搜索
    var J_MoreSeaNum = $('#J_MoreSeaNum');      // 搜索结果数量
    var J_StyleAd = $('#J_StyleAd');            // 广告
    var J_DownloadType = $('#J_DownloadType');  // 图片下载类型
    var J_DownloadType2= $('#J_DownloadType2'); // 图片下载类型右键
    var J_MoreRec = $('#J_MoreRec');            // 更多推荐
    var J_Collect = $('#J_Collect');            // 更多推荐 T_Collect   T_JoinW T_Download  T_Share
    var J_JoinW = $('#J_JoinW');                //加入工作台
    var J_Download = $('#J_Download');          //下载单张
    var J_MoreRecDis = $('#J_MoreRecDis');      //更多推荐是否显示
    var J_VendorAdLists = $("#J_VendorAdLists");
    var J_Spatter = $("#J_Spatter");             //相关图案
    var J_Rfabric = $("#J_Rfabric");             //相关面料
    var J_Vcloth  =$("#J_Vcloth");               //虚拟样衣
    var J_Visit   =$("#J_Visit");                //判断栏目权限
    var J_Mfabric =$("#J_Mfabric");              //面料定制
    var curUrl;
    var isF5 = pop_layer.data("f5") ? true : false;
    var t, id, col;
    
    // 模板调用
    function replaceChangeContent(data, _i) {
        var data_detail_list = data.detailList || [];
        data.detailList = data.detailList || [{
            'smallPath':data.cover||''
        }];
        _i = _i==undefined?-1:_i;

        POPTOOL.pop.render(J_Title, 'T_Title', data);
        POPTOOL.pop.render(J_DetailList, 'T_DetailList', data);
        POPTOOL.pop.render(J_LeftRightBtn, 'T_LeftRightBtn', data);
        POPTOOL.pop.render(J_StyleInfo, 'T_StyleInfo', data);
        POPTOOL.pop.render(J_SupllierInfo, 'T_SupplierInfo', data);
        POPTOOL.pop.render(J_Label, 'T_Label', data);
        POPTOOL.pop.render(J_StyleAd, 'T_StyleAd', data);
        POPTOOL.pop.render(J_DownloadType, 'T_DownloadType', data);
        POPTOOL.pop.render(J_DownloadType2, 'T_DownloadType', data);
        POPTOOL.pop.render(J_Collect, 'T_Collect', data);
        POPTOOL.pop.render(J_JoinW, 'T_JoinW', data);
        POPTOOL.pop.render(J_Download, 'T_Download', data);
        POPTOOL.pop.render(J_VendorAdLists, 'T_VendorAdLists', data);
        POPTOOL.pop.render(J_Rfabric, 'T_Rfabric', data);
        POPTOOL.pop.render(J_Spatter, 'T_Spatter', data);
        POPTOOL.pop.render(J_Vcloth, 'T_Vcloth', data);
        POPTOOL.pop.render(J_Visit, 'T_Visit', data);
        POPTOOL.pop.render(J_Mfabric, 'T_Mfabric', data);
        // TDK(data.searchKeys);
        // review(data.tablename, data.id);

        detail_def_obj['colorPieData'] = data.colorPieData||[];
        if(detail_def_obj['colorPieData'].length<=0){
            $('.js-analysis-similar').hide();
        }else{
            $('.js-analysis-similar').show();
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

        detail_def_obj.new_analysis = true;     //色彩分析内容更新
        var min_pic=$('.js-min-imgs li'),dragg_img=$('#draggable img');
        if(min_pic.length<=1){
            $(".js-img-detail").hide();
        }else{
            $(".js-img-detail").show();
        }
        if(data.visit==true){       //有权限
            $('.pics-item-control').removeClass('no-bott-set');
            $(".js-detail-footer").removeClass('visi-hidden');
            if(min_pic.length>0){
                draggImg(min_pic.eq(0),dragg_img);
            } 
        }else{
            if(min_pic.length>0){
                min_pic.removeClass('img-sec').addClass('imgs-no-hv');
                // 无栏目权限
                var null_img=$(".js-null-img"),small_img=min_pic.eq(0).find('img').attr('data-sp')||'';
                null_img.attr('src',small_img);
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
            }
        }

        if(!$('.js-detail-search').hasClass('detail-search2')){
            $('.pics-item-control').addClass('item-control-set');
        }else{
            $('.pics-item-control').removeClass('item-control-set');
        }

        detail_def_obj.detail_list = data_detail_list;

        imgScroll();
        detail_def_obj.is_cat_set = false;
        //弹层订货会合辑
        if(data.col=="52"){
            var brandInfo = data.brandInfo;             //品牌信息
            var seasonInfo = data.seasonInfo;           //季节信息
            if (seasonInfo && brandInfo && seasonInfo != "" && brandInfo != "") {
                $('.Scrollbar').mCustomScrollbar({
                    theme:'dark'
                });
                var url = "/books/ordermeeting/bra_" + brandInfo.id + "-sea_" + seasonInfo.id + "/#anchor"
                $.ajax({
                    type: "post",
                    dataType: "json",
                    url: url,
                    data: {
                        pageSize:5
                    },
                    success: function (data) {
                        if (data.code == '0') {
                            var list = data.data;
                            if(list == "" || list == undefined){
                                $('.js-ord-box').hide();
                                return;
                            }
                            $('.js-ord-box').show();
                            var html = '';
                            for (var i = 0; i < list.length; i++){
                                if (i<1) {
                                    html += '<li class="clearfix">';
                                } else {
                                    html += '<li class="clearfix hide">';
                                }
                                html += '<a href="/books/seclist/id_' + list[i].list.id + '-t_' + list[i].tableName + '-yl_' + list[i].list.iPreviewMode + '-col_' + list[i].columnId + '/" target="_blank">' +
                                    '<img src="'+list[i].list.cover+'">' +
                                    '<div class="ordermeeting-title">' +
                                    '<span class="nameText">'+list[i].list.name_text+'</span>' +
                                    '<span class="catName">'+list[i].list.categoryName+'</span>' +
                                    '<span class="catName" style="margin-top: 7px;">'+list[i].list.brandName+'</span></div></a></li>';
                            }
                            if (list.length<2) {
                                $('.js-ord-more').hide();
                            }else{
                                $('.js-ord-more').show();
                            }
                            $('.js-ordermeeting-ul').html('');
                            $('.js-ordermeeting-ul').append(html);
                            $('.js-ord-more').on('click', function () {
                                $('.js-ordermeeting-ul .hide').show();
                                $(this).hide();
                            })
                        } else {
                            $('.js-ord-box').hide();
                        }
                    }
                })
            }
        }else{
            $('.js-ord-box').hide();
        }
        //弹层加载T台推荐数据
        getRunwaysData(data.t,data.id,data.col);
    }

    /*--------------------款式列表页点击查看详情判断   2018/9/12   lut000----------------------*/
    $('.js-list-contain').on('click','a.img_click',function(){
        setDetailPage($(this));
        return false;
    });
    function setDetailPage(obj){
        var self = obj;
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
        detail_def_obj.jump_url = url+'t_'+table+'-id_'+id+'-col_'+col+'/';
        var curUrl;
        var params;
        if(colPid == 121){
            curUrl = url+'t_'+table+'-id_'+id+'-col_'+col+'/';
            params = {p: p, key:search, total: total, index: index, colPid:colPid, type:type, isuc:uc, wbid:wbid, sort:sort};
        }else{
            curUrl = url;
            var encrypt = $("#encrypt");
            var timeStamp = encrypt.data("timestamp");
            var sign = encrypt.data("sign");
            var token = encrypt.data("token");
            var s_p = {t:t,id:id,col:col,timeStamp:timeStamp,sign:sign};            
            var s_p_str = JSON.stringify(s_p);
            params = {p: p, key:search, total: total, index: index, colPid:colPid, type:type, isuc:uc, wbid:wbid, sort:sort,s_p:rsa_encrypt(s_p_str),token:token};  
        }

        $("#otherPageTurnList").val($("#thisPageTurnList").val());
        //是手机端时,不走F5页面
        if(IsPC()==false){
            window.location.href = detail_def_obj.jump_url;
            return ;
        }

        var param_url=link.data('param')||'',dis_index=0;
        if(param_url.indexOf('dis_')!=-1){
            dis_index=parseFloat(param_url.split('dis_')[1])||0;
        }
        // POP_USER
        //先判断权限，cookie中没有值未登录，跳到/details/style/(display中间页)
        if(((P_UserId == null || P_UserId == '') || $("#link").data("pow") == 2) && col != 121 ) {            
            window.open(detail_def_obj.jump_url );
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
                    if(colPid != 121){
                        if(!data.data || data.data == undefined){
                            $("#loadingBg").hide();
                            return false;
                        }   
                        var sign = encrypt.data("sign");
                        data = JSON.parse(aes_decrypt(data.data,sign));
                    }
                    if(data.code == 1){
                        alert(data.msg);
                    }else if (data.code=="5001") {
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
                    var detail_list=data.detailList||[];
                    var _type=data.type||0;
                    var _total=data.total||0;
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
                    POPTOOL.pop.popLayer(pop_layer, detail_def_obj.jump_url, uc);
                    replaceChangeContent(data);
                    // 图案栏目列表用方形的
                    if ($.inArray(col, [80,85,117,84,82,120,121,124]) != -1) {
                        $('.js-detail-list').addClass('js-patter-list');
                    }

                    // 看了又看列表用方形的
                    if ($.inArray(col, [82,120,121,124]) != -1) {
                        $('#J_MoreRec').addClass('see-patter');
                    }

                    // 当前url 
                    detail_def_obj.action.sSelfUrl = detail_def_obj.jump_url +'';
                    if(detail_def_obj.action.sSelfUrl.indexOf('pop-fashion.com')==-1){
                        detail_def_obj.action.sSelfUrl = 'https://www.pop-fashion.com'+detail_def_obj.action.sSelfUrl;
                    }
                    if(data.visit){
                        detail_def_obj.action.iVisit = 1;
                    }else{
                        detail_def_obj.action.iVisit = 0;
                    }
                    //页面初始化
                    detailInit(id,col,_type,uc,wbid,dis_index,detail_list,_total,index,table);

                    detail_def_obj.action.sTableName = table;
                    detail_def_obj.action.iPrid = id;
                    detail_def_obj.action.iSubColumnId = col;
                    detail_def_obj.visit = data.visit;

                    $(".bdsharebuttonbox").attr("data-id",id);
                    $(".bdsharebuttonbox").attr("data-t",table);
                    $(".bdsharebuttonbox").attr("data-col",col);

                    // 浏览量统计
                    actionView(detail_def_obj.action);
                    if($.inArray(col, detail_def_obj.power) != -1){
                        actionFunc(table,id,'view',request_id,scene_type);   // 用户行为  
                        if(detail_def_obj.visit){                      
                            guessLike();    // 猜你喜欢  
                        }
                    }
                   
                },
                complete: function () {
                    $("#loadingBg").hide(); 
                }
            });
        }
        return false;
    }
    /*-----------------end of change------------------*/
    // 点击图片触发弹层
    allPic.on('click', 'a.img_click', function () {
        setDetailPage($(this));
        return false;
    });

    function stopDefault(ev){       //阻止默认事件
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
        $('.js-ord-box').hide();
        $('.js-station-list').removeClass('max-h');
        // 去除全屏
        $('.js-page-control').css({
            'right': '300px'
        });
        $('.js-max-box').removeClass('max-box2');
        $('.js-detail-content, .js-detail-footer>div').css({
            'padding-right': '300px'
        });
        $('.js-nav-right').css({
            'width': '299px'
        });

        if($(window).width() >= 1400) {
            $(".js-page-control-guesslike").css({'right': '441px'});
            $('.js-detail-content-guesslike, .js-detail-footer-guesslike>div').css({
                'padding-right': '441px'
            });
            $('.js-nav-right-guesslike').css({
                'width': '440px'
            });
        }else{
            $(".js-page-control-guesslike").css({'right': '300px'});
            $('.js-detail-content-guesslike, .js-detail-footer-guesslike>div').css({
                'padding-right': '300px'
            });
            $('.js-nav-right-guesslike').css({
                'width': '299px'
            });
        }

        $('.js-detail-search').attr('data-down', 0);
    }

    $body = $('body');
    $('body').on("click", ".js-pop-close", function () {
        closeLayer();
    });
    $('body').on("click", ".js-detail-content", function (ev) {
        // ev.stopPropagation();
        if(!isF5){
            closeLayer();
        }
    });
    $('body').on('click','.js-detail-contant',function(){
        $(".js-downimg-layer,.js-join-workbench-layer,.bd_weixin_popup,.bd_weixin_popup_bg,.js-analysis-similar .prompt-disc").hide();
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


    //切换列表
    function catSet(_ele){
        _ele=_ele||'';
        if(_ele.length<=0){
            detail_def_obj.is_cat_set=false;
            return;
        }
        var self = _ele;
        var this_id=self.data('id')||'';
        var this_col=self.data('col')||'';
        var this_t=self.data('t')||'';
        var total=self.data('total')||'';
        var wbid = self.data('wbid')||'';

        var link = $('#link');
        var p=link.data('param')||'';
        var colPid = link.data('col')||'';
        var sort = link.data('sor')||'';
        var index = self.data('index');
        var type = detail_def_obj.type||0;
        var keys = link.data('search')||'';

        $(".bdsharebuttonbox").attr("data-id",this_id);
        $(".bdsharebuttonbox").attr("data-t",this_t);
        $(".bdsharebuttonbox").attr("data-col",this_col);
        var url = '/details/style/';
        var curUrl;
        var params;
        if(colPid == 121){
            curUrl = url+'t_'+this_t+'-id_'+this_id+'-col_'+this_col+'/';
            var params = {p: p,key:keys,isuc:detail_def_obj.isuc||0,total: detail_def_obj.total,index: index,colPid:colPid,wbid:wbid,'sort':sort,'type':type};
        }else{
            curUrl = url;
            var encrypt = $("#encrypt");
            var timeStamp = encrypt.data("timestamp");
            var sign = encrypt.data("sign");
            var token = encrypt.data("token");
            var s_p = {t:this_t,id:this_id,col:this_col,timeStamp:timeStamp,sign:sign};            
            var s_p_str = JSON.stringify(s_p);
            params = {p: p,key:keys,isuc:detail_def_obj.isuc||0,total: detail_def_obj.total,index: index,colPid:colPid,wbid:wbid,'sort':sort,'type':type,s_p:rsa_encrypt(s_p_str),token:token};
        }        
        detail_def_obj.jump_url = curUrl+'t_'+this_t+'-id_'+this_id+'-col_'+this_col+'/';
        $.ajax({
            type: "post",
            data: params,
            dataType: "json",
            url: curUrl,
            success: function(data){                
                if(colPid != 121){
                    if(!data.data || data.data == undefined){
                        $("#loadingBg").hide();
                        return false;
                    }
                    var sign = encrypt.data("sign");
                    data = JSON.parse(aes_decrypt(data.data,sign));
                }               
                //登陆状态失效
                if(data.code == 1){
                    alert(data.msg);
                }
                POPTOOL.pop.popLayer(pop_layer, detail_def_obj.jump_url, detail_def_obj.isuc);
                // 当前url ,visit, userType
                detail_def_obj.action.sSelfUrl = detail_def_obj.jump_url+'';
                if(detail_def_obj.action.sSelfUrl.indexOf('pop-fashion.com')==-1){
                    detail_def_obj.action.sSelfUrl = 'https://www.pop-fashion.com'+detail_def_obj.action.sSelfUrl;
                }

                var _index=_ele.index();
                detail_def_obj.id = this_id;
                detail_def_obj.table = this_t;
                detail_def_obj.col = this_col;

                detail_def_obj.action.sTableName = this_t;
                detail_def_obj.action.iPrid = this_id;
                detail_def_obj.action.iSubColumnId = this_col;

                replaceChangeContent(data,_index);

                if(data.visit){
                    detail_def_obj.action.iVisit = 1;
                }else{
                    detail_def_obj.action.iVisit = 0;
                }
                // 浏览量统计
                actionView(detail_def_obj.action);
                if($.inArray(detail_def_obj.col,detail_def_obj.power) != -1 && detail_def_obj.visit) {
                    guessLike();    // 猜你喜欢
                }
            },
            error:function(){
                detail_def_obj.is_cat_set=false;
            }
        });
        //统计浏览量
//      viewCount(this_t,this_id,this_col);


    }


    //页面初始化
    function detailInit(_id,_col,_type,_uc,_wbid,dis_index,detail_list,_total,_index,_table){
        _index=_index||0;
        _total=_total||0;
        detail_list=detail_list||[];
        _wbid=_wbid||'';
        dis_index=dis_index||0;
        _id=_id||'';
        _col=_col||'';
        _type=_type||0;
        _table = _table||'';

        //缓存初始化
        detail_def_obj['total'] = _total;
        detail_def_obj['table'] = _table;
        detail_def_obj['index'] = _index;
        detail_def_obj['detail_list'] = detail_list;    //细节图列表
        detail_def_obj['isuc'] = _uc;
        detail_def_obj['wbid'] = _wbid;
        detail_def_obj['id'] = _id;
        detail_def_obj['col'] = _col;
        detail_def_obj['type'] = _type;
        detail_def_obj['is_cat_set'] = false;
        detail_def_obj['dis_index'] = dis_index;    //单品，品牌
        detail_def_obj['move_y'] = 0;               //滚动条移动距离
        detail_def_obj['is_move_pic'] = false;      //是否移动中
        detail_def_obj['control_top'] = 74;     //放大镜top距离
        detail_def_obj['min_radio'] = 0.5;      //图片放大最小倍数
        detail_def_obj['max_radio'] = 3;            //图片放大最大倍数
        detail_def_obj['is_first_list'] = false;    //第一次加载list
        detail_def_obj['list_w'] = 10000;       //列表宽度
        detail_def_obj['imgW'] = 0;           //图片宽
        detail_def_obj['imgH'] = 0;         //图片宽
        detail_def_obj['_bigbox'] = '';
        detail_def_obj['lun_timer'] = null;
        //底部轮播
        detail_def_obj['detail_lun']['item_w'] = 76;        //推荐li宽度
        detail_def_obj['detail_lun']['lun_n'] = 1;      //一次滚动宽度
        detail_def_obj['detail_lun']['hist_n'] = 0;     //历史滚动距离
        detail_def_obj['detail_lun']['is_lun'] = false;     //是否轮播状态
        detail_def_obj['detail_lun']['has_list'] = true;        //是否有数据了    
        detail_def_obj['detail_lun']['page'] = 1;               //页数
        detail_def_obj['detail_lun']['pre_page'] = 1;
        detail_def_obj['detail_lun']['next_page'] = 1;
        detail_def_obj['detail_lun']['data'] = {};


        if(detail_def_obj.dis_index<=1){
            $('.js-patter-contain').css({
                "padding":'8px 54px 0'
            });
            $('.js-kuan-num').hide();
        }else{
            $('.js-patter-contain').css({
                "padding":'8px 86px 0 54px'
            });
        }

        btnLinkInit(detail_def_obj.detail_list,0);

        var detail_prompt_disc=$.cookie('detail_prompt_disc',{ path:'/', domain:'.pop-fashion.com'});
        if(detail_prompt_disc==1){
            $('.js-prompt .prompt-disc').hide();
        }else{
            $('.js-prompt .prompt-disc').show();
        }

        //获取更多图片列表
        var _link=$('#link');
        var _col=_link.data('col')||0;
        var _param=_link.data('param')||'';
        var _page=pop_list_page_obj.page?pop_list_page_obj.page:(_link.data('page')||1);
        var _keys = _link.data('search')||'';

        if(detail_def_obj.dis_index>=2){        //聚合页从第一页开始
            _page=1;
        }
        detail_def_obj.detail_lun.page=_page;
        detail_def_obj.detail_lun.pre_page=_page;
        detail_def_obj.detail_lun.next_page=_page;

        detail_def_obj.detail_lun.data['col']=_col;
        detail_def_obj.detail_lun.data['params']=_param;
        detail_def_obj.detail_lun.data['page']=_page;
        detail_def_obj.detail_lun.data['key']=_keys;

        //请求列表
        $('.js-detail-list').html('').css({"margin-left":0,"width":'10000px'});
        $('.js-patter-contain').hide();

        lunInit();
        lunSetStart(1);
        $('.js-detail-search').addClass('detail-search2');
        getPatterList(0,-1);

        var doc_w=$(window).outerWidth(true),doc_h=$(window).outerHeight(true);

        if(doc_w>1340){
            $('.js-detail-search,.detail-down,.js-detail-collect,.detail-share').removeClass('no-detail');
        }else{
            $('.js-detail-search,.detail-down,.js-detail-collect,.detail-share').addClass('no-detail');
        }

        //大图控制说明
        var tbook_bp_set=$.cookie('tbook_bp_set',{ path:'/', domain:'.pop-fashion.com'});
        if((doc_w<1300 || doc_h<600) && tbook_bp_set!=1){
            $('.js-bp-set').show();
        }else{
            $('.js-bp-set').hide();
        }

        $(window).on('resize',function(){

            var _w=$(window).outerWidth(true),_h=$(window).outerHeight(true);
            var tbook_bp_set=$.cookie('tbook_bp_set',{ path:'/', domain:'.pop-fashion.com'});
            if((_w<1300 || _h<600) && tbook_bp_set!=1){
                $('.js-bp-set').show();
            }else{
                $('.js-bp-set').hide();
            }

            if(_w>1340){
                $('.js-detail-search,.detail-down,.js-detail-collect,.detail-share').removeClass('no-detail');
            }else{
                $('.js-detail-search,.detail-down,.js-detail-collect,.detail-share').addClass('no-detail');
            }
            lunInit();
        });
        // imgScroll();
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
    function draggImg(ele1, ele2){
        $("#draggable img").hide();
        ele2.attr({"src":''});
        $('.js-bp-load').show();
        if($('.js-detail-search').length<=0){
            var bott_h=120;
        }else if(detail_def_obj.is_first_list==false || !$('.js-detail-search').hasClass('detail-search2')){
            var bott_h=79;
        }else{
            var bott_h=79;
        }
        
        var _img=ele1.find('img');
        var _bp=_img.data('bp')||'';        //大图
        var _id=_img.data('id')||'';        //id
        var _t=_img.data('t')||'';      //大图
        var _sp=_img.data('sp')||'';
        var _bpurlencode=_img.data('bpurlencode')||'';
        var _spurlencode=_img.data('spurlencode')||'';
        var _staticurl=_img.data('staticurl')||'';
        var _sign=_img.data('sign')||'';
        var _col=_img.data('col')||'';
        var _shape=_img.data('shape')||'';
        var _size=_img.data('size')||'';
        var _up=_img.data('up')||'';
        var _rename=_img.data('rename')||'';
        var _footprint=_img.attr('footprint')||'';
        var s_obj = new Image(),b_obj = new Image();
        var obj_bp = _bp;
        var obj_sp = _sp;

        s_obj.onload = function(){
            var detail_con=$(".js-detail-content");
            var deW=detail_con.width();
            var deH=detail_con.height();
            var _w=s_obj.width||1;
            var _h=s_obj.height||1;
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
        b_obj.src = obj_bp;

        ele2.attr({
            "src":_bp,
            "data-bp": _bp,
            "data-id":_id,
            "data-t":_t,
            "data-sp":_sp,
            "data-bpurlencode":_bpurlencode,
            "data-spurlencode":_spurlencode,
            "data-staticurl":_staticurl,
            "data-sign":_sign,
            "data-col":_col,
            "data-shape":_shape,
            "data-size":_size,
            "data-rename":_rename,
            "footprint":_footprint
        });
        $('.js-reset').addClass('on-reset');   //初始不能复位
    }

    //底部轮播图
    function lunInit(){
        var _len=$('.js-detail-list li').length;
        if(_len>0){
            detail_def_obj.detail_lun.item_w = $('.js-detail-list li').eq(0).outerWidth(true)||76;      //获取li宽度
        }

        //大小屏包含框宽度

        if(detail_def_obj.dis_index<=1 || detail_def_obj.isuc==1){
            var contain_w=$('.footer-cont').width()-108;
        }else{
            var contain_w=$('.footer-cont').width()-140;
        }
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
        is_init=is_init==undefined?2:is_init;
        is_init=is_init||2;
        _move=_move||-1;
        _type=_type||0;     //加载数据类型  加载，向左加载，向右加载 0,2,1

        if(_type==1 || is_init!=2){
            detail_def_obj.detail_lun.data['page']=detail_def_obj.detail_lun.next_page;     //记录下一页页数
        }else if(_type==2){
            detail_def_obj.detail_lun.pre_page--;
            detail_def_obj.detail_lun.data['page']=detail_def_obj.detail_lun.pre_page;      //记录上一页页数
        }

        $link = $('#link');
        // 款式分页大小
        var pageSize = pop_list_page_obj.page_size?pop_list_page_obj.page_size:($link.data('pagesize')||60);
        detail_def_obj.detail_lun.data['pageSize'] = pageSize;
        var bott_url = "";
        if(detail_def_obj.isuc==1){
            detail_def_obj.detail_lun.data["wbid"] = detail_def_obj.wbid
            bott_url = "/ajax/getworkbenchbottomdatalist/?"+Math.random();
        }else if(detail_def_obj.dis_index<=1){
            bott_url = "/ajax/getbottomdatalist/?"+Math.random();
        }
        if(detail_def_obj.dis_index<=1 || detail_def_obj.isuc==1){          //如果是单张
            pop_fashion_global.fn.subAjax({
                url:bott_url,
                ctp:"application/x-www-form-urlencoded",
                data:detail_def_obj.detail_lun.data,
                successFunc:function(data){
                    $('.js-btn-load-left,.js-btn-load-right').fadeOut(100);
                    var _data=data['data']||[],_len=_data.length,_html='',_info=data['info']||{},_size=_info.pageSize||pageSize,_total=_info.total||2000;
                    if(_len>0){
                        for(var i=0;i<_len;i++){
                            var _col=_data[i]['col']||'';
                            var _cover=_data[i]['cover']||'';
                            var _id=_data[i]['id']||'';
                            var _tb=_data[i]['tb']||'';
                            var _index=_data[i]['index']||0;
                            if(detail_def_obj.isuc ==1){
                                if(detail_def_obj.is_first_list==false && _id==detail_def_obj.id && is_init==2){
                                    _html+='<li class="list-sec" data-index="'+_index+'" data-col="'+_col+'" data-id="'+_id+'" data-t="'+_tb+'" data-isuc="1" data-wbid="'+detail_def_obj.wbid+'"><img src="'+_cover+'"/></li>'
                                }else{
                                    _html+='<li data-index="'+_index+'" data-col="'+_col+'" data-id="'+_id+'" data-t="'+_tb+'" data-isuc="1" data-wbid="'+detail_def_obj.wbid+'"><img src="'+_cover+'"/></li>'
                                }                                
                            }else{                                
                                if(detail_def_obj.is_first_list==false && _id==detail_def_obj.id && is_init==2){
                                    _html+='<li class="list-sec" data-total="'+_total+'" data-index="'+_index+'" data-col="'+_col+'" data-id="'+_id+'" data-t="'+_tb+'"><img src="'+_cover+'"/></li>'
                                }else{
                                    _html+='<li data-total="'+_total+'" data-index="'+_index+'" data-col="'+_col+'" data-id="'+_id+'" data-t="'+_tb+'"><img src="'+_cover+'"/></li>'
                                }
                            }
                        }

                        if(_type==2 && is_init==2){
                            $('.js-detail-list').prepend(_html);
                        }else{
                            $('.js-detail-list').append(_html);
                            if(_size>_len || parseInt(pageSize * pop_list_page_obj.page) == _total){
                                detail_def_obj.detail_lun.has_list=false;       //后面没有数据了
                            }
                        }

                    }else{
                        if(_type==1){
                            detail_def_obj.detail_lun.has_list=false;       //后面没有数据了
                        }
                    }

                    if($('.js-detail-list li').length>=2000){
                        detail_def_obj.detail_lun.has_list=false;       //后面没有数据了
                    }

                    //加载dom增加列表宽度
                    detail_def_obj.list_w+=_len*detail_def_obj.detail_lun.item_w;
                    $('.js-detail-list').css({"width":detail_def_obj.list_w+'px'});

                    if(is_init!=2){
                        detail_def_obj.detail_lun.next_page++;
                        var _i=$('.js-detail-list .list-sec').index();      //当前选中的index
                        detail_def_obj.detail_lun.hist_n = -_i;
                        $('.js-detail-list').stop().animate({
                            "margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
                        },400,function(){
                            $('.js-list-prev').addClass('list-hv-on');
                            detail_def_obj.detail_lun.is_lun=false; //跳出操作
                        });
                    }else if(_type==1){ //向后加载
                        detail_def_obj.detail_lun.next_page++;
                        if(_move==1){
                            var _i=$('.js-detail-list .list-sec').index();      //当前选中的index
                            var cat_ele=$('.js-detail-list li').eq((_i+1));
                            cat_ele.addClass('list-sec').siblings('li').removeClass('list-sec');
                            $('.pics-item-control').eq(0).show();
                            catSet(cat_ele);
                            detail_def_obj.detail_lun.hist_n = -1-_i;
                            $('.js-detail-list').stop().animate({
                                "margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
                            },400,function(){
                                detail_def_obj.detail_lun.is_lun=false; //跳出操作
                            });
                        }else{
                            detail_def_obj.detail_lun.hist_n -= detail_def_obj.detail_lun.lun_n;
                            if(Math.abs(detail_def_obj.detail_lun.hist_n)>=$('.js-detail-list li').length){
                                detail_def_obj.detail_lun.is_lun=false;     //跳出轮播状态
                                return;
                            }
                            $('.js-detail-list').stop().animate({
                                "margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
                            },500,function(){
                                if(Math.abs(detail_def_obj.detail_lun.hist_n-detail_def_obj.detail_lun.lun_n)>=$('.js-detail-list li').length && detail_def_obj.detail_lun.has_list==false){
                                    $('.js-list-next').removeClass('list-hv-on');
                                }
                                detail_def_obj.detail_lun.is_lun=false;     //跳出轮播状态
                            });
                        }
                    }else if(_type==2){
                        detail_def_obj.detail_lun.hist_n -= _len;
                        $('.js-detail-list').stop().css({"margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'});

                        if(_move==2){
                            //当前视线之外
                            var _i=$('.js-detail-list .list-sec').index();      //当前选中的index
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
                                detail_def_obj.detail_lun.is_lun=false; //跳出操作
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
                                detail_def_obj.detail_lun.is_lun=false;     //跳出轮播状态
                            });
                        }
                    }else{
                        detail_def_obj.detail_lun.is_lun=false; //跳出操作
                        detail_def_obj.detail_lun.next_page++;
                    }

                    //轮播列表初始化
                    if(detail_def_obj.is_first_list==false){        //list-hv-on
                        var _i=$('.js-detail-list .list-sec').index();      //当前选中的index
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

                        // detail_def_obj.detail_lun.is_lun=true;      //轮播状态
                        // $('.js-detail-search').removeClass('detail-search2');
                        // $('.js-patter-contain').slideDown(10);
                        // $('.pics-item-control').addClass('item-control-set');


                        lunFirstL();        //轮播初始位置
                        detail_def_obj.is_first_list=true;
                    }

                },
                errorFunc:function(){
                    detail_def_obj.detail_lun.is_lun=false; //跳出操作
                }
            });
        }else{      //如果不是单张  聚合页
            var aUrl = '/ajax/getmoresealist/?'+Math.random();

            var link = $('#link');
            var colPid = link.data('col');
            var keys = link.data('search')||'';
            var catshowt=detail_def_obj.dis_index;
            var _p = link.data('param')||'';
            var col=detail_def_obj.col;
            var type=detail_def_obj.type;

            var _href='javascript:void(0);';

            var getMoreParams = {
                p:_p,
                page:detail_def_obj.detail_lun.next_page,
                cat:catshowt,
                type:type,
                col:col,
                colPid:colPid,
                limit:40,
                key:keys
            };
            $.post(aUrl, getMoreParams, function (data) {
                $('.js-btn-load-left,.js-btn-load-right').fadeOut(100);
                var _data=data.result||[],_html='',_len=_data.length;
                _href=data.link||_href;
                var total_num=data.total||0;        //总
                for(var i=0;i<_len;i++){
                    var smallPath=_data[i]['smallPath']||'';
                    var bigPath=_data[i]['bigPath']||'';
                    var id=_data[i]['id']||'';
                    var tableName=_data[i]['tableName']||'';
                    var offset=_data[i]['offset']||0;
                    var columnId=_data[i]['columnId']||'';
                    var type1=_data[i]['type']||'';
                    var _total=_data[i]['total']||'';

                    if(detail_def_obj.is_first_list==false && id==detail_def_obj.id){
                        _html+='<li class="list-sec"  data-bigpic="'+bigPath+'" data-t="'+tableName+'" data-id="'+id+'" data-index="'+offset+'" data-col="'+columnId+'" data-total="'+_total+'" data-type="'+type1+'"><img src="'+smallPath+'"/></li>'
                    }else{
                        _html+='<li  data-bigpic="'+bigPath+'" data-t="'+tableName+'" data-id="'+id+'" data-index="'+offset+'" data-col="'+columnId+'" data-total="'+_total+'" data-type="'+type1+'"><img src="'+smallPath+'"/></li>'
                    }

                }
                $('.js-detail-list').append(_html);
                detail_def_obj.detail_lun.next_page++;
                var list_len=$('.js-detail-list li').length;

                if(total_num <= list_len || list_len>=2000 ){
                    detail_def_obj.detail_lun.has_list=false;       //后面没有数据了
                }

                //加载dom增加列表宽度
                detail_def_obj.list_w+=_len*detail_def_obj.detail_lun.item_w;
                $('.js-detail-list').css({"width":detail_def_obj.list_w+'px'});

                if(is_init!=2){
                    var _i=$('.js-detail-list .list-sec').index();      //当前选中的index
                    detail_def_obj.detail_lun.hist_n = -_i;
                    $('.js-detail-list').stop().animate({
                        "margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
                    },400,function(){
                        $('.js-list-prev').addClass('list-hv-on');
                        detail_def_obj.detail_lun.is_lun=false; //跳出操作
                    });
                }else if(_type==1){ //向后加载
                    if(_move==1){
                        var _i=$('.js-detail-list .list-sec').index();      //当前选中的index
                        var cat_ele=$('.js-detail-list li').eq((_i+1));
                        cat_ele.addClass('list-sec').siblings('li').removeClass('list-sec');
                        $('.pics-item-control').eq(0).show();
                        catSet(cat_ele);
                        detail_def_obj.detail_lun.hist_n = -1-_i;
                        $('.js-detail-list').stop().animate({
                            "margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
                        },400,function(){
                            detail_def_obj.detail_lun.is_lun=false; //跳出操作
                        });
                    }else{
                        detail_def_obj.detail_lun.hist_n -= detail_def_obj.detail_lun.lun_n;
                        if(Math.abs(detail_def_obj.detail_lun.hist_n)>=$('.js-detail-list li').length){
                            detail_def_obj.detail_lun.is_lun=false;     //跳出轮播状态
                            return;
                        }
                        $('.js-detail-list').stop().animate({
                            "margin-left":detail_def_obj.detail_lun.hist_n*detail_def_obj.detail_lun.item_w+'px'
                        },500,function(){
                            if(Math.abs(detail_def_obj.detail_lun.hist_n-detail_def_obj.detail_lun.lun_n)>=$('.js-detail-list li').length && detail_def_obj.detail_lun.has_list==false){
                                $('.js-list-next').removeClass('list-hv-on');
                            }
                            detail_def_obj.detail_lun.is_lun=false;     //轮播状态
                        });
                    }
                }

                //轮播列表初始化
                if(detail_def_obj.is_first_list==false){
                    var _i=$('.js-detail-list .list-sec').index();      //当前选中的index
                    var contain_w=$('.footer-cont').width()-140;
                    detail_def_obj.detail_lun.lun_n = Math.floor(contain_w/detail_def_obj.detail_lun.item_w);
                    if(_len > (detail_def_obj.detail_lun.lun_n||10)){
                        $('.js-list-next').addClass('list-hv-on');
                    }
                    if(_i==0){
                        $('.pics-item-control').eq(0).hide();
                    }
                    if(_i==_len-1 && detail_def_obj.detail_lun.has_list==false){
                        $('.pics-item-control').eq(1).hide();
                    }

                    $('.js-kuan-num').attr('href',_href).show();
                    $('.js-kuan-num').find('p').text(total_num);

                    // $('.js-detail-search').removeClass('detail-search2');
                    // $('.js-patter-contain').slideDown(10);
                    // $('.pics-item-control').addClass('item-control-set');

                    // detail_def_obj.detail_lun.is_lun=true;      //轮播状态
                    lunFirstL();        //轮播初始位置
                    detail_def_obj.is_first_list=true;
                }

            }, "json");
        }
    }


    //轮播位置初始化
    function lunFirstL(){
        var _i=$('.js-detail-list .list-sec').index();      //当前选中的index
        var lun_n=detail_def_obj.detail_lun.lun_n||20;
        var _l=Math.abs( detail_def_obj.detail_lun.hist_n - lun_n);
        var _s=Math.abs( detail_def_obj.detail_lun.hist_n );

        if(_i+1 >= _s && _i+1 <= _l-1){     //当前视线之内
            detail_def_obj.detail_lun.is_lun=false;     //跳出 轮播状态
        }else {         //视线外
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
                $(this).addClass('detail-search2').attr('data-down', 1);
                $('.js-patter-contain').slideUp(200);
                $('.pics-item-control').removeClass('item-control-set');
            }
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
                    if(!isF5){
                        lunInit();
                    }
                    is_max = true;
                });

                $('.js-detail-content, .js-detail-footer>div').animate({
                    'padding-right': '300px'
                }, 200);

                $('.js-nav-right').animate({
                    'width': '299px'
                }, 200);

                navResizeFunc();

                if($('.js-detail-search').length){  // 底部展示
                    var _search = $('.js-detail-search').attr('data-down')||1;
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
                    if(!isF5){
                        lunInit();
                    }
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

    $(window).on("resize", function() {
        navResizeFunc();
    });
    function navResizeFunc() {
        if($(window).width() >= 1400) {
            $('.js-page-control-guesslike').animate({
                'right': '441px'
            }, 200, function(){
                $('.js-max-box').removeClass('max-box2');
                if(!isF5){
                    lunInit();
                }
                is_max = true;
            });

            $('.js-detail-content-guesslike, .js-detail-footer-guesslike>div').animate({
                'padding-right': '441px'
            }, 200);

            $('.js-nav-right-guesslike').animate({
                'width': '440px'
            }, 200);
        }else{
            $('.js-page-control-guesslike').animate({
                'right': '300px'
            }, 200, function(){
                $('.js-max-box').removeClass('max-box2');
                if(!isF5){
                    lunInit();
                }
                is_max = true;
            });

            $('.js-detail-content-guesslike, .js-detail-footer-guesslike>div').animate({
                'padding-right': '300px'
            }, 200);

            $('.js-nav-right-guesslike').animate({
                'width': '299px'
            }, 200);
        }
    }

    //文案提示框
    pop_layer.on('click','.js-nav-control-disc,.prompt-disc,.js-analysis-similar',function(ev){
        pop_fashion_global.fn.stopBubble(ev);       //阻止事件冒泡
    })

    //点击显示底部说明
    pop_layer.on('click','.js-prompt',function(){
        $(this).find('.prompt-disc').toggle();
    });


    // echarts图表的配置项和数据画图
    function createPie (main, data) {
        data = data||[],_len=data.length;

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
//              data[i].labelLine = labelLine;
            }

            data[i].itemStyle = itemStyle;
            data[i].labelLine = labelLine;
        }

        $('.js-analysis-similar').find('.pie-box').removeClass('pie-start');

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
                    radius: ['44.6%', '65%'],
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
                            length2: 75
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
        detail_def_obj.new_analysis = false;

    };


    //色彩分析
    pop_layer.on('click','.js-analysis-similar',function(ev){
        pop_fashion_global.fn.stopBubble(ev);       //阻止事件冒泡
        //数据更新中不可点击
        if(isF5){
            if(detail_def_obj.new_analysis == -1){
                return;
            }
        }else{
            if(detail_def_obj.new_analysis == -1 || detail_def_obj.detail_lun.is_lun==true || detail_def_obj.is_cat_set==true){
                return;
            }
        }        
        var analysis_ele = $(this).find('.prompt-disc');
        if(analysis_ele.is(":hidden")){
            if(detail_def_obj.new_analysis == false){   //已经有了直接显示
                analysis_ele.show();
            }else{          //没有请求渲染
                if(detail_def_obj.colorPieData.length>0 && $('.js-min-imgs li>img').length>0){
                    var _pic = $('.js-min-imgs li>img').eq(0).attr('data-sp')||'';

                    analysis_ele.find('.pie-box').addClass('pie-start');
                    analysis_ele.find('.pic-box>img').attr('src', _pic);
                    analysis_ele.find('#analysis-pie').html('');
                    analysis_ele.find('.js-analysis-simil').attr('href','/coloranalysis/?path='+_pic+'&t='+detail_def_obj.table+'&id='+detail_def_obj.id);
                    analysis_ele.show();

                    createPie(document.getElementById('analysis-pie'), detail_def_obj.colorPieData); //色彩分析
                }
            }
        }else{
            analysis_ele.hide();
        }
    });

    pop_layer.on('click','.js-prompt .dic-close',function(ev){
        pop_fashion_global.fn.stopBubble(ev);       //阻止事件冒泡
        $.cookie('detail_prompt_disc',1,{ path:'/', expires: 365, domain:'.pop-fashion.com'});
        $(this).parents('.prompt-disc').hide();
    });

    pop_layer.on('click','.js-analysis-similar .prompt-disc>i',function(ev){
        $(this).parents('.prompt-disc').hide();
    });

    //导航按键收缩
    $(document).on('keydown',function(ev){      //按键
        //图案列表初始化后
        if(!isF5 && detail_def_obj.detail_lun.is_lun!=true && detail_def_obj.is_first_list!=false){
            keyFunc(ev)
        }else{
            keyFunc(ev)
        }
    });

    // 按键切换
    function keyFunc(ev){
        var e=ev||event;
        var _v=e.keyCode;
        if(_v==37){//左
            stopDefault(ev);
            if($('.js-pic-prev').length>0){
                $(".js-downimg-layer,.js-join-workbench-layer,.bd_weixin_popup,.bd_weixin_popup_bg,.js-analysis-similar .prompt-disc").hide();
                picPrev();
            }
        }else if(_v==39){//右
            stopDefault(ev);
            if($('.js-pic-next').length>0){
                $(".js-downimg-layer,.js-join-workbench-layer,.bd_weixin_popup,.bd_weixin_popup_bg,.js-analysis-similar .prompt-disc").hide();
                pixNext();
            }
        }else if(_v==38){//上
            stopDefault(ev);
            if($('.js-min-imgs li').length>1 && $('.js-min-imgs .img-sec').length>0){
                var _index=$('.js-min-imgs .img-sec').index();
                if(_index>0){
                    $(".js-downimg-layer,.js-join-workbench-layer,.bd_weixin_popup,.bd_weixin_popup_bg,.js-analysis-similar .prompt-disc").hide();
                    $('.js-min-imgs li').eq(_index-1).addClass('img-sec').siblings('li').removeClass('img-sec');
                    var min_pic=$('.js-min-imgs li').eq(_index-1),dragg_img=$('#draggable img');
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
                    $(".js-downimg-layer,.js-join-workbench-layer,.bd_weixin_popup,.bd_weixin_popup_bg,.js-analysis-similar .prompt-disc").hide();
                    $('.js-min-imgs li').eq(_index+1).addClass('img-sec').siblings('li').removeClass('img-sec');
                    var min_pic=$('.js-min-imgs li').eq(_index+1),dragg_img=$('#draggable img');
                    //重置大图
                    draggImg(min_pic,dragg_img);
                    btnLinkInit(detail_def_obj.detail_list,(_index+1));
                }
            }
        }else if(_v==27){       //esc
            closeLayer();
        }
    }

    //细节图列表
    pop_layer.on('click','.js-min-imgs li', function(){
        if(!$(this).hasClass('img-sec') && !$(this).hasClass('imgs-no-hv')){
            var _index=$(this).index();
            $(this).addClass('img-sec').siblings('li').removeClass('img-sec');
            var min_pic=$(this),dragg_img=$('#draggable img');
            //重置大图
            draggImg(min_pic,dragg_img);
            btnLinkInit(detail_def_obj.detail_list,_index);
        }
    });

    //放大缩小滚动条
    pop_layer.on('mousedown', '.js-control-l i',function(e){
        if($("#draggable img").is(":hidden")){
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
                ySet(_y);       //同步放大倍数
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


    //轮播左滑
    pop_layer.on('click','.js-list-prev',function(){
        if(detail_def_obj.detail_lun.is_lun==true || detail_def_obj.is_first_list==false){
            return;
        }
        detail_def_obj.detail_lun.is_lun=true;      //轮播状态
        var _len=$('.js-detail-list li').length;
        //到左边并有数据
        if(detail_def_obj.detail_lun.hist_n+detail_def_obj.detail_lun.lun_n >0 && detail_def_obj.detail_lun.pre_page>1 && _len<2000 && detail_def_obj.dis_index<=1){
            //向前加载数据
            $('.js-list-next').addClass('list-hv-on');
            $('.js-btn-load-left').fadeIn(100);
            getPatterList(2);
            return;
        }

        if(_len>=2000 && detail_def_obj.dis_index<=1 && detail_def_obj.detail_lun.hist_n>=0){   //单张页最左边
            //超出长度提示
            layer_bg.show();
            collectBox.find( 'p' ).html( '还有更多内容继续浏览搜索列表~' );
            collectBox.show();
            collectSureBoxHide();
            detail_def_obj.detail_lun.is_lun=false;     //跳出轮播状态
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
            detail_def_obj.detail_lun.is_lun=false;     //跳出轮播状态
        });
    });

    //轮播右滑
    pop_layer.on('click','.js-list-next',function(){
        if(detail_def_obj.detail_lun.is_lun==true || detail_def_obj.is_first_list==false ){
            return;
        }
        detail_def_obj.detail_lun.is_lun=true;      //轮播状态
        var _len=$('.js-detail-list li').length;

        //距离右边近并且少于2000数据
        if(Math.abs(detail_def_obj.detail_lun.hist_n-detail_def_obj.detail_lun.lun_n) >= _len-19 && _len<2000){
            //向后加载
            if(detail_def_obj.detail_lun.has_list==true){
                $('.js-btn-load-right').fadeIn(100);
                $('.js-list-prev').addClass('list-hv-on');
                getPatterList(1);
                return;
            }
        }

        if(Math.abs(detail_def_obj.detail_lun.hist_n-detail_def_obj.detail_lun.lun_n)>=_len){       //轮播到最右端
            if(_len>=2000){         //超出长度提示
                layer_bg.show();
                collectBox.find( 'p' ).html( '还有更多内容继续浏览搜索列表~' );
                collectBox.show();
                collectSureBoxHide();
            }
            detail_def_obj.detail_lun.is_lun=false;     //跳出轮播状态
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
            detail_def_obj.detail_lun.is_lun=false;     //跳出轮播状态
        });
    });

    //上一款
    function picPrev(){
        if(detail_def_obj.detail_lun.is_lun==true || detail_def_obj.is_first_list==false || detail_def_obj.is_cat_set==true){       //轮播中
            return;
        }
        detail_def_obj.detail_lun.is_lun=true;
        detail_def_obj.is_cat_set=true;

        var _len=$('.js-detail-list li').length;
        var _i=$('.js-detail-list .list-sec').index();      //当前选中的index
        var _l=Math.abs( detail_def_obj.detail_lun.hist_n - detail_def_obj.detail_lun.lun_n);
        var _s=Math.abs( detail_def_obj.detail_lun.hist_n );

        if(_i-1 >= _s && _i-1 <= _l && _i>0){       //当前视线之内
            var cat_ele=$('.js-detail-list li').eq((_i-1));
            cat_ele.addClass('list-sec').siblings('li').removeClass('list-sec');
            $('.pics-item-control').eq(1).show();
            catSet(cat_ele);
            setTimeout(function(){
                detail_def_obj.detail_lun.is_lun=false; //跳出操作
            },200);
        }else if(_i<=0 && detail_def_obj.detail_lun.pre_page>1){        //当前列表之外加载数据
            if(detail_def_obj.dis_index<=1 || detail_def_obj.isuc==1){
                if(_len>=2000){ //单张页最左边
                    //超出长度提示
                    layer_bg.show();
                    collectBox.find( 'p' ).html( '还有更多内容继续浏览搜索列表~' );
                    collectBox.show();
                    collectSureBoxHide();
                    detail_def_obj.is_cat_set=false;
                    detail_def_obj.detail_lun.is_lun=false; //跳出操作
                    return;
                }
                $('.js-btn-load-left').fadeIn(100);
                getPatterList(2,2);
            }else{      //聚合页  个人中心不加载前面的
                detail_def_obj.is_cat_set=false;
                detail_def_obj.detail_lun.is_lun=false; //跳出操作
            }
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
                detail_def_obj.detail_lun.is_lun=false; //跳出操作
            });
        }
    }

    //下一款
    function pixNext(){
        if(detail_def_obj.detail_lun.is_lun==true || detail_def_obj.is_first_list==false || detail_def_obj.is_cat_set==true){       //轮播中
            return;
        }
        detail_def_obj.detail_lun.is_lun=true;
        detail_def_obj.is_cat_set=true;

        var _i=$('.js-detail-list .list-sec').index();      //当前选中的index
        var _l=Math.abs( detail_def_obj.detail_lun.hist_n - detail_def_obj.detail_lun.lun_n);
        var _s=Math.abs( detail_def_obj.detail_lun.hist_n );

        var _len=$(".js-detail-list li").length;

        if(_i+1 >= _s && _i+1 <= _l-1 && _i<_len-1){        //当前视线之内
            var cat_ele=$('.js-detail-list li').eq((_i+1));
            cat_ele.addClass('list-sec').siblings('li').removeClass('list-sec');
            $('.pics-item-control').eq(0).show();
            catSet(cat_ele);
            
            setTimeout(function(){
                detail_def_obj.detail_lun.is_lun=false; //跳出操作
            },300);
        }else {         //视线外
            if(_i>=_len-20 && _len<2000 && detail_def_obj.detail_lun.has_list==true || detail_def_obj.isuc==1){     //当前列表之外加载数据
                $('.js-btn-load-right').fadeIn(100);
                getPatterList(1,1);     //后面添加数据并移动
            }else{

                if(_i>=_len-1){
                    if(_len>=2000 ){    //最右边
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
                    detail_def_obj.detail_lun.is_lun=false; //跳出操作
                });
            }
        }
    }

    //上一款
    pop_layer.on('click','.js-pic-prev',function(ev){
        ev.stopPropagation();
        $(".js-downimg-layer").hide();
        picPrev();
    });

    //下一款
    pop_layer.on('click','.js-pic-next',function(ev){
        ev.stopPropagation();
        $(".js-downimg-layer").hide();
        pixNext();
    });

    //款式图案选择
    pop_layer.on('click','.js-detail-list li',function(){
        if(detail_def_obj.detail_lun.is_lun==true || detail_def_obj.is_first_list==false || detail_def_obj.is_cat_set==true){       //轮播中
            return;
        }
        detail_def_obj.is_cat_set=true;
        $(this).addClass('list-sec').siblings('li').removeClass('list-sec');
        catSet($(this));
    });

    //看了又看 
    pop_layer.on('click','.js-detail-see',function(){
        seeModel();
    });
    //看了又看关闭
    pop_layer.on('click','.js-see-close,.js-see-cont',function(e){
        e.stopPropagation();
        $('.js-see-cont').fadeOut(200);
    });
    //去除冒泡
    pop_layer.on('click','.js-see-cont>.see-more',function(e){
        var ev=e||event;
        pop_fashion_global.fn.stopBubble(ev);
    });

    //获取看了又看模块
    function seeModel(){
        if($('.js-visit-quanxian img').length > 0){
            var img_ele=$('.js-visit-quanxian img');

            var col=img_ele.attr('data-col') ,id=img_ele.attr('data-id') ,table=img_ele.attr('data-t');

            $('.js-see-cont').fadeIn(200);
            POPTOOL.pop.render(J_MoreRec, 'T_MoreRecLodingTemplate');
            var _data = {'report':[], 'style':[]};

            pop_fashion_global.fn.subAjax({
                "url":"/ajax/getmorereportrec/?" + Math.random(),
                ctp:"application/x-www-form-urlencoded",
                data:{
                    t:table,
                    id:id,
                    col:col,
                    type:"report"
                },
                successFunc:function(data){

                    if (data.code == 0) {
                        _data['report'] = data.data.report;
                    }
                    getMoreReport();
                }
            });

            function getMoreReport(){
                pop_fashion_global.fn.subAjax({
                    "url":"/ajax/getmorereportrec/?" + Math.random(),
                    ctp:"application/x-www-form-urlencoded",
                    data:{
                        t:table,
                        id:id,
                        col:col,
                        type:"style"
                    },
                    successFunc:function(data){
                        if (data.code == 0) {
                            _data['style'] = data.data.style;
                        }

                        POPTOOL.pop.render(J_MoreRec, 'T_MoreRecTemplate', _data);
                    }
                });
            }
        }
    }

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
        if($("#draggable img").is(":hidden")){
            return;
        }
        $('.js-reset').removeClass('on-reset');
        natural($("#J_Visit img.js-bigbox"));
        intBigImg();
    });

    // 重置图片--复位
    pop_layer.on('click', '.js-reset', function() {
        if($("#draggable img").is(":hidden")){
            return;
        }
        if(!$(this).hasClass('on-reset')){
            $(this).addClass('on-reset');
            imgSize($("#draggable img"),detail_def_obj.imgW,detail_def_obj.imgH);
            intBigImg();
        }
    });

    pop_layer.on('click', '.js-bp-set .dic-close', function(ev) {
        pop_fashion_global.fn.stopBubble(ev);       //阻止事件冒泡
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
    var collectSureTime;
    pop_layer.on("click", "#J_Collect", function() {        
        var self = $(this).find('a');
        var iscollected = self.data('iscollected');
        var id = self.data('id');
        var t = self.data('t');
        var col = self.data('col');
        // 用户行为
        if($.inArray(col, detail_def_obj.power) != -1){
            actionFunc(t,id,'collect',request_id,scene_type)
        }  
        if (iscollected == 2) {
            var usertype = self.data('usertype');
            if (4 == usertype) {
                //普通用户点击无反应
                return;
            }
            layer_bg.show();
            collectBox.find( 'p' ).html( '您当前为管理员账号，使用设计师专属账号可收藏喜欢的款式~' );
            collectBox.show();
            collectSureBoxHide();
            return;
        }
        if (iscollected) {
            var status = 0;
            layer_bg.show();
            collectBox.find( 'p' ).html( '已取消收藏' );
            collectBox.show();
        }else {
            var status = 1;
            layer_bg.show();
            collectBox.find( 'p' ).html( '收藏成功' );
            collectBox.show();
        }
        collectSureBoxHide();
        var colpara = self.data('colpara');
        var params = colpara.split('-');
        var callback = function () {
            if(status == 0) {
                self.removeClass('is-collected').find('span').eq(0).html('加入收藏');
                self.data('iscollected', 0);
            }else {
                self.addClass('is-collected').find('span').eq(0).html('已收藏');
                self.data('iscollected', 1);
            }
            // if (isF5) {
            //     window.location.reload();
            // }
        };
        oCommon.collect(self, params[0], params[1], params[2], params[3], callback, status);              
    });
    function collectSureBoxHide(){
        collectSureTime=setTimeout(function(){
            clearInterval(collectSureTime);
            layer_bg.fadeOut(500);
            collectBox.fadeOut(500);
        },2000);
    }

    // new加入工作台    
    var smallImage, bigImage, tablename, tableid, column, $detail;
    var J_WorkBenchList = $('#J_Workbench');

    pop_layer.on('click','#J_JoinW',function(){
        // 获取当前图片的路径
        $detail = $('.js-bigbox');
        smallImage = $detail.attr('data-sp');
        bigImage = $detail.attr('data-bp');
        tablename = $detail.attr('data-t');
        tableid = $detail.attr('data-id');
        column = parseInt($detail.attr('data-col'));
        
        // 用户行为        
        if($.inArray(column, detail_def_obj.power) != -1){
            actionFunc(tablename,tableid,'platform',request_id,scene_type)
        }        
        var url = '/ajax/getworkbenchlist/'+Math.random();
        var params = {t:tablename,col:column,id:tableid,sp:smallImage};
        $.post(
            url,
            params,
            function(data){
                POPTOOL.pop.render(J_WorkBenchList, 'T_Workbench', data);

                var join_workbench_layer = J_WorkBenchList.find(".js-join-workbench-layer");
                var join_picture = $(".js-join-picture");
                join_workbench_layer.show();
                join_picture.find('img').attr('src', smallImage);
            },
            'json'
        );
    });

    // 创建工作台，input框获取焦点
    pop_layer.on('focus','.js-input-text',function(){
        createBtn($(this));
    });
    pop_layer.on('keyup','.js-input-text',function(){
        createBtn($(this));
    });
    function createBtn(obj){
        $(".js-msg-info").hide();
        var input_button = $('.js-input-button')
        var selTxt = $.trim(obj.val());
        if(selTxt == ""){
            input_button.addClass('gray-bg').show();
        }else{
            input_button.removeClass('gray-bg').show();
        }
    }

    // 点击创建--创建工作台
    pop_layer.on('click', '.js-input-button', function(ev){
        var self=$(this);
        var selTxt = $.trim(self.siblings(".js-input-text").val());
        var reg_n = /^[\u4e00-\u9fa5\w]+$/;        
        if(selTxt != ""){
            if(!RegExp(reg_n, 'ig').test(selTxt) || selTxt.length > 20 ){
                alert('请输入不超过20字符的数字、字母、中文！');
                return false;
            }
            $.ajax({
                url: '/ajax/createworkbench/'+Math.random(),
                data: {name: selTxt},
                type: 'POST',
                dataType: 'json',
                async: true,
                success: function(data){
                    if (data.s == 1) {
                        var _li = '<li data-workbenchid="'+data.id+'" class=""><span>'+selTxt+'</span><a href="javascript:void(0);" title="加入">加入</a></li>';
                        J_WorkBenchList.find(".workbench-name").prepend(_li);
                        self.hide();
                        self.siblings('input').val("");
                    } else if(data.s==0 && data.code==1){
                        $(".js-msg-info").html('已有相同的工作台，换个名字吧~')
                        $(".js-msg-info").show();
                    } else if(data.s==0 && data.code==2){
                        $(".js-workbench-num,.bg-layer").show();
                    }
                }
            });
        }
        stopPropagation(ev);
    });

    // 工作台数量弹窗
    pop_layer.on('click','.w-cancel-btn',function(){
        $(".js-workbench-num,.bg-layer").hide();
    });
    pop_layer.on('click','.w-admin-btn',function(){
        $(".js-join-workbench-layer,.js-workbench-num,.bg-layer").hide();
    });

    // 加入工作台
    pop_layer.on('click','#J_Workbench li',function(){
        if($(this).hasClass('full-li')) return;

        var workbenchId = $(this).data('workbenchid');
        var workname = $(this).find('span').text();
        var param = {
            wkb: workbenchId,
            t: tablename,
            id: tableid,
            col: column,
            sp: smallImage,
            bp: bigImage
        };
        $.ajax({
            url:'/ajax/createworkbenchimage/'+Math.random(),
            data: param,
            type: 'POST',
            dataType: 'json',
            async: true,
            success: function(data){
                $(".js-join-workbench-layer").hide();
                layer_bg.show();
                collectBox.find( 'p' ).html('已保存到<span>'+workname+'</span>工作台');
                collectBox.show();
                collectSureBoxHide();
            }

        });
    });
    // 关闭创建工作台
    pop_layer.on('click', '.js-workbench-close', function(){
        $(".js-join-workbench-layer").hide();
    });

    // 面料定制
    pop_layer.on("mouseenter mouseleave","#J_Mfabric",function(e){
        if(e.type == "mouseenter"){
            $(this).find(".popky-infor").show();
        }else{
            $(this).find(".popky-infor").hide();
        }
    });

    // 虚拟样衣
    pop_layer.on('click', '#fitting_cat', function() {
        var self = $(this);
        var t = self.data('t');
        var id = self.data('id');
        var pic = self.attr('data-pic');
        var sign = self.attr('data-sign');
        var col = parseInt(self.attr('data-col'));
        // 用户行为       
        if($.inArray(col, detail_def_obj.power) != -1) {
            actionFunc(t,id,'imitate',request_id,scene_type);
        }
        var params={table:t, id:id};
        $.ajax({
            type: "get",
            url: "/ajax/recordfitting/" + Math.random(),
            data: params,
            async: true
        });        
            
        codepic = encodeURIComponent(encodeURIComponent(oCommon.popReplace(pic)));
        url = '/details/virtualSpl/';
        window.open(url+'?id='+id+'&t='+t+'&col='+col+'&sign='+sign+'&codepic='+codepic);               
    });

    pop_layer.on('click', '.js-down-close', function () {
        layer_bg.hide();
        $('.js-downimg-layer').hide();
        $('.js-downimg-right').hide();
    });

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
        // 下载行为上报
        if($.inArray(column, detail_def_obj.power) != -1) {
            actionFunc(t,id,'download',request_id,scene_type);
        } 
        if(column==null || id==null || t==null){
            return false;
        }
        // 80=>款式模板（矢量图） 82=>图案素材
        if ($.inArray(column, [73,80,82,120]) != -1) {
            // 获取下载格式
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
    // 素材类下载
    pop_layer.on('click', '.js-download-btn', function () {        
        $detail = $('.js-bigbox');
        var bigImage = $(this).attr('data-bp');
        var bigImage_suffix =  bigImage.split('.').pop();

        column = parseInt($detail.attr('data-col'));
        id = $detail.attr('data-id');
        t = $detail.attr('data-t');
        detail_def_obj.action.sTableName = t;
        detail_def_obj.action.iPrid=id;
        detail_def_obj.action.iSubColumnId=column;
        if($.inArray(column, detail_def_obj.power) != -1) {
            actionFunc(t,id,'download',request_id,scene_type);
        }
        // 简单的权限控制
        if($(this).hasClass('pow')){
            return;
        }
        if (!oCommon.downloadPrivilege()) {
            return;
        }
        // 下载重命名
        var _rename= $detail.attr('data-rename')==undefined||$detail.attr('data-rename')==""?"":"?rename="+$detail.attr('data-rename');
        _rename = _rename.split('.').shift() + '.' + bigImage_suffix;
        if($.inArray(column, [80,82,120,117]) != -1){
            bigImage = 'https://imgf1.pop-fashion.com' + decodeURIComponent(bigImage);
        }
        bigImage = bigImage + _rename;

        down(bigImage,t,id,column);

        var type=$(this).attr("data-type");
        if(type==1){
            layer_bg.hide();
            $('.js-downimg-layer').hide();
            $('.js-downimg-right').hide();
        }
    });


    // 分享用户行为
    $(".bdsharebuttonbox").on("click","a", function(){
        var id = $(this).parent("div").attr("data-id");
        var t = $(this).parent("div").attr("data-t");
        var col = parseInt($(this).parent("div").attr("data-col"));
        if($.inArray(col, detail_def_obj.power) != -1) {
            actionFunc(t,id,'share',request_id,scene_type)
        }
    })


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
    // 百度分享 https://www.hrwhisper.me/baidu-share-not-support-https-solution/
    window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"1","bdMiniList":false,"bdPic":"","bdStyle":"0","bdSize":"24"},"share":{}};with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='/static/api/js/share.js?v=89860593.js?cdnversion='+~(-new Date()/36e5)];

    // 进入弹层重新计算图片大小
    function imgSize(img,_w,_h){
        if(img.length>0){
            if($('.js-detail-search').length<=0){
                var bott_h=120;
            }else if(detail_def_obj.is_first_list==false || !$('.js-detail-search').hasClass('detail-search2')){
                var bott_h=79;
            }else{
                var bott_h=79;
            }
            var detail_con=$(".js-detail-content");
            var deW=detail_con.width();
            var deH=detail_con.height();
            detail_def_obj.imgW=_w||1;
            detail_def_obj.imgH=_h||1;
            if(_h > deH-bott_h){
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
            }else{
                img[0].height=_h;
                img[0].width=_w;
            }
            
            $('.js-bp-load').hide();
            $("#draggable img").show();
        }
    }

    // 猜你喜欢
    function guessLike(){
        var def = {
            guess_data:{
                t:detail_def_obj.table,
                id:detail_def_obj.id,
                col:detail_def_obj.col
            },
            is_add_dom:false
        }		
        getData()
        function getData(){
            def.is_add_dom = true;		
            $.ajax({
                type: "post",
                dataType: "json",
                url: '/patterns/getLikeDataByPopId/',
                data:def.guess_data,
                success:function(data){
                    def.is_add_dom = false;
                    var arr=data.data||[];
                    if(!arr.length || data.code == 1001){
                        return false;
                    }
                    $(".guess-you-like").show();
                    loadData(arr);				
                }
            })
        }
        

        function loadData(totalArr){			
            var ele = $(".js-guess-imgs");
            var _html = "";
            for(var i=0;i<totalArr.length;i++){
                var cover=totalArr[i]["cover"]?totalArr[i]["cover"]:"";
                var title=totalArr[i]["title"]?totalArr[i]["title"]:"";
                var tableName= totalArr[i]["t"]?totalArr[i]["t"]:"";
                var id=totalArr[i]["id"]?totalArr[i]["id"]:"";
                var columnId=totalArr[i]["columnId"]?totalArr[i]["columnId"]:"";
                var request_id =totalArr[i]["request_id"]?totalArr[i]["request_id"]:"";
                var link=totalArr[i]["link"]?totalArr[i]["link"]:"";
                cover = cover.indexOf("pop-fashion") != -1 ? cover : static_url+cover;
                _html+='<li>';
                _html+='<a  href="'+link+'"  data-t="'+tableName+'" data-col="'+columnId+'" data-id="'+id+'" data-request_id="'+request_id+'" target="_blank">';
                _html+='<img alt="'+title+'" src="'+cover+'" />';
                _html+='</a>';
                _html+='<span class="js-dislike-img">不喜欢</span>';
                _html+='</li>';
            }            
            ele.html(_html);
            $('.Scrollbar').mCustomScrollbar({
                theme:'dark'
            });		
        }
        
    }
    pop_layer.on("click", ".js-guess-imgs a", function() {
        var this_t = $(this).data("t");
        var this_id = $(this).data("id");
        var request_id = $(this).data("request_id");
        actionFunc(this_t,this_id,'rec_click',request_id,'relate_picture_clothing');
    });

    pop_layer.on("click", ".js-dislike-img", function() {
        var this_t = $(this).siblings("a").data("t");
        var this_id = $(this).siblings("a").data("id");
        var request_id = $(this).siblings("a").data("request_id");
        var _this_html = $(this).parents("li");
        actionFunc(this_t,this_id,'dislike',request_id,'relate_picture_clothing',function(){
            _this_html.remove();
        });
    })
    /* 推荐T台数据 */
    function getRunwaysData(t,id,col){
        var url=location.href;
        var param={
            pageSize:10
        }
        if(t && id && col){
            param.t=t;
            param.id=id;
            param.col=col;
        }else{
            if(url.indexOf('/style/')<0){
                return;
            }
            var _data='';
            if(url.indexOf('?')>0){
                _data=url.substring(url.indexOf('/style/')+7,url.indexOf('?'));
            }else{
                _data=url.substring(url.indexOf('/style/')+7,url.length-1);
            }
            var list=_data.split('-');
            param.t=list[0].substring(list[0].indexOf('t_')+2,list[0].length);
            param.id=list[1].substring(list[1].indexOf('id_')+3,list[1].length);
            param.col=list[2].substring(list[2].indexOf('col_')+4,list[2].length);
        }
        $('.station-more').on('click',function(){
            $(this).hide();
            $('.js-station-list').addClass('max-h');
            $('.Scrollbar').mCustomScrollbar({
                theme:'dark'
            });
        })
        $.ajax({
            url:"/ajax/getRunwaysData/",
            type:"get",
            dataType:"json",
            data:param,
            success:function(data){
                var html='';
                var list=data.data;
                for(var i =0;i<list.length;i++){
                    html+='<a href="/runways/inside/id_'+list[i].id+'/" target="_blank" class="station-item clearfix">';
                    if(list[i].cover_image_tag == '1'){
                        if(list[i].cover.indexOf('<img')>=0){
                            html+='<div class="item-left">'+list[i].cover+'</div>';
                        }else{
                            html+='<div class="item-left"><img data-original="'+list[i].cover+'" alt="'+list[i].nme+'" itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/loading/runway.gif/></div>';
                        }
                    }else{
                        html+='<div class="item-left img-list">'+list[i].cover+'</div>';
                    }
                    html+='<div class="item-right">'+
                        '<div class="tit">'+list[i].nme+'</div>'+
                        '<div class="des">地点：'+list[i].regionName+'</div>'+
                        '</div></a>';
                }
                $('.js-station-list').removeClass('max-h');
                $('.js-station-list').html('');
                $('.js-station-list').append(html);
                //图片懒加载
                $('.item-left img').lazyload({
                    effect: "fadeIn",
                    threshold: 200
                });
                if(list.length==0){
                    $('.T-station').hide();
                }else if(list.length<=1){
                    $('.station-more').hide();
                    $('.T-station').show();
                }else if(list.length>1){
                    $('.station-more').show();
                    $('.T-station').show();
                }
                $(window).trigger("resize");
            },
            error:function(){

            }
        })
    }
    getRunwaysData();


})(jQuery);
// 下载
function down(path,t,id,col) {
    if (typeof path == 'undefined') {
        return;
    }
    var fileType = path.split('.');
    fileType = fileType.pop();  //下载文件格式
    var params={ table:t , id:id , iColumnId:col ,action:'DownCount',fileType:fileType};
    detail_def_obj.action.sTableName = t;
    detail_def_obj.action.iPrid = id;
    detail_def_obj.action.iSubColumnId = col;
    // 下载统计
    actionDown(detail_def_obj.action);
    oCommon.download(path);
}

// 右键点击
function rightClick(position) {
    var imgShape = $('#imgShape');
    var imgSize = $('#imgSize');
    var isDetailImg = imgShape.length > 0 ? true : false;
    var curImg = $('#draggable').find('img');
    if (parseInt(curImg.data('col')) == 121) {
        return false;
    }
    /*if (!oCommon.downloadPrivilege()) {
        return false;
    }*/
    if (isDetailImg) {
        imgShape.html(curImg.data('shape'));
        imgSize.html(curImg.data('size'));
        $('.downimg-layer-data').find('.js-download-btn').attr({'data-bp':curImg.attr('data-bp'),"data-type":1});
    }else{
        //素材(有多种下载格式)
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

