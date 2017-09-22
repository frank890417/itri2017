<template lang="pug">
  section.section_room
    SoundPanel(:now_room="rooms[now_place_id]")
    .container
      .row
        .col-sm-12
          h2 第二階段 / 電器填寫
          br
          br
        .col-sm-8
          h4 / {{rooms[now_place_id].eng}}
          //h1 {{rooms[now_place_id].name}}

          //房間選擇
          .btn_group.btn_room_selector(:class="{fixed: site_width<600 && inPageRange}")
            h1(v-for="(room,rid) in rooms",:class="{active:rid==now_place_id}",@click="switch_place(rid)") {{room.name}}
            h1.manage +管理房間 
          h4 {{rooms[now_place_id].slogan}}

          //電表跟數字
          .consumption_pointer
            .pointer_el(:style="{'animation-duration': get_duration(total.room_sum[now_place_id].value)+'s'}")
            h4 {{total.room_sum[now_place_id].value}} 度/年
          
          //場景圖片
          .room_img
            transition-group(name="fade",mode="out-in")
              img.scene(
                :src="'/img/場景/'+room.eng+'2.png'" 
                style="width: 100%",
                v-for="room in rooms",
                v-show="rooms[now_place_id].eng==room.eng",
                :key="room")
       
        .col-sm-4(v-if="now_device")

          //現在在編輯的電器表單
          .form_block(v-if="now_device", style="margin-top: 15%")
            .form-group
              .device_info
                .eng {{now_device.english_name}}
                h3 {{now_device.name}}
                  button_moreinfo(:msg="'請選擇房間主要光源'",
                                  v-if="now_device.name=='照明'")

              img.device_pic(:src="'/img/電器/icon_'+now_device.name+'.svg'")
              
              .device_watt(v-if="!isNaN(now_device.consumption) && now_device.consumption>0") {{now_device.consumption}}
              .device_watt(v-if="(isNaN(now_device.consumption) || now_device.consumption=='') && now_device.default_consumption!=-1") {{now_device.default_consumption}}
              //- img

          //電器選取清單
          .form_block
            .form-group
              ul.room_device_list
                li(v-for="(device,did) in filter_device",@click="switch_device(did)",:class="{active: now_device_id==did,empty: device.count==0}") 
                  span(v-if="now_device_id==did")
                  span {{device.name}}
                  // div (數量:{{device.count}} {{["很少","偶爾","經常","頻繁"][device.option]}}使用 {{ ["0-3年","3-5年","5-10年","10年以上"][device.buy_time] }})

          .form_block.spec_select
            ul 
              li(:class="{active: alter_id==-1}"
                 @click="alter_id=-1") {{now_device.consumption}}w x {{now_device.count}}
              li(:class="{active: alter_id==alt_id}",
                v-if="now_device.alter_specs" 
                v-for="(alt,alt_id) in now_device.alter_specs",
                @click="alter_id=alt_id") {{alt.consumption}}w x {{alt.count}}
              li.more(@click="add_other_spec(now_device)") +
          //電器消耗
          .form_block
            .form-group(v-if="now_device.type=='normal'")
              label 電器瓦數
              input(type="number",v-model="now_device_profile.consumption")
            .form-group(v-if="now_device.type=='light'")
              ul.room_device_list
                li(:class="{active:now_device_profile.light_option==lid}",
                   @click="now_device_profile.light_option=lid",
                   v-for="(light,lid) in light_list") {{light.name}}
            .form-group
              label 數量
              span.input_side_btn(@click="now_device_profile.count-=now_device_profile.count>0?1:0") -
              input(type="number" ,v-model="now_device_profile.count")
              span.input_side_btn(@click="now_device_profile.count++") +
            .form-group
              label 平均使用
              br
              .btn_group_inline(v-if="now_device")
                button.btn(:class="{active:now_device_profile.option==0}",@click="now_device_profile.option=0")
                  span 很少
                  span.degree {{now_device.rarely}}hr
                button.btn(:class="{active:now_device_profile.option==1}",@click="now_device_profile.option=1")
                  span 偶爾
                  span.degree {{now_device.occasionally}}hr
                button.btn(:class="{active:now_device_profile.option==2}",@click="now_device_profile.option=2")
                  span 經常
                  span.degree {{now_device.often}}hr
                button.btn(:class="{active:now_device_profile.option==3}",@click="now_device_profile.option=3")
                  span 頻繁
                  span.degree {{now_device.frequently}}hr

            .form-group
              label 購買年份
              br
              .btn_group_inline(v-if="now_device_profile")
                button.btn(v-for="op in get_year_options(now_device.year_options)" ,
                           :class="{active:now_device_profile.buy_time==op.value}",
                           @click="now_device_profile.buy_time=op.value")
                  span {{op.label}}
                  span.degree {{op.degree}}
               

            .form_group.test_info
              hr
              //h4 一年 {{total.value}} 度 (平均 {{ parseInt(total.value/12) }} 度)
              h4 計算清單 ({{rooms[now_place_id].name}}):
              ul
                li(v-for="log in filterlog(total.log)") {{log.content}}
              //hr
              //ul
                li(v-for="(r,id) in total.room_sum") {{r.value}}度 ({{r.percentage}}%)
</template>

<script>
// import device_data from '../device_data'
import rooms from '../rooms'
import {mapState,mapMutations} from 'vuex'
import SoundPanel from './SoundPanel'
import button_moreinfo from './button_moreinfo'
import $ from 'jquery'

export default {
  name: 'page_room',
  mounted (){
    console.log("page room mounted.");
    axios.get("/api/devices").then(res=>{
      var device_data = res.data
      device_data.forEach(obj=>{obj.place_id=this.rooms.map(o=>o.name).indexOf(obj.place) })
      device_data.forEach(obj=>{obj.option=0})
      device_data.forEach(obj=>{obj.light_option=0})
      device_data.forEach(obj=>{obj.buy_time=""})
      device_data.forEach(obj=>{obj.alter_specs=[]})
      device_data.forEach(obj=>{obj.consumption=obj.default_consumption})
      this.devices=device_data
      console.log(device_data)

    })
  },
  components: {
    SoundPanel,button_moreinfo
  },
  computed: {
    filter_device(){
      console.log(this.devices.map(o=>o.place));
      return this.devices
            .filter( device => (device.place==this.rooms[this.now_place_id].name) );
    },
    //當前的電器
    now_device(){
      if (this.now_device_id==-1) return null;
      return this.filter_device[this.now_device_id];
    },
    //根據alt id 選擇主規格或是alter_specs裡面的自訂規格
    now_device_profile(){   
      return this.alter_id==-1 ? this.now_device : this.now_device.alter_specs[this.alter_id]
    },
    inPageRange(){
      let sc = this.scrollTop
      let $section = $(".section_room")
      if ($(".section_room").length){
        let page_top = $section.offset().top
        let page_height = $section.outerHeight()
        let result = sc> page_top && sc < page_top + page_height
        // console.log("inRange",result)
        return result
      }
      return false
      
    },
    total(){
      var total_c = 0;
      var log_list=[];
      var light_total=0;
      var room_sum=[0,0,0,0];

      //加總電器
      this.devices.forEach((device,i)=>{
        var cump=0;

        device.hour_consumption = 0
        device.device_consumption = 0
        
        //主規格與次要規格foreach
        var profiles = 
          [device].concat(device.alter_specs)
                  .filter(o=>o.count>0)

        
        profiles.forEach(profile=>{
          //不同計算消耗電的方式
          if (device.type=="normal"){
            cump=profile.consumption;
          }
          if (device.type=="light"){
            cump=this.light_list[profile.light_option].value;
            
          }if (device.type=="hotwater"){
            cump=1070;
          }

          //計算使用時間 ＊ 單位時間能耗
          var hour=[device.rarely,device.occasionally,device.often,device.frequently][profile.option];
          var device_consumption= parseInt(cump*profile.count*device.consumption_mul*device.day
                  *hour/1000 );

          //老舊加乘
          let optiontext = profile.buy_time.replace("+","")
          if (optiontext.indexOf("-")!=-1) optiontext=optiontext.split("-")[0]
          let is_old = parseInt(optiontext)>=device.old_condition && device.old_condition!=-1
          
          if (is_old){
            device_consumption*=1.5
          }

          if (device.type=="light"){
            light_total +=device_consumption;
          } 
          total_c+=device_consumption;

        
          log_list.push({
            content: profile.count+" x "+device.name 
                    + " ("+["很少","偶爾","經常","頻繁"][profile.option]+")"
                    +(is_old?"(老舊*1.5)":"") + " : "
            //+" ("+device.place+"):  "
            //+cump+"*"+device.consumption_mul+"*"+hour+"hr *"+device.day+" = "
            +device_consumption+" (度 / 年)",
            place: device.place});
        
          //設定設備清單上的單電器消耗量
          device.hour_consumption += cump;
          device.device_consumption += device_consumption;

          //加總到房間總耗電量
          room_sum[this.get_place_id(device.place)]+=device_consumption;
        })
            
        //如果沒有任何成立的profile 預設值為0
        device.hour_consumption = device.hour_consumption || 0
        device.device_consumption = device.device_consumption || 0
      
      });
    

      //處理預設照明
      if (light_total==0){
       log_list.push(
        {content: "無填寫預設照明： 坪數 "+this.house_area_size+" * 12w = "+this.house_area_size*12+"度",
         place: "all"});
       total_c+=this.house_area_size*12;
       room_sum=room_sum.map((value,i)=>value+this.house_area_size*12*this.rooms[i].default_percentage);
       room_sum.forEach((value,i)=>{
          log_list.push(
          {content: "預設照明： 坪數 "+this.house_area_size+" * 12w = "+this.house_area_size*12+"度",
           place: this.rooms[i].name});
       })
      }
      // console.log(room_sum)

      //後處理房間百分比與包資料
      room_sum=room_sum.map(value=>({value,percentage: parseInt(100*value/total_c)}));

      var result={
        value: total_c,
        log: log_list,
        room_sum: room_sum,
        user_filled: this.devices.map(dev=>dev.count).reduce((a,b)=>(a+b),0)
      };
      //回傳與設定vuex全域變數
      this.set_devices(this.devices.slice());
      this.set_device_result(result);
      return result;
    },
    ...mapState(['house_area_size','site_width','scrollTop'])
  },
  methods: {
    //加入一個自訂規格
    add_other_spec(device){
      if (!device.alter_specs){
        device.alter_specs = []
      }
      device.alter_specs.push({
        consumption: 0,
        type: device.type,
        count: 0,
        buy_time: "",
        option: 0,
        light_option: 0
      });
      console.log(device.alter_specs)
    },
    get_year_options(op_array_text){
      let ar = JSON.parse(op_array_text)
      let options = []
      let label2 = ["新","老舊"]
      let label3 = ["新","一般","老舊"]
      let label4 = ["新","一般","久","老舊"]

      //產生選項(內部記錄用)
      ar.forEach((d,i)=>{
        if (i==0){
          options.push({
            value: "0-"+d,
            degree: (d+'年') 
          })
        }else if (i<=ar.length-1){
          options.push({
            value: ar[i-1]+"-"+d,
            degree: (d+'年') 
          })
        }
        if (i==ar.length-1){
          options.push({
            value: d+"+",
            degree: '以上'
          })
        }
      })

      let use_label_set = null
      //判斷長度給定選項
      switch (options.length) {
        case 2:
          use_label_set = label2
          break;
        case 3:
          use_label_set = label3
          break;
        case 4:
          use_label_set = label4
          break;
      }
      options.forEach((o,i,arr)=>{
        o.label=use_label_set[i]
      })
      return options
      
    },
    switch_device(id){
      this.now_device_id=id;
      this.alter_id=-1;
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
    filterlog(log){
      console.log("log",log)
      return log.filter(o=>o.place==this.rooms[this.now_place_id].name ) 
    },
    ...mapMutations(['set_device_result','set_devices','scrollTop','site_width'])
  },
  data(){
    return {
      now_place_id: 0,
      devices: [],
      now_device_id: 0,
      rooms,
      user_filled: false,
      alter_id: -1,
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


