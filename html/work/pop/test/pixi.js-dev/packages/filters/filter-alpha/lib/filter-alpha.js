/*!
 * @pixi/filter-alpha - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/filter-alpha is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
'use strict';

Object.defineProperty(exports, '__esModule', { value: true });

var core = require('@pixi/core');

var fragment = "varying vec2 vTextureCoord;\n\nuniform sampler2D uSampler;\nuniform float uAlpha;\n\nvoid main(void)\n{\n   gl_FragColor = texture2D(uSampler, vTextureCoord) * uAlpha;\n}\n";

/**
 * Simplest filter - applies alpha.
 *
 * Use this instead of Container's alpha property to avoid visual layering of individual elements.
 * AlphaFilter applies alpha evenly across the entire display object and any opaque elements it contains.
 * If elements are not opaque, they will blend with each other anyway.
 *
 * Very handy if you want to use common features of all filters:
 *
 * 1. Assign a blendMode to this filter, blend all elements inside display object with background.
 *
 * 2. To use clipping in display coordinates, assign a filterArea to the same container that has this filter.
 *
 * @class
 * @extends PIXI.Filter
 * @memberof PIXI.filters
 */
var AlphaFilter = /*@__PURE__*/(function (Filter) {
    function AlphaFilter(alpha)
    {
        if ( alpha === void 0 ) alpha = 1.0;

        Filter.call(this, core.defaultVertex, fragment, { uAlpha: 1 });

        this.alpha = alpha;
    }

    if ( Filter ) AlphaFilter.__proto__ = Filter;
    AlphaFilter.prototype = Object.create( Filter && Filter.prototype );
    AlphaFilter.prototype.constructor = AlphaFilter;

    var prototypeAccessors = { alpha: { configurable: true } };

    /**
     * Coefficient for alpha multiplication
     *
     * @member {number}
     * @default 1
     */
    prototypeAccessors.alpha.get = function ()
    {
        return this.uniforms.uAlpha;
    };

    prototypeAccessors.alpha.set = function (value) // eslint-disable-line require-jsdoc
    {
        this.uniforms.uAlpha = value;
    };

    Object.defineProperties( AlphaFilter.prototype, prototypeAccessors );

    return AlphaFilter;
}(core.Filter));

exports.AlphaFilter = AlphaFilter;
//# sourceMappingURL=filter-alpha.js.map