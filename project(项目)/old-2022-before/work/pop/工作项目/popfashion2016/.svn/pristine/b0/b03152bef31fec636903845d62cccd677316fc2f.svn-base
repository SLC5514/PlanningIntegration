/*
    fanglinna
    20191217
*/
$(function() {

    // 验证手机号
    var reg = {
        mobile: "^1\\d{10}$",
        password: "^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{6,20}$"
    }

    var def = {
        pwd_display: false,
        is_send: false,
        url: 'https://member.pop136.com',
        is_login: false,
        is_reg : false,
        is_next: false
    };

    var free_login_cookie = $.cookie("FREE_LOGIN");
    if(free_login_cookie == null){
        $(".js-free-login-bg,.js-free-login-layer,.js-phone-infor-box").show();
    } 

    $(".js-free-login-web>li").on('click', function() {
        var website = $(this).data("website");
        $.ajax({
            url: def.url + '/interface/getFreeLoginUrl/',
            type: 'get',
            dataType: 'jsonp',
            data: {'website':website,'encrypt_user_id':encrypt_user_id,'token':free_login_cookie},
            success: function(data){
                if(data.code == 0){
                    window.open(data.data.url);
                }else if(data.code == 1001){
                    $.cookie("FREE_LOGIN","", {path: '/', domain: 'pop-fashion.com'});
                    $(".js-free-login-bg,.js-free-login-layer,.js-phone-infor-box").show();
                }else{
                    oCommon.showTips(data.msg);
                }
            }
        })
    });


    // 重置弹窗
    function initFunc() {
        $(".login-infor-box").hide();
    }

    var cellphone = $(".js-phone-input");
    var password = $(".js-password-input");
    var sendcode = $(".js-sendcode-input");
    var login_password = $(".js-password-login");
    var cellphone_val;
    var password_val;
    var login_psw_val;

    // 密码显示隐藏
    $(".js-pwd-display").on("click", function () {
        var inp, val;
        if (def.pwd_display) {
            inp = $(".js-pwd-box").find('input')
            val = inp.val();
            $(".js-pwd-box").html('<input type="password" class="js-password-input" value="' + val + '" name="password" id="pw" placeholder="请输入密码，超过6位字符"></input>');
            $(this).addClass("pwd-display");
            def.pwd_display = false;
        } else {
            inp = $(".js-pwd-box").find('input')
            val = inp.val();
            if (val == "") {
                $(".js-pwd-box").html('<input type="text" class="js-password-input" name="password" id="pw" placeholder="请输入密码，超过6位字符"></input>');
            } else {
                $(".js-pwd-box").html('<input type="text" class="js-password-input" value="' + val + '" name="password" id="pw" placeholder="请输入密码，超过6位字符"></input>');
            }
            $(this).removeClass("pwd-display");
            def.pwd_display = true;
        }
    });

    // 手机号验证
    var cellphoneFunc = function(self) {
        var span = self.parent("div").siblings('span.input-msg');
        cellphone_val = $.trim(self.val());
        if (!cellphone_val || !RegExp(reg.mobile, 'ig').test(cellphone_val)) {
            span.show();
            $(".js-step-tips").addClass("gray-status");
            return false;
        }
        else {
            span.hide();
            $(".js-step-tips").removeClass("gray-status");            
            return true;
        }
    }
    // 密码验证
    var passwordFunc = function(self) {
        var span = self.parent("div").siblings('span.input-msg');
        password_val = $.trim(self.val());
        if (!password_val || !RegExp(reg.password, 'ig').test(password_val)) {
            span.show();
            $(".js-bind-tips").addClass("gray-status");
            return false;
        }
        else {
            span.hide();
            $(".js-bind-tips").removeClass("gray-status");            
            return true;
        }
    }

    cellphone.on("blur", function() {
        cellphoneFunc($(this));
    });

    $("body").on("blur", '.js-password-input', function() {
        passwordFunc($(".js-password-input"));
    });

    login_password.on("blur", function() {
        var span = $(this).parent("div").siblings('span.input-msg');
        var psw_val =  $.trim($(this).val());
        if(psw_val == ""){
            span.show();
            $(".js-bind-tips").addClass("gray-status");
            return false;
        }else{
            span.hide();
            $(".js-bind-tips").removeClass("gray-status");
            return true;
        }
    });

    login_password.keyup(function() {
        $(".js-use-tips").removeClass("gray-status");
    });

    cellphone.keyup(function() {
        $(".js-step-tips").removeClass("gray-status");
    })

    $("body").on("keyup", ".js-password-input", function() {
        $(".js-bind-tips").removeClass("gray-status");
    })

    // 重新输入短信验证码
    sendcode.on("focus", function() {
        $(this).parent().siblings(".js-code-input-msg").hide();
    });

    // 手机号判断
    $(".js-step-tips").on("click", function() {
        if(!cellphoneFunc(cellphone) || def.is_next){
            return false;
        }
        def.is_next = true;
        cellphone_val = cellphone.val();   
        $.ajax({
            url: def.url + '/interface/childAccountStatus/',
            type: 'get',
            dataType: 'jsonp',
            data: {'mobile':cellphone_val,'encrypt_user_id':encrypt_user_id},
            success: function(data){
                var account = data.data.account || "";
                initFunc();
                if(data.code == 0){
                    $(".js-password-infor-box").show()
                }else if(data.code == 2){                    
                    $(".js-register-infor-box").show();
                }else{
                    $(".js-warming-tips-box").show();
                    $(".js-free-login-layer").hide();
                    $(".js-main-account").html(account);
                }
                def.is_next = false;
            }
        })
    });

    // 换手机号
    $(".js-replace-phone").on("click", function() {
        $(".js-free-login-bg,.js-free-login-layer,.js-phone-infor-box").show();
        $(".js-warming-tips-box").hide();
        cellphone.val("");
    });

    // 注册
    $(".js-bind-tips").on("click", function() {
        if(!passwordFunc($(".js-password-input")) || $.trim(sendcode.val()) == "" || def.is_reg){
            return false;
        }
        def.is_reg = true;
        sendcode_val = $.trim(sendcode.val());
        password_val = $.trim($(".js-password-input").val());
        $.ajax({
            url: def.url + '/interface/registerChildAccount/',
            type: 'get',
            dataType: 'jsonp',
            data: {'mobile':cellphone_val,'password':password_val,'captcha':sendcode_val,'encrypt_user_id':encrypt_user_id},
            success: function(data){
                if(data.code == 0){
                    var token = data.data.token || ""
                    $.cookie('FREE_LOGIN', token, { expires: 30, path: '/', domain: 'pop-fashion.com'});
                    window.location.reload();
                    $(".js-free-login-bg,.js-free-login-layer").hide();
                }else{
                    $('.js-code-input-msg').html(data.message || data.msg).show();
                }
                def.is_reg = false;
            }
        })
        
    })

    
    // 验证短信
    var handler = function (captchaObj) {
        def.is_load_Geetest = captchaObj;
        def.is_load_Geetest.onReady(function() {
            def.is_load_Geetest.verify(); //显示验证码
        }).onSuccess(function () {
            var result = def.is_load_Geetest.getValidate();
            captchaSend(result.geetest_challenge, result.geetest_validate, result.geetest_seccode)
        }).onError(function () {

        })        
    }

    // 获取验证码
    $(".js-send-btn").on("click", function () {
        if (def.is_send || !cellphoneFunc(cellphone)) {
            return false;
        }
        if(def.is_load_Geetest){
            def.is_load_Geetest.verify(); //显示验证码
            return;
        }
        $.ajax({
            url: def.url + "/captcha/geetest/?" + (new Date()).getTime(),
            type: "get",
            dataType: "jsonp",
            success: function (data) {
                initGeetest({
                    product: 'bind',
                    gt: data.gt,
                    width: "500px",
                    challenge: data.challenge,
                    new_captcha: data.new_captcha,
                    offline: !data.success, // 表示用户后台检测极验服务器是否宕机，一般不需要关注
                    https: true
                }, handler);
            }
        });           
    });

    // 发送验证码
    var captchaTime = 60, captchaInter;
    var captchaSend = function (geetest_challenge, geetest_validate, geetest_seccode) {
        if(def.is_send){
            return;
        }
        def.is_send = true; 
        var url = def.url + "/captcha/sendSms";
        var obj = {
            mobile: cellphone_val,
            website: 1,
            sence: 'CHILD_USER_REGISTER',
            geetest_challenge: geetest_challenge,
            geetest_validate: geetest_validate,
            geetest_seccode: geetest_seccode
        };
        $.ajax({
            url: url,
            type: 'get',
            dataType: 'jsonp',
            data: obj,
            success: function(data) {
                if (data.code == 0) {
                    $('.js-errorbox').hide();
                    clearInterval(captchaInter);
                    captchaInter = setInterval(function () {
                        captchaTime--;
                        if (captchaTime < 0) {
                            def.is_send = false;
                            captchaTime = 60;
                            $('.js-send-btn').text('获取验证码');
                            $('.js-send-btn').css('backgroundColor', '#232f3c');
                            clearInterval(captchaInter);
                        } else {
                            $('.js-send-btn').text('已发送(' + captchaTime + 's)');
                            $('.js-send-btn').css('backgroundColor', '#DBDDE0');
                        }
                    }, 1000);
                } else {
                    def.is_send = false;
                    $('.js-code-input-msg').html(data.message || data.msg).show();
                }
            },error: function() {
                def.is_send = false;
            }
        })
    }
    
    // 登录
    $(".js-use-tips").on("click", function() {
        if(login_password.val() == "" || def.is_login) {
            return false;
        }
        def.is_login = true;
        login_psw_val = $.trim(login_password.val());
        var obj = {
            encrypt_user_id: encrypt_user_id,
            mobile: cellphone_val,
            password: login_psw_val
        }
        $.ajax({
            url: def.url + '/interface/loginChildAccount/',
            type: 'get',
            dataType: 'jsonp',
            data: obj,
            success: function(data) {
                if(data.code == 0){
                    var token = data.data.token || "";
                    $.cookie('FREE_LOGIN', token, { expires: 30, path: '/', domain: 'pop-fashion.com'});
                    window.location.reload();
                    $(".js-free-login-bg,.js-free-login-layer").hide();                    
                }else{
                    $(".js-login-input-msg").html(data.message || data.msg).show();
                }
                def.is_login = false;
            }
        })
    });
});