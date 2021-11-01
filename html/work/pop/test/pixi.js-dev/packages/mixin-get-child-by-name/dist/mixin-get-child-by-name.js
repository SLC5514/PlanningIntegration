/*!
 * @pixi/mixin-get-child-by-name - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/mixin-get-child-by-name is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
this.PIXI = this.PIXI || {};
(function (display) {
    'use strict';

    /**
     * The instance name of the object.
     *
     * @memberof PIXI.DisplayObject#
     * @member {string} name
     */
    display.DisplayObject.prototype.name = null;

    /**
     * Returns the display object in the container.
     *
     * @method getChildByName
     * @memberof PIXI.Container#
     * @param {string} name - Instance name.
     * @return {PIXI.DisplayObject} The child with the specified name.
     */
    display.Container.prototype.getChildByName = function getChildByName(name)
    {
        for (var i = 0; i < this.children.length; i++)
        {
            if (this.children[i].name === name)
            {
                return this.children[i];
            }
        }

        return null;
    };

}(PIXI));
Object.assign(this.PIXI, _pixi_mixin_get_child_by_name);
//# sourceMappingURL=mixin-get-child-by-name.js.map
