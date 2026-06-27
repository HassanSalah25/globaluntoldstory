<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AwardTranslation extends Model
{
    protected $fillable = ['award_id', 'locale', 'title', 'organization', 'year_label'];

    public function award(): BelongsTo
    {
        return $this->belongsTo(Award::class);
    }
}
