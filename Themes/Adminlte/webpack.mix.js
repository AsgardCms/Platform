let mix = require('laravel-mix').mix;
const WebpackShellPlugin = require('webpack-shell-plugin');
const themeInfo = require('./theme.json');


/**
 * Compile less
 */
mix.less('resources/assets/less/asgard.less', 'assets/css/asgard.css')


/**
 * Copy scripts
 */
mix.copy('resources/assets/js', 'assets/js');

/**
 * Copy node module
 */
mix.copyDirectory('node_modules/admin-lte', 'assets/vendor/admin-lte');
mix.copyDirectory('node_modules/animate.css', 'assets/vendor/animate.css');
mix.copyDirectory('node_modules/bootstrap', 'assets/vendor/bootstrap');
mix.copyDirectory('node_modules/clipboard', 'assets/vendor/clipboard');
mix.copyDirectory('node_modules/datatables.net', 'assets/vendor/datatables.net');
mix.copyDirectory('node_modules/datatables.net-bs', 'assets/vendor/datatables.net-bs');
mix.copyDirectory('node_modules/font-awesome', 'assets/vendor/font-awesome');
mix.copyDirectory('node_modules/gridstack', 'assets/vendor/gridstack');
mix.copyDirectory('node_modules/icheck', 'assets/vendor/iCheck');
mix.copyDirectory('node_modules/jquery', 'assets/vendor/jquery');
mix.copyDirectory('node_modules/jquery-ui', 'assets/vendor/jquery-ui');
mix.copyDirectory('node_modules/lodash', 'assets/vendor/lodash');
mix.copyDirectory('node_modules/simplemde/src', 'assets/vendor/simplemde/src');

/**
 * Publishing the assets
 */
mix.webpackConfig({
  plugins: [
    new WebpackShellPlugin({onBuildEnd:['php ../../artisan stylist:publish ' + themeInfo.name]})
  ]
});