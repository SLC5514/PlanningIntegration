/*
    #author     lut000
    #date       2017/11/15
    #porpuse    修改手机号
*/
define(["jquery","general","msg"],function(jquery,general,msg){
	$(function(){
		var def={
            input_ele:$(".js-input-area"),
            input_ele1:$(".js-step1-section .js-input-area"),
            input_ele2:$(".js-step2-section .js-input-area"),
            re:{
                mobile:/^1[1-9][0-9]{9}$/
            },
            is_sub:false,                                                       //是否提交中
            is_get_code1:false,                                                 //是否获取验证码中
            is_get_code2:false                                                  //是否获取验证码中

        };

        // input判断
        def.input_ele1.on("focus",function(){
            var error_ele=$(this).siblings("p");
            error_ele.hide();
        }).on("blur",function(){
            checkFunc($(this),def.input_ele1);
        });
        def.input_ele2.on("focus",function(){
            var error_ele=$(this).siblings("p");
            error_ele.hide();
        }).on("blur",function(){
            checkFunc($(this),def.input_ele2);
        });

        //限制只能输入数字
        $(".js-number-only").on("input propertychange",function(){
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
        });
        // 下一步
        $(".js-next-btn2").on("click",function(){
            stepTwoFunc($(this));
        });
        // 改变图形验证码
        $(".js-img-code-btn").on("click",function(){
            var str=$(this).attr("data-src") || "";
            var t=(new Date()).getTime();
            $(this).attr("src",str+"?t="+t);
        });
        // 获取手机验证码
        $(".js-get-code").on("click",function(){
            getMsgFunc($(this));
        });
        // 提交数据
        $(".js-register-btn").on("click",function(){
            subFunc($(this));
        });
        // 回车事件
        $(".js-step1-section").on("keydown",function(e){
            var keycode=e.keyCode;
            if(keycode==13){
            	stepOneFunc($(".js-next-btn1"));
            	return false;
            }
        });
        $(".js-step2-section").on("keydown",function(e){
            var keycode=e.keyCode;
            if(keycode==13){
            	stepTwoFunc($(".js-next-btn2"));
            	return false;
            }
        });
        // 完成刷新
        $(".js-complete-btn").on("click",function(){
            window.location.reload(true);
        });

        function checkFunc(obj,tag){
            var index=tag.index(obj);
            var is_ok=true,txt=obj.val();
            var error_ele=obj.siblings("p");
            txt=txt.replace(/\s/g,"");
            var msg_txt="";

            switch(index){
                case 0:
                	if(txt==""){
                        is_ok=false;
                        msg_txt="请输入图形验证码";
                    }else if(txt.length<4){
                        is_ok=false;
                        msg_txt="图形验证码不正确";
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
                        msg_txt="请输入短信验证码";
                    }
                    break;
                case 3:
                default:
                    break;
            }
            error_ele.text(msg_txt);
            if(is_ok==true){
                error_ele.hide();
            }else{
                error_ele.show();
            }
            return is_ok;
        };

        function getMsgFunc(obj){
        	var key=obj.attr("data-key") || "is_get_code1";
            if(def[key]==true){return false};
            var port_str=key=="is_get_code1"?"/user/sendcodeforbind/":"/user/sendmodbindmobile/";
            var ntag=key=="is_get_code1"?def.input_ele1:def.input_ele2;
            var img_tag=key=="is_get_code1"?$(".js-img-code-btn").eq(0):$(".js-img-code-btn").eq(1);
            var is_next=true,data={}
            ntag.each(function(i){
                if(i==0 || i==1){
                	var nkey=$(this).attr("name") || "";
                	data[nkey]=$(this).val() || "";
                    is_next=checkFunc($(this),ntag);
                    return is_next;
                }
            });
            if(is_next==true){
            	// data.act=key=="is_get_code1"?"bind_step1":"bind_step2";
            	def[key]=true;
                obj.text("获取中...").addClass("disabled-ele");
                general.fn.subAjax({
                    url:port_str,
                    data:data,
                    ctp:"application/x-www-form-urlencoded",
                    success:function(data){

                        obj.text("60(S)");
                        
                        var timer=null;
                        timeFn(60);
                        // $(".js-img-code-btn").click();
                        function timeFn(num){
                            timer=setTimeout(function(){
                                clearTimeout(timer);
                                num--;
                                if(num<1){
                                    def[key]=false;
                                    obj.text("重新发送").removeClass("disabled-ele");
                                    img_tag.click();
                                }else{
                                    obj.text(num+"(S)");
                                    timeFn(num);
                                }
                                
                            },1000);
                        }
                    },
                    error:function(data){
                        obj.text("获取短信验证码").removeClass("disabled-ele");
                        def[key]=false;
                        var ndata=data || {};
                        if(ndata.code!="10002"){
                            img_tag.click();
                        }
                    }
                })
            }
        };

        function stepOneFunc(obj){

            if(def.is_sub==true){return false};
            var is_next=true,data={};
            def.input_ele1.each(function(){
                is_next=checkFunc($(this),def.input_ele1);
                var nkey=$(this).attr("name") || "";
                data[nkey]=$(this).val();
                return is_next;
            });
            if(is_next==false){
                return false;
            }else{
                obj.text("提交中...");
                def.is_sub=true;
                data.act="bind_step1";
                general.fn.subAjax({
                    url:"/user/unbindmobile/",
                    data:data,
                    ctp:"application/x-www-form-urlencoded",
                    success:function(data){
                        obj.text("下一步");
                        def.is_sub=false;
                        $(".js-step1-section").hide();
                        $(".js-step2-section").show();
                        $(".js-div-section>h3").text("修改绑定-修改绑定手机");
                    },
                    error:function(data){
                        obj.text("下一步");
                        def.is_sub=false;
                        var ndata=data || {};
                        if(ndata.code!="10002"){
                            $(".js-img-code-btn").eq(0).click();
                        }
                    }
                })
            }
        };
        function stepTwoFunc(obj){

            if(def.is_sub==true){return false};
            var is_next=true,data={};
            def.input_ele2.each(function(){
                is_next=checkFunc($(this),def.input_ele2);
                var nkey=$(this).attr("name") || "";
                data[nkey]=$(this).val();
                return is_next;
            });
            if(is_next==false){
                return false;
            }else{
                obj.text("提交中...");
                def.is_sub=true;
                // data.act="find_step2";
                data.oldMobile=def.input_ele.eq(1).val();
                general.fn.subAjax({
                    url:"/user/modbind/",
                    data:data,
                    ctp:"application/x-www-form-urlencoded",
                    success:function(data){
                        obj.text("下一步");
                        def.is_sub=false;
                        $(".js-step2-section").hide().siblings(".js-step3-section").show();
                        $(".js-div-section>h3").text("修改绑定-修改成功");
                    },
                    error:function(data){
                        obj.text("下一步");
                        def.is_sub=false;
                        var ndata=data || {};
                        if(ndata.code!="10002"){
                            $(".js-img-code-btn").eq(1).click();
                        }
                    }
                })
            }
        };
	});
});