$(function(){
            f_BlocksIt();
            var lazyloadimg = $(".lazyload img");
            lazyloadimg.length && lazyloadimg.lazyload({effect : "show",failure_limit:12,load:f_BlocksIt});        
        function f_BlocksIt(){                  
                if($('.con_width').width() <1500){
            		$('.picbox').masonry({
                        gutterWidth: 47,
                        itemSelector: 'li',
                        isAnimated: false
                	});
            	}
            	else{
            		$('.picbox').masonry({
                        gutterWidth: 57,
                        itemSelector: 'li',
                        isAnimated: false
                	});
            	}               
        }
        $(window).resize(function() {
            f_BlocksIt();
        });
        $(window).scroll(function() {
            f_BlocksIt();
        });
});
