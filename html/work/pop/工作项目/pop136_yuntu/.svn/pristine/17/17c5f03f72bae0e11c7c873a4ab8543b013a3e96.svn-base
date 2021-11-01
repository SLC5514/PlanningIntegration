/*
    #author     lut000
    #date       2017/11/16
    #porpuse    修改密码
*/
define(["jquery","general","msg"],function(jquery,general,msg){
	$(function(){
		var def={
            re:{
                password:/^[a-zA-Z0-9_\-\.!\^\[\]\*\$\(\)\+<>=%#@&]{6,20}$/     //密码验证
            },
            input_ele:$(".js-password-section").find(".js-input-area"),
            list_ele:$(".js-strength-list>li"),
            is_sub:false                                                        //是否提交中
        };

        // input判断
        def.input_ele.on("focus",function(){
            var error_ele=$(this).siblings("p");
            error_ele.hide();
        }).on("blur",function(){
            checkFunc($(this));
        });
        $('.js-new-password').on("keyup",function (e){
		    var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g"); 
			var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g"); 
			var enoughRegex = new RegExp("(?=.{6,}).*", "g"); 


		    var nindex=1;
		    if (false == enoughRegex.test($(this).val())){
		        nindex=4;
		    }else if (strongRegex.test($(this).val())){
		        nindex=3;
		    }else if (mediumRegex.test($(this).val())){
		        nindex=2;
		    }else{
		        nindex=1;
		    }
		    def.list_ele.each(function(i){
		    	if(nindex!=4){
		    		if(i<nindex){
		    			$(this).addClass("now-light");
		    		}else{
		    			$(this).removeClass("now-light");
		    		}
		    	}else{
		    		$(this).removeClass("now-light");
		    	}
		    	
		    });
		    return true;
		});
        // 提交数据
        $(".js-sub-change-password").on("click",function(){
            subFunc($(this));
        });
        // 完成刷新
        $(".js-complete-btn").on("click",function(){
            window.location.reload(true);
        });

        def.input_ele.eq(1).on("keydown",function(e){
            var code=e.keyCode;
            if(code===32){
                $(this).siblings("p").text("密码不能有空格").show();
                return false;
            }else{
                $(this).siblings("p").hide();
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

        // 回车事件
        $(".js-password-section").on("keydown",function(e){
            var keycode=e.keyCode;
            if(keycode==13){
            	subFunc($(".js-sub-change-password"));
            	return false;
            }
        });


        function checkFunc(obj,type){
            var index=def.input_ele.index(obj);
            var is_ok=true,txt=obj.val();
            var error_ele=obj.siblings("p");
            txt=txt.replace(/\s/g,"");
            var msg_txt="";

            switch(index){
	            case 0:
	                if(txt==""){
	                    is_ok=false;
	                    msg_txt="请输入原密码";
	                }else if(general.fn.getCharacterLen(txt)<6 || general.fn.getCharacterLen(txt)>20){
	                    is_ok=false;
	                    msg_txt="6-20位字符，可由数字、字母、特殊字符组成";
	                }
	                break;
                case 1:
                    if(txt==""){
                        is_ok=false;
                        msg_txt="请输入新密码";
                    }else if(general.fn.getCharacterLen(txt)<6 || general.fn.getCharacterLen(txt)>20 || !def.re.password.test(txt)){
                        is_ok=false;
                        msg_txt="可以由6~20个字符字母、数字、特殊字符组成，请注意大小写";
                    }
                    break;
                case 2:
                    if(txt==""){
                        is_ok=false;
                        msg_txt="请再次输入密码进行确认，请注意大小写";
                    }else if(def.input_ele.eq(1).val()!=txt){
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
            }else{
                error_ele.show();
            }
            obj.val(txt);
            return is_ok;
        };

        

        function subFunc(obj){

            if(def.is_sub==true){return false};
            var is_next=true,data={};
            def.input_ele.each(function(){
            	var nkey=$(this).attr("name") || "";
                is_next=checkFunc($(this));
                data[nkey]=$(this).val();
                return is_next;
            });
            if(is_next==false){
                return false;
            }else{
                obj.text("提交中...");
                def.is_sub=true;
                
                general.fn.subAjax({
                    url:"/user/modpwd/",
                    data:data,
                    ctp:"application/x-www-form-urlencoded",
                    success:function(data){
                        obj.text("提交修改");
                        def.is_sub=false;
                        $(".js-change-password-div").hide().siblings(".js-step3-section").show();
                    },
                    error:function(){
                        obj.text("提交修改");
                        def.is_sub=false;
                    }
                })
            }
        };
	});
});