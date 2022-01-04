'use strict';

var fntk = require('fntk');

function _interopDefaultLegacy (e) { return e && typeof e === 'object' && 'default' in e ? e : { 'default': e }; }

var fntk__default = /*#__PURE__*/_interopDefaultLegacy(fntk);

var commonjsGlobal = typeof globalThis !== 'undefined' ? globalThis : typeof window !== 'undefined' ? window : typeof global !== 'undefined' ? global : typeof self !== 'undefined' ? self : {};

var theAnswer = {exports: {}};

(function (module, exports) {
(function (global, factory) {
	module.exports = factory() ;
}(commonjsGlobal, (function () {
var index = 42;

return index;

})));
}(theAnswer));

var answer = theAnswer.exports;

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
  console.log('the answer is ' + answer);
  console.log(fntk__default["default"]);
});

module.exports = main;
