<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcessStep extends Model
{
    use HasTranslations;

    protected $fillable = ['step_number', 'sort_order'];

    public function translations(): HasMany
    {
        return $this->hasMany(ProcessStepTranslation::class);
    }
}
