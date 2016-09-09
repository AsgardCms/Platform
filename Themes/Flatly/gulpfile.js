var gulp = require("gulp");
var shell = require('gulp-shell');
var elixir = require('laravel-elixir');
var themeInfo = require('./theme.json');

var Task = elixir.Task;

elixir.extend('stylistPublish', function() {
    new Task('stylistPublish', function() {
        return gulp.src("").pipe(shell("php ../../artisan stylist:publish " + themeInfo.name));
    });
});
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function (mix) {

    /**
     * Compile less
     */
    mix.less([
        "main.less"
    ], 'assets/css/main.css')
    .stylistPublish();

    /**
     * Concat scripts
     */
    mix.scripts([
        '/vendor/jquery/dist/jquery.js',
        '/vendor/bootstrap/dist/js/bootstrap.min.js',
        '/vendor/prism/prism.js',
        '/js/bootswatch.js'
    ], null, 'resources');

    /**
     * Copy Bootstrap fonts
     */
    mix.copy(
        'resources/vendor/bootstrap/fonts',
        'assets/fonts'
    );

    /**
     * Copy Fontawesome fonts
     */
    mix.copy(
        'resources/vendor/font-awesome/fonts',
        'assets/fonts'
    );
});
