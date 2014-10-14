<?php

Route::group(['prefix' => 'setting', 'namespace' => 'Modules\Setting\Http\Controllers'], function()
{
	Route::get('/', 'SettingController@index');
});