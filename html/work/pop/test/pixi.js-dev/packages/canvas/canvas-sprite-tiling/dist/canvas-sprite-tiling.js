/*!
 * @pixi/canvas-sprite-tiling - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/canvas-sprite-tiling is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
this.PIXI = this.PIXI || {};
(function (spriteTiling, canvasRenderer, utils) {
    'use strict';

    /**
     * Renders the object using the Canvas renderer
     *
     * @protected
     * @function _renderCanvas
     * @memberof PIXI.TilingSprite#
     * @param {PIXI.CanvasRenderer} renderer - a reference to the canvas renderer
     */
    spriteTiling.TilingSprite.prototype._renderCanvas = function _renderCanvas(renderer)
    {
        var texture = this._texture;

        if (!texture.baseTexture.valid)
        {
            return;
        }

        var context = renderer.context;
        var transform = this.worldTransform;
        var resolution = renderer.resolution;
        var baseTexture = texture.baseTexture;
        var source = baseTexture.getDrawableSource();
        var baseTextureResolution = baseTexture.resolution;
        var modX = ((this.tilePosition.x / this.tileScale.x) % texture._frame.width) * baseTextureResolution;
        var modY = ((this.tilePosition.y / this.tileScale.y) % texture._frame.height) * baseTextureResolution;

        // create a nice shiny pattern!
        if (this._textureID !== this._texture._updateID || this.cachedTint !== this.tint)
        {
            this._textureID = this._texture._updateID;
            // cut an object from a spritesheet..
            var tempCanvas = new utils.CanvasRenderTarget(texture._frame.width,
                texture._frame.height,
                baseTextureResolution);

            // Tint the tiling sprite
            if (this.tint !== 0xFFFFFF)
            {
                this._tintedCanvas = canvasRenderer.CanvasTinter.getTintedCanvas(this, this.tint);
                tempCanvas.context.drawImage(this._tintedCanvas, 0, 0);
            }
            else
            {
                tempCanvas.context.drawImage(source,
                    -texture._frame.x * baseTextureResolution, -texture._frame.y * baseTextureResolution);
            }
            this.cachedTint = this.tint;
            this._canvasPattern = tempCanvas.context.createPattern(tempCanvas.canvas, 'repeat');
        }

        // set context state..
        context.globalAlpha = this.worldAlpha;
        context.setTransform(transform.a * resolution,
            transform.b * resolution,
            transform.c * resolution,
            transform.d * resolution,
            transform.tx * resolution,
            transform.ty * resolution);

        renderer.setBlendMode(this.blendMode);

        // fill the pattern!
        context.fillStyle = this._canvasPattern;

        // TODO - this should be rolled into the setTransform above..
        context.scale(this.tileScale.x / baseTextureResolution, this.tileScale.y / baseTextureResolution);

        var anchorX = this.anchor.x * -this._width;
        var anchorY = this.anchor.y * -this._height;

        if (this.uvRespectAnchor)
        {
            context.translate(modX, modY);

            context.fillRect(-modX + anchorX, -modY + anchorY,
                this._width / this.tileScale.x * baseTextureResolution,
                this._height / this.tileScale.y * baseTextureResolution);
        }
        else
        {
            context.translate(modX + anchorX, modY + anchorY);

            context.fillRect(-modX, -modY,
                this._width / this.tileScale.x * baseTextureResolution,
                this._height / this.tileScale.y * baseTextureResolution);
        }
    };

}(PIXI, PIXI, PIXI.utils));
Object.assign(this.PIXI, _pixi_canvas_sprite_tiling);
//# sourceMappingURL=canvas-sprite-tiling.js.map
