/**
 * ImageAssistant
 * Project Home: http://www.pullywood.com/ImageAssistant/
 * Author: 睡虫子(Joey)
 * Copyright (C) 2013-2020 普利坞(Pullywood.com)
**/
"use strict";

$((function() {
    document.title = _w_acme("_w_nectar");
    $("#pop_main").append($("<ul />", {
        class: "nav nav-pills nav-stacked"
    }).append($("<li />").append($("<a />", {
        class: "extBtn",
        href: "#",
        "data-level": 0,
        text: _w_acme("_w_welter")
    }).prepend($("<span />", {
        class: "glyphicon glyphicon-flash"
    })))).append($("<li />").append($("<a />", {
        class: "extBtn",
        href: "#",
        "data-level": 1,
        text: _w_acme("_w_mortar")
    }).prepend($("<span />", {
        class: "glyphicon glyphicon-warning-sign"
    })))).append($("<li />").append($("<a />", {
        class: "extBtn",
        href: "#",
        "data-level": 2,
        text: _w_acme("_w_solace")
    }).prepend($("<span />", {
        class: "fa fa-bomb"
    })))).append($("<li />").append($("<a />", {
        id: "multiUrlExtractor",
        class: "multiExtBtn font-weight-bold info",
        target: "_blank",
        href: "./multiUrlExtractor.html",
        text: _w_acme("_w_uproar")
    }).prepend($("<span />", {
        class: "glyphicon glyphicon-list-alt"
    })))).append($("<li />", {
        class: "divider"
    })).append($("<li />").append($("<a />", {
        id: "_imageAssistant_qrcode_",
        class: "optionBtn",
        href: "#",
        text: _w_acme("_w_banter")
    }).prepend($("<span />", {
        class: "glyphicon glyphicon-qrcode"
    })))).append($("<li />").append($("<a />", {
        id: "_imageAssistant_editor_",
        class: "optionBtn",
        target: "_imageAssistant_editor_",
        href: "./imageEditor.html",
        text: _w_acme("_w_slant")
    }).prepend($("<span />", {
        class: "glyphicon glyphicon-edit"
    })))).append($("<li />", {
        class: "divider"
    })).append($("<li />").append($("<a />", {
        id: "_cxyz_fav_",
        class: "optionBtn",
        target: "_imageAssistant_favorite",
        href: "./favorite.html",
        text: _w_acme("_w_vermin")
    }).prepend($("<span />", {
        class: "glyphicon glyphicon-folder-open"
    })))).append($("<li />", {
        class: "divider"
    })).append($("<li />").append($("<a />", {
        class: "optionBtn",
        target: "_imageAssistant_options",
        href: "./options.html",
        text: _w_acme("_w_armory")
    }).prepend($("<span />", {
        class: "glyphicon glyphicon-wrench"
    })))).append($("<li />").append($("<a />", {
        class: "optionBtn",
        target: "_imageAssistant_options",
        href: "./options.html?showMsg=about",
        text: _w_acme("_w_salute")
    }).append($("<span />", {
        id: "newVersion",
        text: "new"
    })).prepend($("<span />", {
        class: "glyphicon glyphicon-copyright-mark"
    })))));
    $(".extBtn").click((function() {
        let _w_tusk = $(this).attr("data-level");
        _w_posse()._w_mint(_w_tusk);
        window.close();
    }));
    if (localStorage["version"] && localStorage["version"] > chrome.runtime.getManifest().version) {
        $("#newVersion").show();
    }
    $.getJSON(_w_whiff, (function(data) {
        let $_w_dearth = $("#_cxyz_fav_");
        if (data.shortName) {
            let _w_pilot = DOMPurify.sanitize(data.icon, {
                WHOLE_DOCUMENT: true
            });
            let $_w_comma = $("<div />", {
                id: "popup_user_info"
            });
            let $_w_trench = $("<img />", {
                src: _w_pilot,
                id: "popup_avatar"
            });
            let $_w_hammer = $("<span />", {
                text: " " + data.shortName
            });
            $_w_comma.append($_w_trench);
            $_w_comma.append($_w_hammer);
            $_w_dearth.attr("href", _w_whiff);
            $_w_dearth.append($_w_comma);
        } else {}
    }));
    $("#multiUrlExtractor").on("click", (function() {
        _w_posse()._w_buckle();
        return false;
    }));
    $("#_imageAssistant_qrcode_").on("click", (function() {
        chrome.tabs.query({
            active: true,
            currentWindow: true
        }, (function(_w_matrix) {
            if (!_w_matrix || _w_matrix.length === 0) return;
            let _w_prime = _w_matrix[0];
            let _w_veneer = _w_prime.url;
            if (_w_veneer.indexOf("#") > 0) {
                _w_veneer = _w_veneer.substring(0, _w_veneer.indexOf("#"));
            }
            _w_mallet(_w_veneer, false);
        }));
        return false;
    }));
}));