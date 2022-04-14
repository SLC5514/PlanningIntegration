const path = require('path');

module.exports = {
  assetsDir: 'static',
  productionSourceMap: false,
  lintOnSave: true,
  configureWebpack: () => {
    if (process.env.NODE_ENV !== 'production') return;
    const PrerenderSPAPlugin = require("prerender-spa-plugin");
    const Renderer = PrerenderSPAPlugin.PuppeteerRenderer;
    return {
      plugins: [
        new PrerenderSPAPlugin({
          routes: ["/"],
          staticDir: path.join(__dirname, 'dist'),
          outputDir: path.join(__dirname, 'dist'),
          indexPath: path.join(__dirname, 'dist', 'index.html'),
          minify: {
            minifyCSS: true,
            removeComments: true
          },
          renderer: new Renderer({
            headless: false,
            maxConcurrentRoutes: 4,
            renderAfterDocumentEvent: "render-event",
          }),
        }),
      ],
    };
  },
};
