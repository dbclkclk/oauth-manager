module.exports = {
    entry : __dirname + '/app/index.js',
    module : {
        loaders: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader'
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
