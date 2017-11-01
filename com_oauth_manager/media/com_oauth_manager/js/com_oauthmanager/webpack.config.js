const ExtractTextPlugin = require("extract-text-webpack-plugin");

module.exports = {
    entry : __dirname + '/app/index.js',
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
                    'css-loader?modules&importLoaders=1&localIdentName=[path]___[name]__[local]___[hash:base64:5]',
                    'resolve-url-loader',
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
    },
    plugins: [
    	new ExtractTextPlugin({
    		  filename: 'app.css',
    		  allChunks: true
    		})
    ]
};
