/*!
 * @pixi/mesh - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/mesh is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
'use strict';

Object.defineProperty(exports, '__esModule', { value: true });

var core = require('@pixi/core');
var math = require('@pixi/math');
var constants = require('@pixi/constants');
var display = require('@pixi/display');
var settings = require('@pixi/settings');
var utils = require('@pixi/utils');

/**
 * Class controls cache for UV mapping from Texture normal space to BaseTexture normal space.
 *
 * @class
 * @memberof PIXI
 */
var MeshBatchUvs = function MeshBatchUvs(uvBuffer, uvMatrix)
{
    /**
     * Buffer with normalized UV's
     * @member {PIXI.Buffer}
     */
    this.uvBuffer = uvBuffer;

    /**
     * Material UV matrix
     * @member {PIXI.TextureMatrix}
     */
    this.uvMatrix = uvMatrix;

    /**
     * UV Buffer data
     * @member {Float32Array}
     * @readonly
     */
    this.data = null;

    this._bufferUpdateId = -1;

    this._textureUpdateId = -1;

    this._updateID = 0;
};

/**
 * updates
 *
 * @param {boolean} forceUpdate - force the update
 */
MeshBatchUvs.prototype.update = function update (forceUpdate)
{
    if (!forceUpdate
        && this._bufferUpdateId === this.uvBuffer._updateID
        && this._textureUpdateId === this.uvMatrix._updateID)
    {
        return;
    }

    this._bufferUpdateId = this.uvBuffer._updateID;
    this._textureUpdateId = this.uvMatrix._updateID;

    var data = this.uvBuffer.data;

    if (!this.data || this.data.length !== data.length)
    {
        this.data = new Float32Array(data.length);
    }

    this.uvMatrix.multiplyUvs(data, this.data);

    this._updateID++;
};

var tempPoint = new math.Point();
var tempPolygon = new math.Polygon();

/**
 * Base mesh class.
 *
 * This class empowers you to have maximum flexibility to render any kind of WebGL visuals you can think of.
 * This class assumes a certain level of WebGL knowledge.
 * If you know a bit this should abstract enough away to make you life easier!
 *
 * Pretty much ALL WebGL can be broken down into the following:
 * - Geometry - The structure and data for the mesh. This can include anything from positions, uvs, normals, colors etc..
 * - Shader - This is the shader that PixiJS will render the geometry with (attributes in the shader must match the geometry)
 * - State - This is the state of WebGL required to render the mesh.
 *
 * Through a combination of the above elements you can render anything you want, 2D or 3D!
 *
 * @class
 * @extends PIXI.Container
 * @memberof PIXI
 */
var Mesh = /*@__PURE__*/(function (Container) {
    function Mesh(geometry, shader, state, drawMode)// vertices, uvs, indices, drawMode)
    {
        if ( drawMode === void 0 ) drawMode = constants.DRAW_MODES.TRIANGLES;

        Container.call(this);

        /**
         * Includes vertex positions, face indices, normals, colors, UVs, and
         * custom attributes within buffers, reducing the cost of passing all
         * this data to the GPU. Can be shared between multiple Mesh objects.
         * @member {PIXI.Geometry}
         * @readonly
         */
        this.geometry = geometry;

        geometry.refCount++;

        /**
         * Represents the vertex and fragment shaders that processes the geometry and runs on the GPU.
         * Can be shared between multiple Mesh objects.
         * @member {PIXI.Shader|PIXI.MeshMaterial}
         */
        this.shader = shader;

        /**
         * Represents the WebGL state the Mesh required to render, excludes shader and geometry. E.g.,
         * blend mode, culling, depth testing, direction of rendering triangles, backface, etc.
         * @member {PIXI.State}
         */
        this.state = state || core.State.for2d();

        /**
         * The way the Mesh should be drawn, can be any of the {@link PIXI.DRAW_MODES} constants.
         *
         * @member {number}
         * @see PIXI.DRAW_MODES
         */
        this.drawMode = drawMode;

        /**
         * Typically the index of the IndexBuffer where to start drawing.
         * @member {number}
         * @default 0
         */
        this.start = 0;

        /**
         * How much of the geometry to draw, by default `0` renders everything.
         * @member {number}
         * @default 0
         */
        this.size = 0;

        /**
         * thease are used as easy access for batching
         * @member {Float32Array}
         * @private
         */
        this.uvs = null;

        /**
         * thease are used as easy access for batching
         * @member {Uint16Array}
         * @private
         */
        this.indices = null;

        /**
         * this is the caching layer used by the batcher
         * @member {Float32Array}
         * @private
         */
        this.vertexData = new Float32Array(1);

        /**
         * If geometry is changed used to decide to re-transform
         * the vertexData.
         * @member {number}
         * @private
         */
        this.vertexDirty = 0;

        this._transformID = -1;

        // Inherited from DisplayMode, set defaults
        this.tint = 0xFFFFFF;
        this.blendMode = constants.BLEND_MODES.NORMAL;

        /**
         * Internal roundPixels field
         *
         * @member {boolean}
         * @private
         */
        this._roundPixels = settings.settings.ROUND_PIXELS;

        /**
         * Batched UV's are cached for atlas textures
         * @member {PIXI.MeshBatchUvs}
         * @private
         */
        this.batchUvs = null;
    }

    if ( Container ) Mesh.__proto__ = Container;
    Mesh.prototype = Object.create( Container && Container.prototype );
    Mesh.prototype.constructor = Mesh;

    var prototypeAccessors = { uvBuffer: { configurable: true },verticesBuffer: { configurable: true },material: { configurable: true },blendMode: { configurable: true },roundPixels: { configurable: true },tint: { configurable: true },texture: { configurable: true } };

    /**
     * To change mesh uv's, change its uvBuffer data and increment its _updateID.
     * @member {PIXI.Buffer}
     * @readonly
     */
    prototypeAccessors.uvBuffer.get = function ()
    {
        return this.geometry.buffers[1].data;
    };

    /**
     * To change mesh vertices, change its uvBuffer data and increment its _updateID.
     * Incrementing _updateID is optional because most of Mesh objects do it anyway.
     * @member {PIXI.Buffer}
     * @readonly
     */
    prototypeAccessors.verticesBuffer.get = function ()
    {
        return this.geometry.buffers[0].data;
    };

    /**
     * Alias for {@link PIXI.Mesh#shader}.
     * @member {PIXI.Shader|PIXI.MeshMaterial}
     */
    prototypeAccessors.material.set = function (value)
    {
        this.shader = value;
    };

    prototypeAccessors.material.get = function ()
    {
        return this.shader;
    };

    /**
     * The blend mode to be applied to the Mesh. Apply a value of
     * `PIXI.BLEND_MODES.NORMAL` to reset the blend mode.
     *
     * @member {number}
     * @default PIXI.BLEND_MODES.NORMAL;
     * @see PIXI.BLEND_MODES
     */
    prototypeAccessors.blendMode.set = function (value)
    {
        this.state.blendMode = value;
    };

    prototypeAccessors.blendMode.get = function ()
    {
        return this.state.blendMode;
    };

    /**
     * If true PixiJS will Math.floor() x/y values when rendering, stopping pixel interpolation.
     * Advantages can include sharper image quality (like text) and faster rendering on canvas.
     * The main disadvantage is movement of objects may appear less smooth.
     * To set the global default, change {@link PIXI.settings.ROUND_PIXELS}
     *
     * @member {boolean}
     * @default false
     */
    prototypeAccessors.roundPixels.set = function (value)
    {
        if (this._roundPixels !== value)
        {
            this._transformID = -1;
        }
        this._roundPixels = value;
    };

    prototypeAccessors.roundPixels.get = function ()
    {
        return this._roundPixels;
    };

    /**
     * The multiply tint applied to the Mesh. This is a hex value. A value of
     * `0xFFFFFF` will remove any tint effect.
     *
     * @member {number}
     * @default 0xFFFFFF
     */
    prototypeAccessors.tint.get = function ()
    {
        return this.shader.tint;
    };

    prototypeAccessors.tint.set = function (value)
    {
        this.shader.tint = value;
    };

    /**
     * The texture that the Mesh uses.
     *
     * @member {PIXI.Texture}
     */
    prototypeAccessors.texture.get = function ()
    {
        return this.shader.texture;
    };

    prototypeAccessors.texture.set = function (value)
    {
        this.shader.texture = value;
    };

    /**
     * Standard renderer draw.
     * @protected
     */
    Mesh.prototype._render = function _render (renderer)
    {
        // set properties for batching..
        // TODO could use a different way to grab verts?
        var vertices = this.geometry.buffers[0].data;

        // TODO benchmark check for attribute size..
        if (this.shader.batchable && this.drawMode === constants.DRAW_MODES.TRIANGLES && vertices.length < Mesh.BATCHABLE_SIZE * 2)
        {
            this._renderToBatch(renderer);
        }
        else
        {
            this._renderDefault(renderer);
        }
    };

    /**
     * Standard non-batching way of rendering.
     * @protected
     * @param {PIXI.Renderer} renderer - Instance to renderer.
     */
    Mesh.prototype._renderDefault = function _renderDefault (renderer)
    {
        var shader = this.shader;

        shader.alpha = this.worldAlpha;
        if (shader.update)
        {
            shader.update();
        }

        renderer.batch.flush();

        if (shader.program.uniformData.translationMatrix)
        {
            shader.uniforms.translationMatrix = this.transform.worldTransform.toArray(true);
        }

        // bind and sync uniforms..
        renderer.shader.bind(shader);

        // set state..
        renderer.state.setState(this.state);

        // bind the geometry...
        renderer.geometry.bind(this.geometry, shader);

        // then render it
        renderer.geometry.draw(this.drawMode, this.size, this.start, this.geometry.instanceCount);
    };

    /**
     * Rendering by using the Batch system.
     * @protected
     * @param {PIXI.Renderer} renderer - Instance to renderer.
     */
    Mesh.prototype._renderToBatch = function _renderToBatch (renderer)
    {
        var geometry = this.geometry;

        if (this.shader.uvMatrix)
        {
            this.shader.uvMatrix.update();
            this.calculateUvs();
        }

        // set properties for batching..
        this.calculateVertices();
        this.indices = geometry.indexBuffer.data;
        this._tintRGB = this.shader._tintRGB;
        this._texture = this.shader.texture;

        var pluginName = this.material.pluginName;

        renderer.batch.setObjectRenderer(renderer.plugins[pluginName]);
        renderer.plugins[pluginName].render(this);
    };

    /**
     * Updates vertexData field based on transform and vertices
     */
    Mesh.prototype.calculateVertices = function calculateVertices ()
    {
        var geometry = this.geometry;
        var vertices = geometry.buffers[0].data;

        if (geometry.vertexDirtyId === this.vertexDirty && this._transformID === this.transform._worldID)
        {
            return;
        }

        this._transformID = this.transform._worldID;

        if (this.vertexData.length !== vertices.length)
        {
            this.vertexData = new Float32Array(vertices.length);
        }

        var wt = this.transform.worldTransform;
        var a = wt.a;
        var b = wt.b;
        var c = wt.c;
        var d = wt.d;
        var tx = wt.tx;
        var ty = wt.ty;

        var vertexData = this.vertexData;

        for (var i = 0; i < vertexData.length / 2; i++)
        {
            var x = vertices[(i * 2)];
            var y = vertices[(i * 2) + 1];

            vertexData[(i * 2)] = (a * x) + (c * y) + tx;
            vertexData[(i * 2) + 1] = (b * x) + (d * y) + ty;
        }

        if (this._roundPixels)
        {
            for (var i$1 = 0; i$1 < vertexData.length; i$1++)
            {
                vertexData[i$1] = Math.round(vertexData[i$1]);
            }
        }

        this.vertexDirty = geometry.vertexDirtyId;
    };

    /**
     * Updates uv field based on from geometry uv's or batchUvs
     */
    Mesh.prototype.calculateUvs = function calculateUvs ()
    {
        var geomUvs = this.geometry.buffers[1];

        if (!this.shader.uvMatrix.isSimple)
        {
            if (!this.batchUvs)
            {
                this.batchUvs = new MeshBatchUvs(geomUvs, this.shader.uvMatrix);
            }
            this.batchUvs.update();
            this.uvs = this.batchUvs.data;
        }
        else
        {
            this.uvs = geomUvs.data;
        }
    };

    /**
     * Updates the bounds of the mesh as a rectangle. The bounds calculation takes the worldTransform into account.
     * there must be a aVertexPosition attribute present in the geometry for bounds to be calculated correctly.
     *
     * @protected
     */
    Mesh.prototype._calculateBounds = function _calculateBounds ()
    {
        this.calculateVertices();

        this._bounds.addVertexData(this.vertexData, 0, this.vertexData.length);
    };

    /**
     * Tests if a point is inside this mesh. Works only for PIXI.DRAW_MODES.TRIANGLES.
     *
     * @param {PIXI.Point} point the point to test
     * @return {boolean} the result of the test
     */
    Mesh.prototype.containsPoint = function containsPoint (point)
    {
        if (!this.getBounds().contains(point.x, point.y))
        {
            return false;
        }

        this.worldTransform.applyInverse(point, tempPoint);

        var vertices = this.geometry.getAttribute('aVertexPosition').data;

        var points = tempPolygon.points;
        var indices =  this.geometry.getIndex().data;
        var len = indices.length;
        var step = this.drawMode === 4 ? 3 : 1;

        for (var i = 0; i + 2 < len; i += step)
        {
            var ind0 = indices[i] * 2;
            var ind1 = indices[i + 1] * 2;
            var ind2 = indices[i + 2] * 2;

            points[0] = vertices[ind0];
            points[1] = vertices[ind0 + 1];
            points[2] = vertices[ind1];
            points[3] = vertices[ind1 + 1];
            points[4] = vertices[ind2];
            points[5] = vertices[ind2 + 1];

            if (tempPolygon.contains(tempPoint.x, tempPoint.y))
            {
                return true;
            }
        }

        return false;
    };
    /**
     * Destroys the Mesh object.
     *
     * @param {object|boolean} [options] - Options parameter. A boolean will act as if all
     *  options have been set to that value
     * @param {boolean} [options.children=false] - if set to true, all the children will have
     *  their destroy method called as well. 'options' will be passed on to those calls.
     */
    Mesh.prototype.destroy = function destroy (options)
    {
        Container.prototype.destroy.call(this, options);

        this.geometry.refCount--;
        if (this.geometry.refCount === 0)
        {
            this.geometry.dispose();
        }

        this.geometry = null;
        this.shader = null;
        this.state = null;
        this.uvs = null;
        this.indices = null;
        this.vertexData = null;
    };

    Object.defineProperties( Mesh.prototype, prototypeAccessors );

    return Mesh;
}(display.Container));

/**
 * The maximum number of vertices to consider batchable. Generally, the complexity
 * of the geometry.
 * @memberof PIXI.Mesh
 * @static
 * @member {number} BATCHABLE_SIZE
 */
Mesh.BATCHABLE_SIZE = 100;

var vertex = "attribute vec2 aVertexPosition;\nattribute vec2 aTextureCoord;\n\nuniform mat3 projectionMatrix;\nuniform mat3 translationMatrix;\nuniform mat3 uTextureMatrix;\n\nvarying vec2 vTextureCoord;\n\nvoid main(void)\n{\n    gl_Position = vec4((projectionMatrix * translationMatrix * vec3(aVertexPosition, 1.0)).xy, 0.0, 1.0);\n\n    vTextureCoord = (uTextureMatrix * vec3(aTextureCoord, 1.0)).xy;\n}\n";

var fragment = "varying vec2 vTextureCoord;\nuniform vec4 uColor;\n\nuniform sampler2D uSampler;\n\nvoid main(void)\n{\n    gl_FragColor = texture2D(uSampler, vTextureCoord) * uColor;\n}\n";

/**
 * Slightly opinionated default shader for PixiJS 2D objects.
 * @class
 * @memberof PIXI
 * @extends PIXI.Shader
 */
var MeshMaterial = /*@__PURE__*/(function (Shader) {
    function MeshMaterial(uSampler, options)
    {
        var uniforms = {
            uSampler: uSampler,
            alpha: 1,
            uTextureMatrix: math.Matrix.IDENTITY,
            uColor: new Float32Array([1, 1, 1, 1]),
        };

        // Set defaults
        options = Object.assign({
            tint: 0xFFFFFF,
            alpha: 1,
            pluginName: 'batch',
        }, options);

        if (options.uniforms)
        {
            Object.assign(uniforms, options.uniforms);
        }

        Shader.call(this, options.program || core.Program.from(vertex, fragment), uniforms);

        /**
         * Only do update if tint or alpha changes.
         * @member {boolean}
         * @private
         * @default false
         */
        this._colorDirty = false;

        /**
         * TextureMatrix instance for this Mesh, used to track Texture changes
         *
         * @member {PIXI.TextureMatrix}
         * @readonly
         */
        this.uvMatrix = new core.TextureMatrix(uSampler);

        /**
         * `true` if shader can be batch with the renderer's batch system.
         * @member {boolean}
         * @default true
         */
        this.batchable = options.program === undefined;

        /**
         * Renderer plugin for batching
         *
         * @member {string}
         * @default 'batch'
         */
        this.pluginName = options.pluginName;

        this.tint = options.tint;
        this.alpha = options.alpha;
    }

    if ( Shader ) MeshMaterial.__proto__ = Shader;
    MeshMaterial.prototype = Object.create( Shader && Shader.prototype );
    MeshMaterial.prototype.constructor = MeshMaterial;

    var prototypeAccessors = { texture: { configurable: true },alpha: { configurable: true },tint: { configurable: true } };

    /**
     * Reference to the texture being rendered.
     * @member {PIXI.Texture}
     */
    prototypeAccessors.texture.get = function ()
    {
        return this.uniforms.uSampler;
    };
    prototypeAccessors.texture.set = function (value)
    {
        if (this.uniforms.uSampler !== value)
        {
            this.uniforms.uSampler = value;
            this.uvMatrix.texture = value;
        }
    };

    /**
     * This gets automatically set by the object using this.
     *
     * @default 1
     * @member {number}
     */
    prototypeAccessors.alpha.set = function (value)
    {
        if (value === this._alpha) { return; }

        this._alpha = value;
        this._colorDirty = true;
    };
    prototypeAccessors.alpha.get = function ()
    {
        return this._alpha;
    };

    /**
     * Multiply tint for the material.
     * @member {number}
     * @default 0xFFFFFF
     */
    prototypeAccessors.tint.set = function (value)
    {
        if (value === this._tint) { return; }

        this._tint = value;
        this._tintRGB = (value >> 16) + (value & 0xff00) + ((value & 0xff) << 16);
        this._colorDirty = true;
    };
    prototypeAccessors.tint.get = function ()
    {
        return this._tint;
    };

    /**
     * Gets called automatically by the Mesh. Intended to be overridden for custom
     * MeshMaterial objects.
     */
    MeshMaterial.prototype.update = function update ()
    {
        if (this._colorDirty)
        {
            this._colorDirty = false;
            var baseTexture = this.texture.baseTexture;

            utils.premultiplyTintToRgba(this._tint, this._alpha, this.uniforms.uColor, baseTexture.premultiplyAlpha);
        }
        if (this.uvMatrix.update())
        {
            this.uniforms.uTextureMatrix = this.uvMatrix.mapCoord;
        }
    };

    Object.defineProperties( MeshMaterial.prototype, prototypeAccessors );

    return MeshMaterial;
}(core.Shader));

/**
 * Standard 2D geometry used in PixiJS.
 *
 * Geometry can be defined without passing in a style or data if required.
 *
 * ```js
 * const geometry = new PIXI.Geometry();
 *
 * geometry.addAttribute('positions', [0, 0, 100, 0, 100, 100, 0, 100], 2);
 * geometry.addAttribute('uvs', [0,0,1,0,1,1,0,1], 2);
 * geometry.addIndex([0,1,2,1,3,2]);
 *
 * ```
 * @class
 * @memberof PIXI
 * @extends PIXI.Geometry
 */
var MeshGeometry = /*@__PURE__*/(function (Geometry) {
    function MeshGeometry(vertices, uvs, index)
    {
        Geometry.call(this);

        var verticesBuffer = new core.Buffer(vertices);
        var uvsBuffer = new core.Buffer(uvs, true);
        var indexBuffer = new core.Buffer(index, true, true);

        this.addAttribute('aVertexPosition', verticesBuffer, 2, false, constants.TYPES.FLOAT)
            .addAttribute('aTextureCoord', uvsBuffer, 2, false, constants.TYPES.FLOAT)
            .addIndex(indexBuffer);

        /**
         * Dirty flag to limit update calls on Mesh. For example,
         * limiting updates on a single Mesh instance with a shared Geometry
         * within the render loop.
         * @private
         * @member {number}
         * @default -1
         */
        this._updateId = -1;
    }

    if ( Geometry ) MeshGeometry.__proto__ = Geometry;
    MeshGeometry.prototype = Object.create( Geometry && Geometry.prototype );
    MeshGeometry.prototype.constructor = MeshGeometry;

    var prototypeAccessors = { vertexDirtyId: { configurable: true } };

    /**
     * If the vertex position is updated.
     * @member {number}
     * @readonly
     * @private
     */
    prototypeAccessors.vertexDirtyId.get = function ()
    {
        return this.buffers[0]._updateID;
    };

    Object.defineProperties( MeshGeometry.prototype, prototypeAccessors );

    return MeshGeometry;
}(core.Geometry));

exports.Mesh = Mesh;
exports.MeshBatchUvs = MeshBatchUvs;
exports.MeshGeometry = MeshGeometry;
exports.MeshMaterial = MeshMaterial;
//# sourceMappingURL=mesh.js.map
