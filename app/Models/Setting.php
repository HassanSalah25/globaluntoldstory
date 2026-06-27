<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Setting extends Model
{
    use HasTranslations;

    protected $fillable = ['key', 'group', 'value'];

    protected function casts(): array
    {
        return [
            'value' => 'array',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(SettingTranslation::class);
    }
}
