const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CopyPlugin = require('copy-webpack-plugin');

const themeName = 'your_theme_name';
const themePath = '../../app/themes/custom/' + themeName;

const config = {
  entry: [
    themePath + '/assets/js/main.js',
    themePath + '/assets/scss/main.scss'
  ],
  output: {
    path: path.resolve(__dirname, themePath + '/dist/'),
    filename: 'js/[name].min.js'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        loader: 'babel-loader',
        options: {
          presets: ['@babel/preset-env'],
          plugins: ['@babel/plugin-transform-runtime']
        }
      },
      {
        test: /\.scss$/,
        exclude: /node_modules/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'sass-loader',
          'postcss-loader'
        ]
      },
      {
        test: /\.(eot|ttf|woff|woff2|otf)$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'fonts'
            }
          }
        ]
      },
      {
        test: /\.(png|jpg|jpeg|svg|gif)$/i,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]'
            }
          }
        ]
      }
    ]
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: 'css/[name].min.css',
    }),
    new CopyPlugin({
      patterns: [
        {
          from: themePath + '/assets/images',
          to: './images',
          noErrorOnMissing: true
        }
      ]
    })
  ]
};

module.exports = config;