import { createApp, nextTick } from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";

import "./styles/index.scss";

const app = createApp(App);

app.use(router);
app.use(store);

app.directive('toggle', (el, binding) => {
  el.classList.add('toggle');
  el.onclick = function() {
    this.isclose = !this.isclose;
    if (this.isclose) {
      this.parentNode.style.height = this.clientHeight + 'px';
      this.classList.add('isclose');
    } else {
      this.parentNode.style.height = 'auto';
      this.classList.remove('isclose');
    }
  }
})

app.config.errorHandler = (err, vm, info) => {
  // Don't ask me why I use Vue.nextTick, it just a hack.
  // detail see https://forum.vuejs.org/t/dispatch-in-vue-config-errorhandler-has-some-problem/23500
  nextTick(() => {
    store.dispatch("errorLog/addErrorLog", {
      err,
      vm,
      info,
      url: window.location.href,
    });
    console.error(err, info);
  });
};

app.mount("#app");
