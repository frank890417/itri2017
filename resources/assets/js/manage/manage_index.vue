<template lang="pug">
  section.manage_index.container-fluid
    .row
      .col-sm-3
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
                    :rows = "['total -> 總計']")



    
</template>

<script>
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
        "created_time -> 時間"

      ]
    }
  },
  computed: {
    ...mapState(['uuid_devicelog']),
    show_uuid_table(){
      return this.uuid_devicelog
    },
    conclude_table(){
      return [{
        total: this.uuid_devicelog.length
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
