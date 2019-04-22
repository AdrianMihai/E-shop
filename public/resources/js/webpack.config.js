var HTMLWebpackPlugin = require('html-webpack-plugin');
var HTMLWebpackPluginConfig = new HTMLWebpackPlugin({
		template:  './react_test/startApp.html',
		filename: 'startApp.html',
		inject: 'body'
});

module.exports = {
	entry: __dirname + '/react_test/indexApp.js',
	module : {
		loaders: [
			{
				test: /\.js/,
				exclude: /node_modules/,
				loader: 'babel-loader'
			}
		]
	},
	output:{
		filename: 'indexAppTransformed.js',
		path: __dirname + '/transformed'
	},
	plugins: [HTMLWebpackPluginConfig]
};