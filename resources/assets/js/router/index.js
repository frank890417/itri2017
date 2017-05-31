import Vue from 'vue'
import Router from 'vue-router'
import page_index from '../components/page_index'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      component: page_index
    }
  ],
  mode: "history"
})
