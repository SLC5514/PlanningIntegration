import { createApp } from 'vue'

import App from './App.vue'

import 'element-plus/theme-chalk/src/message.scss'
import 'virtual:windi.css'
import 'virtual:windi-devtools'
import './styles/main.scss'

// import headInstall from './modules/head'
// import i18nInstall from './modules/i18n'
// import piniaInstall from './modules/pinia'
// import routerInstall from './modules/router'

const app = createApp(App)

// headInstall(app)
// i18nInstall(app)
// piniaInstall(app)
// routerInstall(app)

Object.values(import.meta.globEager('./modules/*.ts')).map(i => i.install?.(app))

// import './permission'

app.mount('#app')
