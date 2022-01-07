import axios from 'axios'
// import store from '@/store'
import { ElMessage } from 'element-plus'

// create an axios instance
const service = axios.create({
  timeout: 30000 // request timeout
})

// request interceptor
service.interceptors.request.use(
  config => {
    if (config.method.toLocaleUpperCase() === 'GET') {
      config.params = { ...config.params, /* site: store.getters.site, hash: store.getters.hash, translate: store.getters.translate || null */ }
    } else if (config.method.toLocaleUpperCase() === 'POST') {
      config.data = { ...config.data, /* site: store.getters.site, hash: store.getters.hash, translate: store.getters.translate || null */ }
    }
    return config
  },
  error => {
    ElMessage({
      message: error.message,
      type: 'error',
      duration: 5 * 1000
    })
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  response => {
    const res = response.data
    if (res.ret !== 1) {
      ElMessage({
        message: res.msg || 'Error',
        type: 'error',
        duration: 5 * 1000
      })
      return Promise.reject(res.msg || 'Error')
    } else {
      return res
    }
  },
  error => {
    ElMessage({
      message: error,
      type: 'error',
      duration: 5 * 1000
    })
    return Promise.reject(error)
  }
)

export default service
