import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import manage_index from '../components/manage_index.vue'
import manage_about from '../components/manage_about.vue'
import manage_room from '../components/manage_room.vue'
import manage_solution from '../components/manage_solution.vue'

export default new Router({
  routes: [
    {
      path: '/',
      component: manage_index
    },
    {
      path: '/index',
      component: manage_index
    },
    {
      path: '/about',
      component: manage_about
    },
    {
      path: '/room',
      component: manage_room
    },
    {
      path: '/solution',
      component: manage_solution
    },
    {
      path: '/share',
      component: manage_index
    },
    {
      path: '/analysis',
      component: manage_index
    },
    {
      path: '/dataset',
      component: manage_index
    }
  ],
  mode: "hash"
})
