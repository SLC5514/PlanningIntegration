import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'Home',
      component: () => import('@/components/Home')
    },
    {
      path: '/about',
      name: 'About',
      component: () => import('@/components/About'),
      children: [
        {
          path: 'us',
          name: 'Us',
          component: () => import('@/components/Us')
        }
      ]
    }
  ]
})
