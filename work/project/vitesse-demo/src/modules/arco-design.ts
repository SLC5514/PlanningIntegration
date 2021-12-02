import ArcoVue from '@arco-design/web-vue'
import { UserModule } from '~/types'
import '@arco-design/web-vue/dist/arco.css'

export const install: UserModule = ({ app }) => {
  app.use(ArcoVue)
}

export default install
