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
        .panel-heading 使用者資料 {{show_data[0].uuid}} |  ({{show_data[0].updated_at}})
        .panel-body
          p(v-if="userinfo") {{userinfo}}        
          vue_lazy_table(
            :table_data="show_data",
            :rows = "user_rows"
          )
</template>

<script>
import {mapState} from "vuex"
export default {
  props: ["uuid"],
  data(){
    return {
      userdata: [],
      userinfo: null,
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
        "created_at -> __hide",
        "updated_at -> __hide",
      ]
    }
  },
  mounted(){
    axios.get("/api/devicelog/"+this.uuid).then(res=>{
      this.userdata=res.data.logs
      this.userinfo=res.data.user
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
  }
}
</script>

<style>

</style>
