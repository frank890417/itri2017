import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router)
import manage_index from '../components/manage_index.vue'
import manage_about from '../components/manage_about.vue'
import manage_room from '../components/manage_room.vue'
import manage_device from '../components/manage_device.vue'
import manage_solution from '../components/manage_solution.vue'
import manage_test from '../components/manage_test.vue'
import manage_user_detail from '../components/manage_user_detail.vue'
import manage_users from '../components/manage_users.vue'


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
      path: '/device/:device_id',
      component: manage_device,
      props: true
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
    },
    {
      path: '/test',
      component: manage_test
    },
    {
      path: '/user/:uuid',
      component: manage_user_detail,
      props: true
    },{
      path: '/users',
      component: manage_users
    },  
  ],
  mode: "hash"
})
