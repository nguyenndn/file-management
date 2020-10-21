<?php

$listDisks = ['local', 's3', 'gcs', 'minio'];

$disksConfig = [
    'local' => [
        'driver' => 'local',
        'root' => storage_path('app/public'),
        'url' => env('APP_URL') . '/storage/public',
        'visibility' => 'public',
    ],
    's3' => [
        'driver' => 's3',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'bucket' => env('AWS_BUCKET'),
        'url' => env('AWS_URL'),
    ],
    'gcs' => [
        'driver' => 'gcs',
        'project_id' => env('GOOGLE_CLOUD_PROJECT_ID', 'trans-century-292502'),
        'key_file' => storage_path('GG-services/trans-century-292502-f1667700ff23.json'), // optional: /path/to/service-account.json
        'bucket' => env('GOOGLE_CLOUD_STORAGE_BUCKET', 'library-media'),
        'path_prefix' => env('GOOGLE_CLOUD_STORAGE_PATH_PREFIX', null), // optional: /default/path/to/apply/in/bucket
        'storage_api_uri' => env('GOOGLE_CLOUD_STORAGE_API_URI', null), // see: Public URLs below
        'visibility' => 'public',
    ],
    'minio' => [
        'driver' => 'minio',
        'key' => env('MINIO_KEY', 'minio'),
        'secret' => env('MINIO_SECRET', 'minio123'),
        'region' => '',
        'bucket' => env('MINIO_BUCKET', 'media'),
        'endpoint' => env('MINIO_ENDPOINT', 'http://minio:9000'),
        'use_path_style_endpoint' => true,
    ],
];

$configs = config('filesystems');
dd($configs['disks']);
foreach ($listDisks as $disk) {
    if (array_key_exists($disk, $configs['disks'])) {
        unset($disksConfig[$disk]);
    }
}

return [
    'HTTP_STATUS_CODE' => [
        'NOT_FOUND'            => 404,
        'BAD_REQUEST'          => 400,
        'SERVER_ERROR'         => 500,
        'METHOD_NOT_ALLOWED'   => 405,
        'UNAUTHORIZED'         => 401,
        'PERMISSION_DENIED'    => 403,
        'UNPROCESSABLE_ENTITY' => 422,
        'NOT_ACCEPTABLE'       => 406,
        'SUCCESS'              => 200,
    ],

    'disks' => $disksConfig,

    'list_disk' => $listDisks,

    'name_generator' => env('NAME_GENERATE', false),

    /*
     * The disk on which to store added files and derived images by default. Choose
     * one or more of the disks you've configured in config/filesystems.php.
     */
    'disk_name' => env('STORAGE_DISK', 'local'),

    /*
     * The maximum file size of an item in bytes.
     * Adding a larger file will result in an exception.
     */
    'max_file_size' => 1024 * 1024 * 10,

    /*
     * The media library will try to optimize all converted images by removing
     * metadata and applying a little bit of compression. These are
     * the optimizers that will be used by default.
     */
    'image_optimizers' => [
        Spatie\ImageOptimizer\Optimizers\Jpegoptim::class => [
            '--strip-all', // this strips out all text information such as comments and EXIF data
            '--all-progressive', // this will make sure the resulting image is a progressive one
        ],
        Spatie\ImageOptimizer\Optimizers\Pngquant::class => [
            '--force', // required parameter for this package
        ],
        Spatie\ImageOptimizer\Optimizers\Optipng::class => [
            '-i0', // this will result in a non-interlaced, progressive scanned image
            '-o2', // this set the optimization level to two (multiple IDAT compression trials)
            '-quiet', // required parameter for this package
        ],
        Spatie\ImageOptimizer\Optimizers\Svgo::class => [
            '--disable=cleanupIDs', // disabling because it is known to cause troubles
        ],
        Spatie\ImageOptimizer\Optimizers\Gifsicle::class => [
            '-b', // required parameter for this package
            '-O3', // this produces the slowest but best results
        ],
    ],

    /*
     * These generators will be used to create an image of media files.
     */
    'image_generators' => [
        Spatie\MediaLibrary\Conversions\ImageGenerators\Image::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Webp::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Pdf::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Svg::class,
        Spatie\MediaLibrary\Conversions\ImageGenerators\Video::class,
    ],

    /*
     * The engine that should perform the image conversions.
     * Should be either `gd` or `imagick`.
     */
    'image_driver' => env('IMAGE_DRIVER', 'gd'),

    /*
     * FFMPEG & FFProbe binaries paths, only used if you try to generate video
     * thumbnails and have installed the php-ffmpeg/php-ffmpeg composer
     * dependency.
     */
    'ffmpeg_path' => env('FFMPEG_PATH', '/usr/bin/ffmpeg'),
    'ffprobe_path' => env('FFPROBE_PATH', '/usr/bin/ffprobe'),

    /*
     * Here you can override the class names of the jobs used by this package. Make sure
     * your custom jobs extend the ones provided by the package.
     */
    'jobs' => [
        'perform_conversions' => \Spatie\MediaLibrary\Conversions\Jobs\PerformConversionsJob::class,
        'generate_responsive_images' => \Spatie\MediaLibrary\ResponsiveImages\Jobs\GenerateResponsiveImagesJob::class,
    ],
];
