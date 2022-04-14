import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from "./store"

import PrimeVue from 'primevue/config'
import Button from 'primevue/button'
import PanelMenu from 'primevue/panelmenu'

import 'primevue/resources/themes/saga-blue/theme.css'
import 'primevue/resources/primevue.min.css'
import 'primeicons/primeicons.css'
import 'primeflex/primeflex.min.css'
import './styles/index.scss'

const app = createApp(App)

app.use(router)
app.use(store)
app.use(PrimeVue, { ripple: true })

app.component('Button', Button)
app.component('PanelMenu', PanelMenu)

app.mount('#app')
