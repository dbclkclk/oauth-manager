const ExtractTextPlugin = require("extract-text-webpack-plugin");

module.exports = {
    entry : __dirname + '/app/index.js',
    devtool: 'eval-source-map',
    module : {
    	loaders: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader'
            },
            {
                test: /\.scss$/,
                loaders: [
                    'style-loader',
                    'css-loader?importLoader=1&modules&localIdentName=[path]___[name]__[local]___[hash:base64:5]',
                    'sass-loader'
                  ]
            }
           
        ]
    },
    output: {
        filename : 'bundled.js',
        path : __dirname + '/build'
    },
    watch: true,
    watchOptions: {
        aggregateTimeout: 300,
        poll: 200
    }
};
