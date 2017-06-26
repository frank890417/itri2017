
import Vue from 'vue'
import Vuex from 'vuex'
const uuidv4 = require('uuid/v4');
Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    loading: false,
    house_area_size: 15,
    device_result: {},
    full_nav_open: false,
    general_infos: {},
    devices: [],
    show_result: false,
    scrollTop: 0,
    user_degree: 0,
    user_uuid: uuidv4()
  },
  mutations: {
    set_loading(state,mode){
      state.loading = mode;
    },
    set_area_size(state,size){
      state.house_area_size = size
    },
    set_device_result(state,result){
      state.device_result = result
    },
    set_general_infos(state,result){
      state.general_infos = result
    },
    set_devices(state,devices){
      state.devices = devices;
    },
    toggle_nav(state){
      state.full_nav_open = !state.full_nav_open;
    },
    toggle_result(state){
      state.show_result = !state.show_result;
      store.dispatch("send_user_data");
    },
    set_scrollTop(state,value){
      state.scrollTop = value;
  // console.log(state.scrollTop);
    },
    set_user_degree(state,value){
      state.user_degree = value;
  // console.log(state.scrollTop);
    }
  },
  actions: {

    send_user_data(context){
      var user_data = context.state.devices
        .filter(o=>o.device_consumption!=0)
        .map( (o)=>{
          return {
            uuid: context.state.user_uuid,
            device_id: o.id,
            count: o.count,
            consumption: o.consumption,
            device_consumption: o.device_consumption,
            hour_consumption: o.hour_consumption,
            buy_time: [3,5,10,13][o.buy_time_option],
            use_time: [o.rarely,o.occasionally,o.often,o.frequently][o.option],
            light_option: o.light_option,
            place_id: o.place_id
          }
        })
      var return_data = {
        uuid: context.state.user_uuid,
        user_data,
      };
      console.log('user_data:',return_data);
      axios.post("/devicelog",return_data);

    }
  }
});

window.onscroll= (evt) => {
  store.commit("set_scrollTop",window.pageYOffset);
}

export default store