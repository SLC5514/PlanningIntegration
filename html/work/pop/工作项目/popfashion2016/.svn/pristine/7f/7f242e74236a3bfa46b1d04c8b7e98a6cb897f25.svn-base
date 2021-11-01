$(function(){
    if($(window).width()<=800){
        $(".footerFixed").hide();
        setTimeout(function(){
            $(".js-section1").addClass("anit-section1");
            var timer= setTimeout(function(){
                $(".js-section1").addClass("anit-section1-p1");
            },1000);
            var timer= setTimeout(function(){
                $(".js-section1").addClass("anit-section1-p2");
            },1500);
            var timer= setTimeout(function(){
                $(".js-section1").addClass("anit-section1-p3");
            },2000);
            var timer= setTimeout(function(){
                $(".js-section1").addClass("anit-section1-p4");
            },2500);
        },500);
    }
    setTimeout(function(){
        $(".js-color-banner").addClass("anit-banner");
    },500)
    

    $(window).on("scroll", function(){
        var scrollTop = $(this).scrollTop();
        if(scrollTop >$(".js-section1").offset().top-400){
            $(".js-section1").addClass("anit-section1");
            var timer= setTimeout(function(){
                $(".js-section1").addClass("anit-section1-p1");
            },1000);
            var timer= setTimeout(function(){
                $(".js-section1").addClass("anit-section1-p2");
            },1500);
            var timer= setTimeout(function(){
                $(".js-section1").addClass("anit-section1-p3");
            },2000);
            var timer= setTimeout(function(){
                $(".js-section1").addClass("anit-section1-p4");
            },2500);
        }
        if(scrollTop >$(".js-section2").offset().top-400){
            $(".js-section2").addClass("anit-section2");           
        }
    });
})