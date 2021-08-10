/*
 * @Author: SLC
 * @Date: 2021-08-10 09:51:38
 * @LastEditors: SLC
 * @LastEditTime: 2021-08-10 15:23:14
 * @Description: file content
 */

import { createRouter, createWebHistory } from "vue-router";

const routes = [
  { path: "/", component: () => import("@/views/Index.vue") },
  { path: "/page1", component: () => import("@/views/Page1.vue") },
  { path: "/page2", component: () => import("@/views/Page2.vue") },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
