<template lang="pug">
  section.section_room
    //SoundPanel(:now_room="rooms[now_place_id]")
    transition(name="fade")
      .dialog_edit_room(v-if="show_edit_room")
        .dialog
          h3 編輯房間
            span.close_btn(@click="show_edit_room=!show_edit_room") x
          hr
          ul
            li(v-for="(room,rid) in rooms")
              span {{rid+1}}.{{room.name}}
              span.remove_btn(v-if="room.type!='origin'",@click="removeAltRoom(room)") -
            button.btn(v-for="(room,rid) in rooms",v-if="room.type=='origin'",@click="addAltRoom(room.name)")
              span + 新增{{room.name}}
    .container
      .row
        .col-sm-12
          h3.page_title 第二階段 / 電器填寫
          div(v-if="music_enabled")
            audio(:src="'/music/'+rooms[now_place_id].name+'/1照明.mp3'" :autoplay="!debug" loop)

        .col-sm-8
          //h5 / {{rooms[now_place_id].eng}}
          //h1 {{rooms[now_place_id].name}}

          //房間選擇
          .btn_group.btn_room_selector(:class="{fixed: site_width<600 && inPageRange}")
            h1(v-for="(room,rid) in rooms",:class="{active:rid==now_place_id}",@click="switch_place(rid)") {{room.name}}
            h1.manage.hidden-xs(@click="show_edit_room=!show_edit_room") +管理房間 
          p {{rooms[now_place_id].slogan}}

          //電表跟數字
          .consumption_pointer
            .pointer_el(:style="{'animation-duration': get_duration(total.room_sum[now_place_id].value)+'s'}")
            p {{ (total.room_sum[now_place_id].value).toFixed(1) }} 度/年
          
          //場景圖片
          .room_img
            //transition-group(name="fade-delay", mode="out-in")
            
            img.scene.animated.fadeIn(
              :src="'/img/場景/'+room.eng+'2.png'" 
              style="width: 100%",
              v-for="(room,rid) in rooms",
              v-show="now_place_id==rid",
              :key="room.hash")
      
        .col-sm-4.col-controlpanel(v-if="now_device")

          //現在在編輯的電器表單
          .form_block(v-if="now_device")
            .form-group
              .device_info
                .eng {{now_device.english_name}}
                h3 {{now_device.name}} 
                  button_moreinfo(:msg="'請選擇房間主要光源'",
                                  v-if="now_device.name=='照明'")

                  button_moreinfo(msg="年耗電度數(以額定冷氣能力值、<br>CSPF值與冷氣運轉率計算)，<br>實際耗電度數將依使用情況有所差異",
                                  v-if="now_device.name=='冷氣機'")

              img.device_pic(:src="'/img/電器/icon_'+now_device.name+'.svg'")

              // 改成顯示目前填寫的平均值，若沒有電器則呈現0
              br
              p.device_watt 用電度數: {{now_device.device_consumption || 0}} (度/年)   
              //- p.device_watt(v-if="!isNaN(now_device.consumption) && now_device.consumption>0") {{now_device.consumption}}
              //- p.device_watt(v-if="(isNaN(now_device.consumption) || now_device.consumption=='') && now_device.default_consumption!=-1") {{now_device.default_consumption}}
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
              //預設規格
              li(:class="{active: alter_id==-1}"
                 @click="alter_id=-1") {{now_device.consumption}}瓦 ({{now_device.count}}個)
              //其他規格
              li(:class="{active: alter_id==alt_id}",
                v-if="now_device.alter_specs.length>0" 
                v-for="(alt,alt_id) in now_device.alter_specs",
                @mousedown="alter_id=alt_id") {{alt.consumption}}瓦 ({{alt.count}}個)
                span.remove_alt_btn(@click = "removeAlt(now_device,alt_id)") &nbsp; x
              li.more(@click="add_other_spec(now_device)") +

          //電器消耗
          .form_block.contanier-fluid
            .form-group.row(v-if="now_device.name=='冷氣機'")
              .col-md-5.col-xs-4
                label 坪數
                .extent-border
              .col-md-7.col-xs-8(v-if="now_device_profile")
                input(type="number",v-model.number="now_device_profile.area_size",
                      :disabled="(now_device_profile.ac_power!=0 && now_device_profile.ac_power)?true:false" ,
                      @change = "now_device_profile.area_size=now_device_profile.area_size<=1?1:now_device_profile.area_size")
            .form-group.row(v-if="now_device.name=='冷氣機'")
              .col-md-5.col-xs-4
                label 額定冷氣能力(kW)
                  button_moreinfo(:msg="`建議值：${acinfo.ac_power_min}~${acinfo.ac_power_max} kW`",
                                  v-if="now_device.name=='冷氣機'")
              .col-md-7.col-xs-8(v-if="now_device_profile")
                input(type="number",v-model.number="now_device_ac_power_kw",
                      placeholder="選填")
            .form-group.row(v-if="now_device.name=='冷氣機'")
              .col-md-5.col-xs-4
                label CSPF值
              .col-md-7.col-xs-8(v-if="now_device_profile")
                input(type="number",v-model.number="now_device_profile.cspf"  min=0 max=71)
            .form-group.row(v-else-if="now_device.type=='normal'")
              .col-md-5.col-xs-4
                label(v-if="now_device.name=='冷氣機'") 額定冷氣能力
                label(v-else) 電器瓦數
              .col-md-7.col-xs-8(v-if="now_device_profile")
                input(type="number",v-model.number="now_device_profile.consumption")
            .form-group.row(v-if="now_device.type=='light'")
              .col-xs-12
                ul.room_device_list
                  li(:class="{active:now_device_profile.light_option==lid}",
                    @click="now_device_profile.light_option=lid;now_device_profile.consumption=light_list[lid].value",
                    v-for="(light,lid) in light_list") {{light.name}}
              .col-xs-4
                label 電器瓦數
              .col-xs-8
                input(type="number",v-model="now_device_profile.consumption")
            .form-group.row
              .col-md-5.col-xs-4
                label 數量
              .col-md-7.col-xs-8
                .btn_with_side
                  span.input_side_btn(@click="now_device_profile.count-=now_device_profile.count>0?1:0") -
                  input(type="number" ,v-model="now_device_profile.count")
                  span.input_side_btn(@click="now_device_profile.count++") +
            .form-group.row
              .col-xs-4
                label 平均使用
              .col-xs-12
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

            .form-group.row
              .col-xs-4
                label 購買年份
              .col-xs-12
                .btn_group_inline(v-if="now_device_profile")
                  button.btn(v-for="op in get_year_options(now_device.year_options)" ,
                            :class="{active:now_device_profile.buy_time==op.value}",
                            @click="now_device_profile.buy_time=op.value")
                    span {{op.label}}
                    span.degree {{op.degree}}
                

            .form_group.test_info.row
              
              .col-sm-12
                h5 計算清單 ({{rooms[now_place_id].name}}):
              .col-sm-12
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
// import SoundPanel from './SoundPanel'
import button_moreinfo from './button_moreinfo'
import $ from 'jquery'
import Vue from 'vue'

export default {
  name: 'page_room',
  mounted(){

    axios.get("/api/page/acinfo").then((res)=>{
      this.$set(this,"acinfo",JSON.parse(res.data.content));
      console.log("ac info Data Loaded!", this.acinfo )
    })
    // console.log("page room mounted.");
    //assign hash number for rooms
    this.rooms.forEach(room=>{
      room.type="origin"
      room.hash = this.getHash()
    })
    //get devices
    axios.get("/api/devices").then(res=>{
      let device_data = res.data

      //add other by reduce all device data
      let other_devices = 
        device_data.map(o=>o.name)
                   .filter((d,i,arr)=>arr.indexOf(d)==i)
                   .map(devicename=>device_data.find(r=>r.name==devicename))
      other_devices = JSON.parse(JSON.stringify(other_devices))
      other_devices.forEach(device=>device.place="其他")
      device_data = device_data.concat(other_devices)

      //initial device options
      device_data.forEach(obj=>{
        let deviceBelongRoomId = this.rooms.map(o=>o.name).indexOf(obj.place) 
        obj.place_id=deviceBelongRoomId
        obj.option=obj.default_freq_option
        obj.light_option=null
        obj.buy_time=""
        obj.alter_specs=[]
        obj.consumption=obj.default_consumption  
        obj.area_size=obj.default_area_size   
        obj.ac_power=obj.default_ac_power   
        obj.cspf=obj.default_cspf      
        if (obj.type=="light"){
          obj.consumption=this.light_list[0].value
        }
        if (obj.type=="hotwater"){
          obj.consumption=1070
        }
        
        obj.roomtype="origin"
        obj.hash=this.rooms[deviceBelongRoomId].hash
      })

      this.devices = device_data    
      this.originDevicesData = JSON.parse(JSON.stringify(this.devices))    
    
      //logined user with last data       
      if(store.state.member_data.users_id != "0")
      {
        try {
          axios.get("/api/devicesLast/"+store.state.member_data.users_id).then((res)=>
        {
            let device_data_new=res.data
            device_data_new.forEach((obj,i,arr)=>{
              //let deviceBelongRoomId = this.rooms.map(o=>o.name).indexOf(obj.place) 
              //obj.place_id=deviceBelongRoomId
              obj.option=obj.default_freq_option
              obj.light_option=null
              obj.buy_time=""
              obj.alter_specs=[]
              obj.consumption=obj.default_consumption     
              if (obj.type=="light"){
                obj.consumption=this.light_list[0].value
              }
              if (obj.type=="hotwater"){
                obj.consumption=1070
              }
              
              obj.roomtype="origin"
              obj.hash=this.rooms[obj.place_id].hash          
            
              obj.count=obj.dvclog_count
              this.devices.forEach((d, i, arr)=>{
                if (d.id==obj.id && d.place_id==obj.place_id) {
                  //console.log("index:",i);
                  this.devices.splice(i,1);
                }
              })
           })
           //this.devices.splice(0,1);
           //this.devices.splice(1,1);
           this.devices = device_data_new.concat(this.devices)
           //this.originDevicesData = JSON.parse(JSON.stringify(this.devices))    
           console.log("device_data_new:",device_data_new)
           console.log("this.devices:",this.devices)
        }) 
        } catch (error) {
          
        }         
      }
    })    
    //console.log("devicelogLast if");
  //console.log("devicelogLast if end");

  },
  components: {
    button_moreinfo
  },
  computed: {
    use_ac_space_mult(){
      if (this.acinfo){
        return this.acinfo.space_caculate_mult
      }
      return 0.15*3024/860
    },
    use_ac_power_range() {
      if (this.acinfo){
        
        return {
          max: this.acinfo.ac_power_max*1000,
          min: this.acinfo.ac_power_min*1000,
        }
      }
      return {
        max: 71000,
        min: 0
      }
    },
    now_device_ac_power_kw: {
      get(){
        if (this.now_device_profile.ac_power==0){
          return null
        }
        if (this.now_device_profile.ac_power>this.use_ac_power_range.max){
          this.now_device_profile.ac_power=this.use_ac_power_range.max
        }
        if (this.now_device_profile.ac_power<this.use_ac_power_range.min){
          this.now_device_profile.ac_power=this.use_ac_power_range.min
        }
        return this.now_device_profile.ac_power/1000
      },
      set(value){
        //- console.log("ASDSDDSADSADSA",this.ac_power_max)
        if (value==null){
          value=0
        }
        if (value>this.use_ac_power_range.max){
          value=this.use_ac_power_range.max
        }
        if (value<this.use_ac_power_range.min){
          value=this.use_ac_power_range.min
        }

        this.now_device_profile.ac_power=value*1000
      }
    },
    //目前房間的電器
    filter_device(){
      // console.log(this.devices.map(o=>o.place ));
      return this.devices
            .filter( device => (device.hash==this.rooms[this.now_place_id].hash) );
    },
    //當前的電器
    now_device(){
      if (this.now_device_id==-1) return null;

      let now_device = this.filter_device[this.now_device_id]
      let avg_consumption_list = [now_device].concat(now_device.alter_specs).map(obj=>({
        count: obj.count,
        consumption: obj.consumption
      }))
      let total_device_count = avg_consumption_list.map(o=>o.count).reduce((a,b)=>(a+b),0)

      now_device.avg_consumption = avg_consumption_list.reduce((total,obj)=>( total+obj.count*obj.consumption) ,0)/total_device_count
      
      return this.filter_device[this.now_device_id];

    },
    //根據alt id 選擇主規格或是alter_specs裡面的自訂規格
    now_device_profile(){   
      return this.alter_id==-1 ? this.now_device : this.now_device.alter_specs[this.alter_id]
    },
    inPageRange(){
      // console.log("element room",this.$el)
      let sc = this.scrollTop
    
      if (this.$el){
        let page_top = this.$el.offsetTop;
        let page_height = this.$el.offsetHeight;
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
      var room_sum=this.rooms.map(o=>0);

      //devices 每一個電器種類
      //profile 每一份不同規格的電器

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
          profile.max_per_device_consumption=0
        })
        profiles.forEach(profile=>{
          //不同計算消耗電的方式
          if (device.type=="normal"){
            cump=profile.consumption;
          }
          if (device.type=="light"){
            cump=profile.consumption;
            // cump=this.light_list[profile.light_option].value;
            
          }if (device.type=="hotwater"){
            cump=1070;
          }
          if (device.type=="ac"){
            if (profile.ac_power){
              cump=profile.ac_power/(profile.cspf || 1);
            }else{
              cump=1000*profile.area_size*this.use_ac_space_mult/(profile.cspf || 1);
            }
            profile.consumption= parseInt(cump)
            
          }

          //計算使用時間 ＊ 單位時間能耗
          var hour = [device.rarely,device.occasionally,device.often,device.frequently][profile.option];
          var use_hour_per_year = device.day*hour;
          var per_profile_device_consumption = Math.round(cump*device.consumption_mul * device.day * hour/1000 );
          var profile_consumption = profile.count * per_profile_device_consumption
          // console.log("profile",cump,profile.count,device.consumption_mul,device.day,hour)

          //老舊加乘
          let optiontext = profile.buy_time.replace("+","")
          if (optiontext.indexOf("-")!=-1) optiontext=optiontext.split("-")[0]
          let is_old = parseInt(optiontext)>=device.old_condition && device.old_condition!=-1
          
          if (is_old){
            profile_consumption*=1.5
          }

          if (device.type=="light"){
            light_total +=profile_consumption;
          } 
          total_c+=profile_consumption;

        
          //將計算是記錄下來    
          log_list.push({
            content: device.name +" x "+ profile.count 
                    + " ("+["很少","偶爾","經常","頻繁"][profile.option]+")"
                    +(is_old?"(老舊*1.5)":"") + " : " +profile_consumption+" (度 / 年)",
            place: device.place,
            hash: device.hash
          });
        
          //設定設備清單上的單電器消耗量
          if ((!profile.max_per_device_consumption || per_profile_device_consumption>profile.max_per_device_consumption )) {
            profile.max_per_device_consumption = per_profile_device_consumption ;
          }
          device.hour_consumption += cump;
          device.device_consumption += profile_consumption;

          //一年總共用的小時數（計算比較電器用)
          device.use_hour_per_year = use_hour_per_year;

          //加總到房間總耗電量
          let room_id = this.get_place_id(device.hash);
          console.log(profile_consumption)
          room_sum[room_id]+=profile_consumption;
        })
            
        //如果沒有任何成立的profile 預設值為0
        device.hour_consumption = device.hour_consumption || 0
        device.device_consumption = device.device_consumption || 0
      
      });

      // // 處理預設照明
      // if (light_total==0){
      //   log_list.push(
      //   {
      //     content: "無填寫預設照明： 坪數 "+this.house_area_size+" * 12w = "+this.house_area_size*12+"度",
      //     place: "all"
      //   });
      //   total_c+=this.house_area_size*12;
      //   room_sum=room_sum.map((value,i)=>value+this.house_area_size*12*this.rooms[i].default_percentage);
      //   room_sum.forEach((value,i)=>{
      //   log_list.push(
      //     {
      //       content: "預設照明： 坪數 "+this.house_area_size+" * 12w = "+this.house_area_size*12+"度",
      //       place: this.rooms[i].name,
      //       hash: this.rooms[i].hash
      //     });
      //  })
      // }
      // console.log(room_sum)


      //後處理房間百分比與包資料
      room_sum=room_sum.map(value=>({
        value,
        percentage: parseInt(100*value/total_c)
      }));

      let result={
        value: total_c,
        log: log_list,
        room_sum: room_sum,
        user_filled: this.devices.map(
            device => [device].concat(device.alter_specs)
                              .filter(o=>o.count>0)
                              .map(o=>parseInt(o.count))
        ).reduce((a,b) => (a+b),0)
      };
      
      //回傳與設定vuex全域變數
      this.set_devices(this.devices.slice());
      this.set_device_result(result);
      return result;
    },
    ...mapState(['house_area_size','site_width','scrollTop','debug','music_enabled'])
  },
  methods: {
    getHash(){
      return parseInt(Math.random()*10000000)
    },
    //加入新房間
    addAltRoom(roomName){
      let clone  = 
        JSON.parse(JSON.stringify(
          this.rooms.find(r=>r.name==roomName && r.type=="origin")
        ))
      console.log(clone)
      clone.type = "alt"
      clone.hash = this.getHash();
      let room_devices = this.originDevicesData.filter(d=>d.roomtype=="origin" && d.place==clone.name)
      let clone_devices = 
        JSON.parse(JSON.stringify(
          room_devices
        ))
      clone_devices.forEach(d=>{
        d.roomtype="clone"
        d.hash=clone.hash
      })
      this.devices = this.devices.concat(clone_devices)
      this.rooms.push(clone)
    },
    //移除複製的房間
    removeAltRoom(room){
      let newid = this.get_place_id(room.hash)-1
      newid = newid>=0?newid:0
      this.now_place_id = newid
      this.devices=this.devices.filter(d=>d.hash!=room.hash)
      this.rooms=this.rooms.filter(r=>r.hash!=room.hash)
      
    },
    //移除其他規格（如果沒規格舊回歸alt_id)
    removeAlt(device,id){
      this.alter_id=(id-1)>=0?(id-1):-1
      Vue.nextTick(()=>{
        device.alter_specs.splice(id,1)

      })
    },
    //加入一個自訂規格
    add_other_spec(device){
      if (!device.alter_specs){
        device.alter_specs = []
      }
      device.alter_specs.push({
        consumption: 0,
        type: device.type,
        hash: device.hash,
        count: 0,
        buy_time: "",
        option: device.default_freq_option,
        light_option: null
      });
      // console.log(device.alter_specs)
      this.alter_id = device.alter_specs.length-1
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
      this.alter_id=-1
      this.now_device_id=id
      // this.now_device.count=1;
    },
    switch_place(id){
      this.alter_id=-1
      this.now_place_id=id
      this.now_device_id=0
    },
    //取得hash對應的房間
    get_place_id(hash){
      var result=0;
      this.rooms.forEach((d,i)=>{
        if (d.hash==hash)
          result=i;
      });
      return result;
    },
    get_duration(val){
      return 3* (Math.pow( Math.E, - val/2000 ) )+0.6;
    },
    filterlog(log){
      // console.log("log",log)
      return log.filter(o=>o.hash==this.rooms[this.now_place_id].hash ) 
    },
    ...mapMutations(['set_device_result','set_devices'])
  },
  data(){
    return {
      now_place_id: 0,
      devices: [],
      now_device_id: 0,
      rooms,
      user_filled: false,
      alter_id: -1,
      show_edit_room: false,
      acinfo: {},
      originDevicesData: [],
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
  watch: {
    "now_device_profile.consumption": function(){
      if (this.now_device_profile){
        if (this.now_device_profile.consumption=="" || isNaN(this.now_device_profile.consumption) || this.now_device_profile.consumption<0 ){
          this.now_device_profile.consumption=0
        }
      }
    }
  }

}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>


