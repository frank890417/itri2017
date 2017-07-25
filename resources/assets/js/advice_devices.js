import ed from "./energylabel/cata_冷氣機.js"
import cata_ice from "./savepowercrawl/cata_電冰箱.js"
import cata_tv from "./energylabel/cata_電視機.js"
import cata_hotpot from "./savepowercrawl/cata_電熱水瓶.js"
import cata_washing from "./energylabel/cata_洗衣機.js"
import cata_cooking from "./energylabel/cata_電鍋_電子鍋.js"
import cata_hot_water from "./savepowercrawl/cata_貯備型電熱水器.js"
import cata_computer from "./energylabel/cata_桌上型電腦.js"

import cata_ac_1 from "./savepowercrawl/cata_分離式冷氣機.js"
import cata_ac_2 from "./savepowercrawl/cata_窗(壁)型及箱型冷氣機.js"

// console.log("test電扇",ed)

var if_value = (o)=>((o)?o.content:null)
var get_by_tag = (arr,tag) => (arr.infos.find((info)=>(info.label.indexOf(tag)!=-1)))

var advice_devices ={};

advice_devices["冷氣機"]=
  cata_ac_1.concat(cata_ac_2)
    .map((o)=>({
      infos: [
        {
          label: "廠牌名稱",
          content: if_value(get_by_tag(o,"標示義務公司"))
        },{
          label: "產品型號",
          content: if_value(get_by_tag(o,"產品型號"))
        },{
          label: "額定總冷氣能力",
          content: if_value(get_by_tag(o,"額定總冷氣能力"))
        },{
          label: "年耗電量",
          content: if_value(get_by_tag(o,"年耗電量"))
        }
      ],
      comsumption: (if_value(get_by_tag(o,"額定總冷氣能力")) || "100000").replace("kW",""),
      link: "https://ranking.energylabel.org.tw/_outweb/product/Approval/"+o.url
      
    }
    ))
    .sort((a,b)=>(parseFloat(a.comsumption)-parseFloat(b.comsumption) ))
    .sort((a,b)=>(parseFloat(a.comsumption)-parseFloat(b.comsumption) ))

advice_devices["冰箱"]=
  cata_ice
    .map((o)=>({
      infos: [
        {
          label: "廠牌名稱",
          content: if_value(get_by_tag(o,"標示義務公司"))
        },{
          label: "產品型號",
          content: if_value(get_by_tag(o,"產品型號"))
        },{
          label: "有效內容積",
          content: if_value(get_by_tag(o,"有效內容積"))
        },{
          label: "年耗電量",
          content: if_value(get_by_tag(o,"年耗電量"))
        }
      ],
      size: if_value(get_by_tag(o,"有效內容積")) || 100000,
      link: "https://ranking.energylabel.org.tw/_outweb/product/Approval/"+o.url
      
    } 
    ))
      .sort((a,b)=>(parseInt(a.size)-parseInt(b.size) ))

advice_devices["電視"]=
  cata_tv
    .map((o)=>({
      infos: [
        {
          label: "廠牌名稱",
          content: if_value(get_by_tag(o,"廠牌名"))
        },{
          label: "產品型號",
          content: if_value(get_by_tag(o,"型號"))
        },{
          label: "規格（吋）",
          content: if_value(get_by_tag(o,"規格(吋)"))
        },{
          label: "待機消耗電力",
          content: if_value(get_by_tag(o,"待機消耗電力"))
        }
      ],
      comsumption: if_value(get_by_tag(o,"規格(吋)")) || 100000,
      link: "http://www.energylabel.org.tw/purchasing/product/"+o.url
      
    }
    ))
      .sort((a,b)=>(parseFloat(a.comsumption)-parseFloat(b.comsumption) ))

advice_devices["照明"]=[]

advice_devices["電熱水瓶"]=
  cata_hotpot
    .map((o)=>({
      infos: [
        {
          label: "廠牌名稱",
          content: if_value(get_by_tag(o,"標示義務公司"))
        },{
          label: "產品型號",
          content: if_value(get_by_tag(o,"產品型號"))
        },{
          label: "有效內容積",
          content: if_value(get_by_tag(o,"有效內容積"))
        },{
          label: "年耗電量",
          content: if_value(get_by_tag(o,"年耗電量"))
        }
      ],
      size: if_value(get_by_tag(o,"有效內容積")) || 100000,
      link: "https://ranking.energylabel.org.tw/_outweb/product/Approval/"+o.url
      
    } 
    ))
      .sort((a,b)=>(parseFloat(a.size)-parseFloat(b.size) ))
      
advice_devices["洗衣機"]=
  cata_washing.map((o)=>({
      infos: [
        {
          label: "廠牌名",
          content: if_value(get_by_tag(o,"廠牌名"))
        },{
          label: "型號",
          content: if_value(get_by_tag(o,"型號"))
        },{
          label: "額定容量",
          content: if_value(get_by_tag(o,"額定容量(Kg)"))
        },{
          label: "年耗電量",
          content: if_value(get_by_tag(o,"年耗電量"))
        }
      ],
      comsumption: if_value(get_by_tag(o,"額定容量(Kg)")) || 10000,
      link: "http://www.energylabel.org.tw/purchasing/product/"+o.url
      
    }
    ))
      .sort((a,b)=>(parseFloat(b.comsumption)-parseFloat(a.comsumption) ))

advice_devices["電熱水器"]=
  cata_hot_water
    .map((o)=>({
      infos: [
        {
          label: "廠牌名稱",
          content: if_value(get_by_tag(o,"標示義務公司"))
        },{
          label: "產品型號",
          content: if_value(get_by_tag(o,"產品型號"))
        },{
          label: "內桶容量",
          content: if_value(get_by_tag(o,"內桶容量"))
        },{
          label: "年耗電量",
          content: if_value(get_by_tag(o,"年耗電量"))
        }
      ],
      size: if_value(get_by_tag(o,"內桶容量")) || 100000,
      link: "https://ranking.energylabel.org.tw/_outweb/product/Approval/"+o.url
      
    } 
    ))
      .sort((a,b)=>(parseInt(a.size)-parseInt(b.size) ))

advice_devices["電鍋"]=
  cata_cooking.map((o)=>({
      infos: [
        {
          label: "廠牌名稱",
          content: if_value(get_by_tag(o,"廠牌名"))
        },{
          label: "型號",
          content: if_value(get_by_tag(o,"型號"))
        }
      ],
      comsumption: if_value(get_by_tag(o,"型號")) || 10000,
      link: "http://www.energylabel.org.tw/purchasing/product/"+o.url
      
    }
    ))
      .sort((a,b)=>(parseInt(b.comsumption)-parseInt(a.comsumption) ))

advice_devices["電腦（桌上型）"]=
  cata_computer.map((o)=>({
      infos: [
        {
          label: "廠牌名稱",
          content: if_value(get_by_tag(o,"廠牌名"))
        },{
          label: "型號",
          content: if_value(get_by_tag(o,"型號"))
        },{
          label: "核心處理器",
          content: if_value(get_by_tag(o,"核心處理器"))
        },{
          label: "實測典型能源消耗量",
          content: if_value(get_by_tag(o,"實測典型能源消耗量"))
        }
      ],
      comsumption: if_value(get_by_tag(o,"核心處理器")) || -1000,
      link: "http://www.energylabel.org.tw/purchasing/product/"+o.url
      
    }
    ))
      .sort((a,b)=>(parseFloat(b.comsumption)-parseFloat(a.comsumption) ))

var advice_catas = [{"value":0,"name":"全部"},{"value":1,"name":"冷氣機"},{"value":59,"name":"無風管空調機"},{"value":8,"name":"電扇"},{"value":5,"name":"除濕機"},{"value":4,"name":"電冰箱"},{"value":7,"name":"電視機"},{"value":9,"name":"螢光燈管"},{"value":6,"name":"洗衣機"},{"value":2,"name":"乾衣機"},{"value":10,"name":"吹風機"},{"value":11,"name":"烘手機"},{"value":12,"name":"溫熱型開飲機"},{"value":13,"name":"冰溫熱型開飲機"},{"value":14,"name":"冰溫熱型飲水機"},{"value":15,"name":"汽車"},{"value":16,"name":"機車"},{"value":17,"name":"安定器內藏式螢光燈泡"},{"value":20,"name":"顯示器"},{"value":19,"name":"燃氣台爐"},{"value":18,"name":"即熱式燃氣熱水器"},{"value":21,"name":"電鍋_電子鍋"},{"value":23,"name":"貯備型電熱水器"},{"value":22,"name":"電熱水瓶"},{"value":24,"name":"出口及避難指示燈"},{"value":25,"name":"DVD(錄)放影機"},{"value":26,"name":"溫熱型飲水機"},{"value":27,"name":"室內照明燈具"},{"value":28,"name":"組合音響"},{"value":29,"name":"緊密型螢光燈管"},{"value":32,"name":"影印機"},{"value":33,"name":"印表機"},{"value":34,"name":"空氣清淨機"},{"value":35,"name":"道路照明燈具"},{"value":41,"name":"浴室用通風電扇"},{"value":42,"name":"壁式通風電扇"},{"value":37,"name":"筆記型電腦"},{"value":36,"name":"桌上型電腦"},{"value":46,"name":"空氣源式熱泵熱水器"},{"value":47,"name":"排油煙機"},{"value":48,"name":"微波爐"},{"value":43,"name":"軸流式風機"},{"value":49,"name":"離心式風機"},{"value":50,"name":"螢光燈管用安定器"},{"value":51,"name":"電烤箱"},{"value":53,"name":"貯(儲)備型電開水機"},{"value":54,"name":"發光二極體燈泡"},{"value":56,"name":"LED平板燈"},{"value":57,"name":"在線式不斷電式電源供應器"},{"value":58,"name":"天井燈"},{"value":60,"name":"筒燈及嵌燈"}];


// "洗衣機", 
// "電熱水器",
// "電鍋",
window.advice_device_compiled = {advice_devices,advice_catas}
export default {advice_devices,advice_catas}
// 1.      品牌
// 2.      型號
// 3.      尺寸(大小)
// 4.      年耗電量(若無則第二順位為功率)
