<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpertiseVideoTranslation extends Model
{
    protected $fillable = ['expertise_video_id', 'locale', 'tag', 'title'];

    public function expertiseVideo(): BelongsTo
    {
        return $this->belongsTo(ExpertiseVideo::class);
    }
}
