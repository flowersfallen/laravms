import request from '@/utils/request'

/**
 * 视频剪辑
 * */
export function listVideo(params) {
  return request({
    url: '/admin/video/listVideo',
    method: 'get',
    params
  })
}

export function listClip(params) {
  return request({
    url: '/admin/video/listClip',
    method: 'get',
    params
  })
}

export function listCombine(params) {
  return request({
    url: '/admin/video/listCombine',
    method: 'get',
    params
  })
}

export function clipVideo(data) {
  return request({
    url: '/admin/video/clipVideo',
    method: 'post',
    data
  })
}

export function combineClip(params) {
  return request({
    url: '/admin/video/combineClip',
    method: 'get',
    params
  })
}

export function setShare(params) {
  return request({
    url: '/admin/video/setShare',
    method: 'get',
    params
  })
}

export function delItem(params) {
  return request({
    url: '/admin/video/delItem',
    method: 'get',
    params
  })
}

export function updateVideo(params) {
  return request({
    url: '/admin/video/updateVideo',
    method: 'get',
    params
  })
}
