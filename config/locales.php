<?php

use App\Enums\Locale;

return [

    /*
    |--------------------------------------------------------------------------
    | Admin-editable locales
    |--------------------------------------------------------------------------
    |
    | All locales shown in the admin dashboard translation tabs.
    | Must match Locale enum values used by the public API.
    |
    */

    'admin' => Locale::values(),

    /*
    |--------------------------------------------------------------------------
    | Required locales
    |--------------------------------------------------------------------------
    |
    | These locales require non-empty values on create/update.
    | Other admin locales are optional but can still be saved.
    |
    */

    'required' => ['en', 'ar'],

];
