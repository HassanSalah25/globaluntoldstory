<?php

namespace App\Services\Content;

use App\Models\Award;
use App\Models\Faq;
use App\Models\HeroSlide;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\PortfolioItem;
use App\Models\ProcessStep;
use App\Models\Service;
use App\Models\Stat;
use App\Models\Testimonial;
use App\Services\Media\FrontendMediaImporter;
use App\Services\Settings\SettingService;
use App\Services\Seo\SeoService;
use App\Support\MediaUrl;

class HomePageService
{
    public function __construct(
        private readonly SeoService $seo,
        private readonly ServiceCatalogService $services,
        private readonly BlogService $blog,
        private readonly SettingService $settings,
    ) {}

    public function getHome(string $locale): array
    {
        $page = Page::query()
            ->where('slug', 'home')
            ->where('is_active', true)
            ->with(['translations', 'sections.translations'])
            ->first();

        $sections = $page?->sections->keyBy('type') ?? collect();
        $heroSplit = $sections->get('hero_split');
        $servicesIntro = $sections->get('services_intro');
        $ctaBanner = $sections->get('cta_banner');
        $photographySection = $sections->get('photography');
        $heroSplitT = $heroSplit?->translate($locale);
        $servicesIntroT = $servicesIntro?->translate($locale);
        $ctaBannerT = $ctaBanner?->translate($locale);
        $photographyT = $photographySection?->translate($locale);
        $heroSettings = $heroSplit?->settings ?? [];
        $servicesIntroSettings = $servicesIntro?->settings ?? [];
        $photographySettings = $photographySection?->settings ?? [];
        $pipeline = $heroSettings["production_pipeline_{$locale}"]
            ?? $servicesIntroSettings["production_pipeline_{$locale}"]
            ?? $heroSettings['production_pipeline']
            ?? $servicesIntroSettings['production_pipeline']
            ?? [];

        $photographyService = Service::query()
            ->where('slug', 'photography')
            ->with('translations')
            ->first();

        $photographyCopy = $locale === 'ar'
            ? [
                'badge' => 'التصوير',
                'title' => 'معدات الكاميرات السينمائية الاحترافية',
                'description' => 'كاميرا ARRI Alexa Mini LF السينمائية ومعدات التصوير السينمائي المستخدمة في الإنتاجات التجارية والأفلام الوثائقية والمحتوى المرتبط بالعلامات ومشاريع الأفلام في مصر.',
                'tagline' => 'مصقول ومتناسق مع أسلوبك',
            ]
            : [
                'badge' => 'Photography',
                'title' => 'Professional Cinema Camera Equipment',
                'description' => 'Professional ARRI Alexa Mini LF cinema camera and filmmaking equipment used for commercial productions, documentaries, branded content, and film projects in Egypt.',
                'tagline' => 'Polished and aligned with your tone',
            ];

        $photographyImage = $photographySection
            ? MediaUrl::toPublicUrl($photographySettings['image'] ?? null)
            : MediaUrl::toPublicUrl(
                FrontendMediaImporter::resolvedPath('home-photography-section')
            );

        return [
            'hero_slides' => $this->getHeroSlides($locale),
            'hero' => $this->getHero($locale, $heroSplit, $heroSplitT),
            'hero_split' => $this->mapHeroSplit($heroSplit, $heroSplitT, $locale),
            'stats' => $this->getStats($locale),
            'services' => $this->services->listFeatured($locale),
            'home_data' => [
                'servicesBadge' => $servicesIntroT?->badge,
                'servicesTitle' => $servicesIntroT?->title,
                'servicesSubtext' => $servicesIntroT?->subtitle,
                'servicesCta' => $servicesIntroT?->content,
                'productionPipeline' => $pipeline,
                'portfolioTitle' => $this->section('portfolio_title', $locale, 'Projects done by The Untold Story'),
                'quoteBadge' => $this->section('quote_badge', $locale, 'Where story meets execution'),
                'quoteTitle' => $this->section('quote_title', $locale, 'Predictable budgets. Premium results.'),
                'photographyBadge' => $photographyT?->badge ?? $photographyCopy['badge'],
                'photographyTitle' => $photographyT?->title ?? $photographyCopy['title'],
                'photographyDesc' => $photographyT?->content ?? $photographyCopy['description'],
                'photographyTagline' => $photographyT?->subtitle ?? $photographyCopy['tagline'],
                'photographyImage' => $photographyImage,
                'creativeTitle' => $this->section('testimonials_badge', $locale, 'Creative Creations'),
                'creativeSubtitle' => $this->section('testimonials_title', $locale, 'Behind every Frame'),
                'blogBadge' => $this->section('blog_badge', $locale, 'News & Insights'),
                'blogTitle' => $this->section('blog_title', $locale, 'News & Insights'),
                'blogSubtext' => $this->section('blog_subtext', $locale, 'Company news and updates'),
                'ctaBannerTitle' => $ctaBannerT?->title,
                'ctaBannerText' => $ctaBannerT?->content,
            ],
            'work_showcase' => $this->getWorkShowcase($locale),
            'process' => $this->getProcess($locale),
            'testimonials' => $this->getTestimonials($locale),
            'photography' => [
                'badge' => $photographyT?->badge ?? $photographyCopy['badge'],
                'title' => $photographyT?->title ?? $photographyCopy['title'],
                'description' => $photographyT?->content ?? $photographyCopy['description'],
                'tagline' => $photographyT?->subtitle ?? $photographyCopy['tagline'],
                'image' => $photographyImage,
                'icon' => $photographyService?->icon,
            ],
            'awards' => $this->getAwards($locale),
            'blog_preview' => $this->blog->list($locale, limit: 3)['items'],
            'faq' => $this->getFaq($locale),
            'cta_banner' => [
                'title' => $ctaBannerT?->title,
                'text' => $ctaBannerT?->content,
                'cta_label' => $ctaBannerT?->cta_label,
                'cta_url' => $ctaBannerT?->cta_url,
            ],
            'seo' => $this->seo->getForPageSlug('home', $locale),
        ];
    }

    private function getHeroSlides(string $locale): array
    {
        return HeroSlide::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(function (HeroSlide $slide) use ($locale) {
                $t = $slide->translate($locale);

                return [
                    'img' => $slide->image_url,
                    'gradient' => $slide->gradient,
                    'badge' => $t?->badge,
                    'title' => $t?->title,
                    'titleHighlight' => $t?->title_highlight,
                    'subtitle' => $t?->subtitle,
                    'desc' => $t?->description,
                    'cta' => $t?->cta_primary_label,
                    'ctaHref' => $t?->cta_primary_url,
                    'ctaSecondary' => $t?->cta_secondary_label,
                    'ctaSecondaryHref' => $t?->cta_secondary_url,
                ];
            })->values()->all();
    }

    private function getHero(string $locale, ?PageSection $heroSplit, $heroSplitT): array
    {
        if ($heroSplit && $heroSplitT) {
            $settings = $heroSplit->settings ?? [];
            $suffixKey = "headline_suffix_{$locale}";
            $secondaryLabelKey = "cta_secondary_label_{$locale}";

            return [
                'badge' => $heroSplitT->badge,
                'headline1' => $heroSplitT->title,
                'headline2' => $heroSplitT->subtitle,
                'headline3' => $settings[$suffixKey] ?? null,
                'subtext' => $heroSplitT->content,
                'cta1' => [
                    'label' => $heroSplitT->cta_label,
                    'href' => $heroSplitT->cta_url,
                ],
                'cta2' => [
                    'label' => $settings[$secondaryLabelKey] ?? null,
                    'href' => $settings['cta_secondary_url'] ?? null,
                ],
                'image' => MediaUrl::toPublicUrl($settings['image'] ?? null),
                'quoteBadge' => null,
            ];
        }

        return $this->getHeroFromFirstSlide($locale);
    }

    private function mapHeroSplit(?PageSection $heroSplit, $heroSplitT, string $locale): ?array
    {
        if (! $heroSplit || ! $heroSplitT) {
            return null;
        }

        $settings = $heroSplit->settings ?? [];
        $suffixKey = "headline_suffix_{$locale}";
        $secondaryLabelKey = "cta_secondary_label_{$locale}";

        return [
            'badge' => $heroSplitT->badge,
            'headline' => [
                'start' => $heroSplitT->title,
                'highlight' => $heroSplitT->subtitle,
                'end' => $settings[$suffixKey] ?? null,
            ],
            'description' => $heroSplitT->content,
            'image' => MediaUrl::toPublicUrl($settings['image'] ?? null),
            'ctaPrimary' => [
                'label' => $heroSplitT->cta_label,
                'href' => $heroSplitT->cta_url,
            ],
            'ctaSecondary' => [
                'label' => $settings[$secondaryLabelKey] ?? null,
                'href' => $settings['cta_secondary_url'] ?? null,
            ],
            'productionPipeline' => $settings["production_pipeline_{$locale}"] ?? $settings['production_pipeline'] ?? [],
        ];
    }

    private function getHeroFromFirstSlide(string $locale): array
    {
        $slide = HeroSlide::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->first();

        $t = $slide?->translate($locale);

        return [
            'badge' => $t?->badge,
            'headline1' => $t?->title,
            'headline2' => $t?->title_highlight,
            'subtext' => $t?->description,
            'cta1' => [
                'label' => $t?->cta_primary_label,
                'href' => $t?->cta_primary_url,
            ],
            'cta2' => [
                'label' => $t?->cta_secondary_label,
                'href' => $t?->cta_secondary_url,
            ],
            'image' => $slide?->image_url,
            'quoteBadge' => $t?->subtitle,
        ];
    }

    private function getStats(string $locale): array
    {
        return Stat::query()
            ->where('context', 'home')
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(function (Stat $stat) use ($locale) {
                $t = $stat->translate($locale);
                $value = (string) $stat->numeric_value.($stat->suffix ?? '');

                return [
                    'value' => $value,
                    'label' => $t?->label,
                    'icon' => $stat->icon,
                ];
            })->values()->all();
    }

    private function getWorkShowcase(string $locale): array
    {
        $items = PortfolioItem::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->with(['translations', 'category.translations'])
            ->limit(4)
            ->get();

        if ($items->isEmpty()) {
            $items = PortfolioItem::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->with(['translations', 'category.translations'])
                ->limit(4)
                ->get();
        }

        return [
            'badge' => $this->section('work_showcase_badge', $locale, '✨ Our Work'),
            'title' => $this->section('work_showcase_title', $locale, 'Projects done by The Untold Story'),
            'subtitle' => $this->section('work_showcase_subtitle', $locale, 'Film, video, advertising, documentaries, and corporate content'),
            'viewAll' => $this->section('work_showcase_view_all', $locale, 'Our Work'),
            'projects' => $items->map(function (PortfolioItem $item) use ($locale) {
                $t = $item->translate($locale);
                $categoryT = $item->category?->translate($locale);

                return [
                    'slug' => $item->slug,
                    'img' => $item->image_url,
                    'category' => $categoryT?->name,
                    'title' => $t?->title,
                    'metric' => $t?->metric ?? $item->metric,
                    'size' => $item->grid_size,
                ];
            })->values()->all(),
        ];
    }

    private function getProcess(string $locale): array
    {
        $steps = ProcessStep::query()
            ->orderBy('sort_order')
            ->with('translations')
            ->get();

        return [
            'badge' => $this->section('process_badge', $locale, 'Production Cycle'),
            'title' => $this->section('process_title', $locale, 'The Untold Story delivers the full production cycle'),
            'steps' => $steps->map(function (ProcessStep $step) use ($locale) {
                $t = $step->translate($locale);

                return [
                    'step' => (string) $step->step_number,
                    'title' => $t?->title,
                    'desc' => $t?->description,
                ];
            })->values()->all(),
        ];
    }

    private function getTestimonials(string $locale): array
    {
        $items = Testimonial::query()
            ->where('is_active', true)
            ->where('type', 'client')
            ->orderBy('sort_order')
            ->with('translations')
            ->get();

        return [
            'badge' => $this->section('testimonials_badge', $locale, 'Creative Creations'),
            'title' => $this->section('testimonials_title', $locale, 'Behind every Frame'),
            'list' => $items->map(function (Testimonial $item) use ($locale) {
                $t = $item->translate($locale);

                return [
                    'name' => $t?->name,
                    'role' => $t?->role,
                    'text' => $t?->text,
                    'rating' => $item->rating,
                    'avatar' => $item->avatar_url,
                ];
            })->values()->all(),
        ];
    }

    private function getAwards(string $locale): array
    {
        return Award::query()
            ->orderBy('sort_order')
            ->with('translations')
            ->get()
            ->map(function (Award $award) use ($locale) {
                $t = $award->translate($locale);

                return [
                    'icon' => $award->icon,
                    'color' => $award->color,
                    'title' => $t?->title,
                    'organization' => $t?->organization,
                    'yearLabel' => $t?->year_label,
                ];
            })->values()->all();
    }

    private function getFaq(string $locale): array
    {
        $items = Faq::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->get();

        return [
            'badge' => $this->section('faq_badge', $locale, 'FAQ'),
            'title' => $this->section('faq_title', $locale, 'Answers to Your Key Questions'),
            'list' => $items->map(function (Faq $faq) use ($locale) {
                $t = $faq->translate($locale);

                return [
                    'q' => $t?->question,
                    'a' => $t?->answer,
                ];
            })->values()->all(),
        ];
    }

    private function section(string $key, string $locale, string $fallback): string
    {
        return $this->settings->get("sections.{$key}", $locale) ?? $fallback;
    }
}
