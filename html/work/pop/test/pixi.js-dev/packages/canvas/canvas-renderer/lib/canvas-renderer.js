/*!
 * @pixi/canvas-renderer - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/canvas-renderer is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
'use strict';

Object.defineProperty(exports, '__esModule', { value: true });

var core = require('@pixi/core');
var utils = require('@pixi/utils');
var math = require('@pixi/math');
var constants = require('@pixi/constants');
var settings = require('@pixi/settings');

/**
 * A set of functions used to handle masking.
 *
 * Sprite masking is not supported on the CanvasRenderer.
 *
 * @class
 * @memberof PIXI
 */
var CanvasMaskManager = function CanvasMaskManager(renderer)
{
    this.renderer = renderer;
};

/**
 * This method adds it to the current stack of masks.
 *
 * @param {object} maskData - the maskData that will be pushed
 */
CanvasMaskManager.prototype.pushMask = function pushMask (maskData)
{
    var renderer = this.renderer;

    renderer.context.save();

    var cacheAlpha = maskData.alpha;
    var transform = maskData.transform.worldTransform;
    var resolution = renderer.resolution;

    renderer.context.setTransform(
        transform.a * resolution,
        transform.b * resolution,
        transform.c * resolution,
        transform.d * resolution,
        transform.tx * resolution,
        transform.ty * resolution
    );

    // TODO support sprite alpha masks??
    // lots of effort required. If demand is great enough..
    if (!maskData._texture)
    {
        this.renderGraphicsShape(maskData);
        renderer.context.clip();
    }

    maskData.worldAlpha = cacheAlpha;
};

/**
 * Renders a PIXI.Graphics shape.
 *
 * @param {PIXI.Graphics} graphics - The object to render.
 */
CanvasMaskManager.prototype.renderGraphicsShape = function renderGraphicsShape (graphics)
{
    var context = this.renderer.context;
    var graphicsData = graphics.geometry.graphicsData;
    var len = graphicsData.length;

    if (len === 0)
    {
        return;
    }

    context.beginPath();

    for (var i = 0; i < len; i++)
    {
        var data = graphicsData[i];
        var shape = data.shape;

        if (data.type === math.SHAPES.POLY)
        {
            var points = shape.points;

            context.moveTo(points[0], points[1]);

            for (var j = 1; j < points.length / 2; j++)
            {
                context.lineTo(points[j * 2], points[(j * 2) + 1]);
            }

            // if the first and last point are the same close the path - much neater :)
            if (points[0] === points[points.length - 2] && points[1] === points[points.length - 1])
            {
                context.closePath();
            }
        }
        else if (data.type === math.SHAPES.RECT)
        {
            context.rect(shape.x, shape.y, shape.width, shape.height);
            context.closePath();
        }
        else if (data.type === math.SHAPES.CIRC)
        {
            // TODO - need to be Undefined!
            context.arc(shape.x, shape.y, shape.radius, 0, 2 * Math.PI);
            context.closePath();
        }
        else if (data.type === math.SHAPES.ELIP)
        {
            // ellipse code taken from: http://stackoverflow.com/questions/2172798/how-to-draw-an-oval-in-html5-canvas

            var w = shape.width * 2;
            var h = shape.height * 2;

            var x = shape.x - (w / 2);
            var y = shape.y - (h / 2);

            var kappa = 0.5522848;
            var ox = (w / 2) * kappa; // control point offset horizontal
            var oy = (h / 2) * kappa; // control point offset vertical
            var xe = x + w; // x-end
            var ye = y + h; // y-end
            var xm = x + (w / 2); // x-middle
            var ym = y + (h / 2); // y-middle

            context.moveTo(x, ym);
            context.bezierCurveTo(x, ym - oy, xm - ox, y, xm, y);
            context.bezierCurveTo(xm + ox, y, xe, ym - oy, xe, ym);
            context.bezierCurveTo(xe, ym + oy, xm + ox, ye, xm, ye);
            context.bezierCurveTo(xm - ox, ye, x, ym + oy, x, ym);
            context.closePath();
        }
        else if (data.type === math.SHAPES.RREC)
        {
            var rx = shape.x;
            var ry = shape.y;
            var width = shape.width;
            var height = shape.height;
            var radius = shape.radius;

            var maxRadius = Math.min(width, height) / 2 | 0;

            radius = radius > maxRadius ? maxRadius : radius;

            context.moveTo(rx, ry + radius);
            context.lineTo(rx, ry + height - radius);
            context.quadraticCurveTo(rx, ry + height, rx + radius, ry + height);
            context.lineTo(rx + width - radius, ry + height);
            context.quadraticCurveTo(rx + width, ry + height, rx + width, ry + height - radius);
            context.lineTo(rx + width, ry + radius);
            context.quadraticCurveTo(rx + width, ry, rx + width - radius, ry);
            context.lineTo(rx + radius, ry);
            context.quadraticCurveTo(rx, ry, rx, ry + radius);
            context.closePath();
        }
    }
};

/**
 * Restores the current drawing context to the state it was before the mask was applied.
 *
 * @param {PIXI.CanvasRenderer} renderer - The renderer context to use.
 */
CanvasMaskManager.prototype.popMask = function popMask (renderer)
{
    renderer.context.restore();
    renderer.invalidateBlendMode();
};

/**
 * Destroys this canvas mask manager.
 *
 */
CanvasMaskManager.prototype.destroy = function destroy ()
{
    /* empty */
};

/**
 * Creates a little colored canvas
 *
 * @ignore
 * @param {string} color - The color to make the canvas
 * @return {canvas} a small canvas element
 */
function createColoredCanvas(color)
{
    var canvas = document.createElement('canvas');

    canvas.width = 6;
    canvas.height = 1;

    var context = canvas.getContext('2d');

    context.fillStyle = color;
    context.fillRect(0, 0, 6, 1);

    return canvas;
}

/**
 * Checks whether the Canvas BlendModes are supported by the current browser
 *
 * @private
 * @return {boolean} whether they are supported
 */
function canUseNewCanvasBlendModes()
{
    if (typeof document === 'undefined')
    {
        return false;
    }

    var magenta = createColoredCanvas('#ff00ff');
    var yellow = createColoredCanvas('#ffff00');

    var canvas = document.createElement('canvas');

    canvas.width = 6;
    canvas.height = 1;

    var context = canvas.getContext('2d');

    context.globalCompositeOperation = 'multiply';
    context.drawImage(magenta, 0, 0);
    context.drawImage(yellow, 2, 0);

    var imageData = context.getImageData(2, 0, 1, 1);

    if (!imageData)
    {
        return false;
    }

    var data = imageData.data;

    return (data[0] === 255 && data[1] === 0 && data[2] === 0);
}

/**
 * Maps blend combinations to Canvas.
 *
 * @memberof PIXI
 * @function mapCanvasBlendModesToPixi
 * @private
 * @param {string[]} [array=[]] - The array to output into.
 * @return {string[]} Mapped modes.
 */
function mapCanvasBlendModesToPixi(array)
{
    if ( array === void 0 ) array = [];

    if (canUseNewCanvasBlendModes())
    {
        array[constants.BLEND_MODES.NORMAL] = 'source-over';
        array[constants.BLEND_MODES.ADD] = 'lighter'; // IS THIS OK???
        array[constants.BLEND_MODES.MULTIPLY] = 'multiply';
        array[constants.BLEND_MODES.SCREEN] = 'screen';
        array[constants.BLEND_MODES.OVERLAY] = 'overlay';
        array[constants.BLEND_MODES.DARKEN] = 'darken';
        array[constants.BLEND_MODES.LIGHTEN] = 'lighten';
        array[constants.BLEND_MODES.COLOR_DODGE] = 'color-dodge';
        array[constants.BLEND_MODES.COLOR_BURN] = 'color-burn';
        array[constants.BLEND_MODES.HARD_LIGHT] = 'hard-light';
        array[constants.BLEND_MODES.SOFT_LIGHT] = 'soft-light';
        array[constants.BLEND_MODES.DIFFERENCE] = 'difference';
        array[constants.BLEND_MODES.EXCLUSION] = 'exclusion';
        array[constants.BLEND_MODES.HUE] = 'hue';
        array[constants.BLEND_MODES.SATURATION] = 'saturate';
        array[constants.BLEND_MODES.COLOR] = 'color';
        array[constants.BLEND_MODES.LUMINOSITY] = 'luminosity';
    }
    else
    {
        // this means that the browser does not support the cool new blend modes in canvas 'cough' ie 'cough'
        array[constants.BLEND_MODES.NORMAL] = 'source-over';
        array[constants.BLEND_MODES.ADD] = 'lighter'; // IS THIS OK???
        array[constants.BLEND_MODES.MULTIPLY] = 'source-over';
        array[constants.BLEND_MODES.SCREEN] = 'source-over';
        array[constants.BLEND_MODES.OVERLAY] = 'source-over';
        array[constants.BLEND_MODES.DARKEN] = 'source-over';
        array[constants.BLEND_MODES.LIGHTEN] = 'source-over';
        array[constants.BLEND_MODES.COLOR_DODGE] = 'source-over';
        array[constants.BLEND_MODES.COLOR_BURN] = 'source-over';
        array[constants.BLEND_MODES.HARD_LIGHT] = 'source-over';
        array[constants.BLEND_MODES.SOFT_LIGHT] = 'source-over';
        array[constants.BLEND_MODES.DIFFERENCE] = 'source-over';
        array[constants.BLEND_MODES.EXCLUSION] = 'source-over';
        array[constants.BLEND_MODES.HUE] = 'source-over';
        array[constants.BLEND_MODES.SATURATION] = 'source-over';
        array[constants.BLEND_MODES.COLOR] = 'source-over';
        array[constants.BLEND_MODES.LUMINOSITY] = 'source-over';
    }
    // not-premultiplied, only for webgl
    array[constants.BLEND_MODES.NORMAL_NPM] = array[constants.BLEND_MODES.NORMAL];
    array[constants.BLEND_MODES.ADD_NPM] = array[constants.BLEND_MODES.ADD];
    array[constants.BLEND_MODES.SCREEN_NPM] = array[constants.BLEND_MODES.SCREEN];

    // composite operations
    array[constants.BLEND_MODES.SRC_IN] = 'source-in';
    array[constants.BLEND_MODES.SRC_OUT] = 'source-out';
    array[constants.BLEND_MODES.SRC_ATOP] = 'source-atop';
    array[constants.BLEND_MODES.DST_OVER] = 'destination-over';
    array[constants.BLEND_MODES.DST_IN] = 'destination-in';
    array[constants.BLEND_MODES.DST_OUT] = 'destination-out';
    array[constants.BLEND_MODES.DST_ATOP] = 'destination-atop';

    // SUBTRACT from flash, does not exist in canvas
    array[constants.BLEND_MODES.SUBTRACT] = 'source-over';

    return array;
}

/**
 * The CanvasRenderer draws the scene and all its content onto a 2d canvas.
 *
 * This renderer should be used for browsers that do not support WebGL.
 * Don't forget to add the CanvasRenderer.view to your DOM or you will not see anything!
 *
 * @class
 * @memberof PIXI
 * @extends PIXI.AbstractRenderer
 */
var CanvasRenderer = /*@__PURE__*/(function (AbstractRenderer) {
    function CanvasRenderer(options, arg2, arg3)
    {
        AbstractRenderer.call(this, 'Canvas', options, arg2, arg3);

        this.type = constants.RENDERER_TYPE.CANVAS;

        /**
         * The root canvas 2d context that everything is drawn with.
         *
         * @member {CanvasRenderingContext2D}
         */
        this.rootContext = this.view.getContext('2d', { alpha: this.transparent });

        /**
         * The currently active canvas 2d context (could change with renderTextures)
         *
         * @member {CanvasRenderingContext2D}
         */
        this.context = this.rootContext;

        /**
         * Boolean flag controlling canvas refresh.
         *
         * @member {boolean}
         */
        this.refresh = true;

        /**
         * Instance of a CanvasMaskManager, handles masking when using the canvas renderer.
         *
         * @member {PIXI.CanvasMaskManager}
         */
        this.maskManager = new CanvasMaskManager(this);

        /**
         * The canvas property used to set the canvas smoothing property.
         *
         * @member {string}
         */
        this.smoothProperty = 'imageSmoothingEnabled';

        if (!this.rootContext.imageSmoothingEnabled)
        {
            if (this.rootContext.webkitImageSmoothingEnabled)
            {
                this.smoothProperty = 'webkitImageSmoothingEnabled';
            }
            else if (this.rootContext.mozImageSmoothingEnabled)
            {
                this.smoothProperty = 'mozImageSmoothingEnabled';
            }
            else if (this.rootContext.oImageSmoothingEnabled)
            {
                this.smoothProperty = 'oImageSmoothingEnabled';
            }
            else if (this.rootContext.msImageSmoothingEnabled)
            {
                this.smoothProperty = 'msImageSmoothingEnabled';
            }
        }

        this.initPlugins(CanvasRenderer.__plugins);

        /**
         * Tracks the blend modes useful for this renderer.
         *
         * @member {object<number, string>}
         */
        this.blendModes = mapCanvasBlendModesToPixi();
        this._activeBlendMode = null;
        this._outerBlend = false;

        this.renderingToScreen = false;

        utils.sayHello('Canvas');

        /**
         * Fired after rendering finishes.
         *
         * @event PIXI.CanvasRenderer#postrender
         */

        /**
         * Fired before rendering starts.
         *
         * @event PIXI.CanvasRenderer#prerender
         */

        this.resize(this.options.width, this.options.height);
    }

    if ( AbstractRenderer ) CanvasRenderer.__proto__ = AbstractRenderer;
    CanvasRenderer.prototype = Object.create( AbstractRenderer && AbstractRenderer.prototype );
    CanvasRenderer.prototype.constructor = CanvasRenderer;

    /**
     * Renders the object to this canvas view
     *
     * @param {PIXI.DisplayObject} displayObject - The object to be rendered
     * @param {PIXI.RenderTexture} [renderTexture] - A render texture to be rendered to.
     *  If unset, it will render to the root context.
     * @param {boolean} [clear=false] - Whether to clear the canvas before drawing
     * @param {PIXI.Matrix} [transform] - A transformation to be applied
     * @param {boolean} [skipUpdateTransform=false] - Whether to skip the update transform
     */
    CanvasRenderer.prototype.render = function render (displayObject, renderTexture, clear, transform, skipUpdateTransform)
    {
        if (!this.view)
        {
            return;
        }

        // can be handy to know!
        this.renderingToScreen = !renderTexture;

        this.emit('prerender');

        var rootResolution = this.resolution;

        if (renderTexture)
        {
            renderTexture = renderTexture.baseTexture || renderTexture;

            if (!renderTexture._canvasRenderTarget)
            {
                renderTexture._canvasRenderTarget = new utils.CanvasRenderTarget(
                    renderTexture.width,
                    renderTexture.height,
                    renderTexture.resolution
                );
                renderTexture.source = renderTexture._canvasRenderTarget.canvas;
                renderTexture.valid = true;
            }

            this.context = renderTexture._canvasRenderTarget.context;
            this.resolution = renderTexture._canvasRenderTarget.resolution;
        }
        else
        {
            this.context = this.rootContext;
        }

        var context = this.context;

        if (!renderTexture)
        {
            this._lastObjectRendered = displayObject;
        }

        if (!skipUpdateTransform)
        {
            // update the scene graph
            var cacheParent = displayObject.parent;
            var tempWt = this._tempDisplayObjectParent.transform.worldTransform;

            if (transform)
            {
                transform.copyTo(tempWt);

                // lets not forget to flag the parent transform as dirty...
                this._tempDisplayObjectParent.transform._worldID = -1;
            }
            else
            {
                tempWt.identity();
            }

            displayObject.parent = this._tempDisplayObjectParent;

            displayObject.updateTransform();
            displayObject.parent = cacheParent;
            // displayObject.hitArea = //TODO add a temp hit area
        }

        context.save();
        context.setTransform(1, 0, 0, 1, 0, 0);
        context.globalAlpha = 1;
        this._activeBlendMode = constants.BLEND_MODES.NORMAL;
        this._outerBlend = false;
        context.globalCompositeOperation = this.blendModes[constants.BLEND_MODES.NORMAL];

        if (clear !== undefined ? clear : this.clearBeforeRender)
        {
            if (this.renderingToScreen)
            {
                if (this.transparent)
                {
                    context.clearRect(0, 0, this.width, this.height);
                }
                else
                {
                    context.fillStyle = this._backgroundColorString;
                    context.fillRect(0, 0, this.width, this.height);
                }
            } // else {
            // TODO: implement background for CanvasRenderTarget or RenderTexture?
            // }
        }

        // TODO RENDER TARGET STUFF HERE..
        var tempContext = this.context;

        this.context = context;
        displayObject.renderCanvas(this);
        this.context = tempContext;

        context.restore();

        this.resolution = rootResolution;

        this.emit('postrender');
    };

    /**
     * Clear the canvas of renderer.
     *
     * @param {string} [clearColor] - Clear the canvas with this color, except the canvas is transparent.
     */
    CanvasRenderer.prototype.clear = function clear (clearColor)
    {
        var context = this.context;

        clearColor = clearColor || this._backgroundColorString;

        if (!this.transparent && clearColor)
        {
            context.fillStyle = clearColor;
            context.fillRect(0, 0, this.width, this.height);
        }
        else
        {
            context.clearRect(0, 0, this.width, this.height);
        }
    };

    /**
     * Sets the blend mode of the renderer.
     *
     * @param {number} blendMode - See {@link PIXI.BLEND_MODES} for valid values.
     * @param {boolean} [readyForOuterBlend=false] - Some blendModes are dangerous, they affect outer space of sprite.
     * Pass `true` only if you are ready to use them.
     */
    CanvasRenderer.prototype.setBlendMode = function setBlendMode (blendMode, readyForOuterBlend)
    {
        var outerBlend = blendMode === constants.BLEND_MODES.SRC_IN
            || blendMode === constants.BLEND_MODES.SRC_OUT
            || blendMode === constants.BLEND_MODES.DST_IN
            || blendMode === constants.BLEND_MODES.DST_ATOP;

        if (!readyForOuterBlend && outerBlend)
        {
            blendMode = constants.BLEND_MODES.NORMAL;
        }

        if (this._activeBlendMode === blendMode)
        {
            return;
        }

        this._activeBlendMode = blendMode;
        this._outerBlend = outerBlend;
        this.context.globalCompositeOperation = this.blendModes[blendMode];
    };

    /**
     * Removes everything from the renderer and optionally removes the Canvas DOM element.
     *
     * @param {boolean} [removeView=false] - Removes the Canvas element from the DOM.
     */
    CanvasRenderer.prototype.destroy = function destroy (removeView)
    {
        // call the base destroy
        AbstractRenderer.prototype.destroy.call(this, removeView);

        this.context = null;

        this.refresh = true;

        this.maskManager.destroy();
        this.maskManager = null;

        this.smoothProperty = null;
    };

    /**
     * Resizes the canvas view to the specified width and height.
     *
     * @extends PIXI.AbstractRenderer#resize
     *
     * @param {number} screenWidth - the new width of the screen
     * @param {number} screenHeight - the new height of the screen
     */
    CanvasRenderer.prototype.resize = function resize (screenWidth, screenHeight)
    {
        AbstractRenderer.prototype.resize.call(this, screenWidth, screenHeight);

        // reset the scale mode.. oddly this seems to be reset when the canvas is resized.
        // surely a browser bug?? Let PixiJS fix that for you..
        if (this.smoothProperty)
        {
            this.rootContext[this.smoothProperty] = (settings.settings.SCALE_MODE === constants.SCALE_MODES.LINEAR);
        }
    };

    /**
     * Checks if blend mode has changed.
     */
    CanvasRenderer.prototype.invalidateBlendMode = function invalidateBlendMode ()
    {
        this._activeBlendMode = this.blendModes.indexOf(this.context.globalCompositeOperation);
    };

    /**
     * Collection of installed plugins. These are included by default in PIXI, but can be excluded
     * by creating a custom build. Consult the README for more information about creating custom
     * builds and excluding plugins.
     * @name PIXI.CanvasRenderer#plugins
     * @type {object}
     * @readonly
     * @property {PIXI.accessibility.AccessibilityManager} accessibility Support tabbing interactive elements.
     * @property {PIXI.extract.CanvasExtract} extract Extract image data from renderer.
     * @property {PIXI.interaction.InteractionManager} interaction Handles mouse, touch and pointer events.
     * @property {PIXI.prepare.CanvasPrepare} prepare Pre-render display objects.
     */

    /**
     * Adds a plugin to the renderer.
     *
     * @method
     * @param {string} pluginName - The name of the plugin.
     * @param {Function} ctor - The constructor function or class for the plugin.
     */
    CanvasRenderer.registerPlugin = function registerPlugin (pluginName, ctor)
    {
        CanvasRenderer.__plugins = CanvasRenderer.__plugins || {};
        CanvasRenderer.__plugins[pluginName] = ctor;
    };

    return CanvasRenderer;
}(core.AbstractRenderer));

/**
 * Utility methods for Sprite/Texture tinting.
 *
 * Tinting with the CanvasRenderer involves creating a new canvas to use as a texture,
 * so be aware of the performance implications.
 *
 * @class
 * @memberof PIXI
 */
var CanvasTinter = {
    /**
     * Basically this method just needs a sprite and a color and tints the sprite with the given color.
     *
     * @memberof PIXI.CanvasTinter
     * @param {PIXI.Sprite} sprite - the sprite to tint
     * @param {number} color - the color to use to tint the sprite with
     * @return {HTMLCanvasElement} The tinted canvas
     */
    getTintedCanvas: function (sprite, color) {
        var texture = sprite._texture;

        color = CanvasTinter.roundColor(color);

        var stringColor = "#" + ((("00000" + ((color | 0).toString(16)))).substr(-6));

        texture.tintCache = texture.tintCache || {};

        var cachedCanvas = texture.tintCache[stringColor];

        var canvas;

        if (cachedCanvas)
        {
            if (cachedCanvas.tintId === texture._updateID)
            {
                return texture.tintCache[stringColor];
            }

            canvas = texture.tintCache[stringColor];
        }
        else
        {
            canvas = CanvasTinter.canvas || document.createElement('canvas');
        }

        CanvasTinter.tintMethod(texture, color, canvas);

        canvas.tintId = texture._updateID;

        if (CanvasTinter.convertTintToImage)
        {
            // is this better?
            var tintImage = new Image();

            tintImage.src = canvas.toDataURL();

            texture.tintCache[stringColor] = tintImage;
        }
        else
        {
            texture.tintCache[stringColor] = canvas;
            // if we are not converting the texture to an image then we need to lose the reference to the canvas
            CanvasTinter.canvas = null;
        }

        return canvas;
    },

    /**
     * Tint a texture using the 'multiply' operation.
     *
     * @memberof PIXI.CanvasTinter
     * @param {PIXI.Texture} texture - the texture to tint
     * @param {number} color - the color to use to tint the sprite with
     * @param {HTMLCanvasElement} canvas - the current canvas
     */
    tintWithMultiply: function (texture, color, canvas) {
        var context = canvas.getContext('2d');
        var crop = texture._frame.clone();
        var resolution = texture.baseTexture.resolution;

        crop.x *= resolution;
        crop.y *= resolution;
        crop.width *= resolution;
        crop.height *= resolution;

        canvas.width = Math.ceil(crop.width);
        canvas.height = Math.ceil(crop.height);

        context.save();
        context.fillStyle = "#" + ((("00000" + ((color | 0).toString(16)))).substr(-6));

        context.fillRect(0, 0, crop.width, crop.height);

        context.globalCompositeOperation = 'multiply';

        var source = texture.baseTexture.getDrawableSource();

        context.drawImage(
            source,
            crop.x,
            crop.y,
            crop.width,
            crop.height,
            0,
            0,
            crop.width,
            crop.height
        );

        context.globalCompositeOperation = 'destination-atop';

        context.drawImage(
            source,
            crop.x,
            crop.y,
            crop.width,
            crop.height,
            0,
            0,
            crop.width,
            crop.height
        );
        context.restore();
    },

    /**
     * Tint a texture using the 'overlay' operation.
     *
     * @memberof PIXI.CanvasTinter
     * @param {PIXI.Texture} texture - the texture to tint
     * @param {number} color - the color to use to tint the sprite with
     * @param {HTMLCanvasElement} canvas - the current canvas
     */
    tintWithOverlay: function tintWithOverlay(texture, color, canvas)
    {
        var context = canvas.getContext('2d');
        var crop = texture._frame.clone();
        var resolution = texture.baseTexture.resolution;

        crop.x *= resolution;
        crop.y *= resolution;
        crop.width *= resolution;
        crop.height *= resolution;

        canvas.width = Math.ceil(crop.width);
        canvas.height = Math.ceil(crop.height);

        context.save();
        context.globalCompositeOperation = 'copy';
        context.fillStyle = "#" + ((("00000" + ((color | 0).toString(16)))).substr(-6));
        context.fillRect(0, 0, crop.width, crop.height);

        context.globalCompositeOperation = 'destination-atop';
        context.drawImage(
            texture.baseTexture.getDrawableSource(),
            crop.x,
            crop.y,
            crop.width,
            crop.height,
            0,
            0,
            crop.width,
            crop.height
        );

        // context.globalCompositeOperation = 'copy';
        context.restore();
    },

    /**
     * Tint a texture pixel per pixel.
     *
     * @memberof PIXI.CanvasTinter
     * @param {PIXI.Texture} texture - the texture to tint
     * @param {number} color - the color to use to tint the sprite with
     * @param {HTMLCanvasElement} canvas - the current canvas
     */
    tintWithPerPixel: function (texture, color, canvas) {
        var context = canvas.getContext('2d');
        var crop = texture._frame.clone();
        var resolution = texture.baseTexture.resolution;

        crop.x *= resolution;
        crop.y *= resolution;
        crop.width *= resolution;
        crop.height *= resolution;

        canvas.width = Math.ceil(crop.width);
        canvas.height = Math.ceil(crop.height);

        context.save();
        context.globalCompositeOperation = 'copy';
        context.drawImage(
            texture.baseTexture.getDrawableSource(),
            crop.x,
            crop.y,
            crop.width,
            crop.height,
            0,
            0,
            crop.width,
            crop.height
        );
        context.restore();

        var rgbValues = utils.hex2rgb(color);
        var r = rgbValues[0];
        var g = rgbValues[1];
        var b = rgbValues[2];

        var pixelData = context.getImageData(0, 0, crop.width, crop.height);

        var pixels = pixelData.data;

        for (var i = 0; i < pixels.length; i += 4)
        {
            pixels[i + 0] *= r;
            pixels[i + 1] *= g;
            pixels[i + 2] *= b;
        }

        context.putImageData(pixelData, 0, 0);
    },

    /**
     * Rounds the specified color according to the CanvasTinter.cacheStepsPerColorChannel.
     *
     * @memberof PIXI.CanvasTinter
     * @param {number} color - the color to round, should be a hex color
     * @return {number} The rounded color.
     */
    roundColor: function (color) {
        var step = CanvasTinter.cacheStepsPerColorChannel;

        var rgbValues = utils.hex2rgb(color);

        rgbValues[0] = Math.min(255, (rgbValues[0] / step) * step);
        rgbValues[1] = Math.min(255, (rgbValues[1] / step) * step);
        rgbValues[2] = Math.min(255, (rgbValues[2] / step) * step);

        return utils.rgb2hex(rgbValues);
    },

    /**
     * Number of steps which will be used as a cap when rounding colors.
     *
     * @memberof PIXI.CanvasTinter
     * @type {number}
     */
    cacheStepsPerColorChannel: 8,

    /**
     * Tint cache boolean flag.
     *
     * @memberof PIXI.CanvasTinter
     * @type {boolean}
     */
    convertTintToImage: false,

    /**
     * Whether or not the Canvas BlendModes are supported, consequently the ability to tint using the multiply method.
     *
     * @memberof PIXI.CanvasTinter
     * @type {boolean}
     */
    canUseMultiply: canUseNewCanvasBlendModes(),

    /**
     * The tinting method that will be used.
     *
     * @memberof PIXI.CanvasTinter
     * @type {Function}
     */
    tintMethod: function () { // jslint-disable no-empty-function

    },
};

CanvasTinter.tintMethod = CanvasTinter.canUseMultiply ? CanvasTinter.tintWithMultiply : CanvasTinter.tintWithPerPixel;

// Reference to Renderer.create static function
var parentCreate = core.Renderer.create;

/**
 * Override the Renderer.create to fallback to use CanvasRenderer.
 * Also supports forceCanvas option with Application or autoDetectRenderer.
 * @private
 */
core.Renderer.create = function create(options)
{
    var forceCanvas = options && options.forceCanvas;

    if (!forceCanvas)
    {
        try
        {
            return parentCreate(options);
        }
        catch (err)
        {
            // swallow WebGL-unsupported error
        }
    }

    return new CanvasRenderer(options);
};

/**
 * Get the drawable source, such as HTMLCanvasElement or HTMLImageElement suitable
 * for rendering with CanvasRenderer. Provided by **@pixi/canvas-renderer** package.
 * @method getDrawableSource
 * @memberof PIXI.BaseTexture#
 * @return {PIXI.ICanvasImageSource} Source to render with CanvasRenderer
 */
core.BaseTexture.prototype.getDrawableSource = function getDrawableSource()
{
    var resource = this.resource;

    return resource ? (resource.bitmap || resource.source) : this.source;
};

exports.CanvasRenderer = CanvasRenderer;
exports.CanvasTinter = CanvasTinter;
exports.canUseNewCanvasBlendModes = canUseNewCanvasBlendModes;
//# sourceMappingURL=canvas-renderer.js.map