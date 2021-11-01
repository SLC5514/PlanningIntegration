require(["jquery","general","msg"],function(jquery,general,msg){
	$(function(){
		var def={
            content_def_height:0,
            input_ele:$(".js-input-area"),
            re:{
                account:/^[a-zA-Z0-9_@\-&\u4e00-\u9fa5]{2,}$/,                //账号验证
                password:/^[a-zA-Z0-9_\-\.!\^\[\]\*\$\(\)\+<>=%#@&]{6,20}$/,    //密码验证
                hanzi:/[^\u4E00-\u9FA5\uF900-\uFA2D]/,                          //汉子验证
                mobile:/^1[1-9][0-9]{9}$/,
            },
            is_sub:false,                                                       //是否提交中
            is_get_code:false,                                                  //是否获取验证码中

            agreement_ele:$(".js-agreement-section"),
            bg_ele:$(".js-bg-div"),
            loading_ele:$(".js-loading-div"),                                                                   //加载等待
            bg_ele:$(".js-bg-div"),                                                                             //加载等待
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
        def.input_ele.eq(4).on("input propertychange",function(){
            var re=/\D/g;
            var txt=$(this).val();
            if(re.test(txt)){
                txt=txt.replace(re,"");
                $(this).val(txt);
            }
        });
        def.input_ele.eq(2).on("keydown",function(e){
            var code=e.keyCode;
            if(code===32){
                $(this).siblings("p").text("密码不能有空格").show();
                return false;
            }else{
                $(this).siblings("p").hide();
            }
        });
        def.input_ele.eq(3).on("keydown",function(e){
            var code=e.keyCode;
            if(code===32){
                $(this).siblings("p").text("密码不能有空格").show();
                return false;
            }else{
                $(this).siblings("p").hide();
            }
        });

		// 用户协议
        $(".js-agree-btn").on("click",function(){
            if(!$(this).hasClass("no-agree")){
                $(this).addClass("no-agree");
            }else{
                $(this).removeClass("no-agree");
            }
            return false;
        });
        $(".js-agreement-btn").on("click",function(){
            def.agreement_ele.fadeIn(200);
            def.bg_ele.fadeIn(400);

            setEleHeight(def.agreement_ele);
            return false;
        });
        $(".js-sure-agree-btn").on("click",function(){
            def.agreement_ele.fadeOut(200);
            def.bg_ele.fadeOut(400);
            $(".js-agree-btn").removeClass("no-agree");
            return false;
        });
        $(".js-cancel-agree-btn").on("click",function(){
            def.agreement_ele.fadeOut(200);
            def.bg_ele.fadeOut(400);
            return false;
        });
        $(".js-close-btn").on("click",function(){
            def.agreement_ele.fadeOut(200);
            def.bg_ele.fadeOut(400);
            return false;
        });
        // 改变图形验证码
        $(".js-img-code-btn").on("click",function(){
            var str="/user/imgcaptcha/reg_code/";
            var t=(new Date()).getTime();
            $(this).attr("src",str+"?t="+t);
        });
        // 获取手机验证码
        // $(".js-get-code").on("click",function(){
        //     getMsgFunc($(this));
        //     return false;
        // });
        // 提交数据
        $(".js-register-btn").on("click",function(){
            subFunc($(this));
            return false;
        });
        // 回车事件
        $(".js-form-ele").on("keydown",function(e){
            var keycode=e.keyCode;
            if(keycode==13){
                subFunc($(".js-register-btn"));
                return false;
            }
        });

        // 品类下拉
        $('.js-category-area').on('click', function() {
            $(this).next('.select-options').show().data('type', 1);
        }).next('.select-options').on('click', 'li', function() {
            $(this).addClass('on').siblings().removeClass('on').parent().hide().prev('.js-category-area').text($(this).text()).data('id', $(this).data('id')).prev('label').removeClass('label-category');
            checkFunc($('.js-category-area'));
        });
        $(document).on('click', function(e) {
            var e = e || window.event;
            var target = $(e.target);
            if (!target.hasClass('js-category-area') && $('.js-category-area').next('.select-options').data('type') === 1) {
                $('.js-category-area').next('.select-options').hide().data('type', 0);
                checkFunc($('.js-category-area'));
            }
        })

        function checkFunc(obj){
            var index=def.input_ele.index(obj);
            var is_ok=true,txt=obj.prop("tagName") !== 'INPUT' ? obj.text() : obj.val();
            var error_ele=obj.siblings("p");
            var correct_ele=obj.siblings("span");
            txt=txt.replace(/\s/g,"");
            var msg_txt="";

            switch(index){
                case 0:
                    if(txt==""){
                        is_ok=false;
                        msg_txt="请输入用户名";
                    }else if(general.fn.getCharacterLen(txt)<4 || general.fn.getCharacterLen(txt)>20 || !def.re.account.test(txt)){
                        is_ok=false;
                        msg_txt="4-20位字符，一个汉字是两个字符";
                    }
                    break;
                case 1:
                    if(txt==""){
                        is_ok=false;
                        msg_txt="请输入真实姓名";
                    }else if(general.fn.getCharacterLen(txt)<4 || general.fn.getCharacterLen(txt)>20){
                        is_ok=false;
                        msg_txt="4-20位字符，一个汉字是两个字符";
                    }
                    break;
                case 2:
                    if(txt==""){
                        is_ok=false;
                        msg_txt="请输入密码";
                    }else if(general.fn.getCharacterLen(txt)<6 || general.fn.getCharacterLen(txt)>20 || !def.re.password.test(txt)){
                        is_ok=false;
                        msg_txt="6-20位字符，可由数字、字母、特殊字符组成";
                    }
                    break;
                case 3:
                    if(txt=="" || def.input_ele.eq(2).val()!=txt){
                        is_ok=false;
                        msg_txt="两次密码输入不一致";
                    }
                    break;
                case 4:
                    if(txt==""){
                        is_ok=false;
                        msg_txt="手机号码为空，请正确填写";
                    }else if(txt.length<11 || def.re.mobile.test(txt)==false){
                        is_ok=false;
                        msg_txt="请输入11位正确手机号码";
                    }
                    break;
                case 5:
                    if(txt==""){
                        is_ok=false;
                        msg_txt="请输入短信验证码";
                    }
                    break;
                case 6:
                    if(txt==""){
                        is_ok=false;
                        msg_txt="请选择主营品类";
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
            obj.prop("tagName") !== 'INPUT' ? obj.text(txt) : obj.val(txt);
            return is_ok;
        };

        // 验证短信验证码
        var captchaObj_new;
        // 获取验证码事件
        $(".js-get-code").on("click", function() {
            if(def.is_get_code==true){return false};
            var _this = $(this);
            var is_next=true;
            def.input_ele.each(function(i){
                if(i==4){
                    is_next=checkFunc($(this));
                    return is_next;
                }
            });
            if(is_next){
                if(captchaObj_new){
                    captchaObj_new.verify();
                    return;
                }
                // 验证码
                var handler = function(captchaObj){
                    if(captchaObj){
                        captchaObj_new=captchaObj;
                    }
                    captchaObj.onReady(function(){
                        captchaObj.verify(); //显示验证码
                    }).onSuccess(function(){
                        var result = captchaObj.getValidate();
                        getMsgFunc(_this,result.geetest_challenge,result.geetest_validate,result.geetest_seccode)
                    }).onError(function(){
                        
                    })
                }
                $.ajax({
                    url: "/user/geetest/?" + (new Date()).getTime(), 
                    type: "get",
                    dataType: "json",
                    success: function (data) {
                        initGeetest({
                            product: 'bind',
                            gt: data.gt,
                            width:"500px",
                            challenge: data.challenge,
                            new_captcha: data.new_captcha,
                            offline: !data.success, // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                            https: true
                        },handler);
                    }
                });
            }
            
        });

        function getMsgFunc(obj,geetest_challenge,geetest_validate,geetest_seccode){           
            var mobile=def.input_ele.eq(4).val(); 
                obj.text("获取中...").addClass("disabled-ele");
                general.fn.subAjax({
                    url:"/user/sendmessage/",
                    data:{
                        "act":"reg",
                        "mobile":mobile,
                        'geetest_challenge': geetest_challenge,
                        'geetest_validate': geetest_validate,
                        'geetest_seccode': geetest_seccode
                    },
                    ctp:"application/x-www-form-urlencoded",
                    success:function(data){

                        obj.text("60(S)");
                        def.is_get_code=true;
                        var timer=null;
                        timeFn(60);
                        // $(".js-img-code-btn").click();
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
        };

        function subFunc(obj){
            if(def.is_sub==true){return false};
            var is_next=true;
            def.input_ele.each(function(){
                is_next=checkFunc($(this));
                return is_next;
            });
            if(is_next==false){
                return false;
            }else if($(".js-agree-btn").hasClass("no-agree")){
                msg.msg({
                    txt:"请先同意用户协议"
                },1200);
                return false;
            }else{
                obj.text("提交中...");
                def.is_sub=true;
                var str=$(".js-form-ele").serialize();
                str += '&main_category=' + $('.js-category-area').data('id');
                str=decodeURIComponent(str,true);
                var data=general.fn.getLocationParameter("?"+str);
                general.fn.subAjax({
                    url:"/user/doregister/",
                    data:data,
                    ctp:"application/x-www-form-urlencoded",
                    success:function(data){
                        // console.log(data);
                        obj.text("立即注册");
                        def.is_sub=false;
                        window.location.href='/user/regcomplete/';                        
                    },
                    error:function(data){
                        var ndata=data || {};
                        if(ndata.code!="10002"){
                            $(".js-img-code-btn").click();
                        }
                        obj.text("立即注册");
                        def.is_sub=false;
                        def.input_ele.eq(5).siblings("span").css({"opacity":0});
                    }
                })
            }
        };


        function setEleHeight(obj){
            var oh=obj.outerHeight();
            var wh=document.documentElement.clientHeight;
            var ntop=(wh-oh)/2<0?20:(wh-oh)/2;
            obj.css({"top":ntop+"px"});
        };



        $(window).on('resize',function(){
            general.fn.throttle(setSty,null,[def.content_def_height]);
            general.fn.throttle(setEleHeight,null,[def.agreement_ele]);
        });
        function setSty(nh){
            var wh=document.documentElement.clientHeight || document.body.clientHeight;
            if(wh>nh+159){
                $(".js-content-section").height(wh-159+"px");
            }
        };
	});
});