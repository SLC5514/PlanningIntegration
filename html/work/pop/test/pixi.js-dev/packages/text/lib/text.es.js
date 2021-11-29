/*!
 * @pixi/text - v5.0.0-rc.3
 * Compiled Wed, 27 Mar 2019 02:53:29 UTC
 *
 * @pixi/text is licensed under the MIT License.
 * http://www.opensource.org/licenses/mit-license
 */
import { Sprite } from '@pixi/sprite';
import { Texture } from '@pixi/core';
import { settings } from '@pixi/settings';
import { Rectangle } from '@pixi/math';
import { hex2string, hex2rgb, string2hex, trimCanvas, sign } from '@pixi/utils';

/**
 * Constants that define the type of gradient on text.
 *
 * @static
 * @constant
 * @name TEXT_GRADIENT
 * @memberof PIXI
 * @type {object}
 * @property {number} LINEAR_VERTICAL Vertical gradient
 * @property {number} LINEAR_HORIZONTAL Linear gradient
 */
var TEXT_GRADIENT = {
    LINEAR_VERTICAL: 0,
    LINEAR_HORIZONTAL: 1,
};

// disabling eslint for now, going to rewrite this in v5

var defaultStyle = {
    align: 'left',
    breakWords: false,
    dropShadow: false,
    dropShadowAlpha: 1,
    dropShadowAngle: Math.PI / 6,
    dropShadowBlur: 0,
    dropShadowColor: 'black',
    dropShadowDistance: 5,
    fill: 'black',
    fillGradientType: TEXT_GRADIENT.LINEAR_VERTICAL,
    fillGradientStops: [],
    fontFamily: 'Arial',
    fontSize: 26,
    fontStyle: 'normal',
    fontVariant: 'normal',
    fontWeight: 'normal',
    letterSpacing: 0,
    lineHeight: 0,
    lineJoin: 'miter',
    miterLimit: 10,
    padding: 0,
    stroke: 'black',
    strokeThickness: 0,
    textBaseline: 'alphabetic',
    trim: false,
    whiteSpace: 'pre',
    wordWrap: false,
    wordWrapWidth: 100,
    leading: 0,
};

/**
 * A TextStyle Object contains information to decorate a Text objects.
 *
 * An instance can be shared between multiple Text objects; then changing the style will update all text objects using it.
 *
 * A tool can be used to generate a text style [here](https://pixijs.io/pixi-text-style).
 *
 * @class
 * @memberof PIXI
 */
var TextStyle = function TextStyle(style)
{
    this.styleID = 0;

    this.reset();

    deepCopyProperties(this, style, style);
};

var prototypeAccessors = { align: { configurable: true },breakWords: { configurable: true },dropShadow: { configurable: true },dropShadowAlpha: { configurable: true },dropShadowAngle: { configurable: true },dropShadowBlur: { configurable: true },dropShadowColor: { configurable: true },dropShadowDistance: { configurable: true },fill: { configurable: true },fillGradientType: { configurable: true },fillGradientStops: { configurable: true },fontFamily: { configurable: true },fontSize: { configurable: true },fontStyle: { configurable: true },fontVariant: { configurable: true },fontWeight: { configurable: true },letterSpacing: { configurable: true },lineHeight: { configurable: true },leading: { configurable: true },lineJoin: { configurable: true },miterLimit: { configurable: true },padding: { configurable: true },stroke: { configurable: true },strokeThickness: { configurable: true },textBaseline: { configurable: true },trim: { configurable: true },whiteSpace: { configurable: true },wordWrap: { configurable: true },wordWrapWidth: { configurable: true } };

/**
 * Creates a new TextStyle object with the same values as this one.
 * Note that the only the properties of the object are cloned.
 *
 * @return {PIXI.TextStyle} New cloned TextStyle object
 */
TextStyle.prototype.clone = function clone ()
{
    var clonedProperties = {};

    deepCopyProperties(clonedProperties, this, defaultStyle);

    return new TextStyle(clonedProperties);
};

/**
 * Resets all properties to the defaults specified in TextStyle.prototype._default
 */
TextStyle.prototype.reset = function reset ()
{
    deepCopyProperties(this, defaultStyle, defaultStyle);
};

/**
 * Alignment for multiline text ('left', 'center' or 'right'), does not affect single line text
 *
 * @member {string}
 */
prototypeAccessors.align.get = function ()
{
    return this._align;
};
prototypeAccessors.align.set = function (align) // eslint-disable-line require-jsdoc
{
    if (this._align !== align)
    {
        this._align = align;
        this.styleID++;
    }
};

/**
 * Indicates if lines can be wrapped within words, it needs wordWrap to be set to true
 *
 * @member {boolean}
 */
prototypeAccessors.breakWords.get = function ()
{
    return this._breakWords;
};
prototypeAccessors.breakWords.set = function (breakWords) // eslint-disable-line require-jsdoc
{
    if (this._breakWords !== breakWords)
    {
        this._breakWords = breakWords;
        this.styleID++;
    }
};

/**
 * Set a drop shadow for the text
 *
 * @member {boolean}
 */
prototypeAccessors.dropShadow.get = function ()
{
    return this._dropShadow;
};
prototypeAccessors.dropShadow.set = function (dropShadow) // eslint-disable-line require-jsdoc
{
    if (this._dropShadow !== dropShadow)
    {
        this._dropShadow = dropShadow;
        this.styleID++;
    }
};

/**
 * Set alpha for the drop shadow
 *
 * @member {number}
 */
prototypeAccessors.dropShadowAlpha.get = function ()
{
    return this._dropShadowAlpha;
};
prototypeAccessors.dropShadowAlpha.set = function (dropShadowAlpha) // eslint-disable-line require-jsdoc
{
    if (this._dropShadowAlpha !== dropShadowAlpha)
    {
        this._dropShadowAlpha = dropShadowAlpha;
        this.styleID++;
    }
};

/**
 * Set a angle of the drop shadow
 *
 * @member {number}
 */
prototypeAccessors.dropShadowAngle.get = function ()
{
    return this._dropShadowAngle;
};
prototypeAccessors.dropShadowAngle.set = function (dropShadowAngle) // eslint-disable-line require-jsdoc
{
    if (this._dropShadowAngle !== dropShadowAngle)
    {
        this._dropShadowAngle = dropShadowAngle;
        this.styleID++;
    }
};

/**
 * Set a shadow blur radius
 *
 * @member {number}
 */
prototypeAccessors.dropShadowBlur.get = function ()
{
    return this._dropShadowBlur;
};
prototypeAccessors.dropShadowBlur.set = function (dropShadowBlur) // eslint-disable-line require-jsdoc
{
    if (this._dropShadowBlur !== dropShadowBlur)
    {
        this._dropShadowBlur = dropShadowBlur;
        this.styleID++;
    }
};

/**
 * A fill style to be used on the dropshadow e.g 'red', '#00FF00'
 *
 * @member {string|number}
 */
prototypeAccessors.dropShadowColor.get = function ()
{
    return this._dropShadowColor;
};
prototypeAccessors.dropShadowColor.set = function (dropShadowColor) // eslint-disable-line require-jsdoc
{
    var outputColor = getColor(dropShadowColor);
    if (this._dropShadowColor !== outputColor)
    {
        this._dropShadowColor = outputColor;
        this.styleID++;
    }
};

/**
 * Set a distance of the drop shadow
 *
 * @member {number}
 */
prototypeAccessors.dropShadowDistance.get = function ()
{
    return this._dropShadowDistance;
};
prototypeAccessors.dropShadowDistance.set = function (dropShadowDistance) // eslint-disable-line require-jsdoc
{
    if (this._dropShadowDistance !== dropShadowDistance)
    {
        this._dropShadowDistance = dropShadowDistance;
        this.styleID++;
    }
};

/**
 * A canvas fillstyle that will be used on the text e.g 'red', '#00FF00'.
 * Can be an array to create a gradient eg ['#000000','#FFFFFF']
 * {@link https://developer.mozilla.org/en-US/docs/Web/API/CanvasRenderingContext2D/fillStyle|MDN}
 *
 * @member {string|string[]|number|number[]|CanvasGradient|CanvasPattern}
 */
prototypeAccessors.fill.get = function ()
{
    return this._fill;
};
prototypeAccessors.fill.set = function (fill) // eslint-disable-line require-jsdoc
{
    var outputColor = getColor(fill);
    if (this._fill !== outputColor)
    {
        this._fill = outputColor;
        this.styleID++;
    }
};

/**
 * If fill is an array of colours to create a gradient, this can change the type/direction of the gradient.
 * See {@link PIXI.TEXT_GRADIENT}
 *
 * @member {number}
 */
prototypeAccessors.fillGradientType.get = function ()
{
    return this._fillGradientType;
};
prototypeAccessors.fillGradientType.set = function (fillGradientType) // eslint-disable-line require-jsdoc
{
    if (this._fillGradientType !== fillGradientType)
    {
        this._fillGradientType = fillGradientType;
        this.styleID++;
    }
};

/**
 * If fill is an array of colours to create a gradient, this array can set the stop points
 * (numbers between 0 and 1) for the color, overriding the default behaviour of evenly spacing them.
 *
 * @member {number[]}
 */
prototypeAccessors.fillGradientStops.get = function ()
{
    return this._fillGradientStops;
};
prototypeAccessors.fillGradientStops.set = function (fillGradientStops) // eslint-disable-line require-jsdoc
{
    if (!areArraysEqual(this._fillGradientStops,fillGradientStops))
    {
        this._fillGradientStops = fillGradientStops;
        this.styleID++;
    }
};

/**
 * The font family
 *
 * @member {string|string[]}
 */
prototypeAccessors.fontFamily.get = function ()
{
    return this._fontFamily;
};
prototypeAccessors.fontFamily.set = function (fontFamily) // eslint-disable-line require-jsdoc
{
    if (this.fontFamily !== fontFamily)
    {
        this._fontFamily = fontFamily;
        this.styleID++;
    }
};

/**
 * The font size
 * (as a number it converts to px, but as a string, equivalents are '26px','20pt','160%' or '1.6em')
 *
 * @member {number|string}
 */
prototypeAccessors.fontSize.get = function ()
{
    return this._fontSize;
};
prototypeAccessors.fontSize.set = function (fontSize) // eslint-disable-line require-jsdoc
{
    if (this._fontSize !== fontSize)
    {
        this._fontSize = fontSize;
        this.styleID++;
    }
};

/**
 * The font style
 * ('normal', 'italic' or 'oblique')
 *
 * @member {string}
 */
prototypeAccessors.fontStyle.get = function ()
{
    return this._fontStyle;
};
prototypeAccessors.fontStyle.set = function (fontStyle) // eslint-disable-line require-jsdoc
{
    if (this._fontStyle !== fontStyle)
    {
        this._fontStyle = fontStyle;
        this.styleID++;
    }
};

/**
 * The font variant
 * ('normal' or 'small-caps')
 *
 * @member {string}
 */
prototypeAccessors.fontVariant.get = function ()
{
    return this._fontVariant;
};
prototypeAccessors.fontVariant.set = function (fontVariant) // eslint-disable-line require-jsdoc
{
    if (this._fontVariant !== fontVariant)
    {
        this._fontVariant = fontVariant;
        this.styleID++;
    }
};

/**
 * The font weight
 * ('normal', 'bold', 'bolder', 'lighter' and '100', '200', '300', '400', '500', '600', '700', 800' or '900')
 *
 * @member {string}
 */
prototypeAccessors.fontWeight.get = function ()
{
    return this._fontWeight;
};
prototypeAccessors.fontWeight.set = function (fontWeight) // eslint-disable-line require-jsdoc
{
    if (this._fontWeight !== fontWeight)
    {
        this._fontWeight = fontWeight;
        this.styleID++;
    }
};

/**
 * The amount of spacing between letters, default is 0
 *
 * @member {number}
 */
prototypeAccessors.letterSpacing.get = function ()
{
    return this._letterSpacing;
};
prototypeAccessors.letterSpacing.set = function (letterSpacing) // eslint-disable-line require-jsdoc
{
    if (this._letterSpacing !== letterSpacing)
    {
        this._letterSpacing = letterSpacing;
        this.styleID++;
    }
};

/**
 * The line height, a number that represents the vertical space that a letter uses
 *
 * @member {number}
 */
prototypeAccessors.lineHeight.get = function ()
{
    return this._lineHeight;
};
prototypeAccessors.lineHeight.set = function (lineHeight) // eslint-disable-line require-jsdoc
{
    if (this._lineHeight !== lineHeight)
    {
        this._lineHeight = lineHeight;
        this.styleID++;
    }
};

/**
 * The space between lines
 *
 * @member {number}
 */
prototypeAccessors.leading.get = function ()
{
    return this._leading;
};
prototypeAccessors.leading.set = function (leading) // eslint-disable-line require-jsdoc
{
    if (this._leading !== leading)
    {
        this._leading = leading;
        this.styleID++;
    }
};

/**
 * The lineJoin property sets the type of corner created, it can resolve spiked text issues.
 * Default is 'miter' (creates a sharp corner).
 *
 * @member {string}
 */
prototypeAccessors.lineJoin.get = function ()
{
    return this._lineJoin;
};
prototypeAccessors.lineJoin.set = function (lineJoin) // eslint-disable-line require-jsdoc
{
    if (this._lineJoin !== lineJoin)
    {
        this._lineJoin = lineJoin;
        this.styleID++;
    }
};

/**
 * The miter limit to use when using the 'miter' lineJoin mode
 * This can reduce or increase the spikiness of rendered text.
 *
 * @member {number}
 */
prototypeAccessors.miterLimit.get = function ()
{
    return this._miterLimit;
};
prototypeAccessors.miterLimit.set = function (miterLimit) // eslint-disable-line require-jsdoc
{
    if (this._miterLimit !== miterLimit)
    {
        this._miterLimit = miterLimit;
        this.styleID++;
    }
};

/**
 * Occasionally some fonts are cropped. Adding some padding will prevent this from happening
 * by adding padding to all sides of the text.
 *
 * @member {number}
 */
prototypeAccessors.padding.get = function ()
{
    return this._padding;
};
prototypeAccessors.padding.set = function (padding) // eslint-disable-line require-jsdoc
{
    if (this._padding !== padding)
    {
        this._padding = padding;
        this.styleID++;
    }
};

/**
 * A canvas fillstyle that will be used on the text stroke
 * e.g 'blue', '#FCFF00'
 *
 * @member {string|number}
 */
prototypeAccessors.stroke.get = function ()
{
    return this._stroke;
};
prototypeAccessors.stroke.set = function (stroke) // eslint-disable-line require-jsdoc
{
    var outputColor = getColor(stroke);
    if (this._stroke !== outputColor)
    {
        this._stroke = outputColor;
        this.styleID++;
    }
};

/**
 * A number that represents the thickness of the stroke.
 * Default is 0 (no stroke)
 *
 * @member {number}
 */
prototypeAccessors.strokeThickness.get = function ()
{
    return this._strokeThickness;
};
prototypeAccessors.strokeThickness.set = function (strokeThickness) // eslint-disable-line require-jsdoc
{
    if (this._strokeThickness !== strokeThickness)
    {
        this._strokeThickness = strokeThickness;
        this.styleID++;
    }
};

/**
 * The baseline of the text that is rendered.
 *
 * @member {string}
 */
prototypeAccessors.textBaseline.get = function ()
{
    return this._textBaseline;
};
prototypeAccessors.textBaseline.set = function (textBaseline) // eslint-disable-line require-jsdoc
{
    if (this._textBaseline !== textBaseline)
    {
        this._textBaseline = textBaseline;
        this.styleID++;
    }
};

/**
 * Trim transparent borders
 *
 * @member {boolean}
 */
prototypeAccessors.trim.get = function ()
{
    return this._trim;
};
prototypeAccessors.trim.set = function (trim) // eslint-disable-line require-jsdoc
{
    if (this._trim !== trim)
    {
        this._trim = trim;
        this.styleID++;
    }
};

/**
 * How newlines and spaces should be handled.
 * Default is 'pre' (preserve, preserve).
 *
 *  value   | New lines |   Spaces
 *  ---     | ---       |   ---
 * 'normal' | Collapse  |   Collapse
 * 'pre'    | Preserve  |   Preserve
 * 'pre-line'   | Preserve  |   Collapse
 *
 * @member {string}
 */
prototypeAccessors.whiteSpace.get = function ()
{
    return this._whiteSpace;
};
prototypeAccessors.whiteSpace.set = function (whiteSpace) // eslint-disable-line require-jsdoc
{
    if (this._whiteSpace !== whiteSpace)
    {
        this._whiteSpace = whiteSpace;
        this.styleID++;
    }
};

/**
 * Indicates if word wrap should be used
 *
 * @member {boolean}
 */
prototypeAccessors.wordWrap.get = function ()
{
    return this._wordWrap;
};
prototypeAccessors.wordWrap.set = function (wordWrap) // eslint-disable-line require-jsdoc
{
    if (this._wordWrap !== wordWrap)
    {
        this._wordWrap = wordWrap;
        this.styleID++;
    }
};

/**
 * The width at which text will wrap, it needs wordWrap to be set to true
 *
 * @member {number}
 */
prototypeAccessors.wordWrapWidth.get = function ()
{
    return this._wordWrapWidth;
};
prototypeAccessors.wordWrapWidth.set = function (wordWrapWidth) // eslint-disable-line require-jsdoc
{
    if (this._wordWrapWidth !== wordWrapWidth)
    {
        this._wordWrapWidth = wordWrapWidth;
        this.styleID++;
    }
};

/**
 * Generates a font style string to use for `TextMetrics.measureFont()`.
 *
 * @return {string} Font style string, for passing to `TextMetrics.measureFont()`
 */
TextStyle.prototype.toFontString = function toFontString ()
{
    // build canvas api font setting from individual components. Convert a numeric this.fontSize to px
    var fontSizeString = (typeof this.fontSize === 'number') ? ((this.fontSize) + "px") : this.fontSize;

    // Clean-up fontFamily property by quoting each font name
    // this will support font names with spaces
    var fontFamilies = this.fontFamily;

    if (!Array.isArray(this.fontFamily))
    {
        fontFamilies = this.fontFamily.split(',');
    }

    for (var i = fontFamilies.length - 1; i >= 0; i--)
    {
        // Trim any extra white-space
        var fontFamily = fontFamilies[i].trim();

        // Check if font already contains strings
        if (!(/([\"\'])[^\'\"]+\1/).test(fontFamily))
        {
            fontFamily = "\"" + fontFamily + "\"";
        }
        fontFamilies[i] = fontFamily;
    }

    return ((this.fontStyle) + " " + (this.fontVariant) + " " + (this.fontWeight) + " " + fontSizeString + " " + (fontFamilies.join(',')));
};

Object.defineProperties( TextStyle.prototype, prototypeAccessors );

/**
 * Utility function to convert hexadecimal colors to strings, and simply return the color if it's a string.
 * @private
 * @param {number|number[]} color
 * @return {string} The color as a string.
 */
function getSingleColor(color)
{
    if (typeof color === 'number')
    {
        return hex2string(color);
    }
    else if ( typeof color === 'string' )
    {
        if ( color.indexOf('0x') === 0 )
        {
            color = color.replace('0x', '#');
        }
    }

    return color;
}

/**
 * Utility function to convert hexadecimal colors to strings, and simply return the color if it's a string.
 * This version can also convert array of colors
 * @private
 * @param {number|number[]} color
 * @return {string} The color as a string.
 */
function getColor(color)
{
    if (!Array.isArray(color))
    {
        return getSingleColor(color);
    }
    else
    {
        for (var i = 0; i < color.length; ++i)
        {
            color[i] = getSingleColor(color[i]);
        }

        return color;
    }
}

/**
 * Utility function to convert hexadecimal colors to strings, and simply return the color if it's a string.
 * This version can also convert array of colors
 * @private
 * @param {Array} array1 First array to compare
 * @param {Array} array2 Second array to compare
 * @return {boolean} Do the arrays contain the same values in the same order
 */
function areArraysEqual(array1, array2)
{
    if (!Array.isArray(array1) || !Array.isArray(array2))
    {
        return false;
    }

    if (array1.length !== array2.length)
    {
        return false;
    }

    for (var i = 0; i < array1.length; ++i)
    {
        if (array1[i] !== array2[i])
        {
            return false;
        }
    }

    return true;
}

/**
 * Utility function to ensure that object properties are copied by value, and not by reference
 * @private
 * @param {Object} target Target object to copy properties into
 * @param {Object} source Source object for the properties to copy
 * @param {string} propertyObj Object containing properties names we want to loop over
 */
function deepCopyProperties(target, source, propertyObj) {
    for (var prop in propertyObj) {
        if (Array.isArray(source[prop])) {
            target[prop] = source[prop].slice();
        } else {
            target[prop] = source[prop];
        }
    }
}

/**
 * The TextMetrics object represents the measurement of a block of text with a specified style.
 *
 * ```js
 * let style = new PIXI.TextStyle({fontFamily : 'Arial', fontSize: 24, fill : 0xff1010, align : 'center'})
 * let textMetrics = PIXI.TextMetrics.measureText('Your text', style)
 * ```
 *
 * @class
 * @memberof PIXI
 */
var TextMetrics = function TextMetrics(text, style, width, height, lines, lineWidths, lineHeight, maxLineWidth, fontProperties)
{
    this.text = text;
    this.style = style;
    this.width = width;
    this.height = height;
    this.lines = lines;
    this.lineWidths = lineWidths;
    this.lineHeight = lineHeight;
    this.maxLineWidth = maxLineWidth;
    this.fontProperties = fontProperties;
};

/**
 * Measures the supplied string of text and returns a Rectangle.
 *
 * @param {string} text - the text to measure.
 * @param {PIXI.TextStyle} style - the text style to use for measuring
 * @param {boolean} [wordWrap] - optional override for if word-wrap should be applied to the text.
 * @param {HTMLCanvasElement} [canvas] - optional specification of the canvas to use for measuring.
 * @return {PIXI.TextMetrics} measured width and height of the text.
 */
TextMetrics.measureText = function measureText (text, style, wordWrap, canvas)
{
        if ( canvas === void 0 ) canvas = TextMetrics._canvas;

    wordWrap = (wordWrap === undefined || wordWrap === null) ? style.wordWrap : wordWrap;
    var font = style.toFontString();
    var fontProperties = TextMetrics.measureFont(font);

    // fallback in case UA disallow canvas data extraction
    // (toDataURI, getImageData functions)
    if (fontProperties.fontSize === 0)
    {
        fontProperties.fontSize = style.fontSize;
        fontProperties.ascent = style.fontSize;
    }

    var context = canvas.getContext('2d');

    context.font = font;

    var outputText = wordWrap ? TextMetrics.wordWrap(text, style, canvas) : text;
    var lines = outputText.split(/(?:\r\n|\r|\n)/);
    var lineWidths = new Array(lines.length);
    var maxLineWidth = 0;

    for (var i = 0; i < lines.length; i++)
    {
        var lineWidth = context.measureText(lines[i]).width + ((lines[i].length - 1) * style.letterSpacing);

        lineWidths[i] = lineWidth;
        maxLineWidth = Math.max(maxLineWidth, lineWidth);
    }
    var width = maxLineWidth + style.strokeThickness;

    if (style.dropShadow)
    {
        width += style.dropShadowDistance;
    }

    var lineHeight = style.lineHeight || fontProperties.fontSize + style.strokeThickness;
    var height = Math.max(lineHeight, fontProperties.fontSize + style.strokeThickness)
        + ((lines.length - 1) * (lineHeight + style.leading));

    if (style.dropShadow)
    {
        height += style.dropShadowDistance;
    }

    return new TextMetrics(
        text,
        style,
        width,
        height,
        lines,
        lineWidths,
        lineHeight + style.leading,
        maxLineWidth,
        fontProperties
    );
};

/**
 * Applies newlines to a string to have it optimally fit into the horizontal
 * bounds set by the Text object's wordWrapWidth property.
 *
 * @private
 * @param {string} text - String to apply word wrapping to
 * @param {PIXI.TextStyle} style - the style to use when wrapping
 * @param {HTMLCanvasElement} [canvas] - optional specification of the canvas to use for measuring.
 * @return {string} New string with new lines applied where required
 */
TextMetrics.wordWrap = function wordWrap (text, style, canvas)
{
        if ( canvas === void 0 ) canvas = TextMetrics._canvas;

    var context = canvas.getContext('2d');

    var width = 0;
    var line = '';
    var lines = '';

    var cache = {};
    var letterSpacing = style.letterSpacing;
        var whiteSpace = style.whiteSpace;

    // How to handle whitespaces
    var collapseSpaces = TextMetrics.collapseSpaces(whiteSpace);
    var collapseNewlines = TextMetrics.collapseNewlines(whiteSpace);

    // whether or not spaces may be added to the beginning of lines
    var canPrependSpaces = !collapseSpaces;

    // There is letterSpacing after every char except the last one
    // t_h_i_s_' '_i_s_' '_a_n_' '_e_x_a_m_p_l_e_' '_!
    // so for convenience the above needs to be compared to width + 1 extra letterSpace
    // t_h_i_s_' '_i_s_' '_a_n_' '_e_x_a_m_p_l_e_' '_!_
    // ________________________________________________
    // And then the final space is simply no appended to each line
    var wordWrapWidth = style.wordWrapWidth + letterSpacing;

    // break text into words, spaces and newline chars
    var tokens = TextMetrics.tokenize(text);

    for (var i = 0; i < tokens.length; i++)
    {
        // get the word, space or newlineChar
        var token = tokens[i];

        // if word is a new line
        if (TextMetrics.isNewline(token))
        {
            // keep the new line
            if (!collapseNewlines)
            {
                lines += TextMetrics.addLine(line);
                canPrependSpaces = !collapseSpaces;
                line = '';
                width = 0;
                continue;
            }

            // if we should collapse new lines
            // we simply convert it into a space
            token = ' ';
        }

        // if we should collapse repeated whitespaces
        if (collapseSpaces)
        {
            // check both this and the last tokens for spaces
            var currIsBreakingSpace = TextMetrics.isBreakingSpace(token);
            var lastIsBreakingSpace = TextMetrics.isBreakingSpace(line[line.length - 1]);

            if (currIsBreakingSpace && lastIsBreakingSpace)
            {
                continue;
            }
        }

        // get word width from cache if possible
        var tokenWidth = TextMetrics.getFromCache(token, letterSpacing, cache, context);

        // word is longer than desired bounds
        if (tokenWidth > wordWrapWidth)
        {
            // if we are not already at the beginning of a line
            if (line !== '')
            {
                // start newlines for overflow words
                lines += TextMetrics.addLine(line);
                line = '';
                width = 0;
            }

            // break large word over multiple lines
            if (TextMetrics.canBreakWords(token, style.breakWords))
            {
                // break word into characters
                var characters = token.split('');

                // loop the characters
                for (var j = 0; j < characters.length; j++)
                {
                    var char = characters[j];

                    var k = 1;
                    // we are not at the end of the token

                    while (characters[j + k])
                    {
                        var nextChar = characters[j + k];
                        var lastChar = char[char.length - 1];

                        // should not split chars
                        if (!TextMetrics.canBreakChars(lastChar, nextChar, token, j, style.breakWords))
                        {
                            // combine chars & move forward one
                            char += nextChar;
                        }
                        else
                        {
                            break;
                        }

                        k++;
                    }

                    j += char.length - 1;

                    var characterWidth = TextMetrics.getFromCache(char, letterSpacing, cache, context);

                    if (characterWidth + width > wordWrapWidth)
                    {
                        lines += TextMetrics.addLine(line);
                        canPrependSpaces = false;
                        line = '';
                        width = 0;
                    }

                    line += char;
                    width += characterWidth;
                }
            }

            // run word out of the bounds
            else
            {
                // if there are words in this line already
                // finish that line and start a new one
                if (line.length > 0)
                {
                    lines += TextMetrics.addLine(line);
                    line = '';
                    width = 0;
                }

                var isLastToken = i === tokens.length - 1;

                // give it its own line if it's not the end
                lines += TextMetrics.addLine(token, !isLastToken);
                canPrependSpaces = false;
                line = '';
                width = 0;
            }
        }

        // word could fit
        else
        {
            // word won't fit because of existing words
            // start a new line
            if (tokenWidth + width > wordWrapWidth)
            {
                // if its a space we don't want it
                canPrependSpaces = false;

                // add a new line
                lines += TextMetrics.addLine(line);

                // start a new line
                line = '';
                width = 0;
            }

            // don't add spaces to the beginning of lines
            if (line.length > 0 || !TextMetrics.isBreakingSpace(token) || canPrependSpaces)
            {
                // add the word to the current line
                line += token;

                // update width counter
                width += tokenWidth;
            }
        }
    }

    lines += TextMetrics.addLine(line, false);

    return lines;
};

/**
 * Convienience function for logging each line added during the wordWrap
 * method
 *
 * @private
 * @param  {string}   line    - The line of text to add
 * @param  {boolean}  newLine - Add new line character to end
 * @return {string}   A formatted line
 */
TextMetrics.addLine = function addLine (line, newLine)
{
        if ( newLine === void 0 ) newLine = true;

    line = TextMetrics.trimRight(line);

    line = (newLine) ? (line + "\n") : line;

    return line;
};

/**
 * Gets & sets the widths of calculated characters in a cache object
 *
 * @private
 * @param  {string}                key        The key
 * @param  {number}                letterSpacing  The letter spacing
 * @param  {object}                cache      The cache
 * @param  {CanvasRenderingContext2D}  context    The canvas context
 * @return {number}                The from cache.
 */
TextMetrics.getFromCache = function getFromCache (key, letterSpacing, cache, context)
{
    var width = cache[key];

    if (width === undefined)
    {
        var spacing = ((key.length) * letterSpacing);

        width = context.measureText(key).width + spacing;
        cache[key] = width;
    }

    return width;
};

/**
 * Determines whether we should collapse breaking spaces
 *
 * @private
 * @param  {string}   whiteSpace  The TextStyle property whiteSpace
 * @return {boolean}  should collapse
 */
TextMetrics.collapseSpaces = function collapseSpaces (whiteSpace)
{
    return (whiteSpace === 'normal' || whiteSpace === 'pre-line');
};

/**
 * Determines whether we should collapse newLine chars
 *
 * @private
 * @param  {string}   whiteSpace  The white space
 * @return {boolean}  should collapse
 */
TextMetrics.collapseNewlines = function collapseNewlines (whiteSpace)
{
    return (whiteSpace === 'normal');
};

/**
 * trims breaking whitespaces from string
 *
 * @private
 * @param  {string}  text  The text
 * @return {string}  trimmed string
 */
TextMetrics.trimRight = function trimRight (text)
{
    if (typeof text !== 'string')
    {
        return '';
    }

    for (var i = text.length - 1; i >= 0; i--)
    {
        var char = text[i];

        if (!TextMetrics.isBreakingSpace(char))
        {
            break;
        }

        text = text.slice(0, -1);
    }

    return text;
};

/**
 * Determines if char is a newline.
 *
 * @private
 * @param  {string}  char  The character
 * @return {boolean}  True if newline, False otherwise.
 */
TextMetrics.isNewline = function isNewline (char)
{
    if (typeof char !== 'string')
    {
        return false;
    }

    return (TextMetrics._newlines.indexOf(char.charCodeAt(0)) >= 0);
};

/**
 * Determines if char is a breaking whitespace.
 *
 * @private
 * @param  {string}  char  The character
 * @return {boolean}  True if whitespace, False otherwise.
 */
TextMetrics.isBreakingSpace = function isBreakingSpace (char)
{
    if (typeof char !== 'string')
    {
        return false;
    }

    return (TextMetrics._breakingSpaces.indexOf(char.charCodeAt(0)) >= 0);
};

/**
 * Splits a string into words, breaking-spaces and newLine characters
 *
 * @private
 * @param  {string}  text   The text
 * @return {string[]}  A tokenized array
 */
TextMetrics.tokenize = function tokenize (text)
{
    var tokens = [];
    var token = '';

    if (typeof text !== 'string')
    {
        return tokens;
    }

    for (var i = 0; i < text.length; i++)
    {
        var char = text[i];

        if (TextMetrics.isBreakingSpace(char) || TextMetrics.isNewline(char))
        {
            if (token !== '')
            {
                tokens.push(token);
                token = '';
            }

            tokens.push(char);

            continue;
        }

        token += char;
    }

    if (token !== '')
    {
        tokens.push(token);
    }

    return tokens;
};

/**
 * This method exists to be easily overridden
 * It allows one to customise which words should break
 * Examples are if the token is CJK or numbers.
 * It must return a boolean.
 *
 * @private
 * @param  {string}  token   The token
 * @param  {boolean}  breakWords  The style attr break words
 * @return {boolean} whether to break word or not
 */
TextMetrics.canBreakWords = function canBreakWords (token, breakWords)
{
    return breakWords;
};

/**
 * This method exists to be easily overridden
 * It allows one to determine whether a pair of characters
 * should be broken by newlines
 * For example certain characters in CJK langs or numbers.
 * It must return a boolean.
 *
 * @private
 * @param  {string}  char  The character
 * @param  {string}  nextChar  The next character
 * @param  {string}  token The token/word the characters are from
 * @param  {number}  index The index in the token of the char
 * @param  {boolean}  breakWords  The style attr break words
 * @return {boolean} whether to break word or not
 */
TextMetrics.canBreakChars = function canBreakChars (char, nextChar, token, index, breakWords) // eslint-disable-line no-unused-vars
{
    return true;
};

/**
 * Calculates the ascent, descent and fontSize of a given font-style
 *
 * @static
 * @param {string} font - String representing the style of the font
 * @return {PIXI.IFontMetrics} Font properties object
 */
TextMetrics.measureFont = function measureFont (font)
{
    // as this method is used for preparing assets, don't recalculate things if we don't need to
    if (TextMetrics._fonts[font])
    {
        return TextMetrics._fonts[font];
    }

    var properties = {};

    var canvas = TextMetrics._canvas;
    var context = TextMetrics._context;

    context.font = font;

    var metricsString = TextMetrics.METRICS_STRING + TextMetrics.BASELINE_SYMBOL;
    var width = Math.ceil(context.measureText(metricsString).width);
    var baseline = Math.ceil(context.measureText(TextMetrics.BASELINE_SYMBOL).width);
    var height = 2 * baseline;

    baseline = baseline * TextMetrics.BASELINE_MULTIPLIER | 0;

    canvas.width = width;
    canvas.height = height;

    context.fillStyle = '#f00';
    context.fillRect(0, 0, width, height);

    context.font = font;

    context.textBaseline = 'alphabetic';
    context.fillStyle = '#000';
    context.fillText(metricsString, 0, baseline);

    var imagedata = context.getImageData(0, 0, width, height).data;
    var pixels = imagedata.length;
    var line = width * 4;

    var i = 0;
    var idx = 0;
    var stop = false;

    // ascent. scan from top to bottom until we find a non red pixel
    for (i = 0; i < baseline; ++i)
    {
        for (var j = 0; j < line; j += 4)
        {
            if (imagedata[idx + j] !== 255)
            {
                stop = true;
                break;
            }
        }
        if (!stop)
        {
            idx += line;
        }
        else
        {
            break;
        }
    }

    properties.ascent = baseline - i;

    idx = pixels - line;
    stop = false;

    // descent. scan from bottom to top until we find a non red pixel
    for (i = height; i > baseline; --i)
    {
        for (var j$1 = 0; j$1 < line; j$1 += 4)
        {
            if (imagedata[idx + j$1] !== 255)
            {
                stop = true;
                break;
            }
        }

        if (!stop)
        {
            idx -= line;
        }
        else
        {
            break;
        }
    }

    properties.descent = i - baseline;
    properties.fontSize = properties.ascent + properties.descent;

    TextMetrics._fonts[font] = properties;

    return properties;
};

/**
 * Clear font metrics in metrics cache.
 *
 * @static
 * @param {string} [font] - font name. If font name not set then clear cache for all fonts.
 */
TextMetrics.clearMetrics = function clearMetrics (font)
{
        if ( font === void 0 ) font = '';

    if (font)
    {
        delete TextMetrics._fonts[font];
    }
    else
    {
        TextMetrics._fonts = {};
    }
};

/**
 * Internal return object for {@link PIXI.TextMetrics.measureFont `TextMetrics.measureFont`}.
 *
 * @typedef {object} FontMetrics
 * @property {number} ascent - The ascent distance
 * @property {number} descent - The descent distance
 * @property {number} fontSize - Font size from ascent to descent
 * @memberof PIXI.TextMetrics
 * @private
 */

var canvas = document.createElement('canvas');

canvas.width = canvas.height = 10;

/**
 * Cached canvas element for measuring text
 * @memberof PIXI.TextMetrics
 * @type {HTMLCanvasElement}
 * @private
 */
TextMetrics._canvas = canvas;

/**
 * Cache for context to use.
 * @memberof PIXI.TextMetrics
 * @type {CanvasRenderingContext2D}
 * @private
 */
TextMetrics._context = canvas.getContext('2d');

/**
 * Cache of {@see PIXI.TextMetrics.FontMetrics} objects.
 * @memberof PIXI.TextMetrics
 * @type {Object}
 * @private
 */
TextMetrics._fonts = {};

/**
 * String used for calculate font metrics.
 * @static
 * @memberof PIXI.TextMetrics
 * @name METRICS_STRING
 * @type {string}
 * @default |Éq
 */
TextMetrics.METRICS_STRING = '|Éq';

/**
 * Baseline symbol for calculate font metrics.
 * @static
 * @memberof PIXI.TextMetrics
 * @name BASELINE_SYMBOL
 * @type {string}
 * @default M
 */
TextMetrics.BASELINE_SYMBOL = 'M';

/**
 * Baseline multiplier for calculate font metrics.
 * @static
 * @memberof PIXI.TextMetrics
 * @name BASELINE_MULTIPLIER
 * @type {number}
 * @default 1.4
 */
TextMetrics.BASELINE_MULTIPLIER = 1.4;

/**
 * Cache of new line chars.
 * @memberof PIXI.TextMetrics
 * @type {number[]}
 * @private
 */
TextMetrics._newlines = [
    0x000A, // line feed
    0x000D ];

/**
 * Cache of breaking spaces.
 * @memberof PIXI.TextMetrics
 * @type {number[]}
 * @private
 */
TextMetrics._breakingSpaces = [
    0x0009, // character tabulation
    0x0020, // space
    0x2000, // en quad
    0x2001, // em quad
    0x2002, // en space
    0x2003, // em space
    0x2004, // three-per-em space
    0x2005, // four-per-em space
    0x2006, // six-per-em space
    0x2008, // punctuation space
    0x2009, // thin space
    0x200A, // hair space
    0x205F, // medium mathematical space
    0x3000 ];

/**
 * A number, or a string containing a number.
 * @memberof PIXI
 * @typedef IFontMetrics
 * @property {number} ascent - Font ascent
 * @property {number} descent - Font descent
 * @property {number} fontSize - Font size
 */

/* eslint max-depth: [2, 8] */

var defaultDestroyOptions = {
    texture: true,
    children: false,
    baseTexture: true,
};

/**
 * A Text Object will create a line or multiple lines of text.
 *
 * The text is created using the [Canvas API](https://developer.mozilla.org/en-US/docs/Web/API/Canvas_API).
 *
 * The primary advantage of this class over BitmapText is that you have great control over the style of the next,
 * which you can change at runtime.
 *
 * The primary disadvantages is that each piece of text has it's own texture, which can use more memory.
 * When text changes, this texture has to be re-generated and re-uploaded to the GPU, taking up time.
 *
 * To split a line you can use '\n' in your text string, or, on the `style` object,
 * change its `wordWrap` property to true and and give the `wordWrapWidth` property a value.
 *
 * A Text can be created directly from a string and a style object,
 * which can be generated [here](https://pixijs.io/pixi-text-style).
 *
 * ```js
 * let text = new PIXI.Text('This is a PixiJS text',{fontFamily : 'Arial', fontSize: 24, fill : 0xff1010, align : 'center'});
 * ```
 *
 * @class
 * @extends PIXI.Sprite
 * @memberof PIXI
 */
var Text = /*@__PURE__*/(function (Sprite) {
    function Text(text, style, canvas)
    {
        canvas = canvas || document.createElement('canvas');

        canvas.width = 3;
        canvas.height = 3;

        var texture = Texture.from(canvas);

        texture.orig = new Rectangle();
        texture.trim = new Rectangle();

        Sprite.call(this, texture);

        /**
         * The canvas element that everything is drawn to
         *
         * @member {HTMLCanvasElement}
         */
        this.canvas = canvas;

        /**
         * The canvas 2d context that everything is drawn with
         * @member {CanvasRenderingContext2D}
         */
        this.context = this.canvas.getContext('2d');

        /**
         * The resolution / device pixel ratio of the canvas.
         * This is set to automatically match the renderer resolution by default, but can be overridden by setting manually.
         * @member {number}
         * @default 1
         */
        this._resolution = settings.RESOLUTION;
        this._autoResolution = true;

        /**
         * Private tracker for the current text.
         *
         * @member {string}
         * @private
         */
        this._text = null;

        /**
         * Private tracker for the current style.
         *
         * @member {object}
         * @private
         */
        this._style = null;
        /**
         * Private listener to track style changes.
         *
         * @member {Function}
         * @private
         */
        this._styleListener = null;

        /**
         * Private tracker for the current font.
         *
         * @member {string}
         * @private
         */
        this._font = '';

        this.text = text;
        this.style = style;

        this.localStyleID = -1;
    }

    if ( Sprite ) Text.__proto__ = Sprite;
    Text.prototype = Object.create( Sprite && Sprite.prototype );
    Text.prototype.constructor = Text;

    var prototypeAccessors = { width: { configurable: true },height: { configurable: true },style: { configurable: true },text: { configurable: true },resolution: { configurable: true } };

    /**
     * Renders text and updates it when needed.
     *
     * @private
     * @param {boolean} respectDirty - Whether to abort updating the text if the Text isn't dirty and the function is called.
     */
    Text.prototype.updateText = function updateText (respectDirty)
    {
        var style = this._style;

        // check if style has changed..
        if (this.localStyleID !== style.styleID)
        {
            this.dirty = true;
            this.localStyleID = style.styleID;
        }

        if (!this.dirty && respectDirty)
        {
            return;
        }

        this._font = this._style.toFontString();

        var context = this.context;
        var measured = TextMetrics.measureText(this._text || ' ', this._style, this._style.wordWrap, this.canvas);
        var width = measured.width;
        var height = measured.height;
        var lines = measured.lines;
        var lineHeight = measured.lineHeight;
        var lineWidths = measured.lineWidths;
        var maxLineWidth = measured.maxLineWidth;
        var fontProperties = measured.fontProperties;

        this.canvas.width = Math.ceil((Math.max(1, width) + (style.padding * 2)) * this._resolution);
        this.canvas.height = Math.ceil((Math.max(1, height) + (style.padding * 2)) * this._resolution);

        context.scale(this._resolution, this._resolution);

        context.clearRect(0, 0, this.canvas.width, this.canvas.height);

        context.font = this._font;
        context.strokeStyle = style.stroke;
        context.lineWidth = style.strokeThickness;
        context.textBaseline = style.textBaseline;
        context.lineJoin = style.lineJoin;
        context.miterLimit = style.miterLimit;

        var linePositionX;
        var linePositionY;

        if (style.dropShadow)
        {
            var dropShadowColor = style.dropShadowColor;
            var rgb = hex2rgb(typeof dropShadowColor === 'number' ? dropShadowColor : string2hex(dropShadowColor));

            context.shadowColor = "rgba(" + (rgb[0] * 255) + "," + (rgb[1] * 255) + "," + (rgb[2] * 255) + "," + (style.dropShadowAlpha) + ")";
            context.shadowBlur = style.dropShadowBlur;
            context.shadowOffsetX = Math.cos(style.dropShadowAngle) * style.dropShadowDistance;
            context.shadowOffsetY = Math.sin(style.dropShadowAngle) * style.dropShadowDistance;
        }
        else
        {
            context.shadowColor = 0;
            context.shadowBlur = 0;
            context.shadowOffsetX = 0;
            context.shadowOffsetY = 0;
        }

        // set canvas text styles
        context.fillStyle = this._generateFillStyle(style, lines);

        // draw lines line by line
        for (var i = 0; i < lines.length; i++)
        {
            linePositionX = style.strokeThickness / 2;
            linePositionY = ((style.strokeThickness / 2) + (i * lineHeight)) + fontProperties.ascent;

            if (style.align === 'right')
            {
                linePositionX += maxLineWidth - lineWidths[i];
            }
            else if (style.align === 'center')
            {
                linePositionX += (maxLineWidth - lineWidths[i]) / 2;
            }

            if (style.stroke && style.strokeThickness)
            {
                this.drawLetterSpacing(
                    lines[i],
                    linePositionX + style.padding,
                    linePositionY + style.padding,
                    true
                );
            }

            if (style.fill)
            {
                this.drawLetterSpacing(
                    lines[i],
                    linePositionX + style.padding,
                    linePositionY + style.padding
                );
            }
        }

        this.updateTexture();
    };

    /**
     * Render the text with letter-spacing.
     * @param {string} text - The text to draw
     * @param {number} x - Horizontal position to draw the text
     * @param {number} y - Vertical position to draw the text
     * @param {boolean} [isStroke=false] - Is this drawing for the outside stroke of the
     *  text? If not, it's for the inside fill
     * @private
     */
    Text.prototype.drawLetterSpacing = function drawLetterSpacing (text, x, y, isStroke)
    {
        if ( isStroke === void 0 ) isStroke = false;

        var style = this._style;

        // letterSpacing of 0 means normal
        var letterSpacing = style.letterSpacing;

        if (letterSpacing === 0)
        {
            if (isStroke)
            {
                this.context.strokeText(text, x, y);
            }
            else
            {
                this.context.fillText(text, x, y);
            }

            return;
        }

        var characters = String.prototype.split.call(text, '');
        var currentPosition = x;
        var index = 0;
        var current = '';

        while (index < text.length)
        {
            current = characters[index++];
            if (isStroke)
            {
                this.context.strokeText(current, currentPosition, y);
            }
            else
            {
                this.context.fillText(current, currentPosition, y);
            }
            currentPosition += this.context.measureText(current).width + letterSpacing;
        }
    };

    /**
     * Updates texture size based on canvas size
     *
     * @private
     */
    Text.prototype.updateTexture = function updateTexture ()
    {
        var canvas = this.canvas;

        if (this._style.trim)
        {
            var trimmed = trimCanvas(canvas);

            if (trimmed.data)
            {
                canvas.width = trimmed.width;
                canvas.height = trimmed.height;
                this.context.putImageData(trimmed.data, 0, 0);
            }
        }

        var texture = this._texture;
        var style = this._style;
        var padding = style.trim ? 0 : style.padding;
        var baseTexture = texture.baseTexture;

        texture.trim.width = texture._frame.width = canvas.width / this._resolution;
        texture.trim.height = texture._frame.height = canvas.height / this._resolution;
        texture.trim.x = -padding;
        texture.trim.y = -padding;

        texture.orig.width = texture._frame.width - (padding * 2);
        texture.orig.height = texture._frame.height - (padding * 2);

        // call sprite onTextureUpdate to update scale if _width or _height were set
        this._onTextureUpdate();

        baseTexture.setRealSize(canvas.width, canvas.height, this._resolution);

        this.dirty = false;
    };

    /**
     * Renders the object using the WebGL renderer
     *
     * @param {PIXI.Renderer} renderer - The renderer
     */
    Text.prototype.render = function render (renderer)
    {
        if (this._autoResolution && this._resolution !== renderer.resolution)
        {
            this._resolution = renderer.resolution;
            this.dirty = true;
        }

        this.updateText(true);

        Sprite.prototype.render.call(this, renderer);
    };

    /**
     * Renders the object using the Canvas renderer
     *
     * @private
     * @param {PIXI.CanvasRenderer} renderer - The renderer
     */
    Text.prototype._renderCanvas = function _renderCanvas (renderer)
    {
        if (this._autoResolution && this._resolution !== renderer.resolution)
        {
            this._resolution = renderer.resolution;
            this.dirty = true;
        }

        this.updateText(true);

        Sprite.prototype._renderCanvas.call(this, renderer);
    };

    /**
     * Gets the local bounds of the text object.
     *
     * @param {Rectangle} rect - The output rectangle.
     * @return {Rectangle} The bounds.
     */
    Text.prototype.getLocalBounds = function getLocalBounds (rect)
    {
        this.updateText(true);

        return Sprite.prototype.getLocalBounds.call(this, rect);
    };

    /**
     * calculates the bounds of the Text as a rectangle. The bounds calculation takes the worldTransform into account.
     * @protected
     */
    Text.prototype._calculateBounds = function _calculateBounds ()
    {
        this.updateText(true);
        this.calculateVertices();
        // if we have already done this on THIS frame.
        this._bounds.addQuad(this.vertexData);
    };

    /**
     * Method to be called upon a TextStyle change.
     * @private
     */
    Text.prototype._onStyleChange = function _onStyleChange ()
    {
        this.dirty = true;
    };

    /**
     * Generates the fill style. Can automatically generate a gradient based on the fill style being an array
     *
     * @private
     * @param {object} style - The style.
     * @param {string[]} lines - The lines of text.
     * @return {string|number|CanvasGradient} The fill style
     */
    Text.prototype._generateFillStyle = function _generateFillStyle (style, lines)
    {
        if (!Array.isArray(style.fill))
        {
            return style.fill;
        }

        // the gradient will be evenly spaced out according to how large the array is.
        // ['#FF0000', '#00FF00', '#0000FF'] would created stops at 0.25, 0.5 and 0.75
        var gradient;
        var totalIterations;
        var currentIteration;
        var stop;

        var width = this.canvas.width / this._resolution;
        var height = this.canvas.height / this._resolution;

        // make a copy of the style settings, so we can manipulate them later
        var fill = style.fill.slice();
        var fillGradientStops = style.fillGradientStops.slice();

        // wanting to evenly distribute the fills. So an array of 4 colours should give fills of 0.25, 0.5 and 0.75
        if (!fillGradientStops.length)
        {
            var lengthPlus1 = fill.length + 1;

            for (var i = 1; i < lengthPlus1; ++i)
            {
                fillGradientStops.push(i / lengthPlus1);
            }
        }

        // stop the bleeding of the last gradient on the line above to the top gradient of the this line
        // by hard defining the first gradient colour at point 0, and last gradient colour at point 1
        fill.unshift(style.fill[0]);
        fillGradientStops.unshift(0);

        fill.push(style.fill[style.fill.length - 1]);
        fillGradientStops.push(1);

        if (style.fillGradientType === TEXT_GRADIENT.LINEAR_VERTICAL)
        {
            // start the gradient at the top center of the canvas, and end at the bottom middle of the canvas
            gradient = this.context.createLinearGradient(width / 2, 0, width / 2, height);

            // we need to repeat the gradient so that each individual line of text has the same vertical gradient effect
            // ['#FF0000', '#00FF00', '#0000FF'] over 2 lines would create stops at 0.125, 0.25, 0.375, 0.625, 0.75, 0.875
            totalIterations = (fill.length + 1) * lines.length;
            currentIteration = 0;
            for (var i$1 = 0; i$1 < lines.length; i$1++)
            {
                currentIteration += 1;
                for (var j = 0; j < fill.length; j++)
                {
                    if (typeof fillGradientStops[j] === 'number')
                    {
                        stop = (fillGradientStops[j] / lines.length) + (i$1 / lines.length);
                    }
                    else
                    {
                        stop = currentIteration / totalIterations;
                    }
                    gradient.addColorStop(stop, fill[j]);
                    currentIteration++;
                }
            }
        }
        else
        {
            // start the gradient at the center left of the canvas, and end at the center right of the canvas
            gradient = this.context.createLinearGradient(0, height / 2, width, height / 2);

            // can just evenly space out the gradients in this case, as multiple lines makes no difference
            // to an even left to right gradient
            totalIterations = fill.length + 1;
            currentIteration = 1;

            for (var i$2 = 0; i$2 < fill.length; i$2++)
            {
                if (typeof fillGradientStops[i$2] === 'number')
                {
                    stop = fillGradientStops[i$2];
                }
                else
                {
                    stop = currentIteration / totalIterations;
                }
                gradient.addColorStop(stop, fill[i$2]);
                currentIteration++;
            }
        }

        return gradient;
    };

    /**
     * Destroys this text object.
     * Note* Unlike a Sprite, a Text object will automatically destroy its baseTexture and texture as
     * the majority of the time the texture will not be shared with any other Sprites.
     *
     * @param {object|boolean} [options] - Options parameter. A boolean will act as if all options
     *  have been set to that value
     * @param {boolean} [options.children=false] - if set to true, all the children will have their
     *  destroy method called as well. 'options' will be passed on to those calls.
     * @param {boolean} [options.texture=true] - Should it destroy the current texture of the sprite as well
     * @param {boolean} [options.baseTexture=true] - Should it destroy the base texture of the sprite as well
     */
    Text.prototype.destroy = function destroy (options)
    {
        if (typeof options === 'boolean')
        {
            options = { children: options };
        }

        options = Object.assign({}, defaultDestroyOptions, options);

        Sprite.prototype.destroy.call(this, options);

        // make sure to reset the the context and canvas.. dont want this hanging around in memory!
        this.context = null;
        this.canvas = null;

        this._style = null;
    };

    /**
     * The width of the Text, setting this will actually modify the scale to achieve the value set
     *
     * @member {number}
     */
    prototypeAccessors.width.get = function ()
    {
        this.updateText(true);

        return Math.abs(this.scale.x) * this._texture.orig.width;
    };

    prototypeAccessors.width.set = function (value) // eslint-disable-line require-jsdoc
    {
        this.updateText(true);

        var s = sign(this.scale.x) || 1;

        this.scale.x = s * value / this._texture.orig.width;
        this._width = value;
    };

    /**
     * The height of the Text, setting this will actually modify the scale to achieve the value set
     *
     * @member {number}
     */
    prototypeAccessors.height.get = function ()
    {
        this.updateText(true);

        return Math.abs(this.scale.y) * this._texture.orig.height;
    };

    prototypeAccessors.height.set = function (value) // eslint-disable-line require-jsdoc
    {
        this.updateText(true);

        var s = sign(this.scale.y) || 1;

        this.scale.y = s * value / this._texture.orig.height;
        this._height = value;
    };

    /**
     * Set the style of the text. Set up an event listener to listen for changes on the style
     * object and mark the text as dirty.
     *
     * @member {object|PIXI.TextStyle}
     */
    prototypeAccessors.style.get = function ()
    {
        return this._style;
    };

    prototypeAccessors.style.set = function (style) // eslint-disable-line require-jsdoc
    {
        style = style || {};

        if (style instanceof TextStyle)
        {
            this._style = style;
        }
        else
        {
            this._style = new TextStyle(style);
        }

        this.localStyleID = -1;
        this.dirty = true;
    };

    /**
     * Set the copy for the text object. To split a line you can use '\n'.
     *
     * @member {string}
     */
    prototypeAccessors.text.get = function ()
    {
        return this._text;
    };

    prototypeAccessors.text.set = function (text) // eslint-disable-line require-jsdoc
    {
        text = String(text === null || text === undefined ? '' : text);

        if (this._text === text)
        {
            return;
        }
        this._text = text;
        this.dirty = true;
    };

    /**
     * The resolution / device pixel ratio of the canvas.
     * This is set to automatically match the renderer resolution by default, but can be overridden by setting manually.
     * @member {number}
     * @default 1
     */
    prototypeAccessors.resolution.get = function ()
    {
        return this._resolution;
    };

    prototypeAccessors.resolution.set = function (value) // eslint-disable-line require-jsdoc
    {
        this._autoResolution = false;

        if (this._resolution === value)
        {
            return;
        }

        this._resolution = value;
        this.dirty = true;
    };

    Object.defineProperties( Text.prototype, prototypeAccessors );

    return Text;
}(Sprite));

export { TEXT_GRADIENT, Text, TextMetrics, TextStyle };
//# sourceMappingURL=text.es.js.map