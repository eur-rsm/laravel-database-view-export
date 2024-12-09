<?php

namespace EUR\RSM\DatabaseViewExport\Models;

use Illuminate\Database\Eloquent\Model;

final class Export extends Model
{
    protected $table = 'database_view_exports';
    public $timestamps = false;
    protected $casts = [
        'slug' => 'string',
        'name' => 'string',
        'view_name' => 'string',
    ];
}
