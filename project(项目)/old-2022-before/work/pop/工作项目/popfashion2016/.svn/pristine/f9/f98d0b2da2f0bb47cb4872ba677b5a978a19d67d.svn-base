$(function(){
    if($(window).width()<=800){
        $(".footerFixed").hide();
        setTimeout(function(){
            $(".js-section1").addClass("anit-section1");
        },500);
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
                $(".js-section2").addClass("anit-download1");
            },500);
            setTimeout(function(){
                $(".js-section2").addClass("anit-download2");
            },1200);
        }

        if(scrollTop >$(".js-section3").offset().top-400){
            $(".js-section3").addClass("anit-section3");
        }

        if(scrollTop >$(".js-section4").offset().top-400){
            $(".js-section4").addClass("anit-section4");            
        }
        
        if(scrollTop >$(".js-section5").offset().top-400){
            $(".js-section5").addClass("anit-section5");
        }

        if(scrollTop >$(".js-section6").offset().top-400){
            $(".js-section6").addClass("anit-section6");
        }
    });
})