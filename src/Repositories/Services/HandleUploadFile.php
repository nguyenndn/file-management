<?php
namespace GGPHP\FileMedia\Repositories\Services;

use Illuminate\Support\Facades\Storage;

class HandleUploadFile
{
    public function handleUploadFile($file, $name)
    {
        $disks = config('constants-fileMedia.disk_name');

        $disk = Storage::disk($disks);
        $filePath = 'FileMedia/' . $name;
        $disk->putFileAs('FileMedia', $file, $name);
        $disk->setVisibility($filePath, 'public');
        return $disk->url($filePath);
    }

}