require('./bootstrap');
import router from './manage/router'
import store from './manage/store'
import Vue from 'vue'
import axios from 'axios'

window.store=store

store.dispatch("get_uuid_devicelog")
store.dispatch("get_devices")
store.dispatch("get_website_zh")

new Vue({
  el: '#app',
  router,
  store
})
