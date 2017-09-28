/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import router from './router'
import store from './store'
import Vue from 'vue'

import vue_i18n from './vue_i18n'
import axios from 'axios'

import {mapState,mapMutations} from 'vuex'
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));

window.store=store

new Vue({
  el: '#app',
  i18n: vue_i18n.i18n,
  router,
  store,
  mounted(){
    $(window).scroll((evt)=>{
      this.set_scrollTop(window.scrollY)
    })
  },
  methods: {
    ...mapMutations(['set_scrollTop'])
  }
})



window.onload=function(){
  store.commit("set_loading",false)
  console.log("load conplete")
}

console.log("User uuid(Random)",store.state.user_uuid)

if (document.domain=="saving.energypark.org.tw"){

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-52977512-20', 'auto');
  ga('send', 'pageview');

  window.ga=ga;
  console.log("ga enabled.")
}else{
  console.log("ga disabled.")

}

//fb UI
window.fbAsyncInit = function() {
  FB.init({
    appId            : '202392740294346',
    autoLogAppEvents : true,
    xfbml            : true,
    version          : 'v2.9'
  });
  FB.AppEvents.logPageView();
};

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));