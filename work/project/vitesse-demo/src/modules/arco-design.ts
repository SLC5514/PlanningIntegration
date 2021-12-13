import ArcoVue from '@arco-design/web-vue'
import { Plugin } from 'vue'
import { UserModule } from '~/types'
import '@arco-design/web-vue/dist/arco.css'

export const install: UserModule = ({ app }) => {
  app.use((ArcoVue as Plugin))
}

export default install
