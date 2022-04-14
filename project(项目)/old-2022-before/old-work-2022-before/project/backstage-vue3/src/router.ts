/*
 * @Author: SLC
 * @Date: 2021-08-10 09:51:38
 * @LastEditors: SLC
 * @LastEditTime: 2021-08-16 11:05:47
 * @Description: file content
 */

import { createRouter, createWebHistory } from "vue-router";

/* Layout */
import Layout from '@/layout/Index.vue'

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  { path: "/", component: Layout, redirect: '/home', children: [
    { path: "home", component: () => import("@/views/Index.vue") }
  ] },
  { path: "/page1", component: Layout, redirect: '/page1/index', children: [
    { path: "index", component: () => import("@/views/Page1.vue") }
  ] },
  { path: "/page2", component: Layout, redirect: '/page2/index', children: [
    { path: "index", component: () => import("@/views/Page2.vue") }
  ] },
];

/**
 * asyncRoutes
 * the routes that need to be dynamically loaded based on user roles
 */
export const asyncRoutes = []

const create = () =>
  createRouter({
    history: createWebHistory(),
    routes: constantRoutes,
  });

let router = create();

export function resetRouter() {
  router = create()
}

export default router;
