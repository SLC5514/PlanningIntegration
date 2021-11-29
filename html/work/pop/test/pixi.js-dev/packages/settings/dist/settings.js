/*!
 * @pixi/settings - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/settings is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
this.PIXI = this.PIXI || {};
var _pixi_settings = (function (exports, isMobile) {
    'use strict';

    isMobile = isMobile && isMobile.hasOwnProperty('default') ? isMobile['default'] : isMobile;

    /**
     * The maximum recommended texture units to use.
     * In theory the bigger the better, and for desktop we'll use as many as we can.
     * But some mobile devices slow down if there is to many branches in the shader.
     * So in practice there seems to be a sweet spot size that varies depending on the device.
     *
     * In v4, all mobile devices were limited to 4 texture units because for this.
     * In v5, we allow all texture units to be used on modern Apple or Android devices.
     *
     * @private
     * @param {number} max
     * @returns {number}
     */
    function maxRecommendedTextures(max)
    {
        var allowMax = true;

        if (isMobile.tablet || isMobile.phone)
        {
            allowMax = false;

            if (isMobile.apple.device)
            {
                var match = (navigator.userAgent).match(/OS (\d+)_(\d+)?/);

                if (match)
                {
                    var majorVersion = parseInt(match[1], 10);

                    // All texture units can be used on devices that support ios 11 or above
                    if (majorVersion >= 11)
                    {
                        allowMax = true;
                    }
                }
            }
            if (isMobile.android.device)
            {
                var match$1 = (navigator.userAgent).match(/Android\s([0-9.]*)/);

                if (match$1)
                {
                    var majorVersion$1 = parseInt(match$1[1], 10);

                    // All texture units can be used on devices that support Android 7 (Nougat) or above
                    if (majorVersion$1 >= 7)
                    {
                        allowMax = true;
                    }
                }
            }
        }

        return allowMax ? max : 4;
    }

    /**
     * Uploading the same buffer multiple times in a single frame can cause performance issues.
     * Apparent on iOS so only check for that at the moment
     * This check may become more complex if this issue pops up elsewhere.
     *
     * @private
     * @returns {boolean}
     */
    function canUploadSameBuffer()
    {
        return !isMobile.apple.device;
    }

    /**
     * User's customizable globals for overriding the default PIXI settings, such
     * as a renderer's default resolution, framerate, float precision, etc.
     * @example
     * // Use the native window resolution as the default resolution
     * // will support high-density displays when rendering
     * PIXI.settings.RESOLUTION = window.devicePixelRatio.
     *
     * // Disable interpolation when scaling, will make texture be pixelated
     * PIXI.settings.SCALE_MODE = PIXI.SCALE_MODES.NEAREST;
     * @namespace PIXI.settings
     */
    var settings = {

        /**
         * If set to true WebGL will attempt make textures mimpaped by default.
         * Mipmapping will only succeed if the base texture uploaded has power of two dimensions.
         *
         * @static
         * @name MIPMAP_TEXTURES
         * @memberof PIXI.settings
         * @type {PIXI.MIPMAP_MODES}
         * @default PIXI.MIPMAP_MODES.POW2
         */
        MIPMAP_TEXTURES: 1,

        /**
         * Default resolution / device pixel ratio of the renderer.
         *
         * @static
         * @name RESOLUTION
         * @memberof PIXI.settings
         * @type {number}
         * @default 1
         */
        RESOLUTION: 1,

        /**
         * Default filter resolution.
         *
         * @static
         * @name FILTER_RESOLUTION
         * @memberof PIXI.settings
         * @type {number}
         * @default 1
         */
        FILTER_RESOLUTION: 1,

        /**
         * The maximum textures that this device supports.
         *
         * @static
         * @name SPRITE_MAX_TEXTURES
         * @memberof PIXI.settings
         * @type {number}
         * @default 32
         */
        SPRITE_MAX_TEXTURES: maxRecommendedTextures(32),

        // TODO: maybe change to SPRITE.BATCH_SIZE: 2000
        // TODO: maybe add PARTICLE.BATCH_SIZE: 15000

        /**
         * The default sprite batch size.
         *
         * The default aims to balance desktop and mobile devices.
         *
         * @static
         * @name SPRITE_BATCH_SIZE
         * @memberof PIXI.settings
         * @type {number}
         * @default 4096
         */
        SPRITE_BATCH_SIZE: 4096,

        /**
         * The default render options if none are supplied to {@link PIXI.Renderer}
         * or {@link PIXI.CanvasRenderer}.
         *
         * @static
         * @name RENDER_OPTIONS
         * @memberof PIXI.settings
         * @type {object}
         * @property {HTMLCanvasElement} view=null
         * @property {number} resolution=1
         * @property {boolean} antialias=false
         * @property {boolean} forceFXAA=false
         * @property {boolean} autoResize=false
         * @property {boolean} transparent=false
         * @property {number} backgroundColor=0x000000
         * @property {boolean} clearBeforeRender=true
         * @property {boolean} preserveDrawingBuffer=false
         * @property {number} width=800
         * @property {number} height=600
         * @property {boolean} legacy=false
         */
        RENDER_OPTIONS: {
            view: null,
            antialias: false,
            forceFXAA: false,
            autoResize: false,
            transparent: false,
            backgroundColor: 0x000000,
            clearBeforeRender: true,
            preserveDrawingBuffer: false,
            width: 800,
            height: 600,
            legacy: false,
        },

        /**
         * Default Garbage Collection mode.
         *
         * @static
         * @name GC_MODE
         * @memberof PIXI.settings
         * @type {PIXI.GC_MODES}
         * @default PIXI.GC_MODES.AUTO
         */
        GC_MODE: 0,

        /**
         * Default Garbage Collection max idle.
         *
         * @static
         * @name GC_MAX_IDLE
         * @memberof PIXI.settings
         * @type {number}
         * @default 3600
         */
        GC_MAX_IDLE: 60 * 60,

        /**
         * Default Garbage Collection maximum check count.
         *
         * @static
         * @name GC_MAX_CHECK_COUNT
         * @memberof PIXI.settings
         * @type {number}
         * @default 600
         */
        GC_MAX_CHECK_COUNT: 60 * 10,

        /**
         * Default wrap modes that are supported by pixi.
         *
         * @static
         * @name WRAP_MODE
         * @memberof PIXI.settings
         * @type {PIXI.WRAP_MODES}
         * @default PIXI.WRAP_MODES.CLAMP
         */
        WRAP_MODE: 33071,

        /**
         * Default scale mode for textures.
         *
         * @static
         * @name SCALE_MODE
         * @memberof PIXI.settings
         * @type {PIXI.SCALE_MODES}
         * @default PIXI.SCALE_MODES.LINEAR
         */
        SCALE_MODE: 1,

        /**
         * Default specify float precision in vertex shader.
         *
         * @static
         * @name PRECISION_VERTEX
         * @memberof PIXI.settings
         * @type {PIXI.PRECISION}
         * @default PIXI.PRECISION.HIGH
         */
        PRECISION_VERTEX: 'highp',

        /**
         * Default specify float precision in fragment shader.
         *
         * @static
         * @name PRECISION_FRAGMENT
         * @memberof PIXI.settings
         * @type {PIXI.PRECISION}
         * @default PIXI.PRECISION.MEDIUM
         */
        PRECISION_FRAGMENT: isMobile.apple.device ? 'highp' : 'mediump',

        /**
         * Can we upload the same buffer in a single frame?
         *
         * @static
         * @name CAN_UPLOAD_SAME_BUFFER
         * @memberof PIXI.settings
         * @type {boolean}
         */
        CAN_UPLOAD_SAME_BUFFER: canUploadSameBuffer(),

        /**
         * Enables bitmap creation before image load
         *
         * @static
         * @name CREATE_IMAGE_BITMAP
         * @memberof PIXI.settings
         * @type {boolean}
         * @default true
         */
        CREATE_IMAGE_BITMAP: true,

        /**
         * If true PixiJS will Math.floor() x/y values when rendering, stopping pixel interpolation.
         * Advantages can include sharper image quality (like text) and faster rendering on canvas.
         * The main disadvantage is movement of objects may appear less smooth.
         *
         * @static
         * @constant
         * @memberof PIXI.settings
         * @type {boolean}
         * @default false
         */
        ROUND_PIXELS: false,
    };

    exports.isMobile = isMobile;
    exports.settings = settings;

    return exports;

}({}, isMobile));
Object.assign(this.PIXI, _pixi_settings);
//# sourceMappingURL=settings.js.map