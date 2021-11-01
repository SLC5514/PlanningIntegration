/*!
 * @pixi/graphics - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/graphics is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
this.PIXI = this.PIXI || {};
var _pixi_graphics = (function (exports, math, utils, core, display, constants) {
    'use strict';

    /**
     * Graphics curves resolution settings. If `adaptive` flag is set to `true`,
     * the resolution is calculated based on the curve's length to ensure better visual quality.
     * Adaptive draw works with `bezierCurveTo` and `quadraticCurveTo`.
     *
     * @static
     * @constant
     * @memberof PIXI
     * @name GRAPHICS_CURVES
     * @type {object}
     * @property {boolean} adaptive=false - flag indicating if the resolution should be adaptive
     * @property {number} maxLength=10 - maximal length of a single segment of the curve (if adaptive = false, ignored)
     * @property {number} minSegments=8 - minimal number of segments in the curve (if adaptive = false, ignored)
     * @property {number} maxSegments=2048 - maximal number of segments in the curve (if adaptive = false, ignored)
     */
    var GRAPHICS_CURVES = {
        adaptive: true,
        maxLength: 10,
        minSegments: 8,
        maxSegments: 2048,
        _segmentsCount: function _segmentsCount(length, defaultSegments)
        {
            if ( defaultSegments === void 0 ) defaultSegments = 20;

            if (!this.adaptive)
            {
                return defaultSegments;
            }

            var result = Math.ceil(length / this.maxLength);

            if (result < this.minSegments)
            {
                result = this.minSegments;
            }
            else if (result > this.maxSegments)
            {
                result = this.maxSegments;
            }

            return result;
        },
    };

    /**
     * Fill style object for Graphics.
     *
     * @class
     * @memberof PIXI
     */
    var FillStyle = function FillStyle()
    {
        this.reset();
    };

    /**
     * Clones the object
     *
     * @return {PIXI.FillStyle}
     */
    FillStyle.prototype.clone = function clone ()
    {
        var obj = new FillStyle();

        obj.color = this.color;
        obj.alpha = this.alpha;
        obj.texture = this.texture;
        obj.matrix = this.matrix;
        obj.visible = this.visible;

        return obj;
    };

    /**
     * Reset
     */
    FillStyle.prototype.reset = function reset ()
    {
        /**
         * The hex color value used when coloring the Graphics object.
         *
         * @member {number}
         * @default 1
         */
        this.color = 0xFFFFFF;

        /**
         * The alpha value used when filling the Graphics object.
         *
         * @member {number}
         * @default 1
         */
        this.alpha = 1;

        /**
         * The texture to be used for the fill.
         *
         * @member {string}
         * @default 0
         */
        this.texture = core.Texture.WHITE;

        /**
         * The transform aplpied to the texture.
         *
         * @member {string}
         * @default 0
         */
        this.matrix = null;

        /**
         * If the current fill is visible.
         *
         * @member {boolean}
         * @default false
         */
        this.visible = false;
    };

    /**
     * Destroy and don't use after this
     */
    FillStyle.prototype.destroy = function destroy ()
    {
        this.texture = null;
        this.matrix = null;
    };

    /**
     * A class to contain data useful for Graphics objects
     *
     * @class
     * @memberof PIXI
     */
    var GraphicsData = function GraphicsData(shape, fillStyle, lineStyle, matrix)
    {
        if ( fillStyle === void 0 ) fillStyle = null;
        if ( lineStyle === void 0 ) lineStyle = null;
        if ( matrix === void 0 ) matrix = null;

        /**
         * The shape object to draw.
         * @member {PIXI.Circle|PIXI.Ellipse|PIXI.Polygon|PIXI.Rectangle|PIXI.RoundedRectangle}
         */
        this.shape = shape;

        /**
         * The style of the line.
         * @member {PIXI.LineStyle}
         */
        this.lineStyle = lineStyle;

        /**
         * The style of the fill.
         * @member {PIXI.FillStyle}
         */
        this.fillStyle = fillStyle;

        /**
         * The transform matrix.
         * @member {PIXI.Matrix}
         */
        this.matrix = matrix;

        /**
         * The type of the shape, see the Const.Shapes file for all the existing types,
         * @member {number}
         */
        this.type = shape.type;

        /**
         * The collection of points.
         * @member {number[]}
         */
        this.points = [];

        /**
         * The collection of holes.
         * @member {PIXI.GraphicsData[]}
         */
        this.holes = [];
    };

    /**
     * Creates a new GraphicsData object with the same values as this one.
     *
     * @return {PIXI.GraphicsData} Cloned GraphicsData object
     */
    GraphicsData.prototype.clone = function clone ()
    {
        return new GraphicsData(
            this.shape,
            this.fillStyle,
            this.lineStyle,
            this.matrix
        );
    };

    /**
     * Destroys the Graphics data.
     */
    GraphicsData.prototype.destroy = function destroy ()
    {
        this.shape = null;
        this.holes.length = 0;
        this.holes = null;
        this.points.length = 0;
        this.points = null;
        this.lineStyle = null;
        this.fillStyle = null;
    };

    /**
     * Builds a circle to draw
     *
     * Ignored from docs since it is not directly exposed.
     *
     * @ignore
     * @private
     * @param {PIXI.WebGLGraphicsData} graphicsData - The graphics object to draw
     * @param {object} webGLData - an object containing all the WebGL-specific information to create this shape
     * @param {object} webGLDataNativeLines - an object containing all the WebGL-specific information to create nativeLines
     */
    var buildCircle = {

        build: function build(graphicsData)
        {
            // need to convert points to a nice regular data
            var circleData = graphicsData.shape;
            var points = graphicsData.points;
            var x = circleData.x;
            var y = circleData.y;
            var width;
            var height;

            points.length = 0;

            // TODO - bit hacky??
            if (graphicsData.type === math.SHAPES.CIRC)
            {
                width = circleData.radius;
                height = circleData.radius;
            }
            else
            {
                width = circleData.width;
                height = circleData.height;
            }

            if (width === 0 || height === 0)
            {
                return;
            }

            var totalSegs = Math.floor(30 * Math.sqrt(circleData.radius))
                || Math.floor(15 * Math.sqrt(circleData.width + circleData.height));

            totalSegs /= 2.3;

            var seg = (Math.PI * 2) / totalSegs;

            for (var i = 0; i < totalSegs; i++)
            {
                points.push(
                    x + (Math.sin(seg * i) * width),
                    y + (Math.cos(seg * i) * height)
                );
            }

            points.push(
                points[0],
                points[1]
            );
        },

        triangulate: function triangulate(graphicsData, graphicsGeometry)
        {
            var points = graphicsData.points;
            var verts = graphicsGeometry.points;
            var indices = graphicsGeometry.indices;

            var vertPos = verts.length / 2;
            var center = vertPos;

            verts.push(graphicsData.shape.x, graphicsData.shape.y);

            for (var i = 0; i < points.length; i += 2)
            {
                verts.push(points[i], points[i + 1]);

                // add some uvs
                indices.push(vertPos++, center, vertPos);
            }
        },
    };

    /**
     * Builds a line to draw
     *
     * Ignored from docs since it is not directly exposed.
     *
     * @ignore
     * @private
     * @param {PIXI.GraphicsData} graphicsData - The graphics object containing all the necessary properties
     * @param {PIXI.GraphicsGeometry} graphicsGeometry - Geometry where to append output
     */
    function buildLine (graphicsData, graphicsGeometry)
    {
        if (graphicsData.lineStyle.native)
        {
            buildNativeLine(graphicsData, graphicsGeometry);
        }
        else
        {
            buildLine$1(graphicsData, graphicsGeometry);
        }
    }

    /**
     * Builds a line to draw using the polygon method.
     *
     * Ignored from docs since it is not directly exposed.
     *
     * @ignore
     * @private
     * @param {PIXI.GraphicsData} graphicsData - The graphics object containing all the necessary properties
     * @param {PIXI.GraphicsGeometry} graphicsGeometry - Geometry where to append output
     */
    function buildLine$1(graphicsData, graphicsGeometry)
    {
        var shape = graphicsData.shape;
        var points = graphicsData.points || shape.points.slice();

        if (points.length === 0)
        {
            return;
        }
        // if the line width is an odd number add 0.5 to align to a whole pixel
        // commenting this out fixes #711 and #1620
        // if (graphicsData.lineWidth%2)
        // {
        //     for (i = 0; i < points.length; i++)
        //     {
        //         points[i] += 0.5;
        //     }
        // }

        var style = graphicsData.lineStyle;

        // get first and last point.. figure out the middle!
        var firstPoint = new math.Point(points[0], points[1]);
        var lastPoint = new math.Point(points[points.length - 2], points[points.length - 1]);
        var closedShape = shape.type !== math.SHAPES.POLY || shape.closeStroke;
        var closedPath = firstPoint.x === lastPoint.x && firstPoint.y === lastPoint.y;

        // if the first point is the last point - gonna have issues :)
        if (closedShape)
        {
            // need to clone as we are going to slightly modify the shape..
            points = points.slice();

            if (closedPath)
            {
                points.pop();
                points.pop();
                lastPoint.set(points[points.length - 2], points[points.length - 1]);
            }

            var midPointX = lastPoint.x + ((firstPoint.x - lastPoint.x) * 0.5);
            var midPointY = lastPoint.y + ((firstPoint.y - lastPoint.y) * 0.5);

            points.unshift(midPointX, midPointY);
            points.push(midPointX, midPointY);
        }

        var verts = graphicsGeometry.points;
        var length = points.length / 2;
        var indexCount = points.length;
        var indexStart = verts.length / 2;

        // DRAW the Line
        var width = style.width / 2;

        // sort color
        var p1x = points[0];
        var p1y = points[1];
        var p2x = points[2];
        var p2y = points[3];
        var p3x = 0;
        var p3y = 0;

        var perpx = -(p1y - p2y);
        var perpy = p1x - p2x;
        var perp2x = 0;
        var perp2y = 0;
        var perp3x = 0;
        var perp3y = 0;

        var dist = Math.sqrt((perpx * perpx) + (perpy * perpy));

        perpx /= dist;
        perpy /= dist;
        perpx *= width;
        perpy *= width;

        var ratio = style.alignment;// 0.5;
        var r1 = (1 - ratio) * 2;
        var r2 = ratio * 2;

        // start
        verts.push(
            p1x - (perpx * r1),
            p1y - (perpy * r1));

        verts.push(
            p1x + (perpx * r2),
            p1y + (perpy * r2));

        for (var i = 1; i < length - 1; ++i)
        {
            p1x = points[(i - 1) * 2];
            p1y = points[((i - 1) * 2) + 1];

            p2x = points[i * 2];
            p2y = points[(i * 2) + 1];

            p3x = points[(i + 1) * 2];
            p3y = points[((i + 1) * 2) + 1];

            perpx = -(p1y - p2y);
            perpy = p1x - p2x;

            dist = Math.sqrt((perpx * perpx) + (perpy * perpy));
            perpx /= dist;
            perpy /= dist;
            perpx *= width;
            perpy *= width;

            perp2x = -(p2y - p3y);
            perp2y = p2x - p3x;

            dist = Math.sqrt((perp2x * perp2x) + (perp2y * perp2y));
            perp2x /= dist;
            perp2y /= dist;
            perp2x *= width;
            perp2y *= width;

            var a1 = (-perpy + p1y) - (-perpy + p2y);
            var b1 = (-perpx + p2x) - (-perpx + p1x);
            var c1 = ((-perpx + p1x) * (-perpy + p2y)) - ((-perpx + p2x) * (-perpy + p1y));
            var a2 = (-perp2y + p3y) - (-perp2y + p2y);
            var b2 = (-perp2x + p2x) - (-perp2x + p3x);
            var c2 = ((-perp2x + p3x) * (-perp2y + p2y)) - ((-perp2x + p2x) * (-perp2y + p3y));

            var denom = (a1 * b2) - (a2 * b1);

            if (Math.abs(denom) < 0.1)
            {
                denom += 10.1;
                verts.push(
                    p2x - (perpx * r1),
                    p2y - (perpy * r1));

                verts.push(
                    p2x + (perpx * r2),
                    p2y + (perpy * r2));

                continue;
            }

            var px = ((b1 * c2) - (b2 * c1)) / denom;
            var py = ((a2 * c1) - (a1 * c2)) / denom;
            var pdist = ((px - p2x) * (px - p2x)) + ((py - p2y) * (py - p2y));

            if (pdist > (196 * width * width))
            {
                perp3x = perpx - perp2x;
                perp3y = perpy - perp2y;

                dist = Math.sqrt((perp3x * perp3x) + (perp3y * perp3y));
                perp3x /= dist;
                perp3y /= dist;
                perp3x *= width;
                perp3y *= width;

                verts.push(p2x - (perp3x * r1), p2y - (perp3y * r1));

                verts.push(p2x + (perp3x * r2), p2y + (perp3y * r2));

                verts.push(p2x - (perp3x * r2 * r1), p2y - (perp3y * r1));

                indexCount++;
            }
            else
            {
                verts.push(p2x + ((px - p2x) * r1), p2y + ((py - p2y) * r1));

                verts.push(p2x - ((px - p2x) * r2), p2y - ((py - p2y) * r2));
            }
        }

        p1x = points[(length - 2) * 2];
        p1y = points[((length - 2) * 2) + 1];

        p2x = points[(length - 1) * 2];
        p2y = points[((length - 1) * 2) + 1];

        perpx = -(p1y - p2y);
        perpy = p1x - p2x;

        dist = Math.sqrt((perpx * perpx) + (perpy * perpy));
        perpx /= dist;
        perpy /= dist;
        perpx *= width;
        perpy *= width;

        verts.push(p2x - (perpx * r1), p2y - (perpy * r1));

        verts.push(p2x + (perpx * r2), p2y + (perpy * r2));

        var indices = graphicsGeometry.indices;

        // indices.push(indexStart);

        for (var i$1 = 0; i$1 < indexCount - 2; ++i$1)
        {
            indices.push(indexStart, indexStart + 1, indexStart + 2);

            indexStart++;
        }
    }

    /**
     * Builds a line to draw using the gl.drawArrays(gl.LINES) method
     *
     * Ignored from docs since it is not directly exposed.
     *
     * @ignore
     * @private
     * @param {PIXI.GraphicsData} graphicsData - The graphics object containing all the necessary properties
     * @param {PIXI.GraphicsGeometry} graphicsGeometry - Geometry where to append output
     */
    function buildNativeLine(graphicsData, graphicsGeometry)
    {
        var i = 0;

        var points = graphicsData.points || graphicsData.shape.points;

        if (points.length === 0) { return; }

        var verts = graphicsGeometry.points;
        var indices = graphicsGeometry.indices;
        var length = points.length / 2;

        var indexStart = verts.length / 2;
        // sort color

        for (i = 1; i < length; i++)
        {
            var p1x = points[(i - 1) * 2];
            var p1y = points[((i - 1) * 2) + 1];

            var p2x = points[i * 2];
            var p2y = points[(i * 2) + 1];

            verts.push(p1x, p1y);

            verts.push(p2x, p2y);

            indices.push(indexStart++, indexStart++);
        }
    }

    /**
     * Builds a polygon to draw
     *
     * Ignored from docs since it is not directly exposed.
     *
     * @ignore
     * @private
     * @param {PIXI.WebGLGraphicsData} graphicsData - The graphics object containing all the necessary properties
     * @param {object} webGLData - an object containing all the WebGL-specific information to create this shape
     * @param {object} webGLDataNativeLines - an object containing all the WebGL-specific information to create nativeLines
     */
    var buildPoly = {

        build: function build(graphicsData)
        {
            graphicsData.points = graphicsData.shape.points.slice();
        },

        triangulate: function triangulate(graphicsData, graphicsGeometry)
        {
            var points = graphicsData.points;
            var holes = graphicsData.holes;
            var verts = graphicsGeometry.points;
            var indices = graphicsGeometry.indices;

            if (points.length >= 6)
            {
                var holeArray = [];
                // Process holes..

                for (var i = 0; i < holes.length; i++)
                {
                    var hole = holes[i];

                    holeArray.push(points.length / 2);
                    points = points.concat(hole.points);
                }

                // sort color
                var triangles = utils.earcut(points, holeArray, 2);

                if (!triangles)
                {
                    return;
                }

                var vertPos = verts.length / 2;

                for (var i$1 = 0; i$1 < triangles.length; i$1 += 3)
                {
                    indices.push(triangles[i$1] + vertPos);
                    indices.push(triangles[i$1 + 1] + vertPos);
                    indices.push(triangles[i$1 + 2] + vertPos);
                }

                for (var i$2 = 0; i$2 < points.length; i$2++)
                {
                    verts.push(points[i$2]);
                }
            }
        },
    };

    /**
     * Builds a rectangle to draw
     *
     * Ignored from docs since it is not directly exposed.
     *
     * @ignore
     * @private
     * @param {PIXI.WebGLGraphicsData} graphicsData - The graphics object containing all the necessary properties
     * @param {object} webGLData - an object containing all the WebGL-specific information to create this shape
     * @param {object} webGLDataNativeLines - an object containing all the WebGL-specific information to create nativeLines
     */
    var buildRectangle = {

        build: function build(graphicsData)
        {
            // --- //
            // need to convert points to a nice regular data
            //
            var rectData = graphicsData.shape;
            var x = rectData.x;
            var y = rectData.y;
            var width = rectData.width;
            var height = rectData.height;

            var points = graphicsData.points;

            points.length = 0;

            points.push(x, y,
                x + width, y,
                x + width, y + height,
                x, y + height);
        },

        triangulate: function triangulate(graphicsData, graphicsGeometry)
        {
            var points = graphicsData.points;
            var verts = graphicsGeometry.points;

            var vertPos = verts.length / 2;

            verts.push(points[0], points[1],
                points[2], points[3],
                points[6], points[7],
                points[4], points[5]);

            graphicsGeometry.indices.push(vertPos, vertPos + 1, vertPos + 2,
                vertPos + 1, vertPos + 2, vertPos + 3);
        },
    };

    /**
     * Builds a rounded rectangle to draw
     *
     * Ignored from docs since it is not directly exposed.
     *
     * @ignore
     * @private
     * @param {PIXI.WebGLGraphicsData} graphicsData - The graphics object containing all the necessary properties
     * @param {object} webGLData - an object containing all the WebGL-specific information to create this shape
     * @param {object} webGLDataNativeLines - an object containing all the WebGL-specific information to create nativeLines
     */
    var buildRoundedRectangle = {

        build: function build(graphicsData)
        {
            var rrectData = graphicsData.shape;
            var points = graphicsData.points;
            var x = rrectData.x;
            var y = rrectData.y;
            var width = rrectData.width;
            var height = rrectData.height;

            var radius = rrectData.radius;

            points.length = 0;

            points.push(x, y + radius);
            quadraticBezierCurve(x, y + height - radius, x, y + height, x + radius, y + height, points);
            quadraticBezierCurve(x + width - radius, y + height, x + width, y + height, x + width, y + height - radius, points);
            quadraticBezierCurve(x + width, y + radius, x + width, y, x + width - radius, y, points);
            quadraticBezierCurve(x + radius, y, x, y, x, y + radius + 0.0000000001, points);

            // this tiny number deals with the issue that occurs when points overlap and earcut fails to triangulate the item.
            // TODO - fix this properly, this is not very elegant.. but it works for now.
        },

        triangulate: function triangulate(graphicsData, graphicsGeometry)
        {
            var points = graphicsData.points;

            var verts = graphicsGeometry.points;
            var indices = graphicsGeometry.indices;

            var vecPos = verts.length / 2;

            var triangles = utils.earcut(points, null, 2);

            for (var i = 0, j = triangles.length; i < j; i += 3)
            {
                indices.push(triangles[i] + vecPos);
                //     indices.push(triangles[i] + vecPos);
                indices.push(triangles[i + 1] + vecPos);
                //   indices.push(triangles[i + 2] + vecPos);
                indices.push(triangles[i + 2] + vecPos);
            }

            for (var i$1 = 0, j$1 = points.length; i$1 < j$1; i$1++)
            {
                verts.push(points[i$1], points[++i$1]);
            }
        },
    };

    /**
     * Calculate a single point for a quadratic bezier curve.
     * Utility function used by quadraticBezierCurve.
     * Ignored from docs since it is not directly exposed.
     *
     * @ignore
     * @private
     * @param {number} n1 - first number
     * @param {number} n2 - second number
     * @param {number} perc - percentage
     * @return {number} the result
     *
     */
    function getPt(n1, n2, perc)
    {
        var diff = n2 - n1;

        return n1 + (diff * perc);
    }

    /**
     * Calculate the points for a quadratic bezier curve. (helper function..)
     * Based on: https://stackoverflow.com/questions/785097/how-do-i-implement-a-bezier-curve-in-c
     *
     * Ignored from docs since it is not directly exposed.
     *
     * @ignore
     * @private
     * @param {number} fromX - Origin point x
     * @param {number} fromY - Origin point x
     * @param {number} cpX - Control point x
     * @param {number} cpY - Control point y
     * @param {number} toX - Destination point x
     * @param {number} toY - Destination point y
     * @param {number[]} [out=[]] - The output array to add points into. If not passed, a new array is created.
     * @return {number[]} an array of points
     */
    function quadraticBezierCurve(fromX, fromY, cpX, cpY, toX, toY, out)
    {
        if ( out === void 0 ) out = [];

        var n = 20;
        var points = out;

        var xa = 0;
        var ya = 0;
        var xb = 0;
        var yb = 0;
        var x = 0;
        var y = 0;

        for (var i = 0, j = 0; i <= n; ++i)
        {
            j = i / n;

            // The Green Line
            xa = getPt(fromX, cpX, j);
            ya = getPt(fromY, cpY, j);
            xb = getPt(cpX, toX, j);
            yb = getPt(cpY, toY, j);

            // The Black Dot
            x = getPt(xa, xb, j);
            y = getPt(ya, yb, j);

            points.push(x, y);
        }

        return points;
    }

    var BATCH_POOL = [];
    var DRAW_CALL_POOL = [];

    var TICK = 0;
    /**
     * Map of fill commands for each shape type.
     *
     * @member {Object}
     * @private
     */
    var fillCommands = {};

    fillCommands[math.SHAPES.POLY] = buildPoly;
    fillCommands[math.SHAPES.CIRC] = buildCircle;
    fillCommands[math.SHAPES.ELIP] = buildCircle;
    fillCommands[math.SHAPES.RECT] = buildRectangle;
    fillCommands[math.SHAPES.RREC] = buildRoundedRectangle;

    /**
     * A little internal structure to hold interim batch objects.
     *
     * @private
     */
    var BatchPart = function BatchPart()
    {
        this.style = null;
        this.size = 0;
        this.start = 0;
        this.attribStart = 0;
        this.attribSize = 0;
    };

    /**
     * The Graphics class contains methods used to draw primitive shapes such as lines, circles and
     * rectangles to the display, and to color and fill them.
     *
     * GraphicsGeometry is designed to not be continually updating the geometry since it's expensive
     * to re-tesselate using **earcut**. Consider using {@link PIXI.Mesh} for this use-case, it's much faster.
     *
     * @class
     * @extends PIXI.BatchGeometry
     * @memberof PIXI
     */
    var GraphicsGeometry = /*@__PURE__*/(function (BatchGeometry) {
        function GraphicsGeometry()
        {
            BatchGeometry.call(this);

            /**
             * An array of points to draw
             *
             * @member {PIXI.Point[]}
             * @protected
             */
            this.points = [];

            /**
             * The collection of colors
             *
             * @member {number[]}
             * @protected
             */
            this.colors = [];

            /**
             * The UVs collection
             *
             * @member {number[]}
             * @protected
             */
            this.uvs = [];

            /**
             * The indices of the vertices
             *
             * @member {number[]}
             * @protected
             */
            this.indices = [];

            /**
             * Reference to the texture IDs.
             *
             * @member {number[]}
             * @protected
             */
            this.textureIds = [];

            /**
             * The collection of drawn shapes.
             *
             * @member {PIXI.GraphicsData[]}
             * @protected
             */
            this.graphicsData = [];

            /**
             * Used to detect if the graphics object has changed. If this is set to true then the graphics
             * object will be recalculated.
             *
             * @member {number}
             * @protected
             */
            this.dirty = 0;

            /**
             * Batches need to regenerated if the geometry is updated.
             *
             * @member {number}
             * @protected
             */
            this.batchDirty = -1;

            /**
             * Used to check if the cache is dirty.
             *
             * @member {number}
             * @protected
             */
            this.cacheDirty = -1;

            /**
             * Used to detect if we clear the graphics WebGL data.
             *
             * @member {number}
             * @default 0
             * @protected
             */
            this.clearDirty = 0;

            /**
             * List of current draw calls drived from the batches.
             *
             * @member {object[]}
             * @protected
             */
            this.drawCalls = [];

            /**
             * Intermediate abstract format sent to batch system.
             * Can be converted to drawCalls or to batchable objects.
             *
             * @member {object[]}
             * @protected
             */
            this.batches = [];

            /**
             * Index of the current last shape in the stack of calls.
             *
             * @member {number}
             * @protected
             */
            this.shapeIndex = 0;

            /**
             * Cached bounds.
             *
             * @member {PIXI.Bounds}
             * @protected
             */
            this._bounds = new display.Bounds();

            /**
             * The bounds dirty flag.
             *
             * @member {number}
             * @protected
             */
            this.boundsDirty = -1;

            /**
             * Padding to add to the bounds.
             *
             * @member {number}
             * @default 0
             */
            this.boundsPadding = 0;

            this.batchable = false;

            this.indicesUint16 = null;

            this.uvsFloat32 = null;
        }

        if ( BatchGeometry ) GraphicsGeometry.__proto__ = BatchGeometry;
        GraphicsGeometry.prototype = Object.create( BatchGeometry && BatchGeometry.prototype );
        GraphicsGeometry.prototype.constructor = GraphicsGeometry;

        var prototypeAccessors = { bounds: { configurable: true } };

        /**
         * Get the current bounds of the graphic geometry.
         *
         * @member {PIXI.Bounds}
         * @readonly
         */
        prototypeAccessors.bounds.get = function ()
        {
            if (this.boundsDirty !== this.dirty)
            {
                this.boundsDirty = this.dirty;
                this.calculateBounds();
            }

            return this._bounds;
        };

        /**
         * Clears the graphics that were drawn to this Graphics object, and resets fill and line style settings.
         *
         * @return {PIXI.GraphicsGeometry} This GraphicsGeometry object. Good for chaining method calls
         */
        GraphicsGeometry.prototype.clear = function clear ()
        {
            if (this.graphicsData.length > 0)
            {
                this.boundsDirty = -1;
                this.dirty++;
                this.clearDirty++;
                this.batchDirty++;
                this.graphicsData.length = 0;
                this.shapeIndex = 0;

                this.points.length = 0;
                this.colors.length = 0;
                this.uvs.length = 0;
                this.indices.length = 0;
                this.textureIds.length = 0;

                for (var i = 0; i < this.drawCalls.length; i++)
                {
                    this.drawCalls[i].textures.length = 0;
                    DRAW_CALL_POOL.push(this.drawCalls[i]);
                }

                this.drawCalls.length = 0;

                for (var i$1 = 0; i$1 < this.batches.length; i$1++)
                {
                    var batch =  this.batches[i$1];

                    batch.start = 0;
                    batch.attribStart = 0;
                    batch.style = null;
                    BATCH_POOL.push(batch);
                }

                this.batches.length = 0;
            }

            return this;
        };

        /**
         * Draws the given shape to this Graphics object. Can be any of Circle, Rectangle, Ellipse, Line or Polygon.
         *
         * @param {PIXI.Circle|PIXI.Ellipse|PIXI.Polygon|PIXI.Rectangle|PIXI.RoundedRectangle} shape - The shape object to draw.
         * @param {PIXI.FillStyle} fillStyle - Defines style of the fill.
         * @param {PIXI.LineStyle} lineStyle - Defines style of the lines.
         * @param {PIXI.Matrix} matrix - Transform applied to the points of the shape.
         * @return {PIXI.GraphicsGeometry} Returns geometry for chaining.
         */
        GraphicsGeometry.prototype.drawShape = function drawShape (shape, fillStyle, lineStyle, matrix)
        {
            var data = new GraphicsData(shape, fillStyle, lineStyle, matrix);

            this.graphicsData.push(data);
            this.dirty++;

            return this;
        };

        /**
         * Draws the given shape to this Graphics object. Can be any of Circle, Rectangle, Ellipse, Line or Polygon.
         *
         * @param {PIXI.Circle|PIXI.Ellipse|PIXI.Polygon|PIXI.Rectangle|PIXI.RoundedRectangle} shape - The shape object to draw.
         * @param {PIXI.Matrix} matrix - Transform applied to the points of the shape.
         * @return {PIXI.GraphicsGeometry} Returns geometry for chaining.
         */
        GraphicsGeometry.prototype.drawHole = function drawHole (shape, matrix)
        {
            if (!this.graphicsData.length)
            {
                return null;
            }

            var data = new GraphicsData(shape, null, null, matrix);

            var lastShape = this.graphicsData[this.graphicsData.length - 1];

            data.lineStyle = lastShape.lineStyle;

            lastShape.holes.push(data);

            this.dirty++;

            return data;
        };

        /**
         * Destroys the Graphics object.
         *
         * @param {object|boolean} [options] - Options parameter. A boolean will act as if all
         *  options have been set to that value
         * @param {boolean} [options.children=false] - if set to true, all the children will have
         *  their destroy method called as well. 'options' will be passed on to those calls.
         * @param {boolean} [options.texture=false] - Only used for child Sprites if options.children is set to true
         *  Should it destroy the texture of the child sprite
         * @param {boolean} [options.baseTexture=false] - Only used for child Sprites if options.children is set to true
         *  Should it destroy the base texture of the child sprite
         */
        GraphicsGeometry.prototype.destroy = function destroy (options)
        {
            BatchGeometry.prototype.destroy.call(this, options);

            // destroy each of the GraphicsData objects
            for (var i = 0; i < this.graphicsData.length; ++i)
            {
                this.graphicsData[i].destroy();
            }

            this.points.length = 0;
            this.points = null;
            this.colors.length = 0;
            this.colors = null;
            this.uvs.length = 0;
            this.uvs = null;
            this.indices.length = 0;
            this.indices = null;
            this.indexBuffer.destroy();
            this.indexBuffer = null;
            this.graphicsData.length = 0;
            this.graphicsData = null;
            this.drawCalls.length = 0;
            this.drawCalls = null;
            this.batches.length = 0;
            this.batches = null;
            this._bounds = null;
        };

        /**
         * Check to see if a point is contained within this geometry.
         *
         * @param {PIXI.Point} point - Point to check if it's contained.
         * @return {Boolean} `true` if the point is contained within geometry.
         */
        GraphicsGeometry.prototype.containsPoint = function containsPoint (point)
        {
            var graphicsData = this.graphicsData;

            for (var i = 0; i < graphicsData.length; ++i)
            {
                var data = graphicsData[i];

                if (!data.fillStyle.visible)
                {
                    continue;
                }

                // only deal with fills..
                if (data.shape)
                {
                    if (data.shape.contains(point.x, point.y))
                    {
                        if (data.holes)
                        {
                            for (var i$1 = 0; i$1 < data.holes.length; i$1++)
                            {
                                var hole = data.holes[i$1];

                                if (hole.shape.contains(point.x, point.y))
                                {
                                    return false;
                                }
                            }
                        }

                        return true;
                    }
                }
            }

            return false;
        };

        /**
         * Generates intermediate batch data. Either gets converted to drawCalls
         * or used to convert to batch objects directly by the Graphics object.
         * @protected
         */
        GraphicsGeometry.prototype.updateBatches = function updateBatches ()
        {
            if (this.dirty === this.cacheDirty) { return; }
            if (this.graphicsData.length === 0) { return; }

            if (this.dirty !== this.cacheDirty)
            {
                for (var i = 0; i < this.graphicsData.length; i++)
                {
                    var data = this.graphicsData[i];

                    if (data.fillStyle && !data.fillStyle.texture.baseTexture.valid) { return; }
                    if (data.lineStyle && !data.lineStyle.texture.baseTexture.valid) { return; }
                }
            }

            this.cacheDirty = this.dirty;

            var uvs = this.uvs;

            var batchPart = this.batches.pop()
                || BATCH_POOL.pop()
                || new BatchPart();

            batchPart.style = batchPart.style
                || this.graphicsData[0].fillStyle
                || this.graphicsData[0].lineStyle;

            var currentTexture = batchPart.style.texture.baseTexture;
            var currentColor = batchPart.style.color + batchPart.style.alpha;

            this.batches.push(batchPart);

            // TODO - this can be simplified
            for (var i$1 = this.shapeIndex; i$1 < this.graphicsData.length; i$1++)
            {
                this.shapeIndex++;

                var data$1 = this.graphicsData[i$1];
                var command = fillCommands[data$1.type];

                var fillStyle = data$1.fillStyle;
                var lineStyle = data$1.lineStyle;

                // build out the shapes points..
                command.build(data$1);

                if (data$1.matrix)
                {
                    this.transformPoints(data$1.points, data$1.matrix);
                }

                for (var j = 0; j < 2; j++)
                {
                    var style = (j === 0) ? fillStyle : lineStyle;

                    if (!style.visible) { continue; }

                    var nextTexture = style.texture.baseTexture;

                    if (currentTexture !== nextTexture || (style.color + style.alpha) !== currentColor)
                    {
                        // TODO use a const
                        nextTexture.wrapMode = 10497;
                        currentTexture = nextTexture;
                        currentColor = style.color + style.alpha;

                        var index$1 = this.indices.length;
                        var attribIndex = this.points.length / 2;

                        batchPart.size = index$1 - batchPart.start;
                        batchPart.attribSize = attribIndex - batchPart.attribStart;

                        if (batchPart.size > 0)
                        {
                            batchPart = BATCH_POOL.pop() || new BatchPart();

                            this.batches.push(batchPart);
                        }

                        batchPart.style = style;
                        batchPart.start = index$1;
                        batchPart.attribStart = attribIndex;

                        // TODO add this to the render part..
                    }

                    var start = this.points.length / 2;

                    if (j === 0)
                    {
                        if (data$1.holes.length)
                        {
                            this.processHoles(data$1.holes);

                            buildPoly.triangulate(data$1, this);
                        }
                        else
                        {
                            command.triangulate(data$1, this);
                        }
                    }
                    else
                    {
                        buildLine(data$1, this);

                        for (var i$2 = 0; i$2 < data$1.holes.length; i$2++)
                        {
                            buildLine(data$1.holes[i$2], this);
                        }
                    }

                    var size = (this.points.length / 2) - start;

                    this.addUvs(this.points, uvs, style.texture, start, size, style.matrix);
                }
            }

            var index = this.indices.length;
            var attrib = this.points.length / 2;

            batchPart.size = index - batchPart.start;
            batchPart.attribSize = attrib - batchPart.attribStart;
            this.indicesUint16 = new Uint16Array(this.indices);

            // TODO make this a const..
            this.batchable = this.isBatchable();

            if (this.batchable)
            {
                this.batchDirty++;

                this.uvsFloat32 = new Float32Array(this.uvs);

                // offset the indices so that it works with the batcher...
                for (var i$3 = 0; i$3 < this.batches.length; i$3++)
                {
                    var batch = this.batches[i$3];

                    for (var j$1 = 0; j$1 < batch.size; j$1++)
                    {
                        var index$2 = batch.start + j$1;

                        this.indicesUint16[index$2] = this.indicesUint16[index$2] - batch.attribStart;
                    }
                }
            }
            else
            {
                this.buildDrawCalls();
            }
        };

        /**
         * Checks to see if this graphics geometry can be batched.
         * Currently it needs to be small enough and not contain any native lines.
         * @protected
         */
        GraphicsGeometry.prototype.isBatchable = function isBatchable ()
        {
            var batches = this.batches;

            for (var i = 0; i < batches.length; i++)
            {
                if (batches[i].style.native)
                {
                    return false;
                }
            }

            return (this.points.length < GraphicsGeometry.BATCHABLE_SIZE * 2);
        };

        /**
         * Converts intermediate batches data to drawCalls.
         * @protected
         */
        GraphicsGeometry.prototype.buildDrawCalls = function buildDrawCalls ()
        {
            TICK++;

            for (var i = 0; i < this.drawCalls.length; i++)
            {
                this.drawCalls[i].textures.length = 0;
                DRAW_CALL_POOL.push(this.drawCalls[i]);
            }

            this.drawCalls.length = 0;

            var uvs = this.uvs;
            var colors = this.colors;
            var textureIds = this.textureIds;

            var currentGroup =  DRAW_CALL_POOL.pop() || new core.BatchDrawCall();

            currentGroup.textureCount = 0;
            currentGroup.start = 0;
            currentGroup.size = 0;
            currentGroup.type = constants.DRAW_MODES.TRIANGLES;

            var textureCount = 0;
            var currentTexture = null;
            var textureId = 0;
            var native = false;
            var drawMode = constants.DRAW_MODES.TRIANGLES;

            var index = 0;

            this.drawCalls.push(currentGroup);

            // TODO - this can be simplified
            for (var i$1 = 0; i$1 < this.batches.length; i$1++)
            {
                var data = this.batches[i$1];

                // TODO add some full on MAX_TEXTURE CODE..
                var MAX_TEXTURES = 8;

                var style = data.style;

                var nextTexture = style.texture.baseTexture;

                if (native !== style.native)
                {
                    native = style.native;
                    drawMode = native ? constants.DRAW_MODES.LINES : constants.DRAW_MODES.TRIANGLES;

                    // force the batch to break!
                    currentTexture = null;
                    textureCount = MAX_TEXTURES;
                    TICK++;
                }

                if (currentTexture !== nextTexture)
                {
                    currentTexture = nextTexture;

                    if (nextTexture._enabled !== TICK)
                    {
                        if (textureCount === MAX_TEXTURES)
                        {
                            TICK++;

                            textureCount = 0;

                            if (currentGroup.size > 0)
                            {
                                currentGroup = DRAW_CALL_POOL.pop() || new core.BatchDrawCall();
                                this.drawCalls.push(currentGroup);
                            }

                            currentGroup.start = index;
                            currentGroup.size = 0;
                            currentGroup.textureCount = 0;
                            currentGroup.type = drawMode;
                        }

                        // TODO add this to the render part..
                        nextTexture.touched = 1;// touch;
                        nextTexture._enabled = TICK;
                        nextTexture._id = textureCount;
                        nextTexture.wrapMode = 10497;

                        currentGroup.textures[currentGroup.textureCount++] = nextTexture;
                        textureCount++;
                    }
                }

                currentGroup.size += data.size;
                index += data.size;

                textureId = nextTexture._id;

                this.addColors(colors, style.color, style.alpha, data.attribSize);
                this.addTextureIds(textureIds, textureId, data.attribSize);
            }

            // upload..
            // merge for now!
            var verts = this.points;

            // verts are 2 positions.. so we * by 3 as there are 6 properties.. then 4 cos its bytes
            var glPoints = new ArrayBuffer(verts.length * 3 * 4);
            var f32 = new Float32Array(glPoints);
            var u32 = new Uint32Array(glPoints);

            var p = 0;

            for (var i$2 = 0; i$2 < verts.length / 2; i$2++)
            {
                f32[p++] = verts[i$2 * 2];
                f32[p++] = verts[(i$2 * 2) + 1];

                f32[p++] = uvs[i$2 * 2];
                f32[p++] = uvs[(i$2 * 2) + 1];

                u32[p++] = colors[i$2];

                f32[p++] = textureIds[i$2];
            }

            this._buffer.update(glPoints);
            this._indexBuffer.update(this.indicesUint16);
        };

        /**
         * Process the holes data.
         *
         * @param {PIXI.GraphicsData[]} holes - Holes to render
         * @protected
         */
        GraphicsGeometry.prototype.processHoles = function processHoles (holes)
        {
            for (var i = 0; i < holes.length; i++)
            {
                var hole = holes[i];

                var command = fillCommands[hole.type];

                command.build(hole);

                if (hole.matrix)
                {
                    this.transformPoints(hole.points, hole.matrix);
                }
            }
        };

        /**
         * Update the local bounds of the object. Expensive to use performance-wise.
         * @protected
         */
        GraphicsGeometry.prototype.calculateBounds = function calculateBounds ()
        {
            var minX = Infinity;
            var maxX = -Infinity;

            var minY = Infinity;
            var maxY = -Infinity;

            if (this.graphicsData.length)
            {
                var shape = null;
                var x = 0;
                var y = 0;
                var w = 0;
                var h = 0;

                for (var i = 0; i < this.graphicsData.length; i++)
                {
                    var data = this.graphicsData[i];

                    var type = data.type;
                    var lineWidth = data.lineStyle ? data.lineStyle.width : 0;

                    shape = data.shape;

                    if (type === math.SHAPES.RECT || type === math.SHAPES.RREC)
                    {
                        x = shape.x - (lineWidth / 2);
                        y = shape.y - (lineWidth / 2);
                        w = shape.width + lineWidth;
                        h = shape.height + lineWidth;

                        minX = x < minX ? x : minX;
                        maxX = x + w > maxX ? x + w : maxX;

                        minY = y < minY ? y : minY;
                        maxY = y + h > maxY ? y + h : maxY;
                    }
                    else if (type === math.SHAPES.CIRC)
                    {
                        x = shape.x;
                        y = shape.y;
                        w = shape.radius + (lineWidth / 2);
                        h = shape.radius + (lineWidth / 2);

                        minX = x - w < minX ? x - w : minX;
                        maxX = x + w > maxX ? x + w : maxX;

                        minY = y - h < minY ? y - h : minY;
                        maxY = y + h > maxY ? y + h : maxY;
                    }
                    else if (type === math.SHAPES.ELIP)
                    {
                        x = shape.x;
                        y = shape.y;
                        w = shape.width + (lineWidth / 2);
                        h = shape.height + (lineWidth / 2);

                        minX = x - w < minX ? x - w : minX;
                        maxX = x + w > maxX ? x + w : maxX;

                        minY = y - h < minY ? y - h : minY;
                        maxY = y + h > maxY ? y + h : maxY;
                    }
                    else
                    {
                        // POLY
                        var points = shape.points;
                        var x2 = 0;
                        var y2 = 0;
                        var dx = 0;
                        var dy = 0;
                        var rw = 0;
                        var rh = 0;
                        var cx = 0;
                        var cy = 0;

                        for (var j = 0; j + 2 < points.length; j += 2)
                        {
                            x = points[j];
                            y = points[j + 1];
                            x2 = points[j + 2];
                            y2 = points[j + 3];
                            dx = Math.abs(x2 - x);
                            dy = Math.abs(y2 - y);
                            h = lineWidth;
                            w = Math.sqrt((dx * dx) + (dy * dy));

                            if (w < 1e-9)
                            {
                                continue;
                            }

                            rw = ((h / w * dy) + dx) / 2;
                            rh = ((h / w * dx) + dy) / 2;
                            cx = (x2 + x) / 2;
                            cy = (y2 + y) / 2;

                            minX = cx - rw < minX ? cx - rw : minX;
                            maxX = cx + rw > maxX ? cx + rw : maxX;

                            minY = cy - rh < minY ? cy - rh : minY;
                            maxY = cy + rh > maxY ? cy + rh : maxY;
                        }
                    }
                }
            }
            else
            {
                minX = 0;
                maxX = 0;
                minY = 0;
                maxY = 0;
            }

            var padding = this.boundsPadding;

            this._bounds.minX = minX - padding;
            this._bounds.maxX = maxX + padding;

            this._bounds.minY = minY - padding;
            this._bounds.maxY = maxY + padding;
        };

        /**
         * Transform points using matrix.
         *
         * @protected
         * @param {number[]} points - Points to transform
         * @param {PIXI.Matrix} matrix - Transform matrix
         */
        GraphicsGeometry.prototype.transformPoints = function transformPoints (points, matrix)
        {
            for (var i = 0; i < points.length / 2; i++)
            {
                var x = points[(i * 2)];
                var y = points[(i * 2) + 1];

                points[(i * 2)] = (matrix.a * x) + (matrix.c * y) + matrix.tx;
                points[(i * 2) + 1] = (matrix.b * x) + (matrix.d * y) + matrix.ty;
            }
        };

        /**
         * Add colors.
         *
         * @protected
         * @param {number[]} colors - List of colors to add to
         * @param {number} color - Color to add
         * @param {number} alpha - Alpha to use
         * @param {number} size - Number of colors to add
         */
        GraphicsGeometry.prototype.addColors = function addColors (colors, color, alpha, size)
        {
            // TODO use the premultiply bits Ivan added
            var rgb = (color >> 16) + (color & 0xff00) + ((color & 0xff) << 16);

            var rgba =  utils.premultiplyTint(rgb, alpha);

            while (size-- > 0)
            {
                colors.push(rgba);
            }
        };

        /**
         * Add texture id that the shader/fragment wants to use.
         *
         * @protected
         * @param {number[]} textureIds
         * @param {number} id
         * @param {number} size
         */
        GraphicsGeometry.prototype.addTextureIds = function addTextureIds (textureIds, id, size)
        {
            while (size-- > 0)
            {
                textureIds.push(id);
            }
        };

        /**
         * Generates the UVs for a shape.
         *
         * @protected
         * @param {number[]} verts - Vertices
         * @param {number[]} uvs - UVs
         * @param {PIXI.Texture} texture - Reference to Texture
         * @param {number} start - Index buffer start index.
         * @param {number} size - The size/length for index buffer.
         * @param {PIXI.Matrix} [matrix] - Optional transform for all points.
         */
        GraphicsGeometry.prototype.addUvs = function addUvs (verts, uvs, texture, start, size, matrix)
        {
            var index = 0;
            var uvsStart = uvs.length;
            var frame = texture.frame;

            while (index < size)
            {
                var x = verts[(start + index) * 2];
                var y = verts[((start + index) * 2) + 1];

                if (matrix)
                {
                    var nx = (matrix.a * x) + (matrix.c * y) + matrix.tx;

                    y = (matrix.b * x) + (matrix.d * y) + matrix.ty;
                    x = nx;
                }

                index++;

                uvs.push(x / frame.width, y / frame.height);
            }

            var baseTexture = texture.baseTexture;

            if (frame.width < baseTexture.width
                || frame.height < baseTexture.height)
            {
                this.adjustUvs(uvs, texture, uvsStart, size);
            }
        };

        /**
         * Modify uvs array according to position of texture region
         * Does not work with rotated or trimmed textures
         * @param {number} uvs array
         * @param {PIXI.Texture} texture region
         * @param {number} start starting index for uvs
         * @param {number} size how many points to adjust
         */
        GraphicsGeometry.prototype.adjustUvs = function adjustUvs (uvs, texture, start, size)
        {
            var baseTexture = texture.baseTexture;
            var eps = 1e-6;
            var finish = start + (size * 2);
            var frame = texture.frame;
            var scaleX = frame.width / baseTexture.width;
            var scaleY = frame.height / baseTexture.height;
            var offsetX = frame.x / frame.width;
            var offsetY = frame.y / frame.width;
            var minX = Math.floor(uvs[start] + eps);
            var minY = Math.floor(uvs[start + 1] + eps);

            for (var i = start + 2; i < finish; i += 2)
            {
                minX = Math.min(minX, Math.floor(uvs[i] + eps));
                minY = Math.min(minY, Math.floor(uvs[i + 1] + eps));
            }
            offsetX -= minX;
            offsetY -= minY;
            for (var i$1 = start; i$1 < finish; i$1 += 2)
            {
                uvs[i$1] = (uvs[i$1] + offsetX) * scaleX;
                uvs[i$1 + 1] = (uvs[i$1 + 1] + offsetY) * scaleY;
            }
        };

        Object.defineProperties( GraphicsGeometry.prototype, prototypeAccessors );

        return GraphicsGeometry;
    }(core.BatchGeometry));

    /**
     * The maximum number of points to consider an object "batchable",
     * able to be batched by the renderer's batch system.
     *
     * @memberof PIXI.GraphicsGeometry
     * @static
     * @member {number} BATCHABLE_SIZE
     * @default 100
     */
    GraphicsGeometry.BATCHABLE_SIZE = 100;

    /**
     * Represents the line style for Graphics.
     * @memberof PIXI
     * @class
     * @extends PIXI.FillStyle
     */
    var LineStyle = /*@__PURE__*/(function (FillStyle) {
        function LineStyle () {
            FillStyle.apply(this, arguments);
        }

        if ( FillStyle ) LineStyle.__proto__ = FillStyle;
        LineStyle.prototype = Object.create( FillStyle && FillStyle.prototype );
        LineStyle.prototype.constructor = LineStyle;

        LineStyle.prototype.clone = function clone ()
        {
            var obj = new LineStyle();

            obj.color = this.color;
            obj.alpha = this.alpha;
            obj.texture = this.texture;
            obj.matrix = this.matrix;
            obj.visible = this.visible;
            obj.width = this.width;
            obj.alignment = this.alignment;
            obj.native = this.native;

            return obj;
        };
        /**
         * Reset the line style to default.
         */
        LineStyle.prototype.reset = function reset ()
        {
            FillStyle.prototype.reset.call(this);

            // Override default line style color
            this.color = 0x0;

            /**
             * The width (thickness) of any lines drawn.
             *
             * @member {number}
             * @default 0
             */
            this.width = 0;

            /**
             * The alignment of any lines drawn (0.5 = middle, 1 = outter, 0 = inner).
             *
             * @member {number}
             * @default 0
             */
            this.alignment = 0.5;

            /**
             * If true the lines will be draw using LINES instead of TRIANGLE_STRIP
             *
             * @member {boolean}
             * @default false
             */
            this.native = false;
        };

        return LineStyle;
    }(FillStyle));

    /**
     * Utilities for bezier curves
     * @class
     * @private
     */
    var BezierUtils = function BezierUtils () {};

    BezierUtils.curveLength = function curveLength (fromX, fromY, cpX, cpY, cpX2, cpY2, toX, toY)
    {
        var n = 10;
        var result = 0.0;
        var t = 0.0;
        var t2 = 0.0;
        var t3 = 0.0;
        var nt = 0.0;
        var nt2 = 0.0;
        var nt3 = 0.0;
        var x = 0.0;
        var y = 0.0;
        var dx = 0.0;
        var dy = 0.0;
        var prevX = fromX;
        var prevY = fromY;

        for (var i = 1; i <= n; ++i)
        {
            t = i / n;
            t2 = t * t;
            t3 = t2 * t;
            nt = (1.0 - t);
            nt2 = nt * nt;
            nt3 = nt2 * nt;

            x = (nt3 * fromX) + (3.0 * nt2 * t * cpX) + (3.0 * nt * t2 * cpX2) + (t3 * toX);
            y = (nt3 * fromY) + (3.0 * nt2 * t * cpY) + (3 * nt * t2 * cpY2) + (t3 * toY);
            dx = prevX - x;
            dy = prevY - y;
            prevX = x;
            prevY = y;

            result += Math.sqrt((dx * dx) + (dy * dy));
        }

        return result;
    };

    /**
     * Calculate the points for a bezier curve and then draws it.
     *
     * Ignored from docs since it is not directly exposed.
     *
     * @ignore
     * @param {number} cpX - Control point x
     * @param {number} cpY - Control point y
     * @param {number} cpX2 - Second Control point x
     * @param {number} cpY2 - Second Control point y
     * @param {number} toX - Destination point x
     * @param {number} toY - Destination point y
     * @param {number[]} points - Path array to push points into
     */
    BezierUtils.curveTo = function curveTo (cpX, cpY, cpX2, cpY2, toX, toY, points)
    {
        var fromX = points[points.length - 2];
        var fromY = points[points.length - 1];

        points.length -= 2;

        var n = GRAPHICS_CURVES._segmentsCount(
            BezierUtils.curveLength(fromX, fromY, cpX, cpY, cpX2, cpY2, toX, toY)
        );

        var dt = 0;
        var dt2 = 0;
        var dt3 = 0;
        var t2 = 0;
        var t3 = 0;

        points.push(fromX, fromY);

        for (var i = 1, j = 0; i <= n; ++i)
        {
            j = i / n;

            dt = (1 - j);
            dt2 = dt * dt;
            dt3 = dt2 * dt;

            t2 = j * j;
            t3 = t2 * j;

            points.push(
                (dt3 * fromX) + (3 * dt2 * j * cpX) + (3 * dt * t2 * cpX2) + (t3 * toX),
                (dt3 * fromY) + (3 * dt2 * j * cpY) + (3 * dt * t2 * cpY2) + (t3 * toY)
            );
        }
    };

    /**
     * Utilities for quadratic curves
     * @class
     * @private
     */
    var QuadraticUtils = function QuadraticUtils () {};

    QuadraticUtils.curveLength = function curveLength (fromX, fromY, cpX, cpY, toX, toY)
    {
        var ax = fromX - (2.0 * cpX) + toX;
        var ay = fromY - (2.0 * cpY) + toY;
        var bx = (2.0 * cpX) - (2.0 * fromX);
        var by = (2.0 * cpY) - (2.0 * fromY);
        var a = 4.0 * ((ax * ax) + (ay * ay));
        var b = 4.0 * ((ax * bx) + (ay * by));
        var c = (bx * bx) + (by * by);

        var s = 2.0 * Math.sqrt(a + b + c);
        var a2 = Math.sqrt(a);
        var a32 = 2.0 * a * a2;
        var c2 = 2.0 * Math.sqrt(c);
        var ba = b / a2;

        return (
            (a32 * s)
                + (a2 * b * (s - c2))
                + (
                    ((4.0 * c * a) - (b * b))
                   * Math.log(((2.0 * a2) + ba + s) / (ba + c2))
                )
        ) / (4.0 * a32);
    };

    /**
     * Calculate the points for a quadratic bezier curve and then draws it.
     * Based on: https://stackoverflow.com/questions/785097/how-do-i-implement-a-bezier-curve-in-c
     *
     * @private
     * @param {number} cpX - Control point x
     * @param {number} cpY - Control point y
     * @param {number} toX - Destination point x
     * @param {number} toY - Destination point y
     * @param {number[]} points - Points to add segments to.
     */
    QuadraticUtils.curveTo = function curveTo (cpX, cpY, toX, toY, points)
    {
        var fromX = points[points.length - 2];
        var fromY = points[points.length - 1];

        var n = GRAPHICS_CURVES._segmentsCount(
            QuadraticUtils.curveLength(fromX, fromY, cpX, cpY, toX, toY)
        );

        var xa = 0;
        var ya = 0;

        for (var i = 1; i <= n; ++i)
        {
            var j = i / n;

            xa = fromX + ((cpX - fromX) * j);
            ya = fromY + ((cpY - fromY) * j);

            points.push(xa + (((cpX + ((toX - cpX) * j)) - xa) * j),
                ya + (((cpY + ((toY - cpY) * j)) - ya) * j));
        }
    };

    /**
     * Utilities for arc curves
     * @class
     * @private
     */
    var ArcUtils = function ArcUtils () {};

    ArcUtils.curveTo = function curveTo (x1, y1, x2, y2, radius, points)
    {
        var fromX = points[points.length - 2];
        var fromY = points[points.length - 1];

        var a1 = fromY - y1;
        var b1 = fromX - x1;
        var a2 = y2 - y1;
        var b2 = x2 - x1;
        var mm = Math.abs((a1 * b2) - (b1 * a2));

        if (mm < 1.0e-8 || radius === 0)
        {
            if (points[points.length - 2] !== x1 || points[points.length - 1] !== y1)
            {
                points.push(x1, y1);
            }

            return null;
        }

        var dd = (a1 * a1) + (b1 * b1);
        var cc = (a2 * a2) + (b2 * b2);
        var tt = (a1 * a2) + (b1 * b2);
        var k1 = radius * Math.sqrt(dd) / mm;
        var k2 = radius * Math.sqrt(cc) / mm;
        var j1 = k1 * tt / dd;
        var j2 = k2 * tt / cc;
        var cx = (k1 * b2) + (k2 * b1);
        var cy = (k1 * a2) + (k2 * a1);
        var px = b1 * (k2 + j1);
        var py = a1 * (k2 + j1);
        var qx = b2 * (k1 + j2);
        var qy = a2 * (k1 + j2);
        var startAngle = Math.atan2(py - cy, px - cx);
        var endAngle = Math.atan2(qy - cy, qx - cx);

        return {
            cx: (cx + x1),
            cy: (cy + y1),
            radius: radius,
            startAngle: startAngle,
            endAngle: endAngle,
            anticlockwise: (b1 * a2 > b2 * a1),
        };
    };

    /**
     * The arc method creates an arc/curve (used to create circles, or parts of circles).
     *
     * @private
     * @param {number} startX - Start x location of arc
     * @param {number} startY - Start y location of arc
     * @param {number} cx - The x-coordinate of the center of the circle
     * @param {number} cy - The y-coordinate of the center of the circle
     * @param {number} radius - The radius of the circle
     * @param {number} startAngle - The starting angle, in radians (0 is at the 3 o'clock position
     *  of the arc's circle)
     * @param {number} endAngle - The ending angle, in radians
     * @param {boolean} anticlockwise - Specifies whether the drawing should be
     *  counter-clockwise or clockwise. False is default, and indicates clockwise, while true
     *  indicates counter-clockwise.
     * @param {number} n - Number of segments
     * @param {number[]} points - Collection of points to add to
     */
    ArcUtils.arc = function arc (startX, startY, cx, cy, radius, startAngle, endAngle, anticlockwise, points)
    {
        var sweep = endAngle - startAngle;
        var n = GRAPHICS_CURVES._segmentsCount(
            Math.abs(sweep) * radius,
            Math.ceil(Math.abs(sweep) / math.PI_2) * 40
        );

        var theta = (sweep) / (n * 2);
        var theta2 = theta * 2;
        var cTheta = Math.cos(theta);
        var sTheta = Math.sin(theta);
        var segMinus = n - 1;
        var remainder = (segMinus % 1) / segMinus;

        for (var i = 0; i <= segMinus; ++i)
        {
            var real = i + (remainder * i);
            var angle = ((theta) + startAngle + (theta2 * real));
            var c = Math.cos(angle);
            var s = -Math.sin(angle);

            points.push(
                (((cTheta * c) + (sTheta * s)) * radius) + cx,
                (((cTheta * -s) + (sTheta * c)) * radius) + cy
            );
        }
    };

    /**
     * Draw a star shape with an arbitrary number of points.
     *
     * @class
     * @extends PIXI.Polygon
     * @memberof PIXI
     * @param {number} x - Center X position of the star
     * @param {number} y - Center Y position of the star
     * @param {number} points - The number of points of the star, must be > 1
     * @param {number} radius - The outer radius of the star
     * @param {number} [innerRadius] - The inner radius between points, default half `radius`
     * @param {number} [rotation=0] - The rotation of the star in radians, where 0 is vertical
     * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
     */
    var Star = /*@__PURE__*/(function (Polygon) {
        function Star(x, y, points, radius, innerRadius, rotation)
        {
            innerRadius = innerRadius || radius / 2;

            var startAngle = (-1 * Math.PI / 2) + rotation;
            var len = points * 2;
            var delta = math.PI_2 / len;
            var polygon = [];

            for (var i = 0; i < len; i++)
            {
                var r = i % 2 ? innerRadius : radius;
                var angle = (i * delta) + startAngle;

                polygon.push(
                    x + (r * Math.cos(angle)),
                    y + (r * Math.sin(angle))
                );
            }

            Polygon.call(this, polygon);
        }

        if ( Polygon ) Star.__proto__ = Polygon;
        Star.prototype = Object.create( Polygon && Polygon.prototype );
        Star.prototype.constructor = Star;

        return Star;
    }(math.Polygon));

    var temp = new Float32Array(3);

    // a default shader used by graphics..
    var defaultShader = null;

    /**
     * The Graphics class contains methods used to draw primitive shapes such as lines, circles and
     * rectangles to the display, and to color and fill them.
     *
     * @class
     * @extends PIXI.Container
     * @memberof PIXI
     */
    var Graphics = /*@__PURE__*/(function (Container) {
        function Graphics(geometry)
        {
            if ( geometry === void 0 ) geometry = null;

            Container.call(this);
            /**
             * Includes vertex positions, face indices, normals, colors, UVs, and
             * custom attributes within buffers, reducing the cost of passing all
             * this data to the GPU. Can be shared between multiple Mesh or Graphics objects.
             * @member {PIXI.Geometry}
             * @readonly
             */
            this.geometry = geometry || new GraphicsGeometry();

            this.geometry.refCount++;

            /**
             * Represents the vertex and fragment shaders that processes the geometry and runs on the GPU.
             * Can be shared between multiple Graphics objects.
             * @member {PIXI.Shader}
             */
            this.shader = null;

            /**
             * Represents the WebGL state the Graphics required to render, excludes shader and geometry. E.g.,
             * blend mode, culling, depth testing, direction of rendering triangles, backface, etc.
             * @member {PIXI.State}
             */
            this.state = core.State.for2d();

            /**
             * Current fill style
             *
             * @member {PIXI.FillStyle}
             * @protected
             */
            this._fillStyle = new FillStyle();

            /**
             * Current line style
             *
             * @member {PIXI.LineStyle}
             * @protected
             */
            this._lineStyle = new LineStyle();

            /**
             * Current shape transform matrix.
             *
             * @member {PIXI.Matrix}
             * @protected
             */
            this._matrix = null;

            /**
             * Current hole mode is enabled.
             *
             * @member {boolean}
             * @default false
             * @protected
             */
            this._holeMode = false;

            /**
             * Current path
             *
             * @member {PIXI.Polygon}
             * @protected
             */
            this.currentPath = null;

            /**
             * When cacheAsBitmap is set to true the graphics object will be rendered as if it was a sprite.
             * This is useful if your graphics element does not change often, as it will speed up the rendering
             * of the object in exchange for taking up texture memory. It is also useful if you need the graphics
             * object to be anti-aliased, because it will be rendered using canvas. This is not recommended if
             * you are constantly redrawing the graphics element.
             *
             * @name cacheAsBitmap
             * @member {boolean}
             * @memberof PIXI.Graphics#
             * @default false
             */

            /**
             * A collections of batches! These can be drawn by the renderer batch system.
             *
             * @protected
             * @member {object[]}
             */
            this.batches = [];

            /**
             * Update dirty for limiting calculating tints for batches.
             *
             * @protected
             * @member {number}
             * @default -1
             */
            this.batchTint = -1;

            /**
             * Copy of the object vertex data.
             *
             * @protected
             * @member {Float32Array}
             */
            this.vertexData = null;

            this._transformID = -1;
            this.batchDirty = -1;

            // Set default
            this.tint = 0xFFFFFF;
            this.blendMode = constants.BLEND_MODES.NORMAL;
        }

        if ( Container ) Graphics.__proto__ = Container;
        Graphics.prototype = Object.create( Container && Container.prototype );
        Graphics.prototype.constructor = Graphics;

        var prototypeAccessors = { blendMode: { configurable: true },tint: { configurable: true },fill: { configurable: true },line: { configurable: true } };

        /**
         * Creates a new Graphics object with the same values as this one.
         * Note that the only the properties of the object are cloned, not its transform (position,scale,etc)
         *
         * @return {PIXI.Graphics} A clone of the graphics object
         */
        Graphics.prototype.clone = function clone ()
        {
            this.finishPoly();

            return new Graphics(this.geometry);
        };

        /**
         * The blend mode to be applied to the graphic shape. Apply a value of
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
         * The tint applied to the graphic shape. This is a hex value. A value of
         * 0xFFFFFF will remove any tint effect.
         *
         * @member {number}
         * @default 0xFFFFFF
         */
        prototypeAccessors.tint.get = function ()
        {
            return this._tint;
        };
        prototypeAccessors.tint.set = function (value)
        {
            this._tint = value;
        };

        /**
         * The current fill style.
         *
         * @member {PIXI.FillStyle}
         * @readonly
         */
        prototypeAccessors.fill.get = function ()
        {
            return this._fillStyle;
        };

        /**
         * The current line style.
         *
         * @member {PIXI.LineStyle}
         * @readonly
         */
        prototypeAccessors.line.get = function ()
        {
            return this._lineStyle;
        };

        /**
         * Specifies the line style used for subsequent calls to Graphics methods such as the lineTo()
         * method or the drawCircle() method.
         *
         * @param {number} [width=0] - width of the line to draw, will update the objects stored style
         * @param {number} [color=0] - color of the line to draw, will update the objects stored style
         * @param {number} [alpha=1] - alpha of the line to draw, will update the objects stored style
         * @param {number} [alignment=1] - alignment of the line to draw, (0 = inner, 0.5 = middle, 1 = outter)
         * @param {boolean} [native=false] - If true the lines will be draw using LINES instead of TRIANGLE_STRIP
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.lineStyle = function lineStyle (width, color, alpha, alignment, native)
        {
            if ( width === void 0 ) width = 0;
            if ( color === void 0 ) color = 0;
            if ( alpha === void 0 ) alpha = 1;
            if ( alignment === void 0 ) alignment = 0.5;
            if ( native === void 0 ) native = false;

            this.lineTextureStyle(width, core.Texture.WHITE, color, alpha, null, alignment, native);

            return this;
        };

        /**
         * Like line style but support texture for line fill.
         *
         * @param {number} [width=0] - width of the line to draw, will update the objects stored style
         * @param {PIXI.Texture} [texture=PIXI.Texture.WHITE] - Texture to use
         * @param {number} [color=0] - color of the line to draw, will update the objects stored style
         * @param {number} [alpha=1] - alpha of the line to draw, will update the objects stored style
         * @param {PIXI.Matrix} [matrix=null] Texture matrix to transform texture
         * @param {number} [alignment=0.5] - alignment of the line to draw, (0 = inner, 0.5 = middle, 1 = outter)
         * @param {boolean} [native=false] - If true the lines will be draw using LINES instead of TRIANGLE_STRIP
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.lineTextureStyle = function lineTextureStyle (width, texture, color, alpha,
            matrix, alignment, native)
        {
            if ( width === void 0 ) width = 0;
            if ( texture === void 0 ) texture = core.Texture.WHITE;
            if ( color === void 0 ) color = 0xFFFFFF;
            if ( alpha === void 0 ) alpha = 1;
            if ( matrix === void 0 ) matrix = null;
            if ( alignment === void 0 ) alignment = 0.5;
            if ( native === void 0 ) native = false;

            if (this.currentPath)
            {
                this.startPoly();
            }

            var visible = width > 0 && alpha > 0;

            if (!visible)
            {
                this._lineStyle.reset();
            }
            else
            {
                if (matrix)
                {
                    matrix = matrix.clone();
                    matrix.invert();
                }

                Object.assign(this._lineStyle, {
                    color: color,
                    width: width,
                    alpha: alpha,
                    matrix: matrix,
                    texture: texture,
                    alignment: alignment,
                    native: native,
                    visible: visible,
                });
            }

            return this;
        };

        /**
         * Start a polygon object internally
         * @protected
         */
        Graphics.prototype.startPoly = function startPoly ()
        {
            if (this.currentPath)
            {
                var points = this.currentPath.points;
                var len = this.currentPath.points.length;

                if (len > 2)
                {
                    this.drawShape(this.currentPath);
                    this.currentPath = new math.Polygon();
                    this.currentPath.closeStroke = false;
                    this.currentPath.points.push(points[len - 2], points[len - 1]);
                }
            }
            else
            {
                this.currentPath = new math.Polygon();
                this.currentPath.closeStroke = false;
            }
        };

        /**
         * Finish the polygon object.
         * @protected
         */
        Graphics.prototype.finishPoly = function finishPoly ()
        {
            if (this.currentPath)
            {
                if (this.currentPath.points.length > 2)
                {
                    this.drawShape(this.currentPath);
                    this.currentPath = null;
                }
                else
                {
                    this.currentPath.points.length = 0;
                }
            }
        };

        /**
         * Moves the current drawing position to x, y.
         *
         * @param {number} x - the X coordinate to move to
         * @param {number} y - the Y coordinate to move to
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.moveTo = function moveTo (x, y)
        {
            this.startPoly();
            this.currentPath.points[0] = x;
            this.currentPath.points[1] = y;

            return this;
        };

        /**
         * Draws a line using the current line style from the current drawing position to (x, y);
         * The current drawing position is then set to (x, y).
         *
         * @param {number} x - the X coordinate to draw to
         * @param {number} y - the Y coordinate to draw to
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.lineTo = function lineTo (x, y)
        {
            if (!this.currentPath)
            {
                this.moveTo(0, 0);
            }

            // remove duplicates..
            var points = this.currentPath.points;
            var fromX = points[points.length - 2];
            var fromY = points[points.length - 1];

            if (fromX !== x || fromY !== y)
            {
                points.push(x, y);
            }

            return this;
        };

        /**
         * Initialize the curve
         *
         * @protected
         * @param {number} [x=0]
         * @param {number} [y=0]
         */
        Graphics.prototype._initCurve = function _initCurve (x, y)
        {
            if ( x === void 0 ) x = 0;
            if ( y === void 0 ) y = 0;

            if (this.currentPath)
            {
                if (this.currentPath.points.length === 0)
                {
                    this.currentPath.points = [x, y];
                }
            }
            else
            {
                this.moveTo(x, y);
            }
        };

        /**
         * Calculate the points for a quadratic bezier curve and then draws it.
         * Based on: https://stackoverflow.com/questions/785097/how-do-i-implement-a-bezier-curve-in-c
         *
         * @param {number} cpX - Control point x
         * @param {number} cpY - Control point y
         * @param {number} toX - Destination point x
         * @param {number} toY - Destination point y
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.quadraticCurveTo = function quadraticCurveTo (cpX, cpY, toX, toY)
        {
            this._initCurve();

            var points = this.currentPath.points;

            if (points.length === 0)
            {
                this.moveTo(0, 0);
            }

            QuadraticUtils.curveTo(cpX, cpY, toX, toY, points);

            return this;
        };

        /**
         * Calculate the points for a bezier curve and then draws it.
         *
         * @param {number} cpX - Control point x
         * @param {number} cpY - Control point y
         * @param {number} cpX2 - Second Control point x
         * @param {number} cpY2 - Second Control point y
         * @param {number} toX - Destination point x
         * @param {number} toY - Destination point y
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.bezierCurveTo = function bezierCurveTo (cpX, cpY, cpX2, cpY2, toX, toY)
        {
            this._initCurve();

            BezierUtils.curveTo(cpX, cpY, cpX2, cpY2, toX, toY, this.currentPath.points);

            return this;
        };

        /**
         * The arcTo() method creates an arc/curve between two tangents on the canvas.
         *
         * "borrowed" from https://code.google.com/p/fxcanvas/ - thanks google!
         *
         * @param {number} x1 - The x-coordinate of the beginning of the arc
         * @param {number} y1 - The y-coordinate of the beginning of the arc
         * @param {number} x2 - The x-coordinate of the end of the arc
         * @param {number} y2 - The y-coordinate of the end of the arc
         * @param {number} radius - The radius of the arc
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.arcTo = function arcTo (x1, y1, x2, y2, radius)
        {
            this._initCurve(x1, y1);

            var points = this.currentPath.points;

            var result = ArcUtils.curveTo(x1, y1, x2, y2, radius, points);

            if (result)
            {
                var cx = result.cx;
                var cy = result.cy;
                var radius$1 = result.radius;
                var startAngle = result.startAngle;
                var endAngle = result.endAngle;
                var anticlockwise = result.anticlockwise;

                this.arc(cx, cy, radius$1, startAngle, endAngle, anticlockwise);
            }

            return this;
        };

        /**
         * The arc method creates an arc/curve (used to create circles, or parts of circles).
         *
         * @param {number} cx - The x-coordinate of the center of the circle
         * @param {number} cy - The y-coordinate of the center of the circle
         * @param {number} radius - The radius of the circle
         * @param {number} startAngle - The starting angle, in radians (0 is at the 3 o'clock position
         *  of the arc's circle)
         * @param {number} endAngle - The ending angle, in radians
         * @param {boolean} [anticlockwise=false] - Specifies whether the drawing should be
         *  counter-clockwise or clockwise. False is default, and indicates clockwise, while true
         *  indicates counter-clockwise.
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.arc = function arc (cx, cy, radius, startAngle, endAngle, anticlockwise)
        {
            if ( anticlockwise === void 0 ) anticlockwise = false;

            if (startAngle === endAngle)
            {
                return this;
            }

            if (!anticlockwise && endAngle <= startAngle)
            {
                endAngle += math.PI_2;
            }
            else if (anticlockwise && startAngle <= endAngle)
            {
                startAngle += math.PI_2;
            }

            var sweep = endAngle - startAngle;

            if (sweep === 0)
            {
                return this;
            }

            var startX = cx + (Math.cos(startAngle) * radius);
            var startY = cy + (Math.sin(startAngle) * radius);

            // If the currentPath exists, take its points. Otherwise call `moveTo` to start a path.
            var points = this.currentPath ? this.currentPath.points : null;

            if (points)
            {
                // TODO: make a better fix.

                // We check how far our start is from the last existing point
                var xDiff = Math.abs(points[points.length - 2] - startX);
                var yDiff = Math.abs(points[points.length - 1] - startY);

                if (xDiff < 0.001 && yDiff < 0.001)
                {
                    // If the point is very close, we don't add it, since this would lead to artifacts
                    // during tessellation due to floating point imprecision.
                }
                else
                {
                    points.push(startX, startY);
                }
            }
            else
            {
                this.moveTo(startX, startY);
                points = this.currentPath.points;
            }

            ArcUtils.arc(startX, startY, cx, cy, radius, startAngle, endAngle, anticlockwise, points);

            return this;
        };

        /**
         * Specifies a simple one-color fill that subsequent calls to other Graphics methods
         * (such as lineTo() or drawCircle()) use when drawing.
         *
         * @param {number} [color=0] - the color of the fill
         * @param {number} [alpha=1] - the alpha of the fill
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.beginFill = function beginFill (color, alpha)
        {
            if ( color === void 0 ) color = 0;
            if ( alpha === void 0 ) alpha = 1;

            return this.beginTextureFill(core.Texture.WHITE, color, alpha);
        };

        /**
         * Begin the texture fill
         *
         * @param {PIXI.Texture} [texture=PIXI.Texture.WHITE] - Texture to fill
         * @param {number} [color=0xffffff] - Background to fill behind texture
         * @param {number} [alpha=1] - Alpha of fill
         * @param {PIXI.Matrix} [matrix=null] - Transform matrix
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.beginTextureFill = function beginTextureFill (texture, color, alpha, matrix)
        {
            if ( texture === void 0 ) texture = core.Texture.WHITE;
            if ( color === void 0 ) color = 0xFFFFFF;
            if ( alpha === void 0 ) alpha = 1;
            if ( matrix === void 0 ) matrix = null;

            if (this.currentPath)
            {
                this.startPoly();
            }

            var visible = alpha > 0;

            if (!visible)
            {
                this._fillStyle.reset();
            }
            else
            {
                if (matrix)
                {
                    matrix = matrix.clone();
                    matrix.invert();
                }

                Object.assign(this._fillStyle, {
                    color: color,
                    alpha: alpha,
                    texture: texture,
                    matrix: matrix,
                    visible: visible,
                });
            }

            return this;
        };

        /**
         * Applies a fill to the lines and shapes that were added since the last call to the beginFill() method.
         *
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.endFill = function endFill ()
        {
            this.finishPoly();

            this._fillStyle.reset();

            return this;
        };

        /**
         * Draws a rectangle shape.
         *
         * @param {number} x - The X coord of the top-left of the rectangle
         * @param {number} y - The Y coord of the top-left of the rectangle
         * @param {number} width - The width of the rectangle
         * @param {number} height - The height of the rectangle
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.drawRect = function drawRect (x, y, width, height)
        {
            return this.drawShape(new math.Rectangle(x, y, width, height));
        };

        /**
         * Draw a rectangle shape with rounded/beveled corners.
         *
         * @param {number} x - The X coord of the top-left of the rectangle
         * @param {number} y - The Y coord of the top-left of the rectangle
         * @param {number} width - The width of the rectangle
         * @param {number} height - The height of the rectangle
         * @param {number} radius - Radius of the rectangle corners
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.drawRoundedRect = function drawRoundedRect (x, y, width, height, radius)
        {
            return this.drawShape(new math.RoundedRectangle(x, y, width, height, radius));
        };

        /**
         * Draws a circle.
         *
         * @param {number} x - The X coordinate of the center of the circle
         * @param {number} y - The Y coordinate of the center of the circle
         * @param {number} radius - The radius of the circle
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.drawCircle = function drawCircle (x, y, radius)
        {
            return this.drawShape(new math.Circle(x, y, radius));
        };

        /**
         * Draws an ellipse.
         *
         * @param {number} x - The X coordinate of the center of the ellipse
         * @param {number} y - The Y coordinate of the center of the ellipse
         * @param {number} width - The half width of the ellipse
         * @param {number} height - The half height of the ellipse
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.drawEllipse = function drawEllipse (x, y, width, height)
        {
            return this.drawShape(new math.Ellipse(x, y, width, height));
        };

        /**
         * Draws a polygon using the given path.
         *
         * @param {number[]|PIXI.Point[]|PIXI.Polygon} path - The path data used to construct the polygon.
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.drawPolygon = function drawPolygon (path)
        {
            var arguments$1 = arguments;

            // prevents an argument assignment deopt
            // see section 3.1: https://github.com/petkaantonov/bluebird/wiki/Optimization-killers#3-managing-arguments
            var points = path;

            var closeStroke = true;// !!this._fillStyle;

            // check if data has points..
            if (points.points)
            {
                closeStroke = points.closeStroke;
                points = points.points;
            }

            if (!Array.isArray(points))
            {
                // prevents an argument leak deopt
                // see section 3.2: https://github.com/petkaantonov/bluebird/wiki/Optimization-killers#3-managing-arguments
                points = new Array(arguments.length);

                for (var i = 0; i < points.length; ++i)
                {
                    points[i] = arguments$1[i]; // eslint-disable-line prefer-rest-params
                }
            }

            var shape = new math.Polygon(points);

            shape.closeStroke = closeStroke;

            this.drawShape(shape);

            return this;
        };

        /**
         * Draw any shape.
         *
         * @param {PIXI.Circle|PIXI.Ellipse|PIXI.Polygon|PIXI.Rectangle|PIXI.RoundedRectangle} shape - Shape to draw
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.drawShape = function drawShape (shape)
        {
            if (!this._holeMode)
            {
                this.geometry.drawShape(
                    shape,
                    this._fillStyle.clone(),
                    this._lineStyle.clone(),
                    this._matrix
                );
            }
            else
            {
                this.geometry.drawHole(shape, this._matrix);
            }

            return this;
        };

        /**
         * Draw a star shape with an arbitrary number of points.
         *
         * @param {number} x - Center X position of the star
         * @param {number} y - Center Y position of the star
         * @param {number} points - The number of points of the star, must be > 1
         * @param {number} radius - The outer radius of the star
         * @param {number} [innerRadius] - The inner radius between points, default half `radius`
         * @param {number} [rotation=0] - The rotation of the star in radians, where 0 is vertical
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.drawStar = function drawStar (x, y, points, radius, innerRadius, rotation)
        {
            if ( rotation === void 0 ) rotation = 0;

            return this.drawPolygon(new Star(x, y, points, radius, innerRadius, rotation));
        };

        /**
         * Clears the graphics that were drawn to this Graphics object, and resets fill and line style settings.
         *
         * @return {PIXI.Graphics} This Graphics object. Good for chaining method calls
         */
        Graphics.prototype.clear = function clear ()
        {
            this.geometry.clear();

            this._matrix = null;
            this._holeMode = false;
            this.currentPath = null;
            this._spriteRect = null;

            return this;
        };

        /**
         * True if graphics consists of one rectangle, and thus, can be drawn like a Sprite and
         * masked with gl.scissor.
         *
         * @returns {boolean} True if only 1 rect.
         */
        Graphics.prototype.isFastRect = function isFastRect ()
        {
            // will fix this!
            return false;
            // this.graphicsData.length === 1
            //  && this.graphicsData[0].shape.type === SHAPES.RECT
            // && !this.graphicsData[0].lineWidth;
        };

        /**
         * Renders the object using the WebGL renderer
         *
         * @protected
         * @param {PIXI.Renderer} renderer - The renderer
         */
        Graphics.prototype._render = function _render (renderer)
        {
            this.finishPoly();

            var geometry = this.geometry;

            // batch part..
            // batch it!
            geometry.updateBatches();

            if (geometry.batchable)
            {
                if (this.batchDirty !== geometry.batchDirty)
                {
                    this.batches = [];
                    this.batchTint = -1;
                    this._transformID = -1;
                    this.batchDirty = geometry.batchDirty;

                    this.vertexData = new Float32Array(geometry.points);

                    var blendMode = this.blendMode;

                    for (var i = 0; i < geometry.batches.length; i++)
                    {
                        var gI = geometry.batches[i];

                        var color = gI.style.color;

                        //        + (alpha * 255 << 24);

                        var vertexData = new Float32Array(this.vertexData.buffer,
                            gI.attribStart * 4 * 2,
                            gI.attribSize * 2);

                        var uvs = new Float32Array(geometry.uvsFloat32.buffer,
                            gI.attribStart * 4 * 2,
                            gI.attribSize * 2);

                        var indices = new Uint16Array(geometry.indicesUint16.buffer,
                            gI.start * 2,
                            gI.size);

                        var batch = {
                            vertexData: vertexData,
                            blendMode: blendMode,
                            indices: indices,
                            uvs: uvs,
                            _batchRGB: utils.hex2rgb(color),
                            _tintRGB: color,
                            _texture: gI.style.texture,
                            alpha: gI.style.alpha,
                            worldAlpha: 1 };

                        this.batches[i] = batch;
                    }
                }

                renderer.batch.setObjectRenderer(renderer.plugins.batch);

                if (this.batches.length)
                {
                    this.calculateVertices();
                    this.calculateTints();

                    for (var i$1 = 0; i$1 < this.batches.length; i$1++)
                    {
                        var batch$1 = this.batches[i$1];

                        batch$1.worldAlpha = this.worldAlpha * batch$1.alpha;

                        renderer.plugins.batch.render(batch$1);
                    }
                }
            }
            else
            {
                // no batching...
                renderer.batch.flush();

                if (!this.shader)
                {
                    // if there is no shader here, we can use the default shader.
                    // and that only gets created if we actually need it..
                    if (!defaultShader)
                    {
                        var sampleValues = new Int32Array(16);

                        for (var i$2 = 0; i$2 < 16; i$2++)
                        {
                            sampleValues[i$2] = i$2;
                        }

                        var uniforms = {
                            tint: new Float32Array([1, 1, 1, 1]),
                            translationMatrix: new math.Matrix(),
                            default: core.UniformGroup.from({ uSamplers: sampleValues }, true),
                        };

                        // we can bbase default shader of the batch renderers program
                        var program =  renderer.plugins.batch.shader.program;

                        defaultShader = new core.Shader(program, uniforms);
                    }

                    this.shader = defaultShader;
                }

                var uniforms$1 = this.shader.uniforms;

                // lets set the transfomr
                uniforms$1.translationMatrix = this.transform.worldTransform;

                var tint = this.tint;
                var wa = this.worldAlpha;

                // and then lets set the tint..
                uniforms$1.tint[0] = (((tint >> 16) & 0xFF) / 255) * wa;
                uniforms$1.tint[1] = (((tint >> 8) & 0xFF) / 255) * wa;
                uniforms$1.tint[2] = ((tint & 0xFF) / 255) * wa;
                uniforms$1.tint[3] = wa;

                // the first draw call, we can set the uniforms of the shader directly here.

                // this means that we can tack advantage of the sync function of pixi!
                // bind and sync uniforms..
                // there is a way to optimise this..
                renderer.shader.bind(this.shader);

                // then render it
                renderer.geometry.bind(geometry, this.shader);

                // set state..
                renderer.state.setState(this.state);

                // then render the rest of them...
                for (var i$3 = 0; i$3 < geometry.drawCalls.length; i$3++)
                {
                    var drawCall = geometry.drawCalls[i$3];

                    var groupTextureCount = drawCall.textureCount;

                    for (var j = 0; j < groupTextureCount; j++)
                    {
                        renderer.texture.bind(drawCall.textures[j], j);
                    }

                    // bind the geometry...
                    renderer.geometry.draw(drawCall.type, drawCall.size, drawCall.start);
                }
            }
        };

        /**
         * Retrieves the bounds of the graphic shape as a rectangle object
         *
         * @protected
         */
        Graphics.prototype._calculateBounds = function _calculateBounds ()
        {
            this.finishPoly();
            var lb = this.geometry.bounds;

            this._bounds.addFrame(this.transform, lb.minX, lb.minY, lb.maxX, lb.maxY);
        };

        /**
         * Tests if a point is inside this graphics object
         *
         * @param {PIXI.Point} point - the point to test
         * @return {boolean} the result of the test
         */
        Graphics.prototype.containsPoint = function containsPoint (point)
        {
            this.worldTransform.applyInverse(point, Graphics._TEMP_POINT);

            return this.geometry.containsPoint(Graphics._TEMP_POINT);
        };

        /**
         * Recalcuate the tint by applying tin to batches using Graphics tint.
         * @protected
         */
        Graphics.prototype.calculateTints = function calculateTints ()
        {
            if (this.batchTint !== this.tint)
            {
                this.batchTint = this.tint;

                var tintRGB = utils.hex2rgb(this.tint, temp);

                for (var i = 0; i < this.batches.length; i++)
                {
                    var batch = this.batches[i];

                    var batchTint = batch._batchRGB;

                    var r = (tintRGB[0] * batchTint[0]) * 255;
                    var g = (tintRGB[1] * batchTint[1]) * 255;
                    var b = (tintRGB[2] * batchTint[2]) * 255;

                    // TODO Ivan, can this be done in one go?
                    var color = (r << 16) + (g << 8) + (b | 0);

                    batch._tintRGB = (color >> 16)
                            + (color & 0xff00)
                            + ((color & 0xff) << 16);
                }
            }
        };

        /**
         * If there's a transform update or a change to the shape of the
         * geometry, recaculate the vertices.
         * @protected
         */
        Graphics.prototype.calculateVertices = function calculateVertices ()
        {
            if (this._transformID === this.transform._worldID)
            {
                return;
            }

            this._transformID = this.transform._worldID;

            var wt = this.transform.worldTransform;
            var a = wt.a;
            var b = wt.b;
            var c = wt.c;
            var d = wt.d;
            var tx = wt.tx;
            var ty = wt.ty;

            var data = this.geometry.points;// batch.vertexDataOriginal;
            var vertexData = this.vertexData;

            var count = 0;

            for (var i = 0; i < data.length; i += 2)
            {
                var x = data[i];
                var y = data[i + 1];

                vertexData[count++] = (a * x) + (c * y) + tx;
                vertexData[count++] = (d * y) + (b * x) + ty;
            }
        };

        /**
         * Closes the current path.
         *
         * @return {PIXI.Graphics} Returns itself.
         */
        Graphics.prototype.closePath = function closePath ()
        {
            var currentPath = this.currentPath;

            if (currentPath)
            {
                // we don't need to add extra point in the end because buildLine will take care of that
                currentPath.closeStroke = true;
            }

            return this;
        };

        /**
         * Apply a matrix to the positional data.
         *
         * @param {PIXI.Matrix} matrix - Matrix to use for transform current shape.
         * @return {PIXI.Graphics} Returns itself.
         */
        Graphics.prototype.setMatrix = function setMatrix (matrix)
        {
            this._matrix = matrix;

            return this;
        };

        /**
         * Begin adding holes to the last draw shape
         * IMPORTANT: holes must be fully inside a shape to work
         * Also weirdness ensues if holes overlap!
         * Ellipses, Circles, Rectangles and Rounded Rectangles cannot be holes or host for holes in CanvasRenderer,
         * please use `moveTo` `lineTo`, `quadraticCurveTo` if you rely on pixi-legacy bundle.
         * @return {PIXI.Graphics} Returns itself.
         */
        Graphics.prototype.beginHole = function beginHole ()
        {
            this.finishPoly();
            this._holeMode = true;

            return this;
        };

        /**
         * End adding holes to the last draw shape
         * @return {PIXI.Graphics} Returns itself.
         */
        Graphics.prototype.endHole = function endHole ()
        {
            this.finishPoly();
            this._holeMode = false;

            return this;
        };

        /**
         * Destroys the Graphics object.
         *
         * @param {object|boolean} [options] - Options parameter. A boolean will act as if all
         *  options have been set to that value
         * @param {boolean} [options.children=false] - if set to true, all the children will have
         *  their destroy method called as well. 'options' will be passed on to those calls.
         * @param {boolean} [options.texture=false] - Only used for child Sprites if options.children is set to true
         *  Should it destroy the texture of the child sprite
         * @param {boolean} [options.baseTexture=false] - Only used for child Sprites if options.children is set to true
         *  Should it destroy the base texture of the child sprite
         */
        Graphics.prototype.destroy = function destroy (options)
        {
            Container.prototype.destroy.call(this, options);

            this.geometry.refCount--;
            if (this.geometry.refCount === 0)
            {
                this.geometry.dispose();
            }

            this._matrix = null;
            this.currentPath = null;
            this._lineStyle.destroy();
            this._lineStyle = null;
            this._fillStyle.destroy();
            this._fillStyle = null;
            this.geometry = null;
            this.shader = null;
            this.vertexData = null;
            this.batches.length = 0;
            this.batches = null;

            Container.prototype.destroy.call(this, options);
        };

        Object.defineProperties( Graphics.prototype, prototypeAccessors );

        return Graphics;
    }(display.Container));

    /**
     * Temporary point to use for containsPoint
     *
     * @static
     * @private
     * @member {PIXI.Point}
     */
    Graphics._TEMP_POINT = new math.Point();

    exports.GRAPHICS_CURVES = GRAPHICS_CURVES;
    exports.Graphics = Graphics;
    exports.GraphicsData = GraphicsData;
    exports.GraphicsGeometry = GraphicsGeometry;

    return exports;

}({}, PIXI, PIXI.utils, PIXI, PIXI, PIXI));
Object.assign(this.PIXI, _pixi_graphics);
//# sourceMappingURL=graphics.js.map
