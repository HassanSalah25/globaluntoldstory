<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkillBar extends Model
{
    use HasTranslations;

    protected $fillable = ['percent', 'color', 'sort_order'];

    public function translations(): HasMany
    {
        return $this->hasMany(SkillBarTranslation::class);
    }
}
