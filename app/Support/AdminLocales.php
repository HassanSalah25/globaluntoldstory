<?php

namespace App\Support;

use App\Enums\Locale;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AdminLocales
{
    public static function codes(): array
    {
        return config('locales.admin', Locale::values());
    }

    public static function publicCodes(): array
    {
        return config('locales.public', self::codes());
    }

    public static function required(): array
    {
        return config('locales.required', ['en']);
    }

    public static function validationRule(): string
    {
        return 'in:'.implode(',', self::publicCodes());
    }

    /**
     * @return array<int, array{code: string, label: string, native: string, rtl: bool, required: bool}>
     */
    public static function all(): array
    {
        return self::mapCodes(self::codes());
    }

    /**
     * @return array<int, array{code: string, label: string, native: string, rtl: bool, required: bool}>
     */
    public static function public(): array
    {
        return self::mapCodes(self::publicCodes());
    }

    /**
     * @param  array<int, string>  $codes
     * @return array<int, array{code: string, label: string, native: string, rtl: bool, required: bool}>
     */
    private static function mapCodes(array $codes): array
    {
        return collect($codes)->map(function (string $code) {
            $locale = Locale::resolve($code);

            return [
                'code'     => $code,
                'label'    => $locale->label(),
                'native'   => $locale->nativeLabel(),
                'rtl'      => $locale->direction() === 'rtl',
                'required' => in_array($code, self::required(), true),
            ];
        })->all();
    }

    /**
     * Build validation rules for translated fields across all admin locales.
     *
     * @param  array<string, string>  $fields  Field name => base rule (without required/nullable prefix)
     * @param  array<string>  $requiredFields  Field names required for required locales (en by default)
     */
    public static function fieldRules(array $fields, array $requiredFields = []): array
    {
        $rules = [];

        foreach (self::codes() as $locale) {
            $localeIsRequired = in_array($locale, self::required(), true);

            foreach ($fields as $field => $rule) {
                $key = "{$field}_{$locale}";
                $baseRule = ltrim(preg_replace('/^required\|?/', '', $rule) ?? $rule, '|');
                $fieldRequired = $localeIsRequired && in_array($field, $requiredFields, true);

                $rules[$key] = ($fieldRequired ? 'required' : 'nullable') . '|' . $baseRule;
            }
        }

        return $rules;
    }

    /**
     * Expand a list of base field rules (e.g. badge_en, title_en) to all locales.
     *
     * @param  array<string, string>  $baseRules  Keys ending in _en or _ar are expanded to all locales
     */
    public static function expandRules(array $baseRules): array
    {
        $rules = [];

        foreach ($baseRules as $key => $rule) {
            if (preg_match('/^(.+)_(en|ar)$/', $key, $matches)) {
                $field = $matches[1];
                $isRequired = str_starts_with($rule, 'required');

                foreach (self::codes() as $locale) {
                    $localeKey = "{$field}_{$locale}";
                    $baseRule = ltrim(preg_replace('/^required\|?/', '', $rule) ?? $rule, '|');
                    $localeRequired = $isRequired && in_array($locale, self::required(), true);

                    $rules[$localeKey] = ($localeRequired ? 'required' : 'nullable') . '|' . $baseRule;
                }
            } else {
                $rules[$key] = $rule;
            }
        }

        return $rules;
    }

    public static function syncTranslations(Model $model, Request $request, array $attributes): void
    {
        foreach (self::codes() as $locale) {
            $data = [];

            foreach ($attributes as $attribute) {
                $data[$attribute] = $request->input("{$attribute}_{$locale}");
            }

            $model->translations()->updateOrCreate(
                ['locale' => $locale],
                $data
            );
        }
    }

    public static function inputName(string $field, string $locale): string
    {
        return "{$field}_{$locale}";
    }

    public static function oldOrTranslation(
        string $field,
        string $locale,
        mixed $translations,
        mixed $default = ''
    ): mixed {
        $key = self::inputName($field, $locale);

        if (old($key) !== null) {
            return old($key);
        }

        if (is_array($translations)) {
            return $translations[$locale][$field] ?? $default;
        }

        $translation = $translations[$locale] ?? null;

        return $translation?->{$field} ?? $default;
    }
}
