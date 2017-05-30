
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

const routes = [
  { path: '/', component: page_index },
  { path: '/about', component: page_about },
  { path: '/tech', component: page_tech },
  { path: '/solution/:id', component: page_product , props: true},
  { path: '/solution/0', alias: '/solution'},
  { path: '/news', component: page_news },
  { path: '/news/:id', component: page_post , props: true},
  { path: '/news/cata/:cataname', component: page_news , props: true},
  { path: '/job', component: page_job },
  { path: '/contact', component: page_contact },
  { path: '/tern', component: page_tern },
  { path: '/search', component: section_search }
];


const store = new Vuex.Store({
  state: {
  });

const router = new VueRouter({
  routes,
  mode: "history"
})

const app = new Vue({
    el: '#app'
});
