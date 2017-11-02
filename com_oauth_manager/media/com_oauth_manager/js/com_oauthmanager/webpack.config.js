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
                use: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: 'css-loader!sass-loader?modules,localIdentName="[name]-[local]-[hash:base64:6]"'
                }),
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
