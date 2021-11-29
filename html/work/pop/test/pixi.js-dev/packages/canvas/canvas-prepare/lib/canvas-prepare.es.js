/*!
 * @pixi/canvas-prepare - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/canvas-prepare is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
import { BaseTexture } from '@pixi/core';
import { BasePrepare } from '@pixi/prepare';

var CANVAS_START_SIZE = 16;

/**
 * The prepare manager provides functionality to upload content to the GPU.
 *
 * This cannot be done directly for Canvas like in WebGL, but the effect can be achieved by drawing
 * textures to an offline canvas. This draw call will force the texture to be moved onto the GPU.
 *
 * An instance of this class is automatically created by default, and can be found at `renderer.plugins.prepare`
 *
 * @class
 * @extends PIXI.prepare.BasePrepare
 * @memberof PIXI.prepare
 */
var CanvasPrepare = /*@__PURE__*/(function (BasePrepare) {
    function CanvasPrepare(renderer)
    {
        BasePrepare.call(this, renderer);

        this.uploadHookHelper = this;

        /**
        * An offline canvas to render textures to
        * @type {HTMLCanvasElement}
        * @private
        */
        this.canvas = document.createElement('canvas');
        this.canvas.width = CANVAS_START_SIZE;
        this.canvas.height = CANVAS_START_SIZE;

        /**
         * The context to the canvas
        * @type {CanvasRenderingContext2D}
        * @private
        */
        this.ctx = this.canvas.getContext('2d');

        // Add textures to upload
        this.registerUploadHook(uploadBaseTextures);
    }

    if ( BasePrepare ) CanvasPrepare.__proto__ = BasePrepare;
    CanvasPrepare.prototype = Object.create( BasePrepare && BasePrepare.prototype );
    CanvasPrepare.prototype.constructor = CanvasPrepare;

    /**
     * Destroys the plugin, don't use after this.
     *
     */
    CanvasPrepare.prototype.destroy = function destroy ()
    {
        BasePrepare.prototype.destroy.call(this);
        this.ctx = null;
        this.canvas = null;
    };

    return CanvasPrepare;
}(BasePrepare));

/**
 * Built-in hook to upload PIXI.Texture objects to the GPU.
 *
 * @private
 * @param {*} prepare - Instance of CanvasPrepare
 * @param {*} item - Item to check
 * @return {boolean} If item was uploaded.
 */
function uploadBaseTextures(prepare, item)
{
    if (item instanceof BaseTexture)
    {
        var image = item.source;

        // Sometimes images (like atlas images) report a size of zero, causing errors on windows phone.
        // So if the width or height is equal to zero then use the canvas size
        // Otherwise use whatever is smaller, the image dimensions or the canvas dimensions.
        var imageWidth = image.width === 0 ? prepare.canvas.width : Math.min(prepare.canvas.width, image.width);
        var imageHeight = image.height === 0 ? prepare.canvas.height : Math.min(prepare.canvas.height, image.height);

        // Only a small subsections is required to be drawn to have the whole texture uploaded to the GPU
        // A smaller draw can be faster.
        prepare.ctx.drawImage(image, 0, 0, imageWidth, imageHeight, 0, 0, prepare.canvas.width, prepare.canvas.height);

        return true;
    }

    return false;
}

export { CanvasPrepare };
//# sourceMappingURL=canvas-prepare.es.js.map