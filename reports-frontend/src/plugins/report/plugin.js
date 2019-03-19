import Toast from 'muse-ui-toast'

const plugin = {}

plugin.install = function (Vue) {
  Vue.prototype.$report = function (error) {
    if (error.response) {
      Toast.error('错误：' + error.response.data.message)
      console.error('响应异常:', error.response)
    } else {
      Toast.error('异常：' + error.message)
    }
  }
}

export default plugin
