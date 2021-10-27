import { createRouter, createWebHashHistory } from "vue-router";

export const routes = [
  {
    path: "/",
    name: "Index",
    component: () => import("~/layout/index.vue"),
  },
  {
    path: "/home",
    name: "Home",
    component: () => import("~/views/home/index.vue"),
  },
  {
    path: "/about",
    name: "About",
    component: () => import("~/views/about/index.vue"),
  },
  {
    path: "/page1",
    name: "Page1",
    component: () => import("~/views/page1/index.vue"),
    children: [
      {
        path: "/page1_1",
        name: "Page1_1",
        component: () => import("~/views/page1/page1_1/index.vue"),
        children: [
          {
            path: "/page1_1_1",
            name: "Page1_1_1",
            component: () => import("~/views/page1/page1_1/page1_1_1/index.vue"),
          },
        ]
      },
    ]
  },
  {
    path: "/page2",
    name: "Page2",
    component: () => import("~/views/page2/index.vue"),
    children: [
      {
        path: "/page2_1",
        name: "Page2_1",
        component: () => import("~/views/page2/page2_1/index.vue"),
        children: [
          {
            path: "/page2_1_1",
            name: "Page2_1_1",
            component: () => import("~/views/page2/page2_1/page2_1_1/index.vue"),
          },
        ]
      },
    ]
  },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
});

export default router;
