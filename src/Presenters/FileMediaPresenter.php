<?php

namespace GGPHP\FileMedia\Presenters;

use GGPHP\FileMedia\Transformers\FileMediaTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class UserPresenter.
 *
 * @package namespace App\Presenters;
 */
class FileMediaPresenter extends FractalPresenter
{
    /**
     * @var string
     */
    public $resourceKeyItem = 'File Media';

    /**
     * @var string
     */
    public $resourceKeyCollection = 'File Media';

    /**
     * Transformer
     *
     * @return FileMediaTransformer
     */
    public function getTransformer()
    {
        return new FileMediaTransformer();
    }
}
