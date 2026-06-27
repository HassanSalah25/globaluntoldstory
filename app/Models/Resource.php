<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    use HasTranslations;

    protected $fillable = ['icon', 'color', 'sort_order', 'file_url'];

    public function translations(): HasMany
    {
        return $this->hasMany(ResourceTranslation::class);
    }
}
