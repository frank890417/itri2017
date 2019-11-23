<template lang="pug">
section.section_print  
  .container
    .row
      .col-sm-12
        
        //- br
        //- h4 1. 使用者基本資料
        //-   pre {{general_infos}}
        //-   pre 房間坪數：{{house_area_size}}

        h3 電器填寫內容
      .row
        .col-sm-6(v-for="(roomGroup) in [logListGroupArray.slice(0,2),logListGroupArray.slice(2)]")
          div(v-for="(roomData) in roomGroup")
            //- pre {{roomGroup}}
            h4 {{roomData[0]}} 
            ol
              li(v-for="log in roomData[1]") {{log.content}}
            hr
            
        //- h4 3. 電器用電度數分析
          //- pre {{device_result}}
        //- h4 4. 各房間用電度數分析
          //- pre {{roomResult}}
        //- h3 5. 診斷結果(我家用電是平均的__倍&我家吃電怪獸是___)
        //- h3 6. 行動處方籤(只留下吃電怪獸家電的建議，如果吃電怪獸判定沒有相符的，則顯示預設冷氣)
        //- h3 7. 節能電器推薦
        //- h3 8. 合格電器承裝檢驗維護業資料查詢
        br
  //- pre {{devices}}

</template>

<script>
import {mapState,mapMutations} from 'vuex' 
import _ from 'lodash'
export default {
  name: 'page_print',
  data(){
    return {}
  },

  computed: {
    ...mapState(['house_area_size','general_infos','devices','device_result', 
                 'scrollTop','site_width','rooms']),
    roomResult(){
      return this.rooms
      // .map((r,rid)=>{
      //   return {
      //     ...this.device_result.room_sum[rid],
      //     // room: r
      //   }
      // })
    },
    logListGroup(){
      return _.groupBy(
              (this.device_result.log||[]).filter(item=>item.place!='all'),
              (item)=>item.place)
    },
    logListGroupArray(){
      return Object.entries(this.logListGroup)
    }
  },
  
}
</script>
