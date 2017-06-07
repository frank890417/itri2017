<template lang="pug">
  div.page
    .hambergur(@click="toggle_nav",:class="{active: full_nav_open}")
      .bar
      .bar
      .bar
    transition(name="fade")    
      full_nav(v-if="full_nav_open")
    transition(name="fade")
      page_load(v-if="loading")

    section.section_index
      .container
        .row
          .col-sm-4.text-right
            img(src="/img/thunder.svg" width=45)
            h4 Comsumption of daily life
            h1 家庭電器用電家計簿
            h5 尋找家裡的吃電怪獸
            br
            button.btn(@click="scroll_to_about") 前往探索
            //.lang 中文     ENG
          .col-sm-8
            .consumption_pointer
            h1 Consumption<br>of Daily <br>Electricity
            img.scene(src="/img/場景/kitchen2.png")
    page_about
    page_diagnose
    page_room
    section.section_show_result(v-if="!show_result")
      button.btn(@click="toggle_result") 
        img(src="http://itri2017.dev/img/thunder.svg" height=50)
        span 計算吃電怪獸診斷結果！     
    transition(name="fade")
      page_solution(v-if="show_result")
    transition(name="fade")
      page_share(v-if="show_result")
    footer
      .container
        .col-sm-4
          img(src="/img/thunder.svg" width=45)
          h4 Comsumption of daily life
          h1 家庭電器用電家計簿
          h5 尋找家裡的吃電怪獸
        .col-sm-8
          br
          br
          br

          .row
            
            .col-sm-4
              label 指導單位
              br
              img(src='http://wp.shu.edu.tw/wp-ctt/uploads/sites/23/2015/10/311.png' height=90 style="margin-top: -10px")
            .col-sm-4
              label 主辦單位
              br
              img(src="https://hvcs.gigaenergy.com.tw/ESK/Content/Image/Login/%E7%B6%93%E6%BF%9F%E9%83%A8%E8%83%BD%E6%BA%90%E5%B1%80.png" height=60)
            .col-sm-4
              label 執行單位
              br
              img(src="/img/itri_logo.png" height=70)
          .row
            .col-sm-12 智權政策   |   法律聲明   |   聯絡我們
          br
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
  computed: {...mapState(['loading','full_nav_open','show_result'])},
  methods: {
    ...mapMutations(['set_loading','toggle_nav','toggle_result']),
    scroll_to_about(){
      $("html,body").animate({scrollTop: $(".section_about").offset().top },"slow");
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>
