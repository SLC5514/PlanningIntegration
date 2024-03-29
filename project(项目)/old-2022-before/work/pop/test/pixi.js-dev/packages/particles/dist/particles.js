/*!
 * @pixi/particles - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/particles is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
this.PIXI = this.PIXI || {};
var _pixi_particles = (function (exports, constants, utils, display, core, math) {
    'use strict';

    /**
     * The ParticleContainer class is a really fast version of the Container built solely for speed,
     * so use when you need a lot of sprites or particles.
     *
     * The tradeoff of the ParticleContainer is that most advanced functionality will not work.
     * ParticleContainer implements the basic object transform (position, scale, rotation)
     * and some advanced functionality like tint (as of v4.5.6).
     *
     * Other more advanced functionality like masking, children, filters, etc will not work on sprites in this batch.
     *
     * It's extremely easy to use:
     * ```js
     * let container = new ParticleContainer();
     *
     * for (let i = 0; i < 100; ++i)
     * {
     *     let sprite = new PIXI.Sprite.from("myImage.png");
     *     container.addChild(sprite);
     * }
     * ```
     *
     * And here you have a hundred sprites that will be rendered at the speed of light.
     *
     * @class
     * @extends PIXI.Container
     * @memberof PIXI
     */
    var ParticleContainer = /*@__PURE__*/(function (Container) {
        function ParticleContainer(maxSize, properties, batchSize, autoResize)
        {
            if ( maxSize === void 0 ) maxSize = 1500;
            if ( batchSize === void 0 ) batchSize = 16384;
            if ( autoResize === void 0 ) autoResize = false;

            Container.call(this);

            // Making sure the batch size is valid
            // 65535 is max vertex index in the index buffer (see ParticleRenderer)
            // so max number of particles is 65536 / 4 = 16384
            var maxBatchSize = 16384;

            if (batchSize > maxBatchSize)
            {
                batchSize = maxBatchSize;
            }

            if (batchSize > maxSize)
            {
                batchSize = maxSize;
            }

            /**
             * Set properties to be dynamic (true) / static (false)
             *
             * @member {boolean[]}
             * @private
             */
            this._properties = [false, true, false, false, false];

            /**
             * @member {number}
             * @private
             */
            this._maxSize = maxSize;

            /**
             * @member {number}
             * @private
             */
            this._batchSize = batchSize;

            /**
             * @member {Array<PIXI.Buffer>}
             * @private
             */
            this._buffers = null;

            /**
             * for every batch stores _updateID corresponding to the last change in that batch
             * @member {number[]}
             * @private
             */
            this._bufferUpdateIDs = [];

            /**
             * when child inserted, removed or changes position this number goes up
             * @member {number[]}
             * @private
             */
            this._updateID = 0;

            /**
             * @member {boolean}
             *
             */
            this.interactiveChildren = false;

            /**
             * The blend mode to be applied to the sprite. Apply a value of `PIXI.BLEND_MODES.NORMAL`
             * to reset the blend mode.
             *
             * @member {number}
             * @default PIXI.BLEND_MODES.NORMAL
             * @see PIXI.BLEND_MODES
             */
            this.blendMode = constants.BLEND_MODES.NORMAL;

            /**
             * If true, container allocates more batches in case there are more than `maxSize` particles.
             * @member {boolean}
             * @default false
             */
            this.autoResize = autoResize;

            /**
             * If true PixiJS will Math.floor() x/y values when rendering, stopping pixel interpolation.
             * Advantages can include sharper image quality (like text) and faster rendering on canvas.
             * The main disadvantage is movement of objects may appear less smooth.
             * Default to true here as performance is usually the priority for particles.
             *
             * @member {boolean}
             * @default true
             */
            this.roundPixels = true;

            /**
             * The texture used to render the children.
             *
             * @readonly
             * @member {BaseTexture}
             */
            this.baseTexture = null;

            this.setProperties(properties);

            /**
             * The tint applied to the container.
             * This is a hex value. A value of 0xFFFFFF will remove any tint effect.
             *
             * @private
             * @member {number}
             * @default 0xFFFFFF
             */
            this._tint = 0;
            this.tintRgb = new Float32Array(4);
            this.tint = 0xFFFFFF;
        }

        if ( Container ) ParticleContainer.__proto__ = Container;
        ParticleContainer.prototype = Object.create( Container && Container.prototype );
        ParticleContainer.prototype.constructor = ParticleContainer;

        var prototypeAccessors = { tint: { configurable: true } };

        /**
         * Sets the private properties array to dynamic / static based on the passed properties object
         *
         * @param {object} properties - The properties to be uploaded
         */
        ParticleContainer.prototype.setProperties = function setProperties (properties)
        {
            if (properties)
            {
                this._properties[0] = 'vertices' in properties || 'scale' in properties
                    ? !!properties.vertices || !!properties.scale : this._properties[0];
                this._properties[1] = 'position' in properties ? !!properties.position : this._properties[1];
                this._properties[2] = 'rotation' in properties ? !!properties.rotation : this._properties[2];
                this._properties[3] = 'uvs' in properties ? !!properties.uvs : this._properties[3];
                this._properties[4] = 'tint' in properties || 'alpha' in properties
                    ? !!properties.tint || !!properties.alpha : this._properties[4];
            }
        };

        /**
         * Updates the object transform for rendering
         *
         * @private
         */
        ParticleContainer.prototype.updateTransform = function updateTransform ()
        {
            // TODO don't need to!
            this.displayObjectUpdateTransform();
            //  PIXI.Container.prototype.updateTransform.call( this );
        };

        /**
         * The tint applied to the container. This is a hex value.
         * A value of 0xFFFFFF will remove any tint effect.
         ** IMPORTANT: This is a WebGL only feature and will be ignored by the canvas renderer.
         * @member {number}
         * @default 0xFFFFFF
         */
        prototypeAccessors.tint.get = function ()
        {
            return this._tint;
        };

        prototypeAccessors.tint.set = function (value) // eslint-disable-line require-jsdoc
        {
            this._tint = value;
            utils.hex2rgb(value, this.tintRgb);
        };

        /**
         * Renders the container using the WebGL renderer
         *
         * @private
         * @param {PIXI.Renderer} renderer - The webgl renderer
         */
        ParticleContainer.prototype.render = function render (renderer)
        {
            var this$1 = this;

            if (!this.visible || this.worldAlpha <= 0 || !this.children.length || !this.renderable)
            {
                return;
            }

            if (!this.baseTexture)
            {
                this.baseTexture = this.children[0]._texture.baseTexture;
                if (!this.baseTexture.valid)
                {
                    this.baseTexture.once('update', function () { return this$1.onChildrenChange(0); });
                }
            }

            renderer.batch.setObjectRenderer(renderer.plugins.particle);
            renderer.plugins.particle.render(this);
        };

        /**
         * Set the flag that static data should be updated to true
         *
         * @private
         * @param {number} smallestChildIndex - The smallest child index
         */
        ParticleContainer.prototype.onChildrenChange = function onChildrenChange (smallestChildIndex)
        {
            var bufferIndex = Math.floor(smallestChildIndex / this._batchSize);

            while (this._bufferUpdateIDs.length < bufferIndex)
            {
                this._bufferUpdateIDs.push(0);
            }
            this._bufferUpdateIDs[bufferIndex] = ++this._updateID;
        };

        ParticleContainer.prototype.dispose = function dispose ()
        {
            if (this._buffers)
            {
                for (var i = 0; i < this._buffers.length; ++i)
                {
                    this._buffers[i].destroy();
                }

                this._buffers = null;
            }
        };

        /**
         * Destroys the container
         *
         * @param {object|boolean} [options] - Options parameter. A boolean will act as if all options
         *  have been set to that value
         * @param {boolean} [options.children=false] - if set to true, all the children will have their
         *  destroy method called as well. 'options' will be passed on to those calls.
         * @param {boolean} [options.texture=false] - Only used for child Sprites if options.children is set to true
         *  Should it destroy the texture of the child sprite
         * @param {boolean} [options.baseTexture=false] - Only used for child Sprites if options.children is set to true
         *  Should it destroy the base texture of the child sprite
         */
        ParticleContainer.prototype.destroy = function destroy (options)
        {
            Container.prototype.destroy.call(this, options);

            this.dispose();

            this._properties = null;
            this._buffers = null;
            this._bufferUpdateIDs = null;
        };

        Object.defineProperties( ParticleContainer.prototype, prototypeAccessors );

        return ParticleContainer;
    }(display.Container));

    /**
     * @author Mat Groves
     *
     * Big thanks to the very clever Matt DesLauriers <mattdesl> https://github.com/mattdesl/
     * for creating the original PixiJS version!
     * Also a thanks to https://github.com/bchevalier for tweaking the tint and alpha so that
     * they now share 4 bytes on the vertex buffer
     *
     * Heavily inspired by LibGDX's ParticleBuffer:
     * https://github.com/libgdx/libgdx/blob/master/gdx/src/com/badlogic/gdx/graphics/g2d/ParticleBuffer.java
     */

    /**
     * The particle buffer manages the static and dynamic buffers for a particle container.
     *
     * @class
     * @private
     * @memberof PIXI
     */
    var ParticleBuffer = function ParticleBuffer(properties, dynamicPropertyFlags, size)
    {
        this.geometry = new core.Geometry();

        this.indexBuffer = null;

        /**
         * The number of particles the buffer can hold
         *
         * @private
         * @member {number}
         */
        this.size = size;

        /**
         * A list of the properties that are dynamic.
         *
         * @private
         * @member {object[]}
         */
        this.dynamicProperties = [];

        /**
         * A list of the properties that are static.
         *
         * @private
         * @member {object[]}
         */
        this.staticProperties = [];

        for (var i = 0; i < properties.length; ++i)
        {
            var property = properties[i];

            // Make copy of properties object so that when we edit the offset it doesn't
            // change all other instances of the object literal
            property = {
                attributeName: property.attributeName,
                size: property.size,
                uploadFunction: property.uploadFunction,
                type: property.type || constants.TYPES.FLOAT,
                offset: property.offset,
            };

            if (dynamicPropertyFlags[i])
            {
                this.dynamicProperties.push(property);
            }
            else
            {
                this.staticProperties.push(property);
            }
        }

        this.staticStride = 0;
        this.staticBuffer = null;
        this.staticData = null;
        this.staticDataUint32 = null;

        this.dynamicStride = 0;
        this.dynamicBuffer = null;
        this.dynamicData = null;
        this.dynamicDataUint32 = null;

        this._updateID = 0;

        this.initBuffers();
    };

    /**
     * Sets up the renderer context and necessary buffers.
     *
     * @private
     */
    ParticleBuffer.prototype.initBuffers = function initBuffers ()
    {
        var geometry = this.geometry;

        var dynamicOffset = 0;

        /**
         * Holds the indices of the geometry (quads) to draw
         *
         * @member {Uint16Array}
         * @private
         */
        this.indexBuffer = new core.Buffer(utils.createIndicesForQuads(this.size), true, true);
        geometry.addIndex(this.indexBuffer);

        this.dynamicStride = 0;

        for (var i = 0; i < this.dynamicProperties.length; ++i)
        {
            var property = this.dynamicProperties[i];

            property.offset = dynamicOffset;
            dynamicOffset += property.size;
            this.dynamicStride += property.size;
        }

        var dynBuffer = new ArrayBuffer(this.size * this.dynamicStride * 4 * 4);

        this.dynamicData = new Float32Array(dynBuffer);
        this.dynamicDataUint32 = new Uint32Array(dynBuffer);
        this.dynamicBuffer = new core.Buffer(this.dynamicData, false, false);

        // static //
        var staticOffset = 0;

        this.staticStride = 0;

        for (var i$1 = 0; i$1 < this.staticProperties.length; ++i$1)
        {
            var property$1 = this.staticProperties[i$1];

            property$1.offset = staticOffset;
            staticOffset += property$1.size;
            this.staticStride += property$1.size;
        }

        var statBuffer = new ArrayBuffer(this.size * this.staticStride * 4 * 4);

        this.staticData = new Float32Array(statBuffer);
        this.staticDataUint32 = new Uint32Array(statBuffer);
        this.staticBuffer = new core.Buffer(this.staticData, true, false);

        for (var i$2 = 0; i$2 < this.dynamicProperties.length; ++i$2)
        {
            var property$2 = this.dynamicProperties[i$2];

            geometry.addAttribute(
                property$2.attributeName,
                this.dynamicBuffer,
                0,
                property$2.type === constants.TYPES.UNSIGNED_BYTE,
                property$2.type,
                this.dynamicStride * 4,
                property$2.offset * 4
            );
        }

        for (var i$3 = 0; i$3 < this.staticProperties.length; ++i$3)
        {
            var property$3 = this.staticProperties[i$3];

            geometry.addAttribute(
                property$3.attributeName,
                this.staticBuffer,
                0,
                property$3.type === constants.TYPES.UNSIGNED_BYTE,
                property$3.type,
                this.staticStride * 4,
                property$3.offset * 4
            );
        }
    };

    /**
     * Uploads the dynamic properties.
     *
     * @private
     * @param {PIXI.DisplayObject[]} children - The children to upload.
     * @param {number} startIndex - The index to start at.
     * @param {number} amount - The number to upload.
     */
    ParticleBuffer.prototype.uploadDynamic = function uploadDynamic (children, startIndex, amount)
    {
        for (var i = 0; i < this.dynamicProperties.length; i++)
        {
            var property = this.dynamicProperties[i];

            property.uploadFunction(children, startIndex, amount,
                property.type === constants.TYPES.UNSIGNED_BYTE ? this.dynamicDataUint32 : this.dynamicData,
                this.dynamicStride, property.offset);
        }

        this.dynamicBuffer._updateID++;
    };

    /**
     * Uploads the static properties.
     *
     * @private
     * @param {PIXI.DisplayObject[]} children - The children to upload.
     * @param {number} startIndex - The index to start at.
     * @param {number} amount - The number to upload.
     */
    ParticleBuffer.prototype.uploadStatic = function uploadStatic (children, startIndex, amount)
    {
        for (var i = 0; i < this.staticProperties.length; i++)
        {
            var property = this.staticProperties[i];

            property.uploadFunction(children, startIndex, amount,
                property.type === constants.TYPES.UNSIGNED_BYTE ? this.staticDataUint32 : this.staticData,
                this.staticStride, property.offset);
        }

        this.staticBuffer._updateID++;
    };

    /**
     * Destroys the ParticleBuffer.
     *
     * @private
     */
    ParticleBuffer.prototype.destroy = function destroy ()
    {
        this.indexBuffer = null;

        this.dynamicProperties = null;
        // this.dynamicBuffer.destroy();
        this.dynamicBuffer = null;
        this.dynamicData = null;
        this.dynamicDataUint32 = null;

        this.staticProperties = null;
        // this.staticBuffer.destroy();
        this.staticBuffer = null;
        this.staticData = null;
        this.staticDataUint32 = null;
        // all buffers are destroyed inside geometry
        this.geometry.destroy();
    };

    var vertex = "attribute vec2 aVertexPosition;\nattribute vec2 aTextureCoord;\nattribute vec4 aColor;\n\nattribute vec2 aPositionCoord;\nattribute float aRotation;\n\nuniform mat3 translationMatrix;\nuniform vec4 uColor;\n\nvarying vec2 vTextureCoord;\nvarying vec4 vColor;\n\nvoid main(void){\n    float x = (aVertexPosition.x) * cos(aRotation) - (aVertexPosition.y) * sin(aRotation);\n    float y = (aVertexPosition.x) * sin(aRotation) + (aVertexPosition.y) * cos(aRotation);\n\n    vec2 v = vec2(x, y);\n    v = v + aPositionCoord;\n\n    gl_Position = vec4((translationMatrix * vec3(v, 1.0)).xy, 0.0, 1.0);\n\n    vTextureCoord = aTextureCoord;\n    vColor = aColor * uColor;\n}\n";

    var fragment = "varying vec2 vTextureCoord;\nvarying vec4 vColor;\n\nuniform sampler2D uSampler;\n\nvoid main(void){\n    vec4 color = texture2D(uSampler, vTextureCoord) * vColor;\n    gl_FragColor = color;\n}";

    /**
     * @author Mat Groves
     *
     * Big thanks to the very clever Matt DesLauriers <mattdesl> https://github.com/mattdesl/
     * for creating the original PixiJS version!
     * Also a thanks to https://github.com/bchevalier for tweaking the tint and alpha so that they now
     * share 4 bytes on the vertex buffer
     *
     * Heavily inspired by LibGDX's ParticleRenderer:
     * https://github.com/libgdx/libgdx/blob/master/gdx/src/com/badlogic/gdx/graphics/g2d/ParticleRenderer.java
     */

    /**
     * Renderer for Particles that is designer for speed over feature set.
     *
     * @class
     * @memberof PIXI
     */
    var ParticleRenderer = /*@__PURE__*/(function (ObjectRenderer) {
        function ParticleRenderer(renderer)
        {
            ObjectRenderer.call(this, renderer);

            // 65535 is max vertex index in the index buffer (see ParticleRenderer)
            // so max number of particles is 65536 / 4 = 16384
            // and max number of element in the index buffer is 16384 * 6 = 98304
            // Creating a full index buffer, overhead is 98304 * 2 = 196Ko
            // let numIndices = 98304;

            /**
             * The default shader that is used if a sprite doesn't have a more specific one.
             *
             * @member {PIXI.Shader}
             */
            this.shader = null;

            this.properties = null;

            this.tempMatrix = new math.Matrix();

            this.properties = [
                // verticesData
                {
                    attributeName: 'aVertexPosition',
                    size: 2,
                    uploadFunction: this.uploadVertices,
                    offset: 0,
                },
                // positionData
                {
                    attributeName: 'aPositionCoord',
                    size: 2,
                    uploadFunction: this.uploadPosition,
                    offset: 0,
                },
                // rotationData
                {
                    attributeName: 'aRotation',
                    size: 1,
                    uploadFunction: this.uploadRotation,
                    offset: 0,
                },
                // uvsData
                {
                    attributeName: 'aTextureCoord',
                    size: 2,
                    uploadFunction: this.uploadUvs,
                    offset: 0,
                },
                // tintData
                {
                    attributeName: 'aColor',
                    size: 1,
                    type: constants.TYPES.UNSIGNED_BYTE,
                    uploadFunction: this.uploadTint,
                    offset: 0,
                } ];

            this.shader = core.Shader.from(vertex, fragment, {});
        }

        if ( ObjectRenderer ) ParticleRenderer.__proto__ = ObjectRenderer;
        ParticleRenderer.prototype = Object.create( ObjectRenderer && ObjectRenderer.prototype );
        ParticleRenderer.prototype.constructor = ParticleRenderer;

        /**
         * Renders the particle container object.
         *
         * @param {PIXI.ParticleContainer} container - The container to render using this ParticleRenderer
         */
        ParticleRenderer.prototype.render = function render (container)
        {
            var children = container.children;
            var maxSize = container._maxSize;
            var batchSize = container._batchSize;
            var renderer = this.renderer;
            var totalChildren = children.length;

            if (totalChildren === 0)
            {
                return;
            }
            else if (totalChildren > maxSize)
            {
                totalChildren = maxSize;
            }

            var buffers = container._buffers;

            if (!buffers)
            {
                buffers = container._buffers = this.generateBuffers(container);
            }

            var baseTexture = children[0]._texture.baseTexture;

            // if the uvs have not updated then no point rendering just yet!
            this.renderer.state.setBlendMode(utils.correctBlendMode(container.blendMode, baseTexture.premultiplyAlpha));

            var gl = renderer.gl;

            var m = container.worldTransform.copyTo(this.tempMatrix);

            m.prepend(renderer.globalUniforms.uniforms.projectionMatrix);

            this.shader.uniforms.translationMatrix = m.toArray(true);

            this.shader.uniforms.uColor = utils.premultiplyRgba(container.tintRgb,
                container.worldAlpha, this.shader.uniforms.uColor, baseTexture.premultiplyAlpha);

            this.shader.uniforms.uSampler = baseTexture;

            this.renderer.shader.bind(this.shader);

            var updateStatic = false;

            // now lets upload and render the buffers..
            for (var i = 0, j = 0; i < totalChildren; i += batchSize, j += 1)
            {
                var amount = (totalChildren - i);

                if (amount > batchSize)
                {
                    amount = batchSize;
                }

                if (j >= buffers.length)
                {
                    if (!container.autoResize)
                    {
                        break;
                    }
                    buffers.push(this._generateOneMoreBuffer(container));
                }

                var buffer = buffers[j];

                // we always upload the dynamic
                buffer.uploadDynamic(children, i, amount);

                var bid = container._bufferUpdateIDs[j] || 0;

                updateStatic = updateStatic || (buffer._updateID < bid);
                // we only upload the static content when we have to!
                if (updateStatic)
                {
                    buffer._updateID = container._updateID;
                    buffer.uploadStatic(children, i, amount);
                }

                // bind the buffer
                renderer.geometry.bind(buffer.geometry);
                gl.drawElements(gl.TRIANGLES, amount * 6, gl.UNSIGNED_SHORT, 0);
            }
        };

        /**
         * Creates one particle buffer for each child in the container we want to render and updates internal properties
         *
         * @param {PIXI.ParticleContainer} container - The container to render using this ParticleRenderer
         * @return {PIXI.ParticleBuffer[]} The buffers
         * @private
         */
        ParticleRenderer.prototype.generateBuffers = function generateBuffers (container)
        {
            var buffers = [];
            var size = container._maxSize;
            var batchSize = container._batchSize;
            var dynamicPropertyFlags = container._properties;

            for (var i = 0; i < size; i += batchSize)
            {
                buffers.push(new ParticleBuffer(this.properties, dynamicPropertyFlags, batchSize));
            }

            return buffers;
        };

        /**
         * Creates one more particle buffer, because container has autoResize feature
         *
         * @param {PIXI.ParticleContainer} container - The container to render using this ParticleRenderer
         * @return {PIXI.ParticleBuffer} generated buffer
         * @private
         */
        ParticleRenderer.prototype._generateOneMoreBuffer = function _generateOneMoreBuffer (container)
        {
            var batchSize = container._batchSize;
            var dynamicPropertyFlags = container._properties;

            return new ParticleBuffer(this.properties, dynamicPropertyFlags, batchSize);
        };

        /**
         * Uploads the vertices.
         *
         * @param {PIXI.DisplayObject[]} children - the array of display objects to render
         * @param {number} startIndex - the index to start from in the children array
         * @param {number} amount - the amount of children that will have their vertices uploaded
         * @param {number[]} array - The vertices to upload.
         * @param {number} stride - Stride to use for iteration.
         * @param {number} offset - Offset to start at.
         */
        ParticleRenderer.prototype.uploadVertices = function uploadVertices (children, startIndex, amount, array, stride, offset)
        {
            var w0 = 0;
            var w1 = 0;
            var h0 = 0;
            var h1 = 0;

            for (var i = 0; i < amount; ++i)
            {
                var sprite = children[startIndex + i];
                var texture = sprite._texture;
                var sx = sprite.scale.x;
                var sy = sprite.scale.y;
                var trim = texture.trim;
                var orig = texture.orig;

                if (trim)
                {
                    // if the sprite is trimmed and is not a tilingsprite then we need to add the
                    // extra space before transforming the sprite coords..
                    w1 = trim.x - (sprite.anchor.x * orig.width);
                    w0 = w1 + trim.width;

                    h1 = trim.y - (sprite.anchor.y * orig.height);
                    h0 = h1 + trim.height;
                }
                else
                {
                    w0 = (orig.width) * (1 - sprite.anchor.x);
                    w1 = (orig.width) * -sprite.anchor.x;

                    h0 = orig.height * (1 - sprite.anchor.y);
                    h1 = orig.height * -sprite.anchor.y;
                }

                array[offset] = w1 * sx;
                array[offset + 1] = h1 * sy;

                array[offset + stride] = w0 * sx;
                array[offset + stride + 1] = h1 * sy;

                array[offset + (stride * 2)] = w0 * sx;
                array[offset + (stride * 2) + 1] = h0 * sy;

                array[offset + (stride * 3)] = w1 * sx;
                array[offset + (stride * 3) + 1] = h0 * sy;

                offset += stride * 4;
            }
        };

        /**
         * Uploads the position.
         *
         * @param {PIXI.DisplayObject[]} children - the array of display objects to render
         * @param {number} startIndex - the index to start from in the children array
         * @param {number} amount - the amount of children that will have their positions uploaded
         * @param {number[]} array - The vertices to upload.
         * @param {number} stride - Stride to use for iteration.
         * @param {number} offset - Offset to start at.
         */
        ParticleRenderer.prototype.uploadPosition = function uploadPosition (children, startIndex, amount, array, stride, offset)
        {
            for (var i = 0; i < amount; i++)
            {
                var spritePosition = children[startIndex + i].position;

                array[offset] = spritePosition.x;
                array[offset + 1] = spritePosition.y;

                array[offset + stride] = spritePosition.x;
                array[offset + stride + 1] = spritePosition.y;

                array[offset + (stride * 2)] = spritePosition.x;
                array[offset + (stride * 2) + 1] = spritePosition.y;

                array[offset + (stride * 3)] = spritePosition.x;
                array[offset + (stride * 3) + 1] = spritePosition.y;

                offset += stride * 4;
            }
        };

        /**
         * Uploads the rotiation.
         *
         * @param {PIXI.DisplayObject[]} children - the array of display objects to render
         * @param {number} startIndex - the index to start from in the children array
         * @param {number} amount - the amount of children that will have their rotation uploaded
         * @param {number[]} array - The vertices to upload.
         * @param {number} stride - Stride to use for iteration.
         * @param {number} offset - Offset to start at.
         */
        ParticleRenderer.prototype.uploadRotation = function uploadRotation (children, startIndex, amount, array, stride, offset)
        {
            for (var i = 0; i < amount; i++)
            {
                var spriteRotation = children[startIndex + i].rotation;

                array[offset] = spriteRotation;
                array[offset + stride] = spriteRotation;
                array[offset + (stride * 2)] = spriteRotation;
                array[offset + (stride * 3)] = spriteRotation;

                offset += stride * 4;
            }
        };

        /**
         * Uploads the Uvs
         *
         * @param {PIXI.DisplayObject[]} children - the array of display objects to render
         * @param {number} startIndex - the index to start from in the children array
         * @param {number} amount - the amount of children that will have their rotation uploaded
         * @param {number[]} array - The vertices to upload.
         * @param {number} stride - Stride to use for iteration.
         * @param {number} offset - Offset to start at.
         */
        ParticleRenderer.prototype.uploadUvs = function uploadUvs (children, startIndex, amount, array, stride, offset)
        {
            for (var i = 0; i < amount; ++i)
            {
                var textureUvs = children[startIndex + i]._texture._uvs;

                if (textureUvs)
                {
                    array[offset] = textureUvs.x0;
                    array[offset + 1] = textureUvs.y0;

                    array[offset + stride] = textureUvs.x1;
                    array[offset + stride + 1] = textureUvs.y1;

                    array[offset + (stride * 2)] = textureUvs.x2;
                    array[offset + (stride * 2) + 1] = textureUvs.y2;

                    array[offset + (stride * 3)] = textureUvs.x3;
                    array[offset + (stride * 3) + 1] = textureUvs.y3;

                    offset += stride * 4;
                }
                else
                {
                    // TODO you know this can be easier!
                    array[offset] = 0;
                    array[offset + 1] = 0;

                    array[offset + stride] = 0;
                    array[offset + stride + 1] = 0;

                    array[offset + (stride * 2)] = 0;
                    array[offset + (stride * 2) + 1] = 0;

                    array[offset + (stride * 3)] = 0;
                    array[offset + (stride * 3) + 1] = 0;

                    offset += stride * 4;
                }
            }
        };

        /**
         * Uploads the tint.
         *
         * @param {PIXI.DisplayObject[]} children - the array of display objects to render
         * @param {number} startIndex - the index to start from in the children array
         * @param {number} amount - the amount of children that will have their rotation uploaded
         * @param {number[]} array - The vertices to upload.
         * @param {number} stride - Stride to use for iteration.
         * @param {number} offset - Offset to start at.
         */
        ParticleRenderer.prototype.uploadTint = function uploadTint (children, startIndex, amount, array, stride, offset)
        {
            for (var i = 0; i < amount; ++i)
            {
                var sprite = children[startIndex + i];
                var premultiplied = sprite._texture.baseTexture.premultiplyAlpha;
                var alpha = sprite.alpha;
                // we dont call extra function if alpha is 1.0, that's faster
                var argb = alpha < 1.0 && premultiplied ? utils.premultiplyTint(sprite._tintRGB, alpha)
                    : sprite._tintRGB + (alpha * 255 << 24);

                array[offset] = argb;
                array[offset + stride] = argb;
                array[offset + (stride * 2)] = argb;
                array[offset + (stride * 3)] = argb;

                offset += stride * 4;
            }
        };

        /**
         * Destroys the ParticleRenderer.
         */
        ParticleRenderer.prototype.destroy = function destroy ()
        {
            ObjectRenderer.prototype.destroy.call(this);

            if (this.shader)
            {
                this.shader.destroy();
                this.shader = null;
            }

            this.tempMatrix = null;
        };

        return ParticleRenderer;
    }(core.ObjectRenderer));

    exports.ParticleContainer = ParticleContainer;
    exports.ParticleRenderer = ParticleRenderer;

    return exports;

}({}, PIXI, PIXI.utils, PIXI, PIXI, PIXI));
Object.assign(this.PIXI, _pixi_particles);
//# sourceMappingURL=particles.js.map
