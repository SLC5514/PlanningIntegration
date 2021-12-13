import { Plugin } from 'vue'
import { createPinia } from 'pinia'
import { UserModule } from '~/types'

// Setup Pinia
// https://pinia.esm.dev/
export const install: UserModule = ({ isClient, initialState, app }) => {
  const pinia = createPinia()
  app.use((pinia as Plugin))
  // Refer to
  // https://github.com/antfu/vite-ssg/blob/main/README.md#state-serialization
  // for other serialization strategies.
  if (isClient)
    pinia.state.value = (initialState.pinia) || {}

  else
    initialState.pinia = pinia.state.value
}

export default install
