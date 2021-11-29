/*!
 * @pixi/filter-blur - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/filter-blur is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
'use strict';

Object.defineProperty(exports, '__esModule', { value: true });

var core = require('@pixi/core');
var settings = require('@pixi/settings');

var vertTemplate = "\n    attribute vec2 aVertexPosition;\n\n    uniform mat3 projectionMatrix;\n\n    uniform float strength;\n\n    varying vec2 vBlurTexCoords[%size%];\n\n    uniform vec4 inputSize;\n    uniform vec4 outputFrame;\n    \n    vec4 filterVertexPosition( void )\n    {\n        vec2 position = aVertexPosition * max(outputFrame.zw, vec2(0.)) + outputFrame.xy;\n    \n        return vec4((projectionMatrix * vec3(position, 1.0)).xy, 0.0, 1.0);\n    }\n    \n    vec2 filterTextureCoord( void )\n    {\n        return aVertexPosition * (outputFrame.zw * inputSize.zw);\n    }\n\n    void main(void)\n    {\n        gl_Position = filterVertexPosition();\n\n        vec2 textureCoord = filterTextureCoord();\n        %blur%\n    }";

function generateBlurVertSource(kernelSize, x)
{
    var halfLength = Math.ceil(kernelSize / 2);

    var vertSource = vertTemplate;

    var blurLoop = '';
    var template;
    // let value;

    if (x)
    {
        template = 'vBlurTexCoords[%index%] =  textureCoord + vec2(%sampleIndex% * strength, 0.0);';
    }
    else
    {
        template = 'vBlurTexCoords[%index%] =  textureCoord + vec2(0.0, %sampleIndex% * strength);';
    }

    for (var i = 0; i < kernelSize; i++)
    {
        var blur = template.replace('%index%', i);

        // value = i;

        // if(i >= halfLength)
        // {
        //     value = kernelSize - i - 1;
        // }

        blur = blur.replace('%sampleIndex%', ((i - (halfLength - 1)) + ".0"));

        blurLoop += blur;
        blurLoop += '\n';
    }

    vertSource = vertSource.replace('%blur%', blurLoop);
    vertSource = vertSource.replace('%size%', kernelSize);

    return vertSource;
}

var GAUSSIAN_VALUES = {
    5: [0.153388, 0.221461, 0.250301],
    7: [0.071303, 0.131514, 0.189879, 0.214607],
    9: [0.028532, 0.067234, 0.124009, 0.179044, 0.20236],
    11: [0.0093, 0.028002, 0.065984, 0.121703, 0.175713, 0.198596],
    13: [0.002406, 0.009255, 0.027867, 0.065666, 0.121117, 0.174868, 0.197641],
    15: [0.000489, 0.002403, 0.009246, 0.02784, 0.065602, 0.120999, 0.174697, 0.197448],
};

var fragTemplate = [
    'varying vec2 vBlurTexCoords[%size%];',
    'uniform sampler2D uSampler;',

    'void main(void)',
    '{',
    '    gl_FragColor = vec4(0.0);',
    '    %blur%',
    '}' ].join('\n');

function generateBlurFragSource(kernelSize)
{
    var kernel = GAUSSIAN_VALUES[kernelSize];
    var halfLength = kernel.length;

    var fragSource = fragTemplate;

    var blurLoop = '';
    var template = 'gl_FragColor += texture2D(uSampler, vBlurTexCoords[%index%]) * %value%;';
    var value;

    for (var i = 0; i < kernelSize; i++)
    {
        var blur = template.replace('%index%', i);

        value = i;

        if (i >= halfLength)
        {
            value = kernelSize - i - 1;
        }

        blur = blur.replace('%value%', kernel[value]);

        blurLoop += blur;
        blurLoop += '\n';
    }

    fragSource = fragSource.replace('%blur%', blurLoop);
    fragSource = fragSource.replace('%size%', kernelSize);

    return fragSource;
}

/**
 * The BlurFilterPass applies a horizontal or vertical Gaussian blur to an object.
 *
 * @class
 * @extends PIXI.Filter
 * @memberof PIXI.filters
 */
var BlurFilterPass = /*@__PURE__*/(function (Filter) {
    function BlurFilterPass(horizontal, strength, quality, resolution, kernelSize)
    {
        kernelSize = kernelSize || 5;
        var vertSrc = generateBlurVertSource(kernelSize, horizontal);
        var fragSrc = generateBlurFragSource(kernelSize);

        Filter.call(
            // vertex shader
            this, vertSrc,
            // fragment shader
            fragSrc
        );

        this.horizontal = horizontal;

        this.resolution = resolution || settings.settings.RESOLUTION;

        this._quality = 0;

        this.quality = quality || 4;

        this.blur = strength || 8;
    }

    if ( Filter ) BlurFilterPass.__proto__ = Filter;
    BlurFilterPass.prototype = Object.create( Filter && Filter.prototype );
    BlurFilterPass.prototype.constructor = BlurFilterPass;

    var prototypeAccessors = { blur: { configurable: true },quality: { configurable: true } };

    BlurFilterPass.prototype.apply = function apply (filterManager, input, output, clear)
    {
        if (output)
        {
            if (this.horizontal)
            {
                this.uniforms.strength = (1 / output.width) * (output.width / input.width);
            }
            else
            {
                this.uniforms.strength = (1 / output.height) * (output.height / input.height);
            }
        }
        else
        {
            if (this.horizontal) // eslint-disable-line
            {
                this.uniforms.strength = (1 / filterManager.renderer.width) * (filterManager.renderer.width / input.width);
            }
            else
            {
                this.uniforms.strength = (1 / filterManager.renderer.height) * (filterManager.renderer.height / input.height); // eslint-disable-line
            }
        }

        // screen space!
        this.uniforms.strength *= this.strength;
        this.uniforms.strength /= this.passes;

        if (this.passes === 1)
        {
            filterManager.applyFilter(this, input, output, clear);
        }
        else
        {
            var renderTarget = filterManager.getFilterTexture();
            var renderer = filterManager.renderer;

            var flip = input;
            var flop = renderTarget;

            this.state.blend = false;
            filterManager.applyFilter(this, flip, flop, false);

            for (var i = 1; i < this.passes - 1; i++)
            {
                renderer.renderTexture.bind(flip, flip.filterFrame);

                this.uniforms.uSampler = flop;

                var temp = flop;

                flop = flip;
                flip = temp;

                renderer.shader.bind(this);
                renderer.geometry.draw(5);
            }

            this.state.blend = true;
            filterManager.applyFilter(this, flop, output, clear);
            filterManager.returnFilterTexture(renderTarget);
        }
    };
    /**
     * Sets the strength of both the blur.
     *
     * @member {number}
     * @default 16
     */
    prototypeAccessors.blur.get = function ()
    {
        return this.strength;
    };

    prototypeAccessors.blur.set = function (value) // eslint-disable-line require-jsdoc
    {
        this.padding = 1 + (Math.abs(value) * 2);
        this.strength = value;
    };

    /**
     * Sets the quality of the blur by modifying the number of passes. More passes means higher
     * quaility bluring but the lower the performance.
     *
     * @member {number}
     * @default 4
     */
    prototypeAccessors.quality.get = function ()
    {
        return this._quality;
    };

    prototypeAccessors.quality.set = function (value) // eslint-disable-line require-jsdoc
    {
        this._quality = value;
        this.passes = value;
    };

    Object.defineProperties( BlurFilterPass.prototype, prototypeAccessors );

    return BlurFilterPass;
}(core.Filter));

/**
 * The BlurFilter applies a Gaussian blur to an object.
 *
 * The strength of the blur can be set for the x-axis and y-axis separately.
 *
 * @class
 * @extends PIXI.Filter
 * @memberof PIXI.filters
 */
var BlurFilter = /*@__PURE__*/(function (Filter) {
    function BlurFilter(strength, quality, resolution, kernelSize)
    {
        Filter.call(this);

        this.blurXFilter = new BlurFilterPass(true, strength, quality, resolution, kernelSize);
        this.blurYFilter = new BlurFilterPass(false, strength, quality, resolution, kernelSize);

        this.resolution = resolution || settings.settings.RESOLUTION;
        this.quality = quality || 4;
        this.blur = strength || 8;

        this.repeatEdgePixels = false;
    }

    if ( Filter ) BlurFilter.__proto__ = Filter;
    BlurFilter.prototype = Object.create( Filter && Filter.prototype );
    BlurFilter.prototype.constructor = BlurFilter;

    var prototypeAccessors = { blur: { configurable: true },quality: { configurable: true },blurX: { configurable: true },blurY: { configurable: true },blendMode: { configurable: true },repeatEdgePixels: { configurable: true } };

    /**
     * Applies the filter.
     *
     * @param {PIXI.systems.FilterSystem} filterManager - The manager.
     * @param {PIXI.RenderTexture} input - The input target.
     * @param {PIXI.RenderTexture} output - The output target.
     */
    BlurFilter.prototype.apply = function apply (filterManager, input, output, clear)
    {
        var xStrength = Math.abs(this.blurXFilter.strength);
        var yStrength = Math.abs(this.blurYFilter.strength);

        if (xStrength && yStrength)
        {
            var renderTarget = filterManager.getFilterTexture();

            this.blurXFilter.apply(filterManager, input, renderTarget, true);
            this.blurYFilter.apply(filterManager, renderTarget, output, clear);

            filterManager.returnFilterTexture(renderTarget);
        }
        else if (yStrength)
        {
            this.blurYFilter.apply(filterManager, input, output, clear);
        }
        else
        {
            this.blurXFilter.apply(filterManager, input, output, clear);
        }
    };

    BlurFilter.prototype.updatePadding = function updatePadding ()
    {
        if (this._repeatEdgePixels)
        {
            this.padding = 0;
        }
        else
        {
            this.padding = Math.max(Math.abs(this.blurXFilter.strength), Math.abs(this.blurYFilter.strength)) * 2;
        }
    };

    /**
     * Sets the strength of both the blurX and blurY properties simultaneously
     *
     * @member {number}
     * @default 2
     */
    prototypeAccessors.blur.get = function ()
    {
        return this.blurXFilter.blur;
    };

    prototypeAccessors.blur.set = function (value) // eslint-disable-line require-jsdoc
    {
        this.blurXFilter.blur = this.blurYFilter.blur = value;
        this.updatePadding();
    };

    /**
     * Sets the number of passes for blur. More passes means higher quaility bluring.
     *
     * @member {number}
     * @default 1
     */
    prototypeAccessors.quality.get = function ()
    {
        return this.blurXFilter.quality;
    };

    prototypeAccessors.quality.set = function (value) // eslint-disable-line require-jsdoc
    {
        this.blurXFilter.quality = this.blurYFilter.quality = value;
    };

    /**
     * Sets the strength of the blurX property
     *
     * @member {number}
     * @default 2
     */
    prototypeAccessors.blurX.get = function ()
    {
        return this.blurXFilter.blur;
    };

    prototypeAccessors.blurX.set = function (value) // eslint-disable-line require-jsdoc
    {
        this.blurXFilter.blur = value;
        this.updatePadding();
    };

    /**
     * Sets the strength of the blurY property
     *
     * @member {number}
     * @default 2
     */
    prototypeAccessors.blurY.get = function ()
    {
        return this.blurYFilter.blur;
    };

    prototypeAccessors.blurY.set = function (value) // eslint-disable-line require-jsdoc
    {
        this.blurYFilter.blur = value;
        this.updatePadding();
    };

    /**
     * Sets the blendmode of the filter
     *
     * @member {number}
     * @default PIXI.BLEND_MODES.NORMAL
     */
    prototypeAccessors.blendMode.get = function ()
    {
        return this.blurYFilter.blendMode;
    };

    prototypeAccessors.blendMode.set = function (value) // eslint-disable-line require-jsdoc
    {
        this.blurYFilter.blendMode = value;
    };

    /**
     * If set to true the edge of the target will be clamped
     *
     * @member {bool}
     * @default false
     */
    prototypeAccessors.repeatEdgePixels.get = function ()
    {
        return this._repeatEdgePixels;
    };

    prototypeAccessors.repeatEdgePixels.set = function (value)
    {
        this._repeatEdgePixels = value;
        this.updatePadding();
    };

    Object.defineProperties( BlurFilter.prototype, prototypeAccessors );

    return BlurFilter;
}(core.Filter));

exports.BlurFilter = BlurFilter;
exports.BlurFilterPass = BlurFilterPass;
//# sourceMappingURL=filter-blur.js.map