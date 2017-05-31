<template lang="pug">
  section.section_solution.grey
    .container
      .row.text-left
        .col-sm-12
          h1.title SOLUTION
            span.chinese 節能處方箋
        .card.col-sm-4
          .card_inner
            h2 房間圖案
            button.btn(v-for="(room,rid) in rooms",:class="{active:rid==now_place_id}",@click="now_place_id=rid") {{room.name}}
            h4 {{rooms[now_place_id].name}}裡吃電怪獸排名：
            h4 1.電冰箱 2.烤麵包機 3.電磁爐
        .card.col-sm-8
          .card_inner
            h2 用電比例視覺化
            graph_bubble
            ul.room_part_value
              li(v-for="(r,id) in device_result.room_sum" , :style="room_part(r)") {{rooms[id].name}}  {{get_room_percentage(r)}}%
        .card.col-sm-12
          .card_inner
            h2 節能處方
            p 你的 「冷氣」、「電風扇」、 「電暖器」已經老舊囉！<br>除了採用處方，更可以考慮更換有節能標章的新電器，聰明的省電！

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
