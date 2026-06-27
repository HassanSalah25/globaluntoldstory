<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValueItemTranslation extends Model
{
    protected $fillable = ['value_item_id', 'locale', 'title', 'description'];

    public function valueItem(): BelongsTo
    {
        return $this->belongsTo(ValueItem::class);
    }
}
