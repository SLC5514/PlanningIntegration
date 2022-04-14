import path from 'path'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import './types';

// https://vitejs.dev/config/
export default defineConfig({
  resolve: {
    alias: {
      '~/': `${path.resolve(__dirname, 'src')}/`,
    },
  },
  // https://github.com/antfu/vite-ssg
  ssgOptions: {
    script: 'async',
    formatting: 'minify',
  },
  plugins: [vue()]
})
