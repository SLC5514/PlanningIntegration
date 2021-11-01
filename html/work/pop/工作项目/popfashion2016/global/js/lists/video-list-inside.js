/*
    fanglinna
    20191216
*/

$(function() {

    // 其他网站
    // $(".js-web-logo").on("mouseenter", function() {
    //     $(this).find("a").addClass("web-on");
    //     $(this).find(".web-all-link").show();
    // }).on("mouseleave", function() {
    //     $(this).find("a").removeClass("web-on");
    //     $(this).find(".web-all-link").hide();
    // });
    
    ;(function($){
        $.fn.Swipertab = function(options){
            var defaults = {
                //各种参数，各种属性
                d_value:50
            }
            var options = $.extend(defaults,options);
            this.each(function(){
                var self = $(this);
                var child_box = self.find(".js-swiper-child");
                var index = child_box.find("a.select-on").index();
                var a_len = child_box.find("a").length
                var a_wid = child_box.find("a").outerWidth(true);
                child_box.width(a_wid * a_len);
                var all_wid = child_box.outerWidth(true);
                var par_wid = self.width()-defaults.d_value;
                var on_wid = self.find(".select-on").index()*a_wid; 
                var area_index =Math.round(par_wid/a_wid); 
                var move_wid = 0;
                if(all_wid - on_wid  < par_wid){
                    move_wid = all_wid-(area_index*a_wid);     
                    index = a_len - area_index;
                    self.find(".js-swiper-switch-next").removeClass("data-on");
                }else{
                    move_wid = on_wid;
                    self.find(".js-swiper-switch-next").addClass("data-on");
                }
                if(all_wid <= par_wid){
                    return false
                }else{
                    self.find(".js-switch-btn").show();
                    child_box.animate({"left":-move_wid},50);        
                }    

                if(index > 0){
                    self.find(".js-swiper-switch-prev").addClass("data-on");
                }
                
                var left_val = move_wid;
                function slideRightFunc(obj) {        
                    index ++     
                    if(index > a_len-area_index){
                        index = a_len-area_index;
                        return false;
                    }else{                        
                        left_val += a_wid;
                        obj.animate({"left":-left_val},50);
                        self.find(".js-swiper-switch-prev").addClass("data-on");
                        if(index == a_len-area_index){
                            self.find(".js-swiper-switch-next").removeClass("data-on");            
                        }
                    }        
                }

                function slideLeftFunc(obj) {
                    if(index == 0){
                        return false;
                    }else{
                        index--;
                        if(index == 0){
                            left_val = 0;
                            obj.animate({"left":left_val},50);
                            self.find(".js-swiper-switch-prev").removeClass("data-on");
                            self.find(".js-swiper-switch-next").addClass("data-on"); 
                            return false;
                        }else{
                            var per_val = self.find("a").eq(index).outerWidth(true);
                            left_val -= per_val;
                            obj.animate({"left":-left_val},50);
                        }
                        self.find(".js-swiper-switch-next").addClass("data-on"); 
                    }
                }

                self.find(".js-swiper-switch-next").on("click", function() {
                    var $self = $(this).siblings(".js-swiper-child");
                    slideRightFunc($self)
                });
                self.find(".js-swiper-switch-prev").on("click", function() {
                    var $self = $(this).siblings(".js-swiper-child");
                    slideLeftFunc($self)
                });
            });
        }

    })(jQuery);   

    // 头部季节切换
    $(".js-swiper-season-main").Swipertab();
    // 更多发布会切换
    $(".js-swiper-runway-main").Swipertab();

    
    // 计算列表宽度
    function widthFunc(self) {
        var li_len = self.find("li").length;
        var li_wid = self.find("li").outerWidth(true);
        var all_wid = parseInt(li_len*li_wid);
        self.find("ul").width(all_wid);
    }
    // 相关分析视频
    widthFunc($(".js-runway-more-lists"));

    // 无数据推荐
    var all_len = $(".js-recommend-list li").length;
    switchInit();
    var num = 0;
    function switchInit() {
        if($(".lookbook-recommend-list").length){
            if($(window).width() < 1500){
                num = 4
            }else{
                num = 5
            }
        }else{
            if($(window).width() < 1500){
                num = 3
            }else{
                num = 4
            }
        }        
        if(all_len > num){
            $(".recommend-switch").show();
        }else{
            $(".recommend-switch").hide();
        }
    };

    $(window).on("resize", function() {
        switchInit();
    });
    widthFunc($(".js-recommend-list"));
    
    var mySwiper = new Swiper('.swiper-container', {
        slidesPerView:'auto',
        simulateTouch:false
    })

    $(".js-switch-btn-next").on("click", function() {
        mySwiper.slideNext();
    });
    $(".js-switch-btn-prev").on("click", function() {
        mySwiper.slidePrev();
    });

    $(".js-recommend-prev").on("click", function() {
        mySwiper.slidePrev();
    });

    $(".js-recommend-next").on("click", function() {
        mySwiper.slideNext();
    });

    // 发布会视频花絮切换
    var videoid = $(".js-switch-video-btn>a.v-on").attr("data-videoId");
    if(videoid != "" && $("#runwayPlayer").length){
        player = video_player('runwayPlayer',videoid);
    }
    var is_play = false;
    var $video_btn = $(".js-switch-video-btn>a");
    if($video_btn.length > 1){
        $video_btn.on("click", function() {
            if($(this).hasClass("v-on")){
                return false;
            }            
            is_play = false;
            is_close = false;
            $(this).addClass("v-on").siblings("a").removeClass("v-on");
            switch_payer = video_player('runwayPlayer',$(this).attr("data-videoId"));
        });
    }
    
    // 灵感视频
    var $insp_player = $("#inspirationPlayer");
    if($insp_player.length){
        video_player('inspirationPlayer',$insp_player.attr("data-videoId"));
    }

    function video_player(player_id,vid){
        var params = {
            styleid: '0',
            client_id: '6304acd0252780d2',
            vid: vid,
            newPlayer: true
        };
        return new YKU.Player(player_id, params);
    }
    
    
    window.addEventListener('message',function(e){
        var data = e.data;
        switch (data.msg){
            case "onPlayEnd":{
                is_play = false;
                break;
            }
            case "state":{
                is_play = true;
                break;
            }
        }
    },false);

    var is_close = false;
    $(window).on("scroll", function() {
        var scrolltop = $(this).scrollTop();
        if(is_play && scrolltop > 450 && is_close == false){
            $(".play-video-img").addClass("position-video");
            $(".video-detail-switch-main").css("transform",'none');
        }else{
            $(".play-video-img").removeClass("position-video");
            if($(".video-trend-lists-blank").length == 0){                
                $(".video-detail-switch-main").css("transform",'translateY(-50%)');
            }
        }
    })

    // 关闭视频
    $(".close-video-positiion").on("click", function() {
        is_close = true;
        $(".play-video-img").removeClass("position-video");
        $(".close-video-positiion").hide();
    });

    // 切换季节跳转
    var link = $("#link");
    var param = $("#param");
    var brand_id = $(".js-brand-selected").attr("data-bra");
    var col  = link.attr("data-col")? link.attr("data-col"):param.attr("data-col");
    var reg = link.attr("data-reg")?link.attr("data-reg"):param.attr("data-reg");
    var v_data = {};
    var videotype = link.attr("videotype") || null;
    
    $(".js-season-seleted-labels").on("click", "a", function() {
        if($(this).hasClass("select-on")){
            return false;
        }
        var sea_id = $(this).attr("data-sea");
        if(col == 10){
            v_data = {sea:sea_id,bra:brand_id};
        }else{
            v_data = {sea:sea_id,bra:brand_id,col:col};
        }
        $.ajax({
            url:'/video/getinsideurl/?'+Math.random(),
            type:'post',
            dataType:'json',
            data:v_data,
            success: function(data) {
                window.location.href= data.data.url;
            }
        })
    });

    $('.lazyload img').lazyload({effect: "show"});
});