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
        get_times_max:2,                                                                        //默认最大次数
        col:$("#link").data('col'),                                                             //栏目id
        url:$("#link").data('url') || '/',                                                      //栏目路径
        param:$("#link").data('param') || '',                                                   //栏目参数
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
    
    if(def.col==81){
        def.show_page_size=42;
        def.now_page_size=21;
    }else{
        def.show_page_size=60;
        def.now_page_size=30;
    }

    def.show_page=def.href_obj.page || 1;
    def.href_obj.key?def.key=def.href_obj.key:'';

    pop_list_page_obj.page=def.show_page;
    pop_list_page_obj.page_size=def.show_page_size;

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
    });

    // 小分页点击事件
    $('.js-page-control .js-prev-page-btn').on('click',function(){
        if(def.is_add_dom==true){return;}
        if(def.show_page!=1){
            def.show_page--;
            pop_list_page_obj.page=def.show_page;
            
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
            
            def.is_set_page=false;
            setHistoryUrl(def.show_page);
            resetPage();
        }
    });

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
        pop_fashion_global.fn.subAjax({
            url:url,
            data:get_obj,
            ctp:'application/x-www-form-urlencoded; charset=UTF-8',
            successFunc:function(data){       
                if(data.data==""){
                    def.isHide=true;
                }
                setDom(data)
            },
            errorFunc:noDataFunc
        })
    }

    function setDom(data){
        var arr=data.data||[];
        var tag=$(".js-references-list-box>ul");
        var shade = data.info.powers['shade'];
        var isWatermark=data.info.showWatermark;
        def.showMask=data.info.showMask;
        def.withConditions=data.info.withConditions;
        def.total_page=Math.ceil(data.info.totalCount/def.now_page_size/def.get_times_max);

        var _html="";                        
        for(var i=0,len=arr.length;i<len;i++){
            var link=arr[i]["link"]?arr[i]["link"]:"";
            var cover=arr[i]["cover"]?arr[i]["cover"]:"";
            var title=arr[i]["title"]?arr[i]["title"]:"";
            var updateTime=arr[i]["updateTime"]?arr[i]["updateTime"]:"";
            // create_time=create_time.substr(0,10);
            var tableName= arr[i]["tableName"]?arr[i]["tableName"]:"";
            var id=arr[i]["id"]?arr[i]["id"]:"";
            var columnId=arr[i]["columnId"]?arr[i]["columnId"]:"";
            var total=arr[i]["total"]?arr[i]["total"]:"";
            var offset=arr[i]["offset"]?arr[i]["offset"]:0;
            var labels=arr[i]["labels"]?arr[i]["labels"]:[];
            var colorProportion =arr[i]["colorProportion"]?arr[i]["colorProportion"]:[];
            var iCollectStatus =arr[i]["iCollectStatus"]?arr[i]["iCollectStatus"]:"";
            var isNew =arr[i]["isNew"]?arr[i]["isNew"]:null;
            if(def.col == 80){
                _html+='<li>';
                _html+='<div itemscope class="pic_box">';
                if(P_UserType==5){
                    _html+='<a class="pic-item-area js-general-user-status" href="javascript:void(0);" title="查看详情">';
                }else{
                    _html+='<a itemprop="url" class="img_click" href="/details/style/t_'+tableName+'-id_'+id+'-col_'+columnId+'/" data-t="'+tableName+'" data-col="'+columnId+'" data-id="'+id+'" data-total="'+total+'" data-index="'+offset+'" title="查看详情">';
                }
                if(isWatermark){
                    _html+='<div itemscope class="shuiy"></div>';
                }
                // if(cover.length >30){
                    
                // }else{
                //     _html+='<img itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/loading/referenceMR150.jpg">';
                // }
                if(i>5){
                    _html+='<img alt="'+title+'" itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/loading/reference150-150.gif" data-original="'+cover+'" alt="pic">';
                }else{
                    _html+='<img alt="'+title+'" itemprop="thumbnail" src="'+cover+'" data-original="'+cover+'" alt="pic">';
                }
                _html+='<div class="color-time-box">';
                _html+='<p class="time"><em></em>'+updateTime+'</p>';
                _html+='</div>';
                _html+='</a>';
                _html+='</div>';
                _html+='<div class="titleBox">';
                _html+='<div class="patter-label-position">';
                for(var a=0, alen = labels.length;a<alen;a++){
                    _html+='<a class="label-link" href="'+labels[a].lLink+'" title="'+labels[a].name+'">'+labels[a].name+'</a>'
                }
                _html+='</div>';
                _html+='</div>';
                _html+='</li>';
            }else if(def.col == 81){
                _html+='<li>';
                _html+='<div itemscope class="pic_box">';
                if(P_UserType==5){
                    _html+='<a class="xijieImg pic-item-area js-general-user-status" href="javascript:void(0);" title="查看详情">';
                }else{
                    _html+='<a itemprop="url" class="xijieImg img_click" href="/details/style/t_'+tableName+'-id_'+id+'-col_'+columnId+'/" data-t="'+tableName+'" data-col="'+columnId+'" data-id="'+id+'" data-total="'+total+'" data-index="'+offset+'" title="查看详情">';
                }                
                if(isWatermark){
                    _html+='<div itemscope class="shuiy"></div>';
                }
                // if(cover.length >30){
                    
                // }else{
                //     _html+='<img itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/loading/referenceMR165.jpg">';
                // }
                if(i>6){
                    _html+='<img alt="'+title+'" itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/loading/reference165-220.gif" data-original="'+cover+'" alt="pic">';
                }else{
                    _html+='<img alt="'+title+'" itemprop="thumbnail" src="'+cover+'" data-original="'+cover+'" alt="pic">';
                }
                _html+='<p class="time"><em></em>'+updateTime+'</p>';
                _html+='</a>';
                _html+='</div>';
                _html+='<div class="titleBox">';
                _html+='<div class="references-label-position">';
                for(var a=0, alen = labels.length;a<alen;a++){
                    _html+='<a class="label-link" href="'+labels[a].lLink+'" title="'+labels[a].name+'">'+labels[a].name+'</a>'
                }
                _html+='</div>';
                _html+='</div>';
                _html+='</li>';
            }else if(def.col == 84){
                _html+='<li>';
                _html+='<div itemscope class="pic_box">';
                if(P_UserType==5){
                    _html+='<a class="pic-item-area js-general-user-status" href="javascript:void(0);" title="查看详情">';
                }else{
                    _html+='<a itemprop="url" class="img_click" href="/details/style/t_'+tableName+'-id_'+id+'-col_'+columnId+'/" data-t="'+tableName+'" data-col="'+columnId+'" data-id="'+id+'" data-total="'+total+'" data-index="'+offset+'" title="查看详情">';
                }
                                    
                if(isWatermark){
                    _html+='<div itemscope class="shuiy"></div>';
                }
                // if(cover.length >30){
                    
                // }else{
                //     _html+='<img itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/loading/referenceMR150.jpg">';
                // }
                if(i>6){
                    _html+='<img alt="'+title+'" itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/loading/reference150-150.gif" data-original="'+cover+'" alt="pic">';
                }else{
                    _html+='<img alt="'+title+'" itemprop="thumbnail" src="'+cover+'" data-original="'+cover+'" alt="pic">';
                }
                _html+='<div class="color-time-box">';
                _html+='<p class="time"><em></em>'+updateTime+'</p>';
                _html+='</div>';
                _html+='</a>';
                _html+='</div>';
                _html+='<div class="titleBox">';
                _html+='<div class="patter-label-position">';
                for(var a=0, alen = labels.length;a<alen;a++){
                    _html+='<a class="label-link" href="'+labels[a].lLink+'" title="'+labels[a].name+'">'+labels[a].name+'</a>'
                }
                _html+='</div>';
                _html+='</div>';
                _html+='</li>';                    
            }else if(def.col == 85){
                _html+='<li>';
                _html+='<div itemscope class="pic_box">';
                if(P_UserType==5){
                    _html+='<a class="pic-item-area js-general-user-status" href="javascript:void(0);" title="查看详情">';
                }else{
                    _html+='<a itemprop="url" class="img_click" href="/details/style/t_'+tableName+'-id_'+id+'-col_'+columnId+'/" data-t="'+tableName+'" data-col="'+columnId+'" data-id="'+id+'" data-total="'+total+'" data-index="'+offset+'" title="查看详情">';
                }
                                    
                if(isWatermark){
                    _html+='<div itemscope class="shuiy"></div>';
                }
                // if(cover.length >30){
                    
                // }else{
                //     _html+='<img itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/loading/referenceMR.jpg">';
                // }
                if(i>4){
                    _html+='<img alt="'+title+'" itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/loading/reference1.gif" data-original="'+cover+'" alt="pic">';
                }else{
                    _html+='<img alt="'+title+'" itemprop="thumbnail" src="'+cover+'" data-original="'+cover+'" alt="pic">';
                }
                _html+='</a>';
                _html+='</div>';
                _html+='<div class="titleBox">';
                _html+='<div class="references-chuchuang-position">';
                for(var a=0, alen = labels.length;a<alen;a++){
                    _html+='<a class="label-link" href="'+labels[a].lLink+'" title="'+labels[a].name+'">'+labels[a].name+'</a>'
                }
                _html+='</div>';
                _html+='</div>';
                _html+='</li>';
            }else{
                _html+='<li>';
                _html+='<div itemscope class="pic_box">';
                if(P_UserType==5){
                    _html+='<a class="pic-item-area js-general-user-status" href="javascript:void(0);" title="查看详情">';
                }else{
                    _html+='<a itemprop="url" class="img_click" href="/details/style/t_'+tableName+'-id_'+id+'-col_'+columnId+'/" data-t="'+tableName+'" data-col="'+columnId+'" data-id="'+id+'" data-total="'+total+'" data-index="'+offset+'" title="查看详情">';
                }
                                    
                if(isWatermark){
                    _html+='<div itemscope class="shuiy"></div>';
                }
                // if(cover.length >30){
                    
                // }else{
                //     _html+='<img itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/loading/referenceMR150.jpg">';
                // }
                if(i>5){
                    _html+='<img alt="'+title+'" itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/loading/reference150-150.gif" data-original="'+cover+'" alt="pic">';
                }else{
                    _html+='<img alt="'+title+'" itemprop="thumbnail" src="'+cover+'" data-original="'+cover+'" alt="pic">';
                }
                _html+='<div class="color-time-box">';
                _html+='<p class="time"><em></em>'+updateTime+'</p>';
                _html+='</div>';
                _html+='</a>';
                _html+='</div>';
                _html+='<div class="titleBox">';
                _html+='<div class="patter-label-position">';
                for(var a=0, alen = labels.length;a<alen;a++){
                    _html+='<a class="label-link" href="'+labels[a].lLink+'" title="'+labels[a].name+'">'+labels[a].name+'</a>'
                }
                _html+='</div>';
                _html+='</div>';
                _html+='</li>';
            }
            
        }
        if(_html!=''){

            if(def.get_times==1){
                $('.js-references-list-box .js-placeholder-item').hide();
                tag.append(_html);
                $('.js-total-info-section').find('p>a').text(data.info.totalCount || '');
                $(".js-page-control span").eq(0).text(def.show_page);
                $(".js-page-control span").eq(1).text(def.total_page);
            }else{
                tag.append(_html);
            }
            $('.js-references-list-box img').lazyload({effect: "show"});
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
                    $('.js-references-list-box').hide();
                    $('.js-page-section').hide();
                }
            }else{
                if(tag.children('li:not(.js-placeholder-item)').length<=0){
                    $('.js-error-section').show();
                    $('.js-references-list-box').hide();
                }
                $('.js-page-section').hide();
            }
            
            def.get_times=def.get_times_max+1;
            def.tag_ele.fadeOut(400);
        }
        def.is_add_dom=false;
        isTourist();
    }        

    
    // 没有数据
    function noDataFunc(data){
        $(".js-error-section").show();
        $(".js-error-section h4").html("对不起，网络开小差了，请<a href='javascript:location.reload();'>立即刷新</a>重试或稍后打开");
        $('.js-references-list-box').hide();
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

        $('.js-references-list-box>ul>li').each(function(){
            if($(this).hasClass('js-placeholder-item')){
                $(this).show();
            }else{
                $(this).remove();
            }
        });
        $('html,body').animate({'scrollTop':$('#anchor').offset().top+'px'},0);
    };

    function setAjaxUrl(str,page_num){                                                                          //拼接ajax url
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