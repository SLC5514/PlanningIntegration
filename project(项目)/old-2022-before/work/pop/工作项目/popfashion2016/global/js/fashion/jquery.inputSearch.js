// JavaScript Document

$(function () {
   ;(function ($) {
        $.fn.inputText = function (options) {
            var defaultVal = {
                txt: "请输入搜索关键字",
                lightColor: "#ccc",
                color: "#333",
                fontSize: "12px"
            };
            var obj = $.extend(defaultVal, options);
            return this.each(function () {
                var sel = $(this);
                //sel.css({"color":obj.lightColor,"font-size":obj.fontSize});
                //sel.val(obj.txt);    

            sel.on("focus",function () {
                    if (sel.val() == obj.txt) {
                        sel.val("").css("color", obj.color);
                    }
                    else {sel.css("color", obj.color);}
            });
            sel.on("blur",function () {
                    if ($.trim(sel.val()) === "") {
                        sel.val(obj.txt).css("color", obj.lightColor);
                    }
	       else if(sel.val() !="" && sel.val() != obj.txt ) {
                        sel.css("color", obj.color);
                    }
	       else{
	           sel.css("color", obj.lightColor);
	       }
                });
            });
        }
    })(jQuery);
    
     if(navigator.appName == "Microsoft Internet Explorer" && (navigator.appVersion.match(/7./i)=="7." || navigator.appVersion.match(/8./i)=="8." || navigator.appVersion.match(/9./i)=="9.")){        
        var topInVal2="请输入关键字，例如：2017春夏 发布会";
        $(".head-log .Itext").val(topInVal2);
        $(".head-log .Itext").inputText({
            txt:topInVal2,
            lightColor:"#999",
            color:"#666",
            fontSize:"12px"
        });
    }



});