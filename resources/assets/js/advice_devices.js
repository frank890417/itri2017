import ed from "./energylabel/cata_冷氣機.js"
import cata_ice from "./energylabel/cata_電冰箱.js"
// console.log("test電扇",ed)

var if_value = (o)=>((typeof o[0]!="undefined")?o[0].content:null)
var get_by_tag = (arr,tag) => (arr.infos.filter((info)=>(info.label.indexOf(tag)!=-1)))

var advice_devices ={};

advice_devices["冷氣機"]=
  ed.map((o)=>({
        brand: if_value(get_by_tag(o,"廠牌名")),
        name: o.title,
        size: if_value(get_by_tag(o,"尺寸")), 
        comsumption: [if_value(get_by_tag(o,"年耗電量")),
                     if_value(get_by_tag(o,"消耗")),null].filter(o=>o)[0],
        type: ["年耗電量","消耗功率"][if_value(get_by_tag(o,"年耗電量"))?0:1]
      }))
      .sort((a,b)=>(parseInt(a.comsumption)-parseInt(b.comsumption) ))
  
advice_devices["電冰箱"]=
  cata_ice.map((o)=>({
        brand: if_value(get_by_tag(o,"廠牌名")),
        name: o.title,
        size: if_value(get_by_tag(o,"尺寸")), 
        comsumption: [if_value(get_by_tag(o,"年耗電量")),
                     if_value(get_by_tag(o,"消耗")),null].filter(o=>o)[0],
        type: ["年耗電量","消耗功率"][if_value(get_by_tag(o,"年耗電量"))?0:1]
      }))
      .sort((a,b)=>(parseInt(a.comsumption)-parseInt(b.comsumption) ))
    
console.log(advice_devices["電冰箱"])

export default advice_devices
// 1.      品牌
// 2.      型號
// 3.      尺寸(大小)
// 4.      年耗電量(若無則第二順位為功率)
