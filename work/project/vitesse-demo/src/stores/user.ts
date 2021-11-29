import { acceptHMRUpdate, defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
  state: () => {
    return {
      name: ''
    }
  },
  getters: {
    getName: state => state.name
  },
  actions: {
    setName(name: string) {
      this.name = name
    }
  }
})

if (import.meta.hot)
  import.meta.hot.accept(acceptHMRUpdate(useUserStore, import.meta.hot))
