$(function(){
    if($(window).width()<=800){
        $(".footerFixed").hide();
    }
    setTimeout(function(){
        $(".js-color-banner").addClass("anit-banner");
    },500)
    

    $(window).on("scroll", function(){
        var scrollTop = $(this).scrollTop();
        if(scrollTop >$(".js-section1").offset().top-300){
            $(".js-section1").addClass("anit-section1");
        }
        if(scrollTop >$(".js-section2").offset().top-400){
            $(".js-section2").addClass("anit-section2");
            var time1 =setTimeout(function(){
                $(".js-section2").addClass("anit-downlist1");
            },1200);
            var time2 =setTimeout(function(){
                $(".js-section2").addClass("anit-downlist2");
            },1900);
            var time3 =setTimeout(function(){
                $(".js-section2").addClass("anit-downlist3");
            },2600);
            var time4 =setTimeout(function(){
                $(".js-section2").addClass("anit-downlist4");
            },3200);
        }

        if(scrollTop >$(".js-section3").offset().top-300){
            $(".js-section3").addClass("anit-section3");
        }

        if(scrollTop >$(".js-section4").offset().top-400){
            $(".js-section4").addClass("anit-section4");
            setTimeout(function(){
                $(".js-section4").addClass("anit-section4-next");
            },2000)            
        }

        if(scrollTop >$(".js-section-add").offset().top-400){
            $(".js-section-add").addClass("anit-setion-add");            
        }
    });
})