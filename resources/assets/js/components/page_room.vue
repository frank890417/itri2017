<template lang="pug">
  section.section_room
    SoundPanel(:now_room="rooms[now_place_id]")
    .container
      .row
        .col-sm-8
          h4 / {{rooms[now_place_id].eng}}
          //h1 {{rooms[now_place_id].name}}
          .btn_group
            h1(v-for="(room,rid) in rooms",:class="{active:rid==now_place_id}",@click="switch_place(rid)") {{room.name}}
               
          h4 {{rooms[now_place_id].slogan}}

          .consumption_pointer
            .pointer_el(:style="{'animation-duration': get_duration(total.room_sum[now_place_id].value)+'s'}")
            h4 {{total.room_sum[now_place_id].value}} 度/年
          
          .room_img(style="min-height: 500px;margin-top: -40px;mix-blend-mode: multiply")
            transition-group(name="fade",mode="out-in")
              img.scene(
                :src="'/img/場景/'+room.eng+'2.png'" 
                style="width: 100%",
                v-for="room in rooms",
                v-if="rooms[now_place_id].eng==room.eng",
                :key="room")
          

          //select(name="room",v-model="now_place_id", v-on:change="now_device_id=0")
            option(v-for="(room,rid) in rooms",:value="rid") {{room.name}}
          
        .col-sm-4

          .form_block(v-if="now_device", style="margin-top: 15%")
            .form-group
              .device_info
                .eng Speaker
                h3 {{now_device.name}}

              img.device_pic(:src="'/img/電器/icon_'+now_device.name+'.svg'")
              .device_watt(v-if="!isNaN(now_device.default_consumption)") {{now_device.default_consumption}}
              //- img
          .form_block
            .form-group
              ul.room_device_list
                li(v-for="(device,did) in filter_device",@click="switch_device(did)",:class="{active: now_device_id==did,empty: device.count==0}") 
                  span(v-if="now_device_id==did")
                  span {{device.name}}
                  // div (數量:{{device.count}} {{["很少","偶爾","經常","頻繁"][device.option]}}使用 {{ ["0-3年","3-5年","5-10年","10年以上"][device.buy_time_option] }})
          .form_block
            .form-group(v-if="now_device.type=='normal'")
              label 電器瓦數
              input(type="number",v-model="now_device.consumption")
            .form-group(v-if="now_device.type=='light'")
              ul.room_device_list
                li(:class="{active:now_device.light_option==lid}",@click="now_device.light_option=lid",v-for="(light,lid) in light_list") {{light.name}}
            .form-group
              label 數量
              span.input_side_btn(@click="now_device.count--") -
              input(type="number" ,v-model="now_device.count")
              span.input_side_btn(@click="now_device.count++") +
            .form-group
              label 平均使用
              br
              .btn_group(v-if="now_device")
                button.btn(:class="{active:now_device.option==0}",@click="now_device.option=0")
                  span 很少
                  span.degree {{now_device.rarely}}hr
                button.btn(:class="{active:now_device.option==1}",@click="now_device.option=1")
                  span 偶爾
                  span.degree {{now_device.occaionally}}hr
                button.btn(:class="{active:now_device.option==2}",@click="now_device.option=2")
                  span 經常
                  span.degree {{now_device.often}}hr
                button.btn(:class="{active:now_device.option==3}",@click="now_device.option=3")
                  span 頻繁
                  span.degree {{now_device.frequently}}hr
            .form-group
              label 購買年份
              br
              .btn_group(v-if="now_device")
                button.btn(:class="{active:now_device.buy_time_option==0}",@click="now_device.buy_time_option=0")
                  span 新
                  span.degree 3年
                button.btn(:class="{active:now_device.buy_time_option==1}",@click="now_device.buy_time_option=1")
                  span 一般
                  span.degree 5年
                button.btn(:class="{active:now_device.buy_time_option==2}",@click="now_device.buy_time_option=2")
                  span 久
                  span.degree 10年
                button.btn(:class="{active:now_device.buy_time_option==3}",@click="now_device.buy_time_option=3")
                  span 老舊
                  span.degree 以上

            .form_group.test_info
              hr
              //h4 一年 {{total.value}} 度 (平均 {{ parseInt(total.value/12) }} 度)
              //h4 計算清單(測試用)
              ul
                li(v-for="log in total.log") {{log}}
              //hr
              //ul
                li(v-for="(r,id) in total.room_sum") {{rooms[id].name}}: {{r.value}}度 ({{r.percentage}}%)
</template>

<script>
import device_data from '../device_data'
import rooms from '../rooms'
import {mapState,mapMutations} from 'vuex'
import SoundPanel from './SoundPanel'

device_data.forEach(obj=>{obj.option=0;});

device_data.forEach(obj=>{obj.light_option=0;});
device_data.forEach(obj=>{obj.buy_time_option=0;});

device_data.forEach(obj=>{obj.consumption=obj.default_consumption;});

export default {
  name: 'page_room',
  mounted (){
    console.log("page room mounted.");
  },
  components: {
    SoundPanel
  },
  computed: {
    filter_device(){
      console.log(this.devices.map(o=>o.place));
      return this.devices
            .filter( device => (device.place==this.rooms[this.now_place_id].name) );
    },
    now_device(){
      if (this.now_device_id==-1) return null;
      return this.filter_device[this.now_device_id];
    },
    total(){
      var total_c = 0;
      var log_list=[];
      var light_total=0;
      var room_sum=[0,0,0,0];

      //加總電器
      this.devices.forEach((d,i)=>{
        var cump=0;
        if (d.type=="normal"){
          cump=d.consumption;
        }
        if (d.type=="light"){
          cump=this.light_list[d.light_option].value;
          
        }if (d.type=="hotwater"){
          cump=1070;
        }
        var hour=[d.rarely,d.occaionally,d.often,d.frequently][d.option];
        var device_consumption= parseInt(cump*d.count*d.consumption_mul*d.day
                *hour/1000 );
        
        if (d.type=="light") light_total +=device_consumption;
        total_c+=device_consumption;
        if (d.count>0){
        log_list.push(d.count+" x "+d.name+" ("+d.place+"): "+cump+"*"+d.consumption_mul+"*"+hour+"hr *"+d.day+" = "+device_consumption);
        }
        d.device_consumption = device_consumption;
        room_sum[this.get_place_id(d.place)]+=device_consumption;
      });

      //處理預設照明
      if (light_total==0){
       log_list.push("預設無填寫照明： 坪數 "+this.house_area_size+" * 12w = "+this.house_area_size*12+"kwh");
       total_c+=this.house_area_size*12;
       room_sum=room_sum.map(value=>value+this.house_area_size*12/4);
      }
      // console.log(room_sum)

      //後處理房間百分比與包資料
      room_sum=room_sum.map(value=>({value,percentage: parseInt(100*value/total_c)}));

      var result={
        value: total_c,
        log: log_list,
        room_sum: room_sum
      };
      //回傳與設定vuex全域變數
      this.set_devices(this.devices.slice());
      this.set_device_result(result);
      return result;
    },
    ...mapState(['house_area_size'])
  },
  methods: {
    switch_device(id){
      this.now_device_id=id;
      // this.now_device.count=1;
    },
    switch_place(id){
      this.now_place_id=id;
      this.now_device_id=0;
    },
    get_place_id(name){
      var result=0;
      this.rooms.forEach((d,i)=>{
        if (d.name==name)
          result=i;
      });
      return result;
    },
    get_duration(val){
      return 3* (Math.pow( Math.E, - val/2000 ) )+0.6;
    },
    ...mapMutations(['set_device_result','set_devices'])
  },
  data(){
    return {
      now_place_id: 0,
      devices: device_data,
      now_device_id: 0,
      rooms,
      light_list: [
        {
          name: "省電燈泡",
          value: 21 
        },
        {
          name: "白熾燈泡",
          value: 60 
        },
        {
          name: "LED燈泡",
          value: 9 
        },
        {
          name: "螢光燈管",
          value: 28 
        },
        {
          name: "鹵素崁燈",
          value: 50 
        },
        {
          name: "LED崁燈",
          value: 3 
        },
      ]
    }
  },
  created(){
  },


}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>


