<template lang="jade">
  div.svg_inline(v-html="svg_html",:hash="hash")
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
      let vobj=this
      var $pointers = [4,3,2,1].map((num)=>$(`[data-name='pointer_${num}']`))
      var $degree_text = $($(`.svg_inline[hash=${this.hash}] text`)[0])
      var interval_approach = setInterval(()=>{

        if (Math.abs(vobj.temp_degree-vobj.degree)>1){
          if (vobj.init){
            vobj.temp_degree-=(vobj.temp_degree-vobj.degree)*0.03
            $degree_text.text(Math.ceil(this.temp_degree) )
            $pointers[0].css("transform","rotate("+36*(this.temp_degree/1000 )+"deg)")
            $pointers[1].css("transform","rotate("+36*(this.temp_degree%1000/100)+"deg)")
            $pointers[2].css("transform","rotate("+36*(this.temp_degree%100/10)+"deg)")
            $pointers[3].css("transform","rotate("+36*(this.temp_degree%10)+"deg)")
            // console.log(vobj.temp_degree)
          }
        }else{
          clearInterval(interval_approach)
        }
      },33)
      
    }
  },
  mounted(){
    let vobj=this
    axios.get(this.src).then((r)=>{
      console.log(r)
      this.svg_html=r.data
      vobj.degree=0
    })
    setInterval(()=>{
      $($(`.svg_inline[hash=${this.hash}] text`)[0]).text(Math.ceil(this.temp_degree) )
    },200)
  }
}
</script>

<style scoped lang="sass?indentedSyntax">
.svg_inline
  text

</style>