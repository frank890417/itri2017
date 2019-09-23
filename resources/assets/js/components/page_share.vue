<template lang="pug">
  section.section_share.yellow
    .container
      .row
        .col-sm-12
          h1.title RESULT
            br.visible-xs
            span.chinese 診斷結果
            button_moreinfo(:msg="'計算結果僅供參考，詳細情況仍有許多變因'")
      .row
        .col-sm-4
          h4 我的用電是平均的...
          h1.mul_text {{ ( user_degree_final/ total_avg_year ).toFixed(1) }}倍
            button_moreinfo(:msg="'依據您目前填寫的資料與全國日均用電量相較結果'")
          span {{user_degree?"根據所填寫的用電度數估算":"根據電器加總度數估算"}}
          svg_inline_watch.elec_watch(src="/img/elec_watch-01.svg",
                     :degree="user_degree_final",
                     :init="scrl_start_watch")
          

        .card.col-sm-8.text-left(v-if='monster')
          .card_inner(style="margin-top: 10px")
            .row
              .col-md-12
                h2 我家的用電量分析結果
              .col-md-6
                graph_end_compare(use_power="0.2",
                                  use_unit="度" ,
                                  :datas="[{name: '您的用電', value: user_degree_final},{name: '年平均用電', value: avg_standard.result['年平均用電度數']}]")
                  
              .col-md-6
                ul.box-info(v-if="general_infos.county || general_infos.building_type || general_infos.member_count || general_infos.area_size")
                  li(v-if="general_infos.county")
                    b 居住地區: 
                    | {{ general_infos.county || "未填寫" }}
                  li(v-if="general_infos.building_type")
                    b 住宅類型: 
                    | {{ general_infos.building_type  || "未填寫" }}
                  li(v-if="general_infos.member_count")
                    b 家庭成員: 
                    | {{ general_infos.member_count || "未填寫" }}
                  li(v-if="general_infos.area_size")
                    b 坪數: 
                    | {{ general_infos.area_size || "未填寫"  }}
                ul
                  .compare-bars
                    label 同類型年平均用電 {{ avg_standard.result['年平均用電度數'] }}度
                    .bar(:style="compareBarStyles.avg") 
                    br
                    label 您的年平均用電度數 {{ user_degree_final }}度 (較平均 {{ compare_delta>0?'多':'少' }} {{ Math.abs(compare_delta) }} 度)
                    .bar(:style="compareBarStyles.user") 
                  br
                  li {{ compare_advice }}
                  div(v-if="debug")
                    li 
                      br
                      h5 比較基準：
                    li(v-if="avg_standard.result['居住地區']")
                      b 居住地區: 
                      | {{ avg_standard.result['居住地區'] }}
                    li(v-if="avg_standard.result['住宅類型']")
                      b 住宅類型: 
                      | {{ avg_standard.result['住宅類型'] }}
                    li(v-if="avg_standard.result['人口數']")
                      b 家庭成員: 
                      | {{ avg_standard.result['人口數'] }}
                    li(v-if="avg_standard.result['坪數']")
                      b 坪數: 
                      | {{ avg_standard.result['坪數'] }}
                    li(v-if="avg_standard.result['年平均用電度數']")
                      b 年平均用電度數: 
                      | {{ avg_standard.result['年平均用電度數'] }}
                    li(v-if="debug")
                      pre {{avg_standard}}
          .card_inner(style="margin-top: 30px")
            .row
              .col-md-12
                .row
                  .col-md-5
                    h3 我家的吃電怪獸是….{{monster.name}}
                    img(width=300,:src="'/img/電器/icon_'+monster.name+'.svg'")
                  .col-md-7
                    br
                    br
                    h4 處方箋小語：
                    p {{monster.sharetext}}<br>老舊的電器平均會消耗超過兩倍的電，如果用新型有節能標章的電器，甚至可以有省電三倍以上的效能！
              .col-md-12
                .btn_group_inline
                  button.btn.active(@click="share_result();$ga.event('share', 'click')") 分享我的吃電怪獸
                    i.fa.fa-share-alt
                  button.btn(@click="toggle_result();$ga.event('caculate_result', 'click','back')") 返回診斷
                    i.fa.fa-undo
                  a.btn(href="http://www.energypark.org.tw/", target="_blank",@click="$ga.event('external_link', 'click','http://www.energypark.org.tw/')") 節約能源園區
                    i.fa.fa-external-link
</template>
<script>
import {mapState,mapMutations} from 'vuex' 
import button_moreinfo from "./button_moreinfo"
import svg_inline_watch from "./svg_inline_watch"
import graph_end_compare from './graph_end_compare'

export default {
  name: 'page_share',
  data () {
    return {
      avg_month: 434,
      scrl_start_watch: false
    }
  },
  components: {
    button_moreinfo,
    svg_inline_watch,
    graph_end_compare
  },
  mounted (){
    console.log("info",this.general_infos)
    this.avg_month = parseInt(this.$t("page_share.avg_consump"))
  },
  computed: {...mapState(['debug','general_infos','avg_house_data','loading','device_result','devices','user_degree','scrollTop']),
    compare_advice(){
      let usr_value =  this.user_degree_final
      let avg_value =  this.avg_standard.result['年平均用電度數']

      if (usr_value <  avg_value){
        return "您的用電量低於平均用電量: 請繼續保持您的好用電習慣"
      } else if (usr_value < 1.5* avg_value){
        return "您的用電量高於平均用電量但小於1.5倍平均用電量: 有不錯的用電習慣，再努力一點節電可以更好喔"
      } else {
        return "您的用電量高於1.5倍平均用電量: 建議可以多參考節電手法，省視自己的用電習慣"
      }
      
    },
    compare_delta(){
      return  this.user_degree_final - this.avg_standard.result['年平均用電度數']
    },
    compareBarStyles(){
      let maximum = Math.max(this.user_degree_final,this.avg_standard.result['年平均用電度數'])
      return {
        user: {
          width: (this.user_degree_final*100/maximum)+"%"
        },
        avg:{
          width: (this.avg_standard.result['年平均用電度數']*100/maximum)+"%"
        } 

      }
    },
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
    },
    user_degree_final(){
      return (this.user_degree?(this.user_degree*6):this.device_result.value)
    },
    avg_standard(){
      let weather_north_area = "臺北市、新北市、宜蘭縣、基隆市、桃園縣、桃園市、新竹縣、新竹市、苗栗縣、連江縣、金門縣".split("、")
      let weather_middle_area = "台中市、彰化縣、南投縣、雲林縣、澎湖縣、花蓮縣".split("、")
      let weather_south_area = "嘉義市、嘉義縣、台南市、高雄市、屏東縣、台東縣".split("、")
      let getArea = (name)=> {
        if (weather_north_area.find(county=>county==name)){
          return "北部氣候區"
        }
        if (weather_middle_area.find(county=>county==name)){
          return "中部氣候區"
        }
        if (weather_south_area.find(county=>county==name)){
          return "南部氣候區"
        }
        return "全國"
      }
      let judgeCondition = (condition,value)=>{
        if (condition == "") return false
        let regex = /(\d{1,2})[\-\~]?(\d{1,2})?/gm;
        let result = regex.exec(condition)
        let min = 0
        let max = 0
        //- console.log(result)
        if (result.length==3){
          min = parseInt(result[1])
          max = parseInt(result[2])
        }else if (result.length==2){
          if (condition.indexOf("以上")!=-1){
            min = parseInt(result[1])
            max = 1000
          }
          if (condition.indexOf("以下")!=-1){
            min = 0
            max = parseInt(result[1])
          }
        }
        //- console.log(min,max,value,value >= min && value <= max)
        if (value >= min && value <= max){
          return true
        }else{
          return false
        }

      }
      let userArea = getArea(this.general_infos.county)

      let resultList = this.avg_house_data.filter((obj)=>{
        if (obj["居住地區"]){
          if (obj["居住地區"]!="全國" && obj["居住地區"]!=userArea){
            return false
          }
        }
        if (obj["人口數"]){
          if ( !judgeCondition( obj["人口數"],this.general_infos.member_count ) ){
            //- console.log("人口not match")
            return false
          }
        }
        if (obj["坪數"]){
          if ( !judgeCondition( obj["坪數"],this.general_infos.area_size ) ){
            return false
          }
        }
        if (obj["住宅類型"]){
          if (this.general_infos.building_type!=obj["住宅類型"]){
            return false
          }
        }
        return true
      })
      let result = resultList[0]

      let feedback = ""
      if (this.user_degree_final){
        if (this.user_degree_final< parseInt(result["年平均用電度數"]) ){
          feedback="您的用電量低於平均用電量: 請繼續保持您的好用電習慣"
        }else if (this.user_degree_final< 1.5* parseInt(result["年平均用電度數"]) ){
          feedback="您的用電量高於平均用電量但小於1.5倍平均用電量: 有不錯的用電習慣，再努力一點節電可以更好喔"
        }else {
          feedback="您的用電量高於1.5倍平均用電量: 建議可以多參考節電手法，檢視自己的用電習慣"
        }
      }

      return {
        count: resultList.length,
        area: userArea,
        result,
        resultList,
        feedback
      }
    }
  },
  methods: {
    ...mapMutations(['set_loading','toggle_result']),
    share_result(){
      window.open('https://www.facebook.com/sharer/sharer.php?u='+"http://saving.energypark.org.tw")
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
