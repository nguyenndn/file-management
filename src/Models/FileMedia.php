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
    const PUBLIC = 'PUBLIC';
    const PRIVATE = 'PRIVATE';
    const DOWNLOAD = 'DOWNLOAD';
    const DELETE = 'DELETE';

    protected $presenter = FileMediaPresenter::class;

    protected $fillable = [
        'uuid', 'name', 'file_name_original', 'mime_type', 'disk', 'status', 'size', 'updated_at', 'created_at'
    ];

}
