/*
	#author		fanglinna
	#date 		2018/01/15
    #porpuse    款式列表页
*/

$(function(){
    var def={
        show_page:1,                                                                            //当前显示的页        
        is_add_dom:false,
        now_page_size:30,                                                                       //后端显示的一页数量        
        url:window.location.href,                                                               //栏目路径
        tag_ele:$('.js-scroll-load'),                                                           //加载dom 
        ow:document.documentElement.clientWidth || document.body.clientWidth,
        oh:document.documentElement.clientHeight || document.body.clientHeight,
        get_time:0,                                                                               //第一次加载
        totalArrIndex:0,                                                                          //加载总数组哪一项
        totalArr:[],
        is_show:false,                                                                            //款式子栏目是否显示
        is_subColumn:false,                                                                        //是否选中款式子栏目
        dataCol:[],                                                                                      //选中栏目参数
        load_data:$(".js-load-data"),
        is_loading:false
            
    }
  
    // 窗口变化监听
    $(window).on('resize',function(){
        // 函数节流
        pop_fashion_global.fn.throttle(function(){
            def.ow=document.documentElement.clientWidth || document.body.clientWidth;
            def.oh=document.documentElement.clientHeight || document.body.clientHeight;
        },this,[]);
    });

    // 猜你喜欢
    $(".js-style-subcolumn-click").on("click", function() {
        if(!def.is_show){
            $(".js-style-subcolumn-list").css("display","inline-block");
            $(this).text("<");
            $(".js-style-load-data").text("全部款式");
            def.is_show = true;
        }else{
            $(".js-style-subcolumn-list").css("display","none");
            $(this).text(">");
            $(".js-style-load-data").text("款式");
            def.is_show = false;
        }
        
    });


    // 通过url获取选中的col    
    var url_col =  pop_fashion_global.fn.getLocationParameter().col || "";
    if(url_col != ""){
        $('.js-load-data[data-col="'+url_col+'"]').addClass("on");
        def.dataCol.push(url_col);
    }
    
    // 切换栏目存入数组
    function colFuncData(obj){
        var col = obj.attr("data-col");
        var index = def.load_data.index(obj);
        var index_col = def.dataCol.indexOf(col);
        if(obj.hasClass("on")){            
            obj.removeClass("on");
            def.dataCol.splice(index_col,1);
        }else{
            obj.addClass("on");
            def.dataCol.push(col);
            if(index == 1){
                $(".js-style-subcolumn-list>li").each(function(){
                    $(this).removeClass("on");
                    var col = $(this).attr("data-col");
                    var index_sub = def.dataCol.indexOf(col);
                    if(index_sub > -1){
                        def.dataCol.splice(index_sub,1);
                    }
                });      
            }else if(index > 1){
                var index_all_style = def.dataCol.indexOf("4");
                if(index_all_style > -1){
                    def.dataCol.splice(index_all_style,1);
                    $(".js-style-load-data").removeClass("on");                
                }     
            }
        }        
    }

    def.load_data.on("click", function() {
        if(url_col != ""){
            def.url = window.location.pathname;
            history.pushState(null,null,def.url);
        }
        colFuncData($(this));
        initData()
        getData();
    });
    
    // 分割数据    
    if(recommend_usertype == 5){
        alert("请先登录！");
        $(".js-scroll-load").hide();
        window.location.href="/member/pagelogin/";
        return false;
    }else{
        getData();
    }

    // 初始化
    function initData(){
        def.totalArr = [];
        def.totalArrIndex = 0;
        def.get_time = 0;
    }

    // 点击查看更多
    $(".js-scroll-look-more").on("click", function() {
        getData();
    });
        
    // 加载数据
    function getData(){
        def.is_add_dom=true;   
        $.ajax({
            url:def.url,
            type:"POST",
            dataType:"json",
            data:{col:def.dataCol},
            success:function(data){
                def.is_add_dom=false;
                var arr=data.data||[];
                if(!data.data){
                    $(".js-scroll-load").hide();
                    return false;
                }
                if(arr.length <= def.now_page_size){
                    $(".js-scroll-load").hide();
                }        
                // 请求成功后渲染第一次DOM
                for(var i=0;i<arr.length;i+=def.now_page_size){
                    def.totalArr.push(arr.slice(i,i+def.now_page_size));
                }                            
                loadData(def.totalArr[def.totalArrIndex]);
            },
            error:noDataFunc
        })
    }
    
    $(window).on('scroll',setScrollFunc);
    function setScrollFunc(){
        // 函数节流
        pop_fashion_global.fn.throttle(getMoreData,this,[]);
    }
    
    function getMoreData(){               
        if(def.is_add_dom===false){
            var now_srcolltop=$(window).scrollTop();
            var tag_scrolltop=def.tag_ele.offset().top;
            var oh = def.oh;
            if (now_srcolltop+(1.2*oh) > tag_scrolltop && !def.is_loading){
                def.is_loading = true;
                def.totalArrIndex++;
                def.get_time++; 
                if(def.totalArrIndex % 2 === 0){
                    if(recommend_usertype == 4){
                        $(".js-opening-authority").show();
                        $(".js-scroll-load").hide();
                        return false;
                    }
                    if(def.totalArrIndex % 6 === 0){
                        $(".js-scroll-look-more").show();
                        $(".js-scroll-load").hide();
                        return false;
                    }else{
                        $(".js-scroll-look-more").hide();
                        $(".js-scroll-load").show();
                    }                                        
                    getData();
                }else{                    
                    loadData(def.totalArr[def.totalArrIndex]); 
                }                           
            }            
        }
    };

    // 加载DOM
    function loadData(totalArr){           
        var tag=$(".js-patterns-lists-box").find('ul');
        var _html="";
        if(totalArr && totalArr.length){
            for(var i=0,len=totalArr.length;i<len;i++){
                var cover=totalArr[i]["cover"]?totalArr[i]["cover"]:"";
                var title=totalArr[i]["title"]?totalArr[i]["title"]:"";
                var tableName= totalArr[i]["t"]?totalArr[i]["t"]:"";
                var id=totalArr[i]["id"]?totalArr[i]["id"]:"";
                var columnId=totalArr[i]["columnId"]?totalArr[i]["columnId"]:"";
                var request_id = totalArr[i]["request_id"]?totalArr[i]["request_id"]:0;
                var link = totalArr[i]["link"]?totalArr[i]["link"]:"";
                _html+='<li>';              
                _html +='<div itemscope class="pic_box">';            
                _html+='<a itemprop="url" href="'+link+'" data-t="'+tableName+'" data-request_id="'+request_id+'" target="_blank" data-col="'+columnId+'" data-id="'+id+'" ondragstart="return false;" oncontextmenu="return false;" title="查看详情">';
                _html+='<img alt="'+title+'" itemprop="thumbnail" src="'+cover+'">';        
                _html+='</a>';
                _html+='<span class="js-dislike-img">不喜欢</span>'
                _html+='</div>';
                _html+='</li>';
            }
        }        
        if(_html!=''){            
            if(def.get_time===0){
                tag.html(_html);
            } else {
                tag.append(_html);
            }
            tag.find("img").load(function() {
                $(".masonry").masonry("reload");
            })
            def.is_loading= false;
        }else{
            // 没有数据
        }
    }    

    // 没有数据
    function noDataFunc(data){
        $(".js-error-section").show();
        $(".js-error-section h4").html("对不起，网络开小差了，请<a href='javascript:location.reload();'>立即刷新</a>重试或稍后打开");
        $('.js-patterns-lists-box').hide();
        def.is_add_dom=false;
        def.get_times++;
    };


    // 上传用户行为
    $(".js-list-parent").on("click", ".pic_box>a", function() {
        var this_t = $(this).data("t");
        var this_id = $(this).data("id");
        var request_id = $(this).data("request_id");
        var action_data = {t:this_t,id:this_id,request_id:request_id,action_type:"rec_click",userid:P_AccountId,scene_type:'personal_picture_clothing'};
        $.ajax({
			type: "get",
			dataType: "jsonp",
			url: 'https://api.pop136.com/internal/datagrand.php',
            data:action_data,
			success:function(){}
		})
    });

    $(".js-list-parent").on("click", ".js-dislike-img", function() {
        var this_t = $(this).siblings('a').data("t");
        var this_id = $(this).siblings('a').data("id");
        var request_id = $(this).siblings('a').data("request_id");
        var action_data = {t:this_t,id:this_id,request_id:request_id,action_type:"dislike",userid:P_AccountId,scene_type:'personal_picture_clothing'};
        var _this_html = $(this).parents("li");
        $.ajax({
			type: "get",
			dataType: "jsonp",
			url: 'https://api.pop136.com/internal/datagrand.php',
            data:action_data,
			success:function(){
                _this_html.remove();
                $(".masonry").masonry("reload");
            }
		})
    });
    // 瀑布流布局
    f_BlocksIt();            
    function f_BlocksIt(){
        $contain = $("body").find(".patterns-recommend"); 
        $contain.imagesLoaded(function(){
            $contain.masonry({
                gutterWidth: 0,
                itemSelector: 'li',
                isAnimated: false
            });
        });
    }
    $(window).resize(function() {
        f_BlocksIt();
    });
});