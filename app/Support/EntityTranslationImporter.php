<?php

namespace App\Support;

use App\Models\HeroSlide;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\ProcessStep;
use App\Models\SeoMeta;
use App\Models\Setting;

class EntityTranslationImporter
{
    public function import(string $locale, string $contentRoot): void
    {
        $home = $this->loadJson($contentRoot, "content/pages/home-page/{$locale}.json");
        $about = $this->loadJson($contentRoot, "content/pages/about-us/{$locale}.json");
        $services = $this->loadJson($contentRoot, "content/pages/services-overview/{$locale}.json");

        if ($home) {
            $this->importProcessSteps($locale, $home);
            $this->importHeroSlides($locale, $home);
            $this->importCommonSettings($locale, $home);
        }

        if ($about) {
            $this->importAboutSections($locale, $about);
        }

        if ($services) {
            $this->importServicesCommonSettings($locale, $services);
        }

        $this->importSeoMeta($locale);
    }

    private function loadJson(string $contentRoot, string $relativePath): ?array
    {
        $path = $contentRoot.'/'.ltrim(str_replace('\\', '/', $relativePath), '/');
        if (! is_file($path)) {
            return null;
        }

        return json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    }

    private function importProcessSteps(string $locale, array $home): void
    {
        $pipeline = $this->parsePipelineHeading($this->heading($home, 9));
        if ($pipeline === []) {
            return;
        }

        $steps = ProcessStep::query()->orderBy('sort_order')->get();

        foreach ($steps as $index => $step) {
            $title = $pipeline[$index] ?? null;
            if (! $title) {
                continue;
            }

            $step->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $title,
                    'description' => $step->translate('en')?->description,
                ]
            );
        }
    }

    private function importHeroSlides(string $locale, array $home): void
    {
        $slides = HeroSlide::query()->orderBy('sort_order')->get();
        if ($slides->isEmpty()) {
            return;
        }

        $first = $slides->first();
        if ($first) {
            $first->translations()->updateOrCreate(['locale' => $locale], [
                'badge' => null,
                'title' => $this->heading($home, 6) ?? $first->translate('en')?->title,
                'title_highlight' => null,
                'subtitle' => $this->heading($home, 7) ?? $first->translate('en')?->subtitle,
                'description' => $this->bodyLine($home, 8, 0) ?? $first->translate('en')?->description,
                'cta_primary_label' => $this->heading($home, 6) ?? $first->translate('en')?->cta_primary_label,
                'cta_primary_url' => '/contact',
                'cta_secondary_label' => $this->bodyLine($home, 5, 1) ?? $first->translate('en')?->cta_secondary_label,
                'cta_secondary_url' => '/portfolio',
            ]);
        }

        $second = $slides->skip(1)->first();
        if ($second) {
            $second->translations()->updateOrCreate(['locale' => $locale], [
                'badge' => null,
                'title' => $this->heading($home, 8) ?? $second->translate('en')?->title,
                'title_highlight' => null,
                'subtitle' => $this->heading($home, 11) ?? $second->translate('en')?->subtitle,
                'description' => $this->bodyLine($home, 8, 0) ?? $second->translate('en')?->description,
                'cta_primary_label' => $this->heading($home, 5) ?? $second->translate('en')?->cta_primary_label,
                'cta_primary_url' => '/contact',
                'cta_secondary_label' => $this->bodyLine($home, 5, 1) ?? $second->translate('en')?->cta_secondary_label,
                'cta_secondary_url' => '/portfolio',
            ]);
        }
    }

    private function importCommonSettings(string $locale, array $home): void
    {
        $this->upsertSetting($locale, 'common.all_services', $this->heading($home, 20));
        $this->upsertSetting($locale, 'common.explore_services', $this->heading($home, 11));
        $this->upsertSetting($locale, 'common.contact_us', $this->heading($home, 5));
        $this->upsertSetting($locale, 'common.contact_us_now', $this->bodyLine($home, 30, 0));
        $this->upsertSetting($locale, 'common.request_service', $this->heading($home, 7));
        $this->upsertSetting($locale, 'common.read_more', $this->defaultReadMore($locale));
        $this->upsertSetting($locale, 'common.submit_btn', $this->defaultSubmitBtn($locale));
        $this->upsertSetting($locale, 'common.why_us', $this->defaultWhyUs($locale));
    }

    private function importServicesCommonSettings(string $locale, array $services): void
    {
        $this->upsertSetting($locale, 'common.explore_services', $this->heading($services, 0));
        $this->upsertSetting($locale, 'sections.services_grid_badge', $this->heading($services, 0));
        $this->upsertSetting($locale, 'sections.services_grid_title', $this->bodyLine($services, 0, 0));
    }

    private function importAboutSections(string $locale, array $about): void
    {
        $page = Page::query()->where('slug', 'about')->first();
        if (! $page) {
            return;
        }

        $sections = $page->sections()->get()->keyBy('type');

        if ($story = $sections->get('story')) {
            $paragraphs = array_values(array_filter([
                $this->bodyLine($about, 2, 1),
                $this->bodyLine($about, 2, 2),
                $this->bodyLine($about, 2, 3),
                $this->bodyLine($about, 2, 4),
            ]));

            $story->translations()->updateOrCreate(['locale' => $locale], [
                'badge' => $this->defaultStoryBadge($locale),
                'title' => $this->bodyLine($about, 1, 0) ?? $this->heading($about, 1) ?? $story->translate('en')?->title,
                'content' => $paragraphs !== [] ? implode("\n\n", $paragraphs) : $story->translate('en')?->content,
                'cta_label' => $this->heading($about, 3) ?? $story->translate('en')?->cta_label,
                'cta_url' => '/contact',
            ]);
        }

        if ($mission = $sections->get('mission')) {
            $mission->translations()->updateOrCreate(['locale' => $locale], [
                'title' => $this->defaultMissionTitle($locale),
                'content' => $this->bodyLine($about, 0, 0) ?? $mission->translate('en')?->content,
            ]);
        }

        if ($vision = $sections->get('vision')) {
            $vision->translations()->updateOrCreate(['locale' => $locale], [
                'title' => $this->defaultVisionTitle($locale),
                'content' => $this->bodyLine($about, 2, 2) ?? $vision->translate('en')?->content,
            ]);
        }
    }

    private function importSeoMeta(string $locale): void
    {
        SeoMeta::query()->with('translations')->each(function (SeoMeta $seo) use ($locale) {
            $page = Page::query()->where('slug', $seo->page_slug)->first();
            $translation = $page?->translate($locale) ?? $page?->translate('en');

            if (! $translation) {
                return;
            }

            $seo->translations()->updateOrCreate(['locale' => $locale], [
                'meta_title' => $translation->title.' | The Untold Story',
                'meta_description' => $translation->subtitle ?? $translation->title,
                'og_title' => $translation->title,
                'og_description' => $translation->subtitle,
            ]);
        });
    }

    private function upsertSetting(string $locale, string $key, ?string $value): void
    {
        if ($value === null || $value === '') {
            return;
        }

        $group = str_starts_with($key, 'common.') ? 'common' : (str_starts_with($key, 'footer.') ? 'footer' : 'sections');
        $setting = Setting::query()->firstOrCreate(
            ['key' => $key],
            ['group' => $group, 'value' => null]
        );

        $setting->translations()->updateOrCreate(['locale' => $locale], ['value' => $value]);
    }

    private function heading(array $data, int $index): ?string
    {
        $heading = $data['blocks'][$index]['heading'] ?? null;

        return is_string($heading) && $heading !== '' ? trim($heading) : null;
    }

    private function bodyLine(array $data, int $blockIndex, int $lineIndex = 0): ?string
    {
        $line = $data['blocks'][$blockIndex]['body'][$lineIndex] ?? null;

        return is_string($line) && $line !== '' ? trim($line) : null;
    }

    private function parsePipelineHeading(?string $heading): array
    {
        if ($heading === null || $heading === '') {
            return [];
        }

        $parts = preg_split('/\s*[•·|]\s*/u', $heading) ?: [];

        return array_values(array_filter(array_map('trim', $parts)));
    }

    private function defaultReadMore(string $locale): string
    {
        return match ($locale) {
            'de' => 'Mehr lesen',
            'es' => 'Leer más',
            'fr' => 'En savoir plus',
            'it' => 'Leggi di più',
            'pt' => 'Ler mais',
            'tr' => 'Devamını oku',
            'ru' => 'Читать далее',
            default => 'Read More',
        };
    }

    private function defaultSubmitBtn(string $locale): string
    {
        return match ($locale) {
            'de' => 'Nachricht senden',
            'es' => 'Enviar mensaje',
            'fr' => 'Envoyer le message',
            'it' => 'Invia messaggio',
            'pt' => 'Enviar mensagem',
            'tr' => 'Mesaj gönder',
            'ru' => 'Отправить сообщение',
            default => 'Send Message',
        };
    }

    private function defaultWhyUs(string $locale): string
    {
        return match ($locale) {
            'de' => 'Warum The Untold Story?',
            'es' => '¿Por qué The Untold Story?',
            'fr' => 'Pourquoi The Untold Story ?',
            'it' => 'Perché The Untold Story?',
            'pt' => 'Por que The Untold Story?',
            'tr' => 'Neden The Untold Story?',
            'ru' => 'Почему The Untold Story?',
            default => 'Why The Untold Story?',
        };
    }

    private function defaultStoryBadge(string $locale): string
    {
        return match ($locale) {
            'de' => 'Unsere Geschichte',
            'es' => 'Nuestra historia',
            'fr' => 'Notre histoire',
            'it' => 'La nostra storia',
            'pt' => 'A nossa história',
            'tr' => 'Hikayemiz',
            'ru' => 'Наша история',
            default => 'Our Story',
        };
    }

    private function defaultMissionTitle(string $locale): string
    {
        return match ($locale) {
            'de' => 'Unsere Mission',
            'es' => 'Nuestra misión',
            'fr' => 'Notre mission',
            'it' => 'La nostra missione',
            'pt' => 'A nossa missão',
            'tr' => 'Misyonumuz',
            'ru' => 'Наша миссия',
            default => 'Our Mission',
        };
    }

    private function defaultVisionTitle(string $locale): string
    {
        return match ($locale) {
            'de' => 'Unsere Vision',
            'es' => 'Nuestra visión',
            'fr' => 'Notre vision',
            'it' => 'La nostra visione',
            'pt' => 'A nossa visão',
            'tr' => 'Vizyonumuz',
            'ru' => 'Наше видение',
            default => 'Our Vision',
        };
    }
}
