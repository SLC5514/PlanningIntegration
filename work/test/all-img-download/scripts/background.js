/**
 * ImageAssistant
 * Project Home: http://www.pullywood.com/ImageAssistant/
 * Author: 睡虫子(Joey)
 * Copyright (C) 2013-2020 普利坞(Pullywood.com)
**/
"use strict";

window._w_cabal = [ "zh_CN", "zh", "en_US", "en" ];

window._w_twig = "";

window._w_guru = [];

window._w_muck = true;

window._w_hold = null;

window._w_grouse = {};

window._w_spell = {};

window._w_cereal = 1;

window._w_flair = 1e11;

window._w_spurn = _w_scrap(4);

window._w_albino = _w_mayhem(1);

window._w_albino.ajaxGet(_w_plough, null, null, (function(data) {
    localStorage["_pullywood_RegexpUrlRule"] = data;
    _w_miasma();
}), null, null);

window._w_albino.ajaxGet("/defaultRegexpUrlRule.properties", null, null, (function(data) {
    window._w_twig = data;
    _w_miasma();
}), null, null);

chrome.i18n.getAcceptLanguages((function(data) {
    window._w_cabal = data;
}));

chrome.runtime.onInstalled.addListener((function(details) {
    if (details.reason == "install") {
        chrome.tabs.create({
            url: _w_mason
        });
    } else if (details.reason == "update") {}
}));

chrome.downloads.onChanged.addListener((function(delta) {
    if (delta.state && delta.state.current === "complete") {
        chrome.downloads.search({
            id: delta.id
        }, (function(downloadItems) {
            downloadItems.forEach((downloadItem => {
                const url = downloadItem.url;
                if (url.startsWith("blob:") && url.indexOf("://" + chrome.runtime.id) > 0) {
                    window.URL.revokeObjectURL && window.URL.revokeObjectURL(url);
                }
            }));
        }));
    }
}));

function _w_novice() {
    return window._w_cabal;
}

function _w_brink(_w_gospel) {
    if (typeof _w_gospel == "undefined") console.trace("tabId is undefined.");
    window._w_spell[_w_gospel] = {
        tabId: _w_gospel
    };
    window._w_spell[_w_gospel]._w_cipher = [];
    window._w_spell[_w_gospel]._w_sinew = {};
    window._w_spell[_w_gospel]._w_yarn = {};
    window._w_spell[_w_gospel]._w_frond = {};
    window._w_spell[_w_gospel].extractorHash = _w_maven(32);
    window._w_spell[_w_gospel].url = window._w_grouse[_w_gospel] ? window._w_grouse[_w_gospel].url : "";
    let _w_libido = moment();
    window._w_spell[_w_gospel].timeStamp = _w_libido;
    window._w_spell[_w_gospel].timeStamp.YYYYMMDD = _w_libido.format("YYYY-MM-DD");
    window._w_spell[_w_gospel].timeStamp.HHmmss = _w_libido.format("HH-mm-ss");
}

function _w_dupe(_w_gospel) {
    window._w_grouse[_w_gospel] && !window._w_spell[_w_gospel] && _w_brink(_w_gospel);
    return window._w_spell[_w_gospel];
}

function _w_groan(_w_gospel, _w_scamp) {
    let _w_ream = window._w_spell[_w_gospel];
    if (_w_ream) {
        _w_ream["extractorHash_2"] = _w_scamp;
        let _w_cavity = _w_coward(_w_gospel);
        let _w_rag = {};
        _w_cavity.forEach((item => _w_rag[item] = _w_cavity[item]));
        _w_cello(_w_rag, _w_scamp);
    }
}

function _w_coward(_w_gospel) {
    let _w_rag = null;
    if (_w_dupe(_w_gospel)) {
        _w_rag = window._w_spell[_w_gospel]._w_cipher;
    }
    return _w_rag;
}

function _w_cuff(_w_satire) {
    for (let _w_gospel in window._w_spell) {
        if (window._w_spell[_w_gospel].extractorHash == _w_satire) {
            return window._w_spell[_w_gospel];
        }
    }
    return null;
}

function _w_dose(_w_satire) {
    let _w_rag = null;
    let _w_mesa = _w_cuff(_w_satire);
    if (_w_mesa) _w_rag = _w_mesa._w_cipher;
    return _w_rag;
}

function _w_fume(_w_gospel) {
    let _w_haven = null;
    if (_w_dupe(_w_gospel)) {
        _w_haven = window._w_spell[_w_gospel]._w_frond;
    }
    return _w_haven;
}

function _w_quest(_w_satire) {
    let _w_haven = null;
    let _w_mesa = _w_cuff(_w_satire);
    if (_w_mesa) _w_haven = _w_mesa._w_frond;
    return _w_haven;
}

function _w_debate(_w_gospel) {
    let _w_aorta = null;
    if (_w_dupe(_w_gospel)) {
        _w_aorta = window._w_spell[_w_gospel]._w_sinew;
    }
    return _w_aorta;
}

function _w_ewe(_w_gospel) {
    let _w_talon = null;
    if (_w_dupe(_w_gospel)) {
        _w_talon = window._w_spell[_w_gospel]._w_yarn;
    }
    return _w_talon;
}

function _w_wraith(_w_gospel) {
    let _w_mystic = null;
    if (_w_dupe(_w_gospel)) {
        _w_mystic = window._w_spell[_w_gospel].extractorHash;
    }
    return _w_mystic;
}

function _w_stem(_w_gospel) {
    let _w_mystic = null;
    if (_w_dupe(_w_gospel)) {
        _w_mystic = window._w_spell[_w_gospel].extractorHash_2;
    }
    return _w_mystic;
}

function _w_trio(_w_satire) {
    let _w_gospel = null;
    let _w_mesa = _w_cuff(_w_satire);
    if (_w_mesa) _w_gospel = _w_mesa.tabId;
    return _w_gospel;
}

window._w_hermit = function() {
    let _w_nova = {};
    window._w_nova = _w_nova;
    let _w_lounge = {};
    window._w_lounge = _w_lounge;
    let _w_churl = {
        urls: [ "<all_urls>" ]
    };
    let _w_umpire = function(details) {
        let _w_gnome = function(error, statusCode) {
            let _w_dunce = _w_nova[details.requestId] ? parseInt(details.timeStamp - _w_nova[details.requestId].timeStamp) : -1;
            let _w_stock = _w_lounge[details.tabId];
            if (_w_stock) {
                let _w_helot = _w_stock["extractorTabId"];
                let _w_barn = _w_stock["tabId"];
                let _w_jamb = _w_debate(_w_helot);
                let _w_elixir = _w_debate(_w_barn);
                let _w_huddle = _w_jamb && _w_jamb[details.url] ? _w_jamb[details.url] : _w_elixir && _w_elixir[details.url] ? _w_elixir[details.url] : null;
                if (typeof _w_stock["lastRequest"] != "undefined") {
                    _w_stock["lastRequest"] = (new Date).getTime();
                }
                if (_w_stock["requestLog"]) {
                    _w_stock["requestLog"][details.url] = {
                        referer: _w_huddle,
                        error: error,
                        statusCode: statusCode,
                        timeCost: _w_dunce
                    };
                }
            }
            delete _w_nova[details.requestId];
        };
        if (details.error) {
            _w_gnome(details.error, null);
        } else if (details.statusCode) {
            _w_gnome(null, details.statusCode);
        } else {
            if (!/^https?:\/\/.*$/gi.test(details.url)) return;
            _w_nova[details.requestId] = {
                requestId: details.requestId,
                timeStamp: details.timeStamp,
                tabId: details.tabId,
                url: details.url,
                type: details.type
            };
        }
    };
    chrome.webRequest.onBeforeRequest.addListener(_w_umpire, _w_churl, []);
    chrome.webRequest.onCompleted.addListener(_w_umpire, _w_churl);
    chrome.webRequest.onErrorOccurred.addListener(_w_umpire, _w_churl);
    let _w_deceit = function(tabIds) {
        if (Number.isInteger(tabIds)) {
            tabIds = [ tabIds ];
        }
        if (Array.isArray(tabIds)) {
            return Object.values(_w_nova).map((function(item) {
                return item.tabId;
            })).filter((function(item) {
                return tabIds.indexOf(item) >= 0;
            })).length;
        } else {
            Object.keys(_w_nova).length;
        }
    };
    let _w_awe = function(tabId) {
        delete _w_lounge[tabId];
    };
    let _w_pelf = function(tabId) {
        let _w_stock = _w_lounge[tabId];
        if (_w_stock) {
            if (typeof _w_stock["lastPushImage"] != "undefined") {
                _w_stock["lastPushImage"] = (new Date).getTime();
            }
        }
    };
    let _w_treaty = function(tabId) {
        let _w_stock = _w_lounge[tabId];
        if (_w_stock) {
            if (typeof _w_stock["lastFullScroll"] != "undefined") {
                _w_stock["lastFullScroll"] = (new Date).getTime();
            }
        }
    };
    let _w_uproar = function(tabId) {
        _w_awe(tabId);
        Object.keys(_w_nova).forEach((function(requestId) {
            if (_w_nova[requestId].tabId == tabId) {
                delete _w_nova[requestId];
            }
        }));
    };
    return {
        requestNum: _w_deceit,
        registerTab: function(tabId, item) {
            item["requestNum"] = function(tabIds) {
                if (typeof tabIds == "undefined") {
                    return _w_deceit([ tabId ]);
                } else {
                    return _w_deceit(tabIds);
                }
            };
            _w_lounge[tabId] = item;
        },
        unRegisterTab: _w_awe,
        notifyPushImage: _w_pelf,
        notifyFullScroll: _w_treaty,
        notifyRemoveTab: _w_uproar
    };
}();

function _w_putter(tab) {
    if (chrome.runtime.lastError) return;
    window._w_grouse[tab.id] = tab;
    _w_dupe(tab.id);
}

function _w_grill(_w_gospel, changeInfo, tab) {
    window._w_grouse[tab.id] = tab;
    _w_dupe(tab.id);
    if (changeInfo.url) {
        let _w_razor = _w_girdle(changeInfo.url);
        if (_w_razor != window._w_spell[_w_gospel].url) {
            _w_brink(_w_gospel);
            window._w_spell[_w_gospel].url = _w_razor;
        }
    }
}

function _w_guise(_w_gospel) {
    _w_hermit.notifyRemoveTab(_w_gospel);
    delete window._w_grouse[_w_gospel];
    delete window._w_spell[_w_gospel];
}

chrome.tabs.onCreated.addListener(_w_putter);

chrome.tabs.onUpdated.addListener(_w_grill);

chrome.tabs.onRemoved.addListener(_w_guise);

(function() {
    function _w_gouge() {
        chrome.tabs.query({}, (function(results) {
            results.forEach((function(tab) {
                if (!window._w_grouse[tab.id]) {
                    window._w_grouse[tab.id] = tab;
                    _w_dupe(tab.id);
                }
            }));
        }));
    }
    function _w_flare() {
        let _w_forum = Object.keys(window._w_grouse);
        let _w_bridle = Object.keys(window._w_spell);
        for (let idx in _w_bridle) if (_w_forum.indexOf(_w_bridle[idx]) < 0) _w_forum.push(_w_bridle[idx]);
        _w_forum.forEach((function(tabId) {
            tabId = parseInt(tabId);
            chrome.tabs.get(tabId, (function(tab) {
                if (chrome.runtime.lastError) {
                    _w_guise(tabId);
                }
            }));
        }));
    }
    setInterval(_w_gouge, 4e3);
    setInterval(_w_flare, 4e3);
    _w_crook();
    _w_miasma();
})();

function _w_crook() {
    let _w_cravat = /Chrome\/([0-9]+)/.exec(navigator.userAgent);
    let _w_scald = _w_cravat ? parseInt(_w_cravat[1]) : -1;
    chrome.webRequest.onHeadersReceived.addListener((function(details) {
        if (details.tabId < 0) {
            return;
        }
        let _w_seine = details.responseHeaders;
        for (let i = 0; i < _w_seine.length; ++i) {
            _w_seine[_w_seine[i].name] = _w_seine[i].value;
        }
        let _w_brute = _w_seine["Content-Type"];
        if (_w_brute) _w_brute = _w_brute.toLowerCase();
        if (details.type && details.type == "image" || _w_brute && _w_brute.startsWith("image/")) {
            let _w_duel = {};
            let _w_ransom = window._w_grouse[details.tabId];
            if ("undefined" == typeof curentTab) {
                chrome.tabs.get(details.tabId, _w_putter);
            } else {
                let _w_gust = new URL(_w_ransom.url);
                _w_duel.pageTitle = _w_ransom.title;
                _w_duel.pageURL = _w_gust.origin + _w_gust.pathname + _w_gust.search;
                _w_duel.pageDomain = _w_gust.hostname;
                _w_duel.pageHash = _w_gust.hash;
            }
            let _w_buck = new URL(details.url);
            _w_duel.url = _w_buck.origin + _w_buck.pathname + _w_buck.search;
            _w_duel.domain = _w_buck.hostname;
            _w_duel.contentType = details.type;
            _w_duel.size = null;
            _w_duel.resolution = null;
            _w_duel.filename = null;
            let _w_hinge = null;
            if (_w_seine["Content-Length"]) {
                if (_w_seine["Content-Length"] >= 1024 * 1024 * 1024) {
                    _w_hinge = (_w_seine["Content-Length"] / 1024 / 1024 / 1024).toFixed(2) + "GB";
                } else if (_w_seine["Content-Length"] >= 1024 * 1024) {
                    _w_hinge = (_w_seine["Content-Length"] / 1024 / 1024).toFixed(2) + "MB";
                } else {
                    _w_hinge = (_w_seine["Content-Length"] / 1024).toFixed(2) + "KB";
                }
                _w_duel.size = _w_hinge;
            }
            _w_duel.filename = _w_buck.pathname.substring(_w_buck.pathname.lastIndexOf("/") + 1);
            let _w_signal = {};
            let _w_spite = _w_debate(details.tabId);
            let _w_cadet;
            let _w_yarn = _w_ewe(details.tabId);
            let _w_gambol = details.url;
            if (_w_yarn) {
                while (_w_yarn[_w_gambol] && _w_gambol != _w_yarn[_w_gambol]) {
                    _w_gambol = _w_yarn[_w_gambol];
                    if (_w_gambol == details.url) {
                        break;
                    }
                }
            }
            _w_spite && (_w_cadet = _w_spite[_w_gambol]);
            _w_signal[_w_gambol] = {
                title: "",
                alt: "",
                serial: _w_flair++,
                referer: _w_cadet
            };
            let _w_court = _w_coward(details.tabId);
            if (_w_court && !_w_court[_w_gambol]) {
                let _w_martyr = _w_wraith(details.tabId);
                _w_cello(_w_signal, _w_martyr);
            }
        } else if (details.type && details.type == "media" || _w_brute && _w_brute.indexOf("video/") > -1 || _w_brute && _w_brute.indexOf("audio/") > -1) {}
    }), {
        urls: [ "<all_urls>" ]
    }, [ "blocking", "responseHeaders" ]);
    chrome.webRequest.onBeforeRedirect.addListener((function(details) {
        if (details.redirectUrl == details.url) return;
        if (!window._w_grouse[details.tabId]) {
            return;
        }
        let _w_menace = details.tabId;
        let _w_crab = window._w_grouse[_w_menace].url;
        if (_w_lament(_w_crab) || /^[a-z]+-extension:\/\//gi.test(_w_crab)) {
            let _w_sinew = _w_debate(_w_menace);
            if (_w_sinew && _w_sinew[details.url] && !_w_sinew[details.redirectUrl]) {
                _w_sinew[details.redirectUrl] = _w_sinew[details.url];
            }
        }
        let _w_yarn = _w_ewe(_w_menace);
        if (_w_yarn) {
            let _w_veneer = details.url;
            let _w_tedium = true;
            while (_w_yarn[_w_veneer] && _w_veneer != _w_yarn[_w_veneer]) {
                _w_veneer = _w_yarn[_w_veneer];
                if (_w_veneer == details.redirectUrl) {
                    _w_tedium = false;
                    break;
                }
            }
            if (_w_tedium) _w_yarn[details.redirectUrl] = details.url;
        }
    }), {
        urls: [ "<all_urls>" ]
    }, [ "responseHeaders" ]);
    let _w_closet = [ "blocking", "requestHeaders" ];
    if (_w_scald >= 72) _w_closet.push("extraHeaders");
    chrome.webRequest.onBeforeSendHeaders.addListener((function(details) {
        let _w_forger = false;
        let _w_prig = false;
        let _w_wit = [];
        if (details.tabId == -1) {
            return _w_ginger(details);
        }
        for (let i = 0; i < details.requestHeaders.length; ++i) {
            if (details.requestHeaders[i].name === "Referer") {
                _w_forger = true;
                _w_wit.push(i);
                let _w_sinew = _w_debate(details.tabId);
                if (_w_sinew && _w_sinew[details.url]) {
                    details.requestHeaders[i].value = _w_sinew[details.url];
                } else if (_w_sinew) {
                    _w_sinew[details.url] = details.requestHeaders[i].value;
                }
                let _w_huddle = details.requestHeaders[i].value;
                if (_w_huddle == _w_typo || _w_huddle == _w_gamut || _w_huddle == null) {
                    _w_prig = true;
                }
            } else if (details.requestHeaders[i].name === "IA-Tag") {
                _w_wit.push(i);
                _w_prig = true;
            }
        }
        if (_w_prig && _w_wit.length > 0) {
            _w_wit.reverse();
            for (let idx in _w_wit) {
                details.requestHeaders.splice(_w_wit[idx], 1);
            }
        }
        if (!_w_forger) {
            let _w_sinew = _w_debate(details.tabId);
            if (_w_sinew && _w_sinew[details.url] && _w_sinew[details.url] != _w_typo && _w_sinew[details.url] != _w_gamut) {
                details.requestHeaders.push({
                    name: "Referer",
                    value: _w_sinew[details.url]
                });
            }
        }
        return {
            requestHeaders: details.requestHeaders
        };
    }), {
        urls: [ "<all_urls>" ]
    }, _w_closet);
}

function _w_fealty(_w_mystic, url) {
    let _w_sandal = url;
    let _w_frond = _w_quest(_w_mystic);
    if (_w_frond) {
        url = _w_girdle(url);
        let _w_stench = _w_frond[url];
        if (_w_stench && _w_stench.title && _w_stench.title.length > 0) {
            _w_sandal = _w_stench.title;
        }
    }
    return _w_sandal;
}

function _w_ridge(_w_satire) {
    let _w_husk = {
        YYYYMMDD: "YYYY-MM-DD",
        HHmmss: "HH-mm-ss"
    };
    let _w_mesa = _w_cuff(_w_satire);
    if (_w_mesa) _w_husk = _w_mesa.timeStamp;
    return _w_husk;
}

function _w_ginger(details) {
    let _w_forger = false;
    let _w_scathe = false;
    let _w_mare = -1;
    let _w_brood = undefined;
    for (let i = 0; i < details.requestHeaders.length; ++i) {
        if (details.requestHeaders[i].name === "Referer") {
            _w_forger = true;
            _w_mare = i;
        }
    }
    Object.values(_w_spell).forEach((function(tab) {
        if (tab._w_sinew && tab._w_sinew[details.url] && tab._w_sinew[details.url] != _w_typo && tab._w_sinew[details.url] != _w_gamut) {
            _w_brood = tab._w_sinew[details.url];
        }
    }));
    if (_w_brood && _w_forger) {
        details.requestHeaders[_w_mare].value = _w_brood;
    } else if (_w_brood) {
        details.requestHeaders.push({
            name: "Referer",
            value: _w_brood
        });
    }
    return {
        requestHeaders: details.requestHeaders
    };
}

_w_swirl();

chrome.runtime.onMessage.addListener((function(message, sender, callback) {
    message && message.type == "_w_ennui" && _w_reward(sender.tab.id);
    message && message.type == "_w_scoop" && _w_scoop(message._w_gospel, message._w_tusk);
    message && message.type == "_w_sop" && _w_sop(message._w_gospel, message._w_tusk, message._w_clique);
    message && message.type == "_w_intent" && _w_cello(message.images, message.extractorHash);
    message && message.type == "_w_slew" && window._w_hermit && window._w_hermit.notifyFullScroll(sender.tab.id);
    message && message.type == "_w_crypt" && _w_knack(message._w_sinew, sender.tab.id, true);
    message && message.type == "_w_school" && _w_bit(message.pageInfo, message.extractorHash);
    message && message.type == "_w_loaf" && callback(_w_lumen());
    message && message.type == "_w_chord" && _w_chord(message.url, message.action, sender.tab.id, message.createNewTab);
    message && message.type == "_w_vanity" && _w_vanity(sender.tab.id, message.fetchLevel);
    message && message.type == "_w_spoke" && window._w_spurn.addTask((function(beginFun, endFun) {
        _w_prick(message.requestParam, message.requestHash, sender.tab.id, beginFun, endFun);
    }));
    message && message.type == "_w_aviary" && (localStorage["folderMark"] = message.folderMark);
    message && message.type == "_w_homage" && callback(_w_homage());
    message && message.type == "_w_array" && callback(_w_hermit.requestNum(sender.tab.id));
}));

function _w_hassle(_w_mystic, callback) {
    chrome.runtime.sendMessage(_w_psalm(), {
        type: "_w_hassle",
        extractorHash: _w_mystic
    }, callback);
}

function _w_buckle(_w_sap, _w_throne) {
    !_w_throne && (_w_throne = false);
    let _w_axle = function(_w_mesa) {
        _w_swine(_w_mesa.id, (function(observedTab) {
            let _w_veneer = observedTab.url;
            let _w_pylon = _w_hide(32);
            let _w_nausea = "multiUrlExtractor.html?msgChannel=" + _w_pylon;
            if (_w_log(_w_veneer)) {
                _w_nausea += "&originalUrl=" + encodeURIComponent(_w_veneer);
            }
            chrome.tabs.create({
                url: _w_nausea,
                active: true
            }, (function(newTab) {
                if (_w_log(observedTab.url)) {
                    _w_cell(observedTab.id, newTab.id, _w_pylon, _w_throne);
                }
            }));
        }));
    };
    if (_w_sap) {
        _w_axle(_w_sap);
    } else {
        chrome.tabs.query({
            active: true,
            currentWindow: true
        }, (function(tabArray) {
            if (!tabArray || tabArray.length === 0) return;
            let _w_prime = tabArray[0];
            _w_axle(_w_prime);
        }));
    }
}

function _w_mint(_w_tusk) {
    chrome.tabs.query({
        active: true,
        currentWindow: true
    }, (function(tabArray) {
        if (!tabArray || tabArray.length == 0) return;
        let _w_prime = tabArray[0];
        _w_vanity(_w_prime.id, _w_tusk);
    }));
}

function _w_epoch(url, _w_tusk) {
    chrome.tabs.create({
        url: url,
        active: false
    }, (function(tab) {
        chrome.tabs.onUpdated.addListener((function updatedFun(tabId, changeInfo, updatedTab) {
            if (tabId == tab.id && changeInfo.url) {
                chrome.tabs.onUpdated.removeListener(updatedFun);
                _w_vanity(tabId, _w_tusk);
            }
        }));
    }));
}

function _w_odium(url) {
    chrome.tabs.create({
        url: url,
        active: true
    }, (function(tab) {
        chrome.tabs.onUpdated.addListener((function updatedFun(tabId, changeInfo, updatedTab) {
            if (tabId == tab.id && changeInfo.url) {
                chrome.tabs.onUpdated.removeListener(updatedFun);
                _w_buckle(tab, true);
            }
        }));
    }));
}

function _w_vanity(_w_gospel, _w_tusk) {
    _w_dolt(_w_gospel, _w_tusk, true);
}

function _w_dolt(_w_gospel, _w_tusk, _w_carat) {
    chrome.tabs.get(_w_gospel, (function(tab) {
        if (_w_log(tab.url) || _w_toll(tab.url)) {
            let _w_croak = setTimeout((function() {
                chrome.tabs.create({
                    index: tab.index + 1,
                    url: "imageExtractor.html?tabId=" + tab.id + "&fetchLevel=" + _w_tusk,
                    active: _w_carat ? true : false
                });
            }), 512);
            _w_hassle(_w_wraith(tab.id), (function callback(data) {
                if (!chrome.runtime.lastError && data) {
                    clearTimeout(_w_croak);
                    chrome.tabs.update(data.tabId, {
                        active: true
                    }, (function(tab) {
                        chrome.windows.update(tab.windowId, {
                            focused: true,
                            drawAttention: false
                        });
                    }));
                }
            }));
        } else {
            chrome.notifications.create("", {
                type: "basic",
                iconUrl: "./images/icon512.png",
                title: _w_acme("_w_helot"),
                message: _w_acme("_w_fag")
            });
        }
    }));
}

function _w_scoop(_w_gospel, _w_tusk) {
    let _w_clique = _w_wraith(_w_gospel) + _w_swirl();
    _w_sop(_w_gospel, _w_tusk, _w_clique);
}

function _w_sop(_w_gospel, _w_tusk, _w_clique) {
    _w_den(_w_gospel, [ {
        file: "libs/jquery/3.4.1/jquery-3.4.1.min.js",
        runAt: "document_end",
        allFrames: true
    }, {
        file: "libs/DOMPurify/2.0.8/purify.min.js",
        runAt: "document_end",
        allFrames: true
    }, {
        file: "scripts/function.js",
        runAt: "document_end",
        allFrames: true
    }, {
        file: "scripts/mime.js",
        runAt: "document_end",
        allFrames: true
    }, {
        file: "scripts/script.js",
        runAt: "document_end",
        allFrames: true
    }, {
        code: "_w_minnow(" + _w_tusk + ', "' + _w_clique + '");',
        runAt: "document_end",
        allFrames: true
    } ]);
}

function _w_cell(_w_ferret, _w_poll, _w_canon, _w_throne) {
    _w_den(_w_ferret, [ {
        file: "libs/jquery/3.4.1/jquery-3.4.1.min.js",
        runAt: "document_end",
        allFrames: false
    }, {
        file: "scripts/function.js",
        runAt: "document_end",
        allFrames: false
    }, {
        file: "scripts/mime.js",
        runAt: "document_end",
        allFrames: false
    }, {
        file: "scripts/characteristicUrlExtract.js",
        runAt: "document_end",
        allFrames: false
    }, {
        code: '_w_pane(_w_ration, "' + _w_canon + '", ' + _w_throne + ");",
        runAt: "document_end",
        allFrames: false
    } ]);
}

function _w_chord(_w_jerk, _w_ferry, _w_idyll, _w_amity) {
    let _w_iota = function(execTab) {
        if (!_w_ferry || _w_ferry.trim() == "") return;
        chrome.tabs.onUpdated.addListener((function updatedFun(tabId, changeInfo) {
            if (tabId == execTab.id && changeInfo.url) {
                chrome.tabs.onUpdated.removeListener(updatedFun);
                _w_den(tabId, [ {
                    file: "libs/jquery/3.4.1/jquery-3.4.1.min.js",
                    runAt: "document_end",
                    allFrames: true
                }, {
                    file: "scripts/function.js",
                    runAt: "document_start",
                    allFrames: true
                }, {
                    file: "scripts/scriptForthirdPartPage.js",
                    runAt: "document_start",
                    allFrames: true
                }, {
                    code: _w_ferry + "();",
                    runAt: "document_start",
                    allFrames: true
                } ]);
            }
        }));
    };
    if (_w_amity) {
        chrome.tabs.create({
            url: _w_jerk,
            active: true
        }, _w_iota);
    } else {
        chrome.tabs.update(_w_idyll, {
            url: _w_jerk,
            active: true
        }, _w_iota);
    }
}

window._w_avowal = true;

function _w_credo(url) {
    let _w_poncho = [];
    for (let idx in window._w_guru) {
        let _w_augury = window._w_guru[idx]["urlRegexp"];
        if (_w_augury.test(url)) {
            _w_poncho.push(window._w_guru[idx]["urlRuleStr"]);
            if (!window._w_avowal) break;
        }
    }
    return _w_poncho;
}

function _w_flail(url, deepth) {
    let _w_diva = {};
    if (!_w_muck) {
        return Object.keys(_w_diva);
    } else if (!Number.isInteger(deepth)) {
        deepth = 4;
    } else if (deepth <= 0) {
        return Object.keys(_w_diva);
    } else {
        deepth--;
    }
    for (let idx in window._w_guru) {
        let _w_augury = window._w_guru[idx]["urlRegexp"];
        let _w_poise = window._w_guru[idx]["urlReplace"];
        if (_w_augury.test(url)) {
            let _w_sock = url.replace(_w_augury, _w_poise);
            if (_w_sock != url) {
                _w_diva[_w_sock] = true;
                let _w_scoff = _w_flail(_w_sock, deepth);
                _w_scoff.forEach((function(result_url) {
                    _w_diva[result_url] = true;
                }));
                if (!window._w_avowal) break;
            }
        }
    }
    return Object.keys(_w_diva);
}

function _w_bit(_w_fusion, _w_mystic) {
    let _w_menace = _w_trio(_w_mystic);
    let _w_lyric = _w_stem(_w_menace);
    if (_w_lyric) {
        _w_school(_w_fusion, _w_trio(_w_lyric));
    }
    _w_school(_w_fusion, _w_trio(_w_mystic));
}

function _w_school(_w_fusion, _w_gospel) {
    if (!_w_gospel) {
        return;
    }
    let _w_cape = _w_fume(_w_gospel);
    let _w_morsel = _w_girdle(_w_fusion["url"]);
    _w_fusion["url"] = _w_morsel;
    let _w_stench = _w_cape[_w_morsel];
    if (_w_stench == null) {
        _w_cape[_w_morsel] = _w_fusion;
    } else {
        for (let key in _w_fusion) {
            _w_stench[key] == null ? _w_stench[key] = _w_fusion[key] : _w_fusion[key] == null ? "" : _w_fusion[key].length > _w_stench[key].length ? _w_stench[key] = _w_fusion[key] : "";
        }
    }
}

function _w_cello(_w_rag, _w_mystic) {
    let _w_menace = _w_trio(_w_mystic);
    let _w_lyric = _w_stem(_w_menace);
    if (_w_lyric) {
        _w_intent(_w_rag, _w_trio(_w_lyric));
    }
    _w_intent(_w_rag, _w_trio(_w_mystic));
}

function _w_arroyo(_w_rag) {
    Object.keys(_w_rag).forEach((itemUrl => {
        if (/^https?:\/\/.*/i.test(itemUrl) || /^data:.*/i.test(itemUrl)) return;
        if (itemUrl.startsWith("//")) {
            let protocol = "https:";
            let referer = _w_rag[itemUrl] ? _w_rag[itemUrl].referer : null;
            if (referer && /^(https?:)\/\/.*/i.test(referer)) {
                protocol = /^(https?:)\/\/.*/i.exec(referer)[1];
            }
            let newItemUrl = protocol + itemUrl;
            if (!_w_rag[newItemUrl]) {
                _w_rag[newItemUrl] = _w_rag[itemUrl];
                delete _w_rag[itemUrl];
            }
        }
    }));
    return _w_rag;
}

function _w_intent(_w_rag, _w_gospel) {
    _w_arroyo(_w_rag);
    if (!_w_gospel) {
        return;
    }
    if (window._w_hermit) {
        window._w_hermit.notifyPushImage(_w_gospel);
    }
    let _w_court = _w_coward(_w_gospel);
    let _w_poseur = _w_lament(window._w_grouse[_w_gospel].url);
    let _w_pest = function(item, _w_condor, _w_weasel) {
        if (_w_tuxedo(item)) {} else if (!_w_court[item]) {
            _w_court[item] = _w_condor;
            _w_court.push(item);
        } else {
            let _w_arch = 0;
            if (_w_condor.title && _w_condor.title.length > 0 && _w_court[item].title != _w_condor.title) {
                _w_court[item].title = _w_condor.title;
                _w_arch |= 1;
            }
            if (_w_condor.alt && _w_condor.alt.length > 0 && _w_court[item].alt != _w_condor.alt) {
                _w_court[item].alt = _w_condor.alt;
                _w_arch |= 2;
            }
            if (_w_condor.referer && _w_condor.referer.length > 0 && _w_court[item].referer != _w_condor.referer) {
                _w_court[item].referer = _w_condor.referer;
                _w_arch |= 4;
            }
            if (_w_condor.serial && _w_condor.serial < _w_court[item].serial && _w_court[item].serial != _w_condor.serial) {
                _w_court[item].serial = _w_condor.serial;
                _w_arch |= 8;
            }
            if (_w_arch > 0) _w_weasel[_w_court.indexOf(item)] = _w_arch;
        }
    };
    let _w_weasel = {};
    for (let item in _w_rag) {
        try {
            let _w_condor = _w_rag[item];
            item = _w_hull(item);
            _w_condor.referer = _w_hull(_w_condor.referer);
            if (_w_poseur) item = _w_want(item);
            try {
                let _w_drove = _w_flail(item);
                for (let ridx in _w_drove) {
                    _w_pest(_w_drove[ridx], _w_condor, _w_weasel);
                }
            } catch (exception) {}
            _w_pest(item, _w_condor, _w_weasel);
        } catch (exception) {}
    }
    chrome.runtime.sendMessage(_w_psalm(), {
        type: "_w_gallon",
        extractorHash: _w_wraith(_w_gospel)
    });
    if (Object.keys(_w_weasel).length > 0) {
        chrome.runtime.sendMessage(_w_psalm(), {
            type: "_w_bale",
            extractorHash: _w_wraith(_w_gospel),
            ItemIdxMap: _w_weasel
        });
    }
}

function _w_knack(_w_spite, _w_gospel, _w_alcove) {
    let _w_sinew = _w_debate(_w_gospel);
    for (let _w_slough in _w_spite) {
        if (_w_slough.indexOf(_w_typo) == 0 || _w_slough.indexOf(_w_gamut) == 0) {
            continue;
        }
        if (_w_sinew && (!_w_sinew[_w_slough] || _w_sinew[_w_slough].length == 0 || _w_alcove)) {
            _w_sinew[_w_slough] = _w_spite[_w_slough];
        }
    }
}

function _w_den(_w_gospel, _w_tenor, _w_sleigh) {
    function createCallback(_w_gospel, injectDetails, innerCallback) {
        return function() {
            chrome.tabs.executeScript(_w_gospel, injectDetails, innerCallback);
        };
    }
    while (_w_tenor.length > 0) {
        _w_sleigh = createCallback(_w_gospel, _w_tenor.pop(), _w_sleigh);
    }
    if (_w_sleigh !== null) {
        _w_sleigh();
    }
}

function _w_gauge(_w_gospel, _w_tenor, _w_sleigh) {
    function createCallback(_w_gospel, _w_retail, _w_dune) {
        return function() {
            chrome.tabs.insertCSS(_w_gospel, _w_retail, _w_dune);
        };
    }
    while (_w_tenor.length > 0) {
        _w_sleigh = createCallback(_w_gospel, _w_tenor.pop(), _w_sleigh);
    }
    if (_w_sleigh !== null) {
        _w_sleigh();
    }
}

chrome.commands.onCommand.addListener((function(command) {
    if (command == "command_extract_images") {
        _w_mint(0);
    } else if (command == "command_multi_extract_images") {
        _w_buckle();
    }
}));

function _w_eaglet() {
    let _w_loon = {
        width: 10,
        height: 10
    };
    let _w_fang = Number.parseInt(_w_totem("defaultFunnelWidth"));
    let _w_tremor = Number.parseInt(_w_totem("defaultFunnelHeight"));
    _w_fang && _w_fang > 0 && (_w_loon.width = _w_fang);
    _w_tremor && _w_tremor > 0 && (_w_loon.height = _w_tremor);
    return _w_loon;
}

function _w_loop(width, height) {
    let _w_fang = Number.parseInt(width);
    let _w_tremor = Number.parseInt(height);
    _w_fang && _w_awl("defaultFunnelWidth", _w_fang);
    _w_tremor && _w_awl("defaultFunnelHeight", _w_tremor);
}

function _w_homage() {
    let _w_ploy;
    let _w_cone = _w_totem("image_size");
    _w_cone ? _w_ploy = _w_quiver(_w_cone.split(",")) : _w_ploy = _w_choir();
    return _w_ploy;
}

function _w_choir() {
    return _w_quiver(_w_blight.slice(0));
}

function _w_deputy() {
    let _w_ploy = _w_choir();
    _w_awl("image_size", _w_ploy);
}

const _w_phobia = (() => {
    const default_config = {};
    _w_smudge.forEach((suffix => default_config[suffix] = false));
    delete default_config["png"];
    return default_config;
})();

function _w_molt(_w_genome) {
    if (!_w_genome) return false;
    _w_genome = _w_genome.replace(/\./gi, "");
    let _w_elite = _w_whim();
    if (_w_elite[_w_genome] == true) {
        return true;
    } else if (typeof _w_elite[_w_genome] == "undefined" && _w_elite["_"]) {
        return true;
    } else {
        return false;
    }
}

function _w_whim() {
    let _w_elite = {
        ..._w_phobia
    };
    const _w_screed = localStorage["convert_format_when_downloading"];
    if (_w_screed) {
        try {
            Object.assign(_w_elite, JSON.parse(_w_screed));
        } catch (e) {}
    }
    return _w_elite;
}

function _w_toady(_w_mite) {
    let _w_elite = {
        ..._w_phobia
    };
    if (_w_mite) {
        Object.keys(_w_elite).forEach((key => {
            if (_w_mite[key]) {
                _w_elite[key] = true;
            } else {
                _w_elite[key] = false;
            }
        }));
    }
    localStorage["convert_format_when_downloading"] = JSON.stringify(_w_elite);
}

function _w_cougar(width, height) {
    let _w_boor = Number.parseInt(width);
    let _w_split = Number.parseInt(height);
    let _w_rue = _w_boor && _w_split ? _w_boor + "x" + _w_split : null;
    let _w_ploy = _w_homage();
    if (!_w_ploy[_w_rue]) {
        _w_ploy.push(_w_rue);
        _w_ploy = _w_quiver(_w_ploy);
        _w_ploy[_w_rue] = {
            width: _w_boor,
            height: _w_split
        };
        _w_awl("image_size", _w_ploy);
    }
}

function _w_limbo(width, height) {
    let _w_boor = Number.parseInt(width);
    let _w_split = Number.parseInt(height);
    let _w_spear = _w_boor && _w_split ? _w_boor + "x" + _w_split : null;
    let _w_ploy = _w_homage();
    if (_w_ploy[_w_spear]) {
        delete _w_ploy[_w_spear];
        let _w_prop = _w_ploy.indexOf(_w_spear);
        _w_prop > -1 && _w_ploy.splice(_w_prop, 1);
        _w_awl("image_size", _w_ploy);
    }
}

function _w_donor() {
    let _w_sibyl = 27;
    let _w_ewer = 31;
    let _w_valor = Number.parseInt(_w_totem("menu_status"));
    if (_w_valor >= 0) {
        _w_sibyl = _w_ewer & _w_valor;
    }
    return _w_sibyl;
}

function _w_ranger(status) {
    let _w_ewer = 31;
    let _w_sonata = Number.parseInt(status);
    if (_w_sonata >= 0) {
        _w_sonata = _w_sonata & _w_ewer;
        _w_awl("menu_status", _w_sonata);
    }
}

function _w_wont() {
    let _w_tyrant = _w_psalm();
    let _w_plaza = _w_totem(_w_tyrant);
    if (!_w_plaza || _w_plaza.length < 32) {
        _w_awl(_w_tyrant, _w_hide(32));
        _w_plaza = _w_totem(_w_tyrant);
    }
    return _w_plaza;
}

function _w_cachet() {
    let _w_gadget = Number.parseInt(localStorage["dyLoadSize"]);
    if (!_w_gadget) {
        _w_gadget = 256;
        _w_torso(_w_gadget);
    }
    return _w_gadget;
}

function _w_torso(_w_muddle) {
    _w_muddle = Number.parseInt(_w_muddle);
    if (!_w_muddle) _w_muddle = 256; else if (_w_muddle < 64) _w_muddle = 64; else if (_w_muddle > 2048) _w_muddle = 2048;
    localStorage["dyLoadSize"] = _w_muddle;
}

function _w_herald() {
    let _w_lurch = Number.parseInt(localStorage["extMaxLoad"]);
    if (!_w_lurch) {
        _w_lurch = 1024;
        _w_fawn(_w_lurch);
    }
    return _w_lurch;
}

function _w_fawn(_w_plinth) {
    _w_plinth = Number.parseInt(_w_plinth);
    if (!_w_plinth) _w_plinth = 1024; else if (_w_plinth < 1024) _w_plinth = 1024; else if (_w_plinth > 4096) _w_plinth = 4096;
    localStorage["extMaxLoad"] = _w_plinth;
}

function _w_trophy() {
    let _w_badger = localStorage["extClickAct"];
    if (typeof _w_badger == "undefined") {
        _w_badger = false;
        _w_despot(_w_badger);
    }
    return _w_badger;
}

function _w_despot(_w_finale) {
    if (_w_finale) {
        _w_finale = true;
    } else {
        _w_finale = false;
    }
    localStorage["extClickAct"] = _w_finale;
}

function _w_piazza() {
    let _w_saddle = localStorage["regexpUrlRule"];
    if (!_w_saddle || _w_saddle.trim().length == 0) {
        _w_saddle = window._w_twig;
        setTimeout((function() {
            _w_roe(_w_saddle);
        }), 2e3);
    }
    return _w_saddle;
}

function _w_roe(_w_apron) {
    localStorage["regexpUrlRule"] = _w_apron;
    return _w_miasma();
}

function _w_miasma() {
    let _w_sect = [];
    let _w_sprig = [];
    let _w_dagger = localStorage["_pullywood_RegexpUrlRule"];
    if (!_w_dagger) {
        _w_dagger = "";
    }
    let _w_puddle = _w_piazza().concat("\n\r").concat(window._w_twig).concat("\n\r").concat(_w_dagger).split(/[\n\r]+/);
    window._w_glow = _w_flora();
    for (let idx in _w_puddle) {
        let _w_strand = _w_puddle[idx].trim();
        if (_w_strand.length == 0 || _w_strand.startsWith("//") || _w_strand.split("{#^_^#}").length != 2) {
            continue;
        } else {
            if (window._w_glow.has(_w_strand)) continue;
            window._w_glow.add(_w_strand);
            let _w_miser = _w_strand.split("{#^_^#}");
            let _w_augury = _w_miser[0];
            let _w_poise = _w_miser[1];
            try {
                _w_augury = new RegExp(_w_augury);
                _w_sect.push({
                    urlRegexp: _w_augury,
                    urlReplace: _w_poise,
                    urlRuleStr: _w_strand
                });
            } catch (exception) {
                _w_sprig.push(_w_augury);
            }
        }
    }
    window._w_guru = _w_sect;
    return _w_sprig;
}

function _w_totem(_w_code) {
    if (!_w_code || _w_code.length == 0) return;
    let _w_fleece = localStorage[_w_code];
    chrome.storage.sync.get(_w_code, (function(data) {
        let _w_eddy = data[_w_code];
        if ((!_w_eddy || _w_eddy.length == 0) && _w_fleece && _w_fleece.length > 0) {
            _w_awl(_w_code, _w_fleece);
        } else if (_w_eddy && _w_fleece != _w_eddy && _w_eddy.length > 0) {
            localStorage[_w_code] = _w_eddy;
        }
    }));
    return _w_fleece;
}

function _w_awl(keyStr, valueStr) {
    valueStr = String(valueStr);
    if (!keyStr || keyStr.length == 0 || !valueStr || valueStr.length == 0) return;
    if (keyStr == _w_psalm()) {
        chrome.storage.sync.get(keyStr, (function(data) {
            let _w_eddy = data[keyStr];
            if ((!_w_eddy || _w_eddy.length != 32) && valueStr.length == 32) {
                let _w_falcon = {};
                _w_falcon[keyStr] = valueStr;
                chrome.storage.sync.set(_w_falcon, (function() {}));
                localStorage[keyStr] = valueStr;
            } else {
                localStorage[keyStr] = _w_eddy;
            }
        }));
        return;
    }
    let _w_falcon = {};
    _w_falcon[keyStr] = valueStr;
    chrome.storage.sync.set(_w_falcon, (function() {}));
    localStorage[keyStr] = valueStr;
}

chrome.storage.onChanged.addListener((function(changes, namespace) {
    for (let key in changes) {
        let _w_levy = changes[key];
        localStorage[key] = _w_levy.newValue;
    }
}));

$.ajax({
    method: "get",
    url: "http://www.pullywood.com/ImageAssistant/version.json?" + Math.random(),
    contentType: "application/json",
    mimeType: "application/json"
}).done((function(data) {
    let _w_fury = /Firefox/i.test(navigator.userAgent) ? "version_firefox" : /Edg/i.test(navigator.userAgent) ? "version_edge" : "version";
    data && data[_w_fury] && (localStorage["version"] = data[_w_fury]);
})).always((function() {
    if (localStorage["version"] && localStorage["version"] > chrome.runtime.getManifest().version) {
        chrome.browserAction.setBadgeText({
            text: "new"
        });
        chrome.browserAction.setBadgeBackgroundColor({
            color: "#FF3F3F"
        });
    } else {
        chrome.browserAction.setBadgeText({
            text: null
        });
        chrome.browserAction.setBadgeBackgroundColor({
            color: "#4285F4"
        });
    }
}));

function _w_lumen() {
    return window._w_cereal++;
}

function _w_prick(_w_vault, _w_pestle, tabId, _w_drill, _w_adage) {
    _w_drill();
    _w_vault["timeout"] = _w_clout;
    _w_vault["headers"]["Accept"] = "*/*; charset=UTF-8";
    _w_vault["headers"]["Cache-Control"] = "no-cache, no-store, must-revalidate, max-age=0, post-check=0, pre-check=0";
    _w_vault["headers"]["Pragma"] = "no-cache";
    _w_vault["headers"]["Expires"] = "0";
    $.ajax(_w_vault).always((function(data, status, xhr) {
        _w_adage();
        if (status == "success") {
            let _w_bigot = {};
            if (xhr.getAllResponseHeaders) {
                let _w_belch = xhr.getAllResponseHeaders().split("\n");
                for (let idx in _w_belch) {
                    let _w_planet = _w_belch[idx];
                    let _w_folder = _w_planet.indexOf(":");
                    let _w_vent = _w_planet.slice(0, _w_folder).trim();
                    let _w_regent = _w_planet.slice(_w_folder + 1).trim();
                    if (_w_vent.length == 0 || _w_regent.length == 0) {
                        continue;
                    } else if (!_w_bigot[_w_vent]) {
                        _w_bigot[_w_vent] = _w_regent;
                    } else if (typeof _w_bigot[_w_vent] == "string") {
                        let _w_swipe = [];
                        _w_swipe.push(_w_bigot[_w_vent]);
                        _w_swipe.push(_w_regent);
                        _w_bigot[_w_vent] = _w_swipe;
                    } else if (_w_bigot[_w_vent].push) {
                        _w_bigot[_w_vent].push(_w_regent);
                    }
                }
            }
            xhr["responseHeaders"] = _w_bigot;
        }
        chrome.tabs.sendMessage(tabId, {
            type: "_w_heresy",
            data: data,
            status: status,
            xhr: xhr,
            requestHash: _w_pestle
        });
    }));
}

chrome.contextMenus.create({
    title: _w_acme("_w_leeway"),
    id: "_w_strip",
    contexts: [ "all" ]
}, (function() {
    chrome.contextMenus.create({
        title: _w_acme("_w_chord"),
        id: "_w_crest",
        parentId: "_w_strip",
        contexts: [ "link" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_shoal"),
        id: "_w_resort",
        parentId: "_w_strip",
        contexts: [ "link" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_heir"),
        id: "_w_weed",
        parentId: "_w_strip",
        contexts: [ "image" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_pore"),
        id: "_w_tread",
        parentId: "_w_strip",
        contexts: [ "image" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_lust"),
        id: "_w_chant",
        parentId: "_w_strip",
        contexts: [ "image" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_chant"),
        id: "_w_toil",
        parentId: "_w_strip",
        contexts: [ "image" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_dose"),
        id: "_w_regime",
        parentId: "_w_strip",
        contexts: [ "image" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_fluke"),
        id: "_w_pluck",
        parentId: "_w_strip",
        contexts: [ "image" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_bench"),
        id: "_w_diver",
        parentId: "_w_strip",
        contexts: [ "selection" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_pastry"),
        id: "_w_kidney",
        parentId: "_w_strip",
        contexts: [ "selection" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_dogma"),
        id: "_w_arena",
        parentId: "_w_strip",
        contexts: [ "selection" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_welter").trim(),
        id: "_w_squash",
        parentId: "_w_strip",
        contexts: [ "page", "frame" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_mortar").trim(),
        id: "_w_rancor",
        parentId: "_w_strip",
        contexts: [ "page", "frame" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_solace").trim(),
        id: "_w_jargon",
        parentId: "_w_strip",
        contexts: [ "page", "frame" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_harbor").trim(),
        id: "_w_plain",
        parentId: "_w_strip",
        contexts: [ "page", "frame" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_filth"),
        id: "_w_bedlam",
        parentId: "_w_strip",
        contexts: [ "page", "frame" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_raisin"),
        id: "_w_revue",
        parentId: "_w_strip",
        contexts: [ "link" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_lumber"),
        id: "_w_bazaar",
        parentId: "_w_strip",
        contexts: [ "image" ]
    }, (function() {}));
    chrome.contextMenus.create({
        title: _w_acme("_w_dismay"),
        id: "_w_schism",
        parentId: "_w_strip",
        contexts: [ "image" ]
    }, (function() {}));
}));

chrome.contextMenus.onClicked.addListener((function(info, tab) {
    let _w_splint = null;
    if (info.menuItemId == "_w_bazaar") {
        _w_splint = _w_bazaar(info.srcUrl, info.pageUrl, tab.id);
    } else if (info.menuItemId == "_w_schism") {
        _w_splint = _w_schism(info.srcUrl, info.pageUrl, tab.id);
    } else if (info.menuItemId == "_w_weed") {
        _w_splint = _w_weed(info.srcUrl);
    } else if (info.menuItemId == "_w_tread") {
        _w_splint = _w_tread(info.srcUrl);
    } else if (info.menuItemId == "_w_chant") {
        _w_splint = _w_chant(info.srcUrl);
    } else if (info.menuItemId == "_w_toil") {
        _w_splint = _w_toil(info.srcUrl);
    } else if (info.menuItemId == "_w_regime") {
        _w_splint = _w_regime(info.srcUrl);
    } else if (info.menuItemId == "_w_diver") {
        _w_splint = _w_strait(info.selectionText);
    } else if (info.menuItemId == "_w_kidney") {
        _w_splint = _w_sage(info.selectionText);
    } else if (info.menuItemId == "_w_crest") {
        _w_epoch(info.linkUrl, 0);
    } else if (info.menuItemId == "_w_resort") {
        _w_odium(info.linkUrl);
    } else if (info.menuItemId == "_w_squash") {
        _w_vanity(tab.id, 0);
    } else if (info.menuItemId == "_w_rancor") {
        _w_vanity(tab.id, 1);
    } else if (info.menuItemId == "_w_jargon") {
        _w_vanity(tab.id, 3);
    } else if (info.menuItemId == "_w_plain") {
        _w_buckle();
    } else if (info.menuItemId == "_w_arena") {
        _w_combat(tab.id, info.selectionText);
    } else if (info.menuItemId == "_w_pluck") {
        _w_combat(tab.id, info.srcUrl);
    } else if (info.menuItemId == "_w_revue") {
        _w_combat(tab.id, info.linkUrl);
    } else if (info.menuItemId == "_w_bedlam") {
        _w_combat(tab.id, info.pageUrl);
    } else {
        return;
    }
    if (_w_splint) {
        if (_w_lament(info.pageUrl)) _w_splint.url = _w_want(_w_splint.url);
        _w_chord(_w_splint.url, _w_splint.action, tab.id, _w_splint.createNewTab);
    }
}));

function _w_bazaar(_w_slough, _w_sprout, _w_gospel) {
    if (!_w_slough) {
        return;
    } else if (!_w_slough.startsWith("data:image") && _w_lament(_w_sprout)) {
        let _w_sinew = _w_debate(_w_gospel);
        if (_w_sinew[_w_slough]) {
            _w_sprout = _w_sinew[_w_slough];
        }
    }
    chrome.tabs.create({
        url: "imageEditor.html",
        active: true
    }, (function(tab) {
        _w_feud(tab.id, (function(_w_ballad) {
            chrome.tabs.sendMessage(_w_ballad.id, {
                type: "_w_bazaar",
                _w_finery: _w_slough,
                _w_sprout: _w_sprout
            });
        }));
    }));
}

function _w_schism(_w_slough, _w_sprout, _w_gospel) {
    if (!_w_slough.startsWith("data:image") && _w_lament(_w_sprout)) {
        let _w_sinew = _w_debate(_w_gospel);
        if (_w_sinew[_w_slough]) {
            _w_sprout = _w_sinew[_w_slough];
        }
    }
    chrome.runtime.sendMessage(chrome.runtime.id, {
        type: "_w_otter",
        _w_finery: _w_slough,
        _w_sprout: _w_sprout
    });
}

function _w_combat(_w_gospel, _w_medal) {
    if (!_w_gospel) return;
    chrome.tabs.get(_w_gospel, (function(tab) {
        let _w_jerk = new URL(tab.url);
        if (_w_forte(_w_jerk.href)) {
            chrome.runtime.sendMessage(_w_psalm(), {
                type: "_w_mallet",
                text: _w_medal
            });
        } else if (_w_jerk.protocol == "http:" || _w_jerk.protocol == "https:") {
            _w_den(_w_gospel, [ {
                file: "libs/jquery/3.4.1/jquery-3.4.1.min.js",
                allFrames: false
            }, {
                file: "libs/qrcode/dist/qrcode.js",
                allFrames: false
            }, {
                file: "libs/bootstrap/3.4.1/js/bootstrap.min.js",
                allFrames: false
            }, {
                file: "scripts/function.js",
                allFrames: false
            }, {
                code: "_w_mallet(" + JSON.stringify(_w_medal) + ", true);",
                allFrames: false
            } ]);
        } else {
            chrome.notifications.create("", {
                type: "basic",
                iconUrl: "./images/icon512.png",
                title: _w_acme("_w_helot"),
                message: _w_acme("_w_tease")
            });
        }
    }));
}