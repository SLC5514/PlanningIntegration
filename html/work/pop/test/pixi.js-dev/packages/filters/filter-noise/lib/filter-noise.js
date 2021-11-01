/*!
 * @pixi/filter-noise - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/filter-noise is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
'use strict';

Object.defineProperty(exports, '__esModule', { value: true });

var core = require('@pixi/core');

var fragment = "precision highp float;\n\nvarying vec2 vTextureCoord;\nvarying vec4 vColor;\n\nuniform float uNoise;\nuniform float uSeed;\nuniform sampler2D uSampler;\n\nfloat rand(vec2 co)\n{\n    return fract(sin(dot(co.xy, vec2(12.9898, 78.233))) * 43758.5453);\n}\n\nvoid main()\n{\n    vec4 color = texture2D(uSampler, vTextureCoord);\n    float randomValue = rand(gl_FragCoord.xy * uSeed);\n    float diff = (randomValue - 0.5) * uNoise;\n\n    // Un-premultiply alpha before applying the color matrix. See issue #3539.\n    if (color.a > 0.0) {\n        color.rgb /= color.a;\n    }\n\n    color.r += diff;\n    color.g += diff;\n    color.b += diff;\n\n    // Premultiply alpha again.\n    color.rgb *= color.a;\n\n    gl_FragColor = color;\n}\n";

/**
 * @author Vico @vicocotea
 * original filter: https://github.com/evanw/glfx.js/blob/master/src/filters/adjust/noise.js
 */

/**
 * A Noise effect filter.
 *
 * @class
 * @extends PIXI.Filter
 * @memberof PIXI.filters
 */
var NoiseFilter = /*@__PURE__*/(function (Filter) {
    function NoiseFilter(noise, seed)
    {
        if ( noise === void 0 ) noise = 0.5;
        if ( seed === void 0 ) seed = Math.random();

        Filter.call(this, core.defaultFilterVertex, fragment, {
            uNoise: 0,
            uSeed: 0,
        });

        this.noise = noise;
        this.seed = seed;
    }

    if ( Filter ) NoiseFilter.__proto__ = Filter;
    NoiseFilter.prototype = Object.create( Filter && Filter.prototype );
    NoiseFilter.prototype.constructor = NoiseFilter;

    var prototypeAccessors = { noise: { configurable: true },seed: { configurable: true } };

    /**
     * The amount of noise to apply, this value should be in the range (0, 1].
     *
     * @member {number}
     * @default 0.5
     */
    prototypeAccessors.noise.get = function ()
    {
        return this.uniforms.uNoise;
    };

    prototypeAccessors.noise.set = function (value) // eslint-disable-line require-jsdoc
    {
        this.uniforms.uNoise = value;
    };

    /**
     * A seed value to apply to the random noise generation. `Math.random()` is a good value to use.
     *
     * @member {number}
     */
    prototypeAccessors.seed.get = function ()
    {
        return this.uniforms.uSeed;
    };

    prototypeAccessors.seed.set = function (value) // eslint-disable-line require-jsdoc
    {
        this.uniforms.uSeed = value;
    };

    Object.defineProperties( NoiseFilter.prototype, prototypeAccessors );

    return NoiseFilter;
}(core.Filter));

exports.NoiseFilter = NoiseFilter;
//# sourceMappingURL=filter-noise.js.map
