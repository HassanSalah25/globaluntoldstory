<?php

namespace App\Models;

use App\Traits\StoresMediaPaths;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeoMetaTranslation extends Model
{
    use StoresMediaPaths;

    protected $fillable = [
        'seo_meta_id', 'locale', 'meta_title', 'meta_description',
        'og_title', 'og_description', 'og_image_url',
        'twitter_title', 'twitter_description', 'twitter_image_url',
    ];

    protected array $mediaPathAttributes = ['og_image_url', 'twitter_image_url'];

    public function seoMeta(): BelongsTo
    {
        return $this->belongsTo(SeoMeta::class);
    }
}
