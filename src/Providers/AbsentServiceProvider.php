<?php

namespace GGPHP\Absent\Providers;

use GGPHP\Absent\Repositories\Absent\AbsentReasonRepository;
use GGPHP\Absent\Repositories\Absent\AbsentRepository;
use GGPHP\Absent\Repositories\Absent\AbsentTypeRepository;
use GGPHP\Absent\Repositories\Absent\RevokeShiftRepository;
use GGPHP\Absent\Repositories\Eloquent\AbsentReasonRepositoryEloquent;
use GGPHP\Absent\Repositories\Eloquent\AbsentRepositoryEloquent;
use GGPHP\Absent\Repositories\Eloquent\AbsentTypeRepositoryEloquent;
use GGPHP\Absent\Repositories\Eloquent\RevokeShiftRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class AbsentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/config.php', 'constants-absent'
        );
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'lang-absent');
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AbsentRepository::class, AbsentRepositoryEloquent::class);
        $this->app->bind(AbsentTypeRepository::class, AbsentTypeRepositoryEloquent::class);
        $this->app->bind(AbsentReasonRepository::class, AbsentReasonRepositoryEloquent::class);
        $this->app->bind(RevokeShiftRepository::class, RevokeShiftRepositoryEloquent::class);
    }
}
