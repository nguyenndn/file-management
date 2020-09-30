<?php

namespace GGPHP\FileManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use GGPHP\FileManagement\Repositories\Contracts\FileManagementRepository;
use Illuminate\Http\Request;

class FileManagementController extends Controller
{
    /**
     * @var $userRepository
     */
    protected $fileManagementRepository;

    /**
     * UserController constructor.
     * @param FileManagementRepository $fileManagementRepository
     */
    public function __construct(FileManagementRepository $fileManagementRepository)
    {
        $this->fileManagementRepository = $fileManagementRepository;
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
