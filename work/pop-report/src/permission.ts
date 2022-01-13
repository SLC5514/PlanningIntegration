import { router } from './modules/router'
import { useAppStore } from '~/stores/app'

const appStore = useAppStore()
console.log(666, appStore)

router.beforeEach((to, from) => {
  console.log(appStore)
  console.log(to)
  // appStore.query
})
