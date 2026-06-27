<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageSectionTranslation extends Model
{
    protected $fillable = [
        'page_section_id', 'locale', 'title', 'subtitle', 'content',
        'cta_label', 'cta_url', 'badge',
    ];

    public function pageSection(): BelongsTo
    {
        return $this->belongsTo(PageSection::class);
    }
}
