const ExtractTextPlugin = require('extract-text-webpack-plugin');
const autoprefixer = require('autoprefixer');
const path = require('path');
const webpack = require('webpack');

module.exports = {
  entry: {
    'scripts/landing.js': './typescripts/landing',
    'styles/bootstrap.css': './scss/custom-bootstrap/bootstrap',
    'styles/landing.css': './scss/landing',
  },
  output: {
    path: '../../public',
    publicPath: '/',
    filename: '[name]'
  },
  resolve: {
    extensions: ['.scss', '.js', '.ts']
  },
  devtool: 'source-map',
  module: {
    loaders: [
      {
        test: /\.scss$/,
        loader: ExtractTextPlugin.extract({
          fallbackLoader: 'style-loader',
          loader: [
            {
              loader: 'css-loader',
              options: {
                sourceMap: true,
                modules: true
              }
            },
            {
              loader: 'sass-loader',
              options: {
                includePaths: [path.resolve(__dirname, './scss')],
                indentedSyntax: 'sass'
              }
            },
            {
              loader: 'postcss-loader'
            }
          ]
        })
      },
      {
        test: /\.ts$/,
        loader: ['awesome-typescript-loader']
      }
    ]
  },
  plugins: [
    new webpack.ProgressPlugin(),
    new ExtractTextPlugin('[name]'),
    new webpack.LoaderOptionsPlugin({
      options: {
        postcss: [
          autoprefixer({
            browsers: ['last 3 version', 'ie >= 10']
          })
        ]
      }
    })
  ],
  externals: {
    'jquery': '$',
    'lodash': '_'
  }
};
