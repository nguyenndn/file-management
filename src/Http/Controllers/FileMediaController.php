<?php

namespace GGPHP\FileMedia\Http\Controllers;

use App\Http\Controllers\Controller;
use GGPHP\FileMedia\Repositories\Contracts\FileMediaRepository;
use Illuminate\Http\Request;

class FileMediaController extends Controller
{
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

        return $this->success($absents, trans('lang-absent::messages.common.getListSuccess'));
    }

    /**
     * @param Request $request
     */
    public function upload(Request $request)
    {
        dd($request->all());
    }
}
