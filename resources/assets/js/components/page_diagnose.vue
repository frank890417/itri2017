<template lang="pug">
  section.section_diagnose.yellow
    .container
      .row
        .col-sm-12
          h4 「 讓我們一起抓出家裡的吃電怪獸吧！ 」
          h1.title DIAGNOSE
            span.chinese 電器診斷
      .row
        .col-sm-12
          h2 基本資料
        .col-sm-6
          ul.nav.nav-tabs
            li.active 
              a 北區
            li 
              a 中區
            li 
              a 東區
            li 
              a 南區
          ul.panel.bar_list
            li 臺北市
            li 新北市
            li 基隆市
            li 宜蘭縣
            li 桃園市
            li 新竹縣
            li 新竹市
        .col-sm-6
          .form-group
            .row.form-group
              .col-sm-4
                label 家庭成員：
              .col-sm-8
                input.form-control(type="number",v-model="member_count")
            .row.form-group
              .col-sm-4
                label 坪數({{area_size}})：
              .col-sm-8
                input.form-control(type="number",v-model="area_size")
            .row
              .col-sm-4
                label 近期用電度數({{summer=='true'?"夏月":"非夏月"}})：
              .col-sm-8
                .form-group
                  .btn_group_inline
                    button.btn(:class="{active:summer=='true'}",@click="summer='true'") 夏月
                    button.btn(:class="{active:summer=='false'}",@click="summer='false'") 非夏月
                 
                .form-group
                  label 度數：
                  input.form-control(v-model="degree")
                .form-group
                  label 電費：
                  input.form-control(v-model="money")
            //.row
              .col-sm-4
                label 換算年用電
              .col-sm-8
                  span(v-if="summer=='true'")  
                    h4 夏月({{money}}) * 2 + 非夏月({{money}} / 1.37) * 4
                    h2 {{(parseInt(money)*2+money/1.37*4).toFixed(3)}}
                  span(v-else) 
                    h4 夏月({{money}} * 1.37) * 2 + 非夏月({{money}} ) * 4
                    h2 {{(money*1.37*2+money*4).toFixed(3) }}

</template>


<script>
import {mapState,mapMutations} from 'vuex' 
export default {
  name: 'page_index',
  data(){
    return {
      degree: 0,
      summer: "false",
      money: 0,
      gates: [
            {gate: 240,price: 1.63,price_summer: 1.63},
            {gate: 660,price: 2.10,price_summer: 2.38},
            {gate: 1000,price: 2.89,price_summer: 3.52},
            {gate: 1400,price: 3.79,price_summer: 4.61},
            {gate: 2000,price: 4.42,price_summer: 5.42},
            {gate: 200000,price: 4.83,price_summer: 6.13},
          ],
      debounce: false,
      member_count: 3,
      area_size: 15
    }; 
  },
  watch:{
    area_size (){
      this.set_area_size(this.area_size);
    },
    summer(){
      this.degree=0;
    },
    degree(){
      if (this.debounce) {
        this.debounce=false;
        return ;
      }
      var result=0;
        var summer_mode=(this.summer=="true");
        var gates=JSON.parse(JSON.stringify(this.gates));
        var last_total=0;
        var lastgate={gate: 0,price:0,price_summer: 0,sub_total: 0,sub_total_summer: 0};
        gates.map((obj)=>{
          obj.sub_total=lastgate.sub_total + obj.price*(obj.gate-lastgate.gate);
          obj.sub_total_summer=lastgate.sub_total_summer + obj.price_summer*(obj.gate-lastgate.gate);
          lastgate=obj;
          return obj;
        })
        var endflag=false;
        var lastgate={gate: 0,price:0,price_summer: 0,sub_total: 0,sub_total_summer: 0};
        gates.reverse().forEach((gate)=>{
          var use_price=summer_mode ? gate.price_summer:gate.price;
          var use_total=summer_mode ? gate.sub_total_summer:gate.sub_total;
          var use_last_price=summer_mode ? lastgate.price_summer:lastgate.price;
          if(!endflag){
            if (this.degree>gate.gate){
              result+=use_total;
              result+=use_last_price*(this.degree-gate.gate);
              endflag = true;
              console.log(result);
            }else if (gate.gate == 240){
              console.log(gate);
              result+= use_price*this.degree;
            }
            
          
          }
          lastgate=gate;
        });
      this.debounce=true;
      this.money = result.toFixed(3);
      
    },
    money(){
        if (this.debounce) {
          this.debounce=false;
          return ;
        }
        var result=0;
        var summer_mode=(this.summer=="true");
        var gates=JSON.parse(JSON.stringify(this.gates));
        var last_total=0;
        var lastgate={gate: 0,price:0,price_summer: 0,sub_total: 0,sub_total_summer: 0};
        gates.map((obj)=>{
          obj.sub_total=lastgate.sub_total + obj.price*(obj.gate-lastgate.gate);
          obj.sub_total_summer=lastgate.sub_total_summer + obj.price_summer*(obj.gate-lastgate.gate);
          lastgate=obj;
          return obj;
        })
        
        
        var endflag=false;
        var lastgate={gate: 0,price:0,price_summer: 0};
        gates.reverse().forEach((gate)=>{
          var use_price=summer_mode ? gate.price_summer:gate.price;
          var use_total=summer_mode ? gate.sub_total_summer:gate.sub_total;
          var use_last_price=summer_mode ? lastgate.price_summer:lastgate.price;
          if(!endflag){
            if (this.money>use_total){
              result+=gate.gate;
              result+= (this.money-use_total)/use_last_price;
              endflag = true;
              console.log(gate);
            }else if (gate.gate == 240){
              console.log(gate);
              result+= this.money / use_price;
            }
            
          
          }
          lastgate=gate;
        });
        this.debounce=true;
        this.degree=result.toFixed(1);
        return result;
    }
  },
  components: {
  },
  mounted (){
    console.log("diagnose mounted");
    
  },
  computed: {...mapState(['loading','house_area_size'])},
  methods: {...mapMutations(['set_loading','set_area_size'])}
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>

