import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

import { join } from "path"

export default defineConfig({
  alias: {
    '@': join(__dirname, "src"),
  },
  plugins: [vue()]
})
