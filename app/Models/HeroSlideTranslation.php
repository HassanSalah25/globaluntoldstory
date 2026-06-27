<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeroSlideTranslation extends Model
{
    protected $fillable = [
        'hero_slide_id', 'locale', 'badge', 'title', 'title_highlight', 'subtitle',
        'description', 'cta_primary_label', 'cta_primary_url', 'cta_secondary_label', 'cta_secondary_url',
    ];

    public function heroSlide(): BelongsTo
    {
        return $this->belongsTo(HeroSlide::class);
    }
}
