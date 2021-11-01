/*
 * @Author: SLC
 * @Date: 2020-06-19 16:11:28
 * @LastEditors: SLC
 * @LastEditTime: 2020-06-29 18:45:49
 * @Description: file content
 */
require.config({
    paths: {
        lazyload: ["lib/jquery.lazyload.min"],
        jquery_mousewheel: ["lib/jquery.mousewheel"],
        perfectScrollbar: ["lib/perfect-scrollbar"],
    },
    shim: {
        lazyload: {
            deps: ["jquery"],
            exports: "lazyload",
        },
        jquery_mousewheel: {
            deps: ["jquery"],
            exports: "jquery_mousewheel",
        },
        perfectScrollbar: {
            deps: ["jquery", "jquery_mousewheel"],
            exports: "perfectScrollbar",
        },
    },
});

require([
    "jquery",
    "general",
    "lazyload",
    "perfectScrollbar",
], function (jquery, general, perfectScrollbar) {
    $(function () {
        var sor = location.search.match(/.*sor=(\d*).*/);
        var def = {
            loadingEl: $('.js-loading-div'),
            loadingBgEl: $('.js-bg-div'),
            tipEl: $('.tip-box'),
            timeout: null,
            cond: $('#cond'),	//数据
            cond_data: {
                params: '',
                key: ''
            },
            filterFirst: false,
            listFirst: false,
            is_collect: false
        }

        def.cond_data.params = def.cond.data('params') || '';
        def.cond_data.key = def.cond.data('key') || '';
        // var global_storage = general.fn.getLocalSto('global_storage') || {};
        var sorArr = def.cond_data.params.match(/sor_([^-]*)\/*/) || [0, 1];
        var sor = parseInt(sorArr[1]);
        $('.js-type-list>li').eq(sor - 1).addClass('on').siblings().removeClass('on');

        // 页面加载控制
        function loadingToggle(type) {
            if (type === true) {
                def.loadingEl.fadeIn(200);
                def.loadingBgEl.fadeIn(400);
            } else if (type === false) {
                def.loadingEl.fadeOut(200);
                def.loadingBgEl.fadeOut(400);
            } else {
                def.loadingEl.fadeToggle(200);
                def.loadingBgEl.fadeToggle(400);
            }
        }

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

        /* ====================== 列表控制器 ====================== */

        // tab切换
        $('.js-type-list').on('click', 'li', function () {
            var idx = $(this).index() + 1;
            location.href = '/trendspattern/' + dealParam(def.cond_data.params, 'sor', idx) + '/' + location.search;
        })

        // 季节下拉
        $('.js-select-nav').on('click', '.js-season-more', function () {
            if ($(this).hasClass('on')) {
                $(this).removeClass('on').prev().addClass('on');
            } else {
                $(this).addClass('on').prev().removeClass('on');
            }
        })

        //搜索
        $('.js-neiye-list').on('click', function () {
            var _keywords = $('.js-sear-input').val();
            if ($.trim(_keywords) == '' || $.trim(_keywords) == '搜索') {
                return false;
            }
            editKeyJump(_keywords);
        });

        $('.js-search-box').on('keydown', function (ev) {
            var _keywords = $('.js-sear-input').val();
            if ($.trim(_keywords) == '') {
                return;
            }
            var e = ev || window.event;
            var keycode = e.keyCode || e.which;
            if (keycode == 13) {
                editKeyJump(_keywords);
            }
        });

        // 分页
        $(".js-rightc-bottom").on("click", '#goBtn', function () {
            //获取需要跳转到的页码
            var page = $('#J_GoPage').val();
            page = parseInt(page);
            //获取总页数pageCount
            var pageCount = parseInt($("#_pageCount").val());
            if (page >= pageCount) {
                page = pageCount;
            }
            //获取需要跳转到的URL
            var url = $('#goBtn').attr('data');
            var search = $('#goBtn').attr('search');
            if (isNaN(page)) {
                return false;
            }
            window.location.href = url + 'page_' + page + '/' + (search ? search : '');
        });
        $('.js-rightc-bottom').on('keypress', '#J_GoPage', goto);
        $('.js-rightc-bottom').on('focus', '#J_GoPage', function () {
            $(this).val('');
        });
        $('.js-rightc-bottom').on('blur', '#J_GoPage', function () {
            var $self = $(this);
            if (!$.trim($self.val())) {
                $self.val(this.defaultValue);
            }
        });

        // 跳转详情
        $('.js-trends-list').on('click', 'a[data-href]', function () {
            if (userType === 'GENERAL') {
                $('.js-trend-prompt').slideDown();
            } else {
                var data = $(this).parents('li').data();
                var frame_url = '/trendspattern/detail/?id=' + data.id + '&t=' + data.t + '&col=' + (data.col || '')/*  + '&refresh=1' */;
                window.open(frame_url);
            }
        })

        // 最新趋势 普通用户提示
        $('.js-trend-prompt').on('click', '.close', function () {
            $('.js-trend-prompt').slideUp(200);
        })

        // 获取筛选项
        getFilterconditions();
        function getFilterconditions() {
            $.ajax({
                url: '/trendspattern/filterconditions/',
                dataType: 'json',
                data: def.cond_data,
                success: function (res) {
                    if (res.code === 0) {
                        var data = res.data || {};
                        for (var i in data) {
                            if (!data[i]) { data[i] = []; }
                            var item = $('.js-select-nav>div[data-name=' + i + ']');
                            var _html = '';
                            for (var j = 0; j < data[i].length; j++) {
                                _html += '<a href="' + data[i][j].link + '">' + data[i][j].name + '</a>';
                            }
                            if (data[i].length) {
                                item.children('div').html(_html);
                            } else {
                                item.hide();
                            }
                            if (i === 'iSeason' && item.children('div').height() > 40) {
                                item.children('div').addClass('on');
                                $('.js-season-more').show();
                            }
                        }
                        var sel = $('.js-select-nav>div');
                        var count = 0;
                        for (var i = 0; i < sel.length; i++) {
                            if ($(sel[i]).css('display') === 'none') {
                                count++;
                            }
                        }
                        if (count === sel.length) {
                            $('.js-select-nav').hide();
                        }
                    }
                },
                complete: function () {
                    def.filterFirst = true;
                    if (def.listFirst) {
                        loadingToggle(false);
                    }
                }
            })
        }

        // 获取报告列表数据
        getList();
        function getList() {
            $.ajax({
                url: '/trendspattern/getList/',
                dataType: 'json',
                data: def.cond_data,
                success: function (res) {
                    if (res.code === 0) {
                        var data = res || {};
                        var list = data.data.list || [], selected = data.data.selected || '', page_html = data.data.pageHtml, total = data.info.total || 0;
                        // $('.js-finds-tyle>span').text(total);		//总数
                        $('.js-rightc-bottom').show();
                        // $('.js-list-more').hide();
                        if (list.length > 0) {
                            $('.js-rightc-bottom').html(page_html);
                            setTrendsList(list);
                        } else {
                            $('.js-trends-null').show();
                            $('.js-trends-section').hide();
                        }
                        //写入已选择筛选项
                        var select_html = '';
                        if (selected) {
                            for (var i in selected) {
                                var cancelLink = selected[i].cancelLink || '/trendspattern/' + (def.cond_data.params ? def.cond_data.params + '/' : '');
                                select_html += '<a href="' + cancelLink + '">' + selected[i].name + '：' + selected[i].value + '<span class="close"></span></a>';
                                $('.js-select-nav>div[data-key=' + i + ']').hide();
                            }
                            $('.js-keycho>div').html(select_html);
                            if (select_html) {
                                $('.js-keycho').show();
                                $('.js-select-nav').show();
                            } else {
                                $('.js-keycho').hide();
                            }
                        }
                    }
                },
                complete: function () {
                    def.listFirst = true;
                    if (def.filterFirst) {
                        loadingToggle(false);
                    }
                }
            })
        }

        // 渲染列表
        function setTrendsList(list) {
            list = list || [];
            if (list.length > 0) {
                var _html = '', len = list.length;
                for (var i = 0; i < len; i++) {
                    var labels = list[i].labels || '';
                    _html += '<li data-id="' + list[i].id + '" data-t="' + list[i].t + '" data-col="' + list[i].col + '" data-handle="' + (list[i].collect_status ? 'cancel' : 'join') + '">' +
                        '<div class="collect-data js-collect-data ' + (list[i].collect_status ? 'on' : '') + '"></div>' +
                        '<a data-href="' + list[i].detail_url + '" title="' + list[i].title + '">';
                        if(i < 12) {
                            _html +='<img src="' + list[i].cover + '" alt="' + list[i].title + '">';
                        } else {
                            _html +='<img src="' + general.def.img_path + '/global/images/common/reference150-150.gif" data-original="' + list[i].cover + '" alt="' + list[i].title + '">';
                        }
                        _html +='</a>' +
                        '<div class="text-down">' +
                        '<a data-href="' + list[i].detail_url + '">' +
                        '<p class="time clear">' +
                        '<span class="fl">浏览（' + list[i].view + '）</span>' +
                        '<span class="fr">' + list[i].publish_time + '</span>' +
                        '</p>' +
                        '<p class="title" title="' + list[i].title + '">' + list[i].title + '</p>' +
                        '</a>' +
                        '<div class="label">';
                    for (var j = 0; j < labels.length; j++) {
                        _html += '<a href="' + labels[j].lLink + '" title="' + labels[j].name + '">' + labels[j].name + '</a>'
                    }
                    _html += '</div></div></li>';
                }
                $('.js-trends-list').html(_html);
                $('.js-trends-list img').lazyload({ effect: "fadeIn" });
                $(window).scrollTop($(window).scrollTop() + 1);
            }
        }

        // 页码跳转
        function goto(event) {
            if (event.which == 13) {
                //获取需要跳转到的页码
                var page = $('#J_GoPage').val();
                page = parseInt(page);
                //获取总页数pageCount
                var pageCount = parseInt($("#_pageCount").val());
                if (page >= pageCount) {
                    page = pageCount;
                }
                //获取需要跳转到的URL
                var url = $('#goBtn').attr('data');
                var search = $('#goBtn').attr('search');
                if (isNaN(page)) {
                    return false;
                }
                window.location.href = url + 'page_' + page + '/' + (search ? search : '');
            }
        };

        //搜索
        function editKeyJump(val) {
            var param = def.cond_data.params, _url = '/trendspattern/';
            // 页码如果存在则将页码去掉
            if (param.indexOf('-page_') !== -1) {
                var reg = RegExp('-page_([^-]*)', 'gi');
                param = param.replace(reg, '');
            } else if (param.indexOf('page_') !== -1) {
                var reg = RegExp('page_([^-]*)', 'gi');
                param = param.replace(reg, '');
            }
            // 关键字条件
            val = encodeURIComponent(encodeURIComponent(val));
            if ($.trim(param) != '') {
                // 不存在则增加
                if (param.indexOf('?key=') === -1) {
                    param = param.replace(/\/$/, '') + '/?key=' + val;
                }
                // 否则替换
                else {
                    param = param.replace(/\/$/, '').replace(/\?key=([^&]*)/gi, '/?key=' + val);
                }
            } else {
                param = '?key=' + val;
            }
            location.href = _url + param.replace(/^\//, '');
        }

        // 对参数`进行处理
        function dealParam(param, type, _val) {
            var val, param_str;
            if(typeof _val == 'undefined') {
                val = 1;
            }else {
                val = _val;
            }
            if($.trim(param) == null || !param) {
                param_str = type+'_'+val;
            }else {
                var param_arr = param.split('-');
                // flag=false=》不存在本身的参数直接添加，falg=1=》去掉参数   page=true=>去掉page参数
                var flag = page = false;
                for(var i = 0 in param_arr) {
                    var type_val_arr = param_arr[i].split('_');
                    if(type_val_arr[0] == 'page') {
                        var page_index = i;
                        page = true;
                    }
                    // 存在自身的type
                    if(type_val_arr[0] == type) {
                        if(type_val_arr[1] == val || type == 'coll' || type == 'vect') {
                            // 再次点击本身去掉参数
                            if(type == 'coll' || type == 'vect' || type == 'ihot' || type == 'uli' || type == 'auth' || type == 'off' || type == 'popky' || type == "tlive"){
                                var type_index = i;
                                flag = 1;
                            }else {
                                return param;
                            }

                        }else if(val == 'all') {
                            // 点击全部去掉本身的参数
                            var type_index = i;
                            flag = 1;
                        }else {
                            if(type == 'ihot') {
                                if(type_val_arr[1] == 3) {
                                    type_val_arr[1] = 3 - val;
                                }else {
                                    type_val_arr[1] = 3;
                                }
                            }else {
                                type_val_arr[1] = val;
                            }
                            flag = true;
                        }
                    }
                    type_val_str = type_val_arr.join('_');
                    param_arr[i] = type_val_str;
                }
                if(page) {
                    param_arr.splice(page_index, 1);
                }
                // 不存在本身的参数或者不是点击了全部则直接添加参数
                if(!flag && val != 'all') {
                    param_arr.push(type+'_'+val);
                }else if(flag === 1) {
                    param_arr.splice(type_index, 1);
                }
                param_str = param_arr.join('-');
            }
            return param_str;
        }
    });
});
