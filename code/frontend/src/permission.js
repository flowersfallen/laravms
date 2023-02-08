import router from './router'
import store from './store'
import NProgress from 'nprogress' // progress bar
import 'nprogress/nprogress.css' // progress bar style

NProgress.configure({ showSpinner: false }) // NProgress Configuration

router.beforeEach(async(to, from, next) => {
  //  如果用户访问登录页面，直接放行进入
  if (to.path === '/login') return next()
  //  从 sessionStorage 中获取 token 值
  const tokenStr = window.sessionStorage.getItem('token')
  //  没有 token ，强制跳转到 ‘/login’ 登录页
  if (!tokenStr) return next('/login')
  const hasGetUserInfo = store.getters.name
  if (!hasGetUserInfo) {
    try {
      const { roles } = await store.dispatch('user/getInfo')
      const accessRoutes = await store.dispatch('permission/generateRoutes', roles)
      router.addRoutes(accessRoutes)
      next({ ...to, replace: true })
    } catch (error) {
      window.sessionStorage.setItem('token', '')
      next(`/login?redirect=${to.path}`)
    }
  }
  next()
})

router.afterEach(() => {
  // finish progress bar
  NProgress.done()
})
