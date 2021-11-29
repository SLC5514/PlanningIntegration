/*!
 * @pixi/extract - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/extract is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
'use strict';

Object.defineProperty(exports, '__esModule', { value: true });

var core = require('@pixi/core');
var utils = require('@pixi/utils');
var math = require('@pixi/math');

var TEMP_RECT = new math.Rectangle();
var BYTES_PER_PIXEL = 4;

/**
 * The extract manager provides functionality to export content from the renderers.
 *
 * An instance of this class is automatically created by default, and can be found at `renderer.plugins.extract`
 *
 * @class
 * @memberof PIXI.extract
 */
var Extract = function Extract(renderer)
{
    this.renderer = renderer;
    /**
     * Collection of methods for extracting data (image, pixels, etc.) from a display object or render texture
     *
     * @member {PIXI.extract.Extract} extract
     * @memberof PIXI.Renderer#
     * @see PIXI.extract.Extract
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
Extract.prototype.image = function image (target)
{
    var image = new Image();

    image.src = this.base64(target);

    return image;
};

/**
 * Will return a a base64 encoded string of this target. It works by calling
 *  `Extract.getCanvas` and then running toDataURL on that.
 *
 * @param {PIXI.DisplayObject|PIXI.RenderTexture} target - A displayObject or renderTexture
 *  to convert. If left empty will use the main renderer
 * @return {string} A base64 encoded string of the texture.
 */
Extract.prototype.base64 = function base64 (target)
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
Extract.prototype.canvas = function canvas (target)
{
    var renderer = this.renderer;
    var resolution;
    var frame;
    var flipY = false;
    var renderTexture;
    var generated = false;

    if (target)
    {
        if (target instanceof core.RenderTexture)
        {
            renderTexture = target;
        }
        else
        {
            renderTexture = this.renderer.generateTexture(target);
            generated = true;
        }
    }

    if (renderTexture)
    {
        resolution = renderTexture.baseTexture.resolution;
        frame = renderTexture.frame;
        flipY = false;
        renderer.renderTexture.bind(renderTexture);
    }
    else
    {
        resolution = this.renderer.resolution;

        flipY = true;

        frame = TEMP_RECT;
        frame.width = this.renderer.width;
        frame.height = this.renderer.height;

        renderer.renderTexture.bind(null);
    }

    var width = frame.width * resolution;
    var height = frame.height * resolution;

    var canvasBuffer = new utils.CanvasRenderTarget(width, height, 1);

    var webglPixels = new Uint8Array(BYTES_PER_PIXEL * width * height);

    // read pixels to the array
    var gl = renderer.gl;

    gl.readPixels(
        frame.x * resolution,
        frame.y * resolution,
        width,
        height,
        gl.RGBA,
        gl.UNSIGNED_BYTE,
        webglPixels
    );

    // add the pixels to the canvas
    var canvasData = canvasBuffer.context.getImageData(0, 0, width, height);

    canvasData.data.set(webglPixels);

    canvasBuffer.context.putImageData(canvasData, 0, 0);

    // pulling pixels
    if (flipY)
    {
        canvasBuffer.context.scale(1, -1);
        canvasBuffer.context.drawImage(canvasBuffer.canvas, 0, -height);
    }

    if (generated)
    {
        renderTexture.destroy(true);
    }

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
Extract.prototype.pixels = function pixels (target)
{
    var renderer = this.renderer;
    var resolution;
    var frame;
    var renderTexture;
    var generated = false;

    if (target)
    {
        if (target instanceof core.RenderTexture)
        {
            renderTexture = target;
        }
        else
        {
            renderTexture = this.renderer.generateTexture(target);
            generated = true;
        }
    }

    if (renderTexture)
    {
        resolution = renderTexture.baseTexture.resolution;
        frame = renderTexture.frame;

        // bind the buffer
        renderer.renderTexture.bind(renderTexture);
    }
    else
    {
        resolution = renderer.resolution;

        frame = TEMP_RECT;
        frame.width = renderer.width;
        frame.height = renderer.height;

        renderer.renderTexture.bind(null);
    }

    var width = frame.width * resolution;
    var height = frame.height * resolution;

    var webglPixels = new Uint8Array(BYTES_PER_PIXEL * width * height);

    // read pixels to the array
    var gl = renderer.gl;

    gl.readPixels(
        frame.x * resolution,
        frame.y * resolution,
        width,
        height,
        gl.RGBA,
        gl.UNSIGNED_BYTE,
        webglPixels
    );

    if (generated)
    {
        renderTexture.destroy(true);
    }

    return webglPixels;
};

/**
 * Destroys the extract
 *
 */
Extract.prototype.destroy = function destroy ()
{
    this.renderer.extract = null;
    this.renderer = null;
};

/**
 * This namespace provides renderer-specific plugins for exporting content from a renderer.
 * For instance, these plugins can be used for saving an Image, Canvas element or for exporting the raw image data (pixels).
 *
 * Do not instantiate these plugins directly. It is available from the `renderer.plugins` property.
 * See {@link PIXI.CanvasRenderer#plugins} or {@link PIXI.Renderer#plugins}.
 * @example
 * // Create a new app (will auto-add extract plugin to renderer)
 * const app = new PIXI.Application();
 *
 * // Draw a red circle
 * const graphics = new PIXI.Graphics()
 *     .beginFill(0xFF0000)
 *     .drawCircle(0, 0, 50);
 *
 * // Render the graphics as an HTMLImageElement
 * const image = app.renderer.plugins.extract.image(graphics);
 * document.body.appendChild(image);
 * @namespace PIXI.extract
 */

exports.Extract = Extract;
//# sourceMappingURL=extract.js.map