import { acceptHMRUpdate, defineStore } from 'pinia'

export const useAppStore = defineStore('app', () => {
  const title = ref('POP报告后台')
  const description = ref('POP报告后台')
  const { locale } = useI18n() // 默认语言
  // 可选语言列表
  const localeList = ref([
    {
      name: '简体中文',
      value: 'zh-CN'
    },
    {
      name: 'English',
      value: 'en'
    },
  ])

  return {
    title,
    description,
    locale,
    localeList,
  }
})

if (import.meta.hot)
  import.meta.hot.accept(acceptHMRUpdate(useAppStore, import.meta.hot))
