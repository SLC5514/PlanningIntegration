define(["jquery", "general", "msg", "perfectScrollbar"], function (jquery, general, msg, perfectScrollbar) {
    $(function () {
        var def = {
            params: general.fn.getLocationParameter() || {},
            loadingEl: $('.js-loading-div'),
            loadingBgEl: $('.js-bg-div'),
            tipEl: $('.tip-box'),
            psBars: {}, // 存放滚动条控制器
            groupData: {
                el: $('.js-group-list'),
                list: [],
                page: 0,
                pageSize: 30,
                total: 0,
                isLoad: false,
                editEl: null,
                group_id: '',
                group_name: '',
                defImgSrc: general.def.img_path + '/global/images/user/placeholder.png'
            },
            virData: {
                el: $('.js-vir-list'),
                page: 0,
                pageSize: 30,
                total: 0,
                isLoad: false,
                group_id: '',
                checkIds: [],
            },
            detailData: {
                el: $('.js-groupvir-list'),
                page: 0,
                pageSize: 30,
                total: 0,
                isLoad: false,
                group_id: '',
                checkIds: [],
            },
        };
        // loadingToggle(false);

        initScrollbarH($('.js-scroll-Sb'), true);

        listGroup();
        // 分组详情
        if (def.params.page === 'groupdetail') {
            def.detailData.group_id = def.params.group_id;
            groupidList(def.detailData.group_id);
        } else if (!def.params.page || def.params.page === 'masterroom-group' || !def.params.page) {
            centerGroup();
        } else if (def.params.page === 'masterroom-virtual') {
            groupidList(def.virData.group_id, true);
        }

        // 页面尺寸改变时实时触发
        $(window).on("resize", function () {
            if (def.params.page === 'groupdetail') {
                general.fn.throttle(waterFall, null, ['.js-water-fall3', true], 18);
            } else if (def.params.page === 'masterroom-virtual') {
                general.fn.throttle(waterFall, null, ['.js-water-fall2', true], 18);
            }/*  else if (def.params.page === 'masterroom-group' || !def.params.page) {
                general.fn.throttle(waterFall, null, ['.js-water-fall1', true], 18);
            } */
        });
        $(window).on("scroll", function () {
            if (def.params.page === 'groupdetail') {
                general.fn.throttle(sLoadGroupDetail, this, [], 18);
            } else if (def.params.page === 'masterroom-group' || !def.params.page) {
                general.fn.throttle(sLoadGroup, this, [], 18);
            } else if (def.params.page === 'masterroom-virtual') {
                general.fn.throttle(sLoadVir, this, [], 18);
            }
        });

        // 分组全部切换/批量管理
        $('.js-lab-list').on('click', 'a', function () {
            if ($(this).hasClass('batch-manage')) {
                if (def.virData.el.hasClass('checkbtn-list-on')) {
                    def.virData.checkIds = [];
                    $(this).text('批量管理');
                    $('.js-check-col').removeClass('on');
                    def.virData.el.removeClass('checkbtn-list-on');
                    def.virData.el.find('li .check-btn').removeClass('on');
                } else {
                    if (def.virData.el.find('li').length <= 1) {
                        tipToggle('没有可批量管理的内容！');
                        return;
                    }
                    def.virData.el.addClass('checkbtn-list-on');
                    $(this).text('完成').addClass('on');
                    $('.js-check-col').addClass('on');
                }
            }/*  else {
                var idx = $(this).index();
                $(this).addClass('on').siblings().removeClass('on');
                $('.js-lab-ctn').children().eq(idx).show().siblings().hide();
                if (idx === 1) {
                    $('.js-batch-manage').addClass('on');
                    def.virData.group_id = '';
                } else {
                    $('.js-batch-manage').removeClass('on').text('批量管理');
                    $('.js-check-col').removeClass('on');
                    def.virData.checkIds = [];
                    def.virData.el.removeClass('checkbtn-list-on');
                    def.virData.el.find('li .check-btn').removeClass('on');
                }
            } */
        })

        // 效果图增删操作
        $('.js-check-col').on('click', 'a', function () {
            var self = $(this);
            if (self.hasClass('setall')) {
                def.virData.checkIds = [];
                if (self.hasClass('cancel')) {
                    self.removeClass('cancel').text('全选');
                    def.virData.el.find('li .check-btn').removeClass('on');
                } else {
                    self.addClass('cancel').text('取消全选');
                    def.virData.el.find('li .check-btn').addClass('on');
                    def.virData.el.find('li').each(function (i, v) {
                        var id = $(v).data('id');
                        id && def.virData.checkIds.push(id);
                    })
                }
            } else if (self.hasClass('additem')) {
                if (def.virData.el.find('li .check-btn.on').length < 1) {
                    tipToggle('请先勾选内容');
                    return;
                }
                $('.js-addmegroup-box').fadeIn(200);
                def.loadingBgEl.fadeIn(400);
                $('#addMeGroupIpt').val('');
                var str = '';
                for (var i = 0; i < def.groupData.list.length; i++) {
                    str += '<li data-id="' + def.groupData.list[i].id + '"><span>' + def.groupData.list[i].sGroupName + '</span><div class="btn">添加</div></li>';
                }
                $('.js-megroup-list').html(str);
                def.psBars[$('.js-megroup-list').data('sbkey')].update();
                if (def.groupData.list.length) {
                    $('.js-addmegroup-box .js-megroup-list').show();
                    $('.js-addmegroup-box .empty').hide();
                    $('.js-addmegroup-box .confirm').hide();
                } else {
                    $('.js-addmegroup-box .js-megroup-list').hide();
                    $('.js-addmegroup-box .empty').show();
                    $('.js-addmegroup-box .confirm').show();
                }
            } else if (self.hasClass('delete')) {
                if (def.virData.el.find('li .check-btn.on').length < 1) {
                    tipToggle('请先勾选内容');
                    return;
                }
                $('.js-dialog-box').attr('store', 'delete').fadeIn(200).dialogBack(function () {
                    delImg(def.virData.checkIds, function () {
                        def.virData.el.find('li').each(function (i, v) {
                            var id = $(v).data('id');
                            def.virData.checkIds.forEach(function (val, idx) {
                                if (id && (id === val)) {
                                    $(v).remove();
                                }
                            })
                        })
                        waterFall('.js-water-fall2');
                        def.virData.checkIds = [];
                        // def.virData.el.removeClass('checkbtn-list-on');
                        // def.virData.el.find('li .check-btn').removeClass('on');
                        // $('.js-batch-manage').text('批量管理');
                        // $('.js-check-col').removeClass('on');
                    });
                }, null, true);
            }
        })

        // 分组详情增删操作
        $('.js-vircheck-col').on('click', 'a', function () {
            var self = $(this);
            if (self.hasClass('setall')) {
                def.detailData.checkIds = [];
                if (self.hasClass('cancel')) {
                    self.removeClass('cancel').text('全选');
                    def.detailData.el.find('li .check-btn').removeClass('on');
                } else {
                    self.addClass('cancel').text('取消全选');
                    def.detailData.el.find('li .check-btn').addClass('on');
                    def.detailData.el.find('li').each(function (i, v) {
                        var id = $(v).data('id');
                        id && def.detailData.checkIds.push(id);
                    })
                }
            } else if (self.hasClass('remove')) {
                if (def.detailData.el.find('li .check-btn.on').length < 1) {
                    tipToggle('请先勾选内容');
                    return;
                }
                removeImg(def.groupData.group_id, def.detailData.checkIds, function () {
                    def.detailData.el.find('li').each(function (i, v) {
                        var id = $(v).data('id');
                        def.detailData.checkIds.forEach(function (val, idx) {
                            if (id && (id === val)) {
                                $(v).remove();
                            }
                        })
                    })
                    waterFall('.js-water-fall3');
                    def.detailData.checkIds = [];
                    def.detailData.el.removeClass('checkbtn-list-on');
                    def.detailData.el.find('li .check-btn').removeClass('on');
                    $('.js-col-box .batch-manage').text('批量管理');
                    $('.js-vircheck-col').removeClass('on');
                });
            } else if (self.hasClass('moveto')) {
                if (def.detailData.el.find('li .check-btn.on').length < 1) {
                    tipToggle('请先勾选内容');
                    return;
                }
                $('.js-movegroup-box').fadeIn(200);
                def.loadingBgEl.fadeIn(400);
                $('#moveGroupIpt>span').text('选择分组').removeClass('on').data('id', '');
                var str = '';
                for (var i = 0; i < def.groupData.list.length; i++) {
                    str += '<li data-id="' + def.groupData.list[i].id + '">' + def.groupData.list[i].sGroupName + '</li>';
                }
                $('.js-movegroup-list').html(str);
                def.psBars[$('.js-movegroup-list').data('sbkey')].update();
            } else if (self.hasClass('delete')) {
                if (def.detailData.el.find('li .check-btn.on').length < 1) {
                    tipToggle('请先勾选内容');
                    return;
                }
                $('.js-dialog-box').attr('store', 'delete').fadeIn(200).dialogBack(function () {
                    delImg(def.detailData.checkIds, function () {
                        def.detailData.el.find('li').each(function (i, v) {
                            var id = $(v).data('id');
                            def.detailData.checkIds.forEach(function (val, idx) {
                                if (id && (id === val)) {
                                    $(v).remove();
                                }
                            })
                        })
                        waterFall('.js-water-fall3');
                        def.detailData.checkIds = [];
                        def.detailData.el.removeClass('checkbtn-list-on');
                        def.detailData.el.find('li .check-btn').removeClass('on');
                        $('.js-col-box .batch-manage').text('批量管理');
                        $('.js-vircheck-col').removeClass('on');
                    });
                }, null, true);
            }
        })

        // 分组详情操作
        $('.js-col-box').on('click', 'div', function () {
            if ($(this).hasClass('edit')) {
                $('.js-dialog-box .group-name').val(def.groupData.group_name);
                $('.js-dialog-box').attr('store', 'deit-group').fadeIn(200).dialogBack(function () {
                    editGroup(def.detailData.group_id);
                }, function () {
                    $('.js-dialog-box').attr('store', 'delete-group').fadeIn(200).dialogBack(function () {
                        delGroup(def.detailData.group_id);
                    });
                });
            } else if ($(this).hasClass('batch-manage')) {
                if (def.detailData.el.hasClass('checkbtn-list-on')) {
                    def.detailData.el.removeClass('checkbtn-list-on');
                    $(this).text('批量管理');
                    $('.js-vircheck-col').removeClass('on');
                    def.detailData.checkIds = [];
                    def.detailData.el.removeClass('checkbtn-list-on');
                    def.detailData.el.find('li .check-btn').removeClass('on');
                } else {
                    if (def.detailData.el.find('li').length <= 1) {
                        tipToggle('没有可批量管理的内容！');
                        return;
                    }
                    def.detailData.el.addClass('checkbtn-list-on');
                    $(this).text('完成').addClass('on');
                    $('.js-vircheck-col').addClass('on');
                }
            } else if ($(this).hasClass('share')) {
                if (def.detailData.el.find('li').length <= 1) {
                    tipToggle('没有可批量管理的内容！');
                    return;
                }
                $('.js-share-box').removeClass('on');
                $('.js-share-box').fadeIn(200);
                def.loadingBgEl.fadeIn(400);
            }
        })

        // 分享弹窗
        $('.js-share-box').on('click', '.close,.cancel', function () {
            $('.js-share-box').fadeOut(200);
            def.loadingBgEl.fadeOut(400);
        }).on('click', '.confirm', function () {
            $('.js-share-box').fadeOut(200);
            var is_encrypt = $('.js-share-box .radio>.on').data('id');
            var expire_day = $('#shareIpt>span').data('id');
            saveMyShareSet(def.groupData.group_id, is_encrypt, expire_day, function (data) {
                $('.js-share-box').addClass('on');
                $('.js-share-box').fadeIn(200);
                def.loadingBgEl.fadeIn(400);
                def.loadingEl.fadeOut(200);
                if (is_encrypt == 1) {
                    $('.js-share-box .yq-link>input').val(window.location.origin + data.shareUrl);
                    $('.js-share-box .yq-code').show().find('input').val(data.key);
                    $('.js-share-box .ctn').removeClass('vcenter');
                    $('.js-share-box .confirm-copy').removeClass('confirm-link').text('复制链接及邀请码');
                } else {
                    $('.js-share-box .yq-link>input').val(window.location.origin + data.shareUrl);
                    $('.js-share-box .yq-code').hide().find('input').val('');
                    $('.js-share-box .ctn').addClass('vcenter');
                    $('.js-share-box .confirm-copy').addClass('confirm-link').text('复制链接');
                }
                $('.js-share-box .yq-time').text('有效期：' + data.expireDate.replace(/(\d{4}-\d{2}-\d{2}\s\d{2}:\d{2}):\d{2}/, '$1'));
            });
        }).on('click', '.confirm-copy', function () {
            if ($(this).hasClass('confirm-link')) {
                var text = $('.js-share-box .yq-link>input').val();
                var status = copyText(text);
                status ? tipToggle('复制成功', true) : tipToggle('复制失败');
            } else {
                var text = '链接：' + $('.js-share-box .yq-link>input').val() + '\n邀请码：' + $('.js-share-box .yq-code>input').val();
                var status = copyText(text);
                status ? tipToggle('复制成功', true) : tipToggle('复制失败');
            }
        })
        $('.js-share-box .radio').on('click', 'li', function () {
            $(this).addClass('on').siblings().removeClass('on');
        })
        $('.js-share-box .select').on('mouseenter', function () {
            $(this).find('ul').addClass('on');
        }).on('mouseleave', function () {
            $(this).find('ul').removeClass('on');
        }).on('click', 'li', function () {
            $('#shareIpt>span').data('id', $(this).data('id')).text($(this).text()).addClass('on')
            $('#shareIpt>ul').removeClass('on');
        })

        // 移动至分组
        $('.js-movegroup-box').on('click', '.close', function () {
            $('.js-movegroup-box').fadeOut(200);
            def.loadingBgEl.fadeOut(400);
        }).on('click', '.confirm', function () {
            var id = $('#moveGroupIpt>span').data('id');
            if (id) {
                $('.js-movegroup-box').fadeOut(200);
                moveAddImg(id, def.detailData.checkIds, function () {
                    def.detailData.el.find('li').each(function (i, v) {
                        var id = $(v).data('id');
                        def.detailData.checkIds.forEach(function (val, idx) {
                            if (id && (id === val)) {
                                $(v).remove();
                            }
                        })
                    })
                    waterFall('.js-water-fall3');
                    def.detailData.checkIds = [];
                    // def.detailData.el.removeClass('checkbtn-list-on');
                    // def.detailData.el.find('li .check-btn').removeClass('on');
                    // $('.js-col-box .batch-manage').text('批量管理');
                    // $('.js-vircheck-col').removeClass('on');
                });
            } else {
                tipToggle('请选择分组');
            }
        })
        $('#moveGroupIpt').on('mouseenter', function () {
            $(this).find('ul').addClass('on');
            def.psBars[$('.js-movegroup-list').data('sbkey')].update();
        }).on('mouseleave', function () {
            $(this).find('ul').removeClass('on');
        }).on('click', 'li', function () {
            $('#moveGroupIpt>span').data('id', $(this).data('id')).text($(this).text()).addClass('on')
            $('#moveGroupIpt>ul').removeClass('on');
        })

        // 分组详情批量管理/详情
        def.detailData.el.on('click', 'li', function () {
            var self = $(this);
            var btn = self.find('.check-btn');
            var id = self.data('id');
            var tid = self.data('tid');
            if (self.hasClass('add')) {
                // if ($('.params').val() > 0) {
                //     window.location.href = '/system/snapshot/?type=1';
                //     return;
                // }
                window.location.href = '/virtualtryon/virtualspl/';
            } else if (def.detailData.el.hasClass('checkbtn-list-on')) {
                if (btn.hasClass('on')) {
                    btn.removeClass('on');
                    def.detailData.checkIds.splice(def.detailData.checkIds.indexOf(id), 1);
                } else {
                    btn.addClass('on');
                    def.detailData.checkIds.push(id);
                }
            } else {
                // if ($('.params').val() > 0) {
                //     window.location.href = '/system/snapshot/?type=1';
                //     return;
                // }
                window.location.href = '/virtualtryon/virtualspl/?did=' + id + '&tid=' + tid + '&gid=' + def.detailData.group_id;
            }
        })

        // 添加至我的分组
        $('.js-addmegroup-box').on('click', '.close', function () {
            $('.js-addmegroup-box').fadeOut(200);
            def.loadingBgEl.fadeOut(400);
        }).on('click', '.btn', function () {
            $('.js-addmegroup-box').fadeOut(200);
            moveImg($(this).parent().data('id'), def.virData.checkIds/* , function () {
                def.virData.checkIds = [];
                def.virData.el.removeClass('checkbtn-list-on');
                def.virData.el.find('li .check-btn').removeClass('on');
                $('.js-batch-manage').text('批量管理');
                $('.js-check-col').removeClass('on');
            } */);
        }).on('click', '.confirm', function () {
            addGroup($('#addMeGroupIpt').val(), function() {
                $('#addMeGroupIpt').val('');
                var val = $('#addMeGroupIpt').val();
                var str = '';
                for (var i = 0; i < def.groupData.list.length; i++) {
                    str += '<li data-id="' + def.groupData.list[i].id + '"><span>' + def.groupData.list[i].sGroupName + '</span><div class="btn">添加</div></li>';
                }
                $('.js-megroup-list').html(str);
                def.psBars[$('.js-megroup-list').data('sbkey')].update();
                if (str.length) {
                    $('.js-addmegroup-box .js-megroup-list').show();
                    $('.js-addmegroup-box .empty').hide();
                    $('.js-addmegroup-box .confirm').hide();
                } else {
                    $('.js-addmegroup-box .js-megroup-list').hide();
                    $('.js-addmegroup-box .empty').show();
                    $('.js-addmegroup-box .confirm').show();
                }
            });
            // $('.js-addmegroup-box').fadeOut(200);
            // def.loadingBgEl.fadeOut(400);
        })

        // 添加分组上限
        $('.js-group-limit-box').on('click', '.close,.cancel,.confirm', function () {
            $('.js-group-limit-box').fadeOut(200);
            def.loadingBgEl.fadeOut(400);
        })

        // 筛选我的分组
        $('#addMeGroupIpt').on('input', function () {
            var val = this.value;
            var str = '';
            for (var i = 0; i < def.groupData.list.length; i++) {
                if (def.groupData.list[i].sGroupName.includes(val)) {
                    str += '<li data-id="' + def.groupData.list[i].id + '"><span>' + def.groupData.list[i].sGroupName + '</span><div class="btn">添加</div></li>';
                }
            }
            $('.js-megroup-list').html(str);
            def.psBars[$('.js-megroup-list').data('sbkey')].update();
            if (str.length) {
                $('.js-addmegroup-box .js-megroup-list').show();
                $('.js-addmegroup-box .empty').hide();
                $('.js-addmegroup-box .confirm').hide();
            } else {
                $('.js-addmegroup-box .js-megroup-list').hide();
                $('.js-addmegroup-box .empty').show();
                $('.js-addmegroup-box .confirm').show();
            }
        })

        // 效果图批量管理/详情
        def.virData.el.on('click', 'li', function () {
            var self = $(this);
            var btn = self.find('.check-btn');
            var id = self.data('id');
            var tid = self.data('tid');
            if (self.hasClass('add')) {
                // if ($('.params').val() > 0) {
                //     window.location.href = '/system/snapshot/?type=1';
                //     return;
                // }
                window.location.href = '/virtualtryon/virtualspl/';
            } else if (def.virData.el.hasClass('checkbtn-list-on')) {
                if (btn.hasClass('on')) {
                    btn.removeClass('on');
                    def.virData.checkIds.splice(def.virData.checkIds.indexOf(id), 1);
                } else {
                    btn.addClass('on');
                    def.virData.checkIds.push(id);
                }
            } else {
                // if ($('.params').val() > 0) {
                //     window.location.href = '/system/snapshot/?type=1';
                //     return;
                // }
                window.location.href = '/virtualtryon/virtualspl/?did=' + id + '&tid=' + tid + '&gid=all';
            }
        })

        // 创建分组/分组详情
        def.groupData.el.on('click', 'li', function (e) {
            var e = e || window.event;
            var target = $(e.target);
            if ($(this).hasClass('add')) {
                def.groupData.group_name = '';
                $('.js-dialog-box .group-name').val(def.groupData.group_name);
                $('.js-dialog-box').attr('store', 'add-group').fadeIn(200).dialogBack(function () {
                    addGroup($('.js-dialog-box .group-name').val());
                });
            } else {
                def.groupData.group_id = $(this).data('id');
                def.groupData.group_name = $(this).data('name');
                // 编辑分组
                if (target.hasClass('edit')) {
                    def.groupData.editEl = $(this);
                    $('.js-dialog-box .group-name').val(def.groupData.group_name);
                    $('.js-dialog-box').attr('store', 'deit-group').fadeIn(200).dialogBack(function () {
                        editGroup(def.groupData.group_id, true);
                    }, function () {
                        $('.js-dialog-box').attr('store', 'delete-group').fadeIn(200).dialogBack(function () {
                            delGroup(def.groupData.group_id, true);
                        });
                    });
                } else {
                    // 详情
                    window.location.href = '/user/usercenterview/?page=groupdetail&group_id=' + def.groupData.group_id;
                }
            }
        })

        // 对话框回调
        $.fn.dialogBack = function (confirmBack, cancelBack, type) {
            def.loadingBgEl.fadeIn(400);
            $('.js-dialog-box .close').off().click(function () {
                $(this).parents('.js-dialog-box').fadeOut(200);
                def.loadingBgEl.fadeOut(400);
            })
            $('.js-dialog-box .cancel').off().click(function () {
                $(this).parents('.js-dialog-box').fadeOut(200);
                def.loadingBgEl.fadeOut(400);
                cancelBack && cancelBack();
            })
            $('.js-dialog-box .confirm').off().click(function () {
                $(this).parents('.js-dialog-box').fadeOut(200);
                type && def.loadingBgEl.fadeOut(400);
                confirmBack && confirmBack();
            })
            $('.js-dialog-box .delete').off().click(function () {
                type && def.loadingBgEl.fadeOut(400);
                cancelBack && cancelBack();
            })
        }

        /**
         * @description: 滚动条初始化
         * @param {Object} el JQ DOM 对象/ queryname
         * @param {Boolean} type 返回类型 true:dom false:height
         * @param {Boolean} status 是否设置高 true:设置 false:不设置
         * @return: Number / JQ DOM
         */
        function initScrollbarH(el, type, status) {
            var el = el;
            if (typeof el === 'string') {
                el = $(el);
            }
            if (!el.length) {
                if (type) {
                    return el;
                } else {
                    return 0;
                }
            }
            var h = el.parent().height() - el.position().top - (el.siblings('.js-aside-nav-Sb-next').height() || 0);
            status && el.height(h).data('h', h);
            el.each(function (i, v) {
                var item = $(v);
                if (item.data('sbkey')) {
                    def.psBars[item.data('sbkey')].update();
                } else {
                    var sbkey = i + Math.random();
                    item.data('sbkey', sbkey);
                    def.psBars[sbkey] = new perfectScrollbar(v);
                }
            });
            if (type) {
                return el;
            } else {
                return h;
            }
        }

        // 瀑布流
        function waterFall(strName, loop) {
            var boxEl = $(strName);
            var itemEls = boxEl.find('li');
            var smallScreen = $('.js-section').width() < 1500 ? 1 : 0; // 大屏0, 小屏1, 移动2
            var pageWidth = $('.js-section').width();
            var columns = smallScreen === 0 ? 6 : 5;
            var itemWidth = 210;
            var space = (pageWidth - itemWidth * columns) / (columns - 1);
            var spaceBtm = 48;
            var defh = 276;
            var colHArr = [];
            var maxTop = 0;
            for (var i = 0; i < columns; i++) {
                colHArr[i] = 0;
            }
            itemEls.each(function (i, v) {
                var item = $(v);
                var itemImg = $(v).find('img');
                var h = itemImg.data('h');
                var minValue = colHArr[0];
                var minIndex = 0;
                if (!h) {
                    h = defh;
                    itemImg.data('h', defh);
                    if (!loop) {
                        itemImg.on('load', function () {
                            $(this).data('h', this.height);
                            waterFall(strName, true);
                        }).on('error', function () {
                            $(this).data('h', defh).height(defh);
                            waterFall(strName, true);
                        })
                    }
                }
                for (var i = colHArr.length; i >= 0; i--) {
                    if (colHArr[i] <= minValue) {
                        minValue = colHArr[i];
                        minIndex = i;
                    }
                }
                item.css({
                    left: minIndex * itemWidth + minIndex * space,
                    top: minValue + (i > columns - 1 ? spaceBtm : 0)
                })
                colHArr[minIndex] += h + spaceBtm;
                var top = parseInt(item.css('top'));
                if (maxTop < top + h) {
                    maxTop = top + h;
                    boxEl.height(maxTop);
                }
            })
        }

        // 分组列表 加载下一页
        function sLoadGroup() {
            var lastEl = def.groupData.el.children().last();
            var lastOffsetTop = lastEl.offset().top - $(window).height();
            var reH = $(window).scrollTop();
            if (!def.groupData.isLoad && lastOffsetTop <= reH) {
                centerGroup();
            }
        }

        // 效果图列表 加载下一页
        function sLoadVir() {
            var lastEl = def.virData.el.children().last();
            var lastOffsetTop = lastEl.offset().top - $(window).height();
            var reH = $(window).scrollTop();
            if (!def.virData.isLoad && lastOffsetTop <= reH) {
                groupidList(def.virData.group_id, true);
            }
        }

        // 分组详情列表 加载下一页
        function sLoadGroupDetail() {
            var lastEl = def.detailData.el.children().last();
            var lastOffsetTop = lastEl.offset().top - $(window).height();
            var reH = $(window).scrollTop();
            if (!def.detailData.isLoad && lastOffsetTop <= reH) {
                groupidList(def.detailData.group_id);
            }
        }

        // 复制文本
        function copyText(text) {
            var textarea = document.createElement("textarea");
            var currentFocus = document.activeElement;
            document.body.appendChild(textarea);
            $(textarea).css('position', 'fixed');
            textarea.value = text;
            textarea.focus();
            if (textarea.setSelectionRange) {
                textarea.setSelectionRange(0, textarea.value.length);
            } else {
                textarea.select();
            }
            try {
                var flag = document.execCommand("copy");
            } catch (eo) {
                var flag = false;
            }
            document.body.removeChild(textarea);
            currentFocus.focus();
            return flag;
        }

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
            var timeout = setTimeout(function () {
                clearTimeout(timeout);
                def.tipEl.fadeOut(200);
            }, 2000);
            if (type) {
                def.tipEl.addClass('on');
            } else {
                def.tipEl.removeClass('on');
            }
            def.tipEl.text(msg).fadeIn(200);
        }

        // 添加分组
        function addGroup(group_name, callback) {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageGroup/add_group/',
                type: 'get',
                dataType: "json",
                data: {
                    group_name: group_name
                },
                success: function (res) {
                    if (res.code === 0) {
                        var str = '';
                        str += '<li data-id="' + res.data.group_id + '" data-name="' + group_name + '"><img src="' + def.groupData.defImgSrc + '" alt=""><div class="edit">编辑</div><div class="title">' + group_name + '</div></li>';
                        def.groupData.el.find('.add').after(str);
                        // waterFall('.js-water-fall1');
                        tipToggle('创建成功', true);
                        listGroup(callback);
                        centerGroup(true, true);
                        // loadingToggle(false);
                        def.loadingEl.fadeOut(200);
                    } else if (res.code === 1001) {
                        $('.js-group-limit-box').fadeIn(200);
                        def.loadingEl.fadeOut(200);
                    } else {
                        loadingToggle(false);
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                    loadingToggle(false);
                }
            })
        }

        // 编辑分组
        function editGroup(group_id, type) {
            var group_name = $('.js-dialog-box .group-name').val();
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageGroup/edit_group/',
                type: 'get',
                dataType: "json",
                data: {
                    group_id: group_id,
                    group_name: group_name
                },
                success: function (res) {
                    if (res.code === 0) {
                        tipToggle(res.msg);
                        if (type) {
                            def.groupData.editEl.data('name', group_name).find('.title').text(group_name);
                            tipToggle(res.msg, true);
                        } else {
                            $('.js-group-name').text(group_name);
                        }
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    loadingToggle(false);
                }
            })
        }

        // 删除分组
        function delGroup(group_id, type) {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageGroup/del_group/',
                type: 'get',
                dataType: "json",
                data: {
                    group_id: group_id
                },
                success: function (res) {
                    if (res.code === 0) {
                        if (type) {
                            // def.groupData.editEl.remove();
                            // waterFall('.js-water-fall1');
                            tipToggle(res.msg, true);
                            centerGroup(true, true);
                        } else {
                            window.history.go(-1);
                            waterFall('.js-water-fall3');
                        }
                    } else {
                        loadingToggle(false);
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    loadingToggle(false);
                    console.log('for bug: ' + err)
                }
            })
        }

        // 制版间列表下拉展示所有分组
        function listGroup(callback) {
            // loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageGroup/list_group/',
                type: 'get',
                dataType: "json",
                success: function (res) {
                    if (res.code === 0) {
                        def.groupData.list = res.data.group_list;
                        callback && callback();
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    // loadingToggle(false);
                }
            })
        }

        // 分组列表（取第一张封面图）
        function centerGroup(type, mode) {
            if (type) {
                def.groupData.page = 0;
            }
            if (def.groupData.page && def.groupData.total && (def.groupData.page * def.groupData.pageSize >= def.groupData.total)) {
                return;
            }
            def.groupData.page++;
            def.groupData.isLoad = true;
            //\ !mode && loadingToggle(true);
            !mode && def.groupData.el.siblings('.js-nav-loading').fadeIn(200);
            $.ajax({
                url: '/virtualtryon/manageGroup/center_group/',
                type: 'get',
                dataType: "json",
                data: {
                    page: def.groupData.page,
                    pageSize: def.groupData.pageSize
                },
                success: function (res) {
                    if (res.code === 0) {
                        if (!res.data) {res.data = {count: 0, list: []};}
                        def.groupData.total = res.data.count;
                        var list = res.data.list;
                        var str = '';
                        for (var i = 0; i < list.length; i++) {
                            str += '<li data-id="' + list[i].group_id + '" data-name="' + list[i].group_name + '"><img src="' + (list[i].covers && (general.def.img_path + list[i].covers) || def.groupData.defImgSrc) + '" alt=""><div class="edit">编辑</div><div class="title">' + list[i].group_name + '</div></li>';
                        }
                        if (type) {
                            def.groupData.el.html('<li class="add"><i></i><span>创建分组</span></li>' + str);
                        } else {
                            def.groupData.el.append(str);
                        }
                        // waterFall('.js-water-fall1');
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    def.groupData.isLoad = false;
                    mode && loadingToggle(false);
                    !mode && def.groupData.el.siblings('.js-nav-loading').fadeOut(200);
                }
            })
        }

        // 获取单个分组下模板图列表
        function groupidList(group_id, type) {
            if (type) {
                if (def.virData.page && def.virData.total && (def.virData.page * def.virData.pageSize >= def.virData.total)) {
                    return;
                }
                def.virData.page++;
                def.virData.el.siblings('.js-nav-loading').fadeIn(200);
            } else {
                if (def.detailData.page && def.detailData.total && (def.detailData.page * def.detailData.pageSize >= def.detailData.total)) {
                    return;
                }
                def.detailData.page++;
                def.detailData.el.siblings('.js-nav-loading').fadeIn(200);
            }
            // loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageGroup/groupid_list/',
                type: 'get',
                dataType: "json",
                data: {
                    group_id: group_id,
                    page: type ? def.virData.page : def.detailData.page,
                    pageSize: type ? def.virData.pageSize : def.detailData.pageSize
                },
                success: function (res) {
                    if (res.code === 0) {
                        if (type) {
                            def.virData.total = res.data.count;
                        } else {
                            def.detailData.total = res.data.count;
                        }
                        var list = res.data.list;
                        var str = '';
                        for (var i = 0; i < list.length; i++) {
                            str += '<li data-id="' + list[i].id + '" data-tid="' + list[i].iTemplateId + '"><img src="' + general.def.img_path + list[i].sTemplateRender + '" alt=""><div class="check-btn"></div></li>';
                        }
                        if (type) {
                            def.virData.el.append(str);
                            waterFall('.js-water-fall2');
                        } else {
                            def.groupData.group_id = group_id;
                            def.groupData.group_name = res.data.group_name;
                            $('.js-group-name').text(res.data.group_name);
                            def.detailData.el.append(str);
                            waterFall('.js-water-fall3');
                        }
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    def.virData.isLoad = false;
                    def.detailData.isLoad = false;
                    // loadingToggle(false);
                    def.virData.el.siblings('.js-nav-loading').fadeOut(200);
                    def.detailData.el.siblings('.js-nav-loading').fadeOut(200);
                }
            })
        }

        // 移动添加分组
        function moveAddImg(new_group_id, img_id_arr, callback) {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageDesignImg/move_add_img/ ',
                type: 'get',
                dataType: "json",
                data: {
                    new_group_id: new_group_id,
                    img_id_arr: img_id_arr,
                    old_group_id: def.detailData.group_id
                },
                success: function (res) {
                    if (res.code === 0) {
                        callback && callback();
                        tipToggle(res.msg, true);
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    loadingToggle(false);
                }
            })
        }

        // 移动分组
        function moveImg(new_group_id, img_id_arr, callback) {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageDesignImg/move_img/',
                type: 'get',
                dataType: "json",
                data: {
                    new_group_id: new_group_id,
                    img_id_arr: img_id_arr
                },
                success: function (res) {
                    if (res.code === 0) {
                        callback && callback();
                        tipToggle(res.msg, true);
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    loadingToggle(false);
                }
            })
        }

        // 移除分组
        function removeImg(group_id, img_id_arr, callback) {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageDesignImg/remove_img/',
                type: 'get',
                dataType: "json",
                data: {
                    group_id: group_id,
                    img_id_arr: img_id_arr
                },
                success: function (res) {
                    if (res.code === 0) {
                        callback && callback();
                        tipToggle(res.msg, true);
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    loadingToggle(false);
                }
            })
        }

        // 删除效果图
        function delImg(checkIds, callback) {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageDesignImg/del_Img/',
                type: 'get',
                dataType: "json",
                data: {
                    img_id_arr: checkIds
                },
                success: function (res) {
                    if (res.code === 0) {
                        callback && callback();
                        tipToggle(res.msg, true);
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    loadingToggle(false);
                }
            })
        }

        // 生成分享链接
        function saveMyShareSet(group_id, is_encrypt, expire_day, callback) {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/saveMyShareSet/',
                type: 'get',
                dataType: "json",
                data: {
                    group_id: group_id,
                    is_encrypt: is_encrypt,
                    expire_day: expire_day
                },
                success: function (res) {
                    if (res.code === 0) {
                        callback && callback(res.data);
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                    loadingToggle(false);
                },
                // complete: function () {
                //     loadingToggle(false);
                // }
            })
        }

    });
});