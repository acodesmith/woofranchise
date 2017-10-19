const webpack               = require('webpack');
const path                  = require('path');
const BASE_DIR              = path.resolve('./');
const ExtractTextPlugin     = require("extract-text-webpack-plugin");



let config = {
    entry: {
        "dist/admin": BASE_DIR + '/resources/scripts/admin.js',
    },
    output: {
        filename: '[name].js'
    },
    resolve: {
        extensions: ['.js' ]
    },
    devtool: 'source-map',
    module : {
        loaders: [
            {
                test: /\.js$/,
                exclude: /(node_modules|build)/,
                loader: 'eslint-loader'
            },{
                test : /\.jsx?/,
                exclude: /(node_modules|bower_components)/,
                loader : 'babel-loader',
                query: {
                    plugins: ['transform-runtime'],
                    presets: ['stage-2', 'es2015' ],
                }
            },{
                test: /\.scss$/,
                use: ExtractTextPlugin.extract({
                    fallback: "style-loader",
                    use: ["css-loader", "sass-loader"]
                })
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin( '[name].css' ),
        new webpack.DefinePlugin({
            'process.env': {
                NODE_ENV: JSON.stringify(process.env.NODE_ENV),
            },
        })
    ]
};

module.exports = config;