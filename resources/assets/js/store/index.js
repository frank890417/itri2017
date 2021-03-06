
import Vue from 'vue'
import Vuex from 'vuex'
import {avg_data} from '../2019_avg_house_data.js'
import axios from 'axios'
// import {dataElecLoad,dataElecTest} from '../2019_fixing_panel_data.js'
const uuidv4 = require('uuid/v4');
Vue.use(Vuex)

const store = new Vuex.Store({
  state: {
    loading: TextTrackCueList,
    house_area_size: 15,
    device_result: {},
    full_nav_open: false,
    general_infos: {},
    devices: [],
    show_result: false,
    scrollTop: 0,
    user_degree: 0,
    user_uuid: uuidv4(),
    site_width: window.innerWidth,
    member_data:{ 
      users_id: MEMBER_DATA.users_id ,
      email: MEMBER_DATA.email,
      isLogin: MEMBER_DATA.isLogin,
      isAdmin: 0, 
    },
    advice_device:'冷氣機',
    KWH:1300,
    debug: false,
    music_enabled: true,
    avg_house_data: avg_data,
    // dataElecLoad,
    // dataElecTest
  },
  mutations: {
    set_avg_house_data(state,value){
      state.avg_house_data=value
    },
    set_music_enable(state,value){
      state.music_enabled = value
    },
    FETCH(state, advice_device) {
      state.advice_device = advice_device;
    },
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
      //console.log("member isLogin:", state.member_userData.isLogin );
    },
    set_scrollTop(state,value){
      state.scrollTop = value;
  // console.log(state.scrollTop);
    },
    set_user_degree(state,value){
      state.user_degree = value
  // console.log(state.scrollTop)
    },
    set_window_width(state,value){
      state.site_width=value
    },

    setMember_data_isAdmin(state, value){
      state.member_data.isAdmin=value;    
    },
    set_debug(state,value){
      state.debug=value
    }
  },
  actions: {
    load_avg_house_data(context){
      axios.get("/api/page/compareavg").then((res)=>{
        context.commit("set_avg_house_data",JSON.parse(res.data.content).avg_house_data)
        console.log("avg house data Loaded!", context.state.avg_house_data)
      })
    },
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
            buy_time: o.buy_time,
            use_time: [o.rarely,o.occasionally,o.often,o.frequently][o.option],
            light_option: o.light_option,
            place_id: o.place_id,            
            users_id: context.state.member_data.users_id ,     
            area_size: o.area_size,   
            ac_power: o.ac_power,   
            cspf: o.cspf

          }
        })

      var return_data = {
        uuid: context.state.user_uuid,
        user_data,
      };
      console.log('user_data:',return_data);
      axios.post("/devicelog",return_data);

       var user_data = {
        uuid: context.state.user_uuid,
        general_infos: context.state.general_infos,
        users_id: context.state.member_data.users_id ,
      };
      console.log('userdetails.general_infos:',user_data.general_infos);
      //console.log('userdetails.general_infos.money:',user_data.general_infos.money);
      axios.post("/userdetail",user_data);


    },
  	
    rec_webCount(context){
    	var user_data = {
        	uuid:context.state.user_uuid
        }
  		axios.post("/webcounter", user_data);
    	//console.log("webCnt:", user_data);
    },
    get_general_infos(context, users_id){
      axios.get("/api/userdetails/"+users_id).then(
        (res)=>context.commit("set_general_infos",res.data)
      )
    },
  }
});

window.onscroll= (evt) => {
  store.commit("set_scrollTop",window.pageYOffset);
}

export default store