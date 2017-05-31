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
    section.section_room
      .container
        .row
          .col-sm-8
            h4 / Kitchen
            h1 客廳
            h5 全家休閒吃飯的空間，常常會忘記關冷氣
            ul
              li 廚房
              li 客廳
              li 餐廳
              li 浴室
          .col-sm-4

            h2 選電器的表單
            .form_block
              .form-group
                .info
                  .eng Speaker
                  h1 音響
            .form_block
              .form-group
                label 音響     除濕機     電視    燈    冷氣
            .form_block
              .form-group
                label 電器瓦數
                input
              .form-group
                label 數量
                input(type="number")
              .form-group
                label 平均使用
                .btn-group
                  button 很少
                  button 偶爾
                  button 經常
                  button 頻繁
              .form-group
                label 購買年份
                input(type="number")
    section.section_solution.grey
      .container
        .row.text-left
          .col-sm-12
            h1.title SOLUTION
              span.chinese 節能處方箋
          .card.col-sm-4
            .card_inner
              h2 房間圖案
              h4 廚房裡吃電怪獸排名：
              h4 1.電冰箱 2.烤麵包機 3.電磁爐
          .card.col-sm-8
            .card_inner
              h2 泡泡圖
              h4 廚房用電比例
          .card.col-sm-12
            .card_inner
              h2 節能處方
              p 你的 「冷氣」、「電風扇」、 「電暖器」已經老舊囉！<br>除了採用處方，更可以考慮更換有節能標章的新電器，聰明的省電！

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
    page_about
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
