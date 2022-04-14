/*
	#author		fanglinna
	#date 		2019/07/31
*/


require(["jquery","general"], function(jquery,general){
    $(function(){
        var def={
            show_page:1,                                                                            //当前显示的页        
            is_add_dom:false,
            now_page_size:20,                                                                       //后端显示的一页数量        
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
            general.fn.throttle(function(){
                def.ow=document.documentElement.clientWidth || document.body.clientWidth;
                def.oh=document.documentElement.clientHeight || document.body.clientHeight;
            },this,[]);
        });

        getData();   
    
        // 点击查看更多
        $(".js-scroll-look-more").on("click", function() {
            getData();
        });
            
        // 加载数据
        var request_id = "";
        function getData(){
            def.is_add_dom=true;   
            $.ajax({
                url:def.url,
                async:false,
                type:"POST",
                dataType:"json",
                data:{col:def.dataCol},
                success:function(data){
                    $('.js-loading-div,.js-bg-div').fadeOut();     //加载去除
                    def.is_add_dom=false;
                    var arr=data.data||[];
                    if(!data.data){
                        $(".js-scroll-load").hide();
                        return false;
                    }
                    request_id = data.info.request_id || "";
                    if(arr.length <= def.now_page_size){
                        $(".js-scroll-load").hide();
                    }                    
                    for(var i=0;i<arr.length;i+=def.now_page_size){
                        def.totalArr.push(arr.slice(i,i+def.now_page_size));
                    }                     
                    loadData(def.totalArr[def.totalArrIndex]);
                },
                error:function(){
                    $('.js-loading-div,.js-bg-div').fadeOut();     //加载去除
                }
            })
        }
        
        $(window).on('scroll',setScrollFunc);
        function setScrollFunc(){
            // 函数节流
            general.fn.throttle(getMoreData,this,[]);
        }
        
        function getMoreData(){               
            if(def.is_add_dom===false){
                var now_srcolltop=$(window).scrollTop();
                var tag_scrolltop=def.tag_ele.offset().top;
                var oh = def.oh;
                if (now_srcolltop+(2*oh) > tag_scrolltop && !def.is_loading){
                    def.is_loading = true;
                    def.totalArrIndex++;
                    def.get_time++; 
                    if(def.totalArrIndex % 3 === 0){                        
                        if(def.totalArrIndex % 9 === 0){
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
            var tag=$(".js-datagrand-box").find('ul');
            var _html="";
            if(totalArr && totalArr.length){
                for(var i=0,len=totalArr.length;i<len;i++){
                    var cover=totalArr[i]["cover"]?totalArr[i]["cover"]:"";
                    var tableName= totalArr[i]["t"]?totalArr[i]["t"]:"";
                    var id=totalArr[i]["id"]?totalArr[i]["id"]:"";
                    _html+='<li>';              
                    _html +='<div itemscope class="pic_box">';            
                    _html+='<a itemprop="url" data-t="'+tableName+'" data-request_id="'+request_id+'" target="_blank" data-id="'+id+'" ondragstart="return false;" oncontextmenu="return false;" title="查看详情">';
                    _html+='<img itemprop="thumbnail" src="'+cover+'">';        
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
                def.is_loading= false;
            }else{
                // 没有数据
            }
        }

        // 上传用户行为
        $(".js-list-parent").on("click", ".pic_box>a", function() {
            var this_t = $(this).data("t");
            var this_id = $(this).data("id");
            var request_id = $(this).data("request_id");
            var action_data = {"t":this_t,"id":this_id,"request_id":request_id,"action_type":"rec_click",userid:userId,scene_type:'personal_cloud',site:1};
            $.ajax({
                type: "get",
                dataType: "jsonp",
                url: '//api.pop136.com/internal/datagrand.php?'+Math.random(),
                data:action_data,
                success:function(){}
            });
            var frame_url = '/patternlibrary/detail/?id='+this_id+'&t='+this_t+'&request_id='+request_id+'&scene_type=personal_cloud&origin_type=5';
			$('.js-detail-frame').find('iframe').attr('src',frame_url);
			$('.js-detail-frame').fadeIn();
			$('body').addClass('over-hidden');
        });
    
        $(".js-list-parent").on("click", ".js-dislike-img", function() {
            var this_t = $(this).siblings('a').data("t");
            var this_id = $(this).siblings('a').data("id");
            var request_id = $(this).siblings('a').data("request_id");
            var action_data = {"t":this_t,"id":this_id,"request_id":request_id,"action_type":"dislike",userid:userId,scene_type:'personal_cloud',site:1};
            var _this_html = $(this).parents("li");
            $.ajax({
                type: "get",
                dataType: "jsonp",
                url: '//api.pop136.com/internal/datagrand.php?'+Math.random(),
                data:action_data,
                success:function(){
                    _this_html.remove();
                }
            })
        });
    });
});

