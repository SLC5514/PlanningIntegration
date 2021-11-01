/*!
 * @pixi/display - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/display is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
this.PIXI = this.PIXI || {};
var _pixi_display = (function (exports, settings, math, utils) {
    'use strict';

    /**
     * Sets the default value for the container property 'sortableChildren'.
     * If set to true, the container will sort its children by zIndex value
     * when updateTransform() is called, or manually if sortChildren() is called.
     *
     * This actually changes the order of elements in the array, so should be treated
     * as a basic solution that is not performant compared to other solutions,
     * such as @link https://github.com/pixijs/pixi-display
     *
     * Also be aware of that this may not work nicely with the addChildAt() function,
     * as the zIndex sorting may cause the child to automatically sorted to another position.
     *
     * @static
     * @constant
     * @name SORTABLE_CHILDREN
     * @memberof PIXI.settings
     * @type {boolean}
     * @default false
     */
    settings.settings.SORTABLE_CHILDREN = false;

    /**
     * 'Builder' pattern for bounds rectangles.
     *
     * This could be called an Axis-Aligned Bounding Box.
     * It is not an actual shape. It is a mutable thing; no 'EMPTY' or those kind of problems.
     *
     * @class
     * @memberof PIXI
     */
    var Bounds = function Bounds()
    {
        /**
         * @member {number}
         * @default 0
         */
        this.minX = Infinity;

        /**
         * @member {number}
         * @default 0
         */
        this.minY = Infinity;

        /**
         * @member {number}
         * @default 0
         */
        this.maxX = -Infinity;

        /**
         * @member {number}
         * @default 0
         */
        this.maxY = -Infinity;

        this.rect = null;
    };

    /**
     * Checks if bounds are empty.
     *
     * @return {boolean} True if empty.
     */
    Bounds.prototype.isEmpty = function isEmpty ()
    {
        return this.minX > this.maxX || this.minY > this.maxY;
    };

    /**
     * Clears the bounds and resets.
     *
     */
    Bounds.prototype.clear = function clear ()
    {
        this.updateID++;

        this.minX = Infinity;
        this.minY = Infinity;
        this.maxX = -Infinity;
        this.maxY = -Infinity;
    };

    /**
     * Can return Rectangle.EMPTY constant, either construct new rectangle, either use your rectangle
     * It is not guaranteed that it will return tempRect
     *
     * @param {PIXI.Rectangle} rect - temporary object will be used if AABB is not empty
     * @returns {PIXI.Rectangle} A rectangle of the bounds
     */
    Bounds.prototype.getRectangle = function getRectangle (rect)
    {
        if (this.minX > this.maxX || this.minY > this.maxY)
        {
            return math.Rectangle.EMPTY;
        }

        rect = rect || new math.Rectangle(0, 0, 1, 1);

        rect.x = this.minX;
        rect.y = this.minY;
        rect.width = this.maxX - this.minX;
        rect.height = this.maxY - this.minY;

        return rect;
    };

    /**
     * This function should be inlined when its possible.
     *
     * @param {PIXI.Point} point - The point to add.
     */
    Bounds.prototype.addPoint = function addPoint (point)
    {
        this.minX = Math.min(this.minX, point.x);
        this.maxX = Math.max(this.maxX, point.x);
        this.minY = Math.min(this.minY, point.y);
        this.maxY = Math.max(this.maxY, point.y);
    };

    /**
     * Adds a quad, not transformed
     *
     * @param {Float32Array} vertices - The verts to add.
     */
    Bounds.prototype.addQuad = function addQuad (vertices)
    {
        var minX = this.minX;
        var minY = this.minY;
        var maxX = this.maxX;
        var maxY = this.maxY;

        var x = vertices[0];
        var y = vertices[1];

        minX = x < minX ? x : minX;
        minY = y < minY ? y : minY;
        maxX = x > maxX ? x : maxX;
        maxY = y > maxY ? y : maxY;

        x = vertices[2];
        y = vertices[3];
        minX = x < minX ? x : minX;
        minY = y < minY ? y : minY;
        maxX = x > maxX ? x : maxX;
        maxY = y > maxY ? y : maxY;

        x = vertices[4];
        y = vertices[5];
        minX = x < minX ? x : minX;
        minY = y < minY ? y : minY;
        maxX = x > maxX ? x : maxX;
        maxY = y > maxY ? y : maxY;

        x = vertices[6];
        y = vertices[7];
        minX = x < minX ? x : minX;
        minY = y < minY ? y : minY;
        maxX = x > maxX ? x : maxX;
        maxY = y > maxY ? y : maxY;

        this.minX = minX;
        this.minY = minY;
        this.maxX = maxX;
        this.maxY = maxY;
    };

    /**
     * Adds sprite frame, transformed.
     *
     * @param {PIXI.Transform} transform - TODO
     * @param {number} x0 - TODO
     * @param {number} y0 - TODO
     * @param {number} x1 - TODO
     * @param {number} y1 - TODO
     */
    Bounds.prototype.addFrame = function addFrame (transform, x0, y0, x1, y1)
    {
        var matrix = transform.worldTransform;
        var a = matrix.a;
        var b = matrix.b;
        var c = matrix.c;
        var d = matrix.d;
        var tx = matrix.tx;
        var ty = matrix.ty;

        var minX = this.minX;
        var minY = this.minY;
        var maxX = this.maxX;
        var maxY = this.maxY;

        var x = (a * x0) + (c * y0) + tx;
        var y = (b * x0) + (d * y0) + ty;

        minX = x < minX ? x : minX;
        minY = y < minY ? y : minY;
        maxX = x > maxX ? x : maxX;
        maxY = y > maxY ? y : maxY;

        x = (a * x1) + (c * y0) + tx;
        y = (b * x1) + (d * y0) + ty;
        minX = x < minX ? x : minX;
        minY = y < minY ? y : minY;
        maxX = x > maxX ? x : maxX;
        maxY = y > maxY ? y : maxY;

        x = (a * x0) + (c * y1) + tx;
        y = (b * x0) + (d * y1) + ty;
        minX = x < minX ? x : minX;
        minY = y < minY ? y : minY;
        maxX = x > maxX ? x : maxX;
        maxY = y > maxY ? y : maxY;

        x = (a * x1) + (c * y1) + tx;
        y = (b * x1) + (d * y1) + ty;
        minX = x < minX ? x : minX;
        minY = y < minY ? y : minY;
        maxX = x > maxX ? x : maxX;
        maxY = y > maxY ? y : maxY;

        this.minX = minX;
        this.minY = minY;
        this.maxX = maxX;
        this.maxY = maxY;
    };

    /**
     * Adds screen vertices from array
     *
     * @param {Float32Array} vertexData - calculated vertices
     * @param {number} beginOffset - begin offset
     * @param {number} endOffset - end offset, excluded
     */
    Bounds.prototype.addVertexData = function addVertexData (vertexData, beginOffset, endOffset)
    {
        var minX = this.minX;
        var minY = this.minY;
        var maxX = this.maxX;
        var maxY = this.maxY;

        for (var i = beginOffset; i < endOffset; i += 2)
        {
            var x = vertexData[i];
            var y = vertexData[i + 1];

            minX = x < minX ? x : minX;
            minY = y < minY ? y : minY;
            maxX = x > maxX ? x : maxX;
            maxY = y > maxY ? y : maxY;
        }

        this.minX = minX;
        this.minY = minY;
        this.maxX = maxX;
        this.maxY = maxY;
    };

    /**
     * Add an array of mesh vertices
     *
     * @param {PIXI.Transform} transform - mesh transform
     * @param {Float32Array} vertices - mesh coordinates in array
     * @param {number} beginOffset - begin offset
     * @param {number} endOffset - end offset, excluded
     */
    Bounds.prototype.addVertices = function addVertices (transform, vertices, beginOffset, endOffset)
    {
        var matrix = transform.worldTransform;
        var a = matrix.a;
        var b = matrix.b;
        var c = matrix.c;
        var d = matrix.d;
        var tx = matrix.tx;
        var ty = matrix.ty;

        var minX = this.minX;
        var minY = this.minY;
        var maxX = this.maxX;
        var maxY = this.maxY;

        for (var i = beginOffset; i < endOffset; i += 2)
        {
            var rawX = vertices[i];
            var rawY = vertices[i + 1];
            var x = (a * rawX) + (c * rawY) + tx;
            var y = (d * rawY) + (b * rawX) + ty;

            minX = x < minX ? x : minX;
            minY = y < minY ? y : minY;
            maxX = x > maxX ? x : maxX;
            maxY = y > maxY ? y : maxY;
        }

        this.minX = minX;
        this.minY = minY;
        this.maxX = maxX;
        this.maxY = maxY;
    };

    /**
     * Adds other Bounds
     *
     * @param {PIXI.Bounds} bounds - TODO
     */
    Bounds.prototype.addBounds = function addBounds (bounds)
    {
        var minX = this.minX;
        var minY = this.minY;
        var maxX = this.maxX;
        var maxY = this.maxY;

        this.minX = bounds.minX < minX ? bounds.minX : minX;
        this.minY = bounds.minY < minY ? bounds.minY : minY;
        this.maxX = bounds.maxX > maxX ? bounds.maxX : maxX;
        this.maxY = bounds.maxY > maxY ? bounds.maxY : maxY;
    };

    /**
     * Adds other Bounds, masked with Bounds
     *
     * @param {PIXI.Bounds} bounds - TODO
     * @param {PIXI.Bounds} mask - TODO
     */
    Bounds.prototype.addBoundsMask = function addBoundsMask (bounds, mask)
    {
        var _minX = bounds.minX > mask.minX ? bounds.minX : mask.minX;
        var _minY = bounds.minY > mask.minY ? bounds.minY : mask.minY;
        var _maxX = bounds.maxX < mask.maxX ? bounds.maxX : mask.maxX;
        var _maxY = bounds.maxY < mask.maxY ? bounds.maxY : mask.maxY;

        if (_minX <= _maxX && _minY <= _maxY)
        {
            var minX = this.minX;
            var minY = this.minY;
            var maxX = this.maxX;
            var maxY = this.maxY;

            this.minX = _minX < minX ? _minX : minX;
            this.minY = _minY < minY ? _minY : minY;
            this.maxX = _maxX > maxX ? _maxX : maxX;
            this.maxY = _maxY > maxY ? _maxY : maxY;
        }
    };

    /**
     * Adds other Bounds, masked with Rectangle
     *
     * @param {PIXI.Bounds} bounds - TODO
     * @param {PIXI.Rectangle} area - TODO
     */
    Bounds.prototype.addBoundsArea = function addBoundsArea (bounds, area)
    {
        var _minX = bounds.minX > area.x ? bounds.minX : area.x;
        var _minY = bounds.minY > area.y ? bounds.minY : area.y;
        var _maxX = bounds.maxX < area.x + area.width ? bounds.maxX : (area.x + area.width);
        var _maxY = bounds.maxY < area.y + area.height ? bounds.maxY : (area.y + area.height);

        if (_minX <= _maxX && _minY <= _maxY)
        {
            var minX = this.minX;
            var minY = this.minY;
            var maxX = this.maxX;
            var maxY = this.maxY;

            this.minX = _minX < minX ? _minX : minX;
            this.minY = _minY < minY ? _minY : minY;
            this.maxX = _maxX > maxX ? _maxX : maxX;
            this.maxY = _maxY > maxY ? _maxY : maxY;
        }
    };

    // _tempDisplayObjectParent = new DisplayObject();

    /**
     * The base class for all objects that are rendered on the screen.
     *
     * This is an abstract class and should not be used on its own; rather it should be extended.
     *
     * @class
     * @extends PIXI.utils.EventEmitter
     * @memberof PIXI
     */
    var DisplayObject = /*@__PURE__*/(function (EventEmitter) {
        function DisplayObject()
        {
            EventEmitter.call(this);

            this.tempDisplayObjectParent = null;

            // TODO: need to create Transform from factory
            /**
             * World transform and local transform of this object.
             * This will become read-only later, please do not assign anything there unless you know what are you doing.
             *
             * @member {PIXI.Transform}
             */
            this.transform = new math.Transform();

            /**
             * The opacity of the object.
             *
             * @member {number}
             */
            this.alpha = 1;

            /**
             * The visibility of the object. If false the object will not be drawn, and
             * the updateTransform function will not be called.
             *
             * Only affects recursive calls from parent. You can ask for bounds or call updateTransform manually.
             *
             * @member {boolean}
             */
            this.visible = true;

            /**
             * Can this object be rendered, if false the object will not be drawn but the updateTransform
             * methods will still be called.
             *
             * Only affects recursive calls from parent. You can ask for bounds manually.
             *
             * @member {boolean}
             */
            this.renderable = true;

            /**
             * The display object container that contains this display object.
             *
             * @member {PIXI.Container}
             * @readonly
             */
            this.parent = null;

            /**
             * The multiplied alpha of the displayObject.
             *
             * @member {number}
             * @readonly
             */
            this.worldAlpha = 1;

            /**
             * Which index in the children array the display component was before the previous zIndex sort.
             * Used by containers to help sort objects with the same zIndex, by using previous array index as the decider.
             *
             * @member {number}
             * @protected
             */
            this._lastSortedIndex = 0;

            /**
             * The zIndex of the displayObject.
             * A higher value will mean it will be rendered on top of other displayObjects within the same container.
             *
             * @member {number}
             * @protected
             */
            this._zIndex = 0;

            /**
             * The area the filter is applied to. This is used as more of an optimization
             * rather than figuring out the dimensions of the displayObject each frame you can set this rectangle.
             *
             * Also works as an interaction mask.
             *
             * @member {?PIXI.Rectangle}
             */
            this.filterArea = null;

            /**
             * Sets the filters for the displayObject.
             * * IMPORTANT: This is a WebGL only feature and will be ignored by the canvas renderer.
             * To remove filters simply set this property to `'null'`.
             *
             * @member {?PIXI.Filter[]}
             */
            this.filters = null;
            this._enabledFilters = null;

            /**
             * The bounds object, this is used to calculate and store the bounds of the displayObject.
             *
             * @member {PIXI.Bounds}
             * @protected
             */
            this._bounds = new Bounds();
            this._boundsID = 0;
            this._lastBoundsID = -1;
            this._boundsRect = null;
            this._localBoundsRect = null;

            /**
             * The original, cached mask of the object.
             *
             * @member {PIXI.Graphics|PIXI.Sprite}
             * @protected
             */
            this._mask = null;

            /**
             * Fired when this DisplayObject is added to a Container.
             *
             * @event PIXI.DisplayObject#added
             * @param {PIXI.Container} container - The container added to.
             */

            /**
             * Fired when this DisplayObject is removed from a Container.
             *
             * @event PIXI.DisplayObject#removed
             * @param {PIXI.Container} container - The container removed from.
             */

            /**
             * If the object has been destroyed via destroy(). If true, it should not be used.
             *
             * @member {boolean}
             * @protected
             */
            this._destroyed = false;

            /**
             * used to fast check if a sprite is.. a sprite!
             * @member {boolean}
             */
            this.isSprite = false;
        }

        if ( EventEmitter ) DisplayObject.__proto__ = EventEmitter;
        DisplayObject.prototype = Object.create( EventEmitter && EventEmitter.prototype );
        DisplayObject.prototype.constructor = DisplayObject;

        var prototypeAccessors = { _tempDisplayObjectParent: { configurable: true },x: { configurable: true },y: { configurable: true },worldTransform: { configurable: true },localTransform: { configurable: true },position: { configurable: true },scale: { configurable: true },pivot: { configurable: true },skew: { configurable: true },rotation: { configurable: true },angle: { configurable: true },zIndex: { configurable: true },worldVisible: { configurable: true },mask: { configurable: true } };

        /**
         * @protected
         * @member {PIXI.DisplayObject}
         */
        DisplayObject.mixin = function mixin (source)
        {
            // in ES8/ES2017, this would be really easy:
            // Object.defineProperties(target, Object.getOwnPropertyDescriptors(source));

            // get all the enumerable property keys
            var keys = Object.keys(source);

            // loop through properties
            for (var i = 0; i < keys.length; ++i)
            {
                var propertyName = keys[i];

                // Set the property using the property descriptor - this works for accessors and normal value properties
                Object.defineProperty(
                    DisplayObject.prototype,
                    propertyName,
                    Object.getOwnPropertyDescriptor(source, propertyName)
                );
            }
        };

        prototypeAccessors._tempDisplayObjectParent.get = function ()
        {
            if (this.tempDisplayObjectParent === null)
            {
                this.tempDisplayObjectParent = new DisplayObject();
            }

            return this.tempDisplayObjectParent;
        };

        /**
         * Updates the object transform for rendering.
         *
         * TODO - Optimization pass!
         */
        DisplayObject.prototype.updateTransform = function updateTransform ()
        {
            this.transform.updateTransform(this.parent.transform);
            // multiply the alphas..
            this.worldAlpha = this.alpha * this.parent.worldAlpha;

            this._bounds.updateID++;
        };

        /**
         * Recursively updates transform of all objects from the root to this one
         * internal function for toLocal()
         */
        DisplayObject.prototype._recursivePostUpdateTransform = function _recursivePostUpdateTransform ()
        {
            if (this.parent)
            {
                this.parent._recursivePostUpdateTransform();
                this.transform.updateTransform(this.parent.transform);
            }
            else
            {
                this.transform.updateTransform(this._tempDisplayObjectParent.transform);
            }
        };

        /**
         * Retrieves the bounds of the displayObject as a rectangle object.
         *
         * @param {boolean} [skipUpdate] - Setting to `true` will stop the transforms of the scene graph from
         *  being updated. This means the calculation returned MAY be out of date BUT will give you a
         *  nice performance boost.
         * @param {PIXI.Rectangle} [rect] - Optional rectangle to store the result of the bounds calculation.
         * @return {PIXI.Rectangle} The rectangular bounding area.
         */
        DisplayObject.prototype.getBounds = function getBounds (skipUpdate, rect)
        {
            if (!skipUpdate)
            {
                if (!this.parent)
                {
                    this.parent = this._tempDisplayObjectParent;
                    this.updateTransform();
                    this.parent = null;
                }
                else
                {
                    this._recursivePostUpdateTransform();
                    this.updateTransform();
                }
            }

            if (this._boundsID !== this._lastBoundsID)
            {
                this.calculateBounds();
            }

            if (!rect)
            {
                if (!this._boundsRect)
                {
                    this._boundsRect = new math.Rectangle();
                }

                rect = this._boundsRect;
            }

            return this._bounds.getRectangle(rect);
        };

        /**
         * Retrieves the local bounds of the displayObject as a rectangle object.
         *
         * @param {PIXI.Rectangle} [rect] - Optional rectangle to store the result of the bounds calculation.
         * @return {PIXI.Rectangle} The rectangular bounding area.
         */
        DisplayObject.prototype.getLocalBounds = function getLocalBounds (rect)
        {
            var transformRef = this.transform;
            var parentRef = this.parent;

            this.parent = null;
            this.transform = this._tempDisplayObjectParent.transform;

            if (!rect)
            {
                if (!this._localBoundsRect)
                {
                    this._localBoundsRect = new math.Rectangle();
                }

                rect = this._localBoundsRect;
            }

            var bounds = this.getBounds(false, rect);

            this.parent = parentRef;
            this.transform = transformRef;

            return bounds;
        };

        /**
         * Calculates the global position of the display object.
         *
         * @param {PIXI.IPoint} position - The world origin to calculate from.
         * @param {PIXI.IPoint} [point] - A Point object in which to store the value, optional
         *  (otherwise will create a new Point).
         * @param {boolean} [skipUpdate=false] - Should we skip the update transform.
         * @return {PIXI.IPoint} A point object representing the position of this object.
         */
        DisplayObject.prototype.toGlobal = function toGlobal (position, point, skipUpdate)
        {
            if ( skipUpdate === void 0 ) skipUpdate = false;

            if (!skipUpdate)
            {
                this._recursivePostUpdateTransform();

                // this parent check is for just in case the item is a root object.
                // If it is we need to give it a temporary parent so that displayObjectUpdateTransform works correctly
                // this is mainly to avoid a parent check in the main loop. Every little helps for performance :)
                if (!this.parent)
                {
                    this.parent = this._tempDisplayObjectParent;
                    this.displayObjectUpdateTransform();
                    this.parent = null;
                }
                else
                {
                    this.displayObjectUpdateTransform();
                }
            }

            // don't need to update the lot
            return this.worldTransform.apply(position, point);
        };

        /**
         * Calculates the local position of the display object relative to another point.
         *
         * @param {PIXI.IPoint} position - The world origin to calculate from.
         * @param {PIXI.DisplayObject} [from] - The DisplayObject to calculate the global position from.
         * @param {PIXI.IPoint} [point] - A Point object in which to store the value, optional
         *  (otherwise will create a new Point).
         * @param {boolean} [skipUpdate=false] - Should we skip the update transform
         * @return {PIXI.IPoint} A point object representing the position of this object
         */
        DisplayObject.prototype.toLocal = function toLocal (position, from, point, skipUpdate)
        {
            if (from)
            {
                position = from.toGlobal(position, point, skipUpdate);
            }

            if (!skipUpdate)
            {
                this._recursivePostUpdateTransform();

                // this parent check is for just in case the item is a root object.
                // If it is we need to give it a temporary parent so that displayObjectUpdateTransform works correctly
                // this is mainly to avoid a parent check in the main loop. Every little helps for performance :)
                if (!this.parent)
                {
                    this.parent = this._tempDisplayObjectParent;
                    this.displayObjectUpdateTransform();
                    this.parent = null;
                }
                else
                {
                    this.displayObjectUpdateTransform();
                }
            }

            // simply apply the matrix..
            return this.worldTransform.applyInverse(position, point);
        };

        /**
         * Renders the object using the WebGL renderer.
         *
         * @param {PIXI.Renderer} renderer - The renderer.
         */
        DisplayObject.prototype.render = function render (renderer) // eslint-disable-line no-unused-vars
        {
            // OVERWRITE;
        };

        /**
         * Set the parent Container of this DisplayObject.
         *
         * @param {PIXI.Container} container - The Container to add this DisplayObject to.
         * @return {PIXI.Container} The Container that this DisplayObject was added to.
         */
        DisplayObject.prototype.setParent = function setParent (container)
        {
            if (!container || !container.addChild)
            {
                throw new Error('setParent: Argument must be a Container');
            }

            container.addChild(this);

            return container;
        };

        /**
         * Convenience function to set the position, scale, skew and pivot at once.
         *
         * @param {number} [x=0] - The X position
         * @param {number} [y=0] - The Y position
         * @param {number} [scaleX=1] - The X scale value
         * @param {number} [scaleY=1] - The Y scale value
         * @param {number} [rotation=0] - The rotation
         * @param {number} [skewX=0] - The X skew value
         * @param {number} [skewY=0] - The Y skew value
         * @param {number} [pivotX=0] - The X pivot value
         * @param {number} [pivotY=0] - The Y pivot value
         * @return {PIXI.DisplayObject} The DisplayObject instance
         */
        DisplayObject.prototype.setTransform = function setTransform (x, y, scaleX, scaleY, rotation, skewX, skewY, pivotX, pivotY)
        {
            if ( x === void 0 ) x = 0;
            if ( y === void 0 ) y = 0;
            if ( scaleX === void 0 ) scaleX = 1;
            if ( scaleY === void 0 ) scaleY = 1;
            if ( rotation === void 0 ) rotation = 0;
            if ( skewX === void 0 ) skewX = 0;
            if ( skewY === void 0 ) skewY = 0;
            if ( pivotX === void 0 ) pivotX = 0;
            if ( pivotY === void 0 ) pivotY = 0;

            this.position.x = x;
            this.position.y = y;
            this.scale.x = !scaleX ? 1 : scaleX;
            this.scale.y = !scaleY ? 1 : scaleY;
            this.rotation = rotation;
            this.skew.x = skewX;
            this.skew.y = skewY;
            this.pivot.x = pivotX;
            this.pivot.y = pivotY;

            return this;
        };

        /**
         * Base destroy method for generic display objects. This will automatically
         * remove the display object from its parent Container as well as remove
         * all current event listeners and internal references. Do not use a DisplayObject
         * after calling `destroy()`.
         *
         */
        DisplayObject.prototype.destroy = function destroy ()
        {
            this.removeAllListeners();
            if (this.parent)
            {
                this.parent.removeChild(this);
            }
            this.transform = null;

            this.parent = null;

            this._bounds = null;
            this._currentBounds = null;
            this._mask = null;

            this.filterArea = null;

            this.interactive = false;
            this.interactiveChildren = false;

            this._destroyed = true;
        };

        /**
         * The position of the displayObject on the x axis relative to the local coordinates of the parent.
         * An alias to position.x
         *
         * @member {number}
         */
        prototypeAccessors.x.get = function ()
        {
            return this.position.x;
        };

        prototypeAccessors.x.set = function (value) // eslint-disable-line require-jsdoc
        {
            this.transform.position.x = value;
        };

        /**
         * The position of the displayObject on the y axis relative to the local coordinates of the parent.
         * An alias to position.y
         *
         * @member {number}
         */
        prototypeAccessors.y.get = function ()
        {
            return this.position.y;
        };

        prototypeAccessors.y.set = function (value) // eslint-disable-line require-jsdoc
        {
            this.transform.position.y = value;
        };

        /**
         * Current transform of the object based on world (parent) factors.
         *
         * @member {PIXI.Matrix}
         * @readonly
         */
        prototypeAccessors.worldTransform.get = function ()
        {
            return this.transform.worldTransform;
        };

        /**
         * Current transform of the object based on local factors: position, scale, other stuff.
         *
         * @member {PIXI.Matrix}
         * @readonly
         */
        prototypeAccessors.localTransform.get = function ()
        {
            return this.transform.localTransform;
        };

        /**
         * The coordinate of the object relative to the local coordinates of the parent.
         * Assignment by value since pixi-v4.
         *
         * @member {PIXI.IPoint}
         */
        prototypeAccessors.position.get = function ()
        {
            return this.transform.position;
        };

        prototypeAccessors.position.set = function (value) // eslint-disable-line require-jsdoc
        {
            this.transform.position.copyFrom(value);
        };

        /**
         * The scale factor of the object.
         * Assignment by value since pixi-v4.
         *
         * @member {PIXI.IPoint}
         */
        prototypeAccessors.scale.get = function ()
        {
            return this.transform.scale;
        };

        prototypeAccessors.scale.set = function (value) // eslint-disable-line require-jsdoc
        {
            this.transform.scale.copyFrom(value);
        };

        /**
         * The pivot point of the displayObject that it rotates around.
         * Assignment by value since pixi-v4.
         *
         * @member {PIXI.IPoint}
         */
        prototypeAccessors.pivot.get = function ()
        {
            return this.transform.pivot;
        };

        prototypeAccessors.pivot.set = function (value) // eslint-disable-line require-jsdoc
        {
            this.transform.pivot.copyFrom(value);
        };

        /**
         * The skew factor for the object in radians.
         * Assignment by value since pixi-v4.
         *
         * @member {PIXI.ObservablePoint}
         */
        prototypeAccessors.skew.get = function ()
        {
            return this.transform.skew;
        };

        prototypeAccessors.skew.set = function (value) // eslint-disable-line require-jsdoc
        {
            this.transform.skew.copyFrom(value);
        };

        /**
         * The rotation of the object in radians.
         * 'rotation' and 'angle' have the same effect on a display object; rotation is in radians, angle is in degrees.
         *
         * @member {number}
         */
        prototypeAccessors.rotation.get = function ()
        {
            return this.transform.rotation;
        };

        prototypeAccessors.rotation.set = function (value) // eslint-disable-line require-jsdoc
        {
            this.transform.rotation = value;
        };

        /**
         * The angle of the object in degrees.
         * 'rotation' and 'angle' have the same effect on a display object; rotation is in radians, angle is in degrees.
         *
         * @member {number}
         */
        prototypeAccessors.angle.get = function ()
        {
            return this.transform.rotation * math.RAD_TO_DEG;
        };

        prototypeAccessors.angle.set = function (value) // eslint-disable-line require-jsdoc
        {
            this.transform.rotation = value * math.DEG_TO_RAD;
        };

        /**
         * The zIndex of the displayObject.
         * If a container has the sortableChildren property set to true, children will be automatically
         * sorted by zIndex value; a higher value will mean it will be moved towards the end of the array,
         * and thus rendered on top of other displayObjects within the same container.
         *
         * @member {number}
         */
        prototypeAccessors.zIndex.get = function ()
        {
            return this._zIndex;
        };

        prototypeAccessors.zIndex.set = function (value) // eslint-disable-line require-jsdoc
        {
            this._zIndex = value;
            if (this.parent)
            {
                this.parent.sortDirty = true;
            }
        };

        /**
         * Indicates if the object is globally visible.
         *
         * @member {boolean}
         * @readonly
         */
        prototypeAccessors.worldVisible.get = function ()
        {
            var item = this;

            do
            {
                if (!item.visible)
                {
                    return false;
                }

                item = item.parent;
            } while (item);

            return true;
        };

        /**
         * Sets a mask for the displayObject. A mask is an object that limits the visibility of an
         * object to the shape of the mask applied to it. In PixiJS a regular mask must be a
         * {@link PIXI.Graphics} or a {@link PIXI.Sprite} object. This allows for much faster masking in canvas as it
         * utilities shape clipping. To remove a mask, set this property to `null`.
         *
         * For sprite mask both alpha and red channel are used. Black mask is the same as transparent mask.
         * @example
         * const graphics = new PIXI.Graphics();
         * graphics.beginFill(0xFF3300);
         * graphics.drawRect(50, 250, 100, 100);
         * graphics.endFill();
         *
         * const sprite = new PIXI.Sprite(texture);
         * sprite.mask = graphics;
         * @todo At the moment, PIXI.CanvasRenderer doesn't support PIXI.Sprite as mask.
         *
         * @member {PIXI.Graphics|PIXI.Sprite}
         */
        prototypeAccessors.mask.get = function ()
        {
            return this._mask;
        };

        prototypeAccessors.mask.set = function (value) // eslint-disable-line require-jsdoc
        {
            if (this._mask)
            {
                this._mask.renderable = true;
                this._mask.isMask = false;
            }

            this._mask = value;

            if (this._mask)
            {
                this._mask.renderable = false;
                this._mask.isMask = true;
            }
        };

        Object.defineProperties( DisplayObject.prototype, prototypeAccessors );

        return DisplayObject;
    }(utils.EventEmitter));

    // performance increase to avoid using call.. (10x faster)
    DisplayObject.prototype.displayObjectUpdateTransform = DisplayObject.prototype.updateTransform;

    function sortChildren(a, b)
    {
        if (a.zIndex === b.zIndex)
        {
            return a._lastSortedIndex - b._lastSortedIndex;
        }

        return a.zIndex - b.zIndex;
    }

    /**
     * A Container represents a collection of display objects.
     *
     * It is the base class of all display objects that act as a container for other objects (like Sprites).
     *
     *```js
     * let container = new PIXI.Container();
     * container.addChild(sprite);
     * ```
     *
     * @class
     * @extends PIXI.DisplayObject
     * @memberof PIXI
     */
    var Container = /*@__PURE__*/(function (DisplayObject) {
        function Container()
        {
            DisplayObject.call(this);

            /**
             * The array of children of this container.
             *
             * @member {PIXI.DisplayObject[]}
             * @readonly
             */
            this.children = [];

            /**
             * If set to true, the container will sort its children by zIndex value
             * when updateTransform() is called, or manually if sortChildren() is called.
             *
             * This actually changes the order of elements in the array, so should be treated
             * as a basic solution that is not performant compared to other solutions,
             * such as @link https://github.com/pixijs/pixi-display
             *
             * Also be aware of that this may not work nicely with the addChildAt() function,
             * as the zIndex sorting may cause the child to automatically sorted to another position.
             *
             * @see PIXI.settings.SORTABLE_CHILDREN
             *
             * @member {boolean}
             */
            this.sortableChildren = settings.settings.SORTABLE_CHILDREN;

            /**
             * Should children be sorted by zIndex at the next updateTransform call.
             * Will get automatically set to true if a new child is added, or if a child's zIndex changes.
             *
             * @member {boolean}
             */
            this.sortDirty = false;

            /**
             * Fired when a DisplayObject is added to this Container.
             *
             * @event PIXI.Container#childAdded
             * @param {PIXI.DisplayObject} child - The child added to the Container.
             * @param {PIXI.Container} container - The container that added the child.
             * @param {number} index - The children's index of the added child.
             */

            /**
             * Fired when a DisplayObject is removed from this Container.
             *
             * @event PIXI.DisplayObject#removedFrom
             * @param {PIXI.DisplayObject} child - The child removed from the Container.
             * @param {PIXI.Container} container - The container that removed removed the child.
             * @param {number} index - The former children's index of the removed child
             */
        }

        if ( DisplayObject ) Container.__proto__ = DisplayObject;
        Container.prototype = Object.create( DisplayObject && DisplayObject.prototype );
        Container.prototype.constructor = Container;

        var prototypeAccessors = { width: { configurable: true },height: { configurable: true } };

        /**
         * Overridable method that can be used by Container subclasses whenever the children array is modified
         *
         * @protected
         */
        Container.prototype.onChildrenChange = function onChildrenChange ()
        {
            /* empty */
        };

        /**
         * Adds one or more children to the container.
         *
         * Multiple items can be added like so: `myContainer.addChild(thingOne, thingTwo, thingThree)`
         *
         * @param {...PIXI.DisplayObject} child - The DisplayObject(s) to add to the container
         * @return {PIXI.DisplayObject} The first child that was added.
         */
        Container.prototype.addChild = function addChild (child)
        {
            var arguments$1 = arguments;

            var argumentsLength = arguments.length;

            // if there is only one argument we can bypass looping through the them
            if (argumentsLength > 1)
            {
                // loop through the arguments property and add all children
                // use it the right way (.length and [i]) so that this function can still be optimized by JS runtimes
                for (var i = 0; i < argumentsLength; i++)
                {
                    this.addChild(arguments$1[i]);
                }
            }
            else
            {
                // if the child has a parent then lets remove it as PixiJS objects can only exist in one place
                if (child.parent)
                {
                    child.parent.removeChild(child);
                }

                child.parent = this;
                this.sortDirty = true;

                // ensure child transform will be recalculated
                child.transform._parentID = -1;

                this.children.push(child);

                // ensure bounds will be recalculated
                this._boundsID++;

                // TODO - lets either do all callbacks or all events.. not both!
                this.onChildrenChange(this.children.length - 1);
                this.emit('childAdded', child, this, this.children.length - 1);
                child.emit('added', this);
            }

            return child;
        };

        /**
         * Adds a child to the container at a specified index. If the index is out of bounds an error will be thrown
         *
         * @param {PIXI.DisplayObject} child - The child to add
         * @param {number} index - The index to place the child in
         * @return {PIXI.DisplayObject} The child that was added.
         */
        Container.prototype.addChildAt = function addChildAt (child, index)
        {
            if (index < 0 || index > this.children.length)
            {
                throw new Error((child + "addChildAt: The index " + index + " supplied is out of bounds " + (this.children.length)));
            }

            if (child.parent)
            {
                child.parent.removeChild(child);
            }

            child.parent = this;
            this.sortDirty = true;

            // ensure child transform will be recalculated
            child.transform._parentID = -1;

            this.children.splice(index, 0, child);

            // ensure bounds will be recalculated
            this._boundsID++;

            // TODO - lets either do all callbacks or all events.. not both!
            this.onChildrenChange(index);
            child.emit('added', this);
            this.emit('childAdded', child, this, index);

            return child;
        };

        /**
         * Swaps the position of 2 Display Objects within this container.
         *
         * @param {PIXI.DisplayObject} child - First display object to swap
         * @param {PIXI.DisplayObject} child2 - Second display object to swap
         */
        Container.prototype.swapChildren = function swapChildren (child, child2)
        {
            if (child === child2)
            {
                return;
            }

            var index1 = this.getChildIndex(child);
            var index2 = this.getChildIndex(child2);

            this.children[index1] = child2;
            this.children[index2] = child;
            this.onChildrenChange(index1 < index2 ? index1 : index2);
        };

        /**
         * Returns the index position of a child DisplayObject instance
         *
         * @param {PIXI.DisplayObject} child - The DisplayObject instance to identify
         * @return {number} The index position of the child display object to identify
         */
        Container.prototype.getChildIndex = function getChildIndex (child)
        {
            var index = this.children.indexOf(child);

            if (index === -1)
            {
                throw new Error('The supplied DisplayObject must be a child of the caller');
            }

            return index;
        };

        /**
         * Changes the position of an existing child in the display object container
         *
         * @param {PIXI.DisplayObject} child - The child DisplayObject instance for which you want to change the index number
         * @param {number} index - The resulting index number for the child display object
         */
        Container.prototype.setChildIndex = function setChildIndex (child, index)
        {
            if (index < 0 || index >= this.children.length)
            {
                throw new Error(("The index " + index + " supplied is out of bounds " + (this.children.length)));
            }

            var currentIndex = this.getChildIndex(child);

            utils.removeItems(this.children, currentIndex, 1); // remove from old position
            this.children.splice(index, 0, child); // add at new position

            this.onChildrenChange(index);
        };

        /**
         * Returns the child at the specified index
         *
         * @param {number} index - The index to get the child at
         * @return {PIXI.DisplayObject} The child at the given index, if any.
         */
        Container.prototype.getChildAt = function getChildAt (index)
        {
            if (index < 0 || index >= this.children.length)
            {
                throw new Error(("getChildAt: Index (" + index + ") does not exist."));
            }

            return this.children[index];
        };

        /**
         * Removes one or more children from the container.
         *
         * @param {...PIXI.DisplayObject} child - The DisplayObject(s) to remove
         * @return {PIXI.DisplayObject} The first child that was removed.
         */
        Container.prototype.removeChild = function removeChild (child)
        {
            var arguments$1 = arguments;

            var argumentsLength = arguments.length;

            // if there is only one argument we can bypass looping through the them
            if (argumentsLength > 1)
            {
                // loop through the arguments property and add all children
                // use it the right way (.length and [i]) so that this function can still be optimized by JS runtimes
                for (var i = 0; i < argumentsLength; i++)
                {
                    this.removeChild(arguments$1[i]);
                }
            }
            else
            {
                var index = this.children.indexOf(child);

                if (index === -1) { return null; }

                child.parent = null;
                // ensure child transform will be recalculated
                child.transform._parentID = -1;
                utils.removeItems(this.children, index, 1);

                // ensure bounds will be recalculated
                this._boundsID++;

                // TODO - lets either do all callbacks or all events.. not both!
                this.onChildrenChange(index);
                child.emit('removed', this);
                this.emit('childRemoved', child, this, index);
            }

            return child;
        };

        /**
         * Removes a child from the specified index position.
         *
         * @param {number} index - The index to get the child from
         * @return {PIXI.DisplayObject} The child that was removed.
         */
        Container.prototype.removeChildAt = function removeChildAt (index)
        {
            var child = this.getChildAt(index);

            // ensure child transform will be recalculated..
            child.parent = null;
            child.transform._parentID = -1;
            utils.removeItems(this.children, index, 1);

            // ensure bounds will be recalculated
            this._boundsID++;

            // TODO - lets either do all callbacks or all events.. not both!
            this.onChildrenChange(index);
            child.emit('removed', this);
            this.emit('childRemoved', child, this, index);

            return child;
        };

        /**
         * Removes all children from this container that are within the begin and end indexes.
         *
         * @param {number} [beginIndex=0] - The beginning position.
         * @param {number} [endIndex=this.children.length] - The ending position. Default value is size of the container.
         * @returns {DisplayObject[]} List of removed children
         */
        Container.prototype.removeChildren = function removeChildren (beginIndex, endIndex)
        {
            if ( beginIndex === void 0 ) beginIndex = 0;

            var begin = beginIndex;
            var end = typeof endIndex === 'number' ? endIndex : this.children.length;
            var range = end - begin;
            var removed;

            if (range > 0 && range <= end)
            {
                removed = this.children.splice(begin, range);

                for (var i = 0; i < removed.length; ++i)
                {
                    removed[i].parent = null;
                    if (removed[i].transform)
                    {
                        removed[i].transform._parentID = -1;
                    }
                }

                this._boundsID++;

                this.onChildrenChange(beginIndex);

                for (var i$1 = 0; i$1 < removed.length; ++i$1)
                {
                    removed[i$1].emit('removed', this);
                    this.emit('childRemoved', removed[i$1], this, i$1);
                }

                return removed;
            }
            else if (range === 0 && this.children.length === 0)
            {
                return [];
            }

            throw new RangeError('removeChildren: numeric values are outside the acceptable range.');
        };

        /**
         * Sorts children by zIndex. Previous order is mantained for 2 children with the same zIndex.
         */
        Container.prototype.sortChildren = function sortChildren$1 ()
        {
            var sortRequired = false;

            for (var i = 0, j = this.children.length; i < j; ++i)
            {
                var child = this.children[i];

                child._lastSortedIndex = i;

                if (!sortRequired && child.zIndex !== 0)
                {
                    sortRequired = true;
                }
            }

            if (sortRequired && this.children.length > 1)
            {
                this.children.sort(sortChildren);
            }

            this.sortDirty = false;
        };

        /**
         * Updates the transform on all children of this container for rendering
         */
        Container.prototype.updateTransform = function updateTransform ()
        {
            if (this.sortableChildren && this.sortDirty)
            {
                this.sortChildren();
            }

            this._boundsID++;

            this.transform.updateTransform(this.parent.transform);

            // TODO: check render flags, how to process stuff here
            this.worldAlpha = this.alpha * this.parent.worldAlpha;

            for (var i = 0, j = this.children.length; i < j; ++i)
            {
                var child = this.children[i];

                if (child.visible)
                {
                    child.updateTransform();
                }
            }
        };

        /**
         * Recalculates the bounds of the container.
         *
         */
        Container.prototype.calculateBounds = function calculateBounds ()
        {
            this._bounds.clear();

            this._calculateBounds();

            for (var i = 0; i < this.children.length; i++)
            {
                var child = this.children[i];

                if (!child.visible || !child.renderable)
                {
                    continue;
                }

                child.calculateBounds();

                // TODO: filter+mask, need to mask both somehow
                if (child._mask)
                {
                    child._mask.calculateBounds();
                    this._bounds.addBoundsMask(child._bounds, child._mask._bounds);
                }
                else if (child.filterArea)
                {
                    this._bounds.addBoundsArea(child._bounds, child.filterArea);
                }
                else
                {
                    this._bounds.addBounds(child._bounds);
                }
            }

            this._lastBoundsID = this._boundsID;
        };

        /**
         * Recalculates the bounds of the object. Override this to
         * calculate the bounds of the specific object (not including children).
         *
         * @protected
         */
        Container.prototype._calculateBounds = function _calculateBounds ()
        {
            // FILL IN//
        };

        /**
         * Renders the object using the WebGL renderer
         *
         * @param {PIXI.Renderer} renderer - The renderer
         */
        Container.prototype.render = function render (renderer)
        {
            // if the object is not visible or the alpha is 0 then no need to render this element
            if (!this.visible || this.worldAlpha <= 0 || !this.renderable)
            {
                return;
            }

            // do a quick check to see if this element has a mask or a filter.
            if (this._mask || this.filters)
            {
                this.renderAdvanced(renderer);
            }
            else
            {
                this._render(renderer);

                // simple render children!
                for (var i = 0, j = this.children.length; i < j; ++i)
                {
                    this.children[i].render(renderer);
                }
            }
        };

        /**
         * Render the object using the WebGL renderer and advanced features.
         *
         * @protected
         * @param {PIXI.Renderer} renderer - The renderer
         */
        Container.prototype.renderAdvanced = function renderAdvanced (renderer)
        {
            renderer.batch.flush();

            var filters = this.filters;
            var mask = this._mask;

            // push filter first as we need to ensure the stencil buffer is correct for any masking
            if (filters)
            {
                if (!this._enabledFilters)
                {
                    this._enabledFilters = [];
                }

                this._enabledFilters.length = 0;

                for (var i = 0; i < filters.length; i++)
                {
                    if (filters[i].enabled)
                    {
                        this._enabledFilters.push(filters[i]);
                    }
                }

                if (this._enabledFilters.length)
                {
                    renderer.filter.push(this, this._enabledFilters);
                }
            }

            if (mask)
            {
                renderer.mask.push(this, this._mask);
            }

            // add this object to the batch, only rendered if it has a texture.
            this._render(renderer);

            // now loop through the children and make sure they get rendered
            for (var i$1 = 0, j = this.children.length; i$1 < j; i$1++)
            {
                this.children[i$1].render(renderer);
            }

            renderer.batch.flush();

            if (mask)
            {
                renderer.mask.pop(this, this._mask);
            }

            if (filters && this._enabledFilters && this._enabledFilters.length)
            {
                renderer.filter.pop();
            }
        };

        /**
         * To be overridden by the subclasses.
         *
         * @protected
         * @param {PIXI.Renderer} renderer - The renderer
         */
        Container.prototype._render = function _render (renderer) // eslint-disable-line no-unused-vars
        {
            // this is where content itself gets rendered...
        };

        /**
         * Removes all internal references and listeners as well as removes children from the display list.
         * Do not use a Container after calling `destroy`.
         *
         * @param {object|boolean} [options] - Options parameter. A boolean will act as if all options
         *  have been set to that value
         * @param {boolean} [options.children=false] - if set to true, all the children will have their destroy
         *  method called as well. 'options' will be passed on to those calls.
         * @param {boolean} [options.texture=false] - Only used for child Sprites if options.children is set to true
         *  Should it destroy the texture of the child sprite
         * @param {boolean} [options.baseTexture=false] - Only used for child Sprites if options.children is set to true
         *  Should it destroy the base texture of the child sprite
         */
        Container.prototype.destroy = function destroy (options)
        {
            DisplayObject.prototype.destroy.call(this);

            this.sortDirty = false;

            var destroyChildren = typeof options === 'boolean' ? options : options && options.children;

            var oldChildren = this.removeChildren(0, this.children.length);

            if (destroyChildren)
            {
                for (var i = 0; i < oldChildren.length; ++i)
                {
                    oldChildren[i].destroy(options);
                }
            }
        };

        /**
         * The width of the Container, setting this will actually modify the scale to achieve the value set
         *
         * @member {number}
         */
        prototypeAccessors.width.get = function ()
        {
            return this.scale.x * this.getLocalBounds().width;
        };

        prototypeAccessors.width.set = function (value) // eslint-disable-line require-jsdoc
        {
            var width = this.getLocalBounds().width;

            if (width !== 0)
            {
                this.scale.x = value / width;
            }
            else
            {
                this.scale.x = 1;
            }

            this._width = value;
        };

        /**
         * The height of the Container, setting this will actually modify the scale to achieve the value set
         *
         * @member {number}
         */
        prototypeAccessors.height.get = function ()
        {
            return this.scale.y * this.getLocalBounds().height;
        };

        prototypeAccessors.height.set = function (value) // eslint-disable-line require-jsdoc
        {
            var height = this.getLocalBounds().height;

            if (height !== 0)
            {
                this.scale.y = value / height;
            }
            else
            {
                this.scale.y = 1;
            }

            this._height = value;
        };

        Object.defineProperties( Container.prototype, prototypeAccessors );

        return Container;
    }(DisplayObject));

    // performance increase to avoid using call.. (10x faster)
    Container.prototype.containerUpdateTransform = Container.prototype.updateTransform;

    exports.Bounds = Bounds;
    exports.Container = Container;
    exports.DisplayObject = DisplayObject;

    return exports;

}({}, PIXI, PIXI, PIXI.utils));
Object.assign(this.PIXI, _pixi_display);
//# sourceMappingURL=display.js.map
