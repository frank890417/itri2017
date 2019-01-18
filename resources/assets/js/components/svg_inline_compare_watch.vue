<template lang="jade">
  div
    .svg_inline.comp-watch(v-html="svg_html",:hash="hash", ref="comp_watch")
    label.text-center 年耗電量 {{ parseInt(temp_degree) }} 度
</template>


<script>
import axios from 'axios'
export default {
  props: {
    src: {
      default: ""
    },
    degree: {
      default: 0
    },
    init: {
      default: false
    }
  },
  data(){
    return {
      svg_html: "",
      hash: parseInt(Math.random()*1000000),
      temp_degree: -5
    }
  },
  watch:{
    degree(){
      this.updatePointer()
      
    },
    init(newVal,oldVal){
      if (newVal && !oldVal){
        this.temp_degree=0
        this.updatePointer()

      }
      if (!newVal && oldVal){
        this.temp_degree=0
        this.updatePointer()
      }
    }
  },
  mounted(){
    let vobj=this
    axios.get(this.src).then((r)=>{
      // console.log(r)
      this.svg_html=r.data
      this.$nextTick(()=>{
        this.updatePointer()
      })
    })
    // setInterval(()=>{
      // $($(`.svg_inline[hash=${this.hash}] text`)[0]).text(Math.ceil(this.temp_degree) )
    // },200)
  },
  methods: {
    //更新元素轉動角度
    updatePointer(){
      // console.log("pointer", this.$refs.comp_watch.querySelector(".pointer") )
      
      let vobj=this
      // var $pointers = [4,3,2,1].map((num)=>$(`[data-name='pointer_${num}']`))
      // var $degree_text = $($(`.svg_inline[hash=${this.hash}] text`)[0])
      var interval_approach = setInterval(()=>{

        if (Math.abs(vobj.temp_degree-vobj.degree)>1){
          if (vobj.init){
            vobj.temp_degree-=(vobj.temp_degree-vobj.degree)*0.025
            // $degree_text.text(Math.ceil(this.temp_degree) )
            $(this.$refs.comp_watch.querySelector(".pointer")).css("transform","rotate("+180*(this.temp_degree/2000 )+"deg)")
           
            // console.log(vobj.temp_degree)
          }
        }else{
          vobj.temp_degree=vobj.degree
          clearInterval(interval_approach)
        }
      },33)
    }
  }
}
</script>

<style lang="sass?indentedSyntax">
.comp-watch
  g.pointer
    transform-origin: 90% 50%


</style>