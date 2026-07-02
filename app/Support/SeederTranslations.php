<?php

namespace App\Support;

use App\Models\Setting;

class SeederTranslations
{
    public static function codes(): array
    {
        return AdminLocales::codes();
    }

    /**
     * @return array<int, string>
     */
    public static function extraCodes(): array
    {
        return array_values(array_diff(self::codes(), ['en', 'ar']));
    }

    /**
     * Build a locale map with English placeholders for locales beyond en/ar.
     *
     * @param  array<string, mixed>  $en
     * @param  array<string, mixed>  $ar
     * @return array<string, array<string, mixed>>
     */
    public static function fromEnAr(array $en, array $ar): array
    {
        $map = ['en' => $en, 'ar' => $ar];

        foreach (self::extraCodes() as $locale) {
            $map[$locale] = $en;
        }

        return $map;
    }

    /**
     * @return array<string, string>
     */
    public static function scalarMap(string $en, ?string $ar = null): array
    {
        $map = ['en' => $en, 'ar' => $ar ?? $en];

        foreach (self::extraCodes() as $locale) {
            $map[$locale] = $en;
        }

        return $map;
    }

    /**
     * @param  array<string, array<string, mixed>>  $byLocale
     */
    public static function seed(object $model, array $byLocale): void
    {
        foreach ($byLocale as $locale => $attributes) {
            $model->translations()->updateOrCreate(
                ['locale' => $locale],
                $attributes
            );
        }
    }

    /**
     * @param  array<string, string|null>  $valuesByLocale
     */
    public static function seedSetting(Setting $setting, array $valuesByLocale): void
    {
        foreach ($valuesByLocale as $locale => $value) {
            if ($value === null) {
                continue;
            }

            $setting->translations()->updateOrCreate(
                ['locale' => $locale],
                ['value' => $value]
            );
        }
    }
}
