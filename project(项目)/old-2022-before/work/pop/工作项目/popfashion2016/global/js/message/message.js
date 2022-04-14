$(function () {
    def = {
        url: '//member.pop136.com',
        data: {
            type: '',    //留言类型
            telphone: '',   //电话或手机号码
            user_name: '',   //用户姓名
            company: '',   //公司名称
            account: '',   //账户名
            description: ''  //描述
        },
        isTable:false
    }
    //提交留言表单
    $('.js-message-btn').on('click', function () {
        if (!def.isTable) return;
        var url = def.url + '/user/leaveMessage';
        var jwt = pop_fashion_global.fn.getCookie('POP_USER');
        def.data.telphone = $('.js-telphone').val();
        def.data.user_name = $('.js-user_name').val();
        def.data.account = $('.js-account1').val();
        def.data.company = $('.js-company').val();
        def.data.website = '1';
        def.data.jwt = jwt == null ? '' : jwt;
        $.ajaxFun(url, def.data, function (data) {
            if (data.code == '0') {
                $('.js-meg-success').show();
                $('.js-message').hide();
            } else {
                
            }
        })
    })
    //留言类型选择
    $('.js-issue-Ul .issue-Li').on('click', function (e) {
        var text = $(this).text();
        // text = text.substring(text.indexOf('、')+1, text.length);
        $('.js-messageType').text(text);
        $('.js-messageType').css('color', '#333');
        $('.js-issue-Ul').hide();
        def.data.type = text;
        e.stopPropagation();
    })
    $('.js-Type').on('click', function () {
        var _visible = $('.js-issue-Ul').is(':visible');
        if (_visible) {
            $('.js-issur-show').removeClass('selection');
            $('.js-issue-Ul').hide();
        } else {
            $('.js-issur-show').addClass('selection');
            $('.js-issue-Ul').show();
        }
    })
    //表单验证
    $('.js-table').blur(function () {
        var state = true;
        $('.js-table').each(function () {
            var val = $(this).val();
            if (val == '') {
                state = false;
            }
        })
        if (def.data.type != '' && state) {
            def.isTable = state;
            $('.js-message-btn').addClass('action');
        } else {
            def.isTable = state;
            $('.js-message-btn').removeClass('action');
        }
    })
    //留言剩余字数计算
    $('.js-text').bind('input propertychange', function () {
        var number = $(".js-text").val();
        if (number.length>=500) {
            number = number.substring(0, 500);
            $('.js-text').val(number);
            $('.js-number').text('500/500');
        } else {
            $('.js-number').text(number.length + '/500');
        };
        def.data.description = number;
    })
})