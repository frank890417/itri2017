<template lang="pug">
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
</template>
<script>
import {mapState,mapMutations} from 'vuex' 
export default {
  name: 'page_share',
  data () {
    return {

    }
  },
  components: {

  },
  mounted (){
   
  },
  computed: {...mapState(['loading'])},
  methods: {...mapMutations(['set_loading'])}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>
