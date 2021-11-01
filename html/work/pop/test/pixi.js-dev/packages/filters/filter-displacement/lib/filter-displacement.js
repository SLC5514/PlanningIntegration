/*!
 * @pixi/filter-displacement - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/filter-displacement is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
'use strict';

Object.defineProperty(exports, '__esModule', { value: true });

var core = require('@pixi/core');
var math = require('@pixi/math');

var vertex = "attribute vec2 aVertexPosition;\n\nuniform mat3 projectionMatrix;\nuniform mat3 filterMatrix;\n\nvarying vec2 vTextureCoord;\nvarying vec2 vFilterCoord;\n\nuniform vec4 inputSize;\nuniform vec4 outputFrame;\n\nvec4 filterVertexPosition( void )\n{\n    vec2 position = aVertexPosition * max(outputFrame.zw, vec2(0.)) + outputFrame.xy;\n\n    return vec4((projectionMatrix * vec3(position, 1.0)).xy, 0.0, 1.0);\n}\n\nvec2 filterTextureCoord( void )\n{\n    return aVertexPosition * (outputFrame.zw * inputSize.zw);\n}\n\nvoid main(void)\n{\n\tgl_Position = filterVertexPosition();\n\tvTextureCoord = filterTextureCoord();\n\tvFilterCoord = ( filterMatrix * vec3( vTextureCoord, 1.0)  ).xy;\n}\n";

var fragment = "varying vec2 vFilterCoord;\nvarying vec2 vTextureCoord;\n\nuniform vec2 scale;\nuniform mat2 rotation;\nuniform sampler2D uSampler;\nuniform sampler2D mapSampler;\n\nuniform highp vec4 inputSize;\nuniform vec4 inputClamp;\n\nvoid main(void)\n{\n  vec4 map =  texture2D(mapSampler, vFilterCoord);\n\n  map -= 0.5;\n  map.xy = scale * inputSize.zw * (rotation * map.xy);\n\n  gl_FragColor = texture2D(uSampler, clamp(vec2(vTextureCoord.x + map.x, vTextureCoord.y + map.y), inputClamp.xy, inputClamp.zw));\n}\n";

/**
 * The DisplacementFilter class uses the pixel values from the specified texture
 * (called the displacement map) to perform a displacement of an object.
 *
 * You can use this filter to apply all manor of crazy warping effects.
 * Currently the `r` property of the texture is used to offset the `x`
 * and the `g` property of the texture is used to offset the `y`.
 *
 * The way it works is it uses the values of the displacement map to look up the
 * correct pixels to output. This means it's not technically moving the original.
 * Instead, it's starting at the output and asking "which pixel from the original goes here".
 * For example, if a displacement map pixel has `red = 1` and the filter scale is `20`,
 * this filter will output the pixel approximately 20 pixels to the right of the original.
 *
 * @class
 * @extends PIXI.Filter
 * @memberof PIXI.filters
 */
var DisplacementFilter = /*@__PURE__*/(function (Filter) {
    function DisplacementFilter(sprite, scale)
    {
        var maskMatrix = new math.Matrix();

        sprite.renderable = false;

        Filter.call(this, vertex, fragment, {
            mapSampler: sprite._texture,
            filterMatrix: maskMatrix,
            scale: { x: 1, y: 1 },
            rotation: new Float32Array([1, 0, 0, 1]),
        });

        this.maskSprite = sprite;
        this.maskMatrix = maskMatrix;

        if (scale === null || scale === undefined)
        {
            scale = 20;
        }

        /**
         * scaleX, scaleY for displacements
         * @member {PIXI.Point}
         */
        this.scale = new math.Point(scale, scale);
    }

    if ( Filter ) DisplacementFilter.__proto__ = Filter;
    DisplacementFilter.prototype = Object.create( Filter && Filter.prototype );
    DisplacementFilter.prototype.constructor = DisplacementFilter;

    var prototypeAccessors = { map: { configurable: true } };

    /**
     * Applies the filter.
     *
     * @param {PIXI.systems.FilterSystem} filterManager - The manager.
     * @param {PIXI.RenderTexture} input - The input target.
     * @param {PIXI.RenderTexture} output - The output target.
     */
    DisplacementFilter.prototype.apply = function apply (filterManager, input, output)
    {
        this.uniforms.filterMatrix = filterManager.calculateSpriteMatrix(this.maskMatrix, this.maskSprite);
        this.uniforms.scale.x = this.scale.x;
        this.uniforms.scale.y = this.scale.y;

        // Extract rotation from world transform
        var wt = this.maskSprite.transform.worldTransform;
        var lenX = Math.sqrt((wt.a * wt.a) + (wt.b * wt.b));
        var lenY = Math.sqrt((wt.c * wt.c) + (wt.d * wt.d));

        if (lenX !== 0 && lenY !== 0)
        {
            this.uniforms.rotation[0] = wt.a / lenX;
            this.uniforms.rotation[1] = wt.b / lenX;
            this.uniforms.rotation[2] = wt.c / lenY;
            this.uniforms.rotation[3] = wt.d / lenY;
        }

        // draw the filter...
        filterManager.applyFilter(this, input, output);
    };

    /**
     * The texture used for the displacement map. Must be power of 2 sized texture.
     *
     * @member {PIXI.Texture}
     */
    prototypeAccessors.map.get = function ()
    {
        return this.uniforms.mapSampler;
    };

    prototypeAccessors.map.set = function (value) // eslint-disable-line require-jsdoc
    {
        this.uniforms.mapSampler = value;
    };

    Object.defineProperties( DisplacementFilter.prototype, prototypeAccessors );

    return DisplacementFilter;
}(core.Filter));

exports.DisplacementFilter = DisplacementFilter;
//# sourceMappingURL=filter-displacement.js.map
