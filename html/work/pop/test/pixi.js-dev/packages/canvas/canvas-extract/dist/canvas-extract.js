/*!
 * @pixi/canvas-extract - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/canvas-extract is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
this.PIXI = this.PIXI || {};
this.PIXI.extract = this.PIXI.extract || {};
var _pixi_canvas_extract = (function (exports, core, utils, math) {
    'use strict';

    var TEMP_RECT = new math.Rectangle();

    /**
     * The extract manager provides functionality to export content from the renderers.
     *
     * An instance of this class is automatically created by default, and can be found at `renderer.plugins.extract`
     *
     * @class
     * @memberof PIXI.extract
     */
    var CanvasExtract = function CanvasExtract(renderer)
    {
        this.renderer = renderer;
        /**
         * Collection of methods for extracting data (image, pixels, etc.) from a display object or render texture
         *
         * @member {PIXI.extract.CanvasExtract} extract
         * @memberof PIXI.CanvasRenderer#
         * @see PIXI.extract.CanvasExtract
         */
        renderer.extract = this;
    };

    /**
     * Will return a HTML Image of the target
     *
     * @param {PIXI.DisplayObject|PIXI.RenderTexture} target - A displayObject or renderTexture
     *  to convert. If left empty will use the main renderer
     * @return {HTMLImageElement} HTML Image of the target
     */
    CanvasExtract.prototype.image = function image (target)
    {
        var image = new Image();

        image.src = this.base64(target);

        return image;
    };

    /**
     * Will return a a base64 encoded string of this target. It works by calling
     *  `CanvasExtract.getCanvas` and then running toDataURL on that.
     *
     * @param {PIXI.DisplayObject|PIXI.RenderTexture} target - A displayObject or renderTexture
     *  to convert. If left empty will use the main renderer
     * @return {string} A base64 encoded string of the texture.
     */
    CanvasExtract.prototype.base64 = function base64 (target)
    {
        return this.canvas(target).toDataURL();
    };

    /**
     * Creates a Canvas element, renders this target to it and then returns it.
     *
     * @param {PIXI.DisplayObject|PIXI.RenderTexture} target - A displayObject or renderTexture
     *  to convert. If left empty will use the main renderer
     * @return {HTMLCanvasElement} A Canvas element with the texture rendered on.
     */
    CanvasExtract.prototype.canvas = function canvas (target)
    {
        var renderer = this.renderer;
        var context;
        var resolution;
        var frame;
        var renderTexture;

        if (target)
        {
            if (target instanceof core.RenderTexture)
            {
                renderTexture = target;
            }
            else
            {
                renderTexture = renderer.generateTexture(target);
            }
        }

        if (renderTexture)
        {
            context = renderTexture.baseTexture._canvasRenderTarget.context;
            resolution = renderTexture.baseTexture._canvasRenderTarget.resolution;
            frame = renderTexture.frame;
        }
        else
        {
            context = renderer.rootContext;
            resolution = renderer.resolution;
            frame = TEMP_RECT;
            frame.width = this.renderer.width;
            frame.height = this.renderer.height;
        }

        var width = frame.width * resolution;
        var height = frame.height * resolution;

        var canvasBuffer = new utils.CanvasRenderTarget(width, height, 1);
        var canvasData = context.getImageData(frame.x * resolution, frame.y * resolution, width, height);

        canvasBuffer.context.putImageData(canvasData, 0, 0);

        // send the canvas back..
        return canvasBuffer.canvas;
    };

    /**
     * Will return a one-dimensional array containing the pixel data of the entire texture in RGBA
     * order, with integer values between 0 and 255 (included).
     *
     * @param {PIXI.DisplayObject|PIXI.RenderTexture} target - A displayObject or renderTexture
     *  to convert. If left empty will use the main renderer
     * @return {Uint8ClampedArray} One-dimensional array containing the pixel data of the entire texture
     */
    CanvasExtract.prototype.pixels = function pixels (target)
    {
        var renderer = this.renderer;
        var context;
        var resolution;
        var frame;
        var renderTexture;

        if (target)
        {
            if (target instanceof core.RenderTexture)
            {
                renderTexture = target;
            }
            else
            {
                renderTexture = renderer.generateTexture(target);
            }
        }

        if (renderTexture)
        {
            context = renderTexture.baseTexture._canvasRenderTarget.context;
            resolution = renderTexture.baseTexture._canvasRenderTarget.resolution;
            frame = renderTexture.frame;
        }
        else
        {
            context = renderer.rootContext;

            frame = TEMP_RECT;
            frame.width = renderer.width;
            frame.height = renderer.height;
        }

        return context.getImageData(0, 0, frame.width * resolution, frame.height * resolution).data;
    };

    /**
     * Destroys the extract
     *
     */
    CanvasExtract.prototype.destroy = function destroy ()
    {
        this.renderer.extract = null;
        this.renderer = null;
    };

    exports.CanvasExtract = CanvasExtract;

    return exports;

}({}, PIXI, PIXI.utils, PIXI));
Object.assign(this.PIXI.extract, _pixi_canvas_extract);
//# sourceMappingURL=canvas-extract.js.map