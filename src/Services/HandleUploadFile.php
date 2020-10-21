<?php
namespace GGPHP\FileMedia\Services;

use Illuminate\Support\Facades\Storage;

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
}