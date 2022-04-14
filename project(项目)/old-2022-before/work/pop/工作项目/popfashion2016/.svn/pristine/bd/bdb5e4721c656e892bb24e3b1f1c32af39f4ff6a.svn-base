/*
	#author		fanglinna
	#date 		2018/01/15
    #porpuse    款式列表页
*/

$(function(){
    var def={
        limit_page:$('#link').attr('data-limitnum')-0,
        pages:1,                                                                                //当前显示的总页数
        
        show_page:1,                                                                            //当前显示的页
        show_page_size:60,                                                                      //当前显示的一页数量
        
        is_add_dom:false,
        
        now_page:1,                                                                             //后端页
        now_page_size:20,                                                                       //后端显示的一页数量
        total_page:1,                                                                           //总页数
        is_set_page:false,                                                                      //是否设置了分页
        get_times:1,                                                                            //ajax请求次数
        get_times_default:1,                                                                    //默认次数
        get_times_max:3,                                                                        //默认最大次数
        col:$("#link").data('col'),                                                             //栏目id
        url:$("#link").data('url') || '/',                                                      //栏目路径
        param:$("#link").data('param') || '',                                                   //栏目参数
        is_group:$("#link").data('isgroup') || 2,                                               //是否成册 1=成册
        ajax_url:'',
        tag_ele:$('.js-scroll-load'),                                                           //加载dom
        showMask:$("#showMask").val()=="1",                                        //游客弹层
        withConditions:false,                                                                   //筛选显示
        isHide:false,
        searchKey:$("#link").attr("data-search"),                                               //搜索key
        ow:document.documentElement.clientWidth || document.body.clientWidth,
        oh:document.documentElement.clientHeight || document.body.clientHeight,
        
        href_obj:pop_fashion_global.fn.getLocationParameter() || {}
    }
    if(def.is_group==1){
        def.show_page_size=60;
        def.now_page_size=20;
    }else{
        def.show_page_size=90;
        def.now_page_size=30;
    }
    var body_width=$(".style-width").width();
    var style_length=$(".js-style-selection-box").attr('data-report-count');

    def.show_page=def.href_obj.page-0 || 1;
    def.href_obj.key?def.key=def.href_obj.key:'';

    pop_list_page_obj.page=def.show_page;
    pop_list_page_obj.page_size=def.show_page_size;

    // 第2页不显示推荐报告
    if(def.show_page>1){
        $('.js-style-selection-box').hide();
    }

    hideMore();
    getData();

    //游客弹层是否显示
    function isTourist(){
        if(def.showMask && !def.isHide){
            $(".tourist").show();
            $("#isHeight").show();
            if(def.withConditions){
                $(".permission").each(function(index){
                    $(this).hide();
                    if(index==1){
                        $(this).show();
                    }
                })
            }
        }else{
            $(".tourist").hide();
            $("#isHeight").hide();
        }
    }
    
    // 窗口变化监听
    $(window).on('resize',function(){
        // 函数节流
        pop_fashion_global.fn.throttle(function(){
            def.ow=document.documentElement.clientWidth || document.body.clientWidth;
            def.oh=document.documentElement.clientHeight || document.body.clientHeight;
        },this,[]);

        pop_fashion_global.fn.throttle(hideMore,this,[]);
    });

    // 小分页点击事件
    $('.js-page-control .js-prev-page-btn').on('click',function(){
        if(def.is_add_dom==true){return;}
        if(def.show_page!=1){
            def.show_page--;
            pop_list_page_obj.page=def.show_page;
            if(def.show_page==1){
                $('.js-style-selection-box').show();
            }
            def.is_set_page=false;
            setHistoryUrl(def.show_page);
            resetPage();
        }

    });

    $('.js-page-control .js-next-page-btn').on('click',function(){
        if(def.is_add_dom==true){return;}
        if(def.show_page<def.total_page){
            def.show_page++;
            pop_list_page_obj.page=def.show_page;
            if(def.show_page>1){
                $('.js-style-selection-box').hide();
            }
            def.is_set_page=false;
            setHistoryUrl(def.show_page);
            resetPage();
        }
    });
    
    
    function hideMore(){
        if(body_width == 1200){
            if(style_length == 4){
                $(".js-selection-more").hide();
            }else if(style_length >4){
                $(".js-selection-more").show();
            }else if(style_length <3){
                $(".js-style-selection-box").hide();
            }
        }else{
            if(style_length == 5){
                $(".js-selection-more").hide();
            }else if(style_length >5){
                $(".js-selection-more").show();
            }else if(style_length <4){
                $(".js-style-selection-box").hide();
            }
        }
    }
    

    // 加载数据
    function getData(){
        def.is_add_dom=true;
        $('.js-page-section').hide();
        if(def.get_times!=1){
            def.tag_ele.fadeIn(200);
            $(".js-page-control span").eq(0).text(def.show_page);
        }
        def.now_page=(def.show_page-1)*def.get_times_max+def.get_times;
        //key搜索
        var url;
        if(def.searchKey!=''){
            var search=def.searchKey.substr(1,def.searchKey.length);
            url=def.url+setAjaxUrl(def.param,def.now_page)+'/?page='+def.show_page+"&"+search;
        }else{
            url=def.url+setAjaxUrl(def.param,def.now_page)+'/?page='+def.show_page;
        }
        var get_obj={'pageSize':def.now_page_size};
        if(def.key!=undefined){
            get_obj.key=def.key;
        }
        $.ajax({
            type:'post',
            url:url + '&m=' + Math.random(),
            data:get_obj,
            dataType:'json',
            success:function(data){   
                if(data.data==""){
                    def.isHide=true;
                }         
                setDom(data)
            },
            error:noDataFunc
        })
    };
    // 获取数据
    function setDom(data){
        var arr=data.data||[];
        var tag=$(".js-style-lists-box>ul");
        var isGroup = data.info.isGroup;
        var P_Collect = data.info.powers['P_Collect'];
        var isWatermark=data.info.showWatermark;
        def.showMask=data.info.showMask;
        def.withConditions=data.info.withConditions;
        def.total_page=Math.ceil(data.info.totalCount/def.now_page_size/def.get_times_max);      

        var _html='';            
        for(var i=0,len=arr.length;i<len;i++){
            var link=arr[i]["link"]?arr[i]["link"]:"";
            var cover=arr[i]["cover"]?arr[i]["cover"]:"";
            var title=arr[i]["title"]?arr[i]["title"]:"";
            var create_time=arr[i]["create_time"]?arr[i]["create_time"]:"";
            create_time=create_time.substr(0,10);
            var tableName= arr[i]["table"]?arr[i]["table"]:"";
            var id=arr[i]["id"]?arr[i]["id"]:"";
            var columnId=arr[i]["col"]?arr[i]["col"]:"";
            var total=arr[i]["total"]?arr[i]["total"]:"";
            var offset=arr[i]["offset"]?arr[i]["offset"]:0;
            var labels=arr[i]["labels"]?arr[i]["labels"]:[];
            var colorProportion =arr[i]["colorProportion"]?arr[i]["colorProportion"]:[];
            var iCollectStatus =arr[i]["iCollectStatus"]?arr[i]["iCollectStatus"]:"";
            var isNew =arr[i]["isNew"]?arr[i]["isNew"]:null;
            if(isGroup == true){
                _html+='<li>';
                _html+='<a href="'+link+'" target="_blank" ondragstart="return false;" oncontextmenu="return false;" title="查看详情">';
                _html+='<div class="img-div">';
                if(i<5){                            
                    _html+='<img src="'+cover+'" alt="pic">';                       
                    _html+='<img class="rotate-img" src="'+cover+'" alt="pic">';
                }else{                            
                    _html+='<img src="'+'https://imgf2.pop-fashion.com/global/images/loading/style135xMR.png'+'" data-original="'+cover+'" alt="pic">';
                    _html+='<img class="rotate-img" src="'+'https://imgf2.pop-fashion.com/global/images/loading/style135xMR.png'+'" data-original="'+cover+'" alt="pic">';
                } 
                _html+='<div class="rotate-img-layer"></div>';
                _html+='<p itemprop="release time" class="Dtime"><em></em>'+create_time+'</p>';                                
                _html+='</div>';
                _html+='<div class="style-book-text">';
                _html+='<div class="style-book-position">';
                _html+='<p>'+title+'</p>';
                if(isNew == true){                            
                    _html+='<span class="new-icon"></span>';
                }
                _html+='</div>';
                _html+='</div>';
                _html+='</a>';
                _html+='</li>';
            }else{
                _html+='<li>';
                _html+='<div class="img-div">';
                if(P_UserType==5){
                    _html+='<a itemprop="url" href="javascript:void(0);" class="pic-item-area js-general-user-status" ondragstart="return false;" oncontextmenu="return false;" title="查看详情">';
                }else{
                    _html+='<a itemprop="url" href="/details/style/t_'+tableName+'-id_'+id+'-col_'+columnId+'/" class="img_a img_click js-data-collect" data-col="'+columnId+'" data-t="'+tableName+'" data-id="'+id+'" data-total="'+total+'" data-index="'+offset+'" ondragstart="return false;" oncontextmenu="return false;" title="查看详情">';
                }
                if(isWatermark){
                    _html+='<div class="shuiy"></div>';
                }
                if(i<7){
                    _html+='<img itemprop="thumbnail" src="'+cover+'" alt="pic">';
                }else{
                   _html+='<img  itemprop="thumbnail" src="/global/images/loading/style210.jpg" data-original="'+cover+'" alt="pic">';
                }
                _html+='<div class="color-and-time">';
                _html+='<p itemprop="release time" class="Dtime"><em></em>'+create_time+'</p>';
                if(colorProportion && colorProportion.length>0){
                    _html+='<div class="color_darea clearfix">';
                    for(var k=0,clen=colorProportion.length;k<clen;k++){                       
                        _html+='<div style="background:'+colorProportion[k].Color+';width:'+colorProportion[k].Percent+'%;" title="潘通参考值:'+colorProportion[k].pantonColorNumber+'"></div>';
                    }
                    _html+='</div>';
                }            
                _html+='</div>';
                _html+='</a>';
                // 单张下载
                  _html+='<div class="collect-download" ondragstart="return false;" oncontextmenu="return false;">';
                  if(P_Collect == true){
                      if(iCollectStatus == 1){
                          _html+='<a class="style-collect-btn js-collect-btn cur" href="javascript:void(0);"></a>';
                      }else{
                          _html+='<a class="style-collect-btn js-collect-btn" href="javascript:void(0);"></a>';
                      }
                  }
                  if (P_UserType!=4 && P_UserType!=5) {
                    _html+='<a href="javascript:void(0);" class="download-btn js-style-download-btn"></a>';
                  }
                  _html+='</div>';
                // 单张下载
                _html+='</div>';
                _html+='<div class="style-page-text">';
                _html+='<div class="style-page-position">';
                for(var a=0, alen=labels.length;a<alen;a++){
                    _html+='<a href="'+labels[a].lLink+'" title="'+labels[a].name+'">'+labels[a].name+'</a>'
                }
                _html+='</div>';
                _html+='</div>';
                _html+='</li>';
            }
        }
        
        if(_html!=''){
            if(def.get_times==1){
                $('.js-style-lists-box .js-placeholder-item').hide();
                tag.append(_html);
                $('.js-total-info-section').find('p>a').text(data.info.totalCount || '');
                $(".js-page-control span").eq(0).text(def.show_page);
                $(".js-page-control span").eq(1).text(def.total_page);
            }else{
                tag.append(_html);
            }
            $('.js-style-lists-box img').lazyload({effect: "show"});
            var now_scroll=$(window).scrollTop();
            $('html,body').animate({'scrollTop':(now_scroll+1)+'px'},30);
            def.get_times++;

            

            if(def.total_page>1){
                if(def.is_set_page==false){
                    def.is_set_page=true;
                    setPage(def.total_page);   
                }
                
                if(arr.length<def.now_page_size){
                    def.tag_ele.fadeOut(400);
                    def.get_times=def.get_times_max+1;
                    $('.js-page-section').show();
                }else{
                    if(def.get_times>=def.get_times_max){
                        def.tag_ele.fadeOut(400);
                        $('.js-page-section').show();
                    }
                }
            }else{
                $('.js-page-section').hide();
                if(arr.length<def.now_page_size){
                    def.tag_ele.fadeOut(400);
                    def.get_times=def.get_times_max+1;
                }else{
                    if(def.get_times>=def.get_times_max){
                        def.tag_ele.fadeOut(400);
                    }
                }
            }

            // 下载统计
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
            var action = {		//统计数据
              token: statistics_action_token,
              iUserType: P_UserType_action,		//用户类型
              
              iUserId: P_AccountId,  			//用户ID
              sChildUserId: '', 		//子账号id
              sTableName: '', 		//假表名
              iPrid: '',				//当前详情ID
              
              iColumnId: '',			//主栏目ID
              iSubColumnId: '',  		//子栏目ID
              sSelfUrl: location.href, 			//当前URL
              sRefererUrl: document.referrer||'',		//上个页面URL
              iSite: 1, 				//站点
              sLang: 'cn',			//语言
              
              iVisit: 1    		//是否能访问 0-无访问权限 1-有访问权限
            }
            var action_down = true, action_timer = null;
            if(P_UserType=='2'){
                action.sChildUserId = P_UserId;	//子账号  
            }
            // 下载
            $('.js-style-download-btn').on('click', function(){
                setAction($(this));
                actionFunc(action.sTableName,action.iPrid,"download");
                if (!oCommon.downloadPrivilege()) {
                    return;
                }
                actionDown();
                downloadFun($(this))
            });
            function setAction(obj){  // 配置统计数据
                var param_el = obj.parents("li").find("a.js-data-collect");
                action.sTableName = param_el.data('t')||'';
                action.iPrid = param_el.data('id')||'';
                action.iSubColumnId = param_el.data('col')||'';
            }
            function actionDown(){
                if( action_down ){     // 在能够下载统计时， 排除书籍的
                    action_down = false;
                    $.ajax({
                        "url": "//api.pop136.com/internal/statistics.php?action=down&" + Math.random(),
                        "data": action,
                        "type": 'get',
                        'dataType': "jsonp",
                        'jsonp': "callback"
                    });
                    
                    clearTimeout(action_timer);
                    action_timer = setTimeout(function(){
                        action_down = true;
                    }, 3000);
                }
            }
            function downloadFun(obj){
              var down_data=obj.parents("li").find("a.js-data-collect");
              var id = down_data.data('id')||"";
              var col = down_data.data('col')||"";
              var table = down_data.data('t')||"";
              pop_fashion_global.fn.subAjax({
                url:'/ajax/downloadstylesingle/?id='+id+'&col='+col+'&table='+table+'',
                ctp:'application/x-www-form-urlencoded; charset=UTF-8',
                successFunc:function(data){
                  var link = data.data || "";
                  window.location.href = '/download/dlsingle/?dl_link=' + encodeURIComponent(link) + '&' + Math.random();
                },
                errorFunc:function(data){
                  oCommon.showTips(data.message);
                }
              });
            }
        }else{
            // 没有数据
            if(def.show_page>1){
                if(def.is_set_page==true){
                    $('.js-page-section').show();
                }else{
                    $('.js-page-section').hide();
                }
                if(tag.children('li:not(.js-placeholder-item)').length<=0){
                    $('.js-error-section').show();
                    $('.js-style-lists-box').hide();
                    $('.js-page-section').hide();
                }
            }else{
                if(tag.children('li:not(.js-placeholder-item)').length<=0){
                    $('.js-error-section').show();
                    $('.js-style-lists-box').hide();
                }
                $('.js-page-section').hide();
            }

            def.get_times=def.get_times_max+1;
            def.tag_ele.fadeOut(400);
        }
        def.is_add_dom=false;
        isTourist();
    }
    // 标签显示全部
    $(".js-style-images-contain").on('mouseenter mouseleave','.style-page-box li',function(e){
        var label_position=$(this).find(".style-page-position")
        var cur_height=label_position.css('height');
        label_position.css('height','auto');
        var animate_height=label_position.css('height');
        label_position.css('height',cur_height);
        if(e.type=="mouseenter"){
            label_position.animate({height:animate_height},100);
        }else{
            label_position.animate({height:'53px'},50);
        }
    });
    $(".js-style-images-contain").on('mouseenter mouseleave','.style-book-box li',function(e){
        var label_position=$(this).find(".style-book-position")
        var cur_height=label_position.css('height');
        label_position.css('height','auto');
        var animate_height=label_position.css('height');
        label_position.css('height',cur_height);
        if(e.type=="mouseenter"){
            label_position.animate({height:animate_height},100);
        }else{
            label_position.animate({height:'40px'},50);
        }
    });


    // 没有数据
    function noDataFunc(data){
        $(".js-error-section").show();
        $(".js-error-section h4").html("对不起，网络开小差了，请<a href='javascript:location.reload();'>立即刷新</a>重试或稍后打开");
        $('.js-style-lists-box').hide();
        def.is_add_dom=false;
        def.get_times++;
    };
    // 设置分页
    function setPage(total){
        new laypage({
            cont:$('.js-page-section')
            ,pages:total
            ,curr:def.show_page
            ,groups:5
            ,search:'page'
            ,skip:true
            ,first:1
            ,last:total
            ,prev:'<em><</em>上一页'
            ,next:'下一页<em>></em>'
            ,jump:function(obj,is_first){
                def.show_page=obj.curr;
                if(def.show_page>1){
                    $('.js-style-selection-box').hide();
                }else{
                    $('.js-style-selection-box').show();
                }
                pop_list_page_obj.page=def.show_page;
                if(is_first!=true){
                    resetPage();
                }
            }
        });
    };

    if(!def.showMask && !def.withConditions){
        $(window).on('scroll',setScrollFunc);
    }
    function setScrollFunc(){
        // 函数节流
        pop_fashion_global.fn.throttle(getMoreData,this,[]);
    }
    
    function getMoreData(){
        if(def.get_times>def.get_times_max){
            $(window).off('scroll',setScrollFunc);
        }else{
            if(def.is_add_dom==false){
                var now_srcolltop=$(window).scrollTop();
                var tag_scrolltop=def.tag_ele.offset().top;
                var oh = def.oh;

                if (now_srcolltop+oh+100>=tag_scrolltop){
                    getData();
                }
            }
        }
    };


    function resetPage(){                                                                                       //新页重置dom
        def.get_times=def.get_times_default;
        var is_bind=false;
        $(".js-page-control span").eq(0).text(def.show_page);

        $('.js-style-lists-box>ul>li').each(function(){
            if($(this).hasClass('js-placeholder-item')){
                $(this).show();
            }else{
                $(this).remove();
            }
        });
        $('html,body').animate({'scrollTop':$('#anchor').offset().top+'px'},0);
        
        // getData();
    };

    function setAjaxUrl(str,page_num){                                                           //拼接ajax url
        page_num=page_num?page_num:1;
        var re_url=/page_\d+/i;
        var page_str='page_' + page_num;
        if(!str){
            return page_str
        }
        if(re_url.test(str)){
            return str.replace(re_url,page_str);
            
        }else{
            return str+'-'+page_str;
        }
    };

    function setHistoryUrl(page_num){                                                                          //添加浏览器url
        var search_str=location.search;
        var path_name=location.pathname;
        var path_hash=location.hash;
        var complete_url='';
        var page_str='page='+page_num;
        var re=/page=\d+/i;
        // 如果存在pid将去掉
        if(search_str.indexOf('pid=') !== -1){
            var reg = RegExp('[?&]pid=([^-]*)', 'gi');
            search_str = search_str.replace(reg, '');
        }
        if(search_str==''){
            complete_url=path_name+'?'+page_str+path_hash;
        }else{
            if(re.test(search_str)){
                complete_url=location.href.replace(re,page_str);
            }else{
                complete_url=path_name+search_str+'&'+page_str+path_hash;
            }
        }
        location.href=complete_url;
    };
    
});