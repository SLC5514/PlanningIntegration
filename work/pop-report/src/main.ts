import { createApp } from 'vue'

import App from './App.vue'

import 'element-plus/theme-chalk/src/message.scss'
import 'virtual:windi.css'
import 'virtual:windi-devtools'
import './styles/main.scss'

const app = createApp(App)

Object.values(import.meta.globEager('./modules/*.ts')).map(i => i.install?.(app))

app.mount('#app')
