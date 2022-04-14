import { acceptHMRUpdate, defineStore } from 'pinia'

// 方式一
// export const useUserStore = defineStore('user', {
//   state: () => {
//     return {
//       name: 'test',
//     }
//   },
//   getters: {
//     getName: state => state.name,
//   },
//   actions: {
//     setName(name: string) {
//       this.name = name
//     },
//   },
// })

// 方式二 推荐
export const useUserStore = defineStore('user', () => {
  const name = ref('test')
  const getName = computed(() => name)
  const setName = (newName: string) => {
    name.value = newName
  }
  return {
    name,
    getName,
    setName,
  }
})

if (import.meta.hot)
  import.meta.hot.accept(acceptHMRUpdate(useUserStore, import.meta.hot))
