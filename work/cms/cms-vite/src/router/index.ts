/*
 * @Author: SLC
 * @Date: 2021-10-09 15:38:03
 * @LastEditors: SLC
 * @LastEditTime: 2021-10-13 16:22:09
 * @Description: file content
 */

import { createRouter, createWebHashHistory } from 'vue-router'

/* Layout */
import Layout from '@/layout/index.vue'

export const constantRoutes = [
  {
    path: '/redirect',
    component: Layout,
    hidden: true,
    children: [
      {
        path: '/redirect/:path(.*)',
        component: () => import('@/views/redirect/index.vue')
      }
    ]
  },
  {
    path: '/login',
    name: 'login',
    component: () => import('@/views/login/index.vue'),
    hidden: true
  },
  {
    path: '/auth-redirect',
    component: () => import('@/views/login/auth-redirect.vue'),
    hidden: true
  },
  {
    path: '/404',
    component: () => import('@/views/error-page/404.vue'),
    hidden: true
  },
  {
    path: '/401',
    component: () => import('@/views/error-page/401.vue'),
    hidden: true
  },
  {
    path: '/',
    component: Layout,
    redirect: '/dashboard',
    children: [
      {
        path: 'dashboard',
        component: () => import('@/views/dashboard/index.vue'),
        name: 'Dashboard',
        meta: { title: 'Dashboard', icon: 'dashboard', affix: true }
      }
    ]
  },
  // {
  //   path: '/',
  //   name: 'home',
  //   component: () => import('@/views/test/index.vue'),
  // },
]

export const asyncRoutes = []

const create = () =>
  createRouter({
    history: createWebHashHistory(),
    routes: constantRoutes,
  })

let router = create()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  // router = create()
  // router.matcher = newRouter.matcher // reset router
}

export default router
