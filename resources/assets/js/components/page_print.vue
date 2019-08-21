<template lang="pug">
section.section_print  
  h1 列印頁面
  h3 1. 使用者基本資料
    pre {{general_infos}}
    pre 房間坪數：{{house_area_size}}

  h3 2. 電器填寫內容(條列式清單)
  h3 3. 電器用電度數分析
  h3 4. 各房間用電度數分析
  h3 5. 診斷結果(我家用電是平均的__倍&我家吃電怪獸是___)
  h3 6. 行動處方籤(只留下吃電怪獸家電的建議，如果吃電怪獸判定沒有相符的，則顯示預設冷氣)
  h3 7. 節能電器推薦
  h3 8. 合格電器承裝檢驗維護業資料查詢
  br
  //- pre {{devices}}

</template>

<script>
import {mapState,mapMutations} from 'vuex' 
export default {
  name: 'page_print',
  data(){
    return {}
  },
  computed: {
    ...mapState(['house_area_size','general_infos','devices']),
    
  },
  
}
</script>
