<?php

namespace GGPHP\FileMedia\Transformers;

use GGPHP\Core\Traits\ApprovalTransformerTrait;
use GGPHP\Core\Transformers\BaseTransformer;

/**
 * Class UserTransformer.
 *
 * @package namespace App\Transformers;
 */
class FileMediaTransformer extends BaseTransformer
{
    use ApprovalTransformerTrait;

    protected $defaultIncludes = [];
    protected $availableIncludes = [];

}
