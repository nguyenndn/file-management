<?php

namespace GGPHP\FileMedia\Http\Controllers;

use App\Http\Controllers\Controller;
use GGPHP\FileMedia\Http\Requests\FileMediaDeleteRequest;
use GGPHP\FileMedia\Http\Requests\FileMediaUploadRequest;
use GGPHP\FileMedia\Models\FileMedia;
use GGPHP\FileMedia\Repositories\Contracts\FileMediaRepository;
use GGPHP\FileMedia\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FileMediaController extends Controller
{
    use ResponseTrait;

    /**
     * @var $fileMediaRepository
     */
    protected $fileMediaRepository;

    /**
     * UserController constructor.
     * @param FileMediaRepository $fileMediaRepository
     */
    public function __construct(FileMediaRepository $fileMediaRepository)
    {
        $this->fileMediaRepository = $fileMediaRepository;
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
        $file = $this->fileMediaRepository->delete($id);

        return $this->success([], trans('lang-fileMedia::messages.common.getListSuccess'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function download(Request $request, $id)
    {
        $filePath = public_path('url_file');
        return response()->download($filePath);
    }
}
