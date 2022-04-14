import Vue from "vue";

// 防止连续点击
const preventReClick = Vue.directive("preventReClick", {
  inserted: function(el, binding) {
    el.addEventListener("click", () => {
      if (!el.disabled) {
        el.disabled = true;
        el.timer = setTimeout(() => {
          el.disabled = false;
          clearTimeout(el.timer);
        }, binding.value || 3000);
      }
    });
  }
});
