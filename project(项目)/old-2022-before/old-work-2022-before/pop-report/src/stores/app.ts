import { acceptHMRUpdate, defineStore } from 'pinia'

export const useAppStore = defineStore('app', () => {
  const title = ref('POP报告后台')
  const description = ref('POP报告后台')
  const query = ref() // 路由参数
  // const locale = ref('zh-CN') // 默认语言
  const localeList = ref([
    {
      name: '简体中文',
      value: 'zh-CN'
    },
    {
      name: 'English',
      value: 'en'
    },
  ]) // 可选语言列表

  return {
    title,
    description,
    query,
    // locale,
    localeList,
  }
})

if (import.meta.hot)
  import.meta.hot.accept(acceptHMRUpdate(useAppStore, import.meta.hot))
