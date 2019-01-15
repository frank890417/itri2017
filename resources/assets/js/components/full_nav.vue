<template lang="pug">
  
  section.full_nav.yellow(:class='{active:full_nav_open}')
    ul
      li(@click="login()", v-if="!member_data.isLogin")
        .eng LOGIN
        .chinese 會員登入
      //li(@click="to_section('.section_index')")
      li(@click="to_section('.section_top')")
        .eng INDEX
        .chinese 首頁
      li(@click="to_section('.section_about')")
        .eng FACT
        .chinese 節能簡介
      li(@click="to_section('.section_diagnose')")
        .eng DIAGNOSE
        .chinese 節能診斷
      li(@click="to_section('.section_room')")
        .eng ROOM
        .chinese 電器選取
      li(@click="to_section('.section_solution')", v-if="show_result")
        .eng SOLUTION
        .chinese 節能處方箋
      li(@click="to_section('.section_share')", v-if="show_result")
        .eng RESULT
        .chinese 診斷結果
      li(@click="to_manage()", v-if="member_data.isAdmin==1")
        .eng MANAGE
        .chinese 管理平台
      li(@click="logout()", v-if="member_data.isLogin")
        .eng LOGOUT
        .chinese 登出

</template>

<script>
import {mapState,mapMutations} from 'vuex' 
  export default {
  name: 'page_index',
  data () {
    return {

    }
  },
  mounted (){
    //console.log("index mounted");
    setTimeout(()=>{
      this.set_loading(false);
    },2000)

    this.validAdminUser();
  },
  computed: {...mapState(['loading','full_nav_open','show_result','member_data'])},
  methods: {
    ...mapMutations(['set_loading','toggle_nav', 'setMember_data_isAdmin']),
    to_section(target){
      $("html,body").animate({scrollTop: $(target).offset().top });
      this.toggle_nav();
    },
    login(){
      location.assign('/login');
    },
    logout(){
       event.preventDefault();
       document.getElementById('logout-form').submit();
    },
    validAdminUser(){
      axios.get("/api/user_admin/"+ store.state.member_data.users_id).then(
        (res)=>this.$store.commit("setMember_data_isAdmin", res.data)
      )
    },
    to_manage(){
      location.assign('/manage');
    },
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">
  

</style>

