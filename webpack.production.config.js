var webpack = require('webpack');
var path = require('path');
var loaders = require('./webpack.loaders');

module.exports = {
    entry: [
        './js/app.js' // Your appʼs entry point
    ],
    output: {
        path: path.join(__dirname, 'web/js/'),
        filename: 'app.js'
    },
    resolve: {
        extensions: ['', '.js', '.jsx']
    },
    module: {
        loaders: loaders
    }
};