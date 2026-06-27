<?php

namespace App\Services\Settings;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    public function get(string $key, string $locale): ?string
    {
        $settings = $this->loadSettings();

        if (! isset($settings[$key])) {
            return null;
        }

        return $settings[$key][$locale]
            ?? $settings[$key]['en']
            ?? null;
    }

    public function getGroup(string $group, string $locale): array
    {
        $settings = $this->loadSettings();
        $result = [];

        foreach ($settings as $key => $values) {
            if (($values['group'] ?? null) !== $group) {
                continue;
            }

            $shortKey = str_contains($key, '.') ? substr($key, strrpos($key, '.') + 1) : $key;
            $result[$shortKey] = $values[$locale] ?? $values['en'] ?? null;
        }

        return $result;
    }

    public function getCommonLabels(string $locale): array
    {
        $labels = [];
        $settings = $this->loadSettings();

        foreach ($settings as $key => $values) {
            if (($values['group'] ?? null) !== 'common') {
                continue;
            }

            $labelKey = str_replace('common.', '', $key);
            $camelKey = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', str_replace('.', '_', $labelKey)))));
            $labels[$camelKey] = $values[$locale] ?? $values['en'] ?? null;
        }

        return $labels;
    }

    public function getSiteConfig(string $locale): array
    {
        $socialJson = $this->get('site.social_links', $locale);
        $socialLinks = is_string($socialJson) ? json_decode($socialJson, true) : [];

        return [
            'name' => $this->get('site.name', $locale),
            'tagline' => $this->get('site.tagline', $locale),
            'description' => $this->get('site.description', $locale),
            'email' => $this->get('site.email', $locale),
            'phone' => $this->get('site.phone', $locale),
            'address' => $this->get('site.address', $locale),
            'workingHours' => $this->get('site.working_hours', $locale),
            'socialLinks' => is_array($socialLinks) ? $socialLinks : [],
        ];
    }

    private function loadSettings(): array
    {
        return Cache::remember('cms.settings', 3600, function () {
            $settings = Setting::query()
                ->with('translations')
                ->get();

            $mapped = [];

            foreach ($settings as $setting) {
                $mapped[$setting->key] = [
                    'group' => $setting->group,
                ];

                foreach ($setting->translations as $translation) {
                    $mapped[$setting->key][$translation->locale] = $translation->value;
                }
            }

            return $mapped;
        });
    }
}
