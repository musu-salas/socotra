const ExtractTextPlugin = require('extract-text-webpack-plugin');
const autoprefixer = require('autoprefixer');
const path = require('path');
const webpack = require('webpack');

module.exports = {
  entry: {
    'styles/bootstrap.css': './scss/custom-bootstrap/bootstrap',

    'styles/landing.css': './scss/landing',
    'scripts/landing.js': './typescripts/landing',

    'scripts/group-edit.js': './typescripts/pages/group-edit'
  },
  output: {
    path: path.join(__dirname, '../../public'),
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
          fallback: 'style-loader',
          use: [
            {
              loader: 'css-loader',
              options: {
                sourceMap: true
              }
            },
            {
              loader: 'postcss-loader'
            },
            {
              loader: 'sass-loader',
              options: {
                includePaths: [path.resolve(__dirname, './scss')]
              }
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
    new webpack.ProvidePlugin({
      'Promise': 'imports-loader?this=>global!exports-loader?global.Promise!es6-promise'
    }),
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
