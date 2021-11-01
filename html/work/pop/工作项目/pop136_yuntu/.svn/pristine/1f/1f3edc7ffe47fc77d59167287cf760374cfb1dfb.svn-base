

/**
 * fanglinna
 * 20190428
 */

// 收藏
require(['jquery', 'general', 'msg'], function (jquery, general, msg) {
    $(function () {
        var def = {
            loadingEl: $('.js-loading-div'),
            loadingBgEl: $('.js-bg-div'),
            tipEl: $('.tip-box'),
            timeout: null,
            is_collect: false,
            collect_data: {}
        }
        $(".js-lists-parent").on("click", '.js-collect-data', function (e) {
            general.fn.stopBubble(e);
            if (!def.is_collect) {
                collectFunc($(this));
            }
        })

        // tip提示控制
        function tipToggle(msg, type) {
            if (type) {
                def.tipEl.addClass('on');
            } else {
                def.tipEl.removeClass('on');
            }
            def.tipEl.text(msg).fadeIn(200);
            clearTimeout(def.timeout);
            def.timeout = setTimeout(function () {
                def.tipEl.fadeOut(200);
                clearTimeout(def.timeout);
            }, 2000);
        }

        function collectFunc(obj) {
            def.is_collect = true;
            var data_a = obj.parents("li");
            var id = data_a.data("id");
            var t = data_a.data("t");
            actionFunc(t, id, 'collect', request_id, scene_type)
            if (obj.hasClass("on")) {
                def.collect_data = {
                    id: id,
                    t: t,
                    handle: 'cancel'
                }
            } else {
                def.collect_data = {
                    id: id,
                    t: t,
                    handle: 'join'
                }
            }
            $.ajax({
                url: '/collect/setcollect/' + Math.random(),
                type: 'POST',
                data: def.collect_data,
                success: function (data) {
                    // 取消成功
                    if (parseInt(data.data.code) === 10) {
                        obj.removeClass('on');
                        // msg.msg({ 'txt': data.data.msg }, 1200);
                        tipToggle('已取消收藏');
                    }
                    // 加入成功
                    if (parseInt(data.data.code) === 20) {
                        obj.addClass('on');
                        // msg.msg({ 'txt': data.data.msg }, 1200);
                        tipToggle('已收藏');
                    }
                    def.is_collect = false;
                },
                error: function () {
                    def.is_collect = false;
                }
            })
        }

        // 用户行为统计
        var action_data = {
            t: "",
            id: "",
            userid: userId,
            site: 1,
            action_type: "",
            timestamp: ""
        };
        var iframe_src = $(".js-detail-frame", parent.document).find("iframe").attr("src")
        var win_search = general.fn.getLocationParameter(iframe_src);
        var request_id = win_search.request_id || "";
        var scene_type = win_search.scene_type || "";
        function actionFunc(tablename, id, action_type, request_id, scene_type, func) {
            if (userType == "TOURIST") {
                return false;
            }
            action_data.request_id = request_id;
            action_data.scene_type = scene_type;
            action_data.t = tablename;
            action_data.id = id;
            action_data.action_type = action_type;
            action_data.timestamp = new Date().getTime();
            $.ajax({
                type: "get",
                dataType: "jsonp",
                url: '//api.pop136.com/internal/datagrand.php?' + Math.random(),
                data: action_data,
                success: func
            })
        }
    });
})

