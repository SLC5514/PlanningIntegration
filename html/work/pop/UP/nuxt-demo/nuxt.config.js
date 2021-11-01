const pkg = require('./package')

module.exports = {
  mode: 'universal', // spa universal

  /*
  ** Headers of the page
  */
  head: {
    title: pkg.name,
    meta: [
      { 'http-equiv': 'Content-Type', content: 'text/html', charset: 'utf-8' },
      { 'http-equiv': 'X-UA-Compatible', content: 'IE=Edge' },
      {
        name: 'viewport',
        content:
          'user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1'
      },
      { hid: 'keywords', name: 'keywords', content: 'vue,nuxt' },
      { hid: 'description', name: 'description', content: pkg.description },
      { name: 'robots', content: 'all' },
      { 'http-equiv': 'pragma', content: 'no-cache' },
      { 'http-equiv': 'cache-control', content: 'no-cache' }
    ],
    link: [
      { rel: 'canonical', href: 'http://www.pop-fashion.com/' },
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },

  /*
  ** 添加路由中间件
  */
  router: {
    middleware: 'stats'
  },

  /*
  ** Customize the progress-bar color
  */
  loading: { color: '#35495e' },

  /*
  ** Global CSS
  */
  css: ['assets/css/reset.css'],

  /*
  ** Plugins to load before mounting the App
  */
  plugins: [],

  /*
  ** Nuxt.js modules
  */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios'
  ],
  /*
  ** Axios module configuration
  */
  axios: {
    // See https://github.com/nuxt-community/axios-module#options
  },

  /*
  ** Build configuration
  */
  build: {
    /*
    ** You can extend webpack config here
    */
    extend(config, ctx) {
      // Run ESLint on save
      if (ctx.isDev && ctx.isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/
        })
      }
    }
  }
}
