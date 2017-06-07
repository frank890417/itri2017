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
            .explain_block.white(@mouseover="highlight='summer'" ,@mouseleave="highlight=''") 十年以上老電器
            .explain_block.yellow(@mouseover="highlight='nsummer'" ,@mouseleave="highlight=''") 節能新電器 
        .col-sm-8
          //- h1 長條圖
          graph_bar.about_bar(:init="scrl_control_bar",:highlight="highlight")
      .row
        .col-sm-7
          graph_bubble(:size="{width: 800,height: 550}",:datas="bubble_data")
        .col-sm-5.text-left
          h2 電用在哪？
          p 家庭用電採用累進分段的六級電費，用電量越多單價越高<br>由於夏季冷氣用電激增，導致電力公司無法供應用戶瞬間尖峰用電。因此為了降低用電，每年夏月(6/1-9/30)，電價比其他月份高。<br><br>夏月空調占總用電量的43%，非夏月則是電冰箱占26%，究竟是哪些電器默默吃掉你的用電呢？
          .btn_group_inline
            button.btn(@click="mode='summer'", :class="{active: mode=='summer'}") 夏月
            button.btn(@click="mode='nsummer'", :class="{active: mode=='nsummer'}") 非夏月 

</template>

<script>

import graph_bubble from './graph_bubble'
import graph_bar from './graph_bar'
import {mapState,mapMutations} from 'vuex' 

export default {
  name: 'page_about',
  data () {
    return {
      scrl_control_bar: false,
      mode: "summer",
      highlight: 'summer'
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
  watch:{
    mode(){
      var sound = new Audio("http://awiclass.monoame.com/%E5%8B%95%E6%85%8B%E5%9C%96%E8%A1%A8%E9%9F%B3%E6%95%88/%E6%94%BE%E5%A4%A7.mp3");
          sound.play();
    }
  },
  computed: {
    bubble_data(){
      if (this.mode=="summer"){
        return [{name: "電冰箱", value: 26.2},
               {name: "飲水機", value: 14.2},
               {name: "電熱水瓶", value: 12.4},
               {name: "冷氣機", value: 7.8},
               {name: "照明設備", value: 7.8},
               {name: "洗衣機", value: 5.2},
               {name: "電腦", value: 5.1}];
      }else{
        return [{name: "冷氣機", value: 43.1},
               {name: "電冰箱", value: 16.9},
               {name: "飲水機", value: 8.8},
               {name: "電熱水瓶", value: 6.7},
               {name: "照明設備", value: 5.0},
               {name: "洗衣機", value: 5.2},
               {name: "電腦", value: 5.1}];
      }

    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>