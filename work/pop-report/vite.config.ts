import { resolve } from 'path'
import { defineConfig } from 'vite'
import { viteMockServe } from 'vite-plugin-mock'

import vue from '@vitejs/plugin-vue'
import Pages from 'vite-plugin-pages'
import Layouts from 'vite-plugin-vue-layouts'

import Icons from 'unplugin-icons/vite'
import WindiCSS from 'vite-plugin-windicss'
import IconsResolver from 'unplugin-icons/resolver'
import VueI18n from '@intlify/vite-plugin-vue-i18n'

import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { ElementPlusResolver } from 'unplugin-vue-components/resolvers'

// https://vitejs.dev/config/
export default defineConfig(() => {
  return {
    resolve: {
      alias: {
        '~/': `${resolve(__dirname, 'src')}/`,
      },
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
      proxy: {
        '/api': {
          target: 'https://cms.pop136.com',
          changeOrigin: true
        },
        '/upload/report/image': {
          target: 'https://imgf1.pop-fashion.com',
          changeOrigin: true
        },
        '/mfs/popfashion/upload/report/image': {
          target: 'https://imgf1.pop-fashion.com',
          changeOrigin: true
        },
        '/sp': {
          target: 'https://cms.pop136.com',
          changeOrigin: true
        },
        '/styles': {
          target: 'https://cms.pop136.com',
          changeOrigin: true
        },
        '/popbags': {
          target: 'https://imgb1.pop-fashion.com/',
          changeOrigin: true
        },
        '/bags_bigimage': {
          target: 'https://cms.pop136.com',
          changeOrigin: true
        },
      },
    },
    optimizeDeps: {
      include: [
        'vue',
        'pinia',
        'vue-i18n',
        'vue-router',
        '@vueuse/core',
        '@vueuse/head',
      ],
    },
    plugins: [
      vue(),
      Pages(),
      Layouts(),
      viteMockServe(),
      AutoImport({
        imports: [
          'vue',
          'pinia',
          'vue-i18n',
          'vue-router',
          '@vueuse/core',
          '@vueuse/head',
        ],
        dts: 'src/auto-imports.d.ts',
        resolvers: [
          ElementPlusResolver(),
        ],
      }),
      Components({
        dts: 'src/components.d.ts',
        resolvers: [
          ElementPlusResolver(),
          IconsResolver(),
        ],
      }),
      Icons({
        compiler: 'vue3',
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
