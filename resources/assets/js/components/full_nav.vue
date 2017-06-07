<template lang="pug">
  
  section.full_nav.yellow
    ul
      li(@click="to_section('.section_index')") INDEX
      li(@click="to_section('.section_about')") FACT
      li(@click="to_section('.section_diagnose')") DIAGNOSE
      li(@click="to_section('.section_room')") PLAYGROUND
      li(@click="to_section('.section_solution')", v-if="show_result") SOLUTION
      li(@click="to_section('.section_share')", v-if="show_result") RESULT

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

