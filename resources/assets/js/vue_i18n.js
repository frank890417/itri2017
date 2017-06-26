import Vue from 'vue'
import VueI18n from 'vue-i18n'
Vue.use(VueI18n)

// const messages = {
//   zh: {
//     page_about: {
//       section_1: {
//         title: "家電該換了嗎？",
//         content: "老舊家電耗電量平均是節能家電的2.5倍，<br>在夏季用電尖峰時期影響更大。<br>"
//       },
//       section_2: {
//         title: "電用在哪？",
//         content: "家庭用電採用累進分段的六級電費，用電量越多單價越高<br>由於夏季冷氣用電激增，導致電力公司無法供應用戶瞬間尖峰用電。因此為了降低用電，每年夏月(6/1-9/30)，電價比其他月份高。<br><br>夏月空調占總用電量的43%，非夏月則是電冰箱占26%，究竟是哪些電器默默吃掉你的用電呢？",
//         chart: {
//           summer: [{name: "冷氣機", value: 43.1},
//                {name: "電冰箱", value: 16.9},
//                {name: "飲水機", value: 8.8},
//                {name: "電熱水瓶", value: 6.7},
//                {name: "照明設備", value: 5.0},
//                {name: "洗衣機", value: 5.2},
//                {name: "電腦", value: 5.1}],
//           nsummer: [{name: "電冰箱", value: 26.2},
//                {name: "飲水機", value: 14.2},
//                {name: "電熱水瓶", value: 12.4},
//                {name: "冷氣機", value: 7.8},
//                {name: "照明設備", value: 7.8},
//                {name: "洗衣機", value: 5.2},
//                {name: "電腦", value: 5.1}]
//         }
//       },
      
//     }
//   },
//   ja: {
//     message: {
//       hello: 'こんにちは、世界'
//     }
//   }
// }

const messages = window.site_info_zh
console.log(JSON.stringify(messages))
// Create VueI18n instance with options
const i18n = new VueI18n({
  locale: 'zh', // set locale
  messages, // set locale messages
})

export default {i18n, messages}