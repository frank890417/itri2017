require('./bootstrap');
import router from './manage/router'
import store from './manage/store'
import Vue from 'vue'
import axios from 'axios'

window.store=store

store.dispatch("get_uuid_devicelog")

new Vue({
  el: '#app',
  router,
  store
})
