<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\AdminLocales;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::with('translations')
            ->get()
            ->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    public function edit($setting)
    {
        $setting = Setting::with('translations')->findOrFail($setting);

        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request, $setting)
    {
        $setting = Setting::findOrFail($setting);

        $request->validate(array_merge([
            'value' => 'nullable',
        ], AdminLocales::fieldRules([
            'value' => 'nullable|string',
        ])));

        $jsonValue = $request->value;

        if (is_string($jsonValue)) {
            $decoded = json_decode($jsonValue, true);
            $jsonValue = json_last_error() === JSON_ERROR_NONE ? $decoded : $jsonValue;
        }

        $setting->update([
            'value' => $jsonValue,
        ]);

        foreach (AdminLocales::codes() as $locale) {
            $translationValue = $request->input("value_{$locale}");

            if ($translationValue !== null) {
                $setting->translations()->updateOrCreate(
                    ['locale' => $locale],
                    ['value' => $translationValue]
                );
            }
        }

        session()->flash('success', 'Setting updated successfully.');

        return redirect()->route('admin.settings.index');
    }
}
