<template lang="pug">
  section.section_share.yellow
    .container
      .row
        .col-sm-12
          h1.title RESULT
            span.chinese 診斷結果
            button_moreinfo(:msg="'計算結果僅供參考，詳細情況仍有許多變因'")
      .row
        .col-sm-4
          h4 我的用電是平均的...
          h1.mul_text {{ ( (user_degree?user_degree*12:device_result.value) / total_avg_year ).toFixed(1) }}倍
            button_moreinfo(:msg="'依據您目前填寫的資料與全國日均用電量相較結果'")
          span {{user_degree?"根據所填寫的用電度數估算":"根據電器加總度數估算"}}
          svg_inline_watch.elec_watch(src="/img/elec_watch-01.svg",
                     :degree="(user_degree?user_degree*12:device_result.value)",
                     :init="scrl_start_watch")

        .card.col-sm-8.text-left
          .card_inner
            h2 我家的吃電怪獸是….{{monster.name}}
            img(width=300,:src="'/img/電器/icon_'+monster.name+'.svg'")
            h3 處方箋小語：
            p {{monster.sharetext}}<br>老舊的電器平均會消耗超過兩倍的電，如果用新型有節能標章的電器，甚至可以有省電三倍以上的效能！
            .btn_group_inline
              button.btn.active(@click="share_result") 分享我的吃電怪獸
                i.fa.fa-share-alt
              button.btn(@click="toggle_result") 返回診斷
                i.fa.fa-undo
              a.btn(href="http://www.energypark.org.tw/", target="_blank") 節約能源園區
                i.fa.fa-external-link
</template>
<script>
import {mapState,mapMutations} from 'vuex' 
import button_moreinfo from "./button_moreinfo"
import svg_inline_watch from "./svg_inline_watch"
export default {
  name: 'page_share',
  data () {
    return {
      avg_month: 460,
      scrl_start_watch: false
    }
  },
  components: {
    button_moreinfo,
    svg_inline_watch
  },
  mounted (){
   
  },
  computed: {...mapState(['loading','device_result','devices','user_degree','scrollTop']),
    monster(){
      var result=this.devices.slice()
                    .sort((a,b)=>(b.device_consumption-a.device_consumption))
                    .slice(0,1)[0];
      // console.log(result);
      return result
    },
    total_avg_year (){
      // console.log(this.avg_month*(12));
      return (this.avg_month*(12));
    }
  },
  methods: {
    ...mapMutations(['set_loading','toggle_result']),
    share_result(){
      
    }
  },
  watch:{
    scrollTop(){
      if (this.scrollTop+$(window).height()>$(".elec_watch").offset().top+$(".elec_watch").outerWidth()-100){
        this.scrl_start_watch=true;
      }else{
        this.scrl_start_watch=false;
      }
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">
  

</style>
