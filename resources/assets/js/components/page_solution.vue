<template lang="pug">
  section.section_solution.grey
    .container
      .row.text-left
        .col-sm-12
          h1.title SOLUTION
            span.chinese 節能處方箋
        .card.col-sm-4
          .card_inner
            h5 {{rooms[now_place_id].eng}}
            h2 {{rooms[now_place_id].name}}
            //.btn_group_inline
              button.btn(v-for="(room,rid) in rooms",:class="{active:rid==now_place_id}",@click="now_place_id=rid") {{room.name}}
            img(:src="'/img/場景/'+rooms[now_place_id].eng+'2.png'", style="width: 100%")
            h4 耗電：{{device_result.room_sum[now_place_id].value}}度
            h4 {{rooms[now_place_id].name}} 吃電怪獸排名：
            p(v-if="sorted_devices.length>0")
              span(v-for="(sdevice,sid) in sorted_devices") {{sid+1}}. {{sdevice.name}} ({{sdevice.device_consumption}}度)
            p(v-else)
              span (資料填寫不足！無法計算)
        .card.col-sm-8
          .card_inner
            h2 用電比例視覺化
            graph_bubble(:datas="device_value",
                         :use_power="0.33",
                         :use_unit="'kwh'",
                         :use_mul="8",
                         @set_device="set_advice_device"
                        )
            h5 1kwh = 1度 (約4元)
            ul.room_part_value
              li(v-for="(r,id) in device_result.room_sum" ,
                 :style="{width: (r.percentage+12)+'%'}",
                 @click="now_place_id=id" , 
                 :class="{active: id==now_place_id}") {{rooms[id].name}} {{r.percentage}}%
        .card.col-sm-12
          .card_inner
            h2 節能處方 ({{advice_device}})
            hr
            p 你的 「冷氣」、「電風扇」、 「電暖器」已經老舊囉！<br>除了採用處方，更可以考慮更換有節能標章的新電器，聰明的省電！
            br
            h3 行動處方
            ul(v-if="get_advices(advice_device)")
              li(v-for="(adv,id) in get_advices(advice_device).content") {{id+1}}. {{adv}}


            br
            h3 節能標章電器推薦
            ul.recommend_list
              li(v-for="i in 5")
                img(:src="'/img/電器/icon_'+['冰箱','冷氣','吹風機','音響','微波爐'][i-1]+'.svg'", width=200)
                .device_title xx電器
                .device_com 功率: 300W

</template>



<script>
import {mapState,mapMutations} from 'vuex' 
import graph_bubble from './graph_bubble'
import rooms from '../rooms'
import advices from '../advices'
export default {
  name: 'page_solution',
  data(){
    return {
      now_place_id: 0,
      rooms,
      advices,
      advice_device: '冷氣機'
    }; 
  },
  components: {
    graph_bubble
  },
  mounted (){
    console.log("solution mounted");
    
  },
  watch: {

  },
  computed: {
    ...mapState(['device_result','devices']),
    sorted_devices(){
      // console.log( this.devices);
      return this.devices
                 .filter((obj)=>obj.place==this.rooms[this.now_place_id].name)
                 .filter((obj)=>obj.device_consumption>0)
                 .sort((a,b)=>(b.device_consumption-a.device_consumption))
                 .slice(0,3);

    },
    device_value(){
      var room_total=this.device_result.room_sum[this.now_place_id].value;
      return this.devices
                 // .filter((obj)=>obj.place==this.rooms[this.now_place_id].name)
                 // .filter((obj)=>obj.device_consumption>0)
                 .map((obj)=>({
                    name: obj.name,
                    place: obj.place,
                    value: (obj.place==this.rooms[this.now_place_id].name)?obj.device_consumption:0,
                 }));
                
    }
  },
  methods: {...mapMutations([]),
    room_part(room_value){
      var percent = this.get_room_percentage(room_value);
      return {
        width: percent+"%",

      }
    },
    get_room_percentage(room_value){
      return parseInt(100*room_value/this.device_result.value);
    },
    get_advices(device_name){
      var result = advices.filter((obj)=>obj.name==device_name)
      if (result.length==0) return null
      return result[0];
    },
    set_advice_device(name){
      this.advice_device=name;
      console.log("trigger emit: "+name);
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>
