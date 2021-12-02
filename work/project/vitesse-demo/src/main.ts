import { ViteSSG } from 'vite-ssg'
import generatedRoutes from 'virtual:generated-pages'
import { setupLayouts } from 'virtual:generated-layouts'
// import { UserModule } from '~/types'
import App from '~/App.vue'

import 'virtual:windi.css'
import '~/styles/main.css'

// https://github.com/antfu/vite-ssg
export const createApp = ViteSSG(
  App,
  { routes: setupLayouts(generatedRoutes) },
  (ctx) => {
    // install all modules under `modules/`
    Object.values(import.meta.globEager('./modules/*.ts')).map(i => i.install?.(ctx))
    // Object.values(import.meta.globEager('./modules/index.ts')).map(i => {
    //   i.modules.map((i: UserModule) => i?.(ctx))
    // })
  },
)
