import { resolve } from 'path'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// Dotenv 是一个零依赖的模块，它能将环境变量中的变量从 .env 文件加载到 process.env 中

// https://vitejs.dev/config/
export default defineConfig({
  root: 'src',
  resolve: {
    alias: {
      '@': resolve(__dirname, 'src')
    }
  },
  css: {
    preprocessorOptions: {
      scss: {
        additionalData: `@import "@/styles/variables.scss";`
      }
    }
  },
  build: {
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'src/index.html'),
        home: resolve(__dirname, 'src/home/index.html'),
        nested: resolve(__dirname, 'src/nested/index.html')
      }
    }
  },
  plugins: [vue()]
})
