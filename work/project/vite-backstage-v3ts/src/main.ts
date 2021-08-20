import { createApp } from 'vue'

import 'normalize.css/normalize.css' // a modern alternative to CSS resets

import { ElButton, ElPagination } from 'element-plus';
import 'element-plus/packages/theme-chalk/src/base.scss';
// import ElementPlus from 'element-plus';
// import './styles/element-variables.scss'
// import 'element-plus/lib/theme-chalk/index.css';
// import enLang from 'element-ui/lib/locale/lang/en'// 如果使用中文语言包请默认支持，无需额外引入，请删除该依赖

import '@/styles/index.scss' // global css

import App from './App.vue'
import router from './router'
import store from './store'

const app = createApp(App)

// app.use(ElementPlus)
app.use(ElButton)
app.use(ElPagination)
app.use(router)
app.use(store)

// app.component('Provider', Provider)

app.mount('#app')
