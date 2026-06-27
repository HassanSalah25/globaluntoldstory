<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioItemTranslation extends Model
{
    protected $fillable = ['portfolio_item_id', 'locale', 'title', 'results_text', 'metric'];

    public function portfolioItem(): BelongsTo
    {
        return $this->belongsTo(PortfolioItem::class);
    }
}
