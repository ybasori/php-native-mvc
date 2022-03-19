/* eslint-disable no-undef */
/* eslint-disable @typescript-eslint/no-var-requires */
const { merge } = require("webpack-merge");
const common = require("./webpack.common.js");

require("dotenv").config();

module.exports = merge(common, {
  mode: "development",
  devtool: "source-map",
  devServer: {
    historyApiFallback: true,
    port: 3000,
    open: true,
    hot: true,
    proxy: {
      "*": {
        target: `http://0.0.0.0:8000`,
        changeOrigin: true,
        // secure: true,
      },
    },
  },
  output: {
    clean: true,
    publicPath: `http://localhost:3000/assets`,
  },
});
