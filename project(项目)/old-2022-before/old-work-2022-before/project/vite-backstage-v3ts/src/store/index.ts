import { createStore } from 'vuex'
import getters from './getters'

let modules = {}
const modulesFiles = import.meta.globEager('./modules/*.ts')

for (const path in modulesFiles) {
  const moduleName = path.replace(/(.*\/)*([^.]+).*/gi, '$2')
  modules[moduleName] = modulesFiles[path].default
}

const store = createStore({
  modules,
  getters
})

export default store
