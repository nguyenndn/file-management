<?php

namespace GGPHP\FileManagement\Transformers;

use GGPHP\Absent\Models\FileManagement;
use GGPHP\Core\Traits\ApprovalTransformerTrait;
use GGPHP\Core\Transformers\BaseTransformer;

/**
 * Class UserTransformer.
 *
 * @package namespace App\Transformers;
 */
class FileManagementTransformer extends BaseTransformer
{
    use ApprovalTransformerTrait;

    protected $defaultIncludes = [];
    protected $availableIncludes = [];

}
