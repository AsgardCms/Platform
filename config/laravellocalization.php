<?php

return array(

    // Uncomment the languages that your site supports - or add new ones.
    // These are sorted by the native name, which is the order you might show them in a language selector.
    // Regional languages are sorted by their base languge, so "British English" sorts as "English, British"
    'supportedLocales' => [
        'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English'],
    ],
    // Negotiate for the user locale using the Accept-Language header if it's not defined in the URL?
    // If false, system will take app.php locale attribute
    'useAcceptLanguageHeader' => true,
    // Should application use the locale stored in a session
    // if the locale is not defined in the url?
    // If false and locale not defined in the url, the system will
    // take the default locale (defined in config/app.php) or
    // the browser preferred language (if useAcceptLanguageHeader is true) or
    // the cookie locale (if useCookieLocale is true)
    'useSessionLocale' => true,
    // Should application use and store the locale stored in a cookie
    // if the locale is not defined in the url or the session?
    // If true and locale not defined in the url or the session,
    // system will take the default locale (defined in config/app.php)
    // or the browser preferred locale (if useAcceptLanguageHeader is true)
    'useCookieLocale' => true,
    // If LaravelLocalizationRedirectFilter is active and hideDefaultLocaleInURL
    // is true, the url would not have the default application language
    //
    // IMPORTANT - When hideDefaultLocaleInURL is set to true, the unlocalized root is treated as the applications default locale "app.locale".
    // Because of this language negotiation using the Accept-Language header will NEVER occur when hideDefaultLocaleInURL is true.
    //
    'hideDefaultLocaleInURL' => false,

);
