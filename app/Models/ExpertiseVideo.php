<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\StoresMediaPaths;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpertiseVideo extends Model
{
    use HasTranslations, StoresMediaPaths;

    protected $fillable = ['video_url', 'poster_url', 'sort_order', 'is_active'];

    protected array $mediaPathAttributes = ['video_url', 'poster_url'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ExpertiseVideoTranslation::class);
    }
}
