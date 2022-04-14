require(["jquery"],function(){
    // 配置H5
    var oHtml = document.documentElement;
    getSize();
    window.onresize = function(){
        getSize();
    }
    function getSize(){
        var screenWidth = oHtml.clientWidth;
        if(screenWidth < 320){
            oHtml.style.fontSize = '20px';
        } else if(screenWidth > 720){
            oHtml.style.fontSize = '40px';
        }else{
            oHtml.style.fontSize = screenWidth/(750/40) + 'px';
        }
    } 
});

    
