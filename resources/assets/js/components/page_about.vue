<template lang="pug">
  section.section_about
    .container
      .row
        .col-sm-6.col-sm-offset-3
          //p 電器是我們生活中很重要的成員，但鮮少我們會注意到，直到帳單來臨時才發現比想像的多，讓我們來探索自己家中的電器，是否在不知不覺中老化，成為了家中的吃電怪獸吧！
        .col-sm-4.text-left
          h2(v-html="$t('page_about.section_1.title')")
            img(src="/img/thunder.svg" width=45)
            br
            span 
          p(v-html="$t('page_about.section_1.content')")
          p 
            .explain_block.white(@mouseover="highlight='summer'" ,@mouseleave="highlight=''") 十年以上老電器
            .explain_block.yellow(@mouseover="highlight='nsummer'" ,@mouseleave="highlight=''") 節能新電器 
        .col-sm-8.col-about-bargraph
          //- h1 長條圖
          graph_bar.about_bar(:init="scrl_control_bar",:highlight="highlight")
      .row 
        .col-sm-7
          graph_bubble#testgraph(:size="{width: page_width<800?page_width:800, height: 550, use_mul: 20}",:datas="bubble_data")
        .col-sm-5.text-left
          h2(v-html="$t('page_about.section_2.title')") 
          p(v-html="$t('page_about.section_2.content')") 
          .btn_group_inline
            button.btn(@click="mode='nsummer';$ga.event('switch_summer', 'click', 'not_summer')", :class="{active: mode=='nsummer'}") 全年 
            button.btn(@click="mode='summer';$ga.event('switch_summer', 'click', 'summer')", :class="{active: mode=='summer'}") 夏月
        
</template>

<script>

import graph_bubble from './graph_bubble'
import graph_bar from './graph_bar'
import {mapState,mapMutations} from 'vuex' 
import domtoimage from 'dom-to-image';

export default {
  name: 'page_about',
  data () {
    return {
      scrl_control_bar: false,
      mode: "summer",
      highlight: 'summer',
      sound_expand: null,
      page_width: window.outerWidth
    }
  },
  components: {
    graph_bubble,
    graph_bar
  },
  mounted (){
    var vobj=this;
    this.sound_expand=new Audio("/music/%E5%8B%95%E6%85%8B%E5%9C%96%E8%A1%A8%E9%9F%B3%E6%95%88/%E6%94%BE%E5%A4%A7.mp3");
  },
  watch:{
    mode(){
      this.sound_expand.currentTime=0;
      this.sound_expand.play();
    },
    scrollTop(){
      var vobj=this;
      if (this.scrollTop+$(window).height()>$(".about_bar").offset().top+$(".about_bar").outerWidth()){
        vobj.scrl_control_bar=true;
      }
    }
  },
  computed: {
    ...mapState(['scrollTop']),
    bubble_data(){

      if (this.mode=="nsummer"){
        return this.$t("page_about.section_2.chart.nsummer")
      }else{
        return this.$t("page_about.section_2.chart.summer")
      }

    }
  },
  methods:{
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>