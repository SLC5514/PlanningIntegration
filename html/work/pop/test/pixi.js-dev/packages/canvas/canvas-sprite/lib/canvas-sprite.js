/*!
 * @pixi/canvas-sprite - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/canvas-sprite is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
'use strict';

Object.defineProperty(exports, '__esModule', { value: true });

var constants = require('@pixi/constants');
var math = require('@pixi/math');
var canvasRenderer = require('@pixi/canvas-renderer');
var sprite = require('@pixi/sprite');

var canvasRenderWorldTransform = new math.Matrix();

/**
 * Types that can be passed to drawImage
 * @typedef {HTMLImageElement | HTMLCanvasElement | HTMLVideoElement | ImageBitmap} ICanvasImageSource
 * @memberof PIXI
 */

/**
 * @author Mat Groves
 *
 * Big thanks to the very clever Matt DesLauriers <mattdesl> https://github.com/mattdesl/
 * for creating the original PixiJS version!
 * Also a thanks to https://github.com/bchevalier for tweaking the tint and alpha so that they now
 * share 4 bytes on the vertex buffer
 *
 * Heavily inspired by LibGDX's CanvasSpriteRenderer:
 * https://github.com/libgdx/libgdx/blob/master/gdx/src/com/badlogic/gdx/graphics/g2d/CanvasSpriteRenderer.java
 */

/**
 * Renderer dedicated to drawing and batching sprites.
 *
 * @class
 * @protected
 * @memberof PIXI
 */
var CanvasSpriteRenderer = function CanvasSpriteRenderer(renderer)
{
    this.renderer = renderer;
};

/**
 * Renders the sprite object.
 *
 * @param {PIXI.Sprite} sprite - the sprite to render when using this spritebatch
 */
CanvasSpriteRenderer.prototype.render = function render (sprite)
{
    var texture = sprite._texture;
    var renderer = this.renderer;
    var context = renderer.context;

    var width = texture._frame.width;
    var height = texture._frame.height;

    var wt = sprite.transform.worldTransform;
    var dx = 0;
    var dy = 0;

    var source = texture.baseTexture.getDrawableSource();

    if (texture.orig.width <= 0 || texture.orig.height <= 0 || !source)
    {
        return;
    }

    if (!texture.valid)
    {
        return;
    }

    renderer.setBlendMode(sprite.blendMode, true);

    renderer.context.globalAlpha = sprite.worldAlpha;

    // If smoothingEnabled is supported and we need to change the smoothing property for sprite texture
    var smoothingEnabled = texture.baseTexture.scaleMode === constants.SCALE_MODES.LINEAR;

    if (renderer.smoothProperty && renderer.context[renderer.smoothProperty] !== smoothingEnabled)
    {
        context[renderer.smoothProperty] = smoothingEnabled;
    }

    if (texture.trim)
    {
        dx = (texture.trim.width / 2) + texture.trim.x - (sprite.anchor.x * texture.orig.width);
        dy = (texture.trim.height / 2) + texture.trim.y - (sprite.anchor.y * texture.orig.height);
    }
    else
    {
        dx = (0.5 - sprite.anchor.x) * texture.orig.width;
        dy = (0.5 - sprite.anchor.y) * texture.orig.height;
    }

    if (texture.rotate)
    {
        wt.copyTo(canvasRenderWorldTransform);
        wt = canvasRenderWorldTransform;
        math.GroupD8.matrixAppendRotationInv(wt, texture.rotate, dx, dy);
        // the anchor has already been applied above, so lets set it to zero
        dx = 0;
        dy = 0;
    }

    dx -= width / 2;
    dy -= height / 2;

    // Allow for pixel rounding
    if (sprite.roundPixels)
    {
        renderer.context.setTransform(
            wt.a,
            wt.b,
            wt.c,
            wt.d,
            (wt.tx * renderer.resolution) | 0,
            (wt.ty * renderer.resolution) | 0
        );

        dx = dx | 0;
        dy = dy | 0;
    }
    else
    {
        renderer.context.setTransform(
            wt.a,
            wt.b,
            wt.c,
            wt.d,
            wt.tx * renderer.resolution,
            wt.ty * renderer.resolution
        );
    }

    var resolution = texture.baseTexture.resolution;
    var outerBlend = renderer._outerBlend;

    if (outerBlend)
    {
        context.save();
        context.beginPath();
        context.rect(
            dx * renderer.resolution,
            dy * renderer.resolution,
            width * renderer.resolution,
            height * renderer.resolution
        );
        context.clip();
    }

    if (sprite.tint !== 0xFFFFFF)
    {
        if (sprite._cachedTint !== sprite.tint || sprite._tintedCanvas.tintId !== sprite._texture._updateID)
        {
            sprite._cachedTint = sprite.tint;

            // TODO clean up caching - how to clean up the caches?
            sprite._tintedCanvas = canvasRenderer.CanvasTinter.getTintedCanvas(sprite, sprite.tint);
        }

        context.drawImage(
            source,
            0,
            0,
            width * resolution,
            height * resolution,
            dx * renderer.resolution,
            dy * renderer.resolution,
            width * renderer.resolution,
            height * renderer.resolution
        );
    }
    else
    {
        context.drawImage(
            source,
            texture._frame.x * resolution,
            texture._frame.y * resolution,
            width * resolution,
            height * resolution,
            dx * renderer.resolution,
            dy * renderer.resolution,
            width * renderer.resolution,
            height * renderer.resolution
        );
    }

    if (outerBlend)
    {
        context.restore();
    }
    // just in case, leaking outer blend here will be catastrophic!
    renderer.setBlendMode(constants.BLEND_MODES.NORMAL);
};

/**
 * destroy the sprite object.
 *
 */
CanvasSpriteRenderer.prototype.destroy = function destroy ()
{
    this.renderer = null;
};

/**
 * Cached tinted texture.
 * @memberof PIXI.Sprite#
 * @member {HTMLCanvasElement} _tintedCanvas
 * @protected
 */
sprite.Sprite.prototype._tintedCanvas = null;

/**
 * Cached tint value so we can tell when the tint is changed.
 * @memberof PIXI.Sprite#
 * @member {number} _cachedTint
 * @protected
 */
sprite.Sprite.prototype._cachedTint = 0xFFFFFF;

/**
* Renders the object using the Canvas renderer
*
* @private
* @method _renderCanvas
* @memberof PIXI.Sprite#
* @param {PIXI.CanvasRenderer} renderer - The renderer
*/
sprite.Sprite.prototype._renderCanvas = function _renderCanvas(renderer)
{
    renderer.plugins.sprite.render(this);
};

exports.CanvasSpriteRenderer = CanvasSpriteRenderer;
//# sourceMappingURL=canvas-sprite.js.map