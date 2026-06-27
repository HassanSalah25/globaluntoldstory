<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class SeoMeta extends Model
{
    use HasTranslations;

    protected $table = 'seo_meta';

    protected $fillable = [
        'seoable_type', 'seoable_id', 'page_slug',
        'canonical_url', 'robots', 'structured_data',
    ];

    protected function casts(): array
    {
        return [
            'structured_data' => 'array',
        ];
    }

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }

    public function translations(): HasMany
    {
        return $this->hasMany(SeoMetaTranslation::class);
    }
}
