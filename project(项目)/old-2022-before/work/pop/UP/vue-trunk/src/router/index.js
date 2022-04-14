import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: '/',
      name: 'account',
      component: () => import('@/view/Account')
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/view/Login')
    },
    {
      path: '/business',
      name: 'business',
      component: () => import('@/view/Business')
    },
    {
      path: '/product',
      name: 'product',
      component: () => import('@/view/Product'),
      children: [
        {
          path: 'product_compile',
          name: 'product_compile',
          component: () => import('@/view/ProductCompile')
        },
        {
          path: 'product_add',
          name: 'product_add',
          component: () => import('@/view/ProductAdd')
        }
      ]
    },
    {
      path: '/fashion',
      name: 'fashion',
      component: () => import('@/view/Fashion'),
      children: [
        {
          path: 'fashion_compile',
          name: 'fashion_compile',
          component: () => import('@/view/FashionCompile')
        },
        {
          path: 'fashion_add',
          name: 'fashion_add',
          component: () => import('@/view/FashionAdd')
        }
      ]
    }
  ]
});
