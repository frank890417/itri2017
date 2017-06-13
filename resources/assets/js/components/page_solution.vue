<template lang="pug">
  section.section_solution.grey
    .container
      .row.text-left
        .col-sm-12
          h1.title SOLUTION
            span.chinese 節能處方箋
        .card.col-sm-4
          .card_inner
            h5 {{now_room.eng}}
            h2 {{now_room.name}}
            //.btn_group_inline
              button.btn(v-for="(room,rid) in rooms",:class="{active:rid==now_place_id}",@click="now_place_id=rid") {{room.name}}
            img(:src="'/img/場景/'+now_room.pic+'2.png'", style="width: 100%")
            h4 耗電：{{now_consumption}}度
            h4 {{now_room.name}} 吃電怪獸排名：
            p(v-if="sorted_devices.length>0")
              span(v-for="(sdevice,sid) in sorted_devices") {{sid+1}}. {{sdevice.name}} ({{sdevice.device_consumption}}度)<br>
            p(v-else)
              span (資料填寫不足！無法計算)
        .card.col-sm-8
          .card_inner
            .card_title
              h5 Comsumption Percentage
              h2 用電比例視覺化
            graph_bubble(:datas="device_value",
                         :use_power="0.33",
                         :use_unit="'度'",
                         :use_mul="8",
                         @set_device="set_advice_device"
                        )
            
            ul.room_part_value
              li(v-for="(r,id) in device_result.room_sum" ,
                 :style="{width: (r.percentage+12)+'%'}",
                 @mouseenter="now_place_id=id" , 
                 @mouseleave="now_place_id=-1" , 
                 :class="{active: id==now_place_id}") {{rooms[id].name}} {{r.percentage}}%
        .card.col-sm-12
          .card_inner
            h2 節能處方箋
            hr
            p(v-if="old_devices.length>0")
              span 你的 
              span(v-for="odev in old_devices") 「{{odev.name}}」
              span 已經老舊囉！
              span <br>除了採用處方，更可以考慮更換有節能標章的新電器，聰明的省電！
            br

            .btn_group_inline.advice_device_list
              button.btn(v-for="(dev,did) in advice_device_list",:class="{active:dev==advice_device}",@click="set_advice_device(dev)") 
                img(:src="'/img/電器/icon_'+dev+'.svg'", style="width: 100%")
                div {{dev}}  

            h3 行動處方
            hr
            p
              ul(v-if="get_advices(advice_device)")
                li(v-for="(adv,id) in get_advices(advice_device).content") 
                  span {{id+1}}. {{adv}}
            

            br
            h3 節能電器推薦
            hr
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
      now_place_id: -1,
      rooms,
      advices,
      advice_device: '冷氣機',
      sound_expand: null
    }; 
  },
  components: {
    graph_bubble
  },
  mounted (){
    console.log("solution mounted");
    this.sound_expand=new Audio("http://awiclass.monoame.com/%E5%8B%95%E6%85%8B%E5%9C%96%E8%A1%A8%E9%9F%B3%E6%95%88/%E6%94%BE%E5%A4%A7.mp3");
  },
  watch: {
    now_place_id(){
      this.sound_expand.play();
    }
  },
  computed: {
    ...mapState(['device_result','devices']),
    advice_device_list(){
      return this.advices.map(o=>o.name);
    },
    sorted_devices(){
      //取的排序好的電器(前三名)
      // console.log( this.devices);
      if (this.now_place_id==-1){
        //全屋排名，利用不重複明稱，撈filter同名稱reduce累加，模擬成電器資料
        return this.uni_name_list.map(dev_name=>({
                    name: dev_name,
                    place: "all",
                    device_consumption: this.devices
                              .filter(dev=>dev.name==dev_name)
                              .map(o=>o.device_consumption)
                              .concat([0,0])
                              .reduce((a,b)=>(a+b))
                  }))
                 .sort((a,b)=>(b.device_consumption-a.device_consumption))
                 .filter((obj)=>obj.device_consumption>0)
                 .slice(0,3);
      }else{
        return this.devices
               .filter(((obj)=>obj.place==this.rooms[this.now_place_id].name))
               .filter((obj)=>obj.device_consumption>0)
               .sort((a,b)=>(b.device_consumption-a.device_consumption))
               .slice(0,3);
      }
      

    },
    now_room(){
      //取得現在房間(-1為全屋子)
      if (this.now_place_id==-1){
        return {name: "整體用電",eng: "Total Consumption",pic: "all"};
      }else{
        return this.rooms[this.now_place_id];
      }
    },
    now_consumption(){
      //取得現在消耗量(-1為全屋子)
      if (this.now_place_id==-1){
        return this.device_result.room_sum.map(o=>o.value).reduce((a,b)=>a+b);
      }else{
        return this.device_result.room_sum[this.now_place_id].value
      }
    },
    uni_name_list(){
      //取得不重複的電器類別名稱
      return this.devices.map(obj=>obj.name)
                         .filter((obj,ind,arr)=>arr.indexOf(obj)==ind)
    },
    device_value(){
      // var room_total=this.device_result.room_sum[this.now_place_id].value;
      // 設備數值
      if (this.now_place_id!=-1){
        //項目不filter，但是不是當前房間的值為0(先產生物件)
        return this.devices
           // .filter((obj)=>obj.place==this.rooms[this.now_place_id].name)
           // .filter((obj)=>obj.device_consumption>0)
           .map((obj)=>({
              name: obj.name,
              place: obj.place,
              value: (obj.place==this.rooms[this.now_place_id].name)?obj.device_consumption:0,
           }))
           //接續all房間值為零的物件
           .concat(this.uni_name_list.map(dev_name=>({
              name: dev_name,
              place: "all",
              value: 0
            })));
      }else{
        //假設一個"all"房間
        // console.log(this.uni_name_list);
        var result= this.uni_name_list.map(dev_name=>({
          name: dev_name,
          place: "all",
          value: this.devices
                    .filter(dev=>dev.name==dev_name)
                    .map(o=>o.device_consumption)
                    .concat([0,0])
                    .reduce((a,b)=>(a+b))
        }));
        //接續所有電器的零物件
        result = result.concat(
          this.devices.map((obj)=>({
              name: obj.name,
              place: obj.place,
              value: 0,
           }))

        )
        // console.log("result",result)
        return result
      }
                
    },
    old_devices(){
      return this.devices.filter(dev=>dev.buy_time_option==3)
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
