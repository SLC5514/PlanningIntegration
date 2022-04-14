/*
	#author		lut000
	#date 		2017/11/06
    #porpuse    共用入口
*/
require.config({
    baseUrl:STATIC_URL3+"/global/js",
    // urlArgs:"r="+(new Date()).getTime(),
    urlArgs:"t="+static_change_time,
    paths:{
        "jquery":["lib/jquery-1.11.3.min"],
        "cookie":["lib/jquery.cookie"],
        "general":["common/general-1.0"],
        "loop":["common/loop-require"],
        "msg":["common/pop-msg-1.0"],
        "mousewheel":["lib/jquery.mousewheel"],
    },
    map:{
        '*':{
            "css":"lib/require/css-min",
        }
    },
    shim:{
        "general":{
            deps:["jquery"],
            exports:"general"
        },
        "cookie":{
            deps:["jquery"],
            exports:"cookie"
        },
        "msg":{
            deps:["jquery"],
            exports:"msg"
        },
        "loop":{
            deps:["jquery"]
        }
    }
});
require(["jquery","general","loop"],function(jquery,general){
    $(function(){
        var def={
            href_obj:general.fn.getLocationParameter() || {}  
        };
        // 返回上次页面
        $(".js-return-back-btn").on("click",function(){
            var ntype=def.href_obj["type"]!=undefined?def.href_obj["type"]:1;
            if(ntype == 8){
                window.location.href="/virtualtryon/virtualspl/"
            }else{
                history.back(-1);
            }            
        });

        // 退出登录
        $(".js-login-out-btn").on("click",function(){
            general.fn.subAjax({
                url:"/user/logOut",
                type:"get",
                ctp:"application/x-www-form-urlencoded",
                success:function(data){
                    window.location.href="/user/login/";
                },
                error:function(data){
                    
                }
            })

        });

        // 滚动监听
        // if(window.location.href.indexOf('/virtualtryon/virtualspl/')!=-1){
        //     setVirtualBackShow($(window));
        //     $(window).on("scroll",function(){
        //         general.fn.throttle(setVirtualBackShow,null,[$(this)],18);
        //     });
        // }else{
        //     setBackShow($(window));
        //     $(window).on("scroll",function(){
        //         general.fn.throttle(setBackShow,null,[$(this)],18);
        //     });
        // }
        setBackShow($(window));
        $(window).on("scroll",function(){
            general.fn.throttle(setBackShow,null,[$(this)],18);
        });
        function setBackShow(obj){
            var left_scroll=obj.scrollLeft();
            $(".js-header").stop().animate({"left":-left_scroll+"px"},200,"linear");
        };
        // function setVirtualBackShow(obj){
        //     var left_scroll=obj.scrollLeft();
        //     $(".js-header").stop().animate({"left":-left_scroll+"px"},200,"linear");
        //     $(".js-pattern-list-div").stop().animate({"left":-left_scroll+"px"},200,"linear");
        // };

        //在线qq
        $("body .js-contact-qq-btn").each(function(i){
            var id_txt="js-contact-qq-btn"+(i+1);
            var type=$(this).attr("data-type") || "";
            var qq_number=0;
            if(type!="" && type==1){
                qq_number=800020016;            //售后
            }else{
                qq_number=800030036;            //售前
            }
            $(this).attr("id",id_txt);
            BizQQWPA.addCustom({
                aty:0,
                nameAccount:qq_number,
                selector:id_txt
            });
        });
    });
});