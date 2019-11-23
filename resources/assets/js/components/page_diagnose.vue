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
          h3 基本資料
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
                                     v-model="member_count",id="txt_member_count")
                  span.input_side_btn(@click="member_count++") +
            .row.form-group
              .col-sm-4
                label 坪數：
              .col-sm-8                
                .btn_with_side
                  span.input_side_btn(@click="area_size--") -
                  input.form-control(type="number",v-model="area_size")
                  span.input_side_btn(@click="area_size++") +
            .row.form-group
              .col-sm-4
                label 住宅種類
              .col-sm-8                
                .btn_group_inline
                  button.btn(:class="{active:building_type==type}",
                             @click="building_type=type",
                             v-for="type in ['公寓','大廈','透天屋','其他']") {{ type }}

            //.row
              .col-sm-4
                label(title="請填寫最近一期-兩個月之帳單電費")
                  span 近期用電
                  button_moreinfo(:msg="'帳單電費為兩個月一期'")
                  span  ({{summer=='true'?"夏月":"全年"}})：
              .col-sm-8
                .form-group
                  .btn_group_inline
                    button.btn(:class="{active:summer=='true'}",@click="summer='true'") 夏月
                    button.btn(:class="{active:summer=='false'}",@click="summer='false'") 全年
                .form-group
                  label 度數：
                    button_moreinfo(:msg="'請填入台電電費單上之用電度數'")
                  input.form-control(v-model="degree", type="number")
                .form-group
                  label 流動電費：
                    button_moreinfo(:msg="'請填入台電電費單上之電費'")
                  input.form-control(v-model="money", type="number")
                .form-group(v-if="debug")
                  pre {{general_infos}}
                  pre {{avg_standard}}
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
            {gate: 1400,price: 3.94,price_summer: 4.80},
            {gate: 2000,price: 4.6,price_summer: 5.66},
            {gate: 200000,price: 5.03,price_summer: 6.41},
          ],
      debounce: false,      
      member_count: 0,
      area_size: 0,
      sel_area: "北區",
      sel_county: -1,
      region_data,
      building_type: ""
    }; 
  },
  watch:{
    building_type(){
      this.update_general_infos();
    },
    sel_county(){
      this.update_general_infos();
    },
    member_count(){
      this.update_general_infos();
    },
    area_size (){
      this.set_area_size(this.area_size);
      this.update_general_infos();
            
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
      //return result;  
      this.update_general_infos();    
      //console.log("result.fixed3=",this.money);
    },
    money(){
        if (this.debounce) {
          this.debounce=false;
          return ;
        }
        this.update_general_infos();
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
    // this.update_general_infos();
    if(store.state.member_data.users_id != "0")
    {
      axios.get("/api/userdetails/"+store.state.member_data.users_id).then((res)=>
      {
        if (res.data != "") {
    	//this.degree=res.data.degree;
    	this.summer= res.data.summer;
    	this.money= res.data.money;
    	this.member_count= res.data.member_count;
    	this.area_size= res.data.area_size;
    	this.sel_area= this.region_data.filter(o=>o.name==res.data.county)[0].area;
    	this.sel_county= res.data.county;
      this.building_type = res.data.building_type;
    
      //console.log("sel_area:",this.sel_area);
  		this.update_general_infos();  
        }
      })  
    }  
    
  },
  computed: {
    ...mapState(['avg_house_data','loading','house_area_size','general_infos','debug']),
    //for debugging (正式的在page_share中)
    avg_standard(){
      let weather_north_area = "臺北市、新北市、宜蘭縣、基隆市、桃園縣、桃園市、新竹縣、新竹市、苗栗縣、連江縣、金門縣".split("、")
      let weather_middle_area = "台中市、彰化縣、南投縣、雲林縣、澎湖縣、花蓮縣".split("、")
      let weather_south_area = "嘉義市、嘉義縣、台南市、高雄市、屏東縣、台東縣".split("、")
      let getArea = (name)=> {
        if (weather_north_area.find(county=>county==name)){
          return "北部氣候區"
        }
        if (weather_middle_area.find(county=>county==name)){
          return "中部氣候區"
        }
        if (weather_south_area.find(county=>county==name)){
          return "南部氣候區"
        }
        return "全國"
      }
      let judgeCondition = (condition,value)=>{
        if (condition == "") return false
        let regex = /(\d{1,2})[\-\~]?(\d{1,2})?/gm;
        let result = regex.exec(condition)
        let min = 0
        let max = 0
        //- console.log(result)
        if (result.length==3){
          min = parseInt(result[1])
          max = parseInt(result[2])
        }else if (result.length==2){
          if (condition.indexOf("以上")!=-1){
            min = parseInt(result[1])
            max = 1000
          }
          if (condition.indexOf("以下")!=-1){
            min = 0
            max = parseInt(result[1])
          }
        }
        //- console.log(min,max,value,value >= min && value <= max)
        if (value >= min && value <= max){
          return true
        }else{
          return false
        }

      }
      let userArea = getArea(this.general_infos.county)

      let resultList = this.avg_house_data.filter((obj)=>{
        if (obj["居住地區"]){
          if (obj["居住地區"]!="全國" && obj["居住地區"]!=userArea){
            return false
          }
        }
        if (obj["人口數"]){
          if ( !judgeCondition( obj["人口數"],this.general_infos.member_count ) ){
            //- console.log("人口not match")
            return false
          }
        }
        if (obj["坪數"]){
          if ( !judgeCondition( obj["坪數"],this.general_infos.area_size ) ){
            return false
          }
        }
        if (obj["住宅類型"]){
          if (this.general_infos.building_type!=obj["住宅類型"]){
            return false
          }
        }
        return true
      })

      let feedback = ""
      if (this.general_infos.degree){
        if (this.general_infos.degree< parseInt(result["年平均用電度數"]) ){
          feedback="您的用電量低於平均用電量: 請繼續保持您的好用電習慣"
        }else if (this.general_infos.degree< 1.5* parseInt(result["年平均用電度數"]) ){
          feedback="您的用電量高於平均用電量但小於1.5倍平均用電量: 有不錯的用電習慣，再努力一點節電可以更好喔"
        }else {
          feedback="您的用電量高於1.5倍平均用電量: 建議可以多參考節電手法，檢視自己的用電習慣"
        }
      }

      return {
        count: resultList.length,
        area: userArea,
        result: resultList[0],
        resultList: resultList,
        feedback
      }
    }
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
        money: this.money,
        building_type: this.building_type
      });
    },    
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">


</style>

