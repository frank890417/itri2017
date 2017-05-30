import Vue from 'vue'
import Router from 'vue-router'
import SoundPanel from '@/components/SoundPanel'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/SoundPanel',
      name: 'SoundPanel',
      component: SoundPanel
    }
  ]
})
