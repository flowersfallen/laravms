import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

/* Layout */
import Layout from '@/layout'

/**
 * Note: sub-menu only appear when route children.length >= 1
 * Detail see: https://panjiachen.github.io/vue-element-admin-site/guide/essentials/router-and-nav.html
 *
 * hidden: true                   if set true, item will not show in the sidebar(default is false)
 * alwaysShow: true               if set true, will always show the root menu
 *                                if not set alwaysShow, when item has more than one children route,
 *                                it will becomes nested mode, otherwise not show the root menu
 * redirect: noRedirect           if set noRedirect will no redirect in the breadcrumb
 * name:'router-name'             the name is used by <keep-alive> (must set!!!)
 * meta : {
    roles: ['admin','editor']    control the page roles (you can set multiple roles)
    title: 'title'               the name show in sidebar and breadcrumb (recommend set)
    icon: 'svg-name'/'el-icon-x' the icon show in the sidebar
    breadcrumb: false            if set false, the item will hidden in breadcrumb(default is true)
    activeMenu: '/example/list'  if set path, the sidebar will highlight the path you set
  }
 */

/**
 * constantRoutes
 * a base page that does not have permission requirements
 * all roles can be accessed
 */
export const constantRoutes = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/views/login/index'),
    hidden: true
  },

  {
    path: '/404',
    component: () => import('@/views/404'),
    hidden: true
  },

  {
    path: '/',
    component: Layout,
    redirect: '/dashboard',
    children: [{
      path: 'dashboard',
      name: 'Dashboard',
      component: () => import('@/views/dashboard/index'),
      meta: { title: '首页', icon: 'dashboard' }
    }]
  },

  {
    path: '/video',
    component: Layout,
    redirect: '/video/list-video',
    meta: { title: '视频', icon: 'el-icon-s-help' },
    children: [
      {
        path: 'upload-video',
        name: 'UploadVideo',
        component: () => import('@/views/video/upload-video'),
        meta: { title: '上传视频', icon: 'table' }
      },
      {
        path: 'list-video',
        name: 'ListVideo',
        component: () => import('@/views/video/list-video'),
        meta: { title: '视频列表', icon: 'table' }
      },
      {
        path: 'clip-video',
        name: 'ClipVideo',
        hidden: true,
        component: () => import('@/views/video/clip-video'),
        meta: { title: '', icon: 'dashboard' }
      },
      {
        path: 'list-clip',
        name: 'ListClip',
        component: () => import('@/views/video/list-clip'),
        meta: { title: '切片列表', icon: 'table' }
      },
      {
        path: 'combine-clip',
        name: 'CombineClip',
        component: () => import('@/views/video/combine-clip'),
        meta: { title: '合并切片', icon: 'table' }
      },
      {
        path: 'list-combine',
        name: 'ListCombine',
        component: () => import('@/views/video/list-combine'),
        meta: { title: '合并列表', icon: 'table' }
      }
    ]
  },

  {
    path: '/share',
    component: Layout,
    redirect: '/share/share-video',
    meta: { title: '共享', icon: 'el-icon-s-help' },
    children: [
      {
        path: 'share-video',
        name: 'ShareVideo',
        component: () => import('@/views/video/share-video'),
        meta: { title: '视频列表', icon: 'table' }
      },
      {
        path: 'share-clip',
        name: 'ShareClip',
        component: () => import('@/views/video/share-clip'),
        meta: { title: '切片列表', icon: 'table' }
      }
    ]
  }
]

export const asyncRoutes = [
  {
    path: '/admin',
    component: Layout,
    redirect: '/admin/admin-list',
    alwaysShow: true,
    meta: { title: '用户', icon: 'el-icon-s-help', roles: ['admin'] },
    children: [
      {
        path: 'admin-list',
        name: 'AdminList',
        component: () => import('@/views/admin/admin-list'),
        meta: { title: '用户列表', icon: 'table', roles: ['admin'] }
      }
    ]
  },

  // 404 page must be placed at the end !!!
  { path: '*', redirect: '/404', hidden: true }
]

const createRouter = () => new Router({
  // mode: 'history', // require service support
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRoutes
})

const router = createRouter()

// Detail see: https://github.com/vuejs/vue-router/issues/1234#issuecomment-357941465
export function resetRouter() {
  const newRouter = createRouter()
  router.matcher = newRouter.matcher // reset router
}

export default router
