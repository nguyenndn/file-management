<?php

namespace GGPHP\Absent\Presenters;

use GGPHP\Absent\Transformers\AbsentTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class UserPresenter.
 *
 * @package namespace App\Presenters;
 */
class AbsentPresenter extends FractalPresenter
{
    /**
     * @var string
     */
    public $resourceKeyItem = 'Absent';

    /**
     * @var string
     */
    public $resourceKeyCollection = 'Absent';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new AbsentTransformer();
    }
}
