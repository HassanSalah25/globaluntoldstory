<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcessStepTranslation extends Model
{
    protected $fillable = ['process_step_id', 'locale', 'title', 'description'];

    public function processStep(): BelongsTo
    {
        return $this->belongsTo(ProcessStep::class);
    }
}
