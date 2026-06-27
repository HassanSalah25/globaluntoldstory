<?php

namespace App\Enums;

enum Locale: string
{
    case English = 'en';
    case Arabic = 'ar';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function resolve(?string $locale): self
    {
        return match ($locale) {
            'ar' => self::Arabic,
            default => self::English,
        };
    }

    public function direction(): string
    {
        return $this === self::Arabic ? 'rtl' : 'ltr';
    }
}
