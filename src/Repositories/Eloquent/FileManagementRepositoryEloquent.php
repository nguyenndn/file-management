<?php

namespace GGPHP\FileManagement\Repositories\Eloquent;

use GGPHP\FileManagement\Models\FileManagement;
use GGPHP\FileManagement\Presenters\FileManagementPresenter;
use GGPHP\FileManagement\Repositories\Contracts\FileManagementRepository;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProfileInformationRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class FileManagementRepositoryEloquent extends BaseRepository implements FileManagementRepository
{
    protected $userRepositoryEloquent, $excelExporterServices;

    public function __construct(
        Application $app
    ) {
        parent::__construct($app);
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
        return FileManagement::class;
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
        return FileManagementPresenter::class;
    }

}
