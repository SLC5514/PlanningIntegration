/* eslint valid-jsdoc: "off" */

'use strict';

const path = require('path');

/**
 * @param {Egg.EggAppInfo} appInfo app info
 */
module.exports = appInfo => {
  /**
   * built-in config
   * @type {Egg.EggAppConfig}
   **/
  const config = exports = {};

  // use for cookie sign key, should change to your own and keep security
  config.keys = appInfo.name + '_1622712373361_1908';

  // add your middleware config here
  config.middleware = [];

  // add your user config here
  const userConfig = {
    // myAppName: 'egg',
    view: {
      root: [
        path.join(appInfo.baseDir, 'app/view'),
        path.join(appInfo.baseDir, 'web/dist'),
      ].join(','),
      defaultViewEngine: 'nunjucks',
      defaultExtension: '.html',
      mapping: {
        '.html': 'nunjucks',
      },
    },
    static: {
      dir: [
        path.join(appInfo.baseDir, 'app/public'),
        path.join(appInfo.baseDir, 'web/dist/public'),
      ],
    },
  };

  return {
    ...config,
    ...userConfig,
  };
};
