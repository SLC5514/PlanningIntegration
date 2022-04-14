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
        show_page_size:42,                                                                      //当前显示的一页数量
        
        is_add_dom:false,
        
        now_page:1,                                                                             //后端页
        now_page_size:21,                                                                       //后端显示的一页数量
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
        ow:document.documentElement.clientWidth || document.body.clientWidth,
        oh:document.documentElement.clientHeight || document.body.clientHeight,
        
        href_obj:pop_fashion_global.fn.getLocationParameter() || {}
    }

   

    def.show_page=def.href_obj.page || 1;
    def.href_obj.key?def.key=def.href_obj.key:'';

    pop_list_page_obj.page=def.show_page;
    pop_list_page_obj.page_size=def.show_page_size;

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
        var tag=$(".js-book-list-box>ul");
        var P_Collect = data.info.powers['P_Collect'];
        def.total_page=Math.ceil(data.info.totalCount/def.now_page_size/def.get_times_max);

        var _html="";
        for(var i=0,len=arr.length;i<len;i++){
            var cover=arr[i]["list"]["cover"]?arr[i]["list"]["cover"]:"";
            var title=arr[i]["list"]["title"]?arr[i]["list"]["title"]:"";
            var sBuyingLinks=arr[i]["list"]["sBuyingLinks"]?arr[i]["list"]["sBuyingLinks"]:"";
            var iPreviewMode=arr[i]["list"]["iPreviewMode"]?arr[i]["list"]["iPreviewMode"]:2;
            var pop_id=arr[i]["list"]["pop_id"]?arr[i]["list"]["pop_id"]:"";
            var bookname=arr[i]["list"]["bookname"]?arr[i]["list"]["bookname"]:"";                
            var id=arr[i]["list"]["id"]?arr[i]["list"]["id"]:"";
            var create_time=arr[i]["list"]["create_time"]?arr[i]["list"]["create_time"]:"";
            create_time=create_time.substr(0,10);
            var vectorIcon=arr[i]["list"]["vectorIcon"]?arr[i]["list"]["vectorIcon"]:"";
            var tableName= arr[i]["tableName"]?arr[i]["tableName"]:"";
            var columnId= arr[i]["columnId"]?arr[i]["columnId"]:"";
            var total=arr[i]["total"]?arr[i]["total"]:"";
            var offset=arr[i]["offset"]?arr[i]["offset"]:0;
            var video_have = arr[i]["video_have"]?arr[i]["video_have"]:false;
            _html+='<li>';
            _html+='<div class="pic-box">';
            _html+='<a href="javascript:void(0)" title="查看详情">';
            if(i>6){
                _html+='<img alt="'+title+'" itemprop="thumbnail" src="https://imgf2.pop-fashion.com/global/images/loading/book.gif" data-original="'+cover+'">'
            }else{
                _html+='<img alt="'+title+'" itemprop="thumbnail" src="'+cover+'">';
            }
            _html+='</a>';
            if(video_have){
                _html+='<span class="video-icon"></span>';
            }
            _html+='<div class="hover-link">';
            if(sBuyingLinks){
                _html+='<div class="buy-link">';
            }else{
                _html+='<div>'
            }
            if(iPreviewMode == 1){
                if(P_UserType==5){
                    _html+='<a class="yl js-general-user-status" href="javascript:void(0);" title="免费预览"><i></i>免费预览</a>';
                }else{
                    _html+='<a class="yl" target="_blank" href="/books/seclist/id_'+id+'-t_'+tableName+'-yl_'+iPreviewMode+'-col_'+columnId+'/" data-yl="'+iPreviewMode+'" data-url="/books/seclist/" data-col="'+columnId+'" data-t="'+tableName+'" data-bookname="'+bookname+'" data-popid="'+pop_id+'" data-id="'+id+'" data-total="'+total+'" data-index="'+offset+'" title="免费预览"><i></i>免费预览</a>';
                }
            }else{                    
                if(P_UserType==5){
                    _html+='<a class="wz js-general-user-status" href="javascript:void(0);" title="完整预览"><i></i>完整预览</a>';
                }else{
                    _html+='<a itemprop="full browser" target="_blank" class="wz" href="/books/seclist/id_'+id+'-t_'+tableName+'-yl_'+iPreviewMode+'-col_'+columnId+'/" data-yl="'+iPreviewMode+'" data-url="/books/seclist/" data-col="'+columnId+'" data-t="'+tableName+'" data-bookname="'+bookname+'" data-popid="'+pop_id+'" data-id="'+id+'" data-total="'+total+'" data-index="'+offset+'" title="完整预览"><i></i>完整浏览</a>';
                }
            }
            if(sBuyingLinks){
                _html+='<a href="'+sBuyingLinks+'" class="buy" target="_blank" title="购买"><i></i>购买</a>';
            }
            _html+='</div>';
            _html+='<p class="update-time"><img src="https://imgf2.pop-fashion.com/global/images/lists/books-list/time.png" alt="icon">'+create_time+'</p>'
            _html+='</div>';
            if(vectorIcon){
                _html+='<i class="s_file_icon"></i>';
            }
            _html+='</div>';
            _html+='<div class="image-title">';
            _html+='<p itemprop="name" class="title" title="'+title+'">'+title+'</p>'
            _html+='</div>';
            _html+='</li>';                
        }

        if(_html!=''){

            if(def.get_times==1){
                $('.js-book-list-box .js-placeholder-item').hide();
                tag.append(_html);
                $('.js-total-info-section').find('p>a').text(data.info.totalCount || '');
                $(".js-page-control span").eq(0).text(def.show_page);
                $(".js-page-control span").eq(1).text(def.total_page);
            }else{
                tag.append(_html);
            }
            $('.js-book-list-box img').lazyload({effect: "show"});
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
                    $('.js-book-list-box').hide();
                    $('.js-page-section').hide();
                }
            }else{
                if(tag.children('li:not(.js-placeholder-item)').length<=0){
                    $('.js-error-section').show();
                    $('.js-book-list-box').hide();
                }
                $('.js-page-section').hide();
            }
            
            def.get_times=def.get_times_max+1;
            def.tag_ele.fadeOut(400);
        }
        def.is_add_dom=false;
    }
    
    // 没有数据
    function noDataFunc(data){
        $(".js-error-section").show();
        $(".js-error-section h4").html("对不起，网络开小差了，请<a href='javascript:location.reload();'>立即刷新</a>重试或稍后打开");
        $('.js-book-list-box').hide();
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


    $(window).on('scroll',setScrollFunc);

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

        $('.js-book-list-box>ul>li').each(function(){
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