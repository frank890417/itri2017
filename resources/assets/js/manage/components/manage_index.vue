<template lang="pug">
section.manage_index.panel.panel-primary
  .panel_header 節能介紹
  .panel_body(v-if="temp_zh")
    .col-sm-12
      h3 區塊-關於
      hr
      h4 長條圖
      input.form-control(v-model="temp_zh.zh.page_about.section_1.title")
      textarea.form-control(v-model="temp_zh.zh.page_about.section_1.content" rows=5)
      hr
      h4 圓餅圖
      input.form-control(v-model="temp_zh.zh.page_about.section_2.title")
      textarea.form-control(v-model="temp_zh.zh.page_about.section_2.content" rows=5)
      br
    .col-sm-6
      h4 資料-夏月
      div(v-for="(d,key) in temp_zh.zh.page_about.section_2.chart.summer").form-inline
        label(style="width: 70px") {{d.name}}
        input.form-control(v-model="d.value")
    .col-sm-6
      h4 資料-非夏月
      div(v-for="(d,key) in temp_zh.zh.page_about.section_2.chart.nsummer").form-inline
        label(style="width: 70px")  {{d.name}}
        input.form-control(v-model="d.value")
    .col-sm-12
      br
      button.btn.btn-danger(@click="save_website_data") 儲存變更
      br
      br

  h1 節能建議
  div(v-for="obj in advices")
    h3 {{obj.name}}
    //vue_lazy_table(:table_data = "obj.content.map((o)=>({content: o}))"
                    
              :configs = "{show_id: false, show_search: false}")

  h1 使用者紀錄
  hr
  vue_lazy_table(:table_data = "show_uuid_table"
              :rows = "uuid_devicelog_rows")
  h1 統計
  hr
  vue_lazy_table(:table_data = "conclude_table"
              :rows = "conclude_table_rows"
              :configs = "{show_id: false, show_search: false}")
  h1 裝置
  hr
  vue_lazy_table(:table_data = "devices",
              :rows = "devices_table_rows")
  h1 推薦電器
  hr
  select(v-model="now_select_device")
    option(v-for="op in Object.keys(advice_devices)" , :value="op") {{op}}

  vue_lazy_table(:table_data = "advice_devices[now_select_device]"            :rows = "adv_table_rows"  )



    
</template>

<script>
import advice_devices from "../../advice_devices"
import vue_lazy_table from '../components/vue_lazy_table';
import advices from "../../advices"
import {mapState,mapMutations,mapActions} from 'vuex' ;

export default {
  data () {
    return {
      msg: 'Welcome to Your Vue.js App',
      advices: advices,
      uuid_devicelog_rows: [
        "id -> 編號",
        "uuid -> 使用者編號",
        "total_consumption -> 總消耗度數(年)",
        "created_time -> 時間",
        "device_count -> 填寫電器數"

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
      advice_devices: advice_devices.advice_devices,
      advice_catas: advice_devices.advice_catas,
      temp_zh: null
    }
  },
  computed: {
    ...mapState(['uuid_devicelog','devices','website_zh']),
    show_uuid_table(){
      return this.uuid_devicelog
    },
    conclude_table(){
      return [{
        total: this.uuid_devicelog.length,
        avg: (this.uuid_devicelog.map(o=>o.total_consumption).reduce((a,b)=>1.0*a+1.0*b,0)/ this.uuid_devicelog.length).toFixed(2),
        device_count: (this.uuid_devicelog.map(o=>o.device_count).reduce((a,b)=>1.0*a+1.0*b,0)/ this.uuid_devicelog.length).toFixed(1)
      }]
    }
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
