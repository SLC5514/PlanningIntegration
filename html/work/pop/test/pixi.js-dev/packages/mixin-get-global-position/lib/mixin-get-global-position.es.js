/*!
 * @pixi/mixin-get-global-position - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/mixin-get-global-position is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
import { DisplayObject } from '@pixi/display';
import { Point } from '@pixi/math';

/**
 * Returns the global position of the displayObject. Does not depend on object scale, rotation and pivot.
 *
 * @method getGlobalPosition
 * @memberof PIXI.DisplayObject#
 * @param {Point} point - The point to write the global value to. If null a new point will be returned
 * @param {boolean} skipUpdate - Setting to true will stop the transforms of the scene graph from
 *  being updated. This means the calculation returned MAY be out of date BUT will give you a
 *  nice performance boost.
 * @return {Point} The updated point.
 */
DisplayObject.prototype.getGlobalPosition = function getGlobalPosition(point, skipUpdate)
{
    if ( point === void 0 ) point = new Point();
    if ( skipUpdate === void 0 ) skipUpdate = false;

    if (this.parent)
    {
        this.parent.toGlobal(this.position, point, skipUpdate);
    }
    else
    {
        point.x = this.position.x;
        point.y = this.position.y;
    }

    return point;
};
//# sourceMappingURL=mixin-get-global-position.es.js.map