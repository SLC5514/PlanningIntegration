/*
 * @Author: SLC
 * @Date: 2021-08-17 17:15:11
 * @LastEditors: SLC
 * @LastEditTime: 2021-08-18 10:11:49
 * @Description: file content
 */

import { createStore } from 'vuex'
import getters from './getters'

// const store = createStore({
//   state: {
//     count: 0
//   },
//   getters: {
//     count: state => state.count
//   },
//   mutations: {
//     increment (state) {
//       state.count++
//     }
//   },
//   actions: {
//     increment (context) {
//       context.commit('increment')
//     }
//   }
// })

let modules = {}
const modulesFiles = import.meta.globEager('./modules/*.ts')

for (const path in modulesFiles) {
  const moduleName = path.replace(/(.*\/)*([^.]+).*/gi, '$2')
  // modules = { ...modules, [moduleName]: modulesFiles[path].default }
  modules[moduleName] = modulesFiles[path].default
}

const store = createStore({
  modules,
  getters
})

export default store
