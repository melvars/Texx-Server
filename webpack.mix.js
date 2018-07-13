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
    .browserSync({
        files: [
            'public/css/*.css',
            'public/js/*.js',
            '**/*.blade.php'
        ],
        injectChanges: true,
        proxy: {
            target: '127.0.0.1:8000',
            reqHeaders: function () {
                return {
                    host: 'localhost:3000'
                };
            }
        }
    })
    .disableNotifications();