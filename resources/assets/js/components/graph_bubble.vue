<template lang="pug">
  svg.graph_bubble

</template>


<script>
import {mapState,mapMutations} from 'vuex' 
var d3 = require("d3")
import 'd3-force'
import {selection, select} from "d3-selection";
import "d3-transition"
import "d3-selection-multi";
import rooms from '../rooms'
export default {
  name: 'page_solution',
  props: ["datas"],
  data(){
    return {
      nodes: [{name: "電冰箱", value: 26.2},
     {name: "飲水機", value: 14.2},
     {name: "電熱水瓶", value: 12.4},
     {name: "冷氣機", value: 7.8},
     {name: "照明設備", value: 7.8},
     {name: "洗衣機", value: 5.2},
     {name: "電腦", value: 5.1}]
    }; 
  },
  components: {
  },
  mounted () {
    var nodes = this.nodes;
    //設定模擬(v4)
    var simulation = d3.forceSimulation(nodes)
        //設定碰撞與半徑
        .force("collide", d3.forceCollide().radius(function(d) { return d.r + 3; }).iterations(5))
        //設定中心
        .force("center", d3.forceCenter($(window).outerWidth()-300,$(window).outerHeight()/2))
        //本身有排斥力
        .force("charge", d3.forceManyBody()) 
        //往中心的力量
        .force("y", d3.forceY(5))
        .force("x", d3.forceX(5))
        //隨時間更新
        //.on("tick", ticked)
    ;

    //設定svg畫布
    var svg=d3.select("svg.graph_bubble");

    //attrs 一次設定多個屬性（要多引入js庫）
    svg.attrs({
      width: $(window).outerWidth(),
      height: $(window).outerHeight()
    });


    //填色圓形
    var cir=svg.selectAll("circle.fill")
      .data(nodes).enter().append("circle");
    //線框圓形
    var cir_stroke=svg.selectAll("circle.stroke")
      .data(nodes).enter().append("circle");
    //文字
    var text=svg.selectAll("text")
      .data(nodes).enter().append("text");
    //百分比
    var text_percent=svg.selectAll("text.percent")
      .data(nodes).enter().append("text");

    //顏色範圍函數(v4)
    var color= d3.scaleLinear()
                  .domain([0,60])
                  .range(["#EEC545","#F5DA4E"]);

    //拖曳事件與更新
    var drag = d3
              .drag()
              .on("start",function(){
                //重新開始模擬
                simulation.alphaTarget(0.3).restart();
                
              })
              .on("drag",function(){
                //更新位置
                  nodes[d3.select(this).attr("dataid")].x=d3.event.x;
                  nodes[d3.select(this).attr("dataid")].y=d3.event.y;
                
              }).on("end",function(){
                //結束
                 // nodes[d3.select(this).attr("dataid")].value+=5;   
              });

    //預先設定位置跟填色，還有select用的dataid
    cir.attrs({
        cx: function(d){return d.x},
        cy: function(d){return d.y},
        r: function(d){return d.r},
        fill: (d)=>{
          return color(d.r);
        },
        class: "fill",
        dataid: (d,i)=>(i)
      })
      .call(drag)
      .on("mouseover", handleMouseOver)
        .on("mouseout", handleMouseOut);

    //預設線框，透明度動態控制
    cir_stroke.attrs({
        cx: function(d){return d.x+5},
        cy: function(d){return d.y+5},
        r: function(d){return d.r},
        stroke: "#515050",
        fill: "none",
        opacity: (d)=>(d.value>5?1:0)
      })
    ;

    //設定文字樣式
    text.text((d)=>d.name)
        .attrs({
      x: (d)=>(d.x),
      y: (d)=>(d.y),
      })
      .attr("text-anchor", "middle")
      .attr("alignment-baseline","middle")
      .attr("fill","#515050")
      .style("font-size","14px")
      .style("letter-spacing","2px");

    //設定百分比
    text_percent
      .text((d)=> ( (parseInt(d.value)>2)?(d.value.toFixed(1)+"%"):"") )
        .attrs({
      x: (d)=>(d.x),
      y: (d)=>(d.y),
      })
      .attr("text-anchor", "middle")
      .attr("alignment-baseline","middle")
      .style("font-size","16px")
      .style("letter-spacing","2px");

    // cir.exit().remove();

    //維持計算 並不斷重新更新collide
    setInterval(function(){
      simulation.force("collide", d3.forceCollide().radius(function(d) { return d.r + 3; }).iterations(5))
        .alphaTarget(0.3).restart();
    },100);

    //更新
    function ticked(){
      //根據比例更新半徑
      nodes.forEach((d,i)=>{
        var target_r=Math.pow(d.value,0.6)*15;
        d.r+=(target_r-d.r)*0.02;
      });
      
      cir.attrs({
          cx: (d)=>d.x,
          cy: (d)=>d.y,
          r: (d)=>d.r,
        })
      cir.transition().duration(100)
        .attr("fill", (d,i)=>(vm.highlight_air?(nodes[i].name=="冷氣機"?color(d.r):"transparent"):color(d.r) )
        );
      
      cir_stroke
        .attrs({
        cx: function(d){return d.x+3},
        cy: function(d){return d.y+3},
        r: function(d){return d.r}
      })
      
      text 
        .text((d)=>d.name)
        .attrs({
        x: (d)=>(d.x),
        y: (d)=>(d.y-4),
      });

      
      text_percent  
        .attrs({
        x: (d)=>(d.x),
        y: (d)=>(d.y+15),
        }).text((d)=> ( (parseInt(d.value)>2)?((parseFloat(d.value) ).toFixed(1)+"%"):"") )
    }
  },
  computed: {...mapState(['device_result'])},
  methods: {...mapMutations([]),
  
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>
