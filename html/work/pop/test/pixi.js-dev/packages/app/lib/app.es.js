/*!
 * @pixi/app - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/app is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
import { Container } from '@pixi/display';
import { autoDetectRenderer } from '@pixi/core';

/**
 * Convenience class to create a new PIXI application.
 *
 * This class automatically creates the renderer, ticker and root container.
 *
 * @example
 * // Create the application
 * const app = new PIXI.Application();
 *
 * // Add the view to the DOM
 * document.body.appendChild(app.view);
 *
 * // ex, add display objects
 * app.stage.addChild(PIXI.Sprite.from('something.png'));
 *
 * @class
 * @memberof PIXI
 */
var Application = function Application(options)
{
    var this$1 = this;

    // The default options
    options = Object.assign({
        forceCanvas: false,
    }, options);

    /**
     * WebGL renderer if available, otherwise CanvasRenderer.
     * @member {PIXI.Renderer|PIXI.CanvasRenderer}
     */
    this.renderer = autoDetectRenderer(options);

    /**
     * The root display container that's rendered.
     * @member {PIXI.Container}
     */
    this.stage = new Container();

    // install plugins here
    Application._plugins.forEach(function (plugin) {
        plugin.init.call(this$1, options);
    });
};

var prototypeAccessors = { view: { configurable: true },screen: { configurable: true } };

/**
 * Register a middleware plugin for the application
 * @static
 * @param {PIXI.Application.Plugin} plugin - Plugin being installed
 */
Application.registerPlugin = function registerPlugin (plugin)
{
    Application._plugins.push(plugin);
};

/**
 * Render the current stage.
 */
Application.prototype.render = function render ()
{
    this.renderer.render(this.stage);
};

/**
 * Reference to the renderer's canvas element.
 * @member {HTMLCanvasElement}
 * @readonly
 */
prototypeAccessors.view.get = function ()
{
    return this.renderer.view;
};

/**
 * Reference to the renderer's screen rectangle. Its safe to use as `filterArea` or `hitArea` for the whole screen.
 * @member {PIXI.Rectangle}
 * @readonly
 */
prototypeAccessors.screen.get = function ()
{
    return this.renderer.screen;
};

/**
 * Destroy and don't use after this.
 * @param {Boolean} [removeView=false] Automatically remove canvas from DOM.
 * @param {object|boolean} [stageOptions] - Options parameter. A boolean will act as if all options
 *  have been set to that value
 * @param {boolean} [stageOptions.children=false] - if set to true, all the children will have their destroy
 *  method called as well. 'stageOptions' will be passed on to those calls.
 * @param {boolean} [stageOptions.texture=false] - Only used for child Sprites if stageOptions.children is set
 *  to true. Should it destroy the texture of the child sprite
 * @param {boolean} [stageOptions.baseTexture=false] - Only used for child Sprites if stageOptions.children is set
 *  to true. Should it destroy the base texture of the child sprite
 */
Application.prototype.destroy = function destroy (removeView)
{
        var this$1 = this;

    // Destroy plugins in the opposite order
    // which they were constructed
    var plugins = Application._plugins.slice(0);

    plugins.reverse();
    plugins.forEach(function (plugin) {
        plugin.destroy.call(this$1);
    });

    this.stage.destroy();
    this.stage = null;

    this.renderer.destroy(removeView);
    this.renderer = null;

    this._options = null;
};

Object.defineProperties( Application.prototype, prototypeAccessors );

/**
 * @memberof PIXI.Application
 * @typedef {object} Plugin
 * @property {function} init - Called when Application is constructed, scoped to Application instance.
 *  Passes in `options` as the only argument, which are Application constructor options.
 * @property {function} destroy - Called when destroying Application, scoped to Application instance
 */

/**
 * Collection of installed plugins.
 * @static
 * @private
 * @type {PIXI.Application.Plugin[]}
 */
Application._plugins = [];

/**
 * Middleware for for Application's resize functionality
 * @private
 * @class
 */
var ResizePlugin = function ResizePlugin () {};

ResizePlugin.init = function init (options)
{
        var this$1 = this;

    /**
     * The element or window to resize the application to.
     * @type {Window|HTMLElement}
     * @name resizeTo
     * @memberof PIXI.Application#
     */
    Object.defineProperty(this, 'resizeTo',
        {
            set: function set(dom)
            {
                window.removeEventListener('resize', this.resize);
                this._resizeTo = dom;
                if (dom)
                {
                    window.addEventListener('resize', this.resize);
                    this.resize();
                }
            },
            get: function get()
            {
                return this._resizeTo;
            },
        });

    /**
     * If `resizeTo` is set, calling this function
     * will resize to the width and height of that element.
     * @method PIXI.Application#resize
     */
    this.resize = function () {
        if (this$1._resizeTo)
        {
            // Resize to the window
            if (this$1._resizeTo === window)
            {
                this$1.renderer.resize(
                    window.innerWidth,
                    window.innerHeight
                );
            }
            // Resize to other HTML entities
            else
            {
                this$1.renderer.resize(
                    this$1._resizeTo.clientWidth,
                    this$1._resizeTo.clientHeight
                );
            }
        }
    };

    // On resize
    this._resizeTo = null;
    this.resizeTo = options.resizeTo || null;
};

/**
 * Clean up the ticker, scoped to application
 * @static
 * @private
 */
ResizePlugin.destroy = function destroy ()
{
    this.resizeTo = null;
    this.resize = null;
};

Application.registerPlugin(ResizePlugin);

export { Application };
//# sourceMappingURL=app.es.js.map