/*!
 * @pixi/runner - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/runner is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
this.PIXI = this.PIXI || {};
var _pixi_runner = (function (exports) {
    'use strict';

    /**
     * A Runner is a highly performant and simple alternative to signals. Best used in situations
     * where events are dispatched to many objects at high frequency (say every frame!)
     *
     *
     * like a signal..
     * ```
     * const myObject = {
     *     loaded: new PIXI.Runner('loaded')
     * }
     *
     * const listener = {
     *     loaded: function(){
     *         // thin
     *     }
     * }
     *
     * myObject.update.add(listener);
     *
     * myObject.loaded.emit();
     * ```
     *
     * Or for handling calling the same function on many items
     * ```
     * const myGame = {
     *     update: new PIXI.Runner('update')
     * }
     *
     * const gameObject = {
     *     update: function(time){
     *         // update my gamey state
     *     }
     * }
     *
     * myGame.update.add(gameObject1);
     *
     * myGame.update.emit(time);
     * ```
     * @class
     * @memberof PIXI
     */
    var Runner = function Runner(name)
    {
        this.items = [];
        this._name = name;
    };

    var prototypeAccessors = { empty: { configurable: true },name: { configurable: true } };

    /**
     * Dispatch/Broadcast Runner to all listeners added to the queue.
     * @param {...any} params - optional parameters to pass to each listener
     */
    Runner.prototype.emit = function emit (a0, a1, a2, a3, a4, a5, a6, a7)
    {
        if (arguments.length > 8)
        {
            throw new Error('max arguments reached');
        }

        var ref = this;
            var name = ref.name;
            var items = ref.items;

        for (var i = 0, len = items.length; i < len; i++)
        {
            items[i][name](a0, a1, a2, a3, a4, a5, a6, a7);
        }

        return this;
    };

    /**
     * Add a listener to the Runner
     *
     * Runners do not need to have scope or functions passed to them.
     * All that is required is to pass the listening object and ensure that it has contains a function that has the same name
     * as the name provided to the Runner when it was created.
     *
     * Eg A listener passed to this Runner will require a 'complete' function.
     *
     * ```
     * const complete = new PIXI.Runner('complete');
     * ```
     *
     * The scope used will be the object itself.
     *
     * @param {any} item - The object that will be listening.
     */
    Runner.prototype.add = function add (item)
    {
        if (item[this._name])
        {
            this.remove(item);
            this.items.push(item);
        }

        return this;
    };

    /**
     * Remove a single listener from the dispatch queue.
     * @param {any} item - The listenr that you would like to remove.
     */
    Runner.prototype.remove = function remove (item)
    {
        var index = this.items.indexOf(item);

        if (index !== -1)
        {
            this.items.splice(index, 1);
        }

        return this;
    };

    /**
     * Check to see if the listener is already in the Runner
     * @param {any} item - The listener that you would like to check.
     */
    Runner.prototype.contains = function contains (item)
    {
        return this.items.indexOf(item) !== -1;
    };

    /**
     * Remove all listeners from the Runner
     */
    Runner.prototype.removeAll = function removeAll ()
    {
        this.items.length = 0;

        return this;
    };

    /**
     * Remove all references, don't use after this.
     */
    Runner.prototype.destroy = function destroy ()
    {
        this.removeAll();
        this.items = null;
        this._name = null;
    };

    /**
     * `true` if there are no this Runner contains no listeners
     *
     * @member {boolean}
     * @readonly
     */
    prototypeAccessors.empty.get = function ()
    {
        return this.items.length === 0;
    };

    /**
     * The name of the runner.
     *
     * @member {string}
     * @readonly
     */
    prototypeAccessors.name.get = function ()
    {
        return this._name;
    };

    Object.defineProperties( Runner.prototype, prototypeAccessors );

    /**
     * Alias for `emit`
     * @memberof PIXI.Runner#
     * @method dispatch
     * @see PIXI.Runner#emit
     */
    Runner.prototype.dispatch = Runner.prototype.emit;

    /**
     * Alias for `emit`
     * @memberof PIXI.Runner#
     * @method run
     * @see PIXI.Runner#emit
     */
    Runner.prototype.run = Runner.prototype.emit;

    exports.Runner = Runner;

    return exports;

}({}));
Object.assign(this.PIXI, _pixi_runner);
//# sourceMappingURL=runner.js.map