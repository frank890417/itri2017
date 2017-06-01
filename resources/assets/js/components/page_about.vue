<template lang="pug">
  section.section_about
    .container
      .row
        .col-sm-4.text-left
          h2 
            img(src="/img/thunder.svg" width=45)
            br
            span 家電該換了嗎？
          p 老舊家電耗電量平均是節能家電的2.5倍，<br>在夏季用電尖峰時期影響更大。<br>
          p 
            .explain_block.white 十年以上老電器
            .explain_block.yellow 節能新電器 
        .col-sm-8
          //- h1 長條圖
          graph_bar.about_bar(:init="scrl_control_bar")
      .row
        .col-sm-7
          graph_bubble(:size="{width: 800,height: 500}")
        .col-sm-5.text-left
          h2 電用在哪？
          p 家庭用電採用累進分段的六級電費，用電量越多單價越高<br>由於夏季冷氣用電激增，導致電力公司無法供應用戶瞬間尖峰用電。因此為了降低用電，每年夏月(6/1-9/30)，電價比其他月份高。<br><br>夏月空調占總用電量的43%，非夏月則是電冰箱占26%，究竟是哪些電器默默吃掉你的用電呢？

</template>

<script>

import graph_bubble from './graph_bubble'
import graph_bar from './graph_bar'
import {mapState,mapMutations} from 'vuex' 

var d3 = require("d3")

export default {
  name: 'page_about',
  data () {
    return {
      scrl_control_bar: false
    }
  },
  components: {
    graph_bubble,
    graph_bar
  },
  mounted (){
    var vobj=this;
    $(window).scroll(function(){
      if ($(window).scrollTop()+$(window).height()>$(".about_bar").offset().top+$(".about_bar").outerWidth()){
        vobj.scrl_control_bar=true;
      }
    });
  },
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>