

// window.Vue = require('vue')
// window.Vuex = require('vuex')

/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import router from './router'

import Vue from 'vue'
import Vuex from 'vuex'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));


Vue.use(Vuex)
const store = new Vuex.Store({
  state: {
    loading: false,
    house_area_size: 15,
    device_result: {},
    full_nav_open: false

  },
  mutations: {
    set_loading(state,mode){
      state.loading = mode;
    },
    set_area_size(state,size){
      state.house_area_size = size;
    },
    set_device_result(state,result){
      state.device_result = result;
    },
    toggle_nav(state){
      state.full_nav_open=!state.full_nav_open;
    }
  }
});

new Vue({
  el: '#app',
  router,
  store
})

