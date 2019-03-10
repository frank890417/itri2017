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
  .row(v-if="show_data[0]")
    .col-sm-12
      .panel.panel-primary
        .panel-heading 使用者資料 {{show_data[0].uuid}} |  ({{show_data[0].updated_at}})
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
          //- el-button(@click='resetDateFilter') 清除日期过滤器
          //- el-button(@click='clearFilter') 清除所有过滤器
          //el-table(ref='filterTable', :data='show_data', style='width: 100%')
            //- el-table-column(prop='uuid', label='uuid', sortable='', width='180', column-key='date', :filters="[{text: '2016-05-01', value: '2016-05-01'}, {text: '2016-05-02', value: '2016-05-02'}, {text: '2016-05-03', value: '2016-05-03'}, {text: '2016-05-04', value: '2016-05-04'}]", :filter-method='filterHandler')
            //- el-table-column(prop='county', label='居住地區', sortable='', width='180', column-key='county', :filters="[{text: '2016-05-01', value: '2016-05-01'}, {text: '2016-05-02', value: '2016-05-02'}, {text: '2016-05-03', value: '2016-05-03'}, {text: '2016-05-04', value: '2016-05-04'}]", :filter-method='filterHandler')
            //- el-table-column(prop='member_count', label='家庭人數', sortable='', width='180', column-key='member_count', :filters="[{text: '2016-05-01', value: '2016-05-01'}, {text: '2016-05-02', value: '2016-05-02'}, {text: '2016-05-03', value: '2016-05-03'}, {text: '2016-05-04', value: '2016-05-04'}]", :filter-method='filterHandler')
            //- el-table-column(prop='area_size', label='空間大小(坪)', sortable='', width='180', column-key='area_size', :filters="[{text: '2016-05-01', value: '2016-05-01'}, {text: '2016-05-02', value: '2016-05-02'}, {text: '2016-05-03', value: '2016-05-03'}, {text: '2016-05-04', value: '2016-05-04'}]", :filter-method='filterHandler')
            //- el-table-column(prop='summer', label='月份', sortable='', width='180', column-key='summer', :filters="[{text: '2016-05-01', value: '2016-05-01'}, {text: '2016-05-02', value: '2016-05-02'}, {text: '2016-05-03', value: '2016-05-03'}, {text: '2016-05-04', value: '2016-05-04'}]", :filter-method='filterHandler')
            //- el-table-column(prop='degree', label='度數', sortable='', width='180', column-key='degree', :filters="[{text: '2016-05-01', value: '2016-05-01'}, {text: '2016-05-02', value: '2016-05-02'}, {text: '2016-05-03', value: '2016-05-03'}, {text: '2016-05-04', value: '2016-05-04'}]", :filter-method='filterHandler')
            //- el-table-column(prop='money', label='電費', sortable='', width='180', column-key='money', :filters="[{text: '2016-05-01', value: '2016-05-01'}, {text: '2016-05-02', value: '2016-05-02'}, {text: '2016-05-03', value: '2016-05-03'}, {text: '2016-05-04', value: '2016-05-04'}]", :filter-method='filterHandler')
            //- el-table-column(prop='created_at', label='建立時間', sortable='', width='180', column-key='created_at', :filters="[{text: '2016-05-01', value: '2016-05-01'}, {text: '2016-05-02', value: '2016-05-02'}, {text: '2016-05-03', value: '2016-05-03'}, {text: '2016-05-04', value: '2016-05-04'}]", :filter-method='filterHandler')
            
            
            
            //- el-table-column(prop='name', label='姓名', width='180')
            //- el-table-column(prop='address', label='地址', :formatter='formatter')
            //- el-table-column(prop='tag', label='标签', width='100', :filters="[{ text: '家', value: '家' }, { text: '公司', value: '公司' }]", :filter-method='filterTag', filter-placement='bottom-end')
            //-   template(slot-scope='scope')
            //-     el-tag(:type="scope.row.tag === '家' ? 'primary' : 'success'", disable-transitions='') {{scope.row.tag}}

          //- vue_lazy_table(
          //-   :table_data="show_data",
          //-   :rows = "user_rows"
          //- )
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
