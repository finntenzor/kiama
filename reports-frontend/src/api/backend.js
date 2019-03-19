import request from 'axios'
import config from '@/config'

export function getReportAtPage(page) {
  return request({
    url: config.BACKEND_URL,
    method: 'get',
    params: {
      page
    }
  })
}

export function getReportById(id) {
  return request({
    url: config.BACKEND_URL + '/' + id,
    method: 'get',
  })
}

export function removeReportById(id) {
  return request({
    url: config.BACKEND_URL + '/' + id,
    method: 'delete',
  })
}
