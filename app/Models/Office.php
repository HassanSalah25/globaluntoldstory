<?php

namespace App\Models;

use App\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Office extends Model
{
    use HasTranslations;

    protected $fillable = [
        'flag', 'city', 'country', 'address', 'phone', 'email',
        'timezone', 'sort_order', 'is_headquarters',
    ];

    protected function casts(): array
    {
        return [
            'is_headquarters' => 'boolean',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(OfficeTranslation::class);
    }
}
