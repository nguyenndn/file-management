<?php

namespace GGPHP\FileMedia\Models;

use GGPHP\FileMedia\Presenters\FileMediaPresenter;
use GGPHP\Users\Models\User;
use Illuminate\Database\Eloquent\Model;

class FileMedia extends Model
{
    /**
     * Status Absent
     */
    const PENDING = 'PENDING';
    const APPROVED = 'APPROVED';


    protected $presenter = FileMediaPresenter::class;

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
