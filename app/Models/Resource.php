<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\StoresMediaPaths;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    use HasTranslations, StoresMediaPaths;

    protected $fillable = ['icon', 'color', 'sort_order', 'file_url'];

    protected array $mediaPathAttributes = ['file_url'];

    public function translations(): HasMany
    {
        return $this->hasMany(ResourceTranslation::class);
    }
}
