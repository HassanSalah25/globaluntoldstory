<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeoMetaTranslation extends Model
{
    protected $fillable = [
        'seo_meta_id', 'locale', 'meta_title', 'meta_description',
        'og_title', 'og_description', 'og_image_url',
        'twitter_title', 'twitter_description', 'twitter_image_url',
    ];

    public function seoMeta(): BelongsTo
    {
        return $this->belongsTo(SeoMeta::class);
    }
}
