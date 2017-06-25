<template lang="pug">
  i.fa.fa-info-circle
    .explain {{msg}}

</template>

<script>
import {mapState,mapMutations} from 'vuex' 
export default {
  name: 'button_moreinfo',
  props: {
    msg:{
      default(){
        return "Welcome to Your Vue.js App"
      }
    }
  },
  data () {
    return {
    }
  },
  computed: {...mapState(['loading'])},
  methods: {...mapMutations(['set_loading'])}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">

i.fa.fa-info-circle
  position: relative
  white-space: nowrap
  opacity: 1
  cursor: pointer
  font-size: 14px

  .explain
    position: absolute
    background-color: #FFF
    box-shadow: 0px 0px 10px rgba(0,0,0,0.3)
    opacity: 0
    padding: 10px 15px
    border-radius: 3px
    z-index: 50
    left: 50%
    bottom: 50%
    transoform: translate(-50%,-50%)
    pointer-events: none
    transition: 0.3s

  &:hover
    .explain
      opacity: 1




</style>
