<template lang="jade">
  .svg_inline(v-html="svg_html",:hash="hash")

</template>


<script>
import axios from 'axios'
// import svg_inline_distribution from './svg_inline_distribution'
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
    },
    nodes: {
      default: []
    }
  },
  data(){
    return {
      svg_html: "",
      hash: parseInt(Math.random()*1000000),
      temp_degree: -5,
      // use_nodes: [
      //   {
      //     name: "bathroom",
      //     value: 94
      //   },
      //   {
      //     name: "livingroom",
      //     value: 624
      //   },{
      //     name: "bedroom",
      //     value: 531
      //   },{
      //     name: "kitchen",
      //     value: 23
      //   },{
      //     name: "other",
      //     value: 135
      //   }
      // ]
    }
  },
  watch:{
    nodes(){
      console.log(this.nodes)
      let vobj=this
      // this.nodes.forEach(node=>{
      //   this.$set(this.use_nodes.find(n=>n.name==node.name),'value',node.value)
      // })
      this.$nextTick(()=>{
        this.updateNodes()
      })
    }
  },
  methods: {
    updateNodes(){
      this.nodes.forEach(node=>{
        // console.log($(`[id='value_${node.name}']`))
        $(`[data-name='value_${node.name}']`).html("<tspan>"+node.value+"</tspan>"+"<tspan font-size='32px'> 度</tspan>")
      })
    }
  },
  mounted(){
    let vobj=this
    axios.get(this.src).then((r)=>{
      // console.log(r)
      this.svg_html=r.data
    })
    setInterval(()=>{
      this.updateNodes()
    },1000)
  },
  compontents: {
    // svg_inline_distribution
  }
}
</script>

<style scoped lang="sass?indentedSyntax">

</style>