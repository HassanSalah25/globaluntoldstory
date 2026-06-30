<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasTranslations;

    protected $fillable = ['slug', 'icon', 'image_url', 'sort_order', 'is_active', 'is_featured'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ServiceTranslation::class);
    }

    public function leads(): HasMany
    {
        return $this->hasMany(Lead::class);
    }
}
