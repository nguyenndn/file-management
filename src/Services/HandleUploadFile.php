<?php
namespace GGPHP\FileMedia\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Spatie\ImageOptimizer\OptimizerChainFactory;

class HandleUploadFile
{
    protected $driver;
    protected $folderStorage;
    protected $thumbnailStorage;

    public function __construct()
    {
        $this->driver = config('constants-fileMedia.disk_name');
        $this->folderStorage = config('constants-fileMedia.folder_save');
        $this->thumbnailStorage = $this->folderStorage . '/' . config('constants-fileMedia.thumbnail_storage');
    }

    public function uploadFile($file, $name)
    {
        $url = '';

        $disk = Storage::disk($this->driver);

        $filePath = $this->folderStorage . '/' . $name;

        $pathImage = public_path('storage/' . $this->thumbnailStorage . '/' .  $name);

        $disk->putFileAs($this->folderStorage, $file, $name);
        $disk->setVisibility($filePath, 'public');

        if ($this->driver === 'local') {
            $url = asset($disk->url($filePath));
//            return asset($disk->url($filePath));
        } else {
            $url = $disk->url($filePath);
//            return
        }

        if (is_bool(config('constants-fileMedia.thumbnail')) && config('constants-fileMedia.thumbnail')) {
//            $disk->putFileAs($this->thumbnailStorage, $file, $name);
            $this->createThumbnail($disk, $url, $file, $name);
        }
//
//        if (is_bool(config('constants-fileMedia.watermark')) && config('constants-fileMedia.watermark')) {
//            $this->insertWatermark($pathImage);
//        }

//        if (is_bool(config('constants-fileMedia.optimize_image')) && config('constants-fileMedia.optimize_image')) {
//            $this->optimizeImage($name);
//        }

//        $disk->putFileAs($this->folderStorage, $file, $name);
//        $disk->setVisibility($filePath, 'public');
//
//        if ($this->driver === 'local') {
//            return asset($disk->url($filePath));
//        } else {
//            return $disk->url($filePath);
//        }
        return $url;
    }

    public function createThumbnail($disk, $url, $file, $name)
    {
//        $thumbnailpath = public_path('storage/' . $this->thumbnailStorage . '/' .  $name);
//        $thumbnailpath = env('AWS_URL') . '/' . $this->thumbnailStorage . '/' .  $name;
        $thumbnailpath = $this->thumbnailStorage . '/' . $name;

//        $disk->putFileAs($this->folderStorage, $file, $name);

        $size = explode(',', config('constants-fileMedia.thumbnail_size'));
        $width = $size[0];
        $height = $size[1];

        $img = Image::make($url)->resize($width, $height, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        $img = $img->stream();
        $disk->put($thumbnailpath, $img->__toString());
        $disk->setVisibility($thumbnailpath, 'public');
//        $disk->putFileAs($thumbnailpath, $img, $name);

//        $img->save($thumbnailpath, $img->__toString());
    }

    public function insertWatermark($pathImage)
    {
        $img = Image::make($pathImage);
        $text = config('constants-fileMedia.watermark_test');

        $img->text($text, 200 , 170, function($data){
            $data->size(50);
            $data->color('000000');
            $data->align('center');
            $data->valign('bottom');
        });
//        $img->crop(100, 100, 0, 0);
        $img->rotate(-90);
        $img->save($pathImage);
    }

    public function optimizeImage($name)
    {
        $path = public_path('storage/' . $this->folderStorage . '/' .  $name);
        $img = Image::make($path);
        $img->save($path, 10);
    }
}
