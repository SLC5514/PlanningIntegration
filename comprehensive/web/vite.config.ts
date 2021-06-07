import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import styleImport from 'vite-plugin-style-import'

import path from 'path'

const resolve = (dir: string) => path.resolve(__dirname, dir)

// https://vitejs.dev/config/
export default defineConfig({
  resolve: {
    alias: { '/@': resolve('src') },
  },
  build: {
    assetsDir: 'public',
  },
  css: {
    preprocessorOptions: {
      less: {
        javascriptEnabled: true,
        modifyVars: {
          hack: `true; @import "${resolve('./src/assets/style/custom-vant-style.less')}";`,
        },
      },
    },
  },
  plugins: [
    vue(),
    styleImport({
      libs: [
        {
          libraryName: 'ant-design-vue',
          esModule: true,
          resolveStyle: (name) => {
            return `../../node_modules/ant-design-vue/es/${name}/style/index`
          },
        },
      ]
    }),
  ],
})
