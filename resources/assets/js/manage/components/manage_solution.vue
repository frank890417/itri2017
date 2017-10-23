<template lang="pug">
section.manage_room.container-fluid 
  .row
    .col-sm-12
      h1 行動處方
  .row
    .col-sm-8
      .panel.panel-primary
        .panel-heading 處方列表
        .panel-body
          vue_lazy_table(:table_data = "advices",
                        :dataTitle="'處方列表'",
                        :edit = "switchDevice")
    .col-sm-4
      .panel.panel-default
        .panel-heading 建議編輯-{{nowAdviceId}}
        .panel-body
          .row
            .col-sm-12
              .form-group
                label 電器名稱
                input.form-control(v-model="advices[nowAdviceId].device")
              .form-group
                label 建議種類
                input.form-control(v-model="advices[nowAdviceId].cata")
              .form-group
                label 建議內容
                textarea.form-control(row=10,v-model="advices[nowAdviceId].content")
                //textarea(v-model="")
              .form-group
                .btn.btn-primary 儲存建議
      
</template>

<script>
import advice_devices from "../../advices"
import vue_lazy_table from './vue_lazy_table';
// import editor_form from './editor_form';
import advices from "../../advices"
import {mapState,mapMutations,mapActions} from 'vuex' ;

export default {
  data () {
    return {
      msg: 'Welcome to Your Vue.js App',
      advices: advices,
      nowAdviceId: 0,
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
    vue_lazy_table,
    // editor_form
  },
  methods: {
    ...mapActions(['push_website_data']),
    save_website_data(){
      this.push_website_data(this.temp_zh)
    },
    switchDevice(advice){
      this.nowAdviceId=advice.id
    }
  }
  // methods: {}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass">

</style>
