function f_BlocksIt(){
    if($(window).width() <1500){
        $('.rightc_main .inspiration').masonry({
            gutterWidth: 70,
            itemSelector: 'li',
            isAnimated: false
        });
    }
    else{
        $('.rightc_main .inspiration').masonry({
            gutterWidth: 30,
            itemSelector: 'li',
            isAnimated: false
        });
    }
}
$(function(){
    f_BlocksIt();
    var lazyloadimg = $(".lazyload img");
        lazyloadimg.length && lazyloadimg.lazyload({effect : "show",load:f_BlocksIt});   
    
    $(window).scroll(function() {
        f_BlocksIt();
    });   
});