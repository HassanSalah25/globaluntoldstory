<?php

namespace App\Enums;

enum Locale: string
{
    case English = 'en';
    case Arabic = 'ar';
    case German = 'de';
    case Spanish = 'es';
    case French = 'fr';
    case Italian = 'it';
    case Portuguese = 'pt';
    case Turkish = 'tr';
    case Russian = 'ru';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function resolve(?string $locale): self
    {
        return match ($locale) {
            'ar' => self::Arabic,
            'de' => self::German,
            'es' => self::Spanish,
            'fr' => self::French,
            'it' => self::Italian,
            'pt' => self::Portuguese,
            'tr' => self::Turkish,
            'ru' => self::Russian,
            default => self::English,
        };
    }

    public function direction(): string
    {
        return $this === self::Arabic ? 'rtl' : 'ltr';
    }

    public function label(): string
    {
        return match ($this) {
            self::English => 'English',
            self::Arabic => 'Arabic',
            self::German => 'German',
            self::Spanish => 'Spanish',
            self::French => 'French',
            self::Italian => 'Italian',
            self::Portuguese => 'Portuguese',
            self::Turkish => 'Turkish',
            self::Russian => 'Russian',
        };
    }

    public function nativeLabel(): string
    {
        return match ($this) {
            self::English => 'English',
            self::Arabic => 'عربي',
            self::German => 'Deutsch',
            self::Spanish => 'Español',
            self::French => 'Français',
            self::Italian => 'Italiano',
            self::Portuguese => 'Português',
            self::Turkish => 'Türkçe',
            self::Russian => 'Русский',
        };
    }
}
