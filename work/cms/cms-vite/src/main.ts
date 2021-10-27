import { createApp } from 'vue'

import Cookies from 'js-cookie'

import 'normalize.css/normalize.css' // a modern alternative to CSS resets

import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
// import zhCn from 'element-plus/lib/locale/lang/zh-cn'

import '@/styles/index.scss' // global css

import App from '@/App.vue'
import store from '@/store'
import router from '@/router'

import SvgIcon from '@/components/SvgIcon/index.vue'// svg component
// import './icons' // icon
import 'virtual:svg-icons-register';
import '@/permission' // permission control
import { initErrLog } from '@/utils/error-log' // error log

import * as filters from '@/filters' // global filters

/**
 * If you don't want to use mock-server
 * you want to use MockJs for mock api
 * you can execute: mockXHR()
 *
 * Currently MockJs will be used in the production environment,
 * please remove it before going online ! ! !
 */
// if (import.meta.env.MODE === 'production') {
  import { mockXHR } from '@/../mock/index.js'
  mockXHR()
// }

const app = createApp(App)

initErrLog(app)
// register global utility filters
app.config.globalProperties.$filters = {}
Object.keys(filters).forEach(key => {
  app.config.globalProperties.$filters[key] = filters[key]
})

app.use(ElementPlus, {
  size: Cookies.get('size') || 'medium', // set element-ui default size
  // locale: zhCn
})
app.use(router)
app.use(store)

// register globally
app.component('svg-icon', SvgIcon)

app.mount('#app')
