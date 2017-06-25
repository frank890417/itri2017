
import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    uuid_devicelog: []
  },
  mutations: {
    set_uuid_devicelog(state,data){
      state.uuid_devicelog=data
    }
  },
  actions: {
    get_uuid_devicelog(context){
      axios.get("/api/devicelog/uuid").then(
        (res)=>context.commit("set_uuid_devicelog",res.data)

      )
    }
  }
});


export default store