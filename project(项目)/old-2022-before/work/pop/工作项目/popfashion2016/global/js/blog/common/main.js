/*
	#author		lut000
	#date 		2018/05/06
    #porpuse    共用入口
*/
require.config({
    baseUrl:"/global/js",
    urlArgs:"r="+(new Date()).getTime(),
    paths:{
        "jquery":["common/jquery-1.11.3.min"],
        "general":["common/general-1.0"],
        "msg":["common/pop-msg-1.2"]
    },
    waitSeconds:0,
    shim:{
        "general":{
            deps:["jquery"],
            exports:"general"
        },
        "msg":{
            deps:["jquery"],
            exports:"msg"
        }
    },
    
});

require(["jquery","general","msg"],function(jquery,general,msg){
   
});