import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import styleImport from 'vite-plugin-style-import'

import path from 'path'

// https://vitejs.dev/config/
export default defineConfig({
  resolve: {
    alias: { '/@': path.resolve(__dirname, 'src') },
  },
  build: {
    assetsDir: 'public',
  },
  css: {
    preprocessorOptions: {
      less: {
        javascriptEnabled: true,
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
