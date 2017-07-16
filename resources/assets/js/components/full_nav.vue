<template lang="pug">
  
  section.full_nav.yellow
    ul
      li(@click="to_section('.section_index')")
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
        .chinese 結能處方箋
      li(@click="to_section('.section_share')", v-if="show_result")
        .eng RESULT
        .chinese 診斷結果

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
    // console.log("index mounted");
    setTimeout(()=>{
      this.set_loading(false);
    },2000)
  },
  computed: {...mapState(['loading','full_nav_open','show_result'])},
  methods: {
    ...mapMutations(['set_loading','toggle_nav']),
    to_section(target){
      $("html,body").animate({scrollTop: $(target).offset().top });
      this.toggle_nav();
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">
  

</style>

