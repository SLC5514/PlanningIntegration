/*
留资common
20200330
*/
$(function() {
    var def = {
        codeState :false,    //是否正在发送
        url: 'https://member.pop136.com',
        enterprise: ""
    };

    var phone = $("#phone-area");
    var sms_code = $("#sms-area");
    var type = $("#type-area");

    type.on("click", function(e) {
        e.stopPropagation();
        $(this).siblings(".type-downlist").show();
        $(this).siblings("i").addClass("on");
    });

    $(".type-downlist").on("click", "a", function(e) {
        e.stopPropagation();
        var val = $(this).text();
        var id = $(this).data("val");
        type.val(val);
        def.enterprise = id;
        $(".f-type-input span").hide();
        $(".type-downlist").hide();
        $(".type-downlist").siblings("i").removeClass("on");
    })

    $("body").on("click", function() {
        $(".type-downlist").hide();
        $(".type-downlist").siblings("i").removeClass("on");
    });

    // 验证手机
    var phoneFunc = function(_this) {
        var phone_val = $.trim(_this.val());
        var span_msg = _this.siblings("span");
        if(phone_val == "" || phone_val.length != 11) {
            span_msg.show();
            return false
        }else{
            span_msg.hide();
            return true;
        }
    };

    phone.on("blur", function() {
        phoneFunc(phone);
    });

    // 验证验证码
    var smsFunc = function(_this) {
        var span_msg = _this.siblings("span");
        var sms_val = $.trim(_this.val());
        if(sms_val == "") {
            span_msg.show();
            return false
        }else{
            span_msg.hide();
            return true;
        }
    };

    // 验证企业类型
    var typeFunc = function(_this) {
        var type_val = $.trim(_this.val());
        var span_msg = _this.siblings("span");
        if(type_val == "") {
            span_msg.show();
            return false
        }else{
            span_msg.hide();
            return true;
        }
    };

    type.on("blur", function(e) {
        e.stopPropagation();
        typeFunc(type);
    });
    

    // 留资
    var is_submit = false;
    $('.js-free-get').on('click',function(){
        if(!phoneFunc(phone) || !typeFunc(type) ||  !smsFunc(sms_code)){
            return false;
        }        
        var pid=pop_fashion_global.fn.getLocationParameter().pid ? pop_fashion_global.fn.getLocationParameter().pid : ($.cookie('pidtt') ? $.cookie('pidtt') : '');
        var url = encodeURI(window.location.href);
        var data={
            cellphone:phone.val(),
            web_code:'fashion',
            pid: pid,
            channel_url:url,
            enterprise: def.enterprise,
            captcha:sms_code.val()
        };
        $.ajax({
            url: 'https://api.pop-fashion.com/app/stayconsultation/',
            type: 'GET',
            dataType:'jsonp',
            data: data,
            success: function(data) {
                if(data.code=='0'){
                    $(".js-msg-sms").hide();                   
                    $('.js-col-sem-success,.js-col-trials').show();
                    $(".js-col-footer-layer").hide();
                    $(".web-common-foot,.footer").css("padding-bottom","0");
                    is_submit = true;
                }else if(data.code == 1004){
                    $(".js-msg-sms").html(data.msg).show();
                }else{
                    $('.js-col-sem-layer,.js-col-trials').show();
                }
            },
            error: function(err) {}
        })
    })

    //获取验证码
    function regCode (geetest_challenge,geetest_validate,geetest_seccode) {
        var url = def.url + '/captcha/sendSms';
        var obj = {
            'mobile': phone.val(),   //手机号
            'website': '1',         //站点
            'sence': 'CONSULTATION_CHANNEL',   //短信验证类型
            'pop_token': $('meta[name="pop-verification"]').attr('content'),
            'pop_uid':$.cookie('POP_UID'),
            'geetest_challenge': geetest_challenge,
            'geetest_validate': geetest_validate,
            'geetest_seccode': geetest_seccode
        }
        $.ajaxFun(url, obj, function (data) {
            if (data.code == '0') {
                $('.js-msg-sms').hide();
                var i = 60;
                var code = setInterval(function () {
                    def.codeState = true;
                    $('.js-get-sms').text(i + 'S');
                    i--;
                    if (i < 0) {
                        def.codeState = false;
                        $('.js-get-sms').text('获取验证码');
                        clearInterval(code);
                    }
                }, 1000);
            } else {
                $('.js-msg-sms').html(data.msg).show();
            }
        })
    }
    var captchaObj_new;
    // 获取验证码事件
    $(".js-get-sms").on("click", function() {
        if(!phoneFunc(phone) || def.codeState){
            return false;
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

    $(".js-col-close").on("click", function() {
        $('.js-col-sem-layer,.js-col-trials,.js-col-sem-success').hide();
    });

    // if(P_UserType == 5){
    //     $(".web-common-foot,.footer").css("padding-bottom","70px");
    // }else{
    //     $(".web-common-foot,.footer").css("padding-bottom","0");
    // }

    // 栏目页底部留资
    // var path = window.location.pathname;
    // if(path != "/" && path != "/home/"){
    //     $(window).on("scroll", function() {
    //         var scrollTop = $(this).scrollTop();
    //         if(scrollTop > 100 && !is_submit){
    //             $(".js-col-footer-layer").show();
    //         }else{
    //             $(".js-col-footer-layer").hide();
    //         }
    //     });
    // }    
})