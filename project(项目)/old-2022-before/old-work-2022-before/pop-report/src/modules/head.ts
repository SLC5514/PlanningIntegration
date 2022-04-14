import { createHead } from '@vueuse/head'

const head = createHead()

export const install = app => {
  app.use(head)
}

export default install
