const { VueLoaderPlugin } = require('vue-loader')
const webpack = require('webpack');
module.exports = {
  mode: "development",

  devtool: 'inline-source-map',

  entry: {
    "credit":"./credit/credit.ts",
    "todo":"./todo/todo.ts",
    "travel": "./travel/travel.ts"
  },

  output: {
    path: `${__dirname}/dist`,
    filename: "[name].js"
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: "vue-loader"
      },
      {
        test: /\.css$/,
        use:[
          "style-loader",
          "css-loader"
        ]
      },
      {
        test: /\.ts$/,
        loader: "ts-loader",
        options: {
          appendTsSuffixTo: [/\.vue$/]
        }
      },
    ]
  },
  resolve: {
    extensions: [".ts"]
  },
  plugins:[
    new VueLoaderPlugin(),
    new webpack.DefinePlugin({
      __VUE_OPTIONS_API__: true,
      __VUE_PROD_DEVTOOLS__: false
    })
  ]
}
