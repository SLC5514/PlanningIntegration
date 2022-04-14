/**
 * ImageAssistant
 * Project Home: http://www.pullywood.com/ImageAssistant/
 * Author: 睡虫子(Joey)
 * Copyright (C) 2013-2020 普利坞(Pullywood.com)
**/
"use strict";

function _w_tonic() {
    chrome.runtime.sendMessage(_w_psalm(), {
        type: "_w_vanity",
        fetchLevel: 0
    });
}

function _w_gaggle(_w_barb) {
    if (!_w_barb) return;
    let _w_augur = window.setInterval((function() {
        for (let idx in _w_barb) {
            let $_w_levity = $(_w_barb[idx]);
            if ($_w_levity.length > 0) {
                window.clearInterval(_w_augur);
                let _w_ritual = $_w_levity.get(0).href;
                chrome.runtime.sendMessage(_w_psalm(), {
                    type: "_w_chord",
                    url: _w_ritual,
                    action: "_w_tonic",
                    createNewTab: false
                });
            }
        }
    }), 256);
}

function _w_lance() {
    _w_gaggle([ "a[href*='search']:contains('全部尺寸')", "a[href*='search']:contains('所有大小')", "a[href*='search']:contains('All sizes')" ]);
}

function _w_oracle() {
    _w_gaggle([ "a[href*='search']:contains('外观类似的图片')", "a[href*='search']:contains('看起來相似的圖片')", "a[href*='search']:contains('Visually similar images')" ]);
}

