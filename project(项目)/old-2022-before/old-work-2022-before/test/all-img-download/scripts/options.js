/**
 * ImageAssistant
 * Project Home: http://www.pullywood.com/ImageAssistant/
 * Author: 睡虫子(Joey)
 * Copyright (C) 2013-2020 普利坞(Pullywood.com)
**/
"use strict";

$((function() {
    document.title = _w_acme("_w_knack");
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
    $("#sideNavbar").append($("<ul />", {
        class: "nav nav-sidebar"
    }).append($("<li />").append($("<a />", {
        class: "sidbarItem",
        href: "#",
        contentId: "extOption",
        text: _w_acme("_w_ritual")
    }).prepend($("<span />", {
        class: "glyphicon glyphicon-wrench"
    })))).append($("<li />").append($("<a />", {
        class: "sidbarItem",
        href: "#",
        contentId: "aboutExt",
        text: _w_acme("_w_ivory")
    }).prepend($("<span />", {
        class: "glyphicon glyphicon-copyright-mark"
    })))));
    $("#defSizeOptTle").append(_w_acme("_w_molar"));
    $("#defSizeOptAlert").append(_w_acme("_w_fury"));
    $("#sizeOptionTle").append(_w_acme("_w_hedge"));
    $("#defautlOperation").append($("<div />", {
        class: "btn btn-primary",
        id: "selectAllButton",
        text: _w_acme("_w_apogee")
    })).append($("<div />", {
        class: "btn btn-primary",
        id: "selectNoneButton",
        text: _w_acme("_w_pygmy")
    }));
    $("#customSizeOption").append(_w_acme("_w_abuse"));
    $("#newItemWidth").attr("placeHolder", _w_acme("_w_hawker"));
    $("#newItemHeight").attr("placeHolder", _w_acme("_w_reign"));
    $("#newItemButton").append(_w_acme("_w_strife"));
    $("#aboutExTle").append(_w_acme("_w_coup"));
    $("#aboutExtCnt").append($("<h4 />", {
        class: "media-heading",
        text: _w_acme("_w_herald")
    })).append($("<p />", {
        text: _w_acme("_w_scrub")
    })).append($("<p />", {
        html: _w_acme("_w_iota")
    })).append($("<div />", {
        class: "pwd-callout pwd-callout-info"
    }).append($("<h4 />", {
        class: "page-header",
        text: _w_acme("_w_scad")
    })).append($("<p />").append(_w_acme("_w_goggle")).append($("<a />", {
        target: "_pullywood_",
        href: "http://www.pullywood.com/ImageAssistant/#docs-download",
        text: chrome.runtime.getManifest().version
    }).append($("<span />", {
        id: "newVersion",
        text: "Latest: " + localStorage["version"]
    })))).append($("<p />").append(_w_acme("_w_guru")).append($("<a />", {
        target: "_chromeAppStore_",
        href: "https://chrome.google.com/webstore/detail/dbjbempljhcmhlfpfacalomonjpalpko",
        text: "https://chrome.google.com/webstore/detail/dbjbempljhcmhlfpfacalomonjpalpko"
    }))).append($("<p />").append(_w_acme("_w_imp")).append($("<a />", {
        target: "_edgeAppStore_",
        href: "https://microsoftedge.microsoft.com/addons/detail/odphnbhiddhdpoccbialllejaajemdio",
        text: "https://microsoftedge.microsoft.com/addons/detail/odphnbhiddhdpoccbialllejaajemdio"
    }))).append($("<p />").append(_w_acme("_w_cuff")).append($("<a />", {
        target: "_firefoxAppStore_",
        href: "https://addons.mozilla.org/zh-CN/firefox/addon/ia-batch-image-downloader/",
        text: "https://addons.mozilla.org/zh-CN/firefox/addon/ia-batch-image-downloader/"
    }))).append($("<p />").append(_w_acme("_w_consul")).append($("<a />", {
        target: "_source_list_",
        href: "http://www.pullywood.com/publish/imageassistant-resource-list",
        text: "http://www.pullywood.com/publish/imageassistant-resource-list"
    }))).append($("<p />").append(_w_acme("_w_flask")).append($("<a />", {
        target: "_pullywood_",
        href: "https://www.pullywood.com/ImageAssistant/",
        text: _w_acme("_w_groom")
    }))).append($("<p />").append(_w_acme("_w_scythe")).append($("<a />", {
        target: "_pullywood_",
        href: "https://www.pullywood.com/publish/",
        text: _w_acme("_w_succor")
    }))).append($("<p />").append("Twitter: ").append($("<a />", {
        target: "_twitter_",
        href: "https://twitter.com/pullywood",
        text: "https://twitter.com/pullywood"
    }))).append($("<p />").append($("<img />", {
        height: 128,
        src: "./images/wechat_offical.jpg"
    })).append($("<img />", {
        height: 128,
        src: "./images/wechat.jpg"
    })))).append($("<p />").append(_w_acme("_w_comity")).append(" &copy; 2013 - 2021 ").append($("<a />", {
        target: "_pullywood_",
        href: "http://www.pullywood.com/",
        text: _w_acme("_w_scoff")
    })).append($("<span> | </span>").append($("<a />", {
        target: "_privacy_",
        href: _w_acme("_w_whiff"),
        text: _w_acme("_w_epic")
    }))));
    $("#extClickAct").bootstrapSwitch({
        labelText: _w_acme("_w_felon"),
        onText: _w_acme("_w_moment"),
        offText: _w_acme("_w_husk"),
        labelWidth: 100,
        state: "true" == _w_posse()._w_trophy(),
        onSwitchChange: function(event, state) {
            _w_posse()._w_despot(state);
        }
    });
    $("#dyLoadSize").prop("value", _w_posse()._w_cachet()).on("change", (function() {
        let _w_gadget = $(this).prop("value");
        _w_posse()._w_torso(_w_gadget);
        $(this).prop("value", _w_posse()._w_cachet());
    }));
    $("#extMaxLoad").prop("value", _w_posse()._w_herald()).on("change", (function() {
        let _w_lurch = $(this).prop("value");
        _w_posse()._w_fawn(_w_lurch);
        $(this).prop("value", _w_posse()._w_herald());
    }));
    let _w_vista = _w_posse()._w_whim();
    let $_w_frolic = $("#extFormatChangeWhenDownOptContainer");
    let $_w_issue = $("<div />", {
        class: "btn-toolbar",
        role: "toolbar"
    });
    for (let key in _w_vista) {
        let $_w_asthma = $("<div />", {
            class: "btn btn-default btn-md formatOption " + (_w_vista[key] ? "btn-pwd active" : ""),
            value: key,
            text: key == "_" ? _w_acme("_w_farce") : "*." + key
        });
        $_w_issue.append($_w_asthma);
    }
    $_w_frolic.append($_w_issue);
    $(".formatOption").on("click", (function() {
        const _w_rhyme = $(this).attr("value");
        let _w_sonata = false;
        if ($(this).is(".active")) {
            _w_sonata = false;
            $(this).removeClass("btn-pwd active");
        } else {
            _w_sonata = true;
            $(this).addClass("btn-pwd active");
        }
        _w_vista = _w_posse()._w_whim();
        _w_vista[_w_rhyme] = _w_sonata;
        _w_posse()._w_toady(_w_vista);
        console.log(_w_vista);
    }));
    $("#regexpUrlRule").html(_w_posse()._w_piazza()).on("blur", (function() {
        let _w_saddle = $(this).text();
        let _w_foul = _w_posse()._w_roe(_w_saddle);
        let _w_harp = _w_posse()._w_piazza();
        for (let idx in _w_foul) {
            _w_harp = _w_harp.replace(_w_foul[idx], "<crule>" + _w_foul[idx] + "</crule>");
        }
        $(this).html(_w_harp);
    })).trigger("blur");
    $("#resetRegexpUrlRule").on("click", (function() {
        $("#regexpUrlRule").html("").trigger("blur");
    }));
    $("#regexpUrlTestInput").on("blur", (function() {
        let $_w_fascia = $("#regexpUrlTestOutput");
        $_w_fascia.html("");
        $("#regexpUrlRule").trigger("blur");
        let _w_omelet = $(this).prop("value");
        if (!_w_omelet || _w_omelet.trim("").length == 0) {
            $_w_fascia.html("");
            return;
        } else if (!_w_omelet.startsWith("http://") && !_w_omelet.startsWith("https://")) {
            $_w_fascia.html(_w_acme("_w_helve"));
            return;
        }
        let _w_quarry = _w_posse()._w_credo(_w_omelet);
        if (!Array.isArray(_w_quarry) || _w_quarry.length == 0) {
            $_w_fascia.html(_w_acme("_w_sonnet"));
            return;
        } else {
            _w_quarry.forEach((function(matchedRule) {
                $("#regexpUrlRule").html($("#regexpUrlRule").html().replace(matchedRule, "<mrule>" + matchedRule + "</mrule>"));
            }));
        }
        let _w_dirge = _w_posse()._w_flail(_w_omelet, 1);
        $_w_fascia.append(_w_acme("_w_slab") + _w_omelet + "\n");
        let $_w_draft = $("<span />", {
            text: _w_acme("_w_creek")
        });
        $_w_fascia.append($_w_draft).append("\n");
        let _w_comity = new Image;
        _w_comity.onload = function() {
            $_w_draft.append(this.width + " x " + this.height);
        };
        _w_comity.onerror = function() {
            _w_comity.onerror = null;
            $_w_draft.append("N/A");
        };
        _w_comity.src = _w_omelet;
        _w_dirge.forEach((function(matchedUrl) {
            console.log(matchedUrl);
            $_w_fascia.append("<hr />" + _w_acme("_w_gander") + matchedUrl + "\n");
            let $_w_locale = $("<span />", {
                text: _w_acme("_w_levy")
            });
            $_w_fascia.append($_w_locale).append("\n");
            let _w_browse = new Image;
            _w_browse.onload = function() {
                $_w_locale.append(this.width + " x " + this.height);
            };
            _w_browse.onerror = function() {
                _w_browse.onerror = null;
                $_w_locale.append("N/A");
            };
            _w_browse.src = matchedUrl;
        }));
    }));
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
    if (localStorage["version"] && localStorage["version"] > chrome.runtime.getManifest().version) {
        $("#newVersion").show();
    }
    let _w_dither = _w_amulet("showMsg");
    if (_w_dither != null && _w_dither == "about") {
        $("#aboutExt").slideDown().siblings().slideUp();
        let $_w_duel = $(".sidbarItem[contentId=aboutExt]").parent();
        $_w_duel.addClass("btn-pwd active");
    } else {
        $("#extOption").slideDown().siblings().slideUp();
        let $_w_duel = $(".sidbarItem[contentId=extOption]").parent();
        $_w_duel.addClass("btn-pwd active");
    }
    let _w_loon = _w_posse()._w_eaglet();
    $("#defaultFunnelWidth").val(_w_loon.width);
    $("#defaultFunnelHeight").val(_w_loon.height);
    $(".defaultFunnelSize").on("input", (function() {
        _w_loon.width = $("#defaultFunnelWidth").val();
        _w_loon.height = $("#defaultFunnelHeight").val();
        _w_posse()._w_loop(_w_loon.width, _w_loon.height);
    }));
    let _w_ploy = _w_posse()._w_homage();
    let _w_blight = _w_posse()._w_choir();
    _w_maxim(_w_ploy, _w_blight);
    $("#extClickActOptTle").html(_w_acme("_w_grit"));
    $("#extClickActOptDesc").html(_w_acme("_w_spate"));
    $("#dyLoadSizeOptTle").html(_w_acme("_w_realm"));
    $("#i18n_dynamic_load_desc").html(_w_acme("_w_curb"));
    $("#i18n_dynamic_load_desc_1").html(_w_acme("_w_graft"));
    $("#extMaxLoadOptTle").html(_w_acme("_w_slice"));
    $("#i18n_selector_page_max_load_image_desc").html(_w_acme("_w_glance"));
    $("#regexpUrlRuleOptTle").html(_w_acme("_w_epoch"));
    $("#i18n_image_url_regexp_replace_expression_desc").html(_w_acme("_w_flak"));
    $("#i18n_btn_default_rule").html(_w_acme("_w_lint"));
    $("#i18n_test_url").html(_w_acme("_w_brink"));
    $("#extFormatChangeWhenDownOptTle").html(_w_acme("_w_balk"));
    $("#i18n_selector_ext_format_change_when_down").html(_w_acme("_w_rhyme"));
}));

function _w_maxim(_w_ploy, _w_blight) {
    let _w_ditch = [];
    let _w_batch = [];
    function preDealContainer(_w_ploy, _w_batch, _w_ditch) {
        for (let i = 0; i < _w_ploy.length; ++i) {
            let _w_billow = _w_ploy[i];
            let _w_levee = _w_ploy[_w_billow];
            let _w_renown = _w_levee.width - _w_levee.height > 0 ? _w_levee.width : _w_levee.height;
            let _w_jest = _w_levee.width - _w_levee.height < 0 ? _w_levee.width : _w_levee.height;
            let _w_lot = _w_renown + "x" + _w_jest;
            _w_renown && _w_jest && _w_ditch.indexOf(_w_lot) == -1 && _w_batch.indexOf(_w_lot) == -1 && _w_batch.push(_w_lot);
        }
    }
    preDealContainer(_w_blight, _w_ditch, _w_ditch);
    preDealContainer(_w_ploy, _w_batch, _w_ditch);
    _w_ditch = _w_quiver(_w_ditch);
    _w_batch = _w_quiver(_w_batch);
    function htmlContainerFill(_w_ploy, _w_grove, $_w_duct) {
        for (let i = 0; i < _w_grove.length; ++i) {
            let $_w_spoor = $("<div />", {
                class: "btn-group btn-group-sm"
            });
            let _w_billow = _w_grove[_w_grove[i]];
            let _w_cinder = _w_billow.width + "x" + _w_billow.height;
            let $_w_tenet = $("<div />", {
                class: "btn btn-default sizeItemOption",
                value: _w_cinder,
                text: _w_cinder
            });
            _w_ploy.indexOf(_w_cinder) > -1 && $_w_tenet.addClass("btn-pwd active");
            let _w_rung = _w_billow.height + "x" + _w_billow.width;
            let $_w_yacht = $("<div />", {
                class: "btn btn-default sizeItemOption",
                value: _w_rung,
                text: _w_rung
            });
            _w_ploy.indexOf(_w_rung) > -1 && $_w_yacht.addClass("btn-pwd active");
            $_w_spoor.append($_w_tenet);
            _w_billow.width - _w_billow.height != 0 && $_w_spoor.append($_w_yacht);
            $_w_duct.append($_w_spoor);
        }
    }
    let $_w_fallow = $("#sizeConfigure");
    let $_w_bower = $("#sizeConfigurExt");
    htmlContainerFill(_w_ploy, _w_ditch, $_w_fallow);
    htmlContainerFill(_w_ploy, _w_batch, $_w_bower);
    $("#selectAllButton").on("click", (function() {
        $("#sizeConfigure .sizeItemOption").each((function() {
            let _w_hamper = $(this).attr("value").split("x");
            _w_hamper[0] = parseInt(_w_hamper[0]);
            _w_hamper[1] = parseInt(_w_hamper[1]);
            $(this).addClass("btn-pwd active");
            let _w_duel = _w_ploy[$(this).attr("value")];
            _w_posse()._w_cougar(_w_hamper[0], _w_hamper[1]);
        }));
    }));
    $("#selectNoneButton").on("click", (function() {
        $("#sizeConfigure .sizeItemOption").each((function() {
            let _w_hamper = $(this).attr("value").split("x");
            _w_hamper[0] = parseInt(_w_hamper[0]);
            _w_hamper[1] = parseInt(_w_hamper[1]);
            $(this).removeClass("btn-pwd active");
            _w_posse()._w_limbo(_w_hamper[0], _w_hamper[1]);
        }));
    }));
    $(".sizeItemOption").on("click", (function() {
        let _w_hamper = $(this).attr("value").split("x");
        _w_hamper[0] = parseInt(_w_hamper[0]);
        _w_hamper[1] = parseInt(_w_hamper[1]);
        if ($(this).is(".active")) {
            $(this).removeClass("btn-pwd active");
            _w_posse()._w_limbo(_w_hamper[0], _w_hamper[1]);
        } else {
            $(this).addClass("btn-pwd active");
            let _w_duel = _w_ploy[$(this).attr("value")];
            _w_posse()._w_cougar(_w_hamper[0], _w_hamper[1]);
        }
    }));
    $("#newItemButton").on("click", (function() {
        let _w_fret = parseInt($("#newItemWidth").prop("value"));
        let _w_groove = parseInt($("#newItemHeight").prop("value"));
        _w_fret && _w_groove && _w_fret > 0 && _w_groove > 0 && _w_posse()._w_cougar(_w_fret, _w_groove);
        window.location.reload();
    }));
    $(".sidbarItem").on("click", (function() {
        $(this).parent().addClass("btn-pwd active").siblings().removeClass("btn-pwd active");
        $("#" + $(this).attr("contentId")).slideDown().siblings().slideUp();
    }));
}