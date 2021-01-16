const mix = require('laravel-mix');

mix
	.js('resources/src/main.js', 'public/assets/js')
	.sass('resources/style/style.scss', 'public/assets/css');

mix.disableNotifications();

if (mix.inProduction()) {
	mix.version();
}