<?php

namespace Database\Seeders;

use App\Models\BlogPouse App\Models\Category;
use App\Models\Page;
use App\Models\PortfolioItem;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use RuntimeException;

class MultilingualContentSeeder extends Seeder
{
    private const TARGET_LOCALES = ['de', 'es', 'fr', 'it', 'pt', 'tr', 'ru'];

    private const PAGE_SLUG_MAP = [
        'home-page' => 'home',
        'about-us' => 'about',
        'contact-page' => 'contact',
        'services-overview' => 'services',
    ];

    private const SERVICE_SLUG_MAP = [
        'commercial-advertising-production' => 'commercial',
        'corporate-industrial-content' => 'corporate',
        'documentary-production' => 'documentary',
        'event-coverage-live-production' => 'events',
        'marketing-solutions-performance' => 'marketing',
        'motion-cgi-ai-powered-visuals' => 'motion-cgi',
        'multilingual-dubbing-voice-over-localization' => 'dubbing',
        'on-ground-production-services-egypt' => 'on-ground-egypt',
        'original-ip-development' => 'original-ip',
        'photography' => 'photography',
        'podcast-production' => 'podcast',
        'post-production-mastering' => 'post-production',
        'tv-shows-production' => 'tv-broadcast',
    ];

    private const PORTFOLIO_CATEGORY_MAP = [
        'commercial-advertising' => 'video',
        'documentary' => 'video',
        'industry' => 'video',
        'tv-show-live' => 'video',
    ];

    private string $contentRoot;

    public function run(): void
    {
        $this->contentRoot = $this->resolveContentRoot();
        $manifestPath = $this->contentRoot.'/combined/manifest.json';
        if (! is_file($manifestPath)) {
            throw new RuntimeException("Structured content manifest not found at: {$manifestPath}");
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true, 512, JSON_THROW_ON_ERROR);
        $this->command?->info('Ensuring English base records exist for articles and portfolio items…');
        $this->seedEnglishBaseRecords($manifest);

        $entries = array_filter($manifest, fn (array $entry) => in_array($entry['locale'], self::TARGET_LOCALES, true));
        $this->command?->info('Importing '.count($entries).' multilingual translation files…');

        foreach ($entries as $entry) {
            $this->importEntry($entry);
        }

        $this->command?->info('Multilingual content import complete.');
    }

    private function resolveContentRoot(): string
    {
        foreach (array_filter([
            env('STRUCTURED_CONTENT_PATH'),
            database_path('structured_content'),
        ]) as $path) {
            if (is_dir($path.'/combined') && is_dir($path.'/content')) {
                return rtrim(str_replace('\\', '/', $path), '/');
            }
        }

        throw new RuntimeException(
            'Structured content directory not found. Place files in database/structured_content or set STRUCTURED_CONTENT_PATH.'
        );
    }

    private function seedEnglishBaseRecords(array $manifest): void
    {
        foreach ($manifest as $entry) {
            if ($entry['locale'] !== 'en') {
                continue;
            }
            if ($entry['category'] === 'articles') {
                $this->ensureBlogPostFromEnglish($entry);
            }
            if ($entry['category'] === 'portfolios' && $entry['slug'] !== 'our-portfolio') {
                $this->ensurePortfolioItemsFromEnglish($entry);
            }
        }
    }

    private function importEntry(array $entry): void
    {
        $data = $this->loadContentFile($entry['file']);
        match ($entry['category']) {
            'pages' => $this->seedPageTranslation($entry['slug'], $entry['locale'], $data),
            'services' => $this->seedServiceTranslation($entry['slug'], $entry['locale'], $data),
            'articles' => $this->seedArticleTranslation($entry['slug'], $entry['locale'], $data),
            'portfolios' => $this->seedPortfolioTranslations($entry['slug'], $entry['locale'], $data),
            default => null,
        };
    }

    private function loadContentFile(string $relativePath): array
    {
        $path = $this->contentRoot.'/'.ltrim(str_replace('\\', '/', $relativePath), '/');
        if (! is_file($path)) {
            throw new RuntimeException("Content file not found: {$path}");
        }

        return json_decode((string) file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
    }

    private function seedPageTranslation(string $sourceSlug, string $locale, array $data): void
    {
        $pageSlug = self::PAGE_SLUG_MAP[$sourceSlug] ?? null;
        if (! $pageSlug || ! ($page = Page::query()->where('slug', $pageSlug)->first())) {
            $this->command?->warn("Page not found for source slug: {$sourceSlug}");

            return;
        }

        $page->translations()->updateOrCreate(['locale' => $locale], $this->extractPageFields($sourceSlug, $data));
    }

    private function extractPageFields(string $sourceSlug, array $data): array
    {
        $blocks = $data['blocks'] ?? [];

        return match ($sourceSlug) {
            'home-page' => [
                'title' => 'The Untold Story',
                'subtitle' => $this->findBlockBody($blocks, 'The Untold Story delivers') ?? $this->findBlockBody($blocks, 'Film') ?? $this->firstBodyLine($blocks),
                'badge' => null,
            ],
            'about-us' => [
                'title' => $this->firstBodyLine($blocks) ?? 'About Us',
                'subtitle' => $this->findBlockBody($blocks, 'offices') ?? $this->findBlockBody($blocks, 'MENA') ?? $this->secondBodyLine($blocks),
                'badge' => $this->findHeading($blocks, 'About') ?? 'About Us',
            ],
            'services-overview' => [
                'title' => $this->findHeading($blocks, 'Services') ?? 'Services',
                'subtitle' => $this->findBlockBody($blocks, 'Services') ?? $this->firstBodyLine($blocks),
                'badge' => $this->findHeading($blocks, 'Services'),
            ],
            'contact-page' => [
                'title' => $this->findHeading($blocks, 'Help') ?? $this->findHeading($blocks, 'Contact') ?? 'Contact Us',
                'subtitle' => $this->findHeading($blocks, 'Office') ?? $this->firstBodyLine($blocks),
                'badge' => $this->findHeading($blocks, 'Contact') ?? 'Contact Us',
            ],
            default => [
                'title' => $this->firstHeading($blocks) ?? Str::headline(str_replace('-', ' ', $sourceSlug)),
                'subtitle' => $this->firstBodyLine($blocks),
                'badge' => null,
            ],
        };
    }

    private function seedServiceTranslation(string $sourceSlug, string $locale, array $data): void
    {
        $serviceSlug = self::SERVICE_SLUG_MAP[$sourceSlug] ?? null;
        if (! $serviceSlug || ! ($service = Service::query()->where('slug', $serviceSlug)->first())) {
            $this->command?->warn("Service not found for source slug: {$sourceSlug}");

            return;
        }

        $intro = $data['intro'] ?? [];
        $sections = $data['sections'] ?? [];
        $service->translations()->updateOrCreate(['locale' => $locale], [
            'title' => $data['title'] ?? $service->translate('en')?->title ?? Str::headline($serviceSlug),
            'short_desc' => $data['subtitle'] ?? $this->firstParagraph($intro),
            'full_desc' => $this->sectionsToHtml($intro, $sections),
            'price' => $service->translate('en')?->price,
        ]);
    }

    private function ensureBlogPostFromEnglish(array $entry): void
    {
        $this->upsertBlogPost('en', $entry['slug'], $this->loadContentFile($entry['file']));
    }

    private function seedArticleTranslation(string $sourceSlug, string $locale, array $data): void
    {
        $this->upsertBlogPost($locale, $sourceSlug, $data);
    }

    private function upsertBlogPost(string $locale, string $slug, array $data): void
    {
        $sections = $data['sections'] ?? [];
        $rawTitle = trim((string) ($data['title'] ?? ''));
        $title = $this->resolveArticleTitle($slug, $rawTitle, $sections);
        $body = $this->sectionsToHtml([], $sections);

        if ($rawTitle !== '' && $rawTitle !== $title && ! str_contains($body, e($rawTitle))) {
            $body = '<p>'.e($rawTitle).'</p>'.$body;
        }

        $post = BlogPost::query()->firstOrCreate(['slug' => $slug], [
            'category_id' => Category::query()->where('type', 'blog')->orderBy('sort_order')->value('id'),
            'author_name' => 'The Untold Story',
            'author_image_url' => null,
            'featured_image_url' => null,
            'published_at' => Carbon::parse('2026-06-01'),
            'read_time_minutes' => max(3, (int) ceil(str_word_count(strip_tags($body)) / 200)),
            'is_featured' => false,
            'is_published' => true,
            'sort_order' => BlogPost::query()->count() + 1,
        ]);

        $post->translations()->updateOrCreate(['locale' => $locale], [
            'title' => $title,
            'excerpt' => $this->firstParagraphFromSections($sections) ?? Str::limit(strip_tags($body), 240),
            'body' => $body,
            'tags' => [],
        ]);
    }

    private function resolveArticleTitle(string $slug, string $rawTitle, array $sections): string
    {
        if ($rawTitle !== '' && strlen($rawTitle) <= 200 && str_word_count($rawTitle) <= 16) {
            return $rawTitle;
        }

        foreach ($sections as $section) {
            $heading = $section['heading'] ?? null;
            if (is_string($heading) && $heading !== '') {
                return Str::limit($heading, 200, '');
            }
        }

        if ($rawTitle !== '') {
            return Str::limit($rawTitle, 200, '…');
        }

        return Str::limit(Str::headline(str_replace('-', ' ', $slug)), 200, '');
    }

    private function ensurePortfolioItemsFromEnglish(array $entry): void
    {
        $this->upsertPortfolioItems('en', $entry['slug'], $this->loadContentFile($entry['file']));
    }

    private function seedPortfolioTranslations(string $portfolioSlug, string $locale, array $data): void
    {
        if ($portfolioSlug === 'our-portfolio') {
            $page = Page::query()->where('slug', 'portfolio')->first();
            $item = $data['items'][0] ?? null;
            if ($page && is_array($item)) {
                $page->translations()->updateOrCreate(['locale' => $locale], [
                    'title' => $item['title'] ?? $page->translate('en')?->title,
                    'subtitle' => implode(' • ', $item['description'] ?? []),
                    'badge' => $page->translate('en')?->badge,
                ]);
            }

            return;
        }

        $this->upsertPortfolioItems($locale, $portfolioSlug, $data);
    }

    private function upsertPortfolioItems(string $locale, string $portfolioSlug, array $data): void
    {
        $categoryId = Category::query()->where('type', 'portfolio')->where('slug', self::PORTFOLIO_CATEGORY_MAP[$portfolioSlug] ?? 'video')->value('id');
        foreach ($data['items'] ?? [] as $index => $item) {
            if (! is_array($item)) {
                continue;
            }
            $itemSlug = $portfolioSlug.'-'.$index;
            $description = $item['description'] ?? [];
            $resultsText = is_array($description) ? implode("\n", $description) : (string) $description;
            $portfolioItem = PortfolioItem::query()->firstOrCreate(['slug' => $itemSlug], [
                'category_id' => $categoryId,
                'client_name' => $item['client'] ?? 'The Untold Story',
                'image_url' => null,
                'duration' => null,
                'budget' => null,
                'results' => Str::limit($resultsText, 250, '…'),
                'metric' => null,
                'sort_order' => ($index + 1) * 10,
                'is_featured' => $index === 0,
                'is_active' => true,
                'grid_size' => $index === 0 ? 'large' : 'small',
            ]);
t = is_array($description) ? implode("\n", $description) : (string) $description            $portfolioItem = PortfolioItem::query()->where('slug', $itemSlug)->first();
            if (! $portfolioItem) {
                continue;
            }

);
rray($description) ? implode("\n", $description) : (string) $description;
            $portfolioItem = PortfolioItem::query()->where('slug', $itemSlug)->first();
            if (! $portfolioItem) {
                continue;
            }

            $portfolioItem->translations()->updateOrCreate(['locale' => $locale], [
                'title' => $item['title'] ?? Str::headline($itemSlug),
                'results_text' => $resultsText,
                'metric' => $portfolioItem->translate('en')?->metric,
            ]);
        }
    }

    private function sectionsToHtml(array $paragraphs, array $sections): string
    {
        $html = '';
        foreach ($paragraphs as $paragraph) {
            if ($paragraph !== '') {
                $html .= '<p>'.e($paragraph).'</p>';
            }
        }
        foreach ($sections as $section) {
            $heading = $section['heading'] ?? null;
            if (is_string($heading) && $heading !== '') {
                $html .= '<h2>'.e($heading).'</h2>';
            }
            foreach ($section['body'] ?? [] as $paragraph) {
                if ($paragraph !== '') {
                    $html .= '<p>'.e($paragraph).'</p>';
                }
            }
        }

        return $html;
    }

    private function firstParagraphFromSections(array $sections): ?string
    {
        foreach ($sections as $section) {
            foreach ($section['body'] ?? [] as $paragraph) {
                if ($paragraph !== '') {
                    return $paragraph;
                }
            }
        }

        return null;
    }

    private function firstParagraph(array $paragraphs): ?string
    {
        foreach ($paragraphs as $paragraph) {
            if ($paragraph !== '') {
                return $paragraph;
            }
        }

        return null;
    }

    private function firstHeading(array $blocks): ?string
    {
        foreach ($blocks as $block) {
            $heading = $block['heading'] ?? null;
            if (is_string($heading) && $heading !== '') {
                return $heading;
            }
        }

        return null;
    }

    private function firstBodyLine(array $blocks): ?string
    {
        foreach ($blocks as $block) {
            foreach ($block['body'] ?? [] as $line) {
                if ($line !== '') {
                    return $line;
                }
            }
        }

        return null;
    }

    private function secondBodyLine(array $blocks): ?string
    {
        $seen = 0;
        foreach ($blocks as $block) {
            foreach ($block['body'] ?? [] as $line) {
                if ($line === '') {
                    continue;
                }
                if (++$seen === 2) {
                    return $line;
                }
            }
        }

        return null;
    }

    private function findHeading(array $blocks, string $needle): ?string
    {
        foreach ($blocks as $block) {
            $heading = $block['heading'] ?? null;
            if (is_string($heading) && stripos($heading, $needle) !== false) {
                return $heading;
            }
        }

        return null;
    }

    private function findBlockBody(array $blocks, string $needle): ?string
    {
        foreach ($blocks as $block) {
            $heading = $block['heading'] ?? '';
            if (is_string($heading) && stripos($heading, $needle) !== false) {
                return $this->firstParagraph($block['body'] ?? []);
            }
            foreach ($block['body'] ?? [] as $line) {
                if (stripos($line, $needle) !== false) {
                    return $line;
                }
            }
        }

        return null;
    }
}
