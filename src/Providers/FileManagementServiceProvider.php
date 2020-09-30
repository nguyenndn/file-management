<?php

namespace GGPHP\FileManagement\Providers;

use GGPHP\FileManagement\Repositories\Contracts\FileManagementRepository;
use GGPHP\FileManagement\Repositories\Eloquent\FileManagementRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class FileManagementServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/config.php', 'constants-FileManagement'
        );
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'lang-FileManagement');
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
        $this->app->bind(FileManagementRepository::class, FileManagementRepositoryEloquent::class);
    }
}
