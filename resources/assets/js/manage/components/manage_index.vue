<template lang="pug">
section.manage_index.container-fluid 
  .row
    .col-sm-12
      h1 資料分析
  .row
    .col-sm-12
      .panel.panel-primary
        .panel-heading 平均耗電
        .panel-body
          input.form-control(v-model="temp_zh.zh.page_share.avg_consump",
                             placeholder="月平均")
          button.btn.btn-danger(@click="save_website_data") 儲存變更
  .row
    .col-sm-12
      .panel.panel-primary
        .panel-heading 使用者基本資料
        .panel-body
          vue_lazy_table(:table_data = "userdetails",
                        :rows = "uuid_user_details",
                        :dataTitle = "'使用者基本資料'")
          //graph_bar(:data = "")

  .row
    .col-sm-12
      .panel.panel-primary
        .panel-heading 使用者紀錄
        .panel-body
          vue_lazy_table(:table_data = "show_uuid_table",
                         :rows = "uuid_devicelog_rows",
                         :dataTitle="'使用者紀錄'")

  
  .row
    .col-sm-12
      .panel.panel-primary
        .panel-heading 統計
        .panel-body
          vue_lazy_table(:table_data = "conclude_table"
                      :rows = "conclude_table_rows"
                      :configs = "{show_id: false, show_search: false}")
  //h1 裝置
  //hr
  //vue_lazy_table(:table_data = "devices",
              :rows = "devices_table_rows")
  //pre {{userdetails}}


  
  // select(v-model="now_select_device")
  //   option(v-for="op in Object.keys(advice_devices)" , :value="op") {{op}}

  // vue_lazy_table(:table_data = "advice_devices[now_select_device]"            :rows = "adv_table_rows"  )



    
</template>

<script>
// import advice_devices from "../../advice_devices_compiled"
import vue_lazy_table from '../components/vue_lazy_table';
import advices from "../../advices"
import {mapState,mapMutations,mapActions} from 'vuex' ;

export default {
  data () {
    return {
      msg: 'Welcome to Your Vue.js App',
      advices: advices,
      uuid_user_details: [
        "id -> 編號",
        "uuid -> 使用者編號",
        "county -> 縣市",
        "member_count -> 成員數量",
        "area_size -> 大小",
        "summer -> 夏月",
        "degree -> 度",
        "money -> 錢",
        "created_at -> 建立時間",
        "updated_at -> __hide"

      ],
      uuid_devicelog_rows: [
        "id -> 編號",
        "uuid -> 使用者編號",
        "total_consumption -> 總消耗度數(年)",
        "created_time -> 時間",
        "device_count -> 填寫電器數",
        "updated_at -> __hide"

      ],
      conclude_table_rows: [
        'total -> 人數總計',
        'avg -> 平均度數',
        'device_count -> 平均填寫電器'
      ],
      adv_table_rows:[
        'brand -> 品牌',
        'name -> 名稱',
        'comsumption -> 功率',
        'size -> 尺寸',
        'link -> 連結 | hide'
      ],
      devices_table_rows:[
        'name -> 名稱',
        'place -> 地點',
        'count -> 預設數量 | hide',
        'rarely -> 很少',
        'occasionally -> 偶爾',
        'often -> 經常',
        'frequently -> 頻繁',
        'day -> 使用天數',
        'default_consumption -> 預設能耗',
        'consumption_mul -> 能耗加乘',
        'type -> 電器種類',
        'created_at -> 創建時間 | hide',
        'updated_at -> 更新時間 | hide'

      ],
      now_select_device: "電視機",
      // advice_devices: advice_devices.advice_devices,
      // advice_catas: advice_devices.advice_catas,
      temp_zh: null,
      
    }
  },
  computed: {
    ...mapState(['uuid_devicelog','devices','website_zh','userdetails']),
    show_uuid_table(){
      return this.uuid_devicelog
    },
    conclude_table(){
      return [{
        total: this.uuid_devicelog.length,
        avg: (this.uuid_devicelog.map(o=>o.total_consumption).reduce((a,b)=>1.0*a+1.0*b,0)/ this.uuid_devicelog.length).toFixed(2),
        device_count: (this.uuid_devicelog.map(o=>o.device_count).reduce((a,b)=>1.0*a+1.0*b,0)/ this.uuid_devicelog.length).toFixed(1)
      }]
    },
    // graph_degree(){
    //   // let result = this.uuid_devicelog_rows.reduce((total,obj)=>{
    //   //   for(let i =0;i<5;i++){
    //   //     if (obj.total_consumption > i*4000 && obj.total_consumption<(i+1)*4000){
    //   //       total[i+"-"+i*4000]++
    //   //     }
    //   //   }
    //   // },({
    //   //   "0-4000": 0,
    //   //   "4000-8000": 0,
    //   //   "8000-12000": 0,
    //   //   "12000-16000": 0,
    //   //   "16000-20000": 0,
    //   // }))

      
    //   // return Object.keys(result).map(k=>({name: k,value: result[k]}))

    // }
  },
  watch: {
    website_zh(){
      this.temp_zh=this.website_zh
    }
  },
  components: {
    vue_lazy_table
  },
  methods: {
    ...mapActions(['push_website_data']),
    save_website_data(){
      this.push_website_data(this.temp_zh)
    }
  }
  // methods: {}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass">

</style>
