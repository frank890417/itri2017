<template lang="pug">
  svg.graph_bar(:data-hash="hash")

</template>


<script>
import {mapState,mapMutations} from 'vuex' 
import rooms from '../rooms';

export default {
  name: 'graph_bar',
  props: ["datas","size","init","highlight"],
  data(){
    return {
      nodes: {summer_old:[56.251,54.284,64.618,72.400,91.070,102.100,107.110,112.320,109.910,105.570,102.510,85.538],summer_new:[24.311,23.213,27.275,33.678,34.558,36.672,38.695,38.877,37.703,37.243,34.554,32.246]},
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
    highlight(){
      // var svg=d3.select("svg.graph_bar[data-hash='"+this.hash+"']");
      var svg=d3.select(this.$el);
      console.log("d3 select root svg",svg);
      var summer_old=svg.selectAll("rect.tem_summer")
      var summer_new=svg.selectAll("rect.tem_summer_new")
      if (this.highlight=="summer"){
        summer_new.transition().duration(500)
          .style("opacity",0.2);
        summer_old.transition().duration(500)
          .style("opacity",1);
      }else if(this.highlight=="nsummer"){
        summer_new.transition().duration(500)
          .style("opacity",1);
        summer_old.transition().duration(500)
          .style("opacity",0.2);
      }else{
        summer_new.transition().duration(500)
          .style("opacity",1);
        summer_old.transition().duration(500)
          .style("opacity",1);
      }
    }
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
      datas.summer_mul=[];
      for(var i=0;i<datas.summer_old.length;i++){
        datas.summer_mul[i]=datas.summer_old[i]/datas.summer_new[i];
      }
      var svg=d3.select("svg.graph_bar[data-hash='"+this.hash+"']");
      svg.attr("width","1000px");
      svg.attr("height","350px");


      var summer_old=svg.selectAll("rect.tem_summer")
        .data(datas.summer_old)
        .enter().append("rect")
        .attr("class","tem_summer")
      var summer_new=svg.selectAll("rect.tem_summer_new")
        .data(datas.summer_new)
        .enter().append("rect")
        .attr("class","tem_summer_new")
      var mul_text = svg.selectAll("text.mul")
        .data(datas.summer_mul)
        .enter().append("text")
        .attr("class","mul")

      var label_ns = svg.selectAll("text.ns")
        .data(datas.summer_old)
        .enter().append("text")

      var label_s = svg.selectAll("text.s")
        .data(datas.summer_new)
        .enter().append("text")


      var mul=2;
      var split_width=80;
      var span_width=30;
      var rect_width=15;
      var graph_height=300;


      var axis = svg.append("line")
                  .attr("x1",-200).attr("x2",1000).attr("y1",graph_height).attr("y2",graph_height)
                  .attr("stroke","#333")

      // var unitline = svg.append("line")
      //             .attr("x1",-200).attr("x2",1000).attr("y1",graph_height/2).attr("y2",graph_height/2)
      //             .attr("stroke","#333")

      summer_old.attr("fill","transparent").attr("stroke","black")
        
        .attr("x",(d,i)=>(i*split_width+50))
        .attr("y",(d,i)=>(graph_height))
        .attr("width",rect_width)
        .attr("height",0)
        .transition().duration(1000).delay((d,i)=>i*100) 
        .attr("y",(d,i)=>(graph_height-d*mul))
        .attr("height",(d,i)=>d*mul);
        
      summer_new.attr("fill","#F6D568").attr("stroke","black")
        .attr("x",(d,i)=>(i*split_width+span_width+50))
        .attr("y",(d,i)=>(graph_height))
        .attr("width",rect_width)
        .attr("height",0)
        .transition().duration(1000).delay((d,i)=>i*100)
        .attr("y",(d,i)=>(graph_height-d*mul))
        .attr("height",(d,i)=>d*mul);
        
      mul_text.attr("fill","#333")
        .text((d,i)=>(i+1)+'月')
        .attr("x",(d,i)=>(i*split_width+1.5*span_width/2+50))
        .attr("y",graph_height+30)
        .attr("text-anchor","middle")
        .style("font-size","14px")

      // label_ns  
      //   .attr("x",(d,i)=>(i*split_width))
      //   .attr("y",(d,i)=>(graph_height))
      //   .text((d,i)=>(i+1)+'月')
      //   .attr("width",rect_width)
      //   .attr("height",0)
      //   .text(d=>d)
      //   .transition().duration(1000).delay((d,i)=>i*100) 
      //   .attr("y",(d,i)=>(graph_height-d*mul+ 10))
      //   .attr("height",(d,i)=>d*mul);

      // label_s.attr("fill","#333")
      //   .attr("x",(d,i)=>(i*split_width+span_width))
      //   .attr("y",(d,i)=>(graph_height))
      //   .attr("width",rect_width)
      //   .attr("height",0)
      //   .text(d=>d)
      //   .transition().duration(1000).delay((d,i)=>i*100)
      //   .attr("y",(d,i)=>(graph_height-d*mul+ 10))
      //   .attr("height",(d,i)=>d*mul);
        
        
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">
circle
  cursor: pointer

</style>
