import { ViteSSG } from 'vite-ssg'
// import { createApp } from 'vue'
// import { createHead } from '@vueuse/head'

import App from './App.vue'
import { routes } from './router'

// createApp(App).mount('#app')
// const head = createHead()

export const createApp = ViteSSG(
  // the root component
  App,
  // vue-router options
  { routes },
  // function to have custom setups
  ({ app, router, routes, isClient, initialState }) => {
    // install plugins etc.
    // app.use(head)
  }
)
