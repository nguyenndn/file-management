<?php
namespace GGPHP\FileMedia\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class HandleUploadFile
{
    public function uploadFile($file, $name)
    {
        $driver = config('constants-fileMedia.disk_name');

        $disk = Storage::disk($driver);

        $filePath = env('FOLDER_SAVE', 'library') . '/' . $name;
        $disk->putFileAs(env('FOLDER_SAVE', 'library'), $file, $name);
        $disk->setVisibility($filePath, 'public');

        return $disk->url($filePath);
    }

    public function createThumbnail($name)
    {
        $filePath = env('APP_URL', 'http://localhost:8000') . $name;
        $size = explode(',', config('constants-fileMedia.thumbnail_size'));

        $img = Image::make(storage_path('library/5f9149ea47d3c-1603357162-68606be56dc11854df98169853582ee8.jpg'));

        $img = Image::make($filePath)->resize($size[0], $size[1])->insert($filePath);
    }
}