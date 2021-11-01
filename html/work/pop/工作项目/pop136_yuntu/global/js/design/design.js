/*
	#author		shuzhan
	#date 		2018/08/27
*/
require.config({
    paths:{
        "lazyload":["lib/jquery.lazyload.min"]
    },
    shim:{
       	"lazyload":{
            deps: ["jquery"],
            exports: "lazyload"
        }
    }
});

require(["jquery",'msg'], function (jquery, msg) {
    $(".js-msg span").on('click', function () {
        msg.msg({"txt":"尊敬的会员，谢谢您的反馈"},1600);
    })
})