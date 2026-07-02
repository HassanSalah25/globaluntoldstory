<?php

namespace App\Support;

use App\Models\Award;
use App\Models\Category;
use App\Models\Faq;
use App\Models\FeatureHighlight;
use App\Models\Office;
use App\Models\PartnerLabel;
use App\Models\Resource;
use App\Models\SkillBar;
use App\Models\TimelineEvent;
use App\Models\ValueItem;

class CmsOverlayTranslationImporter
{
    public function import(string $locale, string $contentRoot): void
    {
        $overlays = $this->loadOverlays($contentRoot);
        if ($overlays === []) {
            return;
        }

        $this->importFaqs($locale, $overlays['faqs'] ?? []);
        $this->importTimeline($locale, $overlays['timeline'] ?? []);
        $this->importSkillBars($locale, $overlays['skill_bars'] ?? []);
        $this->importValueItems($locale, $overlays['value_items'] ?? []);
        $this->importFeatureHighlights($locale, $overlays['feature_highlights'] ?? []);
        $this->importPartnerLabels($locale, $overlays['partner_labels'] ?? []);
        $this->importAwards($locale, $overlays['awards'] ?? []);
        $this->importOffices($locale, $overlays['offices'] ?? []);
        $this->importResources($locale, $overlays['resources'] ?? []);
        $this->importCategories($locale, $overlays['categories'] ?? []);
    }

    private function loadOverlays(string $contentRoot): array
    {
        $path = $contentRoot.'/translations/cms-overlays.php';
        if (! is_file($path)) {
            return [];
        }

        $data = require $path;

        return is_array($data) ? $data : [];
    }

    private function importFaqs(string $locale, array $items): void
    {
        foreach ($items as $sortOrder => $locales) {
            if (! isset($locales[$locale])) {
                continue;
            }

            $faq = Faq::query()->where('sort_order', (int) $sortOrder)->first();
            if (! $faq) {
                continue;
            }

            $faq->translations()->updateOrCreate(
                ['locale' => $locale],
                $locales[$locale]
            );
        }
    }

    private function importTimeline(string $locale, array $items): void
    {
        foreach ($items as $sortOrder => $locales) {
            if (! isset($locales[$locale])) {
                continue;
            }

            $event = TimelineEvent::query()->where('sort_order', (int) $sortOrder)->first();
            if (! $event) {
                continue;
            }

            $event->translations()->updateOrCreate(
                ['locale' => $locale],
                $locales[$locale]
            );
        }
    }

    private function importSkillBars(string $locale, array $items): void
    {
        foreach ($items as $sortOrder => $locales) {
            if (! isset($locales[$locale]['label'])) {
                continue;
            }

            $bar = SkillBar::query()->where('sort_order', (int) $sortOrder)->first();
            if (! $bar) {
                continue;
            }

            $bar->translations()->updateOrCreate(
                ['locale' => $locale],
                ['label' => $locales[$locale]['label']]
            );
        }
    }

    private function importValueItems(string $locale, array $items): void
    {
        foreach ($items as $sortOrder => $locales) {
            if (! isset($locales[$locale])) {
                continue;
            }

            $item = ValueItem::query()->where('sort_order', (int) $sortOrder)->first();
            if (! $item) {
                continue;
            }

            $item->translations()->updateOrCreate(
                ['locale' => $locale],
                $locales[$locale]
            );
        }
    }

    private function importFeatureHighlights(string $locale, array $groups): void
    {
        foreach ($groups as $context => $items) {
            foreach ($items as $sortOrder => $locales) {
                if (! isset($locales[$locale])) {
                    continue;
                }

                $item = FeatureHighlight::query()
                    ->where('context', $context)
                    ->where('sort_order', (int) $sortOrder)
                    ->first();

                if (! $item) {
                    continue;
                }

                $item->translations()->updateOrCreate(
                    ['locale' => $locale],
                    $locales[$locale]
                );
            }
        }
    }

    private function importPartnerLabels(string $locale, array $items): void
    {
        foreach ($items as $sortOrder => $locales) {
            if (! isset($locales[$locale]['label'])) {
                continue;
            }

            $label = PartnerLabel::query()->where('sort_order', (int) $sortOrder)->first();
            if (! $label) {
                continue;
            }

            $label->translations()->updateOrCreate(
                ['locale' => $locale],
                ['label' => $locales[$locale]['label']]
            );
        }
    }

    private function importAwards(string $locale, array $items): void
    {
        foreach ($items as $sortOrder => $locales) {
            if (! isset($locales[$locale])) {
                continue;
            }

            $award = Award::query()->where('sort_order', (int) $sortOrder)->first();
            if (! $award) {
                continue;
            }

            $award->translations()->updateOrCreate(
                ['locale' => $locale],
                $locales[$locale]
            );
        }
    }

    private function importOffices(string $locale, array $items): void
    {
        foreach ($items as $sortOrder => $locales) {
            if (! isset($locales[$locale])) {
                continue;
            }

            $office = Office::query()->where('sort_order', (int) $sortOrder)->first();
            if (! $office) {
                continue;
            }

            $office->translations()->updateOrCreate(
                ['locale' => $locale],
                $locales[$locale]
            );
        }
    }

    private function importResources(string $locale, array $items): void
    {
        foreach ($items as $sortOrder => $locales) {
            if (! isset($locales[$locale])) {
                continue;
            }

            $resource = Resource::query()->where('sort_order', (int) $sortOrder)->first();
            if (! $resource) {
                continue;
            }

            $resource->translations()->updateOrCreate(
                ['locale' => $locale],
                $locales[$locale]
            );
        }
    }

    private function importCategories(string $locale, array $groups): void
    {
        foreach ($groups as $type => $items) {
            foreach ($items as $slug => $locales) {
                if (! isset($locales[$locale])) {
                    continue;
                }

                $category = Category::query()->where('type', $type)->where('slug', $slug)->first();
                if (! $category) {
                    continue;
                }

                $category->translations()->updateOrCreate(
                    ['locale' => $locale],
                    $locales[$locale]
                );
            }
        }
    }
}
