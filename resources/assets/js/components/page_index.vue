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
    section.section_share.yellow
      .container
        .row
          .col-sm-4
            h4 我的用電是平均的...
            h1 2.35倍
          .card.col-sm-8.text-left
            .card_inner
              h2 我家的吃電怪獸是….老舊的冰箱
              h3 處方箋小語：
              p 處方箋小語：<br>冰箱老了，看到帳單的你就哭了。<br>老舊的電器平均會消耗超過兩倍的電，如果用新型有節能標章的電器，甚至可以有省電三倍以上的效能！
              button.btn.btn-primary 分享我的吃電怪獸
              button.btn 再玩一次
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
    page_solution
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
