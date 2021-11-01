$(function(){
    if($(window).width()<=800){
        $(".footerFixed").hide();
        setTimeout(function(){
            $(".js-section1").addClass("anit-section1");
        },500)
    }
    setTimeout(function(){
        $(".js-color-banner").addClass("anit-banner");
    },500)
    

    $(window).on("scroll", function(){
        var scrollTop = $(this).scrollTop();
        if(scrollTop >$(".js-section1").offset().top-400){
            $(".js-section1").addClass("anit-section1");
        }
        if(scrollTop >$(".js-section2").offset().top-400){
            $(".js-section2").addClass("anit-section2");
            setTimeout(function(){
                $(".js-small-ul").hide();
            },1500);
        }

        if(scrollTop >$(".js-section3").offset().top-400){
            $(".js-section3").addClass("anit-section3");
        }

        if(scrollTop >$(".js-section4").offset().top-400){
            $(".js-section4").addClass("anit-section4");
            setTimeout(function(){
                if($(window).width()>800){
                    $(".js-color-height").animate({"height":"190px"},500);
                }else{
                    $(".js-color-height").animate({"height":"4.75rem"},500);
                }                
                $(".js-section4").addClass("anit-section4-next");
            },2000)
        }
    });
})