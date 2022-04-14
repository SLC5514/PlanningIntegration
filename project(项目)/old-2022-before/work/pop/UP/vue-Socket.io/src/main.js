import Vue from 'vue'
import App from './App'
import router from './router'
import Promise from 'es6-promise'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import store from './Store'
import VueSocketIO from 'vue-socket.io'
import socketio from 'socket.io-client'

Promise.polyfill()

Vue.use(ElementUI)

Vue.config.productionTip = false

Vue.use(new VueSocketIO({
  debug: true,
  connection: socketio('http://localhost:9001/'),
  vuex: {
    store
  }
}))

let vm = new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})

Vue.use({
  vm
})
