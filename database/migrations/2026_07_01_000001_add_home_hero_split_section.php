<?php

use App\Models\Page;
use App\Models\PageSection;
use App\Support\MediaUrl;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $home = Page::query()->where('slug', 'home')->first();

        if (! $home) {
            return;
        }

        $exists = PageSection::query()
            ->where('page_id', $home->id)
            ->where('type', 'hero_split')
            ->exists();

        if ($exists) {
            return;
        }

        $firstSlide = \App\Models\HeroSlide::query()
            ->orderBy('sort_order')
            ->with('translations')
            ->first();

        $servicesIntro = PageSection::query()
            ->where('page_id', $home->id)
            ->where('type', 'services_intro')
            ->first();

        $pipeline = $servicesIntro?->settings['production_pipeline'] ?? [
            'Planning', 'Filming', 'Live', 'Post & final delivery', 'Localization',
        ];

        $en = $firstSlide?->translations->firstWhere('locale', 'en');
        $ar = $firstSlide?->translations->firstWhere('locale', 'ar');

        $section = PageSection::query()->create([
            'page_id' => $home->id,
            'type' => 'hero_split',
            'sort_order' => 0,
            'settings' => [
                'image' => MediaUrl::toStoragePath($firstSlide?->image_url) ?? 'frontend/on-ground-production-services-egypt-giza-pyramids.webp',
                'production_pipeline' => $pipeline,
                'headline_suffix_en' => 'production services in Egypt',
                'headline_suffix_ar' => 'خدمات الإنتاج في مصر',
                'cta_secondary_label_en' => $en?->cta_secondary_label ?? 'Our Work',
                'cta_secondary_label_ar' => $ar?->cta_secondary_label ?? 'أعمالنا',
                'cta_secondary_url' => $en?->cta_secondary_url ?? '/portfolio',
            ],
            'is_active' => true,
        ]);

        $section->translations()->create([
            'locale' => 'en',
            'badge' => $en?->badge ?? 'On-Ground Production Services in Egypt',
            'title' => 'The Untold Story delivers',
            'subtitle' => 'on-ground',
            'content' => $en?->description,
            'cta_label' => $en?->cta_primary_label,
            'cta_url' => $en?->cta_primary_url,
        ]);

        $section->translations()->create([
            'locale' => 'ar',
            'badge' => $ar?->badge ?? 'خدمات الإنتاج الميداني في مصر',
            'title' => 'تقدّم The Untold Story',
            'subtitle' => 'إنتاجاً ميدانياً',
            'content' => $ar?->description,
            'cta_label' => $ar?->cta_primary_label,
            'cta_url' => $ar?->cta_primary_url,
        ]);
    }

    public function down(): void
    {
        $home = Page::query()->where('slug', 'home')->first();

        if (! $home) {
            return;
        }

        PageSection::query()
            ->where('page_id', $home->id)
            ->where('type', 'hero_split')
            ->delete();
    }
};
