$(function(){
    f_BlocksIt(); 
	var lazyloadimg = $(".lazyload img");
	lazyloadimg.length && lazyloadimg.lazyload({effect : "show",threshold: 100,load:f_BlocksIt});
    function f_BlocksIt(){
        	if($('.con_width').width() <1500){
        		$('.rightc_main .inspiration').masonry({
                    gutterWidth: 53,
                    itemSelector: 'li',
                    isAnimated: false
            	}); 
        	}
        	else{
        		$('.rightc_main .inspiration').masonry({
                    gutterWidth: 13,
                    itemSelector: 'li',
                    isAnimated: false
            	}); 
        	}         
    }
});