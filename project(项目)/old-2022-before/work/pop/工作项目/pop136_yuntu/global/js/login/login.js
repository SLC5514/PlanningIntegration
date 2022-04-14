/*
    #author     lut000
    #date       2017/11/08
    #porpuse    登录
*/

require.config({
    paths:{
        "md5":["lib/md5.min"],
    },
    shim:{
        "md5":{
            exports:["md5"]
        }
    }
});

require(["jquery","general","md5","cookie","msg"],function(jquery,general,md5,cookie){
	$(function(){
		// pop.msg({"txt":"欢迎登录pop！"},1200);       //弹出提示层

		var def={
            loading_ele:$(".js-loading-div"),                                                                   //加载等待
            bg_ele:$(".js-bg-div")                                                                              //加载等待
        };
		def.loading_ele.fadeOut(200);
        def.bg_ele.fadeOut(400);
		// input判断
        $(".js-input-area").on("focus",function(){
            var tar=$(this).hasClass("js-code")?$(this).parent().next(".js-error-ele"):$(this).next(".js-error-ele");
            tar.css({"opacity":0});
        }).on("blur",function(){
            var index=$(".js-input-area").index($(this));
            var is_ok=true,txt=$(this).val();
            var tar=$(this).hasClass("js-code")?$(this).parent().next(".js-error-ele"):$(this).next(".js-error-ele");
            txt=txt.replace(/\s/g,"");
        	// console.log(txt);
            switch(index){
                case 0:
                    if(txt=="" || general.fn.getCharacterLen(txt)<2){
                        is_ok=false;
                    }
                    break;
                case 1:
                    if(txt=="" || general.fn.getCharacterLen(txt)<6){
                        is_ok=false;
                    }
                    break;
                case 2:
                    if(txt=="" || txt.length<4){
                        is_ok=false;
                    }
                    break;
                default:
                    break;
            }
            if(is_ok==true){
                tar.css({"opacity":0});
            }else{
                tar.css({"opacity":1});
            }
        });
        // 记住用户名
        var userName = general.fn.getLocalSto("userName") ? general.fn.getLocalSto("userName") : '';
        $("#username").val(userName);
        if (userName != '') {
            $("#userBox").attr("checked","checked");
        }
        // 改变图形验证码
        $(".js-img-code-btn").on("click",function(){
            var str="/user/imgcaptcha/yuntu_login_code/";
            var t=(new Date()).getTime();
            $(this).attr("src",str+"?t="+t);
        });
		// 提交数据
        $(".js-login-btn").on("click",function(){
            subFunc($(this));
        });
        // 回车事件
        $(".js-form-ele").on("keydown",function(e){
            var keycode=e.keyCode;
            if(keycode==13){
                subFunc($(".js-login-btn"));
                return false;
            }
        });

        function subFunc(obj){
            var data={};
            $('.js-form-ele .js-input-area').each(function(){
                var txt=$(this).val() || '';
                var key_name=$(this).attr('name');
                data[key_name]=txt;
            });
            var error_eles=$(".js-error-ele");
            if(data["account"]==""){
                error_eles.eq(0).css({"opacity":1});
            }else if(data["password"]==""){
                error_eles.eq(1).css({"opacity":1});
            }else if(data["verify_code"]=="" || data["verify_code"].length<4){
                error_eles.eq(2).css({"opacity":1});
            }else{
                obj.val("登录中...");
                obj.off("click");
                data["password"]=md5(data["password"]);
                general.fn.subAjax({
                    url:"/user/dologin/",
                    data:data,
                    ctp:"application/x-www-form-urlencoded",
                    success:function(rdata){
                        obj.val("登录");
                        obj.on("click",function(){
                            subFunc($(this))
                        });
                        //添加用户名cookie
                        if($("#userBox").is(":checked")){//选中
                            general.fn.setLocalSto("userName", data["account"]);
                        } else {
                            general.fn.delLocalSto("userName");
                        }
                        window.location.href="/home/";
                    },
                    error:function(data){
                        obj.val("登录");
                        obj.on("click",function(){
                            subFunc($(this))
                        });
                        var ndata=data || {};
                        if(ndata.code!="10002"){
                            $(".js-img-code-btn").click();
                        }
                    }
                })
            }
        }
        
        // 留资
        $('.js-free-get').on('click',function(){
            var phone = $("#val1").val();
            if(phone=='' || phone==undefined || phone.length != 11){
                $('#msg').show();
                return;
            }else{
                $('#msg').hide();
            }
            var pid=general.fn.getLocationParameter().pid ? general.fn.getLocationParameter().pid : ($.cookie('pidtt') ? $.cookie('pidtt') : '');
            var url = encodeURI(window.location.href);
            var data={
                cellphone:phone,
                web_code:'yuntu',
                pid: pid,
                channel_url:url
            };
            $.ajax({
                url: 'https://api.pop-fashion.com/app/stayconsultation/',
                type: 'GET',
                dataType:'jsonp',
                data: data,
                success: function(data) {
                    if(data.code=='0'){                    
                        $('.js-sem-layer,.js-bg-div,.success-txt').show();
                        $(".fail-txt").hide();
                        $(".footwrap").hide()
                    }else{
                        $('.js-sem-layer,.js-bg-div,.fail-txt').show();
                        $(".success-txt").hide();
                    }
                },
                error: function(err) {}
            })
        })

        $(".js-sem-close").on("click", function() {
            $('.js-sem-layer,.js-bg-div').hide();
        });
	});
});