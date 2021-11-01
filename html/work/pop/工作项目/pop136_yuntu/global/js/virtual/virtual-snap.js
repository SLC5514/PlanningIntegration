/*
 * @Author: SLC
 * @Date: 2020-04-26 13:04:11
 * @LastEditors: SLC
 * @LastEditTime: 2020-08-03 13:20:20
 * @Description: 2D实景模拟关联插件
 */

(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD. Register as an anonymous module.
        define(['jquery', 'snap', 'html2canvas'], factory);
    } else if (typeof exports === 'object') {
        // Node/CommonJS
        factory(require('jquery', 'snap', 'html2canvas'));
    } else {
        // Browser globals
        factory(jQuery, Snap, html2canvas);
    }
}(function ($, $Snap, html2canvas) {
    // The default settings for the plugin
    var defloop = 1;
    var defaultSettings = {
        smallScreen: $(window).height() < 750, // 大小屏
        // smallScreen: $(window).width() < 1500, // 大小屏
        imgHost: 'https://imgyt2.pop-fashion.com', // 图片前缀
        broswer: {}, // 浏览器信息

        /* ================== 盒子 ================== */
        svgPaper: null, // snap对象
        // 通用宽高
        w: 250, // 宽度（770，450，430，250）
        h: 450, // 高度（770，450，450）
        // 不同类型宽高
        smallRectWH: { w: 250, h: 450 }, // 默认取
        bigRectWH: { w: 430, h: 770 },
        smallSquareWH: { w: 450, h: 450 },
        bigSquareWH: { w: 770, h: 770 },
        aspectRatio: true, // 宽高比类型（false：正方形，true：长方形）

        /* ================== 轮询定时器 ================== */
        loopTime: 100, // 定时器时间
        imgLoaded: false, // 数据获取状态

        /* ================== 输出模板数据 ================== */
        resetTplData: {},
        tplData: {
            // // 接口数据
            // id: "", // 模板id
            // iSiteId: "",
            // iClassifyId: "",
            // sTemplateName: "", // 模板名称
            // sCover: "", // 小图
            // sBigTemplateImg: "", // 大图
            // svg_file: [ // 部件
            //     {
            //         iTemplateId: "", // 部件id
            //         sPartName: "", // 部件名称
            //         sPartPathArr: [], // 部件路径
            //         // 新增字段
            //         pattern: { // 图案
            //             id: '', // 图案id
            //             src: '' // 图案链接
            //         },
            //         config: { // 配置项
            //             ...defCfg
            //         }
            //     }
            // ],
            // // 新增字段
            // ogWH: { w: 0, h: 0 }, // 原始宽高
            // tplScale: 1, // 回显/原始缩放
        },

        /* ================== 部件默认配置项 ================== */
        defloop: defloop,
        defCfg: {
            loop: defloop, // 图案循环（0：无，1：全循环，2：水平，3：垂直）
            scale: 1, // 大小（图案缩放倍数）
            angle: 0, // 角度（图案旋转角度）
            panl: 0, // 水平平移（px，缩放前平移距离）
            panv: 0, // 垂直平移（px，缩放前平移距离）
            fill: 'transparent' // 底色填充
        },

        /* ================== 模板 ================== */
        tplEl: null, // image对象
        tplLoaded: false, // 模板绘制状态

        /* ================== 部件 ================== */
        comBoxEl: $('.js-com-list'), // 列表盒子
        comEls: [], // 路径
        comUseBgEls: [], // use路径
        comLoaded: false, // 部件绘制状态
        comIdx: 0, // 部件当前部件下标

        /* ================== 图案 ================== */
        errInfoEl: $('.js-aside-control-box .err-info'),
        patternBoxEl: $('.js-loop-list'), // 盒子
        patternEls: [], // 图案image对象
        comUseMaskEls: [], // use图案
        // patternList: [], // 图案列表
        testPtSrc: 'https://imgyt2.pop-fashion.com/fashion/graphic/20171027-sh4432-04/1/small/0-38416303339_sh4432.jpg',

        /* ================== 控制器滑动盒子 ================== */
        sliderScaleBtn: $('.js-slider-scale button'),
        sliderScaleTxt: $('.js-slider-scale .text'),
        sliderAngleBtn: $('.js-slider-angle button'),
        sliderAngleTxt: $('.js-slider-angle .text'),
        sliderPanlBtn: $('.js-slider-panl button'),
        sliderPanlTxt: $('.js-slider-panl .text'),
        sliderPanvBtn: $('.js-slider-panv button'),
        sliderPanvTxt: $('.js-slider-panv .text'),

        /* ================== 控制状态 ================== */
        scaleDelta: [], // 缩放大小（增量++）
        scaleMax: 4, // 缩放最大倍数
        scaleMin: 0.01, // 缩放最小量
        angleDelta: [], // 角度大小（增量++）
        angleMax: 360, // 旋转最大量
        angleMin: 1, // 旋转最小量
        panlDelta: [], // 水平平移大小（增量++）
        panlMax: 500, // 水平平移最大量
        panlMin: -500, // 水平平移最小量
        panvDelta: [], // 垂直平移大小（增量++）
        panvMax: 500, // 垂直平移最大量
        panvMin: -500, // 垂直平移最小量
        downStatus1: false, // 点击状态
        downStatus2: false, // 点击状态2
        hoverStatus: false, // 划过状态
        mouseStatus: false, // 拖动状态
        // 临时数据
        idx: -1,
        x: 0,
        y: 0,
        angle: 0,
        panl: 0,
        panv: 0,
        matrixArr: [],
    };

    // 添加forEach兼容
    if (!Array.prototype.forEach) {
        Array.prototype.forEach = function forEach(callback, thisArg) {
            var T, k;
            if (this == null) {
                throw new TypeError("this is null or not defined");
            }
            var O = Object(this);
            var len = O.length >>> 0;
            if (typeof callback !== "function") {
                throw new TypeError(callback + " is not a function");
            }
            if (arguments.length > 1) {
                T = thisArg;
            }
            k = 0;
            while (k < len) {
                var kValue;
                if (k in O) {
                    kValue = O[k];
                    callback.call(T, kValue, k, O);
                }
                k++;
            }
        };
    }

    var virtualSnap = function (querySelector, option) {
        // Use the default settings
        var def = $.extend(true, {}, defaultSettings),
            $paper = $(querySelector),
            self = this;

        if (typeof option === "object") {
            $.extend(true, def, option);
        }

        this.def = def;
        this.def.svgPaper = $Snap($paper[0]);
        this.def.broswer = this.getBroswer();

        // 取色
        this.def.svgPaper.click(function (e, x, y) {
            var straw = this.attr('class');
            if (straw !== 'straw') {
                return;
            }
            var offset = $(this.node).parent().offset();
            self.getColor(self.def.svgPaper.outerSVG(), self.def.svgPaper.getBBox().w, self.def.svgPaper.getBBox().h, x - offset.left, y - offset.top);
        })

        //屏蔽右键菜单
        /* this.def.svgPaper.node.oncontextmenu = function (event) {
            if (window.event) {
                event = window.event;
            }
            try {
                return false;
            } catch (e) {
                return false;
            }
        } */

        // 旋转拖动
        $(document).on('mousedown', function (e) {
            /* var e = e || window.event;
            var target = $(e.target);
            self.def.idx = -1; */
            // 旋转
            /* if (!self.def.downStatus2 && e.button === 0 && target.attr('class') === 'mask-use') {
                self.def.idx = $Snap(target[0]).data('idx');
                self.selectCom(self.def.idx, true);
                if (!self.def.patternEls[self.def.idx]) {
                    return;
                }
                self.def.mouseStatus = false;
                self.def.downStatus2 = true;
                self.def.comIdx = self.def.idx;
                self.def.x = e.clientX;
                self.def.y = e.clientY;
                // self.def.angle = self.def.tplData.svg_file[self.def.idx].config.angle;
                var matrixArr = self.def.patternEls[self.def.comIdx].attr('patternTransform').match(/matrix\((.*)\)/)[1].split(',');
                self.def.matrixArr = matrixArr;
            } */
            // 拖动
            /* if (!self.def.downStatus1 && e.button === 2 && target.attr('class') === 'mask-use') {
                self.def.idx = $Snap(target[0]).data('idx');
                self.selectCom(self.def.idx, true);
                if (!self.def.patternEls[self.def.idx]) {
                    return;
                }
                self.def.mouseStatus = false;
                self.def.downStatus1 = true;
                self.def.comIdx = self.def.idx;
                self.def.x = e.clientX;
                self.def.y = e.clientY;
                self.def.panl = self.def.tplData.svg_file[self.def.idx].config.panl;
                self.def.panv = self.def.tplData.svg_file[self.def.idx].config.panv;
                var matrixArr = self.def.patternEls[self.def.comIdx].attr('patternTransform').match(/matrix\((.*)\)/)[1].split(',');
                self.def.matrixArr = matrixArr;
            } */
        }).on('mousemove', function (e) {
            var e = e || window.event;
            var target = $(e.target);
            if ((!self.def.downStatus1 && !self.def.downStatus2) && target.attr('class') === 'mask-use') {
                self.def.idx = $Snap(target[0]).data('idx');
                self.def.hoverStatus = true;
            } else {
                self.def.idx = -1;
                self.def.hoverStatus = false;
            }
            // 旋转
            /* if (self.def.downStatus2 && self.def.comIdx != -1) {
                self.def.mouseStatus = true;
                if (!self.def.angleDelta[self.def.idx]) { self.def.angleDelta[self.def.idx] = 0; }
                var angle = self.getAngle(self.def.x, self.def.y, e.clientX, e.clientY, parseFloat(self.def.patternEls[self.def.idx].data('left')) + parseFloat(self.def.patternEls[self.def.idx].data('ogw')) * self.def.tplData.svg_file[self.def.idx].config.scale / 2, parseFloat(self.def.patternEls[self.def.idx].data('top')) + parseFloat(self.def.patternEls[self.def.idx].data('ogh')) * self.def.tplData.svg_file[self.def.idx].config.scale / 2);
                var deg, rotate;
                if (e.clientX - self.def.x > 0) {
                    deg = self.def.angle + angle;
                } else {
                    angle = 360 - angle;
                    deg = self.def.angle - angle;
                }
                rotate = deg < 0 ? deg + 360 : (deg > 360 ? deg - 360 : deg);
                self.def.angleDelta[self.def.idx] = rotate;
                self.angleChange(self.def.idx);
            } */
            // 拖动
            /* if (self.def.downStatus1 && self.def.comIdx != -1) {
                self.def.mouseStatus = true;
                if (!self.def.panlDelta[self.def.idx]) { self.def.panlDelta[self.def.idx] = 0; }
                self.def.panlDelta[self.def.idx] = (e.clientX - self.def.x) / self.def.tplData.tplScale + self.def.panl;
                if (self.def.panlDelta[self.def.idx] < self.def.panlMin) {
                    self.def.panlDelta[self.def.idx] = self.def.panlMin;
                } else if (self.def.panlDelta[self.def.idx] > self.def.panlMax) {
                    self.def.panlDelta[self.def.idx] = self.def.panlMax;
                }
                if (!self.def.panvDelta[self.def.idx]) { self.def.panvDelta[self.def.idx] = 0; }
                self.def.panvDelta[self.def.idx] = (e.clientY - self.def.y) / self.def.tplData.tplScale + self.def.panv;
                if (self.def.panvDelta[self.def.idx] < self.def.panlMin) {
                    self.def.panvDelta[self.def.idx] = self.def.panlMin;
                } else if (self.def.panvDelta[self.def.idx] > self.def.panlMax) {
                    self.def.panvDelta[self.def.idx] = self.def.panlMax;
                }
                self.panChange(self.def.idx);
            } */
        }).on('mouseup', function (e) {
            var e = e || window.event;
            var target = $(e.target);
            if (self.def.idx > -1 && self.def.hoverStatus && e.button === 0 && target.attr('class') === 'mask-use' && self.def.svgPaper.attr('class') !== 'straw') {
                self.selectCom(self.def.idx);
            }
            // if (self.def.downStatus1) {
            //     self.def.downStatus1 = false;
            // }
            // if (self.def.downStatus2) {
            //     self.def.downStatus2 = false;
            // }
        })

        // 滚轮缩放
        /* var eventHandle = {
            getEvent: function (event) {
                return event || window.event;
            },
            addEvent: function (elementName, type, handler) {
                $(elementName).each(function (i, element) {
                    if (element.addEventListener) {
                        element.addEventListener(type, handler, false);
                    }
                    else if (element.attachEvent) {
                        element.attachEvent('on' + type, handler);
                    } else {
                        element['on' + type] = handler;
                    }
                })
            },
            getWheelDelta: function (event) {
                return event.wheelDelta ? event.wheelDelta : (-event.detail) * 40;
            }
        }
        function mouseHandle(e) {
            var e = e || window.e;
            if (self.def.patternEls[self.def.idx] && self.def.hoverStatus && $Snap(e.target).attr('class') === 'mask-use') {
                self.selectCom(self.def.idx, true);
                e.preventDefault();
                e.stopPropagation();
                e = eventHandle.getEvent(e);
                var scaleDelta = eventHandle.getWheelDelta(e);
                if (!self.def.scaleDelta[self.def.idx]) { self.def.scaleDelta[self.def.idx] = 1; }
                if (scaleDelta < 0) { self.def.scaleDelta[self.def.idx] = parseFloat(self.def.scaleDelta[self.def.idx]) - self.def.scaleMin; }
                else { self.def.scaleDelta[self.def.idx] = parseFloat(self.def.scaleDelta[self.def.idx]) + self.def.scaleMin; }
                if (self.def.scaleDelta[self.def.idx] <= self.def.scaleMin) {
                    self.def.scaleDelta[self.def.idx] = self.def.scaleMin;
                } else if (self.def.scaleDelta[self.def.idx] >= self.def.scaleMax) {
                    self.def.scaleDelta[self.def.idx] = self.def.scaleMax;
                }
                self.scaleChange(self.def.idx);
            }
        }
        eventHandle.addEvent('#paper', 'mousewheel', mouseHandle);
        eventHandle.addEvent('#paper', 'DOMMouseScroll', mouseHandle); */

    };

    virtualSnap.prototype = {
        // 初始化
        init: function (data, callback) {
            this.def.tplData = JSON.parse(JSON.stringify(data));
            this.def.resetTplData = JSON.parse(JSON.stringify(data));
            // 清除画布
            this.def.svgPaper.clear();
            // 获取模板原始尺寸
            this.def.tplData.ogWH = this.getImgNatural(this.def.imgHost + this.def.tplData.sBigTemplateImg, true);
            // 绘制模板图
            this.drawTplImg(this.def.imgHost + this.def.tplData.sBigTemplateImg);
            // 绘制部件
            this.drawComPath(callback);
        },
        // 获取图片原始尺寸
        getImgNatural: function (src, type, successBack) {
            var img = new Image(),
                og = { w: 0, h: 0 },
                self = this;
            if (!src) { return og; }
            this.imgLoaded = false;
            img.crossOrigin = "Anonymous";
            img.onload = function () {
                og.w = this.width;
                og.h = this.height;
                if (type) { // 设置模板
                    if (self.def.smallScreen) {
                        self.def.w = self.def.smallSquareWH.w;
                        self.def.h = self.def.smallSquareWH.h;
                    } else {
                        self.def.w = self.def.bigSquareWH.w;
                        self.def.h = self.def.bigSquareWH.h;
                    }
                    if (this.width / this.height !== 1) {
                        self.def.aspectRatio = true;
                        if (self.def.smallScreen) {
                            self.def.w = self.def.smallRectWH.w;
                            self.def.h = self.def.smallRectWH.h;
                        } else {
                            self.def.w = self.def.bigRectWH.w;
                            self.def.h = self.def.bigRectWH.h;
                        }
                    }
                    var scale = 1;
                    if (self.def.w / og.w < self.def.h / og.h) {
                        scale = self.def.w / og.w
                    } else {
                        scale = self.def.h / og.h
                    }
                    self.def.tplData.tplScale = scale;
                    self.def.svgPaper.attr({
                        width: self.def.w + 'px',
                        height: self.def.h + 'px',
                        viewBox: '0 0 ' + og.w + ' ' + og.h + ''
                    })
                }
                successBack && successBack(og);
                self.imgLoaded = true;
            }
            img.onerror = function () {
                console.log("当前src=" + img.src);
                console.log("图片加载失败");
                self.imgLoaded = true;
            }
            img.src = src;
            return og;
        },
        // 绘制模板图
        drawTplImg: function (src) {
            var self = this;
            this.tplLoaded = false;
            clearInterval(this.drawTplImg.timeval);
            if (!this.imgLoaded) {
                this.drawTplImg.timeval = setInterval(function () {
                    clearInterval(self.drawTplImg.timeval);
                    self.drawTplImg(src);
                }, this.def.loopTime);
                return;
            }
            // do something
            this.def.tplEl = this.def.svgPaper.image(src, 0, 0, this.def.tplData.ogWH.w, this.def.tplData.ogWH.h)/* .node.style['mix-blend-mode'] = 'multiply' */;
            this.tplLoaded = true;
            // 垂直居中
            this.vcenter();
        },
        // 绘制部件路径
        drawComPath: function (callback) {
            var self = this;
            this.comLoaded = false;
            clearInterval(this.drawComPath.timeval);
            if (!this.tplLoaded) {
                this.drawComPath.timeval = setInterval(function () {
                    clearInterval(self.drawComPath.timeval);
                    self.drawComPath(callback);
                }, this.def.loopTime);
                return;
            }
            // do something
            this.def.patternEls = [];
            for (var i = 0; i < this.def.tplData.svg_file.length; i++) {
                this.def.comIdx = i;
                this.def.comEls[i] && this.def.comEls[i].forEach(function (v, idx) {
                    v.remove();
                })
                this.def.comUseBgEls[i] && this.def.comUseBgEls[i].forEach(function (v, idx) {
                    v.remove();
                })
                this.def.comUseMaskEls[i] && this.def.comUseMaskEls[i].forEach(function (v, idx) {
                    v.remove();
                })
                if (!this.def.tplData.svg_file[i].sPartPathArr.length) {
                    console.log('空路径:模板ID=' + this.def.tplData.svg_file[i].iTemplateId + ';部件名称=' + this.def.tplData.svg_file[i].sPartName);
                }
                // 路径
                this.def.comEls[i] = this.def.tplData.svg_file[i].sPartPathArr.map(function (v, idx) {
                    return self.def.svgPaper.path(v).toDefs().data('idx', i);
                })
                // use底色
                this.def.comUseBgEls[i] = this.def.comEls[i].map(function (v, idx) {
                    var item = self.def.svgPaper.use(v).attr({ 'fill': 'transparent' });
                    item.node.style['mix-blend-mode'] = 'multiply';
                    return item.data({ 'idx': i, 'fill': 'transparent' });
                })
                // use图案
                this.def.comUseMaskEls[i] = this.def.comEls[i].map(function (v, idx) {
                    var item = self.def.svgPaper.use(v).attr({ 'fill': 'transparent' });
                    item.node.style['mix-blend-mode'] = 'multiply';
                    return item.data({ 'idx': i, 'fill': 'transparent' }).addClass('mask-use');
                })
                // 初始化配置项
                this.def.tplData.svg_file[i].config = this.def.tplData.svg_file[i].config || JSON.parse(JSON.stringify(this.def.defCfg));
                this.def.tplData.svg_file[i].pattern = this.def.tplData.svg_file[i].pattern || { id: '', src: '' };
                this.drawComColor(this.def.tplData.svg_file[i].config.fill);
                if (this.def.tplData.svg_file[i].pattern.src) {
                    this.drawComBg(this.def.tplData.svg_file[i].pattern.id, this.def.tplData.svg_file[i].pattern.src, this.def.tplData.svg_file[i].config.loop);
                }
            }
            this.comLoaded = true;
            // 设置base64
            this.setBase64();
            // 默认选中第一个部件（全部）
            this.selectCom(0);
            // 初始完成回调
            callback && callback();
        },
        // 绘制部件背景色
        drawComColor: function (fill) {
            this.def.tplData.svg_file && (this.def.tplData.svg_file[this.def.comIdx].config.fill = fill);
            this.def.comUseBgEls[this.def.comIdx] && this.def.comUseBgEls[this.def.comIdx].forEach(function (v, i) {
                v.attr({ 'fill': fill }).data('fill', fill);
            })
        },
        // 绘制图案(type: 0无循环 1全循环 2水平循环 3垂直循环)
        /* drawComBg: function (id, src, type) {
            var self = this,
                idx = this.def.comIdx,
                loopType = type === undefined ? this.def.defloop : type;
            if (!this.def.tplData.svg_file.length) { return; }
            this.getImgNatural(src, false, function (og) {
                var ogwh = {
                    w: og.w,
                    h: og.h
                };
                // var ogwh = {
                //     w: 200,
                //     h: 200 / (og.w / og.h)
                // };
                for (var i = 0; i < self.def.tplData.svg_file.length; i++) {
                    self.def.scaleDelta[i] = 1;
                    self.def.patternEls[i] && self.def.patternEls[i].remove();
                    self.def.tplData.svg_file[i].pattern = { id: id, src: src };
                    self.def.tplData.svg_file[i].config.loop = loopType;
                    if (loopType == 0) {
                        self.def.patternEls[i] = self.def.svgPaper.image(src, 0, 0, ogwh.w, ogwh.h).pattern((self.def.tplData.ogWH.w + ogwh.w) / 2 * self.def.tplData.tplScale, (self.def.tplData.ogWH.h + ogwh.h) / 2 * self.def.tplData.tplScale, ogwh.w * self.def.scaleMax + self.def.panlMax, ogwh.h * self.def.scaleMax + self.def.panvMax);
                    } else if (loopType == 1) {
                        self.def.patternEls[i] = self.def.svgPaper.image(src, 0, 0, ogwh.w, ogwh.h).pattern(0, 0, ogwh.w, ogwh.h);
                    } else if (loopType == 2) {
                        self.def.patternEls[i] = self.def.svgPaper.image(src, 0, 0, ogwh.w, ogwh.h).pattern(0, (self.def.tplData.ogWH.h + ogwh.h) / 2 * self.def.tplData.tplScale, ogwh.w, ogwh.h * self.def.scaleMax + self.def.panvMax);
                    } else if (loopType == 3) {
                        self.def.patternEls[i] = self.def.svgPaper.image(src, 0, 0, ogwh.w, ogwh.h).pattern((self.def.tplData.ogWH.w + ogwh.w) / 2 * self.def.tplData.tplScale, 0, ogwh.w * self.def.scaleMax + self.def.panlMax, ogwh.h);
                    }
                    self.def.patternEls[i].attr({
                        'patternTransform': $Snap.matrix(1, 0, 0, 1, 0, 0),
                        'viewBox': ''
                    }).data({
                        'idx': i,
                        'ogw': ogwh.w,
                        'ogh': ogwh.h,
                        'id': id,
                        'x': 0,
                        'y': 0,
                        'px': 0,
                        'py': 0,
                        'pw': 0,
                        'ph': 0
                    });
                    self.def.comUseMaskEls[i] && self.def.comUseMaskEls[i].forEach(function (v, i) {
                        v.attr({ 'fill': self.def.patternEls[i] });
                        if (i === 0) {
                            var pathBox = $Snap.path.getBBox(self.def.comEls[i][i]);
                            self.def.patternEls[i].attr({
                                'x': (pathBox.x || 0) + ((pathBox.w || 0) - (ogwh.w || 0)) / 2,
                                'y': (pathBox.y || 0) + ((pathBox.h || 0) - (ogwh.h || 0)) / 2
                            }).data({
                                'px': (pathBox.x || 0),
                                'py': (pathBox.y || 0),
                                'pw': (pathBox.w || 0),
                                'ph': (pathBox.h || 0)
                            })
                        }
                    })
                    self.scaleChange(i, true);
                    self.angleChange(i, true);
                }
                // self.setBase64();
            })
        }, */
        drawComBg: function (id, src, type) {
            var self = this,
                idx = this.def.comIdx,
                loopType = type === undefined ? this.def.defloop : type;
            if (!(this.def.tplData.svg_file && this.def.tplData.svg_file[idx])) { return; }
            this.def.scaleDelta[idx] = 1;
            this.def.patternEls[idx] && this.def.patternEls[idx].remove();
            this.def.tplData.svg_file[idx].pattern = { id: id, src: src };
            this.def.tplData.svg_file[idx].config.loop = loopType;
            this.getImgNatural(src, false, function (og) {
                var ogwh = {
                    w: og.w,
                    h: og.h
                };
                // var ogwh = {
                //     w: 200,
                //     h: 200 / (og.w / og.h)
                // };
                if (loopType == 0) {
                    self.def.patternEls[idx] = self.def.svgPaper.image(src, 0, 0, ogwh.w, ogwh.h).pattern((self.def.tplData.ogWH.w + ogwh.w) / 2 * self.def.tplData.tplScale, (self.def.tplData.ogWH.h + ogwh.h) / 2 * self.def.tplData.tplScale, ogwh.w * self.def.scaleMax + self.def.panlMax, ogwh.h * self.def.scaleMax + self.def.panvMax);
                } else if (loopType == 1) {
                    self.def.patternEls[idx] = self.def.svgPaper.image(src, 0, 0, ogwh.w, ogwh.h).pattern(0, 0, ogwh.w, ogwh.h);
                } else if (loopType == 2) {
                    self.def.patternEls[idx] = self.def.svgPaper.image(src, 0, 0, ogwh.w, ogwh.h).pattern(0, (self.def.tplData.ogWH.h + ogwh.h) / 2 * self.def.tplData.tplScale, ogwh.w, ogwh.h * self.def.scaleMax + self.def.panvMax);
                } else if (loopType == 3) {
                    self.def.patternEls[idx] = self.def.svgPaper.image(src, 0, 0, ogwh.w, ogwh.h).pattern((self.def.tplData.ogWH.w + ogwh.w) / 2 * self.def.tplData.tplScale, 0, ogwh.w * self.def.scaleMax + self.def.panlMax, ogwh.h);
                }
                self.def.patternEls[idx].attr({
                    'patternTransform': $Snap.matrix(1, 0, 0, 1, 0, 0),
                    'viewBox': ''
                }).data({
                    'idx': idx,
                    'ogw': ogwh.w,
                    'ogh': ogwh.h,
                    'id': id,
                    'x': 0,
                    'y': 0,
                    'px': 0,
                    'py': 0,
                    'pw': 0,
                    'ph': 0
                });
                self.def.comUseMaskEls[idx] && self.def.comUseMaskEls[idx].forEach(function (v, i) {
                    v.attr({ 'fill': self.def.patternEls[idx] });
                    if (i === 0) {
                        var pathBox = $Snap.path.getBBox(self.def.comEls[idx][i]);
                        self.def.patternEls[idx].attr({
                            'x': (pathBox.x || 0) + ((pathBox.w || 0) - (ogwh.w || 0)) / 2,
                            'y': (pathBox.y || 0) + ((pathBox.h || 0) - (ogwh.h || 0)) / 2
                        }).data({
                            'px': (pathBox.x || 0),
                            'py': (pathBox.y || 0),
                            'pw': (pathBox.w || 0),
                            'ph': (pathBox.h || 0)
                        })
                    }
                })
                self.setBase64();
                self.scaleChange(idx, true);
                self.angleChange(idx, true);
            })
        },

        /* ================== 调取方法 ================== */

        // 选择部件
        selectCom: function (idx, type) {
            var self = this;
            this.def.comIdx = idx;
            if (!this.def.tplData.svg_file[idx]) { return; }
            // 初始化配置
            this.def.comBoxEl.children().eq(idx).addClass('on').siblings().removeClass('on');
            this.def.patternBoxEl.find('li[data-type=' + this.def.tplData.svg_file[idx].config.loop + ']').addClass('on').siblings().removeClass('on');
            this.scaleChange(idx, true, true);
            this.angleChange(idx, true, true);
            if (type) { return; }
            // 选中效果
            this.def.comUseBgEls[idx].forEach(function (v, i) {
                v.attr({ 'fill': 'skyblue' });
            })
            var timeout = setTimeout(function () {
                self.def.comUseBgEls[idx].forEach(function (v, i) {
                    var fill = v.data('fill') || 'transparent';
                    v.attr({ 'fill': fill });
                })
                clearTimeout(timeout);
            }, 300);
        },
        // 循环方式
        selLoopFunc: function (type, mode) {
            if (!this.def.patternEls[this.def.comIdx]) {
                mode && this.noPattern();
            }
            this.def.patternBoxEl.find('li[data-type=' + type + ']').addClass('on').siblings().removeClass('on');
            this.drawComBg(this.def.tplData.svg_file[this.def.comIdx].pattern.id, this.def.tplData.svg_file[this.def.comIdx].pattern.src, type);
        },
        // 缩放
        scaleChange: function (idx, status, mode) {
            if (!this.def.patternEls[idx]) {
                this.def.sliderScaleBtn.css('left', '25%');
                this.def.sliderScaleTxt.text('x1.00');
                return;
            }
            var ptData = this.def.patternEls[idx].data();
            var type = this.def.tplData.svg_file[idx].config.loop;
            var scale = this.def.tplData.svg_file[idx].config.scale;
            if (status) {
                this.def.scaleDelta[idx] = scale;
            }
            var maxW = parseFloat(this.def.sliderScaleBtn.parent().width());
            var x = parseFloat(this.def.scaleDelta[idx]).toFixed(2);
            this.def.tplData.svg_file[idx].config.scale = x;
            if (this.def.comIdx === idx) {
                this.def.sliderScaleBtn.css('left', x / this.def.scaleMax * maxW + 'px');
                this.def.sliderScaleTxt.text('x' + x);
            }
            if (mode) { return; }
            var w = ptData.ogw * parseFloat(this.def.scaleDelta[idx]);
            var h = ptData.ogh * parseFloat(this.def.scaleDelta[idx]);
            if (type == 0) {
                this.def.patternEls[idx].attr({
                    'width': ptData.ogw * this.def.scaleMax + this.def.panlMax,
                    'height': ptData.ogh * this.def.scaleMax + this.def.panvMax,
                    'x': ptData.px + (ptData.pw - w) / 2,
                    'y': ptData.py + (ptData.ph - h) / 2
                });
            } else if (type == 1) {
                this.def.patternEls[idx].attr({
                    'width': w,
                    'height': h,
                    'x': ptData.px + (ptData.pw - w) / 2,
                    'y': ptData.py + (ptData.ph - h) / 2
                });
            } else if (type == 2) {
                this.def.patternEls[idx].attr({
                    'width': w,
                    'x': ptData.px + (ptData.pw - w) / 2,
                    'y': ptData.py + (ptData.ph - h) / 2
                });
            } else if (type == 3) {
                this.def.patternEls[idx].attr({
                    'height': h,
                    'x': ptData.px + (ptData.pw - w) / 2,
                    'y': ptData.py + (ptData.ph - h) / 2
                });
            }
            this.def.patternEls[idx].select('image').attr({
                'width': w,
                'height': h
            });
        },
        // 缩放控制
        scaleChangeCol: function (type, ratio, mode) {
            if (!this.def.patternEls[this.def.comIdx]) {
                mode && this.noPattern();
                return;
            }
            this.selectCom(this.def.comIdx, true);
            if (!this.def.scaleDelta[this.def.comIdx]) { this.def.scaleDelta[this.def.comIdx] = 1; }
            if (type === '-') {
                this.def.scaleDelta[this.def.comIdx] = parseFloat(this.def.scaleDelta[this.def.comIdx]) - this.def.scaleMin;
                if (this.def.scaleDelta[this.def.comIdx] < this.def.scaleMin) {
                    this.def.scaleDelta[this.def.comIdx] = this.def.scaleMin;
                }
            } else if (type === '+') {
                this.def.scaleDelta[this.def.comIdx] = parseFloat(this.def.scaleDelta[this.def.comIdx]) + this.def.scaleMin;
                if (this.def.scaleDelta[this.def.comIdx] > this.def.scaleMax) {
                    this.def.scaleDelta[this.def.comIdx] = this.def.scaleMax;
                }
            } else {
                this.def.scaleDelta[this.def.comIdx] = ratio / (1 / this.def.scaleMax);
                if (this.def.scaleDelta[this.def.comIdx] < this.def.scaleMin) {
                    this.def.scaleDelta[this.def.comIdx] = this.def.scaleMin;
                } else if (this.def.scaleDelta[this.def.comIdx] > this.def.scaleMax) {
                    this.def.scaleDelta[this.def.comIdx] = this.def.scaleMax;
                }
            }
            this.scaleChange(this.def.comIdx);
        },
        // 旋转
        angleChange: function (idx, status, mode) {
            if (!this.def.patternEls[idx]) {
                this.def.sliderAngleBtn.css('left', '0px');
                this.def.sliderAngleTxt.text('0°');
                this.def.sliderPanlBtn.css('left', '50%');
                this.def.sliderPanlTxt.text('x0');
                this.def.sliderPanvBtn.css('left', '50%');
                this.def.sliderPanvTxt.text('x0');
                return;
            }
            var ptData = this.def.patternEls[idx].data();
            var scale = this.def.tplData.svg_file[idx].config.scale;
            var angle = this.def.tplData.svg_file[idx].config.angle;
            var panl = this.def.tplData.svg_file[idx].config.panl;
            var panv = this.def.tplData.svg_file[idx].config.panv;
            if (status) {
                this.def.scaleDelta[idx] = scale;
                this.def.angleDelta[idx] = angle;
                this.def.panlDelta[idx] = panl;
                this.def.panvDelta[idx] = panv;
            }
            var maxW = parseFloat(this.def.sliderAngleBtn.parent().width());
            var x = parseInt(this.def.angleDelta[idx]);
            var l = x / this.def.angleMax * maxW;
            var matrix = $Snap.matrix(1, 0, 0, 1, 0, 0).translate(panl, panv).rotate(x, parseFloat(this.def.patternEls[idx].attr('x')) + parseFloat(ptData.ogw) * this.def.tplData.svg_file[idx].config.scale / 2, parseFloat(this.def.patternEls[idx].attr('y')) + parseFloat(ptData.ogh) * this.def.tplData.svg_file[idx].config.scale / 2);
            this.def.patternEls[idx].attr('patternTransform', matrix);
            this.def.tplData.svg_file[idx].config.angle = x;
            if (this.def.comIdx === idx) {
                this.def.sliderAngleBtn.css('left', l + 'px');
                this.def.sliderAngleTxt.text(x + '°');
            }
            if (mode) {
                if (idx === this.def.comIdx) {
                    // 水平
                    x = parseInt(this.def.panlDelta[idx]);
                    l = (x / this.def.panlMax / 2 + 0.5) * maxW;
                    this.def.sliderPanlBtn.css('left', l + 'px');
                    this.def.sliderPanlTxt.text('x' + x);
                    // 垂直
                    x = parseInt(this.def.panvDelta[idx]);
                    l = (x / this.def.panvMax / 2 + 0.5) * maxW;
                    this.def.sliderPanvBtn.css('left', l + 'px');
                    this.def.sliderPanvTxt.text('x' + x);
                }
                return;
            }
        },
        // 旋转控制
        angleChangeCol: function (type, ratio, mode) {
            if (!this.def.patternEls[this.def.comIdx]) {
                mode && this.noPattern();
                return;
            }
            if (this.def.angleDelta[this.def.comIdx] === undefined) {
                this.def.angleDelta[this.def.comIdx] = 0;
            }
            if (type === '-') {
                this.def.angleDelta[this.def.comIdx] += -this.def.angleMin;
                if (this.def.angleDelta[this.def.comIdx] < 0) {
                    this.def.angleDelta[this.def.comIdx] = 0;
                }
            } else if (type === '+') {
                this.def.angleDelta[this.def.comIdx] += this.def.angleMin;
                if (this.def.angleDelta[this.def.comIdx] > this.def.angleMax) {
                    this.def.angleDelta[this.def.comIdx] = this.def.angleMax;
                }
            } else {
                this.def.angleDelta[this.def.comIdx] = ratio * this.def.angleMax;
                if (this.def.angleDelta[this.def.comIdx] < 0) {
                    this.def.angleDelta[this.def.comIdx] = 0;
                } else if (this.def.angleDelta[this.def.comIdx] > this.def.angleMax) {
                    this.def.angleDelta[this.def.comIdx] = this.def.angleMax;
                }
            }
            this.angleChange(this.def.comIdx);
        },
        // 拖动平移
        panChange: function (idx, status, type) {
            if (!this.def.patternEls[idx]) {
                this.def.sliderPanlBtn.css('left', '50%');
                this.def.sliderPanlTxt.text('x0');
                this.def.sliderPanvBtn.css('left', '50%');
                this.def.sliderPanvTxt.text('x0');
                return;
            }
            var ptData = this.def.patternEls[idx].data();
            var scale = this.def.tplData.svg_file[idx].config.scale;
            var angle = this.def.tplData.svg_file[idx].config.angle;
            var panl = this.def.tplData.svg_file[idx].config.panl;
            var panv = this.def.tplData.svg_file[idx].config.panv;
            if (status) {
                this.def.scaleDelta[idx] = scale;
                this.def.angleDelta[idx] = angle;
                this.def.panlDelta[idx] = panl;
                this.def.panvDelta[idx] = panv;
            }
            var maxW1 = parseFloat(this.def.sliderPanlBtn.parent().width());
            var x1 = parseInt(this.def.panlDelta[idx]);
            var l1 = (x1 / this.def.panlMax / 2 + 0.5) * maxW1;

            var maxW2 = parseFloat(this.def.sliderPanvBtn.parent().width());
            var x2 = parseInt(this.def.panvDelta[idx]);
            var l2 = (x2 / this.def.panvMax / 2 + 0.5) * maxW2;
            if (type === 1) { // 水平
                var matrix1 = $Snap.matrix(1, 0, 0, 1, 0, 0).translate(x1, panv).rotate(angle, parseFloat(this.def.patternEls[idx].attr('x')) + parseFloat(ptData.ogw) * this.def.tplData.svg_file[idx].config.scale / 2, parseFloat(this.def.patternEls[idx].attr('y')) + parseFloat(ptData.ogh) * this.def.tplData.svg_file[idx].config.scale / 2);
                this.def.patternEls[idx].attr('patternTransform', matrix1);
                this.def.tplData.svg_file[idx].config.panl = x1;
            } else if (type === 2) { // 垂直
                var matrix2 = $Snap.matrix(1, 0, 0, 1, 0, 0).translate(panl, x2).rotate(angle, parseFloat(this.def.patternEls[idx].attr('x')) + parseFloat(ptData.ogw) * this.def.tplData.svg_file[idx].config.scale / 2, parseFloat(this.def.patternEls[idx].attr('y')) + parseFloat(ptData.ogh) * this.def.tplData.svg_file[idx].config.scale / 2);
                this.def.patternEls[idx].attr('patternTransform', matrix2);
                this.def.tplData.svg_file[idx].config.panv = x2;
            } else { // 混合
                var matrix3 = $Snap.matrix(1, 0, 0, 1, 0, 0).translate(x1, x2).rotate(angle, parseFloat(this.def.patternEls[idx].attr('x')) + parseFloat(ptData.ogw) * this.def.tplData.svg_file[idx].config.scale / 2, parseFloat(this.def.patternEls[idx].attr('y')) + parseFloat(ptData.ogh) * this.def.tplData.svg_file[idx].config.scale / 2);
                this.def.patternEls[idx].attr('patternTransform', matrix3);
                this.def.tplData.svg_file[idx].config.panl = x1;
                this.def.tplData.svg_file[idx].config.panv = x2;
            }
            if (this.def.comIdx === idx) {
                this.def.sliderPanlBtn.css('left', l1 + 'px');
                this.def.sliderPanlTxt.text('x' + x1);
                this.def.sliderPanvBtn.css('left', l2 + 'px');
                this.def.sliderPanvTxt.text('x' + x2);
            }
        },
        // 水平平移控制
        panlChangeCol: function (type, ratio, mode) {
            if (!this.def.patternEls[this.def.comIdx]) {
                mode && this.noPattern();
                return;
            }
            if (this.def.panlDelta[this.def.comIdx] === undefined) {
                this.def.panlDelta[this.def.comIdx] = 0;
            }
            if (type === '-') {
                this.def.panlDelta[this.def.comIdx] += -1;
                if (this.def.panlDelta[this.def.comIdx] < this.def.panlMin) {
                    this.def.panlDelta[this.def.comIdx] = this.def.panlMin;
                }
            } else if (type === '+') {
                this.def.panlDelta[this.def.comIdx] += 1;
                if (this.def.panlDelta[this.def.comIdx] > this.def.panlMax) {
                    this.def.panlDelta[this.def.comIdx] = this.def.panlMax;
                }
            } else {
                this.def.panlDelta[this.def.comIdx] = (ratio - 0.5) * this.def.panlMax * 2;
                if (this.def.panlDelta[this.def.comIdx] < this.def.panlMin) {
                    this.def.panlDelta[this.def.comIdx] = this.def.panlMin;
                } else if (this.def.panlDelta[this.def.comIdx] > this.def.panlMax) {
                    this.def.panlDelta[this.def.comIdx] = this.def.panlMax;
                }
            }
            this.panChange(this.def.comIdx, false, 1);
        },
        // 垂直平移控制
        panvChangeCol: function (type, ratio, mode) {
            if (!this.def.patternEls[this.def.comIdx]) {
                mode && this.noPattern();
                return;
            }
            if (this.def.panvDelta[this.def.comIdx] === undefined) {
                this.def.panvDelta[this.def.comIdx] = 0;
            }
            if (type === '-') {
                this.def.panvDelta[this.def.comIdx] += -1;
                if (this.def.panvDelta[this.def.comIdx] < this.def.panvMin) {
                    this.def.panvDelta[this.def.comIdx] = this.def.panvMin;
                }
            } else if (type === '+') {
                this.def.panvDelta[this.def.comIdx] += 1;
                if (this.def.panvDelta[this.def.comIdx] > this.def.panvMax) {
                    this.def.panvDelta[this.def.comIdx] = this.def.panvMax;
                }
            } else {
                this.def.panvDelta[this.def.comIdx] = (ratio - 0.5) * this.def.panvMax * 2;
                if (this.def.panvDelta[this.def.comIdx] < this.def.panvMin) {
                    this.def.panvDelta[this.def.comIdx] = this.def.panvMin;
                } else if (this.def.panvDelta[this.def.comIdx] > this.def.panvMax) {
                    this.def.panvDelta[this.def.comIdx] = this.def.panvMax;
                }
            }
            this.panChange(this.def.comIdx, false, 2);
        },

        // 设置base64
        setBase64: function () {
            var imageAll = this.def.svgPaper.selectAll('image');
            for (var i = 0; i < imageAll.length; i++) {
                if (!imageAll[i].data('base64')) {
                    this.getBase64Image(imageAll[i].node.href.baseVal, imageAll[i], function (imgItem, dataURL, ext) {
                        imgItem.attr('href', dataURL).data({ 'base64': true, 'ext': ext });
                    })
                }
            }
        },
        // 获取base64
        getBase64Image: function (imgSrc, imgItem, success) {
            var image = new Image();
            image.crossOrigin = "Anonymous";
            image.onload = function () {
                var canvas = document.createElement("canvas");
                canvas.width = image.width;
                canvas.height = image.height;
                var ctx = canvas.getContext("2d");
                ctx.drawImage(image, 0, 0, image.width, image.height);
                var ext = image.src.substring(image.src.lastIndexOf(".") + 1).toLowerCase();
                var dataURL = canvas.toDataURL("image/" + ext);
                success && success(imgItem, dataURL, ext);
            }
            image.src = imgSrc;
        },
        // 取色
        getColor: function (svgXml, w, h, x, y) {
            var self = this;
            var image = new Image();
            image.crossOrigin = "Anonymous";
            image.onload = function () {
                var canvas = document.createElement('canvas');
                canvas.width = w;
                canvas.height = h;
                var context = canvas.getContext('2d');
                context.drawImage(image, 0, 0);
                var colorData = canvas.getPixelColor(x, y);
                var color = colorData.rgba;
                self.drawComColor(color);
            }
            image.src = 'data:image/svg+xml;base64,' + window.btoa(unescape(encodeURIComponent(svgXml)));
        },
        // 生成效果图
        genImage: function (type, callback, errback, noloop) {
            var self = this;
            var w = this.def.tplData.ogWH.w,
                h = this.def.tplData.ogWH.h;
            if (type) {
                w = this.def.w;
                h = this.def.h;
            }
            if (type && w === this.def.smallSquareWH.w) {
                w = this.def.bigSquareWH.w;
                h = this.def.bigSquareWH.h;
            }
            if (type && w === this.def.smallRectWH.w) {
                w = this.def.bigRectWH.w;
                h = this.def.bigRectWH.h;
            }
            var cloneEl = this.def.svgPaper.clone();
            cloneEl.attr({'width': w + 'px', 'height': h + 'px', 'style': 'position:absolute;z-index:-1'});
            // 创建一个新的canvas
            var canvas = document.createElement('canvas');
            canvas.width = w;
            canvas.height = h;
            // var scale = window.devicePixelRatio || 1; // 设备的devicePixelRatio
            // canvas.width = w * scale;
            // canvas.height = h * scale;
            // canvas.getContext('2d').scale(scale, scale);
            html2canvas(cloneEl.node, {
                canvas: canvas,
                scale: 1,
                // scale: scale,
                allowTaint: true,
                useCORS: true,
                width: w + 'px',
                hegiht: h + 'px',
            }).then(function (cvs) {
                var ctx = cvs.getContext('2d');
                // 关闭抗锯齿形
                ctx.mozImageSmoothingEnabled = false;
                ctx.webkitImageSmoothingEnabled = false;
                ctx.msImageSmoothingEnabled = false;
                ctx.imageSmoothingEnabled = false;
                // 生成图片
                var baseurl = cvs.toDataURL('image/png', 1);
                if (!noloop && self.def.broswer.broswer === 'Safari') {
                    self.genImage(type, callback, errback, true);
                    return;
                }
                callback && callback(baseurl, 'png');
                // cvs.style.display = 'none';
                // document.documentElement.appendChild(cvs);
            }).catch(function () {
                console.log('图片加载失败');
                errback && errback();
            })
            cloneEl.remove();
            /* var svgXml = cloneEl.outerSVG();
            var image = new Image();
            image.crossOrigin = "Anonymous";
            image.onload = function () {
                var canvas = document.createElement('canvas');
                var context = canvas.getContext('2d');
                var scale = window.devicePixelRatio || 1; // 设备的devicePixelRatio
                canvas.width = w * scale;
                canvas.height = h * scale;
                context.scale(scale, scale);
                context.drawImage(image, 0, 0, w * scale, h * scale);
                var baseurl = canvas.toDataURL('image/png', 1);
                callback && callback(baseurl, 'png');
            }
            image.onerror = function () {
                console.log('图片加载失败');
                errback && errback();
            }
            image.src = 'data:image/svg+xml;base64,' + window.btoa(unescape(encodeURIComponent(svgXml)).replace(/\shref/g, ' xlink:href')); */
            // var src = 'data:image/svg+xml;base64,' + unescape(encodeURIComponent(svgXml)).replace(/\shref/g, ' xlink:href');
            // var a = document.createElement('a');
            // a.href = image.src;
            // document.documentElement.appendChild(a);
            // var a = document.createElement('a');
            // a.href = src;
            // document.documentElement.appendChild(a);
        },
        // 下载效果图
        downImage: function () {
            var self = this;
            this.genImage(false, function (baseurl, ext) {
                var a = document.createElement('a');
                var mimeType = "image/" + ext;
                a.download = self.def.tplData.sTemplateName;
                a.href = baseurl;
                a.dataset.downloadurl = [mimeType, a.download, a.href].join(':');
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });
        },
        // 重置
        reset: function () {
            this.init(this.def.resetTplData);
        },
        // 垂直居中
        vcenter: function () {
            var t = ($('.js-virtual-content-box').height() - ($('.js-virtual-control').height() + this.def.h + 15)) / 2;
            if (t < 15) { t = 15 }
            $('.js-virtual-content').css('margin-top', t + 'px');
        },
        // 获得旋转夹角
        getAngle: function (x1, y1, x2, y2, cx, cy) {
            //中心点
            // var cx = this.def.tplData.ogWH.w / 2;
            // var cy = this.def.tplData.ogWH.h / 2;
            //2个点之间的角度获取
            var c1 = Math.atan2(y1 - cy, x1 - cx) * 180 / (Math.PI);
            var c2 = Math.atan2(y2 - cy, x2 - cx) * 180 / (Math.PI);
            var angle;
            c1 = c1 <= -90 ? (360 + c1) : c1;
            c2 = c2 <= -90 ? (360 + c2) : c2;
            //夹角获取
            angle = Math.floor(c2 - c1);
            angle = angle < 0 ? angle + 360 : angle;
            return angle;
        },
        // 获取浏览器信息
        getBroswer: function () {
            var sys = {};
            var ua = navigator.userAgent.toLowerCase();
            var s;
            (s = ua.match(/edge\/([\d.]+)/)) ? sys.edge = s[1] :
            (s = ua.match(/rv:([\d.]+)\) like gecko/)) ? sys.ie = s[1] :
            (s = ua.match(/msie ([\d.]+)/)) ? sys.ie = s[1] :
            (s = ua.match(/firefox\/([\d.]+)/)) ? sys.firefox = s[1] :
            (s = ua.match(/chrome\/([\d.]+)/)) ? sys.chrome = s[1] :
            (s = ua.match(/opera.([\d.]+)/)) ? sys.opera = s[1] :
            (s = ua.match(/version\/([\d.]+).*safari/)) ? sys.safari = s[1] : 0;
            if (sys.edge) return { broswer : "Edge", version : sys.edge };
            if (sys.ie) return { broswer : "IE", version : sys.ie };
            if (sys.firefox) return { broswer : "Firefox", version : sys.firefox };
            if (sys.chrome) return { broswer : "Chrome", version : sys.chrome };
            if (sys.opera) return { broswer : "Opera", version : sys.opera };
            if (sys.safari) return { broswer : "Safari", version : sys.safari };
            return { broswer : "", version : "0" };
        },
        // 无图案提示
        noPattern: function() {
            var self = this;
            clearTimeout(this.noPatternTimeout);
            this.def.errInfoEl.css('visibility', 'visible');
            this.noPatternTimeout = setTimeout(function() {
                self.def.errInfoEl.css('visibility', 'hidden');
                clearTimeout(self.noPatternTimeout);
            }, 2000);
        }
    }

    window.virtualSnap = virtualSnap;

    return virtualSnap;
}));
