const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const path = require("path");
const fs = require("fs");
const CopyPlugin = require("copy-webpack-plugin");

module.exports = (env, argv) => {
  return {
    plugins: [
      new CopyPlugin([
        {
          from: "./src/static",
          to: "static"
        }
      ]),

      new MiniCssExtractPlugin({
        filename: "bundle.css"
      })
    ],
    entry: ["./src/scripts/app.js", "./src/styles/global.scss"],
    output: {
      path: __dirname + "/dist",
      filename: "bundle.js"
    },
    module: {
      rules: [
        {
          test: /\.js$/,
          exclude: /node_modules/,
          use: {
            loader: "babel-loader"
          }
        },
        {
          test: /\.s[ac]ss$/i,
          use: [
            MiniCssExtractPlugin.loader,
            //   // Creates `style` nodes from JS strings
            //   "style-loader",
            //   // Translates CSS into CommonJS
            "css-loader?url=false",
            "postcss-loader",
            // Compiles Sass to CSS
            "sass-loader"
          ]
        }
        // {
        //   test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
        //   use: [
        //     {
        //       loader: "file-loader",
        //       options: {
        //         name: "[name].[ext]",
        //         outputPath: "dist/static/fonts"
        //       }
        //     }
        //   ]
        // }
      ]
    }
  };
};
