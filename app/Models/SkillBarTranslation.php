<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkillBarTranslation extends Model
{
    protected $fillable = ['skill_bar_id', 'locale', 'label'];

    public function skillBar(): BelongsTo
    {
        return $this->belongsTo(SkillBar::class);
    }
}
