<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeatureHighlight extends Model
{
    use HasTranslations;

    protected $fillable = ['context', 'icon', 'sort_order'];

    public function translations(): HasMany
    {
        return $this->hasMany(FeatureHighlightTranslation::class);
    }
}
