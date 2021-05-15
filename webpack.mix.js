const mix = require('laravel-mix');

mix.disableNotifications();

if (mix.inProduction()) {
	mix.version();
}

mix.webpackConfig({
	output: {
		chunkFilename: 'assets/js/[name].js',
	},
});

mix.js('resources/src/main.js', 'public/assets/js');
mix.sass('resources/style/style.scss', 'public/assets/css');