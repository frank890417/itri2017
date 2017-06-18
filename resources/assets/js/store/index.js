
import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)
const store = new Vuex.Store({
  state: {
    loading: false,
    house_area_size: 15,
    device_result: {},
    full_nav_open: false,
    devices: [],
    show_result: false,
    scrollTop: 0,
    user_degree: 0

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
    set_devices(state,devices){
      state.devices = devices;
    },
    toggle_nav(state){
      state.full_nav_open = !state.full_nav_open;
    },
    toggle_result(state){
      state.show_result = !state.show_result
    },
    set_scrollTop(state,value){
      state.scrollTop = value;
  // console.log(state.scrollTop);
    },
    set_user_degree(state,value){
      state.user_degree = value;
  // console.log(state.scrollTop);
    }
  }
});

window.onscroll= (evt) => {
  store.commit("set_scrollTop",window.pageYOffset);
}

export default store