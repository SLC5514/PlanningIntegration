require.config({
    paths:{
        "Swiper":["lib/swiper"]
    },
    shim:{
        "Swiper":{
            deps:["jquery"],
            exports:"Swiper"
        }
    }
})

require(["jquery","msg","general","Swiper"],function(jquery,msg,general,Swiper){
    $(function(){ 
        var def ={
            _index:0,             //当前选中的
            layer_opacity:$(".js-bg-layer"),
            layer_top:$(".layer-top"),
            url:'https://api.pop-fashion.com',
            page:1,
            is_loading:true,
            has_down_power:0,                 //没有下载权限
            token:$(".js-token-hidden").data("token") || ""               

        };

        // 获取链接id
        var input_hid = $(".js-token-hidden");
        var filter_id = input_hid.data("filter_id");
        var hash = input_hid.data("hash");
        // 获取列表
        ajaxDataFunc();
        function ajaxDataFunc(){            
            $.ajax({
                url:def.url+'/app/top/',
                dataType:'jsonp',
                type:'get',
                data:{data_type:'jsonp',filter_id:filter_id,hash:hash,page:def.page},
                success:function(data){
                    var ndata= data.data || [];
                    if(data.code == 0) {
                        listDataFunc(ndata);
                    }else if(data.code == 404) {
                        $(".js-load-img").hide();
                        $(".js-null-data-box").show().find("img").hide();
                        def.is_loading == true;
                        return false;                        
                    }else{
                        alert(data.msg);
                        def.is_loading == true;
                    }
                }
            })
        }        

        // 列表数据DOM
        function listDataFunc(ndata){            
            var _html="";
            var time_range = ndata.time_range || "";
            var time_range_name = ndata.time_range_name || "";
            var title = ndata.title || "";
            $(".js-h2-title>span").html(title);
            $(".js-top-time").html(time_range);
            $(".js-Lately-month").html(time_range_name);
            if(ndata.list && ndata.list.length > 0){
                for(var i=0;i<ndata.list.length;i++){                
                    var cover = ndata.list[i].cover || "";                        
                    var pop_id = ndata.list[i].pop_id || "";                       
                    _html += '<li>';
                    _html += '<a data-pop_id="'+pop_id+'">';
                    _html += '<img src="'+cover+'">';
                    _html += '</a></li>';
                }                
                $(".js-top-lists").append(_html);
                if(ndata.list && ndata.list.length < 12){
                    $(".js-load-img").hide();
                    def.is_loading == true;
                    return false;
                }                                
                def.is_loading = false;
            }else if(ndata.list && ndata.list.length == 0 && $(".js-top-lists>li").length == 0){
                $(".js-load-img").hide();
                $(".js-null-data-box").show();
                return false;
            }                                 
        }
        
        // 滚动加载
        $(window).on("scroll", setScrollFunc);

        // 函数节流
        function setScrollFunc(){
            general.fn.throttle(getMoreData,this,[]);
        }

        function getMoreData() {
            var scroll_top = $(window).scrollTop();
            var scroll_hei = $(window).height();
            var tag_hei = $(".top-footer").offset().top;
            if(scroll_top + scroll_hei >= tag_hei && !def.is_loading){
                def.is_loading = true;
                def.page ++;
                ajaxDataFunc();
            }
        }

        // 初始化弹窗
        function initLayer() {
            $(".js-layer-top>p").html("");
            $(".js-layer-top>.look-tips").html("");
            $(".user-tourist").hide();
            $(".user-general-btn").hide();
            $(".user-download-btn").hide();
            $(".js-total-img-num").html("");
            $(".js-cur-page-num").html("1");
            def._index = 0;
        }
        var mySwiper = new Swiper ('.swiper-container', {
            initialSlide :0,
            updateOnImagesReady : true,
            on: {
                slideChangeTransitionEnd: function(){
                    $(".js-cur-page-num").html(this.activeIndex+1);
                }
            }
        });
        // 点击列表--弹层     
        $(".js-top-lists").on("click" ,"li", function(){
            initLayer()      
            getData($(this));
        }); 
        // 获取款式详情页数据
        function getData(ele){            
            var ele = ele ||"";
            var pop_id = ele.find("a").data("pop_id");
            $.ajax({
                type:'GET',
                url:def.url+'/app/top/detail/',
                dataType:'jsonp',
                data:{data_type:'jsonp',pop_id:pop_id,token:def.token},
                success:function(data){                       
                    var arr = data.data || {};
                    var _html="";
                    var user_type = data.info.current_user_type || "";
                    def.has_down_power = data.info.has_down_power || 0;
                    if(user_type == "GUEST"){
                        def.layer_opacity.show();
                        def.layer_top.show();
                        $(".js-layer-top>p").html("想要查看该款全部细节吗？");
                        $(".js-layer-top>.look-tips").html("VIP用户可免费查看<br/>非VIP用户请联系咨询<br/>让款式开发更得心应手");
                        $(".user-tourist").show();
                    }else if(user_type == "NORMAL") {      
                        def.layer_opacity.show();
                        def.layer_top.show();
                        $(".js-layer-top>p").html("浏览需要VIP权限");
                        $(".js-layer-top>.look-tips").html("开通VIP可浏览全站趋势图片及行业<br/>深度报告书籍<br/>您现在是普通用户，可联系客服开通<br/>试用账号体验全站");
                        $(".user-general-btn").show();
                    }else if(user_type == "VIP" || user_type == "TRIAL"){
                        var title = arr.title || "";
                        var images = arr.images || [];
                        var img_len = images.length;                        
                        for(var i=0; i < img_len; i++) {
                            _html += '<div class="swiper-slide"><img src="'+images[i]+'"/></div>';
                        }
                        if(user_type == "TRIAL"){
                            $(".js-trial-layer").show();
                        }else{
                            $(".js-trial-layer").hide();
                        }
                        $(".js-img-box").html(_html);
                        $(".js-detail-title").html(title)
                        $(".js-total-img-num").html(img_len);
                        $(".js-detail-bigimg>div>img").eq(0).show()
                        def.layer_opacity.show();
                        $(".js-detail-contain").show();
                        mySwiper.activeIndex = 0;
                        mySwiper.update();  
                        mySwiper.init();  
                        
                    }               
                }
            })
        }

        // 款式、图案详情页翻页
        var ele = $(".detail-img-box");
        var timer = 0;
        if(ele.length){
            ele.on("touchstart", function(ev) {
                if(!def.has_down_power) {
                    ev.stopPropagation();
                    timer = setTimeout(function() {
                        def.layer_opacity.show();
                        def.layer_top.show();
                        $(".js-layer-top>p").html("保存需要VIP权限");
                        $(".js-layer-top>.look-tips").html("需要正式解锁VIP后才可保存图片！");
                        $(".user-download-btn").show();                        
                    },500);
                }

            });
            ele.on("touchend", function(ev) {
                if(!def.has_down_power) {
                    ev.stopPropagation();
                    clearTimeout(timer);
                }
            });
        }
        
        // 关闭权限提示弹框
        $(".js-close-layer,.js-cancel-tel,.js-download-know").on("click", function() {
            def.layer_opacity.hide();
            def.layer_top.hide();
        });

        // 关闭详情弹框
        $(".js-close-detail-top").on("click", function() {
            mySwiper.init();
            def.layer_opacity.hide();
            $(".js-detail-contain").hide();
        });

        
    })
})
