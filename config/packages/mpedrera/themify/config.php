<?php

return array(
    // In case you do not define a theme in your controller,
    // or explicitly using Themify::setTheme(),
    // this one will be used.
    'default_theme' => 'default',

    // Internal folder where theme views are stored.
    // Defaults to (...)/app/themes
    //
    // The package will search for views directly
    // inside each theme folder, so you don't need
    // a 'views' folder inside your theme:
    // app
    //   |_ themes
    //      |_ default
    //          |_ layouts
    //          |_ post
    //          |_ static
    'themes_path' => base_path() . '/themes',

    // The directory inside your public folder where all theme
    // assets are stored. If you changed your public folder in your
    // app config, it will be automatically detected.
    //
    // Do not include the public folder itself, or
    // leading/trailing slashes.
    //
    // With default value, your assets structure would be:
    // public
    // |_ assets
    //    |_ themes
    //        |_ default
    //           |_ css, js, img, etc.
    //        |_ admin-theme
    //           |_ css, js, img, etc.
    //        |_ alternative-theme
    //           |_ css, js, img, etc.
    'themes_assets_path' => 'assets/themes',
);
