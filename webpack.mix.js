const mix = require('laravel-mix');

if (mix.inProduction()) {
    mix.sourceMaps().disableNotifications().version();
}

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
    .extract()
    // .extract([
    //     '@babel/polyfill',
    //     'bootstrap-sass',
    //     'element-ui',
    //     'form-backend-validation',
    //     'lodash',
    //     'moment',
    //     'typy',
    //     'vue',
    //     'vue-data-tables',
    //     'vue-events',
    //     'vue-i18n',
    //     'vue-router',
    //     'vue-shortkey',
    //     'vue-simplemde',
    //     'vue-template-compiler',
    // ])
    .sass('resources/assets/sass/app.scss', 'public/css');
