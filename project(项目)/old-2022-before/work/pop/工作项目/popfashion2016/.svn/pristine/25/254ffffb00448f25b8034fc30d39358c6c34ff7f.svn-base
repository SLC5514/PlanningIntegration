// 中间页咨询小秘书弹窗
$(function () {
    function qqTimer(delayt) {
        setTimeout(function () {
            pop_fashion_global.fn.subAjaxGet({
                url: '/ajax/getalertlayeradforqq/',
                successFunc: function (data) {
                    var $up_img = $(".update_img");
                    if (data["data"][0]["sNewWindow"] === 1) {
                        $up_img.attr("target", '_blank');
                    }
                    var sLink = data["data"][0]["sLink"] || '';
                    var sImagePath = data["data"][0]["sImagePath"] || '';
                    var sTitle = data["data"][0]["sTitle"] || '';

                    $up_img.attr("href", sLink);
                    $up_img.find('img').attr("src", sImagePath);
                    $up_img.attr("title", sTitle);
                }
            });
            $(".waikuang").show();
            if (!$.cookie('delayTime')) {
                $.cookie('delayTime', 'delayTime', {path: '/'});
            }
        }, delayt);
    }

    $('body').on('click', '.close', function () {
        $(".waikuang").hide();
    });
    if ($.cookie('delayTime') === 'delayTime') {
        qqTimer(1800000);//30min
    } else {
        qqTimer(120000);//2min
    }
});

