let mix = require('laravel-mix').mix;
const WebpackShellPlugin = require('webpack-shell-plugin');
const themeInfo = require('./theme.json');

/**
 * Compile less
 */
mix.less('resources/less/main.less', 'assets/css/main.css')

/**
 * Concat scripts
 */
mix.scripts([
  'node_modules/jquery/dist/jquery.js',
  'node_modules/bootstrap/dist/js/bootstrap.min.js',
  'node_modules/prismjs/prism.js',
  'resources/js/bootswatch.js'
], 'assets/js/all.js');

/**
 * Copy Font directory https://laravel.com/docs/5.4/mix#url-processing
 */
mix.copy(
  'fonts',
  '../../public/fonts'
);

/**
 * Publishing the assets
 */
mix.webpackConfig({
  plugins: [
    new WebpackShellPlugin({onBuildEnd:['php ../../artisan stylist:publish ' + themeInfo.name]})
  ]
});
