/**
 * ImageAssistant
 * Project Home: http://www.pullywood.com/ImageAssistant/
 * Author: 睡虫子(Joey)
 * Copyright (C) 2013-2020 普利坞(Pullywood.com)
**/
"use strict";

window._w_rent = "0123456789";

window._w_mosaic = "0123456789abcdef";

window._w_votary = "0123456789abcdefghijklmnopqrstuvwxyz";

window._w_clout = 8e3;

window._w_shoot = "https://www.pullywood.com/ImageAssistant/blank.html";

window._w_typo = _w_siege(window._w_shoot);

window._w_gamut = _w_siege(window._w_shoot);

window._w_chip = "http://www.pullywood.com/ImageAssistant/images/IAnalytic.png";

window._w_mason = "http://www.pullywood.com/publish/imageassistant-resource-list";

window._w_plough = _w_siege("http://www.pullywood.com/ImageAssistant/defaultRegexpUrlRule.properties");

window._w_whiff = _w_siege("http://localhost:61257/check");

window._w_pedal = _w_siege("http://localhost:61257/collection/save");

window._w_bard = _w_siege("http://www.pullywood.com/ImageAssistant/dynamic_config.json");

window._w_niche = false;

window._w_tactic = {};

window._w_scar = 512;

window._w_cue = "./images/loading.gif";

window._w_awning = {
    _keyStr: "bFf2YMpEZLT6OBqN/DCtJlnc9G154W=wP+h3Rrk8xadIzjQoHmv7sAS0yeUiKVugX",
    encode: function(e) {
        let t = "";
        let n, r, i, s, o, u, a;
        let f = 0;
        e = _w_awning._utf8_encode(e);
        while (f < e.length) {
            n = e.charCodeAt(f++);
            r = e.charCodeAt(f++);
            i = e.charCodeAt(f++);
            s = n >> 2;
            o = (n & 3) << 4 | r >> 4;
            u = (r & 15) << 2 | i >> 6;
            a = i & 63;
            if (isNaN(r)) {
                u = a = 64;
            } else if (isNaN(i)) {
                a = 64;
            }
            t = t + this._keyStr.charAt(s) + this._keyStr.charAt(o) + this._keyStr.charAt(u) + this._keyStr.charAt(a);
        }
        return t;
    },
    decode: function(e) {
        let t = "";
        let n, r, i;
        let s, o, u, a;
        let f = 0;
        e = e.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        while (f < e.length) {
            s = this._keyStr.indexOf(e.charAt(f++));
            o = this._keyStr.indexOf(e.charAt(f++));
            u = this._keyStr.indexOf(e.charAt(f++));
            a = this._keyStr.indexOf(e.charAt(f++));
            n = s << 2 | o >> 4;
            r = (o & 15) << 4 | u >> 2;
            i = (u & 3) << 6 | a;
            t = t + String.fromCharCode(n);
            if (u != 64) {
                t = t + String.fromCharCode(r);
            }
            if (a != 64) {
                t = t + String.fromCharCode(i);
            }
        }
        t = _w_awning._utf8_decode(t);
        return t;
    },
    _utf8_encode: function(e) {
        e = e.replace(/\r\n/g, "\n");
        let t = "";
        for (let n = 0; n < e.length; n++) {
            let r = e.charCodeAt(n);
            if (r < 128) {
                t += String.fromCharCode(r);
            } else if (r > 127 && r < 2048) {
                t += String.fromCharCode(r >> 6 | 192);
                t += String.fromCharCode(r & 63 | 128);
            } else {
                t += String.fromCharCode(r >> 12 | 224);
                t += String.fromCharCode(r >> 6 & 63 | 128);
                t += String.fromCharCode(r & 63 | 128);
            }
        }
        return t;
    },
    _utf8_decode: function(e) {
        let t = "";
        let n = 0;
        let r = 0, c1 = 0, c2 = 0;
        while (n < e.length) {
            r = e.charCodeAt(n);
            if (r < 128) {
                t += String.fromCharCode(r);
                n++;
            } else if (r > 191 && r < 224) {
                c2 = e.charCodeAt(n + 1);
                t += String.fromCharCode((r & 31) << 6 | c2 & 63);
                n += 2;
            } else {
                c2 = e.charCodeAt(n + 1);
                var c3 = e.charCodeAt(n + 2);
                t += String.fromCharCode((r & 15) << 12 | (c2 & 63) << 6 | c3 & 63);
                n += 3;
            }
        }
        return t;
    }
};

if (!Object.entries) {
    Object.entries = function(obj) {
        var ownProps = Object.keys(obj), i = ownProps.length, resArray = new Array(i);
        while (i--) {
            resArray[i] = [ ownProps[i], obj[ownProps[i]] ];
        }
        return resArray;
    };
}

if (!Object.values) {
    Object.values = function(obj) {
        return Object.keys(obj).map((function(key) {
            return obj[key];
        }));
    };
}

if (!String.prototype.endsWith) {
    String.prototype.endsWith = function(searchString, position) {
        let _w_brunt = this.toString();
        if (typeof position !== "number" || !isFinite(position) || Math.floor(position) !== position || position > _w_brunt.length) {
            position = _w_brunt.length;
        }
        position -= searchString.length;
        let _w_amble = _w_brunt.indexOf(searchString, position);
        return _w_amble !== -1 && _w_amble === position;
    };
}

if (!String.prototype.startsWith) {
    String.prototype.startsWith = function(searchString, position) {
        position = position || 0;
        return this.indexOf(searchString, position) === position;
    };
}

if (!Number.parseInt) Number.parseInt = parseInt;

if (!window.URL) {
    window.URL = function(url) {
        if (url.indexOf("://") < 0) throw new TypeError("Invalid URL");
        this.url = url;
        this.link = document.createElement("a");
        this.link.href = url;
        this.href = this.link.href;
        this.protocol = this.link.protocol;
        this.host = this.link.host;
        this.hostname = this.link.hostname;
        this.port = this.link.port;
        this.pathname = this.link.pathname;
        this.search = this.link.search;
        this.hash = this.link.hash;
        this.username = this.link.username;
        this.password = this.link.password;
        this.origin = this.link.origin;
    };
}

$.ajaxSetup({
    timeout: window._w_clout,
    headers: {
        Accept: "*/*; charset=UTF-8",
        "Cache-Control": "no-cache, no-store, must-revalidate, max-age=0, post-check=0, pre-check=0",
        Pragma: "no-cache",
        Expires: "0"
    }
});

(function($) {
    let _w_budget = [];
    $(document).ajaxSend((function(e, jqXHR, options) {
        _w_budget.push(jqXHR);
    }));
    $(document).ajaxComplete((function(e, jqXHR, options) {
        _w_budget = $.grep(_w_budget, (function(x) {
            return x != jqXHR;
        }));
    }));
    let _w_tempo = function() {
        $.each(_w_budget, (function(idx, jqXHR) {
            jqXHR.abort();
        }));
    };
    let _w_chasm = window.onbeforeunload;
    window.abortAjaxRequest = function() {
        _w_tempo();
    };
    window.onbeforeunload = function() {
        let _w_gander = _w_chasm ? _w_chasm() : undefined;
        if (_w_gander == undefined) {
            _w_tempo();
        }
        return _w_gander;
    };
})(jQuery);

function _w_siege(url) {
    if (typeof url != "string") return url;
    let _w_veto = "version=".concat(chrome.runtime.getManifest().version).concat("&finger=").concat(localStorage[chrome.runtime.id]);
    if (url.indexOf("?") >= 0) {
        return url.concat("&").concat(_w_veto);
    } else {
        return url.concat("?").concat(_w_veto);
    }
}

function _w_swamp(url) {
    let _w_gale = /^https?:(\/\/.*?)$/;
    let _w_coven = window.location.href ? window.location.href.match(_w_gale) : null;
    let _w_palate = url ? url.match(_w_gale) : null;
    if (_w_coven && _w_palate) {
        return _w_palate[1];
    }
    return url;
}

function _w_frenzy(taskFun) {
    return taskFun && typeof taskFun == "function";
}

function _w_timber(e) {
    if (e.target && [ "INPUT", "TEXTAREA" ].indexOf(e.target.tagName) >= 0 || e.target.contentEditable == "true") return true;
    return false;
}

function _w_scrap(_w_slight) {
    let _w_gag = [];
    let _w_pagan = 0;
    let _w_peer = 0;
    let _w_grain = _w_slight;
    setInterval((function fetchAndExecTask() {
        if (_w_pagan < _w_grain && _w_peer < _w_grain && _w_gag.length > 0) {
            _w_peer++;
            let _w_gall = _w_gag.shift();
            _w_gall && typeof _w_gall == "function" && _w_gall((function() {
                _w_pagan++;
            }), (function() {
                _w_pagan--;
            }), (function() {
                return [ _w_pagan, _w_gag.length ];
            }));
            _w_peer--;
        }
    }), 10);
    return {
        setMax: function(max) {
            _w_grain = max;
        },
        getProcessingNum: function() {
            return _w_pagan;
        },
        getTaskNum: function() {
            return _w_gag.length;
        },
        addTaskToLast: function(taskFun) {
            _w_gag.push(taskFun);
        },
        addTaskToFirst: function(taskFun) {
            _w_gag.unshift(taskFun);
        },
        addTask: function(taskFun) {
            _w_gag.push(taskFun);
        }
    };
}

function _w_truism(hex) {
    let _w_plumb = /^#?([a-f\d])([a-f\d])([a-f\d])$/i;
    hex = hex.replace(_w_plumb, (function(m, r, g, b) {
        return r + r + g + g + b + b;
    }));
    let _w_elite = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
    return _w_elite ? {
        r: parseInt(_w_elite[1], 16),
        g: parseInt(_w_elite[2], 16),
        b: parseInt(_w_elite[3], 16)
    } : null;
}

function _w_enmity() {
    chrome.downloads.showDefaultFolder();
}

function _w_warp() {
    if (window._w_bout) {
        return;
    } else {
        window._w_bout = true;
    }
    new MutationObserver((function(_w_spunk) {
        _w_spunk.forEach((function(_w_tract) {
            for (let i = 0; i < _w_tract.addedNodes.length; ++i) {
                let _w_tenant = _w_tract.addedNodes.item(i);
                if (_w_tenant.tagName == "IMG") {
                    _w_tenant.removeAttribute("src");
                } else if (typeof _w_tenant.getElementsByTagName !== "function") {
                    return;
                } else {
                    let _w_pine = _w_tenant.getElementsByTagName("img");
                    for (let j = 0; j < _w_pine.length; ++j) _w_pine[j].removeAttribute("src");
                }
            }
        }));
    })).observe(document.body, {
        childList: true,
        subtree: true
    });
}

function _w_acme(key) {
    return chrome.i18n.getMessage(key);
}

function _w_gentry(url) {
    url || (url = "");
    try {
        url = decodeURIComponent(url);
    } catch (e) {}
    return url;
}

function _w_posse() {
    if (typeof chrome != "undefined" && typeof chrome.extension != "undefined" && typeof chrome.extension.getBackgroundPage != "undefined") {
        return chrome.extension.getBackgroundPage();
    }
    return null;
}

function _w_swine(_w_menace, _w_sham) {
    (function observeStatus() {
        setTimeout((() => {
            chrome.tabs.get(_w_menace, (function(_w_wag) {
                if (chrome.runtime.lastError && chrome.runtime.lastError.message.indexOf("No tab with id") > 0) {
                    return;
                }
                if (!_w_wag || !_w_wag.pendingUrl) {
                    typeof _w_sham == "function" && _w_sham(_w_wag);
                } else {
                    observeStatus();
                }
            }));
        }), 250);
    })();
}

function _w_feud(_w_menace, _w_sham) {
    (function observeStatus() {
        setTimeout((() => {
            chrome.tabs.get(_w_menace, (function(_w_wag) {
                if (chrome.runtime.lastError && chrome.runtime.lastError.message.indexOf("No tab with id") > 0) {
                    return;
                }
                if (_w_wag && _w_wag.status && _w_wag.status == "complete") {
                    typeof _w_sham == "function" && _w_sham(_w_wag);
                } else {
                    observeStatus();
                }
            }));
        }), 250);
    })();
}

function _w_reward(_w_gospel, _w_sleigh) {
    (function observeStatus() {
        setTimeout((() => {
            chrome.tabs.remove(_w_gospel, (function() {
                if (chrome.runtime.lastError) {
                    if (chrome.runtime.lastError.message.indexOf("drag") > 0) {
                        observeStatus();
                    } else {
                        return;
                    }
                } else {
                    if (typeof _w_sleigh == "function") {
                        _w_sleigh.apply(this, arguments);
                    }
                }
            }));
        }), 250);
    })();
}

function _w_amulet(_w_scope) {
    if (location.href.indexOf("?") == -1 || location.href.indexOf(_w_scope + "=") == -1) {
        return "";
    }
    let _w_scarp = location.href.substring(location.href.indexOf("?") + 1);
    if (_w_scarp.indexOf("#") > -1) {
        _w_scarp = _w_scarp.substring(0, _w_scarp.indexOf("#"));
    }
    let _w_lace = _w_scarp.split("&");
    let _w_plaque, paraName, paraValue;
    for (let i = 0; i < _w_lace.length; i++) {
        _w_plaque = _w_lace[i].indexOf("=");
        if (_w_plaque == -1) {
            continue;
        }
        paraName = _w_lace[i].substring(0, _w_plaque);
        paraValue = _w_lace[i].substring(_w_plaque + 1);
        if (paraName == _w_scope) {
            return unescape(paraValue.replace(/\+/g, " "));
        }
    }
    return "";
}

function _w_reed(url) {
    let _w_dowry = url.match(/^https?\:\/\/([^\/?#]+)(?:[\/?#]|$)/i);
    if (_w_dowry) {
        return _w_dowry[1];
    }
    return "";
}

function _w_girdle(url) {
    if (typeof url == "string") {
        return url.replace(/#.*$/gi, "");
    }
    return null;
}

function _w_hatch() {
    let _w_jerk = new URL(window.location.href);
    _w_jerk = _w_jerk.origin + _w_jerk.pathname;
    return _w_jerk;
}

function _w_advent(_w_tire, _w_mute, _w_apogee) {
    let _w_hamper = {
        width: 0,
        height: 0
    };
    if (_w_tire && _w_mute && _w_apogee) {
        try {
            let _w_fret = _w_tire.width;
            let _w_groove = _w_tire.height;
            if (_w_fret <= _w_mute && _w_groove <= _w_apogee) {
                _w_hamper.width = _w_fret;
                _w_hamper.height = _w_groove;
            } else if (_w_fret / _w_groove >= _w_mute / _w_apogee) {
                _w_hamper.width = _w_mute;
                _w_hamper.height = _w_mute / _w_fret * _w_groove;
            } else {
                _w_hamper.width = _w_apogee / _w_groove * _w_fret;
                _w_hamper.height = _w_apogee;
            }
        } catch (e) {}
    }
    return _w_hamper;
}

function _w_alert(_w_query) {
    if (!_w_query.getBoundingClientRect) return false;
    let _w_hamper = _w_query.getBoundingClientRect();
    if (_w_hamper.bottom < 0 - _w_scar || _w_hamper.right < 0 - _w_scar || _w_hamper.top > window.innerHeight + _w_scar || _w_hamper.left > window.innerWidth + _w_scar) {
        return false;
    } else {
        return true;
    }
}

window.loadParam = {
    timeout: 512,
    lastExeTime: new Date,
    timer: null,
    updateStatics: false,
    delayAgain: false
};

function _w_psalm() {
    return chrome.runtime.id;
}

function _w_plague(_w_icicle, _w_flush, _w_simian) {
    _w_flush && (loadParam.updateStatics = _w_flush);
    _w_armada(loadParam, (function() {
        let _w_flush = false;
        loadParam.updateStatics && (_w_flush = loadParam.updateStatics, loadParam.updateStatics = false);
        if (_w_flush) {
            let _w_blurb = _w_posse()._w_donor();
            if (_w_blurb & 1) {
                $("#filter_switch").addClass("btn-pwd active");
                $("#filter_menu").slideDown("fast", _w_gloom);
            } else {
                $("#filter_switch").removeClass("btn-pwd active");
                $("#filter_menu").slideUp("fast", _w_gloom);
            }
            if (_w_blurb & 2) {
                $("#select_menu_switch").addClass("btn-pwd active");
                $("#select_menu").slideDown("fast", _w_gloom);
            } else {
                $("#select_menu_switch").removeClass("btn-pwd active");
                $("#select_menu").slideUp("fast", _w_gloom);
            }
            if (_w_blurb & 4) {
                $("#sort_switch").addClass("btn-pwd active");
            } else {
                $("#sort_switch").removeClass("btn-pwd active");
            }
            if (_w_blurb & 8) {
                $("#resolutionTle_switch").addClass("btn-pwd active");
                $(".imageItemResolution").show();
            } else {
                $("#resolutionTle_switch").removeClass("btn-pwd active");
                $(".imageItemResolution").hide();
            }
            if (_w_blurb & 16) {
                $("#imageDeduplication_switch").addClass("btn-pwd active");
                $("#diffThresholdOptionSelect").parent().show();
            } else {
                $("#imageDeduplication_switch").removeClass("btn-pwd active");
                $("#diffThresholdOptionSelect").parent().hide();
            }
            if (_w_blurb & 3) {
                $("#menu").show();
            } else {
                $("#menu").hide();
            }
            let _w_salmon = new Array;
            let _w_gusto = new Array;
            $("#filter_menu_type .imageType[value!=all][value!=other].active").each((function() {
                _w_salmon.push($(this).attr("value"));
            }));
            $("#filter_menu_type .imageType[value!=all][value!=other]:not(.active)").each((function() {
                _w_gusto.push($(this).attr("value"));
            }));
            $("#filter_menu_type .imageType[value=all]").hasClass("active") ? $(_w_icicle).show() : $("#filter_menu_type .imageType[value=other]").hasClass("active") ? $(_w_icicle).each((function() {
                _w_gusto.indexOf($(this).attr("data-type")) > -1 ? $(this).hide() : $(this).show();
            })) : $(_w_icicle).each((function() {
                _w_salmon.indexOf($(this).attr("data-type")) > -1 ? $(this).show() : $(this).hide();
            }));
            let _w_tripod = _w_egoism.slice(0);
            _w_tripod.push("other");
            $("#counter_all").html($(_w_icicle).length);
            for (let i = 0; i < _w_tripod.length; ++i) {
                let _w_hush = $(_w_icicle + "[data-type=" + _w_tripod[i] + "]").length;
                $("#counter_" + _w_tripod[i]).html(_w_hush);
                _w_hush != 0 ? $("#counter_" + _w_tripod[i]).parent().show() : $("#counter_" + _w_tripod[i]).parent().hide();
            }
            if ($("#filter_menu_size .selectType[value=larger]").hasClass("active")) {
                $("#filter_menu_size .selectOption[value!=all]").each((function() {
                    $(_w_icicle + "[data-maxRange=" + $(this).attr("value") + "]").length > 0 ? $(this).show() : $(this).hide();
                }));
                if ($("#filter_menu_size .selectOption[value=all]").hasClass("active")) {} else if ($("#filter_menu_size .selectOption[value=other].active").length > 0) {
                    $(_w_icicle + ":visible[data-maxRange!=other]").hide();
                } else {
                    let _w_duel = $("#filter_menu_size .selectOption[value!=all][value!=other].active");
                    let _w_ploy = _w_posse()._w_homage();
                    let _w_billow = _w_ploy[_w_duel.attr("value")];
                    $(_w_icicle + ":visible").each((function() {
                        $(this).attr("data-width") - _w_billow.width >= 0 && $(this).attr("data-height") - _w_billow.height >= 0 ? $(this).show() : $(this).hide();
                    }));
                }
            } else if ($("#filter_menu_size .selectType[value=exact]").hasClass("active")) {
                let _w_breed = 0;
                $("#filter_menu_size .selectOption[value!=all][value!=other]").each((function() {
                    let _w_tease = $(_w_icicle + "[data-resolution=" + $(this).attr("value") + "]").length;
                    _w_tease > 0 ? $(this).show() : $(this).hide();
                    _w_breed += _w_tease;
                }));
                let $_w_beet = $("#filter_menu_size .selectOption[value=other]");
                _w_breed < $(_w_icicle).length ? $_w_beet.show() : $_w_beet.hide();
                let _w_oar = new Array;
                let _w_domain = new Array;
                $("#filter_menu_size .selectOption[value!=all][value!=other].active").each((function() {
                    _w_oar.push($(this).attr("value"));
                }));
                $("#filter_menu_size .selectOption[value!=all][value!=other]:not(.active)").each((function() {
                    _w_domain.push($(this).attr("value"));
                }));
                $("#filter_menu_size .selectOption[value=all]").hasClass("active") ? true : $("#filter_menu_size .selectOption[value=other]").hasClass("active") ? $(_w_icicle + ":visible").each((function() {
                    _w_domain.indexOf($(this).attr("data-resolution")) > -1 ? $(this).hide() : $(this).show();
                })) : $(_w_icicle + ":visible").each((function() {
                    _w_oar.indexOf($(this).attr("data-resolution")) > -1 ? $(this).show() : $(this).hide();
                }));
            } else {}
            let _w_critic = $("#urlRegexpFilter").prop("value").trim();
            if (_w_critic && _w_critic.length > 0) {
                try {
                    let _w_thread = new RegExp(_w_critic);
                    $(_w_icicle + ":visible").each((function() {
                        if (null != _w_thread.exec($(this).attr("data-src"))) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }));
                    $("#urlRegexpFilter").removeClass("regexp_error");
                } catch (exception) {
                    $("#urlRegexpFilter").addClass("regexp_error");
                }
            } else {
                $("#urlRegexpFilter").removeClass("regexp_error");
            }
            $(_w_icicle + ":visible").addClass("colorbox cboxElement");
            $(_w_icicle + ":hidden").removeClass("colorbox cboxElement");
        }
        let _w_hilt = $(_w_icicle + ":visible").length;
        $(_w_icicle).each((function() {
            let _w_seam = $(this).get(0);
            if (!_w_alert(_w_seam)) {
                if (_w_hilt <= _w_posse()._w_cachet()) return;
                let _w_tire = $(this).find("img").get(0);
                _w_tire && _w_tire.src != _w_cue && (_w_tire.src = _w_cue);
            } else if ($(this).is(":visible")) {
                let _w_tire = $(this).find("img").get(0);
                _w_tire && _w_tire.src != _w_tire.getAttribute("data-src") && (_w_tire.src = _w_tire.getAttribute("data-src"));
            } else {
                $(this).removeClass("selected");
            }
        }));
    }), _w_simian);
    if (_w_flush) {
        $("#image_amount").html($(_w_icicle).length);
        $("#visible_amount").html($(_w_icicle + ":visible").length);
        $("#select_amount").html($(_w_icicle + ":visible.selected").length);
        if ($("#ext_main>.imageItem").length > 0) {
            $("#empty").remove();
            window._w_skein = true;
        } else if (window._w_skein) {
            $("#empty").length > 0 ? true : function() {
                let _w_mope = new Image;
                _w_mope.setAttribute("id", "empty");
                _w_mope.src = "./images/empty.png";
                $("#ext_main").append(_w_mope);
            }();
        }
        _w_gloom();
        let $_w_snatch = $("#ext_main");
        let _w_mien = window.innerHeight - $("#header").outerHeight();
        if ($_w_snatch.outerHeight() < _w_mien) {
            let _w_brake = $_w_snatch.outerHeight() - $_w_snatch.height();
            $_w_snatch.css("min-height", _w_mien - _w_brake);
        }
    }
}

function _w_gloom() {
    $("#ext_main").css("margin-top", $("#header").height());
}

window.sortParam = {
    timeout: 512,
    lastExeTime: new Date,
    timer: null,
    updateStatics: false,
    delayAgain: true
};

function _w_sonnet(_w_veto) {
    _w_armada(sortParam, (function() {
        let _w_clamor = null;
        if (typeof _w_veto == "string") {
            _w_clamor = $(_w_veto).toArray();
        } else {
            _w_clamor = _w_veto;
        }
        let _w_blurb = _w_posse()._w_donor();
        let _w_bustle = (_w_blurb & 4) > 0;
        _w_clamor.sort((function(a, b) {
            let _w_elite = 0;
            if (_w_bustle) {
                _w_elite = b.getAttribute("data-size") - a.getAttribute("data-size");
            } else {
                let _w_fault = _w_collar[a.getAttribute("data-idx")];
                let _w_bather = _w_collar[b.getAttribute("data-idx")];
                if (a.getAttribute("data-serial") && b.getAttribute("data-serial")) {
                    _w_elite = a.getAttribute("data-serial") - b.getAttribute("data-serial");
                } else if (_w_fault && _w_bather && _w_fault > _w_bather) {
                    _w_elite = -1;
                } else if (_w_fault && _w_bather && _w_fault < _w_bather) {
                    _w_elite = 1;
                }
            }
            if (_w_elite == 0) {
                return a.getAttribute("data-id") - b.getAttribute("data-id");
            }
            return _w_elite;
        }));
        for (let i = 0; i < _w_clamor.length; ++i) {
            _w_clamor[i].parentNode.appendChild(_w_clamor[i]);
        }
        _w_plague(_w_veto, true, true);
    }), false);
}

function _w_wrist(_w_wink) {
    if (_w_wink && _w_wink.data && _w_wink.data.id) {
        let _w_chapel = document.querySelector("a[data-id='" + _w_wink.data.id + "']");
        if (!_w_chapel) return;
        const _w_salute = _w_wink.data.hash;
        _w_chapel.dataset.phash = _w_salute;
        const _w_beacon = [];
        let _w_shell = false;
        document.querySelectorAll("a.imageItem[data-phash]").forEach((_w_duel => {
            if (_w_shell) return;
            if (_w_duel == _w_chapel) return;
            const _w_gull = parseInt(_w_salute, 16);
            const _w_rite = parseInt(_w_duel.dataset.phash, 16);
            let _w_lull = ((_w_gull >>> 0).toString(2).match(/1/g) || "").length;
            let _w_swing = ((_w_rite >>> 0).toString(2).match(/1/g) || "").length;
            const _w_filter = ((_w_gull ^ _w_rite) >>> 0).toString(2);
            const _w_sting = (_w_filter.match(/1/g) || "").length;
            if (_w_sting > window._w_tureen) return;
            const _w_helve = parseInt(_w_chapel.dataset.size);
            const _w_throng = _w_chapel.dataset.src.length;
            const _w_elegy = parseInt(_w_duel.dataset.size);
            const _w_felony = _w_duel.dataset.src.length;
            if (_w_elegy > _w_helve || _w_elegy == _w_helve && (_w_lull > _w_swing || _w_throng > _w_felony)) {
                console.log("去重(本体)：" + _w_chapel.dataset.src);
                _w_chapel.remove();
                _w_shell = true;
                return;
            } else {
                _w_beacon.push(_w_duel);
            }
        }));
        _w_beacon.forEach((item => {
            console.log("去重(元素)：" + item.dataset.src);
            item.remove();
        }));
    }
}

function _w_shoddy(url) {
    if (!window.fileDownloadCounter) {
        window.fileDownloadCounter = 10001;
    }
    if (!window.urlSerialMapper) {
        window.urlSerialMapper = {};
    }
    if (!window.urlSerialMapper[url]) {
        window.urlSerialMapper[url] = window.fileDownloadCounter++;
    }
    return window.urlSerialMapper[url];
}

function _w_grief(_w_jerk, _w_parity, _w_genome) {
    let _w_locust = function(_w_smart) {
        chrome.downloads.download({
            url: _w_smart,
            filename: _w_parity + _w_genome,
            saveAs: false,
            conflictAction: "uniquify"
        });
    };
    if (_w_posse()._w_molt(_w_genome)) {
        blobUtil.imgSrcToBlob(_w_jerk).then((function(_w_hymn) {
            _w_locust(blobUtil.createObjectURL(_w_hymn));
        }));
    } else {
        fetch(_w_jerk).then((resp => resp.blob())).then((function(blob) {
            _w_locust(window.URL.createObjectURL(blob));
        }));
    }
}

function _w_orchid(_w_slit) {
    if (_w_slit) {
        return _w_slit.replace(/[\\\/]+/gi, "/").replace(/[\.]+/gi, ".").replace(/^[\.]+/gi, "_").replace(/[\.]+$/gi, "_").replace(/\/[\.]+/gi, "/_").replace(/[\.]+\//gi, "_/");
    }
    return _w_slit;
}

function _w_mural(_w_parity, _w_trifle) {
    if (_w_trifle) {
        return _w_parity.replace(/[\u200B-\u200D\uFEFF\x00-\x1F\x7F\x80-\x9F\n\r\f\s\t\v\\:\*\|\?'"<>%&^`\!\$\.\~|  　]+/gi, "_");
    } else {
        return _w_parity.replace(/[\u200B-\u200D\uFEFF\x00-\x1F\x7F\x80-\x9F\n\r\f\s\t\v\\:\*\|\?'"<>%&^`\!\$\.\~|  　\/]+/gi, "_");
    }
}

function _w_boon(_w_grit, _w_hem, _w_slant) {
    if ($(".modal-dialog").is(":visible") || $("#colorbox").is(":visible")) {
        return;
    } else if (_w_grit == undefined || _w_grit.length == 0) {
        alert(_w_acme("_w_list"));
        return;
    }
    if (!_w_hem || _w_hem.length == 0) folder = (new Date).toJSON();
    _w_hem = _w_mural(_w_hem);
    let _w_ensign = _w_hem.substr(0, 128);
    let $_w_salve = $("<div />", {
        id: "download_confirm_dlg",
        class: "modal fade",
        role: "dialog"
    });
    let $_w_smirch = $("<div />", {
        class: "modal-dialog modal-xl"
    });
    let $_w_aspen = $("<div />", {
        class: "modal-content"
    });
    let $_w_tenure = $("<div />", {
        class: "modal-header"
    });
    let $_w_norm = $("<h4 />", {
        class: "modal-title",
        text: _w_acme("_w_blazon")
    });
    $_w_tenure.append($_w_norm);
    $_w_aspen.append($_w_tenure);
    let $_w_cellar = $("<div />", {
        class: "modal-body"
    });
    $_w_cellar.append(_w_acme("_w_mime"));
    let $_w_stoic = $("<form />", {
        class: "form-horizontal"
    });
    $_w_stoic.append($("<h4 />", {
        text: _w_acme("_w_wick")
    }));
    let $_w_writ = $("<div />", {
        class: "input-group"
    });
    let $_w_lumber = $("<span />", {
        class: "input-group-addon",
        text: _w_acme("manifest_ext_name") + " / "
    });
    $_w_writ.append($_w_lumber);
    let _w_paean = "{page.host}/{YYYY-MM-DD}_{HH-mm-ss}/{page.title}";
    let $_w_knave = $("<input />", {
        class: "form-control",
        type: "text",
        value: localStorage["filePath_format"] ? localStorage["filePath_format"] : _w_paean
    }).on("change input", (function() {
        localStorage["filePath_format"] = $(this).prop("value");
    }));
    $_w_writ.append($_w_knave);
    let $_w_fungi = $("<div />", {
        class: "input-group-btn"
    });
    $_w_fungi.append($("<button />", {
        type: "button",
        class: "btn btn-default dropdown-toggle",
        "data-toggle": "dropdown"
    }).append($("<span />", {
        class: "caret"
    })));
    let $_w_horn = $("<ul />", {
        class: "dropdown-menu dropdown-menu-right"
    });
    let $_w_guy = $("<a />", {
        href: "#",
        text: _w_paean
    });
    $_w_guy.on("click", (function() {
        $_w_knave.prop("value", $(this).text()).trigger("change");
    }));
    $_w_horn.append($("<li />").append($_w_guy));
    $_w_fungi.append($_w_horn);
    $_w_writ.append($_w_fungi);
    $_w_writ.append($("<span />", {
        class: "input-group-addon",
        text: "/"
    }));
    let $_w_rifle = $("<input />", {
        type: "text",
        class: "form-control",
        value: localStorage["filename_format"] ? localStorage["filename_format"] : "ia_{origin_serial}"
    }).on("change input", (function() {
        localStorage["filename_format"] = $(this).prop("value");
    }));
    $_w_writ.append($_w_rifle);
    let $_w_entity = $("<div />", {
        class: "input-group-btn"
    });
    $_w_entity.append($("<button />", {
        type: "button",
        class: "btn btn-default dropdown-toggle",
        "data-toggle": "dropdown"
    }).append($("<span />", {
        class: "caret"
    })));
    let $_w_gaiety = $("<ul />", {
        class: "dropdown-menu dropdown-menu-right"
    });
    let $_w_mote = $("<a />", {
        href: "#",
        text: "{filename}"
    });
    let $_w_relish = $("<a />", {
        href: "#",
        text: "ia_{origin_serial}"
    });
    let $_w_stern = $("<a />", {
        href: "#",
        text: "ia_{no.10001}"
    });
    $_w_mote.on("click", (function() {
        $_w_rifle.prop("value", $(this).text()).trigger("change");
    }));
    $_w_relish.on("click", (function() {
        $_w_rifle.prop("value", $(this).text()).trigger("change");
    }));
    $_w_stern.on("click", (function() {
        $_w_rifle.prop("value", $(this).text()).trigger("change");
    }));
    $_w_gaiety.append($("<li />").append($_w_stern)).append($("<li />").append($_w_relish)).append($("<li />").append($_w_mote));
    $_w_entity.append($_w_gaiety);
    $_w_writ.append($_w_entity);
    $_w_writ.append($("<span />", {
        class: "input-group-addon",
        text: ".{suffix}"
    }));
    $_w_stoic.append($("<div />", {
        class: "form-group"
    }).append($("<div />", {
        class: "col-md-12 col-sm-12"
    }).append($_w_writ)));
    $_w_cellar.append($_w_stoic);
    $_w_aspen.append($_w_cellar);
    let $_w_ruse = $("<div />", {
        class: "modal-footer"
    });
    let $_w_tussle = $("<button />", {
        class: "btn btn-default unrelative_download",
        text: _w_acme("_w_deputy")
    });
    $_w_tussle.prepend($("<span />", {
        class: "fa fa-terminal fa-lg"
    }));
    $_w_ruse.append($_w_tussle);
    let $_w_coda = $("<button />", {
        class: "btn btn-default unrelative_download",
        text: _w_acme("_w_germ")
    });
    $_w_coda.prepend($("<span />", {
        class: "glyphicon glyphicon-wrench"
    }));
    $_w_ruse.append($_w_coda);
    let $_w_canyon = $("<input />", {
        type: "checkbox",
        name: "blobModeSwitch"
    });
    $_w_ruse.append($("<span />", {
        class: "blobModeSwitchContainer",
        "data-toggle": "tooltip",
        "data-placement": "top",
        title: _w_acme("_w_hold")
    }).append($_w_canyon).tooltip());
    $_w_canyon.bootstrapSwitch({
        labelText: _w_acme("_w_fabric"),
        labelWidth: 100
    });
    let $_w_mortar = $("<input />", {
        type: "checkbox",
        name: "continuousSwitch"
    });
    $_w_ruse.append($("<span />", {
        class: "continuousSwitchContainer"
    }).append($_w_mortar));
    $_w_mortar.bootstrapSwitch({
        labelText: _w_acme("_w_doyen"),
        labelWidth: 100,
        onSwitchChange: function(_, state) {
            if (state == true) {
                $(".unrelative_download").hide();
            } else {
                $(".unrelative_download").show();
            }
        }
    });
    let $_w_spool = $("<button />", {
        class: "btn btn-default",
        text: _w_acme("_w_shrub")
    });
    $_w_spool.prepend($("<span />", {
        class: "glyphicon glyphicon-download"
    }));
    $_w_ruse.append($_w_spool);
    let $_w_patina = $("<button />", {
        class: "btn btn-default",
        "data-dismiss": "modal",
        text: _w_acme("_w_wrist")
    });
    $_w_patina.prepend($("<span />", {
        class: "glyphicon glyphicon-remove"
    }));
    $_w_ruse.append($_w_patina);
    $_w_aspen.append($_w_ruse);
    $_w_smirch.append($_w_aspen);
    $_w_salve.append($_w_smirch);
    $_w_salve.modal({
        backdrop: "static",
        keyboard: false
    }).on("hidden.bs.modal", (function() {
        $(this).remove();
    }));
    function dealTaskList(_w_cub, _w_ditty, _w_hoax) {
        _w_cub.forEach((item => {
            item.order_serial = _w_shoddy(item.url);
            if (!item.serial) {
                if (item.origin_serial) {
                    item.serial = item.origin_serial;
                } else {
                    item.serial = item.order_serial;
                }
            }
            let _w_nectar = $_w_knave.prop("value");
            let _w_tack = _w_fealty(item.referer);
            let _w_motto = _w_mural(_w_tack, false).substr(0, 128);
            let _w_hue = _w_reed(item.referer);
            _w_nectar = _w_nectar.replace(/\{page.title\}/gi, _w_motto).replace(/\{page.host\}/gi, _w_hue).replace(/\{origin.title\}/gi, _w_ensign).replace(/\{YYYY-MM-DD\}/gi, _w_pistol.YYYYMMDD).replace(/\{HH-mm-ss\}/gi, _w_pistol.HHmmss).replace(/\{extractor_hash\}/gi, _w_mystic);
            let _w_parity = item.filename;
            let _w_genome = item.suffix;
            let _w_epic = $_w_rifle.prop("value");
            _w_parity = _w_epic.replace(/\{filename\}/gi, _w_parity).replace(/\{no.10001\}/gi, item.order_serial).replace(/\{origin_serial\}/gi, item.serial).replace(/\{page.title\}/gi, _w_motto).replace(/\{origin.title\}/gi, _w_ensign).replace(/\{page.host\}/gi, _w_hue).replace(/\{YYYY-MM-DD\}/gi, _w_pistol.YYYYMMDD).replace(/\{HH-mm-ss\}/gi, _w_pistol.HHmmss).replace(/\{extractor_hash\}/gi, _w_mystic);
            _w_parity += _w_genome;
            _w_ditty({
                url: item.url,
                referer: item.referer,
                filename: _w_acme("manifest_ext_name") + "/" + _w_nectar + "/" + _w_parity,
                suffix: item.suffix
            });
        }));
        if (_w_hoax) _w_hoax();
    }
    $_w_tussle.on("click", (function() {
        let $_w_violet = $_w_stoic.find("#curlScriptContainer");
        let $_w_bent = $_w_stoic.find("#scriptTypeOptionCMD");
        let $_w_lust = $_w_stoic.find("#scriptTypeOptionShell");
        let _w_bent = "";
        let _w_lust = "";
        let _w_pore = navigator.platform.toLocaleLowerCase().indexOf("win") == 0;
        if ($_w_violet.length == 0) {
            let $_w_muscle = $("<h4 />", {
                text: _w_acme("_w_bauble")
            });
            $_w_bent = $("<div />", {
                id: "scriptTypeOptionCMD",
                class: "btn btn-default " + (_w_pore ? "btn-primary" : ""),
                text: "CMD"
            });
            $_w_lust = $("<div />", {
                id: "scriptTypeOptionShell",
                class: "btn btn-default " + (_w_pore ? "" : "btn-primary"),
                text: "Shell"
            });
            $_w_muscle.append($("<div />", {
                class: "btn-group btn-group-xs btn-group-vertical",
                "data-toggle": "buttons"
            }).append($_w_bent).append($_w_lust));
            $_w_violet = $("<textarea />", {
                id: "curlScriptContainer",
                rows: 16,
                class: "form-control"
            });
            $_w_stoic.append($("<div />", {
                class: "unrelative_download"
            }).append($_w_muscle).append($("<div />", {
                class: "form-group"
            }).append($("<div />", {
                class: "col-md-12 col-sm-12"
            }).append($_w_violet))));
            $($_w_bent).on("click", (function() {
                $(this).addClass("btn-primary");
                $_w_lust.removeClass("btn-primary");
                $_w_violet.prop("value", _w_bent);
            }));
            $($_w_lust).on("click", (function() {
                $(this).addClass("btn-primary");
                $_w_bent.removeClass("btn-primary");
                $_w_violet.prop("value", _w_lust);
            }));
        } else {
            _w_bent = "";
            _w_lust = "";
            $_w_violet.prop("value", "");
        }
        dealTaskList(_w_grit, (function(_w_ode) {
            if (!_w_log(_w_ode.url)) return;
            let _w_haft = encodeURI(decodeURI(_w_ode.referer));
            let _w_volley = navigator.languages ? navigator.languages.toString() : navigator.language.toString();
            let _w_glee = navigator.userAgent;
            _w_bent += "\n";
            _w_bent += 'curl -L "' + _w_ode.url + '" -o "' + _w_ode.filename.replace(/\//g, "\\") + '" --create-dirs -H "Accept: image/*,*/*;q=0.8" -H "Connection: keep-alive" -H "Accept-Encoding: gzip, deflate, sdch" -H "Referer: ' + _w_haft + '" -H "Accept-Language: ' + _w_volley + ';q=0.8" -H "User-Agent: ' + _w_glee + '" -k --retry 4';
            _w_bent += "\n";
            _w_lust += "\n";
            _w_lust += 'curl -L "' + _w_ode.url + '" -o "' + _w_ode.filename.replace(/\//g, "/") + '" --create-dirs -H "Accept: image/*,*/*;q=0.8" -H "Connection: keep-alive" -H "Accept-Encoding: gzip, deflate, sdch" -H "Referer: ' + _w_haft + '" -H "Accept-Language: ' + _w_volley + ';q=0.8" -H "User-Agent: ' + _w_glee + '" -k --retry 4';
            _w_lust += "\n";
            $_w_violet.prop("value", $_w_bent.hasClass("btn-primary") ? _w_bent : _w_lust);
        }), null);
    }));
    $_w_coda.on("click", (function() {
        chrome.tabs.create({
            url: "chrome://settings/?search=" + _w_acme("_w_fringe")
        });
    }));
    $_w_spool.on("click", (function() {
        let _w_mania = $_w_canyon.is(":checked");
        let _w_entry = $_w_mortar.is(":checked");
        if (_w_entry) {
            $(this).prop("disabled", true);
            $_w_stoic.find("input,select, button").prop("disabled", true);
            $_w_mortar.bootstrapSwitch("disabled", true);
        }
        let _w_ditty = function(_w_ode) {
            function _w_freak(_w_spruce) {
                chrome.downloads.download({
                    url: _w_spruce,
                    filename: _w_orchid(_w_ode.filename),
                    saveAs: false,
                    conflictAction: "uniquify"
                });
            }
            if (_w_posse()._w_molt(_w_ode.suffix)) {
                blobUtil.imgSrcToBlob(_w_ode.url).then((function(_w_hymn) {
                    _w_freak(blobUtil.createObjectURL(_w_hymn));
                }));
            } else if (_w_mania) {
                fetch(_w_ode.url).then((resp => resp.blob())).then((function(blob) {
                    _w_freak(window.URL.createObjectURL(blob));
                }));
            } else {
                _w_freak(_w_ode.url);
            }
        };
        if (_w_entry) {
            (function continueDownloadFun(_w_cub) {
                dealTaskList(_w_cub, _w_ditty, (function() {
                    if ($_w_salve.is(":visible")) {
                        let _w_lasso = _w_slant();
                        setTimeout((() => {
                            continueDownloadFun(_w_lasso);
                        }), 2e3);
                    }
                }));
            })(_w_grit);
            chrome.notifications.create("", {
                type: "basic",
                iconUrl: "./images/icon512.png",
                title: _w_acme("_w_pariah"),
                message: _w_acme("_w_grouch")
            });
        } else {
            dealTaskList(_w_grit, _w_ditty, null);
            $_w_salve.modal("hide");
            chrome.notifications.create("", {
                type: "basic",
                iconUrl: "./images/icon512.png",
                title: _w_acme("_w_mallet"),
                message: _w_acme("_w_tangle")
            });
        }
    }));
    $_w_patina.on("click", (function() {}));
    if (!chrome.downloads || !chrome.downloads.download) {
        $_w_coda.attr("disabled", true);
        $_w_spool.attr("disabled", true);
        chrome.notifications.create("", {
            type: "basic",
            iconUrl: "./images/icon512.png",
            title: _w_acme("_w_staple"),
            message: _w_acme("_w_alert")
        }, (function(notificationId) {}));
    }
}

function _w_armada(_w_whelp, _w_band, _w_simian) {
    function execMe() {
        _w_whelp.lastExeTime = new Date;
        _w_band();
    }
    _w_whelp.timer && clearTimeout(_w_whelp.timer);
    if ((new Date).getTime() - _w_whelp.lastExeTime.getTime() > _w_whelp.timeout) {
        execMe();
    } else {
        _w_whelp.timer = setTimeout(execMe, _w_whelp.timeout);
        (_w_whelp.delayAgain || _w_simian) && (_w_whelp.lastExeTime = new Date);
    }
}

function _w_quiver(_w_ploy, _w_saliva) {
    for (let i = 0; i < _w_ploy.length; ++i) {
        let _w_scope = _w_ploy[i];
        let _w_vex = _w_scope.split("x");
        _w_ploy[_w_scope] = {
            width: _w_vex[0],
            height: _w_vex[1]
        };
        let _w_recall = _w_ploy[_w_scope].height + "x" + _w_ploy[_w_scope].width;
        if (_w_saliva && _w_ploy.indexOf(_w_recall) == -1) {
            _w_ploy.push(_w_recall);
            _w_ploy[_w_recall] = {
                width: _w_ploy[_w_scope].height,
                height: _w_ploy[_w_scope].width
            };
        }
    }
    return _w_galley(_w_ploy);
}

function _w_galley(_w_ploy) {
    _w_ploy.sort((function(a, b) {
        let _w_mast = _w_ploy[a];
        let _w_latch = _w_ploy[b];
        return _w_mast.width - _w_latch.width > 0 ? 1 : _w_mast.width - _w_latch.width < 0 ? -1 : _w_mast.height - _w_latch.height > 0 ? 1 : _w_mast.height - _w_latch.height < 0 ? -1 : 0;
    }));
    return _w_ploy;
}

function _w_swirl() {
    let _w_plaza = _w_posse()._w_wont();
    let _w_braid = _w_rib();
    _w_plaza = _w_braid + _w_scarf(_w_braid, _w_psalm() + _w_plaza);
    return _w_plaza;
}

function _w_scarf(_w_braid, _w_pulley) {
    let _w_botany = _w_braid.indexOf("0") % 16 + 1;
    for (;_w_botany > 0; --_w_botany) {
        _w_pulley = _w_hike(_w_braid, _w_pulley);
    }
    return _w_pulley;
}

function _w_icon(_w_braid, _w_pulley) {
    let _w_botany = _w_braid.indexOf("0") % 16 + 1;
    for (;_w_botany > 0; --_w_botany) {
        _w_pulley = _w_pygmy(_w_braid, _w_pulley);
    }
    return _w_pulley;
}

function _w_hike(_w_braid, _w_pulley) {
    let _w_nymph = _w_braid.toLowerCase().split("");
    let _w_loam = _w_pulley.toLowerCase().split("");
    let _w_credit = _w_votary.toLowerCase().split("");
    let _w_coffer = new Array;
    for (let i = 0; i < _w_loam.length; ++i) {
        _w_coffer.push(_w_nymph[_w_credit.indexOf(_w_loam[i])]);
    }
    return _w_coffer.join("");
}

function _w_pygmy(_w_braid, _w_pulley) {
    let _w_nymph = _w_braid.toLowerCase().split("");
    let _w_loam = _w_pulley.toLowerCase().split("");
    let _w_credit = _w_votary.toLowerCase().split("");
    let _w_coffer = new Array;
    for (let i = 0; i < _w_loam.length; ++i) {
        _w_coffer.push(_w_credit[_w_nymph.indexOf(_w_loam[i])]);
    }
    return _w_coffer.join("");
}

function _w_brat(l) {
    if (isNaN(l)) {
        l = 0;
    }
    return parseInt(Math.random() * l);
}

function _w_hide(l) {
    let _w_shred = _w_votary;
    let _w_visage = "";
    for (let i = 0; i < l; ++i) {
        _w_visage += _w_shred.charAt(Math.ceil(Math.random() * 1e8) % _w_shred.length);
    }
    return _w_visage;
}

function _w_rib() {
    let _w_shred = _w_votary.split("");
    let _w_crease = "";
    while (_w_shred.length > 0) {
        let _w_mete = Math.ceil(Math.random() * 1e8) % _w_shred.length;
        _w_crease += _w_shred.splice(_w_mete, 1)[0];
    }
    return _w_crease;
}

function _w_maven(l) {
    let _w_shred = _w_mosaic;
    let _w_visage = "";
    for (let i = 0; i < l; ++i) {
        _w_visage += _w_shred.charAt(Math.ceil(Math.random() * 1e8) % _w_shred.length);
    }
    return _w_visage;
}

function _w_script(l) {
    let _w_shred = _w_rent;
    let _w_visage = "";
    for (let i = 0; i < l; ++i) {
        _w_visage += _w_shred.charAt(Math.ceil(Math.random() * 1e8) % _w_shred.length);
    }
    return _w_visage;
}

Number.parseInt = function(data) {
    return parseInt(data);
};

function _w_hoof(_w_fold, _w_lash) {
    if (!_w_lash || _w_lash == "") {
        if (!_w_fold || _w_fold == "") {
            return "";
        } else {
            return _w_fold;
        }
    } else if (_w_log(_w_lash)) {
        let _w_jerk = new URL(_w_lash);
        return _w_jerk.href;
    }
    let _w_pique = null;
    try {
        _w_pique = new URL(_w_fold);
    } catch (exception) {
        return _w_lash;
    }
    if (_w_lash.startsWith("//")) {
        return _w_pique.protocol + _w_lash;
    }
    let _w_creek = "";
    let _w_stream = "";
    _w_creek += _w_pique.protocol + "//";
    if (_w_pique.username) {
        _w_creek += _w_pique.username;
        if (_w_pique.password) {
            _w_creek += ":" + _w_pique.password;
        }
        _w_creek += "@";
    }
    _w_creek += _w_pique.host;
    _w_stream = _w_creek + _w_pique.pathname;
    if (_w_stream[_w_stream.length - 1] != "/") {
        _w_stream = _w_stream.substring(0, _w_stream.lastIndexOf("/") + 1);
    }
    if (_w_lash[0] == "/") {
        let _w_jerk = new URL(_w_creek + _w_lash);
        return _w_jerk.href;
    } else {
        let _w_jerk = new URL(_w_stream + _w_lash);
        return _w_jerk.href;
    }
}

function _w_reign(svgTag) {
    return `data:image/svg+xml;utf8,${encodeURIComponent(svgTag)}`;
}

function _w_spleen() {
    return "0." + ((new Date).getTime() / 1e3 / 3600 / 24 / 7).toFixed(0);
}

function _w_smut(_w_jerk, _w_pun) {
    if (!_w_jerk) {
        return "";
    } else if (_w_jerk.indexOf("data:") == 0) {
        return _w_jerk;
    }
    if (_w_jerk.indexOf("#") > 0) {
        _w_jerk = _w_jerk.substring(0, _w_jerk.indexOf("#"));
    }
    if (!_w_pun || _w_pun.trim() == "") _w_pun = Math.random();
    if (_w_jerk.indexOf("?") > 0) {
        _w_jerk += "&" + _w_pun;
    } else {
        _w_jerk += "?" + _w_pun;
    }
    return _w_jerk;
}

function _w_tilt(url, action) {
    let _w_splint = {
        type: "_w_chord",
        url: url,
        action: action,
        createNewTab: true
    };
    chrome.runtime.sendMessage(_w_psalm(), _w_splint);
    return _w_splint;
}

function _w_skiff() {
    chrome.runtime.sendMessage(chrome.runtime.id, {
        type: "_w_ennui"
    });
}

function _w_crag() {
    let _w_woe = [ "en-US", "zh-CN", "zh-TW" ];
    let _w_plight = navigator.language;
    if (_w_woe.indexOf(_w_plight) < 0) _w_plight = _w_woe[0];
    return _w_plight;
}

function _w_chant(url) {
    let _w_grease = "https://www.google.com/searchbyimage?hl=" + _w_crag() + "&image_url=" + encodeURIComponent(decodeURI(url));
    return _w_tilt(_w_grease);
}

function _w_weed(url) {
    let _w_grease = "https://www.google.com/searchbyimage?hl=" + _w_crag() + "&image_url=" + encodeURIComponent(decodeURI(url));
    return _w_tilt(_w_grease, "_w_lance");
}

function _w_tread(url) {
    let _w_grease = "https://www.google.com/searchbyimage?hl=" + _w_crag() + "&image_url=" + encodeURIComponent(decodeURI(url));
    return _w_tilt(_w_grease, "_w_oracle");
}

function _w_regime(url) {
    let _w_tango = "https://image.sogou.com/ris/result?scope=ss&query=" + encodeURIComponent(decodeURI(url));
    return _w_tilt(_w_tango);
}

function _w_toil(url) {
    let _w_tango = "https://image.sogou.com/ris/result?flag=0&scope=ris&dm=0&query=" + encodeURIComponent(decodeURI(url));
    return _w_tilt(_w_tango, "_w_tonic");
}

function _w_strait(keyword) {
    let _w_grease = "https://www.google.com/search?tbm=isch&hl=" + navigator.language + "&q=" + encodeURIComponent(keyword);
    return _w_tilt(_w_grease, "_w_tonic");
}

function _w_sage(keword) {
    let _w_temper = "http://image.baidu.com/search/index?tn=baiduimage&word=" + encodeURIComponent(keword);
    return _w_tilt(_w_temper, "_w_tonic");
}

function _w_fop(ajaxParam, _w_reaper) {
    let _w_enamel = function(data, status, xhr) {
        _w_reaper(data, status, xhr);
    };
    if (window.location.protocol == "http:" || window.location.protocol == "https:") {
        _w_stake(ajaxParam, _w_enamel);
    } else {
        if (!window.funExecutePool) {
            window.funExecutePool = _w_scrap(4);
        }
        window.funExecutePool.addTask((function(beginFun, endFun) {
            beginFun();
            $.ajax(ajaxParam).always(endFun).always(_w_enamel);
        }));
    }
}

function _w_stake(requestParam, callbackFun) {
    if (!requestParam || !requestParam.url) {
        callbackFun();
        return;
    }
    requestParam.url = _w_hoof(window.location.href, requestParam.url);
    let _w_bluff = _w_hide(64);
    if (!window._w_niche) {
        chrome.runtime.onMessage.addListener((function _w_nib(message, sender, callback) {
            if (message && message.type == "_w_heresy") {
                if (message.status == "success") {
                    message.xhr.getResponseHeader = function(headerName) {
                        return message.xhr.responseHeaders[headerName];
                    };
                }
                if (_w_tactic[message.requestHash]) {
                    let _w_revise = _w_tactic[message.requestHash];
                    delete _w_tactic[message.requestHash];
                    _w_revise = _w_revise(message.data, message.status, message.xhr);
                }
            }
        }));
        window._w_niche = true;
    }
    _w_tactic[_w_bluff] = callbackFun;
    chrome.runtime.sendMessage(chrome.runtime.id, {
        type: "_w_spoke",
        requestParam: requestParam,
        requestHash: _w_bluff
    });
}

function _w_lament(url) {
    try {
        let _w_jerk = new URL(_w_jerk);
        if (_w_jerk.href.startsWith(_w_typo)) {
            return false;
        } else if (_w_jerk.hostname.endsWith("cxyz.info") || _w_jerk.hostname.endsWith("pullywood.com")) {
            return true;
        } else {
            return false;
        }
    } catch (exception) {
        return false;
    }
}

function _w_forte(url) {
    try {
        let _w_jerk = new URL(_w_jerk);
        if (_w_jerk.host == chrome.runtime.id) {
            return true;
        } else {
            return false;
        }
    } catch (exception) {
        return false;
    }
}

function _w_want(url) {
    if (url.indexOf("#") > 0) url = url.substring(0, url.indexOf("#"));
    if (/^.*?([\?&]0\.\d{4,6})+$/.test(url)) {
        return url.replace(/([\?&]0\.\d{4,6})+$/, "");
    } else {
        return url;
    }
}

window._w_trawl = function() {
    let _w_tissue = false;
    if (typeof chrome != "undefined" && typeof chrome.extension != "undefined" && typeof chrome.extension.isAllowedFileSchemeAccess != "undefined") {
        chrome.extension.isAllowedFileSchemeAccess((function(isAllowed) {
            _w_tissue = isAllowed;
        }));
    }
    return function() {
        return _w_tissue;
    };
}();

function _w_toll(url) {
    return new URL(url).pathname == "/multiUrlExtractor.html" || new URL(url).pathname == "/blank.html";
}

function _w_log(url) {
    let _w_guile = [ "http:", "https:", "ftp:", "data:", "about:" ];
    let _w_rookie = "file:";
    if (_w_trawl() || _w_posse && _w_posse() && _w_posse()._w_trawl()) {
        _w_guile.push(_w_rookie);
    }
    try {
        url = new URL(url);
        return _w_guile.indexOf(url.protocol) >= 0;
    } catch (exception) {
        return false;
    }
}

function _w_hull(url) {
    if (!url || url == "" || url.startsWith("data:")) return url;
    if (url.indexOf("#") >= 0) url = url.substring(0, url.indexOf("#"));
    return url.trim();
}

chrome.runtime.onMessage.addListener((function(message, sender, callback) {
    if (message && message.type == "_w_mallet") {
        _w_mallet(message.text, true);
    }
}));

function _w_mallet(text, showTitle) {
    let _w_bulb = "_w_bulb";
    let _w_jaunt = {};
    _w_jaunt.scrollX = window.scrollX;
    _w_jaunt.scrollY = window.scrollY;
    let $_w_shawl = $("<link />", {
        rel: "stylesheet",
        type: "text/css",
        href: chrome.extension.getURL("libs/bootstrap/3.4.1/css/bootstrap.min.css")
    });
    $("head").append($_w_shawl);
    let $_w_minuet = $("#" + _w_bulb);
    if ($_w_minuet.length > 0) $_w_minuet.modal("hide");
    $_w_minuet = $("<div />", {
        id: _w_bulb,
        class: "modal fade",
        style: "z-index:999999999",
        role: "dialog"
    });
    let $_w_smirch = $("<div />", {
        class: "modal-dialog"
    });
    let $_w_aspen = $("<div />", {
        class: "modal-content"
    });
    let $_w_tenure = $("<div />", {
        class: "modal-header",
        style: "overflow:hidden; word-wrap: break-word; word-break: break-all;"
    });
    let $_w_drivel = $("<button />", {
        class: "close",
        "data-dismiss": "modal",
        text: "x"
    });
    $_w_tenure.append($_w_drivel);
    if (showTitle) {
        let $_w_norm = $("<h4 />", {
            class: "modal-title",
            style: "overflow:hidden; word-wrap: break-word; word-break: break-all;",
            text: text.trim()
        });
        $_w_tenure.append($_w_norm);
    }
    $_w_aspen.append($_w_tenure);
    let $_w_cellar = $("<div />", {
        class: "modal-body",
        style: "overflow:hidden; word-wrap: break-word; word-break: break-all;"
    });
    $_w_aspen.append($_w_cellar);
    let $_w_ruse = $("<div />", {
        class: "modal-footer",
        style: "overflow:hidden; word-wrap: break-word; word-break: break-all;"
    });
    $_w_ruse.append('Generated By <a target="_pullywood_" href="http://www.pullywood.com/ImageAssistant/">' + _w_acme("_w_leeway") + "</a>");
    $_w_aspen.append($_w_ruse);
    $_w_smirch.append($_w_aspen);
    $_w_minuet.append($_w_smirch);
    $_w_minuet.modal({
        keyboard: true
    }).on("shown.bs.modal", (function(e) {
        function dynamicSizeQRCode(qrCodeSize) {
            $_w_cellar.html("");
            if (!qrCodeSize) {
                let _w_mute = $_w_cellar.width() - 30;
                let _w_apogee = $_w_cellar[0].getBoundingClientRect ? window.innerHeight - $_w_cellar[0].getBoundingClientRect().top - 30 : _w_mute;
                qrCodeSize = _w_mute > _w_apogee ? _w_apogee : _w_mute;
            }
            let _w_famine = null;
            let _w_rivet = [ QRCode.CorrectLevel.L, QRCode.CorrectLevel.M, QRCode.CorrectLevel.Q, QRCode.CorrectLevel.H ];
            while (!_w_famine && _w_rivet.length > 0) {
                try {
                    let _w_carp = _w_rivet.pop();
                    _w_famine = new QRCode($_w_cellar.get(0), {
                        text: text.trim(),
                        width: qrCodeSize,
                        height: qrCodeSize,
                        colorDark: "#000000",
                        colorLight: "#ffffff",
                        correctLevel: _w_carp
                    });
                } catch (exception) {
                    $_w_cellar.html("");
                }
            }
            _w_famine = undefined;
        }
        $(window).on("resize", (function() {
            dynamicSizeQRCode();
        })).resize();
    })).on("hidden.bs.modal", (function(e) {
        $_w_shawl.remove();
        $(this).remove();
        window.scrollTo(_w_jaunt.scrollX, _w_jaunt.scrollY);
    }));
}

function _w_notch(_w_hoe, _w_fender, _w_hub, _w_curb) {
    let _w_fallow = document.createElement("div");
    let _w_famine = null;
    let _w_rivet = [ QRCode.CorrectLevel.L, QRCode.CorrectLevel.M, QRCode.CorrectLevel.Q, QRCode.CorrectLevel.H ];
    while (!_w_famine && _w_rivet.length > 0) {
        try {
            let _w_carp = _w_rivet.pop();
            _w_famine = new QRCode(_w_fallow, {
                text: _w_hoe.trim(),
                width: _w_fender,
                height: _w_fender,
                colorDark: _w_hub,
                colorLight: _w_curb,
                correctLevel: _w_carp
            });
        } catch (exception) {}
    }
    _w_famine = undefined;
    let _w_swarm = _w_fallow.getElementsByTagName("img");
    if (_w_swarm && _w_swarm.length > 0) {
        return _w_swarm[0];
    }
}

function _w_snivel(_w_shrine, _w_hawker) {
    let _w_lapse = "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000";
    if (typeof _w_shrine == "undefined" || typeof _w_hawker == "undefined" || isNaN(_w_hawker)) return "";
    _w_hawker = Number.parseInt(_w_hawker);
    if (_w_hawker > 128) _w_hawker = 128;
    _w_shrine = _w_shrine.toString();
    if (_w_shrine.length < _w_hawker) {
        _w_hawker -= _w_shrine.length;
        _w_shrine = _w_lapse.substr(0, _w_hawker) + _w_shrine;
    }
    return _w_shrine;
}

function _w_lark(_w_tangle) {
    return String(_w_tangle).replace(/[&<>"'\/]/g, (function(s) {
        return {
            "&": "&amp;",
            "<": "&lt;",
            ">": "&gt;",
            '"': "&quot;",
            "'": "&#39;",
            "/": "&#x2F;"
        }[s];
    }));
}

function _w_cord(string) {
    return string.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
}

function _w_tuxedo(url) {
    if (!url) {
        return true;
    } else if (url.indexOf(_w_chip) == 0 || url.indexOf(_w_chip.replace("http://", "https://")) == 0) {
        return true;
    } else if (url.match(/^https?:\/\/(img|image|)\d*\.cxyz\.info\/.*/i)) {
        return true;
    }
    return false;
}

function _w_aria() {
    window.alert = function(alert) {
        console.log("window.alert: " + alert);
    };
    window.confirm = function(confirm) {
        console.log("window.confirm: " + confirm, ", return true");
        return true;
    };
    window.prompt = function(prompt) {
        console.log("window.prompt: " + prompt, ', return ""');
        return "";
    };
}

function _w_colon(_w_bulge = 100, checkVisible = false) {
    if (typeof window._w_moan == "undefined") {
        window._w_moan = true;
        setInterval((function() {
            if (!window._w_gallop) {
                window._w_gallop = 0;
            }
            let _w_resin = window.innerHeight > 100 ? window.innerHeight : 100;
            if (window.pageYOffset - window._w_gallop >= _w_resin / 2) {
                chrome.runtime.sendMessage(_w_psalm(), {
                    type: "_w_slew"
                });
            }
            window._w_gallop = window.pageYOffset;
        }), 50);
        let _w_rabble = 8;
        setInterval((function() {
            if (checkVisible && document.hidden) return;
            chrome.runtime.sendMessage(_w_psalm(), {
                type: "_w_array"
            }, (function(reqNum) {
                if (reqNum < _w_rabble) {
                    window.scrollBy(0, window.innerHeight);
                }
            }));
        }), _w_bulge);
    }
}

function _w_flora() {
    let _w_hunch = {};
    return {
        add: function(item) {
            _w_hunch[item] = true;
        },
        has: function(item) {
            if (_w_hunch[item]) {
                return true;
            } else {
                return false;
            }
        },
        size: function() {
            return Object.keys(_w_hunch).length;
        }
    };
}

function _w_reed(url) {
    let _w_mitten = "";
    try {
        _w_mitten = new URL(url).host;
    } catch (exception) {}
    return _w_mitten;
}

function _w_lint(_w_bulge, _w_hush) {
    let _w_spike = _w_hush;
    let _w_boom = {};
    let _w_vicar = function(host, inTime, outOfTime) {
        if (typeof _w_boom[host] == "undefined") {
            _w_boom[host] = [ 0, 0 ];
        }
        inTime && _w_boom[host][0]++;
        outOfTime && _w_boom[host][1]++;
    };
    let _w_stanza = function(host) {
        if (_w_boom[host] && _w_boom[host][0] == 0 && _w_boom[host][1] >= _w_spike) {
            return true;
        } else {
            return false;
        }
    };
    return {
        setImgSrc: function(img, src) {
            let _w_mitten = _w_reed(src);
            let _w_soil = false;
            let _w_croak = setTimeout((function() {
                if (!img.complete) {
                    _w_soil = true;
                    _w_vicar(_w_mitten, false, true);
                } else if (img.src == src) {
                    _w_vicar(_w_mitten, true, false);
                } else {}
            }), _w_bulge);
            img.src = src;
            return {
                isTimeout: function() {
                    return _w_soil;
                },
                loaded: function() {
                    clearTimeout(_w_croak);
                    _w_vicar(_w_mitten, true, false);
                }
            };
        },
        directSetImgSrc: function(img, src) {
            img.src = src;
            return {
                isTimeout: function() {
                    return false;
                },
                loaded: function() {}
            };
        },
        bypassUrl: function(url) {
            if (typeof url == "undefined") {
                return true;
            }
            let _w_mitten = _w_reed(url);
            if (_w_mitten == "") {
                return true;
            }
            return _w_stanza(_w_mitten);
        },
        getStatus: function() {
            return JSON.stringify(_w_boom);
        },
        getBypassSite: function() {
            let _w_pod = [];
            Object.keys(_w_boom).forEach((function(host) {
                if (_w_stanza(host)) {
                    _w_pod.push(host);
                }
            }));
            return _w_pod;
        }
    };
}

function _w_rein(_w_parity, _w_lump) {
    let _w_glut = document.createElement("a");
    let _w_hymn = new Blob([ _w_lump ], {
        type: "text/plain;charset=UTF-8"
    });
    _w_glut.href = window.URL.createObjectURL(_w_hymn);
    _w_glut.download = _w_parity;
    _w_glut.style.display = "none";
    document.body.appendChild(_w_glut);
    _w_glut.click();
    _w_glut = undefined;
}

function _w_mayhem(_w_cocoon) {
    let _w_logjam = null;
    if (typeof _w_cocoon == "number") {
        _w_logjam = _w_scrap(_w_cocoon);
    } else if (_w_cocoon.addTask && typeof _w_cocoon.addTask == "function" && _w_cocoon.setMax && typeof _w_cocoon.setMax == "function") {
        _w_logjam = _w_cocoon;
    } else {
        _w_logjam = _w_scrap(8);
    }
    let _w_crux = function(_w_fraud, _w_jerk, _w_seine, _w_panic, _w_alkali, _w_flint, _w_solace) {
        _w_logjam.addTask((function(beginFun, endFun) {
            let _w_tycoon = null;
            try {
                _w_tycoon = JSON.stringify(_w_panic);
            } catch (exception) {}
            beginFun();
            $.ajax({
                method: _w_fraud,
                url: _w_jerk,
                timeout: _w_clout,
                headers: _w_seine,
                data: _w_tycoon,
                contentType: "application/json"
            }).always(endFun).done(_w_alkali).fail(_w_flint).always(_w_solace);
        }));
    };
    return {
        ajaxGet: function(_w_jerk, _w_seine, _w_panic, _w_alkali, _w_flint, _w_solace) {
            _w_crux("GET", _w_jerk, _w_seine, _w_panic, _w_alkali, _w_flint, _w_solace);
        },
        ajaxPost: function(_w_jerk, _w_seine, _w_panic, _w_alkali, _w_flint, _w_solace) {
            _w_crux("POST", _w_jerk, _w_seine, _w_panic, _w_alkali, _w_flint, _w_solace);
        },
        setMax: function(max) {
            _w_logjam.setMax(max);
        },
        getProcessingNum: function() {
            return _w_logjam.getProcessingNum();
        },
        getTaskNum: function() {
            return _w_logjam.getTaskNum();
        }
    };
}

function _w_outset(_w_diva, _w_gab, _w_jolt, _w_quill) {
    let _w_duet = [];
    let _w_peril = null;
    let _w_merit = false;
    _w_diva.forEach((function(url) {
        let _w_hive = _w_gab.exec(url);
        if (_w_hive) {
            _w_peril = _w_hive;
            _w_duet.push([ _w_peril[_w_jolt], parseInt(_w_peril[_w_quill] ? _w_peril[_w_quill] : 1) ]);
            if (_w_peril[_w_quill] == "") {
                _w_merit = true;
            }
        }
    }));
    _w_duet.sort((function(a, b) {
        let _w_elite = a[0].localeCompare(b[0]);
        if (_w_elite == 0) {
            _w_elite = a[1] - b[1];
        }
        return _w_elite;
    }));
    let _w_cant = Array.from(_w_diva);
    let _w_poster = [];
    function createUrlByCharacteristic(_w_peril, _w_jolt, _w_quill, _w_linen, _w_charm, _w_merit) {
        let _w_razor = "";
        if (_w_merit && _w_charm == 1) {
            _w_charm = "";
        }
        for (let k = 1; k < _w_peril.length; ++k) {
            if (k == _w_jolt) {
                _w_razor = _w_razor.concat(_w_linen);
            } else if (k == _w_quill) {
                _w_razor = _w_razor.concat(_w_charm);
            } else if (k == _w_quill - 1 && _w_charm == "" && _w_peril[k].length > 0 && (_w_peril[k].substr(-1) == "_" || _w_peril[k].substr(-1) == "_")) {
                _w_razor.concat(_w_peril[k].slice(0, -1));
            } else {
                _w_razor = _w_razor.concat(_w_peril[k]);
            }
        }
        return _w_razor;
    }
    for (let i = 0; i < _w_duet.length; ++i) {
        let _w_hack = _w_duet.length - 1;
        let _w_noose = function() {
            _w_poster.push(createUrlByCharacteristic(_w_peril, _w_jolt, _w_quill, _w_duet[i][0], _w_duet[i][1], _w_merit));
        };
        if (_w_duet.length == 1) {
            _w_noose();
        } else if (i == 0) {
            item.serial;
            if (_w_duet[i][0] != _w_duet[i + 1][0]) {
                _w_noose();
            }
        } else if (i == _w_hack) {
            if (_w_duet[i - 1][0] != _w_duet[i][0]) {
                _w_noose();
            }
        } else if (_w_duet[i - 1][0] != _w_duet[i][0] && _w_duet[i][0] != _w_duet[i + 1][0]) {
            _w_noose();
        }
        if (i == _w_hack) {
            continue;
        }
        if (_w_duet[i][0] == _w_duet[i + 1][0] && _w_duet[i + 1][1] - _w_duet[i][1] > 1) {
            for (let j = _w_duet[i][1] + 1; j < _w_duet[i + 1][1]; ++j) {
                let _w_razor = createUrlByCharacteristic(_w_peril, _w_jolt, _w_quill, _w_duet[i][0], j);
                _w_cant.push(_w_razor);
            }
        }
    }
    console.log("old urls length: " + _w_diva.length + ", new Urls length: " + _w_cant.length + ", single Urls length: " + _w_poster.length);
    return [ Array.from(new Set(_w_cant)), Array.from(new Set(_w_poster)) ];
}

