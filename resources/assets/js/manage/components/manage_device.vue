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
    .col-sm-4
      .panel.panel-primary
        .panel-heading {{now_device.name}}
        .panel-body
          //pre(v-html="now_device")
          //h3 {{now_device.name}}
          .form-group.row(v-for="key in left_key_list.concat(now_device.type=='ac'?ac_key_list:[])",
                          v-if="!(hidden_attrs.indexOf(key)+1)")
            .col-sm-3
              label {{ translate_label(key) }}
            .col-sm-9(v-if="key!='default_freq_option'")
              input.form-control(v-model="now_device[key]")
            .col-sm-9(v-else)
              select.form-control(v-model="now_device[key]")
                option(value="0") 很少
                option(value="1") 偶爾
                option(value="2") 經常
                option(value="3") 頻繁


          .form-group
            .btn.btn-primary(@click='save_device_infos') 儲存推薦電器
    .col-sm-8
      .panel.panel-default
        .panel-heading 其他設定
        .panel-body
          //pre(v-html="now_device")
          //h3 {{now_device.name}}
          .form-group.row(v-for="key in get_obj_keys(now_device,left_key_list.concat(now_device.type=='ac'?ac_key_list:[]))",
                          v-if="!(hidden_attrs.indexOf(key)+1)")
            .col-sm-3
              label {{ translate_label(key) }}
            .col-sm-9(v-if="key!='default_freq_option'")
              input.form-control(v-model="now_device[key]")
            .col-sm-9(v-else)
              select.form-control(v-model="now_device[key]")
                option(value="0") 很少
                option(value="1") 偶爾
                option(value="2") 經常
                option(value="3") 頻繁
          .form-group

        //.panel-body {{devices}}
          //vue_lazy_table(:table_data = "devices",
                         :rows = "devices_table_rows")
    
</template>

<script>
import advice_devices from "../../advice_devices"
// import editor_form from './editor_form';
import advices from "../../advices"
import {mapState,mapMutations,mapActions} from 'vuex' ;

export default {
  props: ['device_id'],
  data () {
    return {
      msg: 'Welcome to Your Vue.js App',
      advices: [],
      hidden_attrs: ['id','created_at','updated_at'],
      advice_devices: advice_devices.advice_devices,
      advice_catas: advice_devices.advice_catas,
      temp_zh: null,
      left_key_list: ['name','place','count','english_name','default_consumption','default_freq_option'],
      ac_key_list: ['default_area_size','default_ac_power','default_cspf']
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
      return this.devices.find(o=>o.id==this.device_id)
    }
  },
  watch: {
    website_zh(){
      this.temp_zh=this.website_zh
    }
  },
  components: {

    // editor_form
  },
  methods: {
    ...mapActions(['push_website_data']),
    get_obj_keys(obj,without){
      let keys = Object.keys(obj)
      return keys.filter(key=>(without || []).indexOf(key)==-1)
    },
    save_website_data(){
      this.push_website_data(this.temp_zh)
    },
    translate_label(eng){
      let translist = [
        "id -> #",
        "name -> 名稱",
        "place -> 地點",
        "rarely -> 很少(hr)",
        "count -> 數量",
        "occasionally -> 偶爾(hr)",
        "often -> 經常(hr)",
        "frequently -> 頻繁(hr)",
        "day -> 使用天數",
        "default_consumption -> 預設瓦數",
        "consumption_mul -> 消耗加乘",
        "type -> 種類",
        "created_at -> 建立時間",
        "updated_at -> 修改時間",
        "english_name -> 英文名稱",
        "sharetext -> 分享文字",
        "year_options -> 年份",
        "old_condition -> 老舊條件",
        "default_freq_option -> 預設選項",
        "default_area_size -> 預設坪數",
        "default_ac_power -> 預設額定冷氣能力(kw)",
        "default_cspf -> 預設CSPF值",
        
      ]
      translist = translist.map(o=>o.split(" -> "))
      let result = translist.find(o=>o[0]==eng)
      return result?result[1]:eng

    },
    save_device_infos(){
      axios.post('/device/'+this.device_id,{
        _method: 'put',
        ...this.now_device
      }).then((res)=>{
        // console.log("ajax result",res)
        if (res.data.status=='success'){
          alert("儲存成功")
        }
      })
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass">

</style>
