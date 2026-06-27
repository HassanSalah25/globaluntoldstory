<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Award extends Model
{
    use HasTranslations;

    protected $fillable = ['icon', 'color', 'sort_order'];

    public function translations(): HasMany
    {
        return $this->hasMany(AwardTranslation::class);
    }
}
