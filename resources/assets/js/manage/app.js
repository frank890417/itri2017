require('../bootstrap');
import router from './router'
import store from './store'
import Vue from 'vue'
import axios from 'axios'

window.store=store

store.dispatch("get_uuid_devicelog")
store.dispatch("get_devices")
store.dispatch("get_userdetail")
store.dispatch("get_website_zh")


new Vue({
  el: '#app',
  router,
  store,
  data: {
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
      },
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
