<template lang="pug">
  svg.graph_bubble(:data-hash="hash")

</template>


<script>
import {mapState,mapMutations} from 'vuex' 
import * as d3 from 'd3/build/d3.node';
import d3SelectMulti from 'd3-selection-multi';
console.log(d3.selection.prototype);
import rooms from '../rooms';

export default {
  name: 'page_solution',
  props: ["datas","size" ,"use_unit","use_power","use_mul"],
  data(){
    return {
      nodes: [{name: "電冰箱", value: 26.2},
               {name: "飲水機", value: 14.2},
               {name: "電熱水瓶", value: 12.4},
               {name: "冷氣機", value: 7.8},
               {name: "照明設備", value: 7.8},
               {name: "洗衣機", value: 5.2},
               {name: "電腦", value: 5.1}],
      nodes_summer:  [{name: "冷氣機", value: 43.1},
                     {name: "電冰箱", value: 16.9},
                     {name: "飲水機", value: 8.8},
                     {name: "電熱水瓶", value: 6.7},
                     {name: "照明設備", value: 5.0},
                     {name: "洗衣機", value: 5.2},
                     {name: "電腦", value: 5.1}],
      hash: parseInt(Math.random()*100000),
      width: 600,
      height: 500,
      unit: "%",
      power: 0.5,
      mul: 15,
    }; 
  },
  components: {
  },
  watch: {
    datas(){
       // console.log(this.datas.map((obj)=>obj.value));
       this.datas.forEach((obj)=>{
          this.nodes.forEach((obj2)=>{if (obj.name==obj2.name && obj.place==obj2.place && obj.name!="") obj2.value=obj.value});
        });
    },
    use_unit(){
      this.unit=this.use_unit;
    },
    use_power(){
      this.power=this.use_power;

    },
    use_mul(){
      this.mul=this.use_mul;
    }
  },
  mounted () {
    var vobj=this;
    this.width=this.size ? this.size.width: $("svg.graph_bubble[data-hash='"+this.hash+"']").outerWidth();
    this.height=this.size ? this.size.height:$("svg.graph_bubble[data-hash='"+this.hash+"']").outerHeight();
    this.mul=this.use_mul ? this.use_mul:this.mul;
    this.unit=this.use_unit ? this.use_unit:this.unit;
    this.power=this.use_power ? this.use_power:this.power;
    console.log(this.use_unit);

    if (this.datas) this.nodes=JSON.parse(JSON.stringify(this.datas));
    var nodes = this.nodes;
    // console.log(this.datas);
    var last=nodes.map(a=>a.value).reduce((a,b)=>(a+b));
    //推一些無用的小球進去
    for(var i=0;i<25;i++){
      nodes.push({name: "hash"+i, value: Math.random()*2+0.001});
    }

    //利用資料產生半徑，指數大小，亂數指定初始位置
    nodes.map((d,i)=>{
      d.r=Math.pow(d.value,vobj.power)*vobj.mul;
      d.x=Math.random()*200;
      d.y=Math.random()*200;
      return d;
    });


    //設定模擬(v4)
    var simulation = d3.forceSimulation(nodes)
        //設定碰撞與半徑
        .force("collide", d3.forceCollide().radius(function(d) { return d.r + 3; }).iterations(5))
        //設定中心
        .force("center", d3.forceCenter(this.width/2,this.height/2))
        //本身有排斥力
        .force("charge", d3.forceManyBody()) 
        //往中心的力量
        .force("y", d3.forceY(5))
        .force("x", d3.forceX(5))
        //隨時間更新
        .on("tick", ticked)
    ;

    //設定svg畫布
    var svg=d3.select("svg.graph_bubble[data-hash='"+this.hash+"']");

    //attrs 一次設定多個屬性（要多引入js庫）

    svg.attrs({
      width: this.width,
      height: this.height
    });


    //填色圓形
    var cir=svg.selectAll("circle.fill")
      .data(nodes,(d,i)=>d.name+d.place).enter().append("circle");
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
                console.log(d3.select(this).attr("data-name"));
                vobj.$emit("set_device",d3.select(this).attr("data-name"));
                
              })
              .on("drag",function(){
                //更新位置
                  nodes[d3.select(this).attr("dataid")].x=d3.event.x;
                  nodes[d3.select(this).attr("dataid")].y=d3.event.y;

              }).on("end",function(){
                //結束
                 // nodes[d3.select(this).attr("dataid")].value+=5;   
              });

      //一些固定的屬性

      text
      .attr("text-anchor", "middle")
      .attr("alignment-baseline","middle")
      .attr("fill","#515050")
      .style("user-select","none")
      .style("font-size","14px")
      .style("letter-spacing","2px");

      text_percent
      .attr("text-anchor", "middle")
      .attr("alignment-baseline","middle")
      .style("font-size","16px")
      .style("user-select","none")
      .style("letter-spacing","2px");

      cir
      .attr("class","fill")
      .call(drag)
      .attr("data-name",(d)=>d.name)
      .on("mouseover", handleMouseOver)
      .on("mouseout", handleMouseOut);

      cir_stroke
      .attrs({
        stroke: "#515050",
        fill: "none",
      });

    // cir.exit().remove();

    //維持計算 並不斷重新更新collide
    setInterval(function(){
      simulation
        .force("collide", d3.forceCollide()
        .radius(function(d) { return d.r + 3; }).iterations(5))
        .alphaTarget(0.3).restart();
    },100);

    //更新
    function ticked(){
      //根據比例更新半徑
      nodes.forEach((d,i)=>{
        var target_r=Math.pow(d.value,vobj.power)*vobj.mul;
        d.r+=(target_r-d.r)*0.025;
        if (d.r<=0) d.r=0.01;
      });
      
        //預先設定位置跟填色，還有select用的dataid
      cir.attrs({
        cx: function(d){return d.x},
        cy: function(d){return d.y},
        r: function(d){return d.r},
        fill: (d)=>{
          return color(d.r);
        },
        dataid: (d,i)=>(i)
      })

      cir.transition().duration(100)
        .attr("fill", (d,i)=>(color(d.r))
      );
      
      //預設線框，透明度動態控制
      cir_stroke.attrs({
        cx: function(d){return d.x+5},
        cy: function(d){return d.y+5},
        r: function(d){return d.r},
        opacity: (d)=>(d.value>5?1:0)
      });
      
      //設定文字樣式
      text.text((d)=>d.name.indexOf("hash")!=-1?"":d.name)
          .attrs({
            x: (d)=>(d.x),
            y: (d)=>(d.y),
          })
      .style("opacity",(d)=>d.value==0?0:1)

      
      text_percent  
        .attrs({
          x: (d)=>(d.x),
          y: (d)=>(d.y+20),
        })
        .text((d)=> ( (parseInt(d.value)>2)?((parseFloat(d.value) ).toFixed(vobj.unit=="%"?1:0)+vobj.unit):"") )
    }

    var ss = new Audio("http://awiclass.monoame.com/%E5%8B%95%E6%85%8B%E5%9C%96%E8%A1%A8%E9%9F%B3%E6%95%88/%E6%BB%91%E9%BC%A0%E7%A7%BB%E9%80%B2%E7%90%83.mp3");
    ss.volume=0.3;
    function handleMouseOver(d, i) {  
      // console.log("over");
      d.r+=5;
      ss.currentTime=0;
      ss.play();
      
    }
    function handleMouseOut(d, i) { 
      // console.log("out");
    }
  },
  computed: {...mapState(['device_result'])},
  methods: {...mapMutations([]),
  
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">
circle
  cursor: pointer

</style>
