<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasTranslations
{
    abstract public function translations(): HasMany;

    public function translate(?string $locale = null): ?Model
    {
        $locale = $locale ?? app()->getLocale();

        if ($this->relationLoaded('translations')) {
            return $this->translations->firstWhere('locale', $locale)
                ?? $this->translations->firstWhere('locale', 'en');
        }

        return $this->translations()
            ->where('locale', $locale)
            ->first()
            ?? $this->translations()->where('locale', 'en')->first();
    }
}
