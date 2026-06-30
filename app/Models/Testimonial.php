<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\StoresMediaPaths;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Testimonial extends Model
{
    use HasTranslations, StoresMediaPaths;

    protected $fillable = ['avatar_url', 'rating', 'sort_order', 'is_active', 'type'];

    protected array $mediaPathAttributes = ['avatar_url'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(TestimonialTranslation::class);
    }
}
