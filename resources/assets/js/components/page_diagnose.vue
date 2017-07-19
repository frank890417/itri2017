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
          h2 第一階段 / 基本資料
          button_moreinfo(:msg="'來跟全國均用電比較一下吧！'")
          br
          
        //地區選單
        .col-sm-6
          .btn_group_inline
            button.btn(:class="{active:sel_area=='北區'}",@click="sel_area='北區'") 北區
            button.btn(:class="{active:sel_area=='中區'}",@click="sel_area='中區'") 中區
            button.btn(:class="{active:sel_area=='東區'}",@click="sel_area='東區'") 東區
            button.btn(:class="{active:sel_area=='南區'}",@click="sel_area='南區'") 南區
            button.btn(:class="{active:sel_area=='離島'}",@click="sel_area='離島'") 離島
          ul.panel.bar_list
            li(v-for = "county in get_area(sel_area)",
               :class = "{active: county.name == sel_county}",
               @click = "sel_county = county.name") {{county.name}}

        .col-sm-6
          .form-group
            .row.form-group
              .col-sm-4
                label 家庭成員：
              .col-sm-8
                .btn_with_side
                  span.input_side_btn(@click="member_count--") -
                  input.form-control(type="number",
                                     v-model="member_count")
                  span.input_side_btn(@click="member_count++") +
            .row.form-group
              .col-sm-4
                label 坪數：
              .col-sm-8                
                .btn_with_side
                  span.input_side_btn(@click="area_size--") -
                  input.form-control(type="number",v-model="area_size")
                  span.input_side_btn(@click="area_size++") +

            .row
              .col-sm-4
                label(title="請填寫最近一期-兩個月之帳單電費")
                  span 近期用電
                  button_moreinfo(:msg="'帳單電費為兩個月一期'")
                  span  ({{summer=='true'?"夏月":"非夏月"}})：

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
import region_data from '../region_data'
import button_moreinfo from './button_moreinfo'
export default {
  name: 'page_diagnose',
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
      area_size: 15,
      sel_area: "北區",
      sel_county: -1,
      region_data
    }; 
  },
  watch:{
    sel_county(){
      this.update_general_infos();
    },
    member_count(){
      this.update_general_infos();
    },
    area_size (){
      this.set_area_size(this.area_size);
            
    },
    summer(){
      this.degree=0;
      this.update_general_infos();
    },
    degree(){
      this.set_user_degree(this.degree)
      this.update_general_infos();
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
    button_moreinfo
  },
  mounted (){
    console.log("diagnose mounted");
    
  },
  computed: {
    ...mapState(['loading','house_area_size','general_infos']),
    
  },
  methods: {
    ...mapMutations(['set_loading','set_area_size','set_user_degree','set_general_infos']),
    get_area(area){
      return this.region_data.filter(o=>o.area==area)
    },
    update_general_infos(){
      console.log("update_general_infos");
      this.set_general_infos({
        county: this.sel_county,
        member_count: this.member_count,
        area_size: this.area_size,
        summer: this.summer,
        degree: this.degree,
        money: this.money
      });
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>

