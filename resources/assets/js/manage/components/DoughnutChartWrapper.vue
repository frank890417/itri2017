// CommitChart.js
<template lang="pug">
  div
    //- button.btn.btn-default.text-center(@click="downloadData") 下載資料
    //- pre {{chartData}}
    DoughnutChart(:chartData="chartData.graphData")
    vue_lazy_table(ref="table", :table_data="chartData.printData.data", :dataTitle="title")
</template>
<script>
import DoughnutChart from './DoughnutChart'
import vue_lazy_table from '../components/vue_lazy_table';
export default {
  props: ["chartData","title"],
  components: {DoughnutChart, vue_lazy_table},
  methods: {
    downloadData(){
      this.$refs.table.export_csv()
    }
  }
  // watch:{
  //   datasets(){
  //     console.log("chart rendered")
      

  //   }

  // }
}
</script>