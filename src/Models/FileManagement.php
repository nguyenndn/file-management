<?php

namespace GGPHP\FileManagement\Models;

use GGPHP\Absent\Presenters\AbsentPresenter;
use GGPHP\Users\Models\User;
use Illuminate\Database\Eloquent\Model;

class FileManagement extends Model
{
    /**
     * Status Absent
     */
    const PENDING = 'PENDING';
    const APPROVED = 'APPROVED';


    protected $presenter = AbsentPresenter::class;

    protected $fillable = [
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
