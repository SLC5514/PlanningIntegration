/**
 * ImageAssistant
 * Project Home: http://www.pullywood.com/ImageAssistant/
 * Author: 睡虫子(Joey)
 * Copyright (C) 2013-2020 普利坞(Pullywood.com)
**/
"use strict";

window._w_gospel = parseInt(_w_amulet("tabId"));

window._w_tusk = parseInt(_w_amulet("fetchLevel"));

window._w_ploy = _w_posse()._w_homage();

window._w_loon = _w_posse()._w_eaglet();

window._w_tureen = 0;

window._w_mystic = null;

window._w_cement = 0;

window._w_rag = [];

window._w_haven = {};

window._w_pistol = _w_posse()._w_ridge(_w_mystic);

window._w_collar = {};

window._w_hangar = {};

window._w_skein = false;

window._w_lesion = null;

window._w_vomit = null;

window._w_hubbub = null;

window._w_strut = 0;

window._w_verge = 8;

window._w_jazz = _w_posse()._w_herald();

window._w_pucker = null;

window._w_ire = _w_lint(8e3, 16);

window._w_fleck = {};

window._w_nettle = _w_scrap(4);

window.Worker && (() => {
    const _w_tiff = navigator && navigator.hardwareConcurrency && navigator.hardwareConcurrency > 4 ? 4 : 2;
    const _w_mill = [];
    let _w_yokel = 0;
    for (let i = 0; i < _w_tiff; ++i) {
        const _w_viand = new Worker("./scripts/pHashWorker.js");
        _w_viand.onmessage = _w_wrist;
        _w_mill.push(_w_viand);
    }
    let postMessage = _calc_data => {
        _w_mill[_w_yokel++ % _w_mill.length].postMessage(_calc_data);
    };
    window._w_viand = {
        postMessage: postMessage,
        concurrency: _w_mill.length
    };
})();

chrome.tabs.get(_w_gospel, (function(tab) {
    if (!tab) return;
    window._w_vomit = tab.title;
    window._w_hubbub = tab.url;
    let _w_tundra = function() {
        return _w_vomit + " - " + _w_vomit;
    };
    setInterval((function() {
        document.title = "[" + _w_strut + "/" + _w_cement + "/" + _w_rag.length + "-retry:" + _w_nettle.getProcessingNum() + "/" + _w_nettle.getTaskNum() + "]/" + _w_tundra();
    }), 100);
    window._w_mystic = _w_posse()._w_wraith(_w_gospel);
}));

chrome.tabs.getCurrent((function(tab) {
    window._w_lesion = tab.id;
    $((function() {
        _w_swine(_w_gospel, _w_vagary);
    }));
}));

chrome.runtime.onMessage.addListener((function(message, sender, callback) {
    message && message.type == "_w_hassle" && _w_mystic && _w_mystic.length > 0 && message.extractorHash.length && message.extractorHash == _w_mystic && callback({
        tabId: _w_lesion
    });
    message && message.type == "_w_gallon" && _w_mystic && _w_mystic.length > 0 && message.extractorHash.length && message.extractorHash == _w_mystic && _w_rascal(_w_rag);
    message && message.type == "_w_bale" && _w_mystic && _w_mystic.length > 0 && message.extractorHash.length && message.extractorHash == _w_mystic && _w_aroma(message.ItemIdxMap);
}));

function _w_vagary(_w_galaxy) {
    if (!_w_gospel || _w_gospel.length == 0) {
        $("#ext_main").html(_w_acme("_w_spire"));
        return;
    } else if (_w_gospel == _w_lesion) {
        $("#ext_main").html(_w_acme("_w_slit"));
        return;
    } else if (!_w_posse()._w_grouse[_w_gospel]) {
        $("#ext_main").html(_w_acme("_w_spire"));
        return;
    } else {
        try {
            let _w_crab = new URL(_w_galaxy.url);
            if (!_w_log(_w_crab.href) && !_w_toll(_w_crab.href)) {
                $("#ext_main").html(_w_acme("_w_pier"));
                return;
            }
        } catch (e) {
            console.log("parse url error." + _w_galaxy.url);
            return;
        }
    }
    window._w_mystic = _w_posse()._w_wraith(_w_gospel);
    if (!_w_mystic || _w_mystic.length == 0) {
        $("#ext_main").html(_w_acme("_w_spire"));
        return;
    } else {
        let _w_spell = _w_posse()._w_spell;
        let _w_aspect = setInterval((function updateTabPosition() {
            if (!_w_spell[_w_gospel]) {
                clearInterval(_w_aspect);
            } else if (_w_spell[_w_gospel].extractorHash == _w_mystic) {
                chrome.tabs.get(_w_gospel, (function(tab) {
                    chrome.tabs.getCurrent((function(currentTab) {
                        if (currentTab.windowId != tab.windowId || currentTab.index != tab.index + 1) {
                            let _w_scurvy = tab.windowId;
                            let _w_lien = tab.index + 1;
                            if (_w_scurvy == currentTab.windowId && currentTab.index < tab.index) _w_lien = tab.index;
                            try {
                                chrome.tabs.move(currentTab.id, {
                                    windowId: _w_scurvy,
                                    index: _w_lien
                                }, (function() {}));
                            } catch (exception) {}
                        }
                    }));
                }));
            }
        }), 512);
    }
    let _w_crab = new URL(_w_posse()._w_grouse[_w_gospel].url);
    if (!_w_toll(_w_crab.href)) {
        _w_posse()._w_scoop(_w_gospel, _w_tusk);
    }
    let _w_croak = setTimeout((function() {
        _w_rag = _w_posse()._w_dose(_w_mystic);
        _w_haven = _w_posse()._w_quest(_w_mystic);
        _w_pistol = _w_posse()._w_ridge(_w_mystic);
        _w_rascal(_w_rag);
    }), 512);
    _w_posse()._w_hassle(_w_mystic, (function callback(data) {
        if (data && data.tabId && data.tabId != _w_lesion) {
            clearTimeout(_w_croak);
            chrome.tabs.update(data.tabId, {
                active: true
            }, (function(tab) {
                chrome.windows.update(tab.windowId, {
                    focused: true,
                    drawAttention: true
                }, (function(window) {
                    _w_reward(_w_lesion);
                }));
            }));
        }
    }));
}

function _w_brook(_w_canard, _w_ban) {
    if (_w_ban && $("a[data-idx=" + _w_canard + "]").length == 0) return;
    let _w_foil = _w_rag[_w_canard];
    let _w_gleam = _w_rag[_w_foil].referer;
    if (/^data:image\/svg.*?<svg[^>]+><\/svg>$/i.test(_w_foil)) return;
    _w_fleck[_w_canard] = true;
    let _w_grind = null;
    let _w_acorn = async function() {
        _w_strut--;
        _w_grind && _w_grind.loaded();
        delete _w_fleck[_w_canard];
        this.onload = null;
        this.onabort = null;
        this.onerror = null;
        let _w_chore = this.src;
        let _w_sanity = _w_rag[_w_chore];
        let _w_gleam = _w_sanity.referer;
        if (this.naturalWidth < _w_loon.width || this.naturalHeight < _w_loon.height) return;
        let _w_ramp = await async function() {
            let _w_shroud = null;
            if (/^data:image\/.*?;.*?/i.test(_w_chore)) {
                let match = _w_chore.match(/^data:(image\/.*?);/i);
                _w_shroud = match ? _w_attire[match[1].toLowerCase()] : null;
                return _w_shroud;
            } else if (/^https?:\/\/.*?/i.test(_w_chore)) {
                await Promise.race([ new Promise(((_, reject) => setTimeout((() => reject("timeout!")), 2e3))).catch((() => {})), fetch(_w_chore, {
                    method: "HEAD"
                }).then((resp => resp.blob())).then((blob => {
                    let match = blob.type.match(/^(image\/.*?)$/i);
                    _w_shroud = match ? _w_attire[match[1].toLowerCase()] : null;
                })).catch((() => {})) ]);
            }
            return _w_shroud;
        }();
        let _w_genome = null;
        let _w_parity = null;
        if (_w_chore.match(/^data:(image\/.*?);/i)) {
            _w_genome = _w_ramp;
            _w_parity = _w_hide(32);
        } else {
            let _w_anthem = new URL(_w_chore).pathname;
            _w_parity = _w_anthem.substring(_w_anthem.lastIndexOf("/") + 1);
            _w_genome = _w_parity.lastIndexOf(".") > -1 ? _w_parity.substring(_w_parity.lastIndexOf(".") + 1).toLowerCase() : null;
            _w_parity = _w_parity.substring(0, _w_parity.lastIndexOf("."));
            if (_w_ramp && _w_genome) {
                if (_w_skunk[_w_ramp] != _w_skunk[_w_genome]) {
                    _w_genome = _w_ramp;
                }
            } else if (_w_ramp) {
                _w_genome = _w_ramp;
            } else if (_w_genome) {
                if (_w_archer[_w_genome]) {
                    _w_ramp = _w_archer[_w_genome];
                }
            }
        }
        _w_ramp = _w_archer[_w_ramp] ? _w_archer[_w_ramp] : "other";
        this.title = _w_acme("_w_pirate") + this.naturalWidth + "x" + this.naturalHeight + " / " + _w_acme("_w_beam") + _w_ramp.toUpperCase();
        _w_sanity.title && _w_sanity.title.length > 0 && (this.title += " / Title: " + _w_lark(_w_sanity.title));
        _w_sanity.alt && _w_sanity.alt.length > 0 && _w_sanity.title != _w_sanity.alt && (this.title += " / Alt: " + _w_lark(_w_sanity.alt));
        this.title = this.title;
        this.setAttribute("data-idx", _w_canard);
        let _w_dismay = document.createElement("a");
        const _w_sluice = (new Date).getTime() + _w_script(4);
        _w_dismay.setAttribute("class", "imageItem");
        _w_dismay.setAttribute("data-width", this.naturalWidth);
        _w_dismay.setAttribute("data-height", this.naturalHeight);
        _w_dismay.setAttribute("data-size", this.naturalWidth * this.naturalHeight);
        _w_dismay.setAttribute("data-resolution", this.naturalWidth + "x" + this.naturalHeight);
        _w_dismay.setAttribute("data-id", _w_sluice);
        _w_dismay.setAttribute("data-serial", _w_sanity.serial);
        _w_dismay.setAttribute("data-title", _w_sanity.title ? _w_sanity.title : "");
        _w_dismay.setAttribute("data-alt", _w_sanity.alt ? _w_sanity.alt : "");
        _w_dismay.setAttribute("data-idx", _w_canard);
        _w_dismay.href = _w_chore;
        _w_dismay.title = this.title;
        _w_dismay.setAttribute("data-type", _w_ramp.toLowerCase());
        _w_dismay.setAttribute("data-filename", _w_parity);
        _w_dismay.setAttribute("data-suffix", _w_genome ? "." + _w_genome : ".jpg");
        _w_dismay.setAttribute("data-referer", _w_gleam);
        this.setAttribute("data-referer", _w_gleam);
        let _w_hoard = _w_gleam ? _w_gleam.match(/([\d]+|[^\d]+)/g) : [];
        for (let arrIdx in _w_hoard) if (!isNaN(_w_hoard[arrIdx])) _w_hoard[arrIdx] = _w_snivel(_w_hoard[arrIdx], 128);
        _w_collar[_w_canard] = _w_hoard;
        _w_dismay.setAttribute("data-maxRange", "other");
        for (let i in _w_ploy) {
            if (isNaN(i)) continue;
            let _w_scope = _w_ploy[i];
            let _w_duel = _w_ploy[_w_scope];
            this.naturalWidth - _w_duel.width >= 0 && this.naturalHeight - _w_duel.height >= 0 && _w_dismay.setAttribute("data-maxRange", _w_scope);
        }
        this.setAttribute("data-src", _w_chore);
        _w_dismay.setAttribute("data-src", _w_chore);
        _w_dismay.appendChild(this);
        $(_w_dismay).append($("<div />", {
            class: "imageItemResolution",
            text: this.naturalWidth + "x" + this.naturalHeight
        }));
        let _w_kernel = false;
        let _w_troupe = this.naturalWidth * this.naturalHeight;
        let _w_anvil = window._w_hangar["serial_" + _w_sanity.serial];
        if (_w_anvil && _w_anvil > _w_troupe) {
            return;
        } else if ($("a[data-serial=" + _w_sanity.serial + "][data-idx!=" + _w_canard + "]").length > 0) {
            let $_w_levity = $("a[data-serial=" + _w_sanity.serial + "][data-idx!=" + _w_canard + "]");
            if ($_w_levity.is(".selected")) _w_kernel = true;
            $_w_levity.remove();
        }
        window._w_hangar["serial_" + _w_sanity.serial] = _w_troupe;
        {
            let $_w_cavern = $("a[data-idx=" + _w_canard + "]");
            if ($_w_cavern.length > 0) {
                if ($_w_cavern.is(".selected")) _w_kernel = true;
                if (_w_kernel) _w_dismay.classList.add("selected");
                $_w_cavern.replaceWith(_w_dismay);
            } else {
                $("#empty").remove();
                $("#ext_main").append(_w_dismay);
            }
        }
        if (this.naturalWidth > this.naturalHeight) {
            this.style.width = this.naturalWidth + "px";
        } else {
            this.style.height = this.naturalHeight + "px";
        }
        this.style.maxWidth = this.getBoundingClientRect().width + "px";
        this.style.maxHeight = this.getBoundingClientRect().height + "px";
        _w_dismay.style.lineHeight = this.getBoundingClientRect().height - 4 + "px";
        _w_dismay.style.width = _w_dismay.getBoundingClientRect().width + "px";
        _w_dismay.style.height = _w_dismay.getBoundingClientRect().height + "px";
        this.style.width = null;
        this.style.height = null;
        $(_w_dismay).colorbox({
            rel: "imageItem",
            photo: true,
            maxWidth: "99%",
            maxHeight: function() {
                return localStorage["verticalViewMode"] != "fullView" ? "99%" : "";
            },
            top: function() {
                let $_w_iodine = $.colorbox.element();
                let _w_scare = window.innerHeight - (parseInt($_w_iodine.attr("data-height")) + 32);
                if (_w_scare > 0) {
                    return _w_scare / 2;
                } else {
                    return 0;
                }
            },
            slideshow: true,
            slideshowAuto: false,
            slideshowSpeed: 5e3,
            href: function() {
                let _w_fell = $(this).attr("href");
                let _w_span = $(this).children(":first-child").attr("data-src");
                return _w_log(_w_span) ? _w_span : _w_fell;
            },
            onOpen: function() {
                let $_w_iodine = $.colorbox.element();
                if ($_w_iodine.hasClass("preview_ignore_configure")) {
                    $("#colorbox").show();
                } else if ("true" == _w_posse()._w_trophy()) {
                    $_w_iodine.hasClass("selected") ? $_w_iodine.removeClass("selected") : $_w_iodine.addClass("selected");
                    _w_plague("#ext_main>.imageItem", true, false);
                    $.colorbox.close();
                    $("#colorbox").hide();
                } else {
                    $("#colorbox").show();
                }
                $_w_iodine.removeClass("preview_ignore_configure");
            },
            onCleanup: function() {},
            onComplete: function() {
                $("#cboxTitle").each((function() {
                    $("#cboxTitle").attr("style", "width:" + ($(this).parent().width() - 90) + "px; white-space:nowrap; overflow:hidden;");
                    $("#cboxContent").attr("title", _w_acme("_w_con"));
                }));
                _w_oath();
            }
        });
        let _w_dome = _w_posse()._w_donor() & 16;
        if (_w_dome) {
            const imgBitmap = await createImageBitmap(this);
            window._w_viand.postMessage({
                id: _w_sluice,
                src: _w_chore,
                imgBitmap: typeof OffscreenCanvas == "undefined" ? null : imgBitmap
            });
        }
        if (!_w_alert(this) && $("#ext_main>.imageItem:visible").length > _w_posse()._w_cachet()) {
            this.src = _w_cue;
        }
        _w_sonnet("#ext_main>.imageItem");
        $("#image_amount").html($(".imageItem").length);
    };
    {
        let _w_prude = {};
        _w_prude[_w_foil] = _w_gleam;
        _w_posse()._w_knack(_w_prude, _w_lesion, true);
    }
    let _w_timbre = /^https?:/.test(_w_foil) ? 4 : 0;
    let _w_mantle = new Image;
    _w_mantle.onload = _w_acorn;
    _w_mantle.onerror = function() {
        _w_strut--;
        if (!_w_grind.isTimeout() && _w_timbre-- > 0) {
            let _w_span = _w_mantle.src;
            let _w_decree = function() {
                setTimeout((function() {
                    _w_strut++;
                    _w_mantle.src = _w_span;
                }), 2e3);
            };
            _w_nettle.addTask((function(_w_accord, _w_sash) {
                _w_accord();
                function clearReferer(_w_score) {
                    let _w_prude = {};
                    _w_prude[_w_score] = null;
                    _w_posse()._w_knack(_w_prude, _w_lesion, true);
                }
                $.ajax({
                    method: "get",
                    timeout: _w_clout,
                    headers: {
                        Accept: "*/*; charset=UTF-8",
                        "Cache-Control": "no-cache, no-store, must-revalidate, max-age=0, post-check=0, pre-check=0",
                        Pragma: "no-cache",
                        Expires: "0"
                    },
                    url: _w_span
                }).done((function(data, status, xhr) {
                    _w_grind && _w_grind.loaded();
                    _w_timbre = -1;
                    if (xhr.status == 200 && data.match && data.match(/(html|script|style|head|body)/i) != null) {
                        clearReferer(_w_span);
                    }
                    _w_decree();
                })).fail((function(xhr) {
                    _w_grind && _w_grind.loaded();
                    _w_timbre = -1;
                    if (xhr.status == 404) {
                        delete _w_fleck[_w_canard];
                    } else if (xhr.status == 403) {
                        clearReferer(_w_span);
                        _w_decree();
                    } else {
                        _w_decree();
                    }
                })).always(_w_sash);
            }));
        }
    };
    _w_mantle.onabort = _w_mantle.onerror;
    if (_w_ire.bypassUrl(_w_foil)) {
        _w_strut++;
        _w_grind = _w_ire.directSetImgSrc(_w_mantle, _w_foil);
    } else {
        _w_strut++;
        _w_grind = _w_ire.setImgSrc(_w_mantle, _w_foil);
    }
}

function _w_fealty(url) {
    let _w_sandal = url;
    let _w_stench = _w_haven[url];
    if (_w_stench && _w_stench.title && _w_stench.title.length > 0) {
        _w_sandal = _w_stench.title;
    }
    return _w_sandal;
}

function _w_rascal(_w_rag) {
    for (;_w_cement < _w_rag.length; ) {
        if (_w_strut >= _w_verge) {
            break;
        } else if ($("a.imageItem").length >= _w_jazz - _w_strut) {
            break;
        } else {
            _w_brook(_w_cement++, false);
        }
    }
}

setInterval((function() {
    _w_rascal(_w_rag);
}), 500);

function _w_aroma(_w_stance) {
    for (let idx in _w_stance) {
        if (!_w_rag) continue;
        _w_brook(idx, true);
    }
}

$((function() {
    document.title = _w_acme("_w_ploy");
    $.extend($.colorbox.settings, {
        current: _w_acme("_w_hack"),
        previous: _w_acme("_w_slosh"),
        next: _w_acme("_w_strip"),
        close: _w_acme("_w_credo"),
        xhrError: _w_acme("_w_swarm"),
        imgError: _w_acme("_w_mitten"),
        slideshowStart: _w_acme("_w_scoop"),
        slideshowStop: _w_acme("_w_brim")
    });
}));

$((function() {
    $(window).bind("scroll resize", (function() {
        _w_plague("#ext_main>.imageItem", false, false);
    }));
}));

$((function() {}));

window.selectParam = {
    timeout: 128,
    lastExeTime: new Date,
    timer: null,
    updateStatics: true,
    delayAgain: false
};

$((function() {
    $("body").on("mousedown", (function dnFun(dnEvent) {
        if (dnEvent.button == 0) {
            let $_w_hoist = $(dnEvent.target);
            if (!$_w_hoist.is("#ext_main") && $_w_hoist.parents("#ext_main").length <= 0) {
                return;
            } else if ($(".modal-dialog").is(":visible") || $("#colorbox").is(":visible")) {
                return;
            }
            dnEvent.preventDefault();
            $(".selectorDiv").remove();
            $(this).off("mousemove");
            $(this).off("mouseup");
            let $_w_icicle = $("<div />", {
                class: "selectorDiv"
            });
            $(this).append($_w_icicle);
            let _w_colt, mvFun, moveStep = 0;
            $(this).on("mousemove", mvFun = function(mvEvent) {
                let _w_auger = {};
                _w_auger.x1 = dnEvent.pageX <= mvEvent.pageX ? dnEvent.pageX : mvEvent.pageX;
                _w_auger.y1 = dnEvent.pageY <= mvEvent.pageY ? dnEvent.pageY : mvEvent.pageY;
                _w_auger.x2 = dnEvent.pageX >= mvEvent.pageX ? dnEvent.pageX : mvEvent.pageX;
                _w_auger.y2 = dnEvent.pageY >= mvEvent.pageY ? dnEvent.pageY : mvEvent.pageY;
                if (++moveStep == 1 || _w_auger.x2 - _w_auger.x1 < 4 && _w_auger.y2 - _w_auger.y1 < 4) return true;
                _w_armada(selectParam, (function() {
                    $_w_icicle.css("z-index", "1020").css("border", "2px solid #3399ff").css("background-color", "rgba(51, 153, 255, 0.2)").css("position", "absolute").css("left", _w_auger.x1 + "px").css("top", _w_auger.y1 + "px").css("width", _w_auger.x2 - _w_auger.x1 + "px").css("height", _w_auger.y2 - _w_auger.y1 + "px");
                    $(".imageItem").each((function() {
                        let _w_flange = $(this).get(0);
                        let _w_dike = {
                            x1: _w_flange.offsetLeft,
                            y1: _w_flange.offsetTop,
                            x2: _w_flange.offsetLeft + _w_flange.offsetWidth,
                            y2: _w_flange.offsetTop + _w_flange.offsetHeight
                        };
                        if (_w_dike.y2 < _w_auger.y1 || _w_dike.x2 < _w_auger.x1 || _w_dike.y1 > _w_auger.y2 || _w_dike.x1 > _w_auger.x2) {
                            $(this).removeClass("preSelect preUnSelect");
                        } else {
                            $(this).hasClass("selected") ? $(this).addClass("preUnSelect") : $(this).addClass("preSelect");
                        }
                    }));
                }), false);
            }).on("mouseup", _w_colt = function(upEvent) {
                if (upEvent.button == 0) {
                    $(this).off("mousemove", mvFun);
                    $(this).off("mouseup", _w_colt);
                    $_w_icicle.remove();
                    $(".imageItem").removeClass("preSelect preUnSelect");
                    let _w_auger = {};
                    _w_auger.x1 = dnEvent.pageX <= upEvent.pageX ? dnEvent.pageX : upEvent.pageX;
                    _w_auger.y1 = dnEvent.pageY <= upEvent.pageY ? dnEvent.pageY : upEvent.pageY;
                    _w_auger.x2 = dnEvent.pageX >= upEvent.pageX ? dnEvent.pageX : upEvent.pageX;
                    _w_auger.y2 = dnEvent.pageY >= upEvent.pageY ? dnEvent.pageY : upEvent.pageY;
                    if (moveStep == 1 || _w_auger.x2 - _w_auger.x1 < 4 && _w_auger.y2 - _w_auger.y1 < 4) return true;
                    $(upEvent.target).one("click", (function(event) {
                        event.preventDefault();
                        event.stopPropagation();
                    }));
                    _w_armada(selectParam, (function() {
                        $(".imageItem").each((function() {
                            let _w_flange = $(this).get(0);
                            let _w_dike = {
                                x1: _w_flange.offsetLeft,
                                y1: _w_flange.offsetTop,
                                x2: _w_flange.offsetLeft + _w_flange.offsetWidth,
                                y2: _w_flange.offsetTop + _w_flange.offsetHeight
                            };
                            if (_w_dike.y2 < _w_auger.y1 || _w_dike.x2 < _w_auger.x1 || _w_dike.y1 > _w_auger.y2 || _w_dike.x1 > _w_auger.x2) {} else {
                                $(this).hasClass("selected") ? $(this).removeClass("selected") : $(this).addClass("selected");
                            }
                        }));
                        _w_plague("#ext_main>.imageItem", true, false);
                    }), false);
                }
            });
        }
    }));
}));

function _w_hulk() {
    $(".imageItem:visible").addClass("selected");
    _w_oath();
    _w_plague("#ext_main>.imageItem", true, true);
}

function _w_leak() {
    $(".imageItem:visible").removeClass("selected");
    _w_oath();
    _w_plague("#ext_main>.imageItem", true, true);
}

function _w_mirth() {
    let $_w_bane = $(".imageItem:visible.selected");
    let $_w_welter = $(".imageItem:visible:not(.selected)");
    $_w_bane.removeClass("selected");
    $_w_welter.addClass("selected");
    _w_oath();
    _w_plague("#ext_main>.imageItem", true, true);
}

function _w_usury() {
    if ($("#cboxLoadedContent").length > 0) {
        let _w_forge = $("#cboxLoadedContent img").attr("src");
        $.colorbox.element().remove();
        $.colorbox.next();
        $(".imageItem:visible").length == 0 && $.colorbox.close();
    } else {
        $(".selected:visible").remove();
        _w_oath();
    }
    _w_plague("#ext_main>.imageItem", true, true);
}

function _w_cargo() {
    $(".imageItem:hidden").remove();
    _w_oath();
    _w_plague("#ext_main>.imageItem", true, true);
}

function _w_plume() {
    if ($(".selected:visible").length > 0) {
        $(".imageItem:not(.selected):visible").remove();
        _w_oath();
        _w_plague("#ext_main>.imageItem", true, true);
    }
}

function _w_slant() {
    $(".imageItem.selected:visible").remove();
    $(".imageItem:visible").addClass("selected");
    let _w_cub = new Array;
    $(".imageItem.selected:visible").each((function() {
        _w_cub.push({
            name: "",
            url: $(this).attr("href"),
            referer: $(this).attr("data-referer"),
            serial: $(this).attr("data-serial"),
            filename: $(this).attr("data-filename"),
            suffix: $(this).attr("data-suffix")
        });
    }));
    return _w_cub;
}

function _w_prune() {
    let _w_cub = new Array;
    if ($("#cboxLoadedContent").length > 0) {
        let $item = $.colorbox.element();
        _w_grief($item.attr("data-src"), $item.attr("data-filename"), $item.attr("data-suffix"));
    } else {
        $(".selected:visible").each((function() {
            _w_cub.push({
                name: "",
                url: $(this).attr("href"),
                referer: $(this).attr("data-referer"),
                serial: $(this).attr("data-serial"),
                filename: $(this).attr("data-filename"),
                suffix: $(this).attr("data-suffix")
            });
        }));
    }
    _w_boon(_w_cub, _w_vomit, _w_slant);
}

function _w_toupee() {
    let _w_cub = new Array;
    $(".imageItem:visible").each((function() {
        _w_cub.push({
            name: "",
            url: $(this).attr("href"),
            referer: $(this).attr("data-referer"),
            serial: $(this).attr("data-serial"),
            filename: $(this).attr("data-filename"),
            suffix: $(this).attr("data-suffix")
        });
    }));
    _w_boon(_w_cub, _w_vomit, _w_slant);
}

function _w_assay() {
    let _w_lancet = function() {
        $("#pullywood_production").popover({
            title: "<span class='glyphicon glyphicon-info-sign'></span> " + _w_acme("_w_gaggle"),
            content: _w_acme("_w_locust"),
            placement: "auto",
            html: true
        }).popover("show").next().on("click", (function() {
            $(this).popover("destroy");
        })).prev().on("mouseover", (function() {
            $(this).popover("destroy");
        }));
    };
    $.ajax({
        method: "get",
        url: _w_whiff,
        dataType: "json",
        contentType: "application/json",
        mimeType: "application/json"
    }).done((function(data) {
        if (data && data.version) {
            _w_gravel();
        } else if (data && !data.version) {
            alert(_w_acme("_w_bulk"));
        } else {
            _w_lancet();
        }
    })).fail((function() {
        _w_lancet();
    }));
}

function _w_gravel() {
    let _w_cub = $(".imageItem.selected:visible").toArray();
    if (_w_cub.length <= 0) {
        alert(_w_acme("_w_sprout"));
        return;
    }
    let $_w_salve = $("<div />", {
        id: "add_favorite_dlg",
        class: "modal fade",
        role: "dialog"
    });
    let $_w_smirch = $("<div />", {
        class: "modal-dialog"
    });
    let $_w_aspen = $("<div />", {
        class: "modal-content"
    });
    let $_w_tenure = $("<div />", {
        class: "modal-header"
    });
    let $_w_norm = $("<h4 />", {
        class: "modal-title"
    });
    let $_w_cellar = $("<div />", {
        class: "modal-body"
    });
    let $_w_ruse = $("<div />", {
        class: "modal-footer"
    });
    $_w_tenure.append($_w_norm);
    $_w_aspen.append($_w_tenure);
    $_w_aspen.append($_w_cellar);
    $_w_aspen.append($_w_ruse);
    $_w_smirch.append($_w_aspen);
    $_w_salve.append($_w_smirch);
    $_w_norm.append($("<span />", {
        class: "glyphicon glyphicon-folder-open"
    })).append(_w_acme("_w_signal"));
    let $_w_gem = $("<div />", {
        class: "alert alert-info",
        html: _w_acme("_w_aviary") + _w_cub.length
    });
    $_w_cellar.append($_w_gem);
    let $_w_mortar = $("<input />", {
        type: "checkbox",
        name: "continuousSwitch"
    });
    $_w_ruse.append($("<span />", {
        class: "continuousSwitchContainer"
    }).append($_w_mortar));
    $_w_mortar.bootstrapSwitch({
        labelText: _w_acme("_w_cygnet")
    });
    let $_w_dotage = $("<button />", {
        class: "btn btn-primary",
        disabled: false,
        text: _w_acme("_w_pact")
    });
    $_w_dotage.prepend($("<span />", {
        class: "glyphicon glyphicon-floppy-open"
    }));
    $_w_dotage.on("click", (function() {
        $_w_dotage.attr("disabled", true);
        let _w_baron = $_w_mortar.is(":checked");
        let _w_effigy = _w_cub;
        _w_cub = new Array;
        let _w_panic = [];
        _w_effigy.forEach((function(taskItem) {
            _w_panic.push({
                src: $(taskItem).attr("data-src"),
                referer: $(taskItem).attr("data-referer"),
                description: typeof $(taskItem).attr("title") != "undefined" ? $(taskItem).attr("title").replace(/分辨率:\s\d+x\d+\s\/\s类型:\s[a-zA-Z0-9]+(\s\/\s)?/gi, "") : "",
                width: $(taskItem).attr("data-width"),
                height: $(taskItem).attr("data-height"),
                extHash: _w_mystic,
                serial: $(taskItem).attr("data-serial")
            });
        }));
        $_w_gem.text(_w_acme("_w_swipe"));
        if (!$_w_salve.is(":visible")) {
            return;
        }
        $.ajax({
            method: "post",
            url: _w_pedal,
            data: JSON.stringify(_w_panic),
            dataType: "json",
            contentType: "application/json",
            mimeType: "application/json"
        }).done((function(result) {
            $_w_gem.text(_w_acme("_w_bedlam"));
        })).fail((function(a, b, c) {
            console.log("fialed", a, b, c);
            _w_cub = _w_effigy;
            let $_w_heed = $_w_dotage.children().first();
            $_w_dotage.text(_w_acme("_w_balm")).prepend($_w_heed).attr("disabled", _w_baron ? true : false);
            $_w_gem.text(_w_acme("_w_gleam"));
        })).always((function() {
            if (_w_baron) {
                let _w_biped = 500;
                if (_w_cub.length > 0) {
                    console.log(JSON.stringify(_w_cub));
                    $_w_gem.text(_w_acme("_w_woe"));
                } else {
                    $(".imageItem.selected:visible").remove();
                    $_w_gem.text(_w_acme("_w_cane"));
                }
                let _w_slaver = setInterval((function continuousAdd() {
                    if (_w_cub.length == 0) {
                        _w_hulk();
                        _w_cub = $(".imageItem.selected:visible").toArray();
                    }
                    if (_w_cub && _w_cub.length > 0) {
                        clearInterval(_w_slaver);
                        $_w_dotage.click();
                    }
                }), _w_biped);
            } else {
                $(this).remove();
            }
        }));
    }));
    $_w_ruse.append($_w_dotage);
    let $_w_patina = $("<button />", {
        class: "btn btn-default",
        "data-dismiss": "modal",
        text: _w_acme("_w_trophy")
    });
    $_w_patina.prepend($("<span />", {
        class: "glyphicon glyphicon-remove"
    }));
    $_w_patina.on("click", (function() {}));
    $_w_ruse.append($_w_patina);
    $_w_salve.modal({
        backdrop: "static",
        keyboard: false
    }).on("hidden.bs.modal", (function() {
        $(this).remove();
    }));
}

function _w_ogle() {
    if ($("#cboxLoadedContent").length > 0) {
        $("#colorbox").addClass("colorboxSelect");
        $(".imageItem:not(.selected):visible[href='" + $("#cboxLoadedContent img").attr("src") + "']").addClass("selected");
    }
}

function _w_pastry() {
    if ($("#cboxLoadedContent").length > 0) {
        $("#colorbox").removeClass("colorboxSelect");
        $(".imageItem.selected:visible[href='" + $("#cboxLoadedContent img").attr("src") + "']").removeClass("selected");
    }
}

function _w_oath() {
    if ($("#cboxLoadedContent").length > 0) {
        $("#colorbox").removeClass("colorboxSelect");
        $.colorbox.element().is(".selected:visible") && $("#colorbox").addClass("colorboxSelect");
    }
}

function _w_gusher() {
    let _w_scion = {
        0: 1,
        1: 3,
        3: 2,
        2: 0
    };
    let _w_blurb = _w_posse()._w_donor();
    let _w_sonata = _w_scion[_w_blurb];
    "undefined" == typeof _w_sonata && (_w_sonata = 3);
    _w_posse()._w_ranger(_w_sonata);
    _w_plague("#ext_main>.imageItem", true, false);
}

function _w_spat() {
    let _w_sonata = _w_posse()._w_donor();
    if (_w_sonata & 4) {
        _w_sonata &= ~4;
    } else {
        _w_sonata |= 4;
    }
    _w_posse()._w_ranger(_w_sonata);
    _w_sonnet("#ext_main>.imageItem");
    _w_plague("#ext_main>.imageItem", true, false);
}

function _w_nicest() {
    let _w_sonata = _w_posse()._w_donor();
    if (_w_sonata & 8) {
        _w_sonata &= ~8;
    } else {
        _w_sonata |= 8;
    }
    _w_posse()._w_ranger(_w_sonata);
    _w_plague("#ext_main>.imageItem", true, false);
}

function _w_clip() {
    let _w_sonata = _w_posse()._w_donor();
    if (_w_sonata & 16) {
        _w_sonata &= ~16;
    } else {
        _w_sonata |= 16;
        document.querySelectorAll("a.imageItem:not([data-phash])").forEach((item => {
            _w_brook(item.dataset.idx, true);
        }));
    }
    _w_posse()._w_ranger(_w_sonata);
    _w_plague("#ext_main>.imageItem", true, false);
}

function _w_carafe() {
    if ($("#colorbox").is(":visible")) {
        if (localStorage["verticalViewMode"] != "fullView") {
            localStorage["verticalViewMode"] = "fullView";
        } else {
            localStorage["verticalViewMode"] = "";
        }
        $.colorbox.element().colorbox({
            open: true
        });
    }
}

function _w_demise() {
    chrome.tabs.get(_w_gospel, (function(tab) {
        if (tab && _w_hubbub == tab.url) {
            chrome.tabs.remove(tab.id, (function() {
                window.close();
            }));
        } else {
            window.close();
        }
    }));
}

$((function() {
    let $_w_havoc = $("<div>", {
        class: "btn-group btn-group-xs"
    });
    let $_w_mode = $("<a />", {
        target: "_configure_",
        class: "btn btn-pwd",
        href: "options.html",
        text: _w_acme("_w_pillar")
    });
    $_w_mode.prepend($("<span />", {
        class: "glyphicon glyphicon-wrench"
    }));
    let $_w_simile = $("<a />", {
        target: "_configure_",
        class: "btn btn-home",
        href: "options.html?showMsg=about",
        text: _w_acme("_w_script")
    });
    $_w_simile.prepend($("<span />", {
        class: "glyphicon glyphicon-copyright-mark"
    }));
    let $_w_shale = $("<a />", {
        target: "_imageAssistant_favorite",
        id: "_cxyz_fav_",
        class: "btn btn-primary",
        href: "./favorite.html",
        text: _w_acme("_w_vermin")
    });
    $_w_shale.prepend($("<span />", {
        class: "glyphicon glyphicon-folder-open"
    }));
    let $_w_idol = $("<a />", {
        target: "_pullywood_production_",
        class: "btn btn-home",
        href: "http://www.pullywood.com",
        text: _w_acme("_w_plight")
    });
    $_w_idol.prepend($("<span />", {
        class: "glyphicon glyphicon-home"
    }));
    $_w_havoc.append($_w_mode).append($_w_simile).append($_w_shale).append($_w_idol);
    $("#pullywood_production").append($_w_havoc);
    let _w_panic = [ {
        name: _w_acme("_w_pecan"),
        showText: true,
        className: "mainMenuItem",
        iconClass: "glyphicon glyphicon-refresh",
        fun: function() {
            $("#ext_main .imageItem").remove();
            _w_cement = 0;
            _w_rascal(_w_rag);
        }
    }, {
        name: _w_acme("_w_defile"),
        showText: true,
        className: "mainMenuItem",
        iconClass: "glyphicon glyphicon-align-justify",
        fun: _w_hulk
    }, {
        name: "",
        showText: false,
        className: "mainMenuItem",
        iconClass: "glyphicon glyphicon-collapse-down",
        fun: function() {},
        subMenu: [ {
            name: _w_acme("_w_ford"),
            showText: true,
            className: "mainMenuItem",
            iconClass: "glyphicon glyphicon-list",
            fun: _w_leak
        }, {
            name: _w_acme("_w_credit"),
            showText: true,
            className: "mainMenuItem",
            iconClass: "glyphicon glyphicon-retweet",
            fun: _w_mirth
        } ]
    }, {
        name: _w_acme("_w_array"),
        showText: true,
        className: "mainMenuItem",
        iconClass: "glyphicon glyphicon-trash",
        fun: _w_usury
    }, {
        name: "",
        showText: false,
        className: "mainMenuItem",
        iconClass: "glyphicon glyphicon-collapse-down",
        fun: function() {},
        subMenu: [ {
            name: _w_acme("_w_knave"),
            className: "mainMenuItem",
            iconClass: "glyphicon glyphicon-remove",
            fun: _w_cargo
        }, {
            name: _w_acme("_w_slot"),
            showText: true,
            className: "mainMenuItem",
            iconClass: "glyphicon glyphicon-log-in",
            fun: _w_plume
        } ]
    }, {
        name: _w_acme("_w_digit"),
        showText: true,
        className: "mainMenuItem",
        iconClass: "glyphicon glyphicon-cloud-download",
        fun: _w_prune
    }, {
        name: "",
        showText: false,
        className: "mainMenuItem",
        iconClass: "glyphicon glyphicon-collapse-down",
        fun: function() {},
        subMenu: [ {
            name: _w_acme("_w_drone"),
            showText: true,
            className: "mainMenuItem",
            iconClass: "glyphicon glyphicon-download",
            fun: _w_toupee
        } ]
    }, {
        name: _w_acme("_w_heed"),
        showText: true,
        className: "mainMenuItem",
        iconClass: "glyphicon glyphicon-folder-open",
        fun: _w_enmity
    }, {
        name: _w_acme("_w_iodine"),
        showText: true,
        className: "mainMenuItem",
        iconClass: "glyphicon glyphicon-folder-open",
        fun: _w_assay
    } ];
    let $_w_breach = $("#select_menu");
    $_w_breach.addClass("container btn-group btn-group-sm");
    $_w_breach.attr("role", "group");
    for (let i in _w_panic) {
        if (isNaN(i)) continue;
        let _w_duel = _w_panic[i];
        if (_w_duel.subMenu) {
            let $_w_clay = $("<div />", {
                class: "btn-group btn-group-sm",
                role: "group"
            });
            let $_w_safe = $("<button />", {
                type: "button",
                class: "btn btn-default dropdown-toggle",
                "data-toggle": "dropdown",
                "aria-expanded": "false"
            });
            $_w_safe.append($("<span />", {
                class: "caret"
            }));
            $_w_clay.append($_w_safe);
            $_w_breach.append($_w_clay);
            let $_w_blotch = $("<ul />", {
                class: "dropdown-menu dropdown-menu-right",
                role: "menu"
            });
            for (let j in _w_duel.subMenu) {
                if (isNaN(j)) continue;
                let _w_flak = _w_duel.subMenu[j];
                let $_w_strife = $("<li />");
                let $_w_dismay = $("<a />", {
                    href: "#"
                });
                $_w_dismay.append($("<span />", {
                    class: _w_flak.iconClass,
                    "aria-hidden": true
                }));
                $_w_dismay.append(" " + _w_flak.name);
                $_w_strife.append($_w_dismay);
                $_w_blotch.append($_w_strife);
                $_w_dismay.on("click", _w_flak.fun);
            }
            $_w_clay.append($_w_blotch);
            $_w_breach.append($_w_clay);
        } else {
            let $_w_safe = $("<button />", {
                class: "btn btn-default"
            });
            $_w_safe.append($("<span />", {
                class: _w_duel.iconClass,
                "aria-hidden": true
            }));
            _w_duel.showText && $_w_safe.append(" " + _w_duel.name);
            $_w_breach.append($_w_safe);
            $_w_safe.on("click", _w_duel.fun);
        }
    }
    $(document).on("keydown", (function(e) {
        if (_w_timber(e)) return;
        if ($("#download_confirm_dlg").length > 0) return true;
        e.which == 88 && e.shiftKey && e.altKey && _w_demise();
        e.which == 86 && e.ctrlKey == false && e.altKey == false && _w_carafe();
        e.which == 68 && e.ctrlKey && _w_prune() & e.preventDefault();
        e.which == 109 && !e.ctrlKey && _w_pastry() & e.preventDefault();
        e.which == 107 && !e.ctrlKey && _w_ogle() & e.preventDefault();
        (e.which == 46 || e.which == 110) && _w_usury() & e.preventDefault();
        if ($("#cboxOverlay, .modal-dialog").is(":visible")) return true;
        e.which == 65 && e.ctrlKey && _w_hulk() & e.preventDefault();
        e.which == 90 && e.ctrlKey && _w_leak() & e.preventDefault();
        e.which == 82 && e.ctrlKey && _w_mirth() & e.preventDefault();
        e.which == 83 && e.ctrlKey && _w_plume() & e.preventDefault();
        e.which == 70 && e.ctrlKey && _w_assay() & e.preventDefault();
        e.which == 77 && e.altKey && _w_gusher() & e.preventDefault();
        e.which == 83 && e.altKey && _w_spat() & e.preventDefault();
        e.which == 84 && e.altKey && _w_nicest() & e.preventDefault();
        e.which == 72 && e.altKey && _w_clip() & e.preventDefault();
    }));
    let _w_ladle = [ {
        name: _w_acme("_w_argot"),
        showText: true,
        className: "imageContextMenuURLE",
        iconClass: "glyphicon glyphicon-picture",
        fun: function() {
            let _w_moron = $(this).attr("data-id");
            $(".imageItem[data-id='" + _w_moron + "']").addClass("preview_ignore_configure").trigger("click");
        }
    }, {
        name: _w_acme("_w_pastor"),
        showText: true,
        className: "imageContextMenuURLE",
        iconClass: "glyphicon glyphicon-download",
        fun: function() {
            let _w_forge = $(this).attr("data-src");
            _w_grief(_w_forge, $(this).attr("data-filename"), $(this).attr("data-suffix"));
        }
    }, {
        name: _w_acme("_w_dwarf"),
        showText: true,
        className: "imageContextMenuURL",
        iconClass: "glyphicon glyphicon-qrcode",
        fun: function() {
            $(this).attr("data-src") && _w_mallet($(this).attr("data-src"), true);
        }
    }, {
        name: _w_acme("_w_lumber"),
        showText: true,
        className: "imageContextMenuURLE",
        iconClass: "glyphicon glyphicon-edit",
        fun: function() {
            $(this).attr("data-src") && _w_posse()._w_bazaar($(this).attr("data-src"), $(this).attr("data-referer"), -1);
        }
    }, {
        name: _w_acme("_w_dismay"),
        showText: true,
        className: "imageContextMenuURLE",
        iconClass: "glyphicon glyphicon-picture",
        fun: function() {
            $(this).attr("data-src") && _w_posse()._w_schism($(this).attr("data-src"), $(this).attr("data-referer"), -1);
        }
    }, {
        name: _w_acme("_w_heir"),
        showText: true,
        className: "imageContextMenuURL",
        iconClass: "fab fa-google",
        fun: function() {
            $(this).attr("data-src") && _w_weed($(this).attr("data-src"));
        }
    }, {
        name: _w_acme("_w_pore"),
        showText: true,
        className: "imageContextMenuURL",
        iconClass: "fab fa-google",
        fun: function() {
            $(this).attr("data-src") && _w_tread($(this).attr("data-src"));
        }
    }, {
        name: _w_acme("_w_lust"),
        showText: true,
        className: "imageContextMenuURL",
        iconClass: "fab fa-google",
        fun: function() {
            $(this).attr("data-src") && _w_chant($(this).attr("data-src"));
        }
    }, {
        name: _w_acme("_w_chant"),
        showText: true,
        className: "imageContextMenuURL",
        iconClass: "fa fa-paw",
        fun: function() {
            $(this).attr("data-src") && _w_toil($(this).attr("data-src"));
        }
    }, {
        name: _w_acme("_w_dose"),
        showText: true,
        className: "imageContextMenuURL",
        iconClass: "fa fa-paw",
        fun: function() {
            $(this).attr("data-src") && _w_regime($(this).attr("data-src"));
        }
    } ];
    _w_panic = _w_panic.slice(0, 0).concat(_w_ladle).concat(_w_panic.slice(0));
    let $_w_qualm = $("<ul />", {
        class: "dropdown-menu",
        role: "menu",
        style: "z-index:9999;"
    });
    for (let i in _w_panic) {
        if (isNaN(i)) continue;
        let _w_duel = _w_panic[i];
        if (_w_duel.subMenu) {
            let _w_parka = _w_duel.subMenu;
            for (let j in _w_parka) {
                if (isNaN(j)) continue;
                let _w_flak = _w_parka[j];
                let $_w_fathom = $("<li />", {
                    role: "presentation"
                });
                let $_w_frieze = $("<a />", {
                    role: "menuitem",
                    class: _w_flak.className,
                    tabIndex: "-1",
                    href: "#"
                });
                $_w_frieze.append($("<span />", {
                    class: _w_flak.iconClass
                }));
                $_w_frieze.append(" " + _w_flak.name);
                $_w_fathom.append($_w_frieze);
                $_w_qualm.append($_w_fathom);
                $_w_frieze.on("click", _w_flak.fun);
            }
            continue;
        }
        let $_w_fathom = $("<li />", {
            role: "presentation"
        });
        let $_w_frieze = $("<a />", {
            role: "menuitem",
            class: _w_duel.className,
            tabIndex: "-1",
            href: "#"
        });
        $_w_frieze.append($("<span />", {
            class: _w_duel.iconClass
        }));
        $_w_frieze.append(" " + _w_duel.name);
        $_w_fathom.append($_w_frieze);
        $_w_frieze.on("click", _w_duel.fun);
        $_w_qualm.append($_w_fathom);
    }
    $_w_qualm.on("mousedown mousemove mouseup click", (function(e) {}));
    $("body").append($_w_qualm);
    $_w_qualm.dropdown();
    $_w_qualm.hide();
    $(document).on("keydown scroll", (function(e) {
        (e.which == 27 || e.type == "scroll") && $_w_qualm.fadeOut("fast");
    }));
    $("html").on("click", (function(e) {
        $_w_qualm.fadeOut("fast");
    }));
    $_w_qualm.on("click", (function(e) {
        $_w_qualm.fadeOut("fast");
    }));
    $(document).on("contextmenu", (function(e) {
        $(".context_menu").hide();
        $_w_qualm.hide();
        if ($(".modal-dialog").is(":visible") || $("#colorbox").is(":visible")) {
            return true;
        }
        let $_w_entrée = $(e.target.parentElement);
        if ($_w_entrée && $_w_entrée.hasClass("imageItem")) {
            $(".imageContextMenuURLE").attr("data-src", $_w_entrée.attr("data-src")).attr("data-filename", $_w_entrée.attr("data-filename")).attr("data-suffix", $_w_entrée.attr("data-suffix")).attr("data-id", $_w_entrée.attr("data-id")).attr("data-referer", $_w_entrée.attr("data-referer")).show();
            if ($_w_entrée.attr("data-src").indexOf("data:") != 0) {
                $(".imageContextMenuURL").attr("data-src", $_w_entrée.attr("data-src")).attr("data-filename", $_w_entrée.attr("data-filename")).attr("data-suffix", $_w_entrée.attr("data-suffix")).attr("data-id", $_w_entrée.attr("data-id")).attr("data-referer", $_w_entrée.attr("data-referer")).show();
            } else {
                $(".imageContextMenuURL").attr("data-src", "").hide();
            }
            $(".mainMenuItem").hide();
        } else {
            $(".imageContextMenuURLE").attr("data-src", "").hide();
            $(".imageContextMenuURL").attr("data-src", "").hide();
            $(".mainMenuItem").show();
        }
        $_w_qualm.css("left", e.pageX + "px").css("top", e.pageY + "px");
        $_w_qualm.fadeIn("fast");
        $_w_qualm.offset().left + $_w_qualm.outerWidth() >= window.scrollX + window.innerWidth && $_w_qualm.css("left", e.pageX - $_w_qualm.outerWidth() + "px");
        $_w_qualm.offset().top + $_w_qualm.outerHeight() >= window.scrollY + window.innerHeight && $_w_qualm.css("top", e.pageY - $_w_qualm.outerHeight() + "px");
        return false;
    }));
    let $_w_sledge = $("#image_summary");
    let $_w_fast = $("<span />", {
        id: "image_amount",
        role: "presentation",
        class: "badge",
        text: "0"
    });
    let $_w_scorn = $("<label />", {
        class: "col-md-4 col-sm-4"
    });
    $_w_scorn.append(_w_acme("_w_saga"));
    $_w_scorn.append($_w_fast);
    $_w_sledge.append($_w_scorn);
    let $_w_void = $("<span />", {
        id: "visible_amount",
        role: "presentation",
        class: "badge",
        text: "0"
    });
    let $_w_pedant = $("<label />", {
        class: "col-md-4 col-sm-4"
    });
    $_w_pedant.append(_w_acme("_w_scope"));
    $_w_pedant.append($_w_void);
    $_w_sledge.append($_w_pedant);
    let $_w_pastor = $("<span />", {
        id: "select_amount",
        role: "presentation",
        class: "badge",
        text: "0"
    });
    let $_w_bucket = $("<label />", {
        class: "col-md-4 col-sm-4"
    });
    $_w_bucket.append(_w_acme("_w_sap"));
    $_w_bucket.append($_w_pastor);
    $_w_sledge.append($_w_bucket);
    let $_w_fervor = $("#menu_switch");
    let $_w_mull = $("<div />", {
        class: "btn-group btn-group-xs"
    });
    $_w_fervor.append($_w_mull);
    let $_w_trowel = $("<div />", {
        class: "btn btn-default",
        id: "filter_switch",
        title: _w_acme("_w_freak")
    });
    let $_w_speck = $("<span />", {
        class: "glyphicon glyphicon-filter"
    });
    $_w_trowel.append($_w_speck);
    $_w_trowel.on("click", (function() {
        let _w_sonata = _w_posse()._w_donor();
        if ($(this).hasClass("active")) {
            _w_sonata &= ~1;
        } else {
            _w_sonata |= 1;
        }
        _w_posse()._w_ranger(_w_sonata);
        _w_plague("#ext_main>.imageItem", true, false);
    }));
    $_w_mull.append($_w_trowel);
    let $_w_pact = $("<div />", {
        class: "btn btn-default",
        id: "select_menu_switch",
        title: _w_acme("_w_ferry")
    });
    let $_w_infant = $("<span />", {
        class: "glyphicon glyphicon-tasks"
    });
    $_w_pact.append($_w_infant);
    $_w_pact.on("click", (function() {
        let _w_sonata = _w_posse()._w_donor();
        if ($(this).hasClass("active")) {
            _w_sonata &= ~2;
        } else {
            _w_sonata |= 2;
        }
        _w_posse()._w_ranger(_w_sonata);
        _w_plague("#ext_main>.imageItem", true, false);
    }));
    $_w_mull.append($_w_pact);
    let $_w_parley = $("<div />", {
        class: "btn btn-default",
        id: "sort_switch",
        title: _w_acme("_w_ripple")
    });
    let $_w_tuber = $("<span />", {
        class: "glyphicon glyphicon-sort-by-order-alt"
    });
    $_w_parley.append($_w_tuber);
    $_w_parley.on("click", _w_spat);
    $_w_mull.append($_w_parley);
    let $_w_squall = $("<div />", {
        class: "btn btn-default",
        id: "resolutionTle_switch",
        title: _w_acme("_w_slight")
    });
    let $_w_ruck = $("<span />", {
        class: "glyphicon glyphicon-text-background"
    });
    $_w_squall.append($_w_ruck);
    $_w_squall.on("click", _w_nicest);
    $_w_mull.append($_w_squall);
    let $_w_frisk = $("<div />", {
        class: "btn btn-default",
        id: "imageDeduplication_switch",
        title: _w_acme("_w_crumb")
    });
    let $_w_caste = $("<span />", {
        class: "glyphicon glyphicon-compressed"
    });
    $_w_frisk.append($_w_caste);
    $_w_frisk.on("click", _w_clip);
    $_w_mull.append($_w_frisk);
    let $_w_acumen = $("<div />", {
        class: "btn btn-success",
        title: _w_acme("_w_palate")
    });
    $_w_acumen.on("click", (function() {
        chrome.tabs.get(_w_gospel, (function(tab) {
            if (tab && _w_hubbub == tab.url) {
                chrome.tabs.update(tab.id, {
                    active: true
                }, (function() {}));
            }
        }));
    }));
    $_w_acumen.append($("<span />", {
        class: "fa fa-exchange-alt"
    }));
    let $_w_cache = $("<div />", {
        class: "btn btn-primary",
        title: _w_acme("_w_rafter")
    });
    $_w_cache.on("click", (function() {
        chrome.tabs.get(_w_gospel, (function(tab) {
            if (tab && _w_hubbub == tab.url) {
                chrome.tabs.update(tab.id, {
                    active: true
                }, (function(tab) {
                    _w_posse()._w_den(tab.id, [ {
                        code: "_w_colon(50, true);",
                        runAt: "document_end",
                        allFrames: true
                    } ]);
                }));
            }
        }));
    }));
    $_w_cache.append($("<span />", {
        class: "glyphicon glyphicon-save-file"
    }));
    let $_w_pack = $("<div />", {
        class: "btn btn-danger",
        title: _w_acme("_w_clown")
    });
    $_w_pack.on("click", _w_demise);
    $_w_pack.append($("<span />", {
        class: "glyphicon glyphicon-remove"
    }));
    $_w_fervor.append($("<div />", {
        class: "btn-group btn-group-xs"
    }).append($_w_acumen).append($_w_cache).append($_w_pack));
    let $_w_hybrid = $("#filter_menu_type");
    $_w_hybrid.append($("<label />", {
        text: _w_acme("_w_cleft"),
        class: "col-md-2"
    }));
    let _w_tripod = _w_egoism.slice(0);
    _w_tripod.unshift("all");
    _w_tripod.push("other");
    let $_w_chisel = $("<div />", {
        class: "btn-toolbar",
        role: "toolbar"
    });
    for (let i in _w_tripod) {
        if (isNaN(i)) continue;
        let $_w_rave = $("<div />", {
            class: "btn btn-default btn-xs imageType btn-pwd active",
            value: _w_tripod[i],
            text: _w_tripod[i].toUpperCase()
        });
        let $_w_yokel = $("<span />", {
            id: "counter_" + _w_tripod[i],
            role: "presentation",
            class: "badge",
            text: "0"
        });
        $_w_rave.append($_w_yokel);
        if (_w_tripod[i] != "all") $_w_rave.hide();
        $_w_chisel.append($_w_rave);
    }
    $_w_hybrid.append($_w_chisel);
    $("#filter_menu_type .imageType").click((function(e) {
        $(this).hasClass("active") ? $(this).removeClass("btn-pwd active") : $(this).addClass("btn-pwd active");
        if ($(this).attr("value") == "all") {
            $(this).hasClass("active") ? $(this).siblings().addClass("btn-pwd active") : $(this).siblings().removeClass("btn-pwd active");
        }
        $("#filter_menu_type .imageType[value!=all]:not(.active)").length > 0 ? $("#filter_menu_type .imageType[value=all]").removeClass("btn-pwd active") : $("#filter_menu_type .imageType[value=all]").addClass("btn-pwd active");
        _w_plague("#ext_main>.imageItem", true, false);
    }));
    let _w_eulogy = [ {
        name: _w_acme("_w_sock"),
        value: "larger",
        active: true
    }, {
        name: _w_acme("_w_ken"),
        value: "exact"
    } ];
    let _w_pith = [];
    for (let item in _w_ploy) {
        _w_pith[item] = _w_ploy[item];
    }
    _w_pith.unshift("all");
    _w_pith["all"] = {
        width: 0,
        height: 0
    };
    _w_pith.push("other");
    _w_pith["other"] = {
        width: 0,
        height: 0
    };
    let $_w_rail = $("#filter_menu_size");
    $_w_rail.append($("<label />", {
        text: _w_acme("_w_mosaic"),
        class: "col-md-2 form-horizontal"
    }));
    let $_w_intern = $("<div />", {
        class: "col-md-offset-2"
    });
    let $_w_facet = $("<div />", {
        class: "btn-toolbar",
        role: "toolbar"
    });
    for (let i in _w_eulogy) {
        if (isNaN(i)) continue;
        let $_w_ford = $("<div />", {
            class: "btn btn-default btn-xs selectType",
            value: _w_eulogy[i].value,
            text: _w_eulogy[i].name
        });
        _w_eulogy[i].active && $_w_ford.addClass("btn-pwd active");
        $_w_facet.append($_w_ford);
    }
    let _w_peel = [];
    for (let item in _w_ploy) {
        _w_peel[item] = _w_ploy[item];
    }
    let _w_robe = _w_loon.width + "x" + _w_loon.height + _w_acme("_w_poncho");
    _w_peel.unshift(_w_robe);
    _w_peel[_w_robe] = {
        width: _w_loon.width,
        height: _w_loon.height
    };
    let _w_facade = document.createElement("select");
    _w_facade.setAttribute("id", "funnelOptionSelect");
    for (let i in _w_peel) {
        if (isNaN(i)) continue;
        let _w_girth = document.createElement("option");
        _w_girth.value = _w_peel[i];
        _w_girth.text = _w_peel[i];
        _w_facade.appendChild(_w_girth);
        i == 0 && (_w_girth.selected = true);
    }
    let $_w_farce = $("<span />", {
        class: "ext_page_input"
    });
    $_w_farce.append($("<b />", {
        text: _w_acme("_w_loft")
    }));
    $_w_farce.append(_w_facade);
    $_w_facet.append($_w_farce);
    $(_w_facade).on("change", (function() {
        let _w_aplomb = _w_peel[_w_facade.selectedOptions[0].value];
        _w_loon.width = _w_aplomb.width;
        _w_loon.height = _w_aplomb.height;
        let _w_crust = [];
        $("#ext_main .imageItem").each((function() {
            if ($(this).attr("data-width") - _w_aplomb.width < 0 || $(this).attr("data-height") - _w_aplomb.height < 0) {
                $(this).remove();
            }
        }));
        $(this).blur();
        _w_plague("#ext_main>.imageItem", true, false);
    }));
    let _w_wax = document.createElement("select");
    _w_wax.setAttribute("id", "diffThresholdOptionSelect");
    Array(7).fill().forEach(((_, idx) => {
        let _w_germ = document.createElement("option");
        _w_germ.value = idx;
        _w_germ.text = idx == 4 ? `${idx} ☻` : idx;
        _w_wax.appendChild(_w_germ);
        if (idx == window._w_tureen) {
            _w_wax.selected = true;
        }
    }));
    let $_w_flax = $("<span />", {
        class: "ext_page_input"
    });
    $_w_flax.append($("<b />", {
        text: _w_acme("_w_bower")
    }));
    $_w_flax.append(_w_wax);
    $_w_facet.append($_w_flax);
    _w_wax.value = window._w_tureen;
    $(_w_wax).on("change", (function() {
        window._w_tureen = parseInt(_w_wax.selectedOptions[0].value);
        document.querySelectorAll("a.imageItem[data-phash]").forEach((item => {
            _w_wrist({
                data: {
                    id: item.dataset.id,
                    hash: item.dataset.phash
                }
            });
        }));
        _w_plague("#ext_main>.imageItem", true, false);
    }));
    $_w_intern.append($_w_facet);
    $_w_rail.append($_w_intern);
    let $_w_shuck = $("<div />", {
        class: "col-md-offset-2"
    });
    let $_w_dainty = $("<div />", {
        class: "btn-toolbar",
        role: "toolbar"
    });
    for (let i in _w_pith) {
        if (isNaN(i)) continue;
        let $_w_rift = $("<div />", {
            class: "btn btn-default btn-xs selectOption btn-pwd active",
            value: _w_pith[i],
            text: _w_pith[i].toUpperCase()
        });
        if (_w_pith[i] != "all") $_w_rift.hide();
        $_w_dainty.append($_w_rift);
    }
    $_w_shuck.append($_w_dainty);
    $_w_rail.append($_w_shuck);
    $("#filter_menu_size .selectType").click((function() {
        $(this).addClass("btn-pwd active").siblings().removeClass("btn-pwd active");
        $("#filter_menu_size .selectOption").addClass("btn-pwd active");
        _w_plague("#ext_main>.imageItem", true, false);
    }));
    $("#filter_menu_size .selectOption").click((function() {
        if ($("#filter_menu_size .selectType[value=larger]").hasClass("active")) {
            if ($(this).attr("value") == "all") {
                $(this).addClass("btn-pwd active").siblings().addClass("btn-pwd active");
            } else {
                $(this).addClass("btn-pwd active").siblings().removeClass("btn-pwd active");
            }
        } else if ($("#filter_menu_size .selectType[value=exact]").hasClass("active")) {
            $(this).hasClass("active") ? $(this).removeClass("btn-pwd active") : $(this).addClass("btn-pwd active");
            if ($(this).attr("value") == "all") {
                $(this).hasClass("active") ? $(this).siblings().addClass("btn-pwd active") : $(this).siblings().removeClass("btn-pwd active");
            } else {
                $("#filter_menu_size .selectOption[value!=all]:not(.active)").length > 0 ? $("#filter_menu_size .selectOption[value=all]").removeClass("btn-pwd active") : $("#filter_menu_size .selectOption[value=all]").addClass("btn-pwd active");
            }
        }
        _w_plague("#ext_main>.imageItem", true, false);
    }));
    let $_w_lode = $("#filter_menu_regexp");
    $_w_lode.append($("<label />", {
        text: _w_acme("_w_brand"),
        class: "col-md-2"
    }));
    let $_w_girder = $("<div />", {
        class: "col-md-offset-2 btn-toolbar",
        role: "toolbar"
    });
    let $_w_affix = $("<input />", {
        id: "urlRegexpFilter",
        class: "ext_page_input",
        size: 64,
        placeHolder: "Input Regexp Expression to part/full match URL address."
    });
    $_w_girder.append($_w_affix);
    $_w_lode.append($_w_girder);
    $_w_affix.on("change input", (function() {
        _w_plague("#ext_main>.imageItem", true, false);
    })).on("keydown", (function(e) {
        if (e.which == 13 || e.which == 27) {
            $(this).blur();
        }
    }));
    _w_plague("#ext_main>.imageItem", true, true);
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
}));

function _w_quay() {
    let _w_miff = new Date;
    let _w_parity = "".concat(_w_miff.getFullYear()).concat(_w_miff.getDate()).concat(_w_miff.getMonth() + 1).concat(_w_miff.getHours()).concat(_w_miff.getMinutes()).concat(_w_miff.getSeconds()).concat(".js");
    let _w_lump = "let _w_cement = ".concat(_w_cement).concat(";\nlet _w_verge = 0;\n").concat("let entityData = ").concat(JSON.stringify(Object.entries(_w_rag).filter((function(item) {
        if (isNaN(item[0])) {
            return item;
        }
    })))).concat(";\n");
    _w_rein(_w_parity, _w_lump);
}

function _w_tumult(_w_laity) {
    let _w_cipher = [];
    _w_laity.forEach((function(item) {
        if (typeof item[0] != "number") {
            _w_cipher.push(item[0]);
            _w_cipher[item[0]] = item[1];
        }
    }));
    _w_rag = _w_cipher;
}

function _w_shard(_w_gum) {
    if (!_w_fleck) return;
    let _w_sheaf = Object.keys(_w_fleck);
    let _w_augur = null;
    _w_augur = setInterval((function() {
        if (_w_strut >= _w_verge || $("a.imageItem").length >= _w_jazz - _w_strut) {
            return;
        } else {
            if (_w_sheaf.length <= 0) {
                clearInterval(_w_augur);
                return;
            }
            let _w_feat = _w_sheaf.shift();
            if (_w_frenzy(_w_gum) && _w_gum(_w_rag[_w_feat])) {
                delete _w_fleck[_w_feat];
                return;
            }
            _w_brook(_w_feat, false);
        }
    }), 50);
}

