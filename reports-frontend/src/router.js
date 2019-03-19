import Vue from 'vue'
import Router from 'vue-router'
import BackendList from './views/Backend/List'
import BackendReport from './views/Backend/Report'
import Frontend from './views/Frontend'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/backend',
      name: 'BackendList',
      component: BackendList
    },
    {
      path: '/backend/:id',
      name: 'BackendReport',
      component: BackendReport
    },
    {
      path: '/frontend',
      name: 'Frontend',
      component: Frontend
    },
    {
      path: '*',
      redirect: '/backend'
    }
  ]
})
