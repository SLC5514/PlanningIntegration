/*!
 * @pixi/mesh-extras - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/mesh-extras is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
import { MeshGeometry, Mesh, MeshMaterial } from '@pixi/mesh';
import { Texture } from '@pixi/core';

var PlaneGeometry = /*@__PURE__*/(function (MeshGeometry) {
    function PlaneGeometry(width, height, segWidth, segHeight)
    {
        if ( width === void 0 ) width = 100;
        if ( height === void 0 ) height = 100;
        if ( segWidth === void 0 ) segWidth = 10;
        if ( segHeight === void 0 ) segHeight = 10;

        MeshGeometry.call(this);

        this.segWidth = segWidth;
        this.segHeight = segHeight;

        this.width = width;
        this.height = height;

        this.build();
    }

    if ( MeshGeometry ) PlaneGeometry.__proto__ = MeshGeometry;
    PlaneGeometry.prototype = Object.create( MeshGeometry && MeshGeometry.prototype );
    PlaneGeometry.prototype.constructor = PlaneGeometry;

    /**
     * Refreshes plane coordinates
     * @private
     */
    PlaneGeometry.prototype.build = function build ()
    {
        var total = this.segWidth * this.segHeight;
        var verts = [];
        var uvs = [];
        var indices = [];

        var segmentsX = this.segWidth - 1;
        var segmentsY = this.segHeight - 1;

        var sizeX = (this.width) / segmentsX;
        var sizeY = (this.height) / segmentsY;

        for (var i = 0; i < total; i++)
        {
            var x = (i % this.segWidth);
            var y = ((i / this.segWidth) | 0);

            verts.push(x * sizeX, y * sizeY);
            uvs.push(x / segmentsX, y / segmentsY);
        }

        var totalSub = segmentsX * segmentsY;

        for (var i$1 = 0; i$1 < totalSub; i$1++)
        {
            var xpos = i$1 % segmentsX;
            var ypos = (i$1 / segmentsX) | 0;

            var value = (ypos * this.segWidth) + xpos;
            var value2 = (ypos * this.segWidth) + xpos + 1;
            var value3 = ((ypos + 1) * this.segWidth) + xpos;
            var value4 = ((ypos + 1) * this.segWidth) + xpos + 1;

            indices.push(value, value2, value3,
                value2, value4, value3);
        }

        this.buffers[0].data = new Float32Array(verts);
        this.buffers[1].data = new Float32Array(uvs);
        this.indexBuffer.data = new Uint16Array(indices);

        // ensure that the changes are uploaded
        this.buffers[0].update();
        this.buffers[1].update();
        this.indexBuffer.update();
    };

    return PlaneGeometry;
}(MeshGeometry));

/**
 * RopeGeometry allows you to draw a geometry across several points and then manipulate these points.
 *
 * ```js
 * for (let i = 0; i < 20; i++) {
 *     points.push(new PIXI.Point(i * 50, 0));
 * };
 * const rope = new PIXI.RopeGeometry(100, points);
 * ```
 *
 * @class
 * @extends PIXI.MeshGeometry
 * @memberof PIXI
 *
 */
var RopeGeometry = /*@__PURE__*/(function (MeshGeometry) {
    function RopeGeometry(width, points)
    {
        if ( width === void 0 ) width = 200;

        MeshGeometry.call(this, new Float32Array(points.length * 4),
            new Float32Array(points.length * 4),
            new Uint16Array((points.length - 1) * 6));

        /**
         * An array of points that determine the rope
         * @member {PIXI.Point[]}
         */
        this.points = points;

        /**
         * The width (i.e., thickness) of the rope.
         * @member {number}
         * @readOnly
         */
        this.width = width;

        this.build();
    }

    if ( MeshGeometry ) RopeGeometry.__proto__ = MeshGeometry;
    RopeGeometry.prototype = Object.create( MeshGeometry && MeshGeometry.prototype );
    RopeGeometry.prototype.constructor = RopeGeometry;
    /**
     * Refreshes Rope indices and uvs
     * @private
     */
    RopeGeometry.prototype.build = function build ()
    {
        var points = this.points;

        if (!points) { return; }

        var vertexBuffer = this.getAttribute('aVertexPosition');
        var uvBuffer = this.getAttribute('aTextureCoord');
        var indexBuffer = this.getIndex();

        // if too little points, or texture hasn't got UVs set yet just move on.
        if (points.length < 1)
        {
            return;
        }

        // if the number of points has changed we will need to recreate the arraybuffers
        if (vertexBuffer.data.length / 4 !== points.length)
        {
            vertexBuffer.data = new Float32Array(points.length * 4);
            uvBuffer.data = new Float32Array(points.length * 4);
            indexBuffer.data = new Uint16Array((points.length - 1) * 6);
        }

        var uvs = uvBuffer.data;
        var indices = indexBuffer.data;

        uvs[0] = 0;
        uvs[1] = 0;
        uvs[2] = 0;
        uvs[3] = 1;

        // indices[0] = 0;
        // indices[1] = 1;

        var total = points.length; // - 1;

        for (var i = 0; i < total; i++)
        {
            // time to do some smart drawing!
            var index = i * 4;
            var amount = i / (total - 1);

            uvs[index] = amount;
            uvs[index + 1] = 0;

            uvs[index + 2] = amount;
            uvs[index + 3] = 1;
        }

        var indexCount = 0;

        for (var i$1 = 0; i$1 < total - 1; i$1++)
        {
            var index$1 = i$1 * 2;

            indices[indexCount++] = index$1;
            indices[indexCount++] = index$1 + 1;
            indices[indexCount++] = index$1 + 2;

            indices[indexCount++] = index$1 + 2;
            indices[indexCount++] = index$1 + 1;
            indices[indexCount++] = index$1 + 3;
        }

        // ensure that the changes are uploaded
        uvBuffer.update();
        indexBuffer.update();

        this.updateVertices();
    };

    /**
     * refreshes vertices of Rope mesh
     */
    RopeGeometry.prototype.updateVertices = function updateVertices ()
    {
        var points = this.points;

        if (points.length < 1)
        {
            return;
        }

        var lastPoint = points[0];
        var nextPoint;
        var perpX = 0;
        var perpY = 0;

        // this.count -= 0.2;

        var vertices = this.buffers[0].data;
        var total = points.length;

        for (var i = 0; i < total; i++)
        {
            var point = points[i];
            var index = i * 4;

            if (i < points.length - 1)
            {
                nextPoint = points[i + 1];
            }
            else
            {
                nextPoint = point;
            }

            perpY = -(nextPoint.x - lastPoint.x);
            perpX = nextPoint.y - lastPoint.y;

            var perpLength = Math.sqrt((perpX * perpX) + (perpY * perpY));
            var num = this.width / 2; // (20 + Math.abs(Math.sin((i + this.count) * 0.3) * 50) )* ratio;

            perpX /= perpLength;
            perpY /= perpLength;

            perpX *= num;
            perpY *= num;

            vertices[index] = point.x + perpX;
            vertices[index + 1] = point.y + perpY;
            vertices[index + 2] = point.x - perpX;
            vertices[index + 3] = point.y - perpY;

            lastPoint = point;
        }

        this.buffers[0].update();
    };

    RopeGeometry.prototype.update = function update ()
    {
        this.updateVertices();
    };

    return RopeGeometry;
}(MeshGeometry));

/**
 * The rope allows you to draw a texture across several points and then manipulate these points
 *
 *```js
 * for (let i = 0; i < 20; i++) {
 *     points.push(new PIXI.Point(i * 50, 0));
 * };
 * let rope = new PIXI.Rope(PIXI.Texture.from("snake.png"), points);
 *  ```
 *
 * @class
 * @extends PIXI.Mesh
 * @memberof PIXI
 *
 */
var SimpleRope = /*@__PURE__*/(function (Mesh) {
    function SimpleRope(texture, points)
    {
        var ropeGeometry = new RopeGeometry(texture.height, points);
        var meshMaterial = new MeshMaterial(texture);

        Mesh.call(this, ropeGeometry, meshMaterial);

        /**
         * re-calculate vertices by rope points each frame
         *
         * @member {boolean}
         */
        this.autoUpdate = true;
    }

    if ( Mesh ) SimpleRope.__proto__ = Mesh;
    SimpleRope.prototype = Object.create( Mesh && Mesh.prototype );
    SimpleRope.prototype.constructor = SimpleRope;

    SimpleRope.prototype._render = function _render (renderer)
    {
        if (this.autoUpdate
            || this.geometry.width !== this.shader.texture.height)
        {
            this.geometry.width = this.shader.texture.height;
            this.geometry.update();
        }

        Mesh.prototype._render.call(this, renderer);
    };

    return SimpleRope;
}(Mesh));

/**
 * The Plane allows you to draw a texture across several points and then manipulate these points
 *
 *```js
 * for (let i = 0; i < 20; i++) {
 *     points.push(new PIXI.Point(i * 50, 0));
 * };
 * let Plane = new PIXI.Plane(PIXI.Texture.from("snake.png"), points);
 *  ```
 *
 * @class
 * @extends PIXI.Mesh
 * @memberof PIXI
 *
 */
var SimplePlane = /*@__PURE__*/(function (Mesh) {
    function SimplePlane(texture, verticesX, verticesY)
    {
        var planeGeometry = new PlaneGeometry(texture.width, texture.height, verticesX, verticesY);
        var meshMaterial = new MeshMaterial(Texture.WHITE);

        Mesh.call(this, planeGeometry, meshMaterial);

        // lets call the setter to ensure all necessary updates are performed
        this.texture = texture;
    }

    if ( Mesh ) SimplePlane.__proto__ = Mesh;
    SimplePlane.prototype = Object.create( Mesh && Mesh.prototype );
    SimplePlane.prototype.constructor = SimplePlane;

    var prototypeAccessors = { texture: { configurable: true } };

    /**
     * Method used for overrides, to do something in case texture frame was changed.
     * Meshes based on plane can override it and change more details based on texture.
     */
    SimplePlane.prototype.textureUpdated = function textureUpdated ()
    {
        this._textureID = this.shader.texture._updateID;

        this.geometry.width = this.shader.texture.width;
        this.geometry.height = this.shader.texture.height;

        this.geometry.build();
    };

    prototypeAccessors.texture.set = function (value)
    {
        // Track texture same way sprite does.
        // For generated meshes like NineSlicePlane it can change the geometry.
        // Unfortunately, this method might not work if you directly change texture in material.

        if (this.shader.texture === value)
        {
            return;
        }

        this.shader.texture = value;
        this._textureID = -1;

        if (value.baseTexture.valid)
        {
            this.textureUpdated();
        }
        else
        {
            value.once('update', this.textureUpdated, this);
        }
    };

    prototypeAccessors.texture.get = function ()
    {
        return this.shader.texture;
    };

    SimplePlane.prototype._render = function _render (renderer)
    {
        if (this._textureID !== this.shader.texture._updateID)
        {
            this.textureUpdated();
        }

        Mesh.prototype._render.call(this, renderer);
    };

    Object.defineProperties( SimplePlane.prototype, prototypeAccessors );

    return SimplePlane;
}(Mesh));

/**
 * The Simple Mesh class mimics Mesh in PixiJS v4, providing easy-to-use constructor arguments.
 * For more robust customization, use {@link PIXI.Mesh}.
 *
 * @class
 * @extends PIXI.Mesh
 * @memberof PIXI
 */
var SimpleMesh = /*@__PURE__*/(function (Mesh) {
    function SimpleMesh(texture, vertices, uvs, indices, drawMode)
    {
        if ( texture === void 0 ) texture = Texture.EMPTY;

        var geometry = new MeshGeometry(vertices, uvs, indices);

        geometry.getAttribute('aVertexPosition').static = false;

        var meshMaterial = new MeshMaterial(texture);

        Mesh.call(this, geometry, meshMaterial, null, drawMode);

        /**
         * upload vertices buffer each frame
         * @member {boolean}
         */
        this.autoUpdate = true;
    }

    if ( Mesh ) SimpleMesh.__proto__ = Mesh;
    SimpleMesh.prototype = Object.create( Mesh && Mesh.prototype );
    SimpleMesh.prototype.constructor = SimpleMesh;

    var prototypeAccessors = { vertices: { configurable: true } };

    /**
     * Collection of vertices data.
     * @member {Float32Array}
     */
    prototypeAccessors.vertices.get = function ()
    {
        return this.geometry.getAttribute('aVertexPosition').data;
    };
    prototypeAccessors.vertices.set = function (value)
    {
        this.geometry.getAttribute('aVertexPosition').data = value;
    };

    SimpleMesh.prototype._render = function _render (renderer)
    {
        if (this.autoUpdate)
        {
            this.geometry.getAttribute('aVertexPosition').update();
        }

        Mesh.prototype._render.call(this, renderer);
    };

    Object.defineProperties( SimpleMesh.prototype, prototypeAccessors );

    return SimpleMesh;
}(Mesh));

var DEFAULT_BORDER_SIZE = 10;

/**
 * The NineSlicePlane allows you to stretch a texture using 9-slice scaling. The corners will remain unscaled (useful
 * for buttons with rounded corners for example) and the other areas will be scaled horizontally and or vertically
 *
 *```js
 * let Plane9 = new PIXI.NineSlicePlane(PIXI.Texture.from('BoxWithRoundedCorners.png'), 15, 15, 15, 15);
 *  ```
 * <pre>
 *      A                          B
 *    +---+----------------------+---+
 *  C | 1 |          2           | 3 |
 *    +---+----------------------+---+
 *    |   |                      |   |
 *    | 4 |          5           | 6 |
 *    |   |                      |   |
 *    +---+----------------------+---+
 *  D | 7 |          8           | 9 |
 *    +---+----------------------+---+

 *  When changing this objects width and/or height:
 *     areas 1 3 7 and 9 will remain unscaled.
 *     areas 2 and 8 will be stretched horizontally
 *     areas 4 and 6 will be stretched vertically
 *     area 5 will be stretched both horizontally and vertically
 * </pre>
 *
 * @class
 * @extends PIXI.SimplePlane
 * @memberof PIXI
 *
 */
var NineSlicePlane = /*@__PURE__*/(function (SimplePlane) {
    function NineSlicePlane(texture, leftWidth, topHeight, rightWidth, bottomHeight)
    {
        SimplePlane.call(this, Texture.WHITE, 4, 4);

        this._origWidth = texture.orig.width;
        this._origHeight = texture.orig.height;

        /**
         * The width of the NineSlicePlane, setting this will actually modify the vertices and UV's of this plane
         *
         * @member {number}
         * @override
         */
        this._width = this._origWidth;

        /**
         * The height of the NineSlicePlane, setting this will actually modify the vertices and UV's of this plane
         *
         * @member {number}
         * @override
         */
        this._height = this._origHeight;

        /**
         * The width of the left column (a)
         *
         * @member {number}
         * @private
         */
        this._leftWidth = typeof leftWidth !== 'undefined' ? leftWidth : DEFAULT_BORDER_SIZE;

        /**
         * The width of the right column (b)
         *
         * @member {number}
         * @private
         */
        this._rightWidth = typeof rightWidth !== 'undefined' ? rightWidth : DEFAULT_BORDER_SIZE;

        /**
         * The height of the top row (c)
         *
         * @member {number}
         * @private
         */
        this._topHeight = typeof topHeight !== 'undefined' ? topHeight : DEFAULT_BORDER_SIZE;

        /**
         * The height of the bottom row (d)
         *
         * @member {number}
         * @private
         */
        this._bottomHeight = typeof bottomHeight !== 'undefined' ? bottomHeight : DEFAULT_BORDER_SIZE;

        // lets call the setter to ensure all necessary updates are performed
        this.texture = texture;
    }

    if ( SimplePlane ) NineSlicePlane.__proto__ = SimplePlane;
    NineSlicePlane.prototype = Object.create( SimplePlane && SimplePlane.prototype );
    NineSlicePlane.prototype.constructor = NineSlicePlane;

    var prototypeAccessors = { vertices: { configurable: true },width: { configurable: true },height: { configurable: true },leftWidth: { configurable: true },rightWidth: { configurable: true },topHeight: { configurable: true },bottomHeight: { configurable: true } };

    NineSlicePlane.prototype.textureUpdated = function textureUpdated ()
    {
        this._textureID = this.shader.texture._updateID;
        this._refresh();
    };

    prototypeAccessors.vertices.get = function ()
    {
        return this.geometry.getAttribute('aVertexPosition').data;
    };

    prototypeAccessors.vertices.set = function (value)
    {
        this.geometry.getAttribute('aVertexPosition').data = value;
    };

    /**
     * Updates the horizontal vertices.
     *
     */
    NineSlicePlane.prototype.updateHorizontalVertices = function updateHorizontalVertices ()
    {
        var vertices = this.vertices;

        var h = this._topHeight + this._bottomHeight;
        var scale = this._height > h ? 1.0 : this._height / h;

        vertices[9] = vertices[11] = vertices[13] = vertices[15] = this._topHeight * scale;
        vertices[17] = vertices[19] = vertices[21] = vertices[23] = this._height - (this._bottomHeight * scale);
        vertices[25] = vertices[27] = vertices[29] = vertices[31] = this._height;
    };

    /**
     * Updates the vertical vertices.
     *
     */
    NineSlicePlane.prototype.updateVerticalVertices = function updateVerticalVertices ()
    {
        var vertices = this.vertices;

        var w = this._leftWidth + this._rightWidth;
        var scale = this._width > w ? 1.0 : this._width / w;

        vertices[2] = vertices[10] = vertices[18] = vertices[26] = this._leftWidth * scale;
        vertices[4] = vertices[12] = vertices[20] = vertices[28] = this._width - (this._rightWidth * scale);
        vertices[6] = vertices[14] = vertices[22] = vertices[30] = this._width;
    };

    /**
     * The width of the NineSlicePlane, setting this will actually modify the vertices and UV's of this plane
     *
     * @member {number}
     */
    prototypeAccessors.width.get = function ()
    {
        return this._width;
    };

    prototypeAccessors.width.set = function (value) // eslint-disable-line require-jsdoc
    {
        this._width = value;
        this._refresh();
    };

    /**
     * The height of the NineSlicePlane, setting this will actually modify the vertices and UV's of this plane
     *
     * @member {number}
     */
    prototypeAccessors.height.get = function ()
    {
        return this._height;
    };

    prototypeAccessors.height.set = function (value) // eslint-disable-line require-jsdoc
    {
        this._height = value;
        this._refresh();
    };

    /**
     * The width of the left column
     *
     * @member {number}
     */
    prototypeAccessors.leftWidth.get = function ()
    {
        return this._leftWidth;
    };

    prototypeAccessors.leftWidth.set = function (value) // eslint-disable-line require-jsdoc
    {
        this._leftWidth = value;
        this._refresh();
    };

    /**
     * The width of the right column
     *
     * @member {number}
     */
    prototypeAccessors.rightWidth.get = function ()
    {
        return this._rightWidth;
    };

    prototypeAccessors.rightWidth.set = function (value) // eslint-disable-line require-jsdoc
    {
        this._rightWidth = value;
        this._refresh();
    };

    /**
     * The height of the top row
     *
     * @member {number}
     */
    prototypeAccessors.topHeight.get = function ()
    {
        return this._topHeight;
    };

    prototypeAccessors.topHeight.set = function (value) // eslint-disable-line require-jsdoc
    {
        this._topHeight = value;
        this._refresh();
    };

    /**
     * The height of the bottom row
     *
     * @member {number}
     */
    prototypeAccessors.bottomHeight.get = function ()
    {
        return this._bottomHeight;
    };

    prototypeAccessors.bottomHeight.set = function (value) // eslint-disable-line require-jsdoc
    {
        this._bottomHeight = value;
        this._refresh();
    };

    /**
     * Refreshes NineSlicePlane coords. All of them.
     */
    NineSlicePlane.prototype._refresh = function _refresh ()
    {
        var texture = this.texture;

        var uvs = this.geometry.buffers[1].data;

        this._origWidth = texture.orig.width;
        this._origHeight = texture.orig.height;

        var _uvw = 1.0 / this._origWidth;
        var _uvh = 1.0 / this._origHeight;

        uvs[0] = uvs[8] = uvs[16] = uvs[24] = 0;
        uvs[1] = uvs[3] = uvs[5] = uvs[7] = 0;
        uvs[6] = uvs[14] = uvs[22] = uvs[30] = 1;
        uvs[25] = uvs[27] = uvs[29] = uvs[31] = 1;

        uvs[2] = uvs[10] = uvs[18] = uvs[26] = _uvw * this._leftWidth;
        uvs[4] = uvs[12] = uvs[20] = uvs[28] = 1 - (_uvw * this._rightWidth);
        uvs[9] = uvs[11] = uvs[13] = uvs[15] = _uvh * this._topHeight;
        uvs[17] = uvs[19] = uvs[21] = uvs[23] = 1 - (_uvh * this._bottomHeight);

        this.updateHorizontalVertices();
        this.updateVerticalVertices();

        this.geometry.buffers[0].update();
        this.geometry.buffers[1].update();
    };

    Object.defineProperties( NineSlicePlane.prototype, prototypeAccessors );

    return NineSlicePlane;
}(SimplePlane));

export { NineSlicePlane, PlaneGeometry, RopeGeometry, SimpleMesh, SimplePlane, SimpleRope };
//# sourceMappingURL=mesh-extras.es.js.map