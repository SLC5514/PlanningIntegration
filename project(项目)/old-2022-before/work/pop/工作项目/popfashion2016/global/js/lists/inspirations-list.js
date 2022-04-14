/*
	#author		fanglinna
	#date 		2018/01/15
    #porpuse    款式列表页
*/

$(function(){
    var def={
        limit_page:$('#link').attr('data-limitnum')-0,
        pages:1,                                                                                //当前显示的总页数
        
        
        is_add_dom:false,
        
        now_page:1,                                                                             //后端页
        now_page_size:60,                                                                       //后端显示的一页数量
        total_page:1,                                                                           //总页数
        is_set_page:false,                                                                      //是否设置了分页
        col:$("#link").data('col'),                                                             //栏目id
        url:$("#link").data('url') || '/',                                                      //栏目路径
        param:$("#link").data('param') || '',                                                   //栏目参数
        ajax_url:'',
        tag_ele:$('.js-scroll-load'),                                                           //加载dom
        ow:document.documentElement.clientWidth || document.body.clientWidth,
        oh:document.documentElement.clientHeight || document.body.clientHeight,
        
        href_obj:pop_fashion_global.fn.getLocationParameter() || {}
    }


    def.now_page=def.href_obj.page || 1;
    def.href_obj.key?def.key=def.href_obj.key:'';


    getData();

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
        if(def.now_page!=1){
            def.now_page--;
            pop_list_page_obj.page=def.now_page;
            
            def.is_set_page=false;
            setHistoryUrl(def.now_page);

            resetPage();
        }

    });

    $('.js-page-control .js-next-page-btn').on('click',function(){
        if(def.is_add_dom==true){return;}
        if(def.now_page<def.total_page){
            def.now_page++;
            pop_list_page_obj.page=def.now_page;
            
            def.is_set_page=false;
            setHistoryUrl(def.now_page);
            resetPage();
        }
    });

    // 加载数据
    function getData(){
        def.is_add_dom=true;
        
        $(".js-page-control span").eq(0).text(def.now_page);

        
        var get_obj={'pageSize':def.now_page_size};
        if(def.key!=undefined){
            get_obj.key=def.key;
        }
        pop_fashion_global.fn.subAjax({
            url:def.url+setAjaxUrl(def.param,def.now_page)+'/',
            data:get_obj,
            ctp:'application/x-www-form-urlencoded; charset=UTF-8',
            successFunc:function(data){            
                setDom(data)
            },
            errorFunc:noDataFunc
        })
    }

    function setDom(data){
        var arr=data.data||[];
        var tag=$(".js-inspiration-list-box>ul");
        def.total_page=Math.ceil(data.info.totalCount/def.now_page_size);

        var _html="";
        for(var i=0,len=arr.length;i<len;i++){
            var cover=arr[i]["list"]["cover"]?arr[i]["list"]["cover"]:"";
            var title=arr[i]["list"]["title"]?arr[i]["list"]["title"]:"";            
            var id=arr[i]["list"]["id"]?arr[i]["list"]["id"]:"";
            var view_count=arr[i]["list"]["view_count"]?arr[i]["list"]["view_count"]:"";
            var abstract=arr[i]["list"]["abstract"]?arr[i]["list"]["abstract"]:"";
            var inspirationTypeName=arr[i]["list"]["inspirationTypeName"]?arr[i]["list"]["inspirationTypeName"]:"";
            var create_time=arr[i]["list"]["create_time"]?arr[i]["list"]["create_time"]:"";
            create_time=create_time.substr(0,10);
            var vectorIcon=arr[i]["list"]["vectorIcon"]?arr[i]["list"]["vectorIcon"]:"";
            var tableName= arr[i]["tableName"]?arr[i]["tableName"]:"";
            var columnId= arr[i]["columnId"]?arr[i]["columnId"]:"";
            var total=arr[i]["total"]?arr[i]["total"]:"";
            var offset=arr[i]["offset"]?arr[i]["offset"]:0;
            if(def.col == 90){
                _html+='<li itemscope>';
                _html+='<a itemprop="url" class="img_bg" href="/details/report/t_'+tableName+'-id_'+id+'-col_'+columnId+'/" target="_blank" data-t="'+tableName+'" data-id="'+id+'" data-total="'+total+'" data-index="'+offset+'" data-col="'+columnId+'" title="查看详情">'
                // if(cover.length >30){                        
                //     _html+='<img alt="'+title+'" itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/loading/inspiration.gif" data-original="'+cover+'">';
                // }else{
                //     _html+='<img itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/loading/inspritionMR.jpg" alt="'+title+'">';
                // }
                _html+='<img alt="'+title+'" itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/loading/inspiration.gif" data-original="'+cover+'">';
                _html+='<p itemscope class="sm_icon">';
                _html+='<span itemprop="release time"><img itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/zzjd_2.png" alt="icon"/>'+create_time+'</span>';
                _html+='<strong><img itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/zzjd_3.png" alt="icon"/>浏览（'+view_count+'）</strong>';
                _html+='</p>';
                _html+='</a>';
                _html+='<div itemscope class="des_title">';
                _html+='<p itemprop="name" class="title"  title="'+title+'">';
                _html+='<a itemprop="url" href="/details/report/t_'+tableName+'-id_'+id+'-col_'+columnId+'/" target="_blank" data-t="'+tableName+'" data-id="'+id+'" data-total="'+total+'" data-index="'+offset+'" data-col="'+columnId+'" title="查看详情">'+title+'</a>';
                _html+='</p>';
                _html+='<p itemprop="description" class="sm_cont js-img-description">'+pop_fashion_global.fn.cutByWidth(abstract,950,12)+'</p>';
                _html+='</div>';
                if(inspirationTypeName){
                    _html+='<span itemscope class="Thover"></span>';
                    _html+='<span itemprop="art" class="Ttxt">'+inspirationTypeName+'</span>';
                }
                _html+='</li>';
            }      
        }


        if(_html!=''){

            _html = $(_html);        
            tag.html(_html).masonry('appended', _html, true);
            $('.js-total-info-section').find('p>a').text(data.info.totalCount || '');
            $(".js-page-control span").eq(0).text(def.now_page);
            $(".js-page-control span").eq(1).text(def.total_page);

            $('.js-inspiration-list-box img').lazyload({effect: "show",load:f_BlocksIt});
            var now_scroll=$(window).scrollTop();
            $('html,body').animate({'scrollTop':(now_scroll+1)+'px'},30);

            

            if(def.total_page>1){
                if(def.is_set_page==false){
                    def.is_set_page=true;
                    setPage(def.total_page);   
                }
                $('.js-page-section').show();
            }else{
                $('.js-page-section').hide();
            }
        }else{
            // 没有数据
            if(def.now_page>1){
                if(def.is_set_page==true){
                    $('.js-page-section').show();
                }else{
                    $('.js-page-section').hide();
                }
                if(tag.children('li:not(.js-placeholder-item)').length<=0){
                    $('.js-error-section').show();
                    $('.js-inspiration-list-box').hide();
                    $('.js-page-section').hide();
                }
            }else{
                $('.js-page-section').hide();
                $(".js-error-section").show();
                $('.js-inspiration-list-box').hide();
            }
            
        }
        def.is_add_dom=false;
        def.tag_ele.fadeOut(400);
    }        

    // 没有数据
    function noDataFunc(data){
        $(".js-error-section").show();
        $(".js-error-section h4").html("对不起，网络开小差了，请<a href='javascript:location.reload();'>立即刷新</a>重试或稍后打开");
        $('.js-inspiration-list-box').hide();
        def.is_add_dom=false;
    };
    // 设置分页
    function setPage(total){
        new laypage({
            cont:$('.js-page-section')
            ,pages:total
            ,curr:def.now_page
            ,groups:5
            ,search:'page'
            ,skip:true
            ,first:1
            ,last:total
            ,prev:'<em><</em>上一页'
            ,next:'下一页<em>></em>'
            ,jump:function(obj,is_first){
                def.now_page=obj.curr;
                pop_list_page_obj.page=def.now_page;
                if(is_first!=true){
                    resetPage();
                }
            }
        });
    };


    


    function resetPage(){                                                                                       //新页重置dom
        var is_bind=false;
        $(".js-page-control span").eq(0).text(def.now_page);
        
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