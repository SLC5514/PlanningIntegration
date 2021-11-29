/*!
 * @pixi/canvas-graphics - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/canvas-graphics is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
'use strict';

Object.defineProperty(exports, '__esModule', { value: true });

var math = require('@pixi/math');
var graphics = require('@pixi/graphics');
var canvasRenderer$1 = require('@pixi/canvas-renderer');
var core = require('@pixi/core');

/**
 * @author Mat Groves
 *
 * Big thanks to the very clever Matt DesLauriers <mattdesl> https://github.com/mattdesl/
 * for creating the original PixiJS version!
 * Also a thanks to https://github.com/bchevalier for tweaking the tint and alpha so that they
 * now share 4 bytes on the vertex buffer
 *
 * Heavily inspired by LibGDX's CanvasGraphicsRenderer:
 * https://github.com/libgdx/libgdx/blob/1.0.0/gdx/src/com/badlogic/gdx/graphics/glutils/ShapeRenderer.java
 */

/**
 * Renderer dedicated to drawing and batching graphics objects.
 *
 * @class
 * @protected
 * @memberof PIXI
 */
var CanvasGraphicsRenderer = function CanvasGraphicsRenderer(renderer)
{
    this.renderer = renderer;
};

/**
 * Renders a Graphics object to a canvas.
 *
 * @param {PIXI.Graphics} graphics - the actual graphics object to render
 */
CanvasGraphicsRenderer.prototype.render = function render (graphics)
{
    var renderer = this.renderer;
    var context = renderer.context;
    var worldAlpha = graphics.worldAlpha;
    var transform = graphics.transform.worldTransform;
    var resolution = renderer.resolution;

    context.setTransform(
        transform.a * resolution,
        transform.b * resolution,
        transform.c * resolution,
        transform.d * resolution,
        transform.tx * resolution,
        transform.ty * resolution
    );

    // update tint if graphics was dirty
    if (graphics.canvasTintDirty !== graphics.dirty
        || graphics._prevTint !== graphics.tint)
    ;

    renderer.setBlendMode(graphics.blendMode);

    var graphicsData = graphics.geometry.graphicsData;

    for (var i = 0; i < graphicsData.length; i++)
    {
        var data = graphicsData[i];
        var shape = data.shape;
        var fillStyle = data.fillStyle;
        var lineStyle = data.lineStyle;

        var fillColor = fillStyle.color;// data._fillTint;
        var lineColor = lineStyle.color;// data._lineTint;

        context.lineWidth = lineStyle.width;

        if (data.type === math.SHAPES.POLY)
        {
            context.beginPath();

            var points = shape.points;
            var holes = data.holes;
            var outerArea = (void 0);
            var innerArea = (void 0);

            context.moveTo(points[0], points[1]);

            for (var j = 2; j < points.length; j += 2)
            {
                context.lineTo(points[j], points[j + 1]);
            }

            if (shape.closeStroke)
            {
                context.closePath();
            }

            if (holes.length > 0)
            {
                outerArea = 0;
                for (var j$1 = 0; j$1 < points.length; j$1 += 2)
                {
                    outerArea += (points[j$1] * points[j$1 + 3]) - (points[j$1 + 1] * points[j$1 + 2]);
                }

                for (var k = 0; k < holes.length; k++)
                {
                    points = holes[k].points;

                    innerArea = 0;
                    for (var j$2 = 0; j$2 < points.length; j$2 += 2)
                    {
                        innerArea += (points[j$2] * points[j$2 + 3]) - (points[j$2 + 1] * points[j$2 + 2]);
                    }

                    if (innerArea * outerArea < 0)
                    {
                        context.moveTo(points[0], points[1]);

                        for (var j$3 = 2; j$3 < points.length; j$3 += 2)
                        {
                            context.lineTo(points[j$3], points[j$3 + 1]);
                        }
                    }
                    else
                    {
                        context.moveTo(points[points.length - 2], points[points.length - 1]);

                        for (var j$4 = points.length - 4; j$4 >= 0; j$4 -= 2)
                        {
                            context.lineTo(points[j$4], points[j$4 + 1]);
                        }
                    }

                    if (holes[k].shape.closeStroke)
                    {
                        context.closePath();
                    }
                }
            }

            if (fillStyle.visible)
            {
                context.globalAlpha = fillStyle.alpha * worldAlpha;

                context.fillStyle = "#" + ((("00000" + ((fillColor | 0).toString(16)))).substr(-6));
                context.fill();
            }

            if (lineStyle.visible)
            {
                context.globalAlpha = lineStyle.alpha * worldAlpha;
                context.strokeStyle = "#" + ((("00000" + ((lineColor | 0).toString(16)))).substr(-6));
                context.stroke();
            }
        }
        else if (data.type === math.SHAPES.RECT)
        {
            if (fillStyle.visible)
            {
                context.globalAlpha = fillStyle.alpha * worldAlpha;
                context.fillStyle = "#" + ((("00000" + ((fillColor | 0).toString(16)))).substr(-6));
                context.fillRect(shape.x, shape.y, shape.width, shape.height);
            }
            if (lineStyle.visible)
            {
                context.globalAlpha = lineStyle.alpha * worldAlpha;
                context.strokeStyle = "#" + ((("00000" + ((lineColor | 0).toString(16)))).substr(-6));
                context.strokeRect(shape.x, shape.y, shape.width, shape.height);
            }
        }
        else if (data.type === math.SHAPES.CIRC)
        {
            // TODO - need to be Undefined!
            context.beginPath();
            context.arc(shape.x, shape.y, shape.radius, 0, 2 * Math.PI);
            context.closePath();

            if (fillStyle.visible)
            {
                context.globalAlpha = fillStyle.alpha * worldAlpha;
                context.fillStyle = "#" + ((("00000" + ((fillColor | 0).toString(16)))).substr(-6));
                context.fill();
            }

            if (lineStyle.visible)
            {
                context.globalAlpha = lineStyle.alpha * worldAlpha;
                context.strokeStyle = "#" + ((("00000" + ((lineColor | 0).toString(16)))).substr(-6));
                context.stroke();
            }
        }
        else if (data.type === math.SHAPES.ELIP)
        {
            // ellipse code taken from: http://stackoverflow.com/questions/2172798/how-to-draw-an-oval-in-html5-canvas

            var w = shape.width * 2;
            var h = shape.height * 2;

            var x = shape.x - (w / 2);
            var y = shape.y - (h / 2);

            context.beginPath();

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

            if (fillStyle.visible)
            {
                context.globalAlpha = fillStyle.alpha * worldAlpha;
                context.fillStyle = "#" + ((("00000" + ((fillColor | 0).toString(16)))).substr(-6));
                context.fill();
            }
            if (lineStyle.visible)
            {
                context.globalAlpha = lineStyle.alpha * worldAlpha;
                context.strokeStyle = "#" + ((("00000" + ((lineColor | 0).toString(16)))).substr(-6));
                context.stroke();
            }
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

            context.beginPath();
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

            if (fillStyle.visible)
            {
                context.globalAlpha = fillStyle.alpha * worldAlpha;
                context.fillStyle = "#" + ((("00000" + ((fillColor | 0).toString(16)))).substr(-6));
                context.fill();
            }
            if (lineStyle.visible)
            {
                context.globalAlpha = lineStyle.alpha * worldAlpha;
                context.strokeStyle = "#" + ((("00000" + ((lineColor | 0).toString(16)))).substr(-6));
                context.stroke();
            }
        }
    }
};

/**
 * Updates the tint of a graphics object
 *
 * @protected
 * @param {PIXI.Graphics} graphics - the graphics that will have its tint updated
 */
CanvasGraphicsRenderer.prototype.updateGraphicsTint = function updateGraphicsTint (graphics)
{
    graphics._prevTint = graphics.tint;
    graphics.canvasTintDirty = graphics.dirty;

    var tintR = ((graphics.tint >> 16) & 0xFF) / 255;
    var tintG = ((graphics.tint >> 8) & 0xFF) / 255;
    var tintB = (graphics.tint & 0xFF) / 255;

    for (var i = 0; i < graphics.graphicsData.length; ++i)
    {
        var data = graphics.graphicsData[i];

        var fillColor = data.fillColor | 0;
        var lineColor = data.lineColor | 0;

        // super inline, cos optimization :)
        data._fillTint = (
            (((fillColor >> 16) & 0xFF) / 255 * tintR * 255 << 16)
            + (((fillColor >> 8) & 0xFF) / 255 * tintG * 255 << 8)
            + (((fillColor & 0xFF) / 255) * tintB * 255)
        );

        data._lineTint = (
            (((lineColor >> 16) & 0xFF) / 255 * tintR * 255 << 16)
            + (((lineColor >> 8) & 0xFF) / 255 * tintG * 255 << 8)
            + (((lineColor & 0xFF) / 255) * tintB * 255)
        );
    }
};

/**
 * destroy graphics object
 *
 */
CanvasGraphicsRenderer.prototype.destroy = function destroy ()
{
    this.renderer = null;
};

var canvasRenderer;
var tempMatrix = new math.Matrix();

/**
 * Generates a canvas texture. Only available with **pixi.js-legacy** bundle
 * or the **@pixi/canvas-graphics** package.
 * @method generateCanvasTexture
 * @memberof PIXI.Graphics#
 * @param {number} scaleMode - The scale mode of the texture.
 * @param {number} resolution - The resolution of the texture.
 * @return {PIXI.Texture} The new texture.
 */
graphics.Graphics.prototype.generateCanvasTexture = function generateCanvasTexture(scaleMode, resolution)
{
    if ( resolution === void 0 ) resolution = 1;

    var bounds = this.getLocalBounds();

    var canvasBuffer = core.RenderTexture.create(bounds.width, bounds.height, scaleMode, resolution);

    if (!canvasRenderer)
    {
        canvasRenderer = new canvasRenderer$1.CanvasRenderer();
    }

    this.transform.updateLocalTransform();
    this.transform.localTransform.copyTo(tempMatrix);

    tempMatrix.invert();

    tempMatrix.tx -= bounds.x;
    tempMatrix.ty -= bounds.y;

    canvasRenderer.render(this, canvasBuffer, true, tempMatrix);

    var texture = core.Texture.from(canvasBuffer.baseTexture._canvasRenderTarget.canvas, {
        scaleMode: scaleMode,
    });

    texture.baseTexture.resolution = resolution;
    texture.baseTexture.update();

    return texture;
};

graphics.Graphics.prototype.cachedGraphicsData = [];

/**
 * Renders the object using the Canvas renderer
 *
 * @method _renderCanvas
 * @memberof PIXI.Graphics#
 * @private
 * @param {PIXI.CanvasRenderer} renderer - The renderer
 */
graphics.Graphics.prototype._renderCanvas = function _renderCanvas(renderer)
{
    if (this.isMask === true)
    {
        return;
    }

    this.finishPoly();
    renderer.plugins.graphics.render(this);
};

exports.CanvasGraphicsRenderer = CanvasGraphicsRenderer;
//# sourceMappingURL=canvas-graphics.js.map