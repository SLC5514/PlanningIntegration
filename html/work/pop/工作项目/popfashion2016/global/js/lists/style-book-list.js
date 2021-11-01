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
        show_page_size:20,                                                                      //当前显示的一页数量
        
        is_add_dom:false,
        
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
    var body_width=$(".style-width").width();
    var style_length=$(".js-style-selection-box").attr('data-report-count');

    def.show_page=def.href_obj.page-0 || 1;
    def.href_obj.key?def.key=def.href_obj.key:'';
    // 第2页不显示推荐报告
    if(def.show_page>1){
        $('.js-style-selection-box').hide();
    }

    hideMore();
    getData();

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
        
        $(".js-page-control span").eq(0).text(def.show_page);

        
        var get_obj={'pageSize':def.show_page_size};
        if(def.key!=undefined){
            get_obj.key=def.key;
        }
        $.ajax({
            type:"post",
            async: false,
            url:def.url+setAjaxUrl(def.param,def.show_page)+'/',
            data:get_obj,
            dataType:'json',
            success:function(data){            
                setDom(data)
            },
            error:noDataFunc
        })
    };
    // 获取数据
    function setDom(data){
        var arr=data.data||[];
        var tag=$(".js-style-lists-box>ul");
        def.total_page=Math.ceil(data.info.totalCount/def.show_page_size);  

        var _html='';            
        for(var i=0,len=arr.length;i<len;i++){
            var link=arr[i]["link"]?arr[i]["link"]:"";
            var cover=arr[i]["cover"]?arr[i]["cover"]:"";
            // var title=arr[i]["title"]?arr[i]["title"]:"";
            var sSeason=arr[i]["sSeason"]?arr[i]["sSeason"]:"";
            var sMarket=arr[i]["sMarket"]?arr[i]["sMarket"]:"";
            var sExhibition=arr[i]["sExhibition"]?arr[i]["sExhibition"]:"";
            var sStar=arr[i]["sStar"]?arr[i]["sStar"]:"";
            var sRegion=arr[i]["sRegion"]?arr[i]["sRegion"]:"";
            var sBrand=arr[i]["sBrand"]?arr[i]["sBrand"]:"";
            // var cnBrand=arr[i]["cnBrand"]?arr[i]["cnBrand"]:"";
            // var enBrand=arr[i]["enBrand"]?arr[i]["enBrand"]:"";
            var create_time=arr[i]["create_time"]?arr[i]["create_time"]:"";
            create_time=create_time.substr(0,10);
            var isNew =arr[i]["isNew"]?arr[i]["isNew"]:null;

            _html+='<li>';
            _html+='<a href="'+link+'" target="_blank" ondragstart="return false;" oncontextmenu="return false;" title="查看详情">';                        
            _html+='<div class="img-div">';
            if(i<5){                            
                _html+='<img src="'+cover+'" alt="pic">';                       
                // _html+='<img class="rotate-img" src="'+cover+'" alt="pic">';
            }else{
                _html+='<img src="'+'https://imgf2.pop-fashion.com/global/images/loading/style165x.jpg'+'" data-original="'+cover+'" alt="pic">';
                // _html+='<img class="rotate-img" src="'+'https://imgf2.pop-fashion.com/global/images/loading/style135xMR.png'+'" data-original="'+cover+'" alt="pic">';
            } 
            _html+='</div>';
            _html+='<div class="style-book-text">';
            _html+='<div class="style-book-position">';
            _html+='<p class="season">'+sSeason+'</p>';
            if (def.col == 54 && def.param.indexOf('ds_1') != -1) {
                _html+='<p class="sname">'+sMarket+'</p>';
            } else if (def.col == 54 && def.param.indexOf('ds_3') != -1) {
                _html+='<p class="sname">'+sExhibition+'</p>';
            } else if (def.col == 57) {
                _html+='<p class="sname">'+sStar+'</p>';
            } else if (def.col == 56) {
                _html+='<p class="sname">'+sRegion+'</p>';
            } else {
                _html+='<p class="sname skeep">'+sBrand+'</p>';
            }
            _html+='<p itemprop="release time" class="Dtime"><em></em>'+create_time+'</p>';
            _html+='</div>';
            _html+='</div>';
            _html+='<div class="lab"><i></i>';
            if(isNew == true){
                _html+='<span></span>';
            }
            _html+='</div>';
            _html+='</a>';
            _html+='</li>';
        }
        
        if(_html!=''){
            $('.js-style-lists-box .js-placeholder-item').hide();
            tag.append(_html);
            $('.js-total-info-section').find('p>a').text(data.info.totalCount || '');
            $(".js-page-control span").eq(0).text(def.show_page);
            $(".js-page-control span").eq(1).text(def.total_page);
            $('.js-style-lists-box img').lazyload({effect: "show"});
            // var now_scroll=$(window).scrollTop();
            // $('html,body').animate({'scrollTop':(now_scroll+1)+'px'},30);

            

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
                $('.js-page-section').hide();
                $(".js-error-section").show();
                $('.js-style-lists-box').hide();
            }
        }
        def.is_add_dom=false;
        def.tag_ele.fadeOut(400);
        
        // var isBV = $('.js-style-images-contain>.contentW').width() == 1200 ? 0 : 1;
        // $(".js-style-lists-box>ul>li").each(function(i, v) {
        //     if (!$(v).hasClass('js-placeholder-item')) {
        //         var bEn = $(v).find('.style-book-position>p.brand-en');
        //         var bZh = $(v).find('.style-book-position>p.brand-zh');
        //         var textEn = bEn.text();
        //         var textZh = bZh.text();
        //         if (isBV) {
        //             bEn.text(general.fn.cutByWidth(textEn, 275, 20));
        //             bZh.text(general.fn.cutByWidth(textZh, 270, 14));
        //         } else {
        //             bEn.text(general.fn.cutByWidth(textEn, 235, 16));
        //             bZh.text(general.fn.cutByWidth(textZh, 205, 12));
        //         }
        //     }
        // })
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
    // $(".js-style-images-contain").on('mouseenter mouseleave','.style-book-box li',function(e){
    //     var label_position=$(this).find(".style-book-position")
    //     var cur_height=label_position.css('height');
    //     label_position.css('height','auto');
    //     var animate_height=label_position.css('height');
    //     label_position.css('height',cur_height);
    //     if(e.type=="mouseenter"){
    //         label_position.animate({height:animate_height},100);
    //     }else{
    //         label_position.animate({height:'40px'},50);
    //     }
    // });


    // 没有数据
    function noDataFunc(data){
        $(".js-error-section").show();
        $(".js-error-section h4").html("对不起，网络开小差了，请<a href='javascript:location.reload();'>立即刷新</a>重试或稍后打开");
        $('.js-style-lists-box').hide();
        def.is_add_dom=false;
        // msg.msg({'txt':'网络似乎出现了问题。请稍后再试！点击 <a href="javascript:window.location.reload(true);" title="刷新" style="color:#d8b056;font-weight:bold;cursor:pointer;">刷新</a>。'})
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
                if(is_first!=true){
                    resetPage();
                }
            }
        });
    };



    function resetPage(){                                                                                       //新页重置dom
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