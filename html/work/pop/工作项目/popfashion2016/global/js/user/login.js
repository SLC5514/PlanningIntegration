$(function() {
    var def = {
        token: "",
        click_login: false, //点击登录按钮
        // clientWidth: '410',
        url: '//member.pop136.com',
        loginType: '1',
        codeState: false,
        data: {},
        isPwdem: false
    }
    _def.clientWidth = '410_76';

  
/* 登录改版 */
    //判断Array类型
    function isArrayFn(value) {
        if (typeof Array.isArray === "function") {
            return Array.isArray(value);
        } else {
            return Object.prototype.toString.call(value) === "[object Array]";
        }
    }
    $('.js-forget-pwd a').on('click', function () {
        var url = $(this).attr('data-url');
        top.location.href = url;
    })
    //登录
    $('.js-login-btn').on('click', function () {
        var _this=$(this);
        var status = _this.data('status');
        var user = $("#account1").val();
        var pwd = $("#password1").val();
        if (def.loginType == '1') {
            if(user == ''){
                $('.errorbox10').html('<i></i><span>用户名不能为空</span>').show();
                return;
            }else if(pwd == ''){
                $('.errorbox10').html('<i></i><span>密码不能为空</span>').show();
                return;
            }
            def.data['account'] = user;  //账号
            def.data['password'] = pwd;
        } else if (def.loginType == '2') {
            if(user == ''){
                $('.errorbox10').html('<i></i><span>手机号不能为空</span>').show();
                return;
            }else if(pwd == ''){
                $('.errorbox10').html('<i></i><span>密码不能为空</span>').show();
                return;
            }
            def.data['mobile'] = user;   //手机号
            def.data['password'] = pwd   //密码
        } else if (def.loginType == '3') {
            def.data['mobile'] = user;
            def.data['captcha'] = pwd    //验证码
        }
        def.data['type'] = def.loginType;   //登录方式
        def.data['website'] = '1'; //登录站点
        def.data['dragtoken'] = _def.slide_Id;
        def.data['remember'] = $('.js-free-login1').is(':checked') ? 1 : 0;
        $(".js-login-btn").text("提交中...");
        var url = def.url + '/login?' + Math.random();
        $.ajaxFun(url, def.data, function (data) {
            if (data.code == '0') {
                var userList = data.data.user;
                if (isArrayFn(userList)) {
                    $('.chooseUser').show();
                    $('.chooseUser').css('opacity', '1');
                    $('.js-userName1').each(function (index) {
                        $(this).text(userList[index].account);
                        $(this).prev().attr('data-id', userList[index].id);
                    })
                    $('#jsJwt1').val(data.data.jwt);
                    //默认登录第一个账号
                    $('#userId1').val(userList[0].id);
                } else {
                    var POP_USER = data.data.jwt;
                    var POP_SSID = data.data.POP_SSID;
                    $.cookie('NO_REFRESH_JWT','1',{path:'/',domain: 'pop-fashion.com',secure: false,raw:false });
                    $.cookie('POP_USER', POP_USER, { expires: data.data.expire, path: '/', domain: 'pop-fashion.com', secure: false, raw: false });
                    $.cookie('POP_SSID', POP_SSID, { expires: data.data.expire, path: '/', domain: 'pop-fashion.com', secure: false, raw: false });
                    //跳转猜你喜欢
                    if(status){
                        location.href = '/patterns/patternRecommend/';
                        return;
                    }
                    // IP限制页面跳转到首页
                    var ipInfo = $('#ipInfo').val();
                    if (ipInfo=='true') {
                        location.href = '//www.pop-fashion.com/';
                    } else {
                        location.reload();
                    }
                }
            } else {
                $('.errorbox10').html('<i></i><span>'+data.msg+'</span>').show();
                var box=_this.prev().prev().find('.js-click-btn');
                if (data.errorCode == 'validation.captcha.slide.need' || data.errorCode == 'validation.captcha.slide.incorrect') {
                    _def.clientWidth = "410_76";
                    box.show();
                    $('.check-free-login').removeClass('stu');
                    $('.pop_loginbg').removeClass('stu');
                    $('.js-slip-code').show();
                }
                box.html('点击按钮完成图形验证');
                box.removeClass('stu');
                box.attr('data-state','1');
            }
            $(".js-login-btn").text("立即登录");
        })
    })
    //登录方式切换
    $('.js-loginClass span').on('click', function () {
        $(this).siblings().removeClass('action');
        $(this).addClass('action');
        def.loginType = $(this).attr('data-type');
        $('#login-type1').val(def.loginType);
        $('.errorbox10').hide();
        $('.js-slip-code').hide();
        $('.check-free-login').addClass('stu');
        $('.pop_loginbg').addClass('stu');
        if (def.loginType == "1") {
            $('.js-user1').html('用户名');
            $('.js-paw1').html('登录密码');
            $('#account1').attr('placeholder', '请输入用户名');
            $('#account1').siblings('.placeholder').text('请输入用户名');
            $('#account1').val('');
            $('#password1').attr('placeholder', '请输入登录密码');
            $('#password1').val('');
            $('.js-code-btn1').hide();
            $('#pwd-em1').show();
            if(def.isPwdem){
                $('#password1').attr('type', 'password');
            }
        } else if (def.loginType == "2") {
            $('.js-user1').html('手机号');
            $('.js-paw1').html('登录密码');
            $('#account1').attr('placeholder', '请输入手机号');
            $('#account1').siblings('.placeholder').text('请输入手机号');
            $('#account1').val('');
            $('#password1').attr('placeholder', '请输入登录密码');
            $('#password1').val('');
            $('.js-code-btn1').hide();
            $('#pwd-em1').show();
            if(def.isPwdem){
                $('#password1').attr('type', 'password');
            }
        } else if (def.loginType == "3") {
            $('.js-code-btn').show();
            $('#pwd-em1').hide();
            $('#password1').attr('type', 'text');
            $('#password1').attr('placeholder', '请输入验证码');
            $('#account1').attr('placeholder', '请输入手机号');
            $('#password1').val('');
        }
    })
    //选择账号
    $(".js-user-close").on('click', function () {
        $('.chooseUser').hide();
        $('.chooseUser').css('opacity', '0');
    })
    $('.js-default1').on('click', function () {
        if ($(this).hasClass('i-action')) {
            $('.js-default1').removeClass('i-action');
            $(this).parents('.account').next().hide();
            $('.js-confirm-btn1').removeClass('btn-action');
            $('.userList').css('paddingTop','35px');
            $('#userId1').val('');
            //清除默认登陆此账号状态
            $('.js-default-action1 i').removeClass('i-action');
            $("#userDef1").val(false);
            def.isChooseUser = false;
        } else {
            $('.js-default1').removeClass('i-action');
            $(this).addClass('i-action');
            $('.js-default-action1').hide();
            $(this).parents('.account').next().show();
            $('.js-confirm-btn1').addClass('btn-action');
            $('#userId1').val($(this).attr('data-id'));
            $('.userList').css('paddingTop','15px');
            //清除默认登陆此账号状态
            $('.js-default-action1 i').removeClass('i-action');
            $("#userDef1").val(false);
            def.isChooseUser = true;
        }
    })
    $('.js-default-action1 i').on('click',function(){
        if($(this).hasClass('i-action')){
            $("#userDef1").val(false);
            $('.js-default-action1 i').removeClass('i-action');
        }else{
            $("#userDef1").val(true);
            $('.js-default-action1 i').removeClass('i-action');
            $(this).addClass('i-action');
        }
        
    })
    //选中账号登录
    $('.js-confirm-btn1').on('click', function () {
        var url = def.url + '/login/chooseUser'
        var obj = {
            'loginId': $('#userId1').val(),        //登录账号ID
            'default': $('#userDef1').val() == 'true' ? '1' : '0',       //是否默认登录此账号
            'jwt': $('#jsJwt1').val(),            //登录返回码
            "website": '1'         //站点
        }
        $.ajaxFun(url, obj, function (data) {
            if (data.code == '0') {
                var POP_USER = data.data.jwt;
                $.cookie('NO_REFRESH_JWT','1',{path:'/',domain: 'pop-fashion.com',secure: false,raw:false });
                $.cookie('POP_USER',POP_USER,{ expires:data.data.expire,path:'/',domain: 'pop-fashion.com',secure: false,raw:false });
                // IP限制页面跳转到首页
                var ipInfo = $('#ipInfo').val();
                if (ipInfo=='true') {
                    location.href = '//www.pop-fashion.com/';
                } else {
                    location.reload();
                }
            } else {
                $('.errorbox10').html('<i></i><span>'+data.msg+'</span>').show();
            }
        })
    })
    //密码可见
    $('#pwd-em1').on("click", function () {
        if (!$('#pwd-em1').hasClass('pwd-display')) {
            $('#pwd-em1').addClass('pwd-display');
            $("#password1").attr('type', 'text');
            def.isPwdem = false;
        } else {
            $('#pwd-em1').removeClass('pwd-display');
            $("#password1").attr('type', 'password');
            def.isPwdem = true;
        }

    })
    //获取验证码
    $('.js-code-btn').on('click', function () {
        if (def.codeState) return;
        var url = def.url + '/captcha/sendSms';
        var obj = {
            'mobile': $('#account1').val(),   //手机号
            'website': '1',         //站点
            'sence': 'USER_LOGIN',   //短信验证类型
            'dragtoken': _def.slide_Id     //滑块验证，除注册外，非必填
        }
        $.ajaxFun(url, obj, function (data) {
            if (data.code == '0') {
                var i = 60;
                var code = setInterval(function () {
                    def.codeState = true;
                    $('.js-code-btn').text('已发送(' + i + ')');
                    $('.js-code-btn').css('backgroundColor', '#ccc');
                    i--;
                    if (i < 0) {
                        def.codeState = false;
                        $('.js-code-btn').text('获取验证码');
                        $('.js-code-btn').css('backgroundColor', '#d8b056');
                        clearInterval(code);
                    }
                }, 1000);
            } else {
                $('.errorbox10').html('<i></i><span>'+data.msg+'</span>').show();
                if (data.errorCode == 'validation.captcha.slide.need' || data.errorCode == 'validation.captcha.slide.incorrect') {
                    $('.js-click-btn').show();
                    $('.check-free-login').removeClass('stu');
                    $('.pop_loginbg').removeClass('stu');
                    $('.js-slip-code').show();
                }
            }
        })
    })
    $('.js-message-on').on('click', function () {
        top.location.href = '/member/leavemessage';
    })
    $('.loginClose').on('click', function () {
        $('.js-bg-div').hide();
        $('.pop_loginbg').hide();
    })
    /* ie9默认提示文字 */
    function placeholderSupport() {
        return 'placeholder' in document.createElement('input');
    }
    if (!placeholderSupport()) {   // 判断浏览器是否支持 placeholder
        $(".pop_loginbg [placeholder]").each(function () {
            var _this = $(this);
            var left = _this.css("padding-left");
            _this.parent().append('<span class="placeholder" data-type="placeholder" style="left: ' + left + '">' + _this.attr("placeholder") + '</span>');
            if (_this.val() != "") {
                _this.parent().find("span.placeholder").hide();
            }
            else {
                _this.parent().find("span.placeholder").show();
            }
        }).on("focus", function () {
            $(this).parent().find("span.placeholder").hide();
        }).on("blur", function () {
            var _this = $(this);
            if (_this.val() != "") {
                _this.parent().find("span.placeholder").hide();
            }
            else {
                _this.parent().find("span.placeholder").show();
            }
        });
        $(".js-switch-box [placeholder]").each(function () {
            var _this = $(this);
            var left = _this.css("padding-left");
            _this.parent().append('<span class="placeholder" data-type="placeholder" style="left: ' + left + '">' + _this.attr("placeholder") + '</span>');
            if (_this.val() != "") {
                _this.parent().find("span.placeholder").hide();
            }
            else {
                _this.parent().find("span.placeholder").show();
            }
        }).on("focus", function () {
            $(this).parent().find("span.placeholder").hide();
        }).on("blur", function () {
            var _this = $(this);
            if (_this.val() != "") {
                _this.parent().find("span.placeholder").hide();
            }
            else {
                _this.parent().find("span.placeholder").show();
            }
        });
        // 点击表示placeholder的标签相当于触发input
        $("span.placeholder").on("click", function () {
            $(this).hide();
            $(this).siblings("[placeholder]").trigger("click");
            $(this).siblings("[placeholder]").trigger("focus");
        });
    }
    
    
});