<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\StoresMediaPaths;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogPost extends Model
{
    use HasTranslations, StoresMediaPaths;

    protected $fillable = [
        'slug', 'category_id', 'author_name', 'author_image_url', 'featured_image_url',
        'published_at', 'read_time_minutes', 'is_featured', 'is_published', 'sort_order',
    ];

    protected array $mediaPathAttributes = ['author_image_url', 'featured_image_url'];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function translations(): HasMany
    {
        return $this->hasMany(BlogPostTranslation::class);
    }
}
