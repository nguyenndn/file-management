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
    'folder_save' => env('FOLDER_SAVE', 'library'),
    
    'watermark' => env('WATERMARK', 'false'),
    'watermark_test' => env('WATERMARK_TEXT', 'Watermark'),

    'thumbnail' => env('THUMBNAIL', 'true'),
    'thumbnail_size' => env('THUMBNAIL_SIZE', '30,30'),
    'thumbnail_storage' => env('THUMBNAIL_STORAGE', 'thumbnails'),
    
    'optimize_image' => env('OPTIMIZE_IMAGE', 'true'),
    'rotate_image' => env('ROTATE_IMAGE', 'true'),
    'rotate_deg' => env('ROTATE_DEG', '-90'),

];
