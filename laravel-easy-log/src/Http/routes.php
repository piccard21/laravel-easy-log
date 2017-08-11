<?php

Route::group(['middleware' => ['web']], function () { 
    Route::get('lel', 'Piccard\LEL\Http\DbLogController@index')->name('home');
    Route::post('lel/view/filter', 'Piccard\LEL\Http\DbLogController@filter')->name('log-filter');
    Route::get('lel/view/filter', 'Piccard\LEL\Http\DbLogController@getFilteredLogs')->name('logs-filtered');
});
