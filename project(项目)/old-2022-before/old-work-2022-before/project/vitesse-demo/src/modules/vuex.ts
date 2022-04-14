import { createStore } from 'vuex'
import { UserModule } from '~/types'

// Setup Pinia
// https://pinia.esm.dev/
export const install: UserModule = ({ app }) => {
  const modules = {}
  const globStores = import.meta.globEager('../stores-vuex/*.ts')
  for (const key in globStores) {
    const name = key.match(/(?<=\/)[\w-]+(?=\.ts)/)
    modules[name ? name[0] : key] = globStores[key].default
  }
  const vuex = createStore({
    modules,
  })
  app.use(vuex)
}

export default install
