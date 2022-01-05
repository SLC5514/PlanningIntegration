/**
 * ImageAssistant
 * Project Home: http://www.pullywood.com/ImageAssistant/
 * Author: 睡虫子(Joey)
 * Copyright (C) 2013-2020 普利坞(Pullywood.com)
**/
"use strict";

function _w_threat(_w_nomad) {
    let _w_burrow = [];
    for (let idx in _w_nomad) {
        let _w_jerk = _w_nomad[idx];
        if ("string" != typeof _w_jerk || !_w_jerk.startsWith("http")) continue;
        let _w_sentry = _w_jerk.split(/([0-9]+)/);
        _w_burrow.push(_w_sentry);
    }
    return _w_burrow;
}

function _w_ration(_w_nomad) {
    let _w_burrow = [];
    for (let idx in _w_nomad) {
        let _w_jerk = _w_nomad[idx];
        if ("string" != typeof _w_jerk || !_w_jerk.startsWith("http")) continue;
        let _w_sentry = _w_jerk.split(/([^0-9a-zA-Z%]+|\d+(?=[\/_\?#]))/);
        let _w_foible = _w_jerk.split(/([^0-9a-zA-Z%\-_]+)/);
        _w_burrow.push(_w_sentry);
        if (JSON.stringify(_w_sentry) != JSON.stringify(_w_foible)) {
            _w_burrow.push(_w_foible);
        }
    }
    return _w_burrow;
}

function _w_pane(_w_lure, _w_pylon, _w_grace) {
    let _w_blast = document.links;
    let _w_nomad = [];
    let _w_dimple = {};
    let _w_hawser = window.location.href;
    if (_w_hawser.indexOf("#") > 0) _w_hawser = _w_hawser.substring(0, _w_hawser.indexOf("#"));
    for (let idx in _w_blast) {
        let _w_dismay = _w_blast[idx];
        if (_w_dismay.protocol && (_w_dismay.protocol == "http:" || _w_dismay.protocol == "https:")) {
            let _w_bile = _w_dismay.href.trim();
            if (_w_bile.indexOf("#") > 0) _w_bile = _w_bile.substring(0, _w_bile.indexOf("#"));
            if (_w_nomad.indexOf(_w_bile) < 0) {
                _w_nomad.push(_w_bile);
                _w_dimple[_w_bile] = _w_dismay;
            } else if (_w_dismay.text.trim().length > _w_dimple[_w_bile].text.trim().length) {
                _w_dimple[_w_bile] = _w_dismay;
            }
        }
    }
    if (_w_nomad.indexOf(_w_hawser) < 0) {
        _w_nomad.push(_w_hawser);
    }
    _w_dimple[_w_hawser] = $("<a />", {
        href: _w_hawser,
        title: document.title,
        text: document.title
    })[0];
    let _w_burrow = _w_lure(_w_nomad);
    let _w_mimic = {};
    for (let i = 0; i < _w_burrow.length; ++i) {
        for (let j = i + 1; j < _w_burrow.length; ++j) {
            let _w_yoke = _w_burrow[i];
            let _w_lava = _w_burrow[j];
            if (_w_yoke.length != _w_lava.length) continue;
            let _w_urchin = 0;
            let _w_rage = _w_yoke.length;
            let _w_haunt = "";
            let _w_lout = "";
            let _w_owl = "";
            let _w_ravine = 0;
            let _w_helm = [];
            for (let cnt = 0; cnt < _w_rage; ++cnt) {
                if (_w_yoke[cnt] != _w_lava[cnt]) {
                    if (_w_urchin != 0) {
                        _w_urchin = Number.MAX_SAFE_INTEGER;
                        break;
                    }
                    _w_lout = _w_haunt;
                    _w_haunt = "";
                    _w_ravine = cnt;
                    _w_helm.push(_w_yoke[cnt]);
                    _w_helm.push(_w_lava[cnt]);
                    _w_urchin++;
                } else {
                    _w_haunt += _w_yoke[cnt];
                }
            }
            _w_owl = _w_haunt;
            _w_haunt = _w_lout + "*" + _w_owl;
            if (_w_urchin != 1) {
                continue;
            }
            let _w_sheen = function(a, b) {
                return a + b;
            };
            let _w_fosse = [ _w_yoke.reduce(_w_sheen), _w_lava.reduce(_w_sheen) ];
            let _w_fallow = _w_mimic[_w_haunt];
            if (_w_fallow == null) {
                _w_fallow = {};
                _w_fallow["joinedStrBeforeDiff"] = _w_lout;
                _w_fallow["joinedStrAfterDiff"] = _w_owl;
                _w_fallow["joinedStrPattern"] = _w_haunt;
                _w_fallow["diffIndex"] = _w_ravine;
                _w_fallow["diffIsNumeric"] = true;
                _w_fallow["diffBreakCount"] = 0;
                _w_fallow["diffList"] = _w_helm;
                _w_fallow["urlList"] = _w_fosse;
                _w_fallow["containCurrentPageUrl"] = false;
                _w_fallow["currentPageUrl"] = _w_hawser;
                _w_mimic[_w_haunt] = _w_fallow;
            } else {
                for (let idx in _w_helm) {
                    if (_w_fallow["diffList"].indexOf(_w_helm[idx]) < 0) {
                        _w_fallow["diffList"].push(_w_helm[idx]);
                    }
                }
                for (let idx in _w_fosse) {
                    if (_w_fallow["urlList"].indexOf(_w_fosse[idx]) < 0) {
                        _w_fallow["urlList"].push(_w_fosse[idx]);
                    }
                }
            }
            if (_w_hawser == _w_fosse[0] || _w_hawser == _w_fosse[1]) {
                _w_fallow["containCurrentPageUrl"] = true;
            }
        }
    }
    let _w_con = [];
    for (let pattern in _w_mimic) {
        _w_con.push(_w_mimic[pattern]);
    }
    for (let idx in _w_con) {
        let _w_doyen = _w_con[idx];
        _w_doyen["diffList"].sort((function(a, b) {
            if (jQuery.isNumeric(a) && jQuery.isNumeric(b)) {
                return parseInt(a) - parseInt(b);
            } else {
                return a - b;
            }
        }));
        let _w_jade = 0;
        let _w_purse = 5;
        let _w_helm = _w_doyen["diffList"];
        for (let idx = 0; idx < _w_helm.length - 1; ++idx) {
            if (idx == 0 && _w_helm[idx] == "") {
                continue;
            } else if (!jQuery.isNumeric(_w_helm[idx]) || !jQuery.isNumeric(_w_helm[idx + 1])) {
                _w_jade = Number.MAX_VALUE;
                _w_doyen["diffIsNumeric"] = false;
            } else if (_w_helm[idx + 1] - _w_helm[idx] > 1) {
                _w_jade += 1;
            }
        }
        _w_doyen["diffBreakCount"] = _w_jade;
        if (_w_jade > _w_purse) {
            _w_doyen["fillList"] = _w_doyen["diffList"];
        } else if (_w_helm[_w_helm.length - 1] - _w_helm.length > 1024) {
            _w_doyen["fillList"] = _w_doyen["diffList"];
        } else {
            let _w_marsh = _w_helm[0].toString()[0] == "0" && _w_helm[0].toString().length > 1 ? _w_helm[0].toString().length : 1;
            _w_doyen["fillList"] = [];
            for (let i = 0; i <= _w_helm[_w_helm.length - 1]; ++i) {
                _w_doyen["fillList"].push(_w_snivel(i, _w_marsh));
            }
        }
        let _w_lout = _w_doyen["joinedStrBeforeDiff"];
        let _w_owl = _w_doyen["joinedStrAfterDiff"];
        _w_doyen["urlList"] = [];
        _w_doyen["urlText"] = {};
        _w_helm = _w_doyen["diffList"];
        for (let itemIdx in _w_helm) {
            let _w_curfew = _w_helm[itemIdx];
            let _w_jerk = _w_lout + _w_curfew + _w_owl;
            let _w_maple = _w_dimple[_w_jerk] ? _w_dimple[_w_jerk].text : "无文字链接[" + _w_curfew + "]";
            _w_maple = _w_maple.trim().length > 0 ? _w_maple.trim() : _w_jerk;
            _w_doyen["urlList"].push(_w_jerk);
            _w_doyen["urlText"][_w_jerk] = _w_maple;
        }
        _w_doyen["fillUrlList"] = [];
        _w_doyen["fillUrlText"] = {};
        let _w_mask = _w_doyen["fillList"];
        let _w_handle = false;
        for (let itemIdx in _w_mask) {
            let _w_chunk = _w_mask[itemIdx];
            let _w_jerk = _w_lout + _w_chunk + _w_owl;
            let _w_maple = _w_dimple[_w_jerk] ? _w_dimple[_w_jerk].text : "智能填充链接[" + _w_chunk + "]";
            _w_maple = _w_maple.trim().length > 0 ? _w_maple.trim() : _w_jerk;
            let _w_venom = _w_lout.substr(-1);
            if (!_w_handle && (_w_venom == "-" || _w_venom == "_" || _w_venom == "/") && (_w_owl.length == 0 || _w_owl[0] == ".") && (_w_chunk == 0 || _w_chunk == 1)) {
                let _w_layman;
                if (_w_venom == "/") {
                    _w_layman = _w_lout;
                } else {
                    _w_layman = _w_lout.slice(0, -1) + _w_owl;
                }
                let _w_bench = _w_dimple[_w_layman] ? _w_dimple[_w_layman].text : "智能填充链接[ ]";
                _w_doyen["fillUrlList"].push(_w_layman);
                _w_doyen["fillUrlText"][_w_layman] = _w_bench;
                _w_handle = true;
                if (_w_doyen["currentPageUrl"] == _w_layman) {
                    _w_doyen["containCurrentPageUrl"] = true;
                    _w_doyen["urlList"].push(_w_layman);
                    _w_doyen["urlText"][_w_layman] = _w_bench;
                }
            }
            _w_doyen["fillUrlList"].push(_w_jerk);
            _w_doyen["fillUrlText"][_w_jerk] = _w_maple;
        }
    }
    _w_con.sort((function(a, b) {
        let _w_elite = 0;
        if (a["containCurrentPageUrl"] && !b["containCurrentPageUrl"]) {
            _w_elite = -1;
        } else if (!a["containCurrentPageUrl"] && b["containCurrentPageUrl"]) {
            _w_elite = 1;
        }
        if (_w_elite == 0) {
            if (a["diffIsNumeric"] && !b["diffIsNumeric"]) {
                _w_elite = -1;
            } else if (!a["diffIsNumeric"] && b["diffIsNumeric"]) {
                _w_elite = 1;
            }
        }
        if (_w_elite == 0) {
            _w_elite = a["diffBreakCount"] - b["diffBreakCount"];
        }
        if (_w_elite == 0) {
            if (a["diffList"] && b["diffList"]) {
                _w_elite = b["diffList"].length - a["diffList"].length;
            } else {
                _w_elite = a - b;
            }
        }
        if (_w_elite == 0 && a.diffIndex != null && b.diffIndex != null) {
            _w_elite = a.diffIndex - b.diffIndex;
        }
        return _w_elite;
    }));
    let _w_gorge = {};
    Object.entries(_w_dimple).forEach((arr => {
        _w_gorge[arr[0]] = arr[1].text;
    }));
    let _w_thrall = function() {
        chrome.runtime.sendMessage(chrome.runtime.id, {
            type: "_w_raisin",
            collections: _w_con,
            links: _w_gorge,
            currentPageUrl: _w_hawser,
            currentPageTitle: document.title,
            channel: _w_pylon
        });
    };
    setTimeout((() => {
        _w_thrall();
        _w_grace && _w_skiff();
    }), 500);
}

