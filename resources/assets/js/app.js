/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import router from './router'
import store from './store'
import Vue from 'vue'

import ed from "./energylabel/cata_冷氣機.js"
// console.log("test電扇",ed)

var if_value = (o)=>((typeof o[0]!="undefined")?o[0].content:null)
var get_by_tag = (arr,tag) => (arr.infos.filter((info)=>(info.label.indexOf(tag)!=-1)))

console.log(
  ed.filter(o=>o.infos.map(t=>t.label).indexOf("效率分級")!=-1)
    // .filter(o=>(get_by_tag("效率分級").content=="1" ) )
    .map((o)=>({
      brand: if_value(get_by_tag(o,"廠牌")),
      name: o.title,
      size: if_value(get_by_tag(o,"尺寸")), 
      comsumption: [if_value(get_by_tag(o,"年耗電量")),
                   if_value(get_by_tag(o,"消耗")),null].filter(o=>o)[0],
      type: ["年耗電量","消耗功率"][if_value(get_by_tag(o,"年耗電量"))?0:1]
    }))
)
  

// 1.      品牌
// 2.      型號
// 3.      尺寸(大小)
// 4.      年耗電量(若無則第二順位為功率)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));

new Vue({
  el: '#app',
  router,
  store
})

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
