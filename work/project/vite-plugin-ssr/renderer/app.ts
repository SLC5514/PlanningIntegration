import { createSSRApp, defineComponent, h } from 'vue'
import PageWrapper from './PageWrapper.vue'
import { setPageContext } from './usePageContext'
import type { PageContext } from './types'

import ArcoVue from '@arco-design/web-vue'
import '@arco-design/web-vue/dist/arco.css'

export { createApp }

function createApp(pageContext: PageContext) {
  const { Page, pageProps } = pageContext
  const PageWithLayout = defineComponent({
    render() {
      return h(
        PageWrapper,
        {},
        {
          default() {
            return h(Page, pageProps || {})
          }
        }
      )
    }
  })

  const app = createSSRApp(PageWithLayout)

  app.use(ArcoVue)

  // Make `pageContext` available from any Vue component
  setPageContext(app, pageContext)

  return app
}
