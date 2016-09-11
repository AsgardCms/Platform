var gulp = require("gulp");
var shell = require('gulp-shell');
var elixir = require('laravel-elixir');
var themeInfo = require('./theme.json');

var task = elixir.Task;
elixir.extend("stylistPublish", function() {
    new task("stylistPublish", function() {
        return gulp.src("").pipe(shell("php ../../artisan stylist:publish " + themeInfo.name));
    }).watch("**/*.less");
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
    mix
        .less([
            "asgard.less"
        ], 'assets/css/asgard.css')
        .stylistPublish();

    /**
     * Concat scripts
     */
    //mix.scripts([
    //    "app.js"
    //]);

    mix.copy('resources/assets/js', 'assets/js');
});
