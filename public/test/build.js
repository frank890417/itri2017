const path = require('path')
const webpack = require('webpack')

const extractCommons = new webpack.optimize.CommonsChunkPlugin({
  name: 'commons',
  filename: 'commons.js'
})

const config = {
  context: path.join(__dirname, 'src'),
  entry: {
    app: './app.js',
  },
  output: {
    path: path.join(__dirname, 'dist'),
    filename: '[name].bundle.js'
  },
  // ...
  plugins: [
    extractCommons
  ]
}

module.exports = config
