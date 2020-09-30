<?php

namespace GGPHP\Absent;

use GGPHP\Core\RouteRegistrar as CoreRegistrar;

class RouteRegistrar extends CoreRegistrar
{
    /**
     * The namespace implementation.
     */
    protected static $namespace = '\GGPHP\Absent\Http\Controllers';

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

            \Route::post('absents/without-leave', [
                'comment' => 'Danh sách nghỉ không phép',
                'uses' => 'AbsentController@absentWithoutLeave',
                'as' => 'absents.absentWithoutLeave.index',
                'group' => 'Nghỉ phép',
            ])->middleware('permission_for_role:absents.absentWithoutLeave.index');

            \Route::get('absents', [
                'comment' => 'Danh sách nghỉ phép',
                'uses' => 'AbsentController@index',
                'as' => 'absents.index',
                'group' => 'Nghỉ phép',
            ])->middleware('permission_for_role:absents.index', 'check_permission_view:absents');

        });
    }
}
