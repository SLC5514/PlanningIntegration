/*!
 * @pixi/canvas-display - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/canvas-display is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
this.PIXI = this.PIXI || {};
(function (display) {
    'use strict';

    /**
     * To be overridden by the subclass
     * @method _renderCanvas
     * @memberof PIXI.Container#
     * @protected
     * @param {PIXI.CanvasRenderer} renderer - The renderer
     */
    display.Container.prototype._renderCanvas = function _renderCanvas(renderer) // eslint-disable-line no-unused-vars
    {
        // this is where content itself gets rendered...
    };

    /**
     * Renders the object using the Canvas renderer
     * @method renderCanvas
     * @memberof PIXI.Container#
     * @param {PIXI.CanvasRenderer} renderer - The renderer
     */
    display.Container.prototype.renderCanvas = function renderCanvas(renderer)
    {
        // if not visible or the alpha is 0 then no need to render this
        if (!this.visible || this.worldAlpha <= 0 || !this.renderable)
        {
            return;
        }

        if (this._mask)
        {
            renderer.maskManager.pushMask(this._mask);
        }

        this._renderCanvas(renderer);
        for (var i = 0, j = this.children.length; i < j; ++i)
        {
            this.children[i].renderCanvas(renderer);
        }

        if (this._mask)
        {
            renderer.maskManager.popMask(renderer);
        }
    };

    /**
     * Renders the object using the Canvas renderer
     * @method renderCanvas
     * @memberof PIXI.Container#
     * @param {PIXI.CanvasRenderer} renderer - The renderer
     */
    display.DisplayObject.prototype.renderCanvas = function renderCanvas(renderer) // eslint-disable-line no-unused-vars
    {
        // OVERWRITE;
    };

}(PIXI));
Object.assign(this.PIXI, _pixi_canvas_display);
//# sourceMappingURL=canvas-display.js.map
