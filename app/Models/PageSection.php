<?php

namespace App\Models;

use App\Support\MediaUrl;
use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageSection extends Model
{
    use HasTranslations;

    protected $fillable = ['page_id', 'type', 'sort_order', 'settings', 'is_active'];

    protected static function booted(): void
    {
        static::saving(function (PageSection $section) {
            if (! is_array($section->settings) || ! isset($section->settings['image'])) {
                return;
            }

            $settings = $section->settings;
            $settings['image'] = MediaUrl::toStoragePath($settings['image']);
            $section->settings = $settings;
        });
    }

    protected function casts(): array
    {
        return [
            'settings' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PageSectionTranslation::class);
    }
}
