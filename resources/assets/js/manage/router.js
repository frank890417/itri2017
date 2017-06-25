import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import manage_index from './manage_index.vue'

export default new Router({
  routes: [
    {
      path: '/manage',
      component: manage_index
    }
  ],
  mode: "history"
})
