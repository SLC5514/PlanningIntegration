/*
 * @Author: SLC
 * @Date: 2020-04-26 13:04:11
 * @LastEditors: SLC
 * @LastEditTime: 2020-08-11 17:47:12
 * @Description: 2D实景模拟
 */

require.config({
    paths: {
        "virtualSnap": ["virtual/virtual-snap"],
        "perfectScrollbar": ["lib/perfect-scrollbar-1.2.min"],
        "snap": ["lib/snap.svg-min"],
        "html2canvas": ["lib/html2canvas.min"],
    },
    shim: {
        "virtualSnap": {
            deps: ["jquery", "snap", 'html2canvas'],
            exports: "virtualSnap"
        }
    }
});
var pattern_model = null;
require(["jquery", "general", "msg", "virtualSnap", "perfectScrollbar"], function ($, general, msg, virtualSnap, perfectScrollbar) {
    $(function () {
        // 盒子元素初始
        var asideNavTabEl = $('.js-aside-nav-list');
        var asideNavCtnEl = $('.js-aside-nav-content-box');
        // 拾色器初始项
        var colorPickerBox = $('.js-color-picker'),
            colorRibbonCvs = colorPickerBox.find('.color-ribbon')[0],
            colorRibbonCtx = null,
            colorSwatchCvs = colorPickerBox.find('.color-swatch')[0],
            colorSwatchCtx = null,
            swatchVal = colorPickerBox.find('.swatch-val input'),
            swatchStraw = colorPickerBox.find('.swatch-val i');
        if (colorRibbonCvs) {
            colorRibbonCvs.width = $(colorRibbonCvs).width();
            colorRibbonCvs.height = $(colorRibbonCvs).height();
            colorRibbonCtx = colorRibbonCvs.getContext("2d");
        }
        if (colorSwatchCvs) {
            colorSwatchCvs.width = $(colorSwatchCvs).width();
            colorSwatchCvs.height = $(colorSwatchCvs).height();
            colorSwatchCtx = colorSwatchCvs.getContext("2d");
        }

        // 全局初始项
        var def = {
            loadingEl: $('.js-loading-div'),
            loadingBgEl: $('.js-bg-div'),
            tipEl: $('.tip-box'),
            asideNavTabEl: asideNavTabEl, // 侧栏导航tab盒子
            asideNavCtnEl: asideNavCtnEl, // 侧栏导航内容盒子
            asideNavMasterEl: asideNavCtnEl.find('.master-room'), // 侧栏导航 我的制版间
            asideNavColMasterEl: asideNavCtnEl.find('.master-room .aside-nav-content-control'), // 侧栏导航 我的制版间 控制器
            asideNavCtnMasterEl: asideNavCtnEl.find('.master-room .aside-nav-content-list'), // 侧栏导航 我的制版间 内容
            asideNavMyPtnEl: asideNavCtnEl.find('.my-pattern'), // 侧栏导航 我的花型
            asideNavCtnMyPtnEl: asideNavCtnEl.find('.my-pattern .aside-nav-content-list'), // 侧栏导航 我的花型 内容
            asideNavCustomEl: asideNavCtnEl.find('.custom-made'), // 侧栏导航 定制
            asideNavCtnCustomEl: asideNavCtnEl.find('.custom-made .aside-nav-content-list'), // 侧栏导航 定制 内容
            asideNavPtnEl: asideNavCtnEl.find('.pattern'), // 侧栏导航 图案花型
            asideNavCtnPtnEl: asideNavCtnEl.find('.pattern .aside-nav-content-list'), // 侧栏导航 图案花型 内容
            asideNavAsyncEl: asideNavCtnEl.find('.async'), // 侧栏导航 品类
            asideNavCtnAsyncEl: asideNavCtnEl.find('.async .aside-nav-content-list'), // 侧栏导航 品类 内容

            imgHost: 'https://imgyt2.pop-fashion.com', // 图片前缀
            params: general.fn.getLocationParameter(), // 路由参数
            psBars: {}, // 存放滚动条控制器
            selTplType: '', // 用户类型
            selTplIds: [], // 选择模板id
            user_permission: { // 用户使用权限
                is_out: false,
                remaining_times: 0,
                arr: []
            },
            is_first_get: true,
            backPreview: false,

            /* ======================== 侧栏导航 ======================== */

            selTplEl: null, // 所选模板元素（li）
            // 侧栏数据(基本字段)
            asideData: {
                master: {
                    isFirst: true,
                    page: 1,
                    pageSize: 30,
                    total: 0,
                    isLoad: false,
                    group: '', // 分组id
                    checkId: '' // 移动选择的效果图id
                },
                myPattern: {
                    isFirst: true,
                    page: 1,
                    pageSize: 30,
                    total: 0,
                    isLoad: false,
                    checkIds: []
                },
                custom: {
                    isFirst: true,
                    page: 1,
                    pageSize: 30,
                    total: 0,
                    isLoad: false
                },
                pattern: {
                    isFirst: true,
                    page: 1,
                    pageSize: 30,
                    total: 0,
                    isLoad: false
                },
                // 有权限
                clothing: {
                    page: 1,
                    pageSize: 30,
                    total: 0,
                    isLoad: false,
                    classify: ''
                },
                bags: {
                    page: 1,
                    pageSize: 30,
                    total: 0,
                    isLoad: false,
                    classify: ''
                },
                shoes: {
                    page: 1,
                    pageSize: 30,
                    total: 0,
                    isLoad: false,
                    classify: ''
                },
                textile: {
                    page: 1,
                    pageSize: 30,
                    total: 0,
                    isLoad: false,
                    classify: ''
                },
                other: {
                    page: 1,
                    pageSize: 30,
                    total: 0,
                    isLoad: false,
                    classify: ''
                }
            },
            // 对照(1:服装, 2:箱包, 3:鞋子, 5:家纺, 100:其他)
            contrast: {
                1: 'clothing',
                2: 'bags',
                3: 'shoes',
                5: 'textile',
                100: 'other'
            },
            patternViewImg: '', // 当前选中图案

            /* ======================== 侧栏控制器 ======================== */
            sliderScaleBtn: $('.js-slider-scale-btn'),
            sliderAngleBtn: $('.js-slider-angle-btn'),
            sliderPanlBtn: $('.js-slider-panl-btn'),
            sliderPanvBtn: $('.js-slider-panv-btn'),
            // 拾色器初始项
            colorPickerBox: colorPickerBox,
            colorRibbonCvs: colorRibbonCvs,
            colorRibbonCtx: colorRibbonCtx,
            colorSwatchCvs: colorSwatchCvs,
            colorSwatchCtx: colorSwatchCtx,
            swatchVal: swatchVal, // 回显输入框（rgb，16进制）
            swatchStraw: swatchStraw, // 吸管
            colorSwatchX: 0, // colorSwatchCvs.width - 1, // 默认取色点x位置
            colorSwatchY: 0, // 默认取色点y位置
            isDragStart: false, // 是否开始拖拽

            /* ======================== 模拟效果部分 ======================== */
            svgSnap: $('#paper'),
            paperSnap: new virtualSnap('#paper'),
            // 数据存放
            groupList: [],
            viewPtLink: '' // 查看图案url
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
            if (type) {
                def.tipEl.addClass('on');
            } else {
                def.tipEl.removeClass('on');
            }
            def.tipEl.text(msg).fadeIn(200);
            var timeout = null;
            clearTimeout(timeout);
            timeout = setTimeout(function () {
                def.tipEl.fadeOut(200);
                clearTimeout(timeout);
                timeout = null;
            }, 2000);
        }

        /* ======================== 起始 ======================== */
        if (window.getComputedStyle(document.body).mixBlendMode === undefined) {
            $('.err-box').fadeIn(200);
        }

        // 垂直居中
        def.paperSnap.vcenter();

        if (def.params.templateId) {
            // 后台预览
            def.backPreview = true;
            $('.js-header').hide().siblings('.content').children('.virtual-box').css('top', 0);
            getVirtualDetail(def.params.templateId);
        } else {
            // 是否选择模板站点
            templateSelectShow();
        }

        // 选择模板站点
        $('.js-select-category-box').on('click', '.close', function () {
            window.location.href = '/home/';
        }).on('click', '.confirm', function () {
            if (!def.selTplIds.length) {
                tipToggle('请选择品类');
                return;
            }
            $('.js-select-category-box').fadeOut(200);
            saveTplSite();
        }).on('click', 'li', function () {
            var self = $(this);
            var id = self.data('id');
            var idx = def.selTplIds.indexOf(id);
            if (idx !== -1) {
                self.removeClass('on');
                def.selTplIds.splice(idx, 1);
            } else {
                self.addClass('on');
                def.selTplIds.push(id);
            }
        })

        // 选择关注品类弹框显示/隐藏
        function toggleSelectCategory(type) {
            if (type === true) {
                $('.js-bg-div,.js-select-category-box').fadeIn(200);
            } else if (type === false) {
                $('.js-bg-div,.js-select-category-box').fadeOut(200);
            } else {
                $('.js-bg-div,.js-select-category-box').fadeToggle(200);
            }
        }

        // 对话框回调
        $.fn.dialogBack = function (title, confirmBack, cancelBack, type) {
            def.loadingBgEl.fadeIn(400);
            $('.js-dialog-box .title').text(title);
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
        }

        // 上传图片
        // $('.js-upload').on('click', function () {
        //     if (def.asideData.myPattern.total >= 100) {
        //         $('.js-upload-limit-box').fadeIn(200);
        //         def.loadingBgEl.fadeIn(400);
        //         return false;
        //     }
        // })
        $('.js-upload-ipt').change(function () {
            var files = this.files;
            var file;
            if (files && files.length) {
                file = files[0];
            } else if (!files) {
                try {
                    // this.select();
                    // this.blur();
                    // var path = document.selection.createRange().text;
                    var fso = new ActiveXObject("Scripting.FileSystemObject");   
                    file = fso.GetFile(this.value);
                } catch (e) {
                    alert(e+"\n"+"如果错误为：Error:Automation 服务器不能创建对象；"+"\n"+"请按以下方法配置浏览器："+"\n"+"请打开【Internet选项-安全-Internet-自定义级别-ActiveX控件和插件-对未标记为可安全执行脚本的ActiveX控件初始化并执行脚本（不安全）-点击启用-确定】");
                }
            } else {
                $('.js-upload-ipt').val('');
            }
            if (file) {
                if (file.size / 1024 / 1024 > 5) {
                    tipToggle('图片大小不能超过5M');
                    $('.js-upload-ipt').val('');
                    return;
                }
                if (/^image\/\w+$/.test(file.type)) {
                    uploadFlowers();
                } else {
                    $('.js-upload-ipt').val('');
                }
            }
        });

        // 上传图片上限
        $('.js-upload-limit-box').on('click', '.close,.confirm', function () {
            $('.js-upload-limit-box').fadeOut(200);
            def.loadingBgEl.fadeOut(400);
        })

        // 添加分组上限
        $('.js-group-limit-box').on('click', '.close,.cancel,.confirm', function () {
            $('.js-group-limit-box').fadeOut(200);
        })

        // 功能引导说明
        $('.js-function-tips').on('click', '.close', function() {
            $('.js-function-tips').fadeOut(200);
            $('.js-function-tips-btn').addClass('on');
        })
        $('.js-function-tips-btn').on('click', function() {
            $('.js-function-tips').fadeIn(200);
            $('.js-function-tips-btn').removeClass('on');
        })

        /* ======================== 侧栏导航 ======================== */

        // 滚动条初始化
        initScrollbarH($(".js-aside-nav-Sb"), true, true);
        initScrollbarH($(".js-aside-control-Sb"), true);
        initScrollbarH($('.js-scroll-Sb'), true);

        // 侧栏导航列表切换
        def.asideNavTabEl.on('click', 'li', function (e, type) {
            var self = $(this);
            var navListBox = def.asideNavCtnEl.children().eq(self.index());
            self.addClass('on').siblings().removeClass('on');
            navListBox.addClass('on').siblings().removeClass('on');
            // 滚动条初始化
            var sbEl = navListBox.find('.js-aside-nav-Sb');
            initScrollbarH(sbEl, false, true);
            if (self.hasClass('master-room') && def.asideData.master.isFirst) {
                def.asideData.master.isFirst = false;
                // 制版间列表下拉展示所有分组
                listGroup(true);
            }
            if (self.hasClass('my-pattern') && def.asideData.myPattern.isFirst) {
                def.asideData.myPattern.isFirst = false;
                // 我的花型列表接口
                myFlowers(true);
            }
            if (self.hasClass('custom-made') && def.asideData.custom.isFirst) {
                def.asideData.custom.isFirst = false;
                // 获取定制模板列表接口
                getUserCustomTpl(true);
            }
            if (self.hasClass('pattern')) {
                $('.js-pattern-tab').children().eq(0).trigger('click', true);
                if (def.asideData.pattern.isFirst) {
                    def.asideData.pattern.isFirst = false;
                    // 图案花型列表接口
                    if (!type) {
                        if (def.params.pattern) {
                            // 默认选择图案
                            if (def.params.id && def.params.t) {
                                def.viewPtLink = '/patternlibrary/detail/?id=' + def.params.id + '&t=' + def.params.t + '&origin_type=1&virtual=true';
                            }
                            def.paperSnap.drawComBg(def.params.id, def.params.pattern);
                            def.asideNavCtnMyPtnEl.find('li').removeClass('on');
                            def.asideNavCtnPtnEl.children().eq(0).addClass('on').siblings().removeClass('on');
                            $('.js-loop-list').find('li').eq(0).addClass('on').siblings().removeClass('on');
                            if (def.asideNavTabEl.children('.pattern').hasClass('on')) {
                                $('.js-virtual-viewpt').removeClass('nodrop');
                            }
                            // 当前选中图案
                            def.patternViewImg = def.params.pattern;
                            $('.js-pattern-view').data({id: def.params.id, t: def.params.t, src: def.patternViewImg});
                            $('.js-pattern-view').children('img').attr('src', def.patternViewImg);
                            virtualsplpicmatch2(true);
                        } else {
                            virtualsplpicmatch1(true);
                        }
                    }
                }
            }
            // 查看图案详情状态
            if (self.hasClass('pattern') && def.viewPtLink) {
                $('.js-virtual-viewpt').removeClass('nodrop');
            } else {
                $('.js-virtual-viewpt').addClass('nodrop');
            }
        })

        // 我的制版间下拉框切换
        def.asideNavCtnEl.on('mouseenter', '.aside-nav-content-control>.select', function () {
            $(this).find('ul').removeClass('hide');
        }).on('mouseleave', '.aside-nav-content-control>.select', function () {
            $(this).find('ul').addClass('hide');
        });
        def.asideNavCtnEl.on('click', '.aside-nav-content-control>.select>ul>li', function () {
            def.asideData.master.group = $(this).data('id');
            $(this).addClass('on').siblings().removeClass('on');
            $(this).parent().addClass('hide').prev('span').text($(this).text()).attr('data-id', $(this).data('id'));
            // 我的制版间列表接口
            myDesignList(false, true);
        });

        // 图案花型tab切换
        $('.js-pattern-tab').on('click', 'li', function(type) {
            var self = $(this);
            var idx = self.index();
            var navListBox = self.parent().next('.js-pattern-tab-content').children().eq(idx);
            self.addClass('on').siblings().removeClass('on');
            navListBox.addClass('on').siblings().removeClass('on');
            // 滚动条初始化
            var sbEl = navListBox.find('.js-aside-nav-Sb');
            initScrollbarH(sbEl, false, true);
            if (idx && def.asideData.myPattern.isFirst) {
                def.asideData.myPattern.isFirst = false;
                // 我的花型列表接口
                myFlowers(true);
            }
        })

        // 选中花型点击
        $('.js-pattern-view').on('click', function() {
            var self = $(this);
            var id = self.data('id');
            var t = self.data('t');
            var src = self.data('src');
            if (!src) { return; }
            def.viewPtLink = '';
            if (def.selTplType === 'VIP') {
                if (t) {
                    def.viewPtLink = '/patternlibrary/detail/?id=' + id + '&t=' + t + '&origin_type=1&virtual=true';
                }
                def.paperSnap.drawComBg(id, src);
                def.asideNavCtnPtnEl.find('li').removeClass('on');
                $(this).addClass('on').siblings().removeClass('on');
                $('.js-loop-list').find('li').eq(0).addClass('on').siblings().removeClass('on');
                // 当前选中图案
                // def.patternViewImg = src;
                // $('.js-pattern-view').data({id: id, t: t, src: def.patternViewImg});
                // $('.js-pattern-view').children('img').attr('src', def.patternViewImg);
            } else {
                sendSrc(src, function () {
                    if (t) {
                        def.viewPtLink = '/patternlibrary/detail/?id=' + id + '&t=' + t + '&origin_type=1&virtual=true';
                    }
                    def.paperSnap.drawComBg(id, src);
                    def.asideNavCtnPtnEl.find('li').removeClass('on');
                    $(this).addClass('on').siblings().removeClass('on');
                    $('.js-loop-list').find('li').eq(0).addClass('on').siblings().removeClass('on');
                    // 当前选中图案
                    // def.patternViewImg = src;
                    // $('.js-pattern-view').data({id: id, t: '', src: def.patternViewImg});
                    // $('.js-pattern-view').children('img').attr('src', def.patternViewImg);
                });
            }
            // def.params.pattern = src;
            // virtualsplpicmatch2(true);
        })

        // 推荐花型重新加载
        $('.js-pattern-reload').on('click', function() {
            def.asideNavCtnPtnEl.html('<div class="nav-loading on"><i></i><span>加载中</span></div>');
            def.asideNavCtnPtnEl.next('.empty-list').hide();
            // 图案花型列表接口
            if (def.params.pattern) {
                virtualsplpicmatch2(true);
            } else {
                virtualsplpicmatch1(true);
            }
        })

        // 我的花型批量管理
        def.asideNavMyPtnEl.on('click', '.aside-nav-content-control .js-batch', function (e) {
            var e = e || window.event;
            var target = $(e.target);
            var self = $(this);
            var list = self.parent().siblings('.checkbtn-list');
            if (target.hasClass('col') || target.parent().hasClass('col')) {
                if (!list.find('li').length) {
                    return;
                }
                self.addClass('on');
                list.addClass('checkbtn-list-on');
            } else if (target.hasClass('cancel')) {
                self.removeClass('on');
                list.removeClass('checkbtn-list-on').find('.check-btn').removeClass('on');
                def.asideData.myPattern.checkIds = [];
            } else if (target.hasClass('confirm')) {
                if (!def.asideData.myPattern.checkIds.length) {
                    tipToggle('请先勾选内容');
                    return;
                }
                $('.js-dialog-box').fadeIn(200).dialogBack('确认要删除数据吗？', function () {
                    delMyFlowers(function () {
                        self.removeClass('on');
                        list.removeClass('checkbtn-list-on').find('.check-btn.on').parent().remove();
                    });
                }, function () {
                    self.removeClass('on');
                    list.removeClass('checkbtn-list-on').find('.check-btn').removeClass('on');
                    def.asideData.myPattern.checkIds = [];
                });
            }
        });

        // 我的花型列表点击
        def.asideNavCtnMyPtnEl.on('click', 'li', function () {
            var self = $(this);
            var list = self.parent();
            var btn = self.find('.check-btn');
            var id = self.data('id');
            var src = self.data('src');
            if (list.hasClass('checkbtn-list-on')) {
                if (btn.hasClass('on')) {
                    btn.removeClass('on');
                    def.asideData.myPattern.checkIds.splice(def.asideData.myPattern.checkIds.indexOf(id), 1);
                } else {
                    btn.addClass('on');
                    def.asideData.myPattern.checkIds.push(id);
                }
            } else {
                def.viewPtLink = '';
                if (def.selTplType === 'VIP') {
                    def.paperSnap.drawComBg(id, src);
                    def.asideNavCtnPtnEl.find('li').removeClass('on');
                    $(this).addClass('on').siblings().removeClass('on');
                    $('.js-loop-list').find('li').eq(0).addClass('on').siblings().removeClass('on');
                    // 当前选中图案
                    def.patternViewImg = src;
                    $('.js-pattern-view').data({id: id, t: '', src: def.patternViewImg});
                    $('.js-pattern-view').children('img').attr('src', def.patternViewImg);
                } else {
                    sendSrc(src, function () {
                        def.paperSnap.drawComBg(id, src);
                        def.asideNavCtnPtnEl.find('li').removeClass('on');
                        $(this).addClass('on').siblings().removeClass('on');
                        $('.js-loop-list').find('li').eq(0).addClass('on').siblings().removeClass('on');
                        // 当前选中图案
                        def.patternViewImg = src;
                        $('.js-pattern-view').data({id: id, t: '', src: def.patternViewImg});
                        $('.js-pattern-view').children('img').attr('src', def.patternViewImg);
                    });
                }
                def.params.pattern = src;
                virtualsplpicmatch2(true);
            }
        });

        // 图案花型列表点击
        def.asideNavCtnPtnEl.on('click', 'li', function () {
            var self = $(this);
            var id = self.data('id');
            var t = self.data('t');
            var src = self.data('src');
            if (def.selTplType === 'VIP') {
                def.viewPtLink = '/patternlibrary/detail/?id=' + id + '&t=' + t + '&origin_type=1&virtual=true';
                def.paperSnap.drawComBg(id, src);
                def.asideNavCtnMyPtnEl.find('li').removeClass('on');
                $(this).addClass('on').siblings().removeClass('on');
                $('.js-loop-list').find('li').eq(0).addClass('on').siblings().removeClass('on');
                if (def.asideNavTabEl.children('.pattern').hasClass('on')) {
                    $('.js-virtual-viewpt').removeClass('nodrop');
                }
                // 当前选中图案
                def.patternViewImg = src;
                $('.js-pattern-view').data({id: id, t: t, src: def.patternViewImg});
                $('.js-pattern-view').children('img').attr('src', def.patternViewImg);
            } else {
                sendSrc(src, function () {
                    def.viewPtLink = '/patternlibrary/detail/?id=' + id + '&t=' + t + '&origin_type=1&virtual=true';
                    def.paperSnap.drawComBg(id, src);
                    def.asideNavCtnMyPtnEl.find('li').removeClass('on');
                    $(this).addClass('on').siblings().removeClass('on');
                    $('.js-loop-list').find('li').eq(0).addClass('on').siblings().removeClass('on');
                    if (def.asideNavTabEl.children('.pattern').hasClass('on')) {
                        $('.js-virtual-viewpt').removeClass('nodrop');
                    }
                    // 当前选中图案
                    def.patternViewImg = src;
                    $('.js-pattern-view').data({id: id, t: t, src: def.patternViewImg});
                    $('.js-pattern-view').children('img').attr('src', def.patternViewImg);
                });
            }
            def.params.pattern = src;
            virtualsplpicmatch2(true);
        });

        // 展开/收起更多
        def.asideNavCtnEl.on('click', '.select-tpl .js-classify-more', function () {
            $(this).parent().toggleClass('on');
            var listEl = $(this).parents('li');
            var listCtn = listEl.find('ul');
            var h = listEl.height() - listCtn.position().top - (listCtn.siblings('.js-aside-nav-Sb-next').height() || 0);
            listCtn.height(h).data('h', h);
            def.psBars[listCtn.data('sbkey')].update();
        });

        // 切换标签
        def.asideNavCtnEl.on('click', '.select-tpl .aside-nav-content-control>.btn', function () {
            if (!$(this).hasClass('js-classify-more')) {
                $(this).addClass('on').siblings().removeClass('on');
                var key = $(this).parents('li').data('id');
                def.asideData[def.contrast[key]].classify = $(this).data('id');
                getTplListByClassifyId(true);
            }
        });

        // 切换模板
        def.asideNavCtnEl.on('click', '.select-tpl .aside-nav-content-list>li', function (e) {
            if (def.asideNavTabEl.find('.on').hasClass('master-room')) {
                var e = e || window.e;
                var target = $(e.target);
                if (target.hasClass('move-btn')) {
                    // 我的制版间列表移动分组
                    def.asideData.master.checkId = target.parent().data('id');
                    $('.js-movegroup-box').fadeIn(200);
                    def.loadingBgEl.fadeIn(400);
                    $('#moveGroupIpt>span').text('选择分组').removeClass('on').data('id', '');
                } else {
                    // 不同栏目取消选中样式
                    def.selTplEl && def.selTplEl.removeClass('on');
                    def.selTplEl = $(this);
                    $(this).addClass('on').siblings().removeClass('on');
                    // 我的制版间列表点击
                    getVirtualDetail($(this).data('tplid'), $(this).data('id'));
                }
            } else {
                // 不同栏目取消选中样式
                def.selTplEl && def.selTplEl.removeClass('on');
                def.selTplEl = $(this);
                $(this).addClass('on').siblings().removeClass('on');
                getVirtualDetail($(this).data('id'));
            }
        });

        /**
         * @description: 滚动条初始化
         * @param {Object} el JQ DOM 对象/ queryname
         * @param {Boolean} type 返回类型 true:dom false:height
         * @param {Boolean} status 是否设置高 true:设置 false:不设置
         * @return: Number / JQ DOM
         */
        function initScrollbarH(el, type, status) {
            var el = el;
            var h = 0;
            if (typeof el === 'string') {
                el = $(el);
            }
            if (!el.length) {
                if (type) {
                    return el;
                } else {
                    return h;
                }
            }
            el.each(function (i, v) {
                var item = $(v);
                h = item.parent().height() - item.position().top - (item.siblings('.js-aside-nav-Sb-next').height() || 0);
                status && item.height(h).data('h', h);
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

        /* ======================== 分页 ======================== */

        // 我的制版间
        def.asideNavCtnMasterEl.on('ps-y-reach-end', function () {
            if (!def.asideData.master.isLoad && Math.ceil(def.asideData.master.total / def.asideData.master.pageSize) > def.asideData.master.page) {
                def.asideData.master.page++;
                myDesignList();
            }
        })

        // 我的花型
        def.asideNavCtnMyPtnEl.on('ps-y-reach-end', function () {
            if (!def.asideData.myPattern.isLoad && Math.ceil(def.asideData.myPattern.total / def.asideData.myPattern.pageSize) > def.asideData.myPattern.page) {
                def.asideData.myPattern.page++;
                myFlowers();
            }
        })

        // 定制
        def.asideNavCtnCustomEl.on('ps-y-reach-end', function () {
            if (!def.asideData.custom.isLoad && Math.ceil(def.asideData.custom.total / def.asideData.custom.pageSize) > def.asideData.custom.page) {
                def.asideData.custom.page++;
                getUserCustomTpl();
            }
        })

        // 图案花型
        def.asideNavCtnPtnEl.on('ps-y-reach-end', function () {
            if (!def.asideData.pattern.isLoad && !def.params.pattern && Math.ceil(def.asideData.pattern.total / def.asideData.pattern.pageSize) > def.asideData.pattern.page) {
                def.asideData.pattern.page++;
                virtualsplpicmatch1();
            }
        })

        // 品类
        def.asideNavCtnAsyncEl.on('ps-y-reach-end', function () {
            var id = def.asideNavTabEl.find('.on').data('id');
            if (!def.asideData[def.contrast[id]].isLoad && Math.ceil(def.asideData[def.contrast[id]].total / def.asideData[def.contrast[id]].pageSize) > def.asideData[def.contrast[id]].page) {
                def.asideData[def.contrast[id]].page++;
                getTplListByClassifyId();
            }
        })

        /* ======================== 侧栏控制器 ======================== */

        // 收起/展开
        $('.js-aside-control-box').on('click', '.aside-control-collapse', function () {
            $(this).parent().toggleClass('on');
            $('.js-virtual-content-box').toggleClass('on');
        })

        // 选择部件
        $('.js-com-list').on('click', 'li', function () {
            def.paperSnap.selectCom($(this).data().idx);
        })

        // 选择图案循环
        $('.js-loop-list').on('click', 'li', function () {
            // $(this).addClass('on').siblings().removeClass('on');
            def.paperSnap.selLoopFunc($(this).data('type'), true);
        })

        // 大小
        $('.js-slider-scale').on('click', '.sub', function () {
            def.paperSnap.scaleChangeCol('-', 0, true);
        }).on('click', '.add', function () {
            def.paperSnap.scaleChangeCol('+', 0, true);
        })
        // 角度
        $('.js-slider-angle').on('click', '.sub', function () {
            def.paperSnap.angleChangeCol('-', 0, true);
        }).on('click', '.add', function () {
            def.paperSnap.angleChangeCol('+', 0, true);
        })
        // 水平平移
        $('.js-slider-panl').on('click', '.sub', function () {
            def.paperSnap.panlChangeCol('-', 0, true);
        }).on('click', '.add', function () {
            def.paperSnap.panlChangeCol('+', 0, true);
        })
        // 垂直平移
        $('.js-slider-panv').on('click', '.sub', function () {
            def.paperSnap.panvChangeCol('-', 0, true);
        }).on('click', '.add', function () {
            def.paperSnap.panvChangeCol('+', 0, true);
        })
        var sliderE;
        $(document).on('mousedown', '.js-slider-list button', function (e) {
            sliderE = e || window.event;
            this._isDown = true;
            this._x = sliderE.clientX;
            if ($(this).hasClass('js-slider-scale-btn')) {
                this._l = parseFloat(def.sliderScaleBtn.css('left'));
                this._maxW = parseFloat(def.sliderScaleBtn.parent().width());
            }
            if ($(this).hasClass('js-slider-angle-btn')) {
                this._l = parseFloat(def.sliderAngleBtn.css('left'));
                this._maxW = parseFloat(def.sliderAngleBtn.parent().width());
            }
            if ($(this).hasClass('js-slider-panl-btn')) {
                this._l = parseFloat(def.sliderPanlBtn.css('left'));
                this._maxW = parseFloat(def.sliderPanlBtn.parent().width());
            }
            if ($(this).hasClass('js-slider-panv-btn')) {
                this._l = parseFloat(def.sliderPanvBtn.css('left'));
                this._maxW = parseFloat(def.sliderPanvBtn.parent().width());
            }
        }).on('mousemove', function (e) {
            // 大小
            if (def.sliderScaleBtn[0] && def.sliderScaleBtn[0]._isDown) {
                sliderE = e || window.event;
                var m = sliderE.clientX - def.sliderScaleBtn[0]._x;
                var l = def.sliderScaleBtn[0]._l;
                var maxW = def.sliderScaleBtn[0]._maxW;
                var left = l + m;
                if (left > maxW) {
                    left = maxW;
                }
                if (left < 0) {
                    left = 0;
                }
                def.paperSnap.scaleChangeCol('*', left / maxW, true);
            }
            // 角度
            if (def.sliderAngleBtn[0] && def.sliderAngleBtn[0]._isDown) {
                sliderE = e || window.event;
                var m = sliderE.clientX - def.sliderAngleBtn[0]._x;
                var l = def.sliderAngleBtn[0]._l;
                var maxW = def.sliderAngleBtn[0]._maxW;
                var left = l + m;
                if (left > maxW) {
                    left = maxW;
                }
                if (left < 0) {
                    left = 0;
                }
                def.paperSnap.angleChangeCol('*', left / maxW, true);
            }
            // 水平平移
            if (def.sliderPanlBtn[0] && def.sliderPanlBtn[0]._isDown) {
                sliderE = e || window.event;
                var m = sliderE.clientX - def.sliderPanlBtn[0]._x;
                var l = def.sliderPanlBtn[0]._l;
                var maxW = def.sliderPanlBtn[0]._maxW;
                var left = l + m;
                if (left > maxW) {
                    left = maxW;
                }
                if (left < 0) {
                    left = 0;
                }
                def.paperSnap.panlChangeCol('*', left / maxW, true);
            }
            // 垂直平移
            if (def.sliderPanvBtn[0] && def.sliderPanvBtn[0]._isDown) {
                sliderE = e || window.event;
                var m = sliderE.clientX - def.sliderPanvBtn[0]._x;
                var l = def.sliderPanvBtn[0]._l;
                var maxW = def.sliderPanvBtn[0]._maxW;
                var left = l + m;
                if (left > maxW) {
                    left = maxW;
                }
                if (left < 0) {
                    left = 0;
                }
                def.paperSnap.panvChangeCol('*', left / maxW, true);
            }
        }).on('mouseup', function () {
            if (def.sliderScaleBtn[0] && def.sliderScaleBtn[0]._isDown) {
                def.sliderScaleBtn[0]._isDown = false;
            }
            if (def.sliderAngleBtn[0] && def.sliderAngleBtn[0]._isDown) {
                def.sliderAngleBtn[0]._isDown = false;
            }
            if (def.sliderPanlBtn[0] && def.sliderPanlBtn[0]._isDown) {
                def.sliderPanlBtn[0]._isDown = false;
            }
            if (def.sliderPanvBtn[0] && def.sliderPanvBtn[0]._isDown) {
                def.sliderPanvBtn[0]._isDown = false;
            }
        })

        // 渲染部件
        function renderCom(svg_file) {
            var str = '';
            for (var i = 0; i < svg_file.length; i++) {
                str += '<li data-idx="' + i + '">' + svg_file[i].sPartName + '</li>';
            }
            $('.js-com-list').html(str);
            def.psBars[$('.js-com-list').data('sbkey')].update();
        }

        // 拾色器 ========================

        // 绘制色带
        drawRibbon(def.colorRibbonCvs, def.colorRibbonCtx, def.colorRibbonCvs && def.colorRibbonCvs.width, def.colorRibbonCvs && def.colorRibbonCvs.height);

        // 绘制色板
        drawSwatch(def.colorSwatchCtx, def.colorRibbonCtx && def.colorRibbonCtx.getImageData(0, 0, 1, 1).data, def.colorSwatchCvs && def.colorSwatchCvs.width, def.colorSwatchCvs && def.colorSwatchCvs.height);

        // 选择色板色值
        def.colorSwatchCvs && def.colorSwatchCvs.addEventListener("click", function (e) {
            var e = e || window.event;
            var sp = $(this).siblings('span')[0];
            var spnw = parseInt(getComputedStyle(sp, false)["width"]);
            var x = e.offsetX < 0 ? 0 : e.offsetX;
            var y = e.offsetY < 0 ? 0 : e.offsetY;
            if (x >= this.width) {
                x = this.width - 1;
            }
            sp.style.top = e.offsetY - spnw / 2 + "px";
            sp.style.left = e.offsetX - spnw / 2 + "px";
            def.colorSwatchX = x;
            def.colorSwatchY = y;
            setSwatchVal(def.swatchVal[0], def.swatchVal[1], def.colorSwatchCtx, def.colorSwatchX, def.colorSwatchY);
            e.preventDefault;
            e.stopPropagation();
        }, false);

        // 取色
        def.swatchStraw.on('click', function () {
            var straw = def.svgSnap.attr('class');
            if (straw === 'straw') {
                def.svgSnap.attr('class', '');
            } else {
                def.svgSnap.attr('class', 'straw');
            }
        });

        /**
         * @description: 绘制色带
         * @param {Object} ctx 色带上下文对象
         * @param {Number} tw 色带宽度
         * @param {Number} th 色带高度
         * @return: 
         */
        function drawRibbon(cvs, ctx, tw, th) {
            if (!cvs || !ctx) { return; }
            var sp = $(cvs).siblings('span')[0];
            var spnw = parseInt(getComputedStyle(sp, false)["width"]);
            var tw = tw;
            while (--tw > -1) {
                ctx.beginPath();
                ctx.strokeStyle = "hsl(" + tw + ",100%,50%)";
                ctx.moveTo(tw + 1, 0);
                ctx.lineTo(tw + 1, th);
                ctx.stroke();
                ctx.closePath();
            }
            // 选择色带色区
            cvs.addEventListener("click", function (e) {
                var x = e.offsetX < 0 ? 0 : e.offsetX;
                var y = e.offsetY < 0 ? 0 : e.offsetY;
                var ndata = ctx.getImageData(x, y, 1, 1);
                drawSwatch(def.colorSwatchCtx, ndata.data, def.colorSwatchCvs.width, def.colorSwatchCvs.height);
                sp.style.left = x - Math.floor(spnw / 2) + "px";
                e.preventDefault;
                e.stopPropagation();
            }, false);
            // 色带拖拽
            cvs.addEventListener("mousedown", function (e) {
                def.isDragStart = true;
                e.preventDefault;
                e.stopPropagation();
            }, false);
            cvs.addEventListener("mousemove", function (e) {
                if (def.isDragStart == true) {
                    var x = e.offsetX < 0 ? 0 : e.offsetX;
                    var y = e.offsetY < 0 ? 0 : e.offsetY;
                    var ndata = ctx.getImageData(x, y, 1, 1);
                    drawSwatch(def.colorSwatchCtx, ndata.data, def.colorSwatchCvs.width, def.colorSwatchCvs.height);
                    sp.style.left = x - Math.floor(spnw / 2) + "px";
                }
                e.preventDefault;
                e.stopPropagation();
            }, false);
            cvs.addEventListener("mouseup", function (e) {
                if (def.isDragStart == true) {
                    def.isDragStart = false;
                }
                e.preventDefault;
                e.stopPropagation();
            }, false);
            document.body.addEventListener("mouseup", function (e) {
                if (def.isDragStart == true) {
                    def.isDragStart = false;
                }
            }, false);
        }

        /**
         * @description: 绘制色板
         * @param {Object} ctx 色板上下文对象
         * @param {Array} data 颜色数据
         * @param {Number} ow 色板宽度
         * @param {Number} oh 色板高度
         * @return: 
         */
        function drawSwatch(ctx, data, ow, oh) {
            if (!ctx) { return; }
            var ow = ow,
                oh = oh,
                r = data[0],
                g = data[1],
                b = data[2],
                a = data[3],
                img_data = ctx.getImageData(0, 0, ow, oh),
                px_data = img_data.data,
                k = 0,
                v = 0;
            for (var i = 0; i < oh; i++) {
                k = (oh - i) / oh;
                v = 255 * k;
                for (var j = 0; j < ow * 4; j += 4) {
                    px_data[i * ow * 4 + j] = v - (v - r * k) * j / ow / 4 | 0;
                    px_data[i * ow * 4 + j + 1] = v - (v - g * k) * j / ow / 4 | 0;
                    px_data[i * ow * 4 + j + 2] = v - (v - b * k) * j / ow / 4 | 0;
                    px_data[i * ow * 4 + j + 3] = 255;
                }
            }
            ctx.putImageData(img_data, 0, 0);
            setSwatchVal(def.swatchVal[0], def.swatchVal[1], def.colorSwatchCtx, def.colorSwatchX, def.colorSwatchY);
        };

        /**
         * @description: 设置色板值
         * @param {Object} rgbIpt rgb回显输入框
         * @param {Object} ffIpt 16进制回显输入框
         * @param {Object} ctx 色板上下文对象
         * @param {Number} x 色板x轴坐标点
         * @param {Number} y 色板y轴坐标点
         * @return: 
         */
        function setSwatchVal(rgbIpt, ffIpt, ctx, x, y) {
            var nimg_data = ctx.getImageData(x, y, 1, 1);
            var r = nimg_data.data[0];
            var g = nimg_data.data[1];
            var b = nimg_data.data[2];
            var a = nimg_data.data[3];
            var ff = toFF(r) + toFF(g) + toFF(b);
            rgbIpt.value = r + "," + g + "," + b;
            ffIpt.value = ff;
            def.paperSnap.drawComColor('#' + ffIpt.value);
        };

        /**
         * @description: 转16进制
         * @param {Number} num 2进制数
         * @return: 16进制
         */
        function toFF(num) {
            return num > 16 ? num.toString(16).toUpperCase() : "0" + num.toString(16).toUpperCase();
        };

        /* ======================== 模拟效果部分 ======================== */

        // 上传花型
        $('.js-uploadimg').on('click', function() {
            $('.js-upload').trigger('click');
        })

        // 保存制版间
        $('.js-virtual-save').on('click', function () {
            if (!(def.paperSnap.def.tplData.ogWH && def.paperSnap.def.tplData.ogWH.w)) {
                tipToggle('保存失败');
                return;
            }
            var str = '';
            for (var i = 0; i < def.groupList.length; i++) {
                str += '<li data-id="' + def.groupList[i].id + '"><span>' + def.groupList[i].sGroupName + '</span><div class="btn">添加</div></li>';
            }
            $('.js-megroup-list').html(str);
            initScrollbarH($('.js-megroup-list'), false, false);
            if (str.length) {
                $('.js-addmegroup-box .js-megroup-list').show();
                $('.js-addmegroup-box .empty').hide();
                $('.js-addmegroup-box .confirm').hide();
            } else {
                $('.js-addmegroup-box .js-megroup-list').hide();
                $('.js-addmegroup-box .empty').show();
                $('.js-addmegroup-box .confirm').show();
            }
            $('.js-addmegroup-box').fadeIn(200);
            def.loadingBgEl.fadeIn(400);
            $('#addMeGroupIpt').val('');
        })

        // 添加至我的分组
        $('.js-addmegroup-box').on('click', '.close', function () {
            $('.js-addmegroup-box').fadeOut(200);
            def.loadingBgEl.fadeOut(400);
        }).on('click', '.btn', function () {
            $('.js-addmegroup-box').fadeOut(200);
            def.loadingEl.fadeIn(200);
            var groupId = $(this).parent().data('id');
            def.paperSnap.genImage(true, function (baseurl, ext) {
                saveDesignTpl(def.paperSnap.def.tplData.id, groupId, baseurl, def.paperSnap.def.tplData, ext);
            }, function () {
                loadingToggle(false);
                tipToggle('生成失败');
            });
        }).on('click', '.confirm', function () {
            def.loadingEl.fadeOut(400);
            addGroup($('#addMeGroupIpt').val(), true);
        })

        // 筛选我的分组
        $('#addMeGroupIpt').on('input', function () {
            var val = this.value;
            var str = '';
            for (var i = 0; i < def.groupList.length; i++) {
                if (def.groupList[i].sGroupName.includes(val)) {
                    str += '<li data-id="' + def.groupList[i].id + '"><span>' + def.groupList[i].sGroupName + '</span><div class="btn">添加</div></li>';
                }
            }
            $('.js-megroup-list').html(str);
            initScrollbarH($('.js-megroup-list'), false, false);
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

        // 移动至分组
        $('.js-movegroup-box').on('click', '.close', function () {
            $('.js-movegroup-box').fadeOut(200);
            def.loadingBgEl.fadeOut(400);
        }).on('click', '.confirm', function () {
            var id = $('#moveGroupIpt>span').data('id');
            if (id) {
                $('.js-movegroup-box').fadeOut(200);
                moveAddImg(id, [def.asideData.master.checkId], function () {
                    def.asideNavCtnMasterEl.find('li').each(function (i, v) {
                        var id = $(v).data('id');
                        if (id && (id === def.asideData.master.checkId)) {
                            $(v).remove();
                        }
                    })
                    initScrollbarH(def.asideNavColMasterEl.find('.select>ul'), false, false);
                    initScrollbarH($('.js-megroup-list'), false, false);
                    initScrollbarH($('.js-movegroup-list'), false, false);
                    def.asideData.master.checkId = '';
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

        // 下载模拟效果图
        $('.js-virtual-download').on('click', function () {
            if (!(def.paperSnap.def.tplData.ogWH && def.paperSnap.def.tplData.ogWH.w)) {
                tipToggle('下载失败');
                return;
            }
            def.paperSnap.downImage();
        })

        // 重置
        $('.js-virtual-reset').on('click', function () {
            if (!(def.paperSnap.def.tplData.ogWH && def.paperSnap.def.tplData.ogWH.w)) {
                tipToggle('重置失败');
                return;
            }
            $('.js-dialog-box').fadeIn(200).dialogBack('确定要重置吗？', function () {
                def.paperSnap.reset();
            }, null, true);
        })

        // 查看图案详情
        $('.js-virtual-viewpt').on('click', function () {
            if (def.asideNavTabEl.children('.pattern').hasClass('on') && def.viewPtLink) {
                $('.js-detail-frame').find('iframe').attr('src', def.viewPtLink);
                $('.js-detail-frame').fadeIn();
                $('body').addClass("over-hidden");
            }
        })

        /* ======================== 2D试衣-接口 ======================== */

        // 是否选择模板站点
        function templateSelectShow() {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/templateSelectShow/',
                type: 'get',
                dataType: "json",
                success: function (res) {
                    if (res.code === 0) {
                        def.selTplType = res.data.user_type;
                        if (res.data.display === true) {
                            def.loadingEl.fadeOut(200);
                            toggleSelectCategory(true);
                            return;
                        } else {
                            if (def.is_first_get === true) {
                                getFreeTimes();
                            }

                            // 获取左侧菜单栏
                            getLeftmenu(true);

                            // 制版间列表下拉展示所有分组
                            listGroup(true, true);

                            // 功能引导
                            var fnTips = localStorage.getItem('fn-tips') || '';
                            if (fnTips.indexOf(userId) === -1) {
                                fnTips += (fnTips ? '-' : '') + userId;
                                localStorage.setItem('fn-tips', fnTips);
                                $('.js-function-tips').fadeIn(200);
                            } else {
                                $('.js-function-tips-btn').addClass('on');
                            }
                        }
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

        // 保存弹窗模板站点设置
        function saveTplSite() {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/saveTplSite/',
                type: 'get',
                dataType: "json",
                data: {
                    tpl_site_arr: def.selTplIds
                },
                success: function (res) {
                    if (res.code === 0) {
                        window.location.reload();
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                }
            })
        }

        // 获取左侧菜单栏
        function getLeftmenu(isFirst) {
            !isFirst && loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/getLeftmenu/',
                type: 'get',
                dataType: "json",
                success: function (res) {
                    if (res.code === 0) {
                        for (var key in res.data) {
                            // 初始化导航项
                            $('.js-aside-nav-list .async').each(function (idx, v) {
                                if ($(v).data('id') == key) {
                                    $(v).addClass('async-show');
                                }
                            })
                            // 初始化导航内容
                            def.asideNavAsyncEl.each(function (idx, v) {
                                if ($(v).data('id') == key) {
                                    $(v).addClass('async-show');
                                    var classifyEl = $(v).find('.aside-nav-content-control'),
                                        listEl = $(v).find('.aside-nav-content-list'),
                                        emptyEl = $(v).find('.empty-list'),
                                        classifyStr = '',
                                        listStr = '',
                                        classifyTotal = 0,
                                        classifyCount = 0;
                                    for (var k in res.data[key].data.classify) {
                                        classifyTotal++;
                                    }
                                    // 渲染标签
                                    for (var k in res.data[key].data.classify) {
                                        classifyCount++;
                                        classifyStr += '<button class="btn" data-id="' + res.data[key].data.classify[k].id + '">' + res.data[key].data.classify[k].name + '</button>';
                                        if (classifyCount === 8 && classifyTotal > 9) {
                                            classifyStr += '<button class="btn unfold js-classify-more">展开更多</button>';
                                        }
                                    }
                                    if (classifyTotal) {
                                        classifyStr = '<button class="btn" data-id="">全部</button>' + classifyStr;
                                    }
                                    if (classifyTotal > 9) {
                                        classifyStr += '<button class="btn collapse js-classify-more">收起</button>';
                                    }
                                    // 渲染列表
                                    for (var k in res.data[key].data.list.list) {
                                        listStr += '<li data-id="' + res.data[key].data.list.list[k].id + '"><img src="' + def.imgHost + res.data[key].data.list.list[k].sCover + '" alt="' + res.data[key].data.list.list[k].sTemplateName + '"><button class="simu-btn">模拟成品</button></li>';
                                    }
                                    if (Math.ceil(res.data[key].data.list.count / res.data[key].data.list.pageSize) > res.data[key].data.list.page || res.data[key].data.list.list.length === 0) {
                                        listStr += '<div class="nav-loading on"><i></i><span>加载中</span></div>';
                                    } else {
                                        listStr += '<div class="nav-loading"><i></i><span>加载中</span></div>';
                                    }
                                    listEl.find('.nav-loading').remove();
                                    classifyEl.html(classifyStr).children().eq(0).addClass('on');
                                    listEl.html(listStr);
                                    if (res.data[key].data.list.list.length === 0 || !(Math.ceil(res.data[key].data.list.count / res.data[key].data.list.pageSize) > res.data[key].data.list.page)) {
                                        listEl.find('.nav-loading').addClass('hide');
                                    } else {
                                        listEl.find('.nav-loading').removeClass('on');
                                    }
                                    // 初始化
                                    def.asideData[def.contrast[key]].classify = classifyEl.children().eq(0).data('id') || ''; // 初始化标签选中
                                    // if (res.data[key].data.list.count <= res.data[key].data.list.pageSize) { // 初始化是否加载
                                    //     def.asideData[def.contrast[key]].isLoad = true;
                                    // }
                                    if (!listEl.find('li').length) { // 初始化是否为空
                                        emptyEl.show();
                                    } else {
                                        emptyEl.hide();
                                    }
                                }
                            })
                        }
                        var asideItem, tplItem;
                        if (def.params.did) {
                            // 选择默认导航
                            def.asideNavTabEl.find('.master-room').trigger('click');
                        } else if (def.params.pattern) {
                            // 选择默认导航
                            def.asideNavTabEl.children('.pattern').trigger('click');
                            // 选择默认模板
                            tplItem = def.asideNavCtnEl.find('.async-show .aside-nav-content-list>li');
                            tplItem.eq(0).trigger('click');
                            // 选择默认导航
                            $('.js-pattern-tab').children().eq(0).trigger('click');
                            loadingToggle(false);
                        } else {
                            // 选择默认导航
                            asideItem = def.asideNavTabEl.find('.async-show');
                            asideItem.eq(0).trigger('click');
                            // 选择默认模板
                            tplItem = def.asideNavCtnEl.find('.async-show .aside-nav-content-list>li');
                            tplItem.eq(0).trigger('click');
                            // 无模板
                            if (!(asideItem.length && tplItem.length)) {
                                loadingToggle(false);
                            }
                        }
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    !isFirst && loadingToggle(false);
                }
            })
        }

        // 获取站点分类下模板列表
        function getTplListByClassifyId(type) {
            var siteid = def.asideNavTabEl.find('.on').data('id');
            if (type) {
                def.asideData[def.contrast[siteid]].page = 1;
                def.asideNavAsyncEl.filter('[data-id=' + siteid + ']').find('.aside-nav-content-list').find('.nav-loading').addClass('on').removeClass('hide');
                def.asideNavAsyncEl.filter('[data-id=' + siteid + ']').find('.aside-nav-content-list').next('.empty-list').hide();
            }
            // loadingToggle(true);
            def.asideData[def.contrast[siteid]].isLoad = true;
            $.ajax({
                url: '/virtualtryon/getTplListByClassifyId/',
                type: 'get',
                dataType: "json",
                data: {
                    site: siteid,
                    classify_id: def.asideData[def.contrast[siteid]].classify,
                    page: def.asideData[def.contrast[siteid]].page,
                    pageSize: def.asideData[def.contrast[siteid]].pageSize
                },
                success: function (res) {
                    if (res.code === 0) {
                        var box = def.asideNavAsyncEl.filter('[data-id=' + siteid + ']');
                        var ctn = box.find('.aside-nav-content-list');
                        var emptyEl = ctn.next('.empty-list');
                        def.asideData[def.contrast[siteid]].total = res.data.count;
                        var list = res.data.list;
                        var str = '';
                        for (var i = 0; i < list.length; i++) {
                            str += '<li data-id="' + list[i].id + '"><img src="' + def.imgHost + list[i].sCover + '" alt="' + list[i].sTemplateName + '"><button class="simu-btn">模拟成品</button></li>';
                        }
                        if (Math.ceil(def.asideData[def.contrast[siteid]].total / def.asideData[def.contrast[siteid]].pageSize) > def.asideData[def.contrast[siteid]].page || list.length === 0) {
                            str += '<div class="nav-loading on"><i></i><span>加载中</span></div>';
                        } else {
                            str += '<div class="nav-loading"><i></i><span>加载中</span></div>';
                        }
                        ctn.find('.nav-loading').remove();
                        if (type) {
                            ctn.html(str);
                        } else {
                            ctn.append(str);
                        }
                        if (list.length === 0 || !(Math.ceil(def.asideData[def.contrast[siteid]].total / def.asideData[def.contrast[siteid]].pageSize) > def.asideData[def.contrast[siteid]].page)) {
                            ctn.find('.nav-loading').addClass('hide');
                        } else {
                            ctn.find('.nav-loading').removeClass('on');
                        }
                        if (!ctn.find('li').length) {
                            emptyEl.show();
                        } else {
                            emptyEl.hide();
                        }
                        // 更新滚动条
                        var st = ctn[0].scrollTop;
                        ctn[0].scrollTop = st - 1;
                        def.psBars[ctn.data('sbkey')].update();
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    // loadingToggle(false);
                    def.asideData[def.contrast[siteid]].isLoad = false;
                }
            })
        }

        // 获取模板详情接口
        function getVirtualDetail(id, designId) {
            loadingToggle(true);
            var data = { templateId: id };
            var url = '/virtualtryon/getVirtualDetail/';
            if (designId) {
                data.designId = designId;
            }
            if (def.params.templateId) {
                url = window.location.href;
            }
            $.ajax({
                url: url,
                type: 'get',
                dataType: "json",
                data: data,
                success: function (res) {
                    if (res.code === 0) {
                        var data = res.data;
                        if (res.data.render_info) {
                            for (var i = 0; i < res.data.render_info.svg_file.length; i++) {
                                for (var j = 0; j < res.data.svg_file.length; j++) {
                                    if (res.data.render_info.svg_file[i].id === res.data.svg_file[j].id) {
                                        res.data.render_info.svg_file[i].sPartPathArr = res.data.svg_file[j].sPartPathArr;
                                    }
                                }
                            }
                            data = res.data.render_info;
                        }
                        !def.backPreview && renderCom(data.svg_file);
                        if (!designId && data.svg_file.length) {
                            def.paperSnap.init(data, function() {
                                def.paperSnap.drawComBg(def.params.id, def.params.pattern);
                            });
                            return;
                        }
                        def.paperSnap.init(data);
                    } else if (res.code === 100) {
                        msg.msg({ "txt": '模板已被删除' }, 2000);
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

        // 我的制版间列表接口
        function myDesignList(isFirst, type) {
            if (!def.asideData.master.group) {
                def.asideNavCtnMasterEl.find('.nav-loading').addClass('hide');
                def.asideNavCtnMasterEl.next('.empty-list').show();
                return;
            }
            //\ !isFirst && loadingToggle(true);
            def.asideData.master.isLoad = true;
            if (type) {
                def.asideData.master.page = 1;
                def.asideNavCtnMasterEl.find('.nav-loading').addClass('on').removeClass('hide');
                def.asideNavCtnMasterEl.next('.empty-list').hide();
            }
            // if (def.asideData.master.group === 'all') {
            //     def.asideNavCtnMasterEl.addClass('nomove');
            // } else {
            //     def.asideNavCtnMasterEl.removeClass('nomove');
            // }
            $.ajax({
                url: '/virtualtryon/myDesignList/',
                type: 'get',
                dataType: "json",
                data: {
                    group_id: def.asideData.master.group,
                    page: def.asideData.master.page,
                    pageSize: def.asideData.master.pageSize
                },
                success: function (res) {
                    if (res.code === 0) {
                        def.asideData.master.total = res.data.count;
                        var list = res.data.list;
                        var str = '';
                        for (var i = 0; i < list.length; i++) {
                            str += '<li data-id="' + list[i].id + '" data-tplid="' + list[i].iTemplateId + '"><img src="' + def.imgHost + list[i].sTemplateRender + '" alt=""><button class="move-btn">移动</button></li>';
                        }
                        if (Math.ceil(def.asideData.master.total / def.asideData.master.pageSize) > def.asideData.master.page || list.length === 0) {
                            if (list.length === 0) {
                                str += '<div class="nav-loading on"><i></i><span>加载中</span></div>';
                            } else {
                                str += '<div class="nav-loading"><i></i><span>加载中</span></div>';
                            }
                        }
                        def.asideNavCtnMasterEl.find('.nav-loading').remove();
                        if (type) {
                            def.asideNavCtnMasterEl.html(str);
                        } else {
                            def.asideNavCtnMasterEl.append(str);
                        }
                        if (list.length === 0) {
                            def.asideNavCtnMasterEl.find('.nav-loading').addClass('hide');
                        } else {
                            def.asideNavCtnMasterEl.find('.nav-loading').removeClass('on');
                        }
                        if (isFirst && def.params.did) {
                            // 选择默认模板
                            tplItem = def.asideNavCtnMasterEl.children('li');
                            var idx = '';
                            for (var i = 0; i < tplItem.length; i++) {
                                if ($(tplItem[i]).data('id') == def.params.did) {
                                    idx = i;
                                }
                            }
                            if (idx !== '') {
                                tplItem.eq(idx).trigger('click');
                            } else {
                                getVirtualDetail(def.params.tid, def.params.did);
                            }
                        }
                        if (!def.asideNavCtnMasterEl.find('li').length) {
                            def.asideNavCtnMasterEl.next('.empty-list').show();
                        } else {
                            def.asideNavCtnMasterEl.next('.empty-list').hide();
                        }
                        // 更新滚动条
                        var st = def.asideNavCtnMasterEl[0].scrollTop;
                        def.asideNavCtnMasterEl[0].scrollTop = st - 1;
                        !isFirst && def.psBars[def.asideNavCtnMasterEl.data('sbkey')].update();
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    //\ !isFirst && loadingToggle(false);
                    def.asideData.master.isLoad = false;
                }
            })
        }

        // 保存到制版间接口
        function saveDesignTpl(tplId, groupId, url, info, ext) {
            loadingToggle(true);
            var renderInfo = JSON.parse(JSON.stringify(info));
            for (var i = 0; i < renderInfo.svg_file.length; i++) {
                if (renderInfo.svg_file[i].sPartPath) {
                    renderInfo.svg_file[i].sPartPath = '';
                }
                if (renderInfo.svg_file[i].sPartPathArr) {
                    renderInfo.svg_file[i].sPartPathArr = [];
                }
            }
            $.ajax({
                url: '/virtualtryon/saveDesignTpl/',
                type: 'post',
                dataType: "json",
                data: {
                    templateId: parseInt(tplId),
                    groupId: groupId,
                    renderUrl: url.replace(/data:image\/\w+;base64,/, ''),
                    renderInfo: JSON.stringify(renderInfo),
                    ext: ext
                },
                success: function (res) {
                    if (res.code === 0) {
                        if (def.asideData.master.group == groupId || def.asideData.master.group === 'all') {
                            var str = '<li data-id="' + res.data.design_id + '" data-tplid="' + tplId + '"><img src="' + def.imgHost + res.data.design_url + '" alt=""><button class="move-btn">移动</button></li>';
                            def.asideNavCtnMasterEl.prepend(str);
                            if (!def.asideNavCtnMasterEl.find('li').length) {
                                def.asideNavCtnMasterEl.next('.empty-list').show();
                            } else {
                                def.asideNavCtnMasterEl.next('.empty-list').hide();
                            }
                            // 更新滚动条
                            var st = def.asideNavCtnMasterEl[0].scrollTop;
                            def.asideNavCtnMasterEl[0].scrollTop = st - 1;
                            def.psBars[def.asideNavCtnMasterEl.data('sbkey')].update();
                        }
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

        // 我的花型列表接口
        function myFlowers(isFirst) {
            //\ !isFirst && loadingToggle(true);
            def.asideData.myPattern.isLoad = true;
            $.ajax({
                url: '/virtualtryon/myFlowers/',
                type: 'get',
                dataType: "json",
                data: {
                    page: def.asideData.myPattern.page,
                    pageSize: def.asideData.myPattern.pageSize
                },
                success: function (res) {
                    if (res.code === 0) {
                        def.asideData.myPattern.total = res.data.count;
                        var list = res.data.list;
                        var str = '';
                        for (var i = 0; i < list.length; i++) {
                            var prefix = getTimeSize(list[i].dCreateTime, 3) && '/fashion' || '';
                            str += '<li data-id="' + list[i].id + '" data-src="' + def.imgHost + prefix + list[i].sUploadUrl + '"><img src="' + def.imgHost + prefix + list[i].sUploadUrl + '" alt=""><button class="check-btn"></button></li>';
                        }
                        if (Math.ceil(def.asideData.myPattern.total / def.asideData.myPattern.pageSize) > def.asideData.myPattern.page) {
                            str += '<div class="nav-loading"><i></i><span>加载中</span></div>';
                        }
                        def.asideNavCtnMyPtnEl.find('.nav-loading').remove();
                        def.asideNavCtnMyPtnEl.append(str);
                        if (!list.length) {
                            $('.js-batch').hide();
                            def.asideNavCtnMyPtnEl.next('.empty-list').show();
                        } else {
                            $('.js-batch').show();
                            def.asideNavCtnMyPtnEl.next('.empty-list').hide();
                        }
                        // 更新滚动条
                        if (isFirst) {
                            initScrollbarH(def.asideNavCtnMyPtnEl, false, true);
                        } else {
                            var st = def.asideNavCtnMyPtnEl[0].scrollTop;
                            def.asideNavCtnMyPtnEl[0].scrollTop = st - 1;
                            def.psBars[def.asideNavCtnMyPtnEl.data('sbkey')].update();
                        }
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    //\ !isFirst && loadingToggle(false);
                    def.asideData.myPattern.isLoad = false;
                }
            })
        }

        // 批量管理花型列表
        function delMyFlowers(callback) {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/delMyFlowers/',
                type: 'get',
                dataType: "json",
                data: {
                    id_arr: def.asideData.myPattern.checkIds
                },
                success: function (res) {
                    if (res.code === 0) {
                        def.asideData.myPattern.checkIds = [];
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

        // 上传本地花型接口
        function uploadFlowers() {
            loadingToggle(true);
            var formData = new FormData($('.js-upload-form')[0]);
            $.ajax({
                url: '/virtualtryon/uploadFlowers/',
                type: 'post',
                dataType: "json",
                processData: false,
                contentType: false,
                data: formData,
                success: function (res) {
                    if (res.code === 0) {
                        if (def.selTplType !== 'VIP') {
                            if (def.user_permission.is_out === true) {
                                top.location.href = '/system/snapshot/?type=1';
                                return false;
                            }
                        }
                        if (!def.asideData.myPattern.isFirst) {
                            $('.js-batch').show();
                            def.asideNavCtnMyPtnEl.next('.empty-list').hide();
                            var str = '<li data-id="' + res.data.id + '" data-src="' + def.imgHost + res.data.imgPath + '"><img src="' + def.imgHost + res.data.imgPath + '" alt=""><button class="check-btn"></button></li>';
                            def.asideNavCtnMyPtnEl.prepend(str);
                            // 更新滚动条
                            var st = def.asideNavCtnMyPtnEl[0].scrollTop;
                            def.asideNavCtnMyPtnEl[0].scrollTop = st - 1;
                            def.psBars[def.asideNavCtnMyPtnEl.data('sbkey')].update();
                        }
                        // def.asideNavTabEl.children('.my-pattern').trigger('click');
                        def.asideNavTabEl.children('.pattern').trigger('click', true);
                        tipToggle(res.msg, true);
                        loadingToggle(false);
                        def.params.pattern = def.imgHost + res.data.imgPath;
                        virtualsplpicmatch2(true, {
                            id: res.data.id,
                            src: def.imgHost + res.data.imgPath
                        });
                    } else if (res.code === 1001) {
                        $('.js-upload-limit-box').fadeIn(200);
                        def.loadingEl.fadeOut(200);
                    } else {
                        loadingToggle(false);
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err);
                    loadingToggle(false);
                },
                complete: function () {
                    $('.js-upload-ipt').val('');
                }
            })
        }

        // 获取定制模板列表接口
        function getUserCustomTpl(isFirst) {
            //\ !isFirst && loadingToggle(true);
            def.asideData.custom.isLoad = true;
            $.ajax({
                url: '/virtualtryon/getUserCustomTpl/',
                type: 'get',
                dataType: "json",
                data: {
                    page: def.asideData.custom.page,
                    pageSize: def.asideData.custom.pageSize,
                },
                success: function (res) {
                    if (res.code === 0) {
                        def.asideData.custom.total = res.data.count;
                        var list = res.data.list;
                        var str = '';
                        for (var i = 0; i < list.length; i++) {
                            str += '<li data-id="' + list[i].id + '"><img src="' + def.imgHost + list[i].sCover + '" alt="' + list[i].sTemplateName + '"><button class="simu-btn">模拟成品</button></li>';
                        }
                        if (Math.ceil(def.asideData.custom.total / def.asideData.custom.pageSize) > def.asideData.custom.page) {
                            str += '<div class="nav-loading"><i></i><span>加载中</span></div>';
                        }
                        def.asideNavCtnCustomEl.find('.nav-loading').remove();
                        def.asideNavCtnCustomEl.append(str);
                        if (!def.asideNavCtnCustomEl.find('li').length) {
                            def.asideNavCtnCustomEl.next('.empty-list').show();
                        } else {
                            def.asideNavCtnCustomEl.next('.empty-list').hide();
                        }
                        // 更新滚动条
                        var st = def.asideNavCtnCustomEl[0].scrollTop;
                        def.asideNavCtnCustomEl[0].scrollTop = st - 1;
                        !isFirst && def.psBars[def.asideNavCtnCustomEl.data('sbkey')].update();
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    //\ !isFirst && loadingToggle(false);
                    def.asideData.custom.isLoad = false;
                }
            })
        }

        // 图案花型列表接口
        function virtualsplpicmatch1(isFirst) {
            //\ !isFirst && loadingToggle(true);
            def.asideData.pattern.isLoad = true;
            $.ajax({
                url: '/patternlibrary/getList/',
                type: 'get',
                dataType: "json",
                data: {
                    params: 'page_' + def.asideData.pattern.page,
                    pageSize: def.asideData.pattern.pageSize,
                    column: 1
                },
                success: function (res) {
                    if (res.code === 0) {
                        def.asideData.pattern.total = res.info.total;
                        var list = res.data.list;
                        var str = '';
                        for (var i = 0; i < list.length; i++) {
                            str += '<li data-id="' + list[i].id + '" data-t="' + list[i].t + '" data-src="' + list[i].mbigPath + '"><img src="' + list[i].cover + '" alt=""></li>';
                        }
                        if (Math.ceil(def.asideData.pattern.total / def.asideData.pattern.pageSize) > def.asideData.pattern.page) {
                            str += '<div class="nav-loading"><i></i><span>加载中</span></div>';
                        }
                        def.asideNavCtnPtnEl.find('.nav-loading').remove();
                        def.asideNavCtnPtnEl.append(str);
                        if (!def.asideNavCtnPtnEl.find('li').length) {
                            def.asideNavCtnPtnEl.next('.empty-list').show();
                        } else {
                            def.asideNavCtnPtnEl.next('.empty-list').hide();
                        }
                        // 更新滚动条
                        var st = def.asideNavCtnPtnEl[0].scrollTop;
                        def.asideNavCtnPtnEl[0].scrollTop = st - 1;
                        !isFirst && def.psBars[def.asideNavCtnPtnEl.data('sbkey')].update();
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    //\ !isFirst && loadingToggle(false);
                    def.asideData.pattern.isLoad = false;
                }
            })
        }
        function virtualsplpicmatch2(isFirst, upImg) {
            //\ !isFirst && loadingToggle(true);
            def.asideData.pattern.isLoad = true;
            def.asideNavCtnPtnEl.find('.nav-loading').addClass('on').removeClass('hide');
            $.ajax({
                url: '/virtualtryon/virtualsplpicmatch/',
                type: 'get',
                dataType: "json",
                data: {
                    path: encodeURIComponent(def.params.pattern)
                },
                success: function (res) {
                    if (res.code === 0) {
                        var list = res.data || [];
                        var str = '';
                        // if (upImg) {
                        //     str += '<li data-id="' + upImg.id + '" data-t="" data-src="' + upImg.src + '"><img src="' + upImg.src + '" alt=""></li>';
                        // }
                        for (var i = 0; i < list.length; i++) {
                            str += '<li data-id="' + list[i].id + '" data-t="' + list[i].t + '" data-src="' + list[i].mbig + '"><img src="' + list[i].small + '" alt=""></li>';
                        }
                        str += '<div class="nav-loading hide"><i></i><span>加载中</span></div>';
                        def.asideNavCtnPtnEl.find('.nav-loading').remove();
                        def.asideNavCtnPtnEl.html(str);
                        if (upImg) {
                            // 默认选择图案
                            def.viewPtLink = '';
                            def.paperSnap.drawComBg(upImg.id, upImg.src);
                            // def.asideNavCtnMyPtnEl.find('li').removeClass('on');
                            // def.asideNavCtnPtnEl.children().eq(0).addClass('on').siblings().removeClass('on');
                            // $('.js-loop-list').find('li').eq(0).addClass('on').siblings().removeClass('on');
                            $('.js-virtual-viewpt').addClass('nodrop');
                            // 当前选中图案
                            def.patternViewImg = upImg.src;
                            $('.js-pattern-view').data({id: upImg.id, t: '', src: def.patternViewImg});
                            $('.js-pattern-view').children('img').attr('src', def.patternViewImg);
                        }
                        if (!def.asideNavCtnPtnEl.find('li').length) {
                            def.asideNavCtnPtnEl.next('.empty-list').show();
                        } else {
                            def.asideNavCtnPtnEl.next('.empty-list').hide();
                        }
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    //\ !isFirst && loadingToggle(false);
                    def.asideData.pattern.isLoad = false;
                }
            })
        }

        // 发送图片url
        function sendSrc(src, callback) {
            var has_used = false;
            for (var i = 0, len = def.user_permission.arr.length; i < len; i++) {
                if (def.user_permission.arr[i] === src) {
                    has_used = true;
                    break;
                }
            }
            if (has_used) {
                callback && callback();
                return;
            }
            loadingToggle(true);
            $.ajax({
                url: "/virtualtryon/tryoutlog/",
                type: "POST",
                dataType: 'json',
                data: { path: src },
                success: function (res) {
                    if (res.code === 2001) {
                        def.user_permission.is_out = true;
                        top.location.href = '/system/snapshot/?type=1';
                        return;
                    }
                    def.user_permission.arr.push(src);
                    def.user_permission.remaining_times = res.data && res.data.free || '';
                    callback && callback();
                },
                error: function (err) {
                    console.log(err)
                },
                complete: function () {
                    loadingToggle(false);
                }
            });
        }
        function getFreeTimes() {
            def.is_first_get = false;
            if (def.selTplType === 'GENERAL') {
                if (def.params.pattern) {
                    $.ajax({
                        url: "/virtualtryon/tryoutlog/",
                        type: "POST",
                        dataType: 'json',
                        data: { path: def.params.pattern },
                        success: function (res) {
                            if (res.code === 2001) {
                                def.user_permission.is_out = true;
                                top.location.href = '/system/snapshot/?type=1';
                                return;
                            }
                            def.user_permission.arr.push(def.params.pattern);
                            def.user_permission.remaining_times = res.data && res.data.free || '';
                        },
                        error: function (err) {
                            console.log(err)
                        }
                    })
                }
            }
        }

        /* ======================== 个人中心接口 ======================== */

        // 添加分组
        function addGroup(group_name, type) {
            !type && loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageGroup/add_group/',
                type: 'get',
                dataType: "json",
                data: {
                    group_name: group_name
                },
                success: function (res) {
                    if (res.code === 0) {
                        listGroup(true);
                        tipToggle('创建成功', true);
                        !type && loadingToggle(false);
                    } else if (res.code === 1001) {
                        $('.js-group-limit-box').fadeIn(200);
                        def.loadingEl.fadeOut(200);
                    } else {
                        !type && loadingToggle(false);
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err);
                    !type && loadingToggle(false);
                }
            })
        }

        // 编辑分组
        function editGroup() {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageGroup/edit_group/',
                type: 'get',
                dataType: "json",
                data: {
                    group_id: '',
                    group_name: ''
                },
                success: function (res) {
                    if (res.code === 0) {
                        console.log(res.data)
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
        function delGroup() {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageGroup/del_group/',
                type: 'get',
                dataType: "json",
                data: {
                    group_id: ''
                },
                success: function (res) {
                    if (res.code === 0) {
                        console.log(res.data)
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

        // 制版间列表下拉展示所有分组
        function listGroup(isFirst, nolist) {
            !isFirst && loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageGroup/list_group/',
                type: 'get',
                dataType: "json",
                success: function (res) {
                    if (res.code === 0) {
                        def.groupList = res.data.group_list;
                        var list = res.data.group_list;
                        var str1 = '', str2 = '', str3 = '';
                        var gidx = -1;
                        for (var i = 0; i < list.length; i++) {
                            if (def.params.gid && list[i].id == def.params.gid) {
                                gidx = i;
                            }
                            if (i === 0) {
                                str1 += '<li' + ((def.params.gid ? 'all' == def.params.gid : i === 0) ? ' class="on"' : '') + ' data-id="all">全部</li>';
                                str1 += '<li' + ((def.params.gid ? list[i].id == def.params.gid : false) ? ' class="on"' : '') + ' data-id="' + list[i].id + '">' + list[i].sGroupName + '</li>';
                            } else {
                                str1 += '<li' + ((def.params.gid ? list[i].id == def.params.gid : i === 0) ? ' class="on"' : '') + ' data-id="' + list[i].id + '">' + list[i].sGroupName + '</li>';
                            }
                            str2 += '<li data-id="' + list[i].id + '"><span>' + list[i].sGroupName + '</span><div class="btn">添加</div></li>';
                            str3 += '<li data-id="' + list[i].id + '">' + list[i].sGroupName + '</li>';
                        }
                        // 默认分组
                        if (list.length) {
                            var listEl1 = def.asideNavColMasterEl.find('.select>ul');
                            if (gidx === -1) {
                                def.asideData.master.group = 'all';
                                def.asideNavColMasterEl.find('.select>span').text('全部').attr('data-id', def.asideData.master.group);
                            } else {
                                def.asideData.master.group = list[gidx].id;
                                def.asideNavColMasterEl.find('.select>span').text(list[gidx].sGroupName).attr('data-id', def.asideData.master.group);
                            }
                            listEl1.html(str1);
                            initScrollbarH(listEl1, false, false);
                            // 添加至分组
                            var listEl2 = $('.js-megroup-list');
                            listEl2.html(str2);
                            initScrollbarH(listEl2, false, false);
                            $('.js-addmegroup-box .js-megroup-list').show();
                            $('.js-addmegroup-box .empty').hide();
                            $('.js-addmegroup-box .confirm').hide();
                            // 移动至分组
                            var listEl3 = $('.js-movegroup-list');
                            listEl3.html(str3);
                            initScrollbarH(listEl3, false, false);
                        } else {
                            $('.js-addmegroup-box .js-megroup-list').hide();
                            $('.js-addmegroup-box .empty').show();
                            $('.js-addmegroup-box .confirm').show();
                        }
                        $('#addMeGroupIpt').val('');
                        // 我的制版间列表接口
                        !nolist && myDesignList(isFirst, true);
                    } else {
                        msg.msg({ "txt": res.msg }, 2000);
                    }
                },
                error: function (err) {
                    console.log('for bug: ' + err)
                },
                complete: function () {
                    !isFirst && loadingToggle(false);
                }
            })
        }

        // 分组列表（取第一张封面图）
        function centerGroup() {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageGroup/center_group/',
                type: 'get',
                dataType: "json",
                data: {
                    page: 1,
                    pageSize: 30
                },
                success: function (res) {
                    if (res.code === 0) {
                        console.log(res.data)
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

        // 获取单个分组下模板图列表
        function groupidList() {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageGroup/groupid_list/',
                type: 'get',
                dataType: "json",
                data: {
                    group_id: '',
                    page: 1,
                    pageSize: 30
                },
                success: function (res) {
                    if (res.code === 0) {
                        console.log(res.data)
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

        /* ======================== 删除，移除，移动效果图 ======================== */

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
                    old_group_id: def.asideData.master.group
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
        function removeImg() {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageDesignImg/remove_img/',
                type: 'get',
                dataType: "json",
                data: {
                    group_id: '',
                    img_id_arr: []
                },
                success: function (res) {
                    if (res.code === 0) {
                        console.log(res.data)
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
        function delImg() {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageDesignImg/del_Img/',
                type: 'get',
                dataType: "json",
                data: {
                    img_id_arr: []
                },
                success: function (res) {
                    if (res.code === 0) {
                        console.log(res.data)
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

        /* ======================== 分享 ======================== */

        // 分享页-PC/h5列表
        function shareList() {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageDesignImg/shareList/',
                type: 'get',
                dataType: "json",
                data: {
                    pwd: '',
                    page: 1,
                    pageSize: 30
                },
                success: function (res) {
                    if (res.code === 0) {
                        console.log(res.data)
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
        function setShare() {
            loadingToggle(true);
            $.ajax({
                url: '/virtualtryon/manageDesignImg/saveMyShareSet/',
                type: 'get',
                dataType: "json",
                data: {
                    group_id: '',
                    is_encrypt: '',
                    expire_day: ''
                },
                success: function (res) {
                    if (res.code === 0) {
                        console.log(res.data)
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

        /* ======================== 方法 ======================== */

        // 判断时间大小
        function getTimeSize(time, days) {
            var o = new Date(time).getTime();
            var n = new Date().getTime();
            if (n - o > days * 24 * 3600 * 1000) {
                return true;
            }
            return false;
        }
    })

});