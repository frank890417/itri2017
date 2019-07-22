<template lang="pug">
section.manage_comparedevice.container-fluid
  .row.mb-3
    .col-sm-12
      h1 其他頁面資訊編輯

  .row
    .col-sm-12
      .panel.panel-primary
        .panel-heading 資訊編輯

        .panel-body(@keyup="updateJsonEditor", v-show="nowEditingPage")

          .row
            .col-sm-4
              h3 編輯區塊
              ul.list-group
                	
                li.list-group-item(v-for="page in pages",
                                   @click="setEditingPage(page)",
                                   :class="{active: page===nowEditingPage}") {{ getSectionTitle(page.title) }}
              el-button.mt-3.w100(@click="savePageData(nowEditingPage)",
                       type="danger") 儲存資料變更

            .col-sm-8
              #jsoneditor.mt-2
      

</template>


<script>
export default {
  mounted(){
    var container = document.getElementById("jsoneditor");
    this.editor = new JSONEditor(container);
    
  },
  methods:{
    updateJsonEditor(){
      this.$set(this.nowEditingPage,"content",this.editor.get())
      console.log("json updated")
    },
    savePageData(page){
      this.$set(this.nowEditingPage,"content",this.editor.get())
      console.log(page)
      let pageClone = JSON.parse(JSON.stringify(page))
      pageClone.content = JSON.stringify(pageClone.content)

      axios.post("/api/page/"+page.title,{
        _method: "PATCH",
        ...pageClone
      }).then((res)=>{
        // this.setEvent(res.data)
        this.$message.success("Save Success!")
      })
    },
    setEditingPage(page){
      this.$set(this,"nowEditingPage",page);
      this.editor.set(this.nowEditingPage.content);
      this.editor.expandAll();
    },
    getSectionTitle(key){
      let list = {
        comparedevice: "比較電器",
        embedsection: "嵌入區塊"
      }
      return list[key] || key
    }
  },
  data(){
    return {
      editor: null,
      nowEditingPage: null,
      pages: []
      
    }
  },
  created(){
    axios.get("/api/page/").then((res)=>{
      this.$set(this,"pages",res.data)
      this.pages.forEach(page=>{
        page.content = page.content ?JSON.parse(page.content):{}
      })
      this.setEditingPage(this.pages.find(p=>p.title=="embedsection"))
    })
  }
}
</script>
