
import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    uuid_devicelog: [],
    devices: [],
    website_zh: {},
    userdetails: [],
  	users: []  
  },
  mutations: {
    set_uuid_devicelog(state,data){
      state.uuid_devicelog=data
    },
    set_devices(state,data){
      state.devices=data

    },
    set_website_zh(state,data){
      state.website_zh=data
    },
    set_userdetails(state,data){
      state.userdetails=data
    },
    set_users(state,data){
      state.users=data
    },  
  },
  actions: {
    get_uuid_devicelog(context){
      axios.get("/api/device/uuid").then(
        (res)=>context.commit("set_uuid_devicelog",res.data)

      )
    },

    get_devices(context){
      axios.get("/api/devices").then(
        (res)=>context.commit("set_devices",res.data)

      )
    },

    get_userdetail(context){
      axios.get("/api/userdetails").then(
        (res)=>context.commit("set_userdetails",res.data)
      )
    },

    get_users(context){
      axios.get("/api/users").then(
        (res)=>context.commit("set_users",res.data)
    )
    },
  
    get_website_zh(context){
      axios.get("/api/websiteinfo/key/zh_info").then(
        (res)=>context.commit("set_website_zh",res.data)

      )
    },
    push_website_data(context,data){
      context.commit("set_website_zh",data)
      axios.post("/api/websiteinfo/key/zh_info",data).then(()=>{
        alert("更新完畢")
      })
    }
  }
});


export default store