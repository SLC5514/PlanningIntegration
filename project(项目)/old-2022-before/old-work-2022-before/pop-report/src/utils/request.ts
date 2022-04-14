import axios from 'axios'
import { ElMessage } from 'element-plus'
import { useAppStore } from '~/stores/app'

// create an axios instance
const service = axios.create({
  timeout: 30000 // request timeout
})

// request interceptor
service.interceptors.request.use(
  config => {
    const appStore = useAppStore()
    if (config.method?.toLocaleUpperCase() === 'GET') {
      config.params = {
        ...config.params,
        site: appStore.query?.site,
        hash: appStore.query?.hash,
        translate: appStore.query?.translate
      }
    } else if (config.method?.toLocaleUpperCase() === 'POST') {
      config.data = {
        ...config.data,
        site: appStore.query?.site,
        hash: appStore.query?.hash,
        translate: appStore.query?.translate
      }
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
