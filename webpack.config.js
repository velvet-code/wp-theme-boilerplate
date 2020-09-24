const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const path = require("path");
const fs = require("fs");
const CopyPlugin = require("copy-webpack-plugin");

module.exports = (env, argv) => {
  return {
    devtool: "source-map",
    plugins: [
      new CopyPlugin([
        {
          from: "./src/static",
          to: "static",
        },
      ]),

      new MiniCssExtractPlugin({
        filename: "bundle.css",
      }),
    ],
    entry: ["./src/scripts/app.js", "./src/styles/main.scss"],
    output: {
      path: __dirname + "/dist",
      filename: "bundle.js",
      sourceMapFilename: "[file].map",
    },
    module: {
      rules: [
        {
          test: /\.js$/,
          exclude: /node_modules/,
          use: {
            loader: "babel-loader",
          },
        },
        {
          test: /\.s[ac]ss$/i,
          use: [
            {
              loader: MiniCssExtractPlugin.loader,
            },
            {
              loader: "css-loader",
              options: {
                url: false,
                sourceMap: true,
              },
            },
            {
              loader: "postcss-loader",
              options: {
                sourceMap: true,
              },
            },
            {
              loader: "sass-loader",
              options: {
                sourceMap: true,
              },
            },
          ],
        },
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
      ],
    },
  };
};
