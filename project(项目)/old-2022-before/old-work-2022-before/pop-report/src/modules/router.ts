import { createRouter, createWebHistory } from 'vue-router'

import generatedRoutes from 'virtual:generated-pages'
import { setupLayouts } from 'virtual:generated-layouts'

import { useAppStore } from '~/stores/app'

const routes = setupLayouts(generatedRoutes)

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach(to => {
  const appStore = useAppStore()
  appStore.query = to.query // 初始化路由参数
})

export const install = app => {
  app.use(router)
}

export default install
