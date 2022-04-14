
$(function () {
    (function ($) {
		//子账号解绑发送短信验证码
        var sendVerifyCodefunc = function (_this) {
            var postData = {"act":"unbind"};
            $.ajax({
                url: '/member/sendMessage/?'+Math.random(),
                type: 'POST',
                dataType: 'json',
                async: false,
                data: postData
            }).done(function (data) {
                if (data.status == '1') {
                    //发送验证码
                    _this.val('还有60秒');
                    _this.attr("disabled", true);
                    var timeFunc = setInterval(function () {
                        var nowTime = parseInt(_this.val().replace(/[^0-9]/ig, ""));
                        if (nowTime == 0) {
							_this.attr("disabled", false);
							_this.val('获取验证码');
                            clearInterval(timeFunc);
                            return;
                        }
                        else {
                            _this.val(_this.val().replace(/[0-9]+/ig, nowTime - 1));
                        }
                    }, 1000);
                }else {
                    alert(data.info);
                    return;
                }
            });
        };
		//子账号解绑操作
		var confirmUnbind = function (_this) {
            var verifyCode;
			verifyCode =  $.trim($('#verificationCode').val());
            var postData = {verifyCode : verifyCode };
            $.ajax({
                url: '/member/dounbind/?'+Math.random(),
                type: 'POST',
                dataType: 'json',
                async: false,
                data: postData
            }).done(function (data) {
				if(data==1){
					window.location.href="/member/logout/";
				}else{
					alert(data);
				}
            });
        };
        $('#sendUnbindMessage').click(function () {
            sendVerifyCodefunc($(this), false);
        });
        
		//确认解绑按钮提示
        $('#ConfirmUnbind').click(function () {
			var code =  $.trim($('#verificationCode').val());
			if( !code ){
				alert("请输入短信验证码！");
				return false;
			}
			$(".shadow_black").show();
			$(".unbind_layer").show();
        });
		
		//确认解绑
		$('.unbind_yes').click(function () {
            $(".shadow_black").hide();
            $(".unbind_layer").hide();
			confirmUnbind($(this));
        });
		//取消解绑
        $(".unbind_no").click(function(){
            $(".shadow_black").hide();
            $(".unbind_layer").hide();
        })
    })(jQuery);
});