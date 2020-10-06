<?php

namespace GGPHP\FileMedia\Providers;

use GGPHP\FileMedia\Exceptions\FileMediaException;
use GGPHP\FileMedia\Repositories\Contracts\FileMediaRepository;
use GGPHP\FileMedia\Repositories\Eloquent\FileMediaRepositoryEloquent;
use Illuminate\Support\ServiceProvider;
use App\Exceptions\Handler;

class FileMediaServiceProvider extends ServiceProvider
{
    protected $namespace = 'GGPHP\FileMedia';

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(
            Handler::class,
            FileMediaException::class
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/file-media.php', 'constants-fileMedia'
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

    public function map()
    {
        $this->mapApiRoutes();
    }

    protected function mapApiRoutes()
    {
        Route::prefix('file')->middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('RouteRegistrar.php'));
    }
}
