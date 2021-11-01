/*
	#author		fanglinna
	#date 		2018/8/16
    #porpuse    blog
*/


require(["general"], function (general) {
    $(function(){
        function IsPC(){  
            var flag = true;
            var doc_w = document.documentElement.clientWidth;        
            if( doc_w < 800 && document.addEventListener){
                flag = false;
            }        
            return flag;  
        }     
        var pc_flag = IsPC();
            
            
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
            $('.js-blog-view, .js-blog-h5-header').animate({
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
            $('.js-blog-view, .js-blog-h5-header').animate({ 
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
            $('.js-nav-show')[0].addEventListener("touchstart",function(ev){
                if(nav_show){
                    return;
                }
                ev=ev||event;
                var touchC = ev.changedTouches[0];
                touchObj.x = touchC.clientX;
                touchObj.y = touchC.clientY;
            });

            $('.js-nav-show')[0].addEventListener("touchmove",function(ev){
                console.log(111);
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
            });

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


        var oHtml = document.documentElement;
        getSize();
        window.onresize = function(){
            getSize();
        }
        function getSize(){
            var screenWidth = oHtml.clientWidth;
            if(screenWidth < 320){
                oHtml.style.fontSize = '20px';
            } else if(screenWidth > 720){
                oHtml.style.fontSize = '40px';
            }else{
                oHtml.style.fontSize = screenWidth/(720/40) + 'px';
            }
        }        
    });


})



