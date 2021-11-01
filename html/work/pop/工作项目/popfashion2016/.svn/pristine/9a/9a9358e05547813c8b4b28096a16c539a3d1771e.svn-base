
$(function () {
    (function ($) {
        // 局部对象
        var def = {
            is_sub_reg: false,                                                               //是否提交中
            is_check_msg_code: false,                                                        //是否验证短信验证码中
            reg_token: "",                                                                   //注册临时token
            tmp_id: "",                                                                      //临时id
            pwd_display: false,                                                             //密码隐藏
            referrer_url: '',
            is_init: false,
            codeState: false,
            clientWidth:'',
            url: 'https://member.pop136.com',
            isUsername:false,
            captcha_id:"",
            is_send:false
        };
        var L = {};
        L.us_1 = '请输入用户名';
        L.us_2 = '4-20位字符，一个汉字是两个字符';
        L.us_3 = '此账号已经存在';
        L.us_4 = '请输入真实用户名';
        L.pd_1 = '请输入密码';
        L.pd_2 = '6-20位字符，可由数字、字母、特殊字符组成';
        L.pd_3 = '请再次输入密码';
        L.pd_4 = '请您输入确认密码';
        L.pd_5 = '两次输入的密码不一致';
        L.phone = '请确保输入手机号的有效性，以便快速登录网站时使用';
        L.agree = '请同意协议';
        L.failed = '很抱歉，注册失败请您再确认信息后 重新注册！';
        L.opterr = '操作有误，请您重新检查后进入下一步！！';
        L.skip = '跳转中...';
        L.pd_6 = "对不起，该密码不符合系统要求，请尝试设置其它密码";
        L.pd_7 = "验证码不正确";
        L.pd_8 = "短信验证码不能为空";
        L.pd_9 = "请先拖拽验证";
        L.pd_10 = "图片验证码不能为空"

        var regexEnum = {
            username:"^[a-zA-Z0-9_@\\-&\u4e00-\u9fa5]{4,20}$",
            password: '^[a-zA-Z0-9_\\-\\.\\!\\^\\[\\]\\*\\$\\(\\)\\+<>=%#@&]{6,20}$',
            intege1: "^[1-9]\\d*$",
            num1: "^[1-9]\\d*|0$",
            email: "^\\w+((-\\w+)|(\\.\\w+))*\\@[A-Za-z0-9]+((\\.|-)[A-Za-z0-9]+)*\\.[A-Za-z0-9]+$",
            url: "^http[s]?:\\/\\/([\\w-]+\\.)+[\\w-]+([\\w-./?%&=]*)?$",
            zipcode: "^\\d{6}$",
            mobile: "^1\\d{10}$",
            notempty: "^\\S+$",
            letter: "^[A-Za-z]+$"
        }

        var username = $('.reg-info input[name=username]');
        // var truename = $('.reg-info input[name=truename]');
        var password = $('.js-pwd-box .js-pwd-area');
        var cellphone = $('.reg-info input[name=cellphone]');

        // var cellphone_1 = $(".veri-phone > input");
        var cellphone_1 = $(".reg-info input[name=cellphone]");
        // var verifyCode = $('.veri-list input[name=verifyCode]');
        var verifyCode = $('.reg-info input[name=verifyCode]');
        var real_name = $('.reg-info input[name=real_name]');
        var imgcaptcha = $('.reg-info input[name=imgcaptcha]');

        def.referrer_url = document.referrer;
        if (def.referrer_url == "") {
            def.referrer_url = "/";
        } else if (def.referrer_url.indexOf("www.pop-fashion.com") == -1) {
            def.referrer_url = "/";
        } else if (def.referrer_url.indexOf("pagelogin") != -1) {
            def.referrer_url = "/";
        } else {
            def.referrer_url = def.referrer_url;
        }

        // 密码显示
        $(".js-pwd-display").on("click", function () {
            var inp, val;
            if (def.pwd_display) {
                inp = $(".js-pwd-box").find('input')
                val = inp.val();
                $(".js-pwd-box").html('<input type="text" class="js-pwd-area" value="' + val + '" name="password" id="pw" placeholder="方便登录时验证身份"></input>');
                $(this).removeClass("pwd-display");
                def.pwd_display = false;
            } else {
                inp = $(".js-pwd-box").find('input')
                val = inp.val();
                if (val == "") {
                    $(".js-pwd-box").html('<input type="password" class="js-pwd-area" name="password" id="pw" placeholder="方便登录时验证身份"></input>');
                } else {
                    $(".js-pwd-box").html('<input type="password" class="js-pwd-area" value="' + val + '" name="password" id="pw" placeholder="方便登录时验证身份"></input>');
                }
                $(this).addClass("pwd-display");
                def.pwd_display = true;
            }
        });


        var usernameFunc = function (_this) {
            var val = _this.val();
            var span = _this.siblings('span.msg');
            var url = def.url+'/register/checkAccount';
            var username = $.trim(val);
            var usernameLength = username.replace(/[^\x00-\xff]/g, "**").length;
            var obj = {
                account: val
            };
            if (val == '') {
                span.html('用户名不能为空').show();
                def.isUsername = false;
            } else {
                /* !RegExp(regexEnum.username, 'ig').test(username) || */
                if (!username || usernameLength < 4 || usernameLength > 40) {
                    span.html(L.us_2).show();
                    def.isUsername = false;
                } else {
                    $.ajaxFun(url, obj, function (data) {
                        if (data.code == '0') {
                            span.html('').hide();
                            def.isUsername = true;
                        } else {
                            span.html(data.msg).show();
                            def.isUsername = false;
                        }
                    });
                }
            }   
        };
        var passwordFunc = function (_this) {
            var span = _this.parent('.js-pwd-box').siblings('span.msg');
            var password = $.trim(_this.val());
            if (!password || !RegExp(regexEnum.password, 'ig').test(password)) {
                span.html(L.pd_2).show();
                return false;
            }
            else {
                span.html('').hide();
                return true;
            }
        };
        var cellphoneFunc = function (_this, flag) {
            var cellphone = _this.val();
            var span = _this.siblings('span.msg');
            // if (flag == 'cellphone_1') {
            //     span = $(".msg-ervi");
            // }
            if (!$.trim(cellphone) || !RegExp(regexEnum.mobile, 'ig').test(cellphone)) {
                span.html(L.phone).show();
                def.is_send = false;
                return false;
            } else {
                span.html('').hide();
                def.is_send = true;
                def.is_check_msg_code = false;
                return true;
            }
        };
        var verifyCodeFun=function (_this) {
            var cellphone = _this.val();
            var span = _this.siblings('span.msg');
            if (!$.trim(cellphone)) {
                span.html(L.pd_8).show();
                return false;
            } else {
                span.html('').hide();
                return true;
            }
        };

        
        var imgCaptchaFun=function (_this) {
            var imgcaptcha = _this.val();
            var span = _this.siblings('span.msg');
            if (!$.trim(imgcaptcha)) {
                span.html(L.pd_10).show();
                return false;
            } else {
                span.html('').hide();
                return true;
            }
        };

        username.blur(function () {
            usernameFunc(username);
        });
        $("body").on('blur', '.js-pwd-area', function () {
            passwordFunc($(".js-pwd-area"))
        });
        cellphone.blur(function () {
            cellphoneFunc(cellphone)
        });
        cellphone_1.blur(function () {
            cellphoneFunc(cellphone_1, 'cellphone_1');
        });
        verifyCode.blur(function () {
            verifyCodeFun(verifyCode);
        });

        imgcaptcha.blur(function() {
            imgCaptchaFun(imgcaptcha);
        });

        //限制只能输入数字
        $(".js-only-number").on("input propertychange", function (event) {
            var re = /\D/g;
            var txt = $(this).val();
            if (re.test(txt)) {
                txt = txt.replace(re, "");
                $(this).val(txt);
            }
        });
        $('.reg-info input[name=register_submit]').click(function () {
            if (!def.isUsername) {
                usernameFunc(username);
                return;
            }
            if (!$("#showAgree").hasClass('on')) {
                alert(L.agree);
                return false;
            }
            if (!cellphoneFunc(cellphone) || !verifyCodeFun(verifyCode) || !passwordFunc($(".js-pwd-area"))) {
                return;
            }
            submitData(cellphone);
            /* if (def.is_sub_reg == false) {
                def.is_sub_reg = true;
                submitData(cellphone);
            } */
        });

        var userinfo = {
            name: '',
            com_name: '',
            position: '',
            industry1: [],
            industry2: []
        }
        // $('.reg-info .veri-code>input[name=verifyCode]').on('blur', function() {
        //     codeFunc(verifyCode);
        // })
        $(".industry span").on("click", function () {
            var self = $(this);
            var type = self.parents('li').attr('data-type');
            var other = self.hasClass('other');
            var text = self.text();
            industry(self, type, text, other);
        });
        $('#linkman,#enterprise,#duty').on('focus', function () {
            $(this).parent().next('.err').css('visibility', 'hidden');
        })
        /* 完善资料 */
        $('.immediately').on('click', function () {
            var industry1_other_ipt = $('.industry1_other_ipt').val().trim();
            var industry2_other_ipt = $('.industry2_other_ipt').val().trim();
            userinfo.name = $('#linkman').val().trim();
            userinfo.com_name = $('#enterprise').val().trim();
            userinfo.position = $('#duty').val().trim();
            if (industry1_other_ipt && userinfo.industry1.indexOf(industry1_other_ipt) == -1) userinfo.industry1.push(industry1_other_ipt);
            if (industry2_other_ipt && userinfo.industry2.indexOf(industry2_other_ipt) == -1) userinfo.industry2.push(industry2_other_ipt);
            if (!userinfo.name) {
                $('.linkman_err').css('visibility', 'visible');
            } else if (!userinfo.com_name) {
                $('.enterprise_err').css('visibility', 'visible');
            } else if (!userinfo.position) {
                $('.duty_err').css('visibility', 'visible');
            } else {
                def.is_sub_reg = true;
                $.ajax({
                    url: '/member/addmoreuserinfo/?' + Math.random(),
                    dataType: "json",
                    type: "post",
                    data: {
                        "name": userinfo.name,
                        "com_name": userinfo.com_name,
                        "position": userinfo.position,
                        "com_type": JSON.stringify(userinfo.industry1),
                        "industry": JSON.stringify(userinfo.industry2),
                    },
                    success: function (data) {
                        var ndata = data || {};
                        var code = ndata.code;
                        if (code != 0) {
                            $('.err_msg').html(data.msg).show();
                            def.is_sub_reg = false;
                            return false;
                        } else {
                            $('.supplement_black, .supplement, .err_msg').hide();
                            def.is_sub_reg = false;
                            window.location.href = '/member/register/register_complete/?url=' + def.referrer_url;
                            return true;
                        }
                    },
                    error: function (err) {
                        def.is_sub_reg = false;
                        return false;
                    }
                });
            }
        })
        function codeFunc (self) {
            if (!self.val()) {
                $('.reg-info .veri-code .msg').text(L.pd_7);
                $('.reg-info .veri-code .msg').show();
                return false;
            } else {
                $('.reg-info .veri-code .msg').hide();
                return true;
            }
        }
        function localInfo () {
            $('.supplement_black,.supplement').show();
        }
        function industry (self, type, text, other) {
            var placeholder = type == 'industry1' ? '请输入企业类型' :
                (type == 'industry2' ? '请输入您相关行业' : '');
            var text_idx = userinfo[type].indexOf(text);
            if (self.hasClass('active')) {
                self.removeClass('active');
                if (other) {
                    $('.' + type + '_other_ipt').fadeOut();
                } else {
                    userinfo[type].splice(text_idx, 1);
                }
            } else {
                self.addClass('active');
                if (other) {
                    $('.' + type + '_other_ipt').fadeIn();
                } else {
                    userinfo[type].push(text);
                }
            }
        }
        /* 注册改版 */
        var reg=false;
        var submitData = function (ocell) {
            $('.reg-info input[name=register_submit]').val('注册中...');
            if(reg){
                return;
            }
            reg = true;
            var pidtt = $.cookie('pidtt')==null?"":$.cookie('pidtt');
            var obj = {
                'mobile': ocell.val(),
                'captcha': verifyCode.val(),
                'password': $(".js-pwd-area").val(),
                "website": '1',
                'account': username.val(),
                'pidtt': pidtt,
                'real_name': real_name.val()
            }
            var url = def.url + '/register';
            $.ajaxFun(url, obj, function (data) {
                reg=false;
                $('.reg-info input[name=register_submit]').val('立即注册');
                if (data.code=='0') {
                    var reg_token = data.regToken || "";
                    var tmp_id = data.tmpId || "";
                    def.reg_token = reg_token;
                    def.tmp_id = tmp_id;
                    //注册成功登录
                    var POP_USER = data.data.jwt;
                    $.cookie('NO_REFRESH_JWT','1',{path:'/',domain: 'pop-fashion.com',secure: false,raw:false });
                    $.cookie('POP_USER', POP_USER, { expires: data.data.expire, path: '/', domain: 'pop-fashion.com', secure: false, raw: false });
                    window._agl  &&  window._agl.push(['track',  ['success',  {t:  3}]])
                    if (def.is_check_msg_code == false) {
                        def.is_check_msg_code = true;
                        localInfo();
                    }
                } else {
                    $('.errorbox').html('<i></i><span>'+data.msg+'</span>').show();
                }
            })
        };
        //获取验证码
        function regCode (geetest_challenge,geetest_validate,geetest_seccode) {
            var url = def.url + '/captcha/sendSms';
            var obj = {
                'mobile': $('#cp').val(),   //手机号
                'website': '1',         //站点
                'sence': 'USER_REGISTER',   //短信验证类型
                //header-token ，jsonp无法设置headers所以只能放参数
                'pop_token': $('meta[name="pop-verification"]').attr('content'),
                'pop_uid':$.cookie('POP_UID'),
                'geetest_challenge': geetest_challenge,
                'geetest_validate': geetest_validate,
                'geetest_seccode': geetest_seccode
            }
            $.ajaxFun(url, obj, function (data) {
                if (data.code == '0') {
                    $('.errorbox').hide();
                    var i = 60;
                    var code = setInterval(function () {
                        def.codeState = true;
                        $('#sendVerifyCode').val('已发送(' + i + ')');
                        $('#sendVerifyCode').css('backgroundColor', '#ccc');
                        i--;
                        if (i < 0) {
                            def.codeState = false;
                            $('#sendVerifyCode').val('获取验证码');
                            $('#sendVerifyCode').css('backgroundColor', '#232f3c');
                            clearInterval(code);
                        }
                    }, 1000);
                } else {
                    $('.errorbox').html('<i></i><span>'+data.msg+'</span>').show();
                }
            })
        }
        var captchaObj_new;
        // 获取验证码事件
        $(".sms-button").on("click", function() {
            if(!def.is_send){
                cellphoneFunc(cellphone);
                return false;
            }
            if(def.codeState){
                return;
            }
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
                    regCode(result.geetest_challenge,result.geetest_validate,result.geetest_seccode)
                }).onError(function(){
                    
                })
            }
            $.ajax({
                url: def.url + "/captcha/geetest/?" + (new Date()).getTime(), 
                type: "get",
                dataType: "jsonp",
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
        })
    })(jQuery);

    
    //服务协议
    $("#showAgree").click(function () {
        $(".shadow_black").show();
        $(".closeBtn").show();
        $(".alertLayer").show();
    });

    $(".closeBtn,.bottom input[name='cancel']").click(function () {
        $("#showAgree").removeClass("on");
        $(".shadow_black").hide();
        $(".closeBtn").hide();
        $(".alertLayer").hide();
    });

    $(".bottom input[name='agree']").click(function () {
        //使协议框选中
        $("#showAgree").addClass("on");
        $(".shadow_black").hide();
        $(".closeBtn").hide();
        $(".alertLayer").hide();
    });

    // 港澳台注册
    $(".js-city-reg-btn").on("click", function() {
        $(".js-city-reg-layer,.shadow_black").show();
    });

    $(".js-close-city-area").on("click", function() {
        $(".js-city-reg-layer,.shadow_black").hide();
    });

    $(".js-area-li-box").on("mouseenter", function() {
        $(this).find(".area-downlist").show();
        $(this).find(".input-box>i").addClass("on");
    }).on("mouseleave", function(){
        $(this).find(".area-downlist").hide();
        $(this).find(".input-box>i").removeClass("on");
    });

    $(".js-city-reg-layer input:not(.area-input)").on("focus", function() {
        $(this).parents("li").css("border-color","#333");
    }).on("blur", function() {
        $(this).parents("li").css("border-color","#ccc");
    });

    $(".js-area-downlist>a").on("click", function() {
        var val = $(this).text().trim();
        var id = $(this).attr("data-val");
        $(".js-area-input").val(val);
        $(".js-area-input").attr("data-val",id);
        $(".js-area-downlist").hide();
        $(".js-area-li-box").find(".input-box>i").removeClass("on");

    });

    var city = {
        name: '',
        tel: '',
        area: '',
        email: '',
    };
    var is_sub = false;
    function init() {
        $("#js-name-input").val("")
        $("#js-area-input").attr("data-val","").val("");
        $("#js-contact-input").val("");
        $("#js-email-input").val("");
    }
    $(".js-area-reg-submit").on("click", function() {
        city.name = $("#js-name-input").val().trim();
        city.area = $("#js-area-input").attr("data-val");
        city.tel = $("#js-contact-input").val().trim();
        city.email = $("#js-email-input").val().trim();        
        if(!city.name){
            $(".js-name-msg").show();
        }else if(!city.area){
            $(".js-area-msg").show();
        }else if(!city.tel){
            $(".js-contact-msg").show();
        }else if(!city.email){
            $(".js-email-msg").show();
        }else{
            if(is_sub){
                return false;
            }
            is_sub = true;
            $.ajax({
                url : '/ajax/getHMTUserData/?' + Math.random(),
                type: 'post',
                dataType: 'json',
                data: city,
                success: function(data) {
                    if(data.code == 0) {
                        $(".js-city-reg-layer,.shadow_black").hide();
                        $(".js-toast-reg-layer").animate({"top":"50px"},500).html("提交成功");
                    }else{
                        $(".js-city-reg-layer,.shadow_black").hide();
                        $(".js-toast-reg-layer").animate({"top":"50px"},500).html("提交失败，请重新提交");
                    }
                    is_sub = false;
                    init();
                    setTimeout(function () {
                        $(".js-toast-reg-layer").animate({"top":"-50%"},500).html();
                    },2000);
                }
            })
        }
    });
    
});