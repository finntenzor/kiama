import Vue from 'vue'
import App from './App.vue'
import router from './router'

import './plugins/muse'
import './plugins/hljs'
import './plugins/report'

const instance = new Vue({
  router,
  render: h => h(App)
})

Vue.config.productionTip = false

const gatherVue = function (vm) {
  const data = {}
  for (const key in vm.$data) {
    try {
      data[key] = vm.$data[key]
    } catch {
      data[key] = '(error)'
    }
  }
  const children = []
  for (const child of vm.$children) {
    children.push(
      gatherVue(child)
    )
  }
  // const computed = {}
  // for (const key in vm.$data) {
  //   try {
  //     data[key] = vm.$data[key]
  //   } catch {
  //     data[key] = '(error)'
  //   }
  // }
  return {
    data,
    children
  }
}

Vue.config.errorHandler = function (/*err, vm, info*/) {
  console.log(instance)
  console.log(gatherVue(instance))
  // const data = JSON.stringify({
  //   err,
  //   // vm,
  //   info
  // })
  // console.log(data)
  // console.log(err, vm, info)
}

instance.$mount('#app')
