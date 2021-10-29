import { createRouter, createWebHashHistory, RouteRecordRaw } from "vue-router";

const context = import.meta.glob("../views/import/**/*.vue") as AnyObject;

const viewRoutes: Array<RouteRecordRaw> = [];

for (const path in context) {
  const name = path.replace(/(\.+\/views\/|\/index\.vue)/g, "");
  const nameSplit = name.split("/");
  const item = {
    path: "/layout/" + encodeURI(name),
    name: nameSplit[nameSplit.length - 1],
    pname: nameSplit[nameSplit.length - 2] || "",
    component: context[path],
    children: [],
  };
  let hasParent = loopRoutes(viewRoutes, item);
  if (!hasParent) viewRoutes.push(item);
}

function loopRoutes(routes: Array<AnyObject>, item: AnyObject): boolean {
  let hasParent = false;
  for (let i = 0; i < routes.length; i++) {
    if (routes[i]?.children?.length > 0) {
      hasParent = loopRoutes(routes[i].children || [], item);
    }
    if (hasParent) break;
    if (item?.pname === routes[i].name) {
      hasParent = true;
      routes[i].children?.push(item);
      break;
    }
  }
  return hasParent;
}

export const routes = [
  {
    path: '/redirect',
    component: () => import("~/layout/index.vue"),
    meta: {
      hidden: true,
    },
    children: [
      {
        path: '/redirect/:path(.*)',
        component: () => import('~/views/redirect/index.vue')
      }
    ]
  },
  {
    path: "/",
    redirect: "/layout",
    component: () => import("~/layout/index.vue"),
    meta: {
      hidden: true,
    },
  },
  {
    path: "/layout",
    name: "layout",
    redirect: viewRoutes[0].path,
    component: () => import("~/layout/index.vue"),
    meta: {
      hiddenitem: true,
    },
    children: [...viewRoutes],
  },
  {
    path: "/:pathMatch(.*)*",
    name: "NotFound",
    redirect: "/",
    meta: {
      hidden: true,
    },
  },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
});

const progress = document.createElement('div');
const progressChild = document.createElement('div');
let count = 0;
let timer: number = 0;
progress.className = 'progress';
progress.style.position = 'fixed';
progress.style.top = '0';
progress.style.left = '0';
progress.style.right = '0';
progress.style.height = '2px';
progress.style.zIndex = '9';
progressChild.style.width = count + '%';
progressChild.style.height = '100%';
progressChild.style.background = 'skyblue';
progressChild.style.borderRadius = '50%';
progressChild.style.transition = 'width 0.2s';

router.beforeEach((to, from) => {
  document.title = "Vite App - " + (to?.meta?.title ? to?.meta?.title : to.name?.toString());

  document.querySelector('.progress')?.remove();
  count = 0;
  progressChild.style.width = count + '%';
  progress.appendChild(progressChild);
  document.body.appendChild(progress);
  clearInterval(timer);
  timer = setInterval(() => {
    count++
    if (count > 120) {
      clearInterval(timer);
      document.querySelector('.progress')?.remove();
    }
    if (count <= 100) {
      progressChild.style.width = count + '%';
    }
  }, 10)
});

export default router;
