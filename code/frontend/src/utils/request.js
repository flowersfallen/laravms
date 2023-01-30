import axios from 'axios'
import router from '@/router'
import { MessageBox } from 'element-ui'

// create an axios instance
const service = axios.create({
  baseURL: '/', // url = base url + request url
  // withCredentials: true, // send cookies when cross-domain requests
  timeout: 50000 // request timeout
})

var isMessage = true

// request interceptor
service.interceptors.request.use(
  config => {
    // do something before request is sent

    config.headers.Authorization = window.sessionStorage.getItem('token')
    return config
  },
  error => {
    // do something with request error
    console.log(error) // for debug
    return Promise.reject(error)
  }
)

// response interceptor
service.interceptors.response.use(
  /**
   * If you want to get http information such as headers or status
   * Please return  response => response
  */

  /**
   * Determine the request status by custom code
   * Here is just an example
   * You can also judge the status by HTTP Status Code
   */
  response => {
    const res = response.data

    // if the custom code is not 20000, it is judged as an error.
    if (res.code !== 0) {
      //  Message({
      //    message: res.message || 'Error',
      //    type: 'error',
      //    duration: 5 * 1000
      //  })

      // 50008: Illegal token; 50012: Other clients logged in; 50014: Token expired;
      if (res.code === 50008 || res.code === 50012 || res.code === 50014) {
        if (isMessage) {
          MessageBox.alert('登录已过期，请重新登录', '提示', {
            confirmButtonText: '重新登录',
            type: 'warning',
            callback: action => {
              router.push('/login')
            }
          })
          isMessage = false
        } else {
          return false
        }
      } else {
        return res
      }
    } else {
      return res
    }
  },
  error => {
    //  console.log('err' + error) // for debug
    //  Message({
    //    message: error.message,
    //    type: 'error',
    //    duration: 5 * 1000
    //  })
    return Promise.reject(error)
  }
)

export default service
