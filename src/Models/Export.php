<?php

namespace EUR\RSM\DatabaseViewExport\Models;

use Illuminate\Database\Eloquent\Model;

final class Export extends Model
{
    /** @var string */
    protected $table = 'database_view_exports';

    /** @var bool */
    public $incrementing = false;

    /** @var bool */
    public $timestamps = false;

    /** @var string */
    protected $primaryKey = 'slug';

    /** @var string */
    protected $keyType = 'string';
}
