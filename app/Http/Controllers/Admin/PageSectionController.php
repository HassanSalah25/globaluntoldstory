<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSection;
use App\Support\MediaUrl;
use Illuminate\Http\Request;

class PageSectionController extends Controller
{
    public function edit(PageSection $pageSection)
    {
        $pageSection->load(['page.translations', 'translations']);

        $translations = $pageSection->translations->keyBy('locale');
        $settings = $pageSection->settings ?? [];
        $imageValue = $this->resolveImageFieldValue($settings['image'] ?? null);

        $view = match ($pageSection->type) {
            'hero_split' => 'admin.page-sections.edit-hero-split',
            'story' => 'admin.page-sections.edit-story',
            'photography' => 'admin.page-sections.edit-photography',
            default => 'admin.page-sections.edit-default',
        };

        return view($view, compact('pageSection', 'translations', 'imageValue'));
    }

    public function update(Request $request, PageSection $pageSection)
    {
        $rules = $this->rulesForType($pageSection->type);
        $validated = $request->validate($rules);

        $settings = $pageSection->settings ?? [];

        if (array_key_exists('image', $validated)) {
            $settings['image'] = $validated['image'] ?: null;
        }

        if ($pageSection->type === 'hero_split') {
            $settings['headline_suffix_en'] = $validated['headline_suffix_en'] ?? null;
            $settings['headline_suffix_ar'] = $validated['headline_suffix_ar'] ?? null;
            $settings['cta_secondary_label_en'] = $validated['cta_secondary_label_en'] ?? null;
            $settings['cta_secondary_label_ar'] = $validated['cta_secondary_label_ar'] ?? null;
            $settings['cta_secondary_url'] = $validated['cta_secondary_url'] ?? null;
            $settings['production_pipeline'] = $this->parsePipeline($validated['production_pipeline'] ?? '');
        }

        if ($pageSection->type === 'services_intro') {
            $settings['production_pipeline'] = $this->parsePipeline($validated['production_pipeline'] ?? '');
        }

        $pageSection->update([
            'is_active' => $request->boolean('is_active'),
            'settings' => $settings,
        ]);

        foreach (['en', 'ar'] as $locale) {
            $pageSection->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'badge' => $request->input("badge_{$locale}"),
                    'title' => $request->input("title_{$locale}"),
                    'subtitle' => $request->input("subtitle_{$locale}"),
                    'content' => $request->input("content_{$locale}"),
                    'cta_label' => $request->input("cta_label_{$locale}"),
                    'cta_url' => $request->input("cta_url_{$locale}"),
                ]
            );
        }

        return redirect()
            ->route('admin.page-sections.edit', $pageSection)
            ->with('success', 'Section updated successfully.');
    }

    private function resolveImageFieldValue(?string $storedPath): string
    {
        $resolved = MediaUrl::toPublicUrl($storedPath) ?? $storedPath ?? '';

        return old('image', $resolved);
    }

    private function rulesForType(string $type): array
    {
        $base = [
            'image' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'badge_en' => 'nullable|string|max:255',
            'badge_ar' => 'nullable|string|max:255',
            'title_en' => 'nullable|string|max:500',
            'title_ar' => 'nullable|string|max:500',
            'subtitle_en' => 'nullable|string|max:500',
            'subtitle_ar' => 'nullable|string|max:500',
            'content_en' => 'nullable|string',
            'content_ar' => 'nullable|string',
            'cta_label_en' => 'nullable|string|max:255',
            'cta_label_ar' => 'nullable|string|max:255',
            'cta_url_en' => 'nullable|string|max:255',
            'cta_url_ar' => 'nullable|string|max:255',
        ];

        return match ($type) {
            'hero_split' => array_merge($base, [
                'image' => 'required|string|max:500',
                'headline_suffix_en' => 'nullable|string|max:255',
                'headline_suffix_ar' => 'nullable|string|max:255',
                'cta_secondary_label_en' => 'nullable|string|max:255',
                'cta_secondary_label_ar' => 'nullable|string|max:255',
                'cta_secondary_url' => 'nullable|string|max:255',
                'production_pipeline' => 'nullable|string',
            ]),
            'story' => array_merge($base, [
                'image' => 'required|string|max:500',
            ]),
            'photography' => array_merge($base, [
                'image' => 'required|string|max:500',
            ]),
            'services_intro' => array_merge($base, [
                'production_pipeline' => 'nullable|string',
            ]),
            default => $base,
        };
    }

    private function parsePipeline(string $value): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n|,/', $value) ?: [])));
    }
}
