if (module.hot) {
  module.hot.accept()
}


import {groupBy} from 'lodash/collection'
import people from './people'
import soundlist from './soundlist'
import './style.sass'
import Vue from 'vue'
// import sound_panel from 'sound_panel.vue'

const app = new Vue({
  el: "#app",
  data: {
    soundlist: soundlist,
    test: "hello"
  }
});


// import imageURL from './code.png'


