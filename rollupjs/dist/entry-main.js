(function (global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory(require('the-answer'), require('fntk')) :
    typeof define === 'function' && define.amd ? define(['the-answer', 'fntk'], factory) :
    (global = typeof globalThis !== 'undefined' ? globalThis : global || self, global.rollupTest = factory(global.answer, global.fntk));
})(this, (function (answer, fntk) { 'use strict';

    function _interopDefaultLegacy (e) { return e && typeof e === 'object' && 'default' in e ? e : { 'default': e }; }

    var answer__default = /*#__PURE__*/_interopDefaultLegacy(answer);
    var fntk__default = /*#__PURE__*/_interopDefaultLegacy(fntk);

    /*
     * @Author: SLC
     * @Date: 2022-01-04 11:18:27
     * @LastEditors: SLC
     * @LastEditTime: 2022-01-04 11:19:33
     * @Description: file content
     */
    var aaa = 1;
    var tstest = {
        aaa: aaa,
        bbb: function () { return aaa; }
    };

    /*
     * @Author: SLC
     * @Date: 2021-12-30 15:57:01
     * @LastEditors: SLC
     * @LastEditTime: 2022-01-04 11:20:12
     * @Description: file content
     */
    console.log(tstest);
    var main = (function () {
      console.log('the answer is ' + answer__default["default"]);
      console.log(fntk__default["default"]);
    });

    return main;

}));
