<template lang="pug">
  section.section_solution.grey
    .container
      .row.text-left
        .col-sm-12
          h1.title SOLUTION
            br.visible-xs
            span.chinese 節能處方箋
    .full_width_bar.yellow
    .container
      .row.text-left
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
              span (資料填寫不足！無法計算)<br><br><br>
        .card.col-sm-8
          .card_inner
            .card_title
              h5 Comsumption Percentage
              h2 用電比例視覺化({{now_room.name}})
            .btn-bubble-all(@click="now_place_id=-1", v-if="now_place_id!=-1")  &lt; 顯示總比例
            graph_bubble(:datas="device_value",
                         :use_power="0.33",
                         :use_unit="'度'",
                         :use_mul="8",
                         @set_device="set_advice_device",
                         :size="{height: 600}"
                        )
            
            ul.room_part_value
              li(v-for="(r,id) in device_result.room_sum" ,
                 :style="{width: (r.percentage+15)+'%'}",
                 v-if="r.percentage",
                 @click="now_place_id=id" ,
                 :class="{active: id==now_place_id}") {{rooms[id].name}} {{r.percentage}}%
        .card.col-sm-12.card_prescription
          .card_inner
            h5 Saving Prescription
            h2 節能處方箋
            //- hr
            p(v-if="old_devices.length>0")
              span 你的 
              span(v-for="odev in old_devices") 「{{odev.name}}」
              span 已經老舊囉！
              span <br>除了採用處方，更可以考慮更換有節能標章的新電器，聰明的省電！
            br

            .btn_group_inline.advice_device_list(:class="{fixed: site_width<600 && inPageRange}")
              button.btn(
                  v-for="(dev,did) in advice_device_list",
                  :class="{active:dev==advice_device}",
                  @click="set_advice_device(dev)"
                ) 
                img(:src="'/img/電器/icon_'+dev+'.svg'")
                div {{dev}}  

            h3 行動處方
            hr
            transition(name="fade",mode="out-in")
              div(:key="advice_device")
                .col-sm-12(v-if="get_advices(advice_device,cataname).length",
                    v-for="cataname in ['聰明省','好選擇','好習慣']")
                  .row.row_cata
                    .col-sm-2.col_advice_cata
                      img.advice_cata_icon(:src="'/img/建議icon/advice_cata_'+cataname+'.svg'")
                    ul.col-sm-10
                      li 
                        h4 {{cataname}}
                      li(v-for="(adv,id) in get_advices(advice_device,cataname)")
                        div {{id+1}}. {{adv.content}}                  
        
        .card.col-sm-12(v-if="advice_devices.length>0")
          .card_inner.nominh
            h2 節能電器推薦
              a.small(:href="get_cata_link(advice_device)", target="_blank") (查看更多推薦電器)
            input(v-model="recommend_advice_filter", placeholder="輸入搜尋關鍵字...")
            hr
            .recommend_list.row
              .col-sm-3
                img(:src="'/img/電器/icon_'+advice_device+'.svg'")
                //select.brandlist(v-model="filter_brand")
                  option(value="") 全部品牌
                  option(v-for="brand in advice_brands", :value="brand") {{brand}}
              .col-sm-9.row
                .col-sm-6.option(v-for="ad_dev in filter_value(advice_devices.slice(advice_index*6,advice_index*6+6),recommend_advice_filter,filter_brand)")
                  .info
                    //- pre {{ad_dev}}
                    
                    div(v-if="ad_dev.infos")
                      div {{info}}
                      div(v-for="info in parse_json(ad_dev.infos)")
                        span {{info.label}}： 
                        span {{info.content}}                        
                    div(v-else)
                      .device_title 品牌 &nbsp;|&nbsp; {{ad_dev.company}}
                      .device_com 型號 &nbsp;|&nbsp; {{ad_dev.itemid}}
                      .device_com 尺寸 &nbsp;|&nbsp; {{ad_dev.size}}
                      .device_com 年耗電量 &nbsp;|&nbsp; {{ad_dev.consumption}}
                      
                    a(:href="ad_dev.link" target="_blank" title="點擊前往網站")
                      i.fa.fa-external-link
                  hr
                .col-sm-12
                  .nav_btns
                    .btn.btn_pre(@click="advice_index -=advice_index>0?1:0") &lt; 上6筆
                    .pagenum {{advice_index+1}} / {{parseInt(advice_devices.length/6)}}
                    .btn.btn_post(@click="advice_index+=1") 下6筆 &gt;
</template>

<script>
import { mapState, mapMutations } from 'vuex'
import graph_bubble from './graph_bubble'
import rooms from '../rooms'
// import advices from '../advices'
// import advice_devices from '../advice_devices_compiled' 
import Axios from 'axios'

var advice_catas = [{"value":0,"name":"全部"},{"value":1,"name":"冷氣機"},{"value":59,"name":"無風管空調機"},{"value":8,"name":"電扇"},{"value":5,"name":"除濕機"},{"value":4,"name":"電冰箱"},{"value":7,"name":"電視機"},{"value":9,"name":"螢光燈管"},{"value":6,"name":"洗衣機"},{"value":2,"name":"乾衣機"},{"value":10,"name":"吹風機"},{"value":11,"name":"烘手機"},{"value":12,"name":"溫熱型開飲機"},{"value":13,"name":"冰溫熱型開飲機"},{"value":14,"name":"冰溫熱型飲水機"},{"value":15,"name":"汽車"},{"value":16,"name":"機車"},{"value":17,"name":"安定器內藏式螢光燈泡"},{"value":20,"name":"顯示器"},{"value":19,"name":"燃氣台爐"},{"value":18,"name":"即熱式燃氣熱水器"},{"value":21,"name":"電鍋_電子鍋"},{"value":23,"name":"貯備型電熱水器"},{"value":22,"name":"電熱水瓶"},{"value":24,"name":"出口及避難指示燈"},{"value":25,"name":"DVD(錄)放影機"},{"value":26,"name":"溫熱型飲水機"},{"value":27,"name":"室內照明燈具"},{"value":28,"name":"組合音響"},{"value":29,"name":"緊密型螢光燈管"},{"value":32,"name":"影印機"},{"value":33,"name":"印表機"},{"value":34,"name":"空氣清淨機"},{"value":35,"name":"道路照明燈具"},{"value":41,"name":"浴室用通風電扇"},{"value":42,"name":"壁式通風電扇"},{"value":37,"name":"筆記型電腦"},{"value":36,"name":"桌上型電腦"},{"value":46,"name":"空氣源式熱泵熱水器"},{"value":47,"name":"排油煙機"},{"value":48,"name":"微波爐"},{"value":43,"name":"軸流式風機"},{"value":49,"name":"離心式風機"},{"value":50,"name":"螢光燈管用安定器"},{"value":51,"name":"電烤箱"},{"value":53,"name":"貯(儲)備型電開水機"},{"value":54,"name":"發光二極體燈泡"},{"value":56,"name":"LED平板燈"},{"value":57,"name":"在線式不斷電式電源供應器"},{"value":58,"name":"天井燈"},{"value":60,"name":"筒燈及嵌燈"}];

export default {
  name: 'page_solution',
  data() {
    return {
      now_place_id: -1,
      rooms,
      advices: [],
      advice_device: '冷氣機',
      sound_expand: null,
      advice_devices: [],
      advice_catas: advice_catas,
      recommend_advice_filter: "",
      filter_brand: "",
      advice_index: 1
    };
  },
  components: {
    graph_bubble
  },
  mounted() {
    console.log("solution mounted");
    this.sound_expand = new Audio("/music/%E5%8B%95%E6%85%8B%E5%9C%96%E8%A1%A8%E9%9F%B3%E6%95%88/%E6%94%BE%E5%A4%A7.mp3");
    Axios.get('/api/advices').then((res)=>{
      this.advices=res.data
    })
    Axios.get('/api/advice_devices/'+this.advice_device).then((res)=>{
      this.advice_devices=res.data
    })
  },
  watch: {
    now_place_id() {
      this.sound_expand.play();
    },
    advice_device(){
      Axios.get('/api/advice_devices/'+this.advice_device).then((res)=>{
        this.advice_devices=res.data
      })
    },
  },
  computed: {
    ...mapState(['device_result', 'devices','scrollTop','site_width']),
    advice_brands(){
      return this.advice_devices
              .map(o=>o.brand)
              .filter((d,i,arr)=>arr.indexOf(d)==i)
    },
    advice_device_list() {
      return this.advices
                    .map(o => o.device)
                    .filter((d, i, arr) => arr.indexOf(d) == i);
    },
    sorted_devices() {
      //取的排序好的電器(前三名)
      // console.log( this.devices);
      if (this.now_place_id == -1) {
        //全屋排名，利用不重複明稱，撈filter同名稱reduce累加，模擬成電器資料
        return this.uni_name_list.map(dev_name => ({
          name: dev_name,
          place: "all",
          device_consumption: this.devices
            .filter(dev => dev.name == dev_name)
            .map(o => o.device_consumption)
            .concat([0, 0])
            .reduce((a, b) => (a + b))
        }))
          .sort((a, b) => (b.device_consumption - a.device_consumption))
          .filter((obj) => obj.device_consumption > 0)
          .slice(0, 3);
      } else {
        return this.devices
          .filter(((obj) => obj.place == this.rooms[this.now_place_id].name))
          .filter((obj) => obj.device_consumption > 0)
          .sort((a, b) => (b.device_consumption - a.device_consumption))
          .slice(0, 3);
      }


    },
    now_room() {
      //取得現在房間(-1為全屋子)
      if (this.now_place_id == -1) {
        return { name: "整體用電", eng: "Total Consumption", pic: "all" };
      } else {
        return this.rooms[this.now_place_id];
      }
    },
    now_consumption() {
      //取得現在消耗量(-1為全屋子)
      if (this.now_place_id == -1) {
        return parseInt(this.device_result.room_sum.map(o => o.value).reduce((a, b) => a + b));
      } else {
        return parseInt(this.device_result.room_sum[this.now_place_id].value)
      }
    },
    uni_name_list() {
      //取得不重複的電器類別名稱
      return this.devices.map(obj => obj.name)
        .filter((obj, ind, arr) => arr.indexOf(obj) == ind)
    },
    device_value() {
      // var room_total=this.device_result.room_sum[this.now_place_id].value;
      // 設備數值
      if (this.now_place_id != -1) {
        //項目不filter，但是不是當前房間的值為0(先產生物件)
        return this.devices
          // .filter((obj)=>obj.place==this.rooms[this.now_place_id].name)
          // .filter((obj)=>obj.device_consumption>0)
          .map((obj) => ({
            name: obj.name,
            place: obj.place,
            value: (obj.place == this.rooms[this.now_place_id].name) ? obj.device_consumption : 0,
            devices: obj.alter_specs
          }))
          //接續all房間值為零的物件
          .concat(this.uni_name_list.map(dev_name => ({
            name: dev_name,
            place: "all",
            value: 0
          })));
      } else {
        //假設一個"all"房間
        // console.log(this.uni_name_list);
        var result = this.uni_name_list.map(dev_name => ({
          name: dev_name,
          place: "all",
          value: this.devices
            .filter(dev => dev.name == dev_name)
            .map(o => o.device_consumption)
            .concat([0, 0])
            .reduce((a, b) => (a + b))
        }));
        //接續所有電器的零物件
        result = result.concat(
          this.devices.map((obj) => ({
            name: obj.name,
            place: obj.place,
            value: 0,
          }))

        )
        // console.log("result",result)
        return result
      }

    },
    old_devices() {
      return this.devices.filter(dev => dev.buy_time_option == 3)
    },

    inPageRange(){
      let sc = this.scrollTop
      
      if (this.$el){
        let page_top = this.$el.offsetTop
        let page_height = this.$el.offsetHeight
        let result = sc> page_top && sc < page_top + page_height
        // console.log("inRange",result)
        return result
      }
     
  
      return false
      
    }
  },
  methods: {
    parse_json(text){
      return JSON.parse(text)
    },

    ...mapMutations([]),
    filter_value(array_obj, filter, brand) {
      return array_obj.filter((o) => JSON.stringify(Object.values(o)).indexOf(filter) != -1)
                      .filter(o => brand=="" || o.brand == brand )
    },
    room_part(room_value) {
      var percent = this.get_room_percentage(room_value);
      return {
        width: percent + "%",

      }
    },
    get_room_percentage(room_value) {
      return parseInt(100 * room_value / this.device_result.value);
    },
    get_advices(device_name, cata) {
      var result = this.advices
        .filter((obj) => obj.device == device_name)
        .filter((obj) => obj.cata == cata)
        .sort((a, b) => (b.cata > a.cata))
      // console.log(result)
      return result;
    },
    set_advice_device(name) {
      this.advice_device = name;
      this.advice_index = 0;
      store.state.advice_device = name;
      console.log("trigger emit: " + name);
    },
    get_cata_link(devname) {
      var dev = this.advice_catas.find(o => o.name == devname);
      if (dev) {
        return "http://www.energylabel.org.tw/purchasing/product/upt.aspx?p0=" + dev.value
      } else {
        return "#"
      }
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">

</style>
