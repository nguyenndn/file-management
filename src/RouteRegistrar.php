<?php

namespace GGPHP\FileManagement;

use GGPHP\Core\RouteRegistrar as CoreRegistrar;

class RouteRegistrar extends CoreRegistrar
{
    /**
     * The namespace implementation.
     */
    protected static $namespace = '\GGPHP\FileManagement\Http\Controllers';

    /**
     * Register routes for bread.
     *
     * @return void
     */
    public function all()
    {
        $this->forBread();
    }

    /**
     * Register the routes needed for managing clients.
     *
     * @return void
     */
    public function forBread()
    {
        $this->router->group(['middleware' => []], function ($router) {

            \Route::group(['prefix' => 'file'], function () {
                \Route::post('upload', [
                    'uses' => 'FileManagementController@upload',
                    'as' => 'file.upload',
                ]);
            });

        });
    }
}
