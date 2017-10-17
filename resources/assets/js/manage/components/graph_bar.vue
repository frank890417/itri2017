<template lang="pug">
  svg.graph_bar

</template>


<script>
import {mapState,mapMutations} from 'vuex' 
import * as d3 from 'd3/build/d3.node';
import d3SelectMulti from 'd3-selection-multi';
// import rooms from '../rooms'/;

export default {
  name: 'graph_bar',
  props: ["datas","size","init","highlight"],
  data(){
    return {
      nodes: [
        {
          name: "A",
          value: 30
        },
        {
          name: "B",
          value: 40
        },
        {
          name: "C",
          value: 50
        },
        {
          name: "D",
          value: 60
        },
        {
          name: "E",
          value: 10
        }
      ],
      hash: parseInt(Math.random()*100000),
      width: 600,
      height: 500
    }; 
  },
  components: {
  },
  watch: {
    init(){
      if (this.init){
        this.init_graph()
      }
    },
  },
  mounted () {
    console.log("graph bar mounted");
    if (typeof this.init== 'undefined'){
      this.init_graph()
    }
  },
  computed: {...mapState(['device_result'])},
  methods: {...mapMutations([]),
    init_graph(){

      console.log("graph bar init");
      var datas = this.nodes ;

      var svg=d3.select(this.$el);
      svg.attr("width","1000px");
      svg.attr("height","350px");

      var bars=svg.selectAll("rect.bar")
        .data(datas)
        .enter().append("rect")
        .attr("class","bar")
      // var summer_new=svg.selectAll("rect.tem_summer_new")
      //   .data(datas.summer_new)
      //   .enter().append("rect")
      //   .attr("class","tem_summer_new")
      var mul_text = svg.selectAll("text.mul")
        .data(datas)
        .enter().append("text")
        .attr("class","mul")

      var mul=2;
      var split_width=80;
      var span_width=30;
      var rect_width=40;
      var graph_height=300;


      var axis = svg.append("line")
                  .attr("x1",-200).attr("x2",1000)
                  .attr("y1",graph_height).attr("y2",graph_height)
                  .attr("stroke","#333")

      // var unitline = svg.append("line")
      //             .attr("x1",-200).attr("x2",1000).attr("y1",graph_height/2).attr("y2",graph_height/2)
      //             .attr("stroke","#333")

      bars.attr("fill","transparent").attr("stroke","black")
        .attr("x",(d,i)=>(i*split_width))
        .attr("y",(d,i)=>(graph_height))
        .attr("width",rect_width)
        // .attr("height",0)
        // .transition().duration(1000).delay((d,i)=>i*100) 
        .attr("y",(d,i)=>(graph_height-d.value*mul))
        .attr("height",(d,i)=>d.value*mul);
        
      mul_text.attr("fill","#333")
        .text((d,i)=>d.name)
        .attr("x",(d,i)=>(i*split_width+1.5*span_width/2))
        .attr("y",graph_height+30)
        .attr("text-anchor","middle")
        .style("font-size","14px")
        
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">
circle
  cursor: pointer

</style>
