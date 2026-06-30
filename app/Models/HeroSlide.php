<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\StoresMediaPaths;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HeroSlide extends Model
{
    use HasTranslations, StoresMediaPaths;

    protected $fillable = ['sort_order', 'image_url', 'gradient', 'is_active'];

    protected array $mediaPathAttributes = ['image_url'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(HeroSlideTranslation::class);
    }
}
