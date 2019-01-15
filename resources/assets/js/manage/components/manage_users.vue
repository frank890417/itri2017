<template lang="pug">
section.manage_room.container-fluid 
  .row
    .col-sm-12
      h1 會員帳號管理
  .row
    .col-sm-8
      .panel.panel-primary
        .panel-heading 帳號列表
          //.btn.btn-secondary(@click="addNewDevice") +新增建議
        .panel-body
          vue_lazy_table(:table_data = "users",
                        :dataTitle="'帳號列表'",
                        :edit = "switchUser",
                        :rows = "user_rows")
    .col-sm-4
      .panel.panel-default
        .panel-heading 編輯-{{nowUserId}}
        .panel-body(v-if="nowUser")
          .row
            .col-sm-12
              .form-group
                label 姓名
                input.form-control(v-model="nowUser.name")
              .form-group
                label Email
                input.form-control(v-model="nowUser.email" required type="email")
              .form-group
                label 是否為管理者<br>
                //input.form-control(v-model="nowUser.admin")
                .row
                 .col-sm-6                 
                  input(type="radio" id="r1" name="admin" value="1" v-model="nowUser.admin" :checked="nowUser.admin" ) 
                  label(for="r1") 是
                 .col-sm-6
                  input(type="radio" id="r2" name="admin" value="0" v-model="nowUser.admin" :checked="!nowUser.admin") 
                  label(for="r2") 否
              .form-group
                .btn.btn-primary(@click="saveUser") 儲存帳號
                .btn.btn-danger(@click="deleteUser") 刪除帳號
      
</template>

<script>
//import advice_devices from "../../advices"
//import users from "../../users"
import vue_lazy_table from './vue_lazy_table';
// import editor_form from './editor_form';
// import advices from "../../advices"
import {mapState,mapMutations,mapActions} from 'vuex' ;
import Axios from 'axios'

export default {
  data () {
    return {
      msg: 'Welcome to Your Vue.js App',
      users: [],
      nowUserId: 1,
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
	  
      user_rows: [
        "id -> 編號",
        "email -> 帳號",
		"name -> 姓名",
        "admin -> 管理者",
      "verified -> __hide",
      "verification_token -> __hide",
        "created_at -> __hide",
        "updated_at -> 更新時間"
      ],
	  
      //now_select_device: "電視機",
      //advice_devices: advice_devices.advice_devices,
      //advice_catas: advice_devices.advice_catas,
      temp_zh: null
    }
  },
  mounted(){
    Axios.get('/api/users').then((res)=>{
      this.users=res.data
      //alert(res.data)
    })  
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
    nowUser(){
      return this.users.find( (u)=>(u.id==this.nowUserId) )
      //return this.users.find( (u)=>(u.id==1) )
    },
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
    switchUser(user){
      this.nowUserId=user.id
	  //this.nowUserEmail=user.email
    },
    saveUser(){
      axios.post('/user/'+this.nowUserId,{
        _method: 'put',
        ...this.nowUser
      }).then((res)=>{
        if (res.data.status=='success'){
          alert("儲存成功")
        }
      })
    },
    addNewUser(){
      let newId = this.users.push({
        id: this.users.map(o=>o.id).reduce((a,b)=>a>b?a:b)+1,
        name: "ddd",
        email: ""
      })
      this.nowUserId = newId
    },
    deleteUser(){
      if (confirm('確認要刪除此帳號嗎?')){
        Axios.post('/user/'+this.nowUserId,{
          _method: 'delete',
        }).then((res)=>{

          Axios.get('/api/users').then((res)=>{
            this.users=res.data
          })
        })
      }
    }
  }
  // methods: {}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass">

</style>
