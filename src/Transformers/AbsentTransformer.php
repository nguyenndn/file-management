<?php

namespace GGPHP\Absent\Transformers;

use GGPHP\Absent\Models\Absent;
use GGPHP\Core\Traits\ApprovalTransformerTrait;
use GGPHP\Core\Transformers\BaseTransformer;
use GGPHP\RolePermission\Transformers\StoreTransformer;
use GGPHP\Users\Models\User;
use GGPHP\Users\Transformers\UserTransformer;

/**
 * Class UserTransformer.
 *
 * @package namespace App\Transformers;
 */
class AbsentTransformer extends BaseTransformer
{
    use ApprovalTransformerTrait;

    protected $defaultIncludes = ['absentType'];
    protected $availableIncludes = ['user', 'store', 'absentReason', 'approve', 'owner', 'approval'];

    /**
     * Include AbsentType
     * @param Absent $absent
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Absent $absent)
    {
        if (empty($absent->user)) {
            return;
        }

        return $this->item($absent->user, new UserTransformer(), 'User');
    }

    /**
     * Include AbsentType
     * @param Absent $absent
     * @return \League\Fractal\Resource\Item
     */
    public function includeStore(Absent $absent)
    {
        if (empty($absent->store)) {
            return;
        }

        return $this->item($absent->store, new StoreTransformer(), 'Store');
    }

    /**
     * Include AbsentReason
     * @param Absent $absent
     * @return \League\Fractal\Resource\Item
     */
    public function includeAbsentReason(Absent $absent)
    {
        if (empty($absent->absentReason)) {
            return;
        }

        return $this->item($absent->absentReason, new AbsentReasonTransformer(), 'AbsentReason');
    }

    /**
     * Include AbsentType
     * @param Absent $absent
     * @return \League\Fractal\Resource\Item
     */
    public function includeAbsentType(Absent $absent)
    {
        if (empty($absent->absentType)) {
            return;
        }

        return $this->item($absent->absentType, new AbsentTypeTransformer(), 'AbsentType');
    }

    /**
     * Include User Approve
     * @param Absent $absent
     * @return \League\Fractal\Resource\Item
     */
    public function includeApprove(Absent $absent)
    {
        if (empty($absent->approve)) {
            return;
        }

        return $this->item($absent->approve, new UserTransformer(), 'Approve');
    }

    /**
     * Include Owner
     * @param Absent $absent
     * @return \League\Fractal\Resource\Item
     */
    public function includeOwner(Absent $absent)
    {
        if (empty($absent->owner)) {
            return;
        }

        return $this->item($absent->owner, new UserTransformer(), 'Owner');
    }
}
