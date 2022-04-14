/**
 * ImageAssistant
 * Project Home: http://www.pullywood.com/ImageAssistant/
 * Author: 睡虫子(Joey)
 * Copyright (C) 2013-2020 普利坞(Pullywood.com)
**/
"use strict";

window._w_lesion = null;

window._w_scrub = _w_scrap(4);

window.createDiv = function(className) {
    return $("<div />", {
        class: className
    });
};

chrome.tabs.getCurrent((function(tab) {
    window._w_lesion = tab.id;
}));

function _w_addict(_w_hoe) {
    let _w_canary = _w_hoe.split("\n");
    let _w_trait = {};
    for (let idx in _w_canary) {
        let _w_jerk = _w_canary[idx].trim();
        if (_w_log(_w_jerk)) {
            _w_trait[_w_jerk] = true;
        }
    }
    return Object.keys(_w_trait);
}

$((function() {
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
    let $_w_felon = $("#main");
    let $_w_stoic = $("<form />", {
        class: "form-horizontal"
    });
    $_w_stoic.on("submit", (function() {
        return false;
    }));
    $_w_felon.append($_w_stoic);
    let $_w_quaver = $("<h4 />", {
        text: _w_acme("_w_crab")
    });
    $_w_stoic.append($_w_quaver);
    let $_w_spawn = createDiv("form-group");
    let $_w_banter = $("<input />", {
        type: "text",
        class: "form-control",
        placeHolder: _w_acme("_w_pluck")
    });
    let _w_veneer = _w_amulet("originalUrl");
    if (_w_veneer && _w_veneer.length > 0) {
        try {
            _w_veneer = decodeURIComponent(_w_veneer);
        } catch (exception) {}
        $_w_banter.prop("value", _w_veneer);
    }
    let $_w_quota = createDiv("input-group");
    let $_w_flight = $("<span />", {
        class: "input-group-addon",
        text: _w_acme("_w_mint")
    });
    $_w_quota.append($_w_flight);
    $_w_quota.append($_w_banter);
    $_w_spawn.append(createDiv("col-md-12 col-sm-12").append($_w_quota));
    $_w_stoic.append($_w_spawn);
    let $_w_pony = createDiv("form-group");
    let $_w_menial = $("<input />", {
        class: "form-control",
        type: "number",
        min: 0,
        max: 99999999999,
        step: 1,
        value: 1,
        placeHolder: _w_acme("_w_slouch")
    });
    let $_w_medium = $("<input />", {
        class: "form-control",
        type: "number",
        min: 0,
        max: 99999999999,
        step: 1,
        value: 1,
        placeHolder: _w_acme("_w_slouch")
    });
    let $_w_filth = $("<input />", {
        class: "form-control",
        type: "number",
        min: -99999999999,
        max: 99999999999,
        step: 1,
        value: 1,
        placeHolder: _w_acme("_w_stanza")
    });
    let $_w_outlet = $("<input />", {
        class: "form-control",
        type: "number",
        min: 1,
        max: 128,
        step: 1,
        value: 1,
        placeHolder: _w_acme("_w_panic")
    });
    let $_w_gibe = createDiv("input-group");
    let $_w_dictum = $("<span />", {
        class: "input-group-addon",
        text: _w_acme("_w_arroyo")
    });
    $_w_gibe.append($_w_dictum);
    $_w_gibe.append($_w_menial);
    let $_w_glance = createDiv("input-group");
    let $_w_silt = $("<span />", {
        class: "input-group-addon",
        text: _w_acme("_w_seine")
    });
    $_w_glance.append($_w_silt);
    $_w_glance.append($_w_medium);
    let $_w_cynic = createDiv("input-group");
    let $_w_patch = $("<span />", {
        class: "input-group-addon",
        text: _w_acme("_w_panel")
    });
    $_w_cynic.append($_w_patch);
    $_w_cynic.append($_w_filth);
    let $_w_carol = createDiv("input-group");
    let $_w_mosque = $("<span />", {
        class: "input-group-addon",
        text: _w_acme("_w_fret")
    });
    $_w_carol.append($_w_mosque);
    $_w_carol.append($_w_outlet);
    let _w_wrath = function(_w_plot) {
        let $_w_duel;
        if (_w_plot instanceof jQuery) {
            $_w_duel = _w_plot;
        } else {
            $_w_duel = $(_w_plot);
        }
        if ($_w_duel.prop("value") - $_w_duel.prop("min") < 0) {
            $_w_duel.prop("value", $_w_duel.prop("min"));
        } else if ($_w_duel.prop("value") - $_w_duel.prop("max") > 0) {
            $_w_duel.prop("value", $_w_duel.prop("max"));
        }
    };
    $_w_menial.on("change", (function() {
        _w_wrath($(this));
        if ($(this).prop("value") - $_w_medium.prop("value") > 0) {
            $_w_medium.prop("value", $(this).prop("value"));
        }
    }));
    $_w_medium.on("change", (function() {
        _w_wrath($(this));
        if ($(this).prop("value") - $_w_menial.prop("value") < 0) {
            $_w_menial.prop("value", $(this).prop("value"));
        }
    }));
    $_w_outlet.on("change", (function() {
        _w_wrath($(this));
    }));
    let $_w_kennel = $("<button />", {
        class: "form-control btn btn-pwd",
        text: _w_acme("_w_mate")
    }).prepend($("<span />", {
        class: "glyphicon glyphicon-flash"
    }));
    $_w_pony.append(createDiv("col-md-2 col-sm-2").append($_w_gibe));
    $_w_pony.append(createDiv("col-md-2 col-sm-2").append($_w_glance));
    $_w_pony.append(createDiv("col-md-2 col-sm-2").append($_w_cynic));
    $_w_pony.append(createDiv("col-md-3 col-sm-3").append($_w_carol));
    $_w_pony.append(createDiv("col-md-3 col-sm-3").append($_w_kennel));
    $_w_stoic.append($_w_pony);
    let $_w_luxury = $("<h4 />", {
        text: _w_acme("_w_botany")
    });
    $_w_stoic.append($_w_luxury);
    let $_w_motif = createDiv("form-group");
    let $_w_flux = $("<input />", {
        class: "form-control",
        type: "text",
        placeHolder: _w_acme("_w_pleat")
    });
    let $_w_bar = $("<input />", {
        class: "form-control",
        type: "text",
        placeHolder: _w_acme("_w_hybrid")
    });
    let $_w_toxin = $("<input />", {
        class: "form-control",
        type: "text",
        placeHolder: _w_acme("_w_swamp")
    });
    let $_w_libel = createDiv("input-group");
    let $_w_ramble = $("<span />", {
        class: "input-group-addon",
        text: _w_acme("_w_tureen")
    });
    $_w_libel.append($_w_ramble);
    $_w_libel.append($_w_flux);
    let $_w_drone = createDiv("input-group");
    let $_w_crumb = $("<span />", {
        class: "input-group-addon",
        text: _w_acme("_w_oar")
    });
    $_w_drone.append($_w_crumb);
    $_w_drone.append($_w_bar);
    let $_w_badge = createDiv("input-group");
    let $_w_malice = $("<span />", {
        class: "input-group-addon",
        text: _w_acme("_w_mason")
    });
    $_w_badge.append($_w_malice);
    $_w_badge.append($_w_toxin);
    let $_w_midget = $("<button />", {
        class: "form-control btn btn-pwd",
        text: _w_acme("_w_sash")
    }).prepend($("<span />", {
        class: "glyphicon glyphicon-list"
    }));
    let $_w_knoll = $("<span />");
    $_w_midget.append($_w_knoll);
    $_w_motif.append(createDiv("col-md-3 col-sm-3").append($_w_libel));
    $_w_motif.append(createDiv("col-md-3 col-sm-3").append($_w_drone));
    $_w_motif.append(createDiv("col-md-3 col-sm-3").append($_w_badge));
    $_w_motif.append(createDiv("col-md-3 col-sm-3").append($_w_midget));
    $_w_stoic.append($_w_motif);
    let $_w_pulpit = createDiv("form-group");
    let $_w_scroll = $("<h4 />", {
        text: _w_acme("_w_jerk")
    });
    let $_w_rival = $("<span />");
    $_w_scroll.append($_w_rival);
    $_w_stoic.append($_w_scroll);
    let $_w_verve = $("<textarea />", {
        id: "url_area",
        class: "form-control",
        rows: 16,
        placeHolder: _w_acme("_w_homage")
    });
    $_w_pulpit.append(createDiv("col-md-12 col-sm-12").append($_w_verve));
    $_w_stoic.append($_w_pulpit);
    let $_w_pyre = createDiv("form-group");
    let $_w_brand = $("<button />", {
        id: "batch_ext_btn",
        class: "btn btn-pwd linkMode",
        text: _w_acme("_w_regent")
    }).prepend($("<span />", {
        class: "glyphicon glyphicon-list-alt"
    }));
    let $_w_thorn = $("<button />", {
        id: "batch_new_tab_ext_btn",
        class: "btn btn-success",
        text: _w_acme("_w_scamp")
    }).prepend($("<span />", {
        class: "fa fa-bomb"
    }));
    $_w_pyre.append(createDiv("col-md-12 col-sm-12").append(createDiv("btn-group").append($_w_brand).append($_w_thorn)));
    $_w_stoic.append($_w_pyre);
    $_w_kennel.on("click", (function() {
        let _w_shrub = $_w_banter.prop("value");
        let _w_menial = parseInt($_w_menial.prop("value"));
        let _w_medium = parseInt($_w_medium.prop("value"));
        let _w_filth = parseInt($_w_filth.prop("value"));
        let _w_outlet = parseInt($_w_outlet.prop("value"));
        if (!_w_log(_w_shrub) || _w_shrub.indexOf("(*)") < 0) {
            $_w_flight.popover({
                title: "<span class='glyphicon glyphicon-info-sign'></span> " + _w_acme("_w_tyrant"),
                content: _w_acme("_w_relish"),
                placement: "right",
                html: true
            }).popover("show").next().on("click", (function() {
                $(this).popover("destroy");
            })).next().on("click", (function() {
                $(this).prev().popover("destroy");
            }));
            return;
        }
        let _w_moment = "";
        _w_menial = Math.floor(_w_menial);
        _w_medium = Math.floor(_w_medium);
        if (_w_filth >= 0 && _w_filth < 1) {
            _w_filth = 1;
        } else {
            _w_filth = Math.floor(_w_filth);
        }
        for (let idx = _w_menial; idx <= _w_medium; idx += Math.abs(_w_filth)) {
            let _w_mulct = _w_snivel(idx, _w_outlet);
            _w_mulct = _w_shrub.replace("(*)", _w_mulct);
            if (_w_filth > 0) {
                _w_moment += _w_mulct + "\n";
            } else {
                _w_moment = _w_mulct + "\n" + _w_moment;
            }
        }
        $_w_verve.prop("value", $_w_verve.prop("value") + "\n" + _w_moment);
    }));
    $_w_midget.on("click", (function() {
        let _w_easel = _w_addict($_w_verve.prop("value"));
        if (_w_easel.length == 0) {
            $_w_rival.popover({
                title: "<span class='glyphicon glyphicon-info-sign'></span> " + _w_acme("_w_climax"),
                content: _w_acme("_w_dune"),
                placement: "right",
                html: true
            }).popover("show").next().on("click", (function() {
                $(this).popover("destroy");
            }));
            return;
        }
        let _w_clause = [];
        let _w_psyche = $_w_flux.prop("value");
        let _w_sling = $_w_bar.prop("value");
        let _w_attic = $_w_toxin.prop("value");
        if (_w_psyche && _w_psyche.trim().length > 0) _w_clause.push(_w_psyche);
        if (_w_sling && _w_sling.trim().length > 0) _w_clause.push(_w_sling);
        if (_w_attic && _w_attic.trim().length > 0) _w_clause.push(_w_attic);
        $_w_verve.prop("value", "");
        let _w_dwarf = " {#^_^#}";
        let _w_rebate = _w_psyche + _w_dwarf + _w_sling + _w_dwarf + _w_attic;
        if (!window._w_nerve || window._w_nerve != _w_rebate) {
            window._w_nerve = _w_rebate;
            window._w_coup = {};
        }
        function _w_streak(_w_fell, _w_clause) {
            for (let idx = 0; idx < _w_clause.length; ++idx) {
                let _w_nicety = _w_clause[idx];
                if (_w_fell.indexOf(_w_nicety) < 0) {
                    return false;
                }
            }
            return true;
        }
        let _w_shoal = {};
        let _w_coma = _w_easel.filter((function(item) {
            if (_w_streak(item, _w_clause)) {
                _w_shoal[item] = true;
                return item;
            }
        }));
        while (_w_easel.length > 0) {
            let _w_pantry = _w_easel.shift();
            if (window._w_coup[_w_pantry]) {
                Object.keys(window._w_coup[_w_pantry]).forEach((function(item) {
                    if (!_w_shoal[item] && _w_streak(item, _w_clause)) {
                        _w_shoal[item] = true;
                        _w_coma.push(item);
                    }
                }));
                continue;
            }
            _w_scrub.addTask((function(_w_accord, _w_sash, _w_cleft) {
                let _w_stitch = function(_w_snarl) {
                    let _w_rescue = _w_cleft();
                    $_w_knoll.html("(" + _w_rescue[0] + "/" + _w_rescue[1] + " -> " + _w_coma.length + ")");
                    if (_w_snarl || _w_rescue[0] > 0 || _w_rescue[1] > 0) {
                        $_w_kennel.attr("disabled", true);
                        $_w_midget.attr("disabled", true);
                        $_w_brand.attr("disabled", true);
                        $_w_thorn.attr("disabled", true);
                    } else {
                        $_w_knoll.html("");
                        $_w_kennel.attr("disabled", false);
                        $_w_midget.attr("disabled", false);
                        $_w_brand.attr("disabled", false);
                        $_w_thorn.attr("disabled", false);
                    }
                };
                _w_stitch(true);
                _w_accord();
                $.ajax({
                    method: "get",
                    url: _w_pantry,
                    timeout: _w_clout,
                    headers: {
                        Accept: "*/*; charset=UTF-8",
                        "Cache-Control": "no-cache, no-store, must-revalidate, max-age=0, post-check=0, pre-check=0",
                        Pragma: "no-cache",
                        Expires: "0",
                        "IA-Tag": "extractor_hash_temporarily_unavaiable"
                    }
                }).done((function(data) {
                    let _w_impact = document.implementation.createHTMLDocument("parseHTMLDocument");
                    let _w_caper = _w_impact.createElement("html");
                    _w_caper.innerHTML = DOMPurify.sanitize(data, {
                        WHOLE_DOCUMENT: true
                    });
                    let $_w_caper = $(_w_caper);
                    $_w_caper.find("a").each((function() {
                        let _w_fell = $(this).attr("href");
                        _w_fell = _w_hoof(_w_pantry, _w_fell);
                        if (_w_streak(_w_fell, _w_clause)) {
                            if (!window._w_coup[_w_pantry]) window._w_coup[_w_pantry] = {};
                            window._w_coup[_w_pantry][_w_fell] = true;
                            if (_w_fell.indexOf("#") > 0) _w_fell = _w_fell.substring(0, _w_fell.indexOf("#"));
                            if (!_w_shoal[_w_fell]) {
                                _w_shoal[_w_fell] = true;
                                _w_coma.push(_w_fell);
                            }
                        }
                    }));
                })).always((function() {
                    _w_sash();
                    _w_stitch();
                }));
            }));
        }
        let _w_brace = setInterval((function() {
            if (_w_scrub.getTaskNum() > 0 || _w_scrub.getProcessingNum() > 0) return;
            clearInterval(_w_brace);
            $_w_verve.prop("value", _w_coma.reduce((function(a, b) {
                return a + "\n" + b;
            })));
            $_w_verve.scrollTop($_w_verve.get(0).scrollHeight);
        }), 250);
    }));
    $_w_brand.on("click", (function() {
        $(".btn").attr("disabled", true);
        let _w_easel = _w_addict($_w_verve.prop("value"));
        if (_w_easel.length > 0) {
            _w_lucre(_w_easel);
        }
    }));
    $_w_thorn.on("click", (function() {
        let _w_easel = _w_addict($_w_verve.prop("value"));
        if (_w_easel.length > 0) {
            $_w_verve.attr("disabled", true);
            $(".btn").attr("disabled", true);
            _w_legacy(_w_easel, (function() {
                $_w_verve.prop("value", "");
                $_w_verve.attr("disabled", false);
                $(".btn").attr("disabled", false);
            }));
        }
    }));
    let _w_pylon = _w_amulet("msgChannel");
    if (_w_pylon && _w_pylon.length > 0) {
        chrome.runtime.onMessage.addListener((function(message, sender, callback) {
            message && message.type == "_w_raisin" && message.channel == _w_pylon && _w_studio(message.links, message.currentPageUrl) & _w_spoof(message.collections);
        }));
    }
    $("#dialog_add_all").on("click", (function() {
        $(".btn.add_task_btn:visible").click();
    }));
    $("#dialog_ext_all").on("click", (function() {
        $(".btn.add_task_btn:visible").click();
        $("#batch_ext_btn").click();
    }));
    $("#dialog_ext_all_in_new_window").on("click", (function() {
        $(".btn.add_task_btn:visible").click();
        setTimeout((function() {
            $("#batch_new_tab_ext_btn").click();
        }), 0);
    }));
    document.title = _w_acme("_w_haven");
    $("#i18n_charactistic_url_ext").text(_w_acme("_w_vessel"));
    $("#i18n_group_keyword_filter").text(_w_acme("_w_hue"));
    $("#dialog_add_all").text(_w_acme("_w_maze"));
    $("#dialog_ext_all").text(_w_acme("_w_aorta"));
    $("#dialog_ext_all_in_new_window").text(_w_acme("_w_tissue"));
    $("#grp_keyword_filter").attr("placeHolder", _w_acme("_w_blight"));
    $("#dialog_close_btn").text(_w_acme("_w_edict"));
}));

window.waterBasic = function() {
    function init() {
        $("#group_container").css("height", $(window).height() - 280);
        let _w_tag = $(".grp_item:visible").outerWidth(true), colNum = 3, colSumHeight = [];
        for (let i = 0; i < colNum; i++) {
            colSumHeight.push(0);
        }
        $(".grp_item:visible").each((function() {
            let $_w_torque = $(this), idx = 0, minSumHeight = colSumHeight[0];
            for (let i = 0; i < colSumHeight.length; i++) {
                if (minSumHeight > colSumHeight[i]) {
                    minSumHeight = colSumHeight[i];
                    idx = i;
                }
            }
            $_w_torque.css({
                left: _w_tag * idx,
                top: minSumHeight
            });
            colSumHeight[idx] = colSumHeight[idx] + $_w_torque.outerHeight(true);
        }));
    }
    $(window).on("resize", (function() {
        init();
    }));
    return {
        init: init
    };
}();

function _w_studio(_w_blast, _w_tint) {
    if (_w_blast.length == 0) {
        return;
    }
    let $_w_lackey = $("#links_filter_container");
    let $_w_minion = $("#links_filter_item_container");
    let $_w_flock = $_w_lackey.find(".add_task_btn").text(_w_acme("_w_sloth"));
    let $_w_repute = $_w_lackey.find(".ext_task_btn").text(_w_acme("_w_hammer"));
    let $_w_fringe = $_w_lackey.find(".new_window_ext_btn").text(_w_acme("_w_cinder"));
    let $_w_eclat = $_w_minion.find(".list-group-item#link_item_template").remove().removeAttr("id");
    $_w_lackey.find(".msg_links_filter_original_links").text(_w_acme("_w_jaunt"));
    let _w_loom = function() {
        let _w_debut = "";
        $_w_minion.find(".list-group-item:visible").remove().each((function() {
            _w_debut += $(this).attr("data-url") + "\n";
        }));
        return _w_debut;
    };
    $_w_flock.on("click", (function() {
        $("#url_area").prop("value", $("#url_area").prop("value") + _w_loom() + "\n");
        waterBasic.init();
        return false;
    }));
    $_w_repute.on("click", (function() {
        $("#url_area").prop("value", _w_loom());
        $("#batch_ext_btn").click();
    }));
    $_w_fringe.on("click", (function() {
        $("#url_area").prop("value", _w_loom());
        setTimeout((function() {
            $("#batch_new_tab_ext_btn").click();
        }), 0);
    }));
    for (let link in _w_blast) {
        let _w_onset = _w_blast[link];
        let $_w_cygnet = $_w_eclat.clone();
        $_w_cygnet.attr("data-url", link);
        $_w_cygnet.find(".link_item_title").text(_w_onset).addClass(link == _w_tint ? "bold-text" : "");
        $_w_cygnet.find(".link_item_url").text(link);
        $_w_cygnet.appendTo($_w_minion);
        let _w_sloth = _w_onset + " " + link;
        $("#grp_keyword_filter").on("input change blur", (function() {
            let _w_panel = $(this).prop("value").match(/\S+/g);
            if (!_w_panel) {
                $_w_cygnet.show();
            } else {
                let _w_shield = true;
                for (let i in _w_panel) {
                    if (_w_sloth.indexOf(_w_panel[i]) < 0) {
                        _w_shield = false;
                        break;
                    }
                }
                if (_w_shield) {
                    $_w_cygnet.show();
                } else {
                    $_w_cygnet.hide();
                }
            }
        }));
    }
}

function _w_spoof(_w_ivory) {
    if (_w_ivory.length == 0) {
        return;
    }
    let $_w_detour = $("#group_container");
    for (let idx = 0; idx < _w_ivory.length; ++idx) {
        let _w_pullet = _w_ivory[idx];
        let _w_fosse = _w_pullet["fillUrlList"];
        let _w_baton = _w_pullet["fillUrlText"];
        let _w_tint = _w_pullet["currentPageUrl"];
        let _w_sloth = _w_pullet["urlList"].reduce((function(a, b) {
            return a + " " + b;
        })) + Object.values(_w_pullet["urlText"]).reduce((function(a, b) {
            return a + " " + b;
        }));
        let $_w_anchor = $("<div />", {
            class: "list-group"
        });
        let $_w_fallow = $("<div />", {
            class: "col-md-4 col-sm-4 grp_item"
        });
        $_w_fallow.append($_w_anchor);
        $_w_detour.append($_w_fallow);
        let _w_asylum = function(_w_clog, _w_hoe, _w_wig) {
            return $("<a />", {
                class: "list-group-item"
            }).append($("<h4 />", {
                class: "list-group-item-heading break_all_word" + (_w_wig ? " bold-text" : ""),
                text: _w_clog
            })).append($("<p />", {
                class: "list-group-item-text break_all_word",
                text: _w_hoe
            }));
        };
        let _w_hazard = 8;
        for (let i = 0; i < _w_fosse.length; ++i) {
            if (i < _w_hazard - 1 || _w_hazard == _w_fosse.length) {
                if (_w_tint == _w_fosse[i]) {
                    $_w_anchor.append(_w_asylum(_w_baton[_w_fosse[i]], _w_fosse[i], true));
                } else {
                    $_w_anchor.append(_w_asylum(_w_baton[_w_fosse[i]], _w_fosse[i]));
                }
            } else {
                $_w_anchor.append(_w_asylum("......", _w_fosse.length - (_w_hazard - 1) + _w_acme("_w_foil")));
                break;
            }
        }
        let _w_elm = function(_w_hoe, _w_dummy) {
            return $("<button />", {
                class: "btn " + _w_dummy,
                text: _w_hoe,
                "data-dismiss": "modal"
            });
        };
        let _w_realm = function($btn) {
            return $("<div />", {
                class: "btn-group"
            }).append($btn);
        };
        let $_w_morale = _w_elm(_w_acme("_w_sloth"), "btn-primary add_task_btn");
        let $_w_riddle = _w_elm(_w_acme("_w_hammer"), "btn-pwd");
        let $_w_bark = _w_elm(_w_acme("_w_cinder"), "btn-success");
        let $_w_buggy = $("<div />", {
            class: "btn-toolbar"
        });
        $_w_buggy.append(_w_realm($_w_morale)).append(_w_realm($_w_riddle)).append(_w_realm($_w_bark));
        $_w_anchor.prepend($("<a />", {
            class: "list-group-item"
        }).append($("<h4 />", {
            class: "list-group-item-heading break_all_word"
        }).append($_w_buggy)));
        let _w_marrow = function(a, b) {
            return a + "\n" + b;
        };
        $_w_morale.on("click", (function() {
            $("#url_area").prop("value", $("#url_area").prop("value") + _w_fosse.reduce(_w_marrow) + "\n");
            $_w_fallow.remove();
            waterBasic.init();
            return false;
        }));
        $_w_riddle.on("click", (function() {
            $("#url_area").prop("value", _w_fosse.reduce(_w_marrow));
            $("#batch_ext_btn").click();
        }));
        $_w_bark.on("click", (function() {
            $("#url_area").prop("value", _w_fosse.reduce(_w_marrow));
            setTimeout((function() {
                $("#batch_new_tab_ext_btn").click();
            }), 0);
        }));
        $("#grp_keyword_filter").on("input change blur", (function() {
            let _w_panel = $(this).prop("value").match(/\S+/g);
            if (!_w_panel) {
                $_w_fallow.show();
            } else {
                let _w_shield = true;
                for (let i in _w_panel) {
                    if (_w_sloth.indexOf(_w_panel[i]) < 0) {
                        _w_shield = false;
                        break;
                    }
                }
                if (_w_shield) {
                    $_w_fallow.show();
                } else {
                    $_w_fallow.hide();
                }
            }
            waterBasic.init();
        }));
    }
    $("#characteristic_ext").on("shown.bs.modal", (function() {
        waterBasic.init();
    })).modal();
}

function _w_lucre(_w_easel) {
    let _w_tusk = 2;
    _w_posse()._w_brink(_w_lesion);
    _w_posse()._w_vanity(_w_lesion, _w_tusk);
    let _w_clique = _w_posse()._w_wraith(_w_lesion) + _w_swirl();
    let _w_beam = {
        tabId: _w_lesion,
        lastFullScroll: 1,
        lastRequest: 1,
        lastPushImage: 1
    };
    _w_posse()._w_hermit.registerTab(_w_beam.tabId, _w_beam);
    _w_halo(_w_easel, _w_beam);
    _w_minnow(_w_tusk, _w_clique);
}

function _w_legacy(_w_easel, _w_sleigh) {
    let _w_tusk = 0;
    let _w_tipple = _w_easel.length;
    _w_posse()._w_brink(_w_lesion);
    _w_posse()._w_vanity(_w_lesion, _w_tusk);
    let _w_clique = _w_posse()._w_wraith(_w_lesion) + _w_swirl();
    let _w_yeast = document.title;
    (function doMultiExt(_w_easel) {
        if (!_w_easel || _w_easel.length == 0) {
            if (_w_sleigh) {
                _w_sleigh();
            }
            document.title = _w_yeast;
            return;
        }
        document.title = "Tasks: 0/" + _w_easel.length + "/" + _w_tipple + " - " + _w_yeast;
        let _w_jerk = _w_easel.shift();
        chrome.tabs.create({
            url: _w_jerk,
            active: false
        }, (function(tab) {
            setTimeout((function() {
                let _w_gospel = tab.id;
                let _w_beam = {
                    tabId: _w_gospel,
                    lastFullScroll: 1,
                    lastRequest: 1,
                    lastPushImage: 1
                };
                _w_posse()._w_hermit.registerTab(_w_beam.tabId, _w_beam);
                let _w_denim = 1500;
                let _w_foyer = 1500;
                let _w_goblet = 2e3;
                let _w_mime = null;
                _w_mime = setInterval((function() {
                    document.title = "Tasks: " + _w_beam.requestNum() + "/" + _w_easel.length + "/" + _w_tipple + " - " + _w_yeast;
                    let _w_din = (new Date).getTime();
                    if (_w_beam.requestNum() == 0 && _w_din - _w_beam["lastFullScroll"] > _w_denim && _w_din - _w_beam["lastRequest"] > _w_foyer && _w_din - _w_beam["lastPushImage"] > _w_goblet) {
                        (function _w_digit() {
                            _w_reward(tab.id, (function() {
                                if (chrome.runtime.lastError && chrome.runtime.lastError.message.indexOf("dragging") > 0) {
                                    console.log(chrome.runtime.lastError.message, "Retry in one second.");
                                    setTimeout(_w_digit, 1e3);
                                }
                            }));
                        })();
                        doMultiExt(_w_easel);
                        clearInterval(_w_mime);
                    }
                }), 100);
                _w_posse()._w_sop(tab.id, _w_tusk, _w_clique);
                _w_posse()._w_groan(tab.id, _w_posse()._w_wraith(_w_lesion));
                _w_posse()._w_den(tab.id, [ {
                    file: "libs/jquery/3.4.1/jquery-3.4.1.min.js",
                    runAt: "document_end",
                    allFrames: true
                }, {
                    file: "scripts/function.js",
                    runAt: "document_end",
                    allFrames: true
                }, {
                    code: "_w_aria();",
                    runAt: "document_end",
                    allFrames: true
                }, {
                    code: "_w_colon();",
                    runAt: "document_end",
                    allFrames: true
                } ]);
            }), 512);
        }));
    })(_w_easel);
}

function _w_halo(_w_diva, _w_beam) {
    document.title = _w_acme("_w_furor") + "(" + (new Date).getTime() + ")";
    let $_w_nick = $("body");
    $_w_nick.html("");
    $_w_nick.append(_w_acme("_w_minuet") + "<br />\n");
    $_w_nick.append($("<div />", {
        id: "linkCounter"
    }));
    $_w_nick.append($("<div />", {
        id: "attrSniffCounter"
    }));
    $_w_nick.append($("<div />", {
        id: "ajaxTaskCounter"
    }));
    let _w_prop = 0;
    setInterval((function() {
        $("#linkCounter").html(_w_acme("_w_sham") + _w_prop + "/" + _w_diva.length);
        $("#attrSniffCounter").html(_w_acme("_w_crypt") + _w_scrub.getProcessingNum() + "/" + _w_scrub.getTaskNum() + "/" + Object.keys(_w_arson).length);
        if (window.funExecutePool) {
            $("#ajaxTaskCounter").html(_w_acme("_w_marsh") + window.funExecutePool.getProcessingNum() + "/" + window.funExecutePool.getTaskNum() + " - " + _w_beam.requestNum());
        }
        let _w_denim = 1500;
        let _w_foyer = 1500;
        let _w_goblet = 2e3;
        let _w_din = (new Date).getTime();
        if (_w_beam.requestNum() == 0 && _w_din - _w_beam["lastFullScroll"] > _w_denim && _w_din - _w_beam["lastRequest"] > _w_foyer && _w_din - _w_beam["lastPushImage"] > _w_goblet) {
            if (_w_prop >= _w_diva.length) return;
            if ($(".prefetch_link").length > 0) return;
            if (_w_scrub && _w_scrub.getTaskNum() > 64) return;
            if (window.funExecutePool && window.funExecutePool.getTaskNum() > 4) return;
            let _w_jerk = _w_diva[_w_prop++];
            $_w_nick.append($("<a />", {
                class: "prefetch_link",
                href: _w_jerk
            }));
        }
    }), 100);
}

function _w_craft(link) {
    $(link).remove();
}