import { acceptHMRUpdate, defineStore } from 'pinia'

export const useReportStore = defineStore('report', () => {
  const name = ref('test')

  return {
    name,
  }
})

if (import.meta.hot)
  import.meta.hot.accept(acceptHMRUpdate(useReportStore, import.meta.hot))
