<template lang='jade'>
  .container-fluid.carousel_editor
    .row(v-for="(c,cid) in now_carousel_data")
      .col-sm-5
        //pre {{c}}
        div.preview(:style="css_cover(c.url)")
      .col-sm-7
        h4 {{c.url==""?"請選擇":""}}圖片{{ options.allow_multi?(cid+1):"" }} 
          .btn.btn-default(@click="update_obj.obj[update_obj.tagkey].splice(cid,1)") x
        hr
        df_pic_selector(:output.sync="c.url", :update_obj="{obj: c,tagkey: 'url'}", :ar_id="cid", @select_pic="select_pic")
        input.form-control(v-model="c.url" @value="set_pic(c,value)")
    .row
      .col-sm-12(v-if="options.allow_multi || now_carousel_data.length<1")
        .ctn.btn.btn-default(@click="carousel_data.push('')") 新增圖片
    //input(:name=" input_name || 'carousel'" , :value="output_json" hidden)

</template>

<script>
    export default {
        props: ['input_name','carousel_data','allow_multi','update_obj'],
        name: "carousel_editor",
        data(){
          return {
            // carousel_data: ["/img/homepage/Solution2.jpg","/img/homepage/Post2.jpg"]
            // now_carousel_data: [],
            options: {
              allow_multi: true
            }
          }
        },
        watch: {
          carousel_data:{
            handler(){
              console.log("carousel_data changed")
            },
            deep: true
          //   let delta = (typeof this.carousel_data=="string")?[this.carousel_data]:this.carousel_data
          //   let nowstate = this.now_carousel_data.map(o=>o.url)
          //   console.log("delta:",JSON.stringify(delta) )
          //   console.log("nowstate:",JSON.stringify(nowstate))

          //   if (JSON.stringify(delta) != JSON.stringify(nowstate)) {
          //     console.log("update state")
          //     if (typeof this.carousel_data == "string"){
          //       this.now_carousel_data   =[this.carousel_data]
          //     }else{
          //       this.now_carousel_data
          //         =this.carousel_data.map( (t)=>({url: t}) )
          //     }     
          //   }
          }
        },
        created() {
            console.log('carousel created.')
        //     console.log(this.carousel_data)
        //     this.options.allow_multi=(typeof this.allow_multi!="undefined")?this.allow_multi:this.options.allow_multi;  
        //     console.log(this.options.allow_multi)

        //     if (typeof this.carousel_data == "string"){
        //       this.now_carousel_data   =[this.carousel_data]
        //     }else{
        //       this.now_carousel_data
        //         =this.carousel_data.map( (t)=>({url: t}) )
        //     }           
        },
        methods:{
          css_cover(url) {
            return {
              "background-image": `url(${(""+url).replace(" ","%20")})`,
              "background-size": "cover",
              width: "100%",
              height: "200px",
              margin: "10px"
            }
          },
          css_default_block(url){
            return {
              "background-image": `url(${url.replace(" ","%20")})`,
              "background-size": "cover",
              width: "100px",
              height: "100px",
              margin: "5px",
              display: "inline-block"
            }
          },
          select_pic(obj){

            // this.now_carousel_data[obj.id].url=obj.url
            this.$emit("update_obj_by_key",{obj: this.update_obj.obj,tagkey: this.update_obj.tagkey, id: obj.id ,value: obj.url, multi: this.options.allow_multi, update_panel: this} );
          }
        },
        computed: {
          now_carousel_data(){
            console.log("recaculate nowcarouesel")
            console.log(this.carousel_data)

            this.options.allow_multi=(typeof this.allow_multi!="undefined")?this.allow_multi:this.options.allow_multi;  
            console.log(this.options.allow_multi)

            if (typeof this.carousel_data == "string"){
              return [{url: this.carousel_data}]
            }else{
              return this.carousel_data.map( (t)=>({url: t}) )
            }     

          }
          // output_json(){
          //   let outdata=this.now_carousel_data.map(o=>o.url).filter(o=>o!="");
          //   if (!this.options.allow_multi){
          //     var outdata_single=this.now_carousel_data.map(o=>o.url).filter(o=>o!="")[0];
          //     this.$emit("update_obj_by_key",{...this.update_obj,value: outdata_single} );
          //     if (this.update_obj){
          //       console.log(this.update_obj)
          //       // this.update_obj.obj[this.update_obj.tagkey]=outdata_single
          //     }
          //     return outdata_single
          //   }
          //   this.$emit("update_obj_by_key",{...this.update_obj,value: outdata});
          //   if (this.update_obj){
          //       // this.update_obj.obj[this.update_obj.tagkey]=JSON.parse(JSON.stringify(outdata))
          //     }
          //   return JSON.stringify(outdata);
          // }
        }
    }
</script>

<style scoped lang="sass?indentedSyntax">
  .carousel_editor
    .preview
      background-color: #eee
      background-position: center center
</style>