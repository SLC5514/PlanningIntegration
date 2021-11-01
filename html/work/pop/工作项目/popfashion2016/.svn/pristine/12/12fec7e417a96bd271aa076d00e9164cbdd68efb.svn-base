
    $(function(){
        var wait = 60;  //停留时间
        //初始化页面样式  class="cur"   class="Scur"  class="lastdl"
        $('#icon_1').addClass('cur');
        $('#icon_2, #icon_3').addClass('lastdl');

        var sendVerifyCodefunc = function (_this, accountName, freeCellphone, is_voice) {
            var cellphonePatt = /^1\d{10}$/;
            if (freeCellphone == '' || !cellphonePatt.test(freeCellphone)) {
                alert('手机号码格式错误');
                return false;
            }
            var postData = {"account": accountName, "bind_mobile": freeCellphone,"act":"findpassword"};
            if (is_voice) {
                postData.is_voice = is_voice;
            }
            $.ajax({
                url: '/member/sendMessage/?'+Math.random(),
                type: 'post',
                dataType: 'json',
                async: false,
                data: postData
            }).done(function (data) {
                if (data.status === 1) {
                    if (is_voice) {
                        _this.val('60秒后可重新获取').removeClass('Prompt_btn').addClass('Prompt_btn_gray').attr('disabled', 'disabled');
                    } else {
                        _this.val('请'+wait+'秒后重新获取');
                    }
                    //按钮禁用
                    _this.attr("disabled", true);
                    var timeFunc = setInterval(function () {
                        var nowTime = parseInt(_this.val().replace(/[^0-9]/ig, ""));
                        if (nowTime == 0) {
                            if (is_voice) {
                                _this.val('重新获取语音验证码').removeClass('Prompt_btn_gray').addClass('Prompt_btn').removeAttr('disabled');
                            } else {
                                _this.attr("disabled", false);
                                _this.val('获取验证码');
                                $('.J_VoiceLayer').removeClass('hide');
								$('.voice_bg').show();
                            }
                            clearInterval(timeFunc);
                            return;
                        }
                        else {

                            _this.val(_this.val().replace(/[0-9]+/ig, nowTime - 1));
                        }
                    }, 1000);
                } else if( data.status == 2 ){
                    alert( "您的访问频率过高, 请您稍后再试!" );
                    return false;
                } else if(data.status == 3) {
                    alert('手机号码格式错误');
                    return false;
                } else if(data.status == 4) {
                    alert('短信发送失败，请稍后再试');
                    return false;
                } else if(data.status == 5){
                    alert( "对不起，您未进行绑定操作或绑定手机输入错误，无法获得密码，请联系客服" );
                    return false;
                }
            });
        };


        /**
         * 设计师专属账号密码找回，发送短信验证码
         * @param _this
         * @param child_account
         * @returns {boolean}
         */
        var sendChildVerifyCodefunc = function (_this, freeCellphone) {
            var cellphonePatt = /^1\d{10}$/;
            if (freeCellphone == '' || !cellphonePatt.test(freeCellphone)) {
                layer.alert('个人手机号格式不正确，必须是手机号码');
                return false;
            }
            var postData = {"account": freeCellphone, "act":"findchildpassword"};

            $.ajax({
                url: '/member/sendMessage/?'+Math.random(),
                type: 'post',
                dataType: 'json',
                async: false,
                data: postData
            }).done(function (data) {
                if (data.status == 1) {
                    _this.val('请'+wait+'秒后重新获取');
                    //按钮禁用
                    _this.attr("disabled", true);
                    var timeFunc = setInterval(function () {
                        var nowTime = parseInt(_this.val().replace(/[^0-9]/ig, ""));
                        if (nowTime == 0) {
                            _this.attr("disabled", false);
                            _this.val('获取短信验证码');
                            clearInterval(timeFunc);
                        }
                        else {
                            _this.val(_this.val().replace(/[0-9]+/ig, nowTime - 1));
                        }
                    }, 1000);
                } else if( data.status == 2 ){
                    alert( "您的访问频率过高, 请您稍后再试!" );
                    return false;
                } else if(data.status == 3) {
                    alert('手机号码格式错误');
                    return false;
                } else if(data.status == 4) {
                    alert('短信发送失败，请稍后再试');
                    return false;
                } else if(data.status == 5){
                    alert( "对不起，您未进行绑定操作或绑定手机输入错误，无法获得密码，请联系客服" );
                    return false;
                }
            });
        };


		//手机提示
		$('#mobileprompt').click(function(){
			var account = $.trim($('#account').val());
			$.ajax({
				type:"POST",
				url:"/member/findpassword/bindingMobile/?"+Math.random(),
				data:{"account":account},
				success:function(msg){
					if( msg != "" ){
                        $(".fg_tel_layer").show();
                        $(".fg_tel_info").find("em").html(msg);
                        $(".fg_tel_info").show();
					}
					else{
                        $(".fg_tel_layer").show();
                        $(".no_tel_num").show();
					}
				}
			});
		});


		//主账号找回密码-发送手机验证码
		$('#sendmobilecode').click(function(){
            var account = $.trim($('#account').val());
            var bind_mobile = $.trim($('#bind_mobile').val());
            var main_img_code = $.trim($('#main_img_code').val());

            if( account == '') {
                layer.alert("账号名不能为空");
                return false;
            }
            if( bind_mobile == '' ) {
                layer.alert("绑定手机号不能为空");
                return false;
            }
            if( main_img_code != $.cookie('maincode') ) {
                layer.alert("图形验证码不正确");
                return false;
            }
            sendVerifyCodefunc($(this),account,bind_mobile,false);
		});


		//专属账号找回密码-发送手机验证码
		$('#sendmesscode').click(function(){
            var child_account = $.trim($('#child_account').val());
            var child_img_code = $.trim($('#child_img_code').val());

            if( child_account == '') {
                layer.alert("个人手机号不能为空");
                return false;
            }
            if( child_img_code != $.cookie('childcode') ) {
                layer.alert("图形验证码不正确");
                return false;
            }
            sendChildVerifyCodefunc($(this), child_account);
		});


        //找回注册账号密码step1提交
        $('#mainstep1submit').click(function(){
            var account = $.trim($('#account').val());
            var bind_mobile = $.trim($('#bind_mobile').val());
            var valid_code = $.trim($('#valid_code').val());

            if( account == '') {
                layer.alert("账号名不能为空");
                return false;
            }
            if( bind_mobile == '' ) {
                layer.alert("绑定手机号不能为空");
                return false;
            }
            if( valid_code == '' ) {
                layer.alert("短信验证码不能为空");
                return false;
            }

            $.ajax({
                type:"POST",
                url:"/member/findpassword/findpassword_submit/?"+Math.random(),
                dataType:'json',
                data:{"account":account,"act":"step1","bind_mobile":bind_mobile,"valid_code":valid_code},
                success:function(e){
                   if(e.status==0){
                       layer.alert('请先获取手机验证码并填写验证码');
                   }
                   else if(e.status==1){
                       layer.alert('手机验证码已过期，请重新获取验证码');
                   }
                   else if(e.status==2){
                       layer.alert('该手机验证码已使用过，请重新获取验证码');
                   }
                   else if(e.status==3){
                       layer.alert('手机验证码错误，请重新填写');
                   }
                   else if(e.status==4){
                       layer.alert('对不起，您未进行绑定操作或绑定手机输入错误，无法获得密码，请联系客服');
                   }
                   else if(e.status==5){
                       window.location = "/member/findpassword/findpassword_2/?hashcode=" + e.data;
                   }
                }
            });
        });


        //找回设计师专属账号密码step1提交
        $('#childstep1submit').click(function(){
            var child_account = $.trim($('#child_account').val());
            var child_code = $.trim($('#child_code').val());

            if (child_account == '' || ! /^1\d{10}$/.test(child_account)) {
                layer.alert('个人手机号格式不正确，必须是手机号码');
                return false;
            }
            if( child_code == '' ) {
                layer.alert("短信验证码不能为空");
                return false;
            }

            $.ajax({
                type:"POST",
                url:"/member/findpassword/findpassword_submit/?"+Math.random(),
                dataType:'json',
                data:{"account":child_account,"act":"childstep1","valid_code":child_code},
                success:function(e){
                   if(e.status==0){
                       layer.alert('请先获取手机验证码并填写验证码');
                   }
                   else if(e.status==1){
                       layer.alert('手机验证码已过期，请重新获取验证码');
                   }
                   else if(e.status==2){
                       layer.alert('该手机验证码已使用过，请重新获取验证码');
                   }
                   else if(e.status==3){
                       layer.alert('手机验证码错误，请重新填写');
                   }
                   else if(e.status==4){
                       layer.alert('对不起，您未进行绑定操作或绑定手机输入错误，无法获得密码，请联系客服');
                   }
                   else if(e.status==5){
                       window.location = "/member/findpassword/findpassword_2/?hashcode=" + e.data;
                   }
                }
            });
        });


        //step2提交
        $('#step2submit').click(function(){
            var pass1 = $('#pass1').val();
            var pass2 = $('#pass2').val();
            var hashcode = $('#hashcode').val();
            $.ajax({
                type:"POST",
                url:"/member/findpassword/findpassword_submit/?"+Math.random(),
                data:{"act":"step2","hashcode":hashcode,"pass1":pass1,"pass2":pass2},
                success:function(e){
                   if(e==0){
                        layer.alert('两次密码输入的不一致');
                   }
                   else if(e==1){
                        layer.alert('对不起，该密码不符合系统要求，请尝试设置其它密码');
                   }
                   else if(e == 2 || e == 4){
                       window.location = "/member/findpassword/findpassword_3/";
                   }
                   else if(e==3){
                        layer.alert('系统发生错误，稍后重试');
                   }
                }
            });
        });
    });
