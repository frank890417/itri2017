<template lang="pug">
  section.full_nav.yellow(v-show="full_nav_open")
    ul
      li INDEX
      li FACT
      li PLAYGROUND
      li DIAGNOSE
      li SOLUTION

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
  computed: {...mapState(['loading','full_nav_open'])},
  methods: {...mapMutations(['set_loading','toggle_nav'])}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">
  

</style>

