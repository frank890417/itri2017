<template lang="pug">
  .page(:class='{full_nav_open:full_nav_open}')
    //navbar.navbar.navbar-fixed-top.nav
      ul
        li 首頁
        li 我的電去哪裡了？
        li 找出吃電怪獸
        li 家庭用電分析
        li 診斷結果
        li 吃電怪獸
    full_nav
    .hambergur(@click="toggle_nav",:class="{active: full_nav_open}")
      .bar
      .bar
      .bar

    .icon-music(@click="set_music_enable(!music_enabled)")
      span(v-if="music_enabled")
        i.fa.fa-volume-up
      span(v-else) 
        i.fa.fa-volume-off

    
      //- h1 {{nowSection}}
    transition(name="fade")
      page_load(v-show="loading")

    .page_view
      section.section_index
        .container
          .row
            .col-sm-3.text-right
              img(src="/img/thunder.svg" width=45)
              h4 Comsumption of daily life
              h1 家庭電器用電
                br.hidden-xs 
                | 家計簿
              h5 尋找家中的吃電怪獸
              br
              button.btn.btn-lg(@click="scroll_to_about(); $ga.event('explore', 'click')") 前往探索
              //.lang 中文     ENG
            .col-sm-9
              .consumption_pointer
                .pointer_el
              h2 Consumption<br>of Daily <br>Electricity
              video.scene(src="/video/2019_10_13_kitchen_OVERVIEW_ren1012_1.mp4" autoplay muted loop)
        ul
          li(v-for="track in HowlObjs",
             @click="toggleTrack(track)") 
            span {{ track.name }}  | 
            span {{ track.playing?'開啟':'關閉' }}
      page_print.print-visible
      page_about
      page_diagnose
      page_room
      section.section_show_result(v-if="!show_result")
        button.btn(@click="toggle_result();$ga.event('caculate_result', 'click','start');") 
          img(src="/img/thunder.svg" height=50)
          span 計算吃電怪獸診斷結果！
        span.default(v-if="!device_result.user_filled") 您沒有填寫電器，將顯示預設數值 
        //button.btn(@click="testv")
          span testv
      transition(name="fade")
        page_solution(v-if="show_result")
      transition(name="fade")
        page_share(v-if="show_result")
      transition(name="fade")
        section.section_embed(
              v-if="embed_section.show")
          .container
            .row
              .col-sm-12
                div(v-html="embed_section.content")
  
      footer
        .container
          .row
          .col-sm-4
            img(src="/img/thunder.svg" width=45)
            h4 Comsumption of daily life
            h1 家庭電器用電家計簿
            h5 尋找家裡的吃電怪獸
          .col-sm-8
            .row
              .col-sm-12.col_legal_statement
                br
                p 
                  a(href="https://www.itri.org.tw/chi/Content/Messagess/contents.aspx?SiteID=1&MmmID=620605466257000546" , target="_blank", @click="$ga.event('external_link', 'click','智權政策')") 智權政策
                  a(href="https://www.itri.org.tw/chi/Content/Messagess/contents.aspx?SiteID=1&MmmID=620605466251104462" , target="_blank", @click="$ga.event('external_link', 'click','法律聲明')") 法律聲明
                  //a(href="http://web3.moeaboe.gov.tw/ECW/populace/content/ContentFoot.aspx?menu_id=2900" , target="_blank") 政府網站資料開放宣告
                br
            .row
              .col-sm-12
                p 聯絡我們：energypark@itri.org.tw<br>
                  | 建議使用： Chrome、Edge、Safari 或 Firefox 瀏覽器<br>
                  | 最佳解析度： 1920 x 1080

            //.row
              
              .col-sm-4
                label 指導單位
                br
                img(src='http://wp.shu.edu.tw/wp-ctt/uploads/sites/23/2015/10/311.png' height=90 style="margin-top: -10px")
              .col-sm-4
                label 主辦單位
                br
                img(src="https://hvcs.gigaenergy.com.tw/ESK/Content/Image/Login/%E7%B6%93%E6%BF%9F%E9%83%A8%E8%83%BD%E6%BA%90%E5%B1%80.png" height=60)
              .col-sm-4
                label 執行單位
                br
                img(src="/img/itri_logo.png" height=70)
            
            br
            .row
              .col-sm-12 
                img(src="/img/itri_logo.png",height=50)
                //div © Industrial Technology Research Institute of Taiwan, R.O.C 工業技術研究院 版權所有
                div Copyright© 工業技術研究院

</template>

<script>
import page_load from './page_load'
import page_diagnose from './page_diagnose'
import page_about from './page_about'
import page_room from './page_room'
import page_solution from './page_solution'
import page_share from './page_share'
import page_print from './page_print'
import full_nav from './full_nav'
import axios from 'axios'
import {mapState,mapMutations} from 'vuex' 
import {Howl, Howler} from 'howler';

export default {
  data () {
    return {
      embed_section: {
        title: "",
        url: "https://zh.wikipedia.org/wiki/Wiki",
        show: true,
        minHeight: 450

      },
      nowSection: "",
      HowlObjs: []
    }
  },
  components: {
    page_load,
    page_diagnose,
    page_about,
    page_room,
    page_solution,
    page_share,
    page_print,
    full_nav
  },
  mounted (){
    // console.log("index mounted");
    setTimeout(()=>{
      this.set_loading(false);
    },2000)
    axios.get("/api/page/embedsection").then((res)=>{
      this.$set(this,"embed_section",JSON.parse(res.data.content));
      console.log("embed Data Loaded!", this.embed_section)
    })
    this.$store.dispatch('load_avg_house_data')
    window.send_user_data=this.send_user_data

    



  },
  computed: {
    ...mapState(['loading','full_nav_open','show_result','scrollTop',
                 'device_result','user_uuid','devices',
                 'debug','music_enabled']),
    // nowSection(){
    //   return
    //               

    // }  
  },
  watch: {
    debug(){
      if (this.debug){
        var sounds = `
        1照明.mp3
        2冷氣機.mp3
        3電暖氣.mp3
        4收音機.mp3
        5電熨斗.mp3
        6電腦.mp3
        7印表機.mp3
        8除濕機.mp3
        `
        let HowlObjs = sounds.trim().split("\n").map(o=>o.trim()).filter(o=>o)
        .map(o=>({
          obj: new Howl({
            src: ['/music/臥室/'+o],
            preload: true
          }),
          name: o
        })).map(track=>({
          id: track.obj.play(),
          obj: track.obj,
          playing: true,
          name: track.name
        }))
        this.$set(this,"HowlObjs",HowlObjs)
      }
    },
    scrollTop(){
       this.nowSection = Array.from($("section"))
            .map(el=>({ name: el.classList[0], top: $(el).offset().top - this.scrollTop + window.outerHeight/2 } ))
            .filter(obj=>obj.name!="full_nav" && obj.name!="section_loading")
            .find(obj=>obj.top>0).name
    },
    nowSection(pre,post){
      if(pre!=post){
        let pgName = '/'+ this.nowSection.replace("section_","")
        this.$ga.page(pgName)
        console.log("[GA] " + pgName)
      }
    }
  },
  methods: {
    ...mapMutations(['set_loading','toggle_nav','toggle_result','set_music_enable']),
    toggleTrack(track){
      // console.log(track.obj)
      // console.log(track.id)
      track.obj.mute(track.playing,track.id)
      track.playing=!track.playing
    },
    scroll_to_about(){
      $("html,body").animate({scrollTop: $(".section_about").offset().top },"slow");
    },
    get_navl_active(section){
      if ($(section).length>0){
        var top = $(section).position().top;
        var wh = $(window).outerHeight();
        return (top+ wh /2 < this.scrollTop) 
      }
      else{
        return false
      }
    },
    testv(){
	  alert("testv clicked:"+ store.state.member_data.email);
	},
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="sass?indentedSyntax">
iframe.embed_section
  width: 100%
  border: none

</style>
