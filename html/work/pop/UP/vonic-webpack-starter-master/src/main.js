import Vue from 'vue'
import Vonic from 'vonic'

// Routes
const routes = [
  { path: '/', component: () => import('./components/Home.vue') },
  { path: '/button', component: () => import('./components/Button.vue') },
  { path: '/input', component: () => import('./components/Input.vue') },
  { path: '/search', component: () => import('./components/Search.vue') },
  { path: '/radio', component: () => import('./components/Radio.vue') },
  { path: '/checkbox', component: () => import('./components/Checkbox.vue') },
  { path: '/toggle', component: () => import('./components/Toggle.vue') },
  { path: '/range', component: () => import('./components/Range.vue') },
  { path: '/header', component: () => import('./components/Header.vue') },
  { path: '/badge', component: () => import('./components/Badge.vue') },
  { path: '/list', component: () => import('./components/List.vue') },
  { path: '/cells', component: () => import('./components/Cells.vue') },
  { path: '/tabs', component: () => import('./components/Tabs.vue') },
  { path: '/buttonBar', component: () => import('./components/ButtonBar.vue') },
  { path: '/scalable', component: () => import('./components/Scalable.vue') },
  { path: '/swiper', component: () => import('./components/Swiper.vue') },
  { path: '/scroll', component: () => import('./components/Scroll.vue') },
]

Vue.use(Vonic.app, {
  routes: routes
})
