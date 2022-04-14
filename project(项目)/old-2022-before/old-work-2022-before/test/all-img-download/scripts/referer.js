/**
 * ImageAssistant
 * Project Home: http://www.pullywood.com/ImageAssistant/
 * Author: 睡虫子(Joey)
 * Copyright (C) 2013-2020 普利坞(Pullywood.com)
**/
"use strict";

function _w_prank($_w_swarm) {
    let _w_sinew = {};
    $_w_swarm.each((function() {
        let _w_hurdle = $(this).attr("data-src");
        let _w_stigma = $(this).attr("data-referer");
        if (_w_hurdle) {
            _w_sinew[_w_hurdle] = _w_stigma;
            $(this).attr("data-src", _w_hurdle);
            _w_sinew[_w_hurdle] = _w_stigma;
        }
    }));
    if (Object.keys(_w_sinew).length > 0) {
        chrome.runtime.sendMessage(chrome.runtime.id, {
            type: "_w_crypt",
            refererMap: _w_sinew
        }, (function callback(data) {
            $_w_swarm.each((function() {
                $(this).on("error", (function() {
                    let _w_span = $(this).attr("data-src");
                    let $_w_tire = $(this);
                    $.ajax({
                        method: "get",
                        timeout: _w_clout,
                        url: _w_span
                    }).fail((function(xhr) {
                        if (xhr.status === 404) {
                            _w_span = _w_want(_w_span);
                            $_w_tire.attr("data-src", _w_span);
                        }
                    })).always((function() {
                        setTimeout((function() {
                            $_w_tire.removeAttr("src").attr("src", _w_span);
                        }), 2e3);
                    }));
                }));
                if ($(this).attr("src").indexOf("/static/gallery/favorite/image/loading.gif") >= 0 || $(this).attr("src").indexOf("/static/gallery/favorite/image/folder_02.png") >= 0) {
                    $(this).attr("src", $(this).attr("data-src"));
                } else {
                    (new Image).src = $(this).attr("data-src");
                }
            }));
        }));
    }
}

(function() {
    _w_prank($("img[data-src]"));
    let _w_raffle = new MutationObserver((function(_w_hawk) {
        _w_hawk.map((function(record) {
            if (record.addedNodes) {
                let _w_rag = {};
                for (let i = 0; i < record.addedNodes.length; ++i) {
                    let _w_rote = record.addedNodes.item(i);
                    let _w_swarm = $(_w_rote).find("img").toArray();
                    $(_w_rote).is("IMG") && _w_swarm.push(_w_rote);
                    _w_prank($(_w_swarm));
                }
            }
        }));
    }));
    let _w_bulk = document.body;
    let _w_mirage = {
        childList: true,
        subtree: true
    };
    _w_raffle.observe(_w_bulk, _w_mirage);
    if ($(".cxyz_btn_edit_folder").length > 0) {
        let $_w_rider = $(".callout_btn_menu");
        let $_w_pauper = $("<button />", {
            class: "btn btn-success",
            title: " 标记收藏到此文件夹"
        }).prepend($("<span />", {
            class: "glyphicon glyphicon-map-marker"
        }));
        $_w_rider.append($_w_pauper);
        $_w_pauper.on("click", (function() {
            let _w_nude = {};
            _w_nude.markTime = parseInt((new Date).getTime() / 1e3);
            _w_nude.categoryId = parseInt($(".cxyz_btn_edit_folder").attr("data-categoryId"));
            _w_nude.folderId = parseInt($(".cxyz_btn_edit_folder").attr("value"));
            chrome.runtime.sendMessage(chrome.runtime.id, {
                type: "_w_aviary",
                folderMark: JSON.stringify(_w_nude)
            });
            let $_w_wink = $("<span>(标记成功！)</span>");
            $(this).append($_w_wink);
            setTimeout((function() {
                $_w_wink.remove();
            }), 2e3);
        }));
    }
    if ($("#rs_lt_list").length > 0 || $("#rs_eq_list").length > 0) {
        chrome.runtime.sendMessage(chrome.runtime.id, {
            type: "_w_homage"
        }, (function(_w_ploy) {
            let $_w_tatter = $("#rs_lt_list");
            let $_w_slouch = $("#rs_eq_list");
            if (_w_ploy.length > 0) {
                if ($_w_tatter) $_w_tatter.append($("<li />", {
                    class: "divider",
                    role: "separator"
                }));
                if ($_w_slouch) $_w_slouch.append($("<li />", {
                    class: "divider",
                    role: "separator"
                }));
            }
            for (let i = 0; i < _w_ploy.length; ++i) {
                let _w_scope = _w_ploy[i];
                let _w_adobe = _w_scope.split("x");
                let _w_fret = parseInt(_w_adobe[0]);
                let _w_groove = parseInt(_w_adobe[1]);
                if ($_w_tatter) {
                    let $_w_memoir = $("<a />", {
                        class: "rs_lt_item",
                        text: _w_scope,
                        "data-width": _w_fret,
                        "data-height": _w_groove
                    });
                    $_w_tatter.append($("<li />").append($_w_memoir));
                }
                if ($_w_slouch) {
                    let $_w_gait = $("<a />", {
                        class: "rs_eq_item",
                        text: _w_scope,
                        "data-width": _w_fret,
                        "data-height": _w_groove
                    });
                    $_w_slouch.append($("<li />").append($_w_gait));
                }
            }
        }));
    }
})();