<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimelineEventTranslation extends Model
{
    protected $fillable = ['timeline_event_id', 'locale', 'title', 'description'];

    public function timelineEvent(): BelongsTo
    {
        return $this->belongsTo(TimelineEvent::class);
    }
}
