<template lang="pug">
  .sound_panel(v-if="show_control")
    .room(v-for="room in soundlist")
      h1 
        span.curp(@click="play(room)") {{room.name}}
        span.curp(@click="play(room)") [ ► ]
        span.curp(@click="play(room,'stop')") [ ❙❙ ]
      ul(v-if="room")
        li.device(v-for="dev in room.devices" @click="play_dev(dev,dev.playing?'stop':'')") {{dev.name}} {{dev.playing?' ❙❙':'►'}} {{dev.volume}}
</template>

<script>
import soundlist from '../soundlist'
import jquery from 'jquery'
import rooms from '../rooms'
window.$=jquery
function add_play_state(room){
  room.devices.forEach((dev)=>{
    dev.playing=false;
    dev.volume=1;
    dev.audio_el=new Audio('/music/'+room.name+'/'+dev.file);
  });
};
soundlist.forEach(room=>{add_play_state(room)});
export default {
  props: ["now_room"],
  name: 'hello',
  data () {
    return {
      soundlist: soundlist,
      show_control: false
    }
  },
  created () {
    console.log(soundlist);
  },
  watch:{
    now_room(){
      var vobj=this;
      console.log(this.now_room.name);
      soundlist.forEach((room)=>{
        if (room.name==this.now_room.name){
          vobj.play(room)
        }else{
          vobj.play(room,"stop");
        }
      });
     
    }
  },
  mounted () {
  },
  methods: {
    play (room,option) {
      // console.log(room);
      room.devices.forEach((obj,index)=>{
        if (option=="stop"){
          obj.playing=false;
          obj.audio_el.currentTime=0;
          obj.audio_el.pause();
        }else{
          obj.playing=true;
          obj.audio_el.currentTime=0;
          obj.audio_el.play();
        }
      })
    },
    play_dev(dev,option){
      if (option=="stop"){
        dev.playing=false;
        // dev.audio_el

        dev.volume=0;
        dev.audio_el.volume=0;
        // dev.audio_el.pause();
      }else{
        dev.playing=true;
        dev.volume=1;
        dev.audio_el.volume=1;
        // dev.audio_el.play();
      }
    }
  }      

}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">
h1, h2 
  font-weight: normal


ul 
  list-style-type: none
  padding: 0


li 
  display: inline-block
  margin: 0 10px


a 
  color: #42b983

.device,.curp
  cursor: pointer

</style>
