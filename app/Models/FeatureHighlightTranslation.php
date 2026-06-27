<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeatureHighlightTranslation extends Model
{
    protected $fillable = ['feature_highlight_id', 'locale', 'title', 'description'];

    public function featureHighlight(): BelongsTo
    {
        return $this->belongsTo(FeatureHighlight::class);
    }
}
