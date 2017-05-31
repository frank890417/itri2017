<template lang="pug">
  section.section_room
    .container
      .row
        .col-sm-7
          h4 / {{rooms[now_place_id].eng}}
          h1 {{rooms[now_place_id].name}}
          h5 全家休閒吃飯的空間，常常會忘記關冷氣
          //select(name="room",v-model="now_place_id", v-on:change="now_device_id=0")
            option(v-for="(room,rid) in rooms",:value="rid") {{room.name}}

          button.btn(v-for="(room,rid) in rooms",:class="{active:rid==now_place_id}",@click="switch_place(rid)") {{room.name}}
        .col-sm-5

          .form_block(v-if="now_device")
            .form-group
              .device_info
                .eng Speaker
                h1 {{now_device.name}}
              img.device_pic
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
              
              button.btn(:class="{active:now_device.light_option==lid}",@click="now_device.light_option=lid",v-for="(light,lid) in light_list") {{light.name}}
            .form-group
              label 數量
              span.input_side_btn(@click="now_device.count--") -
              input(type="number" ,v-model="now_device.count")
              span.input_side_btn(@click="now_device.count++") +
            .form-group
              label 平均使用
              br
              div(v-if="now_device")
                button.btn(:class="{active:now_device.option==0}",@click="now_device.option=0") 很少 (0-{{now_device.rarely}}hr)
                button.btn(:class="{active:now_device.option==1}",@click="now_device.option=1") 偶爾 ({{now_device.rarely}}-{{now_device.occaionally}}hr)
                button.btn(:class="{active:now_device.option==2}",@click="now_device.option=2") 經常 ({{now_device.occaionally}}-{{now_device.often}}hr)
                button.btn(:class="{active:now_device.option==3}",@click="now_device.option=3") 頻繁 ({{now_device.often}}-{{now_device.frequently}}hr)
            .form-group
              label 購買年份
              br
              div(v-if="now_device")
                button.btn(:class="{active:now_device.buy_time_option==0}",@click="now_device.buy_time_option=0") 0-3年
                button.btn(:class="{active:now_device.buy_time_option==1}",@click="now_device.buy_time_option=1") 3-5年
                button.btn(:class="{active:now_device.buy_time_option==2}",@click="now_device.buy_time_option=2") 5-10年
                button.btn(:class="{active:now_device.buy_time_option==3}",@click="now_device.buy_time_option=3") 10年以上

            .form_group
              hr
              h2 年：{{total.value}}度 (月平均 {{ parseInt(total.value/12) }} 度)
              h4 計算清單(測試用)
              ul
                li(v-for="log in total.log") {{log}}
              hr
              ul
                li(v-for="(r,id) in total.room_sum") {{rooms[id].name}}: {{r}}度 ({{parseInt(100*r/total.value)}}%)
</template>

<script>
import device_data from '../device_data'
import rooms from '../rooms'
import {mapState,mapMutations} from 'vuex' 

device_data.forEach(obj=>{obj.option=0;});

device_data.forEach(obj=>{obj.buy_time_option=0;});

device_data.forEach(obj=>{obj.consumption=obj.default_consumption;});

export default {
  name: 'page_room',
  computed: {
    filter_device(){
      return this.devices
            .filter( device=>(device.place==this.rooms[this.now_place_id].name) );
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
        var device_c= parseInt(cump*d.count*d.consumption_mul*d.day
                *hour/1000 );
        
        if (d.type=="light") light_total +=device_c;
        total_c+=device_c;
        if (d.count>0)
        log_list.push(d.count+" x "+d.name+" ("+d.place+"): "+cump+"*"+d.consumption_mul+"*"+hour+"hr *"+d.day+" = "+device_c);

        room_sum[this.get_place_id(d.place)]+=device_c;
      });
      if (light_total==0){
       log_list.push("預設無填寫照明： 坪數 "+this.house_area_size+" * 12w = "+this.house_area_size*12+"kwh"); 
       total_c+=this.house_area_size*12;
      }
      this.set_device_result({value: total_c,log: log_list,room_sum: room_sum});
      return {value: total_c,log: log_list,room_sum: room_sum};
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
    ...mapMutations(['set_device_result'])
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


