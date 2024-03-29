/*!
 * @pixi/canvas-mesh - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/canvas-mesh is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
import { DRAW_MODES } from '@pixi/constants';
import { settings } from '@pixi/settings';
import { MeshMaterial, Mesh } from '@pixi/mesh';
import { CanvasTinter } from '@pixi/canvas-renderer';
import { NineSlicePlane, SimpleMesh, SimpleRope } from '@pixi/mesh-extras';

/**
 * Renderer dedicated to meshes.
 *
 * @class
 * @protected
 * @memberof PIXI
 */
var CanvasMeshRenderer = function CanvasMeshRenderer(renderer)
{
    this.renderer = renderer;
};

/**
 * Renders the Mesh
 *
 * @param {PIXI.Mesh} mesh - the Mesh to render
 */
CanvasMeshRenderer.prototype.render = function render (mesh)
{
    var renderer = this.renderer;
    var context = renderer.context;

    var transform = mesh.worldTransform;
    var res = renderer.resolution;

    if (mesh.roundPixels)
    {
        context.setTransform(
            transform.a * res,
            transform.b * res,
            transform.c * res,
            transform.d * res,
            (transform.tx * res) | 0,
            (transform.ty * res) | 0
        );
    }
    else
    {
        context.setTransform(
            transform.a * res,
            transform.b * res,
            transform.c * res,
            transform.d * res,
            transform.tx * res,
            transform.ty * res
        );
    }

    renderer.context.globalAlpha = mesh.worldAlpha;
    renderer.setBlendMode(mesh.blendMode);

    if (mesh.drawMode !== DRAW_MODES.TRIANGLES)
    {
        this._renderTriangleMesh(mesh);
    }
    else
    {
        this._renderTriangles(mesh);
    }
};

/**
 * Draws the object in Triangle Mesh mode
 *
 * @private
 * @param {PIXI.RawMesh} mesh - the Mesh to render
 */
CanvasMeshRenderer.prototype._renderTriangleMesh = function _renderTriangleMesh (mesh)
{
    // draw triangles!!
    var length = mesh.geometry.buffers[0].data.length;

    for (var i = 0; i < length - 2; i++)
    {
        // draw some triangles!
        var index = i * 2;

        this._renderDrawTriangle(mesh, index, (index + 2), (index + 4));
    }
};

/**
 * Draws the object in triangle mode using canvas
 *
 * @private
 * @param {PIXI.RawMesh} mesh - the current mesh
 */
CanvasMeshRenderer.prototype._renderTriangles = function _renderTriangles (mesh)
{
    // draw triangles!!
    var indices = mesh.geometry.getIndex().data;
    var length = indices.length;

    for (var i = 0; i < length; i += 3)
    {
        // draw some triangles!
        var index0 = indices[i] * 2;
        var index1 = indices[i + 1] * 2;
        var index2 = indices[i + 2] * 2;

        this._renderDrawTriangle(mesh, index0, index1, index2);
    }
};

/**
 * Draws one of the triangles that from the Mesh
 *
 * @private
 * @param {PIXI.RawMesh} mesh - the current mesh
 * @param {number} index0 - the index of the first vertex
 * @param {number} index1 - the index of the second vertex
 * @param {number} index2 - the index of the third vertex
 */
CanvasMeshRenderer.prototype._renderDrawTriangle = function _renderDrawTriangle (mesh, index0, index1, index2)
{
    var context = this.renderer.context;
    var vertices = mesh.geometry.buffers[0].data;
    var uvs = mesh.uvs;
        var texture = mesh.texture;

    if (!texture.valid)
    {
        return;
    }

    var base = texture.baseTexture;
    var textureSource = base.getDrawableSource();
    var textureWidth = base.width;
    var textureHeight = base.height;

    var u0 = uvs[index0] * base.width;
    var u1 = uvs[index1] * base.width;
    var u2 = uvs[index2] * base.width;
    var v0 = uvs[index0 + 1] * base.height;
    var v1 = uvs[index1 + 1] * base.height;
    var v2 = uvs[index2 + 1] * base.height;

    var x0 = vertices[index0];
    var x1 = vertices[index1];
    var x2 = vertices[index2];
    var y0 = vertices[index0 + 1];
    var y1 = vertices[index1 + 1];
    var y2 = vertices[index2 + 1];

    var canvasPadding = mesh.canvasPadding / this.renderer.resolution;

    if (canvasPadding > 0)
    {
        var paddingX = canvasPadding / Math.abs(mesh.worldTransform.a);
        var paddingY = canvasPadding / Math.abs(mesh.worldTransform.d);
        var centerX = (x0 + x1 + x2) / 3;
        var centerY = (y0 + y1 + y2) / 3;

        var normX = x0 - centerX;
        var normY = y0 - centerY;

        var dist = Math.sqrt((normX * normX) + (normY * normY));

        x0 = centerX + ((normX / dist) * (dist + paddingX));
        y0 = centerY + ((normY / dist) * (dist + paddingY));

        //

        normX = x1 - centerX;
        normY = y1 - centerY;

        dist = Math.sqrt((normX * normX) + (normY * normY));
        x1 = centerX + ((normX / dist) * (dist + paddingX));
        y1 = centerY + ((normY / dist) * (dist + paddingY));

        normX = x2 - centerX;
        normY = y2 - centerY;

        dist = Math.sqrt((normX * normX) + (normY * normY));
        x2 = centerX + ((normX / dist) * (dist + paddingX));
        y2 = centerY + ((normY / dist) * (dist + paddingY));
    }

    context.save();
    context.beginPath();

    context.moveTo(x0, y0);
    context.lineTo(x1, y1);
    context.lineTo(x2, y2);

    context.closePath();

    context.clip();

    // Compute matrix transform
    var delta = (u0 * v1) + (v0 * u2) + (u1 * v2) - (v1 * u2) - (v0 * u1) - (u0 * v2);
    var deltaA = (x0 * v1) + (v0 * x2) + (x1 * v2) - (v1 * x2) - (v0 * x1) - (x0 * v2);
    var deltaB = (u0 * x1) + (x0 * u2) + (u1 * x2) - (x1 * u2) - (x0 * u1) - (u0 * x2);
    var deltaC = (u0 * v1 * x2) + (v0 * x1 * u2) + (x0 * u1 * v2) - (x0 * v1 * u2) - (v0 * u1 * x2) - (u0 * x1 * v2);
    var deltaD = (y0 * v1) + (v0 * y2) + (y1 * v2) - (v1 * y2) - (v0 * y1) - (y0 * v2);
    var deltaE = (u0 * y1) + (y0 * u2) + (u1 * y2) - (y1 * u2) - (y0 * u1) - (u0 * y2);
    var deltaF = (u0 * v1 * y2) + (v0 * y1 * u2) + (y0 * u1 * v2) - (y0 * v1 * u2) - (v0 * u1 * y2) - (u0 * y1 * v2);

    context.transform(
        deltaA / delta,
        deltaD / delta,
        deltaB / delta,
        deltaE / delta,
        deltaC / delta,
        deltaF / delta
    );

    context.drawImage(
        textureSource,
        0,
        0,
        textureWidth * base.resolution,
        textureHeight * base.resolution,
        0,
        0,
        textureWidth,
        textureHeight
    );

    context.restore();
    this.renderer.invalidateBlendMode();
};

/**
 * Renders a flat Mesh
 *
 * @private
 * @param {PIXI.RawMesh} mesh - The Mesh to render
 */
CanvasMeshRenderer.prototype.renderMeshFlat = function renderMeshFlat (mesh)
{
    var context = this.renderer.context;
    var vertices = mesh.geometry.getAttribute('aVertexPosition').data;
    var length = vertices.length / 2;

    // this.count++;

    context.beginPath();

    for (var i = 1; i < length - 2; ++i)
    {
        // draw some triangles!
        var index = i * 2;

        var x0 = vertices[index];
        var y0 = vertices[index + 1];

        var x1 = vertices[index + 2];
        var y1 = vertices[index + 3];

        var x2 = vertices[index + 4];
        var y2 = vertices[index + 5];

        context.moveTo(x0, y0);
        context.lineTo(x1, y1);
        context.lineTo(x2, y2);
    }

    context.fillStyle = '#FF0000';
    context.fill();
    context.closePath();
};

/**
 * destroy the the renderer.
 *
 */
CanvasMeshRenderer.prototype.destroy = function destroy ()
{
    this.renderer = null;
};

/**
 * Default `canvasPadding` for canvas-based Mesh rendering.
 *
 * @see PIXI.Mesh2d#canvasPadding
 * @static
 * @name MESH_CANVAS_PADDING
 * @memberof PIXI.settings
 * @type {number}
 * @default 0
 */
settings.MESH_CANVAS_PADDING = 0;

/**
 * Renders the mesh using the Canvas renderer
 *
 * @protected
 * @method render
 * @memberof PIXI.MeshMaterial#
 * @param {PIXI.CanvasRenderer} renderer - The canvas renderer.
 * @param {PIXI.Mesh} mesh - Mesh to render.
 */
MeshMaterial.prototype._renderCanvas = function _renderCanvas(renderer, mesh)
{
    renderer.plugins.mesh.render(mesh);
};

/**
 * Cached tint value so we can tell when the tint is changed.
 * @memberof PIXI.NineSlicePlane#
 * @member {number} _cachedTint
 * @protected
 */
NineSlicePlane.prototype._cachedTint = 0xFFFFFF;

/**
 * Cached tinted texture.
 * @memberof PIXI.NineSlicePlane#
 * @member {HTMLCanvasElement} _tintedCanvas
 * @protected
 */
NineSlicePlane.prototype._tintedCanvas = null;

/**
 * Temporary storage for canvas source coords
 * @memberof PIXI.NineSlicePlane#
 * @member {number[]} _canvasUvs
 * @private
 */
NineSlicePlane.prototype._canvasUvs = null;

/**
 * Renders the object using the Canvas renderer
 *
 * @private
 * @method _renderCanvas
 * @memberof PIXI.NineSlicePlane#
 * @param {PIXI.CanvasRenderer} renderer - The canvas renderer to render with.
 */
NineSlicePlane.prototype._renderCanvas = function _renderCanvas(renderer)
{
    var context = renderer.context;
    var transform = this.worldTransform;
    var res = renderer.resolution;
    var isTinted = this.tint !== 0xFFFFFF;
    var texture = this._texture;

    // Work out tinting
    if (isTinted)
    {
        if (this._cachedTint !== this.tint)
        {
            // Tint has changed, need to update the tinted texture and use that instead

            this._cachedTint = this.tint;

            this._tintedCanvas = CanvasTinter.getTintedCanvas(this, this.tint);
        }
    }

    var textureSource = !isTinted ? texture.baseTexture.source : this._tintedCanvas;

    if (!this._canvasUvs)
    {
        this._canvasUvs = [0, 0, 0, 0, 0, 0, 0, 0];
    }

    var vertices = this.vertices;
    var uvs = this._canvasUvs;
    var u0 = isTinted ? 0 : texture.frame.x;
    var v0 = isTinted ? 0 : texture.frame.y;
    var u1 = u0 + texture.frame.width;
    var v1 = v0 + texture.frame.height;

    uvs[0] = u0;
    uvs[1] = u0 + this._leftWidth;
    uvs[2] = u1 - this._rightWidth;
    uvs[3] = u1;
    uvs[4] = v0;
    uvs[5] = v0 + this._topHeight;
    uvs[6] = v1 - this._bottomHeight;
    uvs[7] = v1;

    for (var i = 0; i < 8; i++)
    {
        uvs[i] *= texture.baseTexture.resolution;
    }

    context.globalAlpha = this.worldAlpha;
    renderer.setBlendMode(this.blendMode);

    if (this.roundPixels)
    {
        context.setTransform(
            transform.a * res,
            transform.b * res,
            transform.c * res,
            transform.d * res,
            (transform.tx * res) | 0,
            (transform.ty * res) | 0
        );
    }
    else
    {
        context.setTransform(
            transform.a * res,
            transform.b * res,
            transform.c * res,
            transform.d * res,
            transform.tx * res,
            transform.ty * res
        );
    }

    for (var row = 0; row < 3; row++)
    {
        for (var col = 0; col < 3; col++)
        {
            var ind = (col * 2) + (row * 8);
            var sw = Math.max(1, uvs[col + 1] - uvs[col]);
            var sh = Math.max(1, uvs[row + 5] - uvs[row + 4]);
            var dw = Math.max(1, vertices[ind + 10] - vertices[ind]);
            var dh = Math.max(1, vertices[ind + 11] - vertices[ind + 1]);

            context.drawImage(textureSource, uvs[col], uvs[row + 4], sw, sh,
                vertices[ind], vertices[ind + 1], dw, dh);
        }
    }
};

/**
 * Renders the object using the Canvas renderer
 *
 * @private
 * @method _renderCanvas
 * @memberof PIXI.Mesh#
 * @param {PIXI.CanvasRenderer} renderer - The canvas renderer.
 */
Mesh.prototype._renderCanvas = function _renderCanvas(renderer)
{
    if (this.shader.uvMatrix)
    {
        this.shader.uvMatrix.update();
        this.calculateUvs();
    }

    this.material._renderCanvas(renderer, this);
};

// IMPORTANT: Please do NOT use this as a precedent to use `settings` after the object is created
// this was merely created to completely decouple canvas from the base Mesh class and we are
// unable to add `canvasPadding` in the constructor anymore, as the case was for PixiJS v4.

/**
 * Internal variable for `canvasPadding`.
 *
 * @private
 * @memberof PIXI.Mesh
 * @member {number}
 * @default null
 */
Mesh.prototype._canvasPadding = null;

/**
 * Triangles in canvas mode are automatically antialiased, use this value to force triangles
 * to overlap a bit with each other. To set the global default, set {@link PIXI.settings.MESH_CANVAS_PADDING}
 *
 * @see PIXI.settings.MESH_CANVAS_PADDING
 * @member {number} canvasPadding
 * @memberof PIXI.SimpleMesh#
 * @default 0
 */
Object.defineProperty(Mesh.prototype, 'canvasPadding', {
    get: function get()
    {
        return this._canvasPadding !== null ? this._canvasPadding : settings.MESH_CANVAS_PADDING;
    },
    set: function set(value)
    {
        this._canvasPadding = value;
    },
});

/**
 * Renders the object using the Canvas renderer
 *
 * @private
 * @method _renderCanvas
 * @memberof PIXI.Mesh#
 * @param {PIXI.CanvasRenderer} renderer - The canvas renderer.
 */
SimpleMesh.prototype._renderCanvas = function _renderCanvas(renderer)
{
    if (this.autoUpdate)
    {
        this.geometry.getAttribute('aVertexPosition').update();
    }

    if (this.shader.update)
    {
        this.shader.update();
    }

    this.calculateUvs();

    this.material._renderCanvas(renderer, this);
};

/**
 * Renders the object using the Canvas renderer
 *
 * @protected
 * @method _renderCanvas
 * @memberof PIXI.Mesh#
 * @param {PIXI.CanvasRenderer} renderer - The canvas renderer.
 */
SimpleRope.prototype._renderCanvas = function _renderCanvas(renderer)
{
    if (this.autoUpdate
        || this.geometry.width !== this.shader.texture.height)
    {
        this.geometry.width = this.shader.texture.height;
        this.geometry.update();
    }

    if (this.shader.update)
    {
        this.shader.update();
    }

    this.calculateUvs();

    this.material._renderCanvas(renderer, this);
};

export { CanvasMeshRenderer };
//# sourceMappingURL=canvas-mesh.es.js.map
