/**
 * ImageAssistant
 * Project Home: http://www.pullywood.com/ImageAssistant/
 * Author: 睡虫子(Joey)
 * Copyright (C) 2013-2020 普利坞(Pullywood.com)
**/
"use strict";

window._w_acuity = null;

window._w_grave = "";

window._w_lease = null;

window._w_nadir = null;

window._w_ballot = null;

window._w_agenda = [];

window._w_lesion = null;

window._w_cull = null;

window._w_phial = null;

window._w_buoy = null;

window._w_gaffe = null;

chrome.tabs.getCurrent((function(tab) {
    window._w_lesion = tab.id;
    $(initEditor);
}));

window._w_slot = {
    timeout: 512,
    lastExeTime: new Date,
    timer: null,
    updateStatics: false,
    delayAgain: true
};

window._w_scythe = {
    timeout: 2048,
    lastExeTime: new Date,
    timer: null,
    updateStatics: false,
    delayAgain: true
};

function _w_asset(_w_group, _w_huddle) {
    let _w_parity = "";
    if (!_w_group.startsWith("data:image")) {
        let _w_prude = {};
        _w_prude[_w_group] = _w_huddle;
        _w_posse()._w_knack(_w_prude, _w_lesion, true);
        _w_parity = _w_group.substring(_w_group.lastIndexOf("/") + 1);
    } else {
        _w_parity = "ia_" + _w_hide(16) + ".png";
    }
    _w_cult(_w_group, _w_parity, false, false);
}

function _w_virago(_w_group, _w_huddle) {
    if (!_w_group.startsWith("data:image")) {
        let _w_prude = {};
        _w_prude[_w_group] = _w_huddle;
        _w_posse()._w_knack(_w_prude, _w_lesion, true);
        $("#watermarkImageUrl").prop("value", _w_group).attr("data-referer", _w_huddle);
    }
    chrome.tabs.update(_w_lesion, {
        active: true
    }, (function(tab) {
        chrome.windows.update(tab.windowId, {
            focused: true,
            drawAttention: false
        }, (function(window) {}));
    }));
    _w_sway(_w_group, false);
}

function _w_cult(_w_group, _w_parity, _w_apiary, _w_pecan) {
    if (!_w_acuity) _w_acuity = new Image;
    if (_w_apiary) {
        _w_penury(_w_acuity, _w_parity, _w_pecan);
    } else {
        _w_acuity.onload = function() {
            if (!_w_apiary) {
                $("#scaleRate").prop("value", 1);
                $("#scWidth").prop("value", _w_acuity.width);
                $("#scWidth").attr("max", _w_acuity.width);
                $("#scHeight").prop("value", _w_acuity.height);
                $("#scHeight").attr("max", _w_acuity.height);
                window._w_grave = _w_parity;
            }
            _w_penury(this, _w_parity, _w_pecan);
        };
        _w_acuity.src = _w_group;
    }
}

function _w_sway(_w_gutter, _w_apiary) {
    if (!_w_buoy) _w_buoy = new Image;
    if (_w_apiary) {
        _w_manure();
    } else {
        _w_buoy.onload = function() {
            if (!_w_apiary) {
                $("#wmImageCutUp").prop("value", null);
                $("#wmImageCutRight").prop("value", null);
                $("#wmImageCutDown").prop("value", null);
                $("#wmImageCutLeft").prop("value", null);
                $("#watermarkScaleRate").prop("value", 1);
                $("#wmScWidth").attr("max", _w_buoy.width).prop("value", _w_buoy.width);
                $("#wmScHeight").attr("max", _w_buoy.height).prop("value", _w_buoy.height);
                $("#brToAlpha").prop("checked", false);
                $("#brToAlphaReverse").prop("checked", false).prop("disabled", true);
                $("#pixelAlphaRateRange").prop("value", 1).prop("disabled", true);
                if (_w_gutter == window._w_cull.watermarkImageUrl) {
                    $("#wmImageCutUp").prop("value", window._w_cull.imageCutUp);
                    $("#wmImageCutRight").prop("value", window._w_cull.imageCutRight);
                    $("#wmImageCutDown").prop("value", window._w_cull.imageCutDown);
                    $("#wmImageCutLeft").prop("value", window._w_cull.imageCutLeft);
                    $("#watermarkScaleRate").prop("value", window._w_cull.watermarkImageWidth / _w_buoy.watermarkImageHeight);
                    $("#wmScWidth").prop("value", window._w_cull.watermarkImageWidth);
                    $("#wmScHeight").prop("value", window._w_cull.watermarkImageHeight);
                    $("#brToAlpha").prop("checked", window._w_cull.brToAlpha);
                    $("#brToAlphaReverse").prop("checked", window._w_cull.brToAlphaReverse).prop("disabled", window._w_cull.brToAlpha ? false : true);
                    $("#pixelAlphaRateRange").prop("value", window._w_cull.pixelAlphaRateRange).prop("disabled", window._w_cull.brToAlpha ? false : true);
                }
            }
            _w_manure();
        };
        _w_buoy.src = _w_gutter;
    }
}

function _w_whit() {
    let _w_prose = $("#qrCodeFgColor").prop("value");
    let _w_faucet = $("#qrCodeBgColor").prop("value");
    let _w_molar = _w_truism(_w_faucet);
    let _w_fetter = parseInt($("#qrCodeAlpha").prop("value")) / 100;
    _w_faucet = "RGBA(" + _w_molar.r + ", " + _w_molar.g + ", " + _w_molar.b + ", " + _w_fetter + ")";
    let _w_fender = $("#qrCodeSize").prop("value");
    let _w_sod = $("#qrCodeText").prop("value");
    window._w_gaffe = _w_notch(_w_sod, _w_fender, _w_prose, _w_faucet);
    setTimeout(_w_manure, 256);
}

function _w_mogul() {
    if (!window._w_cull) window._w_cull = {};
    let _w_tutor = window._w_cull;
    _w_tutor.textWatermark = $(".typeMenuItem.textWatermark").is(".active");
    _w_tutor.imageWatermark = $(".typeMenuItem.imageWatermark").is(".active");
    _w_tutor.qrCodeWatermark = $(".typeMenuItem.qrCodeWatermark").is(".active");
    _w_tutor.font = $("select#fontSelecter option:selected").prop("value");
    _w_tutor.fontSize = parseInt($("#fontSize").prop("value"));
    _w_tutor.fontColor = $("#fontColor").prop("value");
    _w_tutor.fontAlpha = parseInt($("#fontAlpha").prop("value"));
    _w_tutor.borderFont = $("#borderFont:checkbox").is(":checked");
    _w_tutor.text = $("#watermarkText").prop("value");
    _w_tutor.imageAlpha = parseInt($("#imageAlpha").prop("value"));
    if (_w_buoy && _w_log(_w_buoy.src)) {
        _w_tutor.watermarkImageUrl = $("#watermarkImageUrl").prop("value");
        _w_tutor.watermarkImageReferer = $("#watermarkImageReferer").attr("data-referer");
        _w_tutor.imageCutUp = $("#wmImageCutUp").prop("value");
        _w_tutor.imageCutRight = $("#wmImageCutRight").prop("value");
        _w_tutor.imageCutDown = $("#wmImageCutDown").prop("value");
        _w_tutor.imageCutLeft = $("#wmImageCutLeft").prop("value");
        _w_tutor.watermarkImageWidth = $("#wmScWidth").prop("value");
        _w_tutor.watermarkImageHeight = $("#wmScHeight").prop("value");
        _w_tutor.brToAlpha = $("#brToAlpha").prop("checked");
        _w_tutor.brToAlphaReverse = $("#brToAlphaReverse").prop("checked");
        _w_tutor.pixelAlphaRateRange = $("#pixelAlphaRateRange").prop("value");
    }
    _w_tutor.qrCodeFgolor = $("#qrCodeFgColor").prop("value");
    _w_tutor.qrCodeBgolor = $("#qrCodeBgColor").prop("value");
    _w_tutor.qrCodeSize = $("#qrCodeSize").prop("value");
    _w_tutor.qrCodeAlpha = parseInt($("#qrCodeAlpha").prop("value"));
    _w_tutor.qrCodeBorder = parseInt($("#qrCodeBorder").prop("value"));
    _w_tutor.qrCodeText = $("#qrCodeText").prop("value");
    _w_tutor.tileWatermark = $(".presentationMenuItem.tileWatermark").is(".active");
    _w_tutor.locationWatermark = $(".presentationMenuItem.locationWatermark").is(".active");
    _w_tutor.offsetX = parseInt($("#offsetX").prop("value"));
    _w_tutor.offsetY = parseInt($("#offsetY").prop("value"));
    _w_tutor.marginX = parseInt($("#marginX").prop("value"));
    _w_tutor.marginY = parseInt($("#marginY").prop("value"));
    _w_tutor.rotate = parseInt($("#rotation").prop("value"));
    _w_tutor.alignX = parseInt($("#alignX").prop("value"));
    _w_tutor.alignY = parseInt($("#alignY").prop("value"));
    _w_tutor.middleAlignX = $("#middleAlignX").is(":checked");
    _w_tutor.middleAlignY = $("#middleAlignY").is(":checked");
    localStorage["watermarkConfigure"] = JSON.stringify(_w_tutor);
    window._w_cull = _w_tutor;
}

function _w_rudder() {
    let _w_tutor = localStorage["watermarkConfigure"];
    if (_w_tutor && _w_tutor.length > 0) {
        _w_tutor = JSON.parse(_w_tutor);
        $("select#fontSelecter option[value='" + _w_tutor.font + "']").attr("selected", true);
        $("#fontSizeRange,#fontSize").prop("value", _w_tutor.fontSize);
        $("#fontColor").prop("value", _w_tutor.fontColor);
        $("#fontAlphaRange,#fontAlpha").prop("value", _w_tutor.fontAlpha);
        $("#borderFont:checkbox").prop("checked", _w_tutor.borderFont);
        $("#middleAlignX").prop("checked", _w_tutor.middleAlignX);
        $("#middleAlignY").prop("checked", _w_tutor.middleAlignY);
        $("#watermarkText").prop("value", _w_tutor.text);
        $("#imageAlphaRange,#imageAlpha").prop("value", _w_tutor.imageAlpha);
        if (_w_tutor.watermarkImageUrl && _w_log(_w_tutor.watermarkImageUrl)) {
            $("#watermarkImageUrl").prop("value", _w_tutor.watermarkImageUrl);
            $("#watermarkImageReferer").attr("data-referer", _w_tutor.watermarkImageReferer);
            $("#wmImageCutUp").prop("value", _w_tutor.imageCutUp);
            $("#wmImageCutRight").prop("value", _w_tutor.imageCutRight);
            $("#wmImageCutDown").prop("value", _w_tutor.imageCutDown);
            $("#wmImageCutLeft").prop("value", _w_tutor.imageCutLeft);
            $("#wmScWidth").prop("value", _w_tutor.watermarkImageWidth);
            $("#wmScHeight").prop("value", _w_tutor.watermarkImageHeight);
            $("#brToAlpha").prop("checked", _w_tutor.brToAlpha);
            $("#brToAlphaReverse").prop("checked", _w_tutor.brToAlphaReverse).prop("disabled", _w_tutor.brToAlpha ? false : true);
            $("#pixelAlphaRateRange").prop("value", _w_tutor.pixelAlphaRateRange).prop("disabled", _w_tutor.brToAlpha ? false : true);
        }
        $("#qrCodeFgColor").prop("value", _w_tutor.qrCodeFgolor);
        $("#qrCodeBgColor").prop("value", _w_tutor.qrCodeBgolor);
        $("#qrCodeSizeRange,#qrCodeSize").prop("value", _w_tutor.qrCodeSize);
        $("#qrCodeAlphaRange,#qrCodeAlpha").prop("value", _w_tutor.qrCodeAlpha);
        $("#qrCodeBorderRange,#qrCodeBorder").prop("value", _w_tutor.qrCodeBorder);
        $("#qrCodeText").prop("value", _w_tutor.qrCodeText);
        $("#offsetXRange,#offsetX").prop("value", _w_tutor.offsetX);
        $("#offsetYRange,#offsetY").prop("value", _w_tutor.offsetY);
        $("#marginXRange,#marginX").prop("value", _w_tutor.marginX);
        $("#marginYRange,#marginY").prop("value", _w_tutor.marginY);
        $("#rotationRange,#rotation").prop("value", _w_tutor.rotate);
        $("#alignXRange,#alignX").prop("value", _w_tutor.alignX);
        $("#alignYRange,#alignY").prop("value", _w_tutor.alignY);
        if (_w_tutor.watermarkImageUrl && _w_tutor.watermarkImageUrl.length > 0) {
            if (!_w_tutor.watermarkImageUrl.startsWith("data:image")) {
                let _w_prude = {};
                _w_prude[_w_tutor.watermarkImageUrl] = _w_tutor.watermarkImageReferer;
                _w_posse()._w_knack(_w_prude, _w_lesion, true);
            }
            _w_sway(_w_tutor.watermarkImageUrl, false);
        }
        if (_w_tutor.textWatermark) {
            $(".typeMenuItem.textWatermark").trigger("click");
        } else if (_w_tutor.imageWatermark) {
            $(".typeMenuItem.imageWatermark").trigger("click");
        } else if (_w_tutor.qrCodeWatermark) {
            $(".typeMenuItem.qrCodeWatermark").trigger("click");
        }
        if (_w_tutor.tileWatermark) {
            $(".presentationMenuItem.tileWatermark").trigger("click");
        } else if (_w_tutor.locationWatermark) {
            $(".presentationMenuItem.locationWatermark").trigger("click");
        }
        window._w_cull = _w_tutor;
    } else {
        _w_prey();
    }
}

function _w_prey() {
    let _w_tutor = {
        textWatermark: true,
        imageWatermark: false,
        qrCodeWatermark: false,
        font: "微软雅黑",
        fontSize: 32,
        fontColor: "#000000",
        fontAlpha: 50,
        borderFont: false,
        text: _w_acme("_w_ram"),
        imageAlpha: 50,
        watermarkImageUrl: "http://www.pullywood.com/ImageAssistant/images/logo.png",
        watermarkImageReferer: "http://www.pullywood.com/",
        imageCutUp: null,
        imageCutRight: null,
        imageCutDown: null,
        imageCutLeft: null,
        watermarkImageWidth: 128,
        watermarkImageHeight: 128,
        brToAlpha: true,
        brToAlphaReverse: false,
        pixelAlphaRateRange: 1,
        qrCodeFgolor: "#008000",
        qrCodeBgolor: "#ffffff",
        qrCodeSize: 128,
        qrCodeAlpha: 32,
        qrCodeBorder: 10,
        qrCodeText: "https://www.pullywood.com/ImageAssistant/",
        tileWatermark: true,
        locationWatermark: false,
        offsetX: 0,
        offsetY: 0,
        marginX: 32,
        marginY: 32,
        rotate: -30,
        alignX: -20,
        alignY: -20,
        middleAlignX: false,
        middleAlignY: false
    };
    localStorage["watermarkConfigure"] = JSON.stringify(_w_tutor);
    _w_rudder();
}

function _w_manure() {
    if (!_w_acuity) return;
    _w_armada(window._w_slot, (function() {
        _w_cult(_w_acuity.src, _w_grave, true, false);
    }), false);
    _w_mogul();
}

function _w_flaw() {
    if (!_w_buoy || !_w_acuity) return;
    _w_armada(window._w_slot, (function() {
        _w_sway(_w_acuity.src, true);
    }), false);
    _w_mogul();
}

chrome.runtime.onMessage.addListener((function(message, sender, callback) {
    message && message.type == "_w_bazaar" && _w_asset(message._w_finery, message._w_sprout);
    message && message.type == "_w_otter" && _w_virago(message._w_finery, message._w_sprout);
}));

function initEditor() {
    $("#header_link_container").append($("<a />", {
        class: "navbar-brand",
        text: _w_acme("_w_leeway")
    }).prepend($("<img />", {
        src: "./images/icon128.png",
        id: "brand_text"
    })));
    $("#navbar").append($("<ul />", {
        class: "nav navbar-nav navbar-right"
    }).append($("<li />").append($("<a />", {
        target: "_pullywood_",
        href: "http://www.pullywood.com/",
        text: _w_acme("_w_sting")
    }).prepend($("<span />", {
        class: "glyphicon glyphicon-home"
    })))).append($("<li />").append($("<a />", {
        id: "_cxyz_fav_",
        target: "_imageAssistant_favorite",
        href: "./favorite.html",
        text: _w_acme("_w_vermin")
    }).prepend($("<span />", {
        class: "glyphicon glyphicon-folder-open"
    })))));
    $.getJSON(_w_whiff, (function(data) {
        let $_w_dearth = $("#_cxyz_fav_");
        if (data.shortName) {
            let _w_pilot = data.icon;
            let $_w_comma = $("<div />", {
                id: "popup_user_info"
            });
            let $_w_hammer = $("<span />", {
                text: " [ " + data.shortName + " ] "
            });
            $_w_comma.append($_w_hammer);
            $_w_dearth.attr("href", _w_whiff);
            $_w_dearth.append($_w_comma);
        } else {}
    }));
    $("#imageLoader,#multipleImageInput").attr("accept", "image/" + "*");
    document.onpaste = function(event) {
        let _w_hymn = event.clipboardData.items[0].getAsFile();
        if (!_w_hymn.type || !_w_hymn.type.startsWith("image")) return;
        let _w_lease = new FileReader;
        _w_lease.onload = function(event) {
            _w_cult(event.target.result, _w_maven(16), false, false);
        };
        _w_lease.readAsDataURL(_w_hymn);
    };
    chrome.fontSettings && chrome.fontSettings.getFontList((function populateFontList(fontArr) {
        fontArr.sort().reverse();
        for (let key in fontArr) {
            let _w_oak = fontArr[key].displayName;
            _w_oak = _w_oak.replace(/^\s\s*/, "").replace(/\s\s*$/, "");
            if (_w_oak.match(/[_\-\s]Italic$/) || _w_oak.match(/[_\-\s](Demi)?[Bb]old$/) || _w_oak.match(/[_\-\s]Medium$/) || _w_oak.match(/[_\-\s](Ultra)?[Ll]ight$/) || _w_oak.match(/[_\-\s]Condensed$/)) _w_agenda.push(_w_oak); else {
                _w_agenda.push(_w_oak);
            }
        }
        let $_w_pledge = $("#fontSelecter");
        for (let i = 0; i < _w_agenda.length; ++i) {
            let _w_oak = _w_agenda[i];
            $_w_pledge.append($("<option />", {
                value: _w_oak,
                text: _w_oak,
                style: "font-family:" + _w_oak + ";",
                selected: window._w_cull && window._w_cull.font && window._w_cull.font == _w_oak
            }));
        }
    }));
    $(window).on("resize", _w_manure);
    $("#imageLoader").on("change", (function(funEvent) {
        let _w_guilt = $(this).get(0).files;
        if (_w_guilt.length == 0) return;
        let _w_recipe = _w_guilt[0];
        let $_w_verse = $(this);
        if (!_w_lease) _w_lease = new FileReader;
        _w_lease.onload = function(event) {
            _w_cult(event.target.result, _w_recipe.name, false, false);
        };
        _w_lease.readAsDataURL(_w_recipe);
    }));
    $("#scaleRate,#scWidth,#scHeight").on("change", (function() {
        let _w_purity = parseFloat($("#scaleRate").prop("value"));
        let _w_jockey = parseInt($("#scWidth").prop("value"));
        let _w_slice = parseInt($("#scHeight").prop("value"));
        let _w_mute = parseInt($("#scWidth").prop("max"));
        let _w_apogee = parseInt($("#scHeight").prop("max"));
        if ($(this).is("#scaleRate")) {
            _w_jockey = Math.round(_w_mute * _w_purity);
            _w_slice = Math.round(_w_apogee * _w_purity);
        } else if ($(this).is("#scWidth")) {
            _w_purity = _w_jockey / _w_mute;
            _w_slice = Math.round(_w_apogee * _w_purity);
        } else if ($(this).is("#scHeight")) {
            _w_purity = _w_slice / _w_apogee;
            _w_jockey = Math.round(_w_mute * _w_purity);
        } else {
            return;
        }
        $("#scaleRate").prop("value", _w_purity);
        $("#scWidth").prop("value", _w_jockey);
        $("#scHeight").prop("value", _w_slice);
        _w_manure();
    }));
    $(".typeMenuItem").on("click", (function() {
        $(".typeMenuItem").removeClass("active");
        $(this).addClass("active");
        $(".typeMenuTab").hide();
        if ($(this).is(".textWatermark")) {
            $(".typeMenuTab.textWatermark").show();
            _w_manure();
        } else if ($(this).is(".imageWatermark")) {
            $(".typeMenuTab.imageWatermark").show();
            _w_flaw();
        } else if ($(this).is(".qrCodeWatermark")) {
            $(".typeMenuTab.qrCodeWatermark").show();
            _w_whit();
        }
    }));
    $("#fontSelecter").on("change", (function() {
        _w_manure();
    }));
    $("#fontSizeRange,#fontSize").on("change", (function() {
        if ($(this).is("#fontSizeRange")) {
            $("#fontSize").prop("value", $(this).prop("value"));
        } else if ($(this).is("#fontSize")) {
            $("#fontSizeRange").prop("value", $(this).prop("value"));
        } else {
            return;
        }
        _w_manure();
    }));
    $("#fontColor").on("input", (function() {
        _w_manure();
    }));
    $("#fontAlphaRange,#fontAlpha").on("change", (function() {
        if ($(this).is("#fontAlphaRange")) {
            $("#fontAlpha").prop("value", $(this).prop("value"));
        } else if ($(this).is("#fontAlpha")) {
            $("#fontAlphaRange").prop("value", $(this).prop("value"));
        } else {
            return;
        }
        _w_manure();
    }));
    $("#borderFont").on("change", (function() {
        _w_manure();
    }));
    $("#watermarkText").on("change", (function() {
        _w_manure();
    }));
    $("#watermarkImage").on("change", (function(funEvent) {
        let _w_guilt = $(this).get(0).files;
        if (_w_guilt.length == 0) return;
        let _w_recipe = _w_guilt[0];
        let $_w_verse = $(this);
        if (!_w_phial) _w_phial = new FileReader;
        _w_phial.onload = function(event) {
            _w_sway(event.target.result, false);
        };
        _w_phial.readAsDataURL(_w_recipe);
    }));
    $("#wmImageCutUp,#wmImageCutRight,#wmImageCutDown,#wmImageCutLeft").on("change", (function() {
        if (_w_buoy == null) return;
        let _w_vogue = parseInt($("#wmImageCutUp").prop("value"));
        _w_vogue = _w_vogue ? _w_vogue : 0;
        let _w_hail = parseInt($("#wmImageCutRight").prop("value"));
        _w_hail = _w_hail ? _w_hail : 0;
        let _w_pinch = parseInt($("#wmImageCutDown").prop("value"));
        _w_pinch = _w_pinch ? _w_pinch : 0;
        let _w_dart = parseInt($("#wmImageCutLeft").prop("value"));
        _w_dart = _w_dart ? _w_dart : 0;
        let _w_mute = _w_buoy.width - _w_dart - _w_hail;
        let _w_apogee = _w_buoy.height - _w_vogue - _w_pinch;
        $("#wmScWidth").prop("max", _w_mute);
        $("#wmScHeight").prop("max", _w_apogee);
        $("#watermarkScaleRate").trigger("change");
        _w_manure();
    }));
    $("#watermarkScaleRate,#wmScWidth,#wmScHeight").on("change", (function() {
        if (_w_buoy == null) return;
        let _w_delta = parseFloat($("#watermarkScaleRate").prop("value"));
        let _w_vertex = parseInt($("#wmScWidth").prop("value"));
        let _w_chef = parseInt($("#wmScHeight").prop("value"));
        let _w_mute = parseInt($("#wmScWidth").prop("max"));
        let _w_apogee = parseInt($("#wmScHeight").prop("max"));
        if ($(this).is("#watermarkScaleRate")) {
            _w_vertex = Math.round(_w_mute * _w_delta);
            _w_chef = Math.round(_w_apogee * _w_delta);
        } else if ($(this).is("#wmScWidth")) {
            _w_delta = _w_vertex / _w_mute;
            _w_chef = Math.round(_w_apogee * _w_delta);
        } else if ($(this).is("#wmScHeight")) {
            _w_delta = _w_chef / _w_apogee;
            _w_vertex = Math.round(_w_mute * _w_delta);
        } else {
            return;
        }
        $("#watermarkScaleRate").prop("value", _w_delta);
        $("#wmScWidth").prop("value", _w_vertex);
        $("#wmScHeight").prop("value", _w_chef);
        _w_flaw();
    }));
    $("#brToAlpha,#brToAlphaReverse").on("change", (function() {
        if ($("#brToAlpha").is(":checked")) {
            $("#brToAlphaReverse,#pixelAlphaRateRange").attr("disabled", false);
        } else {
            $("#brToAlphaReverse,#pixelAlphaRateRange").attr("disabled", true);
        }
        _w_manure();
    }));
    $("#pixelAlphaRateRange").on("change", (function() {
        _w_manure();
    }));
    $("#imageAlphaRange,#imageAlpha").on("change", (function() {
        if ($(this).is("#imageAlphaRange")) {
            $("#imageAlpha").prop("value", $(this).prop("value"));
        } else if ($(this).is("#imageAlpha")) {
            $("#imageAlphaRange").prop("value", $(this).prop("value"));
        } else {
            return;
        }
        _w_flaw();
    }));
    $("#qrCodeFgColor").on("input", (function() {
        _w_whit();
    }));
    $("#qrCodeBgColor").on("input", (function() {
        _w_whit();
    }));
    $("#qrCodeSizeRange,#qrCodeSize").on("change", (function() {
        if ($(this).is("#qrCodeSizeRange")) {
            $("#qrCodeSize").prop("value", $(this).prop("value"));
        } else if ($(this).is("#qrCodeSize")) {
            $("#qrCodeSizeRange").prop("value", $(this).prop("value"));
        } else {
            return;
        }
        _w_whit();
    }));
    $("#qrCodeAlphaRange,#qrCodeAlpha").on("change", (function() {
        if ($(this).is("#qrCodeAlphaRange")) {
            $("#qrCodeAlpha").prop("value", $(this).prop("value"));
        } else if ($(this).is("#qrCodeAlpha")) {
            $("#qrCodeAlphaRange").prop("value", $(this).prop("value"));
        } else {
            return;
        }
        _w_whit();
    }));
    $("#qrCodeBorderRange,#qrCodeBorder").on("change", (function() {
        if ($(this).is("#qrCodeBorderRange")) {
            $("#qrCodeBorder").prop("value", $(this).prop("value"));
        } else if ($(this).is("#qrCodeBorder")) {
            $("#qrCodeBorderRange").prop("value", $(this).prop("value"));
        } else {
            return;
        }
        _w_whit();
    }));
    $("#qrCodeText").on("input", (function() {
        _w_whit();
    }));
    $(".presentationMenuItem").on("click", (function() {
        $(".presentationMenuItem").removeClass("active");
        $(this).addClass("active");
        $(".presentationMenuTab").hide();
        if ($(this).is(".tileWatermark")) {
            $(".presentationMenuTab.tileWatermark").show();
        } else if ($(this).is(".locationWatermark")) {
            $(".presentationMenuTab.locationWatermark").show();
        }
        _w_manure();
    }));
    $("#offsetXRange,#offsetX").on("change", (function() {
        if ($(this).is("#offsetXRange")) {
            $("#offsetX").prop("value", $(this).prop("value"));
        } else if ($(this).is("#offsetX")) {
            $("#offsetXRange").prop("value", $(this).prop("value"));
        } else {
            return;
        }
        _w_manure();
    }));
    $("#offsetYRange,#offsetY").on("change", (function() {
        if ($(this).is("#offsetYRange")) {
            $("#offsetY").prop("value", $(this).prop("value"));
        } else if ($(this).is("#offsetY")) {
            $("#offsetYRange").prop("value", $(this).prop("value"));
        } else {
            return;
        }
        _w_manure();
    }));
    $("#marginXRange,#marginX").on("change", (function() {
        if ($(this).is("#marginXRange")) {
            $("#marginX").prop("value", $(this).prop("value"));
        } else if ($(this).is("#marginX")) {
            $("#marginXRange").prop("value", $(this).prop("value"));
        } else {
            return;
        }
        _w_manure();
    }));
    $("#marginYRange,#marginY").on("change", (function() {
        if ($(this).is("#marginYRange")) {
            $("#marginY").prop("value", $(this).prop("value"));
        } else if ($(this).is("#marginY")) {
            $("#marginYRange").prop("value", $(this).prop("value"));
        } else {
            return;
        }
        _w_manure();
    }));
    $("#rotationRange,#rotation").on("change", (function() {
        if ($(this).is("#rotationRange")) {
            $("#rotation").prop("value", $(this).prop("value"));
        } else if ($(this).is("#rotation")) {
            $("#rotationRange").prop("value", $(this).prop("value"));
        } else {
            return;
        }
        _w_manure();
    }));
    $("#alignXRange,#alignX").on("change", (function() {
        if ($(this).is("#alignXRange")) {
            $("#alignX").prop("value", $(this).prop("value"));
        } else if ($(this).is("#alignX")) {
            $("#alignXRange").prop("value", $(this).prop("value"));
        } else {
            return;
        }
        _w_manure();
    }));
    $("#alignYRange,#alignY").on("change", (function() {
        if ($(this).is("#alignYRange")) {
            $("#alignY").prop("value", $(this).prop("value"));
        } else if ($(this).is("#alignY")) {
            $("#alignYRange").prop("value", $(this).prop("value"));
        } else {
            return;
        }
        _w_manure();
    }));
    $("#middleAlignX").on("change", (function() {
        if ($(this).is(":checked")) {
            $("#alignXRange,#alignX").attr("disabled", true);
        } else {
            $("#alignXRange,#alignX").attr("disabled", false);
        }
        _w_manure();
    }));
    $("#middleAlignY").on("change", (function() {
        if ($(this).is(":checked")) {
            $("#alignYRange,#alignY").attr("disabled", true);
        } else {
            $("#alignYRange,#alignY").attr("disabled", false);
        }
        _w_manure();
    }));
    $("#downloadCanvasImage").on("click", (function() {
        _w_cult(_w_acuity.src, _w_grave, false, true);
    }));
    $("#batchImage").on("click", (function() {
        $("#multipleImageInput").get(0).click();
    }));
    $("#openDownloadFolderBtn").on("click", _w_enmity);
    $("#multipleImageInput").on("change", (function() {
        let _w_guilt = $(this).get(0).files;
        let $_w_verse = $(this);
        let _w_pariah = Object.keys(_w_guilt);
        let _w_edict = _w_pariah.length;
        $(window).off("resize", _w_manure);
        let _w_needle = document.title;
        (function makeWM() {
            if (_w_pariah.length == 0) {
                document.title = _w_acme("_w_critic") + _w_acme("_w_debate");
                setTimeout((function() {
                    document.title = _w_needle;
                }), 2e3);
                $(window).on("resize", _w_manure);
                _w_manure();
            } else {
                document.title = _w_acme("_w_critic") + (_w_edict - _w_pariah.length) + "/" + _w_edict;
                let _w_recipe = _w_guilt[_w_pariah.shift()];
                if (!_w_lease) _w_lease = new FileReader;
                _w_lease.onload = function(event) {
                    let _w_sack = (new Date).getTime();
                    _w_cult(event.target.result, _w_recipe.name, false, true);
                    let _w_deed = (new Date).getTime();
                    let _w_helmet = _w_deed - _w_sack;
                    let _w_nil = _w_helmet > 256 ? _w_helmet * 2 : 256;
                    (function ctAction() {
                        chrome.downloads.search({
                            urlRegex: "^data:image/.*$",
                            state: "in_progress"
                        }, (function(data) {
                            if (data.length > 2) {
                                setTimeout(ctAction, _w_nil);
                            } else {
                                makeWM();
                            }
                        }));
                    })();
                };
                _w_lease.readAsDataURL(_w_recipe);
            }
        })();
    }));
    $("#resetConfigure").on("click", (function() {
        _w_prey();
        _w_manure();
    }));
    document.title = _w_acme("_w_scarp");
    $("#editor_i18n_1001").html(_w_acme("_w_glut"));
    $("#editor_i18n_1002").html(_w_acme("_w_rue"));
    $("#editor_i18n_1003").html(_w_acme("_w_chapel"));
    $("#editor_i18n_1004").html(_w_acme("_w_volley"));
    $("#editor_i18n_1005").html(_w_acme("_w_amble"));
    $("#editor_i18n_1006").html(_w_acme("_w_soot"));
    $("#editor_i18n_1007").html(_w_acme("_w_stern"));
    $("#editor_i18n_1008").html(_w_acme("_w_grief"));
    $("#editor_i18n_1009").html(_w_acme("_w_martyr"));
    $("#editor_i18n_1010").html(_w_acme("_w_prey"));
    $("#editor_i18n_1011").html(_w_acme("_w_tatter"));
    $("#editor_i18n_1012").html(_w_acme("_w_facade"));
    $("#editor_i18n_1013").html(_w_acme("_w_fusion"));
    $("#editor_i18n_1014").html(_w_acme("_w_quiver"));
    $("#editor_i18n_1015").html(_w_acme("_w_heresy"));
    $("#wmImageCutUp").attr("placeHolder", _w_acme("_w_gulch"));
    $("#wmImageCutRight").attr("placeHolder", _w_acme("_w_vanity"));
    $("#wmImageCutDown").attr("placeHolder", _w_acme("_w_shuck"));
    $("#wmImageCutLeft").attr("placeHolder", _w_acme("_w_fathom"));
    $("#editor_i18n_1020").html(_w_acme("_w_putter"));
    $("#editor_i18n_1021").html(_w_acme("_w_snatch"));
    $("#editor_i18n_1022").html(_w_acme("_w_mote"));
    $("#editor_i18n_1023").html(_w_acme("_w_threat"));
    $("#editor_i18n_1024").html(_w_acme("_w_dummy"));
    $("#editor_i18n_1025").html(_w_acme("_w_galaxy"));
    $("#editor_i18n_1026").html(_w_acme("_w_lumen"));
    $("#editor_i18n_1027").html(_w_acme("_w_planet"));
    $("#editor_i18n_1028").html(_w_acme("_w_spoke"));
    $("#editor_i18n_1029").html(_w_acme("_w_avowal"));
    $("#editor_i18n_1030").html(_w_acme("_w_frond"));
    $("#editor_i18n_1031").html(_w_acme("_w_tread"));
    $("#editor_i18n_1032").html(_w_acme("_w_brat"));
    $("#editor_i18n_1033").html(_w_acme("_w_quill"));
    $("#editor_i18n_1034").html(_w_acme("_w_flush"));
    $("#editor_i18n_1035").html(_w_acme("_w_mural"));
    $("#editor_i18n_1036").html(_w_acme("_w_aria"));
    $("#editor_i18n_1037").html(_w_acme("_w_handle"));
    $("#editor_i18n_1038").html(_w_acme("_w_rival"));
    $("#editor_i18n_1039").html(_w_acme("_w_omelet"));
    $("#editor_i18n_1040").html(_w_acme("_w_lancet"));
    $("#editor_i18n_1041").html(_w_acme("_w_sheen"));
    $("#editor_i18n_1042").html(_w_acme("_w_menace"));
    $("#editor_i18n_1043").html(_w_acme("_w_aspect"));
    $("#editor_i18n_1044").html(_w_acme("_w_mulct"));
    $("#editor_i18n_1045").html(_w_acme("_w_margin"));
    $("#editor_i18n_1046").html(_w_acme("_w_vagary"));
    $("#editor_i18n_1047").html(_w_acme("_w_tenure"));
    _w_rudder();
}

function _w_penury(_w_mantle, _w_parity, _w_pecan) {
    if (!_w_nadir) _w_nadir = $("#canvas").get(0);
    let $_w_guffaw = $(_w_nadir).parent();
    _w_nadir.width = $("#scWidth").prop("value");
    _w_nadir.height = $("#scHeight").prop("value");
    let _w_mute = _w_nadir.parentElement.offsetWidth;
    if (_w_nadir.width <= _w_mute) {
        _w_nadir.style.width = _w_nadir.width + "px";
        _w_nadir.style.height = _w_nadir.height + "px";
        $("#displayRate").html(_w_acme("_w_slag") + "100%");
    } else {
        _w_nadir.style.width = _w_mute + "px";
        _w_nadir.style.height = _w_mute / _w_nadir.width * _w_nadir.height + "px";
        $("#displayRate").html(_w_acme("_w_slag") + (_w_mute / _w_nadir.width * 100).toFixed(1) + "%");
    }
    if (!_w_ballot) _w_ballot = _w_nadir.getContext("2d");
    _w_ballot.drawImage(_w_mantle, 0, 0, _w_nadir.width, _w_nadir.height);
    let _w_escort = function(_w_sermon) {
        let _w_hoe = $("#watermarkText").prop("value");
        if (_w_hoe.length == 0) return;
        let _w_raid = parseInt($("#fontSize").prop("value"));
        let _w_hut = _w_truism($("#fontColor").prop("value"));
        let _w_pelt = parseInt($("#fontAlpha").prop("value")) / 100;
        let _w_trunk = "rgba(" + _w_hut.r + ", " + _w_hut.g + ", " + _w_hut.b + ", " + _w_pelt + ")";
        _w_ballot.font = _w_raid + "px " + $("select#fontSelecter option:selected").prop("value");
        _w_ballot.strokeStyle = _w_trunk;
        _w_ballot.fillStyle = _w_trunk;
        let _w_bog = {};
        _w_bog.width = _w_ballot.measureText(_w_hoe).width;
        _w_bog.height = _w_raid;
        _w_sermon(_w_bog, (function(x, y) {
            if ($("#borderFont:checkbox").is(":checked")) {
                _w_ballot.strokeText(_w_hoe, x, y + _w_bog.height);
            } else {
                _w_ballot.fillText(_w_hoe, x, y + _w_bog.height);
            }
        }));
    };
    let _w_wallow = null;
    let _w_quack = function(_w_sermon) {
        if (_w_buoy == null) return;
        if (_w_wallow == null) {
            let _w_vogue = parseInt($("#wmImageCutUp").prop("value"));
            _w_vogue = _w_vogue ? _w_vogue : 0;
            let _w_hail = parseInt($("#wmImageCutRight").prop("value"));
            _w_hail = _w_hail ? _w_hail : 0;
            let _w_pinch = parseInt($("#wmImageCutDown").prop("value"));
            _w_pinch = _w_pinch ? _w_pinch : 0;
            let _w_dart = parseInt($("#wmImageCutLeft").prop("value"));
            _w_dart = _w_dart ? _w_dart : 0;
            let _w_cob = _w_buoy.width - _w_dart - _w_hail;
            let _w_pivot = _w_buoy.height - _w_vogue - _w_pinch;
            let _w_marvel = document.createElement("canvas");
            _w_marvel.width = _w_cob;
            _w_marvel.height = _w_pivot;
            let _w_mold = _w_marvel.getContext("2d");
            _w_mold.drawImage(_w_buoy, _w_dart, _w_vogue, _w_cob, _w_pivot, 0, 0, _w_cob, _w_pivot);
            if ($("#brToAlpha").is(":checked")) {
                let _w_stroke = $("#brToAlphaReverse").is(":checked");
                let _w_idiom = parseFloat($("#pixelAlphaRateRange").prop("value"));
                let _w_garret = _w_mold.getImageData(0, 0, _w_marvel.width, _w_marvel.height);
                let _w_argot = _w_garret.data.length;
                for (let i = 0; i < _w_argot; i += 4) {
                    let _w_mate = .299 * _w_garret.data[i] + .587 * _w_garret.data[i + 1] + .114 * _w_garret.data[i + 2];
                    let _w_balm = _w_mate;
                    if (_w_stroke) {
                        _w_balm = 255 - _w_balm;
                    }
                    _w_balm = _w_balm * _w_idiom;
                    if (_w_garret.data[i + 3] > 0) _w_garret.data[i + 3] = _w_balm;
                }
                _w_mold.putImageData(_w_garret, 0, 0);
            }
            _w_wallow = _w_marvel;
        }
        let _w_zest = parseInt($("#wmScWidth").prop("value"));
        let _w_stupor = parseInt($("#wmScHeight").prop("value"));
        let _w_maize = parseInt($("#imageAlpha").prop("value")) / 100;
        let _w_bog = {};
        _w_bog.width = _w_zest;
        _w_bog.height = _w_stupor;
        _w_sermon(_w_bog, (function(x, y) {
            _w_ballot.save();
            _w_ballot.globalAlpha = _w_maize;
            _w_ballot.drawImage(_w_wallow, 0, 0, _w_wallow.width, _w_wallow.height, x, y, _w_zest, _w_stupor);
            _w_ballot.restore();
        }));
    };
    let _w_pawn = function(_w_sermon) {
        if (window._w_gaffe == null) return;
        let _w_zest = window._w_gaffe.width;
        let _w_stupor = window._w_gaffe.height;
        let _w_faucet = $("#qrCodeBgColor").prop("value");
        let _w_molar = _w_truism(_w_faucet);
        let _w_fetter = parseInt($("#qrCodeAlpha").prop("value")) / 100;
        _w_faucet = "RGBA(" + _w_molar.r + ", " + _w_molar.g + ", " + _w_molar.b + ", " + _w_fetter + ")";
        let _w_pitch = parseInt($("#qrCodeBorderRange").prop("value"));
        let _w_bog = {};
        _w_bog.width = _w_zest + 2 * _w_pitch;
        _w_bog.height = _w_stupor + 2 * _w_pitch;
        _w_sermon(_w_bog, (function(x, y) {
            let _w_valve = _w_ballot.fillStyle;
            _w_ballot.fillStyle = _w_faucet;
            _w_ballot.fillRect(x, y, _w_zest + 2 * _w_pitch, _w_stupor + 2 * _w_pitch);
            _w_ballot.fillStyle = _w_valve;
            _w_ballot.drawImage(window._w_gaffe, 0, 0, window._w_gaffe.width, window._w_gaffe.height, x + _w_pitch, y + _w_pitch, _w_zest, _w_stupor);
        }));
    };
    let _w_fern = function(_w_sermon) {
        if ($(".typeMenuItem.textWatermark").is(".active")) {
            _w_escort(_w_sermon);
        } else if ($(".typeMenuItem.imageWatermark").is(".active")) {
            _w_quack(_w_sermon);
        } else if ($(".typeMenuItem.qrCodeWatermark").is(".active")) {
            _w_pawn(_w_sermon);
        }
    };
    let _w_gong = function() {
        let _w_inroad = {};
        _w_inroad.width = parseInt($("#marginX").prop("value"));
        _w_inroad.height = parseInt($("#marginY").prop("value"));
        let _w_snob = parseInt($("#rotation").prop("value"));
        let _w_wile = {};
        _w_wile.x = parseInt($("#offsetX").prop("value"));
        _w_wile.y = parseInt($("#offsetY").prop("value"));
        let _w_turret = Math.sqrt(Math.pow(_w_nadir.width + Math.abs(_w_wile.x), 2) + Math.pow(_w_nadir.height + Math.abs(_w_wile.y), 2));
        let _w_sermon = function(_w_bog, _w_screw) {
            _w_bog.width += _w_inroad.width;
            _w_bog.height += _w_inroad.height;
            _w_ballot.rotate(_w_snob * Math.PI / 180);
            for (let x = 0; x < _w_turret; x += _w_bog.width) {
                for (let y = 0; y < _w_turret; y += _w_bog.height) {
                    _w_screw(x + _w_inroad.width + _w_wile.x, y + _w_wile.y);
                }
            }
            for (let x = 0; x < _w_turret; x += _w_bog.width) {
                for (let y = -_w_bog.height; y > -_w_turret; y -= _w_bog.height) {
                    _w_screw(x + _w_inroad.width + _w_wile.x, y + _w_wile.y);
                }
            }
            for (let x = -_w_bog.width; x > -_w_turret; x -= _w_bog.width) {
                for (let y = -_w_bog.height; y > -_w_turret; y -= _w_bog.height) {
                    _w_screw(x + _w_inroad.width + _w_wile.x, y + _w_wile.y);
                }
            }
            for (let x = -_w_bog.width; x > -_w_turret; x -= _w_bog.width) {
                for (let y = 0; y < _w_turret; y += _w_bog.height) {
                    _w_screw(x + _w_inroad.width + _w_wile.x, y + _w_wile.y);
                }
            }
        };
        _w_fern(_w_sermon);
    };
    let _w_bonnet = function() {
        let _w_feint = parseInt($("#alignX").prop("value"));
        let _w_irony = parseInt($("#alignY").prop("value"));
        let _w_forage = $("#middleAlignX").is(":checked");
        let _w_rigor = $("#middleAlignY").is(":checked");
        let _w_sermon = function(_w_bog, _w_screw) {
            let _w_spire = {
                x: 0,
                y: 0
            };
            if (_w_forage) {
                _w_spire.x = Math.round(_w_nadir.width / 2 - _w_bog.width / 2);
            } else if (_w_feint > 0) {
                _w_spire.x = _w_feint;
            } else if (_w_feint < 0) {
                _w_spire.x = _w_nadir.width - _w_bog.width + _w_feint + 1;
            }
            if (_w_rigor) {
                _w_spire.y = Math.round(_w_nadir.height / 2 - _w_bog.height / 2);
            } else if (_w_irony > 0) {
                _w_spire.y = _w_irony;
            } else if (_w_irony < 0) {
                _w_spire.y = _w_nadir.height - _w_bog.height + _w_irony + 1;
            }
            _w_screw(_w_spire.x, _w_spire.y);
        };
        _w_fern(_w_sermon);
    };
    if ($(".presentationMenuItem.tileWatermark").is(".active")) {
        _w_gong();
    } else if ($(".presentationMenuItem.locationWatermark").is(".active")) {
        _w_bonnet();
    }
    _w_parity = "watermark_" + _w_parity;
    _w_parity = _w_parity.replace(/^(.*?)(\.[\w]{3,4})?$/, "$1.png");
    let _w_imp = function() {
        blobUtil.canvasToBlob(_w_nadir).then((function(blob) {
            chrome.downloads.download({
                url: blobUtil.createObjectURL(blob),
                filename: _w_orchid(_w_acme("manifest_ext_name") + "/" + _w_acme("_w_verse") + "/" + _w_parity),
                saveAs: false,
                conflictAction: "uniquify"
            });
        }));
    };
    if (_w_pecan) {
        _w_imp();
    }
    let _w_boast = parseInt(_w_nadir.style.width.replace("px", ""));
    let _w_smear = parseInt(_w_nadir.style.height.replace("px", ""));
    $(_w_nadir).parent().off("mouseover mouseout mousemove");
    if (_w_boast < _w_nadir.width) {
        let _w_medley = function(event) {
            let _w_guffaw = $(_w_nadir).parent().get(0);
            let _w_tinker = _w_guffaw.offsetTop;
            let _w_gist = _w_guffaw.offsetLeft;
            let _w_soot = event.pageX - _w_gist;
            let _w_slag = event.pageY - _w_tinker;
            let _w_wallop = -(_w_nadir.width - _w_boast) * _w_soot / _w_boast;
            let _w_riffle = -(_w_nadir.height - _w_smear) * _w_slag / _w_smear;
            $(_w_nadir).css("margin-top", _w_riffle);
            $(_w_nadir).css("margin-left", _w_wallop);
        };
        $(_w_nadir).parent().on("mouseover", (function(event) {
            $(_w_nadir).css("width", _w_nadir.width);
            $(_w_nadir).css("height", _w_nadir.height);
            $(this).off("mousemove").on("mousemove", _w_medley);
        })).on("mouseout", (function() {
            $(_w_nadir).css("width", _w_boast);
            $(_w_nadir).css("height", _w_smear);
            $(_w_nadir).css("margin", "0px auto");
            $(this).off("mousemove", _w_medley);
        }));
    }
}