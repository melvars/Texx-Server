let mix = require('laravel-mix');
let SWPrecacheWebpackPlugin = require('sw-precache-webpack-plugin');

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
    .js('resources/assets/js/admin.js', 'public/js') // TODO: FIX
    .js('resources/assets/js/initial_key_gen.js', 'public/js')
    // .ts('resources/assets/js/typescript.ts', 'public/js') => for use of typescript!
    .sass('resources/assets/sass/admin.scss', 'public/css')
    .sass('resources/assets/sass/app.scss', 'public/css')
    .sourceMaps()
    .browserSync({
        files: [
            'public/css/*.css',
            'public/js/*.js',
            //'**/*.blade.php'
        ],
        notify: false,
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
    .disableNotifications()
    .webpackConfig({
        module: {
            rules: [{
                test: /\.css$/,
                loader: 'postcss-loader',
                options: {
                    plugins: () => [require('autoprefixer')]
                }
            }]
        },
        plugins: [
            new SWPrecacheWebpackPlugin({
                cacheId: 'BEAM-Messenger',
                filename: 'service-worker.js',
                staticFileGlobs: [
                    'public/**/*.{css,js,eot,svg,ttf,woff,woff2,html}', // all compiled/public scripts
                ],
                minify: true,
                stripPrefix: 'public/',
                handleFetch: true,
                maximumFileSizeToCacheInBytes: 20971520,
                // dynamicUrlToDependencies: {
                //     '/': ['resources/views/writeMessage.blade.php'],
                //     '/profile': ['resources/views/profile.blade.php'],
                //     // TODO: add more for even better caching => ??
                // },
                staticFileGlobsIgnorePatterns: [/\.map$/, /mix-manifest\.json$/, /manifest\.json$/],
                navigateFallback: '/',
                importScripts: ['js/service-worker-ext.js'],
                runtimeCaching: [{
                        urlPattern: /^https:\/\/fonts\.googleapis\.com\//,
                        handler: 'cacheFirst'
                    },
                    {
                        urlPattern: /^https:\/\/fonts\.gstatic\.com\//,
                        handler: 'cacheFirst'
                    },
                    {
                        urlPattern: /^https:\/\/cdn\.socket\.io\//,
                        handler: 'cacheFirst'
                    },
                    {
                        urlPattern: /\/socket.io\//,
                        handler: 'cacheFirst'
                    },
                    {
                        urlPattern: /\/_debugbar\//,
                        handler: 'cacheFirst'
                    },
                    {
                        urlPattern: /\/browser-sync\//,
                        handler: 'cacheFirst'
                    },
                    {
                        urlPattern: /\/avatar\//,
                        handler: 'cacheFirst'
                    }
                ],
            })
        ]
    });