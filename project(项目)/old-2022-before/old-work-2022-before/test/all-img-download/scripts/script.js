/**
 * ImageAssistant
 * Project Home: http://www.pullywood.com/ImageAssistant/
 * Author: 睡虫子(Joey)
 * Copyright (C) 2013-2020 普利坞(Pullywood.com)
**/
"use strict";

!window._w_slate && (window._w_slate = 0);

!window.prefetchForImage && (window.prefetchForImage = false);

!window.prefetchForDomImage && (window.prefetchForDomImage = false);

!window.extractorHash && (window.extractorHash = "");

!window._w_arson && (window._w_arson = {});

!window._w_crater && (window._w_crater = _w_scrap(8));

function _w_cast(_w_sprout) {
    if (_w_sprout) {
        return _w_girdle(_w_sprout);
    } else {
        return _w_girdle(window.location.href);
    }
}

function _w_climax(_w_plot) {
    let $_w_duel;
    if (_w_plot instanceof jQuery) {
        $_w_duel = _w_plot;
    } else {
        $_w_duel = $(_w_plot);
    }
    return $_w_duel.attr("data-referer");
}

function _w_corral() {
    let _w_groom = function() {
        let _w_rag = {};
        let _w_mantle = $(this).get(0);
        let _w_sprout = _w_climax(_w_mantle) ? _w_climax(_w_mantle) : _w_cast();
        _w_rag[_w_mantle.src] = {
            title: _w_mantle.title,
            alt: _w_mantle.alt,
            serial: window._w_slate++,
            referer: _w_sprout
        };
        _w_bud(_w_rag);
    };
    $("img").on("load", _w_groom);
    let _w_hoax = function(_w_parody) {
        _w_parody.map((function(_w_tract) {
            switch (_w_tract.type) {
              case "attributes":
                let _w_gaze = _w_tract.target.closest("svg");
                if (_w_gaze) {
                    let _w_rag = {};
                    let _w_sprout = _w_cast();
                    _w_rag[_w_reign(_w_gaze.outerHTML)] = {
                        title: _w_gaze.title,
                        alt: _w_gaze.alt,
                        serial: window._w_slate++,
                        referer: _w_sprout
                    };
                    _w_bud(_w_rag);
                }
                break;

              case "childList":
              case "subtree":
                {
                    let _w_rag = {};
                    _w_tract.addedNodes.forEach((_w_rote => {
                        if (!_w_rote.querySelector) return;
                        let _w_melody = _w_rote.querySelectorAll("svg");
                        let _w_influx = 0;
                        window._w_slate = (_w_influx = window._w_slate) + _w_melody.length;
                        _w_melody.forEach((_w_gaze => {
                            let _w_sprout = _w_cast();
                            _w_rag[_w_reign(_w_gaze.outerHTML)] = {
                                title: _w_gaze.title,
                                alt: _w_gaze.alt,
                                serial: _w_influx++,
                                referer: _w_sprout
                            };
                        }));
                        let _w_blast = $(_w_rote).find("a").toArray();
                        $(_w_rote).is("A") && _w_blast.push(_w_rote);
                        _w_blast.forEach((_w_dismay => {
                            let _w_fiat = _w_shack(_w_dismay, _w_cast());
                            for (let imgUrl in _w_fiat) {
                                _w_rag[imgUrl] = _w_fiat[imgUrl];
                            }
                            _w_agony(_w_dismay, _w_cast());
                        }));
                        let _w_swarm = $(_w_rote).find("img").toArray();
                        $(_w_rote).is("IMG") && _w_swarm.push(_w_rote);
                        let _w_tact = 0;
                        window._w_slate = (_w_tact = window._w_slate) + _w_swarm.length;
                        $(_w_swarm).on("load", _w_groom);
                        _w_swarm.forEach((_w_mantle => {
                            let _w_sprout = null;
                            if (_w_mantle.src) {
                                _w_sprout = _w_climax(_w_mantle) ? _w_climax(_w_mantle) : _w_cast();
                                _w_rag[_w_mantle.src] = {
                                    title: _w_mantle.title,
                                    alt: _w_mantle.alt,
                                    serial: _w_tact++,
                                    referer: _w_sprout
                                };
                            }
                            _w_agony(_w_mantle, _w_sprout);
                        }));
                        let _w_canopy = $(_w_rote).find("*").toArray();
                        _w_canopy.push(_w_rote);
                        let _w_scowl = 0;
                        window._w_slate = (_w_scowl = window._w_slate) + _w_canopy.length;
                        _w_canopy.forEach((_w_plot => {
                            let _w_score = _w_balk(_w_plot);
                            if (_w_score && _w_score.length > 0) {
                                _w_rag[_w_score] = {
                                    title: "",
                                    alt: "",
                                    serial: _w_scowl++,
                                    referer: _w_cast()
                                };
                            }
                        }));
                        $(_w_canopy).trigger("mouseover");
                        for (let j = 0; j < _w_blast.length; ++j) {
                            let _w_dismay = _w_blast[j];
                            _w_claim(_w_dismay);
                        }
                    }));
                    _w_bud(_w_rag);
                }
            }
        }));
    };
    let _w_raffle = new MutationObserver(_w_hoax);
    let _w_bulk = document.body;
    let _w_mirage = {
        childList: true,
        subtree: true,
        attributes: true
    };
    _w_raffle.observe(_w_bulk, _w_mirage);
    $("*").trigger("mouseover");
}

function _w_balk(_w_tackle) {
    let _w_fray = [];
    try {
        let _w_sliver = $(_w_tackle).css("background-image");
        if (_w_sliver && _w_sliver.match(/url\((['"]?)(.*?)\1\)/gi)) {
            let _w_dowry = _w_sliver.match(/url\((['"]?)(.*?)\1\)/gi);
            for (let idx in _w_dowry) {
                let _w_marble = /url\((['"]?)(.*?)\1\)/gi.exec(_w_dowry[idx]);
                _w_fray.push(_w_marble[2]);
            }
        }
    } catch (exception) {}
    return _w_fray;
}

function _w_shack(_w_dismay, _w_sprout) {
    let _w_rag = {};
    if (!_w_dismay || !_w_dismay.href) {
        return _w_rag;
    }
    let _w_fell = _w_hoof(_w_sprout, _w_dismay.getAttribute("href"));
    let _w_vim = _w_climax(_w_dismay) ? _w_climax(_w_dismay) : _w_sprout;
    if (_w_abuse.indexOf(_w_dismay.pathname.substring(_w_dismay.pathname.lastIndexOf(".") + 1).toLowerCase()) != -1) {
        _w_rag[_w_fell] = {
            title: _w_dismay.title,
            alt: "",
            serial: window._w_slate++,
            referer: _w_vim
        };
    }
    let _w_graft = _w_climax(_w_dismay) ? _w_climax(_w_dismay) : _w_fell;
    let _w_apex = _w_unison(_w_dismay.search);
    if (_w_apex && _w_apex.length > 0) {
        let _w_warden = 0;
        window._w_slate = (_w_warden = window._w_slate) + _w_apex.length;
        let _w_brooch = function(_w_staple) {
            let _w_axiom = {};
            for (let idx in _w_apex) {
                let _w_forge = _w_apex[idx];
                if (_w_rag[_w_forge]) continue;
                _w_axiom[_w_forge] = {
                    title: "",
                    alt: "",
                    serial: _w_warden++,
                    referer: _w_staple
                };
            }
            _w_bud(_w_axiom);
        };
        let _w_reaper = function(_w_panic, _w_valor, _w_annex) {
            if (_w_valor == "success") {
                let _w_brute = _w_annex.getResponseHeader("Content-Type");
                if (_w_brute && _w_brute.indexOf("html") < 0) {
                    _w_brooch(_w_sprout);
                } else {
                    let _w_impact = document.implementation.createHTMLDocument("parseHTMLDocument");
                    let _w_caper = _w_impact.createElement("html");
                    _w_caper.innerHTML = DOMPurify.sanitize(_w_panic, {
                        WHOLE_DOCUMENT: true
                    });
                    let $_w_moat = $(_w_caper);
                    _w_myopia(_w_graft, $_w_moat);
                    if ($_w_moat.find("a").length > 0 || $_w_moat.find("img").length > 0) {
                        _w_brooch(_w_graft);
                    } else {
                        _w_brooch(_w_sprout);
                    }
                }
            } else {
                _w_brooch(_w_sprout);
            }
        };
        let _w_lair = {
            method: "get",
            url: _w_graft,
            headers: {
                Accept: "*/*; charset=UTF-8",
                "Cache-Control": "no-cache, no-store, must-revalidate, max-age=0, post-check=0, pre-check=0",
                Pragma: "no-cache",
                Expires: "0",
                "IA-Tag": window.extractorHash
            }
        };
        _w_fop(_w_lair, _w_reaper);
    }
    return _w_rag;
}

function _w_unison(_w_damper) {
    let _w_bore = [];
    if (_w_damper && _w_damper.length > 4) {
        let _w_core = _w_damper.substring(1).split("&");
        for (let j = 0; j < _w_core.length; ++j) {
            if (_w_core[j].length > 0 && _w_core[j].split("=").length == 2) {
                let _w_armory = _w_core[j].split("=")[1];
                _w_armory = _w_gentry(_w_armory);
                if (_w_log(_w_armory)) {
                    let _w_die = new URL(_w_armory);
                    if (_w_abuse.indexOf(_w_die.pathname.substring(_w_die.pathname.lastIndexOf(".") + 1).toLowerCase()) != -1) {
                        if (_w_bore.indexOf(_w_die.href) < 0) _w_bore.push(_w_die.href);
                    }
                }
            }
        }
    }
    return _w_bore;
}

function _w_minnow(_w_tusk, _w_harrow) {
    chrome.runtime.sendMessage(chrome.runtime.id, {
        type: "_w_loaf"
    }, (function callback(_w_plank) {
        window._w_slate = _w_plank * 1e8;
        _w_reel(_w_tusk, _w_harrow);
    }));
}

function _w_myopia(_w_jerk, $_w_nick) {
    let _w_fusion = {};
    _w_fusion["url"] = _w_jerk;
    _w_fusion["title"] = $_w_nick.find("title").text();
    chrome.runtime.sendMessage(chrome.runtime.id, {
        type: "_w_school",
        extractorHash: extractorHash,
        pageInfo: _w_fusion
    });
}

function _w_tirade() {
    let _w_pulp = () => {
        let $_w_nick = $("html");
        _w_myopia(window.location.href, $_w_nick);
    };
    setInterval(_w_pulp, 2e3);
    _w_pulp();
}

function _w_reel(_w_tusk, _w_harrow) {
    if (window._w_skit) return;
    window._w_skit = (new Date).getTime();
    _w_harrow = _w_harrow.substr(0, 32);
    if ((_w_tusk & 1) > 0) {
        window.prefetchForImage = true;
    }
    if ((_w_tusk & 2) > 0) {
        window.prefetchForDomImage = true;
    }
    (!window.extractorHash || window.extractorHash.length == 0) && (window.extractorHash = _w_harrow);
    _w_tirade();
    _w_corral();
    let _w_rag = {};
    let _w_melody = document.querySelectorAll("svg");
    let _w_influx = 0;
    window._w_slate = (_w_influx = window._w_slate) + _w_melody.length;
    for (let i = 0; i < _w_melody.length; ++i) {
        let _w_gaze = _w_melody[i];
        let _w_sprout = _w_cast();
        _w_rag[_w_reign(_w_gaze.outerHTML)] = {
            title: _w_gaze.title,
            alt: _w_gaze.alt,
            serial: _w_influx++,
            referer: _w_sprout
        };
    }
    let _w_swarm = document.images;
    let _w_tare = 0;
    window._w_slate = (_w_tare = window._w_slate) + _w_swarm.length;
    for (let i = 0; i < _w_swarm.length; ++i) {
        let _w_mantle = _w_swarm[i];
        if (_w_mantle.src) {
            let _w_sprout = _w_climax(_w_mantle) ? _w_climax(_w_mantle) : _w_cast();
            _w_rag[_w_mantle.src] = {
                title: _w_mantle.title,
                alt: _w_mantle.alt,
                serial: _w_tare++,
                referer: _w_sprout
            };
        }
        _w_agony(_w_mantle, _w_cast());
    }
    let _w_canopy = $("*");
    let _w_moor = 0;
    window._w_slate = (_w_moor = window._w_slate) + _w_canopy.length;
    for (let i = 0; i < _w_canopy.length; ++i) {
        let _w_fray = _w_balk(_w_canopy[i]);
        for (let idx in _w_fray) {
            let _w_score = _w_fray[idx];
            if (_w_score && _w_score.length > 0) {
                _w_rag[_w_score] = {
                    title: "",
                    alt: "",
                    serial: _w_moor++,
                    referer: _w_cast()
                };
            }
        }
    }
    let _w_bauble = new Array;
    let _w_blast = document.links;
    for (let i = 0; i < _w_blast.length; ++i) {
        let _w_dismay = _w_blast[i];
        if (_w_dismay && _w_dismay.href && _w_bauble.indexOf(_w_dismay.href) == -1) {
            _w_bauble.push(_w_dismay);
        }
    }
    for (let i = 0; i < _w_bauble.length; ++i) {
        let _w_dismay = _w_bauble[i];
        let _w_fiat = _w_shack(_w_dismay, _w_cast());
        for (let imgUrl in _w_fiat) {
            _w_rag[imgUrl] = _w_fiat[imgUrl];
        }
        _w_agony(_w_dismay, _w_cast());
    }
    _w_bud(_w_rag);
    for (let i = 0; i < _w_bauble.length; ++i) {
        let _w_dismay = _w_bauble[i];
        _w_claim(_w_dismay);
    }
}

function _w_claim(_w_dismay) {
    let _w_jerk;
    try {
        _w_jerk = new URL(_w_dismay);
    } catch (exception) {
        return;
    }
    let _w_sprout = _w_climax(_w_dismay) ? _w_climax(_w_dismay) : _w_cast();
    if (_w_jerk.protocol != "http:" && _w_jerk.protocol != "https:") return;
    if (_w_lament(window.location.href)) {
        return;
    }
    let _w_mantle = new Image;
    let _w_rubble = _w_jerk.href;
    let _w_waffle = function() {
        let _w_rag = {};
        _w_rag[this.src] = {
            title: "",
            alt: "",
            serial: window._w_slate++,
            referer: _w_cast()
        };
        _w_bud(_w_rag);
    };
    let _w_glaze = function() {
        let _w_reaper = function(_w_panic, _w_valor, _w_annex) {
            if (_w_valor != "success") return;
            let _w_impact = document.implementation.createHTMLDocument("parseHTMLDocument");
            let _w_caper = _w_impact.createElement("html");
            _w_caper.innerHTML = DOMPurify.sanitize(_w_panic, {
                WHOLE_DOCUMENT: true
            });
            let $_w_succor = $(_w_caper);
            _w_myopia(_w_rubble, $_w_succor);
            let $_w_swarm = $_w_succor.find("img");
            let $_w_blast = $_w_succor.find("a");
            if (_w_lament(_w_rubble)) {
                $_w_swarm.each((function() {
                    if ($(this).attr("data-src")) $(this).attr("src", $(this).attr("data-src"));
                }));
                $_w_blast.each((function() {
                    if ($(this).attr("data-src")) $(this).attr("href", $(this).attr("data-src"));
                }));
            }
            let _w_rag = {};
            let _w_pleat = 0;
            window._w_slate = (_w_pleat = window._w_slate) + $_w_swarm.length;
            for (let i = 0; i < $_w_swarm.length; ++i) {
                let _w_mantle = $_w_swarm[i];
                let _w_score = _w_mantle.getAttribute("src");
                if (_w_score) {
                    _w_score = _w_hoof(_w_rubble, _w_score);
                    _w_rag[_w_score] = {
                        title: _w_mantle.title,
                        alt: _w_mantle.alt,
                        serial: _w_pleat++,
                        referer: _w_cast(_w_rubble)
                    };
                }
                _w_agony(_w_mantle, _w_rubble);
            }
            for (let j = 0; j < $_w_blast.length; ++j) {
                let _w_dismay = $_w_blast[j];
                let _w_fiat = _w_shack(_w_dismay, _w_rubble);
                for (let imgUrl in _w_fiat) {
                    _w_rag[imgUrl] = _w_fiat[imgUrl];
                }
                _w_agony(_w_dismay, _w_rubble);
            }
            _w_bud(_w_rag);
        };
        let _w_lair = {
            method: "get",
            url: _w_rubble,
            headers: {
                Accept: "*/*; charset=UTF-8",
                "Cache-Control": "no-cache, no-store, must-revalidate, max-age=0, post-check=0, pre-check=0",
                Pragma: "no-cache",
                Expires: "0",
                "IA-Tag": window.extractorHash
            }
        };
        _w_fop(_w_lair, _w_reaper);
        _w_frenzy(window._w_craft) && window._w_craft(_w_dismay);
    };
    if (prefetchForImage) {
        _w_mantle.onload = _w_waffle;
        if (prefetchForDomImage) {
            _w_mantle.onerror = _w_glaze;
            _w_mantle.onabort = _w_mantle.onerror;
        }
        _w_mantle.src = _w_rubble;
    } else if (prefetchForDomImage) {
        _w_glaze();
    }
}

function _w_bud(_w_rag) {
    if (!_w_rag || Object.keys(_w_rag).length == 0) return;
    for (let imgSrc in _w_rag) {
        if (_w_lament(window.location.href)) {
            break;
        } else if (imgSrc.startsWith("data:")) {
            break;
        }
        try {
            let _w_jerk = new URL(imgSrc);
            let _w_anthem = decodeURIComponent(_w_jerk.pathname);
            if (_w_anthem.indexOf("http://") >= 0 || _w_anthem.indexOf("https://") >= 0) {
                let _w_canary = /https?:\/\/.*/.exec(_w_anthem);
                if (_w_canary && _w_canary.length > 0 && !_w_rag[_w_canary[0]]) {
                    let _w_byline = {};
                    let _w_chaff = _w_rag[imgSrc];
                    let _w_file = Object.keys(_w_chaff);
                    for (let oIdx in _w_file) {
                        let _w_sewer = _w_file[oIdx];
                        if (typeof _w_sewer != "undefined" && _w_sewer != null) {
                            _w_byline[_w_sewer] = _w_chaff[_w_sewer];
                        }
                    }
                    _w_rag[_w_canary[0]] = _w_byline;
                }
            }
            let _w_apex = _w_unison(_w_jerk.search);
            let _w_wick = 0;
            window._w_slate = (_w_wick = window._w_slate) + _w_apex.length;
            for (let idx in _w_apex) {
                let _w_forge = _w_apex[idx];
                if (!_w_rag[_w_forge]) {
                    let _w_byline = {};
                    let _w_chaff = _w_rag[imgSrc];
                    let _w_file = Object.keys(_w_chaff);
                    for (let oIdx in _w_file) {
                        let _w_sewer = _w_file[oIdx];
                        if (typeof _w_sewer != "undefined" && _w_sewer != null) {
                            _w_byline[_w_sewer] = _w_chaff[_w_sewer];
                        }
                    }
                    _w_rag[_w_forge] = _w_byline;
                }
            }
        } catch (exception) {}
    }
    chrome.runtime.sendMessage(chrome.runtime.id, {
        type: "_w_intent",
        extractorHash: extractorHash,
        images: _w_rag
    });
}

function _w_agony(_w_plot, _w_brim) {
    if (_w_lament(window.location.href) || _w_lament(_w_brim)) {
        return;
    }
    let _w_sprout = _w_climax(_w_plot) ? _w_climax(_w_plot) : _w_cast(_w_brim);
    let _w_crutch = _w_plot.attributes;
    let _w_hawker = _w_crutch.length;
    for (let idx = 0; idx < _w_hawker; ++idx) {
        let _w_gnat = _w_crutch[idx];
        if (_w_gnat.specified && _w_gnat.name != "href" && _w_gnat.name != "src") {
            let _w_import = /^[^'"\s]+?\.(apng|bmp|gif|ico|cur|jpg|jpeg|jfif|pjpeg|pjp|png|svg|tif|tiff|webp)(\?.+)?$/i;
            let _w_jigsaw = /^https?:\/\/[^'"\s]+\/[^'"\s]+$/i;
            let _w_margin = /(https?:\/\/[^'"\s]+\.(apng|bmp|gif|ico|cur|jpg|jpeg|jfif|pjpeg|pjp|png|svg|tif|tiff|webp))(\?[^'"\s]+)?/gi;
            if (_w_import.test(_w_gnat.value) || _w_jigsaw.test(_w_gnat.value)) {
                let _w_kin = _w_hoof(_w_brim, _w_gnat.value);
                if (_w_arson[_w_kin]) {
                    continue;
                } else {
                    _w_arson[_w_kin] = true;
                }
                _w_crater.addTask((function(_w_accord, _w_sash) {
                    let _w_fidget = function() {
                        let _w_rag = {};
                        _w_rag[_w_kin] = {
                            title: _w_plot.title,
                            alt: _w_plot.alt,
                            serial: window._w_slate++,
                            referer: _w_sprout
                        };
                        _w_bud(_w_rag);
                    };
                    let _w_mantle = new Image;
                    _w_mantle.onerror = _w_sash;
                    _w_mantle.onabort = _w_mantle.onerror;
                    _w_mantle.onload = function() {
                        _w_sash();
                        _w_fidget();
                    };
                    _w_accord();
                    _w_mantle.src = _w_kin;
                }));
            } else {
                let _w_rag = {};
                let _w_skewer = _w_gnat.value.match(_w_margin);
                _w_skewer && _w_skewer.forEach((function(_w_kin) {
                    _w_rag[_w_kin] = {
                        title: _w_plot.title,
                        alt: _w_plot.alt,
                        serial: window._w_slate++,
                        referer: _w_sprout
                    };
                }));
                if (Object.keys(_w_rag).length > 0) {
                    _w_bud(_w_rag);
                }
            }
        }
    }
}

!window._w_ken && (window._w_ken = function() {
    let _w_defect = [];
    let _w_clot = {};
    setInterval((function() {
        if (!window._w_skit) return;
        try {
            let _w_ripple = "_w_podium";
            let _w_leeway = document.getElementById(_w_ripple);
            if (_w_leeway) {
                if (_w_leeway.value.length > 0 && _w_leeway.value != _w_ripple) {
                    let _w_trance = JSON.parse(_w_leeway.value);
                    _w_trance.forEach((function(_w_geyser) {
                        if (!_w_clot[_w_geyser]) {
                            _w_clot[_w_geyser] = true;
                            _w_defect.push(_w_geyser);
                        }
                    }));
                }
                _w_leeway.value = _w_ripple;
            }
        } catch (ex) {}
        while (_w_defect.length > 0) {
            let _w_kin = _w_defect.pop();
            _w_crater.addTask((function(_w_accord, _w_sash) {
                let _w_mantle = new Image;
                _w_mantle.onerror = _w_sash;
                _w_mantle.onabort = _w_mantle.onerror;
                _w_mantle.onload = _w_sash;
                _w_accord();
                _w_mantle.src = _w_kin;
            }));
        }
    }), 512);
    return {
        _w_lathe: function() {
            return _w_defect.length;
        }
    };
}());