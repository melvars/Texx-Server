let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
    .js('resources/assets/js/initial_key_gen.js', 'public/js')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sourceMaps()
    .browserSync('http://127.0.0.1:8000', {
        files: [
            'public/css/*.css',
            'public/js/*.js'
        ],
        injectChanges: true,
        proxy: false
    })
    .disableNotifications();