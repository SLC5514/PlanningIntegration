$(function() {
    // 悬浮导航展示
    $(".js-all-column-type").on("click", function() {
        $(".all-column-downlist").slideDown(100);
    });

    $("body").on("click", function() {
        $(".all-column-downlist").slideUp(50);
        $(".dropdown-column-list").hide();
        $(".js-all-nav-li").removeClass("on");
    })

    // 阻止
    $(".column-nav-box,.js-all-nav-li,.dropdown-column-list").on("click", function(e) {
        e.stopPropagation();        
    })

    $(".js-all-nav-li").on("mouseenter", function() {
        $(this).addClass("on");
        $(this).find(".dropdown-column-list").show();
    }).on("mouseleave", function() {
        $(this).removeClass("on");
        $(this).find(".dropdown-column-list").hide();
    })

    // 滚动导航悬浮
    var _url = window.location.href; 
    $(window).on("scroll", function() {
        var scrollTop = $(this).scrollTop();
        if(_url.indexOf("/colordata/") == -1){
            if(scrollTop > 110){
                $(".js-leftT").hide();
            }else{
                $(".js-leftT").show();
            }
        }else{
            if(scrollTop > 110){
                $(".js-head-top-fixed").show();
                $(".js-head-top").hide();
            }else{
                $(".js-head-top-fixed").hide();
                $(".js-head-top").show();
            }
        }
        
    })

    // 全站搜索栏目搜索切换
    $(".js-f-switch-box").on("click", "a", function() {
        var type = $(this).attr("data-type");
        $(this).addClass("on").siblings("a").removeClass("on");
        if(type == "all") {
            $(".js-all-input-contain").find("input[type=text]").attr("placeholder","时尚资讯一网打尽").removeAttr("data-type");
        }else{
            $(".js-all-input-contain").find("input[type=text]").attr("placeholder","在本页面搜索你想要的…").attr("data-type","column");
        }
    });
    
        
});