import { createRouter, createWebHistory } from "vue-router"

const routes = [
  {
    path: "/",
    name: "Home",
    component: () => import("/@/view/Home.vue"),
    children: [
      {
        path: "/html",
        name: "HTML",
        component: () => import("/@/view/HTML.vue"),
      },
      {
        path: "/css",
        name: "CSS",
        component: () => import("/@/view/CSS.vue"),
      },
    ]
  },
]

const router = createRouter({
  history: createWebHistory(),
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
