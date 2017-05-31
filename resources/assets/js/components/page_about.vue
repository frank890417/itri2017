<template lang="pug">
  section.section_about
    .container
      .row
        .col-sm-4.text-left
          h2 家電該換了嗎？
          p 老舊家電耗電量平均是節能家電的2.5倍，<br>在夏季用電尖峰時期影響更大。
        .col-sm-8
          //- h1 長條圖
          svg.graph_new_old
      .row
        .col-sm-7
          h1 泡泡圖
        .col-sm-5.text-left
          h2 電用在哪？
          p 家庭用電之電費計算，是採用累進分段的六級電費，用電量越多單價越高；<br>夏季冷氣用電激增，電力公司無法供應用戶瞬間尖峰用電，為降低用電，每年夏月(6/1-9/30)用電，電價比其他月份高。<br><br>夏月空調占總用電量的43%，非夏月則是電冰箱占26%，究竟是哪些電器默默吃掉你的用電呢？

</template>

<script>
import {mapState,mapMutations} from 'vuex' 
var d3 = require("d3")

export default {
  name: 'page_about',
  data () {
    return {

    }
  },
  mounted (){
    // console.log("index mounted");
    var datas ={summer_old:[56.251,54.284,64.618,72.400,91.070,102.100,107.110,112.320,109.910,105.570,102.510,85.538],summer_new:[24.311,23.213,27.275,33.678,34.558,36.672,38.695,38.877,37.703,37.243,34.554,32.246]} ;
    datas.summer_mul=[];
    for(var i=0;i<datas.summer_old.length;i++){
      datas.summer_mul[i]=datas.summer_old[i]/datas.summer_new[i];
    }
    var svg=d3.select("svg.graph_new_old");
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

    var mul=2;
    var split_width=80;
    var span_width=30;
    var rect_width=15;
    var graph_height=300;

    summer_old.attr("fill","transparent").attr("stroke","black")
      
      .attr("x",(d,i)=>(i*split_width))
      .attr("y",(d,i)=>(graph_height))
      .attr("width",rect_width)
      .attr("height",0)
      .transition().duration(1000).delay((d,i)=>i*100) 
      .attr("y",(d,i)=>(graph_height-d*mul))
      .attr("height",(d,i)=>d*mul);
      
    summer_new.attr("fill","#F6D568").attr("stroke","black")
      .attr("x",(d,i)=>(i*split_width+span_width))
      .attr("y",(d,i)=>(graph_height))
      .attr("width",rect_width)
      .attr("height",0)
      .transition().duration(1000).delay((d,i)=>i*100)
      .attr("y",(d,i)=>(graph_height-d*mul))
      .attr("height",(d,i)=>d*mul);
      
    mul_text.attr("fill","#333")
      .text((d,i)=>d.toFixed(2))
      .attr("x",(d,i)=>(i*split_width+1.5*span_width/2))
      .attr("y",graph_height+30)
      .attr("text-anchor","middle")
      .style("font-size","14px")
      


    // summer_old.transition().duration(500).attr("fill","transparent");
    // summer_new.transition().delay(500).duration(500).attr("fill","transparent");

    function show_summer(){
      console.log("show old");
      summer_new.transition().duration(500)
      .style("opacity",0.2);
      summer_old.transition().duration(500)
      .style("opacity",1);
    }
    function show_summer_new(){
      summer_new.transition().duration(500)
      .style("opacity",1);
      summer_old.transition().duration(500)
      .style("opacity",0.2);
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>