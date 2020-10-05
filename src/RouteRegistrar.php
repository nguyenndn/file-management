<?php

namespace GGPHP\FileMedia;

use GGPHP\Core\RouteRegistrar as CoreRegistrar;

class RouteRegistrar extends CoreRegistrar
{
    /**
     * The namespace implementation.
     */
    protected static $namespace = '\GGPHP\FileMedia\Http\Controllers';

    /**
     * Register routes for bread.
     *
     * @return void
     */
    public function all()
    {
        $this->router->group(['middleware' => []], function ($router) {

            \Route::group(['prefix' => 'file'], function () {
                \Route::post('upload', [
                    'uses' => 'FileMediaController@upload',
                    'as' => 'file.upload',
                ]);

                \Route::delete('delete/{id}', [
                    'uses' => 'FileMediaController@delete',
                    'as' => 'file.delete',
                ]);

                \Route::delete('download/{id}', [
                    'uses' => 'FileMediaController@download',
                    'as' => 'file.download',
                ]);
            });

        });
    }
}
