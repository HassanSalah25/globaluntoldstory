<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\StoresMediaPaths;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PortfolioItem extends Model
{
    use HasTranslations, StoresMediaPaths;

    protected $fillable = [
        'slug', 'category_id', 'client_name', 'image_url', 'duration', 'budget',
        'results', 'metric', 'sort_order', 'is_featured', 'is_active', 'grid_size',
    ];

    protected array $mediaPathAttributes = ['image_url'];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(PortfolioItemTranslation::class);
    }
}
