<template lang="pug">
  .power_table
    label Search: 
    input(v-model="search_keyword")
    table.table.table-hover
      thead
        th(v-for = "row_key in (row_keys || default_row_keys)",
           @click = "set_sort_key(row_key)")
          | {{ row_name_alias(row_key) }}
          span(v-if="row_key==sort_key && sort_direction") ▼
          span(v-if="row_key==sort_key && !sort_direction") ▲
          span(v-if="row_key!=sort_key ") 　
      tbody
        tr(v-for="(row,rid) in sliced_data")
          td(v-for = "row_key in (row_keys || default_row_keys)")
            | {{ row[row_key] }}
    .page_nav
      .btn.btn-default(v-if="pages.length>1",
                       v-for="p in pages",
                       :class="{active: page==p}",
                       @click="page=p") {{p}}
</template>

<script>

// sorted -> sliced
export default {
  name: 'power_table',
  props: ["table_data","row_keys","rows"],
  data () {
    return {
      sort_key: null,
      sort_direction: true,
      show_id: true,
      search_keyword: "",
      page_split_num: 10,
      page: 1
    }
  },
  watch:{
    table_data(){
      


    }
  },
  mounted(){

  },
  computed: {
    default_row_keys(){
      //使用reduce 過濾初步重複欄位名稱（預設抓資料）
      let row_keys = Array.from(this.table_data).concat({}).reduce(
        (keys,data_row)=>keys.concat(Object.keys(data_row)),[]
      ).filter(
        (d,i,arr)=>arr.indexOf(d)==i
      )
      if (this.show_id && row_keys.indexOf("id")==-1 ){
        row_keys.unshift("id")
      }
      return row_keys
    },
    sorted_data(){
      let data_clone = JSON.parse(JSON.stringify(this.table_data))

      if (data_clone){
        //add id col
        if (this.show_id){data_clone.forEach( (o,id)=> {
            if (!o.id) { o.id = id+1}
          }
        )}

      
        //依照sortkey 排序
        let raw_sort = this.sort_key?data_clone.sort(
          (a,b)=>{
            var [var_a,var_b]= [a[this.sort_key],b[this.sort_key]]
            if (!isNaN(var_a))
              return parseFloat(var_a)< parseFloat(var_b)
            else
              return a[this.sort_key]<b[this.sort_key]
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
      let raw_sort = this.sorted_data
      let slice_pre = (this.sort_direction?raw_sort:raw_sort.reverse());
      let slice_post = slice_pre.slice( (this.page-1)*this.page_split_num,(this.page)*this.page_split_num )
      return slice_post
    },
    pages(){
      return Array.from( {length: Math.ceil(1.0*this.sorted_data.length / this.page_split_num) } , (d,i)=>i+1)
    }
  },
  methods: { 
    //取得表格欄位的暱稱
    row_name_alias(row_name){
      var list = Array.from(this.rows);
      //可以用string "name | as" 也可以 {name: "name..",as: "as.."}
      var parse_items_list = 
        list.slice().map(o=>{
          if (typeof o == "string"){
            return {
              name: o.split(" -> ")[0],
              as: o.split(" -> ")[1],
            }
          }else if  (typeof o == "object"){
            return o
          }
        })

      //尋找每一行的別名
      var find_row = parse_items_list.find(o=>o.name==row_name)
      // console.log(list,find_row)
      if (find_row){
        return find_row.as || row_name
      }
      return row_name
    },
    set_sort_key(key){
      if (this.sort_key!=key){
        this.sort_key=key
      }else{
        this.sort_direction=!this.sort_direction
      }
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="sass?indentedSyntax">
  *
    // border: solid 1px black
  th
    cursor: pointer
    user-select: none
    padding: 10px
</style>
