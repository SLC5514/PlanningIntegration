import { defineConfig } from 'umi';

export default defineConfig({
  layout: {},
  routes: [
    { path: '/', component: '@/pages/home/index' },
    { path: '/list', component: '@/pages/list/index' },
  ],
});
