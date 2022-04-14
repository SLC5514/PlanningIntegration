import Vue from 'vue';
import App from './App';
import router from './router';
import Promise from 'es6-promise';
import ElementUI from 'element-ui';
import axios from 'axios';
import 'element-ui/lib/theme-chalk/index.css';
import { post, get, patch, put } from './assets/getData';

Promise.polyfill();

//定义全局变量
Vue.prototype.$post=post;
Vue.prototype.$get=get;
Vue.prototype.$patch=patch;
Vue.prototype.$put=put;
Vue.prototype.$productUploadImg = 'http://cms-wechat.pop-fashion.com';

Vue.use(ElementUI);

Vue.config.productionTip = false;

new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
});
