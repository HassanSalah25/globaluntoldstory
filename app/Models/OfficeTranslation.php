<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OfficeTranslation extends Model
{
    protected $fillable = ['office_id', 'locale', 'title', 'status'];

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }
}
