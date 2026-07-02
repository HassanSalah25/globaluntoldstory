<?php

namespace App\Support;

use App\Enums\Locale;
use Illuminate\Http\Request;

class LocaleResolver
{
    public static function fromRequest(Request $request): Locale
    {
        $locale = $request->query('locale')
            ?? $request->header('Accept-Language')
            ?? $request->input('locale');

        if (is_string($locale) && str_contains($locale, ',')) {
            $locale = trim(explode(',', $locale)[0]);
        }

        if (is_string($locale) && str_contains($locale, '-')) {
            $locale = explode('-', $locale)[0];
        }

        $code = is_string($locale) ? $locale : null;

        if ($code !== null && ! in_array($code, AdminLocales::publicCodes(), true)) {
            $code = null;
        }

        return Locale::resolve($code);
    }
}
