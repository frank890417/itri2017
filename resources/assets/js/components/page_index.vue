<template lang="pug">
  div.page
    .hambergur
      .bar
      .bar
      .bar
    section.navbar.yellow
      ul
        li INDEX
        li FACT
        li PLAYGROUND
        li DIAGNOSE
        li SOLUTION

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
            .lang 中文     ENG
          .col-sm-8
            h1 Consumption<br>of Daily <br>Electricity
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
    page_share
  },
  mounted (){
    // console.log("index mounted");
    setTimeout(()=>{
      this.set_loading(false);
    },2000)
  },
  computed: {...mapState(['loading'])},
  methods: {...mapMutations(['set_loading'])}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>
