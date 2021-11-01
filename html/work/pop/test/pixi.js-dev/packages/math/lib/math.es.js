/*!
 * @pixi/math - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/math is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
/**
 * The Point object represents a location in a two-dimensional coordinate system, where x represents
 * the horizontal axis and y represents the vertical axis.
 *
 * @class
 * @memberof PIXI
 */
var Point = function Point(x, y)
{
    if ( x === void 0 ) x = 0;
    if ( y === void 0 ) y = 0;

    /**
     * @member {number}
     * @default 0
     */
    this.x = x;

    /**
     * @member {number}
     * @default 0
     */
    this.y = y;
};

/**
 * Creates a clone of this point
 *
 * @return {PIXI.Point} a copy of the point
 */
Point.prototype.clone = function clone ()
{
    return new Point(this.x, this.y);
};

/**
 * Copies x and y from the given point
 *
 * @param {PIXI.IPoint} p - The point to copy from
 * @returns {PIXI.IPoint} Returns itself.
 */
Point.prototype.copyFrom = function copyFrom (p)
{
    this.set(p.x, p.y);

    return this;
};

/**
 * Copies x and y into the given point
 *
 * @param {PIXI.IPoint} p - The point to copy.
 * @returns {PIXI.IPoint} Given point with values updated
 */
Point.prototype.copyTo = function copyTo (p)
{
    p.set(this.x, this.y);

    return p;
};

/**
 * Returns true if the given point is equal to this point
 *
 * @param {PIXI.IPoint} p - The point to check
 * @returns {boolean} Whether the given point equal to this point
 */
Point.prototype.equals = function equals (p)
{
    return (p.x === this.x) && (p.y === this.y);
};

/**
 * Sets the point to a new x and y position.
 * If y is omitted, both x and y will be set to x.
 *
 * @param {number} [x=0] - position of the point on the x axis
 * @param {number} [y=0] - position of the point on the y axis
 */
Point.prototype.set = function set (x, y)
{
    this.x = x || 0;
    this.y = y || ((y !== 0) ? this.x : 0);
};

/**
 * The Point object represents a location in a two-dimensional coordinate system, where x represents
 * the horizontal axis and y represents the vertical axis.
 *
 * An ObservablePoint is a point that triggers a callback when the point's position is changed.
 *
 * @class
 * @memberof PIXI
 */
var ObservablePoint = function ObservablePoint(cb, scope, x, y)
{
    if ( x === void 0 ) x = 0;
    if ( y === void 0 ) y = 0;

    this._x = x;
    this._y = y;

    this.cb = cb;
    this.scope = scope;
};

var prototypeAccessors = { x: { configurable: true },y: { configurable: true } };

/**
 * Creates a clone of this point.
 * The callback and scope params can be overidden otherwise they will default
 * to the clone object's values.
 *
 * @override
 * @param {Function} [cb=null] - callback when changed
 * @param {object} [scope=null] - owner of callback
 * @return {PIXI.ObservablePoint} a copy of the point
 */
ObservablePoint.prototype.clone = function clone (cb, scope)
{
        if ( cb === void 0 ) cb = null;
        if ( scope === void 0 ) scope = null;

    var _cb = cb || this.cb;
    var _scope = scope || this.scope;

    return new ObservablePoint(_cb, _scope, this._x, this._y);
};

/**
 * Sets the point to a new x and y position.
 * If y is omitted, both x and y will be set to x.
 *
 * @param {number} [x=0] - position of the point on the x axis
 * @param {number} [y=0] - position of the point on the y axis
 */
ObservablePoint.prototype.set = function set (x, y)
{
    var _x = x || 0;
    var _y = y || ((y !== 0) ? _x : 0);

    if (this._x !== _x || this._y !== _y)
    {
        this._x = _x;
        this._y = _y;
        this.cb.call(this.scope);
    }
};

/**
 * Copies x and y from the given point
 *
 * @param {PIXI.IPoint} p - The point to copy from.
 * @returns {PIXI.IPoint} Returns itself.
 */
ObservablePoint.prototype.copyFrom = function copyFrom (p)
{
    if (this._x !== p.x || this._y !== p.y)
    {
        this._x = p.x;
        this._y = p.y;
        this.cb.call(this.scope);
    }

    return this;
};

/**
 * Copies x and y into the given point
 *
 * @param {PIXI.IPoint} p - The point to copy.
 * @returns {PIXI.IPoint} Given point with values updated
 */
ObservablePoint.prototype.copyTo = function copyTo (p)
{
    p.set(this._x, this._y);

    return p;
};

/**
 * Returns true if the given point is equal to this point
 *
 * @param {PIXI.IPoint} p - The point to check
 * @returns {boolean} Whether the given point equal to this point
 */
ObservablePoint.prototype.equals = function equals (p)
{
    return (p.x === this._x) && (p.y === this._y);
};

/**
 * The position of the displayObject on the x axis relative to the local coordinates of the parent.
 *
 * @member {number}
 */
prototypeAccessors.x.get = function ()
{
    return this._x;
};

prototypeAccessors.x.set = function (value) // eslint-disable-line require-jsdoc
{
    if (this._x !== value)
    {
        this._x = value;
        this.cb.call(this.scope);
    }
};

/**
 * The position of the displayObject on the x axis relative to the local coordinates of the parent.
 *
 * @member {number}
 */
prototypeAccessors.y.get = function ()
{
    return this._y;
};

prototypeAccessors.y.set = function (value) // eslint-disable-line require-jsdoc
{
    if (this._y !== value)
    {
        this._y = value;
        this.cb.call(this.scope);
    }
};

Object.defineProperties( ObservablePoint.prototype, prototypeAccessors );

/**
 * A number, or a string containing a number.
 * @memberof PIXI
 * @typedef {(PIXI.Point|PIXI.ObservablePoint)} IPoint
 */

/**
 * Two Pi.
 *
 * @static
 * @constant {number} PI_2
 * @memberof PIXI
 */
var PI_2 = Math.PI * 2;

/**
 * Conversion factor for converting radians to degrees.
 *
 * @static
 * @constant {number} RAD_TO_DEG
 * @memberof PIXI
 */
var RAD_TO_DEG = 180 / Math.PI;

/**
 * Conversion factor for converting degrees to radians.
 *
 * @static
 * @constant {number} DEG_TO_RAD
 * @memberof PIXI
 */
var DEG_TO_RAD = Math.PI / 180;

/**
 * Constants that identify shapes, mainly to prevent `instanceof` calls.
 *
 * @static
 * @constant
 * @name SHAPES
 * @memberof PIXI
 * @type {object}
 * @property {number} POLY Polygon
 * @property {number} RECT Rectangle
 * @property {number} CIRC Circle
 * @property {number} ELIP Ellipse
 * @property {number} RREC Rounded Rectangle
 */
var SHAPES = {
    POLY: 0,
    RECT: 1,
    CIRC: 2,
    ELIP: 3,
    RREC: 4,
};

/**
 * The PixiJS Matrix as a class makes it a lot faster.
 *
 * Here is a representation of it:
 * ```js
 * | a | c | tx|
 * | b | d | ty|
 * | 0 | 0 | 1 |
 * ```
 * @class
 * @memberof PIXI
 */
var Matrix = function Matrix(a, b, c, d, tx, ty)
{
    if ( a === void 0 ) a = 1;
    if ( b === void 0 ) b = 0;
    if ( c === void 0 ) c = 0;
    if ( d === void 0 ) d = 1;
    if ( tx === void 0 ) tx = 0;
    if ( ty === void 0 ) ty = 0;

    /**
     * @member {number}
     * @default 1
     */
    this.a = a;

    /**
     * @member {number}
     * @default 0
     */
    this.b = b;

    /**
     * @member {number}
     * @default 0
     */
    this.c = c;

    /**
     * @member {number}
     * @default 1
     */
    this.d = d;

    /**
     * @member {number}
     * @default 0
     */
    this.tx = tx;

    /**
     * @member {number}
     * @default 0
     */
    this.ty = ty;

    this.array = null;
};

var staticAccessors = { IDENTITY: { configurable: true },TEMP_MATRIX: { configurable: true } };

/**
 * Creates a Matrix object based on the given array. The Element to Matrix mapping order is as follows:
 *
 * a = array[0]
 * b = array[1]
 * c = array[3]
 * d = array[4]
 * tx = array[2]
 * ty = array[5]
 *
 * @param {number[]} array - The array that the matrix will be populated from.
 */
Matrix.prototype.fromArray = function fromArray (array)
{
    this.a = array[0];
    this.b = array[1];
    this.c = array[3];
    this.d = array[4];
    this.tx = array[2];
    this.ty = array[5];
};

/**
 * sets the matrix properties
 *
 * @param {number} a - Matrix component
 * @param {number} b - Matrix component
 * @param {number} c - Matrix component
 * @param {number} d - Matrix component
 * @param {number} tx - Matrix component
 * @param {number} ty - Matrix component
 *
 * @return {PIXI.Matrix} This matrix. Good for chaining method calls.
 */
Matrix.prototype.set = function set (a, b, c, d, tx, ty)
{
    this.a = a;
    this.b = b;
    this.c = c;
    this.d = d;
    this.tx = tx;
    this.ty = ty;

    return this;
};

/**
 * Creates an array from the current Matrix object.
 *
 * @param {boolean} transpose - Whether we need to transpose the matrix or not
 * @param {Float32Array} [out=new Float32Array(9)] - If provided the array will be assigned to out
 * @return {number[]} the newly created array which contains the matrix
 */
Matrix.prototype.toArray = function toArray (transpose, out)
{
    if (!this.array)
    {
        this.array = new Float32Array(9);
    }

    var array = out || this.array;

    if (transpose)
    {
        array[0] = this.a;
        array[1] = this.b;
        array[2] = 0;
        array[3] = this.c;
        array[4] = this.d;
        array[5] = 0;
        array[6] = this.tx;
        array[7] = this.ty;
        array[8] = 1;
    }
    else
    {
        array[0] = this.a;
        array[1] = this.c;
        array[2] = this.tx;
        array[3] = this.b;
        array[4] = this.d;
        array[5] = this.ty;
        array[6] = 0;
        array[7] = 0;
        array[8] = 1;
    }

    return array;
};

/**
 * Get a new position with the current transformation applied.
 * Can be used to go from a child's coordinate space to the world coordinate space. (e.g. rendering)
 *
 * @param {PIXI.Point} pos - The origin
 * @param {PIXI.Point} [newPos] - The point that the new position is assigned to (allowed to be same as input)
 * @return {PIXI.Point} The new point, transformed through this matrix
 */
Matrix.prototype.apply = function apply (pos, newPos)
{
    newPos = newPos || new Point();

    var x = pos.x;
    var y = pos.y;

    newPos.x = (this.a * x) + (this.c * y) + this.tx;
    newPos.y = (this.b * x) + (this.d * y) + this.ty;

    return newPos;
};

/**
 * Get a new position with the inverse of the current transformation applied.
 * Can be used to go from the world coordinate space to a child's coordinate space. (e.g. input)
 *
 * @param {PIXI.Point} pos - The origin
 * @param {PIXI.Point} [newPos] - The point that the new position is assigned to (allowed to be same as input)
 * @return {PIXI.Point} The new point, inverse-transformed through this matrix
 */
Matrix.prototype.applyInverse = function applyInverse (pos, newPos)
{
    newPos = newPos || new Point();

    var id = 1 / ((this.a * this.d) + (this.c * -this.b));

    var x = pos.x;
    var y = pos.y;

    newPos.x = (this.d * id * x) + (-this.c * id * y) + (((this.ty * this.c) - (this.tx * this.d)) * id);
    newPos.y = (this.a * id * y) + (-this.b * id * x) + (((-this.ty * this.a) + (this.tx * this.b)) * id);

    return newPos;
};

/**
 * Translates the matrix on the x and y.
 *
 * @param {number} x How much to translate x by
 * @param {number} y How much to translate y by
 * @return {PIXI.Matrix} This matrix. Good for chaining method calls.
 */
Matrix.prototype.translate = function translate (x, y)
{
    this.tx += x;
    this.ty += y;

    return this;
};

/**
 * Applies a scale transformation to the matrix.
 *
 * @param {number} x The amount to scale horizontally
 * @param {number} y The amount to scale vertically
 * @return {PIXI.Matrix} This matrix. Good for chaining method calls.
 */
Matrix.prototype.scale = function scale (x, y)
{
    this.a *= x;
    this.d *= y;
    this.c *= x;
    this.b *= y;
    this.tx *= x;
    this.ty *= y;

    return this;
};

/**
 * Applies a rotation transformation to the matrix.
 *
 * @param {number} angle - The angle in radians.
 * @return {PIXI.Matrix} This matrix. Good for chaining method calls.
 */
Matrix.prototype.rotate = function rotate (angle)
{
    var cos = Math.cos(angle);
    var sin = Math.sin(angle);

    var a1 = this.a;
    var c1 = this.c;
    var tx1 = this.tx;

    this.a = (a1 * cos) - (this.b * sin);
    this.b = (a1 * sin) + (this.b * cos);
    this.c = (c1 * cos) - (this.d * sin);
    this.d = (c1 * sin) + (this.d * cos);
    this.tx = (tx1 * cos) - (this.ty * sin);
    this.ty = (tx1 * sin) + (this.ty * cos);

    return this;
};

/**
 * Appends the given Matrix to this Matrix.
 *
 * @param {PIXI.Matrix} matrix - The matrix to append.
 * @return {PIXI.Matrix} This matrix. Good for chaining method calls.
 */
Matrix.prototype.append = function append (matrix)
{
    var a1 = this.a;
    var b1 = this.b;
    var c1 = this.c;
    var d1 = this.d;

    this.a = (matrix.a * a1) + (matrix.b * c1);
    this.b = (matrix.a * b1) + (matrix.b * d1);
    this.c = (matrix.c * a1) + (matrix.d * c1);
    this.d = (matrix.c * b1) + (matrix.d * d1);

    this.tx = (matrix.tx * a1) + (matrix.ty * c1) + this.tx;
    this.ty = (matrix.tx * b1) + (matrix.ty * d1) + this.ty;

    return this;
};

/**
 * Sets the matrix based on all the available properties
 *
 * @param {number} x - Position on the x axis
 * @param {number} y - Position on the y axis
 * @param {number} pivotX - Pivot on the x axis
 * @param {number} pivotY - Pivot on the y axis
 * @param {number} scaleX - Scale on the x axis
 * @param {number} scaleY - Scale on the y axis
 * @param {number} rotation - Rotation in radians
 * @param {number} skewX - Skew on the x axis
 * @param {number} skewY - Skew on the y axis
 * @return {PIXI.Matrix} This matrix. Good for chaining method calls.
 */
Matrix.prototype.setTransform = function setTransform (x, y, pivotX, pivotY, scaleX, scaleY, rotation, skewX, skewY)
{
    this.a = Math.cos(rotation + skewY) * scaleX;
    this.b = Math.sin(rotation + skewY) * scaleX;
    this.c = -Math.sin(rotation - skewX) * scaleY;
    this.d = Math.cos(rotation - skewX) * scaleY;

    this.tx = x - ((pivotX * this.a) + (pivotY * this.c));
    this.ty = y - ((pivotX * this.b) + (pivotY * this.d));

    return this;
};

/**
 * Prepends the given Matrix to this Matrix.
 *
 * @param {PIXI.Matrix} matrix - The matrix to prepend
 * @return {PIXI.Matrix} This matrix. Good for chaining method calls.
 */
Matrix.prototype.prepend = function prepend (matrix)
{
    var tx1 = this.tx;

    if (matrix.a !== 1 || matrix.b !== 0 || matrix.c !== 0 || matrix.d !== 1)
    {
        var a1 = this.a;
        var c1 = this.c;

        this.a = (a1 * matrix.a) + (this.b * matrix.c);
        this.b = (a1 * matrix.b) + (this.b * matrix.d);
        this.c = (c1 * matrix.a) + (this.d * matrix.c);
        this.d = (c1 * matrix.b) + (this.d * matrix.d);
    }

    this.tx = (tx1 * matrix.a) + (this.ty * matrix.c) + matrix.tx;
    this.ty = (tx1 * matrix.b) + (this.ty * matrix.d) + matrix.ty;

    return this;
};

/**
 * Decomposes the matrix (x, y, scaleX, scaleY, and rotation) and sets the properties on to a transform.
 *
 * @param {PIXI.Transform} transform - The transform to apply the properties to.
 * @return {PIXI.Transform} The transform with the newly applied properties
 */
Matrix.prototype.decompose = function decompose (transform)
{
    // sort out rotation / skew..
    var a = this.a;
    var b = this.b;
    var c = this.c;
    var d = this.d;

    var skewX = -Math.atan2(-c, d);
    var skewY = Math.atan2(b, a);

    var delta = Math.abs(skewX + skewY);

    if (delta < 0.00001 || Math.abs(PI_2 - delta) < 0.00001)
    {
        transform.rotation = skewY;
        transform.skew.x = transform.skew.y = 0;
    }
    else
    {
        transform.rotation = 0;
        transform.skew.x = skewX;
        transform.skew.y = skewY;
    }

    // next set scale
    transform.scale.x = Math.sqrt((a * a) + (b * b));
    transform.scale.y = Math.sqrt((c * c) + (d * d));

    // next set position
    transform.position.x = this.tx;
    transform.position.y = this.ty;

    return transform;
};

/**
 * Inverts this matrix
 *
 * @return {PIXI.Matrix} This matrix. Good for chaining method calls.
 */
Matrix.prototype.invert = function invert ()
{
    var a1 = this.a;
    var b1 = this.b;
    var c1 = this.c;
    var d1 = this.d;
    var tx1 = this.tx;
    var n = (a1 * d1) - (b1 * c1);

    this.a = d1 / n;
    this.b = -b1 / n;
    this.c = -c1 / n;
    this.d = a1 / n;
    this.tx = ((c1 * this.ty) - (d1 * tx1)) / n;
    this.ty = -((a1 * this.ty) - (b1 * tx1)) / n;

    return this;
};

/**
 * Resets this Matrix to an identity (default) matrix.
 *
 * @return {PIXI.Matrix} This matrix. Good for chaining method calls.
 */
Matrix.prototype.identity = function identity ()
{
    this.a = 1;
    this.b = 0;
    this.c = 0;
    this.d = 1;
    this.tx = 0;
    this.ty = 0;

    return this;
};

/**
 * Creates a new Matrix object with the same values as this one.
 *
 * @return {PIXI.Matrix} A copy of this matrix. Good for chaining method calls.
 */
Matrix.prototype.clone = function clone ()
{
    var matrix = new Matrix();

    matrix.a = this.a;
    matrix.b = this.b;
    matrix.c = this.c;
    matrix.d = this.d;
    matrix.tx = this.tx;
    matrix.ty = this.ty;

    return matrix;
};

/**
 * Changes the values of the given matrix to be the same as the ones in this matrix
 *
 * @param {PIXI.Matrix} matrix - The matrix to copy to.
 * @return {PIXI.Matrix} The matrix given in parameter with its values updated.
 */
Matrix.prototype.copyTo = function copyTo (matrix)
{
    matrix.a = this.a;
    matrix.b = this.b;
    matrix.c = this.c;
    matrix.d = this.d;
    matrix.tx = this.tx;
    matrix.ty = this.ty;

    return matrix;
};

/**
 * Changes the values of the matrix to be the same as the ones in given matrix
 *
 * @param {PIXI.Matrix} matrix - The matrix to copy from.
 * @return {PIXI.Matrix} this
 */
Matrix.prototype.copyFrom = function copyFrom (matrix)
{
    this.a = matrix.a;
    this.b = matrix.b;
    this.c = matrix.c;
    this.d = matrix.d;
    this.tx = matrix.tx;
    this.ty = matrix.ty;

    return this;
};

/**
 * A default (identity) matrix
 *
 * @static
 * @const
 * @member {PIXI.Matrix}
 */
staticAccessors.IDENTITY.get = function ()
{
    return new Matrix();
};

/**
 * A temp matrix
 *
 * @static
 * @const
 * @member {PIXI.Matrix}
 */
staticAccessors.TEMP_MATRIX.get = function ()
{
    return new Matrix();
};

Object.defineProperties( Matrix, staticAccessors );

// Your friendly neighbour https://en.wikipedia.org/wiki/Dihedral_group of order 16

var ux = [1, 1, 0, -1, -1, -1, 0, 1, 1, 1, 0, -1, -1, -1, 0, 1];
var uy = [0, 1, 1, 1, 0, -1, -1, -1, 0, 1, 1, 1, 0, -1, -1, -1];
var vx = [0, -1, -1, -1, 0, 1, 1, 1, 0, 1, 1, 1, 0, -1, -1, -1];
var vy = [1, 1, 0, -1, -1, -1, 0, 1, -1, -1, 0, 1, 1, 1, 0, -1];
var tempMatrices = [];

var mul = [];

function signum(x)
{
    if (x < 0)
    {
        return -1;
    }
    if (x > 0)
    {
        return 1;
    }

    return 0;
}

function init()
{
    for (var i = 0; i < 16; i++)
    {
        var row = [];

        mul.push(row);

        for (var j = 0; j < 16; j++)
        {
            var _ux = signum((ux[i] * ux[j]) + (vx[i] * uy[j]));
            var _uy = signum((uy[i] * ux[j]) + (vy[i] * uy[j]));
            var _vx = signum((ux[i] * vx[j]) + (vx[i] * vy[j]));
            var _vy = signum((uy[i] * vx[j]) + (vy[i] * vy[j]));

            for (var k = 0; k < 16; k++)
            {
                if (ux[k] === _ux && uy[k] === _uy && vx[k] === _vx && vy[k] === _vy)
                {
                    row.push(k);
                    break;
                }
            }
        }
    }

    for (var i$1 = 0; i$1 < 16; i$1++)
    {
        var mat = new Matrix();

        mat.set(ux[i$1], uy[i$1], vx[i$1], vy[i$1], 0, 0);
        tempMatrices.push(mat);
    }
}

init();

/**
 * Implements Dihedral Group D_8, see [group D4]{@link http://mathworld.wolfram.com/DihedralGroupD4.html},
 * D8 is the same but with diagonals. Used for texture rotations.
 *
 * Vector xX(i), xY(i) is U-axis of sprite with rotation i
 * Vector yY(i), yY(i) is V-axis of sprite with rotation i
 * Rotations: 0 grad (0), 90 grad (2), 180 grad (4), 270 grad (6)
 * Mirrors: vertical (8), main diagonal (10), horizontal (12), reverse diagonal (14)
 * This is the small part of gameofbombs.com portal system. It works.
 *
 * @author Ivan @ivanpopelyshev
 * @class
 * @memberof PIXI
 */
var GroupD8 = {
    E: 0,
    SE: 1,
    S: 2,
    SW: 3,
    W: 4,
    NW: 5,
    N: 6,
    NE: 7,
    MIRROR_VERTICAL: 8,
    MIRROR_HORIZONTAL: 12,
    uX: function (ind) { return ux[ind]; },
    uY: function (ind) { return uy[ind]; },
    vX: function (ind) { return vx[ind]; },
    vY: function (ind) { return vy[ind]; },
    inv: function (rotation) {
        if (rotation & 8)
        {
            return rotation & 15;
        }

        return (-rotation) & 7;
    },
    add: function (rotationSecond, rotationFirst) { return mul[rotationSecond][rotationFirst]; },
    sub: function (rotationSecond, rotationFirst) { return mul[rotationSecond][GroupD8.inv(rotationFirst)]; },

    /**
     * Adds 180 degrees to rotation. Commutative operation.
     *
     * @memberof PIXI.GroupD8
     * @param {number} rotation - The number to rotate.
     * @returns {number} rotated number
     */
    rotate180: function (rotation) { return rotation ^ 4; },

    /**
     * Direction of main vector can be horizontal, vertical or diagonal.
     * Some objects work with vertical directions different.
     *
     * @memberof PIXI.GroupD8
     * @param {number} rotation - The number to check.
     * @returns {boolean} Whether or not the direction is vertical
     */
    isVertical: function (rotation) { return (rotation & 3) === 2; },

    /**
     * @memberof PIXI.GroupD8
     * @param {number} dx - TODO
     * @param {number} dy - TODO
     *
     * @return {number} TODO
     */
    byDirection: function (dx, dy) {
        if (Math.abs(dx) * 2 <= Math.abs(dy))
        {
            if (dy >= 0)
            {
                return GroupD8.S;
            }

            return GroupD8.N;
        }
        else if (Math.abs(dy) * 2 <= Math.abs(dx))
        {
            if (dx > 0)
            {
                return GroupD8.E;
            }

            return GroupD8.W;
        }
        else if (dy > 0)
        {
            if (dx > 0)
            {
                return GroupD8.SE;
            }

            return GroupD8.SW;
        }
        else if (dx > 0)
        {
            return GroupD8.NE;
        }

        return GroupD8.NW;
    },

    /**
     * Helps sprite to compensate texture packer rotation.
     *
     * @memberof PIXI.GroupD8
     * @param {PIXI.Matrix} matrix - sprite world matrix
     * @param {number} rotation - The rotation factor to use.
     * @param {number} tx - sprite anchoring
     * @param {number} ty - sprite anchoring
     */
    matrixAppendRotationInv: function (matrix, rotation, tx, ty) {
        if ( tx === void 0 ) tx = 0;
        if ( ty === void 0 ) ty = 0;

        // Packer used "rotation", we use "inv(rotation)"
        var mat = tempMatrices[GroupD8.inv(rotation)];

        mat.tx = tx;
        mat.ty = ty;
        matrix.append(mat);
    },
};

/**
 * Transform that takes care about its versions
 *
 * @class
 * @memberof PIXI
 */
var Transform = function Transform()
{
    /**
     * The global matrix transform. It can be swapped temporarily by some functions like getLocalBounds()
     *
     * @member {PIXI.Matrix}
     */
    this.worldTransform = new Matrix();

    /**
     * The local matrix transform
     *
     * @member {PIXI.Matrix}
     */
    this.localTransform = new Matrix();

    /**
     * The coordinate of the object relative to the local coordinates of the parent.
     *
     * @member {PIXI.ObservablePoint}
     */
    this.position = new ObservablePoint(this.onChange, this, 0, 0);

    /**
     * The scale factor of the object.
     *
     * @member {PIXI.ObservablePoint}
     */
    this.scale = new ObservablePoint(this.onChange, this, 1, 1);

    /**
     * The pivot point of the displayObject that it rotates around.
     *
     * @member {PIXI.ObservablePoint}
     */
    this.pivot = new ObservablePoint(this.onChange, this, 0, 0);

    /**
     * The skew amount, on the x and y axis.
     *
     * @member {PIXI.ObservablePoint}
     */
    this.skew = new ObservablePoint(this.updateSkew, this, 0, 0);

    this._rotation = 0;

    this._cx = 1; // cos rotation + skewY;
    this._sx = 0; // sin rotation + skewY;
    this._cy = 0; // cos rotation + Math.PI/2 - skewX;
    this._sy = 1; // sin rotation + Math.PI/2 - skewX;

    this._localID = 0;
    this._currentLocalID = 0;

    this._worldID = 0;
    this._parentID = 0;
};

var prototypeAccessors$1 = { rotation: { configurable: true } };

/**
 * Called when a value changes.
 *
 * @private
 */
Transform.prototype.onChange = function onChange ()
{
    this._localID++;
};

/**
 * Called when skew or rotation changes
 *
 * @private
 */
Transform.prototype.updateSkew = function updateSkew ()
{
    this._cx = Math.cos(this._rotation + this.skew._y);
    this._sx = Math.sin(this._rotation + this.skew._y);
    this._cy = -Math.sin(this._rotation - this.skew._x); // cos, added PI/2
    this._sy = Math.cos(this._rotation - this.skew._x); // sin, added PI/2

    this._localID++;
};

/**
 * Updates only local matrix
 */
Transform.prototype.updateLocalTransform = function updateLocalTransform ()
{
    var lt = this.localTransform;

    if (this._localID !== this._currentLocalID)
    {
        // get the matrix values of the displayobject based on its transform properties..
        lt.a = this._cx * this.scale._x;
        lt.b = this._sx * this.scale._x;
        lt.c = this._cy * this.scale._y;
        lt.d = this._sy * this.scale._y;

        lt.tx = this.position._x - ((this.pivot._x * lt.a) + (this.pivot._y * lt.c));
        lt.ty = this.position._y - ((this.pivot._x * lt.b) + (this.pivot._y * lt.d));
        this._currentLocalID = this._localID;

        // force an update..
        this._parentID = -1;
    }
};

/**
 * Updates the values of the object and applies the parent's transform.
 *
 * @param {PIXI.Transform} parentTransform - The transform of the parent of this object
 */
Transform.prototype.updateTransform = function updateTransform (parentTransform)
{
    var lt = this.localTransform;

    if (this._localID !== this._currentLocalID)
    {
        // get the matrix values of the displayobject based on its transform properties..
        lt.a = this._cx * this.scale._x;
        lt.b = this._sx * this.scale._x;
        lt.c = this._cy * this.scale._y;
        lt.d = this._sy * this.scale._y;

        lt.tx = this.position._x - ((this.pivot._x * lt.a) + (this.pivot._y * lt.c));
        lt.ty = this.position._y - ((this.pivot._x * lt.b) + (this.pivot._y * lt.d));
        this._currentLocalID = this._localID;

        // force an update..
        this._parentID = -1;
    }

    if (this._parentID !== parentTransform._worldID)
    {
        // concat the parent matrix with the objects transform.
        var pt = parentTransform.worldTransform;
        var wt = this.worldTransform;

        wt.a = (lt.a * pt.a) + (lt.b * pt.c);
        wt.b = (lt.a * pt.b) + (lt.b * pt.d);
        wt.c = (lt.c * pt.a) + (lt.d * pt.c);
        wt.d = (lt.c * pt.b) + (lt.d * pt.d);
        wt.tx = (lt.tx * pt.a) + (lt.ty * pt.c) + pt.tx;
        wt.ty = (lt.tx * pt.b) + (lt.ty * pt.d) + pt.ty;

        this._parentID = parentTransform._worldID;

        // update the id of the transform..
        this._worldID++;
    }
};

/**
 * Decomposes a matrix and sets the transforms properties based on it.
 *
 * @param {PIXI.Matrix} matrix - The matrix to decompose
 */
Transform.prototype.setFromMatrix = function setFromMatrix (matrix)
{
    matrix.decompose(this);
    this._localID++;
};

/**
 * The rotation of the object in radians.
 *
 * @member {number}
 */
prototypeAccessors$1.rotation.get = function ()
{
    return this._rotation;
};

prototypeAccessors$1.rotation.set = function (value) // eslint-disable-line require-jsdoc
{
    if (this._rotation !== value)
    {
        this._rotation = value;
        this.updateSkew();
    }
};

Object.defineProperties( Transform.prototype, prototypeAccessors$1 );

Transform.IDENTITY = new Transform();

/**
 * Rectangle object is an area defined by its position, as indicated by its top-left corner
 * point (x, y) and by its width and its height.
 *
 * @class
 * @memberof PIXI
 */
var Rectangle = function Rectangle(x, y, width, height)
{
    if ( x === void 0 ) x = 0;
    if ( y === void 0 ) y = 0;
    if ( width === void 0 ) width = 0;
    if ( height === void 0 ) height = 0;

    /**
     * @member {number}
     * @default 0
     */
    this.x = Number(x);

    /**
     * @member {number}
     * @default 0
     */
    this.y = Number(y);

    /**
     * @member {number}
     * @default 0
     */
    this.width = Number(width);

    /**
     * @member {number}
     * @default 0
     */
    this.height = Number(height);

    /**
     * The type of the object, mainly used to avoid `instanceof` checks
     *
     * @member {number}
     * @readOnly
     * @default PIXI.SHAPES.RECT
     * @see PIXI.SHAPES
     */
    this.type = SHAPES.RECT;
};

var prototypeAccessors$2 = { left: { configurable: true },right: { configurable: true },top: { configurable: true },bottom: { configurable: true } };
var staticAccessors$1 = { EMPTY: { configurable: true } };

/**
 * returns the left edge of the rectangle
 *
 * @member {number}
 */
prototypeAccessors$2.left.get = function ()
{
    return this.x;
};

/**
 * returns the right edge of the rectangle
 *
 * @member {number}
 */
prototypeAccessors$2.right.get = function ()
{
    return this.x + this.width;
};

/**
 * returns the top edge of the rectangle
 *
 * @member {number}
 */
prototypeAccessors$2.top.get = function ()
{
    return this.y;
};

/**
 * returns the bottom edge of the rectangle
 *
 * @member {number}
 */
prototypeAccessors$2.bottom.get = function ()
{
    return this.y + this.height;
};

/**
 * A constant empty rectangle.
 *
 * @static
 * @constant
 * @member {PIXI.Rectangle}
 */
staticAccessors$1.EMPTY.get = function ()
{
    return new Rectangle(0, 0, 0, 0);
};

/**
 * Creates a clone of this Rectangle
 *
 * @return {PIXI.Rectangle} a copy of the rectangle
 */
Rectangle.prototype.clone = function clone ()
{
    return new Rectangle(this.x, this.y, this.width, this.height);
};

/**
 * Copies another rectangle to this one.
 *
 * @param {PIXI.Rectangle} rectangle - The rectangle to copy from.
 * @return {PIXI.Rectangle} Returns itself.
 */
Rectangle.prototype.copyFrom = function copyFrom (rectangle)
{
    this.x = rectangle.x;
    this.y = rectangle.y;
    this.width = rectangle.width;
    this.height = rectangle.height;

    return this;
};

/**
 * Copies this rectangle to another one.
 *
 * @param {PIXI.Rectangle} rectangle - The rectangle to copy to.
 * @return {PIXI.Rectangle} Returns given parameter.
 */
Rectangle.prototype.copyTo = function copyTo (rectangle)
{
    rectangle.x = this.x;
    rectangle.y = this.y;
    rectangle.width = this.width;
    rectangle.height = this.height;

    return rectangle;
};

/**
 * Checks whether the x and y coordinates given are contained within this Rectangle
 *
 * @param {number} x - The X coordinate of the point to test
 * @param {number} y - The Y coordinate of the point to test
 * @return {boolean} Whether the x/y coordinates are within this Rectangle
 */
Rectangle.prototype.contains = function contains (x, y)
{
    if (this.width <= 0 || this.height <= 0)
    {
        return false;
    }

    if (x >= this.x && x < this.x + this.width)
    {
        if (y >= this.y && y < this.y + this.height)
        {
            return true;
        }
    }

    return false;
};

/**
 * Pads the rectangle making it grow in all directions.
 *
 * @param {number} paddingX - The horizontal padding amount.
 * @param {number} paddingY - The vertical padding amount.
 */
Rectangle.prototype.pad = function pad (paddingX, paddingY)
{
    paddingX = paddingX || 0;
    paddingY = paddingY || ((paddingY !== 0) ? paddingX : 0);

    this.x -= paddingX;
    this.y -= paddingY;

    this.width += paddingX * 2;
    this.height += paddingY * 2;
};

/**
 * Fits this rectangle around the passed one.
 *
 * @param {PIXI.Rectangle} rectangle - The rectangle to fit.
 */
Rectangle.prototype.fit = function fit (rectangle)
{
    var x1 = Math.max(this.x, rectangle.x);
    var x2 = Math.min(this.x + this.width, rectangle.x + rectangle.width);
    var y1 = Math.max(this.y, rectangle.y);
    var y2 = Math.min(this.y + this.height, rectangle.y + rectangle.height);

    this.x = x1;
    this.width = Math.max(x2 - x1, 0);
    this.y = y1;
    this.height = Math.max(y2 - y1, 0);
};

/**
 * Enlarges rectangle that way its corners lie on grid
 *
 * @param {number} [resolution=1] resolution
 * @param {number} [eps=0.001] precision
 */
Rectangle.prototype.ceil = function ceil (resolution, eps)
{
        if ( resolution === void 0 ) resolution = 1;
        if ( eps === void 0 ) eps = 0.001;

    var x2 = Math.ceil((this.x + this.width - eps) * resolution) / resolution;
    var y2 = Math.ceil((this.y + this.height - eps) * resolution) / resolution;

    this.x = Math.floor((this.x + eps) * resolution) / resolution;
    this.y = Math.floor((this.y + eps) * resolution) / resolution;

    this.width = x2 - this.x;
    this.height = y2 - this.y;
};

/**
 * Enlarges this rectangle to include the passed rectangle.
 *
 * @param {PIXI.Rectangle} rectangle - The rectangle to include.
 */
Rectangle.prototype.enlarge = function enlarge (rectangle)
{
    var x1 = Math.min(this.x, rectangle.x);
    var x2 = Math.max(this.x + this.width, rectangle.x + rectangle.width);
    var y1 = Math.min(this.y, rectangle.y);
    var y2 = Math.max(this.y + this.height, rectangle.y + rectangle.height);

    this.x = x1;
    this.width = x2 - x1;
    this.y = y1;
    this.height = y2 - y1;
};

Object.defineProperties( Rectangle.prototype, prototypeAccessors$2 );
Object.defineProperties( Rectangle, staticAccessors$1 );

/**
 * The Circle object is used to help draw graphics and can also be used to specify a hit area for displayObjects.
 *
 * @class
 * @memberof PIXI
 */
var Circle = function Circle(x, y, radius)
{
    if ( x === void 0 ) x = 0;
    if ( y === void 0 ) y = 0;
    if ( radius === void 0 ) radius = 0;

    /**
     * @member {number}
     * @default 0
     */
    this.x = x;

    /**
     * @member {number}
     * @default 0
     */
    this.y = y;

    /**
     * @member {number}
     * @default 0
     */
    this.radius = radius;

    /**
     * The type of the object, mainly used to avoid `instanceof` checks
     *
     * @member {number}
     * @readOnly
     * @default PIXI.SHAPES.CIRC
     * @see PIXI.SHAPES
     */
    this.type = SHAPES.CIRC;
};

/**
 * Creates a clone of this Circle instance
 *
 * @return {PIXI.Circle} a copy of the Circle
 */
Circle.prototype.clone = function clone ()
{
    return new Circle(this.x, this.y, this.radius);
};

/**
 * Checks whether the x and y coordinates given are contained within this circle
 *
 * @param {number} x - The X coordinate of the point to test
 * @param {number} y - The Y coordinate of the point to test
 * @return {boolean} Whether the x/y coordinates are within this Circle
 */
Circle.prototype.contains = function contains (x, y)
{
    if (this.radius <= 0)
    {
        return false;
    }

    var r2 = this.radius * this.radius;
    var dx = (this.x - x);
    var dy = (this.y - y);

    dx *= dx;
    dy *= dy;

    return (dx + dy <= r2);
};

/**
* Returns the framing rectangle of the circle as a Rectangle object
*
* @return {PIXI.Rectangle} the framing rectangle
*/
Circle.prototype.getBounds = function getBounds ()
{
    return new Rectangle(this.x - this.radius, this.y - this.radius, this.radius * 2, this.radius * 2);
};

/**
 * The Ellipse object is used to help draw graphics and can also be used to specify a hit area for displayObjects.
 *
 * @class
 * @memberof PIXI
 */
var Ellipse = function Ellipse(x, y, halfWidth, halfHeight)
{
    if ( x === void 0 ) x = 0;
    if ( y === void 0 ) y = 0;
    if ( halfWidth === void 0 ) halfWidth = 0;
    if ( halfHeight === void 0 ) halfHeight = 0;

    /**
     * @member {number}
     * @default 0
     */
    this.x = x;

    /**
     * @member {number}
     * @default 0
     */
    this.y = y;

    /**
     * @member {number}
     * @default 0
     */
    this.width = halfWidth;

    /**
     * @member {number}
     * @default 0
     */
    this.height = halfHeight;

    /**
     * The type of the object, mainly used to avoid `instanceof` checks
     *
     * @member {number}
     * @readOnly
     * @default PIXI.SHAPES.ELIP
     * @see PIXI.SHAPES
     */
    this.type = SHAPES.ELIP;
};

/**
 * Creates a clone of this Ellipse instance
 *
 * @return {PIXI.Ellipse} a copy of the ellipse
 */
Ellipse.prototype.clone = function clone ()
{
    return new Ellipse(this.x, this.y, this.width, this.height);
};

/**
 * Checks whether the x and y coordinates given are contained within this ellipse
 *
 * @param {number} x - The X coordinate of the point to test
 * @param {number} y - The Y coordinate of the point to test
 * @return {boolean} Whether the x/y coords are within this ellipse
 */
Ellipse.prototype.contains = function contains (x, y)
{
    if (this.width <= 0 || this.height <= 0)
    {
        return false;
    }

    // normalize the coords to an ellipse with center 0,0
    var normx = ((x - this.x) / this.width);
    var normy = ((y - this.y) / this.height);

    normx *= normx;
    normy *= normy;

    return (normx + normy <= 1);
};

/**
 * Returns the framing rectangle of the ellipse as a Rectangle object
 *
 * @return {PIXI.Rectangle} the framing rectangle
 */
Ellipse.prototype.getBounds = function getBounds ()
{
    return new Rectangle(this.x - this.width, this.y - this.height, this.width, this.height);
};

/**
 * A class to define a shape via user defined co-orinates.
 *
 * @class
 * @memberof PIXI
 */
var Polygon = function Polygon()
{
    var points = [], len = arguments.length;
    while ( len-- ) points[ len ] = arguments[ len ];

    if (Array.isArray(points[0]))
    {
        points = points[0];
    }

    // if this is an array of points, convert it to a flat array of numbers
    if (points[0] instanceof Point)
    {
        var p = [];

        for (var i = 0, il = points.length; i < il; i++)
        {
            p.push(points[i].x, points[i].y);
        }

        points = p;
    }

    /**
     * An array of the points of this polygon
     *
     * @member {number[]}
     */
    this.points = points;

    /**
     * The type of the object, mainly used to avoid `instanceof` checks
     *
     * @member {number}
     * @readOnly
     * @default PIXI.SHAPES.POLY
     * @see PIXI.SHAPES
     */
    this.type = SHAPES.POLY;

    /**
     * `false` after moveTo, `true` after `closePath`. In all other cases it is `true`.
     * @member {boolean}
     * @default true
     */
    this.closeStroke = true;
};

/**
 * Creates a clone of this polygon
 *
 * @return {PIXI.Polygon} a copy of the polygon
 */
Polygon.prototype.clone = function clone ()
{
    var polygon = new Polygon(this.points.slice());

    polygon.closeStroke = this.closeStroke;

    return polygon;
};

/**
 * Checks whether the x and y coordinates passed to this function are contained within this polygon
 *
 * @param {number} x - The X coordinate of the point to test
 * @param {number} y - The Y coordinate of the point to test
 * @return {boolean} Whether the x/y coordinates are within this polygon
 */
Polygon.prototype.contains = function contains (x, y)
{
    var inside = false;

    // use some raycasting to test hits
    // https://github.com/substack/point-in-polygon/blob/master/index.js
    var length = this.points.length / 2;

    for (var i = 0, j = length - 1; i < length; j = i++)
    {
        var xi = this.points[i * 2];
        var yi = this.points[(i * 2) + 1];
        var xj = this.points[j * 2];
        var yj = this.points[(j * 2) + 1];
        var intersect = ((yi > y) !== (yj > y)) && (x < ((xj - xi) * ((y - yi) / (yj - yi))) + xi);

        if (intersect)
        {
            inside = !inside;
        }
    }

    return inside;
};

/**
 * The Rounded Rectangle object is an area that has nice rounded corners, as indicated by its
 * top-left corner point (x, y) and by its width and its height and its radius.
 *
 * @class
 * @memberof PIXI
 */
var RoundedRectangle = function RoundedRectangle(x, y, width, height, radius)
{
    if ( x === void 0 ) x = 0;
    if ( y === void 0 ) y = 0;
    if ( width === void 0 ) width = 0;
    if ( height === void 0 ) height = 0;
    if ( radius === void 0 ) radius = 20;

    /**
     * @member {number}
     * @default 0
     */
    this.x = x;

    /**
     * @member {number}
     * @default 0
     */
    this.y = y;

    /**
     * @member {number}
     * @default 0
     */
    this.width = width;

    /**
     * @member {number}
     * @default 0
     */
    this.height = height;

    /**
     * @member {number}
     * @default 20
     */
    this.radius = radius;

    /**
     * The type of the object, mainly used to avoid `instanceof` checks
     *
     * @member {number}
     * @readonly
     * @default PIXI.SHAPES.RREC
     * @see PIXI.SHAPES
     */
    this.type = SHAPES.RREC;
};

/**
 * Creates a clone of this Rounded Rectangle
 *
 * @return {PIXI.RoundedRectangle} a copy of the rounded rectangle
 */
RoundedRectangle.prototype.clone = function clone ()
{
    return new RoundedRectangle(this.x, this.y, this.width, this.height, this.radius);
};

/**
 * Checks whether the x and y coordinates given are contained within this Rounded Rectangle
 *
 * @param {number} x - The X coordinate of the point to test
 * @param {number} y - The Y coordinate of the point to test
 * @return {boolean} Whether the x/y coordinates are within this Rounded Rectangle
 */
RoundedRectangle.prototype.contains = function contains (x, y)
{
    if (this.width <= 0 || this.height <= 0)
    {
        return false;
    }
    if (x >= this.x && x <= this.x + this.width)
    {
        if (y >= this.y && y <= this.y + this.height)
        {
            if ((y >= this.y + this.radius && y <= this.y + this.height - this.radius)
            || (x >= this.x + this.radius && x <= this.x + this.width - this.radius))
            {
                return true;
            }
            var dx = x - (this.x + this.radius);
            var dy = y - (this.y + this.radius);
            var radius2 = this.radius * this.radius;

            if ((dx * dx) + (dy * dy) <= radius2)
            {
                return true;
            }
            dx = x - (this.x + this.width - this.radius);
            if ((dx * dx) + (dy * dy) <= radius2)
            {
                return true;
            }
            dy = y - (this.y + this.height - this.radius);
            if ((dx * dx) + (dy * dy) <= radius2)
            {
                return true;
            }
            dx = x - (this.x + this.radius);
            if ((dx * dx) + (dy * dy) <= radius2)
            {
                return true;
            }
        }
    }

    return false;
};

/**
 * Math classes and utilities mixed into PIXI namespace.
 *
 * @lends PIXI
 */

export { Circle, DEG_TO_RAD, Ellipse, GroupD8, Matrix, ObservablePoint, PI_2, Point, Polygon, RAD_TO_DEG, Rectangle, RoundedRectangle, SHAPES, Transform };
//# sourceMappingURL=math.es.js.map
