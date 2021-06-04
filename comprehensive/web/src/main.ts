import { createApp } from 'vue'
import App from './App.vue'

import router from "./router";
// import Antd from 'ant-design-vue'
// import 'ant-design-vue/dist/antd.css'

import AntdvInit from './lib/antdv'

const app = createApp(App)

AntdvInit(app)

app
  .use(router)
  .mount('#app')
