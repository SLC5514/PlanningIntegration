import { createRouter, createWebHistory } from "vue-router"

const routes = [
  {
    path: "/",
    name: "Home",
    component: () => import("/@/view/Home.vue"),
  },
  {
    path: "/antdv-layout",
    name: "AntdvLayout",
    component: () => import("/@/layout/Antdv.vue"),
  },

  // { path: "/:catchAll(.*)", redirect: "/404", hidden: true },
]

const router = createRouter({
  history: createWebHistory(), //history模式使用HTML5模式
  routes,
})

// export function resetRouter() {
//   const newRouter = createRouter({
//     history: createWebHistory(),
//     routes,
//   })

//   // router.matcher = newRouter.matcher; // reset router
// }

// router.beforeEach(async(to, from, next) => {

// })

export default router
