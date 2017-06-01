<template lang="pug">
  section.section_loading
    .info
      img(src="/img/電器/icon_電鍋.svg" width="220px")
      h4 Comsumption of daily life
      h1 家庭電器用電家計簿
      ul.graduation.text-left
        li(v-for="i in 10",:class="{longer: (i-1)%5==0}")

</template>

<script>
import {mapState,mapMutations} from 'vuex' 
export default {
  name: 'page_load',
  data () {
    return {
      msg: 'Welcome to Your Vue.js App'
    }
  },
  computed: {...mapState(['loading'])},
  methods: {...mapMutations(['set_loading'])}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


$color_grey: #4D4D4D
$color_yellow: #F5DA4E

$gutter_width: 20px

@mixin size($w,$h: $w)
  width: $w
  height: $h

@mixin ab_center
  position: absolute
  left: 50%
  top: 50%
  transform: translate(-50%, -50%)

section
  position: fixed
  +size(100%)
  left: 0
  top: 0
  background-color: white
  z-index: 3
  display: flex
  align-items: center
  justify-content: center



</style>
