/*
	#author		lut000
	#date 		2017/12/01
    #porpuse    首页
*/
require.config({
    baseUrl:"/global/js",
    urlArgs:"r="+(new Date()).getTime(),
    paths:{
        // "parallax":["lib/jquery/jquery.parallax-1.1.3"],
        "general":["common/general-1.0"],
        "stab":["common/stab-1.1.development"],
        "wcloud":["home/wordcloud-1.0"],
        "line-canvas":["home/line-canvas"]
    },
    shim:{
        "general":{
            // deps:["jquery"],
            exports:"general"
        },
        "wcloud":{
            deps:["general"]
        }
    }
});
require(["general","stab","wcloud"],function(general,stab,wcloud){
    $(function(){

        var def={
            is_sub:false,

            tab_obj:null,
            yunci:null,
            tab_box:$(".js-tab-container")[0],
            vip_remind:$(".remind"),  //会员过期提醒
            header_ele:$(".js-head-top"),
            canvas_div:$(".js-canvas-div"),
            // introduce_section:$(".js-introduce-section"),
            nav_btn:$(".js-nav-btn"),
            scroll_val:{
                header:0,
                introduce:0,
                nav:0
            },
            is_move:false,
            is_move_num:false,
            is_click_show:false,
            return_data:{},
            timer:null,
            timer2:null,
            number_arr:[],
            is_set_number_dom:false,

            banner_scale: 1
        };

        // 广告内容位置判断
        setPcenter();
        function setPcenter(){
            $(".js-advertisement-small").find("a").each(function(){
                var ph=$(this).height();
                var oh=$(this).find("p").height();
                $(this).find("p").css({"top":(ph-oh)/2+"px"});
            });
        };
        
        $(".js-close-drop-nav").on("click",function(){
            def.is_click_show=false;
            def.header_ele.removeClass("is-down");
            $(".js-dropdown-nav").removeClass("drop-now").hide();
            $(this).hide();
            $(".js-dropdown-nav .widthx80").css({"margin-top":"auto"});


            var nscroll_y=$(window).scrollTop();
            setMoveFunc(nscroll_y);

        });
        
        // banner放大
        function bannerScale(nscroll_y){
            var banner_h = $('.js-nav-section').height();
            if( nscroll_y < banner_h ){
                def.banner_scale = nscroll_y/banner_h * .2 + 1;
                def.banner_scale < 1? def.banner_scale = 1 : 1;
                $('.js-nav-section').css({
                    'background-size': 100*def.banner_scale+'%'
                })
            }
        }

        function getScrollVal(){
            def.scroll_val.nav=def.nav_btn.offset().top;
            def.scroll_val.header=def.scroll_val.nav
            def.scroll_val.txt=$(".js-nav-div").offset().top;

            setMoveFunc($(window).scrollTop());
        };        
        

        function setMoveFunc(nscroll_y){

            if(def.is_click_show==true){return false;}
            var tscroll=def.scroll_val.txt;
            if(tscroll>=nscroll_y){
                $(".js-scroll-opacity").css({"opacity":1.1-(nscroll_y+1)/tscroll});
            }
            if(def.scroll_val.nav>=nscroll_y){
                def.header_ele.removeClass("has-scroll");
            }else{
                def.header_ele.addClass("has-scroll");                
            }
        }

        // 首页导航
        $(".js-home-nav-list>li").on("mouseleave",function(){
            var a=$(this);
            clearTimeout(def.timer2);
            def.timer2=setTimeout(function(){
                a.children("ul").stop(true).slideUp(200);
            },200);

            a.removeClass("now-choice");
        }).on("mouseenter",function(){
            var a=$(this);
            clearTimeout(def.timer2);
            a.addClass("now-choice");
            a.children("ul").stop(true).slideDown(200);
            a.siblings("li").removeClass("now-choice").children("ul").stop(true).slideUp(200);
        });
        //会员过期提醒
        var remind_time=$("#remind-time").val();
        var sta_remind=false;
        var curDate = new Date();
        var reDate=pop_fashion_global.fn.getSto("vip_remind_time") != undefined ? pop_fashion_global.fn.getSto("vip_remind_time") : 0;
        sta_remind=$.cookie('vip_remind') ? $.cookie('vip_remind') : "false";
        if(def.vip_remind.length>0){
            if(remind_time>7){
                if(curDate.getTime()<reDate){
                    def.vip_remind.hide();
                }else{
                    pop_fashion_global.fn.delSto("vip_remind_time");
                    def.vip_remind.show();
                }
            }else if(remind_time<=7){
                if(sta_remind== "true"){
                    def.vip_remind.hide();
                }else{
                    def.vip_remind.show();
                }
            }else{
                if(sta_remind== "true"){
                    def.vip_remind.hide();
                }else{
                    def.vip_remind.show();
                }
            }
            $(".js-remind-close").on("click",function(){
                def.vip_remind.hide();
                if(remind_time>7){
                    pop_fashion_global.fn.setSto("vip_remind_time",getRreindTime())   //存储关闭时间
                }else{
                    $.cookie('vip_remind', "true", {path: '/' });
                }
            })
        }
        //自然天7天后
        function getRreindTime(){
            var curDate = new Date();
            var reDate = new Date(curDate.getTime() + (24*60*60*1000)*7);
            var Y = reDate.getFullYear() + '/';
            var M = (reDate.getMonth()+1 < 10 ? '0'+(reDate.getMonth()+1) : reDate.getMonth()+1) + '/';
            var D = reDate.getDate();
            var reDate1=new Date(Y+M+D+" 00:00:00");
            return reDate1.getTime();
        }
        
    });
});