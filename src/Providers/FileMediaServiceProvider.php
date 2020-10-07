<?php

namespace GGPHP\FileMedia\Providers;

use App\Exceptions\Handler;
use Aws\S3\S3Client;
use Config;
use GGPHP\FileMedia\Exceptions\FileMediaException;
use GGPHP\FileMedia\Repositories\Contracts\FileMediaRepository;
use GGPHP\FileMedia\Repositories\Eloquent\FileMediaRepositoryEloquent;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Filesystem;

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
        include __DIR__ . '/../routes.php';

        // Custom exception
        $this->app->bind(
            Handler::class,
            FileMediaException::class
        );

        // Overwrite storage root
        config(['filesystems.links' => [public_path('storage') => storage_path('app')]]);

        $this->mergeConfigFrom(
            __DIR__ . '/../../config/file-media.php', 'constants-fileMedia'
        );

        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'lang-fileMedia');
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        }

        $this->mergeConfigFrom(__DIR__.'/../../config/file-media.php', 'filesystems');

        // Register minio storage
        Storage::extend('minio', function($app, $config)
        {
            $client = new S3Client([
                'credentials' => [
                    'key'    => $config["key"],
                    'secret' => $config["secret"]
                ],
                'region' => $config["region"],
                'version' => "latest",
                'bucket_endpoint' => false,
                'use_path_style_endpoint' => true,
                'endpoint' => $config["endpoint"],
            ]);
            $options = [
                'override_visibility_on_copy' => true
            ];
            return new Filesystem(new AwsS3Adapter($client, $config["bucket"], '', $options));
        });
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

    protected function mergeConfigFrom($path, $key)
    {
        if (! ($this->app instanceof CachesConfiguration && $this->app->configurationIsCached())) {
            $this->app['config']->set($key, array_merge_recursive(
                $this->app['config']->get($key, []), require $path
            ));
        }
    }
}
