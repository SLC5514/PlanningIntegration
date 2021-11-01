const pkg = require('./package')

module.exports = {
  mode: 'universal',
  /*
  ** Headers of the page
  */
  head: {
    meta: [
      { name: 'baidu-site-verification', content: 'sxdp9m2LM2' },
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      {
        hid: 'description',
        name: 'description',
        content:
          '史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯'
      },
      {
        hid: 'keywords',
        name: 'keywords',
        content:
          '史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯,史留闯'
      }
    ],
    title:
      '史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯史留闯',
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }]
  },

  /*
  ** Customize the progress-bar color
  */
  loading: {
    color: '#00c1de'
  },

  /*
  ** Global CSS
  */
  css: ['element-ui/lib/theme-chalk/index.css'],

  /*
  ** Plugins to load before mounting the App
  */
  plugins: ['@/plugins/element-ui'],

  /*
  ** Nuxt.js modules
  */
  modules: [
    // Doc: https://github.com/nuxt-community/axios-module#usage
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
    analyze: true,
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
