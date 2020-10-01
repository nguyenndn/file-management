<?php

namespace GGPHP\FileMedia\Repositories\Eloquent;

use GGPHP\FileMedia\Models\FileMedia;
use GGPHP\FileMedia\Presenters\FileMediaPresenter;
use GGPHP\FileMedia\Repositories\Contracts\FileMediaRepository;
use Illuminate\Container\Container as Application;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ProfileInformationRepositoryEloquent.
 *
 * @package namespace App\Repositories\Eloquent;
 */
class FileMediaRepositoryEloquent extends BaseRepository implements FileMediaRepository
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

}
