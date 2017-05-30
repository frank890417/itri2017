const path = require('path')
const webpack = require('webpack')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const extractCSS = new ExtractTextPlugin('[name].bundle.css')
const vueloader = require('vue-loader')


const extractCommons = new webpack.optimize.CommonsChunkPlugin({
  name: 'commons',
  filename: 'commons.js'
})

const config = {
  /**
   * webpack 執行環境，即 webpack 載入檔案時相對路徑的根目錄環境
   * 預設（沒有設定時）為執行指令（webpack）所在的那個目錄
   *
   * 【其他】
   * 假設自己新增一個 build/build.js 檔案，使用載入 webpack 的作法
   *
   * 例如範例：https://github.com/andyyou/webpack-context-prove/blob/master/build/build.js#L14
   *
   * 在不同目錄執行：
   * > node build/build.js (in root/ folder)
   * > node build.js (in build/ folder)
   * 預設 context 會分別為 root/ 和 root/build/
   */
  context: path.join(__dirname, 'src'),
  // ...
 
  /**
   * Entry point
   * 因為設定了 context 所以不需要加上 src/ 了
   */
  entry: {
    app: './app.js',
    // admin: './admin.js'
  },
  /**
   * 輸出路徑與檔名
   */
  output: {
    path: path.join(__dirname, 'dist'),
    publicPath: '/dist/',
    filename: '[name].bundle.js'
  },
  /**
   * loaders 對應使用規則
   */
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: [
          {
            /* webpack 2.x 移除了省略 -loader 的寫法 */
            loader: 'babel-loader',
            options: {
              presets: [
                /* Loose mode and No native modules(Tree Shaking) */
                ['es2015', { modules: false, loose: false }]
              ]
            }
          }
        ]
      },
      {
        test: /\.sass$/,
        loader: extractCSS.extract([
          'css-loader',
          'sass-loader'
        ])


      },
      {
        test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
        use: {
          loader: 'url-loader',
          options: {
            limit: 10000
          }
        }



      },
      {
        test: /\.vue$/,
        use: {
          loader: 'vue-loader',
        }



      }

    ]
  }, 
  plugins: [
    extractCommons,
    extractCSS,
    new webpack.NamedModulesPlugin()
  ]
}

module.exports = config