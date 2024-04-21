const { VueLoaderPlugin } = require('vue-loader')
const webpack = require('webpack');
const path = require('path');
const { TsconfigPathsPlugin } = require('tsconfig-paths-webpack-plugin');

module.exports = {
  mode: "development",

  devtool: 'inline-source-map',

  entry: {
    "credit":"./credit/credit.ts",
    //"todo":"./todo/todo.ts",
    "rate":"./ex_rate/rate.ts",
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
          transpileOnly:true,
          //appendTsSuffixTo: [/\.vue$/]
        }
      },
    ]
  },
  resolve: {

    modules:[
      path.resolve("./node_modules"),
      path.resolve("./util")
    ],
    alias:{
      "@": path.resolve(__dirname, "util")
    },
    plugins: [new TsconfigPathsPlugin({ configFile: './tsconfig.json' })],
    extensions: [".ts", ".vue"]
  },
  performance: {
    maxEntrypointSize: 10000000,
    maxAssetSize: 10000000
  },
  plugins:[
    new VueLoaderPlugin(),
    new webpack.DefinePlugin({
      __VUE_OPTIONS_API__: true,
      __VUE_PROD_DEVTOOLS__: false
    })
  ]
}
