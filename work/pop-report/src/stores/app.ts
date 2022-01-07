import { acceptHMRUpdate, defineStore } from 'pinia'


export const useAppStore = defineStore('app', () => {
  const title = ref('POP报告后台')
  const description = ref('POP报告后台')
  const { locale } = useI18n()

  // 获取语言列表

  return {
    title,
    description,
    locale,
  }
})

if (import.meta.hot)
  import.meta.hot.accept(acceptHMRUpdate(useAppStore, import.meta.hot))
