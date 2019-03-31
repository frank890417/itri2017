<template lang="pug">
section.manage_person.container-fluid 
  .row
    ul.breadcrumb
      li 使用者資料
      li.active 
        router-link(to="/share") 返回列表
  .row
    .col-sm-12
      h1 詳細資料
  .row
    .col-sm-12
      .panel.panel-primary
        .panel-heading 使用者資料 {{ uuid}} |  ({{ show_data[0].updated_at}})
        .panel-body
          ul(v-if="userinfo")
            li uuid: {{userinfo.uuid}}
            li 居住地區: {{ userinfo.county}}
            li 家庭人數: {{userinfo.member_count}}
            li 空間大小(坪): {{userinfo.area_size}}
            li 月份: {{userinfo.summer?"夏月":"非夏月"}}
            li 度數: {{userinfo.degree}}
            li 電費: {{userinfo.money}}
            li 建立時間: {{userinfo.created_at}}
          
          vue_lazy_table(
            :table_data="show_data",
            :rows = "user_rows"
          )
</template>

<script>
import {mapState} from "vuex"
import region_data from '../../region_data'
export default {
  props: ["uuid"],
  data(){
    return {
      userdata: [],
      userinfo: null,
      region_data,
      user_rows: [
        "id -> #",
        "device_name -> 電器",
        "uuid -> __hide",
        "count -> 數量",
        "device_consumption -> 度數（填寫)",
        "hour_consumption -> __hide",
        "use_time -> 使用(小時/日)",
        "buy_time -> 購買(年)",
        "light_option -> 燈種類",
        "device_id -> __hide",
        "place -> 地點",
        "place_id -> __hide",
      	"users_id -> 會員id | hide",
        "created_at -> __hide",
        "updated_at -> __hide",
      ]
    }
  },
  created(){
    axios.get("/api/devicelog/"+this.uuid).then(res=>{
      this.$set(this,"userdata",res.data.logs)
      this.$set(this,"userinfo",res.data.user)
    })
  },
  computed: {
    ...mapState(['devices']),
    show_data(){
      let temp = this.userdata
      temp.forEach(o=>{
        o.device_name=this.devices[o.device_id].name
        o.place=this.devices[o.device_id].place
      })
      return temp
    }
  },
  methods: {
    resetDateFilter() {
      this.$refs.filterTable.clearFilter('date');
    },
    clearFilter() {
      this.$refs.filterTable.clearFilter();
    },
    formatter(row, column) {
      return row.address;
    },
    filterTag(value, row) {
      return row.tag === value;
    },
    filterHandler(value, row, column) {
      const property = column['property'];
      return row[property] === value;
    }
  }
}
</script>

<style>

</style>
