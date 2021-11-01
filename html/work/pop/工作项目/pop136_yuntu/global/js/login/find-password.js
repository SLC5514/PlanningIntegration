require(["jquery","general","msg"],function(jquery,general,msg){
	$(function(){
		var def={
            content_def_height:0,
            input_ele:$(".js-input-area"),
            input_ele1:$(".js-form1-ele .js-input-area"),
            input_ele2:$(".js-form2-ele .js-input-area"),
            re:{
                password:/^[a-zA-Z0-9_\-\.!\^\[\]\*\$\(\)\+<>=%#@&]{6,20}$/,    //密码验证
                hanzi:/[^\u4E00-\u9FA5\uF900-\uFA2D]/gi,                        //汉子验证
                mobile:/^1[1-9][0-9]{9}$/,
            },
            is_sub:false,                                                       //是否提交中
            is_get_code:false,                                                  //是否获取验证码中
            agreement_ele:$(".js-agreement-section"),
            bg_ele:$(".js-bg-div"),
            loading_ele:$(".js-loading-div"),                                                                   //加载等待
            bg_ele:$(".js-bg-div"),                                                                             //加载等待

            token:"",
            hashcode:""

        };
        def.content_def_height=$(".js-content-section").height();
        setSty(def.content_def_height);
        def.loading_ele.fadeOut(200);
        def.bg_ele.fadeOut(400);
		// input判断
        def.input_ele.on("focus",function(){
            var error_ele=$(this).siblings("p");
            error_ele.hide();
        }).on("blur",function(){
            checkFunc($(this));
        });

        //限制只能输入数字
        def.input_ele.eq(1).on("input propertychange",function(){
            var re=/\D/g;
            var txt=$(this).val();
            if(re.test(txt)){
                txt=txt.replace(re,"");
                $(this).val(txt);
            }
        });

        // 下一步
        $(".js-next-btn1").on("click",function(){
            stepOneFunc($(this));
            return false;
        });
        // 下一步
        $(".js-next-btn2").on("click",function(){
            stepTwoFunc($(this));
            return false;
        });
        // 回车事件
        $(".js-form1-ele").on("keydown",function(e){
            var keycode=e.keyCode;
            if(keycode==13){
                stepOneFunc($(".js-next-btn1"));
                return false;
            }
        });
        $(".js-form2-ele").on("keydown",function(e){
            var keycode=e.keyCode;
            if(keycode==13){
                stepTwoFunc($(".js-next-btn2"));
                return false;
            }
        });

        // 改变图形验证码
        $(".js-img-code-btn").on("click",function(){
            var str="/user/imgcaptcha/find_code/";
            var t=(new Date()).getTime();
            $(this).attr("src",str+"?t="+t);
        });
        // 获取手机验证码
        $(".js-get-code").on("click",function(){
            getMsgFunc($(this));
            return false;
        });
        // 提交数据
        $(".js-register-btn").on("click",function(){
            subFunc($(this));
            return false;
        });


        function checkFunc(obj,type){
            var index=def.input_ele.index(obj);
            var is_ok=true,txt=obj.val();
            var error_ele=obj.siblings("p");
            var correct_ele=obj.siblings("span");
            txt=txt.replace(/\s/g,"");
            var msg_txt="";

            switch(index){
                case 0:
                    if(txt==""){
                        is_ok=false;
                        msg_txt="请输入用户名";
                    }else if(general.fn.getCharacterLen(txt)<4 || general.fn.getCharacterLen(txt)>20){
                        is_ok=false;
                        msg_txt="4-20位字符，一个汉字是两个字符";
                    }
                    break;
                case 1:
                    if(txt==""){
                        is_ok=false;
                        msg_txt="手机号码为空，请正确填写";
                    }else if(txt.length<11 || def.re.mobile.test(txt)==false){
                        is_ok=false;
                        msg_txt="请输入11位正确手机号码";
                    }
                    break;
                case 2:
                    if(txt==""){
                        is_ok=false;
                        msg_txt="请输入图形验证码";
                    }else if(txt.length<4){
                        is_ok=false;
                        msg_txt="图形验证码不正确";
                    }
                    break;
                case 3:
                    if(txt==""){
                        is_ok=false;
                        msg_txt="请输入短信验证码";
                    }
                    break;
                case 4:
                    if(txt==""){
                        is_ok=false;
                        msg_txt="请输入新密码";
                    }else if(general.fn.getCharacterLen(txt)<6 || general.fn.getCharacterLen(txt)>20 || !def.re.password.test(txt)){
                        is_ok=false;
                        msg_txt="6-20位字符，可由数字、字母、特殊字符组成";
                    }
                    break;
                case 5:
                    if(txt=="" || def.input_ele.eq(4).val()!=txt){
                        is_ok=false;
                        msg_txt="两次密码输入不一致";
                    }
                    break;
                default:
                    break;
            }
            error_ele.text(msg_txt);
            if(is_ok==true){
                error_ele.hide();
                correct_ele.css({"opacity":1});
            }else{
                error_ele.show();
                correct_ele.css({"opacity":0});
            }
            obj.val(txt);
            return is_ok;
        };

        function getMsgFunc(obj){
            if(def.is_get_code==true){return false};
            var account=def.input_ele1.eq(0).val();
            var mobile=def.input_ele1.eq(1).val();
            var img_code=def.input_ele1.eq(2).val();
            var is_next=true;
            def.input_ele1.each(function(i){
                if(i!=3){
                    is_next=checkFunc($(this));
                    return is_next;
                }
            });
            if(is_next==true){
                def.is_get_code=true;
                obj.text("获取中...").addClass("disabled-ele");
                general.fn.subAjax({
                    url:"/user/sendmessage/",
                    data:{
                        "account":account,
                        "act":"find_password",
                        "bind_mobile":mobile,
                        "find_code":img_code
                    },
                    ctp:"application/x-www-form-urlencoded",
                    success:function(data){
                        // $(".js-img-code-btn").click();
                        obj.text("60(S)");
                        
                        var timer=null;
                        timeFn(60);
                        function timeFn(num){
                            timer=setTimeout(function(){
                                clearTimeout(timer);
                                num--;
                                if(num<1){
                                    def.is_get_code=false;
                                    obj.text("重新发送").removeClass("disabled-ele");
                                    $(".js-img-code-btn").click();
                                }else{
                                    obj.text(num+"(S)");
                                    timeFn(num);
                                }
                                
                            },1000);
                        }
                    },
                    error:function(data){
                        obj.text("获取验证码").removeClass("disabled-ele");
                        var ndata=data || {};
                        if(ndata.code!="10002"){
                            $(".js-img-code-btn").click();
                        }
                        def.is_get_code=false;
                    }
                })
            }
        };

        function stepOneFunc(obj){

            if(def.is_sub==true){return false};
            var is_next=true;
            def.input_ele1.each(function(){
                is_next=checkFunc($(this));
                return is_next;
            });
            if(is_next==false){
                return false;
            }else{
                obj.text("提交中...");
                def.is_sub=true;
                var str=$(".js-form1-ele").serialize();
                str=decodeURIComponent(str,true);
                var data=general.fn.getLocationParameter("?"+str);
                
                data.act="find_step1";
                general.fn.subAjax({
                    url:"/user/findpassword/",
                    data:data,
                    ctp:"application/x-www-form-urlencoded",
                    success:function(data){
                        var ndata=data || {};
                        def.token=ndata.data.token || "";
                        def.hashcode=ndata.data.hashcode || "";
                        obj.text("下一步");
                        def.is_sub=false;
                        $(".js-form1-ele").hide();
                        $(".js-form2-ele").show();
                        $(".js-step-p").eq(1).addClass("now-step").siblings(".js-step-p").removeClass("now-step");
                    },
                    error:function(data){
                        obj.text("下一步");
                        def.is_sub=false;
                        var ndata=data || {};
                        if(ndata.code!="10002"){
                            $(".js-img-code-btn").click();
                        }
                    }
                })
            }
        };
        function stepTwoFunc(obj){

            if(def.is_sub==true){return false};
            var is_next=true;
            def.input_ele2.each(function(){
                is_next=checkFunc($(this));
                return is_next;
            });
            if(is_next==false){
                return false;
            }else{
                obj.text("提交中...");
                def.is_sub=true;
                var str=$(".js-form2-ele").serialize();
                
                var data=general.fn.getLocationParameter("?"+str);
                data.act="find_step2";
                data.account=def.input_ele.eq(0).val();
                data.token=def.token;
                data.hashcode=def.hashcode;
                general.fn.subAjax({
                    url:"/user/findpassword/",
                    data:data,
                    ctp:"application/x-www-form-urlencoded",
                    success:function(data){
                        obj.text("下一步");
                        def.is_sub=false;
                        $(".js-form2-ele").hide();
                        $(".js-find-password-success").show();
                        $(".js-find-password-div>h3>span").text("找回密码成功");
                        $(".js-step-p").eq(2).addClass("now-step").siblings(".js-step-p").removeClass("now-step");
                    },
                    error:function(){
                        obj.text("下一步");
                        def.is_sub=false;
                    }
                })
            }
        };



        $(window).on('resize',function(){
            general.fn.throttle(setSty,null,[def.content_def_height]);
        });
        function setSty(nh){
            var wh=document.documentElement.clientHeight || document.body.clientHeight;
            if(wh>nh+159){
                $(".js-content-section").height(wh-159+"px");
            }
        };
	});
});