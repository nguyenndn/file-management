<?php

namespace GGPHP\FileMedia\Repositories\Eloquent;

use GGPHP\FileMedia\Models\FileMedia;
use GGPHP\FileMedia\Presenters\FileMediaPresenter;
use GGPHP\FileMedia\Repositories\Contracts\FileMediaRepository;
use Illuminate\Container\Container as Application;
use Illuminate\Support\Str;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;
use GGPHP\FileMedia\Services\HandleUploadFile;

/**
 * Class ProfileInformationRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class FileMediaRepositoryEloquent extends BaseRepository implements FileMediaRepository
{
    protected $userRepositoryEloquent, $excelExporterServices, $handleUploadFile;
    
    public function __construct(
        Application $app,
        HandleUploadFile $handleUploadFile
    ) {
        parent::__construct($app);
        $this->handleUploadFile = $handleUploadFile;
    }
    
    protected $fieldSearchable = [
    
    ];
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return FileMedia::class;
    }
    
    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
    /**
     * Specify Presenter class name
     *
     * @return string
     */
    public function presenter()
    {
        return FileMediaPresenter::class;
    }
    
    /**
     * @param $request
     * UploadFile
     */
    public function uploadFile($request)
    {
        $rename = $request->name;
        $files = $request->file;
        $pathFile = [];
        if(!empty($files)) {
            foreach ($files as $key => $file) {
                
                $isGenerateName = config('constants-fileMedia.name_generator');
                $disks = config('constants-fileMedia.disk_name');
                $extension  = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION );
                
                $data = [
                    'mime_type' => $files[$key]->getMimeType(),
                    'size' => $file->getSize(),
                    'file_name_original' => $file->getClientOriginalName(),
                    'name' => $isGenerateName ? $this->generateFileName($file)
                        : ($rename ? $rename[$key] . '.' . $extension : $this->generateFileName($file) ),
                    'uuid' => (string) Str::uuid(),
                    'disk' => $disks,
                    'status' => FileMedia::PUBLIC,
                ];
                
                $pathFile[$key] = $this->handleUploadFile->uploadFile($file, $data['name']);
                FileMedia::create($data);
            }
            
            return $pathFile;
        }
        
        return [];
    }
    
    /**
     * @param $file
     * @return string
     */
    public function generateFileName($file) {
        $filename   = uniqid() . "-" . time() . "-" . md5(time());
        $extension  = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION );
        $basename   = $filename . "." . $extension;
        return $basename;
    }
}
