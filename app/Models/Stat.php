<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stat extends Model
{
    use HasTranslations;

    protected $fillable = ['icon', 'numeric_value', 'suffix', 'color', 'bg_gradient', 'sort_order', 'context'];

    public function translations(): HasMany
    {
        return $this->hasMany(StatTranslation::class);
    }
}
