<?php

namespace GGPHP\FileMedia\Models;

use GGPHP\FileMedia\Presenters\FileMediaPresenter;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class FileMedia extends Model
{
    /**
     * Status Absent
     */
    const PENDING = 'PENDING';
    const APPROVED = 'APPROVED';

    protected $presenter = FileMediaPresenter::class;

    protected $fillable = [
        'uuid', 'name', 'file_name_original', 'mime_type', 'disk', 'status', 'size'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Uuid::generate();
        });
    }

}
