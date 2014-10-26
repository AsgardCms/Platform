<?php

Route::group(
    ['prefix' => 'media', 'namespace' => 'Modules\Media\Http\Controllers'],
    function () {
        Route::get('/', 'MediaController@index');
    }
);
