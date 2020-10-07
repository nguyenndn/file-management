<?php

Route::group(['middleware' => []], function ($router) {

    \Route::group(['prefix' => 'api/file'], function () {
        \Route::post('upload', [
            'uses' => 'GGPHP\FileMedia\Http\Controllers\FileMediaController@upload',
            'as' => 'file.upload',
        ]);

        \Route::delete('delete/{id}', [
            'uses' => 'GGPHP\FileMedia\Http\Controllers\FileMediaController@delete',
            'as' => 'file.delete',
        ]);

        \Route::get('download/{id}', [
            'uses' => 'GGPHP\FileMedia\Http\Controllers\FileMediaController@download',
            'as' => 'file.download',
        ]);
    });

});