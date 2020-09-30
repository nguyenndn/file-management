<?php

namespace GGPHP\Absent\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use GGPHP\Absent\Http\Requests\AbsentCreateRequest;
use GGPHP\Absent\Http\Requests\AbsentUpdateRequest;
use GGPHP\Absent\Models\Absent;
use GGPHP\Absent\Models\AbsentType;
use GGPHP\Absent\Repositories\Absent\AbsentRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AbsentController extends Controller
{
    /**
     * @var $userRepository
     */
    protected $absentRepository;

    /**
     * UserController constructor.
     * @param AbsentRepository $absentRepository
     */
    public function __construct(AbsentRepository $absentRepository)
    {
        $this->absentRepository = $absentRepository;
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
     * @return Response
     */
    public function absentWithoutLeave(Request $request)
    {
        $limit = config('constants-absent.SEARCH_VALUES_DEFAULT.LIMIT');
        if ($request->has('limit')) {
            $limit = $request->limit;
        }

        $data = $request->all();
        $data['limit'] = $limit;
        $absents = $this->absentRepository->filterAbsentWithoutLeave($data);

        return $this->success($absents, trans('lang-absent::messages.common.getListSuccess'));
    }

    /**
     * Export Absent
     * @param Request $request
     * @return Response
     */
    public function export(Request $request)
    {
        $result = $this->absentRepository->export($request);

        if (is_string($result)) {
            return $this->error('Export failed', trans('lang-absent::messages.export.template-not-found'), 400);
        }

        return $result;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param AbsentCreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(AbsentCreateRequest $request)
    {
        $credentials = $request->all();
        $awolType = AbsentType::where('type', AbsentType::AWOL)->first();

        if (!empty($credentials['absent_type_id']) && $credentials['absent_type_id'] == $awolType->id) {
            $data['owner_id'] = Auth::id();
            $data['user_id'] = Auth::id();
            $data['absent_type_id'] = AbsentType::where('type', AbsentType::AWOL)->first()->id;
            $data['start_date'] = $credentials['start_date'];
            $data['end_date'] = $credentials['end_date'];
            $data['store_id'] = $credentials['store_id'];
            $data['created_at'] = Carbon::now();
            $data['updated_at'] = Carbon::now();
            Absent::insert($data);

            return $this->success([], trans('lang-absent::messages.auth.registerSuccess'), ['code' => Response::HTTP_CREATED]);
        }

        $typeQuitWork = AbsentType::find(request()->absent_type_id);
        $credentials['owner_id'] = $typeQuitWork ? Auth::id() : null;
        $absent = $this->absentRepository->create($credentials);
        return $this->success($absent, trans('lang-absent::messages.auth.registerSuccess'), ['code' => Response::HTTP_CREATED]);
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $absent = $this->absentRepository->find($id);
        return $this->success($absent, trans('lang-absent::messages.common.getInfoSuccess'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AbsentUpdateRequest $request
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function update(AbsentUpdateRequest $request, $id)
    {
        $credentials = $request->all();
        $credentials['approval_id'] = Auth::id();
        $absent = $this->absentRepository->update($credentials, $id);
        return $this->success($absent, trans('lang-absent::messages.common.modifySuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->absentRepository->delete($id);
        return $this->success([], trans('lang-absent::messages.common.deleteSuccess'), ['code' => Response::HTTP_NO_CONTENT]);
    }

    /**
     * Get Absent by user
     * @param Request $request
     * @return Response
     */
    public function absentByUser(Request $request)
    {
        $limit = config('constants.SEARCH_VALUES_DEFAULT.LIMIT');
        if ($request->has('limit')) {
            $limit = $request->limit;
        }

        $data = $request->all();
        $data['limit'] = $limit;

        $users = $this->absentRepository->getAbsent($data);
        return $this->success($users, trans('lang::messages.common.getListSuccess'));
    }

    /**
     * Export Absent by user
     * @param Request $request
     * @return Response
     */
    public function absentByUserExport(Request $request)
    {
        $result = $this->absentRepository->absentByUserExport($request);

        if (is_string($result)) {
            return $this->error('Export failed', trans('lang-absent::messages.export.template-not-found'), 400);
        }

        return $result;
    }

    /**
     * Get QuitWork by user
     * @param Request $request
     * @return Response
     */
    public function quitWorkByUser(Request $request)
    {
        $limit = config('constants.SEARCH_VALUES_DEFAULT.LIMIT');
        if ($request->has('limit')) {
            $limit = $request->limit;
        }

        $data = $request->all();
        $data['limit'] = $limit;

        $users = $this->absentRepository->getQuitWork($data);
        return $this->success($users, trans('lang::messages.common.getListSuccess'));
    }
}
