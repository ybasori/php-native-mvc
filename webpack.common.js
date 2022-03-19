/* eslint-disable no-undef */
/* eslint-disable @typescript-eslint/no-var-requires */
const path = require("path");
const webpack = require("webpack");

require("dotenv").config();

module.exports = {
  entry: {
    app: {
      import: "./src/index.tsx",
      dependOn: ["shared", "shared-dom", "shared-router-dom"],
    },
    shared: "react",
    "shared-dom": "react-dom",
    "shared-router-dom": "react-router-dom",
  },
  resolve: {
    extensions: [".ts", ".tsx", ".js", ".css", ".scss"],
  },
  output: {
    clean: true,
    path: path.resolve(__dirname, "public/assets/"),
    filename: "js/[name].bundle.js",
  },
  module: {
    rules: [
      {
        test: /\.(ts|tsx)$/,
        exclude: [path.resolve("node_modules")],
        use: ["ts-loader"],
      },
      {
        test: /\.(scss|sass|css)$/i,
        use: ["style-loader", "css-loader", "sass-loader"],
      },
      {
        test: /\.(woff|woff2|eot|ttf|otf)$/i,
        type: "asset/resource",
        generator: {
          filename: "fonts/[hash][ext][query]",
        },
      },
      {
        test: /\.(png|svg|jpg|jpeg|gif)$/i,
        type: "asset/resource",
        generator: {
          filename: "images/[hash][ext][query]",
        },
      },
    ],
  },
  optimization: {
    runtimeChunk: "single",
  },
  plugins: [
    new webpack.DefinePlugin({
      "process.env": JSON.stringify(process.env),
    }),
  ],
};
