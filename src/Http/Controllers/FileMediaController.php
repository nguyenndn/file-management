<?php

namespace GGPHP\FileMedia\Http\Controllers;

use App\Http\Controllers\Controller;
use GGPHP\FileMedia\Http\Requests\FileMediaDeleteRequest;
use GGPHP\FileMedia\Http\Requests\FileMediaUploadRequest;
use GGPHP\FileMedia\Models\FileMedia;
use GGPHP\FileMedia\Repositories\Contracts\FileMediaRepository;
use GGPHP\FileMedia\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileMediaController extends Controller
{
    use ResponseTrait;

    /**
     * @var $fileMediaRepository
     */
    protected $fileMediaRepository;
    protected $folderSave;

    /**
     * UserController constructor.
     * @param FileMediaRepository $fileMediaRepository
     */
    public function __construct(FileMediaRepository $fileMediaRepository)
    {
        $this->fileMediaRepository = $fileMediaRepository;
        $this->folderSave = env('FOLDER_SAVE', 'FileMedia');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = config('constants-absent.SEARCH_VALUES_DEFAULT.LIMIT');
        if ($request->has('limit')) {
            $limit = $request->limit;
        }

        $data = $request->all();
        $data['limit'] = $limit;
        $absents = $this->absentRepository->filterAbsent($data);

        return $this->success($absents, trans('lang-fileMedia::messages.common.getListSuccess'));
    }

    /**
     * @param FileMediaUploadRequest $request
     * @return \Illuminate\Http\Response
     */
    public function upload(FileMediaUploadRequest $request)
    {
        $filePath = $this->fileMediaRepository->uploadFile($request);
        $response = [
            'url' => $filePath,
        ];
        return $this->success($response, trans('lang-fileMedia::messages.common.getListSuccess'), ['isContainByDataString' => true]);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        $this->downloadOrDeleteFile($id, FileMedia::DELETE);
        return $this->success([], trans('lang-fileMedia::messages.common.getListSuccess'));
    }

    /**
     * Download file
     * @param Request $request
     * @param $id
     * @return bool
     */
    public function download(Request $request, $id)
    {
        return $this->downloadOrDeleteFile($id, FileMedia::DOWNLOAD);
    }

    /**
     * Check file exist in storage
     * @param $disk
     * @param $file
     * @return bool
     */
    function existFileInStorage($disk, $file)
    {
        return Storage::disk($disk)->exists(env('FOLDER_SAVE') .'/' . $file);
    }

    /**
     * @param $id
     * @param $type
     * @return mixed
     */
    function downloadOrDeleteFile($id, $type)
    {
        $object = FileMedia::findOrFail($id);
        $fileName = $object->name;
        $fileNameOriginal = $object->file_name_original;
        $diskName = config('constants-fileMedia.disk_name');
        $existFile = $this->existFileInStorage($diskName, $fileName);
        if (!$existFile) {
            $existFile = $this->existFileInStorage($diskName, $fileNameOriginal);
        }

        if ($existFile) {
            if ($type === FileMedia::DOWNLOAD) {
                return Storage::disk($diskName)->download(env('FOLDER_SAVE') .'/' . $fileName);
            }

            if ($type === FileMedia::DELETE) {
                $this->fileMediaRepository->delete($id);
                return Storage::disk($diskName)->delete(env('FOLDER_SAVE') .'/' . $fileName);
            }
        }

        return;
    }
}
