/* eslint-disable prefer-const */
/* eslint-disable no-undef */
'use strict';

/* 初始化 */
const ref = Vue.ref;
const reactive = Vue.reactive;
const onMounted = Vue.onMounted;
const app = Vue.createApp({
  setup() {
    /* 数据初始 */
    let pageData = reactive([]);
    fetch('./data.json')
      .then(res => res.json())
      .then(res => { Object.assign(pageData, res); })
      .catch(err => console.log(err));
    /* 页面控制 */
    const page = ref(0);
    const pageChange = val => {
      console.log('pageChange', val);
      page.value = val;
    };
    /* 展开控制 */
    const collapsed = ref([ 0 ]);
    const collapseChange = val => {
      console.log('collapseChange', val);
      const idx = collapsed.value.indexOf(val);
      if (idx === -1) {
        collapsed.value = [ val ];
      } else {
        collapsed.value.splice(idx, 1);
      }
    };

    onMounted(
      pageChange,
      collapseChange
    );

    return {
      title: '综合知识复习',
      /* 数据初始 */
      pageData,
      /* 页面控制 */
      page,
      pageChange,
      /* 展开控制 */
      collapsed,
      collapseChange,
    };
  },
});

app.mount('#app');
