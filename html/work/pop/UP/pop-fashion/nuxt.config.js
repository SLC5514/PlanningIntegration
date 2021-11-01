const pkg = require('./package')

module.exports = {
  mode: 'universal',

  /*
  ** Headers of the page
  */
  head: {
    title: '服装设计网站_设计师信赖的流行趋势预测平台-POP服装趋势网',
    meta: [
      { 'http-equiv': 'Content-Type', charset: 'utf-8' },
      { 'http-equiv': 'X-UA-Compatible', content: 'IE=Edge' },
      { name: 'viewport', content: 'user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1' },
      { hid: 'keywords', name: 'keywords', content: '时尚杂志,时尚网站,时尚网,服装设计,服装设计网,服装设计网站,服装设计师,服装流行趋势' },
      { hid: 'description', name: 'description', content: 'POP服装趋势网是国内最大、国际领先的专业高端服装设计资源网站，涵盖独立设计师作品、时装周秀场高清图片和时尚杂志书籍，从色彩、面料、图案印花、款式、灵感、主题、廓形等方面，为设计师提供最新最前沿的服装解析。' },
      { name: 'robots', content: 'all' },
      { 'http-equiv': 'pragma', content: 'no-cache' },
      { 'http-equiv': 'cache-control', content: 'no-cache' }
    ],
    link: [
      { rel: 'canonical', href: 'http://www.pop-fashion.com/' },
      { rel: 'shortcut icon', type: 'image/x-icon', href: '/favicon.ico' },
      { rel: 'stylesheet', type: 'text/css', href: '/reset.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/base.css' }
    ]
  },

  /*
  ** Customize the progress-bar color
  */
  loading: { color: '#fff' },

  /*
  ** Global CSS
  */
  css: [],

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
