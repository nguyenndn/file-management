<?php
namespace GGPHP\FileMedia\Repositories\Services;

use Illuminate\Support\Facades\Storage;

class HandleUploadFile
{
    public function handleUploadFile($file, $name)
    {
        $disks = config('constants-fileMedia.disk_name');

        $disk = Storage::disk($disks);
        $filePath = env('FOLDER_SAVE', 'FileMedia') . '/' . $name;
        $disk->putFileAs(env('FOLDER_SAVE', 'FileMedia'), $file, $name);
        $disk->setVisibility($filePath, 'public');
        return $disk->url($filePath);
    }

}