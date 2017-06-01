<template lang="pug">
  div.page
    .hambergur(@click="toggle_nav",:class="{active: full_nav_open}")
      .bar
      .bar
      .bar
    full_nav
    transition(name="fade")
      page_load(v-if="loading")

    section.section_index
      .container
        .row
          .col-sm-4.text-right
            h4 Comsumption of daily life
            h1 家庭電器用電家計簿
            h5 尋找家裡的吃電怪獸
            .btn.btn-primary 前往探索
            //.lang 中文     ENG
          .col-sm-8
            .consumption_pointer
            h1 Consumption<br>of Daily <br>Electricity
            img.scene(src="/img/場景/kitchen.png")
    page_about
    page_diagnose
    page_room
    page_solution
    page_share
    footer
      .container
        .col-sm-4
          h2 家庭電器用電家計簿
        .col-sm-8
          .row
            .col-sm-4
              label 指導單位
            .col-sm-4
              label 主辦單位
            .col-sm-4
              label 執行單位
          .row
            .col-sm-12 智權政策   |   法律聲明   |   聯絡我們
          .row
            .col-sm-12 © 工業技術研究院 ITRI All Rights Reserved.    

</template>

<script>
import page_load from './page_load'
import page_diagnose from './page_diagnose'
import page_about from './page_about'
import page_room from './page_room'
import page_solution from './page_solution'
import page_share from './page_share'
import full_nav from './full_nav'
import {mapState,mapMutations} from 'vuex' 

export default {
  name: 'page_index',
  data () {
    return {

    }
  },
  components: {
    page_load,
    page_diagnose,
    page_about,
    page_room,
    page_solution,
    page_share,
    full_nav
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
