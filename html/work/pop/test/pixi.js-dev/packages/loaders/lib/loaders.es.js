/*!
 * @pixi/loaders - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/loaders is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
import ResourceLoader, { Resource } from 'resource-loader';
import { EventEmitter } from '@pixi/utils';
import { blobMiddlewareFactory } from 'resource-loader/lib/middlewares/parsing/blob';
import { Texture } from '@pixi/core';

/**
 * Loader plugin for handling Texture resources.
 * @class
 * @memberof PIXI
 * @implements PIXI.ILoaderPlugin
 */
var TextureLoader = function TextureLoader () {};

TextureLoader.use = function use (resource, next)
{
    // create a new texture if the data is an Image object
    if (resource.data && resource.type === Resource.TYPE.IMAGE)
    {
        resource.texture = Texture.fromLoader(
            resource.data,
            resource.url,
            resource.name
        );
    }
    next();
};

/**
 * The new loader, extends Resource Loader by Chad Engler: https://github.com/englercj/resource-loader
 *
 * ```js
 * const loader = PIXI.Loader.shared; // PixiJS exposes a premade instance for you to use.
 * //or
 * const loader = new PIXI.Loader(); // you can also create your own if you want
 *
 * const sprites = {};
 *
 * // Chainable `add` to enqueue a resource
 * loader.add('bunny', 'data/bunny.png')
 *       .add('spaceship', 'assets/spritesheet.json');
 * loader.add('scoreFont', 'assets/score.fnt');
 *
 * // Chainable `pre` to add a middleware that runs for each resource, *before* loading that resource.
 * // This is useful to implement custom caching modules (using filesystem, indexeddb, memory, etc).
 * loader.pre(cachingMiddleware);
 *
 * // Chainable `use` to add a middleware that runs for each resource, *after* loading that resource.
 * // This is useful to implement custom parsing modules (like spritesheet parsers, spine parser, etc).
 * loader.use(parsingMiddleware);
 *
 * // The `load` method loads the queue of resources, and calls the passed in callback called once all
 * // resources have loaded.
 * loader.load((loader, resources) => {
 *     // resources is an object where the key is the name of the resource loaded and the value is the resource object.
 *     // They have a couple default properties:
 *     // - `url`: The URL that the resource was loaded from
 *     // - `error`: The error that happened when trying to load (if any)
 *     // - `data`: The raw data that was loaded
 *     // also may contain other properties based on the middleware that runs.
 *     sprites.bunny = new PIXI.TilingSprite(resources.bunny.texture);
 *     sprites.spaceship = new PIXI.TilingSprite(resources.spaceship.texture);
 *     sprites.scoreFont = new PIXI.TilingSprite(resources.scoreFont.texture);
 * });
 *
 * // throughout the process multiple signals can be dispatched.
 * loader.onProgress.add(() => {}); // called once per loaded/errored file
 * loader.onError.add(() => {}); // called once per errored file
 * loader.onLoad.add(() => {}); // called once per loaded file
 * loader.onComplete.add(() => {}); // called once when the queued resources all load.
 * ```
 *
 * @see https://github.com/englercj/resource-loader
 *
 * @class Loader
 * @memberof PIXI
 * @param {string} [baseUrl=''] - The base url for all resources loaded by this loader.
 * @param {number} [concurrency=10] - The number of resources to load concurrently.
 */
var Loader = /*@__PURE__*/(function (ResourceLoader) {
    function Loader(baseUrl, concurrency)
    {
        var this$1 = this;

        ResourceLoader.call(this, baseUrl, concurrency);
        EventEmitter.call(this);

        for (var i = 0; i < Loader._plugins.length; ++i)
        {
            var plugin = Loader._plugins[i];
            var pre = plugin.pre;
            var use = plugin.use;

            if (pre)
            {
                this.pre(pre);
            }

            if (use)
            {
                this.use(use);
            }
        }

        // Compat layer, translate the new v2 signals into old v1 events.
        this.onStart.add(function (l) { return this$1.emit('start', l); });
        this.onProgress.add(function (l, r) { return this$1.emit('progress', l, r); });
        this.onError.add(function (e, l, r) { return this$1.emit('error', e, l, r); });
        this.onLoad.add(function (l, r) { return this$1.emit('load', l, r); });
        this.onComplete.add(function (l, r) { return this$1.emit('complete', l, r); });

        /**
         * If this loader cannot be destroyed.
         * @member {boolean}
         * @default false
         * @private
         */
        this._protected = false;
    }

    if ( ResourceLoader ) Loader.__proto__ = ResourceLoader;
    Loader.prototype = Object.create( ResourceLoader && ResourceLoader.prototype );
    Loader.prototype.constructor = Loader;

    var staticAccessors = { shared: { configurable: true } };

    /**
     * Destroy the loader, removes references.
     * @private
     */
    Loader.prototype.destroy = function destroy ()
    {
        if (!this._protected)
        {
            this.removeAllListeners();
            this.reset();
        }
    };

    /**
     * A premade instance of the loader that can be used to load resources.
     * @name shared
     * @type {PIXI.Loader}
     * @static
     * @memberof PIXI.Loader
     */
    staticAccessors.shared.get = function ()
    {
        var shared = Loader._shared;

        if (!shared)
        {
            shared = new Loader();
            shared._protected = true;
            Loader._shared = shared;
        }

        return shared;
    };

    Object.defineProperties( Loader, staticAccessors );

    return Loader;
}(ResourceLoader));

// Copy EE3 prototype (mixin)
Object.assign(Loader.prototype, EventEmitter.prototype);

/**
 * Collection of all installed `use` middleware for Loader.
 *
 * @static
 * @member {Array<PIXI.ILoaderPlugin>} _plugins
 * @memberof PIXI.Loader
 * @private
 */
Loader._plugins = [];

/**
 * Adds a Loader plugin for the global shared loader and all
 * new Loader instances created.
 *
 * @static
 * @method registerPlugin
 * @memberof PIXI.Loader
 * @param {PIXI.ILoaderPlugin} plugin - The plugin to add
 * @return {PIXI.Loader} Reference to PIXI.Loader for chaining
 */
Loader.registerPlugin = function registerPlugin(plugin)
{
    Loader._plugins.push(plugin);

    if (plugin.add)
    {
        plugin.add();
    }

    return Loader;
};

// parse any blob into more usable objects (e.g. Image)
Loader.registerPlugin({ use: blobMiddlewareFactory() });

// parse any Image objects into textures
Loader.registerPlugin(TextureLoader);

/**
 * Plugin to be installed for handling specific Loader resources.
 *
 * @memberof PIXI
 * @typedef ILoaderPlugin
 * @property {function} [add] - Function to call immediate after registering plugin.
 * @property {PIXI.Loader.loaderMiddleware} [pre] - Middleware function to run before load, the
 *           arguments for this are `(resource, next)`
 * @property {PIXI.Loader.loaderMiddleware} [use] - Middleware function to run after load, the
 *           arguments for this are `(resource, next)`
 */

/**
 * @memberof PIXI.Loader
 * @callback loaderMiddleware
 * @param {PIXI.LoaderResource} resource
 * @param {function} next
 */

/**
 * Application plugin for supporting loader option. Installing the LoaderPlugin
 * is not necessary if using **pixi.js** or **pixi.js-legacy**.
 * @example
 * import {AppLoaderPlugin} from '@pixi/loaders';
 * import {Application} from '@pixi/app';
 * Application.registerPlugin(AppLoaderPlugin);
 * @class
 * @memberof PIXI
 */
var AppLoaderPlugin = function AppLoaderPlugin () {};

AppLoaderPlugin.init = function init (options)
{
    options = Object.assign({
        sharedLoader: false,
    }, options);

    /**
     * Loader instance to help with asset loading.
     * @name PIXI.Application#loader
     * @type {PIXI.Loader}
     * @readonly
     */
    this.loader = options.sharedLoader ? Loader.shared : new Loader();
};

/**
 * Called when application destroyed
 * @private
 */
AppLoaderPlugin.destroy = function destroy ()
{
    if (this.loader)
    {
        this.loader.destroy();
        this.loader = null;
    }
};

/**
 * Reference to **{@link https://github.com/englercj/resource-loader
 * resource-loader}**'s Resource class.
 * @see http://englercj.github.io/resource-loader/Resource.html
 * @class LoaderResource
 * @memberof PIXI
 */
var LoaderResource = Resource;

export { AppLoaderPlugin, Loader, LoaderResource, TextureLoader };
//# sourceMappingURL=loaders.es.js.map
