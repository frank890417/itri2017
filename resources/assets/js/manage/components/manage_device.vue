<template lang="pug">
section.manage_room.container-fluid 
  .row
    ul.breadcrumb
      li 推薦電器
      li.active 
        router-link(to="/room") 返回列表
  .row
    .col-sm-12
      h1 電器編輯

  .row
    .col-sm-12
      .panel.panel-primary
        .panel-heading {{now_device.name}}
        .panel-body
          //pre(v-html="now_device")
          //h3 {{now_device.name}}
          .form-group.row(v-for="(item,key) in now_device")
            .col-sm-3
              label {{key}}
            .col-sm-9
              input.form-control(v-model="now_device[key]")
          .form-group
            .btn.btn-primary 儲存推薦電器
        //.panel-body {{devices}}
          //vue_lazy_table(:table_data = "devices",
                         :rows = "devices_table_rows")
    
</template>

<script>
import advice_devices from "../../advice_devices"
import vue_lazy_table from './vue_lazy_table';
// import editor_form from './editor_form';
import advices from "../../advices"
import {mapState,mapMutations,mapActions} from 'vuex' ;

export default {
  props: ['device_id'],
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
        'english_name -> 英文名稱',
        'sharetext -> 分享文字',
        'year_options -> 年份選項',
        'old_condition -> 老舊條件',
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
    },
    now_device(){
      return this.devices[this.device_id]
    }
  },
  watch: {
    website_zh(){
      this.temp_zh=this.website_zh
    }
  },
  components: {
    vue_lazy_table,
    // editor_form
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
