<template lang="pug">
  section.section_share.yellow
    .container
      .row
        .col-sm-12
          h1.title RESULT
            span.chinese 診斷結果
      .row
        .col-sm-4
          h4 我的用電是平均的...
          h1 {{ (device_result.value / total_avg_year ).toFixed(1) }}倍
          img(src="/img/watch.svg")
        .card.col-sm-8.text-left
          .card_inner
            h2 我家的吃電怪獸是….{{monster.name}}
            img(width=300,:src="'/img/電器/icon_'+monster.name+'.svg'")
            h3 處方箋小語：
            p 冰箱老了，看到帳單的你就哭了。<br>老舊的電器平均會消耗超過兩倍的電，如果用新型有節能標章的電器，甚至可以有省電三倍以上的效能！
            .btn_group_inline
              button.btn.active 分享我的吃電怪獸
              button.btn(@click="toggle_result") 返回診斷
</template>
<script>
import {mapState,mapMutations} from 'vuex' 
export default {
  name: 'page_share',
  data () {
    return {
      avg_month: 460
    }
  },
  components: {

  },
  mounted (){
   
  },
  computed: {...mapState(['loading','device_result','devices']),
    monster(){
      var result=this.devices
                    .sort((a,b)=>(b.device_consumption-a.device_consumption))
                    .slice(0,1)[0];
      console.log(result);
      return result
    },
    total_avg_year (){
      console.log(this.avg_month*(12));
      return (this.avg_month*(12));
    }
  },
  methods: {...mapMutations(['set_loading','toggle_result'])}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>
