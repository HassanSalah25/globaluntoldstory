<?php

namespace App\Models;

use App\Traits\HasTranslations;
use App\Traits\StoresMediaPaths;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientLogo extends Model
{
    use HasTranslations, StoresMediaPaths;

    protected $fillable = ['name', 'image_url', 'sort_order', 'is_active'];

    protected array $mediaPathAttributes = ['image_url'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ClientLogoTranslation::class);
    }
}
