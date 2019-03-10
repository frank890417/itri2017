// CommitChart.js
import { Doughnut, mixins } from 'vue-chartjs'
 
export default {
  extends: Doughnut,
  mixins: [mixins.reactiveProp],
  props: ["chartData"],
  mounted () {
    // Overwriting base render method with actual data.
    this.renderChart(this.chartData)
  },
  // watch:{
  //   datasets(){
  //     console.log("chart rendered")
      

  //   }

  // }
}