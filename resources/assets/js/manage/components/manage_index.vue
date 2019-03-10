<template lang="pug">
section.manage_index.container-fluid 
  .row
    .col-sm-12
      h1 資料分析
  .row.pt-3.pb-3.mt-3
    .col-sm-12
      .panel.panel-primary
        .panel-heading 紀錄分析圖表
        .panel-body.pt-2.pb-2
          .container-fluid
            .row.mt-3
              .col-sm-6

                h3.text-center 使用者地區分佈
                DoughnutChart(:chartData="data_grouped_by_county", v-if="data_grouped_by_county")
              .col-sm-6

                h3.text-center 房間耗電占比分佈
                DoughnutChart(:chartData="data_grouped_by_place", v-if="data_grouped_by_place")
      .panel.panel-primary
        .panel-heading 電器紀錄分析圖表
        .panel-body.pt-2.pb-2
          .container-fluid
            .row
              .col-sm-12

                h3.text-center 電器耗電總比例 (1MWh)
                DoughnutChart(:chartData="data_grouped_by_device", v-if="data_grouped_by_device")
  
      
  .row
    .col-sm-12
      .panel.panel-primary
        .panel-heading 使用者基本資料
        .panel-body
          vue_lazy_table(:table_data = "userdetails",
                        :rows = "uuid_user_details",
                        :dataTitle = "'使用者基本資料'")
          //graph_bar(:data = "")
        //- p {{data_grouped_by_county }}
        //- CommitChart

  .row
    .col-sm-12
      .panel.panel-primary
        .panel-heading 使用者基本資料(e-table)
        el-input
        el-table(:data="userdetails")
          el-table-column(v-for="r in uuid_user_details",
                          :prop = "r.split(' -> ')[0]",
                          :label = "r.split(' -> ')[1]",
                          :key="r")
            
  .row
    .col-sm-12
      .panel.panel-primary
        .panel-heading 電器填寫紀錄
        .panel-body
          vue_lazy_table(:table_data = "show_uuid_table",
                         :rows = "uuid_devicelog_rows",
                         :dataTitle="'使用者紀錄'",
                         btntext="查看",
                         :edit="gotoUser")

  
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
import region_data from '../../region_data'
import * as d3 from 'd3'
import _ from "lodash"
export default {
  data () {
    return {
      msg: 'Welcome to Your Vue.js App',
      advices: advices,
      region_data,
      uuid_user_details: [
        "id -> 編號",
        "uuid -> 使用者編號",
        "county -> 縣市",
        "member_count -> 成員數量",
        "area_size -> 大小",
        "summer -> 夏月",
        "degree -> 度",
        "money -> 錢",
      	"users_id -> 會員id | hide",
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
      data_grouped_by_device_raw: null,
      data_grouped_by_place_raw: null
    }
  },
  computed: {
    ...mapState(['uuid_devicelog','devices','website_zh','userdetails']),
    userdetails_show(){
      return this.userdetails.map((u)=>({
        ...u,
        region: this.region_data[u.region],


      }) )
    },
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
    data_grouped_by_county(){
      var c = d3.scaleOrdinal(d3.schemeCategory20)
      let result = Object.entries(_.groupBy(this.userdetails,"county")).map((item)=>item[1].length)
      console.log(result)
      return {
        labels: Object.keys(_.groupBy(this.userdetails,"county")),
        datasets: [{
          label: "使用者",
          backgroundColor: result.map((d,i)=>c(i)),
          data: result
        }]
      }

    },
    data_grouped_by_device(){
      if (!this.data_grouped_by_device_raw) return null
      var c = d3.scaleOrdinal(d3.schemeCategory20)

      return {
        labels: this.data_grouped_by_device_raw.map(d=>this.devices.find(device=>device.id==d.device_id).name),
        datasets: [{
          label: "電器耗電比例",
          backgroundColor: this.data_grouped_by_device_raw.map((d,i)=>c(i)),
          data: this.data_grouped_by_device_raw.map(d=>d.sum/1000)
        }]
      }

    },
    data_grouped_by_place(){
      if (!this.data_grouped_by_place_raw) return null
      var c = d3.scaleOrdinal(d3.schemeCategory20)

      return {
        labels: this.data_grouped_by_place_raw.map(d=>d.place),
        datasets: [{
          label: "房間耗電比例",
          backgroundColor: this.data_grouped_by_place_raw.map((d,i)=>c(i)),
          data: this.data_grouped_by_place_raw.map(d=>d.sum/1000)
        }]
      }

    }
    
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
    },
    gotoUser(row){
      this.$router.push("/user/"+row.uuid)
    }
  },
  created(){
    axios.get("/api/device/summary").then(res=>{
      this.$set(this,"data_grouped_by_device_raw",res.data)
    })

    axios.get("/api/place/summary").then(res=>{
      this.$set(this,"data_grouped_by_place_raw",res.data)
    })
  }
  // methods: {}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass">

</style>
