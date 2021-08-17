/*
 * @Author: SLC
 * @Date: 2021-08-17 17:15:11
 * @LastEditors: SLC
 * @LastEditTime: 2021-08-17 17:50:20
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

// https://webpack.js.org/guides/dependency-management/#requirecontext
const modulesFiles = require.context('./modules', true, /\.js$/)

// you do not need `import app from './modules/app'`
// it will auto require all vuex module from modules file
const modules = modulesFiles.keys().reduce((modules: any, modulePath: string) => {
  // set './app.js' => 'app'
  const moduleName = modulePath.replace(/^\.\/(.*)\.\w+$/, '$1')
  const value = modulesFiles(modulePath)
  modules[moduleName] = value.default
  return modules
}, {})

const store = createStore({
  modules,
  getters
})

export default store
