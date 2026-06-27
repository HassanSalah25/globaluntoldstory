<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StatTranslation extends Model
{
    protected $fillable = ['stat_id', 'locale', 'label', 'sublabel'];

    public function stat(): BelongsTo
    {
        return $this->belongsTo(Stat::class);
    }
}
