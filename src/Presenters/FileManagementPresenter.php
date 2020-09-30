<?php

namespace GGPHP\FileManagement\Presenters;

use GGPHP\FileManagement\Transformers\FileManagementTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class UserPresenter.
 *
 * @package namespace App\Presenters;
 */
class FileManagementPresenter extends FractalPresenter
{
    /**
     * @var string
     */
    public $resourceKeyItem = 'File Management';

    /**
     * @var string
     */
    public $resourceKeyCollection = 'File Management';

    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new FileManagementTransformer();
    }
}
