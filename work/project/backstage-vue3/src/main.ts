import { createApp } from 'vue'
import App from './App.vue'

import Menu from '@/components/Menu/Index.vue'

import 'normalize.css'
import '@/styles/main.scss'

const app = createApp(App)
app.component('Menu', Menu)
app.mount('#app')
