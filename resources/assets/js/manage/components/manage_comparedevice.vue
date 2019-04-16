<template lang="pug">
section.manage_comparedevice.container-fluid
  .row.mb-3
    .col-sm-12
      h1 一級節能電器編輯

  .row
    .col-sm-12
      .panel.panel-primary
        .panel-heading 資訊編輯

        .panel-body(@keyup="updateJsonEditor", v-show="nowEditingPage")

          .row
            .col-sm-4
              h3 物件屬性說明
              ul.list-group
                	
                li.list-group-item order	排列順序
                li.list-group-item name	 電器名稱
                li.list-group-item consumption	預設耗電量
                li.list-group-item show_compare	 顯示在電器比較區域 (1顯示;0隱藏)
                li.list-group-item show_solution	顯示電器處方箋 (1顯示;0隱藏)
                li.list-group-item multiplier  電器加成比例
                li.list-group-item multiplier_info	 電器加成比例原因
                li.list-group-item maximum_consumption	指針的最大值
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
      this.setEditingPage(this.pages.find(p=>p.title=="comparedevice"))
    })
  }
}
</script>
