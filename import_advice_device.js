var data =  require("./resources/assets/js/advice_devices_compiled.js")

//載入MySQL模組
var mysql = require('mysql');
//建立連線
var connection = mysql.createConnection({
    host: '127.0.0.1',
    user: 'root',
    password: '@##434frt))',
    database: 'itri2017'
});
//開始連接
connection.connect();
Object.keys(data.advice_devices).forEach(function(key){
  let currentCata = key
  let nowData = data.advice_devices[currentCata]
  nowData.forEach(d=>{
    let useData = {
      itemid: (d.infos.find(o => o.label == "產品型號") || {}).content,
      company: (d.infos.find(o=>o.label=="廠牌名稱") || {}).content,
      infos: d.infos,
      consumption: d.comsumption,
      size: d.size,
      link: d.link,
      cata: currentCata
    }
    // if (useData.itemid){
    //   useData.infos = useData.infos.filter(o => o.label !="產品型號")
    // }
    // if (useData.company) {
    //   useData.infos = useData.infos.filter(o => o.label != "廠牌名稱")
    // }

    Object.keys(useData).forEach(dk=>{
      if (typeof useData[dk]=='object'){
        useData[dk] = JSON.stringify(useData[dk])
      }
      if (typeof useData[dk]=="undefined"){
        delete useData[dk]
      }
    })
    connection.query('INSERT INTO `advice_devices` SET ?', useData, function(error){
          if(error){
            console.log('寫入資料失敗！', useData);
              throw error;
          }
      });
  })
})
connection.end();