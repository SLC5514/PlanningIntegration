import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'Home',
      component: () => import('@/view/Home')
    },
    {
      path: '/about',
      name: 'About',
      component: () => import('@/view/About')
    }
  ]
})
