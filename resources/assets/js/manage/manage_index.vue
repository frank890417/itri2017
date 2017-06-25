<template lang="pug">
  section.manage_index.container-fluid
    .row
      .col-sm-3
        h2 家計簿-後台
        hr
        ul.list-group
          a.list-group-item.active 資訊列表
          a.list-group-item 頁面內容
          a.list-group-item 填寫紀錄
      .col-sm-9
        h1 使用者紀錄
        hr
        power_table(:table_data = "show_uuid_table"
                    :rows = "uuid_devicelog_rows")
        h1 統計
        hr
        power_table(:table_data = "conclude_table"
                    :rows = "conclude_table_rows"
                    :configs = "{show_id: false, show_search: false}")
        h1 推薦電器
        select(v-model="now_select_device")
          option(v-for="op in Object.keys(advice_devices)" , :value="op") {{op}}

        power_table(:table_data = "advice_devices[now_select_device]"            :rows = "adv_table_rows"  )



    
</template>

<script>
import advice_devices from "../advice_devices"
import power_table from './power_table';
import {mapState,mapMutations} from 'vuex' 

export default {
  name: 'manage_index',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App',
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
      now_select_device: "電視機",
      advice_devices: advice_devices.advice_devices,
      advice_catas: advice_devices.advice_catas
    }
  },
  computed: {
    ...mapState(['uuid_devicelog']),
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
  components: {
    power_table
  }
  // methods: {...mapMutations(['set_loading'])}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">
  *
    // border: solid 1px black
</style>
