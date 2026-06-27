<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceTranslation extends Model
{
    protected $fillable = ['resource_id', 'locale', 'title', 'type_label'];

    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
