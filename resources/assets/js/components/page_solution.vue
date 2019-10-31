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
          .card_inner.minheight
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
          .card_inner.minheight
            .card_title
              h5 Comsumption Percentage
              h2 用電比例視覺化({{now_room.name}})
            .btn-bubble-all(@click="now_place_id=-1", v-if="now_place_id!=-1") 
              i.fa.fa-angle-left.mr-4
              span 顯示總比例
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

        .card.col-sm-12.card_distribution
          .card_inner
            .card_title
              h5 Comsumption Distribution
              h2 用電分佈
            div.mt-2
              svg_inline_distribution(src="/img/roomVisualization.svg",
                                      :nodes="distributionData")
            div 
              h4.pull-left.m-0 全屋用電： {{ total_consumption }} 度
              span.pull-right * 數值為年耗電度數
              br
        .card.col-sm-12.card_prescription.card_prescription_menu
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
                  @click="set_advice_device(dev);$ga.event('advice_device', 'click',dev);"
                ) 
                img(:src="'/img/電器/icon_'+dev+'.svg'")
                div {{ dev }}  

        .card.col-sm-12.card_prescription(v-if="current_compare_data && current_compare_data.show_compare && compare_result.show  && compare_result.better")
          .card_inner.yellow
            .container-fluid
              .row
                //.col-12
                  pre {{current_compare_device_filled_data}}

                .col-sm-3
                  h5 Device Comparison
                  h2.mb-1 電器耗電比較
                    br
                    span {{ advice_device }}
                  img.fadeIn.animated.ani-delay-2(:src="'/img/電器/icon_'+advice_device+'.svg'",
                                        :key="advice_device+'_img'")
                  span.text-center 以上試算數據僅供參考，需以產品實際使用情況為準。

                .col-sm-9.col_watches(v-if="current_compare_data" )
                  .row
                    .col-sm-6.block_watch.fadeIn.animated.ani-delay-3(:key="advice_device+'_cur_data'")
                      h5.block_title.watch_title 您的電器
                      div
                      .p-5.pb-0
                        .watch.mb-4
                          svg_inline_compare_watch.elec_watch(
                            src="/img/compare_watch.svg",
                            :degree="compare_result.target_consumption", 
                            :max="current_compare_data.maximum_consumption", 
                            :init="scrl_start_watch")
                        h5.tag.text-center 您的電器耗電量為節能電器 {{ compare_result.mul }} 倍
                        
                    .col-sm-6.block_watch.fadeIn.animated.ani-delay-6(:key="advice_device+'_compare_data'")
                      h5.block_title.watch_title.mr-4 節能電器
                      span.ml-3 {{ current_compare_data.notes }} 
                      //- span.extra_multiplier {{ current_compare_data.multiplier_info?`(${current_compare_data.multiplier_info} - x${current_compare_data.multiplier})`:"" }}
                      div
                      .p-5.pb-0
                        .watch.mb-4
                          svg_inline_compare_watch.elec_watch(
                            src="/img/compare_watch.svg",
                            :degree="compare_result.ideal_consumption", :max="current_compare_data.maximum_consumption", :init="scrl_start_watch")
                        
                        h5.tag.text-center 節能電器可省下{{ -parseInt(compare_result.delta) }}度電({{ parseInt(-compare_result.delta*2.48) }}元)
                          img.crown.fadeIn.animated.ani-delay-10(
                              src="/img/crown.svg", :key="advice_device",
                              v-if="compare_result.crown")
                      
            
        .card.col-sm-12.card_prescription
          .card_inner.yellow
            .container-fluid
              .row
                .col-sm-12
                  h5 Action prescription
                  h2.mb-5 行動處方 - {{ advice_device }}
                  //- hr
                  transition(name="fade",mode="out-in")
                    div(:key="advice_device").mt-3
                      .col-sm-12(v-if="get_advices(advice_device,cataname).length",
                          v-for="cataname in ['聰明省','好選擇','好習慣']")
                        .row.row_cata.mb-3
                          .col-sm-2.col_advice_cata
                            img.advice_cata_icon(:src="'/img/建議icon/advice_cata_'+cataname+'.svg'")
                          ul.col-sm-10
                            li 
                              h3 {{cataname}}
                            li(v-for="(adv,id) in get_advices(advice_device,cataname)") {{id+1}}. {{adv.content}}                  
              
        .card.col-sm-12(v-if="advice_devices.length>0")
          .card_inner.nominh
            .container-fluid
              .row
                .col-sm-12
                  h2.mb-5 節能電器推薦
                    
                    a.small(:href="get_cata_link(advice_device)", target="_blank") 
                      span.small (查看更多推薦電器)
                      span.small.mr-5 資料更新日期：2019/10/30
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
                            //- div {{info}}
                            div(v-for="info in processAdviceDeviceInfo(parse_json(ad_dev.infos)) ")
                              span {{info.label}}： 
                              span {{info.content}}                        
                          div(v-else)
                            .device_title 品牌 &nbsp;|&nbsp; {{ad_dev.company}}
                            .device_com 型號 &nbsp;|&nbsp; {{ad_dev.itemid}}
                            .device_com 尺寸 &nbsp;|&nbsp; {{ad_dev.size}}
                            .device_com 年耗電量 &nbsp;|&nbsp; {{ad_dev.consumption}}
                            
                          //a(:href="ad_dev.link" target="_blank" title="點擊前往網站")
                            i.fa.fa-external-link
                        hr
                      .col-sm-12
                        .nav_btns
                          .btn.btn_pre(@click="advice_index -=advice_index>0?1:0") 
                            i.fa.fa-angle-left 
                            span.ml-3 上6筆
                          .pagenum {{advice_index+1}} / {{parseInt(advice_devices.length/6)}}
                          .btn.btn_post(@click="advice_index+=1") 
                            span.mr-3 下6筆
                            i.fa.fa-angle-right

        .card.col-sm-12
          .card_inner.nominh.yellow
            .container-fluid
              .row
                .col-sm-12
                  h2.mb-5
                    i.fa.fa-hammer
                    span 用電設備承裝/維護站
              .row
                .col-sm-12
                  input(placeholder="輸入搜尋關鍵字",
                        v-model="searchElecKeyword")
              .row
                .col-sm-6
                  h3 合格電器承裝業
                  table.table_fix_company
                    thead
                      th.p-1(v-for="(d,key) in ['序號','公司行號名稱','等級','地址'] ",
                            :style="{width: ['10%','35%','15%','40%'][key]}") {{d}}
                    
                    tr(v-for="item in getChunk(filteredDataElecLoad)[nowElecLoadPage]")
                      td(v-for="key in ['id','company_name','level','address']", v-html="item[key]")
                      td
                        a(:href="`https://www.google.com/maps/place/${item['地址']}`", target="_blank")
                          i.fa.fa-link
                  ul.page-sel.mt-2
                    li.curp
                      i.fa.fa-angle-left(@click="elecLoadStartPage-=10",v-if="elecLoadStartPage>0")
                    li(v-for="(page,pageId) in getChunk(filteredDataElecLoad)",
                       @click="nowElecLoadPage = pageId",
                       :class="{active: pageId==nowElecLoadPage }",
                       v-if="pageId>=elecLoadStartPage && pageId<=elecLoadStartPage+10") {{pageId+1}}
                    li.curp
                      i.fa.fa-angle-right(@click="elecLoadStartPage+=10", v-if="elecLoadStartPage<getChunk(filteredDataElecLoad).length-10")
                .col-sm-6
                  h3 合格用電設備檢查維護業
                  table.table_fix_company
                    thead
                      th.p-1(v-for="(d,key) in ['序號','公司行號名稱','登記維護範圍','地址']",
                            :style="{width: ['10%','30%','30%','40%'][key]}") {{d}}
                    tr(v-for="item in getChunk(filteredDataElecTest)[nowElecTestPage]")
                      td(v-for="key in ['id','company_name','maintenance_area','address']", v-html="item[key]")
                      td
                        a(:href="`https://www.google.com/maps/place/${item['地址']}`", target="_blank")
                          i.fa.fa-link
                  ul.page-sel.mt-2
                    li.curp
                      i.fa.fa-angle-left(@click="elecTestStartPage-=10", v-if="elecTestStartPage>0")
                    li(v-for="(page,pageId) in getChunk(filteredDataElecTest)",
                       @click="nowElecTestPage = pageId",
                       :class="{active: pageId==nowElecTestPage}",
                       v-if="pageId>=elecTestStartPage && pageId<=elecTestStartPage+10") {{pageId+1}}
                    li.curp
                      i.fa.fa-angle-right(@click="elecTestStartPage+=10", v-if="elecTestStartPage<getChunk(filteredDataElecTest).length-10")
                  
</template>

<script>
import { mapState, mapMutations } from 'vuex'
import graph_bubble from './graph_bubble'
import rooms from '../rooms'
import svg_inline_compare_watch from "./svg_inline_compare_watch"
import svg_inline_distribution from "./svg_inline_distribution"
// import advices from '../advices'
// import advice_devices from '../advice_devices_compiled' 
import _ from 'underscore'
import axios from 'axios'
import $ from 'jquery'

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
      advice_index: 1,
      scrl_start_watch: false,
      compare_data: [],
      searchElecKeyword: "",
      nowElecTestPage: 0,
      nowElecLoadPage: 0,
      elecTestStartPage: 0,
      elecLoadStartPage: 0,

      co_elec_contracter: [],
      co_elec_maintenance: [],

      filterAdviceLabels: ["標示義務公司","產品型號","額定冷氣能力","年耗電量","內桶容量","品牌","產品型號","電鍋型式",
      "有效內容積","標示熱效率","名稱","熱水系統貯水桶容量標示值","每24小時標準化備用損失Est,24(標示登錄值)",
      "廠牌名","規格(吋)","待機消耗電力(W)","型號","核心處理器CPU",
      "實測典型能源消耗量(kWh)","額定容量(Kg)","實測全流程耗電量(kWh/kg)"]

      //- distributionData: [
      //-   {
      //-     name: "bathroom",
      //-     value: 94
      //-   },
      //-   {
      //-     name: "livingroom",
      //-     value: 624
      //-   },{
      //-     name: "bedroom",
      //-     value: 531
      //-   },{
      //-     name: "kitchen",
      //-     value: 23
      //-   },{
      //-     name: "other",
      //-     value: 135
      //-   }
      //- ]
      
    };
  },
  components: {
    graph_bubble, svg_inline_compare_watch, svg_inline_distribution
  },
  mounted() {
    console.log("solution mounted");
    this.sound_expand = new Audio("/music/%E5%8B%95%E6%85%8B%E5%9C%96%E8%A1%A8%E9%9F%B3%E6%95%88/%E6%94%BE%E5%A4%A7.mp3");
    axios.get('/api/advices').then((res)=>{
      this.advices=res.data
    })
    this.getAdviceDeviceData()
    //載入電器比較資料
    axios.get("/api/page/comparedevice").then((res)=>{
      this.$set(this,"compare_data",JSON.parse(res.data.content).compare_data);
      console.log("Compare Data Loaded!", this.compare_data)
    }) 

    axios.get("/api/co_elec/contracter").then((res)=>{
      console.log("elec contracter", res.data)
      this.$set(this,"co_elec_contracter",res.data);

    })
    axios.get("/api/co_elec/maintenance").then((res)=>{
      console.log("elec maintenance", res.data)
      this.$set(this,"co_elec_maintenance",res.data);
    })


  },
  watch: {
    now_place_id(){
      this.sound_expand.play();
    },
    scrollTop(){
      if ($(".col_watches").length){
        //- console.log("yes")
        if (this.scrollTop+$(window).height()>$(".col_watches").offset().top+$(".elec_watch").outerWidth()-100){
          //- console.log("running")
          this.scrl_start_watch=true;
        }else{
          this.scrl_start_watch=false;
        }
      }
    },
    advice_device(){
      this.getAdviceDeviceData()
    },
    searchElecKeyword(){
      this.nowElecTestPage = 0
      this.nowElecLoadPage = 0
      this.elecLoadStartPage = 0
      this.elecTestStartPage = 0
    }
  },
  computed: {
    
    ...mapState(['device_result', 'devices','scrollTop','site_width']),

    advice_brands(){
      return this.advice_devices
              .map(o=>o.brand)
              .filter((d,i,arr)=>arr.indexOf(d)==i)
    },
    advice_device_list() {
      let getOrder = (name)=>(this.compare_data.find(d=>d.name==name) || {}).order  || 1000
      let result =  this.advices
                    .map(advice=>advice.device)
                    .filter((d, i, arr) => arr.indexOf(d) == i)
                    .sort((a,b)=>(getOrder(a)-getOrder(b)))
      console.log(result)
      return result
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
    total_consumption(){
      return parseInt(this.device_result.room_sum.map(o => o.value).reduce((a, b) => a + b));
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
      
    },
    current_compare_data(){
      return this.compare_data.find(d=>d.name==this.advice_device)
    },
    current_compare_device_filled_data(){
      // var profiles = _.flatten(this.devices.map(d=>[
      //   d,d.alter_specs
      // ])).filter(d=>d.count>0)
      let result =  _.flatten(this.devices.filter(d=>d.name==this.current_compare_data.name).map(d=>[d,d.alter_specs]))
                      .filter(d=>d.count>0)
                      .filter(d=>d.max_per_device_consumption)
                      .sort((a,b)=>a.max_per_device_consumption>b.max_per_device_consumption?-1:1)
      console.log(result)
      return result.length?result[0]:null
    },
    compare_result(){
      if (!this.current_compare_device_filled_data){
        return {}
      }
      let target_consumption = this.current_compare_device_filled_data.max_per_device_consumption * (this.current_compare_data.user_multiplier || 1)
      let ideal_consumption = parseInt(this.current_compare_data.consumption * this.current_compare_data.multiplier * this.current_compare_device_filled_data.use_hour_per_year / 1000)

      return {
        target_consumption: target_consumption,
        ideal_consumption: ideal_consumption,
        better: ideal_consumption<target_consumption,
        show: (this.current_compare_data && this.current_compare_device_filled_data)?true:false,
        mul: (target_consumption/ideal_consumption).toFixed(2),
        crown: target_consumption/ideal_consumption>1.5,
        delta: ideal_consumption - target_consumption
      }
    },
    distributionData(){
      return this.device_result.room_sum.map((obj,oid)=>({
        ...obj,
        room: this.rooms[oid]
      })).map((obj)=>({
        name: obj.room.eng.toLowerCase(),
        value: obj.value
      }))
    },

    filteredDataElecLoad(){
      let result =  this.filterArrByWord(this.co_elec_contracter || [],this.searchElecKeyword)
      if (this.searchElecKeyword!=""){
        result = JSON.parse(JSON.stringify(result).replace(new RegExp("(" +this.searchElecKeyword.replace("台","臺")+")","g"),"<span class=highlight>$1</span>"))
      }
      result = result.slice()
      result = result.slice().sort((a,b)=>a['level']>b['level']?-1:1)
      return result
    },
    filteredDataElecTest(){
      let result =  this.filterArrByWord(this.co_elec_maintenance || [],this.searchElecKeyword)
      if (this.searchElecKeyword!=""){
        result = JSON.parse(JSON.stringify(result).replace(new RegExp("(" +this.searchElecKeyword.replace("台","臺")+")","g"),"<span class=highlight>$1</span>"))
      }
      result = result.slice()
      return result
    }

  },
  methods: {

    processAdviceDeviceInfo(obj){
      let useData = obj.filter(item=>this.filterAdviceLabels.indexOf(item.label)!=-1)
      
     
      return useData
    },

    getChunk(arr){
      return _.chunk(arr,6)
    },

    filterArrByWord(arr, key){
      let useKey = key.replace("台","臺")
      return (arr || []).filter(obj=>JSON.stringify(obj).indexOf(useKey)!=-1 )
    },
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
    },

    //取得該種類推薦電器
    getAdviceDeviceData(){
      let convertList = {
        "冷氣機": "冷氣",
        "冰箱": "電冰箱",
        "電視": "電視機",
        "電腦": "桌上型電腦",
      }
      axios.get('/api/advice_devices/'+ (convertList[this.advice_device] || this.advice_device) ).then((res)=>{
        this.advice_devices=res.data
      })
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">

</style>
