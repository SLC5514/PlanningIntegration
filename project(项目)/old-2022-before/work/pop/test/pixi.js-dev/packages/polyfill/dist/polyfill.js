/*!
 * @pixi/polyfill - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/polyfill is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
(function (es6PromisePolyfill) {
    'use strict';

    // Support for IE 9 - 11 which does not include Promises
    if (!window.Promise)
    {
        window.Promise = es6PromisePolyfill.Polyfill;
    }

    /*
    object-assign
    (c) Sindre Sorhus
    @license MIT
    */

    'use strict';
    /* eslint-disable no-unused-vars */
    var getOwnPropertySymbols = Object.getOwnPropertySymbols;
    var hasOwnProperty = Object.prototype.hasOwnProperty;
    var propIsEnumerable = Object.prototype.propertyIsEnumerable;

    function toObject(val) {
    	if (val === null || val === undefined) {
    		throw new TypeError('Object.assign cannot be called with null or undefined');
    	}

    	return Object(val);
    }

    function shouldUseNative() {
    	try {
    		if (!Object.assign) {
    			return false;
    		}

    		// Detect buggy property enumeration order in older V8 versions.

    		// https://bugs.chromium.org/p/v8/issues/detail?id=4118
    		var test1 = new String('abc');  // eslint-disable-line no-new-wrappers
    		test1[5] = 'de';
    		if (Object.getOwnPropertyNames(test1)[0] === '5') {
    			return false;
    		}

    		// https://bugs.chromium.org/p/v8/issues/detail?id=3056
    		var test2 = {};
    		for (var i = 0; i < 10; i++) {
    			test2['_' + String.fromCharCode(i)] = i;
    		}
    		var order2 = Object.getOwnPropertyNames(test2).map(function (n) {
    			return test2[n];
    		});
    		if (order2.join('') !== '0123456789') {
    			return false;
    		}

    		// https://bugs.chromium.org/p/v8/issues/detail?id=3056
    		var test3 = {};
    		'abcdefghijklmnopqrst'.split('').forEach(function (letter) {
    			test3[letter] = letter;
    		});
    		if (Object.keys(Object.assign({}, test3)).join('') !==
    				'abcdefghijklmnopqrst') {
    			return false;
    		}

    		return true;
    	} catch (err) {
    		// We don't expect any of the above to throw, but better to be safe.
    		return false;
    	}
    }

    var _objectAssign_4_1_1_objectAssign = shouldUseNative() ? Object.assign : function (target, source) {
    	var arguments$1 = arguments;

    	var from;
    	var to = toObject(target);
    	var symbols;

    	for (var s = 1; s < arguments.length; s++) {
    		from = Object(arguments$1[s]);

    		for (var key in from) {
    			if (hasOwnProperty.call(from, key)) {
    				to[key] = from[key];
    			}
    		}

    		if (getOwnPropertySymbols) {
    			symbols = getOwnPropertySymbols(from);
    			for (var i = 0; i < symbols.length; i++) {
    				if (propIsEnumerable.call(from, symbols[i])) {
    					to[symbols[i]] = from[symbols[i]];
    				}
    			}
    		}
    	}

    	return to;
    };

    // References:

    if (!Object.assign)
    {
        Object.assign = _objectAssign_4_1_1_objectAssign;
    }

    var commonjsGlobal = typeof window !== 'undefined' ? window : typeof global !== 'undefined' ? global : typeof self !== 'undefined' ? self : {};

    function commonjsRequire () {
    	throw new Error('Dynamic requires are not currently supported by rollup-plugin-commonjs');
    }

    function unwrapExports (x) {
    	return x && x.__esModule && Object.prototype.hasOwnProperty.call(x, 'default') ? x.default : x;
    }

    function createCommonjsModule(fn, module) {
    	return module = { exports: {} }, fn(module, module.exports), module.exports;
    }

    function getCjsExportFromNamespace (n) {
    	return n && n.default || n;
    }

    // References:
    // http://paulirish.com/2011/requestanimationframe-for-smart-animating/
    // https://gist.github.com/1579671
    // http://updates.html5rocks.com/2012/05/requestAnimationFrame-API-now-with-sub-millisecond-precision
    // https://gist.github.com/timhall/4078614
    // https://github.com/Financial-Times/polyfill-service/tree/master/polyfills/requestAnimationFrame

    // Expected to be used with Browserfiy
    // Browserify automatically detects the use of `global` and passes the
    // correct reference of `global`, `self`, and finally `window`

    var ONE_FRAME_TIME = 16;

    // Date.now
    if (!(Date.now && Date.prototype.getTime))
    {
        Date.now = function now()
        {
            return new Date().getTime();
        };
    }

    // performance.now
    if (!(commonjsGlobal.performance && commonjsGlobal.performance.now))
    {
        var startTime = Date.now();

        if (!commonjsGlobal.performance)
        {
            commonjsGlobal.performance = {};
        }

        commonjsGlobal.performance.now = function () { return Date.now() - startTime; };
    }

    // requestAnimationFrame
    var lastTime = Date.now();
    var vendors = ['ms', 'moz', 'webkit', 'o'];

    for (var x = 0; x < vendors.length && !commonjsGlobal.requestAnimationFrame; ++x)
    {
        var p = vendors[x];

        commonjsGlobal.requestAnimationFrame = commonjsGlobal[(p + "RequestAnimationFrame")];
        commonjsGlobal.cancelAnimationFrame = commonjsGlobal[(p + "CancelAnimationFrame")] || commonjsGlobal[(p + "CancelRequestAnimationFrame")];
    }

    if (!commonjsGlobal.requestAnimationFrame)
    {
        commonjsGlobal.requestAnimationFrame = function (callback) {
            if (typeof callback !== 'function')
            {
                throw new TypeError((callback + "is not a function"));
            }

            var currentTime = Date.now();
            var delay = ONE_FRAME_TIME + lastTime - currentTime;

            if (delay < 0)
            {
                delay = 0;
            }

            lastTime = currentTime;

            return setTimeout(function () {
                lastTime = Date.now();
                callback(performance.now());
            }, delay);
        };
    }

    if (!commonjsGlobal.cancelAnimationFrame)
    {
        commonjsGlobal.cancelAnimationFrame = function (id) { return clearTimeout(id); };
    }

    var requestAnimationFrame = {

    };

    // References:
    // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Math/sign

    if (!Math.sign)
    {
        Math.sign = function mathSign(x)
        {
            x = Number(x);

            if (x === 0 || isNaN(x))
            {
                return x;
            }

            return x > 0 ? 1 : -1;
        };
    }

    // References:
    // https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Number/isInteger

    if (!Number.isInteger)
    {
        Number.isInteger = function numberIsInteger(value)
        {
            return typeof value === 'number' && isFinite(value) && Math.floor(value) === value;
        };
    }

    if (!window.ArrayBuffer)
    {
        window.ArrayBuffer = Array;
    }

    if (!window.Float32Array)
    {
        window.Float32Array = Array;
    }

    if (!window.Uint32Array)
    {
        window.Uint32Array = Array;
    }

    if (!window.Uint16Array)
    {
        window.Uint16Array = Array;
    }

    if (!window.Uint8Array)
    {
        window.Uint8Array = Array;
    }

    if (!window.Int32Array)
    {
        window.Int32Array = Array;
    }

}(es6PromisePolyfill));
//# sourceMappingURL=polyfill.js.map
