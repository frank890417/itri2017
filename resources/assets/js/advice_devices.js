import ed from "./energylabel/cata_冷氣機.js"
import cata_ice from "./energylabel/cata_電冰箱.js"
import cata_tv from "./energylabel/cata_電視機.js"
import cata_hotpot from "./energylabel/cata_電熱水瓶.js"
import cata_washing from "./energylabel/cata_洗衣機.js"
import cata_cooking from "./energylabel/cata_電鍋_電子鍋.js"
import cata_hot_water from "./energylabel/cata_貯備型電熱水器.js"
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
        type: ["年耗電量","消耗功率"][if_value(get_by_tag(o,"年耗電量"))?0:1],
        link: "http://www.energylabel.org.tw/purchasing/product/"+o.url
      }))
      .sort((a,b)=>(parseInt(a.comsumption)-parseInt(b.comsumption) ))

advice_devices["冰箱"]=
  cata_ice.map((o)=>({
        brand: if_value(get_by_tag(o,"廠牌名")),
        name: o.title,
        size: if_value(get_by_tag(o,"尺寸")), 
        comsumption: [if_value(get_by_tag(o,"年耗電量")),
                     if_value(get_by_tag(o,"消耗")),null].filter(o=>o)[0],
        type: ["年耗電量","消耗功率"][if_value(get_by_tag(o,"年耗電量"))?0:1],
        link: "http://www.energylabel.org.tw/purchasing/product/"+o.url
      }))
      .sort((a,b)=>(parseInt(a.comsumption)-parseInt(b.comsumption) ))

advice_devices["電視機"]=
  cata_tv.map((o)=>({
        brand: if_value(get_by_tag(o,"廠牌名")),
        name: o.title,
        size: if_value(get_by_tag(o,"尺寸")), 
        comsumption: [if_value(get_by_tag(o,"年耗電量")),
                     if_value(get_by_tag(o,"消耗")),null].filter(o=>o)[0],
        type: ["年耗電量","消耗功率"][if_value(get_by_tag(o,"年耗電量"))?0:1],
        link: "http://www.energylabel.org.tw/purchasing/product/"+o.url
      }))
      .sort((a,b)=>(parseInt(a.comsumption)-parseInt(b.comsumption) ))

advice_devices["照明"]=[]
advice_devices["電腦（桌上型）"]=[]
advice_devices["電熱水瓶"]=
  cata_hotpot.map((o)=>({
        brand: if_value(get_by_tag(o,"廠牌名")),
        name: o.title,
        size: if_value(get_by_tag(o,"標示容量(公升)"))+"公升", 
        comsumption: [if_value(get_by_tag(o,"年耗電量")),
                     if_value(get_by_tag(o,"消耗")),null].filter(o=>o)[0]+"度",
        type: ["年耗電量","消耗功率"][if_value(get_by_tag(o,"年耗電量"))?0:1],
        link: "http://www.energylabel.org.tw/purchasing/product/"+o.url
      }))
      .sort((a,b)=>(parseInt(a.comsumption)-parseInt(b.comsumption) ))
advice_devices["洗衣機"]=
  cata_washing.map((o)=>({
        brand: if_value(get_by_tag(o,"廠牌名")),
        name: o.title,
        size: if_value(get_by_tag(o,"尺寸")), 
        comsumption: [if_value(get_by_tag(o,"年耗電量")),
                     if_value(get_by_tag(o,"消耗")),null].filter(o=>o)[0],
        type: ["年耗電量","消耗功率"][if_value(get_by_tag(o,"年耗電量"))?0:1],
        link: "http://www.energylabel.org.tw/purchasing/product/"+o.url
      }))
      .sort((a,b)=>(parseInt(a.comsumption)-parseInt(b.comsumption) ))

advice_devices["電熱水器"]=
  cata_hot_water.map((o)=>({
        brand: if_value(get_by_tag(o,"廠牌名")),
        name: o.title,
        size: if_value(get_by_tag(o,"標示容量")), 
        comsumption: if_value(get_by_tag(o,"平均24小時累計之耗電量E24(kWh)") )+"(度/天)",
        type: "平均耗電量",
        link: "http://www.energylabel.org.tw/purchasing/product/"+o.url
      }))
      .sort((a,b)=>(parseInt(a.comsumption)-parseInt(b.comsumption) ))

advice_devices["電鍋"]=
  cata_cooking.map((o)=>({
        brand: if_value(get_by_tag(o,"廠牌名")),
        name: o.title,
        size: if_value(get_by_tag(o,"尺寸")), 
        comsumption: [if_value(get_by_tag(o,"年耗電量")),
                     if_value(get_by_tag(o,"消耗")),null].filter(o=>o)[0],
        type: ["年耗電量","消耗功率"][if_value(get_by_tag(o,"年耗電量"))?0:1],
        link: "http://www.energylabel.org.tw/purchasing/product/"+o.url
      }))
      .sort((a,b)=>(parseInt(a.comsumption)-parseInt(b.comsumption) ))


// "洗衣機", 
// "電熱水器",
// "電鍋",
export default advice_devices
// 1.      品牌
// 2.      型號
// 3.      尺寸(大小)
// 4.      年耗電量(若無則第二順位為功率)
