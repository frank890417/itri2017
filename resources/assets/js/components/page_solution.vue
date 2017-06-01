<template lang="pug">
  section.section_solution.grey
    .container
      .row.text-left
        .col-sm-12
          h1.title SOLUTION
            span.chinese 節能處方箋
        .card.col-sm-4
          .card_inner
            h2 {{rooms[now_place_id].name}}
            button.btn(v-for="(room,rid) in rooms",:class="{active:rid==now_place_id}",@click="now_place_id=rid") {{room.name}}
            img(src="/img/場景/kitchen.png", style="width: 100%")
            h4 耗電：{{device_result.room_sum[now_place_id].value}}度
            h4 {{rooms[now_place_id].name}}裡吃電怪獸排名：
            h4 1.電冰箱 2.烤麵包機 3.電磁爐
        .card.col-sm-8
          .card_inner
            h2 用電比例視覺化
            graph_bubble
            ul.room_part_value
              li(v-for="(r,id) in device_result.room_sum" , :style="{width: (r.percentage+6)+'%'}") {{rooms[id].name}} {{r.percentage}}%
        .card.col-sm-12
          .card_inner
            h2 節能處方
            p 你的 「冷氣」、「電風扇」、 「電暖器」已經老舊囉！<br>除了採用處方，更可以考慮更換有節能標章的新電器，聰明的省電！
            h4 行動處方
            p 採用高能源效率比值 EER 的冷氣機，EER 值每提高0.1可節省約4% 冷氣機用電。<br>正確安裝在通風良好，避免日光直射，裝設離地面至少75公分以上。<br>冷氣設定以26～28℃為宜，設定溫度每提高1℃可省電6%，配合電風扇使用，可使室內冷氣分佈均勻並增加舒適感。<br>選購「能源效率分級標示」級數小、效率高之冷氣機，聰明買聰明省。

            h4 節能標章電器推薦
            ul.recommend_list
              li(v-for="i in 5")
                img(:src="'/img/電器/icon_'+['冰箱','冷氣','吹風機','音響','微波爐'][i-1]+'.svg'", width=200)
                .device_title xx電器
                .device_com 功率: 300W

</template>



<script>
import {mapState,mapMutations} from 'vuex' 
import graph_bubble from './graph_bubble'
import rooms from '../rooms'
export default {
  name: 'page_solution',
  data(){
    return {
      now_place_id: 0,
      rooms
    }; 
  },
  components: {
    graph_bubble
  },
  mounted (){
    console.log("solution mounted");
    
  },
  computed: {...mapState(['device_result'])},
  methods: {...mapMutations([]),
    room_part(room_value){
      var percent = this.get_room_percentage(room_value);
      return {
        width: percent+"%",

      }
    },
    get_room_percentage(room_value){
      return parseInt(100*room_value/this.device_result.value);
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>
