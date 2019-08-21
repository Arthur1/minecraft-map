let mix = require('laravel-mix');

// mix.webpackConfig({
// 	resolve: {
// 		alias: {
// 			'vue$': 'vue/dist/vue.esm.js'
// 		}
// 	}
// });

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/assets/js')
	.js('resources/js/materialize.js', 'public/assets/js')
	.sass('resources/sass/materialize.scss', 'public/assets/css');
