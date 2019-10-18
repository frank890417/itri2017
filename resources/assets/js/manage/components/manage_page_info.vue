<template lang="pug">
section.manage_comparedevice.container-fluid
  .row.mb-3
    .col-sm-12
      h1 編輯其他頁面資訊

  .row
    .col-sm-12
      .panel.panel-primary
        .panel-heading 資訊編輯

        .panel-body(v-show="nowEditingPage")

          .row
            .col-sm-4
              h3 編輯區塊
              ul.list-group
                li.list-group-item(v-for="page in pages",
                                   @click="setEditingPage(page)",
                                   :class="{active: page===nowEditingPage}") {{ getSectionTitle(page.title) }}
              el-button.mt-3.w100(@click="savePageData(nowEditingPage)",
                       type="danger") 儲存資料變更

            .col-sm-8(v-show="nowEditingPage && nowEditingPage.title=='compareavg'")
              .import-buttons
                h3.mr-3 匯入csv比較資料
                label 僅能包含 判定優先順序, 居住地區, 人口數, 坪數, 住宅類型, 年平均用電度數六個欄位之csv檔案，utf-8編碼
                label 上傳完畢之後請按"儲存資料變更"
                br
                input#dealCsv(type="file", accept=".csv")
              br
              br

            .col-sm-8(v-if="nowEditingPage && nowEditingPage.title=='embedsection'")
              h3 嵌入區塊設定
              br
              label 是否顯示&nbsp;
              el-switch(v-model="nowEditingPage.content.show",
                          @change="setEditingPage(nowEditingPage)")
              br
              br
              vue-editor(id="editor",
                        v-model="nowEditingPage.content.content",
                        @change="setEditingPage(nowEditingPage)",
                        @imageAdded="handleImageAdded",
                        :useCustomImageHandler="true",
                        
                      )
            .col-sm-8(v-show="!(nowEditingPage && nowEditingPage.title=='embedsection')",
                      @keyup="updateJsonEditor")
              #jsoneditor.mt-2
              // 
      

</template>


<script>
import csv from 'csvtojson'
import axios from 'axios'
import { VueEditor ,Quill } from 'vue2-editor'


export default { 
  mounted(){
    var container = document.getElementById("jsoneditor");
    this.editor = new JSONEditor(container);

    //csv reader
    let _this = this
    let input = document.getElementById('dealCsv');
    
    input.addEventListener('change', function() {

      if (this.files && this.files[0]) {

          var myFile = this.files[0];
          var reader = new FileReader();
          
          reader.addEventListener('load', function (e) {
              
              let csvdata = e.target.result; 
              console.log(csvdata)
              csv({
                noheader: true,
                output: "csv"
              })
              .fromString(csvdata)
              .then((csvRow)=>{
                let headers = csvRow[0]
                let contents = csvRow.slice(1)
                let combinedData = contents.map(content=>{
                  let row = {}
                  for(var i=0;i<headers.length;i++){
                    row[headers[i]]=content[i]
                  }
                  return row
                })
                console.log(combinedData)
                let updatePageData = {
                  avg_house_data: combinedData
                }
                _this.$set(_this.nowEditingPage,"content",updatePageData)
                _this.editor.set(updatePageData);
                _this.editor.expandAll();
                
              }) 
              // parseCsv.getParsecsvdata(csvdata); // calling function for parse csv data 
          });
          
          reader.readAsText(myFile);
      }
    });
  },
  methods:{
    updateJsonEditor(){
      this.$set(this.nowEditingPage,"content",this.editor.get())
      console.log("json updated")
    },
    savePageData(page){
      // this.$set(this.nowEditingPage,"content",this.editor.get())
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
        embedsection: "嵌入區塊",
        compareavg: "地區平均比較"
      }
      return list[key] || key
    },
    handleImageAdded(file, Editor, cursorLocation) {
      // An example of using FormData
      // NOTE: Your key could be different such as:
      // formData.append('file', file)
      console.log("get picture!")
      var formData = new FormData();
      formData.append('file', file)
      console.log(file)

      axios({
        url: '/api/upload',
        method: 'POST',
        data: formData
      })
        .then((result) => {
          let url = result.data // Get url from response
          Editor.insertEmbed(cursorLocation, 'image', url);
        })
        .catch((err) => {
          console.log(err);
        })
    },
  },
  data(){
    return {
      editor: null,
      nowEditingPage: null,
      pages: [],
      customToolbar: [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        
        ['my-thing'],
        ['blockquote', 'code-block','image','video','link'],

        // [{ 'header': 1 }, { 'header': 2 }],               // custom button values
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'script': 'sub'}, { 'script': 'super' }],      // superscript/subscript
        [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
        [{ 'direction': 'rtl' }],                         // text direction

        // [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

        [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
        [{ 'font': [] }],
        [{ 'align': [] }],

        ['clean']                                         // remove formatting button
      ],
      
    }
  },
  created(){
    axios.get("/api/page/").then((res)=>{
      this.$set(this,"pages",res.data)
      this.pages.forEach(page=>{
        page.content = page.content ?JSON.parse(page.content):{}
      })
      this.pages=this.pages.filter(p=>p.title!="comparedevice")
      this.setEditingPage(this.pages.find(p=>p.title=="embedsection"))
    })
  },
  components: {
    VueEditor
  },
  watch:{
    nowEditingPage(){
      this.editor.set(this.nowEditingPage.content);
      this.editor.expandAll();
      
    }
  }
}
</script>
