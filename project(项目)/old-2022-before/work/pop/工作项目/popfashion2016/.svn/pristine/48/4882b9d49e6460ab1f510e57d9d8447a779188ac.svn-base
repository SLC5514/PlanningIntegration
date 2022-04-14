/*
    fanglinna
    20191216
*/

$(function() {
    var def = {
        url : location.href,
        data:{}
    }
    // 设置广告轮播总宽度
    var all_len = $(".js-swiper-wrapper>div").length;
    var per_wid = $(".js-swiper-wrapper>div").outerWidth(true);
    var all_wid = parseInt(all_len * per_wid);
    $(".js-swiper-wrapper").width(all_wid);

    // 是否显示左右切换按钮
    switchInit();
    function switchInit() {
        if($(window).width() < 1500){
            if(all_len >4){
                $(".switch-btn-box").show();
            }else{
                $(".switch-btn-box").hide();
            }
        }else{
            if(all_len > 5){
                $(".switch-btn-box").show();
            }else{
                $(".switch-btn-box").hide();
            }
        }
    };

    $(window).on("resize", function() {
        switchInit();
    });    
    
    var mySwiper = new Swiper('.swiper-container', {
        slidesPerView:'auto',
        simulateTouch:false
    })

    // 上下款切换
    $(".js-switch-next").on("click", function() {
        mySwiper.slideNext();
    });
    $(".js-switch-prev").on("click", function() {
        mySwiper.slidePrev();
    });

    // 发布会视频
    $('.lazyload img').lazyload({effect: "show"});
    $(".js-area-video-box").on("click", "a", function() {
        var data_val = $(this).attr("data-reg");
        $(this).addClass("on").siblings().removeClass("on");
        if(data_val == "titbit"){
            def.data = {titbit:data_val};
        }else{
            def.data = {reg:data_val};
        }
        $.ajax({
            url:def.url + '?' + Math.random(),
            type:'post',
            dataType:'json',
            data:def.data,
            success: function(data) {
                var arr = data.data || [];
                var more_url = data.info.more_url || "";            
                var _html = "";
                if(arr.length > 0){
                    for(var i = 0;i < arr.length; i++){
                        var cover = arr[i].cover || "";
                        var inside_url = arr[i].inside_url || "";
                        var title = arr[i].title || "";
                        var regionName = arr[i].regionName || "";
                        var seasonName = arr[i].seasonName || "";
                        var brand_name = arr[i].brand_name || "";
                        var video_tag = arr[i].video_tag || 2;
                        _html += '<li>';
                        if(P_UserType == 5){
                            _html +='<a href="javascript:void(0);" class="js-general-user-status" data-columnId="3">';
                        }else{
                            _html +='<a href="'+inside_url+'" target="_blank" data-columnId="3">';
                        }
                        if(video_tag == 1){
                            _html += '<div class="img-box"><img src="'+cover+'"/></div>'
                        }else{
                            _html += '<div class="img-box">'+cover+'</div>'
                        }
                        _html += '<div class="conference-text"><p>'+title+'</p><div><span>'+regionName+'</span><span>'+brand_name+'</span><span>'+seasonName+'</span></div></div></a> </li>'
                    }
                    $(".js-conference-video-ul").html(_html);
                    $(".js-more-conference-video").find("a").attr("href",more_url);
                    $('.lazyload img').lazyload({effect: "show"});
                }else{
                    $(".conference-video-main").html('<div class="video-list-blank-data"><img src="https://imgf1.pop-fashion.com/global/images/fashion_home/null.png"><p>暂无内容 ，去看看别的吧～</p></div>');
                }
                
            }
        })
    })
    


});