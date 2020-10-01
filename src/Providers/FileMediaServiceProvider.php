<?php

namespace GGPHP\FileMedia\Providers;

use GGPHP\FileMedia\Repositories\Contracts\FileMediaRepository;
use GGPHP\FileMedia\Repositories\Eloquent\FileMediaRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class FileMediaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/config.php', 'constants-fileMedia'
        );
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'lang-fileMedia');
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
        $this->app->bind(FileMediaRepository::class, FileMediaRepositoryEloquent::class);
    }
}
