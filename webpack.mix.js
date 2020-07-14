const mix = require('laravel-mix');

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

mix.react('resources/js/app.js', 'public/js')
    .scripts([
        'resources/js/lib/common.js',
        'resources/js/lib/unslider.min.js'
    ], 'public/js/custom.js')
    .sass('resources/sass/app.scss', 'public/css')
    .styles([
        'resources/sass/common.css',
        'resources/sass/index.css',
        'resources/sass/style.css',
        'resources/sass/response.css',
    ], 'public/css/custom.css')
    .version();
