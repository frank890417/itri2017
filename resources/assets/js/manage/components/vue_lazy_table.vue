<template lang="pug">
  .power_table
    div(v-if="conf.show_search")
      .form-group-inline
        label Search: &nbsp;
        input(v-model="search_keyword", :placeholder="'共 '+sorted_data.length+' 筆結果'")
        .btn.btn-primary.pull-right(@click="export_csv") 匯出csv
      //- pre {{rowFilters}}
    table.table.table-hover
      thead
        th(v-for = "row_key in (row_keys || default_row_keys)",
           @click = "set_sort_key(row_key)",
           v-if = "row_name_alias(row_key)!='__hide'",)
          | {{ row_name_alias(row_key) }}
          span(v-if="row_key==sort_key && sort_direction") ▼
          span(v-if="row_key==sort_key && !sort_direction") ▲
          span(v-if="row_key!=sort_key ") 　
          br
          span
            span(v-for="(filter,fid) in (rowFilters[row_key] || [])",
                style="display: flex")
              select(v-model="filter.operator")
                option(v-for="op in operators", :value="op") {{op.symbol}}
              //- pre {{filter.operator}}
              input(v-model="filter.target", @keyup = "$forceUpdate();", style="flex: 1;width: calc(100% - 50px);flex-grow: 0.3;display: inline-block;min-width: 50px;")
              button(@click="rowFilters[row_key].splice(fid,1);$forceUpdate();") x
            div
              i.fa.fa-filter(@click="addFilter(row_key)", title="新增篩選條件")

          //- input(v-model="rowFilters[row_key]" style="width: 100%",
          //-       placeholder="" )
          
      tbody
        tr(v-for="(row,rid) in sliced_data", :key="rid")
          td(v-for = "row_key in (row_keys || default_row_keys)",
             v-if = "row_name_alias(row_key)!='__hide'")
            | {{ row[row_key] }}
          td
            .btn.btn-default(@click="edit(row)",
                             v-if="edit",
                             v-text="btntext || '編輯'")
            //.btn.btn-danger 刪除
    .page_nav
      .btn.btn-default(v-if="pages.length>1",
                       v-for="p in pages",
                       :class="{active: page==p}",
                       @click="page=p") {{p}}
</template>

<script>
import Vue from 'vue'
// sorted -> sliced
export default {
  name: 'vue_lazy_table',
  props: ["table_data","row_keys","rows","configs","edit","dataTitle","btntext"],
  data () {
    return {
      sort_key: null,
      sort_direction: true,
      operators: [{
        symbol: "=", 
        func: (a,b)=>a==b
      },{
        symbol: "<",
        func: (a,b)=>1*a<1*b
      },{
        symbol: ">",
        func: (a,b)=>1*a>1*b
      },{
        symbol: ">=",
        func: (a,b)=>1*a>=1*b
      },{
        symbol: "<=",
        func: (a,b)=>1*a<=1*b
      },{
        symbol: "⊃",
        func: (a,b)=>(a+"").indexOf(b)!=-1
      },{
        symbol: "≠",
        func: (a,b)=>a!=b
      }],
      conf: {
        show_id: true,
        show_search: true
      },
      search_keyword: "",
      page_split_num: 10,
      page: 1,
      rowFilters: {},
    }
  },
  watch:{
  },
  mounted(){
    this.conf = Object.assign(this.conf,this.configs)
    console.log(this.configs)
  },
  computed: {
    default_row_keys(){
      //使用reduce 過濾初步重複欄位名稱（預設抓資料）
      let row_keys = Array.from(this.table_data).concat({}).reduce(
        (keys,data_row)=>keys.concat(Object.keys(data_row)),[]
      ).filter(
        (d,i,arr)=>arr.indexOf(d)==i
      )

      if (this.conf.show_id && row_keys.indexOf("id")==-1 ){
        row_keys.unshift("id")
      }

      return row_keys
    },
    sorted_data(){
      let data_clone = JSON.parse(JSON.stringify(this.table_data))

      //filter original data
      if (this.rowFilters){
        Object.keys(this.rowFilters).filter(key=>this.rowFilters[key]!="").forEach(key=>{
            data_clone=data_clone.filter(item=>{
              let flag = true
              console.log(this.rowFilters[key])
              this.rowFilters[key].forEach(condition=>{
                
                if (condition.target=="" || !condition.operator  || !condition.operator.func){
                  flag = true
                  return
                }
                if (!condition.operator.func(item[key],condition.target) ){
                  flag = false
                }
              })
              // .map(item=>item).replace(/\$/g, item[key]  )
              // console.log(cond)
              // let result = true
              // try{
              //   result = eval(cond)  
              // }catch(e){
              //   result = false
              // }
              return flag
            })

        })
      }



      let handlers = this.default_row_keys.map(key=>({key,handler: this.get_key_handler(key)}) )
      console.log(handlers)
      //if handler of key exist , compute handled value
      data_clone.forEach( (obj)=>{
        this.default_row_keys.forEach( (key)=>{
          let computed_handler= handlers.find(o=>o.key==key).handler
          if (computed_handler){
            obj[key]=computed_handler(obj[key])
          }
        })
      })

      if (data_clone){
        // add id col
        if (this.conf.show_id){data_clone.forEach( (o,id)=> {
            if (!o.id) { o.id = id}
          }
        )}

      
        //依照sortkey 排序
        let raw_sort = this.sort_key?data_clone.sort(
          (a,b)=>{
            var [var_a,var_b]= [a[this.sort_key],b[this.sort_key]]
            if (!isNaN(var_a))
              return parseFloat(var_a)< parseFloat(var_b)?1:-1
            else
              return var_a<var_b?1:-1
          }
        ):data_clone
        //search by content
        raw_sort=this.search_keyword==""?raw_sort:raw_sort.filter((o)=>Object.values(o).reduce((a,b)=>(a||(b+"").indexOf(this.search_keyword)!=-1),false)
        )

        return raw_sort
      }else{
        return []
      }
      
    },
    sliced_data(){
      let raw_sort = this.sorted_data.slice()
      let slice_pre = (this.sort_direction?raw_sort:raw_sort.reverse());
      let slice_post = slice_pre.slice( (this.page-1)*this.page_split_num,(this.page)*this.page_split_num )
      
      // slice_post
      return slice_post
    },
    pages(){
      return Array.from( {length: Math.ceil(1.0*this.sorted_data.length / this.page_split_num) } , (d,i)=>i+1)
    },
    parse_items_list(){
      let list = this.rows?Array.from(this.rows):[];
      //可以用string "name | as" 也可以 {name: "name..",as: "as.."}
      let parse_items_list = 
        list.slice().map(o=>{
          if (typeof o == "string"){
            return {
              name: o.split(" -> ")[0],
              as: o.split(" -> ")[1].split(' | ')[0],
              arg: (o.split(" -> ")[1].split(' | ')[1]||"").split(",")
            }
          }else if  (typeof o == "object"){
            return Object.assign({
              as: null,
              arg: []
            },o)
          }
        })
      return parse_items_list
    }
  },
  methods: { 
    addFilter(key){

      if (!this.rowFilters[key]){
        this.$set(this.rowFilters,key,[])
      }
      this.rowFilters[key].push({operator: this.operators.find(op=>op.symbol=="="),target: ""})
    },

    //取得表格欄位的暱稱
    row_name_alias(row_name){
      let parse_items_list = this.parse_items_list
      //尋找每一行的別名
      var find_row = parse_items_list.find(o=>o.name==row_name)
      // console.log(list,find_row)
      if (find_row){
        return find_row.arg.find(o=>o.indexOf("hide")!=-1)?"__hide":(find_row.as || row_name)
      }
      return row_name
    },
    set_sort_key(key){
      if (this.sort_key!=key){
      }else{
        this.sort_direction=!this.sort_direction
        
      }
      this.sort_key=key
    },
    get_key_handler(key){
      let key_obj = this.parse_items_list.find(o=>o.name==key)
      return key_obj?key_obj.handler:null
    },
    export_csv(){
      
      let convertToCSV = function convertToCSV(objArray) {
          var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
          var str = '';

          for (var i = 0; i < array.length; i++) {
              var line = '';
              for (var index in array[i]) {
                  if (line != '') line += ','
                  let hasNL = (""+array[i][index]).indexOf("\n")>-1
                  if (hasNL) line+='"'
                  line += array[i][index];
                  if (hasNL) line+='"'
              }

              str += line + '\r\n';
          }

          return str;
      }

      let exportCSVFile = function exportCSVFile(headers, items, fileTitle) {
          if (headers) {
              items.unshift(headers);
          }

          // Convert Object to JSON
          var jsonObject = JSON.stringify(items);

          var universalBOM = "\uFEFF";
          var csv = convertToCSV(jsonObject);

          var exportedFilenmae = fileTitle + '.csv' || 'export.csv';

          var blob = new Blob(["\ufeff",csv], { type: 'text/csv;charset=utf-8;' });
          if (navigator.msSaveBlob) { // IE 10+
              navigator.msSaveBlob(blob, exportedFilenmae);
          } else {
              var link = document.createElement("a");
              if (link.download !== undefined) { // feature detection
                  // Browsers that support HTML5 download attribute
                  var url = URL.createObjectURL(blob);
                  link.setAttribute("href", url);
                  link.setAttribute("download", exportedFilenmae);
                  link.style.visibility = 'hidden';
                  document.body.appendChild(link);
                  link.click();
                  document.body.removeChild(link);
              }
          }
      }

      let rkey = this.row_keys || this.default_row_keys;
      let headers = {};
      rkey.forEach(o=>headers[o]=o)

      var fileTitle = this.dataTitle || "資料匯出"; // or 'my-unique-title'
      let dateString = ( new Date().toLocaleDateString()).replace(/[\/\s\:]/g,"");
      exportCSVFile(headers, this.sorted_data, fileTitle + dateString); 

    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">
.power_table
  *
    // border: solid 1px black
  th
    cursor: pointer
    user-select: none
    padding: 10px
    vertical-align: top

  .fade-enter-active, .fade-leave-active 
    transition: opacity .5s

  .fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ 
    opacity: 0
  i.fa.fa-filter


    opacity: 0.7
    &:hover
      transform: scale(1.1)
      opacity: 1


</style>
