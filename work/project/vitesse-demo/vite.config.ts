import { resolve } from 'path'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import Pages from 'vite-plugin-pages'
import Layouts from 'vite-plugin-vue-layouts'
import AutoImport from 'unplugin-auto-import/vite'
import VueI18n from '@intlify/vite-plugin-vue-i18n'
import Components from 'unplugin-vue-components/vite'
import WindiCSS from 'vite-plugin-windicss'

// https://vitejs.dev/config/
export default defineConfig(({ mode }) => {
  const __DEV__ = mode === 'development'
  const VITE_LOCALE = process.env.VITE_LOCALE

  const alias: Record<string, string> = {
    '~/': `${resolve(__dirname, 'src')}/`,
  }

  if (__DEV__) {
    // 解决警告 You are running the esm-bundler build of vue-i18n.
    alias['vue-i18n'] = 'vue-i18n/dist/vue-i18n.cjs.js'
  }

  return {
    // base: __DEV__ ? '/' : VITE_LOCALE ? `/${VITE_LOCALE}/` : '/zh_CN/',

    build: {
      outDir: `dist${VITE_LOCALE ? `/${VITE_LOCALE}` : '/zh_CN'}`,
    },

    resolve: {
      alias,
    },

    css: {
      postcss: {
        plugins: [
          require('autoprefixer'),
          require('postcss-flexbugs-fixes'),
        ],
      },
      preprocessorOptions: {
        scss: {
          charset: false,
        },
      },
    },

    server: {
      fs: {
        strict: true,
      },
    },

    // https://github.com/antfu/vite-ssg
    ssgOptions: {
      script: 'async',
      formatting: 'minify',
    },

    optimizeDeps: {
      include: [
        'vue',
        'vue-router',
        '@vueuse/core',
        '@vueuse/head',
      ],
      // exclude: [
      //   'vue-demi',
      // ],
    },

    plugins: [
      vue({
        include: [/\.vue$/], // , /\.md$/
      }),

      // https://github.com/hannoeru/vite-plugin-pages
      Pages({
        extensions: ['vue'], // , 'md'
        extendRoute(route) {
          // if (__DEV__) route.path = (VITE_LOCALE ? `/${VITE_LOCALE}` : '/zh_CN') + route.path
          return {
            ...route,
          }
        },
      }),

      // https://github.com/JohnCampionJr/vite-plugin-vue-layouts
      Layouts(),

      // https://github.com/antfu/unplugin-auto-import
      AutoImport({
        imports: [
          'vue',
          'vue-router',
          'vue-i18n',
          '@vueuse/head',
          '@vueuse/core',
        ],
        dts: 'src/auto-imports.d.ts',
      }),

      // https://github.com/antfu/unplugin-vue-components
      Components({
        // allow auto load markdown components under `./src/components/`
        extensions: ['vue'], // , 'md'

        // allow auto import and register components used in markdown
        include: [/\.vue$/, /\.vue\?vue/], // , /\.md$/

        // custom resolvers
        // resolvers: [
        //   auto import icons
        //   https://github.com/antfu/unplugin-icons
        //   IconsResolver({
        //     componentPrefix: '',
        //     // enabledCollections: ['carbon']
        //   }),
        // ],

        dts: 'src/components.d.ts',
      }),

      // https://github.com/intlify/bundle-tools/tree/main/packages/vite-plugin-vue-i18n
      VueI18n({
        runtimeOnly: true,
        compositionOnly: true,
        include: [resolve(__dirname, 'locales/**')],
      }),

      // https://github.com/antfu/vite-plugin-windicss
      WindiCSS(),

    ],

  }
})
