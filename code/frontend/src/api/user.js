import request from '@/utils/request'

export function login(data) {
  return request({
    url: '/admin/login',
    method: 'post',
    data
  })
}

export function getInfo(token) {
  return request({
    url: '/admin/user',
    method: 'get',
    params: { token }
  })
}

export function logout() {
  return request({
    url: '/admin/logout',
    method: 'post'
  })
}

export function getStat(params) {
  return request({
    url: '/admin/getStat',
    method: 'get',
    params
  })
}

export function getList(params) {
  return request({
    url: '/admin/list',
    method: 'get',
    params: params
  })
}

export function addUser(data) {
  return request({
    url: 'admin/add',
    method: 'post',
    data: data
  })
}

export function updateUser(data) {
  return request({
    url: 'admin/update',
    method: 'post',
    data: data
  })
}

export function delUser(data) {
  return request({
    url: 'admin/del',
    method: 'post',
    data: data
  })
}
