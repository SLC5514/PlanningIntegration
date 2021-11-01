define(["jquery", "general", "msg"], function (jquery, general, msg) {
    $(function () {
        var def = {
            tipEl: $('.tip-box'),
            formBox1: $(".js-form-list1"),
            formBox2: $(".js-form-list2"),
            formBox3: $(".js-form-list3"),
            accountIpt: $("#account-ipt1"),
            pswdnewIpt: $("#pswdnew-ipt"),
            list_ele: $(".js-strength-list>li"),
            re: {
                mobile: /^1[1-9][0-9]{9}$/,
                password: /^[a-zA-Z0-9_\-\.!\^\[\]\*\$\(\)\+<>=%#@&]{6,20}$/
            },
            is_sub: false, // 是否提交中
            is_get_code1: false, // 是否获取验证码中
            is_get_code2: false // 是否获取验证码中
        };

        $('.js-setuser-list').on('click', '.title', function () {
            $(this).parent().addClass('on').siblings().removeClass('on');
        })

        // tip提示控制
        function tipToggle(msg, type, callback) {
            if (type) {
                def.tipEl.addClass('on');
            } else {
                def.tipEl.removeClass('on');
            }
            def.tipEl.text(msg).fadeIn(200);
            var timeout = setTimeout(function () {
                def.tipEl.fadeOut(200);
                callback && callback();
                clearTimeout(timeout);
            }, 2000);
        }

        /* ======================= 修改手机 =================== */

        // 改变图形验证码
        $(".js-img-code-btn").on("click", function () {
            var str = $(this).attr("data-src") || "";
            var t = (new Date()).getTime();
            $(this).attr("src", str + "?t=" + t);
        });

        // 获取手机验证码
        $(".js-get-code").on("click", function () {
            getMsgFunc($(this));
        });

        //限制只能输入数字
        $(".js-number-only").on("input propertychange", function () {
            var re = /\D/g;
            var txt = $(this).val();
            if (re.test(txt)) {
                txt = txt.replace(re, "");
                $(this).val(txt);
            }
        });

        // input判断
        def.formBox1.find('input').on("focus", function () {
            var error_ele = $(this).siblings(".err");
            error_ele.hide();
        }).on("blur", function () {
            checkFunc($(this), $(this).parent());
        });
        def.formBox2.find('input').on("focus", function () {
            var error_ele = $(this).siblings(".err");
            error_ele.hide();
        }).on("blur", function () {
            checkFunc($(this), $(this).parent());
        });

        function checkFunc(obj, tag) {
            var index = tag.index();
            var is_ok = true, txt = obj.val();
            var error_ele = obj.siblings(".err");
            txt = txt.replace(/\s/g, "");
            var msg_txt = "";
            error_ele.text(index);
            switch (index) {
                case 0:
                    if (txt == "") {
                        is_ok = false;
                        msg_txt = "手机号码为空，请正确填写";
                    } else if (txt.length < 11 || def.re.mobile.test(txt) === false) {
                        is_ok = false;
                        msg_txt = "请输入11位正确手机号码";
                    }
                    break;
                case 1:
                    if (txt == "") {
                        is_ok = false;
                        msg_txt = "请输入图形验证码";
                    } else if (txt.length < 4) {
                        is_ok = false;
                        msg_txt = "图形验证码不正确";
                    }
                    break;
                case 2:
                    if (txt == "") {
                        is_ok = false;
                        msg_txt = "请输入短信验证码";
                    }
                    break;
                case 3:
                default:
                    break;
            }
            error_ele.text(msg_txt);
            if (is_ok == true) {
                error_ele.hide();
            } else {
                error_ele.show();
            }
            return is_ok;
        };

        // 下一步
        $(".js-next-btn1").on("click", function () {
            stepOneFunc($(this));
        });
        $(".js-next-btn2").on("click", function () {
            stepTwoFunc($(this));
        });
        // 回车事件
        def.formBox1.on("keydown", function (e) {
            var keycode = e.keyCode;
            if (keycode == 13) {
                stepOneFunc($(".js-next-btn1"));
                return false;
            }
        });
        def.formBox2.on("keydown", function (e) {
            var keycode = e.keyCode;
            if (keycode == 13) {
                stepTwoFunc($(".js-next-btn2"));
                return false;
            }
        });

        // 发送验证码
        function getMsgFunc(obj) {
            var key = obj.attr("data-key") || "is_get_code1";
            if (def[key] == true) { return false };
            var port_str = key == "is_get_code1" ? "/user/sendcodeforbind/" : "/user/sendmodbindmobile/";
            var ntag = key == "is_get_code1" ? def.formBox1 : def.formBox2;
            var img_tag = key == "is_get_code1" ? $(".js-img-code-btn").eq(0) : $(".js-img-code-btn").eq(1);
            var is_next = true, data = {}
            ntag.find('input').each(function (i) {
                if (i == 0 || i == 1) {
                    var nkey = $(this).attr("name") || "";
                    data[nkey] = $(this).val() || "";
                    is_next = checkFunc($(this), $(this).parent());
                    return is_next;
                }
            });
            if (is_next == true) {
                def[key] = true;
                obj.text("获取中...").addClass("disabled-ele");
                general.fn.subAjax({
                    url: port_str,
                    data: data,
                    ctp: "application/x-www-form-urlencoded",
                    success: function (data) {
                        obj.text("60(S)");
                        var timer = null;
                        timeFn(60);
                        function timeFn(num) {
                            timer = setTimeout(function () {
                                clearTimeout(timer);
                                num--;
                                if (num < 1) {
                                    def[key] = false;
                                    obj.text("重新发送").removeClass("disabled-ele");
                                    img_tag.click();
                                } else {
                                    obj.text(num + "(S)");
                                    timeFn(num);
                                }
                            }, 1000);
                        }
                    },
                    error: function (data) {
                        obj.text("获取验证码").removeClass("disabled-ele");
                        def[key] = false;
                        var ndata = data || {};
                        if (ndata.code != "10002") {
                            img_tag.click();
                        }
                    }
                })
            }
        };

        // 下一步
        function stepOneFunc(obj) {
            if (def.is_sub == true) { return false };
            var is_next = true, data = {};
            def.formBox1.find('input').each(function () {
                $(this).blur();
                is_next = checkFunc($(this), $(this).parent());
                var nkey = $(this).attr("name") || "";
                data[nkey] = $(this).val();
                return is_next;
            });
            if (is_next == false) {
                return false;
            } else {
                obj.text("提交中...");
                def.is_sub = true;
                data.act = "bind_step1";
                general.fn.subAjax({
                    url: "/user/unbindmobile/",
                    data: data,
                    ctp: "application/x-www-form-urlencoded",
                    success: function (data) {
                        obj.text("下一步");
                        def.is_sub = false;
                        $(".js-form-list1").hide();
                        $(".js-form-list2").show();
                        def.formBox1.find('input').each(function () {
                            if (!$(this).hasClass('account-ipt')) {
                                $(this).val('');
                            }
                        });
                    },
                    error: function (data) {
                        obj.text("下一步");
                        def.is_sub = false;
                        var ndata = data || {};
                        if (ndata.code != "10002") {
                            $(".js-img-code-btn").eq(0).click();
                        }
                    }
                })
            }
        };
        function stepTwoFunc(obj) {
            if (def.is_sub == true) { return false };
            var is_next = true, data = {};
            def.formBox2.find('input').each(function () {
                $(this).blur();
                is_next = checkFunc($(this), $(this).parent());
                var nkey = $(this).attr("name") || "";
                data[nkey] = $(this).val();
                return is_next;
            });
            if (is_next == false) {
                return false;
            } else {
                obj.text("提交中...");
                def.is_sub = true;
                // data.act="find_step2";
                data.oldMobile = def.accountIpt.val();
                general.fn.subAjax({
                    url: "/user/modbind/",
                    data: data,
                    ctp: "application/x-www-form-urlencoded",
                    success: function (data) {
                        obj.text("提交");
                        def.is_sub = false;
                        tipToggle('修改成功', true, function() {
                            window.location.href = '/';
                        });
                        def.formBox2.find('input').each(function () {
                            $(this).val('');
                        });
                    },
                    error: function (data) {
                        obj.text("提交");
                        def.is_sub = false;
                        var ndata = data || {};
                        if (ndata.code != "10002") {
                            $(".js-img-code-btn").eq(1).click();
                        }
                    }
                })
            }
        };

        /* ======================= 修改密码 =================== */

        // input判断
        def.formBox3.find('input').on("focus", function () {
            var error_ele = $(this).siblings(".err");
            error_ele.hide();
        }).on("blur", function () {
            checkPwdFunc($(this), $(this).parent());
        });
        $('.js-new-password').on("keyup", function (e) {
            var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
            var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
            var enoughRegex = new RegExp("(?=.{6,}).*", "g");
            var nindex = 1;
            if (false == enoughRegex.test($(this).val())) {
                nindex = 4;
            } else if (strongRegex.test($(this).val())) {
                nindex = 3;
            } else if (mediumRegex.test($(this).val())) {
                nindex = 2;
            } else {
                nindex = 1;
            }
            def.list_ele.each(function (i) {
                if (nindex != 4) {
                    if (i < nindex) {
                        $(this).addClass("on");
                    } else {
                        $(this).removeClass("on");
                    }
                } else {
                    $(this).removeClass("on");
                }
            });
            return true;
        });

        function checkPwdFunc(obj, tag) {
            var index = tag.index();
            var is_ok = true, txt = obj.val();
            var error_ele = obj.siblings(".err");
            txt = txt.replace(/\s/g, "");
            var msg_txt = "";
            switch (index) {
                case 0:
                    if (txt == "") {
                        is_ok = false;
                        msg_txt = "请输入原密码";
                    } else if (general.fn.getCharacterLen(txt) < 6 || general.fn.getCharacterLen(txt) > 20) {
                        is_ok = false;
                        msg_txt = "6-20位字符，可由数字、字母、特殊字符组成";
                    }
                    break;
                case 1:
                    if (txt == "") {
                        is_ok = false;
                        msg_txt = "请输入新密码";
                    } else if (general.fn.getCharacterLen(txt) < 6 || general.fn.getCharacterLen(txt) > 20 || !def.re.password.test(txt)) {
                        is_ok = false;
                        msg_txt = "可以由6~20个字符字母、数字、特殊字符组成，请注意大小写";
                    }
                    break;
                case 2:
                    if (txt == "") {
                        is_ok = false;
                        msg_txt = "请再次输入密码进行确认，注意区分大小写";
                    } else if (def.pswdnewIpt.val() != txt) {
                        is_ok = false;
                        msg_txt = "两次密码输入不一致";
                    }
                    break;
                default:
                    break;
            }
            error_ele.text(msg_txt);
            if (is_ok == true) {
                error_ele.hide();
            } else {
                error_ele.show();
            }
            obj.val(txt);
            return is_ok;
        };

        // 提交数据
        $(".js-sub-change-password").on("click", function () {
            subFunc($(this));
        });
        // 回车事件
        def.formBox3.on("keydown", function (e) {
            var keycode = e.keyCode;
            if (keycode == 13) {
                subFunc($(".js-sub-change-password"));
                return false;
            }
        });

        function subFunc(obj) {
            if (def.is_sub == true) { return false };
            var is_next = true, data = {};
            def.formBox3.find('input').each(function () {
                $(this).blur();
                var nkey = $(this).attr("name") || "";
                is_next = checkPwdFunc($(this), $(this).parent());
                data[nkey] = $(this).val();
                return is_next;
            });
            if (is_next == false) {
                return false;
            } else {
                obj.text("提交中...");
                def.is_sub = true;

                general.fn.subAjax({
                    url: "/user/modpwd/",
                    data: data,
                    ctp: "application/x-www-form-urlencoded",
                    success: function (data) {
                        obj.text("提交");
                        def.is_sub = false;
                        tipToggle('修改成功', true, function() {
                            $('.js-login-out-btn').click();
                        });
                        def.formBox3.find('input').each(function () {
                            $(this).val('');
                        });
                    },
                    error: function () {
                        obj.text("提交");
                        def.is_sub = false;
                    }
                })
            }
        };
    });
});