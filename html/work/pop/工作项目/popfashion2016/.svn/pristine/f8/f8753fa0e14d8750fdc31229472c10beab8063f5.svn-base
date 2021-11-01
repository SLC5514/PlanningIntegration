$(function () {
    var def = {
        url: '//member.pop136.com',
        loginType: '',
        codeState: false,
        datatType: '1',
        findType: '',  //main、找回注册账号密码   //child、找回设计师账号密码
        alterToken: '',   //找回密码token
        userId: '',        //用户ID
        crumbsText:['登录页','找回密码'],     //面包屑文字
        data: {},
        website:'1'
        /* isTable: false */
    }
    
    var phoneBox = $('#phone'),
        verifyCode = $('#verifyCode'),
        accountBox = $('#account'),
        newPwdBox = $('#new-pwd'),
        reapPwdBox = $('#reap-pwd'),
        password = $('#password');
    var regexEnum = {
        mobile: "^1\\d{10}$",
        //password: /^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$/,
    }
    var L = {
        account: '请输入账号',
        phone1: '请输入手机号码',
        phone2: '请输入真实手机号码',
        pd_0:'请输入密码',
        //pd_1: '6-20位字符，可由数字、字母、特殊字符组成',
        pd_2: '两次输入的密码不一致',
        pd_3 : '请您输入密码',
        pd_4: '请再次输入密码',
        code: '请输入验证码',
    };
    //手机号验证
    var phoneFun = function (_this) {
        var span = _this.siblings('p.msg');
        var username = $.trim(_this.val());
        if (!username) {
            span.html(L.phone1).show();
            return false;
        }else if (!RegExp(regexEnum.phone, 'ig').test(username)) {
            span.html(L.phone2).show();
            return false;
        }else {
            span.html('').hide();
            return true;
        }
    }
    //密码验证
    var passwordFun = function (_this) {
        var span = _this.siblings('p.msg');
        var username = $.trim(_this.val());
        if (!username) {
            span.html(L.pd_0).show();
            return false;
        }else {
            span.html('').hide();
            return true;
        }
    }
    //短信验证码非空验证
    var verifyCodeFun = function (_this) {
        var span = _this.siblings('p.msg');
        var username = $.trim(_this.val());
        if (!username) {
            span.html(L.code).show();
            return false;
        }else {
            span.html('').hide();
            return true;
        }
    }
    //账号非空验证
    var accountFun = function (_this) {
        var span = _this.siblings('p.msg');
        var username = $.trim(_this.val());
        if (!username) {
            span.html(L.account).show();
            return false;
        }else {
            span.html('').hide();
            return true;
        }
    }
    //密码验证
    var newPwdFun = function (_this) {
        var span = _this.siblings('p.msg');
        var username = $.trim(_this.val());
        if (!username) {
            span.html(L.pd_3).show();
            return false;
        }else {
            span.html('').hide();
            return true;
        }
    }
    //再次输入密码验证
    var reapPwdFun = function (_this) {
        var span = _this.siblings('p.msg');
        var newPwd = newPwdBox.val();
        var username = $.trim(_this.val());
        if (!username) {
            span.html(L.pd_4).show();
            return false;
        }else if (newPwd!==username) {
            span.html(L.pd_2).show();
            return false;
        }else {
            span.html('').hide();
            return true;
        }
    }
    phoneBox.blur(function () {
        phoneFun($(this));
    })
    password.blur(function () {
        passwordFun($(this));
    })
    verifyCode.blur(function () {
        verifyCodeFun($(this));
    })
    accountBox.blur(function () {
        accountFun($(this));
    })
    newPwdBox.blur(function () {
        newPwdFun($(this));
    })
    reapPwdBox.blur(function () {
        reapPwdFun($(this));
    })
    //找回方式切换
    $('.js-tableH1 span').on('click', function () {
        $('.js-tableH1 span').removeClass('action');
        $(this).addClass('action');
        def.datatType = $(this).attr('data-id');
        if (def.datatType != '1') {
            $('.js-password').hide();
            $('.js-tableH1 label').hide();
        } else {
            $('.js-password').show();
            $('.js-tableH1 label').show();
        }
    })
    var codeFun = function (_this) {
        var url = def.url + '/captcha/sendSms';
        var sence = _this.attr('data-type');
        var obj = {
            'mobile': $('#phone').val(),   //手机号
            'website': def.website,         //站点
            'sence': sence,   //短信验证类型
            'dragtoken': _def.slide_Id     //滑块验证，除注册外，非必填
        }
        if (sence == 'FORGET_PWD') {
            obj['account'] = accountBox.val();
            obj['accountType'] = def.findType;
        }
        $.ajaxFun(url, obj, function (data) {
            if (data.code == '0') {
                var i = 60;
                var code = setInterval(function () {
                    def.codeState = true;
                    _this.text('已发送(' + i + ')');
                    _this.css('backgroundColor', '#ccc');
                    i--;
                    if (i < 0) {
                        def.codeState = false;
                        _this.text('获取验证码');
                        _this.css('backgroundColor', '#232f3c');
                        clearInterval(code);
                    }
                }, 1000);
                $('.errorbox').hide();
            } else {
                $('.errorbox').html('<i></i><span>'+data.msg+'</span>').show();
                if (data.errorCode == 'validation.captcha.slide.need' || data.errorCode == 'validation.captcha.slide.incorrect') {
                    _def.clientWidth = '410_76';
                    _this.parents().next().find('.js-click-btn').show();
                }
            }
        })
    }
    //获取短信验证码
    $('.js-code-btn3').on('click', function () {
        if (!phoneFun(phoneBox)) return;
        if (def.codeState) return;
        codeFun($(this));
    })
    //关闭弹层
    $('.js-multiClose').on('click', function () {
        $('.multi-account').hide();
        $('.box-bg').hide();
        $('.box-contact').hide();
        $('.errorbox').hide();
    })
    var findUser = function () {
        var url = def.url + '/user/forgetAccount';
        var obj = {};
        if (def.datatType == '1') {
            obj['findAccountType'] = "password";
            obj['password'] = $('#password').val();
        } else {
            obj['findAccountType'] = "captcha";
        }
        obj['mobile'] = $('#phone').val();
        obj['website'] = def.website;
        obj['captcha'] = $('#verifyCode').val();
        $.ajaxFun(url, obj, function (data) {
            console.log(data);
            if (data.code == '0') {
                $('.multi-account').show();
                $('.box-bg').show();
                $('.js-account').text(data.data.account);
                $('.errorbox').hide();
            } else {
                $('.errorbox').html('<i></i><span>'+data.msg+'</span>').show();
                if (data.errorCode == 'validation.account.forget.not_only') {
                    $('.box-bg').show();
                    $('.box-contact').show();
                } else if (data.errorCode == 'validation.account.forget.not_found') {
                    //没有找到账号
                }
            }
        })
    }
    //找回用户名提交
    $('.js-submit').on('click', function () {
        if (!phoneFun(phoneBox)) return;
        if (def.datatType == '1') {
            if (!passwordFun(password)) return;
        }
        if (!verifyCodeFun(verifyCode)) return;
        findUser()
    })
    var findOneFun = function () {
        var url = def.url + '/user/forgetPassword/1';
        var obj = {
            'accountType':def.findType,     //找回密码类型
            'mobile': $('#phone').val(),   //手机号
            'website': def.website,         //站点
            'captcha': $('#verifyCode').val(),   //短信验证码
            'account': $('#account').val()    //账号找回时,必填
        }
        $.ajaxFun(url, obj, function (data) {
            console.log(data);
            if (data.code == '0') {
                def.alterToken = data.data.alterToken;
                def.userId = data.data.userId;
                $('.js-find-box').hide();
                $('.js-reset-pwd').show();
                //面包屑改变
                crumbsFun('重置密码');
                //进度
                progressFun(1);
                $('.errorbox').hide();
                /* $('.js-submit').removeClass('action');
                def.isTable = false; */
            } else {
                $('.errorbox').html('<i></i><span>'+data.msg+'</span>').show();
            }
        })
    }
    //找回密码提交第一步
    $('.js-find').on('click', function () {
        if (def.findType=='main') {
            if (!accountFun(accountBox) || !phoneFun(phoneBox) || !verifyCodeFun(verifyCode)) return;
        } else {
            if (!phoneFun(phoneBox) || !verifyCodeFun(verifyCode)) return;
        }
        findOneFun();
    })
    var findTwoFun = function () {
        var url = def.url + '/user/forgetPassword/2';
        var obj = {
            'password': $('#new-pwd').val(),   //密码
            'password_confirmation': $('#reap-pwd').val(),   //确认密码
            'alterToken':def.alterToken,  //第一步获取到的token
            'userId':def.userId,     //用户ID
            'accountType':def.findType,     //找回密码类型
            'website': def.website         //站点
        }
        $.ajaxFun(url, obj, function (data) {
            console.log(data);
            if (data.code == '0') {
                $('.js-reset-pwd').hide();
                $('.js-alter-success').show();
                def.crumbsText = def.crumbsText.slice(0, 2);
                crumbsFun('修改成功');
                //进度
                progressFun(2);
                setTimeout(function () {
                    location.href = '/member/pagelogin/'
                }, 3000);
                $('.errorbox').hide();
            } else {
                $('.errorbox').html('<i></i><span>'+data.msg+'</span>').show();
            }
        })
    }
    //找回密码提交第二步
    $('.js-pwd-submit').on('click', function () {
        if (!newPwdFun(newPwdBox) || !reapPwdFun(reapPwdBox)) return;
        findTwoFun()
    })
    //面包屑添加
    function crumbsFun (text) {
        def.crumbsText.push(text);
        var cruText = ''
        for (var i = 0; i < def.crumbsText.length; i++){
            if (i!=def.crumbsText.length-1) {
                cruText += '<span>' + def.crumbsText[i] + '</span>>'
            } else {
                cruText += '<span class="action">' + def.crumbsText[i] + '</span>'
            }
        }
        $('.js-crumbs').html(cruText);
    }
    //进度改变
    function progressFun (i) {
        var _label = $('.js-progress span label');
        _label.each(function (index) {
            var _this = $(_label[index]);
            _this.removeClass('progress');
            _this.removeClass('progress1');
            if (index < i) {
                _this.addClass('progress1');
            } else if (index == i) {
                _this.addClass('progress');
            }
        })
    }
    //切换找回密码方式
    $('.js-find-btn').on('click', function () {
        $('.js-find-btn').removeClass('action1');
        $(this).addClass('action1');
        def.findType = $(this).attr('data-id');
        /*if (def.findType=='child') {
            $('#account').removeClass('isTable');
        } */
        /* $('.js-type-btn').css('backgroundColor', '#d8b056'); */
    })
    //关闭提示
    $('.js-find-phone label b').on('click', function (e) {
        e.stopPropagation()
        $('.js-find-phone label').hide();
    })
    //确认找回方式
    $('.js-type-btn').on('click', function () {
        if (def.findType == '') return;
        $('.js-find-box').show();
        $('.js-find-type').hide();
        $('.js-head').hide();
        $('.js-pro').show();
        $('.js-find-btn').each(function () {
            var _this = $((this));
            if (_this.hasClass('action1')) {
                crumbsFun(_this.attr('data-text'));
                $(".js-action").text(_this.attr('data-text'));
            }
        });
        if (def.findType=="main") {
            $('.js-account').show();
        } else if(def.findType=="child") {
            $('.js-account').hide();
        }
    })
    /* //原有逻辑
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
    $(".fg_tel_layer .fg_close").on("click", function () {
        $(".fg_tel_layer, .fg_tel_info, .no_tel_num").hide();
    }); */
})