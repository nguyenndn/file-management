<?php

namespace GGPHP\Absent\Models;

use GGPHP\Absent\Presenters\AbsentPresenter;
use GGPHP\Approval\Traits\ApprovalRegister;
use GGPHP\Core\Models\CoreModel;
use GGPHP\RolePermission\Models\Store;
use GGPHP\Users\Models\User;

class Absent extends CoreModel
{
    use ApprovalRegister;
    /**
     * Status Absent
     */
    const PENDING = 'PENDING';
    const APPROVED = 'APPROVED';
    const DECLINED = 'DECLINED';
    const CANCELED = 'CANCELED';
    const KIOSK_FILTER_DE_XUAT = 'DE_XUAT';
    const KIOSK_FILTER_TRA_CUU = 'TRA_CUU';
    const KIOSK_FILTER_OFF = 'TRA_CUU_OFF';

    protected static $processing_time;

    protected $presenter = AbsentPresenter::class;

    protected $fillable = [
        'absent_type_id', 'absent_reason_id', 'user_id', 'store_id', 'status', 'start_date', 'end_date', 'approval_id', 'owner_id',
    ];

    protected $attributes = [
        'status' => self::PENDING,
    ];

    protected $dateTimeFields = [
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function absentType()
    {
        return $this->belongsTo(AbsentType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function absentReason()
    {
        return $this->belongsTo(AbsentReason::class);
    }

    /**
     * Define relations approve
     */
    public function approve()
    {
        return $this->belongsTo(User::class, 'approval_id');
    }

    /**
     * Define relations approve
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
