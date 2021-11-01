/*
	#author		lut000
	#date 		2018/05/06
    #porpuse    共用入口
*/
require.config({
    baseUrl:"/",
    urlArgs:"r="+global_backstage_obj.static_change_time,
    paths:{
        "jquery":["lib/jquery/jquery-1.11.3.min"],
        "msg":["common/pop-msg-1.2"],
        "general":["common/general-1.0"],        
        "register":["user/register"],
        "cookie":["lib/jquery/jquery.cookie"]
    },
    map:{
        '*':{
            "css":"lib/require/css-min",
        }
    },
    waitSeconds:0,
    shim:{
        "general":{
            deps:["jquery"],
            exports:"general"
        },
        "msg":{
            deps:["jquery"],
            exports:"msg"
        },
        "cookie":{
            deps:["jquery"],
            exports:"cookie"
        }
    },
    
});

require(["jquery","msg","cookie","https://wpa.b.qq.com/cgi/wpa.php"],function(jquery,msg){
    $(function(){
        // 适配移动端        
        var oHtml = document.documentElement;
        getSize();
        window.onresize = function(){
            getSize();
        }
        function getSize(){
            var screenWidth = oHtml.clientWidth;
            if(screenWidth < 320){
                oHtml.style.fontSize = '20px';
            } else if(screenWidth > 750){
                oHtml.style.fontSize = '40px';
            }else{
                oHtml.style.fontSize = screenWidth/(750/40) + 'px';
            }
        }
        // 是否是pc
        function IsPC(){  
            var flag = true;
            var doc_w = document.documentElement.clientWidth;        
            if( doc_w < 750 && document.addEventListener){
                flag = false;
            }        
            return flag;  
        }     
        var pc_flag = IsPC();

        // 安卓系统orIOS系统
        var u = navigator.userAgent;
        var isIOS = !!u.match(/\(i[^;]+;( U;)? CPU.+Mac OS X/); //ios终端        
        if (isIOS) {
    　　　　$(".js-contact-qq-btn").attr("href","tel:4008 210 500")
        }else{
            //在线qq
            $("body .js-contact-qq-btn").each(function(i){
                var id_txt="js-contact-qq-btn"+(i+1);        
                $(this).attr("id",id_txt);
                BizQQWPA.addCustom({
                    aty:0,
                    nameAccount:800030036,
                    selector:id_txt
                });
            });

        }
            
        // 头部导航
        var nav_show = false, nav_slide = false;
        function navBoxhide(){  // 隐藏列表
            if(nav_show){
                return;
            }
            nav_show = true;
            
            $('.js-nav-show').animate({
                'left': '-70%'
            }, 200, function(){
                $('.js-web-downlist1').hide().siblings('.js-web-downlist').show();
                $('.js-nav-show').hide();
                nav_show = false;  // 恢复可操作
            });
            $('.js-view-contain, .js-h5-header').animate({
                'left': '0%'
            }, 200)
        }
    
    
    
        // 按钮显示列表
        $('.js-web-downlist').on('click', function(){
            if(nav_show){
                return;
            }
            nav_show = true;
            $('.js-nav-show').show().animate({
                'left': '0%'
            }, 200, function(){
                $('.js-web-downlist').hide().siblings('.js-web-downlist1').show();
                nav_show = false;  // 恢复可操作
            });
            $('.js-view-contain, .js-h5-header').animate({ 
                'left': '70%'
            }, 200)
        });    
            
        // 按钮隐藏列表
        $('.js-web-downlist1').on('click', function(){
            navBoxhide();
        });    
        
    
        var touchObj ={
            x: 0,
            y: 0,
            is_x: false,
            is_f: false
        };
    
        if( !pc_flag ){
            if($('.js-nav-show').length == 0){
                return false;
            }
            $('.js-nav-show')[0].addEventListener("touchstart",function(ev){
                if(nav_show){
                    return;
                }
                ev=ev||event;
                var touchC = ev.changedTouches[0];
                touchObj.x = touchC.clientX;
                touchObj.y = touchC.clientY;
            },{passive:true});
    
            $('.js-nav-show')[0].addEventListener("touchmove",function(ev){
                if(touchObj.is_f || nav_show){  //第一次进
                    return;
                }
                touchObj.is_f = true;
                ev=ev||event;
                var touchC = ev.changedTouches[0];
                var nowX = touchC.clientX;
                var nowY = touchC.clientY;
                var disX = Math.abs(nowX - touchObj.x);
                var disY = Math.abs(nowY - touchObj.y);
                if( disX <= disY ){
                    touchObj.is_x = true;
                }
            },{passive:true});
    
            $('.js-nav-show')[0].addEventListener("touchend",function(ev){
                if( touchObj.is_x || nav_show ){  //第一次进
                    touchObj.is_x = false;
                    touchObj.is_f = false;
                    return;
                }
                ev=ev||event;
                var touchC = ev.changedTouches[0];
                var endX = touchC.clientX;
                var disX = endX - touchObj.x;
                if( disX < -90 ){
                    navBoxhide();
                }
                touchObj.is_x = false;
                touchObj.is_f = false;
            });
        }

        // 头部登录or未登录
        var logined = $.cookie('userinfo_id');
        if(logined){
            $(".js-logined-success").show();
            $(".js-logined-fail").hide();
            // 获取头像与姓名
            $.ajax({
                url:'/member/getNameAvatar/?'+Math.random(),
                dataType:'json',
                type:'post',
                success:function(data){
                    var avatar = data.data.avatar || "";
                    if(avatar == ""){
                        $(".js-avatar-img").find('img').attr('src','/application/global/images/user/avatar.png');
                    }else{
                        $(".js-avatar-img").find('img').attr('src',global_backstage_obj.static_img+data.data.avatar);
                    }   
                    
                    $(".js-avatar-img").find('span').text(data.data.real_name);              
                }
            })         
        }else{
            $(".js-home-reg").show();
        }
        


        

        
    });   
});
