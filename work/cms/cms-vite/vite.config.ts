import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

import { resolve } from 'path'
// import Components from 'unplugin-vue-components/vite'
// import { ElementPlusResolver } from 'unplugin-vue-components/resolvers'
import viteSvgIcons from 'vite-plugin-svg-icons';

// https://vitejs.dev/config/
export default defineConfig({
  resolve: {
    alias: {
      '@': resolve(__dirname, 'src')
    }
  },
  // css: {
  //   preprocessorOptions: {
  //     scss: {
  //       additionalData: `@use "@/styles/variables.scss" as *;`,
  //     },
  //   },
  // },
  plugins: [
    vue(),
    // Components({
    //   resolvers: [ElementPlusResolver()],
    // }),
    viteSvgIcons({
      // 指定需要缓存的图标文件夹
      iconDirs: [resolve(process.cwd(), 'src/icons/svg')],
      // 指定symbolId格式
      symbolId: 'icon-[dir]-[name]',
    }),
  ],
})
