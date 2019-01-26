// require("./node_modules/babel-polyfill/dist/polyfill.js");
require('../bootstrap');
import router from './router'
import store from './store'
import Vue from 'vue'
import axios from 'axios'
// import elementUI from 'element-ui'
// import 'element-ui/lib/theme-chalk/index.css'
// Vue.use(elementUI)

window.store=store

import vue_lazy_table from './components/vue_lazy_table';
Vue.component("vue_lazy_table", vue_lazy_table)

store.dispatch("get_uuid_devicelog")
store.dispatch("get_devices")
store.dispatch("get_userdetail")
store.dispatch("get_website_zh")
store.dispatch("get_users")

new Vue({
  el: '#app',
  router,
  store,
  data: {
    user: window.user,
    routes: [
      // {
      //   label: "說明頁",
      //   link: "/",
      // },
      {
        label: "說明頁",
        link: "/about",
      },
      // {
      //   label: "基礎資料",
      //   link: "/about",
      // },
      {
        label: "電器填寫",
        link: "/room",
      },{
        label: "節能處方",
        link: "/solution",
      },{
        label: "診斷結果",
        link: "/share",
      },{
        label: "會員管理",
        link: "/users",
      }
      // {
      //   label: "圖表與分析",
      //   link: "/analysis",
      // },{
      //   label: "外部資料",
      //   link: "/dataset",
      // }
    ]
  }
})
